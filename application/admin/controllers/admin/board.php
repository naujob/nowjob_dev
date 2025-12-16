<?php
    class Board extends MY_Controller{
		function __construct() { 
	        parent::__construct();
			
			//모델로드
			$this->load->model('settingmodel','setting_model');
			$this->load->model('boardmodel','board_model');
			$this->load->model('membermodel','member_model');
	    } 
		
		//테이블 목록
		function table_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$use_yn = input_get("use_yn","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$show_yn = input_get("show_yn","");
			
			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		        "show_yn" => $show_yn
		    ];
			$result = $this->board_model->getTableList($params);
		
			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
					 "show_yn" => $show_yn
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/board/table_list',$data);
		}
		
		//테이블 상세
		function table_edit(){
			//파라미터 설정
		    $idx = input_get("idx",0);
		    
		    //데이터 조회
		    $data['info'] = $this->board_model->getTable($idx,"");
		    
		    //리턴 처리
		    $this->layout->admin('admin/board/table_edit',$data);
		}

		//테이블 등록,수정
		function table_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();
		    
		    //처리
		    $result = [];
		    if($params["proc_mode"]=="insert"){
		    	//테이블명 중복확인
		    	$dup = $this->board_model->hasDupTable(["tb_id"=>trim($params["tb_id"])]);
				
				if($dup==true){
					json_return("91","이미 사용중인 게시판 아이디가 있습니다.","");
					exit;
				}
		    	
		        $insert = [
		            "tb_id" => trim($params["tb_id"]),
		            "tb_name" => $params["tb_name"],
		            "tb_show_yn" => $params["tb_show_yn"],
		            "tb_location" => $params["tb_location"],
		            "tb_period_yn" => $params["tb_period_yn"],
		            "tb_basic_yn" => "N",
		            "tb_img_yn" => $params["tb_img_yn"],
		            "tb_file_yn" => $params["tb_file_yn"],
		            "tb_page_cnt" => $params["tb_page_cnt"],
		            "tb_grant_add" => $params["tb_grant_add"],
		            "tb_grant_cmt" => $params["tb_grant_cmt"],
		            "tb_grant_list" => $params["tb_grant_list"],
		            "tb_grant_detail" => $params["tb_grant_detail"],
		            "tb_grant_edit" => $params["tb_grant_edit"],
		            "reg_id" => getAdminInfo("admin_id"),
		        ];
		        
		        //저장
		        $result = $this->board_model->insertTable($insert);
		    }else if($params["proc_mode"]=="update"){
		        $update = [
		            "tb_name" => $params["tb_name"],
		            "tb_show_yn" => $params["tb_show_yn"],
		            "tb_location" => $params["tb_location"],
		            "tb_period_yn" => $params["tb_period_yn"],
		             //"tb_basic_yn" => $params["tb_basic_yn"],
		            "tb_img_yn" => $params["tb_img_yn"],
		            "tb_file_yn" => $params["tb_file_yn"],
		            "tb_page_cnt" => $params["tb_page_cnt"],
		            "tb_grant_add" => $params["tb_grant_add"],
		            "tb_grant_cmt" => $params["tb_grant_cmt"],
		            "tb_grant_list" => $params["tb_grant_list"],
		            "tb_grant_detail" => $params["tb_grant_detail"],
		            "tb_grant_edit" => $params["tb_grant_edit"],
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];
		        
		        //저장
		        $where = ["tb_idx"=>$params["tb_idx"]];
		        $result = $this->board_model->updateTable($update,$where);
		    }
		    
		    json_return($result["code"],$result["msg"],"");
		}
		
		//게시글 목록
		function board_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$use_yn = input_get("use_yn","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$tb_id = input_get("tb_id","notice");
			
			//데이터 설정
			{
				$params = [
			        "pagesize" => 20,
			        "page" => $page,
			        "search_type" => $search_type,
			        "search_value" => $search_value,
			        "tb_id" => $tb_id
			    ];
				
				$result = $this->board_model->getBoardList($params);
				
				//테이블 리스트
				$params = [
			        "pagesize" => 100,
			        "page" => 1,
			        "search_type" => "",
			        "search_value" => "",
			        "show_yn" => "Y"
			    ];
				$temp_table = $this->board_model->getTableList($params);
				$table_list = getKeyValue($temp_table["list"],"tb_id","tb_name");
				
				$setting = $this->board_model->getTable(0,$tb_id);
			}
			//리턴 설정
			$data = [
				"search" =>[
					"table_list" => $table_list,
					"search_type" => $search_type,
					"search_value" => $search_value,
					"tb_id" => $tb_id
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/board/board_list',$data);
		}
		
		//게시글 상세
		function board_edit(){
			//파라미터 설정
		    $idx = input_get("idx",0);
			$tb_id = input_get("tb_id","notice");
		    
		    //데이터 조회
		    $info = $this->board_model->getBoard($idx);
			$setting = $this->board_model->getTable(0,$tb_id);
		    
			//테이블 리스트
			$params = [
		        "pagesize" => 100,
		        "page" => 1,
		        "search_type" => "",
		        "search_value" => "",
		        "show_yn" => "Y"
		    ];
			$temp_table = $this->board_model->getTableList($params);
			$table_list = getKeyValue($temp_table["list"],"tb_id","tb_name");
			
			//서브 카테고리
			$sub_list = [];
			$tb_category = explode(";",$setting["tb_category"]);
			foreach($tb_category as $ct){
				$sub_list[$ct] = $ct;
			}
		    
		    //리턴 처리
		    $data = [
		    	"admin_id"=>getAdminInfo("admin_id"),
		    	"info"=>$info,
		    	"tb_id"=>$tb_id,
		    	"setting"=>$setting,
		    	"sub_list"=>$sub_list,
		    	"table_list"=>$table_list,
		    ];
			
		    $this->layout->admin('admin/board/board_edit',$data);
		}
		
		//게시글 등록/수정
		function board_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all(FALSE);
			
			$bd_file1 = $this->common->file_upload_url_name("board","bd_file1");
			$bd_file2 = $this->common->file_upload_url_name("board","bd_file2");
			$bd_file3 = $this->common->file_upload_url_name("board","bd_file3");
			$bd_list_img1 = $this->common->img_upload_url("board","bd_list_img1");
			$bd_list_img2 = $this->common->img_upload_url("board","bd_list_img2");
			$bd_img1 = $this->common->img_upload_url("board","bd_img1");
			$bd_img2 = $this->common->img_upload_url("board","bd_img2");
			
			if($bd_file1["error"]!=""){
				json_return("96",$bd_file1["error"],"");
				exit;
			}
			
			if($bd_file2["error"]!=""){
				json_return("96",$bd_file2["error"],"");
				exit;
			}
			
			if($bd_file3["error"]!=""){
				json_return("96",$bd_file3["error"],"");
				exit;
			}
			
		    //처리
		    $result = [];
		    if($params["proc_mode"]=="insert"){
		        $insert = [
		            "tb_id" => trim($params["tb_id"]),
		            "bd_subject" => $params["bd_subject"],
		            "bd_subtext" => $params["bd_subtext"]==null ? "":$params["bd_subtext"],
		            "bd_cate" => $params["bd_cate"],
		            "bd_show_type" => isset($params["bd_show_type"]) ? $params["bd_show_type"]:"",
		            "bd_notice_yn" => isset($params["bd_notice_yn"]) ? $params["bd_notice_yn"]:"",
		            "bd_sdate" => $params["bd_sdate"],
		            "bd_edate" => $params["bd_edate"],
		            "mm_id" => "admin",
		            "bd_content" => $params["bd_content"],
		            "bd_content_m" => isset($params["bd_content_m"]) ? $params["bd_content_m"]:"",
		            "bd_aws" => $params["bd_aws"],
		            "bd_sort" => $params["bd_sort"],
		            "bd_write_type" => "A",
		            "bd_file1" => $bd_file1["url"],
		            "bd_file2" => $bd_file2["url"],
		            "bd_file3" => $bd_file3["url"],
		            "bd_file1_nm" => $bd_file1["name"],
		            "bd_file2_nm" => $bd_file2["name"],
		            "bd_file3_nm" => $bd_file3["name"],
		            "bd_list_img1" => $bd_list_img1,
		            "bd_list_img1" => $bd_list_img2,
		            "bd_img1" => $bd_img1,
		            "bd_img2" => $bd_img2,
		            "reg_id" => getAdminInfo("admin_id"),
		        ];
		        
		        //저장
		        $result = $this->board_model->insertBoard($insert);
		    }else if($params["proc_mode"]=="update"){
		        $update = [
		            "tb_id" => trim($params["tb_id"]),
		            "bd_subject" => $params["bd_subject"],
		            "bd_subtext" => $params["bd_subtext"]==null ? "":$params["bd_subtext"],
		            "bd_cate" => $params["bd_cate"],
		            "bd_show_type" => isset($params["bd_show_type"]) ? $params["bd_show_type"]:"",
		            "bd_notice_yn" => isset($params["bd_notice_yn"]) ? $params["bd_notice_yn"]:"",
		            "bd_sdate" => $params["bd_sdate"],
		            "bd_edate" => $params["bd_edate"],
		            "bd_content" => $params["bd_content"],
		            "bd_content_m" => isset($params["bd_content_m"]) ? $params["bd_content_m"]:"",
		            "bd_aws" => $params["bd_aws"],
		            "bd_sort" => $params["bd_sort"],
		            "bd_write_type" => "A",
		            "bd_file1" => $bd_file1["url"],
		            "bd_file2" => $bd_file2["url"],
		            "bd_file3" => $bd_file3["url"],
		            "bd_file1_nm" => $bd_file1["name"],
		            "bd_file2_nm" => $bd_file2["name"],
		            "bd_file3_nm" => $bd_file3["name"],
		            "bd_list_img1" => $bd_list_img1,
		            "bd_list_img2" => $bd_list_img2,
		            "bd_img1" => $bd_img1,
		            "bd_img2" => $bd_img2,
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];
		        
		        //저장
		        $where = ["bd_idx"=>$params["bd_idx"]];
		        $result = $this->board_model->updateBoard($update,$where);

		    }
		    
		    json_return($result["code"],$result["msg"],"");
		}
		
		//테이블 등록,수정
		function board_add_proc(){
		    //파라미터 설정
		    $params = input_post_all();
			
		    //처리
		    $result = [];
		    if($params["proc_mode"]=="aws"){
		        $update = [
		            "bd_aws" => trim($params["bd_aws"]),
		            "bd_aws_name" => trim($params["bd_aws_name"]),
		            "bd_aws_yn" => "Y",
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		            "bd_aws_dt" => get_today(),
		        ];
		        
		        //저장
		        $where = ["bd_idx"=>$params["bd_idx"]];
		        $result = $this->board_model->updateBoard($update,$where);
				
				json_return($result["code"],"답변이 완료되었습니다.","");
		    }
		}
		
		
		//게시판 금지어 관리
		function keyword_setting(){
			//데이터 설정
			$data = [
				"info" => $this->setting_model->getSettingEtcInfo(),
			];
			$this->layout->admin('admin/board/keyword_setting',$data);
		}
		
		//회원 등급 정책 처리
		public function keyword_setting_proc(){
			//파라미터 설정
			$params = input_post_all();
			
			//처리
			$result = [];
			$update = $params;
			
			//저장
			$where = ["st_type"=>"shopping"];	//이부분세션으로 변경해야함
			$result = $this->setting_model->updateSettingEtc($update,$where);
			
			json_return($result["code"],$result["msg"],"");
		}
    }
?>