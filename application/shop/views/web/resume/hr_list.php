<?php
$banner_list = getBannerList("BN00302041");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<script src="<?= RES_PATH ?>/js/ad-banner.js"></script>
<style>

.search-result{
  width: auto;
}
  .search-result .content-area:first-child {
    margin-left: -65px;
  }
  .search-result .content-area {
    margin: 0 77px;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 1530px;
    flex: 0 0 1530px;
  }

  .search-result .sidebar {
    width: 200px;
    height: 870px;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 200px;
    flex: 0 0 200px;
    position: sticky;
    top: 90px;

  }

  .result-header .total.today-new {
    margin-left: 28px;
  }

  .arrow--disabled {
    display: none;
  }
  .recomm-slider.person .container.swiperX {
  padding: 0 52px;
  max-width: 1634px;
}
.arrow.arrow--left {
  left: -25px;
}

.recomm-slider.person .arrow.arrow--right {
    left: auto;
    right:  -17px;
}
</style>
<div id="content" class="page page-person page-hr-list">
  <main class="page-content">

    <!-- <div class="visual-banner person">
      <div class="container">
        <div class="visual-content">
          <p>새로 등록된 추천 인재</p>
          <p class="headline">지금 꼭, 필요한 인재</p>
          <a href="javascript:void(0);" id="btnRegist" class="btn-round">채용공고 등록</a>
        </div>
      </div>
    </div> -->

    <div class="recomm-slider person">
      <div class="container swiperX">
        <h2 class="primary-title">추천인재</h2>
        <div class="slider-wrap keen-slider" id="my-keen-slider">
        </div>
      </div>
    </div>

    <div class="section">
      <div class="container">
        <div class="search-result">

          <div class="content-area">
           
            <?php $search_wrap_title = '인재정보'; include APPPATH . 'views/web/_layout/search_recruit.php'; ?>

            <div class="result-wrap">

              <div class="result-header">
                <p class="total">인재정보 <em id="total_count"></em>건</p>
                <p class="total today-new">오늘 새로운 인재 <em><?= number_format(isset($today_recruit_count) ? $today_recruit_count : 0) ?></em>건</p>
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

              <!-- 목록 -->
              <ol class="result-list" id="ajaxList" data-page="1" style="min-height:400px;">
              </ol>
              <!-- //목록 -->

              <div id="page_cont">
              </div>

              <div class="list-action v2">
                <a href="/resume/hr_map" title="지도에서 찾기" class="btn-map"><span class="sr-text">지도보기</span></a>
              </div>

              <script>
                /* 목록이 3개이하인경우 리스트액션 고정 */
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
              <a id="adBannerLink" href="" target="_blank">
                <img id="adBannerImg" src="" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
              </a>
            </div>
          </div>
        </div><!-- //search-result -->
      </div><!-- //container -->
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<!-- 인재 목록 템플릿 (공유) -->
<?php
$tpl_id               = 'tpl_resume_card';
$tpl_show_timestamp   = true;
$tpl_show_mm_idx      = true;
$tpl_price_wrap_width = '290px';
$tpl_show_recomm_badge = true;
include APPPATH . 'views/web/_layout/resume_list_template.php';
?>

<!-- 추천 리스트 템플릿 -->
<?php
$reco_show_bookmark = true;
$reco_show_mm_idx   = true;
include APPPATH . 'views/web/_layout/reco_list_template.php';
?>

<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />

