<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 웹(PC) 다국어 헬퍼
 *
 * 언어 결정 규칙
 *  - 페이지 주소가 /zh/... 이면 중국어 (index.php 가 /zh 를 떼고 SITE_LANG_PATH='zh' 로 표시)
 *  - 주소에 언어가 없는 AJAX·POST·*_proc 요청은 site_lang 쿠키를 따른다
 *  - 그 외 페이지 요청은 한국어 (쿠키가 zh 이면 index.php 가 302 로 /zh 주소로 리다이렉트, 컨트롤러 실행 전)
 *  - 모바일(앱 WebView 포함)은 주소를 바꾸지 않고 site_lang 쿠키만 따른다 (쿠키가 zh 이면 중국어, /zh 리다이렉트·/zh 링크 없음)
 *
 * 정적 문구 사전: application/common/language/zh/ui_dict.php
 *  (tools/i18n/xlsx_to_dict.py 가 엑셀 번역표에서 생성 — 직접 수정 금지)
 */

if (!function_exists('site_lang_is_mobile')) {
    /**
     * 모바일 기기(앱 WebView·모바일 브라우저) 요청인지 여부
     * 모바일은 /zh 주소를 쓰지 않고 쿠키로만 언어를 정하므로 여러 곳에서 구분에 사용한다.
     * @return bool
     */
    function site_lang_is_mobile()
    {
        // CLI(cron)에는 apache_request_headers()가 없어 getDeviceType()을 부를 수 없다
        if (!function_exists('apache_request_headers') || !function_exists('getDeviceType')) {
            return false;
        }
        return getDeviceType() !== 'web';
    }
}

if (!function_exists('site_lang')) {
    /**
     * 현재 요청의 표시 언어
     * @return string 'zh' | 'ko'
     */
    function site_lang()
    {
        static $lang = null;
        if ($lang !== null) {
            return $lang;
        }

        $lang = 'ko';

        // CLI(cron) 요청은 항상 한국어 (getDeviceType()이 쓰는 apache_request_headers()가 CLI 에는 없음)
        if (!function_exists('apache_request_headers')) {
            return $lang;
        }

        // 모바일(앱 WebView 포함)은 주소를 바꾸지 않고 site_lang 쿠키로만 언어를 정한다
        // (/zh 링크로 들어온 경우는 index.php 가 SITE_LANG_PATH='zh' + 쿠키 zh 로 맞춰 둔다)
        if (site_lang_is_mobile()) {
            $is_zh_path = (defined('SITE_LANG_PATH') && SITE_LANG_PATH === 'zh');
            $is_zh_cookie = (isset($_COOKIE['site_lang']) && $_COOKIE['site_lang'] === 'zh');
            $lang = ($is_zh_path || $is_zh_cookie) ? 'zh' : 'ko';
            return $lang;
        }

        if (defined('SITE_LANG_PATH') && SITE_LANG_PATH === 'zh') {
            $lang = 'zh';
            return $lang;
        }

        if (site_lang_is_data_request()) {
            $lang = (isset($_COOKIE['site_lang']) && $_COOKIE['site_lang'] === 'zh') ? 'zh' : 'ko';
        }
        return $lang;
    }
}

if (!function_exists('site_lang_is_data_request')) {
    /**
     * 화면 이동이 아닌 데이터 요청(AJAX·POST·*_proc)인지 여부
     * 이런 요청은 주소에 /zh 가 없으므로 쿠키로 언어를 판단한다.
     * @return bool
     * 데이터 요청인지 검사
     */
    function site_lang_is_data_request()
    {
        $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) // 자바스크립트가 화면 새로고침 없이 서버에 몰래 데이터를 요청한 것인가?
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        $is_post = isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'; //사용자가 글쓰기 버튼 등을 눌러서 서버로 데이터를 훅 던져준 것인가?
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''; //현재 요청 주소. /zh/... 이면 화면 이동이므로 데이터 요청이 아니다.
        $is_proc = (strpos($uri, '_proc/') !== false); //주소에 _proc/라는 글자가 포함되어 있는가?
        return ($is_ajax || $is_post || $is_proc);
    }
}

