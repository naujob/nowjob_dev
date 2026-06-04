<?php
header('Location: /recruit/job_list');
exit;

$banner_list = getBannerList("BN00302031");
# IOS 검수 대비
if ($g_ios_publish == true) {
  foreach ($popup_list as $key => $popup) {
    if ($popup["pp_idx"] != "7" && $popup["pp_idx"] != "8") {
      unset($popup_list[$key]);
    }
  }
  foreach ($banner_list as $key => $bb) {
    if ($bb["bd_idx"] == "28" || $bb["bd_idx"] == "26" || $bb["bd_idx"] == "25" || $bb["bd_idx"] == "34" || $bb["bd_idx"] == "32" || $bb["bd_idx"] == "30"  || $bb["bd_idx"] == "29") {
      unset($banner_list[$key]);
    }
  }
}
?>
<!-- 이벤트팝업 -->
<?php if ($popup_list != null) : ?>
  <div id="notice1Panel" class="layer-panel card-type notify" tabindex="-1">
    <div class="panel-container">
      <div class="panel-header">
        <h2 class="sr-text">이벤트 알림</h2>
      </div>
      <div class="panel-body">
        <div class="panel-content">
          <div class="event-banner">
            <div class="notice-slider keen-slider">
              <?php foreach ($popup_list as $popup) : ?>
                <div class="keen-slider__slide">
                  <a href="<?= $popup["pp_link_m"] ?>"><img src="<?= $popup["pp_pc_img"] ?>" alt="<?= $popup["pp_name"] ?>"></a>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <div class="sticky-bottom view-footer">
          <button type="button" aria-controls="notice1Panel" onclick="setCookie('notice1Panel', 'hide', 1);return false;" class="btn btn-secondary js-toggle-panel">오늘 하루 열지 않기</button>
          <button type="button" aria-controls="notice1Panel" class="btn btn-primary js-toggle-panel">확인</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    /* 쿠키추가 */
    function setCookie(cookie_name, value, days) {
      var exdate = new Date();
      var cookie_value;

      exdate.setDate(exdate.getDate() + days);
      cookie_value = escape(value) + ((days == null) ? '' : '; expires =' + exdate.toUTCString());
      document.cookie = cookie_name + '=' + cookie_value;
    }

    /* 쿠키 가져오기 */
    function getCookie(cookie_name) {
      return document.cookie.indexOf(cookie_name);
    }

    /* 쿠키체크 후 팝업 열기 */
    function showNotice(selector, cookie_name) {
      if (getCookie(cookie_name) < 0) {
        var targetElement = document.querySelector(selector);
        if (!targetElement) return;

        // openPanel이 정의되어 있는지 확인
        if (typeof window.openPanel === 'function') {
          openPanel(targetElement);
        } else {
          // openPanel이 아직 로드되지 않았으면 잠시 후 재시도
          var retryCount = 0;
          var maxRetries = 20; // 최대 2초 대기 (20 * 100ms)

          var checkOpenPanel = setInterval(function() {
            retryCount++;
            if (typeof window.openPanel === 'function') {
              clearInterval(checkOpenPanel);
              openPanel(targetElement);
            } else if (retryCount >= maxRetries) {
              clearInterval(checkOpenPanel);
              console.warn('openPanel 함수를 로드할 수 없습니다.');
            }
          }, 100);
        }
      }
    }

    window.addEventListener('DOMContentLoaded', function() {
      var slider = document.querySelector('.event-banner .notice-slider');
      showNotice('#notice1Panel', 'notice1Panel');
      if (typeof KeenSlider !== undefined) {
        new KeenSlider(slider, {
          loop: false,
          defaultAnimation: {
            duration: 1000,
          },
        }, [
          addActiveClass,
          //  sliderDots,
          function autoplay(slider) {
            sliderAutoplay(slider, 4000, false);
          }
        ]);
      }
    });
  </script>
<?php endif; ?>
<!-- //이벤트팝업 -->

