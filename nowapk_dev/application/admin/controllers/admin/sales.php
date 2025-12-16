<?php
    class Sales extends MY_Controller{
		function __construct() { 
	        parent::__construct();
			
			//모델로드
			$this->load->model('settingmodel','setting_model');
			$this->load->model('salesmodel','sales_model');
			$this->load->model('ordermodel','order_model');
			$this->load->model('goodsmodel','goods_model');
			$this->load->model('membermodel','member_model');
	    } 
		
		//일일 매출 및 발송 현황
		function sales_list(){
			$data = [];
			
			//주문조회
			$page = input_get("page",1);
			$edate = input_get("edate",date("Y-m-d"));
			$sdate = input_get("sdate",date("Y-m-d", strtotime("-1 months")));
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$member_depth = input_get("member_depth","");  
			$o_num = input_get("o_num","");
			$o_pay_yn = input_get("o_pay_yn","");
			$o_paymethod = input_get("o_paymethod","");
			$op_status = input_get("op_status","");
		
			//주문리스트
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "sdate"=>$sdate,
				"edate"=>$edate,
				"search_type"=>$search_type,
				"search_value"=>$search_value,
				"member_depth"=>$member_depth,
				"o_pay_yn"=>$o_pay_yn,
				"o_paymethod"=>$o_paymethod,
				"op_status"=>$op_status,
				
		    ];
			$result = $this->sales_model->getSalesList($params);
			
			$params["cd_grp"] = "sales_depth";
			$sales_depth_list = $this->setting_model->getCodeList($params);
			$select_depth = getKeyValue($sales_depth_list,"cd_val","cd_val","-- 전체검색 --");
			
			$params["cd_grp"] = "paymethod";
			$paymethod_list = $this->setting_model->getCodeList($params);
			$select_paymethod = getKeyValue($paymethod_list,"cd_key","cd_val","-- 전체검색 --");
			
			//리턴 설정
			$data = [
				"search" =>[
					"pagesize" => 20,
			        "page" => 1,
			        "sdate"=>$sdate,
					"edate"=>$edate,
					"search_type"=>$search_type,
					"search_value"=>$search_value,
					"member_depth" => $member_depth,
					"select_depth" => $select_depth,
					"select_paymethod" => $select_paymethod,
					"o_pay_yn"=>$o_pay_yn,
					"o_paymethod"=>$o_paymethod,
					"op_status"=>$op_status,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/sales/sales_list',$data);
		}

		
		
		//일일 매출 및 발송 현황
		function daily_list(){
			$data = [];
			
			//주문조회
			$page = input_get("page",1);
			$edate = input_get("edate",date("Y-m-d"));
			$sdate = input_get("sdate",date("Y-m-d", strtotime("-1 months")));
			$sangho_nm = input_get("sangho_nm","");
			
			//주문리스트
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "sdate"=>$sdate,
				"edate"=>$edate,
				"sangho_nm"=>$sangho_nm,
				
		    ];
			$result = $this->sales_model->getDailyList($params);
			
			//리턴 설정
			$data = [
				"search" =>[
					"pagesize" => 20,
			        "page" => 1,
			        "sdate"=>$sdate,
					"edate"=>$edate,
					"sangho_nm"=>$sangho_nm,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/sales/daily_list',$data);
		}

		
		//일일 매출 및 발송 현황
		function seller_list(){
			$data = [];
			
			//주문조회
			$page = input_get("page",1);
			$edate = input_get("edate",date("Y-m-d"));
			$sdate = input_get("sdate",date("Y-m-d", strtotime("-1 months")));
			$sangho_nm = input_get("sangho_nm","");
			
			//주문리스트
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "sdate"=>$sdate,
				"edate"=>$edate,
				"sangho_nm"=>$sangho_nm,
				
		    ];
			$result = $this->sales_model->getSellerList($params);
			
			//리턴 설정
			$data = [
				"search" =>[
					"pagesize" => 20,
			        "page" => 1,
			        "sdate"=>$sdate,
					"edate"=>$edate,
					"sangho_nm"=>$sangho_nm,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/sales/seller_list',$data);
		}

		//일일 상세 리스트
		function seller_detail_list(){
			$data = [];
			
			//주문조회
			$mm_idx = input_get("mm_idx",1);
			$page = input_get("page",1);
			$edate = input_get("edate",date("Y-m-d"));
			$sdate = input_get("sdate",date("Y-m-d", strtotime("-1 months")));
			$mem_info = $this->member_model->getMember($mm_idx);
			
			//주문리스트
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "sdate"=>$sdate,
				"edate"=>$edate,
				"mm_idx"=>$mm_idx,
		    ];
			$result = $this->sales_model->getSellerDetailList($params);
			
			
			$params2 = [
		        "pagesize" => 10000,
		        "page" => 1,
		        "sdate"=>$sdate,
				"edate"=>$edate,
				"mm_idx"=>$mm_idx,
		    ];
			$result_sum_list = $this->sales_model->getSellerDetailList($params2);
			
			$total_supply = 0;
			$total_tax = 0;
			foreach($result_sum_list["list"] as $row){
				$total_supply = $total_supply + floor($row["op_price"]/1.1);
				$total_tax = $total_tax + ($row["op_price"]-(floor($row["op_price"]/1.1)));
			}
			
			//리턴 설정
			$data = [
				"search" =>[
					"pagesize" => 20,
			        "page" => 1,
			        "sdate"=>$sdate,
					"edate"=>$edate,
				],
				"mem_info"=>$mem_info,
				"total_supply"=>$total_supply,
				"total_tax"=>$total_tax,
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/sales/seller_detail_list',$data);
		}
    }
?>