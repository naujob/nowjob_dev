<?php
class orderModel extends MY_Model {
   	function __construct() {
    	parent::__construct();
	}

	# 주문 마스터
	function getOrderMaster($o_num){
		return $this->db_row("st_order",["o_num"=>$o_num]);
	}

	function getOrderInfo($o_idx){
		$sql = "
			select aa.*
				,cc.cd_val2 as order_point
				,cp.cp_name
			FROM st_order aa
			inner join st_member bb
				on aa.mm_idx = bb.mm_idx
			inner join st_code cc
				on aa.o_goods_code = cc.cd_key and cc.cd_grp = '구매포인트'
			left join st_coupon_list cl
				on aa.o_coupon_idx = cl.cl_idx
			left join st_coupon cp
				on cl.cp_idx = cp.cp_idx
			where aa.o_idx = '$o_idx'
		";

		$qry = $this->db->query($sql);
		return $qry->row_array();
	}

	# 주문 업데이트
	function updateOrder($o_num,$data){
		return $this->db_update("st_order",$data,["o_num"=>$o_num]);
	}

	# PG정보 조회
	function getPgInfo(){
		return $this->db_row("st_setting_pg",["sp_default_yn"=>"Y"]);
	}

	# PG정보 조회
	function getPgInfoName($pg_name){
		return $this->db_row("st_setting_pg",["sp_name"=>$pg_name]);
	}

	//쿠폰 정보 조회
	function checkCoupon($cl_idx){
		return $this->db_row("st_coupon_list",["cl_idx"=>$cl_idx]);
	}

	# 결제 리스트
	function getOrderList($params){
		//컬럼
		$cols = [
			"aa.*"
			,"bb.mm_id,bb.mm_type,bb.mm_comp_name,bb.mm_name"
			,"cc.cd_val2 as order_point"
		];

		//바디
		$body = "
			st_order aa
				inner join st_member bb
					on aa.mm_idx = bb.mm_idx
				inner join st_code cc
					on aa.o_goods_code = cc.cd_key and cc.cd_grp = '구매포인트'
		";

		//조건
		$where = [];
		$where[] = 	" and o_status != 'XXXX'  ";

		if(isset($params["mm_idx"]) && $params["mm_idx"] !=""){
			$where[] = 	" and (aa.mm_idx = '".$this->inject($params["mm_idx"])."')  ";
		}

		if(isset($params["search_type"]) && isset($params["search_value"])){
			if($params["search_value"]!=""){
				if($params["search_type"]==""){
					$where[] = 	" and (bb.mm_id like '%".$this->inject($params["search_value"])."%'
				 				  or bb.mm_name like '%".$this->inject($params["search_value"])."%'
				 				  or bb.mm_comp_name like '%".$this->inject($params["search_value"])."%'
				 				  or aa.o_num like '%".$this->inject($params["search_value"])."%'
	                              or aa.o_goods_name like '%".$this->inject($params["search_value"])."%') ";
				}else if($params["search_type"]=="mm_id"){
					$where[] = 	" and (bb.mm_id like '%".$this->inject($params["search_value"])."%')  ";
				}else if($params["search_type"]=="mm_name"){
					$where[] = 	" and (bb.mm_name like '%".$this->inject($params["search_value"])."%' or mm_comp_name like '%".$this->inject($params["search_value"])."%')  ";
				}else if($params["search_type"]=="o_num"){
					$where[] = 	" and (aa.o_num like '%".$this->inject($params["search_value"])."%')  ";
				}else if($params["search_type"]=="o_goods_name"){
					$where[] = 	" and (aa.o_goods_name like '%".$this->inject($params["search_value"])."%')  ";
				}
			}
		}


		if(isset($params["mm_type"]) && $params["mm_type"] !=""){
			$where[] = 	" and bb.mm_type = '".$this->inject($params["mm_type"])."' ";
		}


		if(isset($params["o_paymethod"]) && $params["o_paymethod"] !=""){
			$where[] = 	" and aa.o_paymethod = '".$this->inject($params["o_paymethod"])."' ";
		}


		if(isset($params["o_status"]) && $params["o_status"] !=""){
			$where[] = 	" and aa.o_status = '".$this->inject($params["o_status"])."' ";
		}

