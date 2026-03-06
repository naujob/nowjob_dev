**Overview**
이 프로젝트는 CodeIgniter 3 기반의 PHP 웹 애플리케이션을 중심으로, 관리자/사용자(웹·모바일) UI와 별도 Node.js 실시간 채팅 서버가 결합된 구조입니다. 프런트 컨트롤러(`index.php`)에서 도메인/서브도메인과 환경 변수를 기준으로 `application/admin` 또는 `application/shop`으로 분기하며, 공용 코드는 `application/common`에 집중되어 있습니다. 실시간 채팅은 `chatting/chat`에 있는 Socket.IO 서버가 담당하고, MongoDB·MySQL·FCM(푸시)을 함께 사용합니다.

**Context Diagram**
```text
[Browser/WebView/App]
  -> HTTP(S)
     -> PHP (CodeIgniter) index.php
        -> application/shop (사용자 서비스)
        -> application/admin (관리자)
        -> application/common (공용 라이브러리/모델/헬퍼)
     -> MySQL (주 데이터)
     -> MongoDB (채팅/알림 일부)
     -> Memcached (캐시)
  -> WebSocket/Socket.IO
     -> Node.js Chat Server (chatting/chat)
        -> MongoDB (채팅룸/메시지)
        -> MySQL (회원/디바이스 토큰)
        -> Firebase Admin (FCM 푸시)
```

**Codebase Layout**
- `application/shop`: 사용자-facing 웹/모바일(구직·이력서·마이페이지·채팅 등) 컨트롤러/뷰.
- `application/admin`: 관리자 UI 및 운영 도구.
- `application/common`: 공용 라이브러리·헬퍼·모델·설정(autoloader, DB, REST, 외부 연동).
- `system`: CodeIgniter 코어.
- `chatting`: 실시간 채팅 관련 PHP UI + Node.js 채팅 서버.
- `resource`: 정적 자원(관리자/사용자 JS/CSS/이미지).
- `outer`: 별도 정적 페이지/자산.
- `logs`, `php_log`, `application/*/logs`, `chatting/chat/logs`: 로그 저장 위치.

**Bootstrap And Routing**
- 프런트 컨트롤러는 `index.php`에서 환경(`APP_ENV`)과 도메인 규칙을 해석하고, `admin`/`api`/기본(`shop`) 서브도메인에 따라 `application/*` 폴더를 선택합니다.
- 공용 경로는 `SHAREDPATH`로 고정되어 `application/common`이 공유 모듈 역할을 합니다.
- `config/functions.php`와 `config/initialize.php`에서 공용 컨트롤러/클래스 로딩이 선행되며, 이후 CodeIgniter 부트스트랩이 실행됩니다.
- 라우팅 기본값은 `application/shop/config/routes.php`와 `application/admin/config/routes.php`의 `default_controller = 'home'`입니다.
- `application/common/libraries/MY_Router.php`는 하위 폴더 컨트롤러 탐색을 지원합니다.

**Application Layer**
- 사용자 영역(Shop)
- `application/shop/controllers`는 `member`, `recruit`, `resume`, `chat`, `mypage`, `community` 등 도메인 기능을 담당합니다.
- `application/shop/views/web`, `application/shop/views/mobile`에 웹/모바일 템플릿이 분리되어 있으며, `application/common/libraries/Layout.php`에서 디바이스 타입에 따라 렌더링 경로를 선택합니다.
- `RES_PATH`는 정적 리소스 루트(`/resource/shop/...`)로 정의됩니다.

- 관리자 영역(Admin)
- `application/admin/controllers`와 `application/admin/controllers/admin` 하위 컨트롤러(회원/주문/설정/게시판 등)로 구성됩니다.
- 관리자 레이아웃도 `Layout.php`에서 세션/메뉴 기반으로 렌더링됩니다.

- 공용 영역(Common)
- 공용 설정/라이브러리/헬퍼/모델은 `application/common`에 집중되어 있고, autoload는 `application/common/config/autoload.php`에서 처리됩니다.
- 공용 헬퍼(`application/common/helpers/common_helper.php`)는 MongoDB 연결, JSON 응답, 세션/입력 유틸 등 횡단 관심사를 제공합니다.

