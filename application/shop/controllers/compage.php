<?php
class Compage extends CI_Controller
{
	# 전역변수 선언
	public $mm_idx;
	public $mm_id;
	public $mm_type;

	function __construct()
	{
		parent::__construct();

		$this->load->model('membermodel', 'member_model');
		$this->load->model('sitemodel', 'site_model');
		$this->load->model('settingmodel', 'setting_model');
		$this->load->model('ordermodel', 'order_model');
		$this->load->model('commonmodel', 'common_model');
		$this->load->model('boardmodel', 'board_model');
		$this->load->model('recruitmodel', 'recruit_model');

		# 로그인확인후 이동처리
		if ($this->user->isLogin() == false) {
			go_location("/member/login");
		}

		$this->mm_id = $this->user->getUserId();
		$this->mm_idx = $this->user->getUserIdx();
		$this->mm_type = $this->user->getUserType();

		# 로그인확인후 이동처리
		if ($this->mm_type != "COMP") {
			go_location("/mypage/index");
		}

		# 자동 쿠폰 발급 처리
		if ($this->mm_idx != "") {
			$this->site_model->getAutoRegistCoupon($this->mm_idx);
		}
	}

	# 회원용 마이페이지
	function index()
	{
		$data = [];

		$gbn = $this->input->get('gbn');

		$mm_id = $this->user->getUserId();
		$data["mem_info"] = $this->member_model->getMember(["mm_id" => $mm_id]);
		$data["hit_cnt"] = 20;
		$data["req_cnt"] = 24;
		$data["fav_cnt"] = 12;
		$data["enc_data"] = $this->member_model->getNiceEncData();
		$data["gbn"] = $gbn;

		# 웹접속인경우 경로 이동처리
		if (device_type() == "W") {
			go_location("/compage/point_charge_step1");
		}

		$this->layout->shop('/compage/index', $data);
	}

	# 회원용 마이페이지(검토용)
	function index_new()
	{
		$data = [];

		$gbn = $this->input->get('gbn');

		$mm_id = $this->user->getUserId();
		$data["mem_info"] = $this->member_model->getMember(["mm_id" => $mm_id]);
		$data["hit_cnt"] = 20;
		$data["req_cnt"] = 24;
		$data["fav_cnt"] = 12;
		$data["enc_data"] = $this->member_model->getNiceEncData();
		$data["gbn"] = $gbn;

		# 웹접속인경우 경로 이동처리
		if (device_type() == "W") {
			go_location("/compage/point_charge_step1");
		}

		$this->layout->shop('/compage/index_new', $data);
	}

	# 마이페이지(검토용)
	function mypage_new($reqgbn = null)
	{
		$data = [];

		$data["info"] = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$data["enc_data"] = $this->member_model->getNiceEncData();
		$data["reqgbn"] = $reqgbn;
		$data["comp_popup_info"] = $data["info"];
		$data["comp_popup_enc_data"] = $data["enc_data"];
		$data["comp_popup_reqgbn"] = $reqgbn;
		$this->layout->shop('/compage/mypage_new', $data);
	}

	# 마이페이지
	function mypage($reqgbn = null)
	{
		$data = [];

		$data["info"] = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$data["enc_data"] = $this->member_model->getNiceEncData();
		$data["reqgbn"] = $reqgbn;
		$data["comp_popup_info"] = $data["info"];
		$data["comp_popup_enc_data"] = $data["enc_data"];
		$data["comp_popup_reqgbn"] = $reqgbn;
		$this->layout->shop('/compage/mypage', $data);
	}

	# 포인트 충전 스텝1
	function point_charge_step1()
	{
		$data = [];

		$data["mem_info"] = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$data["point_list"] = $this->setting_model->getCodeList(["cd_grp" => "구매포인트", "use_yn" => "Y"]);

		#가입 당일 구입시 30% 할인추가.
		$mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$reg_dt = substr($mem_info["reg_dt"], 0, 10);

		if ($reg_dt == Date('Y-m-d')) {
			foreach ($data["point_list"] as $key => $row) {
				$data["point_list"][$key]["cd_val"] = $data["point_list"][$key]["cd_val"] - round($data["point_list"][$key]["cd_val"] * 0.3);
			}
		}

		$this->layout->shop('/compage/point_charge_step1', $data);
	}

	# 포인트 충전 스텝2
	function point_charge_step2($reqidx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		//$is_check = $this->site_model->checkSiteLog("POINT_ORDER","스텝1");
		//if($is_check==false){
		//	go_location("/compage/point_charge_step1","정상적인 결제 진행이 아닙니다. 포인트 결제를 다시 진행해주세요.");
		//}

		$info = $this->site_model->getStepTempData("POINT_ORDER", "스텝1");
		$data["point_key"] = $info["point_key"];
		$data["point_price"] = $info["point_price"];

		if ($reqidx != "" && $reqidx != "0") {

			$query_point  = "select * from st_coupon_list ";
			$query_point .= "where cl_idx = '" . $reqidx . "' ";

			$result_point = $this->db->query($query_point);
			$count_point = $result_point->row_array();
			if ($count_point != null) {
				$cnt_point = $count_point["cl_price"];
			}
			if ($cnt_point < 2000) {
				$data["point_key"] = "P001";
				$data["point_price"] = "1000";
			} else if ($cnt_point < 5000) {
				$data["point_key"] = "P002";
				$data["point_price"] = "2000";
			} else if ($cnt_point < 10000) {
				$data["point_key"] = "P003";
				$data["point_price"] = "5000";
			} else if ($cnt_point < 50000) {
				$data["point_key"] = "P004";
				$data["point_price"] = "10000";
			} else if ($cnt_point < 100000) {
				$data["point_key"] = "P005";
				$data["point_price"] = "50000";
			} else {
				$data["point_key"] = "P006";
				$data["point_price"] = "100000";
			}
		}

		# 사용가능 쿠폰조회
		$params = [
			"pagesize" => 100,
			"page" => 1,
			"cp_idx" => "",
			"search_type" => "",
			"search_value" => "",
			"mm_idx" => $this->mm_idx,
			"cl_use_yn" => "N",
			"cl_limit_price" => $data["point_price"],
		];
		$result = $this->site_model->getCouponListList($params);

		$data["coupon_list"] = $result["list"];
		$data["coupon_count"] = $this->site_model->getCouponListCount($this->mm_idx);
		$data["valid_count"] = $this->site_model->getCouponListCount($this->mm_idx, $data["point_price"], "N");
		$data["pg_info"] = $this->order_model->getPgInfo();
		$data["reqidx"] = "" . $reqidx;


		$this->layout->shop('/compage/point_charge_step2', $data);
	}

	# 포인트 충전 스텝3
	function point_charge_step3($o_num)
	{
		$data = [];

		if ($o_num == null) {
			go_location("/compage/point_charge_step1", "정상적인 결제 진행아 아닙니다. 포인트 결제를 다시 진행해주세요.");
		}

		$order_info = $this->order_model->getOrderMaster($o_num);

		if ($order_info == null) {
			go_location("/compage/point_charge_step1", "정상적인 결제 진행아 아닙니다. 포인트 결제를 다시 진행해주세요.");
		}

		$data["order_info"] = $order_info;

		$point_info = $this->setting_model->getCodeInfo(["cd_grp" => "구매포인트", "cd_key" => $order_info["o_goods_code"]]);
		$data["point_info"] = $point_info;

		$this->layout->shop('/compage/point_charge_step3', $data);
	}


	# 포인트 충전 리스트
	function point_charge_list()
	{
		$data = [];
		$data["mem_info"] = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$data["order_list"] = $this->order_model->getChargePointList($this->mm_idx);
		$this->layout->shop('/compage/point_charge_list', $data);
	}

	# 포인트 사용 리스트
	function point_use_list()
	{
		$data = [];
		$data["use_list"] = $this->order_model->getUsePointList($this->mm_idx);
		$this->layout->shop('/compage/point_use_list', $data);
	}

	# 광고 구매
	function point_promotion_buy()
	{
		$data = [];

		$recruit_list = $this->member_model->getAddrRecruitList($this->mm_idx);

		$addr_list = [];
		foreach ($recruit_list as $row) {
			$addr_list[] = trim($row["ad_addr"]);
		}

		$addr_list = array_unique($addr_list);

		$data["mem_info"] = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$data["addr_list"] = $addr_list;
		$data["recruit_list"] = $recruit_list;
		$data["point1"] = $this->setting_model->getCodeInfo(["cd_key" => "C001"]);
		$data["point2"] = $this->setting_model->getCodeInfo(["cd_key" => "C002"]);
		$data["point3"] = $this->setting_model->getCodeInfo(["cd_key" => "C003"]);
		$data["point4"] = $this->setting_model->getCodeInfo(["cd_key" => "C004"]);
		$data["promotion_list"] = $this->setting_model->getCodeList(["cd_grp" => "업체광고구매"]);

		$data["promotion_buy_list"] = $this->site_model->getPromotionList(["mm_idx" => $this->mm_idx, "pm_status" => "Y"]);

		$this->layout->shop('/compage/point_promotion_buy', $data);
	}

