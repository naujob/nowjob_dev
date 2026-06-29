<?php

// CodeIgniter 로더 규칙에 따라 파일명과 클래스명을 맞춘 라이브러리
// 파일: application/common/libraries/PpurioClient.php
// 클래스: CI_PpurioClient

class CI_PpurioClient
{
    /** @var array|null */
    private $config;

    public function __construct()
    {
        // CodeIgniter 환경에서 config_item 으로 시도, 없으면 파일에서 직접 로드
        if (function_exists('config_item')) {
            $cfg = config_item('ppurio'); //$config['ppurio'] = '뿌리오 API 설정 ; application\common\config\ppurio.php
            if (is_array($cfg)) {
                $this->config = $cfg;
                return;
            }
        }

        // application/common/config/ppurio.php 직접 로드
        $configPath = (defined('SHAREDPATH') ? SHAREDPATH : APPPATH . 'common/') . 'config/ppurio.php';
        if (file_exists($configPath)) {
            $config = array();
            include $configPath;
            if (isset($config['ppurio']) && is_array($config['ppurio'])) {
                $this->config = $config['ppurio'];
                return;
            }
        }
    }

    /**
     * 뿌리오 액세스 토큰 발급 요청
     * @return array 결과 배열: ok(bool), access_token(string|null), expires_in(int|null), error(string|null)
     */
    public function requestAccessToken()
    {
        //error_log('[PPURIO] ========== 토큰 발급 시작 ==========');

        // 설정 검증
        $tokenUrl = $this->getConfigValue('token_url');
        $account = $this->getConfigValue('account');
        $accessKey = $this->getConfigValue('access_key');

        //error_log('[PPURIO][STEP1] 설정 확인 - token_url=' . $tokenUrl . ', account=' . $account . ', access_key=' . (empty($accessKey) ? 'EMPTY' : substr($accessKey, 0, 6) . '...' . substr($accessKey, -4)));

        if ($tokenUrl === '' || $account === '' || $accessKey === '') {
            //error_log('[PPURIO][STEP1-ERROR] 설정 누락 - token_url=' . $tokenUrl . ', account=' . $account . ', access_key=' . (empty($accessKey) ? 'EMPTY' : 'SET'));
            return array(
                'ok' => false,
                'access_token' => null,
                'expires_in' => null,
                'error' => 'ppurio 설정 누락(token_url/account/access_key)'
            );
        }

        // 뿌리오 API 문서 기준: POST body 없이 Basic Auth 헤더만 사용
        //error_log('[PPURIO][STEP2] 토큰 요청 준비 - URL=' . $tokenUrl . ', account=' . $account);

        $ch = curl_init();
        if ($ch === false) {
            return array(
                'ok' => false,
                'access_token' => null,
                'expires_in' => null,
                'error' => 'cURL 초기화 실패'
            );
        }

        // HTTP Basic Authentication 헤더 사용
        // 뿌리오 API 문서 기준: 계정:뿌리오 개발 인증키를 Base64 인코딩
        // 헤더 형식: "Basic " + base64_encode("account:access_key")
        $authString = $account . ':' . $accessKey;
        $authBase64 = base64_encode($authString);
        $authHeader = 'Basic ' . $authBase64;

        //error_log('[PPURIO][STEP2] Basic Auth 헤더 생성 - account=' . $account . ', auth_string_length=' . strlen($authString) . ', auth_header=' . substr($authHeader, 0, 25) . '...');

        curl_setopt_array($ch, array(
            CURLOPT_URL => $tokenUrl,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $authHeader,
            ),
            CURLOPT_POSTFIELDS => '', // 빈 POST body (nginx 400 오류 방지)
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ));

        $responseBody = curl_exec($ch);
        if ($responseBody === false) {
            $err = curl_error($ch);
            curl_close($ch);
            //error_log('[PPURIO][STEP3-ERROR] 토큰 요청 cURL 실패 - error=' . $err);
            return array(
                'ok' => false,
                'access_token' => null,
                'expires_in' => null,
                'error' => '요청 실패: ' . $err,
            );
        }

        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        //error_log('[PPURIO][STEP3] 토큰 응답 수신 - HTTP=' . $httpStatus . ', body_length=' . strlen($responseBody));

        if ($httpStatus < 200 || $httpStatus >= 300) {
            //error_log('[PPURIO][STEP3-ERROR] 토큰 HTTP 오류 - status=' . $httpStatus . ', body=' . substr($responseBody, 0, 200));
            $decoded = json_decode($responseBody, true);
            return array(
                'ok' => false,
                'access_token' => null,
                'expires_in' => null,
                'error' => 'HTTP 오류: ' . $httpStatus . ' ' . ($decoded !== null ? json_encode($decoded) : $responseBody),
            );
        }

