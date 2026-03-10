# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 언어 및 커뮤니케이션 규칙

- **기본 응답 언어**: 한국어
- **코드 주석**: 한국어로 작성
- **커밋 메시지**: 한국어로 작성
- **문서화**: 한국어로 작성
- **변수명/함수명**: 영어 (코드 표준 준수)

## 프로젝트 개요

구인/구직 플랫폼 (잡 보드). PHP CodeIgniter 3.x + Node.js Socket.IO 기반의 멀티 도메인 웹 서비스.

## 주요 명령어

### PHP 애플리케이션

Apache 기반으로 실행되며 별도 빌드 과정 없음. 파일 수정 즉시 반영.

### 채팅 서버 (Node.js)

```bash
cd /website/dev_nowapk/chatting/chat

# 개발 실행
node chat_server.js

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
- **컨트롤러**: `application/shop/controllers/` (shop), `application/api/controllers/` (API)
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

## 브랜치 전략

- `develop` — 개발 브랜치 (현재)
- `master` — 프로덕션 배포 브랜치 (PR 대상)
