<?php
$banner_list = getBannerList("BN00010001");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<style>
  /* 페이지 레이아웃 */
  .page-content {
    background: #fff;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    touch-action: pan-y;
  }

  .page-recruit {
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    touch-action: pan-y;
  }

  .current-area .btn {
    max-width: 100%;
    font-size: 1.8rem;
  }

  /* 검색 옵션 카테고리 */
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

  /* 기업 상단 이미지 영역 */
  .comp-info {
    position: relative;
    height: 300px;
    width: 100%;
    margin-top: 25px;
    margin-bottom: 25px;
    ;

  }

  .comp-info .comp-img {
    position: absolute;
    width: 100%;
    height: 300px;
    border: 0;
    overflow: hidden;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  .comp-info .comp-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }

  .comp-info .comp-img .alt-text {
    display: none;
    width: 100%;
    height: 100%;
    text-align: center;
    line-height: 300px;
    font-size: 36px;
    font-weight: 700;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .comp-info .comp-name {
    position: absolute;
    bottom: 20px;
    width: 100%;
    height: 24px;
    font-size: 24px;
    font-weight: 400;
    letter-spacing: 5px;
    text-align: center;
  }
</style>
<div class="page-recruit">
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/main_visual_header.php'); ?>

  <div class="page-content">
    <div class="comp-info">
      <div class="comp-img">
        <img src="<?= $comp_thumb ?>" alt="<?= $comp_name ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
        <div class="alt-text"><?= $comp_name ?></div>
      </div>
    </div>
    <!-- 상세 검색 옵션 선택시: has-option 클래스 추가 -->
    <div class="search-option has-option">
      <style>
        .current-area {
          padding: 0.5rem 0 0.50rem;
        }

        .search-option.has-option .btn-option {
          top: 0.975rem;
        }
      </style>
      <?php /* 현재 위치 - 주석처리됨
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
      */ ?>
    </div>

    <div class="section is-last">
      <div class="container">
        <h2 class="sr-text">채용공고</h2>
        <ol class="result-list result-list-card" id="ajaxList" data-page="1" data-more="Y">
        </ol>
        <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
          <p>등록된 정보가 없습니다.</p>
        </div>
      </div>
    </div>

  </div><!-- //page-content -->
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
</div>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<?php /* 채용공고 카드 템플릿 파셜 include */ ?>
<?php
$tpl_id = 'tpl_list';
$tpl_recomm_class = true;
$mm_type = $this->user->getUserType();
include(APPPATH . 'views/mobile/_partial/recruit_card_template.php');
?>

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
  var comp_idx = "<?= $comp_idx ?>";
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
  }); //2
  tab_list.push({
    df: "근무형태",
    text: "",
    active: ""
  }); //3
  tab_list.push({
    df: "근무요일",
    text: "",
    active: ""
  }); //4
  tab_list.push({
    df: "급여",
    text: "",
    active: ""
  }); //5
  tab_list.push({
    df: "성별",
    text: "",
    active: ""
  }); //6
  tab_list.push({
    df: "나이",
    text: "",
    active: ""
  }); //7

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
      comp_idx: comp_idx,
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
      $content = $content.replaceAll("{{rc_name}}", row.rc_name);
      $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);
      $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img);
      $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type || "");
      $content = $content.replaceAll("{{rc_price_type}}", row.rt_price_type || "");
      $content = $content.replaceAll("{{rc_price_pay}}", row.rt_price_pay || "");

      $clone.html($content);
      $(list_id).append($clone.html());

    });

    showEmpty();

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

  function showEmpty() {
    if ($(list_id).find("li").length == 0) {
      $(list_empty_id).show();
      $(list_id).hide();
    } else {
      $(list_id).show();
      $(list_empty_id).hide();
    }
  }

  // desc 클릭 시 채용공고 상세 페이지로 이동
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      window.location.href = '/recruit/job_post/' + rcIdx;
    }
  });

  $(window).scroll(function() {
    var $window = $(this);
    var scrollTop = $window.scrollTop() + $window.height();
    var windowHeight = $window.height();
    var documentHeight = $(list_id).height();

    if (list_loading == false) {
      if (scrollTop + 400 >= documentHeight) {
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
</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/recruit/index_search.php'); ?>
<script>
  window.addEventListener('DOMContentLoaded', function() {
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
      cashing_id: "recruit_cashing",
      list_id: list_id,
      category_list_id: "#tab_list",
      scroll_obj: $(".page-recruit"),
      fn_cashing: initCashingData,
      fn_load: initLoadData,
    };
    $.CashingAjax(params);

    // iOS 스크롤 활성화
    $('body').css({
      'overflow': 'auto',
      'overflow-y': 'auto',
      '-webkit-overflow-scrolling': 'touch'
    });

    $('html').css({
      'overflow': 'auto',
      'overflow-y': 'auto'
    });

    // 스크롤 컨테이너에도 iOS 스크롤 적용
    ['.page-recruit', '.page-content'].forEach(function(selector) {
      var $container = $(selector);
      if ($container.length && $container.css('overflow') !== 'hidden') {
        $container.css({
          'overflow-y': 'auto',
          '-webkit-overflow-scrolling': 'touch',
          'touch-action': 'pan-y'
        });
      }
    });
  });
</script>