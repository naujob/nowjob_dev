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