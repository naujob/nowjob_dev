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

  .recomm-slider.person .keen-slider__slide {
    flex: 0 0 180px !important;
    width: 180px !important;
    min-width: 180px;
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
    font-size: 13px;
    cursor: pointer;
    font-weight: 400;
  }

  .person-card .content-wrap {
    margin: -16px 0 0;
    padding: 22px 5px 5px;
    border-radius: 0 0 5px 5px;
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
    display: block;
    position: absolute;
    top: 50%;
    right: 0;
    width: 1px;
    height: 6px;
    margin: -3px 0 0;
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
    font-size: 11px;
    color: #3A70DB;
    line-height: 2;
    text-align: center;
    vertical-align: top;
    border-radius: 100px;

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
</style>
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

    <div class="section index_comp">
      <div class="container">
        <h2 class="secondary-title index">추천인재</h2>
        <div class="recomm-slider person">
          <div class="slider-wrap keen-slider" id="recomm-list">
          </div>
        </div>
      </div>
    </div>

    <div class="section index_comp">
      <div class="container">
        <h2 class="secondary-title">추천공고</h2>
        <!-- 목록 -->
        <ol class="result-list result-list-card">
        </ol>
        <!-- //목록 -->
        <!-- <a href="/recruit/job_list" class="btn-view-all">더보기</a> -->
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

<?php $tpl_id = 'tpl_list'; ?>
<?php include(APPPATH . 'views/mobile/_partial/recruit_card_template.php'); ?>
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
            <p class="title"><strong>{{ru_name}}</strong></p>
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


  // 추천인재 데이터를 가져오는 함수
  function getRecoList() {
    var params = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "10"
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
          $(".arrow--left").remove();
          $(".arrow--right").remove();

          if (list && list.length > 0) {
            list.forEach(function(row) {
              var $clone = $("#tpl_reco_list").clone();
              var $content = $clone.html();

              $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || "");
              $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
              $content = $content.replaceAll("{{ru_name}}", row.ru_name || "");
              $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img || "");
              $content = $content.replaceAll("{{mm_nation}}", row.mm_nation || "");
              $content = $content.replaceAll("{{mm_age}}", (row.mm_age || "") + "대");
              $content = $content.replaceAll("{{ru_price_type}}", row.ru_price_type || "");
              $content = $content.replaceAll("{{ru_work_type}}", row.ru_work_type || "");
              $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img || "");
              $content = $content.replaceAll("{{ru_my_type}}", row.ru_my_type || "");

              $clone.html($content);
              $("#recomm-list").append($clone.html());
            });

            // 슬라이더 초기화
            new KeenSlider(".recomm-slider .slider-wrap", {
              loop: false,
              mode: 'free-snap',
              slides: {
                perView: "auto",
                spacing: 10
              },
              breakpoints: {
                "(min-width: 768px)": {
                  slides: {
                    perView: 3
                  }
                }
              }
            });
          } else {
            console.log("추천인재 데이터가 없습니다.");
            $("#recomm-list").html("<p style='text-align:center; padding:20px; color:#999;'>추천인재가 없습니다.</p>");
          }
        } else {
          console.error("추천인재 API 응답 오류:", ret.message);
          $("#recomm-list").html("<p style='text-align:center; padding:20px; color:#999;'>데이터를 불러올 수 없습니다.</p>");
        }
      },
      error: function(xhr, status, error) {
        console.error("추천인재 데이터 가져오기 오류:", error);
        $("#recomm-list").html("<p style='text-align:center; padding:20px; color:#999;'>데이터를 불러올 수 없습니다.</p>");
      }
    });
  }

  // 추천공고 데이터를 가져오는 함수 (함수명 변경)
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
        $(".result-list").html("");

        list.forEach(function(row) {
          var $clone = $("#tpl_list").clone();
          var $content = $clone.html();
          $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
          $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
          $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
          // 제목이 18자 이상이면 ...으로 자르기
          var rc_name = row.rc_name;
          if (rc_name && rc_name.length > 18) {
            rc_name = rc_name.substring(0, 18) + '...';
          }
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
          $clone.html($content);

          $(".result-list").append($clone.html());
        });

      } else {
        console.error("추천공고 데이터 로딩 실패:", ret.message);
      }
    });
  }




  window.addEventListener('DOMContentLoaded', function() {
    // 추천인재 데이터 로딩
    getRecoList();
    // 추천공고 데이터 로딩
    getRecruitRecoList();

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

  // 추천공고 클릭 이벤트
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.location.href = url;
    }
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
