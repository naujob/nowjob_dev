<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab2"; ?>
            <?php $tab_on2 = "menu2"; ?>
            <?php include_once(get_base_url() . '/application/shop/views/web/mypage/top_line_2025.php'); ?>
            <?php
            // 카카오 키 가져오기 for Geocoding
            $kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
            ?>
            <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?= $kakao_key["인증키"] ?>&libraries=services"></script>

            <style>
                /* ============================================
                   이력서 작성 페이지 CSS - 리팩토링 버전
                   공통 클래스 중심 구조로 개선
                   ============================================ */

                /* ============================================
                   폰트 크기 관리 (CSS 변수)
                   ============================================
                   크기별 폰트:
                   - 20px: 제목급 (섹션 제목, 주요 라벨)
                   - 16px: 본문급 (입력 요소, 버튼)

                   변수 정의:
                   --rw-title-font-size: 20px
                     → .rw-section-title (섹션 제목)
                     → 상세등록 링크

                   --rw-label-font-size: 20px
                     → .rw-label (메인 라벨)
                     → 기본정보, 근무조건, 자기소개/강점, 경력사항
                     → 경력사항 섹션 모든 라벨 (기업명, 업종, 자격증명, 취득일자)

                   --rw-input-font-size: 16px
                     → input[type], select, textarea 등 모든 입력 요소
                     → 버튼 텍스트
                   ============================================ */

                /* CSS 변수 정의 */
                :root {
                    --rw-input-height: 70px;
                    --rw-title-font-size: 25px;
                    --rw-input-font-size: 20px;
                    --rw-label-width: 255px;
                    --rw-label-font-size: 22px;
                    --rw-row-padding: 20px;
                    --rw-section-padding: 20px;
                    --rw-section-padding-left: 44px;
                    --rw-border-radius: 4px;
                    --rw-border-color: #D8D8D8;
                    --rw-basic-group-width: 650px;
                }

                /* 입력 요소 폰트 크기 통일 */
                /* 모든 입력 요소(input, select, textarea)는 16px 통일 */
                input[type="text"],
                input[type="number"],
                input[type="email"],
                input[type="password"],
                input[type="date"],
                input[type="tel"],
                textarea,
                select {
                    font-size: var(--rw-input-font-size);
                }

                /* ============================================
                   공통 레이아웃 클래스 (핵심 리팩토링)
                   ============================================ */

                /* 섹션 기본 구조 */
                .rw-section {
                    margin-bottom: 32px;
                }

                .rw-section-title {
                    padding: 16px 20px;

                    font-size: var(--rw-title-font-size);
                    font-weight: 800;
                    border-bottom: 1px solid var(--rw-border-color);
                    margin: 0;
                }

                /* 4개 섹션 공통 래퍼 */
                /* 섹션 내용: 좌측 들여쓰기 효과 (padding-left: 44px) */
                .rw-basic-info,
                .rw-work-condition,
                .rw-intro-strength,
                .rw-career {
                    padding: var(--rw-section-padding) 24px;
                    padding-left: var(--rw-section-padding-left);
                }

                /* 공통 form-row - 4개 섹션 모두 동일한 기본 구조 */
                .rw-form-row {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    margin: 0;
                    padding: var(--rw-row-padding) 0;
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    align-items: center;
                    -ms-flex-wrap: wrap;
                    flex-wrap: wrap;
                    border-bottom: 1px solid var(--rw-border-color);
                }

                .rw-basic-info .form-label {
                    width: 100%;
                }

                .rw-form-row:first-child {
                    padding-top: 0;
                }

                .rw-form-row:last-child {
                    border-bottom: none;
                    padding-bottom: 0;
                }

                /* rw-label이 없는 form-row (기업명/업종, 자격증명/취득일자) */
                .rw-form-row:has(.rw-dual-col) {
                    -webkit-box-align: stretch;
                    -ms-flex-align: stretch;
                    align-items: stretch;
                }

                .rw-form-row:has(.rw-dual-col) .rw-value {
                    width: 100%;
                }

                /* 공통 라벨 */
                /* 메인 라벨: 모든 섹션의 라벨 (기본정보, 근무조건, 자기소개/강점, 경력사항 포함) = 20px */
                .rw-label {
                    width: var(--rw-label-width);
                    margin: 0;
                    padding: 0;
                    font-size: var(--rw-label-font-size);
                    line-height: 1;
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 var(--rw-label-width);
                    flex: 0 0 var(--rw-label-width);
                    -ms-flex-item-align: center;
                    align-self: center;
                }

                /* 공통 value 영역 */
                .rw-value {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-flex: 1;
                    -ms-flex: 1;
                    flex: 1;
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                    -ms-flex-direction: column;
                    flex-direction: column;
                    -webkit-box-pack: center;
                    -ms-flex-pack: center;
                    justify-content: center;
                }

                /* ============================================
                   섹션별 오버라이드 (차이점만 정의)
                   ============================================ */

                /* 기본정보, 근무조건: label 중앙 정렬 + 고정 높이 */
                .rw-basic-info .rw-label,
                .rw-work-condition .rw-label {
                    height: var(--rw-input-height);
                    display: flex;
                    align-items: center;
                }

                .rw-basic-info .rw-value,
                .rw-work-condition .rw-value {
                    height: var(--rw-input-height);
                }

                /* 근무조건: value는 min-height로 유연하게 */
                .rw-work-condition .rw-value {
                    height: auto;
                    min-height: var(--rw-input-height);
                }

                /* 자기소개/강점, 경력사항: label 상단 정렬 */
                .rw-intro-strength .rw-label,
                .rw-career .rw-label {
                    -ms-flex-item-align: start;
                    align-self: flex-start;
                }

                /* ============================================
                   기본정보 섹션 고유 스타일
                   ============================================ */

                /* 이력서명 form-group */
                .rw-basic-info .form-row:first-child .form-group {
                    display: flex;
                    gap: 0;
                    align-items: center;
                    width: calc(100% - var(--rw-basic-group-width) + 200px);
                    flex: 1;
                }

                .rw-basic-info .form-row:first-child label {
                    width: 100%;
                    display: flex;
                    align-items: center;
                }

                /* 이력서명 입력창 */
                .rw-basic-info .form-text {
                    border: 1px solid #ccc;
                    border-radius: var(--rw-border-radius);
                    padding: 12px 16px;
                    height: var(--rw-input-height);
                    font-size: var(--rw-input-font-size);
                    font-weight: normal;
                    box-sizing: border-box;
                    width: 100%;
                }

                /* 국적 버튼 그룹 */
                .rw-basic-info .form-row:last-child .form-group {
                    display: flex;
                    gap: 0;
                    align-items: center;
                    width: var(--rw-basic-group-width);
                }

                .rw-basic-info .form-toggle-btn2 {
                    margin-right: 0;
                    height: var(--rw-input-height);
                    display: flex;
                    align-items: center;
                    flex: 1;
                }

                .rw-basic-info .form-toggle-btn2 input {
                    display: none;
                }

                .rw-basic-info .form-toggle-btn2 .btn {
                    padding: 0;
                    height: var(--rw-input-height);
                    width: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 1px solid #ccc;
                    background: #fff;
                    color: #333;
                    font-size: var(--rw-input-font-size);
                    font-weight: normal;
                    margin: 0;
                    cursor: pointer;
                }

                .rw-basic-info .form-toggle-btn2:first-child .btn {
                    border-radius: 4px 0 0 4px;
                }

                .rw-basic-info .form-toggle-btn2:last-child .btn {
                    border-radius: 0 4px 4px 0;
                }

                .rw-basic-info .form-toggle-btn2 input:checked+.btn {
                    background: #1abc9c;
                    color: #fff;
                    border-color: #1abc9c;
                }

                /* ============================================
                   근무조건 섹션 고유 스타일
                   ============================================ */

                /* 희망업무 - 체크박스 버튼 5개 한 줄 배치 */
                .rw-work-condition .form-group.col-5 {
                    display: flex !important;
                    flex-wrap: wrap !important;
                    gap: 0;
                }

                .rw-work-condition .form-group.col-5>.form-toggle-btn2 {
                    width: calc(20% - 4px) !important;
                    max-width: calc(20% - 4px) !important;
                    flex: none !important;
                    margin-right: 4px !important;
                    margin-bottom: 6px !important;
                }

                .rw-work-condition .form-group.col-5>.form-toggle-btn2 .btn {
                    height: var(--rw-input-height);
                    width: 100%;
                    font-size: var(--rw-input-font-size);
                }

                /* 근무조건 내 form-group 공통 규칙 */
                .rw-work-condition .form-group {
                    display: flex;
                    gap: 2px;
                    align-items: center;
                    flex-wrap: wrap;
                }

                .rw-work-condition .form-toggle-btn2 {
                    margin-right: 0;
                    height: var(--rw-input-height);
                    display: flex;
                    align-items: center;
                    flex: 1;
                    min-width: 100px;
                }

                .rw-work-condition .form-toggle-btn2 input {
                    display: none;
                }

                .rw-work-condition .form-toggle-btn2 .btn {
                    padding: 0;
                    height: var(--rw-input-height);
                    width: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 1px solid #ccc;
                    background: #fff;
                    color: #333;
                    font-size: var(--rw-input-font-size);
                    font-weight: normal;
                    margin: 0;
                    cursor: pointer;
                }

                .rw-work-condition .form-toggle-btn2 input:checked+.btn {
                    background: #1abc9c;
                    color: #fff;
                    border-color: #1abc9c;
                }

                /* 희망지역 검색창 스타일 */
                .address-search-box {
                    position: relative;
                    margin-bottom: 12px;
                    height: var(--rw-input-height);
                    cursor: pointer;
                    width: 100%;
                }

                .address-search-box .addr-search-input {
                    width: 100%;
                    border: 1px solid #ddd;
                    border-radius: var(--rw-border-radius);
                    padding: 12px 16px 12px 16px;
                    padding-right: 50px;
                    height: 100%;
                    font-size: var(--rw-input-font-size);
                    background: #fff;
                    color: #999;
                    box-sizing: border-box;
                }

                .address-search-box .btn-search-icon {
                    position: absolute;
                    right: 12px;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 24px;
                    height: 24px;
                    border: none;
                    background: none;
                    padding: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                }

                .address-search-box .btn-search-icon img {
                    width: 100%;
                    height: 100%;
                }

                /* 자식 요소들이 클릭 이벤트를 부모에 전달하도록 설정 */
                .address-search-box .addr-search-input,
                .address-search-box .btn-search-icon {
                    pointer-events: none;
                }

                /* 희망연봉 영역 - 기존 인라인 스타일 대체 */
                .rw-salary-row {
                    padding-left: 100px;
                    width: 270px;
                }

                .rw-salary-label {
                    width: 90px;
                }

                .rw-salary-label p {
                    width: 100%;
                    font-weight: normal;
                }

                /* ============================================
                   자기소개/강점 섹션 고유 스타일
                   ============================================ */

                /* 나의강점 - 체크박스 버튼 5개 한 줄 배치 */
                .rw-intro-strength .form-group.col-8>* {
                    width: calc(20% - 4px);
                }

                .rw-intro-strength .form-group.col-8>.form-toggle-btn2.sm .btn {
                    height: var(--rw-input-height);
                    font-size: var(--rw-input-font-size);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                /* 자기소개 textarea */
                .rw-intro-textarea {
                    min-height: 324px;
                }

                /* ============================================
                   경력사항 섹션 고유 스타일
                   ============================================ */

                /* 경력사항 섹션의 근무기간 form-row */
                .rw-career .rw-form-row:has(.rw-career-period-wrapper) {
                    align-items: center;
                }

                .rw-career .rw-form-row:has(.rw-career-period-wrapper) .rw-label {
                    align-self: center;
                }

                .rw-career .rw-form-row:has(.rw-career-period-wrapper) .rw-value {
                    flex-direction: row;
                    align-items: center;
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 auto;
                    flex: 0 0 auto;
                    width: 45%;
                }

                /* 경력사항/자격증 추가 버튼 */
                .rw-career .rw-form-row:has(.js-add-career),
                .rw-career .rw-form-row:has(.js-add-license) {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px 0;
                    border-bottom: 1px solid var(--rw-border-color);
                }

                .rw-career .rw-form-row:has(.js-add-career):last-of-type,
                .rw-career .rw-form-row:has(.js-add-license) {
                    border-bottom: none;
                    padding-bottom: 0;
                }

                .rw-career .js-add-career,
                .rw-career .js-add-license {
                    border: none;
                    border-radius: var(--rw-border-radius);
                    padding: 0 24px;
                    background: #999;
                    color: #fff;
                    font-size: var(--rw-input-font-size);
                    font-weight: 700;
                    cursor: pointer;
                    height: var(--rw-input-height);
                    width: 12.5em;

                }

                .rw-career .js-add-career:hover,
                .rw-career .js-add-license:hover {
                    background: #888;
                }

                /* 텍스트 입력창 높이 통일 */
                .rw-career .form-text {
                    height: var(--rw-input-height);
                    box-sizing: border-box;
                }

                /* 셀렉트박스 높이 통일 */
                .rw-career .form-selectbox select {
                    height: var(--rw-input-height);
                    box-sizing: border-box;
                }

                /* 액션 버튼 (등록/추가) 높이 통일 */
                .rw-career .btn-add-item {
                    height: var(--rw-input-height);
                    box-sizing: border-box;
                }

                /* 경력사항 섹션 입력 요소 border 추가 */
                .rw-career .form-text {
                    border: 1px solid #ccc;
                    border-radius: var(--rw-border-radius);
                    padding: 12px 16px;
                    box-sizing: border-box;
                }

                .rw-career .form-selectbox {
                    border: 1px solid #ccc;
                    border-radius: var(--rw-border-radius);
                    overflow: hidden;
                    height: var(--rw-input-height);
                }

                .rw-career .form-selectbox select {
                    border: none;
                    border-radius: 0;
                    padding: 12px 16px;
                    box-sizing: border-box;
                    background: #fff;
                    color: #333;
                    font-size: var(--rw-input-font-size);
                    width: 100%;
                    height: var(--rw-input-height);
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    appearance: none;
                    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
                    background-repeat: no-repeat;
                    background-position: right 12px center;
                    background-size: 20px;
                    padding-right: 36px;
                }

                .rw-career .form-selectbox .liner {
                    display: none;
                }

                .rw-career .btn-add-item {
                    border: none;
                    border-radius: var(--rw-border-radius);
                    padding: 0 24px;
                    background: #999;
                    color: #fff;
                    font-size: 16px;
                    font-weight: normal;
                    cursor: pointer;
                    height: var(--rw-input-height);
                    width: 100%;
                    max-width: 400px;
                }

                .rw-career .btn-add-item:hover {
                    background: #888;
                }

                /* 경력/자격증 섹션 좌우 배치 */
                .rw-dual-col {
                    width: 100%;
                    padding: 0;
                }

                .rw-dual-col .form-group {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    gap: 24px;
                    width: 100%;
                    -webkit-box-align: flex-start;
                    -ms-flex-align: flex-start;
                    align-items: flex-start;
                }

                .rw-col-half {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: horizontal;
                    -webkit-box-direction: normal;
                    -ms-flex-direction: row;
                    flex-direction: row;
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    align-items: center;
                    -webkit-box-flex: 1;
                    -ms-flex: 1;
                    flex: 1;
                }

                /* rw-dual-col 내 rw-col-half의 gap */
                .rw-dual-col .form-group>.rw-col-half {
                    gap: 190px;
                }

                /* 기업명과 자격증명(첫번째 rw-col-half)의 gap 조정 */
                .extra-section .rw-career .rw-form-row:nth-of-type(1) .rw-col-half:nth-of-type(1),
                .extra-section .rw-career .rw-form-row:nth-of-type(4) .rw-col-half:nth-of-type(1) {
                    /* gap: 100px; */
                }

                /* 첫 번째 rw-col-half (기업명) 더 넓게 */
                .rw-dual-col .form-group>.rw-col-half:nth-of-type(1) {
                    -webkit-box-flex: 2;
                    -ms-flex: 2;
                    flex: 2;
                }

                /* 두 번째 rw-col-half (업종) 더 좁게 */
                .rw-dual-col .form-group>.rw-col-half:nth-of-type(2) {
                    -webkit-box-flex: 1;
                    -ms-flex: 1;
                    flex: 1;
                }

                .rw-col-half .form-label {
                    margin: 0;
                    padding: 0;
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 auto;
                    flex: 0 0 auto;
                    white-space: nowrap;
                }

                /* 업종과 취득일자 라벨만 넓이 증가 */
                /* 업종 라벨 (경력 섹션 첫 번째 rw-dual-col) */
                .rw-career .rw-dual-col:first-of-type .rw-col-half:nth-of-type(2)>p.form-label {
                    flex: 0 0 137px;
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 80px;
                    min-width: 80px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    position: relative;
                    left: 116px;
                }

                /* 취득일자 라벨 (자격증 섹션) */
                .rw-dual-col .rw-col-half.rw-col-with-calendar>p.form-label {
                    flex: 0 0 80px;
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 80px;
                    min-width: 80px;
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                }

                .rw-col-half>label,
                .rw-col-half .form-selectbox {
                    -webkit-box-flex: 1;
                    -ms-flex: 1;
                    flex: 1;
                    width: 60%;
                }

                /* 경력 섹션 기업명 input만 width 수정 */
                .rw-career .rw-dual-col:first-of-type .rw-col-half:nth-of-type(1)>input[name="company[]"] {
                    width: 60%;
                }

                /* 자격증 섹션 자격증명 input만 width 수정 */
                .rw-dual-col .rw-col-half:nth-of-type(1)>input[name="license[]"] {
                    width: 60%;
                }

                .rw-col-with-calendar {
                    position: relative;
                }

                .rw-block-label {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    flex: 1;
                    width: 100%;
                }

                .rw-calendar-input-wrapper {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    flex: 1;
                    width: 100%;
                }

                .rw-calendar-input-wrapper .btn-calendar {
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 auto;
                    flex: 0 0 auto;
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #f5f5f5;
                    border: 1px solid #ddd;
                    border-radius: var(--rw-border-radius);
                    cursor: pointer;
                    padding: 0;
                    margin: 0;
                }

                .rw-calendar-input-wrapper .btn-calendar:hover {
                    background: #efefef;
                }

                /* 텍스트 입력창 높이 통일 */
                .rw-career .form-text {
                    height: var(--rw-input-height);
                    box-sizing: border-box;
                }

                /* 셀렉트박스 높이 통일 */
                .rw-career .form-selectbox select {
                    height: var(--rw-input-height);
                    box-sizing: border-box;
                }

                /* 액션 버튼 (등록/추가) 높이 통일 */
                .rw-career .btn-add-item {
                    height: var(--rw-input-height);
                    box-sizing: border-box;
                }

                /* 경력사항 섹션 입력 요소 border 추가 */
                .rw-career .form-text {
                    border: 1px solid #ccc;
                    border-radius: var(--rw-border-radius);
                    padding: 12px 16px;
                    box-sizing: border-box;
                }

                .rw-career .form-selectbox {
                    border: 1px solid #ccc;
                    border-radius: var(--rw-border-radius);
                    overflow: hidden;
                    height: var(--rw-input-height);
                }

                .rw-career .form-selectbox select {
                    border: none;
                    border-radius: 0;
                    padding: 12px 16px;
                    box-sizing: border-box;
                    background: #fff;
                    color: #333;
                    font-size: var(--rw-input-font-size);
                    width: 100%;
                    height: var(--rw-input-height);
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    appearance: none;
                    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
                    background-repeat: no-repeat;
                    background-position: right 12px center;
                    background-size: 20px;
                    padding-right: 36px;
                }

                .rw-career .form-selectbox .liner {
                    display: none;
                }

                .rw-career .btn-add-item {
                    border: none;
                    border-radius: var(--rw-border-radius);
                    padding: 0 24px;
                    background: #999;
                    color: #fff;
                    font-size: 16px;
                    font-weight: normal;
                    cursor: pointer;
                    height: var(--rw-input-height);
                    width: 100%;
                    max-width: 400px;
                }

                .rw-career .btn-add-item:hover {
                    background: #888;
                }

                /* 경력/자격증 섹션 좌우 배치 */
                .rw-dual-col {
                    width: 100%;
                    padding: 0;
                }

                .rw-dual-col .form-group {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    gap: 60px;
                    width: 100%;
                    -webkit-box-align: stretch;
                    -ms-flex-align: stretch;
                    align-items: stretch;
                    padding: 20px 0;
                }

                .rw-col-half {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: horizontal;
                    -webkit-box-direction: normal;
                    -ms-flex-direction: row;
                    flex-direction: row;
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    align-items: center;
                    -webkit-box-flex: 1;
                    -ms-flex: 1;
                    flex: 1;
                    gap: 16px;
                    height: var(--rw-input-height);
                }

                /* 첫 번째 rw-col-half (기업명) 더 넓게 */
                .rw-dual-col .form-group>.rw-col-half:nth-of-type(1) {
                    -webkit-box-flex: 2;
                    -ms-flex: 2;
                    flex: 2;
                }

                /* 두 번째 rw-col-half (업종) 더 좁게 */
                .rw-dual-col .form-group>.rw-col-half:nth-of-type(2) {
                    -webkit-box-flex: 1;
                    -ms-flex: 1;
                    flex: 1;
                }

                /* 경력사항 섹션 라벨 (기업명, 업종, 자격증명, 취득일자) */
                .rw-col-half>.rw-label {
                    margin: 0;
                    padding: 0;
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 auto;
                    flex: 0 0 60px;
                    white-space: nowrap;
                    text-align: left;
                    font-size: var(--rw-label-font-size);
                    font-weight: normal;
                    color: #333;
                    -ms-flex-item-align: center;
                    align-self: center;
                }

                /* 경력사항 첫번째 form-row (기업명/업종)의 두번째 rw-col-half 라벨 */
                .extra-section .rw-career .rw-form-row:nth-of-type(1) .rw-col-half:nth-of-type(2) .rw-label,
                /* 경력사항 네번째 form-row (자격증명/취득일자)의 두번째 rw-col-half 라벨 */
                .extra-section .rw-career .rw-form-row:nth-of-type(4) .rw-col-half:nth-of-type(2) .rw-label {
                    position: relative;
                    left: 106px;
                }

                /* 입력 요소 스타일 */
                .rw-col-half>input,
                .rw-col-half>label,
                .rw-col-half .form-selectbox {
                    -webkit-box-flex: 1;
                    -ms-flex: 1;
                    flex: 1;
                    height: 100%;
                }

                /* selectbox와 input의 높이 통일 */
                .rw-col-half .form-selectbox {
                    display: flex;
                    align-items: center;
                }

                .rw-col-half .form-selectbox select {
                    height: 100%;
                    width: 100%;
                    opacity: 1;
                }

                /* 기업명 input */
                .rw-col-half>input[type="text"] {
                    height: 100%;
                }

                .rw-col-with-calendar {
                    position: relative;
                }

                .rw-calendar-input-wrapper {
                    position: relative;
                    display: flex;
                    align-items: center;
                    flex: 1;
                }

                .rw-calendar-input-wrapper input {
                    padding-right: 40px;
                }

                /* 날짜 input 화살표 제거 */
                .js-datepicker {
                    appearance: none !important;
                    -webkit-appearance: none !important;
                    -moz-appearance: none !important;
                }

                .js-datepicker::-webkit-calendar-picker-indicator {
                    display: none !important;
                }

                .js-datepicker::-webkit-outer-spin-button,
                .js-datepicker::-webkit-inner-spin-button {
                    -webkit-appearance: none !important;
                    margin: 0 !important;
                    display: none !important;
                }

                .js-datepicker::-moz-calendar-picker-indicator {
                    display: none !important;
                }

                input[type="text"].js-datepicker {
                    background-image: none !important;
                }

                .js-datepicker[type="date"] {
                    position: relative;
                }

                .btn-calendar {
                    position: absolute;
                    right: 12px;
                    top: 50%;
                    -webkit-transform: translateY(-50%);
                    -ms-transform: translateY(-50%);
                    transform: translateY(-50%);
                    background: none;
                    border: none;
                    cursor: pointer;
                    padding: 0;
                    width: 20px;
                    height: 20px;
                    color: #333;
                    font-size: 16px;
                }

                .rw-career-period-wrapper {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    gap: 12px;
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    align-items: center;
                    width: 100%;
                    flex: 1;
                }

                .rw-career-period-wrapper .rw-period-field {
                    -webkit-box-flex: 1;
                    -ms-flex: 1;
                    flex: 1;
                    position: relative;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .rw-period-field .btn-calendar {
                    -webkit-box-flex: 0;
                    -ms-flex: 0 0 auto;
                    flex: 0 0 auto;
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #f5f5f5;
                    border: 1px solid #ddd;
                    border-radius: var(--rw-border-radius);
                    cursor: pointer;
                    padding: 0;
                    margin: 0;
                }

                .rw-period-field .btn-calendar:hover {
                    background: #efefef;
                }

                .rw-period-field .rw-block-label {
                    flex: 1;
                }

                .rw-period-field .form-text {
                    width: 100%;
                }

                .rw-career-period-separator {
                    color: #999;
                    font-size: 16px;
                }

                /* 희망지역 팝업 - PC 센터 모달 스타일 */
                #changeArea .panel-container {
                    max-width: 720px;
                    width: 90vw;
                    max-height: 88vh;
                    top: 50% !important;
                    left: 50% !important;
                    transform: translate(-50%, -50%) !important;
                }

                #changeArea .panel-body {
                    max-height: calc(86vh - 64px);
                    overflow: auto;
                }

                .fixed {
                    color: #e40d18;

                }

                /* 상세등록 섹션 */
                .detail-register-section {
                    margin-bottom: 40px;
                    text-align: center;
                }

                .detail-register-link {
                    display: inline-block;
                    min-width: 12.5em;
                    padding: 20px 30px;
                    text-align: center;
                    border: 1px solid #d5d8df;
                    border-radius: 5px;
                    color: #1B9D7D;
                    font-size: var(--rw-title-font-size);
                    font-weight: 500;
                    text-decoration: none;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                .detail-register-link:hover {
                    background-color: #F0F5FF;
                }

                /* 추가정보 섹션 - 초기 숨김 */
                .extra-section {
                    display: none;
                }

                /* 상세등록 클릭 시 표시 */
                .extra-section.show {
                    display: block;
                }
            </style>
            <div class="tab-panel on">
                <div class="manage-my-content">
                    <form id="frmAll" onsubmit="return false">
                        <input type="hidden" name="ru_idx" value="<?= getData($info, "ru_idx", "0") ?>" />
                        <input type="hidden" name="ru_work_img" value="" />
                        <input type="hidden" name="arr_certi" value="" />
                        <input type="hidden" name="arr_career" value="" />

                        <h3 class="sr-text">이력서 작성</h3>
                        <div class="addition-info-form">
                            <div class="form-row">
                                <div class="form-group" style="position:static; right:auto;">
                                    <span class="sr-text">간편등록 단일 폼</span>
                                </div>
                            </div>

                            <!-- 기본정보 섹션 -->
                            <div class="rw-section">
                                <h4 class="rw-section-title">기본정보<span class="fixed">*</span></h4>
                                <div class="rw-basic-info">
                                    <div class="rw-form-row">
                                        <p class="rw-label">이력서명</p>
                                        <div class="rw-value">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    <span class="sr-text">이력서명 입력</span>
                                                    <input type="text" name="ru_name" placeholder="이력서명을 작성해주세요" value="<?= $info["ru_name"] ?>" autocomplete="off" aria-required="true" required class="form-text">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rw-form-row">
                                        <p class="rw-label">국적</p>
                                        <div class="rw-value">
                                            <div class="form-group">
                                                <?php
                                                $ru_nationality = getData($info, "ru_nationality", "");
                                                // 회원 정보에서 국적 가져오기 (이력서에 없으면)
                                                if (empty($ru_nationality) && isset($mem_info["mm_nation"])) {
                                                    $ru_nationality = $mem_info["mm_nation"];
                                                }
                                                ?>
                                                <label class="form-toggle-btn2">
                                                    <input type="radio" name="ru_nationality" value="내국인" <?= ($ru_nationality == "내국인" ? "checked" : "") ?> required aria-required="true">
                                                    <span class="btn">내국인</span>
                                                </label>
                                                <label class="form-toggle-btn2">
                                                    <input type="radio" name="ru_nationality" value="외국인" <?= ($ru_nationality == "외국인" ? "checked" : "") ?> required aria-required="true">
                                                    <span class="btn">외국인</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- 근무조건 섹션 -->
                            <div class="rw-section">
                                <h4 class="rw-section-title">근무조건<span class="fixed">*</span></h4>
                                <div class="rw-work-condition">
                                    <div class="rw-form-row">
                                        <p class="rw-label">희망업무</p>
                                        <div class="rw-value">
                                            <div class="form-group col-5">
                                                <?php
                                                // 표시 허용 희망업무 화이트리스트
                                                $allowed_work_list = ["생산", "사무직", "운전배달", "물류", "식당", "서비스", "매장관리", "건설", "상담"];
                                                foreach ($ru_wrok_list as $row) :
                                                    // 허용 목록 이외 항목은 출력 제외
                                                    if (!in_array($row["cd_val"], $allowed_work_list, true)) {
                                                        continue;
                                                    }
                                                ?>
                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="ru_work[]" data-select-limit="2" value="<?= $row["cd_val"] ?>" data-img="<?= $row["cd_val2"] ?>" <?= in_array_return($row["cd_val"], explode(",", $info["ru_work"]), "check") ?>>
                                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rw-form-row">
                                        <p class="rw-label">희망지역</p>
                                        <div class="rw-value">
                                            <div class="form-group">
                                                <div class="address-search-box js-add-address" role="button" aria-haspopup="dialog" aria-controls="changeArea">
                                                    <input type="text" id="addr_search" placeholder="희망지역을 입력해주세요" class="addr-search-input" readonly>
                                                    <button type="button" class="btn-search-icon">
                                                        <img src="/resource/shop/mobile/img/common/search.png" alt="검색">
                                                    </button>
                                                </div>
                                                <ul class="added-location" id="addr_location">
                                                    <?php foreach ($addr_list as $row) : ?>
                                                        <?php if (!empty($row["ad_addr"])) : ?>
                                                            <li>
                                                                <input type="hidden" name="ad_zipcode[]" value="<?= $row["ad_zip_code"] ?>" />
                                                                <input type="hidden" name="ad_jibun[]" value="<?= $row["ad_jibun"] ?>" />
                                                                <input type="hidden" name="ad_doro[]" value="<?= $row["ad_doro"] ?>" />
                                                                <input type="hidden" name="ad_addr[]" value="<?= $row["ad_addr"] ?>" />
                                                                <input type="hidden" name="ad_gps_x[]" value="<?= $row["ad_gps_x"] ?>" />
                                                                <input type="hidden" name="ad_gps_y[]" value="<?= $row["ad_gps_y"] ?>" />
                                                                <p class="addr-display"><?= $row["ad_addr"] ?></p>
                                                                <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                                                            </li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rw-form-row">
                                        <p class="rw-label">희망급여</p>
                                        <div class="rw-value">
                                            <div class="form-group">
                                                <?php foreach ($ru_price_type_list as $row) : ?>
                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="ru_price_type[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], explode(",", $info["ru_price_type"]), "check") ?>>
                                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="addition-option cont-price <?= (strpos($info["ru_price_type"], "월급x") !== false ? "" : "hidden") ?>">
                                                <input type="radio" name="opt" id="opt1">
                                                <input type="radio" name="opt" id="opt3">

                                                <div id="amount3" class="row rw-salary-row">
                                                    <div class="label rw-salary-label">
                                                        <p class="on">희망연봉</p>
                                                    </div>
                                                    <label class="inline-group">
                                                        <input type="text" name="ru_month_price" value="<?= number_format($info["ru_month_price"]) ?>" class="form-text is-currency comma" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                        <span class="is-abs">원</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="help-msg  <?= count(explode(",", $info["ru_price_type"])) == 3 ? "" : "hidden" ?>">협의 가능</p>
                                        </div>
                                    </div>

                                    <div class="rw-form-row">
                                        <p class="rw-label">근무형태</p>
                                        <div class="rw-value">
                                            <div class="form-group">
                                                <?php foreach ($ru_work_type_list as $row) : ?>
                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="ru_work_type[]" class="<?= $row["cd_val"] != "2교대" ? "js-check-time" : "" ?>" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], explode(",", $info["ru_work_type"]), "check") ?>>
                                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                            <p class="help-msg <?= count(explode(",", $info["ru_work_type"])) == 3 ? "" : "hidden" ?>">협의 가능</p>
                                        </div>
                                        <script>
                                            (function() {
                                                $("[name='ru_work_type[]']").click(function() {
                                                    if ($("[name='ru_work_type[]']:checked").length == 3) {
                                                        $(this).closest(".value").find(".help-msg").removeClass("hidden");
                                                    } else {
                                                        $(this).closest(".value").find(".help-msg").addClass("hidden");
                                                    }
                                                });

                                                $("[name='ru_price_type[]']").click(function() {
                                                    //console.log($("[name='ru_price_type[]']:checked").length);
                                                    if ($("[name='ru_price_type[]']:checked").length == 3) {
                                                        commonAlert("급여는 2개까지 선택 가능합니다.", function() {});
                                                        return false;
                                                    }
                                                    if ($("[name='ru_price_type[]']:checked").length == 3) {
                                                        $(this).closest(".value").find(".help-msg").removeClass("hidden");
                                                    } else {
                                                        $(this).closest(".value").find(".help-msg").addClass("hidden");
                                                    }

                                                    //월급 선택시 희망연봉 표시 on/off
                                                    //if($("[name='ru_price_type[]']").eq(2).is(":checked")){
                                                    //	$(".cont-price").removeClass("hidden");
                                                    //}else{
                                                    //	$(".cont-price").addClass("hidden");
                                                    //}
                                                });

                                                /* 근무시간 문구 표시 */
                                                function timeChecked(el) {
                                                    var total = document.querySelectorAll('.js-check-time').length;
                                                    var isChecked = document.querySelectorAll('.js-check-time:checked');
                                                    var row = el.closest('.form-row');
                                                    var msg = row.querySelector('.help-msg');

                                                    if (!msg) {
                                                        //msg = document.createElement('P');
                                                        //msg.classList.add('help-msg');
                                                        //msg.innerHTML = '주간 고정, 야간 고정을 선택하셨습니다.';
                                                    }

                                                    if (isChecked.length == total) {
                                                        //    row.appendChild(msg);
                                                    } else {
                                                        //    msg.remove();
                                                    }
                                                }

                                                document.querySelectorAll('.js-check-time').forEach(function(item) {
                                                    item.addEventListener('change', function() {
                                                        timeChecked(item);
                                                    });

                                                    timeChecked(item);
                                                });
                                            }());
                                        </script>
                                    </div>
                                </div>
                            </div>


                            <!-- 자기소개/강점 섹션 -->
                            <div class="rw-section">
                                <h4 class="rw-section-title">자기소개/강점<span class="fixed">*</span></h4>
                                <div class="rw-intro-strength">
                                    <div class="rw-form-row">
                                        <p class="rw-label">자기소개</p>
                                        <div class="rw-value">
                                            <label class="full-width">
                                                <span class="sr-text">자기소개 입력</span>
                                                <textarea name="ru_desc" maxlength="500" class="form-textarea rw-intro-textarea"><?= $info["ru_desc"] ?></textarea>
                                            </label>
                                        </div>

                                    </div>

                                    <div class="rw-form-row">
                                        <p class="rw-label">나의강점</p>
                                        <div class="rw-value">
                                            <div class="form-group col-8 full-width">
                                                <?php foreach ($ru_my_type_list as $row) : ?>
                                                    <label class="form-toggle-btn2 sm">
                                                        <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], explode(",", $info["ru_my_type"]), "check") ?>>
                                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="advantage-form">
                                                <!-- <div class="inline-group">
                                                    <label class="col">
                                                        <input type="text" name="addAdvantage" placeholder="나의 강점을 입력해주세요" autocomplete="off" class="form-text has-confirm">
                                                    </label>
                                                    <button type="button" class="btn-round btn-tertiary is-abs js-add-advantage">추가</button>
                                                </div> -->

                                                <!-- 추가목록 -->
                                                <div class="form-group col-8 full-width">
                                                    <!-- 추가 태그 -->
                                                    <?php if (isset($info["ru_my_type"]) && $info["ru_my_type"] != "") : ?>
                                                        <?php foreach (explode(",", $info["ru_my_type"]) as $row) : ?>
                                                            <?php
                                                            $is_arr = false;
                                                            foreach ($ru_my_type_list as $temp) {
                                                                if ($temp["cd_val"] == $row) {
                                                                    $is_arr = true;
                                                                }
                                                            }
                                                            if ($is_arr == false) :
                                                            ?>
                                                                <label class="form-toggle-btn2">
                                                                    <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?= $row ?>" checked>
                                                                    <span class="btn"><?= $row ?></span>
                                                                </label>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <script>
                                                (function() {
                                                    //목록선택 제한 설정
                                                    document.querySelectorAll('input[data-select-limit]').forEach(function(item) {
                                                        item.addEventListener('change', function() {
                                                            var childItems = document.querySelectorAll('[name="' + this.getAttribute('name') + '"]:checked');

                                                            if (this.checked == childItems.length > this.getAttribute('data-select-limit')) {
                                                                this.checked = false;
                                                            }
                                                        });
                                                    });

                                                    document.querySelector('.js-add-advantage').addEventListener('click', function() {
                                                        var value = document.querySelector('input[name="addAdvantage"]').value;
                                                        var label;

                                                        if (!value) return;

                                                        label = document.createElement('LABEL');
                                                        value = value.trim().replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-+<>@\#$%&\\\=\(\'\"]/g, '');

                                                        label.setAttribute('class', 'form-toggle-btn2');
                                                        label.innerHTML = '<input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="' + value + '"> <span class="btn">' + value + '</span>';

                                                        document.querySelector('.advantage-form .form-group').appendChild(label);

                                                        label.querySelector('input').addEventListener('change', function() {
                                                            var childItems = document.querySelectorAll('[name="' + this.getAttribute('name') + '"]:checked');

                                                            if (this.checked == childItems.length > this.getAttribute('data-select-limit')) {
                                                                this.checked = false;
                                                            }
                                                        });

                                                        document.querySelector('input[name="addAdvantage"]').value = "";
                                                    });
                                                }());
                                            </script>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <!-- 경력사항 섹션 -->
                            <div class="rw-section extra-section">
                                <h4 class="rw-section-title">경력사항</h4>
                                <div class="rw-career">
                                    <!-- 업종 및 기업명 form-row -->
                                    <div class="rw-form-row">
                                        <div class="rw-value rw-dual-col">
                                            <div class="form-group">
                                                <!-- 기업명 섹션 -->
                                                <div class="rw-col-half">
                                                    <p class="rw-label">기업명</p>
                                                    <input type="text" name="company[]" maxlength="13" placeholder="" autocomplete="off" class="form-text">
                                                </div>
                                                <!-- 업종 섹션 -->
                                                <div class="rw-col-half">
                                                    <p class="rw-label">업종</p>
                                                    <label class="form-selectbox">
                                                        <span class="sr-text">업종선택</span>
                                                        <select name="category[]" class="added-career-form">
                                                            <option value="" selected disabled>업종</option>
                                                            <?php
                                                            // 표시 허용 업종 화이트리스트 (간단경력용)
                                                            $allowed_work_list = ["생산", "사무직", "운전배달", "물류", "식당", "서비스", "매장관리", "건설", "상담"];
                                                            foreach ($ru_wrok_list as $row) :
                                                                // 허용 목록 이외 항목은 출력 제외
                                                                if (!in_array($row["cd_val"], $allowed_work_list, true)) {
                                                                    continue;
                                                                }
                                                            ?>
                                                                <option value="<?= $row["cd_val"] ?>"><?= $row["cd_val"] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <span class="liner"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 근무기간 form-row -->
                                    <div class="rw-form-row">
                                        <p class="rw-label">근무기간</p>
                                        <div class="rw-value">
                                            <div class="form-group rw-career-period-wrapper">
                                                <div class="rw-period-field">
                                                    <label class="rw-block-label">
                                                        <span class="sr-text">근무기간 시작일</span>
                                                        <input type="text" name="period_start[]" placeholder="" autocomplete="off" class="form-text num js-datepicker">
                                                    </label>
                                                    <button type="button" class="btn-calendar">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </div>
                                                <span class="rw-career-period-separator">~</span>
                                                <div class="rw-period-field">
                                                    <label class="rw-block-label">
                                                        <span class="sr-text">근무기간 종료일</span>
                                                        <input type="text" name="period_end[]" placeholder="" autocomplete="off" class="form-text num js-datepicker">
                                                    </label>
                                                    <button type="button" class="btn-calendar">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 목록 -->
                                    <ol class="added-list career_list">
                                        <?php if (is_array($career_list) && !empty($career_list)) :
                                            foreach ($career_list as $row):
                                                $rc_type = $row['rc_type'];
                                                $rc_name = $row['rc_name'];
                                                $rc_date = $row['rc_date'];
                                        ?>
                                                <li>
                                                    <div class="form-group">
                                                        <div class="col sm">
                                                            <input type="text" name="category[]" value="<?= $rc_type; ?>" disabled class="form-text txt-color2">
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" name="company[]" maxlength="13" disabled value="<?= $rc_name; ?>" class="form-text">
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" name="period[]" disabled value="<?= $rc_date; ?>" class="form-text num">
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn-clear js-del-item">삭제</button>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ol>

                                    <!-- 경력사항 추가 버튼 -->
                                    <div class="rw-form-row">
                                        <button type="button" class="js-add-career">경력사항 추가</button>
                                    </div>

                                    <!-- 자격증명 및 취득일자 form-row -->
                                    <div class="rw-form-row">
                                        <div class="rw-value rw-dual-col">
                                            <div class="form-group">
                                                <!-- 자격증명 섹션 -->
                                                <div class="rw-col-half">
                                                    <p class="rw-label">자격증명</p>
                                                    <input type="text" name="license[]" placeholder="" autocomplete="off" class="form-text added-license-form">
                                                </div>
                                                <!-- 취득일자 섹션 -->
                                                <div class="rw-col-half rw-col-with-calendar">
                                                    <p class="rw-label">취득일자</p>
                                                    <div class="rw-calendar-input-wrapper">
                                                        <input type="text" name="regdate[]" placeholder="" autocomplete="off" class="form-text num js-datepicker">
                                                        <button type="button" class="btn-calendar">
                                                            <i class="fa fa-calendar"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 자격증 목록 -->
                                    <ol class="added-list license_list">
                                    </ol>

                                    <!-- 자격증 추가 버튼 -->
                                    <div class="rw-form-row">
                                        <button type="button" class="js-add-license">자격증 추가</button>
                                    </div>

                                </div>

                                <!-- 입력폼 템플릿 -->
                                <template id="careerTemp">
                                    <li>
                                        <div class="form-group">
                                            <div class="col sm">
                                                <input type="text" name="category[]" value="{$category}" disabled class="form-text txt-color2">
                                            </div>
                                            <div class="col">
                                                <input type="text" name="company[]" maxlength="13" value="{$company}" disabled class="form-text">
                                            </div>
                                            <div class="col">
                                                <input type="text" name="period[]" value="{$period}" disabled class="form-text num">
                                            </div>
                                        </div>
                                        <button type="button" class="btn-clear js-del-item">삭제</button>
                                    </li>
                                </template>

                                <!-- 자격증 입력폼 템플릿 -->
                                <template id="licenseTemp">
                                    <li>
                                        <div class="form-group">
                                            <div class="col">
                                                <input type="text" name="license[]" value="{$license}" disabled class="form-text">
                                            </div>
                                            <div class="col">
                                                <input type="text" name="regdate[]" value="{$regdate}" disabled class="form-text num">
                                            </div>
                                        </div>
                                        <button type="button" class="btn-clear js-del-item">삭제</button>
                                    </li>
                                </template>

                                <script>
                                    (function() {
                                        function chgYear(instance) {
                                            var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                                                year = instance.currentYear;
                                            $con.innerHTML = '<span class="month">' + year + '</span>';
                                        }

                                        function totalRange(rangeDate) {
                                            var diffYear, diffMonth;

                                            if (rangeDate.indexOf('~') == -1) return '';

                                            diffYear = flatpickr.parseDate(rangeDate.split(' ~ ')[1], 'Y.m').getFullYear() - flatpickr.parseDate(rangeDate.split(' ~ ')[0], 'Y.m').getFullYear();
                                            diffMonth = flatpickr.parseDate(rangeDate.split(' ~ ')[1], 'Y.m').getMonth() - flatpickr.parseDate(rangeDate.split(' ~ ')[0], 'Y.m').getMonth();
                                            diffYear = diffYear > 0 ? diffYear + '년' : '';
                                            diffMonth = diffMonth > 0 ? diffMonth + '개월' : '';
                                            return '';
                                            //return '(' + diffYear + diffMonth + ')';
                                        }

                                        function initDatepicker(el) {
                                            if (typeof flatpickr == 'undefined') return;

                                            //달력 생성
                                            flatpickr(el, {
                                                mode: 'single',
                                                locale: 'ko',
                                                nextArrow: '<span class="sr-text">다음</span>',
                                                prevArrow: '<span class="sr-text">이전</span>',
                                                disableMobile: true,
                                                plugins: [
                                                    new monthSelectPlugin({
                                                        shorthand: true,
                                                        dateFormat: "Y.m",
                                                        altFormat: "Y.m",
                                                    })
                                                ],
                                                onYearChange: function(selectedDates, dateStr, instance) {
                                                    chgYear(this);
                                                },
                                                onReady: function() {
                                                    chgYear(this);
                                                }
                                            });
                                        }

                                        window.addEventListener('DOMContentLoaded', function() {
                                            // 모든 datepicker 초기화
                                            document.querySelectorAll('.js-datepicker').forEach(function(el) {
                                                initDatepicker(el);
                                            });

                                            // 달력 버튼 이벤트
                                            document.querySelectorAll('.btn-calendar').forEach(function(btn) {
                                                btn.addEventListener('click', function(e) {
                                                    e.preventDefault();
                                                    var input = this.parentNode.querySelector('.js-datepicker');
                                                    if (input && input._flatpickr) {
                                                        input._flatpickr.open();
                                                    }
                                                });
                                            });

                                            //경력 목록 추가
                                            document.querySelector('.js-add-career').addEventListener('click', function(e) {
                                                e.preventDefault();
                                                var btn = this,
                                                    max = 9,
                                                    temp = document.getElementById('careerTemp').content.cloneNode(true),
                                                    formContainer = btn.closest('.form-row').parentNode,
                                                    list = formContainer.querySelector('.career_list'),
                                                    lastItem = null;

                                                var category = formContainer.querySelector('[name="category[]"]:not([disabled])'),
                                                    company = formContainer.querySelector('[name="company[]"]:not([disabled])'),
                                                    periodStart = formContainer.querySelector('[name="period_start[]"]'),
                                                    periodEnd = formContainer.querySelector('[name="period_end[]"]');

                                                if (!category || !category.value) {
                                                    if (category) category.focus();
                                                    return;
                                                }

                                                if (!company || !company.value) {
                                                    if (company) company.focus();
                                                    return;
                                                }

                                                if (!periodStart || !periodEnd || !periodStart.value || !periodEnd.value) {
                                                    if (!periodStart || !periodStart.value) {
                                                        if (periodStart) periodStart.focus();
                                                    } else if (periodEnd) {
                                                        periodEnd.focus();
                                                    }
                                                    return;
                                                }

                                                if (list.children.length < max) {
                                                    var categoryText = category.value;
                                                    var companyText = company.value;
                                                    var periodText = periodStart.value + ' ~ ' + periodEnd.value;

                                                    var newItem = temp.children[0];
                                                    var formGroup = newItem.querySelector('.form-group');
                                                    formGroup.children[0].querySelector('input').value = categoryText;
                                                    formGroup.children[1].querySelector('input').value = companyText;
                                                    formGroup.children[2].querySelector('input').value = periodText;

                                                    list.insertBefore(temp, list.firstChild);
                                                    lastItem = list.children[0];

                                                    lastItem.querySelector('.js-del-item').addEventListener('click', function() {
                                                        if (list.children.length <= 3) {
                                                            btn.style.display = '';
                                                        }
                                                    });

                                                    // select 초기화
                                                    var selectElement = category.closest('.form-selectbox').querySelector('select');
                                                    selectElement.selectedIndex = 0;
                                                    selectElement.parentNode.querySelector('.liner').textContent = selectElement.children[0].textContent;
                                                    company.value = '';
                                                    periodStart.value = '';
                                                    periodEnd.value = '';
                                                }
                                            });

                                            //자격증 목록 추가
                                            document.querySelector('.js-add-license').addEventListener('click', function(e) {
                                                e.preventDefault();
                                                var btn = this,
                                                    max = 4,
                                                    temp = document.getElementById('licenseTemp').content.cloneNode(true),
                                                    formContainer = btn.closest('.form-row').parentNode,
                                                    list = formContainer.querySelector('.license_list'),
                                                    lastItem;

                                                var license = formContainer.querySelector('[name="license[]"]:not([disabled])'),
                                                    regdate = formContainer.querySelector('[name="regdate[]"]:not([disabled])');

                                                if (!license || !license.value) {
                                                    if (license) license.focus();
                                                    return;
                                                }

                                                if (!regdate || !regdate.value) {
                                                    if (regdate) regdate.focus();
                                                    return;
                                                }

                                                if (list.children.length < max) {
                                                    var licenseText = license.value;
                                                    var regdateText = regdate.value;

                                                    var newItem = temp.children[0];
                                                    var formGroup = newItem.querySelector('.form-group');
                                                    formGroup.children[0].querySelector('input').value = licenseText;
                                                    formGroup.children[1].querySelector('input').value = regdateText;

                                                    list.insertBefore(temp, list.firstChild);
                                                    lastItem = list.children[0];

                                                    lastItem.querySelector('.js-del-item').addEventListener('click', function() {
                                                        if (list.children.length <= max) {
                                                            btn.style.display = '';
                                                        }
                                                    });

                                                    license.value = '';
                                                    regdate.value = '';
                                                }
                                            });
                                        });

                                    }());
                                </script>
                            </div>
                        </div>
                </div>

                <!-- 상세등록 섹션 -->
                <div class="detail-register-section">
                    <a href="#" id="detailRegisterBtn" class="detail-register-link">상세등록</a>
                </div>

                <div class="form-action-wrap">
                    <button type="button" id="btnStep" class="btn-round btn-tertiary"><?= $info != null && $info["ru_idx"] != "0" ? "수정" : "등록" ?>완료</button>
                </div>
                </form>
            </div>
        </div>
