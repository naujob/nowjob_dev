<?php
class siteModel extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}

	######################################
	# 공통 처리 START
	######################################
	function setBookmark($data)
	{
		$check = [
			"bk_type" => $data["bk_type"],
			"bk_key" => $data["bk_key"],
			"mm_idx" => $data["mm_idx"]
		];
		$info = $this->db_row("st_bookmark", $check);

		if ($info == null) {
			$this->db_insert("st_bookmark", $check);
			return "Y";
		} else {
			$this->db_delete("st_bookmark", $check);
			return "N";
		}
	}

	function getBookmark($where)
	{
		return $this->db_row("st_bookmark", $where);
	}

	function deleteBookmark($where)
	{
		return $this->db_delete("st_bookmark", $where);
	}

	//주소 쇼트 처리
	function getAddrShort($addr)
	{
		$arr_addr = explode(" ", $addr);
		$addr2 = "";
		$addr3 = "";
		$addr31 = "";
		$addr4 = "";

		if (count($arr_addr) > 1) {
			$addr2 = $arr_addr[0] . " " . $arr_addr[1];
		}

		if (count($arr_addr) > 2) {
			$addr3 = $arr_addr[0] . " " . $arr_addr[1] . " " . $arr_addr[2];
			$addr31 = "%" . $arr_addr[1] . "%" . $arr_addr[2];
		}

		if (count($arr_addr) > 3) {
			$addr4 = $arr_addr[0] . " " . $arr_addr[1] . " " . $arr_addr[2] . " " . $arr_addr[3];
		}

		//카운터쿼리 생성
		$sql_data = "
			select ca_short_addr
				FROM st_code_addr
			where (ca_addr = '$addr2' or ca_addr = '$addr3' or ca_addr = '$addr4')
		";

		$result = $this->db->query($sql_data);
		$info = $result->row_array();

		if ($info == null) {
			//error_log("getAddrShort 1", "3", "/testlog/php_log");
			$sql_data = "
  			select ca_short_addr
  				FROM st_code_addr
  			where (ca_addr = '$addr2' or ca_addr like '$addr31%') limit 1
  		";
			//error_log("getAddrShort 2 ".$sql_data, "3", "/testlog/php_log");

			$result = $this->db->query($sql_data);
			$info = $result->row_array();
			if ($info == null) {
				//error_log("getAddrShort 3", "3", "/testlog/php_log");
				$sql_data = "
    			select ca_short_addr
    				FROM st_code_addr
    			where (ca_addr like '$addr2%' ) limit 1
    		";
				//error_log("getAddrShort 3 ".$sql_data, "3", "/testlog/php_log");

				$result = $this->db->query($sql_data);
				$info = $result->row_array();

				if ($info == null) {
					$sql_data = "
      			select ca_short_addr
      				FROM st_code_addr
      			where (ca_addr like '" . $arr_addr[0] . " " . $arr_addr[1] . " " . $arr_addr[2] . "%' ) limit 1
      		";
					//error_log("getAddrShort 3 ".$sql_data, "3", "/testlog/php_log");

					$result = $this->db->query($sql_data);
					$info = $result->row_array();

					if ($info == null) {
						$sql_data = "
        			select ca_short_addr
        				FROM st_code_addr
        			where (ca_addr like '" . $arr_addr[0] . " " . $arr_addr[1] . "%' ) limit 1
        		";
						//error_log("getAddrShort 3 ".$sql_data, "3", "/testlog/php_log");

						$result = $this->db->query($sql_data);
						$info = $result->row_array();

						if ($info == null) {
							$sql_data = "
          			select ca_short_addr
          				FROM st_code_addr
          			where (ca_addr like '" . $arr_addr[0] . "%' ) limit 1
          		";
							//error_log("getAddrShort 3 ".$sql_data, "3", "/testlog/php_log");

							$result = $this->db->query($sql_data);
							$info = $result->row_array();

							if ($info == null) {
								return "";
							} else {
								//error_log("getAddrShort 3", "3", "/testlog/php_log");
								return $info["ca_short_addr"];
							}
						} else {
							//error_log("getAddrShort 3", "3", "/testlog/php_log");
							return $info["ca_short_addr"];
						}
					} else {
						//error_log("getAddrShort 3", "3", "/testlog/php_log");
						return $info["ca_short_addr"];
					}
				} else {
					//error_log("getAddrShort 3", "3", "/testlog/php_log");
					return $info["ca_short_addr"];
				}
			} else {
				//error_log("getAddrShort 3", "3", "/testlog/php_log");
				return $info["ca_short_addr"];
			}
		} else {
			//error_log("getAddrShort 4", "3", "/testlog/php_log");
			return $info["ca_short_addr"];
		}
	}

	function getDepthAddrList($depth, $addr1, $addr2)
	{
		if ($depth == 1) {
			$sql_data = "
				select ca_ad1,ca_ad2
					FROM st_code_addr
				where ca_ad1 = '$addr1'
				group by ca_ad1,ca_ad2
			";

			$result = $this->db->query($sql_data);
			return $result->result_array();
		} else if ($depth == 2) {
			$sql_data = "
				select ca_ad1,ca_ad2,ca_ad3
					FROM st_code_addr
				where ca_ad1 = '$addr1'
					and ca_ad2 = '$addr2'
				group by ca_ad1,ca_ad2,ca_ad3
			";

			$result = $this->db->query($sql_data);
			return $result->result_array();
		}
	}

	function getAddrListKeyLike($addr)
	{
		$sql_data = "
				select ca_short_addr
					FROM st_code_addr
				where ca_short_addr like '%$addr%'
				group by ca_short_addr
        limit 30
			";

		$result = $this->db->query($sql_data);
		return $result->result_array();
	}

	# 싸이트 로그 삭제
	function deleteSiteLog($where)
	{
		return $this->db_delete("st_site_log", $where);
	}

	# 싸이트 로그 등록
	function setSiteLog($data, $del_where = [])
	{
		# 90일 지난 로그 지우고
		$this->db_delete("st_site_log", "reg_dt <= DATE_SUB(now(),INTERVAL 90 DAY)");

		#삭제하고 로그 남기기
		if ($del_where != []) {
			$this->db_delete("st_site_log", $del_where);
		}

		# 새로운 로그 생성
		return $this->db_insert("st_site_log", $data);
		//return $data['category'];
		//return "test";
	}

	# 싸이트 로그 수정
	function updateSiteLog($data, $where)
	{
		return $this->db_update("st_site_log", $data, $where);
	}

	# 싸이트 로그 리스트 조회
	function getSiteLogList($where)
	{
		return $this->db_list("st_site_log", $where);
	}

	# 싸이트 로그 조회
	function getSiteLog($where)
	{
		return $this->db_row("st_site_log", $where);
	}

	# 코드 리스트 조회
	function getCodeList($where)
	{
		$where["use_yn"] = "Y";
		return $this->db_list("st_code", $where, "*", ["cd_sort" => "asc"]);
	}

	# 스텝별 데이터 조회
	function getStepData($list, $type)
	{
		foreach ($list as $row) {
			if ($row["sl_step"] == $type) {
				return  json_decode($row["sl_txt"], true);
			}
		}

		return [];
	}

	# 스텝 데이터 조회
	function getStepTempData($type, $step)
	{
		// 최신 데이터를 가져오기 위해 정렬 추가
		$where = [
			"session_id" => session_id(),
			"sl_type" => $type,
			"sl_step" => $step,
			"sl_status" => "Y"
		];

		$list = $this->db_list("st_site_log", $where, "*", ["reg_dt" => "desc"], 0, 1);

		if (empty($list) || $list == null) {
			return [];
		} else {
			$decoded = json_decode($list[0]["sl_txt"], true);
			return $decoded;
		}
	}

	# 스텝 데이터 조회 : 회원
	function getStepTempDataMember($mm_idx, $type, $step)
	{
		$info = $this->getSiteLog([
			"mm_idx" => $mm_idx,
			"sl_type" => $type,
			"sl_step" => $step,
			"sl_status" => "Y"
		]);

		if ($info == null) {
			return [];
		} else {
			return json_decode($info["sl_txt"], true);
		}
	}

	# 스텝 데이터 체크
	function checkSiteLog($sl_type, $sl_step)
	{
		$info = $this->getSiteLog(
			[
				"session_id" => session_id(),
				"sl_type" => $sl_type,
				"sl_step" => $sl_step,
				"sl_status" => "Y"
			]
		);

		if ($info == null) {
			return false;
		} else {
			return true;
		}
	}

	# SMS발송 건수 확인
	function checkSmsSendCount($phone, $sl_type)
	{
		$sql_data = "
			select count(*) as cnt
			FROM st_sms_send_list
			where sl_phone = '" . $this->inject($phone) . "'
			and sl_type = '" . $this->inject($sl_type) . "'
			and date_format(reg_dt,'%Y-%m-%d') = date_format(now(),'%Y-%m-%d')
		";

		$result = $this->db->query($sql_data);
		$count =  $result->row_array();

		if ($count["cnt"] > 10) {
			return ["code" => "90", "msg" => "발송갯수 오버"];
		} else {
			return ["code" => "00", "msg" => "발송가능"];
		}
	}


	# 인증번호 확인
	function checkSmsAuth($phone, $auth)
	{
		$sql_data = "
			select *
			FROM st_sms_send_list
			where sl_phone = '" . $this->inject($phone) . "'
			order by sl_idx desc
			limit 0,1
		";

		$result = $this->db->query($sql_data);
		$sms =  $result->row_array();

		if ($sms == null) {
			return ["code" => "90", "msg" => "잘못된 휴대폰번호입니다."];
		} else {
			if ($sms["sl_auth"] == $auth) {
				return ["code" => "00", "msg" => "확인완료"];
			} else {
				return ["code" => "90", "msg" => "잘못된 인증번호입니다."];
			}
		}
	}

	######################################
	# END 공통 처리
	######################################

	######################################
	# 배너처리 END
	######################################

	//진열관리  리스트
	function getBannerGroupList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"(select count(*) as cnt from st_banner where bg_idx = aa.bg_idx) as banner_cnt",
		];

		//바디
		$body = "
			st_banner_group aa
		";

		//조건
		$where = [];
		$where[] = " and bg_idx <> 1 ";

		if ($params["design_type"] != "") {
			$where[] = 	" and (design_type = '" . $this->inject($params["design_type"]) . "') ";
		}

		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (bg_name like '%" . $this->inject($params["search_value"]) . "%' or bg_desc like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "bg_name") {
				$where[] = 	" and (bg_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "bg_desc") {
				$where[] = 	" and (bg_desc like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		if ($params["bg_status"] != "") {
			$where[] = 	" and (bg_status like '" . $this->inject($params["bg_status"]) . "')  ";
		}

		if ($params["hidden_yn"] == "Y") {
			$where[] = " and bg_hidden = 'N' ";
		}

		//정렬
		$order_by = "aa.bg_idx asc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	//진열관리 조회
	function getBannerGroup($where)
	{
		return $this->db_row("st_banner_group", $where);
	}

	//진열관리  저장
	function insertBannerGroup($data)
	{
		$result = $this->db_insert("st_banner_group", $data);
		return $result;
	}

	//진열관리  수정
	function updateBannerGroup($data, $where)
	{
		return $this->db_update("st_banner_group", $data, $where);
	}

	//진열관리  삭제
	function deleteBannerGroup($where)
	{
		return $this->db_delete("st_banner_group", $where);
	}

	//진열관리 상품 리스트 조회
	function getBannerList($where)
	{
		return $this->db_list(
			"st_banner aa ",
			$where,
			"*,(case when (bn_period_type = 'A' or (date_format(bn_sdate,'%Y%m%d') <= date_format(now(),'%Y%m%d') and date_format(bn_edate,'%Y%m%d') >= date_format(now(),'%Y%m%d') ))then '진행중' else '-' end)  as bn_ing",
			["bn_sort" => "desc", "bn_idx" => "desc"]
		);
	}

	//진열관리 조회
	function getBanner($where)
	{
		return $this->db_row("st_banner", $where);
	}

	//진열관리  저장
	function insertBanner($data)
	{
		$result = $this->db_insert("st_banner", $data);
		return $result;
	}

	//진열관리  수정
	function updateBanner($data, $where)
	{
		return $this->db_update("st_banner", $data, $where);
	}

	//진열관리 상품리스트  삭제
	function deleteBanner($where)
	{
		return $this->db_delete("st_banner", $where);
	}

	//진열관리  저장
	function insertBannerGoods($data)
	{
		$result = $this->db_insert("st_banner_goods", $data);
		return $result;
	}

	//진열관리  수정
	function updateBannerGoods($data, $where)
	{
		return $this->db_update("st_banner_goods", $data, $where);
	}

	//진열관리 상품리스트  삭제
	function deleteBannerGoods($where)
	{
		return $this->db_delete("st_banner_goods", $where);
	}

	######################################
	# END 배너처리
	######################################

	######################################
	# 팝업처리 START
	######################################

	//팝업  리스트
	function getPopupList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
		];

		//바디
		$body = "
			st_popup aa
		";

		//조건
		$where = [];
		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (pp_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "pp_name") {
				$where[] = 	" and (pp_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		if ($params["pp_status"] != "") {
			$where[] = 	" and (pp_status like '" . $this->inject($params["pp_status"]) . "')  ";
		}

		//정렬
		$order_by = "aa.pp_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	//팝업 조회
	function getPopup($where)
	{
		return $this->db_row("st_popup", $where);
	}

	//팝업  저장
	function insertPopup($data)
	{
		$result = $this->db_insert("st_popup", $data);
		return $result;
	}

	//팝업  수정
	function updatePopup($data, $where)
	{
		return $this->db_update("st_popup", $data, $where);
	}

	//팝업  삭제
	function deletePopup($where)
	{
		return $this->db_delete("st_popup", $where);
	}

	######################################
	# END 팝업처리
	######################################

	######################################
	# 팝업처리 START
	######################################

	//팝업  리스트
	function getPushList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
		];

		//바디
		$body = "
			st_push aa
		";

		//조건
		$where = [];
		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (pu_adm_title like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "pp_name") {
				$where[] = 	" and (pu_adm_title like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		//정렬
		$order_by = "aa.pu_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	//팝업 조회
	function getPush($where)
	{
		return $this->db_row("st_push", $where);
	}

	//팝업  저장
	function insertPush($data)
	{
		$result = $this->db_insert("st_push", $data);
		return $result;
	}

	//팝업  수정
	function updatePush($data, $where)
	{
		return $this->db_update("st_push", $data, $where);
	}

	//팝업  삭제
	function deletePush($where)
	{
		return $this->db_delete("st_push", $where);
	}

	######################################
	# END 팝업처리
	######################################

	######################################
	# 일반 페이지 관리 START
	######################################

	//일반 페이지   리스트
	function getPageList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
		];

		//바디
		$body = "
			st_view aa
		";

		//조건
		$where = [];
		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (vw_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "vw_name") {
				$where[] = 	" and (vw_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		if ($params["vw_status"] != "") {
			$where[] = 	" and (vw_status like '" . $this->inject($params["vw_status"]) . "')  ";
		}

		//정렬
		$order_by = "aa.vw_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	//팝업 조회
	function getPage($where)
	{
		return $this->db_row("st_view", $where);
	}

	//일반 페이지   저장
	function insertPage($data)
	{
		//중복체크
		$is_dup = $this->db_is_data("st_view", ["vw_code" => $data["vw_code"]]);

		if ($is_dup) {
			return ['code' => '90', 'msg' => '이미 등록된 코드가 있습니다.', 'key' => ''];
		}

		$result = $this->db_insert("st_view", $data);
		return $result;
	}

	//일반 페이지   수정
	function updatePage($data, $where)
	{
		$is_dup = $this->db_is_data("st_view", ["vw_code" => $data["vw_code"], "vw_idx !=" => $data["vw_idx"]]);

		if ($is_dup) {
			return ['code' => '90', 'msg' => '이미 등록된 코드가 있습니다.', 'key' => ''];
		}

		return $this->db_update("st_view", $data, $where);
	}

	//일반 페이지  삭제
	function deletePage($where)
	{
		return $this->db_delete("st_view", $where);
	}

	######################################
	# END 일반 페이지 관리
	######################################


	######################################
	# 쿠폰관리 START
	######################################

	//쿠폰  리스트
	function getCouponList($params)
	{
		//컬럼
		$cols = [
			"aa.*",


			"(case when aa.cp_auto_yn='Y' then '자동발행' else '수동' end) as cp_auto_type_nm",
			"(case when aa.cp_status='Y' then '사용중' else '중지' end) as cp_status_nm",
			"(select count(*) as cnt FROM st_coupon_list where cp_idx = aa.cp_idx) as cp_cnt",
			"(case when
				(cp_auto_type = 'A' or
					(date_format(cp_auto_sdate,'%Y%m%d') <= date_format(now(),'%Y%m%d') and date_format(cp_auto_edate,'%Y%m%d') >= date_format(now(),'%Y%m%d') ))
				then '자동발행중' else '정지'
			 end)  as cp_auto_ing",
			"(case when
				(cp_use_type = 'A' or
					(date_format(cp_use_sdate,'%Y%m%d') <= date_format(now(),'%Y%m%d') and date_format(cp_use_edate,'%Y%m%d') >= date_format(now(),'%Y%m%d') ))
				then '사용가능' else '사용불가'
			 end)  as cp_use_ing",
		];

		//바디
		$body = "
			st_coupon aa

		";

		//조건
		$where = [];
		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (cp_name like '%" . $this->inject($params["search_value"]) . "%') ";
			} else if ($params["search_type"] == "cp_name") {
				$where[] = 	" and (cp_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		if ($params["cp_status"] != "") {
			$where[] = 	" and (cp_status like '" . $this->inject($params["cp_status"]) . "')  ";
		}


		//정렬
		$order_by = "aa.cp_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	//쿠폰 조회
	function getCoupon($where)
	{
		return $this->db_row("st_coupon", $where);
	}

	//쿠폰  수정
	function updateCoupon($data, $where)
	{
		return $this->db_update("st_coupon", $data, $where);
	}

	//쿠폰  등록
	function insertCoupon($data)
	{
		$result = $this->db_insert("st_coupon", $data);
		return $result;
	}

	//쿠폰확인
	function isCouponUsed($where)
	{
		return $this->db_is_data("st_coupon_list", $where);
	}

	//쿠폰  삭제
	function deleteCoupon($where)
	{
		return $this->db_delete("st_coupon", $where);
	}

	//쿠폰 상세 조회
	function getCouponListInfo($cl_idx)
	{
		//카운터쿼리 생성
		$sql_data = "
			select aa.*
				,bb.mm_id
				,bb.mm_point
				,bb.mm_name
				FROM st_coupon_list aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
			where cl_idx = '" . $this->inject($cl_idx) . "'
		";

		$result = $this->db->query($sql_data);
		return $result->row_array();
	}

	//쿠폰  수정
	function updateCouponList($data, $where)
	{
		return $this->db_update("st_coupon_list", $data, $where);
	}

	//쿠폰  등록
	function insertCouponList($data)
	{
		$result = $this->db_insert("st_coupon_list", $data);
		return $result;
	}

	//쿠폰 발급  리스트
	function getCouponListList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"bb.mm_id",
			"bb.mm_name",
			"bb.mm_type",
			"bb.mm_comp_name",
			"cc.cp_name",
			"(case when cc.cp_use_type='A' then '제한없음' else '날짜지정' end) as cp_use_type_nm",
		];

		//바디
		$body = "
			st_coupon_list aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
				inner join st_coupon cc
					on aa.cp_idx = cc.cp_idx
		";

		//조건
		$where = [];
		if ($params["search_value"] != "") {
			if ($params["search_type"] == "all") {
				$where[] = 	" and (cp_name like '%" . $this->inject($params["search_value"]) . "%' or mm_id like '%" . $this->inject($params["search_value"]) . "%' or mm_name like '%" . $this->inject($params["search_value"]) . "%') ";
			} else if ($params["search_type"] == "cp_name") {
				$where[] = 	" and (cp_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_id") {
				$where[] = 	" and (mm_id like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_name") {
				$where[] = 	" and (mm_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		if (isset($params["cp_idx"]) && $params["cp_idx"] != "") {
			$where[] = 	" and (aa.cp_idx = '" . $this->inject($params["cp_idx"]) . "')  ";
		}

		if (isset($params["mm_idx"]) && $params["mm_idx"] != "") {
			$where[] = 	" and (aa.mm_idx = '" . $this->inject($params["mm_idx"]) . "')  ";
		}

		if (isset($params["cl_use_yn"]) && $params["cl_use_yn"] != "") {
			if ($params["cl_use_yn"] == "N") {
				$where[] = 	" and (aa.cl_use_yn = '" . $this->inject($params["cl_use_yn"]) . "')  ";
				$where[] = 	" and (aa.cl_use_sdate <=now() and aa.cl_use_edate >=now())  ";
			} else if ($params["cl_use_yn"] == "Y") {
				$where[] = 	" and (aa.cl_use_yn = '" . $this->inject($params["cl_use_yn"]) . "'  ";
				$where[] = 	"  or  aa.cl_use_edate < now())  ";
			}
		}

		if (isset($params["cl_limit_price"]) && $params["cl_limit_price"] != "") {
			$where[] = 	" and (aa.cl_limit_price <= '" . $this->inject($params["cl_limit_price"]) . "')  ";
		}

		//정렬
		$order_by = "aa.cl_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	function getCouponListCount($mm_idx, $limit_price = "", $cl_use_yn = "")
	{
		if ($mm_idx == 0) {
			return 0;
		}

		$sql_data = "
	   		SELECT count(*) as cnt
	   		FROM st_coupon aa
				inner join st_coupon_list bb
			    	on aa.cp_idx = bb.cp_idx
			WHERE bb.mm_idx = '$mm_idx'
   		";

		if ($cl_use_yn != "") {
			$sql_data .= " and bb.cl_use_yn = '$cl_use_yn' ";
			$sql_data .= " and (bb.cl_use_sdate <=now() and bb.cl_use_edate >=now()) ";
		}

		if ($limit_price != "") {
			$sql_data .= " and (bb.cl_limit_price <= '" . $this->inject($limit_price) . "')  ";
		}

		$result = $this->db->query($sql_data);
		$count = $result->row_array();

		if ($count == null) {
			return 0;
		} else {
			return $count["cnt"];
		}
	}

	function getAutoRegistCoupon($mm_idx)
	{

		if ($mm_idx == 0) {
			return;
		}

		$sql_data = "
	   		SELECT aa.* FROM st_coupon aa
				left join st_coupon_list bb
			    	on aa.cp_idx = bb.cp_idx and bb.mm_idx = '$mm_idx'
			WHERE aa.cp_auto_yn = 'Y'
			and bb.cl_idx is null
			order by aa.reg_dt asc
   		";

		$result = $this->db->query($sql_data);
		$target_list = $result->result_array();
		foreach ($target_list as $row) {

			$insert = [
				"cp_idx" => $row["cp_idx"],
				"mm_idx" => $mm_idx,
				"cl_use_type" => $row["cp_use_type"],
				"cl_use_sdate" => get_today(),
				"cl_use_day" => $row["cp_use_day"],
				"cl_dan" => $row["cp_dan"],
				"cl_use_edate" => date('Y-m-d', strtotime(get_today() . ' + ' . $row["cp_use_day"] . ' days')) . ' 23:59:59',
				"cl_price" => $row["cp_price"],
				"cl_limit_price" => $row["cp_limit_price"],
				"cl_use_yn" => "N",
				"reg_id" => "system",
			];

			//등록
			$result = $this->insertCouponList($insert);
		}
	}

	######################################
	# END 쿠폰관리
	######################################

	######################################
	# 적립금관리 START
	######################################

	//적립금  리스트
	function getPointList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"
				(case when pt_type = 'JOIN' then '회원가입'
				when pt_type = 'ORDER' then '상품구매'
				when pt_type = 'REVIEW' then '일반리뷰'
				when pt_type = 'PHOTO_REVIEW' then '포토리뷰'
				when pt_type = 'EVENT' then '수동발행'
                when pt_type = 'CHAT' then '채팅사용'
                when pt_type = 'USE' then '사용'
				else '' end) as pt_type_nm
			",
		];

		//바디
		$body = "
			st_point aa
		";

		//조건
		$where = [];
		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (pt_desc like '%" . $this->inject($params["search_value"]) . "%') ";
			} else if ($params["search_type"] == "pt_desc") {
				$where[] = 	" and (pt_desc like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		//정렬
		$order_by = "aa.pt_idx asc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	//적립금 조회
	function getPoint($pt_idx)
	{
		//카운터쿼리 생성
		$sql_data = "
			select *
				,(case when pt_type = 'JOIN' then '회원가입'
				when pt_type = 'ORDER' then '상품구매'
				when pt_type = 'REVIEW' then '일반리뷰'
				when pt_type = 'PHOTO_REVIEW' then '포토리뷰'
				else '' end) as pt_type_nm
			FROM st_point aa
			where pt_idx = '" . $this->inject($pt_idx) . "'
		";

		$result = $this->db->query($sql_data);
		return $result->row_array();
	}

	//적립금  수정
	function updatePoint($data, $where)
	{
		return $this->db_update("st_point", $data, $where);
	}

	//적립금  등록
	function insertPointList($data)
	{
		$result = $this->db_insert("st_point_list", $data);
		return $result;
	}

	//적립금 상세 조회
	function getPointListInfo($pl_idx)
	{

		//카운터쿼리 생성
		$sql_data = "
			select aa.*
				,bb.mm_id
				,bb.mm_point
				,bb.mm_name
				FROM st_point_list aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
			where pl_idx = '" . $this->inject($pl_idx) . "'
		";

		$result = $this->db->query($sql_data);
		return $result->row_array();
	}



	//적립금  리스트
	function getPointListList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"
				(case when pt_type = 'ORDER' then '결제충전'
				when pt_type = 'PROMOTION' then '프로모션구매'
				when pt_type = 'EVENT' then '수동발행'
                when pt_type = 'CHAT' then '채팅사용'
                when pt_type = 'USE' then '사용'
                when pt_type = 'JOIN' then '회원가입'
				else '' end) as pt_type_nm
			",
			"bb.mm_id",
			"bb.mm_name",
			"bb.mm_type",
			"bb.mm_comp_name",
		];

		//바디
		$body = "
			st_point_list aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
		";

		//조건
		$where = [];
		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (pl_desc like '%" . $this->inject($params["search_value"]) . "%' or mm_id like '%" . $this->inject($params["search_value"]) . "%' or mm_name like '%" . $this->inject($params["search_value"]) . "%') ";
			} else if ($params["search_type"] == "pl_desc") {
				$where[] = 	" and (pl_desc like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_id") {
				$where[] = 	" and (mm_id like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_name") {
				$where[] = 	" and (mm_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "add_sangho") {
				$where[] = 	" and (add_sangho like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		if ($params["pt_type"] != "") {
			$where[] = 	" and (pt_type like '" . $this->inject($params["pt_type"]) . "')  ";
		}

		//정렬
		$order_by = "aa.pl_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	######################################
	# END 적립금관리
	######################################

	######################################
	# 프로모션 START
	######################################
	# 프로모션 등록
	function insertPromotionInfo($data)
	{
		return $this->db_insert("st_promotion", $data);
	}

	# 프로모션 수정
	function updatePromotionInfo($data, $where)
	{
		return $this->db_update("st_promotion", $data, $where);
	}

	# 프로모션 리스트 조회
	function getPromotionList($where)
	{
		return $this->db_list("st_promotion", $where, "*, (select rc_name from st_recruit where rc_idx = st_promotion.rc_idx) as rc_name", ["pm_idx" => "desc"]);
	}

	# 프로모션 조회 ONE
	function getPromotionInfo($where)
	{
		return $this->db_row("st_promotion", $where);
	}

	######################################
	# END 프로모션
	######################################

	######################################
	# 중고상픔 START
	######################################
	# 프로모션 등록
	function insertTrade($data)
	{
		return $this->db_insert("st_trade", $data);
	}

	# 프로모션 수정
	function updateTrade($data, $where)
	{
		return $this->db_update("st_trade", $data, $where);
	}

	# 프로모션 리스트 조회
	function getTradeList($where)
	{
		return $this->db_list("st_trade", $where, "*", ["pm_idx" => "desc"]);
	}

	# 프로모션 조회 ONE
	function getTrade($where)
	{
		return $this->db_row("st_trade", $where);
	}

	# 프로모션 조회 상세
	function getTradeDetail($td_idx)
	{
		$sql = "
			select aa.*
			    ,bb.mm_id,bb.mm_comp_name,bb.mm_type,bb.mm_name,bb.mm_phone,bb.mm_phone,bb.mm_point
			    ,ad.ad_addr
			FROM st_trade aa
			inner join st_member bb
			    on aa.mm_idx = bb.mm_idx
			left join st_address ad
			    on aa.mm_idx = ad.mm_idx and ad.ad_type = 'TRADE' and ad.ad_key = aa.td_idx
			where aa.td_idx = '$td_idx'
			group by aa.td_idx
		";

		$result = $this->db->query($sql);
		return $result->row_array();
	}

	# 중고상품 채팅거래 리스트
	function getTradeChatList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"bb.mm_id,bb.mm_comp_name,bb.mm_type,bb.mm_name,bb.mm_phone",
			"ad.ad_addr",
			"ad_mem.ad_addr as mm_ad_addr",
			"rm.rm_idx",
			"rm.reg_dt as rm_reg_dt",
		];

		$add_addr = "";

		//바디
		$body = "
			st_trade aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
				left join st_address ad
					on aa.mm_idx = ad.mm_idx and ad.ad_type = 'TRADE' and ad.ad_key = aa.td_idx
				inner join st_room rm
					on aa.td_idx = rm.td_idx and rm.rm_type = 'trade'
				left join st_address ad_mem
					on rm.mm_idx = ad_mem.mm_idx and ad_mem.ad_type = 'MEMBER' and ad_mem.ad_key = rm.mm_idx

		";

		# 조건
		$where = [];
		$where[] = 	" and rm.td_idx = '" . $this->inject($params["td_idx"]) . "' ";

		//정렬
		$order_by = "rm.rm_idx desc";
		$group_by = "group by aa.td_idx,rm.rm_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 프로모션 뷰처리
	function insertMemberView($data)
	{
		$sql_data = "
      select count(*) as cnt
      FROM st_member_view
      where mv_type = '" . $data['mv_type'] . "'
      and   mv_key = '" . $data['mv_key'] . "'
      and   mm_idx = '" . $this->mm_idx . "'
    ";
		//echo $sql_data;
		//exit;
		$result_cnt = $this->db->query($sql_data);
		$count =  $result_cnt->row_array();

		//echo "count:<".$count["cnt"].">";

		if ($count["cnt"] == 0) {
			$sql_ins = "
        insert into st_member_view
        (mv_type, mv_key, mm_idx, reg_dt)
        values
        ('" . $data['mv_type'] . "','" . $data['mv_key'] . "','" . $this->mm_idx . "',now())
      ";
			//echo $sql_ins;
			//exit;
			$result_ins = $this->db->query($sql_ins);
			return $result_ins;

			//echo $this->db_insert("st_member_view",$data);
			//exit;
			//return $this->db_insert("st_member_view",$data);
		} else {
			$sql_upd = "
        update st_member_view
          set reg_dt = now()
        where mv_type = '" . $data['mv_type'] . "'
        and   mv_key  = '" . $data['mv_key'] . "'
        and   mm_idx  = '" . $this->mm_idx . "'
      ";
			$result_upd = $this->db->query($sql_upd);
			return $result_upd;

			//$data["reg_dt"] = date("Y-m-d H:i:s"); //get_today();
			//return $this->db_update("st_member_view",$data);
		}

		// if($this->db_is_data("st_member_view",$data)==false){
		// 	return $this->db_insert("st_member_view",$data);
		// }else{
		// 	$data["reg_dt"] = date("Y-m-d H:i:s"); //get_today();
		// 	return $this->db_update("st_member_view",$data);
		// }
	}

	# 프로모션 뷰 리스트 10개까지
	function getMemberViewList($type, $mm_idx)
	{
		if ($type == "trade") {
			$sql_data = "
				select aa.*
				FROM st_trade aa
					inner join st_member_view bb
						on aa.td_idx = bb.mv_key
				where bb.mm_idx = '$mm_idx'
				AND bb.mv_type = 'trade'
			";

			$result = $this->db->query($sql_data);
			return $result->result_array();
		} else if ($type == "resume") {
			$sql_data = "
				select aa.*
					,cc.mm_nation
					,cc.mm_name
          ,cc.mm_birth
          ,cc.mm_sex
					,dd.ma_sex
					,dd.ma_age
					,ad.ad_addr
          ,cc.mm_thumb          
				FROM st_resume aa
					inner join st_member_view bb
						on aa.ru_idx = bb.mv_key
					inner join st_member cc
						on aa.mm_idx = cc.mm_idx
					inner join st_member_info dd
						on cc.mm_idx = dd.mm_idx
					left join st_address ad
						on cc.mm_idx = ad.mm_idx and ad.ad_type = 'RESUME' and ad.ad_key = aa.ru_idx
				where bb.mm_idx = '$mm_idx'
				AND bb.mv_type = 'resume'
        GROUP BY aa.ru_idx
			  ORDER BY bb.reg_dt DESC ";

			$result = $this->db->query($sql_data);
			return $result->result_array();
		} else if ($type == "recruit") {
			$sql_data = "
				select aa.*
					,ad.ad_addr
          ,(case
      				when rc_nation = '내국인,외국인' then '국적무관'
      				when rc_nation = '내국인' then '내국인만'
      				when rc_nation = '외국인' then '외국인만'
      				else '국적무관' end) as rc_nation_nm
          ,(case
      				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
      				when rc_sage <> '' then concat(rc_sage, '세 이상')
      				when rc_eage <> '' then concat(rc_eage, '세 이하')
      				else '나이무관' end) as rc_age_nm
				FROM st_recruit aa
					inner join st_member_view bb
						on aa.rc_idx = bb.mv_key
					left join st_address ad
						on aa.mm_idx = ad.mm_idx and ad.ad_type = 'RECRUIT' and ad.ad_key = aa.rc_idx
				where bb.mm_idx = '$mm_idx'
				AND bb.mv_type = 'recruit'
        AND aa.rc_show_yn = 'Y'
        ORDER BY bb.reg_dt desc
        limit 10
			";

			$result = $this->db->query($sql_data);
			return $result->result_array();
		}
	}

	# 중고상품 즐겨찾기 리스트
	function getTradeFavList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"bb.mm_id,bb.mm_comp_name,bb.mm_type,bb.mm_name,bb.mm_phone",
			"ad.ad_addr",
			"ad_mem.ad_addr as mm_ad_addr",
			"bk.bk_idx",
			"bk.reg_dt as bk_reg_dt",
		];

		$add_addr = "";

		//바디
		$body = "
			st_bookmark bk
			inner join st_trade aa
				on aa.td_idx = bk.bk_key and bk.bk_type = 'TRADE'
			inner join st_member bb
					on aa.mm_idx = bb.mm_idx
			left join st_address ad
				on aa.mm_idx = ad.mm_idx and ad.ad_type = 'TRADE' and ad.ad_key = aa.td_idx
			left join st_address ad_mem
				on bk.mm_idx = ad_mem.mm_idx and ad_mem.ad_type = 'MEMBER' and ad_mem.ad_key = bk.mm_idx

		";

		# 조건
		$where = [];
		$where[] = 	" and bk.bk_key = '" . $this->inject($params["td_idx"]) . "' ";

		//정렬
		$order_by = "bk.bk_idx desc";
		$group_by = "group by aa.td_idx,bk.bk_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	function getTradeRecoList($td_idx, $mm_idx)
	{
		$sql_data = "
			select *
				,group_concat(bb.ad_addr) as ad_addr
				,(case when cc.bk_idx is not null then 'Y' else 'N' end) as book_yn
			FROM st_trade aa
				left join st_bookmark cc
					on aa.td_idx = cc.bk_key and cc.bk_type = 'TRADE' and cc.mm_idx = '$mm_idx'
				left join st_address bb
					on aa.td_idx = bb.ad_key and bb.ad_type = 'TRADE'
			where aa.td_idx !='$td_idx'
			group by aa.td_idx
			order by aa.td_idx desc
			limit 0,10;
		";

		$result = $this->db->query($sql_data);
		return $result->result_array();
	}

	function getTradeSearchParamsList($page_size = 20)
	{
		$data = [];

		$mm_idx = $this->user->getUserIdx();
		$page = input_post("page", 1);
		$text = input_post("text", "");
		$skm = input_post("skm", "");
		$ekm = input_post("ekm", "");
		$category = input_post("category", "");
		$sprice = input_post("sprice", "");
		$eprice = input_post("eprice", "");
		$save_yn = input_post("save_yn", "N");

		$addr_info = $this->user->getAddrInfo();

		# 저장인경우 파라미터 그대로 사용하고 아니면 기존 검색 정보 가져와 설정한다.
		if ($save_yn == "N") {
			$site_log = $this->site_model->getSiteLog([
				"session_id" => session_id(),
				"sl_type" => "SEARCH_TRADE",
				"sl_step" => "검색"
			]);

			if ($site_log == null) {
				if ($this->user->isLogin()) {
					$site_log = $this->site_model->getSiteLog([
						"mm_idx" => $mm_idx,
						"sl_type" => "SEARCH_TRADE",
						"sl_step" => "검색"
					]);
				}
			}

			if ($site_log != null) {
				$json_info = json_decode($site_log["sl_txt"], true);
				$text = $json_info["text"];
				$skm = $json_info["skm"];
				$ekm = $json_info["ekm"];
				$category = $json_info["category"];
				$sprice = $json_info["sprice"];
				$eprice = $json_info["eprice"];
			}
		}

		$params = [
			"pagesize" => $page_size,
			"page" => $page,
			"gps_x" => $addr_info["ad_gps_x"],
			"gps_y" => $addr_info["ad_gps_y"],
			"text" => $text,
			"mm_idx" => $mm_idx,
			"skm" => $skm,
			"ekm" => $ekm,
			"category" => $category,
			"sprice" => $sprice,
			"eprice" => $eprice,
		];

		$result = $this->getTradeSearchList($params);
		$list = $result["list"];

		foreach ($list as $key => $row) {
			$list[$key]["td_img"] = getTradeImg($row);
			$list[$key]["reg_dt"] = get_yyyymmdd($row["reg_dt"]);
		}

		# 검색어 저장
		#로그 생성
		$search_txt = [
			"text" => $text,
			"skm" => $skm,
			"ekm" => $ekm,
			"category" => $category,
			"sprice" => $sprice,
			"eprice" => $eprice,
		];

		if ($this->user->isLogin()) {
			$this->site_model->deleteSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "SEARCH_TRADE",
					"sl_step" => "검색"
				]
			);

			$this->site_model->deleteSiteLog(
				[
					"mm_idx" => $mm_idx,
					"sl_type" => "SEARCH_TRADE",
					"sl_step" => "검색"
				]
			);
		} else {
			$this->site_model->deleteSiteLog(
				[
					"session_id" => session_id(),
					"sl_type" => "SEARCH_TRADE",
					"sl_step" => "검색"
				]
			);
		}

		$this->site_model->setSiteLog(
			[
				"session_id" => session_id(),
				"mm_idx" => $mm_idx,
				"sl_type" => "SEARCH_TRADE",
				"sl_step" => "검색",
				"sl_txt" => json_encode($search_txt, JSON_UNESCAPED_UNICODE)
			]
		);

		//리턴 설정
		$data = [
			"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
			"result_list" => $list,
			"result_count" => $result["count"],
		];

		return $data;
	}

	# 업체 리스트 조회 : 메인화면용
	function getTradeSearchList($params)
	{
		$gps_x = $params["gps_x"];
		$gps_y = $params["gps_y"];

		# 지역검색 제외
		$is_addr = true;
		if ($gps_x == "127.028361548" && $gps_y == "37.496486063" || ($gps_x == "") || ($gps_y == "")) {
			$is_addr = false;
		}

		//컬럼
		$cols = [
			"aa.*",
			"cc.ad_addr",
			"cc.ad_gps_x",
			"cc.ad_gps_y",
			"distance",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		$add_addr = "";

		# 거리 처리
		//if($params["skm"] > 0){
		//	$add_addr .= " and ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
		//				-radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1) >= " . $params["skm"];
		//}

		if ($params["ekm"] > 0 && $is_addr == true) {
			$add_addr .= " and ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
						-radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1) <= " . $params["ekm"];
		}

		//바디
		$body = "
			st_trade aa
			inner join (
				SELECT *
					,ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
						-radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1)
					AS distance
				FROM st_address
				where 1=1
				$add_addr
			) cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'TRADE' and cc.ad_key = aa.td_idx
			left join st_bookmark dd
				on aa.td_idx = dd.bk_key and dd.bk_type = 'TRADE' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		";

		# 조건
		$where = [];
		$where[] = 	" and aa.td_status = 'Y' ";


		# 검색어
		if ($params["text"] != "") {
			$where[] = 	" and (aa.td_name like '%" . $this->inject($params["text"]) . "%')";
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAnd($params["category"], "aa.td_category");
			$where[] = $cate_where;
		}

		# 금액
		if ($params["sprice"] != "") {
			$sprice = $params["sprice"] * 10000;
			$where[] = 	" and (aa.td_price >= '" . $this->inject($sprice) . "')";
		}

		# 금액
		if ($params["eprice"] != "") {
			$eprice = $params["eprice"] * 10000;
			$where[] = 	" and (aa.td_price <= '" . $this->inject($eprice) . "')";
		}

		//정렬
		$order_by = "distance asc";
		$group_by = "group by aa.td_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 업체 리스트 조회 : 메인화면용
	function getTradeWebSearchList($params)
	{
		$gps_x = $params["gps_x"];
		$gps_y = $params["gps_y"];

		//컬럼
		$cols = [
			"aa.*",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		$add_addr = "";

		# 거리 처리
		$search_addr = "";
		if ($params["addr1"] != "") {
			$search_addr .= $params["addr1"];
		}

		if ($params["addr2"] != "") {
			$search_addr .= " " . $params["addr2"];
		}

		if ($params["addr3"] != "") {
			$search_addr .= " " . $params["addr3"];
		}

		//바디
		$body = "
			(
                select aa.*
                    ,(select count(*) as cnt FROM st_bookmark where bk_type = 'TRADE' and bk_key = aa.td_idx) as book_cnt
                    ,cc.ad_addr
                    ,cc.ad_gps_x
                    ,cc.ad_gps_y
                FROM st_trade aa
					inner join st_address cc
						on aa.mm_idx = cc.mm_idx and cc.ad_type = 'TRADE' and cc.ad_key = aa.td_idx
            ) aa
			left join st_bookmark dd
				on aa.td_idx = dd.bk_key and dd.bk_type = 'TRADE' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		";

		# 조건
		$where = [];
		$where[] = 	" and aa.td_status = 'Y' ";


		# 검색어
		if ($params["text"] != "") {
			$where[] = 	" and (aa.td_name like '%" . $this->inject($params["text"]) . "%' or ";
			$where[] = 	"      aa.td_category like '%" . $this->inject($params["text"]) . "%' or ";
			$where[] = 	"      aa.td_hash like '%" . $this->inject($params["text"]) . "%' or ";
			$where[] = 	"      aa.ad_addr like '%" . $this->inject($params["text"]) . "%' ";
			$where[] = 	"     ) ";
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAnd($params["category"], "aa.td_category");
			$where[] = $cate_where;
		}

		if ($search_addr != "") {
			$where[] = 	" and (aa.ad_addr like '%" . $search_addr . "%')";
		}

		# 금액
		if ($params["sprice"] != "") {
			$sprice = $params["sprice"] * 10000;
			$where[] = 	" and (aa.td_price >= '" . $this->inject($sprice) . "')";
		}

		# 금액
		if ($params["eprice"] != "") {
			$eprice = $params["eprice"] * 10000;
			$where[] = 	" and (aa.td_price <= '" . $this->inject($eprice) . "')";
		}

		//정렬
		if ($params["order_by"] == "recent") {
			$order_by = "reg_dt desc";
		} else {
			$order_by = "book_cnt desc";
		}
		$group_by = "group by aa.td_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by, false, true);
	}

	# 업체 리스트 조회 : 관리자용
	function getTradeSearchListV2($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"bb.mm_id,bb.mm_comp_name,bb.mm_type,bb.mm_name,bb.mm_phone",
			"ad.ad_addr",
		];

		$add_addr = "";

		//바디
		$body = "
			st_trade aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
				left join st_address ad
					on aa.mm_idx = ad.mm_idx and ad.ad_type = 'TRADE' and ad.ad_key = aa.td_idx

		";

		# 조건
		$where = [];

		$where[] = 	" and td_status in ('Y','H')  ";

		if ($params["search_value"] != "") {
			if ($params["search_type"] == "") {
				$where[] = 	" and (mm_id like '%" . $this->inject($params["search_value"]) . "%'
							  or mm_phone like '%" . $this->inject($params["search_value"]) . "%'
							  or td_name like '%" . $this->inject($params["search_value"]) . "%'
							  or td_hash like '%" . $this->inject($params["search_value"]) . "%'
                              or ad.ad_addr like '%" . $this->inject($params["search_value"]) . "%'
                              or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%'
							  or mm_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_id") {
				$where[] = 	" and (mm_id like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_name") {
				$where[] = 	" and (mm_name like '%" . $this->inject($params["search_value"]) . "%' or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "mm_phone") {
				$where[] = 	" and (mm_phone like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "ad_addr") {
				$where[] = 	" and (ad.ad_addr like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "td_name") {
				$where[] = 	" and (aa.td_name like '%" . $this->inject($params["search_value"]) . "%')  ";
			} else if ($params["search_type"] == "td_hash") {
				$where[] = 	" and (aa.td_hash like '%" . $this->inject($params["search_value"]) . "%')  ";
			}
		}

		if ($params["mm_idx"] != "") {
			$where[] = 	" and (aa.mm_idx = '" . $this->inject($params["mm_idx"]) . "')";
		}

		# 검색어
		if ($params["show_yn"] != "") {
			$where[] = 	" and (aa.td_status = '" . $this->inject($params["show_yn"]) . "')";
		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAnd($params["category"], "aa.td_category");
			$where[] = $cate_where;
		}

		# 금액
		if ($params["sprice"] != "") {
			$sprice = $params["sprice"];
			$where[] = 	" and (aa.td_price >= '" . $this->inject($sprice) . "')";
		}

		# 금액
		if ($params["eprice"] != "") {
			$eprice = $params["eprice"];
			$where[] = 	" and (aa.td_price <= '" . $this->inject($eprice) . "')";
		}

		//정렬
		$order_by = "aa.td_idx desc";
		$group_by = "group by aa.td_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 멀티 조건 만들기
	function getMultiAnd($arry, $col_name)
	{
		$arr_category = explode("^", $arry);

		$where_category = "";
		if (count($arr_category) > 0) {
			$where_category .= " and (";
			foreach ($arr_category as $key => $row) {
				if ($key == 0) {
					$where_category .= " $col_name like '%" . $row . "%' ";
				} else {
					$where_category .= " or $col_name like '%" . $row . "%' ";
				}
			}
			$where_category .= " )";
		}
		return $where_category;
	}

	######################################
	# END 중고상픔
	######################################


	function getMainData()
	{
		//카운터쿼리 생성
		$sql = "
			select aa.date,ifnull(bb.cnt,0) as cnt
			FROM st_calendar aa
			left join (
			    select date_format(o_pay_dt,'%Y%m%d') as ymd
			        ,count(*) as cnt
			    FROM st_order
			    where o_pay_yn = 'Y'
			    and DATE_SUB(now(),INTERVAL 14 DAY) <= o_pay_dt
			    group by date_format(o_pay_dt,'%Y%m%d')
			) bb
			    on aa.yyyymmdd = bb.ymd
			where date_format(DATE_SUB(date_format(now(),'%Y%m%d'),INTERVAL 14 DAY),'%Y%m%d') < aa.yyyymmdd
			and date_format(now(),'%Y%m%d') >= aa.yyyymmdd
			order by aa.yyyymmdd asc
		";

		$result = $this->db->query($sql);
		$order_list = $result->result_array();

		$sql = "
			select aa.date,ifnull(bb.cnt,0) as cnt
			FROM st_calendar aa
			left join (
			    select date_format(reg_dt,'%Y%m%d') as ymd
			        ,count(*) as cnt
			    FROM st_member
			    where  DATE_SUB(now(),INTERVAL 14 DAY) <= reg_dt
			    group by date_format(reg_dt,'%Y%m%d')
			) bb
			    on aa.yyyymmdd = bb.ymd
			where date_format(DATE_SUB(date_format(now(),'%Y%m%d'),INTERVAL 14 DAY),'%Y%m%d') < aa.yyyymmdd
			and date_format(now(),'%Y%m%d') >= aa.yyyymmdd
			order by aa.yyyymmdd asc
		";

		$result = $this->db->query($sql);
		$member_list = $result->result_array();

		$sql = "
			select aa.date,ifnull(bb.cnt,0) as cnt
			FROM st_calendar aa
			left join (
			    select date_format(reg_dt,'%Y%m%d') as ymd
			        ,count(*) as cnt
			    FROM st_recruit
			    where  DATE_SUB(now(),INTERVAL 14 DAY) <= reg_dt
			    group by date_format(reg_dt,'%Y%m%d')
			) bb
			    on aa.yyyymmdd = bb.ymd
			where date_format(DATE_SUB(date_format(now(),'%Y%m%d'),INTERVAL 10 DAY),'%Y%m%d') < aa.yyyymmdd
			and date_format(now(),'%Y%m%d') >= aa.yyyymmdd
			order by aa.yyyymmdd asc
		";

		$result = $this->db->query($sql);
		$recruit_list = $result->result_array();

		$sql = "
			select aa.date,ifnull(bb.cnt,0) as cnt
			FROM st_calendar aa
			left join (
			    select date_format(o_pay_dt,'%Y%m%d') as ymd
			        ,count(*) as cnt
			    FROM st_order
			    where o_pay_yn = 'Y'
			    and DATE_SUB(now(),INTERVAL 14 DAY) <= o_pay_dt
			    group by date_format(o_pay_dt,'%Y%m%d')
			) bb
			    on aa.yyyymmdd = bb.ymd
			where date_format(DATE_SUB(date_format(now(),'%Y%m%d'),INTERVAL 14 DAY),'%Y%m%d') < aa.yyyymmdd
			and date_format(now(),'%Y%m%d') >= aa.yyyymmdd
			order by aa.yyyymmdd asc
		";

		$result = $this->db->query($sql);
		$resume_list = $result->result_array();

		$sql = "
			select *
			FROM st_board
			where tb_id = 'report'
			order by reg_dt desc
			limit 0,5
		";

		$result = $this->db->query($sql);
		$report_list = $result->result_array();

		$sql = "
			select *
			FROM st_board
			where tb_id = 'community'
			order by reg_dt desc
			limit 0,5
		";

		$result = $this->db->query($sql);
		$community_list = $result->result_array();

		return [
			"member_list" => $member_list,
			"recruit_list" => $recruit_list,
			"resume_list" => $resume_list,
			"order_list" => $order_list,
			"report_list" => $report_list,
			"community_list" => $community_list,
		];
	}


	#####################################
	# 푸시 발송 대상자 추출
	#####################################

	# 관리자 발송 대상자
	function getFcmTargetList()
	{
		//카운터쿼리 생성
		$sql_data = "
			select aa.token,bb.alarm_deny_yn,alarm_deny_yoil,alarm_deny_stime,alarm_deny_etime
            FROM st_member_device aa
                inner join st_member bb
                    on aa.mm_idx = bb.mm_idx
            where token !=''
            and bb.alarm_adv = 'Y'
		";

		$result = $this->db->query($sql_data);
		return $result->result_array();
	}

	# 공고 등록 대상자
	function getAlarmTargetRecruit($mm_idx)
	{
		$sql = "
            select mb.mm_idx
                ,mb.mm_id
                ,mv.token
                ,mb.alarm_deny_yn,mb.alarm_deny_yoil,mb.alarm_deny_stime,mb.alarm_deny_etime
            FROM st_bookmark bk
            inner join st_recruit rc
                on bk.bk_key = rc.rc_idx and bk.bk_type = 'RECRUIT'
            inner join st_member mb
                on bk.mm_idx = mb.mm_idx
            left join st_member_device mv
                on mb.mm_idx = mv.mm_idx
            where rc.mm_idx = '$mm_idx'
            and mb.alarm_resume = 'Y'
            group by mb.mm_idx
       ";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 즐겨찾기 대상자
	function getBookmarkUser($data)
	{
		$key = $data["bk_key"];

		$info = [];
		if ($data["bk_type"] == "RECRUIT") {
			$sql_data = "
    			select ifnull(cc.token,'') as token,bb.alarm_deny_yn,bb.alarm_deny_yoil,bb.alarm_deny_stime,bb.alarm_deny_etime,bb.mm_id
    				FROM st_recruit aa
                        inner join st_member bb
                            on aa.mm_idx = bb.mm_idx
                        left join st_member_device cc
                            on bb.mm_idx = cc.mm_idx
    			where aa.rc_idx = '$key'
                and bb.alarm_fav = 'Y'
    		";

			$result = $this->db->query($sql_data);
			$info = $result->row_array();
		} else if ($data["bk_type"] == "RESUME") {
			$sql_data = "
    			select ifnull(cc.token,'') as token,bb.alarm_deny_yn,bb.alarm_deny_yoil,bb.alarm_deny_stime,bb.alarm_deny_etime,bb.mm_id
    				FROM st_resume aa
                        inner join st_member bb
                            on aa.mm_idx = bb.mm_idx
                        left join st_member_device cc
                            on bb.mm_idx = cc.mm_idx
    			where aa.ru_idx = '$key'
                and bb.alarm_fav = 'Y'
    		";

			$result = $this->db->query($sql_data);
			$info = $result->row_array();
		}

		return $info;
	}


	function getPointMemId($mm_id)
	{

		//카운터쿼리 생성
		$sql_data = "
        select bb.mm_id
          ,bb.mm_point
          ,bb.mm_name
          FROM st_member bb
        where mm_id = '" . $this->inject($mm_id) . "'
      ";

		$result = $this->db->query($sql_data);
		return $result->row_array();
	}
}
