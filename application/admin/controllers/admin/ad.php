<?php
class Ad extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('admodel', 'ad_model');
	}

	// PC배너광고 클릭수 관리
	function pc_banner_click_list() {
		$page      = input_get("page", 1);
		$page_type = input_get("page_type", "ALL");
		$mm_type   = input_get("mm_type", "ALL");
		$sdate     = input_get("sdate", date("Y-m-01"));
		$edate     = input_get("edate", date("Y-m-d"));

		$params = array(
			"pagesize"  => 20,
			"page"      => $page,
			"page_type" => $page_type,
			"mm_type"   => $mm_type,
			"sdate"     => $sdate,
			"edate"     => $edate,
		);

		$result = $this->ad_model->getAdClickLogList($params);

		$data = array(
			"search" => array(
				"page_type" => $page_type,
				"mm_type"   => $mm_type,
				"sdate"     => $sdate,
				"edate"     => $edate,
			),
			"start"        => ($result["count"] - (($page - 1) * $params["pagesize"])),
			"result_list"  => $result["list"],
			"result_count" => $result["count"],
			"pagenation"   => $result["pagenation"],
		);

		$this->layout->admin('admin/ad/pc_banner_click_list', $data);
	}

	// PC배너광고 클릭로그 삭제
	function ad_click_log_delete_proc() {
		$click_date = input_post("click_date", "");
		$page_type  = input_post("page_type", "");
		$mm_type    = input_post("mm_type", "");

		if ($click_date == "" || $page_type == "" || $mm_type == "") {
			json_return("99", "잘못된 요청입니다.");
			return;
		}

		// 날짜, 페이지유형, 회원유형이 일치하는 레코드 삭제
		$where = array(
			"DATE(reg_dt)" => $click_date,
			"page_type"    => $page_type,
			"mm_type"      => $mm_type,
		);

		$result = $this->ad_model->deleteAdClickLog($where);

		if ($result) {
			json_return("00", "삭제되었습니다.");
		} else {
			json_return("99", "삭제 중 오류가 발생했습니다.");
		}
	}
}
?>
