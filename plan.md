# Plan: recruit_write 단일 페이지 폼 통합

## 목표
- `recruit_write2_1.php`, `recruit_write3_1.php`의 UI/스크립트를 `recruit_write1_1.php`로 통합해 **단일 페이지** 폼으로 구성.
- 스크롤 기반으로 자연스럽게 입력되도록 레이아웃/스타일 조정.
- 임시저장/미리보기/최종저장 흐름을 **단일 페이지 기준**으로 재정렬.

## 구현 단계
1. 통합 대상 UI/스크립트 정리
   - `recruit_write1_1.php`를 베이스로 `recruit_write2_1.php`, `recruit_write3_1.php`의 섹션/스타일/스크립트를 병합.
   - 중복 함수(`migrateRecruitDraftKeys`, `clearRecruitDraftData`, back/popstate 처리 등)를 단일 정의로 정리.
2. 단일 페이지 레이아웃/스타일 조정
   - 섹션 간격, `sticky-bottom` 위치, 상세등록 섹션 표시/숨김 UX를 단일 스크롤에 맞게 조정.
   - 충돌 가능한 인라인 스타일 중복을 정리하여 한 곳에 통합.
3. 저장 흐름 재설계
   - 임시저장: 입력 변경 시 통합 draft 저장(기존 page1/2/3 저장 로직을 하나로 합침).
   - 미리보기: 검증 후 `/compage_proc/recruit_write3_1` 또는 통합 엔드포인트로 저장 → `/compage_proc/recruit_complete` → `/compage/recruit_view` 이동.
   - 최종저장: 미리보기 경로에서 유지(필요 시 버튼 라벨/동작 재정렬).
4. 검증 로직 통합
   - 공고제목/주소/업무구분/근무형태/급여/상세업무정보/기간 검증을 단일 제출 흐름으로 재정렬.
5. 테스트/확인
   - 입력 복원, 상세등록 토글, 해시태그 추가, 날짜 선택, 파일 업로드, 미리보기 이동, back/popstate 동작 확인.

## 상세 접근 방식
- **UI 병합**: `recruit_write1_1.php`의 `form#frm` 내부에 복리후생(기존 page2)과 상세업무/해시태그/기간/상세등록(기존 page3)을 순서대로 추가.
- **스크립트 병합**:
  - `savePage1Data`, `savePage2Data`, `savePage3Data`를 `saveDraftData`로 통합.
  - `loadPage1Data`, `loadPage2Data`, `loadPage3Data`를 `loadDraftData`로 통합.
  - 기존 `rc_hash_string` 조합, 파일 업로드 분기, flatpickr 초기화, 상세등록 토글 등은 유지하되 단일 흐름으로 재배치.
- **흐름**:
  - 하단 버튼은 `임시저장`, `미리보기`, `최종저장` 순으로 단일 페이지 기준 재정렬.
  - 기존 페이지 이동(`recruit_write2_1`, `recruit_write3_1`)은 제거.

## 수정/생성 파일
- 수정: `application/shop/views/mobile/compage/recruit_write1_1.php`
- 참고만: `application/shop/views/mobile/compage/recruit_write2_1.php`, `application/shop/views/mobile/compage/recruit_write3_1.php`

## 트레이드오프
- **단일 draft 키 통합**
  - 장점: 저장/복원 로직 단순화, 유지보수 쉬움.
  - 단점: 기존 page1/2/3 키와 호환이 깨질 수 있어 마이그레이션 처리 필요.
- **기존 저장 엔드포인트 유지**
  - 장점: 서버 변경 최소화.
  - 단점: 단일 페이지에서 호출 순서를 조정해야 하고, 검증 중복이 남을 수 있음.
