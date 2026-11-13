<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 웹(PC) 다국어 헬퍼
 *
 * 언어 결정 규칙
 *  - 페이지 주소가 /zh/... 이면 중국어 (index.php 가 /zh 를 떼고 SITE_LANG_PATH='zh' 로 표시)
 *  - 주소에 언어가 없는 AJAX·POST·*_proc 요청은 site_lang 쿠키를 따른다
 *  - 그 외 페이지 요청은 한국어 (쿠키가 zh 이면 index.php 가 302 로 /zh 주소로 리다이렉트, 컨트롤러 실행 전)
 *  - 모바일(앱 WebView 포함)은 범위 밖이므로 항상 한국어
 *
 * 정적 문구 사전: application/common/language/zh/ui_dict.php
 *  (tools/i18n/xlsx_to_dict.py 가 엑셀 번역표에서 생성 — 직접 수정 금지)
 */

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

        // 모바일은 다국어 범위 밖
        if (function_exists('getDeviceType') && getDeviceType() !== 'web') {
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

if (!function_exists('translate_enqueue')) {
    /**
     * 저장 직후 자동번역 (언어와 무관하게 항상 실행)
     * 1) 번역 대기열에 등록 → 2) 그 건만 바로 Google 번역 (연결 3초 / 전체 5초 타임아웃)
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
        if (!empty($hashes)) {
            $CI->translation_model->translateNow($hashes, 3, 5);
        }

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
     *  - JS 번역 함수 t() 기본 정의 + 중국어일 때만 JS 사전 파일 로드
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

        // JS 번역 함수: 사전(window.I18N_ZH)이 있으면 번역, 없으면 원문
        $html .= '    <script>window.SITE_LANG = "' . site_lang() . '";'
            . 'window.t = function (s) { var d = window.I18N_ZH; var k = String(s).trim(); return (d && Object.prototype.hasOwnProperty.call(d, k)) ? d[k] : s; };'
            . 'window.lang_url = function (p) { return (window.SITE_LANG === "zh" && p && p.charAt(0) === "/" && !/^\/zh(\/|\?|$)/.test(p)) ? "/zh" + p : p; };'
            . '</script>' . "\n";

        if (site_lang() === 'zh') {
            $js_path = '/resource/shop/web/js/i18n/zh.js';
            $ver = function_exists('getFileVersion') ? getFileVersion($js_path) : '';
            $html .= '    <script src="' . $js_path . '?v=' . $esc($ver) . '"></script>' . "\n";
        }
        return $html;
    }
}
