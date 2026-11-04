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


		$all_addr_conditions = array_filter(array(
			$this->make_addr_like_condition("aa.s_addr", $search_addr, $search_addr_step),
			$this->make_addr_like_condition("aa.ad_addr", $search_addr, $search_addr_step),
			$this->make_addr_like_condition("aa.expose_addr1", $search_addr, $search_addr_step),
			$this->make_addr_like_condition("aa.expose_s_addr1", $search_addr, $search_addr_step),
			$this->make_addr_like_condition("aa.expose_addr2", $search_addr, $search_addr_step),
			$this->make_addr_like_condition("aa.expose_s_addr2", $search_addr, $search_addr_step),
		));

		// 활성 광고를 구매한 회원의 광고 주소(st_promotion.pm_addr)가 검색 주소와 맞으면 그 회원의 공고도 포함
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
			

			$result = $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by, false, true);

		

			return $result;
		} catch (Exception $e) {
			
			throw $e;
		}
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
    // 사용자가 고른 지역을, 공고 주소를 찾아낼 SQL 조건문으로 바꿔주는 함수
	function make_addr_like_condition($column, $addr_array, $step_array = [])
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
			if ($step == 1) {
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
					$sido = addslashes($addr_parts[0]);
					$gugun = addslashes($addr_parts[1]);
					$temp_conditions = [];

					// 1. 시도+시군구 매칭 (경기도 시흥시)
					// 예전에는 이 앞에 시군구의 모든 읍면동마다 "경기도 시흥시 정왕동" LIKE를 붙였으나,
					// 그 주소들은 모두 아래 "경기도 시흥시" 조건에 이미 걸리므로 결과는 같고 비용만 커서 제거했다.
					$full_gugun = $sido . ' ' . $gugun;
					$temp_conditions[] = "$column LIKE '%{$full_gugun}%'";

					// 2. 부분주소 매칭: gugun_name만 저장된 경우 (시흥시)
					// 단, 그 시군구명이 한 시도에만 속할 때만 추가 (cross-region 방지)
					$unique_guguns = $this->_getUniqueGugunSet(array($addr_parts[1]));
					if (isset($unique_guguns[$addr_parts[1]])) {
						$temp_conditions[] = "$column LIKE '%{$gugun}%'";
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
      AND aa.rc_show_yn = 'Y'
      ORDER BY bb.reg_dt desc
      limit 10
    ";

		$result = $this->db->query($sql_data);
		return $result->result_array();
	}

	# 채용공고 목록 (핀맵용)
	function getPinsMapList($params)
	{
		$inClause = implode(',', array_map(function ($v) {
			return "'" . addslashes($v) . "'";
		}, $params["pins"]));
		$pagesize = count($params["pins"]);

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
			"(SELECT pm_idx FROM st_promotion WHERE pm_status = 'Y' AND rc_idx =aa.rc_idx AND pm_code = 'C001' AND pm_sdate <= NOW() AND pm_edate >= NOW() LIMIT 1) AS pm_idx"
		];

		$body = "
				(
					select aa.mm_id
						,aa.mm_status
						,bb.*
						,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
						,ifnull(min(po.pm_sdate),'2050-01-01 00:00:00') as max_date
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
					left join st_promotion po
						on bb.rc_idx = po.rc_idx and (po.pm_sdate <= now() and po.pm_edate >= now())
					group by bb.rc_idx
				) aa
				left join st_bookmark dd
					on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "' ";
		$body .= " LEFT JOIN st_promotion p
			ON aa.rc_idx = p.rc_idx AND p.pm_code in('C002','C003') AND NOW() BETWEEN p.pm_sdate AND p.pm_edate
			";

		# 조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";
		$where[] = 	" and aa.rc_idx IN (" . $inClause . ") ";

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

	// 유료상품 보유 공고를 자동 재등록 (매일 새벽 1시)
	// 수동 재등록과 동일하게 새로운 row를 INSERT하며, 주소 정보도 복사
	function autoReregisterPaidRecruit($mm_idx = null)
	{
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
