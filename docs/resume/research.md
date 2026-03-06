# Research: resume_write1.php + resume_write4.php 통합

## 대상 파일
- source A: `application/shop/views/mobile/mypage/resume_write1.php`
- source B: `application/shop/views/mobile/mypage/resume_write4.php`

## resume_write1.php 핵심 구조/동작
- 상단
  - 카카오 지도 SDK 로드 (`//dapi.kakao.com/v2/maps/sdk.js?...libraries=services`).
  - `html.autofill-hide` 적용 및 `autofill=1` 파라미터 처리.
- UI 구조
  - `.page.page-write` 내부 `form#frm` 단일 폼.
  - 필드: 이력서명, 국적, 희망업무(2개 제한), 희망지역(2개 제한), 희망급여(2개 제한 + 협의), 근무형태(2개 제한 + 협의).
  - 하단 `sticky-bottom`에 `#btnStep`(“다음”)만 존재.
- 주소/지역 처리
  - 주소 목록 렌더링/추가/삭제 (`createAddressListItem`, `rebuildAddressList`, `ensureAddressAddButton`).
  - 주소 정리 함수 `fRemoveBigAdrJS` 적용.
  - 카카오 geocoder로 지번 동(읍/면)까지만 좌표 보정.
  - 서버 주소 데이터(`serverAddrList`) 로딩 로직 포함.
- 로컬스토리지/복원
  - 키 규칙: `resume_draft_{ru_idx}_step1`.
  - `saveResumeStep1Data`, `loadResumeStep1Data` (현재는 `from=step4` 파라미터일 때만 복원).
  - `migrateResumeDraftKeys`, `clearResumeDraft` 정의.
- 자동작성(autofill)
  - `applyResumeAutoFill`가 step1 필드를 채운 뒤 `#btnStep` 자동 클릭.
  - payload는 `resume_autofill_payload`에 저장하고 step4에서 사용.
- 저장/이동 흐름
  - `#btnStep` 클릭 시 검증 후 `/mypage_proc/resume_write1` 저장.
  - 성공 시 `/mypage/resume_write4`로 이동 (autofill 파라미터 전달).
- 뒤로가기 처리
  - `.btn-back` 캡처 리스너로 리스트 이동.
  - `popstate`에서 리스트 이동 및 draft 삭제.

## resume_write4.php 핵심 구조/동작
- 상단
  - `html.autofill-hide` 및 `autofill=1` 파라미터 처리.
- UI 구조
  - `.page.page-write` 내부 `form#frm` 단일 폼.
  - 필드: 자기소개, 나의강점(최대6), 상세등록(희망요일/경력/자격증), 상세등록 버튼.
  - 하단 `sticky-bottom`에 `#btnPrev`(이전) + `#btnStep`(미리보기).
- 스타일
  - `form#frm`의 `top: 6vh`와 `.page-content` 하단 여백 등 단독 페이지 전제 스타일.
- 로컬스토리지/복원
  - 키 규칙: `resume_draft_{ru_idx}_step4`.
  - `saveResumeStep4Data`, `loadResumeStep4Data` (요일 기본값 보정 포함).
  - `migrateResumeDraftKeys`, `clearResumeDraft` 동일 정의.
- 상세등록 동작
  - 상세등록 버튼으로 섹션 show/hide.
  - 경력/자격증 템플릿 추가/삭제, 최대 개수 제한, 빈 li 정리.
  - flatpickr monthSelect 달력 초기화.
- 저장/미리보기/최종
  - `#btnStep` 기본 모드: 검증 → `/mypage_proc/resume_write4` 저장 → `showPreviewInfo` 팝업.
  - 팝업 확인 시 `/mypage_proc/resume_complete` 최종 저장.
  - 자동작성 모드에서는 저장 후 곧바로 `resume_complete`까지 수행.
- 뒤로가기 처리
  - `.btn-back` 캡처 리스너, `popstate`, `pageshow` 처리.

## 통합 시 주요 충돌/조정 포인트
- **중복 요소**: `form#frm`, `ru_idx` hidden, `sticky-bottom`, `autofill-hide` 스크립트, back 핸들러, `migrateResumeDraftKeys`/`clearResumeDraft`.
- **스타일 충돌**: `form#frm top`(0vh vs 6vh), `.page-content` 하단 여백, `sticky-bottom` z-index/box-shadow.
- **스토리지 복원 조건**: step1은 현재 `from=step4` 조건, 통합 시 상시 복원으로 변경 필요.
- **흐름 변경 필요**: 기존 `resume_write1 → resume_write4` 페이지 이동 제거, 단일 페이지 저장 흐름으로 재설계.
- **자동작성**: step1의 자동 클릭/페이지 이동 로직 제거 또는 단일 페이지용으로 변형 필요.

## 요청 기능 해석
- `resume_write4.php`의 모든 UI/스크립트를 `resume_write1.php`에 병합해 **한 페이지**에서 입력하도록 구성.
- 스크롤이 생기더라도 **한 페이지 내에 전체 폼**을 표시.
- 기존 임시저장/자동작성/미리보기/최종저장 흐름은 **단일 페이지 기준으로 재설계** 필요.
