<?php
class goodsModel extends MY_Model {
   	function __construct() {
    	parent::__construct();
	}

	///////////////////////////////////////////////////////////////////////////
	// 상품 처리
	///////////////////////////////////////////////////////////////////////////

	//상품 테이블 리스트
	function getGoodsList($params)
	{
		//컬럼
		$cols = [
			"aa.gd_idx",
			"aa.show_yn",
			"aa.gd_code",
			"aa.gd_adm_code",
			"aa.gd_erp_code",
			"aa.gd_name",
			"aa.gd_list_name",
			"aa.gd_sub_name",
			"aa.gd_status",
			"aa.gd_s_price",
			"aa.gd_p_price",
			"aa.gd_i_price",
			"aa.bd_idx",
			"aa.gd_img_type",
			"aa.gd_img_main",
			"aa.gd_img_list",
			"aa.gd_img_main_url",
			"aa.gd_img_list_url",
			"aa.gd_stock",
			"aa.gd_sort",
			"aa.gd_keyword",
			"aa.gd_cm3",
			"aa.gd_make",
			"aa.gd_origin",
			"aa.gd_stock_yn",
			"aa.gd_option_yn",
			"aa.gd_option_add_type",
			"aa.gd_certi_file",
			"aa.gd_pc_desc",
			"aa.gd_m_desc",
			"aa.gd_detail_pay",
			"aa.gd_detail_delivery",
			"aa.gd_detail_claim",
			"aa.gd_detail_claim_deny",
			"aa.gd_detail_service",
			"aa.gd_detail_pay",
			"aa.gd_adddlv_type",
			"aa.st_admin_type",
			"(case when aa.gd_adddlv_type='today' then '당일배송' when aa.gd_adddlv_type = 'etc' then '기타' else '' end) as gd_adddlv_type_nm ",
			"aa.gd_adddlv_txt",
			"aa.reg_id",
			"aa.up_id",
			"aa.reg_dt",
			"aa.up_dt",
			"cp.gc_name",
			"cp.gc_won",
			"cp.gc_won_type",
			"
			(case when gc_won_type = 'WON' then '원'
				when gc_won_type = 'PER' then '%'
				else '' end) as gc_won_type_nm
			",
			"
			(case when gd_status = 'Y' then '노출'
				when gd_status = 'S' then '품절'
				when gd_status = 'N' then '비노출'
				else '' end) as gd_status_nm
			",
			"
			(case when gd_img_type = 'img' then '이미지'
				when gd_img_type = 'url' then 'URL'
				else '' end) as gd_img_type_nm
			",
			"
			(case when gd_stock_yn = 'Y' then '사용'
				when gd_stock_yn = 'N' then '미사용'
				else '' end) as gd_stock_yn_nm
			",
			"bb.bd_name",
			"ct.all_cate_name",
			"dv.dv_name",

		];

		//카테고리 처리
		$add_where = "";
		if($params["gd_cate3"]!=""){
			$add_where .= " and ct_depth3_idx = '".$this->inject($params["gd_cate3"])."' ";
		}else if($params["gd_cate2"]!=""){
			$add_where .= " and ct_depth2_idx = '".$this->inject($params["gd_cate2"])."' ";
		}else if($params["gd_cate1"]!=""){
			$add_where .= " and ct_depth1_idx = '".$this->inject($params["gd_cate1"])."' ";
		}

		//바디
		$body = "
			vw_goods aa
				left join st_brand bb
					on aa.bd_idx = bb.bd_idx
				left join (
					select
					     aa.gc_name
					    ,aa.gc_won
					    ,aa.gc_won_type
					    ,bb.gd_idx
					FROM st_goods_coupon aa
					    inner join st_goods_coupon_list bb
					        on aa.gc_idx = bb.gc_idx
					    inner join (
					        SELECT gd_idx,max(aa.gc_idx) as gc_idx
					        FROM st_goods_coupon aa
					            inner join st_goods_coupon_list bb
					                on aa.gc_idx = bb.gc_idx
					        where aa.gc_sdate <=now() and gc_edate >= now()
					        group by gd_idx
					    ) cc
					        on bb.gc_idx = bb.gc_idx and bb.gd_idx = cc.gd_idx and aa.gc_idx = cc.gc_idx
				) cp on aa.gd_idx = cp.gd_idx
				left join (
					SELECT bb.gd_idx
						,GROUP_CONCAT(CONCAT(ct_depth1_name
					              ,(case when ct_depth2_name ='' then '' else CONCAT('>',ct_depth2_name)  end)
					              ,(case when ct_depth3_name ='' then '' else CONCAT('>',ct_depth3_name)  end)
					             ) separator ';') as all_cate_name
					             ,bb.ct_idx
					FROM `st_category` aa
						inner join st_goods_category bb
					    	on aa.ct_idx = bb.ct_idx
					where 1=1  $add_where
					group by bb.gd_idx
				) ct on aa.gd_idx = ct.gd_idx
				left join st_delivery dv
					on aa.dv_idx = dv.dv_idx

		";

		//조건
		$where = [];
		if($params["search_value"]!=""){
			$keyword = str_replace(" ","",str_replace("-","",strtolower($params["search_value"])));
			if($params["search_type"]==""){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_code),'-',''),' ','') like '%".$this->inject($keyword)."%'  or REPLACE(REPLACE(LOWER(gd_sub_name),'-',''),' ','') like '%" .$this->inject($keyword)."%')  ";
			}else if($params["search_type"]=="goods_code"){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_code),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
			}else if($params["search_type"]=="goods_name"){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_name),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
			}else if($params["search_type"]=="goods_sub_name"){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_sub_name),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
			}
		}

		if($params["bd_idx"]!=""){
			$where[] = 	" and (aa.bd_idx = '".$this->inject($params["bd_idx"])."')  ";
		}

		if($params["gd_status"]!="R"){
			$where[] = 	" and (gd_status!='R')  ";
		}

		if($params["gd_status"]!=""){
			$where[] = 	" and (gd_status = '".$this->inject($params["gd_status"])."')  ";
		}

		if(isset($params["ignore_idx"]) && $params["ignore_idx"]!=""){
			$where[] = 	" and aa.gd_idx !='".$this->inject($params["ignore_idx"])."'  ";
		}

		if($params["gd_cate1"]!="" || $params["gd_cate2"]!="" || $params["gd_cate3"]!=""){
			$where[] = 	" and ct.ct_idx !=''  ";
		}

		if(isset($params["st_admin_type"]) && ($params["st_admin_type"]=="store")){
			$where[] = " and aa.st_admin_type = 'store' ";
		}

		$where[] = " and aa.gd_type = '' ";

        $groupby = " group by aa.gd_idx ";

		//정렬
		$order_by = "aa.gd_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"],$groupby,false);
   	}

   	//상품 테이블 리스트
	function getGoodsGrantList($params,$grant_list)
	{
		//컬럼
		$cols = [
			"aa.*",
			"bb.bd_name",
			"
			(case when gd_status = 'Y' then '노출'
				when gd_status = 'S' then '품절'
				when gd_status = 'N' then '비노출'
				else '' end) as gd_status_nm
			",
		];

		foreach($grant_list as $grant){
			$join_key = "sg_" . $grant["sg_idx"];
			$cols[] = "" . $join_key . "." . "gp_p_price as " . $join_key . "_gp_p_price";
			$cols[] = "" . $join_key . "." . "gp_s_price as " . $join_key . "_gp_s_price";
			$cols[] = "" . $join_key . "." . "gp_i_price as " . $join_key . "_gp_i_price";
		}

		//카테고리 처리
		$add_where = "";
		if($params["gd_cate3"]!=""){
			$add_where .= " and ct_depth3_idx = '".$this->inject($params["gd_cate3"])."' ";
		}else if($params["gd_cate2"]!=""){
			$add_where .= " and ct_depth2_idx = '".$this->inject($params["gd_cate2"])."' ";
		}else if($params["gd_cate1"]!=""){
			$add_where .= " and ct_depth1_idx = '".$this->inject($params["gd_cate1"])."' ";
		}

		//바디
		$body = "
			st_goods aa
			left join st_brand bb
				on aa.bd_idx = bb.bd_idx

			left join (
				SELECT bb.gd_idx
					,GROUP_CONCAT(CONCAT(ct_depth1_name
				              ,(case when ct_depth2_name ='' then '' else CONCAT('>',ct_depth2_name)  end)
				              ,(case when ct_depth3_name ='' then '' else CONCAT('>',ct_depth3_name)  end)
				             ) separator ';') as all_cate_name
				             ,bb.ct_idx
				FROM `st_category` aa
					inner join st_goods_category bb
				    	on aa.ct_idx = bb.ct_idx
				where 1=1  $add_where
				group by bb.gd_idx
			) ct on aa.gd_idx = ct.gd_idx
		";

		foreach($grant_list as $grant){
			$sg_idx = $grant["sg_idx"];
			$join_key = "sg_" . $grant["sg_idx"];
			$body = $body . "
				left join st_goods_grant_price $join_key
					on aa.gd_idx = $join_key.gd_idx and $join_key.sg_idx = '$sg_idx'
			";
		}

		//조건
		$where = [];
		if($params["search_value"]!=""){
			$keyword = str_replace(" ","",str_replace("-","",strtolower($params["search_value"])));
			if($params["search_type"]==""){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_code),'-',''),' ','') like '%".$this->inject($keyword)."%'  or REPLACE(REPLACE(LOWER(gd_sub_name),'-',''),' ','') like '%" .$this->inject($keyword)."%')  ";
			}else if($params["search_type"]=="goods_code"){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_code),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
			}else if($params["search_type"]=="goods_name"){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_name),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
			}else if($params["search_type"]=="goods_sub_name"){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_sub_name),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
			}
		}

		if($params["bd_idx"]!=""){
			$where[] = 	" and (aa.bd_idx = '".$this->inject($params["bd_idx"])."')  ";
		}

		if($params["gd_status"]!=""){
			$where[] = 	" and (gd_status = '".$this->inject($params["gd_status"])."')  ";
		}

		if(isset($params["ignore_idx"]) && $params["ignore_idx"]!=""){
			$where[] = 	" and aa.gd_idx !='".$this->inject($params["ignore_idx"])."'  ";
		}

		if($params["gd_cate1"]!="" || $params["gd_cate2"]!="" || $params["gd_cate3"]!=""){
			$where[] = 	" and ct.ct_idx !=''  ";
		}

		if(isset($params["st_admin_type"]) && ($params["st_admin_type"]=="store")){
			$where[] = " and aa.st_admin_type = 'store' ";
		}

		$where[] = " and aa.gd_type = '' ";

		//정렬
		$order_by = "aa.gd_sort desc,aa.gd_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

   	//서버 상품정보 그룹 획득
   	function getGoodsListGdIdx($gd_idxs){
   		$sql = "
			select st_admin_key,gd_idx
			FROM st_goods
			where st_admin_key in ($gd_idxs)
		";

		$query = $this->db->query($sql);
		return $query->result_array();
   	}

   	//상품 테이블 리스트
	function getStockList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"bb.bd_name",
			"
			(case when gd_status = 'Y' then '노출'
				when gd_status = 'S' then '품절'
				when gd_status = 'N' then '비노출'
				else '' end) as gd_status_nm
			",
			"ifnull(oo.gl_idxs,'') as gl_idxs",
			"ifnull(oo.gg_names,'') as gg_names",
			"ifnull(oo.gg_stocks,'') as gg_stocks",
		];

		//카테고리 처리
		$add_where = "";
		if($params["gd_cate1"]!=""){
			$add_where .= " and ct_depth1_idx = '".$this->inject($params["gd_cate1"])."' ";
		}else if($params["gd_cate2"]!=""){
			$add_where .= " and ct_depth2_idx = '".$this->inject($params["gd_cate2"])."' ";
		}else if($params["gd_cate1"]!=""){
			$add_where .= " and ct_depth3_idx = '".$this->inject($params["gd_cate3"])."' ";
		}

		//바디
		$body = "
			st_goods aa
			left join (
				select gd_idx
					,group_concat(gl_idx order by gl_idx separator '|' )  as gl_idxs
					,group_concat(gg_name order by gl_idx separator '|' )  as gg_names
					,group_concat(gg_stock order by gl_idx separator '|' )  as gg_stocks
				FROM st_goods_option_list
				group by gd_idx
			) oo
				on aa.gd_idx = oo.gd_idx
			left join st_brand bb
				on aa.bd_idx = bb.bd_idx
			left join (
				SELECT bb.gd_idx
					,GROUP_CONCAT(CONCAT(ct_depth1_name
				              ,(case when ct_depth2_name ='' then '' else CONCAT('>',ct_depth2_name)  end)
				              ,(case when ct_depth3_name ='' then '' else CONCAT('>',ct_depth3_name)  end)
				             ) separator ';') as all_cate_name
				             ,bb.ct_idx
				FROM `st_category` aa
					inner join st_goods_category bb
				    	on aa.ct_idx = bb.ct_idx
				where 1=1  $add_where
				group by bb.gd_idx
			) ct on aa.gd_idx = ct.gd_idx
		";

		//조건
		$where = [];
		if($params["search_value"]!=""){
			$keyword = str_replace(" ","",str_replace("-","",strtolower($params["search_value"])));
			if($params["search_type"]==""){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_code),'-',''),' ','') like '%".$this->inject($keyword)."%'  or REPLACE(REPLACE(LOWER(gd_sub_name),'-',''),' ','') like '%" .$this->inject($keyword)."%')  ";
			}else if($params["search_type"]=="goods_code"){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_code),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
			}else if($params["search_type"]=="goods_name"){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_name),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
			}else if($params["search_type"]=="goods_sub_name"){
				$where[] = 	" and (REPLACE(REPLACE(LOWER(gd_sub_name),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
			}
		}

		if($params["bd_idx"]!=""){
			$where[] = 	" and (aa.bd_idx = '".$this->inject($params["bd_idx"])."')  ";
		}

		if($params["gd_status"]!=""){
			$where[] = 	" and (gd_status = '".$this->inject($params["gd_status"])."')  ";
		}

		if(isset($params["ignore_idx"]) && $params["ignore_idx"]!=""){
			$where[] = 	" and aa.gd_idx !='".$this->inject($params["ignore_idx"])."'  ";
		}

		if($params["gd_cate1"]!="" || $params["gd_cate2"]!="" || $params["gd_cate3"]!=""){
			$where[] = 	" and ct.ct_idx !=''  ";
		}

		if(isset($params["st_admin_type"]) && ($params["st_admin_type"]=="store")){
			$where[] = " and aa.st_admin_type = 'store' ";
		}

		$where[] = " and aa.gd_type = '' and aa.gd_stock_yn = 'Y' ";

		//정렬
		$order_by = "aa.gd_sort desc,aa.gd_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

	//상품 테이블 조회
	function getGoods($where){
   		return $this->db_row("st_goods",$where);
	}

	//상품 리스트 조회
	function getGoodsSimpleList($where){
		return $this->db_list("st_goods",$where);
	}

	//아이콘 테이블 조회
	function getGoodsDetail($idx,$margin_type){
		$mm_idx = $this->user->getUserIdx();
   		$sql = "
   			select aa.*
   				,cp.gc_name
   				,cp.gc_won
   				,cp.gc_won_type
   				,(case when gd_stock_yn='Y' and gd_stock = 0 then 'Y' else 'N' end) as sold_out
   				,(case when gd_stock_yn='Y' and gg_stock = 0 then 'Y' else 'N' end) as option_sold_out
   			FROM vw_goods aa
				left join (
					 select aa.gc_idx
					        ,aa.gc_name
					        ,aa.gc_won
					        ,aa.gc_won_type
					        ,bb.gd_idx
					 FROM (
						  select aa.gc_idx
						        ,aa.gc_name
						        ,aa.gc_won
						        ,aa.gc_won_type
						  FROM
						  st_goods_coupon aa
						    left join st_goods_coupon_member bb
						        on bb.gc_idx = bb.gc_idx
						  where aa.gc_sdate <=now() and gc_edate >= now()
						  and (aa.gc_mem_type in ('','$margin_type') or (aa.gc_mem_type = 'SELECT' and aa.gc_idx = bb.gc_idx and bb.mm_idx = '$mm_idx'))
						  group by aa.gc_idx
					) aa inner join st_goods_coupon_list bb
					  on aa.gc_idx = bb.gc_idx
				) cp on aa.gd_idx = cp.gd_idx
				left join (
					SELECT gd_idx,sum(gg_stock) as gg_stock
					FROM `st_goods_option_list`
					group by gd_idx
				) dd on aa.gd_idx = dd.gd_idx
			where aa.gd_idx = '$idx'

		";

		$result = $this->db->query($sql);
		return $result->row_array();
	}

	//상품  저장
	function insertGoods($data){
		$result = $this->db_insert("st_goods",$data);
		return $result;
	}

	//상품  수정
	function updateGoods($data,$where){
		return $this->db_update("st_goods",$data,$where);
	}

	//상품  삭제
	function deleteGoods($where){
		return $this->db_delete("st_goods",$where);
	}

	//상품  삭제
	function deleteCrontabTemp($where){
		return $this->db_delete("st_crontab_temp",$where);
	}


	//상품  수정
	function updateGoodsOptionList($data,$where){
		return $this->db_update("st_goods_option_list",$data,$where);
	}

	//상품 좋아요 처리.
	function setGoodsWish($params){
		$row = $this->db_row("st_goods_wish",["gd_idx"=>$params["gd_idx"],"mm_idx"=>$params["mm_idx"]]);

		if($row!=null){
			$this->db_delete("st_goods_wish",["gd_idx"=>$params["gd_idx"],"mm_idx"=>$params["mm_idx"]]);
			return "N";
		}else{
			$this->db_insert("st_goods_wish",["gd_idx"=>$params["gd_idx"],"mm_idx"=>$params["mm_idx"]]);
			return "Y";
		}
	}

	//상품 좋아요 처리.
	function deleteGoodsWish($params){
		$this->db_delete("st_goods_wish",["gd_idx"=>$params["gd_idx"],"mm_idx"=>$params["mm_idx"]]);
	}

	function getGoodsWishYn($params){
		$row = $this->db_is_data("st_goods_wish",["gd_idx"=>$params["gd_idx"],"mm_idx"=>$params["mm_idx"]]);
		if($row==true){
			return "Y";
		}else{
			return "N";
		}
	}

	//옵션 리스트 조회
	function getGoodsNoticeList($group)
	{
		//카운터쿼리 생성
		$sql_data = "
			select cd_val,cd_val2
			FROM st_code
			where cd_grp = 'goods_notice'
			and cd_val = '$group'
		";

		$result = $this->db->query($sql_data);
		return $result->result_array();
   	}

   	//상품 아이콘 조회
	function getGoodsIconUsedList($where)
	{
		return $this->db_list("st_goods_icon",$where,"*",["gi_sort"=>"desc"]);
   	}

   	//상품 선택 아이콘 리스트
	function insertGoodsSelIcon($data){
		$result = $this->db_insert("st_goods_sel_icon",$data);
		return $result;
	}

   	//상품 선택 아이콘 리스트 삭제
	function deleteGoodsSelIcon($where){
		return $this->db_delete("st_goods_sel_icon",$where);
	}

   	//상품제품 재공고지 정보 등록
	function insertGoodsDetailList($data){
		$result = $this->db_insert("st_goods_detail_list",$data);
		return $result;
	}

	//상품제품 재공고지 정보 삭제
	function deleteGoodsDetailList($where){
		return $this->db_delete("st_goods_detail_list",$where);
	}

	//상품 옵션 그룹 등록
	function insertGoodsOptionGroup($data){
		$result = $this->db_insert("st_goods_option_group",$data);
		return $result;
	}

   	//상품 옵션 그룹 삭제
	function deleteGoodsOptionGroup($where){
		return $this->db_delete("st_goods_option_group",$where);
	}

	//상품 옵션 리스트 등록
	function insertGoodsOptionList($data){
		$result = $this->db_insert("st_goods_option_list",$data);
		return $result;
	}

   	//상품 옵션 리스트 삭제
	function deleteGoodsOptionList($where){
		return $this->db_delete("st_goods_option_list",$where);
	}

	//상품 카테고리 등록
	function insertGoodsCategory($data){
		$result = $this->db_insert("st_goods_category",$data);
		return $result;
	}

   	//상품 옵션 리스트 삭제
	function deleteGoodsCategory($where){
		return $this->db_delete("st_goods_category",$where);
	}

	function deleteGoodsRelGoods($where){
		return $this->db_delete("st_goods_rel_goods",$where);
	}

	//관련상품 등록
	function insertRelGoods($data){
		$result = $this->db_insert("st_goods_rel_goods",$data);
		return $result;
	}

	//등록데이터 가져오기
	function getReigstIcons($where){
		return $this->db_list("st_goods_sel_icon",$where);
	}

	//등록데이터 가져오기
	function getReigstCategorys($gd_idx){
		$sql = "
			SELECT CONCAT(ct_depth1_name
			              ,(case when ct_depth2_name ='' then '' else CONCAT(' > ',ct_depth2_name)  end)
			              ,(case when ct_depth3_name ='' then '' else CONCAT(' > ',ct_depth3_name)  end)
			             ) as cate_name
			             ,bb.gd_idx
			             ,bb.ct_idx
			FROM `st_category` aa
				inner join st_goods_category bb
			    	on aa.ct_idx = bb.ct_idx
			where bb.gd_idx = '$gd_idx'
			order by bb.ct_idx asc
		";

		$result = $this->db->query($sql);
		return $result->result_array();
	}

	//카테고리 리스트 심플
	function getCategoryList($where){
		return $this->db_list("st_category",$where,"ct_idx,ct_name",["ct_sort"=>"asc"]);
	}

	//카테고리 리스트 전체
	function getCategoryListAll($where){
		return $this->db_list("st_category",$where);
	}

	//등록데이터 가져오기
	function getReigstOptions($where){
		return $this->db_list("st_goods_option_list",$where);
	}

	//등록데이터 가져오기
	function getReigstAddOptions($where){
		return $this->db_list("st_goods_option_list",$where);
	}

	//등록데이터 가져오기
	function getReigstOptionGroups($where){
		return $this->db_list("st_goods_option_group",$where);
	}

	//등록데이터 가져오기
	function getReigstAddOptionGroups($where){
		return $this->db_list("st_goods_option_group",$where);
	}

	//등록데이터 가져오기
	function getReigstDetails($where){
		return $this->db_list("st_goods_detail_list",$where);
	}

	//등록데이터 가져오기
	function getRegistRelGoodsList($gd_idx,$type="",$sub_type = ""){
		$mm_idx = $this->user->getUserIdx();
		$join_data = "";
		$add_column = "";
		if($type=="rel_goods"){
			$add_column = "
				,cp.gc_name
				,cp.gc_won
				,cp.gc_won_type
				,(case when gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and gg_stock = 0)) then 'Y' else 'N' end) as sold_out
			";
			$join_data = "
				inner join st_goods_rel_goods bb
					on aa.gd_idx = bb.rel_gd_idx
				left join st_goods_wish gw
        			on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
        		left join (
					select
					     aa.gc_name
					    ,aa.gc_won
					    ,aa.gc_won_type
					    ,bb.gd_idx
					FROM st_goods_coupon aa
					    inner join st_goods_coupon_list bb
					        on aa.gc_idx = bb.gc_idx
					    inner join (
					        SELECT gd_idx,max(aa.gc_idx) as gc_idx
					        FROM st_goods_coupon aa
					            inner join st_goods_coupon_list bb
					                on aa.gc_idx = bb.gc_idx
					        where aa.gc_sdate <=now() and gc_edate >= now()
					        group by gd_idx
					    ) cc
				     on bb.gc_idx = bb.gc_idx and bb.gd_idx = cc.gd_idx and aa.gc_idx = cc.gc_idx
				) cp on aa.gd_idx = cp.gd_idx
				left join (
				    SELECT gd_idx,sum(gg_stock) as gg_stock
				    FROM `st_goods_option_list`
				    group by gd_idx
				) dd on aa.gd_idx = dd.gd_idx
				where bb.gd_idx = '$gd_idx'
				group by aa.gd_idx
				order by bb.rg_sort asc,bb.rg_idx desc
			";
		}else if($type=="display"){
			$join_data = "
				inner join st_display_goods bb
					on aa.gd_idx = bb.gd_idx
				left join st_goods_wish gw
        			on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
				where bb.ds_idx = '$gd_idx'
				and bb.ds_type = '$sub_type'
				group by aa.gd_idx
				order by bb.pg_sort asc,bb.pg_idx desc
			";
		}else if($type=="plan"){
			$join_data = "
				inner join st_plan_goods bb
					on aa.gd_idx = bb.gd_idx
				left join st_goods_wish gw
        			on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
				where bb.pl_idx = '$gd_idx'
				group by aa.gd_idx
				order by bb.pg_sort asc,bb.pg_idx desc
			";
		}else if($type=="plan_category"){
			$join_data = "
				inner join st_plan_goods bb
					on aa.gd_idx = bb.gd_idx
				left join st_goods_wish gw
        			on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
				where bb.pc_idx = '$gd_idx'
				and bb.pc_idx <> 0
				group by aa.gd_idx
				order by bb.pg_sort asc,bb.pg_idx desc
			";
		}else if($type=="estimate"){
			$join_data = "
				inner join st_estimate_goods bb
					on aa.gd_idx = bb.gd_idx
				left join st_goods_wish gw
        			on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
				where bb.em_idx = '$gd_idx'
				and bb.em_idx <> 0
				order by bb.eg_idx asc
			";
		}else if($type=="coupon"){
			$join_data = "
				inner join st_goods_coupon_list bb
					on aa.gd_idx = bb.gd_idx
				left join st_goods_wish gw
        			on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
				where bb.gc_idx = '$gd_idx'
				group by aa.gd_idx
				order by bb.cl_idx asc
			";
		}else if($type=="banner"){
			$join_data = "
				inner join st_banner_goods bb
					on aa.gd_idx = bb.gd_idx
				left join st_goods_wish gw
        			on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
				where bb.bn_idx = '$gd_idx'
				group by aa.gd_idx
				order by bb.bg_idx asc
			";
		}

		$sql = "
			SELECT aa.gd_idx
				,aa.show_yn
				,aa.gd_name
				,aa.gd_list_name
				,aa.gd_sub_name
				,aa.gd_code
				,aa.gd_name
				,aa.gd_s_price
				,aa.gd_p_price
				,aa.gd_i_price
				,aa.gd_img_type
				,aa.gd_img_main
				,aa.gd_img_list
				,aa.gd_img_main_url
				,aa.gd_img_list_url
				,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
				$add_column
			FROM vw_goods aa
			$join_data
		";

		$result = $this->db->query($sql);
		return $result->result_array();
	}

	///////////////////////////////////////////////////////////////////////////
	// 아이콘 처리
	///////////////////////////////////////////////////////////////////////////

	//아이콘  리스트
	function getGoodsIconList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
		];

		//바디
		$body = "
			st_goods_icon aa
		";

		//조건
		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (gi_desc like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="title"){
				$where[] = 	" and (gi_desc like '%".$this->inject($params["search_value"])."%')  ";
			}
		}

		//정렬
		$order_by = "aa.gi_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

	//아이콘 테이블 조회
	function getGoodsIcon($where){
   		return $this->db_row("st_goods_icon",$where);
	}

	//아이콘  저장
	function insertGoodsIcon($data){
		$result = $this->db_insert("st_goods_icon",$data);
		return $result;
	}

	//아이콘  수정
	function updateGoodsIcon($data,$where){
		return $this->db_update("st_goods_icon",$data,$where);
	}

	//아이콘  삭제
	function deleteGoodsIcon($where){
		return $this->db_delete("st_goods_icon",$where);
	}

	///////////////////////////////////////////////////////////////////////////
	// 옵션 처리
	///////////////////////////////////////////////////////////////////////////

	//옵션그룹 리스트
	function getOptionGroupList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
		];

		//바디
		$body = "
			st_option_group aa
		";

		//조건
		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (og_name like '%".$this->inject($params["search_value"])."%' or og_desc like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="title"){
				$where[] = 	" and (og_name like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="desc"){
				$where[] = 	" and (og_desc like '%".$this->inject($params["search_value"])."%')  ";
			}
		}

		//정렬
		$order_by = "aa.og_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

   	//옵션그룹 리스트
   	function getOptionGroupAllList($where){
   		return $this->db_list("st_option_group",$where,"*",["og_name"=>"asc"]);
   	}

	//옵션 그룹 조회
	function getOptionGroup($where){
   		return $this->db_row("st_option_group",$where);
	}

	//옵션그룹  저장
	function insertOptionGroup($data){
		$result = $this->db_insert("st_option_group",$data);
		return $result;
	}

	//옵션그룹  수정
	function updateOptionGroup($data,$where){
		return $this->db_update("st_option_group",$data,$where);
	}

	//옵션그룹 삭제
	function deleteOptionGroup($where){
		return $this->db_delete("st_option_group",$where);
	}

	//옵션 리스트 조회
	function getOptionListList($where)
	{
   		return $this->db_list("st_option_list",$where,"*",["ol_idx"=>"asc"]);
   	}

	//옵션그룹  저장
	function insertOptionList($data){
		$result = $this->db_insert("st_option_list",$data);
		return $result;
	}

	//옵션그룹  수정
	function deleteOptionList($where){
		return $this->db_delete("st_option_list",$where);
	}

	///////////////////////////////////////////////////////////////////////////
	// 배송비 그룹 처리
	///////////////////////////////////////////////////////////////////////////

	//배송비 리스트
	function getDeliveryList($params,$orderby = "aa.dv_idx desc")
	{
		//컬럼
		$cols = [
			"aa.*",
			"(select cd_val FROM st_code bb where bb.cd_grp = 'dv_type' and bb.cd_key = aa.dv_type limit 0,1) as dv_type_nm",
		];

		//바디
		$body = "
			st_delivery aa
		";

		//조건
		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (dv_name like '%".$this->inject($params["search_value"])."%' )  ";
			}else if($params["search_type"]=="title"){
				$where[] = 	" and (dv_name like '%".$this->inject($params["search_value"])."%')  ";
			}
		}

		//정렬
		$order_by = $orderby;

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

	//배송비 조회
	function getDeliveryInfo($where){
   		return $this->db_row("st_delivery",$where);
	}

	//배송비 조회
	function getDeliveryInfoV2($dv_idx){
   		//카운터쿼리 생성
		$sql = "
			select aa.*
				,(select cd_val FROM st_code bb where bb.cd_grp = 'dlv_code' and bb.cd_key = aa.dv_code) as dlv_name
			FROM st_delivery aa
			where dv_idx = '$dv_idx'
		";

		$query = $this->db->query($sql);
		$info = $query->row_array();

		//상세 리스트 가져오기
		if($info["dv_type"]=="40" || $info["dv_type"]=="50" || $info["dv_type"]=="60"){
			//카운터쿼리 생성
			$sql = "
				select aa.*
				FROM st_delivery_dtl aa
				where dv_idx = '$dv_idx'
			";

			$query = $this->db->query($sql);
			$info["list"] = $query->result_array();
		}

		return $info;
	}

	//배송비  저장
	function insertDeliveryInfo($data){
		$result = $this->db_insert("st_delivery",$data);
		return $result;
	}

	//배송비  수정
	function updateDeliveryInfo($data,$where){
		return $this->db_update("st_delivery",$data,$where);
	}

	//배송비 삭제
	function deleteDeliveryInfo($where){
		return $this->db_delete("st_delivery",$where);
	}

	//배송비 기본설정 제거
	function updateDeliveryDefaultRemove(){
		return $this->db_update("st_delivery",["dv_default_yn"=>"N"],[]);
	}

	//배송비 추가정보 조회
	function getDeliveryDtlList($where)
	{
   		return $this->db_list("st_delivery_dtl",$where,"*",["dv_idx"=>"asc"]);
   	}

	//배송비 추가정보 삭제
	function deleteDeliveryDtl($where){
		return $this->db_delete("st_delivery_dtl",$where);
	}

	//배송비 추가정보 저장
	function insertDeliveryDtl($data){
		$result = $this->db_insert("st_delivery_dtl",$data);
		return $result;
	}

	///////////////////////////////////////////////////////////////////////////
	// 이용안내 처리
	///////////////////////////////////////////////////////////////////////////

	//이용안내 리스트
	function getTemplateGuideList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
		];

		//바디
		$body = "
			st_template_guide aa
		";

		//조건
		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (tg_subject like '%".$this->inject($params["search_value"])."%' )  ";
			}else if($params["search_type"]=="title"){
				$where[] = 	" and (tg_subject like '%".$this->inject($params["search_value"])."%')  ";
			}
		}

		//정렬
		$order_by = "aa.tg_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

	//배송비 조회
	function getTemplateGuide($where){
   		return $this->db_row("st_template_guide",$where);
	}

	//배송비  저장
	function insertTemplateGuide($data){
		$result = $this->db_insert("st_template_guide",$data);
		return $result;
	}

	//배송비  수정
	function updateTemplateGuide($data,$where){
		return $this->db_update("st_template_guide",$data,$where);
	}

	//배송비 삭제
	function deleteTemplateGuide($where){
		return $this->db_delete("st_template_guide",$where);
	}

	//배송비 기본설정 제거
	function updateTemplateGUideDefaultRemove(){
		return $this->db_update("st_template_guide",["tg_default_yn"=>"N"],[]);
	}


	///////////////////////////////////////////////////////////////////////////
	// 브랜드 처리
	///////////////////////////////////////////////////////////////////////////

	//브랜드 리스트
	function getBrandList($params,$orderby = "aa.bd_idx desc")
	{
		//컬럼
		$cols = [
			"aa.*",
		];

		//바디
		$body = "
			st_brand aa
		";

		//조건
		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (bd_name like '%".$this->inject($params["search_value"])."%' )  ";
			}else if($params["search_type"]=="title"){
				$where[] = 	" and (bd_name like '%".$this->inject($params["search_value"])."%')  ";
			}
		}

		//정렬
		$order_by = $orderby;

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

	function getBrandListAll($where)
	{
		return $this->db_list("st_brand",$where);
   	}


	//브랜드 리스트 가져오기
	function getBrandListToGoods($ct_idx = 0,$ct_idx_depth = 0,$keyword = ""){
		$db = DB('default', true);

		$keyword = str_replace(" ","",str_replace("-","",strtolower($keyword)));
		$where = "";

		if($ct_idx > 0){
			if($ct_idx_depth==1){
				$where = " and dd.ct_depth1_idx = ".$this->inject($ct_idx)."";
			}else if($ct_idx_depth==2){
				$where = " and dd.ct_depth2_idx = ".$this->inject($ct_idx)."";
			}else if($ct_idx_depth==3){
				$where = " and dd.ct_depth3_idx = ".$this->inject($ct_idx)."";
			}
		}

		if(isset($keyword) && $keyword!=""){
			$where .= " and (REPLACE(REPLACE(LOWER(gd_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_sub_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_list_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_keyword),'-',''),' ','') like '%".$this->inject($keyword)."%'  or REPLACE(REPLACE(LOWER(bd_name),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
		}


		$sql = "
			select aa.bd_idx,aa.bd_name
			,count(*) as goods_cnt
			FROM (
				select bb.gd_idx,aa.bd_idx,aa.bd_name
				FROM st_brand aa
					inner join vw_goods bb
						on aa.bd_idx = bb.bd_idx
					left join st_goods_category cc
						on bb.gd_idx = cc.gd_idx
					left join st_category dd
						on cc.ct_idx = dd.ct_idx
				where bb.gd_status = 'Y' and bb.gd_type = ''
				$where
				group by bb.gd_idx,aa.bd_idx,aa.bd_name
			) aa group by aa.bd_idx,aa.bd_name
			order by count(*) desc,aa.bd_name asc
		";

		$tmp_result = $db->query($sql);
		$brand_list = $tmp_result->result_array();

		return $brand_list;
	}
	//브랜드 조회
	function getBrandInfo($where){
   		return $this->db_row("st_brand",$where);
	}

	//브랜드  저장
	function insertBrandInfo($data){
		//중복체크
		$is_dup = $this->db_is_data("st_brand",["bd_name"=>$data["bd_name"]]);

		if($is_dup){
			return ['code'=>'90','msg'=>'이미 등록된 브랜드가 있습니다.','key'=>''];
		}

		$result = $this->db_insert("st_brand",$data);
		return $result;
	}

	//브랜드  수정
	function updateBrandInfo($data,$where){
		$is_dup = $this->db_is_data("st_brand",["bd_name"=>$data["bd_name"],"bd_idx !=" =>$data["bd_idx"]]);

		if($is_dup){
			return ['code'=>'90','msg'=>'이미 등록된 브랜드가 있습니다.','key'=>''];
		}


		return $this->db_update("st_brand",$data,$where);
	}

	//브랜드  수정
	function updateBrandInfoV2($data,$where){
		return $this->db_update("st_brand",$data,$where);
	}

	//브랜드 삭제
	function deleteBrandInfo($where){
		return $this->db_delete("st_brand",$where);
	}

	//브랜드 기본설정 제거
	function updateBrandDefaultRemove(){
		return $this->db_update("st_brand",["bd_default_yn"=>"N"],[]);
	}


	///////////////////////////////////////////////////////////////////////////
	// 카테고리 처리
	///////////////////////////////////////////////////////////////////////////

	//카테고리 조회
	function getCategory($where){
		return $this->db_row("st_category",$where);
	}

	//카테고리 조회
	function getCategorySpc($where){
		$sql = "
			SELECT min(ct_idx) as ct_idx FROM `st_category` WHERE reg_dt >='2022-07-11 11:00:00'
		";
		$result = $this->db->query($sql);
		return $result->row_array();
	}

	//카테고리 추가
	function insertCategory($data){
		$result = $this->db_insert("st_category",$data);

		$ct_depth1_idx = "";
		$ct_depth2_idx = "";
		$ct_depth3_idx = "";
		$ct_depth1_name = "";
		$ct_depth2_name = "";
		$ct_depth3_name = "";

		//1뎁스 이상이면 부모 댑스 가져와서 경로및 키값 설정한다.
		if($data["ct_depth"]=="1"){
			$ct_depth1_idx = $result["key"];
			$ct_depth1_name = $data["ct_name"];
		}else if($data["ct_depth"]=="2"){
			$ct_info_1 = $this->getCategory(["ct_idx"=>$data["ct_parent"]]);
			$ct_depth1_idx = $ct_info_1["ct_idx"];
			$ct_depth1_name = $ct_info_1["ct_name"];
			$ct_depth2_idx = $result["key"];
			$ct_depth2_name = $data["ct_name"];
		}else if($data["ct_depth"]=="3"){
			$ct_info_2 = $this->getCategory(["ct_idx"=>$data["ct_parent"]]);
			$ct_info_1 = $this->getCategory(["ct_idx"=>$ct_info_2["ct_parent"]]);
			$ct_depth1_idx = $ct_info_1["ct_idx"];
			$ct_depth1_name = $ct_info_1["ct_name"];
			$ct_depth2_idx = $ct_info_2["ct_idx"];
			$ct_depth2_name = $ct_info_2["ct_name"];
			$ct_depth3_idx = $result["key"];
			$ct_depth3_name = $data["ct_name"];
		}

		//등록후 정렬순서 업데이트
		$this->db_update("st_category",[
			"ct_sort"=>$result["key"],
			"ct_depth1_idx"=>$ct_depth1_idx,
			"ct_depth2_idx"=>$ct_depth2_idx,
			"ct_depth3_idx"=>$ct_depth3_idx,
			"ct_depth1_name"=>$ct_depth1_name,
			"ct_depth2_name"=>$ct_depth2_name,
			"ct_depth3_name"=>$ct_depth3_name,
		],["ct_idx"=>$result["key"]]);
		return $result;
	}

	//카테고리 수정
	function updateCategory($data,$where){
		$result = $this->db_update("st_category",$data,$where);

		$ct_depth1_idx = "";
		$ct_depth2_idx = "";
		$ct_depth3_idx = "";
		$ct_depth1_name = "";
		$ct_depth2_name = "";
		$ct_depth3_name = "";

		//1뎁스 이상이면 부모 댑스 가져와서 경로및 키값 설정한다.
		if($data["ct_depth"]=="1"){
			$ct_depth1_idx = $data["ct_idx"];
			$ct_depth1_name = $data["ct_name"];
		}else if($data["ct_depth"]=="2"){
			$ct_info_1 = $this->getCategory(["ct_idx"=>$data["ct_parent"]]);
			$ct_depth1_idx = $ct_info_1["ct_idx"];
			$ct_depth1_name = $ct_info_1["ct_name"];
			$ct_depth2_idx = $data["ct_idx"];
			$ct_depth2_name = $data["ct_name"];
		}else if($data["ct_depth"]=="3"){
			$ct_info_2 = $this->getCategory(["ct_idx"=>$data["ct_parent"]]);
			$ct_info_1 = $this->getCategory(["ct_idx"=>$ct_info_2["ct_parent"]]);
			$ct_depth1_idx = $ct_info_1["ct_idx"];
			$ct_depth1_name = $ct_info_1["ct_name"];
			$ct_depth2_idx = $ct_info_2["ct_idx"];
			$ct_depth2_name = $ct_info_2["ct_name"];
			$ct_depth3_idx = $data["ct_idx"];
			$ct_depth3_name = $data["ct_name"];
		}

		//등록후 정렬순서 업데이트
		$this->db_update("st_category",[
			"ct_depth1_idx"=>$ct_depth1_idx,
			"ct_depth2_idx"=>$ct_depth2_idx,
			"ct_depth3_idx"=>$ct_depth3_idx,
			"ct_depth1_name"=>$ct_depth1_name,
			"ct_depth2_name"=>$ct_depth2_name,
			"ct_depth3_name"=>$ct_depth3_name,
		],["ct_idx"=>$data["ct_idx"]]);

		if($data["ct_depth"]=="1"){
			$this->db_update("st_category",[
				"ct_depth1_name"=>$ct_depth1_name,
				"up_dt"=>get_today(),
			],["ct_parent"=>$data["ct_idx"],"ct_depth"=>"2"]);

			$this->db_update("st_category",[
				"ct_depth1_name"=>$ct_depth1_name,
				"up_dt"=>get_today(),
			],["ct_parent"=>$data["ct_idx"],"ct_depth"=>"3"]);

		}else if($data["ct_depth"]=="2"){
			$this->db_update("st_category",[
				"ct_depth2_name"=>$ct_depth2_name,
				"up_dt"=>get_today(),
			],["ct_parent"=>$data["ct_idx"],"ct_depth"=>"3"]);
		}

		return $result;
	}

	function updateCategoryV2($data,$where){
		//등록후 정렬순서 업데이트
		return $this->db_update("st_category",$data,$where);
	}

	//카테고리 삭제
	function deleteCategory($where){
		return $this->db_delete("st_category",$where);
	}

	//카테고리 리스트 조회
	function getCategoryDepthList($params)
	{
		//컬럼
		$cols = ["*"];

		//바디
		$body = "st_category";

		//조건
		$where = [];
		$where[] = 	" and del_yn = 'N' ";
		$where[] = 	" and (ct_depth = '".$this->inject($params["depth"])."')";
		if($params["depth"]!="1"){
			$where[] = 	" and (ct_parent = '".$this->inject($params["parent_id"])."')";
		}

		//정렬
		$order_by = "ct_sort asc";

   		return $this->db_select_list($cols,$body,$where,$order_by);
   	}

	//카테고리 리스트 조회
	function getCategoryAllList()
	{
		//컬럼
		$cols = ["ct_idx,ct_depth,ct_parent,ct_name"];

		//바디
		$body = "st_category";

		//조건
		$where = [];
		$where[] = 	" and del_yn = 'N' ";

		//정렬
		$order_by = "ct_sort asc";

   		return $this->db_select_list($cols,$body,$where,$order_by);
   	}

	////////////////////////////////////////////////////////////////////
	// 프론트 사용
	////////////////////////////////////////////////////////////////////

	//테이블 조회
	function getTable($idx,$tb_id = ""){
		//컬럼
		$cols = [
			"aa.*"
		];

		//바디
		$body = "
			st_table aa
		";

		//조건
		if($tb_id!=""){
			$where[] = " and (tb_id = '".$this->inject($tb_id)."')  ";
		}else{
			$where[] = " and (tb_idx = '".$this->inject($idx)."')  ";
		}

		//정렬
		$order_by = "aa.tb_idx desc";

   		return $this->db_select_row($cols,$body,$where,$order_by);
	}

	//상세화면 관련상품 가져오기
	//@TODO : 좀더 수정해야함
	function getAutoRelGoods($info,$rel_type){
		$mm_idx = $this->user->getUserIdx();
		$gd_idx = $info["gd_idx"];

		if($info["gd_rel_type"]=="none"){
			return [];
		}else if($info["gd_rel_type"]=="manual"){
			$list = $this->getRegistRelGoodsList($info["gd_idx"],"rel_goods");
			return $list;
		}else{
			//자동설정 정보 가져오기

			if($rel_type=="cate_fav" || $rel_type=="cate_price" || $rel_type=="cate_date"){
				$where[] = " and (gd_idx = '".$this->inject($info["gd_idx"])."')  ";
				$cate_info = $this->db_select_row(["aa.*"],"st_goods_category aa",$where,"reg_dt desc");

				if($cate_info==null){
					return [];
				}

				$ct_idx = $cate_info["ct_idx"];

				$sql = "
					select aa.*
					,cp.gc_name
					,cp.gc_won
					,cp.gc_won_type
					,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
					,(case when gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and gg_stock = 0)) then 'Y' else 'N' end) as sold_out
					FROM vw_goods aa
						inner join (select * FROM st_goods_category where ct_idx = '$ct_idx') bb
							on aa.gd_idx = bb.gd_idx
						left join st_goods_wish gw
        					on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
	        			left join (
						    SELECT gd_idx,sum(gg_stock) as gg_stock
						    FROM `st_goods_option_list`
						    group by gd_idx
						) dd on aa.gd_idx = dd.gd_idx
						left join (
							select
							     aa.gc_name
							    ,aa.gc_won
							    ,aa.gc_won_type
							    ,bb.gd_idx
							FROM st_goods_coupon aa
							    inner join st_goods_coupon_list bb
							        on aa.gc_idx = bb.gc_idx
							    inner join (
							        SELECT gd_idx,max(aa.gc_idx) as gc_idx
							        FROM st_goods_coupon aa
							            inner join st_goods_coupon_list bb
							                on aa.gc_idx = bb.gc_idx
							        where aa.gc_sdate <=now() and gc_edate >= now()
							        group by gd_idx
							    ) cc
						     on bb.gc_idx = bb.gc_idx and bb.gd_idx = cc.gd_idx and aa.gc_idx = cc.gc_idx
						) cp on aa.gd_idx = cp.gd_idx
					where aa.gd_status = 'Y' and aa.gd_idx !='$gd_idx'
					group by aa.gd_idx
					order by aa.reg_dt desc
					limit 0,10
				";

				$result = $this->db->query($sql);
				return $result->result_array();
			}else{
				$bd_idx = $info["bd_idx"];

				$sql = "
					select aa.*
						,cp.gc_name
						,cp.gc_won
						,cp.gc_won_type
						,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
					  	,(case when gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and gg_stock = 0)) then 'Y' else 'N' end) as sold_out
					FROM vw_goods aa
					left join st_goods_wish gw
        				on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
        			left join (
					    SELECT gd_idx,sum(gg_stock) as gg_stock
					    FROM `st_goods_option_list`
					    group by gd_idx
					) dd on aa.gd_idx = dd.gd_idx
					left join (
						select
						     aa.gc_name
						    ,aa.gc_won
						    ,aa.gc_won_type
						    ,bb.gd_idx
						FROM st_goods_coupon aa
						    inner join st_goods_coupon_list bb
						        on aa.gc_idx = bb.gc_idx
						    inner join (
						        SELECT gd_idx,max(aa.gc_idx) as gc_idx
						        FROM st_goods_coupon aa
						            inner join st_goods_coupon_list bb
						                on aa.gc_idx = bb.gc_idx
						        where aa.gc_sdate <=now() and gc_edate >= now()
						        group by gd_idx
						    ) cc
					     on bb.gc_idx = bb.gc_idx and bb.gd_idx = cc.gd_idx and aa.gc_idx = cc.gc_idx
					) cp on aa.gd_idx = cp.gd_idx
					where aa.gd_status = 'Y' and aa.gd_idx !='$gd_idx'
					and aa.bd_idx = '$bd_idx'
					group by aa.gd_idx
					order by aa.reg_dt desc
					limit 0,10
				";

				$result = $this->db->query($sql);
				return $result->result_array();
			}
		}
	}

	//옵션 그룹 조회
	function getDetailOptionGroup($gd_idx){
		$stmt = $this->db
			->select("gg_depth,gg_group_name1,gg_group_name2,gg_group_name3")
			->from('st_goods_option_list aa')
			->where("aa.gd_idx", $gd_idx)
			->where("aa.gg_type", "default")
			->where("aa.gg_use_yn", "Y")
			->group_by("gg_depth,gg_group_name1,gg_group_name2,gg_group_name3")
			->get();

		$group_info = $stmt->row_array();

		$return_val = null;
		if($group_info!=null){
			for($icnt=1;$icnt <= $group_info["gg_depth"];$icnt++){
				$return_val[] = [
					"group_name"=>$group_info["gg_group_name" . $icnt],
				];
			}
		}

		return $return_val;
	}

	//옵션 depth 리스트 조회
	function getDetailOptionDepthList($gd_stock_yn,$gd_idx,$depth =1,$last_depth =1, $gg_name1="",$gg_name2="",$gg_name3="",$add_type=""){
		$stmt = null;
		$is_last = ($depth ==$last_depth ? "Y":"N");

		if($add_type=="작업복로고"){
			$is_last = "N";
		}

		if ($depth==1) {
			$this->db
				->select("'$gd_stock_yn' as gd_stock_yn, gg_name1 as gg_name,gg_name1 as last_name,gg_p_price,gg_add_price,gg_stock,max(gl_idx) as gl_idx,'$is_last' as last_yn")
				->from('st_goods_option_list aa')
				->join('st_goods bb','aa.gd_idx = bb.gd_idx')
				->where("aa.gd_idx", $gd_idx)
				->where("aa.gg_type", "default")
				->where("aa.gg_use_yn", "Y");

			if($gd_stock_yn=="Y"){
				$this->db->where(["aa.gg_stock >"=>"0"]);
			}
			$stmt = $this->db->group_by("aa.gg_name1")->order_by("aa.gl_idx", "asc")->get();

		}else if ($depth==2) {
			$this->db
				->select("'$gd_stock_yn' as gd_stock_yn, gg_name2 as gg_name,concat(gg_name1 ,' / ' ,gg_name2) as last_name,gg_p_price,gg_add_price,gg_stock,max(gl_idx) as gl_idx,'$is_last' as last_yn")
				->from('st_goods_option_list aa')
				->join('st_goods bb','aa.gd_idx = bb.gd_idx')
				->where("aa.gd_idx", $gd_idx)
				->where("aa.gg_type", "default")
				->where("aa.gg_use_yn", "Y")
				->where("aa.gg_name1", $gg_name1);

				if($gd_stock_yn=="Y"){
					$this->db->where(["aa.gg_stock >"=>"0"]);
				}
				$stmt = $this->db->group_by("aa.gg_name2")->order_by("aa.gl_idx", "asc")->get();
		}else if ($depth==3) {
			$this->db
				->select("'$gd_stock_yn' as gd_stock_yn, gg_name3 as gg_name,concat(gg_name1 ,' / ' ,gg_name2,' / ' ,gg_name3) as last_name,gg_p_price,gg_add_price,gg_stock,max(gl_idx) as gl_idx,'$is_last' as last_yn")
				->from('st_goods_option_list aa')
				->join('st_goods bb','aa.gd_idx = bb.gd_idx')
				->where("aa.gd_idx", $gd_idx)
				->where("aa.gg_type", "default")
				->where("aa.gg_use_yn", "Y")
				->where("aa.gg_name1", $gg_name1)
				->where("aa.gg_name2", $gg_name2);

				if($gd_stock_yn=="Y"){
					$this->db->where(["aa.gg_stock >"=>"0"]);
				}
				$stmt = $this->db->group_by("aa.gg_name2")->order_by("aa.gl_idx", "asc")->get();
		}

		return $stmt->result_array();
	}

	//옵션정보 조회
	function getGoodsOptionInfo($where){
		return $this->db_row("st_goods_option_list",$where);
	}

	//옵션정보 조회
	function getGoodsOptionList($where){
		return $this->db_list("st_goods_option_list",$where,"*",["gl_idx"=>"asc"]);
	}

	//장바구니정보조회
	function getCartInfo($where){
		return $this->db_row("st_cart",$where);
	}

	//장바구니정보조회
	function insertCart($data){
		$result = $this->db_insert("st_cart",$data);
		return $result;
	}

	//장바구니정보조회
	function updateCart($data,$where){
		$result = $this->db_update("st_cart",$data,$where);
		return $result;
	}

	//장바구니정보조회
	function deleteCart($where){
		$result = $this->db_delete("st_cart",$where);
		return $result;
	}

	//장바구니정보조회
	function getCartQty($where){
		return $this->db_count("st_cart",$where);
	}

	//장바구니 리스트
	function getCartGroupList($order_yn,$user_idx,$basic_name,$delivery_type = "",$ct_idxs = ""){
		$setting_goods = $this->common->getGoodsSetting();
		$mm_idx = $this->user->getUserIdx();
		$margin_type = $setting_goods["margin_type"];
		$cart_group = [];
		$add_where = " and bb.gd_status = 'Y' ";
		$add_file_yn = "N";
		$add_file_name = "";
		$box_qty = 0;
		$sum_cm3 = 0;

		if($order_yn=="Y"){
			$add_where = " and aa.ct_order_yn = 'Y' ";
		}

		if($ct_idxs!=""){
			$add_where = " and aa.ct_idx in($ct_idxs) ";
		}

		$sql = "
			select bb.sr_idx
				,(case when bb.sr_idx = 0 then '$basic_name' else cc.sr_name end) as sr_name
			FROM st_cart aa
				inner join st_goods bb
					on aa.gd_idx = bb.gd_idx
				left join st_seller cc
					on bb.sr_idx = cc.sr_idx
				left join st_goods_option_list dd
					on (aa.gd_idx = dd.gd_idx
						and aa.ct_group_name1 = dd.gg_group_name1
						and aa.ct_group_name2 = dd.gg_group_name2
						and aa.ct_group_name3 = dd.gg_group_name3
						and aa.ct_option_name1 = dd.gg_name1
						and aa.ct_option_name2 = dd.gg_name2
						and aa.ct_option_name3 = dd.gg_name3)
			where user_idx = '$user_idx'
			$add_where
			and (
				(bb.gd_option_yn = 'N' and ((bb.gd_stock_yn = 'Y' and bb.gd_stock > 0) or (bb.gd_stock_yn = 'N')))
					or
				(bb.gd_option_yn = 'Y' and dd.gg_use_yn = 'Y' and ((bb.gd_stock_yn = 'Y' and dd.gg_stock > 0 ) or (bb.gd_stock_yn = 'N')))
				)

			group by bb.sr_idx
		";

		$query = $this->db->query($sql);
		$cart_group = $query->result_array();

		$sql_goods = "
			select
				aa.ct_idx,
				bb.gd_p_price,
				bb.gd_s_price,
				bb.gd_i_price,
				bb.gd_p_price as ct_p_price,
				0 as ct_s_price,
				aa.ct_cnt,
				aa.gl_use_yn,
				dd.gl_idx,
				aa.ct_group_name1,
				aa.ct_group_name2,
				aa.ct_group_name3,
				aa.ct_option_name1,
				aa.ct_option_name2,
				aa.ct_option_name3,
				aa.ct_option_add1,
				aa.ct_option_add2,
				aa.ct_option_add3,
				cp.gc_name,
				cp.gc_won,
				cp.gc_won_type,
				bb.*
			FROM st_cart aa
				inner join st_goods bb
					on aa.gd_idx = bb.gd_idx
				left join st_goods_option_list dd
					on (aa.gd_idx = dd.gd_idx
					    and dd.gg_type = 'default'
						and aa.ct_group_name1 = dd.gg_group_name1
						and aa.ct_group_name2 = dd.gg_group_name2
						and aa.ct_group_name3 = dd.gg_group_name3
						and aa.ct_option_name1 = dd.gg_name1
						and aa.ct_option_name2 = dd.gg_name2
						and aa.ct_option_name3 = dd.gg_name3)
				left join (
					 select aa.gc_idx
					        ,aa.gc_name
					        ,aa.gc_won
					        ,aa.gc_won_type
					        ,bb.gd_idx
					 FROM (
						  select aa.gc_idx
						        ,aa.gc_name
						        ,aa.gc_won
						        ,aa.gc_won_type
						  FROM
						  st_goods_coupon aa
						    left join st_goods_coupon_member bb
						        on bb.gc_idx = bb.gc_idx
						  where aa.gc_sdate <=now() and gc_edate >= now()
						  and (aa.gc_mem_type in ('','$margin_type') or (aa.gc_mem_type = 'SELECT' and aa.gc_idx = bb.gc_idx and bb.mm_idx = '$mm_idx'))
						  group by aa.gc_idx
					) aa inner join st_goods_coupon_list bb
					  on aa.gc_idx = bb.gc_idx
				) cp on bb.gd_idx = cp.gd_idx
			where user_idx = '$user_idx' and bb.gd_status = 'Y'
			$add_where
			and (
				(bb.gd_option_yn = 'N' and ((bb.gd_stock_yn = 'Y' and bb.gd_stock > 0) or (bb.gd_stock_yn = 'N')))
					or
				(bb.gd_option_yn = 'Y'  and dd.gg_use_yn = 'Y' and ((bb.gd_stock_yn = 'Y' and dd.gg_stock > 0) or (bb.gd_stock_yn = 'N')))
				)
			order by aa.ct_idx desc
		";

		$query_goods = $this->db->query($sql_goods);
		$cart_goods = $query_goods->result_array();

		$total_cnt = count($cart_goods);
		$total_delivery_price = 0;
		$total_goods_price = 0;
		foreach($cart_group as $group_key=> $group){
			$total_sr_goods_price = 0;
			$total_sr_delivery_price = 0;
			$sql = "
				select bb.sr_idx
					,cc.dv_idx
					,cc.dv_type
					,cc.dv_code
					,cc.dv_price
					,cc.dv_price_v2
					,cc.dv_price_v3
					,cc.dv_price_v4
					,cc.dv_limit_price
					,cc.dv_price
				FROM st_cart aa
					inner join st_goods bb
						on aa.gd_idx = bb.gd_idx
					inner join st_delivery cc
						on bb.dv_idx = cc.dv_idx
				where user_idx = '$user_idx' and bb.gd_status = 'Y'
				$add_where
				group by bb.sr_idx,bb.dv_idx
			";

			$query = $this->db->query($sql);
			$car_delivery = $query->result_array();
			foreach($car_delivery as $delivery_key=> $delivery){
				$cart_group[$group_key]["delivery"][] = $delivery;

				$delivery_price = 0;
				$sum_goods_price = 0;
				$sum_goods_cnt = 0;
				$sum_goods_price = 0;
				$sum_goods_kg = 0;
				$sum_goods_cm3 = 0;
				foreach($cart_goods as $goods_key=> $goods){
					//장바구니 가격처리
					//추가옵션정보 처리
					$add_price = 0;
					if($goods["ct_option_add1"]!="" || $goods["ct_option_add2"]!="" || $goods["ct_option_add3"]!=""){
						$option_add1_info = $this->setting_model->getCodeInfo(["cd_grp"=>"add_option1","cd_val"=>$goods["ct_option_add1"]]);
						$option_add2_info = $this->setting_model->getCodeInfo(["cd_grp"=>"add_option2","cd_val"=>$goods["ct_option_add2"]]);
						$option_add3_info = $this->setting_model->getCodeInfo(["cd_grp"=>"add_option3","cd_val"=>$goods["ct_option_add3"]]);
						$add_price += ($option_add1_info["cd_val2"]=="" ? 0:$option_add1_info["cd_val2"]);
						$add_price += ($option_add2_info["cd_val2"]=="" ? 0:$option_add2_info["cd_val2"]);
						$add_price += ($option_add3_info["cd_val2"]=="" ? 0:$option_add3_info["cd_val2"]);
					}

					$goods["ct_s_price"] = (getSalePrice($setting_goods["margin_type"],$setting_goods["sale_per"],$goods["gd_p_price"],$goods["gd_s_price"],$goods["gd_i_price"],$goods["gc_won_type"],$goods["gc_won"]));
					$goods["ct_s_price"] = $goods["ct_s_price"] + $add_price;
					$cart_goods[$goods_key]["ct_s_price"] = $goods["ct_s_price"];
					if($delivery["sr_idx"] == $goods["sr_idx"] && $delivery["dv_idx"] == $goods["dv_idx"]){
						$cart_group[$group_key]["delivery"][$delivery_key]["goods"][] = $goods;
						$sum_goods_price += $goods["ct_s_price"] * $goods["ct_cnt"];
						$sum_goods_cnt += $goods["ct_cnt"];
						$sum_goods_kg += $goods["gd_kg"];
						$sum_goods_cm3 += toFixed($goods["gd_cm3"] * $goods["ct_cnt"],3);
					}

					//추가파일 여부 확인 : 작업복로고,
					if($goods["gd_option_add_type"]!="" && $goods["ct_option_add1"]!="후가공 없음"){
						$add_file_yn = "Y";
						$add_file_name = $goods["gd_option_add_type"];
					}
				}

				$sum_cm3 = $sum_cm3 + $sum_goods_cm3;

				//배송비 설정하기
				$cart_group[$group_key]["delivery"][$delivery_key]["dv_add_list"] = "";
				switch($delivery["dv_type"]){
					case "00":
						$delivery_price = 0;
						break;
					case "10":
						$delivery_price = $delivery["dv_price"];
						break;
					case "20":
						$html = "<span class='delivery'>조건부 무료배송비<em>".number_format($delivery["dv_price"])."(".number_format($delivery["dv_limit_price"])."이상구매시 배송비 0원)</em>원</span>";
						if($sum_goods_price > $delivery["dv_limit_price"]){
							$delivery_price = 0;
						}else{
							$delivery_price = $delivery["dv_price"];
						}
						break;
					case "30":
						$delivery_price = ($delivery["dv_price"] * ceil($sum_goods_cnt/$delivery["dv_limit_price"]));
						break;
					//시스넷 커스텀
					case "90":
						$limit_price = 1;
						if($delivery_type==""){
							$delivery_price = 0;
						}else{
							$cnt = ceil($sum_goods_cm3/$limit_price);
							if($delivery_type=="00"){
								$setting = $this->db_row("st_setting",["st_use_yn"=>"Y"]);

								//싼디랑 시스넷만 적용한다.
								$minus_qty = 0;
								if($setting["st_store_type"]=="ssandi"){
									//싼디일때만 적용.
									$fmod_val = fmod($sum_goods_cm3, 1);
									if(strval($fmod_val) > 0 && strval($fmod_val) <= 0.2){
										if($cnt > 0){
											$minus_qty = 1;
										}
										$delivery_price = ($delivery["dv_price_v4"]);
									}
								}

								$delivery_price += ($cnt - $minus_qty) * ($delivery["dv_price"]);
							}else if($delivery_type=="01"){
								$delivery_price = $cnt * ($delivery["dv_price_v2"]);;
							}else if($delivery_type=="06"){
								$delivery_price = $cnt * ($delivery["dv_price_v3"]);;
							}
							$box_qty = $cnt;
						}
						break;
					case "40":
					case "50":
					case "60":
						//$add_list = $this->$this->db_list("st_delivery_dtl",["dv_idx"=>$delivery["dv_idx"]],"*",["dt_idx"=>"asc"]);
						$sql = "
							select dt_start,dt_end,dt_price
							FROM st_delivery_dtl
							where dv_idx = '".$this->inject($delivery["dv_idx"])."'
							order by dt_idx asc
						";

						$query = $this->db->query($sql);
						$add_list = $query->result_array();

						$cart_group[$group_key]["delivery"][$delivery_key]["dv_add_list"] = json_encode($add_list);

						foreach($add_list as  $add){
							if($delivery["dv_type"]=="40"){
								if($add["dt_start"]<= $sum_goods_price && $add["dt_end"] > $sum_goods_price){
									$delivery_price = $add["dt_price"];
								}
							}else if($delivery["dv_type"]=="50"){
								if($add["dt_start"]<= $sum_goods_kg && $add["dt_end"] > $sum_goods_kg){
									$delivery_price = $add["dt_price"];
								}
							}else if($delivery["dv_type"]=="60"){
								if($add["dt_start"]<= $sum_goods_cnt && $add["dt_end"] > $sum_goods_cnt){
									$delivery_price = $add["dt_price"];
								}
							}
						}
						break;
				}

				//배송유형별 정보
				$cart_group[$group_key]["delivery"][$delivery_key]["delivery_price"] = $delivery_price;
				$cart_group[$group_key]["delivery"][$delivery_key]["goods_price"] = $sum_goods_price;
				$cart_group[$group_key]["delivery"][$delivery_key]["delivery_addprice"] = 0;	//처리해야함

				//전체 정보

				$total_goods_price += $sum_goods_price;
				$total_delivery_price += $delivery_price;
				$total_sr_goods_price += $sum_goods_price;
				$total_sr_delivery_price += $delivery_price;
			}
			$cart_group[$group_key]["goods_price"] = $total_sr_goods_price;
			$cart_group[$group_key]["delivery_price"] = $total_sr_delivery_price;

		}

		return [
			"list"=>$cart_group,
			"total_cnt"=>$total_cnt,
			"sum_cm3"=>$sum_cm3,
			"add_file_yn"=>$add_file_yn,
			"add_file_name"=>$add_file_name,
			"goods_price"=>$total_goods_price,
			"delivery_price"=>$total_delivery_price,
			"box_qty"=>$box_qty,
		];
	}

	function summaryCartList($target_user_idx,$current_user_idx){
		$cart_list = $this->db_list("st_cart",["user_idx"=>$target_user_idx]);
		$current_list = $this->db_list("st_cart",["user_idx"=>$current_user_idx]);

		foreach($cart_list as $cart){
			$target_yn = "Y";
			foreach($current_list as $current){
				if($cart["gd_idx"] == $current["gd_idx"] && $cart["gl_idx"] == $current["gl_idx"]){
					$target_yn = "N";
				}
			}

			//회원으로 변경 또는 삭제
			if($target_yn=="Y"){
				$this->db_update("st_cart",["user_idx"=>$current_user_idx],["ct_idx"=>$cart["ct_idx"]]);
			}else{
				$this->db_delete("st_cart",["ct_idx"=>$cart["ct_idx"]]);
			}
		}
	}

	//기획전내 상품리스트
	function getPlanRelGoods($pl_idx,$margin_type,$orderby){
		$mm_idx = $this->user->getUserIdx();
		$sql = "
			SELECT aa.gd_idx
				,aa.show_yn
				,aa.gd_name
				,aa.gd_code
				,aa.gd_name
				,aa.gd_list_name
				,aa.gd_sub_name
				,aa.gd_s_price
				,aa.gd_p_price
				,aa.gd_i_price
				,aa.gd_img_type
				,aa.gd_img_main
				,aa.gd_img_list
				,aa.gd_img_main_url
				,aa.gd_img_list_url
				,gg.cnt
				,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
				,cp.gc_name
				,cp.gc_won
				,cp.gc_won_type
				,bb.pc_idx
				,(case when gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and gg_stock = 0)) then 'Y' else 'N' end) as sold_out
			FROM vw_goods aa
			inner join st_plan_goods bb
				on aa.gd_idx = bb.gd_idx
			left join (
				SELECT gd_idx
					,count(*) as cnt
				FROM st_order_goods
				where reg_dt <=DATE_SUB(now(), INTERVAL 3 MONTH)
				group by gd_idx
			) gg on aa.gd_idx = gg.gd_idx
			left join st_goods_wish gw
        		on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
        	left join (
				 select aa.gc_idx
				        ,aa.gc_name
				        ,aa.gc_won
				        ,aa.gc_won_type
				        ,bb.gd_idx
				 FROM (
					  select aa.gc_idx
					        ,aa.gc_name
					        ,aa.gc_won
					        ,aa.gc_won_type
					  FROM
					  st_goods_coupon aa
					    left join st_goods_coupon_member bb
					        on bb.gc_idx = bb.gc_idx
					  where aa.gc_sdate <=now() and gc_edate >= now()
					  and (aa.gc_mem_type in ('','$margin_type') or (aa.gc_mem_type = 'SELECT' and aa.gc_idx = bb.gc_idx and bb.mm_idx = '$mm_idx'))
					  group by aa.gc_idx
				) aa inner join st_goods_coupon_list bb
				  on aa.gc_idx = bb.gc_idx
			) cp on aa.gd_idx = cp.gd_idx
			left join (
				SELECT gd_idx,sum(gg_stock) as gg_stock
				FROM `st_goods_option_list`
				group by gd_idx
			) dd on aa.gd_idx = dd.gd_idx
			where aa.gd_status = 'Y' and bb.pl_idx = '$pl_idx'
			group by aa.gd_idx
		";

		//order by bb.pg_sort asc,bb.pg_idx desc
		if($orderby==""){
			$sql .= " order by bb.pg_sort asc,bb.pg_idx desc ";
		}else if($orderby=="fav"){
			$sql .= " order by gg.cnt desc,bb.pg_sort asc,bb.pg_idx desc ";
		}else if($orderby=="reg_dt"){
			$sql .= " order by aa.reg_dt desc";
		}else if($orderby=="price_up"){
			$sql .= " order by aa.gd_s_price desc,bb.pg_sort asc,bb.pg_idx desc ";
		}else if($orderby=="price_down"){
			$sql .= " order by aa.gd_s_price asc,bb.pg_sort asc,bb.pg_idx desc ";
		}else if($orderby=="gd_name"){
			$sql .= " order by aa.gd_list_name desc,aa.gd_name desc,bb.pg_sort asc,bb.pg_idx desc ";
		}

		$query = $this->db->query($sql);
		return $query->result_array();

	}

	//장바구니에서 판매자 리스트 뽑아서 관련상품 중 잘팔리는 상품 리스트
	function getCartFavGoodsList($user_idx,$margin_type){
		$mm_idx = $this->user->getUserIdx();
		$sql = "
			select bb.sr_idx
				,(case when bb.sr_idx = 0 then '' else cc.sr_name end) as sr_name
				,cp.gc_name
				,cp.gc_won
				,cp.gc_won_type
				,bb.show_yn
				,(case when gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and gg_stock = 0)) then 'Y' else 'N' end) as sold_out
			FROM st_cart aa
				inner join vw_goods bb
					on aa.gd_idx = bb.gd_idx
				left join st_seller cc
					on bb.sr_idx = cc.sr_idx
			left join (
				 select aa.gc_idx
				        ,aa.gc_name
				        ,aa.gc_won
				        ,aa.gc_won_type
				        ,bb.gd_idx
				 FROM (
					  select aa.gc_idx
					        ,aa.gc_name
					        ,aa.gc_won
					        ,aa.gc_won_type
					  FROM
					  st_goods_coupon aa
					    left join st_goods_coupon_member bb
					        on bb.gc_idx = bb.gc_idx
					  where aa.gc_sdate <=now() and gc_edate >= now()
					  and (aa.gc_mem_type in ('','$margin_type') or (aa.gc_mem_type = 'SELECT' and aa.gc_idx = bb.gc_idx and bb.mm_idx = '$mm_idx'))
					  group by aa.gc_idx
				) aa inner join st_goods_coupon_list bb
				  on aa.gc_idx = bb.gc_idx
			) cp on bb.gd_idx = cp.gd_idx
			left join (
				SELECT gd_idx,sum(gg_stock) as gg_stock
				FROM `st_goods_option_list`
				group by gd_idx
			) dd on bb.gd_idx = dd.gd_idx
			where user_idx = '$user_idx' and bb.gd_status = 'Y'
			group by bb.gd_idx
		";

		$query = $this->db->query($sql);
		$cart_group = $query->result_array();

		$sr_idx = "";
		foreach($cart_group as $key=> $row){
			if($sr_idx==""){
				$sr_idx .= $row["sr_idx"];
			}else{
				$sr_idx .= "," . $row["sr_idx"];
			}
		}

		//상품리스트
		$add_where ="";
		if($sr_idx!=""){
			$add_where = " and sr_idx in ($sr_idx) ";
		}else{

		}

		$sql = "
			select aa.*
			,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
			,cp.gc_name
			,cp.gc_won
			,cp.gc_won_type
			,aa.show_yn
			,ifnull(gg.cnt,0) as cnt
			,(case when gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and gg_stock = 0)) then 'Y' else 'N' end) as sold_out
			FROM vw_goods aa
			left join (
				SELECT gd_idx
					,sum(click_cnt) as cnt
				FROM st_goods_click_sum
				where reg_dt >=DATE_SUB(now(), INTERVAL 3 MONTH)
				group by gd_idx
			) gg on aa.gd_idx = gg.gd_idx
			left join st_goods_wish gw
        		on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
        	left join (
				select
				     aa.gc_name
				    ,aa.gc_won
				    ,aa.gc_won_type
				    ,bb.gd_idx
				FROM st_goods_coupon aa
				    inner join st_goods_coupon_list bb
				        on aa.gc_idx = bb.gc_idx
				    inner join (
				        SELECT gd_idx,max(aa.gc_idx) as gc_idx
				        FROM st_goods_coupon aa
				            inner join st_goods_coupon_list bb
				                on aa.gc_idx = bb.gc_idx
				        where aa.gc_sdate <=now() and gc_edate >= now()
				        group by gd_idx
				    ) cc
			     on bb.gc_idx = bb.gc_idx and bb.gd_idx = cc.gd_idx and aa.gc_idx = cc.gc_idx
			) cp on aa.gd_idx = cp.gd_idx
			left join (
				SELECT gd_idx,sum(gg_stock) as gg_stock
				FROM `st_goods_option_list`
				group by gd_idx
			) dd on aa.gd_idx = dd.gd_idx
			where aa.gd_status = 'Y' $add_where
			group by aa.gd_idx
			order by ifnull(gg.cnt,0) desc, aa.reg_dt desc
			limit 0,10
		";


		$query = $this->db->query($sql);
		$goods_list = $query->result_array();

		return $goods_list;
	}

	//장바구니에서 판매자 리스트 뽑아서 관련상품 중 잘팔리는 상품 리스트
	function getBest100List(){
		$mm_idx = $this->user->getUserIdx();
		$add_where = "";
		$sql = "
			select aa.*
				,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
				,cp.gc_name
				,cp.gc_won
				,cp.gc_won_type
			FROM vw_goods aa
			left join (
				SELECT gd_idx
					,count(*) as cnt
				FROM st_order_goods
				where reg_dt <=DATE_SUB(now(), INTERVAL 3 MONTH)
				group by gd_idx
			) gg on aa.gd_idx = gg.gd_idx
			left join st_goods_wish gw
        		on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
        	left join (
				select
				     aa.gc_name
				    ,aa.gc_won
				    ,aa.gc_won_type
				    ,bb.gd_idx
				FROM st_goods_coupon aa
				    inner join st_goods_coupon_list bb
				        on aa.gc_idx = bb.gc_idx
				    inner join (
				        SELECT gd_idx,max(aa.gc_idx) as gc_idx
				        FROM st_goods_coupon aa
				            inner join st_goods_coupon_list bb
				                on aa.gc_idx = bb.gc_idx
				        where aa.gc_sdate <=now() and gc_edate >= now()
				        group by gd_idx
				    ) cc
			     on bb.gc_idx = bb.gc_idx and bb.gd_idx = cc.gd_idx and aa.gc_idx = cc.gc_idx
			) cp on aa.gd_idx = cp.gd_idx
			where 1=1 $add_where
			order by gg.cnt desc, aa.reg_dt desc
			limit 0,100
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	//카테고리 리스트 조회
	function getGoodsSearchList($params)
	{
		$margin_type = $params["margin_type"];
		$mm_idx = $this->user->getUserIdx();
		$keyword = str_replace(" ","",str_replace("-","",strtolower($params["keyword"])));
		$ct_idx = $params["ct_idx"];
		$orderby = $params["orderby"];
		$chk_brand = $params["chk_brand"];

		$page = $params["page"];
		$limit = $params["pagesize"];


		$where = " aa.gd_type = '' and aa.gd_status = 'Y' ";

		if($chk_brand !=""){
			$where .= " and aa.bd_idx in (" . $this->inject($chk_brand). ") ";
		}

		if(isset($keyword) && keyword!=""){
			$keyword = $keyword;
			$where .= " and (REPLACE(REPLACE(LOWER(gd_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_sub_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_list_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_keyword),'-',''),' ','') like '%".$this->inject($keyword)."%'  or REPLACE(REPLACE(LOWER(bd_name),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
		}

		if($ct_idx !=""){
			$where .= " and (aa.ct_depth1_idx = '".$this->inject($ct_idx)."' or aa.ct_depth2_idx = '".$this->inject($ct_idx)."' or aa.ct_depth3_idx = '".$this->inject($ct_idx)."') ";
		}


		$sql = "
			SELECT
				aa.gd_idx
				,aa.show_yn
				,aa.gd_name
				,aa.gd_code
				,aa.gd_list_name
				,aa.gd_sub_name
				,aa.gd_s_price
				,aa.gd_p_price
				,aa.gd_i_price
				,aa.gd_img_type
				,aa.gd_img_main
				,aa.gd_img_list
				,aa.gd_img_main_url
				,aa.gd_img_list_url
				,gg.order_cnt
				,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
				,cp.gc_name
				,cp.gc_won
				,cp.gc_won_type
				,(case when (gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and ifnull(gg_stock,0) = 0))) then 'Y' else 'N' end) as sold_out
			FROM vw_goods aa
				inner join st_brand bb on  aa.bd_idx = bb.bd_idx
			left join (
				SELECT gd_idx
					,count(*) as order_cnt
				FROM st_order_goods
				where reg_dt <=DATE_SUB(now(), INTERVAL 3 MONTH)
				group by gd_idx
			) gg on aa.gd_idx = gg.gd_idx
			left join st_goods_wish gw
        		on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
        	left join (
				 select aa.gc_idx
				        ,aa.gc_name
				        ,aa.gc_won
				        ,aa.gc_won_type
				        ,bb.gd_idx
				 FROM (
					  select aa.gc_idx
					        ,aa.gc_name
					        ,aa.gc_won
					        ,aa.gc_won_type
					  FROM
					  st_goods_coupon aa
					    left join st_goods_coupon_member bb
					        on bb.gc_idx = bb.gc_idx
					  where aa.gc_sdate <=now() and gc_edate >= now()
					  and (aa.gc_mem_type in ('','$margin_type') or (aa.gc_mem_type = 'SELECT' and aa.gc_idx = bb.gc_idx and bb.mm_idx = '$mm_idx'))
					  group by aa.gc_idx
				) aa inner join st_goods_coupon_list bb
				  on aa.gc_idx = bb.gc_idx
			) cp on aa.gd_idx = cp.gd_idx
			left join (
				SELECT gd_idx,sum(gg_stock) as gg_stock
				FROM `st_goods_option_list`
				group by gd_idx
			) dd on aa.gd_idx = dd.gd_idx
			where $where
			group by aa.gd_idx

		";

		if($orderby==""){
			$sql .= "order by gg.order_cnt desc,aa.gd_idx desc";
		}else if($orderby=="fav"){
			$sql .="order by gg.order_cnt desc,aa.gd_idx desc";
		}else if($orderby=="reg_dt"){
			$sql .= "order by aa.reg_dt desc,aa.gd_idx desc";
		}else if($orderby=="price_up"){
			$sql .= "order by aa.gd_s_price desc,aa.gd_idx desc ";
		}else if($orderby=="price_down"){
			$sql .= "order by aa.gd_s_price asc,aa.gd_idx desc ";
		}else if($orderby=="gd_name"){
			$sql .= "order by aa.gd_list_name desc,aa.gd_idx desc ";
		}

		$sql_count = "
			select count(*) as cnt
			from (
				$sql
			) aa
		";

		$result = $this->db->query($sql_count);
		$count = $result->row_array();
		if($count!=null){
			$db_result["count"] = $count["cnt"];
		}


		$start = (($page-1) * $limit);
		$sql .= " LIMIT $start , $limit " ;

		$result = $this->db->query($sql);
		$db_result["list"] = $result->result_array();

		//페이지 생성
		$db_result["pagenation"] = get_page_info($page,$db_result["count"],$limit);
		return $db_result;
   	}

   	//카테고리 리스트 조회
	function getGoodsCategoryList($params)
	{
		$margin_type = $params["margin_type"];
		$mm_idx = $this->user->getUserIdx();
		$page = $params["page"];
		$limit = $params["pagesize"];
		$ct_idx = $params["ct_idx"];
		$orderby = $params["orderby"];
		$chk_brand = $params["chk_brand"];

		$where = "";

		if($chk_brand !=""){
			$where = " and aa.bd_idx in (" . $this->inject($chk_brand). ") ";
		}

		$sql = "
			SELECT
				aa.gd_idx
				,aa.show_yn
				,aa.gd_name
				,aa.gd_code
				,aa.gd_list_name
				,aa.gd_sub_name
				,aa.gd_s_price
				,aa.gd_p_price
				,aa.gd_i_price
				,aa.gd_img_type
				,aa.gd_img_main
				,aa.gd_img_list
				,aa.gd_img_main_url
				,aa.gd_img_list_url
				,gg.order_cnt
				,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
				,cp.gc_name
				,cp.gc_won
				,cp.gc_won_type
				,(case when (gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and ifnull(gg_stock,0) = 0))) then 'Y' else 'N' end) as sold_out
			FROM vw_goods aa
				left join st_goods_category bb
			    	on aa.gd_idx = bb.gd_idx
			    left join st_category cc
			    	on bb.ct_idx = cc.ct_idx
			left join (
				SELECT gd_idx
					,count(*) as order_cnt
				FROM st_order_goods
				where reg_dt <=DATE_SUB(now(), INTERVAL 3 MONTH)
				group by gd_idx
			) gg on aa.gd_idx = gg.gd_idx
			left join st_goods_wish gw
        		on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
        	left join (
				 select aa.gc_idx
				        ,aa.gc_name
				        ,aa.gc_won
				        ,aa.gc_won_type
				        ,bb.gd_idx
				 FROM (
					  select aa.gc_idx
					        ,aa.gc_name
					        ,aa.gc_won
					        ,aa.gc_won_type
					  FROM
					  st_goods_coupon aa
					    left join st_goods_coupon_member bb
					        on bb.gc_idx = bb.gc_idx
					  where aa.gc_sdate <=now() and gc_edate >= now()
					  and (aa.gc_mem_type in ('','$margin_type') or (aa.gc_mem_type = 'SELECT' and aa.gc_idx = bb.gc_idx and bb.mm_idx = '$mm_idx'))
					  group by aa.gc_idx
				) aa inner join st_goods_coupon_list bb
				  on aa.gc_idx = bb.gc_idx
			) cp on aa.gd_idx = cp.gd_idx
			left join (
				SELECT gd_idx,sum(gg_stock) as gg_stock
				FROM `st_goods_option_list`
				group by gd_idx
			) dd on aa.gd_idx = dd.gd_idx
			where gd_type = '' and gd_status = 'Y' and (cc.ct_depth1_idx = '".$this->inject($ct_idx)."' or cc.ct_depth2_idx = '".$this->inject($ct_idx)."' or cc.ct_depth3_idx = '".$this->inject($ct_idx)."')
			$where
			group by aa.gd_idx
				,aa.gd_name
				,aa.gd_code
				,aa.gd_list_name
				,aa.gd_sub_name
				,aa.gd_s_price
				,aa.gd_p_price
				,aa.gd_i_price
				,aa.gd_img_type
				,aa.gd_img_main
				,aa.gd_img_list
				,aa.gd_img_main_url
				,aa.gd_img_list_url
				,gg.order_cnt
		";

		if($orderby==""){
			$sql .= "order by gg.order_cnt desc,aa.gd_idx desc";
		}else if($orderby=="fav"){
			$sql .="order by gg.order_cnt desc,aa.gd_idx desc";
		}else if($orderby=="reg_dt"){
			$sql .= "order by aa.reg_dt desc,aa.gd_idx desc";
		}else if($orderby=="price_up"){
			$sql .= "order by aa.gd_s_price desc,aa.gd_idx desc ";
		}else if($orderby=="price_down"){
			$sql .= "order by aa.gd_s_price asc,aa.gd_idx desc ";
		}else if($orderby=="gd_name"){
			$sql .= "order by aa.gd_list_name desc,aa.gd_idx desc ";
		}

		$sql_count = "
			select count(*) as cnt
			from (
				$sql
			) aa
		";

		$result = $this->db->query($sql_count);
		$count = $result->row_array();
		if($count!=null){
			$db_result["count"] = $count["cnt"];
		}


		$start = (($page-1) * $limit);
		$sql .= " LIMIT $start , $limit " ;

		$result = $this->db->query($sql);
		$db_result["list"] = $result->result_array();

		//페이지 생성
		$db_result["pagenation"] = get_page_info($page,$db_result["count"],$limit);
		return $db_result;
   	}

   	//프론트 카테고리 리스트
   	function getGoodsCategory($ct_idx){
   		$cate_info = $this->db_row("st_category",["ct_idx"=>$ct_idx]);

		//2차 카테고리 구해오기
		$ct_name = $cate_info["ct_name"];
		$parent_idx = $cate_info["ct_idx"];
		if($cate_info["ct_depth"]=="2"){
			$parent_idx = $cate_info["ct_depth1_idx"];
			$cate_parent = $this->db_row("st_category",["ct_idx"=>$parent_idx]);
			$ct_name = $cate_parent["ct_name"];
		}

   		$sql = "
			select *
			FROM st_category
			where ct_depth1_idx = '".$this->inject($parent_idx)."'
			and ct_depth = '2'
			order by ct_sort asc
		";

		$result = $this->db->query($sql);

		return [
			"list"=>$result->result_array()
			,"parent_idx"=> $parent_idx
			,"ct_name"=> $ct_name
		];
   	}

	//카테고리 뎁스별 리스트 가져오기
	function getCategoryDepthAllList($params = null){
		$db = DB('default', true);

		$keyword = str_replace(" ","",str_replace("-","",strtolower($params["keyword"])));

		$where = "";
		if($params["chk_brand"]!=""){
			$where .=" and bb.bd_idx in (".$this->inject($params["chk_brand"]).") ";
		}

		if(isset($keyword) && $keyword!=""){
			$where .= " and bb.gd_type = '' and bb.gd_status = 'Y' and (REPLACE(REPLACE(LOWER(gd_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_sub_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_list_name),'-',''),' ','') like '%".$this->inject($keyword)."%' or REPLACE(REPLACE(LOWER(gd_keyword),'-',''),' ','') like '%".$this->inject($keyword)."%'  or REPLACE(REPLACE(LOWER(bd_name),'-',''),' ','') like '%".$this->inject($keyword)."%')  ";
		}

		$sql = "
			SELECT ct1_name,ct1_idx,ct1_parent
			,count(*) as goods_cnt
			FROM (
			    SELECT ct1.ct_name as ct1_name,ct1.ct_idx as ct1_idx,ct1.ct_sort as ct1_sort,ct1.ct_parent as ct1_parent
			        ,bb.gd_idx
			    FROM st_goods_category aa
			        inner join vw_goods bb
			            on aa.gd_idx = bb.gd_idx
			        inner join st_brand bd
			            on bb.bd_idx = bd.bd_idx
			        inner join st_category ct
			            on aa.ct_idx = ct.ct_idx
			        left join st_category ct1 on ct.ct_depth1_idx = ct1.ct_idx and ct1.ct_status = 'Y'

			    where bb.gd_type = '' and bb.gd_status = 'Y'
			    $where
			    group by ct1.ct_name ,ct1.ct_idx ,ct1.ct_parent,bb.gd_idx
			) aa
			where ct1_idx is not null
			group by ct1_name,ct1_idx
			order by ct1_sort asc
		";

		$sql2 = "
			SELECT ct2_name,ct2_idx,ct2_parent
			,count(*) as goods_cnt
			FROM (
			    SELECT ct2.ct_name as ct2_name,ct2.ct_idx as ct2_idx,ct2.ct_sort as ct2_sort,ct2.ct_parent as ct2_parent
			        ,bb.gd_idx
			    FROM st_goods_category aa
			        inner join vw_goods bb
			            on aa.gd_idx = bb.gd_idx
			        inner join st_brand bd
			            on bb.bd_idx = bd.bd_idx
			        inner join st_category ct
			            on aa.ct_idx = ct.ct_idx
			        left join st_category ct2 on ct.ct_depth2_idx = ct2.ct_idx and ct2.ct_status = 'Y'

			    where bb.gd_type = '' and bb.gd_status = 'Y'
			    $where
			    group by ct2.ct_name ,ct2.ct_idx ,ct2.ct_parent,bb.gd_idx
			) aa
			where ct2_idx is not null
			group by ct2_name,ct2_idx
			order by ct2_sort asc
		";

		$sql3 = "
			SELECT ct3_name,ct3_idx,ct3_parent
			,count(*) as goods_cnt
			FROM (
			    SELECT ct3.ct_name as ct3_name,ct3.ct_idx as ct3_idx,ct3.ct_sort as ct3_sort,ct3.ct_parent as ct3_parent
			        ,bb.gd_idx
			    FROM st_goods_category aa
			        inner join vw_goods bb
			            on aa.gd_idx = bb.gd_idx
			        inner join st_brand bd
			            on bb.bd_idx = bd.bd_idx
			        inner join st_category ct
			            on aa.ct_idx = ct.ct_idx
			        left join st_category ct3 on ct.ct_depth3_idx = ct3.ct_idx and ct3.ct_status = 'Y'

			    where bb.gd_type = '' and bb.gd_status = 'Y'
			    $where
			    group by ct3.ct_name ,ct3.ct_idx ,ct3.ct_parent,bb.gd_idx
			) aa
			where ct3_idx is not null
			group by ct3_name,ct3_idx
			order by ct3_sort asc
		";

		$tmp_result = $db->query($sql);
		$category_list = $tmp_result->result_array();
		$result = [];
		foreach($category_list as  $pt_key=> $ct){
			$result[$ct["ct1_idx"]] = array(
				"ct_idx" => $ct["ct1_idx"],
				"ct_name" => $ct["ct1_name"],
				"goods_cnt" => $ct["goods_cnt"],
				"list" => []
			);
		}

		$tmp_result = $db->query($sql2);
		$category_list = $tmp_result->result_array();
		//2댑스 처리
		foreach($result as $pt_key=> $pt){
			$goods_cnt = $pt["goods_cnt"];
			foreach($category_list as  $key1 => $ct){
				if($pt["ct_idx"] == $ct["ct2_parent"]){
					$goods_cnt += $ct["goods_cnt"];
					$result[$pt["ct_idx"]]["list"][$ct["ct2_idx"]] = [
						"ct_idx" => $ct["ct2_idx"],
						"ct_name" => $ct["ct2_name"],
						"goods_cnt" => $ct["goods_cnt"],
						"list" => []
					];
				}
			}

		}

		$tmp_result = $db->query($sql3);
		$category_list = $tmp_result->result_array();

		//3댑스 처리
		foreach($result as $pt_key=> $pt){
			foreach($pt["list"] as $pt_key2=> $pt2){
				foreach($category_list as  $ct){
					if($pt2["ct_idx"] == $ct["ct3_parent"]){
						$result[$pt["ct_idx"]]["list"][$pt2["ct_idx"]]["list"][] = [
							"ct_idx" => $ct["ct3_idx"],
							"ct_name" => $ct["ct3_name"],
							"goods_cnt" => $ct["goods_cnt"],
						];

						//var_dump($result[$pt["ct_idx"]]["list"][$pt2["ct_idx"]]);
					}
				}

			}
		}
		return $result;
	}


   	//카테고리 리스트 조회
	function getGoodsBrandList($params)
	{
		$margin_type = $params["margin_type"];
		$mm_idx = $this->user->getUserIdx();
		$page = $params["page"];
		$limit = $params["pagesize"];
		$chk_brand = $params["chk_brand"];
		$orderby = $params["orderby"];
		$ct_idx = $params["ct_idx"];

		$where = "";

		if($chk_brand!=""){
			$where .= " and ( aa.bd_idx in (".$this->inject($chk_brand).")) ";
		}

		if($ct_idx !=""){
			$where .= " and (aa.ct_depth1_idx = '".$this->inject($ct_idx)."' or aa.ct_depth2_idx = '".$this->inject($ct_idx)."' or aa.ct_depth3_idx = '".$this->inject($ct_idx)."') ";
		}

		$sql = "
			SELECT
				aa.gd_idx
				,aa.show_yn
				,aa.gd_name
				,aa.gd_code
				,aa.gd_list_name
				,aa.gd_sub_name
				,aa.gd_s_price
				,aa.gd_p_price
				,aa.gd_i_price
				,aa.gd_img_type
				,aa.gd_img_main
				,aa.gd_img_list
				,aa.gd_img_main_url
				,aa.gd_img_list_url
				,gg.order_cnt
				,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
				,cp.gc_name
				,cp.gc_won
				,cp.gc_won_type
				,(case when gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and gg_stock = 0)) then 'Y' else 'N' end) as sold_out
			FROM vw_goods aa
			left join (
				SELECT gd_idx
					,count(*) as order_cnt
				FROM st_order_goods
				where reg_dt <=DATE_SUB(now(), INTERVAL 3 MONTH)
				group by gd_idx
			) gg on aa.gd_idx = gg.gd_idx
			left join st_goods_wish gw
        		on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
        	left join (
				 select aa.gc_idx
				        ,aa.gc_name
				        ,aa.gc_won
				        ,aa.gc_won_type
				        ,bb.gd_idx
				 FROM (
					  select aa.gc_idx
					        ,aa.gc_name
					        ,aa.gc_won
					        ,aa.gc_won_type
					  FROM
					  st_goods_coupon aa
					    left join st_goods_coupon_member bb
					        on bb.gc_idx = bb.gc_idx
					  where aa.gc_sdate <=now() and gc_edate >= now()
					  and (aa.gc_mem_type in ('','$margin_type') or (aa.gc_mem_type = 'SELECT' and aa.gc_idx = bb.gc_idx and bb.mm_idx = '$mm_idx'))
					  group by aa.gc_idx
				) aa inner join st_goods_coupon_list bb
				  on aa.gc_idx = bb.gc_idx
			) cp on aa.gd_idx = cp.gd_idx
			left join (
				SELECT gd_idx,sum(gg_stock) as gg_stock
				FROM `st_goods_option_list`
				group by gd_idx
			) dd on aa.gd_idx = dd.gd_idx
			where gd_type = '' and gd_status = 'Y'
			$where
			group by aa.gd_idx
				,aa.gd_name
				,aa.gd_code
				,aa.gd_list_name
				,aa.gd_sub_name
				,aa.gd_s_price
				,aa.gd_p_price
				,aa.gd_i_price
				,aa.gd_img_type
				,aa.gd_img_main
				,aa.gd_img_list
				,aa.gd_img_main_url
				,aa.gd_img_list_url
				,gg.order_cnt
		";

		if($orderby==""){
			$sql .= "order by gg.order_cnt desc,aa.gd_idx desc";
		}else if($orderby=="fav"){
			$sql .="order by gg.order_cnt desc,aa.gd_idx desc";
		}else if($orderby=="reg_dt"){
			$sql .= "order by aa.reg_dt desc";
		}else if($orderby=="price_up"){
			$sql .= "order by aa.gd_s_price desc,aa.gd_idx desc ";
		}else if($orderby=="price_down"){
			$sql .= "order by aa.gd_s_price asc,aa.gd_idx desc ";
		}else if($orderby=="gd_name"){
			$sql .= "order by aa.gd_list_name desc,aa.gd_idx desc ";
		}

		$sql_count = "
			select count(*) as cnt
			from (
				$sql
			) aa
		";

		$result = $this->db->query($sql_count);
		$count = $result->row_array();
		if($count!=null){
			$db_result["count"] = $count["cnt"];
		}


		$start = (($page-1) * $limit);
		$sql .= " LIMIT $start , $limit " ;

		$result = $this->db->query($sql);
		$db_result["list"] = $result->result_array();

		//페이지 생성
		$db_result["pagenation"] = get_page_info($page,$db_result["count"],$limit);
		return $db_result;
   	}

   	//견적서 상품 리스트
	function getEstimateGoodsList($em_code){
		$mm_idx = $this->user->getUserIdx();

		$sql = "
			SELECT
				aa.gd_idx
				,aa.gd_name
				,aa.gd_code
				,aa.gd_list_name
				,aa.gd_sub_name
				,aa.gd_s_price
				,aa.gd_p_price
				,aa.gd_i_price
				,aa.gd_img_type
				,aa.gd_img_main
				,aa.gd_img_list
				,aa.gd_img_main_url
				,aa.gd_img_list_url
				,gg.order_cnt
				,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
				,cp.gc_name
				,cp.gc_won
				,cp.gc_won_type
				,eg.gd_cnt
				,mg.mg_sale_per
			FROM st_estimate et
				inner join st_estimate_goods eg
					on et.em_idx = eg.em_idx
				inner join st_goods aa
					on eg.gd_idx = aa.gd_idx
				inner join st_brand bb
			    	on aa.bd_idx = bb.bd_idx
			    inner join st_member mb
			    	on et.mm_idx = mb.mm_idx
			    left join st_member_grade mg
			    	on mb.mg_idx = mg.mg_idx
			left join (
				SELECT gd_idx
					,count(*) as order_cnt
				FROM st_order_goods
				where reg_dt <=DATE_SUB(now(), INTERVAL 3 MONTH)
				group by gd_idx
			) gg on aa.gd_idx = gg.gd_idx
			left join st_goods_wish gw
        		on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
        	left join (
				select
				     aa.gc_name
				    ,aa.gc_won
				    ,aa.gc_won_type
				    ,bb.gd_idx
				FROM st_goods_coupon aa
				    inner join st_goods_coupon_list bb
				        on aa.gc_idx = bb.gc_idx
				    inner join (
				        SELECT gd_idx,max(aa.gc_idx) as gc_idx
				        FROM st_goods_coupon aa
				            inner join st_goods_coupon_list bb
				                on aa.gc_idx = bb.gc_idx
				        where aa.gc_sdate <=now() and gc_edate >= now()
				        group by gd_idx
				    ) cc
			     on bb.gc_idx = bb.gc_idx and bb.gd_idx = cc.gd_idx and aa.gc_idx = cc.gc_idx
			) cp on aa.gd_idx = cp.gd_idx
			where et.em_code = '$em_code'
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	//메인 상품 정보
	function getMainGoodsIdx($gd_idxs){
		$mm_idx = $this->user->getUserIdx();

		$sql = "
			SELECT aa.gd_idx
				,aa.gd_name
				,aa.gd_code
				,aa.gd_name
				,aa.gd_list_name
				,aa.gd_sub_name
				,aa.gd_s_price
				,aa.gd_p_price
				,aa.gd_i_price
				,aa.gd_img_type
				,aa.gd_img_main
				,aa.gd_img_list
				,aa.gd_img_main_url
				,aa.gd_img_list_url
				,aa.st_admin_key
				,(case when gw.gd_idx is null then 'N' else 'Y' end) as wish_yn
				,cp.gc_name
				,cp.gc_won
				,cp.gc_won_type
				,(case when gd_stock_yn='Y' and ((gd_stock = 0 and gd_option_yn = 'N') or (gd_option_yn = 'Y' and gg_stock = 0)) then 'Y' else 'N' end) as sold_out
			FROM vw_goods aa
			left join st_goods_wish gw
        		on aa.gd_idx = gw.gd_idx and gw.mm_idx = '$mm_idx'
        	left join (
				select
				     aa.gc_name
				    ,aa.gc_won
				    ,aa.gc_won_type
				    ,bb.gd_idx
				FROM st_goods_coupon aa
				    inner join st_goods_coupon_list bb
				        on aa.gc_idx = bb.gc_idx
				    inner join (
				        SELECT gd_idx,max(aa.gc_idx) as gc_idx
				        FROM st_goods_coupon aa
				            inner join st_goods_coupon_list bb
				                on aa.gc_idx = bb.gc_idx
				        where aa.gc_sdate <=now() and gc_edate >= now()
				        group by gd_idx
				    ) cc
			     on bb.gc_idx = bb.gc_idx and bb.gd_idx = cc.gd_idx and aa.gc_idx = cc.gc_idx
			) cp on aa.gd_idx = cp.gd_idx
			left join (
				SELECT gd_idx,sum(gg_stock) as gg_stock
				FROM `st_goods_option_list`
				group by gd_idx
			) dd on aa.gd_idx = dd.gd_idx
			where aa.st_admin_key in ($gd_idxs)
			and aa.gd_status = 'Y'
		";

		$query = $this->db->query($sql);
		return $query->result_array();

	}

   	//프론트 카테고리 리스트
   	function getGoodsBrand($bd_idx){

   		$sql = "
			select aa.bd_idx,aa.bd_name,aa.bd_img
			FROM st_brand aa
				inner join vw_goods bb
					on aa.bd_idx = bb.bd_idx
			where aa.bd_use_yn = 'Y'
			group by aa.bd_idx,aa.bd_name,aa.bd_img
			order by aa.bd_name asc
		";

		$result = $this->db->query($sql);

		$brand_info = $this->db_row("st_brand",["bd_idx"=>$bd_idx]);

		return [
			"list"=>$result->result_array()
			,"bd_name"=> $brand_info["bd_name"]
		];
   	}

 	//상품 클릭처리
   	function setGoodsClick($session_id,$gd_idx){
   		$date = date("Y-m-d");

   		//클릭등록
   		$click_day = $this->db_row("st_goods_click_day",["gd_idx"=>$gd_idx,"session_id"=>$session_id]);
   		if($click_day!=null){
   			$this->db_update("st_goods_click_day",["reg_dt"=>get_today()],["gd_idx"=>$gd_idx,"session_id"=>$session_id]);
   		}else{
   			$this->db_insert("st_goods_click_day",["gd_idx"=>$gd_idx,"session_id"=>$session_id,"reg_dt"=>get_today()]);
   		}

   		//서머리 등록
   		$click_data = $this->db_row("st_goods_click_sum",["gd_idx"=>$gd_idx,"dt"=>$date]);
   		if($click_data!=null){
   			$this->db_update("st_goods_click_sum",["click_cnt"=>$click_data["click_cnt"] + 1],["gd_idx"=>$gd_idx,"dt"=>$date]);
   		}else{
   			$this->db_insert("st_goods_click_sum",["gd_idx"=>$gd_idx,"dt"=>$date,"click_cnt"=>1]);
   		}

		//삭제 처리
		$sql = "
			delete
			FROM st_goods_click_day
			where reg_dt <= DATE_SUB(NOW(), INTERVAL 3 MONTH);
		";
		$this->db->query($sql);

		//삭제 처리
		$sql = "
			delete
			FROM st_goods_click_sum
			where reg_dt <= DATE_SUB(NOW(), INTERVAL 6 MONTH);
		";
		$this->db->query($sql);
   	}

   	//상품 추가 옵션 처리
   	function getAddOptionList($where){
   		return $this->db_list("st_code",$where,"*",["cd_idx"=>"asc"]);
   	}


	//상점 권한 상품 금액 수정
	function updateGrantGoods($data,$where){
		if($data["gp_p_price"]=="" && $data["gp_s_price"]==""){
			$result = $this->db_delete("st_goods_grant_price",$where);
		}else{
			$is_data = $this->db_is_data("st_goods_grant_price",$where);
			if($is_data==true){
				$result = $this->db_update("st_goods_grant_price",$data,$where);
			}else{
				$result = $this->db_insert("st_goods_grant_price",$data);
			}
		}

		return $result;
	}

	//상품 노출관리 데이터 일괄처리
	function updateGoodsDisplay(){
		$sql = "
			select aa.bd_idx,aa.ct1_idx,aa.ct2_idx,aa.ct3_idx,'Y'
			FROM (
				select aa.bd_idx,aa.ct1_idx,aa.ct2_idx,aa.ct3_idx,'Y'
				FROM (
				    SELECT aa.bd_idx,ifnull(ct1.ct_idx,0) as ct1_idx,ifnull(ct2.ct_idx,0) as ct2_idx,ifnull(ct3.ct_idx,0) as ct3_idx
				    FROM st_goods aa
				        inner join st_goods_category bb on aa.gd_idx = bb.gd_idx
				        inner join st_category cc on bb.ct_idx = cc.ct_idx and cc.del_yn = 'N'
				        left join st_category ct1 on cc.ct_depth1_idx = ct1.ct_idx
				        left join st_category ct2 on cc.ct_depth2_idx = ct2.ct_idx
				        left join st_category ct3 on cc.ct_depth3_idx = ct3.ct_idx
				        inner join st_brand dd on aa.bd_idx = dd.bd_idx
				    where 1=1 and aa.gd_type = ''
				    group by aa.bd_idx,ct1.ct_idx,ct2.ct_idx,ct3.ct_idx
				    order by dd.bd_idx ASC
				) aa

				union all
				select aa.bd_idx,aa.ct1_idx,aa.ct2_idx,aa.ct3_idx,'Y'
				FROM (
				    SELECT aa.bd_idx,0 as ct1_idx, 0 as ct2_idx,0 as ct3_idx
				    FROM st_goods aa
				        inner join st_goods_category bb on aa.gd_idx = bb.gd_idx
				        inner join st_category cc on bb.ct_idx = cc.ct_idx and cc.del_yn = 'N'
				        left join st_category ct1 on cc.ct_depth1_idx = ct1.ct_idx
				        left join st_category ct2 on cc.ct_depth2_idx = ct2.ct_idx
				        left join st_category ct3 on cc.ct_depth3_idx = ct3.ct_idx
				        inner join st_brand dd on aa.bd_idx = dd.bd_idx
				    where 1=1 and aa.gd_type = ''
				    group by aa.bd_idx
				    order by aa.bd_idx ASC
				) aa
			) aa
			left join st_goods_display bb
			    on aa.bd_idx = bb.bd_idx and aa.ct1_idx = bb.ct1_idx and aa.ct2_idx = bb.ct2_idx and aa.ct3_idx = bb.ct3_idx
			where bb.bd_idx is null
			order by aa.bd_idx asc,aa.ct1_idx asc,aa.ct2_idx asc,aa.ct3_idx asc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	//등록처리
	function insertOrUpdateGoodsDisplay($arr_data){
		$is_data = $this->db_is_data("st_goods_display",$arr_data);
		if($is_data == false){
			$this->db_insert("st_goods_display",$arr_data);
		}
	}

	//등록처리
	function updateGoodsDisplayBatch($arr_data,$colname){
		$this->db_update_batch("st_goods_display",$arr_data,$colname);
	}

	//삭제 카테고리 삭제 처리.
	function deleteGoodsDisplayAll(){
		$sql = "
			SELECT * FROM `st_category` aa
				inner join st_goods_display bb
					on aa.ct_depth1_idx = bb.ct1_idx
					and aa.ct_depth2_idx = bb.ct2_idx
					and aa.ct_depth3_idx = bb.ct3_idx
			where aa.del_yn = 'Y'
		";

		$query = $this->db->query($sql);
		$del_list = $query->result_array();

		foreach($del_list as $row){
			$this->db_delete("st_goods_display",["bd_idx"=>$row["bd_idx"],"ct1_idx"=>$row["ct1_idx"],"ct2_idx"=>$row["ct2_idx"],"ct3_idx"=>$row["ct3_idx"]]);
		}
	}

	//상품 노출관리 데이터 일괄처리
	function getGoodsDisplayTargetList(){
		$sql = "
			SELECT aa.ds_idx
				,aa.bd_idx
				,aa.ct1_idx
			    ,aa.ct2_idx
			    ,aa.ct3_idx
			    ,aa.show_yn
			    ,bb.bd_name
			    ,cc.ct_name
			    ,cc.ct_depth
			    ,cc.ct_sort
			FROM st_goods_display aa
				inner join st_brand bb
			    	on aa.bd_idx = bb.bd_idx
			    inner join st_category cc
			    	on aa.ct1_idx = cc.ct_depth1_idx and  aa.ct2_idx = cc.ct_depth2_idx and  aa.ct3_idx = cc.ct_depth3_idx
			order by aa.bd_idx asc ,cc.ct_depth asc,cc.ct_sort asc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
