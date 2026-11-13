<?php

/**
 * Google Cloud Translation API (v2 Basic) 클라이언트
 * 파일: application/common/libraries/GoogleTranslateClient.php
 *
 * 설정: st_setting 테이블 (카카오 인증키와 같은 방식)
 *   st_group='구글번역', st_key='API키'   → st_value = 발급받은 API 키
 *   st_group='구글번역', st_key='사용여부' → st_value = 'Y' | 'N'
 *   등록 예:
 *     INSERT INTO st_setting (st_group, st_key, st_value) VALUES
 *       ('구글번역', 'API키', '여기에_발급받은_API_키'),
 *       ('구글번역', '사용여부', 'Y');
 *
 * 보안: 원문/번역문(이력서 개인정보 포함 가능)과 API 키는 로그에 남기지 않는다.
 */
class GoogleTranslateClient
{
    const ENDPOINT = 'https://translation.googleapis.com/language/translate/v2';

    // 요청 1회당 권장 한도 (Google 권장: 문자 5,000자 / 세그먼트 128개)
    const MAX_CHARS_PER_REQUEST = 5000;
    const MAX_SEGMENTS_PER_REQUEST = 50;

    /** @var array 설정값 */
    private $config = array();

    /** @var int 연결 타임아웃(초) */
    private $connect_timeout = 5;

    /** @var int 전체 타임아웃(초) — cron 기본 30초, 저장 직후 즉시 번역은 짧게 */
    private $timeout = 30;

    /**
     * 타임아웃 설정 (저장 직후 즉시 번역처럼 사용자가 응답을 기다리는 경우 짧게)
     * @param int $connect_timeout 연결 타임아웃(초)
     * @param int $timeout         전체 타임아웃(초)
     */
    public function setTimeout($connect_timeout, $timeout)
    {
        $this->connect_timeout = max(1, (int)$connect_timeout);
        $this->timeout = max(1, (int)$timeout);
    }

    public function __construct()
    {
        // st_setting 테이블에서 조회 (카카오 인증키와 같은 방식). getSettingInfo()는 common/helpers/data_helper.php
        // 의 autoload 헬퍼이며 내부적으로 settingmodel을 로드해 st_group 기준 st_key=>st_value 배열을 돌려준다.
        $setting = array();
        if (function_exists('getSettingInfo')) {
            $setting = getSettingInfo(array('st_group' => '구글번역'));
        }
        $this->config['enabled'] = (isset($setting['사용여부']) && $setting['사용여부'] === 'Y');
        $this->config['api_key'] = isset($setting['API키']) ? $setting['API키'] : '';
    }

    /**
     * 사용 가능 여부 (enabled + api_key 설정)
     * @return bool
     */
    public function isEnabled()
    {
        return !empty($this->config['enabled']) && !empty($this->config['api_key']);
    }

    /**
     * 여러 문장을 한 번에 번역 (같은 format 끼리만)
     * @param array  $texts  원문 배열 (키 유지)
     * @param string $format 'text' | 'html'
     * @param string $target 대상 언어 (기본 zh-CN)
     * @return array ok(bool), translations(array 키=>번역문), error(string|null)
     */
    public function translateBatch($texts, $format = 'text', $target = 'zh-CN')
    {
        if (!$this->isEnabled()) {
            return array('ok' => false, 'translations' => array(), 'error' => 'google_translate 설정 없음 또는 비활성');
        }
        $format = ($format === 'html') ? 'html' : 'text';

        $result = array();
        $chunk = array();
        $chunk_chars = 0;

        foreach ($texts as $key => $text) {
            $len = mb_strlen($text, 'UTF-8');
            // 현재 묶음이 한도를 넘으면 먼저 보낸다
            if (!empty($chunk) && ($chunk_chars + $len > self::MAX_CHARS_PER_REQUEST || count($chunk) >= self::MAX_SEGMENTS_PER_REQUEST)) {
                $res = $this->requestChunk($chunk, $format, $target);
                if (!$res['ok']) {
                    return array('ok' => false, 'translations' => $result, 'error' => $res['error']);
                }
                $result = $result + $res['translations'];
                $chunk = array();
                $chunk_chars = 0;
            }
            $chunk[$key] = $text;
            $chunk_chars += $len;
        }
        if (!empty($chunk)) {
            $res = $this->requestChunk($chunk, $format, $target);
            if (!$res['ok']) {
                return array('ok' => false, 'translations' => $result, 'error' => $res['error']);
            }
            $result = $result + $res['translations'];
        }
        return array('ok' => true, 'translations' => $result, 'error' => null);
    }

    /**
     * API 1회 호출
     * @param array  $chunk  키=>원문
     * @param string $format
     * @param string $target
     * @return array ok, translations, error
     */
    private function requestChunk($chunk, $format, $target)
    {
        // q 파라미터를 반복해서 보낸다 (q=..&q=..). http_build_query 는 q[0]= 형태라 직접 조립
        $body = 'source=ko&target=' . rawurlencode($target) . '&format=' . $format;
        foreach ($chunk as $text) {
            $body .= '&q=' . rawurlencode($text);
        }

        $ch = curl_init(self::ENDPOINT . '?key=' . rawurlencode($this->config['api_key']));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        $response = curl_exec($ch);
        $http_code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_err = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return array('ok' => false, 'translations' => array(), 'error' => 'curl 오류: ' . $curl_err);
        }
        $json = json_decode($response, true);
        if ($http_code !== 200 || !isset($json['data']['translations']) || !is_array($json['data']['translations'])) {
            // 응답 본문에는 원문이 없고 오류 메시지만 있으므로 메시지만 반환
            $msg = isset($json['error']['message']) ? $json['error']['message'] : ('HTTP ' . $http_code);
            return array('ok' => false, 'translations' => array(), 'error' => 'Google 번역 오류: ' . $msg);
        }

        $translations = array();
        $keys = array_keys($chunk);
        foreach ($json['data']['translations'] as $i => $row) {
            if (!isset($keys[$i]) || !isset($row['translatedText'])) {
                continue;
            }
            $text = $row['translatedText'];
            // format=text 도 일부 문자(&#39; 등)가 엔티티로 올 수 있어 일반 텍스트로 되돌린다
            if ($format === 'text') {
                $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
            }
            $translations[$keys[$i]] = $text;
        }
        if (count($translations) !== count($chunk)) {
            return array('ok' => false, 'translations' => array(), 'error' => 'Google 번역 응답 개수 불일치');
        }
        return array('ok' => true, 'translations' => $translations, 'error' => null);
    }
}
