<?php
    class Basic extends MY_Controller{
		function __construct() {
	        parent::__construct();

			//모델로드
			$this->load->model('settingmodel','setting_model');
			$this->load->model('sitemodel','site_model');
	    }

		//쇼핑몰 기본정보
		function index(){
			//파라미터 설정
			$params = [];

			$chklist_bns = ["Y"=>"노출시 체크"];

			$data = [
				"info" => $this->setting_model->getSettingInfo(["st_group"=>"관리자"]),
				"chklist_bns"=> $chklist_bns
			];

			$this->layout->admin('admin/setting/basic/index',$data);
		}

		function setting_change_mode(){
			$st_admin_type = input_post("st_admin_type");
			$where = ["st_type"=>"shopping"];	//이부분세션으로 변경해야함
			$result = $this->setting_model->updateSettingBasic(["st_admin_type"=>$st_admin_type],$where);

			json_return($result["code"],$result["msg"],"");
		}

		//기본정보 수정
		public function index_proc(){
			//파라미터 설정
			$params = input_post_all();

			//처리
			$result = [];
			$update = [
				"st_admin_id"=>$params["st_admin_id"],
				"st_site_name"=>$params["st_site_name"],
				"st_domain"=>$params["st_domain"],
				"st_hp"=>$params["st_hp"],
				"st_hp2"=>$params["st_hp2"],
				"st_hp3"=>$params["st_hp3"],
				"st_comp_name"=>$params["st_comp_name"],
				"st_comp_owner_name"=>$params["st_comp_owner_name"],
				"st_bus_is_yn"=> (isset($params["st_bus_is_yn"]) ? $params["st_bus_is_yn"]:"N"),
				"st_bus_net_num"=>$params["st_bus_net_num"],
				"st_bus_type"=>$params["st_bus_type"],
				"st_bus_item"=>$params["st_bus_item"],
				"st_bus_address"=>$params["st_bus_address"],
				"st_stock_address"=>$params["st_stock_address"],
				"st_main_tel"=>$params["st_main_tel"],
				"st_main_fax"=>$params["st_main_fax"],
				"st_bus_suc_name"=>$params["st_bus_suc_name"],
				"st_bus_num"=>$params["st_bus_num"],
				"st_service_center_tel1"=>$params["st_service_center_tel1"],
				"st_service_center_email"=>$params["st_service_center_email"],
				"st_service_center_info"=>$params["st_service_center_info"],
				"st_copyright"=>$params["st_copyright"],
			];

			$update["st_stamp_img"] = $this->common->img_upload_url("setting","st_stamp_img");
			$update["st_logo_pc"] = $this->common->img_upload_url("setting","st_logo_pc");
			$update["st_logo_m"] = $this->common->img_upload_url("setting","st_logo_m");

			//비밀번호가 있으면 수정한다.
			if($params["st_admin_pwd"]!=""){
				$update["st_admin_pwd"] = $this->common->getSqlPass($params["st_admin_pwd"]);
			}

			//저장
			$where = ["st_type"=>"shopping"];	//이부분세션으로 변경해야함
			$result = $this->setting_model->updateSettingBasic($update,$where);

			json_return($result["code"],$result["msg"],"");
		}

		//싸이트 아이콘 설정
		function icon(){
			//파라미터 설정
			$data['info'] = $this->setting_model->getSettingInfo();
			$this->layout->admin('admin/setting/basic/icon',$data);
		}

		//싸이트 아이콘 설정 처리
		public function icon_proc(){
			//파라미터 설정
			$params = input_post_all();

			//처리
			$result = [];
			$update = [
				"st_meta_title"=>$params["st_meta_title"],
				"st_meta_desc"=>$params["st_meta_desc"],
				"st_meta_keywrod"=>$params["st_meta_keywrod"],
				"st_meta_tag"=>$params["st_meta_tag"],
			];

			$update["st_icon_share"] = $this->common->img_upload_url("setting","st_icon_share");
			$update["st_icon_pc"] = $this->common->img_upload_url("setting","st_icon_pc");
			$update["st_icon_m"] = $this->common->img_upload_url("setting","st_icon_m");

			//저장
			$where = ["st_type"=>"shopping"];	//이부분세션으로 변경해야함
			$result = $this->setting_model->updateSettingBasic($update,$where);

			json_return($result["code"],$result["msg"],"");
		}

		//약관/정책 설정
		function policy(){
			$info = $this->setting_model->getSettingInfo(["st_group"=>"이용약관"]);
			$data["info"] = $info;
			$this->layout->admin('admin/setting/basic/policy',$data);
		}

		//기본정보 수정
		public function policy_proc(){
			//파라미터 설정
			$params = input_post_all();

			//처리
			$result = [];
			$update = $params;

			$this->setting_model->updateSettingBasic(["st_value"=>$params["policy_pc"]],["st_group"=>"이용약관","st_key"=>"policy_pc"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["policy_m"]],["st_group"=>"이용약관","st_key"=>"policy_m"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["privacy_pc"]],["st_group"=>"이용약관","st_key"=>"privacy_pc"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["privacy_m"]],["st_group"=>"이용약관","st_key"=>"privacy_m"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["privacy3_pc"]],["st_group"=>"이용약관","st_key"=>"privacy3_pc"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["privacy3_m"]],["st_group"=>"이용약관","st_key"=>"privacy3_m"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["location_pc"]],["st_group"=>"이용약관","st_key"=>"location_pc"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["location_m"]],["st_group"=>"이용약관","st_key"=>"location_m"]);

			$this->setting_model->updateSettingBasic(["st_value"=>$params["policy2_pc"]],["st_group"=>"이용약관","st_key"=>"policy2_pc"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["policy2_m"]],["st_group"=>"이용약관","st_key"=>"policy2_m"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["trade_pc"]],["st_group"=>"이용약관","st_key"=>"trade_pc"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["trade_m"]],["st_group"=>"이용약관","st_key"=>"trade_m"]);

			$this->setting_model->updateSettingBasic(["st_value"=>$params["trade_goods_pc"]],["st_group"=>"이용약관","st_key"=>"trade_goods_pc"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["trade_goods_m"]],["st_group"=>"이용약관","st_key"=>"trade_goods_m"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["trade_term_pc"]],["st_group"=>"이용약관","st_key"=>"trade_term_pc"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["trade_term_m"]],["st_group"=>"이용약관","st_key"=>"trade_term_m"]);

			$this->setting_model->updateSettingBasic(["st_value"=>$params["adv_pc"]],["st_group"=>"이용약관","st_key"=>"adv_pc"]);
			$this->setting_model->updateSettingBasic(["st_value"=>$params["adv_m"]],["st_group"=>"이용약관","st_key"=>"adv_m"]);

			json_return("00","수정 완료 되었습니다.","");
		}

		//싸이트 아이콘 설정
		function info(){
			//파라미터 설정
			$data['info'] = [
				"st_info_use_pc"=>	$this->setting_model->getSettingText("st_info_use_pc"),
				"st_info_use_m"=>	$this->setting_model->getSettingText("st_info_use_m"),
				"st_info_fire_pc"=>	$this->setting_model->getSettingText("st_info_fire_pc"),
				"st_info_fire_m"=>	$this->setting_model->getSettingText("st_info_fire_m"),
			];
			$this->layout->admin('admin/setting/basic/info',$data);
		}

		//기본정보 수정
		public function info_proc(){
			//파라미터 설정
			$params = input_post_all();

			//처리
			$result = [];
			$update = $params;

			//저장
			$result = $this->setting_model->updateSettingText(["st_text"=>$params["st_info_use_pc"],"st_type"=>"st_info_use_pc"],["st_type"=>"st_info_use_pc"]);
			$result = $this->setting_model->updateSettingText(["st_text"=>$params["st_info_use_m"],"st_type"=>"st_info_use_m"],["st_type"=>"st_info_use_m"]);
			$result = $this->setting_model->updateSettingText(["st_text"=>$params["st_info_fire_pc"],"st_type"=>"st_info_fire_pc"],["st_type"=>"st_info_fire_pc"]);
			$result = $this->setting_model->updateSettingText(["st_text"=>$params["st_info_fire_m"],"st_type"=>"st_info_fire_m"],["st_type"=>"st_info_fire_m"]);

			json_return($result["code"],$result["msg"],"");
		}

		//싸이트 아이콘 설정
		function code_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$use_yn = input_get("use_yn","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_value" => $search_value,
				"use_yn" => $use_yn,
			];

			$result = $this->setting_model->getCodeList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/setting/basic/code_list',$data);
		}

		function config(){
			$data = [];
			$type = input_get("type","희망업무");

			$data["type"] = $type;
			$data["result_list"] = $this->setting_model->getCodeList(["cd_grp"=>$type]);
			$this->layout->admin('admin/setting/basic/config',$data);

		}

		//배송지 관리 상세
		function config_edit(){
			//파라미터 설정
		    $idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->setting_model->getCodeInfo(["cd_idx"=>$idx]);

		    //리턴 처리
		    $data["info"] = $info;
		    $this->layout->admin('admin/setting/basic/config_edit',$data);
		}

		function config_trade(){
			$data = [];
			$type = input_get("type","중고카테고리");

			$data["type"] = $type;
			$data["result_list"] = $this->setting_model->getCodeList(["cd_grp"=>$type]);
			$this->layout->admin('admin/setting/basic/config_trade',$data);

		}

		//배송지 관리 상세
		function config_trade_edit(){
			//파라미터 설정
		    $idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->setting_model->getCodeInfo(["cd_idx"=>$idx]);

		    //리턴 처리
		    $data["info"] = $info;
		    $this->layout->admin('admin/setting/basic/config_trade_edit',$data);
		}

		function config_point(){
			$data = [];
			$type = input_get("type","구매포인트");

			$data["type"] = $type;
			$data["result_list"] = $this->setting_model->getCodeList(["cd_grp"=>$type]);
			$this->layout->admin('admin/setting/basic/config_point',$data);
		}

		//배송지 관리 상세
		function config_point_edit(){
			//파라미터 설정
		    $idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->setting_model->getCodeInfo(["cd_idx"=>$idx]);

		    //리턴 처리
		    $data["info"] = $info;
		    $this->layout->admin('admin/setting/basic/config_point_edit',$data);
		}

		function config_usepoint(){
			$data = [];
			$type = input_get("type","사용포인트");

			$data["type"] = $type;
			$data["result_list"] = $this->setting_model->getCodePointList();
			$this->layout->admin('admin/setting/basic/config_usepoint',$data);
		}

		//배송지 관리 상세
		function config_usepoint_edit(){
			//파라미터 설정
		    $idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->setting_model->getCodeInfo(["cd_idx"=>$idx]);

		    //리턴 처리
		    $data["info"] = $info;
		    $this->layout->admin('admin/setting/basic/config_usepoint_edit',$data);
		}

		//테이블 등록,수정
		function config_point_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

		    //처리
		    $result = [];
		    if($params["proc_mode"]=="update"){
		        $update = [

			        "cd_val" => $params["cd_val"],
			        "cd_val2" => $params["cd_val2"],
		            "use_yn" => $params["use_yn"],
		            "reg_dt"=> get_today(),
		        ];

		        //수정
		        $where = ["cd_idx"=>$params["cd_idx"]];
		        $result = $this->setting_model->updateCodeInfo($update,$where);
		    }

		    json_return($result["code"],$result["msg"],"");
		}

		//테이블 등록,수정
		function config_usepoint_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

		    //처리
		    $result = [];
		    if($params["proc_mode"]=="update"){
		        $update = [
			        "cd_val3" => $params["cd_val3"],
		            "reg_dt"=> get_today(),
		        ];

		        //수정
		        $where = ["cd_idx"=>$params["cd_idx"]];
		        $result = $this->setting_model->updateCodeInfo($update,$where);
		    }

		    json_return($result["code"],$result["msg"],"");
		}

		//테이블 등록,수정
		function config_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

			//이미지 처리
			$cd_val2 = $this->common->img_upload_url("admin","cd_val2");
			$cd_val3 = $this->common->img_upload_url("admin","cd_val3");
			$cd_val4 = $this->common->img_upload_url("admin","cd_val4");
      $cd_val5 = $this->common->img_upload_url("admin","cd_val5");
      $cd_val6 = $this->common->img_upload_url("admin","cd_val6");
      $cd_val7 = $this->common->img_upload_url("admin","cd_val7");
      $cd_val8 = $this->common->img_upload_url("admin","cd_val8");
      $cd_val9 = $this->common->img_upload_url("admin","cd_val9");
      $cd_val10 = $this->common->img_upload_url("admin","cd_val10");
      $cd_val11 = $this->common->img_upload_url("admin","cd_val11");
      $cd_val12 = $this->common->img_upload_url("admin","cd_val12");

		    //처리
		    $result = [];
		    if($params["proc_mode"]=="insert"){
		        $insert = [
		       		"cd_grp" => "희망업무",
			        "cd_key" => $params["cd_key"],
			        "cd_val" => $params["cd_key"],
			        "cd_val2" => $cd_val2,
			        "cd_val3" => $cd_val3,
		            "cd_val4" => $cd_val4,
                "cd_val5" => $cd_val5,
                "cd_val6" => $cd_val6,
                "cd_val7" => $cd_val7,
                "cd_val8" => $cd_val8,
                "cd_val9" => $cd_val9,
                "cd_val10" => $cd_val10,
                "cd_val11" => $cd_val11,
                "cd_val12" => $cd_val12,
		            "cd_sort" => $params["cd_sort"],
		            "use_yn" => $params["use_yn"],
		            "reg_id" => getAdminInfo("admin_id"),
		        ];

		        //등록
		        $result = $this->setting_model->insertCodeInfo($insert);

		    }else if($params["proc_mode"]=="update"){
		        $update = [
			        "cd_key" => $params["cd_key"],
			        "cd_val" => $params["cd_key"],
			        "cd_val2" => $cd_val2,
			        "cd_val3" => $cd_val3,
		            "cd_val4" => $cd_val4,
                "cd_val5" => $cd_val5,
                "cd_val6" => $cd_val6,
                "cd_val7" => $cd_val7,
                "cd_val8" => $cd_val8,
                "cd_val9" => $cd_val9,
                "cd_val10" => $cd_val10,
                "cd_val11" => $cd_val11,
                "cd_val12" => $cd_val12,
		            "use_yn" => $params["use_yn"],
		            "cd_sort" => $params["cd_sort"],
		            "reg_dt"=> get_today(),
		        ];

		        //수정
		        $where = ["cd_idx"=>$params["cd_idx"]];
		        $result = $this->setting_model->updateCodeInfo($update,$where);
		    }

		    json_return($result["code"],$result["msg"],"");
		}
    }
?>
