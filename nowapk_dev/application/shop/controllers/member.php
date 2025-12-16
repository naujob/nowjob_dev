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

		$msg_code = input_get("msg_code", "");
		$data["msg_code"] = $msg_code;

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
			//회원정보를 찾을 수 없습니다.
			go_location("/member/join_sns_start?join_key=" . $key . "&join_type=" . "APPLE");
		} else {
			go_location("/member/login?msg_code=" . $return["code"]);
		}
	}

	function kakao_login_callback() //모바일일때 사용
	{
		// callback 으로 들어온 값을 확인한다.
		$this->load->library('KakaoLogin');
		$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
		$kakao_key = "5643430bdfed15f491b8be74ad1522b1"; // nau2023@naver.com의 키
		$code = $_REQUEST["code"];
		$state = $_REQUEST["state"];
		$error = $_REQUEST["error"];
		$error_description = $_REQUEST["error_description"];

		if ($error) {
			$this->common->scriptReplace("/member/login", "문제가 발생해서 정상적으로 로그인이 되지 않았습니다.(1)");
			exit();
		}

		// 카카오로그인 객체 생성
		//$kakao = new KakaoLogin($kakao_key["인증키"],$this->common->getSession("csrf-token"));
		$kakao = new KakaoLogin("5643430bdfed15f491b8be74ad1522b1", $this->common->getSession("csrf-token")); // nau2023@naver.com의 키

		// 토큰 받기
		$tokenData = $kakao->getToken($code);

		// 사용자 정보 가져오기.
		if (! empty($tokenData["data"]["access_token"])) {

			$userInfoData = $kakao->getUserInfo($tokenData["data"]["access_token"]);
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
				$data["code"] = $return["code"];
				$data["msg"] = $return["msg"];
				$data["res_id"] = $userInfoData["data"]["id"];

				$sl_txt = [
					"mm_name" => ($userInfoData["data"]["kakao_account"]["name"] == "" ? "kakao_" . $userInfoData["data"]["id"] : $userInfoData["data"]["kakao_account"]["name"]),
					"mm_sex" => ($userInfoData["data"]["kakao_account"]["gender"] == "male" ? "남" : ($userInfoData["data"]["kakao_account"]["gender"] == "female" ? "여" : "")),
					"mm_nation" => (""),
					"mm_birth" => $userInfoData["data"]["kakao_account"]["birthyear"] . $userInfoData["data"]["kakao_account"]["birthday"],
					"mm_phone" => $userInfoData["data"]["kakao_account"]["phone_number"],
					"mm_ci" => "",
					"mm_di" => "kakao_" . $userInfoData["data"]["id"],
				];

				$this->site_model->setSiteLog(
					[
						"session_id" => session_id(),
						"sl_type" => "JOIN_" . $type,
						"sl_step" => "본인인증",
						"sl_txt" => json_encode($sl_txt, JSON_UNESCAPED_UNICODE)
					],
					[
						"session_id" => session_id(),
						"sl_type" => "JOIN_" . $type,
						"sl_step" => "본인인증",
						"sl_status" => "Y"
					]
				);

				$this->layout->shop('/member/kakao_login_callback', $data);
			}
		} else {
			$this->common->scriptReplace("/member/login", "문제가 발생해서 정상적으로 로그인이 되지 않았습니다.(2)");
			exit;
		}
	} //kakao_login_callback

	function naver_login_callback()
	{
		// callback 으로 들어온 값을 확인한다.
		$data["code"] = "";
		$data["msg"] = "";
		$data["res_id"] = "";
		$this->layout->shop('/member/naver_login_callback', $data);
	} //naver_login_callback

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

			# 헤더 정보 업데이트 : 매칭안되어 있으면 업데이트 처리한다.
			$headers = apache_request_headers();
			if (isset($headers["uuid"])) {
				$mem_device = $this->member_model->getMemberDevice(["uuid" => $headers["uuid"], "mm_idx" => 0]);
				# 빈값이 아니라면
				if ($mem_device != null) {
					$this->member_model->setMemberDevice(["mm_idx" => $mem_info["mm_idx"]], ["uuid" => $headers["uuid"]]);
				}
			}

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

	# 로그아웃
	function logout()
	{
		$this->common->removeSession("USER_INFO");
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

		$info = [
			"join_key" => $join_key,
			"join_type" => $join_type,
			"username" => $username,
			"email" => $email,
			"gender" => $gender,
			"phonenum" => $phonenum,
		];

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
		$data = null;

		#로그 생성
		$this->site_model->setSiteLog(
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN_MEM",
				"sl_step" => "토튜리얼",
				"sl_txt" => ""
			],
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN_MEM",
				"sl_step" => "토튜리얼",
				"sl_status" => "Y"
			]
		);
		//exit;
		$this->layout->shop('/member/join_mem_toturial', $data);
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
			go_location("/member/join_mem_step2");
		} else {
			#로그 생성
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
	function join_mem_step2()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_MEM", "스텝1");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		$this->layout->shop('/member/join_mem_step2', $data);
	}

	# 회원가입 스탭3
	function join_mem_step3()
	{
		$data = [];

		//# 데이터 검증및 뒤로가기 처리
		//$is_check = $this->site_model->checkSiteLog("JOIN_MEM","스텝2");
		//if($is_check==false){
		//	go_location("/member/join_start","데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		//}

		$data["policy1"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "policy_m"]);
		$data["policy2"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy_m"]);
		$data["policy3"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "location_m"]);
		$data["policy4"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy3_m"]);
		$data["policy5"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "adv_m"]);

		$this->layout->shop('/member/join_mem_step3', $data);
	}

	# 회원가입 스탭4
	function join_mem_step4_1()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_MEM", "스텝3");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		# uuid
		$headers = apache_request_headers();
		$uuid = isset($headers["uuid"]) ? $headers["uuid"] : "";
		$data["uuid"] = $uuid;

		# 데이터 리스트
		$data["ma_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["ma_price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
		$data["ma_work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
		$data["ma_week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);

		# 세션데이터
		$data["info"]  = $this->site_model->getStepTempData("JOIN_MEM", "스텝4_1");
		$this->layout->shop('/member/join_mem_step4_1', $data);
	}

	# 회원가입 스탭4
	function join_mem_step4_2()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_MEM", "스텝4_1");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		# uuid
		$headers = apache_request_headers();
		$uuid = isset($headers["uuid"]) ? $headers["uuid"] : "";
		$data["uuid"] = $uuid;

		# 데이터 리스트
		$data["ma_edu_list"]  = $this->site_model->getCodeList(["cd_grp" => "최종학력"]);

		# 세션데이터
		$data["info"]  = $this->site_model->getStepTempData("JOIN_MEM", "스텝4_2");
		$this->layout->shop('/member/join_mem_step4_2', $data);
	}

	# 회원가입 스탭4
	function join_mem_step4_3()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_MEM", "스텝4_2");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		# uuid
		$headers = apache_request_headers();
		$uuid = isset($headers["uuid"]) ? $headers["uuid"] : "";
		$data["uuid"] = $uuid;

		# 데이터 리스트
		$data["ma_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["ma_my_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "나의강점"]);

		# 세션데이터
		$data["info"]  = $this->site_model->getStepTempData("JOIN_MEM", "스텝4_3");
		$this->layout->shop('/member/join_mem_step4_3', $data);
	}

	# 회원가입 스탭4
	function join_mem_step4_4()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_MEM", "스텝4_3");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		# uuid
		$headers = apache_request_headers();
		$uuid = isset($headers["uuid"]) ? $headers["uuid"] : "";
		$data["uuid"] = $uuid;

		# 세션데이터
		$data["info"]  = $this->site_model->getStepTempData("JOIN_MEM", "스텝4_4");
		$this->layout->shop('/member/join_mem_step4_4', $data);
	}

	# 회원가입 완료
	function join_mem_finish()
	{
		$data = [];
		$this->layout->shop('/member/join_mem_finish', $data);
	}

	# 회원가입 토튜리얼
	function join_comp_toturial()
	{
		$data = [];

		#로그 생성
		$this->site_model->setSiteLog(
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN_COMP",
				"sl_step" => "토튜리얼",
				"sl_txt" => ""
			],
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN_COMP",
				"sl_step" => "토튜리얼",
				"sl_status" => "Y"
			]
		);
		$this->layout->shop('/member/join_comp_toturial', $data);
	}

	# 회원가입 스탭1
	function join_comp_step1()
	{
		$data = [];


		$this->layout->shop('/member/join_comp_step1', $data);
	}

	# 회원가입 스탭2
	function join_comp_step2()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_COMP", "스텝1");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		# 사업자 번호 확인 인증키
		$data["bus_key"] = $this->setting_model->getSettingInfo(["st_group" => "사업자번호", "st_key" => "인증키"]);

		$list = $this->site_model->getSiteLogList(
			["session_id" => session_id(), "sl_type" => "JOIN_COMP", "sl_status" => "Y"]
		);
		if ($list == null) {
			//    json_return("10","등록 데이터가 존재하지 않습니다.",$list);
		} else {
			$step_auth = $this->site_model->getStepData($list, "본인인증");
			$data["mm_phone"] = $step_auth["mm_phone"];
		}

		$this->layout->shop('/member/join_comp_step2', $data);
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
			go_location("/member/join_comp_step4");
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

	# 회원가입 스탭4
	function join_comp_step4()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_COMP", "스텝3");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		$this->layout->shop('/member/join_comp_step4', $data);
	}

	# 회원가입 스탭4
	function join_comp_step5()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_COMP", "스텝4");
		if ($is_check == false) {
			//go_location("/member/join_start","데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		$data["policy1"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "policy2_m"]);
		$data["policy2"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy_m"]);
		$data["policy3"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "location_m"]);
		$data["policy4"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy3_m"]);
		$data["policy5"] = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "adv_m"]);

		$this->layout->shop('/member/join_comp_step5', $data);
	}

	# 회원가입 스탭5
	function join_comp_step6()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_COMP", "스텝5");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		# uuid
		$headers = apache_request_headers();
		$uuid = isset($headers["uuid"]) ? $headers["uuid"] : "";
		$data["uuid"] = $uuid;

		$data["ma_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["ma_price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
		$data["ma_work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
		$data["ma_week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
		$data["ma_edu_list"]  = $this->site_model->getCodeList(["cd_grp" => "최종학력"]);

		# 세션 데이터
		$data["info"]  = $this->site_model->getStepTempData("JOIN_COMP", "스텝6");
		$this->layout->shop('/member/join_comp_step6', $data);
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
			#로그 생성
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

		$list = $this->site_model->getSiteLogList(
			["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_status" => "Y"]
		);
		if ($list == null) {
			//    json_return("10","등록 데이터가 존재하지 않습니다.",$list);
		} else {
			$step_auth = $this->site_model->getStepData($list, "본인인증");
			$data["mm_phone"] = $step_auth["mm_phone"];
		}

		//$data["mm_phone"] = $mm_phone;
		$this->layout->shop('/member/join_wmem_step1', $data);
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

	# 회원가입 스탭3
	function join_wmem_step3()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_MEM", "스텝2");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		$data["ma_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["ma_price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
		$data["ma_work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
		$data["ma_week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
		$data["ma_edu_list"]  = $this->site_model->getCodeList(["cd_grp" => "최종학력"]);
		$data["ma_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["ma_my_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "나의강점"]);

		$this->layout->shop('/member/join_wmem_step3', $data);
	}

	# 회원가입 스탭1
	function join_wcomp_step1()
	{
		$data = [];

		//# 데이터 검증및 뒤로가기 처리
		//$is_check = $this->site_model->checkSiteLog("JOIN_COMP","본인인증");
		//if($is_check==false){
		//	go_location("/member/join_start","데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		//}

		#로그 생성
		$this->site_model->setSiteLog(
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN_COMP",
				"sl_step" => "스텝1",
				"sl_txt" => ""
			],
			[
				"session_id" => session_id(),
				"sl_type" => "JOIN_COMP",
				"sl_step" => "스텝1",
				"sl_status" => "Y"
			]
		);

		# 사업자 번호 확인 인증키
		$data["bus_key"] = $this->setting_model->getSettingInfo(["st_group" => "사업자번호", "st_key" => "인증키"]);

		$list = $this->site_model->getSiteLogList(
			["session_id" => session_id(), "sl_type" => "JOIN_MEM", "sl_status" => "Y"]
		);
		if ($list == null) {
			//    json_return("10","등록 데이터가 존재하지 않습니다.",$list);
		} else {
			$step_auth = $this->site_model->getStepData($list, "본인인증");
			$data["mm_phone"] = $step_auth["mm_phone"];
		}

		$this->layout->shop('/member/join_wcomp_step1', $data);
	}

	# 회원가입 스탭2
	function join_wcomp_step2()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_COMP", "스텝2");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

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
	function join_wcomp_step4()
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("JOIN_COMP", "스텝3");
		if ($is_check == false) {
			go_location("/member/join_start", "데이터 가 유효하지 않습니다. 가입을 다시 진행해주세요.");
		}

		$data["ma_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["ma_price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
		$data["ma_work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
		$data["ma_week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
		$data["ma_edu_list"]  = $this->site_model->getCodeList(["cd_grp" => "최종학력"]);

		$this->layout->shop('/member/join_wcomp_step4', $data);
	}
}
