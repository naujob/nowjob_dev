<?php
/**
 * ============================================================
 * CodeIgniter Front Controller (dev / prod 공통 최종본)
 * Location: /website/dev_nowapk/index.php
 * ============================================================
 */

/**
 * ------------------------------------------------------------
 * 0. index.php 기준 절대 경로
 * ------------------------------------------------------------
 */
// dirname(__FILE__): 현재 index.php 파일이 위치한 디렉터리 절대경로
// DIRECTORY_SEPARATOR: OS별 경로 구분자(Linux/macOS '/', Windows '\')
// /website/nowapk/index.php -> /website/nowapk/
$base_path = dirname(__FILE__) . DIRECTORY_SEPARATOR;

/**
 * ------------------------------------------------------------
 * 1. 서버 이름 안전하게 판별 (프록시 대응)
 * ------------------------------------------------------------
 */
$server_name = '';
// $tmp:['nowjob.co.kr']
if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && $_SERVER['HTTP_X_FORWARDED_HOST'] !== '') {
    $tmp = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
    // $server_name: 'nowjob.co.kr'
    $server_name = trim($tmp[0]);
} elseif (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== '') {
    $server_name = $_SERVER['HTTP_HOST'];
} elseif (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] !== '') {
    $server_name = $_SERVER['SERVER_NAME'];
    // $server_name: 'nowjob.co.kr'
}

$server_name = strtolower($server_name);

// 포트 제거 (포트 제거 후 $host_no_port: 'nowjob.co.kr')
$host_no_port = $server_name;
$pos = strpos($host_no_port, ':');
if ($pos !== false) {
    $host_no_port = substr($host_no_port, 0, $pos);
}

/**
 * ------------------------------------------------------------
 * 2. 환경 판별 (dev / prod)
 * ------------------------------------------------------------
 */
$is_dev = false;
if (strpos($host_no_port, 'dev.nowjob.co.kr') !== false) {
    $is_dev = true;
}

define('ENVIRONMENT', $is_dev ? 'development' : 'production');

/**
 * ------------------------------------------------------------
 * 3. 잘못된 도메인 접근 차단
 * ------------------------------------------------------------
 */
if (strpos($host_no_port, '19job') !== false) {
    if ($is_dev) {
        header('Location: https://dev.nowjob.co.kr');
    } else {
        header('Location: https://nowjob.co.kr');
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
$cookie_domain = $is_dev ? 'dev.nowjob.co.kr' : '.nowjob.co.kr';

$is_https = false;
if (
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
) {
    $is_https = true;
}

ini_set('session.use_cookies', 1);
ini_set('session.cookie_domain', $cookie_domain);
ini_set('session.cookie_path', '/');
ini_set('session.cookie_lifetime', 3600 * 24 * 365);
ini_set('session.gc_maxlifetime', 3600 * 24 * 365);

if ($is_https) {
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_samesite', 'none');
} else {
    ini_set('session.cookie_secure', 0);
    ini_set('session.cookie_samesite', 'Lax');
}

/**
 * ------------------------------------------------------------
 * 6. session_start
 * ------------------------------------------------------------
 */
if (session_status() === PHP_SESSION_NONE) {
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
// $host_no_port: 'nowjob.co.kr'
// $host_parts: ['nowjob', 'co', 'kr']
$host_parts = explode('.', $host_no_port);
// $sub: 'nowjob'
$sub = isset($host_parts[0]) ? $host_parts[0] : '';

if ($sub === 'admin') {
    // $application_folder: /website/nowapk/application/admin
    $application_folder .= '/admin';
} elseif ($sub === 'api') {
    // $application_folder: /website/nowapk/application/api
    $application_folder .= '/api';
} else {
    // $application_folder: /website/nowapk/application/shop
    $application_folder .= '/shop';
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

