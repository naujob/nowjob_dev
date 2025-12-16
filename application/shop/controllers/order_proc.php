<?php
    class Order_proc extends CI_Controller{
    	# 전역변수 선언
    	public $mm_idx;
		public $mm_id;
		public $mm_type;
		public $fail_url;
		public $finish_url;

		function __construct() {
	        parent::__construct();

			# 모델 로드
			$this->load->model('sitemodel','site_model');
            $this->load->model('settingmodel','setting_model');
            $this->load->model('membermodel','member_model');
			$this->load->model('ordermodel','order_model');

			# 로그인확인후 이동처리
			if($this->user->isLogin()==false){
				json_return("99","로그인후 이용가능한 기능입니다.");
			}

			$this->mm_id = $this->user->getUserId();
			$this->mm_idx = $this->user->getUserIdx();
			$this->mm_type = $this->user->getUserType();

			if($this->mm_type=="COMP"){
				$this->fail_url = "/compage/point_charge_step2";
				$this->finish_url = "/compage/point_charge_step3";
			}else{
				$this->fail_url = "/mypage/point_charge_step2";
				$this->finish_url = "/mypage/point_charge_step3";
			}


	    }

		# 주문생성
		function order_insert_proc(){
			$post = input_post_all();

			# 임시주문데이터 삭제
			$this->order_model->deleteTempOrder($this->mm_idx);

			# 데이터 정리
			$point_info = $this->setting_model->getCodeInfo(["cd_grp"=>"구매포인트","cd_key"=>$post["point_key"]]);
			
			
			#가입 당일 구입시 30% 할인추가.
			$mem_info = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);
			$reg_dt = substr($mem_info["reg_dt"],0,10);
			
			if($reg_dt==Date('Y-m-d')){
				$point_info["cd_val"] = $point_info["cd_val"] - round($point_info["cd_val"]*0.3);
			}
			
			$coupon_info = $this->order_model->checkCoupon($post["coupon_idx"]);
			$mem_info = $this->member_model->getMember(["mm_idx"=>$this->mm_idx]);

			$copuon_idx = $coupon_info==null ? 0:$coupon_info["cl_idx"];
			$coupon_price = 0;
			$o_con_type = $this->common->getLocation();
			$o_con_ip = $this->common->getIpAddr();
			$reg_date = date("Y-m-d H:i:s");
			$timestamp = strtotime("+2 days");
			$online_res_dt = date("Y-m-d", $timestamp) . " 23:59:59";

			# 결제 정보 검증
			if($point_info==null){
				json_return("91","결제 정보가 정상적이지 않습니다.결제를 다시 진행해주세요.","");
			}

			# 쿠폰검증
			if(isset($post["coupon_idx"]) && $post["coupon_idx"]!=""){
				if($post["coupon_price"]!=$coupon_info["coupon_price"]){
					json_return("91","쿠폰금액이 정상적이지 않습니다. 다시 주문을 진행해주세요.","");
				}
			}

			if($point_info["cd_val"] <= 0){
					json_return("91","결제 금액은 0원 이상이어야 합니다. 다시 주문을 진행해주세요.","");
				}

			#결제 금액 검증
			if($post["pay_method"]=="COPUPON"){
				if($point_info["cd_val"] > $post["coupon_price"]){
					json_return("91","쿠폰 금액이 부족하여 쿠폰결제 진행이 불가능합니다. 다시 주문을 진행해주세요.","");
				}
			}else{
				if($point_info["cd_val"] - $post["coupon_price"] <=0){
					json_return("91","결제 금액은 0원 이상이어야 합니다. 다시 주문을 진행해주세요.","");
				}
			}

			if($coupon_info!=null){
				if($coupon_info["cl_dan"]=="won"){
					$coupon_price = $coupon_info["cl_price"];
				}else{
					$coupon_price = round($point_info["cd_val"] * ($coupon_info["cl_price"]/100));
				}
			}

			$total_price = $point_info["cd_val"] - $coupon_price;

			if($total_price < 0){
				$total_price = 0;
			}


			# 주문마스터 정보
			$array = [
				"o_num" => $this->common->getOrderKey(),
				"mm_idx" => $this->mm_idx,
				"o_status" => "XXXX",
				"o_oname" => $mem_info["mm_name"],
				"o_ophone" => $mem_info["mm_phone"],
				"o_oemail" => $mem_info["mm_id"],
				"o_goods_name" => $point_info["cd_grp"] . "(" . $point_info["cd_key"] . ")" . number_format($point_info["cd_val"]) . "원",
				"o_goods_code" => $point_info["cd_key"],
				"o_goods_price" => $point_info["cd_val"],
				"o_paymethod" => $post["pay_method"],
				"o_pay_yn" => "N",
				"o_pg_name" => $post["o_pg_name"],
				"o_con_type" => $o_con_type,
				"o_con_ip" => $o_con_ip,
				"o_coupon_idx" => $copuon_idx,
				"o_coupon_price" => $coupon_price,
				"o_total_price" => $total_price,
                "o_escrow_yn" => "N",
				"o_receipt_yn" => "N",
				"o_receipt_type" => "",
				"o_receipt_auth_type" => "",
				"o_receipt_num" => "",
			];

			$result = $this->order_model->insertOrder($array);

			json_return($result["code"],$result["msg"],$result["data"]);
		}

		//PG전송 데이터 만들기
		function order_pg_proc(){
			$http = "https://";

			//https 적용
			if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
			    $http = "http://";
			}

			$post = input_post_all();
			$location = $this->common->getLocation();
			$order = $this->order_model->getOrderMaster($post["o_num"]);
			$order_goods_name = $order["o_goods_name"];

			//PG초기화
			$data["pg_info"] = $this->order_model->getPgInfo();
			if($data["pg_info"]["sp_name"]=="이니시스"){
				$inicis_info = $this->common->getPgInfo($data["pg_info"]["sp_name"],$data["pg_info"],$order["o_num"],$order["o_total_price"]);
				$inicis_info["buyername"] = $order["o_oname"];
				$inicis_info["buyertel"] = $order["o_ophone"];
				$inicis_info["buyeremail"] =$order["o_oemail"];
				$inicis_info["goodsname"] = $order_goods_name;

				if($location=="W"){
					$inicis_info["returnUrl"] = $http . $_SERVER['HTTP_HOST'] . "/order_proc/inicis_order_return";
					$inicis_info["closeUrl"] = $http . $_SERVER['HTTP_HOST'] . "/order_proc/inicis_order_cancel";

					//결제수단.
					if($order["o_paymethod"]=="CARD"){
						$inicis_info["paytype"] = "Card";
					}else if($order["o_paymethod"]=="BANK"){
						$inicis_info["paytype"] = "DirectBank";
					}else if($order["o_paymethod"]=="VBANK"){
						$inicis_info["paytype"] = "VBank";
					}else if($order["o_paymethod"]=="COUPON"){
						$inicis_info["paytype"] = "Coupon";
					}
				}else{
					$inicis_info["notiUrl"] = $http . $_SERVER['HTTP_HOST'] . "/order_proc/inicis_order_virtual";
					$inicis_info["nextUrl"] = $http . $_SERVER['HTTP_HOST'] . "/order_proc/inicis_order_next";

					//결제수단.
					if($order["o_paymethod"]=="CARD"){
						$inicis_info["paytype"] = "CARD";
					}else if($order["o_paymethod"]=="BANK"){
						$inicis_info["paytype"] = "BANK";
					}else if($order["o_paymethod"]=="VBANK"){
						$inicis_info["paytype"] = "VBANK";
					}else if($order["o_paymethod"]=="COUPON"){
						$inicis_info["paytype"] = "Coupon";
					}
				}

				json_return("00","조회성공",$inicis_info);
			}
		}

		//웹용처리
		function inicis_order_cancel()
        {
        	echo "<script>alert('결제가 취소 되었습니다.')</script>";
			echo "<script>parent.INIStdPay.viewOff();</script>";
		}

		//데이터 처리 : 주문처리
        function inicis_order_return()
        {
			require_once(getcwd() . '/application/common/pg/inicis/libs/INIStdPayUtil.php');
			require_once(getcwd() . '/application/common/pg/inicis/libs/HttpClient.php');

	        $util = new INIStdPayUtil();

			$result = [
				"code"=>"",
				"msg"=>"",
				"data"=>"",
			];

	        try {

	            //인증이 성공일 경우만
	            if (strcmp("0000", $_REQUEST["resultCode"]) == 0) {
	                //1.전문 필드 값 설정(***가맹점 개발수정***)
	                $mid 			= $_REQUEST["mid"];     					// 가맹점 ID 수신 받은 데이터로 설정
	                $signKey 		= "SU5JTElURV9UUklQTEVERVNfS0VZU1RS"; 		// 가맹점에 제공된 키(이니라이트키) (가맹점 수정후 고정) !!!절대!! 전문 데이터로 설정금지
	                $timestamp 		= $util->getTimestamp();   					// util에 의해서 자동생성
	                $charset 		= "UTF-8";        							// 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)
	                $format 		= "JSON";        							// 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)
	                $authToken 		= $_REQUEST["authToken"];   				// 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)
	                $authUrl 		= $_REQUEST["authUrl"];    					// 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)
	                $netCancel 		= $_REQUEST["netCancelUrl"];   				// 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)
	                $mKey 			= hash("sha256", $signKey);					// 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
	                $merchantData 	= $_REQUEST["merchantData"];     			// 가맹점 관리데이터 수신

	                //2.signature 생성
	                $signParam["authToken"] 	= $authToken;  	// 필수
	                $signParam["timestamp"] 	= $timestamp;  	// 필수
	                // signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
	                $signature = $util->makeSignature($signParam);

	                //3.API 요청 전문 생성
	                $authMap["mid"] 			= $mid;   		// 필수
	                $authMap["authToken"] 		= $authToken; 	// 필수
	                $authMap["signature"] 		= $signature; 	// 필수
	                $authMap["timestamp"] 		= $timestamp; 	// 필수
	                $authMap["charset"] 		= $charset;  	// default=UTF-8
	                $authMap["format"] 			= $format;  	// default=XML

	                try {

	                    $httpUtil = new HttpClient();

	                    //4.API 통신 시작
	                    $authResultString = "";

	                    if ($httpUtil->processHTTP($authUrl, $authMap)) {
	                        $authResultString = $httpUtil->body;
	                    } else {
							//echo "<script>alert('결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$httpUtil->errormsg."]');location.replace('".$this->fail_url."');</script>";
							$this->common->scriptReplace($this->fail_url,"결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$httpUtil->errormsg."]");
	                    }

	                    $resultMap = json_decode($authResultString, true);

	                    $secureMap["mid"]		= $mid;							//mid
	                    $secureMap["tstamp"]	= $timestamp;					//timestemp
	                    $secureMap["MOID"]		= $resultMap["MOID"];			//MOID
	                    $secureMap["TotPrice"]	= $resultMap["TotPrice"];		//TotPrice

	                    // signature 데이터 생성
	                    $secureSignature = $util->makeSignatureAuth($secureMap);

						if ((strcmp("0000", $resultMap["resultCode"]) == 0) && (strcmp($secureSignature, $resultMap["authSignature"]) == 0) ){	//결제보안 추가 2016-05-18
							$info = $resultMap;

						   	//결제완료 : 결제 성공
						   	$update_info = [
								"o_num"=>getData($info,"MOID",""),
								"o_result_code"=>getData($info,"resultCode",""),
								"o_result_msg"=>getData($info,"resultMsg",""),
								"o_tid"=>getData($info,"tid",""),
								"o_card_cd"=>getData($info,"CARD_Code",""),
								"o_card_name"=>getData($info,"P_FN_NM",""),
								"o_card_ins"=>getData($info,"CARD_Quota",""),
								"o_card_num"=>getData($info,"CARD_Num",""),
								"o_bank_num"=>getData($info,"VACT_Num",""),
								"o_bank_code"=>getData($info,"VACT_BankCode",""),
								"o_bank_name"=>getData($info,"vactBankName",""),
								"o_bank_res_dt"=> date("Y-m-d H:i:s", strtotime(getData($info,"VACT_Date","") . getData($info,"VACT_Time",""))) ,
							];

							$result = $this->order_model->updateOrderResult("order",$update_info,$resultMap);

							if($result==true){
								//$this->common->goLocation($this->finish_url . "/" . getData($info,"MOID",""));
								$this->common->scriptReplace($this->finish_url . "/" . getData($info,"MOID",""));
							}else{
								$this->common->scriptReplace($this->fail_url,"결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.");
								//echo "<script>alert('결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.');location.replace('".$this->fail_url."');</script>";
							}
						} else {
	                        //결제오류 : 오류 처리
	                        //echo "<script>alert('결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$resultMap["resultMsg"]."]');location.replace('".$this->fail_url."');</script>";
							$this->common->scriptReplace($this->fail_url,"결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$resultMap["resultMsg"]."]");
	                    }

	                } catch (Exception $e) {
	                    $error_msg = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
	                    $netcancelResultString = "";
	                    if ($httpUtil->processHTTP($netCancel, $authMap)) {
	                        $netcancelResultString = $httpUtil->body;
	                    } else {

	                    }
						//echo "<script>alert('결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$error_msg."]');location.replace('".$this->fail_url."');</script>";
						$this->common->scriptReplace($this->fail_url,"결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$error_msg."]");

	                }
	            } else {

	            }
	        } catch (Exception $e) {
	            $error_msg = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
				//echo "<script>alert('결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$error_msg."]');location.replace('".$this->fail_url."');</script>";
				$this->common->scriptReplace($this->fail_url,"결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$error_msg."]");
	        }
        }

		//데이터 처리 : 무통장입금
        function inicis_order_virtual()
        {

			@extract($_SERVER);

			$TEMP_IP = getenv("REMOTE_ADDR");
			$PG_IP = substr($TEMP_IP, 0, 10);

			if($PG_IP == "203.238.37" || $PG_IP == "39.115.212" || $PG_IP == "183.109.71") //PG에서 보냈는지 IP로 체크
			{
			    $no_tid = $_REQUEST["no_tid"];
			    $no_oid = $_REQUEST["no_oid"];
			    $cd_bank = $_REQUEST["cd_bank"];
			    $cd_deal = $_REQUEST["cd_deal"];
			    $dt_trans = $_REQUEST["dt_trans"];
			    $tm_trans = $_REQUEST["tm_trans"];
			    $no_vacct = $_REQUEST["no_vacct"];
			    $amt_input = $_REQUEST["amt_input"];
			    $flg_close = $_REQUEST["flg_close"];
			    $cl_close = $_REQUEST["cl_close"];
			    $type_msg = $_REQUEST["type_msg"];
			    $nm_inputbank = $_REQUEST["nm_inputbank"];
			    $nm_input = $_REQUEST["nm_input"];
			    $dt_inputstd = $_REQUEST["dt_inputstd"];
			    $dt_calculstd = $_REQUEST["dt_calculstd"];
			    $dt_transbase = $_REQUEST["dt_transbase"];
			    $cl_trans = $_REQUEST["cl_trans"];
			    $cl_kor = $_REQUEST["cl_kor"];
			    $dt_cshr = $_REQUEST["dt_cshr"];
			    $tm_cshr = $_REQUEST["tm_cshr"];
			    $no_cshr_appl = $_REQUEST["no_cshr_appl"];
			    $no_cshr_tid = $_REQUEST["no_cshr_tid"];

				//로그등록
				$this->order_model->insertOrderVbankLog($no_oid,$_REQUEST);

				//결제완료 : 결제 성공
			   	$update_info = [
					"o_num"=>$no_oid,
				];

				$result = $this->order_model->updateOrderResult("noti",$update_info,$_REQUEST);

				if($result==true){
					echo "OK";
				}else{

				}
			}
		}

		//온라인 주문처리
		function order_online_proc($o_num){
			$update_info = [
				"o_num"=>$o_num
			];
			$result = $this->order_model->updateOrderResult("order",$update_info,$update_info);

			if($result==true){
				$this->common->scriptReplace($this->finish_url . "/" . $o_num);
			}else{
				//echo "<script>alert('결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.');location.replace('".$this->fail_url."');</script>";
				$this->common->scriptReplace($this->fail_url,"결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.");
			}
		}

		//데이터 처리 : 주문처리
        function inicis_order_next()
        {
        	$session_check = $this->common->getSession("ORDER_PROC");
			if($session_check!="NOWJOB_STEP1"){
				$this->common->scriptReplace("/mypage");
				exit;
			}

			header("Content-Type: text/html; charset=utf-8");
			extract($_POST);
			extract($_GET);

	        try {

	            //인증이 성공일 경우만
	            if ($_REQUEST["P_STATUS"] === "00") {
	                $id_merchant = substr($_REQUEST["P_TID"],'10','10');     // P_TID 내 MID 구분
					$data = array(
					  'P_MID' => $id_merchant,        		 // P_MID
					  'P_TID' => $_REQUEST["P_TID"]          // P_TID
					);

					// curl 통신 시작
					$ch = curl_init(); //curl 초기화
					curl_setopt($ch, CURLOPT_URL, $_REQUEST["P_REQ_URL"]);      //URL 지정하기
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);     //connection timeout 10초
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);           //원격 서버의 인증서가 유효한지 검사 안함
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));    //POST 로 $data 를 보냄
					curl_setopt($ch, CURLOPT_POST, 1);                                         //true시 post 전송

					$response = curl_exec($ch);
					curl_close($ch);

					$info = [];
					parse_str($response, $info);

					if($info["P_STATUS"] == "00"){
						//결제완료 : 결제 성공
					   	$update_info = [
							"o_num"=>getData($info,"P_OID",""),
							"o_result_code"=>getData($info,"P_STATUS",""),
							"o_result_msg"=>getData($info,"P_RMESG1",""),
							"o_tid"=>getData($info,"P_TID",""),
							"o_card_cd"=>getData($info,"P_FN_CD1",""),
							"o_card_name"=>getData($info,"P_FN_NM",""),
							"o_card_ins"=>getData($info,"P_RMESG2",""),
							"o_card_num"=>getData($info,"P_CARD_NUM",""),
							"o_bank_num"=>getData($info,"P_VACT_NUM",""),
							"o_bank_code"=>getData($info,"P_VACT_BANK_CODE",""),
							"o_bank_name"=>getData($info,"P_FN_NM",""),
							"o_bank_res_dt"=> date("Y-m-d H:i:s", strtotime(getData($info,"P_VACT_DATE","") . getData($info,"P_VACT_TIME",""))) ,
						];

						$result = $this->order_model->updateOrderResult("order",$update_info,$info);

						if($result==true){
							$this->common->goLocation($this->finish_url . "/" . getData($info,"P_OID",""));
							//$this->common->scriptReplace($this->finish_url . "/" . getData($info,"P_OID",""));
						}else{
							//echo "<script>alert('결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.');location.replace('".$this->fail_url."');</script>";
							$this->common->scriptReplace($this->fail_url,"결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.");
						}
					}else{
						//echo "<script>alert('결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$resultMap["resultMsg"]."]');location.replace('".$this->fail_url."');</script>";
						$this->common->scriptReplace($this->fail_url,"결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$resultMap["resultMsg"]."]");
					}
	            } else {
	            	if($_REQUEST["P_STATUS"] === "01"){
	            		$this->common->scriptReplace("/mypage","결제를 취소하였습니다.다시 진행해주세요.");
	            	}else{
	            		$this->common->scriptReplace($this->fail_url,"결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$resultMap["resultMsg"]."]");
					}
	            }
	        } catch (Exception $e) {
	            $error_msg = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
				//echo "<script>alert('결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$error_msg."]');location.replace('".$this->fail_url."');</script>";
				$this->common->scriptReplace($this->fail_url,"결제오류 가 발생하였습니다.\\n다시 결제 진행부탁드립니다.\\n[".$error_msg."]");
	        }
        }

		function order_refund(){
			$o_num = input_post("o_num","");
			$order_info = $this->order_model->getOrderMaster($o_num);
			$mem_info = $this->member_model->getMember(["mm_idx"=>$order_info["mm_idx"]]);
			$pg_info = $this->order_model->getPgInfo();

			if($order_info==null){
				json_return("91","결제 정보가 존재하지 않습니다.");
			}

			if($mem_info==null){
				json_return("91","회원 정보가 존재하지 않습니다.");
			}

			//포인트 확인
			$point_info = $this->setting_model->getCodeInfo(["cd_grp"=>"구매포인트","cd_key"=>$order_info["o_goods_code"]]);
			$last_point = $mem_info["mm_point"] - $point_info["cd_val2"];

			if($last_point < 0){
				json_return("91","보유 포인트가 부족하여 환불이 불가능합니다.");
			}

			$this->order_model->setOrderLog($order_info["o_num"],"결제취소시도","싸이트",$mem_info["mm_name"] . "님이 결제 취소 시도 하셨습니다.");

			//결제 취소 처리 : 가상계좌는
			if($order_info["o_paymethod"]=="COUPON"){

			}else if($order_info["o_paymethod"]=="CARD" || $order_info["o_paymethod"]=="BANK" || $order_info["o_paymethod"]=="HPP"){
				//익월인경우 : HPP
				if($order_info["o_paymethod"]=="HPP" && substr($order_info["o_pay_dt"],0,7)!=substr(get_today(),0,7)){
					$this->order_model->setOrderLog($order_info["o_num"],"결제취소실패","싸이트","휴대폰 익월 취소로 결제 취소 실패");
					json_return("92","취소 처리가 불가능한 상태입니다. 관리자에 문의 해주세요.");
				}else{
					$result = $this->common->gpCancel($order_info["o_pg_name"],$pg_info,$order_info["o_paymethod"],"Refund",$order_info["o_tid"],"고객요청 취소",$price,$confirm_price);
					if($result["code"]!="00"){
						$this->order_model->setOrderLog($order_info["o_num"],"결제취소실패","싸이트","이니시스 실패 이유 : " . $result["msg"]);
						json_return("92",$result["msg"]);
					}else{
						$this->order_model->setOrderLog($order_info["o_num"],"결제취소성공","싸이트", $result["msg"]);
					}
				}
			}else{
				$this->order_model->setOrderLog($order_info["o_num"],"결제취소실패","싸이트","가상계좌 취소로 결제 취소 실패");
				json_return("92","가상계좌 결제 취소는 현재 불가능합니다. 관리자에 문의해주세요.");
			}

			# 주문 상태 정보 변경.
			$update = [
				"o_status"=>"주문취소",
				"o_coupon_cancel"=>$order_info["o_coupon_price"],
				"o_total_cancel"=>$order_info["o_total_price"],
				"o_cancel_dt"=>get_today(),
				"up_dt"=>get_today()
			];
			$this->order_model->updateOrder($order_info["o_num"],$update);

			# 쿠폰 취소
			if($order_info["o_coupon_idx"]!=0 && $order_info["o_coupon_price"]>0){
				$this->site_model->updateCouponList(["cl_use_yn"=>"N"],["cl_idx"=>$order_info["o_coupon_idx"]]);
			}

			# 포인트 취소
			$insert = [
				"mm_idx" => $mem_info["mm_idx"],
				"o_num"=>$order_info["o_num"],
				"pt_type" => "ORDER",
				"pl_type" => "minus",
				"pl_point" => $point_info["cd_val2"],
				"pl_last_point" => $last_point,
				"pl_desc" => $order_info["o_num"] . " 결제취소",
				"reg_id" => "system",
			];
			$result_point = $this->site_model->insertPointList($insert);

			//회원 정보 갱신한다.
			if($result_point["code"]=="00"){
				$this->member_model->updateMember(["mm_point"=>$last_point],["mm_idx"=>$order_info["mm_idx"]]);
			}

			$this->order_model->setOrderLog($order_info["o_num"],"결제취소완료","싸이트", "결제 취소 최종 완료");

			json_return("00","결제 취소가 완료 되었습니다.");
		}
    }
?>
