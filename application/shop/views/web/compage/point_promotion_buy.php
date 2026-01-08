<style>
  /* 광고구매 카드 스타일 - 기존 스타일 오버라이드 */
  .promo-list {
    margin: 0;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    gap: 20px;
    justify-content: flex-start;
    align-items: stretch;
  }

  .promo-list:after {
    display: none;
  }

  .promo-list li {
    width: 340px;
    margin: 0;
    float: none;
    flex: 0 0 auto;
  }

  .promo-card {
    width: 340px;
    height: 200px;
    padding: 0;
    border: 3px solid transparent;
    /* 기본적으로 투명한 border */
    border-radius: 15px;
    position: relative;
    display: flex;
    flex-direction: column;
    min-height: 200px;
    cursor: pointer;
    /* 클릭 가능한 커서 */
    transition: transform 0.2s ease, border-color 0.3s ease;
    /* 호버 효과와 border 색상 변화를 위한 트랜지션 */
  }

  /* 클릭된 카드에만 border 표시 */
  .promo-card.active {
    border: 3px solid #57CCAF;
  }

  .promo-card:hover {
    transform: translateY(-2px);
    /* 호버 시 살짝 위로 이동 */
  }

  /* 두 번째 카드에만 빨간 세모 강조표시 */
  .promo-list li:nth-child(2) .promo-card::before {
    content: '';
    position: absolute;
    top: -1px;
    left: -1px;
    width: 0;
    height: 0;
    border-left: 15px solid #ff0000;
    border-top: 15px solid #ff0000;
    border-right: 15px solid transparent;
    border-bottom: 15px solid transparent;
    border-top-left-radius: 13px;
    z-index: 10;
  }

  .promo-list li:nth-child(2) .promo-card::after {
    content: '';
    position: absolute;
    bottom: -1px;
    right: -1px;
    width: 0;
    height: 0;
    border-left: 15px solid transparent;
    border-top: 15px solid transparent;
    border-right: 15px solid #ff0000;
    border-bottom: 15px solid #ff0000;
    border-bottom-right-radius: 13px;
    z-index: 10;
  }



  /* 아이콘 컨테이너 - 기존 위치 그대로 유지 */
  .promo-card .card-body .icon-container {
    display: block;
    position: relative;
    top: -0.75em;
    z-index: 1;
  }

  /* BEST 배지 스타일 */
  .promo-card .card-body .best-badge {
    color: #ff0000;
    font-size: 0.75em;
    font-weight: bold;
    position: absolute;
    left: 0;
    top: 0;
    z-index: 2;
  }

  /* 아이콘 공통 스타일 */
  .promo-card .card-body .icon-container img {
    width: 1.5em;
    height: 1.5em;
    flex-shrink: 0;
  }

  /* 첫 번째 아이콘 위치 - 기존 234px 위치 유지 */
  .promo-card .card-body .icon-container .first-img {
    position: absolute;
    left: 14.625em;
    /* 234px를 em으로 변환 */
  }

  /* 두 번째 아이콘 위치 - 기존 264px 위치 유지 */
  .promo-card .card-body .icon-container .pc-mobile-img {
    position: absolute;
    left: 16.5em;
    /* 264px를 em으로 변환 */
  }

  .promo-card .card-body {
    padding: 20px 30px;
    border-radius: 10px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: auto;
    background: #fff;
  }

  .promo-card .card-body:hover {
    background-color: #effffc;
  }

  .promo-card .card-body .title {
    margin: 0 0 8px 0;
    font-size: 16px;
    line-height: 1.3;
    height: auto;
  }

  .promo-card .card-body .title:first-child {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 5px;
  }

  .promo-card .card-body .required-point {
    margin: 5px 0;
    font-size: 20px;
    font-weight: 500;
    color: #1B9D7D;
  }

  .promo-card .card-body .required-point em {
    font-style: normal;
  }

  /* 제목의 굵은 텍스트 폰트 크기 상향 */
  .promo-card .card-body .title strong {
    font-size: 20px;
  }

  .promo-card .card-footer {
    position: absolute;
    bottom: 15px;
    right: 20px;
    width: auto;
    height: auto;
    top: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border-radius: 0;
  }

  .promo-card .card-footer .btn-round {
    width: 80px;
    height: 40px;
    line-height: 2;
    font-size: 14px;
  }

  .line {
    margin: 20px 0;
    border: 1px solid rgb(12, 12, 12);
    width: 101%;
  }

  /* adinfo1, adinfo2, adinfo3, adinfo4 공통 스타일 - 2박스 레이아웃 */
  .adinfo1,
  .adinfo2,
  .adinfo3,
  .adinfo4 {
    display: none;
    margin-top: 50px;
    width: 100%;
    max-width: 1400px;
    margin: 50px auto 0;
    position: relative;
    overflow: hidden;
  }

  /* adinfo2가 표시될 때 블록 레이아웃 적용 */
  .adinfo2.show {
    display: block;
  }

  /* Clearfix for float layout */
  .adinfo1::after,
  .adinfo2::after,
  .adinfo3::after,
  .adinfo4::after {
    content: "";
    display: table;
    clear: both;
  }

  /* adinfo 내부 박스들 스타일 - 2박스 레이아웃 */
  .adinfo1 .adbox1,
  .adinfo2 .adbox1,
  .adinfo3 .adbox1,
  .adinfo4 .adbox1 {
    float: left;
    width: 950px;
    height: 1000px;
    margin-bottom: 50px;
    border-radius: 15px 0 0 15px;
    position: relative;
    overflow: hidden;
    box-sizing: border-box;
  }



  .adinfo1 .adbox3,
  .adinfo2 .adbox3,
  .adinfo3 .adbox3,
  .adinfo4 .adbox3 {
    float: left;
    width: calc(100% - 950px);
    height: 1000px;
    margin-bottom: 50px;
    border-radius: 0 15px 15px 0;
    box-sizing: border-box;
  }

  /* adinfo1, adinfo3, adinfo4의 이미지 컨테이너 스타일 */
  .adinfo1 .image-container,
  .adinfo3 .image-container,
  .adinfo4 .image-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    padding: 20px;
  }

  .adinfo1 .image-container img,
  .adinfo3 .image-container img,
  .adinfo4 .image-container img {
    width: 100%;
    height: auto;
    max-height: 100%;
    object-fit: cover;
  }

  /* Swiper 컨테이너 스타일 */
  .adinfo2 .swiper-container {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
  }

  .adinfo2 .swiper-slide {
    display: flex;
    align-items: center;
    justify-content: center;
    /* background-color: #ededed; */
  }

  .adinfo2 .swiper-slide img {
    width: 100%;
    height: 100%;
    max-height: 100%;
    object-fit: contain;
  }





  /* Swiper 네비게이션 버튼 스타일 - 비활성화 */
  /*
  .adinfo2 .swiper-button-next,
  .adinfo2 .swiper-button-prev {
    color: #1B9D7D;
    width: 44px;
    height: 44px;
    margin-top: -22px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  .adinfo2 .swiper-button-next:hover,
  .adinfo2 .swiper-button-prev:hover {
    background: rgba(255, 255, 255, 1);
    transform: scale(1.1);
  }

  .adinfo2 .swiper-button-next:after,
  .adinfo2 .swiper-button-prev:after {
    font-size: 18px;
    font-weight: bold;
  }

  .adinfo2 .swiper-button-next {
    right: 20px;
  }

  .adinfo2 .swiper-button-prev {
    left: 20px;
  }
  */

  /* Swiper 페이지네이션 스타일 */
  .adinfo2 .swiper-pagination {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
  }

  .adinfo2 .swiper-pagination-bullet {
    width: 12px;
    height: 12px;
    background: rgba(26, 25, 25, 0.5);
    border-radius: 50%;
    margin: 0 6px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .adinfo2 .swiper-pagination-bullet-active {
    background: #1B9D7D;
    transform: scale(1.2);
  }

  .adinfo2 .swiper-pagination-bullet:hover {
    background: #1B9D7D
  }

  /* adbox3 내부 요소들 크기 조정 */
  .adinfo1 .adbox3 .adinfo_tab,
  .adinfo2 .adbox3 .adinfo_tab,
  .adinfo3 .adbox3 .adinfo_tab,
  .adinfo4 .adbox3 .adinfo_tab {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    border-collapse: collapse;
  }

  .adinfo1 .adbox3 .adinfo_tab td span,
  .adinfo2 .adbox3 .adinfo_tab td span,
  .adinfo3 .adbox3 .adinfo_tab td span,
  .adinfo4 .adbox3 .adinfo_tab td span {
    color: #fff;
    background-color: #57ccaf;
    border-radius: 20px;
    width: 4.56em;
    display: block;
    margin: 0 auto;
    height: 1.875em;
    line-height: 1.9em;
    font-size: 1rem;
    text-align: center;
  }






  .adinfo1 .adbox3 .adinfo_tab td.adusepoint,
  .adinfo2 .adbox3 .adinfo_tab td.adusepoint,
  .adinfo3 .adbox3 .adinfo_tab td.adusepoint,
  .adinfo4 .adbox3 .adinfo_tab td.adusepoint {
    color: #57ccaf;
    font-size: 1rem;
    font-weight: bold;
  }

  /* adbox3 내부 폼 요소들 크기 조정 */
  .adinfo1 .adbox3 .form-container,
  .adinfo2 .adbox3 .form-container,
  .adinfo3 .adbox3 .form-container,
  .adinfo4 .adbox3 .form-container {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 48px;
    margin: 0 10px 176px 50px;
  }

  .adinfo3 .adbox3 .form-container {
    margin: 40px 10px 200px 50px;
    /* adinfo3 전용 마진 조정 - 아래: 200px */
  }

  .adinfo4 .adbox3 .form-container {
    margin: 50px 10px 200px 38px;
    /* adinfo4 전용 마진 조정 - 위: 50px, 아래: 200px, 왼쪽: 38px */
  }

  .adinfo1 .adbox3 .form-container select,
  .adinfo1 .adbox3 .form-container input,
  .adinfo2 .adbox3 .form-container select,
  .adinfo2 .adbox3 .form-container input,
  .adinfo3 .adbox3 .form-container select,
  .adinfo3 .adbox3 .form-container input,
  .adinfo4 .adbox3 .form-container select,
  .adinfo4 .adbox3 .form-container input {
    width: 100%;
    height: 32px;
    border: 1px solid #57ccaf;
    border-radius: 4px;
    padding: 0 8px;
    font-size: 14px;
  }

  .adinfo1 .adbox3 .form-container .form-label,
  .adinfo2 .adbox3 .form-container .form-label,
  .adinfo3 .adbox3 .form-container .form-label,
  .adinfo4 .adbox3 .form-container .form-label {
    font-size: 1rem;
    margin-bottom: 0;
  }

  /* 포인트 정보 박스 스타일 */
  .adinfo1 .adbox3 .point-info,
  .adinfo2 .adbox3 .point-info,
  .adinfo3 .adbox3 .point-info,
  .adinfo4 .adbox3 .point-info {
    width: 100%;
    max-width: 400px;
    height: 60px;
    background-color: #1B9D7D;
    margin: 0 auto;
    color: #fff;

    padding: 15px;
    border-radius: 8px;
  }

  .adinfo1 .adbox3 .point-info div,
  .adinfo2 .adbox3 .point-info div,
  .adinfo3 .adbox3 .point-info div,
  .adinfo4 .adbox3 .point-info div {
    margin: 5px 0;
  }

  .adinfo1 .adbox3 .point-info .total-point,
  .adinfo2 .adbox3 .point-info .total-point,
  .adinfo3 .adbox3 .point-info .total-point,
  .adinfo4 .adbox3 .point-info .total-point {
    font-size: 16px;
    font-weight: bold;

  }

  .adinfo1 .adbox3 .point-info .total-point span:first-child,
  .adinfo2 .adbox3 .point-info .total-point span:first-child,
  .adinfo3 .adbox3 .point-info .total-point span:first-child,
  .adinfo4 .adbox3 .point-info .total-point span:first-child {
    padding-left: 16px;
  }

  .adinfo1 .adbox3 .point-info .total-point span:last-child,
  .adinfo2 .adbox3 .point-info .total-point span:last-child,
  .adinfo3 .adbox3 .point-info .total-point span:last-child,
  .adinfo4 .adbox3 .point-info .total-point span:last-child {
    padding-left: 211px;
  }

  /* 버튼 컨테이너 스타일 */
  .adinfo1 .adbox3 .button-container,
  .adinfo2 .adbox3 .button-container,
  .adinfo3 .adbox3 .button-container,
  .adinfo4 .adbox3 .button-container {
    width: 100%;
    max-width: 400px;
    height: 60px;
    margin: 0 75px 0 131px;
    text-align: right;
    padding: 15px;
  }

  .adinfo1 .adbox3 .button-container a,
  .adinfo2 .adbox3 .button-container a,
  .adinfo3 .adbox3 .button-container a,
  .adinfo4 .adbox3 .button-container a {
    display: inline-block;
    color: #1B9D7D;
    border: 1px solid #1B9D7D;
    padding: 8px 16px;

    margin-right: 15px;
    text-decoration: none;
    font-size: 1rem;
    width: 7.5em;
    height: 2.5em;
    font-weight: bold;
    text-align: center;

  }

  .adinfo1 .adbox3 .button-container .btn-round,
  .adinfo2 .adbox3 .button-container .btn-round,
  .adinfo3 .adbox3 .button-container .btn-round,
  .adinfo4 .adbox3 .button-container .btn-round {
    background-color: #1B9D7D;
    color: #fff;
    padding: 8px 16px;
    text-decoration: none;
    line-height: 1.4;

  }

  /* 추가 폼 스타일 */
  .adinfo1 .adbox3 .form-group,
  .adinfo2 .adbox3 .form-group,
  .adinfo3 .adbox3 .form-group,
  .adinfo4 .adbox3 .form-group {
    margin-bottom: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .adinfo1 .adbox3 .input-group,
  .adinfo2 .adbox3 .input-group,
  .adinfo3 .adbox3 .input-group,
  .adinfo4 .adbox3 .input-group {
    position: relative;
    width: 100%;
  }

  .adinfo1 .adbox3 .input-group .btn-search,
  .adinfo2 .adbox3 .input-group .btn-search,
  .adinfo3 .adbox3 .input-group .btn-search,
  .adinfo4 .adbox3 .input-group .btn-search {
    position: absolute;
    top: 0;
    right: 0;
    width: 32px;
    height: 32px;
    border: 0;
    border-radius: 4px;
    background: #1B9D7D url(https://nowjob.co.kr/resource/shop/web/img/ic_search.svg) no-repeat;
    background-size: 15px auto;
    background-position: center;
    cursor: pointer;
  }

  .adinfo1 .adbox3 .datetime-group,
  .adinfo2 .adbox3 .datetime-group,
  .adinfo3 .adbox3 .datetime-group,
  .adinfo4 .adbox3 .datetime-group {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 0;
  }

  .adinfo1 .adbox3 .datetime-group input,
  .adinfo1 .adbox3 .datetime-group select,
  .adinfo2 .adbox3 .datetime-group input,
  .adinfo2 .adbox3 .datetime-group select,
  .adinfo3 .adbox3 .datetime-group input,
  .adinfo3 .adbox3 .datetime-group select,
  .adinfo4 .adbox3 .datetime-group input,
  .adinfo4 .adbox3 .datetime-group select {
    flex: 1;
    min-width: 0;
  }

  .adinfo1 .adbox3 .datetime-group .unit,
  .adinfo2 .adbox3 .datetime-group .unit,
  .adinfo3 .adbox3 .datetime-group .unit,
  .adinfo4 .adbox3 .datetime-group .unit {
    font-size: 14px;
    font-weight: bold;
    white-space: nowrap;
  }

  .adinfo1 .adbox3 .duration-group,
  .adinfo2 .adbox3 .duration-group,
  .adinfo3 .adbox3 .duration-group,
  .adinfo4 .adbox3 .duration-group {
    display: flex;
    align-items: center;

    margin-top: 0;
    justify-content: space-evenly;

  }

  .adinfo1 .adbox3 .duration-group select,
  .adinfo2 .adbox3 .duration-group select,
  .adinfo3 .adbox3 .duration-group select,
  .adinfo4 .adbox3 .duration-group select {
    flex: 0 0 auto;
    width: 80px;
  }

  .adinfo1 .adbox3 .duration-group span,
  .adinfo2 .adbox3 .duration-group span,
  .adinfo3 .adbox3 .duration-group span,
  .adinfo4 .adbox3 .duration-group span {

    font-size: 1rem;
    color: #666;
    font-weight: bold;


  }

  .adinfo1 .adbox3 .duration-group .plus-day,
  .adinfo2 .adbox3 .duration-group .plus-day,
  .adinfo3 .adbox3 .duration-group .plus-day,
  .adinfo4 .adbox3 .duration-group .plus-day {
    color: #1B9D7D;
    font-weight: bold;
    font-size: 1rem;
    margin-right: 52px;
  }

  .adinfo1 .adbox3 .table-container,
  .adinfo2 .adbox3 .table-container,
  .adinfo3 .adbox3 .table-container,
  .adinfo4 .adbox3 .table-container {
    margin: 0;
  }

  .adinfo1 .adbox3 .adinfo_tab th,
  .adinfo1 .adbox3 .adinfo_tab td,
  .adinfo2 .adbox3 .adinfo_tab th,
  .adinfo2 .adbox3 .adinfo_tab td,
  .adinfo3 .adbox3 .adinfo_tab th,
  .adinfo3 .adbox3 .adinfo_tab td,
  .adinfo4 .adbox3 .adinfo_tab th,
  .adinfo4 .adbox3 .adinfo_tab td {
    text-align: center;
    padding: 8px;
    font-size: 1rem;
  }



  .adinfo1 .adbox3 .adinfo_tab th,
  .adinfo2 .adbox3 .adinfo_tab th,
  .adinfo3 .adbox3 .adinfo_tab th,
  .adinfo4 .adbox3 .adinfo_tab th {
    background-color: #f8f9fa;
    font-weight: bold;
    border: none;
    border-bottom: 1px solid #57ccaf;
  }

  .adinfo1 .adbox3 .adinfo_tab td,
  .adinfo2 .adbox3 .adinfo_tab td,
  .adinfo3 .adbox3 .adinfo_tab td,
  .adinfo4 .adbox3 .adinfo_tab td {
    border: none;
  }


  .point-info-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin-left: 55px;
  }

  /* adinfo3의 point-info-container 위치 조정 - 아래로 내리기 */
  .adinfo3 .adbox3 .point-info-container {
    position: relative;
    top: -50px;
  }
</style>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab1"; ?>
      <?php $tab_on2 = "menu4"; ?>
      <?php include_once('top_line.php'); ?>
      <div class="tab-panel on">
        <div class="purchage-promo">
          <h2 class="sr-text">광고구매</h3>
            <hr class="line">
            <ul class="promo-list">
              <li>
                <div class="promo-card">
                  <div class="card-body">
                    <div class="icon-container">
                      <img src="/resource/shop/web/img/hotIssueCom_icon.svg" alt="hotIssueCompany-icon" class="first-img">
                      <img src="/resource/shop/web/img/PC_M_icon.svg" alt="pc-mobile-icon" class="pc-mobile-img">
                    </div>
                    <p class="title"><strong>지금뜨는 업체</strong></p>
                    <p class="title">* 홈화면 지금뜨는 업체란에 노출시킵니다.</p>
                    <p class="required-point">차감포인트 : <em>1일 / <?= number_format($point4["cd_val3"]) ?></em>P</p>
                  </div>

                  <!-- 구매팝업템플릿 -->
                  <template id="promo4Temp">
                    <div class="promo-view" style="width: 380px;">
                      <div class="promo-content">
                        <h3 class="secondary-title" style="text-align: center; line-height: 32px;">
                          <font style="color:#1B9D7D;">지금뜨는 업체</font>를 <br>구매하시겠습니까?
                        </h3>

                        <p class="required-point" style="display:none;">차감포인트 : <em><?= $point3["cd_val3"] ?></em>P</p>


                        <div class="form-wrap">
                          <form action="/">
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록지역</p>
                              <label>
                                <span class="sr-text">등록지역 선택</span>
                                <select name="pm_addr4" class="form-text js-find-loc" style="text-align:right;">
                                  <option value="">지역선택</option>
                                  <?php foreach ($addr_list as $addr) : ?>
                                    <option value="<?= $addr["ad_addr"] ?>"><?= $addr["ad_addr"] ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </label>
                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록시작일</p>
                              <label>
                                <span class="sr-text">등록시작일 선택</span>
                                <input type="text" name="pm_sdate" value="" aria-required="true" required readonly class="form-text js-datepicker">
                              </label>

                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">*광고지역:<span id="ad_area_pop4"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*광고기간:<span id="ad_duration_pop4"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*차감포인트:<span id="ad_point_pop4"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                          </form>
                        </div>
                      </div>

                      <div class="promo-footer">
                        <button type="button" class="btn btn-secondary" style="width:50%; float:left;" onclick="$('.btn-close-popup').click();">취소</button>
                        <button type="button" data-code="C004" data-point="<?= $point4["cd_val3"] ?>" class="btnBuy btn btn-primary" style="width:50%;">확인</button>
                      </div>
                    </div>
                  </template>
                </div>
              </li>
              <li>
                <div class="promo-card">
                  <div class="card-body">
                    <div class="icon-container">
                      <img src="/resource/shop/web/img/recom_icon.svg" alt="recommand-icon" class="first-img">
                      <img src="/resource/shop/web/img/PC_M_icon.svg" alt="pc-mobile-icon" class="pc-mobile-img">
                    </div>
                    <p class="title"><strong>추천공고등록</strong></p>
                    <p class="title">* 채용정보 상단 추천공고란에 노출됩니다.</p>
                    <p class="required-point">차감포인트 : <em>1일 / <?= number_format($point1["cd_val3"]) ?></em>P</p>
                  </div>

                  <!-- 구매팝업템플릿 -->
                  <template id="promo1Temp">
                    <div class="promo-view" style="width: 380px;">
                      <div class="promo-content">
                        <h3 class="secondary-title" style="text-align: center; line-height: 32px;">
                          <font style="color:#1B9D7D;">추천공고등록</font>을 <br>구매하시겠습니까?
                        </h3>

                        <p class="required-point" style="display:none;">차감포인트 : <em><?= $point1["cd_val3"] ?></em>P</p>

                        <p class="desc" style="display:none;">포인트는 500P가 차감되며,<br> 지정한 등록지역, 공고, 등록시작에 7일간 추천인재로 표시됩니다.</p>

                        <div class="form-wrap">
                          <form action="/">
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록지역</p>
                              <label>
                                <span class="sr-text">등록지역 선택</span>
                                <select name="pm_addr" class="form-text js-find-loc" style="text-align:right;">
                                  <option value="">지역선택</option>
                                  <?php foreach ($addr_list as $addr) : ?>
                                    <option value="<?= $addr["ad_addr"] ?>"><?= $addr["ad_addr"] ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </label>
                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록시작일</p>
                              <label>
                                <span class="sr-text">등록시작일 선택</span>
                                <input type="text" name="pm_sdate" value="" aria-required="true" required readonly class="form-text js-datepicker">
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*광고지역:<span id="ad_area_pop1"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*광고기간:<span id="ad_duration_pop1"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*차감포인트:<span id="ad_point_pop1"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                          </form>
                        </div>
                      </div>

                      <div class="promo-footer">
                        <button type="button" class="btn btn-secondary" style="width:50%; float:left;" onclick="$('.btn-close-popup').click();">취소</button>
                        <button type="button" data-code="C001" data-point="<?= $point1["cd_val3"] ?>" class="btnBuy btn btn-primary" style="width:50%;">확인</button>
                      </div>
                    </div>
                  </template>
                </div>
              </li>
              <li>
                <div class="promo-card">
                  <div class="card-body">
                    <div class="icon-container">
                      <span class="best-badge">BEST</span>
                      <img src="/resource/shop/web/img/jumpUp_icon.svg" alt="jumpUp
                    -icon" class="first-img">
                      <img src="/resource/shop/web/img/PC_M_icon.svg" alt="pc-mobile-icon" class="pc-mobile-img">
                    </div>
                    <p class="title"><strong>점프업(4시간)</strong></p>
                    <p class="title">* 채용정보 채용공고를 4시간마다 채용정보 리스트 상위에 노출시킵니다.</p>
                    <p class="required-point">차감포인트 : <em>1일 / <?= number_format($point2["cd_val3"]) ?></em>P</p>
                  </div>

                  <!-- 구매팝업템플릿 -->
                  <template id="promo2Temp">
                    <div class="promo-view" style="width: 380px;">
                      <div class="promo-content">
                        <h3 class="secondary-title" style="text-align: center; line-height: 32px;">
                          <font style="color:#1B9D7D;">점프업(4시간)</font>을 <br>구매하시겠습니까?
                        </h3>

                        <p class="required-point" style="display:none;">차감포인트 : <em><?= $point2["cd_val3"] ?></em>P</p>


                        <div class="form-wrap">
                          <form action="/">
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록지역</p>
                              <label>
                                <span class="sr-text">등록지역 선택</span>
                                <select name="pm_addr2" class="form-text js-find-loc" style="text-align:right;">
                                  <option value="">지역선택</option>
                                  <?php foreach ($addr_list as $addr) : ?>
                                    <option value="<?= $addr["ad_addr"] ?>"><?= $addr["ad_addr"] ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </label>
                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록시작일</p>
                              <label>
                                <span class="sr-text">등록시작일 선택</span>
                                <input type="text" name="pm_sdate" value="" aria-required="true" required readonly class="form-text js-datepicker">
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*광고지역:<span id="ad_area_pop2"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*광고기간:<span id="ad_duration_pop2"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*차감포인트:<span id="ad_point_pop2"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                          </form>
                        </div>
                      </div>

                      <div class="promo-footer">
                        <button type="button" class="btn btn-secondary" style="width:50%; float:left;" onclick="$('.btn-close-popup').click();">취소</button>
                        <button type="button" data-code="C002" data-point="<?= $point2["cd_val3"] ?>" class="btnBuy btn btn-primary" style="width:50%;">확인</button>
                      </div>
                    </div>
                  </template>
                </div>
              </li>
              <li>
                <div class="promo-card">
                  <div class="card-body">
                    <div class="icon-container">
                      <img src="/resource/shop/web/img/jumpUp_icon.svg" alt="jumpUp
                    -icon" class="first-img">
                      <img src="/resource/shop/web/img/PC_M_icon.svg" alt="pc-mobile-icon" class="pc-mobile-img">
                    </div>
                    <p class="title"><strong>점프업(8시간)</strong></p>
                    <p class="title">* 채용정보 채용공고를 8시간마다 채용정보 리스트 상위에 노출시킵니다.</p>
                    <p class="required-point">차감포인트 : <em>1일 / <?= number_format($point3["cd_val3"]) ?></em>P</p>
                  </div>

                  <!-- 구매팝업템플릿 -->
                  <template id="promo3Temp">
                    <div class="promo-view" style="width: 380px;">
                      <div class="promo-content">
                        <h3 class="secondary-title" style="text-align: center; line-height: 32px;">
                          <font style="color:#1B9D7D;">점프업(8시간)</font>을 <br>구매하시겠습니까?
                        </h3>

                        <p class="required-point" style="display:none;">차감포인트 : <em><?= $point3["cd_val3"] ?></em>P</p>


                        <div class="form-wrap">
                          <form action="/">
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록지역</p>
                              <label>
                                <span class="sr-text">등록지역 선택</span>
                                <select name="pm_addr2" class="form-text js-find-loc" style="text-align:right;">
                                  <option value="">지역선택</option>
                                  <?php foreach ($addr_list as $addr) : ?>
                                    <option value="<?= $addr["ad_addr"] ?>"><?= $addr["ad_addr"] ?></option>
                                  <?php endforeach; ?>
                                </select>
                              </label>
                            </div>
                            <div class="form-row" style="display:none;">
                              <p class="form-label">등록시작일</p>
                              <label>
                                <span class="sr-text">등록시작일 선택</span>
                                <input type="text" name="pm_sdate" value="" aria-required="true" required readonly class="form-text js-datepicker">
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*광고지역:<span id="ad_area_pop3"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*광고기간:<span id="ad_duration_pop3"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                            <div class="form-row">
                              <p class="form-label">*차감포인트:<span id="ad_point_pop3"></span></p>
                              <label>
                                <span class="sr-text"></span>
                              </label>

                            </div>
                          </form>
                        </div>
                      </div>

                      <div class="promo-footer">
                        <button type="button" class="btn btn-secondary" style="width:50%; float:left;" onclick="$('.btn-close-popup').click();">취소</button>
                        <button type="button" data-code="C003" data-point="<?= $point3["cd_val3"] ?>" class="btnBuy btn btn-primary" style="width:50%;">확인</button>
                      </div>
                    </div>
                  </template>
                </div>
              </li>

            </ul>


            <script>
              <?php
              echo "const promotionBuyArray = [ ";
              foreach ($promotion_buy_list as $promotion_buy) {
                echo "{ idx: " . $promotion_buy['rc_idx'] . " }, ";
              }
              echo "];";
              ?>
            </script>
            <p class="help-msg" style="display:none;">메인배너 광고건은 나우잡 고객센터 032-833-0019에 문의바랍니다. (별도협의필요)</p>
            <!-- adinfo1 - 추천공고등록 -->
            <div class="adinfo1">
              <div class="adbox1">
                <div class="image-container">
                <img src="/resource/shop/web/img/ad_reco2.png" alt="추천공고등록">
                </div>
              </div>
              <div class="adbox3">
                <div class="form-container">
                  <div class="form-group">
                    <label class="form-label">공고 선택</label>
                    <select id="ad_resume4" onchange="fAddrSet(this.value,'ad_addr4');">
                      <?php
                      echo "<option value=\"\"></option>";
                      foreach ($recruit_list as $resume) {
                        echo "<option value=\"" . $resume['rc_idx'] . "\">" . $resume['rc_name'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <!-- 포인트 정보 테이블 -->
                  <div class="table-container">
                    <table class="adinfo_tab">
                      <thead>
                        <tr>
                          <th>광고기간</th>
                          <th>추가혜택</th>
                          <th>차감포인트</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><span>5일</span></td>
                          <td><span>+1일</span></td>
                          <td class="adusepoint">-30,000P</td>
                        </tr>
                        <tr>
                          <td><span>10일</span></td>
                          <td><span>+3일</span></td>
                          <td class="adusepoint">-60,000P</td>
                        </tr>
                        <tr>
                          <td><span>20일</span></td>
                          <td><span>+7일</span></td>
                          <td class="adusepoint">-120,000P</td>
                        </tr>
                        <tr>
                          <td><span>30일</span></td>
                          <td><span>+10일</span></td>
                          <td class="adusepoint">-180,000P</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="form-group">
                    <label class="form-label">광고지역</label>
                    <div class="input-group">
                      <input id="ad_addr4" name="ad_addr4" onclick="$('#changeArea').addClass('on');">
                      <button type="button" id="btnTopSearch4" class="btn-search" onclick="$('#changeArea').addClass('on');">
                        <span class="sr-text">검색</span>
                      </button>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="form-label">광고시작일</label>
                    <div class="datetime-group">
                      <input id="ad_startdate4" name="ad_startdate4" class="form-text js-datepicker" value="2024-05-23" onchange="fMakeDuration4();">
                      <span class="unit">일</span>
                      <select id="ad_starthour4" name="ad_starthour4">
                        <?php
                        for ($h = 0; $h <= 23; $h++) {
                          echo "<option value=\"" . $h . "\" " . ($h == 99 ? "selected" : "") . ">" . $h . "</option>";
                        }
                        ?>
                      </select>
                      <span class="unit">시</span>
                      <input id="ad_startmin4" name="ad_startmin4" value="0">
                      <span class="unit">분</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="form-label">광고기간</label>
                    <div class="duration-group">
                      <select id="ad_duration4" name="ad_duration4" onchange="fMakeDuration4();">
                        <option value="5">5일</option>
                        <option value="10">10일</option>
                        <option value="20">20일</option>
                        <option value="30">30일</option>
                      </select>
                      <span class="plus-day">+1일</span>
                      <span id="ad_duration_txt4"></span>
                    </div>
                  </div>
                </div>

                <div class="point-info-container">
                  <!-- 포인트 정보 박스 -->
                  <div class="point-info">

                    <div class="total-point"><span>결제포인트</span><span id="usepoint4">0</span></div>
                  </div>
                  <!-- 버튼 컨테이너 -->
                  <div class="button-container">
                    <a href="/mypage/point_charge_step1">충전하기</a>
                    <!-- 추천공고등록 구매 팝업 -->
                    <a href="#promo1" aria-haspopup="dialog" class="btn-round btn-tertiary js-view-promo">구매하기</a>
                  </div>
                </div>
              </div>
            </div><!-- // adinfo1 -->

            <div class="adinfo2">
              <!-- Swiper 컨테이너 -->
              <div class="adbox1">
                <div class="swiper-container">
                  <div class="swiper-wrapper">
                    <div class="swiper-slide">
                      <img src="/resource/shop/web/img/ad_reco1.png" alt="광고 이미지 1">
                    </div>
                    <div class="swiper-slide">
                    <img src="/resource/shop/web/img/ad_reco1_re.png" alt="점프업(4시간)">
                    </div>
                  </div>
                  <!-- 네비게이션 버튼 비활성화 -->
                  <!-- <div class="swiper-button-next"></div> -->
                  <!-- <div class="swiper-button-prev"></div> -->
                  <!-- 페이지네이션 -->
                  <div class="swiper-pagination"></div>
                </div>
              </div>

              <!-- 폼 영역 -->
              <div class="adbox3">
                <div class="form-container">
                  <div class="form-group">
                    <label class="form-label">공고 선택</label>
                    <select id="ad_resume2_temp" onchange="fAddrSet(this.value,'ad_addr3');">
                      <?php
                      echo "<option value=\"\"></option>";
                      foreach ($recruit_list as $resume) {
                        echo "<option value=\"" . $resume['rc_idx'] . "\">" . $resume['rc_name'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <!-- 포인트 정보 테이블 -->
                  <div class="table-container">
                    <table class="adinfo_tab">
                      <thead>
                        <tr>
                          <th>광고기간</th>
                          <th>추가혜택</th>
                          <th>차감포인트</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><span>5일</span></td>
                          <td><span>+1일</span></td>
                          <td class="adusepoint">-10,000P</td>
                        </tr>
                        <tr>
                          <td><span>10일</span></td>
                          <td><span>+3일</span></td>
                          <td class="adusepoint">-20,000P</td>
                        </tr>
                        <tr>
                          <td><span>20일</span></td>
                          <td><span>+7일</span></td>
                          <td class="adusepoint">-40,000P</td>
                        </tr>
                        <tr>
                          <td><span>30일</span></td>
                          <td><span>+10일</span></td>
                          <td class="adusepoint">-60,000P</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="form-group">
                    <label class="form-label">광고지역</label>
                    <div class="input-group">
                      <input id="ad_addr3" name="ad_addr3" onclick="$('#changeArea').addClass('on');">
                      <button type="button" id="btnTopSearch3" class="btn-search" onclick="$('#changeArea').addClass('on');">
                        <span class="sr-text">검색</span>
                      </button>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="form-label">광고시작일</label>
                    <div class="datetime-group">
                      <input id="ad_startdate3" name="ad_startdate3" class="form-text js-datepicker" value="2024-05-23" onchange="fMakeDuration3();">
                      <span class="unit">일</span>
                      <select id="ad_starthour3" name="ad_starthour3">
                        <?php
                        for ($h = 0; $h <= 23; $h++) {
                          echo "<option value=\"" . $h . "\" " . ($h == 99 ? "selected" : "") . ">" . $h . "</option>";
                        }
                        ?>
                      </select>
                      <span class="unit">시</span>
                      <input id="ad_startmin3" name="ad_startmin3" value="0">
                      <span class="unit">분</span>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="form-label">광고기간</label>
                    <div class="duration-group">
                      <select id="ad_duration3" name="ad_duration3" onchange="fMakeDuration3();">
                        <option value="5">5일</option>
                        <option value="10">10일</option>
                        <option value="20">20일</option>
                        <option value="30">30일</option>
                      </select>
                      <span class="plus-day">+1일</span>
                      <span id="ad_duration_txt3"></span>
                    </div>
                  </div>
                </div>


                <div class="point-info-container">
                  <!-- 포인트 정보 박스 -->
                  <div class="point-info">

                    <div class="total-point"><span>결제포인트</span><span id="usepoint2">0</span></div>
                  </div>
                  <!-- 버튼 컨테이너 -->
                  <div class="button-container">
                    <a href="/mypage/point_charge_step1">충전하기</a>
                    <!-- 점프업(4시간) 구매 팝업 -->
                    <a href="#promo2" aria-haspopup="dialog" class="btn-round btn-tertiary js-view-promo">구매하기</a>
                  </div>
                </div>

              </div>
            </div><!-- // adinfo2 -->

            <!-- adinfo3 - 점프업(8시간) -->
            <div class="adinfo3">
              <div class="adbox1">
                <div class="image-container">
                  <img src="/resource/shop/web/img/ad_reco1_re.png" alt="점프업(8시간)">
                </div>
              </div>
              <div class="adbox3">
                <div class="form-container">
                  <div class="form-group">
                    <label class="form-label">공고 선택</label>
                    <select id="ad_resume3" onchange="fAddrSet(this.value,'ad_addr2');">
                      <?php
                      echo "<option value=\"\"></option>";
                      foreach ($recruit_list as $resume) {
                        echo "<option value=\"" . $resume['rc_idx'] . "\">" . $resume['rc_name'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <!-- 포인트 정보 테이블 -->
                  <div class="table-container">
                    <table class="adinfo_tab">
                      <thead>
                        <tr>
                          <th>광고기간</th>
                          <th>추가혜택</th>
                          <th>차감포인트</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><span>5일</span></td>
                          <td><span>+1일</span></td>
                          <td class="adusepoint">-5,000P</td>
                        </tr>
                        <tr>
                          <td><span>10일</span></td>
                          <td><span>+3일</span></td>
                          <td class="adusepoint">-10,000P</td>
                        </tr>
                        <tr>
                          <td><span>20일</span></td>
                          <td><span>+7일</span></td>
                          <td class="adusepoint">-20,000P</td>
                        </tr>
                        <tr>
                          <td><span>30일</span></td>
                          <td><span>+10일</span></td>
                          <td class="adusepoint">-30,000P</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="form-group">
                    <label class="form-label">광고지역</label>
                    <div class="input-group">
                      <input id="ad_addr2" name="ad_addr2" onclick="$('#changeArea').addClass('on');">
                      <button type="button" id="btnTopSearch2" class="btn-search" onclick="$('#changeArea').addClass('on');">
                        <span class="sr-text">검색</span>
                      </button>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="form-label">광고시작일</label>
                    <div class="datetime-group">
                      <input id="ad_startdate2" name="ad_startdate2" class="form-text js-datepicker" value="2024-05-23" onchange="fMakeDuration2();">
                      <span class="unit">일</span>
                      <select id="ad_starthour2" name="ad_starthour2">
                        <?php
                        for ($h = 0; $h <= 23; $h++) {
                          echo "<option value=\"" . $h . "\" " . ($h == 99 ? "selected" : "") . ">" . $h . "</option>";
                        }
                        ?>
                      </select>
                      <span class="unit">시</span>
                      <input id="ad_startmin2" name="ad_startmin2" value="0">
                      <span class="unit">분</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="form-label">광고기간</label>
                    <div class="duration-group">
                      <select id="ad_duration2" name="ad_duration2" onchange="fMakeDuration2();">
                        <option value="5">5일</option>
                        <option value="10">10일</option>
                        <option value="20">20일</option>
                        <option value="30">30일</option>
                      </select>
                      <span class="plus-day">+1일</span>
                      <span id="ad_duration_txt2"></span>
                    </div>
                  </div>
                </div>

                <div class="point-info-container">
                  <!-- 포인트 정보 박스 -->
                  <div class="point-info">
                    <div class="total-point"><span>결제포인트</span><span id="usepoint3">0</span></div>
                  </div>
                  <!-- 버튼 컨테이너 -->
                  <div class="button-container">
                    <a href="/mypage/point_charge_step1">충전하기</a>
                    <!-- 점프업(8시간) 구매 팝업 -->
                    <a href="#promo3" aria-haspopup="dialog" class="btn-round btn-tertiary js-view-promo">구매하기</a>
                  </div>
                </div>
              </div>
            </div><!-- // adinfo3 -->

            <!-- adinfo4 - 지금뜨는 업체 -->
            <div class="adinfo4">
              <div class="adbox1">
                <div class="image-container">
                <img src="/resource/shop/web/img/ad_img_comp1.png" alt="지금뜨는 업체">
                </div>
              </div>
              <div class="adbox3">
                <div class="form-container">
                  <!-- 공고 선택 기능 주석처리 (사용하지 않음) -->
                  <!--
                  <div class="form-group">
                    <label class="form-label">공고 선택</label>
                    <select id="ad_resume1" onchange="fAddrSet(this.value,'ad_addr1');">
                      <?php
                      echo "<option value=\"\"></option>";
                      foreach ($recruit_list as $resume) {
                        echo "<option value=\"" . $resume['rc_idx'] . "\">" . $resume['rc_name'] . "</option>";
                      }
                      ?>
                    </select>
                  </div>
                  -->
                  <!-- 포인트 정보 테이블 -->
                  <div class="table-container">
                    <table class="adinfo_tab">
                      <thead>
                        <tr>
                          <th>광고기간</th>
                          <th>추가혜택</th>
                          <th>차감포인트</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><span>5일</span></td>
                          <td><span>+1일</span></td>
                          <td class="adusepoint">-10,000P</td>
                        </tr>
                        <tr>
                          <td><span>10일</span></td>
                          <td><span>+3일</span></td>
                          <td class="adusepoint">-20,000P</td>
                        </tr>
                        <tr>
                          <td><span>20일</span></td>
                          <td><span>+7일</span></td>
                          <td class="adusepoint">-40,000P</td>
                        </tr>
                        <tr>
                          <td><span>30일</span></td>
                          <td><span>+10일</span></td>
                          <td class="adusepoint">-60,000P</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- 광고지역 입력 필드 제거 (지금뜨는 업체는 지역선택 없음) -->
                  <!--
                  <div class="form-group">
                    <label class="form-label">광고지역</label>
                    <div class="input-group">
                      <input id="ad_addr1" name="ad_addr1" onclick="$('#changeArea').addClass('on');">
                      <button type="button" id="btnTopSearch1" class="btn-search" onclick="$('#changeArea').addClass('on');">
                        <span class="sr-text">검색</span>
                      </button>
                    </div>
                  </div>
                  -->
                  <div class="form-group">
                    <label class="form-label">광고시작일</label>
                    <div class="datetime-group">
                      <input id="ad_startdate1" name="ad_startdate1" class="form-text js-datepicker" value="2024-05-23" onchange="fMakeDuration();">
                      <span class="unit">일</span>
                      <select id="ad_starthour1" name="ad_starthour1">
                        <?php
                        for ($h = 0; $h <= 23; $h++) {
                          echo "<option value=\"" . $h . "\" " . ($h == 99 ? "selected" : "") . ">" . $h . "</option>";
                        }
                        ?>
                      </select>
                      <span class="unit">시</span>
                      <input id="ad_startmin1" name="ad_startmin1" value="0">
                      <span class="unit">분</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="form-label">광고기간</label>
                    <div class="duration-group">
                      <select id="ad_duration1" name="ad_duration1" onchange="fMakeDuration();">
                        <option value="5">5일</option>
                        <option value="10">10일</option>
                        <option value="20">20일</option>
                        <option value="30">30일</option>
                      </select>
                      <span class="plus-day">+1일</span>
                      <span id="ad_duration_txt1"></span>
                    </div>
                  </div>
                </div>

                <div class="point-info-container">
                  <!-- 포인트 정보 박스 -->
                  <div class="point-info">

                    <div class="total-point"><span>결제포인트</span><span id="usepoint1">0</span></div>
                  </div>
                  <!-- 버튼 컨테이너 -->
                  <div class="button-container">
                    <a href="/mypage/point_charge_step1">충전하기</a>
                    <!-- 지금뜨는 업체 구매 팝업 -->
                    <a href="#promo4" aria-haspopup="dialog" class="btn-round btn-tertiary js-view-promo">구매하기</a>
                  </div>
                </div>

                
              </div>
            </div><!-- // adinfo4 -->

        </div>

      </div>

    </div>

  </main><!-- //page-content -->
</div><!-- //page -->


<input type="hidden" name="mm_point" value="<?= $mem_info["mm_point"] ?>" />

<script>
  $(document).ready(function() {
    // Swiper 초기화 - 자동넘김 활성화
    var swiper = new Swiper('.adinfo2 .swiper-container', {
      loop: true,
      speed: 500,
      autoplay: {
        delay: 3000, // 3초마다 자동 슬라이드
        disableOnInteraction: false, // 사용자 상호작용 후에도 자동재생 유지
        pauseOnMouseEnter: true, // 마우스 호버 시 일시정지
      },
      // 네비게이션 버튼 비활성화
      // navigation: {
      //   nextEl: '.adinfo2 .swiper-button-next',
      //   prevEl: '.adinfo2 .swiper-button-prev',
      // },
      pagination: {
        el: '.adinfo2 .swiper-pagination',
        clickable: true,
        dynamicBullets: true,
      },
      // 자동재생이 제대로 시작되도록 보장
      on: {
        init: function() {
          this.autoplay.start();
        }
      }
    });

    // 슬라이드 클릭 시 해당 슬라이드로 이동하는 기능 복구
    $('.adinfo2 .swiper-slide').on('click', function() {
      var slideIndex = $(this).index();
      swiper.slideTo(slideIndex);
    });

    // 카드-섹션 매핑 (카드 순서와 adinfo 번호가 다르므로 명시)
    // 1: 지금뜨는 업체 -> adinfo4
    // 2: 추천공고등록 -> adinfo1
    // 3: 점프업(4시간) -> adinfo2
    // 4: 점프업(8시간) -> adinfo3
    const promoInfoMap = {
      1: '.adinfo4',
      2: '.adinfo1',
      3: '.adinfo2',
      4: '.adinfo3',
    };

    // 페이지 로드 시 두 번째 카드(추천공고등록)를 기본 선택
    $('.promo-list li:nth-child(2) .promo-card').addClass('active');
    $('.adinfo1').css('padding-top', '500px').show().animate({
      paddingTop: '0px'
    }, 600);

    // 카드 클릭 이벤트 추가
    $('.promo-card').on('click', function() {
      var cardIndex = $(this).closest('li').index() + 1; // 카드 번호 (1~4)

      // 모든 카드의 active 클래스 제거
      $('.promo-card').removeClass('active');

      // 클릭한 카드에 active 클래스 추가
      $(this).addClass('active');

      // 모든 adinfo 숨기기
      $('.adinfo1, .adinfo2, .adinfo3, .adinfo4').hide().removeClass('show');

      // 매핑된 adinfo 표시
      const targetSelector = promoInfoMap[cardIndex];
      if (targetSelector) {
        $(targetSelector).css('padding-top', '500px').show().animate({
          paddingTop: '0px'
        }, 600);

        // adinfo2만 슬라이드 스타일이 필요하므로 show 클래스 유지
        if (targetSelector === '.adinfo2') {
          $(targetSelector).addClass('show');
        }
      }
    });

    $(document).on("click", ".btnBuy", function() {
      var code = $(this).attr("data-code");
      var point = $(this).attr("data-point");
      var mm_point = $("[name=mm_point]").val();
      var sdate = "";
      var edate = "";
      var addr = $("[name=ad_addr1]").val();
      var addr2 = $("[name=ad_addr2]").val();
      var addr3 = $("[name=ad_addr3]").val();
      var addr4 = $("[name=ad_addr4]").val();

      var sdate = $("[name=ad_startdate1]").val();
      var sdate2 = $("[name=ad_startdate2]").val();
      var sdate3 = $("[name=ad_startdate3]").val();
      var sdate4 = $("[name=ad_startdate4]").val();

      var hour = document.getElementById('ad_starthour1').value;
      var minute = document.getElementById('ad_startmin1').value;
      var hour2 = document.getElementById('ad_starthour2').value;
      var minute2 = document.getElementById('ad_startmin2').value;
      var hour3 = document.getElementById('ad_starthour3').value;
      var minute3 = document.getElementById('ad_startmin3').value;
      var hour4 = document.getElementById('ad_starthour4').value;
      var minute4 = document.getElementById('ad_startmin4').value;

      // 두 자리 숫자 형식으로 변환
      var formattedHour = padNumber(hour);
      var formattedMinute = padNumber(minute);
      var formattedHour2 = padNumber(hour2);
      var formattedMinute2 = padNumber(minute2);
      var formattedHour3 = padNumber(hour3);
      var formattedMinute3 = padNumber(minute3);
      var formattedHour4 = padNumber(hour4);
      var formattedMinute4 = padNumber(minute4);

      var params = {};
      params["pm_code"] = code;
      if (code == "C001") {
        params["pm_sdate"] = sdate4 + ' ' + formattedHour4 + ':' + formattedMinute4;
        params["rc_idx"] = $('#ad_resume4').val();
        params["pm_duration"] = $('#ad_duration4 option:selected').val();
        params["pm_addr"] = addr4;
      }
      if (code == "C002") {
        params["pm_sdate"] = sdate3 + ' ' + formattedHour3 + ':' + formattedMinute3;
        params["rc_idx"] = ""; // 공고 선택 기능을 사용하지 않으므로 빈 문자열
        params["pm_duration"] = $('#ad_duration3 option:selected').val();
        params["pm_addr"] = addr3;
      }
      if (code == "C003") {
        params["pm_sdate"] = sdate2 + ' ' + formattedHour2 + ':' + formattedMinute2;
        params["rc_idx"] = $('#ad_resume3').val();
        params["pm_duration"] = $('#ad_duration2 option:selected').val();
        params["pm_addr"] = addr2;
      }
      if (code == "C004") {
        params["pm_sdate"] = sdate + ' ' + formattedHour + ':' + formattedMinute;
        params["rc_idx"] = "";
        params["pm_duration"] = $('#ad_duration1 option:selected').val();
        params["pm_addr"] = addr;
      }

      comAjax("/compage_proc/point_promotion", params, function(ret) {
        if (ret.code == "00") {
          location.href = "/compage/point_promotion_list"
        } else {
          commonAlert(ret.msg, function() {});
        }
      });

      return;
    });

    // 0~59분만 입력되도록
    $('[name^="ad_startmin1"], [name^="ad_startmin2"], [name^="ad_startmin3"], [name^="ad_startmin4"]').on('input', function() {
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
    });


    //위치 찾는 팝업에서 광고구매 이면, 현재위치 버튼 숨기기
    $('#btnGps').hide();
    $('#changeArea .result-wrap').css('margin-top', '30px');

  }); //ready

  // 검색 완료 선택 처리
  function selectAddrInfo(zipcode, doro, jibun, addr, x, y) {
    var params = {};
    params["ad_zipcode"] = zipcode;
    params["ad_jibun"] = jibun;
    params["ad_doro"] = doro;
    params["ad_addr"] = addr;
    params["ad_gps_x"] = x;
    params["ad_gps_y"] = y;
    console.log(addr);
    $('#ad_addr1').val(addr);
    $('#ad_addr2').val(addr);
    $('#ad_addr3').val(addr);
    $('#ad_addr4').val(addr);
    $('#changeArea').removeClass('on');
  }
</script>
<?php include_once(get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>

<script>
  //구매 내용 팝업
  (function() {

    //달력 제어
    function chgMonth(instance) {
      var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
        month = Number(instance.currentMonth) + 1,
        year = instance.currentYear;
      $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
    }

    // 날짜 선택용 js-datepicker 클래스 인풋에 flatpickr 달력 위젯을 초기화하고,
    // 달력 스타일 및 옵션(한국어, 포맷, 오늘 이후만 선택 등)을 세팅한 뒤,
    // 달력이 준비될 때 광고기간 계산 함수(fMakeDuration 등)를 호출해 해당 인풋과 연동되는 광고기간 등의 값을 자동 갱신해줍니다.
    function initDatepicker() {
      if (typeof flatpickr == 'undefined') return false;

      flatpickr('.js-datepicker', {
        locale: 'ko',
        dateFormat: 'Y-m-d',
        ariaDateFormat: 'Y-m-d',
        altFormat: 'Y.m.d',
        altInput: true,
        position: 'auto right',
        nextArrow: '<span class="sr-text">다음</span>',
        prevArrow: '<span class="sr-text">이전</span>',
        disableMobile: true,
        position: 'above center',
        defaultDate: new Date(),
        minDate: 'today', // 오늘 날짜 이전은 선택할 수 없도록 설정
        onMonthChange: function(selectedDates, dateStr, instance) {
          chgMonth(instance);
        },
        onDayCreate: function(dObj, dStr, fp, dayElem) {
          var day = new Date(dayElem.getAttribute('aria-label')).getDay();
          if (day == 0) {
            dayElem.classList.add('sun');
          } else if (day == 6) {
            dayElem.classList.add('sat');
          }
        },
        onReady: function() {
          chgMonth(this);
          //console.log($('.form-control'));
          $('.form-control').css('display', 'inline-block');
          $('.form-control').css('width', '130px');
          $('.form-control').css('height', '36px');

          $('.form-control').css('border', '1px solid #57ccaf');
          $('.form-control').css('text-align', 'left');
          $('.form-control').css('background-color', '#fff');
          $('.form-control').css('background-size', '10px auto');
          $('.form-control').css('padding', '0 0 0 16px');
          $('.form-control').css('background-position', '112px 14px');

          fMakeDuration();
          fMakeDuration2();
          fMakeDuration3();
          fMakeDuration4();
        }
      });
    }

    //프로모 구매 안내 팝업
    window.addEventListener('DOMContentLoaded', function(e) {
      var viewBtns = document.querySelectorAll('.js-view-promo');

      if (!viewBtns) return;

      initDatepicker();

      viewBtns.forEach(function(btn) {

        btn.addEventListener('click', function(e) {
          e.preventDefault();

          var id = e.target.getAttribute('href');
          var content = document.querySelector(id + 'Temp') ? document.querySelector(id + 'Temp').innerHTML : '';
          console.log(content);

          var adNo = id.replace('#promo', '');
          // adNo에 따라 실제 사용하는 요소 번호 결정 (adbox3 교체로 인한 매핑)
          var actualNo = adNo;
          var actualAddrNo = adNo; // 광고지역용 요소 번호
          var actualDateTimeNo = adNo; // 시간/분/기간용 요소 번호
          if (adNo == '1') {
            actualNo = '4'; // 추천공고등록은 4번 요소 사용
            actualAddrNo = '4';
            actualDateTimeNo = '4';
          } else if (adNo == '2') {
            actualNo = '3'; // 점프업(4시간)은 3번 요소 사용
            actualAddrNo = '3';
            actualDateTimeNo = '3';
          } else if (adNo == '3') {
            actualNo = '3'; // 점프업(8시간)은 공고선택만 3번 요소 사용
            actualAddrNo = '2'; // 점프업(8시간)은 광고지역은 2번 요소 사용
            actualDateTimeNo = '2'; // 점프업(8시간)은 시간/분/기간은 2번 요소 사용
          } else if (adNo == '4') {
            actualNo = '1'; // 지금뜨는 업체는 1번 요소 사용
            actualAddrNo = '1';
            actualDateTimeNo = '1';
          }
          content = content.replace('<span id="ad_area_pop' + adNo + '"></span>', ' <span id="ad_area_pop' + adNo + '">' + $('#ad_addr' + actualAddrNo).val() + '</span>');

          var point = 0;
          var pointTxt = 0;
          if (adNo == '1') {
            if ($('#ad_duration4 option:selected').val() == '2') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt4').html() + '<br><span style="margin-left:73px;">2일 결제(+1일)</span>' + '</span>');
              point = 4000;
              pointTxt = '4,000';
            } else if ($('#ad_duration4 option:selected').val() == '5') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt4').html() + '<br><span style="margin-left:73px;">5일 결제(+1일)</span>' + '</span>');
              point = 30000;
              pointTxt = '30,000';
            } else if ($('#ad_duration4 option:selected').val() == '10') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt4').html() + '<br><span style="margin-left:73px;">10일 결제(+3일)</span>' + '</span>');
              point = 60000;
              pointTxt = '60,000';
            } else if ($('#ad_duration4 option:selected').val() == '20') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt4').html() + '<br><span style="margin-left:73px;">20일 결제(+7일)</span>' + '</span>');
              point = 120000;
              pointTxt = '120,000';
            } else if ($('#ad_duration4 option:selected').val() == '30') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt4').html() + '<br><span style="margin-left:73px;">30일 결제(+10일)</span>' + '</span>');
              point = 180000;
              pointTxt = '180,000';
            }
          } else if (adNo == '2') {
            // 점프업 4시간은 3번 요소 사용
            if ($('#ad_duration3 option:selected').val() == '2') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt3').html() + '<br><span style="margin-left:73px;">2일 결제(+1일)</span>' + '</span>');
              point = 1000;
              pointTxt = '1,000';
            } else if ($('#ad_duration3 option:selected').val() == '5') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt3').html() + '<br><span style="margin-left:73px;">5일 결제</span>' + '</span>');
              point = 10000;
              pointTxt = '10,000';
            } else if ($('#ad_duration3 option:selected').val() == '10') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt3').html() + '<br><span style="margin-left:73px;">10일 결제</span>' + '</span>');
              point = 20000;
              pointTxt = '20,000';
            } else if ($('#ad_duration3 option:selected').val() == '20') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt3').html() + '<br><span style="margin-left:73px;">20일 결제</span>' + '</span>');
              point = 40000;
              pointTxt = '40,000';
            } else if ($('#ad_duration3 option:selected').val() == '30') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt3').html() + '<br><span style="margin-left:73px;">30일 결제</span>' + '</span>');
              point = 60000;
              pointTxt = '60,000';
            }
          } else if (adNo == '3') {
            if ($('#ad_duration2 option:selected').val() == '2') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt2').html() + '<br><span style="margin-left:73px;">2일 결제(+1일)</span>' + '</span>');
              point = 800;
              pointTxt = '800';
            } else if ($('#ad_duration2 option:selected').val() == '5') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt2').html() + '<br><span style="margin-left:73px;">5일 결제</span>' + '</span>');
              point = 5000;
              pointTxt = '5,000';
            } else if ($('#ad_duration2 option:selected').val() == '10') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt2').html() + '<br><span style="margin-left:73px;">10일 결제</span>' + '</span>');
              point = 10000;
              pointTxt = '10,000';
            } else if ($('#ad_duration2 option:selected').val() == '20') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt2').html() + '<br><span style="margin-left:73px;">20일 결제</span>' + '</span>');
              point = 20000;
              pointTxt = '20,000';
            } else if ($('#ad_duration2 option:selected').val() == '30') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt2').html() + '<br><span style="margin-left:73px;">30일 결제</span>' + '</span>');
              point = 30000;
              pointTxt = '30,000';
            }
          } else if (adNo == '4') {
            if ($('#ad_duration1 option:selected').val() == '2') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt1').html() + '<br><span style="margin-left:73px;">2일 결제(+1일)</span>' + '</span>');
              point = 1000;
              pointTxt = '1000';
            } else if ($('#ad_duration1 option:selected').val() == '5') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt1').html() + '<br><span style="margin-left:73px;">5일 결제</span>' + '</span>');
              point = 10000;
              pointTxt = '10,000';
            } else if ($('#ad_duration1 option:selected').val() == '10') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt1').html() + '<br><span style="margin-left:73px;">10일 결제</span>' + '</span>');
              point = 20000;
              pointTxt = '20,000';
            } else if ($('#ad_duration1 option:selected').val() == '20') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt1').html() + '<br><span style="margin-left:73px;">20일 결제</span>' + '</span>');
              point = 40000;
              pointTxt = '40,000';
            } else if ($('#ad_duration1 option:selected').val() == '30') {
              content = content.replace('<span id="ad_duration_pop' + adNo + '"></span>', ' <span id="ad_duration_pop' + adNo + '">' + $('#ad_duration_txt1').html() + '<br><span style="margin-left:73px;">30일 결제</span>' + '</span>');
              point = 60000;
              pointTxt = '60,000';
            }
          }
          content = content.replace('<span id="ad_point_pop' + adNo + '"></span>', ' <span id="ad_point_pop' + adNo + '">' + pointTxt + 'P</span>');

          // adNo가 '2'인 경우(점프업 4시간)는 공고 선택 기능을 사용하지 않으므로 체크 건너뛰기
          // adNo가 '4'인 경우(지금뜨는 업체)도 공고 선택 기능이 주석처리되어 있으므로 체크 건너뛰기
          if (adNo !== '2' && adNo !== '4') {
            // adNo가 '3'인 경우(점프업 8시간)는 adinfo3 내부의 ad_resume3만 선택
            var resumeSelector = adNo == '3' ? '.adinfo3 #ad_resume3' : '#ad_resume' + actualNo;
            if ($(resumeSelector).val() == '' || $(resumeSelector).val() == null) {
              commonAlert("광고하실 채용공고를 선택하세요", function() {});
              return;
            }

            var addrIdx = $(resumeSelector).val();
            const matchingPromotionBuyItem = promotionBuyArray.find(item => item.idx === parseInt(addrIdx));
            var rtnPromotionBuyItem = matchingPromotionBuyItem ? true : false;
            if (rtnPromotionBuyItem) {
              commonAlert("해당공고는 이미 유료광고를 구매한 상태입니다", function() {});
              return false;
            }
          }

          // 지금뜨는 업체(adNo=4)만 광고지역 입력이 없음
          // 점프업(4시간, adNo=2)과 점프업(8시간, adNo=3)은 광고지역 선택이 필요함
          if (adNo !== '4') {
            if ($('#ad_addr' + actualAddrNo + '').val() == '') {
              commonAlert("광고하실 지역을 선택하세요", function() {});
              return;
            }
          }

          var hour = document.getElementById('ad_starthour' + actualDateTimeNo + '').value;
          var minute = document.getElementById('ad_startmin' + actualDateTimeNo + '').value;

          // 두 자리 숫자 형식으로 변환
          var formattedHour = padNumber(hour);
          var formattedMinute = padNumber(minute);
          if (formattedHour < 0 || formattedHour > 23) {
            commonAlert("광고시작 시간을 0~23시로 선택해주세요", function() {});
            return;
          }
          if (formattedMinute < 0 || formattedMinute > 59) {
            commonAlert("광고시작 시간을 0~59분으로 입력해주세요", function() {});
            return;
          }

          var mm_point = $("[name=mm_point]").val();
          if (parseInt(point) > parseInt(mm_point)) {
            commonAlert("보유하신 포인트가 부족합니다. 보유포인트 : " + mm_point.format(), function() {});
            return;
          }


          if (!id) return;

          openPopup('modal', {
            id: id + 'Dialog',
            title: '광고구매',
            content: content,
          });

          //달력 생성
          if (content.indexOf('js-datepicker') > -1) {
            initDatepicker();
          }

          //커스텀 목록 박스 생성
          if (content.indexOf('form-selectbox') > -1) {
            initSelectbox(document.querySelector('.layer-popup.in .form-selectbox'));
          }

          //팝업내 추가 스크립트 생성
          if (content.indexOf('script') > -1) {
            var currentEl = document.querySelector('.layer-popup.in script');
            var newEl = document.createElement('SCRIPT');

            newEl.innerHTML = currentEl.innerHTML;
            currentEl.remove();
            document.querySelector('.layer-popup.in').appendChild(newEl);
          }
        });
      });
    });

  }());

  function fMakeDuration() {
    // 시작 날짜 가져오기
    var startDate = document.getElementById('ad_startdate1').value;
    // 기간(일 수) 가져오기
    var duration = parseInt(document.getElementById('ad_duration1').value);

    // if(duration==2){ duration += 1; $('#usepoint1').html('-4,000');
    // }else if(duration==5){ duration += 3; $('#usepoint1').html('-30,000');
    // }else if(duration==10){ duration += 7; $('#usepoint1').html('-60,000');
    // }else if(duration==20){ duration += 7; $('#usepoint1').html('-120,000');
    // }else if(duration==30){ duration += 10; $('#usepoint1').html('-180,000'); }

    if (duration == 2) {
      duration += 1;
      $('#usepoint1').html('-1,000p');
    } else if (duration == 5) {
      duration += 1;
      $('#usepoint1').html('-10,000p');
    } else if (duration == 10) {
      duration += 3;
      $('#usepoint1').html('-20,000p');
    } else if (duration == 20) {
      duration += 7;
      $('#usepoint1').html('-40,000p');
    } else if (duration == 30) {
      duration += 10;
      $('#usepoint1').html('-60,000p');
    }

    // 날짜 형식으로 변환
    var date = new Date(startDate);
    // 선택된 일 수 만큼 날짜에 추가
    date.setDate(date.getDate() + (duration - 1));

    // 결과 날짜를 YYYY-MM-DD 형식으로 변환
    var endDate = date.toISOString().split('T')[0];

    // 결과 문자열 만들기
    var result = startDate + " 부터 " + endDate + " 까지";

    // 결과를 ad_duration_txt 요소에 설정
    document.getElementById('ad_duration_txt1').innerHTML = result;
  }

  function fMakeDuration2() {
    // 시작 날짜 가져오기
    var startDate = document.getElementById('ad_startdate2').value;
    // 기간(일 수) 가져오기
    var duration = parseInt(document.getElementById('ad_duration2').value);

    // 점프업 8시간용 포인트 업데이트 (usepoint3 사용)
    if (duration == 2) {
      duration += 1;
      $('#usepoint3').html('-1,000p');
    } else if (duration == 5) {
      duration += 1;
      $('#usepoint3').html('-5,000p');
    } else if (duration == 10) {
      duration += 3;
      $('#usepoint3').html('-10,000p');
    } else if (duration == 20) {
      duration += 7;
      $('#usepoint3').html('-20,000p');
    } else if (duration == 30) {
      duration += 10;
      $('#usepoint3').html('-30,000p');
    }

    // 날짜 형식으로 변환
    var date = new Date(startDate);
    // 선택된 일 수 만큼 날짜에 추가
    date.setDate(date.getDate() + (duration - 1));

    // 결과 날짜를 YYYY-MM-DD 형식으로 변환
    var endDate = date.toISOString().split('T')[0];

    // 결과 문자열 만들기
    var result = startDate + " 부터 " + endDate + " 까지";

    // 결과를 ad_duration_txt 요소에 설정
    document.getElementById('ad_duration_txt2').innerHTML = result;
  }

  function fMakeDuration3() {
    // 시작 날짜 가져오기
    var startDate = document.getElementById('ad_startdate3').value;
    // 기간(일 수) 가져오기
    var duration = parseInt(document.getElementById('ad_duration3').value);

    // 점프업 4시간용 포인트 업데이트 (usepoint2 사용)
    if (duration == 2) {
      duration += 1;
      $('#usepoint2').html('-1,000p');
    } else if (duration == 5) {
      duration += 1;
      $('#usepoint2').html('-10,000p');
    } else if (duration == 10) {
      duration += 3;
      $('#usepoint2').html('-20,000p');
    } else if (duration == 20) {
      duration += 7;
      $('#usepoint2').html('-40,000p');
    } else if (duration == 30) {
      duration += 10;
      $('#usepoint2').html('-60,000p');
    }

    // 날짜 형식으로 변환
    var date = new Date(startDate);
    // 선택된 일 수 만큼 날짜에 추가
    date.setDate(date.getDate() + (duration - 1));

    // 결과 날짜를 YYYY-MM-DD 형식으로 변환
    var endDate = date.toISOString().split('T')[0];

    // 결과 문자열 만들기
    var result = startDate + " 부터 " + endDate + " 까지";

    // 결과를 ad_duration_txt 요소에 설정
    document.getElementById('ad_duration_txt3').innerHTML = result;
  }

  function fMakeDuration4() {
    // 시작 날짜 가져오기
    var startDate = document.getElementById('ad_startdate4').value;
    // 기간(일 수) 가져오기
    var duration = parseInt(document.getElementById('ad_duration4').value);

    if (duration == 2) {
      duration += 1;
      $('#usepoint4').html('-1,000p');
    } else if (duration == 5) {
      duration += 1;
      $('#usepoint4').html('-30,000p');
    } else if (duration == 10) {
      duration += 3;
      $('#usepoint4').html('-60,000p');
    } else if (duration == 20) {
      duration += 7;
      $('#usepoint4').html('-120,000p');
    } else if (duration == 30) {
      duration += 10;
      $('#usepoint4').html('-180,000p');
    }

    // 날짜 형식으로 변환
    var date = new Date(startDate);
    // 선택된 일 수 만큼 날짜에 추가
    date.setDate(date.getDate() + (duration - 1));

    // 결과 날짜를 YYYY-MM-DD 형식으로 변환
    var endDate = date.toISOString().split('T')[0];

    // 결과 문자열 만들기
    var result = startDate + " 부터 " + endDate + " 까지";

    // 결과를 ad_duration_txt 요소에 설정
    document.getElementById('ad_duration_txt4').innerHTML = result;
  }

  function padNumber(num) {
    return num.toString().padStart(2, '0');
  }