	# 광고 구매 진행.
	function point_promotion_payment($type)
	{
		$data = [];
		$sdate = "";
		$edate = "";
		if ($type == null) {
			go_location("/compage/point_promotion_buy", "잘못된 접근입니다.");
		}

		$promotion_info = $this->setting_model->getCodeInfo(["cd_grp" => "업체광고구매", "cd_key" => $type]);
		if ($promotion_info == null) {
			go_location("/compage/point_promotion_buy", "잘못된 접근입니다.");
		}

		$recruit_list = $this->member_model->getAddrRecruitList($this->mm_idx);
		$addr_list = [];
		foreach ($recruit_list as $row) {
			$addr = trim($row["ad_addr"]);
			$addr_list[$addr] = $addr;
		}

		$addr_list = array_unique($addr_list);

		$data["type"] = $type;
		$data["recruit_list"] = $recruit_list;
		$data["addr_list"] = $addr_list;
		$data["mem_info"] = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$data["promotion_info"] = $promotion_info;

		$data["promotion_buy_list"] = $this->site_model->getPromotionList(["mm_idx" => $this->mm_idx, "pm_status" => "Y"]);

		$this->layout->shop('/compage/point_promotion_payment', $data);
	}

	# 광고 관리
	function point_promotion_list()
	{
		$data = [];
		$data["promotion_list"] = $this->site_model->getPromotionList(["mm_idx" => $this->mm_idx, "pm_status" => "Y"]);
		$this->layout->shop('/compage/point_promotion_list', $data);
	}

	# 채용공고 리스트(확인용)
	function recruit_list_new()
	{

		$gbn = $this->input->get('gbn');

		$data = [];
		if ($gbn == 'ing') {
			$data["recruit_list"] = $this->recruit_model->getRecruitActaiveList($this->mm_idx);
		} elseif ($gbn == 'wait') {
			$data["recruit_list"] = $this->recruit_model->getRecruitWaitList($this->mm_idx);
		} elseif ($gbn == 'close') {
			$data["recruit_list"] = $this->recruit_model->getRecruitCloseList($this->mm_idx);
		} else {
			$data["recruit_list"] = $this->recruit_model->getRecruitAllList($this->mm_idx);
		}

		$data["recruit_count"] = $this->recruit_model->getRecruitCountForTab($this->mm_idx);
		$this->layout->shop('/compage/recruit_list_new', $data);
	}

	# 채용공고 리스트
	function recruit_list()
	{

		$gbn = $this->input->get('gbn');

		$data = [];
		if ($gbn == 'ing') {
			$data["recruit_list"] = $this->recruit_model->getRecruitActaiveList($this->mm_idx);
		} elseif ($gbn == 'wait') {
			$data["recruit_list"] = $this->recruit_model->getRecruitWaitList($this->mm_idx);
		} elseif ($gbn == 'close') {
			$data["recruit_list"] = $this->recruit_model->getRecruitCloseList($this->mm_idx);
		} else {
			$data["recruit_list"] = $this->recruit_model->getRecruitAllList($this->mm_idx);
		}

		$data["recruit_count"] = $this->recruit_model->getRecruitCountForTab($this->mm_idx);
		$this->layout->shop('/compage/recruit_list', $data);
	}

