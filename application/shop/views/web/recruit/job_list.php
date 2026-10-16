<?php
$banner_list = getBannerList("BN00010001");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<?= getCachCss(RES_PATH . "/css/recruit_list.css") ?>
<?= getCachCss(RES_PATH . "/css/recruit_card.css") ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<?= getCashJs(RES_PATH . "/js/ad-banner.js") ?>
<style>
  .search-result .content-area:first-child {
    margin-left: -65px;
}
  .search-result .content-area {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 1530px;
    flex: 0 0 1530px;
  }
  .result-header .total.today-new {
    margin-left: 28px;
  }
.recomm-slider.recruit .container.swiperX {
  padding: 0 52px;
  max-width: 1634px;
}
.arrow.arrow--left {
  left: -25px;
}
.recomm-slider .arrow.arrow--right {
    left: auto;
    right:  -17px;
}
</style>
<div id="content" class="page page-recruit">
  <main class="page-content">
    <!-- <div class="visual-banner recruit">
      <div class="container">
        <div class="visual-content">
          <p>새로 등록된 추천 공고</p>
          <p class="headline">지금 뜨는, 좋은 일자리</p>
          <?php if ($mm_type === "MEM") { ?> -->
    <!-- 일반회원만 노출: 기업회원은 이력서 등록 버튼 숨김 -->
    <!-- <a href="javascript:void(0);" id="btnRegist" class="btn-round">이력서 등록</a> -->
  <?php } ?>
  <!-- </div>
      </div>
    </div> -->
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
        <div class="content-area">
          <!-- get_base_url(): /website/dev_nowapk-->
          <?php include_once(get_base_url() . '/application/shop/views/web/_layout/search_recruit.php'); ?>
          <div class="result-wrap">
            <div class="result-header">
              <p class="total">채용정보 <em id="total_count"></em>건</p>
              <p class="total today-new">오늘 새로운 채용 <em><?= number_format(isset($today_recruit_count) ? $today_recruit_count : 0) ?></em>건</p>
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
            <a id="adBannerLink" href="https://naver.me/FDn3Ggxk" target="_blank">
              <img id="adBannerImg" src="/outer/ad/nowjob-banner-vertical_v5-2.png" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
            </a>
          </div>
        </div>
      </div><!-- //search-result -->
    </div><!-- //container -->
  </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<?php include_once(get_base_url() . '/application/shop/views/web/_layout/recruit_list_template.php'); ?>

<?php
$tpl_show_bookmark = true;
$tpl_show_hashtag  = true;
include_once(get_base_url() . '/application/shop/views/web/_layout/reco_card_template.php');
?>
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

    console.log('🔎 [PC job_list] getBoardList 호출:', {
      search_addr: search_addr,
      addrName: addrName,
      searchAreaStr: searchAreaStr
    });

    var params = {
      page: list_page,
      pagesize: 30,
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
          console.log('✅ [PC job_list] API 응답 성공:', {
            total: ret.data.count,
            listCount: ret.data.list ? ret.data.list.length : 0,
            returnedData: ret.data
          });

          $(list_id).attr("data-page", list_page);
          $("#total_count").html(ret.data.count);
          if (ret.data.pagenation && ret.data.pagenation.trim() !== '') {
            $("#page_cont").html(ret.data.pagenation.replace(/<li>/g, '').replace(/page-next/g, 'page-next btn'));
          } else {
            $("#page_cont").html('');
          }
          showBoardList(ret.data.list, is_top);
        } else {
          console.error('❌ [PC job_list] API 응답 실패:', ret);
        }
      },
      error: function(xhr, status, error) {
        console.error('❌ [PC job_list] API 요청 오류:', {
          status: status,
          error: error,
          xhr: xhr
        });
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
      $content = $content.replaceAll("{{ad_addr}}", row.ad_addr);
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

      $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type);

      // 협의인 경우 급여 타입 숨김
      if (row.rc_price_type.indexOf('협의') > -1) {
        $content = $content.replaceAll("{{rc_price_type}}", "");
        $content = $content.replaceAll("{{rc_price_display}}", "급여협의");
      } else {
        $content = $content.replaceAll("{{rc_price_type}}", row.rt_price_type);
        $content = $content.replaceAll("{{rc_price_display}}", Number(row.rc_price_pay).toLocaleString() + "원");
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
        list.forEach(function(row) {
          var $clone = $("#tpl_reco_list").clone(); //템플릿 복제
          var $content = $clone.html(); //복제한 템플릿에서 HTML 가져오기

          if (row.rc_nation_nm == "무관" || row.rc_nation_nm == "내국인,외국인") {
            $content = $content.replaceAll("{{rc_nation_nm}}", "국적무관");
          } else {
            $content = $content.replaceAll("{{rc_nation_nm}}", row.rc_nation_nm.replace("만", ""));
          }

          $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
          $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
          $content = $content.replaceAll("{{ad_addr}}", row.ad_addr);
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
          $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type);
          if (row.rc_price_type.indexOf('협의') > -1) {
            $content = $content.replaceAll("{{rc_price_type}}", "<em> </em>");
          } else {
            $content = $content.replaceAll("{{rc_price_type}}", row.rt_price_type);
          }
          if (row.rc_price_type.indexOf('협의') > -1) {
            $content = $content.replaceAll("{{rc_price_pay}}", "급여협의");
          } else {
            $content = $content.replaceAll("{{rc_price_pay}}", Number(row.rc_price_pay).toLocaleString() + "원");
          }
          $content = $content.replaceAll("{{mm_id}}", row.mm_id);
          $content = $content.replaceAll("{{rt_hash}}", row.rt_hash);

          $content = $content.replaceAll("{{timestamp}}", row.timestamp);

          $clone.html($content);

          $("#my-keen-slider").append($clone.html());
        });

        // main.js의 통합 슬라이더 초기화 함수 사용
        if (typeof initRecommendationSlider === 'function') {
          initRecommendationSlider('.recomm-slider.recruit');
        }

      } else {

      }
    });
  }
</script>

<script>
  var appBaseUrl = "<?php echo rtrim($this->config->item('app_base_url') ? $this->config->item('app_base_url') : base_url(), '/'); ?>";

  $(document).ready(function() {
    $('.btn-map').insertBefore('#bottomchatbtn');
    loadRandomAdBanner('recruit');
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
            location.href = appBaseUrl + "/mypage/resume_list";
          } else {
            location.href = appBaseUrl + "/mypage/resume_write";
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