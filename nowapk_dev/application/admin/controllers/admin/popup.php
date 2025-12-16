<?php
    class Popup extends MY_Controller{
		function __construct() { 
	        parent::__construct();
			
			//모델로드
			$this->load->model('settingmodel','setting_model');
			$this->load->model('goodsmodel','goods_model');
			$this->load->model('membermodel','member_model');
			$this->load->model('ordermodel','order_model');
			$this->load->model('sitemodel','site_model');
	    } 
		
		//상품 목록
		function goods_select(){
			//파라미터 설정
			$page = input_get("page",1);
			$sel_type = input_get("sel_type","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			$bd_idx = input_get("bd_idx","");
			$gd_cate1 = input_get("gd_cate1","");
			$gd_cate2 = input_get("gd_cate2","");
			$gd_cate3 = input_get("gd_cate3","");
			$ignore_idx = input_get("ignore_idx","");
			$mm_idx = input_get("mm_idx","");
			
			//데이터 설정
			$params = [
		        "pagesize" => 20,
		        "page" => $page,
		        "search_type" => $search_type,
		        "search_value" => $search_value,
		        "gd_status" => "Y",
		        "bd_idx" => $bd_idx,
		        "gd_cate1" => $gd_cate1,
		        "gd_cate2" => $gd_cate2,
		        "gd_cate3" => $gd_cate3,
		        "ignore_idx" => $ignore_idx,
		        "mm_idx" => $mm_idx,
		    ];
			
			$result = $this->goods_model->getGoodsList($params);
			
			$mg_sale_per = 0;
			if($mm_idx!=""){
				$mem_info = $this->member_model->getMember($mm_idx);
				$mg_sale_per = $mem_info["mg_sale_per"];
			}
			
			//브랜드 리스트
			$brand_list = $this->goods_model->getBrandList($params,"aa.bd_name asc");
			$select_brand = getKeyValue($brand_list["list"],"bd_idx","bd_name","-- 브랜드 선택 --");
			
			//리턴 설정
			$data = [
				"search" =>[
					"search_type" => $search_type,
					"search_value" => $search_value,
			        "bd_idx" => $bd_idx,
			        "sel_type" => $sel_type,
			        "gd_cate1" => $gd_cate1,
			        "gd_cate2" => $gd_cate2,
			        "gd_cate3" => $gd_cate3,
			        "ignore_idx" => $ignore_idx,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
				"select_brand" => $select_brand,
				 "mg_sale_per" => $mg_sale_per,
			];

			$this->layout->admin_popup('admin/popup/goods_select',$data);
		}

		//회원관리
		function member_select(){
			//파라미터 설정
			$page = input_get("page",1);
			$sel_type = input_get("sel_type","");
			$mm_status = input_get("member_status","Y");
			$mm_grade = input_get("member_grade","");
			$search_type = input_get("search_type","");
			$search_value = input_get("search_value","");
			
			//데이터 설정
			{
				//회원 리스트
				$params = [
					"pagesize" => 20,
					"page" => $page,
					"search_type" => $search_type,
					"search_value" => $search_value,
					"member_grade" => $member_grade,
					"member_status" => $member_status,
				];
				$result = $this->member_model->getMemberList($params);
					
				//등급 셀렉트 박스
				$grade_params = [
					"pagesize" => 100,
					"page" => 1,
				];
	
			}
			
			//리턴 설정
			$data = [
				"search" =>[
					"member_status" => $member_status,
					"search_type" => $search_type,
					"search_value" => $search_value,
					"sel_type" => $sel_type,
				],
				"start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin_popup('admin/popup/member_select',$data);
		}

		//회원관리
		function sales_coupon(){
			//파라미터 설정
			$mm_idx = input_get("mm_idx",0);
			$sdate = input_get("sdate",date("Y-m-01"));
			$edate = input_get("edate",date("Y-m-t"));
			$per = input_get("per",0);
			
			//데이터 설정
			{
				//회원 리스트
				$params = [
					"mm_idx" => $mm_idx,
					"sdate" => $sdate,
					"edate" => $edate,
				];
				
				$total_price = $this->site_model->getMemberOrderInfo($params);
				$order_list = $this->site_model->getMemberOrderList($params);
			}
			
			//리턴 설정
			$data = [
				"mm_idx" => $mm_idx,
				"sdate" => $sdate,
				"edate" => $edate,
				"per" => $per,
				"total_price" => $total_price,
				"order_list" => $order_list,
			];
			
			$this->layout->admin_popup('admin/popup/sales_coupon',$data);
		}


		//취소,교환,반품 처리
		function order_req_claim(){
			//파라미터 설정
			$o_num = input_get("o_num","");
			$claim_type = input_get("claim_type","");
			$op_idxs = input_get("op_idxs","");
			
			//타입명
			
			//데이터 가져오기
			$order = $this->order_model->getClaimData($claim_type,$o_num,$op_idxs);
			
			//데이터 설정
			if($claim_type=="return"){
				$data["reason_list"] = $this->setting_model->getCodeList(["cd_grp"=>"return_reason"]);	
			}else if($claim_type=="change"){
				$data["reason_list"] = $this->setting_model->getCodeList(["cd_grp"=>"change_reason"]);
			}else{
				$data["reason_list"] = $this->setting_model->getCodeList(["cd_grp"=>"cancel_reason"]);
			}
			
			//데이터 설정
			$data["o_num"] = $o_num;	
			$data["claim_type"] = $claim_type;	
			$data["claim_nm"] = $order["claim"]["type_nm"];	
			$data["order"] = $order;
			
			$data["op_idxs"] = $op_idxs;	
			$this->layout->admin_popup('admin/popup/order_req_claim',$data);
		}
		
		//취소,교환,반품 처리
		function order_req_proc(){
			//파라미터 설정
			$o_num = input_post("o_num","");
			$claim_type = input_post("claim_type","");
			$op_idxs = input_post("op_idxs","");
			$reason = input_post("reason","");
			$reason_etc = input_post("reason_etc","");
			
			$oc_direct_yn = input_post("oc_direct_yn","");
			$oc_acc_name = input_post("oc_acc_name","");
			$oc_acc_num = input_post("oc_acc_num","");
			$oc_acc_bank = input_post("oc_acc_bank","");
			$oc_acc_payed = input_post("oc_acc_payed","");
			$oc_addr_post = input_post("oc_addr_post","");
			$oc_addr_doro = input_post("oc_addr_doro","");
			$oc_addr_jibun = input_post("oc_addr_jibun","");
			$oc_addr_dtl = input_post("oc_addr_dtl","");
			
			//데이터 가져오기
			$order = $this->order_model->getClaimData($claim_type,$o_num,$op_idxs);
			
			$order["claim"]["user_type"] = "A";
			$order["claim"]["user_id"] = $this->admin->getAdminId();
			$order["claim"]["reason"] = $reason;
			$order["claim"]["reason_etc"] = $reason_etc;
			$order["claim"]["oc_direct_yn"] = $oc_direct_yn;
			$order["claim"]["oc_acc_name"] = $oc_acc_name;
			$order["claim"]["oc_acc_num"] = $oc_acc_num;
			$order["claim"]["oc_acc_bank"] = $oc_acc_bank;
			$order["claim"]["oc_acc_payed"] = $oc_acc_payed;
			$order["claim"]["oc_addr_post"] = $oc_addr_post;
			$order["claim"]["oc_addr_doro"] = $oc_addr_doro;
			$order["claim"]["oc_addr_jibun"] = $oc_addr_doro;
			$order["claim"]["oc_addr_dtl"] = $oc_addr_dtl;
			
			$log_info = [
				"reg_id"=>$this->admin->getAdminId(),
				"location"=>"관리자",
			];
			
			$result = $this->order_model->setClaimData($claim_type,$order,$log_info);
			
			json_return($result["code"],$result["msg"]);
		}
		
		//취소,교환,반품 처리
		function order_proc_claim(){
			//파라미터 설정
			$oc_idx = input_get("oc_idx","");
		
			//데이터 가져오기
			$order = $this->order_model->getOrderClaimInfo($oc_idx);
			
			//데이터 설정
			$data["oc_idx"] = $oc_idx;
			$data["order"] = $order;
			
			$this->layout->admin_popup('admin/popup/order_proc_claim',$data);
		}
		
		//취소,교환,반품 처리
		function order_proc_proc(){
			//파라미터 설정
			$oc_idx = input_post("oc_idx","");
			$oc_status = input_post("oc_status","");
			$oc_reject_content = input_post("oc_reject_content","");
			$result = $this->order_model->setClaimRequestData($oc_idx,$oc_status,$oc_reject_content);
			
			json_return($result["code"],$result["msg"]);
		}
		
		//송장번호 조회
		function delivery_tracking(){
			//파라미터 설정
			$invocie_no = input_get("invoice_no","");
			
			$tracking = $this->order_model->getDeliveryTracking($invocie_no);
			$tracking_list = $this->order_model->getDeliveryTrackingListList($invocie_no);
			
			$data["tracking"] = $tracking;
			$data["tracking_list"] = $tracking_list;
			$this->layout->admin_popup('admin/popup/delivery_tracking',$data);
		}
		
		//주문서 출력
		function order_print(){
			//파라미터 설정
			$data = [];
			
			$page = input_get("page",1);
			$chk_list = input_get("chk_list","");
			$print_type = input_get("print_type","");
		
			//주문리스트
			$params = [
		        "pagesize" => 500,
		        "page" => 1,
		        "sdate"=>"",
				"edate"=>"",
				"search_type"=>"",
				"search_value"=>"",
				"member_depth"=>"",
				"o_pay_yn"=>"",
				"o_paymethod"=>"",
				"op_status"=>"",
				"order_type" => "",
				"chk_list" => $chk_list,
		    ];
			$result = $this->order_model->getOrderAllList($params);
			
			$data["order_list"] = $result;
			$data["print_type"] = $print_type;
			$data["add_print_num"] = $print_type=="list" ? 0:0;
			$data["first_goods_cnt"] = 6 + $data["add_print_num"];
			$data["second_goods_cnt1"] = $data["first_goods_cnt"] + 15;
			$data["second_goods_cnt2"] = $data["second_goods_cnt1"] + 15;
			$data["second_goods_cnt3"] = $data["second_goods_cnt2"] + 15;
			$data["second_goods_cnt4"] = $data["second_goods_cnt3"] + 15;
			$data["second_goods_cnt5"] = $data["second_goods_cnt4"] + 15;
			$this->layout->admin_popup('admin/popup/order_print',$data);
		}
		
		//출고지시 처리
		function order_print_proc(){
			$proc_mode = input_post("proc_mode","");
			$o_nums = input_post("o_nums","");
			
			//배열처리
			$arr_onum = explode("^",$o_nums);
			foreach($arr_onum as $key=> $o_num){
				$order_info = $this->order_model->getOrderMaster($o_num);
				$this->order_model->updateOrder($o_num,[$proc_mode=>$order_info[$proc_mode] + 1]);
			}
			json_return("00","처리완료.");
		}
    }
?>
