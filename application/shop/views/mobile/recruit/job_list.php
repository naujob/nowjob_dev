<?php
$banner_list = getBannerList("BN00302031");

// 사용자 주소 정보 로드
$addr_info = $this->user->getAddrInfo();
$mm_type = $this->user->getUserType();

// Controller에서 전달받은 포맷팅된 데이터 사용
// (이미 Controller의 job_list()에서 초기 데이터 로드 및 포맷팅됨)
$use_server_render = isset($use_server_render) ? $use_server_render : false;
$initial_recruits = isset($initial_recruits) ? $initial_recruits : ['list' => []];
$initial_total_count = isset($initial_total_count) ? $initial_total_count : 0;
$today_recruit_count = isset($today_recruit_count) ? $today_recruit_count : 0;
$hot_companies = isset($hot_companies) ? $hot_companies : ['chunks' => []];
$initial_page = isset($initial_page) ? $initial_page : 1;

?>
<style>
  /* iOS WKWebView 바운스(고무줄) 스크롤 방지 */
  html,
  body {
    overscroll-behavior-y: none;
  }

  .page-content {
    background: #fff;
  }

  .current-area .btn {
    max-width: 90%;
  }

  .search-option .categories .cate {
    display: none;
  }





  .search-option .categories .filter-tag-item {
    display: none;
  }

  .search-option .categories .filter-tag-item.on {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background-color: #fff;
    border-radius: 20px;
    padding: 5px 10px;
    font-size: var(--page-recruit-font-size-13);
    color: #333;
    font-weight: 400;
    white-space: nowrap;
  }

  .search-option .categories .filter-tag-item input[type="checkbox"] {
    display: none;
  }

  .search-option .categories .filter-tag-item .cate {
    display: inline;
    color: #333;
  }

  .search-option .categories .filter-tag-item.on::after {
    content: '×';
    font-size: var(--page-recruit-font-size-md);
    color: #999;
    margin-left: 2px;
    line-height: 1;
  }



  .current-area .btn {
    max-width: 100%;
    font-size: var(--page-recruit-font-size-2xl);
  }

  /* 지금 뜨는 업체 슬라이더 양쪽 peek */
  .section:has(.hot-list) .container {
    overflow: visible;
  }

  .section:has(.hot-list) {
    overflow: hidden;
  }

  /* 슬라이더 dot 인디케이터 */
  .hot-list .slider-dots {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin-top: 12px;
    padding: 0;
  }

  .hot-list .slider-dots .dot {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: #d0d0d0;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .hot-list .slider-dots .dot.active {
    background-color: #57CCAF;
    width: 26px;
    border-radius: 50px;
  }

  /* 추천인재 슬라이더 양쪽 peek — container overflow 재정의 */
  .section.index_comp:has(.recomm-slider) .container {
    overflow: visible;
  }

  .section.index_comp:has(.recomm-slider) {
    overflow: hidden;
  }

  /* KeenSlider 컨테이너 — 양쪽 peek: padding으로 이전/다음 슬라이드 노출 */
  .recomm-slider.person .slider-wrap {
    overflow: visible;
    padding: 0 20px;
    box-sizing: border-box;
  }

  /* 슬라이더 슬라이드: 전체 너비 */
  .recomm-slider.person .keen-slider__slide {
    flex: 0 0 100% !important;
    width: 100% !important;
    min-width: 0;
    padding-bottom: 10px !important;
  }

  /* 슬라이드 내 2x2 — flexbox wrap 방식 */
  .recomm-slider.person .person-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 0;
    width: 100%;
  }

  /* 그리드 아이템 — 2열 배치 (gap 10px 고려) */
  .recomm-slider.person .person-grid-item {
    display: flex;
    flex-direction: column;
    width: calc(50% - 5px);
    min-width: 0;
  }

  /* 카드 높이 최소화 */
  .recomm-slider.person .person-card {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .recomm-slider.person .person-card .title-wrap {
    margin: 0 0 2px;
  }

  .recomm-slider.person .person-card .title-wrap .title {
    font-size: var(--page-recruit-font-size-sm);
    font-weight: 700;
    line-height: 1.3;
    margin: 0;
    color: #333;
  }

  .recomm-slider.person .person-card .meta {
    font-size: var(--page-recruit-font-size-sm);
    font-weight: 500;
    color: #000;
    margin: 2px 0 0;
    display: flex;
    text-align: center;
    align-items: center;
    justify-content: center;
    gap: 0;
  }

  .recomm-slider.person .person-card .meta p {
    flex: 0 1 auto;
    padding: 0;
    margin: 0;
    color: #000;
    white-space: nowrap;
    line-height: 1.3;
  }

  .recomm-slider.person .person-card .meta p:after {
    content: "";
    display: none;
  }

  .recomm-slider.person .person-card .meta-divider {
    padding: 0 11px;
    color: #ccc;
    font-size: var(--page-recruit-font-size-xs);
    flex: 0 0 auto;
  }

  .recomm-slider.person .person-card .addr {
    font-size: var(--page-recruit-font-size-base);
    font-weight: 700;
    margin: 2px 0 0;
    padding: 0;
    color: #000;
    line-height: 1.2;
    text-align: center;
  }

  .recomm-slider.person .person-card .hashtag {
    font-size: 0;
    height: 50px;
    max-height: 22px;
    border-top: none;
    background-color: #3A70DB;
    border-radius: 0 0 8px 8px;
    overflow: hidden;
    line-height: 1.3;
  }

  .recomm-slider.person .person-card .tag {
    font-size: var(--page-recruit-font-size-lg);
    color: #fff;
    text-align: center !important;
  }

  /* 카드 스타일 */
  .recomm-slider.person .person-card {
    padding: 10px 0 10px;
    border: 1px solid #d2cece;
    border-radius: 25px;
    background: #fff;
    overflow: hidden;
  }

  .recomm-slider.person .person-card .desc {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .person-card {
    padding: 0 5px 0 0;
  }

  .person-card .thumb-wrap {
    position: relative;
    display: flex;
    flex-direction: column;
  }

  .person-card .recomm-badge {
    top: 10px;
    left: 10px;
    background-color: #57CCAF;
  }

  .person-card .thumb {
    position: relative;
    left: 31%;
    border-radius: 50px;
    overflow: hidden;
    background: #f0f0f0;
    width: 80px;
    height: 75px;
  }

  /* 뱃지 래퍼: 이미지 아래 배치 */
  .person-card .person-badge-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    margin-top: 4px;
    margin-bottom: 2px;
  }

  /* 뱃지 아이콘 */
  .person-card .person-badge-icon {
    font-size: 0;
  }

  .person-card .person-badge-icon img {
    width: 47px;
    height: 20px;
    display: inline-block;
  }

  /* 뱃지 텍스트 */
  .person-card .person-badge {
    position: static;
    padding: 2px 10px;
    font-size: var(--page-recruit-font-size-10);
    font-weight: 700;
    color: #fff;
    background-color: #1b9d7d;
    border-radius: 100px;
    line-height: 1.2;
    white-space: nowrap;
    max-width: 90%;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
  }

  .person-card .thumb img {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    -o-object-fit: cover;
    object-fit: cover;
    -webkit-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
  }

  .person-card .desc {
    font-size: var(--page-recruit-font-size-13);
    cursor: pointer;
    font-weight: 400;
  }

  /* 주소 */
  .person-card .addr {
    margin: 3px 0 0;
    font-size: var(--page-recruit-font-size-sm);
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
  }

  .person-card .category {
    margin: 0 0 5px;
    font-size: 0;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    word-break: keep-all;
    white-space: nowrap;
    overflow: hidden;
  }

  .person-card .title-wrap {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin: 0 0 3px;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    font-size: var(--page-recruit-font-size-md);
  }

  .person-card .title {
    width: 85%;
    margin: 0 5px 0 0;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
  }

  .person-card .area {
    margin: 0;
    color: #000;
    height: auto;
    width: 70%;
    text-align: right;
    font-weight: 600;
  }

  .person-card .meta {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    text-align: center;
  }

  .person-card .meta p {
    position: relative;
    flex: 1;
    word-break: keep-all;
    padding: 0px 3px;
    text-align: center;
  }

  .person-card .meta p:first-child {
    text-align: left;
    padding-left: 0;
  }

  .person-card .meta p:last-child {
    padding-right: 0;
  }

  .person-card .meta p:after {
    content: "";
    display: none;
  }

  .person-card .meta p:last-child:after {
    content: none;
  }

  /* 메타 구분자 */
  .person-card .meta-divider {
    flex: 0 0 auto;
    padding: 0 2px;
    font-size: var(--page-recruit-font-size-10);
    color: #ccc;
    line-height: 1;
  }

  .person-card .meta p em {
    font-style: normal;
  }

  .person-card .hashtag {
    height: auto;
    max-height: 24px;
    display: block;
  }

  /* Google AdSense 인피드 광고 스타일 */
  .ad-item-adsense {
    list-style: none;
     margin-top: 25px;
    padding: 0;
    position: relative;
  }

  .ad-item-adsense::before {
    content: "AD";
    position: absolute;
    top: 8px;
    left: 8px;
    font-size: var(--page-recruit-font-size-10);
    font-weight: 700;
    color: #999;
    z-index: 1;
  }

  .ad-item-adsense .adsense-wrapper {
    border-top: 1px solid #e0e0e0;
    border-bottom: 1px solid #e0e0e0;
    padding: 0;
    background-color: #fafafa;
  }

  .ad-item-adsense ins.adsbygoogle {
    background-color: transparent;
  }

  /* 온보딩 튜토리얼 팝업 */
  #tutorial-popup {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.9);
    z-index: 1400;
    display: none;
    align-items: center;
    justify-content: center;
  }

  .tutorial-skip-btn {
    position: absolute;
    top: 30px;
    right: 20px;
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    opacity: 0;
    z-index: 1401;
  }

  .tutorial-container {
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 30px;
  }

  .tutorial-image {
    width: 80px;
    height: 125px;
    flex-shrink: 0;
  }

  #tutorial-popup .tutorial-text {
    color: #fff;
    font-size: 18px;
    line-height: 1.5;
    text-align: center;
    padding: 0 40px;
    font-weight: 700;
  }

  #tutorial-popup .tutorial-text .highlight-text {
    font-weight: 700;
    font-size: 20px;
    color: #1B9D7D;
  }

  .tutorial-indicators {
    display: flex;
    gap: 12px;
    margin-top: 20px;
  }

  .tutorial-indicators .indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.3);
    cursor: pointer;
    transition: background-color 0.15s ease;
  }

  .tutorial-indicators .indicator.active {
    background-color: #57CCAF;
    width: 20px;
    border-radius: 4px;
  }

  .tutorial-recruit {
    position: absolute;
    bottom: 30px;
       left: 29px;
    width: 115px;
    height: 115px;
    opacity: 0;
  }

  .tutorial-arrow {
    position: absolute;
    bottom: 21px;
    left: 22px;
    width: 115px;
    height: 115px;
    opacity: 0;
  }

  .tutorial-arrow-right {
    position: absolute;
    bottom: 30px;
        left: 19px;
    width: 115px;
    height: 115px;
    opacity: 0;
  }

  .tutorial-arrow-text {
    position: absolute;
    bottom: 145px;
    left: 9px;
    width: 182px;
    color: #fff;
    font-weight: 700;
    font-size: 18px;
    text-align: center;
    line-height: 1.4;
    padding-top: 30px;
    opacity: 0;
    z-index: 10;
  }

  .tutorial-arrow-text .highlight-text-arrow {
    color: #1b9d7d;
    font-size: 20px;
    font-weight: 700;
  }

  /* 페이지 2 요소들 */
  .tutorial-resume {
    position: absolute;
    bottom: 21px;
    right: 278px;
    width: 115px;
    height: 115px;
    opacity: 0;
  }

  .tutorial-arrow-page2 {
    position: absolute;
    bottom: 11px;
    right: 12px;
    width: 115px;
    height: 115px;
    opacity: 0;
  }

  /* 페이지 2 - arrow_effect_left.png와 텍스트 */
  .tutorial-arrow-left-page2 {
    position: absolute;
    bottom: 21px;
    right: 278px;
    width: 115px;
    height: 115px;
    opacity: 0;
  }

  .tutorial-arrow-text-page2 {
    position: absolute;
    bottom: 136px;
        right: 222px;
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;
    white-space: nowrap;
    opacity: 0;
  }

  .tutorial-arrow-text-page2 .highlight-text-arrow {
    color: #1b9d7d;
    font-size:20px;
    font-weight: 700;
  }

  /* 페이지 3 요소들 */
  .tutorial-map {
    position: absolute;
    bottom: 21px;
    left: 50%;
    transform: translateX(-50%);
    width: 115px;
    height: 115px;
    opacity: 0;
  }

  .tutorial-arrow-page3 {
    position: absolute;
    bottom: 11px;
    left: 50%;
    transform: translateX(-50%);
    width: 115px;
    height: 115px;
    opacity: 0;
  }

  .tutorial-arrow-right-page3 {
    position: absolute;
    bottom: 21px;
    left: 50%;
    transform: translateX(-50%);
    width: 115px;
    height: 115px;
    opacity: 0;
  }

  .tutorial-map-text {
    position: absolute;
    bottom: 139px;
    left: 50%;
    transform: translateX(-50%);
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;
    white-space: nowrap;
    opacity: 0;
  }

  .tutorial-map-text .highlight-text-arrow {
    color: #1b9d7d;
    font-size: 20px;
    font-weight: 700;
  }

  /* 페이지 4 요소들 */
  .tutorial-page-4-wrapper {
    position: absolute;
    top: 68px;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    display: flex;
    flex-direction: column; 
    gap: 20px;
    z-index: 1402;
  }

  .tutorial-page-4-wrapper .tutorial-search-text {
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;
    padding-left: 25px;
    margin-top: -16px;
  }

  .tutorial-page-4-wrapper .tutorial-search {
        width: 457px;
    height: 55px
    object-fit: cover;
  }

  .tutorial-page-4-wrapper .highlight-text-arrow {
    color: #1b9d7d;
    font-size: 20px;
  }

  /* 채용공고 팝업 */
  #jobPostPopup {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: #fff;
    overflow-y: auto;
  }

  /* 팝업 상태: data-state 속성으로 관리 */
  #jobPostPopup[data-state="closed"] {
    transform: translateX(100%);
    pointer-events: none;
  }

  #jobPostPopup[data-state="open"] {
    transform: none;
    pointer-events: auto;
  }

  /* 오늘 채용 현황 마퀴 배너 */
  .recruit-status-banner {
    overflow: hidden;
    
    padding: 8px 0;
    margin-top: 15px;
  }

  .recruit-status-banner .marquee-wrap {
    overflow: hidden;
    width: 100%;
  }

  .recruit-status-banner .marquee-track {
    display: flex;
    width: max-content;
    animation: recruit-marquee 10s linear infinite;
  }

  .recruit-status-banner .marquee-item {
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
    padding-right: 30px;
    font-size: var(--page-recruit-font-size-13);
  }

  .recruit-status-banner .marquee-item .label {
    color: #000;
    font-weight: 500;
    margin-right: 4px;
  }

  .recruit-status-banner .marquee-item .value {
    color: #57CCAF;
    font-weight: 700;
    font-size: 20px;
  }

  @keyframes recruit-marquee {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
  }

