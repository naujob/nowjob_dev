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
}
?>
