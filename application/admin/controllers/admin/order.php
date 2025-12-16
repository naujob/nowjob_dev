<?php
    class Order extends MY_Controller{
		function __construct() {
	        parent::__construct();

			//모델로드
			$this->load->model('settingmodel','setting_model');
			$this->load->model('ordermodel','order_model');
			$this->load->model('membermodel','member_model');
	    }

		//주문전체 목록
		function order_list(){
			$data = [];

			//주문조회
			$page = input_get("page",1);
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$o_paymethod = input_get("o_paymethod","");
			$mm_type = input_get("mm_type","");
			$o_status = input_get("o_status","");

			//주문리스트
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
				"search_type"=>$search_type,
				"search_value"=>$search_value,
				"mm_type"=>$mm_type,
				"o_paymethod"=>$o_paymethod,
				"o_status"=>$o_status,
		    ];
			$result = $this->order_model->getOrderList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"pagesize" => $page_size,
			        "page" => 1,
					"search_type"=>$search_type,
					"search_value"=>$search_value,
					"mm_type"=>$mm_type,
					"o_paymethod"=>$o_paymethod,
					"o_status"=>$o_status,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/order/order_list',$data);
		}

		//주문 상세 정보
		function order_edit(){
			$o_idx = input_get("o_idx","");

			if($o_idx==""){
				$this->common->goBack("잘못된 접근입니다.");
			}
			
			$info = $this->order_model->getOrderInfo($o_idx);
			
			$mem = $this->member_model->getMember(["mm_idx"=>$info["mm_idx"]]);
			
			$data["info"] = $info;
			$data["mem"] = $mem;

			$this->layout->admin('admin/order/order_edit',$data);
		}
		
		//주문전체 목록
		function point_list(){
			$data = [];

			//주문조회
			$page = input_get("page",1);
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$o_paymethod = input_get("o_paymethod","");
			$mm_type = input_get("mm_type","");
			$o_status = input_get("o_status","");

			//주문리스트
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
				"search_type"=>$search_type,
				"search_value"=>$search_value,
				"mm_type"=>$mm_type,
				"o_paymethod"=>$o_paymethod,
				"o_status"=>$o_status,
		    ];
			
			$result = $this->order_model->getPointList($params);

			//리턴 설정
			$data = [
				"search" =>[
					"pagesize" => $page_size,
			        "page" => 1,
					"search_type"=>$search_type,
					"search_value"=>$search_value,
					"mm_type"=>$mm_type,
					"o_paymethod"=>$o_paymethod,
					"o_status"=>$o_status,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];

			$this->layout->admin('admin/order/point_list',$data);
		}
    }
?>
