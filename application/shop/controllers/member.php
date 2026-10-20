<?php
class Member extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		# 모델 로드
		$this->load->model('sitemodel', 'site_model');
		$this->load->model('membermodel', 'member_model');
		$this->load->model('settingmodel', 'setting_model');
		$this->load->model('mongomodel', 'mongo_model');
	}

	/**
	 * HTTP URL을 HTTPS로 강제 변환
	 * OAuth redirect_uri는 항상 HTTPS여야 하므로 플랫폼 조건 없이 처리
	 *
	 * @param string $url
	 * @return string
	 */
	private function ensure_https($url)
	{
		if (strpos($url, 'http://') === 0) {
			return 'https://' . substr($url, 7);
		}
		return $url;
	}

	/**
	 * OAuth redirect_uri 프로토콜 검증 (디버깅용)
	 * GET /member/oauth_debug
	 */
	public function oauth_debug()
	{
		header('Content-Type: application/json');
		$app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
		$is_ios_safari = preg_match('/(iPhone|iPad|iPod)/i', $user_agent) === 1
			&& strpos($user_agent, 'NowJobApp_iOS') === false;

		$naver_redirect = $this->ensure_https($app_base_url) . '/member/naver_login_callback';
		$google_redirect = $this->ensure_https($app_base_url) . '/member/google_login_callback';
		$wechat_redirect = $this->ensure_https($app_base_url) . '/member/wechat_login_callback';

		echo json_encode([
			'app_base_url' => $app_base_url,
			'user_agent' => substr($user_agent, 0, 200),
			'is_ios_safari' => $is_ios_safari,
			'x_forwarded_proto' => isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? $_SERVER['HTTP_X_FORWARDED_PROTO'] : 'not set',
			'https' => isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'not set',
			'server_port' => isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 'not set',
			'http_host' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'not set',
			'naver_redirect' => $naver_redirect,
			'google_redirect' => $google_redirect,
			'wechat_redirect' => $wechat_redirect,
			'wechat_console_note' => 'WeChat 개발자 콘솔에 등록된 redirect_uri와 위 URL이 정확히 일치해야 함 (프로토콜, 도메인, 경로 모두 동일)',
		], JSON_UNESCAPED_SLASHES);
		exit;
	}

	# 로그인
	function login()
	{
		$data = [];

		$data["header_type"] = "";

		$csrf = make_token();
		$this->common->setSession("csrf-token", $csrf);

		# 로그인시 메인화면으로 이동
		if ($this->user->isLogin()) {
			go_location("/home/index");
		}

		$data["csrf"] = $csrf;
		$data["kakao_key"] = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);

		// 네이버 로그인 설정 가져오기
		$naver_setting_info = $this->setting_model->getSettingInfo(["st_group" => "네이버"]);
		$data["naver_client_id"] = isset($naver_setting_info["Client ID"]) ? $naver_setting_info["Client ID"] : "";

		$msg_code = input_get("msg_code", "");
		$data["msg_code"] = $msg_code;

		// WeChat: SNS 콜백에서 session에 저장된 unionid를 가져옴 (크로스 플랫폼 지원)
		$join_type = input_get("join_type", "");
		$join_sns = input_get("join_sns", "");
		$data["sPhoneUnionId"] = "";
		if ($join_type == "WECHAT" && $join_sns == "1") {
			$existing_sns = $this->site_model->getStepTempData("JOIN", "SNS");
			if (!empty($existing_sns["unionid"])) {
				$data["sPhoneUnionId"] = $existing_sns["unionid"];
			}
		}

		// 최근 로그인 SNS 조회 (쿠키/세션에서 읽기)
		$session_value = $this->common->getSession("last_login_type");
		$recent_login_type = $session_value !== null ? $session_value : (isset($_COOKIE["last_login_type"]) ? $_COOKIE["last_login_type"] : null);
		$data["recent_login_type"] = $recent_login_type;

		$this->layout->shop('/member/login', $data);
	}

	function apple_login_callback()
	{
		$id_token = $_POST["id_token"];
		$tokenParts = explode(".", $id_token);

		$tokenHeader = base64_decode($tokenParts[0]);
		$tokenPayload = base64_decode($tokenParts[1]);
		$jwtHeader = json_decode($tokenHeader);
		$jwtPayload = json_decode($tokenPayload);

		//본인인증및 회원이면 처리 비회원이면 회원가입 하시겠습니까 문의?

		$data = [];
		$key =  $jwtPayload->sub;
		$data["key"] = $key;

		//회원가입 여부 확인및 회원가입 페이지 이동
		//$this->layout->shop('/member/join_comp_step4',$data);

		//같은 키값 있으면 로그인처리

		$return = $this->login_process("APPLE", $key);

		if ($return["code"] == "00") {
			$this->common->scriptReplace("/");
			exit;
		} else if ($return["code"] == "90") {
			// 신규 회원: login.php로 리다이렉트 + 파라미터 (phone popup 띄우기)
			$login_url = "/member/login?join_sns=1&join_key=" . urlencode($key) . "&join_type=APPLE";
			go_location($login_url);
		} else {
			go_location("/member/login?msg_code=" . $return["code"]);
		}
	}

	function kakao_login_callback()
	{
		// callback 으로 들어온 값을 확인한다.
		$this->load->library('KakaoLogin');
		$kakao_setting = $this->setting_model->getSettingInfo(["st_group" => "카카오"]);
		$kakao_key = isset($kakao_setting["REST_API_KEY"]) ? $kakao_setting["REST_API_KEY"] : "";
		$code = $_REQUEST["code"];
		$state = $_REQUEST["state"];
		$error = $_REQUEST["error"];
		$error_description = $_REQUEST["error_description"];

		if ($error) {
			$this->common->scriptReplace("/member/login", "문제가 발생해서 정상적으로 로그인이 되지 않았습니다.(1)");
			exit();
		}

		// 카카오 OAuth state 검증
		$saved_kakao_state = $this->common->getSession("kakao-oauth-state");
		if (empty($saved_kakao_state) || $saved_kakao_state !== $state) {
			$this->common->scriptReplace("/member/login", "카카오 로그인 상태값 검증에 실패했습니다.");
			exit();
		}

		// 카카오로그인 객체 생성
		//$kakao = new KakaoLogin($kakao_key["인증키"],$this->common->getSession("csrf-token"));
		$kakao = new KakaoLogin($kakao_key, $this->common->getSession("csrf-token"));

		// 토큰 받기
		$tokenData = $kakao->getToken($code);

		// 사용자 정보 가져오기.
		if (! empty($tokenData["data"]["access_token"])) {

			$userInfoData = $kakao->getUserInfo($tokenData["data"]["access_token"]);

			// 사용자 정보 조회 실패 검증
			if (empty($userInfoData["data"]["id"])) {
				$this->common->scriptReplace("/member/login", "카카오 사용자 정보를 가져오지 못했습니다.");
				exit;
			}

			//print_r($userInfoData);
			//echo "[".$userInfoData["data"]["kakao_account"]["name"]."] "; // 사용자이름
			//echo "[".$userInfoData["data"]["kakao_account"]["email"]."] "; // 이메일
			//echo "[".$userInfoData["data"]["kakao_account"]["birthyear"]."] "; // 생년
			//echo "[".$userInfoData["data"]["kakao_account"]["birthday"]."] "; // 생일
			//echo "[".$userInfoData["data"]["kakao_account"]["gender"]."] "; // 성별 female: 여성, male: 남성
			//echo "[".$userInfoData["data"]["kakao_account"]["phone_number"]."] "; // 전화번호 : 국내 번호인 경우 +82 00-0000-0000 형식
			$return = $this->login_process("KAKAO", $userInfoData["data"]["id"]);
			//print_r($return);
			//exit; //test

			if ($return["code"] == "00") {
				$this->common->scriptReplace("/");
				exit;
			} else {
				// 회원 미존재 시: SNS 정보를 세션에 저장하고 PASS 인증 페이지로 이동
				$kakao_id = $userInfoData["data"]["id"];
				$kakao_account = $userInfoData["data"]["kakao_account"];

				// 전화번호 포맷 변환: +82 10-9002-5528 → 01090025528
				$phonenum = isset($kakao_account["phone_number"]) ? $kakao_account["phone_number"] : "";
				if ($phonenum != "") {
					$phonenum = preg_replace('/\+82\s*10-/', '010', $phonenum);
					$phonenum = preg_replace('/[-\s]/', '', $phonenum);
				}

				// 회원이 없을 때 전화번호 중복 체크
				if (!empty($phonenum)) {
					$duplicate_check = checkDuplicatePhoneForSns($phonenum);
					if ($duplicate_check["is_duplicate"]) {
						// 중복이 있으면 로그인 페이지로 리다이렉트 (쿼리파라미터로 중복 계정 정보 전달)
						$dup_url = "/member/login?dup_join_type=" . urlencode($duplicate_check["join_type"])
								. "&dup_mm_phone=" . urlencode(isset($duplicate_check["mm_phone"]) ? $duplicate_check["mm_phone"] : "");
						redirect($dup_url, 'refresh');
						exit;
					}
				}

				// 기존 SNS 데이터 확인 (앱에서 저장한 생년월일 정보 유지)
				$existing_sns = $this->site_model->getStepTempData("JOIN", "SNS");

				// SNS 정보를 세션에 저장
				$sns_info = [
					"join_type" => "KAKAO",
					"join_key" => $kakao_id,
					"username" => isset($kakao_account["name"]) && $kakao_account["name"] != "" ? $kakao_account["name"] : "kakao_" . $kakao_id,
					"email" => isset($kakao_account["email"]) ? $kakao_account["email"] : "",
					"gender" => isset($kakao_account["gender"]) ? ($kakao_account["gender"] == "male" ? "남" : ($kakao_account["gender"] == "female" ? "여" : "")) : "",
					"phonenum" => $phonenum,
					"birthyear" => isset($kakao_account["birthyear"]) ? $kakao_account["birthyear"] : (!empty($existing_sns["birthyear"]) ? $existing_sns["birthyear"] : ""),
					"birthday" => isset($kakao_account["birthday"]) ? $kakao_account["birthday"] : (!empty($existing_sns["birthday"]) ? $existing_sns["birthday"] : ""),
				];

				// 성별도 기존 데이터가 있으면 우선 사용 (앱에서 변환된 값 유지)
				if (!empty($existing_sns["gender"]) && ($existing_sns["gender"] == "남" || $existing_sns["gender"] == "여")) {
					$sns_info["gender"] = $existing_sns["gender"];
				}

				// 기존 로그 삭제
				$this->site_model->deleteSiteLog(["session_id" => session_id(), "sl_status" => "Y"]);

				// SNS 정보 로그 저장
				$this->site_model->setSiteLog(
					[
						"session_id" => session_id(),
						"sl_type" => "JOIN",
						"sl_step" => "SNS",
						"sl_txt" => json_encode($sns_info, JSON_UNESCAPED_UNICODE)
					],
					[
						"session_id" => session_id(),
						"sl_type" => "JOIN",
						"sl_step" => "SNS",
						"sl_status" => "Y"
					]
				);

				// PASS 인증 페이지로 이동 (회원가입 안내와 함께)
				$data["code"] = $return["code"];
				$data["msg"] = $return["msg"];
				$data["res_id"] = $kakao_id;
				$data["sns_info"] = $sns_info;
				$data["enc_data"] = $this->member_model->getNiceEncData();

				$this->layout->shop('/member/kakao_login_callback', $data);
			}
		} else {
			$this->common->scriptReplace("/member/login", "문제가 발생해서 정상적으로 로그인이 되지 않았습니다.(2)");
			exit;
		}
	} //kakao_login_callback

	/**
	 * 위챗 로그인 콜백 처리
	 * WeChat OAuth 리다이렉트 후 code를 이용해 로그인/회원가입 처리
	 */
	function wechat_login_callback()
	{
		$code  = isset($_GET["code"])  ? $_GET["code"]  : "";
		$state = isset($_GET["state"]) ? $_GET["state"] : "";



		if (empty($code)) {
			$this->common->scriptReplace("/member/login", "위챗 로그인에 실패하였습니다.(1)");
			exit;
		}

		// 위챗 OAuth state 검증
		$saved_wechat_state = $this->common->getSession("wechat-oauth-state");

		if (empty($saved_wechat_state) || $saved_wechat_state !== $state) {
			$this->common->scriptReplace("/member/login", "위챗 로그인 상태값 검증에 실패했습니다.");
			exit;
		}

		// 위챗 설정 조회 (login.php와 동일하게 WEB_APP_ID/WEB_APP_SECRET 사용, 없으면 APP_ID/APP_SECRET 폴백)
		$wechat_setting = $this->setting_model->getSettingInfo(["st_group" => "위챗"]);
		$app_id     = isset($wechat_setting["WEB_APP_ID"]) ? $wechat_setting["WEB_APP_ID"] : (isset($wechat_setting["APP_ID"]) ? $wechat_setting["APP_ID"] : "");
		$app_secret = isset($wechat_setting["WEB_APP_SECRET"]) ? $wechat_setting["WEB_APP_SECRET"] : (isset($wechat_setting["APP_SECRET"]) ? $wechat_setting["APP_SECRET"] : "");

		// Callback URL (iOS Safari HTTPS 강제 처리)
		$app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
		$app_base_url = $this->ensure_https($app_base_url);
		$callback_url = $app_base_url . "/member/wechat_login_callback";


		if (empty($app_id) || empty($app_secret)) {

			$this->common->scriptReplace("/member/login", "위챗 로그인 설정이 없습니다.");
			exit;
		}

		// code → access_token + openid 교환
		$token_url = "https://api.weixin.qq.com/sns/oauth2/access_token"
			. "?appid=" . $app_id
			. "&secret=" . $app_secret
			. "&code=" . $code
			. "&grant_type=authorization_code";

		// cURL 호출
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $token_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$token_response = curl_exec($ch);
		$curl_error = curl_error($ch);
		curl_close($ch);



		if (!$token_response) {
			$this->common->scriptReplace("/member/login", "위챗 인증에 실패하였습니다.(2)");
			exit;
		}

		$token_data = json_decode($token_response, true);



		if (!is_array($token_data) || isset($token_data["errcode"])) {
			$this->common->scriptReplace("/member/login", "위챗 인증에 실패하였습니다.(3)");
			exit;
		}

		$access_token = $token_data["access_token"];
		$openid       = $token_data["openid"];

		// 사용자 정보 조회
		$info_url = "https://api.weixin.qq.com/sns/userinfo"
			. "?access_token=" . $access_token
			. "&openid=" . $openid
			. "&lang=zh_CN";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $info_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$info_response = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);



		$user_info = $info_response ? json_decode($info_response, true) : null;

		// 사용자 정보 조회 실패 검증
		if (empty($user_info) || !isset($user_info["openid"])) {
			$this->common->scriptReplace("/member/login", "위챗 사용자 정보를 가져오지 못했습니다.");
			exit;
		}

		$sex_code  = ($user_info && isset($user_info["sex"]))     ? intval($user_info["sex"])    : 0;
		$unionid   = ($user_info && isset($user_info["unionid"])) ? $user_info["unionid"]        : "";
		$nickname  = ($user_info && isset($user_info["nickname"])) ? $user_info["nickname"]       : "";

		// sex 변환: 1=남, 2=여, 0=미지정
		$gender = ($sex_code == 1) ? "남" : (($sex_code == 2) ? "여" : "");

		// 로그인 처리 (WeChat unionid 폴백 지원)
		$return = $this->wechat_login_process($openid, $unionid);

		if ($return["code"] == "00") {
			$this->common->scriptReplace("/");
			exit;
		}

		// 탈퇴 회원 처리
		if ($return["code"] == "91") {
			$this->common->scriptReplace("/member/login", $return["msg"]);
			exit;
		}

		// 신규 회원 (code == "90"): 회원가입 유도
		// 통합 WeChat 계정 지원: unionid를 기본 키로 사용 (모든 플랫폼에서 동일)
		$join_key_to_use = !empty($unionid) ? $unionid : $openid;
		$sns_info = array(
			"join_type" => "WECHAT",
			"join_key"  => $join_key_to_use,
			"username"  => !empty($nickname) ? $nickname : "wechat_" . $openid,
			"email"     => "",
			"gender"    => $gender,
			"phonenum"  => "",
			"birthyear" => "",
			"birthday"  => "",
		);
		if (!empty($unionid)) {
			$sns_info["unionid"] = $unionid;
		}
		if (!empty($openid) && $openid != $join_key_to_use) {
			$sns_info["openid"] = $openid;
		}

		// DB 로그 저장
		$this->site_model->deleteSiteLog(array("session_id" => session_id(), "sl_status" => "Y"));
		$this->site_model->setSiteLog(
			array(
				"session_id" => session_id(),
				"sl_type"    => "JOIN",
				"sl_step"    => "SNS",
				"sl_txt"     => json_encode($sns_info, JSON_UNESCAPED_UNICODE)
			),
			array(
				"session_id" => session_id(),
				"sl_type"    => "JOIN",
				"sl_step"    => "SNS",
				"sl_status"  => "Y"
			)
		);

		// code "90" (신규 회원) 처리: login.php로 리다이렉트 + 파라미터 (phone popup 띄우기)
		if ($return["code"] == "90") {
			$login_url = "/member/login"
				. "?join_sns=1"
				. "&join_key=" . urlencode($openid)
				. "&join_type=WECHAT"
				. (!empty($nickname) ? "&username=" . urlencode($nickname) : "")
				. (!empty($gender) ? "&gender=" . urlencode($gender) : "")
				. (!empty($unionid) ? "&unionid=" . urlencode($unionid) : "");
			go_location($login_url);
			exit;
		}

		$data["code"]     = $return["code"];
		$data["msg"]      = $return["msg"];
		$data["res_id"]   = $openid;
		$data["sns_info"] = $sns_info;
		$data["enc_data"] = $this->member_model->getNiceEncData();

		$this->layout->shop('/member/wechat_login_callback', $data);
	} //wechat_login_callback

	function naver_login_callback()
	{
		// callback 으로 들어온 값을 확인한다.
		$code = isset($_REQUEST["code"]) ? $_REQUEST["code"] : "";
		$state = isset($_REQUEST["state"]) ? $_REQUEST["state"] : "";
		$error = isset($_REQUEST["error"]) ? $_REQUEST["error"] : "";
		$error_description = isset($_REQUEST["error_description"]) ? $_REQUEST["error_description"] : "";

		if ($error) {
			$error_msg = "문제가 발생해서 정상적으로 로그인이 되지 않았습니다.(1)";
			if (!empty($error_description)) {
				$error_msg .= " - " . $error_description;
			}
			$this->common->scriptReplace("/member/login", $error_msg);
			exit();
		}

		if (empty($code)) {
			$this->common->scriptReplace("/member/login", "네이버 로그인 인증 코드를 받지 못했습니다.");
			exit();
		}

		// 네이버 OAuth state 검증
		$saved_naver_state = $this->common->getSession("naver-oauth-state");
		if (empty($saved_naver_state) || $saved_naver_state !== $state) {
			$this->common->scriptReplace("/member/login", "네이버 로그인 상태값 검증에 실패했습니다.");
			exit();
		}

		// 네이버 로그인 설정 가져오기
		$naver_setting_info = $this->setting_model->getSettingInfo(["st_group" => "네이버"]);
		$naver_client_id = isset($naver_setting_info["Client ID"]) ? $naver_setting_info["Client ID"] : "";
		$naver_client_secret = isset($naver_setting_info["Client Secret"]) ? $naver_setting_info["Client Secret"] : "";

		if (empty($naver_client_id) || empty($naver_client_secret)) {
			$this->common->scriptReplace("/member/login", "네이버 로그인 설정이 올바르지 않습니다. (Client ID 또는 Secret이 없습니다)");
			exit();
		}

		// app_base_url: 환경변수 APP_BASE_URL 또는 요청 도메인/스킴 기반으로 결정되는 앱 기본 URL
		// iOS Safari HTTPS 강제 처리
		$app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
		$app_base_url = $this->ensure_https($app_base_url);
		$callback_url = $app_base_url . "/member/naver_login_callback";

		// 토큰 받기
		// ⚠️ 중요: authorize 단계에서 urlencode($redirect_uri)로 전송했으므로
		// token 단계에서도 동일하게 인코딩하지 않은 원본 URL을 전송해야 네이버 검증을 통과
		$token_url = "https://nid.naver.com/oauth2.0/token";
		$token_data = array(
			"grant_type" => "authorization_code",
			"client_id" => $naver_client_id,
			"client_secret" => $naver_client_secret,
			"redirect_uri" => $callback_url,
			"code" => $code,
			"state" => $state
		);

		$token_headers = array(
			"Content-type: application/x-www-form-urlencoded;charset=utf-8"
		);

		$token_result = $this->call_naver_api($token_headers, $token_url, $token_data);
		$token_response = json_decode($token_result[0], true);

		// 토큰 요청 오류 처리
		if (empty($token_response["access_token"])) {
			$error_msg = "문제가 발생해서 정상적으로 로그인이 되지 않았습니다.(2)";
			if (isset($token_response["error"])) {
				$error_msg .= " - 오류: " . $token_response["error"];
			}
			if (isset($token_response["error_description"])) {
				$error_msg .= " - " . $token_response["error_description"];
			}

			$this->common->scriptReplace("/member/login", $error_msg);
			exit();
		}

		// 사용자 정보 가져오기
		$user_info_url = "https://openapi.naver.com/v1/nid/me";
		$user_info_headers = array(
			"Authorization: Bearer " . $token_response["access_token"],
			"Content-type: application/x-www-form-urlencoded;charset=utf-8"
		);

		$user_info_result = $this->call_naver_api($user_info_headers, $user_info_url);
		$user_info_response = json_decode($user_info_result[0], true);

		if (!empty($user_info_response["response"]["id"])) {
			$naver_id = $user_info_response["response"]["id"];
			
			$return = $this->login_process("NAVER", $naver_id);
		

			if ($return["code"] == "00") {
				
				$this->common->scriptReplace("/");
				exit;
			} else {
				// 회원 미존재 시: SNS 정보를 세션에 저장하고 PASS 인증 페이지로 이동
				$naver_response = $user_info_response["response"];

				$phonenum = normalizeSnsPhone(isset($naver_response["mobile"]) ? $naver_response["mobile"] : "");

				// 회원이 없을 때 전화번호 정규화 및 중복 체크 (모든 비숫자 제거)
				if (!empty($phonenum)) {
					$normalized_phone = preg_replace('/[^0-9]/', '', $phonenum);
					$duplicate_check = checkDuplicatePhoneForSns($normalized_phone);
					if ($duplicate_check["is_duplicate"]) {
						// 중복이 있으면 로그인 페이지로 리다이렉트 (쿼리파라미터로 중복 계정 정보 전달)
						$dup_url = "/member/login?dup_join_type=" . urlencode($duplicate_check["join_type"])
								. "&dup_mm_phone=" . urlencode(isset($duplicate_check["mm_phone"]) ? $duplicate_check["mm_phone"] : "");
						redirect($dup_url, 'refresh');
						exit;
					}
				}

				// 기존 SNS 데이터 확인 (앱에서 저장한 생년월일 정보 유지)
				$existing_sns = $this->site_model->getStepTempData("JOIN", "SNS");

				// SNS 정보를 세션에 저장
				$sns_info = [
					"join_type" => "NAVER",
					"join_key" => $naver_id,
					"username" => isset($naver_response["name"]) ? $naver_response["name"] : "naver_" . $naver_id,
					"email" => isset($naver_response["email"]) ? $naver_response["email"] : "",
					"gender" => isset($naver_response["gender"]) ? (($naver_response["gender"] == "M") ? "남" : (($naver_response["gender"] == "F") ? "여" : "")) : "",
					"phonenum" => $phonenum,
					"birthyear" => isset($naver_response["birthyear"]) ? $naver_response["birthyear"] : (!empty($existing_sns["birthyear"]) ? $existing_sns["birthyear"] : ""),
					"birthday" => isset($naver_response["birthday"]) ? $naver_response["birthday"] : (!empty($existing_sns["birthday"]) ? $existing_sns["birthday"] : ""),
				];

				// 성별도 기존 데이터가 있으면 우선 사용 (앱에서 변환된 값 유지)
				if (!empty($existing_sns["gender"]) && ($existing_sns["gender"] == "남" || $existing_sns["gender"] == "여")) {
					$sns_info["gender"] = $existing_sns["gender"];
				}

				// 기존 로그 삭제
				$this->site_model->deleteSiteLog(["session_id" => session_id(), "sl_status" => "Y"]);

				// SNS 정보 로그 저장
				$this->site_model->setSiteLog(
					[
						"session_id" => session_id(),
						"sl_type" => "JOIN",
						"sl_step" => "SNS",
						"sl_txt" => json_encode($sns_info, JSON_UNESCAPED_UNICODE)
					],
					[
						"session_id" => session_id(),
						"sl_type" => "JOIN",
						"sl_step" => "SNS",
						"sl_status" => "Y"
					]
				);

				// PASS 인증 페이지로 이동 (회원가입 안내와 함께)
				$data["code"] = $return["code"];
				$data["msg"] = $return["msg"];
				$data["res_id"] = $naver_id;
				$data["username"] = $sns_info["username"];
				$data["email"] = $sns_info["email"];
				$data["phonenum"] = $sns_info["phonenum"];
				$data["gender"] = $sns_info["gender"];
				$data["sns_info"] = $sns_info;
				$data["enc_data"] = $this->member_model->getNiceEncData();

				$this->layout->shop('/member/naver_login_callback', $data);
			}
		} else {
			$error_msg = "사용자 정보를 가져올 수 없습니다.";
			if (isset($user_info_response["errorMessage"])) {
				$error_msg .= " - " . $user_info_response["errorMessage"];
			}

			$this->common->scriptReplace("/member/login", $error_msg);
			exit();
		}
	} //naver_login_callback

	// WeChat 로그인 시작 (PC 웹용 - state 저장 필수)
	// WeChat은 모바일 앱과 PC 웹에서 동일한 OAuth URL을 사용하지만, PC 웹에서는 state 값을 세션에 저장하고 콜백에서 검증하는 방식이 필요
	function wechat_login()
	{
		// WeChat 설정 조회
		$wechat_setting = $this->setting_model->getSettingInfo(["st_group" => "위챗"]);
		$app_id = isset($wechat_setting["WEB_APP_ID"]) ? $wechat_setting["WEB_APP_ID"] : (isset($wechat_setting["APP_ID"]) ? $wechat_setting["APP_ID"] : "");

		if (empty($app_id)) {
			$this->common->scriptReplace("/member/login", "위챗 로그인 설정이 없습니다.");
			exit();
		}

		// iOS Safari HTTPS 강제 처리
		$app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
		$app_base_url = $this->ensure_https($app_base_url);
		$redirect_uri = $app_base_url . "/member/wechat_login_callback";

		// state 생성 및 세션에 저장 (CSRF 방지)
		$state = make_token() . "_" . time() . "_" . mt_rand(1000, 9999);
		$this->common->setSession("wechat-oauth-state", $state);

		// WeChat OAuth 페이지로 리다이렉트
		$scope = "snsapi_login";
		$auth_url = "https://open.weixin.qq.com/connect/qrconnect"
			. "?appid=" . urlencode($app_id)
			. "&redirect_uri=" . urlencode($redirect_uri)
			. "&response_type=code"
			. "&scope=" . urlencode($scope)
			. "&state=" . urlencode($state)
			. "#wechat_redirect";

		go_location($auth_url);
	}

	// 구글 로그인 시작 (서버 주도 OAuth)
	function google_login()
	{
		// 1. 설정 모델에서 "구글"이라는 그룹의 정보를 가져와서 상자에 담기
		$google_setting_info = $this->setting_model->getSettingInfo(["st_group" => "구글"]);
		$google_client_id = isset($google_setting_info["WEB_CLIENT_ID"]) ? $google_setting_info["WEB_CLIENT_ID"] : "";
		$google_client_secret = isset($google_setting_info["WEB_CLIENT_SECRET"]) ? $google_setting_info["WEB_CLIENT_SECRET"] : "";

		if (empty($google_client_id) || empty($google_client_secret)) {
			$this->common->scriptReplace("/member/login", "구글 로그인 설정이 올바르지 않습니다.");
			exit();
		}

		// iOS Safari HTTPS 강제 처리
		$app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
		$app_base_url = $this->ensure_https($app_base_url);
		$redirect_uri = $app_base_url . "/member/google_login_callback";

		// 매번 고유한 state 값 사용
		$state = make_token() . "_" . time() . "_" . mt_rand(1000, 9999);
		$this->common->setSession("google-oauth-state", $state);

		$scope = urlencode("openid email profile");
		$auth_url = "https://accounts.google.com/o/oauth2/v2/auth?response_type=code"
			. "&client_id=" . urlencode($google_client_id)
			. "&redirect_uri=" . urlencode($redirect_uri)
			. "&scope=" . $scope
			. "&state=" . urlencode($state)
			. "&prompt=select_account";

		go_location($auth_url);
	}

	function google_login_callback()
	{
		$code = isset($_REQUEST["code"]) ? $_REQUEST["code"] : "";
		$state = isset($_REQUEST["state"]) ? $_REQUEST["state"] : "";
		$error = isset($_REQUEST["error"]) ? $_REQUEST["error"] : "";
		$error_description = isset($_REQUEST["error_description"]) ? $_REQUEST["error_description"] : "";

		if ($error) {
			$error_msg = "문제가 발생해서 정상적으로 로그인이 되지 않았습니다.(1)";
			if (!empty($error_description)) {
				$error_msg .= " - " . $error_description;
			}
			$this->common->scriptReplace("/member/login", $error_msg);
			exit();
		}

		if (empty($code)) {
			$this->common->scriptReplace("/member/login", "구글 로그인 인증 코드를 받지 못했습니다.");
			exit();
		}

		$saved_state = $this->common->getSession("google-oauth-state");
		if (empty($saved_state) || $saved_state !== $state) {
			$this->common->scriptReplace("/member/login", "구글 로그인 상태값 검증에 실패했습니다.");
			exit();
		}

		$google_setting_info = $this->setting_model->getSettingInfo(["st_group" => "구글"]);
		$google_client_id = isset($google_setting_info["WEB_CLIENT_ID"]) ? $google_setting_info["WEB_CLIENT_ID"] : "";
		$google_client_secret = isset($google_setting_info["WEB_CLIENT_SECRET"]) ? $google_setting_info["WEB_CLIENT_SECRET"] : "";

		if (empty($google_client_id) || empty($google_client_secret)) {
			$this->common->scriptReplace("/member/login", "구글 로그인 설정이 올바르지 않습니다.");
			exit();
		}

		// iOS Safari HTTPS 강제 처리
		$app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
		$app_base_url = $this->ensure_https($app_base_url);
		$redirect_uri = $app_base_url . "/member/google_login_callback";

		$token_url = "https://oauth2.googleapis.com/token";
		$token_data = array(
			"code" => $code,
			"client_id" => $google_client_id,
			"client_secret" => $google_client_secret,
			"redirect_uri" => $redirect_uri,
			"grant_type" => "authorization_code"
		);
		$token_headers = array(
			"Content-type: application/x-www-form-urlencoded;charset=utf-8"
		);

		$token_result = $this->call_google_api($token_headers, $token_url, $token_data);
		$token_response = json_decode($token_result[0], true);

		if (empty($token_response["id_token"])) {
			$error_msg = "문제가 발생해서 정상적으로 로그인이 되지 않았습니다.(2)";
			if (isset($token_response["error"])) {
				$error_msg .= " - 오류: " . $token_response["error"];
			}
			if (isset($token_response["error_description"])) {
				$error_msg .= " - " . $token_response["error_description"];
			}
			$this->common->scriptReplace("/member/login", $error_msg);
			exit();
		}

		$verify = $this->verify_google_id_token($token_response["id_token"], [$google_client_id]);
		if (!$verify["ok"]) {
			$this->common->scriptReplace("/member/login", "구글 토큰 검증에 실패했습니다.");
			exit();
		}

		$token_data = $verify["data"];
		// Google 계정의 고유 식별자(sub, subject)
		$google_sub = $token_data["sub"];
		$email = isset($token_data["email"]) ? $token_data["email"] : "";
		$name = "";

		// 사용자 정보 가져오기 (가능한 경우)
		if (!empty($token_response["access_token"])) {
			$user_info_url = "https://openidconnect.googleapis.com/v1/userinfo";
			$user_info_headers = array(
				"Authorization: Bearer " . $token_response["access_token"]
			);
			$user_info_result = $this->call_google_api($user_info_headers, $user_info_url);
			$user_info_response = json_decode($user_info_result[0], true);

			if (is_array($user_info_response)) {
				if (isset($user_info_response["email"]) && $user_info_response["email"] != "") {
					$email = $user_info_response["email"];
				}
				if (isset($user_info_response["name"]) && $user_info_response["name"] != "") {
					$name = $user_info_response["name"];
				} else if (isset($user_info_response["given_name"]) && $user_info_response["given_name"] != "") {
					$name = $user_info_response["given_name"];
				}
			}
		}

		$return = $this->login_process("GOOGLE", $google_sub);
		if ($return["code"] == "00") {
			$this->common->scriptReplace("/");
			exit;
		} else if ($return["code"] == "90") {
			// 신규 회원: 로그인 페이지로 리다이렉트해 전화번호 입력 (가입 방식 무관 중복 감지)
			// join_sns=1: 페이지 로드 시 openGoogleJoinPhonePopup 자동 호출 신호
			$join_url = "/member/login?join_sns=1&join_key=" . urlencode($google_sub) . "&join_type=GOOGLE";
			if (!empty($email)) {
				$join_url .= "&email=" . urlencode($email);
			}
			if (!empty($name)) {
				$join_url .= "&username=" . urlencode($name);
			}
			go_location($join_url);
		} else {
			go_location("/member/login?msg_code=" . $return["code"]);
		}
	}

	/**
	 * 네이버 API 호출 메서드
	 * @param array $headers
	 * @param string $url
	 * @param array $postData
	 * @return array
	 */
	private function call_naver_api($headers, $url, $postData = null)
	{
		try {
			$postDataString = "";
			if (is_array($postData)) {
				$postDataString = http_build_query($postData);
			}

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HEADER, false);

			if ($postData !== null) {
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataString);
			} else {
				curl_setopt($ch, CURLOPT_POST, false);
			}

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$result[0] = curl_exec($ch);
			$result[1] = curl_errno($ch);
			$result[2] = curl_error($ch);
			$result[3] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			return $result;
		} catch (Exception $e) {
			echo $e->getMessage();
			return array("false");
		}
	}

	/**
	 * 구글 API 호출 메서드
	 * @param array $headers
	 * @param string $url
	 * @param array $postData
	 * @return array
	 */
	private function call_google_api($headers, $url, $postData = null)
	{
		try {
			$postDataString = "";
			if (is_array($postData)) {
				$postDataString = http_build_query($postData);
			}

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HEADER, false);

			if ($postData !== null) {
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataString);
			} else {
				curl_setopt($ch, CURLOPT_POST, false);
			}

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$result[0] = curl_exec($ch);
			$result[1] = curl_errno($ch);
			$result[2] = curl_error($ch);
			$result[3] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			return $result;
		} catch (Exception $e) {
			echo $e->getMessage();
			return array("false");
		}
	}

	/**
	 * Google ID Token 검증
	 */
	private function verify_google_id_token($id_token, $allowed_client_ids = [])
	{
		if ($id_token == "") {
			return ["ok" => false, "error" => "id_token 없음"];
		}

		$url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($id_token);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_error = curl_error($ch);
		curl_close($ch);

		if ($response === false || $http_code != 200) {
			return ["ok" => false, "error" => "토큰 검증 실패: " . $curl_error];
		}

		$data = json_decode($response, true);
		if (!is_array($data) || empty($data["sub"])) {
			return ["ok" => false, "error" => "토큰 정보 파싱 실패"];
		}

		if (!empty($allowed_client_ids)) {
			$aud = isset($data["aud"]) ? $data["aud"] : "";
			if ($aud == "" || !in_array($aud, $allowed_client_ids, true)) {
				return ["ok" => false, "error" => "클라이언트 ID 불일치"];
			}
		}

		if (isset($data["exp"]) && (int)$data["exp"] < time()) {
			return ["ok" => false, "error" => "토큰 만료"];
		}

		return ["ok" => true, "data" => $data];
	}
    // SNS 로그인 처리 (공통)
	function login_process($join_type, $join_key)
	{
		# 정보조회후 로그인처리

		$mem_info = $this->member_model->getMember(["mm_join_type" => $join_type, "mm_join_key" => $join_key, "mm_status" => "Y"]);
		//print_r($mem_info);
		//exit;//test
		if ($mem_info != null && $mem_info["mm_status"] == "Y") {
			// 회원이 있으면 로그인 시키고
			$addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
			$this->user->setUser([
				"mm_idx" => $mem_info["mm_idx"],
				"mm_id" => $mem_info["mm_id"],
				"mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
				"mm_type" => $mem_info["mm_type"],
				"ad_addr" => $addr_info["ad_addr"],
				"ad_gps_x" => $addr_info["ad_gps_x"],
				"ad_gps_y" => $addr_info["ad_gps_y"],
				"mm_sex" => $mem_info["mm_sex"],
			]);

			# 접속 처리
			$this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $mem_info["mm_id"]]);

			# 최근 로그인 SNS 추적 저장 (세션 + 쿠키)
			$this->common->setSession("last_login_type", $join_type);
			setcookie("last_login_type", $join_type, time() + (86400 * 365), "/");

			# 헤더 정보 업데이트 : UUID로 디바이스를 찾아 현재 로그인한 회원과 연결한다.
			$headers = apache_request_headers();
			if (isset($headers["uuid"])) {
				// UUID로 디바이스 정보를 조회한다 (mm_idx 조건 없이)
				$mem_device = $this->member_model->getMemberDevice(["uuid" => $headers["uuid"]]);
				# 빈값이 아니라면
				if ($mem_device != null) {
					// 해당 디바이스를 로그인한 회원(mm_idx)과 연결 (기존 연결 관계와 무관하게 재할당)
					$this->member_model->setMemberDevice(["mm_idx" => $mem_info["mm_idx"]], ["uuid" => $headers["uuid"]]);
				}
			}

			# 로그인 성공 시 해당 사용자의 모든 디바이스 중 토큰이 있는 경우 push_yn을 'Y'로 활성화
			$sql = "UPDATE st_member_device 
					SET push_yn = 'Y', up_dt = NOW() 
					WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
			$this->db->query($sql, [$mem_info["mm_idx"]]);

			# 몽고 회원정보 DB업데이트
			$mem_device = $this->member_model->getMemberDevice(["mm_idx" => $mem_info["mm_idx"]]);
			$this->mongo_model->insertUserInfo($mem_info, $mem_device != null ? $mem_device["token"] : "");

			return ["code" => "00", "msg" => "로그인 성공"];
		} //로그인 성공

		//
		$query  = "select * from st_member ";
		$query .= "where mm_join_type = '" . $join_type . "' ";
		$query .= "and   mm_join_key = '" . $join_key . "'  ";
		$query .= "and   mm_status = 'N'  ";
		$query .= "order by mm_idx desc limit 1 ";

		$result = $this->db->query($query);
		$mem_info = $result->row_array();
		if ($mem_info == null) {
			return ["code" => "90", "msg" => "회원정보를 찾을수 없습니다."];
		}

		// if($mem_info==null){
		// 	return ["code"=>"90","msg"=>"회원정보를 찾을수 없습니다."];
		// }

		if ($mem_info["mm_status"] == "N") {

			$fireDate = new DateTime($mem_info["fire_dt"]); // 탈퇴일
			$currentDate = new DateTime(); // 현재 시간
			$interval = $fireDate->diff($currentDate)->days; // 두 날짜 차이 계산 (일 단위)

			if ($interval < 15) {
				return ["code" => "91", "msg" => "탈퇴한 회원입니다."];
			} else {
				return ["code" => "90", "msg" => "회원정보를 찾을 수 없습니다."];
			}
			// return ["code"=>"91","msg"=>"탈퇴한 회원입니다."];
		}
	}

	// WeChat 로그인 처리: unionid/openid 폴백 지원
	// 모든 플랫폼(웹/앱)에서 동일한 WeChat 계정으로 로그인할 수 있도록 함
	function wechat_login_process($openid, $unionid = "")
	{
		// 우선순위: unionid 먼저 시도 → 없으면 openid로 시도
		$mm_join_key_for_query = (!empty($unionid)) ? $unionid : $openid;

		$mem_info = $this->member_model->getMember(["mm_join_type" => "WECHAT", "mm_join_key" => $mm_join_key_for_query, "mm_status" => "Y"]);

		// unionid로 못 찾고 openid가 다르다면, openid로 재시도 (기존 openid 레코드 호환성)
		if ($mem_info == null && !empty($unionid) && $unionid != $openid) {
			$mem_info = $this->member_model->getMember(["mm_join_type" => "WECHAT", "mm_join_key" => $openid, "mm_status" => "Y"]);
		}

		if ($mem_info != null && $mem_info["mm_status"] == "Y") {
			// 기존 회원 로그인 성공
			$addr_info = $this->member_model->getAddress(["ad_type" => "MEMBER", "mm_idx" => $mem_info["mm_idx"]]);
			$this->user->setUser([
				"mm_idx" => $mem_info["mm_idx"],
				"mm_id" => $mem_info["mm_id"],
				"mm_name" => ($mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"]),
				"mm_type" => $mem_info["mm_type"],
				"ad_addr" => $addr_info["ad_addr"],
				"ad_gps_x" => $addr_info["ad_gps_x"],
				"ad_gps_y" => $addr_info["ad_gps_y"],
				"mm_sex" => $mem_info["mm_sex"],
			]);

			// 접속 처리
			$this->member_model->updatemember(["last_login_dt" => get_today()], ["mm_id" => $mem_info["mm_id"]]);

			// 최근 로그인 SNS 추적 저장 (세션 + 쿠키)
			$this->common->setSession("last_login_type", "WECHAT");
			setcookie("last_login_type", "WECHAT", time() + (86400 * 365), "/");

			// openid로 조회된 기존 회원이면 unionid로 자동 마이그레이션
			if (!empty($unionid) && $mem_info["mm_join_key"] != $unionid) {
				$this->member_model->updatemember(
					["mm_join_key" => $unionid],
					["mm_idx" => $mem_info["mm_idx"]]
				);
			}

			// 헤더 정보 업데이트 (UUID 디바이스 연결)
			$headers = apache_request_headers();
			if (isset($headers["uuid"])) {
				$mem_device = $this->member_model->getMemberDevice(["uuid" => $headers["uuid"]]);
				if ($mem_device != null) {
					$this->member_model->setMemberDevice(["mm_idx" => $mem_info["mm_idx"]], ["uuid" => $headers["uuid"]]);
				}
			}

			// 로그인 성공 시 디바이스 push_yn 활성화
			$sql = "UPDATE st_member_device
					SET push_yn = 'Y', up_dt = NOW()
					WHERE mm_idx = ? AND token IS NOT NULL AND token != ''";
			$this->db->query($sql, [$mem_info["mm_idx"]]);

			// 몽고 회원정보 DB업데이트
			$mem_device = $this->member_model->getMemberDevice(["mm_idx" => $mem_info["mm_idx"]]);
			$this->mongo_model->insertUserInfo($mem_info, $mem_device != null ? $mem_device["token"] : "");

			return ["code" => "00", "msg" => "로그인 성공"];
		}

		// 탈퇴/비활성 회원 확인
		$mm_join_key_for_inactive = (!empty($unionid)) ? $unionid : $openid;

		$query  = "select * from st_member ";
		$query .= "where mm_join_type = 'WECHAT' ";
		$query .= "and   mm_join_key = '" . $mm_join_key_for_inactive . "'  ";
		$query .= "and   mm_status = 'N'  ";
		$query .= "order by mm_idx desc limit 1 ";

		$result = $this->db->query($query);
		$mem_info = $result->row_array();

		// 탈퇴/비활성도 못 찾으면 openid로 재시도
		if ($mem_info == null && !empty($unionid) && $unionid != $openid) {
			$query  = "select * from st_member ";
			$query .= "where mm_join_type = 'WECHAT' ";
			$query .= "and   mm_join_key = '" . $openid . "'  ";
			$query .= "and   mm_status = 'N'  ";
			$query .= "order by mm_idx desc limit 1 ";

			$result = $this->db->query($query);
			$mem_info = $result->row_array();
		}

		if ($mem_info == null) {
			return ["code" => "90", "msg" => "회원정보를 찾을수 없습니다."];
		}

		// 탈퇴 회원 처리
		if ($mem_info["mm_status"] == "N") {
			$fireDate = new DateTime($mem_info["fire_dt"]);
			$currentDate = new DateTime();
			$interval = $fireDate->diff($currentDate)->days;

			if ($interval < 15) {
				return ["code" => "91", "msg" => "탈퇴한 회원입니다."];
			} else {
				return ["code" => "90", "msg" => "회원정보를 찾을 수 없습니다."];
			}
		}

		return ["code" => "90", "msg" => "회원정보를 찾을수 없습니다."];
	}

	# 전화번호로 로그인 페이지 2025.08.28 추가
	function nowjob_login()
	{
		$data = [];

		$data["header_type"] = "";

		$csrf = make_token();
		$this->common->setSession("csrf-token", $csrf);

		# 로그인시 메인화면으로 이동
		if ($this->user->isLogin()) {
			go_location("/home/index");
		}

		$data["csrf"] = $csrf;
		$data["kakao_key"] = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);

		$msg_code = input_get("msg_code", "");
		$data["msg_code"] = $msg_code;

		$this->layout->shop('/member/nowjob_login', $data);
	}

	# 휴대폰 OTP 로그인 인증 페이지
	function phone_login_auth()
	{
		$data = [];
		$data["header_type"] = "";

		# 로그인시 메인화면으로 이동
		if ($this->user->isLogin()) {
			go_location("/home/index");
		}

		$this->layout->shop('/member/phone_login_auth', $data);
	}

	# 로그아웃
	function logout()
	{
		// 세션에서 사용자 정보 조회
		$user_info = $this->common->getSession("USER_INFO");

		if ($user_info && isset($user_info["mm_idx"]) && $user_info["mm_idx"] > 0) {
			$mm_idx = $user_info["mm_idx"];
			$mm_id = isset($user_info["mm_id"]) ? $user_info["mm_id"] : "";

			// 해당 사용자의 모든 디바이스의 push_yn을 'N'으로 업데이트 (푸시 알림 비활성화)
			$this->member_model->updateMemberDevice(
				["push_yn" => "N"],
				["mm_idx" => $mm_idx]
			);

			// MongoDB의 사용자 상태도 'N'으로 업데이트 (채팅 서버와의 일관성 유지, 탈퇴와 공통 함수)
			$this->member_model->setMongoUserStatus($mm_id, "N");
		}

		// USER_INFO만 삭제 (다른 세션 데이터는 유지)
		$this->common->removeSession("USER_INFO");

		// 최근 로그인 타입 쿠키는 유지 (로그인 화면에서 최근 로그인 방식 표시용)

		// iOS 감지
		$headers = apache_request_headers();
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$is_ios = preg_match('/nowjobswift|CFNetwork|Darwin/i', $user_agent) || preg_match('/\b(iPhone|iPad|iPod)\b/i', $user_agent);

		// iOS인 경우 세션 쿠키를 명시적으로 삭제하고 세션 ID 재생성
		if ($is_ios) {
			// 도메인 설정 (index.php와 동일한 로직)
			$domain_name = $_SERVER['SERVER_NAME'];
			$domain_name = str_replace("www.", "", $domain_name);
			$domain_name = str_replace("home.", "", $domain_name);
			$domain_name = str_replace("app.", "", $domain_name);
			$domain_name = str_replace("admin.", "", $domain_name);

			// 세션 쿠키 삭제 (도메인과 경로를 정확히 맞춰야 함)
			$session_name = session_name();
			if (isset($_COOKIE[$session_name])) {
				// 서브도메인 포함 쿠키 삭제
				setcookie($session_name, '', time() - 3600, '/', '.' . $domain_name, true, true);
				// 메인 도메인 쿠키 삭제
				setcookie($session_name, '', time() - 3600, '/', $domain_name, true, true);
				// 경로만 지정한 쿠키 삭제
				setcookie($session_name, '', time() - 3600, '/', '', true, true);
			}

			// 세션 ID 재생성 (이전 세션 파일 삭제, 현재 세션 데이터는 새 세션으로 이동)
			if (session_status() === PHP_SESSION_ACTIVE) {
				session_regenerate_id(true);
			}
		} else {
			// 안드로이드/기타: 세션 ID만 재생성 (이전 세션 파일 삭제)
			if (session_status() === PHP_SESSION_ACTIVE) {
				session_regenerate_id(true);
			}
		}

		go_location("/member/login");
	}

	# 테스트
	function addr_test()
	{
		$data = [];
		$this->layout->shop('/member/addr_test', $data);
	}

	# 아이디
	function find_id()
	{
		$data = [];
		$this->layout->shop('/member/find_id', $data);
	}

	# 패스워드
	function find_pwd()
	{
		$data = [];
		$this->layout->shop('/member/find_pwd', $data);
	}

	# 회원가입 시작
	function join_sns_start()
	{
		$data = [];

		$join_key = input_get("join_key", "");
		$join_type = input_get("join_type", "");

		$username = input_get("username", "");
		$email = input_get("email", "");
		$gender = input_get("gender", "");
		$phonenum = input_get("phonenum", "");
		$birthyear = input_get("birthyear", ""); // URL 파라미터로 생년 받기
		$birthday = input_get("birthday", ""); // URL 파라미터로 생일 받기
		// 클라이언트에서 이미 포맷 변환된 데이터를 받음 (중복 변환 제거)

		// 기존 SNS 데이터 (로그인 단계에서 site_log에 저장된 값)
		$existing_sns = $this->site_model->getStepTempData("JOIN", "SNS");

		// 카카오: 개인정보는 URL로 받지 않고 site_log 값을 사용 (URL 값이 있으면 URL 값 우선)
		if ($join_type == "KAKAO" && !empty($existing_sns) && isset($existing_sns["join_type"]) && $existing_sns["join_type"] == "KAKAO") {
			if ($join_key == "") $join_key = isset($existing_sns["join_key"]) ? $existing_sns["join_key"] : "";
			if ($username == "") $username = isset($existing_sns["username"]) ? $existing_sns["username"] : "";
			if ($email == "") $email = isset($existing_sns["email"]) ? $existing_sns["email"] : "";
			if ($gender == "") $gender = isset($existing_sns["gender"]) ? $existing_sns["gender"] : "";
			if ($phonenum == "") $phonenum = isset($existing_sns["phonenum"]) ? $existing_sns["phonenum"] : "";
		}

		// 저장 형식 통일: 숫자만 (예: 01090025528)
		$phonenum = normalizeSnsPhone($phonenum);

		// 간편로그인 회원가입 시작 시 전화번호 필수
		if (empty($phonenum)) {
			go_location("/member/login", "전화번호 인증이 필요합니다. 다시 시도해주세요.");
			exit;
		}

		// 전화번호로 기존 회원 존재 여부 확인
		$duplicate_check = checkDuplicatePhoneForSns($phonenum);
		if ($duplicate_check["is_duplicate"]) {
			// 중복이 있으면 로그인 페이지로 리다이렉트 (쿼리파라미터로 중복 계정 정보 전달)
			$dup_url = "/member/login?dup_join_type=" . urlencode($duplicate_check["join_type"])
					. "&dup_mm_phone=" . urlencode(isset($duplicate_check["mm_phone"]) ? $duplicate_check["mm_phone"] : "");
			redirect($dup_url, 'refresh');
			exit;
		}

		// 성별 변환: 카카오의 경우 MALE/FEMALE -> 남/여
		$gender_converted = $gender;
		if ($join_type == "KAKAO") {
			if ($gender == "MALE") {
				$gender_converted = "남";
			} else if ($gender == "FEMALE") {
				$gender_converted = "여";
			}
		}

		// WeChat: site_log에서 unionid 가져오기 (PC 웹 OAuth에서 저장됨)
		if ($join_type == "WECHAT" && !empty($existing_sns["unionid"])) {
			$join_key = $existing_sns["unionid"];
		}

		$info = [
			"join_key" => $join_key,
			"join_type" => $join_type,
			"username" => $username,
			"email" => $email,
			"gender" => $gender_converted,
			"phonenum" => $phonenum,
			// 생년월일: URL 파라미터 우선, 없으면 기존 데이터에서 가져오기
			"birthyear" => !empty($birthyear) ? $birthyear : (!empty($existing_sns["birthyear"]) ? $existing_sns["birthyear"] : ""),
			"birthday" => !empty($birthday) ? $birthday : (!empty($existing_sns["birthday"]) ? $existing_sns["birthday"] : ""),
		];

		// unionid 있으면 저장 (WeChat 크로스 플랫폼 지원)
		if (!empty($existing_sns["unionid"])) {
			$info["unionid"] = $existing_sns["unionid"];
		}

		// 성별도 기존 데이터가 있으면 우선 사용 (앱에서 변환된 값 유지)
		if (!empty($existing_sns["gender"]) && ($existing_sns["gender"] == "남" || $existing_sns["gender"] == "여")) {
			$info["gender"] = $existing_sns["gender"];
		}

		#기존 로그 삭제
		$this->site_model->deleteSiteLog(["session_id" => session_id(), "sl_status" => "Y"]);

		#로그 생성
		$this->site_model->setSiteLog(
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN",
				"sl_step" => "SNS",
				"sl_txt" => json_encode($info, JSON_UNESCAPED_UNICODE)
			],
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN",
				"sl_step" => "SNS",
				"sl_status" => "Y"
			]
		);

		go_location("/member/join_start?join_type=sns");
	}

	# 회원가입 시작
	function join_start()
	{
		$data = [];

		$headers = apache_request_headers();
		$data["uuid"] = $headers["uuid"];
		$data["os_type"] = isset($headers["os_type"]) ? $headers["os_type"] : "";

		$join_type = input_get("join_type", "");

		#기존 로그 삭제
		if ($join_type != "sns") {
			$this->site_model->deleteSiteLog(["session_id" => session_id(), "sl_status" => "Y"]);
		}

		#로그 생성
		$this->site_model->setSiteLog(
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN",
				"sl_step" => "시작",
				"sl_txt" => ""
			],
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN",
				"sl_step" => "시작",
				"sl_status" => "Y"
			]
		);

		$data["enc_data"] = $this->member_model->getNiceEncData();
		// $data["join_type"] = $join_type;
		$this->layout->shop('/member/join_start', $data);
	}

	# 회원가입 인증
	function join_auth()
	{
		$data = [];
		$this->layout->shop('/member/join_auth', $data);
	}

	# 회원가입 시작
	function join_error()
	{
		$data = [];

		$session_di = $this->common->getSession("AUTH_DI");
		$mem_info = $this->member_model->getMember(["mm_di" => $session_di, "mm_status" => "Y"]);

		if ($mem_info == null) {
			go_location("/member/login", "회원정보를 찾을수 없습니다.");
		}

		$data["mem_info"] = $mem_info;
		$this->layout->shop('/member/join_error', $data);
	}

	# 회원가입 토튜리얼
	function join_mem_toturial()
	{
		$data = [];
		$headers = apache_request_headers();
		$data["uuid"] = isset($headers["uuid"]) ? $headers["uuid"] : "";
		$data["ma_type"] = "MEM";

		# NICE 본인인증 정보에서 휴대폰 번호 추출
		$type = $this->common->getSession("AUTH_TYPE"); // type: MEM or COMP
		$list = $this->site_model->getSiteLogList(
			["session_id" => session_id(), "sl_type" => "JOIN_" . $type, "sl_status" => "Y"]
		);
		$step_auth = $this->site_model->getStepData($list, "본인인증");

		# SNS 회원가입 여부 확인
		$check_sns = $this->site_model->getSiteLog(
			["session_id" => session_id(), "sl_type" => "JOIN", "sl_step" => "SNS", "sl_status" => "Y"]
		);

		if (!empty($check_sns)) {
			# SNS 가입: 기존 로직
			$sns_info = json_decode($check_sns["sl_txt"], true);
			$uniqid = ($sns_info["join_type"] == "KAKAO") ? uniqid("kakao_") : uniqid("apple_");
		} else if (!empty($step_auth["mm_phone"])) {
			# 일반 가입(NICE): 휴대폰 번호를 mm_id로 사용
			$uniqid = preg_replace('/[^0-9]/', '', $step_auth["mm_phone"]);
		} else {
			# 폴백
			$uniqid = uniqid("web_");
		}

		# 스텝1(아이디/비밀번호) 자동 생성
		$sl_txt = ["mm_id" => $uniqid, "mm_pwd" => $uniqid];
		$this->site_model->setSiteLog(
			["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_step" => "스텝1", "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)],
			["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_step" => "스텝1", "sl_status" => "Y"]
		);

		$this->layout->shop('/member/join_auto_complete', $data);
	}

	# 회원가입 스탭1
	function join_mem_step1()
	{
		$data = [];

		/*
			# 본인인증 : @TODO : 임시
			$sl_txt = [
				"mm_name"=>"박현종",
				"mm_sex"=>"남",
				"mm_nation"=>"한국",
				"mm_birth"=>"19830126",
				"mm_phone"=>"01025324181",
				"mm_ci"=>"123asdfjj3oi23lj23ioj23rlkj23rlij23lr",
				"mm_di"=>"asefasfoiwuerouwieruoiwuerowueoruwieurowiuero3",
			];

			$this->site_model->setSiteLog(
                [
                    "session_id"=>session_id()
                    ,"sl_type"=>"JOIN_MEM"
                    ,"sl_step"=>"본인인증"
                    ,"sl_txt"=>json_encode($sl_txt,JSON_UNESCAPED_UNICODE)
                ],
                [
                    "session_id"=>session_id()
                    ,"sl_type"=>"JOIN_MEM"
                    ,"sl_step"=>"본인인증"
                    ,"sl_status"=>"Y"
                ]
            );
			*/

		//# 데이터 검증및 뒤로가기 처리
		//$is_check = $this->site_model->checkSiteLog("JOIN_MEM","본인인증");
		//if($is_check==false){
		//	go_location("/member/join_start","데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		//}

		# SNS 회원가입인경우 패스 처리
		$check_sns = $this->site_model->getSiteLog(
			["session_id" => session_id(), "sl_type" => "JOIN", "sl_step" => "SNS", "sl_status" => "Y"]
		);

		if ($check_sns != null) {
			$sns_info = json_decode($check_sns["sl_txt"], true);
			$uniqid = "";

			if ($sns_info["join_type"] == "KAKAO") {
				$uniqid = uniqid("kakao_");
			} else {
				$uniqid = uniqid("apple_");
			}

			#로그 생성
			$sl_txt = [
				"mm_id" => $uniqid,
				"mm_pwd" => $uniqid
			];

			$this->site_model->setSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "JOIN_MEM",
					"sl_step" => "스텝1",
					"sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
				],
				[
					"session_id" => session_id(),
					"sl_type" => "JOIN_MEM",
					"sl_step" => "스텝1",
					"sl_status" => "Y"
				]
			);
			go_location("/member/join_mem_step3");
		} else {
			// 기존 스텝1 로그 확인 (nice_success()에서 이미 생성했을 수 있음)
			$existing_step1 = $this->site_model->getSiteLog(
				["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_step" => "스텝1", "sl_status" => "Y"]
			);

			if (empty($existing_step1)) {
				// 기존 로그가 없을 때만 빈값으로 생성 (사용자가 아이디/비밀번호를 입력할 준비 상태)
				$this->site_model->setSiteLog(
					[
						"session_id" => session_id(),
						"sl_type" => "JOIN_MEM",
						"sl_step" => "스텝1",
						"sl_txt" => ""
					],
					[
						"session_id" => session_id(),
						"sl_type" => "JOIN_MEM",
						"sl_step" => "스텝1",
						"sl_status" => "Y"
					]
				);
			}
			// nice_success()에서 이미 저장했으면 그대로 유지
		}

		$list = $this->site_model->getSiteLogList(
			["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_status" => "Y"]
		);
		if ($list == null) {
			//    json_return("10","등록 데이터가 존재하지 않습니다.",$list);
		} else {
			$step_auth = $this->site_model->getStepData($list, "본인인증");
			$data["mm_phone"] = $step_auth["mm_phone"];
		}

		$this->layout->shop('/member/join_mem_step1', $data);
	}

	# 회원가입 스탭2
	# 회원가입 스탭3
	function join_mem_step3()
	{
		$data = [];

		//# 데이터 검증및 뒤로가기 처리
		//$is_check = $this->site_model->checkSiteLog("JOIN_MEM","스텝2");
		//if($is_check==false){
		//	go_location("/member/join_start","데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		//}

		# uuid
		$headers = apache_request_headers();
		$uuid = isset($headers["uuid"]) ? $headers["uuid"] : "";
		$data["uuid"] = $uuid;

		$data["policy1"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "policy_m"]);
		$data["policy2"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy_m"]);
		$data["policy3"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "location_m"]);
		$data["policy4"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy3_m"]);
		$data["policy5"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "adv_m"]);

		$this->layout->shop('/member/join_mem_step3', $data);
	}

	# 회원가입 완료
	function join_mem_finish()
	{
		$data = [];
		$is_login = $this->user->isLogin();

		$data["mm_type"] = $is_login ? $this->user->getUserType() : "";
		$data["join_type"] = $is_login ? $this->user->getJoinType() : "";

		# uuid (헤더로만 전달된다. Layout::shop()은 주입하지 않음)
		$headers = apache_request_headers();
		$data["uuid"] = isset($headers["uuid"]) ? $headers["uuid"] : "";
		$data["join_key"] = "";

		# 가입 대기 상태 판별
		# SNS/비SNS 모두 계정을 아직 만들지 않은 채 이 페이지에 도달한다(비로그인).
		# 가입 임시 데이터(st_site_log)가 살아 있으면 '적용하기'에서 계정을 생성할 수 있다.
		# - SNS: join_sns_start가 JOIN/SNS 로그를 남김
		# - 비SNS: nice_success가 본인인증/스텝1/스텝3 로그를 남김
		$data["join_pending"] = "N";
		$step_sns = [];
		$step_auth = [];
		$has_sns = false;
		if (!$is_login) {
			$join_log = $this->site_model->getSiteLogList(["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_status" => "Y"]);
			$step_sns = $this->site_model->getStepTempData("JOIN", "SNS");
			$has_sns = (is_array($step_sns) && isset($step_sns["join_type"]) && $step_sns["join_type"] != "");

			if ($join_log != null) {
				$data["join_pending"] = "Y";
				$data["mm_type"] = "MEM";

				if ($has_sns) {
					$data["join_type"] = $step_sns["join_type"];
					$data["join_key"] = isset($step_sns["join_key"]) ? $step_sns["join_key"] : "";
				} else {
					# 비SNS(NICE 본인인증) 경로
					$data["join_type"] = "WEB";
					$step_auth = $this->site_model->getStepData($join_log, "본인인증");
					if (!is_array($step_auth)) {
						$step_auth = [];
					}
				}
			} else {
				# 로그인도 아니고 진행 중인 가입도 없으면 처음부터 다시
				go_location("/member/login", "가입 정보가 만료되었습니다. 다시 진행해주세요.");
			}
		}

		# QuickInfo 바텀시트 미리 선택값 (가입 시점에 이미 확보한 성별/나이/국적)
		# 세션(USER_INFO)에는 mm_birth/mm_nation이 없으므로 DB에서 직접 조회한다
		$data["quickinfo_prefill"] = ["nation" => "", "gender" => "", "age" => ""];

		# 가입 대기 + SNS: 회원 레코드가 아직 없으므로 SNS 프로필에서 채운다
		if ($data["join_pending"] == "Y" && $has_sns) {
			$sns_gender = isset($step_sns["gender"]) ? $step_sns["gender"] : "";
			if ($sns_gender == "남" || $sns_gender == "male" || $sns_gender == "M") {
				$data["quickinfo_prefill"]["gender"] = "male";
			} else if ($sns_gender == "여" || $sns_gender == "female" || $sns_gender == "F") {
				$data["quickinfo_prefill"]["gender"] = "female";
			}

			# 생년: birthyear(4자리)만 있어도 연령대 산출이 가능하다
			$sns_birth = isset($step_sns["birthyear"]) ? $step_sns["birthyear"] : "";
			if ($sns_birth != "" && isset($step_sns["birthday"]) && $step_sns["birthday"] != "") {
				$sns_birth = $sns_birth . $step_sns["birthday"];
			}
			$data["quickinfo_prefill"]["age"] = getAgeGroupByBirth($sns_birth);
			# 국적은 SNS가 제공하지 않으므로 공백(사용자가 직접 선택)
		}

		# 가입 대기 + 비SNS: 본인인증 데이터에서 채운다.
		# nice_success가 저장한 값 형식이 DB(st_member)와 동일해 변환이 필요 없다.
		if ($data["join_pending"] == "Y" && !$has_sns) {
			$auth_sex = isset($step_auth["mm_sex"]) ? $step_auth["mm_sex"] : "";
			if ($auth_sex == "남") {
				$data["quickinfo_prefill"]["gender"] = "male";
			} else if ($auth_sex == "여") {
				$data["quickinfo_prefill"]["gender"] = "female";
			}

			$auth_nation = isset($step_auth["mm_nation"]) ? $step_auth["mm_nation"] : "";
			if ($auth_nation == "내국인") {
				$data["quickinfo_prefill"]["nation"] = "domestic";
			} else if ($auth_nation == "외국인") {
				$data["quickinfo_prefill"]["nation"] = "foreign";
			}

			$auth_birth = isset($step_auth["mm_birth"]) ? $step_auth["mm_birth"] : "";
			$data["quickinfo_prefill"]["age"] = getAgeGroupByBirth($auth_birth);
		}

		if ($is_login) {
			$mem_info = $this->member_model->getMember(["mm_idx" => $this->user->getUserIdx()]);
			if (!empty($mem_info)) {
				$mm_sex = isset($mem_info["mm_sex"]) ? $mem_info["mm_sex"] : "";
				$mm_nation = isset($mem_info["mm_nation"]) ? $mem_info["mm_nation"] : "";
				$mm_birth = isset($mem_info["mm_birth"]) ? $mem_info["mm_birth"] : "";

				# 성별: 남/여 외의 값(X, 공백)은 미선택으로 둔다
				if ($mm_sex == "남") {
					$data["quickinfo_prefill"]["gender"] = "male";
				} else if ($mm_sex == "여") {
					$data["quickinfo_prefill"]["gender"] = "female";
				}

				# 국적: SNS 가입은 항상 공백이라 대부분 미선택 상태가 된다
				if ($mm_nation == "내국인") {
					$data["quickinfo_prefill"]["nation"] = "domestic";
				} else if ($mm_nation == "외국인") {
					$data["quickinfo_prefill"]["nation"] = "foreign";
				}

				# 생년월일 -> 연령대 코드 (형식이 제각각이라 헬퍼가 흡수)
				$data["quickinfo_prefill"]["age"] = getAgeGroupByBirth($mm_birth);
			}
		}

		# 업종(희망업무) 선택지: 코드테이블에서 조회해 뷰로 전달
		$data["ru_wrok_list"] = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);

		$this->layout->shop('/member/join_mem_finish', $data);
	}

	# 회원가입 토튜리얼
	function join_comp_toturial()
	{
		$data = [];
		$headers = apache_request_headers();
		$data["uuid"] = isset($headers["uuid"]) ? $headers["uuid"] : "";
		$data["ma_type"] = "COMP";

		# NICE 본인인증 정보에서 휴대폰 번호 추출
		$type = $this->common->getSession("AUTH_TYPE");
		$list = $this->site_model->getSiteLogList(
			["session_id" => session_id(), "sl_type" => "JOIN_" . $type, "sl_status" => "Y"]
		);
		$step_auth = $this->site_model->getStepData($list, "본인인증");

		# SNS 회원가입 여부 확인
		$check_sns = $this->site_model->getSiteLog(
			["session_id" => session_id(), "sl_type" => "JOIN", "sl_step" => "SNS", "sl_status" => "Y"]
		);

		if (!empty($check_sns)) {
			# SNS 가입: 기존 로직
			$sns_info = json_decode($check_sns["sl_txt"], true);
			$uniqid = ($sns_info["join_type"] == "KAKAO") ? uniqid("kakao_") : uniqid("apple_");
		} else if (!empty($step_auth["mm_phone"])) {
			# 일반 가입(NICE): 휴대폰 번호를 mm_id로 사용
			$uniqid = preg_replace('/[^0-9]/', '', $step_auth["mm_phone"]);
		} else {
			# 폴백
			$uniqid = uniqid("web_");
		}

		# 스텝3(기업 위저드의 비밀번호 단계 번호) 자동 생성
		$sl_txt = ["mm_id" => $uniqid, "mm_pwd" => $uniqid];
		$this->site_model->setSiteLog(
			["session_id" => session_id(), "sl_type" => "JOIN_COMP", "sl_step" => "스텝3", "sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)],
			["session_id" => session_id(), "sl_type" => "JOIN_COMP", "sl_step" => "스텝3", "sl_status" => "Y"]
		);

		$this->layout->shop('/member/join_auto_complete', $data);
	}

	# 회원가입 스탭1
	function join_comp_step1()
	{
		$data = [];


		$this->layout->shop('/member/join_comp_step1', $data);
	}

	# 회원가입 스탭3
	function join_comp_step3()
	{
		$data = [];

		//# 데이터 검증및 뒤로가기 처리
		//$is_check = $this->site_model->checkSiteLog("JOIN_COMP","스텝2");
		//if($is_check==false){
		//	go_location("/member/join_start","데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		//}

		# SNS 회원가입인경우 패스 처리
		$check_sns = $this->site_model->getSiteLog(
			["session_id" => session_id(), "sl_type" => "JOIN", "sl_step" => "SNS", "sl_status" => "Y"]
		);

		if ($check_sns != null) {
			$sns_info = json_decode($check_sns["sl_txt"], true);
			$uniqid = "";

			if ($sns_info["join_type"] == "KAKAO") {
				$uniqid = uniqid("kakao_");
			} else {
				$uniqid = uniqid("apple_");
			}

			#로그 생성
			$sl_txt = [
				"mm_id" => $uniqid,
				"mm_pwd" => $uniqid
			];

			$this->site_model->setSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "JOIN_COMP",
					"sl_step" => "스텝3",
					"sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
				],
				[
					"session_id" => session_id(),
					"sl_type" => "JOIN_COMP",
					"sl_step" => "스텝3",
					"sl_status" => "Y"
				]
			);
			go_location("/member/join_comp_step5");
		}

		$list = $this->site_model->getSiteLogList(
			["session_id" => session_id(), "sl_type" => "JOIN_COMP", "sl_status" => "Y"]
		);
		if ($list == null) {
			//    json_return("10","등록 데이터가 존재하지 않습니다.",$list);
		} else {
			$step_auth = $this->site_model->getStepData($list, "본인인증");
			$data["mm_phone"] = $step_auth["mm_phone"];
		}

		$this->layout->shop('/member/join_comp_step3', $data);
	}

	# 회원가입 스탭5
	function join_comp_step5()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_COMP", "스텝4");
		if ($is_check == false) {
			//go_location("/member/join_start","데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		# uuid
		$headers = apache_request_headers();
		$uuid = isset($headers["uuid"]) ? $headers["uuid"] : "";
		$data["uuid"] = $uuid;

		$data["policy1"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "policy2_m"]);
		$data["policy2"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy_m"]);
		$data["policy3"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "location_m"]);
		$data["policy4"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy3_m"]);
		$data["policy5"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "adv_m"]);

		$this->layout->shop('/member/join_comp_step5', $data);
	}

	# 회원가입 완료
	function join_comp_finish()
	{
		$data = [];
		$this->layout->shop('/member/join_comp_finish', $data);
	}

	#나이스 처리
	function nice_popup()
	{
		$data["enc_data"] = $this->member_model->getNiceEncData();
		$this->layout->shop('/member/nice_popup', $data);
	}






	##########################################################
	# 웹용
	##########################################################

	# 회원가입 스탭1
	function join_wmem_step1()
	{

		//$mm_phone = input_get("mm_phone","");

		$data = [];

		//# 데이터 검증및 뒤로가기 처리
		//$is_check = $this->site_model->checkSiteLog("JOIN_MEM","본인인증");
		//if($is_check==false){
		//	go_location("/member/join_start","데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		//}

		# SNS 회원가입인경우 패스 처리
		$check_sns = $this->site_model->getSiteLog(
			["session_id" => session_id(), "sl_type" => "JOIN", "sl_step" => "SNS", "sl_status" => "Y"]
		);

		if ($check_sns != null) {
			$sns_info = json_decode($check_sns["sl_txt"], true);
			$uniqid = "";

			if ($sns_info["join_type"] == "KAKAO") {
				$uniqid = uniqid("kakao_");
			} else if ($sns_info["join_type"] == "NAVER") {
				$uniqid = uniqid("naver_");
			} else {
				$uniqid = uniqid("apple_");
			}

			#로그 생성
			$sl_txt = [
				"mm_id" => $uniqid,
				"mm_pwd" => $uniqid
			];

			$this->site_model->setSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "JOIN_MEM",
					"sl_step" => "스텝1",
					"sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
				],
				[
					"session_id" => session_id(),
					"sl_type" => "JOIN_MEM",
					"sl_step" => "스텝1",
					"sl_status" => "Y"
				]
			);
			go_location("/member/join_wmem_step2");
		} else {
			# NICE 본인인증 완료 시 nice_success()가 "스텝1"에 mm_id/mm_pwd(휴대폰번호)를 이미 저장함
			# 비밀번호 입력 단계 없이 약관 동의 단계로 바로 이동
			if ($this->site_model->checkSiteLog("JOIN_MEM", "스텝1")) {
				go_location("/member/join_wmem_step2");
			}

			# 본인인증 없이 직접 접근한 경우 가입을 처음부터 다시 진행하도록 안내
			go_location("/member/join_start", "본인인증을 먼저 진행해주세요.");
		}
	}

	# 회원가입 스탭2
	function join_wmem_step2()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_MEM", "스텝1");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		$data["policy1"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "policy_pc"]);
		$data["policy2"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy_pc"]);
		$data["policy3"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "location_pc"]);
		$data["policy4"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy3_pc"]);
		$data["policy5"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "adv_pc"]);

		$this->layout->shop('/member/join_wmem_step2', $data);
	}

	// 회원가입 스탭3 — 제거됨 (step2로 이전, join_wmem_step3.php 파일 삭제)
	// function join_wmem_step3() { ... }

	# 회원가입 스탭1
	# 회원가입 스탭2
	function join_wcomp_step2()
	{
		$data = [];

		# 스텝2 로그 생성
		$this->site_model->setSiteLog(
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN_COMP",
				"sl_step" => "스텝2",
				"sl_txt" => ""
			],
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN_COMP",
				"sl_step" => "스텝2",
				"sl_status" => "Y"
			]
		);

		# SNS 회원가입인경우 패스 처리
		$check_sns = $this->site_model->getSiteLog(
			["session_id" => session_id(), "sl_type" => "JOIN", "sl_step" => "SNS", "sl_status" => "Y"]
		);

		if ($check_sns != null) {
			$sns_info = json_decode($check_sns["sl_txt"], true);
			$uniqid = "";

			if ($sns_info["join_type"] == "KAKAO") {
				$uniqid = uniqid("kakao_");
			} else if ($sns_info["join_type"] == "NAVER") {
				$uniqid = uniqid("naver_");
			} else {
				$uniqid = uniqid("apple_");
			}

			#로그 생성
			$sl_txt = [
				"mm_id" => $uniqid,
				"mm_pwd" => $uniqid
			];

			$this->site_model->setSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "JOIN_COMP",
					"sl_step" => "스텝3",
					"sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
				],
				[
					"session_id" => session_id(),
					"sl_type" => "JOIN_COMP",
					"sl_step" => "스텝3",
					"sl_status" => "Y"
				]
			);
			go_location("/member/join_wcomp_step3");
		}

		$list = $this->site_model->getSiteLogList(
			["session_id" => session_id(), "sl_type" => "JOIN_COMP", "sl_status" => "Y"]
		);
		if ($list == null) {
			//    json_return("10","등록 데이터가 존재하지 않습니다.",$list);
		} else {
			$step_auth = $this->site_model->getStepData($list, "본인인증");
			$data["mm_phone"] = $step_auth["mm_phone"];
		}

		$this->layout->shop('/member/join_wcomp_step2', $data);
	}

	# 회원가입 스탭2
	function join_wcomp_step3()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_COMP", "스텝3");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		$data["policy1"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "policy2_pc"]);
		$data["policy2"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy_pc"]);
		$data["policy3"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "location_pc"]);
		$data["policy4"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy3_pc"]);
		$data["policy5"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "adv_pc"]);


		$this->layout->shop('/member/join_wcomp_step3', $data);
	}

	# 회원가입 스탭3
	// 회원가입 스탭4 — 제거됨 (step3로 이전, join_wcomp_step4.php 파일 삭제)
	// function join_wcomp_step4() { ... }
}
