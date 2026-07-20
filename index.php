<?php

/**
 * 유지보수 모드: 서비스 점검 중일 때 활성화
 * true = 점검 페이지만 표시, false = 정상 서비스
 */
$maintenance_mode = false;

if ($maintenance_mode) {
    // 기존 라우팅 무시하고 점검 페이지만 표시
    header('Location: /outer/index.html');
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
}

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
