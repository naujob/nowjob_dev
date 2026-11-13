<?php

/**
 * 유지보수 모드: 2026-08-01 03:00~08:00 (KST) 자동 점검 전환
 * 이 시간대에는 모든 요청을 외부 점검 페이지로 리다이렉트
 */
date_default_timezone_set('Asia/Seoul');
$maintenance_start = strtotime('2026-08-01 03:00:00');
$maintenance_end   = strtotime('2026-08-01 08:00:00');
$now = time();

if ($now >= $maintenance_start && $now < $maintenance_end) { // 깃허브 점검 안내 페이지로 이동
    header('Location: https://songsong1122.github.io/nowjob_maintenance/');
    exit;
}

/**
 * ============================================================
 * CodeIgniter Front Controller (dev / prod 공통 최종본)
 * Location: /website/dev_nowapk/index.php
 * ============================================================
 * ● FCPATH (웹 루트)     : /var/www/html/DEV_NOWAPK/
 *   APPPATH (현재 앱)    : /var/www/html/DEV_NOWAPK/application/shop/
 *   SHAREDPATH (공용)    : /var/www/html/DEV_NOWAPK/application/common/
 *   BASEPATH (CI 본체)   : /var/www/html/DEV_NOWAPK/system/
 
 */

/**
 * ------------------------------------------------------------
 * 0. index.php 기준 절대 경로
 * ------------------------------------------------------------
 */
// dirname(__FILE__): 현재 index.php 파일이 위치한 디렉터리 절대경로
// DIRECTORY_SEPARATOR: OS별 경로 구분자(Linux/macOS '/', Windows '\')
// /website/nowapk/index.php -> /website/nowapk/
// $base_path: /home/nowapk/dev_nowapk/index.php -> /home/nowapk/dev_nowapk/
$base_path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

/**
 * ------------------------------------------------------------
 * 1. 서버 이름 안전하게 판별 (프록시 대응)
 * ------------------------------------------------------------
 * 현재 접속한 서버의 도메인을 안전하게 판별해서 환경에 맞는 도메인 값을 만드는 코드
 * HTTP_HOST = 사용자가 서버에 요청할 때 보낸 도메인 이름
 * 중간에 프록시 서버가 있다면 도메인 이름을 정학히 판별하기 어렵기 때문에, 프록시 서버가 전달하는 HTTP_X_FORWARDED_HOST 헤더를 우선적으로 사용합니다.
 * 프록시 서버 뒤에 있을 때 HTTP_X_FORWARDED_HOST 헤더를 우선적으로 사용하여 원래의 호스트 이름을 판별합니다.
 * HTTP_X_FORWARDED_HOST가 없으면 HTTP_HOST, 그 다음 SERVER_NAME을 사용합니다. 이렇게 하면 프록시 서버 뒤에서도 올바른 호스트 이름을 얻을 수
 * HTTP_X_FORWARDED_HOST: 원래 사용자가 어떤 주소(test.com)로 접속하려고 했는지 프록시 서버가 전달하는 헤더
 * HTTP_HOST: 현재 요청의 호스트 이름(프록시 서버의 주소일 수 있음) 예: www.mysite.com:8080
 * SERVER_NAME: 웹서버 설정 파일에 적어둔 서버 이름 값 / dev.nowjob.co.kr
 * // HTTP_X_FORWARDED_HOST :비었음
 * HTTP_HOST: dev.nowjob.co.kr
 * <VirtualHost *:80>
 *    ServerName example.com
  *   ServerAlias www.example.com
* </VirtualHost>
 */
$server_name = ''; // server_name: dev.nowjob.co.kr
if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $_SERVER['HTTP_X_FORWARDED_HOST'] !== '') {
    $tmp = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
    $server_name = trim($tmp[0]);
} elseif (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== '') {
    $server_name = $_SERVER['HTTP_HOST'];
} elseif (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] !== '') {
    $server_name = $_SERVER['SERVER_NAME'];
}

$server_name = strtolower($server_name);

// 포트 제거 
// host_no_port":"dev.nowjob.co.kr
$host_no_port = $server_name;
$pos = strpos($host_no_port, ':');
if ($pos !== false) {
    $host_no_port = substr($host_no_port, 0, $pos);
}

