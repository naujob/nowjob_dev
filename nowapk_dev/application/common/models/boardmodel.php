<?php
class boardModel extends MY_Model {
   	function __construct() {
    	parent::__construct();
	}

	///////////////////////////////////////////////////////////////////////////
	// 테이블 처리
	///////////////////////////////////////////////////////////////////////////

	//테이블 리스트
	function getTableList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"(case when tb_show_yn = 'Y' then '노출' else '비노출' end) as show_yn_nm",
			"(case when tb_basic_yn = 'Y' then '기본' else '일반' end) as basic_yn_nm",
			"(case when tb_location = 'board' then '게시판' else '커뮤니티' end) as location_nm",
		];

		//바디
		$body = "
			st_table aa
		";

		//조건
		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (tb_name like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="tb_name"){
				$where[] = 	" and (tb_name like '%".$this->inject($params["search_value"])."%')  ";
			}
		}

		if($params["show_yn"]!=""){
			$where[] = 	" and (tb_show_yn = '".$this->inject($params["show_yn"])."')  ";
		}

		//정렬
		$order_by = "aa.tb_basic_yn asc,aa.tb_idx asc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

	//테이블 조회
	function hasDupTable($where){
   		return $this->db_is_data("st_table",$where);
	}

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

	//테이블 저장
	function insertTable($data){
		$result = $this->db_insert("st_table",$data);
		return $result;
	}

	//테이블 수정
	function updateTable($data,$where){
		return $this->db_update("st_table",$data,$where);
	}


	///////////////////////////////////////////////////////////////////////////
	// 게시글 처리
	///////////////////////////////////////////////////////////////////////////

	//테이블 리스트
	function getBoardList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"bb.tb_name",
			"cc.mm_name",
			"cc.mm_type",
			"cc.mm_comp_name",
			"(case when aa.reg_id='admin' then '마스터 관리자' else au.au_name end) as admin_name",
			"(case when (date_format(bd_sdate,'%Y%m%d') <= date_format(now(),'%Y%m%d') and date_format(bd_edate,'%Y%m%d') >= date_format(now(),'%Y%m%d') ) then '진행중'
			  when date_format(bd_sdate,'%Y%m%d') > date_format(now(),'%Y%m%d') then '대기'
			  else '종료'	 end)  as bd_ing",
		];

		//바디
		$body = "
			st_board aa
				inner join st_table bb
					on aa.tb_id = bb.tb_id
				left join st_member cc
					on aa.mm_id = cc.mm_id and cc.mm_status = 'Y'
				left join st_admin_user au
					on aa.reg_id = au.au_id
		";

		//조건
		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (bd_subject like '%".$this->inject($params["search_value"])."%' or cc.mm_name like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="bd_subject"){
				$where[] = 	" and (bd_subject like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="bd_writer"){
				$where[] = 	" and (cc.mm_name like '%".$this->inject($params["search_value"])."%')  ";
			}
		}

		if($params["tb_id"]!=""){
			$where[] = 	" and (aa.tb_id = '".$this->inject($params["tb_id"])."')  ";
		}

		//아이디 처리
		if(isset($params["mm_id"]) && $params["mm_id"]!=""){
			$where[] = 	" and (aa.mm_id = '".$this->inject($params["mm_id"])."') ";
		}

		//아이디 처리
		if(isset($params["bd_show_type"]) && $params["bd_show_type"]!=""){
			$where[] = 	" and (aa.bd_show_type = 'ALL' or aa.bd_show_type = '".$this->inject($params["bd_show_type"])."') ";
		}

		if(isset($params["bd_aws_yn"]) && $params["bd_aws_yn"]!=""){
			if($params["bd_aws_yn"]=="Y"){
				$where[] = 	" and (aa.bd_aws <> '') ";
			}else{
				$where[] = 	" and (aa.bd_aws = '') ";
			}
		}

		if(isset($params["date_status"])){
			if($params["date_status"]=="ing"){
				$where[] = 	" and (date_format(aa.bd_edate,'%Y%m%d') >= date_format(now(),'%Y%m%d') and date_format(aa.bd_sdate,'%Y%m%d') <= date_format(now(),'%Y%m%d'))   ";
			}else if($params["date_status"]=="end"){
				$where[] = 	" and (date_format(aa.bd_edate,'%Y%m%d') < date_format(now(),'%Y%m%d'))  ";
			}
		}

		if(isset($params["bd_cate"]) && $params["bd_cate"]!=""){
			$where[] = 	" and (aa.bd_cate = '".$this->inject($params["bd_cate"])."')  ";
		}


		if(isset($params["sdate"]) && $params["sdate"]!=""){
			$where[] = 	" and (date_format(aa.reg_dt,'%Y-%m-%d') >= '".$this->inject($params["sdate"])."')  ";
		}

		if(isset($params["edate"]) && $params["edate"]!=""){
			$where[] = 	" and (date_format(aa.reg_dt,'%Y-%m-%d') <= '".$this->inject($params["edate"])."')  ";
		}

		//정렬
		$order_by = "aa.bd_notice_yn desc, aa.reg_dt desc";

		//정렬 추가 처리
		if(isset($params["sort"]) && $params["sort"]!=""){
			if($params["sort"]=="reg_dt"){
				$order_by = "aa.bd_notice_yn asc, aa.reg_dt desc";
			}else if($params["sort"]=="hit"){
				$order_by = "aa.bd_notice_yn asc, aa.bd_hit desc";
			}
		}

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

   	function getCommunityBestList(){
   		$sql = "
			select *
			FROM st_board
			where tb_id = 'community'
			and bd_notice_yn = 'Y'
			order by reg_dt desc
			limit 0,5
		";

		$query = $this->db->query($sql);
		return $query->result_array();
   	}

	//신고내역 리스트
	function getMyReportList($params)
	{
		//컬럼
		$cols = [
			"aa.*",
			"mm.mm_name",
			"(case
				when aa.br_type = 'trade' then '중고거래'
				when aa.br_type = 'community'  then '커뮤니티'
				when aa.br_type = 'resume'  then '이력서'
				when aa.br_type = 'recruit'  then '채용공고'
				else '' end) as br_type_nm",
			"(case
				when aa.br_type = 'trade' then cc.td_name
				when aa.br_type = 'community'  then bb.bd_subject
				when aa.br_type = 'resume'  then dd.ru_name
				when aa.br_type = 'recruit'  then ee.rc_name
				else '' end) as subject"
		];

		//바디
		$body = "
			st_board_report aa
				left join st_board bb
					on aa.br_key = bb.bd_idx and br_type = 'community'
				left join st_trade cc
					on aa.br_key =cc.td_idx and br_type = 'trade'
				left join st_resume dd
					on aa.br_key =dd.ru_idx and br_type = 'resume'
				left join st_recruit ee
					on aa.br_key =ee.rc_idx and br_type = 'recruit'
				inner join st_member mm
					on aa.mm_idx = mm.mm_idx
		";

		//조건
		$where = [];
    //내가쓴글만 보도록
    $mm_idx = $this->user->getUserIdx();
    $where[] = " and aa.mm_idx = '".$mm_idx."'  ";

		if($params["mm_idx"]!=""){
			$where[] = " and (aa.mm_idx = '".$this->inject($params["mm_idx"])."')  ";
		}

		if(isset($params["search_type"]) && $params["search_type"]!=""){
			if(isset($params["search_value"]) && $params["search_value"]!=""){
				if($params["search_type"]=="subject"){
					$where[] = " and (
						cc.td_name  like '%".$this->inject($params["search_value"])."%'
						or bb.bd_subject   like '%".$this->inject($params["search_value"])."%'
						or dd.ru_name  like '%".$this->inject($params["search_value"])."%'
						or ee.rc_name  like '%".$this->inject($params["search_value"])."%'

					)  ";
				}else if($params["search_type"]=="br_desc"){
					$where[] = " and (
						aa.br_desc  like '%".$this->inject($params["search_value"])."%'
					)  ";
				}
			}
		}

		if(isset($params["br_type"]) && $params["br_type"]!=""){
			$where[] = " and (aa.br_type = '".$this->inject($params["br_type"])."')  ";
		}

		//정렬
		$order_by = "aa.br_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

   	//게시판 정보 조회
	function getBoardReport($idx){
		//컬럼
		$cols = [
			"aa.*",
			"(case
				when aa.br_type = 'trade' then '중고거래'
				when aa.br_type = 'community'  then '커뮤니티'
				when aa.br_type = 'resume'  then '이력서'
				when aa.br_type = 'recruit'  then '채용공고'
				else '' end) as br_type_nm",
			"(case
				when aa.br_type = 'trade' then cc.td_name
				when aa.br_type = 'community'  then bb.bd_subject
				when aa.br_type = 'resume'  then dd.ru_name
				when aa.br_type = 'recruit'  then ee.rc_name
				else '' end) as subject"
		];

		//바디
		$body = "st_board_report aa
					left join st_board bb
						on aa.br_key = bb.bd_idx and br_type = 'community'
					left join st_trade cc
						on aa.br_key =cc.td_idx and br_type = 'trade'
					left join st_resume dd
						on aa.br_key =dd.ru_idx and br_type = 'resume'
					left join st_recruit ee
						on aa.br_key =ee.rc_idx and br_type = 'recruit'
		";

		//조건
		$where[] = " and (aa.br_idx = '".$this->inject($idx)."')  ";

		//정렬
		$order_by = "aa.br_idx desc";

   		return $this->db_select_row($cols,$body,$where,$order_by);
	}

	//게시판 정보 조회
	function getBoard($idx){
		//컬럼
		$cols = [
			"aa.*",
			"cc.mm_name",
			"(case when aa.reg_id='admin' then '마스터 관리자' else au.au_name end) as admin_name",
			"(case when (date_format(bd_sdate,'%Y%m%d') <= date_format(now(),'%Y%m%d') and date_format(bd_edate,'%Y%m%d') >= date_format(now(),'%Y%m%d') ) then '진행중' else '종료'	 end)  as bd_ing",
		];

		//바디
		$body = "
			st_board aa
				left join st_member cc
					on aa.mm_id = cc.mm_id
				left join st_admin_user au
					on aa.reg_id = au.au_id
		";

		//조건
		$where[] = " and (bd_idx = '".$this->inject($idx)."')  ";

		//정렬
		$order_by = "aa.bd_idx desc";

   		return $this->db_select_row($cols,$body,$where,$order_by);
	}


	//게시판정보 저장
	function insertBoard($data){
		return $this->db_insert("st_board",$data);
	}

	//게시판정보 수정
	function updateBoard($data,$where){
		return $this->db_update("st_board",$data,$where);
	}

	//게시글 삭제
	function deleteBoard($where){
		return $this->db_delete("st_board",$where);
	}

	//댓글 등록
	function insertBoardComment($data){
		$result = $this->db_insert("st_board_comment",$data);

		if($result["code"]=="00"){
			if($data["parent_idx"]=="0"){
				$this->db_update("st_board_comment",["parent_idx"=>$result["key"]],["bc_idx"=>$result["key"]]);
			}
		}
		return $result;
	}

	//댓글 삭제
	function deleteBoardComment($where){
		$result = $this->db_delete("st_board_comment",$where);
		return $result;
	}

	//댓글 리스트
	function getBoardCommentList($bd_idx){
		$sql = "
			SELECT aa.*
				,bb.mm_name
				,bb.mm_id
				,bb.mm_thumb
				,bb.mm_img1
				,bb.mm_img2
				,bb.mm_img3
				,bb.mm_img4
				,bb.mm_img5
				,(case when aa.bc_idx!=aa.parent_idx then '2' else '1' end) as reply_depth
                ,(select count(*) FROM st_board_comment_fav kk where kk.bc_idx = aa.bc_idx) as fav_cnt
			FROM st_board_comment aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
			where bd_idx = '$bd_idx'
			order by aa.parent_idx asc,aa.bc_idx asc
			limit 0,300
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	//이전글 다음글
	function getBoardNextPrev($idx,$tb_id,$type){
		$updown = $type=="prev" ? " < ":" > ";
		$orderby = $type=="prev" ? " order by bd_idx desc ":" order by bd_idx asc ";
		$sql = "
			SELECT aa.*
			FROM st_board aa
			where tb_id = '$tb_id'
			and bd_idx $updown $idx
			$orderby
		";

		$query = $this->db->query($sql);
		return $query->row_array();
	}

	//카테고리 그룹
	function getBoardCateGroup($tb_id){
		$sql = "
			SELECT aa.bd_cate
			FROM st_board aa
			where tb_id = '$tb_id'
			group by aa.bd_cate
			order by aa.bd_cate asc
		";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function setBoardCmtFav($where,$data){
		if($this->db_is_data("st_board_comment_fav",$where)){
			$this->deleteBoardCmtFav($where);
			return false;
		}else{
			$this->insertBoardCmtFav($data);
			return true;
		}
	}

    # 댓글 등록
	function insertBoardCmtFav($data){
		return $this->db_insert("st_board_comment_fav",$data);
	}

    # 댓글 삭제
	function deleteBoardCmtFav($where){
		return $this->db_delete("st_board_comment_fav",$where);
	}

    # 좋아요 갯수
	function getBoardCmtFavCnt($bc_idx){
		$sql = "
			SELECT count(*) as cnt
			FROM st_board_comment aa
				inner join st_board_comment_fav bb
					on aa.bc_idx = bb.bc_idx
			where bb.bc_idx = '$bc_idx'
		";

		$query = $this->db->query($sql);
		$row = $query->row_array();

		if($row==null){
			return 0;
		}else{
			return $row["cnt"];
		}
	}

    function setBoardFav($where,$data){
		if($this->db_is_data("st_board_fav",$where)){
			$this->deleteBoardFav($where);
			return false;
		}else{
			$this->insertBoardFav($data);
			return true;
		}
	}

    # 댓글 등록
	function insertBoardFav($data){
		return $this->db_insert("st_board_fav",$data);
	}

    # 댓글 삭제
	function deleteBoardFav($where){
		return $this->db_delete("st_board_fav",$where);
	}

    # 좋아요 갯수
	function getBoardFavCnt($bd_idx){
		$sql = "
			SELECT count(*) as cnt
			FROM st_board aa
				inner join st_board_fav bb
					on aa.bd_idx = bb.bd_idx
			where aa.bd_idx = '$bd_idx'
		";

		$query = $this->db->query($sql);
		$row = $query->row_array();

		if($row==null){
			return 0;
		}else{
			return $row["cnt"];
		}
	}

    # 댓글 등록
    function insertBoardReport($data){
        return $this->db_insert("st_board_report",$data);
    }

    # 댓글 삭제
    function deleteBoardReport($where){
        return $this->db_delete("st_board_report",$where);
    }

    function getBoardNewCnt($bd_name){
      $sql = "
        SELECT count(*) as cnt
        FROM st_board aa
        where tb_id = '$bd_name'
        AND   reg_dt >= DATE_SUB(NOW(), INTERVAL 5 DAY)
      ";

      $query = $this->db->query($sql);
      $row = $query->row_array();

      if($row==null){
        return 0;
      }else{
        return $row["cnt"];
      }
    }

}
