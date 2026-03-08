# Plan: result-list 공통화 (main.css)

## 목표
- 네 페이지에서 중복된 카드 리스트 스타일을 `resource/shop/mobile/css/main.css`로 이동해 공통 관리.
- 각 페이지별 차이점은 기존 파일에 남겨 동일한 화면 유지.

## 접근 방식
1. 공통 클래스 추가
   - `<ol class="result-list">`에 `result-list-card` 클래스를 추가.
   - 공통 스타일을 `.result-list-card .content-li ...`처럼 스코프해 다른 페이지 영향 최소화.
2. main.css에 공통 스타일 묶음 추가
   - 카드 레이아웃, 이미지 박스, summary/summary2/summary3 기본값 등 공통 규칙을 이동.
3. 각 파일에서 공통 CSS 제거
   - 중복되는 규칙 삭제, 페이지별 차이만 남김.
4. 시각적 차이 확인
   - border 색상, hover, summary2 폰트 크기/색상 등 페이지별 규칙 유지.
5. main.css에서도 중복되는 스타일 코드 정리
6. 페이지별로 구조 통일화

## 수정/생성 파일
- 수정: `resource/shop/mobile/css/main.css`
- 수정: `application/shop/views/mobile/home/index_comp.php`
- 수정: `application/shop/views/mobile/home/index_mem.php`
- 수정: `application/shop/views/mobile/resume/hr_list.php`
- 수정: `application/shop/views/mobile/recruit/job_list.php`

## 트레이드오프
- 장점: 유지보수 단일화, 중복 제거, 스타일 일관성 향상
- 단점: main.css 변경이 다른 페이지에 영향을 줄 수 있음
  - 이를 방지하기 위해 `.result-list-card` 스코프로 제한
- 추가 작업: 마크업 클래스 추가 필요 (작은 변경이지만 4곳 수정)

## 검사(요구 사항)
- 린터/타입 체크는 프로젝트에 명시된 커맨드가 확인되면 실행.
- 관련 설정이 없을 경우 실행 불가 사유를 보고.