if (!function_exists('site_lang_dict')) {
    /**
     * 정적 문구 사전 (한국어 원문 => 중국어). 요청당 1회만 읽는다.
     * @return array
     */
    function site_lang_dict()
    {
        static $dict = null;
        if ($dict === null) {
            $dict = array();
            $file = SHAREDPATH . 'language/zh/ui_dict.php';
            if (file_exists($file)) {
                $loaded = include $file;
                if (is_array($loaded)) {
                    $dict = $loaded;
                }
            }
        }
        return $dict;
    }
}

if (!function_exists('t')) {
    /**
     * 화면 문구 번역. 중국어 페이지면 사전에서 찾아 반환, 없으면 원문 그대로.
     * 주의: <option value> 처럼 서버로 전송·검색에 쓰이는 값에는 사용하지 않는다(표시 라벨에만 사용).
     * @param string $ko 한국어 원문
     * @return string
     */
    function t($ko)
    {
        if (site_lang() !== 'zh') {
            return $ko;
        }
        $dict = site_lang_dict();
        $key = trim($ko);
        return isset($dict[$key]) ? $dict[$key] : $ko;
    }
}

if (!function_exists('t_point_desc')) {
    /**
     * 포인트 사용내역 설명(pl_desc) 번역
     * "고정문구(내용)" 형식에서 고정문구와 "차감"만 번역하고, 숫자(300P)와 상품명은 원문 유지
     * @param string $desc pl_desc 원문 (예: "채용공고등록(300P차감)", "광고구매(상품명)", "123 결제취소")
     * @return string 화면 표시용 문구
     */
    function t_point_desc($desc)
    {
        if (preg_match('/^(.+) 결제취소$/u', $desc, $m)) {
            return $m[1] . ' ' . t('결제취소');
        }
        if (preg_match('/^([^(]+)\((.*)\)$/u', $desc, $m) && t_point_balanced($m[2])) {
            $inner = (strpos($m[2], '(') !== false) ? t_point_desc($m[2]) : t_point_desc_inner($m[2]);
            return t($m[1]) . '(' . $inner . ')';
        }
        if (preg_match('/^([^(]+)((?:\([^)]*\))+)$/u', $desc, $m)) {
            $out = t($m[1]);
            preg_match_all('/\(([^)]*)\)/u', $m[2], $groups);
            foreach ($groups[1] as $inner) {
                $out .= '(' . t_point_desc_inner($inner) . ')';
            }
            return $out;
        }
        return t($desc);
    }
}

if (!function_exists('t_point_balanced')) {
    /**
     * 괄호가 순서대로 짝이 맞는지 확인 (t_point_desc()의 중첩 괄호 판정용)
     * @param string $s
     * @return bool
     */
    function t_point_balanced($s)
    {
        $depth = 0;
        $len = strlen($s);
        for ($i = 0; $i < $len; $i++) {
            if ($s[$i] === '(') {
                $depth++;
            } elseif ($s[$i] === ')') {
                $depth--;
                if ($depth < 0) {
                    return false;
                }
            }
        }
        return $depth === 0;
    }
}

if (!function_exists('t_point_desc_inner')) {
    /**
     * t_point_desc()의 괄호 안 조각 번역 (예: "3,000P차감" → "3,000P扣除", "4시간" → "4小时")
     * @param string $inner 괄호 안 원문
     * @return string
     */
    function t_point_desc_inner($inner)
    {
        if (preg_match('/^([\d,]+P)차감$/u', $inner, $n)) {
            return $n[1] . t('차감');
        }
        if (preg_match('/^[\d,]+P$/u', $inner)) {
            return $inner;
        }
        if (preg_match('/^(\d+)(일|시간)$/u', $inner, $n)) {
            return $n[1] . t($n[2] === '일' ? '기간(일)' : '시간');
        }
        return t($inner);
    }
}

