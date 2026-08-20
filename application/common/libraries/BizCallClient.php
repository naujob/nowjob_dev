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
    // 050 가상번호와 실제 번호를 연결해 달라고 통신사(델피콤)에 API 요청 보내고, 답장을 받아 검사
	public function setVirtualNumber($vNumber, $realNumber, $memo)
	{
		$enabled = $this->getConfigValue('enabled');
		if ($enabled === false || $enabled === 0 || $enabled === 'false' || $enabled === '0') {
			if (function_exists('log_message')) {
				log_message('info', '[BIZCALL] 기능이 비활성화되어 있음 - 발송하지 않음');
			}
			return array(
				'ok' => false,
				'response' => null,
				'error' => '비즈콜 기능이 비활성화되어 있습니다'
			);
		}

		$iid = $this->getConfigValue('iid');
		$crId = $this->getConfigValue('cr_id');
		$ifId = $this->getConfigValue('if_id');
		$apiUrl = $this->getConfigValue('api_url');

		if ($iid === '' || $crId === '' || $ifId === '' || $apiUrl === '') {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL] 설정 누락 - iid=' . $iid . ', cr_id=' . $crId . ', if_id=' . $ifId . ', api_url=' . $apiUrl);
			}
			return array(
				'ok' => false,
				'response' => null,
				'error' => '비즈콜 설정 누락(iid/cr_id/if_id/api_url)'
			);
		}

		$realNumber = preg_replace('/[^0-9]/', '', (string)$realNumber);
		$auth = base64_encode(md5($iid . $vNumber));

		$postFields = array(
			'iid' => $iid,
			'vn' => $vNumber,
			'rn' => $realNumber,
			'cr_id' => $crId,
			'if_id' => $ifId,
			'memo' => (string)$memo,
			'auth' => $auth
		);

		$ch = curl_init();
		if ($ch === false) {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL] cURL 초기화 실패');
			}
			return array(
				'ok' => false,
				'response' => null,
				'error' => 'cURL 초기화 실패'
			);
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
			log_message('info', '[BIZCALL][REQ] vn=' . $vNumber . ', rn=' . $realNumber . ', memo=' . (string)$memo);
		}

		$responseBody = curl_exec($ch);
		if ($responseBody === false) {
			$err = curl_error($ch);
			curl_close($ch);
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][REQ-FAIL] ' . $err);
			}
			return array(
				'ok' => false,
				'response' => null,
				'error' => '요청 실패: ' . $err,
			);
		}

		$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$decoded = json_decode($responseBody, true);
		if (function_exists('log_message')) {
			log_message('info', '[BIZCALL][RESP] status=' . $httpStatus . ' body=' . $responseBody);
		}

		if ($httpStatus < 200 || $httpStatus >= 300) {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][RESP-ERROR] HTTP ' . $httpStatus);
			}
			return array(
				'ok' => false,
				'response' => ($decoded !== null ? $decoded : $responseBody),
				'error' => 'HTTP 오류: ' . $httpStatus,
			);
		}

		if (!is_array($decoded)) {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][RESP-ERROR] JSON 파싱 실패 - body=' . substr($responseBody, 0, 200));
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
				log_message('info', '[BIZCALL][SUCCESS] vn=' . $vNumber . ' 매핑 완료');
			}
			return array(
				'ok' => true,
				'response' => $decoded,
				'error' => null,
			);
		} else {
			$rs = isset($decoded['rs']) ? $decoded['rs'] : '알 수 없는 오류';
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][FAIL] rt=' . $rt . ', rs=' . $rs);
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
