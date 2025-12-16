<?php
class communityModel extends MY_Model {
   	function __construct() { 
    	parent::__construct(); 
	}
	
	///////////////////////////////////////////////////////////////////////////
	// 테이블 처리
	///////////////////////////////////////////////////////////////////////////
	
	//테이블 리스트
	function getReviewList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"bb.mm_name",
			"cc.*",
		];
		
		//바디
		$body = "
			st_review aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
				inner join st_goods cc
					on aa.gd_idx = cc.gd_idx
		";
		
		//조건
		$where = [];
		if($params["search_type"]!=""){
			if($params["search_type"]=="all"){
				$where[] = 	" and (rv_subject like '%".$this->inject($params["search_value"])."%' or rv_content like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="rv_subject"){
				$where[] = 	" and (rv_subject like '%".$this->inject($params["search_value"])."%' )  ";
			}else if($params["search_type"]=="rv_content"){
				$where[] = 	" and (rv_content like '%".$this->inject($params["search_value"])."%' )  ";
			}
		}
		
		//정렬
		$order_by = "aa.reg_dt desc";
		
   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}
	
	//테이블 리스트
	function getGoodsInquiryList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"bb.mm_name",
			"bb.mm_id",
			"cc.*",
		];
		
		//바디
		$body = "
			st_goods_inquiry aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
				inner join st_goods cc
					on aa.gd_idx = cc.gd_idx
		";
		
		//조건
		$where = [];
		if($params["search_type"]!=""){
			if($params["search_type"]=="all"){
				$where[] = 	" and (gi_subject like '%".$this->inject($params["search_value"])."%' or gi_content like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="rv_subject"){
				$where[] = 	" and (gi_subject like '%".$this->inject($params["search_value"])."%' )  ";
			}else if($params["search_type"]=="rv_content"){
				$where[] = 	" and (gi_content like '%".$this->inject($params["search_value"])."%' )  ";
			}
		}
		
		//정렬
		$order_by = "aa.reg_dt desc";
		
   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}
   	
   	//테이블 리스트
	function getGoodsInquiryInfo($idx)
	{
		//바디
		$sql = "
			select aa.gi_idx
				,aa.gi_subject
				,aa.gi_content
				,aa.gi_aws_yn
				,aa.gi_aws
				,aa.reg_id
				,aa.mm_idx
				,cc.*
			FROM st_goods_inquiry aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
				inner join st_goods cc
					on aa.gd_idx = cc.gd_idx
			where aa.gi_idx = '".$this->inject($idx)."'
		";
		
		$result = $this->db->query($sql);
		return $result->row_array();
   	}
}