<script>
  // function getLocation() {
  //   if (navigator.geolocation) {
  //     navigator.geolocation.getCurrentPosition(function(position) {

  //     }, function(error) {
  //       if (error.message == "User denied Geolocation") {
  //         alert("위치사용을 허용해주세요");
  //       }
  //     }, {
  //       enableHighAccuracy: false,
  //       maximumAge: 0,
  //       timeout: Infinity
  //     });
  //   } else {
  //     alert('GPS를 지원하지 않습니다');
  //   }
  // }
  //getLocation();
</script>
<style>
  /* 추천인재 슬라이더 양쪽 peek — container overflow 재정의 */
  .section.index_comp:has(.recomm-slider) .container {
    overflow: visible;
  }

  .section.index_comp:has(.recomm-slider) {
    overflow: hidden;
    /* 섹션 밖으로는 클리핑 */
  }

  .sr-text {
    display: block;
    position: absolute;
    width: 1px;
    height: 1px;
    margin: 0;
    padding: 0;
    font-size: 1px;
    color: transparent;
    pointer-events: none;
    white-space: nowrap;
    overflow: hidden;
    border: 0;
    outline: 0;
  }

  /* em 태그의 기본 italic 스타일 제거 */
  em {
    font-style: normal;
  }

  .result-list .content-li {
    line-height: 1.5;
    border: 1px solid #EB5349;
  }

  .result-list .content-li:hover {
    background: #f8f8f8;
  }

  .content-info .desc {
    cursor: pointer;
  }

  .content-info .summary p {
    font-weight: 600;
  }

  .content-info .summary2 {
    font-size: 16px;
    font-weight: 600;
    color: #1b9d7d;
  }

  .content-info .summary2 .pricetype {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
  }

  .content-info .summary2 .pricetype em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .content-info .summary2 .pricetype em:not(:last-child) {
    padding-right: 5px;
  }

  .content-info .summary2 .pricetype em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 6px;
    right: 10px;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777777;
  }

  .content-info .summary2 .period em.on {
    color: #1b9d7d;
  }



  .content-info .summary3 p {
    margin: 0;
    text-align: right;
  }

  .content-info .summary3 .worktype {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;

  }

  .content-info .summary3 .worktype em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .content-info .summary3 .worktype em.wtype1 {
    color: #3a70db;
  }

  .content-info .summary3 .worktype em.wtype2 {
    color: #F86048;
  }

  .content-info .summary3 .worktype em.wtype3 {
    color: #1B9D7D;
  }

  .content-info .summary3 .worktype em.wtype0,
  .content-info .summary3 em.wtype0 {
    color: #000;
  }

  .content-info .summary3 .worktype em:not(:last-child) {
    padding-right: 5px;
  }

  .content-info .summary3 .worktype em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777777;
  }

  .content-info .star-rating {
    position: absolute;
    bottom: 5px;
    left: 5px;
    width: 85px;
    height: 15px;
    margin: 5px auto 0;
  }

  .working-day {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin: 0 0 -4px;
    list-style: none;
  }

  .working-day>li {
    width: 1.8em;
    height: 1.8em;
    margin: 0 0.5em 4px 0;
    font-size: 12px;
    color: #BEBFC9;
    line-height: 1.5;
    text-align: center;
    text-indent: -1px;
    border-radius: 100%;
    border: 1px solid #BEBFC9;
    background: #fff;
    float: left;
  }

  .working-day>li:last-child {
    margin-right: 0;
  }

  .working-day li[aria-hidden=false] {
    color: #fff;
    border-color: transparent;
    background-color: #1B9D7D;
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

  .recomm-slider.person .person-card .thumb {}



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
    font-size: 9px;
    flex: 0 0 auto;
  }

  .recomm-slider.person .person-card .addr {
    font-size: 1rem;
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
    font-size: 10px;
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
    margin: 4px 0 0;
    padding: 1px 34px;
    font-size: 0;
    background-color: #3A70DB;
    border-radius: 4px;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    white-space: nowrap;
    border-top: none;
    overflow: hidden;
    line-height: 1.3;
  }

  .person-card .tag {
    display: inline-block;
    margin: 0 3px 0 0;
    padding: 0;
    font-size: 10px;
    color: #fff;
    line-height: 1.2;
    text-align: center;
    vertical-align: top;
    border-radius: 0;
  }

  .person-card .tag:last-child {
    margin-right: 0;
  }

  .person-card .img {
    position: relative;
    width: 175px;
    height: 175px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  /* 그리드 뷰에서 img 고정 크기 무효화 */
  .recomm-slider.person .person-card .img {
    width: auto;
    height: auto;
  }

  .person-card .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* job_list.php에서 필요한 추가 스타일 */
  .page-content {
    background: #fff;
  }

  .current-area .btn {
    max-width: 90%;
  }

  .search-option .categories .cate {
    display: none;
  }

  .search-option .categories .form-toggle-btn2 {
    display: none;
  }

  .search-option .categories .form-toggle-btn2.on {
    display: inline-block;
  }

  .search-option .categories {
    height: auto;
    margin-bottom: 10px;
  }

  .current-area .btn {
    max-width: 100%;
    font-size: 1.8rem;
  }

  .section:has(.hot-list) .container {
    overflow: visible;
  }

  .section:has(.hot-list) {
    overflow: hidden;
  }

  /* Google AdSense 인피드 광고 스타일 */
  .ad-item-adsense {
    list-style: none;
    margin: 0;
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
    display: block;
    background-color: transparent;
  }

  .pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    margin: 20px 0;
    padding: 10px 0;
  }

  .pagination-wrapper button {
    padding: 8px 12px;
    border: 1px solid #ddd;
    background: #fff;
    color: #333;
    cursor: pointer;
    border-radius: 4px;
    font-size: 14px;
  }

  .pagination-wrapper button.active {
    background: #1B9D7D;
    color: #fff;
    border-color: #1B9D7D;
  }

  .pagination-wrapper button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .item-info .thumb {
    width: 5rem;
  }

  .item-info {
    padding: 0.4rem 0;
    margin: 5px 0;
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
    line-height: 1rem;
  }

  .item-info .area {
    max-height: 1.3rem;
    margin-top: 4px;
  }

  .item-info .amount {
    letter-spacing: -2px;
  }
