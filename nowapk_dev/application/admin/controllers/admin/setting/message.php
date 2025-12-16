<?php
    class Message extends MY_Controller{
		function __construct() { 
			parent::__construct();
			
			//모델로드
			$this->load->model('settingmodel','setting_model');
			$this->load->model('ordermodel','order_model');
	    } 
		
		//SMS 정보 설정
		function setting(){
			$info = $this->setting_model->getSettingEtcInfo();
			$yyyymm = date("Ym");
			$month = date("m");
			$direct_info = [
				"connected" => "N",
				"price" => 0,
				"send_sms_price" => "9.5",
				"send_kakao_price" => "7.5",
				"send_email_price" => "0.5",
				"send_sms_cnt" => 0,
				"send_kakao_cnt" => 0,
				"send_email_cnt" => 0,
			];
			
			
			//잔액조회
			$remain = $this->common->getRemainCoin($info["st_direct_id"],$info["st_direct_key"]);
			
			//포인트 확인
			if(isset($remain["point"])){
				$direct_info["connected"] = "Y";
				$direct_info["price"] = $remain["point"];
			}
			
			//발송가능 건수 설정
			$direct_info["send_sms_cnt"] = $this->setting_model->getSmsSendCnt("sms",$yyyymm);
			$direct_info["send_kakao_cnt"] = $this->setting_model->getSmsSendCnt("kakao",$yyyymm);
			$direct_info["send_email_cnt"] = $this->setting_model->getSmsSendCnt("email",$yyyymm);
			
			//파라미터 설정
			$data = [
				"info" => $info,
				"direct_info" => $direct_info,
				"month" => $month,
			];
			
			$this->layout->admin('admin/setting/message/setting',$data);
		}
		
		//회원 등급 정책 처리
		function setting_proc(){
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
		
		//SMS 정보 설정
		function setting_sms(){
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
				"ss_type" => "sms",
			];
			
			//데이터 설정
			$result = $this->setting_model->getSmsSettingList($params);
		
			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/setting/message/setting_sms',$data);
		}
		
		//SMS 정보 설정 수정
		function setting_sms_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);
			
			//데이터 설정
			$info = $this->setting_model->getSmsSetting($idx);
			
			//리턴 설정
			$data = [
				"info" => $info,
			];
			
			$this->layout->admin('admin/setting/message/setting_sms_edit',$data);
		}
		
		//알림톡 정보 설정
		function setting_talk(){
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
				"ss_type" => "kakao",
			];
			
			//데이터 설정
			$result = $this->setting_model->getSmsSettingList($params);
		
			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/setting/message/setting_talk',$data);
		}
		
		//SMS 정보 설정 수정
		function setting_talk_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);
			
			//데이터 설정
			$info = $this->setting_model->getSmsSetting($idx);
			
			//리턴 설정
			$data = [
				"info" => $info,
			];
			
			$this->layout->admin('admin/setting/message/setting_talk_edit',$data);
		}
		
		//알림톡 정보 설정
		function setting_email(){
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
				"ss_type" => "email",
			];
			
			//데이터 설정
			$result = $this->setting_model->getSmsSettingList($params);
		
			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			$this->layout->admin('admin/setting/message/setting_email',$data);
		}
		
		//SMS 정보 설정 수정
		function setting_email_edit(){
			//파라미터 설정
			$idx = input_get("idx",0);
			
			//데이터 설정
			$info = $this->setting_model->getSmsSetting($idx);
			
			//리턴 설정
			$data = [
				"info" => $info,
			];
			
			$this->layout->admin('admin/setting/message/setting_email_edit',$data);
		}
		
		//SMS 정보 설정 수정
		function setting_sms_proc(){
			//파라미터 설정
			$params = input_post_all();
			
			$result = $this->setting_model->updateSmsSetting($params, ["ss_idx"=>$params["ss_idx"]]);
			
			json_return($result["code"],$result["msg"],"");
		}
		
		//개별/전체 SMS 발송
		function send_sms(){
			//파라미터 설정
			$data['info'] = [];
			$this->layout->admin('admin/setting/message/send_sms',$data);
		}
		
		//SMS/알림톡 발송내역
		function sms_history(){
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
				"ss_type" => "sms",
			];
			
			//데이터 설정
			$result = $this->setting_model->getSmsSendList($params);
		
			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			
			$this->layout->admin('admin/setting/message/sms_history',$data);
		}
		
		//SMS 정보 설정 수정
		function sms_history_view(){
			//파라미터 설정
			$idx = input_get("idx",0);
			
			//데이터 설정
			$info = $this->setting_model->getSmsSendInfo($idx);
			
			//리턴 설정
			$data = [
				"info" => $info,
			];
			
			$this->layout->admin('admin/setting/message/sms_history_view',$data);
		}
		
		//이메일 발송내역
		function talk_history(){
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
				"ss_type" => "kakao",
			];
			
			//데이터 설정
			$result = $this->setting_model->getSmsSendList($params);
		
			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			
			$this->layout->admin('admin/setting/message/talk_history',$data);
		}
		
		//SMS 정보 설정 수정
		function talk_history_view(){
			//파라미터 설정
			$idx = input_get("idx",0);
			
			//데이터 설정
			$info = $this->setting_model->getSmsSendInfo($idx);
			
			//리턴 설정
			$data = [
				"info" => $info,
			];
			
			$this->layout->admin('admin/setting/message/talk_history_view',$data);
		}
		
		//이메일 발송내역
		function email_history(){
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
				"ss_type" => "email",
			];
			
			//데이터 설정
			$result = $this->setting_model->getSmsSendList($params);
		
			//리턴 설정
			$data = [
				"search" =>[
					"use_yn" => $use_yn,
					"search_type" => $search_type,
					"search_value" => $search_value,
				],
				"result_list" => $result["list"],
				"result_count" => $result["count"],
				"pagenation" => $result["pagenation"],
			];
			
			
			$this->layout->admin('admin/setting/message/email_history',$data);
		}
		
		//SMS 정보 설정 수정
		function email_history_view(){
			//파라미터 설정
			$idx = input_get("idx",0);
			
			//데이터 설정
			$info = $this->setting_model->getSmsSendInfo($idx);
			
			//리턴 설정
			$data = [
				"info" => $info,
			];
			
			$this->layout->admin('admin/setting/message/email_history_view',$data);
		}
    }
?>