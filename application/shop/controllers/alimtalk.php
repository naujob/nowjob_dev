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

	// 현재 요청 도메인이 개발(dev.) 서브도메인인지 판별 (index.php의 host 판별 로직과 동일)
	private function _is_dev_domain()
	{
		$server_name = '';
		if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $_SERVER['HTTP_X_FORWARDED_HOST'] !== '') {
			$tmp = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
			$server_name = trim($tmp[0]);
		} elseif (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== '') {
			$server_name = $_SERVER['HTTP_HOST'];
		} elseif (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] !== '') {
			$server_name = $_SERVER['SERVER_NAME'];
		}

		$server_name = strtolower($server_name);
		$host_no_port = $server_name;
		$pos = strpos($host_no_port, ':');
		if ($pos !== false) {
			$host_no_port = substr($host_no_port, 0, $pos);
		}

		return strpos($host_no_port, 'dev.') === 0;
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

		// 운영(non-dev 도메인)에서는 개선된 브릿지 뷰(per.php) 사용, 개발(dev.) 도메인은 기존 뷰 유지
		$view_name = $this->_is_dev_domain() ? 'mobile/alimtalk/send_per_bridge' : 'mobile/alimtalk/per';

		$this->load->view($view_name, $data);
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

		// 운영(non-dev 도메인)에서는 개선된 브릿지 뷰(corp.php) 사용, 개발(dev.) 도메인은 기존 뷰 유지
		$view_name = $this->_is_dev_domain() ? 'mobile/alimtalk/send_corp_bridge' : 'mobile/alimtalk/corp';

		$this->load->view($view_name, $data);
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

		// 운영(non-dev 도메인)에서는 개선된 브릿지 뷰(match_corp.php) 사용, 개발(dev.) 도메인은 기존 뷰 유지
		$view_name = $this->_is_dev_domain() ? 'mobile/alimtalk/send_match_corp_bridge' : 'mobile/alimtalk/match_corp';

		$this->load->view($view_name, $data);
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

		// 운영(non-dev 도메인)에서는 개선된 브릿지 뷰(match_per.php) 사용, 개발(dev.) 도메인은 기존 뷰 유지
		$view_name = $this->_is_dev_domain() ? 'mobile/alimtalk/send_match_per_bridge' : 'mobile/alimtalk/match_per';

		$this->load->view($view_name, $data);
	}

}
