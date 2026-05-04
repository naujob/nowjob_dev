<?php
class Home extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('membermodel', 'member_model');
		$this->load->model('mongomodel', 'mongo_model');
		$this->load->model('settingmodel', 'setting_model');
		$this->load->model('sitemodel', 'site_model');
		$this->load->model('commonmodel', 'common_model');
		$this->mm_type = $this->user->getUserType();
	}

	# 메인화면
	function index()
	{
		$list = $this->member_model->db_list("st_code_addr", []);

		foreach ($list as $row) {
			$addr = explode(" ", $row["ca_short_addr"]);
			$update = [
				"ca_ad1" => $addr[0],
				"ca_ad2" => $addr[1],
				"ca_ad3" => $addr[2] == null ? "" : $addr[2],
			];
			//$this->member_model->db_update("st_code_addr",$update,["ca_idx"=>$row["ca_idx"]]);
		}

		$headers = apache_request_headers();
		$data["uuid"] = $headers["uuid"];
		$uuid = $data["uuid"];
		if ($uuid != "") {
			$info = [
				"uuid" => $uuid,
				"md_step3" => "Y",
			];

			# 로그인시 연동처리
			if ($this->user->isLogin()) {
				$info["mm_idx"] = $this->user->getUserIdx();
			}

			$this->member_model->setMemberDevice($info, ["uuid" => $uuid]);
		}

		# 몽고디비 동기화
		# 로그인중이면서 채팅정보 없다면 등록한다.
		if ($this->user->isLogin() == true) {
			$mm_id = $this->user->getUserId();
			$chk_id = $this->mongo_model->isUserData($mm_id);

			if ($chk_id == null) {
				$mem_info = $this->member_model->getMember(["mm_id" => $mm_id]);
				if ($mem_info != null) {
					$mem_device = $this->member_model->getMemberDevice(["uuid" => $uuid]);
					$this->mongo_model->insertUserInfo($mem_info, $mem_device["token"]);
				}
			}
		}

		if ($this->common->getLocation() == "W") {
			$this->index_pc();
		} else {
			if ($this->user->isLogin()) {
				if ($this->user->getUserType() == "COMP") {
					$this->index_comp();
				} else {
					$this->index_mem();
				}
			} else {
				$this->index_mem();
			}
		}

		/*
            $orig_date = get_today();
            # or you can use any other way to construct with (int timestamp)
            $dt = new DateTime(date('Y-m-d'), new DateTimeZone('UTC'));
            $ts = get_today();
            $today = new MongoDate($ts);
            echo $today;

            $tbl_alarm = get_mongodb("alarm");

            //알림 추가
            $alarm = [
                "user_id" => "underdragon@naver.com",
                "al_type" => "recruit",
                "al_key" => "" . 12,
                "al_text" => "<strong>즐겨찾기한 <em>" . "현종상회" . "</em>에서 <em>일자리를 등록</em>했습니다.</strong>",
                "al_etc" => "",
                "al_url" => "",
                "show_yn" => "N",
                "reg_dt"=>new MongoDate()
            ];

            $tbl_alarm->insert($alarm);
			*/
		//$seq = key_mongodb("user");
		//echo "회원키값 : " . $seq;

		/*
			try {
		       	$tbl_user = get_mongodb("user");
				$document = $tbl_user->find([]);

		    }
		    catch (Exception $e) {
		        echo $e->getMessage();
			}
    		*/
	}

	# PC 홈(검토용)
	function index_pc_new()
	{
		$mm_idx = $this->user->getUserIdx();
		$addr_info = $this->user->getAddrInfo();

		# 뜨는 업체
		$up_list = $this->common_model->getHomeUpCompList($addr_info, 8);

		# 팝업리스트
		$popup_list = $this->common_model->getEventPopupList($this->user->getUserType());

		$data["mm_type"] = $this->mm_type;
		$data["popup_list"] = $popup_list;
		$data["header_class"] = "transparency";
		$data["up_list"] = $up_list;
		$this->layout->shop('/home/index_pc_new', $data);
	}

	# 모바일 개인&비회원 홈(검토용)
	function index_mem_new()
	{
		$data = [];
		$addr_info = $this->user->getAddrInfo();

		# 팝업리스트
		$popup_list = $this->common_model->getEventPopupList($this->user->getUserType());
		$data["popup_list"] = $popup_list;
		$data["up_list"] = $this->common_model->getHomeUpCompList($addr_info, 8);  // 최대 8개 (2슬라이드 × 4개)
		$data["mm_type"] = $this->mm_type;

		$this->layout->shop('/home/index_mem_new', $data);
	}

	# 모바일 기업 홈(검토용)
	function index_comp_new()
	{
		$data = [];
		$mm_idx = $this->user->getUserIdx();
		$addr_info = $this->user->getAddrInfo();
		$data["mm_type"] = $this->mm_type;

		$popup_list = $this->common_model->getEventPopupList($this->user->getUserType());
		$data["popup_list"] = $popup_list;

		$this->layout->shop('/home/index_comp_new', $data);
	}

	# PC 홈
	function index_pc()
	{
		$mm_idx = $this->user->getUserIdx();
		$addr_info = $this->user->getAddrInfo();

		# 뜨는 업체
		$up_list = $this->common_model->getHomeUpCompList($addr_info, 8);

		# 팝업리스트
		$popup_list = $this->common_model->getEventPopupList($this->user->getUserType());

		$data["mm_type"] = $this->mm_type;
		$data["popup_list"] = $popup_list;
		$data["header_class"] = "transparency";
		$data["up_list"] = $up_list;
		$this->layout->shop('/home/index_pc', $data);
	}

	# 모바일 개인&비회원 홈
	function index_mem()
	{
		$data = [];
		$addr_info = $this->user->getAddrInfo();

		# 팝업리스트
		$popup_list = $this->common_model->getEventPopupList($this->user->getUserType());
		$data["popup_list"] = $popup_list;
		$data["up_list"] = $this->common_model->getHomeUpCompList($addr_info, 8);  // 최대 8개 (2슬라이드 × 4개)
		$data["mm_type"] = $this->mm_type;

		# 채용공고 목록과 검색 필터에 필요한 변수
		$data["addr_info"] = $addr_info;
		$data["wrok_list"] = $this->site_model->getCodeList(array("cd_grp" => "희망업무"));
		$data["work_type_list"] = $this->site_model->getCodeList(array("cd_grp" => "근무형태"));
		$data["week_list"] = $this->site_model->getCodeList(array("cd_grp" => "근무요일"));
		$data["search_txt"] = array(
			"text" => "", "addr" => "", "category" => "",
			"work_type" => "", "week" => "", "price_type" => "",
			"sex" => "", "sage" => "", "eage" => "",
			"addrName" => "", "searchAreaStr" => ""
		);
		$data["skip_search_state_load"] = false;
		$data["resume"] = $this->member_model->getResume(
			array("mm_idx" => $this->mm_idx, "ru_show_yn" => "Y", "ru_status" => "Y")
		);

		$this->layout->shop('/home/index_mem', $data);
	}

	# 모바일 기업 홈
	function index_comp()
	{
		$data = [];
		$mm_idx = $this->user->getUserIdx();
		$addr_info = $this->user->getAddrInfo();
		$data["mm_type"] = $this->mm_type;

		$popup_list = $this->common_model->getEventPopupList($this->user->getUserType());
		$data["popup_list"] = $popup_list;

		# 채용공고 목록과 검색 필터에 필요한 변수
		$data["addr_info"] = $addr_info;
		$data["up_list"] = $this->common_model->getHomeUpCompList($addr_info, 6);
		$data["wrok_list"] = $this->site_model->getCodeList(array("cd_grp" => "희망업무"));
		$data["work_type_list"] = $this->site_model->getCodeList(array("cd_grp" => "근무형태"));
		$data["week_list"] = $this->site_model->getCodeList(array("cd_grp" => "근무요일"));
		$data["search_txt"] = array(
			"text" => "", "addr" => "", "category" => "",
			"work_type" => "", "week" => "", "price_type" => "",
			"sex" => "", "sage" => "", "eage" => "",
			"addrName" => "", "searchAreaStr" => ""
		);
		$data["skip_search_state_load"] = false;
		$data["resume"] = null;

		$this->layout->shop('/home/index_comp', $data);
	}

	# 토튜리얼
	function toturial()
	{
		$this->toturial_cmp();
	}

	# 토튜리얼
	function toturial_mem()
	{
		$data = [];
		$this->layout->shop('/home/toturial_mem', $data);
	}

	# 토튜리얼
	function toturial_cmp()
	{
		$data = [];
		$this->layout->shop('/home/toturial_comp', $data);
	}

	# 스플레시
	function splash()
	{
		$data = [];
		$is_first = input_get("is_first", "N");

		$headers = apache_request_headers();

		$step = "grant";
		$uuid = $headers["uuid"];
		$os_type = isset($headers["os_type"]) ? $headers["os_type"] : "";

		# 첫접속이면 승인한것 모두 제거한다.
		if ($is_first == "Y") {
			$this->member_model->deleteMemberDevice(["uuid" => $uuid]);
		}

		if ($uuid != "") {
			$mem_device = $this->member_model->getMemberDevice(["uuid" => $uuid]);
			if ($mem_device != null) {
				if ($mem_device["md_step3"] == "Y") {
					$step = "main";
				} else if ($mem_device["md_step2"] == "Y" && $mem_device["md_step3"] == "N") {
					$step = "login";
				}
			} else {
				$info = [
					"uuid" => $uuid,
					"os_type" => $os_type,
					"md_step1" => "Y"
				];
				$this->member_model->setMemberDevice($info, ["uuid" => $uuid]);
			}

			# 로그인중이면서 uuid있으면서 연동이 안되어있다면 연동처리한다.
			if ($this->user->isLogin()) {
				$this->member_model->setMemberDevice(["mm_idx" => $this->user->getUserIdx()], ["uuid" => $uuid]);
			}

			# 로그인중이면서 채팅정보 없다면 등록한다.
			if ($this->user->isLogin() == true) {
				$mm_id = $this->user->getUserId();

				$mem_info = $this->member_model->getMember(["mm_id" => $mm_id]);
				if ($mem_info != null) {
					$this->mongo_model->insertUserInfo($mem_info, $mem_device["token"]);
				}
			}
		}

		$data["uuid"] = $headers["uuid"];
		$data["step"] = $step;
		$this->layout->shop('/home/splash', $data);
	}

	# QR 코드 다운로드 페이지
	function qr_ko()
	{
		$data = [];
		$this->layout->shop('/home/qr_ko', $data);
	}

	# QR 코드 다운로드 페이지 (중문)
	function qr_ch()
	{
		$data = [];
		$this->layout->shop('/home/qr_ch', $data);
	}

	function about()
	{
		$data = [];
		$this->layout->shop('/home/about', $data);
	}

	# PC 약관1
	function site_policy()
	{
		$data = [];
		$info = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "policy_pc"]);
		$data["info"] = $info;
		$this->layout->shop('/home/site_policy', $data);
	}

	# PC 약관1
	function site_policy2()
	{
		$data = [];
		$info = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "policy2_pc"]);
		$data["info"] = $info;
		$this->layout->shop('/home/site_policy2', $data);
	}

	# PC 약관2
	function site_privacy()
	{
		$data = [];
		$info = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy_pc"]);
		$data["info"] = $info;
		$this->layout->shop('/home/site_privacy', $data);
	}

	# PC 약관3
	function site_terms2()
	{
		$data = [];
		$info = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "privacy3_pc"]);
		$data["info"] = $info;
		$this->layout->shop('/home/site_terms2', $data);
	}

	# PC 약관4
	function site_terms3()
	{
		$data = [];
		$info = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "location_pc"]);
		$data["info"] = $info;
		$this->layout->shop('/home/site_terms3', $data);
	}

	# PC 약관4
	function site_terms4()
	{
		$data = [];
		$info = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "trade_goods_pc"]);
		$data["info"] = $info;
		$this->layout->shop('/home/site_terms4', $data);
	}

	# PC 약관4
	function site_terms5()
	{
		$data = [];
		$info = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "adv_pc"]);
		$data["info"] = $info;
		$this->layout->shop('/home/site_terms5', $data);
	}

	# PC 약관4
	function site_terms6()
	{
		$data = [];
		$info = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "trade_term_pc"]);
		$data["info"] = $info;
		$this->layout->shop('/home/site_terms6', $data);
	}

	function search()
	{
		$data = [];
		$text = input_get("text");
		$data["text"] = $text;

		if ($text == "") {
			go_location("/", "잘못된 접근입니다.");
		}
		$data["mm_type"] = $this->mm_type;
		$tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);
		$data["tag_list"] = $tag_info["search"] != "" ? explode(",", $tag_info["search"]) : [];
		$this->layout->shop('/home/search', $data);
	}

	# 전체검색(시급적용)
	function search_new()
	{
		$data = [];
		$text = input_get("text");
		$data["text"] = $text;

		if ($text == "") {
			go_location("/", "잘못된 접근입니다.");
		}

		$tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);
		$data["tag_list"] = $tag_info["search"] != "" ? explode(",", $tag_info["search"]) : [];
		$this->layout->shop('/home/search_new', $data);
	}

	function search_recruit()
	{
		$data = [];
		$text = input_get("text");
		$data["text"] = $text;

		if ($text == "") {
			go_location("/", "잘못된 접근입니다.");
		}

		$tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);
		$data["tag_list"] = $tag_info["search"] != "" ? explode(",", $tag_info["search"]) : [];
		$this->layout->shop('/home/search_recruit', $data);
	}

	function search_resume()
	{
		$data = [];
		$text = input_get("text");
		$data["text"] = $text;

		if ($text == "") {
			go_location("/", "잘못된 접근입니다.");
		}

		$tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);
		$data["tag_list"] = $tag_info["search"] != "" ? explode(",", $tag_info["search"]) : [];
		$this->layout->shop('/home/search_resume', $data);
	}

	function search_trade()
	{
		$data = [];
		$text = input_get("text");
		$data["text"] = $text;

		if ($text == "") {
			go_location("/", "잘못된 접근입니다.");
		}

		$tag_info = $this->setting_model->getSettingInfo(["st_group" => "태그", "st_key" => "search"]);
		$data["tag_list"] = $tag_info["search"] != "" ? explode(",", $tag_info["search"]) : [];
		$this->layout->shop('/home/search_trade', $data);
	}

	// 개인정보처리방침 파일 보여주기 2025.07.18
	function site_privacy_url()
	{
		$data = [];
		$data["image_path"] = "/resource/shop/web/img/site_privacy_url/PRIVACY_POLICY.png";

		// layout 없이 바로 view 로드
		$this->load->view('web/home/site_privacy_url', $data);
	}
}
