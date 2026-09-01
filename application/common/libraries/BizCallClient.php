<?php

class CI_BizCallClient
{
	private $config;

	public function __construct()
	{
		if (function_exists('config_item')) {
			$cfg = config_item('bizcall');
			if (is_array($cfg)) {
				$this->config = $cfg;
				return;
			}
		}

		$configPath = (defined('SHAREDPATH') ? SHAREDPATH : APPPATH . 'common/') . 'config/bizcall.php';
		if (file_exists($configPath)) {
			$config = array();
			include $configPath;
			if (isset($config['bizcall']) && is_array($config['bizcall'])) {
				$this->config = $config['bizcall'];
				return;
			}
		}
	}

	// 최초 발급 — 시간제한 매핑 (auto_expire_mapp.do)
	public function assignAutoExpireVirtualNumber($realNumber, $memo, $expireHours)
	{
		$enabled = $this->getConfigValue('enabled');
		if ($enabled === false || $enabled === 0 || $enabled === 'false' || $enabled === '0') {
			if (function_exists('log_message')) {
				log_message('info', '[BIZCALL] 기능이 비활성화되어 있음 - 발송하지 않음');
			}
			return array(
				'ok' => false,
				'vn' => null,
				'response' => null,
				'error' => '비즈콜 기능이 비활성화되어 있습니다'
			);
		}

		$iid = $this->getConfigValue('iid');
		$apiUrl = $this->getConfigValue('auto_expire_mapp_url');

		if ($iid === '' || $apiUrl === '') {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL] 설정 누락 - iid=' . $iid . ', auto_expire_mapp_url=' . $apiUrl);
			}
			return array(
				'ok' => false,
				'vn' => null,
				'response' => null,
				'error' => '비즈콜 설정 누락(iid/auto_expire_mapp_url)'
			);
		}

		$realNumber = preg_replace('/[^0-9]/', '', (string)$realNumber);
		$auth = base64_encode(md5($iid . $realNumber));

		$postFields = array(
			'iid' => $iid,
			'rn' => $realNumber,
			'memo' => (string)$memo,
			'expire_hour' => (int)$expireHours,
			'auth' => $auth
		);

		$result = $this->callApi($apiUrl, $postFields, 'AUTO_EXPIRE_MAPP');

		if ($result['ok'] && isset($result['response']['vn'])) {
			$result['vn'] = $result['response']['vn'];
		} else {
			$result['vn'] = null;
		}

		return $result;
	}

	// 통화 발생 시 만료시간 연장 (auto_expire_update.do)
	public function extendVirtualNumberExpire($vNumber, $expireHours)
	{
		$enabled = $this->getConfigValue('enabled');
		if ($enabled === false || $enabled === 0 || $enabled === 'false' || $enabled === '0') {
			return array(
				'ok' => false,
				'response' => null,
				'error' => '비즈콜 기능이 비활성화되어 있습니다'
			);
		}

		$iid = $this->getConfigValue('iid');
		$apiUrl = $this->getConfigValue('auto_expire_update_url');

		if ($iid === '' || $apiUrl === '') {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL] 설정 누락 - iid=' . $iid . ', auto_expire_update_url=' . $apiUrl);
			}
			return array(
				'ok' => false,
				'response' => null,
				'error' => '비즈콜 설정 누락(iid/auto_expire_update_url)'
			);
		}

		$auth = base64_encode(md5($iid . $vNumber));

		$postFields = array(
			'iid' => $iid,
			'vn' => $vNumber,
			'expire_hour' => (int)$expireHours,
			'auth' => $auth
		);

		return $this->callApi($apiUrl, $postFields, 'AUTO_EXPIRE_UPDATE');
	}

	// POST 요청 보내고 응답 파싱 (공통 로직)
	private function callApi($apiUrl, array $postFields, $logTag)
	{
		if ($apiUrl === '') {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][' . $logTag . '] api url 누락');
			}
			return array('ok' => false, 'response' => null, 'error' => 'API URL 누락');
		}

		$ch = curl_init();
		if ($ch === false) {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][' . $logTag . '] cURL 초기화 실패');
			}
			return array('ok' => false, 'response' => null, 'error' => 'cURL 초기화 실패');
		}

		curl_setopt_array($ch, array(
			CURLOPT_URL => $apiUrl,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($postFields),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT => 15,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
		));

		if (function_exists('log_message')) {
			log_message('info', '[BIZCALL][' . $logTag . '][REQ] ' . http_build_query($postFields));
		}

		$responseBody = curl_exec($ch);
		if ($responseBody === false) {
			$err = curl_error($ch);
			curl_close($ch);
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][' . $logTag . '][REQ-FAIL] ' . $err);
			}
			return array('ok' => false, 'response' => null, 'error' => '요청 실패: ' . $err);
		}

		$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$decoded = json_decode($responseBody, true);
		if (function_exists('log_message')) {
			log_message('info', '[BIZCALL][' . $logTag . '][RESP] status=' . $httpStatus . ' body=' . $responseBody);
		}

		if ($httpStatus < 200 || $httpStatus >= 300) {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][' . $logTag . '][RESP-ERROR] HTTP ' . $httpStatus);
			}
			return array(
				'ok' => false,
				'response' => ($decoded !== null ? $decoded : $responseBody),
				'error' => 'HTTP 오류: ' . $httpStatus,
			);
		}

		if (!is_array($decoded)) {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][' . $logTag . '][RESP-ERROR] JSON 파싱 실패 - body=' . substr($responseBody, 0, 200));
			}
			return array(
				'ok' => false,
				'response' => null,
				'error' => '응답 파싱 실패(JSON 아님)',
			);
		}

		$rt = isset($decoded['rt']) ? (int)$decoded['rt'] : null;
		if ($rt === 0) {
			if (function_exists('log_message')) {
				log_message('info', '[BIZCALL][' . $logTag . '][SUCCESS]');
			}
			return array(
				'ok' => true,
				'response' => $decoded,
				'error' => null,
			);
		} else {
			$rs = isset($decoded['rs']) ? $decoded['rs'] : '알 수 없는 오류';
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][' . $logTag . '][FAIL] rt=' . $rt . ', rs=' . $rs);
			}
			return array(
				'ok' => false,
				'response' => $decoded,
				'error' => $rs,
			);
		}
	}

	// 설정값을 가져오는 내부 메서드
	private function getConfigValue($key)
	{
		if (is_array($this->config) && isset($this->config[$key])) {
			if ($key === 'enabled') {
				return $this->config[$key];
			}
			return (string)$this->config[$key];
		}
		if ($key === 'enabled') {
			return true;
		}
		return '';
	}
}
