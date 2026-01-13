<?php
$banner_list = getBannerList("BN00010001");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<style>
  .search-result .content-area {
    margin: 0 10px;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
  }

  .search-result .sidebar {
    width: 200px;
    height: 800px;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 200px;
    flex: 0 0 200px;
    position: sticky;
    top: 90px;
  }

  .adBanner {
    position: relative;
    margin-bottom: 10px;
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

  .adBanner1 {
    width: 200px;
    height: 600px;
  }

  .adBanner.adBanner2 {
    width: 200px;
    height: 200px;
  }

  .arrow--disabled {
    display: none;
  }

  .recruit-card .type em {
    display: inline-block;
    font-style: normal;
    font-weight: 500;
    color: #1B9D7D;
  }

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
    background: url("https://nowjob.co.kr/resource/shop/web/img/ic_bookmark_off.svg") no-repeat 0 0;
    background-size: contain;
    pointer-events: all;
    z-index: 1;
  }

  .btn-bookmark.on:after {
    background-image: inherit;
  }

  .result-list {
    margin-top: 20px;
  }

  .result-list .content-li {
    position: relative;
    width: 100%;
    height: 120px;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
    border: 0px;
    overflow: hidden;
    transition: background 0.2s ease;
    cursor: pointer;
  }

  .result-list .content-li:hover {
    background: #f8f8f8;
  }

  .result-list .content-li .content-info {
    width: 100%;
    height: 100%;
    padding: 5px;
  }

  .result-list .content-info .content-body {
    position: relative;
    overflow: hidden;
    display: flex;
    height: 100%;
    gap: 10px;
  }

  .result-list .content-info .img {
    position: relative;
    width: 130px;
    height: 110px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  .result-list .content-info .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .result-list .content-info .desc {
    position: relative;
    display: flex;
    height: 100%;
    width: 100%;
    gap: 10px;
  }

  .result-list .content-info .desc .desc1 {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 60%;
    justify-content: center;
  }

  .result-list .content-info .desc .desc1 .timestamp {
    padding-bottom: 5px;
    font-size: 14px;
    color: #777;
  }

  .result-list .content-info .desc .desc1 .reco-title {
    color: #1b9d7d;
    font-weight: 600;
  }

  .result-list .content-info .desc .desc1 .wicon img {
    height: 26px;
  }

  .result-list .content-info .desc .desc1 .title {
    font-size: 20px;
    font-weight: 600;
  }

  .result-list .content-info .desc .desc1 .ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .result-list .content-info .summary {
    display: flex;
    font-size: 15px;
    gap: 5px;
    position: relative;
  }

  .result-list .content-info .summary p {
    position: relative;
    padding-right: 5px;
    margin: 0;
    font-size: 16px;
    color: #333;
  }

  .result-list .content-info .summary p:not(:last-child)::after {
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

  .result-list .content-info .summary em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .result-list .content-info .summary em:not(:last-child) {
    padding-right: 5px;
  }

  .result-list .content-info .summary em.on {
    color: #1b9d7d;
  }

  .result-list .content-info .summary em:not(:last-child)::after {
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

  .result-list .content-info .desc .desc2 {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 20%;
    align-items: center;
    justify-content: center;
  }

  .result-list .content-info .desc .desc2 p.area {
    color: #000;
    font-size: 18px;
    font-weight: 600;
  }

  .result-list .content-info .desc .desc2 .wtype {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-weight: 600;
  }

  .result-list .content-info .desc .desc2 .wtype em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #333;
  }

  .result-list .content-info .desc .desc2 .wtype em:not(:last-child) {
    padding-right: 5px;
  }

  .result-list .content-info .desc .desc2 .wtype em:not(:last-child)::after {
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

  .result-list .content-info .desc .desc2 .wtype em.wtype1 {
    color: #3a70db;
  }

  .result-list .content-info .desc .desc2 .wtype em.wtype2 {
    color: #F86048;
  }

  .result-list .content-info .desc .desc2 .wtype em.wtype3 {
    color: #1B9D7D;
  }

  .result-list .content-info .desc .desc3 {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 20%;
    align-items: flex-end;
    justify-content: center;
  }

  .result-list .content-info .desc .desc3 p {
    margin: 0;
    text-align: right;
  }

  .result-list .content-info .desc .desc3 .period {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;
  }

  .result-list .content-info .desc .desc3 .amount {
    font-weight: 600;
    font-size: 22px;
    color: #1b9d7d;
  }

  .result-list .content-info .desc .desc3 .amount em {
    font-style: normal;
  }

  .result-list .content-info .desc .desc3 .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .result-list .content-info .desc .desc3 .period em:not(:last-child) {
    padding-right: 5px;
  }

  .result-list .content-info .desc .desc3 .period em.on {
    color: #1b9d7d;
  }

  .result-list .content-info .desc .desc3 .period em:not(:last-child)::after {
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

  /* 추천 슬라이더 배경색 설정 */
  .recomm-slider.recruit {
    background-color: #fff;
  }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<div id="content" class="page page-recruit">
  <main class="page-content">
    <div class="visual-banner recruit">
      <div class="container">
        <div class="visual-content">
          <p>새로 등록된 추천 공고</p>
          <p class="headline">지금 뜨는, 좋은 일자리</p>
          <?php if ($mm_type === "MEM") { ?>
            <!-- 일반회원만 노출: 기업회원은 이력서 등록 버튼 숨김 -->
            <a href="javascript:void(0);" id="btnRegist" class="btn-round">이력서 등록</a>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="recomm-slider recruit">
      <div class="container swiperX">
        <h2 class="primary-title">추천공고</h2>
        <div class="slider-wrap keen-slider swiper-wrapperX" id="my-keen-slider" style="width: calc(100% - 0px);">
        </div>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="search-result">
          <div class="sidebar">

          </div>
          <div class="content-area">
            <?php include_once(get_base_url() . '/application/shop/views/web/_layout/search_recruit.php'); ?>
            <div class="result-wrap">
              <div class="result-header">
                <p class="total">채용정보 <em id="total_count"></em>건</p>
                <div class="orderby">
                  <label class="option">
                    <input type="radio" name="order_by" value="recent" checked>
                    <span class="label">최신순</span>
                  </label>
                  <label class="option">
                    <input type="radio" name="order_by" value="fav">
                    <span class="label">인기순</span>
                  </label>
                </div>
              </div>
              <ol class="result-list" id="ajaxList" data-page="1" style="min-height:400px;">
              </ol>
              <div id="page_cont">
              </div>
              <div class="list-action v2">
                <a href="/recruit/job_map" title="지도에서 찾기" class="btn-map"><span class="sr-text">지도보기</span></a>
              </div>
              <script>
                window.addEventListener('scroll', function() {
                  var minY = document.querySelector('.result-wrap').getBoundingClientRect().top - (window.innerHeight / 2);
                  var stickyElm = document.querySelector('.result-wrap .list-action');
                  if (document.querySelector('.result-list').children.length < 3) return;
                  if (minY <= document.body.scrollTop) {
                    stickyElm.style.position = 'sticky';
                  } else {
                    stickyElm.style.position = '';
                  }
                });
              </script>
            </div><!-- //result-wrap -->
          </div><!-- //content-area -->
          <div class="sidebar">
            <div class="adBanner adBanner2">
              <img src="/outer/ad/ad1.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
            </div>
            <div class="adBanner adBanner1">
              <a href="/community/event_view/8403" target="_blank">
                <img src="/outer/ad/nowjob-banner-vertical.png" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
              </a>
            </div>
          </div>
        </div><!-- //search-result -->
      </div><!-- //container -->
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<!-- 리스트 템플릿 -->
<template id="tpl_list">
  <li class="list-item" data-rc_idx="{{rc_idx}}">
    <div class="content-li">
      <div class="content-info">
        <div class="content-body">
          <div class="img">
            <img src="{{rt_recruit_img}}">
            <?php if ($mm_type === "MEM") { ?><button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
            <p class="recomm-badge"><span>추천</span></p>
          </div>
          <div class="desc">
            <div class="desc1">
              <div class="timestamp">{{timestamp}}</div>
              <div class="wicon">{{rt_work_img}}</div>
              <div class="title ellipsis">{{rc_name}}</div>
              <div class="summary">
                <p>{{rc_nation_nm}}</p>
                <p>{{rc_age_nm}}</p>
              </div>
            </div>
            <div class="desc2">
              <div class="summary">
                <p class="wtype">{{rt_work_type}}</p>
              </div>
              <p class="area">{{ad_addr}}</p>
            </div>
            <div class="desc3">
              <div class="summary">
                <p class="period">{{rc_price_type}}</p>
              </div>
              <p class="amount">{{rc_price_pay}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </li>
</template>

<!-- 추천 리스트 템플릿 -->
<template id="tpl_reco_list">
  <div class="keen-slider__slide">
    <div class="recruit-card">
      <div class="thumb-wrap">
        <div class="thumb">
          <a href="/recruit/job_post/{{rc_idx}}" target="_blank"><img src="{{rt_recruit_img}}" alt="" class=""></a>
        </div>
        <?php if ($mm_type === "MEM") { ?><button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
      </div>
      <div class="content-wrap">
        <div class="inline-group">
          {{rt_work_img}}
          <p class="area">{{ad_addr}}</p>
        </div>
        <p class="title"><strong><a href="/recruit/job_post/{{rc_idx}}" target="_blank">{{rc_name}}</a></strong></p>
        <div class="meta">
          <p class="type">{{rt_work_type}}</p>
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
<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />
<script>
  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = 1;
  var list_loading = false;
  var search_addr = [];
  var searchAreaStr = "";
  var addrName = "";
  var search_array = {
    category: "",
    work_type: "",
    week: "",
    price_type: "",
    sex: "",
    sage: "",
    eage: "",
    order_by: "",
  };

  $("[name='order_by']").click(function() {
    initPage(true);
  });

  function initPage(is_top) {
    list_page = 1;
    getBoardList(is_top);
  }

  function goPage(page) {
    list_page = page;
    getBoardList(true);
  }

  function getCheckedValues(name) {
    var result = "";
    $("[name='" + name + "']:checked").each(function() {
      var val = $(this).val();
      if (val !== "") {
        result += (result === "" ? "" : "^") + val;
      }
    });
    return result;
  }

  function setBoardParams() {

    var arr_category = getCheckedValues("category");
    var arr_work_type = getCheckedValues("work_type");
    var arr_price_type = getCheckedValues("price_type");
    var arr_week = getCheckedValues("week");
    var arr_sex = getCheckedValues("sex");

    search_array.sage = "";
    search_array.eage = "";

    var checkedAges = $("[name='eage']:checked").map(function() {
      return parseInt($(this).val().replace("대", ""), 10);
    }).get();

    if (checkedAges.length > 0) {
      var minAge = Math.min(...checkedAges);
      var maxAge = Math.max(...checkedAges);
      search_array.sage = minAge;
      search_array.eage = maxAge + 9;
    }

    search_array.category = arr_category;
    search_array.work_type = arr_work_type;
    search_array.week = arr_week;
    search_array.price_type = arr_price_type;
    search_array.sex = arr_sex;
    search_array.order_by = $("[name='order_by']:checked").val();
  }

  function getBoardList(is_top) {
    getBoardListDelay(is_top);
  }

  function getBoardListDelay(is_top) {
    setBoardParams();
    var params = {
      page: list_page,
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
      order_by: search_array.order_by,
    };
    $.ajax({
      url: "/recruit_proc/getRecruitList",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code == "00") {
          $(list_id).attr("data-page", list_page);
          $("#total_count").html(ret.data.count);
          if (ret.data.pagenation && ret.data.pagenation.trim() !== '') {
            $("#page_cont").html(ret.data.pagenation.replace(/<li>/g, '').replace(/page-next/g, 'page-next btn'));
          } else {
            $("#page_cont").html('');
          }
          showBoardList(ret.data.list, is_top);
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  function showBoardList(list, is_top) {
    $(list_id).html("");

    list.forEach(function(row) {
      var $clone = $("#tpl_list").clone();
      var $content = $clone.html();

      $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
      $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      if (row.pm_idx == null) {
        $content = $content.replaceAll("<span>추천</span>", "");
        $content = $content.replaceAll("{{timestamp}}", "등록일 : " + row.up_dt);
      } else {
        $content = $content.replaceAll("{{timestamp}}", "<p class=\"reco-title\">지금뜨는 추천공고</p>");
      }
      $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
      $content = $content.replaceAll("{{rc_name}}", row.rc_name);

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

      $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);

      var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
      $images.slice(2).remove();
      var $categoryHtml = $images.parent().html();
      $content = $content.replaceAll("{{rt_work_img}}", $categoryHtml);

      $content = $content.replaceAll("{{rt_work_type}}", getConvertWorkType(row.rt_work_type));

      if (row.rc_price_type.indexOf('협의') > -1) {
        $content = $content.replaceAll("{{rc_price_type}}", "");
      } else {
        $content = $content.replaceAll("{{rc_price_type}}", getConvertPriceType(row.rc_price_type, row.rc_price_rep));
      }

      if (row.rc_price_type.indexOf('협의') > -1) {
        $content = $content.replaceAll("{{rc_price_pay}}", "급여협의");
      } else {
        $content = $content.replaceAll("{{rc_price_pay}}", Number(row.rc_price_pay).toLocaleString() + "원");
      }
      $content = $content.replaceAll("{{mm_id}}", row.mm_id);

      $clone.html($content);
      $(list_id).append($clone.html());
    });

    if (is_top == true) {
      var ajax_top = $(".search-result").position().top - 50;
      $('html, body').animate({
        scrollTop: ajax_top
      }, 400);
    }
  }

  function getRecoList() {
    var params = {};
    params["addr1"] = $("[name='searchArea']:checked").val() == "전국" ? "" : $("[name='searchArea']:checked").val();
    params["addr2"] = $("[name='searchArea2']:checked").val();
    params["addr3"] = $("[name='searchArea3']:checked").val();
    params["limit"] = "24";

    comAjax("/recruit_proc/getRecruitRecoList", params, function(ret) {
      if (ret.code == "00") {
        var list = ret.data.list;
        $("#my-keen-slider").html("");
        $(".arrow--left").remove();
        $(".arrow--right").remove();
        list.forEach(function(row) {
          var $clone = $("#tpl_reco_list").clone();
          var $content = $clone.html();

          if (row.rc_nation_nm == "무관" || row.rc_nation_nm == "내국인,외국인") {
            $content = $content.replaceAll("{{rc_nation_nm}}", "국적무관");
          } else {
            $content = $content.replaceAll("{{rc_nation_nm}}", row.rc_nation_nm.replace("만", ""));
          }

          $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
          $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
          $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
          $content = $content.replaceAll("{{rc_name}}", row.rc_name);
          $content = $content.replaceAll("{{distance}}", row.distance + "km")

          if (row.rc_age_nm == "무관") {
            $content = $content.replaceAll("{{rc_age_nm}}", "나이무관");
          } else {
            $content = $content.replaceAll("{{rc_age_nm}}", row.rc_age_nm);
          }

          $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);

          var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
          $images.slice(2).remove();
          var $categoryHtml = $images.parent().html();
          $content = $content.replaceAll("{{rt_work_img}}", $categoryHtml);
          $content = $content.replaceAll("{{rt_work_type}}", getConvertWorkType(row.rt_work_type));
          if (row.rc_price_type.indexOf('협의') > -1) {
            $content = $content.replaceAll("{{rc_price_type}}", "<em> </em>");
          } else {
            $content = $content.replaceAll("{{rc_price_type}}", getConvertPriceType(row.rc_price_type, row.rc_price_rep));
          }
          if (row.rc_price_type.indexOf('협의') > -1) {
            $content = $content.replaceAll("{{rc_price_pay}}", "급여협의");
          } else {
            $content = $content.replaceAll("{{rc_price_pay}}", Number(row.rc_price_pay).toLocaleString() + "원");
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
          loop: slideCount > perView,
          slides: {
            perView: 4
          },
        }, [
          addActiveClass,
          sliderArrows
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

      } else {

      }
    });
  }

  function getConvertWorkType(work_type) {
    if (!work_type) {
      return "";
    }
    // "시간협의"를 "근무협의"로 변환
    if (work_type.includes('시간협의')) {
      work_type = work_type.replace(/시간협의/g, '근무협의');
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
    // "근무협의"가 단독으로 있는 경우 처리
    if (item_set.length === 1 && item_set[0] === '근무협의') {
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
      } else if (text === '근무협의') {
        cls = 'wtype0';
      }
      result += `<em class="${cls}">${text}</em>`;
    });
    return result;
  }

  function getConvertPriceType(rc_price_type, rc_price_rep) {
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
</script>

<script>
  $(document).ready(function() {
    $('.btn-map').insertBefore('#bottomchatbtn');
    getRecoList();
    initPage(false);
  });

  $("#btnRegist").click(function() {
    if ($("#is_login").val() != "Y") {
      commonAlert("로그인후 이용가능한 기능입니다.", function() {
        // 확인 버튼 클릭 시 팝업만 닫히도록 처리
      });
      return;
    }

    if ($("[name='mm_type']").val() != "MEM") {
      commonAlert("일반회원만 이력서 작성이 가능합니다.", function() {});
      return;
    }

    // 이력서 개수 확인 후 페이지 이동
    $.ajax({
      url: "/mypage_proc/getResumeCount",
      type: "post",
      dataType: "json",
      success: function(ret) {
        if (ret.code == "00") {
          var resumeCount = ret.data.count || 0;

          // 이력서가 1개 이상이면 관리 페이지로, 0개이면 등록 페이지로 이동
          if (resumeCount >= 1) {
            location.href = "https://nowjob.co.kr/mypage/resume_list";
          } else {
            location.href = "https://nowjob.co.kr/mypage/resume_write";
          }
        } else {
          commonAlert(ret.msg || "이력서 개수를 확인할 수 없습니다.", function() {});
        }
      },
      error: function(xhr, status, error) {
        console.error("이력서 개수 확인 오류:", error);
        commonAlert("이력서 개수를 확인할 수 없습니다.", function() {});
      }
    });
  });
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.list-item').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.open(url, '_blank');
    }
  });
</script>