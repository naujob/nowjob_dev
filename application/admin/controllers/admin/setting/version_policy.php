<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Version_policy extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('appversionmodel', 'app_version_model');
	}

	//관리자 화면(정책 리스트)
	function index() {
		$data = [
			'policies' => [
				'android' => $this->app_version_model->getPolicyByPlatform('android'),
				'ios' => $this->app_version_model->getPolicyByPlatform('ios'),
			]
		];

		$this->layout->admin('admin/setting/version_policy/index', $data);
	}

	//정책 저장 처리
	function index_proc() {
		$platform = input_post('platform', '');
		$payload = [
			'min_version' => input_post('min_version', ''),
			'latest_version' => input_post('latest_version', ''),
			'message_force' => input_post('message_force', ''),
			'message_optional' => input_post('message_optional', ''),
			'store_url' => input_post('store_url', ''),
		];

		$result = $this->app_version_model->savePolicy($platform, $payload);
		json_return($result['code'], $result['msg']);
	}
}
