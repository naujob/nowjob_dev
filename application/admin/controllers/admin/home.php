<?php
    class Home extends MY_Controller{
		function __construct() {
	        parent::__construct();

			//모델로드
			$this->load->model('settingmodel','setting_model');
			$this->load->model('sitemodel','site_model');
	    }

		//메인 페이지
        function index()
        {
			$data ="";
			
			$data["info"] = $this->site_model->getMainData();
			$this->layout->admin('admin/home/index',$data);
        }

		//디자인 페이지
        function design()
        {
			$data ="";
			$this->layout->admin('admin/home/design',$data);
        }

		//로그인화면
		function login()
        {
            if($this->admin->isAdminLogin()){
                $this->common->goLocation('/admin/home');
                exit;
            }

			$admin_info = $this->common->getSession("ADMIN_INFO");
        	$data ="";
        	$this->layout->admin_empty('admin/home/login',$data);
        }

		//로그인화면
		function test()
        {
            $data = [];
			//$_SESSION["TEST0001"] = "adfasdfasdfasdf";
			var_dump($_SESSION);
        	$this->layout->admin_empty('admin/home/test',$data);
        }


        //관리자 로그인처리
        function admin_login_proc()
        {
            $post = input_post_all();
            $result = $this->setting_model->adminLogin($post["admin_id"],$post["admin_pwd"]);

            if($result == true){
                json_return("00","로그인 성공","");
            }else{
                json_return("90","아이디/비밀번호를 확인해주세요.","");
            }
        }

		//로그아웃처리
		function logout(){
			$this->common->removeSession("ADMIN_INFO");

			//자동로그인제거
			if(isset($_COOKIE['auto_admin_id'])){
				setcookie("auto_admin_id",null,-1,"/");
			}

			$this->common->goLocation("/admin/home/login");
		}
    }
?>
