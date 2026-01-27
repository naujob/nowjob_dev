<?php
// $allowed_ips = ['119.196.18.147', '175.212.236.93', '59.14.22.12'];
// function get_client_ip()
// {
//     $keys = [
//         'HTTP_CF_CONNECTING_IP', // Cloudflare
//         'HTTP_CLIENT_IP',
//         'HTTP_X_FORWARDED_FOR',  // 프록시일 때 (콤마로 여러개 올 수 있음)
//         'REMOTE_ADDR',
//     ];
//     foreach ($keys as $k) {
//         if (!empty($_SERVER[$k])) {
//             $ip = $_SERVER[$k];
//             if ($k === 'HTTP_X_FORWARDED_FOR') {
//                 // X-Forwarded-For 값은 "client, proxy1, proxy2" 형식일 수 있음 -> 첫 항목이 실제 클라이언트
//                 $parts = explode(',', $ip);
//                 $ip = trim($parts[0]);
//             }
//             return $ip;
//         }
//     }
//     return '';
// }

// $user_ip = get_client_ip();

// // 디버그용(문제 있을 땐 주석 해제)
// // error_log("Client IP detected: {$user_ip}");
// // echo $user_ip; exit;

// if (!in_array($user_ip, $allowed_ips, true)) {
//     $file = __DIR__ . '/outer/index.html';
//     if (is_file($file)) {
//         if (!headers_sent()) {
//             header('HTTP/1.1 503 Service Unavailable');
//             header('Retry-After: 3600');
//         } else {
//             error_log('Maintenance headers could not be sent — headers already output.');
//         }
//         readfile($file);
//         exit;
//     } else {
//         echo '현재 사이트 점검 중입니다. (안내문 파일이 없습니다)';
//         exit;
//     }
// }

if (strpos($_SERVER['SERVER_NAME'], '19job') !== false) {
    header('Location: https://nowjob.co.kr');
    exit;
}
// $_SERVER['SERVER_NAME'] = nowjob.co.kr
// 도메인 설정 (session_start() 전에 필요)
// 로그인 상태를 모든 서브 도메인(admin, api 등)에서 똑같이 공유하기 위해 도메인 설정
$domain_name = $_SERVER['SERVER_NAME'];
$domain_name = str_replace("www.", "", $domain_name);
$domain_name = str_replace("home.", "", $domain_name);
$domain_name = str_replace("app.", "", $domain_name);
$domain_name = str_replace("admin.", "", $domain_name);

// session_start() 호출 전에 모든 세션 설정을 먼저 적용
// session_cache_limiter("private") 제거 - iOS에서 앱 종료 시 쿠키가 삭제되는 문제 방지
// private는 브라우저 종료 시 쿠키를 만료시키므로 영구 쿠키를 사용하려면 제거해야 함
date_default_timezone_set('Asia/Seoul');

// HTTPS인 경우 쿠키 보안 설정
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
    ini_set("session.cookie_samesite", "none");
    ini_set("session.cookie_secure", 1);
} else {
    ini_set('session.cookie_samesite', 'Lax');
    ini_set("session.cookie_secure", 0);
}

// 세션 쿠키 및 세션 파일 수명 설정 (session_start() 전에 설정해야 적용됨)
ini_set("session.cookie_lifetime", 3600 * 24 * 360); // 1년
ini_set("session.cache_expire", 3600 * 24 * 360);
ini_set("session.gc_maxlifetime", 3600 * 24 * 360); // 세션 파일 수명 1년
ini_set("session.use_cookies", 1);
ini_set("session.cookie_domain", "." . $domain_name);
ini_set("session.cookie_path", "/");

// 그 다음에 session_start() 호출
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 세션 쿠키 갱신 (세션 ID가 이미 생성된 후 쿠키 설정)
// iOS에서 앱을 닫아도 로그인 상태 유지를 위해 영구 쿠키로 설정
$lifetimecookie = 360 * 24 * 60 * 60; // 1년
$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

if ($is_https) {
    // HTTPS: Secure, SameSite=None, HttpOnly
    // setcookie 함수로는 SameSite=None을 정확히 설정하기 어려우므로 header 직접 사용
    $cookie_value = session_id(); // 내장 함수 session_id()를 사용하여 세션 ID 가져옴
    $expires = gmdate('D, d-M-Y H:i:s T', time() + $lifetimecookie);
    header('Set-Cookie: ' . session_name() . '=' . $cookie_value . '; expires=' . $expires . '; path=/; domain=.' . $domain_name . '; Secure; HttpOnly; SameSite=None', false);
} else {
    // HTTP: HttpOnly만 설정
    setcookie(session_name(), session_id(), time() + $lifetimecookie, "/", "." . $domain_name, false, true);
}


/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package CodeIgniter
 * @author  EllisLab Dev Team
 * @copyright   Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright   Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license https://opensource.org/licenses/MIT MIT License
 * @link    https://codeigniter.com
 * @since   Version 1.0.0
 * @filesource
 */

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 */
$site_mode = "development";
define('ENVIRONMENT', $site_mode);

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
switch (ENVIRONMENT) {
    case 'development':
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 1);
        break;

    case 'testing':
    case 'production':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        } else {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}

