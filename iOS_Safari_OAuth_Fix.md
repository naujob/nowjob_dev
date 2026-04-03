# iOS Safari OAuth redirect_uri 수정 사항

## 문제 분석

iOS Safari만 네이버/구글/위챗 로그인 시 redirect_uri mismatch 에러 발생.

### 원인

프록시/로드밸런서가 `X-Forwarded-Proto` 헤더를 iOS Safari로 올바르게 전달하지 않으면:
- `app_base_url`이 `http://nowjob.co.kr`로 설정됨
- 콜백 URL: `http://nowjob.co.kr/member/naver_login_callback` (HTTP)
- OAuth 제공자 설정: `https://nowjob.co.kr/member/naver_login_callback` (HTTPS)
- 결과: **프로토콜 불일치로 인증 실패**

## 적용된 해결책

### 1. Config 레벨 강제 HTTPS (application/common/config/config.php)

```php
// production 도메인에서 HTTP를 HTTPS로 강제 변환
$is_prod_domain = (strpos($base_url_host, 'nowjob.co.kr') !== false)
    && (strpos($base_url_host, 'dev.') === false);
if ($is_prod_domain && strpos($base_url_env, 'http://') === 0) {
    $base_url_env = 'https://' . substr($base_url_env, 7);
}
```

**효과**: 모든 환경에서 `app_base_url`이 production이면 HTTPS로 설정됨

### 2. 컨트롤러 레벨 iOS Safari 감지 및 처리

Member 컨트롤러에 `ensure_https_for_ios()` 메서드 추가:

```php
private function ensure_https_for_ios($url) {
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $is_ios_safari = preg_match('/(iPhone|iPad|iPod)/i', $user_agent) === 1
        && strpos($user_agent, 'NowJobApp_iOS') === false;

    if ($is_ios_safari && strpos($url, 'http://') === 0) {
        return 'https://' . substr($url, 7);
    }
    return $url;
}
```

**적용 대상**:
- `naver_login_callback()` - 콜백 URL 생성
- `google_login()` - 로그인 시작 시 redirect_uri
- `google_login_callback()` - 콜백 URL 생성
- `wechat_login_callback()` - 콜백 URL 생성

### 3. 뷰 레벨 redirect_uri 통일 (login.php)

```php
$is_ios_safari = preg_match('/(iPhone|iPad|iPod)/i', $_SERVER['HTTP_USER_AGENT']) === 1
    && strpos($_SERVER['HTTP_USER_AGENT'], 'NowJobApp_iOS') === false;
if ($is_ios_safari && strpos($app_base_url, 'http://') === 0) {
    $app_base_url = 'https://' . substr($app_base_url, 7);
}

// Apple 로그인도 app_base_url 기반으로 (하드코딩 제거)
$apple_redirect = $app_base_url . "/member/apple_login_callback";
```

**효과**:
- 모든 OAuth 제공자 redirect_uri를 동일한 `app_base_url` 기반으로 생성
- 프로토콜 불일치 방지

## 테스트 방법

### 1. iOS Safari에서 디버그 엔드포인트 접속

```
https://nowjob.co.kr/member/oauth_debug
```

**반환 JSON 확인**:
```json
{
  "app_base_url": "https://nowjob.co.kr",
  "is_ios_safari": true,
  "naver_redirect": "https://nowjob.co.kr/member/naver_login_callback",
  "google_redirect": "https://nowjob.co.kr/member/google_login_callback",
  "wechat_redirect": "https://nowjob.co.kr/member/wechat_login_callback"
}
```

모든 redirect_uri가 **https://** 로 시작하는지 확인.

### 2. 서버 로그 확인

```bash
tail -f /var/log/apache2/error.log | grep "SNS Redirect URI Debug"
```

**로그 출력**:
```
app_base_url: https://nowjob.co.kr
is_ios_safari: true
x-forwarded-proto: https
https: (또는 포트 443)
```

### 3. 실제 로그인 테스트

iOS Safari에서:
1. 로그인 페이지 접속
2. 네이버/구글/위챗 버튼 클릭
3. OAuth 제공자에서 인증 후 리다이렉트
4. 콜백 성공 여부 확인

## 환경 변수 권장 설정 (추가 강화)

`.env` 파일에 명시적으로 설정:

```env
# production
APP_BASE_URL=https://nowjob.co.kr
APP_MAIN_DOMAIN=nowjob.co.kr

# development
APP_DEV_DOMAIN=dev.nowjob.co.kr
```

이렇게 설정하면 프록시 설정에 관계없이 항상 올바른 URL이 사용됨.

## 주의사항

1. **API 요청**: iOS Safari에서 쿠키가 `SameSite=None` + `Secure`여야 함
   - `index.php`에서 이미 처리됨: `session.cookie_samesite='none'`

2. **개발 환경**: `dev.nowjob.co.kr`은 HTTP 허용
   - `dev.*` 접두사로 시작하는 도메인은 HTTP 강제 변환 제외

3. **다른 OAuth 제공자**: 동일한 패턴으로 처리 가능
   - Kakao, Apple도 `ensure_https_for_ios()` 사용 권장

## 향후 개선사항

1. `ensure_https_for_ios()` → 공유 라이브러리로 이동
2. OAuth redirect_uri 검증 헬퍼 함수 추가
3. 로그인 오류 시 더 자세한 프로토콜 정보 표시
