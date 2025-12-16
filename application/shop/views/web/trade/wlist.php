<?php

	$banner_list = getBannerList("BN00302040");
	$banner = null;
	if(count($banner_list) > 0){
		$banner = $banner_list[0];
	}
?>
<div id="content" class="page page-market">
    <main class="page-content" style="padding-top:0px; padding-bottom: 0px;"><!-- 임시 숨김처리 padding-top:0px; padding-bottom: 0px;-->
				<!-- 임시 숨김처리 -->
        <div class="visual-banner market" style="display:none;">
            <div class="container">
                <div class="visual-content" style="position:relative;">
                    <p>새로 등록된 추천 거래</p>
                    <p class="headline">지금 뜨는, 좋은 거래</p>
                    <!--<a href="/trade/trade_write" class="btn-round">상품등록</a>-->
										<span style="position:absolute;left:84px;bottom:30px;">중고거래는 카드결제 및 충전금액 차감되지 않습니다.</span>
                </div>
            </div>
        </div>

				<!-- 임시 숨김처리 -->
				<div style="display: none; width: 100%; height: 240px; text-align: center; padding-top: 150px; font-size: 26px;">준비중</div>
				<div style="display: block; width: 100%; text-align: center; padding-top: 0px; font-size: 26px; margin-top: 80px;background-color: #fff;">
					<img style="width: 100%; max-width: 1200px; margin-top: 70px; margin-bottom: 0px;" src="/resource/shop/web/img/trade_comingsoon.png">
				</div>
				<div style="display: block; width: 100%; height: 124px; margin-top: -124px; background-color: #ACE6D8;">
				</div>

				<!-- 임시 숨김처리 -->
        <div class="section recomm-keyword" style="display:none;">
            <div class="container" style="position:relative;">
                <p class="title">#추천키워드</p>
								<div id="recomm-keyword-left-arrow" style="display: inline-block; width: 20px; height: 20px; line-height: 17px; border: 1px solid #777; border-radius: 10px; text-align: center; position: absolute; top: 10px; cursor:pointer;">←</div>
                <div id="hashtag-container" class="hashtag" style="display:inline-block; width: 1190px; margin-left: 30px; transition: scroll 0.3s; ">
                    <?php foreach($tag_list as $tag) : ?>
                    	<a class="tag btnTag" href="javascript:void(0);" data-tag="<?=$tag?>">#<?=$tag?></a>
                    <?php endforeach;?>
                </div>
								<div id="recomm-keyword-right-arrow" style="display: inline-block; width: 20px; height: 20px; line-height: 17px; border: 1px solid #777; border-radius: 10px; text-align: center; position: absolute; top: 10px; margin-left: 10px; cursor:pointer;">→</div>
            </div>
        </div>
				<!-- 임시 숨김처리 -->
        <div class="recomm-slider sale" style="display:none;">
            <div class="container" style="max-width:1631px;">
                <h2 class="primary-title">추천 중고거래</h2>

                <div class="slider-wrap keen-slider">
                    <?php foreach($trade_reco_list as $row) : ?>
                    <div class="keen-slider__slide">
                        <div class="sale-card">
                            <div class="thumb-wrap">
                                <!-- 추천라벨 -->
                                <p class="recomm-badge"><span>추천</span></p>
                                <!-- 즐겨찾기 추가된 목록: on -->
                                <button type="button" data-bk_type="TRADE" data-bk_key="<?=$row["td_idx"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 추가</span></button>
                                <!-- 썸네일 -->
                                <div class="thumb">
                                     <a href="/trade/trade_detail/<?=$row["td_idx"]?>" target="_blank"><img data-src="<?=getTradeImg($row)?>" alt="" class="lozad"></a>
                                </div>
                            </div>
                            <div class="content-wrap">
                                <p class="title"><strong><a href="/trade/trade_detail/<?=$row["td_idx"]?>" target="_blank"><?=$row["td_name"]?></a></strong></p>
					            <div class="meta">
					                <p class="area"><?=$row["ad_addr"]?></p>
					            </div>
					            <div class="price-wrap" style="text-align:right;">
					                <p class="date" style="display:none;"><?=get_yyyymmdd($row["reg_dt"])?></p>
													<p class="amount"><em class="num"><?=number_format($row["td_price"])?></em> 원</p>
					            </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <script>
                window.addEventListener('DOMContentLoaded', function () {
                    if (typeof KeenSlider === undefined) return;

                    new KeenSlider(".recomm-slider .slider-wrap", {
                        loop: true,
                        slides: { perView: 6 },
                    }, [
                        addActiveClass,
                        sliderArrows
                    ]);
                });
            </script>
        </div>

				<!-- 임시 숨김처리 -->
        <div class="section" style="display:none;">
            <div class="container">

                <div class="search-result">

                    <!-- 퀵옵션 -->
                    <div class="sidebar">
                        <div class="widget">
                            <div class="widget-header">
                                <p class="title">지역별</p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-options align-center">
                                    <ul class="form-group col-4">
                                    	<li><a href="javascript:void(0);" onclick="setTab(this,'addr','전체');" role="button" class="widget-option">전체</a></li>
                                    	<?php foreach($g_addr_list as $key1 => $addr) : ?>
                                        <li><a href="javascript:void(0);" onclick="setTab(this,'addr','<?=$addr["ca_ad1"]?>');" role="button" class="widget-option"><?=$addr["ca_ad1"]?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <a href="/resume/index_map" class="find-map">지도에서 찾기</a>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="widget-header">
                                <p class="title">카테고리별</p>
                            </div>
                            <div class="widget-body" style="padding: 25px 11px 30px;">
                                <div class="widget-options">
                                    <ul class="form-group col-3">
																				<li style="width: 23%; flex: 0 0 23%;"><a href="javascript:void(0);" onclick="setTab(this,'category','전체');"  class="widget-option">전체</a></li>
  			                                <li style="width: 37%; flex: 0 0 37%;"><a href="javascript:void(0);" onclick="setTab(this,'category','굿즈');" class="widget-option">굿즈</a></li>
	  		                                <li style="width: 40%; flex: 0 0 40%;"><a href="javascript:void(0);" onclick="setTab(this,'category','악세사리/신발');" class="widget-option">악세사리/신발</a></li>
	  		                                <li style="width: 23%; flex: 0 0 23%;"><a href="javascript:void(0);" onclick="setTab(this,'category','의류');" class="widget-option">의류</a></li>
	  		                                <li style="width: 37%; flex: 0 0 37%;"><a href="javascript:void(0);" onclick="setTab(this,'category','기타');" class="widget-option">기타</a></li>
	  		                                <li style="width: 40%; flex: 0 0 40%;"><a href="javascript:void(0);" onclick="setTab(this,'category','생활용품/유아');" class="widget-option">생활용품/유아</a></li>
	  		                                <li style="width: 23%; flex: 0 0 23%;"><a href="javascript:void(0);" onclick="setTab(this,'category','미용');" class="widget-option">미용</a></li>
	  		                                <li style="width: 37%; flex: 0 0 37%;"><a href="javascript:void(0);" onclick="setTab(this,'category','디지털가전');" class="widget-option">디지털가전</a></li>
	  		                                <li style="width: 40%; flex: 0 0 40%;"><a href="javascript:void(0);" onclick="setTab(this,'category','음반/악기');" class="widget-option">음반/악기</a></li>
	  		                                <li style="width: 50%; flex: 0 0 50%;"><a href="javascript:void(0);" onclick="setTab(this,'category','자동차/오토바이');" class="widget-option">자동차/오토바이</a></li>
	  		                                <li style="width: 50%; flex: 0 0 50%;"><a href="javascript:void(0);" onclick="setTab(this,'category','인테리어/가구');" class="widget-option">인테리어/가구</a></li>
                                    	<?php /* foreach($category_list as $row) : ?>
		                                <li><a href="javascript:void(0);" onclick="setTab(this,'category','<?=$row["cd_val"]?>');"  class="widget-option"><?=$row["cd_val"]?></a></li>
		                                <?php endforeach; */?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="widget">
                            <div class="widget-header">
                                <p class="title">가격대별</p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-options">
                                    <ul class="form-group col-2">
                                    	<?php for($icnt=1;$icnt < 11;$icnt++) : ?>
                                        	 <li><a href="javascript:void(0);" onclick="setTab(this,'eprice','<?=($icnt * 10)?>');" class="widget-option"><?=($icnt * 10)?>만원 이하</a></li>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 검색내용 -->
                    <div class="content-area">

                        <div id="advancedSearchWidget" class="widget" hidden>
                            <div class="widget-header">
                                <p class="title">지역별</p>
                                <a href="/resume/index_map" class="find-map">지도에서 찾기</a>
                            </div>
                            <div class="widget-body">
                                <!-- 상세검색 -->
                                <div class="advanced-search">
                                    <div class="area">
                                        <!-- 지역(시) -->
                                        <div class="area-si">
                                        	<label class="option">
                                                <input type="radio" name="searchArea1" value="전체">
                                                <span class="btn">전체</span>
                                            </label>
                                        	<?php foreach($g_addr_list as $key1 => $addr) : ?>
	                                        <label class="option">
                                                <input type="radio" name="searchArea1" value="<?=$addr["ca_ad1"]?>">
                                                <span class="btn"><?=$addr["ca_ad1"]?></span>
                                            </label>
	                                        <?php endforeach; ?>
                                        </div>
                                        <!-- 지역(구군) -->
                                        <div class="area-gugun">
                                            <label class="form-checkbox">
                                                <input type="radio" name="searchArea2" value="">
                                                <span class="label">전체</span>
                                            </label>
                                            <?php foreach($g_addr_list as $key1 => $row) : ?>
	                                        	<?php foreach($row as $key2=> $row2) : ?>
	                                        		<label class="form-checkbox" style="display:none;">
		                                                <input type="radio" data-addr1="<?=$key1?>" name="searchArea2" value="<?=$key2?>">
		                                                <span class="label"><?=$key2?></span>
		                                            </label>
	                                        	<?php endforeach; ?>
	                                        <?php endforeach; ?>
                                        </div>
                                        <!-- 지역(동) -->
                                        <div class="area-dong" hidden>
                                        	<label class="form-checkbox">
                                                <input type="radio" name="searchArea3" value="">
                                                <span class="box">전체</span>
                                            </label>
                                        </div>

                                        <script>
                                            /* 지역(동) 옵션 표시 */
                                            function showSearchArea3 () {
                                                document.querySelector('.area-dong').hidden = false;
                                            }

                                            /* 지역(시) 선택 해제 */
                                            function deSelectArea1 (items) {
                                                items.forEach(function (item) {
                                                    if (item.classList.contains('on')) {
                                                        item.classList.remove('on');
                                                    }
                                                });
                                            }

                                            window.addEventListener('DOMContentLoaded', function () {
                                                var options = document.querySelectorAll('.widget-option[data-id]');
                                                var areas = document.querySelectorAll('input[name="searchArea1"]');

                                                /* 지역(시) 선택 */
                                                areas.forEach(function (area) {
                                                    area.addEventListener('change', function (e) {
                                                        var target = document.querySelector('[data-id="#'+ this.getAttribute('id') +'"]');

                                                        if (!target) return;

                                                        deSelectArea1(options);

                                                        target.classList.add('on');
                                                    });
                                                });


                                                /* 지역(구군) 선택시 지역(동) 옵션 표시 */
                                                //document.querySelector('.area-gugun').addEventListener('change', function (e) {
                                                    //showSearchArea3();
                                                //});
                                            });
                                        </script>
                                    </div>
                                    <div class="other">
                                        <!-- 추가옵션 -->
                                        <div class="form-table">
                                            <div class="form-row">
                                                <p class="form-label">카테고리별</p>
                                                <div class="value">
                                                    <div class="form-group col-7">
                                                        <label class="form-checkbox">
                                                            <input type="checkbox" name="category_all" value="all" class="js-check-all">
                                                            <span class="box">전체</span>
                                                        </label>
                                                        <?php foreach($category_list as $row) : ?>
                                                        <label class="form-checkbox">
                                                            <input type="checkbox" name="category[]" value="<?=$row["cd_val"]?>">
                                                            <span class="box"><?=$row["cd_val"]?></span>
                                                        </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <p class="form-label">가격대별</p>
                                                <div class="value">
                                                    <div class="form-group col-8">
                                                        <?php for($age=1;$age < 11;$age++) : ?>
			                                        		<label class="form-checkbox">
	                                                            <input type="checkbox" name="eprice" value="<?=($age * 10)?>">
	                                                            <span class="box"><?=($age * 10)?>만원 이하</span>
	                                                        </label>
				                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<span class="btnCloseAdv"><img src="/resource/shop/web/img/icon_close.png" style="width:23px" /></span>
                        </div>

                    <!-- 검색내용 -->
                    <div class="content-area">

                        <div id="advancedSearchWidget" class="widget" hidden>
                            <div class="widget-header">
                                <p class="title">지역별</p>
                                <a href="/resume/index_map" class="find-map">지도에서 찾기</a>
                            </div>
                            <div class="widget-body">
                                <!-- 상세검색 -->
                                <div class="advanced-search">
                                    <div class="area">
                                        <!-- 지역(시) -->
                                        <div class="area-si">
                                        	<label class="option">
                                                <input type="radio" name="searchArea1" value="">
                                                <span class="btn">전체</span>
                                            </label>
                                        	<?php foreach($g_addr_list as $key1 => $addr) : ?>
	                                        <label class="option">
                                                <input type="radio" name="searchArea1" value="<?=$key1?>">
                                                <span class="btn"><?=$key1?></span>
                                            </label>
	                                        <?php endforeach; ?>
                                        </div>
                                        <!-- 지역(구군) -->
                                        <div class="area-gugun">
                                            <label class="form-checkbox">
                                                <input type="radio" name="searchArea2" value="">
                                                <span class="label">전체</span>
                                            </label>

                                        </div>
                                        <!-- 지역(동) -->
                                        <div class="area-dong" hidden>
                                        	<label class="form-checkbox">
                                                <input type="radio" name="searchArea3" value="">
                                                <span class="box">전체</span>
                                            </label>
                                        </div>

                                        <script>
                                            /* 지역(동) 옵션 표시 */
                                            function showSearchArea3 () {
                                                document.querySelector('.area-dong').hidden = false;
                                            }

                                            /* 지역(시) 선택 해제 */
                                            function deSelectArea1 (items) {
                                                items.forEach(function (item) {
                                                    if (item.classList.contains('on')) {
                                                        item.classList.remove('on');
                                                    }
                                                });
                                            }

                                            window.addEventListener('DOMContentLoaded', function () {
                                                var options = document.querySelectorAll('.widget-option[data-id]');
                                                var areas = document.querySelectorAll('input[name="searchArea1"]');

                                                /* 지역(시) 선택 */
                                                areas.forEach(function (area) {
                                                    area.addEventListener('change', function (e) {
                                                        var target = document.querySelector('[data-id="#'+ this.getAttribute('id') +'"]');

                                                        if (!target) return;

                                                        deSelectArea1(options);

                                                        target.classList.add('on');
                                                    });
                                                });


                                                /* 지역(구군) 선택시 지역(동) 옵션 표시 */
                                                //document.querySelector('.area-gugun').addEventListener('change', function (e) {
                                                    //showSearchArea3();
                                                //});
                                            });
                                        </script>
                                    </div>
                                    <div class="other">
                                        <!-- 추가옵션 -->
                                        <div class="form-table">
                                            <div class="form-row">
                                                <p class="form-label">카테고리별</p>
                                                <div class="value">
                                                    <div class="form-group col-8">
                                                        <label class="form-checkbox">
                                                            <input type="checkbox" name="category_all" value="all" class="js-check-all">
                                                            <span class="box">전체</span>
                                                        </label>
                                                        <?php foreach($wrok_list as $row) : ?>
                                                        <label class="form-checkbox">
                                                            <input type="checkbox" name="category[]" value="<?=$row["cd_val"]?>">
                                                            <span class="box"><?=$row["cd_val"]?></span>
                                                        </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <p class="form-label">가격대별</p>
                                                <div class="value">
                                                    <div class="form-group col-8">
                                                        <?php for($age=1;$age < 11;$age++) : ?>
			                                        		<label class="form-checkbox">
	                                                            <input type="radio" name="eprice" value="<?=($age * 10)?>">
	                                                            <span class="box"><?=($age * 10)?>만원 이하</span>
	                                                        </label>
				                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 검색결과 -->
                        <div class="result-header">
                            <!-- 총개수 -->
                            <p class="total">검색결과 <em  id="total_count"></em>건</p>
                            <!-- 정렬 -->
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

                        <div class="result-wrap">
                            <!-- 목록 -->
                            <ol class="result-list" id="ajaxList" data-page="1" style="min-height:400px;">
                        	</ol>
                            <!-- //목록 -->

                            <div id="page_cont">

                            </div>

                            <div class="list-action">
                                <a href="/trade/index_map" title="지도에서 찾기" class="btn-map"><span class="sr-text">지도보기</span></a>
                            </div>

                            <script>
                                /* 목록이 3개이하인경우 리스트액션 고정 */
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

                        </div><!-- //selction-wrap -->

                    </div><!-- //content-area -->

                </div><!-- //container -->

            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->



