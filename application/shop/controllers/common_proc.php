<?php
class Common_proc extends CI_Controller
{
	private $mongo_uri = "mongodb://localhost:27017";
	private $mongo_db = "chat";

	function __construct()
	{
		parent::__construct();

		$this->load->model('sitemodel', 'site_model');
		$this->load->model('membermodel', 'member_model');
		$this->load->model('settingmodel', 'setting_model');
		$this->load->model('mongomodel', 'mongo_model');
		$this->load->model('commonmodel', 'common_model');
		$this->load->model('admodel', 'ad_model');

		$this->load->library('user');
		$this->load->library('common');

		// 개발환경에서는 dev_chat DB 사용
		if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
			$this->mongo_uri = "mongodb://localhost:27017";
			$this->mongo_db = "dev_chat";
		}
	}

	/**
	 * 토큰 전송 로그 기록 (클라이언트에서 호출)
	 */
	function log_token_send()
	{
		json_return("00", "로그 기록 완료");
	}

	/**
	 * 웹뷰 디버그 로깅 함수 (사용 종료)
	 */
	function log_webview_debug()
	{
		// 호출은 허용하되 더 이상 파일 기록은 하지 않음
		json_return("00", "로그 기록 완료");
	}

	/**
	 * QuickInfo 적용하기 로그 (사용 종료)
	 */
	function log_quickinfo_apply()
	{
		// 호출은 허용하되 더 이상 파일 기록은 하지 않음
		json_return("00", "로그 기록 완료");
	}


	/**
	 * UUID 및 FCM 토큰 등록 (현재 미사용)
	 * 
	 * 용도: 기기 UUID와 FCM 토큰을 함께 등록하는 함수
	 * 상태: 로그 분석 결과 실제 호출되지 않음 (update_token 사용)
	 * 
	 * @return void
	 */
	function set_uuid()
	{
		$uuid = input_post("uuid", "");
		$push_yn = input_post("push_yn", "N");
		$marketing_yn = input_post("marketing_yn", "N");
		$token = input_post("token", ""); // FCM 토큰 추가

		if ($uuid != "") {
			$info = [
				"uuid" => $uuid,
				"push_yn" => $push_yn,
				"marketing_yn" => $marketing_yn,
				"md_step2" => "Y",
			];

			// FCM 토큰이 있으면 함께 저장
			if ($token != "") {
				$info["token"] = $token;
			}

			$result = $this->member_model->setMemberDevice($info, ["uuid" => $uuid]);

			// FCM 토큰이 있고 푸시 동의가 Y인 경우 MongoDB에도 등록
			if ($token != "" && $push_yn == "Y") {
				$mem_device = $this->member_model->getMemberDevice(["uuid" => $uuid]);
				if ($mem_device && $mem_device["mm_idx"] > 0) {
					// 이미 사용자와 연결된 경우 MongoDB에 동기화
					$mem_info = $this->member_model->getMember(["mm_idx" => $mem_device["mm_idx"]]);
					if ($mem_info) {
						$mongo_info = [
							'mm_id' => $mem_info["mm_id"],
							'token' => $token,
							'last_update' => class_exists('MongoDB\BSON\UTCDateTime') ? new MongoDB\BSON\UTCDateTime() : new MongoDate()
						];
						$mongo_result = $this->mongo_model->updateUserInfo($mongo_info);
					}
				} else {
					// 사용자와 연결되지 않은 경우, 나중에 연결할 수 있도록 토큰만 저장
				}
			}
		}

		json_return("00", "성공");
	}

	/**
	 * 테스트용 FCM 토큰 등록 (현재 미사용)
	 * 
	 * 용도: 로그인 없이 FCM 토큰을 테스트로 등록하는 함수
	 * 상태: 로그 분석 결과 실제 호출되지 않음
	 * 
	 * @return void
	 */
	function test_token()
	{
		$token = input_post("token", "");
		$uuid = input_post("uuid", "");

		if ($token == "") {
			json_return("90", "FCM 토큰이 필요합니다.");
			return;
		}

		if ($uuid == "") {
			json_return("90", "UUID가 필요합니다.");
			return;
		}

		try {
			// MySQL에 토큰 저장
			$info = [
				"token" => $token,
				"push_yn" => "Y",
				"up_dt" => date('Y-m-d H:i:s')
			];
			$result = $this->member_model->setMemberDevice($info, ["uuid" => $uuid]);

			if ($result) {
				json_return("00", "테스트 FCM 토큰이 성공적으로 등록되었습니다.");
			} else {
				json_return("90", "테스트 FCM 토큰 등록에 실패했습니다.");
			}
		} catch (Exception $e) {
			json_return("90", "테스트 FCM 토큰 등록 중 오류가 발생했습니다.");
		}
	}

	/**
	 * FCM 토큰 등록/갱신 (실제 사용 중)
	 * 
	 * 용도: 안드로이드/iOS 네이티브 앱에서 FCM 토큰을 서버에 등록/갱신하는 함수
	 * 호출: 네이티브 앱이 직접 HTTP API로 호출 (웹뷰 거치지 않음)
	 * 처리:
	 *   - MySQL st_member_device 테이블에 토큰 저장
	 *   - 로그인 상태면 MongoDB에도 동기화
	 *   - Socket.IO 서버에도 토큰 전송
	 * 
	 * @return void
	 */
	function update_token()
	{
		// 세션 강제 시작 (앱에서 세션 문제 해결)
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		// User 객체가 없으면 다시 로드 시도
		if (!isset($this->user) || !is_object($this->user)) {
			$this->load->library('user');
		}

		// User 객체 초기화 상태 확인
		$user_object_exists = isset($this->user) && is_object($this->user);
		$is_login_result = $user_object_exists ? $this->user->isLogin() : 'user_object_not_found';
		$mm_idx = $user_object_exists ? $this->user->getUserIdx() : -999;

		$token = input_post("token", "");
		$uuid = input_post("uuid", "");
		$app_mm_idx = input_post("mm_idx", 0);

		// OS 타입 감지 (iOS/Android)
		$os_type = "";
		$headers = apache_request_headers();
		if (isset($headers["os_type"]) && $headers["os_type"] != "") {
			$os_type = strtolower($headers["os_type"]);
		} else {
			// User-Agent로 OS 타입 감지
			$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
			if (preg_match('/nowjobswift|CFNetwork|Darwin/i', $user_agent) || preg_match('/\b(iPhone|iPad|iPod)\b/i', $user_agent)) {
				$os_type = 'ios';
			} else if (preg_match('/NowJobApp_Android|Android/i', $user_agent)) {
				$os_type = 'android';
			}
		}

		// 세션 정보 확인
		$session_user_info = $this->common->getSession("USER_INFO");

		// 앱에서는 UUID로 사용자 찾기 (세션 의존하지 않음)
		if ($uuid != "") {
			$device_info = $this->member_model->getMemberDevice(["uuid" => $uuid]);

			if ($device_info && $device_info["mm_idx"] > 0) {
				$mm_idx = $device_info["mm_idx"];
			} else {
				// UUID로 사용자를 찾을 수 없으면 현재 로그인한 사용자에게 연결
				if ($session_user_info && isset($session_user_info["mm_idx"]) && $session_user_info["mm_idx"] > 0) {
					$mm_idx = $session_user_info["mm_idx"];

					// UUID를 현재 사용자에게 연결
					$update_data = [
						"mm_idx" => $mm_idx,
						"push_yn" => "Y",
						"up_dt" => date('Y-m-d H:i:s')
					];
					$update_result = $this->member_model->setMemberDevice($update_data, ["uuid" => $uuid]);
				} else {
					// 세션에서 사용자 정보를 가져올 수 없으면, 앱에서 전송한 사용자 정보를 사용
					$app_mm_idx = input_post("mm_idx", 0);
					if ($app_mm_idx > 0) {
						$mm_idx = $app_mm_idx;

						// UUID를 앱에서 전송한 사용자에게 연결
						$update_data = [
							"mm_idx" => $mm_idx,
							"push_yn" => "Y",
							"up_dt" => date('Y-m-d H:i:s')
						];
						$update_result = $this->member_model->setMemberDevice($update_data, ["uuid" => $uuid]);
					}
				}
			}
		}
		// 세션으로 사용자 찾기 (웹에서만 사용)
		else if ($session_user_info && isset($session_user_info["mm_idx"]) && $session_user_info["mm_idx"] > 0) {
			$mm_idx = $session_user_info["mm_idx"];
		}

		// UUID가 있으면 토큰 업데이트 진행 (로그아웃 상태도 허용)
		if ($mm_idx <= 0 && $uuid == "") {
			json_return("90", "UUID가 필요합니다.");
			return;
		}

		// UUID가 있으면 로그인 상태와 무관하게 토큰 등록 허용
		if ($uuid != "" && $token != "") {
			// UUID로 디바이스 정보 업데이트 (로그인 상태 무관)
			$device_info = $this->member_model->getMemberDevice(["uuid" => $uuid]);
			if ($device_info) {
				// 토큰만 업데이트하고 사용자 연결은 나중에 처리
				$update_data = [
					"token" => $token,
					"push_yn" => "Y",
					"up_dt" => date('Y-m-d H:i:s')
				];

				// os_type이 있으면 업데이트 (기존 값이 없거나 변경된 경우)
				if ($os_type != "") {
					$update_data["os_type"] = $os_type;
				}

				// mm_idx 처리: 로그인한 사용자가 토큰을 업데이트할 때 mm_idx = 0인 경우 자동 연결
				if ($device_info["mm_idx"] == 0 && $mm_idx > 0) {
					// 로그인한 사용자가 mm_idx = 0인 디바이스를 업데이트하면 자동 연결
					$update_data["mm_idx"] = $mm_idx;
				} else if ($device_info["mm_idx"] == 0) {
					// 로그인하지 않은 경우 그대로 0 유지
					$update_data["mm_idx"] = 0;
				} else {
					// 기존 사용자와 연결 유지
					$update_data["mm_idx"] = $device_info["mm_idx"];
				}

				$result = $this->member_model->setMemberDevice($update_data, ["uuid" => $uuid]);

				// 사용자가 연결되어 있으면 MongoDB에도 동기화 (업데이트된 mm_idx 사용)
				$final_mm_idx = isset($update_data["mm_idx"]) ? $update_data["mm_idx"] : $device_info["mm_idx"];
				if ($final_mm_idx > 0) {
					$mem_info = $this->member_model->getMember(["mm_idx" => $final_mm_idx]);
					if ($mem_info) {
						$mongo_info = [
							'mm_id' => $mem_info["mm_id"],
							'token' => $token,
							'last_update' => class_exists('MongoDB\BSON\UTCDateTime') ? new MongoDB\BSON\UTCDateTime() : new MongoDate()
						];
						$mongo_result = $this->mongo_model->updateUserInfo($mongo_info);
					}
				}

				json_return("00", "FCM 토큰이 성공적으로 등록되었습니다.");
				return;
			} else {
				// 디바이스 정보가 없으면 새로 생성 (iOS/Android 모두 동일하게 처리)
				// 새 디바이스 정보 생성
				$new_device_data = [
					"uuid" => $uuid,
					"token" => $token,
					"push_yn" => "Y",
					"mm_idx" => $mm_idx > 0 ? $mm_idx : 0, // 로그인 상태면 mm_idx 설정, 아니면 0
					"reg_dt" => date('Y-m-d H:i:s'),
					"up_dt" => date('Y-m-d H:i:s')
				];

				// os_type이 감지되었으면 저장
				if ($os_type != "") {
					$new_device_data["os_type"] = $os_type;
				}

				$insert_result = $this->member_model->insertMemberDevice($new_device_data);

				if ($insert_result && isset($insert_result['code']) && $insert_result['code'] == '00') {
					// 사용자가 연결되어 있으면 MongoDB에도 동기화
					if ($mm_idx > 0) {
						$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);
						if ($mem_info) {
							$mongo_info = [
								'mm_id' => $mem_info["mm_id"],
								'token' => $token,
								'last_update' => class_exists('MongoDB\BSON\UTCDateTime') ? new MongoDB\BSON\UTCDateTime() : new MongoDate()
							];
							$mongo_result = $this->mongo_model->updateUserInfo($mongo_info);
						}
					}

					json_return("00", "FCM 토큰이 성공적으로 등록되었습니다.");
					return;
				} else {
					// 생성 실패해도 아래 로직으로 계속 진행
				}
			}
		}

		// UUID로 찾은 사용자가 실제로 로그인된 상태인지 확인 (기존 로직 유지)
		$member_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);
		if (!$member_info || $member_info["mm_status"] != "Y") {
			json_return("90", "사용자 정보를 찾을 수 없습니다.");
			return;
		}

		// UUID로 디바이스 정보 업데이트 (로그인된 사용자의 디바이스에 토큰 저장)
		if ($uuid != "") {
			$device_info = $this->member_model->getMemberDevice(["uuid" => $uuid]);
			if ($device_info && $device_info["mm_idx"] == $mm_idx) {
				// 디바이스 확인 완료
			} else if ($device_info && $device_info["mm_idx"] != $mm_idx) {
				// 현재 사용자에게 UUID 재할당
				$this->member_model->setMemberDevice(["mm_idx" => $mm_idx], ["uuid" => $uuid]);
			}
		}

		if ($token == "") {
			json_return("90", "FCM 토큰이 필요합니다.");
			return;
		}

		// FCM 토큰 유효성 검증 (더 관대하게 수정)
		if (strlen($token) < 50) {
			json_return("90", "유효하지 않은 FCM 토큰입니다.");
			return;
		}

		// FCM 토큰 형식 검증 (Firebase FCM 토큰은 보통 163자 이상)
		if (!preg_match('/^[A-Za-z0-9:_-]+$/', $token)) {
			json_return("90", "올바르지 않은 FCM 토큰 형식입니다.");
			return;
		}

		try {
			// 기존 토큰과 중복 확인
			$existing_device = $this->member_model->getMemberDevice(["token" => $token]);
			if ($existing_device && $existing_device["mm_idx"] != $mm_idx) {
				json_return("90", "이미 다른 사용자가 사용 중인 토큰입니다.");
				return;
			}

			// MySQL에 토큰 저장
			$mysql_success = false;
			if ($uuid != "") {
				$info = [
					"token" => $token,
					"push_yn" => "Y", // 토큰 등록 시 푸시 동의로 설정
					"up_dt" => date('Y-m-d H:i:s')
				];

				// os_type이 있으면 업데이트 (기존 값이 없거나 변경된 경우)
				if ($os_type != "") {
					$info["os_type"] = $os_type;
				}

				$result = $this->member_model->setMemberDevice($info, ["uuid" => $uuid]);
				$mysql_success = $result;
			}

			// MongoDB에 토큰 저장
			$mongo_success = false;
			$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);
			if ($mem_info) {
				$mongo_info = [
					'mm_id' => $mem_info["mm_id"],
					'token' => $token,
					'last_update' => class_exists('MongoDB\BSON\UTCDateTime') ? new MongoDB\BSON\UTCDateTime() : new MongoDate()
				];
				$mongo_result = $this->mongo_model->updateUserInfo($mongo_info);
				$mongo_success = $mongo_result;
			}

			// Socket.IO 서버에도 토큰 등록 (chat_server.js의 update_token 이벤트와 동일)
			$socket_success = false;
			try {
				$socket_data = [
					"user_id" => $mem_info["mm_id"],
					"token" => $token
				];

				$socket_url = "https://nowjob.co.kr:3030";
				$socket_data_json = json_encode($socket_data);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $socket_url . "/update_token");
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $socket_data_json);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					'Content-Type: application/json',
					'Content-Length: ' . strlen($socket_data_json)
				]);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 5);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				$socket_result = curl_exec($ch);
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);

				$socket_success = ($http_code == 200);
			} catch (Exception $socket_error) {
				// Socket.IO 토큰 등록 에러는 무시하고 계속 진행
			}

			// 최소 하나라도 성공하면 성공으로 처리 (chat_server.js의 로직과 동일)
			if ($mysql_success || $mongo_success) {
				json_return("00", "FCM 토큰이 성공적으로 등록되었습니다.");
			} else {
				json_return("90", "FCM 토큰 등록에 실패했습니다.");
			}
		} catch (Exception $e) {
			json_return("90", "FCM 토큰 등록 중 오류가 발생했습니다.");
		}
	}

	/**
	 * FCM 토큰을 로그인한 사용자와 연결 (거의 사용되지 않음)
	 * 
	 * 용도: 로그인 전에 등록된 FCM 토큰을 로그인한 사용자(mm_idx)와 연결하는 함수
	 * 상태: 
	 *   - 로그 분석 결과 호출은 되지만 대부분 "FCM 토큰이 없음" 또는 "로그인 필요" 오류로 실패
	 *   - update_token() 함수가 이미 사용자 연결 기능을 포함하고 있어 중복 기능
	 *   - update_token()에서 mm_idx=0인 디바이스를 로그인 사용자에게 자동 연결함 (376-391줄)
	 * 호출: 로그인 성공 후 웹뷰 JavaScript에서 호출 시도 (window.connectFCMTokenToUser)
	 * 
	 * @return void
	 */
	function connect_token_to_user()
	{
		// 세션 강제 시작
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		// User 객체가 없으면 다시 로드 시도
		if (!isset($this->user) || !is_object($this->user)) {
			$this->load->library('user');
		}

		$uuid = input_post("uuid", "");

		// 로그인 확인
		if ($this->user->isLogin() == false) {
			json_return("90", "로그인이 필요합니다.");
			return;
		}

		if ($uuid == "") {
			json_return("90", "UUID가 필요합니다.");
			return;
		}

		$mm_idx = $this->user->getUserIdx();

		try {
			// UUID로 디바이스 정보 조회
			$device_info = $this->member_model->getMemberDevice(["uuid" => $uuid]);
			if (!$device_info) {
				json_return("90", "디바이스 정보를 찾을 수 없습니다.");
				return;
			}

			// 토큰이 있는지 확인
			if (empty($device_info["token"])) {
				json_return("90", "FCM 토큰이 등록되지 않았습니다.");
				return;
			}

			// UUID를 현재 로그인한 사용자에게 연결 (기존 update_token 로직 활용)
			if ($device_info && $device_info["mm_idx"] == $mm_idx) {
				// 이미 연결되어 있음
			} else if ($device_info && $device_info["mm_idx"] == 0) {
				// mm_idx=0 디바이스를 현재 사용자에게 연결
				$update_data = [
					"mm_idx" => $mm_idx,
					"push_yn" => "Y",
					"up_dt" => date('Y-m-d H:i:s')
				];
				$result = $this->member_model->setMemberDevice($update_data, ["uuid" => $uuid]);
			} else if ($device_info && $device_info["mm_idx"] != $mm_idx) {
				// 현재 사용자에게 UUID 재할당
				$update_data = [
					"mm_idx" => $mm_idx,
					"push_yn" => "Y",
					"up_dt" => date('Y-m-d H:i:s')
				];
				$result = $this->member_model->setMemberDevice($update_data, ["uuid" => $uuid]);
			} else {
				json_return("90", "디바이스 정보를 찾을 수 없습니다.");
				return;
			}

			// MongoDB에도 토큰 동기화
			$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);
			if ($mem_info) {
				$mongo_info = [
					'mm_id' => $mem_info["mm_id"],
					'token' => $device_info["token"],
					'last_update' => class_exists('MongoDB\BSON\UTCDateTime') ? new MongoDB\BSON\UTCDateTime() : new MongoDate()
				];
				$mongo_result = $this->mongo_model->updateUserInfo($mongo_info);
			}

			json_return("00", "FCM 토큰이 로그인한 사용자와 성공적으로 연결되었습니다.");
		} catch (Exception $e) {
			json_return("90", "FCM 토큰 연결 중 오류가 발생했습니다.");
		}
	}

	/**
	 * FCM 토큰 갱신 및 동기화 (현재 미사용)
	 * 
	 * 용도: 앱에서 주기적으로 FCM 토큰을 갱신하고 MySQL/MongoDB/Socket.IO에 동기화하는 함수
	 * 상태: 로그 분석 결과 실제 호출되지 않음 (update_token으로 대체)
	 * 
	 * @return void
	 */
	function refresh_token()
	{
		$mm_idx = $this->user->getUserIdx();
		$token = input_post("token", "");
		$uuid = input_post("uuid", "");

		// 로그인 확인
		if ($this->user->isLogin() == false) {
			json_return("90", "로그인이 필요합니다.");
			return;
		}

		if ($token == "") {
			json_return("90", "FCM 토큰이 필요합니다.");
			return;
		}

		// FCM 토큰 유효성 검증
		if (strlen($token) < 100) {
			json_return("90", "유효하지 않은 FCM 토큰입니다.");
			return;
		}

		try {
			// 기존 토큰과 비교하여 변경사항 확인
			$existing_device = $this->member_model->getMemberDevice(["uuid" => $uuid]);
			if ($existing_device && $existing_device["token"] == $token) {
				json_return("00", "토큰이 이미 최신 상태입니다.");
				return;
			}

			// 토큰 갱신 (update_token과 동일한 로직)
			$mysql_success = false;
			if ($uuid != "") {
				$info = [
					"token" => $token,
					"push_yn" => "Y",
					"up_dt" => date('Y-m-d H:i:s')
				];
				$result = $this->member_model->setMemberDevice($info, ["uuid" => $uuid]);
				$mysql_success = $result;
			}

			// MongoDB 토큰 갱신
			$mongo_success = false;
			$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);
			if ($mem_info) {
				$mongo_info = [
					'mm_id' => $mem_info["mm_id"],
					'token' => $token,
					'last_update' => class_exists('MongoDB\BSON\UTCDateTime') ? new MongoDB\BSON\UTCDateTime() : new MongoDate()
				];
				$mongo_result = $this->mongo_model->updateUserInfo($mongo_info);
				$mongo_success = $mongo_result;
			}

			// Socket.IO 서버에도 토큰 갱신
			$socket_success = false;
			try {
				$socket_data = [
					"user_id" => $mem_info["mm_id"],
					"token" => $token
				];

				$socket_url = "https://nowjob.co.kr:3030";
				$socket_data_json = json_encode($socket_data);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $socket_url . "/update_token");
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $socket_data_json);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					'Content-Type: application/json',
					'Content-Length: ' . strlen($socket_data_json)
				]);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 5);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				$socket_result = curl_exec($ch);
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);

				$socket_success = ($http_code == 200);
			} catch (Exception $socket_error) {
				// Socket.IO 토큰 갱신 에러는 무시하고 계속 진행
			}

			// 최소 하나라도 성공하면 성공으로 처리
			if ($mysql_success || $mongo_success) {
				json_return("00", "FCM 토큰이 성공적으로 갱신되었습니다.");
			} else {
				json_return("90", "FCM 토큰 갱신에 실패했습니다.");
			}
		} catch (Exception $e) {
			json_return("90", "FCM 토큰 갱신 중 오류가 발생했습니다.");
		}
	}

	/**
	 * UUID로 기존 동의 정보 조회 (현재 미사용)
	 * 
	 * 용도: UUID로 저장된 푸시/마케팅 동의 정보를 조회하는 함수
	 * 상태: 로그 분석 결과 실제 호출되지 않음
	 * 
	 * @return void
	 */
	function get_uuid_consent()
	{
		$uuid = input_post("uuid", "");

		if ($uuid != "") {
			$mem_device = $this->member_model->getMemberDevice(["uuid" => $uuid]);

			if ($mem_device != null) {
				// 이미 동의 정보가 있는 경우
				$result = [
					"has_consent" => true,
					"push_yn" => $mem_device["push_yn"],
					"marketing_yn" => $mem_device["marketing_yn"],
					"md_step2" => $mem_device["md_step2"]
				];
			} else {
				// 동의 정보가 없는 경우
				$result = [
					"has_consent" => false,
					"push_yn" => "N",
					"marketing_yn" => "N",
					"md_step2" => "N"
				];
			}
		} else {
			$result = [
				"has_consent" => false,
				"push_yn" => "N",
				"marketing_yn" => "N",
				"md_step2" => "N"
			];
		}

		json_return("00", "성공", $result);
	}


	# 즐겨찾기 처리
	function bookmark()
	{
		try {
			$bk_type = input_post("bk_type", "");
			$bk_key = input_post("bk_key", "");
			$mm_idx = $this->user->getUserIdx();
			$mm_id = $this->user->getUserId();
			$mm_name = $this->user->getUserName();

			// 로그인 체크
			if ($this->user->isLogin() == false) {
				json_return("10", "로그인후 이용해주세요.");
			}

			// 파라미터 검증
			if (empty($bk_type) || empty($bk_key) || empty($mm_idx)) {
				json_return("90", "필수 파라미터가 누락되었습니다.");
			}

			// 즐겨찾기 처리
			$result = $this->site_model->setBookmark(["bk_type" => $bk_type, "bk_key" => $bk_key, "mm_idx" => $mm_idx]);

			if ($result == "Y") {
				try {
					$book_user_info = $this->site_model->getBookmarkUser(["bk_type" => $bk_type, "bk_key" => $bk_key]);

					if ($book_user_info && !empty($book_user_info["mm_id"])) {
						#####################################################
						# 푸시 발송
						#####################################################
						$temp = $book_user_info;
						$token_list = [];
						$yoil = get_yoil(get_today());
						$time = get_today("H:i");

						$skip = false;
						if (!empty($temp["token"]) && $temp["alarm_deny_yn"] == "Y" && $temp["alarm_deny_yoil"] == $yoil && ($temp["alarm_deny_stime"] < $time && $temp["alarm_deny_etime"] > $time)) {
							$skip = true;
						}

						if ($skip == false && !empty($temp["token"])) {
							$token_list[] = $temp["token"];
						}

						if (!empty($token_list)) {
							$info = [
								"title" => "NowJob 관심등록 알림",
								"body" => $mm_name . "님이 즐겨찾기 등록 했습니다.",
								"img" => "",
								"url" => "",
							];
							$fcm_result = sendFcm($info, $token_list);
						}
						#####################################################

						#####################################################
						# 알림 등록 (MongoDB 에러 방지)
						#####################################################
						try {
							$tbl_alarm = get_mongodb("alarm");

							if ($tbl_alarm) {
								//알림 추가
								$alarm = [
									"user_id" => $book_user_info["mm_id"],
									"al_type" => "bookmark",
									"al_key" => "" . $mm_idx,
									"al_text" => "<strong><em>" . $mm_name . "</em>님이 <em>즐겨찾기 등록</em>했습니다.</strong>",
									"al_etc" => "",
									"al_url" => "",
									"show_yn" => "N",
									"reg_dt" => get_today(),
								];

								$tbl_alarm->insert($alarm);
							}
						} catch (Exception $mongo_error) {
							// MongoDB 에러는 무시하고 계속 진행
						}
						#####################################################
					}
				} catch (Exception $push_error) {
					// 푸시/알림 에러는 무시하고 계속 진행
				}
			}

			json_return("00", "처리완료", ["yn" => $result, "user_name" => $mm_name]);
		} catch (Exception $e) {
			json_return("90", "처리 중 오류가 발생했습니다.");
		}
	}

	function update_alarm_show()
	{
		$user_id = $this->user->getUserId();

		# 미확인 업데이트
		if ($user_id != "") {
			$tbl_alarm = get_mongodb("alarm");
			// 읽음 처리 시점 저장 (show_dt 필드 추가)
			$tbl_alarm->update(
				["user_id" => $user_id, "show_yn" => "N"],
				['$set' => ['show_yn' => "Y", 'show_dt' => get_today()]],
				['upsert' => true, 'multiple' => true]
			);
		}

		json_return("00", "조회성공");
	}

	function get_member_info()
	{
		$mm_idx = $this->user->getUserIdx();

		# 로그인 유무 확인
		if ($this->user->isLogin() == false) {
			json_return("90", "로그인이 필요합니다.");
		}

		$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

		if ($mem_info == null) {
			json_return("90", "로그인후 이용해주세요.");
		} else {
			json_return("00", "조회 성공", $mem_info);
		}
	}

	// splash 화면에서 로그인 상태 및 회원 유형 확인
	function get_login_status()
	{
		$is_login = $this->user->isLogin();

		if (!$is_login) {
			// 로그인 안됨
			json_return("00", "미로그인", array(
				"is_login" => false,
				"member_type" => null
			));
		} else {
			// 로그인됨 - 회원 유형 확인
			$mm_idx = $this->user->getUserIdx();
			$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

			if ($mem_info == null) {
				json_return("00", "미로그인", array(
					"is_login" => false,
					"member_type" => null
				));
			} else {
				$member_type = isset($mem_info["mm_type"]) && $mem_info["mm_type"] == "COMP" ? "COMP" : "MEM";
				json_return("00", "로그인됨", array(
					"is_login" => true,
					"member_type" => $member_type
				));
			}
		}
	}

	// 제안/지원 대상(idx)의 상대방 mm_idx를 알아낸다.
	// RECRUIT 타입: idx=rc_idx(지원 대상 공고) → 공고 소유자
	// RESUME  타입: idx=ru_idx(제안 대상 인재) → 이력서 소유자
	private function get_opponent_mm_idx($mq_type, $idx)
	{
		if ($mq_type == "RECRUIT") {
			$row = $this->db->query("SELECT mm_idx FROM st_recruit WHERE rc_idx = ?", [$idx])->row_array();
		} else if ($mq_type == "RESUME") {
			$row = $this->db->query("SELECT mm_idx FROM st_resume WHERE ru_idx = ?", [$idx])->row_array();
		} else {
			$row = null;
		}
		return ($row && !empty($row["mm_idx"])) ? $row["mm_idx"] : null;
	}

	// 나(my_mm_idx)와 상대방(opponent_mm_idx) 사이에 이미 존재하는 recruit 타입 방을 찾는다.
	// 방에 연결된 공고 주인/이력서 주인의 쌍으로 판별하며, 정식(지원/제안)과 다이렉트(채팅하기)를
	// 구분하지 않는다 — 같은 기업↔개인 쌍은 방 하나를 쓴다. (더미 공고/이력서도 소유자가 같으므로 동일 쌍으로 매칭됨)
	// mq_type은 방향(내가 공고 주인인지, 이력서 주인인지) 판정에만 쓴다.
	private function find_existing_room_by_opponent($my_mm_idx, $opponent_mm_idx, $mq_type)
	{
		if (empty($my_mm_idx) || empty($opponent_mm_idx) || $my_mm_idx == $opponent_mm_idx) {
			return null; // 자기 자신과의 매칭 방지, 신규 생성 경로로 폴백
		}

		if (strpos($mq_type, "RESUME") === 0) {
			// 제안/기업 채팅하기: 나=공고 주인, 상대=이력서 주인
			return $this->member_model->findRecruitRoomByPair($my_mm_idx, $opponent_mm_idx);
		} else if (strpos($mq_type, "RECRUIT") === 0) {
			// 지원/개인 채팅하기: 나=이력서 주인, 상대=공고 주인
			return $this->member_model->findRecruitRoomByPair($opponent_mm_idx, $my_mm_idx);
		}
		return null;
	}

	// 채용(recruit) 채팅방 신규 생성: MySQL st_room 등록 + MongoDB 방 생성(Node /newroommake) + exit 상태 리셋
	// 정식 요청(member_request)과 다이렉트 채팅(_start_direct_chat)이 함께 사용한다. 성공 시 rm_idx, 실패 시 null
	private function _create_recruit_room($rc_idx, $ru_idx, $mm_idx, $direct_yn = "N")
	{
		$info_chat = [
			"rm_idx" => '',
			"rc_idx" => $rc_idx,
			"ru_idx" => $ru_idx,
			"td_idx" => 0,
			"mm_idx" => $mm_idx,
			"point_yn" => 'N',
			"direct_yn" => $direct_yn, // 다이렉트 채팅 이력 표시 ("이미 채팅중" 판단용)
		];

		$result_chat = $this->common_model->insertRoom($info_chat);
		if (!$result_chat || $result_chat["code"] != "00") {
			return null;
		}

		$rm_idx = $result_chat["key"];
		$current_user_id = $this->user->getUserId();

		$mongo_room_data = [
			"rm_idx" => (int)$rm_idx,
			"rc_idx" => (int)$rc_idx,
			"ru_idx" => (int)$ru_idx,
			"rm_type" => "recruit",
			"rm_img" => "",
			"rm_subject" => "채용공고 채팅방",
			"rm_desc" => "채용공고 관련 채팅방",
			"rm_last_msg" => "",
			"rm_last_dt" => date('Y-m-d H:i:s'),
			"rm_users" => [
				["user_id" => $current_user_id] // 현재 사용자
			],
			"user_info" => [
				["user_id" => $current_user_id]
			],
			"rm_status" => "N",
			"rm_messages" => []
		];

		// MongoDB 채팅방 생성 API 호출 (실패해도 chat_view 진입 시 소켓 room_info에서 자동 생성됨)
		try {
			$is_dev_env = defined('ENVIRONMENT') && ENVIRONMENT === 'development';
			$chat_server_url = $is_dev_env
				? "http://127.0.0.1:3031/newroommake"
				: "https://127.0.0.1:3030/newroommake";

			$ch = curl_init($chat_server_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mongo_room_data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json'
			]);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_exec($ch);
			curl_close($ch);
		} catch (Exception $e) {
			// error_log("[DEBUG] _create_recruit_room newroommake cURL 예외: " . $e->getMessage());
		}

		// 신규 방 생성 직후 exit_yn을 리셋해야 목록에 보임 (기존 방 재사용 시와 동일)
		$this->_reset_room_exit_status($rm_idx);

		return $rm_idx;
	}

	// 이 방에서 이미 채팅하기를 했고(direct_yn='Y') 요청한 내가 방을 나가지 않은 상태인지 ("이미 채팅중" 판단)
	private function _is_already_direct_chatting($rm_idx)
	{
		if (empty($rm_idx)) {
			return false;
		}
		$room = $this->common_model->getRoom(["rm_idx" => $rm_idx]);
		if (!$room || !isset($room["direct_yn"]) || $room["direct_yn"] != "Y") {
			return false;
		}
		$exited = get_exited_rm_idxs(array($rm_idx), $this->user->getUserId(), $this->user->getUserPhone());
		return empty($exited);
	}

	// 채팅하기 사전 확인 (읽기 전용 — 방 생성/기록 없음)
	// 확인 팝업을 띄우기 전에 "이미 채팅중"인 상대인지 먼저 알려준다.
	// 응답: { already_chatting: "Y"|"N", rm_idx }
	function direct_chat_check()
	{
		if ($this->user->isLogin() == false) {
			json_return("90", "로그인후 이용해주세요.");
		}

		$mq_type = input_post("mq_type");
		$target_idx = input_post("target_idx");
		$mm_idx = $this->user->getUserIdx();
		if (empty($target_idx) || empty($mm_idx)) {
			json_return("90", "요청 데이터가 올바르지 않습니다.");
		}

		// 상대방 mm_idx로 기업↔개인 쌍 결정 (RECRUIT: 개인이 공고에서 / RESUME: 기업이 이력서에서)
		$opponent_mm_idx = $this->get_opponent_mm_idx($mq_type, $target_idx);
		$rm_idx = $this->find_existing_room_by_opponent($mm_idx, $opponent_mm_idx, $mq_type);

		$already_chatting = ($rm_idx && $this->_is_already_direct_chatting($rm_idx)) ? "Y" : "N";
		json_return("00", "조회성공", [
			"already_chatting" => $already_chatting,
			"rm_idx" => ($already_chatting == "Y") ? $rm_idx : "",
		]);
	}

	// 다이렉트 채팅(채팅하기) 시작
	// - st_member_request에 기록하지 않는 "순수 채팅" (포인트 차감/푸시 없음)
	// - 방의 rc/ru: 상대 것은 지금 보고 있는 실제 공고/이력서, 내 것은 실제가 있으면 실제, 없을 때만 더미
	// - 같은 쌍의 방이 있으면 재사용 (rc/ru는 덮어쓰지 않음), 없으면 신규 생성
	// - already_chatting: 이 방에서 이미 채팅하기를 했고(direct_yn='Y') 내가 나가지 않은 상태면 "Y"
	private function _start_direct_chat($mq_type, $mq_idxs, $mm_idx)
	{
		$target_idx = (is_array($mq_idxs) && isset($mq_idxs[0])) ? $mq_idxs[0] : null;
		if (empty($target_idx)) {
			json_return("90", "요청 데이터가 올바르지 않습니다.");
		}

		if ($mq_type == "RESUME") {
			// 기업회원이 이력서(hr_post)에서 시작: 대상 = 보고 있는 이력서
			$target_row = $this->db->query("SELECT ru_idx, mm_idx FROM st_resume WHERE ru_idx = ?", array($target_idx))->row_array();
			if (!$target_row) {
				json_return("90", "대상 회원 정보를 찾을 수 없습니다.");
			}
			$comp_mm_idx = $mm_idx;
			$mem_mm_idx = $target_row["mm_idx"];
			$ru_idx = $target_row["ru_idx"];
			$rc_idx = $this->member_model->getChatRecruitIdx($comp_mm_idx);
			if (empty($rc_idx)) {
				// 진행중인 실제 공고가 없을 때만 더미 공고 사용
				$rc_idx = $this->member_model->insertDirectChatRecruit($comp_mm_idx);
			}
		} else if ($mq_type == "RECRUIT") {
			// 개인회원이 공고(job_post)에서 시작: 대상 = 보고 있는 공고
			$target_row = $this->db->query("SELECT rc_idx, mm_idx FROM st_recruit WHERE rc_idx = ?", array($target_idx))->row_array();
			if (!$target_row) {
				json_return("90", "대상 회원 정보를 찾을 수 없습니다.");
			}
			$comp_mm_idx = $target_row["mm_idx"];
			$mem_mm_idx = $mm_idx;
			$rc_idx = $target_row["rc_idx"];
			$ru_idx = $this->member_model->getChatResumeIdx($mem_mm_idx);
			if (empty($ru_idx)) {
				// 실제 이력서가 없을 때만 더미 이력서 사용
				$ru_idx = $this->member_model->insertDirectChatResume($mem_mm_idx);
			}
		} else {
			json_return("90", "요청 타입이 올바르지 않습니다.");
		}

		if (empty($rc_idx) || empty($ru_idx)) {
			json_return("99", "다이렉트 채팅 준비 중 오류가 발생했습니다.");
		}
		if ($comp_mm_idx == $mem_mm_idx) {
			json_return("90", "본인과는 채팅할 수 없습니다.");
		}

		$already_chatting = "N";
		$rm_idx = $this->member_model->findRecruitRoomByPair($comp_mm_idx, $mem_mm_idx);

		if ($rm_idx) {
			// 기존 방 재사용: 이미 채팅하기를 한 방이고 내가 나가지 않았으면 "이미 채팅중"
			if ($this->_is_already_direct_chatting($rm_idx)) {
				$already_chatting = "Y";
			}

			// 방의 공고/이력서(정식 정보일 수 있음)는 덮어쓰지 않고, 다이렉트 이력 표시와 갱신일만 반영
			$this->common_model->updateRoom(
				["direct_yn" => "Y", "up_dt" => get_today()],
				["rm_idx" => $rm_idx]
			);
			// 나갔던 참여자 복구 (기존 다이렉트 채팅 동작과 동일)
			$this->_reset_room_exit_status($rm_idx);
		} else {
			$rm_idx = $this->_create_recruit_room($rc_idx, $ru_idx, $mm_idx, "Y");
			if (empty($rm_idx)) {
				json_return("99", "채팅방 생성 중 오류가 발생했습니다.");
			}
		}

		json_return("00", "처리완료", [
			"rm_idx" => $rm_idx,
			"rc_idx" => $rc_idx,
			"ru_idx" => $ru_idx,
			"mq_type" => $mq_type,
			"already_chatting" => $already_chatting,
		]);
	}

	function member_request()
	{
		try {
			// 출력 버퍼 시작
			ob_start();

			$mq_type = input_post("mq_type");
			$mq_idxs = input_post("mq_idxs"); // [rc_idx] 배열 or [ru_idx] 배열 mq_idxs: [12345]
			$rc_idx = input_post("rc_idx");
			$is_direct_chat_param = input_post("is_direct_chat");
			$is_direct_chat = ($is_direct_chat_param === "Y"); // 다이렉트 채팅 여부 (기본 false)

			$mm_idx = $this->user->getUserIdx();

			// 입력값 검증
		
			if (empty($mq_type)) {
			
				json_return("90", "요청 타입이 올바르지 않습니다.");
			}

			if (empty($mq_idxs) || !is_array($mq_idxs)) {
			
				json_return("90", "요청 데이터가 올바르지 않습니다.");
			}

			if (empty($mm_idx)) {
			
				json_return("90", "사용자 정보가 올바르지 않습니다.");
			}

			# 로그인 유무 확인
		
			if ($this->user->isLogin() == false) {
			
				json_return("90", "로그인후 이용해주세요.");
			}

			# 다이렉트 채팅(채팅하기): 지원/제안 기록 없이 순수 채팅방만 연결하고 바로 응답 (포인트/푸시 없음)
			if ($is_direct_chat) {
				$this->_start_direct_chat($mq_type, $mq_idxs, $mm_idx);
				return;
			}

			# rc_idx 유효성 검사 (RESUME 타입일 때)
			if ($mq_type == "RESUME" && (empty($rc_idx) || $rc_idx == "")) {
				
				json_return("90", "채용공고를 선택해주세요.");
			}

			# 채용공고 존재 여부만 확인 (소유권 검증 제거 - 다른 사용자 채용공고에 제안하는 것이 정상)
			if ($mq_type == "RESUME" && !empty($rc_idx)) {
			
				$sql = "SELECT mm_idx FROM st_recruit WHERE rc_idx = '$rc_idx' AND rc_status = 'Y'";
				$query = $this->db->query($sql);
				$recruit_info = $query->row_array();

				if (!$recruit_info) {
					
					json_return("90", "선택한 채용공고가 존재하지 않습니다.");
				}
			}

			# 이력서 정보 가져오기
		
			$resume = null;
			if ($mq_type == "RESUME") {
				$resume = $this->member_model->getResume(["mm_idx" => $mm_idx, "ru_show_yn" => "Y"]);
				if (!$resume) {
					$resume = $this->member_model->getResume(["ru_idx" => $mq_idxs[0]]);
				}
			} else if ($mq_type == "RECRUIT") {
				$resume = $this->member_model->getResume(["mm_idx" => $mm_idx, "ru_show_yn" => "Y"]);
			}

			if (!$resume) {
				
				json_return("99", "이력서 정보를 찾을 수 없습니다.");
			}
		

			# 중복체크
			$user_id = $this->user->getUserId();
			$user_phone = $this->user->getUserPhone();
			$request_idxs = array(); // 실제로 등록할 대상
			$dup_rm_idx = null;      // 이미 보낸 요청의 채팅방 번호
			$dup_ru_idx = null;      // 이미 보낸 요청의 이력서 번호 (재전송 메시지 카드용)
			foreach ($mq_idxs as $idx) {
				$data["mq_type"] = $mq_type;
				$data["mm_idx"] = $mm_idx;
				if ($mq_type == "RECRUIT") {
					$data["rc_idx"] = $idx;
					$data["ru_idx"] = $resume["ru_idx"];

					// 공고 소유 기업의 mm_idx 조회
					$recruit_row = $this->db->where('rc_idx', $data["rc_idx"])->get('st_recruit')->row_array();
					if (!$recruit_row) {
						json_return("99", "공고 정보를 찾을 수 없습니다.");
					}
					$owner_mm_idx = $recruit_row["mm_idx"];

					// 기업이 이미 이 이력서로 제안한 적이 있는지 확인
					$proposal_rm_idx = $this->member_model->findActiveRequestRoom("RESUME", $owner_mm_idx, $data["rc_idx"], $data["ru_idx"], $user_id, $user_phone);
					if ($proposal_rm_idx) {
						json_return("02", "이미 기업에서 제안한 공고입니다. 다시 지원을 보내시겠습니까?", array("rm_idx" => $proposal_rm_idx));
					}
				} else if ($mq_type == "RESUME") {
					$data["ru_idx"] = $idx;
					$data["rc_idx"] = $rc_idx;
					// 기업회원이 제안할 때는 이력서 작성자 정보가 필요
					if (!$resume) {
						continue;
					}
				}
				// 같은 공고 + 같은 이력서로 이미 보낸 요청이면 다시 등록하지 않고 기존 채팅방으로 안내
				// (내가 그 방을 나간 상태면 null → 재전송으로 취급해 아래 5분 체크 후 재등록)
				$active_rm_idx = $this->member_model->findActiveRequestRoom($mq_type, $mm_idx, $data["rc_idx"], $data["ru_idx"], $user_id, $user_phone);
				if ($active_rm_idx) {
					$dup_rm_idx = $active_rm_idx;
					$dup_ru_idx = $data["ru_idx"];
					continue;
				}

				$request_idxs[] = $idx;
			}

			// 모든 대상이 이미 보낸 요청이면 기록/포인트/메시지 없이 기존 채팅방 번호만 반환
			if (empty($request_idxs)) {
				$dup_msg = ($mq_type == "RECRUIT") ? "이미 지원했습니다." : "이미 제안을 보냈습니다.";
				json_return("02", $dup_msg, ["rm_idx" => $dup_rm_idx, "ru_idx" => $dup_ru_idx]);
			}
			// 여러 대상(웹 일괄 제안) 중 일부만 중복이면 남은 대상만 처리 (포인트도 남은 대상 기준으로 계산)
			$mq_idxs = $request_idxs;

			# 포인트 차감 처리 (다이렉트 채팅은 위에서 이미 분기되어 여기 오지 않음)
			if (!$is_direct_chat) {
				$point_data = [];
				$point_data["mq_type"] = $mq_type;
				$point_data["mq_idxs"] = $mq_idxs;
				$point_data["mm_idx"] = $mm_idx;

				# 제안갯수 확인및 과금 포인트 갯수 확인
				$point_price = 0;
				$point_yn = "Y";
				$point_cnt = $this->member_model->checkMemberRequestPoint($point_data);

				# 회원 포인트 확인
				if ($point_cnt > 0) {
					# 포인트 정보
					$point_info = $this->setting_model->getCodeInfo(["cd_grp" => "사용포인트", "cd_key" => "U020"]);

					# 회원 포인트 정보
					$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

					$point_price = ($point_info["cd_val3"] * $point_cnt);
					if ($mem_info["mm_point"] < $point_price) {
						json_return("90", "매일 2회 무료 제안보내기를 사용하셨습니다.<br/>포인트가 부족하여 인재정보 제안하기를 진행 할 수 없습니다. 포인트를 충전해주세요.<br/>보유포인트 : " . $mem_info["mm_point"] . "P<br/>사용포인트 : " . $point_price . "P");
					} else {
						# 포인트 차감
						$last_point = $mem_info["mm_point"] - $point_price;
						$insert = [
							"mm_idx" => $mm_idx,
							"o_num" => "",
							"pm_idx" => 0,
							"pt_type" => "EVENT",
							"pl_type" => "minus",
							"pl_point" => $point_price,
							"pl_last_point" => $last_point,
							"pl_desc" => "인재정보 제안하기(" . $point_price . "P차감)",
							"reg_id" => "system",
						];
						$result_point = $this->site_model->insertPointList($insert);

						if ($result_point["code"] != "00") {
							$this->db->trans_rollback();
							json_return("92", "포인트 차감시 문제가 발생하였습니다. 관리자에 문의 바랍니다.");
						} else {
							$this->member_model->updateMember(["mm_point" => $last_point], ["mm_idx" => $mm_idx]);
						}
					}
				}
			}

			# 등록처리
			foreach ($mq_idxs as $idx) {
				$data = [
					"mq_type" => $mq_type,
					"mm_idx" => $mm_idx,
				];

				# RECRUIT_FAV : 즐겨찾기 공고,RECRUIT_RECV : 제안받은 공고,TRADE_FAV : 중고상품 즐겨찾기,RESUME_FAV : 즐겨찾기한인재
				if ($mq_type == "RECRUIT") {
					$data["rc_idx"] = $idx;
					$data["ru_idx"] = $resume ? $resume["ru_idx"] : 0;
					$data["origin_rc_idx"] = null;
				} else if ($mq_type == "RESUME") {
					$data["ru_idx"] = $idx;
					$data["rc_idx"] = $rc_idx;
					$data["origin_rc_idx"] = null;
				}

				// 채팅방 처리: 상대방과의 기존 방이 있으면 재사용, 없으면 신규 생성
				// (방을 먼저 결정한 뒤 지원 기록을 저장해야 rm_idx를 포함시킬 수 있음)
				$rm_idx = null;
				try {
					// 1. 상대방 mm_idx 구하기
					$opponent_mm_idx = $this->get_opponent_mm_idx($mq_type, $idx);

					// 2. 기존 방 찾기 (같은 기업↔개인 쌍이면 정식/다이렉트 구분 없이 같은 방)
					$existing_rm_idx = $this->find_existing_room_by_opponent($mm_idx, $opponent_mm_idx, $mq_type);

					if ($existing_rm_idx) {
						// 기존 방이 있으면: 방의 공고/이력서를 이번 요청 값으로 갱신 (순수 채팅방이었다면 정식 방으로 승격)
						$rm_idx = $existing_rm_idx;
						
						$this->common_model->updateRoom(
							["rc_idx" => $data["rc_idx"], "ru_idx" => $data["ru_idx"], "up_dt" => get_today()],
							["rm_idx" => $existing_rm_idx]
						);

						// MongoDB: 나갔던 방이 재사용되는 경우 exit_yn을 리셋
						
						$this->_reset_room_exit_status($existing_rm_idx);
					
					} else {
						// 기존 방이 없으면: 신규 생성 (MySQL + MongoDB + exit 리셋)
						$rm_idx = $this->_create_recruit_room($data["rc_idx"], $data["ru_idx"], $mm_idx, "N");
					}
				} catch (Exception $chat_error) {
					// 채팅방 생성 오류 로그 추가 (조용한 실패 방지)

				}

				// 이제 rm_idx가 정해졌으므로 data에 추가하고 지원 기록 저장
				if (!is_null($rm_idx)) {
					$data["rm_idx"] = $rm_idx;
				}

				// 지원/제안 기록: DELETE 후 INSERT (중복 시 덮어씌우기)
				$result = $this->member_model->setMemberRequest($data, $data);

				// 지원하기 푸시알림 (채용공고 소유자에게)
				if ($mq_type == "RECRUIT") {
					try {
						// error_log("[PUSH_APPLICATION] 지원하기 푸시 알림 시작 - rc_idx: " . $idx . ", mm_idx: " . $mm_idx . ", ru_idx: " . $data["ru_idx"]);

						// 채용공고 소유자의 mm_idx 조회
						$recruit_sql = "SELECT mm_idx FROM st_recruit WHERE rc_idx = ? AND rc_show_yn = 'Y'";
						$recruit_result = $this->db->query($recruit_sql, [$idx]);
						$recruit_info = $recruit_result->row_array();

						if (!$recruit_info) {
							// error_log("[PUSH_APPLICATION_ERROR] 채용공고 정보 없음 - rc_idx: " . $idx);
						} elseif ($recruit_info) {
							// 회원 정보 조회
							$member_sql = "SELECT mm_id FROM st_member WHERE mm_idx = ? AND mm_status = 'Y'";
							$member_result = $this->db->query($member_sql, [$recruit_info['mm_idx']]);
							$member_info = $member_result->row_array();

							if (!$member_info) {
								// error_log("[PUSH_APPLICATION_ERROR] 회원 정보 없음 - recruit_owner_mm_idx: " . $recruit_info['mm_idx']);
							} elseif ($member_info) {
								// error_log("[PUSH_APPLICATION] 회원 정보 조회 성공 - mm_id: " . $member_info['mm_id']);

								// FCM 토큰 조회 (여러 방법으로 시도)
								// 1. mm_idx로 조회 시도
								$token_sql = "SELECT ifnull(token,'') as token, push_yn FROM st_member_device WHERE mm_idx = ? AND push_yn = 'Y' ORDER BY up_dt DESC LIMIT 1";
								$token_result = $this->db->query($token_sql, [$recruit_info['mm_idx']]);
								$token_info = $token_result->row_array();

								// 2. mm_idx로 조회 실패 시 mm_id로 조회 시도
								if (!$token_info || empty($token_info['token'])) {
									$token_sql2 = "SELECT ifnull(token,'') as token, push_yn FROM st_member_device WHERE mm_idx = (SELECT mm_idx FROM st_member WHERE mm_id = ? AND mm_status = 'Y') AND push_yn = 'Y' ORDER BY up_dt DESC LIMIT 1";
									$token_result2 = $this->db->query($token_sql2, [$member_info['mm_id']]);
									$token_info = $token_result2->row_array();
								}

								// MongoDB에서도 토큰 조회 시도
								$mongo_token = null;
								try {
									$tbl_user = get_mongodb("user");
									if ($tbl_user && is_object($tbl_user)) {
										$mongo_user = $tbl_user->findOne(["mm_id" => $member_info['mm_id']]);
										if ($mongo_user && isset($mongo_user['token'])) {
											$mongo_token = $mongo_user['token'];
										}
									}
								} catch (Exception $mongo_error) {
									// error_log("[PUSH_APPLICATION_ERROR] MongoDB 토큰 조회 실패 - mm_id: " . $member_info['mm_id'] . ", error: " . $mongo_error->getMessage());
								}

								// 토큰 우선순위: MySQL > MongoDB
								$final_token = null;
								if ($token_info && !empty($token_info['token'])) {
									$final_token = $token_info['token'];
									// error_log("[PUSH_APPLICATION] FCM 토큰 조회 성공 (MySQL) - mm_id: " . $member_info['mm_id']);
								} else if ($mongo_token && !empty($mongo_token)) {
									$final_token = $mongo_token;
									// error_log("[PUSH_APPLICATION] FCM 토큰 조회 성공 (MongoDB) - mm_id: " . $member_info['mm_id']);
								} else {
									// error_log("[PUSH_APPLICATION_WARN] FCM 토큰 없음 - mm_id: " . $member_info['mm_id']);
								}

								// 지원한 구직자 정보 조회
								$applicant_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

								if (!$applicant_info) {
									// error_log("[PUSH_APPLICATION_ERROR] 지원자 정보 없음 - applicant_mm_idx: " . $mm_idx);
								} elseif ($applicant_info && !empty($applicant_info["mm_name"])) {
									$applicant_name = !empty($applicant_info["mm_name"]) ? $applicant_info["mm_name"] : "구직자";
									// error_log("[PUSH_APPLICATION] 지원자 정보 - applicant_name: " . $applicant_name);

									// FCM 푸시알림 발송 (토큰이 있는 경우에만)
									if ($final_token) {
										$token_list = [$final_token];
										$info = [
											"title" => "NowJob 지원 알림",
											"body" => $applicant_name . "님이 채용공고에 지원했습니다.",
											"url" => "/resume/hr_post/" . $data["ru_idx"],
											"type" => "job_application"
										];

										// 수신자가 채팅방에 없을 때만 푸시 발송
										if (!isUserInChatRoom($member_info['mm_id'], $rm_idx)) {
											$fcm_result = sendFcm($info, $token_list);
										}
										// error_log("[PUSH_APPLICATION] FCM 푸시 발송 - recipient_mm_id: " . $member_info['mm_id'] . ", applicant: " . $applicant_name);
									} else {
										// error_log("[PUSH_APPLICATION_WARN] FCM 토큰 없어 푸시 발송 스킵 - recipient_mm_id: " . $member_info['mm_id']);
									}

									// MongoDB 알림 등록 (토큰 유무와 관계없이 항상 등록)
									try {
										$tbl_alarm = get_mongodb("alarm");
										if ($tbl_alarm && is_object($tbl_alarm)) {
											$alarm = [
												"user_id" => $member_info['mm_id'],
												"al_type" => "job_application",
												"al_key" => "" . $mm_idx,
												"al_text" => "<strong><em>" . $applicant_name . "</em>님이 <em>채용공고에 지원</em>했습니다.</strong><br><small>지원서를 확인하고 연락해보세요.</small>",
												"al_etc" => json_encode([
													"applicant_name" => $applicant_name,
													"applicant_idx" => $mm_idx,
													"recruit_idx" => $idx
												]),
												"al_url" => "/resume/hr_post/" . $data["ru_idx"],
												"show_yn" => "N",
												"reg_dt" => get_today(),
											];
											$tbl_alarm->insert($alarm);
											// error_log("[PUSH_APPLICATION] MongoDB 알림 등록 성공 - recipient_mm_id: " . $member_info['mm_id']);
										}
									} catch (Exception $mongo_error) {
										// error_log("[PUSH_APPLICATION_ERROR] MongoDB 알림 등록 실패 - recipient_mm_id: " . $member_info['mm_id'] . ", error: " . $mongo_error->getMessage());
									}
								}
							}
						}
					} catch (Exception $push_error) {
						// error_log("[PUSH_APPLICATION_ERROR] 지원하기 푸시 알림 처리 중 예외 발생 - rc_idx: " . $idx . ", error: " . $push_error->getMessage());
					}
				}

				// 제안하기 푸시알림 (이력서 소유자에게)
				if ($mq_type == "RESUME") {
					try {
						// error_log("[PUSH_OFFER] 제안하기 푸시 알림 시작 - ru_idx: " . $idx . ", mm_idx: " . $mm_idx . ", rc_idx: " . $data["rc_idx"]);

						// 이력서 소유자의 mm_idx 조회
						$resume_sql = "SELECT mm_idx FROM st_resume WHERE ru_idx = ? AND ru_show_yn = 'Y'";
						$resume_result = $this->db->query($resume_sql, [$idx]);
						$resume_info = $resume_result->row_array();

						if (!$resume_info) {
							// error_log("[PUSH_OFFER_ERROR] 이력서 정보 없음 - ru_idx: " . $idx);
						} elseif ($resume_info) {
							// 회원 정보 조회
							$member_sql = "SELECT mm_id FROM st_member WHERE mm_idx = ? AND mm_status = 'Y'";
							$member_result = $this->db->query($member_sql, [$resume_info['mm_idx']]);
							$member_info = $member_result->row_array();

							if (!$member_info) {
								// error_log("[PUSH_OFFER_ERROR] 회원 정보 없음 - resume_owner_mm_idx: " . $resume_info['mm_idx']);
							} elseif ($member_info) {
								// error_log("[PUSH_OFFER] 회원 정보 조회 성공 - mm_id: " . $member_info['mm_id']);

								// FCM 토큰 조회 (여러 방법으로 시도)
								// 1. mm_idx로 조회 시도
								$token_sql = "SELECT ifnull(token,'') as token, push_yn FROM st_member_device WHERE mm_idx = ? AND push_yn = 'Y' ORDER BY up_dt DESC LIMIT 1";
								$token_result = $this->db->query($token_sql, [$resume_info['mm_idx']]);
								$token_info = $token_result->row_array();

								// 2. mm_idx로 조회 실패 시 mm_id로 조회 시도
								if (!$token_info || empty($token_info['token'])) {
									$token_sql2 = "SELECT ifnull(token,'') as token, push_yn FROM st_member_device WHERE mm_idx = (SELECT mm_idx FROM st_member WHERE mm_id = ? AND mm_status = 'Y') AND push_yn = 'Y' ORDER BY up_dt DESC LIMIT 1";
									$token_result2 = $this->db->query($token_sql2, [$member_info['mm_id']]);
									$token_info = $token_result2->row_array();
								}

								// MongoDB에서도 토큰 조회 시도
								$mongo_token = null;
								try {
									$tbl_user = get_mongodb("user");
									if ($tbl_user && is_object($tbl_user)) {
										$mongo_user = $tbl_user->findOne(["mm_id" => $member_info['mm_id']]);
										if ($mongo_user && isset($mongo_user['token'])) {
											$mongo_token = $mongo_user['token'];
										}
									}
								} catch (Exception $mongo_error) {
									// error_log("[PUSH_OFFER_ERROR] MongoDB 토큰 조회 실패 - mm_id: " . $member_info['mm_id'] . ", error: " . $mongo_error->getMessage());
								}

								// 토큰 우선순위: MySQL > MongoDB
								$final_token = null;
								if ($token_info && !empty($token_info['token'])) {
									$final_token = $token_info['token'];
									// error_log("[PUSH_OFFER] FCM 토큰 조회 성공 (MySQL) - mm_id: " . $member_info['mm_id']);
								} else if ($mongo_token && !empty($mongo_token)) {
									$final_token = $mongo_token;
									// error_log("[PUSH_OFFER] FCM 토큰 조회 성공 (MongoDB) - mm_id: " . $member_info['mm_id']);
								} else {
									// error_log("[PUSH_OFFER_WARN] FCM 토큰 없음 - mm_id: " . $member_info['mm_id']);
								}

								// 제안한 기업 정보 조회
								$company_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

								if (!$company_info) {
									// error_log("[PUSH_OFFER_ERROR] 제안 기업 정보 없음 - company_mm_idx: " . $mm_idx);
								} elseif ($company_info && !empty($company_info["mm_comp_name"])) {
									$company_name = !empty($company_info["mm_comp_name"]) ? $company_info["mm_comp_name"] : "기업";
									// error_log("[PUSH_OFFER] 제안 기업 정보 - company_name: " . $company_name);

									// FCM 푸시알림 발송 (토큰이 있는 경우에만)
									if ($final_token) {
										$token_list = [$final_token];
										$info = [
											"title" => "NowJob 제안 알림",
											"body" => $company_name . "에서 채용 제안을 보냈습니다.",
											"url" => "/recruit/job_post/" . $data["rc_idx"],
											"type" => "job_offer"
										];

										// 수신자가 채팅방에 없을 때만 푸시 발송
										if (!isUserInChatRoom($member_info['mm_id'], $rm_idx)) {
											$fcm_result = sendFcm($info, $token_list);
										}
										// error_log("[PUSH_OFFER] FCM 푸시 발송 - recipient_mm_id: " . $member_info['mm_id'] . ", company: " . $company_name);
									} else {
										// error_log("[PUSH_OFFER_WARN] FCM 토큰 없어 푸시 발송 스킵 - recipient_mm_id: " . $member_info['mm_id']);
									}

									// MongoDB 알림 등록 (토큰 유무와 관계없이 항상 등록)
									try {
										$tbl_alarm = get_mongodb("alarm");
										if ($tbl_alarm && is_object($tbl_alarm)) {
											$alarm = [
												"user_id" => $member_info['mm_id'],
												"al_type" => "job_offer",
												"al_key" => "" . $mm_idx,
												"al_text" => "<strong><em>" . $company_name . "</em>에서 <em>채용 제안</em>을 보냈습니다.</strong><br><small>이력서를 확인하고 제안에 응답해보세요.</small>",
												"al_etc" => json_encode([
													"company_name" => $company_name,
													"company_idx" => $mm_idx,
													"resume_idx" => $idx
												]),
												"al_url" => "/recruit/job_post/" . $data["rc_idx"],
												"show_yn" => "N",
												"reg_dt" => get_today(),
											];
											$tbl_alarm->insert($alarm);
											// error_log("[PUSH_OFFER] MongoDB 알림 등록 성공 - recipient_mm_id: " . $member_info['mm_id']);
										}
									} catch (Exception $mongo_error) {
										// error_log("[PUSH_OFFER_ERROR] MongoDB 알림 등록 실패 - recipient_mm_id: " . $member_info['mm_id'] . ", error: " . $mongo_error->getMessage());
									}
								}
							}
						}
					} catch (Exception $push_error) {
						// error_log("[PUSH_OFFER_ERROR] 제안하기 푸시 알림 처리 중 예외 발생 - ru_idx: " . $idx . ", error: " . $push_error->getMessage());
					}
				}
			}

			// JSON 응답 전에 출력 버퍼 정리
			ob_end_clean();

			// 직접 JSON 응답 처리 (header 문제 방지)
		
			header('Content-type: application/json');
			echo json_encode(["code" => "00", "msg" => "처리완료", "data" => ["rm_idx" => $rm_idx, "rc_idx" => $rc_idx, "ru_idx" => (isset($data["ru_idx"]) ? $data["ru_idx"] : 0), "mq_type" => $mq_type]]);
			exit;
		} catch (Exception $e) {
			// JSON 응답 전에 출력 버퍼 정리
			ob_end_clean();

			json_return("90", "처리 중 오류가 발생했습니다. 다시 시도해주세요.");
		} catch (Error $e) {
			// JSON 응답 전에 출력 버퍼 정리
			ob_end_clean();

			json_return("90", "시스템 오류가 발생했습니다. 관리자에게 문의해주세요.");
		}
	}

	# 제안보내기 갯수 확인
	function member_request_check()
	{
		$mq_type = input_post("mq_type");
		$mq_idxs = input_post("mq_idxs");
		$rc_idx = input_post("rc_idx");
		$mm_idx = $this->user->getUserIdx();

		# 로그인 유무 확인
		if ($this->user->isLogin() == false) {
			json_return("90", "로그인후 이용해주세요.");
		}

		$data = [];
		$data["mq_type"] = $mq_type;
		$data["mq_idxs"] = $mq_idxs;
		$data["mm_idx"] = $mm_idx;

		# 제안갯수 확인및 과금 포인트 갯수 확인
		$point_price = 0;
		$point_yn = "N";
		$point_cnt = $this->member_model->checkMemberRequestPoint($data);

		# 회원 포인트 확인
		if ($point_cnt > 0) {
			# 포인트 정보
			$point_info = $this->setting_model->getCodeInfo(["cd_grp" => "사용포인트", "cd_key" => "U020"]);

			# 회원 포인트 정보
			$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

			$point_price = ($point_info["cd_val3"] * $point_cnt);
			if ($mem_info["mm_point"] < $point_price) {
				json_return("90", "매일 2회 무료 제안보내기를 사용하셨습니다.<br/>포인트가 부족하여 인재정보 제안하기를 진행 할 수 없습니다. 포인트를 충전해주세요.<br/>보유포인트 : " . $mem_info["mm_point"] . "P<br/>사용포인트 : " . $point_price . "P");
			}
			$point_yn = "Y";
		}

		json_return("00", "처리완료", ["point_price" => $point_price, "point_yn" => $point_yn]);
	}

	function request_cancel()
	{
		$proc_type = input_post("proc_type");
		$mq_idxs = input_post("mq_idxs");

		if ($proc_type == "member_request") {
			// 트랙젝션 시작
			$this->db->trans_begin();
			
			// MongoDB 채팅방 삭제 처리를 위한 정보 수집
			$rooms_to_close = [];

			foreach ($mq_idxs as $idx) {
				// 상태 변경 전에 ru_idx와 rc_idx 조회
				$sql = "SELECT ru_idx, rc_idx FROM st_member_request WHERE mq_idx = '" . $this->db->escape_str($idx) . "'";
				$query = $this->db->query($sql);
				$request_info = $query->row_array();

				if ($request_info && !empty($request_info["ru_idx"]) && !empty($request_info["rc_idx"])) {
					$rooms_to_close[] = [
						"ru_idx" => (int)$request_info["ru_idx"],
						"rc_idx" => (int)$request_info["rc_idx"]
					];
				}

				// MySQL에서 지원 정보 상태를 'cancel'로 변경 (삭제 대신 상태 변경)
				$sql = "UPDATE st_member_request SET mq_status = 'cancel' WHERE mq_idx = '" . $this->db->escape_str($idx) . "'";
				$query = $this->db->query($sql);
				$affectedRows = $this->db->affected_rows();

				if (!$query || $affectedRows != 1) {
					// 상태 변경 실패 시 롤백
					$this->db->trans_rollback();
					json_return("01", "지원취소 처리 중 오류가 발생하였습니다.");
					return;
				}
			}
			
			// 트랙젝션 완료
			$this->db->trans_commit();

			// MongoDB에서 채팅방 삭제 처리
			if (!empty($rooms_to_close)) {
				try {
					$uri = "mongodb://localhost:27017";
					@$mongo = new Mongo($uri);
					$database = $mongo->chat;
					$collection = $database->room;

					foreach ($rooms_to_close as $room_info) {
						// ru_idx와 rc_idx로 채팅방 찾기
						$query = [
							'ru_idx' => $room_info["ru_idx"],
							'rc_idx' => $room_info["rc_idx"]
						];

						$room = $collection->findOne($query);

						if ($room) {
							// 채팅방 상태를 CLOSED로 변경 (삭제 대신 상태 변경)
							$update_data = ['$set' => ['rm_status' => 'CLOSED']];
							$collection->update($query, $update_data);
						}
					}
				} catch (Exception $e) {
					// MongoDB 오류는 무시하고 계속 진행 (제안 취소는 성공으로 처리)
				}
			}
		} else if ($proc_type == "bookmark") {
			foreach ($mq_idxs as $idx) {
				$result = $this->member_model->deleteBookmark(["bk_idx" => $idx]);
			}
		}

		json_return("00", "처리완료");
	}

	function set_addr_info()
	{
		# GPS 정보 저장
		$ad_zipcode = input_post("ad_zipcode", "");
		$ad_jibun = input_post("ad_jibun", "");
		$ad_doro = input_post("ad_doro", "");
		$ad_addr = input_post("ad_addr", "");
		$ad_detail = input_post("ad_detail", "");
		$ad_gps_x = input_post("ad_gps_x", "");
		$ad_gps_y = input_post("ad_gps_y", "");


		$info = [
			"ad_zipcode" => $ad_zipcode,
			"ad_jibun" => $ad_jibun,
			"ad_doro" => $ad_doro,
			"ad_addr" => $ad_addr,
			"ad_detail" => $ad_detail,
			"ad_gps_x" => $ad_gps_x,
			"ad_gps_y" => $ad_gps_y,
		];


		if ($this->user->isLogin() == true) {
			$mm_idx = $this->user->getUserIdx();
			$this->member_model->updateAddress($info, ["ad_type" => "MEMBER", "mm_idx" => $mm_idx]);

			# 세션 다시 생성
			$user_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

			$user = [
				"mm_idx" => $user_info["mm_idx"],
				"mm_id" => $user_info["mm_id"],
				"mm_name" => $user_info["mm_name"],
				"mm_type" => $user_info["mm_type"],
				"ad_addr" => $ad_addr,
				"ad_gps_x" => $ad_gps_x,
				"ad_gps_y" => $ad_gps_y,
			];

			$this->common->setSession("USER_INFO", $user);


			$addr_info = $this->member_model->getAddress(["mm_idx" => $mm_idx, "ad_type" => "MEMBER"]);
			if ($addr_info == null) {
				# 본인 주소 다시 설정
				$arr_addr = [
					"mm_idx" => $user_info["mm_idx"],
					"ad_type" => "MEMBER",
					"ad_key" => $user_info["mm_idx"],
					"ad_zipcode" => $ad_zipcode,
					"ad_jibun" => $ad_jibun,
					"ad_doro" => $ad_doro,
					"ad_detail" => $ad_detail,
					"ad_addr" => $ad_addr,
					"ad_gps_x" => $ad_gps_x,
					"ad_gps_y" => $ad_gps_y,
				];

				$result_addr = $this->member_model->insertAddress($arr_addr);
			} else {
				# 본인 주소 다시 설정
				$arr_addr = [
					"mm_idx" => $user_info["mm_idx"],
					"ad_type" => "MEMBER",
					"ad_key" => $user_info["mm_idx"],
					"ad_zipcode" => $ad_zipcode,
					"ad_jibun" => $ad_jibun,
					"ad_doro" => $ad_doro,
					"ad_detail" => $ad_detail,
					"ad_addr" => $ad_addr,
					"ad_gps_x" => $ad_gps_x,
					"ad_gps_y" => $ad_gps_y,
				];
				$result_addr = $this->member_model->updateAddress($arr_addr, ["ad_idx" => $addr_info["ad_idx"]]);
			}
		} else {
			//비회원
			$session_idx = session_id();
			$this->member_model->updateGuest($info, ["gt_session_id" => $session_idx]);

			# 세션 다시 생성
			//$guest_info = $this->member_model->getGuest(["gt_session_id"=>$session_idx]);
			$guest_info = [
				"mm_idx" => "",
				"mm_id" => "",
				"mm_name" => "",
				"mm_type" => "",
				"ad_addr" => $ad_addr,
				"ad_gps_x" => $ad_gps_x,
				"ad_gps_y" => $ad_gps_y,
			];

			$this->common->setSession("GUEST_INFO", $guest_info);
		}
		json_return("00", "처리완료");
	}

	// location_data 테이블에서 정확한 시도명 조회
	private function getFullSidoName($abbr_sido) {
		try {
			$escaped = addslashes($abbr_sido);
			$sql = "SELECT DISTINCT sido_name FROM location_data WHERE sido_name LIKE '{$escaped}%' LIMIT 1";
			$result = $this->db->query($sql)->row_array();
			return !empty($result['sido_name']) ? $result['sido_name'] : $abbr_sido;
		} catch (Exception $e) {
			return $abbr_sido;
		}
	}

	# GPS정보로 주소 검색
	function get_gps_address()
	{
		try {
			$x = input_post("x", "");
			$y = input_post("y", "");
			$params = array(
				'x' => $x,
				'y' => $y,
			);

			$result = kakaoRestApi("coord2address", $params);
			if ($result["meta"]["total_count"] == 0) {
				json_return("10", "검색된 주소가 없습니다.");
			} else {
				if (count($result["documents"]) > 0) {
					$addr_name = "";
					if ($result["documents"][0]["road_address"] == null) {
						$region1 = $this->getFullSidoName($result["documents"][0]["address"]["region_1depth_name"]);
						$addr_name  = $region1 . " ";
						$addr_name .= $result["documents"][0]["address"]["region_2depth_name"] . " ";
						$addr_name .= $result["documents"][0]["address"]["region_3depth_name"] . " ";
					} else {
						$region1 = $this->getFullSidoName($result["documents"][0]["road_address"]["region_1depth_name"]);
						$addr_name  = $region1 . " ";
						$addr_name .= $result["documents"][0]["road_address"]["region_2depth_name"] . " ";
						$addr_name .= $result["documents"][0]["road_address"]["region_3depth_name"] . " ";
					}
					$addr_name .= "^" . $result["documents"][0]["address"]["address_name"] . "";
					$addr_name .= "^" . $result["documents"][0]["road_address"]["address_name"] . "";
					$addr_name .= "^" . $result["documents"][0]["road_address"]["zone_no"] . "";
					$addr_name .= "^" . $result["documents"][0]["address"]["x"] . "";
					$addr_name .= "^" . $result["documents"][0]["address"]["y"] . "";
					json_return("00", "조회성공", $addr_name);
				} else {
					json_return("10", "검색된 주소가 없습니다.");
				}
			}
		} catch (Exception $ex) {
			json_return("90", "주소 검색시 오류 발생");
		}
	}

	# 주소 리스트 검색
	function get_address_list()
	{
		try {
			$query = input_post("query", "");
			$page = input_post("page", "1");
			$params = array(
				'query' => $query,
				'page' => $page,
				'analyze_type' => 'similar',
				'size' => '20'
			);


			$result = kakaoRestApi("address", $params);

			if (count($result["documents"]) == 0) {
				json_return("10", "검색된 주소가 없습니다.");
			} else {
				$addr_list = [];



				foreach ($result["documents"] as $row) {
					$addr_list[] = [
						"address_name" => $row["road_address"]["address_name"],
						"zone_no" => $row["road_address"]["zone_no"],
						"x" => $row["x"],
						"y" => $row["y"],
					];
				}

				$data = [
					"addr_list" => $addr_list,
					"is_end" => $result["meta"]["is_end"],
					"pageable_count" => $result["meta"]["pageable_count"],
					"total_count" => $result["meta"]["total_count"],
				];

				json_return("00", "조회성공", $addr_name);
			}
		} catch (Exception $ex) {
			json_return("90", "주소 검색시 오류 발생");
		}
	}

	# 주소로 GPS정보 찾기
	function get_gps_info()
	{
		try {
			$query = input_post("query", "");
			$params = array(
				'query' => $query,
				'exact' => 'exact',
				'size' => '3'
			);

			$result = kakaoRestApi("address", $params);

			if (count($result["documents"]) == 0) {
				json_return("10", "GPS정보를 조회하지 못했습니다.");
			} else {
				$return = [
					"x" => $result["documents"][0]["x"],
					"y" => $result["documents"][0]["y"]
				];

				//쇼트 주소 검색
				$ad_addr = $this->site_model->getAddrShort($query);

				if ($ad_addr == "") {
					json_return("11", "매칭되는 주소 정보가 없습니다.");
				} else {
					$return["addr"] = $ad_addr;
					json_return("00", "조회성공", $return);
				}
			}
		} catch (Exception $ex) {
			json_return("90", "주소 검색시 오류 발생");
		}
	}

	# 주소로 GPS정보 찾기
	function get_addr_name()
	{
		try {
			$query = input_post("query", "");
			$ad_addr = $this->site_model->getAddrShort($query);

			if ($ad_addr == "") {
				json_return("11", "매칭되는 주소 정보가 없습니다.");
			} else {
				$return["addr"] = $ad_addr;
				json_return("00", "조회성공", $return);
			}
		} catch (Exception $ex) {
			json_return("90", "주소 검색시 오류 발생");
		}
	}

	# 이미지 변경
	function change_img()
	{
		$mm_idx = $this->user->getUserIdx();

		# 기존 등록 이미지 있으면 삭제
		$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);

		# 이미지 처리.
		$mm_thumb = $this->common->img_upload_resize_url("member", "mm_img1", 600);

		if ($mm_thumb != "") {
			if ($mem_info["mm_thumb"] != "") {
				file_remove($mem_info["mm_thumb"]);
			}

			$result = $this->member_model->updateMember(["mm_thumb" => $mm_thumb, "mm_img1" => $mm_thumb], ["mm_idx" => $mm_idx]);

			#몽고 업데이트
			$update = [
				"user_id" => $mem_info["mm_id"],
				"user_img" => $mm_thumb,
			];
			$update["mm_id"] = $mem_info["mm_id"];
			$this->mongo_model->updateUserInfo($update);

			json_return($result["code"], $result["msg"]);
		} else {
			json_return("90", "이미지가 업로드 되지 않았습니다.");
		}
	}

	function send_sms($phone, $msg)
	{
		exit;
		$_api_url = 'https://message.ppurio.com/api/send_utf8_json.php';     // UTF-8 인코딩과 JSON 응답용 호출 페이지
		$_param['userid'] = 'dongwonstec124';           // [필수] 뿌리오 아이디
		$_param['callback'] = '07077122610';    // [필수] 발신번호 - 숫자만
		$_param['phone'] = '01091190227';       // [필수] 수신번호 - 여러명일 경우 |로 구분 '010********|010********|010********'
		$_param['msg'] = '진우씨 테스트발송입니다.';   // [필수] 문자내용 - 이름(names)값이 있다면 [*이름*]가 치환되서 발송됨
		$_param['names'] = '';            // [선택] 이름 - 여러명일 경우 |로 구분 '홍길동|이순신|김철수'
		$_param['appdate'] = '';  // [선택] 예약발송 (현재시간 기준 10분이후 예약가능)
		$_param['subject'] = '';          // [선택] 제목 (30byte)
		$_param['file1'] = ''; // [선택] 포토발송 (jpg, jpeg만 지원  300 K  이하)

		$_curl = curl_init();
		curl_setopt($_curl, CURLOPT_URL, $_api_url);
		curl_setopt($_curl, CURLOPT_POST, true);
		curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($_curl, CURLOPT_POSTFIELDS, $_param);
		$_result = curl_exec($_curl);
		curl_close($_curl);
		$_result = json_decode($_result);
	}

	function get_addrlist()
	{
		$depth = input_post("depth", 1);
		$addr1 = input_post("addr1", "");
		$addr2 = input_post("addr2", "");
		$list = $this->site_model->getDepthAddrList($depth, $addr1, $addr2);

		json_return("00", "조회성공", ["list" => $list]);
	}

	function get_addrlist_keylike()
	{
		$addr = input_post("addr", "");
		$list = $this->site_model->getAddrListKeyLike($addr);

		json_return("00", "조회성공", ["list" => $list]);
	}

	function alarm_count()
	{
		# 알림 카운트
		if ($this->user->isLogin() == false) {
			return 0;
		}

		$mm_id = $this->user->getUserId();

		$tbl_alarm = get_mongodb("alarm");
		$alarm_cnt = $tbl_alarm->find(["show_yn" => "N", "user_id" => $mm_id])->count();
		json_return("00", "조회성공", ["count" => $alarm_cnt]);
	}

	function get_location_data()
	{
		$step = $this->db->escape_str(input_post('step', 1));
		$sido_name = $this->db->escape_str(input_post('sido_name', ''));
		$gugun_name = $this->db->escape_str(input_post('gugun_name', ''));

		$sql = "";

		if ($step == 1) {
			$sql .= "
					SELECT DISTINCT sido_name
					FROM location_data
					WHERE step = 1
					ORDER BY sido_name ASC
				";
		} else if ($step == 2 && $sido_name) {
			$sql .= "
					SELECT DISTINCT gugun_name
					FROM location_data
					WHERE step = 2
					  AND sido_name = '" . $sido_name . "'
					ORDER BY gugun_name ASC
				";
		} else if ($step == 3 && $sido_name && $gugun_name) {
			$sql .= "
					SELECT DISTINCT b_dong_name
					FROM location_data
					WHERE step = 3
					  AND sido_name = '" . $sido_name . "'
					  AND gugun_name = '" . $gugun_name . "'
					ORDER BY b_dong_name ASC
				";
		} else {
			json_return("99", "요청 파라미터 오류", null);
			return;
		}

		$result = $this->db->query($sql)->result_array();
		json_return("00", "조회 성공", $result);
	}

	function get_location_search()
	{
		$keyword = $this->db->escape_str(input_post('search_keyword', ''));

		if ($keyword) {
			$sql = "
          SELECT DISTINCT search_b_dong, step
          FROM location_data
          WHERE search_b_dong LIKE '%{$keyword}%'
          ORDER BY search_b_dong ASC
        ";
		} else {
			json_return("99", "요청 파라미터 오류", null);
			return;
		}

		$result = $this->db->query($sql)->result_array();
		json_return("00", "조회 성공", $result);
	}

	function get_search_b_dong()
	{
		$lat = $this->db->escape_str(input_post('lat', ''));
		$lng = $this->db->escape_str(input_post('lng', ''));
		$dong = $this->db->escape_str(input_post('dong', ''));
		$nKm = $this->db->escape_str(input_post('nKm', ''));

		$sql = "";

		if ($lat && $lng && $dong && $nKm) {
			$sql .= "
SELECT 
    search_b_dong, 
    MIN(distance_km) AS distance_km
FROM (
    SELECT 
        ld1.search_b_dong,
        ROUND(
            6371 * ACOS(
                COS(RADIANS(" . $lat . ")) * COS(RADIANS(ld1.b_latitude)) *
                COS(RADIANS(ld1.b_longitude) - RADIANS(" . $lng . ")) +
                SIN(RADIANS(" . $lat . ")) * SIN(RADIANS(ld1.b_latitude))
            ),
            2
        ) AS distance_km
    FROM location_data ld1
    WHERE ld1.step = 3
    HAVING distance_km <= " . $nKm . "

    UNION ALL

    SELECT 
        ld2.search_b_dong,
        ROUND(
            6371 * ACOS(
                COS(RADIANS(center.center_lat)) *
                COS(RADIANS(ld2.b_latitude)) *
                COS(RADIANS(ld2.b_longitude) - RADIANS(center.center_lng)) +
                SIN(RADIANS(center.center_lat)) *
                SIN(RADIANS(ld2.b_latitude))
            ),
            2
        ) AS distance_km
    FROM location_data ld2
    JOIN (
        SELECT b_latitude AS center_lat, b_longitude AS center_lng
        FROM location_data
        WHERE step = 3
          AND search_b_dong LIKE '" . $dong . "'
        LIMIT 1
    ) AS center
    WHERE ld2.step = 3
    HAVING distance_km <= " . $nKm . "
) AS combined
GROUP BY search_b_dong
ORDER BY distance_km ASC;

				";
		} else {
			json_return("99", "요청 파라미터 오류", null);
			return;
		}

		$result = $this->db->query($sql)->result_array();
		json_return("00", "조회 성공", $result);
	}

	/**
	 * MongoDB 채팅 서버에 방 정보 생성
	 */
	private function createChatRoomInMongoDB($rm_idx, $data, $mm_idx)
	{
		try {

			// MongoDB 연결 시도 (최신 드라이버 사용)
			$mongo = null;
			try {
				// 최신 MongoDB 드라이버 시도
				if (class_exists('MongoDB\Client')) {
					$mongo = new MongoDB\Client("mongodb://localhost:27017");
					$database = $mongo->chat;
					$collection = $database->room;
				} else {
					// 구식 드라이버 fallback
					$mongo = new Mongo("mongodb://localhost:27017");
					$database = $mongo->chat;
					$collection = $database->room;
				}
			} catch (Exception $mongo_connect_error) {
				// MongoDB 연결 실패 시 채팅방 생성을 건너뛰고 계속 진행
				return;
			}

			// 사용자 정보 조회
			$user_info = $this->member_model->getMemberInfo($mm_idx);
			$target_user_info = null;

			if ($data["mq_type"] == "RECRUIT") {
				// 제안하기: 이력서 소유자 정보 조회
				$target_user_info = $this->getMemberInfoByResume($data["ru_idx"]);
			} else if ($data["mq_type"] == "RESUME") {
				// 지원하기: 채용공고 소유자 정보 조회
				try {
					$target_user_info = $this->getMemberInfoByRecruit($data["rc_idx"]);
				} catch (Exception $e) {
					$target_user_info = null;
				}
			}

			// 사용자 정보가 없어도 채팅방은 생성 (빈 user_id로 처리)
			if (!$user_info) {
				$user_info = [
					"mm_idx" => $mm_idx,
					"mm_name" => "알 수 없음",
					"mm_phone" => "",
					"mm_img1" => "",
					"mm_desc" => ""
				];
			}

			if (!$target_user_info) {
				// 대상 사용자 정보가 없어도 채팅방은 생성 (기본값으로 처리)
				$target_user_info = [
					"mm_idx" => 0,
					"mm_name" => "알 수 없음",
					"mm_phone" => "",
					"mm_img1" => "",
					"mm_desc" => ""
				];
			}

			// 방 정보 생성
			$room_document = [
				"rm_idx" => (int)$rm_idx,
				"rc_idx" => (int)$data["rc_idx"],
				"ru_idx" => (int)$data["ru_idx"],
				"rm_type" => "recruit",
				"rm_img" => "",
				"rm_subject" => "채용공고 채팅방",
				"rm_desc" => "채용공고 관련 채팅방",
				"rm_last_msg" => "",
				"rm_last_dt" => class_exists('MongoDB\BSON\UTCDateTime') ? new MongoDB\BSON\UTCDateTime() : new MongoDate(),
				"rm_users" => [
					[
						"user_id" => $user_info["mm_phone"],
						"user_nick" => $user_info["mm_name"],
						"user_img" => isset($user_info["mm_img1"]) ? $user_info["mm_img1"] : "",
						"user_desc" => isset($user_info["mm_desc"]) ? $user_info["mm_desc"] : "",
						"user_status" => "Y"
					],
					[
						"user_id" => $target_user_info["mm_phone"],
						"user_nick" => $target_user_info["mm_name"],
						"user_img" => isset($target_user_info["mm_img1"]) ? $target_user_info["mm_img1"] : "",
						"user_desc" => isset($target_user_info["mm_desc"]) ? $target_user_info["mm_desc"] : "",
						"user_status" => "N"
					]
				],
				"user_info" => [
					[
						"user_id" => $user_info["mm_phone"],
						"user_nick" => $user_info["mm_name"],
						"user_img" => isset($user_info["mm_img1"]) ? $user_info["mm_img1"] : "",
						"user_desc" => isset($user_info["mm_desc"]) ? $user_info["mm_desc"] : "",
						"user_status" => "Y"
					],
					[
						"user_id" => $target_user_info["mm_phone"],
						"user_nick" => $target_user_info["mm_name"],
						"user_img" => isset($target_user_info["mm_img1"]) ? $target_user_info["mm_img1"] : "",
						"user_desc" => isset($target_user_info["mm_desc"]) ? $target_user_info["mm_desc"] : "",
						"user_status" => "N"
					]
				],
				"rm_status" => "N",
				"rm_messages" => []
			];

			// MongoDB에 방 정보 삽입
			$collection->insert($room_document);

			// 사용자 정보도 MongoDB에 등록
			$this->createUserInMongoDB($user_info);
			$this->createUserInMongoDB($target_user_info);
		} catch (Exception $e) {
			// MongoDB 채팅방 생성 오류는 무시
		}
	}

	/**
	 * MongoDB에 사용자 정보 생성
	 */
	private function createUserInMongoDB($user_info)
	{
		try {
			// MongoDB 연결 시도 (최신 드라이버 사용)
			$mongo = null;
			try {
				// 최신 MongoDB 드라이버 시도
				if (class_exists('MongoDB\Client')) {
					$mongo = new MongoDB\Client("mongodb://localhost:27017");
					$database = $mongo->chat;
					$collection = $database->user;
				} else {
					// 구식 드라이버 fallback
					$mongo = new Mongo("mongodb://localhost:27017");
					$database = $mongo->chat;
					$collection = $database->user;
				}
			} catch (Exception $mongo_connect_error) {
				return;
			}

			// 기존 사용자 확인
			$existing_user = $collection->findOne(["user_id" => $user_info["mm_phone"]]);

			if (!$existing_user) {
				// 새 사용자 생성
				$user_document = [
					"user_id" => $user_info["mm_phone"],
					"user_nick" => $user_info["mm_name"],
					"user_img" => isset($user_info["mm_img1"]) ? $user_info["mm_img1"] : "",
					"user_desc" => isset($user_info["mm_desc"]) ? $user_info["mm_desc"] : "",
					"user_status" => "N", // 기본값: 비접속
					"token" => "", // FCM 토큰은 나중에 등록
					"socket_id" => "",
					"reg_dt" => class_exists('MongoDB\BSON\UTCDateTime') ? new MongoDB\BSON\UTCDateTime() : new MongoDate()
				];

				$collection->insert($user_document);
			}
		} catch (Exception $e) {
			// MongoDB 사용자 생성 오류는 무시
		}
	}

	/**
	 * 이력서 소유자 정보 조회
	 */
	private function getMemberInfoByResume($ru_idx)
	{
		$sql = "
			SELECT aa.mm_idx, aa.mm_name, aa.mm_phone, aa.mm_img1, aa.mm_desc
			FROM st_member aa
			INNER JOIN st_resume bb ON aa.mm_idx = bb.mm_idx
			WHERE bb.ru_idx = '$ru_idx'
		";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	/**
	 * 채용공고 소유자 정보 조회
	 */
	private function getMemberInfoByRecruit($rc_idx)
	{
		try {
			$sql = "
				SELECT aa.mm_idx, aa.mm_name, aa.mm_phone, aa.mm_img1, aa.mm_desc
				FROM st_member aa
				INNER JOIN st_recruit bb ON aa.mm_idx = bb.mm_idx
				WHERE bb.rc_idx = '$rc_idx'
			";

			// SQL 쿼리 타임아웃 설정 (30초로 증가)
			$this->db->query("SET SESSION wait_timeout = 30");
			$query = $this->db->query($sql);
			$result = $query->row_array();
			return $result;
		} catch (Exception $e) {
			return null;
		}
	}

	/**
	 * 채팅 푸시 알림 발송 API (Node.js에서 호출)
	 * common_proc의 sendFcm() 함수를 사용하여 채팅 푸시를 발송
	 */
	function send_chat_push()
	{
		// CORS 헤더 설정 (Node.js에서 호출 가능하도록)
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type');
		
		// OPTIONS 요청 처리
		if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			http_response_code(200);
			exit;
		}

		try {
			// JSON 입력 데이터 받기
			$input = file_get_contents('php://input');
			$data = json_decode($input, true);

			// 필수 파라미터 검증
			if (!isset($data['token']) || empty($data['token'])) {
				json_return("90", "토큰이 필요합니다.");
				return;
			}

			if (!isset($data['info']) || !is_array($data['info'])) {
				json_return("90", "푸시 정보가 필요합니다.");
				return;
			}

			$token = $data['token'];
			$info = $data['info'];

			// 필수 필드 검증
			if (!isset($info['title']) || !isset($info['body']) || !isset($info['url'])) {
				json_return("90", "제목, 내용, URL이 필요합니다.");
				return;
			}

			// 토큰 리스트 생성
			$token_list = [$token];

			// common_proc와 동일한 sendFcm() 함수 사용
			$fcm_result = sendFcm($info, $token_list);

			// 결과 반환
			if ($fcm_result && isset($fcm_result['success']) && $fcm_result['success']) {
				json_return("00", "푸시 발송 완료", $fcm_result);
			} else {
				$error_msg = isset($fcm_result['error']) ? $fcm_result['error'] : "푸시 발송 실패";
				json_return("90", $error_msg, $fcm_result);
			}

		} catch (Exception $e) {
			json_return("99", "서버 오류: " . $e->getMessage());
		}
	}

	/**
	 * 사용자 저장된 주소 정보 조회
	 * hr_map 등에서 초기 로드 시 사용자의 저장된 주소를 조회
	 */
	function get_addr_info()
	{
		if ($this->user->isLogin() == false) {
			json_return("90", "로그인이 필요합니다.");
			return;
		}

		$mm_idx = $this->user->getUserIdx();
		$addr_info = $this->member_model->getAddress(["mm_idx" => $mm_idx, "ad_type" => "MEMBER"]);

		if ($addr_info && !empty($addr_info["ad_addr"])) {
			json_return("00", "주소 조회 성공", [
				"ad_addr" => $addr_info["ad_addr"],
				"ad_gps_x" => isset($addr_info["ad_gps_x"]) ? $addr_info["ad_gps_x"] : "",
				"ad_gps_y" => isset($addr_info["ad_gps_y"]) ? $addr_info["ad_gps_y"] : ""
			]);
		} else {
			json_return("00", "저장된 주소 없음", [
				"ad_addr" => "",
				"ad_gps_x" => "",
				"ad_gps_y" => ""
			]);
		}
	}

	/**
	 * 광고 배너 클릭 로그 저장
	 *
	 * @param page_type (recruit/hr)
	 * @return json
	 */
	function log_ad_click()
	{
		$page_type = input_post("page_type", "");
		$mm_idx    = $this->user->isLogin() ? $this->user->getUserIdx() : null;
		$mm_type   = $this->user->isLogin() ? $this->user->getUserType() : "비회원";

		$data = array(
			"page_type"   => $page_type,
			"mm_idx"      => $mm_idx,
			"mm_type"     => $mm_type,
			"acl_ip"      => $this->common->getIpAddr(),
			"acl_session" => session_id(),
			"reg_dt"      => date("Y-m-d H:i:s"),
		);

		$this->ad_model->insertAdClickLog($data);
		json_return("00", "success");
	}

	/**
	 * 기존 방 재사용 시 MongoDB의 exit_yn 리셋
	 * 사용자가 나갔던 방을 다시 사용하려고 할 때 exit_yn='Y' 상태를 'N'으로 복구
	 * (전역 헬퍼 reset_room_exit_status()로 위임)
	 */
	private function _reset_room_exit_status($rm_idx)
	{
		reset_room_exit_status($this->mongo_uri, $this->mongo_db, $rm_idx);
	}
}
