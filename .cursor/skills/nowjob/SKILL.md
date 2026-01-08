---
name: nowjob
description: This is a new rule
---

# Overview

Insert overview text here. The agent will only see this should they choose to apply the rule.
# 프로젝트 규칙 (Project Rules)

## 1. 기술 스택

### 백엔드
- **프레임워크**: CodeIgniter 3.1.11
- **언어**: PHP 5.3+ (5.4 이상 권장)
- **데이터베이스**: MySQL, MongoDB (채팅 서버용)
- **서버**: Node.js 16 (채팅 서버)

### 프론트엔드
- **기본 라이브러리**: jQuery 3.2.1 / 3.5.1
- **프레임워크**: 
  - 일반 페이지는 jQuery 기반으로 작성
  - 채팅 기능에만 Vue.js 사용 (제한적 사용)
- **React / Vue 문법은 일반 페이지에서 사용하지 않는다**
- **실시간 통신**: Socket.IO
- **푸시 알림**: Firebase (안드로이드 앱용)

### 공통
- **에디터**: SmartEditor2, Summernote, CKEditor
- **차트**: Chart.js
- **기타**: jQuery UI, Popper.js, Tippy.js

## 2. 설계 원칙

### 아키텍처
- **MVC 패턴**: CodeIgniter의 MVC 구조를 준수
- **비즈니스 로직과 UI 로직은 분리한다**
- **공통 코드 공유**: `application/common/` 디렉토리 활용
- **SHAREDPATH**: 공통 라이브러리 및 헬퍼는 SHAREDPATH를 통해 공유
- 어떤 도메인(업무 영역)에 속하느냐에 따라로직·UI·레이아웃 책임을 나누기
### JavaScript 구조
- **공통 함수는 전용 파일에만 작성한다**
  - 웹: `resource/shop/web/js/add.js`
  - 모바일: `resource/shop/mobile/js/add.js`
  - 관리자: `resource/admin/js/common.js`
  - 채팅: `chatting/js/common.js`
- **페이지별 스크립트는 별도 파일로 분리**
- **공통 함수는 재사용 가능하도록 설계**

### 관심사 분리 (Separation of Concerns)
- **Controller**: 라우팅 및 요청 처리
- **Model**: 데이터베이스 접근 및 비즈니스 로직
- **View**: 프레젠테이션 로직만 담당
- **Library**: 재사용 가능한 기능은 라이브러리로 분리

### 변경 영향 최소화 (Minimize Change Impact)
- **공통 함수 수정 시 영향 범위를 최소화**
- **기존 API 호환성 유지**
- **점진적 리팩토링 원칙**

### 변경에 강한 구조
- **의존성 최소화**: 모듈 간 결합도 낮추기
- **인터페이스 기반 설계**: 추상화를 통한 유연성 확보
- **설정 파일 분리**: 환경별 설정 관리

### 유지보수와 확장성을 고려한 설계
- **명확한 네이밍**: 변수명과 함수명은 의도를 명확히 표현
- **주석 작성**: 복잡한 로직은 한글 주석으로 설명
- **에러 처리**: 예외 상황에 대한 적절한 처리
- **로깅**: 디버깅을 위한 로그 기록

## 3. 금지 사항

### JavaScript
- **inline JavaScript 작성 금지**
  - HTML 내 `<script>` 태그 직접 작성 금지
  - 이벤트 핸들러는 외부 JS 파일에서 처리
  - 예외: 외부 라이브러리 초기화 등 필수적인 경우만 허용
- **HTML 구조를 의존하는 DOM 탐색 금지**
  - `$('.class-name')` 같은 클래스 기반 탐색 지양
  - **data-* 속성과 dataset 사용 권장**
  - 예: `element.dataset.userId` 또는 `$(element).data('user-id')`
- **React 문법 사용 금지** (일반 페이지)
- **Vue.js는 채팅 기능에만 제한적으로 사용**

### PHP
- **PHP 버전에 맞는 코드 작성**
  - PHP 5.3+ 호환성 유지
  - 최신 PHP 문법 사용 시 호환성 확인 필수
- **보안 취약점 방지**
  - SQL Injection 방지: 쿼리 바인딩 사용
  - XSS 방지: 출력 시 이스케이프 처리
  - CSRF 방지: 토큰 검증

### 기타
- **하드코딩 금지**: 설정값은 설정 파일로 분리
- **디버그 코드 커밋 금지**: `console.log`, `var_dump` 등 제거
- **주석 처리된 코드 정리**: 불필요한 주석 코드 삭제

## 4. 코드 스타일

### JavaScript
- **변수 선언**: `var` 사용 가능 (레거시 코드 유지)
  - 새 코드 작성 시 `const`, `let` 사용 권장
- **함수명**: 동사로 시작
  - 예: `getUserInfo()`, `makeAjaxRequest()`, `updateUserData()`
  - 공통 함수 예: `comAjax()`, `commonAlert()`, `showAjaxLoading()`
- **이벤트 핸들러**: jQuery 이벤트 위임 사용
  - `$(document).on('click', '.selector', handler)` 패턴
- **AJAX 통신**: 공통 함수 `comAjax()` 사용
  ```javascript
  comAjax(url, params, function(result) {
      // 성공 처리
  });
  ```

### PHP
- **네이밍 컨벤션**: CodeIgniter 스타일 준수
  - 클래스명: PascalCase (예: `MY_Controller`)
  - 메서드명: camelCase (예: `getUserInfo()`)
  - 변수명: snake_case (예: `$user_id`)
- **주석**: 한글로 작성
  ```php
  /**
   * 사용자 정보를 가져온다
   * @param int $user_id 사용자 ID
   * @return array 사용자 정보
   */
  function getUserInfo($user_id) {
      // 구현
  }
  ```
- **에러 처리**: try-catch 또는 조건문으로 처리
- **데이터 검증**: 입력값 검증 필수

### HTML/CSS
- **data-* 속성 사용**: JavaScript와의 데이터 전달
  ```html
  <button data-user-id="123" data-action="delete">삭제</button>
  ```
- **클래스명**: 의미 있는 이름 사용 (BEM 방식 권장)
- **인라인 스타일 지양**: CSS 파일로 분리

### 파일 구조
- **View 파일**: `application/shop/views/{web|mobile}/{controller}/{method}.php`
- **JavaScript**: `resource/shop/{web|mobile}/js/{기능}.js`
- **CSS**: `resource/shop/{web|mobile}/css/{기능}.css`
- **공통 라이브러리**: `application/common/libraries/`

## 5. 추가 규칙

### 버전 관리
- **Git 브랜치 전략**: main, develop, feature 브랜치 활용
- **커밋 메시지**: 명확한 설명 작성

### 성능 최적화
- **리소스 캐싱**: `getCashJs()`, `getCashCss()` 함수 활용
- **이미지 최적화**: 적절한 크기와 포맷 사용
- **AJAX 중복 요청 방지**: 로딩 상태 관리

### 보안
- **세션 관리**: 안전한 세션 설정
- **쿠키 보안**: HTTPS 환경에서 Secure, HttpOnly 설정
- **입력 검증**: 서버 측 검증 필수
- **권한 체크**: 각 기능별 권한 확인

### 테스트
- **기능 테스트**: 주요 기능 동작 확인
- **크로스 브라우저**: 주요 브라우저 호환성 확인
- **모바일 대응**: 반응형 디자인 검증

---

**작성일**: 2026-01-08  
**최종 수정일**: 2026-01-08
