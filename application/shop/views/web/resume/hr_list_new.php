<?php
	$banner_list = getBannerList("BN00302041");
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
  .item-info .content-wrap { padding:0;justify-content: center; }
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
    width:100%;
    margin: 0 0 0 105px;
    cursor: pointer;   
    display: flex;
  }
  .item-info .reco-title{
    color: #1b9d7d;
    font-weight: 600;
  }
  .item-info .timestamp{
    margin-bottom:5px;
  }
  .item-info .period{
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
  .item-info .meta{
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
  .item-info .price_type{
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
    color:#3a70db;
  }
  .item-info .price_type em.wtype2 {
    color:#F86048;
  }
  .item-info .price_type em.wtype3 {
    color:#1B9D7D;
  }
  .item-info .working-day {
    width:25%;
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
<div id="content" class="page page-person">
  <main class="page-content">
    <div class="visual-banner person">
      <div class="container">
        <div class="visual-content">
          <p>새로 등록된 추천 인재</p>
          <p class="headline">지금 꼭, 필요한 인재</p>
          <a href="javascript:void(0);" id="btnRegist" class="btn-round">채용공고 작성</a>
        </div>
      </div>
    </div>
    <div class="recomm-slider person">
      <div class="container">
        <h2 class="primary-title">추천인재</h2>
        <div class="slider-wrap keen-slider" id="my-keen-slider">
        </div>
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
            <?php include_once (get_base_url() . '/application/shop/views/web/_layout/search_resume.php'); ?>
            <div class="result-wrap">
              <div class="result-header">
                <p class="total">인재정보 <em  id="total_count"></em>건</p>
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
              <div class="list-action v2">
                <a href="/resume/hr_map" title="지도에서 찾기" class="btn-map"><span class="sr-text">지도보기</span></a>
              </div>
              <script>
                window.addEventListener('scroll', function () {
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
	<li class="list-item item-hover" data-ru_idx="{{ru_idx}}">
		<div class="item-info">
      <div class="img">
        <img src="{{ru_resume_img}}">
        <?php if($mm_type === "COMP") {?><button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
        <p class="recomm-badge"><span>추천</span></p>
      </div>
      <p class="star-rating"><span class="star" style="width:100%;"></span><span class="sr-text">평점</span><span class="sr-text">5</span></p>
      <div class="desc">
        <div class="content-wrap">
          <div class="timestamp">등록일 : {{timestamp}}</div>
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

        <div style="align-content: center;width: 300px;text-align: center;">
          <span class="type price_type">{{ru_work_type}}{{ru_price_type}}</span>
          <span class="area">{{ad_addr}}</span>
        </div>
        <ul class="working-day">
          {{ru_week}}
        </ul>
      </div>
		</div>
	</li>
</template>

<!-- 추천 리스트 템플릿 -->
<template id="tpl_reco_list">
  <div class="keen-slider__slide " >
    <div class="person-card" data-ru_idx="{{ru_idx}}">
      <div class="thumb-wrap">
        <div class="img">
          <img src="{{ru_resume_img}}">
          <?php if($mm_type === "COMP") {?><button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
        </div>      
      </div>
      <div class="desc">
        <div class="content-wrap" style="padding: 46px 5px 24px;">
          <p class="category">
            {{ru_work_img}}
          </p>
          <div class="title-wrap">
            <p class="title"><strong>{{mm_name}}</strong></p>
            <p class="area">{{ad_addr}}</p>
          </div>
          <div class="meta">
            <p class="national">{{mm_nation}}</p>
            <p class="age">{{mm_age}}</p>
            <p class="work_type">{{ru_work_type}}</p>
            <p class="price_type">{{ru_price_type}}</p>
          </div>
          <div class="hashtag">
            {{ru_my_type}}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<input type="hidden" name="mm_type" value="<?=$this->user->getUserType()?>" />
<script>
	var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
	var list_page = 1;
	var list_loading = false;
  var search_addr = [];
  var searchAreaStr = "";  
  var addrName = "";
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

	$(document).ready(function(){
		$('.btn-map').insertBefore('#bottomchatbtn');
    getRecoList();
    initPage(false);
	});

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
		setTimeout(function(){
    	getBoardListDelay(is_top);
		}, 500);
	}

	function getBoardListDelay(is_top){
		setBoardParams();
    var params = {
      page: list_page,
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
      url: "/resume_proc/search_list2025",
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
        var cate_type = "cate1";
        if(row.bd_cate=="업무"){
          cate_type = "cate1";
        }else if(row.bd_cate=="일상"){
          cate_type = "cate2";
        }else if(row.bd_cate=="질문"){
          cate_type = "cate3";
        }

        if(row.pm_idx==null){
          $content = $content.replaceAll("{{timestamp}}",row.up_dt_timestamp);
          $content = $content.replaceAll("<p class=\"recomm-badge\"><span>추천</span></p>","");
        }else{
          $content = $content.replaceAll("{{timestamp}}","<p class=\"reco-title\">지금뜨는 추천인재</p>");
          $content = $content.replaceAll("등록일 : ","");
        }

      $content = $content.replaceAll("{{ru_idx}}",row.ru_idx);
      $content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");
			$content = $content.replaceAll("{{mm_name}}",row.ru_name);
			$content = $content.replaceAll("{{ru_week}}",row.ru_week);
			$content = $content.replaceAll("{{distance}}",row.distance + "km");
			$content = $content.replaceAll("{{ru_work_img}}",row.ru_work_img);

			if(row.mm_nation.trim()==''){
				$content = $content.replaceAll("{{mm_nation}}","");
				$content = $content.replaceAll("class=\"national\" style=\"display: inline-block;\"","class=\"national\" style=\"display: none;\"");
			}else{
				$content = $content.replaceAll("{{mm_nation}}",row.mm_nation);
			}

			if(row.mm_age=='' || row.mm_age=='0'){
				$content = $content.replaceAll("{{ma_age}}","");
				$content = $content.replaceAll("class=\"age\" style=\"display: inline-block;\"","class=\"age\" style=\"display: none;\"");
			}else{
				$content = $content.replaceAll("{{ma_age}}",row.mm_age+"대");
			}

			$content = $content.replaceAll("{{ru_price_type}}",getPriceType(row.ru_price_type));
			$content = $content.replaceAll("{{ru_my_type}}",getHashTagJS(row.ru_my_type,2));
			$content = $content.replaceAll("{{ru_work_type}}",getWorkType(row.ru_work_type));
			$content = $content.replaceAll("{{ru_resume_img}}",row.ru_resume_img);
      $content = $content.replaceAll("{{ad_addr}}",fRemoveBigAdrJS(row.ad_addr));

      $clone.html($content);

      $(list_id).append($clone.html());
		});

		if(is_top==true){
			var ajax_top = $(".search-result").position().top - 50;
			$( 'html, body' ).animate( { scrollTop : ajax_top }, 400 );
		}
	}
  
 	function getRecoList(){
    var params = {
      "addr1" : "",
      "addr2" : "",
      "addr3" : "",
      "limit" : "10"
    };
		comAjax("/resume_proc/search_reco_list2025",params,function(ret){
			if(ret.code=="00"){
				var list = ret.data.list;
				$("#my-keen-slider").html("");
				$(".arrow--left").remove();
				$(".arrow--right").remove();
				list.forEach(function(row){
					var $clone  = $("#tpl_reco_list").clone();
					var $content = $clone.html();
					
          $content = $content.replaceAll("{{ru_idx}}",row.ru_idx);
          $content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");
          $content = $content.replaceAll("{{mm_name}}",row.mm_name);
          $content = $content.replaceAll("{{ru_week}}",row.ru_week);
          $content = $content.replaceAll("{{distance}}",row.distance + "km");
          $content = $content.replaceAll("{{ru_work_img}}",row.ru_work_img);

          if(row.mm_nation.trim()==''){
            $content = $content.replaceAll("{{mm_nation}}","");
            $content = $content.replaceAll("class=\"national\" style=\"display: inline-block;\"","class=\"national\" style=\"display: none;\"");
          }else{
            $content = $content.replaceAll("{{mm_nation}}",row.mm_nation);
          }

          $content = $content.replaceAll("{{mm_age}}",row.mm_age+"대");

          $content = $content.replaceAll("{{ru_price_type}}",getPriceType(row.ru_price_type));
          $content = $content.replaceAll("{{ru_work_type}}",getWorkType(row.ru_work_type));
          $content = $content.replaceAll("{{ru_resume_img}}",row.ru_resume_img);
          $content = $content.replaceAll("{{ru_my_type}}",getHashTagJS(row.ru_my_type,2));
          $content = $content.replaceAll("{{ad_addr}}",fRemoveBigAdrJS(row.ad_addr));
					$content = $content.replaceAll("{{timestamp}}",row.timestamp);

					$clone.html($content);

					$("#my-keen-slider").append($clone.html());
				});
				
				var slideCount = document.querySelectorAll('.recomm-slider .slider-wrap .keen-slider__slide').length;
				var perView = 5;
				var slider = new KeenSlider(".recomm-slider .slider-wrap", {
					loop: slideCount > perView,
					slides: { perView: perView },
				}, [
					addActiveClass,
					sliderArrows
				]);

				var interval = 0;
				var sliderElement = document.getElementById("my-keen-slider");
				function autoplay(run) {
				    clearInterval(interval);
				    interval = setInterval(() => {
				      if (run && slider) {
				        slider.next();
				      }
				    }, 3400);
				}

				sliderElement.addEventListener("mouseover", () => {
					//autoplay(false);
				});
				sliderElement.addEventListener("mouseout", () => {
					//autoplay(true);
				});
				//autoplay(true);
			}else{

			}
		});
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
  
  function getPriceType(price_type) {
      if (!price_type) {
          return "";
      }
      if (price_type === "일급,주급,월급") {
          return "급여협의";
      }
      return price_type
          .split(',')
          .map(type => `${type}`)
          .join('');
  }
  
  function getWorkType(work_type) {
    if (!work_type) {
      return "";
    }
    const matches = work_type.match(/<span>(.*?)<\/span>/g);

    if (!matches) {
      return "";
    }
    return matches.map(item => {
      const text = item.replace(/<\/?span>/g, '').trim();
      let cls = '';
      if (text === '주간') {
        cls = 'wtype1';
      } else if (text === '야간') {
        cls = 'wtype2';
      } else if (text === '2교대') {
        cls = 'wtype3';
      }
      return `<em class="${cls}">${text}</em>`;
    }).join('');
  }
   
	function getHashTagJS(hash, max = 10) {
		let result = "";
    let tmp = hash;
    tmp = tmp.replaceAll("<p class=\'tag\' style=\'width:100%;text-align:right;\'>","");
    tmp = tmp.replaceAll("</p>","");
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
	
</script>

<script>
  $(document).on('click', '.desc', function () {
    <?php if($mm_type !== "COMP") {?>
        commonAlert("기업회원 전용 서비스입니다.",function(){});
        return;
    <?php } else { ?>
      var ruIdx = $(this).closest('.list-item,.person-card').data('ru_idx');
      if (ruIdx) {
        var url = '/resume/hr_post_new/' + ruIdx;
        window.open(url, '_blank');
      }
    <?php } ?>
  });
</script>
