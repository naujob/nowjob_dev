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

    /** @var int 이번 실행에서 Google에 보낸 요청 횟수 (로그용) */
    public $request_count = 0;

    /** @var int 이번 실행에서 Google에 보낸 글자 수 (로그용) */
    public $sent_chars = 0;

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

        // 1) 긴 글은 조각으로 나눈다 (조각 키 = "원래키#번호", 원문을 그대로 이어 붙이면 원래 글이 된다)
        $parts_map = array(); // 원래키 => 조각 키 목록
        $flat = array();      // 조각 키 => 조각 원문
        foreach ($texts as $key => $text) {
            $pieces = $this->splitText($text, $format);
            $parts_map[$key] = array();
            foreach ($pieces as $i => $piece) {
                $flat_key = $key . '#' . $i;
                $flat[$flat_key] = $piece;
                $parts_map[$key][] = $flat_key;
            }
        }

        // 2) 조각을 한도 안에서 묶어서 호출. 한 번이라도 실패하면 중단
        $part_result = array();
        $chunk = array();
        $chunk_chars = 0;
        $err = null;

        foreach ($flat as $flat_key => $piece) {
            $len = mb_strlen($piece, 'UTF-8');
            // 현재 묶음이 한도를 넘으면 먼저 보낸다
            if (!empty($chunk) && ($chunk_chars + $len > self::MAX_CHARS_PER_REQUEST || count($chunk) >= self::MAX_SEGMENTS_PER_REQUEST)) {
                $res = $this->requestChunk($chunk, $format, $target);
                if (!$res['ok']) {
                    $err = $res;
                    break;
                }
                $part_result = $part_result + $res['translations'];
                $chunk = array();
                $chunk_chars = 0;
            }
            $chunk[$flat_key] = $piece;
            $chunk_chars += $len;
        }
        if ($err === null && !empty($chunk)) {
            $res = $this->requestChunk($chunk, $format, $target);
            if (!$res['ok']) {
                $err = $res;
            } else {
                $part_result = $part_result + $res['translations'];
            }
        }

        // 3) 조각을 원래 순서대로 이어 붙인다. 조각 중 하나라도 없으면 그 글은 제외(반쪽 번역 저장 방지)
        $result = array();
        foreach ($parts_map as $key => $flat_keys) {
            $joined = '';
            $complete = true;
            foreach ($flat_keys as $flat_key) {
                if (!isset($part_result[$flat_key])) {
                    $complete = false;
                    break;
                }
                $joined .= $part_result[$flat_key];
            }
            if ($complete) {
                $result[$key] = $joined;
            }
        }

        if ($err !== null) {
            return array('ok' => false, 'translations' => $result, 'error' => $err['error'], 'rate_limited' => !empty($err['rate_limited']));
        }
        return array('ok' => true, 'translations' => $result, 'error' => null);
    }

    /**
     * 긴 글을 MAX_CHARS_PER_REQUEST 이하의 조각으로 나눈다
     * - html: 문단/줄바꿈 태그 뒤에서 자른다 (태그가 중간에서 잘리지 않게)
     * - text: 줄바꿈이나 문장 끝(. 。) 뒤에서 자른다
     * 조각을 순서대로 이어 붙이면 원래 글과 똑같다
     */
    private function splitText($text, $format)
    {
        $max = self::MAX_CHARS_PER_REQUEST;
        if ($format === 'html') {
            $units = preg_split('/(?<=<\/p>)|(?<=<\/div>)|(?<=<\/li>)|(?<=<br>)|(?<=<br\/>)|(?<=<br \/>)/iu', $text, -1, PREG_SPLIT_NO_EMPTY);
        } else {
            $units = preg_split('/(?<=\n)|(?<=。)|(?<=\.\s)/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        }
        if ($units === false || empty($units)) {
            $units = array($text);
        }

        $pieces = array();
        $cur = '';
        foreach ($units as $unit) {
            // 한 덩어리가 한도보다 길면 한도 길이로 강제로 자른다
            while (mb_strlen($unit, 'UTF-8') > $max) {
                if ($cur !== '') {
                    $pieces[] = $cur;
                    $cur = '';
                }
                $pieces[] = mb_substr($unit, 0, $max, 'UTF-8');
                $unit = mb_substr($unit, $max, null, 'UTF-8');
            }
            // 현재 조각에 더하면 한도를 넘으면 조각을 끊는다
            if ($cur !== '' && mb_strlen($cur . $unit, 'UTF-8') > $max) {
                $pieces[] = $cur;
                $cur = '';
            }
            $cur .= $unit;
        }
        if ($cur !== '') {
            $pieces[] = $cur;
        }
        return $pieces;
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

        // 요청 사이에 1초 쉬어서 짧은 시간에 몰리지 않게 한다 (첫 요청은 대기하지 않음)
        if ($this->request_count > 0) {
            usleep(1000000);
        }

        // 로그용 집계 (글자 수만 센다. 원문은 저장하지 않는다)
        $this->request_count++;
        foreach ($chunk as $text) {
            $this->sent_chars += mb_strlen($text, 'UTF-8');
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
            // 네트워크/DNS 오류도 일시적이므로 rate_limited(=일시 오류)로 표시해 재시도 횟수를 올리지 않는다
            return array('ok' => false, 'translations' => array(), 'error' => 'curl 오류: ' . $curl_err, 'rate_limited' => true);
        }
        $json = json_decode($response, true);
        if ($http_code !== 200 || !isset($json['data']['translations']) || !is_array($json['data']['translations'])) {
            // 응답 본문에는 원문이 없고 오류 메시지만 있으므로 메시지만 반환
            $msg = isset($json['error']['message']) ? $json['error']['message'] : ('HTTP ' . $http_code);
            // 분당 호출/글자 수 한도 초과는 일시적 오류 (재시도 횟수를 올리지 않도록 호출부에 알려 준다)
            $is_rate = ($http_code == 429) || (stripos($msg, 'rate limit') !== false) || (stripos($msg, 'quota') !== false);
            return array('ok' => false, 'translations' => array(), 'error' => 'Google 번역 오류: ' . $msg, 'rate_limited' => $is_rate);
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