// 도메인 파생값 (하드코딩 최소화)
// normalized_host":"dev.nowjob.co.kr
// 
$normalized_host = preg_replace('/^www\./', '', $host_no_port);
$base_domain = preg_replace('/^(dev|admin|api)\./', '', $normalized_host);
// base_domain:nowjob.co.kr
if ($base_domain === '') {
    $base_domain = $normalized_host;
}
// APP_MAIN_DOMAIN: 
// main_domain:nowjob.co.kr
$main_domain = getenv('APP_MAIN_DOMAIN');
if ($main_domain === false || $main_domain === '') {
    $main_domain = $base_domain;
}
// dev_domain":"dev.nowjob.co.kr"
$dev_domain = getenv('APP_DEV_DOMAIN');
if ($dev_domain === false || $dev_domain === '') {
    $dev_domain = 'dev.' . $main_domain;
}

/**
 * ------------------------------------------------------------
 * 2. 환경 판별 (APP_ENV 우선, 없으면 호스트 기반)
 * ------------------------------------------------------------
 */
// env_from_app_env":"development
$env_from_app_env = '';
$raw_app_env = getenv('APP_ENV');
// raw_app_env":"developmen
if ($raw_app_env !== false && $raw_app_env !== '') {
    $raw_app_env = strtolower(trim($raw_app_env));
    if (in_array($raw_app_env, ['dev', 'development', 'local', 'test', 'testing', 'stage', 'staging'], true)) {
        $env_from_app_env = 'development';
    } elseif (in_array($raw_app_env, ['prod', 'production', 'live'], true)) {
        $env_from_app_env = 'production';
    }
}

if ($env_from_app_env !== '') {
    define('ENVIRONMENT', $env_from_app_env);
    $is_dev = (ENVIRONMENT === 'development');
} else {
    $is_dev = false;
    if ($normalized_host === $dev_domain || strpos($normalized_host, 'dev.') === 0) {
        $is_dev = true;
    }
    define('ENVIRONMENT', $is_dev ? 'development' : 'production');
}

/**
 * ------------------------------------------------------------
 * 3. 잘못된 도메인 접근 차단
 * ------------------------------------------------------------
 */
if (strpos($host_no_port, '19job') !== false) {
    if ($is_dev) {
        header('Location: https://' . $dev_domain);
    } else {
        header('Location: https://' . $main_domain);
    }
    exit;
}

/**
 * ------------------------------------------------------------
 * 4. 기본 설정
 * ------------------------------------------------------------
 */
date_default_timezone_set('Asia/Seoul');

/**
 * ------------------------------------------------------------
 * 5. 세션 / 쿠키 정책 (session_start 이전)
 * ------------------------------------------------------------
 */
// cookie_domain_env":false
$cookie_domain_env = getenv('APP_COOKIE_DOMAIN');
if ($cookie_domain_env !== false && $cookie_domain_env !== '') {
    $cookie_domain = $cookie_domain_env;
    // cookie_domain":"dev.nowjob.co.kr
} else {
    $cookie_domain = $is_dev ? $dev_domain : '.' . $main_domain;
}
//"is_https":"true
$is_https = false;
if (
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
) {
    $is_https = true;
}

ini_set('session.use_cookies', 1); // 세션 쿠키 사용
ini_set('session.cookie_domain', $cookie_domain); // 세션 쿠키 도메인 설정
ini_set('session.cookie_path', '/'); // 세션 쿠키 경로 설정
ini_set('session.cookie_lifetime', 3600 * 24 * 365); // 세션 쿠키 만료 시간 1년
ini_set('session.gc_maxlifetime', 3600 * 24 * 365); // 세션 데이터 유효기간 1년

if ($is_https) { // HTTPS 환경에서는 Secure, SameSite=None 설정
    ini_set('session.cookie_secure', 1); // 세션 쿠키를 HTTPS에서만 전송
    ini_set('session.cookie_samesite', 'none'); // SameSite=None 설정 (HTTPS 환경에서만 가능)
} else {
    ini_set('session.cookie_secure', 0); // 세션 쿠키를 HTTP에서도 전송
    ini_set('session.cookie_samesite', 'Lax'); // SameSite=Lax 설정 (HTTP 환경에서는 None 불가)
}