function chageHppts($https = true)
{

    if ($https == true) {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
            $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $location);
            exit;
        }
    } else {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
        } else {
            $location = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $location);
            exit;
        }
    }
}

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" directory.
 * Set the path if it is not in the same directory as this file.
 */
$system_path = 'system';

$application_folder = 'application';
defined('SHAREDPATH') || define('SHAREDPATH', $application_folder . '/common/');

//분기처리
$hostname = $_SERVER["HTTP_HOST"];
// 주소를 점(.) 기준으로 쪼개기. [admin, nowjob, co, kr] 
$urls = explode(".", $hostname);
$first_str = "";
if (isset($urls)) {
    $first_str = $urls[0]; //주소 맨 앞 글자($first_str)를 보고 보여줄 폴더를 갈아끼우기
}

if ($first_str == "admin") {
    $application_folder .= '/admin';
    chageHppts(true);
} else if ($first_str == "api") {
    $application_folder .= '/api';
    chageHppts(true);
} else {
    $application_folder .= '/shop';
    chageHppts(true);
}

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want to move the view directory out of the application
 * directory, set the path to it here. The directory can be renamed
 * and relocated anywhere on your server. If blank, it will default
 * to the standard location inside your application directory.
 * If you do move this, use an absolute (full) server path.
 *
 * NO TRAILING SLASH!
 */
$view_folder = '';


/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 *
 * Normally you will set your default controller in the routes.php file.
 * You can, however, force a custom routing by hard-coding a
 * specific controller class/function here. For most applications, you
 * WILL NOT set your routing here, but it's an option for those
 * special instances where you might want to override the standard
 * routing in a specific front controller that shares a common CI installation.
 *
 * IMPORTANT: If you set the routing here, NO OTHER controller will be
 * callable. In essence, this preference limits your application to ONE
 * specific controller. Leave the function name blank if you need
 * to call functions dynamically via the URI.
 *
 * Un-comment the $routing array below to use this feature
 */
// The directory name, relative to the "controllers" directory.  Leave blank
// if your controller is not in a sub-directory within the "controllers" one
// $routing['directory'] = '';

// The controller class file name.  Example:  mycontroller
// $routing['controller'] = '';

// The controller function you wish to be called.
// $routing['function'] = '';


/*
 * -------------------------------------------------------------------
 *  CUSTOM CONFIG VALUES
 * -------------------------------------------------------------------
 *
 * The $assign_to_config array below will be passed dynamically to the
 * config class when initialized. This allows you to set custom config
 * items or override any default config values found in the config.php file.
 * This can be handy as it permits you to share one application between
 * multiple front controller files, with each file containing different
 * config values.
 *
 * Un-comment the $assign_to_config array below to use this feature
 */
// $assign_to_config['name_of_config_item'] = 'value of config item';



// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */

// Set the current directory correctly for CLI requests
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE) {
    $system_path = $_temp . DIRECTORY_SEPARATOR;
} else {
    // Ensure there's a trailing slash
    $system_path = strtr(
        rtrim($system_path, '/\\'),
        '/\\',
        DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR
    ) . DIRECTORY_SEPARATOR;
}

// Is the system path correct?
if (! is_dir($system_path)) {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME);
    exit(3); // EXIT_CONFIG
}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// Path to the system directory
define('BASEPATH', $system_path);

// Path to the front controller (this file) directory
define('FCPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

// Name of the "system" directory
define('SYSDIR', basename(BASEPATH));

// The path to the "application" directory
if (is_dir($application_folder)) {
    if (($_temp = realpath($application_folder)) !== FALSE) {
        $application_folder = $_temp;
    } else {
        $application_folder = strtr(
            rtrim($application_folder, '/\\'),
            '/\\',
            DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR
        );
    }
} elseif (is_dir(BASEPATH . $application_folder . DIRECTORY_SEPARATOR)) {
    $application_folder = BASEPATH . strtr(
        trim($application_folder, '/\\'),
        '/\\',
        DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR
    );
} else {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: ' . SELF;
    exit(3); // EXIT_CONFIG
}

define('APPPATH', $application_folder . DIRECTORY_SEPARATOR);

// The path to the "views" directory
if (! isset($view_folder[0]) && is_dir(APPPATH . 'views' . DIRECTORY_SEPARATOR)) {
    $view_folder = APPPATH . 'views';
} elseif (is_dir($view_folder)) {
    if (($_temp = realpath($view_folder)) !== FALSE) {
        $view_folder = $_temp;
    } else {
        $view_folder = strtr(
            rtrim($view_folder, '/\\'),
            '/\\',
            DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR
        );
    }
} elseif (is_dir(APPPATH . $view_folder . DIRECTORY_SEPARATOR)) {
    $view_folder = APPPATH . strtr(
        trim($view_folder, '/\\'),
        '/\\',
        DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR
    );
} else {
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: ' . SELF;
    exit(3); // EXIT_CONFIG
}

define('VIEWPATH', $view_folder . DIRECTORY_SEPARATOR);

/* -------------------------------------------------------------------
 *  Workaround to loading shared library, helpers, models when using multi-applications
 * -------------------------------------------------------------------
 */
require_once 'config/functions.php';

require_once 'config/initialize.php';

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 */
require_once BASEPATH . 'core/CodeIgniter.php';



