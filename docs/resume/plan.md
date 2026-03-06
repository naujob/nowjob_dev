# Plan: resume_write1.php + resume_write4.php 통합

## 목표
- `resume_write4.php`의 모든 UI/스크립트를 `resume_write1.php`로 옮겨 **단일 페이지 폼**으로 통합.
- 스크롤 기반으로 자연스럽게 입력되도록 레이아웃/스타일 조정.
- 임시저장/자동작성/미리보기/최종저장 흐름을 **단일 페이지 기준**으로 재정렬.

## 세부 구현 단계
1. **레이아웃 통합**
   - `resume_write1.php`의 `form#frm` 안에 `resume_write4.php`의 섹션(자기소개/나의강점/상세등록/템플릿)을 이어 붙임.
   - `arr_career`, `arr_certi` hidden input을 `form#frm` 내부로 이동.
   - `sticky-bottom`은 하나만 유지하고 버튼을 단일 페이지용으로 재구성.

2. **스타일 병합/정리**
   - `resume_write4.php`의 스타일 블록을 `resume_write1.php`에 병합.
   - `form#frm`의 `top` 충돌 해결(단일 페이지 기준으로 0 또는 단계별 적정값 결정).
   - `page-content` 하단 여백과 `sticky-bottom` z-index/box-shadow는 통합 페이지 기준으로 유지.

3. **스크립트 중복 제거 및 통합**
   - `autofill-hide` 처리, back 핸들러, `migrateResumeDraftKeys`, `clearResumeDraft` 중복 정의 제거.
   - step1/step4 임시저장 로직을 **한 페이지 로드 시 모두 복원**하도록 변경.
   - `from=step4` 기반 복원 조건 제거(단일 페이지에서는 항상 복원 가능).

4. **버튼/저장 플로우 재설계**
   - `#btnPrev` 제거 또는 비활성화.
   - 단일 `#btnStep` 클릭 시:
     - step1 필수 검증 → step4 필수 검증 순서로 체크.
     - `/mypage_proc/resume_write1` 저장 후 성공 시 `/mypage_proc/resume_write4` 저장.
     - 정상 저장 시 기존 `showPreviewInfo` 팝업을 그대로 사용하고, 팝업 확인 시 `/mypage_proc/resume_complete` 수행.
   - 기존 페이지 이동(`location.href`) 로직 제거.

5. **자동작성(autofill) 재정리**
   - step1/step4 자동작성 함수가 **페이지 이동 없이** 한 페이지에서 수행되도록 조정.
   - 자동작성 완료 후 `#btnStep` 자동 클릭은 유지하되, 단일 페이지 저장 플로우를 타도록 수정.

6. **동작 검증 체크리스트**
   - 주소 추가/삭제, 희망업무/급여/근무형태 선택 제한 정상 동작.
   - 나의강점, 희망요일, 경력/자격증 템플릿 추가/삭제 및 flatpickr 정상 동작.
   - 미리보기 팝업 및 최종 저장(`/mypage_proc/resume_complete`) 정상 동작.

## 접근 방식 상세
- 기존 코드를 최대한 재사용하고, **중복 제거 + 이벤트 흐름 정리**에 집중.
- 저장 플로우를 하나의 버튼에서 순차 수행:
  1) `resume_write1` 저장
  2) `resume_write4` 저장
  3) 미리보기 팝업 → 최종 저장
- 페이지 이동 제거로 인해 `from=step4` 복원 파라미터는 폐기하고, 로컬스토리지는 항상 복원 가능하도록 변경.

## 수정/생성 파일
- 수정: `application/shop/views/mobile/mypage/resume_write1.php`
- 참고: `application/shop/views/mobile/mypage/resume_write4.php`는 변경하지 않음

## 트레이드오프
- **장점**: 입력 흐름 단순화, 페이지 이동 제거로 UX 개선.
- **단점**: 페이지 길이 증가로 초기 로딩 부담 증가.
- **대안**: 섹션별 아코디언/탭 구성으로 길이 체감 감소(하지만 이동/조작이 추가됨).

## 검사 계획
- 린터 검사
- 타입 검사