		//정렬
		$order_by = "aa.o_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
	}

	# 포인트 리스트
	function getPointList($params){
		//컬럼
		$cols = [
			"aa.*"
			,"mem.mm_id,mem.mm_type,mem.mm_comp_name,mem.mm_name",
			"
				(case when pt_type = 'ORDER' then '결제충전'
				when pt_type = 'PROMOTION' then '프로모션구매'
				when pt_type = 'EVENT' then '수동발행'
				else '' end) as pt_type_nm
			"
		];

		//바디
		$body = "
			st_point_list aa
				inner join st_member mem
					on aa.mm_idx = mem.mm_idx
				left join st_order bb
					on aa.o_num = bb.o_num
				left join st_promotion cc
					on aa.pm_idx = cc.pm_idx
		";

		//조건
		$where = [];

		if(isset($params["mm_idx"]) && $params["mm_idx"] !=""){
			$where[] = 	" and (aa.mm_idx = '".$this->inject($params["mm_idx"])."')  ";
		}

		if(isset($params["search_type"]) && isset($params["search_value"])){
			if($params["search_value"]!=""){
				if($params["search_type"]==""){
					$where[] = 	" and (mem.mm_id like '%".$this->inject($params["search_value"])."%'
				 				  or aa.pl_desc like '%".$this->inject($params["search_value"])."%'
				 				  or aa.o_num like '%".$this->inject($params["search_value"])."%'
	                              or mem.mm_name like '%".$this->inject($params["search_value"])."%') ";
				}else if($params["search_type"]=="mm_id"){
					$where[] = 	" and (mem.mm_id like '%".$this->inject($params["search_value"])."%')  ";
				}else if($params["search_type"]=="mm_name"){
					$where[] = 	" and (mem.mm_name like '%".$this->inject($params["search_value"])."%' or mm_comp_name like '%".$this->inject($params["search_value"])."%')  ";
				}else if($params["search_type"]=="o_num"){
					$where[] = 	" and (aa.o_num like '%".$this->inject($params["search_value"])."%')  ";
				}else if($params["search_type"]=="pl_desc"){
					$where[] = 	" and (aa.pl_desc like '%".$this->inject($params["search_value"])."%')  ";
				}
			}
		}


		if(isset($params["mm_type"]) && $params["mm_type"] !=""){
			$where[] = 	" and bb.mm_type = '".$this->inject($params["mm_type"])."' ";
		}


		if(isset($params["o_paymethod"]) && $params["o_paymethod"] !=""){
			$where[] = 	" and aa.o_paymethod = '".$this->inject($params["o_paymethod"])."' ";
		}


		if(isset($params["o_status"]) && $params["o_status"] !=""){
			$where[] = 	" and aa.o_status = '".$this->inject($params["o_status"])."' ";
		}


		//정렬
		$order_by = "aa.pl_idx desc";

   		return $this->db_paging_list($cols,$body,$where,$order_by,$params["page"],$params["pagesize"]);
	}

	# 주문 조회
	function getChargePointList($mm_idx){
		//카운터쿼리 생성
		$sql = "
			select *
				,aa.reg_dt as pl_reg_dt
			FROM st_point_list aa
				left join st_order bb
					on aa.o_num = bb.o_num
			where aa.mm_idx = '$mm_idx'
			and aa.pl_type = 'plus'
			order by aa.reg_dt desc
			limit 0,100
		";

		$qry = $this->db->query($sql);
		return $qry->result_array();
	}

	# 주문 조회
	function getUsePointList($mm_idx){
		//카운터쿼리 생성
		$sql = "
			select aa.*
				,bb.pm_addr
			FROM st_point_list aa
				left join st_promotion bb
					on aa.pm_idx = bb.pm_idx
			where aa.mm_idx = '$mm_idx'
			and aa.pl_type = 'minus'
			order by aa.reg_dt desc
			limit 0,100
		";

		$qry = $this->db->query($sql);
		return $qry->result_array();
	}

	# 가상계좌로그 등록
	function insertOrderVbankLog($o_num,$vl_msg){
		$order_log = array(
			"o_num"=>$o_num,
			"ol_type"=> "가상계좌입금",
			"ol_location" => "싸이트",
			"ol_msg"=>$this->data->getArrayString($vl_msg),
			"reg_dt"=>date("Y-m-d H:i:s")
		);

		$returl = $this->setPayedLog($order_log);
		return $result;
	}

	function deleteTempOrder($user_idx){
		$result = $this->db_delete("st_order",["mm_idx"=>$user_idx,"o_status"=>"XXXX"]);
		return $result;
	}

	//장바구니 등록
	function insertOrder($info){
		$error_msg= "";

		//트랜젝션 실행
		$this->db->trans_start();
		if(!$result = $this->db->insert('st_order', $info)){
			$error_msg = $error_msg . "st_order::" . $this->db->_error_message();
		}

		$CI =& get_instance();
		$CI->load->model('membermodel','member_model');
		$mem_info = $this->member_model->getMember(["mm_idx"=>$info["mm_idx"]]);
		//로그 남기기
		$log = [
			"o_num"=>$info["o_num"],
			"ol_type"=> "결제완료",
			"ol_location"=>"싸이트",
			"ol_msg"=>$mem_info["mm_name"] . "님이 결제(". $info["o_num"].")" . "를 진행하셨습니다." ,
			"reg_id"=>"system",
		];
		$this->setPayedLog($log);

		//트랜젝션 완료
		$this->db->trans_complete();

		if($error_msg!=""){
			return ["code"=>"99","msg"=>$error_msg,"data"=>""];
		}

		return ["code"=>"00","msg"=>"처리성공","data"=>["o_num" =>$info["o_num"]]];
	}


	//결제성공시 결제 완료 처리한다. type :noti(가상계좌,등 결제 정보 쏴줄때 처리) ,return : url로 리턴됬을경우 처리.
	function updateOrderResult($type,$info,$source){

		try{
			//설정정보 가져오기
			$CI =& get_instance();
	 		$CI->load->model('settingmodel','setting_model');
			$CI->load->model('sitemodel','site_model');
			$CI->load->model('membermodel','member_model');

			# 결제정보가지고온다.
			$order = $this->db_row("st_order",["o_num"=>$info["o_num"]]);

			# 포인트 정보
			$setting = $this->setting_model->getSettingInfo(["st_group"=>"결제"]);
			$timestamp = strtotime("+".$setting["refund_day"]." days");
			$refund_allow_dt = date("Y-m-d", $timestamp) . " 23:59:59";

			# 회원정보 가져온다.
			$mem_info = $this->member_model->getMember(["mm_idx"=>$order["mm_idx"]]);

			# 일반결제와 VBANK로 나누어 처리
			if($order["o_paymethod"]=="VBANK"){
				# 무통장정보 처리시 결제대기,XXXX인경우만 데이터 처리한다.이외에는 수정하지 않는다.
				if($order["o_status"]=="XXXX" || $order["o_status"]=="결제대기"){
					$upt_data = array(
					 	 "o_status" => "결제대기"
					);

					//가상계좌 입금통보시에만 처리
					if($type=="noti"){
						 $upt_data["o_pay_dt"] = date("Y-m-d H:i:s");
						 $upt_data["o_status"] = "결제완료";
						 $upt_data["o_pay_yn"] = "Y";
						 $upt_data["o_refund_allow_dt"] = $refund_allow_dt;
					}else{
						 $upt_data["o_tid"] = $info['o_tid'];
						 $upt_data["o_bank_num"] = $info['o_bank_num'];
						 $upt_data["o_bank_code"] = $info['o_bank_code'];
						 $upt_data["o_bank_name"] = $info['o_bank_name'];
						 $upt_data["o_bank_res_dt"] = $info['o_bank_res_dt'];
						 $upt_data["o_result_code"] = $info['o_result_code'];
						 $upt_data["o_result_msg"] = $info['o_result_msg'];
					}

					$this->db->where('o_num',$info["o_num"]);
			        $this->db->update('st_order',$upt_data);

					if($type!="noti"){
						//쿠폰사용처리 : 쿠폰을 사용한이력이 있으면 고객 쿠폰 사용처리한다.
						if($order["o_coupon_idx"]!=0){
							$upt_coupon["cl_use_yn"] = "Y";
							$this->db->where('cl_idx',$order["o_coupon_idx"]);
							$this->db->where('mm_idx',$order["mm_idx"]);
					        $this->db->update('st_coupon_list',$upt_coupon);
						}
					}else{
						//포인트 지급 처리
						$point_info = $this->setting_model->getCodeInfo(["cd_grp"=>"구매포인트","cd_key"=>$order["o_goods_code"]]);
						$last_point = $mem_info["mm_point"] + $point_info["cd_val2"];

						$insert = [
							"mm_idx" => $mem_info["mm_idx"],
							"o_num"=>$order["o_num"],
							"pt_type" => "ORDER",
							"pl_type" => "plus",
							"pl_point" => $point_info["cd_val2"],
							"pl_last_point" => $last_point,
							"pl_desc" => $order["o_num"] . " 결제 충전",
							"reg_id" => "system",
						];
						$result_point = $this->site_model->insertPointList($insert);

						//회원 정보 갱신한다.
						if($result_point["code"]=="00"){
							$this->member_model->updateMember(["mm_point"=>$last_point],["mm_idx"=>$order["mm_idx"]]);
						}
					}
				}

				//로그등록
				$order_log = array(
					"o_num"=>$info["o_num"],
					"ol_type"=>($type == "noti" ? "결제완료":"주문완료"),
					"ol_location" => "싸이트",
					"ol_msg"=>$this->data->getArrayString($source),
					"reg_dt"=>date("Y-m-d H:i:s")
				);
				$this->setPayedLog($order_log);

				return true;
			}else if($order["o_paymethod"]=="COUPON" || $order["o_paymethod"]=="IOS"){

				$upt_data = array(
				 	 "o_status" => ("결제완료")
				);

				if($order["o_paymethod"]=="COUPON" || $order["o_paymethod"]=="IOS"){
					$upt_data["o_pay_dt"] = date("Y-m-d H:i:s");
					$upt_data["o_pay_yn"] = "Y";
					$upt_data["o_refund_allow_dt"] = $refund_allow_dt;
				}

				$this->db->where('o_num',$info["o_num"]);
		        $this->db->update('st_order',$upt_data);

				//쿠폰사용처리 : 쿠폰을 사용한이력이 있으면 고객 쿠폰 사용처리한다.
				if($order["o_coupon_idx"]!=0){
					$upt_coupon["cl_use_yn"] = "Y";
					$this->db->where('cl_idx',$order["o_coupon_idx"]);
					$this->db->where('mm_idx',$order["mm_idx"]);
			        $this->db->update('st_coupon_list',$upt_coupon);
				}

				//포인트 지급 처리
				$point_info = $this->setting_model->getCodeInfo(["cd_grp"=>"구매포인트","cd_key"=>$order["o_goods_code"]]);
				$last_point = $mem_info["mm_point"] + $point_info["cd_val2"];

				$insert = [
					"mm_idx" => $mem_info["mm_idx"],
					"o_num"=>$order["o_num"],
					"pt_type" => "ORDER",
					"pl_type" => "plus",
					"pl_point" => $point_info["cd_val2"],
					"pl_last_point" => $last_point,
					"pl_desc" => $order["o_num"] . " 결제 충전",
					"reg_id" => "system",
				];
				$result_point = $this->site_model->insertPointList($insert);

				//회원 정보 갱신한다.
				if($result_point["code"]=="00"){
					$this->member_model->updateMember(["mm_point"=>$last_point],["mm_idx"=>$order["mm_idx"]]);
				}

				//로그등록
				$order_log = array(
					"o_num"=>$info["o_num"],
					"ol_type"=>("주문완료"),
					"ol_location" => "싸이트",
					"ol_msg"=> "" . $this->data->getArrayString($source),
					"reg_dt"=>date("Y-m-d H:i:s")
				);
				$this->setPayedLog($order_log);

				return true;
			}else{
				//해당주문이 미처리상태인경우만 데이터 업데이트 처리한다.
				if($order["o_status"]=="XXXX"){
					$upt_data = array(
					 	 "o_status" => "결제완료"
					 	 ,"o_pay_yn" => "Y"
						 ,"o_pay_dt" => date("Y-m-d H:i:s")
						 ,"o_result_code" => $info['o_result_code']
						 ,"o_result_msg" => $info['o_result_msg']
						 ,"o_tid" =>  $info['o_tid']
						 ,"o_refund_allow_dt" => $refund_allow_dt
					);

					if($order["o_paymethod"]=="CARD"){
						$upt_data["o_card_cd"] = $info["o_card_cd"];
						$upt_data["o_card_name"] = $info["o_card_name"];
						$upt_data["o_card_ins"] = $info["o_card_ins"];
						$upt_data["o_card_num"] = $info["o_card_num"];
					}else if($order["o_paymethod"]=="BANK"){
						$upt_data["o_bank_code"] = $info["o_bank_code"];
						//$upt_data["o_bank_acc"] = $info["o_bank_acc"];
					}

					$this->db->where('o_num',$info["o_num"]);
			        $this->db->update('st_order',$upt_data);

					//쿠폰사용처리 : 쿠폰을 사용한이력이 있으면 고객 쿠폰 사용처리한다.
					if($order["o_coupon_idx"]!=0){
						$upt_coupon["cl_use_yn"] = "Y";
						$this->db->where('cl_idx',$order["o_coupon_idx"]);
						$this->db->where('mm_idx',$order["mm_idx"]);
				        $this->db->update('st_coupon_list',$upt_coupon);
					}

					//포인트 지급 처리
					$point_info = $this->setting_model->getCodeInfo(["cd_grp"=>"구매포인트","cd_key"=>$order["o_goods_code"]]);
					$last_point = $mem_info["mm_point"] + $point_info["cd_val2"];

					$insert = [
						"mm_idx" => $mem_info["mm_idx"],
						"o_num"=>$order["o_num"],
						"pt_type" => "ORDER",
						"pl_type" => "plus",
						"pl_point" => $point_info["cd_val2"],
						"pl_last_point" => $last_point,
						"pl_desc" => $order["o_num"] . " 결제 충전",
						"reg_id" => "system",
					];
					$result_point = $this->site_model->insertPointList($insert);

					//회원 정보 갱신한다.
					if($result_point["code"]=="00"){
						$this->member_model->updateMember(["mm_point"=>$last_point],["mm_idx"=>$order["mm_idx"]]);
					}

					//로그등록
					$order_log = array(
						"o_num"=>$info["o_num"],
						"ol_type"=>"결제완료",
						"ol_location" => "싸이트",
						"ol_msg"=>$this->data->getArrayString($source),
						"reg_dt"=>date("Y-m-d H:i:s")
					);
					$this->setPayedLog($order_log);
				}else{
					//로그등록
					$order_log = array(
						"o_num"=>$info["o_num"],
						"ol_type"=>"결제완료",
						"ol_location" => "싸이트",
						"ol_msg"=>"상태정보 오류로 결제 완료 실패됨",
						"reg_dt"=>date("Y-m-d H:i:s")
					);
					$this->setPayedLog($order_log);
				}
				return true;
			}

		}catch(Exception $ex){
			return false;
		}
	}

	function setOrderLog($o_num,$type,$location,$msg){
		//로그등록
		$order_log = array(
			"o_num"=>$o_num,
			"ol_type"=>$type,
			"ol_location" => $location,
			"ol_msg"=>$msg,
			"reg_dt"=>date("Y-m-d H:i:s")
		);
		$this->setPayedLog($order_log);
	}

	//로그 등록
	function setPayedLog($info){
		$result = $this->db_insert('st_order_log', $info);
		return $result;
	}

	//수동 입금처리
	function setOrderManualPayed($o_num){
		$reg_id = $this->admin->getAdminId();
		$setting = $this->setting_model->getSettingInfo();

		$order = $this->getOrderAllInfo($o_num);

		//결제 정보 업데이트
		$update_data = [
			"o_pay_yn"=>"Y",
			"o_status"=>"결제완료",
			"o_pay_dt"=>get_today(),
			"o_result_code"=>"0000",
			"o_result_msg"=>"수동 주문완료 처리 되었습니다.",
		];

		$this->db_update("st_order",$update_data,["o_num"=>$o_num]);

		//상품데이터 업데이트
		$result = $this->db_update("st_order_goods",["op_status"=>"결제완료"],["o_num"=>$o_num]);

		//상점정보 조회
		$api_result = sys_api("get_store_info",[
		     "key"=>get_store_key($setting)
		    ,"type"=>"info"
		]);

		//상점정보 조회시에만 발송한다.
		if($api_result["code"]=="00"){
			$order_info = $order["order"];
			$rev_dt = substr($order_info["o_rev_dt"],0,10);
			$member_info = $this->order_model->db_row("st_member",["mm_idx"=>$order_info["mm_idx"]]);
		    $store_info = $api_result["data"];
		    //알림톡 발송 : 유저용
		    $sms["setting"]["code"] = "174";
		    $sms["setting"]["mobile"] = $order_info["o_ohp"];
		    $sms["data"]["비고1"] = $setting["st_comp_name"];
		    $sms["data"]["비고2"] = $member_info["add_sangho"];
		    $sms["data"]["비고3"] = substr($order_info["reg_dt"],0,10);
		    $sms["data"]["비고4"] = $order_info["o_num"];
			$sms["data"]["비고5"] = ($rev_dt == "0000-00-00" ? "미정": $rev_dt);

		    $api_result = sys_api("send_talk",[
		        "key"=>get_store_key($setting)
		        ,"data"=>json_encode($sms)
		    ]);
		}

		//SMS발송
		//$sms["setting"]["code"] = "SMS00002";
		//$sms["setting"]["mobile"] = $order["order"]["o_ohp"];
		//$sms["data"]["SITE_NAME"] =  $setting["st_comp_name"];
		//$sms["data"]["USER_NAME"] =  $order["order"]["o_oname"];
		//$sms["data"]["ORDER_NO"] = $order["order"]["o_num"];
		//$result = $this->common->sendSms($sms);

		//이메일 발송하기
		//$mail_body = $this->common->getMailBody("order",$order);
		//$this->common->mailer($setting["st_comp_name"],$setting["st_service_center_email"],$order["order"]["o_oemail"],$setting["st_comp_name"] . " 주문확인" ,$mail_body);

		//재고차감하기
		foreach($order["order"]["seller"] as $seller){
			foreach($seller["goods"] as $prd){
				if($prd["gd_stock_yn"]=="Y"){
					$stock["type"] = "주문완료";
					$stock["o_num"] = $order["order"]["o_num"];
					$stock["gd_stock"] = $prd["gd_stock"];
					$stock["gd_idx"] = $prd["gd_idx"];
					$stock["op_name1"] = $prd["op_name1"];
					$stock["op_name2"] = $prd["op_name2"];
					$stock["op_name3"] = $prd["op_name3"];
					$stock["op_cnt"] = $prd["op_cnt"];
					$this->updateOptionStock("minus",$stock);
				}
			}
		}

		//로그 남기기
		$log = [
			"o_num"=>$o_num,
			"ol_type"=>"수동입금처리",
			"ol_location"=>"관리자",
			"ol_msg"=>"관리자 수동 입금처리",
			"reg_id"=>$reg_id,
		];
		$this->setPayedLog($log);

		return $result;
	}

	//수동 입금처리
	function setOrderManualPayedCancel($o_num){
		$reg_id = $this->admin->getAdminId();
		$setting = $this->setting_model->getSettingInfo();

		$order = $this->getOrderAllInfo($o_num);

		//결제 정보 업데이트
		$update_data = [
			"o_pay_yn"=>"N",
			"o_status"=>"결제대기",
			"o_pay_dt"=>"0000-00-00 00:00:00",
			"o_result_code"=> null,
			"o_result_msg"=>null,
		];

		$this->db_update("st_order",$update_data,["o_num"=>$o_num]);

		//상품데이터 업데이트
		$result = $this->db_update("st_order_goods",["op_status"=>"결제대기"],["o_num"=>$o_num]);

		//재고차감하기
		foreach($order["order"]["seller"] as $seller){
			foreach($seller["goods"] as $prd){
				if($prd["gd_stock_yn"]=="Y"){
					$stock["type"] = "주문취소";
					$stock["o_num"] = $order["order"]["o_num"];
					$stock["gd_stock"] = $prd["gd_stock"];
					$stock["gd_idx"] = $prd["gd_idx"];
					$stock["op_name1"] = $prd["op_name1"];
					$stock["op_name2"] = $prd["op_name2"];
					$stock["op_name3"] = $prd["op_name3"];
					$stock["op_cnt"] = $prd["op_cnt"];
					$this->updateOptionStock("plus",$stock);
				}
			}
		}

		//로그 남기기
		$log = [
			"o_num"=>$o_num,
			"ol_type"=>"수동입금취소처리",
			"ol_location"=>"관리자",
			"ol_msg"=>"관리자 수동 입금취소처리",
			"reg_id"=>$reg_id,
		];
		$this->setPayedLog($log);

		return $result;
	}

}
