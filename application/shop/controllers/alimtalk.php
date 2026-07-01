<?php
class Alimtalk extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('membermodel', 'member_model');
		$this->load->model('commonmodel', 'common_model');
	}

	// Alimtalk 브릿지 페이지 - 앱으로 리디렉트하는 단순 브릿지 (기업용)
	function send_corp_bridge()
	{
		$data = [];
		// 로그인 여부 확인
		$data['is_login'] = $this->user->isLogin() ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_corp_bridge', $data);
	}

	// 개인 사용자용 브릿지
	function send_per_bridge()
	{
		$data = [];
		// 로그인 여부 확인
		$data['is_login'] = $this->user->isLogin() ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_per_bridge', $data);
	}

	// 기업 매칭용 브릿지
	function send_match_corp_bridge()
	{
		$data = [];
		// 로그인 여부 확인
		$data['is_login'] = $this->user->isLogin() ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_match_corp_bridge', $data);
	}

	// 개인 매칭용 브릿지
	function send_match_per_bridge()
	{
		$data = [];
		// 로그인 여부 확인
		$data['is_login'] = $this->user->isLogin() ? 'true' : 'false';

		$this->load->view('mobile/alimtalk/send_match_per_bridge', $data);
	}

}
