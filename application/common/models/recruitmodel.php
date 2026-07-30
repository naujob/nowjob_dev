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
          ,cc.ad_addr
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
		$mm_idx = isset($params["mm_idx"]) ? $this->inject($params["mm_idx"]) : 0;

	

	

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
			"aa.pm_idx",
			"(CASE WHEN dd.bk_idx IS NOT NULL THEN 'Y' ELSE 'N' END) as book_yn",
			"aa.up_dt"
		];

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
				CASE
					WHEN b.rc_nation = '내국인,외국인' THEN '국적무관'
					WHEN b.rc_nation = '내국인' THEN '내국인'
					WHEN b.rc_nation = '외국인' THEN '외국인'
					ELSE '국적무관'
				END AS rc_nation,
				b.rc_week,
				b.rc_hash,
				c.ad_addr,
				CONCAT(c.ad_doro, ' ', c.ad_jibun) AS s_addr,
				c.ad_gps_x,
				c.ad_gps_y,
				IFNULL(e1.ad_addr, '') as expose_addr1,
				IFNULL(CONCAT(e1.ad_doro, ' ', e1.ad_jibun), '') as expose_s_addr1,
				IFNULL(e2.ad_addr, '') as expose_addr2,
				IFNULL(CONCAT(e2.ad_doro, ' ', e2.ad_jibun), '') as expose_s_addr2,
				(SELECT COUNT(*) FROM st_bookmark WHERE bk_type = 'RECRUIT' AND bk_key = b.rc_idx) AS book_cnt,
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
			$this->make_addr_like_condition("aa.s_addr", $search_addr),
			$this->make_addr_like_condition("aa.ad_addr", $search_addr),
			$this->make_addr_like_condition("aa.expose_addr1", $search_addr),
			$this->make_addr_like_condition("aa.expose_s_addr1", $search_addr),
			$this->make_addr_like_condition("aa.expose_addr2", $search_addr),
			$this->make_addr_like_condition("aa.expose_s_addr2", $search_addr),
		));
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

		# 회사 지정
		if ($params["compidx"] != "") {
			$compidx = $this->inject($params["compidx"]);
			$where[] = " AND aa.mm_idx = '{$compidx}' ";
		}

		# 정렬 (유료공고 → 최신순, 유료공고 그룹 내부는 매 요청마다 랜덤)
		if ($params["order_by"] == "recent") {
			$order_by = "(aa.pm_idx IS NULL) ASC, (CASE WHEN aa.pm_idx IS NOT NULL THEN RAND() ELSE 0 END), aa.up_dt DESC";
		} else {
			$order_by = "(aa.pm_idx IS NULL) ASC, (CASE WHEN aa.pm_idx IS NOT NULL THEN RAND() ELSE 0 END), aa.book_cnt DESC, aa.up_dt DESC";
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

	function make_addr_like_condition($column, $addr_array)
	{
		if (empty($addr_array) || !is_array($addr_array)) {
			return "";
		}

		$conditions = array_map(function ($addr) use ($column) {
			$escaped_addr = addslashes($addr);
			// "전체"가 포함된 경우, "전체"를 제거하고 와일드카드로 시작
			if (strpos($addr, '전체') !== false) {
				// "서울특별시 전체" → "서울특별시"
				$base_addr = trim(str_replace('전체', '', $addr));
				$cond = "$column LIKE '" . addslashes($base_addr) . "%'";
			} else {
				$cond = "$column LIKE '%" . $escaped_addr . "%'";
			}
			return $cond;
		}, $addr_array);

		$result = " (" . implode(" OR ", $conditions) . ") ";
		return $result;
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
			and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now()
      GROUP BY aa.rc_idx
			order by max_up_dt desc, aa.rc_idx desc
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
			and aa.rc_status = 'Y' and aa.rc_sdate > now()
			order by aa.rc_idx desc
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
			and aa.rc_status = 'Y' and aa.rc_edate < now()
			order by aa.rc_idx desc
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
			and aa.rc_status = 'Y' and aa.rc_edate >= now()
			order by aa.rc_idx desc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 공고 조회 : count
	function getRecruitCountForTab($mm_idx)
	{
		$sql = "
			select sum(case when aa.rc_edate >= now() then 1 else 0 end) as allcnt,
            sum(case when aa.rc_sdate <= now() and aa.rc_edate >= now() then 1 else 0 end) ingcnt,
            sum(case when aa.rc_sdate > now()                           then 1 else 0 end) waitcnt,
            sum(case when aa.rc_edate < now()                           then 1 else 0 end) closecnt
			FROM st_recruit aa
			where aa.mm_idx = '$mm_idx'
			and aa.rc_status = 'Y'
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
}
