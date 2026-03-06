<?php
/**
 * [검사원 B의 전용 검사 도구]
 * 역할: 괄호 짝 맞춤 검사 + PHP 5.4 호환성 체크
 */

// 1. 검사할 파일들 가져오기 (현재 폴더의 모든 .php 파일)
$files = glob("*.php");
$totalErrors = 0;

echo "=== 🛡️ PHP 5.4 & 구문 정합성 검사 시작 ===\n";

foreach ($files as $file) {
    // 이 검사기 파일 자체는 건너뛰어요.
    if ($file === 'lint_check.php') continue;

    $content = file_get_contents($file);
    $fileErrors = 0;
    echo "\n📄 파일 검사 중: $file\n";

    // --- [검사 1] 괄호 짝궁 검사 ---
    $brackets = array(
        '(' => ')', 
        '{' => '}', 
        '[' => ']'
    );

    foreach ($brackets as $open => $close) {
        $openCount = substr_count($content, $open);
        $closeCount = substr_count($content, $close);

        if ($openCount !== $closeCount) {
            echo "❌ [짝궁 에러] '$open'는 {$openCount}개인데, '$close'는 {$closeCount}개입니다.\n";
            $fileErrors++;
        }
    }

    // --- [검사 2] PHP 5.4 금지 문법 (?? 연산자) ---
    // PHP 5.4에는 ?? 연산자가 없어서 에러가 나요.
    if (strpos($content, '??') !== false) {
        echo "⚠️ [버전 경고] PHP 5.4에서 지원하지 않는 '??' 연산자가 발견되었습니다.\n";
        $fileErrors++;
    }

    // --- [검사 3] PHP 자체 문법 검사 (Syntax Check) ---
    // 실제로 컴퓨터가 읽을 수 있는 코드인지 확인해요.
    $output = array();
    $returnVar = 0;
    exec("php -l $file 2>&1", $output, $returnVar);
    
    if ($returnVar !== 0) {
        echo "❌ [문법 오류] " . $output[0] . "\n";
        $fileErrors++;
    }

    if ($fileErrors === 0) {
        echo "✅ 이 파일은 완벽합니다!\n";
    }
    $totalErrors += $fileErrors;
}

// --- 최종 결과 보고 ---
echo "\n======================================\n";
if ($totalErrors === 0) {
    echo "🎉 [최종 합격] 모든 검사를 통과했습니다!\n";
    exit(0); // 에이전트에게 "성공" 신호를 보냄
} else {
    echo "🚨 [최종 불합격] 총 $totalErrors 개의 오류가 발견되었습니다.\n";
    exit(1); // 에이전트에게 "실패" 신호를 보냄
}