</style>
<!-- Google AdSense 인피드 광고 스크립트 -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3622369584322145"
  crossorigin="anonymous"></script>
<div class="page page-home">

  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span>
     
      <span class="cnt"><?= $g_alarm_cnt ?></span>
    </a> -->

  <style>
    .main-visual .visual-slider .dots {
      bottom: 12%;
    }
  </style>
  <div class="page-content">
    <?php include(APPPATH . 'views/mobile/_partial/main_visual.php'); ?>

    <!-- 지금 뜨는 업체 -->
    <div class="section" id="hotListSection">
      <div class="container">
        <h2 class="secondary-title index">지금 뜨는 업체</h2>
        <div class="hot-list">
          <div class="slider-wrap keen-slider">
            <?php
            $limited_up_list = isset($up_list) && is_array($up_list) ? array_slice($up_list, 0, 8) : array();
            $chunked_up_list = array_chunk($limited_up_list, 4);
            if (!empty($chunked_up_list)) {
              $last_idx = count($chunked_up_list) - 1;
              while (count($chunked_up_list[$last_idx]) < 4) {
                $chunked_up_list[$last_idx][] = null;
              }
            }
            foreach ($chunked_up_list as $chunk) :
            ?>
              <div class="keen-slider__slide">
                <div class="hot-grid">
                  <?php foreach ($chunk as $row) : ?>
                    <?php if ($row !== null) : ?>
                      <a href="/recruit/company/<?= $row["mm_idx"] ?>" class="hot-item">
                        <div class="thumb">
                          <img data-src="<?= getCompImg($row) ?>" alt="" class="lozad">
                          <p class="name"><?= $row["mm_comp_name"] ?></p>
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
        </div>
      </div>
    </div>

    <div class="section index_comp">
      <div class="container">
        <h2 class="secondary-title index">추천인재</h2>
        <div class="recomm-slider person">
          <div class="slider-wrap keen-slider" id="recomm-list">
          </div>
        </div>
      </div>
    </div>

    <!-- 채용공고 목록 섹션 -->
    <div class="section is-last">
      <div class="container">
        <h2 class="secondary-title">채용공고</h2>
        <ol class="result-list result-list-card" id="ajaxList" data-page="1">
        </ol>
        <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
          <p>등록된 정보가 없습니다.</p>
        </div>
        <!-- 페이지네이션 버튼 -->
        <div id="paginationWrapper" class="pagination-wrapper" style="display:none;">
        </div>
      </div>
    </div>

    <div class="footer">
      <p class="name">
        <span class="company-text"><strong>NOW D&I Co., Ltd (주)나우디앤아이</strong></span>
        <button class="toggle-btn" aria-label="회사 정보 열기/닫기">
          <span class="icon-right" style="display:inline;vertical-align:middle;">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
              <polyline points="7,5 13,10 7,15" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" />
            </svg>
          </span>
          <span class="icon-down" style="display:none;vertical-align:middle;">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
              <polyline points="5,7 10,13 15,7" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" />
            </svg>
          </span>
        </button>
      </p>
      <div class="service-provider" style="display:none;">
        <p>
          <span>Representative name : HAN OG CHOI</span><br />
          <span style="font-size: 9px;">Business registration number : 634-88-03093<br />
            Mail order business : 2025-인천연수구-1886<br />
            Address : 14, Songdogukje-daero 286beon-gil, Yeonsu-gu, Incheon<br />
            Business of disseminating job information : J15000202540002<br />
            Tel : 032-833-0018 E-mail : nau2023@naver.com
          </span>
        </p>
        <p>Copyright NOW D&I Co., Ltd all right reserved.</p>
      </div>
    </div>

    <a href="#app" class="btn-top js-goto-top"><span class="sr-text">Top</span></a>

  </div><!-- //page-content -->

  <?php $select_menu = "home"; ?>
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
</div>