if (!function_exists('t_list')) {
    /**
     * 쉼표로 구분된 코드값 표시 문구 번역 (예: "내국인,외국인" → "韩国籍,外国籍")
     * 각 항목을 t()로 번역하고 같은 구분자로 다시 잇는다.
     * @param string $text 쉼표 구분 문자열
     * @param string $sep 구분자
     * @return string
     */
    function t_list($text, $sep = ',')
    {
        if (site_lang() !== 'zh' || $text === '' || $text === null) {
            return $text;
        }
        $parts = explode($sep, $text);
        foreach ($parts as $i => $part) {
            $parts[$i] = t(trim($part));
        }
        return implode($sep, $parts);
    }
}

if (!function_exists('translate_rows')) {
    /**
     * 회원 작성 데이터(공고·이력서) 자동번역 적용 — 중국어 페이지에서만 동작, 한국어면 아무것도 안 함
     * 번역 메모리(st_translation)에서 완료된 번역으로 필드 값을 바꾸고, 없으면 원문 유지 + 번역 대기 등록.
     * @param array $rows   행 배열
     * @param array $fields 필드명 => 'T'(텍스트) | 'H'(HTML)
     * @return array 번역이 적용된 행 배열
     * 
     */
    function translate_rows($rows, $fields)
    {
        if (site_lang() !== 'zh' || !is_array($rows) || empty($rows)) {
            return $rows;
        }
        $CI =& get_instance();
        if (!isset($CI->translation_model)) {
            $CI->load->model('translationmodel', 'translation_model');
        }
        // 번역 테이블이 아직 없거나 조회 오류여도 화면은 원문으로 정상 표시되게 한다
        $db_debug = $CI->db->db_debug;
        $CI->db->db_debug = false;
        $CI->translation_model->overlayRows($rows, $fields);
        $CI->db->db_debug = $db_debug;
        return $rows;
    }
}

if (!function_exists('translate_row')) {
    /**
     * translate_rows() 의 단일 행 버전
     * @param array $row
     * @param array $fields
     * @return array
     */
    function translate_row($row, $fields)
    {
        if (!is_array($row) || empty($row)) {
            return $row;
        }
        $rows = translate_rows(array($row), $fields);
        return $rows[0];
    }
}

if (!function_exists('translate_rows_now')) {
    /**
     * translate_rows() + 아직 번역이 없는 칸만 바로 번역 (중국어 페이지에서만 동작)
     * 처음 보는 글은 translate_rows()가 대기열에만 넣어 cron 전까지 한국어로 남으므로,
     * 그 칸에 한해 translateNow()로 즉시 번역한 뒤 다시 치환한다. 번역된 글은 st_translation 에 남아 다음부터는 바로 나온다.
     * 주의: 첫 조회 시 Google 응답을 최대 5초까지 기다릴 수 있다 (translate_after_response 와 같은 타임아웃).
     * @param array $rows   행 배열
     * @param array $fields 필드명 => 'T'(텍스트)
     * @return array 번역이 적용된 행 배열
     */
    function translate_rows_now($rows, $fields)
    {
        if (site_lang() !== 'zh' || !is_array($rows) || empty($rows)) {
            return $rows;
        }
        $rows = translate_rows($rows, $fields);

        // 치환 후에도 한글이 남은 칸 = 아직 번역되지 않은 원문
        $CI =& get_instance();
        if (!isset($CI->translation_model)) {
            $CI->load->model('translationmodel', 'translation_model');
        }
        $pending = array();
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            foreach ($fields as $field => $format) {
                if (isset($row[$field]) && $CI->translation_model->isTranslatable($row[$field])) {
                    $pending[] = md5($row[$field]);
                }
            }
        }
        if (empty($pending)) {
            return $rows;
        }

        $db_debug = $CI->db->db_debug;
        $CI->db->db_debug = false;
        $CI->translation_model->translateNow($pending, 3, 5);
        $CI->db->db_debug = $db_debug;

        // 방금 번역된 칸을 다시 치환
        return translate_rows($rows, $fields);
    }
}

