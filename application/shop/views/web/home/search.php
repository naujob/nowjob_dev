

<?= getCachCss(RES_PATH . "/css/recruit_list.css") ?>
<?= getCachCss(RES_PATH . "/css/recruit_card.css") ?>
<?= getCachCss(RES_PATH . "/css/card_v2.css") ?>
<?= getCashJs(RES_PATH . "/js/card_v2.js") ?>
<style>
  /* search.php 전용 스타일 */
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
</style>
<div id="content" class="page page-event page-search">
  <main class="page-content">
    <div class="container">
      <!-- 중복된 검색폼 - search_cont.php에서 처리됨
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
      -->

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

          <!-- <div class="search_title">
            <span class="tt">"<?= $text ?>"</span>검색결과 총<span class="cnt" id="total_count"></span>입니다.
          </div> -->

          <h5 class="search_header"><?= t('채용정보') ?></h5>
          <div class="result-wrap">
            <ol class="nc-list" id="ajaxList_1" data-page="1" style="min-height:200px;">
              <div class="nodata">
                <div><img src="/resource/shop/web/img/nodata.png" /></div>
                <?= t('검색 결과가 없습니다.') ?>
              </div>
            </ol>
            <div id="page_cont_1">
            </div>
          </div>

          <h5 class="search_header" style="margin-top:50px;"><?= t('인재정보') ?></h5>
          <div class="result-wrap">
            <ol class="nc-list" id="ajaxList_2" data-page="1" style="min-height:200px;">
              <div class="nodata">
                <div><img src="/resource/shop/web/img/nodata.png" /></div>
                <?= t('검색 결과가 없습니다.') ?>
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

<script>
  // 검색폼 처리는 search_cont.php에서 담당 (중복 제거)
  /*
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
  */

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
      render: "html",
    };
    $.ajax({
      url: "/recruit_proc/search_recruit_list2025",
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
          showBoardList_1(ret.data.list_html, is_top);
          total_cnt += parseInt(ret.data.count);
        }

        $("#total_count").html(total_cnt.format() + t("건"));

        if (ret.data.count == 0) {
          showNoData(list_id_1);
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  function showBoardList_1(listHtml, is_top) {
    $(list_id_1).html(listHtml || "");

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
      render: "html",
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
          showBoardList_2(ret.data.list_html, is_top);
          total_cnt += parseInt(ret.data.count);
        }
        $("#total_count").html(total_cnt.format() + t("건"));
        if (ret.data.count == 0) {
          showNoData(list_id_2);
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  function showBoardList_2(listHtml, is_top) {
    $(list_id_2).html(listHtml || "");

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
      return `<em class="wtype0">${t('근무협의')}</em>`;
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
      result += `<em class="${cls}">${t(text)}</em>`;
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
    return `<em class="${cls}">${t(text)}</em>`;
  }


  // 인재 카드는 기업회원만 상세로 (채용 카드는 card_v2.js가 새 창으로 연다)
  window.ncBeforeOpen = function($card) {
    if (!$card.data('ru_idx')) return true;
    <?php if ($mm_type !== "COMP") { ?>
    commonAlert(t("기업회원 전용 서비스입니다."), function() {});
    return false;
    <?php } else { ?>
    return true;
    <?php } ?>
  };
</script>