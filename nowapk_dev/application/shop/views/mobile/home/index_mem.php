<?php
$banner_list = getBannerList("BN00302011");

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
        <div class="sticky-bottom">
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
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
    background: #fff;
    margin-bottom: 10px;
    transition: background 0.2s ease;
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
    height: 19px;
  }

  .content-info .title {
    font-size: 18px;
    font-weight: bold;
    margin: 1px 0px;
  }

  .content-info .summary3 {
    position: absolute;
    top: 6px;
    right: 10px;
    color: #000;
    height: 17px;
    text-align: right;
    font-size: 16px;
    font-weight: 600;
  }

  .content-info .summary3 em {
    position: relative;
    display: inline-block;
    font-style: normal;
    padding-left: 5px;
  }

  .content-info .summary3 em:not(:last-child) {
    padding-right: 5px;
  }

  .content-info .summary3 em:not(:last-child)::after {
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

  .content-info .summary3 em.wtype1 {
    color: #3a70db;
  }

  .content-info .summary3 em.wtype2 {
    color: #F86048;
  }

  .content-info .summary3 em.wtype3 {
    color: #1B9D7D;
  }

  .content-info .content-body {
    position: relative;
    height: 95px;
    overflow: hidden;
  }

  .content-info .desc {
    position: relative;
    height: 95px;
    margin: 0 0 0 105px;
  }

  .content-info .desc .ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 80%;
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
    font-size: 16px;
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
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0;
    color: #1b9d7d;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }

  .content-info .summary2 .summary2 p {
    margin: 0;
    text-align: right;
  }

  .content-info .summary2 .period {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 16px;
    font-weight: 600;
  }

  .content-info .summary2 .amount em {
    font-style: normal;
  }

  .content-info .summary2 .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #777;
  }

  .content-info .summary2 .period em.on {
    color: #1B9D7D;
  }

  .content-info .summary2 .period em:not(:last-child) {
    padding-right: 5px;
  }

  .content-info .summary2 .period em:not(:last-child)::after {
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

  .result-list .list-item+.list-item {
    border-top: 0;
  }

  .section .container {
    padding-top: 0;
  }

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

  .btn-view-all {
    display: block;
    position: absolute;
    top: 0;
    right: 1.4375rem;
    font-size: 0.8125rem;
    font-weight: 300;
  }

  .section+.section {
    margin-top: 0;
    border-top: 0;
  }

  /* 메인 비주얼 슬라이더: 활성 슬라이드만 클릭 가능하도록 처리 */
  /* 겹쳐진 슬라이드가 클릭을 가로채는 문제 예방 */
  .main-visual .visual-slider .slider-wrap .keen-slider__slide {
    pointer-events: none;
  }

  .main-visual .visual-slider .slider-wrap .keen-slider__slide.active {
    pointer-events: auto;
    z-index: 1;
  }

  /* 배너 이미지 전체가 클릭 영역이 되도록 앵커를 블록화 */
  .main-visual .visual-slider .visual-img a {
    display: block;
    width: 100%;
    height: 100%;
  }
</style>
<div class="page page-home">
  <div class="header is-transparency">
    <h1 class="top-logo"><span class="sr-text">지금 뜨는, 좋은 일자리 NOW JOB</span></h1>
    <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span>
      <span class="cnt"><?= $g_alarm_cnt ?></span>
    </a>
  </div><!-- //header -->

  <div class="page-content">

    <div class="main-visual">
      <div class="visual-slider">
        <div class="slider-wrap keen-slider">
          <?php foreach ($banner_list as $banner) : ?>
            <div class="keen-slider__slide">
              <div class="visual-img">
                <?php if ($banner["bn_link_yn"] == "Y") : ?>
                  <?php
                  // community가 포함된 링크를 setting으로 변경
                  $banner_link = str_replace("community", "setting", $banner["bn_link"]);
                  // _blank를 _self로 변경
                  $banner_target = ($banner["bn_link_type"] == "_blank") ? "_self" : $banner["bn_link_type"];
                  ?>
                  <a href="<?= $banner_link ?>" target="<?= $banner_target ?>">
                    <img src="<?= $banner["bg_img"] ?>" alt="메인 배너 이미지">
                  </a>
                <?php else : ?>
                  <img src="<?= $banner["bg_img"] ?>" alt="메인 배너 이미지">
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

    <div class="section" style="border-bottom: none;">
      <div class="container" style="padding-top: 1.15rem; padding-bottom: 1.15rem;">
        <h2 class="secondary-title">지금 뜨는 업체</h2>
        <!-- <a href="/recruit/clist" class="btn-view-all">더보기</a> -->
        <style>
          .hot-list .keen-slider__slide {
            max-width: 8rem;
          }

          .hot-list .thumb {
            width: 7.5rem;
            padding: 100% 0 0;
            /* 72.5% */
          }

          .main-visual .visual-slider .dots {
            bottom: 10%;
          }
        </style>
        <div class="hot-list">
          <div class="slider-wrap keen-slider">
            <?php foreach ($up_list as $row) : ?>
              <div class="keen-slider__slide">
                <a href="/recruit/company/<?= $row["mm_idx"] ?>">
                  <!-- 로고 -->
                  <div class="thumb">
                    <img data-src="<?= getCompImg($row) ?>" alt="" class="lozad" style="top:32%; max-width: 65%; max-height: 60%;">
                    <p class="name" style="position: absolute; bottom: 20%; /* 26%; */ width: 100%; text-align: center; font-size: 0.7375rem;"><?= $row["mm_comp_name"] ?></p>
                    <p class="name" style="position: absolute; bottom: 2%; width: 100%; text-align: center; font-size: 0.8rem; color: #1B9D7D; "><?= $row["cnt"] ?> <span style="color:#222;">건</span></p>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <script>
          window.addEventListener('DOMContentLoaded', function() {
            new KeenSlider(".hot-list .slider-wrap", {
              loop: false,
              mode: 'free',
              slides: {
                perView: "auto"
              },
            });
          });
        </script>
      </div>
    </div>

    <div class="section">
      <div class="container">
        <h2 class="secondary-title">추천공고</h2>
        <ol class="result-list">
        </ol>
        <a href="/recruit/job_list" class="btn-view-all" style="font-weight: 600;">더보기</a>
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
            Tel : 032-833-0019 E-mail : nau2023@naver.com
          </span>
        </p>
        <p>Copyright NOW D&I Co., Ltd all right reserved.</p>
      </div>
    </div>

    <a href="#app" class="btn-top js-goto-top"><span class="sr-text">Top</span></a>
  </div><!-- //page-content -->

  <!-- bottom-nav -->
  <?php $select_menu = "home"; ?>
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
  <!-- //bottom-nav -->
</div>
<!-- 알림목록 -->
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<template id="tpl_list">
  <li class="list-item">
    <div class="content-li" data-rc_idx="{{rc_idx}}">
      <div class="content-info">
        <div class="content-body">
          <div class="img">
            <img src="{{rt_recruit_img}}">
            <?php if ($mm_type === "MEM") { ?><button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
          </div>
          <div class="desc">
            <div class="wicon">
              {{rt_work_img}}
            </div>
            <div class="title ellipsis">
              {{rc_name}}
            </div>
            <div class="summary">
              <p>{{rc_nation_nm}}</p>
              <p>{{rc_age_nm}}</p>
            </div>
            <div class="summary area">
              <p>{{ad_addr}}</p>
            </div>
            <div class="summary2">
              <p class="period">{{rc_price_type}}</p>
              <p class="amount">{{rc_price_pay}}</p>
            </div>
          </div>
        </div>
        <div class="summary3">{{rt_work_type}}</div>
      </div>
    </div>
  </li>
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
      "limit": "24"
    };

    comAjax("/recruit_proc/getRecruitRecoList", params, function(ret) {
      if (ret.code == "00") {
        var list = ret.data.list;
        $(".result-list").html("");

        list.forEach(function(row) {
          var $clone = $("#tpl_list").clone();
          var $content = $clone.html();
          $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
          $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
          $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
          $content = $content.replaceAll("{{rc_name}}", row.rc_name);
          $content = $content.replaceAll("{{rc_age_nm}}", row.rc_age_nm);
          $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);
          $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img);
          $content = $content.replaceAll("{{rt_work_type}}", getConvertWorkType(row.rt_work_type));
          if (row.rc_nation_nm == "무관" || row.rc_nation_nm == "내국인,외국인") {
            $content = $content.replaceAll("{{rc_nation_nm}}", "국적무관");
          } else {
            $content = $content.replaceAll("{{rc_nation_nm}}", row.rc_nation_nm.replace("만", ""));
          }
          $content = $content.replaceAll("{{rc_price_type}}", getConvertPriceType(row.rc_price_type, row.rc_price_rep));
          $content = $content.replaceAll("{{rc_price_pay}}", getConvertPrice(row.rc_price_pay));
          $clone.html($content);

          $(".result-list").append($clone.html());
        });

      } else {

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

  function getConvertWorkType(work_type) {
    if (!work_type) {
      return "";
    }
    let items = [];
    const spanRegex = /<span>(.*?)<\/span>/g;
    let match;
    while ((match = spanRegex.exec(work_type)) !== null) {
      items.push(match[1]);
    }
    if (items.length === 0) {
      items = work_type.split(/\s*,\s*/);
    }
    const item_set = [...new Set(items.map(s => s.trim()))];
    const has_jugan = item_set.includes('주간');
    const has_yagan = item_set.includes('야간');
    const has_2shift = item_set.includes('2교대');
    if (has_jugan && has_yagan && has_2shift) {
      return '<em class="wtype0">근무협의</em>';
    }
    let result = '';
    item_set.forEach(text => {
      if (text === '') return;
      let cls = '';
      if (text === '주간') {
        cls = 'wtype1';
      } else if (text === '야간') {
        cls = 'wtype2';
      } else if (text === '2교대') {
        cls = 'wtype3';
      }
      result += `<em class="${cls}">${text}</em>`;
    });
    return result;
  }

  function getConvertPriceType(rc_price_type, rc_price_rep) {

    if (rc_price_type == "협의") {
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

  function getConvertPrice(rc_price_pay) {

    if (!rc_price_pay) {
      return '급여협의';
    }

    return Number(rc_price_pay).toLocaleString() + "원";
  }

  window.addEventListener('DOMContentLoaded', function() {
    getRecoList();
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
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.location.href = url;
    }
  });
</script>