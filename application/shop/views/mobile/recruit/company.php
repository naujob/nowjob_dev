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

  .search-option .categories .filter-tag-item.on::after {
    content: '×';
    font-size: 14px;
    color: #999;
    margin-left: 2px;
    line-height: 1;
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

<div id="jobPostPopup" class="job-post-popup" data-state="closed"></div>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<?php /* 채용공고 카드 템플릿 파셜 include */ ?>
<?php
$tpl_id = 'tpl_list';
$tpl_recomm_class = true;
$mm_type = $this->user->getUserType();
include(APPPATH . 'views/mobile/_partial/recruit_card_template.php');
?>

<template id="tpl_category">
  <label class="filter-tag-item sm {{active}} btn-category">
    <input type="checkbox" name="filter" value="{{category_name}}">
    <span class="cate">{{category_name}}</span>
  </label>
</template>


<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />

<style>
  /* 채용공고 팝업 */
  #jobPostPopup {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: #fff;
    overflow-y: auto;
    transform: translateX(100%);
    pointer-events: none;
  }

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
  var ad_addr = <?= json_encode(isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : "", JSON_UNESCAPED_UNICODE) ?>;
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
      $content = $content.replaceAll("{{rt_recom_badge}}", row.rt_recom_badge || "");

      $clone.html($content);
      $(list_id).append($clone.html());

    });

    showEmpty();

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

  // desc 클릭 시 채용공고 팝업 열기
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      window.openJobPostPopup(rcIdx);
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

  // 채용공고 팝업 함수
  window.openJobPostPopup = function(rcIdx) {
    $.ajax({
      url: '/recruit/job_post/' + rcIdx,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).done(function(html) {
      var popup = document.getElementById('jobPostPopup');
      popup.innerHTML = html;

      // 로드된 HTML 내 script 태그 실행
      var scripts = popup.querySelectorAll('script');
      scripts.forEach(function(script) {
        if (script.src) {
          var newScript = document.createElement('script');
          newScript.src = script.src;
          newScript.async = script.async;
          popup.appendChild(newScript);
        } else {
          var newScript = document.createElement('script');
          newScript.textContent = script.textContent;
          popup.appendChild(newScript);
        }
      });

      document.body.style.overflow = 'hidden';
      sessionStorage.setItem('recruit_came_from_job_post', 'true');
      sessionStorage.setItem('recruit_highlight_rc_idx', rcIdx);

      // GSAP으로 팝업 열기 애니메이션
      gsap.fromTo('#jobPostPopup',
        {
          x: '100%'
        },
        {
          duration: 0.5,
          x: '0%',
          ease: "power1.out",
          pointerEvents: 'auto'
        }
      );

      // 팝업 로드 후 공유 패널 초기화
      var shareButton = document.querySelector('#jobPostPopup .btn-share');
      if (shareButton && typeof initializeSharePanel === 'function') {
        initializeSharePanel();
      }

      // 팝업 로드 후 이미지 lazy load 초기화
      if (typeof initLazyLoad === 'function') {
        initLazyLoad();
      }

      // 팝업 로드 후 keen-slider 초기화
      if (typeof KeenSlider !== 'undefined') {
        new KeenSlider(".view-visual-slider .slider-wrap", {
          loop: true,
        }, [
          sliderDots,
          addActiveClass
        ]);
      }

      // 팝업 로드 후 이미지 확대 기능 초기화 (magnificPopup)
      if (typeof jQuery !== 'undefined') {
        $('#jobPostPopup .view-visual-slider').magnificPopup({
          delegate: 'img',
          type: 'image',
          gallery: {
            enabled: true
          }
        });
      }

      // 팝업 로드 후 상세정보 확대/핀치줌 기능 초기화
      if (typeof window.initJobPostDescFeatures === 'function') {
        window.initJobPostDescFeatures();
      }
    });
  };

  window.closeJobPostPopup = function(e) {
    if (e) e.preventDefault();

    // GSAP으로 팝업 닫기 애니메이션
    gsap.to('#jobPostPopup', {
      duration: 0.5,
      x: '100%',
      ease: "power1.out",
      pointerEvents: 'none',
      onComplete: function() {
        $('#jobPostPopup').html('');
      }
    });

    document.body.style.overflow = 'auto';
    document.documentElement.style.overflow = 'auto';
  };

  window.isJobPostPopupOpen = function() {
    // GSAP 애니메이션: opacity > 0.5이면 열려있는 것
    return parseFloat($('#jobPostPopup').css('opacity')) > 0.5;
  };
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
      $('.addrName').text(addrName).attr('data-selected', 'true');
    } else {
      addrName = '지역을 선택해 주세요';
      $('.addrName').text(addrName).attr('data-selected', 'false');
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