        $decoded = json_decode($responseBody, true);
        if (!is_array($decoded)) {
            //error_log('[PPURIO][STEP3-ERROR] 토큰 응답 JSON 파싱 실패 - body=' . substr($responseBody, 0, 200));
            return array(
                'ok' => false,
                'access_token' => null,
                'expires_in' => null,
                'error' => '응답 파싱 실패(JSON 아님)',
            );
        }

        $token = null;
        if (isset($decoded['access_token']) && $decoded['access_token'] !== '') {
            $token = $decoded['access_token'];
        } elseif (isset($decoded['token']) && $decoded['token'] !== '') {
            $token = $decoded['token'];
        }

        if ($token === null) {
            //error_log('[PPURIO][STEP3-ERROR] 토큰 응답에 access_token 없음 - decoded=' . json_encode($decoded, JSON_UNESCAPED_UNICODE));
            return array(
                'ok' => false,
                'access_token' => null,
                'expires_in' => null,
                'error' => 'access_token 누락',
            );
        }

        $expiresIn = null;
        if (isset($decoded['expires_in'])) {
            $expiresIn = $decoded['expires_in'];
        }

        //error_log('[PPURIO][STEP3-SUCCESS] 토큰 발급 성공 - token_prefix=' . substr($token, 0, 12) . ', expires_in=' . $expiresIn);

