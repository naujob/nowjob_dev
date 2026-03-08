<?php
$banner_list = getBannerList("BN00302041");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<style>
  .page-content {
    background: #fff;
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
    border: 1px solid #b0b0b0;
  }

  .result-list .content-li.recomm {
    border: 1px solid #EB5349;
  }

  .result-list .content-li:hover {
    background: #f8f8f8;
  }

  .content-info .desc {
    cursor: pointer;
  }

  .content-info .summary p {
    color: #000;
    font-weight: 600;
  }

  .content-info .summary + .summary {
    margin-top: 4px;
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
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777777;
  }

  .content-info .summary3 {
    font-size: 16px;
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

  .content-info .summary3 .worktype em.wtype0 {
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
    font-size: 13px;
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

  .recomm-badge {
    position: absolute;
    background: #EB5349;
    z-index: 1;
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
          <!-- 채용공고 작성 버튼 기업회원만 보이게 -->
          <?php if ($mm_type === "COMP") : ?>
            <a href="#" class="btn" id="btnRecruitList" style="position: absolute; top: 7.5rem; right: 1.5rem; border: 1px solid #57CCAF;">채용공고 등록</a>
          <?php endif; ?>
        </div>
        <div class="visual-img">
          <img src="<?= $banner["bg_img"] ?>" alt="">
        </div>
      </div>
    <?php endif; ?>

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
        <ol class="result-list result-list-card" id="ajaxList" data-page="1" data-more="Y">
        </ol>
        <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
          <p>등록된 정보가 없습니다.</p>
        </div>
      </div>
    </div>
    <div class="list-action">
      <a href="/resume/hr_map" class="btn-map"><span class="sr-text">지도보기</span></a>
    </div>

  </div><!-- //page-content -->
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
</div>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<template id="tpl_list">
  <li class="list-item">
    <div class="content-li recomm" data-ru_idx="{{ru_idx}}" data-mm_idx="{{mm_idx}}">
      <div class="content-info">
        <div class="content-body">
          <div class="img">
            <img src="{{ru_resume_img}}">
            <?php if ($mm_type === "COMP") { ?><button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
            <p class="recomm-badge"><span>추천</span></p>
          </div>
          <!-- <p class="star-rating"><span class="star" style="width:100%;"></span><span class="sr-text">평점</span><span class="sr-text">5</span></p> -->
          <div class="desc">
            <div class="wicon">{{ru_work_img}}</div>
            <div class="title ellipsis">{{mm_name}}</div>
            <div class="summary">
              <ul class="working-day">{{ru_week}}</ul>
            </div>
            <div class="summary">
              <p>{{mm_nation}}</p>
              <p>{{ma_age}}</p>
              <p>{{ad_addr}}</p>
            </div>
          </div>
          <div class="summary-container">
            <div class="summary3">
              <p class="worktype">{{ru_work_type}}</p>
            </div>
            <div class="summary2">
              <p class="pricetype">{{ru_price_type}}</p>
            </div>
          </div>
        </div>
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
  var ad_addr = <?php echo json_encode(isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : ""); ?>;
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
    df: "카테고리",
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
    df: "급여",
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
    getBoardList("");
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
        url: "/resume_proc/search_save_params2025",
        type: "post",
        data: params,
        traditional: false,
        dataType: "json",
        crossDomain: true,
        success: function(ret) {
          if (ret.code === "00") {
            // console.log('설정저장');
          }
        },
        error: function(xhr, status, error) {
          console.error("데이터 저장 오류:", error);
        }
      });
    }

    $.ajax({
      url: "/resume_proc/search_list2025",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        $(list_id).attr("data-more", ret.data.list.length > 0 ? "Y" : "N");
        if (ret.code === "00" && ret.data && Array.isArray(ret.data.list)) {
          list_page++;
          $(list_id).attr("data-page", list_page);
          showBoardList(ret.data.list);
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

      // $content가 null이거나 undefined인 경우 처리
      if (!$content || typeof $content !== 'string') {
        console.error('템플릿을 불러올 수 없습니다. #tpl_list 요소를 확인해주세요.');
        return;
      }

      if (row.pm_idx == null) {
        $content = $content.replaceAll("<div class=\"content-li recomm\"", "<div class=\"content-li\"");
        $content = $content.replaceAll("<p class=\"recomm-badge\"><span>추천</span></p>", "");
      }

      $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || '');
      $content = $content.replaceAll("{{mm_idx}}", row.mm_idx || "");
      $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      // 이름이 18자 이상이면 ...으로 자르기
      var mm_name = row.ru_name || '';
      if (mm_name && mm_name.length > 18) {
        mm_name = mm_name.substring(0, 18) + '...';
      }
      $content = $content.replaceAll("{{mm_name}}", mm_name);
      $content = $content.replaceAll("{{ru_week}}", row.ru_week || '');
      $content = $content.replaceAll("{{distance}}", (row.distance || 0) + "km");
      $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img || '');

      if (!row.mm_nation || (typeof row.mm_nation === 'string' && row.mm_nation.trim() == '')) {
        $content = $content.replaceAll("{{mm_nation}}", "");
        $content = $content.replaceAll("class=\"national\" style=\"display: inline-block;\"", "class=\"national\" style=\"display: none;\"");
      } else {
        $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
      }

      if (row.mm_age == '' || row.mm_age == '0') {
        $content = $content.replaceAll("{{ma_age}}", "");
        $content = $content.replaceAll("class=\"age\" style=\"display: inline-block;\"", "class=\"age\" style=\"display: none;\"");
      } else {
        $content = $content.replaceAll("{{ma_age}}", (row.mm_age || '') + "세");
      }

      $content = $content.replaceAll("{{ru_price_type}}", getPriceType(row.ru_price_type));
      $content = $content.replaceAll("{{ru_work_type}}", getWorkType(row.ru_work_type));
      $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img || '');
      $content = $content.replaceAll("{{ad_addr}}", row.ad_addr ? fRemoveBigAdrJS(row.ad_addr) : '');

      $clone.html($content);

      $(list_id).append($clone.html());
    });

    showEmpty();
  }


  function getKoreanAge(birth) {
    if (!birth || birth.length !== 8) {
      return 0;
    }
    let formattedBirth = birth.slice(0, 4) + "-" + birth.slice(4, 6) + "-" + birth.slice(6, 8);
    let birthday = new Date(formattedBirth);
    let currentDate = new Date();
    let age = currentDate.getFullYear() - birthday.getFullYear();
    return age;
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
      } else if (text === '근무협의' || text === '시간협의') {
        // 근무협의는 근무형태가 3개 이상 선택된 경우 (시간협의도 호환성을 위해 처리)
        cls = 'wtype0';
        // 시간협의가 오면 근무협의로 변환
        if (text === '시간협의') {
          text = '근무협의';
        }
      }
      return `<em class="${cls}">${text}</em>`;
    }).join('');
  }

  $(document).on('click', '.desc', function() {
    var $target = $(this).closest('.content-li');
    var ruIdx = $target.data('ru_idx');
    var resumeMmIdx = $target.data('mm_idx'); // 이력서 소유자 인덱스
    
    // 개인회원이고 자신의 이력서인 경우 클릭 가능
    if (currentUserType === "MEM" && currentUserMmIdx && resumeMmIdx && currentUserMmIdx == resumeMmIdx) {
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.location.href = url;
      }
      return;
    }
    
    // 기업회원인 경우 클릭 가능
    <?php if ($mm_type === "COMP") { ?>
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.location.href = url;
      }
    <?php } else { ?>
      // 개인회원이지만 자신의 이력서가 아닌 경우
      commonAlert("기업회원 전용 서비스입니다.", function() {});
    <?php } ?>
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

  // 여러 스크롤 컨테이너에서 하단 도달 시 추가 로드
  function onScrollLoadMore(e) {
    var $target = $(this);
    var isWindow = this === window;
    var isDocument = this === document;
    var scrollTopVal = isWindow ? $(window).scrollTop() : (isDocument ? $(document).scrollTop() : $target.scrollTop());
    var heightVal = isWindow ? $(window).height() : (isDocument ? $(document).height() : $target.innerHeight());
    var containerScrollHeight = (isWindow || isDocument) ?
      (document.documentElement && document.documentElement.scrollHeight ? document.documentElement.scrollHeight : document.body.scrollHeight) :
      (this && this.scrollHeight ? this.scrollHeight : $(list_id).height());

    var scrollPos = scrollTopVal + heightVal;
    var documentHeight = Math.max(containerScrollHeight || 0, $(list_id).height());

    if (!list_loading && scrollPos + 400 >= documentHeight) {
      if ($(list_id).attr('data-more') === "Y") {
        list_loading = true;
        setTimeout(function() {
          list_loading = false;
        }, 300);
        getBoardList("");
      }
    }
  }

  $(window)
    .add($(document))
    .add($('body'))
    .add($('.page-recruit'))
    .add($('.page-content'))
    .add($('#app'))
    .on('scroll', onScrollLoadMore);

  // 채용공고 작성 버튼 클릭 이벤트 20250923
  // 이벤트 위임 사용 (main_menu.php include 이후에도 동작 보장)
  $(document).off('click', '#btnRecruitList').on('click', '#btnRecruitList', function(e) {
    e.preventDefault();

    // 공통 함수 사용 (main_menu.php에 정의된 전역 함수)
    if (typeof window.handleRecruitPageNavigation === 'function') {
      window.handleRecruitPageNavigation();
    } else {
      // 함수가 아직 로드되지 않은 경우 (안전장치)
      console.error('handleRecruitPageNavigation 함수를 찾을 수 없습니다.');
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
          console.error("채용공고 개수 확인 오류:", error);
          commonAlert("채용공고 개수를 확인할 수 없습니다.", function() {});
        }
      });
    }
  });
</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/resume/index_search2025_new.php'); ?>
<script>
  // DOMContentLoaded는 한 번만 실행되도록 플래그 설정
  var domContentLoadedExecuted = false;
  window.addEventListener('DOMContentLoaded', function() {
    // 이미 실행되었다면 중복 실행 방지
    if (domContentLoadedExecuted) {
      return;
    }
    domContentLoadedExecuted = true;

    // 스크롤 차단 방지: 초기 진입 시 html/body overflow 해제
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

    initCategory();
    initTabUi();

    var params = {
      cashing_id: "resume_cashing",
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

  // 스와이프 뒤로가기 비활성화 (이 페이지에서는 스와이프로 뒤로가기 불가)
  window.DISABLE_SWIPE_BACK = true;
</script>