</script>
<script>
  <?php
  echo "const recruitAddrArray = [ ";
  foreach ($recruit_list as $resume) {
    echo "{ idx: " . $resume['rc_idx'] . ", ad_addr: \"" . $resume['ad_addr'] . "\" }, ";
  }
  echo "];";
  ?>

  function fAddrSet(addrIdx, gbn) {

    const matchingPromotionBuyItem = promotionBuyArray.find(item => item.idx === parseInt(addrIdx));
    var rtnPromotionBuyItem = matchingPromotionBuyItem ? true : false;
    if (rtnPromotionBuyItem) {
      commonAlert("해당공고는 이미 유료광고를 구매한 상태입니다", function() {});
      return false;
    }

    const matchingItem = recruitAddrArray.find(item => item.idx === parseInt(addrIdx));
    const rawAddr = matchingItem && matchingItem.ad_addr ? matchingItem.ad_addr : "";

    // 주소를 fRemoveBigAdrJS로 축약한 뒤 입력 (add.js 로직 재사용)
    let formattedAddr = rawAddr;
    if (typeof fRemoveBigAdrJS === "function") {
      formattedAddr = fRemoveBigAdrJS(rawAddr);
    }

    $('#' + gbn).val(formattedAddr);
  }

  function fPromoteBuyAlready(gbn) {

    var addrIdx = $('#ad_resume' + gbn).val();
    const matchingPromotionBuyItem = promotionBuyArray.find(item => item.idx === parseInt(addrIdx));
    var rtnPromotionBuyItem = matchingPromotionBuyItem ? true : false;
    if (rtnPromotionBuyItem) {
      alert('해당공고는 이미 유료광고를 구매한 상태입니다');
      return false;
    }
    return true;

  }
</script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>