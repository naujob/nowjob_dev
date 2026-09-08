# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 📋 규칙 파일 가이드

자세한 규칙은 `.claude/rules/` 디렉토리에서 확인하세요:

| 파일 | 내용 |
|------|------|
| `loging.md` | PHP, Node.js, 모바일 JS 로그 출력 및 확인 방법 |
| `architecture.md` | 프로젝트 구조, MVC, 데이터베이스 설정 |
| `php_coding_standards.md` | PHP 5.4 호환성, Null 병합 처리, 리팩토링 전략 |
| `javascript_state_management.md` | Data 속성 패턴을 활용한 상태 관리 |
| `rendering_strategy.md` | 서버사이드/클라이언트사이드 렌더링 선택 기준 |

---

## 언어 및 커뮤니케이션 규칙

- **기본 응답 언어**: 한국어
- **코드 주석**: 한국어로 작성 / 주요함수와 변수에 대한 설명 주석 넣기
- **커밋 메시지**: 한국어로 작성
- **문서화**: 한국어로 작성
- **변수명/함수명**: 영어 (코드 표준 준수)

## 프로젝트 개요

구인/구직 플랫폼 (잡 보드). PHP CodeIgniter 3.x + Node.js Socket.IO 기반의 멀티 도메인 웹 서비스.

### 프로젝트 파일 경로

| 프로젝트 | 경로 |
|---------|------|
| **웹 서버 (로컬)** | `c:\Users\ssi06\nowapk_dev\dev_nowapk` |
| **웹 서버 (원격)** | `/website/dev_nowapk` (SFTP 배포 대상) |
| **iOS (Swift)** | `C:\Users\ssi06\Documents\nowjob_google\nowjobswift 2510\nowjobswift\nowjobswift` |
| **안드로이드 (Java)** | `C:\Users\ssi06\Documents\nowjob_google\nowjob_android_2509_new` |
| **계획/문서** | `C:\Users\ssi06\.claude\plans` |

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

# 개발 서버 재시작
pm2 restart chat_server_dev

# 운영 서버 재시작
pm2 restart chat_server_prod

# PM2로 프로덕션 실행
pm2 start ecosystem.config.js --name "chat_server_prod"
pm2 start ecosystem.config.js --name "chat_server_dev"

# 상태 확인
pm2 list
```

**로깅 및 상세 명령어**: `.claude/rules/loging.md` 참조

### 환경 설정

채팅 서버 환경 변수는 `chatting/chat/.env` 파일로 관리. 템플릿: `chatting/chat/.env.example`

## 아키텍처

### 뷰 레이어 (View Layer)

**모바일 뷰** (`application/shop/views/mobile/`)
- 안드로이드 (Java), iOS (Swift) 앱과 연동
- WebView를 통한 하이브리드 앱 구현
- 모바일 최적화된 반응형 디자인

**웹 뷰** (`application/shop/views/web/`)
- PC 브라우저용 완전한 웹 UI
- 독립적인 레이아웃 및 스타일

→ 상세 내용: `.claude/rules/architecture.md` 참조

## PHP 코딩 표준

→ 상세 내용: `.claude/rules/php_coding_standards.md` 참조

## Git 커밋 규칙

- **커밋은 항상 사용자에게 물어보고 진행**
- 수정 작업 완료 후 변경사항을 정리해서 보여주고 "커밋하시겠습니까?"라고 묻기
- 사용자 승인 없이 자동으로 커밋하지 않기

## 수정 후 워크플로우

코드 수정 후 반드시 다음 단계를 거치세요:

1. **문법 체크** - 수정한 코드에 문법 오류가 없는지 확인
2. **린트 통과** - 프로젝트의 코드 스타일 규칙 준수 확인
3. **테스트 실행** - 수정 전후 동작 차이 확인 (가능한 경우)
4. **리뷰 요청** - 사용자에게 변경사항 설명 및 승인 요청

```
코드 수정 → 문법/린트 체크 → 테스트 → 리뷰 요청 → 커밋
```

## JavaScript 상태관리 (Data 속성 패턴)

→ 상세 내용: `.claude/rules/javascript_state_management.md` 참조

## 서버사이드 vs 클라이언트사이드 렌더링 선택 기준

→ 상세 내용: `.claude/rules/rendering_strategy.md` 참조