/**
 * ------------------------------------------------------------
 * 6. session_start
 * ------------------------------------------------------------
 */
if (session_status() === PHP_SESSION_NONE) { // PHP에서 세션의 현재 상태를 나타내는 상수 중 하나
    session_start();
}

/**
 * ------------------------------------------------------------
 * 7. 세션 쿠키 갱신 (iOS WebView 대응)
 * ------------------------------------------------------------
 */
if ($is_https) {
    $expires = gmdate('D, d-M-Y H:i:s T', time() + (3600 * 24 * 365));
    header(
        'Set-Cookie: ' . session_name() . '=' . session_id()
            . '; expires=' . $expires
            . '; path=/'
            . '; domain=' . $cookie_domain
            . '; Secure; HttpOnly; SameSite=None',
        false
    );
}

/**
 * ------------------------------------------------------------
 * 8. HTTPS 강제 (운영만)
 * ------------------------------------------------------------
 */
function force_https()
{
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === '' || $_SERVER['HTTPS'] === 'off') {
        header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
        exit;
    }
}

if (!$is_dev) {
    force_https();
}

/**
 * ------------------------------------------------------------
 * 9. CodeIgniter system / application 경로 설정
 * ------------------------------------------------------------
 */
// $base_path: /website/nowapk/
// $system_path: /website/nowapk/system
// $application_folder: /website/nowapk/application
$system_path = $base_path . 'system';
// $system_path: /website/nowapk/system
// $application_folder: /website/nowapk/application
$application_folder = $base_path . 'application';

// system 경로 정규화
// realpath: 경로를 정규화하여 상대경로를 절대 경로로 반환, 실패하면 false 반환
if (($_temp = realpath($system_path)) !== false) {
    $system_path = $_temp . DIRECTORY_SEPARATOR;
} else {
    $system_path = rtrim($system_path, '/\\') . DIRECTORY_SEPARATOR;
}

if (!is_dir($system_path)) {
    header('HTTP/1.1 503 Service Unavailable', true, 503);
    echo 'Your system folder path does not appear to be set correctly.';
    exit;
}

// application 경로 분기 (admin / api / shop)
// $host_parts: ['nowjob', 'co', 'kr']
$host_parts = explode('.', $host_no_port);
// $sub: 'nowjob'
$sub = isset($host_parts[0]) ? $host_parts[0] : '';
// host_parts":"dev,nowjob,co,kr","sub":"dev"
if ($sub === 'admin') {
    // $application_folder: /website/nowapk/application/admin
    // APPPATH: /website/nowapk/application/admin/
   
    $application_folder .= '/admin';
} elseif ($sub === 'api') {
    // $application_folder: /website/nowapk/application/api
    $application_folder .= '/api';
} else {
    // $application_folder: /website/nowapk/application/shop
    $application_folder .= '/shop';
    // APPPATH: /website/nowapk/application/shop/

    /**
     * 언어 경로 접두어 처리 (웹 다국어)
     * /zh/recruit/job_list → REQUEST_URI를 /recruit/job_list 로 바꿔 기존 라우팅을 그대로 사용하고,
     * SITE_LANG_PATH 상수로 "주소가 중국어"임을 표시한다. 선택 언어는 site_lang 쿠키로 기억한다.
     * 중국어 주소(/zh/...)로 접속하면 SITE_LANG_PATH 를 'zh' 로 정의하고,
     * site_lang 쿠키가 zh 가 아니면 zh 로 저장한다(쿠키는 1년 유지, path=/).
     */
    if (isset($_SERVER['REQUEST_URI']) && preg_match('#^/zh(?=/|\?|$)#', $_SERVER['REQUEST_URI'])) {
        $_lang_rest = (string)substr($_SERVER['REQUEST_URI'], 3); // 주소 맨 앞의 세 글자('/zh')를 싹둑 잘라내고, 나머지 진짜 주소($_lang_rest)만 따로
        if ($_lang_rest === '' || $_lang_rest[0] !== '/') {
            $_lang_rest = '/' . $_lang_rest;
        }
        $_SERVER['REQUEST_URI'] = $_lang_rest; // 서버가 앞으로 진짜로 읽어야 할 주소($_SERVER['REQUEST_URI'])에 원래의 깨끗한 주소로 덮어씌워
        define('SITE_LANG_PATH', 'zh');
        if (!isset($_COOKIE['site_lang']) || $_COOKIE['site_lang'] !== 'zh') {
            setcookie('site_lang', 'zh', time() + 31536000, '/');
            $_COOKIE['site_lang'] = 'zh';
        }
        unset($_lang_rest);
    }
}
// 언어 경로 접두어가 없으면 빈 값 (admin/api 포함)
defined('SITE_LANG_PATH') || define('SITE_LANG_PATH', '');