<!-- 알림목록 -->
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<?php include_once(get_base_url() . '/application/shop/views/mobile/recruit/index_search.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>

<?php $tpl_id = 'tpl_list'; ?>
<?php include(APPPATH . 'views/mobile/_partial/recruit_card_template.php'); ?>
<template id="tpl_reco_item">
  <div class="person-grid-item">
    <div class="person-card" data-ru_idx="{{ru_idx}}">
      <div class="thumb-wrap">
        <div class="thumb">
          <img src="{{ru_resume_img}}" alt="프로필">

        </div>
        <div class="person-badge-wrap">
          <div class="person-badge-icon">{{ru_work_img}}</div>

        </div>
      </div>
      <div class="desc">
        <div class="content-wrap">

          <div class="meta">
            <p class="work_type">{{ru_work_type}}</p>
            <span class="meta-divider">|</span>
            <p class="age">{{mm_age}}</p>
            <span class="meta-divider">|</span>
            <p class="price_type">{{ru_price_type}}</p>
          </div>
          <p class="addr">{{ad_addr}}</p>
          <!-- <div class="hashtag">
            {{ru_my_type}}
          </div> -->
        </div>
      </div>
    </div>
  </div>
</template>

<input type="hidden" name="resume_yn" value="<?= isset($resume) && $resume != null ? 'Y' : 'N' ?>" />
<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />

<script>
  function iosTest(args) {
    alert("ios_test::" + args);
    return "OK";
  }

  function iosSend() {
    var send_data = {
      action: "poing",
      params: {
        count: 1
      }
    };
    window.webkit.messageHandlers.grant_push.postMessage(send_data);
  }



  // 추천인재 데이터를 가져오는 함수
  function getRecoList() {
    var params = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "8"
    };

    $.ajax({
      url: "/resume_proc/search_reco_list2025",
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
            // 4개씩 청크로 분할 (한 슬라이드 = 2x2 그리드)
            var chunked = [];
            for (var i = 0; i < list.length; i += 4) {
              chunked.push(list.slice(i, i + 4));
            }

            // 각 슬라이드 생성
            chunked.forEach(function(chunk, slideIndex) {
              var $slide = $("<div class='keen-slider__slide'><div class='person-grid'></div></div>");
              var $grid = $slide.find(".person-grid");

              // 슬라이드 내 4개 카드 추가 (2x2)
              chunk.forEach(function(row, itemIndex) {
                var $clone = $("#tpl_reco_item").clone();
                var $content = $clone.html();

                // 뱃지용 근무형태 텍스트 추출
                var workTypeRaw = row.ru_work_type || "";
                var badgeText = "";
                var matchBadge = workTypeRaw.match(/<[^>]+>([^<]+)<\/[^>]+>/);
                if (matchBadge) {
                  badgeText = matchBadge[1];
                } else if (workTypeRaw !== "") {
                  badgeText = workTypeRaw.split(",")[0].trim();
                }

                // 해시태그 기본값 설정
                var myTypeRaw = row.ru_my_type || "";
                if (!myTypeRaw || myTypeRaw.trim() === "") {
                  myTypeRaw = "<p class='tag'style='width:100%;text-align:right;'>#성실합니다</p><p class='tag' style='width:100%;text-align:right;'>#책임감있습니다</p><p class='tag' style='width:100%;text-align:right;'>#근면합니다</p>";
                }

                $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || "");
                $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
                $content = $content.replaceAll("{{ru_name}}", row.ru_name || "");
                $content = $content.replaceAll("{{mm_age}}", (row.mm_age || "") + "대");
                $content = $content.replaceAll("{{ru_price_type}}", row.ru_price_type || "");
                $content = $content.replaceAll("{{ru_work_type}}", workTypeRaw);
                $content = $content.replaceAll("{{ru_work_type_badge}}", badgeText);
                $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img || "");
                $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img || "");
                $content = $content.replaceAll("{{ru_my_type}}", myTypeRaw);
                $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr || ""));

                $clone.html($content);
                $grid.append($clone.html());
              });

              $("#recomm-list").append($slide);
            });

            // 슬라이더 초기화 - DOM 요소 확인 후 실행
            initRecommSlider();
          } else {
            $("#recomm-list").html("<p style='text-align:center; padding:20px; color:#999;'>추천인재가 없습니다.</p>");
          }
        } else {
          $("#recomm-list").html("<p style='text-align:center; padding:20px; color:#999;'>데이터를 불러올 수 없습니다.</p>");
        }
      },
      error: function(xhr, status, error) {
        $("#recomm-list").html("<p style='text-align:center; padding:20px; color:#999;'>데이터를 불러올 수 없습니다.</p>");
      }
    });
  }

  // 추천공고 데이터를 가져오는 함수 (함수명 변경)
  // 추천인재 슬라이더 초기화 함수
  function initRecommSlider() {
    var recommSlider = document.querySelector('.recomm-slider.person .slider-wrap');
    var prevBtn = document.querySelector('.recomm-slider.person .slider-nav-prev');
    var nextBtn = document.querySelector('.recomm-slider.person .slider-nav-next');

    if (recommSlider && typeof KeenSlider !== 'undefined') {
      // 이미 초기화되었으면 스킵
      if (!recommSlider.__keenslider__) {
        // loop peek을 위해 슬라이드가 3개 미만이면 복제
        var slides = recommSlider.querySelectorAll('.keen-slider__slide');
        if (slides.length > 0 && slides.length < 4) {
          var cloneCount = Math.ceil(4 / slides.length) * slides.length;
          var added = slides.length;

          // 이미지 src 매핑 (복제 후 src 손실 방지)
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
                // 복제 후 이미지 처리: data-src → src로 변환 및 data-loaded 설정
                cloned.querySelectorAll('img').forEach(function(img, imgIdx) {
                  var srcKey = slideIdx + '-' + imgIdx;
                  if (imageSrcMap[srcKey]) {
                    img.src = imageSrcMap[srcKey];
                  }
                  // Lozad가 적용된 이미지 처리
                  if (img.classList.contains('lozad')) {
                    // data-src가 있으면 src로 변환
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
            perView: 1.1, // 슬라이드 크기 확대
            spacing: 12,
          },
          drag: true,
          dragMinDist: 0,
          rubberband: true,
          renderMode: 'precision'
        });

        // 이전 버튼 클릭
        if (prevBtn) {
          prevBtn.addEventListener('click', function() {
            slider.prev();
          });
        }

        // 다음 버튼 클릭
        if (nextBtn) {
          nextBtn.addEventListener('click', function() {
            slider.next();
          });
        }
      }
    }
  }

  // 채용공고 목록 관련 전역 변수
  var ad_addr = "<?= isset($addr_info) && isset($addr_info['ad_addr']) ? $addr_info['ad_addr'] : '' ?>";
  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = 1;
  var list_loading = false;
  var search_addr = [];
  var searchAreaStr = "";
  var addrName = "";
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
  tab_list.push({
    df: "근무지역",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "업직종",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "근무형태",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "근무요일",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "급여형태",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "성별",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "나이",
    text: "",
    active: ""
  });

  // URL에서 page 파라미터 읽기
  function getPageFromUrl() {
    var params = new URLSearchParams(window.location.search);
    return parseInt(params.get("page")) || 1;
  }

  // URL에 page 파라미터 반영
  function pushPageToUrl(page) {
    var params = new URLSearchParams(window.location.search);
    params.set("page", page);
    history.pushState({
      page: page
    }, "", "?" + params.toString());
  }

  // 캐싱된 페이지 데이터 초기화
  function initCashingData() {
    list_page = getPageFromUrl();
    getBoardList("");
  }

  // 초기 데이터 로드
  function initLoadData() {
    list_page = getPageFromUrl();
    total_count = 0;
    total_pages = 1;
    setTimeout(function() {
      getBoardList("");
    }, 100);
  }

  // 브라우저 뒤로가기 감지
  window.addEventListener('popstate', function(e) {
    list_page = (e.state && e.state.page) ? e.state.page : getPageFromUrl();
    $(list_id).empty();
    getBoardList("");
  });

  // 공고 목록 가져오기
  function getBoardList(save_yn) {
    if (list_loading) return;

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

    if (save_yn === "Y") {
      $.ajax({
        url: "/recruit_proc/search_save_params2025",
        type: "post",
        data: params,
        traditional: false,
        dataType: "json",
        crossDomain: true,
        success: function(ret) {
          if (ret.code === "00") {}
        },
        error: function(xhr, status, error) {}
      });
    }

    list_loading = true;
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
          total_count = ret.data.total || 0;
          total_pages = Math.ceil(total_count / ITEMS_PER_PAGE) || 1;
          showBoardList(ret.data.list);
          renderPagination();
        }
      },
      error: function(xhr, status, error) {}
    });
  }

  // 데이터 보여주기
  function showBoardList(list) {
    list.forEach(function(row, index) {
      var $clone = $("#tpl_list").clone();
      var $content = $clone.html();

      if (row.pm_idx == null) {
        $content = $content.replaceAll("<div class=\"content-li recomm\"", "<div class=\"content-li\"");
        $content = $content.replaceAll("<p class=\"recomm-badge\"><span>추천</span></p>", "");
      }

      if (row.rc_nation_nm == "무관" || row.rc_nation_nm == "내국인,외국인") {
        $content = $content.replaceAll("{{rc_nation_nm}}", "국적무관");
      } else {
        $content = $content.replaceAll("{{rc_nation_nm}}", row.rc_nation_nm);
      }

      if (row.rc_age_nm == "무관") {
        $content = $content.replaceAll("{{rc_age_nm}}", "나이무관");
      } else {
        $content = $content.replaceAll("{{rc_age_nm}}", row.rc_age_nm);
      }

      $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
      $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
      var rc_name = row.rc_name;
      if (rc_name && rc_name.length > 18) {
        rc_name = rc_name.substring(0, 18) + '...';
      }
      $content = $content.replaceAll("{{rc_name}}", rc_name);
      $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);
      $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img);
      $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type || "");
      $content = $content.replaceAll("{{rc_price_type}}", row.rt_price_type || '');
      $content = $content.replaceAll("{{rc_price_pay}}", row.rt_price_pay || '');
      $clone.html($content);
      $(list_id).append($clone.html());
    });

    if (list.length > 0) {
      insertAdsenseAd();
    }
    showEmpty();
    updateTopSectionsVisibility();
  }

  // Google AdSense 광고 삽입
  function insertAdsenseAd() {
    var adHtml = '<li class="list-item ad-item-adsense" style="position: relative;">' +
      '<div class="adsense-wrapper" style="position: relative;">' +
      '<ins class="adsbygoogle" style="display:block"' +
      ' data-ad-format="fluid"' +
      ' data-ad-layout-key="-hd+j-2v-68+mb"' +
      ' data-ad-client="ca-pub-3622369584322145"' +
      ' data-ad-slot="5890695133"></ins>' +
      '</div>' +
      '</li>';
    $(list_id).append(adHtml);

    setTimeout(function() {
      try {
        (adsbygoogle = window.adsbygoogle || []).push({});
      } catch (e) {}
    }, 100);
  }

  function showEmpty() {
    if ($(list_id).find("li").length == 0) {
      $(list_empty_id).show();
      $(list_id).hide();
    } else {
      $(list_id).show();
      $(list_empty_id).hide();
    }
  }

  // 페이지네이션 렌더링
  function renderPagination() {
    var $wrapper = $("#paginationWrapper");
    if (total_count === 0) {
      $wrapper.hide();
      return;
    }

    var html = '';
    html += '<button onclick="goPage(' + (list_page - 1) + ')"' +
      (list_page <= 1 ? ' disabled' : '') + '>◀ 이전</button>';

    var startPage = Math.max(1, list_page - 2);
    var endPage = Math.min(total_pages, startPage + 4);
    startPage = Math.max(1, endPage - 4);

    for (var i = startPage; i <= endPage; i++) {
      html += '<button onclick="goPage(' + i + ')"' +
        (i === list_page ? ' class="active"' : '') + '>' + i + '</button>';
    }

    html += '<button onclick="goPage(' + (list_page + 1) + ')"' +
      (list_page >= total_pages ? ' disabled' : '') + '>다음 ▶</button>';

    $wrapper.html(html).show();
  }

  // 페이지 이동
  function goPage(page) {
    if (page < 1 || page > total_pages) return;
    list_page = page;
    pushPageToUrl(page);
    $(list_id).empty();
    $("html, body").animate({
      scrollTop: 0
    }, 200);
    getBoardList("");
    updateTopSectionsVisibility();
  }

  // 1페이지 여부에 따라 배너/슬라이더 섹션 표시/숨김
  function updateTopSectionsVisibility() {
    var isFirstPage = (list_page <= 1);
    $('.main-visual').toggle(isFirstPage);
    $('#hotListSection').toggle(isFirstPage);
  }


  // 지금 뜨는 업체 슬라이더 초기화
  function initHotSlider() {
    var hotSlider = document.querySelector('.hot-list .slider-wrap');
    if (hotSlider && typeof KeenSlider !== 'undefined') {
      if (!hotSlider.__keenslider__) {
        var slides = hotSlider.querySelectorAll('.keen-slider__slide');
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
        new KeenSlider(hotSlider, {
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
      }
    }
  }

  // .btn-category 클릭 이벤트
  $(document).on("click", ".btn-category", function() {
    var categoryValue = $(this).find("input[name='filter']").val();
    if (search_array.category) {
      var categories = search_array.category.split("^");
      categories = categories.filter(function(cat) {
        return cat !== categoryValue;
      });
      search_array.category = categories.join("^");
    }
    tab_list[0].text = search_array.category ? search_array.category.replaceAll("^", ",") : "";
    tab_list[0].active = search_array.category ? "on" : "";
    initTabUi();
    list_page = 1;
    $(list_id).html("");
    getBoardList("Y");
  });

  // 지역 선택 팝업 적용 시 카테고리 텍스트 숨김
  window.onChangeAreaApply = function() {
    $(".search-option").addClass("hide-cate");
    if (typeof saveSearchStateToStorage === "function") {
      saveSearchStateToStorage();
    }
  };

  // 공고 카드 클릭
  $(document).on('click', '.content-li', function(e) {
    if ($(e.target).closest('button, a, input').length) return;
    if ($(e.target).closest('.ad-item-adsense').length) return;
    var rcIdx = $(this).data('rc_idx');
    if (rcIdx) {
      window.location.href = '/recruit/job_post/' + rcIdx;
    }
  });

  window.DISABLE_SWIPE_BACK = true;

  window.addEventListener('DOMContentLoaded', function() {
    // Android 앱에 회원 유형 전달 (기업회원)
    if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK && typeof APP_NOWAPK.setMemberType === 'function') {
      APP_NOWAPK.setMemberType('comp');
    }

    // 스크롤 강제 활성화
    $('html, body').css({
      overflow: 'auto',
      'overflow-y': 'auto',
      height: 'auto'
    });

    // 지역 정보 초기화
    var addrNames = [];
    if ($('.selbtn').length > 0) {
      $('.selbtn').each(function() {
        var text = $(this).clone().children().remove().end().text().trim();
        addrNames.push(text);
      });
      addrName = addrNames.join(', ');
      $('.addrName').text(addrName).attr('data-selected', 'true');
    } else {
      addrName = '지역을 선택해 주세요';
      $('.addrName').text(addrName).attr('data-selected', 'false');
    }
    var searchArea = [];
    search_addr = [];
    $('.selbtn').each(function() {
      var addr = $(this).data('addr');
      var step = $(this).data('step');
      if (addr) {
        searchArea.push({
          addr: addr,
          step: step
        });
        search_addr.push(addr);
      }
    });
    searchAreaStr = JSON.stringify(searchArea);

    if (typeof selectedRegions === 'undefined') {
      window.selectedRegions = [];
    }
    if (addrNames.length > 0) {
      window.selectedRegions = addrNames.slice(0, 3);
    } else {
      window.selectedRegions = [];
    }

    initCategory();
    initTabUi();

    // 추천인재 데이터 로딩
    getRecoList();

    // $.CashingAjax로 채용공고 목록 로딩
    var params = {
      cashing_id: "home_recruit_cashing_comp",
      list_id: list_id,
      category_list_id: "#tab_list",
      scroll_obj: $(".page-home"),
      fn_cashing: initCashingData,
      fn_load: initLoadData,
    };
    $.CashingAjax(params);

    // iOS 스크롤 복원
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

        ['.page-home', '.page-content', '#app'].forEach(selector => {
          const $container = $(selector);
          if ($container.length && $container.css('overflow') !== 'hidden') {
            $container.css('-webkit-overflow-scrolling', 'touch');
          }
        });
      }
    }

    attemptRestoreScroll();
    setTimeout(attemptRestoreScroll, 50);
    setTimeout(attemptRestoreScroll, 200);

    // 지금 뜨는 업체 슬라이더 초기화
    initHotSlider();

    $('.footer .toggle-btn').on('click', function() {
      var $serviceProvider = $('.footer .service-provider');
      var $iconRight = $(this).find('.icon-right');
      var $iconDown = $(this).find('.icon-down');
      if ($serviceProvider.hasClass('show')) {
        $serviceProvider.removeClass('show').hide();
        $iconRight.show();
        $iconDown.hide();
      } else {
        $serviceProvider.addClass('show').show();
        $iconRight.hide();
        $iconDown.show();
      }
    });

  });

  // 추천인재 클릭 이벤트
  $(document).on('click', '.person-card', function() {
    var ruIdx = $(this).data('ru_idx');
    if (ruIdx) {
      var url = '/resume/hr_post/' + ruIdx;
      window.location.href = url;
    }
  });
</script>

<?= showPopupMobile() ?>