	# 채용공고 등록 - 웹(검토용)
	function recruit_write_new($rc_idx = 0)
	{
		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_name" => "",
			"rc_sdate" => "",
			"rc_edate" => "",
			"addr" => "",
			"expose_addr1" => null,
			"expose_addr2" => null,
			"rc_work" => "",
			"rc_sex" => "",
			"rc_nation" => "",
			"rc_sage" => "",
			"rc_eage" => "",
			"rc_work_type" => "",
			"rc_week_yn" => "",
			"rc_am_stime" => "",
			"rc_am_etime" => "",
			"rc_pm_stime" => "",
			"rc_pm_etime" => "",
			"rc_work_dt" => "",
			"rc_price_type" => "",
			"rc_price_rep" => "",
			"rc_price_pay" => "",
			"rc_week" => "",
			"rc_desc" => "",
			"rc_hash" => "",
			"rc_img1" => "",
			"rc_img2" => "",
			"rc_img3" => "",
			"rc_img4" => "",
			"rc_img5" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list_new", "채용공고가 존재하지 않습니다.");
		}

		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list_new", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_name"] = $recruit_info["rc_name"];
			$info["rc_sdate"] = get_date($recruit_info["rc_sdate"]);
			$info["rc_edate"] = get_date($recruit_info["rc_edate"]);
			$info["addr"] = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $recruit_info["rc_idx"], "ad_type" => "RECRUIT"]);
			$info["expose_addr1"] = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $recruit_info["rc_idx"], "ad_type" => "RECRUIT_EXPOSE_1"]);
			$info["expose_addr2"] = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $recruit_info["rc_idx"], "ad_type" => "RECRUIT_EXPOSE_2"]);

			$info["rc_work"] = explode(",", $recruit_info["rc_work"]);
			$info["rc_sex"] = explode(",", $recruit_info["rc_sex"]);
			$info["rc_nation"] = explode(",", $recruit_info["rc_nation"]);
			$info["rc_sage"] = $recruit_info["rc_sage"];
			$info["rc_eage"] = $recruit_info["rc_eage"];

			$info["rc_work_type"] = explode(",", $recruit_info["rc_work_type"]);
			$info["rc_week_yn"] = $recruit_info["rc_week_yn"];
			$info["rc_am_stime"] = $recruit_info["rc_am_stime"];
			$info["rc_am_etime"] = $recruit_info["rc_am_etime"];
			$info["rc_pm_stime"] = $recruit_info["rc_pm_stime"];
			$info["rc_pm_etime"] = $recruit_info["rc_pm_etime"];
			$info["rc_work_dt"] = $recruit_info["rc_work_dt"];
			$info["rc_price_type"] = $recruit_info["rc_price_type"];
			$info["rc_price_rep"] = $recruit_info["rc_price_rep"];
			$info["rc_price_pay"] = $recruit_info["rc_price_pay"];

			/* 전체 시급 적용전까지 임시 */
			$info["rc_day_price"] = $recruit_info["rc_day_price"];
			$info["rc_week_price"] = $recruit_info["rc_week_price"];
			$info["rc_month_price"] = $recruit_info["rc_month_price"];

			$info["rc_week"] = explode(",", $recruit_info["rc_week"]);
			$info["rc_life_type"] = explode(",", $recruit_info["rc_life_type"]);
			$info["rc_desc"] = $recruit_info["rc_desc"];
			$info["rc_hash"] = $recruit_info["rc_hash"];
			$info["rc_img1"] = $recruit_info["rc_img1"];
			$info["rc_img2"] = $recruit_info["rc_img2"];
			$info["rc_img3"] = $recruit_info["rc_img3"];
			$info["rc_img4"] = $recruit_info["rc_img4"];
			$info["rc_img5"] = $recruit_info["rc_img5"];

			$add_info = $this->member_model->getMemberInfo($this->mm_idx);
			$info["mem_info"] = $add_info;
			$info["mm_comp_num"] = $add_info["mm_comp_num"];
		} else {
			$add_info = $this->member_model->getMemberInfo($this->mm_idx);
			$mem_addr = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $this->mm_idx, "ad_type" => "MEMBER"]);
			$info["mem_info"] = $add_info;

			if ($add_info != null) {
				$info["rc_name"] = "";
				$info["rc_sdate"] = "";
				$info["rc_edate"] = "";
				$info["addr"] = $mem_addr;
				$info["rc_work"] = explode(",", $add_info["ma_work"]);
				$info["rc_sex"] = explode(",", $add_info["ma_sex"]);
				$info["rc_nation"] = explode(",", $add_info["ma_nation"]);
				$info["rc_sage"] = $add_info["ma_sage"];
				$info["rc_eage"] = $add_info["ma_eage"];
				$info["rc_work_type"] = explode(",", $add_info["ma_work_type"]);
				$info["rc_week_yn"] = $add_info["ma_week_yn"];
				$info["rc_am_stime"] = "";
				$info["rc_am_etime"] = "";
				$info["rc_pm_stime"] = "";
				$info["rc_pm_etime"] = "";
				$info["rc_work_dt"] = "";
				$info["rc_price_type"] = "";
				$info["rc_price_rep"] = "";
				$info["rc_price_pay"] = "";
				$info["rc_life_type"] = explode(",", $add_info["ma_life_type"]);
				$info["rc_week"] = explode(",", $add_info["ma_week"]);
				$info["rc_desc"] = $add_info["ma_desc"];
				$info["rc_hash"] = "";
				$info["rc_img1"] = "";
				$info["rc_img2"] = "";
				$info["rc_img3"] = "";
				$info["rc_img4"] = "";
				$info["rc_img5"] = "";
				$info["mm_comp_num"] = $add_info["mm_comp_num"];
			}
		}

		$time_list = [];
		for ($icnt = 0; $icnt < 24; $icnt++) {
			$time_list[] = ($icnt < 10 ? "0" . $icnt : $icnt) . ":00";
			$time_list[] = ($icnt < 10 ? "0" . $icnt : $icnt) . ":30";
		}


		$data["time_list"] = $time_list;
		$data["rc_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["rc_price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
		$data["rc_work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
		$data["rc_week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
		$data["rc_last_recruite_list"]  = $this->member_model->getRecruitList(["mm_idx" => $this->mm_idx]);



		# 데이터 리스트
		$data["info"] = $info;

		$this->layout->shop('/compage/recruit_write_new', $data);
	}

	# 채용공고 등록 - 웹
	function recruit_write($rc_idx = 0)
	{
		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_name" => "",
			"rc_sdate" => "",
			"rc_edate" => "",
			"addr" => "",
			"rc_work" => "",
			"rc_sex" => "",
			"rc_nation" => "",
			"rc_sage" => "",
			"rc_eage" => "",
			"rc_work_type" => "",
			"rc_week_yn" => "",
			"rc_am_stime" => "",
			"rc_am_etime" => "",
			"rc_pm_stime" => "",
			"rc_pm_etime" => "",
			"rc_work_dt" => "",
			"rc_price_type" => "",
			"rc_price_rep" => "",
			"rc_price_pay" => "",
			"rc_week" => "",
			"rc_desc" => "",
			"rc_hash" => "",
			"rc_img1" => "",
			"rc_img2" => "",
			"rc_img3" => "",
			"rc_img4" => "",
			"rc_img5" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}

		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_name"] = $recruit_info["rc_name"];
			$info["rc_sdate"] = get_date($recruit_info["rc_sdate"]);
			$info["rc_edate"] = get_date($recruit_info["rc_edate"]);
			$info["addr"] = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $recruit_info["rc_idx"], "ad_type" => "RECRUIT"]);

			$info["rc_work"] = explode(",", $recruit_info["rc_work"]);
			$info["rc_sex"] = explode(",", $recruit_info["rc_sex"]);
			$info["rc_nation"] = explode(",", $recruit_info["rc_nation"]);
			$info["rc_sage"] = $recruit_info["rc_sage"];
			$info["rc_eage"] = $recruit_info["rc_eage"];

			$info["rc_work_type"] = explode(",", $recruit_info["rc_work_type"]);
			$info["rc_week_yn"] = $recruit_info["rc_week_yn"];
			$info["rc_am_stime"] = $recruit_info["rc_am_stime"];
			$info["rc_am_etime"] = $recruit_info["rc_am_etime"];
			$info["rc_pm_stime"] = $recruit_info["rc_pm_stime"];
			$info["rc_pm_etime"] = $recruit_info["rc_pm_etime"];
			$info["rc_work_dt"] = $recruit_info["rc_work_dt"];
			$info["rc_price_type"] = $recruit_info["rc_price_type"];
			$info["rc_price_rep"] = $recruit_info["rc_price_rep"];
			$info["rc_price_pay"] = $recruit_info["rc_price_pay"];

			/* 전체 시급 적용전까지 임시 */
			$info["rc_day_price"] = $recruit_info["rc_day_price"];
			$info["rc_week_price"] = $recruit_info["rc_week_price"];
			$info["rc_month_price"] = $recruit_info["rc_month_price"];

			$info["rc_week"] = explode(",", $recruit_info["rc_week"]);
			$info["rc_life_type"] = explode(",", $recruit_info["rc_life_type"]);
			$info["rc_desc"] = $recruit_info["rc_desc"];
			$info["rc_hash"] = $recruit_info["rc_hash"];
			$info["rc_img1"] = $recruit_info["rc_img1"];
			$info["rc_img2"] = $recruit_info["rc_img2"];
			$info["rc_img3"] = $recruit_info["rc_img3"];
			$info["rc_img4"] = $recruit_info["rc_img4"];
			$info["rc_img5"] = $recruit_info["rc_img5"];

			$add_info = $this->member_model->getMemberInfo($this->mm_idx);
			$info["mem_info"] = $add_info;
			$info["mm_comp_num"] = $add_info["mm_comp_num"];
		} else {
			$add_info = $this->member_model->getMemberInfo($this->mm_idx);
			$mem_addr = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $this->mm_idx, "ad_type" => "MEMBER"]);
			$info["mem_info"] = $add_info;

			if ($add_info != null) {
				$info["rc_name"] = "";
				$info["rc_sdate"] = "";
				$info["rc_edate"] = "";
				$info["addr"] = $mem_addr;
				$info["rc_work"] = explode(",", $add_info["ma_work"]);
				$info["rc_sex"] = explode(",", $add_info["ma_sex"]);
				$info["rc_nation"] = explode(",", $add_info["ma_nation"]);
				$info["rc_sage"] = $add_info["ma_sage"];
				$info["rc_eage"] = $add_info["ma_eage"];
				$info["rc_work_type"] = explode(",", $add_info["ma_work_type"]);
				$info["rc_week_yn"] = $add_info["ma_week_yn"];
				$info["rc_am_stime"] = "";
				$info["rc_am_etime"] = "";
				$info["rc_pm_stime"] = "";
				$info["rc_pm_etime"] = "";
				$info["rc_work_dt"] = "";
				$info["rc_price_type"] = "";
				$info["rc_price_rep"] = "";
				$info["rc_price_pay"] = "";
				$info["rc_life_type"] = explode(",", $add_info["ma_life_type"]);
				$info["rc_week"] = explode(",", $add_info["ma_week"]);
				$info["rc_desc"] = $add_info["ma_desc"];
				$info["rc_hash"] = "";
				$info["rc_img1"] = "";
				$info["rc_img2"] = "";
				$info["rc_img3"] = "";
				$info["rc_img4"] = "";
				$info["rc_img5"] = "";
				$info["mm_comp_num"] = $add_info["mm_comp_num"];
			}
		}

		$time_list = [];
		for ($icnt = 0; $icnt < 24; $icnt++) {
			$time_list[] = ($icnt < 10 ? "0" . $icnt : $icnt) . ":00";
			$time_list[] = ($icnt < 10 ? "0" . $icnt : $icnt) . ":30";
		}


		$data["time_list"] = $time_list;
		$data["rc_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["rc_price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
		$data["rc_work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
		$data["rc_week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
		$data["rc_last_recruite_list"]  = $this->member_model->getRecruitList(["mm_idx" => $this->mm_idx]);



		# 데이터 리스트
		$data["info"] = $info;

		$this->layout->shop('/compage/recruit_write', $data);
	}

	/* 사용하지 않는 함수 - recruit_write1_1로 대체됨
	# 채용공고 등록1(검토용)
	function recruit_write1_new($rc_idx = 0)
	{
		$data = [];

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_name" => "",
			"rc_sdate" => "",
			"rc_edate" => "",
			"addr" => "",
			"rc_idx" => $rc_idx,
			"rc_work" => "",
			"rc_sex" => "",
			"rc_nation" => "",
			"rc_sage" => "",
			"rc_eage" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			//go_location("/compage/recruit_list","채용공고가 존재하지 않습니다.");
			$this->common->scriptReplace("/compage/index");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list_new", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_name"] = $recruit_info["rc_name"];
			$info["rc_sdate"] = get_date($recruit_info["rc_sdate"]);
			$info["rc_edate"] = get_date($recruit_info["rc_edate"]);
			$info["addr"] = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $recruit_info["rc_idx"], "ad_type" => "RECRUIT"]);
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝1");
			if ($temp_info != null) {
				$rc_sdate = "";
				$rc_edate = "";
				$arr_date = explode("~", $temp_info["rc_date"]);
				$rc_sdate = trim($arr_date[0]);
				$rc_edate = trim($arr_date[1]);

				$info["rc_name"] = $temp_info["rc_name"];
				$info["rc_sdate"] = $rc_sdate;
				$info["rc_edate"] = $rc_edate;
				$info["addr"] = [
					"ad_zipcode" => $temp_info["ad_zipcode"],
					"ad_jibun" => $temp_info["ad_jibun"],
					"ad_doro" => $temp_info["ad_doro"],
					"ad_addr" => $temp_info["ad_addr"],
					"ad_detail" => $temp_info["ad_detail"],
					"ad_gps_x" => $temp_info["ad_gps_x"],
					"ad_gps_y" => $temp_info["ad_gps_y"],
				];
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				$mem_addr = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $this->mm_idx, "ad_type" => "MEMBER"]);
				if ($add_info != null) {
					$info["rc_name"] = "";
					$info["rc_sdate"] = "";
					$info["rc_edate"] = "";
					$info["addr"] = $mem_addr;
				}
			}
		}

		# 데이터 리스트
		$data["info"] = $info;
		$this->layout->shop('/compage/recruit_write1_new', $data);
	}
	*/

	/* 사용하지 않는 함수 - recruit_write2_1로 대체됨
	# 채용공고 등록2(검토용)
	function recruit_write2_new($rc_idx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("RECRUIT", "스텝1");
		if ($is_check == false) {
			//go_location("/compage/recruit_list","데이터 가 유효하지 않습니다. 채용공고등록을 다시 진행해주세요.");
			$this->common->scriptReplace("/compage/index");
		}

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_work" => "",
			"rc_sex" => "",
			"rc_nation" => "",
			"rc_sage" => "",
			"rc_eage" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list_new", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_work"] = explode(",", $recruit_info["rc_work"]);
			$info["rc_sex"] = explode(",", $recruit_info["rc_sex"]);
			$info["rc_nation"] = explode(",", $recruit_info["rc_nation"]);
			$info["rc_sage"] = $recruit_info["rc_sage"];
			$info["rc_eage"] = $recruit_info["rc_eage"];
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝2");
			if ($temp_info != null) {
				$info["rc_work"] = $temp_info["rc_work"];
				$info["rc_sex"] = $temp_info["rc_sex"];
				$info["rc_nation"] = $temp_info["rc_nation"];
				$info["rc_sage"] = $temp_info["rc_sage"];
				$info["rc_eage"] = $temp_info["rc_eage"];
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				if ($add_info != null) {
					$info["rc_work"] = explode(",", $add_info["ma_work"]);
					$info["rc_sex"] = explode(",", $add_info["ma_sex"]);
					$info["rc_nation"] = explode(",", $add_info["ma_nation"]);
					$info["rc_sage"] = $add_info["ma_sage"];
					$info["rc_eage"] = $add_info["ma_eage"];
				}
			}
		}

		# 데이터 리스트
		$data["rc_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["info"] = $info;
		$this->layout->shop('/compage/recruit_write2_new', $data);
	}
	*/

	/* 사용하지 않는 함수 - recruit_write3_1로 대체됨
	# 채용공고 등록3(검토용)
	function recruit_write3_new($rc_idx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("RECRUIT", "스텝2");
		if ($is_check == false) {
			$this->common->scriptReplace("/compage/index");
		}

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_work_type" => "",
			"rc_week_yn" => "",
			"rc_am_stime" => "",
			"rc_am_etime" => "",
			"rc_pm_stime" => "",
			"rc_pm_etime" => "",
			"rc_work_dt" => "",
			"rc_price_type" => "",
			"rc_day_price" => "",
			"rc_month_price" => "",
			"rc_week" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list_new", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_work_type"] = explode(",", $recruit_info["rc_work_type"]);
			$info["rc_week_yn"] = $recruit_info["rc_week_yn"];
			$info["rc_am_stime"] = $recruit_info["rc_am_stime"];
			$info["rc_am_etime"] = $recruit_info["rc_am_etime"];
			$info["rc_pm_stime"] = $recruit_info["rc_pm_stime"];
			$info["rc_pm_etime"] = $recruit_info["rc_pm_etime"];
			$info["rc_work_dt"] = $recruit_info["rc_work_dt"];
			$info["rc_price_type"] = $recruit_info["rc_price_type"];
			$info["rc_price_rep"] = $recruit_info["rc_price_rep"];
			$info["rc_price_pay"] = $recruit_info["rc_price_pay"];
			$info["rc_day_price"] = $recruit_info["rc_day_price"];
			$info["rc_week_price"] = $recruit_info["rc_week_price"];
			$info["rc_month_price"] = $recruit_info["rc_month_price"];
			$info["rc_week"] = explode(",", $recruit_info["rc_week"]);
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝3");
			if ($temp_info != null) {
				$info["rc_work_type"] = $temp_info["rc_work_type"];
				$info["rc_week_yn"] = $temp_info["rc_week_yn"];
				$info["rc_am_stime"] = $temp_info["rc_am_stime"];
				$info["rc_am_etime"] = $temp_info["rc_am_etime"];
				$info["rc_pm_stime"] = $temp_info["rc_pm_stime"];
				$info["rc_pm_etime"] = $temp_info["rc_pm_etime"];
				$info["rc_work_dt"] = $temp_info["rc_work_dt"];
				$info["rc_price_type"] = $temp_info["rc_price_type"];
				$info["rc_day_price"] = $temp_info["rc_day_price"];
				$info["rc_month_price"] = $temp_info["rc_month_price"];
				$info["rc_week"] = $temp_info["rc_week"];
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				if ($add_info != null) {
					$info["rc_work_type"] = explode(",", $add_info["ma_work_type"]);
					$info["rc_week_yn"] = $add_info["ma_week_yn"];
					$info["rc_am_stime"] = "";
					$info["rc_am_etime"] = "";
					$info["rc_pm_stime"] = "";
					$info["rc_pm_etime"] = "";
					$info["rc_work_dt"] = "";
					$info["rc_price_type"] = explode(",", $add_info["ma_price_type"]);
					$info["rc_day_price"] = "";
					$info["rc_month_price"] = "";
					$info["rc_week"] = explode(",", $add_info["ma_week"]);
				}
			}
		}

		# 데이터 리스트
		$data["info"] = $info;
		$data["rc_price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
		$data["rc_work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
		$data["rc_week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
		$this->layout->shop('/compage/recruit_write3_new', $data);
	}
	*/

	/* 사용하지 않는 함수 - recruit_write2_1로 대체됨
	# 채용공고 등록4(검토용)
	function recruit_write4_new($rc_idx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("RECRUIT", "스텝3");
		if ($is_check == false) {
			$this->common->scriptReplace("/compage/index");
		}

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_life_type" => ""
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list_new", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_life_type"] = explode(",", $recruit_info["rc_life_type"]);
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝4");
			if ($temp_info != null) {
				$info["rc_life_type"] = $temp_info["rc_life_type"];
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				if ($add_info != null) {
					$info["rc_life_type"] = explode(",", $add_info["ma_life_type"]);
				}
			}
		}

		$data["info"] = $info;
		$this->layout->shop('/compage/recruit_write4_new', $data);
	}
	*/

	/* 사용하지 않는 함수 - recruit_write3_1로 대체됨
	# 채용공고 등록5(검토용)
	function recruit_write5_new($rc_idx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("RECRUIT", "스텝4");
		if ($is_check == false) {
			$this->common->scriptReplace("/compage/index");
		}

		$mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_desc" => "",
			"rc_hash" => "",
			"rc_img1" => "",
			"rc_img2" => "",
			"rc_img3" => "",
			"rc_img4" => "",
			"rc_img5" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list_new", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_desc"] = $recruit_info["rc_desc"];
			$info["rc_hash"] = $recruit_info["rc_hash"];
			$info["rc_img1"] = $recruit_info["rc_img1"];
			$info["rc_img2"] = $recruit_info["rc_img2"];
			$info["rc_img3"] = $recruit_info["rc_img3"];
			$info["rc_img4"] = $recruit_info["rc_img4"];
			$info["rc_img5"] = $recruit_info["rc_img5"];
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝5");
			if ($temp_info != null) {
				$info["rc_desc"] = $temp_info["rc_desc"];
				$info["rc_hash"] = $temp_info["rc_hash"];
				$info["rc_img1"] = $temp_info["rc_img1"];
				$info["rc_img2"] = $temp_info["rc_img2"];
				$info["rc_img3"] = $temp_info["rc_img3"];
				$info["rc_img4"] = $temp_info["rc_img4"];
				$info["rc_img5"] = $temp_info["rc_img5"];
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				if ($add_info != null) {
					$info["rc_desc"] = $add_info["ma_desc"];
					$info["rc_hash"] = "";
					$info["rc_img1"] = "";
					$info["rc_img2"] = "";
					$info["rc_img3"] = "";
					$info["rc_img4"] = "";
					$info["rc_img5"] = "";
				}
			}
		}

		# 데이터 리스트
		$data["info"] = $info;
		$data["mem_info"] = $mem_info;
		$this->layout->shop('/compage/recruit_write5_new', $data);
	}
	*/

	# 채용공고 미리보기(검토용)
	function recruit_view_new($rc_idx = 0)
	{
		$data = [];
		$info = $this->recruit_model->getRecruitDetail($rc_idx, "");
		$addr = $this->member_model->getAddress(["ad_type" => "RECRUIT", "mm_idx" => $info["mm_idx"], "ad_key" => $rc_idx]);
		$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);

		if ($info == null) {
			go_location("/compage/recruit_list", "이력서 정보를 조회 하지 못하였습니다.");
		}
		if ($this->mm_idx != $info["mm_idx"] && $info != null) {
			go_location("/compage/recruit_list_new", "경고! 넌 누구냐?");
		}
		# 상세 이미지 추가 : 이미지 없으면 추가이미지 생성 : 랜덤값 가져와 처리
		if ($info["rc_img1"] == "" && $info["rc_img2"] == "" && $info["rc_img3"] == "" && $info["rc_img4"] == "" && $info["rc_img5"] == "") {
			$info["rc_img99"] = getRecruitImg($info, "detail");
		} else {
			$info["rc_img99"] = "";
		}

		$data["info"] = $info;
		$data["addr"] = $addr;
		$data["kakao_key"] = $kakao_key;
		$data["mem_info"] = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$this->layout->shop('/compage/recruit_view_new', $data);
	}

	/* 사용하지 않는 함수 - recruit_write1_1로 대체됨
	# 채용공고 등록1
	function recruit_write1($rc_idx = 0)
	{
		$data = [];

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_name" => "",
			"rc_sdate" => "",
			"rc_edate" => "",
			"addr" => "",
			"rc_idx" => $rc_idx,
			"rc_work" => "",
			"rc_sex" => "",
			"rc_nation" => "",
			"rc_sage" => "",
			"rc_eage" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			//go_location("/compage/recruit_list","채용공고가 존재하지 않습니다.");
			$this->common->scriptReplace("/compage/index");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_name"] = $recruit_info["rc_name"];
			$info["rc_sdate"] = get_date($recruit_info["rc_sdate"]);
			$info["rc_edate"] = get_date($recruit_info["rc_edate"]);
			$info["addr"] = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $recruit_info["rc_idx"], "ad_type" => "RECRUIT"]);
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝1");
			if ($temp_info != null) {
				$rc_sdate = "";
				$rc_edate = "";
				$arr_date = explode("~", $temp_info["rc_date"]);
				$rc_sdate = trim($arr_date[0]);
				$rc_edate = trim($arr_date[1]);

				$info["rc_name"] = $temp_info["rc_name"];
				$info["rc_sdate"] = $rc_sdate;
				$info["rc_edate"] = $rc_edate;
				$info["addr"] = [
					"ad_zipcode" => $temp_info["ad_zipcode"],
					"ad_jibun" => $temp_info["ad_jibun"],
					"ad_doro" => $temp_info["ad_doro"],
					"ad_addr" => $temp_info["ad_addr"],
					"ad_detail" => $temp_info["ad_detail"],
					"ad_gps_x" => $temp_info["ad_gps_x"],
					"ad_gps_y" => $temp_info["ad_gps_y"],
				];
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				$mem_addr = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $this->mm_idx, "ad_type" => "MEMBER"]);
				if ($add_info != null) {
					$info["rc_name"] = "";
					$info["rc_sdate"] = "";
					$info["rc_edate"] = "";
					$info["addr"] = $mem_addr;
				}
			}
		}

		# 데이터 리스트
		$data["info"] = $info;
		$this->layout->shop('/compage/recruit_write1', $data);
	}
	*/

	/* 사용하지 않는 함수 - recruit_write2_1로 대체됨
	# 채용공고 등록2
	function recruit_write2($rc_idx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("RECRUIT", "스텝1");
		if ($is_check == false) {
			//go_location("/compage/recruit_list","데이터 가 유효하지 않습니다. 채용공고등록을 다시 진행해주세요.");
			$this->common->scriptReplace("/compage/index");
		}

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_work" => "",
			"rc_sex" => "",
			"rc_nation" => "",
			"rc_sage" => "",
			"rc_eage" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_work"] = explode(",", $recruit_info["rc_work"]);
			$info["rc_sex"] = explode(",", $recruit_info["rc_sex"]);
			$info["rc_nation"] = explode(",", $recruit_info["rc_nation"]);
			$info["rc_sage"] = $recruit_info["rc_sage"];
			$info["rc_eage"] = $recruit_info["rc_eage"];
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝2");
			if ($temp_info != null) {
				$info["rc_work"] = $temp_info["rc_work"];
				$info["rc_sex"] = $temp_info["rc_sex"];
				$info["rc_nation"] = $temp_info["rc_nation"];
				$info["rc_sage"] = $temp_info["rc_sage"];
				$info["rc_eage"] = $temp_info["rc_eage"];
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				if ($add_info != null) {
					$info["rc_work"] = explode(",", $add_info["ma_work"]);
					$info["rc_sex"] = explode(",", $add_info["ma_sex"]);
					$info["rc_nation"] = explode(",", $add_info["ma_nation"]);
					$info["rc_sage"] = $add_info["ma_sage"];
					$info["rc_eage"] = $add_info["ma_eage"];
				}
			}
		}

		# 데이터 리스트
		$data["rc_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["info"] = $info;
		$this->layout->shop('/compage/recruit_write2', $data);
	}
	*/

	/* 사용하지 않는 함수 - recruit_write3_1로 대체됨
	# 채용공고 등록3
	function recruit_write3($rc_idx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("RECRUIT", "스텝2");
		if ($is_check == false) {
			$this->common->scriptReplace("/compage/index");
		}

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_work_type" => "",
			"rc_week_yn" => "",
			"rc_am_stime" => "",
			"rc_am_etime" => "",
			"rc_pm_stime" => "",
			"rc_pm_etime" => "",
			"rc_work_dt" => "",
			"rc_price_type" => "",
			"rc_day_price" => "",
			"rc_month_price" => "",
			"rc_week" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_work_type"] = explode(",", $recruit_info["rc_work_type"]);
			$info["rc_week_yn"] = $recruit_info["rc_week_yn"];
			$info["rc_am_stime"] = $recruit_info["rc_am_stime"];
			$info["rc_am_etime"] = $recruit_info["rc_am_etime"];
			$info["rc_pm_stime"] = $recruit_info["rc_pm_stime"];
			$info["rc_pm_etime"] = $recruit_info["rc_pm_etime"];
			$info["rc_work_dt"] = $recruit_info["rc_work_dt"];
			$info["rc_price_type"] = $recruit_info["rc_price_type"];
			$info["rc_price_rep"] = $recruit_info["rc_price_rep"];
			$info["rc_price_pay"] = $recruit_info["rc_price_pay"];
			$info["rc_day_price"] = $recruit_info["rc_day_price"];
			$info["rc_week_price"] = $recruit_info["rc_week_price"];
			$info["rc_month_price"] = $recruit_info["rc_month_price"];
			$info["rc_week"] = explode(",", $recruit_info["rc_week"]);
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝3");
			if ($temp_info != null) {
				$info["rc_work_type"] = $temp_info["rc_work_type"];
				$info["rc_week_yn"] = $temp_info["rc_week_yn"];
				$info["rc_am_stime"] = $temp_info["rc_am_stime"];
				$info["rc_am_etime"] = $temp_info["rc_am_etime"];
				$info["rc_pm_stime"] = $temp_info["rc_pm_stime"];
				$info["rc_pm_etime"] = $temp_info["rc_pm_etime"];
				$info["rc_work_dt"] = $temp_info["rc_work_dt"];
				$info["rc_price_type"] = $temp_info["rc_price_type"];
				$info["rc_day_price"] = $temp_info["rc_day_price"];
				$info["rc_month_price"] = $temp_info["rc_month_price"];
				$info["rc_week"] = $temp_info["rc_week"];
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				if ($add_info != null) {
					$info["rc_work_type"] = explode(",", $add_info["ma_work_type"]);
					$info["rc_week_yn"] = $add_info["ma_week_yn"];
					$info["rc_am_stime"] = "";
					$info["rc_am_etime"] = "";
					$info["rc_pm_stime"] = "";
					$info["rc_pm_etime"] = "";
					$info["rc_work_dt"] = "";
					$info["rc_price_type"] = explode(",", $add_info["ma_price_type"]);
					$info["rc_day_price"] = "";
					$info["rc_month_price"] = "";
					$info["rc_week"] = explode(",", $add_info["ma_week"]);
				}
			}
		}

		# 데이터 리스트
		$data["info"] = $info;
		$data["rc_price_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망급여"]);
		$data["rc_work_type_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무형태"]);
		$data["rc_week_list"]  = $this->site_model->getCodeList(["cd_grp" => "근무요일"]);
		$this->layout->shop('/compage/recruit_write3', $data);
	}
	*/

	/* 사용하지 않는 함수 - recruit_write2_1로 대체됨
	# 채용공고 등록4
	function recruit_write4($rc_idx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("RECRUIT", "스텝3");
		if ($is_check == false) {
			$this->common->scriptReplace("/compage/index");
		}

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_life_type" => ""
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_life_type"] = explode(",", $recruit_info["rc_life_type"]);
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝4");
			if ($temp_info != null) {
				$info["rc_life_type"] = $temp_info["rc_life_type"];
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				if ($add_info != null) {
					$info["rc_life_type"] = explode(",", $add_info["ma_life_type"]);
				}
			}
		}

		$data["info"] = $info;
		$this->layout->shop('/compage/recruit_write4', $data);
	}
	*/

	/* 사용하지 않는 함수 - recruit_write3_1로 대체됨
	# 채용공고 등록5
	function recruit_write5($rc_idx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("RECRUIT", "스텝4");
		if ($is_check == false) {
			$this->common->scriptReplace("/compage/index");
		}

		$mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_desc" => "",
			"rc_hash" => "",
			"rc_img1" => "",
			"rc_img2" => "",
			"rc_img3" => "",
			"rc_img4" => "",
			"rc_img5" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_desc"] = $recruit_info["rc_desc"];
			$info["rc_hash"] = $recruit_info["rc_hash"];
			$info["rc_img1"] = $recruit_info["rc_img1"];
			$info["rc_img2"] = $recruit_info["rc_img2"];
			$info["rc_img3"] = $recruit_info["rc_img3"];
			$info["rc_img4"] = $recruit_info["rc_img4"];
			$info["rc_img5"] = $recruit_info["rc_img5"];
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝5");
			if ($temp_info != null) {
				$info["rc_desc"] = $temp_info["rc_desc"];
				$info["rc_hash"] = $temp_info["rc_hash"];
				$info["rc_img1"] = $temp_info["rc_img1"];
				$info["rc_img2"] = $temp_info["rc_img2"];
				$info["rc_img3"] = $temp_info["rc_img3"];
				$info["rc_img4"] = $temp_info["rc_img4"];
				$info["rc_img5"] = $temp_info["rc_img5"];
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				if ($add_info != null) {
					$info["rc_desc"] = $add_info["ma_desc"];
					$info["rc_hash"] = "";
					$info["rc_img1"] = "";
					$info["rc_img2"] = "";
					$info["rc_img3"] = "";
					$info["rc_img4"] = "";
					$info["rc_img5"] = "";
				}
			}
		}

		# 데이터 리스트
		$data["info"] = $info;
		$data["mem_info"] = $mem_info;
		$this->layout->shop('/compage/recruit_write5', $data);
	}
	*/

	# 채용공고 등록1_1
	function recruit_write1_1($rc_idx = 0)
	{
		$data = [];

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_name" => "",
			"rc_sdate" => "",
			"rc_edate" => "",
			"addr" => "",
			"rc_work" => "",
			"rc_work_type" => "",
			"rc_work_negotiation" => "Y",
			"rc_am_stime" => "",
			"rc_am_etime" => "",
			"rc_pm_stime" => "",
			"rc_pm_etime" => "",
			"rc_price_type_main" => "",
			"rc_price_amount" => "",
			"rc_price_possible" => "",
			"rc_price_daily" => "",
			"rc_price_weekly" => "",
			"rc_price_monthly" => "",
			"rc_desc" => "",
			"rc_hash" => "",
			"rc_life_type" => [],
			"rc_day" => [],
			"rc_day_all" => "",
			"rc_sex" => [],
			"rc_sex_all" => "",
			"rc_nation" => [],
			"rc_nation_all" => "",
			"rc_sage" => "",
			"rc_eage" => "",
			"rc_age_all" => "",
			"rc_img1" => "",
			"rc_img2" => "",
			"rc_img3" => "",
			"rc_img4" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			$this->common->scriptReplace("/compage/index");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_name"] = $recruit_info["rc_name"];
			$info["rc_sdate"] = get_date($recruit_info["rc_sdate"]);
			$info["rc_edate"] = get_date($recruit_info["rc_edate"]);
			$info["addr"] = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $recruit_info["rc_idx"], "ad_type" => "RECRUIT"]);
			$info["rc_work"] = explode(",", $recruit_info["rc_work"]);
			$info["rc_work_type"] = explode(",", $recruit_info["rc_work_type"]);
			$info["rc_work_negotiation"] = isset($recruit_info["rc_work_negotiation"]) ? $recruit_info["rc_work_negotiation"] : "";
			$info["rc_am_stime"] = isset($recruit_info["rc_am_stime"]) ? $recruit_info["rc_am_stime"] : "";
			$info["rc_am_etime"] = isset($recruit_info["rc_am_etime"]) ? $recruit_info["rc_am_etime"] : "";
			$info["rc_pm_stime"] = isset($recruit_info["rc_pm_stime"]) ? $recruit_info["rc_pm_stime"] : "";
			$info["rc_pm_etime"] = isset($recruit_info["rc_pm_etime"]) ? $recruit_info["rc_pm_etime"] : "";

			// 급여형태 정보 변환: DB 필드(rc_price_rep, rc_price_pay, rc_price_type)를 폼 필드(rc_price_type_main, rc_price_amount, rc_price_possible)로 변환
			// 급여협의 처리: rc_price_type이 "급여협의"이거나 rc_price_rep이 "협의"/"급여협의"인 경우
			$is_negotiation = false;
			if (isset($recruit_info["rc_price_type"]) && $recruit_info["rc_price_type"] == "급여협의") {
				$is_negotiation = true;
			} else if (isset($recruit_info["rc_price_rep"]) && ($recruit_info["rc_price_rep"] == "협의" || $recruit_info["rc_price_rep"] == "급여협의")) {
				$is_negotiation = true;
			}

			if ($is_negotiation) {
				// 급여협의인 경우
				$info["rc_price_type_main"] = "급여협의";
				$info["rc_price_amount"] = "";
				$info["rc_price_possible"] = "";
			} else {
				// 급여협의가 아닌 경우
				// rc_price_rep가 있으면 우선 사용, 없으면 rc_price_type에서 첫 번째 값 추출
				if (isset($recruit_info["rc_price_rep"]) && $recruit_info["rc_price_rep"] != "") {
					$info["rc_price_type_main"] = $recruit_info["rc_price_rep"];
				} else if (isset($recruit_info["rc_price_type"]) && $recruit_info["rc_price_type"] != "") {
					// rc_price_type에서 첫 번째 값 추출 (예: "시급,일급" → "시급")
					$price_type_parts = explode(",", $recruit_info["rc_price_type"]);
					$info["rc_price_type_main"] = trim($price_type_parts[0]);
				} else {
					$info["rc_price_type_main"] = "";
				}

				// 급여 금액: rc_price_pay 사용
				$info["rc_price_amount"] = isset($recruit_info["rc_price_pay"]) && $recruit_info["rc_price_pay"] > 0 ? $recruit_info["rc_price_pay"] : "";

				// rc_price_possible: rc_price_type에서 서브 타입 파싱 (예: "시급,일급" → "일급가능")
				$info["rc_price_possible"] = "";
				if (isset($recruit_info["rc_price_type"]) && $recruit_info["rc_price_type"] != "" && $recruit_info["rc_price_type"] != "급여협의") {
					$price_type_parts = explode(",", $recruit_info["rc_price_type"]);
					if (count($price_type_parts) > 1) {
						// 서브 타입이 있는 경우 (예: "시급,일급")
						$sub_type = trim($price_type_parts[1]);
						$info["rc_price_possible"] = $sub_type . "가능"; // "일급" → "일급가능"
					}
				}
			}

			$info["rc_price_daily"] = isset($recruit_info["rc_price_daily"]) ? $recruit_info["rc_price_daily"] : "";
			$info["rc_price_weekly"] = isset($recruit_info["rc_price_weekly"]) ? $recruit_info["rc_price_weekly"] : "";
			$info["rc_price_monthly"] = isset($recruit_info["rc_price_monthly"]) ? $recruit_info["rc_price_monthly"] : "";

			// 추가 정보 로드
			$info["rc_desc"] = isset($recruit_info["rc_desc"]) ? $recruit_info["rc_desc"] : "";
			$info["rc_hash"] = isset($recruit_info["rc_hash"]) ? $recruit_info["rc_hash"] : "";
			$info["rc_life_type"] = isset($recruit_info["rc_life_type"]) && $recruit_info["rc_life_type"] != "" ? explode(",", $recruit_info["rc_life_type"]) : [];
			$info["rc_day"] = isset($recruit_info["rc_day"]) && $recruit_info["rc_day"] != "" ? explode(",", $recruit_info["rc_day"]) : [];
			$info["rc_day_all"] = isset($recruit_info["rc_day_all"]) ? $recruit_info["rc_day_all"] : "";
			$info["rc_sex"] = isset($recruit_info["rc_sex"]) && $recruit_info["rc_sex"] != "" ? explode(",", $recruit_info["rc_sex"]) : [];
			$info["rc_sex_all"] = isset($recruit_info["rc_sex_all"]) ? $recruit_info["rc_sex_all"] : "";
			$info["rc_nation"] = isset($recruit_info["rc_nation"]) && $recruit_info["rc_nation"] != "" ? explode(",", $recruit_info["rc_nation"]) : [];
			$info["rc_nation_all"] = isset($recruit_info["rc_nation_all"]) ? $recruit_info["rc_nation_all"] : "";
			$info["rc_sage"] = isset($recruit_info["rc_sage"]) ? $recruit_info["rc_sage"] : "";
			$info["rc_eage"] = isset($recruit_info["rc_eage"]) ? $recruit_info["rc_eage"] : "";
			$info["rc_age_all"] = isset($recruit_info["rc_age_all"]) ? $recruit_info["rc_age_all"] : "";
			$info["rc_img1"] = isset($recruit_info["rc_img1"]) ? $recruit_info["rc_img1"] : "";
			$info["rc_img2"] = isset($recruit_info["rc_img2"]) ? $recruit_info["rc_img2"] : "";
			$info["rc_img3"] = isset($recruit_info["rc_img3"]) ? $recruit_info["rc_img3"] : "";
			$info["rc_img4"] = isset($recruit_info["rc_img4"]) ? $recruit_info["rc_img4"] : "";
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝1");
			if ($temp_info != null) {
				$rc_sdate = "";
				$rc_edate = "";
				if (isset($temp_info["rc_sdate"]) && isset($temp_info["rc_edate"])) {
					$rc_sdate = get_date($temp_info["rc_sdate"]);
					$rc_edate = get_date($temp_info["rc_edate"]);
				} else if (isset($temp_info["rc_date"])) {
					$arr_date = explode("~", $temp_info["rc_date"]);
					$rc_sdate = trim($arr_date[0]);
					$rc_edate = isset($arr_date[1]) ? trim($arr_date[1]) : "";
				}

				$info["rc_name"] = isset($temp_info["rc_name"]) ? $temp_info["rc_name"] : "";
				$info["rc_sdate"] = $rc_sdate;
				$info["rc_edate"] = $rc_edate;
				$info["addr"] = [
					"ad_zipcode" => isset($temp_info["ad_zipcode"]) ? $temp_info["ad_zipcode"] : "",
					"ad_jibun" => isset($temp_info["ad_jibun"]) ? $temp_info["ad_jibun"] : "",
					"ad_doro" => isset($temp_info["ad_doro"]) ? $temp_info["ad_doro"] : "",
					"ad_addr" => isset($temp_info["ad_addr"]) ? $temp_info["ad_addr"] : "",
					"ad_detail" => isset($temp_info["ad_detail"]) ? $temp_info["ad_detail"] : "",
					"ad_gps_x" => isset($temp_info["ad_gps_x"]) ? $temp_info["ad_gps_x"] : "",
					"ad_gps_y" => isset($temp_info["ad_gps_y"]) ? $temp_info["ad_gps_y"] : "",
				];
				$info["rc_work"] = isset($temp_info["rc_work"]) && is_array($temp_info["rc_work"]) ? $temp_info["rc_work"] : (isset($temp_info["rc_work"]) ? explode(",", $temp_info["rc_work"]) : []);
				$info["rc_work_type"] = isset($temp_info["rc_work_type"]) && is_array($temp_info["rc_work_type"]) ? $temp_info["rc_work_type"] : (isset($temp_info["rc_work_type"]) ? explode(",", $temp_info["rc_work_type"]) : []);
				$info["rc_work_negotiation"] = isset($temp_info["rc_work_negotiation"]) ? $temp_info["rc_work_negotiation"] : "";
				$info["rc_price_type_main"] = isset($temp_info["rc_price_type_main"]) ? $temp_info["rc_price_type_main"] : "";
				$info["rc_price_amount"] = isset($temp_info["rc_price_amount"]) ? $temp_info["rc_price_amount"] : "";
				$info["rc_price_possible"] = isset($temp_info["rc_price_possible"]) ? $temp_info["rc_price_possible"] : "";
				$info["rc_price_daily"] = isset($temp_info["rc_price_daily"]) ? $temp_info["rc_price_daily"] : "";
				$info["rc_price_weekly"] = isset($temp_info["rc_price_weekly"]) ? $temp_info["rc_price_weekly"] : "";
				$info["rc_price_monthly"] = isset($temp_info["rc_price_monthly"]) ? $temp_info["rc_price_monthly"] : "";
				$info["rc_desc"] = isset($temp_info["rc_desc"]) ? $temp_info["rc_desc"] : "";
				$info["rc_hash"] = isset($temp_info["rc_hash"]) ? $temp_info["rc_hash"] : "";
				$info["rc_life_type"] = isset($temp_info["rc_life_type"]) && is_array($temp_info["rc_life_type"]) ? $temp_info["rc_life_type"] : (isset($temp_info["rc_life_type"]) ? explode(",", $temp_info["rc_life_type"]) : []);
				$info["rc_day"] = isset($temp_info["rc_day"]) && is_array($temp_info["rc_day"]) ? $temp_info["rc_day"] : (isset($temp_info["rc_day"]) ? explode(",", $temp_info["rc_day"]) : []);
				$info["rc_day_all"] = isset($temp_info["rc_day_all"]) ? $temp_info["rc_day_all"] : "";
				$info["rc_sex"] = isset($temp_info["rc_sex"]) && is_array($temp_info["rc_sex"]) ? $temp_info["rc_sex"] : (isset($temp_info["rc_sex"]) ? explode(",", $temp_info["rc_sex"]) : []);
				$info["rc_sex_all"] = isset($temp_info["rc_sex_all"]) ? $temp_info["rc_sex_all"] : "";
				$info["rc_nation"] = isset($temp_info["rc_nation"]) && is_array($temp_info["rc_nation"]) ? $temp_info["rc_nation"] : (isset($temp_info["rc_nation"]) ? explode(",", $temp_info["rc_nation"]) : []);
				$info["rc_nation_all"] = isset($temp_info["rc_nation_all"]) ? $temp_info["rc_nation_all"] : "";
				$info["rc_sage"] = isset($temp_info["rc_sage"]) ? $temp_info["rc_sage"] : "";
				$info["rc_eage"] = isset($temp_info["rc_eage"]) ? $temp_info["rc_eage"] : "";
				$info["rc_age_all"] = isset($temp_info["rc_age_all"]) ? $temp_info["rc_age_all"] : "";
			} else {
				$add_info = $this->member_model->getMemberInfo($this->mm_idx);
				$mem_addr = $this->member_model->getAddress(["mm_idx" => $this->mm_idx, "ad_key" => $this->mm_idx, "ad_type" => "MEMBER"]);
				if ($add_info != null) {
					$info["rc_name"] = "";
					$info["rc_sdate"] = "";
					$info["rc_edate"] = "";
					$info["addr"] = $mem_addr;
				}
			}
		}

		# 데이터 리스트
		$data["rc_wrok_list"]  = $this->site_model->getCodeList(["cd_grp" => "희망업무"]);
		$data["info"] = $info;
		$data["mem_info"] = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$this->layout->shop('/compage/recruit_write1_1', $data);
	}

	# 채용공고 등록2_1
	function recruit_write2_1($rc_idx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("RECRUIT", "스텝1");
		if ($is_check == false) {
			$this->common->scriptReplace("/compage/index");
		}

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_life_type" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_life_type"] = explode(",", $recruit_info["rc_life_type"]);
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝2");
			if ($temp_info != null) {
				$info["rc_life_type"] = isset($temp_info["rc_life_type"]) && is_array($temp_info["rc_life_type"]) ? $temp_info["rc_life_type"] : (isset($temp_info["rc_life_type"]) ? explode(",", $temp_info["rc_life_type"]) : []);
			}
		}

		# 데이터 리스트
		$data["info"] = $info;
		$this->layout->shop('/compage/recruit_write2_1', $data);
	}

	# 채용공고 등록3_1
	function recruit_write3_1($rc_idx = 0)
	{
		$data = [];

		# 데이터 검증및 뒤로가기 처리
		$is_check = $this->site_model->checkSiteLog("RECRUIT", "스텝2");
		if ($is_check == false) {
			$this->common->scriptReplace("/compage/index");
		}

		# 데이터 조회
		$info = [
			"rc_idx" => $rc_idx,
			"rc_desc" => "",
			"rc_hash" => "",
			"rc_sdate" => "",
			"rc_edate" => "",
			"rc_sex" => "",
			"rc_sex_all" => "",
			"rc_nation" => "",
			"rc_nation_all" => "",
			"rc_sage" => "",
			"rc_eage" => "",
			"rc_age_all" => "",
			"rc_img1" => "",
			"rc_img2" => "",
			"rc_img3" => "",
			"rc_img4" => "",
		];

		$recruit_info = $this->recruit_model->getRecruit(["rc_idx" => $rc_idx]);
		if ($rc_idx != 0 && $recruit_info == null) {
			go_location("/compage/recruit_list", "채용공고가 존재하지 않습니다.");
		}
		if ($this->mm_idx != $recruit_info["mm_idx"] && $recruit_info != null) {
			go_location("/compage/recruit_list", "경고! 넌 누구냐?");
		}

		if ($recruit_info != null) {
			$info["rc_desc"] = $recruit_info["rc_desc"];
			$info["rc_hash"] = $recruit_info["rc_hash"];
			$info["rc_sdate"] = get_date($recruit_info["rc_sdate"]);
			$info["rc_edate"] = get_date($recruit_info["rc_edate"]);
			$info["rc_sex"] = explode(",", $recruit_info["rc_sex"]);
			$info["rc_sex_all"] = isset($recruit_info["rc_sex_all"]) ? $recruit_info["rc_sex_all"] : "";
			$info["rc_nation"] = explode(",", $recruit_info["rc_nation"]);
			$info["rc_nation_all"] = isset($recruit_info["rc_nation_all"]) ? $recruit_info["rc_nation_all"] : "";
			$info["rc_sage"] = $recruit_info["rc_sage"];
			$info["rc_eage"] = $recruit_info["rc_eage"];
			$info["rc_age_all"] = isset($recruit_info["rc_age_all"]) ? $recruit_info["rc_age_all"] : "";
			$info["rc_img1"] = $recruit_info["rc_img1"];
			$info["rc_img2"] = $recruit_info["rc_img2"];
			$info["rc_img3"] = $recruit_info["rc_img3"];
			$info["rc_img4"] = $recruit_info["rc_img4"];
		} else {
			$temp_info  = $this->site_model->getStepTempDataMember($this->mm_idx, "RECRUIT", "스텝3");
			if ($temp_info != null) {
				$info["rc_desc"] = isset($temp_info["rc_desc"]) ? $temp_info["rc_desc"] : "";
				$info["rc_hash"] = isset($temp_info["rc_hash"]) ? $temp_info["rc_hash"] : "";
				if (isset($temp_info["rc_sdate"]) && isset($temp_info["rc_edate"])) {
					$info["rc_sdate"] = get_date($temp_info["rc_sdate"]);
					$info["rc_edate"] = get_date($temp_info["rc_edate"]);
				} else {
					$info["rc_sdate"] = "";
					$info["rc_edate"] = "";
				}
				$info["rc_sex"] = isset($temp_info["rc_sex"]) && is_array($temp_info["rc_sex"]) ? $temp_info["rc_sex"] : (isset($temp_info["rc_sex"]) ? explode(",", $temp_info["rc_sex"]) : []);
				$info["rc_sex_all"] = isset($temp_info["rc_sex_all"]) ? $temp_info["rc_sex_all"] : "";
				$info["rc_nation"] = isset($temp_info["rc_nation"]) && is_array($temp_info["rc_nation"]) ? $temp_info["rc_nation"] : (isset($temp_info["rc_nation"]) ? explode(",", $temp_info["rc_nation"]) : []);
				$info["rc_nation_all"] = isset($temp_info["rc_nation_all"]) ? $temp_info["rc_nation_all"] : "";
				$info["rc_sage"] = isset($temp_info["rc_sage"]) ? $temp_info["rc_sage"] : "";
				$info["rc_eage"] = isset($temp_info["rc_eage"]) ? $temp_info["rc_eage"] : "";
				$info["rc_age_all"] = isset($temp_info["rc_age_all"]) ? $temp_info["rc_age_all"] : "";
				$info["rc_img1"] = isset($temp_info["rc_img1"]) ? $temp_info["rc_img1"] : "";
				$info["rc_img2"] = isset($temp_info["rc_img2"]) ? $temp_info["rc_img2"] : "";
				$info["rc_img3"] = isset($temp_info["rc_img3"]) ? $temp_info["rc_img3"] : "";
				$info["rc_img4"] = isset($temp_info["rc_img4"]) ? $temp_info["rc_img4"] : "";
			}
		}

		# 데이터 리스트
		$data["info"] = $info;
		$this->layout->shop('/compage/recruit_write3_1', $data);
	}

	# 채용공고 미리보기
	function recruit_view($rc_idx = 0)
	{
		$data = [];
		$info = $this->recruit_model->getRecruitDetail($rc_idx, "");
		$addr = $this->member_model->getAddress(["ad_type" => "RECRUIT", "mm_idx" => $info["mm_idx"], "ad_key" => $rc_idx]);
		$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);

		if ($info == null) {
			go_location("/compage/recruit_list", "이력서 정보를 조회 하지 못하였습니다.");
		}
		if ($this->mm_idx != $info["mm_idx"] && $info != null) {
			go_location("/compage/recruit_list", "경고! 넌 누구냐?");
		}
		# 상세 이미지 추가 : 이미지 없으면 추가이미지 생성 : 랜덤값 가져와 처리
		if ($info["rc_img1"] == "" && $info["rc_img2"] == "" && $info["rc_img3"] == "" && $info["rc_img4"] == "" && $info["rc_img5"] == "") {
			$info["rc_img99"] = getRecruitImg($info, "detail");
		} else {
			$info["rc_img99"] = "";
		}

		$data["info"] = $info;
		$data["addr"] = $addr;
		$data["kakao_key"] = $kakao_key;
		$data["mem_info"] = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$this->layout->shop('/compage/recruit_view', $data);
	}

	# 관심인재
	function bookmark_person1_new()
	{
		$data = [];

		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
		];
		$result = $this->member_model->getBookMarkResumeList($params);

		$data["list"] = $result["list"];
		$data["count"] = $result["count"];
		$this->layout->shop('/compage/bookmark_person1_new', $data);
	}

	# 관심인재
	function bookmark_person1()
	{
		$data = [];

		//데이터 설정
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
		];
		$result = $this->member_model->getBookMarkResumeList($params);

		$data["list"] = $result["list"];
		$data["count"] = $result["count"];
		$this->layout->shop('/compage/bookmark_person1', $data);
	}

	# 공고지원 인재
	function bookmark_person2($gbn = "")
	{
		$data = [];
		//if($gbn==null) $gbn="";
		//데이터 설정
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
			"gbn" => $gbn
		];
		$result = $this->member_model->getBookMarkRequestResumeList($params);

		$data["list"] = $result["list"];
		$data["count"] = $result["count"];
		$this->layout->shop('/compage/bookmark_person2', $data);
	}

	# 제안보낸 인재
	function bookmark_person3($gbn = "")
	{
		$data = [];
		//if($gbn==null) $gbn="";
		//데이터 설정
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
			"gbn" => $gbn
		];
		//$result = $this->member_model->getBookMarkRequestResumeList($params);
		$result = $this->member_model->getRequestRecruitList2($params);

		$data["list"] = $result["list"];
		$data["count"] = $result["count"];
		$this->layout->shop('/compage/bookmark_person3', $data);
	}

	# 면점자 리스트
	function interv_list()
	{
		$data = [];

		//데이터 설정
		$params = [
			"mm_idx" => $this->mm_idx,
		];
		$result = $this->common_model->getInterviewList($params);

		$data["result_list"] = $result;
		$this->layout->shop('/compage/interv_list', $data);
	}

	# 면점자 상세
	function interv_detail($iv_idx = 0)
	{
		$data = [];
		$info = $this->common_model->getInterview(["iv_idx" => $iv_idx]);

		if ($info == null) {
			go_back("잘못된 접근입니다.");
		}

		$data["resume"] = $this->member_model->getResumeDetail($info["ru_idx"], "");
		$data["info"] = $info;
		$this->layout->shop('/compage/interv_detail', $data);
	}

	# 아웃소싱 멤버관리
	function out_member()
	{
		$data = [];

		$mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$data["mem_info"] = $mem_info;
		$data["result_list"] = $this->member_model->getOutSourcingList($mem_info["mm_comp_num"]);
		$this->layout->shop('/compage/out_member', $data);
	}

	# 아웃소싱 광고관리
	function out_promotion()
	{
		$data = [];

		$mem_info = $this->member_model->getMember(["mm_idx" => $this->mm_idx]);
		$data["mem_info"] = $mem_info;
		$data["result_list"] = $this->member_model->getOutSourcingList($mem_info["mm_comp_num"]);
		$this->layout->shop('/compage/out_promotion', $data);
	}

	# 쿠폰 리스트
	function coupon_list()
	{
		$gbn = $this->input->get('gbn');
		$data = [];

		//데이터 설정
		$params = [
			"pagesize" => 100,
			"page" => 1,
			"cp_idx" => "",
			"search_type" => "",
			"search_value" => "",
			"mm_idx" => $this->mm_idx,
			"cl_use_yn" => $gbn,
		];
		$result = $this->site_model->getCouponListList($params);

		$data["coupon_list"] = $result["list"];
		$data["coupon_count"] = $result["count"];
		$this->layout->shop('/compage/coupon_list', $data);
	}

	# 즐겨찾기 관리
	function bookmark_list()
	{
		$data = [];

		//데이터 설정
		$params = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
		];
		$result = $this->member_model->getBookMarkTradeList($params);

		$data["list"] = $result["list"];
		$data["count"] = $result["count"];
		$this->layout->shop('/compage/bookmark_list', $data);
	}

	function trade_list()
	{
		$data = [];

		//데이터 설정
		$params = [
			"pagesize" => 100,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
		];
		$result = $this->site_model->getTradeSearchListV2($params);

		$data["result_list"] = $result["list"];
		$data["result_count"] = $result["count"];
		$this->layout->shop('/compage/trade_list', $data);
	}

	function trade_edit($td_idx = 0)
	{
		$data = [];

		# 로그인확인후 이동처리
		if ($this->user->isLogin() == false) {
			go_back("로그인후 이용 가능합니다.");
		}

		$mm_idx = $this->user->getUserIdx();
		$trade_info = $this->site_model->getTrade(["td_idx" => $td_idx]);

		if ($td_idx != 0 && $trade_info == null) {
			go_location("/trade", "잘못된 접근입니다.");
		}

		$data["info"] = $trade_info;
		$data["addr_list"]  = $this->member_model->getAddressList(["ad_type" => "TRADE", "mm_idx" => $mm_idx, "ad_key" => $td_idx]);
		$data["td_category_list"]  = $this->site_model->getCodeList(["cd_grp" => "중고카테고리"]);
		$data["td_hash_list"]  = $this->site_model->getCodeList(["cd_grp" => "중고해시태그"]);
		$this->layout->shop('/compage/trade_edit', $data);
	}

	# 문의하기 리스트
	function qna_list()
	{
		$page = input_get("page", 1);
		$tb_id = "report";
		$sort = input_get("sort", "reg_dt");
		$mm_id_yn = input_get("mm_id_yn", "N");

		$data = [];

		$params = [
			"pagesize" => 20,
			"page" => $page,
			"search_type" => "",
			"search_value" => "",
			"bd_aws_yn" => "",
			"tb_id" => $tb_id,
			"sort" => $sort,
			"mm_id" => $this->mm_id,
		];

		$result = $this->board_model->getBoardList($params);

		//리턴 설정
		$data = [
			"sort" => $sort,
			"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
			"setting" => $setting,
			"result_list" => $result["list"],
			"result_count" => $result["count"],
			"pagging" => $result["pagenation"]
		];

		$this->layout->shop('/compage/qna_list', $data);
	}

	# 문의하기 상세
	function qna_view($bd_idx)
	{
		$data = [];
		$date_status = input_get("date_status", "ing");

		if ($bd_idx == null) {
			$this->common->goBack("잘못된 접근입니다.");
		}

		$data["info"] = $this->board_model->getBoard($bd_idx);
		$this->board_model->updateBoard(["bd_hit" => $data["info"]["bd_hit"] + 1], ["bd_idx" => $bd_idx]);
		$this->layout->shop('/compage/qna_view', $data);
	}

	# 문의하기 등록
	function qna_write($bd_idx = 0)
	{
		$data = [];

		$data["info"] = $this->board_model->getBoard($bd_idx);
		$this->layout->shop('/compage/qna_write', $data);
	}

	# 문의하기 리스트
	function my_report_list()
	{
		$page = input_get("page", 1);

		$data = [];
		$params = [
			"pagesize" => 20,
			"page" => $page,
			"mm_id" => ($mm_id_yn == "Y" ? $mm_id : "")
		];

		$result = $this->board_model->getMyReportList($params);

		//리턴 설정
		$data = [
			"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
			"setting" => $setting,
			"result_list" => $result["list"],
			"result_count" => $result["count"],
		];

		$this->layout->shop('/compage/my_report_list', $data);
	}

	# 문의하기 상세
	function my_report_view($br_idx)
	{
		$data = [];
		if ($br_idx == null) {
			$this->common->goBack("잘못된 접근입니다.");
		}

		$data["info"] = $this->board_model->getBoardReport($br_idx);
		$this->layout->shop('/compage/my_report_view', $data);
	}

	# 지원자 리스트
	function applicant_list()
	{
		$gbn = $this->input->get('gbn');
		$mq_status = $this->input->get('mq_status');
		$rcidx = $this->input->get('rcidx');
		$data = [];
		$params1 = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
			"gbn" => "all",
			"mq_status" => "",
			"rc_idx" => $rcidx
		];
		$result1 = $this->member_model->getBookMarkRequestResumeList($params1);

		$params2 = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
			"gbn" => "yet",
			"mq_status" => "",
			"rc_idx" => $rcidx
		];
		$result2 = $this->member_model->getBookMarkRequestResumeList($params2);
		$params3 = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
			"gbn" => "",
			"mq_status" => "pass",
			"rc_idx" => $rcidx
		];
		$result3 = $this->member_model->getBookMarkRequestResumeList($params3);
		$params4 = [
			"pagesize" => 200,
			"page" => 1,
			"mm_idx" => $this->mm_idx,
			"gbn" => "",
			"mq_status" => "fail",
			"rc_idx" => $rcidx
		];
		$result4 = $this->member_model->getBookMarkRequestResumeList($params4);

		if ($mq_status == "pass") $data["applicant_list"] = $result3["list"];
		else if ($mq_status == "fail") $data["applicant_list"] = $result4["list"];
		else if ($gbn == "yet") $data["applicant_list"] = $result2["list"];
		else $data["applicant_list"] = $result1["list"];

		$data["applicant_allcnt"] = count($result1["list"]);
		$data["applicant_yetcnt"] = count($result2["list"]);
		$data["applicant_passcnt"] = count($result3["list"]);
		$data["applicant_failcnt"] = count($result4["list"]);
		$this->layout->shop('/compage/applicant_list', $data);
	}
} //class
