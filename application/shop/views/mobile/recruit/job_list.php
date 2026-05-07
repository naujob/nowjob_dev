<?php
$banner_list = getBannerList("BN00302031");
?>
<style>
  .page-content {
    background: #fff;
  }

  .current-area .btn {
    max-width: 90%;
  }

  .search-option .categories .cate {
    display: none;
  }





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

  .current-area .btn {
    max-width: 100%;
    font-size: 1.8rem;
  }

  /* 지금 뜨는 업체 슬라이더 양쪽 peek */
  .section:has(.hot-list) .container {
    overflow: visible;
  }

  .section:has(.hot-list) {
    overflow: hidden;
  }

  /* 추천인재 슬라이더 양쪽 peek — container overflow 재정의 */
  .section.index_comp:has(.recomm-slider) .container {
    overflow: visible;
  }

  .section.index_comp:has(.recomm-slider) {
    overflow: hidden;
  }

  /* KeenSlider 컨테이너 — 양쪽 peek: padding으로 이전/다음 슬라이드 노출 */
  .recomm-slider.person .slider-wrap {
    overflow: visible;
    padding: 0 20px;
    box-sizing: border-box;
  }

  /* 슬라이더 슬라이드: 전체 너비 */
  .recomm-slider.person .keen-slider__slide {
    flex: 0 0 100% !important;
    width: 100% !important;
    min-width: 0;
    padding-bottom: 10px !important;
  }

  /* 슬라이드 내 2x2 — flexbox wrap 방식 */
  .recomm-slider.person .person-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 0;
    width: 100%;
  }

  /* 그리드 아이템 — 2열 배치 (gap 10px 고려) */
  .recomm-slider.person .person-grid-item {
    display: flex;
    flex-direction: column;
    width: calc(50% - 5px);
    min-width: 0;
  }

  /* 카드 높이 최소화 */
  .recomm-slider.person .person-card {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .recomm-slider.person .person-card .title-wrap {
    margin: 0 0 2px;
  }

  .recomm-slider.person .person-card .title-wrap .title {
    font-size: 12px;
    font-weight: 700;
    line-height: 1.3;
    margin: 0;
    color: #333;
  }

  .recomm-slider.person .person-card .meta {
    font-size: 0.8rem;
    font-weight: 500;
    color: #000;
    margin: 2px 0 0;
    display: flex;
    text-align: center;
    align-items: center;
    justify-content: center;
    gap: 0;
  }

  .recomm-slider.person .person-card .meta p {
    flex: 0 1 auto;
    padding: 0;
    margin: 0;
    color: #000;
    white-space: nowrap;
    line-height: 1.3;
  }

  .recomm-slider.person .person-card .meta p:after {
    content: "";
    display: none;
  }

  .recomm-slider.person .person-card .meta-divider {
    padding: 0 11px;
    color: #ccc;
    font-size: 9px;
    flex: 0 0 auto;
  }

  .recomm-slider.person .person-card .addr {
    font-size: 1rem;
    font-weight: 700;
    margin: 2px 0 0;
    padding: 0;
    color: #000;
    line-height: 1.2;
    text-align: center;
  }

  .recomm-slider.person .person-card .hashtag {
    font-size: 0;
    height: 50px;
    max-height: 22px;
    border-top: none;
    background-color: #3A70DB;
    border-radius: 0 0 8px 8px;
    overflow: hidden;
    line-height: 1.3;
  }

  .recomm-slider.person .person-card .tag {
    font-size: 16px;
    color: #fff;
    text-align: center !important;
  }

  /* 카드 스타일 */
  .recomm-slider.person .person-card {
    padding: 10px 0 10px;
    border: 1px solid #d2cece;
    border-radius: 25px;
    background: #fff;
    overflow: hidden;
  }

  .recomm-slider.person .person-card .desc {
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .person-card {
    padding: 0 5px 0 0;
  }

  .person-card .thumb-wrap {
    position: relative;
    display: flex;
    flex-direction: column;
  }

  .person-card .recomm-badge {
    top: 10px;
    left: 10px;
    background-color: #57CCAF;
  }

  .person-card .thumb {
    position: relative;
    left: 31%;
    border-radius: 50px;
    overflow: hidden;
    background: #f0f0f0;
    width: 80px;
    height: 75px;
  }

  /* 뱃지 래퍼: 이미지 아래 배치 */
  .person-card .person-badge-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    margin-top: 4px;
    margin-bottom: 2px;
  }

  /* 뱃지 아이콘 */
  .person-card .person-badge-icon {
    font-size: 0;
  }

  .person-card .person-badge-icon img {
    width: 47px;
    height: 20px;
    display: inline-block;
  }

  /* 뱃지 텍스트 */
  .person-card .person-badge {
    position: static;
    padding: 2px 10px;
    font-size: 10px;
    font-weight: 700;
    color: #fff;
    background-color: #1b9d7d;
    border-radius: 100px;
    line-height: 1.2;
    white-space: nowrap;
    max-width: 90%;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
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

  /* 주소 */
  .person-card .addr {
    margin: 3px 0 0;
    font-size: 12px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
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
    display: none;
  }

  .person-card .meta p:last-child:after {
    content: none;
  }

  /* 메타 구분자 */
  .person-card .meta-divider {
    flex: 0 0 auto;
    padding: 0 2px;
    font-size: 10px;
    color: #ccc;
    line-height: 1;
  }

  .person-card .meta p em {
    font-style: normal;
  }

  .person-card .hashtag {
    height: auto;
    max-height: 24px;
    display: block;
  }

  /* Google AdSense 인피드 광고 스타일 */
  .ad-item-adsense {
    display: none;
    list-style: none;
    margin: 0;
    padding: 0;
    position: relative;
  }

  .ad-item-adsense::before {
    content: "AD";
    position: absolute;
    top: 8px;
    left: 8px;
    font-size: 10px;
    font-weight: 700;
    color: #999;
    z-index: 1;
  }

  .ad-item-adsense .adsense-wrapper {
    display: none;
    border-top: 1px solid #e0e0e0;
    border-bottom: 1px solid #e0e0e0;
    padding: 0;
    background-color: #fafafa;

  }

  .ad-item-adsense ins.adsbygoogle {
    display: none;
    background-color: transparent;
  }

  .pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    margin: 20px 0;
    padding: 10px 0;
  }

  .pagination-wrapper button {
    padding: 8px 12px;
    border: 1px solid #ddd;
    background: #fff;
    color: #333;
    cursor: pointer;
    border-radius: 4px;
    font-size: 14px;
  }

  .pagination-wrapper button.active {
    background: #1B9D7D;
    color: #fff;
    border-color: #1B9D7D;
  }

  .pagination-wrapper button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
</style>
<!-- Google AdSense 인피드 광고 스크립트 -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3622369584322145"
  crossorigin="anonymous"></script>
<div class="page-recruit">

  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span> -->
  <!-- 미확인 알림개수 -->
  <!-- <span class="cnt"><?= $g_alarm_cnt ?></span> -->

</div><!-- //header -->

<div class="page-content">
  <?php include(APPPATH . 'views/mobile/_partial/main_visual.php'); ?>

  <!-- 지금 뜨는 업체 -->
  <div class="section" id="hotListSection">
    <div class="container">
      <h2 class="secondary-title index">지금 뜨는 업체</h2>
      <div class="hot-list">
        <div class="slider-wrap keen-slider">
          <?php
          $limited_up_list = isset($up_list) && is_array($up_list) ? array_slice($up_list, 0, 8) : array();
          $chunked_up_list = array_chunk($limited_up_list, 4);
          // 마지막 슬라이드가 4개 미만인 경우 빈 슬롯 추가
          if (!empty($chunked_up_list)) {
            $last_idx = count($chunked_up_list) - 1;
            while (count($chunked_up_list[$last_idx]) < 4) {
              $chunked_up_list[$last_idx][] = null;
            }
          }
          foreach ($chunked_up_list as $chunk) :
          ?>
            <div class="keen-slider__slide">
              <div class="hot-grid">
                <?php foreach ($chunk as $row) : ?>
                  <?php if ($row !== null) : ?>
                    <a href="/recruit/company/<?= $row["mm_idx"] ?>" class="hot-item">
                      <div class="thumb">

                        <img data-src="<?= getCompImg($row) ?>" alt="" class="lozad">

                        <p class="name"><?= $row["mm_comp_name"] ?></p>
                      </div>
                    </a>
                  <?php else : ?>
                    <div class="hot-item hot-item--empty">
                      <div class="thumb"></div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
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
      <h2 class="secondary-title">채용공고</h2>
      <ol class="result-list result-list-card" id="ajaxList" data-page="1">
      </ol>
      <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
        <p>등록된 정보가 없습니다.</p>
      </div>
      <!-- 페이지네이션 버튼 -->
      <div id="paginationWrapper" class="pagination-wrapper" style="display:none;">
      </div>
    </div>
  </div>
  <!-- <div class="list-action">
    <a href="/recruit/job_map" class="btn-map"><span class="sr-text">지도보기</span></a>
  </div> -->

</div><!-- //page-content -->
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
</div>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<?php $tpl_id = 'tpl_list';
$tpl_recomm_class = true; ?>
<?php include(APPPATH . 'views/mobile/_partial/recruit_card_template.php'); ?>

<template id="tpl_category">
  <label class="filter-tag-item sm {{active}} btn-category" data-filter-key="{{filter_key}}">
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
  var ITEMS_PER_PAGE = 20; // 한 번에 로드할 아이템 수
  var total_count = 0; // 전체 아이템 수
  var total_pages = 1; // 전체 페이지 수
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
    key: "area",
    df: "근무지역",
    text: "",
    active: ""
  }); //0 - 지역 정보
  tab_list.push({
    key: "category",
    df: "업직종",
    text: "",
    active: ""
  }); //1
  tab_list.push({
    key: "work_type",
    df: "근무형태",
    text: "",
    active: ""
  }); //2
  tab_list.push({
    key: "week",
    df: "근무요일",
    text: "",
    active: ""
  }); //3
  tab_list.push({
    key: "price_type",
    df: "급여형태",
    text: "",
    active: ""
  }); //4
  tab_list.push({
    key: "sex",
    df: "성별",
    text: "",
    active: ""
  }); //5
  tab_list.push({
    key: "age",
    df: "나이",
    text: "",
    active: ""
  }); //6

  $(document).on("click", ".form-checkbox", function(e) {
    e.stopPropagation();
  });

  // URL에서 page 파라미터 읽기
  function getPageFromUrl() {
    var params = new URLSearchParams(window.location.search);
    return parseInt(params.get("page")) || 1;
  }

  // URL에 page 파라미터 반영 (히스토리 push)
  function pushPageToUrl(page) {
    var params = new URLSearchParams(window.location.search);
    params.set("page", page);
    history.pushState({
      page: page
    }, "", "?" + params.toString());
  }

  // 캐싱된 페이지 데이터 초기화 (뒤로가기 복원 시)
  function initCashingData() {
    list_page = getPageFromUrl();
    getBoardList("");
  }

  // 초기 데이터 로드
  function initLoadData() {
    list_page = getPageFromUrl();
    total_count = 0;
    total_pages = 1;
    setTimeout(function() {
      getBoardList("");
    }, 100);
  }

  // 브라우저 뒤로가기/앞으로가기 감지
  window.addEventListener('popstate', function(e) {
    // state:{page: 3} 형태로 저장된 페이지 번호를 읽어서 list_page에 반영
    list_page = (e.state && e.state.page) ? e.state.page : getPageFromUrl();
    $(list_id).empty(); //list_id:"#ajaxList" 초기화
    getBoardList("");
  });
  // 공고 목록 가져오기
  function getBoardList(save_yn) {
    if (list_loading) return;

    var params = {
      page: list_page,
      limit: ITEMS_PER_PAGE,
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

        }
      });
    }

    list_loading = true;
    $.ajax({
      url: "/recruit_proc/getRecruitList",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        list_loading = false;

        if (ret.code === "00" && ret.data && Array.isArray(ret.data.list)) {
          $(list_id).empty(); // 매번 초기화
          total_count = ret.data.total || 0;
          total_pages = Math.ceil(total_count / ITEMS_PER_PAGE) || 1;



          showBoardList(ret.data.list);
          renderPagination();
        } else {

        }
      },
      error: function(xhr, status, error) {

      }
    });
  }

  //데이터 보여주기
  function showBoardList(list) {

    list.forEach(function(row, index) {

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
      $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type || "");
      $content = $content.replaceAll("{{rc_price_type}}", row.rt_price_type || '');
      $content = $content.replaceAll("{{rc_price_pay}}", row.rt_price_pay || '');

      $clone.html($content);
      $(list_id).append($clone.html());

    });

    // 페이지의 마지막에 광고 삽입
    if (list.length > 0) {
      insertAdsenseAd();
    }

    showEmpty();
    updateTopSectionsVisibility();

  }

  // Google AdSense 인피드 광고 삽입 함수
  function insertAdsenseAd() {
    var adHtml = '<li class="list-item ad-item-adsense" style="position: relative;">' +
      '<div class="adsense-wrapper" style="position: relative;">' +
      '<ins class="adsbygoogle" style="display:block"' +
      ' data-ad-format="fluid"' +
      ' data-ad-layout-key="-hd+j-2v-68+mb"' +
      ' data-ad-client="ca-pub-3622369584322145"' +
      ' data-ad-slot="5890695133"></ins>' +
      '</div>' +
      '</li>';
    $(list_id).append(adHtml);

    // AdSense 광고 로드 (공식 코드)
    setTimeout(function() {
      try {
        (adsbygoogle = window.adsbygoogle || []).push({});
      } catch (e) {

      }
    }, 100);
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

  // 페이지네이션 버튼 렌더링
  function renderPagination() {
    var $wrapper = $("#paginationWrapper");
    if (total_count === 0) {
      $wrapper.hide();
      return;
    }

    var html = '';
    // 이전 버튼
    html += '<button onclick="goPage(' + (list_page - 1) + ')"' +
      (list_page <= 1 ? ' disabled' : '') + '>◀ 이전</button>';

    // 페이지 번호 (최대 5개 표시, 현재 페이지 중심)
    var startPage = Math.max(1, list_page - 2);
    var endPage = Math.min(total_pages, startPage + 4);
    startPage = Math.max(1, endPage - 4);

    for (var i = startPage; i <= endPage; i++) {
      html += '<button onclick="goPage(' + i + ')"' +
        (i === list_page ? ' class="active"' : '') + '>' + i + '</button>';
    }

    // 다음 버튼
    html += '<button onclick="goPage(' + (list_page + 1) + ')"' +
      (list_page >= total_pages ? ' disabled' : '') + '>다음 ▶</button>';

    $wrapper.html(html).show();
  }

  // 페이지 이동 함수
  function goPage(page) {
    if (page < 1 || page > total_pages) return;
    list_page = page;
    pushPageToUrl(page);
    $(list_id).empty();
    $("html, body").animate({
      scrollTop: 0
    }, 200);
    getBoardList("");
    updateTopSectionsVisibility();
  }

  // 추천공고 데이터 로딩
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
        $(".section.index_comp .result-list").html("");

        list.forEach(function(row) {
          var $clone = $("#tpl_list").clone();
          var $content = $clone.html();
          $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
          $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
          $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
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
          $(".section.index_comp .result-list").append($clone.html());
        });
      }
    });
  }

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

    getRecruitRecoList();

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

    // 지금 뜨는 업체 슬라이더 초기화
    initHotSlider();

    // 네이티브 광고 제어는 main.js에서 자동으로 초기화되므로 중복 호출 제거
  });
  // 지금 뜨는 업체 슬라이더 초기화
  function initHotSlider() {
    var hotSlider = document.querySelector('.hot-list .slider-wrap');
    if (hotSlider && typeof KeenSlider !== 'undefined') {
      if (!hotSlider.__keenslider__) {
        var slides = hotSlider.querySelectorAll('.keen-slider__slide');
        if (slides.length > 0 && slides.length < 4) {
          var cloneCount = Math.ceil(4 / slides.length) * slides.length;
          var added = slides.length;
          var imageSrcMap = {};
          slides.forEach(function(slide, idx) {
            slide.querySelectorAll('img').forEach(function(img, imgIdx) {
              imageSrcMap[idx + '-' + imgIdx] = img.src;
            });
          });
          while (added < cloneCount) {
            slides.forEach(function(slide, slideIdx) {
              if (added < cloneCount) {
                var cloned = slide.cloneNode(true);
                cloned.querySelectorAll('img').forEach(function(img, imgIdx) {
                  var srcKey = slideIdx + '-' + imgIdx;
                  if (imageSrcMap[srcKey]) img.src = imageSrcMap[srcKey];
                  if (img.classList.contains('lozad')) {
                    if (img.getAttribute('data-src') && !img.src) img.src = img.getAttribute('data-src');
                    img.setAttribute('data-loaded', 'true');
                  }
                });
                hotSlider.appendChild(cloned);
                added++;
              }
            });
          }
        }
        new KeenSlider(hotSlider, {
          loop: true,
          slides: {
            perView: 1.1,
            spacing: 12
          },
          drag: true,
          dragMinDist: 0,
          rubberband: true,
          renderMode: 'precision'
        });
      }
    }
  }

  // 지역 선택 팝업 적용 시 카테고리 텍스트 숨김
  window.onChangeAreaApply = function() {
    $(".search-option").addClass("hide-cate");
    if (typeof saveSearchStateToStorage === "function") {
      saveSearchStateToStorage();
    }
  };

  // 공고 카드 클릭 시 상세 페이지로 이동
  $(document).on('click', '.content-li', function(e) {
    // 버튼이나 링크 클릭은 무시
    if ($(e.target).closest('button, a, input').length) {
      return;
    }
    // Google AdSense 광고 클릭이면 무시 (WebView의 shouldOverrideUrlLoading이 처리)
    if ($(e.target).closest('.ad-item-adsense').length) {
      return;
    }
    var rcIdx = $(this).data('rc_idx');
    if (rcIdx) {
      window.location.href = '/recruit/job_post/' + rcIdx;
    }
  });

  // 1페이지 여부에 따라 배너/슬라이더 섹션 표시/숨김
  function updateTopSectionsVisibility() {
    var isFirstPage = (list_page <= 1);
    $('.main-visual').toggle(isFirstPage);
    $('#hotListSection').toggle(isFirstPage);
  }

  // 스와이프 뒤로가기 비활성화
  window.DISABLE_SWIPE_BACK = true;
</script>
