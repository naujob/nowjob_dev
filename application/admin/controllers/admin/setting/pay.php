<?php
    class Pay extends MY_Controller{
    	private $admin_info; 
		function __construct() { 
	        parent::__construct();
			
			//모델로드
			$this->load->model('settingmodel','setting_model');
	    } 
		
		//결제 수단 설정
		public function setting(){
			//파라미터 설정
			
			//데이터 설정
			$data = [
				"info" => $this->setting_model->getSettingInfo(),
			];
			$this->layout->admin('admin/setting/pay/setting',$data);
		}
		
		//결제 수단 설정 저장
		public function setting_proc(){
			//파라미터 설정
			$params = input_post_all();
			
			//처리
			$result = [];
			$update = $params;
			
			//저장
			$where = ["st_type"=>"shopping"];	//이부분세션으로 변경해야함
			$result = $this->setting_model->updateSettingBasic($update,$where);
			
			json_return($result["code"],$result["msg"],"");
		}
		
		//무통장 입금 은행 관리
		public function bank(){
			//파라미터 설정
			$data['bank_list'] = $this->setting_model->getBankList();
			$this->layout->admin('admin/setting/pay/bank',$data);
		}
		
		//무통장 입금 은행 관리
		public function bank_proc(){
			//파라미터 설정
			$params = input_post_all();
			
			//처리
			$result = [];
			$update = $params;
			
			//저장
			$where = ["st_type"=>"shopping"];	//이부분세션으로 변경해야함
			$result = $this->setting_model->updateSettingBasic($update,$where);
			
			json_return($result["code"],$result["msg"],"");
		}
		
		//PG 연동 관리
		public function setting_pg(){
			//파라미터 설정
			
			//데이터 설정
			$result = $this->setting_model->getPgList();
		
			//리턴 설정
			$data = [
				"list" => $result,
			];
			
			$this->layout->admin('admin/setting/pay/setting_pg',$data);
		}
		
		//PG 연동 관리
		public function setting_pg_detail(){
			//파라미터 설정
			$idx = input_get("idx",0);
			
			$data['info'] = $this->setting_model->db_row("st_setting_pg",["sp_idx" => $idx]);
			
			$this->layout->admin('admin/setting/pay/setting_pg_detail',$data);
		}
		
		//운영자 계정관리 등록,수정 처리
		public function setting_pg_proc(){
			//파라미터 설정
			$params = input_post_all();
			
			//처리
			$result = [];
			if($params["proc_mode"]=="update"){
				
				$update = [
					"sp_name"=>$params["sp_name"],
					"sp_default_yn"=>$params["sp_default_yn"],
					"sp_test_yn"=>$params["sp_test_yn"],
					"sp_shop_id"=>$params["sp_shop_id"],
					"sp_shop_key"=>$params["sp_shop_key"],
					"sp_shop_cancel_key"=>$params["sp_shop_cancel_key"],
					"sp_test_id"=>$params["sp_test_id"],
					"sp_test_key"=>$params["sp_test_key"],
					"sp_test_cancel_key"=>$params["sp_test_cancel_key"],
					"sp_ins_yn"=>$params["sp_ins_yn"],
					"sp_ins_months"=> get_chk_string($params["sp_ins_months"]),
					"sp_vbank_url"=>$params["sp_vbank_url"],
					//"sp_proc_url"=>$params["sp_proc_url"],
					"sp_vbank_priod"=>$params["sp_vbank_priod"],
				];
				
				//저장
				$result = $this->setting_model->db_update("st_setting_pg",$update,["sp_idx"=>$params["sp_idx"]]);
			}
			
			json_return($result["code"],$result["msg"],"");
		}
		
		//결제대기 자동주문취소 설정
		public function auto_cancel(){
			//파라미터 설정
			//데이터 설정
			$data = [
				"info" => $this->setting_model->getSettingInfo(),
			];
			$this->layout->admin('admin/setting/pay/auto_cancel',$data);
		}
		
		//무통장 입금 은행 관리
		public function auto_cancel_proc(){
			//파라미터 설정
			$params = input_post_all();
			
			//처리
			$result = [];
			$update = $params;
			
			//저장
			$where = ["st_type"=>"shopping"];	//이부분세션으로 변경해야함
			$result = $this->setting_model->updateSettingBasic($update,$where);
			
			json_return($result["code"],$result["msg"],"");
		}
		
		//결제대기 자동주문취소 설정
		public function setting_point(){
			//파라미터 설정
			//데이터 설정
			$data = [
				"info" => $this->setting_model->getSettingInfo(),
			];
			$this->layout->admin('admin/setting/pay/setting_point',$data);
		}
		
		//무통장 입금 은행 관리
		public function setting_point_proc(){
			//파라미터 설정
			$params = input_post_all();
			
			//처리
			$result = [];
			$update = $params;
			
			//저장
			$where = ["st_type"=>"shopping"];	//이부분세션으로 변경해야함
			$result = $this->setting_model->updateSettingBasic($update,$where);
			
			json_return($result["code"],$result["msg"],"");
		}
    }
?>