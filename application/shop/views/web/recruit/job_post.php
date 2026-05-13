<?php
function convertHashtagString($input)
{
  //echo "1:".$input."<br>";
  // 1. 해시태그(#) 제거 및 앞뒤 공백 제거
  $cleaned = preg_replace('/#/', '', $input);

  //echo "2:".$cleaned."<br>";
  // 2. 쉼표(,)를 기준으로 분할 및 공백 제거
  $words = array_map('trim', explode(',', $cleaned));

  //echo "3:".$words."<br>";
  // 3. 빈 요소 제거 (공백 또는 잘못된 쉼표 처리)
  $words = array_filter($words, function ($word) {
    return !empty($word);
  });

  // 4. 변환된 문자열 반환
  $rtn = implode(', ', $words);
  //echo "4:".$rtn."<br>";

  $rtn = str_replace(' ', ', ', $rtn);
  //echo "5:".$rtn."<br>";

  return $rtn;
}

function formatPhoneNumber($number)
{
  $number = preg_replace('/[^0-9]/', '', $number);

  if (preg_match('/^01[016789]\d{7,8}$/', $number)) {
    return preg_replace('/^(\d{3})(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
  }

  if (preg_match('/^02\d{7,8}$/', $number)) {
    return preg_replace('/^(02)(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
  }

  if (preg_match('/^0[3-9]{1}[0-9]{1}\d{7,8}$/', $number)) {
    return preg_replace('/^(0\d{2})(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
  }

  if (preg_match('/^(15|16|18)\d{6}$/', $number)) {
    return preg_replace('/^(\d{4})(\d{4})$/', '$1-$2', $number);
  }

  return $number;
}

// function getWorkType2025($work_type)
// {
//   if (!$work_type) {
//     return "";
//   }

//   $items = [];

//   if (preg_match_all('/<span>(.*?)<\/span>/', $work_type, $matches)) {
//     $items = $matches[1];
//   } else {
//     $items = preg_split('/\s*,\s*/', $work_type);
//   }

//   $item_set = array_unique(array_map('trim', $items));

//   $has_jugan = in_array('주간', $item_set);
//   $has_yagan = in_array('야간', $item_set);
//   $has_2shift = in_array('2교대', $item_set);

//   if ($has_jugan && $has_yagan && $has_2shift) {
//     return '<em class="wtype0">근무협의</em>';
//   }

//   $result = '';
//   foreach ($item_set as $text) {
//     if ($text === '') continue;

//     $cls = '';
//     if ($text === '주간') {
//       $cls = 'wtype1';
//     } else if ($text === '야간') {
//       $cls = 'wtype2';
//     } else if ($text === '2교대') {
//       $cls = 'wtype3';
//     }

//     $result .= '<em class="' . $cls . '">' . $text . '</em>';
//   }

//   return $result;
// }

// function getConvertPriceType($rc_price_type, $rc_price_rep)
// {
//   $allTypes = ['일급', '주급', '월급'];

//   // 모든 급여 유형이 포함된 경우
//   $containsAll = true;
//   foreach ($allTypes as $type) {
//     if (strpos($rc_price_type, $type) === false) {
//       $containsAll = false;
//       break;
//     }
//   }

//   if ($containsAll) {
//     return '<em>급여협의</em>';
//   }

//   // 쉼표로 분리
//   $types = preg_split('/\s*,\s*/', $rc_price_type);

//   if (count($types) === 1) {
//     return '<em class="on">' . $types[0] . '</em>';
//   }

//   // 다중 유형 처리
//   $result = '';
//   foreach ($types as $type) {
//     $type = trim($type);
//     if ($type === $rc_price_rep) {
//       $result .= '<em class="on">' . $type . '</em>';
//     } else {
//       $result .= '<em>' . $type . '</em>';
//     }
//   }

//   return $result;
// }

$app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
$job_posting_title = !empty($info["rc_name"]) ? $info["rc_name"] : "내 주변 일자리 - 채용공고 확인하기";
$job_posting_url = $app_base_url . '/recruit/job_post/' . (!empty($info["rc_idx"]) ? $info["rc_idx"] : '');
$job_posting_desc = "나우잡에서 당신의 집 주변 딱 맞는 일자리를 지금 바로 확인하세요!";
$job_posting_company = !empty($mem_info["mm_comp_name"]) ? $mem_info["mm_comp_name"] : "나우잡";
$job_posting_date_raw = !empty($info["reg_dt"]) ? $info["reg_dt"] : (!empty($info["rc_sdate"]) ? $info["rc_sdate"] : date('Y-m-d'));
$job_posting_date_ts = strtotime($job_posting_date_raw);
$job_posting_date = $job_posting_date_ts ? date('Y-m-d', $job_posting_date_ts) : date('Y-m-d');

// fRemoveBigAdr PHP 함수 제거 - JavaScript의 fRemoveBigAdrJS 함수 사용 (resource/shop/web/js/add.js)
?>
<?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'production') : ?>
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "JobPosting",
      "title": <?= json_encode($job_posting_title, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      "url": <?= json_encode($job_posting_url, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      "description": <?= json_encode($job_posting_desc, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      "datePosted": "<?= $job_posting_date ?>",
      "hiringOrganization": {
        "@type": "Organization",
        "name": <?= json_encode($job_posting_company, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
        "sameAs": "<?= $app_base_url ?>",
        "logo": "<?= $app_base_url ?>/resource/shop/web/img/nowjob_info_logo.png"
      },
      "jobLocation": {
        "@type": "Place",
        "address": {
          "@type": "PostalAddress",
          "addressLocality": "전국",
          "addressRegion": "스마트 매칭"
        }
      }
    }
  </script>
<?php endif; ?>
<style>
  .adBanner {
    position: relative;
    margin-bottom: 15px;
  }

  .adBanner:after {
    content: "AD";
    display: block;
    position: absolute;
    top: 5px;
    left: 5px;
    padding: 0 6px;
    font-size: 10px;
    color: #fff;
    line-height: 14px;
    background: #ddd;
    border-radius: 2px;
    z-index: 1;
  }

  .adBanner.adBanner3 {
    width: 970px;
    height: 400px;
    margin-top: 10px;
  }

  .item-view .info-table .category-label {
    height: 26px;
  }

  .info-table .time {
    display: block;
  }

  .info-table .pay {
    align-items: center;
  }

  .info-table .pay span {
    display: flex;
    gap: 5px;
    align-items: center;
  }

  .info-table .pay span em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #777;
  }

  .info-table .pay span em:not(:last-child) {
    padding-right: 5px;
  }

  .info-table .pay span em.on {
    color: #1b9d7d;
  }

  .info-table .pay span em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777;
  }

  .hashtag {
    margin-top: 30px;
  }

  .summary-card .meta {
    display: flex;
    gap: 5px;
    justify-content: flex-start;
    position: relative;
    font-size: 21px;
    font-weight: 600;
    margin: 16px 0 0;
    color: #777;
  }

  .summary-card .meta em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #777;
  }

  .summary-card .meta em:not(:last-child) {
    padding-right: 5px;
  }

  .summary-card .meta em.on {
    color: #1b9d7d;
  }

  .summary-card .meta em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777;
  }

  .recent-history .history-body {
    padding: 10px 5px;
  }

  .recent-history .keen-slider__slide {
    border: 0px;
  }

  .recent-history .recently-slider {
    height: 620px;
  }

  .history-body .content-li {
    position: relative;
    width: 333px;
    height: 95px;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 10px;
    border: 1px solid #57CCAF;
    overflow: hidden;
    background: #fff;
    margin-bottom: 10px;
    transition: background 0.2s ease;
    cursor: pointer;
  }

  .history-body .content-li:hover {
    background: #f8f8f8;
  }

  .history-body .content-li * {
    padding: 0;
    margin: 0;
  }

  .history-body .content-li .content-info {
    width: 100%;
    height: 100%;
    padding: 5px;
  }

  .history-body .content-info .wicon img {
    height: 18px;
  }

  .history-body .content-info .title {
    font-size: 15px;
    font-weight: bold;
    line-height: 25px;
  }

  .history-body .content-info .content-body {
    position: relative;
    overflow: hidden;
  }

  .history-body .content-info .desc {
    position: relative;
    height: 84px;
    margin: 0 0 0 90px;
    display: flex;
    flex-direction: column;
  }

  .history-body .content-info .desc .ellipsis {
    width: 210px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .history-body .content-info .img {
    position: absolute;
    width: 84px;
    height: 84px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  .history-body .content-info .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .history-body .content-info .link {
    color: #5085BB;
  }

  .history-body .content-info .summary {
    display: flex;
    font-size: 14px;
    gap: 5px;
    position: relative;
  }

  .history-body .content-info .summary p {
    position: relative;
    padding-right: 5px;
    margin: 0;
    color: #999;
  }

  .history-body .content-info .summary p:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #ACACAC;
  }

  .history-body .content-info .summary>* {
    min-width: 0;
    max-width: 100%;
    margin-right: 4px;
    margin-bottom: -3px;
  }

  .history-body .content-info .summary.area p {
    color: #000;
    font-weight: 600;
  }

  .history-body .content-info .summary.reco p {
    color: #1b9d7d;
    font-weight: 600;
  }

  .history-body .content-info .summary2 {
    position: absolute;
    right: 2px;
    top: 38px;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 0;
    color: #777;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }

  .history-body .content-info .summary2 p {
    margin: 0;
    text-align: right;
  }

  .history-body .content-info .summary2 .period {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;
  }

  .history-body .content-info .summary2 .amount {
    color: #1b9d7d;
  }

  .history-body .content-info .summary2 .amount em {
    font-style: normal;
  }

  .history-body .content-info .summary2 .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .history-body .content-info .summary2 .period em:not(:last-child) {
    padding-right: 5px;
  }

  .history-body .content-info .summary2 .period em.on {
    color: #1b9d7d;
  }

  .history-body .content-info .summary2 .period em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777;
  }

  .history-body .content-info .summary3 {
    position: absolute;
    top: 0px;
    right: 2px;
    color: #000;
    width: auto;
    height: 16px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
  }

  .history-body .content-info .summary3 .wtype {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;
  }

  .history-body .content-info .summary3 .wtype em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #333;
  }

  .history-body .content-info .summary3 .wtype em:not(:last-child) {
    padding-right: 5px;
  }



  .history-body .content-info .summary3 .wtype em.wtype1 {
    color: #3a70db;
  }

  .history-body .content-info .summary3 .wtype em.wtype2 {
    color: #F86048;
  }

  .history-body .content-info .summary3 .wtype em.wtype3 {
    color: #1B9D7D;
  }

  /* 기업회원이 자신의 채용공고를 볼 때 btn-request:before 비활성화 */
  .item-view.recruit .view-aside .sticky-group.own-recruit .btn-request:before {
    display: none;
    content: none;
  }

  /* 기업회원이 자신의 채용공고를 볼 때 광고신청 버튼만 배경색 변경 */
  .item-view.recruit .view-aside .sticky-group.own-recruit #btnPromotionRecruit {
    background-color: #1B9D7D;
  }

  /* 기업회원이 자신의 채용공고를 볼 때: 지급정보와 타이틀 간격 늘리기 */
  .item-view.recruit .view-aside .sticky-group.own-recruit .summary-card .amount {
    margin-bottom: 20px;
  }

  .item-view.recruit .view-aside .sticky-group.own-recruit .summary-card .title {
    margin-top: 20px;
  }

  /* 기업회원이 자신의 채용공고를 볼 때: 수정 버튼 스타일 */
  .item-view.recruit .view-aside .sticky-group.own-recruit #btnEditRecruit {
    margin-bottom: -12px;
    background-image: url('/resource/shop/web/img/reviseBtn.svg');
    background-repeat: no-repeat;
    background-position: 77px center;
    background-size: 35px 35px;
    padding-left: 35px;
  }

  /* 기업회원이 자신의 채용공고를 볼 때: 광고신청 버튼 배경 이미지 */
  .item-view.recruit .view-aside .sticky-group.own-recruit #btnPromotionRecruit {
    background-image: url('/resource/shop/web/img/adBtn.svg');
    background-repeat: no-repeat;
    background-position: 77px center;
    background-size: 35px 35px;
    padding-left: 45px;
  }

  /* 근무요일 스타일 */
  .item-view.recruit .view-content .working-day.week-display-36>li {
    width: 36px;
    height: 36px;
    line-height: 36px;
    font-size: 18px;
  }

  .content-info .worktype em.wtype1 {
    color: #3a70db;
    padding-right: 4px;
  }

  .content-info .worktype em.wtype2 {
    color: #F86048;
    padding-left: 4px;
  }

  .content-info .worktype em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .content-info .worktype em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777;
  }



  /* 길찾기 드롭다운 */
  .map-nav-wrap {
    display: inline-block;
    position: absolute;
    top: 122px;
    right: 7px;
  }

  .map-nav-wrap .dropdown {
    right: 0;
    left: auto;
    min-width: 110px;
    margin-top: 4px;
    padding: 4px 0;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
  }

  .map-nav-wrap .dropdown a {
    display: block;
    padding: 8px 14px;
    font-size: 13px;
    color: #333;
    text-decoration: none;
    white-space: nowrap;
  }

  .map-nav-wrap .dropdown a:hover {
    background: #f5f5f5;
    color: #1B9D7D;
  }

  .recruit-card .content-wrap .meta p:not(:first-child):after {
    content: "";
    display: block;
    position: absolute;
    top: 50%;
    right: 0;
    width: 1px;
    height: 10px;
    margin: -4px 0 0;
    background: #000000;
  }
