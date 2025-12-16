<?php
    class Manage extends MY_Controller{
		function __construct() {
	        parent::__construct();

			//모델로드
			$this->load->model('settingmodel','setting_model');
	    }

		//관리자 메뉴관리
		public function menu(){
			//파라미터 설정

			$data['info'] = [];
			$this->layout->admin('admin/setting/manage/menu',$data);
		}

		//관리자 메뉴 추가 수정
		public function menu_proc(){
			//파라미터 설정
			$params = input_post_all();

			//처리
			$result = [];
			if($params["proc_mode"]=="insert"){
				$insert = [
					"am_parent"=>$params["am_parent"],
					"am_name"=>$params["am_name"],
					"am_url"=>$params["am_url"],
					"am_depth"=>$params["am_depth"],
					"am_status"=>$params["am_status"],
					"am_child_list"=>$params["am_child_list"],
				];

				//저장
				$result = $this->setting_model->insertAdminMenu($insert);
			}else if($params["proc_mode"]=="update"){
				$update = [
					"am_name"=>$params["am_name"],
					"am_url"=>$params["am_url"],
					"am_status"=>$params["am_status"],
					"am_child_list"=>$params["am_child_list"],
				];

				//저장
				$where = ["am_idx"=>$params["am_idx"]];
				$result = $this->setting_model->updateAdminMenu($update,$where);
			}else if($params["proc_mode"]=="delete"){
				//삭제
				$where = ["am_idx"=>$params["am_idx"]];
				$result = $this->setting_model->deleteAdminMenu($where);
			}else if($params["proc_mode"]=="sort_change"){
				//정렬변경
				$current_info = $this->setting_model->getAdminMenu(["am_idx" => $params["am_idx"]]);
				$target_info = $this->setting_model->getAdminMenu(["am_idx" => $params["target_idx"]]);

				//기존 업데이트
				$update = [
					"am_sort"=>$target_info["am_sort"],
				];
				$result = $this->setting_model->updateAdminMenu($update,["am_idx" => $params["am_idx"]]);

				//대상 업데이트
				$update = [
					"am_sort"=>$current_info["am_sort"],
				];
				$result = $this->setting_model->updateAdminMenu($update,["am_idx" => $params["target_idx"]]);
			}

			json_return($result["code"],$result["msg"],"");
		}

		//메뉴 뎁스별 리스트
		public function menu_depth_list(){
			//설정값
			$depth = input_post("depth",1);
			$parent_id = input_post("parent_id","");

			//데이터 설정
			$params = [
				"depth" => $depth,
				"parent_id" => $parent_id,
			];

			$result = $this->setting_model->getMenuDepthList($params);

			$list_html = "";
			foreach($result as $key=> $row){
				$up_idx = ($key==0 ? "":$result[$key - 1]["am_idx"]);
				$down_idx = ((count($result)-1) == $key ? "":$result[$key + 1]["am_idx"]);

				$list_html = $list_html . "
					<tr class='clickable' data-am_idx='".$row["am_idx"]."' data-am_parent='".$row["am_parent"]."' data-am_name='".$row["am_name"]."' data-am_url='".$row["am_url"]."' data-am_depth='".$row["am_depth"]."' data-am_status='".$row["am_status"]."' data-am_child_list='".$row["am_child_list"]."'  data-up_idx='".$up_idx."' data-down_idx='".$down_idx."'>
						<td class='tc'>
							" . ($row["am_status"]=="Y" ? "<span class='able'>노출</span>":"<span class='disable'>비노출</span>") . "
						</td>
						<td>
							".$row["am_name"]."
						</td>
						<td>
							<a href='javascript:void(0);' class='btnUp'></a>
							<a href='javascript:void(0);' class='btnDown'></a>
							<a href='javascript:void(0);' class='btnEdit'>수정</a>
						</td>
					</tr>
				";
			}

			if(count($result)==0){
				$list_html = "
					<tr >
						<td colspan='3' class='tc pb-20 pt-20'>
							데이터가 없습니다.
						</td>
					</tr>
				";
			}

			json_return("00","조회성공",$list_html);
		}

		//운영자 계정관리
		public function user(){
			//파라미터 설정
			$page = input_get("page",1);
			$use_yn = input_get("use_yn","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
				"use_yn" => $use_yn,
			];
			$result = $this->setting_model->getAdminUserList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/setting/manage/user',$data);
		}

		//운영자 계정관리 등록/수정
		public function user_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);

			//권한 리스트
			$params = [];
			$params["search_value"] = "";
			$params["page"] = 1;
			$params["pagesize"] = 1000;
			$grant_list = $this->setting_model->getGrantList($params);
			$data['option_grant'] = getKeyValue($grant_list["list"],"mg_idx","mg_name");
			$data['info'] = $this->setting_model->getAdminUser($idx);

			//리턴 처리
			$this->layout->admin('admin/setting/manage/user_edit',$data);
		}

		//운영자 계정관리 등록,수정 처리
		public function user_edit_proc(){
			//파라미터 설정
			$params = input_post_all();

			//처리
			$result = [];
			if($params["proc_mode"]=="insert"){
				//아이디 중복체크
				$is_id = $this->setting_model->isAdminUser(["au_id"=>$params["au_id"]]);
				if($is_id){
					json_return("90","이미 사용중인 아이디가 있습니다.","");
					exit;
				}

				//제거
				unset($params["proc_mode"]);
				unset($params["au_pwd_c"]);

				//패스워드 암호화 처리
				$params["au_pwd"] = $this->common->getSqlPass($params["au_pwd"]);

				//저장
				$result = $this->setting_model->insertAdminUser($params);
			}else if($params["proc_mode"]=="update"){
				$update = [
					"mg_idx"=>$params["mg_idx"],
					"au_name"=>$params["au_name"],
					"au_hp"=>$params["au_hp"],
					"au_email"=>$params["au_email"],
          "au_status"=>$params["au_status"],
					"role_mem"=>$params["role_mem"],
          "role_recresu"=>$params["role_recresu"],
          "role_trade"=>$params["role_trade"],
          "role_ad"=>$params["role_ad"],
          "role_pay"=>$params["role_pay"],
          "role_site"=>$params["role_site"],
				];

				//비밀번호가 있으면 수정한다.
				if($params["au_pwd"]!=""){
					$update["au_pwd"] = $this->common->getSqlPass($params["au_pwd"]);
				}

				//저장
				$where = ["au_idx"=>$params["au_idx"]];
				$result = $this->setting_model->updateAdminUser($update,$where);
			}else if($params["proc_mode"]=="delete"){
				$where = ["au_idx"=>$params["idx"]];
				$result = $this->setting_model->deleteAdminUser($where);
			}

			json_return($result["code"],$result["msg"],"");
		}

		//운영자별 메뉴관리
		public function user_menu(){
			//파라미터 설정

			$data['info'] = [];
			$this->layout->admin('admin/setting/manage/user_menu',$data);
		}


		//권한별 메뉴관리
		public function grant_menu(){
			//파라미터 설정
			$page = input_get("page",1);
			$use_yn = input_get("use_yn","Y");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
				"use_yn" => $use_yn,
			];
			$result = $this->setting_model->getGrantList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => count($result),
				"pagenation" => null,
			];

			$this->layout->admin('admin/setting/manage/grant_menu',$data);
		}

		//권한별 메뉴관리
		public function grant_menu_edit(){
			//파라미터 설정
			$mg_idx = input_get("mg_idx",0);
			$master_yn = $this->admin->getMasterYn();

			//데이터 설정
			$info = $this->setting_model->getMenuGrant($mg_idx);

			$result = $this->setting_model->getMenuGrantList($mg_idx,$info["mg_master_yn"]);

			//1차 메뉴
			$menu_list = [];
			foreach($result as $key => $row){
				if($row["am_depth"]==1){
					$menu_list[$key] = $row;
					foreach($result as $key2=> $row2){
						if($row["am_idx"] == $row2["am_parent"]){
							$menu_list[$key]["child"][$key2] = $row2;
							foreach($result as $row3){
								if($row2["am_idx"] == $row3["am_parent"])
								{
									$menu_list[$key]["child"][$key2]["child"][] = $row3;
								}
							}
						}
					}
				}
			}

			//리턴 설정
			$data = [
				"info" => $info,
				"list" => $menu_list,
			];

			$this->layout->admin('admin/setting/manage/grant_menu_edit',$data);
		}

		//권한별 메뉴 저장
		public function grant_menu_proc(){
			//파라미터 설정
			$params = input_post_all();

			//처리
			$result = [];
			if($params["proc_mode"]=="insert"){
				//권한 마스터 저장.
				$data = [
					"mg_name" => $params["mg_name"],
				];

				$result = $this->setting_model->db_insert("st_admin_grant",$data);

				//저장
				$result = $this->setting_model->insertGrantMenu($result["key"],$params["menu_ids"]);
			}else if($params["proc_mode"]=="update"){
				$data = [
					"mg_name" => $params["mg_name"],
				];

				$result = $this->setting_model->db_update("st_admin_grant",$data,["mg_idx"=>$params["mg_idx"]]);

				//저장
				$result = $this->setting_model->insertGrantMenu($params["mg_idx"],$params["menu_ids"]);
			}else if($params["proc_mode"]=="delete"){
				//삭제
				$where = ["mg_idx"=>$params["idx"]];
				$result = $this->setting_model->db_delete("st_admin_grant",$where);
			}

			json_return($result["code"],$result["msg"],"");
		}

		//SNS 로그인 API 설정
		public function sns_api(){
			//파라미터 설정

			$data['info'] = $this->setting_model->getSettingInfo();
			$this->layout->admin('admin/setting/manage/sns_api',$data);
		}

		//SNS 로그인 API 설정 처리
		public function sns_api_proc(){
			//파라미터 설정
			$params = input_post_all();

			$params["st_kakao_login_yn"] = isset($params["st_kakao_login_yn"]) ? $params["st_kakao_login_yn"]:"N";
			$params["st_naver_login_yn"] = isset($params["st_naver_login_yn"]) ? $params["st_naver_login_yn"]:"N";
			$params["st_share_use_kakao"] = isset($params["st_share_use_kakao"]) ? $params["st_share_use_kakao"]:"N";
			$params["st_share_use_kakaostory"] = isset($params["st_share_use_kakaostory"]) ? $params["st_share_use_kakaostory"]:"N";
			$params["st_share_use_facebook"] = isset($params["st_share_use_facebook"]) ? $params["st_share_use_facebook"]:"N";
			$params["st_share_use_twiter"] = isset($params["st_share_use_twiter"]) ? $params["st_share_use_twiter"]:"N";
			$params["st_share_use_naver"] = isset($params["st_share_use_naver"]) ? $params["st_share_use_naver"]:"N";
			$params["st_share_use_band"] = isset($params["st_share_use_band"]) ? $params["st_share_use_band"]:"N";

			//처리
			$result = [];
			$update = $params;

			//저장
			$where = ["st_type"=>"shopping"];	//이부분세션으로 변경해야함
			$result = $this->setting_model->updateSettingBasic($update,$where);

			json_return($result["code"],$result["msg"],"");
		}

		//SNS 로그인 API 설정
		public function fav_menu(){

			//파라미터 설정
			$admin_id = $this->admin->getAdminId();
			$mg_idx = $this->admin->getMgIdx();
			$master_yn = $this->admin->getMasterYn();

			$result = $this->setting_model->getMenuGrantList($mg_idx,$master_yn);

			//1차 메뉴
			$menu_list = [];
			foreach($result as $key => $row){
				if($row["am_depth"]==1){
					$menu_list[$key] = $row;
					foreach($result as $key2=> $row2){
						if($row["am_idx"] == $row2["am_parent"]){
							$menu_list[$key]["child"][$key2] = $row2;
							foreach($result as $row3){
								if($row2["am_idx"] == $row3["am_parent"])
								{
									$menu_list[$key]["child"][$key2]["child"][] = $row3;
								}
							}
						}
					}
				}
			}

			$data['menu_list'] = $menu_list;
			$data['info'] = [];
			$this->layout->admin('admin/setting/manage/fav_menu',$data);
		}

		//메뉴 뎁스별 리스트
		public function fav_menu_list(){
			//파라미터 설정
			$depth = input_post("depth",1);
			$parent_id = input_post("parent_id","");
			$admin_id = $this->admin->getAdminId();

			//데이터 설정
			$result = $this->setting_model->getFavMenuList(["depth"=>$depth,"admin_id"=>$admin_id,"parent_id"=>$parent_id]);

			$list_html = "";
			foreach($result as $key=> $row){
				$up_idx = ($key==0 ? "":$result[$key - 1]["fm_idx"]);
				$down_idx = ((count($result)-1) == $key ? "":$result[$key + 1]["fm_idx"]);

				$list_html = $list_html . "
					<tr class='clickable' data-fm_idx='".$row["fm_idx"]."' data-fm_parent='".$row["fm_parent"]."' data-fm_name='".$row["fm_name"]."' data-am_idx='".$row["am_idx"]."' data-fm_depth='".$row["fm_depth"]."' data-fm_status='".$row["fm_status"]."' data-up_idx='".$up_idx."' data-down_idx='".$down_idx."'>
						<td class='tc'>
							" . ($row["fm_status"]=="Y" ? "<span class='able'>노출</span>":"<span class='disable'>비노출</span>") . "
						</td>
						<td>
							".$row["fm_name"]."
						</td>
						<td>
							<a href='javascript:void(0);' class='btnUp'></a>
							<a href='javascript:void(0);' class='btnDown'></a>
							<a href='javascript:void(0);' class='btnEdit'>수정</a>
						</td>
					</tr>
				";
			}

			if(count($result)==0){
				$list_html = "
					<tr >
						<td colspan='3' class='tc pb-20 pt-20'>
							데이터가 없습니다.
						</td>
					</tr>
				";
			}

			json_return("00","조회성공",$list_html);
		}

		//관리자 자주쓰는 메뉴 추가 수정
		public function fav_menu_proc(){
			//파라미터 설정
			$params = input_post_all();
			$admin_id = $this->admin->getAdminId();

			//처리
			$result = [];
			if($params["proc_mode"]=="insert"){
				$insert = [
					"fm_parent"=>$params["fm_parent"],
					"admin_id"=>$admin_id,
					"fm_name"=>$params["fm_name"],
					"fm_idx"=>$params["fm_idx"],
					"am_idx"=>$params["am_idx"],
					"fm_depth"=>$params["fm_depth"],
					"fm_status"=>$params["fm_status"],
				];

				//저장
				$result = $this->setting_model->db_insert("st_admin_fav_menu",$insert);
				$this->setting_model->db_update("st_admin_fav_menu",["fm_sort"=>$result["key"]],["fm_idx"=>$result["key"]]);

			}else if($params["proc_mode"]=="update"){
				$update = [
					"fm_name"=>$params["fm_name"],
					"fm_idx"=>$params["fm_idx"],
					"am_idx"=>$params["am_idx"],
					"fm_status"=>$params["fm_status"],
				];

				//저장
				$where = ["fm_idx"=>$params["fm_idx"]];
				$result = $this->setting_model->db_update("st_admin_fav_menu",$update,$where);
			}else if($params["proc_mode"]=="delete"){
				//삭제
				$where = ["fm_idx"=>$params["fm_idx"]];
				$result = $this->setting_model->db_delete("st_admin_fav_menu",$where);
			}else if($params["proc_mode"]=="sort_change"){
				//정렬변경
				$current_info = $this->setting_model->db_row("st_admin_fav_menu",["fm_idx" => $params["fm_idx"]]);
				$target_info = $this->setting_model->db_row("st_admin_fav_menu",["fm_idx" => $params["target_idx"]]);

				//기존 업데이트
				$update = [
					"fm_sort"=>$target_info["fm_sort"],
				];
				$result = $this->setting_model->db_update("st_admin_fav_menu",$update,["fm_idx" => $params["fm_idx"]]);

				//대상 업데이트
				$update = [
					"fm_sort"=>$current_info["fm_sort"],
				];
				$result = $this->setting_model->db_update("st_admin_fav_menu",$update,["fm_idx" => $params["target_idx"]]);
			}

			json_return($result["code"],$result["msg"],"");
		}


		//영업부서 관리 리스트
		public function sales_depth_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$use_yn = input_get("use_yn","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
			];
			$result = $this->setting_model->getSalesDepthList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/setting/manage/sales_depth_list',$data);
		}

		//영업부서 관리 수정/등록
		public function sales_depth_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);

			$data['info'] = $this->setting_model->getSalesDepth($idx);

			//리턴 처리
			$this->layout->admin('admin/setting/manage/sales_depth_edit',$data);
		}

		//영업부서 수정/등록 처리
		public function sales_depth_proc(){
			//파라미터 설정
			$params = input_post_all();

			//처리
			$result = [];
			if($params["proc_mode"]=="insert"){

				$insert = [
					"cd_grp"=>"sales_depth",
					"cd_val"=>$params["cd_val"],
					"cd_val2"=>$params["cd_val2"],
				];

				//저장
				$result = $this->setting_model->insertSalesDepth($insert);
			}else if($params["proc_mode"]=="update"){
				$update = [
					"cd_val"=>$params["cd_val"],
					"cd_val2"=>$params["cd_val2"],
				];

				//저장
				$where = ["cd_idx"=>$params["cd_idx"]];
				$result = $this->setting_model->updateSalesDepth($update,$where);
			}else if($params["proc_mode"]=="delete"){

				//삭제
				$where = ["cd_idx"=>$params["cd_idx"]];
				$result = $this->setting_model->deleteSalesDepth($where);
			}

			json_return($result["code"],$result["msg"],"");
		}
    }
?>