// application 경로 정규화
if (is_dir($application_folder)) {
    //$temp: /website/nowapk/application/shop
    if (($_temp = realpath($application_folder)) !== false) {
        // $application_folder: /website/nowapk/application/shop
        $application_folder = $_temp;
    } else {
        // $application_folder: /website/nowapk/application/shop/
        $application_folder = rtrim($application_folder, '/\\');
    }
} else { // 디렉터리가 존재하지 않으면 503 에러 발생
    header('HTTP/1.1 503 Service Unavailable', true, 503);
    echo 'Your application folder path does not appear to be set correctly.';
    exit;
}

/**
 * ------------------------------------------------------------
 * 10. CI 필수 상수 정의
 * ------------------------------------------------------------
 */
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_path);
// $FCPATH: /website/nowapk/
define('FCPATH', $base_path);
// $SYSDIR: nowapk
define('SYSDIR', basename(BASEPATH));
// $APPPATH: /website/nowapk/application/shop/ 
define('APPPATH', $application_folder . DIRECTORY_SEPARATOR);
// $VIEWPATH: /website/nowapk/application/shop/views/
define('VIEWPATH', APPPATH . 'views' . DIRECTORY_SEPARATOR);

// $SHAREDPATH: /website/nowapk/application/common/
defined('SHAREDPATH') || define('SHAREDPATH', $base_path . 'application/common/');

/**
 * 웹 다국어 리다이렉트 (컨트롤러 실행 전)
 * 중국어를 고른 사용자(쿠키 site_lang=zh)가 /zh 없는 주소로 웹 페이지를 열면 /zh 주소로 보낸다.
 * 고정 링크(href="/recruit/...")를 눌러도 중국어 화면이 유지되도록 하기 위함.
 * 컨트롤러보다 먼저 처리해야 조회수 증가 등 부수효과가 두 번 실행되지 않는다.
 * 대상: shop 앱 + GET + 비AJAX + *_proc 아님 + 웹(PC) 기기
 */
if (
    SITE_LANG_PATH === ''
    && strpos($application_folder, DIRECTORY_SEPARATOR . 'shop') !== false
    && isset($_COOKIE['site_lang']) && $_COOKIE['site_lang'] === 'zh'
    && isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'GET'
    && !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    && isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '_proc/') === false
) {
    // getDeviceType(): 앱(os_type 헤더)·모바일 UA 는 'mobile' — CI 로더도 include_once 로 같은 파일을 읽으므로 중복 정의 없음
    require_once SHAREDPATH . 'helpers/path_helper.php';
    if (function_exists('apache_request_headers') && getDeviceType() === 'web') {
        $_lang_uri = $_SERVER['REQUEST_URI'];
        if ($_lang_uri === '' || $_lang_uri[0] !== '/') {
            $_lang_uri = '/' . $_lang_uri;
        }
        header('Location: /zh' . ($_lang_uri === '/' ? '' : $_lang_uri), true, 302);
        exit;
    }
}

/**
 * ------------------------------------------------------------
 * 11. 에러 리포팅
 * ------------------------------------------------------------
 */
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
}

/**
 * ------------------------------------------------------------
 * 12. CodeIgniter 부트스트랩
 * ------------------------------------------------------------
 */
require_once FCPATH . 'config/functions.php';
require_once FCPATH . 'config/initialize.php';
require_once BASEPATH . 'core/CodeIgniter.php';
