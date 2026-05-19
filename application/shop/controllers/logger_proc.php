<?php

/**
 * 브라우저 로거 컨트롤러
 * 브라우저에서 AJAX로 전송된 로그를 파일로 저장
 */

class Logger_proc extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
  }

  /**
   * 브라우저에서 전송한 로그를 파일로 저장
   * POST /logger_proc/log_browser
   */
  public function log_browser()
  {
    // JSON 입력 받기
    $input = json_decode(file_get_contents('php://input'), true);

    // 유효성 검사
    if (!$input || !isset($input['level']) || !isset($input['message'])) {
      header('Content-Type: application/json');
      echo json_encode(array('code' => '01', 'msg' => 'Invalid input'));
      exit;
    }

    // 로그 디렉토리 설정 (check_logs 함수와 동일)
    $log_dir = dirname(APPPATH) . '/logs/' . date('Y-m-d');

    // 로그 디렉토리 생성
    if (!is_dir($log_dir)) {
      @mkdir($log_dir, 0755, true);
    }

    // 로그 데이터 정리
    $log_data = array(
      'timestamp' => isset($input['timestamp']) ? $input['timestamp'] : date('Y-m-d H:i:s'),
      'level' => $input['level'],
      'message' => $input['message'],
      'url' => isset($input['url']) ? $input['url'] : '',
      'userAgent' => isset($input['userAgent']) ? substr($input['userAgent'], 0, 200) : '',
      'data' => isset($input['data']) ? $input['data'] : array()
    );

    // 로그 파일에 작성
    $log_file = $log_dir . '/browser.log';
    $log_line = json_encode($log_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";

    if (!@file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX)) {
      error_log('logger_proc: 브라우저 로그 저장 실패 - ' . $log_file);
    }

    // 응답 (성공)
    header('Content-Type: application/json');
    echo json_encode(array('code' => '00', 'msg' => 'OK'));
    exit;
  }

  /**
   * SNS 로그인 디버깅용 - redirect_uri 정보 로깅
   * GET /logger_proc/log_redirect?service=naver&url=...&error=...
   */
  public function log_redirect()
  {
    $this->load->library('logger');

    $service = input_get('service', '');
    $url = input_get('url', '');
    $error = input_get('error', '');
    $error_desc = input_get('error_description', '');

    // redirect_uri 관련 로그
    $this->logger->error('SNS 로그인 리다이렉트 오류', array(
      'service' => $service,
      'full_url' => $url,
      'error' => $error,
      'error_description' => $error_desc,
      'user_agent' => substr($_SERVER['HTTP_USER_AGENT'], 0, 200),
      'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''
    ));

    // 사용자에게 오류 메시지 표시
    header('Content-Type: application/json');
    echo json_encode(array(
      'code' => '99',
      'msg' => 'SNS 로그인에 실패했습니다. (redirect_uri 불일치)',
      'error' => $error
    ));
    exit;
  }

  /**
   * 로그 파일 확인 (웹 테스트용)
   * GET /logger_proc/check_logs
   */
  public function check_logs()
  {
    header('Content-Type: application/json');

    $log_dir = dirname(APPPATH) . '/logs/' . date('Y-m-d');

    if (!is_dir($log_dir)) {
      echo json_encode(array(
        'code' => '01',
        'msg' => '로그 디렉토리가 없습니다.',
        'log_dir' => $log_dir
      ));
      exit;
    }

    $log_files = array();
    $php_app_log = $log_dir . '/php-app.log';
    $php_error_log = $log_dir . '/php-app-error.log';
    $browser_log = $log_dir . '/browser.log';

    // php-app.log 확인
    if (file_exists($php_app_log)) {
      $lines = file($php_app_log);
      $recent_lines = array_slice($lines, -5);
      $log_files['php-app.log'] = array(
        'exists' => true,
        'size' => filesize($php_app_log) . ' bytes',
        'total_lines' => count($lines),
        'recent_lines' => array_map('trim', $recent_lines)
      );
    } else {
      $log_files['php-app.log'] = array('exists' => false);
    }

    // php-app-error.log 확인
    if (file_exists($php_error_log)) {
      $lines = file($php_error_log);
      $recent_lines = array_slice($lines, -5);
      $log_files['php-app-error.log'] = array(
        'exists' => true,
        'size' => filesize($php_error_log) . ' bytes',
        'total_lines' => count($lines),
        'recent_lines' => array_map('trim', $recent_lines)
      );
    } else {
      $log_files['php-app-error.log'] = array('exists' => false);
    }

    // browser.log 확인
    if (file_exists($browser_log)) {
      $lines = file($browser_log);
      $recent_lines = array_slice($lines, -5);
      $log_files['browser.log'] = array(
        'exists' => true,
        'size' => filesize($browser_log) . ' bytes',
        'total_lines' => count($lines),
        'recent_lines' => array_map('trim', $recent_lines)
      );
    } else {
      $log_files['browser.log'] = array('exists' => false);
    }

    echo json_encode(array(
      'code' => '00',
      'msg' => '로그 파일 확인 완료',
      'log_dir' => $log_dir,
      'files' => $log_files
    ));
    exit;
  }
}
