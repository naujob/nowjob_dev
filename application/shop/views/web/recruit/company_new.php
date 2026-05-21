<?php
	$banner_list = getBannerList("BN00010001");
	$banner = null;
	if(count($banner_list) > 0){
		$banner = $banner_list[0];
	}
?>
<style>
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
    margin-bottom:10px;
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
    display:none;
  }
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
    object-fit: none;
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
  .btn-bookmark {
    display: block;
    position: absolute;
    top: 0px;
    right: 0px;
    width: 40px;
    height: 40px;
    border: 0;
    background: url("/resource/shop/web/img/ic_bookmark_on.svg") no-repeat 0 0;
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
    background: url("/resource/shop/web/img/ic_bookmark_off.svg") no-repeat 0 0;
    background-size: contain;
    pointer-events: all;
    z-index: 1;
  }
  .btn-bookmark.on:after {
    background-image: inherit;
  }

  .result-list {
    margin-top:20px;
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
  .result-list .content-li:hover {
    background: #f8f8f8;
  }  
  .result-list .content-li .content-info {
    width: 100%;
    height: 100%;
    padding:5px;
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
    width: 60%;
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
  .result-list .content-info .desc .desc1 .wicon img{
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
    color:#1b9d7d;
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
  .result-list .content-info .desc .desc2 p.area{
    color: #000;
    font-size: 18px;
    font-weight: 600;    
  } 
  .result-list .content-info .desc .desc2 .wtype{
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
    color:#333;
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
    color:#3a70db;
  }
  .result-list .content-info .desc .desc2 .wtype em.wtype2 {
    color:#F86048;
  }
  .result-list .content-info .desc .desc2 .wtype em.wtype3 {
    color:#1B9D7D;
  }
  .result-list .content-info .desc .desc3 {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 20%;
    align-items: flex-end;
    justify-content: center;    
  }
  .result-list .content-info .desc .desc3 p {
    margin: 0;
    text-align: right;
  }
  .result-list .content-info .desc .desc3 .period{
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;    
  }
  .result-list .content-info .desc .desc3 .amount {
    font-weight: 600;
    font-size: 22px;
    color:#1b9d7d;    
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
    color:#1b9d7d;
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<div id="content" class="page page-recruit">
  <main class="page-content">
    <div class="comp-info">
      <div class="comp-img">
        <img src="<?=$comp_thumb?>" alt="<?=$comp_name?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
        <div class="alt-text"><?=$comp_name?></div>
      </div>
    </div>  
    <div class="section">
      <div class="container">
        <div class="search-result">
          <div class="sidebar">
            <div class="adBanner adBanner1">
              <img src="/outer/ad/ad3.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
            </div>
            <div class="adBanner adBanner2">
              <img src="/outer/ad/ad1.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
            </div>
          </div>
          <div class="content-area">          
            <?php include_once (get_base_url() . '/application/shop/views/web/_layout/search_recruit.php'); ?>
            <div class="result-wrap">
              <div class="result-header">
                <p class="total"><b><?=$comp_name?></b> 채용정보 <em id="total_count"></em>건</p>
                <div class="orderby">
                  <label class="option">
                    <input type="radio" name="order_by" value="recent" checked>
                    <span class="label">최신순</span>
                  </label>
                  <label class="option">
                    <input type="radio" name="order_by" value="fav" >
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
          <div class="sidebar">
            <div class="adBanner adBanner2">
              <img src="/outer/ad/ad1.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
            </div>  
            <div class="adBanner adBanner1">
              <img src="/outer/ad/ad3.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
            </div>
          </div>
        </div><!-- //search-result -->
      </div><!-- //container -->
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<!-- 리스트 템플릿 -->
<template id="tpl_list">
  <li class="list-item" data-rc_idx="{{rc_idx}}">
      <div class="content-li">
        <div class="content-info">
          <div class="content-body">
            <div class="img">
              <img src="{{rt_recruit_img}}">
              <?php if($mm_type === "MEM") {?><button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
              <p class="recomm-badge"><span>추천</span></p>
            </div>
            <div class="desc">
              <div class="desc1">
                <div class="timestamp">{{timestamp}}</div>              
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

<script>
	var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
	var list_page = 1;
	var list_loading = false;
  var search_addr = [];
  var searchAreaStr = "";  
  var addrName = "";
  var comp_idx = "<?=$comp_idx?>";
	var search_array = {
		category : "",
		work_type : "",
		week : "",
		price_type : "",
		sex : "",
		sage : "",
		eage : "",
		order_by : "",
	};

	$("[name='order_by']").click(function(){
		initPage(true);
	});

	function initPage(is_top){
		list_page = 1;
		getBoardList(is_top);
	}

	function goPage(page){
		list_page = page;
		getBoardList(true);
	}
  
	function getCheckedValues(name) {
	  var result = "";
	  $("[name='" + name + "']:checked").each(function () {
      var val = $(this).val();
      if (val !== "") {
        result += (result === "" ? "" : "^") + val;
      }
	  });
	  return result;
	}
  
	function setBoardParams(){
		
		var arr_category    = getCheckedValues("category");
		var arr_work_type   = getCheckedValues("work_type");
		var arr_price_type  = getCheckedValues("price_type");
		var arr_week        = getCheckedValues("week");
		var arr_sex         = getCheckedValues("sex");
		
		search_array.sage = "";
		search_array.eage = "";
		
		var checkedAges = $("[name='eage']:checked").map(function () {
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

	function getBoardList(is_top){
 		getBoardListDelay(is_top);
	}

	function getBoardListDelay(is_top){
		setBoardParams();
    var params = {
      page: list_page,
      comp_idx : comp_idx,      
      "search_addr[]": search_addr,
      addrName : addrName,
      searchAreaStr : searchAreaStr,  
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
        if(ret.code=="00"){
          $(list_id).attr("data-page",list_page);
          $("#total_count").html(ret.data.count);
          if (ret.data.pagenation && ret.data.pagenation.trim() !== '') {
            $("#page_cont").html(ret.data.pagenation.replace(/<li>/g, '').replace(/page-next/g, 'page-next btn'));
          }else{
            $("#page_cont").html('');
          }
          showBoardList(ret.data.list,is_top);
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  } 
  
	function showBoardList(list,is_top){
		$(list_id).html("");

		list.forEach(function(row){
			var $clone  = $("#tpl_list").clone();
      var $content = $clone.html();

      $content = $content.replaceAll("{{rc_idx}}",row.rc_idx);
      $content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");
			if(row.pm_idx == null){
				$content = $content.replaceAll("<span>추천</span>","");
				$content = $content.replaceAll("{{timestamp}}","등록일 : " + row.up_dt);
			}else{
				$content = $content.replaceAll("{{timestamp}}","<p class=\"reco-title\">지금뜨는 추천공고</p>");
			}
			$content = $content.replaceAll("{{ad_addr}}",fRemoveBigAdrJS(row.ad_addr));
			$content = $content.replaceAll("{{rc_name}}",row.rc_name);

			if(row.rc_nation_nm=="무관"||row.rc_nation_nm=="내국인,외국인"){
				$content = $content.replaceAll("{{rc_nation_nm}}","국적무관");
			}else{
				$content = $content.replaceAll("{{rc_nation_nm}}",row.rc_nation_nm);
			}

			if(row.rc_age_nm=="무관"){
				$content = $content.replaceAll("{{rc_age_nm}}","나이무관");
			}else{
				$content = $content.replaceAll("{{rc_age_nm}}",row.rc_age_nm);
			}

			$content = $content.replaceAll("{{rt_recruit_img}}",row.rt_recruit_img);

			var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
			$images.slice(2).remove();
			var $categoryHtml = $images.parent().html();
			$content = $content.replaceAll("{{rt_work_img}}", $categoryHtml);

			$content = $content.replaceAll("{{rt_work_type}}",getConvertWorkType(row.rt_work_type));
      
			if(row.rc_price_type.indexOf('협의') > -1){
				$content = $content.replaceAll("{{rc_price_type}}","");
			}else{
				$content = $content.replaceAll("{{rc_price_type}}",getConvertPriceType(row.rc_price_type, row.rc_price_rep));
			}

			if(row.rc_price_type.indexOf('협의') > -1){
				$content = $content.replaceAll("{{rc_price_pay}}","급여협의");
			}else{
				$content = $content.replaceAll("{{rc_price_pay}}",Number(row.rc_price_pay).toLocaleString()+"원");
			}
			$content = $content.replaceAll("{{mm_id}}",row.mm_id);

      $clone.html($content);
      $(list_id).append($clone.html());
		});

		if(is_top==true){
			var ajax_top = $(".search-result").position().top - 50;
			$( 'html, body' ).animate( { scrollTop : ajax_top }, 400 );
		}
	}
  
	function fRemoveBigAdrJS(arg) {

		const parts1 = arg.split(",");
		arg = parts1[0];

		arg = arg.replace("서울특별시 ", "");
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
	
	function getHashTagJS(hash, max = 10, rc_work) {
		let result = "";
		let tmp = [...hash.matchAll(/<p[^>]*>#?([^<]+)<\/p>/g)]
		  .map(match => match[1])
		  .join(',');
		  
		let arr_hash = tmp.split(",");
		let arr_work = rc_work.split(",");
		arr_work.forEach(function(txt, key) {
			if (key < max) {
				if (txt.trim() !== "") {
					arr_hash.push(txt);
				}
			}
		});
		
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
</script>

<script>
	$(document).ready(function(){
		initPage(false);
	});
  $(document).on('click', '.desc', function () {
    var rcIdx = $(this).closest('.list-item').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post_new/' + rcIdx;
      window.open(url, '_blank');
    }
  });   
</script>

