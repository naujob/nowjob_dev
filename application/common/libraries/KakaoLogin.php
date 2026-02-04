<?php

class KakaoLogin {

	private $auth_url;
	private $api_url;
	private $callback_url;
	private $cliend_id;
	private $state;

    /**
     * 생성자
     *
     * @param string $csrf_token
     */
	public function __construct($api_key="",$csrf_token = "") {
		$ci = function_exists('get_instance') ? get_instance() : null;
		$app_base_url = '';
		if ($ci && isset($ci->config)) {
			$app_base_url = rtrim($ci->config->item('app_base_url') ?: base_url(), '/');
		}
		if ($app_base_url === '') {
			$app_base_url = rtrim((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost'), '/');
		}

		$this->callback_url = $app_base_url . "/member/kakao_login_callback";   // 콜백 URL
		$this->auth_url = "https://kauth.kakao.com";    // 카카오 인증서버
		$this->api_url = "https://kapi.kakao.com";      // 카카오 API 서버
		$this->cliend_id = $api_key;
		$this->state = $csrf_token;
	}

    /**
     * 인가코드를 받기 위한 URL 생성
     *
     * @return string
     */
	public function getAuthorizeUrl() {

		$returnValue = $this->auth_url."/oauth/authorize?";
		$returnValue .= "client_id={$this->cliend_id}";
		$returnValue .= "&redirect_uri=".urlencode($this->callback_url);
		$returnValue .= "&state={$this->state}";
		$returnValue .= "&response_type=code";  //&prompt=login

		return $returnValue;
	}

    /**
     * 인가코드를 이용해 토큰 요청
     *
     * @param string $code
     * @return array
     */
	public function getToken($code) {

        // 토큰 주소
		$url = $this->auth_url . "/oauth/token";

        // 헤더
		$headers = array(
			"Content-type: application/x-www-form-urlencoded;charset=utf-8"
		);

        // 데이터
		$postData = array(
			"grant_type" => "authorization_code",
			"client_id" => $this->cliend_id,
			"redirect_uri" => $this->callback_url,
			"code" => $code,
			"client_secret" => ""
		);

        // API 호출
		$arrResult = $this->call($headers, $url, $postData);

		$returnData = json_decode($arrResult[0], true);

		$retVal["code"] = $arrResult[3];
		$retVal["data"] = $returnData;

		return $retVal;
	}


	/**
	 * Access Token 을 이용해 사용자 정보 가져오기
	 * @param string $accessToken
	 * @return array
	 */
	public function getUserInfo($accessToken) {

		$url = $this->api_url . "/v2/user/me";

		$headers = array(
			"Authorization: Bearer {$accessToken}",
			"Content-type: application/x-www-form-urlencoded;charset=utf-8",
		);

		$arrResult = $this->call($headers, $url, $postData);

		$returnData = json_decode($arrResult[0], true);

		$retVal["code"] = $arrResult[3];
		$retVal["data"] = $returnData;

		return $retVal;
	}


    /**
      * 실제 API 호출 메서드
      *
      * @param array $headers
      * @param string $url
      * @param array $postData
      * @return array
      */
	private function call($headers, $url, $postData = null) {

		try {

			$postDataString = "";
			if(is_array($postData)){
				$postDataString = http_build_query($postData);
			}

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$result[0] = curl_exec($ch);
			$result[1] = curl_errno($ch);
			$result[2] = curl_error($ch);
			$result[3] = curl_getinfo($ch, CURLINFO_HTTP_CODE);


			return $result;
		} catch(Exception $e) {
			echo $e->getMessages();

            return array("false");
		}
	}
}
