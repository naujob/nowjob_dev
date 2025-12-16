<?php
    class Keyword extends MY_Controller{
    	private $admin_info; 
		function __construct() { 
	        parent::__construct();
			
			//모델로드
			$this->load->model('settingmodel','setting_model');
	    } 
	
		//키워드 관리
		public function setting(){
			//파라미터 설정
			//데이터 설정
			$data = [
				"info" => $this->setting_model->getSettingInfo(),
			];
			$this->layout->admin('admin/setting/keyword/setting',$data);
		}
		
		//무통장 입금 은행 관리
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
    }
?>