<!-- 리스트 템플릿 -->
<template id="tpl_list">
	<li class="list-item item-hover click-item-new" data-link="/trade/trade_detail/{{td_idx}}">
	    <div class="item-info colgroup">
	        <div class="thumb-wrap">
	            <!-- 추천라벨 -->
	            <p class="recomm-badge"><span>추천</span></p>
	            <button type="button" data-bk_type="TRADE" data-bk_key="{{td_idx}}"  class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 해제</span></button>
                <div class="thumb big">
                    <a href="/trade/trade_detail/{{td_idx}}" target="_blank"><img src="{{td_img}}" alt="" class=""></a>
                </div>
	        </div>
	        <div class="content-wrap">
	            <!-- 제목 -->
	            <p class="title"><strong><a href="/trade/trade_detail/{{td_idx}}" target="_blank">{{td_name}}</a></strong></p>
	            <!-- 지역 -->
	            <div class="meta">
	                <p class="area">{{ad_addr}}</p>
	            </div>
	        </div>
	        <!-- 작성일 -->
	        <p class="regdate">{{reg_dt}}</p>
	        <!-- 금액 -->
	        <div class="pay">
	            <p class="amount"><em class="num">{{td_price}}</em> 원</p>
	        </div>
	    </div>
	</li>
</template>