**Realtime Chat Subsystem**
- Node.js 채팅 서버는 `chatting/chat/chat_server.js`에서 Socket.IO 기반으로 동작합니다.
- 기본 포트는 운영 3030, 개발 3031이며 `chatting/chat/config/config.js`와 환경 변수(`CHAT_*`, `APP_ENV`)로 제어합니다.
- 환경 파일은 `chatting/chat/config/env_loader.js`에서 `.env`, `.env.development`, `.env.production`을 로딩합니다.
- 데이터 저장소는 MongoDB(채팅룸/메시지), MySQL(회원/디바이스/FCM 토큰) 혼용 구조입니다.
- FCM 푸시는 Firebase Admin SDK로 발송하며 서비스 계정 키 파일은 `chatting/chat/config/firebase-service-account*.json`로 관리됩니다.
- `chatting/chat/ecosystem.config.js`는 PM2 실행 구성(개발/운영)을 제공하고 있습니다.
- PHP 측에서는 `application/common/config/config.php`의 `socket_base_url` 및 `socket_io_script_url`로 클라이언트 스크립트 경로를 제공하며, `application/shop/controllers/recruit.php`에 채팅 서버 헬스 체크/재기동 로직이 존재합니다.

**Data Stores**
- MySQL
- CodeIgniter DB 설정은 `application/common/config/{development,production}/database.php`를 우선 사용하며, `application/common/config/database.php`는 fallback입니다.
- 대부분의 도메인 데이터(회원/채용/결제/운영 등)는 MySQL을 중심으로 처리됩니다.

- MongoDB
- `application/common/config/config.php`의 `mongo_uri`, `mongo_db`를 사용합니다.
- PHP 레이어는 `MongoClient`를 통해 알림/채팅 관련 컬렉션에 접근합니다.

- Memcached
- 캐시 서버는 `application/common/config/memcached.php`에 정의되어 있습니다.

**External Integrations**
- 결제: KG이니시스 라이브러리 `application/common/pg/inicis`.
- 로그인: `application/common/libraries/KakaoLogin.php` (카카오 로그인 연동).
- 알림/메시징: `application/common/config/ppurio.php` (알림톡/문자), `chatting/chat`의 Firebase Admin(FCM).
- 배송조회: `application/common/config/sweettracker.php`.
- 엑셀: `application/common/third_party/PHPExcel` 및 `application/common/libraries/Excel.php`.

**Configuration And Environments**
- 환경 판단은 `APP_ENV` 또는 `dev.` 서브도메인 기반이며, 쿠키/세션/HTTPS 정책은 `index.php`에서 설정됩니다.
- 공용 설정은 `application/common/config` 하위에 집중되어 있고, 개발/운영 분기는 `application/common/config/development`, `application/common/config/production` 폴더가 우선됩니다.
- 채팅 서비스는 `CHAT_*` 환경 변수를 우선 사용하며, 기본값은 `chatting/chat/config/config.json`을 참조합니다.

**Operational Scripts And Logs**
- 운영/점검 스크립트: `check_chat_logs.php`, `check_room_data.js`, `setup_users.js` 등.
- 로그 위치: `application/*/logs`, `logs`, `php_log`, `chatting/chat/logs`, `server_error.log`.
- 로그 로테이션 구성: `logrotate_application.conf`.

**Key Request Flows**
1. 사용자 웹/모바일 페이지
- Client -> `index.php` -> `application/shop` 컨트롤러 -> 모델/DB -> `Layout::shop()` -> `application/shop/views/{web|mobile}` 렌더링.

2. 관리자 페이지
- Client -> `index.php` -> `application/admin` 컨트롤러 -> 공용 라이브러리/모델 -> `Layout::admin()` -> `application/admin/views` 렌더링.

3. 채팅 메시지
- Client Socket.IO -> `chatting/chat/chat_server.js` -> MongoDB(메시지/룸) + MySQL(사용자/FCM) -> FCM 푸시 -> 클라이언트 수신.

**Observations And Recommendations**
- `application/common/config/*` 및 `chatting/chat/config/config.json`에 민감 정보(키/비밀번호)가 직접 포함되어 있습니다. 운영 보안을 위해 환경 변수로 이동하고 리포지토리 내에는 샘플 템플릿만 남기는 구조를 권장합니다.
- `index.php`에서 `api` 서브도메인 분기가 있으나 `application/api` 폴더는 현재 보이지 않습니다. API 전용 앱이 필요하다면 구조를 확장할 수 있습니다.

