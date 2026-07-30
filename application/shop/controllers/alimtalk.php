<?php
class Alimtalk extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('membermodel', 'member_model');
		$this->load->model('commonmodel', 'common_model');

		// 모든 요청에서 os_type 헤더를 세션에 저장 (앱 웹뷰 판별용)
		$headers = apache_request_headers();
		if(isset($headers["os_type"])){
			$this->common->setSession("os_type", $headers["os_type"]);
		}
	}

	// 개인 사용자용 브릿지
	function send_per_bridge()
	{
		$data = [];
		$is_login = $this->user->isLogin();
		$user_type = $this->user->getUserType();
		$is_person = $is_login && $user_type != 'COMP';

		$data['is_login'] = $is_login ? 'true' : 'false';
		$data['is_person'] = $is_person ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_per_bridge', $data);
	}

	// 기업용 브릿지
	function send_corp_bridge()
	{
		$data = [];
		$is_login = $this->user->isLogin();
		$user_type = $this->user->getUserType();
		$is_corp = $is_login && $user_type == 'COMP';

		$data['is_login'] = $is_login ? 'true' : 'false';
		$data['is_corp'] = $is_corp ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_corp_bridge', $data);
	}

	// 기업 매칭용 브릿지
	function send_match_corp_bridge()
	{
		$data = [];
		$is_login = $this->user->isLogin();
		$user_type = $this->user->getUserType();
		$is_corp = $is_login && $user_type == 'COMP';

		$data['is_login'] = $is_login ? 'true' : 'false';
		$data['is_corp'] = $is_corp ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_match_corp_bridge', $data);
	}

	// 개인 매칭용 브릿지
	function send_match_per_bridge()
	{
		$data = [];
		$is_login = $this->user->isLogin();
		$user_type = $this->user->getUserType();
		$is_person = $is_login && $user_type != 'COMP';

		$data['is_login'] = $is_login ? 'true' : 'false';
		$data['is_person'] = $is_person ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_match_per_bridge', $data);
	}

}
