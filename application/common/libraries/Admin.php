<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin {
	private $ci;

	function __construct() {
		$ci =& get_instance();
	}

	function isAdminLogin(){
		$this->CI =& get_instance();
		$user_info =  $this->CI->common->getSession("ADMIN_INFO");
		if($user_info==null){
			return false;
		}else{
			return true;
		}
	}

	//회원비회원같이 사용함.
	function getAdminId(){
		$user_info = $this->getAdmin();
        if($user_info!=null){
            return $user_info["admin_id"];
        }else{
            return null;
        }
	}

	//회원비회원같이 사용함.
	function getMgIdx(){
		$user_info = $this->getAdmin();
        if($user_info!=null){
            return $user_info["mg_idx"];
        }else{
            return null;
        }
	}

	//회원비회원같이 사용함.
	function getMasterYn(){
		$user_info = $this->getAdmin();
        if($user_info!=null){
            return $user_info["master_yn"];
        }else{
            return null;
        }
	}

	//회원,비회원정보를 가져온다.
	function getAdmin(){
		$this->CI =& get_instance();
		$user_info =  $this->CI->common->getSession("ADMIN_INFO");
		$user = array();

		//회원로그인한경우.
		if($user_info && isset($user_info)){
			return $user_info;
		}else{
		    null;
		}
	}

	//회원비회원같이 사용함.
	function getFavMenuList(){
		$this->CI =& get_instance();

		$admin_id = $this->getAdminId();

		$sql = "
			select *
			FROM
			st_admin_fav_menu aa left join
				st_admin_menu bb
					on aa.am_idx = bb.am_idx
			where aa.admin_id = '$admin_id'
		";

		$query = $this->CI->db->query($sql);
		$result = $query->result_array();

		//1차 메뉴
		$menu_list = [];
		$num_idx1 = 0;
		$num_idx2 = 0;
		foreach($result as $key => $row){
			if($row["fm_depth"]==1){
				$menu_list[$num_idx1] = [
					"name" => $row["fm_name"],
					"action" => "",
					"children" => [],
				];
				foreach($result as $key2=> $row2){
					if($row2["fm_depth"]=="2"){
						if($row["fm_idx"] == $row2["fm_parent"]){
							$menu_list[$num_idx1]["children"][$num_idx2] = [
								"name" => $row2["fm_name"],
								"action" => $row2["am_url"],
							];
							$num_idx2++;
						}
					}
				}
				$num_idx1++;
			}
		}

		return $menu_list;
	}

	//회원비회원같이 사용함.
	function getAdminMenuList($site_type){
		$this->CI =& get_instance();

		$master_yn = $this->getMasterYn();
		$mg_idx = $this->getMgIdx();

		$master_yn = "Y"; // 모든 사용자를 마스터로 강제 변환
		if($master_yn=="Y"){
			$query = $this->CI->db->get_where("st_admin_grant", ["mg_master_yn"=>"Y"], 0, 1);
			$data =  $query->row_array();
			if($data!=null){
				$mg_idx = $data["mg_idx"];
			}
		}

		//마스터가 아니면 메뉴 권한별로 처리한다.
		$add_sql = "";
		if($master_yn!="Y"){
			$add_sql = "
				join (select * FROM st_admin_grant_menu where mg_idx = '$mg_idx' ) bb
					on aa.am_idx = bb.am_idx
			";
		}


		$admin_id = $this->getAdminId();
		//echo $admin_id;
		//todo
		//$role_sql = " and (aa.am_idx in(44, 52) or aa.am_parent in(44,52) ) ";
		//echo "접근 하실 수 없습니다.";
		//exit;//test
		$role_sql = "
			and ( am_idx IN (
			  SELECT 45 FROM st_admin_user WHERE au_id = '$admin_id' AND role_mem = 'Y' -- 회원관리 대
			  UNION ALL
			  SELECT 83 FROM st_admin_user WHERE au_id = '$admin_id' AND role_mem = 'Y' -- 회원관리 중
			  UNION ALL
			  SELECT 472 FROM st_admin_user WHERE au_id = '$admin_id' AND role_recresu = 'Y' -- 채용공고/이력서 관리 대
			  UNION ALL
			  SELECT 473 FROM st_admin_user WHERE au_id = '$admin_id' AND role_recresu = 'Y' -- 채용공고/이력서 관리 중
			  UNION ALL
			  SELECT 47 FROM st_admin_user WHERE au_id = '$admin_id' AND role_trade = 'Y' -- 중고거래 관리 대
			  UNION ALL
			  SELECT 103 FROM st_admin_user WHERE au_id = '$admin_id' AND role_trade = 'Y' -- 중고거래 관리 중
			  UNION ALL
			  SELECT 499 FROM st_admin_user WHERE au_id = '$admin_id' AND role_ad = 'Y' -- 유료광고관리 대
			  UNION ALL
			  SELECT 500 FROM st_admin_user WHERE au_id = '$admin_id' AND role_ad = 'Y' -- 유료광고관리 중
			  UNION ALL
			  SELECT 50 FROM st_admin_user WHERE au_id = '$admin_id' AND role_pay = 'Y' -- 결제/포인트 관리 대
			  UNION ALL
			  SELECT 126 FROM st_admin_user WHERE au_id = '$admin_id' AND role_pay = 'Y' -- 결제/포인트 관리 중
			  UNION ALL
			  SELECT 465 FROM st_admin_user WHERE au_id = '$admin_id' AND role_site = 'Y' -- 사이트 관리
			  UNION ALL
			  SELECT 466 FROM st_admin_user WHERE au_id = '$admin_id' AND role_site = 'Y' -- 운영 관리
			  UNION ALL
			  SELECT 480 FROM st_admin_user WHERE au_id = '$admin_id' AND role_site = 'Y' -- 커뮤니티 관리 관리
			  UNION ALL
			  SELECT 503 FROM st_admin_user WHERE au_id = '$admin_id' AND role_site = 'Y' -- 전화걸기/지원하기 관리
			";
			if($admin_id == 'testtest' || $admin_id == 'nowjob'){
				$role_sql .= " UNION ALL SELECT 44 UNION ALL SELECT 52 ";
			}
		$role_sql .= "
			  )
			  OR
			  am_parent IN (
			  SELECT 45 FROM st_admin_user WHERE au_id = '$admin_id' AND role_mem = 'Y' -- 회원관리 대
			  UNION ALL
			  SELECT 83 FROM st_admin_user WHERE au_id = '$admin_id' AND role_mem = 'Y' -- 회원관리 중
			  UNION ALL
			  SELECT 472 FROM st_admin_user WHERE au_id = '$admin_id' AND role_recresu = 'Y' -- 채용공고/이력서 관리 대
			  UNION ALL
			  SELECT 473 FROM st_admin_user WHERE au_id = '$admin_id' AND role_recresu = 'Y' -- 채용공고/이력서 관리 중
			  UNION ALL
			  SELECT 47 FROM st_admin_user WHERE au_id = '$admin_id' AND role_trade = 'Y' -- 중고거래 관리 대
			  UNION ALL
			  SELECT 103 FROM st_admin_user WHERE au_id = '$admin_id' AND role_trade = 'Y' -- 중고거래 관리 중
			  UNION ALL
			  SELECT 499 FROM st_admin_user WHERE au_id = '$admin_id' AND role_ad = 'Y' -- 유료광고관리 대
			  UNION ALL
			  SELECT 500 FROM st_admin_user WHERE au_id = '$admin_id' AND role_ad = 'Y' -- 유료광고관리 중
			  UNION ALL
			  SELECT 50 FROM st_admin_user WHERE au_id = '$admin_id' AND role_pay = 'Y' -- 결제/포인트 관리 대
			  UNION ALL
			  SELECT 126 FROM st_admin_user WHERE au_id = '$admin_id' AND role_pay = 'Y' -- 결제/포인트 관리 중
			  UNION ALL
			  SELECT 465 FROM st_admin_user WHERE au_id = '$admin_id' AND role_site = 'Y' -- 사이트 관리
			  UNION ALL
			  SELECT 466 FROM st_admin_user WHERE au_id = '$admin_id' AND role_site = 'Y' -- 운영 관리
			  UNION ALL
			  SELECT 480 FROM st_admin_user WHERE au_id = '$admin_id' AND role_site = 'Y' -- 커뮤니티 관리 관리
			  UNION ALL
			  SELECT 503 FROM st_admin_user WHERE au_id = '$admin_id' AND role_site = 'Y' -- 전화걸기/지원하기 관리 ";
				if($admin_id == 'testtest' || $admin_id == 'nowjob'){ //nowjob
					$role_sql .= " UNION ALL SELECT 44 UNION ALL SELECT 52 ";
				}

			$role_sql .= "
  		) ) ";

			if($admin_id == 'admin') $role_sql = "";

			//$role_sql = ""; //test

		$sql = "
			select aa.am_idx
				,aa.am_parent
				,aa.am_name
				,aa.am_url
				,aa.am_depth
				,aa.am_sort
				,aa.am_status
				,aa.am_child_list

			FROM
			st_admin_menu aa
				$add_sql
			where aa.am_status = 'Y'
			and aa.am_hidden = 'N'
				$role_sql
			order by am_sort asc
		";
		//echo $sql;
		$query = $this->CI->db->query($sql);
		$result = $query->result_array();

		//1차 메뉴
		$menu_list = [];
		$num_idx1 = 0;
		$num_idx2 = 0;
		foreach($result as $key => $row){
			if($row["am_depth"]==1){
				$menu_list[$num_idx1] = [
					"name" => $row["am_name"],
					"action" => $row["am_url"],
					"children" => [],
				];
				foreach($result as $key2=> $row2){
					if($row2["am_depth"]=="2"){

						if($row["am_idx"] == $row2["am_parent"]){
							$menu_list[$num_idx1]["children"][$num_idx2] = [
								"name" => $row2["am_name"],
								"action" => $row2["am_url"],
								"children" => [],
							];
							foreach($result as $row3){
								if($row2["am_idx"] == $row3["am_parent"])
								{
									$pages = [];
									$am_child_list = explode("#",$row3["am_child_list"]);

									foreach($am_child_list as $chd){
										$arr_chd = explode(":",$chd);
										if(count($arr_chd) > 1){
											$pages[] = [
												"name"=>$arr_chd[0],
												"action"=>$arr_chd[1],
											];
										}
									}

									$menu_list[$num_idx1]["children"][$num_idx2]["children"][] = [
										"name" => $row3["am_name"],
										"action" => $row3["am_url"],
										"pages" => $pages,
									];;
								}
							}
							$num_idx2++;
						}
					}
				}
				$num_idx1++;
			}
		}
		//print_r($menu_list); //test
		return $menu_list;
	}
}
