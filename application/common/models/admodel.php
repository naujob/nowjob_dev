<?php
class adModel extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}

	# 광고 배너 클릭 로그 저장
	function insertAdClickLog($data)
	{
		return $this->db_insert("st_ad_click_log", $data);
	}

	# PC배너광고 클릭수 목록
	function getAdClickLogList($params)
	{
		$cols = array(
			"DATE(reg_dt) as click_date",
			"page_type",
			"mm_type",
			"COUNT(*) as cnt",
			"MAX(reg_dt) as last_dt",
		);
		$body  = "st_ad_click_log";
		$where = array();

		if ($params["page_type"] != "" && $params["page_type"] != "ALL") {
			$where[] = " and page_type = '" . $this->inject($params["page_type"]) . "' ";
		}
		if ($params["mm_type"] != "" && $params["mm_type"] != "ALL") {
			$where[] = " and mm_type = '" . $this->inject($params["mm_type"]) . "' ";
		}
		if ($params["sdate"] != "") {
			$where[] = " and reg_dt >= '" . $this->inject($params["sdate"]) . " 00:00:00' ";
		}
		if ($params["edate"] != "") {
			$where[] = " and reg_dt <= '" . $this->inject($params["edate"]) . " 23:59:59' ";
		}

		$group_by = "GROUP BY DATE(reg_dt), page_type, mm_type";
		$order_by = "reg_dt DESC";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# PC배너광고 클릭로그 삭제
	function deleteAdClickLog($where)
	{
		return $this->db_delete("st_ad_click_log", $where);
	}
}
?>
