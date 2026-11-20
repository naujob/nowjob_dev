<?php
$banner_list = getBannerList("BN00302041");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<script src="<?= RES_PATH ?>/js/ad-banner.js"></script>
<?= getCachCss(RES_PATH . "/css/resume_list.css") ?>
<?= getCachCss(RES_PATH . "/css/search_v2.css") ?>
<?= getCachCss(RES_PATH . "/css/nv_reco.css") ?>
<?= getCachCss(RES_PATH . "/css/card_v2.css") ?>
<?= getCashJs(RES_PATH . "/js/card_v2.js") ?>
<style>
.search-result{
  width: auto;
}
  .search-result .content-area:first-child {
    margin-left: 0;
  }
  .search-result .content-area {
    margin: 0 10px 0 0;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 1400px;
    flex: 0 0 1400px;
  }

  .search-result .sidebar {
    width: 200px;
    height: 880px;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 200px;
    flex: 0 0 200px;
    position: sticky;
    top: 90px;
    padding-left: 35px;

  }

  #page_cont {
    margin: 20px 0;
  }

  #page_cont .pagination {
    margin: 50px 0 0;
  }

  .arrow--disabled {
    display: none;
  }
  .recomm-slider.person .container.swiperX {
  padding: 0 52px;
  max-width: 1634px;
}
.recomm-slider.person {
  padding-bottom: 90px;
}
.recomm-slider.person .person-card {
  padding: 0 25px 0 0;
  min-height: 470px;
}
.recomm-slider.person .person-card .img {
  width: 286px;
  height: 286px;
}
.recomm-slider .person-card .tag {
  padding: 0;
}
.recomm-slider .person-card .tag:not(:last-child) {
  margin-right: 13px;
}
  .recomm-slider.person .slider-state {
    min-height: 470px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 15px;
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

    <!-- 추천인재 -->
    <section class="nv nv-sec nv-sec-list">
      <div class="container">
        <div class="nv-head">
          <div>
            <h2><?= t('추천인재') ?></h2>
          </div>
        </div>
        <div class="nv-people2" id="nvPeople">
          <?php if (!empty($pc_ssr)) : ?>
            <?php $card_list = isset($initial_reco) ? array_slice($initial_reco, 0, 8) : array(); include APPPATH . 'views/web/_partial/resume_nv_reco_card.php'; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <div class="section">
      <div class="container">
        <div class="search-result">

          <div class="content-area">
           
            <?php
            $search_wrap_title = t('인재정보');
            $search_wrap_class = 'search-v2';
            $search_addr_placeholder = t('지역명 검색 예) 서울, 서초구');
            $search_hide_options = array('category:기타', 'price_type:시급');
            include APPPATH . 'views/web/_layout/search_recruit.php';
            ?>

            <div class="result-wrap">

              <div class="nc-head">
                <p class="nc-cnt"><span><?= t('인재정보') ?> <em id="total_count"><?= !empty($pc_ssr) ? (int) $initial_total_count : '…' ?></em><?= t('건') ?></span><span><?= t('신규 인재정보') ?> <em><?= number_format(isset($today_resume_active_count) ? $today_resume_active_count : 0) ?></em><?= t('건') ?></span></p>
                <div class="nc-sort">
                  <label>
                    <input type="radio" name="order_by" value="recent" checked>
                    <span><?= t('최신순') ?></span>
                  </label>
                  <label>
                    <input type="radio" name="order_by" value="fav">
                    <span><?= t('인기순') ?></span>
                  </label>
                </div>
              </div>

              <!-- 목록 -->
              <ol class="nc-list" id="ajaxList" data-page="1" style="min-height:400px;">
                <?php if (!empty($pc_ssr)) : ?>
                  <?php $card_list = isset($initial_resumes) ? $initial_resumes : array(); include APPPATH . 'views/web/_partial/resume_list_card.php'; ?>
                  <?php if (empty($card_list)) : ?><li class="list-state"><?= t('검색 결과가 없습니다.') ?></li><?php endif; ?>
                <?php else : ?>
                  <li class="list-state"><?= t('인재정보를 불러오는 중입니다.') ?></li>
                <?php endif; ?>
              </ol>
              <!-- //목록 -->

              <div id="page_cont">
                <?php if (!empty($pc_ssr) && !empty($initial_pagenation)) : ?>
                  <?= str_replace(array('<li>', 'page-next'), array('', 'page-next btn'), $initial_pagenation) ?>
                <?php endif; ?>
              </div>

              <div class="list-action v2">
                <a href="<?= lang_url('/resume/hr_map') ?>" title="<?= t('지도에서 찾기') ?>" class="btn-map"><span class="sr-text"><?= t('지도보기') ?></span></a>
              </div>

              <script>
                /* 목록이 3개이하인경우 리스트액션 고정 */
                window.addEventListener('scroll', function() {
                  var minY = document.querySelector('.result-wrap').getBoundingClientRect().top - (window.innerHeight / 2);
                  var stickyElm = document.querySelector('.result-wrap .list-action');

                  if (document.querySelector('#ajaxList').children.length < 3) return;

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
  // 첫 화면을 서버가 그렸는지 (true면 첫 목록·추천 AJAX를 건너뜀)
  var INITIAL_SSR = <?= !empty($pc_ssr) ? 'true' : 'false' ?>;
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
    // 첫 화면 추천인재는 서버가 그렸으므로, 서버가 안 그린 경우에만 AJAX 조회
    if (!INITIAL_SSR) {
      getRecoList();
    }
    restoreSearchFromUrl();
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
    if (search_restoring) return;
    setTimeout(function() {
      getBoardListDelay(is_top);
    }, 500);
  }

  // 목록 요청 순서 표시: 늦게 도착한 이전 응답이 최신 목록을 덮지 않도록 한다
  var board_req_seq = 0;
  // keep_seed=true면 서버가 같은 정렬 순서를 유지한다 (언어 전환용)
  function getBoardListDelay(is_top, onDone, keep_seed) {
    setBoardParams();
    updateSearchUrl();
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
      keep_seed: keep_seed ? "Y" : "N",
      render: "html", // 카드 HTML을 서버 파셜로 받는다 (첫 화면과 같은 마크업)
    };
    var board_seq = ++board_req_seq;
    $.ajax({
      url: "/resume_proc/search_list2025",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (board_seq !== board_req_seq) return; // 더 최신 요청이 있으면 이 응답은 버린다
        if (ret.code == "00") {
          $(list_id).attr("data-page", list_page);
          $("#total_count").html(ret.data.count);
          if (ret.data.pagenation && ret.data.pagenation.trim() !== '') {
            $("#page_cont").html(ret.data.pagenation.replace(/<li>/g, '').replace(/page-next/g, 'page-next btn'));
          } else {
            $("#page_cont").html('');
          }
          showBoardList(ret.data.list_html, is_top);
        }
      },
      error: function(xhr, status, error) {
        if (board_seq !== board_req_seq) return;
        console.error("데이터 가져오기 오류:", error);
        // 실패 시 로딩 문구가 계속 남지 않도록 안내 문구로 바꾼다
        $("#total_count").text("-");
        $(list_id).html('<li class="list-state">' + t('목록을 불러오지 못했습니다. 잠시 후 다시 시도해 주세요.') + '</li>');
      },
      complete: function() {
        if (typeof onDone === 'function') onDone(); // 언어 전환 등에서 끝났는지 알림
      }
    });
  }

  // 서버가 카드 파셜(web/_partial/resume_list_card.php)로 그린 HTML을 그대로 넣는다 (JS 템플릿 치환 없음)
  function showBoardList(html, is_top) {
    $(list_id).html($.trim(html) ? html : '<li class="list-state">' + t('검색 결과가 없습니다.') + '</li>');

    if (is_top == true) {
      var ajax_top = $(".search-result").position().top - 30;
      $('html, body').animate({
        scrollTop: ajax_top
      }, 400);
    }
  }

  // 추천 순서 seed: 페이지를 열 때 한 번 만들고, 언어 전환 때도 같은 값을 보내 추천 목록이 바뀌지 않게 한다
  var reco_seed = Math.floor(Math.random() * 2147483646) + 1;

  function getRecoList() {
    comAjax("/resume_proc/search_reco_list2025", { "addr1": "", "addr2": "", "addr3": "", "limit": "8", "render": "nv", "seed": reco_seed }, function(ret) {
      if (ret.code == "00") $("#nvPeople").html(ret.data.list_html);
    });
  }

  // 언어 스위치: 새로고침 없이 현재 페이지의 인재목록·추천인재만 새 언어로 다시 받는다 (_layout/basic.php 가 쿠키·주소를 바꾼 뒤 호출)
  // 필터·페이지 번호·스크롤 위치는 그대로 둔다
  window.onLangSwitch = function(newLang, done) {
    var finished = false;
    function finish() {
      if (finished) return;
      finished = true;
      if (typeof done === 'function') done();
    }
    setTimeout(finish, 5000); // 안전장치: 응답이 없어도 5초 뒤 완료 처리
    getRecoList();
    getBoardListDelay(false, finish, true);
  };


  // 나이 계산: mm_birth는 출생연도 4자리(YYYY)로 저장된다. 과거 YYYYMMDD 값도 앞 4자리로 계산한다
  function getKoreanAge(birth) {
    if (!birth) {
      return 0;
    }

    let year = parseInt(String(birth).slice(0, 4), 10);
    if (isNaN(year)) {
      return 0;
    }

    return new Date().getFullYear() - year;
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
          commonAlert(ret.msg || t("채용공고 개수를 확인할 수 없습니다."), function() {});
        }
      },
      error: function(xhr, status, error) {
        console.error("채용공고 개수 확인 오류:", error);
        commonAlert(t("채용공고 개수를 확인할 수 없습니다."), function() {});
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