</style>

<!-- Google AdSense 인피드 광고 스크립트 (비활성화) -->
<!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3622369584322145"
  crossorigin="anonymous"></script> -->


<?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/app_intercept_layer.php'); ?>
<div class="page-recruit">

  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span> -->
  <!-- 미확인 알림개수 -->
  <!-- <span class="cnt"><?= $g_alarm_cnt ?></span> -->

</div><!-- //header -->

<div id="jobPostPopup" class="job-post-popup" data-state="closed"></div>

<div class="page-content">
  <?php include(APPPATH . 'views/mobile/_partial/main_visual.php'); ?>

  <!-- 오늘 채용 현황 마퀴 배너 (1페이지에서만 노출) -->
  <?php
    $status_items = [
      ['label' => '', 'value' => date('Y-m-d')],
      ['label' => '전체채용공고', 'value' => number_format($initial_total_count) . '개'],
      ['label' => '오늘등록공고', 'value' => number_format($today_recruit_count) . '건'],
    ];
    $status_items_html = '';
    foreach ($status_items as $item) {
      $status_items_html .= '<span class="marquee-item"><span class="label">' . $item['label'] . '</span>&nbsp;&nbsp;<span class="value">' . $item['value'] . '</span></span>';
    }
  ?>
  <div class="section recruit-status-banner" id="recruitStatusBanner"<?= ($initial_page > 1) ? ' style="display:none;"' : '' ?>>
    <div class="marquee-wrap">
      <div class="marquee-track">
        <?= $status_items_html ?>
        <?= $status_items_html ?>
      </div>
    </div>
  </div>

  <!-- 지금 뜨는 업체 -->
  <div class="section" id="hotListSection"<?= ($initial_page > 1) ? ' style="display:none;"' : '' ?>>
    <div class="container">
      <h2 class="secondary-title index" style="margin: 1.3rem 0px 1.1rem;">지금 뜨는 업체</h2>
      <div class="hot-list" style="display: flex; flex-direction: column;">
        <div class="slider-wrap keen-slider">
          <?php foreach ($hot_companies['chunks'] as $slideIndex => $chunk) : ?>
            <div class="keen-slider__slide">
              <div class="hot-grid">
                <?php foreach ($chunk as $row) : ?>
                  <?php if ($row !== null) : ?>
                    <?php $imgAttr = ($slideIndex === 0) ? 'src' : 'data-src'; ?>
                    <?php $lazyClass = ($slideIndex === 0) ? '' : 'lozad'; ?>
                    <a href="/recruit/company/<?= $row["mm_idx"] ?>" class="hot-item">
                      <div class="thumb">
                        <img <?= $imgAttr ?>="<?= $row['comp_img'] ?>" alt="" class="<?= $lazyClass ?>">
                        <p class="name"><?= $row['comp_desc'] ?></p>
                      </div>
                    </a>
                  <?php else : ?>
                    <div class="hot-item hot-item--empty">
                      <div class="thumb"></div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- 슬라이더 dot 인디케이터 -->
        <div class="slider-dots" id="hotSliderDots"></div>
      </div>
    </div>
  </div>

  <div class="section is-last">
    <style>
      .item-info .thumb {
        width: 5rem;
      }

      .item-info {
        padding: 0.4rem 0;
      }

      .item-info .comp_name {
        margin-top: 2px;
      }

      .item-info .title {
        margin: 2px 0 2px;
      }

      .item-info .meta {
        margin: 2px -2px 0 0;
      }

      .item-info .period {
        /* font-size: 0.7rem; */
      }

      .item-info .working-time {
        /* font-size: 0.7rem; */
        line-height: 1rem;
      }

      .item-info .area {
        max-height: 1.3rem;
        margin-top: 4px;
      }

      .item-info {
        margin: 5px 0;
      }

      .item-info .amount {
        letter-spacing: -2px;
      }
    </style>
    <div class="container">
      <h2 class="secondary-title">채용공고</h2>
      <ol class="result-list result-list-card" id="ajaxList" data-page="<?= $initial_page ?>">
      <?php
        // 초기 공고 데이터 렌더링 (Controller에서 이미 포맷팅됨)
        if ($use_server_render && isset($initial_recruits['list']) && is_array($initial_recruits['list'])) {
          foreach ($initial_recruits['list'] as $row) {
            $book_yn_class = ($row['book_yn'] == "Y") ? "on" : "";
      ?>
        <li class="list-item">
          <div class="content-li<?php echo ($row['pm_idx'] != null) ? ' recomm' : ''; ?>" data-rc_idx="<?= $row['rc_idx'] ?>">
            <div class="content-info">
              <div class="content-body">
                <!-- <div class="img"> -->
                  <!-- <img src="<?= $row['rt_recruit_img'] ?>" loading="lazy" alt="채용공고 이미지"> -->
                  <?php if ($mm_type === 'MEM' || $mm_type === 'COMP'): ?>
                    <!-- <button type="button" data-bk_type="RECRUIT" data-bk_key="<?= $row['rc_idx'] ?>"
                      class="btnBookMark btn-bookmark js-toggle-bookmark <?= $book_yn_class ?>">
                      <span class="sr-text">즐겨찾기 추가</span>
                    </button> -->
                  <?php endif; ?>
                  <?php if ($row['pm_idx'] != null): ?>
                    <!-- <p class="recomm-badge"><span>추천</span></p> -->
                  <?php endif; ?>
                <!-- </div> -->
                <div class="desc">
                  <div class="wicon">
                    <?= getRecomBadgeHtml($row['pm_idx']) ?>
                    <?= $row['rt_work_img'] ?>
                  </div>
                  <div class="title ellipsis"><?= $row['rc_name'] ?></div>
                  <div class="summary">
                    <p><?= $row['rc_nation_nm'] ?></p>
                    <p><?= $row['rc_age_nm'] ?></p>
                  </div>
                  <div class="summary area">
                    <p><?= mb_strlen($row['ad_addr']) > 20 ? mb_substr($row['ad_addr'], 0, 20) . '...' : $row['ad_addr'] ?></p>
                  </div>
                </div>
                <div class="summary-container">
                  <div class="summary3"><?= $row['rt_work_type'] ?></div>
                  <div class="summary2">
                    <p class="period"><?= $row['rt_price_type'] ?></p>
                    <p class="amount"><?= $row['rt_price_pay'] ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>
      <?php
          }
        }
      ?>
      </ol>
      <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
        <p>등록된 정보가 없습니다.</p>
      </div>
      <!-- 페이지네이션 버튼 -->
      <div id="paginationWrapper" class="pagination-wrapper" data-pagination="ready"></div>
    </div>
  </div>
  <!-- <div class="list-action">
    <a href="/recruit/job_map" class="btn-map"><span class="sr-text">지도보기</span></a>
  </div> -->

