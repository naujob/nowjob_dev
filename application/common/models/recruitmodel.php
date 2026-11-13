<?php
class recruitModel extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// 나이를 20, 30, 40, 50, 60, 70으로 정규화
	private function normalizeAge($age) {
		if (empty($age) || $age == 0) return '';
		$age = (int)$age;
		// 1의 자리를 반올림하여 10의 배수로 변환
		// 예: 23 -> 20, 45 -> 50, 40 -> 40
		$normalized = round($age / 10) * 10;
		// 범위 제한: 20~70
		if ($normalized < 20) $normalized = 20;
		if ($normalized > 70) $normalized = 70;
		return (string)$normalized;
	}

	function getRecruit($where)
	{
		return $this->db_row("st_recruit", $where, "*", ["rc_idx" => "desc"]);
	}

	function getRecruitRecoList($params)
	{
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

		$limit = isset($params["limit"]) ? $this->inject($params["limit"]) : "24";

		//컬럼
		$cols = [
			"aa.rc_idx",
			"aa.rc_name",
			"aa.rc_work",
			"aa.rc_work_type",
			"aa.rc_price_type",
			"aa.rc_price_rep",
			"aa.rc_price_pay",
			"aa.ad_addr",
			"aa.rc_img1",
			"aa.rc_hash",
			"(case
        when rc_nation = '내국인,외국인' then '국적무관'
        when rc_nation = '내국인' then '내국인'
        when rc_nation = '외국인' then '외국인'
        else '국적무관' end) as rc_nation_nm",
			"rc_sage",
			"rc_eage",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
		$body = "
			( 
        SELECT aa.mm_id
          ,aa.mm_status
          ,bb.*
          ,(SELECT count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' AND bk_key = bb.rc_idx) as book_cnt
          ,ifnull(min(po.pm_sdate),'2000-01-01 00:00:00') as max_date
          ,cc.ad_jibun AS ad_addr
        FROM st_member aa
        INNER JOIN st_recruit bb
          ON aa.mm_idx = bb.mm_idx
        INNER JOIN st_address cc
          ON aa.mm_idx = cc.mm_idx AND cc.ad_type = 'RECRUIT' AND cc.ad_key = bb.rc_idx
        LEFT JOIN (
					SELECT aa.*
					FROM st_promotion aa
					INNER JOIN (
            SELECT max(pm_idx) as max_idx ,rc_idx
            FROM st_promotion po
            where (po.pm_sdate <= now() AND po.pm_edate >= now()) AND po.pm_code = 'C001'
            group by rc_idx
          ) bb ON aa.pm_idx = max_idx
				) po ON bb.rc_idx = po.rc_idx
    ";

		$body .= " where 1=1 ";

		//추천공고 유료만 노출되도록
		$body .= " AND bb.rc_idx = po.rc_idx ";

		//특정업체의 공고만 조회 (지금뜨는 업체에서 클릭)
		if ($params["comp_idx"] != "") {
			$body .= " AND aa.mm_idx = '" . $params["comp_idx"] . "' ";
		}

		$body .= "
        group by bb.rc_idx
      ) aa
			INNER JOIN st_address cc
				ON aa.mm_idx = cc.mm_idx AND cc.ad_type = 'RECRUIT' AND cc.ad_key = aa.rc_idx
			LEFT JOIN st_bookmark dd
				ON aa.rc_idx = dd.bk_key AND dd.bk_type = 'RECRUIT' AND dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
			LEFT JOIN st_promotion p
				ON aa.rc_idx = p.rc_idx AND p.pm_code = 'C002' AND now() between p.pm_sdate AND p.pm_edate
		";

		//조건
		$where = [];
		$where[] = 	" AND aa.mm_status = 'Y' AND aa.rc_show_yn = 'Y' AND aa.rc_status = 'Y' AND aa.rc_sdate <= now() AND aa.rc_edate >= now() ";

		if ($search_addr != "") {
			$where[] = 	" AND (aa.ad_addr like '%" . $search_addr . "%' or p.pm_addr LIKE '%" . $search_addr . "%')";
		}

		$order_by = "rand() ";
		$group_by = "group by aa.rc_idx ";

		return $this->db_paging_list($cols, $body, $where, $order_by, 1, $limit, $group_by);
	}

	# 채용공고 목록 (실시간 쿼리 버전)
	function getRecruitList($params)
	{


		$search_addr = isset($params["search_addr"]) && is_array($params["search_addr"]) ? array_map([$this, 'inject'], $params["search_addr"]) : [];
		$search_addr_step = isset($params["search_addr_step"]) && is_array($params["search_addr_step"]) ? $params["search_addr_step"] : [];
		$mm_idx = isset($params["mm_idx"]) ? $this->inject($params["mm_idx"]) : 0;

			// 빠른 경로(최신순): 1) 필터와 정렬 키로 이 페이지 rc_idx를 고르고 2) 그 공고만 상세 조회
			// 검색 조건 유무와 무관하게 적용한다 (인기순은 기존 방식)
			$fast_path = (isset($params["order_by"]) ? $params["order_by"] : "recent") == "recent";
			$is_no_condition = $this->_isNoSearchCondition($params);
			$fast_page = max(1, (int) $params["page"]);
			$fast_limit = (isset($params["pagesize"]) && (int) $params["pagesize"] >= 1 && (int) $params["pagesize"] <= 100) ? (int) $params["pagesize"] : 20;
			$fast_seed = isset($params["seed"]) ? (int) $params["seed"] : 0;
			
		// 디버그: 변수값 확인
		// error_log("=== 공고 검색 조건 디버그 ===");
		// error_log("search_addr: " . json_encode($search_addr, JSON_UNESCAPED_UNICODE));
		// error_log("search_addr_step: " . json_encode($search_addr_step, JSON_UNESCAPED_UNICODE));
		// error_log("=== 디버그 끝 ===");
         
	

	

		$cols = [
			"aa.rc_idx",
			"aa.rc_name",
			"aa.rc_work",
			"aa.rc_work_type",
			"aa.rc_price_type",
			"aa.rc_price_rep",
			"aa.rc_price_pay",
			"aa.ad_addr",
			"aa.rc_img1",
			"aa.rc_nation as rc_nation_nm",
			"aa.rc_sage",
			"aa.rc_eage",
			"aa.mm_idx",
			"aa.pm_idx",
			"(CASE WHEN dd.bk_idx IS NOT NULL THEN 'Y' ELSE 'N' END) as book_yn",
			"aa.up_dt"
		];

		// 북마크 수는 인기순 정렬에서만 쓴다 → 최신순이면 공고마다 COUNT 서브쿼리를 돌리지 않는다
		$order_by_param = isset($params["order_by"]) ? $params["order_by"] : "recent";
		$book_cnt_col = ($order_by_param == "recent") ? "0" : "(SELECT COUNT(*) FROM st_bookmark WHERE bk_type = 'RECRUIT' AND bk_key = b.rc_idx)";

		$body = "(
			SELECT
				a.mm_id,
				a.mm_status,
				a.mm_idx,
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
				b.rc_sex,
				CASE
					WHEN b.rc_nation = '내국인,외국인' THEN '국적무관'
					WHEN b.rc_nation = '내국인' THEN '내국인'
					WHEN b.rc_nation = '외국인' THEN '외국인'
					ELSE '국적무관'
				END AS rc_nation,
				b.rc_week,
				b.rc_hash,
				c.ad_jibun AS ad_addr,
				CONCAT(c.ad_doro, ' ', c.ad_jibun) AS s_addr,
				c.ad_gps_x,
				c.ad_gps_y,
				IFNULL(e1.ad_addr, '') as expose_addr1,
				IFNULL(CONCAT(e1.ad_doro, ' ', e1.ad_jibun), '') as expose_s_addr1,
				IFNULL(e2.ad_addr, '') as expose_addr2,
				IFNULL(CONCAT(e2.ad_doro, ' ', e2.ad_jibun), '') as expose_s_addr2,
				{$book_cnt_col} AS book_cnt,
				(SELECT pm_idx FROM st_promotion WHERE pm_status = 'Y' AND rc_idx =b.rc_idx AND pm_code = 'C001' AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx,
				GREATEST(
					CASE WHEN b.up_dt = '0000-00-00 00:00:00' THEN b.reg_dt ELSE b.up_dt END,
					IFNULL((SELECT MAX(up_dt) FROM st_promotion WHERE rc_idx = b.rc_idx AND pm_code IN ('C002','C003') AND pm_sdate <= NOW() AND pm_edate >= NOW()), '2000-01-01 00:00:00')
				) AS up_dt,
				b.reg_dt
			FROM st_member a
			INNER JOIN st_recruit b ON a.mm_idx = b.mm_idx
			INNER JOIN st_address c ON a.mm_idx = c.mm_idx AND c.ad_type = 'RECRUIT' AND c.ad_key = b.rc_idx
			LEFT JOIN st_address e1 ON a.mm_idx = e1.mm_idx AND b.rc_idx = e1.ad_key AND e1.ad_type = 'RECRUIT_EXPOSE_1'
			LEFT JOIN st_address e2 ON a.mm_idx = e2.mm_idx AND b.rc_idx = e2.ad_key AND e2.ad_type = 'RECRUIT_EXPOSE_2'
			WHERE a.mm_status = 'Y'
				/* 게재중 조건을 안쪽에서 먼저 걸러 마감/비노출 공고는 집계·서브쿼리 대상에서 제외 (바깥 WHERE에도 같은 조건 유지) */
				AND b.rc_show_yn = 'Y' AND b.rc_status = 'Y' AND b.rc_sdate <= NOW() AND b.rc_edate >= NOW()
				/*__ONLY_RC__*/
			GROUP BY b.rc_idx
		) aa
		LEFT JOIN st_bookmark dd ON aa.rc_idx = dd.bk_key AND dd.bk_type = 'RECRUIT' AND dd.mm_idx = '{$mm_idx}'";

		# WHERE 조건
		$where = [];
		$where[] = " AND aa.rc_show_yn = 'Y' AND aa.rc_status = 'Y' AND aa.rc_sdate <= NOW() AND aa.rc_edate >= NOW() ";

		# 검색어
		if ($params["text"] != "") {
			$text = $this->inject($params["text"]);
			$where[] = " AND (
				aa.rc_name LIKE '%{$text}%' OR
				aa.rc_nation LIKE '%{$text}%' OR
				aa.rc_sex LIKE '%{$text}%' OR
				aa.rc_work LIKE '%{$text}%' OR
				aa.rc_work_type LIKE '%{$text}%' OR
				aa.rc_price_type LIKE '%{$text}%' OR
				aa.rc_hash LIKE '%{$text}%' OR
				aa.mm_comp_name LIKE '%{$text}%' OR
				aa.s_addr LIKE '%{$text}%' OR
				aa.ad_addr LIKE '%{$text}%' OR
				aa.expose_addr1 LIKE '%{$text}%' OR
				aa.expose_s_addr1 LIKE '%{$text}%' OR
				aa.expose_addr2 LIKE '%{$text}%' OR
				aa.expose_s_addr2 LIKE '%{$text}%'
			)";
		}

		# 나이 교집합
		if ($params["sage"] != "" && $params["eage"] != "") {
			$sage = $this->inject($params["sage"]);
			$eage = $this->inject($params["eage"]);
			$where[] = " AND (
				(aa.rc_sage = 0 AND aa.rc_eage = 0) OR
				(aa.rc_sage <= '{$eage}' AND aa.rc_eage >= '{$sage}')
			)";
		}


		// 공고 주소(st_address) 컬럼은 시도 전체 검색을 시도명 하나로 찾는다(4번째 인자 true).
		// 2026-11-04~05에 공고/이력서 주소의 축약 시도("경기")·시도 누락("군포시 …")을 정식 시도명으로 정리했기 때문에
		// 시군구마다 LIKE를 만들 필요가 없다 (WHERE 15,000자 → 수백 자).
		$all_addr_conditions = array_filter(array(
			$this->make_addr_like_condition("aa.s_addr", $search_addr, $search_addr_step, true),
			$this->make_addr_like_condition("aa.ad_addr", $search_addr, $search_addr_step, true),
			$this->make_addr_like_condition("aa.expose_addr1", $search_addr, $search_addr_step, true),
			$this->make_addr_like_condition("aa.expose_s_addr1", $search_addr, $search_addr_step, true),
			$this->make_addr_like_condition("aa.expose_addr2", $search_addr, $search_addr_step, true),
			$this->make_addr_like_condition("aa.expose_s_addr2", $search_addr, $search_addr_step, true),
		));

		// 활성 광고를 구매한 회원의 광고 주소(st_promotion.pm_addr)가 검색 주소와 맞으면 그 회원의 공고도 포함
		// 광고 주소는 시도 없이 저장되는 경로(이력서 주소 축약값)가 있어 기존 방식(시군구별 매칭)을 유지한다
		$promo_addr_condition = $this->make_addr_like_condition("pm.pm_addr", $search_addr, $search_addr_step);
		if ($promo_addr_condition != "") {
			$all_addr_conditions[] = "EXISTS (SELECT 1 FROM st_promotion pm WHERE pm.mm_idx = aa.mm_idx AND pm.pm_status = 'Y' AND NOW() BETWEEN pm.pm_sdate AND pm.pm_edate AND (" . $promo_addr_condition . "))";
		}

		if (!empty($all_addr_conditions)) {

			$where[] = " AND (" . implode(" OR ", $all_addr_conditions) . ") ";
		} else {

		}

		# 카테고리
		if ($params["category"] != "") {
			$cate_where = $this->getMultiAndAll($params["category"], "aa.rc_work");
			$where[] = $cate_where;
		}

		# 근무유형
		if ($params["work_type"] != "") {
			$work_type_where = $this->getMultiAndAll($params["work_type"], "aa.rc_work_type");
			$where[] = $work_type_where;
		}

		# 요일
		if ($params["week"] != "") {
			$week_where = $this->getMultiAndAll($params["week"], "aa.rc_week");
			$where[] = $week_where;
		}

		# 급여유형
		if ($params["price_type"] != "") {
			$price_type_where = $this->getMultiAndAll($params["price_type"], "aa.rc_price_type");
			$where[] = $price_type_where;
		}

		# 성별
		if ($params["sex"] != "") {
			$sex_where = $this->getMultiAndAll($params["sex"], "aa.rc_sex");
			$where[] = $sex_where;
		}

		# 국적 (국적무관 공고는 항상 포함, 둘 다 선택하면 조건 없음)
		if (isset($params["nationality"]) && $params["nationality"] != "") {
			$nations = array_values(array_intersect(array('내국인', '외국인'), explode("^", $params["nationality"])));
			if (count($nations) == 1) {
				$where[] = " AND aa.rc_nation IN ('국적무관', '{$nations[0]}') ";
			}
		}

		# 회사 지정
		if ($params["compidx"] != "") {
			$compidx = $this->inject($params["compidx"]);
			$where[] = " AND aa.mm_idx = '{$compidx}' ";
		}

		# 정렬 (유료공고 → 최신순, 유료공고 그룹 내부는 seed 기반 해시 순서)
		# RAND() 대신 CRC32(rc_idx + seed)를 쓴다: 같은 seed면 페이지마다 순서가 같아 중복/누락이 없고,
		# seed가 바뀌면(새 진입) 순서가 섞인다. rc_idx는 동점 정렬을 고정하는 보조 키다.
		$seed = isset($params["seed"]) ? (int) $params["seed"] : 0;
		$paid_shuffle = "(CASE WHEN aa.pm_idx IS NOT NULL THEN CRC32(CONCAT(aa.rc_idx, ':', {$seed})) ELSE 0 END)";
		if ($params["order_by"] == "recent") {
			$order_by = "(aa.pm_idx IS NULL) ASC, {$paid_shuffle}, aa.up_dt DESC, aa.rc_idx DESC";
		} else {
			$order_by = "(aa.pm_idx IS NULL) ASC, {$paid_shuffle}, aa.book_cnt DESC, aa.up_dt DESC, aa.rc_idx DESC";
		}

		$group_by = ""; // 서브쿼리에서 이미 GROUP BY b.rc_idx 처리됨

	
		foreach ($where as $idx => $w) {
			
		}

		try {
			// 최종 WHERE 조건 로깅
			$where_str = implode(" ", $where);

			// db_paging_list(core)는 페이지마다 COUNT를 다시 돌리므로, 2페이지부터 개수를 재사용하는 자체 함수로 조회한다
			if ($fast_path) {
					// 1단계: 필터 + 정렬 키로 이 페이지 rc_idx만 선별 (건수도 가벼운 임시 테이블로 셈)
										$fast_count = $is_no_condition ? $this->getRecruitActiveTotalCnt() : $this->_countRecruitLight($where_str, $fast_page);
															$fast_ids = $this->_getRecruitPageIdsFast($where_str, $fast_page, $fast_limit, $fast_seed);
										if (empty($fast_ids)) {
						return $this->_buildFastResult($fast_count, array(), $fast_page, $fast_limit);
					}
					$id_list = array();
					foreach ($fast_ids as $fast_id) {
						$id_list[] = (int) $fast_id;
					}
					// 2단계: 선별된 공고만 원래 정렬 기준으로 상세 조회
					$fast_list = $this->db->query(
						"SELECT " . implode(",\n", $cols) . " FROM " . str_replace("/*__ONLY_RC__*/", " AND b.rc_idx IN (" . implode(",", $id_list) . ") ", $body) . " WHERE 1=1 " . $where_str
						. " ORDER BY " . $order_by . " LIMIT 0, " . count($fast_ids)
					)->result_array();
										return $this->_buildFastResult($fast_count, $fast_list, $fast_page, $fast_limit);
				}
				$result = $this->_pagingListWithCountCache($cols, str_replace("/*__ONLY_RC__*/", "", $body),$where_str, $order_by, $params["page"], $params["pagesize"]);

			return $result;
		} catch (Exception $e) {
			
			throw $e;
		}
	}

	// 조건 없음 판별: 검색어·주소·필터·회사지정이 모두 비었고 정렬이 최신순이면 true (빠른 경로 대상)
	// 조건이 하나라도 있으면 false → 기존 쿼리 사용
	private function _isNoSearchCondition($params)
	{
		$keys = array("text", "category", "work_type", "week", "price_type", "sex", "nationality", "sage", "eage", "compidx");
		foreach ($keys as $key) {
			if (!isset($params[$key])) {
				continue;
			}
			// 배열로 넘어온 값(category[] 등)은 항목이 있으면 조건이 있는 것으로 본다
			if (is_array($params[$key])) {
				if (count($params[$key]) > 0) {
					return false;
				}
			} elseif ((string) $params[$key] !== "") {
				return false;
			}
		}
		if (!empty($params["search_addr"])) {
			return false;
		}
		$order_by = isset($params["order_by"]) ? $params["order_by"] : "recent";
		return ($order_by == "recent");
	}

	// 빠른 경로 1단계: 정렬에 필요한 값(유료 여부, 끌어올림 시각)만 계산해 이 페이지의 rc_idx를 고른다
	// 주소 3조인·북마크·노출주소 컬럼을 빼서 임시 테이블이 rc_idx 중심으로 가볍다
	// 정렬식은 getRecruitList의 최신순 ORDER BY와 같아서, 선별 결과가 기존 순서와 같다
	private function _getRecruitPageIdsFast($where_str, $page, $limit, $seed)
	{
		$start = ($page - 1) * $limit;
		$sql = "SELECT aa.rc_idx,
				pm1.sort_pm_idx,
				GREATEST(
					CASE WHEN aa.rc_up_dt = '0000-00-00 00:00:00' THEN aa.reg_dt ELSE aa.rc_up_dt END,
					IFNULL(pm2.sort_up_dt, '2000-01-01 00:00:00')
				) AS sort_up_dt
			FROM " . $this->_recruitLightBody() . "
			LEFT JOIN (
				SELECT rc_idx, MIN(pm_idx) AS sort_pm_idx
				FROM st_promotion
				WHERE pm_status = 'Y' AND pm_code = 'C001' AND pm_sdate <= NOW() AND pm_edate >= NOW()
				GROUP BY rc_idx
			) pm1 ON pm1.rc_idx = aa.rc_idx
			LEFT JOIN (
				SELECT rc_idx, MAX(up_dt) AS sort_up_dt
				FROM st_promotion
				WHERE pm_code IN ('C002','C003') AND pm_sdate <= NOW() AND pm_edate >= NOW()
				GROUP BY rc_idx
			) pm2 ON pm2.rc_idx = aa.rc_idx

			WHERE 1=1 " . $where_str . "
			ORDER BY (sort_pm_idx IS NULL) ASC,
				(CASE WHEN sort_pm_idx IS NOT NULL THEN CRC32(CONCAT(aa.rc_idx, ':', {$seed})) ELSE 0 END),
				sort_up_dt DESC, aa.rc_idx DESC
			LIMIT {$start}, {$limit}";

		$rows = $this->db->query($sql)->result_array();
		$ids = array();
		foreach ($rows as $row) {
			$ids[] = (int) $row["rc_idx"];
		}
		return $ids;
	}

	// 빠른 경로용 가벼운 공고 임시 테이블 (alias aa)
	// 검색 조건(필터)에 쓰는 컬럼만 담는다. 북마크 조인과 정렬용 서브쿼리는 넣지 않는다
	// 정렬 서브쿼리는 필터를 통과한 행에만 실행되도록 바깥 쿼리에서 계산한다
	private function _recruitLightBody()
	{
		return "(
			SELECT
				a.mm_idx,
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
				b.rc_sex,
				b.rc_week,
				b.rc_hash,
				b.up_dt AS rc_up_dt,
				b.reg_dt,
				CASE
					WHEN b.rc_nation = '내국인,외국인' THEN '국적무관'
					WHEN b.rc_nation = '내국인' THEN '내국인'
					WHEN b.rc_nation = '외국인' THEN '외국인'
					ELSE '국적무관'
				END AS rc_nation,
				c.ad_jibun AS ad_addr,
				CONCAT(c.ad_doro, ' ', c.ad_jibun) AS s_addr,
				IFNULL(e1.ad_addr, '') AS expose_addr1,
				IFNULL(CONCAT(e1.ad_doro, ' ', e1.ad_jibun), '') AS expose_s_addr1,
				IFNULL(e2.ad_addr, '') AS expose_addr2,
				IFNULL(CONCAT(e2.ad_doro, ' ', e2.ad_jibun), '') AS expose_s_addr2
			FROM st_member a
			INNER JOIN st_recruit b ON a.mm_idx = b.mm_idx
			INNER JOIN st_address c ON a.mm_idx = c.mm_idx AND c.ad_type = 'RECRUIT' AND c.ad_key = b.rc_idx
			LEFT JOIN st_address e1 ON a.mm_idx = e1.mm_idx AND b.rc_idx = e1.ad_key AND e1.ad_type = 'RECRUIT_EXPOSE_1'
			LEFT JOIN st_address e2 ON a.mm_idx = e2.mm_idx AND b.rc_idx = e2.ad_key AND e2.ad_type = 'RECRUIT_EXPOSE_2'
			WHERE a.mm_status = 'Y'
				AND b.rc_show_yn = 'Y' AND b.rc_status = 'Y' AND b.rc_sdate <= NOW() AND b.rc_edate >= NOW()
			GROUP BY b.rc_idx
		) aa";
	}

	// 빠른 경로 건수: 검색 조건이 있을 때 가벼운 임시 테이블로 센다 (2페이지부터는 세션에 저장된 개수를 재사용)
	private function _countRecruitLight($where_str, $page)
	{
		$query_where = " WHERE 1=1 " . $where_str;
		$cache_key = md5($query_where);
		$count = null;
		if ($page > 1 && isset($_SESSION["RECRUIT_LIST_COUNT"]) && is_array($_SESSION["RECRUIT_LIST_COUNT"])) {
			$cached = $_SESSION["RECRUIT_LIST_COUNT"];
			if (isset($cached["key"], $cached["count"], $cached["time"])
				&& $cached["key"] === $cache_key
				&& (time() - (int) $cached["time"]) < self::RECRUIT_COUNT_CACHE_SEC) {
				$count = (int) $cached["count"];
			}
		}
		if ($count === null) {
			$row = $this->db->query("SELECT COUNT(*) AS cnt FROM " . $this->_recruitLightBody() . $query_where)->row_array();
			$count = ($row == null) ? 0 : (int) $row["cnt"];
			$_SESSION["RECRUIT_LIST_COUNT"] = array("key" => $cache_key, "count" => $count, "time" => time());
		}
		return $count;
	}

	// 빠른 경로 결과를 기존 _pagingListWithCountCache와 같은 형식으로 만든다
	private function _buildFastResult($count, $list, $page, $limit)
	{
		return array(
			"count" => (int) $count,
			"list" => $list,
			"pagenation" => get_page_ajax($page, $count, $limit),
			"page" => $page,
		);
	}

	// 전체 사용자에게 값이 같은 건수를 파일에 잠깐(ttl초) 공유한다. 숫자 하나만 저장하므로 개인정보는 없다
	// 파일명에 DB 이름을 넣어 개발/운영 캐시가 섞이지 않게 한다
	private function _getSharedRecruitCacheFile($name)
	{
		return sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'nowjob_recruit_cnt_' . md5((string) $this->db->database) . '_' . $name . '.json';
	}

	// 공유 캐시에서 값을 읽는다. 없거나 만료되었으면 null
	private function _readSharedRecruitCount($name, $ttl)
	{
		$raw = @file_get_contents($this->_getSharedRecruitCacheFile($name));
		if ($raw === false) {
			return null;
		}
		$cached = json_decode($raw, true);
		if (is_array($cached) && isset($cached["v"], $cached["t"]) && (time() - (int) $cached["t"]) < $ttl) {
			return (int) $cached["v"];
		}
		return null;
	}

	// 공유 캐시에 값을 쓴다 (실패해도 조회 결과에는 영향 없음)
	private function _writeSharedRecruitCount($name, $value)
	{
		@file_put_contents($this->_getSharedRecruitCacheFile($name), json_encode(array("v" => (int) $value, "t" => time())));
	}

	// 채용목록 전체 개수 재사용 시간(초). 이 시간 안에 같은 검색조건으로 2페이지 이상을 보면 COUNT를 다시 세지 않는다
	const RECRUIT_COUNT_CACHE_SEC = 300;

	// 채용목록 페이징 조회 (db_paging_list와 같은 반환 형식: count / list / pagenation / page)
	// db_paging_list(core, 수정 금지)는 페이지마다 COUNT 쿼리를 다시 실행한다.
	// COUNT도 게재중 공고 전체를 펼치는 무거운 쿼리라(측정: 목록 쿼리와 거의 같은 시간) 페이지 이동 비용이 2배가 된다.
	// → 1페이지에서 센 개수를 세션에 검색조건 해시와 함께 저장하고, 같은 조건의 2페이지 이상에서는 재사용한다.
	//   세션에는 해시·개수·시각만 저장한다(검색어/주소 원문은 저장하지 않음).
	//   그사이 공고가 늘거나 줄면 페이지 번호가 잠시 어긋날 수 있으나, 1페이지 진입이나 5분 경과 시 다시 센다.
	// @param array  $cols      SELECT 컬럼 목록
	// @param string $body      FROM 절 (서브쿼리 포함)
	// @param string $where_str WHERE 1=1 뒤에 붙는 조건 문자열
	// @param string $order_by  ORDER BY 절
	// @param mixed  $page      페이지 번호 (요청값)
	// @param mixed  $limit     페이지당 건수 (요청값)
	// @return array ["count" => int, "list" => array, "pagenation" => string, "page" => int]
	private function _pagingListWithCountCache($cols, $body, $where_str, $order_by, $page, $limit)
	{
		// 요청값은 정수로만 쓴다 (LIMIT 절에 그대로 들어가므로)
		$page = max(1, (int) $page);
		$limit = (int) $limit;
		if ($limit < 1 || $limit > 100) {
			$limit = 20;
		}

		$query_where = " WHERE 1=1 " . $where_str;
		$cache_key = md5($query_where);

		// 2페이지 이상이고, 같은 조건으로 최근에 센 개수가 있으면 재사용
		$count = null;
		if ($page > 1 && isset($_SESSION["RECRUIT_LIST_COUNT"]) && is_array($_SESSION["RECRUIT_LIST_COUNT"])) {
			$cached = $_SESSION["RECRUIT_LIST_COUNT"];
			if (isset($cached["key"], $cached["count"], $cached["time"])
				&& $cached["key"] === $cache_key
				&& (time() - (int) $cached["time"]) < self::RECRUIT_COUNT_CACHE_SEC) {
				$count = (int) $cached["count"];
			}
		}

		if ($count === null) {
			$row = $this->db->query("SELECT COUNT(*) AS cnt FROM " . $body . $query_where)->row_array();
			$count = ($row == null) ? 0 : (int) $row["cnt"];
			$_SESSION["RECRUIT_LIST_COUNT"] = array("key" => $cache_key, "count" => $count, "time" => time());
		}

		$start = ($page - 1) * $limit;
		$list = $this->db->query(
			"SELECT " . implode(",\n", $cols) . " FROM " . $body . $query_where
			. " ORDER BY " . $order_by . " LIMIT " . $start . ", " . $limit
		)->result_array();

		return array(
			"count" => $count,
			"list" => $list,
			"pagenation" => get_page_ajax($page, $count, $limit),
			"page" => $page,
		);
	}

	// location_data 조회 결과 캐시 (요청 단위)
	// make_addr_like_condition은 같은 검색조건으로 컬럼명만 바꿔 7번 호출되는데,
	// 조회 결과는 컬럼과 무관하게 같으므로 재사용한다.
	private $_loc_query_cache = array();

	// location_data 조회 + 캐시. 조회 실패 시 false, 성공 시 결과 배열을 반환한다.
	// (호출부가 "실패"와 "결과 없음"을 다르게 처리하므로 둘을 구분해서 돌려준다)
	private function _locQuery($sql)
	{
		if (array_key_exists($sql, $this->_loc_query_cache)) {
			return $this->_loc_query_cache[$sql];
		}
		try {
			$rows = $this->db->query($sql)->result_array();
		} catch (Exception $e) {
			$rows = false;
		}
		$this->_loc_query_cache[$sql] = $rows;
		return $rows;
	}

	// 주어진 시군구명들 중 "전국에서 그 이름을 쓰는 시도가 하나뿐인" 것만 집합으로 반환.
	// 시군구마다 COUNT 쿼리를 따로 돌리던 것(시도 검색 시 수십 번)을 한 번으로 묶은 것이다.
	private function _getUniqueGugunSet($gugun_names)
	{
		if (empty($gugun_names)) {
			return array();
		}
		$escaped = array();
		foreach (array_unique($gugun_names) as $name) {
			$escaped[] = "'" . addslashes($name) . "'";
		}
		$sql = "SELECT gugun_name FROM location_data
				WHERE step = 2 AND gugun_name IN (" . implode(",", $escaped) . ")
				GROUP BY gugun_name HAVING COUNT(DISTINCT sido_name) = 1";
		$rows = $this->_locQuery($sql);
		$set = array();
		if (is_array($rows)) {
			foreach ($rows as $row) {
				$set[$row['gugun_name']] = true;
			}
		}
		return $set;
	}
	// "시 + 구" 시군구(예: 화성시 만세구)에 속한 읍면동을 "시 + 동" 형태로 찾는 조건 목록을 만든다.
	// 구가 새로 생긴 시는 그 전에 저장된 주소에 구가 없다 ("경기 화성시 남양읍 ..." — 운영 기준 화성시 주소의 약 1/3).
	// "화성시 만세구" 조건만으로는 이런 주소가 빠지므로, 그 구의 동네마다 "화성시 남양읍"으로도 찾는다.
	// 한 단어 시군구(시흥시)는 빈 배열을 돌려준다 (기존 조건으로 충분).
	// @param string $column 조건을 걸 컬럼
	// @param string $sido_name 시도명 원문 (경기도)
	// @param array $gugun_words 시군구명 단어 배열 (["화성시", "만세구"])
	// @return array LIKE 조건 문자열 배열
	private function _getCityDongConditions($column, $sido_name, $gugun_words)
	{
		if (count($gugun_words) < 2) {
			return array();
		}
		$sql = "SELECT DISTINCT b_dong_name FROM location_data
				WHERE step = 3 AND sido_name = '" . addslashes($sido_name) . "'
				AND gugun_name = '" . addslashes(implode(" ", $gugun_words)) . "'";
		$rows = $this->_locQuery($sql);
		if (!is_array($rows)) {
			return array();
		}
		$city = addslashes($gugun_words[0]);
		$conditions = array();
		foreach ($rows as $row) {
			if (trim($row['b_dong_name']) === '') {
				continue;
			}
			$conditions[] = "$column LIKE '%{$city} " . addslashes($row['b_dong_name']) . "%'";
		}
		return $conditions;
	}

    // 사용자가 고른 지역을, 공고 주소를 찾아낼 SQL 조건문으로 바꿔주는 함수
	// @param bool $sido_simple true면 시도 전체(step=1)를 시도명 하나로 찾는다 ("%경기도%").
	//                          주소가 정식 시도명으로 정리된 st_address 컬럼에만 쓴다. 광고 주소(pm_addr)는 false.
	function make_addr_like_condition($column, $addr_array, $step_array = [], $sido_simple = false)
	{
		if (empty($addr_array) || !is_array($addr_array)) {
			return "";
		}

		// 시도 공식명 → 축약명 매핑 (membermodel.php 패턴과 동일)
		$sido_abbr_map = array(
			"서울특별시" => "서울",
			"부산광역시" => "부산",
			"대구광역시" => "대구",
			"인천광역시" => "인천",
			"광주광역시" => "광주",
			"대전광역시" => "대전",
			"울산광역시" => "울산",
			"세종특별자치시" => "세종",
			"경기도" => "경기",
			"강원특별자치도" => "강원",
			"충청북도" => "충북",
			"충청남도" => "충남",
			"전북특별자치도" => "전북",
			"전라남도" => "전남",
			"경상북도" => "경북",
			"경상남도" => "경남",
			"제주특별자치도" => "제주"
		);

		$conditions = [];

		foreach ($addr_array as $idx => $addr) {
			$step = isset($step_array[$idx]) ? intval($step_array[$idx]) : 3;
			$escaped_addr = addslashes($addr);

			// step=1 (시도): 해당 시도의 모든 시군구+읍면동 검색
			if ($step == 1 && $sido_simple) {
				// 정식 시도명 하나로만 찾는다 (축약 시도명 "경기"는 2026-11-04 데이터 정리로 남아 있지 않음)
				$conditions[] = "($column LIKE '%{$escaped_addr}%')";
			}
			else if ($step == 1) {
				$temp_conditions = [];

				// 시도+시군구로 매칭 ("경기도 시흥시")
				// 예전에는 시도의 모든 읍면동마다 "경기도 시흥시 정왕동" LIKE를 따로 만들었다(시도 하나에 수천 개 → 목록 조회가 매우 느림).
				// "시도 시군구 동"을 포함하는 주소는 반드시 "시도 시군구"도 포함하므로 동 단위 조건은 결과에 영향이 없어 제거했다.
				// 동 데이터(step=3)에만 있는 시군구도 빠지지 않도록 step 2·3 양쪽에서 시군구를 모은다.
				$sql = "SELECT DISTINCT gugun_name, step FROM location_data
						WHERE sido_name = '{$escaped_addr}' AND step IN (2, 3)";
				$gugun_result = $this->_locQuery($sql);
				if (is_array($gugun_result) && !empty($gugun_result)) {
					$full_gugun_set = array(); // 시도+시군구 매칭 대상 (step 2·3, 중복 제거)
					$step2_gugun_names = array(); // 시군구 단독 매칭 후보 (기존과 같이 step 2만)
					foreach ($gugun_result as $row) {
						$full_gugun_set[(string) $row['gugun_name']] = true;
						if ((int) $row['step'] === 2) {
							$step2_gugun_names[] = $row['gugun_name'];
						}
					}
					// 유일 시군구 집합을 한 번에 조회 (예전에는 시군구마다 COUNT 쿼리를 돌렸다)
					$unique_guguns = $this->_getUniqueGugunSet($step2_gugun_names);

					foreach (array_keys($full_gugun_set) as $gugun_name) {
						$gugun_name = (string) $gugun_name;
						$full_gugun = addslashes($addr . ' ' . $gugun_name);
						$temp_conditions[] = "$column LIKE '%{$full_gugun}%'";
					}

					// 부분주소 처리: gugun_name만 저장된 경우도 매칭
					// 단, 그 시군구명이 한 시도에만 속할 때만 추가 (cross-region 방지)
					foreach (array_unique($step2_gugun_names) as $gugun_name) {
						if (isset($unique_guguns[$gugun_name])) {
							$temp_conditions[] = "$column LIKE '%" . addslashes($gugun_name) . "%'";
						}
					}
				} else {
					// 시군구 조회 실패/0건이면 시도명 단독 매칭으로 대체
					$temp_conditions[] = "$column LIKE '%{$escaped_addr}%'";
				}

				if (!empty($temp_conditions)) {
					$conditions[] = "(" . implode(" OR ", $temp_conditions) . ")";
				}
			}
			// step=2 (시군구): 해당 시군구의 모든 읍면동 검색 ("시도 시군구" 매칭으로 그 안의 동 주소까지 모두 포함)
			else if ($step == 2) {
				$addr_parts = explode(" ", trim($addr));
				if (count($addr_parts) >= 2) {
					// 시군구명은 시도 뒤의 단어 전부 ("경기도 고양시 덕양구" → "고양시 덕양구")
					// 예전에는 두 번째 단어만 써서 "고양시 덕양구"를 골라도 고양시 전체가 검색됐다.
					$gugun_words = array_slice($addr_parts, 1);
					$gugun_name = implode(" ", $gugun_words);
					$sido = addslashes($addr_parts[0]);
					$gugun = addslashes($gugun_name);
					$temp_conditions = [];

					// 1. 시도+시군구 매칭 (경기도 시흥시 / 경기도 고양시 덕양구)
					// 예전에는 이 앞에 시군구의 모든 읍면동마다 "경기도 시흥시 정왕동" LIKE를 붙였으나,
					// 그 주소들은 모두 이 조건에 이미 걸리므로 결과는 같고 비용만 커서 제거했다.
					$full_gugun = $sido . ' ' . $gugun;
					$temp_conditions[] = "$column LIKE '%{$full_gugun}%'";

					// 2. 부분주소 매칭: gugun_name만 저장된 경우 (시흥시)
					// 단, 그 시군구명이 한 시도에만 속할 때만 추가 (cross-region 방지)
					$unique_guguns = $this->_getUniqueGugunSet(array($gugun_name));
					if (isset($unique_guguns[$gugun_name])) {
						$temp_conditions[] = "$column LIKE '%{$gugun}%'";
					}

					// 3. "시 + 구" 시군구(화성시 만세구)는 구가 생기기 전에 등록된 주소("화성시 남양읍")도 찾는다
					foreach ($this->_getCityDongConditions($column, $addr_parts[0], $gugun_words) as $city_dong_condition) {
						$temp_conditions[] = $city_dong_condition;
					}

					if (!empty($temp_conditions)) {
						$conditions[] = "(" . implode(" OR ", $temp_conditions) . ")";
					}
				}
			}
			// step=3 (읍면동): 읍면동 검색 (여러 형태로 fallback)
			else {
				$addr_parts = explode(" ", trim($addr));
				$temp_conditions = [];

				// 1. 원본 전체 문자열 매칭 (예: "강원특별자치도 강릉시 강동면")
				$temp_conditions[] = "$column LIKE '%{$escaped_addr}%'";

				// 2. 시/도 축약명 버전 (예: "강원 강릉시 강동면")
				if (count($addr_parts) >= 3) {
					// 첫 번째 요소(시/도)를 축약명으로 변환
					$full_sido = addslashes($addr_parts[0]);
					if (isset($sido_abbr_map[$full_sido])) {
						$abbr_sido = $sido_abbr_map[$full_sido];
						// 축약된 시/도 + 나머지 주소 부분
						$rest_addr = implode(" ", array_slice($addr_parts, 1));
						$abbr_addr = addslashes($abbr_sido . ' ' . $rest_addr);
						$temp_conditions[] = "$column LIKE '%{$abbr_addr}%'";
					}
				}

				// 3. 시/도 접두어 없는 "시/군/구 + 읍/면/동" 매칭 (예: "강릉시 강동면")
				if (count($addr_parts) >= 3) {
					// 시/도를 제외한 나머지 부분 (시/군/구 + 읍/면/동)
					$without_sido = implode(" ", array_slice($addr_parts, 1));
					$without_sido_escaped = addslashes($without_sido);
					$temp_conditions[] = "$column LIKE '%{$without_sido_escaped}%'";
				}

				// 4. 읍/면/동 단독 매칭 (예: "강동면")
				// 단, 전국에서 유일한 동명인 경우에만 (cross-region 방지)
				if (count($addr_parts) >= 1) {
					$dong_name = addslashes($addr_parts[count($addr_parts) - 1]);
					$check_sql = "SELECT COUNT(DISTINCT gugun_name) as gugun_count FROM location_data
								  WHERE b_dong_name = '{$dong_name}' AND step = 3";
					$check_result = $this->_locQuery($check_sql);
					// 동명이 1곳의 시/군/구에만 속하면 단독 매칭 추가 (유일함)
					if (is_array($check_result) && !empty($check_result) && $check_result[0]['gugun_count'] == 1) {
						$temp_conditions[] = "$column LIKE '%{$dong_name}%'";
					}
				}

				if (!empty($temp_conditions)) {
					$conditions[] = "(" . implode(" OR ", $temp_conditions) . ")";
				}
			}
		}

		return !empty($conditions) ? " (" . implode(" OR ", $conditions) . ") " : "";
	}

	function getMultiAndAll($arry, $col_name)
	{
		$arr_category = array_filter(explode("^", $arry)); // 빈 값 제거
		$count = count($arr_category);

		$where_category = "";
		if ($count > 0) {
			$where_category .= " AND (";

			// 1. 컬럼이 공란인 경우
			$where_category .= " {$col_name} = '' ";

			// 2. 모든 선택지가 다 포함된 경우도 무조건 포함 (like '%월%' AND ... )
			$all_included = [];
			foreach ($arr_category as $val) {
				$escaped = addslashes(trim($val));
				$all_included[] = "{$col_name} like '%{$escaped}%'";
			}
			$where_category .= " or (" . implode(" AND ", $all_included) . ")";

			// 3. 일부만 포함되더라도 포함
			foreach ($arr_category as $val) {
				$escaped = addslashes(trim($val));
				$where_category .= " or {$col_name} like '%{$escaped}%'";
			}

			$where_category .= " )";
		}

		return $where_category;
	}

	# 공고 조회 : 자세히
	function getRecruitDetail($rc_idx, $mm_idx)
	{
		$sql = "
			select bb.*,
				cc.ad_addr,
				cc.ad_doro,
				cc.ad_jibun,
				cc.ad_detail,
				(case
					when rc_nation = '내국인,외국인' then '국적무관'
					when rc_nation = '내국인' then '내국인'
					when rc_nation = '외국인' then '외국인'
					else '국적무관' end) as rc_nation_nm,
				(case
					when rc_sage <> '' and rc_eage <> '' then concat(rc_sage,' ~ ',rc_eage,'대')
					when rc_sage <> '' then concat(rc_sage, '세 ~')
					when rc_eage <> '' then concat('~ ',rc_eage, '대')
					else '나이무관' end) as rc_age_nm,
        (case
          when rc_sex = '남자,여자' then '성별무관'
          when rc_sex = '남자' then '남자'
          when rc_sex = '여자' then '여자'
          else '성별무관' end) as rc_sex_nm,
				(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn,
				aa.mm_phone,
				aa.mm_comp_name,
        ifnull(bk.cnt,0) as fav_cnt,
        aa.mm_id,
        (SELECT pm_idx FROM st_promotion WHERE pm_status = 'Y' AND rc_idx =bb.rc_idx AND pm_code = 'C001' AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx
			FROM st_member aa
			inner join st_recruit bb
				on aa.mm_idx = bb.mm_idx
			inner join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
			left join st_bookmark dd
				on bb.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '$mm_idx'
            left join (
                select bk_key,count(*) as cnt
                FROM st_bookmark
                where bk_type = 'RECRUIT'
                group by bk_key
            ) bk
                on bb.rc_idx = bk.bk_key
			where bb.rc_idx = '$rc_idx'
		";

		$query = $this->db->query($sql);
		return $query->row_array();
	}

	# 채용공고 목록 (기업별)
	function getCompRecruitList($params)
	{
		$search_addr = "";
		if ($params["addr1"] != "") {
			$search_addr .= $this->inject($params["addr1"]);
		}

		if ($params["addr2"] != "") {
			$search_addr .= " " . $this->inject($params["addr2"]);
		}

		if ($params["addr3"] != "") {
			$search_addr .= " " . $this->inject($params["addr3"]);
		}

		$limit = isset($params["limit"]) ? $this->inject($params["limit"]) : "24";

		$cols = [
			"aa.rc_idx",
			"aa.rc_name",
			"aa.rc_work",
			"aa.rc_work_type",
			"aa.rc_price_type",
			"aa.rc_price_rep",
			"aa.rc_price_pay",
			"aa.ad_addr",
			"aa.rc_img1",
			"aa.rc_hash",
			"(case
        when rc_nation = '내국인,외국인' then '국적무관'
        when rc_nation = '내국인' then '내국인'
        when rc_nation = '외국인' then '외국인'
        else '국적무관' end) as rc_nation_nm",
			"rc_sage",
			"rc_eage",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		$body = "
			(
                select aa.mm_id
                    ,aa.mm_status
                    ,bb.*
                    ,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
                    ,ifnull(min(po.pm_sdate),'2000-01-01 00:00:00') as max_date
                    ,cc.ad_addr
                FROM st_member aa
                inner join st_recruit bb
                    on aa.mm_idx = bb.mm_idx
                inner join st_address cc
                    on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
                left join (
					select aa.*
					FROM st_promotion aa
					inner join (
					    select max(pm_idx) as max_idx
					        ,rc_idx
					    FROM st_promotion po
					    where (po.pm_sdate <= now() and po.pm_edate >= now()) and po.pm_code = 'C001'
					    group by rc_idx
					) bb on aa.pm_idx = max_idx
				) po on bb.rc_idx = po.rc_idx
        ";

		$body .= " where 1=1 ";

		if ($params["comp_idx"] != "") {
			$body .= " and aa.mm_idx = '" . $this->inject($params["comp_idx"]) . "' ";
		}

		$body .= "
                group by bb.rc_idx
            ) aa
			inner join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = aa.rc_idx
			left join st_bookmark dd
				on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
			LEFT JOIN st_promotion p
				ON aa.rc_idx = p.rc_idx AND p.pm_code = 'C002' and now() between p.pm_sdate and p.pm_edate
		";

		//조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";

		if ($params["rc_idx"] != "") {
			$where[] = 	" and aa.rc_idx <> " . $this->inject($params["rc_idx"]) . " ";
		}

		if ($search_addr != "") {
			$where[] = 	" and (aa.ad_addr like '%" . $search_addr . "%' or p.pm_addr LIKE '%" . $search_addr . "%')";
		}

		$order_by = "rand() ";

		$group_by = "group by aa.rc_idx ";

		return $this->db_paging_list($cols, $body, $where, $order_by, 1, $limit, $group_by);
	}

	# 내가본 공고
	function getMemberViewList($mm_idx)
	{
		$sql_data = "
      select aa.*,
        ad.ad_addr,
        (case
          when rc_nation = '내국인,외국인' then '국적무관'
          when rc_nation = '내국인' then '내국인'
          when rc_nation = '외국인' then '외국인'
          else '국적무관' end) as rc_nation_nm,
        (case
          when rc_sage <> '' AND rc_eage <> '' then CONCAT(rc_sage, ' ~ ',rc_eage,'대')
          when rc_sage <> '' then CONCAT(rc_sage, '세 ~')
          when rc_eage <> '' then CONCAT(' ~ ', rc_eage, '대')
          else '나이무관' end) as rc_age_nm
      FROM st_recruit aa
        inner join st_member_view bb
          on aa.rc_idx = bb.mv_key
        left join st_address ad
          on aa.mm_idx = ad.mm_idx and ad.ad_type = 'RECRUIT' and ad.ad_key = aa.rc_idx
      where bb.mm_idx = '$mm_idx'
      AND bb.mv_type = 'recruit'
      AND aa.rc_show_yn = 'Y' AND aa.rc_status = 'Y' AND aa.rc_sdate <= NOW() AND aa.rc_edate >= NOW()
      ORDER BY bb.reg_dt desc
      limit 10
    ";

		$result = $this->db->query($sql_data);
		return $result->result_array();
	}

	# 채용공고 목록 (핀맵용)
	function getPinsMapList($params)
	{
		// 클러스터에서 넘어온 공고 번호 목록: 정수로 캐스팅해 SQL 주입 차단, 0/빈값 제거
		$pins = isset($params["pins"]) ? (array)$params["pins"] : array();
		$pins = array_values(array_filter(array_map('intval', $pins)));
		if (count($pins) == 0) {
			return array("count" => 0, "list" => array(), "pagenation" => "", "page" => 1);
		}
		$inClause = implode(',', $pins);
		$pagesize = count($pins);

		$cols = [
			"aa.rc_idx",
			"aa.rc_name",
			"aa.rc_work",
			"aa.rc_work_type",
			"aa.rc_price_type",
			"aa.rc_price_rep",
			"aa.rc_price_pay",
			"aa.ad_addr",
			"aa.rc_img1",
			"(case
        when rc_nation = '내국인,외국인' then '국적무관'
        when rc_nation = '내국인' then '내국인'
        when rc_nation = '외국인' then '외국인'
        else '국적무관' end) as rc_nation_nm",
			"rc_sage",
			"rc_eage",
			"GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) as up_dt",
			"(SELECT pm_idx FROM st_promotion WHERE pm_status = 'Y' AND rc_idx =aa.rc_idx AND pm_code = 'C001' AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		// 파생테이블 안에서 pins/상태/기간 조건을 먼저 걸어 클러스터 공고 수만큼만 만든다
		// (MariaDB 5.5는 바깥 WHERE를 파생테이블 안으로 전달하지 못해 전체 테이블을 materialize함)
		$body = "
				(
					select aa.mm_id
						,aa.mm_status
						,bb.*
						,cc.ad_addr
						,CONCAT(cc.ad_doro, ' ', cc.ad_jibun) AS s_addr
						,cc.ad_gps_x
						,cc.ad_gps_y
						,aa.mm_comp_num
						,aa.mm_comp_name
					FROM st_member aa
					inner join st_recruit bb
						on aa.mm_idx = bb.mm_idx
					INNER JOIN st_address cc
					  ON aa.mm_idx = cc.mm_idx
					  AND cc.ad_type = 'RECRUIT'
					  AND cc.ad_key = bb.rc_idx
					  AND cc.ad_gps_x IS NOT NULL AND cc.ad_gps_y IS NOT NULL
					  AND cc.ad_gps_x > 1 AND cc.ad_gps_y > 1
					where bb.rc_idx IN (" . $inClause . ")
					  and aa.mm_status = 'Y' and bb.rc_show_yn = 'Y' and bb.rc_status = 'Y'
					  and bb.rc_sdate <= now() and bb.rc_edate >= now()
					group by bb.rc_idx
				) aa
				left join st_bookmark dd
					on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		$body .= " LEFT JOIN st_promotion p
			ON aa.rc_idx = p.rc_idx AND p.pm_code in('C002','C003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate
			";

		# 조건
		$where = []; // 조건은 파생테이블 안에서 이미 적용됨

		$order_by = " GREATEST(case when aa.up_dt = '0000-00-00 00:00:00' then aa.reg_dt else aa.up_dt end, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC ";
		$group_by = "group by aa.rc_idx  ";

		return $this->db_paging_list($cols, $body, $where, $order_by, 1, $pagesize, $group_by, false, true);
	}

	# 마이페이지 북마크 : 채용공고 목록
	function getBookMarkRecruitList($params)
	{

		$cols = [
			"bb.rc_idx",
			"bb.rc_name",
			"bb.rc_work",
			"bb.rc_work_type",
			"bb.rc_price_type",
			"bb.rc_price_rep",
			"bb.rc_price_pay",
			"cc.ad_addr",
			"bb.rc_img1",
			"(case
        when rc_nation = '내국인,외국인' then '국적무관'
        when rc_nation = '내국인' then '내국인'
        when rc_nation = '외국인' then '외국인'
        else '국적무관' end) as rc_nation_nm",
			"rc_sage",
			"rc_eage",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"(SELECT pm_idx FROM st_promotion WHERE pm_status = 'Y' AND rc_idx =bb.rc_idx AND pm_code = 'C001' AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx"
		];

		$body = "
            st_member aa
            inner join st_recruit bb
                on aa.mm_idx = bb.mm_idx
            inner join st_bookmark bk
                on bk.bk_key = bb.rc_idx and bk.bk_type = 'RECRUIT'
            inner join st_member mem
            	on bk.mm_idx = mem.mm_idx
            left join st_address cc
                on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
            left join st_bookmark dd
                on bb.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
	    ";

		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' ";

		if (isset($params["mm_idx"]) && $params["mm_idx"] != "") {
			$where[] = 	" and bk.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		}

		if (isset($params["search_type"]) && isset($params["search_value"])) {
			if ($params["search_value"] != "") {
				if ($params["search_type"] == "") {
					$where[] = 	" and (mem.mm_id like '%" . $this->inject($params["search_value"]) . "%'
				 				  or bb.rc_name like '%" . $this->inject($params["search_value"]) . "%'
	                              or mem.mm_name like '%" . $this->inject($params["search_value"]) . "%') ";
				} else if ($params["search_type"] == "mem_id") {
					$where[] = 	" and (mem.mm_id like '%" . $this->inject($params["search_value"]) . "%')  ";
				} else if ($params["search_type"] == "mem_name") {
					$where[] = 	" and (mem.mm_name like '%" . $this->inject($params["search_value"]) . "%' or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')  ";
				} else if ($params["search_type"] == "rc_name") {
					$where[] = 	" and (bb.rc_name like '%" . $this->inject($params["search_value"]) . "%')  ";
				} else if ($params["search_type"] == "ad_addr") {
					$where[] = 	" and (ad.ad_addr like '%" . $this->inject($params["search_value"]) . "%')  ";
				}
			}
		}

		$order_by = "bk.bk_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	# 마이페이지 북마크 : 제안받은 공고
	function getBookMarkRequestRecruitList($params)
	{

		$cols = [
			"cc.rc_idx",
			"cc.rc_name",
			"cc.rc_work",
			"cc.rc_work_type",
			"cc.rc_price_type",
			"cc.rc_price_rep",
			"cc.rc_price_pay",
			"ee.ad_addr",
			"cc.rc_img1",
			"(case
        when rc_nation = '내국인,외국인' then '국적무관'
        when rc_nation = '내국인' then '내국인'
        when rc_nation = '외국인' then '외국인'
        else '국적무관' end) as rc_nation_nm",
			"rc_sage",
			"rc_eage",
			"(case when ff.bk_idx is not null then 'Y' else 'N' end) as book_yn",
			"(SELECT pm_idx FROM st_promotion WHERE pm_status = 'Y' AND rc_idx =cc.rc_idx AND pm_code = 'C001' AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx"
		];

		$body = "
            st_resume aa
			inner join st_member_request bb
		    	on aa.ru_idx = bb.ru_idx and bb.mq_type = 'RESUME'
		    inner join st_recruit cc
		    	on bb.rc_idx = cc.rc_idx
		    inner join st_member dd
		    	on cc.mm_idx = dd.mm_idx
		    left join st_address ee
		        on dd.mm_idx = ee.mm_idx and ee.ad_type = 'RECRUIT' and ee.ad_key = cc.rc_idx
			left join st_bookmark ff
				on cc.rc_idx = ff.bk_key and ff.bk_type = 'RECRUIT' and ff.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
	    ";

		$where = [];
		$where[] = 	" and dd.mm_status = 'Y' ";

		if (isset($params["mm_idx"]) && $params["mm_idx"] != "") {
			$where[] = 	" and aa.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		}

		if (isset($params["ru_idx"]) && $params["ru_idx"] != "") {
			$where[] = 	" and aa.ru_idx = '" . $this->inject($params["ru_idx"]) . "' ";
		}

		$order_by = "bb.mq_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	# 마이페이지 북마크 : 지원중인 공고
	function getMemRequestRecruitList($params)
	{

		$cols = [
			"bb.mq_idx",  // 지원 요청 인덱스 추가
			"cc.rc_idx",
			"cc.rc_name",
			"cc.rc_work",
			"cc.rc_work_type",
			"cc.rc_price_type",
			"cc.rc_price_rep",
			"cc.rc_price_pay",
			"ee.ad_addr",
			"cc.rc_img1",
			"(case
        when rc_nation = '내국인,외국인' then '국적무관'
        when rc_nation = '내국인' then '내국인'
        when rc_nation = '외국인' then '외국인'
        else '국적무관' end) as rc_nation_nm",
			"rc_sage",
			"rc_eage",
			"(case when ff.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		$body = "
            st_resume aa
			inner join st_member_request bb
		    	on aa.ru_idx = bb.ru_idx and bb.mq_type = 'RECRUIT'
		    inner join st_recruit cc
		    	on bb.rc_idx = cc.rc_idx
		    inner join st_member dd
		    	on cc.mm_idx = dd.mm_idx
		    left join st_address ee
		        on dd.mm_idx = ee.mm_idx and ee.ad_type = 'RECRUIT' and ee.ad_key = cc.rc_idx
			left join st_bookmark ff
				on cc.rc_idx = ff.bk_key and ff.bk_type = 'RECRUIT' and ff.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
	    ";

		$where = [];
		$where[] = 	" and dd.mm_status = 'Y' ";
		// 지원취소된 항목 제외 (mq_status가 'cancel'이 아닌 것만)
		$where[] = 	" and (bb.mq_status IS NULL OR bb.mq_status != 'cancel') ";

		if (isset($params["mm_idx"]) && $params["mm_idx"] != "") {
			$where[] = 	" and aa.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		}

		if (isset($params["rc_idx"]) && $params["rc_idx"] != "") {
			$where[] = 	" and bb.rc_idx = '" . $this->inject($params["rc_idx"]) . "' ";
		}

		if (isset($params["ru_idx"]) && $params["ru_idx"] != "") {
			$where[] = 	" and bb.ru_idx = '" . $this->inject($params["ru_idx"]) . "' ";
		}

		$order_by = "bb.mq_idx desc";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"]);
	}

	# 공고 등록
	function insertRecruit($data)
	{
		return $this->db_insert("st_recruit", $data);
	}

	# 공고 수정
	function updateRecruit($data, $where)
	{
		# 업데이트 자동 추가
		$data["up_dt"] = get_today();
		return $this->db_update("st_recruit", $data, $where);
	}

	# 공고 삭제
	function deleteRecruit($where)
	{
		return $this->db_delete("st_recruit", $where);
	}

	# 공고 카운터
	function getRecruitCount($where)
	{
		return $this->db_count("st_recruit", $where);
	}

	function getRecruitTodayCnt($mm_idx)
	{
		$sql = "
			select count(*) as cnt
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and date_format(reg_dt,'%Y%m%d') = date_format(now(),'%Y%m%d')
		";

		$query = $this->db->query($sql);
		$info = $query->row_array();
		if ($info == null) {
			return 0;
		} else {
			return $info["cnt"];
		}
	}

	# 오늘 등록된 전체 공고 건수 (게재중 기준)
	function getRecruitTodayTotalCnt()
	{
		$cached = $this->_readSharedRecruitCount("today", 60);
		if ($cached !== null) {
			return $cached;
		}
		$value = $this->_queryRecruitTodayTotalCnt();
		$this->_writeSharedRecruitCount("today", $value);
		return $value;
	}

	private function _queryRecruitTodayTotalCnt()
	{
		// 오늘 범위를 reg_dt 그대로 비교 (date_format(reg_dt)처럼 컬럼을 가공하면 매 행 계산 + 인덱스 사용 불가)
		$sql = "
			select count(*) as cnt
			FROM st_recruit aa
			where aa.rc_show_yn = 'Y'
			and aa.rc_status = 'Y'
			and aa.reg_dt >= CURDATE() and aa.reg_dt < CURDATE() + INTERVAL 1 DAY
		";

		$query = $this->db->query($sql);
		$info = $query->row_array();
		if ($info == null) {
			return 0;
		} else {
			return $info["cnt"];
		}
	}

	# 공고 목록에 채팅 배지 정보(chat_rm_idx, chat_type) 추가 (mm_idx 기준 최신 1건 모델)
	# recruit.php(채용목록 첫 화면)와 recruit_proc.php(getRecruitList AJAX)가 공통 사용
	# $mm_type이 'MEM'(로그인 개인회원)이 아니면 배지 없음, 나간 방(exit_yn='Y')도 배지 없음
	function addChatBadgeInfo($list, $mm_type, $mm_idx, $user_id, $user_phone)
	{
		if (empty($list)) {
			return $list;
		}

		// 로그인한 개인회원이 아니면 배지 숨김
		if ($mm_type !== 'MEM') {
			foreach ($list as &$row) {
				$row['chat_rm_idx'] = '';
				$row['chat_type'] = '';
			}
			unset($row);
			return $list;
		}

		// 정식 공고 idx 추출
		$rc_idxs = array();
		foreach ($list as $row) {
			$rc_idxs[] = $row['rc_idx'];
		}

		// st_member_request 배치 조회
		// rc_idx 또는 origin_rc_idx가 화면상 공고에 일치하는 기록을 모두 조회하고, rc_idx 단위로 최신 1건씩 선택
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
		", array_merge($rc_idxs, $rc_idxs, array($mm_idx)))->result_array();

		// rc_idx 단위로 첫 등장 1건만 채택 (정렬상 정식 지원이 있으면 정식이 먼저 나옴)
		// origin_rc_idx가 있으면 그것을 기준으로, 없으면 rc_idx를 기준으로 매칭
		$room_by_rc_idx = array();
		foreach ($chat_rooms as $room) {
			$effective_rc_idx = !empty($room['origin_rc_idx']) ? $room['origin_rc_idx'] : $room['rc_idx'];
			if (isset($room_by_rc_idx[$effective_rc_idx])) {
				continue;
			}
			// mq_type으로 아이콘 종류 판단
			$room_by_rc_idx[$effective_rc_idx] = array(
				'rm_idx' => isset($room['rm_idx']) ? $room['rm_idx'] : '',
				'chat_type' => (strpos($room['mq_type'], '_DIRECT') !== false) ? 'direct' : 'apply'
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
			", array_merge($missing_rc_idxs, array($mm_idx)))->result_array();

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
			$exited_rm_idxs = get_exited_rm_idxs($rm_idxs_to_check, $user_id, $user_phone);
		}
		$exited_set = array_flip($exited_rm_idxs);

		// 목록에 chat_rm_idx와 chat_type 추가 (rc_idx로 정확히 매칭, 나간 방이면 배지 숨김)
		foreach ($list as &$row) {
			$target_rc_idx = $row['rc_idx'];
			if (isset($room_by_rc_idx[$target_rc_idx]) && !isset($exited_set[$room_by_rc_idx[$target_rc_idx]['rm_idx']])) {
				$row['chat_rm_idx'] = $room_by_rc_idx[$target_rc_idx]['rm_idx'];
				$row['chat_type'] = $room_by_rc_idx[$target_rc_idx]['chat_type'];
			} else {
				$row['chat_rm_idx'] = '';
				$row['chat_type'] = '';
			}
		}
		unset($row);

		return $list;
	}

	# 게재중인 전체 공고 건수 (검색조건 없음) — getRecruitList()의 조건 없는 count와 같은 기준
	# 목록에 검색조건이 걸려 있을 때만 사용 (조건이 없으면 getRecruitList()의 count를 그대로 쓴다)
	function getRecruitActiveTotalCnt()
	{
		$cached = $this->_readSharedRecruitCount("active", 60);
		if ($cached !== null) {
			return $cached;
		}
		$value = $this->_queryRecruitActiveTotalCnt();
		$this->_writeSharedRecruitCount("active", $value);
		return $value;
	}

	private function _queryRecruitActiveTotalCnt()
	{
		$sql = "
			SELECT COUNT(DISTINCT b.rc_idx) AS cnt
			FROM st_member a
			INNER JOIN st_recruit b ON a.mm_idx = b.mm_idx
			INNER JOIN st_address c ON a.mm_idx = c.mm_idx AND c.ad_type = 'RECRUIT' AND c.ad_key = b.rc_idx
			WHERE a.mm_status = 'Y'
				AND b.rc_show_yn = 'Y' AND b.rc_status = 'Y' AND b.rc_sdate <= NOW() AND b.rc_edate >= NOW()
		";

		$info = $this->db->query($sql)->row_array();
		return ($info == null) ? 0 : (int) $info["cnt"];
	}

	# 공고 조회 : 기본 - 게재중인
	function getRecruitActaiveList($mm_idx)
	{
		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
         AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_sdate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_sdate,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate,
         CONCAT('(', GROUP_CONCAT(sr_room.rm_idx ORDER BY sr_room.rm_idx ASC SEPARATOR ', '), ')') AS rm_idx_list,
         (SELECT MAX(up_dt) FROM st_room WHERE rc_idx = aa.rc_idx AND reg_dt <> up_dt) AS max_up_dt
			FROM st_recruit aa
      LEFT JOIN st_room AS sr_room ON aa.rc_idx = sr_room.rc_idx
                       and sr_room.mm_idx <> aa.mm_idx
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y' and aa.rc_auto_yn != 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now()
      GROUP BY aa.rc_idx
			order by max_up_dt desc, aa.rc_idx desc
			LIMIT 20
		";

		//error_log("\n".$sql."\n", "3", "/testlog/php_log");
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고 조회 : 대기중인
	function getRecruitWaitList($mm_idx)
	{
		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
         AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_sdate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_sdate,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y' and aa.rc_auto_yn != 'Y' and aa.rc_sdate > now()
			order by aa.rc_idx desc
			LIMIT 20
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고 조회 : 마감된
	function getRecruitCloseList($mm_idx)
	{
		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
         AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_sdate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_sdate,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y' and aa.rc_auto_yn != 'Y' and aa.rc_edate < now()
			order by aa.rc_idx desc
			LIMIT 20
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고조회 : 기본
	function getRecruitAllList($mm_idx)
	{

		$sql = "
			select aa.*,
        (SELECT COUNT(*) FROM st_member_request
				 WHERE mq_type = 'RECRUIT'
				 AND   rc_idx = aa.rc_idx) reqcnt,
         (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
 				 WHERE vw.mv_type='resume'
 				 AND   req.mq_type = 'RECRUIT'
				 AND   req.rc_idx = aa.rc_idx
 				 AND   vw.mv_key = req.ru_idx
         AND   vw.mm_idx = aa.mm_idx) viewcnt,
         (SELECT pm_name FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_name,
         (SELECT pm_sdate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_sdate,
         (SELECT pm_edate FROM st_promotion WHERE mm_idx = aa.mm_idx AND   rc_idx = aa.rc_idx ORDER BY pm_idx DESC LIMIT 1
         ) pm_edate
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y' and aa.rc_auto_yn != 'Y' and aa.rc_edate >= now()
			order by aa.rc_idx desc
			LIMIT 30
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고 조회 : count
	function getRecruitCountForTab($mm_idx)
	{
		$sql = "
			select sum(case when aa.rc_edate >= now() then 1 else 0 end) as allcnt,
            sum(case when aa.rc_sdate <= now() and aa.rc_edate >= now() then 1 else 0 end) as ingcnt,
            sum(case when aa.rc_sdate > now()                           then 1 else 0 end) as waitcnt,
            sum(case when aa.rc_edate < now()                           then 1 else 0 end) as closecnt
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y' and aa.rc_auto_yn != 'Y'
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function insertCallLog($data)
	{
		return $this->db_insert("st_call_log", $data);
	}

	// 특정 공고의 전화걸기 로그 전체 삭제
	function deleteCallLog($rc_idx)
	{
		return $this->db_delete("st_call_log", array("rc_idx" => $rc_idx));
	}

	// 전화걸기 로그 기업별 집계 목록 (관리자용)
	function getCallLogSummaryList($params)
	{
		$search_where = "";
		if (isset($params["search_value"]) && $params["search_value"] != "") {
			$search_where = " AND mm.mm_comp_name LIKE '%" . $this->inject($params["search_value"]) . "%' ";
		}

		// cl_type 필터 추가 (ALL=전체, CALL=전화하기, APPLY=지원하기)
		if (isset($params["cl_type"]) && $params["cl_type"] != "" && $params["cl_type"] != "ALL") {
			$search_where .= " AND cl.cl_type = '" . $this->inject($params["cl_type"]) . "' ";
		}

		// 전체 건수 조회
		$count_sql = "
			SELECT COUNT(*) AS cnt FROM (
				SELECT cl.rc_idx, mm.mm_comp_name, rc.rc_name
				FROM st_call_log cl
				JOIN st_recruit rc ON cl.rc_idx = rc.rc_idx
				JOIN st_member mm ON rc.mm_idx = mm.mm_idx
				WHERE 1=1 $search_where
				GROUP BY cl.rc_idx, mm.mm_comp_name, rc.rc_name
			) t
		";

		$count_result = $this->db->query($count_sql)->row_array();
		$total = isset($count_result["cnt"]) ? (int)$count_result["cnt"] : 0;

		// 목록 조회
		$offset = ($params["page"] - 1) * $params["pagesize"];
		$list_sql = "
			SELECT
				cl.rc_idx,
				mm.mm_comp_name,
				rc.rc_name,
				SUM(CASE WHEN cl.cl_type = 'CALL' AND cl.mm_type = 'MEM' THEN 1 ELSE 0 END) AS call_mem_cnt,
				SUM(CASE WHEN cl.cl_type = 'CALL' AND cl.mm_type = '비회원' THEN 1 ELSE 0 END) AS call_nonmem_cnt,
				SUM(CASE WHEN cl.cl_type = 'APPLY' THEN 1 ELSE 0 END) AS apply_cnt,
				COUNT(cl.cl_idx) AS total_cnt,
				MAX(cl.reg_dt) AS last_dt
			FROM st_call_log cl
			JOIN st_recruit rc ON cl.rc_idx = rc.rc_idx
			JOIN st_member mm ON rc.mm_idx = mm.mm_idx
			WHERE 1=1 $search_where
			GROUP BY cl.rc_idx, mm.mm_comp_name, rc.rc_name
			ORDER BY total_cnt DESC
			LIMIT " . (int)$offset . ", " . (int)$params["pagesize"] . "
		";
		$list = $this->db->query($list_sql)->result_array();

		$pagenation = get_page_ajax($params["page"], $total, $params["pagesize"]);

		return array(
			"count"      => $total,
			"list"       => $list,
			"pagenation" => $pagenation,
		);
	}

	// 050 비즈콜 가상번호 관리
    // 공고 번호($rc_idx)를 보고, 이 공고를 올린 사장님의 진짜 휴대폰 번호를 찾아 반환하는 함수

	// 마감 후 30일 지난 공고 정리 (cron_proc::cleanup_closed_recruits에서 호출)
	
		// @param int $limit 이번 실행에서 처리할 최대 공고 수
		// @return array ["processed"=>삭제 성공 건수, "fail"=>실패 건수]
		//DATE_SUB(기준날짜, 뺄_시간_간격) 지금 이 순간부터 정확히 30일 전의 과거 시각을 구해서, 그 시각보다 rc_edate가 작은 공고를 대상으로 삭제한다
		function cleanupClosedRecruits($limit = 20)
		{
			$limit = (int)$limit;
			$sql = "
				SELECT aa.rc_idx, aa.rc_name
				FROM st_recruit aa
				WHERE aa.rc_edate < DATE_SUB(NOW(), INTERVAL 30 DAY)
				  				ORDER BY aa.rc_idx ASC
				LIMIT {$limit}
			";
			$list = $this->db->query($sql)->result_array();

			$processed = 0;
			$fail = 0;
			foreach ($list as $row) {
				if ($this->_deleteClosedRecruitOne($row["rc_idx"], $row["rc_name"])) {
					$processed++;
				} else {
					$fail++;
				}
			}

			return array("processed" => $processed, "fail" => $fail);
		}

		# cleanupClosedRecruits() 내부용: 공고 1건을 삭제한다
		# 삭제 전 공고 제목을 st_promotion.pm_rc_name에 복사해 구매 이력의 제목을 보존하고,
		# 공고 주소·북마크·열람기록을 함께 지운 뒤 DB 커밋 후 업로드 이미지를 삭제한다
		# @param int $rc_idx 삭제할 공고 번호
		# @param string $rc_name 공고 제목 (구매 이력 복사용)
		# @return bool 삭제 성공 여부
		private function _deleteClosedRecruitOne($rc_idx, $rc_name)
		{
			$rc_idx = (int)$rc_idx;
			$info = $this->db->where("rc_idx", $rc_idx)->get("st_recruit")->row_array();
			if ($info == null) {
				return false;
			}

			$image_urls = array();
			foreach (array("rc_thumb", "rc_img1", "rc_img2", "rc_img3", "rc_img4", "rc_img5") as $col) {
				if (!empty($info[$col])) {
					$image_urls[] = $info[$col];
				}
			}

			$this->db->trans_begin();

			// 구매 이력(C코드 상품)에 공고 제목 복사 - 이미 값이 있으면 덮어쓰지 않음
			$this->db->query(
				"UPDATE st_promotion SET pm_rc_name = ? WHERE rc_idx = ? AND pm_code LIKE 'C%' AND pm_rc_name IS NULL",
				array($rc_name, $rc_idx)
			);
			$this->db->query("DELETE FROM st_address WHERE ad_type = 'RECRUIT' AND ad_key = ?", array($rc_idx));
			$this->db->query("DELETE FROM st_bookmark WHERE bk_type = 'RECRUIT' AND bk_key = ?", array($rc_idx));
			$this->db->query("DELETE FROM st_member_view WHERE mv_type = 'recruit' AND mv_key = ?", array($rc_idx));
			// 지원·제안 기록과 채팅방을 먼저 지운다 (지원 기록이 채팅방 rm_idx를 참조하므로 기록 → 방 순서)
			// 채팅방의 메시지는 MongoDB에 있으므로 여기서는 지우지 않는다
			$this->db->query("DELETE FROM st_member_request WHERE rc_idx = ?", array($rc_idx));
			$this->db->query("DELETE FROM st_room WHERE rc_idx = ?", array($rc_idx));
			$this->db->query("DELETE FROM st_recruit WHERE rc_idx = ?", array($rc_idx));

			if ($this->db->trans_status() === false) {
				$error = $this->db->error();
				$this->db->trans_rollback();
				error_log("[cleanupClosedRecruits] 실패 rc_idx=" . $rc_idx . " error=" . $error["message"]);
				return false;
			}
			$this->db->trans_commit();

			// 업로드 파일 삭제 (DB 커밋 후 처리 - 파일 삭제 실패가 데이터 정리 결과를 되돌리지 않음)
			foreach ($image_urls as $url) {
				$this->common->remove_upload_image($url);
			}

			return true;
		}

		// 공고 마감일(rc_edate)이 지난 공고에 걸린 유료 상품(C코드)을 마감일에 맞춰 종료한다
		// 상품 기간이 공고 마감일보다 길어도 마감일에 상품이 끝나며, 그 뒤 자동 재등록 복사도 멈춘다
		// autoReregisterPaidRecruit()(매일 새벽 1시)가 재등록 전에 호출한다
		// @return int 종료 처리된 상품 건수
		function endPromotionsOfExpiredRecruits()
		{
			$sql = "
				UPDATE st_promotion p
				INNER JOIN st_recruit r ON r.rc_idx = p.rc_idx
				SET p.pm_status = 'N', p.pm_edate = LEAST(p.pm_edate, r.rc_edate)
				WHERE p.pm_code LIKE 'C%'
				  AND p.pm_status = 'Y'
				  AND p.pm_edate IS NOT NULL
				  AND r.rc_edate < NOW()
			";
			// 마감일 이후에 시작하는 상품(아직 시작 전)은 기간을 맞출 수 없으므로 상태를 N으로 바꿔 제거한다
			$this->db->query("
				UPDATE st_promotion p
				INNER JOIN st_recruit r ON r.rc_idx = p.rc_idx
				SET p.pm_status = 'N'
				WHERE p.pm_code LIKE 'C%'
				  AND p.pm_status = 'Y'
				  AND p.pm_sdate > r.rc_edate
				  AND r.rc_edate < NOW()
			");

			$this->db->query($sql);
			return $this->db->affected_rows();
		}

		// 광고 구매 시 원본 공고를 복사해 광고 기간(sdate~edate)을 모집기간으로 하는 복사본을 만든다
		// 원본은 건드리지 않는다. 주소(RECRUIT, RECRUIT_EXPOSE_1/2)도 복사한다.
		// @param int    $rc_idx 원본 공고 번호
		// @param int    $mm_idx 요청자 회원 번호 (원본 소유자 검사용)
		// @param string $sdate  복사본 모집 시작일시 "Y-m-d H:i:s"
		// @param string $edate  복사본 모집 종료일시 "Y-m-d H:i:s"
		// @return int 새 복사본 rc_idx, 실패 시 0
		function copyRecruitForPromotion($rc_idx, $mm_idx, $sdate, $edate)
		{
			$rc_idx = (int) $rc_idx;
			$mm_idx = (int) $mm_idx;

			$recruit_info = $this->getRecruit(["rc_idx" => $rc_idx]);
			if ($recruit_info == null || (int) $recruit_info["mm_idx"] !== $mm_idx) {
				return 0;
			}

			$new_recruit = $recruit_info;
			unset($new_recruit["rc_idx"]);
			$new_recruit["rc_sdate"] = $sdate;
			$new_recruit["rc_edate"] = $edate;
			$new_recruit["reg_dt"] = date("Y-m-d H:i:s");
			$new_recruit["up_dt"] = date("Y-m-d H:i:s");
			$new_recruit["rc_hit"] = 0;

			// 삭제·정지·비공개 공고는 광고를 구매할 수 없다 (-1: 공고 상태 문제, 0: 소유자/공고 없음)
			if ($recruit_info["rc_status"] != "Y" || $recruit_info["rc_show_yn"] != "Y") {
				return -1;
			}

			if (!$this->db->insert("st_recruit", $new_recruit)) {
				return 0;
			}
			$new_rc_idx = $this->db->insert_id();

			// 주소 3종 복사 (기본 주소, 노출지역 1, 노출지역 2)
			$ad_types = array("RECRUIT", "RECRUIT_EXPOSE_1", "RECRUIT_EXPOSE_2");
			foreach ($ad_types as $ad_type) {
				$addr = $this->db->query("SELECT * FROM st_address WHERE ad_type = '" . $ad_type . "' AND ad_key = " . $rc_idx . " LIMIT 1")->row_array();
				if ($addr != null) {
					unset($addr["ad_idx"]);
					$addr["ad_key"] = $new_rc_idx;
					$addr["reg_dt"] = date("Y-m-d H:i:s");
					$this->db->insert("st_address", $addr);
				}
			}

			return (int) $new_rc_idx;
		}

		// 유료상품 보유 공고를 자동 재등록 (매일 새벽 1시)
	// 수동 재등록과 동일하게 새로운 row를 INSERT하며, 주소 정보도 복사
	function autoReregisterPaidRecruit($mm_idx = null)
	{
		// 마감일이 지난 공고의 유료상품을 먼저 종료해서, 아래 조회에서 재등록 대상에서 빠지게 한다
		$this->endPromotionsOfExpiredRecruits();

		// st_promotion에 연결된 공고들 조회 (현재 활성 유료상품 중 "추천공고등록"만)
		// 공고 주인이 탈퇴/정지(mm_status != 'Y')한 경우는 자동 재등록 대상에서 제외
		$sql = "
			SELECT DISTINCT p.rc_idx
			FROM st_promotion p
			INNER JOIN st_recruit r ON r.rc_idx = p.rc_idx
			INNER JOIN st_member m ON m.mm_idx = r.mm_idx
			WHERE p.pm_status = 'Y'
			  AND p.pm_sdate <= NOW()
			  AND p.pm_edate >= NOW()
			  AND p.pm_name LIKE '%추천공고등록%'
			  AND p.pm_origin_rc_idx = 0
			  AND m.mm_status = 'Y'
		";
		$query = $this->db->query($sql);
		$result = $query->result_array();

		if (empty($result)) {
			return array("code" => "01", "message" => "유료상품 보유 공고 없음", "count" => 0);
		}

		$success_count = 0;
		$fail_count = 0;

		// 각 공고별로 재등록 처리
		foreach ($result as $row) {
			$rc_idx = $row["rc_idx"];

			// 기존 공고 조회
			$recruit_info = $this->getRecruit(["rc_idx" => $rc_idx]);
			if ($recruit_info == null) {
				$fail_count++;
				continue;
			}

			// 기존 공고의 주소 정보 조회
			$sql_addr = "SELECT * FROM st_address WHERE ad_type = 'RECRUIT' AND ad_key = " . $this->db->escape($rc_idx) . " LIMIT 1";
			$query_addr = $this->db->query($sql_addr);
			$address_info = $query_addr->row_array();

			// 새 공고 생성을 위한 데이터 준비
			$new_recruit = $recruit_info;
			unset($new_recruit["rc_idx"]);

			// 날짜 업데이트
			$new_recruit["rc_sdate"] = date("Y-m-d H:i:s");
			$new_recruit["rc_edate"] = date("Y-m-d H:i:s", strtotime("+60 days"));
			$new_recruit["reg_dt"] = date("Y-m-d H:i:s");
			$new_recruit["up_dt"] = date("Y-m-d H:i:s");

			// 새로운 공고 INSERT
			if (!$this->db->insert("st_recruit", $new_recruit)) {
				$fail_count++;
				continue;
			}

			$new_rc_idx = $this->db->insert_id();

			// 새로운 공고의 기본 주소 정보 복사 (ad_type='RECRUIT')
			if ($address_info != null) {
				$new_address = $address_info;
				unset($new_address["ad_idx"]);
				$new_address["ad_key"] = $new_rc_idx;
				$new_address["reg_dt"] = date("Y-m-d H:i:s");
				$this->db->insert("st_address", $new_address);
			}

			// 노출지역 1 복사 (ad_type='RECRUIT_EXPOSE_1')
			$sql_expose1 = "SELECT * FROM st_address WHERE ad_type = 'RECRUIT_EXPOSE_1' AND ad_key = " . $this->db->escape($rc_idx) . " LIMIT 1";
			$query_expose1 = $this->db->query($sql_expose1);
			$expose_addr1 = $query_expose1->row_array();
			if ($expose_addr1 != null) {
				$new_expose1 = $expose_addr1;
				unset($new_expose1["ad_idx"]);
				$new_expose1["ad_key"] = $new_rc_idx;
				$new_expose1["reg_dt"] = date("Y-m-d H:i:s");
				$this->db->insert("st_address", $new_expose1);
			}

			// 노출지역 2 복사 (ad_type='RECRUIT_EXPOSE_2')
			$sql_expose2 = "SELECT * FROM st_address WHERE ad_type = 'RECRUIT_EXPOSE_2' AND ad_key = " . $this->db->escape($rc_idx) . " LIMIT 1";
			$query_expose2 = $this->db->query($sql_expose2);
			$expose_addr2 = $query_expose2->row_array();
			if ($expose_addr2 != null) {
				$new_expose2 = $expose_addr2;
				unset($new_expose2["ad_idx"]);
				$new_expose2["ad_key"] = $new_rc_idx;
				$new_expose2["reg_dt"] = date("Y-m-d H:i:s");
				$this->db->insert("st_address", $new_expose2);
			}

			$success_count++;
		}

		return array(
			"code" => "00",
			"message" => "자동 재등록 완료",
			"success" => $success_count,
			"fail" => $fail_count
		);
	}
}
