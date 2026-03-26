<?php
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
        openPanel(document.querySelector(selector));
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
          sliderDots,
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
  function getLocation() {
    alert('GPS 기능이 비활성화되었습니다');
    return;
  }
  //getLocation();
</script>
<style>
  .btn-bookmark {
    display: block;
    position: absolute;
    top: 0px;
    right: 0px;
    width: 40px;
    height: 40px;
    border: 0;
    background: url("https://nowjob.co.kr/resource/shop/web/img/ic_bookmark_on.svg") no-repeat 0 0;
    background-size: 0px;
    pointer-events: none;
    z-index: 1;
  }

  .btn-bookmark:before {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    border-top: 40px solid #f8f8f8;
    border-left: 40px solid transparent;
    z-index: -1;
  }

  .btn-bookmark:after {
    content: "";
    display: block;
    position: absolute;
    top: 3px;
    right: 3px;
    width: 16px;
    height: 16px;
    border-style: hidden;
    background: url("https://nowjob.co.kr/resource/shop/web/img/ic_bookmark_off.svg") no-repeat 0 0;
    background-size: contain;
    pointer-events: all;
    z-index: 1;
  }

  .btn-bookmark.on:after {
    background-image: inherit;
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

  .result-list .content-li {
    position: relative;
    width: 100%;
    height: 115px;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
    background: #fff;
    margin-bottom: 10px;
    transition: background 0.2s ease;
  }

  .result-list .content-li:hover {
    background: #f8f8f8;
  }

  .content-li * {
    padding: 0;
    margin: 0;
  }

  .content-li .content-info {
    width: 100%;
    height: 100%;
    padding: 10px;
  }

  .content-info .wicon img {
    height: 18px;
  }

  .content-info .title {
    font-size: 16px;
    font-weight: bold;
    margin: 1px 0px;
  }

  .content-info .content-body {
    position: relative;
    overflow: hidden;
    height: 95px;
  }

  .content-info .desc {
    position: relative;
    height: 100%;
    margin: 0 0 0 110px;
    cursor: pointer;
  }

  .content-info .desc .ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .content-info .img {
    position: absolute;
    width: 95px;
    height: 95px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  .content-info .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    /* 이미지 비율 유지하며 박스를 꽉 채움 */
    display: block;
  }

  .content-info .link {
    color: #5085BB;
  }

  .content-info .summary {
    display: flex;
    font-size: 15px;
    gap: 5px;
    position: relative;
  }

  .content-info .summary p {
    position: relative;
    padding-right: 5px;
    margin: 0;
    color: #999;
  }

  .content-info .summary p:not(:last-child)::after {
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

  .content-info .summary>* {
    min-width: 0;
    max-width: 100%;
    margin-right: 4px;
    margin-bottom: -3px;
  }

  .content-info .summary.area p {
    color: #000;
    font-weight: 600;
  }

  .content-info .summary.reco p {
    color: #1b9d7d;
    font-weight: 600;
  }

  .content-info .summary2 {
    position: absolute;
    right: 0px;
    bottom: 0px;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0;
    color: #1b9d7d;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }

  .content-info .summary2 p {
    margin: 0;
    text-align: right;
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
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777777;
  }

  .content-info .summary3 {
    position: absolute;
    right: 0px;
    top: 0px;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
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
    line-height: 1.8;
    text-align: center;
    text-indent: -1px;
    border-radius: 100%;
    border: 1px solid #57CCAF;
    background: #fff;
    float: left;
  }

  .working-day>li:last-child {
    margin-right: 0;
  }

  .working-day li[aria-hidden=false] {
    color: #fff;
    border-color: transparent;
    background-color: #57CCAF;
  }

  .result-list .list-item+.list-item {
    border-top: 0;
  }

  .recomm-slider.person .keen-slider__slide {
    flex: 0 0 180px !important;
    width: 180px !important;
  }

  .person-card {
    padding: 0 5px 0 0;
  }

  .person-card .thumb-wrap {
    position: relative;
  }

  .person-card .recomm-badge {
    top: 10px;
    left: 10px;
    background-color: #57CCAF;
  }

  .person-card .thumb {
    position: relative;
    padding: 100% 0 0;
    border-radius: 16px;
    overflow: hidden;
    background: #f0f0f0;
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
    font-size: 12px;
    cursor: pointer;
  }

  .person-card .content-wrap {
    margin: -16px 0 0;
    padding: 22px 5px 5px;
    border-radius: 0 0 5px 5px;
    background: #F9FBFD;
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
    width: 50%;
    margin: 0 5px 0 0;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
  }

  .person-card .meta {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    text-align: center;
  }

  .person-card .meta p {
    position: relative;
    width: 25%;
    word-break: keep-all;
    text-align: center;
  }

  .person-card .meta p:first-child {
    text-align: left;
  }

  .person-card .meta p:after {
    content: "";
    display: block;
    position: absolute;
    top: 50%;
    right: 0;
    width: 1px;
    height: 13px;
    margin: -6px 0 0;
    background: #e9e9e9;
  }

  .person-card .meta p:last-child:after {
    content: none;
  }

  .person-card .meta p em {
    font-style: normal;
  }

  .person-card .hashtag {
    height: 26px;
    display: block;
    margin: 5px 0 0;
    padding: 5px 0 0;
    font-size: 0;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    white-space: nowrap;
    border-top: 1px solid #000;
    overflow: hidden;
  }

  .person-card .tag {
    display: inline-block;
    margin: 0 4px 0 0;
    padding: 0 8px;
    font-size: 10px;
    color: #1B9D7D;
    line-height: 2;
    text-align: center;
    vertical-align: top;
    border-radius: 100px;
    background: rgba(49, 161, 130, 0.15);
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

  .person-card .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .person-card .area {
    margin: 0;
    color: #000;
    height: auto;
    width: 50%;
    text-align: right;
    font-weight: 600;
  }
</style>
<div class="page page-home">
  <div class="header is-transparency">
    <h1 class="top-logo"><span class="sr-text">지금 뜨는, 좋은 일자리 NOW JOB</span></h1>
    <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span>
      <!-- 미확인 알림개수 -->
      <span class="cnt"><?= $g_alarm_cnt ?></span>
    </a>
  </div><!-- //header -->
  <style>
    .main-visual .visual-slider .dots {
      bottom: 12%;
    }
  </style>
  <div class="page-content">
    <div class="main-visual">
      <div class="visual-slider">
        <div class="slider-wrap keen-slider">
          <?php foreach ($banner_list as $banner) : ?>
            <div class="keen-slider__slide">
              <div class="visual-img">
                <?php if ($banner["bn_link_yn"] == "Y") : ?>
                  <a href="<?= $banner["bn_link"] ?>" target="<?= $banner["bn_link_type"] ?>">
                    <img src="<?= $banner["bg_img"] ?>" alt="">
                  </a>
                <?php else : ?>
                  <img src="<?= $banner["bg_img"] ?>" alt="">
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <script>
        window.addEventListener('DOMContentLoaded', function() {
          new KeenSlider(".visual-slider .slider-wrap", {
            loop: true,
            defaultAnimation: {
              duration: 2000,
            },
            detailsChanged: function(s) {
              s.slides.forEach(function(element, idx) {
                element.style.opacity = s.track.details.slides[idx].portion;
              });
            },
            renderMode: 'custom',
          }, [
            sliderDots,
            addActiveClass,
            function autoplay(slider) {
              sliderAutoplay(slider, 4000, false);
            }
          ]);
        });
      </script>
    </div>

    <div class="section">
      <div class="container">
        <h2 class="secondary-title">추천인재</h2>
        <div class="recomm-slider person">
          <div class="slider-wrap keen-slider" id="recomm-list">
          </div>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="container">
        <h2 class="secondary-title">인재목록</h2>
        <!-- 목록 -->
        <ol class="result-list">
        </ol>
        <!-- //목록 -->
        <a href="/resume/hr_list" class="btn-view-all" style="top: 0.90rem;font-weight: 600;">더보기</a>
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
            Business of disseminating job information : J15000202540002</span>
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

<template id="tpl_list">
  <li class="list-item">
    <div class="content-li" data-ru_idx="{{ru_idx}}">
      <div class="content-info">
        <div class="content-body">
          <div class="img">
            <img src="{{ru_resume_img}}">
            <?php if ($mm_type === "COMP") { ?><button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
            <p class="recomm-badge"><span>추천</span></p>
          </div>
          <p class="star-rating"><span class="star" style="width:100%;"></span><span class="sr-text">평점</span><span class="sr-text">5</span></p>
          <div class="desc">
            <div class="wicon">{{ru_work_img}}</div>
            <div class="title ellipsis">{{mm_name}}</div>
            <div class="summary">
              <ul class="working-day">{{ru_week}}</ul>
            </div>
            <div class="summary">
              <p>{{mm_nation}}</p>
              <p>{{mm_age}}</p>
              <p>{{ad_addr}}</p>
            </div>
            <div class="summary2">
              <p class="pricetype">{{ru_price_type}}</p>
            </div>
            <div class="summary3">
              <p class="worktype">{{ru_work_type}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </li>
</template>
<template id="tpl_reco_list">
  <div class="keen-slider__slide ">
    <div class="person-card" data-ru_idx="{{ru_idx}}">
      <div class="thumb-wrap">
        <div class="img">
          <img src="{{ru_resume_img}}">
          <?php if ($mm_type === "COMP") { ?><button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
        </div>
      </div>
      <div class="desc">
        <div class="content-wrap">
          <p class="category">
            {{ru_work_img}}
          </p>
          <div class="title-wrap">
            <p class="title"><strong>{{mm_name}}</strong></p>
            <p class="area">{{ad_addr}}</p>
          </div>
          <div class="meta">
            <p class="national">{{mm_nation}}</p>
            <p class="age">{{mm_age}}</p>
            <p class="work_type">{{ru_work_type}}</p>
            <p class="price_type">{{ru_price_type}}</p>
          </div>
          <div class="hashtag">
            {{ru_my_type}}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

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


  function getRecoList() {
    var params = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "10"
    };
    $.ajax({
      url: "/resume_proc/search_list2025",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code == "00") {
          var list = ret.data.list;
          $("#recomm-list").html("");
          $(".arrow--left").remove();
          $(".arrow--right").remove();
          list.forEach(function(row) {
            var $clone = $("#tpl_reco_list").clone();
            var $content = $clone.html();

            $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
            $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
            $content = $content.replaceAll("{{mm_name}}", row.mm_name);
            $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);
            $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
            $content = $content.replaceAll("{{mm_age}}", row.mm_age + "대");
            $content = $content.replaceAll("{{ru_price_type}}", getPriceType(row.ru_price_type));
            $content = $content.replaceAll("{{ru_work_type}}", getWorkType(row.ru_work_type));
            $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
            $content = $content.replaceAll("{{ru_my_type}}", getHashTagJS(row.ru_my_type, 2));
            $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));

            $clone.html($content);
            $("#recomm-list").append($clone.html());
          });
          new KeenSlider(".recomm-slider .slider-wrap", {
            loop: false,
            mode: 'free',
            slides: {
              perView: "auto"
            },
          });
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  function getResumeList() {
    var params = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "5"
    };
    $.ajax({
      url: "/resume_proc/search_list2025",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code == "00") {
          var list = ret.data.list;
          $(".result-list").html("");
          list.forEach(function(row) {
            var $clone = $("#tpl_list").clone();
            var $content = $clone.html();

            $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
            $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
            $content = $content.replaceAll("{{mm_name}}", row.mm_name);
            $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);
            $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
            $content = $content.replaceAll("{{mm_age}}", row.mm_age + "대");
            $content = $content.replaceAll("{{ru_price_type}}", getPriceType(row.ru_price_type));
            $content = $content.replaceAll("{{ru_work_type}}", getWorkType(row.ru_work_type));
            $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
            $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
            $content = $content.replaceAll("{{ru_week}}", row.ru_week);

            $clone.html($content);
            $(".result-list").append($clone.html());
          });
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }


  function fRemoveBigAdrJS(arg) {

    const parts1 = arg.split(",");
    arg = parts1[0];

    arg = arg.replace("서울특별시 ", "서울시 ");
    arg = arg.replace("경기 ", "");
    arg = arg.replace("강원 ", "");
    arg = arg.replace("충남 ", "");
    arg = arg.replace("충북 ", "");
    arg = arg.replace("경남 ", "");
    arg = arg.replace("경북 ", "");
    arg = arg.replace("전남 ", "");
    arg = arg.replace("전북 ", "");
    arg = arg.replace("제주 ", "");
    arg = arg.replace("경기도 ", "");
    arg = arg.replace("강원특별자치도 ", "");
    arg = arg.replace("충청남도 ", "");
    arg = arg.replace("충청북도 ", "");
    arg = arg.replace("경상남도 ", "");
    arg = arg.replace("경상북도 ", "");
    arg = arg.replace("전라남도 ", "");
    arg = arg.replace("전북특별자치도 ", "");
    arg = arg.replace("제주특별자치도 ", "");
    arg = arg.replace("세종특별자치시 ", "세종시 ");
    arg = arg.replace("광주광역시 ", "광주시 ");
    arg = arg.replace("인천광역시 ", "인천시 ");
    arg = arg.replace("울산광역시 ", "울산시 ");
    arg = arg.replace("부산광역시 ", "부산시 ");
    arg = arg.replace("대구광역시 ", "대구시 ");
    arg = arg.replace("대전광역시 ", "대전시 ");

    const parts = arg.split(" ");
    if (parts.length >= 3) {
      return parts[0] + " " + parts[2];
    } else {
      return arg;
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
    const matches = work_type.match(/<span>(.*?)<\/span>/g);

    if (!matches) {
      return "";
    }
    return matches.map(item => {
      const text = item.replace(/<\/?span>/g, '').trim();
      let cls = '';
      if (text === '주간') {
        cls = 'wtype1';
      } else if (text === '야간') {
        cls = 'wtype2';
      } else if (text === '2교대') {
        cls = 'wtype3';
      }
      return `<em class="${cls}">${text}</em>`;
    }).join('');
  }

  function getHashTagJS(hash, max = 10) {
    let result = "";
    let tmp = hash;
    tmp = tmp.replaceAll("<p class=\'tag\' style=\'width:100%;text-align:right;\'>", "");
    tmp = tmp.replaceAll("</p>", "");
    let arr_hash = tmp.trim().split(" ");
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


  window.addEventListener('DOMContentLoaded', function() {
    getRecoList();
    getResumeList();
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

  $(document).on('click', '.desc', function() {
    <?php if ($mm_type !== "COMP") { ?>
      commonAlert("기업회원 전용 서비스입니다.", function() {});
      return;
    <?php } else { ?>
      var ruIdx = $(this).closest('.content-li,.person-card').data('ru_idx');
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.location.href = url;
      }
    <?php } ?>
  });
</script>

<?= showPopupMobile() ?>
