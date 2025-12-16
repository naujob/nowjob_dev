<?php
class Chat extends CI_Controller
{
	# 전역변수 선언
	public $mm_idx;
	public $mm_id;

	function __construct()
	{
		parent::__construct();

		$this->load->model('membermodel', 'member_model');
		$this->load->model('settingmodel', 'setting_model');
		$this->load->model('sitemodel', 'site_model');
		$this->load->model('commonmodel', 'common_model');

		# 로그인확인후 이동처리
		if ($this->user->isLogin() == false) {
			go_href("/member/login");
		}

		$this->mm_id = $this->user->getUserId();
		$this->mm_idx = $this->user->getUserIdx();
	}

	# 메인화면
	function index()
	{
		$data = [];
		$user_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);

		if ($user_info["mm_type"] == "COMP") {
			go_href("/chat/index_comp");
		} else {
			go_href("/chat/chat_list");
		}
	}

	# 기업 채팅
	function index_comp()
	{
		$data = [];

		//데이터 설정
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
		];
		$result = $this->member_model->getRequestRecruitList($params);

		$point_info = $this->setting_model->getCodeInfo(["cd_grp" => "채팅포인트", "cd_key" => "CHAT_POINT"]);

		$data["point_info"] = $point_info;
		$data["user_type"] = $this->user->getUserType();
		$data["list"] = $result["list"];
		$data["count"] = $result["count"];
		$this->layout->shop('/chat/index_comp', $data);
	}

	# 기업 채팅 (새 UI)
	function index_comp_new()
	{
		$data = [];

		//데이터 설정
		$point_info = $this->setting_model->getCodeInfo(["cd_grp" => "채팅포인트", "cd_key" => "CHAT_POINT"]);

		$data["point_info"] = $point_info;
		$data["user_type"] = $this->user->getUserType();
		$this->layout->shop('/chat/index_comp_new', $data);
	}

	function index_comp_ajax()
	{
		$data = [];

		//데이터 설정
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
		];
		$result1 = $this->member_model->getRequestRecruitList($params);

		$mm_idx = $this->user->getUserIdx();
		$result2 = $this->member_model->getRecruitActaiveList2($mm_idx);

		$mergedList = array_merge($result1["list"], $result2);

		//return $result["list"];
		//json_return("00","조회성공",["list"=>$result2["list"]]);
		//json_return("00","조회성공",["list"=>$result2]);
		json_return("00", "조회성공", ["list" => $mergedList]);
	}

	# 채팅
	function chat_list()
	{
		$data = [];

		$headers = apache_request_headers();
		$os_type = isset($data["os_type"]) ? $data["os_type"] : "";

		# 바로가기 처리
		$rm_idx = input_get("rm_idx");
		$view = input_get("view");

		if ($view == "true") {
			go_href("/chat/chat_view?rm_idx=" . $rm_idx);
			//exit;
		}

		$data["os_type"] = $os_type;
		$data["user_type"] = $this->user->getUserType();
		$this->layout->shop('/chat/chat_list', $data);
	}

	function chat_list2()
	{
		$data = [];

		$headers = apache_request_headers();
		$os_type = isset($data["os_type"]) ? $data["os_type"] : "";

		# 바로가기 처리
		$rm_idx = input_get("rm_idx");
		$view = input_get("view");

		if ($view == "true") {
			go_href("/chat/chat_view?rm_idx=" . $rm_idx);
			//exit;
		}

		$data["os_type"] = $os_type;
		$data["user_type"] = $this->user->getUserType();
		$this->layout->shop('/chat/chat_list2', $data);
	}

	function chat_list3()
	{
		$data = [];

		$headers = apache_request_headers();
		$os_type = isset($data["os_type"]) ? $data["os_type"] : "";

		# 바로가기 처리
		$rm_idx = input_get("rm_idx");
		$view = input_get("view");

		if ($view == "true") {
			go_href("/chat/chat_view?rm_idx=" . $rm_idx);
			//exit;
		}

		$data["os_type"] = $os_type;
		$data["user_type"] = $this->user->getUserType();
		$this->layout->shop('/chat/chat_list3', $data);
	}

	# 채팅상세
	function chat_view()
	{
		$data = [];
		$rm_idx = input_get("rm_idx");

		# 룸정보 가져오기
		$room = $this->common_model->getRoom(["rm_idx" => $rm_idx]);

		if ($room["rm_type"] == "recruit") {
			$data["recruit"] = $this->member_model->getRecruitDetail($room["rc_idx"], $this->mm_idx);
			$data["resume"] = $this->member_model->getResumeDetail($room["ru_idx"], $this->mm_idx);

			$data["career_list"] = $this->member_model->getResumeCareerList(["ru_idx" => $room["ru_idx"]]);
			$data["certi_list"] = $this->member_model->getResumeCertiList(["ru_idx" => $room["ru_idx"]]);
		}

		$data["user_type"] = $this->user->getUserType();
		$data["rm_idx"] = $rm_idx;
		$data["room"] = $room;
		//print_r($data);
		//exit;
		$this->layout->shop('/chat/chat_view', $data);
	}

	# 중고상품 채팅
	function trade_list()
	{
		$data = [];

		# 바로가기 처리
		$rm_idx = input_get("rm_idx");
		$view = input_get("view");

		if ($view == "true") {
			go_href("/chat/trade_view?rm_idx=" . $rm_idx);
			exit;
		}

		$data["user_type"] = $this->user->getUserType();
		$this->layout->shop('/chat/trade_list', $data);
	}

	# 중고상품 채팅상세
	function trade_view()
	{
		$data = [];

		$rm_idx = input_get("rm_idx");

		# 룸정보 가져오기
		$room = $this->common_model->getRoom(["rm_idx" => $rm_idx]);
		$td_idx = $room["td_idx"];

		$mm_idx = $this->user->getUserIdx();
		$trade_info = $this->site_model->getTrade(["td_idx" => $td_idx]);
		$reco_list = $this->site_model->getTradeRecoList($td_idx, $mm_idx);
		$addr_list = $this->member_model->getAddressList(["ad_type" => "TRADE", "ad_key" => $td_idx]);
		$mem_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);
		$mem_trade_info = $this->member_model->getMember(["mm_idx" => $trade_info["mm_idx"]]);

		# hit수 업데이트
		$trade_info["td_hit"] = $trade_info["td_hit"] + 1;
		$this->site_model->updateTrade(["td_hit" => $trade_info["td_hit"]], ["td_idx" => $td_idx]);


		$data["rm_idx"] = $rm_idx;
		$data["room"] = $room;
		$data["info"] = $trade_info;
		$data["reco_list"] = $reco_list;
		$data["addr_list"] = $addr_list;
		$data["mem_info"] = $mem_info;
		$data["mem_trade_info"] = $mem_trade_info;

		$this->layout->shop('/chat/trade_view', $data);
	}
}
