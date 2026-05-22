# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 언어 및 커뮤니케이션 규칙

- **기본 응답 언어**: 한국어
- **코드 주석**: 한국어로 작성 / 주요함수와 변수에 대한 설명 주석 넣기
- **커밋 메시지**: 한국어로 작성
- **문서화**: 한국어로 작성
- **변수명/함수명**: 영어 (코드 표준 준수)

## 프로젝트 개요

구인/구직 플랫폼 (잡 보드). PHP CodeIgniter 3.x + Node.js Socket.IO 기반의 멀티 도메인 웹 서비스.

### 환경 구성

**로컬 개발 환경** (`/home/nowapk/dev_nowapk`)
- WSL2 Linux (Windows Subsystem for Linux 2)
- PHP 8.3.6 (현재 개발용)
- 코드 스타일: PHP 5.4 호환성 준수

**원격 운영 서버** (실운영 배포 대상)
- **OS**: CentOS Linux release 7.9.2009 (Core)
- **커널**: 3.10.x
- **PHP**: 5.4.16 (cli) (built: Apr 1 2020 04:07:17)
- **CodeIgniter**: 3.1.11
- **Node.js**: (소켓 서버용)

**포트 구성** (운영 서버)
- Apache: 80/443 (운영), 8080 (추가 리슨)
- Node.js 채팅 서버: 3030 (운영), 3031 (개발)
- MySQL: 3306
- MongoDB: 27017 (로컬)

## 주요 명령어

### PHP 애플리케이션

Apache 기반으로 실행되며 별도 빌드 과정 없음. 파일 수정 즉시 반영.

### 채팅 서버 (Node.js)

```bash
cd /website/dev_nowapk/chatting/chat

# 개발 실행
pm2 restart chat_server_prod (운영)
pm2 restart chat_server_dev (개발)


# PM2로 프로덕션 실행
pm2 start ecosystem.config.js --name "chat_server_prod"
pm2 start ecosystem.config.js --name "chat_server_dev"
pm2 list        # 실행 상태 확인
pm2 logs        # 로그 확인
```

### 환경 설정

채팅 서버 환경 변수는 `chatting/chat/.env` 파일로 관리. 템플릿: `chatting/chat/.env.example`

## 아키텍처

### 멀티 애플리케이션 구조

`/index.php`가 서브도메인에 따라 라우팅:
- `admin.*` → `application/admin/`
- `api.*` → `application/api/`
- 그 외 → `application/shop/` (메인 서비스)
- `dev.*` 접두사: 개발 환경

### CodeIgniter MVC

- **모델**: `application/common/models/` — 공유 데이터 모델 (MemberModel, RecruitModel, GoodsModel 등)
- **컨트롤러**: `application/shop/controllers/` (shop)
- **뷰**: `application/shop/views/mobile/` (모바일), `application/shop/views/web/` (웹)
- **공유 라이브러리**: `application/common/libraries/` — Common.php, User.php, Layout.php, KakaoLogin.php 등

### 공유 설정

`application/common/config/config.php`가 핵심 설정 관리:
- 소켓 서버 URL/포트 (3030)
- MongoDB 연결
- Firebase FCM 설정
- 기본 URL 및 도메인 라우팅

### 실시간 채팅 서버

`chatting/chat/` — Node.js + Socket.IO + MongoDB + MySQL:
- `chat_server.js` — 메인 서버 진입점
- `controllers/` — user_controller.js, chat_controller.js, alarm_controller.js
- `models/` — chat_model.js, user_model.js (MongoDB + MySQL 이중 DB)

### 정적 자산

- `resource/shop/mobile/` — 모바일 CSS/JS/이미지
- `resource/shop/web/` — PC CSS/JS/이미지
- `resource/uploads/` — 사용자 업로드 파일

### 아이콘 라이브러리

- **FontAwesome** — 모든 UI 아이콘은 FontAwesome 사용
- 로드 위치: `application/shop/views/web/_layout/basic.php`,`application/shop/views/mobile/_layout/basic.php`
- 사용 형식: `<i class="fa fa-icon-name"></i>`