<input type="hidden" name="recruit_yn" value="<?=$recruit ==null ? "N":"Y"?>" />
<script>
	$(".btnTag").click(function(){
		var ar_cate = ["의류","악세사리/신발","디지털가전","인테러이/가구","음반/악기","생활용품/유아","미용","굿즈","자동차/오토바이","기타"];
		var ar_price = ["10만원 이하","20만원 이하","30만원 이하","40만원 이하","50만원 이하","60만원 이하","70만원 이하","80만원 이하","90만원 이하","100만원 이하"];
		var tag = $(this).attr("data-tag");

		if(ar_cate.indexOf(tag) > -1){
			top_search_array.category = tag;
		}

		if(ar_price.indexOf(tag) > -1){
			top_search_array.eprice = tag.replace("만원 이하","");
		}

		localStorage.setItem("top_search_array",JSON.stringify(top_search_array));
		localStorage.setItem("top_search_yn","Y");
		location.href="/trade/wlist";
	});

	$(".btnCloseAdv").click(function(){
		$("#advancedSearchWidget").attr("hidden","");
	});

	$(document).on("click",".form-checkbox",function(e){
		e.stopPropagation();
	});

	$("#btnRequest").click(function(){
        //로그인 여부 확인
        if($("#is_login").val()!="Y"){
            commonLogin();
            return;
        }

        //이력서 여부 확인
        if($("[name=recruit_yn]").val()=="N"){
            commonAlert("채용공고 등록및 공개채용공고 설정후 제안해주세요.",function(){});
            return;
        }

		var mq_idxs = new Array();

		for(var icnt =0;icnt < $("[name='chk[]']:checked").length;icnt++){
			mq_idxs.push($("[name='chk[]']:checked").eq(icnt).val());
		}

		if(mq_idxs.length==0){
			commonAlert("제안하실 공고를 선택해주세요.",function(){});
			return;
		}

		openPopup('confirm', {
            content: '<em>“선택하신</em>공고를<br> 제안하시겠습니까?',
            secondCallback: function () {
              	var params = {};
			    params["mq_idxs"] = mq_idxs;
			    params["mq_type"] = "RESUME";
			    comAjax("/common_proc/member_request",params,function(ret){
			        if(ret.code=="00"){
			        	commonAlert("제안 신청 되었습니다.",function(){
			        		$("[name='chk[]']").prop("checked",false);
			        	});
			        }else{
			            commonAlert(ret.msg,function(){});
			            return;
			        }
			    });
            }
        });
	});
