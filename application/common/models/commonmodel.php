<?php
class commonModel extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}

	######################################
	# 공통 처리 START
	######################################

	# 업체 리스트 조회 : 메인화면용 : 모바일
	function getHomeRecruitList($ad_addr, $params, $type = "")
	{
		$addr = $ad_addr["ad_addr"];
		$gps_x = $ad_addr["ad_gps_x"];
		$gps_y = $ad_addr["ad_gps_y"];
		if ($gps_x == '') $gps_x = "''";
		if ($gps_y == '') $gps_y = "''";

		$order_by = "aa.max_date desc,aa.book_cnt desc,aa.gps_km asc";
		$add_where = "  where ad_addr = '" . $this->inject($addr) . "' ";
		if ($type == "remove_addr") {
			$order_by = "aa.max_date desc,aa.gps_km asc,aa.book_cnt desc";
			$add_where = "";
		}

		//컬럼
		$cols = [
			"aa.*",
			"group_concat(cc.ad_addr) as ad_addr",
			"(case
				when rc_nation = '내국인,외국인' then '국적무관'
				when rc_nation = '내국인' then '내국인'
				when rc_nation = '외국인' then '외국인'
				else '국적무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '',' ~ ',rc_eage,'대')
				when rc_sage <> '' then concat(rc_sage, '')
				when rc_eage <> '' then concat(rc_eage, '대')
				else '나이무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];
		//세 이상,  이하

		//바디
		$body = "
			(
                select aa.mm_id
                    ,aa.mm_status
                    ,bb.*
                    ,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
                    ,ifnull(min(po.pm_sdate),'2000-01-01 00:00:00') as max_date
                    ,gps_km
                FROM st_member aa
                inner join st_recruit bb
                    on aa.mm_idx = bb.mm_idx
                inner join (
                    SELECT *
                        ,ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
                            -radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1)
                        AS gps_km
                    FROM st_address
                    $add_where
                ) cc
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
                group by bb.rc_idx
            ) aa
			inner join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = aa.rc_idx
			left join st_bookmark dd
				on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
      LEFT JOIN st_promotion p
        ON aa.rc_idx = p.rc_idx AND p.pm_code = 'C001' AND NOW() BETWEEN p.pm_sdate AND p.pm_edate and p.pm_status = 'Y'
		";

		//조건
		$where = [];

		//24.10.22 유료공고만 나오도록
		$where[] = 	" and aa.rc_idx = p.rc_idx ";

		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";
		if ($params["search_value"] != "") {
			$where[] = 	" and (aa.mm_name like '%" . $this->inject($params["search_value"]) . "%'
						  or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')";
		}

		//정렬
		$group_by = "group by aa.rc_idx";
		//$order_by = "p.pm_idx desc ";
		$order_by = "rand() ";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 업체 리스트 조회 : 메인화면용 : 웹
	function getHomeWebRecruitList($ad_addr, $params)
	{
		$addr = $ad_addr["ad_addr"];
		$gps_x = $ad_addr["ad_gps_x"];
		$gps_y = $ad_addr["ad_gps_y"];

		//컬럼
		$cols = [
			"aa.*",
			"group_concat(cc.ad_addr) as ad_addr",
			"(case
				when rc_nation = '내국인,외국인' then '내국인,외국인'
				when rc_nation = '내국인' then '내국인만'
				when rc_nation = '외국인' then '외국인만'
				else '국적무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '나이무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
		$body = "
			(
                select aa.mm_id
                    ,aa.mm_status
                    ,bb.*
                    ,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
                    ,ifnull(min(po.pm_sdate),'2000-01-01 00:00:00') as max_date
                    , po.pm_sdate
                FROM st_member aa
                inner join st_recruit bb
                    on aa.mm_idx = bb.mm_idx
                inner join st_address cc
                    on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = bb.rc_idx
                left join (
					             select aa.*
					             FROM st_promotion aa
					             inner join (
					                   select max(pm_idx) as max_idx, rc_idx
					                   FROM st_promotion po
					                   where (po.pm_sdate <= now() and po.pm_edate >= now()) and po.pm_code = 'C001'
					                   group by rc_idx,pm_idx
					             ) bb on aa.pm_idx = max_idx
				        ) po on bb.rc_idx = po.rc_idx
                group by bb.rc_idx
            ) aa
			inner join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = aa.rc_idx
			left join st_bookmark dd
				on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		";

		//조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_status = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";
		//2024.10.18
		//유료 추천공고만 나오도록, 일반공고는 안나오게 처리
		$where[] = 	" and aa.pm_sdate is not null ";

		if ($params["search_value"] != "") {
			$where[] = 	" and (aa.mm_name like '%" . $this->inject($params["search_value"]) . "%'
						  or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')";
		}

		//정렬
		//$order_by = "aa.max_date desc,aa.book_cnt desc";
		$order_by = "rand() ";
		$group_by = "group by aa.rc_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 추천 공고 : 모바일용
	function getFavRecruitList($ad_addr, $params)
	{
		$addr = $ad_addr["ad_addr"];
		$gps_x = $ad_addr["ad_gps_x"];
		$gps_y = $ad_addr["ad_gps_y"];
		if ($gps_x == '') $gps_x = "''";
		if ($gps_y == '') $gps_y = "''";

		//컬럼
		$cols = [
			"aa.*",
			"group_concat(cc.ad_addr) as ad_addr",
			"(case
				when rc_nation = '내국인,외국인' then '국적무관'
				when rc_nation = '내국인' then '내국인만'
				when rc_nation = '외국인' then '외국인만'
				else '국적무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '나이무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
		$body = "
			(
                select aa.mm_id
                    ,aa.mm_status
                    ,bb.*
                    ,(select count(*) as cnt FROM st_bookmark where bk_type = 'RECRUIT' and bk_key = bb.rc_idx) as book_cnt
                    ,ifnull(min(po.pm_sdate),'2000-01-01 00:00:00') as max_date
                    ,gps_km
                FROM st_member aa
                inner join st_recruit bb
                    on aa.mm_idx = bb.mm_idx
                inner join (
                    SELECT *
                        ,ROUND(6371*acos(cos(radians($gps_y))*cos(radians(ad_gps_y))*cos(radians(ad_gps_x)
                            -radians($gps_x))+sin(radians($gps_y))*sin(radians(ad_gps_y))),1)
                        AS gps_km
                    FROM st_address
                    where ad_addr = '" . $this->inject($addr) . "'
                ) cc
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
                group by bb.rc_idx
            ) aa
			inner join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RECRUIT' and cc.ad_key = aa.rc_idx
			left join st_bookmark dd
				on aa.rc_idx = dd.bk_key and dd.bk_type = 'RECRUIT' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		";

		//조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and aa.rc_show_yn = 'Y' and aa.rc_sdate <= now() and aa.rc_edate >= now() ";
		if ($params["search_value"] != "") {
			$where[] = 	" and (aa.mm_name like '%" . $this->inject($params["search_value"]) . "%'
						  or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')";
		}

		//정렬
		$order_by = "aa.max_date desc,aa.book_cnt desc,aa.gps_km asc";
		$group_by = "group by aa.rc_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 추천공고 : 웹용
	function getFavRecruitWebList($params)
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

		//컬럼
		$cols = [
			"aa.*",
			"group_concat(cc.ad_addr) as ad_addr",
			"(case
				when rc_nation = '내국인,외국인' then '국적무관'
				when rc_nation = '내국인' then '내국인만'
				when rc_nation = '외국인' then '외국인만'
				else '국적무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '나이무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
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
		//추천공고 유료만 노출되도록
		$body .= " and bb.rc_idx = po.rc_idx ";

		//특정업체의 공고만 조회 (지금뜨는 업체에서 클릭)
		if ($params["comp_idx"] != "") {
			$body .= " and aa.mm_idx = '" . $params["comp_idx"] . "' ";
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

		if ($search_addr != "") {
			$where[] = 	" and (aa.ad_addr like '%" . $search_addr . "%' or p.pm_addr LIKE '%" . $search_addr . "%')";
		}

		//정렬
		//$order_by = "aa.max_date desc, GREATEST(aa.up_dt, IFNULL(p.up_dt, '2000-01-01 00:00:00')) DESC, aa.book_cnt desc";
		$order_by = "rand() ";
		$group_by = "group by aa.rc_idx ";

		return $this->db_paging_list($cols, $body, $where, $order_by, 1, 24, $group_by);
	}

	function getFavRecruitWebList2025($params)
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
			"aa.*",
			"group_concat(cc.ad_addr) as ad_addr",
			"(case
				when rc_nation = '내국인,외국인' then '국적무관'
				when rc_nation = '내국인' then '내국인만'
				when rc_nation = '외국인' then '외국인만'
				else '국적무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '나이무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
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
		//추천공고 유료만 노출되도록
		$body .= " and bb.rc_idx = po.rc_idx ";

		//특정업체의 공고만 조회 (지금뜨는 업체에서 클릭)
		if ($params["comp_idx"] != "") {
			$body .= " and aa.mm_idx = '" . $params["comp_idx"] . "' ";
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

		if ($search_addr != "") {
			$where[] = 	" and (aa.ad_addr like '%" . $search_addr . "%' or p.pm_addr LIKE '%" . $search_addr . "%')";
		}

		$order_by = "rand() ";
		//$order_by .= "limit 0,".$params["limit"]." ";


		$group_by = "group by aa.rc_idx ";

		return $this->db_paging_list($cols, $body, $where, $order_by, 1, $limit, $group_by);
	}

	function getCompRecruitWebList2025($params)
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

		//컬럼
		$cols = [
			"aa.*",
			"group_concat(cc.ad_addr) as ad_addr",
			"(case
				when rc_nation = '내국인,외국인' then '국적무관'
				when rc_nation = '내국인' then '내국인만'
				when rc_nation = '외국인' then '외국인만'
				else '국적무관' end) as rc_nation_nm",
			"(case
				when rc_sage <> '' and rc_eage <> '' then concat(rc_sage, '세 이상',' ~ ',rc_eage,'세 이하')
				when rc_sage <> '' then concat(rc_sage, '세 이상')
				when rc_eage <> '' then concat(rc_eage, '세 이하')
				else '나이무관' end) as rc_age_nm",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
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
		//추천공고 유료만 노출되도록
		// $body .= " and bb.rc_idx = po.rc_idx ";

		//특정업체의 공고만 조회 (지금뜨는 업체에서 클릭)
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

	# 뜨는 업체 리스트 조회 : 메인화면용
	function getHomeUpCompList($ad_addr, $length = 5)
	{
		$addr = $ad_addr["ad_addr"];
		$gps_x = $ad_addr["ad_gps_x"];
		$gps_y = $ad_addr["ad_gps_y"];

		$sql_limit = "";
		if (is_numeric($length) && (int)$length > 0) {
			$sql_limit = " limit 0," . (int)$length;
		}

		$sql = "
			select aa.*
				    ,bb.ma_work
            ,max(rc.rc_idx) as rc_idx
            ,count(rc.rc_idx) as cnt
			FROM st_member aa
      inner join st_recruit rc
          on aa.mm_idx = rc.mm_idx
			left join st_member_info bb
					on aa.mm_idx = bb.mm_idx
      LEFT JOIN st_promotion p
          ON p.mm_idx = aa.mm_idx AND p.pm_code = 'C004' AND p.pm_sdate < NOW() AND p.pm_edate > NOW() AND p.pm_status = 'Y'
			where aa.mm_type= 'COMP'
			  and aa.mm_status = 'Y'
	      and rc.rc_status = 'Y'
        AND rc.rc_show_yn = 'Y'
	      AND rc.rc_sdate < NOW() AND rc.rc_edate > NOW()
        AND p.pm_status = 'Y'
      group by aa.mm_idx
			ORDER BY RAND()
      {$sql_limit}
		";
		//order by p.pm_edate DESC, rc.rc_idx desc

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 중고거래  : 메인하면용
	function getHomeTradeList($ad_addr, $mm_idx, $length = 6)
	{
		$addr = $ad_addr["ad_addr"];
		$gps_x = $ad_addr["ad_gps_x"];
		$gps_y = $ad_addr["ad_gps_y"];

		$sql_data = "
			select *
				,group_concat(bb.ad_addr) as ad_addr
				,(case when cc.bk_idx is not null then 'Y' else 'N' end) as book_yn
			FROM st_trade aa
				left join st_bookmark cc
					on aa.td_idx = cc.bk_key and cc.bk_type = 'TRADE' and cc.mm_idx = '$mm_idx'
				left join st_address bb
					on aa.td_idx = bb.ad_key and bb.ad_type = 'TRADE'
			where 1=1
			and aa.td_status = 'Y'
			group by aa.td_idx
			order by aa.td_idx desc
			limit 0,$length;
		";

		$result = $this->db->query($sql_data);
		return $result->result_array();
	}

	# 중고거래  : 메인하면용
	function getFavTradeList($ad_addr, $mm_idx, $length = 5)
	{
		$addr = $ad_addr["ad_addr"];
		$gps_x = $ad_addr["ad_gps_x"];
		$gps_y = $ad_addr["ad_gps_y"];

		$sql_data = "
			select *
				,group_concat(bb.ad_addr) as ad_addr
				,(case when cc.bk_idx is not null then 'Y' else 'N' end) as book_yn
			FROM st_trade aa
				left join st_bookmark cc
					on aa.td_idx = cc.bk_key and cc.bk_type = 'TRADE' and cc.mm_idx = '$mm_idx'
				left join st_address bb
					on aa.td_idx = bb.ad_key and bb.ad_type = 'TRADE'
			where 1=1
			group by aa.td_idx
			order by aa.td_idx desc
			limit 0,$length;
		";

		$result = $this->db->query($sql_data);
		return $result->result_array();
	}

	# 업체 리스트 조회 : 메인화면용
	function getHomeResumeList($ad_addr, $params)
	{
		$addr = $ad_addr["ad_addr"];
		$gps_x = $ad_addr["ad_gps_x"];
		$gps_y = $ad_addr["ad_gps_y"];

		//컬럼
		$cols = [
			"bb.*",
			"aa.mm_id",
			"aa.mm_name",
			"mi.ma_age",
			"mi.ma_nation",
			"aa.mm_nation",
			"aa.mm_sex",
			"group_concat(cc.ad_addr) as ad_addr",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
		$body = "
			st_member aa
			inner join st_member_info mi
				on aa.mm_idx = mi.mm_idx
			inner join st_resume bb
				on aa.mm_idx = bb.mm_idx
			left join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
			left join st_bookmark dd
				on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
      left join st_promotion po
  			on po.pm_sdate <= now() and po.pm_edate >= now() and po.pm_code = 'M001' and po.rc_idx = bb.ru_idx
		";

		//조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and bb.ru_show_yn = 'Y' ";

		//24.10.18 유료 추천인재만 나오도록 수정
		$where[] = 	" and po.pm_idx is not null ";

		if ($params["search_value"] != "") {
			$where[] = 	" and (aa.mm_name like '%" . $this->inject($params["search_value"]) . "%'
						  or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')";
		}

		//정렬
		$order_by = "po.pm_idx desc, bb.ru_idx desc";
		$group_by = "group by bb.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 모바일용 : 추천 인재 리스트
	function getFavResumeList($ad_addr, $params)
	{
		//pc 인재정리 - 추천인재

		$addr = $ad_addr["ad_addr"];
		$gps_x = $ad_addr["ad_gps_x"];
		$gps_y = $ad_addr["ad_gps_y"];

		//컬럼
		$cols = [
			"bb.*",
			"aa.mm_id",
			"aa.mm_name",
			"aa.mm_nation",
			"mi.ma_age",
			"aa.mm_sex",
			"aa.mm_thumb",
			"group_concat(cc.ad_addr) as ad_addr",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
		$body = "
			st_member aa
			inner join st_member_info mi
                on aa.mm_idx = mi.mm_idx
			inner join st_resume bb
				on aa.mm_idx = bb.mm_idx
			left join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
			left join st_bookmark dd
				on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
      left join st_promotion p
  				on p.pm_code = 'M001'
  				AND   p.pm_sdate <= NOW() AND p.pm_edate >= NOW()
  				AND   p.pm_status = 'Y'
		";

		//조건
		$where = [];

		//24.10.18 유료 추천인재만 나오도록 수정
		$where[] = 	" and p.rc_idx = bb.ru_idx ";

		$where[] = 	" and aa.mm_status = 'Y' and bb.ru_show_yn = 'Y' ";
		if ($params["search_value"] != "") {
			$where[] = 	" and (aa.mm_name like '%" . $this->inject($params["search_value"]) . "%'
						  or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')";
		}

		//정렬
		$order_by = "p.pm_idx desc,bb.ru_idx desc";
		$group_by = "group by bb.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	function getFavResumeList2025($params)
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
		$idx = isset($params["idx"]) ? $this->inject($params["idx"]) : "";

		$limit = isset($params["limit"]) ? $this->inject($params["limit"]) : "24";

		$cols = [
			"bb.*",
			"aa.mm_idx",
			"aa.mm_name",
			"aa.mm_nation",
			"aa.mm_birth",
			"aa.mm_sex",
			"aa.mm_thumb",
			"group_concat(cc.ad_addr) as ad_addr",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
		$body = "
			st_member aa
			inner join st_resume bb
				on aa.mm_idx = bb.mm_idx
			left join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
			left join st_bookmark dd
				on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
      left join st_promotion p
        on p.pm_code = 'M001'
  				AND   p.pm_sdate <= NOW() AND p.pm_edate >= NOW()
  				AND   p.pm_status = 'Y'
		";

		//조건
		$where = [];
		$where[] = 	" and p.rc_idx = bb.ru_idx ";
		$where[] = 	" and aa.mm_status = 'Y' and bb.ru_show_yn = 'Y' ";
		if ($search_addr != "") {
			$where[] = 	" and cc.ad_addr like '%" . $search_addr . "%' ";
		}
		if ($idx != "") {
			$where[] = 	" and bb.ru_idx <> '" . $idx . "' ";
		}
		//정렬
		$order_by = "rand() ";
		$group_by = "group by bb.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, 1, $limit, $group_by);
	}


	# 웹용  : 추천 인재 리스트
	function getFavResumeWebList($ad_addr, $params)
	{
		$addr = $ad_addr["ad_addr"];
		$gps_x = $ad_addr["ad_gps_x"];
		$gps_y = $ad_addr["ad_gps_y"];

		//컬럼
		$cols = [
			"bb.*",
			"aa.mm_id",
			"aa.mm_nation",
			"mi.ma_age",
			"group_concat(cc.ad_addr) as ad_addr",
			"(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn"
		];

		//바디
		$body = "
			st_member aa
			inner join st_member_info mi
                    on aa.mm_idx = mi.mm_idx
			inner join st_resume bb
				on aa.mm_idx = bb.mm_idx
			inner join st_address cc
				on aa.mm_idx = cc.mm_idx and cc.ad_type = 'RESUME' and cc.ad_key = bb.ru_idx
			left join st_bookmark dd
				on bb.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
		";

		//조건
		$where = [];
		$where[] = 	" and aa.mm_status = 'Y' and bb.ru_show_yn = 'Y' ";
		if ($params["search_value"] != "") {
			$where[] = 	" and (aa.mm_name like '%" . $this->inject($params["search_value"]) . "%'
						  or mm_comp_name like '%" . $this->inject($params["search_value"]) . "%')";
		}

		//정렬
		$order_by = "bb.ru_idx desc";
		$group_by = "group by bb.ru_idx";

		return $this->db_paging_list($cols, $body, $where, $order_by, $params["page"], $params["pagesize"], $group_by);
	}

	# 뜨는 이력서 리스트
	function getHomeUpResumeList($ad_addr, $params)
	{
		$addr = $ad_addr["ad_addr"];
		$gps_x = $ad_addr["ad_gps_x"];
		$gps_y = $ad_addr["ad_gps_y"];

		$length = $params["length"];

		$sql = "
			select aa.*
                ,bb.mm_name
                ,bb.mm_nation
                ,bb.mm_id
                ,cc.ma_age
                ,bb.mm_birth
                ,bb.mm_sex
                ,(case when dd.bk_idx is not null then 'Y' else 'N' end) as book_yn
			FROM st_resume aa
                inner join st_member bb
                    on aa.mm_idx = bb.mm_idx
                inner join st_member_info cc
                    on bb.mm_idx = cc.mm_idx
                left join st_bookmark dd
					on aa.ru_idx = dd.bk_key and dd.bk_type = 'RESUME' and dd.mm_idx = '" . $this->inject($params["mm_idx"]) . "'
			where 1=1
			and bb.mm_status = 'Y' and aa.ru_show_yn = 'Y' and aa.ru_status = 'Y'
			order by aa.ru_idx desc
			limit 0,$length
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 채팅룸 셋팅
	function setRoom($info, $where)
	{
		$check = $this->getRoom($where);
		if ($check != null) {
			$info["up_dt"] = get_today();
			return $this->updateRoom($info, $where);
		} else {
			return $this->insertRoom($info);
		}
	}

	# 채팅룸 등록
	function insertRoom($info)
	{
		return $this->db_insert("st_room", $info);
	}

	# 채팅룸 등록
	function updateRoom($info, $where)
	{
		return $this->db_update("st_room", $info, $where);
	}

	# 룸정보 가져오기
	function getRoom($where)
	{
		return $this->db_row("st_room", $where);
	}

	# 채팅룸 등록
	function insertRoomHistory($info)
	{
		return $this->db_insert("st_room_history", $info);
	}

	# 면접 관리 등록
	function insertInterview($info)
	{
		return $this->db_insert("st_interview", $info);
	}

	# 채팅룸 등록
	function updateInterview($info, $where)
	{
		return $this->db_update("st_interview", $info, $where);
	}

	# 룸정보 가져오기
	function getInterview($where)
	{
		return $this->db_row("st_interview", $where);
	}

	# 뜨는 이력서 리스트
	function getInterviewList($params)
	{
		$sql = "
			select bb.mm_name
                ,bb.mm_phone
                ,bb.mm_id
                ,bb.mm_img1
                ,aa.*
            FROM st_interview aa
                inner join st_member bb
                    on aa.ru_mm_idx = bb.mm_idx
            where aa.rc_mm_idx = '" . $this->inject($params["mm_idx"]) . "'
            order by aa.reg_dt desc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	# 팝업 리스트 : 전체 또는 유형에 맞게 노출
	function getEventPopupList($type)
	{
		$add_where = $type != "" ? "where aa.pp_type in ('ALL','" . $this->inject($type) . "')" : "where 1=1 ";
		$sql = "
			select *
            FROM st_popup aa
            $add_where
            and pp_status = 'Y'
            and pp_sdate <= now() and pp_edate >= now()
            order by aa.reg_dt desc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}
	######################################
	# END 공통 처리
	######################################

	# 광고 배너 클릭 로그 저장
	function insertAdClickLog($data)
	{
		return $this->db_insert("st_ad_click_log", $data);
	}
}
