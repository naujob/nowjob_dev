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
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
			$temp_list[$key]["rc_age_nm"] = formatAgeRange($row["rc_sage"], $row["rc_eage"]);
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
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
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
		$addrName = input_post("addrName", "");
		$searchAreaStr = input_post("searchAreaStr", "");
		if (empty($search_addr)) {
			$search_addr = [];
		} elseif (!is_array($search_addr)) {
			$search_addr = [$search_addr];
		}

		// searchAreaStr에서 step 정보 추출
		$search_addr_step = [];
		if (!empty($searchAreaStr)) {
			try {
				$search_area = json_decode($searchAreaStr, true);
				if (is_array($search_area)) {
					foreach ($search_area as $area) {
						if (isset($area['step'])) {
							$search_addr_step[] = intval($area['step']);
						}
					}
				}
			} catch (Exception $e) {
				// 파싱 실패 시 무시
			}
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
			"search_addr_step" => $search_addr_step,
			"addrName" => $addrName,
			"searchAreaStr" => $searchAreaStr,
			"compidx"    => $compidx,
		];

		$result = $this->member_model->getRecruitSearchList2025($params);

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
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

		$truncate_length = getTruncateLength();

		$temp_list = $result["list"];
		foreach ($temp_list as $key => $row) {
			$temp_list[$key]["rt_recruit_img"] = getRecruitImg($row);
			$temp_list[$key]["rt_work_img"] = getWorkingImg($row["rc_work"]);
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
			$temp_list[$key]["rt_price_type"] = getConvertPriceType($row["rc_price_type"], $row["rc_price_rep"]);
			$temp_list[$key]["rt_price_pay"] = getConvertPrice($row["rc_price_pay"], $row["rc_price_type"]);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["rt_hash"] = getHashTag($row["rc_hash"], 3);
			$temp_list[$key]["rc_age_nm"] = formatAgeRange($row["rc_sage"], $row["rc_eage"]);
			// 서버사이드 주소 변환 (지번 기반, 큰 시/도 제거)
			$temp_list[$key]["ad_addr"] = removeBigAddr(isset($row["ad_addr"]) ? $row["ad_addr"] : "");
			// 서버사이드 제목 정규화 (모바일 30글자, PC 50글자 - 멀티바이트 안전)
			if (mb_strlen($row["rc_name"]) > $truncate_length) {
				$temp_list[$key]["rc_name"] = mb_substr($row["rc_name"], 0, $truncate_length) . '...';
			}
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
		$limit = input_post("limit", 20);
		$pagesize = input_post("pagesize", $limit); // limit과 pagesize 통일
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
		$addrName = input_post("addrName", "");
		$searchAreaStr = input_post("searchAreaStr", "");

		if (empty($search_addr)) {
			$search_addr = [];
		} elseif (!is_array($search_addr)) {
			$search_addr = [$search_addr];
		}

		// searchAreaStr에서 step 정보 추출
		$search_addr_step = [];
		if (!empty($searchAreaStr)) {
			try {
				$search_area = json_decode($searchAreaStr, true);
				if (is_array($search_area)) {
					foreach ($search_area as $area) {
						if (isset($area['step'])) {
							$search_addr_step[] = intval($area['step']);
						}
					}
				}
			} catch (Exception $e) {
				// JSON 파싱 실패 시 무시
			}
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
			"search_addr_step" => $search_addr_step,
			"compidx"    => $compidx,
		];

		try {
			$result = $this->recruit_model->getRecruitList($params);

			if (!is_array($result)) {

				json_return("99", "데이터 조회 실패(반환값 오류)");
				return;
			}

			$truncate_length = getTruncateLength();

			if (!is_array($result["list"])) {
				
				$result["list"] = [];
			}

			$result["list"] = $this->_formatRecruitList($result["list"], $truncate_length);
			$result["list"] = $this->_addChatRoomInfo($result["list"]);
			// JavaScript 페이지네이션용 total 필드 추가
			$result["total"] = isset($result["count"]) ? $result["count"] : 0;

			json_return("00", "조회성공", $result);
		} catch (Exception $e) {
			
			json_return("99", "데이터 조회 중 오류 발생: " . $e->getMessage());
		}
	}

	// 채용공고 데이터 포맷팅 (뷰와 AJAX 응답에서 공통 사용)
	private function _formatRecruitList($list, $truncate_length = 18)
	{
		$formatted_list = [];
		foreach ($list as $row) {
			$rc_work = isset($row['rc_work']) ? $row['rc_work'] : '';
			$rc_work_type = isset($row['rc_work_type']) ? $row['rc_work_type'] : '';
			$rc_price_type = isset($row['rc_price_type']) ? $row['rc_price_type'] : '';
			$rc_price_rep = isset($row['rc_price_rep']) ? $row['rc_price_rep'] : '';
			$rc_price_pay = isset($row['rc_price_pay']) ? $row['rc_price_pay'] : '';
			$rc_sage = isset($row['rc_sage']) ? $row['rc_sage'] : '';
			$rc_eage = isset($row['rc_eage']) ? $row['rc_eage'] : '';
			$rc_nation_nm = isset($row['rc_nation_nm']) ? $row['rc_nation_nm'] : '';
			$ad_addr = isset($row['ad_addr']) ? $row['ad_addr'] : '';

			$formatted_list[] = [
				'rc_idx' => isset($row['rc_idx']) ? $row['rc_idx'] : '',
				'mm_idx' => isset($row['mm_idx']) ? $row['mm_idx'] : '',
				'pm_idx' => isset($row['pm_idx']) ? $row['pm_idx'] : null,
				'rt_recom_badge' => getRecomBadgeHtml(isset($row['pm_idx']) ? $row['pm_idx'] : null),
				'book_yn' => isset($row['book_yn']) ? $row['book_yn'] : 'N',
				'rc_name' => (mb_strlen($row['rc_name']) > $truncate_length) ? mb_substr($row['rc_name'], 0, $truncate_length) . '...' : $row['rc_name'],
				'rt_recruit_img' => getRecruitImg($row),
				'rt_work_img' => getWorkingImg($rc_work),
				'rt_work_type' => getWorkTypeWithColor($rc_work_type),
				'rt_price_type' => getConvertPriceType($rc_price_type, $rc_price_rep),
				'rt_price_pay' => getConvertPrice($rc_price_pay, $rc_price_type),
				'rc_price_type' => $rc_price_type,
				'rc_price_pay' => $rc_price_pay,
				'rc_nation_nm' => (in_array($rc_nation_nm, ["무관", "내국인,외국인"])) ? "국적무관" : $rc_nation_nm,
				'rc_age_nm' => formatAgeRange($rc_sage, $rc_eage),
				'ad_addr' => removeBigAddr($ad_addr),
				'mm_id' => isset($row['mm_id']) ? $row['mm_id'] : '',
				'up_dt' => (isset($row['up_dt']) && $row['up_dt'] != '') ? getTimeText($row['up_dt']) : ''
			];
		}
		return $formatted_list;
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
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
			$temp_list[$key]["rt_price_type"] = getConvertPriceType($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["rt_hash"] = getHashTag($row["rc_hash"], 3);
			$temp_list[$key]["rc_age_nm"] = formatAgeRange($row["rc_sage"], $row["rc_eage"]);
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
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
			$temp_list[$key]["rt_price_type"] = getConvertPriceType($row["rc_price_type"], $row["rc_price_rep"]);
			$temp_list[$key]["rt_price_pay"] = getConvertPrice($row["rc_price_pay"], $row["rc_price_type"]);
			$temp_list[$key]["rt_recom_badge"] = getRecomBadgeHtml(isset($row["pm_idx"]) ? $row["pm_idx"] : null);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
			$temp_list[$key]["rc_age_nm"] = formatAgeRange($row["rc_sage"], $row["rc_eage"]);
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
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["rt_recom_badge"] = getRecomBadgeHtml(isset($row["pm_idx"]) ? $row["pm_idx"] : null);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
			$temp_list[$key]["rc_age_nm"] = formatAgeRange($row["rc_sage"], $row["rc_eage"]);
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
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
			$temp_list[$key]["rt_price_type"] = getPriceTypePoint($row["rc_price_type"], $row);
			$temp_list[$key]["rt_price"] = getRecuitPrice($row);
			$temp_list[$key]["timestamp"] = getTimeText($row["reg_dt"]);
			$temp_list[$key]["pm_timestamp"] = getTimeText($row["pm_reg_dt"]);
			$temp_list[$key]["up_dt_timestamp"] = getTimeText($row["up_dt"]);
			$temp_list[$key]["rc_age_nm"] = formatAgeRange($row["rc_sage"], $row["rc_eage"]);
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
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
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
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
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
			$temp_list[$key]["rt_work_type"] = getWorkTypeWithColor($row["rc_work_type"]);
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

		// 같은 이력서로 같은 공고에 이미 지원했으면 다시 지원할 수 있도록 안내 (code 02 + rm_idx)
		$active_rm_idx = $this->member_model->findActiveRequestRoom("RECRUIT", $mm_idx, $rc_idx, $ru_idx, $this->user->getUserId(), $this->user->getUserPhone());
		if ($active_rm_idx) {
			$exited_before = has_room_exit_history($active_rm_idx) ? "Y" : "N";
			// 당사자가 지금 나간 상태(exit_yn='Y')일 수 있으므로 미리 복구해 둔다
			// (그래야 팝업 확인 후 auto_send로 재전송할 때 발신 차단/방 조회 차단에 걸리지 않는다)
			reset_room_exit_status(get_mongo_uri(), get_mongo_db_name(), $active_rm_idx);
			json_return("02", "이미 지원했습니다. 다시 보내시겠습니까.", array("rm_idx" => $active_rm_idx, "ru_idx" => $ru_idx, "exited_before" => $exited_before));
			return;
		}

		// 기업(공고 소유자)이 이미 제안한 공고-이력서 조합인지 확인
		if (!empty($rc_idx) && !empty($ru_idx)) {
			// rc_idx의 소유자(mm_idx) 조회
			$sql_owner = "SELECT mm_idx FROM st_recruit WHERE rc_idx = ?";
			$result_owner = $this->db->query($sql_owner, array($rc_idx));
			$owner_row = $result_owner->row_array();

			if ($owner_row) {
				$owner_mm_idx = $owner_row["mm_idx"];
				// 그 소유자가 이 이력서에 RESUME(제안)으로 보낸 활성 요청이 있는지 확인
				$active_rm_idx = $this->member_model->findActiveRequestRoom("RESUME", $owner_mm_idx, $rc_idx, $ru_idx, $this->user->getUserId(), $this->user->getUserPhone());
				if ($active_rm_idx) {
					$exited_before = has_room_exit_history($active_rm_idx) ? "Y" : "N";
					reset_room_exit_status(get_mongo_uri(), get_mongo_db_name(), $active_rm_idx);
					json_return("02", "이미 기업에서 제안한 공고입니다. 다시 지원을 보내시겠습니까.", array("rm_idx" => $active_rm_idx, "ru_idx" => $ru_idx, "exited_before" => $exited_before));
					return;
				}
			}
		}

		json_return("00", "조회성공", null);
	}

	# 전화하기/지원하기 클릭 로그 기록
	function call_log()
	{
		$rc_idx = input_post("rc_idx", "");
		if ($rc_idx == "") {
			json_return("99", "잘못된 요청입니다.");
			return;
		}

		$cl_type = input_post("cl_type", "CALL");
		$mm_idx  = $this->user->isLogin() ? $this->user->getUserIdx() : null;
		$mm_type = $this->user->isLogin() ? $this->user->getUserType() : "비회원";
		$ip      = $this->common->getIpAddr();
		$session = session_id();

		$data = array(
			"rc_idx"        => $rc_idx,
			"cl_type"       => $cl_type,
			"mm_idx"        => $mm_idx,
			"mm_type"       => $mm_type,
			"cl_ip"         => $ip,
			"cl_session_id" => $session,
			"reg_dt"        => date("Y-m-d H:i:s"),
		);

		$result = $this->recruit_model->insertCallLog($data);
		json_return("00", "success");
	}


	# 캐시 테이블 갱신 (Cron 용)
	function refreshRecruitCache()
	{
		// 환경 감지
		$is_dev = strpos($_SERVER['REQUEST_URI'], '/dev_nowapk') !== false ||
		          strpos($_SERVER['HTTP_HOST'], 'dev.') === 0;
		$environment = $is_dev ? 'development' : 'production';

		// 환경별 보안 키
		$secret_keys = [
			'development' => 'kD9mP2x5nQ5wR8vL3bT',
			'production'  => 'ProdSeC7kEy9mL2wR'
		];

		$secret_key = $secret_keys[$environment];
		$provided_key = isset($_GET['key']) ? $_GET['key'] : '';

		// 보안: API 키 확인
		if (empty($provided_key) || $provided_key !== $secret_key) {
			http_response_code(403);
			json_return("403", "Forbidden");
			return;
		}

		$start_time = microtime(true);

		try {
			// 캐시 테이블 갱신
			$this->db->query("TRUNCATE TABLE vw_recruit_list_cache");

			$sql = "INSERT INTO vw_recruit_list_cache (
				rc_idx, mm_id, mm_idx, mm_status, mm_comp_name, rc_name, rc_work, rc_work_type,
				rc_price_type, rc_price_rep, rc_price_pay, rc_img1, rc_show_yn, rc_status,
				rc_sdate, rc_edate, rc_sage, rc_eage, rc_nation, rc_sex, rc_week, rc_hash,
				ad_addr, s_addr, ad_gps_x, ad_gps_y, book_cnt, pm_idx, pm_code, up_dt, reg_dt
			)
			SELECT
			  aa.rc_idx,
			  aa.mm_id,
			  aa.mm_idx,
			  aa.mm_status,
			  aa.mm_comp_name,
			  aa.rc_name,
			  aa.rc_work,
			  aa.rc_work_type,
			  aa.rc_price_type,
			  aa.rc_price_rep,
			  aa.rc_price_pay,
			  aa.rc_img1,
			  aa.rc_show_yn,
			  aa.rc_status,
			  aa.rc_sdate,
			  aa.rc_edate,
			  aa.rc_sage,
			  aa.rc_eage,
			  CASE
			    WHEN aa.rc_nation = '내국인,외국인' THEN '국적무관'
			    WHEN aa.rc_nation = '내국인' THEN '내국인'
			    WHEN aa.rc_nation = '외국인' THEN '외국인'
			    ELSE '국적무관'
			  END AS rc_nation,
			  CASE
			    WHEN aa.rc_sex = '남자,여자' THEN '성별무관'
			    WHEN aa.rc_sex = '남자' THEN '남자'
			    WHEN aa.rc_sex = '여자' THEN '여자'
			    ELSE '성별무관'
			  END AS rc_sex,
			  aa.rc_week,
			  aa.rc_hash,
			  aa.ad_addr,
			  CONCAT(aa.ad_doro, ' ', aa.ad_jibun) AS s_addr,
			  aa.ad_gps_x,
			  aa.ad_gps_y,
			  (SELECT COUNT(*) FROM st_bookmark WHERE bk_type = 'RECRUIT' AND bk_key = aa.rc_idx) AS book_cnt,
			  (SELECT pm_idx FROM st_promotion WHERE rc_idx = aa.rc_idx AND pm_code = 'C001' LIMIT 1) AS pm_idx,
			  (SELECT pm_code FROM st_promotion WHERE rc_idx = aa.rc_idx AND pm_code IN ('C001', 'C002', 'C003') LIMIT 1) AS pm_code,
			  GREATEST(CASE WHEN aa.up_dt = '0000-00-00 00:00:00' THEN aa.reg_dt ELSE aa.up_dt END,
			           IFNULL((SELECT MAX(up_dt) FROM st_promotion WHERE rc_idx = aa.rc_idx AND pm_code IN ('C002','C003')), '2000-01-01 00:00:00')) AS up_dt,
			  aa.reg_dt
			FROM (
			  SELECT
			    a.mm_id,
			    a.mm_status,
			    a.mm_idx,
			    a.mm_comp_num,
			    a.mm_comp_name,
			    b.rc_idx,
			    b.rc_name,
			    b.rc_work,
			    b.rc_work_type,
			    b.rc_price_type,
			    b.rc_price_rep,
			    b.rc_price_pay,
			    b.rc_img1,
			    b.rc_show_yn,
			    b.rc_status,
			    b.rc_sdate,
			    b.rc_edate,
			    b.rc_sage,
			    b.rc_eage,
			    b.rc_nation,
			    b.rc_sex,
			    b.rc_week,
			    b.rc_hash,
			    b.rc_work_dt,
			    b.reg_dt,
			    b.up_dt,
			    c.ad_addr,
			    c.ad_doro,
			    c.ad_jibun,
			    c.ad_gps_x,
			    c.ad_gps_y
			  FROM st_member a
			  INNER JOIN st_recruit b ON a.mm_idx = b.mm_idx
			  INNER JOIN st_address c ON a.mm_idx = c.mm_idx
			    AND c.ad_type = 'RECRUIT'
			    AND c.ad_key = b.rc_idx
			  WHERE a.mm_status = 'Y'
			    AND b.rc_show_yn = 'Y'
			    AND b.rc_status = 'Y'
			    AND b.rc_sdate <= NOW()
			    AND b.rc_edate >= NOW()
			) aa
			GROUP BY aa.rc_idx
			ORDER BY aa.up_dt DESC
			LIMIT 10000";

			$this->db->query($sql);

			$elapsed = microtime(true) - $start_time;

			json_return("00", "캐시 갱신 완료", [
				'environment' => $environment,
				'elapsed_time' => round($elapsed * 1000, 2) . 'ms',
				'timestamp' => date('Y-m-d H:i:s')
			]);

		} catch (Exception $e) {
			http_response_code(500);
			json_return("500", $e->getMessage());
		}
	}

	// 공고 목록에 채팅 배지 정보 추가 (mm_idx 기준 최신 1건 모델)
	private function _addChatRoomInfo($list)
	{
		if (empty($list)) {
			return $list;
		}

		// 로그인한 개인회원이 아니면 배지 숨김
		if ($this->user->getUserType() !== 'MEM') {
			foreach ($list as &$row) {
				$row['chat_rm_idx'] = '';
				$row['chat_type'] = '';
			}
			return $list;
		}

		// 정식 공고 idx 추출
		$rc_idxs = array();
		foreach ($list as $row) {
			$rc_idxs[] = $row['rc_idx'];
		}

		// st_member_request 배치 조회
		// mq_type으로 아이콘 종류 판단
		// rc_idx 또는 origin_rc_idx가 화면상 공고에 일치하는 기록을 모두 조회하고, rc_idx 단위로 최신 1건씩 선택
		$user_idx = $this->user->getUserIdx();
		$chat_rooms = $this->db->query("
			SELECT
				mr.rc_idx,
				mr.origin_rc_idx,
				mr.mq_type,
				mr.rm_idx
			FROM st_member_request mr
			WHERE (mr.rc_idx IN (" . implode(',', array_fill(0, count($rc_idxs), '?')) . ")
				OR mr.origin_rc_idx IN (" . implode(',', array_fill(0, count($rc_idxs), '?')) . "))
				AND mr.mq_type IN ('RECRUIT','RECRUIT_DIRECT')
				AND mr.mm_idx = ?
			ORDER BY (mr.mq_type = 'RECRUIT') DESC, mr.mq_idx DESC
		", array_merge($rc_idxs, $rc_idxs, array($user_idx)))->result_array();

		// rc_idx 단위로 첫 등장 1건만 채택 (정렬상 정식 지원이 있으면 정식이 먼저 나옴)
		// origin_rc_idx가 있으면 그것을 기준으로, 없으면 rc_idx를 기준으로 매칭
		$room_by_rc_idx = array();
		foreach ($chat_rooms as $room) {
			$effective_rc_idx = !empty($room['origin_rc_idx']) ? $room['origin_rc_idx'] : $room['rc_idx'];

			// 해당 공고의 요청을 이미 찾았으면 스킵
			if (isset($room_by_rc_idx[$effective_rc_idx])) {
				continue;
			}

			// mq_type으로 아이콘 종류 판단
			$chat_type = (strpos($room['mq_type'], '_DIRECT') !== false) ? 'direct' : 'apply';

			$room_by_rc_idx[$effective_rc_idx] = array(
				'rm_idx' => isset($room['rm_idx']) ? $room['rm_idx'] : '',
				'chat_type' => $chat_type
			);
		}

		// 요청 기록이 없는 순수 채팅방(채팅하기) 보완: 방에 연결된 공고가 화면상 공고이고 내 이력서인 방을 direct 배지로 표시
		$missing_rc_idxs = array();
		foreach ($rc_idxs as $list_rc_idx) {
			if (!isset($room_by_rc_idx[$list_rc_idx])) {
				$missing_rc_idxs[] = $list_rc_idx;
			}
		}
		if (!empty($missing_rc_idxs)) {
			$pure_rooms = $this->db->query("
				SELECT room.rc_idx, room.rm_idx
				FROM st_room room
				INNER JOIN st_resume ru ON ru.ru_idx = room.ru_idx
				WHERE room.rm_type = 'recruit'
					AND room.rc_idx IN (" . implode(',', array_fill(0, count($missing_rc_idxs), '?')) . ")
					AND ru.mm_idx = ?
				ORDER BY room.rm_idx DESC
			", array_merge($missing_rc_idxs, array($user_idx)))->result_array();

			foreach ($pure_rooms as $pure_room) {
				if (!isset($room_by_rc_idx[$pure_room['rc_idx']])) {
					$room_by_rc_idx[$pure_room['rc_idx']] = array(
						'rm_idx' => $pure_room['rm_idx'],
						'chat_type' => 'direct'
					);
				}
			}
		}

		// 나간 방(exit_yn='Y')을 배치로 조회
		$rm_idxs_to_check = array();
		foreach ($room_by_rc_idx as $room_info) {
			if (!empty($room_info['rm_idx'])) {
				$rm_idxs_to_check[] = $room_info['rm_idx'];
			}
		}

		$exited_rm_idxs = array();
		if (!empty($rm_idxs_to_check)) {
			$exited_rm_idxs = get_exited_rm_idxs($rm_idxs_to_check, $this->user->getUserId(), $this->user->getUserPhone());
			error_log("[recruit_proc.php _addChatRoomInfo] rm_idxs_to_check=" . json_encode($rm_idxs_to_check) . ", exited_rm_idxs=" . json_encode($exited_rm_idxs));
		}
		$exited_set = array_flip($exited_rm_idxs);

		// 목록에 chat_rm_idx와 chat_type 추가
		// rc_idx로 정확히 매칭
		foreach ($list as &$row) {
			$target_rc_idx = $row['rc_idx'];

			if (isset($room_by_rc_idx[$target_rc_idx])) {
				$rm_idx = $room_by_rc_idx[$target_rc_idx]['rm_idx'];

				// 나간 방이면 배지 숨김
				if (isset($exited_set[$rm_idx])) {
					$row['chat_rm_idx'] = '';
					$row['chat_type'] = '';
				} else {
					$row['chat_rm_idx'] = $rm_idx;
					$row['chat_type'] = $room_by_rc_idx[$target_rc_idx]['chat_type'];
				}
			} else {
				$row['chat_rm_idx'] = '';
				$row['chat_type'] = '';
			}
		}

		return $list;
	}
}