</div><!-- //page-content -->
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
</div>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<!-- 온보딩 튜토리얼 팝업 -->
<div id="tutorial-popup" style="display:none;">
  <span class="tutorial-skip-btn" onclick="window.closeTutorialPopup();">건너뛰기</span>

  <!-- 페이지 4 -->
  <div class="tutorial-page-4-wrapper" style="display:none;">
    <img src="/resource/shop/mobile/img/tutorial/search.png" class="tutorial-search" alt="검색">
    <p class="tutorial-search-text">나에게 딱 맞는&nbsp;<span class="highlight-text-arrow">상세검색</span><br>지역 부터 업종까지 다양한 설정이 가능해요</p>
  </div>

  <div class="tutorial-container">
    <p class="tutorial-text"></p>
    <img src="/resource/shop/mobile/img/tutorial/touch_effect_whole.png" class="tutorial-image" alt="터치 안내">
    <div class="tutorial-indicators">
      <span class="indicator active" data-page="0"></span>
      <span class="indicator" data-page="1"></span>
      <span class="indicator" data-page="2"></span>
      <span class="indicator" data-page="3"></span>
    </div>
  </div>
  <!-- 페이지 1 -->
  <img src="/resource/shop/mobile/img/tutorial/recruit.png" class="tutorial-recruit page-1" alt="채용정보">

  <p class="tutorial-arrow-text page-1">나에게 딱맞는!&nbsp;<span class="highlight-text-arrow">채용정보</span></p>

  <!-- 페이지 2 -->
  <img src="/resource/shop/mobile/img/tutorial/resume.png" class="tutorial-resume page-2" alt="이력서" style="display:none;">

  <p class="tutorial-arrow-text-page2 page-2" style="display:none;">우리 회사에 딱맞는!&nbsp;<span class="highlight-text-arrow">인재정보</span></p>

  <!-- 페이지 3 -->
  <img src="/resource/shop/mobile/img/tutorial/recruit_map.png" class="tutorial-map page-3" alt="지도" style="display:none;">

  <p class="tutorial-map-text page-3" style="display:none;">우리 동네,&nbsp;<span class="highlight-text-arrow">채용지도</span></p>