        return array(
            'ok' => true,
            'access_token' => $token,
            'expires_in' => $expiresIn,
            'error' => null,
        );
    }
    // 설정값을 안전하게 가져오는 헬퍼 메서드
    private function getConfigValue($key)
    {
        if (is_array($this->config) && isset($this->config[$key])) {
            // enabled 같은 boolean 값은 그대로 반환 (문자열로 변환하지 않음)
            if ($key === 'enabled') {
                return $this->config[$key];
            }
            return (string)$this->config[$key];
        }
        // enabled 키가 없으면 기본값 true 반환 (하위 호환성)
        if ($key === 'enabled') {
            return true;
        }
        return '';
    }

    // 용도별 템플릿 코드 조회
    private function getTemplateCode($type)
    {
        if (is_array($this->config) && isset($this->config['template_codes']) && isset($this->config['template_codes'][$type])) {
            return $this->config['template_codes'][$type];
        }
        return '';
    }

    /**
     * 카카오(알림톡) 발송 요청
     * @param string $recipientPhone 수신자 휴대폰 번호
     * @param string $templateCode 템플릿 코드
     * @param array $templateVariables 템플릿 변수 (키-값)
     * @param array $options 추가 옵션 (senderProfile, messageType 등 오버라이드)
     * @return array ok(bool), response(mixed), error(string|null)
     */
    public function sendMessage($recipientPhone, $templateCode, $templateVariables, $options = array())
    {
        // 뿌리오 기능 비활성화 체크
        $enabled = $this->getConfigValue('enabled');
        if ($enabled === false || $enabled === 0 || $enabled === 'false' || $enabled === '0') {
            //error_log('[PPURIO] 뿌리오 기능이 비활성화되어 있음 - 발송하지 않음');
            return array(
                'ok' => false,
                'response' => null,
                'error' => '뿌리오 기능이 비활성화되어 있습니다'
            );
        }

        //error_log('[PPURIO] ========== 알림톡 발송 시작 ==========');
        //error_log('[PPURIO][STEP4] 파라미터 확인 - to=' . $recipientPhone . ', template=' . $templateCode . ', variables=' . json_encode($templateVariables, JSON_UNESCAPED_UNICODE));

        // 필수 설정 로드
        $account = $this->getConfigValue('account');
        $kakaoUrl = $this->getConfigValue('kakao_send_url');
        //error_log('[PPURIO][STEP4] 설정 확인 - account=' . $account . ', kakao_url=' . $kakaoUrl);

        if ($account === '' || $kakaoUrl === '') {
            //error_log('[PPURIO][STEP4-ERROR] 설정 누락 - account=' . $account . ', kakao_url=' . $kakaoUrl);
            return array(
                'ok' => false,
                'response' => null,
                'error' => 'ppurio 설정 누락(account/kakao_send_url)'
            );
        }

        // 액세스 토큰 발급
        //error_log('[PPURIO][STEP5] 토큰 발급 요청 시작');
        $tokenRes = $this->requestAccessToken();
        if (!(isset($tokenRes['ok']) && $tokenRes['ok'] === true)) {
            //error_log('[PPURIO][STEP5-ERROR] 토큰 발급 실패 - error=' . (isset($tokenRes['error']) ? $tokenRes['error'] : '원인 불명'));
            return array(
                'ok' => false,
                'response' => null,
                'error' => '토큰 발급 실패: ' . (isset($tokenRes['error']) ? $tokenRes['error'] : '원인 불명')
            );
        }

        $accessToken = isset($tokenRes['access_token']) ? $tokenRes['access_token'] : '';
        if ($accessToken === '') {
            //error_log('[PPURIO][STEP5-ERROR] 토큰 값 누락');
            return array(
                'ok' => false,
                'response' => null,
                'error' => '토큰 값 누락'
            );
        }

        //error_log('[PPURIO][STEP5-SUCCESS] 토큰 발급 완료 - token_prefix=' . substr($accessToken, 0, 12));

        // 기본값 구성
        $messageType = isset($options['messageType']) ? (string)$options['messageType'] : 'ALT';
        // senderProfile은 반드시 "@"로 시작해야 함
        $senderProfile = isset($options['senderProfile']) ? (string)$options['senderProfile'] : '@나우잡';
        // "@"로 시작하지 않으면 "@" 추가
        if (substr($senderProfile, 0, 1) !== '@') {
            $senderProfile = '@' . $senderProfile;
        }
        $refkey = isset($options['refkey']) ? (string)$options['refkey'] : ('nowjob-' . date('YmdHis') . '-' . mt_rand(1000, 9999));
        // duplicateFlag와 isResend는 문자열 "Y" 또는 "N"으로 전송
        $duplicateFlag = isset($options['duplicateFlag']) ? (($options['duplicateFlag'] === true || $options['duplicateFlag'] === 'Y') ? 'Y' : 'N') : 'N';
        $isResend = isset($options['isResend']) ? (($options['isResend'] === true || $options['isResend'] === 'Y') ? 'Y' : 'N') : 'N';

        // 휴대폰번호 정규화: 숫자만 남김
        $toPhone = preg_replace('/[^0-9]/', '', (string)$recipientPhone);
        //error_log('[PPURIO][STEP6] 알림톡 요청 준비 - messageType=' . $messageType . ', senderProfile=' . $senderProfile . ', to=' . $toPhone . ', refkey=' . $refkey);

        // changeWord 구성 (뿌리오 API 문서 기준: changeWord는 var1, var2, var3 등의 키 사용)
        // 템플릿 변수를 var1, var2, var3 형태로 변환
        $changeWord = array();
        if (is_array($templateVariables) && !empty($templateVariables)) {
            // 템플릿 변수를 var1, var2, var3 형태로 변환 (배열 순서대로)
            $varIndex = 1;
            foreach ($templateVariables as $value) {
                if ($varIndex <= 7) { // var1 ~ var7 까지만 지원
                    $changeWord['var' . $varIndex] = (string)$value;
                    $varIndex++;
                }
            }
        }
         //targets은 배열로 구성되어야 하며, 각 target은 to(필수), changeWord(선택), name(선택)을 포함
        // targets 배열 구성 (뿌리오 API 문서 기준: targets 배열 필수)
        // 각 target은 to(필수), changeWord(선택), name(선택) 포함
        $target = array(
            'to' => $toPhone,
        );
        // changeWord가 있으면 추가
        if (!empty($changeWord)) {
            $target['changeWord'] = $changeWord;
        }
        // 수신자 이름(name) 전달 옵션 지원 - 템플릿의 [*이름*] 치환에 사용
        if (isset($options['name']) && trim((string)$options['name']) !== '') {
            $target['name'] = (string)$options['name'];
            //error_log('[PPURIO][STEP6] target name 포함 - name=' . (string)$options['name']);
        }

        $targets = array($target);
        $targetCount = count($targets);

        // 버튼 자동 생성 (targetPath 옵션이 있으면)
        // 카카오 알림톡의 액션 버튼: 웹링크(WL), 앱링크(AL)
        $buttons = array();
        if (isset($options['targetPath']) && trim((string)$options['targetPath']) !== '') {
            $targetPath = (string)$options['targetPath'];
            $baseUrl = 'https://nowjob.co.kr';

            // 완전한 URL 구성
            $fullUrl = $baseUrl . $targetPath;

            // 앱 링크 버튼 (AL: App Link)
            // 도메인을 nowjob:// 스킴으로 변환 (더블 슬래시 표준)
            $appSchemeUrl = str_replace('https://nowjob.co.kr/', 'nowjob://', $fullUrl);
            $appSchemeUrl = str_replace('https://dev.nowjob.co.kr/', 'nowjob://', $appSchemeUrl);
            $appSchemeUrl = str_replace('http://localhost/', 'nowjob://', $appSchemeUrl);

            // 앱 링크 버튼 (AL: App Link)
            // 뿌리오 API가 요구하는 정확한 키로 구성: scheme_android, scheme_ios, url_mobile
            $buttons[] = array(
                'name' => '앱에서 보기',
                'type' => 'AL',
                'scheme_android' => $appSchemeUrl,  // 안드로이드 앱 스킴
                'scheme_ios'     => $appSchemeUrl,  // iOS 앱 스킴
                'url_mobile'     => $fullUrl        // 앱 미설치 시 웹 폴백 (필수!)
            );

            // 웹 링크 버튼 (WL: Web Link) - 선택사항
            $buttons[] = array(
                'name' => '웹에서 보기',
                'type' => 'WL',
                'url' => $fullUrl
            );

            //error_log('[PPURIO][BUTTON] 버튼 생성 - appScheme=' . $appSchemeUrl . ', webUrl=' . $fullUrl);
        }

        // 요청 본문 구성 (뿌리오 API 문서 기준: targets 배열 구조 사용)
        $payload = array(
            'account' => $account,
            'messageType' => $messageType,
            'senderProfile' => $senderProfile,
            'templateCode' => $templateCode,
            'duplicateFlag' => $duplicateFlag, // "Y" 또는 "N" (중복 발송 허용 여부)
            'targetCount' => $targetCount, // 수신자 목록 수
            'targets' => $targets, // 수신자 목록 배열
            'refKey' => $refkey, // refKey (대문자 K)
            'isResend' => $isResend, // "Y" 또는 "N" (대체 발송 허용 여부)
        );

        // 선택적 파라미터 추가 (필수 아님)
        // sendTime: 예약 발송 시간 (yyyy-MM-ddTHH:mm:ss 형식)
        if (isset($options['sendTime']) && $options['sendTime'] !== '') {
            $payload['sendTime'] = (string)$options['sendTime'];
        }

        // resend: 대체문자 발송 정보 (배열)
        // 주의: 버튼과 대체문자는 동시에 지원되지 않음
        $hasResend = isset($options['resend']) && is_array($options['resend']) && !empty($options['resend']);
        if ($hasResend) {
            $payload['resend'] = $options['resend'];
        }

        // 버튼 추가 (있으면, 단 대체문자가 없을 때만)
        // 뿌리오 정책: 버튼과 대체문자는 동시에 지원되지 않음
        if (!empty($buttons) && !$hasResend) {
            $payload['buttons'] = $buttons;
        }

        $ch = curl_init();
        if ($ch === false) {
            return array(
                'ok' => false,
                'response' => null,
                'error' => 'cURL 초기화 실패'
            );
        }

        //error_log('[PPURIO][STEP6] 알림톡 요청 payload - ' . json_encode($payload, JSON_UNESCAPED_UNICODE));
        if (function_exists('log_message')) {
            log_message('info', '[PPURIO][KAKAO][REQ] ' . json_encode($payload, JSON_UNESCAPED_UNICODE));
        }

        curl_setopt_array($ch, array(
            CURLOPT_URL => $kakaoUrl,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $accessToken,
            ),
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ));

        //error_log('[PPURIO][STEP7] 알림톡 API 요청 전송 - URL=' . $kakaoUrl);
        $responseBody = curl_exec($ch);
        if ($responseBody === false) {
            $err = curl_error($ch);
            curl_close($ch);
            //error_log('[PPURIO][STEP7-ERROR] 알림톡 요청 cURL 실패 - error=' . $err);
            if (function_exists('log_message')) {
                log_message('error', '[PPURIO][KAKAO][REQ-FAIL] ' . json_encode($payload, JSON_UNESCAPED_UNICODE) . ' | ' . $err);
            }
            return array(
                'ok' => false,
                'response' => null,
                'error' => '요청 실패: ' . $err,
            );
        }

        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        //error_log('[PPURIO][STEP7] 알림톡 응답 수신 - HTTP=' . $httpStatus . ', body_length=' . strlen($responseBody) . ', body=' . substr($responseBody, 0, 200));

        $decoded = json_decode($responseBody, true);
        if (function_exists('log_message')) {
            log_message('info', '[PPURIO][KAKAO][RESP] status=' . $httpStatus . ' body=' . $responseBody);
        }
        if ($httpStatus < 200 || $httpStatus >= 300) {
            //error_log('[PPURIO][STEP7-ERROR] 알림톡 HTTP 오류 - status=' . $httpStatus . ', body=' . substr($responseBody, 0, 200));
            return array(
                'ok' => false,
                'response' => ($decoded !== null ? $decoded : $responseBody),
                'error' => 'HTTP 오류: ' . $httpStatus,
            );
        }

        //error_log('[PPURIO][STEP7-SUCCESS] 알림톡 발송 성공 - status=' . $httpStatus . ', refkey=' . $refkey . ', response=' . json_encode($decoded, JSON_UNESCAPED_UNICODE));
        //error_log('[PPURIO] ========== 알림톡 발송 완료 ==========');

        return array(
            'ok' => true,
            'response' => ($decoded !== null ? $decoded : $responseBody),
            'http_status' => $httpStatus,
            'refkey' => $refkey,
            'error' => null,
        );
    }

    /**
     * 기업회원용 알림톡 발송 (템플릿 고정)
     * @param string $recipientPhone 수신자 휴대폰 번호
     * @param array $templateVariables 템플릿 변수 (메시지 본문 내용)
     * @param array $options 추가 옵션
     *        - targetPath: 페이지 경로 (예: /recruit/job_post/46) → 버튼 자동 생성
     *        - name: 수신자 이름 (템플릿에서 [*이름*] 사용 시)
     *        - sendTime: 예약 발송 시간 (yyyy-MM-ddTHH:mm:ss)
     *        - resend: 대체문자 발송 정보
     * 요구사항: messageType=ALH로 강제 지정
     */
    public function sendCorporateAlimtalk($recipientPhone, $templateVariables, $options = array())
    {
        $templateCode = $this->getTemplateCode('corporate');
        // 기본 옵션 병합: messageType 강제 ALH, senderProfile 고정
        $baseOptions = array('messageType' => 'ALH', 'senderProfile' => '@나우잡');
        $finalOptions = is_array($options) ? array_merge($baseOptions, $options) : $baseOptions;
        return $this->sendMessage($recipientPhone, $templateCode, $templateVariables, $finalOptions);
    }

    /**
     * 개인회원용 알림톡 발송 (템플릿 고정)
     * @param string $recipientPhone 수신자 휴대폰 번호
     * @param array $templateVariables 템플릿 변수 (메시지 본문 내용)
     * @param array $options 추가 옵션
     *        - targetPath: 페이지 경로 (예: /recruit/job_post/46) → 버튼 자동 생성
     *        - name: 수신자 이름 (템플릿에서 [*이름*] 사용 시)
     *        - sendTime: 예약 발송 시간 (yyyy-MM-ddTHH:mm:ss)
     *        - resend: 대체문자 발송 정보
     * 요구사항: messageType=ALH로 강제 지정
     */
    public function sendPersonalAlimtalk($recipientPhone, $templateVariables, $options = array())
    {
        $templateCode = $this->getTemplateCode('personal');
        // 기본 옵션 병합: messageType 강제 ALH, senderProfile 고정
        $baseOptions = array('messageType' => 'ALH', 'senderProfile' => '@나우잡');
        $finalOptions = is_array($options) ? array_merge($baseOptions, $options) : $baseOptions;
        return $this->sendMessage($recipientPhone, $templateCode, $templateVariables, $finalOptions);
    }

    /**
     * 미확인 공고 알림톡 발송 (템플릿 고정)
     * @param string $recipientPhone 수신자 휴대폰 번호
     * @param array $templateVariables 템플릿 변수 배열 (메시지 본문 내용)
     *              예: array('배달원', '시급 11,000원', '서울시 강남구') → [*1*], [*2*], [*3*]
     * @param array $options 추가 옵션
     *        - targetPath: 페이지 경로 (예: /recruit/job_post/46) → 버튼 자동 생성
     *        - name: 수신자 이름 (템플릿에서 [*이름*] 사용 시)
     *        - sendTime: 예약 발송 시간 (yyyy-MM-ddTHH:mm:ss)
     *        - resend: 대체문자 발송 정보
     * 요구사항: messageType=ALT로 강제 지정 (기본 알림톡)
     */
    public function sendUnreadNotificationAlimtalk($recipientPhone, $templateVariables, $options = array())
    {
        $templateCode = $this->getTemplateCode('unread_notification');
        // 기본 옵션 병합: messageType 강제 ALT (기본형), senderProfile 고정
        $baseOptions = array('messageType' => 'ALT', 'senderProfile' => '@나우잡');
        $finalOptions = is_array($options) ? array_merge($baseOptions, $options) : $baseOptions;
        return $this->sendMessage($recipientPhone, $templateCode, $templateVariables, $finalOptions);
    }
}
