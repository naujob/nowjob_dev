# 하드코딩 URL 일괄 치환 규칙 (View/CSS/JS)

## 범위
- 대상: `application/shop/views/**/*.php`, `chatting/**/*.php|js|css`
- 1차 점검 기준: `nowjob.co.kr`, `dev.nowjob.co.kr`, `nowapk.co.kr`, `:3030`, `:3031`

## 공통 원칙
- 도메인 문자열 직접 사용 금지: `https://nowjob.co.kr/...`
- 내부 리소스는 절대도메인 대신 루트경로 사용: `"/resource/..."`
- 채팅 소켓은 공통 설정값 사용:
  - PHP(CI): `$this->config->item('socket_base_url')`, `$this->config->item('socket_io_script_url')`
  - JS: `window.__CHAT_BASE_URL` 또는 서버가 내려준 `base_url` 변수
- 외부 제휴 API/NICE/카카오/네이버/TMap 같은 외부 URL은 치환 제외

## 일괄 치환 규칙

### Rule A: 내부 정적 리소스 URL
- 찾기(정규식):
  - `https?:\/\/(dev\.)?nowjob\.co\.kr\/resource\/`
- 바꾸기:
  - `/resource/`
- 예:
  - `url("https://nowjob.co.kr/resource/shop/web/img/a.svg")`
  - `url("/resource/shop/web/img/a.svg")`

### Rule B: 내부 페이지 이동 URL
- 찾기(정규식):
  - `https?:\/\/(dev\.)?nowjob\.co\.kr\/([A-Za-z0-9_\/\-\?\=\&\#\.]+)`
- 바꾸기(권장):
  - PHP 문자열: `site_url('...')` 또는 `base_url('...')`
  - JS 문자열: 상대경로 `"/..."` (동일 도메인 이동일 때)
- 예:
  - `location.href = "https://nowjob.co.kr/recruit/job_list";`
  - `location.href = "/recruit/job_list";`

### Rule C: Socket.IO 스크립트/접속 주소
- 금지:
  - `https://nowjob.co.kr:3030/socket.io/socket.io.js`
  - `const base_url = "https://nowjob.co.kr:3030";`
- 대체:
  - `<script src="<?= $this->config->item('socket_io_script_url') ?>"></script>`
  - `const base_url = "<?= rtrim($this->config->item('socket_base_url'), '/') ?>";`

### Rule D: 메타/OG canonical URL
- 금지:
  - `https://nowjob.co.kr/`
- 대체:
  - `<?= rtrim($this->config->item('app_base_url') ?: base_url(), '/') ?>/`

## 치환 제외(명시)
- `https://developers.kakao.com/...`
- `https://nice.checkplus.co.kr/...`
- `https://nid.naver.com/...`, `https://openapi.naver.com/...`
- `https://apis.openapi.sk.com/...`
- `https://message.ppurio.com/...`
- CDN/폰트/jQuery/SocketIO CDN URL

## 추천 실행 순서
1. 레이아웃(`_layout/basic.php`)의 공통 URL 변수화 먼저 적용
2. CSS/inline style의 `/resource/` 치환 일괄 적용
3. JS `location.href`를 상대경로로 치환
4. 최종 `rg` 재검증 후 예외 케이스만 수동 보정

## 검증 명령어
```bash
rg -n "nowjob\.co\.kr|dev\.nowjob\.co\.kr|nowapk\.co\.kr|:3030|:3031" application/shop/views chatting --glob "*.php" --glob "*.js" --glob "*.css"
```