</div>

<?php $tpl_id = 'tpl_list';
$tpl_recomm_class = true; ?>
<?php include(APPPATH . 'views/mobile/_partial/recruit_card_template.php'); ?>

<template id="tpl_category">
  <label class="filter-tag-item sm {{active}} btn-category" data-filter-key="{{filter_key}}">
    <input type="checkbox" name="filter" value="{{category_name}}">
    <span class="cate">{{category_name}}</span>
  </label>
</template>



<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />

<style>
  /* 팝업 스타일 */
  #applypop {
    position: fixed;
    top: 200px;
    /* 적절한 위치로 조정 */
    right: -5%;
    transform: translateX(-50%);
    background-color: #1B9D7D;
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: var(--page-recruit-font-size-md);
    font-weight: 100;
    white-space: nowrap;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    z-index: 10;
    display: none;
    /* 초기에는 숨김 */
  }

  /* 팝업의 화살표 (아래쪽 삼각형) */
  #applypop::after {
    content: "";
    position: absolute;
    bottom: 18px;
    right: -29px;
    transform: translateX(-26%);
    border-width: 12px;
    border-style: solid;
    border-color: transparent transparent transparent #1B9D7D;
  }
</style>


<script>
  // ========================================
  // 1️⃣  전역 변수 선언
  // ========================================

  // PHP에서 미리 변환된 주소 데이터 (서버사이드 렌더링)
  var ad_addr = <?= json_encode(removeBigAddr(isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : ""), JSON_UNESCAPED_UNICODE) ?>;
  var INITIAL_PAGE = <?= json_encode($initial_page) ?>;
  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = 1;
  var list_loading = false;
  var search_addr = [];
  var searchAreaStr = "";
  var addrName = "";
  var searchStateLoaded = false;
  var loadDataInitialized = false;// 데이터 로드 초기화 여부
  var ITEMS_PER_PAGE = 20;
  var total_count = 0;
  var total_pages = 1;
  var search_array = {
    category: "",
    work_type: "",
    week: "",
    price_type: "",
    sex: "",
    sage: "",
    eage: "",
  };

  var tab_list = [];
  tab_list.push({ key: "area", df: "근무지역", text: "", active: "" }); //0
  tab_list.push({ key: "category", df: "업직종", text: "", active: "" }); //1
  tab_list.push({ key: "work_type", df: "근무형태", text: "", active: "" }); //2
  tab_list.push({ key: "week", df: "근무요일", text: "", active: "" }); //3
  tab_list.push({ key: "price_type", df: "급여형태", text: "", active: "" }); //4
  tab_list.push({ key: "sex", df: "성별", text: "", active: "" }); //5
  tab_list.push({ key: "age", df: "나이", text: "", active: "" }); //6

  // ========================================
  // 2️⃣  URL/페이지 유틸리티 함수들
  // ========================================

  function getPageFromUrl() {
    var params = new URLSearchParams(window.location.search);
    return parseInt(params.get("page")) || 1;
  }

  function pushPageToUrl(page) {
    var params = new URLSearchParams(window.location.search);
    params.set("page", page);
    history.pushState({ page: page }, "", "?" + params.toString());
  }

  function saveCurrentPage() {
    sessionStorage.setItem('recruit_list_page', list_page);
    pushPageToUrl(list_page);
  }

  function initCashingData() {
    list_page = getPageFromUrl();
    getBoardList("");
  }

  // ========================================
  // 3️⃣  데이터 조회 함수들 (AJAX)
  // ========================================
  
  // 공고 리스트 조회
  function getBoardList(save_yn) {
    if (list_loading) {
      return;
    }

    var params = {
      page: list_page,
      limit: ITEMS_PER_PAGE,
      "search_addr[]": search_addr,
      addrName: addrName,
      searchAreaStr: searchAreaStr,
      category: search_array.category,
      work_type: search_array.work_type,
      week: search_array.week,
      price_type: search_array.price_type,
      sex: search_array.sex,
      sage: search_array.sage,
      eage: search_array.eage,
    };
    // : 검색 파라미터 저장만 수행
    if (save_yn === "Y") {
      $.ajax({
        url: "/recruit_proc/search_save_params2025",
        type: "post",
        data: params,
        traditional: false,
        dataType: "json",
        crossDomain: true,
        success: function(ret) {
          if (ret.code === "00") {

          }
        },
        error: function(xhr, status, error) {

        }
      });
    }
    // 공고 리스트 데이터 조회 및 렌더링
    list_loading = true;
    var ajaxStartTime = performance.now();
    $.ajax({
      url: "/recruit_proc/getRecruitList",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        list_loading = false;

        if (ret.code === "00" && ret.data && Array.isArray(ret.data.list)) {
          $(list_id).empty();
          var serverTotal = parseInt(ret.data.total, 10);
          if (isNaN(serverTotal) || serverTotal < 0) {
            serverTotal = 0;
          }
          if (serverTotal > 0) {
            total_count = serverTotal;
          } else {
            var listLen = ret.data.list.length;
            total_count = (listLen === ITEMS_PER_PAGE) ? (list_page * ITEMS_PER_PAGE + 1) : ((list_page - 1) * ITEMS_PER_PAGE + listLen);
          }
          total_pages = Math.max(1, Math.ceil(total_count / ITEMS_PER_PAGE));

          showBoardList(ret.data.list);

          renderPagination("#paginationWrapper", list_page, total_pages, list_id);

          updateTopSectionsVisibility();
        }
      },
      error: function(xhr, status, error) {
        list_loading = false;
      }
    });
  }

  function getRecruitRecoList() {
    var params = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "24"
    };

    comAjax("/recruit_proc/getRecruitRecoList", params, function(ret) {
      if (ret.code == "00") {
        var list = ret.data.list;
        $(".section.index_comp .result-list").html("");

        list.forEach(function(row) {
          var $clone = $("#tpl_list").clone();
          var $content = $clone.html();
          var statusLabel = getStatusLabel(row.rc_sdate, row.rc_edate);

          $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
          $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
          $content = $content.replaceAll("{{ad_addr}}", row.ad_addr);
          var rc_name = row.rc_name;
          $content = $content.replaceAll("{{rc_name}}", rc_name);
          $content = $content.replaceAll("{{rc_age_nm}}", row.rc_age_nm);
          $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);
          $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img);
          $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type || "");
          if (row.rc_nation_nm == "무관" || row.rc_nation_nm == "내국인,외국인") {
            $content = $content.replaceAll("{{rc_nation_nm}}", "국적무관");
          } else {
            $content = $content.replaceAll("{{rc_nation_nm}}", row.rc_nation_nm.replace("만", ""));
          }
          $content = $content.replaceAll("{{rc_price_type}}", row.rt_price_type || '');
          $content = $content.replaceAll("{{rc_price_pay}}", row.rt_price_pay || '');
          $content = $content.replaceAll("{{status_label}}", statusLabel);
          $clone.html($content);
          $(".section.index_comp .result-list").append($clone.html());
        });
      }
    });
  }

  // ========================================
  // 4️⃣  UI 렌더링 함수들
  // ========================================

  // 공고 상태 계산 함수
  function getStatusLabel(rc_sdate, rc_edate) {
    var now = new Date();
    var startDate = new Date(rc_sdate);
    var endDate = new Date(rc_edate);

    if (startDate <= now && now <= endDate) {
      return "모집중";
    } else if (endDate < now) {
      return "마감";
    } else if (startDate > now) {
      return "대기중";
    }
    return "";
  }

  function showBoardList(list) {
    list.forEach(function(row, index) {
      var $clone = $("#tpl_list").clone();
      var $content = $clone.html();

      if (row.pm_idx == null) {
        $content = $content.replaceAll("<div class=\"content-li recomm\"", "<div class=\"content-li\"");
        $content = $content.replaceAll("<p class=\"recomm-badge\"><span>추천</span></p>", "");
      }

      var statusLabel = getStatusLabel(row.rc_sdate, row.rc_edate);

      $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
      $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      $content = $content.replaceAll("{{rc_name}}", row.rc_name);
      $content = $content.replaceAll("{{rc_nation_nm}}", row.rc_nation_nm);
      $content = $content.replaceAll("{{rc_age_nm}}", row.rc_age_nm);
      $content = $content.replaceAll("{{ad_addr}}", row.ad_addr);
      $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);
      $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img);
      $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type || "");
      $content = $content.replaceAll("{{rc_price_type}}", row.rt_price_type || '');
      $content = $content.replaceAll("{{rc_price_pay}}", row.rt_price_pay || '');
      $content = $content.replaceAll("{{rt_recom_badge}}", row.rt_recom_badge || '');
      $content = $content.replaceAll("{{status_label}}", statusLabel);

      $clone.html($content);
      $(list_id).append($clone.html());
    });

    if (list.length > 0) {
      insertAdsenseAd();
    }

    showEmpty();
    applyLastClickedHighlight();
  }

  function insertAdsenseAd() {
    var isDevEnv = window.location.hostname.includes('dev') || window.location.pathname.includes('/dev_nowapk');

    if (isDevEnv) {
      var adHtml = '<li class="list-item ad-item-adsense" style="position: relative; background: #f0f0f0; border: 2px dashed #ccc; padding: 20px; text-align: center; color: #999; font-size: 12px;">광고 자리 (개발 환경)</li>';
      $(list_id).append(adHtml);
    } else {
      // 운영 환경에서 실제 광고 로드 (비활성화됨)
      // var adHtml = '<li class="list-item ad-item-adsense" style="position: relative;">' +
      //   '<div class="adsense-wrapper" style="position: relative;">' +
      //   '<ins class="adsbygoogle" style="display:block"' +
      //   ' data-ad-format="fluid"' +
      //   ' data-ad-layout-key="-hd+j-2v-68+mb"' +
      //   ' data-ad-client="ca-pub-7573726661605583"' +
      //   ' data-ad-slot="9872127954"></ins>' +
      //   '</div>' +
      //   '</li>';
      // $(list_id).append(adHtml);

      // setTimeout(function() {
      //   var pushStartTime = performance.now();
      //   try {
      //     (adsbygoogle = window.adsbygoogle || []).push({});
      //     var pushTime = performance.now() - pushStartTime;
      //   } catch (e) {
      //   }
      // }, 0);
    }
  }

  // 빈 상태 표시 토글
  function showEmpty() {
    if ($(list_id).find("li").length == 0) {
      $(list_empty_id).show();
      $(list_id).hide();
    } else {
      $(list_id).show();
      $(list_empty_id).hide();
    }
  }

  // 상세페이지에서 뒤로가기로 돌아왔을 때 직전에 클릭한 카드에 하이라이트 적용
  function applyLastClickedHighlight() {
    var isFromJobPost = sessionStorage.getItem('recruit_came_from_job_post');
    if (isFromJobPost !== 'true') { // 직전에 채용공고 상세페이지에서 돌아온 경우가 아니라면 하이라이트 적용하지 않음
      return;
    }

    var highlightRcIdx = sessionStorage.getItem('recruit_highlight_rc_idx');
    if (!highlightRcIdx) { // highlightRcIdx가 없으면 하이라이트 적용하지 않음
      return;
    }

    // 기존 하이라이트 모두 제거
    $('.content-li.highlight-recent').removeClass('highlight-recent');

    // 새 하이라이트 적용
    $('.content-li[data-rc_idx="' + highlightRcIdx + '"]').addClass('highlight-recent');

    // 정리
    sessionStorage.removeItem('recruit_came_from_job_post');
    sessionStorage.removeItem('recruit_highlight_rc_idx');
  }


  // 페이지네이션 버튼 클릭 이벤트 (이벤트 위임)
  function goPage(page) {
    if (page < 1 || page > total_pages) {
      return;
    }

    list_page = page;// 페이지 번호 업데이트

    saveCurrentPage(); // 세션 스토리지 및 URL 업데이트
    $(list_id).empty(); // 기존 리스트 초기화

    $("html, body").animate({scrollTop: 0}, 200); // 스크롤 상단 이동

    // 페이지네이션 미리 숨김
    $("#paginationWrapper").removeClass('visible');

    getBoardList(""); // 데이터 조회
    updateTopSectionsVisibility(); // 상단 섹션 표시 업데이트
  }

  // 현재 페이지가 1페이지인지 여부에 따라 상단 영역들을 보이거나 숨기는 역할
  function updateTopSectionsVisibility() {
    var isFirstPage = (list_page <= 1); //true이면 1페이지, false이면 2페이지 이상
    $('.main-visual').toggle(isFirstPage);
    $('#hotListSection').toggle(isFirstPage);
    $('#recruitStatusBanner').toggle(isFirstPage);
  }

  // ========================================
  // 5️⃣  초기화 함수들
  // ========================================

  function initLoadData() {
    if (loadDataInitialized) {
      return;
    }
    loadDataInitialized = true;


    var urlPage = getPageFromUrl();
    if (urlPage > 1) {
      list_page = urlPage;
    } else {
      var savedPage = sessionStorage.getItem('recruit_list_page');
      list_page = (savedPage && parseInt(savedPage) > 1) ? parseInt(savedPage) : 1;
    }

    var initialListCount = $(list_id).find("li").length;
    var hasInitialData = initialListCount > 0;

    if (list_page === INITIAL_PAGE && hasInitialData) {
      var adCount = $(list_id).find(".ad-item-adsense").length;
      var actualItemCount = initialListCount - adCount;

      var serverTotal = <?php echo json_encode($initial_total_count); ?>;
      if (serverTotal && serverTotal > 0) {
        total_count = serverTotal;
      } else {
        total_count = Math.max(actualItemCount, ITEMS_PER_PAGE);
      }
      total_pages = Math.ceil(total_count / ITEMS_PER_PAGE);

      if (actualItemCount > 0) {
        insertAdsenseAd();
      }

      renderPagination("#paginationWrapper", list_page, total_pages, list_id);
      showEmpty();
      updateTopSectionsVisibility();
      applyLastClickedHighlight();
    } else {
      if (!searchStateLoaded) {
        getBoardList("");
      }
    }

    saveCurrentPage();
  }

  function initHotSlider() {
    var hotSlider = document.querySelector('.hot-list .slider-wrap');
    if (hotSlider && typeof KeenSlider !== 'undefined') {
      if (!hotSlider.__keenslider__) {
        var slides = hotSlider.querySelectorAll('.keen-slider__slide');
        var actualSlideCount = slides.length;
        if (slides.length > 0 && slides.length < 4) {
          var cloneCount = Math.ceil(4 / slides.length) * slides.length;
          var added = slides.length;
          var imageSrcMap = {};
          slides.forEach(function(slide, idx) {
            slide.querySelectorAll('img').forEach(function(img, imgIdx) {
              imageSrcMap[idx + '-' + imgIdx] = img.src;
            });
          });
          while (added < cloneCount) {
            slides.forEach(function(slide, slideIdx) {
              if (added < cloneCount) {
                var cloned = slide.cloneNode(true);
                cloned.querySelectorAll('img').forEach(function(img, imgIdx) {
                  var srcKey = slideIdx + '-' + imgIdx;
                  if (imageSrcMap[srcKey]) img.src = imageSrcMap[srcKey];
                  if (img.classList.contains('lozad')) {
                    if (img.getAttribute('data-src') && !img.src) img.src = img.getAttribute('data-src');
                    img.setAttribute('data-loaded', 'true');
                  }
                });
                hotSlider.appendChild(cloned);
                added++;
              }
            });
          }
        }

        var keenSlider = new KeenSlider(hotSlider, {
          loop: true,
          slides: {
            perView: 1.1,
            spacing: 12
          },
          drag: true,
          dragMinDist: 0,
          rubberband: true,
          renderMode: 'precision'
        });

        var dotsContainer = document.getElementById('hotSliderDots');
        if (dotsContainer && actualSlideCount > 1) {
          for (var i = 0; i < actualSlideCount; i++) {
            var dot = document.createElement('div');
            dot.className = 'dot' + (i === 0 ? ' active' : '');
            dot.setAttribute('data-index', i);
            (function(index) {
              dot.addEventListener('click', function(e) {
                e.stopPropagation();
                keenSlider.moveToSlide(index);
              });
            })(i);
            dotsContainer.appendChild(dot);
          }

          keenSlider.on('slideChanged', function() {
            var currentIdx = ((keenSlider.track.details.abs % actualSlideCount) + actualSlideCount) % actualSlideCount;
            var dots = dotsContainer.querySelectorAll('.dot');
            dots.forEach(function(d) {
              d.classList.remove('active');
            });
            if (dots[currentIdx]) {
              dots[currentIdx].classList.add('active');
            }
          });
        }
      }
    }
  }
  // attemptRestoreScroll: 모바일 웹뷰에서 스크롤이 안되는 문제를 해결하기 위해 body와 html의 overflow 속성을 강제로 복원하는 함수
  function attemptRestoreScroll() {
    if (typeof forceRestoreBodyScroll === 'function') {
      forceRestoreBodyScroll();
    } else if (typeof restoreBodyScroll === 'function') {
      restoreBodyScroll();
    } else {
      const b = document.body;
      const h = document.documentElement;
      const propsToRemove = ['position', 'top', 'left', 'right', 'overflow', 'width', 'height'];

      if (b) {
        propsToRemove.forEach(p => {
          if (b.style.getPropertyValue(p)) {
            b.style.removeProperty(p);
          }
        });
        b.classList.remove('no-scroll');
      }

      if (h) {
        propsToRemove.forEach(p => {
          if (h.style.getPropertyValue(p)) {
            h.style.removeProperty(p);
          }
        });
      }

      const shouldForceOverflow = (b && (b.classList.contains('no-scroll') ||
          b.style.position === 'fixed' ||
          getComputedStyle(b).overflow === 'hidden')) ||
        (h && getComputedStyle(h).overflow === 'hidden');

      if (shouldForceOverflow) {
        $("body").css({
          "overflow": "auto",
          "overflow-y": "auto",
          "-webkit-overflow-scrolling": "touch"
        }).removeClass('no-scroll');
        $("html").css({
          "overflow": "auto",
          "overflow-y": "auto"
        });
      }

      ['.page-recruit', '.page-content', '#app'].forEach(selector => {
        const $container = $(selector);
        if ($container.length && $container.css('overflow') !== 'hidden') {
          $container.css('-webkit-overflow-scrolling', 'touch');
        }
      });
    }
  }

  // ========================================
  // 6️⃣  이벤트 리스너 등록
  // ========================================

  // 브라우저 뒤로가기/앞으로가기 감지
  window.addEventListener('popstate', function(e) {
    // 팝업이 열려있는지 확인
    var popup = document.getElementById('jobPostPopup');
    if (popup && popup.getAttribute('data-state') === 'open') {
      // 팝업 내 열린 패널 확인 (길찾기 등)
      var openPanels = popup.querySelectorAll('.layer-panel.on');

      if (openPanels.length > 0) {
        // 패널이 있으면 패널만 닫기 (팝업은 유지)
        openPanels.forEach(function(panel) {
          if (typeof closePanel === 'function') {
            closePanel(panel);
          }
        });
        // 뒤로가기는 여기서 처리하고 리스트 로드하지 않음
        return;
      }

      // 패널이 없으면 팝업 닫기
      if (typeof window.closeJobPostPopup === 'function') {
        window.closeJobPostPopup(null);
      }
    }

    list_page = (e.state && e.state.page) ? e.state.page : getPageFromUrl();
    $(list_id).empty();
    getBoardList("");
  });

  // 페이지네이션 버튼 클릭 (이벤트 위임)
  $(document).on("click", "#paginationWrapper button", function(e) {
    e.preventDefault();
    var page = $(this).data('page');
    if (page) {
      goPage(page);
    }
  });

  // 폼 체크박스 클릭 이벤트
  $(document).on("click", ".form-checkbox", function(e) {
    e.stopPropagation();
  });

  // 공고 카드 클릭 이벤트
  $(document).on('click', '.content-li', function(e) {
    console.log('[공고 카드 클릭] 이벤트 발생');
    if ($(e.target).closest('button, a, input').length) {
      return;
    }
    if ($(e.target).closest('.ad-item-adsense').length) {
      return;
    }
    var rcIdx = $(this).data('rc_idx');
    if (rcIdx) {
      window.openJobPostPopup(rcIdx);
    }
  });

  // 이력서 작성 버튼 클릭 이벤트
  $("#btnResumeList").click(function(e) {
    e.preventDefault();
    if (typeof window.openResumeNavigationPopup === 'function') {
      openResumeNavigationPopup();
    } else {
      commonAlert("이력서 이동 팝업을 불러올 수 없습니다. 잠시 후 다시 시도해주세요.", function() {});
    }
  });

  // ========================================
  // 7️⃣  페이지 로드 시 초기화 (DOMContentLoaded)
  // ========================================

  window.addEventListener('DOMContentLoaded', function() {
      // 중복 방지: DOMContentLoaded 여러 번 발생 시 한 번만 실행
    if (window.__jobListDOMContentLoaded) {
      console.log('[job_list.php DOMContentLoaded] 이미 실행됨 (무시)');
      return;
    }
    window.__jobListDOMContentLoaded = true;

    $('html, body').css({ // 스크롤 강제 활성화 (모바일 웹뷰에서 overflow:hidden 잔존 대응)
      overflow: 'auto',
      'overflow-y': 'auto',
      height: 'auto'
    });

    var pageType = 'recruit';
    var storageKey_regions = 'selectedRegions_' + pageType;
    var storageKey_area = 'searchAreaStr_' + pageType;

    var addrNames = [];
    var searchArea = [];
    var savedSelectedRegions = sessionStorage.getItem(storageKey_regions); // 다른 페이지에서 선택한 지역 정보가 저장되어 있는지 확인
    var savedSearchAreaStr = sessionStorage.getItem(storageKey_area); // 다른 페이지에서 선택한 지역 이름 정보가 저장되어 있는지 확인

    // savedSelectedRegions:"[{\"addr\":\"인천광역시 강화군 강화읍\",\"step\":3},{\"addr\":\"인천광역시 동구 송림동\",\"step\":3}]"
   // savedSearchAreaStr: "[\"강화읍\",\"송림동\"]"
  

    if (savedSelectedRegions) {
      try {
        window.selectedRegions = JSON.parse(savedSelectedRegions);
        addrNames = window.selectedRegions.slice(0, 3);
        //addrNames:['강화읍', '송림동']
      } catch (e) {
 
        window.selectedRegions = [];
      }
    } else {
    
      window.selectedRegions = [];
    }

    if (savedSearchAreaStr) {
      try {
        searchArea = JSON.parse(savedSearchAreaStr);
      } catch (e) {
    
        searchArea = [];
      }
    }

    if (addrNames.length > 0) {
      addrName = addrNames.join(', ');
      $('.addrName').text(addrName).attr('data-selected', 'true');
    } else {
      addrName = '지역을 선택해 주세요';
      $('.addrName').text(addrName).attr('data-selected', 'false');
    }

    search_addr = [];
    searchArea.forEach(function(area) {
      if (area && area.addr) {
        search_addr.push(area.addr);
      }
    });
    searchAreaStr = JSON.stringify(searchArea);

    if (typeof selectedRegions === 'undefined') {
      window.selectedRegions = addrNames.slice(0, 3);
    }

  
    if (typeof window.updateSelectedRegionsTags === 'function') {
  
      window.updateSelectedRegionsTags();
    }

     // 네이티브 앱 식별자 확인
     function isNativeApp() {
      return !!(window.APP_NOWAPK || (window.webkit && window.webkit.messageHandlers));
    }

    // 온보딩 튜토리얼 최초 노출 체크 (Android/iOS 브릿지)
    window.onShowTutorialResult = function(isFirst) {
      console.log('[Tutorial] onShowTutorialResult 호출됨 - isFirst:', isFirst);
      if (isFirst === 'Y') openTutorialPopup();
    };

    // 처음 설치 시에만 팝업 표시 (개발/운영 환경 상관없이)
    if (isNativeApp()) {
      if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK.showTutorial) {
        var result = APP_NOWAPK.showTutorial();
        console.log('[Tutorial] APP_NOWAPK.showTutorial() 결과:', result);
        if (result === 'Y') {
          openTutorialPopup();
        }
      } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.showTutorial) {
        console.log('[Tutorial] iOS 튜토리얼 요청');
        window.webkit.messageHandlers.showTutorial.postMessage("");
      }
    }

    initCategory();
    initTabUi();
    getRecruitRecoList();

    // initLoadData 호출: searchStateLoaded가 true이면 index_search에서 이미 getBoardList를 호출했으므로 건너뜀
    setTimeout(function() {
      if (!searchStateLoaded) {
        initLoadData();
      }
    }, 10);

    $(document).on('click', '.content-li', function(e) {
      saveCurrentPage();
    });

    attemptRestoreScroll();
    setTimeout(attemptRestoreScroll, 50);
    setTimeout(attemptRestoreScroll, 200);

    initHotSlider();
  });

  // ========================================
  // 8️⃣  기타 헬퍼 함수들
  // ========================================

  // 온보딩 튜토리얼 팝업 함수
  window.isTutorialOpen = function() {
    var popup = document.getElementById('tutorial-popup');
    return popup && popup.style.display !== 'none';
  };

  var TUTORIAL_TEXT = "나우잡에서<br><span class='highlight-text'>인재목록,인재지도,공고등록</span> 등 다양한 콘텐츠를<br>이용해보세요!";
  var CURRENT_TUTORIAL_PAGE = 0;

  function preventScroll(e) {
    e.preventDefault();
  }

  function openTutorialPopup() {
    var popup = document.getElementById('tutorial-popup');
    popup.style.display = 'flex';
    CURRENT_TUTORIAL_PAGE = 0;
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
    document.addEventListener('touchmove', preventScroll, { passive: false });
    document.addEventListener('wheel', preventScroll, { passive: false });

    var textEl = popup.querySelector('.tutorial-text');
    var containerEl = popup.querySelector('.tutorial-container');
    var skipBtn = popup.querySelector('.tutorial-skip-btn');
    var recruitEl = popup.querySelector('.tutorial-recruit');
    var arrowTextEl = popup.querySelector('.tutorial-arrow-text');

    textEl.innerHTML = TUTORIAL_TEXT;

    // 모든 page 요소들의 초기 opacity 설정
    gsap.set([
      popup.querySelectorAll('.page-1'),
      popup.querySelectorAll('.page-2'),
      popup.querySelectorAll('.page-3')
    ], { opacity: 0 });
    var page4Wrapper = popup.querySelector('.tutorial-page-4-wrapper');
    if (page4Wrapper) {
      page4Wrapper.style.display = 'none';
      gsap.set(page4Wrapper, { opacity: 0 });
    }

    // GSAP timeline으로 애니메이션 구성
    var tl = gsap.timeline();
    tl.fromTo(containerEl, { opacity: 0 }, { opacity: 1, duration: 0.6, display: 'flex' }, 0.5)
      .fromTo(skipBtn, { opacity: 0 }, { opacity: 1, duration: 0.3 }, 0.5)
      .fromTo(recruitEl, { opacity: 0 }, { opacity: 1, duration: 0.35 }, '+=0.35')
      .fromTo(arrowTextEl, { opacity: 0 }, { opacity: 1, duration: 0.3 }, '-=0.1');

    window.setTutorialPage(0);
  }

  window.closeTutorialPopup = function() {
    var popup = document.getElementById('tutorial-popup');
    popup.style.display = 'none';
    popup.querySelector('.tutorial-container').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.documentElement.style.overflow = 'auto';
    document.removeEventListener('touchmove', preventScroll);
    document.removeEventListener('wheel', preventScroll);

    // GSAP timeline 초기화
    gsap.set([
      popup.querySelector('.tutorial-container'),
      popup.querySelector('.tutorial-skip-btn'),
      popup.querySelector('.tutorial-recruit'),
      popup.querySelector('.tutorial-arrow-text')
    ], { opacity: 0 });
    var page4Wrapper = popup.querySelector('.tutorial-page-4-wrapper');
    if (page4Wrapper) {
      page4Wrapper.style.display = 'none';
      gsap.set(page4Wrapper, { opacity: 0 });
    }

    if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK.onCloseTutorial) {
      APP_NOWAPK.onCloseTutorial();
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.onCloseTutorial) {
      window.webkit.messageHandlers.onCloseTutorial.postMessage("");
    }
  };

  var pageTransitionTimeline = null;

  window.setTutorialPage = function(pageIndex) {
    var popup = document.getElementById('tutorial-popup');
    CURRENT_TUTORIAL_PAGE = pageIndex;

    // 기존 애니메이션 중지
    if (pageTransitionTimeline) pageTransitionTimeline.kill();

    // 인디케이터 업데이트
    var indicators = popup.querySelectorAll('.indicator');
    indicators.forEach(function(ind, idx) {
      if (idx === pageIndex) {
        ind.classList.add('active');
      } else {
        ind.classList.remove('active');
      }
    });

    // 새 타임라인 생성
    pageTransitionTimeline = gsap.timeline({ defaults: { ease: 'power2.out' } });

    var page4Wrapper = popup.querySelector('.tutorial-page-4-wrapper');

    if (pageIndex === 0) {
      // 페이지 1: 페이지 1 요소들만 표시, 나머지 숨김
      var page2Elements = popup.querySelectorAll('.page-2');
      var page3Elements = popup.querySelectorAll('.page-3');
      page2Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      page3Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      if (page4Wrapper) page4Wrapper.style.display = 'none';

      var page1Elements = popup.querySelectorAll('.page-1');
      page1Elements.forEach(function(el) { el.style.display = ''; gsap.set(el, { opacity: 0 }); });
    }
    else if (pageIndex === 1) {
      // 페이지 2: 페이지 1, 3 요소들 숨김
      var page1Elements = popup.querySelectorAll('.page-1');
      var page3Elements = popup.querySelectorAll('.page-3');
      page1Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      page3Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      if (page4Wrapper) page4Wrapper.style.display = 'none';

      var page2Elements = popup.querySelectorAll('.page-2');
      page2Elements.forEach(function(el) { el.style.display = ''; gsap.set(el, { opacity: 0 }); });

      // 페이지 2 애니메이션 순서
      pageTransitionTimeline
        .fromTo('.tutorial-resume', { opacity: 0 }, { opacity: 1, duration: 0.35 }, 0)
        .fromTo('.tutorial-arrow-text-page2', { opacity: 0 }, { opacity: 1, duration: 0.3 }, '+=0.35');
    }
    else if (pageIndex === 2) {
      // 페이지 3: 페이지 1, 2 요소들 숨김
      var page1Elements = popup.querySelectorAll('.page-1');
      var page2Elements = popup.querySelectorAll('.page-2');
      page1Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      page2Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      if (page4Wrapper) page4Wrapper.style.display = 'none';

      var page3Elements = popup.querySelectorAll('.page-3');
      page3Elements.forEach(function(el) { el.style.display = ''; gsap.set(el, { opacity: 0 }); });

      // 페이지 3 애니메이션 순서
      pageTransitionTimeline
        .fromTo('.tutorial-map', { opacity: 0 }, { opacity: 1, duration: 0.35 }, 0)
        .fromTo('.tutorial-map-text', { opacity: 0 }, { opacity: 1, duration: 0.3 }, '+=0.35');
    }
    else if (pageIndex === 3) {
      // 페이지 4: 페이지 1, 2, 3 요소들 숨김
      var page1Elements = popup.querySelectorAll('.page-1');
      var page2Elements = popup.querySelectorAll('.page-2');
      var page3Elements = popup.querySelectorAll('.page-3');
      page1Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      page2Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      page3Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });

      var page4Wrapper = popup.querySelector('.tutorial-page-4-wrapper');
      if (page4Wrapper) {
        page4Wrapper.style.display = 'flex';
        gsap.set(page4Wrapper, { opacity: 1 });

        var searchEl = page4Wrapper.querySelector('.tutorial-search');
        var searchTextEl = page4Wrapper.querySelector('.tutorial-search-text');

        gsap.set([searchEl, searchTextEl], { opacity: 0 });

        // 페이지 4 애니메이션 순서: search 먼저, search-text 나중
        pageTransitionTimeline
          .fromTo(searchEl, { opacity: 0 }, { opacity: 1, duration: 0.35 }, 0)
          .fromTo(searchTextEl, { opacity: 0 }, { opacity: 1, duration: 0.3 }, '+=0.35');
      }
    }
  };

  // tutorial-popup 클릭 이벤트 (페이지 전환)
  document.addEventListener('click', function(e) {
    var popup = document.getElementById('tutorial-popup');
    if (!popup || popup.style.display === 'none') return;

    // indicator 클릭은 무시
    if (e.target.classList.contains('indicator')) {
      return;
    }

    // tutorial-popup 영역 클릭 감지 (배경, 이미지, 텍스트 등)
    if (e.target === popup || popup.contains(e.target)) {
      if (CURRENT_TUTORIAL_PAGE < 3) {
        window.setTutorialPage(CURRENT_TUTORIAL_PAGE + 1);
      } else {
        window.closeTutorialPopup();
      }
    }
  });

  // 지역 선택 팝업 적용 시 카테고리 텍스트 숨김
  window.onChangeAreaApply = function() {
    $(".search-option").addClass("hide-cate");
    if (typeof saveSearchStateToStorage === "function") {
      saveSearchStateToStorage();
    }
  };

  // Android 뒤로가기 패널 상태 관리
  window.isAdvancedSearchOpen = function() {
    return $('#advancedSearch').hasClass('on');
  };

  window.isChangeAreaOpen = function() {
    return $('#changeArea').hasClass('on');
  };

  // 패널 닫힐 때 공통 body 스타일 복원 함수
  window.restorePanelClosedState = function() {
    $('body, html').css({
      'overflow': 'auto',
      'overflow-y': 'auto',
      'position': 'relative',
      'top': 'auto',
      'left': 'auto',
      'right': 'auto',
      'width': 'auto',
      'height': 'auto'
    }).removeClass('no-scroll panel-open');

    var hasOtherOpenPanel = document.querySelector('.layer-panel.on') !== null;
    if (!hasOtherOpenPanel) {
      $('body').removeClass('panel-open');
    }

    if (typeof forceRestoreBodyScroll === 'function') {
      forceRestoreBodyScroll();
    } else if (typeof restoreBodyScroll === 'function') {
      restoreBodyScroll();
    }
  };

  // 채용공고 팝업 함수들
  window.openJobPostPopup = function(rcIdx) {
    $.ajax({
      url: '/recruit/job_post/' + rcIdx,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).done(function(html) {
      var popup = document.getElementById('jobPostPopup');
      popup.innerHTML = html;

      // 로드된 HTML 내 script 태그 실행
      var scripts = popup.querySelectorAll('script');
      var pendingScripts = 0;
      var sharePanelScriptLoaded = false;

      scripts.forEach(function(script) {
        if (script.src) {
          pendingScripts++;
          var newScript = document.createElement('script');
          newScript.src = script.src;
          newScript.async = script.async;

          // share-panel.js 로드 완료 후 initializeSharePanel() 호출
          if (script.src.indexOf('share-panel.js') !== -1) {
            newScript.onload = function() {
              sharePanelScriptLoaded = true;
              if (typeof initializeSharePanel === 'function') {
                initializeSharePanel();
              }
            };
            newScript.onerror = function() {
              console.error('[openJobPostPopup] share-panel.js 로드 실패');
              sharePanelScriptLoaded = true;
            };
          }

          newScript.onloadend = function() {
            pendingScripts--;
          };

          popup.appendChild(newScript);
        } else {
          var newScript = document.createElement('script');
          newScript.textContent = script.textContent;
          popup.appendChild(newScript);
        }
      });

      document.body.style.overflow = 'hidden';
      sessionStorage.setItem('recruit_came_from_job_post', 'true');
      sessionStorage.setItem('recruit_highlight_rc_idx', rcIdx);

      // GSAP으로 팝업 열기 애니메이션
      gsap.fromTo('#jobPostPopup',
        {
          x: '100%'
        },
        {
          duration: 0.5,
          x: '0%',
          ease: "power1.out",
          clearProps: "transform",
          onComplete: function() {
            document.getElementById('jobPostPopup').setAttribute('data-state', 'open');
          }
        }
      );

      // 팝업 로드 후 이미지 lazy load 초기화
      if (typeof initLazyLoad === 'function') {
        initLazyLoad();
      }

      // 팝업 로드 후 keen-slider 초기화
      if (typeof KeenSlider !== 'undefined') {
        new KeenSlider(".view-visual-slider .slider-wrap", {
          loop: true,
        }, [
          sliderDots,
          addActiveClass
        ]);
      }

      // 팝업 로드 후 이미지 확대 기능 초기화 (magnificPopup)
      if (typeof jQuery !== 'undefined') {
        $('#jobPostPopup .view-visual-slider').magnificPopup({
          delegate: 'img',
          type: 'image',
          gallery: {
            enabled: true
          }
        });
      }

      // 팝업 로드 후 상세정보 확대/핀치줌 기능 초기화
      if (typeof window.initJobPostDescFeatures === 'function') {
        window.initJobPostDescFeatures();
      }
    }).fail(function(xhr, status, error) {
      console.error('[openJobPostPopup] AJAX 실패 - status:', status, 'error:', error);
    });
  };

  window.closeJobPostPopup = function(e) {
    if (e) e.preventDefault();

    // 팝업 내 모든 열린 패널 닫기 (길찾기 등)
    var popup = document.getElementById('jobPostPopup');
    if (popup) {
      var openPanels = popup.querySelectorAll('.layer-panel.on');
      openPanels.forEach(function(panel) {
        if (typeof closePanel === 'function') {
          closePanel(panel);
        }
      });
    }

    // GSAP으로 팝업 닫기 애니메이션
    gsap.to('#jobPostPopup', {
      duration: 0.5,
      x: '100%',
      ease: "power1.out",
      onComplete: function() {
        document.getElementById('jobPostPopup').setAttribute('data-state', 'closed');
        $('#jobPostPopup').html('');
      }
    });

    // 스크롤 복원 (nested 팝업으로 인한 overflow 오버라이드 방지)
    document.body.style.overflow = 'auto';
    document.body.style.overflowY = 'auto';
    document.documentElement.style.overflow = 'auto';
    document.documentElement.style.overflowY = 'auto';

    // 모바일 웹뷰 강제 스크롤 복원
    if (typeof restoreBodyScroll === 'function') {
      restoreBodyScroll();
    }

    applyLastClickedHighlight();
  };

  window.isJobPostPopupOpen = function() {
    // GSAP 애니메이션: opacity > 0.5이면 열려있는 것
    return parseFloat($('#jobPostPopup').css('opacity')) > 0.5;
  };

</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/recruit/index_search.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
<script>
  // 주소 팝업이 독립적으로 작동하도록 페이지 타입 설정
  window.__addrPopupPageType = 'recruit';
</script>

<!-- 페이지네이션 헬퍼 스크립트 -->
<?= getCashJs(RES_PATH . "/js/pagination-helper.js") ?>

<!-- app 인터셉트 레이어 스크립트 -->
<?= getCashJs(RES_PATH . "/js/recruit/job_list.js") ?>