</div>
</main><!-- //page-content -->
</div><!-- //page -->
<script>
    // 검색 완료 선택 처리
    function selectAddrInfo(zipcode, doro, jibun, addr, x, y) {
        // 2025.02.10 위치 보안: 상세 주소 대신 동네 중심점 좌표 저장
        var geocoder = new kakao.maps.services.Geocoder();

        // 지번 주소에서 번지 및 그 뒤의 건물명 등을 모두 제거하고 동(읍/면)까지만 추출
        // 예: "경기도 시흥시 정왕동 1866-1 정왕3동주민센터" -> "경기도 시흥시 정왕동"
        // 숫자(번지)가 나오기 직전까지만 잘라냄
        var dongAddr = jibun;
        var match = jibun.match(/^(.*?)(\d+[\d\-]*)/); // 숫자(번지) 시작 부분 찾기
        if (match) {
            dongAddr = match[1].trim();
        }

        geocoder.addressSearch(dongAddr, function(result, status) {
            var finalX = x; // 기본값: 원래 받아온 x
            var finalY = y; // 기본값: 원래 받아온 y

            if (status === kakao.maps.services.Status.OK) {
                if (result[0].x && result[0].y) {
                    finalX = result[0].x;
                    finalY = result[0].y;
                }
            }

            // ---------- 기존 주소 추가 로직 ----------
            // 주소 정리 함수로 지번 주소를 정리하여 표시
            var displayAddr = (typeof fRemoveBigAdrJS === 'function') ? fRemoveBigAdrJS(jibun) : jibun;

            var html = "";
            html += '<li>';
            html += '<input type="hidden" name="ad_zipcode[]" value="' + zipcode + '" />';
            html += '<input type="hidden" name="ad_jibun[]" value="' + jibun + '" />';
            html += '<input type="hidden" name="ad_doro[]" value="' + doro + '" />';
            // ad_addr에는 지번을 클릭하든 도로명을 클릭하든 항상 지번을 저장
            html += '<input type="hidden" name="ad_addr[]" value="' + jibun + '" />';

            // 여기서 변경된 좌표(finalX, finalY)를 사용
            html += '<input type="hidden" name="ad_gps_x[]" value="' + finalX + '" />';
            html += '<input type="hidden" name="ad_gps_y[]" value="' + finalY + '" />';

            html += '<p>' + displayAddr + '</p>';
            html += '<button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>';
            html += '</li>';

            $("#addr_location").append(html);

            //팝업 종료
            $("#changeArea").removeClass("on");
            $("#changeArea")[0].hidden = true;
            // body 스크롤 잠금 해제
            document.body.style.overflow = '';
        });
    }

    function showAddrInfo(maxlen) {
        if ($("#addr_location").find("li").length >= maxlen) {
            commonAlert("희망지역은 " + (maxlen - 1) + "개까지 등록 가능합니다.", function() {});
            return;
        }
        var $panel = $("#changeArea");
        // 패널을 열 때 hidden 속성 제거 및 on 클래스 추가
        $panel[0].hidden = false;
        $panel.addClass("on");
        // body 스크롤 잠금
        document.body.style.overflow = 'hidden';
        // 포커스 설정
        setTimeout(function() {
            $panel[0].focus();
        }, 100);
    }

    // 상세등록 버튼 클릭 이벤트
    $(document).on('click', '#detailRegisterBtn', function(e) {
        e.preventDefault();
        // 추가정보 섹션(경력사항) 표시
        $('.extra-section').addClass('show');
        // 상세등록 버튼 숨김
        $(this).closest('.detail-register-section').hide();
    });

    $(document).ready(function() {
        // 자동 이력서 입력 (웹) - join_mem_finish에서 저장한 payload 사용
        (function() {
            var RESUME_AUTOFILL_KEY = "resume_autofill_payload";
            var RESUME_AUTOFILL_TS_KEY = "resume_autofill_ts";
            var RESUME_AUTOFILL_MAX_AGE = 5 * 60 * 1000;

            function getQueryParam(name) {
                var params = new URLSearchParams(window.location.search || "");
                return params.get(name);
            }

            function clearAutofillPayload() {
                try {
                    localStorage.removeItem(RESUME_AUTOFILL_KEY);
                    localStorage.removeItem(RESUME_AUTOFILL_TS_KEY);
                } catch (e) {}
            }

            if (getQueryParam("autofill") !== "1") {
                return;
            }

            var payloadRaw = null;
            var payload = null;
            var ts = 0;
            try {
                payloadRaw = localStorage.getItem(RESUME_AUTOFILL_KEY);
                ts = parseInt(localStorage.getItem(RESUME_AUTOFILL_TS_KEY), 10) || 0;
                payload = payloadRaw ? JSON.parse(payloadRaw) : null;
            } catch (e) {
                payload = null;
            }

            if (!payload || !ts || Date.now() - ts > RESUME_AUTOFILL_MAX_AGE) {
                clearAutofillPayload();
                return;
            }

            if ($("#addr_location").find("li").length > 0) {
                clearAutofillPayload();
                return;
            }

            if (payload.ru_name_seed_addr && $("[name='ru_name']").val() === "") {
                $("[name='ru_name']").val(payload.ru_name_seed_addr + " 이력서");
            }

            if (typeof selectAddrInfo === "function") {
                selectAddrInfo(
                    payload.ad_zipcode || "",
                    payload.ad_doro || "",
                    payload.ad_jibun || "",
                    payload.ad_addr || "",
                    payload.ad_gps_x || "",
                    payload.ad_gps_y || ""
                );
            }

            clearAutofillPayload();

            // 자동 저장 플로우 시작
            window.__autoResumeFlow = true;
            setTimeout(function() {
                // 필수값 자동 설정
                if ($("[name='ru_name']").val() === "" && payload.ru_name) {
                    $("[name='ru_name']").val(payload.ru_name);
                }

                if ($("[name='ru_nationality']:checked").length === 0) {
                    var $nation = $("[name='ru_nationality'][value='내국인']");
                    if ($nation.length) {
                        $nation.prop("checked", true);
                    } else {
                        $("[name='ru_nationality']").first().prop("checked", true);
                    }
                }

                if ($("[name='ru_work[]']:checked").length === 0) {
                    $("[name='ru_work[]']").first().prop("checked", true);
                }

                if ($("[name='ru_price_type[]']:checked").length === 0) {
                    $("[name='ru_price_type[]']").first().prop("checked", true);
                }

                if ($("[name='ru_work_type[]']:checked").length === 0) {
                    $("[name='ru_work_type[]']").first().prop("checked", true);
                }

                if ($("[name=ru_img1]").val() == "" && $("[name=ru_img1_ori]").val() == "") {
                    // 기본 프로필 이미지로 대체
                    var $form = $("#frmAll");
                    if ($form.find("[name=ru_img1_ori]").length === 0) {
                        $form.append('<input type="hidden" name="ru_img1_ori" value="/resource/shop/web/img/avatar_resume_w.png">');
                    } else {
                        $form.find("[name=ru_img1_ori]").val("/resource/shop/web/img/avatar_resume_w.png");
                    }
                }

                $("#btnStep").trigger("click");
            }, 400);
        })();

        // 주소 추가 버튼 클릭 이벤트 처리
        $(document).on('click', '.js-add-address', function(e) {
            e.preventDefault();
            e.stopPropagation();
            showAddrInfo(2);
        });

        // 페이지 로드 시 기존 주소들을 주소 정리 함수로 처리하여 표시
        if (typeof fRemoveBigAdrJS === 'function') {
            $("#addr_location .addr-display").each(function() {
                var $display = $(this);
                var $li = $display.closest("li");
                var jibun = $li.find('input[name="ad_jibun[]"]').val();
                if (jibun) {
                    $display.text(fRemoveBigAdrJS(jibun));
                }
            });
        }

        <?php
        // SNS 로그인 여부 확인
        $is_sns_login = !empty($mem_info["mm_join_type"]) && in_array(strtoupper($mem_info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER", "GOOGLE", "WECHAT"]);
        // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
        $is_authenticated = ($mem_info != null && (strpos($mem_info["mm_di"], "MC0") === 0 || $is_sns_login));

        if (!$is_authenticated) {
        ?>
            commonAlert("본인인증 후에 이력서를 등록 하실 수 있습니다.", function() {
                location.href = "/mypage/mypage?reqgbn=pass";
            });
        <?php } ?>

        //처리
        $("#btnStep").click(function() {
            if ($("[name='ru_name']").val() == "") {
                commonAlert("이력서명을 입력해주세요.", function() {
                    $("[name='ru_name']").focus();
                });
                return;
            }
            if ($("[name='ru_work[]']").is(":checked") == false) {
                commonAlert("희망업무는 하나이상 선택해주세요.", function() {});
                return;
            }

            // 국적 필수 검증
            if ($("[name='ru_nationality']").is(":checked") == false) {
                commonAlert("국적을 선택해주세요.", function() {});
                return;
            }

            if ($("#addr_location li").filter(function() {
                    return $(this).find('input[name="ad_addr[]"]').val().trim() !== "";
                }).length == 0) {
                commonAlert("희망지역을 선택해주세요.", function() {});
                return;
            }

            if ($("[name='ru_price_type[]']").is(":checked") == false) {
                commonAlert("급여형태는 하나이상 선택해주세요.", function() {});
                return;
            }

            if ($("[name='ru_price_type[]']").eq(2).is(":checked") == true && ($("[name='ru_month_price']").val() == "" || $("[name='ru_month_price']").val() == "0")) {
                //commonAlert("희망연봉을 입력해주세요.",function(){
                //	$("[name='ru_month_price']").focus();
                //});
                //return;
            }

            if ($("[name='ru_work_type[]']").is(":checked") == false) {
                commonAlert("근무형태는 하나이상 선택해주세요.", function() {});
                return;
            }

            //if($("[name='ru_week_yn']").is(":checked")==false && $("[name='ru_week[]']").is(":checked")==false){
            //    commonAlert("근무가능 요일을 하나이상 선택해주세요.",function(){});
            //    return;
            //}

            if ($("[name='ru_work_dt']").val() == "") {
                $("[name='ru_work_dt']").val('<?= Date("Y-m-d") ?>');
                //    commonAlert("근무가능일을 선택해주세요.",function(){});
                //    return;
            }

            //if($("[name='ru_edu']").is(":checked")==false){
            //    commonAlert("최종학력을 선택해주세요.",function(){});
            //    return;
            //}

            // 간단경력 데이터 수집 (disabled 필드 포함, 삭제된 항목 제외)
            var arr_career = "";
            var $carrer_obj = $(".career_list");
            // career_list의 li 요소를 직접 순회하여 삭제된 항목 제외
            $carrer_obj.find("li").each(function() {
                var $li = $(this);
                // 삭제된 항목이거나 display:none인 경우 제외
                if ($li.hasClass("is-deleted") || $li.css("display") === "none") {
                    return true; // continue
                }

                // 해당 li 내의 입력 필드 찾기
                var $category = $li.find("[name='category[]']");
                var $company = $li.find("[name='company[]']");
                var $period = $li.find("[name='period[]']");

                // 값 추출 (disabled 필드도 jQuery의 val()로 읽을 수 있음)
                var category = $category.length > 0 ? $category.val() : "";
                var company = $company.length > 0 ? $company.val() : "";
                var period = $period.length > 0 ? $period.val() : "";

                // 모든 값이 있는 경우에만 추가
                if (category && company && period) {
                    if (arr_career == "") {
                        arr_career = category + "|||" + company + "|||" + period;
                    } else {
                        arr_career = arr_career + "&&&" + category + "|||" + company + "|||" + period;
                    }
                }
            });
            $("[name=arr_career]").val(arr_career);

            var ru_work_img = "";
            for (var icnt = 0; icnt < $("[name='ru_work[]']:checked").length; icnt++) {
                if (ru_work_img == "") {
                    ru_work_img = $("[name='ru_work[]']:checked").eq(icnt).data("img");
                } else {
                    ru_work_img += "," + $("[name='ru_work[]']:checked").eq(icnt).data("img");
                }
            }
            $("[name=ru_work_img]").val(ru_work_img);

            //if($("[name='ru_desc']").val()==""){
            //    commonAlert("자기소개서를 입력해주세요.",function(){});
            //    return;
            //}

            var arr_certi = "";
            var $license_obj = $(".license_list");
            for (var icnt = 0; icnt < $license_obj.find("[name='license[]']").length; icnt++) {
                if ($license_obj.find("[name='license[]']").eq(icnt).val() != "") {
                    if ($license_obj.find("[name='license[]']").eq(icnt).val() == "") {
                        commonAlert("자격증명을 입력해주세요.", function() {
                            $license_obj.find("[name='license[]']").eq(icnt).focus();
                        });
                        return;
                    }

                    if ($license_obj.find("[name='regdate[]']").eq(icnt).val() == "") {
                        commonAlert("취득일자를 선택해주세요.", function() {
                            $license_obj.find("[name='regdate[]']").eq(icnt).focus();
                        });
                        return;
                    }

                    if (arr_certi == "") {
                        arr_certi = $license_obj.find("[name='license[]']").eq(icnt).val() + "|||" + $license_obj.find("[name='regdate[]']").eq(icnt).val();
                    } else {
                        arr_certi = arr_certi + "&&&" + $license_obj.find("[name='license[]']").eq(icnt).val() + "|||" + $license_obj.find("[name='regdate[]']").eq(icnt).val();
                    }
                }
            }
            $("[name=arr_certi]").val(arr_certi);

            if ($("[name=ru_img1]").val() == "" && $("[name=ru_img1_ori]").val() == "") {
                commonAlert("첨부사진을 1나이상 선택해주세요.", function() {});
                return;
            }

            var form = $('#frmAll');
            var params = new FormData(form[0]);
            params.set("ru_month_price", params.get("ru_month_price").replaceAll(",", ""));
            fileAjaxLoading("/mypage_proc/resume_write", params, function(ret) {
                if (ret.code == "00") {
                    showPreviewInfo(ret.data);
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });

        $(".comma").on("keyup", function() {
            $(this).val($(this).val().replace(/\,/g, '').replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'));
        });


        $("[name=ru_week_yn]").click(function() {
            if ($(this).is(":checked")) {
                $("[name='ru_week[]']").prop("checked", true);
            } else {
                $("[name='ru_week[]']").prop("checked", false);
            }
        });

        $("[name='ru_week[]']").click(function() {
            if ($("[name='ru_week[]']:checked").length == 7) {
                $("[name=ru_week_yn]").prop("checked", true);
            } else {
                $("[name=ru_week_yn]").prop("checked", false);
            }
        });

        // 평일(월~금) 기본값 설정 (페이지 로드 시)
        var weekdays = ['월', '화', '수', '목', '금'];
        var hasChecked = false;

        $("[name='ru_week[]']:checked").each(function() {
            hasChecked = true;
        });

        if (!hasChecked) {
            $("[name='ru_week[]']").each(function() {
                if (weekdays.indexOf($(this).val()) !== -1) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            });
            $("[name='ru_week_yn']").prop('checked', false);
        }

        comAjax("/common_proc/get_member_info", {}, function(ret) {
            //console.log('get_member_info');
            //console.log(ret);
            if (ret.code == "00") {
                //console.log('00');
                //selectAddrInfo(ret.data.mm_zipcode, ret.data.mm_doro, ret.data.mm_jibun, ret.data.mm_addr, ret.data.mm_gps_x, ret.data.mm_gps_y);
            }
        });

        // 자기소개 textarea 높이 자동 조절 (스크롤 위치 보존)
        var $ruDesc = $("textarea[name='ru_desc']");
        var minHeight = 324; // 최소 높이

        function adjustHeight() {
            if ($ruDesc.length && $ruDesc[0]) {
                // 스크롤 위치 저장
                var scrollTop = $ruDesc[0].scrollTop;
                // 높이를 1px로 초기화하여 정확한 scrollHeight 측정
                $ruDesc.css('height', '1px');
                // scrollHeight를 기준으로 높이 설정 (최소 높이 보장)
                var newHeight = Math.max($ruDesc[0].scrollHeight, minHeight);
                $ruDesc.css('height', newHeight + 'px');
                // 스크롤 위치 복원
                $ruDesc[0].scrollTop = scrollTop;
            }
        }

        // 초기 설정
        setTimeout(adjustHeight, 100);

        // 입력 시 높이 재설정 (스크롤 위치 보존)
        $ruDesc.on('input', function() {
            var scrollTop = this.scrollTop;
            setTimeout(function() {
                adjustHeight();
                $ruDesc[0].scrollTop = scrollTop;
            }, 10);
        });

    }); //ready

    function showPreviewInfo(info) {
        if (window.__autoResumeFlow === true) {
            if (info && info.ru_idx) {
                $("[name=ru_idx]").val(info.ru_idx);
            }
            var params = {};
            params["ru_idx"] = $("[name=ru_idx]").val();
            comAjax("/mypage_proc/resume_complete", params, function(ret) {
                if (ret.code == "00") {
                    location.href = "/recruit/job_list?auto_resume=1";
                } else if (ret.code == "91") {
                    commonAlert(ret.msg, function() {
                        fManCheck();
                    });
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });
            return;
        }
        var str_ru_work_img = "";
        var str_ru_week = "";
        var str_ru_career = "";
        var str_ru_certi = "";
        var str_ru_type = "";
        var str_img = "";
        var str_month_price = "";

        if (info.ru_work_img) {
            for (var icnt = 0; icnt < info.ru_work_img.length; icnt++) {
                str_ru_work_img += "<img src='" + info.ru_work_img[icnt] + "' alt='생산' class='category-label'>";
            }
        }

        if (info.ru_week) {
            for (var icnt = 0; icnt < info.ru_week.length; icnt++) {
                str_ru_week += "<li aria-hidden='false'>" + info.ru_week[icnt] + "</li>";
            }
        }

        if (info.arr_career) {
            for (var icnt = 0; icnt < info.arr_career.split("&&&").length; icnt++) {
                var temp = info.arr_career.split("&&&")[icnt].split("|||");
                if (temp.length > 2) {
                    str_ru_career += "<li><span>" + temp[0] + "</span><span>" + temp[1] + "</span><em>" + temp[2] + "</em></li>";
                }
            }
        }

        if (info.arr_certi) {
            for (var icnt = 0; icnt < info.arr_certi.split("&&&").length; icnt++) {
                var temp = info.arr_certi.split("&&&")[icnt].split("|||");
                if (temp.length > 1) {
                    str_ru_certi += "<li><span>자격증</span><span>" + temp[0] + "</span><em>" + temp[1] + "</em></li>";
                }
            }
        }

        if (info.ru_my_type) {
            for (var icnt = 0; icnt < info.ru_my_type.length; icnt++) {
                str_ru_type += "<p class='tag'>" + info.ru_my_type[icnt] + "</p>";
            }
        }

        if (info.ru_img1) {
            str_img += "<div class='pic-card'><img src='" + info.ru_img1 + "' alt='' style='min-width: auto; min-height: auto;'></div>";
        }

        if (info.ru_img2) {
            str_img += "<div class='pic-card'><img src='" + info.ru_img2 + "' alt='' style='min-width: auto; min-height: auto;'></div>";
        }

        if (info.ru_img3) {
            str_img += "<div class='pic-card'><img src='" + info.ru_img3 + "' alt='' style='min-width: auto; min-height: auto;'></div>";
        }

        if (info.ru_img4) {
            str_img += "<div class='pic-card'><img src='" + info.ru_img4 + "' alt='' style='min-width: auto; min-height: auto;'></div>";
        }

        if (info.ru_img5) {
            str_img += "<div class='pic-card'><img src='" + info.ru_img5 + "' alt='' style='min-width: auto; min-height: auto;'></div>";
        }

        if (info.ru_price_type.indexOf("월급") > -1 && info.ru_month_price != "0") {
            str_month_price = '<tr><td class="label">급여</td><td class="value">' + info.ru_month_price.format() + '원</td></tr>';
        }

        // 주소 처리: fRemoveBigAdrJS 함수 적용
        var displayAddr = '';

        // ru_addr이 배열인 경우 처리
        if (Array.isArray(info.ru_addr)) {
            // 배열을 쉼표로 구분된 문자열로 변환
            displayAddr = info.ru_addr.filter(function(addr) {
                return addr && addr.trim() !== '';
            }).join(',');
        } else {
            // 문자열인 경우 그대로 사용
            displayAddr = info.ru_addr || '';
        }

        // 주소가 있고 '무관'이 아니면 fRemoveBigAdrJS 함수 적용
        if (displayAddr && displayAddr !== '' && displayAddr !== '무관' && typeof fRemoveBigAdrJS === 'function') {
            // 쉼표로 구분된 여러 주소 처리
            if (displayAddr.indexOf(',') > -1) {
                var addrArray = displayAddr.split(',').map(function(addr) {
                    return addr.trim();
                }).filter(function(addr) {
                    return addr !== '';
                });
                var formattedAddrs = [];
                addrArray.forEach(function(addr) {
                    var formatted = fRemoveBigAdrJS(addr);
                    if (formatted && formattedAddrs.indexOf(formatted) === -1) {
                        formattedAddrs.push(formatted);
                    }
                });
                displayAddr = formattedAddrs.length > 0 ? formattedAddrs.join(' | ') : '';
            } else {
                displayAddr = fRemoveBigAdrJS(displayAddr);
            }
        }

        // 주소가 비어있으면 '무관'으로 설정
        if (!displayAddr || displayAddr === '') {
            displayAddr = '무관';
        }

        if (str_ru_week == '') {
            str_ru_week = '무관';
        }
        if (!info.ru_work_dt) {
            info.ru_work_dt = '근무일무관';
            info.ru_work_dt_yoil = '-';
        }

        openPopup('modal', {
            content: '<div class="regist-info">' +
                '<h5 class="name">' + info.mm_name + '</h5>' +
                '<table>' +
                '<caption><span class="sr-text">회원가입정보</span></caption>' +
                '<colgroup><col><col></colgroup>' +
                '<tbody>' +
                '<tr>' +
                '<td class="label">업무</td>' +
                '<td class="value">' +
                str_ru_work_img +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">지역</td>' +
                '<td class="value">' + displayAddr + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">급여형태</td>' +
                '<td class="value">' + (info.ru_price_type.length > 2 ? "협의가능" : info.ru_price_type) + '</td>' +
                '</tr>' + str_month_price +
                '<tr>' +
                '<td class="label">근무형태</td>' +
                '<td class="value">' + (info.ru_work_type.length > 2 ? "협의가능" : info.ru_work_type) + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">근무요일</td>' +
                '<td class="value">' +
                '<ul class="working-day">' +
                str_ru_week +
                '</ul>' +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">근무시작일</td>' +
                '<td class="value">' + info.ru_work_dt + '(' + info.ru_work_dt_yoil + ')' + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">최종학력</td>' +
                '<td class="value">' + info.ru_edu + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">경력사항</td>' +
                '<td class="value">' +
                '<ul class="period-list">' +
                str_ru_career +
                '</ul>' +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">자격증</td>' +
                '<td class="value">' +
                '<ul class="period-list">' +
                str_ru_certi +
                '</ul>' +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td colspan="2" class="label">자기소개</td>' +
                '</tr>' +
                '<tr>' +
                '<td colspan="2" class="value introduce"><div style="word-wrap: break-word; white-space: normal; width: 410px;">' + info.ru_desc + '</div></td>' +
                '</tr>' +
                '<tr>' +
                '<td colspan="2" class="label">나의강점</td>' +
                '</tr>' +
                '<tr>' +
                '<td colspan="2" class="value">' +
                '<div class="hashtag">' +
                str_ru_type +
                '</div>' +
                '</td>' +
                '</tr>' +
                '</tbody>' +
                '</table>' +
                '</div>',
            firstButton: '정보수정',
            secondButton: ($("[name=ru_idx]").val() != "0" ? '수정완료' : '등록완료'),
            firstCallback: function() {

            },
            secondCallback: function() {
                var params = {};
                params["ru_idx"] = $("[name=ru_idx]").val();
                comAjax("/mypage_proc/resume_complete", params, function(ret) {
                    if (ret.code == "00") {
                        location.href = "/mypage/resume_list";
                    } else if (ret.code == "91") {
                        //24.09.01 실명인증여부
                        commonAlert(ret.msg, function() {
                            fManCheck();
                        });
                        return;
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
            }
        });
    }
</script>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>