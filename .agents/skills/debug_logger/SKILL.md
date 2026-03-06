---
name: logger
description: 특정 데이터를 'host.txt' 파일에 기록하기 위한 PHP file_put_contents 코드를 작성 규칙에 맞춰 생성합니다.
---

# 로그 기록(Logging) 스킬
이 스킬은 특정 데이터를 'debug_log/host.txt' 파일에 기록하는 PHP 코드를 작성합니다.

## 규칙
1. 항상 `file_put_contents` 함수를 사용합니다.
2. 로그 저장 경로는 `DIR . '/debug_log/host.txt'`로 고정합니다.
3. 새로운 내용을 뒤에 추가할 수 있도록 `FILE_APPEND` 옵션을 사용합니다.
4. 초등학생도 이해할 수 있는 한글 주석을 포함합니다.

## 예시 1
**질문:** 사용자 이름을 로그에 남겨줘.
**답변:**
// 'host.txt' 파일에 사용자 이름을 기록해요. (FILE_APPEND는 기존 내용을 지우지 않고 뒤에 덧붙이라는 뜻이에요!)
file_put_contents(DIR . '/debug_log/host.txt', "사용자 이름: " . $username . PHP_EOL, FILE_APPEND);

## 예시 2
**질문:** 에러 메시지를 로그에 기록해줘.
**답변:**
// 에러가 발생했을 때 어떤 에러인지 일기장에 적어두는 코드예요.
file_put_contents(DIR . '/debug_log/host.txt', "에러 발생: " . $errorMessage . PHP_EOL, FILE_APPEND);