<script>
  var appBaseUrl = "<?php echo rtrim($this->config->item('app_base_url') ? $this->config->item('app_base_url') : base_url(), '/'); ?>";

  // 현재 로그인한 사용자 정보
  var currentUserMmIdx = <?php echo $this->user->isLogin() ? $this->user->getUserIdx() : 'null'; ?>;
  var currentUserType = '<?php echo isset($mm_type) ? $mm_type : ""; ?>';

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

  $(document).ready(function() {
    $('.btn-map').insertBefore('#bottomchatbtn');
    loadRandomAdBanner('hr');
    getRecoList();
    initPage(false);
  });

  // 값이 없을 때 trim에서 에러가 나지 않도록 안전하게 처리하는 헬퍼
  function safeTrim(value) {
    if (value === null || value === undefined) {
      return "";
    }
    return String(value).trim();
  }

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
    setTimeout(function() {
      getBoardListDelay(is_top);
    }, 500);
  }

  function getBoardListDelay(is_top) {
    setBoardParams();
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
      url: "/resume_proc/search_list2025",
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

    list.forEach(function(row, index) {
      try {
        var $clone = $("#tpl_resume_card").clone();
        var $content = $clone.html();
        var cate_type = "cate1";
        if (row.bd_cate == "업무") {
          cate_type = "cate1";
        } else if (row.bd_cate == "일상") {
          cate_type = "cate2";
        } else if (row.bd_cate == "질문") {
          cate_type = "cate3";
        }

        if (row.pm_idx == null) {
          $content = $content.replaceAll("{{timestamp}}", row.up_dt_timestamp || "");
          $content = $content.replaceAll("<p class=\"recomm-badge\"><span>추천</span></p>", "");
        } else {
          $content = $content.replaceAll("{{timestamp}}", "<p class=\"reco-title\">지금뜨는 추천인재</p>");
          $content = $content.replaceAll("등록일 : ", "");
        }

        $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || "");
        $content = $content.replaceAll("{{mm_idx}}", row.mm_idx || "");
        $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
        $content = $content.replaceAll("{{ru_name}}", row.ru_name || "");
        $content = $content.replaceAll("{{ru_week}}", row.ru_week || "");
        $content = $content.replaceAll("{{distance}}", (row.distance || "") + (row.distance ? "km" : ""));
        $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img || "");

        var nationText = safeTrim(row.mm_nation);
        if (nationText === '') {
          $content = $content.replaceAll("{{mm_nation}}", "");
          $content = $content.replaceAll("class=\"national\" style=\"display: inline-block;\"", "class=\"national\" style=\"display: none;\"");
        } else {
          $content = $content.replaceAll("{{mm_nation}}", nationText);
        }

        if (row.mm_age == '' || row.mm_age == '0' || !row.mm_age) {
          $content = $content.replaceAll("{{ma_age}}", "");
          $content = $content.replaceAll("class=\"age\" style=\"display: inline-block;\"", "class=\"age\" style=\"display: none;\"");
        } else {
          $content = $content.replaceAll("{{ma_age}}", row.mm_age + "대");
        }

        $content = $content.replaceAll("{{ru_price_type}}", getPriceTypeJSFirst(row.ru_price_type));
        $content = $content.replaceAll("{{ru_my_type}}", getHashTagJS(row.ru_my_type, 2));
        $content = $content.replaceAll("{{ru_work_type}}", (row.ru_work_type));
        $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img || "");
        $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));

        $clone.html($content);

        $(list_id).append($clone.html());
      } catch (e) {
        console.error("리스트 아이템 렌더링 오류 (인덱스 " + index + "):", e, row);
        // 에러가 발생해도 다음 아이템은 계속 처리
      }
    });

    if (is_top == true) {
      var ajax_top = $(".search-result").position().top - 30;
      $('html, body').animate({
        scrollTop: ajax_top
      }, 400);
    }
  }

  function getRecoList() {
    getRecoListCommon({
      containerId: 'my-keen-slider',
      limit: 10,
      showMmIdx: true,
      perView: 5
    });
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
</script>

<script>
  $("#btnRegist").click(function() {
    if (typeof requireCompCertWithGuide === 'function' && !requireCompCertWithGuide()) {
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
            location.href = appBaseUrl + "/compage/recruit_list";
          } else {
            location.href = appBaseUrl + "/compage/recruit_write";
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
  });

  $(document).on('click', '.desc', function() {
    var $target = $(this).closest('.list-item,.person-card');
    var ruIdx = $target.data('ru_idx');

    if (ruIdx) {
      var url = '/resume/hr_post/' + ruIdx;
      window.open(url, '_blank');
    }
  });
</script>