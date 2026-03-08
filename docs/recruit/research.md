# Research: recruit_write1_1.php + recruit_write2_1.php + recruit_write3_1.php 통합

## 대상 파일
- source A: `application/shop/views/mobile/compage/recruit_write1_1.php`
- source B: `application/shop/views/mobile/compage/recruit_write2_1.php`
- source C: `application/shop/views/mobile/compage/recruit_write3_1.php`

## recruit_write1_1.php 핵심 구조/동작
- UI 구조
  - `.page.page-write` 내부 `form#frm` 단일 폼.
  - 섹션: 공고제목, 주소등록(주소 팝업), 업무구분(2개 제한), 근무형태(2개 제한 + 근무협의), 급여형태(급여협의 시 금액 비활성).
  - 하단 `sticky-bottom`에 `#btnStep`(“다음”).
- 주소 처리
  - hidden fields: `ad_zipcode`, `ad_jibun`, `ad_doro`, `ad_detail`, `ad_addr`, `ad_gps_x`, `ad_gps_y`.
  - 서버 주소 데이터 `serverAddrData`가 있을 때 버튼 텍스트/클래스 갱신.
  - `selectAddrInfo(...)` 콜백으로 주소 입력 + 버튼 UI 업데이트.
- 로컬스토리지
  - 키: `recruit_draft_{rc_idx}_page1`.
  - `savePage1Data`, `loadPage1Data`, `migrateRecruitDraftKeys`, `clearRecruitDraftData`.
- 검증/이동 흐름
  - `#btnStep` 클릭 시 필수 검증(공고제목, 주소, 업무구분, 근무형태, 급여금액).
  - 저장: `/compage_proc/recruit_write1_1`.
  - 성공 후 `/compage/recruit_write2_1`로 이동.
- 뒤로가기
  - `.btn-back` 및 `popstate`에서 draft 삭제 후 `/compage/recruit_list` 이동.

## recruit_write2_1.php 핵심 구조/동작
- UI 구조
  - 복리후생(보험/휴가/수당/생활편의 등) 체크박스 그룹 `rc_life_type[]`.
  - 하단 `sticky-bottom`에 `#btnPrev`(이전) + `#btnStep`(다음).
- 로컬스토리지
  - 키: `recruit_draft_{rc_idx}_page2`.
  - `savePage2Data`, `loadPage2Data`.
- 저장/이동 흐름
  - `#btnStep` 클릭 시 `/compage_proc/recruit_write2_1` 저장 후 `/compage/recruit_write3_1` 이동.
  - `#btnPrev`는 `/compage/recruit_write1_1` 이동.
- 뒤로가기
  - `.btn-back` 및 `popstate`에서 draft 삭제 후 `/compage/recruit_list` 이동.

## recruit_write3_1.php 핵심 구조/동작
- UI 구조
  - 상세업무정보, 해시태그(기본+직접입력), 채용공고기간(flatpickr), 상세등록(요일/성별/국적/나이/첨부사진), 상세등록 버튼.
  - 하단 `sticky-bottom`에 `#btnPrev`(이전) + `#btnStep`(미리보기).
- 로컬스토리지
  - 키: `recruit_draft_{rc_idx}_page3`.
  - `savePage3Data`, `loadPage3Data` (해시태그 동적 추가 복원 포함).
- 날짜 처리
  - 시작/종료일 flatpickr 초기화, 60일 제한, start 변경 시 end min/max 업데이트.
- 파일 업로드
  - 이미지 4개 미리보기, 파일 존재 시 `FormData`로 전송.
- 저장/미리보기/완료
  - `#btnStep` 검증 후 `/compage_proc/recruit_write3_1` 저장.
  - 성공 시 `/compage_proc/recruit_complete` 호출하여 미리보기 이동.
- 뒤로가기
  - `.btn-back` 및 `popstate`에서 draft 삭제 후 `/compage/recruit_list` 이동.

## 통합 시 주요 충돌/조정 포인트
- **중복 요소**
  - `form#frm`, `rc_idx` hidden, `sticky-bottom`, `migrateRecruitDraftKeys`/`clearRecruitDraftData`, back/popstate 핸들러.
- **로컬스토리지**
  - page1/2/3 저장 로직을 단일 draft 키(예: `recruit_draft_{rc_idx}_single`)로 합치는지, 기존 키를 유지하며 통합 저장을 병행할지 결정 필요.
- **이동 흐름**
  - 기존 페이지 이동(`/recruit_write2_1`, `/recruit_write3_1`) 제거.
  - 단일 페이지에서 임시저장/미리보기/최종저장으로 재정렬 필요.
- **UI/스크롤**
  - 세 페이지의 스타일이 모두 인라인 `<style>`에 있어 충돌 가능.
  - 단일 페이지 스크롤 기준으로 `sticky-bottom`과 섹션 간격 재조정 필요.
- **검증 순서**
  - page1/3의 필수 검증(공고제목, 주소, 급여, 상세업무정보, 기간)을 단일 제출 흐름에서 재배치해야 함.

## 요청 기능 해석
- `recruit_write2_1.php`, `recruit_write3_1.php`의 UI/스크립트를 `recruit_write1_1.php`에 병합하여 단일 페이지 폼으로 구성.
- 스크롤 기반 입력이 자연스럽도록 레이아웃/스타일 조정.
- 임시저장/미리보기/최종저장 흐름을 **단일 페이지 기준**으로 재정렬.
