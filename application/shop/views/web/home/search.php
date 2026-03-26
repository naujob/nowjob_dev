<style>
  .page-search .search_title {
    padding: 20px;
    margin-top: 0px;
    margin-bottom: 15px;
    background: #fff;
    padding-left: 40px;
    border-radius: 10px;
  }

  .page-search .search-top-cont .search-round .search-input {
    width: 600px;
    background: #fff;
    border: solid 1px #57CCAF;
    padding: 4px 34px;
    border-radius: 30px;
    display: inline-block;
  }

  .page-search .search-top-cont .search-round .search-btn {
    margin-left: 5px;
    display: inline-block;
    width: 120px;
    text-align: center;
    vertical-align: middle;
    background: #57CCAF;
    border-radius: 30px;
    line-height: 54px;
    font-weight: bold;
    color: #fff;
  }

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
    width: 110px;
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
    width: 55%;
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
    width: 25%;
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
    color: #1b9d7d;
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

  .result-list .content-info .desc .desc3 .period em:nth-of-type(2) {
    color: #333;
  }

  .result-list .content-info .desc .desc3 .period em.on:nth-of-type(2) {
    color: #333;
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
</style>

<style>
  .item-info .content-wrap {
    padding: 0;
    justify-content: center;
  }

  .item-info .category-label {
    height: 26px;
  }

  .item-info {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    position: relative;
    padding: 10px 0;
    height: 115px;
  }

  .item-info .desc {
    position: relative;
    height: 100%;
    width: 100%;
    margin: 0 0 0 105px;
    cursor: pointer;
    display: flex;
  }

  .item-info .reco-title {
    color: #1b9d7d;
    font-weight: 600;
  }

  .item-info .timestamp {
    margin-bottom: 5px;
  }

  .item-info .period {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;
  }

  .item-info .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #777;
  }

  .item-info .period em:not(:last-child) {
    padding-right: 5px;
  }

  .item-info .period em:not(:last-child)::after {
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

  .item-info .img {
    position: absolute;
    width: 95px;
    height: 95px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  .item-info .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .item-info .star-rating {
    position: absolute;
    bottom: 15px;
    left: 5px;
    width: 85px;
    height: 15px;
    margin: 5px auto 0;
  }

  .item-info .meta {
    display: flex;
    gap: 5px;
    left: 0;
    top: 0;
    position: relative;
    font-size: 14px;
    font-weight: 600;
    width: 200px;
  }

  .item-info .meta p {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #777;
    margin-right: 0;
  }

  .item-info .meta p:not(:last-child) {
    padding-right: 5px;
  }

  .item-info .meta p:not(:last-child)::after {
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

  .item-info .price_type {
    position: relative;
    font-size: 16px;
    font-weight: 600;
  }

  .item-info .price_type span {
    position: relative;
    display: block;
  }

  .item-info .price_type em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .item-info .price_type em:not(:first-child) {
    padding-left: 5px;
  }

  .item-info .price_type em:not(:last-child) {
    padding-right: 5px;
  }

  .item-info .price_type em:not(:last-child)::after {
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

  .item-info .price_type em.wtype1 {
    color: #3a70db;
  }

  .item-info .price_type em.wtype2 {
    color: #F86048;
  }

  .item-info .price_type em.wtype3 {
    color: #1B9D7D;
  }

  .item-info .working-day {
    width: 25%;
    margin-right: 10px;
    align-content: center;
  }

  .item-info .title-wrap {
    width: 400px;
  }

  .item-info .title-wrap .title {
    max-width: 400px;
    margin: 0;
    font-size: 20px;
    font-weight: 600;
  }

  .item-info .area {
    display: block;
    margin-top: 5px;
    color: #000;
    font-size: 18px;
    font-weight: 600;
  }

  /* ru_price_type 클래스에 대한 스타일 추가 */
  .ru_price_type {
    color: #1B9D7D;
    font-size: 16px;
    font-weight: 600;
  }

  /* ru_price_type 내의 em 태그 기울임 제거 */
  .ru_price_type em {
    font-style: normal;
    position: relative;
  }

  /* ru_price_type 내의 구분자 스타일 */
  .ru_price_type span {
    color: rgb(35, 33, 33);
  }

  /* 구분자 전용 스타일 */
  .item-info .price_type_wrap .ru_price_type em:not(:last-child)::after {
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

  .item-info .price_type_wrap .ru_price_type em:not(:first-child) {
    padding-left: 5px;
  }

  .item-info .price_type_wrap .ru_price_type em:not(:last-child) {
    padding-right: 5px;
  }

  .person-card .meta p {
    position: relative;
    flex: 1;
    word-break: keep-all;
    text-align: center;
    width: unset;
  }

  .person-card .meta p em {
    font-style: normal;
  }

  .person-card .img {
    position: relative;
    width: 256px;
    height: 256px;
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

  .person-card .desc {
    cursor: pointer;
  }

  .person-card .title {
    width: 30%;
  }

  .person-card .area {
    margin: 0;
    color: #000;
    height: auto;
    width: 70%;
    text-align: right;
    font-size: 18px;
    font-weight: 600;
  }

  .person-card .national {
    font-size: unset;
  }

  .person-card .hashtag {
    display: block;
    margin: 14px 0 0;
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
    margin: 0 8px 0 0;
    padding: 0 12px;
    font-size: 14px;
    color: #1B9D7D;
    line-height: 2;
    text-align: center;
    vertical-align: top;
    border-radius: 100px;
    background: rgba(49, 161, 130, 0.15);
  }

  .person-card .tag:last-child {
    margin-right: 0;
  }
</style>
<div id="content" class="page page-event page-search">
  <main class="page-content">
    <div class="container">
      <div class="search-top-cont">
        <div class="search-round">
          <div class="search-input">
            <input type="text" id="search_total_text" value="<?= $text ?>" />
          </div>
          <a id="btnTotalSearch" class="search-btn" href="javascript:void(0);"><img class="icon" src="/resource/shop/web/img/search-icon.png" />검색</a>
        </div>
        <div class="keyword">
          <img src="/resource/shop/web/img/fav_keyword.png" />
          <?php foreach ($tag_list as $tag) : ?>
            <a href="/home/search?text=<?= $tag ?>"><?= $tag ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- div class="tabs-wrap tabs">
        <ul class="tabs">
          <li class="on">
            <a href="/home/search?text=<?= $text ?>" class="tab"><span>통합검색</span></a>
          </li>
          <li>
            <a href="/home/search_job?text=<?= $text ?>" class="tab"><span>채용정보</span></a>
          </li>
          <li>
            <a href="/home/search_hr?text=<?= $text ?>" class="tab"><span>인재정보</span></a>
          </li>
        </ul>
      </div -->

      <div class="search-result">
        <!-- <div class="sidebar">
          <div class="adBanner adBanner1">
            <img src="/outer/ad/ad3.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
          </div>
          <div class="adBanner adBanner2">
            <img src="/outer/ad/ad1.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
          </div>
        </div> -->
        <div class="content-area">

          <div class="search_title">
            <span class="tt">"<?= $text ?>"</span>검색결과 총<span class="cnt" id="total_count"></span>입니다.
          </div>

          <h5 class="search_header">채용정보</h5>
          <div class="result-wrap">
            <ol class="result-list" id="ajaxList_1" data-page="1" style="min-height:200px;">
              <div class="nodata">
                <div><img src="/resource/shop/web/img/nodata.png" /></div>
                검색 결과가 없습니다.
              </div>
            </ol>
            <div id="page_cont_1">
            </div>
          </div>

          <h5 class="search_header" style="margin-top:50px;">인재정보</h5>
          <div class="result-wrap">
            <ol class="result-list" id="ajaxList_2" data-page="1" style="min-height:200px;">
              <div class="nodata">
                <div><img src="/resource/shop/web/img/nodata.png" /></div>
                검색 결과가 없습니다.
              </div>
            </ol>
            <div id="page_cont_2">
            </div>
          </div>

        </div><!-- //content-area -->
        <!-- <div class="sidebar">
          <div class="adBanner adBanner2">
            <img src="/outer/ad/ad1.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
          </div>  
          <div class="adBanner adBanner1">
            <img src="/outer/ad/ad3.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
          </div>
        </div> -->
      </div>

    </div>
  </main>
</div>

<template id="tpl_list_1">
  <li class="list-item item-hover" data-rc_idx="{{rc_idx}}">
    <div class="content-li">
      <div class="content-info">
        <div class="content-body">
          <div class="img">
            <img src="{{rt_recruit_img}}">
            <?php if ($mm_type === "MEM") { ?><button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
          </div>
          <div class="desc">
            <div class="desc1">
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

<template id="tpl_list_2">
  <li class="list-item item-hover" data-ru_idx="{{ru_idx}}">
    <div class="item-info">
      <div class="img">
        <img src="{{ru_resume_img}}">
        <?php if ($mm_type === "COMP") { ?><button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
      </div>
      <p class="star-rating"><span class="star" style="width:100%;"></span><span class="sr-text">평점</span><span class="sr-text">5</span></p>
      <div class="desc">
        <div class="content-wrap">
          <div class="inline-group">
            <p class="category">{{ru_work_img}}</p>
          </div>
          <div class="title-wrap" style="display: block;">
            <p class="title">{{mm_name}}</p>
          </div>
          <div class="meta">
            <p class="national">{{mm_nation}}</p>
            <p class="age">{{ma_age}}</p>
          </div>
        </div>

        <div style="align-content: center;width:20%;text-align: center;" class="price_type_wrap">
          <span class="type price_type">{{ru_work_type}}</span>
          <span class="area">{{ad_addr}}</span>
          <span class="ru_price_type">{{ru_price_type}}</span>
        </div>
        <ul class="working-day">
          {{ru_week}}
        </ul>
      </div>
    </div>
  </li>
</template>

<script>
  $("#btnTotalSearch").click(function() {
    goSearchTotal();
  });

  $("#search_total_text").keyup(function(e) {
    if (e.keyCode === 13) {
      e.preventDefault();
      goSearchTotal();
    }
  });

  function goSearchTotal() {
    if ($("#search_total_text").val() == "") {
      commonAlert("검색어를 입력해주세요.", function() {
        $("#search_total_text").focus();
      });
      return;
    }

    location.href = "/home/search?text=" + encodeURIComponent($("#search_total_text").val());
  }

  var text = "<?= $text ?>";
  var total_cnt = 0;
  var list_id_1 = "#ajaxList_1";
  var list_page_1 = 1;
  var list_id_2 = "#ajaxList_2";
  var list_page_2 = 1;

  getBoardList_1(false);
  getBoardList_2(false);

  function initPage_1(is_top) {
    list_page_1 = 1;
    getBoardList_1(is_top);
  }

  function goPage_1(page) {
    list_page_1 = page;
    getBoardList_1(true);
  }

  function getBoardList_1(is_top) {
    var params = {
      page: list_page_1,
      pagesize: 5,
      text: text,
      order_by: "",
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
          $(list_id_1).attr("data-page", list_page_1);
          if (ret.data.pagenation != false) {
            $("#page_cont_1").html(ret.data.pagenation.replaceAll("goPage", "goPage_1"));
          } else {
            $("#page_cont_1").html("");
          }
          showBoardList_1(ret.data.list, is_top);
          total_cnt += parseInt(ret.data.count);
        }

        $("#total_count").html(total_cnt.format() + "건");

        if (ret.data.count == 0) {
          showNoData(list_id_1);
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  function showBoardList_1(list, is_top) {
    $(list_id_1).html("");

    list.forEach(function(row) {
      var $clone = $("#tpl_list_1").clone();
      var $content = $clone.html();

      $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
      $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      $content = $content.replaceAll("{{ad_addr}}", formatAddr(row.ad_addr));
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
      $(list_id_1).append($clone.html());
    });

    if (is_top == true) {
      var ajax_top = $(".search-result").position().top - 50;
      $('html, body').animate({
        scrollTop: ajax_top
      }, 400);
    }
  }


  function initPage_2(is_top) {
    list_page_2 = 1;
    getBoardList_2(is_top);
  }

  function goPage_2(page) {
    list_page_2 = page;
    getBoardList_2(true);
  }

  function getBoardList_2(is_top) {
    var params = {
      page: list_page_2,
      pagesize: 5,
      text: text,
      order_by: "",
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
          $(list_id_2).attr("data-page", list_page_2);
          if (ret.data.pagenation != false) {
            $("#page_cont_2").html(ret.data.pagenation.replaceAll("goPage", "goPage_2"));
          } else {
            $("#page_cont_2").html("");
          }
          showBoardList_2(ret.data.list, is_top);
          total_cnt += parseInt(ret.data.count);
        }
        $("#total_count").html(total_cnt.format() + "건");
        if (ret.data.count == 0) {
          showNoData(list_id_2);
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  function showBoardList_2(list, is_top) {
    $(list_id_2).html("");

    list.forEach(function(row) {
      var $clone = $("#tpl_list_2").clone();
      var $content = $clone.html();

      $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
      $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      $content = $content.replaceAll("{{mm_name}}", row.ru_name);
      $content = $content.replaceAll("{{ru_week}}", row.ru_week);
      $content = $content.replaceAll("{{distance}}", row.distance + "km");
      $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);

      if (row.mm_nation.trim() == '') {
        $content = $content.replaceAll("{{mm_nation}}", "");
        $content = $content.replaceAll("class=\"national\" style=\"display: inline-block;\"", "class=\"national\" style=\"display: none;\"");
      } else {
        $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
      }

      if (row.mm_age == '' || row.mm_age == '0') {
        $content = $content.replaceAll("{{ma_age}}", "");
        $content = $content.replaceAll("class=\"age\" style=\"display: inline-block;\"", "class=\"age\" style=\"display: none;\"");
      } else {
        $content = $content.replaceAll("{{ma_age}}", row.mm_age + "대");
      }

      $content = $content.replaceAll("{{ru_price_type}}", getPriceType(row.ru_price_type));
      $content = $content.replaceAll("{{ru_my_type}}", getHashTagJS(row.ru_my_type, 2));
      $content = $content.replaceAll("{{ru_work_type}}", getWorkType(row.ru_work_type));
      $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
      $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));

      $clone.html($content);
      $(list_id_2).append($clone.html());
    });

    if (is_top == true) {
      var ajax_top = $(".search-result").position().top - 50;
      $('html, body').animate({
        scrollTop: ajax_top
      }, 400);
    }
  }

  // 기존 함수 (주석처리) - mobile 버전으로 교체
  // function fRemoveBigAdrJS(arg) {
  //
  //   const parts1 = arg.split(",");
  //   arg = parts1[0];
  //
  //   arg = arg.replace("서울특별시 ", "");
  //   arg = arg.replace("경기 ", "");
  //   arg = arg.replace("강원 ", "");
  //   arg = arg.replace("충남 ", "");
  //   arg = arg.replace("충북 ", "");
  //   arg = arg.replace("경남 ", "");
  //   arg = arg.replace("경북 ", "");
  //   arg = arg.replace("전남 ", "");
  //   arg = arg.replace("전북 ", "");
  //   arg = arg.replace("제주 ", "");
  //   arg = arg.replace("경기도 ", "");
  //   arg = arg.replace("강원특별자치도 ", "");
  //   arg = arg.replace("충청남도 ", "");
  //   arg = arg.replace("충청북도 ", "");
  //   arg = arg.replace("경상남도 ", "");
  //   arg = arg.replace("경상북도 ", "");
  //   arg = arg.replace("전라남도 ", "");
  //   arg = arg.replace("전북특별자치도 ", "");
  //   arg = arg.replace("제주특별자치도 ", "");
  //   arg = arg.replace("세종특별자치시 ", "세종시 ");
  //   arg = arg.replace("광주광역시 ", "광주시 ");
  //   arg = arg.replace("인천광역시 ", "인천시 ");
  //   arg = arg.replace("울산광역시 ", "울산시 ");
  //   arg = arg.replace("부산광역시 ", "부산시 ");
  //   arg = arg.replace("대구광역시 ", "대구시 ");
  //   arg = arg.replace("대전광역시 ", "대전시 ");
  //
  //   const parts = arg.split(" ");
  //   if (parts.length >= 3) {
  //     return parts[0] + " " + parts[2];
  //   } else {
  //     return arg;
  //   }
  // }

  // add.js의 fRemoveBigAdrJS 활용 + 안전한 포맷팅
  function formatAddr(addr) {
    if (!addr) {
      return "";
    }
    if (typeof fRemoveBigAdrJS === 'function') {
      return fRemoveBigAdrJS(addr);
    }
    return addr;
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

  // hr_list.php와 동일한 데이터 정리 함수들
  function getPriceType(price_type) {
    if (!price_type) {
      return "";
    }
    if (price_type === "일급,주급,월급") {
      return "급여협의";
    }

    // <em> 태그 제거하고 실제 텍스트만 추출
    const cleanPriceType = price_type.replace(/<\/?em>/g, '');

    // 첫 번째 값만 반환
    const firstType = cleanPriceType.split(',')[0].trim();
    return `<em>${firstType}</em>`;
  }

  function getWorkType(work_type) {
    if (!work_type) {
      return "";
    }

    let items = [];
    
    // <span> 태그가 있으면 파싱
    const spanMatches = work_type.match(/<span>(.*?)<\/span>/g);
    if (spanMatches && spanMatches.length > 0) {
      items = spanMatches.map(match => match.replace(/<\/?span>/g, '').trim());
    } else {
      // 쉼표로 구분된 문자열 처리
      items = work_type.split(',').map(item => item.trim()).filter(item => item !== '');
    }

    if (items.length === 0) {
      return "";
    }

    // 첫 번째 값만 사용
    const text = items[0];
    let cls = '';
    if (text === '주간') {
      cls = 'wtype1';
    } else if (text === '야간') {
      cls = 'wtype2';
    } else if (text === '2교대') {
      cls = 'wtype3';
    }
    return `<em class="${cls}">${text}</em>`;
  }

  function getHashTagJS(hash, max = 10) {
    let result = "";
    let tmp = hash;
    tmp = tmp.replaceAll("<p class=\'tag\' style=\'width:100%;text-align:right;\'>", "");
    tmp = tmp.replaceAll("</p>", "");
    let arr_hash = tmp.trim().split(" ");
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

  $(document).on('click', '.content-li', function() {
    var rcIdx = $(this).closest('.list-item').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.open(url, '_blank');
    }
  });

  $(document).on('click', '.item-info', function() {
    <?php if ($mm_type !== "COMP") { ?>
      commonAlert("기업회원 전용 서비스입니다.", function() {});
      return;
    <?php } else { ?>
      var ruIdx = $(this).closest('.list-item').data('ru_idx');
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.open(url, '_blank');
      }
    <?php } ?>
  });
</script>