### 환경 감지

코드 내에서 `APP_ENV` 환경 변수 또는 경로명(`/dev_nowapk` vs `/nowapk`)으로 dev/prod 자동 판별.



## PHP 코딩 표준

### 프로젝트 PHP version 5.4 버전

- PHP 버전에 맞는 문법 사용(import)

### Null 병합 처리

**PHP ?? null 병합 연산자 사용 금지**

레거시 PHP 버전 호환성을 위해 `??` 연산자를 사용하지 말 것.

```php
// ❌ 사용 금지
$value = $array['key'] ?? 'default';

// ✅ 사용할 것
$value = isset($array['key']) ? $array['key'] : 'default';

// 또는 helper 함수
$value = in_array_return($array['key'], $checked, 'check') ? 'checked' : '';
```

### Refactoring 전략

- 함수가 있는지 확인하고 만들기 (function_exists)

## Git 커밋 규칙

- **커밋은 항상 사용자에게 물어보고 진행**
- 수정 작업 완료 후 변경사항을 정리해서 보여주고 "커밋하시겠습니까?"라고 묻기
- 사용자 승인 없이 자동으로 커밋하지 않기

## JavaScript 상태관리 (Data 속성 패턴)

### 원칙: 역할 분명히 하기

**상태**: HTML의 `data-*` 속성으로 관리
**로직**: JavaScript로 상태 변경 담당
**표현**: CSS에서 `[data-*]` 선택자로 스타일 적용

```html
<!-- 상태를 data 속성으로 정의 -->
<div id="menu" data-state="closed" data-loading="false">
  <button id="menu-toggle">메뉴</button>
</div>
```

```javascript
// JavaScript: 상태 변경 담당
const menu = document.getElementById('menu');
document.getElementById('menu-toggle').addEventListener('click', () => {
  const currentState = menu.getAttribute('data-state');
  const newState = currentState === 'closed' ? 'open' : 'closed';
  menu.setAttribute('data-state', newState);
});

// 로딩 상태 업데이트
function setLoading(isLoading) {
  menu.setAttribute('data-loading', isLoading ? 'true' : 'false');
}
```

```css
/* CSS: data 속성으로 스타일 적용 (역할 분명) */

/* 닫힌 상태 */
#menu[data-state="closed"] {
  display: none;
}

/* 열린 상태 */
#menu[data-state="open"] {
  display: block;
  animation: slideIn 0.3s ease-out;
}

/* 로딩 상태 */
#menu[data-loading="true"] {
  opacity: 0.6;
  pointer-events: none;
}

#menu[data-loading="true"] button {
  cursor: wait;
}
```

### 주요 data 속성 목록

| 속성 | 값 | 용도 |
|------|-----|------|
| `data-state` | open, closed, loading, error | UI 상태 |
| `data-loading` | true, false | 로딩 중 여부 |
| `data-active` | true, false | 활성/비활성 |
| `data-mode` | edit, view, preview | 모드 전환 |
| `data-selected` | true, false | 선택 상태 |
| `data-dirty` | true, false | 폼 수정 여부 |
| `data-visible` | true, false | 표시/숨김 |

### 예시: 폼 상태관리

```html
<form id="recruit-form" data-dirty="false" data-mode="edit">
  <input type="text" id="title" placeholder="공고 제목">
  <button type="submit">저장</button>
  <span class="unsaved-warning">저장되지 않은 변경사항이 있습니다</span>
</form>
```

```javascript
const form = document.getElementById('recruit-form');
const titleInput = document.getElementById('title');

// 입력 변경 시 상태 업데이트
titleInput.addEventListener('change', () => {
  form.setAttribute('data-dirty', 'true');
});

// 저장 후 상태 초기화
form.addEventListener('submit', (e) => {
  e.preventDefault();
  form.setAttribute('data-mode', 'loading');
  
  fetch('/api/recruit/save', {
    method: 'POST',
    body: JSON.stringify({ title: titleInput.value })
  })
  .then(() => {
    form.setAttribute('data-dirty', 'false');
    form.setAttribute('data-mode', 'view');
  })
  .catch(() => {
    form.setAttribute('data-mode', 'edit');
  });
});
```

