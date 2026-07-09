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

	// Alimtalk 브릿지 페이지 - 앱으로 리디렉트하는 단순 브릿지 (기업용)
	function send_corp_bridge()
	{
		$data = [];
		$data['is_login'] = $this->user->isLogin() ? 'true' : 'false';
		$data['is_corp'] = ($this->user->isLogin() && $this->user->getUserType() == 'COMP') ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_corp_bridge', $data);
	}

	// 개인 사용자용 브릿지
	function send_per_bridge()
	{
		$data = [];
		$data['is_login'] = $this->user->isLogin() ? 'true' : 'false';
		$data['is_person'] = ($this->user->isLogin() && $this->user->getUserType() != 'COMP') ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_per_bridge', $data);
	}

	// 기업 매칭용 브릿지
	function send_match_corp_bridge()
	{
		$data = [];
		$data['is_login'] = $this->user->isLogin() ? 'true' : 'false';
		$data['is_corp'] = ($this->user->isLogin() && $this->user->getUserType() == 'COMP') ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_match_corp_bridge', $data);
	}

	// 개인 매칭용 브릿지
	function send_match_per_bridge()
	{
		$data = [];
		$data['is_login'] = $this->user->isLogin() ? 'true' : 'false';
		$data['is_person'] = ($this->user->isLogin() && $this->user->getUserType() != 'COMP') ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_match_per_bridge', $data);
	}

}
