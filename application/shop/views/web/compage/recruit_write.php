<script type="text/javascript" src="/resource/shop/web/smarteditor2/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="/resource/shop/mobile/js/logger.js"></script>

<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab2"; ?>
      <?php $tab_on2 = "menu2"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
      <!--get_base_url():website/dev_nowapk-->
      
      <!-- <script>
        var baseUrl = <?= json_encode(get_base_url()) ?>;
        console.log("get_base_url()======>",baseUrl);
    
      </script> -->
      <?php
      // 인증 상태 계산 (파일 전체에서 사용)
      $mem_info_di = isset($info["mem_info"]["mm_di"]) ? $info["mem_info"]["mm_di"] : null;
      $mem_info_join_type = isset($info["mem_info"]["mm_join_type"]) ? $info["mem_info"]["mm_join_type"] : null;
      $is_sns_login = !empty($mem_info_join_type) && in_array(strtoupper($mem_info_join_type), ["KAKAO", "APPLE", "NAVER"]);
      // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
      $is_pass_authenticated = ($mem_info_di != null && (strpos($mem_info_di, "MC0") === 0 || $is_sns_login));
      // 기업정보 인증 여부 확인
      $is_comp_authenticated = ($info != null && $info["mem_info"] != null && !empty($info["mem_info"]["mm_comp_num"]));
      ?>
      <style>
        /* ============================================
           채용공고 작성 페이지 CSS 변수 정의
           ============================================ */
        :root {
          --rc-input-height: 70px;
          --rc-title-font-size: 25px;
          --rc-input-font-size: 20px;
          --rc-label-width: 180px;
          --rc-label-font-size: 22px;
          --rc-border-radius: 10px;
          --rc-border-color: #ccc;
          --rc-color-primary: #31A182;
          --rc-color-primary-dark: #1B9D7D;
          --rc-color-primary-light: #57CCAF;
          --rc-color-danger: #F86048;
          --rc-section-padding: 20px;
          --rc-row-padding: 16px;
          --rc-tab-gray: #777777;
          --rc-label-align-self: center;
        }

        /* ============================================
           공통 레이아웃 클래스
           ============================================ */

        /* 섹션 기본 구조 */
        .rc-section {
          margin-bottom: 32px;
        }

        .rc-section-title {
          padding: 16px 20px;
          font-size: var(--rc-title-font-size);
          font-weight: 800;
          border-bottom: 1px solid var(--rc-border-color);
          margin: 0;
        }

        /* 섹션 내용 래퍼 */
        .rc-basic-info,
        .rc-work-condition,
        .rc-work-info,
        .rc-extra {
          padding: var(--rc-section-padding) 24px;
          padding-left: 44px;
        }

        /* form-row 공통 구조 */
        .rc-form-row {
          display: flex;
          margin: 0;
          padding: var(--rc-row-padding) 0;
          align-items: center;
          flex-wrap: wrap;
          border-bottom: 1px solid var(--rc-border-color);
        }

        .rc-form-row:first-child {
          padding-top: 0;
        }

        .rc-form-row:last-child {
          border-bottom: none;
          padding-bottom: 0;
        }

        /* 라벨 */
        .rc-label {
          width: var(--rc-label-width);
          margin: 0;
          padding: 0;
          font-size: var(--rc-label-font-size);
          line-height: 1;
          flex: 0 0 var(--rc-label-width);
          align-self: center;
        }

        /* 값 영역 */
        .rc-value {
          display: flex;
          flex: 1;
          flex-direction: column;
          justify-content: center;
        }

        .form-text {
          font-size: var(--rc-input-font-size);
        }

        .tab-panel.on .btn {
          transition: all 0.2s;
        }

        /* 근무정보 섹션 input 높이 통일 및 padding */
        .addition-info-form .form-row .form-text,
        .addition-info-form .form-row .value input[type="text"] {
          height: var(--rc-input-height);
          line-height: var(--rc-input-height);
          padding: 0 0 0 12px;
          font-size: 17px;
        }

        /* input placeholder 스타일 */
        .addition-info-form .form-row .form-text::placeholder,
        .addition-info-form .form-row .value input[type="text"]::placeholder {
          color: var(--rc-border-color);
        }

        /* input focus 시 색상 변화 제거 */
        .addition-info-form .form-row .form-text:focus,
        .addition-info-form .form-row .value input[type="text"]:focus {
          border-color: var(--rc-border-color) !important;
          box-shadow: none !important;
          -webkit-box-shadow: none !important;
        }

        #first-form input[type="text"] {
          border: 1px solid var(--rc-border-color);
          border-radius: var(--rc-border-radius);
        }

        .noti_duration .form-text {
          width: 580px;
        }

        .form-selectbox select {
          font-size: var(--rc-input-font-size);
        }

        .form-table .form-selectbox {
          width: 80px;
        }

        .form-row.starting-date .value input {
          width: 320px;
          padding-left: 0;
          text-align: left;
        }

        /* 모집일 input 아이콘 래퍼 */
        .recruit-date-wrapper {
          position: relative;
        }

        /* 모집일 input 아이콘 */
        .recruit-date-icon {
          position: absolute;
          right: 12px;
          top: 50%;
          transform: translateY(-50%);
          pointer-events: none;

        }

        .law_popup {
          width: 1050px;
          height: 550px;
          border: 1px solid var(--rc-tab-gray);
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
          position: fixed;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          background: white;
          display: none;
          z-index: 999;
          flex-direction: column;
          border-radius: var(--rc-border-radius);
        }

        .law_popup.on {
          display: flex;
        }

        .tab_menu {
          display: flex;
          height: 50px;
          align-items: center;
          font-weight: bold;
          font-size: 25px;
          color: var(--rc-tab-gray);
          flex-shrink: 0;
        }

        .tab_menu .tab1,
        .tab_menu .tab2 {
          padding: 8px 20px;
          cursor: pointer;
          border-bottom: 4px solid #777777;
        }

        .tab_menu .on {
          color: var(--rc-color-primary);
          border-bottom: 4px solid var(--rc-color-primary);
        }

        .tab_menu .close {
          margin-left: auto;
          margin-right: 0;
          margin-top: 0;
          font-size: 20px;
          cursor: pointer;
        }

        .tab_menu .close a {
          display: inline-block;
          padding: 5px 10px 4px;
          line-height: 1;
          color: #000 !important;
          font-size: 12px;
          transition: all .3s
        }

        .tab_menu .close a span {
          display: inline-block;
          background-repeat: no-repeat;
          background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMzMyIgY2xhc3M9ImJpIGJpLXgiIHZpZXdCb3g9IjAgMCAxNiAxNiI+CiAgPHBhdGggZD0iTTQuNjQ2IDQuNjQ2YS41LjUgMCAwIDEgLjcwOCAwTDggNy4yOTNsMi42NDYtMi42NDdhLjUuNSAwIDAgMSAuNzA4LjcwOEw4LjcwNyA4bDIuNjQ3IDIuNjQ2YS41LjUgMCAwIDEtLjcwOC43MDhM OCA4LjcwN2wtMi42NDYgMi42NDdhLjUuNSAwIDAgMS0uNzA4LS43MDhMNy4yOTMgOCA0LjY0NiA1LjM1NGEuNS41IDAgMCAxIDAtLjcwOCIvPgo8L3N2Zz4=");
          overflow: hidden;
          width: 30px;
          height: 30px;
          margin: -2px 0 0 3px;
          font-size: 0;
          text-indent: -3000em;
          vertical-align: middle;
          background-position: center;
          background-size: contain;
          transition: transform ease 0.2s;
        }

        .tab_menu .close a:hover span {
          -ms-transform: rotate(90deg);
          transform: rotate(90deg)
        }

        .tab_content {
          flex: 1;
          overflow-y: auto;
          box-sizing: border-box;
        }

        .tab_content>div {
          display: none;
        }

        .tab_content>.on {
          display: block;
        }

        .tab_desc {
          font-weight: normal;
          font-size: 13px;
          color: var(--rc-tab-gray);
          padding: 25px 10px;
        }

        .tab_desc em {
          color: var(--rc-color-danger);
        }

        .low {
          display: flex;
          background: #f4f4f4;
        }

        .low1,
        .low2 {
          width: 50%;
          padding: 30px;
          box-sizing: border-box;
        }

        .low1 {
          border-right: 1px solid #777777;
        }

        .tab_title {
          font-weight: bold;
          font-size: 15px;
          color: var(--rc-tab-gray);
          margin-bottom: 10px;
        }

        .low dt {
          font-weight: 500;
          font-size: 13px;
          color: var(--rc-tab-gray);
          margin-top: 10px;
        }

        .low dd {
          font-weight: normal;
          font-size: 13px;
          color: var(--rc-tab-gray);
          margin-left: 15px;
        }

        .low_link {
          height: 50px;
          flex-shrink: 0;
          display: flex;
          align-items: center;
          padding-left: 20px;
        }

        .low_link a {
          font-weight: 500;
          font-size: 13px;
          color: var(--rc-color-primary);
          text-decoration: none;
        }

        .viewLaw {
          display: inline-block;
          padding-right: 20px;
          background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMxQTE4MiIgdmlld0JveD0iMCAwIDE2IDE2Ij4KICA8cGF0aCBkPSJNOCAxNkE4IDggMCAxIDAgOCAwYTggOCAwIDAgMCAwIDE2bS45My05LjQxMi0xIDQuNzA1Yy0uMDcuMzQuMDI5LjUzMy4zMDQuNTMzLjE5NCAwIC40ODctLjA3LjY4Ni0uMjQ2bC0uMDg4LjQxNmMtLjI4Ny4zNDYtLjkyLjU5OC0xLjQ2NS41OTgtLjcwMyAwLTEuMDAyLS40MjItLjgwOC0xLjMxOWwuNzM4LTMuNDY4Yy4wNjQtLjI5My4wMDYtLjM5OS0uMjg3LS40NzFsLS40NTEtLjA4MS4wODItLjM4MSAyLjI5LS4yODd6TTggNS41YTEgMSAwIDEgMSAwLTIgMSAxIDAgMCAxIDAgMiIvPgo8L3N2Zz4=") no-repeat right center;
          background-size: 16px 16px;
          cursor: pointer;
          margin-left: 20px;
        }

        .addition-info-form .form-row {
          padding: 18px 0;
          border-bottom: 1px solid #eee;
        }

        .addition-info-form .form-row:last-child {
          border-bottom: none;
        }

        .addition-info-form .form-label {
          margin: 0px;
          display: block;
          align-self: center;
        }

        .addition-info-form .value {
          margin-top: 4px;
        }

        /* ====================
        근무조건 섹션 
        ======================
        */
        /* 근무조건 섹션 공통 row 스타일 */
        .work-section-row {
          display: flex;
          align-items: flex-start;
          gap: 20px;
          padding: 18px 0;
          border-bottom: 1px solid #eee;
        }

        .work-section-row .form-label {
          margin-bottom: 0;
          /* .form-table .form-label 스타일 상속 */
          width: 257px;
          flex: 0 0 255px;
          font-size: 20px;
        }

        .work-section-row .form-group,
        .work-section-row .value {
          flex: 1;
          margin-top: 0;
          display: flex;
          flex-wrap: wrap;
          gap: 8px;
          align-items: flex-start;
        }

        #work_type_memo {
          display: none;
          margin-left: 20px;
        }

        .time-label-sub {
          text-align: right;
          padding-right: 50px;
          font-weight: normal;
          font-size: var(--rc-input-font-size);
        }

        .time-value {
          position: absolute;
          top: 26px;
          left: 258px;
          width: 450px;
        }

        .time-col {
          width: 200px;
        }

        .time-select {
          float: left;
          width: 60px;
          height: 30px;
          text-align: center;
          color: #000;
          border: 1px solid var(--rc-border-color);
          border-radius: var(--rc-border-radius);
          position: relative;
          opacity: initial;
          appearance: auto;
        }

        .time-unit {
          float: left;
          height: 30px;
          line-height: 30px;
          padding: 0 7px;
        }

        .time-input-m {
          float: left;
          width: 60px;
          height: 30px;
          text-align: center;
          background-color: #fff;
          border: 1px solid var(--rc-border-color);
          border-radius: var(--rc-border-radius);
        }

        .time-separator {
          display: inline-block;
          margin-right: 30px;
        }

        .liner {
          display: none;
        }

        /* 근무시간 UI 변경 스타일 */
        .work-time-row {
          display: flex;
          align-items: center;
          gap: 20px;
          padding: 18px 0;
          border-bottom: 1px solid #eee;
        }

        .work-time-row .form-label {
          margin-bottom: 0;
          flex: 0 0 auto;
        }

        .work-time-value {
          position: static !important;
          width: auto !important;
          left: auto !important;
          top: auto !important;
          flex: 1;
        }

        .work-time-group {
          display: flex;
          align-items: center;
          gap: 8px;
          flex-wrap: wrap;
        }

        /* 출근/퇴근 라벨 버튼 스타일 */
        .work-time-label {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          padding: 0 16px;
          height: var(--rc-input-height);
          background: var(--rc-color-primary-light);
          color: #fff;
          border-radius: var(--rc-border-radius);
          font-size: 15px;
          white-space: nowrap;
          font-weight: 500;
          min-width: 80px;
        }

        /* 시간 입력 래퍼 (select + : + input) */
        .work-time-input-wrap {
          display: inline-flex;
          align-items: center;
          gap: 4px;
          height: var(--rc-input-height);
          border: 1px solid var(--rc-border-color);
          border-radius: var(--rc-border-radius);
          padding: 0 10px;
          background: #fff;
          min-width: 198px;
        }

        .work-time-input-wrap .time-select {
          border: none;
          border-radius: 0;
          height: 40px;
          width: 80px;
          float: none;
          padding: 0 8px;
          appearance: auto;
          flex: 1;
        }

        .work-time-input-wrap .time-input-m {
          border: none;
          border-radius: 0;
          height: 40px;
          width: 70px;
          float: none;
          padding: 0 8px;
          flex: 1;
        }

        .work-time-input-wrap>span {
          height: 40px;
          line-height: 40px;
          color: #333;
          font-weight: 500;
        }

        /* 주간/야간 태그 */
        .work-time-tag {
          display: inline-flex;
          align-items: center;
          gap: 6px;
          font-size: var(--rc-input-font-size);
          color: #4a90e2;
          font-weight: 500;
          margin-left: 22px;
        }

        .work-time-tag::before {
          content: "";
          display: inline-block;
          width: 10px;
          height: 10px;
          background: #4a90e2;
          border-radius: 50%;
        }

        /* 근무조건 섹션 - 근무형태 value 너비 제한 */
        .work-section .value>.form-group {
          width: 100%;
        }

        /* 근무조건 섹션 - 근무형태 토글 버튼 크기 */
        .work-section .work-type-group .form-toggle-btn2 .btn {
          min-width: 184px;
          height: var(--rc-input-height);
          padding: 0 15px;
          font-size: var(--rc-input-font-size);
          line-height: 66px;
          color: #000;
          border-color: var(--rc-border-color);
        }

        .work-section .work-type-group .form-toggle-btn2 input:checked~.btn {
          color: #fff;
          border-color: transparent;
          background-color: #57CCAF;
        }


        /* 근무조건 섹션 - 근무형태 체크박스 */
        .work-section .work-type-group .form-checkbox .label {
          font-size: var(--rc-input-font-size);
          line-height: 1.5;
        }

        .work-section .work-type-group .form-checkbox .label:before {
          width: 1.5em;
          height: 1.5em;
        }

        /* ================================================
         근무조건 섹션 UI 개선 
         =================================================
         */
        .work-section .form-toggle-btn2 .btn {
          height: var(--rc-input-height);
          line-height: 42px;
          font-size: var(--rc-input-font-size);
          box-sizing: border-box;
          border-radius: var(--rc-border-radius);
          border-color: var(--rc-border-color);
        }

        .work-section .form-group {
          display: flex;
          flex-wrap: wrap;
          gap: 8px;
        }

        .work-section .time-select {
          height: 41px !important;
          line-height: var(--rc-input-height);
          font-size: var(--rc-input-font-size);
          border-radius: var(--rc-border-radius);
        }

        .work-section .time-input-m {
          height: 41px !important;
          line-height: var(--rc-input-height);
          font-size: var(--rc-input-font-size);
          border-radius: var(--rc-border-radius);
          padding: 0 0 0 8px;
        }

        .work-section .time-unit {
          height: var(--rc-input-height);
          line-height: var(--rc-input-height);
        }

        .work-section .time-value {
          top: 12px !important;
        }

        .work-section .chip {
          height: var(--rc-input-height);
          border-radius: var(--rc-border-radius);
          padding: 0 12px;
          font-size: var(--rc-input-font-size);
          line-height: var(--rc-input-height);
          align-items: center;
        }

        .work-section .amountField {
          height: var(--rc-input-height);
        }

        .work-section .amountField .amount {
          height: var(--rc-input-height);
          border-radius: var(--rc-border-radius);
        }

        .work-section .amountField .amount input[type="text"] {
          height: var(--rc-input-height);
          line-height: var(--rc-input-height);
          padding: 0 0 0 12px;
          font-size: var(--rc-input-font-size);
        }

        .work-section .secondary-title {
          font-size: 15px;
          color: #777;
          margin: 14px 0 8px;
          font-weight: 500;
        }

        /* 근무형태 형태별 레이아웃 */
        .work-section .form-row:first-of-type {
          display: flex;
          align-items: center;
          gap: 20px;
        }

        .work-section .form-row:first-of-type .form-label {
          margin-bottom: 0;
          flex-shrink: 0;
        }

        .work-section .form-row:first-of-type .form-group {
          flex: 1;
          margin-bottom: 0;
        }

        .work-section .form-row:first-of-type #work_type_memo {
          display: flex;
          align-items: center;
          gap: 4px;
          flex-shrink: 0;
          margin-left: 0;
          padding: 8px 12px;
          background: #f0f0f0;
          border-radius: 20px;
          font-size: 14px;
          color: #777;
        }

        .work-section .form-row:first-of-type #work_type_memo::before {
          content: "✓";
          color: #57CCAF;
          font-weight: bold;
          margin-right: 4px;
        }

        /* 근무형태 무관 - extra-agree-wrap으로 통일 (아래 참고) */

        /* 급여형태 select 스타일 */
        .salary-select-wrap {
          display: inline-block;
          flex: 0 0 auto;
        }

        .salary-select {
          height: var(--rc-input-height);
          line-height: var(--rc-input-height);
          padding: 0 12px;
          font-size: var(--rc-input-font-size);
          border-radius: var(--rc-border-radius);
          border: 1px solid var(--rc-border-color);
          background-color: #fff;
          cursor: pointer;
          min-width: 191px;
          appearance: none;
          -webkit-appearance: none;
          -moz-appearance: none;
          background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
          background-repeat: no-repeat;
          background-position: right 8px center;
          background-size: 20px;
          padding-right: 32px;
          box-sizing: border-box;
        }

        /* 금액 입력 */
        .salary-amount-wrap {
          display: flex;
          align-items: center;
          gap: 0;
          height: var(--rc-input-height);
          flex: 0 0 auto;
        }

        .salary-amount-input {
          height: var(--rc-input-height);
          line-height: var(--rc-input-height);
          padding: 0 12px;
          font-size: var(--rc-input-font-size);
          border: 1px solid var(--rc-border-color);
          border-radius: 10px 0 0 10px;
          box-sizing: border-box;
          width: 337px;
        }

        .salary-unit {
          display: flex;
          align-items: center;
          height: var(--rc-input-height);
          padding: 0 12px;
          font-size: var(--rc-input-font-size);
          color: #777;
          white-space: nowrap;
          background-color: #f5f5f5;
          border: 1px solid var(--rc-border-color);
          border-left: none;
          border-radius: 0 10px 10px 0;
          box-sizing: border-box;
          margin: 0;
        }

        /* 추가 옵션 (시급가능, 일급가능 등) */
        .salary-extra-wrap {
          display: flex;
          gap: 12px;
          flex-wrap: wrap;
          width: 100%;
          margin-top: 8px;
        }

        .salary-extra-wrap .chip2 {
          font-size: var(--rc-input-font-size);
        }

        /* =================================
          근무정보 섹션 
          ================================
        */
        .input-wide {
          width: 580px;
        }

        .recruit-list-arrow {
          position: absolute;
          top: 33px;
          left: 808px;
          padding: 11px 5px 0 5px;
          border-radius: 6px;
        }

        #recruiteListBox {
          display: none;
          width: 580px;
          max-height: 120px;
          border: 1px solid #ccc;
          overflow-y: auto;
          overflow-x: hidden;
          background-color: #fff;
          border-radius: 8px;
          box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2), -5px 5px 10px rgba(0, 0, 0, 0.2), 0px 10px 10px rgba(0, 0, 0, 0.2);
        }

        .recruit-list-ul {
          width: 580px;
        }

        .recruit-list-item {
          list-style: none;
          width: 100%;
          height: 30px;
          line-height: 30px;
          padding-left: 10px;
        }

        .rc-desc-hidden {
          display: none;
        }

        /* 근무정보 섹션 - 공고명 글자수 카운터 */
        .rc-name-row {
          display: flex;
          align-items: flex-start;
          gap: 12px;
        }

        .char-count {
          font-size: 15px;
          color: var(--rc-border-color);
          white-space: nowrap;
          height: var(--rc-input-height);
          line-height: var(--rc-input-height);
          display: flex;
          align-items: center;
        }

        /* 근무정보 섹션 - 모집일 너비 통일 */
        .addition-info-form .noti_duration .form-text,
        .addition-info-form .recruit-range .flatpickr-input[type=text],
        .addition-info-form .recruit-range input.flatpickr-input {
          width: 580px;
          height: var(--rc-input-height);
          line-height: var(--rc-input-height);
        }

        .addition-info-form .recruit-range {
          width: 580px;
          flex: 0 0 580px;
        }

        /* 근무정보 섹션 - 업직종 5열 grid */
        .work-type-grid {
          display: grid;
          grid-template-columns: repeat(5, 1fr);
          gap: 8px;
          width: 580px;
        }

        .work-type-grid .form-toggle-btn2 {
          margin: 0;
          width: 100%;
        }

        .work-type-grid .form-toggle-btn2 .btn {
          min-width: unset;
          width: 100%;
          height: var(--rc-input-height);
          line-height: 66px;
          font-size: var(--rc-input-font-size);
          padding: 0;
          box-sizing: border-box;
          border-radius: var(--rc-border-radius);
          color: #000;
          border-color: var(--rc-border-color);
        }

        /* 근무정보 섹션 - 근무지 검색 아이콘 */
        .addr-search-wrap {
          position: relative;
          display: block;
          width: 580px;
        }

        .addr-search-wrap input {
          width: 100%;
          padding-right: 40px;
        }

        .addr-search-icon {
          position: absolute;
          right: 12px;
          top: 50%;
          transform: translateY(-50%);
          font-size: var(--rc-input-font-size);
          color: #222;
          pointer-events: none;
        }

        /* 웹 버전 주소 삭제 버튼 */
        .addr-clear-btn-web {
          position: absolute;
          right: 50px;
          top: 50%;
          transform: translateY(-50%);
          background: none;
          border: none;
          color: #999;
          font-size: 20px;
          cursor: pointer;
          padding: 8px;
          transition: color 0.2s ease;
          z-index: 2;
        }

        .addr-clear-btn-web:hover {
          color: #d32f2f;
        }

        .fixed {
          color: var(--rc-color-danger);
        }

        .work-section .form-toggle-btn2 .btn.benefit-label {
          height: 96px;
          width: 125px;
        }

        /* ===============================================
          업무정보 섹션 
        ================================================
        */
        .desc-value {
          min-height: 398px;
          height: auto;
        }

        .desc-textarea {
          height: 600px;
          min-height: 600px;
        }

        .hash-hidden {
          display: none;
        }

        #hashbox {
          display: block;
          width: 100%;
          float: left;
          margin-top: 15px;
          border: 1px solid var(--rc-border-color);
          min-height: 100px;
          background-color: #fff;
          padding: 10px;
          line-height: 38px;
        }

        .hash-tag-span {
          margin-right: 10px;
          border: none;
          height: 28px;
          display: inline-block;
          line-height: 26px;
          border-radius: 25px;
          padding: 0 10px;
          color: #aaa;
        }

        .hash-tag-input {
          min-width: 90px;
          max-width: 150px;
          height: 24px;
          border: none;
          margin-left: 5px;
        }

        .hashdel {
          margin-left: 5px;
          cursor: pointer;
          display: none;
        }

        .hash-help {
          display: block;
          margin-top: 10px;
        }

        /* 해시태그 프리셋 버튼 영역 */
        .hash-preset-wrap {
          margin-bottom: 10px;
        }

        .hash-preset-group {
          display: grid;
          grid-template-columns: repeat(5, 1fr);
          gap: 8px;
        }

        /* 프리셋 태그 버튼 */
        .hash-preset-btn {
          display: block;
          cursor: pointer;
        }

        .hash-preset-btn input[type="checkbox"] {
          display: none;
        }

        .hash-preset-btn .btn {
          display: flex;
          align-items: center;
          justify-content: center;
          height: var(--rc-input-height);
          width: 100%;
          border: 1px solid var(--rc-border-color);
          border-radius: var(--rc-border-radius);
          background-color: #fff;
          color: #000;
          font-size: var(--rc-input-font-size);
          cursor: pointer;
          transition: all 0.2s;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
        }

        /* 선택된 태그 강조 */
        .hash-preset-btn input[type="checkbox"]:checked+.btn {
          background-color: var(--rc-color-primary-light);
          color: #fff;
          border-color: var(--rc-color-primary-light);
        }

        /* + 버튼 */
        .hash-add-btn .btn {
          font-weight: bold;
          font-size: 18px;
        }

        /* 직접입력 행 */
        .hash-direct-wrap {
          display: flex;
          gap: 8px;
          align-items: center;
          margin-top: 8px;
        }

        .hash-direct-input {
          height: var(--rc-input-height);
          line-height: var(--rc-input-height);
          padding: 0 12px;
          font-size: var(--rc-input-font-size);
          border: 1px solid var(--rc-border-color);
          border-radius: var(--rc-border-radius);
          width: 200px;
          box-sizing: border-box;
        }

        .hash-direct-input:focus {
          border-color: var(--rc-border-color) !important;
          box-shadow: none !important;
          outline: none;
        }

        .hash-confirm-btn {
          height: var(--rc-input-height);
          line-height: var(--rc-input-height);
          padding: 0 24px;
          font-size: var(--rc-input-font-size);
          background-color: #1B9D7D;
          color: #fff;
          border: none;
          border-radius: var(--rc-border-radius);
          cursor: pointer;
        }

        /* 추가정보 섹션 */
        /* 추가정보 - 무관 체크박스 인라인 */
        .extra-agree-wrap {
          display: inline-flex;
          align-items: center;
          margin-left: 16px;
          gap: 4px;
        }

        .extra-agree-wrap .form-checkbox {
          display: inline-flex;
          align-items: center;
          gap: 4px;
          cursor: pointer;
        }

        .extra-agree-wrap .form-checkbox input[type="checkbox"] {
          width: 18px;
          height: 18px;
          accent-color: var(--rc-color-primary-light);
          cursor: pointer;
        }

        .extra-agree-wrap .form-checkbox .label {
          font-size: var(--rc-input-font-size);
          color: #555;
        }

        /* 추가정보 섹션 - form-toggle-btn2 버튼 크기 통일 */
        .extra-section .form-toggle-btn2 .btn {
          height: 50px;
          min-width: 80px;
          line-height: 48px;
          font-size: var(--rc-input-font-size);
          border-radius: 8px;
          box-sizing: border-box;
          padding: 0 12px;
        }

        /* 추가정보 섹션 - form-group 배치 */
        .extra-section .form-row .form-group,
        .extra-section .form-row .inline-group {
          display: flex;
          align-items: center;
          flex-wrap: wrap;
          gap: 8px;
        }

        /* 희망나이 form-group flex 레이아웃 */
        .extra-section .age-group {
          display: flex;
          align-items: center;
          gap: 8px;
          flex-wrap: wrap;
        }

        /* 희망나이 selectbox 크기 제어 - main.css override */
        .extra-section .form-table .form-selectbox {
          width: auto;
          min-width: 140px;
          -webkit-box-flex: 0;
          -ms-flex: 0 0 auto;
          flex: 0 0 236px;
        }

        .extra-section .age-group .form-selectbox {
          height: var(--rc-input-height);
        }

        /* 희망나이 구분자(~) */
        .extra-section .age-group .entity {
          flex: 0 0 auto;
          margin: 0;
          padding: 0 4px;
          font-size: var(--rc-input-font-size);
          font-weight: 500;
          height: 50px;
          display: flex;
          align-items: center;
        }

        /* 희망나이 체크박스 래퍼 */
        .extra-section .age-group .extra-agree-wrap {
          flex: 0 0 auto;
          display: flex;
          align-items: center;
          height: 50px;
        }

        /* 희망나이 법률 안내 래퍼 */
        .extra-section .age-group .law-link-wrap {
          flex: 0 0 auto;
          display: flex;
          align-items: center;
          height: 50px;

        }

        /* 희망나이 select - 토글 버튼과 높이 통일 */
        .extra-section .age-select {
          height: var(--rc-input-height);
          line-height: 48px;
          font-size: var(--rc-input-font-size);
          border-radius: 8px;
          border: 1px solid var(--rc-border-color);
          padding: 0 12px;
          opacity: initial;
          box-sizing: border-box;
          vertical-align: middle;
          text-align: center;
        }

        /* 첨부사진 - 모바일 스타일 적용 */
        .extra-section .form-pic-group {
          display: flex;
          gap: 12px;
          margin: 0;
          width: 100%;
        }

        .extra-section .form-pic-group .form-pic {
          flex: 1;
          position: relative;
          cursor: pointer;

          background-color: #f5f5f5;
          border-radius: var(--rc-border-radius);
          overflow: hidden;
          aspect-ratio: 1;
          float: none;
          width: auto;
          margin: 0;
        }

        .extra-section .form-pic-group .form-pic:before {
          content: "";
          display: block;
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          width: 48px;
          height: 48px;
          border-radius: 50%;
          background-image: url("/resource/shop/web/img/ic_camera.svg");
          background-repeat: no-repeat;
          background-position: center;
          background-size: 100% 100%;
          z-index: 1;
          pointer-events: none;
          margin: 0;
        }

        .extra-section .form-pic-group .form-pic.has-image:before {
          display: none;
        }

        .extra-section .form-pic-group .form-pic .label {
          display: none;
        }

        .extra-section .form-pic-group .form-pic .preview {
          display: block;
          width: 100%;
          padding-top: 100%;
          position: relative;
          overflow: hidden;
          background-image: none;
        }

        .extra-section .form-pic-group .form-pic .preview img {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          object-fit: cover;
          z-index: 2;
        }

        .law-link-wrap {
          margin-top: 7px;
        }



        .age-group {
          display: -webkit-box;
          display: flex;
          align-items: center;
        }

        .age-select {
          width: 80px;
          height: 30px;
          padding-left: 12px;
        }

        .pic-label {
          margin-top: 20px;
        }

        .pic-help-notice {
          display: block;
          color: var(--rc-color-primary-dark);
        }

        /* 섹션 레이아웃 */
        .form-section {
          margin-bottom: 50px;
          padding-bottom: 40px;

        }

        .form-section:last-child {
          border-bottom: none;
          margin-bottom: 0;
        }

        .form-section-title {
          font-size: var(--rc-title-font-size);
          font-weight: 700;
          color: #222;

          padding-bottom: 15px;
          border-bottom: 3px solid #84908d;
          letter-spacing: -0.5px;
        }

        /* 섹션 내 항목들 들여쓰기 */
        .form-section {
          padding-left: 30px;
        }

        .form-section .form-section-title {
          margin-left: -30px;
          padding-left: 0;
        }


        /* 복리후생 섹션 */
        .services .value {
          display: flex;
          flex-direction: column;
          gap: 16px;
        }

        .services .value .secondary-title {
          margin: 0;
        }

        /* 복리후생 섹션 내부 form-group */
        .services .form-group {
          display: flex;
          flex-wrap: wrap;
          gap: 8px;
          margin: 0;
        }

        .extra-section .form-row .form-group,
        .extra-section .form-row .inline-group .nation-group {
          gap: 0px;
        }

        .nation-group .form-toggle-btn2,
        .sex-group .form-toggle-btn2 {
          margin: 0;
        }

        .extra-section .form-toggle-btn2 .no-radius {
          border-radius: 0;
        }

        /* 희망국적, 희망성별 버튼 radius */
        .nation-group .form-toggle-btn2:nth-child(1) .btn,
        .sex-group .form-toggle-btn2:nth-child(1) .btn {
          border-radius: 10px 0 0 10px;
        }

        .nation-group .form-toggle-btn2:nth-child(2) .btn,
        .sex-group .form-toggle-btn2:nth-child(2) .btn {
          border-radius: 0 10px 10px 0;
          margin-right: 0;
        }




        /* 추가정보 섹션 자식 요소들의 넓이 제한 */
        .extra-section .value>.form-group,
        .extra-section .value>.form-pic-group {
          width: 80%;
        }

        /* 추가정보 섹션 내 토글 버튼 크기 확대 */
        .extra-section .form-group .form-toggle-btn2 .btn {
          min-width: 245px;
          height: var(--rc-input-height);
          padding: 0 15px;
          font-size: var(--rc-input-font-size);
          line-height: 60px;
          border: 1px solid var(--rc-border-color);



        }

        /* 추가정보 섹션 근무요일 토글 버튼 스타일 */
        .extra-section .week-day .form-toggle-btn2.sm .btn {
          min-width: 60px;
          height: 60px;
          padding: 0 8px;
          font-size: var(--rc-input-font-size);
          line-height: 58px;
          border-radius: 50%;

        }

        .extra-section .form-toggle-btn2 input:checked~.btn {
          color: #fff;
          border-color: transparent;
          background-color: #57CCAF;
        }

        .form-toggle-btn2 .btn {
          color: #000;
        }

        /* 추가정보 섹션 내 체크박스 크기 확대 */
        .extra-section .form-group .form-checkbox .label {
          font-size: var(--rc-input-font-size);
          line-height: 1.5;
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
          border-radius: var(--rc-border-radius);
          color: var(--rc-color-primary-dark);
          font-size: 20px;
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

        .extra-section.show {
          display: block;
        }

        .addition-info-form .services .form-toggle-btn2 {
          margin: 0 4px 11px 0;
        }

        .form-action-wrap .btn-round {
          border-radius: var(--rc-border-radius);
        }

        .fa-calendar-check:before {
          font-size: 22px;
        }

        /*============================================
                    스타일 추가
        ============================================*/
      </style>
      <div class="tab-panel on">
        <div class="manage-my-content">
          <form id="frm_recruit" onsubmit="return false" autocomplete="false">
            <input type="hidden" id="rc_idx" name="rc_idx" value="<?= ($_GET["gbn"] == "re" ? "0" : $info["rc_idx"]) ?>" />
            <input type="hidden" id="ad_zipcode" name="ad_zipcode" value="<?= $info["addr"]["ad_zipcode"] ?>" />
            <input type="hidden" id="ad_jibun" name="ad_jibun" value="<?= $info["addr"]["ad_jibun"] ?>" />
            <input type="hidden" id="ad_doro" name="ad_doro" value="<?= $info["addr"]["ad_doro"] ?>" />
            <input type="hidden" id="ad_detail" name="ad_detail" value="<?= $info["addr"]["ad_detail"] ?>" />
            <input type="hidden" id="ad_addr" name="ad_addr" value="<?= $info["addr"]["ad_addr"] ?>" />
            <input type="hidden" id="ad_gps_x" name="ad_gps_x" value="<?= $info["addr"]["ad_gps_x"] ?>" />
            <input type="hidden" id="ad_gps_y" name="ad_gps_y" value="<?= $info["addr"]["ad_gps_y"] ?>" />
            <input type="hidden" id="mm_comp_num" name="mm_comp_num" value="<?= $info["mm_comp_num"] ?>" />
            <input type="hidden" id="mm_comp_num_org" name="mm_comp_num_org" value="<?= $info["mm_comp_num"] ?>" />

            <div class="addition-info-form">
              <!-- 근무정보 섹션 -->
              <div class="form-section">
                <p class="form-section-title">근무정보<span class="fixed">*</span></p>
                <div class="form-table" id="first-form">
                  <input type="hidden" name="rc_show_yn" value="Y">

                  <div class="form-row">
                    <p class="form-label">공고명</p>
                    <div class="value">
                      <div class="rc-name-row">
                        <label>
                          <span class="sr-text">공고명 입력</span>
                          <input type="text" name="rc_name" maxlength="50" placeholder="공고명을 작성해주세요(50글자까지)" value="<?= $info["rc_name"] ?>" autocomplete="off" aria-required="true" required class="form-text input-wide">
                          <!-- <div onclick="$('#recruiteListBox').toggle();" class="recruit-list-arrow"><img src="/resource/shop/web/img/ic_arrow_down.svg"></div> -->
                          <div id="recruiteListBox">
                            <script>
                              var recruiteJson = {};

                              function fSet(idx) {
                                $('input[name="rc_name"]').val(recruiteJson[idx]['rc_name']);
                                var rc_work = recruiteJson[idx]['rc_work'];
                                var work_checkboxes = document.querySelectorAll('input[type="checkbox"][name="rc_work[]"]');
                                var rc_workValues = rc_work.split(',');
                                for (var i = 0; i < work_checkboxes.length; i++) {
                                  if (rc_workValues.includes(work_checkboxes[i].value)) {
                                    work_checkboxes[i].checked = true;
                                  } else {
                                    work_checkboxes[i].checked = false;
                                  }
                                }

                                var rc_work_type = recruiteJson[idx]['rc_work_type'];
                                var work_type_checkboxes = document.querySelectorAll('input[type="checkbox"][name="rc_work_type[]"]');
                                var rc_work_typeValues = rc_work_type.split(',');
                                for (var i = 0; i < work_type_checkboxes.length; i++) {
                                  if (rc_work_typeValues.includes(work_type_checkboxes[i].value)) {
                                    work_type_checkboxes[i].checked = true;
                                  } else {
                                    work_type_checkboxes[i].checked = false;
                                  }
                                }
                                /*
                                var rc_price_type = recruiteJson[idx]['rc_price_type'];
                                var price_type_checkboxes = document.querySelectorAll('input[type="checkbox"][name="rc_price_type[]"]');
                                var rc_price_typeValues = rc_price_type.split(',');
                                for (var i = 0; i < price_type_checkboxes.length; i++) {
                                  if (rc_price_typeValues.includes(price_type_checkboxes[i].value)) {
                                    price_type_checkboxes[i].checked = true;
                                  }else{
                                    price_type_checkboxes[i].checked = false;
                                  }
                                }
                                */
                                $('#rc_desc').text($('#rc_desc_' + idx).text());
                                $('#recruiteListBox').hide();

                              }
                            </script>
                            <ul class="recruit-list-ul">
                              <?php $recruite_idx = 0; ?>
                              <?php foreach ($rc_last_recruite_list as $row) : ?>
                                <script>
                                  recruiteJson[<?= $recruite_idx ?>] = {
                                    idx: <?= $recruite_idx ?>,
                                    rc_name: '<?= $row["rc_name"] ?>',
                                    rc_work: '<?= $row["rc_work"] ?>',
                                    rc_work_type: '<?= $row["rc_work_type"] ?>',
                                    rc_price_type: '<?= $row["rc_price_type"] ?>'
                                  };
                                </script>
                                <li class="recruit-list-item" onclick="fSet('<?= $recruite_idx ?>');"><?= $row["rc_name"] ?></li>
                                <?php $recruite_idx++; ?>
                              <?php endforeach; ?>
                            </ul>
                            <?php $recruite_idx = 0; ?>
                            <?php foreach ($rc_last_recruite_list as $row) : ?>
                              <textarea id="rc_desc_<?= $recruite_idx ?>" class="rc-desc-hidden"><?= $row["rc_desc"] ?></textarea>
                              <?php $recruite_idx++; ?>
                            <?php endforeach; ?>
                          </div>
                        </label>
                        <span class="char-count" id="rc_name_count">0/50</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-row">
                    <p class="form-label">모집일</p>
                    <div class="value">
                      <div class="inline-group">
                        <div class="recruit-range noti_duration">
                          <label>
                            <span class="sr-text">모집일 입력</span>
                            <div class="recruit-date-wrapper">
                              <input type="text" name="rc_date" aria-required="true" required class="form-text js-datepicker2" value="<?php echo (isset($info['rc_sdate']) && $info['rc_sdate'] != '' && isset($info['rc_edate']) && $info['rc_edate'] != '') ? '[모집시작일] ' . $info['rc_sdate'] . ' ~ [모집종료일] ' . $info['rc_edate'] : ''; ?>">
                              <i class="fa-regular fa-calendar-check recruit-date-icon"></i>
                            </div>
                          </label>
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="form-row">
                    <p class="form-label">업직종</p>
                    <div class="form-group work-type-grid">
                      <?php
                      // 업직종 출력 순서 직접 제어 (이미지 기준 5열×2행)
                      $ordered_work_list = ["물류", "생산", "건설", "서비스", "운전배달", "식당", "상담", "사무직", "기타"];
                      foreach ($ordered_work_list as $work_name) :
                        $found_row = null;
                        foreach ($rc_wrok_list as $row) {
                          if ($row["cd_val"] === $work_name) {
                            $found_row = $row;
                            break;
                          }
                        }
                        $img_val = isset($found_row["cd_val2"]) ? $found_row["cd_val2"] : "";
                      ?>
                        <label class="form-toggle-btn2">
                          <input type="checkbox" name="rc_work[]" data-select-limit="2"
                            value="<?= $work_name ?>" data-img="<?= $img_val ?>"
                            <?= in_array_return($work_name, $info["rc_work"], "check") ?>>
                          <span class="btn"><?= $work_name ?></span>
                        </label>
                      <?php endforeach; ?>
                    </div>
                  </div>
                  <div class="form-row address">
                    <p class="form-label">근무지</p>
                    <div class="value">
                      <!-- 현재 위치 -->
                      <label class="row">
                        <input type="text" hidden name="changeArea" value="<?= $info["addr"]["ad_addr"] ?>" readonly aria-required="true" required class="form-text js-find-loc">
                      </label>
                      <!-- 시도 -->
                      <div class="addr-search-wrap row">
                        <input type="text" name="rc_addr_doro" value="<?= $info["addr"]["ad_doro"] ?>" placeholder="근무지를 입력해주세요" autocomplete="off" aria-required="true" required class="form-text input-wide">
                        <i class="fas fa-search addr-search-icon"></i>
                      </div>
                    </div>
                  </div>

                  <!-- 공고노출지역 -->
                  <div class="form-row">
                    <p class="form-label">공고노출지역</p>
                    <div class="value">
                      <!-- 노출지역 1 -->
                      <div class="addr-search-wrap row" style="margin-bottom:8px; position: relative;">
                        <input type="text" id="expose_addr1_doro"
                               value="<?= isset($info['expose_addr1']) && $info['expose_addr1'] != null ? $info['expose_addr1']['ad_doro'] : '' ?>"
                               placeholder="채용공고 추가 노출지역을 선택해주세요"
                               autocomplete="off" class="form-text input-wide" readonly>
                        <i class="fas fa-search addr-search-icon"></i>
                        <button type="button" class="addr-clear-btn-web js-clear-expose-addr-web" data-index="1" aria-label="노출지역 1 삭제" style="display: <?= (isset($info['expose_addr1']) && $info['expose_addr1'] != null && !empty($info['expose_addr1']['ad_doro'])) ? 'inline-block' : 'none' ?>;">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                      <!-- 노출지역 2 -->
                      <div class="addr-search-wrap row" style="position: relative;">
                        <input type="text" id="expose_addr2_doro"
                               value="<?= isset($info['expose_addr2']) && $info['expose_addr2'] != null ? $info['expose_addr2']['ad_doro'] : '' ?>"
                               placeholder="채용공고 추가 노출지역을 선택해주세요"
                               autocomplete="off" class="form-text input-wide" readonly>
                        <i class="fas fa-search addr-search-icon"></i>
                        <button type="button" class="addr-clear-btn-web js-clear-expose-addr-web" data-index="2" aria-label="노출지역 2 삭제" style="display: <?= (isset($info['expose_addr2']) && $info['expose_addr2'] != null && !empty($info['expose_addr2']['ad_doro'])) ? 'inline-block' : 'none' ?>;">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                      <!-- hidden 필드 (노출지역 1) -->
                      <input type="hidden" name="expose_addr1_zipcode"  value="<?= isset($info['expose_addr1']) && $info['expose_addr1'] != null ? $info['expose_addr1']['ad_zipcode'] : '' ?>">
                      <input type="hidden" name="expose_addr1_jibun"    value="<?= isset($info['expose_addr1']) && $info['expose_addr1'] != null ? $info['expose_addr1']['ad_jibun'] : '' ?>">
                      <input type="hidden" name="expose_addr1_doro_val" value="<?= isset($info['expose_addr1']) && $info['expose_addr1'] != null ? $info['expose_addr1']['ad_doro'] : '' ?>">
                      <input type="hidden" id="expose_addr1_addr"       name="expose_addr1_addr"    value="<?= isset($info['expose_addr1']) && $info['expose_addr1'] != null ? $info['expose_addr1']['ad_addr'] : '' ?>">
                      <input type="hidden" id="expose_addr1_gps_x"      name="expose_addr1_gps_x"   value="<?= isset($info['expose_addr1']) && $info['expose_addr1'] != null ? $info['expose_addr1']['ad_gps_x'] : '' ?>">
                      <input type="hidden" id="expose_addr1_gps_y"      name="expose_addr1_gps_y"   value="<?= isset($info['expose_addr1']) && $info['expose_addr1'] != null ? $info['expose_addr1']['ad_gps_y'] : '' ?>">
                      <!-- hidden 필드 (노출지역 2) -->
                      <input type="hidden" name="expose_addr2_zipcode"  value="<?= isset($info['expose_addr2']) && $info['expose_addr2'] != null ? $info['expose_addr2']['ad_zipcode'] : '' ?>">
                      <input type="hidden" name="expose_addr2_jibun"    value="<?= isset($info['expose_addr2']) && $info['expose_addr2'] != null ? $info['expose_addr2']['ad_jibun'] : '' ?>">
                      <input type="hidden" name="expose_addr2_doro_val" value="<?= isset($info['expose_addr2']) && $info['expose_addr2'] != null ? $info['expose_addr2']['ad_doro'] : '' ?>">
                      <input type="hidden" id="expose_addr2_addr"       name="expose_addr2_addr"    value="<?= isset($info['expose_addr2']) && $info['expose_addr2'] != null ? $info['expose_addr2']['ad_addr'] : '' ?>">
                      <input type="hidden" id="expose_addr2_gps_x"      name="expose_addr2_gps_x"   value="<?= isset($info['expose_addr2']) && $info['expose_addr2'] != null ? $info['expose_addr2']['ad_gps_x'] : '' ?>">
                      <input type="hidden" id="expose_addr2_gps_y"      name="expose_addr2_gps_y"   value="<?= isset($info['expose_addr2']) && $info['expose_addr2'] != null ? $info['expose_addr2']['ad_gps_y'] : '' ?>">
                    </div>
                  </div>
                </div><!-- //form-table 근무정보 -->
              </div><!-- //근무정보 섹션 -->

              <!-- 근무조건 섹션 -->
              <div class="form-section work-section">
                <p class="form-section-title">근무조건<span class="fixed">*</span></p>
                <div class="form-table">
                  <div class="form-row">
                    <p class="form-label">근무형태</p>
                    <div class="value">
                      <div class="form-group work-type-group">
                        <?php foreach ($rc_work_type_list as $key => $row) : ?>
                          <label class="form-toggle-btn2">
                            <input type="checkbox" id="rc_work_type_<?= $key ?>" name="rc_work_type[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info["rc_work_type"], "check") ?>>
                            <span class="btn"><?= $row["cd_val"] ?></span>
                          </label>
                        <?php endforeach; ?>
                        <div class="extra-agree-wrap">
                          <label class="form-checkbox">
                            <input type="checkbox" id="work_type_agree" value="무관">
                            <span class="label">무관</span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <script>
                    $(function() {
                      const $workTypeCheckboxes = $('input[name="rc_work_type[]"]');
                      const $workTypeAgree = $('#work_type_agree');
                      const WORK_TYPES = ["주간", "야간", "2교대"];

                      function updateWorkTypeState() {
                        const checkedValues = [];
                        $workTypeCheckboxes.each(function() {
                          if ($(this).is(':checked')) {
                            checkedValues.push($(this).val());
                          }
                        });

                        // 주간, 야간, 2교대가 모두 선택되었는지 확인
                        const allSelected = WORK_TYPES.every(type => checkedValues.includes(type));

                        // 무관 버튼 상태 업데이트 (change 이벤트 발생하지 않도록)
                        $workTypeAgree.prop('checked', allSelected);
                      }

                      function selectAllWorkTypes() {
                        WORK_TYPES.forEach(type => {
                          $workTypeCheckboxes.filter(`[value="${type}"]`).prop('checked', true);
                        });
                      }

                      function deselectAllWorkTypes() {
                        $workTypeCheckboxes.prop('checked', false);
                      }

                      // 체크박스 변경 시
                      $workTypeCheckboxes.on('change', function() {
                        updateWorkTypeState();

                        // 선택된 근무형태 확인
                        var isAMSelected = $workTypeCheckboxes.filter('[value="주간"]').is(':checked');
                        var isPMSelected = $workTypeCheckboxes.filter('[value="야간"]').is(':checked');

                        // 선택되지 않은 근무형태의 시간값 초기화
                        if (!isAMSelected) {
                          $('input[name="rc_am_stime_h"]').val('09');
                          $('input[name="rc_am_stime_m"]').val('00');
                          $('input[name="rc_am_etime_h"]').val('18');
                          $('input[name="rc_am_etime_m"]').val('00');
                          $('input[name="rc_am_stime"]').val('');
                          $('input[name="rc_am_etime"]').val('');
                        }
                        if (!isPMSelected) {
                          $('input[name="rc_pm_stime_h"]').val('21');
                          $('input[name="rc_pm_stime_m"]').val('00');
                          $('input[name="rc_pm_etime_h"]').val('06');
                          $('input[name="rc_pm_etime_m"]').val('00');
                          $('input[name="rc_pm_stime"]').val('');
                          $('input[name="rc_pm_etime"]').val('');
                        }
                      });

                      // 무관 체크박스 변경 시 (toggle)
                      $workTypeAgree.on('change', function() {
                        if ($(this).is(':checked')) {
                          // 무관 체크 → 모두 선택
                          selectAllWorkTypes();
                        } else {
                          // 무관 언체크 → 모두 해제
                          deselectAllWorkTypes();
                        }
                      });

                      // 초기 상태 확인
                      updateWorkTypeState();
                    });
                  </script>
                </div>
                <div class="form-row work-section-row" id="time_cont1" <?= (array_search("주간", $info["rc_work_type"]) !== false || array_search("2교대", $info["rc_work_type"]) !== false) ? "" : "hidden" ?>>
                  <p class="form-label">근무시간</p>
                  <div class="value work-time-value">
                    <div class="work-time-group">
                      <!-- 출근 -->
                      <span class="work-time-label">출근</span>
                      <div class="work-time-input-wrap">
                        <select id="rc_am_stime_h" name="rc_am_stime_h" class="time-select">
                          <?php
                          $amStartH = !empty($info["rc_am_stime"]) ? substr($info["rc_am_stime"], 0, 2) : "09";
                          $amStartM = !empty($info["rc_am_stime"]) ? substr($info["rc_am_stime"], 3, 2) : "00";
                          for ($t1 = 0; $t1 <= 23; $t1++) { ?>
                            <option value="<?= str_pad($t1, 2, "0", STR_PAD_LEFT) ?>" <?= $amStartH == str_pad($t1, 2, "0", STR_PAD_LEFT) ? "selected" : "" ?>><?= str_pad($t1, 2, "0", STR_PAD_LEFT) ?></option>
                          <?php } ?>
                        </select>
                        <span>:</span>
                        <input type="text" name="rc_am_stime_m" value="<?= $amStartM ?>" placeholder="00" class="time-input-m">
                      </div>
                      <input type="hidden" name="rc_am_stime" value="<?= $info["rc_am_stime"] ?>">

                      <!-- 퇴근 -->
                      <span class="work-time-label">퇴근</span>
                      <div class="work-time-input-wrap">
                        <select id="rc_am_etime_h" name="rc_am_etime_h" class="time-select">
                          <?php
                          $amEndH = !empty($info["rc_am_etime"]) ? substr($info["rc_am_etime"], 0, 2) : "18";
                          $amEndM = !empty($info["rc_am_etime"]) ? substr($info["rc_am_etime"], 3, 2) : "00";
                          for ($t2 = 0; $t2 <= 23; $t2++) { ?>
                            <option value="<?= str_pad($t2, 2, "0", STR_PAD_LEFT) ?>" <?= $amEndH == str_pad($t2, 2, "0", STR_PAD_LEFT) ? "selected" : "" ?>><?= str_pad($t2, 2, "0", STR_PAD_LEFT) ?></option>
                          <?php } ?>
                        </select>
                        <span>:</span>
                        <input type="text" name="rc_am_etime_m" value="<?= $amEndM ?>" placeholder="00" class="time-input-m">
                      </div>
                      <input type="hidden" name="rc_am_etime" value="<?= $info["rc_am_etime"] ?>">

                      <!-- 주간 태그 -->
                      <span class="work-time-tag">주간</span>
                    </div>
                  </div>
                </div>
                <div class="form-row work-section-row" id="time_cont2" <?= (array_search("야간", $info["rc_work_type"]) !== false || array_search("2교대", $info["rc_work_type"]) !== false) ? "" : "hidden" ?>>
                  <p class="form-label">근무시간</p>
                  <div class="value work-time-value">
                    <div class="work-time-group">
                      <!-- 출근 -->
                      <span class="work-time-label">출근</span>
                      <div class="work-time-input-wrap">
                        <select id="rc_pm_stime_h" name="rc_pm_stime_h" class="time-select">
                          <?php
                          $pmStartH = !empty($info["rc_pm_stime"]) ? substr($info["rc_pm_stime"], 0, 2) : "21";
                          $pmStartM = !empty($info["rc_pm_stime"]) ? substr($info["rc_pm_stime"], 3, 2) : "00";
                          for ($t1 = 0; $t1 <= 23; $t1++) { ?>
                            <option value="<?= str_pad($t1, 2, "0", STR_PAD_LEFT) ?>" <?= $pmStartH == str_pad($t1, 2, "0", STR_PAD_LEFT) ? "selected" : "" ?>><?= str_pad($t1, 2, "0", STR_PAD_LEFT) ?></option>
                          <?php } ?>
                        </select>
                        <span>:</span>
                        <input type="text" name="rc_pm_stime_m" value="<?= $pmStartM ?>" placeholder="00" class="time-input-m">
                      </div>
                      <input type="hidden" name="rc_pm_stime" value="<?= $info["rc_pm_stime"] ?>">

                      <!-- 퇴근 -->
                      <span class="work-time-label">퇴근</span>
                      <div class="work-time-input-wrap">
                        <select id="rc_pm_etime_h" name="rc_pm_etime_h" class="time-select">
                          <?php
                          $pmEndH = !empty($info["rc_pm_etime"]) ? substr($info["rc_pm_etime"], 0, 2) : "06";
                          $pmEndM = !empty($info["rc_pm_etime"]) ? substr($info["rc_pm_etime"], 3, 2) : "00";
                          for ($t2 = 0; $t2 <= 23; $t2++) { ?>
                            <option value="<?= str_pad($t2, 2, "0", STR_PAD_LEFT) ?>" <?= $pmEndH == str_pad($t2, 2, "0", STR_PAD_LEFT) ? "selected" : "" ?>><?= str_pad($t2, 2, "0", STR_PAD_LEFT) ?></option>
                          <?php } ?>
                        </select>
                        <span>:</span>
                        <input type="text" name="rc_pm_etime_m" value="<?= $pmEndM ?>" placeholder="00" class="time-input-m">
                      </div>
                      <input type="hidden" name="rc_pm_etime" value="<?= $info["rc_pm_etime"] ?>">

                      <!-- 야간 태그 -->
                      <span class="work-time-tag">야간</span>
                    </div>
                  </div>
                </div>

                <div class="form-row work-section-row salary-row">
                  <p class="form-label">급여형태</p>
                  <div class="value salary-value">
                    <style>
                      .salary-value {
                        flex: 1;
                        margin-top: 0;
                      }

                      .salary-value>.row {
                        display: flex;
                        gap: 12px;
                        flex-direction: row;
                        align-items: flex-start;
                        flex-wrap: wrap;
                      }

                      .row {
                        display: flex;
                        gap: 8px;
                        flex-direction: column;
                      }

                      .extraField {
                        display: flex;
                        gap: 8px;
                        flex-direction: column;
                      }

                      .checks {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
                        gap: 10px;
                      }

                      .chip {
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        width: 100%;
                        background: #fff;
                        border: 1px solid var(--rc-border-color);
                        border-radius: 999px;
                        padding: 10px 12px;
                        cursor: pointer;
                        justify-content: center;
                        color: #777;
                      }

                      .chip.on {
                        background: var(--rc-color-primary-light);
                        border: 1px solid #57CCAF;
                        color: #fff;
                      }

                      .chip input[type="radio"],
                      .chip2 input[type="radio"] {
                        display: none;
                      }

                      .checks2 {
                        display: flex;
                        gap: 12px;
                      }

                      .chip2 {
                        position: relative;
                        padding-left: 28px;
                        cursor: pointer;
                      }

                      .chip2.on {
                        color: #57CCAF;
                      }

                      .chip2::before {
                        content: "";
                        display: block;
                        position: absolute;
                        left: 5px;
                        width: 1.25rem;
                        height: 1.25rem;
                        background: url(/resource/shop/mobile/img/ic_chkbox_off.svg) no-repeat 0 0;
                        background-size: contain;
                        border-radius: 100%;
                        z-index: 1;
                      }

                      .chip2.on::before {
                        background: url(/resource/shop/mobile/img/ic_chkbox_on.svg) no-repeat 0 0;
                        background-size: contain;
                      }

                      .amountField {
                        display: flex;
                        gap: 8px;
                        height: 34px;
                      }

                      .amountField .amount {
                        display: flex;
                        gap: 4px;
                        align-items: center;
                        width: 40%;
                        border-radius: 999px;
                        border: 1px solid #57CCAF;
                      }

                      .amountField .amount input[type="text"] {
                        width: 100%;
                        margin-left: 16px;
                        border: none;
                        outline: none;
                      }

                      .amountField .amount input[type="text"]:focus {
                        outline: none;
                      }

                      .unit {
                        white-space: nowrap;
                        padding-right: 12px;
                      }

                      .hide {
                        display: none !important;
                      }
                    </style>
                    <?php
                    $rc_price_type_list = ["시급", "일급", "주급", "월급", "급여협의"];
                    $rc_price_type_unit = [
                      "시급" => "원/시",
                      "일급" => "원/일",
                      "주급" => "원/주",
                      "월급" => "원/월",
                      "협의" => "-"
                    ];
                    ?>
                    <input type="hidden" class="hidden_price_value" name="rc_price_type" value="<?= $info["rc_price_type"] ?>">
                    <input type="hidden" class="hidden_price_value" name="rc_price_rep" value="<?= $info["rc_price_rep"] ?>">
                    <input type="hidden" class="hidden_price_value" name="rc_price_pay" value="<?= $info["rc_price_pay"] ?>">
                    <input type="hidden" class="hidden_price_value" name="rc_day_price" value="<?= $info["rc_day_price"] ?>">
                    <input type="hidden" class="hidden_price_value" name="rc_week_price" value="<?= $info["rc_week_price"] ?>">
                    <input type="hidden" class="hidden_price_value" name="rc_month_price" value="<?= $info["rc_month_price"] ?>">

                    <div class="row">
                      <div class="salary-select-wrap">
                        <select id="rc_price_rep_select" name="rc_price_rep_select" class="salary-select">
                          <option value="">선택</option>
                          <?php foreach ($rc_price_type_list as $row) : ?>
                            <option value="<?= $row ?>" <?= ($info['rc_price_rep'] === $row ? 'selected' : '') ?>>
                              <?= $row ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <div class="salary-amount-wrap">
                        <input type="text"
                          class="form-text salary-amount-input"
                          id="rc_price_pay"
                          value="<?= $info['rc_price_pay'] != null ? number_format($info['rc_price_pay']) : '' ?>"
                          placeholder="금액 입력" />
                        <span class="salary-unit" id="amountUnit">
                          <?= isset($rc_price_type_unit[$info['rc_price_rep']]) ? $rc_price_type_unit[$info['rc_price_rep']] : '원' ?>
                        </span>
                      </div>

                      <div class="salary-extra-wrap" id="extraChecks">
                        <?php foreach ($rc_price_type_list as $row) : ?>
                          <?php if ($row !== $info['rc_price_rep'] && $row !== "급여협의") : ?>
                            <label class="chip2 <?= (strpos($info['rc_price_type'], $row) !== false ? 'on' : '') ?>">
                              <input type="radio"
                                name="rc_price_extra"
                                value="<?= $row ?>"
                                <?= (strpos($info['rc_price_type'], $row) !== false ? 'checked' : '') ?> />
                              <span><?= $row ?>가능</span>
                            </label>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </div>
                    </div>
                    <script>
                      $(function() {
                        const PAY_TYPES = ["시급", "일급", "주급", "월급", "급여협의"];
                        const UNIT_BY_TYPE = {
                          "시급": "원/시",
                          "일급": "원/일",
                          "주급": "원/주",
                          "월급": "원/월",
                          "급여협의": "-"
                        };
                        const $priceSelect = $("#rc_price_rep_select"),
                          $amountUnit = $("#amountUnit"),
                          $amountInput = $("#rc_price_pay"),
                          $salaryAmountWrap = $(".salary-amount-wrap"),
                          $extraChecks = $("#extraChecks");

                        function getConvertPriceType(rc_price_type, rc_price_rep) {

                          if (rc_price_rep === "급여협의") {
                            return '';
                          }

                          const types = rc_price_type.split(',');

                          if (types.length === 1) {
                            return `<em class="on">${types[0]}</em>`;
                          }

                          return types.map(type => {
                            if (type === rc_price_rep) {
                              return `<em class="on">${type}</em>`;
                            } else {
                              return `<em>${type}</em>`;
                            }
                          }).join('');
                        }

                        function buildExtraChecks(rep) {
                          $extraChecks.empty();
                          const extras = PAY_TYPES.filter(t => t !== rep && t !== "급여협의");
                          extras.forEach((t, i) => {
                            $extraChecks.append(`<label class="chip2"><input type="radio" name="rc_price_extra" value="${t}" /> <span>${t}가능</span></label>`);
                          });
                        }

                        function setVisibility(rep) {
                          const isNeg = rep === "급여협의";
                          // 급여협의 선택 시 금액 입력과 추가옵션 숨김
                          if (isNeg) {
                            $salaryAmountWrap.hide();
                            $extraChecks.hide();
                            $amountInput.val("");
                          } else {
                            $salaryAmountWrap.show();
                            $extraChecks.show();
                          }
                        }

                        // Select 변경 이벤트
                        $priceSelect.on("change", function() {
                          const val = $(this).val();
                          if (!val) return;

                          $amountInput.val("");
                          $('.hidden_price_value').val('');
                          $amountUnit.text(UNIT_BY_TYPE[val] || "원");
                          setVisibility(val);

                          // 급여협의가 아닐 때만 추가옵션 생성
                          if (val !== "급여협의") {
                            buildExtraChecks(val);
                          } else {
                            $extraChecks.empty().hide();
                          }
                          pricePayUpdate();
                        });

                        // 추가옵션 선택 처리
                        $(document).on("click", 'input[name="rc_price_extra"]', function() {
                          const $this = $(this);
                          $(`input[name="rc_price_extra"]`).closest('label').removeClass('on');
                          $this.closest('label').addClass('on');
                          pricePayUpdate();
                        });

                        $amountInput.on("input", function() {
                          let val = $(this).val().replace(/,/g, "");
                          if (val && !isNaN(val)) {
                            $(this).val(Number(val).toLocaleString());
                          }
                          pricePayUpdate();
                        });

                        function pricePayUpdate() {
                          const rep = $priceSelect.val();
                          if (!rep) {
                            return;
                          }

                          const isNeg = rep === "급여협의";
                          let amount = null;
                          if (!isNeg) {
                            amount = $amountInput.val().replace(/,/g, "");
                            if (!amount) {
                              return;
                            }
                            amount = Number(amount);
                          }

                          const rc_price_type_tmp = [rep];
                          $('input[name="rc_price_extra"]:checked').each(function() {
                            rc_price_type_tmp.push($(this).val());
                          });

                          const rc_price_type = rc_price_type_tmp
                            .sort((a, b) => PAY_TYPES.indexOf(a) - PAY_TYPES.indexOf(b))
                            .join(",");

                          $('[name="rc_price_type"]').val(rc_price_type);
                          $('[name="rc_price_rep"]').val(rep);
                          $('[name="rc_price_pay"]').val(amount);

                          // 전체 시급 적용전까지 임시처리 시작
                          if (rep == '일급') {
                            $('[name="rc_day_price"]').val(amount);
                            $('[name="rc_week_price"]').val('');
                            $('[name="rc_month_price"]').val('');
                          }
                          if (rep == '주급') {
                            $('[name="rc_day_price"]').val('');
                            $('[name="rc_week_price"]').val(amount);
                            $('[name="rc_month_price"]').val('');
                          }
                          if (rep == '월급') {
                            $('[name="rc_day_price"]').val('');
                            $('[name="rc_week_price"]').val('');
                            $('[name="rc_month_price"]').val(amount);
                          }
                          if (rep == '급여협의') {
                            $('[name="rc_day_price"]').val('');
                            $('[name="rc_week_price"]').val('');
                            $('[name="rc_month_price"]').val('');
                          }
                          // 전체 시급 적용전까지 임시처리 끝
                        }

                      });
                    </script>
                  </div>
                </div>

                <div class="form-row work-section-row services">
                  <p class="form-label">복리후생</p>
                  <div class="value">

                    <div class="form-group">
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="국민연금" <?= in_array_return("국민연금", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit1-1">국민연금</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="고용보험" <?= in_array_return("고용보험", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit1-2">고용보험</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="산재보험" <?= in_array_return("산재보험", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit1-3">산재보험</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="건강보험" <?= in_array_return("건강보험", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit1-4">건강보험</span>
                      </label>
                    </div>

                    <div class="form-group">
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="연차" <?= in_array_return("연차", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit2-1">연차</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="월차" <?= in_array_return("월차", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit2-2">월차</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="여름휴가" <?= in_array_return("여름휴가", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit2-3">여름휴가</span>
                      </label>
                    </div>

                    <div class="form-group">
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="야근근로수당" <?= in_array_return("야근근로수당", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-1">야근근로수당</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="휴일근로수당" <?= in_array_return("휴일근로수당", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-2">휴일근로수당</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="장기근속수당" <?= in_array_return("장기근속수당", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-3">장기근속수당</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="연장근로수당" <?= in_array_return("연장근로수당", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-4">연장근로수당</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="인센티브제" <?= in_array_return("인센티브제", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-5">인센티브제</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="퇴직금" <?= in_array_return("퇴직금", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-6">퇴직금</span>
                      </label>
                    </div>

                    <div class="form-group">
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="조식제공" <?= in_array_return("조식제공", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-1">조식제공</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="중식제공" <?= in_array_return("중식제공", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-2">중식제공</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="석식제공" <?= in_array_return("석식제공", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-3">석식제공</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="근무복지급" <?= in_array_return("근무복지급", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-4">근무복지급</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="통근버스운행" <?= in_array_return("통근버스운행", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-5">통근버스운행</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="차량유류비지원" <?= in_array_return("차량유류비지원", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-6">차량유류비지원</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="기숙사운영" <?= in_array_return("기숙사운영", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-7">기숙사운영</span>
                      </label>
                    </div>
                  </div>
                </div><!-- //복리후생 form-row -->
              </div><!-- //form-table 근무조건 -->
            </div><!-- //근무조건 섹션 -->

            <!-- 업무정보 섹션 -->
            <div class="form-section">
              <p class="form-section-title">업무정보<span class="fixed">*</span></p>
              <div class="form-table">
                <div class="form-row">
                  <p class="form-label">상세업무</p>
                  <div class="value desc-value">
                   

                    <label>
                      <span class="sr-text">상세업무정보 입력</span>
                      <textarea id="rc_desc" name="rc_desc" class="form-textarea js-autoheight desc-textarea"><?= $info["rc_desc"] ?></textarea>
                    </label>
                     <!-- 사진 업로드 버튼 -->
                    <div>
                      <button type="button" id="uploadPhotoBtn" style="padding: 8px 16px; background-color: #31A182; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 8px;"><i class="fa-regular fa-image" style="font-size: 16px;"></i> 사진 추가</button>
                      <input type="file" id="photoFileInput" accept="image/*" style="display:none;">
                    </div>
                  </div>
                </div>

                <div class="form-row">
                  <p class="form-label">해시태그</p>
                  <div class="value">
                    <!-- 숨겨진 textarea (서버 전송용) -->
                    <textarea name="rc_hash" class="hash-hidden"><?= htmlspecialchars($info["rc_hash"], ENT_QUOTES, 'UTF-8') ?></textarea>

                    <!-- 미리 정의된 태그 버튼들 -->
                    <div class="hash-preset-wrap">
                      <div class="hash-preset-group">
                        <label class="hash-preset-btn">
                          <input type="checkbox" value="당일지급">
                          <span class="btn">당일지급</span>
                        </label>
                        <label class="hash-preset-btn">
                          <input type="checkbox" value="가불가능">
                          <span class="btn">가불가능</span>
                        </label>
                        <label class="hash-preset-btn">
                          <input type="checkbox" value="초보가능">
                          <span class="btn">초보가능</span>
                        </label>
                        <label class="hash-preset-btn">
                          <input type="checkbox" value="즉시출근">
                          <span class="btn">즉시출근</span>
                        </label>
                        <label class="hash-preset-btn">
                          <input type="checkbox" value="식사제공">
                          <span class="btn">식사제공</span>
                        </label>
                        <label class="hash-preset-btn">
                          <input type="checkbox" value="외국인가능">
                          <span class="btn">외국인가능</span>
                        </label>
                        <label class="hash-preset-btn">
                          <input type="checkbox" value="주부환영">
                          <span class="btn">주부환영</span>
                        </label>
                        <label class="hash-preset-btn">
                          <input type="checkbox" value="동반가능">
                          <span class="btn">동반가능</span>
                        </label>
                        <label class="hash-preset-btn">
                          <input type="checkbox" value="상여금">
                          <span class="btn">상여금</span>
                        </label>
                        <label class="hash-preset-btn">
                          <input type="checkbox" value="경력우대">
                          <span class="btn">경력우대</span>
                        </label>
                        <label class="hash-preset-btn hash-add-btn">
                          <span class="btn">+</span>
                        </label>
                      </div>
                    </div>

                    <!-- 직접입력 행 -->
                    <div class="hash-direct-wrap">
                      <input type="text" class="hash-direct-input" placeholder="직접입력">
                      <button type="button" class="hash-confirm-btn">확인</button>
                    </div>
                  </div>
                </div>
              </div><!-- //form-table 업무정보 -->
            </div><!-- //업무정보 섹션 -->

            <script>
              $(function() {
                // 페이지 로드 시 기존 rc_hash 값 복원
                var savedHash = $('textarea[name="rc_hash"]').val();
                if (savedHash) {
                  var savedTags = savedHash.split(',');
                  $.each(savedTags, function(i, tag) {
                    tag = $.trim(tag).replace(/^#/, '');
                    if (tag) {
                      // 프리셋 버튼 중 일치하는 것 체크
                      $('.hash-preset-btn input[type="checkbox"]').each(function() {
                        if ($(this).val() === tag) {
                          $(this).prop('checked', true);
                        }
                      });
                    }
                  });
                }

                // 프리셋 버튼 클릭 시 textarea 업데이트
                function syncHashToTextarea() {
                  var selected = [];
                  $('.hash-preset-btn input[type="checkbox"]:checked').each(function() {
                    selected.push($(this).val());
                  });
                  $('textarea[name="rc_hash"]').val(selected.join(','));
                }

                $('.hash-preset-btn input[type="checkbox"]').on('change', function() {
                  syncHashToTextarea();
                });

                // 페이지 로드 시 초기 동기화
                syncHashToTextarea();

                // 태그 추가 함수
                function addHashTag() {
                  var inputVal = $.trim($('.hash-direct-input').val());
                  if (!inputVal) return;
                  var tagVal = inputVal.replace(/^#/, '');

                  // 중복 체크
                  var isDuplicate = false;
                  $('.hash-preset-btn input[type="checkbox"]').each(function() {
                    if ($(this).val() === tagVal) {
                      $(this).prop('checked', true);
                      isDuplicate = true;
                    }
                  });

                  if (!isDuplicate) {
                    // 새 버튼 동적 추가 (+ 버튼 앞에)
                    var newBtn = '<label class="hash-preset-btn hash-custom-tag">' +
                      '<input type="checkbox" value="' + tagVal + '" checked>' +
                      '<span class="btn">' + tagVal + '</span>' +
                      '</label>';
                    $('.hash-add-btn').before(newBtn);

                    // 이벤트 재바인딩
                    $('.hash-custom-tag input[type="checkbox"]').on('change', function() {
                      syncHashToTextarea();
                    });
                  }

                  $('.hash-direct-input').val('');
                  syncHashToTextarea();
                }

                // 확인 버튼: 직접입력 태그를 동적 버튼으로 추가
                $('.hash-confirm-btn').on('click', function() {
                  addHashTag();
                });

                // Enter 키로도 태그 추가
                $('.hash-direct-input').on('keypress', function(e) {
                  if (e.which === 13) { // Enter 키
                    e.preventDefault();
                    addHashTag();
                  }
                });

                // + 버튼 클릭 시 직접입력 포커스
                $('.hash-add-btn').on('click', function() {
                  $('.hash-direct-input').focus();
                });
              });
            </script>

            <!-- 추가정보 섹션 -->
            <div class="form-section extra-section">
              <p class="form-section-title">추가정보</p>
              <div class="form-table">
                <div class="form-row">
                  <p class="form-label">근무요일</p>
                  <div class="value">
                    <div class="form-group week-day">
                      <?php foreach ($rc_week_list as $row) : ?>
                        <label class="form-toggle-btn2 sm">
                          <input type="checkbox" name="rc_week[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info["rc_week"], "check") ?>>
                          <span class="btn"><?= $row["cd_val"] ?></span>
                        </label>
                      <?php endforeach; ?>
                      <div class="extra-agree-wrap">
                        <label class="form-checkbox">
                          <input type="checkbox" name="rc_week_yn" value="Y" <?= $info["rc_week_yn"] == "Y" ? "checked" : "" ?>>
                          <span class="label">무관</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label">희망국적</p>
                  <div class="value">
                    <div class="form-group nation-group">
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_nation[]" value="내국인" <?= in_array_return("내국인", $info["rc_nation"], "check") ?>>
                        <span class="btn">내국인</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_nation[]" value="외국인" <?= in_array_return("외국인", $info["rc_nation"], "check") ?>>
                        <span class="btn">외국인</span>
                      </label>
                      <div class="extra-agree-wrap">
                        <label class="form-checkbox">
                          <input type="checkbox" id="rc_nation_yn" name="rc_nation_yn" value="Y" <?= (isset($info["rc_nation_yn"]) && $info["rc_nation_yn"] == "Y") ? "checked" : "" ?>>
                          <span class="label">무관</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label">희망성별</p>
                  <div class="value">
                    <div class="form-group sex-group">
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_sex[]" value="남자" <?= in_array_return("남자", $info["rc_sex"], "check") ?>>
                        <span class="btn">남자</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_sex[]" value="여자" <?= in_array_return("여자", $info["rc_sex"], "check") ?>>
                        <span class="btn">여자</span>
                      </label>
                      <div class="extra-agree-wrap">
                        <label class="form-checkbox">
                          <input type="checkbox" id="rc_sex_yn" name="rc_sex_yn" value="Y" <?= (isset($info["rc_sex_yn"]) && $info["rc_sex_yn"] == "Y") ? "checked" : "" ?>>
                          <span class="label">무관</span>
                        </label>
                      </div>
                      <div class="law-link-wrap">
                        <p class="viewLaw" onClick="viewLaw(1);">남녀고용평등법 안내</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label">희망나이</p>
                  <div class="value">
                    <div class="form-group age-group">
                      <label class="form-selectbox col">
                        <span class="sr-text">최소나이 선택</span>
                        <select name="rc_sage" class="age-select" data-disabled="false">
                          <option value="">선택</option>
                          <?php $ages = [20, 30, 40, 50,60,70];
                          foreach ($ages as $age) : ?>
                            <option value="<?= $age ?>" <?= $info["rc_sage"] == $age ? "selected" : "" ?>><?= $age ?>대</option>
                          <?php endforeach; ?>
                        </select>
                        <span class="liner"></span>
                      </label>
                      <span class="entity">~</span>
                      <label class="form-selectbox col">
                        <span class="sr-text">최대나이 선택</span>
                        <select name="rc_eage" class="age-select" data-disabled="false">
                          <option value="">선택</option>
                          <?php foreach ($ages as $age) : ?>
                            <option value="<?= $age ?>" <?= $info["rc_eage"] == $age ? "selected" : "" ?>><?= $age ?>대</option>
                          <?php endforeach; ?>
                        </select>
                        <span class="liner"></span>
                      </label>
                      <div class="extra-agree-wrap">
                        <label class="form-checkbox">
                          <input type="checkbox" id="rc_age_yn" name="rc_age_yn" value="Y" <?= (isset($info["rc_age_yn"]) && $info["rc_age_yn"] == "Y") ? "checked" : "" ?>>
                          <span class="label">무관</span>
                        </label>
                      </div>
                      <div class="law-link-wrap indent">
                        <p class="viewLaw" onClick="viewLaw(2);">연령차별금지법 안내</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label">첨부사진</p>
                  <div class="value">
                    <div class="form-pic-group">
                      <?php foreach (range(1, 4) as $n) : ?>
                        <label class="form-pic">
                          <input type="file" name="rc_img<?= $n ?>" id="rc_img<?= $n ?>" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(<?= $n ?>);">
                          <span class="label pic-label"></span>
                          <span class="preview" id="prev<?= $n ?>">
                            <?php if (getData($info, "rc_img" . $n) != "") : ?>
                              <img id="imgbox<?= $n ?>" src="<?= getData($info, "rc_img" . $n) ?>" class="has-image" />
                              <input type='hidden' name='rc_img<?= $n ?>_ori' id='rc_img<?= $n ?>_ori' value='<?= getData($info, "rc_img" . $n) ?>' />
                            <?php endif; ?>
                          </span>
                        </label>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
              </div><!-- //form-table 추가정보2 -->
            </div><!-- //추가정보 섹션 -->

        </div>
      </div>

      <!-- 상세등록 섹션 -->
      <div class="detail-register-section">
        <a href="#" id="detailRegisterBtn" class="detail-register-link">상세등록</a>
      </div>

      <div class="form-action-wrap">
        <button id="btnStep" type="button" class="btn-round btn-tertiary"><?= $info["rc_idx"] != "0" ? ($_GET["gbn"] == "re" ? "등록" : "수정") : "등록" ?>완료</button>
      </div>
      </form>

      <script>
        $(function() {
          // 상세등록 버튼 클릭 - 추가정보 섹션 표시
          $('#detailRegisterBtn').on('click', function(e) {
            e.preventDefault();
            var $extraSection = $('.extra-section');
            var isVisible = $extraSection.hasClass('show');

            if (!isVisible) {
              // 섹션 보이기
              $extraSection.addClass('show');

              // 첫 오픈 시에만 기본값 설정 (이미 선택된 값이 없을 때)
              // 희망국적이 선택되지 않았다면 무관으로 설정
              if (!$('input[name="rc_nation[]"]:checked').length && !$('#rc_nation_yn').is(':checked')) {
                $('#rc_nation_yn').prop('checked', true).trigger('change');
              }
              // 희망성별이 선택되지 않았다면 무관으로 설정
              if (!$('input[name="rc_sex[]"]:checked').length && !$('#rc_sex_yn').is(':checked')) {
                $('#rc_sex_yn').prop('checked', true).trigger('change');
              }
              // 희망나이가 선택되지 않았다면 무관으로 설정
              if (!$('select[name="rc_sage"]').val() && !$('select[name="rc_eage"]').val() && !$('#rc_age_yn').is(':checked')) {
                $('#rc_age_yn').prop('checked', true).trigger('change');
              }

              // 추가정보 섹션으로 스크롤
              $('html, body').animate({
                scrollTop: $extraSection.offset().top - 100
              }, 300);
            } else {
              // 섹션 숨기기
              $extraSection.removeClass('show');
            }
          });

          // 초기 상태: 추가정보 섹션 숨기기 (단, DB에서 로드된 데이터가 있다면 유지)
          var hasExtraData = $('input[name="rc_nation[]"]:checked').length > 0 ||
            $('input[name="rc_sex[]"]:checked').length > 0 ||
            $('select[name="rc_sage"]').val() ||
            $('select[name="rc_eage"]').val() ||
            $('#rc_nation_yn').is(':checked') ||
            $('#rc_sex_yn').is(':checked') ||
            $('#rc_age_yn').is(':checked');

          if (!hasExtraData) {
            $('.extra-section').removeClass('show');
          }

          // 희망국적 무관 토글
          $('#rc_nation_yn').on('change', function() {
            if ($(this).is(':checked')) {
              // 무관 체크 → 버튼들도 체크 (색깔로 표시)
              $('input[name="rc_nation[]"]').prop('checked', true);
            } else {
              // 무관 언체크 → 버튼들 언체크
              $('input[name="rc_nation[]"]').prop('checked', false);
            }
          });

          // 희망국적 버튼 change 이벤트
          $('[name="rc_nation[]"]').on('change', function() {
            var checkedCount = $('[name="rc_nation[]"]:checked').length;
            var totalCount = $('[name="rc_nation[]"]').length;
            if (checkedCount === totalCount) {
              // 모든 국적이 선택되면 국적무관 체크
              $('#rc_nation_yn').prop('checked', true);
            } else {
              // 하나라도 언체크되면 국적무관 언체크
              $('#rc_nation_yn').prop('checked', false);
            }
          });

          // 희망성별 무관 토글
          $('#rc_sex_yn').on('change', function() {
            if ($(this).is(':checked')) {
              // 무관 체크 → 버튼들도 체크 (색깔로 표시)
              $('input[name="rc_sex[]"]').prop('checked', true);
            } else {
              // 무관 언체크 → 버튼들 언체크
              $('input[name="rc_sex[]"]').prop('checked', false);
            }
          });

          // 희망성별 버튼 change 이벤트
          $('[name="rc_sex[]"]').on('change', function() {
            var checkedCount = $('[name="rc_sex[]"]:checked').length;
            var totalCount = $('[name="rc_sex[]"]').length;
            if (checkedCount === totalCount) {
              // 모든 성별이 선택되면 성별무관 체크
              $('#rc_sex_yn').prop('checked', true);
            } else {
              // 하나라도 언체크되면 성별무관 언체크
              $('#rc_sex_yn').prop('checked', false);
            }
          });

          // 희망나이 무관 토글
          $('#rc_age_yn').on('change', function() {
            if ($(this).is(':checked')) {
              // 무관 체크 → select 상태만 추적
              $('select[name="rc_sage"], select[name="rc_eage"]').val('').attr('data-disabled', 'true');
            } else {
              // 무관 언체크 → select 선택 가능
              $('select[name="rc_sage"], select[name="rc_eage"]').attr('data-disabled', 'false');
            }
          });

          // 나이 선택시 이벤트 - disabled 상태에서 선택 시도 시 무관 자동 해제
          $(document).on('change', 'select[name="rc_sage"], select[name="rc_eage"]', function() {
            var $select = $(this);
            if ($select.attr('data-disabled') === 'true' && $select.val() !== '') {
              // 선택값이 있으면 무관 체크 자동 해제
              $('#rc_age_yn').prop('checked', false).trigger('change');
            }
          });

          // 초기 상태 복원
          if ($('#rc_nation_yn').is(':checked')) {
            $('input[name="rc_nation[]"]').prop('checked', true);
          }
          if ($('#rc_sex_yn').is(':checked')) {
            $('input[name="rc_sex[]"]').prop('checked', true);
          }
          if ($('#rc_age_yn').is(':checked')) {
            $('select[name="rc_sage"], select[name="rc_eage"]').attr('data-disabled', 'true');
          }
          if ($('input[name="rc_week_yn"]').is(':checked')) {
            $('input[name="rc_week[]"]').prop('checked', true);
          }

          // 데이터 복원 후 각 그룹의 "무관" 상태 동기화 (이벤트 트리거 없이 복원되었으므로 수동 재계산)
          setTimeout(function() {
            // 근무요일 동기화
            var weekCheckedCount = $('input[name="rc_week[]"]:checked').length;
            var weekTotalCount = $('input[name="rc_week[]"]').length;
            if (weekCheckedCount === weekTotalCount && weekCheckedCount > 0) {
              $('input[name="rc_week_yn"]').prop('checked', true);
            }

            // 희망성별 동기화
            var sexCheckedCount = $('input[name="rc_sex[]"]:checked').length;
            var sexTotalCount = $('input[name="rc_sex[]"]').length;
            if (sexCheckedCount === sexTotalCount && sexCheckedCount > 0) {
              $('#rc_sex_yn').prop('checked', true);
            }

            // 희망국적 동기화
            var nationCheckedCount = $('input[name="rc_nation[]"]:checked').length;
            var nationTotalCount = $('input[name="rc_nation[]"]').length;
            if (nationCheckedCount === nationTotalCount && nationCheckedCount > 0) {
              $('#rc_nation_yn').prop('checked', true);
            }
          }, 100);

          // 페이지 로드 시 기본값 설정
          (function() {
            var hasExtraSection = $('.extra-section').length > 0;
            if (!hasExtraSection) return;

            // 근무요일 기본값 설정 (월~금)
            var defaultWeekdays = ['월', '화', '수', '목', '금'];
            var hasExistingWeek = $('input[name="rc_week[]"]:checked').length > 0;
            var isWeekAllSelected = $('input[name="rc_week_yn"]').is(':checked');

            if (!hasExistingWeek && !isWeekAllSelected) {
              $('input[name="rc_week[]"]').each(function() {
                var $checkbox = $(this);
                if (defaultWeekdays.indexOf($checkbox.val()) !== -1) {
                  $checkbox.prop('checked', true);
                }
              });
            }

            // 희망성별 기본값 설정 (성별무관)
            var hasExistingSex = $('input[name="rc_sex[]"]:checked').length > 0;
            var isSexAllSelected = $('#rc_sex_yn').is(':checked');

            if (!hasExistingSex && !isSexAllSelected) {
              $('#rc_sex_yn').prop('checked', true).trigger('change');
            }

            // 희망국적 기본값 설정 (국적무관)
            var hasExistingNation = $('input[name="rc_nation[]"]:checked').length > 0;
            var isNationAllSelected = $('#rc_nation_yn').is(':checked');

            if (!hasExistingNation && !isNationAllSelected) {
              $('#rc_nation_yn').prop('checked', true).trigger('change');
            }

            // 희망나이 기본값 설정 (나이무관)
            var hasSageSelected = $('select[name="rc_sage"]').val() !== '';
            var hasEageSelected = $('select[name="rc_eage"]').val() !== '';
            var isAgeAllSelected = $('#rc_age_yn').is(':checked');

            if (!hasSageSelected && !hasEageSelected && !isAgeAllSelected) {
              $('#rc_age_yn').prop('checked', true).trigger('change');
            }
          }());

          // 근무요일 무관 토글
          $('input[name="rc_week_yn"]').on('change', function() {
            if ($(this).is(':checked')) {
              // 무관 체크 → 버튼들도 체크 (색깔로 표시)
              $('input[name="rc_week[]"]').prop('checked', true);
            } else {
              // 무관 언체크 → 버튼들 언체크
              $('input[name="rc_week[]"]').prop('checked', false);
            }
          });

          // 근무요일 버튼 이벤트
          $('[name="rc_week[]"]').on('change', function() {
            var checkedCount = $('[name="rc_week[]"]:checked').length;
            var totalCount = $('[name="rc_week[]"]').length;
            if (checkedCount === totalCount) {
              // 모든 요일이 선택되면 무관 체크
              $('input[name="rc_week_yn"]').prop('checked', true);
            } else if ($('input[name="rc_week_yn"]').is(':checked')) {
              // 하나라도 언체크되면 무관 언체크
              $('input[name="rc_week_yn"]').prop('checked', false);
            }
          });

          // 희망성별 버튼 이벤트
          $('[name="rc_sex[]"]').on('change', function() {
            var checkedCount = $('[name="rc_sex[]"]:checked').length;
            var totalCount = $('[name="rc_sex[]"]').length;
            if (checkedCount === totalCount) {
              // 모든 성별이 선택되면 성별무관 체크
              $('#rc_sex_yn').prop('checked', true);
            } else {
              // 하나라도 언체크되면 성별무관 언체크
              $('#rc_sex_yn').prop('checked', false);
            }
          });

          // 희망국적 버튼 이벤트
          $('[name="rc_nation[]"]').on('change', function() {
            var checkedCount = $('[name="rc_nation[]"]:checked').length;
            var totalCount = $('[name="rc_nation[]"]').length;
            if (checkedCount === totalCount) {
              // 모든 국적이 선택되면 국적무관 체크
              $('#rc_nation_yn').prop('checked', true);
            } else {
              // 하나라도 언체크되면 국적무관 언체크
              $('#rc_nation_yn').prop('checked', false);
            }
          });

          // 희망나이 선택 이벤트
          $('[name="rc_sage"], [name="rc_eage"]').on('change', function() {
            var sageVal = $('[name="rc_sage"]').val();
            var eageVal = $('[name="rc_eage"]').val();

            // 둘 다 선택되면 나이무관 체크
            if (sageVal !== '' && eageVal !== '') {
              $('#rc_age_yn').prop('checked', true);
            } else {
              // 하나라도 선택되지 않으면 나이무관 언체크
              $('#rc_age_yn').prop('checked', false);
            }
          });
        });
      </script>
    </div>
</div>
</div>
</main><!-- //page-content -->
</div><!-- //page -->


<div class="law_popup">
  <div class="tab_menu">
    <div class="tab1 on">남녀고용평등법 안내</div>
    <div class="tab2">연령차별금지법 안내</div>
    <div class="close"><a href="#"><span></span></a></div>
  </div>
  <div class="tab_content">
    <div class="tab_content1 on">
      <p class="tab_desc">
        채용 시 남녀를 차별하거나, 여성 근로자를 채용할 때 직무 수행에 불필요한 용모,키,체중 등의 신체조건 및 미혼 조건을 제시 또는 요구하는 경우 남녀고용평등법 위반에 해당되어 500만원 이하의 벌금이 부과될 수 있습니다.<br>
        <em>또한 등록제한규정에 따라 사전 동의 없이 삭제 조치될 수 있습니다.</em>
      </p>

      <div class="low">
        <div class="low1">
          <p class="tab_title">차별기준</p>
          <dl>
            <dt>특정성을 지칭하는 직종명 등 사용하는 경우</dt>
            <dd>- 남성 선반공, 병역필 남자에 한함, 세일즈맨, 웨이터, 여성비서, 미용사(여성 환영) 등</dd>
            <dt>직종 등 남녀 분리모집 하거나 모집인원수를 남녀를 달리 정한 경우</dt>
            <dd>- 관리·사무직 남성 10명, 판매직 여성 5명 → (개선) 관리·사무직 10명, 판매직 5명</dd>
            <dd>- 대졸남성 100명, 대졸여성 20명 → (개선) 대졸 120명</dd>
            <dt>학력·경력 등 동일한 자격임에도 다른 성별을 불리한 고용형태로 차등 채용</dt>
            <dd>- 3급 사원:4년제 대졸 남자, 4급 사원:4년제 대졸 여자</dd>
            <dt>직무 수행상 필요하지 않은 채용조건 부과 및 특성 성에 대해서만 다른 조건을 부여</dt>
            <dd>- 남성은 키 170cm, 체중 60kg 이상인 자 → (개선) 신체기준 삭제</dd>
            <dd>- 여성은 키 160cm, 체중 50kg 미만인 자 → (개선) 신체기준 삭제</dd>
            <dt>모집·채용에 관한 정보를 제공함에 있어 성별로 다르게 취합하는 경우</dt>
            <dd>- 회사에 대한 안내 등의 자료를 특정 성에게만 송부하고 다른 성에게는 제공하지 않는 등의 행위</dd>
            <dd>- 여성은 키 160cm, 체중 50kg 미만인 자 → (개선) 신체기준 삭제</dd>
          </dl>
        </div>

        <div class="low2">
          <p class="tab_title">차별로 보지 않는 사례</p>
          <dl>
            <dt>직무 성질상 특정한 성이 불가피한 경우</dt>
            <dd>- 남성 역할 위한 배우·모델 등</dd>
            <dd>- 여성 목욕탕의 여성 목욕 관리사, 여성 장애인·여성 환자의 도우미, 기숙사 사감 등</dd>
            <dd>- 교도관, 경비원 등 방법상 특정 성에 대한 배려를 필요로 하는 경우 등</dd>
            <dt>근로기준법 등 관계법령 상 여성취업이 금지된 직종에 남성만 채용하는 경우</dt>
            <dd>- 근로기준법 제65조(사용금지), 제72조(갱내근로금지) 및 같은 법시행령 제40조<br>(임산부 등의 사용금지 직종)에 근거</dd>
            <dt>현존하는 차별 없애거나 고용 평등 촉진 위해 잠정적으로 특정성 우대하는 조치를 하는 경우 등</dt>
          </dl>
        </div>
      </div>
      <div class="low_link">
        <a href="https://www.law.go.kr/LSW/lsInfoP.do?lsId=000130&ancYnChk=0#0000" target="_blank">*남녀고용평등법 자세히보기</a>
      </div>
    </div>
    <div class="tap_content2">
      <p class="tab_desc">
        채용 시 합리적인 이유 없이 연령제한을 하는 경우 연령차별금지법 위반에 해당되어 500만원 이하의 벌금이 부과될 수 있습니다.<br>
        <em>또한 등록제한규정에 따라 사전 동의 없이 삭제 조치될 수 있습니다</em>
      </p>
      <div class="low">
        <div class="low1">
          <p class="tab_title">차별기준</p>
          <dl>
            <dt>지원자의 나이를 제한하거나 특정 연령층을 선호하는 경우</dt>
          </dl>
        </div>

        <div class="low2">
          <p class="tab_title">차별로 보지 않는 사례</p>
          <dl>
            <dt>직무 성질상 특정 연령 기준이 불가피한 경우(진정 직업 자격)</dt>
            <dt>근속 기간의 차이를 고려하여 임금이나 임금 외의 금품과 복리후생에서 합리적인 차등을 두는 경우</dt>
            <dt>법률 등에 근거한 정년의 설정</dt>
            <dt>이 법이나 다른 법률에 따라 특정 연령 집단의 고용유지·촉진을 위한 지원 조치를 하는 경우</dt>
          </dl>
        </div>
      </div>
      <div class="low_link">
        <a href="https://www.law.go.kr/LSW/lsInfoP.do?lsId=000121&ancYnChk=0#0000" target="_blank">*연령차별금지법 자세히보기</a>
      </div>
    </div>
  </div>
</div>

<script>
  function viewLaw(no) {
    $('.law_popup').addClass('on');

    $('.tab_menu > div').removeClass('on');
    $('.tab_content > div').removeClass('on');

    if (no === 1) {
      $('.tab1').addClass('on');
      $('.tab_content1').addClass('on');
    } else if (no === 2) {
      $('.tab2').addClass('on');
      $('.tap_content2').addClass('on');
    }
  }

  // 법률 팝업 초기화
  function initLawPopup() {
    $('.tab1').click(function() {
      $('.tab_menu > div').removeClass('on');
      $('.tab_content > div').removeClass('on');
      $('.tab1').addClass('on');
      $('.tab_content1').addClass('on');
    });

    $('.tab2').click(function() {
      $('.tab_menu > div').removeClass('on');
      $('.tab_content > div').removeClass('on');
      $('.tab2').addClass('on');
      $('.tap_content2').addClass('on');
    });

    $('.close').click(function() {
      $('.law_popup').removeClass('on');
    });

    $(document).mouseup(function(e) {
      var container = $(".law_popup");
      if (container.hasClass('on') && !container.is(e.target) && container.has(e.target).length === 0) {
        container.removeClass('on');
      }
    });
  }
</script>

<script>
  $("[name=rc_addr_doro]").click(function() {
    var $panel = $("#changeArea");
    // 패널을 열 때 hidden 속성 제거 및 on 클래스 추가
    // $panel[0].hidden = false; 
    // 해당 코드는 jQuery 객체 '$panel'의 첫 번째 원소(일반적으로 DOM 요소)를 가져와서,
    // HTML5의 hidden 속성을 false로 설정함으로써 패널(주소변경 팝업 레이어)이 화면에 표시되도록 한다.
    // 즉, $('#changeArea')[0].hidden = false; 와 동일하며,
    // changeArea 레이어의 숨김 상태를 해제(visible)시키는 역할을 한다.
    $panel[0].hidden = false;
    $panel.addClass("on");
    // body 스크롤 잠금
    document.body.style.overflow = 'hidden';
    // 팝업을 열 때 기존 입력창 포커스를 제거하고 팝업 입력창으로 이동해 오타를 방지
    $(this).blur();
    var $popupAddrInput = $("#changeArea").find("#txt_addr_popup");
    if ($popupAddrInput.length) {
      // DOM 갱신 이후 안전하게 포커스를 옮김
      setTimeout(function() {
        $popupAddrInput.focus();
        // 패널에 포커스 설정
        $panel[0].focus();
      }, 100);
    }
  });

  // 통합 주소 선택 처리 함수 - data-target에 따라 적절한 필드 업데이트
  function selectAddrInfo(zipcode, doro, jibun, addr, x, y) {
    var target = $("#changeArea").attr("data-target");

    console.log("[주소선택] 통합 함수 호출 - target: " + target);

    if (target === "recruit") {
      // 근무지 처리
      $("#ad_zipcode").val(zipcode);
      $("#ad_jibun").val(jibun);
      $("#ad_doro").val(doro);
      $("#ad_addr").val(jibun);
      $("#ad_gps_x").val(x);
      $("#ad_gps_y").val(y);
      $("[name=rc_addr_doro]").val(addr);
      $("[name=changeArea]").val(addr);
    }

    // 팝업 닫기
    $("#changeArea").removeClass("on");
    document.body.style.overflow = '';
  }

  // 공고노출지역 팝업 콜백
  window.onChangeAreaApply = function(data) {
    // hidden 필드 초기화
    for (var i = 1; i <= 2; i++) {
      $("[name='expose_addr" + i + "_zipcode']").val("");
      $("[name='expose_addr" + i + "_jibun']").val("");
      $("[name='expose_addr" + i + "_doro_val']").val("");
      $("[name='expose_addr" + i + "_addr']").val("");
      $("[name='expose_addr" + i + "_gps_x']").val("");
      $("[name='expose_addr" + i + "_gps_y']").val("");
      $("#expose_addr" + i + "_doro").val("");
      $(".js-clear-expose-addr-web[data-index='" + i + "']").hide();
    }

    // 새 데이터 매핑
    if (data.searchArea && data.searchArea.length > 0) {
      data.searchArea.forEach(function(area, idx) {
        var n = idx + 1;
        $("[name='expose_addr" + n + "_addr']").val(area.addr);
        $("#expose_addr" + n + "_doro").val(data.addrNames[idx] || area.addr);
        $(".js-clear-expose-addr-web[data-index='" + n + "']").show();
      });
    }
    return true;
  };

  // 근무지 검색 아이콘 및 입력장 클릭 시 팝업 열기
  $(document).on("click", "input[name='rc_addr_doro'], .form-row.address .addr-search-icon", function() {
    $("#changeArea").attr("data-target", "recruit");
    var $panel = $("#changeArea");
    $panel[0].hidden = false;
    $panel.addClass("on");
    document.body.style.overflow = 'hidden';
    var $popupAddrInput = $("#changeArea").find("#txt_addr_popup");
    if ($popupAddrInput.length) {
      setTimeout(function() {
        $popupAddrInput.focus();
        $panel[0].focus();
      }, 100);
    }
  });

  // 노출지역 1 입력창 클릭 시 팝업 열기
  $(document).on("click", "#expose_addr1_doro, #expose_addr2_doro", function() {
    var $panel = $("#changeAreaExpose");
    $panel[0].hidden = false;
    $panel.addClass("on");
    document.body.style.overflow = 'hidden';
    $(this).blur();
    var $popupAddrInput = $("#changeAreaExpose").find("#txt_addr_expose");
    if ($popupAddrInput.length) {
      setTimeout(function() {
        $popupAddrInput.focus();
        $panel[0].focus();
      }, 100);
    }
  });

  // 주소 선택 영역 초기화 - 패널이 닫힐 때 스크롤 복원
  function initAddressArea() {
    if (window.location.pathname.indexOf('/compage/recruit_write') !== -1) {
      var $panel = $("#changeArea");
      var $panelExpose = $("#changeAreaExpose");

      // #changeArea (근무지 팝업) 모니터링
      if ($panel.length > 0) {
        // MutationObserver를 사용하여 패널의 클래스/속성 변경 감지
        var observer = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
              var $target = $(mutation.target);
              if (!$target.hasClass('on') && ($target[0].hidden === true || $target.css('display') === 'none')) {
                document.body.style.overflow = '';
              }
            }
            if (mutation.type === 'attributes' && mutation.attributeName === 'hidden') {
              var $target = $(mutation.target);
              if ($target[0].hidden === true) {
                document.body.style.overflow = '';
              }
            }
          });
        });

        observer.observe($panel[0], {
          attributes: true,
          attributeFilter: ['class', 'hidden', 'style']
        });

        // 패널 닫기 버튼 클릭 시에도 스크롤 복원
        $(document).on('click', '#changeArea .btn-close-popup, #changeArea .btnCloseArea', function() {
          setTimeout(function() {
            document.body.style.overflow = '';
          }, 100);
        });
      }

      // #changeAreaExpose (공고노출지역 팝업) 모니터링
      if ($panelExpose.length > 0) {
        var observerExpose = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
              var $target = $(mutation.target);
              if (!$target.hasClass('on')) {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
              }
            }
            if (mutation.type === 'attributes' && mutation.attributeName === 'hidden') {
              var $target = $(mutation.target);
              if ($target[0].hidden === true) {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
              }
            }
          });
        });

        observerExpose.observe($panelExpose[0], {
          attributes: true,
          attributeFilter: ['class', 'hidden', 'style']
        });
      }
    }
  }
</script>

<style>
  <?php
  if ($info == null || $info["mem_info"] == null || (!$is_pass_authenticated || !$is_comp_authenticated)) {
  ?>#defaultDialog1 .btn-close-popup {
    display: none;
  }

  #defaultDialog2 .btn-close-popup {
    display: none;
  }

  #defaultDialog3 .btn-close-popup {
    display: none;
  }

  #defaultDialog4 .btn-close-popup {
    display: none;
  }

  #defaultDialog5 .btn-close-popup {
    display: none;
  }

  <?php } ?>