</style>
<input type="hidden" name="rc_idx" value="<?= $info["rc_idx"] ?>" />
<div id="content" class="page page-view">
  <main class="page-content">
    <div class="item-view recruit">
      <div class="container">
        <!-- 컨텐츠 영역 -->
        <div class="view-content">
          <div class="view-visual">
            <div class="inline-group">
              <div class="visual-slider keen-slider">
                <?php if ($info["rc_img1"] != "") : ?>
                  <div class="keen-slider__slide">
                    <img data-src="<?= $info["rc_img1"] ?>" alt="" class="lozad">
                  </div>
                <?php endif; ?>
                <?php if ($info["rc_img2"] != "") : ?>
                  <div class="keen-slider__slide">
                    <img data-src="<?= $info["rc_img2"] ?>" alt="" class="lozad">
                  </div>
                <?php endif; ?>
                <?php if ($info["rc_img3"] != "") : ?>
                  <div class="keen-slider__slide">
                    <img data-src="<?= $info["rc_img3"] ?>" alt="" class="lozad">
                  </div>
                <?php endif; ?>
                <?php if ($info["rc_img4"] != "") : ?>
                  <div class="keen-slider__slide">
                    <img data-src="<?= $info["rc_img4"] ?>" alt="" class="lozad">
                  </div>
                <?php endif; ?>
                <?php if ($info["rc_img5"] != "") : ?>
                  <div class="keen-slider__slide">
                    <img data-src="<?= $info["rc_img5"] ?>" alt="" class="lozad">
                  </div>
                <?php endif; ?>

                <?php if ($info["rc_img99"] != "") : ?>
                  <div class="keen-slider__slide">
                    <img data-src="<?= $info["rc_img99"] ?>" alt="" class="lozad">
                  </div>
                <?php endif; ?>
              </div>
              <div class="thumb-slider">
                <div class="slider-wrap keen-slider">
                  <?php if ($info["rc_img1"] != "") : ?>
                    <div class="keen-slider__slide">
                      <img data-src="<?= $info["rc_img1"] ?>" alt="" class="lozad">
                    </div>
                  <?php endif; ?>
                  <?php if ($info["rc_img2"] != "") : ?>
                    <div class="keen-slider__slide">
                      <img data-src="<?= $info["rc_img2"] ?>" alt="" class="lozad">
                    </div>
                  <?php endif; ?>
                  <?php if ($info["rc_img3"] != "") : ?>
                    <div class="keen-slider__slide">
                      <img data-src="<?= $info["rc_img3"] ?>" alt="" class="lozad">
                    </div>
                  <?php endif; ?>
                  <?php if ($info["rc_img4"] != "") : ?>
                    <div class="keen-slider__slide">
                      <img data-src="<?= $info["rc_img4"] ?>" alt="" class="lozad">
                    </div>
                  <?php endif; ?>
                  <?php if ($info["rc_img5"] != "") : ?>
                    <div class="keen-slider__slide">
                      <img data-src="<?= $info["rc_img5"] ?>" alt="" class="lozad">
                    </div>
                  <?php endif; ?>
                  <?php if ($info["rc_img99"] != "") : ?>
                    <div class="keen-slider__slide">
                      <img data-src="<?= $info["rc_img99"] ?>" alt="" class="lozad">
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <a href="javascript:void(0);" class="bigsize"><img src="/resource/shop/web/img/bigsize.png" /></a>
            <script>
              function ThumbnailPlugin(main) {
                return function(slider) {
                  function removeActive() {
                    slider.slides.forEach(function(slide) {
                      slide.classList.remove('active')
                    });
                  }

                  function addActive(idx) {
                    slider.slides[idx].classList.add('active');
                  }

                  function addClickEvents() {
                    slider.slides.forEach(function(slide, idx) {
                      slide.addEventListener('click', function() {
                        main.moveToIdx(idx);
                      });
                    })
                  }

                  slider.on('created', function() {
                    addActive(slider.track.details.rel);
                    addClickEvents();
                    main.on('animationStarted', function(main) {
                      removeActive();
                      var next = main.animator.targetIdx || 0;
                      addActive(main.track.absToRel(next));
                      slider.moveToIdx(Math.min(slider.track.details.maxIdx, next));
                    });
                  });
                };
              }

              window.addEventListener('DOMContentLoaded', function() {
                if (typeof KeenSlider === 'undefined') return;

                var mslider = new KeenSlider('.visual-slider', {
                  loop: true,
                  defaultAnimation: {
                    duration: 100,
                  },
                  drag: false,
                  detailsChanged: function(s) {
                    s.slides.forEach(function(element, idx) {
                      element.style.opacity = s.track.details.slides[idx].portion;
                    });
                  },
                  renderMode: 'custom',
                }, [addActiveClass]);
                var thumbnails = new KeenSlider(".thumb-slider .slider-wrap", {
                    initial: 0,
                    slides: {
                      perView: 4,
                    },
                    vertical: true,
                    drag: false,
                  },
                  [ThumbnailPlugin(mslider), sliderArrows]
                );
              });
            </script>
          </div>

          <div class="section view-header">
            <div class="view-meta">
              <div class="inline-group">
                <!-- 작성일 -->
                <p class="date"><?= $info["rc_idx"] ?></p>
                <!-- 총 즐겨찾기수 -->
                <p class="total-bookmark">
                  <span class="sr-text">총 즐겨찾기수</span>
                  <em><?= $info["fav_cnt"] ?></em>
                </p>
                <!-- 조회수 -->
                <p class="total-view">
                  <span class="sr-text">조회수</span>
                  <em><?= $info["rc_hit"] ?></em>
                </p>
              </div>
            </div>

            <div class="title-wrap">
              <!-- 제목 -->
              <h2 class="view-title"><?= $info["rc_name"] ?></h2>
              <!-- 모집기간 -->
              <p class="recruit-period">
                <span>모집기간</span>
                <em><?= get_yyyymmdd($info["rc_sdate"]) ?> ~ <?= get_yyyymmdd($info["rc_edate"]) ?></em>
              </p>
              <!-- 신고하기 -->
              <button type="button" aria-haspopup="dialog" aria-controls="reportForm1" class="btn-report js-report"><span class="sr-text">신고하기</span></button>
            </div>

            <!-- 신고하기 양식 -->
            <template id="ReportTemp">
              <div class="alert-msg">
                <p>해당 게시글을 신고하시겠습니까?<br> 신고사유를 작성해주세요</p>
                <div class="report-form">
                  <label>
                    <span class="sr-text">신고사유입력</span>
                    <textarea name="br_desc" placeholder="신고사유" autofocus class="form-textarea js-autoheight"></textarea>
                  </label>
                </div>
              </div>
            </template>
            <!-- //신고하기 양식 -->

            <script>
              /* 신고내용 작성 팝업 */
              window.addEventListener('DOMContentLoaded', function() {
                document.querySelector('.js-report').addEventListener('click', function() {
                  var content = document.querySelector('#ReportTemp').innerHTML;
                  openPopup('modal', {
                    id: 'reportForm',
                    title: '신고하기',
                    content: content,
                    firstButton: '확인',
                    firstCallback: function() {
                      if ($("[name=br_desc]").val() == "") {
                        commonAlert("내용을 입력해주세요.", function() {
                          $("[name=br_desc]").focus();
                        });
                        return false;
                      }
                      var params = {};
                      params["bd_idx"] = $("[name=rc_idx]").val();
                      params["br_type"] = "recruit";
                      params["br_desc"] = $("[name=br_desc]").val();
                      comAjax("/setting_proc/board_report", params, function(ret) {
                        if (ret.code == "00") {
                          removeElement("#ReportWriter1");
                          commonAlert("신고 처리 되었습니다.", function() {});
                        } else {
                          commonAlert("신고 처리시 오류가 발생하였습니다.", function() {});
                        }
                      });
                    }
                  });
                });
              });
            </script>

            <div class="summary">
              <h3 class="secondary-title">구인정보</h3>
              <table class="info-table">
                <caption><span class="sr-text">구인정보</span></caption>
                <colgroup>
                  <col>
                  <col>
                  <col>
                  <col>
                </colgroup>
                <tbody>
                  <tr>
                    <td class="label">업무구분</td>
                    <td class="value"><?= getWorkingImg($info["rc_work"]) ?></td>
                    <td class="label">희망국적</td>
                    <td class="value"><?= $info["rc_nation_nm"] ?></td>
                  </tr>


                  <tr>
                    <td class="label">근무형태</td>
                    <td class="value txt-color2"><?= getWorkType2025($info["rc_work_type"]) ?></td>


                    <td class="label">희망성별</td>
                    <td class="value"><?= $info["rc_sex_nm"] ?></td>
                  </tr>
                  <tr>
                    <td class="label">급&nbsp;&nbsp;&nbsp;&nbsp;여</td>
                    <td class="value txt-color2">
                      <span class="pay">
                        <?php if (strpos($info["rc_price_type"], "협의") !== false) { ?>
                          <em class="amount">급여협의</em>
                        <?php } else {
                          // getConvertPriceType 함수를 사용하여 job_list.php와 동일한 형식으로 표시
                          $rc_price_rep = isset($info["rc_price_rep"]) ? $info["rc_price_rep"] : "";
                          $price_type_html = getConvertPriceType($info["rc_price_type"], $rc_price_rep);
                        ?>
                          <em class="amount"><?= number_format($info["rc_price_pay"]) ?> 원</em>
                          <span>(<?= $price_type_html ?>)</span>
                        <?php } ?>
                      </span>
                    </td>

                    <td class="label">희망나이</td>
                    <td class="value">
                      <?php if ($info["rc_sage"] > 0 || $info["rc_eage"] > 0) : ?>
                        <?= $info["rc_sage"] ?>세 ~ <?= $info["rc_eage"] ?>세
                      <?php else : ?>
                        나이무관
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php /* 근무시간 표시 주석처리
                  <tr>
                    <td class="label" rowspan="2">근무시간</td>
                    <td class="value">
                      <?php if ($info["rc_am_stime"] == "" && $info["rc_pm_stime"] == "") : ?>
                        -
                      <?php else : ?>
                        <?php if (strpos($info["rc_work_type"], "2교대") !== false) : ?>
                          <?php if ($info["rc_am_stime"] != "" && $info["rc_am_etime"]) : ?>
                            <span class="time"><?= $info["rc_am_stime"] ?>~<?= $info["rc_am_etime"] ?>(주간)</span>
                          <?php else : ?>
                            -
                          <?php endif; ?>
                        <?php endif; ?>

                        <?php if (strpos($info["rc_work_type"], "주간") !== false && strpos($info["rc_work_type"], "2교대") === false) :  ?>
                          <?php if ($info["rc_am_stime"] != "" && $info["rc_am_etime"]) : ?>
                            <span class="time"><?= $info["rc_am_stime"] ?>~<?= $info["rc_am_etime"] ?>(주간)</span>
                          <?php else : ?>
                            -
                          <?php endif; ?>
                        <?php endif; ?>
                      <?php endif; ?>
                    </td>
                    <td class="label">근무날짜</td>
                    <td class="value">
                      <?= ($info["rc_work_dt"] == "0000-00-00 00:00:00" ? "근무시작일 무관" : date('Y년 n월 j일', strtotime($info["rc_work_dt"]))) ?>
                    </td>
                  </tr>
                  <tr>
                    <td class="value">
                      <?php if ($info["rc_am_stime"] == "" && $info["rc_pm_stime"] == "") : ?>
                      <?php else : ?>
                        <?php if (strpos($info["rc_work_type"], "2교대") !== false) : ?>
                          <?php if ($info["rc_pm_stime"] != "" && $info["rc_pm_etime"]) : ?>
                            <span class="time"><?= $info["rc_pm_stime"] ?>~<?= $info["rc_pm_etime"] ?>(야간)</span>
                          <?php else : ?>
                            -
                          <?php endif; ?>
                        <?php endif; ?>

                        <?php if (strpos($info["rc_work_type"], "야간") !== false && strpos($info["rc_work_type"], "2교대") === false) :  ?>
                          <?php if ($info["rc_pm_stime"] != "" && $info["rc_pm_etime"]) : ?>
                            <span class="time"><?= $info["rc_pm_stime"] ?>~<?= $info["rc_pm_etime"] ?>(야간)</span>
                          <?php else : ?>
                            -
                          <?php endif; ?>
                        <?php endif; ?>
                      <?php endif; ?>
                    </td>
                    <td class="label">근무요일</td>
                    <td class="value">
                      <?php
                      $isWeekUnlimited = (isset($info["rc_week_yn"]) && $info["rc_week_yn"] === "Y");
                      $hasSavedWeek = trim((string) $info["rc_week"]) !== "";
                      $weekDisplaySource = $hasSavedWeek ? $info["rc_week"] : "월,화,수,목,금"; // 간편등록 기본값을 그대로 노출
                      ?>
                      <?php if ($isWeekUnlimited) { ?>
                        무관
                      <?php } else { ?>
                        <ul class="working-day week-display-36">
                          <?= getWeekYoil($weekDisplaySource) ?>
                        </ul>
                      <?php } ?>
                    </td>
                  </tr>
                  */ ?>
                  <tr>
                    <?php /* 근무날짜 기능 주석처리
                    <td class="label">근무날짜</td>
                    <td class="value">
                      <?= ($info["rc_work_dt"] == "0000-00-00 00:00:00" ? "근무시작일 무관" : date('Y년 n월 j일', strtotime($info["rc_work_dt"]))) ?>
                    </td>
                    */ ?>
                    <td class="label">근무요일</td>
                    <td class="value">
                      <?php
                      $isWeekUnlimited = (isset($info["rc_week_yn"]) && $info["rc_week_yn"] === "Y");
                      $hasSavedWeek = trim((string) $info["rc_week"]) !== "";
                      $weekDisplaySource = $hasSavedWeek ? $info["rc_week"] : "월,화,수,목,금"; // 간편등록 기본값을 그대로 노출
                      ?>
                      <?php if ($isWeekUnlimited) { ?>
                        무관
                      <?php } else { ?>
                        <ul class="working-day week-display-36">
                          <?= getWeekYoil($weekDisplaySource) ?>
                        </ul>
                      <?php } ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

          </div>

          <?php if ($info["rc_life_type"] != "") { ?>
            <div class="section view-benefit">
              <h3 class="secondary-title">복리후생</h3>
              <ul class="benefit-group">
                <?= getLifeType($info["rc_life_type"], "-v2") ?>
              </ul>
            </div>
          <?php } ?>

          <div class="section view-desc">
            <h3 class="secondary-title">상세업무정보</h3>
            <!-- 작성내용 -->
            <div class="desc" style="clear:both;">
              <?= $info["rc_desc"] ?>
              <div class="hashtag">
                <?= getHashTag(convertHashtagString($info["rc_hash"])) ?>
              </div>
            </div>
          </div>

          <div class="section view-ext">
            <div class="inline-group">
              <div class="col">
                <h3 class="secondary-title" style="position:relative;">기본정보
                  <div class="map-nav-wrap">
                    <button type="button" class="js-toggle-dropdown" aria-expanded="false" style="background:#1B9D7D;color:#fff;border-radius:5px;border:solid 1px #1B9D7D;font-size:13px;padding:4px 10px;cursor:pointer;">길찾기</button>
                    <div class="dropdown" hidden>
                      <a href="javascript:naverMap('<?= htmlspecialchars($mem_info["mm_comp_name"], ENT_QUOTES, 'UTF-8') ?>',<?= $addr["ad_gps_y"] ?>,<?= $addr["ad_gps_x"] ?>);">네이버지도</a>
                      <a href="javascript:kakaoMap('<?= htmlspecialchars($mem_info["mm_comp_name"], ENT_QUOTES, 'UTF-8') ?>',<?= $addr["ad_gps_y"] ?>,<?= $addr["ad_gps_x"] ?>);">카카오맵</a>
                    </div>
                  </div>
                </h3>
                <table class="info-table">
                  <caption><span class="sr-text">상호</span></caption>
                  <colgroup>
                    <col>
                    <col>
                  </colgroup>
                  <tbody>
                    <tr>
                      <td class="label">상호</td>
                      <td class="value"><?= $mem_info["mm_comp_name"] ?></td>
                    </tr>
                    <tr>
                      <td class="label">연락처</td>
                      <td class="value"><?= formatPhoneNumber($mem_info["mm_phone"]) ?></td>
                    </tr>
                    <tr>
                      <td class="label">근무지 주소</td>
                      <td class="value"><?= $addr["ad_doro"] ?> <?= $addr["ad_detail"] ?></td>
                    </tr>
                  </tbody>
                </table>
                <div class="map" style="position:relative; height: 420px;">
                  <!-- 회사 지도 좌표  -->
                  <input type="hidden" id="geocoder" value="<?= $addr["ad_gps_y"] ?>,<?= $addr["ad_gps_x"] ?>">
                  <div id="jsMap" class="js-map"></div>
                </div>
                <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?= $kakao_key["인증키"] ?>&libraries=services"></script>
                <script>
                  window.addEventListener('load', function() {
                    var mapContainer = document.getElementById('jsMap');
                    var geocoder = document.getElementById('geocoder').value;
                    var coords, map, marker;

                    if (!geocoder) {
                      mapContainer.parentNode.setAttribute('hidden', '');
                      return;
                    }

                    geocoder = geocoder.split(',');
                    coords = new kakao.maps.LatLng(geocoder[0], geocoder[1]);

                    map = new kakao.maps.Map(mapContainer, {
                      center: coords,
                      level: 3,
                    });

                    map.setZoomable(false);

                    marker = new kakao.maps.Marker({
                      position: coords,
                      image: new kakao.maps.MarkerImage(
                        '/resource/shop/web/img/ic_loc_pin2.svg',
                        new kakao.maps.Size(56, 74), {
                          offset: new kakao.maps.Point(38, 56)
                        }
                      )
                    });

                    marker.setMap(map);
                  });
                </script>

              </div>
            </div>
          </div>



        </div>
        <!-- 컨텐츠 영역 -->

        <!-- aside -->
        <div class="view-aside">

          <?php
          // 기업회원이 자신의 채용공고를 볼 경우를 판단 (변수 재사용)
          $isOwnRecruit = ($this->user->getUserType() === "COMP" && isset($info["mm_idx"]) && $info["mm_idx"] == $this->user->getUserIdx());
          ?>
          <div class="sticky-group<?php if ($isOwnRecruit) : ?> own-recruit<?php endif; ?>">
            <div class="summary-card">
              <div class="top">
                <?php if (!$isOwnRecruit) : ?>
                  <div class="inline-group">
                    <!-- 카테고리 라벨 -->
                    <?= getWorkingImg($info["rc_work"], 2) ?>
                  </div>
                <?php endif; ?>

                <!-- 공유하기 -->
                <div class="share-wrap">
                  <button type="button" class="btn-share js-toggle-dropdown"><span class="sr-text">공유하기</span></button>
                  <div class="dropdown">
                    <!-- 카카오톡 공유 기능 주석처리 -->

                    <a href="javascript:void(0);" onclick="shareMessage();return false;" class="share-link1">
                      <span class="ico"><img src="/resource/shop/web/img/ic_share_vendor1.svg" alt=""></span>
                      <span>카카오톡</span>
                    </a>

                    <a href="javascript:void(0);" onclick="CopyUrl();return false;" class="share-link2">
                      <span class="ico"><img src="/resource/shop/web/img/ic_share_copy_url.svg" alt=""></span>
                      <span>URL복사</span>
                    </a>
                  </div>

                  <!-- 카카오 SDK 스크립트 주석처리 -->
                  <script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script>
                  <script>
                    /* 카카오톡 공유 기능 주석처리 */

                    function shareMessage() {
                      if (!Kakao.isInitialized()) {
                        //Kakao.init("a0a27ea14e36a89cbe2de50dc61323b8");
                        Kakao.init("<?= $kakao_key["인증키"] ?>");
                      }

                      var title = document.querySelector('.view-title').textContent.replace(/\s/g, ''),
                        desc = ''; //document.querySelector('.view-desc .desc').textContent.replace(/\s/g,''),
                      imgUrl = 'https://nowjob.co.kr/resource/shop/web/img/nowjob_info.png'; //document.querySelector('.view-visual .visual-slider img').getAttribute('src');
                      url = location.href,
                        appUrl = 'scheme://host_path';

                      setTimeout(function() {
                        Kakao.Share.sendDefault({
                          objectType: 'feed',
                          content: {
                            title: title, //글제목
                            description: desc, //글내용
                            imageUrl: imgUrl, //대표이미지 절대주소
                            link: {
                              mobileWebUrl: url,
                            },
                          },
                          buttons: [{
                            title: '바로가기',
                            link: {
                              mobileWebUrl: url,
                              webUrl: url
                            },
                          }],
                        });
                      }, 100);
                    }


                    /* URL 복사 */
                    function CopyUrl() {
                      var dummy = document.createElement('input'),
                        url = location.href;

                      document.body.appendChild(dummy);
                      dummy.value = url;
                      dummy.select();
                      document.execCommand('copy');
                      document.body.removeChild(dummy);
                      openPopup('alert', {
                        content: 'URL을 복사했습니다.'
                      });
                    }
                  </script>
                </div>

                <!-- 즐겨찾기 -->
                <button type="button" data-bk_type="RECRUIT" data-bk_key="<?= $info["rc_idx"] ?>" ata-user_id="<?= $mem_info["mm_id"] ?>" class="btnBookMark btn-view-bookmark js-toggle-bookmark <?= $book_yn == "Y" ? "on" : "" ?>">
                  <span class="sr-text">즐겨찾기 추가</span>
                </button>
              </div>
              <?php if ($isOwnRecruit) : ?>
                <!-- 기업회원이 자신의 채용공고를 볼 때: 지급정보를 타이틀 위에 표시 -->
                <!-- 지급정보 -->
                <?php if (strpos($info["rc_price_type"], "협의") !== false) { ?>
                  <p class="meta"><em></em></p>
                  <p class="amount"><em>급여협의</em></p>
                <?php } else {
                  // getConvertPriceType 함수를 사용하여 job_list.php와 동일한 형식으로 표시
                  $rc_price_rep = isset($info["rc_price_rep"]) ? $info["rc_price_rep"] : "";
                  $price_type_html = getConvertPriceType($info["rc_price_type"], $rc_price_rep);
                ?>
                  <p class="meta"><?= $price_type_html ?></p>
                  <p class="amount"><em><?= number_format($info["rc_price_pay"]) ?></em>원</p>
                <?php } ?>
              <?php endif; ?>
              <!-- 타이틀 -->
              <p class="title"><?= $info["rc_name"] ?></p>
              <?php if (!$isOwnRecruit) : ?>
                <!-- 일반 사용자가 볼 때: 지급정보를 타이틀 아래에 표시 -->
                <!-- 지급정보 -->
                <?php if (strpos($info["rc_price_type"], "협의") !== false) { ?>
                  <p class="meta"><em></em></p>
                  <p class="amount"><em>급여협의</em></p>
                <?php } else {
                  // getConvertPriceType 함수를 사용하여 job_list.php와 동일한 형식으로 표시
                  $rc_price_rep = isset($info["rc_price_rep"]) ? $info["rc_price_rep"] : "";
                  $price_type_html = getConvertPriceType($info["rc_price_type"], $rc_price_rep);
                ?>
                  <p class="meta"><?= $price_type_html ?></p>
                  <p class="amount"><em><?= number_format($info["rc_price_pay"]) ?></em>원</p>
                <?php } ?>
              <?php endif; ?>

              <?php if ($isOwnRecruit) { ?>
                <!-- 기업회원이 자신의 채용공고를 볼 때: 수정 및 광고신청 버튼 -->
                <button type="button" id="btnEditRecruit" class="btn-request btn-primary">수정</button>
                <button type="button" id="btnPromotionRecruit" class="btn-request btn-primary">광고신청</button>
              <?php } else { ?>
                <!-- 개인회원이 볼 때: 지원하기 버튼 -->
                <?php if ($this->user->getUserType() == "COMP") { ?>
                  <button id="btnRecruit" type="button" aria-haspopup="dialog" class="btn-request btn-primary" style="background-color:#ccc;" disabled>지원하기</button>
                <?php } else { ?>
                  <button id="btnRecruit" type="button" aria-haspopup="dialog" class="btn-request btn-primary">지원하기</button>
                <?php } ?>
              <?php } ?>
            </div>

            <!-- 히스토리 목록 -->
            <div class="recent-history" style="width: 100%;">
              <div class="history-header">
                <p class="title">내가 본 공고</p>
              </div>
              <div class="history-body">
                <div class="recently-slider keen-slider">
                  <?php foreach ($view_list as $row) : ?>
                    <!-- 목록 -->
                    <div class="keen-slider__slide">

                      <div class="content-li" data-rc_idx="<?= $row["rc_idx"] ?>">
                        <div class="content-info">
                          <div class="content-body">
                            <div class="img">
                              <img src="<?= $row["rt_recruit_img"] ?>">
                            </div>
                            <div class="desc">
                              <div class="wicon"><?= getWorkingImg($row["rc_work"]) ?></div>
                              <div class="title ellipsis"><?= $row["rc_name"] ?></div>
                              <div class="summary">
                                <p><?= $row["rc_nation_nm"] ?></p>
                                <p><?= $row["rc_age_nm"] ?></p>
                              </div>
                              <div class="summary area">
                                <p class="area-addr" data-addr="<?= htmlspecialchars($row["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($row["ad_addr"], ENT_QUOTES, 'UTF-8') ?></p>
                              </div>
                              <div class="summary2">
                                <?php if (strpos($row["rc_price_type"], "협의") !== false) { ?>
                                  <p class="period">&nbsp;</p>
                                  <p class="amount">급여협의</p>
                                <?php } else {  ?>
                                  <p class="period"><?= getConvertPriceType($row["rc_price_type"], $row["rc_price_rep"]) ?></p>
                                  <p class="amount"><?= number_format($row["rc_price_pay"]) ?> 원</p>
                                <?php } ?>
                              </div>
                              <div class="summary3">
                                <p class="wtype"><?= getWorkTypeWithColor($row["rc_work_type"]) ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>

                  <?php endforeach; ?>
                </div>
              </div>

              <script>
                window.addEventListener('DOMContentLoaded', function() {
                  if (typeof KeenSlider === 'undefined') return;
                  var thumbnails = new KeenSlider(".recently-slider", {
                      initial: 0,
                      slides: {
                        perView: 6,
                      },
                      vertical: true,
                      drag: true,
                    },
                    [addActiveClass, sliderArrows]
                  );
                });
              </script>
            </div>
            <!-- //히스토리 목록 -->

          </div>

        </div>
        <!-- //aside -->

      </div>
    </div>

    <div class="view-footer">
      <div class="container">

        <!-- 하단 관련 공고 -->
        <div class="recomm-slider recruit">
          <h3 class="sr-text">관련공고</h3>
          <div class="slider-wrap keen-slider swiper-wrapperX" id="my-keen-slider" style="width: calc(100% - 0px);">
          </div>
        </div>
        <!-- //하단 관련 공고 -->

      </div>
    </div>

  </main><!-- //page-content -->
