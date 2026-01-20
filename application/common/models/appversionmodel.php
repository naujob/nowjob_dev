<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class appversionModel extends MY_Model {
	function __construct() {
		parent::__construct();
	}

	/**
	 * 플랫폼별 업데이트 정책 조회
	 *
	 * @param string $platform 'android' 또는 'ios'
	 * @return array|null
	 */
	function getPolicyByPlatform($platform) {
		$platform = strtolower(trim($platform));
		if ($platform === '') {
			return null;
		}

		return $this->db_row('app_version_policy', ['platform' => $platform]);
	}

	/**
	 * 플랫폼별 정책을 저장하거나 새로 등록합니다.
	 *
	 * @param string $platform
	 * @param array $data
	 * @return array
	 */
	function savePolicy($platform, $data) {
		$platform = strtolower(trim($platform));
		if ($platform === '') {
			return $this->get_error('11', 'Platform is required.');
		}

		$payload = [
			'platform' => $platform,
			'min_version' => isset($data['min_version']) ? trim($data['min_version']) : '',
			'latest_version' => isset($data['latest_version']) ? trim($data['latest_version']) : '',
			'message_force' => isset($data['message_force']) ? trim($data['message_force']) : '',
			'message_optional' => isset($data['message_optional']) ? trim($data['message_optional']) : '',
			'store_url' => isset($data['store_url']) ? trim($data['store_url']) : '',
		];

		if ($this->getPolicyByPlatform($platform) !== null) {
			return $this->db_update('app_version_policy', $payload, ['platform' => $platform]);
		}

		return $this->db_insert('app_version_policy', $payload);
	}
}
