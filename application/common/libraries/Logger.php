<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Logger 라이브러리 - PHP 5.4 호환
 * 일관된 JSON 형식으로 로그를 파일에 저장
 */
class Logger
{

    private $log_dir;
    private $current_date;
    private $context = array();
    private $request_id;

    public function __construct()
    {
        // 로그 디렉토리 설정 (CodeIgniter 경로 기준)
        //CodeIgniter의 /index.php가 서브도메인에 따라 라우팅함
        // APPPATH = /website/dev_nowapk/application/shop/
        // APPPATH . 'logs' = /website/dev_nowapk/application/shop/logs 

        $this->log_dir = APPPATH . 'logs';
        $this->current_date = date('Y-m-d');
        $this->request_id = $this->generate_request_id();

        // 로그 디렉토리 생성
        $this->ensure_log_directory();
    }

    /**
     * 요청 ID 생성
     */
    private function generate_request_id()
    {
        // substr($string, $start, $length);
        // microtime(true) : 현재 Unix 타임스탬프를 마이크로초 단위로 반환
        // mt_rand() : 더 나은 난수 생성기 사용
        // md5() : 문자열을 32자리의 16진수로 변환
        return 'req-' . substr(md5(microtime(true) . mt_rand()), 0, 8);
    }

    /**
     * 로그 디렉토리 생성/확인
     */
    private function ensure_log_directory()
    {
        $date_dir = $this->log_dir . '/' . $this->current_date;
        if (!is_dir($date_dir)) {
            @mkdir($date_dir, 0755, true);
        }
    }

    /**
     * Context 설정
     */
    public function setContext($key, $value)
    {
        $this->context[$key] = $value;
    }

    /**
     * Request ID 설정
     */
    public function setRequestId($request_id)
    {
        $this->request_id = $request_id;
    }

    /**
     * 로그 작성 (공통 메서드)
     */
    private function write_log($level, $message, $data = array())
    {
        // 날짜 변경 확인
        $today = date('Y-m-d');
        if ($today !== $this->current_date) {
            $this->current_date = $today;
            $this->ensure_log_directory();
        }

        $log_entry = array(
            'timestamp' => date('Y-m-d H:i:s.') . substr((string)(microtime(true) * 1000), -3),
            'level' => $level,
            'service' => 'php-app',
            'message' => $message,
            'request_id' => $this->request_id,
            'context' => $this->context,
            'data' => $data
        );
        // "PHP 데이터를 JSON으로 변환"
        $log_line = json_encode($log_entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";

        $date_dir = $this->log_dir . '/' . $this->current_date;

        // 디렉토리 생성 확인
        if (!is_dir($date_dir)) {
            @mkdir($date_dir, 0777, true);
        }

        // 모든 로그 저장
        $log_file = $date_dir . '/php-app.log';
        if (!@file_put_contents($log_file, $log_line, FILE_APPEND | LOCK_EX)) {
            // 파일 쓰기 실패 시에도 조용히 처리 (시스템 에러 방지)
            error_log('Logger: 파일 쓰기 실패 - ' . $log_file);
        }

        // ERROR 이상만 별도 저장
        if (in_array($level, array('ERROR', 'FATAL'))) {
            $error_log_file = $date_dir . '/php-app-error.log';
            @file_put_contents($error_log_file, $log_line, FILE_APPEND | LOCK_EX);
        }
    }

    /**
     * DEBUG 로그
     */
    public function debug($message, $data = array())
    {
        $this->write_log('DEBUG', $message, $data);
    }

    /**
     * INFO 로그
     */
    public function info($message, $data = array())
    {
        $this->write_log('INFO', $message, $data);
    }

    /**
     * WARN 로그
     */
    public function warn($message, $data = array())
    {
        $this->write_log('WARN', $message, $data);
    }

    /**
     * ERROR 로그
     */
    public function error($message, $data = array())
    {
        $this->write_log('ERROR', $message, $data);
    }

    /**
     * FATAL 로그
     */
    public function fatal($message, $data = array())
    {
        $this->write_log('FATAL', $message, $data);
    }
}