if (!function_exists('translate_enqueue')) {
    /**
     * 저장 직후 자동번역 (언어와 무관하게 항상 실행)
     * 1) 번역 대기열에 등록 → 2) 응답 전송 후 그 건만 Google 번역 (연결 3초 / 전체 5초 타임아웃, translate_after_response 참고)
     * 실패·타임아웃이면 대기열에 남아 cron_proc::translate_pending 이 1분 안에 다시 처리한다.
     * 번역 실패가 공고/이력서 저장을 막지 않도록 반드시 커밋 후 호출하고, DB 오류 표시를 끄고 실행한다.
     * @param string $type 'recruit' | 'resume'
     * @param array  $data 저장한 데이터 (recruit: rc_name, rc_hash, rc_desc / resume: ru_name, ru_desc)
     * @param array  $child_names 이력서 경력·자격증 이름 (resume 전용)
     * 쓰기 경로: 공고/이력서 저장 직후 번역 대상 등록 + 즉시 번역 시도
     */
    function translate_enqueue($type, $data, $child_names = array())
    {
        if (!is_array($data)) {
            return;
        }
        $CI =& get_instance();
        if (!isset($CI->translation_model)) {
            $CI->load->model('translationmodel', 'translation_model');
        }
        $db_debug = $CI->db->db_debug;
        $CI->db->db_debug = false;

        $hashes = array();
        if ($type === 'recruit') {
            $hashes = $CI->translation_model->enqueueRecruit($data);
        } elseif ($type === 'resume') {
            $hashes = $CI->translation_model->enqueueResume($data, $child_names);
        }
        // 즉시 번역은 응답을 보낸 뒤로 미룬다 (사용자 "등록되었습니다" 알림 지연 방지)
        if (!empty($hashes)) {
            translate_after_response($hashes);
        }

        $CI->db->db_debug = $db_debug;
    }
}

if (!function_exists('translate_after_response')) {
    /**
     * 번역 대상 해시를 shutdown 단계에 등록한다.
     * shutdown 함수는 json_return 등으로 응답을 보낸 뒤 실행되므로, 번역 대기 시간이 화면 알림을 늦추지 않는다.
     * 번역 실패·타임아웃은 대기열에 남아 cron 이 처리하므로 여기서 예외를 만들지 않는다.
     * @param array $hashes md5 배열 (enqueue 반환값)
     */
    function translate_after_response($hashes)
    {
        // 응답 본문을 출력 버퍼에 모아 두었다가 shutdown 에서 한 번에 보낸다
        if (ob_get_level() === 0) {
            ob_start();
        }
        register_shutdown_function('run_translate_now', $hashes);
    }
}

if (!function_exists('run_translate_now')) {
    /**
     * shutdown 단계 실행: 응답을 먼저 클라이언트에 보낸 뒤 Google 번역을 돌린다.
     * - PHP-FPM: fastcgi_finish_request() 로 응답 종료 후 계속 실행
     * - mod_php(운영 추정): 출력 버퍼를 비워 응답을 먼저 전송 (Content-Length 로 본문 끝 명시)
     * @param array $hashes md5 배열
     */
    function run_translate_now($hashes)
    {
        // 브라우저가 연결을 끊어도 번역은 끝까지 수행
        ignore_user_abort(true);
        @set_time_limit(15);

        // 세션 잠금 해제 (번역 대기 중 다음 요청이 막히지 않도록)
        if (function_exists('session_write_close')) {
            @session_write_close();
        }

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            // 버퍼가 하나뿐이고 헤더가 아직 나가지 않았을 때만 길이를 명시
            if (ob_get_level() === 1 && !headers_sent()) {
                header('Content-Length: ' . ob_get_length());
                header('Connection: close');
            }
            while (ob_get_level() > 0) {
                ob_end_flush();
            }
            flush();
        }

        $CI =& get_instance();
        if (!isset($CI->translation_model)) {
            $CI->load->model('translationmodel', 'translation_model');
        }
        $db_debug = $CI->db->db_debug;
        $CI->db->db_debug = false;

        $CI->translation_model->translateNow($hashes, 3, 5);

        $CI->db->db_debug = $db_debug;
    }
}