</div><!-- //page -->
<style>
  .recruit-card .type em {
    display: inline-block;
    font-style: normal;
    font-weight: 500;
    color: #1B9D7D;
  }

  .summary-card .meta::after {
    display: none !important;
  }

  .meta .worktype em {
    font-style: normal;
  }

  .recruit-card .meta em:after {
    display: none;
  }
</style>
<!-- 추천 리스트 템플릿 -->
<template id="tpl_reco_list">
  <div class="keen-slider__slide">
    <div class="recruit-card">
      <div class="thumb-wrap">
        <!-- p class="recomm-badge">추천</p -->
        <div class="thumb">
          <a href="/recruit/job_post/{{rc_idx}}" target="_blank"><img src="{{rt_recruit_img}}" alt="" class=""></a>
        </div>
        <!-- <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" data-user_id="{{mm_id}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button> -->
      </div>
      <div class="content-wrap">
        <div class="inline-group">
          {{rt_work_img}}
          <p class="area">{{ad_addr}}</p>
        </div>
        <p class="title"><strong><a href="/recruit/job_post/{{rc_idx}}" target="_blank">{{rc_name}}</a></strong></p>
        <div class="meta">{{rt_work_type}}</p>
          <p>{{rc_nation_nm}}</p>
          <p>{{rc_age_nm}}</p>
        </div>
        <div class="price-wrap" style="position:relative;">
          <p class="type">{{rc_price_type}}</p>
          <p class="amount">{{rc_price_pay}}</p>
        </div>
        <div class="hashtag">
          {{rt_hash}}
        </div>
      </div>
    </div>
  </div>
</template>

<input type="hidden" name="rc_idx" value="<?= $info["rc_idx"] ?>" />
<input type="hidden" name="rc_name" value="<?= $info["rc_name"] ?>" />
<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<?php
// 모집 마감 여부: 종료일이 현재 시각보다 과거면 마감 처리
$is_closed = (!empty($info["rc_edate"]) && strtotime($info["rc_edate"]) <= time());
?>
<input type="hidden" id="is_closed" value="<?= $is_closed ? "Y" : "N" ?>" />
<script>
  //티맵 길안내
  function tMap(name, lat, lng) {
    window.open("https://apis.openapi.sk.com/tmap/app/routes?appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41&name=" + name + "&lon=" + lng + "&lat=" + lat);
  }

  //카카오맵 길안내
  function kakaoMap(name, lat, lng) {
    window.open("https://map.kakao.com/link/to/" + name + "," + lat + "," + lng);
  }

  //네이버맵 길안내
  function naverMap(name, lat, lng) {
    var url = "http://map.naver.com/index.nhn?menu=route&pathType=1&elng=" + lng + "&elat=" + lat + "&etext=" + encodeURIComponent(name);
    window.open(url);
  }
</script>

<script>
  $(document).ready(function() {
    $('.bigsize').magnificPopup({
      items: [
        <?php if ($info["rc_img1"] != "") : ?> {
            src: '<?= $info["rc_img1"] ?>'
          },
        <?php endif; ?>
        <?php if ($info["rc_img2"] != "") : ?> {
            src: '<?= $info["rc_img2"] ?>'
          },
        <?php endif; ?>
        <?php if ($info["rc_img3"] != "") : ?> {
            src: '<?= $info["rc_img3"] ?>'
          },
        <?php endif; ?>
        <?php if ($info["rc_img4"] != "") : ?> {
            src: '<?= $info["rc_img4"] ?>'
          },
        <?php endif; ?>
        <?php if ($info["rc_img5"] != "") : ?> {
            src: '<?= $info["rc_img5"] ?>'
          },
        <?php endif; ?>
      ],
      gallery: {
        enabled: true
      },
      type: 'image' // this is default type
    });

    var check_user_type = "<?= $this->user->getUserType(); ?>";
    // 모집 마감 여부 (마감 시 즉시 안내 후 중단)
    var isClosedRecruit = $("#is_closed").val() === "Y";
    $("#btnRecruit").click(function() {
      if (isClosedRecruit) {
        commonAlert("마감된 공고입니다", function() {});
        return;
      }
      //로그인 여부 확인
      if ($("#is_login").val() != "Y") {
        //commonLogin();

        commonAlert("로그인후 이용가능한 기능입니다.", function() {
          // 확인 버튼 클릭 시 팝업만 닫히도록 처리
        });
        return;
      }

      if (check_user_type != "MEM") {
        commonAlert("기업 회원은 지원 할 수 없습니다.", function() {});
        return;
      }

      //이력서 여부 확인
      if ($("[name=resume_yn]").val() == "N") {
        commonAlert("이력서 작성및 공개이력서 설정후 지원해주세요.", function() {});
        return;
      }

      //오늘 지원여부 확인
      var paramsToday = {};
      paramsToday["rc_idx"] = $("[name=rc_idx]").val();

      comAjax("/recruit_proc/todayapply", paramsToday, function(ret) {
        if (ret.code == "00") {
          openPopup('confirm', {
            content: '<em>“' + $("[name=rc_name]").val() + '”</em>으로<br> 지원하시겠습니까?',
            secondCallback: function() {
              var params = {};
              var mq_idxs = [];
              mq_idxs.push($("[name=rc_idx]").val());
              params["mq_idxs"] = mq_idxs;
              params["mq_type"] = "RECRUIT";

              comAjax("/common_proc/member_request", params, function(ret) {
                if (ret.code == "00") {
                  // 알림창 주석처리 - 바로 채팅방으로 이동
                  // commonAlert("지원 신청 되었습니다.", function() {
                  //   location.reload();
                  // });

                  //채팅창 오픈
                  $("body").addClass('has-popup');
                  $(".js-open-chat").addClass("is-trigger");
                  $("#chatPopup").addClass("in");
                  $(".btn-close-chat").addClass("btn-close-chat");
                  $(".js-close-chat").addClass("show");

                  // 사용자 타입과 제안/지원 여부에 따라 올바른 탭 활성화
                  // job_post.php는 개인회원이 채용공고에 지원하는 페이지이므로 탭1 (지원한 공고)
                  var userType = "<?= $this->user->getUserType() ?>";
                  var targetTab = "1"; // 지원을 보낸 경우: 탭1 (지원한 공고)
                  var rc_idx = $("[name=rc_idx]").val();
                  setShow(targetTab);

                  //해당공고 클릭 처리 - 채팅방 목록 로드

                  // chatting_cont.php의 getChatTargetList 함수를 사용하여 채팅방 목록 로드
                  // 개인회원 지원한 공고 탭: get_chat_target_list_recruit_mem
                  if (typeof getChatTargetList === 'function') {
                    // getChatTargetList 함수 호출 (탭1: 지원한 공고)
                    getChatTargetList("1");

                    // 채팅방 목록 로드 후 채팅방 찾아서 자동으로 열기
                    // 채팅방 목록이 DOM에 추가될 때까지 반복 확인
                    var checkCount = 0;
                    var maxCheckCount = 30; // 최대 15초 대기 (0.5초 * 30)
                    var checkInterval = setInterval(function() {
                      try {
                        checkCount++;

                        // 지원한 공고 탭에서 해당 rc_idx와 일치하는 채팅방 찾기
                        var targetRmIdx = null;

                        // 개인회원의 경우: 지원한 공고 탭은 직접 room-item으로 표시됨
                        $("#chat_list1 .room-item").each(function() {
                          var $item = $(this);
                          var itemRmIdx = $item.data('target_id');
                          if (itemRmIdx) {
                            // 채팅방 정보를 확인하여 rc_idx와 일치하는지 확인
                            // 일단 첫 번째 채팅방을 사용 (개인회원은 보통 하나의 공고에 하나의 채팅방)
                            targetRmIdx = itemRmIdx;
                            return false; // break
                          }
                        });

                        // 채팅방을 찾았으면 즉시 열기
                        if (targetRmIdx) {
                          clearInterval(checkInterval);
                          openChatRoom(targetRmIdx, rc_idx, userType);
                          return;
                        }

                        // 최대 시도 횟수에 도달한 경우
                        if (checkCount >= maxCheckCount) {
                          clearInterval(checkInterval);
                          //console.error("채팅방을 찾을 수 없음 - rc_idx:", rc_idx);
                          // 첫 번째 채팅방 사용
                          var $firstChatItem = $("#chat_list1 .room-item:first");
                          if ($firstChatItem.length > 0) {
                            var firstRmIdx = $firstChatItem.data('target_id');
                            if (firstRmIdx) {
                              openChatRoom(firstRmIdx, rc_idx, userType);
                            }
                          }
                        }
                      } catch (error) {
                        //console.error("채팅방 찾기 오류:", error);
                        clearInterval(checkInterval);
                      }
                    }, 500); // 0.5초마다 확인

                    // 채팅방 열기 함수
                    function openChatRoom(targetRmIdx, rc_idx, userType) {
                      if (!targetRmIdx || typeof socket === 'undefined' || !socket) {
                        //console.error("채팅방 열기 실패 - targetRmIdx:", targetRmIdx, "socket:", typeof socket);
                        return;
                      }


                      // chatting_cont.php의 .room-item 클릭 핸들러와 동일한 로직 실행
                      // 대화내용 중복 차단 내역 초기화
                      if (typeof resetProcessedIdx === 'function') {
                        resetProcessedIdx();
                      }

                      // 초기화
                      if (typeof click_type !== 'undefined') {
                        click_type = "list";
                      }
                      if (typeof rm_start !== 'undefined') {
                        rm_start = 0;
                      }
                      $("#chat_view").html("");
                      if (typeof rm_idx !== 'undefined') {
                        rm_idx = targetRmIdx;
                      }

                      // get_chat_info 중복 호출 방지 플래그 초기화
                      if (typeof window.chat_info_called !== 'undefined') {
                        window.chat_info_called = false;
                      }

                      // 채팅방 정보 준비 상태 초기화
                      if (typeof rm_info_ready !== 'undefined') {
                        rm_info_ready = false;
                      }
                      if (typeof rm_info !== 'undefined') {
                        rm_info = {};
                      }

                      $("#btnSend").prop("disabled", true); // 버튼 비활성화

                      // g_user_id 가져오기
                      var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();

                      // 지원 완료 후 자동 메시지 전송 플래그 설정
                      window.autoSendFirstMessage = {
                        enabled: true,
                        rm_idx: targetRmIdx,
                        rc_idx: rc_idx,
                        messageListChecked: false, // message_list_return에서 메시지가 없다고 확인했는지 여부
                        targetUserId: null // room_info_return에서 찾은 상대방 ID
                      };

                      // 리스너를 emit보다 먼저 등록해 응답 레이스로 이벤트를 놓치지 않도록 보강
                      var earlyBindSocketOnce = function(eventName, handler) {
                        if (!socket || typeof handler !== 'function') return;
                        if (typeof socket.once === 'function') {
                          socket.once(eventName, handler);
                          return;
                        }
                        if (typeof socket.on === 'function') {
                          var wrapped = function(ret) {
                            if (typeof socket.off === 'function') {
                              socket.off(eventName, wrapped);
                            }
                            handler(ret);
                          };
                          socket.on(eventName, wrapped);
                        }
                      };

                      earlyBindSocketOnce("room_info_return", function(ret) {
                        if (!ret || ret.code !== "00" || !window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) return;
                        if (!ret.data || Number(ret.data.rm_idx) !== Number(window.autoSendFirstMessage.rm_idx)) return;

                        // chatting_cont.php의 전송 조건(rm_info_ready/rm_info)을 만족시키도록 동기화
                        if (typeof rm_info !== 'undefined') {
                          rm_info = ret.data;
                        }
                        if (typeof rm_info_ready !== 'undefined') {
                          rm_info_ready = true;
                        }

                        var targetUserId = null;
                        if (ret.data.rm_users && Array.isArray(ret.data.rm_users)) {
                          for (var i = 0; i < ret.data.rm_users.length; i++) {
                            if (ret.data.rm_users[i].user_id && ret.data.rm_users[i].user_id != g_user_id) {
                              targetUserId = ret.data.rm_users[i].user_id;
                              break;
                            }
                          }
                        }

                        if (targetUserId) {
                          window.autoSendFirstMessage.targetUserId = targetUserId;
                          if (typeof g_chat_other_id !== 'undefined' && !g_chat_other_id) {
                            g_chat_other_id = targetUserId;
                          }
                          if (window.autoSendFirstMessage.messageListChecked && window.sendFirstMessageAfterProposal) {
                            window.sendFirstMessageAfterProposal(targetUserId, window.autoSendFirstMessage.rm_idx);
                            window.autoSendFirstMessage = null;
                          }
                        }
                      });

                      earlyBindSocketOnce("message_list_return", function(ret) {
                        if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) return;
                        var messageList = ret && ret.data ? ret.data : [];
                        var hasMessages = false;
                        if (Array.isArray(messageList) && messageList.length > 0) {
                          for (var i = 0; i < messageList.length; i++) {
                            if (messageList[i].rm_messages && Array.isArray(messageList[i].rm_messages) && messageList[i].rm_messages.length > 0) {
                              hasMessages = true;
                              break;
                            }
                          }
                        }
                        if (!hasMessages) {
                          window.autoSendFirstMessage.messageListChecked = true;
                          if (window.autoSendFirstMessage.targetUserId && window.sendFirstMessageAfterProposal) {
                            window.sendFirstMessageAfterProposal(window.autoSendFirstMessage.targetUserId, window.autoSendFirstMessage.rm_idx);
                            window.autoSendFirstMessage = null;
                          }
                        }
                      });

                      // 룸 호출
                      socket.emit("room_join", {
                        rm_idx: Number(targetRmIdx),
                        con_user_id: g_user_id
                      });

                      // 채팅방 정보 조회
                      socket.emit("room_info", {
                        rm_idx: Number(targetRmIdx),
                        con_user_id: g_user_id
                      });

                      // 메시지 리스트 조회
                      var params = {
                        rm_idx: Number(targetRmIdx),
                        skip: 0,
                        limit: 1000
                      };
                      socket.emit("message_list", params);

                      // 채팅 뷰 표시
                      $('.chat-view').css('display', 'flex');
                      $('.chat-view').removeAttr('hidden');


                      // get_chat_info를 직접 호출하여 상대방 ID 가져오기
                      if (typeof comAjaxV2 === 'function') {
                        // ajax_first_num 초기화하여 중복 호출 알림 방지
                        if (typeof ajax_first_num !== 'undefined') {
                          ajax_first_num = 0;
                        }

                        comAjaxV2("/chat_proc/get_chat_info", {
                          rm_idx: targetRmIdx,
                          mm_type: userType,
                          chat_tab_id: "1"
                        }, function(ret) {
                          if (ret.code == "00" && ret.data && ret.data.mm_id) {
                            var targetUserId = ret.data.mm_id;

                            // 플래그에 상대방 ID 저장
                            if (window.autoSendFirstMessage && window.autoSendFirstMessage.enabled) {
                              window.autoSendFirstMessage.targetUserId = targetUserId;
                              // get_chat_info에서 가져온 상대방 ID를 data 속성에도 저장
                              $("#top_name").data('chatotherid', targetUserId);

                              // message_list_return에서 메시지가 없다고 확인했으면 바로 전송
                              if (window.autoSendFirstMessage.messageListChecked && window.sendFirstMessageAfterProposal) {
                                window.sendFirstMessageAfterProposal(targetUserId, targetRmIdx);
                                // 플래그 즉시 해제하여 중복 전송 방지
                                window.autoSendFirstMessage = null;
                                return; // 전송 완료 후 즉시 리턴
                              }
                            }
                          } else {}
                        });
                      }

                      function bindSocketOnce(eventName, handler) {
                        if (!socket || typeof handler !== 'function') return;
                        if (typeof socket.once === 'function') {
                          socket.once(eventName, handler);
                          return;
                        }
                        if (typeof socket.on === 'function') {
                          var wrapped = function(ret) {
                            if (typeof socket.off === 'function') {
                              socket.off(eventName, wrapped);
                            }
                            handler(ret);
                          };
                          socket.on(eventName, wrapped);
                        }
                      }

                      // room_info_return 이벤트를 한 번만 리스닝하여 상대방 ID 저장 및 메시지 전송 시도
                      bindSocketOnce("room_info_return", function(ret) {
                        if (ret.code == "00" && window.autoSendFirstMessage && window.autoSendFirstMessage.enabled) {
                          var autoSendInfo = window.autoSendFirstMessage;

                          // 현재 채팅방인지 확인
                          if (autoSendInfo.rm_idx == ret.data.rm_idx || autoSendInfo.rm_idx == rm_idx) {

                            // 상대방 ID 찾기
                            var targetUserId = null;
                            var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();

                            if (ret.data && ret.data.rm_users && Array.isArray(ret.data.rm_users)) {
                              for (var i = 0; i < ret.data.rm_users.length; i++) {
                                if (ret.data.rm_users[i].user_id && ret.data.rm_users[i].user_id != g_user_id) {
                                  targetUserId = ret.data.rm_users[i].user_id;
                                  break;
                                }
                              }
                            } else {}

                            // 상대방 ID를 찾지 못한 경우 get_chat_info에서 가져오기
                            if (!targetUserId) {
                              // get_chat_info가 호출될 때까지 대기
                              setTimeout(function() {
                                // 플래그가 이미 해제되었는지 확인 (get_chat_info에서 이미 전송했을 수 있음)
                                if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) {
                                  return;
                                }

                                var chatOtherId = $("#top_name").data('chatotherid');
                                if (chatOtherId) {
                                  targetUserId = chatOtherId;

                                  // 플래그가 여전히 존재하는지 확인
                                  if (window.autoSendFirstMessage) {
                                    window.autoSendFirstMessage.targetUserId = targetUserId;

                                    // message_list_return에서 메시지가 없다고 확인했으면 바로 전송
                                    if (window.autoSendFirstMessage.messageListChecked && window.sendFirstMessageAfterProposal) {
                                      window.sendFirstMessageAfterProposal(targetUserId, autoSendInfo.rm_idx);
                                      // 플래그 즉시 해제하여 중복 전송 방지
                                      window.autoSendFirstMessage = null;
                                    }
                                  }
                                } else {}
                              }, 1500);
                            } else {
                              // 상대방 ID를 플래그에 저장
                              window.autoSendFirstMessage.targetUserId = targetUserId;

                              // message_list_return에서 메시지가 없다고 확인했으면 바로 전송
                              // (message_list_return이 먼저 발생했을 수 있음)
                              if (window.autoSendFirstMessage.messageListChecked && window.sendFirstMessageAfterProposal) {
                                window.sendFirstMessageAfterProposal(targetUserId, autoSendInfo.rm_idx);
                                // 플래그 해제하여 중복 전송 방지
                                window.autoSendFirstMessage = null;
                              }
                            }
                          }
                        }
                      });

                      // message_list_return 이벤트를 한 번만 리스닝하여 메시지 개수 확인
                      bindSocketOnce("message_list_return", function(ret) {
                        // 이미 전송되었으면 스킵 (room_info_return에서 전송했을 수 있음)
                        if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) {
                          return;
                        }

                        var autoSendInfo = window.autoSendFirstMessage;

                        // 메시지 리스트 확인
                        var messageList = ret.data || [];
                        var hasMessages = false;

                        if (Array.isArray(messageList) && messageList.length > 0) {
                          for (var i = 0; i < messageList.length; i++) {
                            if (messageList[i].rm_messages && Array.isArray(messageList[i].rm_messages) && messageList[i].rm_messages.length > 0) {
                              hasMessages = true;
                              break;
                            }
                          }
                        }


                        // 메시지가 없으면 자동으로 첫 메시지 전송
                        if (!hasMessages) {

                          // message_list_return에서 메시지가 없다고 확인했음을 플래그에 저장
                          window.autoSendFirstMessage.messageListChecked = true;

                          // 상대방 ID 가져오기 (여러 소스에서 시도)
                          var targetUserId = null;
                          var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();

                          // 1. 플래그에 저장된 상대방 ID 사용 (get_chat_info 또는 room_info_return에서 저장됨)
                          if (autoSendInfo.targetUserId) {
                            targetUserId = autoSendInfo.targetUserId;
                          }

                          // 2. rm_info에서 상대방 ID 찾기
                          if (!targetUserId && typeof rm_info !== 'undefined' && rm_info && rm_info.rm_users && Array.isArray(rm_info.rm_users)) {
                            for (var i = 0; i < rm_info.rm_users.length; i++) {
                              if (rm_info.rm_users[i].user_id != g_user_id) {
                                targetUserId = rm_info.rm_users[i].user_id;
                                break;
                              }
                            }
                          }

                          // 3. get_chat_info에서 가져온 값 사용 (더 긴 대기 시간)
                          if (!targetUserId) {
                            setTimeout(function() {
                              // 플래그가 이미 해제되었는지 확인 (다른 곳에서 이미 전송했을 수 있음)
                              if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) {
                                return;
                              }

                              var chatOtherId = $("#top_name").data('chatotherid');
                              if (chatOtherId && window.sendFirstMessageAfterProposal) {
                                window.sendFirstMessageAfterProposal(chatOtherId, window.autoSendFirstMessage.rm_idx);
                                // 플래그 즉시 해제하여 중복 전송 방지
                                window.autoSendFirstMessage = null;
                              } else {
                                // 4. g_chat_other_id 사용
                                var gChatOtherId = (typeof g_chat_other_id !== 'undefined' && g_chat_other_id) ? g_chat_other_id : null;
                                if (gChatOtherId && window.sendFirstMessageAfterProposal) {
                                  window.sendFirstMessageAfterProposal(gChatOtherId, window.autoSendFirstMessage.rm_idx);
                                  // 플래그 즉시 해제하여 중복 전송 방지
                                  window.autoSendFirstMessage = null;
                                } else {
                                  //console.error("get_chat_info에서도 상대방 ID를 찾을 수 없음");
                                  // 플래그 해제
                                  window.autoSendFirstMessage = null;
                                }
                              }
                            }, 2000); // get_chat_info 완료 대기
                            return; // setTimeout에서 처리하므로 여기서는 리턴
                          }

                          // 4. g_chat_other_id 사용
                          if (!targetUserId && typeof g_chat_other_id !== 'undefined' && g_chat_other_id) {
                            targetUserId = g_chat_other_id;
                          }

                          // 상대방 ID를 찾았으면 메시지 전송
                          // 플래그가 여전히 존재하는지 확인 (다른 곳에서 이미 전송했을 수 있음)
                          if (targetUserId && window.sendFirstMessageAfterProposal && window.autoSendFirstMessage && window.autoSendFirstMessage.enabled) {
                            window.sendFirstMessageAfterProposal(targetUserId, autoSendInfo.rm_idx);
                            // 플래그 즉시 해제하여 중복 전송 방지
                            window.autoSendFirstMessage = null;
                          } else if (!targetUserId) {
                            // 상대방 ID를 찾지 못한 경우 재시도
                            setTimeout(function() {
                              // 플래그가 이미 해제되었는지 확인 (다른 곳에서 이미 전송했을 수 있음)
                              if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) {
                                return;
                              }

                              // 재시도 시 여러 소스에서 다시 찾기
                              var retryTargetUserId = window.autoSendFirstMessage.targetUserId ||
                                $("#top_name").data('chatotherid') ||
                                (typeof g_chat_other_id !== 'undefined' ? g_chat_other_id : null) ||
                                (typeof rm_info !== 'undefined' && rm_info && rm_info.rm_users ?
                                  (function() {
                                    var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();
                                    for (var i = 0; i < rm_info.rm_users.length; i++) {
                                      if (rm_info.rm_users[i].user_id != g_user_id) {
                                        return rm_info.rm_users[i].user_id;
                                      }
                                    }
                                    return null;
                                  })() : null);

                              if (retryTargetUserId && window.sendFirstMessageAfterProposal) {
                                window.sendFirstMessageAfterProposal(retryTargetUserId, window.autoSendFirstMessage.rm_idx);
                                // 플래그 즉시 해제하여 중복 전송 방지
                                window.autoSendFirstMessage = null;
                              } else {
                                //console.error("상대방 ID를 찾을 수 없음 - 재시도 실패");
                                // 플래그 해제
                                window.autoSendFirstMessage = null;
                              }
                            }, 3000);
                          }
                        } else {
                          window.autoSendFirstMessage = null;
                        }
                      });

                      // 첫 메시지 전송 함수 (전역 함수로 정의)
                      window.sendFirstMessageAfterProposal = function(targetUserId, targetRmIdx) {
                        if (!targetUserId || !targetRmIdx || typeof socket === 'undefined' || !socket) {
                          //console.error("첫 메시지 전송 실패 - targetUserId:", targetUserId, "targetRmIdx:", targetRmIdx, "socket:", typeof socket);
                          return;
                        }


                        // g_user_id 가져오기
                        var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();

                        // getCurrentDate 함수 확인
                        var currentDate = typeof getCurrentDate === 'function' ? getCurrentDate() : new Date().toISOString();

                        // 사용자 타입에 따른 메시지 내용 결정
                        var current_user_type = typeof check_user_type !== 'undefined' ? check_user_type : "";
                        var messageText = "";
                        if (current_user_type === "COMP") {
                          // 기업이 개인에게 제안보낼 경우
                          messageText = "안녕하세요.\n채용중인 일자리를 제안드립니다.";
                        } else {
                          // 개인이 기업에게 지원할 경우
                          messageText = "안녕하세요.\n채용공고에 이력서를 지원합니다.";
                        }

                        var firstMessage = {
                          rm_idx: Number(targetRmIdx),
                          user_id: g_user_id,
                          chat_type: "recruit",
                          mg_type: "text",
                          mg_text: messageText,
                          mg_req_dt: "",
                          mg_img: "",
                          mg_file: "",
                          mg_imoti: "",
                          mg_dt: currentDate,
                          mg_reads: new Array(),
                        };

                        // 읽음 상태 설정
                        firstMessage.mg_reads.push({
                          read_yn: "Y",
                          user_id: g_user_id
                        });
                        firstMessage.mg_reads.push({
                          read_yn: "N",
                          user_id: targetUserId
                        });

                        // 사용자 목록
                        var firstUsers = [];
                        firstUsers.push({
                          user_id: g_user_id,
                          user_nick: ""
                        });
                        firstUsers.push({
                          user_id: targetUserId,
                          user_nick: ""
                        });


                        // 메시지 전송
                        socket.emit("message_insert", firstMessage, firstUsers);
                      };
                    }
                  } else {
                    //console.error("getChatTargetList 함수를 찾을 수 없음");
                  }

                } else {
                  //console.error("지원하기 오류:", ret);
                  commonAlert(ret.msg, function() {});
                  return;
                }
              });
            }
          });

        } else if (ret.code == "01") {
          commonAlert("<em>5분 후</em> 다시 지원이 가능합니다.", function() {});
          return;
        } else if (ret.code == "90") {
          // 제안이 이미 있는 경우
          commonAlert(ret.msg, function() {});
          return;
        } else {
          commonAlert(ret.msg, function() {});
          return;
        }
      });

    });

    //추천공고
    function getRecoList() {
      var params = {};
      params["addr1"] = $("[name='searchArea']:checked").val() == "전국" ? "" : $("[name='searchArea']:checked").val();
      params["addr2"] = $("[name='searchArea2']:checked").val();
      params["addr3"] = $("[name='searchArea3']:checked").val();
      params["comp_idx"] = <?= $info["mm_idx"] ?>;
      params["rc_idx"] = <?= $info["rc_idx"] ?>;
      params["limit"] = 4;
      comAjax("/recruit_proc/getCompRecruitList", params, function(ret) {
        if (ret.code == "00") {
          var list = ret.data.list;
          $("#my-keen-slider").html("");
          $(".arrow--left").remove();
          $(".arrow--right").remove();
          list.forEach(function(row) {
            var $clone = $("#tpl_reco_list").clone();
            var $content = $clone.html();

            $content = $content.replaceAll("{{rc_nation_nm}}", row.rc_nation_nm);
            $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
            $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
            $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
            $content = $content.replaceAll("{{rc_name}}", row.rc_name);
            $content = $content.replaceAll("{{rc_age_nm}}", row.rc_age_nm);

            $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);

            var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
            $images.slice(2).remove();
            var $categoryHtml = $images.parent().html();
            $content = $content.replaceAll("{{rt_work_img}}", $categoryHtml);
            $content = $content.replaceAll("{{rt_work_type}}", getWorkTypeWithColorJS(row.rt_work_type));
            if (row.rc_price_type.indexOf('협의') > -1) {
              $content = $content.replaceAll("{{rc_price_type}}", "<em> </em>");
            } else {
              $content = $content.replaceAll("{{rc_price_type}}", getConvertPriceType(row.rc_price_type, row.rc_price_rep));
            }
            if (row.rc_price_type.indexOf('협의') > -1) {
              $content = $content.replaceAll("{{rc_price_pay}}", "급여협의");
            } else {
              $content = $content.replaceAll("{{rc_price_pay}}", Number(row.rc_price_pay).toLocaleString() + " 원");
            }
            $content = $content.replaceAll("{{mm_id}}", row.mm_id);
            $content = $content.replaceAll("{{rt_hash}}", getHashTagJS(row.rt_hash, 3, row.rc_work));


            $content = $content.replaceAll("{{timestamp}}", row.timestamp);

            $clone.html($content);

            $("#my-keen-slider").append($clone.html());
          });

          var slideCount = document.querySelectorAll('.recomm-slider .slider-wrap .keen-slider__slide').length;
          var perView = slideCount < 4 ? slideCount : 4;
          var slider = new KeenSlider(".recomm-slider .slider-wrap", {
            loop: false,
            slides: {
              perView: 4
            },
          }, [
            addActiveClass
          ]);

          var interval = 0;
          var sliderElement = document.getElementById("my-keen-slider");

          function autoplay(run) {
            clearInterval(interval);
            interval = setInterval(() => {
              if (run && slider) {
                slider.next();
              }
            }, 3400);
          }

          sliderElement.addEventListener("mouseover", () => {
            //autoplay(false);
          });
          sliderElement.addEventListener("mouseout", () => {
            //autoplay(true);
          });
          //autoplay(true);
        } else {

        }
      });
    }

    // fRemoveBigAdrJS 함수는 resource/shop/web/js/add.js에 정의되어 있으므로 중복 정의 제거



    function getHashTagJS(hash, max = 10, rc_work) {
      let result = "";
      let tmp = [...hash.matchAll(/<p[^>]*>#?([^<]+)<\/p>/g)]
        .map(match => match[1])
        .join(',');

      let arr_hash = tmp.split(",");
      let arr_work = rc_work.split(",");
      arr_work.forEach(function(txt, key) {
        if (key < max) {
          if (txt.trim() !== "") {
            arr_hash.push(txt);
          }
        }
      });

      arr_hash.forEach(function(txt, key) {
        if (key < max) {
          if (!txt.includes("#") && txt.trim() !== "") {
            txt = "#" + txt.trim();
          }
          if (txt.trim() !== "") {
            result += ` <p class='tag'>${txt}</p>`;
          }
        }
      });

      return result;
    }

    $(document).on('click', '.desc', function() {
      var rcIdx = $(this).closest('.content-li').data('rc_idx');
      if (rcIdx) {
        var url = '/recruit/job_post/' + rcIdx;
        window.open(url, '_blank');
      }
    });

    // 기업회원이 자신의 채용공고를 볼 때: 수정 버튼 클릭 이벤트
    $(document).on('click', '#btnEditRecruit', function() {
      var rcIdx = $("[name=rc_idx]").val();
      if (rcIdx) {
        location.href = "/compage/recruit_write/" + rcIdx;
      }
    });

    // 기업회원이 자신의 채용공고를 볼 때: 광고신청 버튼 클릭 이벤트
    $(document).on('click', '#btnPromotionRecruit', function() {
      location.href = "/compage/point_promotion_buy";
    });

    getRecoList();

    // 주소 정리 함수 적용 (fRemoveBigAdrJS 사용)
    $(document).ready(function() {
      $('.area-addr[data-addr]').each(function() {
        var $this = $(this);
        var originalAddr = $this.data('addr');
        if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
          var formattedAddr = fRemoveBigAdrJS(originalAddr);
          $this.text(formattedAddr);
        }
      });
    });

  });
</script>