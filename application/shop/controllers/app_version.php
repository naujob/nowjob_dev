<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_version extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('appversionmodel', 'app_version_model');
	}

	//앱 업데이트 정책 조회
	public function version_check()
	{
		header('Content-Type: application/json; charset=utf-8');

		$platform_input = $this->input->get('platform', TRUE);
		$platform = strtolower(trim($platform_input === null ? '' : $platform_input));
		$version_input = $this->input->get('version', TRUE);
		$version = trim($version_input === null ? '' : $version_input);
		$policy = $this->app_version_model->getPolicyByPlatform($platform);

		if ($platform === '') {
			if (isset($policy['platform'])) {
				$platform_value = $policy['platform'];
			} else {
				$platform_value = 'unknown';
			}
		} else {
			$platform_value = $platform;
		}

		$response = [
			'platform' => $platform_value,
			'latestVersion' => isset($policy['latest_version']) ? $policy['latest_version'] : '',
			'minVersion' => isset($policy['min_version']) ? $policy['min_version'] : '',
			'updateType' => 'NONE',
			'message' => isset($policy['message_optional']) ? $policy['message_optional'] : '',
			'storeUrl' => isset($policy['store_url']) ? $policy['store_url'] : ''
		];

		if (empty($platform) || empty($version) || $policy === null) {
			echo json_encode($response, JSON_UNESCAPED_UNICODE);
			return;
		}

		$updateType = 'NONE';
		if (version_compare($version, $policy['min_version'], '<')) {
			$updateType = 'FORCE';
		} elseif (version_compare($version, $policy['latest_version'], '<')) {
			$updateType = 'OPTIONAL';
		}

		$response['updateType'] = $updateType;
		if ($updateType === 'FORCE') {
			$response['message'] = isset($policy['message_force']) ? $policy['message_force'] : $response['message'];
		} elseif ($updateType === 'OPTIONAL') {
			$response['message'] = isset($policy['message_optional']) ? $policy['message_optional'] : $response['message'];
		} else {
			$response['message'] = isset($policy['message_optional']) ? $policy['message_optional'] : $response['message'];
		}

		echo json_encode($response, JSON_UNESCAPED_UNICODE);
	}
}