</style>

<script>
  // 폼 검증 및 메인 로직 초기화
  function initFormValidation() {

    <?php
    // recruit_list.php에서 인증 후 이동한 경우이므로 자동 팝업 표시하지 않음
    // (recruit_list.php에서 이미 팝업을 표시했으므로)
    ?>

    $("[name='rc_work_type[]']").change(function() {
      $("#time_cont").attr("hidden", "");
      $("#time_cont1").attr("hidden", "");
      $("#time_cont2").attr("hidden", "");

      var isJoogan = $("#rc_work_type_0").is(":checked");
      var isYagan = $("#rc_work_type_1").is(":checked");
      var is2Gyodae = $("#rc_work_type_2").is(":checked");
      var isNoRelation = $("#rc_work_type_3").is(":checked");

      if (isJoogan) {
        $("#time_cont").removeAttr("hidden");
        $("#time_cont1").removeAttr("hidden");
        // 주간 선택 시 오전 시간 자동 설정: 09:00 ~ 18:00
        if ($("[name='rc_am_stime']").val() == "" || $("[name='rc_am_stime']").val() == "00:00") {
          $("#rc_am_stime_h").val("09");
          $("[name='rc_am_stime_m']").val("00");
          $("[name='rc_am_stime']").val("09:00");
          $("#rc_am_etime_h").val("18");
          $("[name='rc_am_etime_m']").val("00");
          $("[name='rc_am_etime']").val("18:00");
        }
      }

      if (isYagan) {
        $("#time_cont").removeAttr("hidden");
        $("#time_cont2").removeAttr("hidden");
      }

      if (is2Gyodae) {
        $("#time_cont").removeAttr("hidden");
        $("#time_cont1").removeAttr("hidden");
        $("#time_cont2").removeAttr("hidden");
        // 2교대 선택 시 오전은 자동 설정: 09:00 ~ 18:00
        if ($("[name='rc_am_stime']").val() == "" || $("[name='rc_am_stime']").val() == "00:00") {
          $("#rc_am_stime_h").val("09");
          $("[name='rc_am_stime_m']").val("00");
          $("[name='rc_am_stime']").val("09:00");
          $("#rc_am_etime_h").val("18");
          $("[name='rc_am_etime_m']").val("00");
          $("[name='rc_am_etime']").val("18:00");
        }
      }

      if (isNoRelation) {
        // 무관 선택 시 오전 시간 자동 설정: 09:00 ~ 18:00
        if ($("[name='rc_am_stime']").val() == "" || $("[name='rc_am_stime']").val() == "00:00") {
          $("#rc_am_stime_h").val("09");
          $("[name='rc_am_stime_m']").val("00");
          $("[name='rc_am_stime']").val("09:00");
          $("#rc_am_etime_h").val("18");
          $("[name='rc_am_etime_m']").val("00");
          $("[name='rc_am_etime']").val("18:00");
        }
      }

      if ($("[name='rc_work_type[]']:checked").length > 2) {
        $(this).closest(".form-row").find(".add_text").removeClass("hidden");
        $('#work_type_memo').show();
      } else {
        $(this).closest(".form-row").find(".add_text").addClass("hidden");
        $('#work_type_memo').hide();
      }
    });

    $("#btnPrev").click(function() {
      history.go(-1);
    });

    function formatDate(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0'); // 월은 0부터 시작하므로 +1, 두 자리로 만듭니다.
      const day = String(date.getDate()).padStart(2, '0'); // 일, 두 자리로 만듭니다.
      return `${year}-${month}-${day}`;
    }

    // 공고명 글자수 카운터
    (function() {
      var $input = $('[name="rc_name"]');
      var $count = $('#rc_name_count');
      $input.on('input', function() {
        $count.text($(this).val().length + '/50');
      });
      // 수정 페이지 진입 시 초기값 반영
      $count.text($input.val().length + '/50');
    }());

    $("#btnStep").click(function() {

      if ($("[name='rc_name']").val() == "") {
        commonAlert("공고제목을 입력해주세요.", function() {
          $("[name='rc_name']").focus();
        });
        return;
      }

      if ($("[name='rc_date']").val() == "") {
        const today = new Date();
        // 2개월 후의 날짜 구하기
        const twoMonthsLater = new Date(today);
        twoMonthsLater.setMonth(today.getMonth() + 2);
        $("[name='rc_date']").val(formatDate(today) + ' ~ ' + formatDate(twoMonthsLater));
      }

      if ($("[name='ad_addr']").val() == "") {
        commonAlert("주소를 등록해주세요.", function() {});
        return;
      }

      if ($("[name='rc_addr_doro']").val() == "") {
        commonAlert("주소를 등록해주세요.", function() {});
        return;
      }

      var rc_work_img = "";
      for (var icnt = 0; icnt < $("[name='rc_work[]']:checked").length; icnt++) {
        if (rc_work_img == "") {
          rc_work_img = $("[name='rc_work[]']:checked").eq(icnt).data("img");
        } else {
          rc_work_img += "," + $("[name='rc_work[]']:checked").eq(icnt).data("img");
        }
      }

      $("[name=rc_work_img]").val(rc_work_img);
      $("#ad_detail").val($("[name=rc_addr_detail]").val());

      if ($("[name='rc_work[]']").is(":checked") == false) {
        commonAlert("희망업무구분은 하나이상 선택해주세요.", function() {});
        return;
      }

      if ($("[name='ma_sage']").val() != "" && $("[name='ma_eage']").val() != "" && $("[name='ma_sage']").val() > $("[name='ma_eage']").val()) {
        commonAlert("희망나이는 앞에 설정한 나이가 뒤에 설정한 나이보다 클 수 없습니다.", function() {});
        return;
      }

      if ($("[name='rc_work_type[]']").is(":checked") == false) {
        commonAlert("근무형태를 하나이상 선택해주세요.", function() {});
        return;
      }

      $("[name='rc_am_stime']").val($("[name='rc_am_stime_h']").val() + ":" + $("[name='rc_am_stime_m']").val());
      $("[name='rc_am_etime']").val($("[name='rc_am_etime_h']").val() + ":" + $("[name='rc_am_etime_m']").val());

      $("[name='rc_pm_stime']").val($("[name='rc_pm_stime_h']").val() + ":" + $("[name='rc_pm_stime_m']").val());
      $("[name='rc_pm_etime']").val($("[name='rc_pm_etime_h']").val() + ":" + $("[name='rc_pm_etime_m']").val());

      // 급여유형 검증: rc_price_rep(select) 또는 rc_price_type(hidden)이 선택되어야 함
      var rc_price_rep = $("[name='rc_price_rep']").val();
      var rc_price_type = $("[name='rc_price_type']").val();

      if (!rc_price_rep && !rc_price_type) {
        commonAlert("급여유형을 하나이상 선택해주세요.", function() {});
        return;
      }

      if ($('#mm_comp_num').val() == '') {
        $('#dimwrap,#dimcont').show();
        return;
      }

      var hashTexts = []; // on 클래스를 가진 span의 내용을 저장할 배열 초기화
      var spans = document.querySelectorAll('#initHash span.on'); // on 클래스를 가진 모든 span 선택
      spans.forEach(function(span) {
        hashTexts.push(span.textContent); // 각 span의 텍스트 내용을 배열에 추가
      });
      var hashString = hashTexts.join(', ');


      var inputValues = []; // input 태그의 값들을 저장할 배열 초기화
      var inputs = document.querySelectorAll('#hashbox input[type="text"]'); // hashbox 내의 모든 input[type="text"] 선택
      inputs.forEach(function(input) {
        inputValues.push(input.value); // 각 input의 value를 배열에 추가
      });
      var concatenatedValues = inputValues.join(', ');
      $("[name='rc_hash']").html(hashString + ', ' + concatenatedValues);

      oEditors.getById["rc_desc"].exec("UPDATE_CONTENTS_FIELD", []); // 에디터의 내용이 textarea에 반영되도록 업데이트 명령 실행

      var form = $('#frm_recruit');

      // 선택된 근무형태 확인
      var isAMSelected = $('input[name="rc_work_type[]"][value="주간"]').is(':checked');
      var isPMSelected = $('input[name="rc_work_type[]"][value="야간"]').is(':checked');
      var is2ShiftSelected = $('input[name="rc_work_type[]"][value="2교대"]').is(':checked');

      // 근무형태별 시간값 처리
      // 주간 또는 2교대가 선택된 경우 AM 시간 저장
      if (isAMSelected || is2ShiftSelected) {
        var amStartH = $('select[name="rc_am_stime_h"]').val() || '09';
        var amStartM = $('input[name="rc_am_stime_m"]').val() || '00';
        var amEndH = $('select[name="rc_am_etime_h"]').val() || '18';
        var amEndM = $('input[name="rc_am_etime_m"]').val() || '00';
        $('input[name="rc_am_stime"]').val(amStartH + ':' + amStartM.padStart(2, '0'));
        $('input[name="rc_am_etime"]').val(amEndH + ':' + amEndM.padStart(2, '0'));
      } else {
        // 주간/2교대가 선택되지 않으면 AM 시간 비움
        $('input[name="rc_am_stime"]').val('');
        $('input[name="rc_am_etime"]').val('');
      }

      // 야간 또는 2교대가 선택된 경우 PM 시간 저장
      if (isPMSelected || is2ShiftSelected) {
        var pmStartH = $('select[name="rc_pm_stime_h"]').val() || '21';
        var pmStartM = $('input[name="rc_pm_stime_m"]').val() || '00';
        var pmEndH = $('select[name="rc_pm_etime_h"]').val() || '06';
        var pmEndM = $('input[name="rc_pm_etime_m"]').val() || '00';
        $('input[name="rc_pm_stime"]').val(pmStartH + ':' + pmStartM.padStart(2, '0'));
        $('input[name="rc_pm_etime"]').val(pmEndH + ':' + pmEndM.padStart(2, '0'));
      } else {
        // 야간/2교대가 선택되지 않으면 PM 시간 비움
        $('input[name="rc_pm_stime"]').val('');
        $('input[name="rc_pm_etime"]').val('');
      }

      var params = new FormData(form[0]);

   
      for (var pair of params.entries()) {
        
      }

   
      fileAjaxLoading("/compage_proc/recruit_write", params, function(ret) {
     
        if (ret.code == "00") {
         
          showPreviewInfo(ret.data);
        } else {
        
          commonAlert(ret.msg, function() {});
          return;
        }
      });
    });

    // 근무요일 무관 토글
    $("[name=rc_week_yn]").on('change', function() {
      if ($(this).is(":checked")) {
        $("[name='rc_week[]']").prop("checked", true).trigger("change");
      } else {
        $("[name='rc_week[]']").prop("checked", false).trigger("change");
      }
    });

    // 근무요일 기본값 설정 (월~금)
    (function() {
      var $weekCheckboxes = $("[name='rc_week[]']");
      var hasExistingSelection = $weekCheckboxes.is(":checked");
      var isAllDaysSelected = $("[name='rc_week_yn']").is(":checked");

      if (!hasExistingSelection && !isAllDaysSelected) {
        var weekdayDefaults = ["월", "화", "수", "목", "금"];
        $weekCheckboxes.each(function() {
          var $checkbox = $(this);
          if (weekdayDefaults.indexOf($checkbox.val()) !== -1) {
            $checkbox.prop("checked", true);
          }
        });
      }
    }());


    $(".comma").on("keyup", function() {
      $(this).val($(this).val().replace(/\,/g, '').replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'));
    });

    var maxLimit = 2;

    $('input[name="rc_work[]"]').change(function() {
      var checkedCount = $('input[name="rc_work[]"]:checked').length;

      if (checkedCount > maxLimit) {
        commonAlert("업무구분은 " + maxLimit + "개까지 선택 할 수 있습니다.", function() {});
        $(this).prop('checked', false);
      }
    });

    // recruit_write.php 페이지에서만 btnSave 동작 오버라이드
    if (window.location.pathname.indexOf('/compage/recruit_write') !== -1) {
      $(document).off('click', '#btnSave').on('click', '#btnSave', function() {
        // 사업자명 입력 확인
        if ($("[name=comp_name]").val() == "" || $("[name=comp_name]").val().trim() == "") {
          commonAlert("사업자명을 입력해주세요.", function() {
            $("[name=comp_name]").focus();
          });
          return;
        }

        // 사업자 인증 처리
        fBizAuth(function() {
          var form = $('#frm1');
          var params = new FormData(form[0]);
          fileAjax("/compage_proc/com_biz_update", params, function(ret) {
            if (ret.code == "00") {
              // 팝업 닫기 후 공고 등록 진행 (페이지 새로고침하지 않음)
              $('#dimwrap').hide();
              $('#dimcont').hide();
              $("#btnStep").click();
            } else {
              commonAlert(ret.msg, function() {});
            }
          });
        });
      });
    }

    <?php if ($_GET["gbn"] == "re") { ?>
      //$("[name='rc_date']").datepicker('setDate', new Date());
      //$('.js-datepicker2').datepicker({
      //  dateFormat: 'yyyy-mm-dd', // Sets the date format to year-month-day
      //  minDate: today,        // Sets the minimum date to today
      //  maxDate: twoMonthsLater // Sets the maximum date to two weeks from today
      //});
    <?php } ?>

    var rcHashArray = $("[name='rc_hash']").html().split(',');
    var initYn = false;
    rcHashArray.forEach(function(hashValue) {
      initYn = false;
      if (hashValue.trim() === '') {
        return; // 다음 반복으로 넘어갑니다.
      }
      $('#initHash span').each(function() {
        if ($(this).html().trim() === hashValue.trim()) {
          $(this).addClass('on');
          initYn = true;
        }
      });

      if (initYn === false) {
        var newInput = $('<input/>', {
          type: 'text',
          style: 'min-width: 90px; max-width: 150px; height: 24px; border: none; margin-left: 5px;',
          value: hashValue.trim()
        });

        var newSpan = $('<span/>', {
          style: 'margin-right:10px; border: 1px solid #ccc; height: 28px; display: inline-block; line-height: 26px; border-radius: 25px; padding: 0 10px;'
        }).append('#').append(newInput).append('<span style="margin-left: 5px; cursor: pointer;" onclick="fHashDel(this);">ⓧ</span>');

        $('#hashbox').prepend(newSpan);
      }

    });

    setTimeout(function() {
      const today = new Date(); // 오늘 날짜를 가져옵니다.
      const twoMonthsLater = new Date(today); // 오늘 날짜를 기반으로 2개월 후의 날짜를 계산합니다.
      twoMonthsLater.setMonth(today.getMonth() + 2);
      // formatDate 함수를 사용하여 YYYY.MM.DD 형식으로 변환
      const formattedStart = formatDate(today).replace(/-/g, '.');
      const formattedEnd = formatDate(twoMonthsLater).replace(/-/g, '.');
      $('.js-datepicker2').val('[모집시작일] ' + formattedStart + ' ~ [모집종료일] ' + formattedEnd);
      $('.js-datepicker').val(formatDate(today));
    }, 500);

    fDelbtnAdd(0);
    fDelbtnAdd(1);
    fDelbtnAdd(2);
    fDelbtnAdd(3);
    fDelbtnAdd(4);
    fDelbtnAdd(5);

    // 페이지 로드 시 초기값 동기화
    syncTimeHiddenFields();

    // 숨겨진 필드 동기화 함수
    function syncTimeHiddenFields() {
      var amStartH = $('select[name="rc_am_stime_h"]').val() || '09';
      var amStartM = $('input[name="rc_am_stime_m"]').val() || '00';
      $('input[name="rc_am_stime"]').val(amStartH + ':' + amStartM.padStart(2, '0'));

      var amEndH = $('select[name="rc_am_etime_h"]').val() || '18';
      var amEndM = $('input[name="rc_am_etime_m"]').val() || '00';
      $('input[name="rc_am_etime"]').val(amEndH + ':' + amEndM.padStart(2, '0'));

      var pmStartH = $('select[name="rc_pm_stime_h"]').val() || '21';
      var pmStartM = $('input[name="rc_pm_stime_m"]').val() || '00';
      $('input[name="rc_pm_stime"]').val(pmStartH + ':' + pmStartM.padStart(2, '0'));

      var pmEndH = $('select[name="rc_pm_etime_h"]').val() || '06';
      var pmEndM = $('input[name="rc_pm_etime_m"]').val() || '00';
      $('input[name="rc_pm_etime"]').val(pmEndH + ':' + pmEndM.padStart(2, '0'));
    }

    // 특정 필드에 대한 선택자를 사용하여 모든 필드에 대해 이벤트를 바인딩
    $('[name^="rc_am_stime_m"], [name^="rc_am_etime_m"], [name^="rc_pm_stime_m"], [name^="rc_pm_etime_m"]').on('input', function() {
      // 숫자만 입력되도록 처리
      $(this).val($(this).val().replace(/[^0-9]/g, ''));

      // 입력된 값이 0에서 59 범위를 벗어난 경우 조정
      let value = parseInt($(this).val(), 10);
      if (!isNaN(value)) {
        if (value < 0) {
          $(this).val(0);
        } else if (value > 59) {
          $(this).val(59);
        }
      }

      // 분 입력 후 숨겨진 필드 동기화
      syncTimeHiddenFields();
    });

    //앞에 시간 선택시 8시간 뒤로 뒷시간 설정
    $('#rc_am_stime_h').change(function() {
      var startTime = parseInt($(this).val(), 10);
      var endTime = (startTime + 8) % 24; // 24시간 이후는 다음날로 넘어가므로 나머지 연산자 사용
      $('#rc_am_etime_h').val(endTime.toString().padStart(2, '0')); // 값 설정, 1자리 숫자 앞에 0 추가

      // 시간 선택 후 숨겨진 필드 동기화
      syncTimeHiddenFields();
    });
    $('#rc_pm_stime_h').change(function() {
      var startTime = parseInt($(this).val(), 10);
      var endTime = (startTime + 8) % 24; // 24시간 이후는 다음날로 넘어가므로 나머지 연산자 사용
      $('#rc_pm_etime_h').val(endTime.toString().padStart(2, '0')); // 값 설정, 1자리 숫자 앞에 0 추가

      // 시간 선택 후 숨겨진 필드 동기화
      syncTimeHiddenFields();
    });

    // 퇴근 시간 선택 시에도 동기화
    $('select[name="rc_am_etime_h"]').change(function() {
      syncTimeHiddenFields();
    });
    $('select[name="rc_pm_etime_h"]').change(function() {
      syncTimeHiddenFields();
    });


    // SmartEditor2 에디터 생성 및 폰트 크기 기본값을 14pt로 설정
    var oEditors = [];
    nhn.husky.EZCreator.createInIFrame({
      oAppRef: oEditors, // SmartEditor2 에디터를 조작하기 위해 저장해두는 변수
      elPlaceHolder: "rc_desc",
      sSkinURI: "/resource/shop/web/smarteditor2/SmartEditor2Skin_ko_KR.html", //에디터 디자인 파일 위치
      fCreator: "createSEditor2", // 에디터 생성 함수
      bRichMode: true, // 리치텍스트 모드 활성화
      bAutoLoad: true, // 자동 로드
      nHeight: 600, // 초기 높이 600px
      htParams: {
        bUseUniversalFont: true,
        fOnBeforeUnload: function() {}
      },
      fOnAppLoad: function() { // 에디터가 로드된 후 실행되는 콜백 함수
        setTimeout(function() {
          try {
            var oEditor = oEditors.getById["rc_desc"];
            if (!oEditor) return;

            // 에디터 body의 기본 폰트 크기를 14pt로 설정
            var oWYSIWYGDocument = oEditor.getWYSIWYGDocument();
            if (oWYSIWYGDocument && oWYSIWYGDocument.body) {
              oWYSIWYGDocument.body.style.fontSize = "14pt";
            }

            // 폰트 크기 플러그인 찾아서 기본값 설정
            var oApp = oEditor.oApp;
            if (oApp && oApp.aPlugins) {
              for (var i = 0; i < oApp.aPlugins.length; i++) {
                var oPlugin = oApp.aPlugins[i];
                if (oPlugin && oPlugin.sDefaultText !== undefined && oPlugin.elFontSizeLabel) {
                  oPlugin.sDefaultText = "14pt";
                  oPlugin.elFontSizeLabel.innerHTML = "14pt";

                  // 14pt 항목 활성화
                  if (oPlugin.aLIFontSizes && oPlugin.aLIFontSizes.length > 0) {
                    for (var j = 0; j < oPlugin.aLIFontSizes.length; j++) {
                      var elLi = oPlugin.aLIFontSizes[j];
                      if (!elLi) continue;

                      var elSpan = elLi.querySelector("span span");
                      if (elSpan && elSpan.style && elSpan.style.fontSize === "14pt") {
                        if (typeof oPlugin._clearFontSizeSelection === "function") {
                          oPlugin._clearFontSizeSelection();
                        }
                        elLi.className = (elLi.className + " active").trim();
                        break;
                      }
                    }
                  }
                  break;
                }
              }
                }
              } catch (e) {
            console.error("폰트 크기 기본값 설정 중 오류:", e);
          }
        }, 200);
      }
    });

    setTimeout(function() {
      $('iframe').css('background-color', '#fff');
      $('iframe').css('border-right', '1px solid #ccc');
    }, 500);

    // 사진 업로드 기능
    $('#uploadPhotoBtn').on('click', function(e) {
      e.preventDefault();
      
      $('#photoFileInput').click();
    });

    $('#photoFileInput').on('change', function() {
      if (this.files && this.files.length > 0) {
        var files = Array.from(this.files);
        var uploadCount = 0;

        // 여러 파일을 순회하면서 각각 업로드
        files.forEach(function(file) {
          var formData = new FormData();
          formData.append('Filedata', file);

          $.ajax({
            url: '/compage_proc/se2_image_upload',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              uploadCount++;
              if (response.success === 1) {
                var oEditor = oEditors.getById["rc_desc"];
                if (oEditor) {
                  var html = '<img src="' + response.url + '" alt="' + response.name + '" style="max-width:100%; height:auto; margin: 10px 0;" />';
                  oEditor.exec("PASTE_HTML", [html]);
                } else {
                  // smarteditor2가 없으면 textarea에 URL 표시
                  var currentText = $('#rc_desc').val();
                  $('#rc_desc').val(currentText + '\n[이미지]: ' + response.url);
                }
              } else {
                alert(file.name + ' 업로드에 실패했습니다.');
              }

              // 모든 파일 업로드 완료 시 input 초기화
              if (uploadCount === files.length) {
                $('#photoFileInput').val('');
              }
            },
            error: function(xhr, status, error) {
              uploadCount++;
              console.error('업로드 오류:', error);
              alert(file.name + ' 업로드 중 오류가 발생했습니다.');

              if (uploadCount === files.length) {
                $('#photoFileInput').val('');
              }
            }
          });
        });
      }
    });

    // 네 개의 입력 필드를 선택하여 순회
    $('input[name="rc_am_stime_m"], input[name="rc_am_etime_m"], input[name="rc_pm_stime_m"], input[name="rc_pm_etime_m"]').each(function() {
      // 값이 빈 문자열이면 "00"을 설정
      if ($(this).val() === "") {
        $(this).val("00");
      }
    });
  }

  // 달력의 월/년 표시를 커스터마이징하는 전역 함수
  // 모집일 달력과 근무시작일 달력 모두에서 사용됨
  function chgMonth(instance) {
    var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
      month = Number(instance.currentMonth) + 1,
      year = instance.currentYear;
    $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
  }

  // 페이지 로드 시 모든 초기화 함수 실행
  $(document).ready(function() {
    initLawPopup();
    initAddressArea();
    initFormValidation();
  });

  // 이미지 삭제 버튼 추가 함수
  function fDelbtnAdd(arg) {
    var imgHtml = '<a href="javascript:if(confirm(\'이미지를 삭제하시겠습니까?\')){ $(\'#rc_img' + arg + '\').val(\'\'); $(\'#rc_img' + arg + '_ori\').val(\'\'); $(\'#prev' + arg + ' img\').hide(); } " id="delimgbtn' + arg + '" style="display:none; position: absolute; top: 5px; right: 5px; overflow: hidden; border-radius: 11px; width: 23px; height: 24px; z-index: 10;">';
    imgHtml += '<span style="font-size: 27px; margin-top: -5px; margin-left: -2px; display: block; background-color: #ccc;">ⓧ</span>';
    imgHtml += '</a>';
    setTimeout(function() {
      if ($('#prev' + arg + ' img').length > 0) {
        $('#prev' + arg).append(imgHtml);
      }
    }, 500);
  }
  // 미리보기 정보 표시 함수
  function showPreviewInfo(info) {

    var str_rc_work_img = "";
    var str_rc_week = "";
    var str_img = "";
    var str_price = "";
    var str_time = "";
    var str_hash = "";
    var str_life_type = "";

    if (info.rc_week) {
      for (var icnt = 0; icnt < info.rc_week.length; icnt++) {
        str_rc_week += "<li aria-hidden='false'>" + info.rc_week[icnt] + "</li>";
      }
    }

    if (info.rc_img1) {
      str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='" + info.rc_img1 + "' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    }

    if (info.rc_img2) {
      str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='" + info.rc_img2 + "' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    }

    if (info.rc_img3) {
      str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='" + info.rc_img3 + "' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    }

    if (info.rc_img4) {
      str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='" + info.rc_img4 + "' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    }

    if (info.rc_img5) {
      str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='" + info.rc_img5 + "' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    }

    str_price += '<em class="currency">' + $('[name="rc_price_pay"]').val() + '</em>';

    if (info.rc_price_type == "급여협의") {
      str_price = "급여협의";
    }

    // rc_work_type null 체크 추가
    if (info.rc_work_type && info.rc_work_type.indexOf('2교대') > -1 || (info.rc_work_type && info.rc_work_type.indexOf('주간') > -1 && info.rc_work_type.indexOf('야간') > -1)) {
      if (info.rc_am_stime != '' || info.rc_am_etime != '') {
        str_time += info.rc_am_stime + '~' + info.rc_am_etime + '(주간)';
      } else {
        str_time += '주간';
      }
      if (info.rc_pm_stime != '' || info.rc_pm_etime != '') {
        str_time += '<br/>' + info.rc_pm_stime + '~' + info.rc_pm_etime + '(야간)';
      } else {
        str_time += ',' + ' 야간';
      }
    } else if (info.rc_work_type && info.rc_work_type.indexOf('주간') > -1 && info.rc_work_type.indexOf('2교대') == -1) {
      if (info.rc_am_stime != '' || info.rc_am_etime != '') {
        str_time += info.rc_am_stime + '~' + info.rc_am_etime + '(주간)';
      } else {
        str_time += '주간';
      }
    } else if (info.rc_work_type && info.rc_work_type.indexOf('야간') > -1 && info.rc_work_type.indexOf('2교대') == -1) {
      if (info.rc_pm_stime != '' || info.rc_pm_etime != '') {
        str_time += info.rc_pm_stime + '~' + info.rc_pm_etime + '(야간)';
      } else {
        str_time += '야간';
      }
    }

    // rc_hash null 체크 추가
    if (info.rc_hash) {
      for (var icnt = 0; icnt < info.rc_hash.split(",").length; icnt++) {
        if (info.rc_hash.split(",")[icnt].trim() != "") {
          str_hash += '<p class="tag">' + info.rc_hash.split(",")[icnt].trim() + '</p>';
        }
      }
    }

    if (info.rc_life_type) {
      for (var icnt = 0; icnt < info.rc_life_type.length; icnt++) {
        str_life_type += '<li class="item" data-tmp=""><p class="benefit-label benefit1-1-v2">' + info.rc_life_type[icnt] + '</p></li>';
      }
    }
    if (!info.rc_work_dt) {
      info.rc_work_dt = '근무일무관';
      info.rc_work_dt_yoil = '-';
    }
    if (info.rc_sex == '') {
      info.rc_sex = '무관';
    }
    if (info.rc_nation == '') {
      info.rc_nation = '무관';
    }
    var ageStr = '';
    if (info.rc_sage == '' && info.rc_eage == '') {
      ageStr = '무관';
    } else {
      ageStr = info.rc_sage + '세이상~' + info.rc_eage + '세이하';
    }
    if (str_rc_week == '') {
      str_rc_week = '무관';
    }

    var preview_life = info.rc_life_type_tag;
    preview_life = preview_life.replaceAll("benefit1-1", "benefit1-1-v2");
    preview_life = preview_life.replaceAll("benefit1-2", "benefit1-2-v2");
    preview_life = preview_life.replaceAll("benefit1-3", "benefit1-3-v2");
    preview_life = preview_life.replaceAll("benefit1-4", "benefit1-4-v2");
    preview_life = preview_life.replaceAll("benefit2-1", "benefit2-1-v2");
    preview_life = preview_life.replaceAll("benefit2-2", "benefit2-2-v2");
    preview_life = preview_life.replaceAll("benefit2-3", "benefit2-3-v2");
    preview_life = preview_life.replaceAll("benefit3-1", "benefit3-1-v2");
    preview_life = preview_life.replaceAll("benefit3-2", "benefit3-2-v2");
    preview_life = preview_life.replaceAll("benefit3-3", "benefit3-3-v2");
    preview_life = preview_life.replaceAll("benefit3-4", "benefit3-4-v2");
    preview_life = preview_life.replaceAll("benefit3-5", "benefit3-5-v2");
    preview_life = preview_life.replaceAll("benefit3-6", "benefit3-6-v2");
    preview_life = preview_life.replaceAll("benefit4-1", "benefit4-1-v2");
    preview_life = preview_life.replaceAll("benefit4-2", "benefit4-2-v2");
    preview_life = preview_life.replaceAll("benefit4-3", "benefit4-3-v2");
    preview_life = preview_life.replaceAll("benefit4-4", "benefit4-4-v2");
    preview_life = preview_life.replaceAll("benefit4-5", "benefit4-5-v2");
    preview_life = preview_life.replaceAll("benefit4-6", "benefit4-6-v2");
    preview_life = preview_life.replaceAll("benefit4-7", "benefit4-7-v2");

    var preview_desc = info.rc_desc;
    preview_desc = preview_desc.replaceAll(/\n/g, "<br>");

    var rc_price_rep_txt = '';
    if ($('[name="rc_price_rep"]') != '급여협의') {
      rc_price_rep_txt = ' (대표급여형태 : ' + $('[name="rc_price_rep"]').val() + ')';
    }
    // 챙용공고 등록 확인 팝업 열기
    openPopup('modal', {
      content: '<div class="regist-info">' +
        '<h5 class="name">' + info.rc_name + '</h5>' +
        '<table>' +
        '<caption><span class="sr-text">채용공고정보</span></caption>' +
        '<colgroup><col><col></colgroup>' +
        '<tbody>' +
        '<tr>' +
        '<td class="label">공개여부</td>' +
        '<td class="value">' + (info.rc_show_yn == 'Y' ? '공개' : '비공개') + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">업무구분</td>' +
        '<td class="value">' +
        info.rc_work_img +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">금액</td>' +
        '<td class="value txt-color2">' +
        str_price +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">급여형태</td>' +
        '<td class="value">' + info.rc_price_type + rc_price_rep_txt + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">근무시간</td>' +
        '<td class="value">' + str_time + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">근무시작일</td>' +
        '<td class="value">' + info.rc_work_dt + '(' + info.rc_work_dt_yoil + ')' + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">희망성별</td>' +
        '<td class="value">' + info.rc_sex + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">희망국적</td>' +
        '<td class="value">' + info.rc_nation + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">희망나이</td>' +
        '<td class="value">' + ageStr + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">근무요일</td>' +
        '<td class="value">' +
        '<ul class="working-day">' +
        str_rc_week +
        '</ul>' +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="value">' +
        '<div class="hashtag">' +
        str_hash +
        '</div>' +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="label">복리후생</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="value">' +
        '<ul class="benefit-group">' +
        preview_life +
        '</ul>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="label" style="padding-top: 34px;">상세업무정보</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="value" style="max-width: 400px;">' +
        preview_desc +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="label" style="padding-top: 34px;">첨부사진</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="value">' +
        str_img +
        '</td>' +
        '</tr>' +
        '</tbody>' +
        '</table>' +
        '</div>',
      firstButton: '정보수정',
      secondButton: ($("[name=rc_idx]").val() != "0" ? '수정완료' : '등록완료'),
      firstCallback: function() {},
      secondCallback: function() {
        console.log("%c=== 등록완료/수정완료 버튼 클릭 ===", "color: #31A182; font-weight: bold;");

        var params = {};
        params["rc_idx"] = $("[name=rc_idx]").val();
        params["mm_comp_num"] = $("[name=mm_comp_num]").val();
        params["mm_comp_num_org"] = $("[name=mm_comp_num_org]").val();
        params["expose_addr1_zipcode"] = $("[name=expose_addr1_zipcode]").val();
        params["expose_addr1_jibun"] = $("[name=expose_addr1_jibun]").val();
        params["expose_addr1_doro_val"] = $("[name=expose_addr1_doro_val]").val();
        params["expose_addr1_addr"] = $("[name=expose_addr1_addr]").val();
        params["expose_addr1_gps_x"] = $("[name=expose_addr1_gps_x]").val();
        params["expose_addr1_gps_y"] = $("[name=expose_addr1_gps_y]").val();
        params["expose_addr2_zipcode"] = $("[name=expose_addr2_zipcode]").val();
        params["expose_addr2_jibun"] = $("[name=expose_addr2_jibun]").val();
        params["expose_addr2_doro_val"] = $("[name=expose_addr2_doro_val]").val();
        params["expose_addr2_addr"] = $("[name=expose_addr2_addr]").val();
        params["expose_addr2_gps_x"] = $("[name=expose_addr2_gps_x]").val();
        params["expose_addr2_gps_y"] = $("[name=expose_addr2_gps_y]").val();

        console.log("전송 파라미터:", params);
        console.log("요청 URL: /compage_proc/recruit_complete");

        comAjax("/compage_proc/recruit_complete", params, function(ret) {
          console.log("서버 응답:", ret);

          if (ret.code == "00") {
            console.log("✅ 저장 성공!");
            location.href = "/compage/recruit_list";
          } else {
            console.warn("❌ 저장 실패:", ret.msg);
            commonAlert(ret.msg, function() {});
            return;
          }
        });

      }
    });
  }

  // 달력 초기화 함수들 (HTML 중간에 분산되어 있던 스크립트를 여기로 통합)

  // 모집일 달력 초기화
  function initRecruitDatePicker() {
    window.addEventListener('DOMContentLoaded', function() {
      if (typeof flatpickr == 'undefined') return false;
      //달력 생성
      <?php
      // 기본 날짜 설정: 재등록이면 오늘부터 2개월, 수정이면 기존 날짜
      if (isset($_GET["gbn"]) && $_GET["gbn"] == "re") {
        $default_dates = '[' . json_encode(date("Y-m-d")) . ', ' . json_encode(date("Y-m-d", strtotime("2 months"))) . ']';
      } else {
        $start_date = !empty($info["rc_sdate"]) && get_date($info["rc_sdate"]) < date("Y-m-d") ? date("Y-m-d") : get_date($info["rc_sdate"]);
        $end_date = get_date($info["rc_edate"]);
        $default_dates = '[' . json_encode($start_date) . ', ' . json_encode($end_date) . ']';
      }
      ?>
      flatpickr('.recruit-range .js-datepicker2', {
        mode: 'range',
        locale: 'ko',
        dateFormat: 'Y-m-d',
        ariaDateFormat: 'Y-m-d',
        altFormat: 'Y.m.d',
        altInput: true,
        defaultDate: <?= $default_dates ?>,
        nextArrow: '<span class="sr-text">다음</span>',
        prevArrow: '<span class="sr-text">이전</span>',
        disableMobile: true,
        minDate: 'today',
        maxDate: new Date().fp_incr(60), //2개월 60일 제한
        onMonthChange: function(selectedDates, dateStr, instance) {
          chgMonth(this);
        },
        onYearChange: function(selectedDates, dateStr, instance) {
          chgMonth(this);
        },
        onDayCreate: function(dObj, dStr, fp, dayElem) {
          var day = new Date(dayElem.getAttribute('aria-label')).getDay();
          if (day == 0) {
            dayElem.classList.add('sun');
          } else if (day == 6) {
            dayElem.classList.add('sat');
          }
        },
        onChange: function(selectedDates, dateStr, instance) {
          // 선택된 날짜를 커스텀 포맷으로 altInput에 표시
          if (selectedDates.length === 2) {
            var startDate = new Date(selectedDates[0]);
            var endDate = new Date(selectedDates[1]);
            var startFormatted = startDate.getFullYear() + '.' +
              String(startDate.getMonth() + 1).padStart(2, '0') + '.' +
              String(startDate.getDate()).padStart(2, '0');
            var endFormatted = endDate.getFullYear() + '.' +
              String(endDate.getMonth() + 1).padStart(2, '0') + '.' +
              String(endDate.getDate()).padStart(2, '0');
            if (this.altInput) {
              this.altInput.value = '[모집시작일] ' + startFormatted + ' ~ [모집종료일] ' + endFormatted;
            }
          }
        },
        onReady: function() {
          chgMonth(this);
          // altInput 스타일 및 초기값 포맷팅
          if (this.altInput) {
            this.altInput.placeholder = '[모집시작일] YYYY.MM.DD ~ [모집종료일] YYYY.MM.DD';
            // 초기값이 있으면 커스텀 포맷으로 표시
            if (this.selectedDates && this.selectedDates.length === 2) {
              var startDate = new Date(this.selectedDates[0]);
              var endDate = new Date(this.selectedDates[1]);
              var startFormatted = startDate.getFullYear() + '.' +
                String(startDate.getMonth() + 1).padStart(2, '0') + '.' +
                String(startDate.getDate()).padStart(2, '0');
              var endFormatted = endDate.getFullYear() + '.' +
                String(endDate.getMonth() + 1).padStart(2, '0') + '.' +
                String(endDate.getDate()).padStart(2, '0');
              this.altInput.value = '[모집시작일] ' + startFormatted + ' ~ [모집종료일] ' + endFormatted;
            }
          }
        }
      });
    });
  }

  // 근무시작일 달력 초기화
  function initStartingDatePicker() {
    window.addEventListener('DOMContentLoaded', function() {
      if (typeof flatpickr == 'undefined') return false;

      //달력 생성
      <?php
      $rc_work_dt_defaultDate = $info["rc_work_dt"];
      if ($_GET["gbn"] == "re") {
        $rc_work_dt_defaultDate = Date("Y-m-d");
      }
      ?>
      flatpickr('.starting-date .js-datepicker', {
        locale: 'ko',
        dateFormat: 'Y-m-d',
        ariaDateFormat: 'Y-m-d',
        altFormat: 'Y.m.d',
        defaultDate: '<?= $rc_work_dt_defaultDate ?>',
        altInput: true,
        nextArrow: '<span class="sr-text">다음</span>',
        prevArrow: '<span class="sr-text">이전</span>',
        disableMobile: true,
        position: 'bottom auto',
        monthSelectorType: 'select', // 월 선택 활성화
        //static: true, // 드롭다운 대신 정적 셀렉터
        yearSelectorType: 'select',
        onMonthChange: function(selectedDates, dateStr, instance) {
          chgMonth(this);
        },
        onDayCreate: function(dObj, dStr, fp, dayElem) {
          var day = new Date(dayElem.getAttribute('aria-label')).getDay();
          if (day == 0) {
            dayElem.classList.add('sun');
          } else if (day == 6) {
            dayElem.classList.add('sat');
          }
        },
        onReady: function(selectedDates, dateStr, instance) {
          //오늘버튼 추가
          const todayButton = document.createElement("button");
          todayButton.type = "button";
          todayButton.textContent = "오늘";
          todayButton.className = "flatpickr-today-button";
          todayButton.style = "position: absolute; top: 29px; right: 83px; font-size: 14px; padding: 2px 5px; background-color: #fff; border: 1px solid #ccc; color: #777;";
          todayButton.addEventListener('click', function() {
            const today = new Date();
            instance.setDate(new Date());

            // flatpickr 인터페이스 내 연도 및 월을 오늘 날짜로 업데이트
            instance.currentYear = today.getFullYear();
            instance.currentMonth = today.getMonth();
            instance.redraw(); // 달력 갱신

            $(this).parent().find('.month').html((today.getMonth() + 1) + '<small>월</small>');
            $(this).parent().find('.year').html(today.getFullYear());
          });
          instance.calendarContainer.appendChild(todayButton);

          chgMonth(this);
        }
      });
    });
  }

  // 웹 버전 공고노출지역 삭제 버튼
  $(document).on("click", ".js-clear-expose-addr-web", function(e) {
    e.preventDefault();
    var index = $(this).data("index");
    var inputId = "#expose_addr" + index + "_doro";
    var $input = $(inputId);
    var $btn = $(this);

    // 숨겨진 필드 초기화
    $("input[name='expose_addr" + index + "_zipcode']").val("");
    $("input[name='expose_addr" + index + "_jibun']").val("");
    $("input[name='expose_addr" + index + "_doro_val']").val("");
    $("input[name='expose_addr" + index + "_addr']").val("");
    $("input[name='expose_addr" + index + "_gps_x']").val("");
    $("input[name='expose_addr" + index + "_gps_y']").val("");

    // input 필드 초기화
    $input.val("");

    // 버튼 숨기기
    $btn.hide();
  });

  // 공고노출지역 input에 값이 입력되었을 때 삭제 버튼 표시
  $(document).on("change", "#expose_addr1_doro, #expose_addr2_doro", function() {
    var index = $(this).attr("id").match(/\d+/)[0]; // id에서 숫자 추출 id="expose_addr1_doro" -> index = 1
    var $btn = $("button[data-index='" + index + "']");

    if ($(this).val().trim() !== "") {
      $btn.show();
    } else {
      $btn.hide();
    }
  });

  // 달력 초기화 함수 호출 추가
  $(document).ready(function() {
    initRecruitDatePicker();
    initStartingDatePicker();

    // 공고노출지역 팝업 설정
    window.__addrPopupPageType = 'recruit_write';
  });
</script>

<?php include_once(get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/web/_layout/addr_popup2025_new_web.php'); ?>