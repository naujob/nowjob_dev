<?php

/**
 * 채팅 디버그 로그 확인 스크립트
 * 앱 간 채팅 메시지 전송 문제 추적용
 */

echo "=== 채팅 디버그 로그 확인 ===\n";
echo "확인 시간: " . date('Y-m-d H:i:s') . "\n\n";

// 1. PHP 디버그 로그 확인
$debug_log_dir = './debug_log';
$debug_log_file = $debug_log_dir . '/debug_' . date('Y-m-d') . '.log';

echo "1. PHP 디버그 로그 확인\n";
echo "로그 파일: " . $debug_log_file . "\n";

if (file_exists($debug_log_file)) {
    $debug_log_content = file_get_contents($debug_log_file);
    $debug_lines = explode("\n", $debug_log_content);

    // 채팅 관련 로그만 필터링
    $chat_logs = array_filter($debug_lines, function ($line) {
        return strpos($line, 'CHAT_DEBUG') !== false;
    });

    if (!empty($chat_logs)) {
        echo "채팅 관련 로그 " . count($chat_logs) . "개 발견:\n";
        foreach (array_slice($chat_logs, -10) as $log) { // 최근 10개만 표시
            echo "  " . $log . "\n";
        }
    } else {
        echo "채팅 관련 로그가 없습니다.\n";
    }
} else {
    echo "디버그 로그 파일이 없습니다.\n";
}

echo "\n";

// 2. Node.js 채팅 로그 확인
$chat_log_file = './chat_log2.txt';

echo "2. Node.js 채팅 로그 확인\n";
echo "로그 파일: " . $chat_log_file . "\n";

if (file_exists($chat_log_file)) {
    $chat_log_content = file_get_contents($chat_log_file);
    $chat_lines = explode("\n", $chat_log_content);

    // 최근 20개 로그만 표시
    $recent_logs = array_slice($chat_lines, -20);

    if (!empty($recent_logs)) {
        echo "최근 채팅 로그 " . count($recent_logs) . "개:\n";
        foreach ($recent_logs as $log) {
            if (trim($log) !== '') {
                echo "  " . $log . "\n";
            }
        }
    } else {
        echo "채팅 로그가 없습니다.\n";
    }
} else {
    echo "채팅 로그 파일이 없습니다.\n";
}

echo "\n";

// 3. 로그 파일 크기 확인
echo "3. 로그 파일 크기 확인\n";
$log_files = [
    $debug_log_file => 'PHP 디버그 로그',
    $chat_log_file => 'Node.js 채팅 로그'
];

foreach ($log_files as $file => $name) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "  {$name}: " . number_format($size) . " bytes\n";
    } else {
        echo "  {$name}: 파일 없음\n";
    }
}

echo "\n";

// 4. 최근 에러 로그 확인
echo "4. PHP 에러 로그 확인\n";
$error_log_file = ini_get('error_log');
if ($error_log_file && file_exists($error_log_file)) {
    $error_content = file_get_contents($error_log_file);
    $error_lines = explode("\n", $error_content);

    // 최근 10개 에러만 표시
    $recent_errors = array_slice($error_lines, -10);

    if (!empty($recent_errors)) {
        echo "최근 에러 로그 " . count($recent_errors) . "개:\n";
        foreach ($recent_errors as $error) {
            if (trim($error) !== '') {
                echo "  " . $error . "\n";
            }
        }
    } else {
        echo "에러 로그가 없습니다.\n";
    }
} else {
    echo "에러 로그 파일을 찾을 수 없습니다.\n";
}

echo "\n=== 로그 확인 완료 ===\n";