```css
/* 저장되지 않은 경고 */
#recruit-form[data-dirty="false"] .unsaved-warning {
  display: none;
}

#recruit-form[data-dirty="true"] .unsaved-warning {
  display: block;
  color: #f44336;
}

/* 로딩 중 비활성화 */
#recruit-form[data-mode="loading"] button {
  opacity: 0.5;
  pointer-events: none;
}

/* 보기 모드 */
#recruit-form[data-mode="view"] input {
  pointer-events: none;
  background-color: #f5f5f5;
}
```

### 장점

- **명확한 역할 분리**: HTML(상태) ↔ JS(로직) ↔ CSS(표현)
- **추적 가능**: 상태가 명시적으로 태그에 저장되어 DevTools로 쉽게 확인
- **재사용성**: 같은 data 속성 패턴이 여러 요소에 적용 가능
- **유지보수성**: CSS와 JS 간의 의존성이 명확함
- **성능**: CSS 클래스 토글보다 더 명확하고 상태 추적이 쉬움

## 서버사이드 vs 클라이언트사이드 렌더링 선택 기준

### 언제 서버(PHP)에서 처리할 것인가

**PHP에서 처음부터 완성해서 보내야 할 경우:**

```
✅ 처음부터 알 수 있는 값
  → 서버에서 완성해서 보내는 게 가장 이상적
```

| 상황 | 처리 위치 | 이유 |
|------|---------|------|
| 현재 URL 기반 페이지 상태 | **PHP** | 서버가 현재 URL을 알고 있음 → 처음부터 완전한 HTML 렌더링 가능 |
| 사용자 로그인 상태/권한 | **PHP** | 세션 정보는 서버에만 있음 |
| DB 기반 동적 데이터 | **PHP** | 서버만 DB 접근 가능 |
| 첫 렌더링 시점에 결정되는 값 | **PHP** | 리플로우/리페인트 제거, 성능 최적화 |

**예시: 바텀 네비게이션 활성 아이콘**

```php
// ✅ 올바른 방법: PHP에서 처음부터 완성
<?php
$_uri = $_SERVER['REQUEST_URI'];
$_is_active = (strpos($_uri, '/recruit/job_list') !== false);
?>
<a href="/recruit/job_list" 
   data-active="<?= $_is_active ? 'true' : 'false' ?>"
   style="background-image: url('<?= $_is_active ? '/recruit_click.png' : '/recruit.png' ?>')">
```

```javascript
// ❌ 피해야 할 방법: DOMContentLoaded 후 JS로 변경
window.addEventListener('DOMContentLoaded', function() {
  // 이미 렌더링된 후 DOM 수정 → 리플로우/리페인트 발생
  // img src 교체 → 이미지 fetch → 깜빡임
  updateNavIcon(); 
});
```

### 언제 클라이언트(JS)에서 처리할 것인가

| 상황 | 처리 위치 | 이유 |
|------|---------|------|
| 사용자 상호작용 후 상태 변경 | **JS** | 클릭, 입력 등 사용자 이벤트에만 반응 |
| SPA 라우팅 | **JS** | 페이지 새로고침 없이 상태 변경 |
| 실시간 데이터 반영 | **JS** | WebSocket/API로 받은 데이터 즉시 적용 |
| 조건이 클라이언트에서만 결정될 때 | **JS** | 서버가 알 수 없는 로컬 상태 |

---

## 성능 영향

서버에서 처음부터 완성된 HTML을 보내면:
- ✅ **리플로우 0**: Paint 전에 모든 레이아웃이 결정됨
- ✅ **리페인트 0**: 이미지 교체/텍스트 삽입 없음
- ✅ **JS 계산 감소**: 불필요한 DOM 탐색/수정 제거
- ✅ **첫 방문부터 최적화**: 캐시나 조건 없이 항상 빠름