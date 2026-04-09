<?php
$banner_list = getBannerList("BN00010001");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<link rel="stylesheet" href="<?= RES_PATH ?>/css/recruit_list.css">
<style>
  /* company.php 고유 스타일 - 기업 헤더 */
  .comp-info {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    position: relative;
    align-items: center;
    height: 330px;
    background-color: #fff;
  }

  .comp-info .comp-img {
    position: absolute;
    width: 600px;
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

  .search-result {
    padding-top: 0;
  }

  .item-info.colgroup .meta {
    color: #333;
  }

  .page-content {
    position: relative;
    padding: 80px 0 80px;
  }

  /* company.php 레이아웃 오버라이드 */
  .result-list .content-li {
    height: 120px;
  }

  .result-list .content-li .content-info {
    padding: 5px;
  }

  .result-list .content-info .content-body {
    height: 100%;
  }

  .result-list .content-info .img {
    width: 110px;
    height: 110px;
  }

  .result-list .content-info .desc .desc1 {
    width: 60%;
  }

  .result-list .content-info .desc .desc2 {
    width: 20%;
  }

  .result-list .content-info .desc .desc3 {
    width: 20%;
  }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<div id="content" class="page page-recruit">
  <main class="page-content">
    <div class="comp-info">
      <div class="comp-img">
        <img src="<?= $comp_thumb ?>" alt="<?= $comp_name ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
        <div class="alt-text"><?= $comp_name ?></div>
      </div>
    </div>
    <div class="section">
      <div class="container">
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
            <?php // include_once(get_base_url() . '/application/shop/views/web/_layout/search_recruit.php'); 
            ?>
            <div class="result-wrap">
              <div class="result-header">
                <p class="total"><b><?= $comp_name ?></b> 채용정보 <em id="total_count"></em>건</p>
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
            </div><!-- //result-wrap -->
          </div><!-- //content-area -->
          <!-- <div class="sidebar">
            <div class="adBanner adBanner2">
              <img src="/outer/ad/ad1.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
            </div>
            <div class="adBanner adBanner1">
              <img src="/outer/ad/ad3.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
            </div>
          </div> -->
        </div><!-- //search-result -->
      </div><!-- //container -->
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<?php include_once(get_base_url() . '/application/shop/views/web/_layout/recruit_list_template.php'); ?>

<script>
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

      $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type);

      // 협의인 경우 급여 타입 숨김 (서버에서 rt_price_pay는 "급여협의" 반환)
      if (row.rc_price_type.indexOf('협의') > -1) {
        $content = $content.replaceAll("{{rc_price_type}}", "");
      } else {
        $content = $content.replaceAll("{{rc_price_type}}", row.rt_price_type);
      }

      $content = $content.replaceAll("{{rc_price_display}}", row.rt_price_pay);
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

</script>

<script>
  $(document).ready(function() {
    initPage(false);
  });
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.list-item').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.open(url, '_blank');
    }
  });
</script>