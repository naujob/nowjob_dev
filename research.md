# Research: result-list 공통화 가능성

## 대상 파일
- application/shop/views/mobile/home/index_comp.php
- application/shop/views/mobile/home/index_mem.php
- application/shop/views/mobile/resume/hr_list.php
- application/shop/views/mobile/recruit/job_list.php

## 공통 구조
네 파일 모두 다음과 같은 마크업 구조를 공유함.
- `<ol class="result-list">` 내부에 `<li class="list-item">`.
- `.content-li > .content-info > .content-body` 구조.
- `.content-body` 내부에 `.img` + `.desc` 영역.
- `.desc` 내부에 `.wicon`, `.title`, `.summary`, `.summary area`, `.summary2`.
- `.content-info` 하단에 `.summary3`.

## 공통 스타일(동일 혹은 거의 동일)
다음 규칙들은 네 파일에서 대부분 동일하며 main.css로 이동 가능.
- `.result-list .content-li`의 기본 카드 박스(크기/라운딩/배경/여백/트랜지션)
- `.content-li * { margin:0; padding:0; }`
- `.content-li .content-info` 레이아웃 패딩
- `.content-info .wicon img` 높이
- `.content-info .title` 폰트 크기/굵기/마진
- `.content-info .content-body` 기본 높이/position/overflow
- `.content-info .desc` 기본 위치/높이/마진
- `.content-info .desc .ellipsis` 처리
- `.content-info .img`, `.content-info .img img` 이미지 박스/cover
- `.content-info .summary` 레이아웃(display:flex, gap, font-size)
- `.content-info .summary p`, `.summary p:not(:last-child)::after`
- `.content-info .summary>*`
- `.content-info .summary.area p`, `.summary.reco p`
- `.content-info .summary2` 기본 배치(position/right/bottom/flex column)
- `.content-info .summary2 p` 공통 텍스트 정렬
- `.content-info .summary3` 기본 배치(position/right/top)
- `.content-info .summary3 .worktype`와 `.worktype em` 기본 정렬
- `.content-info .summary3 .worktype em:not(:last-child)::after`
- `.result-list .list-item+.list-item { border-top: 0; }`

## 차이점(페이지별 유지 필요)
페이지별로 다음 차이가 있어, 공통화 시 분리 필요.
- `.result-list .content-li`의 `border` 색상/굵기와 hover 처리
  - index_comp: 빨간 테두리 + hover 배경
  - index_mem: 빨간 테두리, hover 없음
  - hr_list/job_list: 기본 회색 테두리 + `.recomm` 강조
- `.content-info .summary3` 내부 구조가 다름
  - index_comp/hr_list: `.summary3 .worktype em` 구조
  - index_mem/job_list: `.summary3 em` 직접 사용
- `.content-info .summary2` 폰트 크기/굵기/색상 차이
- `.content-info .desc` 좌측 마진 값 차이 (105px vs 110px)
- `.content-info .desc .ellipsis` width 지정 여부
- `.recomm-badge`는 hr_list/job_list에만 존재

## main.css 현황
- `resource/shop/mobile/css/main.css`에 `.result-list` 기본 스타일만 존재.
- `.content-li` 관련 상세 카드 스타일은 main.css에 없음.

## 결론
공통 요소는 `main.css`로 이동하고, 페이지별 차이는 각 페이지 스타일에 남기는 방식이 적합.
안전성을 위해 새로운 공통 클래스(예: `.result-list-card`)를 추가하면 다른 페이지에 영향 최소화 가능.
