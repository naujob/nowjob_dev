<?php
class Recruit_proc extends CI_Controller
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
		$this->load->model('recruitmodel', 'recruit_model');

		$this->mm_id = $this->user->getUserId();
		$this->mm_idx = $this->user->getUserIdx();
	}

	function chage_addr()
	{
		$mem_addr = $this->member_model->getAddress(["mm_idx" => $this->mm_idx]);
	}

	# 
	function search_list()
	{
		$result = $this->member_model->getRecruitSearchParamsList();
		json_return("00", "조회성공", $result);
	}

	# 조건 저장
	function search_save_params()
	{
		$page = input_post("page", 1);
		$text = input_post("text", "");
		$skm = input_post("skm", "");
		$ekm = input_post("ekm", "");
		$category = input_post("category", "");
		$work_type = input_post("work_type", "");
		$week = input_post("week", "");
		$price_type = input_post("price_type", "");
		$sex = input_post("sex", "");
		$sage = input_post("sage", "");
		$eage = input_post("eage", "");


		#로그 생성
		$search_txt = [
			"text" => $text,
			"skm" => $skm,
			"ekm" => $ekm,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
		];

		if ($this->user->isLogin()) {
			$this->site_model->deleteSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "SEARCH_RECRUIT",
					"sl_step" => "검색"
				]
			);

			$this->site_model->deleteSiteLog(
				[
					"mm_idx" => $this->mm_idx,
					"sl_type" => "SEARCH_RECRUIT",
					"sl_step" => "검색"
				]
			);
		} else {
			$this->site_model->deleteSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "SEARCH_RECRUIT",
					"sl_step" => "검색"
				]
			);
		}

		$ret = $this->site_model->setSiteLog(
			[
				"session_id" => session_id(),
				"mm_idx" => $this->mm_idx,
				"sl_type" => "SEARCH_RECRUIT",
				"sl_step" => "검색",
				"sl_txt" => json_encode($search_txt, JSON_UNESCAPED_UNICODE)
			]
		);
		//json_return("00","처리완료".json_encode($search_txt,JSON_UNESCAPED_UNICODE));
		json_return("00", "처리완료" . $ret);
	}

	# 조건 저장
	function search_save_params2025()
	{
		$page = input_post("page", 1);
		$text = input_post("text", "");
		$addrName = input_post("addrName", "");
		$searchAreaStr = input_post("searchAreaStr", "");
		$mapLevel = input_post("mapLevel", 7);
		$category = input_post("category", "");
		$work_type = input_post("work_type", "");
		$week = input_post("week", "");
		$price_type = input_post("price_type", "");
		$sex = input_post("sex", "");
		$sage = input_post("sage", "");
		$eage = input_post("eage", "");


		#로그 생성
		$search_txt = [
			"text" => $text,
			"addrName" => $addrName,
			"searchAreaStr" => $searchAreaStr,
			"mapLevel" => $mapLevel,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
		];

		if ($this->user->isLogin()) {
			$this->site_model->deleteSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "SEARCH_RECRUIT",
					"sl_step" => "검색"
				]
			);

			$this->site_model->deleteSiteLog(
				[
					"mm_idx" => $this->mm_idx,
					"sl_type" => "SEARCH_RECRUIT",
					"sl_step" => "검색"
				]
			);
		} else {
			$this->site_model->deleteSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "SEARCH_RECRUIT",
					"sl_step" => "검색"
				]
			);
		}

		$ret = $this->site_model->setSiteLog(
			[
				"session_id" => session_id(),
				"mm_idx" => $this->mm_idx,
				"sl_type" => "SEARCH_RECRUIT",
				"sl_step" => "검색",
				"sl_txt" => json_encode($search_txt, JSON_UNESCAPED_UNICODE)
			]
		);
		json_return("00", "처리완료" . $ret);
	}

	# 메인화면 - 웹용
	function search_web_list()
	{
		$addr_info = $this->user->getAddrInfo();
		$mm_idx = $this->user->getUserIdx();
		$page = input_post("page", 1);
		$pagesize = input_post("pagesize", 10);
		$text = input_post("text", "");
		$addr1 = input_post("addr1", "");
		$addr2 = input_post("addr2", "");
		$addr3 = input_post("addr3", "");
		$skm = input_post("skm", "");
		$ekm = input_post("ekm", "");
		$category = input_post("category", "");
		$work_type = input_post("work_type", "");
		$week = input_post("week", "");
		$price_type = input_post("price_type", "");
		$sex = input_post("sex", "");
		$sage = input_post("sage", "");
		$eage = input_post("eage", "");
		$order_by = input_post("order_by", "recent");
		$multiAddr1 = input_post("multiAddr1", "");
		$multiAddr2 = input_post("multiAddr2", "");
		$multiAddr3 = input_post("multiAddr3", "");
		$compidx = input_post("comp_idx", "");

		//error_log("search_web_list:".implode(",",$addr3), "3", "/testlog/php_log");
		//error_log("search_web_list1:".$multiAddr3, "3", "/testlog/php_log");
		//error_log("search_web_list1:".$compidx, "3", "/testlog/php_log");

		$params = [
			"pagesize" => $pagesize,
			"page" => $page,
			"text" => $text,
			"mm_idx" => $mm_idx,
			"addr1" => $addr1,
			"addr2" => $addr2,
			"addr3" => $addr3,
			"skm" => $skm,
			"ekm" => $ekm,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
			"order_by" => $order_by,
			"multiAddr1" => $multiAddr1,
			"multiAddr2" => $multiAddr2,
			"multiAddr3" => $multiAddr3,
			"compidx"    => $compidx,
		];

		$result = $this->member_model->getRecruitWebSearchList($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	# 메인화면 - 웹용(2025)
	function search_web_list2025()
	{
		$addr_info = $this->user->getAddrInfo();
		$mm_idx = $this->user->getUserIdx();
		$page = input_post("page", 1);
		$pagesize = input_post("pagesize", 20);
		$text = input_post("text", "");
		$addr1 = input_post("addr1", "");
		$addr2 = input_post("addr2", "");
		$addr3 = input_post("addr3", "");
		$skm = input_post("skm", "");
		$ekm = input_post("ekm", "");
		$category = input_post("category", "");
		$work_type = input_post("work_type", "");
		$week = input_post("week", "");
		$price_type = input_post("price_type", "");
		$sex = input_post("sex", "");
		$sage = input_post("sage", "");
		$eage = input_post("eage", "");
		$order_by = input_post("order_by", "recent");
		$multiAddr1 = input_post("multiAddr1", "");
		$multiAddr2 = input_post("multiAddr2", "");
		$multiAddr3 = input_post("multiAddr3", "");
		$compidx = input_post("comp_idx", "");

		//error_log("search_web_list:".implode(",",$addr3), "3", "/testlog/php_log");
		//error_log("search_web_list1:".$multiAddr3, "3", "/testlog/php_log");
		//error_log("search_web_list1:".$compidx, "3", "/testlog/php_log");

		$params = [
			"pagesize" => $pagesize,
			"page" => $page,
			"text" => $text,
			"mm_idx" => $mm_idx,
			"addr1" => $addr1,
			"addr2" => $addr2,
			"addr3" => $addr3,
			"skm" => $skm,
			"ekm" => $ekm,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
			"order_by" => $order_by,
			"multiAddr1" => $multiAddr1,
			"multiAddr2" => $multiAddr2,
			"multiAddr3" => $multiAddr3,
			"compidx"    => $compidx,
		];

		$result = $this->member_model->getRecruitWebSearchList2025($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	function search_recruit_list2025()
	{
		$addr_info = $this->user->getAddrInfo();
		$mm_idx = $this->user->getUserIdx();
		$page = input_post("page", 1);
		$pagesize = input_post("pagesize", 20);
		$text = input_post("text", "");
		$category = input_post("category", "");
		$work_type = input_post("work_type", "");
		$week = input_post("week", "");
		$price_type = input_post("price_type", "");
		$sex = input_post("sex", "");
		$sage = input_post("sage", "");
		$eage = input_post("eage", "");
		$order_by = input_post("order_by", "recent");
		$compidx = input_post("comp_idx", "");
		$search_addr = input_post('search_addr');
		if (empty($search_addr)) {
			$search_addr = [];
		} elseif (!is_array($search_addr)) {
			$search_addr = [$search_addr];
		}

		$params = [
			"pagesize" => $pagesize,
			"page" => $page,
			"text" => $text,
			"mm_idx" => $mm_idx,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
			"order_by" => $order_by,
			"search_addr" => $search_addr,
			"compidx"    => $compidx,
		];

		$result = $this->member_model->getRecruitSearchList2025($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	# 채용추천목록(시급적용)
	function getRecruitRecoList()
	{
		$addr1 = input_post("addr1", "");
		$addr2 = input_post("addr2", "");
		$addr3 = input_post("addr3", "");
		$limit = input_post("limit", "");
		$mm_idx = $this->user->getUserIdx();

		$params = [
			"addr1" => $addr1,
			"addr2" => $addr2,
			"addr3" => $addr3,
			"mm_idx" => $mm_idx,
			"limit" => $limit,
		];

		$result = $this->recruit_model->getRecruitRecoList($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["rt_hash"] = getHashTag($row["rc_hash"], 3);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	# 채용공고목록(시급적용)
	function getRecruitList()
	{
		$addr_info = $this->user->getAddrInfo();
		$mm_idx = $this->user->getUserIdx();
		$page = input_post("page", 1);
		$pagesize = input_post("pagesize", 20);
		$text = input_post("text", "");
		$category = input_post("category", "");
		$work_type = input_post("work_type", "");
		$week = input_post("week", "");
		$price_type = input_post("price_type", "");
		$sex = input_post("sex", "");
		$sage = input_post("sage", "");
		$eage = input_post("eage", "");
		$order_by = input_post("order_by", "recent");
		$compidx = input_post("comp_idx", "");
		$search_addr = input_post('search_addr');
		if (empty($search_addr)) {
			$search_addr = [];
		} elseif (!is_array($search_addr)) {
			$search_addr = [$search_addr];
		}

		$params = [
			"pagesize" => $pagesize,
			"page" => $page,
			"text" => $text,
			"mm_idx" => $mm_idx,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
			"order_by" => $order_by,
			"search_addr" => $search_addr,
			"compidx"    => $compidx,
		];

		$result = $this->recruit_model->getRecruitList($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			//$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"],$row);
			//$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			//$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			//$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt"] = getTimeText($row["up_dt"]);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	# 기업별공고목록(시급적용)
	function getCompRecruitList()
	{
		$addr1 = input_post("addr1", "");
		$addr2 = input_post("addr2", "");
		$addr3 = input_post("addr3", "");
		$comp_idx = input_post("comp_idx", "");
		$rc_idx = input_post("rc_idx", "");
		$limit = input_post("limit", "");
		$mm_idx = $this->user->getUserIdx();
		$params = [
			"addr1" => $addr1,
			"addr2" => $addr2,
			"addr3" => $addr3,
			"comp_idx" => $comp_idx,
			"rc_idx" => $rc_idx,
			"mm_idx" => $mm_idx,
			"limit" => $limit,
		];

		$result = $this->recruit_model->getCompRecruitList($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["rt_hash"] = getHashTag($row["rc_hash"], 3);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	# 핀리스트
	function getPins2025()
	{
		$sql = "
        SELECT 
          bb.rc_idx, 
          cc.ad_gps_x, 
          cc.ad_gps_y
        FROM 
          st_member aa
        INNER JOIN st_recruit bb
          ON aa.mm_idx = bb.mm_idx
        INNER JOIN st_address cc 
          ON aa.mm_idx = cc.mm_idx 
          AND cc.ad_type = 'RECRUIT' 
          AND cc.ad_key = bb.rc_idx
          AND cc.ad_gps_x IS NOT NULL AND cc.ad_gps_y IS NOT NULL
          AND cc.ad_gps_x > 1 AND cc.ad_gps_y > 1            
        WHERE 
          aa.mm_status = 'Y' 
          AND bb.rc_status = 'Y' 
          AND bb.rc_show_yn = 'Y'
          AND bb.rc_sdate <= now() 
          AND bb.rc_edate >= now()
        GROUP BY 
          bb.rc_idx
        ORDER BY 
          bb.rc_idx desc;  
      ";
		$result = $this->db->query($sql)->result_array();
		json_return("00", "조회성공", $result);
	}

	# 핀리스트
	function getPins()
	{
		$sql = "
        SELECT 
          bb.rc_idx, 
          cc.ad_gps_x, 
          cc.ad_gps_y
        FROM 
          st_member aa
        INNER JOIN st_recruit bb
          ON aa.mm_idx = bb.mm_idx
        INNER JOIN st_address cc 
          ON aa.mm_idx = cc.mm_idx 
          AND cc.ad_type = 'RECRUIT' 
          AND cc.ad_key = bb.rc_idx
          AND cc.ad_gps_x IS NOT NULL AND cc.ad_gps_y IS NOT NULL
          AND cc.ad_gps_x > 1 AND cc.ad_gps_y > 1            
        WHERE 
          aa.mm_status = 'Y' 
          AND bb.rc_status = 'Y' 
          AND bb.rc_show_yn = 'Y'
          AND bb.rc_sdate <= now() 
          AND bb.rc_edate >= now()
        GROUP BY 
          bb.rc_idx
        ORDER BY 
          bb.rc_idx desc;  
      ";
		$result = $this->db->query($sql)->result_array();
		json_return("00", "조회성공", $result);
	}

	# 지도핀용(시급적용)
	function getPinsMapList()
	{
		$mm_idx = $this->user->getUserIdx();
		$pins = input_post("pins", "");
		$params = [
			"mm_idx" => $mm_idx,
			"pins" => $pins,
		];
		$result = $this->recruit_model->getPinsMapList($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			//$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"],$row);
			//$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	# 지도핀용(2025)
	function get_pins_map_list2025()
	{
		$addr_info = $this->user->getAddrInfo();
		$mm_idx = $this->user->getUserIdx();
		$page = input_post("page", 1);
		$pagesize = input_post("pagesize", 1000);
		$text = input_post("text", "");
		$category = input_post("category", "");
		$work_type = input_post("work_type", "");
		$week = input_post("week", "");
		$price_type = input_post("price_type", "");
		$sex = input_post("sex", "");
		$sage = input_post("sage", "");
		$eage = input_post("eage", "");
		$order_by = input_post("order_by", "recent");
		$currentLatitude = input_post("currentLatitude", "");
		$currentLongitude = input_post("currentLongitude", "");
		$search_addr = input_post("search_addr", "");
		$compidx = input_post("comp_idx", "");
		$pins = input_post("pins", "");

		$params = [
			"pagesize" => $pagesize,
			"page" => $page,
			"text" => $text,
			"mm_idx" => $mm_idx,
			"currentLatitude" => $currentLatitude,
			"currentLongitude" => $currentLongitude,
			"search_addr" => $search_addr,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
			"order_by" => $order_by,
			"compidx"    => $compidx,
			"pins" => $pins,
		];

		$result = $this->member_model->getRecruitPinsMapList2025($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	# 지도용(2025)
	function search_map_list2025()
	{
		$addr_info = $this->user->getAddrInfo();
		$mm_idx = $this->user->getUserIdx();
		$page = input_post("page", 1);
		$pagesize = input_post("pagesize", 1000);
		$text = input_post("text", "");
		$category = input_post("category", "");
		$work_type = input_post("work_type", "");
		$week = input_post("week", "");
		$price_type = input_post("price_type", "");
		$sex = input_post("sex", "");
		$sage = input_post("sage", "");
		$eage = input_post("eage", "");
		$order_by = input_post("order_by", "recent");
		$currentLatitude = input_post("currentLatitude", "");
		$currentLongitude = input_post("currentLongitude", "");
		$search_addr = input_post("search_addr", "");
		$compidx = input_post("comp_idx", "");

		$params = [
			"pagesize" => $pagesize,
			"page" => $page,
			"text" => $text,
			"mm_idx" => $mm_idx,
			"currentLatitude" => $currentLatitude,
			"currentLongitude" => $currentLongitude,
			"search_addr" => $search_addr,
			"category" => $category,
			"work_type" => $work_type,
			"week" => $week,
			"price_type" => $price_type,
			"sex" => $sex,
			"sage" => $sage,
			"eage" => $eage,
			"order_by" => $order_by,
			"compidx"    => $compidx,
		];

		$result = $this->member_model->getRecruitMapList2025($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	# 메인화면 - 추천공고
	function search_web_reco_list()
	{
		$addr1 = input_post("addr1", "");
		$addr2 = input_post("addr2", "");
		$addr3 = input_post("addr3", "");
		$mm_idx = $this->user->getUserIdx();

		$params = [
			"addr1" => $addr1,
			"addr2" => $addr2,
			"addr3" => $addr3,
			"mm_idx" => $mm_idx,
		];

		$result = $this->common_model->getFavRecruitWebList($params);

		if ($result["list"] == null) {
			$params = [
				"addr1" => "",
				"addr2" => "",
				"addr3" => "",
				"mm_idx" => $mm_idx,
			];
			$result = $this->common_model->getFavRecruitWebList($params);
		}

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["rt_hash"] = getHashTag($row["rc_hash"], 3);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	function search_web_reco_list2025()
	{
		$addr1 = input_post("addr1", "");
		$addr2 = input_post("addr2", "");
		$addr3 = input_post("addr3", "");
		$limit = input_post("limit", "");
		$mm_idx = $this->user->getUserIdx();

		$params = [
			"addr1" => $addr1,
			"addr2" => $addr2,
			"addr3" => $addr3,
			"mm_idx" => $mm_idx,
			"limit" => $limit,
		];

		$result = $this->common_model->getFavRecruitWebList2025($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["rt_hash"] = getHashTag($row["rc_hash"], 3);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	function search_comp_reco_list2025()
	{
		$addr1 = input_post("addr1", "");
		$addr2 = input_post("addr2", "");
		$addr3 = input_post("addr3", "");
		$comp_idx = input_post("comp_idx", "");
		$rc_idx = input_post("rc_idx", "");
		$limit = input_post("limit", "");
		$mm_idx = $this->user->getUserIdx();
		$params = [
			"addr1" => $addr1,
			"addr2" => $addr2,
			"addr3" => $addr3,
			"comp_idx" => $comp_idx,
			"rc_idx" => $rc_idx,
			"mm_idx" => $mm_idx,
			"limit" => $limit,
		];

		$result = $this->common_model->getCompRecruitWebList2025($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkType($row["rc_work_type"], "span");
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["rt_hash"] = getHashTag($row["rc_hash"], 3);
		}
		$result["list"] = $temp_list;

		json_return("00", "조회성공", $result);
	}

	//오늘 지원했던 공고인지 확인
	function todayapply()
	{
		$mm_idx = $this->user->getUserIdx();
		$rc_idx = input_post("rc_idx", "");
		$ru_idx = input_post("ru_idx", ""); // 이력서 정보 추가

		// 이력서 정보가 없으면 현재 사용자의 공개 이력서를 가져옴
		if (empty($ru_idx)) {
			$resume = $this->member_model->getResume(["mm_idx" => $mm_idx, "ru_show_yn" => "Y"]);
			if ($resume) {
				$ru_idx = $resume["ru_idx"];
			}
		}

		// 이력서가 없으면 오류 반환
		if (empty($ru_idx)) {
			json_return("90", "이력서 정보를 찾을 수 없습니다.", null);
			return;
		}

		//지원내역이 오늘인지 확인해서 리턴 (ru_idx 추가)
		$query  = "SELECT count(*) cnt FROM st_member_request ";
		$query .= "WHERE mq_type = 'RECRUIT'  ";
		$query .= "  AND rc_idx = '" . $rc_idx . "' ";
		$query .= "  AND mm_idx = '" . $mm_idx . "'  "; // 지원자
		$query .= "  AND ru_idx = '" . $ru_idx . "'  "; // 이력서 추가
		$query .= "  AND reg_dt LIKE '" . date("Y-m-d") . "%'  ";

		$result = $this->db->query($query);
		$count = $result->row_array();
		$today_cnt = 0;
		if ($count != null) {
			$today_cnt = $count["cnt"];
		}

		if ($today_cnt > 0) {
			json_return("01", "이미지원", $result);
			return;
		}

		//중복지원내역에 오늘이 있는지 확인해서 리턴 (ru_idx 추가)
		$query  = "SELECT count(*) cnt FROM st_member_request_dup ";
		$query .= "WHERE mq_type = 'RECRUIT'  ";
		$query .= "  AND rc_idx = '" . $rc_idx . "' ";
		$query .= "  AND mm_idx = '" . $mm_idx . "'  "; // 지원자
		$query .= "  AND ru_idx = '" . $ru_idx . "'  "; // 이력서 추가
		$query .= "  AND reg_dt LIKE '" . date("Y-m-d") . "%'  ";

		$result = $this->db->query($query);
		$count = $result->row_array();
		$today_cnt = 0;
		if ($count != null) {
			$today_cnt = $count["cnt"];
		}

		if ($today_cnt > 0) {
			json_return("01", "이미지원", $result);
			return;
		}
		
		// 기업이 이미 제안한 공고-이력서 조합인지 확인
		$sql_proposal = "
			SELECT COUNT(*) as cnt
			FROM st_member_request
			WHERE mq_type = 'RESUME'
			AND rc_idx = '" . $this->db->escape_str($rc_idx) . "'
			AND ru_idx = '" . $this->db->escape_str($ru_idx) . "'
		";
		$query_proposal = $this->db->query($sql_proposal);
		$proposal_result = $query_proposal->row_array();
		
		if ($proposal_result && $proposal_result["cnt"] > 0) {
			// 제안이 이미 있는 경우 알림창 표시
			json_return("90", "이미 기업에서 제안한 공고입니다.<br/>같은 공고와 같은 이력서로 지원할 수 없습니다.");
			return;
		}
		
		json_return("00", "조회성공", $result);
	}
}
