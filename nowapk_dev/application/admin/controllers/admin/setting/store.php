<?php
    class Store extends MY_Controller{
		function __construct() {
	        parent::__construct();

			//모델로드
			$this->load->model('storemodel','store_model');
			$this->load->model('settingmodel','setting_model');
			$this->load->model('sitemodel','site_model');
			$this->load->model('goodsmodel','goods_model');
			$this->load->model('boardmodel','board_model');
	    }

		//관리자 상점관리
		public function admin_store_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$st_type = input_get("st_type","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
				"st_type" => $st_type,
			];

			//데이터 설정
			$result = $this->store_model->getStoreList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"st_type" => $st_type,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/setting/store/admin_store_list',$data);
		}

		//관리자 상점관리 상세
		function admin_store_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);

			//데이터 설정
			$info = $this->store_model->getStoreInfo(["st_idx"=>$idx]);
			$order_list = $this->store_model->getStoreOrderListSimple($idx);
			$point_list = $this->store_model->getStorePointListSimple($idx);
			$grant_list = $this->store_model->getStoreGrantSimpleList(["sg_type"=>"ORDER"]);
			$select_grant = getKeyValue($grant_list,"sg_code","sg_name","");

			//리턴 설정
			$data = [
				"info" => $info,
				"order_list" => $order_list,
				"point_list" => $point_list,
				"select_grant" => $select_grant,
			];

			$this->layout->admin('admin/setting/store/admin_store_edit',$data);
		}


		//관리자 상점관리 상세
		function admin_store_regist(){
			$grant_list = $this->store_model->getStoreGrantSimpleList(["sg_type"=>"ORDER"]);
			$select_grant = getKeyValue($grant_list,"sg_code","sg_name","");

			$data = [
				"select_grant" => $select_grant,
			];

			$this->layout->admin('admin/setting/store/admin_store_regist',$data);
		}

		//관리자 상점관리 상세 저장
		function admin_store_edit_proc(){
			$params = input_post_all();
			if($params["proc_mode"]=="update"){
				$update = [
		        	"st_name" => $params["st_name"],
		        	"st_type" => $params["st_type"],
		        	"st_bus_name" => $params["st_bus_name"],
                    "st_bus_num" => $params["st_bus_num"],
			        "st_hp" => $params["st_hp"],
			        "st_email" => $params["st_email"],
			        "st_ip" => $params["st_ip"],
			        "st_status" => $params["st_status"],
			        "st_domain" => $params["st_domain"],
			        "st_addr_post" => $params["st_addr_post"],
			        "st_addr_detail" => $params["st_addr_detail"],
			        "st_addr_detail2" => $params["st_addr_detail2"],
			        "st_pg_yn" => $params["st_pg_yn"],
			        "st_use_sms" => $params["st_use_sms"],
			        "st_use_talk" => $params["st_use_talk"],
			        "st_use_tracking" => $params["st_use_tracking"],
			        "st_use_bizring" => $params["st_use_bizring"],
			        "st_use_talk_recv" => ($params["st_use_talk"]=="Y" ? $params["st_use_bizring"] : "N"),
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

				if($params["st_pwd"]!=""){
					$update["st_pwd"] = $this->common->getSqlPass($params["st_pwd"]);
				}

		        //수정
		        $where = ["st_idx"=>$params["st_idx"]];
		        $result = $this->store_model->updateStore($update,$where);
			}else{

				$insert = [
					"st_id" => $params["st_id"],
		        	"st_name" => $params["st_name"],
		        	"st_type" => $params["st_type"],
		        	"st_design" => "basic",
		        	"st_code"=>make_unique("STORE"),
		        	"st_pwd" => $this->common->getSqlPass($params["st_pwd"]),
		        	"st_bus_name" => $params["st_bus_name"],
		        	"st_bus_num" => $params["st_bus_num"],
			        "st_hp" => $params["st_hp"],
			        "st_email" => $params["st_email"],
			        "st_ip" => $params["st_ip"],
			        "st_status" => $params["st_status"],
			        "st_domain" => $params["st_domain"],
			        "st_addr_post" => $params["st_addr_post"],
			        "st_addr_detail" => $params["st_addr_detail"],
			        "st_addr_detail2" => $params["st_addr_detail2"],
			        "st_point" => $params["st_point"],
			        "st_pg_yn" => $params["st_pg_yn"],
			        "st_use_sms" => $params["st_use_sms"],
			        "st_use_talk" => $params["st_use_talk"],
			        "st_sdate" => $params["st_sdate"] . " 00:00:00",
			        "st_edate" => $params["st_edate"] . " 23:59:59",
			        "st_use_tracking" => $params["st_use_tracking"],
			        "st_use_bizring" => $params["st_use_bizring"],
			        "st_use_talk_recv" => ($params["st_use_talk"]=="Y" ? $params["st_use_bizring"] : "N"),
		            "reg_id" => getAdminInfo("admin_id"),
		            "reg_dt" => get_today(),
		        ];

		        $result = $this->store_model->insertStore($insert);
			}

			json_return($result["code"],$result["msg"]);
		}


		//관리자 상점관리
		public function admin_store_setting_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$sg_type = input_get("sg_type","ORDER");

			//데이터 설정
			$params = [
				"pagesize" => 100,
				"page" => $page,
				"sg_type" => $sg_type,
			];

			//데이터 설정
			$result = $this->store_model->getStoreGrantList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"sg_type" => $sg_type,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/setting/store/admin_store_setting_list',$data);
		}



		//관리자 상점관리 상세
		function admin_store_setting_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);
			$sg_type = input_get("sg_type","ORDER");

			//데이터 설정
			$info = $this->store_model->getStoreGrant(["sg_idx"=>$idx]);

			//리턴 설정
			$data = [
				"info" => $info,
				"sg_type" => $sg_type,
			];

			$this->layout->admin('admin/setting/store/admin_store_setting_edit',$data);
		}

		//관리자 상점관리 상세 저장
		function admin_store_setting_edit_proc(){
			$params = input_post_all();

			//처리
		    $result = [];
		    $primary_key = 0;
		    if($params["proc_mode"]=="insert"){
		        $insert = [
		        	"sg_type" => $params["sg_type"],
		        	"sg_code" => $this->common->getTblIdx("st_store_grant"),
			        "sg_name" => $params["sg_name"],
			        "sg_price" => $params["sg_price"],
			        "sg_point" => $params["sg_point"],
			        "sg_sort" => $params["sg_sort"],
			        "reg_id" => getAdminInfo("admin_id"),
		        ];

		        //등록
		        $result = $this->store_model->insertStoreGrant($insert);
				$primary_key = $result["key"];

		    }else if($params["proc_mode"]=="update"){
		        $update = [
		        	"sg_type" => $params["sg_type"],
		        	"sg_code" => $params["sg_code"],
			        "sg_name" => $params["sg_name"],
			        "sg_price" => $params["sg_price"],
			        "sg_point" => $params["sg_point"],
			        "sg_sort" => $params["sg_sort"],
			        "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //수정
		        $where = ["sg_idx"=>$params["sg_idx"]];
		        $result = $this->store_model->updateStoreGrant($update,$where);
				$primary_key = $params["sg_idx"];
		    }else if($params["proc_mode"]=="delete"){
		        //삭제
		        $where = ["sg_idx"=>$params["idx"]];
		        $result = $this->store_model->deleteStoreGrant($where);
		    }

			json_return($result["code"],$result["msg"]);
		}

		//관리자 결제관리 리스트
		public function admin_store_pay_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$so_type = input_get("so_type","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
				"so_type" => $so_type,
			];

			//데이터 설정
			$result = $this->store_model->getStoreOrderList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"so_type" => $so_type,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/setting/store/admin_store_pay_list',$data);
		}

		//입금완료처리
		public function admin_store_pay_list_proc(){
			$so_idx = input_post("idx",0);
			$setting = $this->common->getSetting();

			if($setting["st_admin_type"]!="admin"){
				json_return("99","잘못된 접근입니다.");
				exit;
			}

			//입금완료 처리
			$order_info = $this->store_model->getStoreOrder(["so_idx"=>$so_idx]);

			if($order_info==null){
				json_return("99","잘못된 접근입니다.");
				exit;
			}

			$update_info = [];
			$update_info["so_status"] = "결제완료";

			if($order_info["so_type"]=="주문"){
				$update_info["so_sdate"] = date("Y-m-d H:i:s");
    			$update_info["so_edate"] = date("Y-m-d" . " 23:59:59", strtotime(date("Y-m-d H:i:s") .'+ 1 year'));
			}else if($order_info["so_type"]=="포인트"){
				//고객정보 가져오기
			    $member_info = $this->store_model->getStoreInfo(["st_idx"=>$order_info["st_idx"]]);

			    //포인트 충전정보 등록
			    $insert = [
			        "st_idx"=>$order_info["st_idx"],
			        "sp_type"=>"충전",
			        "sp_name"=>$order_info["so_name"],
			        "sp_updown"=>"ADD",
			        "sp_point"=>$order_info["so_point"],
			        "sp_pre_point"=>$member_info["st_point"],
			        "sp_next_point"=>$member_info["st_point"] + $order_info["so_point"],
			        "reg_id"=>$member_info["st_id"],
			    ];

			    $this->store_model->insertStorePoint($insert);
			    $this->store_model->updateStore(["st_point"=>$member_info["st_point"] + $order_info["so_point"]],["st_idx"=>$member_info["st_idx"]]);
			}

			$result = $this->store_model->updateStoreOrder($update_info,["so_onum"=>$order_info["so_onum"]]);

			json_return($result["code"],"입금 처리가 완료 되었습니다.");
		}

		//관리자 결제관리 상세
		function admin_store_pay_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);

			//데이터 설정
			$info = $this->store_model->getStoreOrderInfo($idx);

			//리턴 설정
			$data = [
				"info" => $info,
			];

			$this->layout->admin('admin/setting/store/admin_store_pay_edit',$data);
		}


		//관리자 결제관리 리스트
		public function admin_store_charge_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$sp_type = input_get("sp_type","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$edate = input_get("edate",date("Y-m-d"));
			$sdate = input_get("sdate",date("Y-m-d", strtotime("-3 months")));

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
				"sdate" =>$sdate,
				"edate" =>$edate,
				"sp_type" => "충전",
			];

			//데이터 설정
			$result = $this->store_model->getStorePointList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"sp_type" => $sp_type,
					"search_type" => $search_type,
					"search_value" => $search_value,
					"sdate" =>$sdate,
					"edate" =>$edate,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/setting/store/admin_store_charge_list',$data);
		}

		//관리자 결제관리 상세
		function admin_store_charge_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);

			//데이터 설정
			$info = $this->store_model->getStorePointInfo($idx);

			//리턴 설정
			$data = [
				"info" => $info,
			];

			$this->layout->admin('admin/setting/store/admin_store_charge_edit',$data);
		}

		//관리자 결제관리 리스트
		public function admin_store_charge_history(){
			//파라미터 설정
			$page = input_get("page",1);
			$sp_type = input_get("sp_type","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$edate = input_get("edate",date("Y-m-d"));
			$sdate = input_get("sdate",date("Y-m-d", strtotime("-3 months")));

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
				"sdate" =>$sdate,
				"edate" =>$edate,
				"sp_type" =>$sp_type,
			];

			//데이터 설정
			$result = $this->store_model->getStorePointList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"sp_type" => $sp_type,
					"search_type" => $search_type,
					"search_value" => $search_value,
					"sdate" => $sdate,
					"edate" => $edate,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/setting/store/admin_store_charge_history',$data);
		}


		//상점 관리자 기본정보
		public function index(){
			$setting = $this->common->getSetting();
			$api_result = sys_api("get_store_info",[
				 "key"=>get_store_key($setting)
				,"type"=>"basic"
			]);

			if($api_result["code"]!="00"){
				go_location("/admin","상점 데이터 조회시 오류가 발생하였습니다. 관리자에게 문의부탁드립니다.");
				exit;
			}

			//리턴 설정
			$data = [
				"info" => $api_result["data"]["info"],
				"order_list" => $api_result["data"]["order_list"],
				"point_list" => $api_result["data"]["point_list"],
			];


			$this->layout->admin('admin/setting/store/index',$data);
		}

		//저장 처리
		function index_proc(){
			$params = input_post_all();

			$update = [
				"st_pg_yn" => $params["st_pg_yn"],
		        "st_use_sms" => $params["st_use_sms"],
		        "st_use_talk" => $params["st_use_talk"],
		        "st_use_tracking" => $params["st_use_tracking"],
		        "st_use_bizring" => $params["st_use_bizring"],
		        "st_use_talk_recv" => ($params["st_use_talk"]=="Y" ? $params["st_use_talk_recv"] : "N"),
	            "up_id" => getAdminInfo("admin_id"),
	            "up_dt" => get_today(),
	        ];

			$setting = $this->common->getSetting();
			$api_result = sys_api("update_store_info",[
				 "key"=>get_store_key($setting)
				 ,"type"=>"info"
				 ,"data"=>json_encode($update)
			]);

			if($api_result["code"]!="00"){
				json_return($api_result["code"],$api_result["msg"]);
				exit;
			}

			json_return($api_result["code"],$api_result["msg"]);
		}

		//상점 관리자 결제관리 리스트
		public function store_pay_list(){
			//파라미터 설정
			$st_idx = 0;
			$page = input_get("page",1);
			$so_type = input_get("so_type","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			$setting = $this->common->getSetting();
			$api_result = sys_api("get_store_info",[
				 "key"=>get_store_key($setting)
				,"type"=>"info"
			]);

			if($api_result["code"]!="00"){
				go_location("/admin","상점 데이터 조회시 오류가 발생하였습니다. 관리자에게 문의부탁드립니다.");
				exit;
			}
			$st_idx = $api_result["data"]["st_idx"];

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
				"so_type" => $so_type,
			];

			$setting = $this->common->getSetting();
			$api_result = sys_api("get_store_info",[
				 "key"=>get_store_key($setting)
				,"type"=>"order_list"
				,"pagesize"=>20
				,"page"=>$page
				,"search_type"=>$search_type
				,"search_value"=>$search_value
				,"so_type"=>$so_type
			]);

			if($api_result["code"]!="00"){
				go_location("/admin","상점 데이터 조회시 오류가 발생하였습니다. 관리자에게 문의부탁드립니다.");
				exit;
			}

			//데이터 설정
			$result = $api_result["data"];

			//리턴 설정
			$data = [
				"search" =>[
					"so_type" => $so_type,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
				"st_idx" => $st_idx,
			];

			$this->layout->admin('admin/setting/store/store_pay_list',$data);
		}

		//상점 관리자 결제관리 상세
		function store_pay_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);

			$setting = $this->common->getSetting();
			$api_result = sys_api("get_store_info",[
				 "key"=>get_store_key($setting)
				,"type"=>"order_detail"
				,"idx"=>$idx
			]);

			if($api_result["code"]!="00"){
				go_location("/admin","상점 데이터 조회시 오류가 발생하였습니다. 관리자에게 문의부탁드립니다.");
				exit;
			}

			//리턴 설정
			$data = [
				"info" => $api_result["data"],
			];

			$this->layout->admin('admin/setting/store/admin_store_pay_edit',$data);
		}

		//상점 관리자 충전금 리스트
		public function store_charge_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$st_idx = 1;

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
				"sp_type" => "충전",
			];

			$setting = $this->common->getSetting();
			$api_result = sys_api("get_store_info",[
				 "key"=>get_store_key($setting)
				,"type"=>"charge_list"
				,"pagesize"=>20
				,"page"=>$page
				,"search_type"=>$search_type
				,"search_value"=>$search_value
				,"sp_type"=>"충전"
			]);

			if($api_result["code"]!="00"){
				go_location("/admin","상점 데이터 조회시 오류가 발생하였습니다. 관리자에게 문의부탁드립니다.");
				exit;
			}

			//데이터 설정
			$result = $api_result["data"];
			$pagenation = get_page_info($page,$result["count"],20);

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $pagenation,
			];

			$this->layout->admin('admin/setting/store/store_charge_list',$data);
		}

		//상점 관리자 충전금 리스트
		public function store_charge_history(){
			//파라미터 설정
			$page = input_get("page",1);
			$sp_type = input_get("sp_type","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$st_idx = 1;

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
				"sp_type" => $sp_type,
			];

			$setting = $this->common->getSetting();
			$api_result = sys_api("get_store_info",[
				 "key"=>get_store_key($setting)
				,"type"=>"charge_list"
				,"pagesize"=>20
				,"page"=>$page
				,"search_type"=>$search_type
				,"search_value"=>$search_value
				,"sp_type"=>$sp_type
			]);

			if($api_result["code"]!="00"){
				go_location("/admin","상점 데이터 조회시 오류가 발생하였습니다. 관리자에게 문의부탁드립니다.");
				exit;
			}

			//데이터 설정
			$result = $api_result["data"];

			$pagenation = get_page_info($page,$result["count"],20);

			//리턴 설정
			$data = [
				"search" =>[
					"sp_type" => $sp_type,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $pagenation,
			];

			$this->layout->admin('admin/setting/store/store_charge_history',$data);
		}

		//배너 관리
		function banner_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$bg_idx = 1;
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");

			//데이터 조회
		    $info = $this->site_model->getBannerGroup(["bg_idx"=>$bg_idx]);

			$result = $this->site_model->getBannerList(["bg_idx"=>$bg_idx]);

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"info" => $info,
				"result_list" => $result,
			];

			$this->layout->admin('admin/setting/store/banner_list',$data);
		}

		//배너 관리
		function banner_edit(){
			//파라미터 설정
			$bg_idx = input_get("bg_idx","");
			$idx = input_get("idx",0);

		    //데이터 조회
		    $info = $this->site_model->getBanner(["bn_idx"=>$idx]);

		    $group_info = $this->site_model->getBannerGroup(["bg_idx"=>$bg_idx]);

		    //리턴 처리
		    $data["bg_idx"] = $bg_idx;
		    $data["info"] = $info;
			$data["group_info"] = $group_info;

			$prd_coupoon_list = [];
			$prd_coupoon_list = $this->goods_model->getRegistRelGoodsList($idx,"banner");

			$data["select_goods_list"] = $prd_coupoon_list;
			$data["select_max_length"] = 10000;

		    $this->layout->admin('admin/setting/store/banner_edit',$data);
		}

		//배너관리  등록,수정
		function banner_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

			$bn_pc_img = $this->common->img_upload_url("site","bn_pc_img");
			$bn_m_img = $this->common->img_upload_url("site","bn_m_img");

		    //처리
		    $result = [];
		    $primary_key = 0;
		    if($params["proc_mode"]=="insert"){
		        $insert = [
			        "bg_idx" => $params["bg_idx"],
		        	"bn_name" => $params["bn_name"],
			        "bn_status" => $params["bn_status"],
			        "bn_store_yn" => "Y",
			        "bn_period_type" => $params["bn_period_type"],
			        "bn_sdate" => $params["bn_sdate"],
			        "bn_edate" => $params["bn_edate"],
			        "bn_link_type" => $params["bn_link_type"],
			        "bn_link" => $params["bn_link"],
			        "bn_bg_color" => $params["bn_bg_color"],
			        "bn_pc_img" => $bn_pc_img,
			        "bn_m_img" => $bn_m_img,
			        "bn_sort" => $params["bn_sort"],
		            "reg_id" => getAdminInfo("admin_id"),
		        ];

		        //등록
		        $result = $this->site_model->insertBanner($insert);
				$primary_key = $result["key"];

		    }else if($params["proc_mode"]=="update"){
		        $update = [
		        	"bg_idx" => $params["bg_idx"],
		        	"bn_name" => $params["bn_name"],
			        "bn_status" => $params["bn_status"],
			        "bn_store_yn" => "Y",
			        "bn_period_type" => $params["bn_period_type"],
			        "bn_sdate" => $params["bn_sdate"],
			        "bn_edate" => $params["bn_edate"],
			        "bn_link_type" => $params["bn_link_type"],
			        "bn_link" => $params["bn_link"],
			        "bn_bg_color" => $params["bn_bg_color"],
			        "bn_pc_img" => $bn_pc_img,
			        "bn_m_img" => $bn_m_img,
			        "bn_sort" => $params["bn_sort"],
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //수정
		        $where = ["bn_idx"=>$params["bn_idx"]];
		        $result = $this->site_model->updateBanner($update,$where);
				$primary_key = $params["bn_idx"];
		    }else if($params["proc_mode"]=="delete"){
		        //삭제
		        $where = ["bn_idx"=>$params["idx"]];
		        $result = $this->site_model->deleteBanner($where);
		    }

			//추가 정보 처리
			if($params["proc_mode"]=="insert" || $params["proc_mode"]=="update"){
				//전체 삭제
				$this->site_model->deleteBannerGoods(["bn_idx"=>$primary_key]);

				//관련상품등록 rel_goods_gd_idx
				if(isset($params["rel_goods_gd_idx"])){
					foreach($params["rel_goods_gd_idx"] as $key => $row){
						$info = [
							"bn_idx"=>$primary_key,
							"gd_idx"=>$row,
							"reg_id" => getAdminInfo("admin_id"),
						];
						$this->site_model->insertBannerGoods($info);
					}
				}
			}


		    json_return($result["code"],$result["msg"],"");
		}


		//인증서 다운로드
		function certi_list(){
			$this->board_list("certi","/admin/setting/store/certi_list","/admin/setting/store/certi_edit");
		}

		//인증서 다운로드 수정
		function certi_edit(){
			$this->board_edit("certi","/admin/setting/store/certi_list","/admin/setting/store/certi_edit");
		}

		//공지사항
		function notice_list(){
			$this->board_list("store_notice","/admin/setting/store/notice_list","/admin/setting/store/notice_edit");
		}

		//공지사항 등록/수정 관리
		function notice_edit(){
			$this->board_edit("store_notice","/admin/setting/store/notice_list","/admin/setting/store/notice_edit");
		}

		//공지사항
		function store_notice_list(){
			//파라미터 설정
			$page = input_get("page",1);
			$sp_type = input_get("sp_type","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$st_idx = 1;

			//데이터 설정
			$params = [
				"pagesize" => 20,
				"page" => $page,
				"search_type" => $search_type,
				"search_value" => $search_value,
			];

			$setting = $this->common->getSetting();
			$api_result = sys_api("get_notice_list",[
				 "key"=>get_store_key($setting)
				,"pagesize"=>20
				,"page"=>$page
				,"search_type"=>$search_type
				,"search_value"=>$search_value
			]);

			if($api_result["code"]!="00"){
				go_location("/admin","상점 데이터 조회시 오류가 발생하였습니다. 관리자에게 문의부탁드립니다.");
				exit;
			}

			//데이터 설정
			$result = $api_result["data"];

			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/setting/store/store_notice_list',$data);
		}

		//공지사항 등록/수정 관리
		function store_notice_view(){
			$idx = input_get("idx",0);

			$setting = $this->common->getSetting();
			$api_result = sys_api("get_notice_view",[
				 "key"=>get_store_key($setting)
				,"idx"=>$idx
			]);

			if($api_result["code"]!="00"){
				go_location("/admin","상점 데이터 조회시 오류가 발생하였습니다. 관리자에게 문의부탁드립니다.");
				exit;
			}

			//데이터 설정
			$data["info"] = $api_result["data"];
			$this->layout->admin('admin/setting/store/store_notice_view',$data);
		}

		//게시글 상세
		function board_edit($tb_id,$list_page,$edit_page){
			//파라미터 설정
		    $idx = input_get("idx",0);
			$tb_id = input_get("tb_id",$tb_id);

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

			if($tb_id=="store_notice"){
				$update = [
		            "bd_hit" => $info["bd_hit"] + 1,
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //저장
		        $where = ["bd_idx"=>$idx];
		        $result = $this->board_model->updateBoard($update,$where);
			}

		    //리턴 처리
		    $data = [
		    	"admin_id"=>getAdminInfo("admin_id"),
		    	"info"=>$info,
		    	"tb_id"=>$tb_id,
		    	"setting"=>$setting,
		    	"sub_list"=>$sub_list,
		    	"table_list"=>$table_list,
		    	"list_page" => $list_page,
				"edit_page" => $edit_page,
		    ];

		    $this->layout->admin($edit_page,$data);
		}

		//테이블 등록,수정
		function board_edit_proc(){
		    //파라미터 설정
		    $params = input_post_all();

			$bd_file1 = "";
			$bd_file2 = "";
			$bd_file3 = "";
			$bd_img1 = "";

			if($params["proc_mode"]=="insert"){
				$bd_file1 = $this->common->file_upload_url_name("board","bd_file1");
				$bd_file2 = $this->common->file_upload_url_name("board","bd_file2");
				$bd_file3 = $this->common->file_upload_url_name("board","bd_file3");
				$bd_img1 = $this->common->img_upload_url("board","bd_img1");
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
			}

		    //처리
		    $result = [];
		    if($params["proc_mode"]=="insert"){
		        $insert = [
		            "tb_id" => trim($params["tb_id"]),
		            "bd_subject" => $params["bd_subject"],
		            "bd_cate" => $params["bd_cate"],
		            "bd_sdate" => $params["bd_sdate"],
		            "bd_edate" => $params["bd_edate"],
		            "mm_id" => $params["mm_id"],
		            "bd_content" => $params["bd_content"],
		            "bd_aws" => $params["bd_aws"],
		            "bd_sort" => $params["bd_sort"],
		            "bd_write_type" => "A",
		            "bd_file1" => $bd_file1["url"],
		            "bd_file2" => $bd_file2["url"],
		            "bd_file3" => $bd_file3["url"],
		            "bd_file1_nm" => $bd_file1["name"],
		            "bd_file2_nm" => $bd_file2["name"],
		            "bd_file3_nm" => $bd_file3["name"],
		            "bd_img1" => $bd_img1,
		            "reg_id" => getAdminInfo("admin_id"),
		        ];

		        //저장
		        $result = $this->board_model->insertBoard($insert);
		    }else if($params["proc_mode"]=="update"){
		        $update = [
		            "tb_id" => trim($params["tb_id"]),
		            "bd_subject" => $params["bd_subject"],
		            "bd_cate" => $params["bd_cate"],
		            "bd_sdate" => $params["bd_sdate"],
		            "bd_edate" => $params["bd_edate"],
		            "mm_id" => $params["mm_id"],
		            "bd_content" => $params["bd_content"],
		            "bd_aws" => $params["bd_aws"],
		            "bd_sort" => $params["bd_sort"],
		            "bd_write_type" => "A",
		            "bd_file1" => $bd_file1["url"],
		            "bd_file2" => $bd_file2["url"],
		            "bd_file3" => $bd_file3["url"],
		            "bd_file1_nm" => $bd_file1["name"],
		            "bd_file2_nm" => $bd_file2["name"],
		            "bd_file3_nm" => $bd_file3["name"],
		            "bd_img1" => $bd_img1,
		            "up_id" => getAdminInfo("admin_id"),
		            "up_dt" => get_today(),
		        ];

		        //저장
		        $where = ["bd_idx"=>$params["bd_idx"]];
		        $result = $this->board_model->updateBoard($update,$where);
		    }else if($params["proc_mode"]=="delete"){
		        //삭제
		        $where = ["bd_idx"=>$params["idx"]];
		        $result = $this->board_model->deleteBoard($where);
		    }


		    json_return($result["code"],$result["msg"],"");
		}

		//이벤트,공지사항,QNA,1:1문의,제휴문의 처리
		function board_list($tb_id,$list_page,$edit_page){
			//파라미터 설정
			$page = input_get("page",1);
			$use_yn = input_get("use_yn","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$bd_aws_yn = input_get("bd_aws_yn","");
			$tb_id = $tb_id;

			//데이터 설정
			{
				$params = [
			        "pagesize" => 20,
			        "page" => $page,
			        "search_type" => $search_type,
			        "search_value" => $search_value,
			        "bd_aws_yn" => $bd_aws_yn,
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
					"bd_aws_yn" => $bd_aws_yn,
					"tb_id" => $tb_id
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"setting" => $setting,
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
				"list_page" => $list_page,
				"edit_page" => $edit_page,
			];

			$this->layout->admin($list_page,$data);
		}

    }
?>