</script>

<script>
	var list_id = "#ajaxList";
    var list_empty_id = "#ajaxEmpty";
	var list_page = 1;
	var list_loading = false;

	var search_array = {
		text : "",
		addr1 : "",
		addr2 : "",
		addr3 : "",
		category : "",
		sprice : "",
		eprice : "",
		order_by : "",
	};

	$(document).ready(function(){
		//데이터 캐싱처리
		var params = {
			cashing_id: "recruit_cashing",
			list_id: list_id,
			category_list_id : "#page_cont",
			scroll_obj : $(".page-recruit"),
			fn_cashing: initCashingData,
			fn_load: initLoadData,
		};
		$.CashingAjax(params);

		//추천키워드 슬라이드 시작
		var scrollAmount = 5; // Set the scroll amount
		var scrollInterval;

		// Function to scroll left
		function scrollLeft() {
				$('#hashtag-container').scrollLeft($('#hashtag-container').scrollLeft() - scrollAmount);
		}

		// Function to scroll right
		function scrollRight() {
				$('#hashtag-container').scrollLeft($('#hashtag-container').scrollLeft() + scrollAmount);
		}

		// Mouse enter event for left arrow
		$('#recomm-keyword-left-arrow').mouseenter(function() {
				scrollInterval = setInterval(scrollLeft, 100);
		}).mouseleave(function() {
				clearInterval(scrollInterval);
		});

		// Mouse enter event for right arrow
		$('#recomm-keyword-right-arrow').mouseenter(function() {
				scrollInterval = setInterval(scrollRight, 100);
		}).mouseleave(function() {
				clearInterval(scrollInterval);
		});
		//추천키워드 슬라이드 종료

		var tagWidth = $('.btnTag').outerWidth(true);
		// Click event for left arrow
		$('#recomm-keyword-left-arrow').click(function() {
				// Scroll left by one tag's width
				$('#hashtag-container').animate({
						scrollLeft: '-=' + tagWidth
				}, 300); // Adjust the animation speed (300ms)
		});

		// Click event for right arrow
		$('#recomm-keyword-right-arrow').click(function() {
				// Scroll right by one tag's width
				$('#hashtag-container').animate({
						scrollLeft: '+=' + tagWidth
				}, 300); // Adjust the animation speed (300ms)
		});


	});

	//캐싱처리 추가 작업
	function initCashingData() {
		//상단 검색처리
		initTopSearch(true);
	}

	//데이터 호출 처리
	function initLoadData() {
		//상단 검색처리
		initTopSearch(false);

		//검색
		initPage(false);
	}

	//카테고리 전체
	$("[name='category_all']").click(function(){
		if($(this).is(":checked")){
			$("[name='category[]']").prop("checked",true);
		}else{
			$("[name='category[]']").prop("checked",false);
		}

		for(var icnt=0;icnt < $("[name='category[]']").length;icnt++){
			var cate_nm = $("[name='category[]']").eq(icnt).val();

			for(var jcnt=0;jcnt < $(".widget-option").length;jcnt++){
				if(cate_nm==$(".widget-option").eq(jcnt).text()){
					if($(this).is(":checked")){
						$(".widget-option").eq(jcnt).addClass("on");
					}else{
						$(".widget-option").eq(jcnt).removeClass("on");
					}
				}
			}
		}
	});

	//카테고리 전체 처리
	$("[name='category[]']").click(function(){
		if($("[name='category[]']").length==$("[name='category[]']:checked").length){
			$("[name='category_all']").prop("checked",true);
		}else{
			$("[name='category_all']").prop("checked",false);
		}
	});

	//정렬변경
	$("[name='order_by']").click(function(){
		//검색
		initPage(true);
	});

	$("[name='category_all']").click(function(){
		//검색
		initPage(true);
	});

	//나이 처리
	$("[name='eprice']").click(function(){
		$("[name='eprice']").not(this).prop("checked",false);
		changeTab(this,"eprice",$(this).val()+"만원 이하");
	});

	$("[name='category[]']").click(function(){
		changeTab(this,"category",$(this).val());
	});

	//왼쪽 메뉴 상태 변경
	function changeTab($this,type,val){
		if(type=="addr" || type=="eprice"){
			if(type=="addr"){
				$(".widget-option").each(function(i){
					if(val==$(this).text()){
						$(this).closest(".form-group").find("a").removeClass("on");
						$(this).addClass("on");
					}
				});
			}else{
				$(".widget-option").each(function(i){
					if(val==$(this).text()){
						if($($this).is(":checked")){
							$(this).closest(".form-group").find("a").removeClass("on");
							$(this).addClass("on");
						}else{
							$(this).closest(".form-group").find("a").removeClass("on");
							$(this).removeClass("on");
						}
					}
				});
			}
		}else{
			if(type=="week_yn"){
				if($($this).is(":checked")){
					$(".left-week").find("a").addClass("on");
				}else{
					$(".left-week").find("a").removeClass("on");
				}
			}else{
				$(".widget-option").each(function(i){
					if(val==$(this).text()){
						if($(this).hasClass("on")){
							$(this).removeClass("on");
						}else{
							$(this).addClass("on");
						}
					}
				});
			}
		}

		//검색
		if(type!="addr"){
			initPage(true);
		}
	}

	//왼쪽 메뉴 클릭 처리
	function setTab($this,type,val){
		$("#advancedSearchWidget").removeAttr("hidden");
		if(type=="addr"){
			$($this).closest(".form-group").find("a").removeClass("on");
			$($this).addClass("on");
			changeSearchAddr(1,val,"");
		}else if(type=="eprice"){
			$($this).closest(".form-group").find("a").removeClass("on");
			$($this).addClass("on");

			$('[name=eprice]').each(function(idx) {
	            var data = $(this).val();
				if(val==data){
					$('[name=eprice]').eq(idx).attr("checked","checked");
					$('[name=eprice]').eq(idx).prop("checked","checked");
				}
	        });
		}else{
			if($($this).hasClass("on")){
				$($this).removeClass("on");
			}else{
				$($this).addClass("on");
			}

			if(type=="week" && val=="all"){
				if($($this).hasClass("on")){
					$("[name='week_yn']").prop("checked",true);
				}else{
					$("[name='week_yn']").prop("checked",false);
				}

				if($("[name='week_yn']").is(":checked")){
					$("[name='week[]']").prop("checked",true);
					$(".left-week").find("a").addClass("on");
				}else{
					$("[name='week[]']").prop("checked",false);
					$(".left-week").find("a").removeClass("on");
				}

			}else{
				$("[name='" + type + "[]" + "']").each(function(idx) {
		            var data = $(this).val();
					if(val==data){
						if($("[name='" + type + "[]" + "']").eq(idx).is(":checked")){
							$("[name='" + type + "[]" + "']").eq(idx).prop("checked",false);
						}else{
							$("[name='" + type + "[]" + "']").eq(idx).prop("checked",true);
						}
					}
		        });
			}
		}

		//검색
		initPage(true);
	}

	function changeSearchAddr(depth,addr1,addr2){
		if(depth==1){
			$('[name=searchArea1]').each(function(idx) {
	            var val = $(this).val();
				if(val==addr1){
					$("[name=searchArea1]").eq(idx).prop("checked",true);
				}
	        });
		}

		get_addrlist(depth,addr1,addr2);
	}

	//지역 선택 처리
	$(document).on("click","[name=searchArea1]",function(){
		changeTab(this,"addr",$(this).val()=="전체" ? "전체":$(this).val());
		changeSearchAddr(1,$(this).val(),"");
	});

	$(document).on("click","[name=searchArea2]",function(){
		changeSearchAddr(2,$(this).attr("data-addr1"),$(this).val());
	});

	$(document).on("click","[name=searchArea3]",function(){
		if($(this).val()==""){
			$("[name=searchArea3]").prop("checked",false);
			$("[name=searchArea3]").eq(0).prop("checked",true);
		}else{
			$("[name=searchArea3]").eq(0).prop("checked",false);
			if($("[name=searchArea3]:checked").length==0){
				$("[name=searchArea3]").eq(0).prop("checked",true);
			}
		}
		changeSearchAddr(3,$(this).attr("data-addr3"),$(this).val());
	});

	function setBoardParams(){
		var arr_category = "";

        for(var icnt=0;icnt < $("[name='category[]']:checked").length;icnt++){
            if($("[name='category[]']:checked").eq(icnt).val()!=""){
                if(arr_category==""){
                    arr_category = $("[name='category[]']:checked").eq(icnt).val();
                }else{
                    arr_category += "^" + $("[name='category[]']:checked").eq(icnt).val();
                }
            }
        }


		//검색어 설정
		search_array.text = $("[name='text']").val();
		search_array.addr1 = $("[name='searchArea1']:checked").val()=="전체" ? "":$("[name='searchArea1']:checked").val();
		search_array.addr2 = $("[name='searchArea2']:checked").val();
		search_array.addr3 = $("[name='searchArea3']:checked").val();
		search_array.category = arr_category;
		search_array.sprice = "";
		search_array.eprice = $("[name='eprice']:checked").val();
		search_array.order_by = $("[name='order_by']:checked").val();

		localStorage.setItem("trade_array",JSON.stringify(search_array));
	}

	//검색
	function getBoardList(is_top){
		//파라미터 설정
		setBoardParams();

		var params = {};
		params["page"] = list_page;
		params["text"] = search_array.text;
		params["addr1"] = search_array.addr1;
		params["addr2"] = search_array.addr2;
		params["addr3"] = search_array.addr3;

		params["category"] = search_array.category;
		params["sprice"] = search_array.sprice;
		params["eprice"] = search_array.eprice;
		params["order_by"] = search_array.order_by;

		comAjax("/trade_proc/search_web_list",params,function(ret){
			if(ret.code=="00"){
				$(list_id).attr("data-page",list_page);
				$("#total_count").html(ret.data.count);
				$("#page_cont").html(ret.data.pagenation.replace(/<li>/g, '').replace(/page-next/g, 'page-next btn'));
				showBoardList(ret.data.list,is_top);
			}else{

			}
		});
	}

	function initTopSearch(is_back){
		var top_serch_yn = localStorage.getItem("top_search_yn");
		if(top_serch_yn=="Y" || is_back==true){
			var top_search_array = "";
			if(is_back==true){
				var top_search_array = localStorage.getItem("resume_array");
			}else{
				localStorage.setItem("top_search_yn","");
				var top_search_array = localStorage.getItem("top_search_array");
			}

			var info = JSON.parse(top_search_array);
			var arr_category = info.category.split("^");
	        var arr_text = new Array();

			if(info.eprice!=""){
				for(var jcnt=0;jcnt < $("[name='eprice']").length;jcnt++){
					if($("[name='eprice']").eq(jcnt).val()==info.eprice){
						$("[name='eprice']").eq(jcnt).prop("checked",true);
						$("[name='eprice']").eq(jcnt).attr("checked",true);
						arr_text.push({type:"radio",val:info.eprice + "만원 이하"});
					}
				}
			}

			if(info.addr1!=""){
				for(var jcnt=0;jcnt < $("[name='searchArea1']").length;jcnt++){
					if($("[name='searchArea1']").eq(jcnt).val()==info.addr1){
						$("[name='searchArea1']").eq(jcnt).prop("checked",true);
						//$("[name='searchArea1']").eq(jcnt).click();
						arr_text.push({type:"radio",val:info.addr1});
					}
				}
				changeSearchAddr(1,info.addr1,"");
			}

			if(info.addr2!=""){
				for(var jcnt=0;jcnt < $("[name='searchArea2']").length;jcnt++){
					if($("[name='searchArea2']").eq(jcnt).val()==info.addr2){
						$("[name='searchArea2']").eq(jcnt).prop("checked",true);
						arr_text.push({type:"radio",val:info.addr2});
					}
				}
				changeSearchAddr(2,info.addr1,info.addr2);
			}

			for(var icnt=0;icnt < arr_category.length;icnt++){
				for(var jcnt=0;jcnt < $("[name='category[]']").length;jcnt++){
					if($("[name='category[]']").eq(jcnt).val()==arr_category[icnt]){
						$("[name='category[]']").eq(jcnt).prop("checked",true);
						arr_text.push({type:"check",val:$("[name='category[]']").eq(jcnt).val()});
					}
				}
			}

			if(arr_text.length  > 0){
				$("#advancedSearchWidget").removeAttr("hidden");
				for(icnt=0;icnt < arr_text.length;icnt++){
					$(".widget-option").each(function(i){
						if(arr_text[icnt].val==$(this).text()){
							if(arr_text[icnt].type=="radio"){
								$(this).closest(".form-group").find("a").removeClass("on");
							}
							$(this).addClass("on");
						}
					});
				}
			}

		}
	}

	function initPage(is_top){
		list_page = 1;
		getBoardList(is_top);
	}

	function goPage(page){
		list_page = page;
		getBoardList(true);
	}

	//데이터 보여주기
	function showBoardList(list,is_top){
		//모두 제거
		$(list_id).html("");

		//리스트 추가
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

    		$content = $content.replaceAll("{{td_idx}}",row.td_idx);
    		$content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");
			$content = $content.replaceAll("{{ad_addr}}",row.ad_addr);
			$content = $content.replaceAll("{{td_name}}",row.td_name);
			$content = $content.replaceAll("{{distance}}",row.distance + "km")

			$content = $content.replaceAll("{{td_img}}",row.td_img1);
			$content = $content.replaceAll("{{td_price}}",row.td_price.format());
			$content = $content.replaceAll("{{reg_dt}}",row.reg_dt);

    		//복사한 내용 다시 넣기
    		$clone.html($content);

    		$(list_id).append($clone.html());
		});

		if(is_top==true){
			var ajax_top = $(".search-result").position().top - 30;
			$( 'html, body' ).animate( { scrollTop : ajax_top }, 400 );
		}
	}

	function get_addrlist(depth,ad1,ad2){
		if(depth==3){
			initPage(true);
			return;
		}

		var params = {};
		params["depth"] = depth;
	    params["addr1"] = ad1;
	    params["addr2"] = ad2;

	    comAjax("/common_proc/get_addrlist",params,function(ret){
	        if(ret.code=="00"){
	        	var html = "";

	        	if(depth==1){
	        		var item ="";
		        	item +="<label class='form-checkbox'>";
	                item +="    <input type='radio' data-addr1='"+ad1+"' checked name='searchArea2' value=''>";
	                item +="    <span class='label'>전체</span>";
	                item +="</label>";
	                html +=item;
	            }else{
	            	if(ad2!=""){
	            		var item ="";
			        	item +="<label class='form-checkbox'>";
		                item +="    <input type='checkbox' data-addr1='"+ad1+"' data-addr2='"+ad2+"' checked name='searchArea3' value=''>";
		                item +="    <span class='box'>전체</span>";
		                item +="</label>";
		                html +=item;
	            	}
	        	}

	        	for(var icnt=0;icnt<ret.data.list.length;icnt++){
	        		if(depth==1){
		        		var ca_ad1 = ret.data.list[icnt].ca_ad1;
		        		var ca_ad2 = ret.data.list[icnt].ca_ad2;
		        		var ca_ad3 = ret.data.list[icnt].ca_ad3;

		        		var item ="";
			        	item +="<label class='form-checkbox'>";
		                item +="    <input type='radio' data-addr1='"+ca_ad1+"' name='searchArea2' value='"+ca_ad2+"'>";
		                item +="    <span class='label'>"+ca_ad2+"</span>";
		                item +="</label>";

		                html +=item;
	        		}else{
	        			var ca_ad1 = ret.data.list[icnt].ca_ad1;
		        		var ca_ad2 = ret.data.list[icnt].ca_ad2;
		        		var ca_ad3 = ret.data.list[icnt].ca_ad3;

		        		var item ="";
			        	item +="<label class='form-checkbox'>";
		                item +="    <input type='checkbox' data-addr1='"+ca_ad1+"' data-addr2='"+ca_ad2+"' name='searchArea3' value='"+ca_ad3+"'>";
		                item +="    <span class='box'>"+ca_ad3+"</span>";
		                item +="</label>";

		                html +=item;
	        		}
	        	}

	        	if(depth==1){
	        		$(".area-gugun").html(html);
	        		$("[name='searchArea2']").eq(0).prop("checked",true);
	        		$(".area-dong").attr("hidden","");

	        		if(html==""){
	        			$(".area-gugun").attr("hidden","");
	        		}
	        	}else{
	        		$(".area-dong").html(html);
	        		$(".area-dong").removeAttr("hidden");

	        		if(html==""){
	        			$(".area-dong").attr("hidden","");
	        		}
	        	}

	        	initPage(true);
	        }else{
	            commonAlert(ret.msg,function(){});
	            return;
	        }
	    });
	}
</script>
