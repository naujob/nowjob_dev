<?php
$banner_list = getBannerList("BN00302031");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}

// Controller에서 전달받은 포맷팅된 데이터 사용
// (이미 resume.php의 hr_list()에서 초기 데이터 로드 및 포맷팅됨)
$use_server_render = isset($use_server_render) ? $use_server_render : false;
$initial_resumes = isset($initial_resumes) ? $initial_resumes : [];
$initial_total_count = isset($initial_total_count) ? $initial_total_count : 0;
$today_recruit_count = isset($today_recruit_count) ? $today_recruit_count : 0;
$has_initial_reco = isset($has_initial_reco) ? $has_initial_reco : false;
$initial_reco = isset($initial_reco) ? $initial_reco : [];
$initial_page = isset($initial_page) ? $initial_page : 1;
?>
<style>
    /* iOS WKWebView 바운스(고무줄) 스크롤 방지 */
    html,
  body {
    overscroll-behavior-y: none;
  }

  /* .pricetype  em {
    font-style: normal;
    position: relative;
    color: #1b9d7d;
  }
  .pricetype  em.off {
    color: #222;
  } */
  /* 카드 스타일은 main.css의 .result-list-card 섹션에서 관리 */


  .page-content {
    background: #fff;
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
    font-size: 13px;
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





  .current-area .btn {
    max-width: 100%;
    font-size: 1.8rem;
  }

  /* 지금 뜨는 업체 슬라이더 양쪽 peek */
  .section:has(.hot-list) .container {
    overflow: visible;
  }

  .section:has(.hot-list) {
    overflow: hidden;
  }

  /* 추천인재 슬라이더 양쪽 peek — container overflow 재정의 */
  .section.index_comp:has(.recomm-slider) .container {
    overflow: visible;
  }

  .section.index_comp:has(.recomm-slider) {
    overflow: hidden;
  }

  /* 추천인재 슬라이더 dot 인디케이터 */
  .recomm-slider.person .slider-dots {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin-top: 12px;
    padding: 0;
  }

  .recomm-slider.person .slider-dots .dot {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: #d0d0d0;
    cursor: pointer;
    transition: background-color 0.3s ease, width 0.3s ease;
  }

  .recomm-slider.person .slider-dots .dot.active {
    background-color: #57CCAF;
    width: 26px;
    border-radius: 50px;
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
    font-size: 12px;
    font-weight: 700;
    line-height: 1.3;
    margin: 0;
    color: #333;
  }

  .recomm-slider.person .person-card .meta {
    font-size: 0.8rem;
    font-weight: 500;
    color: #000;
    margin: 2px 0 0;
    display: flex;
    text-align: center;
    align-items: center;
    justify-content: center;
    gap: 0;
    margin-bottom: 10px;
  }

  .recomm-slider.person .person-card .meta p {
    flex: 0 1 auto;
    padding: 0;
    margin: 0;
    color: #000;
    white-space: nowrap;
    line-height: 1.3;
    text-align: center;
  }

  .recomm-slider.person .person-card .meta p:after {
    content: "";
    display: none;
  }

  .recomm-slider.person .person-card .meta-divider {
    padding: 0 11px;
    color: #ccc;
    font-size: 9px;
    flex: 0 0 auto;
  }

  .recomm-slider.person .person-card .addr {
    font-size: 1rem;
    font-weight: 700;
    margin: 2px 0 10px;
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
    font-size: 16px;
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
    margin-bottom: 10px;
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
    margin-bottom: 10px;
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
    margin-bottom: 10px;
  }

  .person-card .person-badge-icon img {
    width: 47px;
    height: 20px;
    display: inline-block;
  }

  /* 뱃지 텍스트 */
  .person-card .person-badge {
    font-size: 1rem;
    font-weight: 700;
    color: #000000;
    white-space: nowrap;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    max-width: 100%;

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
    font-size: 13px;
    cursor: pointer;
    font-weight: 400;
  }

  /* 주소 */
  .person-card .addr {
    margin: 3px 0 0;
    font-size: 12px;
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
    font-size: 14px;
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
    font-size: 10px;
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

  /* 오늘 인재 현황 마퀴 배너 */
  .resume-status-banner {
    overflow: hidden;

    padding: 8px 0;
    margin-top: 15px;
  }

  .resume-status-banner .marquee-wrap {
    overflow: hidden;
    width: 100%;
  }

  .resume-status-banner .marquee-track {
    display: flex;
    width: max-content;
    animation: resume-marquee 10s linear infinite;
  }

  .resume-status-banner .marquee-item {
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
    padding-right: 30px;
    font-size: 13px;
  }

  .resume-status-banner .marquee-item .label {
    color: #000;
    font-weight: 500;
    margin-right: 4px;
  }

  .resume-status-banner .marquee-item .value {
    color: #57CCAF;
    font-weight: 700;
    font-size: 20px;
  }

  @keyframes resume-marquee {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
  }

  /* summary와 chat_mark를 나란히 배치 */
 

  .result-list-card .content-info .summary-with-chat .summary {
    
    min-width: 0;
  }

 

  

  .result-list-card .content-info .summary-with-chat .chat_mark[data-visible="false"] {
    display: none;
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
    font-size: 10px;
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

  /* 인재 상세 팝업 */
  #hrPostPopup {
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
  #hrPostPopup[data-state="closed"] {
    transform: translateX(100%);
    pointer-events: none;
  }

  #hrPostPopup[data-state="open"] {
    transform: none;
    pointer-events: auto;
  }

</style>
<!-- Google AdSense 인피드 광고 스크립트 (비활성화) -->
<!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3622369584322145"
  crossorigin="anonymous"></script> -->
<div class="page-recruit">

  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span> -->
  <!-- 미확인 알림개수 -->
  <!-- <span class="cnt"><?= $g_alarm_cnt ?></span>
    </a> -->


  <div id="hrPostPopup" class="hr-post-popup" data-state="closed"></div>

  <div class="page-content">
    <?php include(APPPATH . 'views/mobile/_partial/main_visual.php'); ?>

    <!-- 오늘 인재 현황 마퀴 배너 (1페이지에서만 노출) -->
    <?php
      $status_items = [
        ['label' => '', 'value' => date('Y-m-d')],
        ['label' => '전체이력서', 'value' => number_format($initial_total_count) . '개'],
        ['label' => '오늘등록이력서', 'value' => number_format($today_recruit_count) . '건'],
      ];
      $status_items_html = '';
      foreach ($status_items as $item) {
        $status_items_html .= '<span class="marquee-item"><span class="label">' . $item['label'] . '</span>&nbsp;&nbsp;<span class="value">' . $item['value'] . '</span></span>';
      }
    ?>
    <div class="section resume-status-banner" id="resumeStatusBanner"<?= ($initial_page > 1) ? ' style="display:none;"' : '' ?>>
      <div class="marquee-wrap">
        <div class="marquee-track">
          <?= $status_items_html ?>
          <?= $status_items_html ?>
        </div>
      </div>
    </div>

    <!-- 추천인재 -->
    <div class="section index_comp" id="recommSection"<?= ($initial_page > 1) ? ' style="display:none;"' : '' ?>>
      <div class="container">
        <h2 class="secondary-title index">추천인재</h2>
        <div class="recomm-slider person" style="display: flex; flex-direction: column;">
          <div class="slider-wrap keen-slider" id="recomm-list">
            <?php
            if ($has_initial_reco && isset($initial_reco) && is_array($initial_reco)) {
              $chunked = array_chunk($initial_reco, 4);
              foreach ($chunked as $slideIndex => $chunk) {
                ?>
                <div class="keen-slider__slide">
                  <div class="person-grid">
                    <?php foreach ($chunk as $row) { ?>
                      <div class="person-grid-item">
                        <div class="person-card" data-ru_idx="<?= $row['ru_idx'] ?>" data-mm_idx="<?= $row['mm_idx'] ?>">
                          <div class="thumb-wrap">
                            <div class="thumb">
                              <?php $imgAttr = ($slideIndex === 0) ? 'src' : 'data-src'; ?>
                              <?php $lazyClass = ($slideIndex === 0) ? '' : 'lozad'; ?>
                              <img <?= $imgAttr ?>="<?= $row['ru_resume_img'] ?>" alt="프로필" class="<?= $lazyClass ?>">
                            </div>
                            <div class="person-badge-wrap">
                              <div class="person-badge-icon"><?= $row['ru_work_img'] ?></div>
                              <p class="person-badge"><?= $row['ru_name'] ?></p>
                            </div>
                          </div>
                          <div class="desc">
                            <div class="content-wrap">
                              <div class="meta">
                                <p class="work_type"><?= $row['ru_work_type'] ?></p>
                                <span class="meta-divider">|</span>
                                <p class="national"><?= $row['mm_nation'] ?></p>
                                <span class="meta-divider">|</span>
                                <p class="age"><?= ($row['mm_age'] > 0) ? $row['mm_age'].'대' : '' ?></p>
                                <span class="meta-divider">|</span>
                                <p class="price_type"><?= $row['ru_price_type'] ?></p>
                              </div>
                              <p class="addr"><?= $row['ad_addr'] ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
                <?php
              }
            }
            ?>
          </div>
          <!-- 슬라이더 dot 인디케이터 -->
          <div class="slider-dots" id="recommSliderDots"></div>
        </div>
      </div>
    </div>

    <div class="section is-last">
      <style>
        /* .section .container {
          padding-top: 0;
        } */

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
        <h2 class="secondary-title">인재정보</h2>
        <ol class="result-list result-list-card" id="ajaxList" data-page="<?= $initial_page ?>">
          <?php if ($use_server_render && isset($initial_resumes)) {
            foreach ($initial_resumes as $row) {
              $mm_name = isset($row['ru_name']) ? $row['ru_name'] : '';
              $is_recomm = !empty($row['pm_idx']);
          ?>
            <li class="list-item">
              <div class="content-li <?= $is_recomm ? 'recomm' : '' ?>"
                   data-ru_idx="<?= $row['ru_idx'] ?>"
                   data-mm_idx="<?= $row['mm_idx'] ?>">
                <div class="content-info">
                  <div class="content-body">
                    <!-- <div class="img">
                      <img src="<?= $row['ru_resume_img'] ?>" alt="프로필">
                    </div> -->
                    <div class="desc">
                      <div class="wicon">
                        <?= getRecomBadgeHtml($row['pm_idx']) ?>
                        <?= $row['ru_work_img'] ?>
                      </div>
                      <div class="title ellipsis"><?= $mm_name ?></div>
                      <div class="summary">
                        <ul class="working-day"><?= $row['ru_week'] ?></ul>
                      </div>
                      <div class="summary-with-chat">
                        <div class="summary">
                          <p><?= $row['mm_nation'] ?></p>
                          <p><?= ($row['mm_age'] > 0) ? $row['mm_age'].'대' : '' ?></p>
                          <p><?= $row['ad_addr'] ?></p>
                        </div>
                        <div class="chat_mark" data-visible="<?= !empty($row['chat_rm_idx']) ? 'true' : 'false' ?>" data-rm_idx="<?= isset($row['chat_rm_idx']) ? $row['chat_rm_idx'] : '' ?>" style="background-image: url('/resource/shop/mobile/img/hr_list_chat_mark.png');"></div>
                      </div>
                      <div class="summary3">
                        <p class="worktype"><?= $row['ru_work_type'] ?></p>
                      </div>
                      <div class="summary2"><p class="pricetype"><?= $row['ru_price_type'] ?></p></div>
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
        <div id="paginationWrapper" class="pagination-wrapper" style="margin: 0px 0 48px 0;">
        </div>
      </div>
    </div>
    <!-- <div class="list-action">
      <a href="/resume/hr_map" class="btn-map"><span class="sr-text">지도보기</span></a>
    </div> -->

  </div><!-- //page-content -->
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
</div>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<?php $tpl_id = 'tpl_list';
$tpl_recomm_class = true;
$tpl_show_mm_idx = true;
$tpl_show_chat_mark = true; ?>
<?php include(APPPATH . 'views/mobile/_partial/resume_card_template.php'); ?>

<template id="tpl_category">
  <label class="filter-tag-item sm {{active}} btn-category" data-filter-key="{{filter_key}}">
    <input type="checkbox" name="filter" value="{{category_name}}">
    <span class="cate">{{category_name}}</span>
  </label>
</template>

<template id="tpl_reco_item">
  <div class="person-grid-item">
    <div class="person-card" data-ru_idx="{{ru_idx}}">
      <div class="thumb-wrap">
        <div class="thumb">
          <img src="{{ru_resume_img}}" alt="프로필">
        </div>
        <div class="person-badge-wrap">
          <div class="person-badge-icon">{{ru_work_img}}</div>
          <p class="person-badge">{{ru_name}}</p>
        </div>
      </div>
      <div class="desc">
        <div class="content-wrap">
          <div class="meta">
            <p class="work_type">{{ru_work_type}}</p>
            <span class="meta-divider">|</span>
            <p class="national">{{mm_nation}}</p>
            <span class="meta-divider">|</span>
            <p class="age">{{mm_age}}</p>
            <span class="meta-divider">|</span>
            <p class="price_type">{{ru_price_type}}</p>
          </div>
          <p class="addr">{{ad_addr}}</p>
        </div>
      </div>
    </div>
  </div>
</template>


<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />

<script>
  // 현재 로그인한 사용자 정보
  var currentUserMmIdx = <?php
                          $mm_idx = $this->user->isLogin() ? $this->user->getUserIdx() : null;
                          echo json_encode($mm_idx ? (int)$mm_idx : null);
                          ?>;
  var currentUserType = <?php echo json_encode(isset($mm_type) ? $mm_type : ""); ?>;
</script>

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
    font-size: 14px;
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
  // mm_birth 디버깅 - 초기 렌더링 데이터 확인
  var debugInitialResumes = <?php echo json_encode(isset($initial_resumes) && is_array($initial_resumes) ? $initial_resumes : array()); ?>;
  if (debugInitialResumes.length > 0) {
    debugInitialResumes.forEach(function(row, idx) {
    });
  }

  var currentUserId = "<?= $this->user->isLogin() ? $this->user->getUserIdx() : '' ?>";
  var ad_addr = <?php echo json_encode(isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : ""); ?>;
  var INITIAL_PAGE = <?= json_encode($initial_page) ?>;
  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = 1; // 현재 페이지 번호
  var list_loading = false; // 데이터 로딩 중 여부 (중복 요청 방지)
  var search_addr = []; // 선택된 지역 코드 배열 (예: ["11010", "11020"])
  var searchAreaStr = "";
  var addrName = ""; // 현재 선택된 지역 이름 (예: "서울 강남구 역삼동")
  var searchStateLoaded = false; // loadSearchStateFromStorage()에서 설정되는 플래그 (중복 호출 방지)
  var ITEMS_PER_PAGE = 20; // 한 번에 로드할 아이템 수
  var total_count = 0; // 전체 아이템 수
  var total_pages = 1; // 전체 페이지 수
  var search_array = { // 검색 조건 객체
    category: "",
    work_type: "",
    week: "",
    price_type: "",
    sex: "",
    sage: "",
    eage: "",
  };

  var tab_list = [];
  // 근무지역(지역 선택) 탭 항목 — 항상 tab_list[0]으로 유지 (recruit/job_list.php 패턴과 동일)
  tab_list.push({
    key: "area",
    df: "근무지역",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "category",
    df: "업직종",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "work_type",
    df: "근무형태",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "week",
    df: "근무요일",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "price_type",
    df: "급여형태",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "sex",
    df: "성별",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "age",
    df: "나이",
    text: "",
    active: ""
  });

  // 페이지네이션 버튼 클릭 (이벤트 위임)
  $(document).on("click", "#paginationWrapper button", function(e) {
    e.preventDefault();
    var page = $(this).data('page');
    if (page) {
      goPage(page);
    }
  });

  $(document).on("click", ".form-checkbox", function(e) {
    e.stopPropagation();
  });

  // URL에서 page 파라미터 읽기
  function getPageFromUrl() {
    var params = new URLSearchParams(window.location.search);
    return parseInt(params.get("page")) || 1;
  }

  // URL에 page 파라미터 반영 (히스토리 push)
  function pushPageToUrl(page) {
    var params = new URLSearchParams(window.location.search);
    params.set("page", page);
    history.pushState({ //브라우저를 새로고침하지 않고 URL 변경  example.com?page=3
      page: page // 첫 번째 인자는 상태 객체(브라우저 히스토리에 저장하여 나중에 뒤로가기 할 때 이 값을 꺼낼 수 있음), 두 번째 인자는 제목(대부분의 브라우저에서 무시), 세 번째 인자는 변경할 URL
    }, "", "?" + params.toString());
  }

  // 캐싱된 페이지 데이터 초기화 (뒤로가기 복원 시)
  function initCashingData() {
    list_page = getPageFromUrl(); // URL에서 현재 페이지 번호 읽어서 list_page에 저장 example.com?page=3 -> list_page = 3
    getBoardList(""); // 현재 페이지 번호에 해당하는 글 목록 불러오기
  }

  function initLoadData() {
    // URL 파라미터 > sessionStorage > 기본값(1)
    var urlPage = getPageFromUrl();
    if (urlPage > 1) {
      list_page = urlPage;
    } else {
      var savedPage = sessionStorage.getItem('resume_list_page');
      list_page = (savedPage && parseInt(savedPage) > 1) ? parseInt(savedPage) : 1;
    }

    total_count = 0;
    total_pages = 1;

    // 페이지가 초기 페이지이고 초기 데이터가 있으면 AJAX 스킵 (서버사이드 렌더링)
    var initialListCount = $(list_id).find("li").length;
    var hasInitialData = initialListCount > 0;

    if (list_page === INITIAL_PAGE && hasInitialData) {
      // 초기 데이터 있음: 렌더링 완료, 페이지네이션과 광고 추가
      // 광고 제외하고 실제 아이템 개수 계산
      var adCount = $(list_id).find(".ad-item-adsense").length;
      var actualItemCount = initialListCount - adCount;

      // 서버에서 전달받은 전체 데이터 개수 사용 (PHP: initial_total_count)
      var serverTotal = <?php echo json_encode($initial_total_count); ?>;
      if (serverTotal && serverTotal > 0) {
        total_count = serverTotal;
      } else {
        total_count = Math.max(actualItemCount, ITEMS_PER_PAGE);
      }
      total_pages = Math.ceil(total_count / ITEMS_PER_PAGE);
      renderPagination("#paginationWrapper", list_page, total_pages, list_id);

      // 광고 삽입
      if (initialListCount > 0) {
        insertAdsenseAd();
      }

      showEmpty();
      updateTopSectionsVisibility();
      applyLastClickedHighlight();
    } else {
      // 초기 데이터 없음 또는 페이지 2+: AJAX로 로드
      // searchStateLoaded가 true면 이미 loadSearchStateFromStorage()에서 호출했으므로 스킵
      if (!searchStateLoaded) {
        getBoardList("");
      }
    }

    // 현재 페이지를 sessionStorage에 저장
    saveCurrentPage();
  }

  // 현재 페이지를 sessionStorage에 저장 (상세페이지 돌아올 때 복원용)
  function saveCurrentPage() {
    sessionStorage.setItem('resume_list_page', list_page);
    pushPageToUrl(list_page);
  }

  // 브라우저 뒤로가기/앞으로가기 감지
  window.addEventListener('popstate', function(e) {
    list_page = (e.state && e.state.page) ? e.state.page : getPageFromUrl();
    $(list_id).empty();
    getBoardList("");
  });

  function getBoardList(save_yn) { // 글 목록을 가져오는 함수 (save_yn: 검색 조건 저장 여부)
    if (list_loading) return;

    var params = {
      page: list_page,
      pagesize: ITEMS_PER_PAGE,
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

    if (save_yn === "Y") {
      $.ajax({
        url: "/resume_proc/search_save_params2025", // 검색 조건을 저장하는 API 엔드포인트
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

    list_loading = true;
    $.ajax({
      url: "/resume_proc/search_list2025", // 검색 조건에 맞는 글 목록을 가져오는 API 엔드포인트
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        list_loading = false;

        if (ret.code === "00" && ret.data && Array.isArray(ret.data.list)) {
          $(list_id).empty(); // 매번 초기화
          total_count = ret.data.total || 0;
          total_pages = Math.ceil(total_count / ITEMS_PER_PAGE) || 1;

          showBoardList(ret.data.list);
          renderPagination("#paginationWrapper", list_page, total_pages, list_id);
          updateTopSectionsVisibility();
        } else {

        }
      },
      error: function(xhr, status, error) {
        list_loading = false;

      }
    });
  }

  //데이터 보여주기
  function showBoardList(list) {
    list.forEach(function(row, index) {
      // template 태그에서 내용 추출
      var templateEl = document.getElementById("tpl_list");
      var $content = templateEl ? templateEl.innerHTML : "";

      // $content가 null이거나 undefined인 경우 처리
      if (!$content || typeof $content !== 'string') {
        return;
      }

      // 추천 여부 처리
      if (row.pm_idx == null) {
        $content = $content.replaceAll("<div class=\"content-li recomm\"", "<div class=\"content-li\"");
        $content = $content.replaceAll("<p class=\"recomm-badge\"><span>추천</span></p>", "");
      }

      // 포맷팅된 데이터 직접 사용 (Controller에서 이미 처리됨)
      $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || '');
      $content = $content.replaceAll("{{mm_idx}}", row.mm_idx || "");
      $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      $content = $content.replaceAll("{{mm_name}}", row.ru_name || '');
      $content = $content.replaceAll("{{ru_week}}", row.ru_week || '');
      $content = $content.replaceAll("{{distance}}", (row.distance || 0) + "km");
      $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img || '');
      $content = $content.replaceAll("{{mm_nation}}", row.mm_nation || '');
      $content = $content.replaceAll("{{ma_age}}", (row.mm_age || '') + "대");
      $content = $content.replaceAll("{{ru_price_type}}", row.ru_price_type || '');
      $content = $content.replaceAll("{{ru_work_type}}", row.ru_work_type || '');
      $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img || '');
      $content = $content.replaceAll("{{ad_addr}}", row.ad_addr || '');
      $content = $content.replaceAll("{{ru_recom_badge}}", row.ru_recom_badge || '');
      $content = $content.replaceAll("{{has_chat}}", row.chat_rm_idx ? "true" : "false");
      $content = $content.replaceAll("{{chat_rm_idx}}", row.chat_rm_idx || '');

      $(list_id).append($content);
    });

    if (list.length > 0) {
      insertAdsenseAd();
    }

    showEmpty();
    applyLastClickedHighlight();
  }

  // Google AdSense 인피드 광고 삽입 함수 (비동기 로드)
  function insertAdsenseAd() {
    // 도메인 환경 판별
    var isDevEnv = window.location.hostname.includes('dev') || window.location.pathname.includes('/dev_nowapk');

    if (isDevEnv) {
      // 개발 환경에서는 CSS로만 자리 표시
      var adHtml = '<li class="list-item ad-item-adsense" style="position: relative; background: #f0f0f0; border: 2px dashed #ccc; padding: 20px; text-align: center; color: #999; font-size: 12px;">' +
        '광고 자리 (개발 환경)' +
        '</li>';
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

      // // 비동기로 광고 로드 (setTimeout 사용)
      // setTimeout(function() {
      //   try {
      //     (adsbygoogle = window.adsbygoogle || []).push({});
      //   } catch (e) {
      //   }
      // }, 0); // 비동기 로드 (즉시)
    }
  }



  function getPriceType(price_type) {
    if (!price_type) {
      return "";
    }
    if (price_type === "일급,주급,월급") {
      return "급여협의";
    }
    return price_type
      .split(',')
      .map(type => `${type}`)
      .join('');
  }

  function getWorkType(work_type) {
    if (!work_type) {
      return "";
    }

    let items = [];

    // HTML span 형식 처리
    if (work_type.includes('<span>')) {
      const matches = work_type.match(/<span>(.*?)<\/span>/g);
      if (matches && matches.length > 0) {
        items = matches.map(m => m.replace(/<\/?span>/g, '').trim());
      }
    } else {
      // 쉼표로 구분된 일반 문자열 처리
      items = work_type.split(',').map(item => item.trim()).filter(item => item);
    }

    if (items.length === 0) {
      return "";
    }

    // 모든 값을 처리하여 HTML 생성
    return items.map((text, index) => {
      let cls = '';
      if (text === '주간') {
        cls = 'wtype1';
      } else if (text === '야간') {
        cls = 'wtype2';
      } else if (text === '2교대') {
        cls = 'wtype3';
      }
      let result = `<em class="${cls}">${text}</em>`;
      // 마지막 항목이 아니면 쉼표 추가
      if (index < items.length - 1) {
        result += ',';
      }
      return result;
    }).join('');
  }

  // 이력서 상세 페이지 접근 — 모든 사용자 클릭 가능 (hr_post에서 블러 처리)
  function handleResumeCardClick(ruIdx, resumeMmIdx) {
    if (ruIdx) {
      window.openHrPostPopup(ruIdx);
    }
  }

  $(document).on('click', '.content-li', function(e) {
    // 버튼이나 링크 클릭은 무시
    if ($(e.target).closest('button, a, input').length) {
      return;
    }
    // Google AdSense 광고 클릭이면 무시 (WebView의 shouldOverrideUrlLoading이 처리)
    if ($(e.target).closest('.ad-item-adsense').length) {
      return;
    }
    // 채팅 마크 클릭은 무시 (별도 핸들러에서 처리)
    if ($(e.target).closest('.chat_mark').length) {
      return;
    }

    var $target = $(this);
    var ruIdx = $target.data('ru_idx');
    var resumeMmIdx = $target.data('mm_idx');

    handleResumeCardClick(ruIdx, resumeMmIdx);
  });

  // 채팅 마크 클릭 시 채팅방으로 이동
  $(document).on('click', '.chat_mark', function(e) {
    e.stopPropagation();
    var rmIdx = $(this).data('rm_idx');
    if (rmIdx) {
      location.href = '/chat/chat_view?rm_idx=' + rmIdx + '&from=hr_list';
    }
  });

  $(document).on('click', '.person-grid-item', function(e) {
    if ($(e.target).closest('button, a, input').length) {
      return;
    }

    var $target = $(this);
    var ruIdx = $target.find('.person-card').data('ru_idx');
    var resumeMmIdx = $target.find('.person-card').data('mm_idx');

    handleResumeCardClick(ruIdx, resumeMmIdx);
  });

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
    var isFromHrPost = sessionStorage.getItem('resume_came_from_hr_post');
    if (isFromHrPost !== 'true') {
      return;
    }

    var highlightRuIdx = sessionStorage.getItem('resume_highlight_ru_idx');
    if (!highlightRuIdx) {
      return;
    }

    // 기존 하이라이트 모두 제거
    $('.content-li.highlight-recent').removeClass('highlight-recent');

    // 새 하이라이트 적용
    $('.content-li[data-ru_idx="' + highlightRuIdx + '"]').addClass('highlight-recent');

    // 정리
    sessionStorage.removeItem('resume_came_from_hr_post');
    sessionStorage.removeItem('resume_highlight_ru_idx');
  }


  // 페이지 이동 함수
  // page: 이동할 페이지 번호
  function goPage(page) {
    if (page < 1 || page > total_pages) return; // 유효성 검사 이동가능한 페이지인지 확인
    list_page = page; // 전역 변수 업데이트 현재 페이지를 3으로 바꿈
    saveCurrentPage(); // 현재 페이지를 저장 (pushPageToUrl 포함)
    $(list_id).empty(); //기존 목록 지움
    $("html, body").animate({
      scrollTop: 0 //scrollTop: 0으로 하면 화면 맨 위로 이동
    }, 200); //화면 맨 위로 올림
    getBoardList(""); // 이동할 페이지 글 목록 다시 불러옴
    updateTopSectionsVisibility();
  }

  // 채용공고 작성 버튼 클릭 이벤트 20250923
  // 이벤트 위임 사용 (main_menu.php include 이후에도 동작 보장)
  $(document).off('click', '#btnRecruitList').on('click', '#btnRecruitList', function(e) {
    e.preventDefault();

    // 공통 함수 사용 (main_menu.php에 정의된 전역 함수)
    if (typeof window.handleRecruitPageNavigation === 'function') {
      window.handleRecruitPageNavigation();
    } else {
      // 함수가 아직 로드되지 않은 경우 (안전장치)

      // 로그인 여부 확인
      var user_type = $("[name='mm_type']").val();
      if (user_type != "COMP") {
        commonAlert("로그인 후 이용해주세요.", function() {
          location.href = '/member/login';
        });
        return;
      }

      // 채용공고 개수 확인 후 페이지 이동
      $.ajax({
        url: "/compage_proc/getRecruitCount",
        type: "post",
        dataType: "json",
        success: function(ret) {
          if (ret.code == "00") {
            var recruitCount = ret.data.count || 0;

            // 채용공고가 1개 이상이면 관리 페이지로, 0개이면 등록 페이지로 이동
            if (recruitCount >= 1) {
              location.href = "https://nowjob.co.kr/compage/recruit_list";
            } else {
              location.href = "https://nowjob.co.kr/compage/recruit_write1_1";
            }
          } else {
            commonAlert(ret.msg || "채용공고 개수를 확인할 수 없습니다.", function() {});
          }
        },
        error: function(xhr, status, error) {

          commonAlert("채용공고 개수를 확인할 수 없습니다.", function() {});
        }
      });
    }
  });
</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/resume/index_search2025_new.php'); ?>
<script>
  // 주소 팝업이 독립적으로 작동하도록 페이지 타입 설정
  window.__addrPopupPageType = 'resume';

  // [중복 방지] resume 페이지에서만 한 번만 실행
  window.addEventListener('DOMContentLoaded', function() {
    if (window.__hrListDOMContentLoaded) {
      return;
    }
    window.__hrListDOMContentLoaded = true;

    // 스크롤 차단 방지: 초기 진입 시 html/body overflow 해제
    $('html, body').css({
      overflow: 'auto',
      'overflow-y': 'auto',
      height: 'auto'
    });

    // sessionStorage에서 선택된 지역 복원 (resume 페이지별)
    var pageType = 'resume';
    var storageKey_regions = 'selectedRegions_' + pageType;
    var storageKey_area = 'searchAreaStr_' + pageType;

    var addrNames = [];
    var searchArea = [];
    var savedSelectedRegions = sessionStorage.getItem(storageKey_regions);
    var savedSearchAreaStr = sessionStorage.getItem(storageKey_area);

    if (savedSelectedRegions) {
      try {
        window.selectedRegions = JSON.parse(savedSelectedRegions);
        addrNames = window.selectedRegions.slice(0, 3);
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

    // addrName 설정
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

    // window.selectedRegions 동기화
    if (typeof selectedRegions === 'undefined') {
      window.selectedRegions = addrNames.slice(0, 3);
    }

    // UI 동기화
    if (typeof window.updateSelectedRegionsTags === 'function') {
      window.updateSelectedRegionsTags();
    }

    // 검색 조건 초기화 (index_search2025_new.php의 $(document).ready()에서 이미 복원됨)
    if (typeof window.initCategory === 'function' && typeof window.initTabUi === 'function') {
      window.initCategory();
      window.initTabUi();
    }

    // initLoadData 호출: searchStateLoaded가 true이면 index_search에서 이미 getBoardList를 호출했으므로 건너뜀
    setTimeout(function() {
      if (!searchStateLoaded) {
        initLoadData();
      }
    }, 10);

    // 페이지 나갈 때 현재 페이지 저장 (상세페이지에서 돌아올 때 복원용)
    $(document).on('click', '.content-li, .person-grid-item', function(e) {
      saveCurrentPage();
    });

    // 추천인재 슬라이더 초기화 (서버사이드 렌더링된 데이터 기반)
    // 초기 데이터가 있으면 바로 슬라이더 초기화, 없으면 AJAX로 로드
    var hasInitialReco = <?php echo json_encode($has_initial_reco); ?>;
    if (hasInitialReco) {
      // 서버사이드 렌더링된 데이터 있음: 슬라이더 초기화만 수행
      setTimeout(function() {
        initRecommSlider();
      }, 100);
    } else {
      // 초기 데이터 없음: AJAX로 로드
      getRecoList();
    }

    // iOS 웹뷰 대응: 초기 진입 시 스크롤 복원 (개선 버전)
    // 로드 순서 대비 재시도 로직 포함
    function attemptRestoreScroll() {
      if (typeof forceRestoreBodyScroll === 'function') {
        forceRestoreBodyScroll();
      } else if (typeof restoreBodyScroll === 'function') {
        restoreBodyScroll();
      } else {
        // fallback: 직접 복원
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

        // 스크롤 컨테이너에도 적용
        ['.page-recruit', '.page-content', '#app'].forEach(selector => {
          const $container = $(selector);
          if ($container.length && $container.css('overflow') !== 'hidden') {
            $container.css('-webkit-overflow-scrolling', 'touch');
          }
        });
      }
    }

    // 즉시 시도
    attemptRestoreScroll();

    // iOS 타이밍 이슈 대비 재시도
    setTimeout(attemptRestoreScroll, 50);
    setTimeout(attemptRestoreScroll, 200);

    // 네이티브 광고 제어는 main.js에서 자동으로 초기화되므로 중복 호출 제거
  });

  // 추천인재 데이터 로딩
  function getRecoList() {
    // 사용자 주소 정보 가져오기
    var addrInfo = <?php echo json_encode(isset($addr_info) ? $addr_info : []); ?>;

    var params = {
      "addr1": addrInfo.ad_addr1 || "",
      "addr2": addrInfo.ad_addr2 || "",
      "addr3": addrInfo.ad_addr3 || "",
      "limit": "8"
    };

    $.ajax({
      url: "/resume_proc/search_reco_list2025", // 추천인재 리스트를 가져오는 API 엔드포인트
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code == "00") {
          var list = ret.data.list;
          $("#recomm-list").html("");

          if (list && list.length > 0) {
            var chunked = [];
            for (var i = 0; i < list.length; i += 4) {
              chunked.push(list.slice(i, i + 4));
            }

            chunked.forEach(function(chunk) {
              var $slide = $("<div class='keen-slider__slide'><div class='person-grid'></div></div>");
              var $grid = $slide.find(".person-grid");

              chunk.forEach(function(row) {
                var $clone = $("#tpl_reco_item").clone();
                var $content = $clone.html();

                var myTypeRaw = row.ru_my_type || "";
                if (!myTypeRaw || myTypeRaw.trim() === "") {
                  myTypeRaw = "<p class='tag'>#성실합니다</p>";
                }
                // 국적 처리
                var nationStr = "";
                if (!row.mm_nation || (typeof row.mm_nation === 'string' && row.mm_nation.trim() == '')) {
                  nationStr = "";
                } else {
                  nationStr = row.mm_nation;
                }
                $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || "");
                $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
                $content = $content.replaceAll("{{ru_name}}", row.ru_name || "");
                $content = $content.replaceAll("{{mm_age}}", (row.mm_age || "") + "대");
                $content = $content.replaceAll("{{mm_nation}}", nationStr);
                $content = $content.replaceAll("{{ru_price_type}}", getPriceTypeJSFirst(row.ru_price_type));
                $content = $content.replaceAll("{{ru_work_type}}", getWorkTypeFirst(row.ru_work_type));
                $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img || "");
                $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img || "");
                $content = $content.replaceAll("{{ru_my_type}}", myTypeRaw);
                $content = $content.replaceAll("{{ad_addr}}", row.ad_addr || ""); // 서버에서 이미 변환됨

                $clone.html($content);
                $grid.append($clone.html());
              });

              $("#recomm-list").append($slide);
            });

            initRecommSlider();
          } else {
            $("#recomm-list").html("<p style='text-align:center;padding:20px;color:#999;'>추천인재가 없습니다.</p>");
          }
        } else {
          $("#recomm-list").html("<p style='text-align:center;padding:20px;color:#999;'>데이터를 불러올 수 없습니다.</p>");
        }
      },
      error: function() {
        $("#recomm-list").html("<p style='text-align:center;padding:20px;color:#999;'>데이터를 불러올 수 없습니다.</p>");
      }
    });
  }

  // 추천인재 슬라이더 초기화
  function initRecommSlider() {
    var recommSlider = document.querySelector('.recomm-slider.person .slider-wrap');
    if (recommSlider && typeof KeenSlider !== 'undefined' && !recommSlider.__keenslider__) {
      var slides = recommSlider.querySelectorAll('.keen-slider__slide');
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
                if (imageSrcMap[srcKey]) {
                  img.src = imageSrcMap[srcKey];
                }
                if (img.classList.contains('lozad')) {
                  if (img.getAttribute('data-src') && !img.src) {
                    img.src = img.getAttribute('data-src');
                  }
                  img.setAttribute('data-loaded', 'true');
                }
              });
              recommSlider.appendChild(cloned);
              added++;
            }
          });
        }
      }

      var slider = new KeenSlider(recommSlider, {
        loop: true,
        initial: 0,
        slides: {
          perView: 1.1,
          spacing: 12
        },
        drag: true,
        dragMinDist: 0,
        rubberband: true,
        renderMode: 'precision'
      });

      // dot 인디케이터 생성 및 업데이트
      var dotsContainer = document.getElementById('recommSliderDots');
      if (dotsContainer && actualSlideCount > 1) {
        // 원본 슬라이드 개수만큼 dot 생성
        for (var i = 0; i < actualSlideCount; i++) {
          var dot = document.createElement('div');
          dot.className = 'dot' + (i === 0 ? ' active' : '');
          dot.setAttribute('data-index', i);
          (function(index) {
            dot.addEventListener('click', function(e) {
              e.stopPropagation();
              slider.moveToSlide(index);
            });
          })(i);
          dotsContainer.appendChild(dot);
        }

        // 슬라이더 이동 시 dot 업데이트
        slider.on('slideChanged', function() {
          var currentIdx = ((slider.track.details.abs % actualSlideCount) + actualSlideCount) % actualSlideCount;
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

  // 지역 선택 팝업 적용 시 카테고리 텍스트 숨김
  window.onChangeAreaApply = function() {
    $(".search-option").addClass("hide-cate");
    if (typeof saveSearchStateToStorage === "function") {
      saveSearchStateToStorage();
    }

    // updateSelectedRegionsTags 함수 호출
    if (typeof window.updateSelectedRegionsTags === "function") {
      window.updateSelectedRegionsTags();
    }
  };

  // 1페이지 여부에 따라 배너/슬라이더 섹션 표시/숨김
  function updateTopSectionsVisibility() {
    var isFirstPage = (list_page <= 1);
    $('.main-visual').toggle(isFirstPage);
    $('#recommSection').toggle(isFirstPage);
  }

  // Android 뒤로가기 패널 상태 관리
  window.isAdvancedSearchOpen = function() {
    return $('#advancedSearch').hasClass('on');
  };

  window.isChangeAreaOpen = function() {
    return $('#changeArea').hasClass('on');
  };

  // 패널 닫힐 때 공통 body 스타일 복원 함수 (main.js의 closePanel 호출을 대신함)
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

    // 다른 열린 패널 확인
    var hasOtherOpenPanel = document.querySelector('.layer-panel.on') !== null;
    if (!hasOtherOpenPanel) {
      $('body').removeClass('panel-open');
    }

    // iOS 웹뷰 대응: body 스타일 완전 복원
    if (typeof forceRestoreBodyScroll === 'function') {
      forceRestoreBodyScroll();
    } else if (typeof restoreBodyScroll === 'function') {
      restoreBodyScroll();
    }
  };

  // 인재 상세 팝업 함수들
  window.openHrPostPopup = function(ruIdx) {
    $.ajax({
      url: '/resume/hr_post/' + ruIdx,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).done(function(html) {
      var popup = document.getElementById('hrPostPopup');
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
              console.error('[openHrPostPopup] share-panel.js 로드 실패');
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
      localStorage.setItem('resume_came_from_hr_post', 'true');
      localStorage.setItem('came_from_hr_list', 'true');
      sessionStorage.setItem('resume_highlight_ru_idx', ruIdx);

      // GSAP으로 팝업 열기 애니메이션
      gsap.fromTo('#hrPostPopup',
        {
          x: '100%'
        },
        {
          duration: 0.5,
          x: '0%',
          ease: "power1.out",
          clearProps: "transform",
          onComplete: function() {
            document.getElementById('hrPostPopup').setAttribute('data-state', 'open');
          }
        }
      );

      // 팝업 로드 후 이미지 lazy load 초기화
      if (typeof initLazyLoad === 'function') {
        initLazyLoad();
      }

      // 팝업 로드 후 상세정보 확대/핀치줌 기능 초기화
      if (typeof window.initJobPostDescFeatures === 'function') {
        window.initJobPostDescFeatures();
      }
    });
  };

  window.closeHrPostPopup = function(e) {
    if (e) e.preventDefault();

    // 진행 중인 선택 패널(공고 선택)이 열려 있으면 닫기 (상태 잔존 방지)
    var $selectPanel = $("#selectPanel");
    if ($selectPanel.length > 0) {
      $selectPanel.removeClass("on");
    }

    // GSAP으로 팝업 닫기 애니메이션
    gsap.to('#hrPostPopup', {
      duration: 0.5,
      x: '100%',
      ease: "power1.out",
      onComplete: function() {
        document.getElementById('hrPostPopup').setAttribute('data-state', 'closed');
        $('#hrPostPopup').html('');
      }
    });

    document.body.style.overflow = 'auto';
    document.documentElement.style.overflow = 'auto';
    applyLastClickedHighlight();
  };

  window.isHrPostPopupOpen = function() {
    // GSAP 애니메이션: opacity > 0.5이면 열려있는 것
    return parseFloat($('#hrPostPopup').css('opacity')) > 0.5;
  };
</script>

<!-- 페이지네이션 헬퍼 스크립트 -->
<?= getCashJs(RES_PATH . "/js/pagination-helper.js") ?>
