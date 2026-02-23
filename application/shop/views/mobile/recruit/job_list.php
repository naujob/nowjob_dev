<?php
$banner_list = getBannerList("BN00010001");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<style>
  .page-content {
    background: #fff;
  }

  .current-area .btn {
    max-width: 90%;
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
    border: 1px solid #b0b0b0;
    overflow: hidden;
    background: #fff;
    margin-bottom: 10px;
    transition: background 0.2s ease;
  }

  .result-list .content-li.recomm {
    border: 1.5px solid #EB5349;
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
    font-size: 16px;
    font-weight: 600;
    display: flex;
    justify-content: flex-end;
    align-items: center;
  }

  .content-info .summary3 em {
    position: relative;
    display: inline-block;
    font-style: normal;
    margin-left: 8px;
  }

  .content-info .summary3 em:first-child {
    margin-left: 0;
  }

  .content-info .summary3 em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: -4px;
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

  .recomm-badge {
    position: absolute;
    background: #EB5349;
    z-index: 1;
    margin: 3px;
    padding: 3px;
    border-radius: 3px;
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
    color: #222;
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
    justify-content: flex-end;
    position: relative;
    font-size: 16px;
    font-weight: 600;
    align-items: center;
  }

  .content-info .summary2 .amount {
    color: #1b9d7d;
  }

  .content-info .summary2 .amount em {
    font-style: normal;
  }

  .content-info .summary2 .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #777;
    margin-left: 8px;
  }

  .content-info .summary2 .period em:first-child {
    margin-left: 0;
  }

  .content-info .summary2 .period em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: -4px;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777777;
  }

  .content-info .summary2 .period em.on {
    color: #1B9D7D;
  }





  .result-list .list-item+.list-item {
    border-top: 0;
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
</style>
<div class="page-recruit">
  <div class="header is-transparency">
    <h1 class="top-logo"><span class="sr-text">지금 뜨는, 좋은 일자리 NOW JOB</span></h1>
    <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span>
      <!-- 미확인 알림개수 -->
      <span class="cnt"><?= $g_alarm_cnt ?></span>
    </a>
  </div><!-- //header -->

  <div class="page-content">
    <?php if ($banner != null) : ?>
      <div class="top-visual">
        <style>
          .top-visual .visual-content {
            padding: 0 7.729468599vw 6.14vw;
          }
        </style>
        <div class="visual-content" style="margin: 4.7rem 49.2753623188vw 0.0rem 0;">
          <p><?= $banner["bn_text1"] ?></p>
          <p class="headline" style="font-size: 6vw;"><?= $banner["bn_text2"] ?></p>
          <!-- 이력서 작성 버튼 일반회원만 보이게 -->
          <?php if ($mm_type === "MEM") : ?>
            <a href="#" class="btn" id="btnResumeList" style="position: absolute; top: 7.5rem; right: 1.5rem; border: 1px solid #57CCAF;">이력서 등록</a>
          <?php endif; ?>
        </div>
        <div class="visual-img">
          <img src="<?= $banner["bg_img"] ?>" alt="">
        </div>
      </div>
    <?php endif; ?>

    <!-- 상세 검색 옵션 선택시: has-option 클래스 추가 -->
    <div class="search-option has-option">
      <style>
        

        .search-option.has-option .btn-option {
          top: 0.975rem;
        }
      </style>
      <!-- 현재 위치 -->
      <div class="current-area">
        <div class="container">
          <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel addrName">전체</a>
          <!-- <?= $search_txt["searchAreaStr"]; ?> -->
        </div>
      </div>
      <!-- 선택된 카테고리 -->
      <div class="categories">
        <div class="container" id="tab_list">

        </div>
      </div>
      <a href="#advancedSearch" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text">상세검색 열기</span></a>
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
        <h2 class="sr-text">채용공고</h2>
        <ol class="result-list" id="ajaxList" data-page="1" data-more="Y">
        </ol>
        <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
          <p>등록된 정보가 없습니다.</p>
        </div>
      </div>
    </div>
    <div class="list-action">
      <a href="/recruit/job_map" class="btn-map"><span class="sr-text">지도보기</span></a>
    </div>

  </div><!-- //page-content -->
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
</div>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<template id="tpl_list">
  <li class="list-item">
    <div class="content-li recomm" data-rc_idx="{{rc_idx}}">
      <div class="content-info">
        <div class="content-body">
          <p class="recomm-badge"><span>추천</span></p>
          <div class="img">
            <img src="{{rt_recruit_img}}">
            <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button>
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

<template id="tpl_category">
  <label class="form-toggle-btn2 sm {{active}} btn-category">
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
  var ad_addr = "<?= $addr_info["ad_addr"] ?>";
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
  };

  var tab_list = [];
  tab_list.push({
    df: "근무지역",
    text: "",
    active: ""
  }); //0 - 지역 정보
  tab_list.push({
    df: "카테고리",
    text: "",
    active: ""
  }); //1
  tab_list.push({
    df: "근무형태",
    text: "",
    active: ""
  }); //2
  tab_list.push({
    df: "근무요일",
    text: "",
    active: ""
  }); //3
  tab_list.push({
    df: "급여",
    text: "",
    active: ""
  }); //4
  tab_list.push({
    df: "성별",
    text: "",
    active: ""
  }); //5
  tab_list.push({
    df: "나이",
    text: "",
    active: ""
  }); //6

  $(document).on("click", ".btn-category", function() {
    $("#advancedSearch").addClass("on");
  });

  $(document).on("click", ".form-checkbox", function(e) {
    e.stopPropagation();
  });

  function initCashingData() {
    list_page = $(list_id).attr("data-page");
    showEmpty();
  }

  function initLoadData() {
    setTimeout(function() {
      getBoardList("");
    }, 100);
  }

  function getBoardList(save_yn) {
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
          if (ret.code === "00") {
            //console.log('설정저장');
          }
        },
        error: function(xhr, status, error) {
          console.error("데이터 저장 오류:", error);
        }
      });
    }

    $.ajax({
      url: "/recruit_proc/getRecruitList",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        $(list_id).attr("data-more", ret.data.list.length > 0 ? "Y" : "N");
        if (ret.code === "00" && ret.data && Array.isArray(ret.data.list)) {
          //console.log(ret);
          list_page++;
          $(list_id).attr("data-page", list_page);
          setTimeout(function() {
            showBoardList(ret.data.list);
          }, 100);

        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  //데이터 보여주기
  function showBoardList(list) {

    list.forEach(function(row) {

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
      // 제목이 18자 이상이면 ...으로 자르기
      var rc_name = row.rc_name;
      if (rc_name && rc_name.length > 18) {
        rc_name = rc_name.substring(0, 18) + '...';
      }
      $content = $content.replaceAll("{{rc_name}}", rc_name);


      $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);
      $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img);
      $content = $content.replaceAll("{{rt_work_type}}", getConvertWorkType(row.rt_work_type));
      $content = $content.replaceAll("{{rc_price_type}}", getConvertPriceType(row.rc_price_type, row.rc_price_rep));
      $content = $content.replaceAll("{{rc_price_pay}}", getConvertPrice(row.rc_price_pay));

      $clone.html($content);
      $(list_id).append($clone.html());

    });

    showEmpty();

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

    if (!rc_price_pay || rc_price_pay == 0) {
      return '';
    }

    return Number(rc_price_pay).toLocaleString() + "원";
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

  // 주요 스크롤 컨테이너 모두에 스크롤 감지 (window, document, body, page 영역)
  $(window).add($(document)).add($('body')).add($('.page-recruit')).add($('.page-content')).add($('#app')).on('scroll', function() {
    var $target = $(this);
    var isWindow = this === window;
    var isDocument = this === document;
    var scrollTopVal = isWindow ? $(window).scrollTop() : (isDocument ? $(document).scrollTop() : $target.scrollTop());
    var heightVal = isWindow ? $(window).height() : (isDocument ? $(document).height() : $target.innerHeight());
    var containerScrollHeight = (isWindow || isDocument) ?
      (document.documentElement && document.documentElement.scrollHeight ? document.documentElement.scrollHeight : document.body.scrollHeight) :
      (this && this.scrollHeight ? this.scrollHeight : $(list_id).height());
    var scrollPos = scrollTopVal + heightVal;
    var documentHeight = containerScrollHeight || $(list_id).height();
    if (list_loading == false) {
      if (scrollPos + 400 >= documentHeight) {
        if ($(list_id).attr('data-more') == "Y") {
          list_loading = true;
          setTimeout(function() {
            list_loading = false;
          }, 300);
          getBoardList("");
        }
      }
    }
  });

  // 이력서 작성 버튼 클릭 이벤트 (공통 팝업 호출)
  $("#btnResumeList").click(function(e) {
    e.preventDefault();
    if (typeof window.openResumeNavigationPopup === 'function') {
      openResumeNavigationPopup();
    } else {
      commonAlert("이력서 이동 팝업을 불러올 수 없습니다. 잠시 후 다시 시도해주세요.", function() {});
    }
  });
</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/recruit/index_search.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
<script>
  window.addEventListener('DOMContentLoaded', function() {
    // 스크롤 강제 활성화 (모바일 웹뷰에서 overflow:hidden 잔존 대응)
    $('html, body').css({
      overflow: 'auto',
      'overflow-y': 'auto',
      height: 'auto'
    });

    const count = $('.selbtn').length;
    var addrNames = [];

    if ($('.selbtn').length > 0) {
      $('.selbtn').each(function() {
        const text = $(this).clone().children().remove().end().text().trim();
        addrNames.push(text);
      });
      addrName = addrNames.join(', ');
      $('.addrName').text(addrName);
    } else {
      addrName = '전체';
      $('.addrName').text(addrName);
    }

    const searchArea = [];
    search_addr = [];

    $('.selbtn').each(function() {
      const addr = $(this).data('addr');
      const step = $(this).data('step');
      if (addr) {
        searchArea.push({
          addr: addr,
          step: step
        });
        search_addr.push(addr);
      }
    });
    searchAreaStr = JSON.stringify(searchArea);

    // 페이지 로드 시 선택된 지역 정보를 selectedRegions에 반영
    if (typeof selectedRegions === 'undefined') {
      window.selectedRegions = [];
    }
    if (addrNames.length > 0) {
      window.selectedRegions = addrNames.slice(0, 3); // 최대 3개까지만
    } else {
      window.selectedRegions = [];
    }

    initCategory();
    initTabUi();

    var params = {
      cashing_id: "recruit_cashing",
      list_id: list_id,
      category_list_id: "#tab_list",
      scroll_obj: $(".page-recruit"),
      fn_cashing: initCashingData,
      fn_load: initLoadData,
    };
    $.CashingAjax(params);

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
  });
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.location.href = url;
    }
  });

  // 스와이프 뒤로가기 비활성화 (이 페이지에서는 스와이프로 뒤로가기 불가)
  window.DISABLE_SWIPE_BACK = true;
</script>