if (!function_exists('lang_url')) {
    /**
     * 현재 언어에 맞는 내부 링크. 중국어면 /zh 접두어를 붙인다.
     * (접두어 없는 링크도 쿠키 리다이렉트로 중국어가 유지되지만, 302 왕복을 없애기 위해 주요 링크에 사용)
     * @param string $path 루트 기준 경로 (예: /recruit/job_list)
     * @return string
     */
    function lang_url($path)
    {
        if (site_lang() !== 'zh' || $path === '' || $path[0] !== '/') {
            return $path;
        }
        // 모바일은 쿠키로만 언어를 유지하므로 주소에 /zh 를 붙이지 않는다
        if (site_lang_is_mobile()) {
            return $path;
        }
        if (preg_match('#^/zh(?=/|\?|$)#', $path)) {
            return $path;
        }
        return '/zh' . $path;
    }
}

if (!function_exists('site_lang_html_attr')) {
    /**
     * <html lang> 값
     * @return string
     */
    function site_lang_html_attr()
    {
        return site_lang() === 'zh' ? 'zh-CN' : 'ko';
    }
}

if (!function_exists('site_lang_head_tags')) {
    /**
     * <head> 에 넣을 다국어 태그 묶음
     *  - canonical / hreflang(ko, zh-CN) 링크
     *  - JS 공통 함수(t, lang_url) 정적 파일 로드 (i18n-core.js) + 중국어일 때만 JS 사전 파일(zh.js) 로드
     * @param string $base_url 사이트 기본 URL (예: https://nowjob.co.kr)
     * @return string
     */
    function site_lang_head_tags($base_url)
    {
        $base_url = rtrim($base_url, '/');
        // index.php 가 /zh 를 이미 떼어 냈으므로 언어 없는 경로
        $path = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . $path;
        }
        $ko_url = $base_url . $path;
        $zh_url = $base_url . '/zh' . ($path === '/' ? '' : $path);
        $canonical = (site_lang() === 'zh') ? $zh_url : $ko_url;

        $esc = function ($s) {
            return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
        };

        $html = '<link rel="canonical" href="' . $esc($canonical) . '">' . "\n";
        $html .= '    <link rel="alternate" hreflang="ko" href="' . $esc($ko_url) . '">' . "\n";
        $html .= '    <link rel="alternate" hreflang="zh-CN" href="' . $esc($zh_url) . '">' . "\n";
        $html .= '    <link rel="alternate" hreflang="x-default" href="' . $esc($ko_url) . '">' . "\n";

        // JS 공통 함수 t() / lang_url() 은 모든 페이지가 같으므로 정적 파일로 분리 (브라우저 캐시 사용)
        // 현재 언어는 위의 <html lang> 값으로 JS가 판단하므로 여기서 따로 출력하지 않는다
        $core_path = '/resource/shop/web/js/i18n/i18n-core.js';
        $core_ver = function_exists('getFileVersion') ? getFileVersion($core_path) : '';
        $html .= '    <script src="' . $core_path . '?v=' . $esc($core_ver) . '"></script>' . "\n";

        // 중국어 페이지에서만 문구 사전(window.I18N_ZH) 로드
        if (site_lang() === 'zh') {
            $js_path = '/resource/shop/web/js/i18n/zh.js';
            $ver = function_exists('getFileVersion') ? getFileVersion($js_path) : '';
            $html .= '    <script src="' . $js_path . '?v=' . $esc($ver) . '"></script>' . "\n";
        }
        return $html;
    }
}
