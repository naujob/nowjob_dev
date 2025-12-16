<?php
	$banner_list = getBannerList("BN00302040");
	$banner = null;
	if(count($banner_list) > 0){
		$banner = $banner_list[0];
	}
?>
<div class="page page-recruit">
    <div class="header is-transparency">
        <h1 class="top-logo"><span class="sr-text">지금 뜨는, 좋은 일자리 NOW JOB</span></h1>
        <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
            <span class="sr-text">미확인 알림메세지</span>
            <!-- 미확인 알림개수 -->
            <span class="cnt"><?=$g_alarm_cnt?></span>
        </a>
    </div><!-- //header -->

    <div class="page-content">
         <?php if($banner !=null) : ?>
        <div class="top-visual">
            <div class="visual-content">
                <p><?=$banner["bn_text1"]?></p>
                <p class="headline"><?=$banner["bn_text2"]?></p>
                <a href="javascript:void(0);" id="btnRegist" class="btn">상품등록</a>
            </div>
            <div class="visual-img">
                <img src="<?=$banner["bg_img"]?>" alt="">
            </div>
        </div>
        <?php endif; ?>

        <!-- 상세 검색 옵션 선택시: has-option 클래스 추가 -->
        <div class="search-option has-option">
            <!-- 현재 위치 -->
            <div class="current-area">
                <div class="container">
                    <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel"><?=$addr_info["ad_addr"]?></a>
                </div>
            </div>
            <!-- 선택된 카테고리 -->
            <div class="categories">
                <div class="container" id="tab_list">

                </div>
            </div>
            <a href="#advancedSearch" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text">상세검색 열기</span></a>
        </div>

        <div class="section is-last">
            <div class="container">
                <h2 class="sr-text">거래목록</h2>

                <!-- 목록 -->
                <ol class="result-list" id="ajaxList">

                </ol>
                <!-- //목록 -->

				<div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
	                <p>등록된 정보가 없습니다.</p>
	            </div>
            </div>
        </div>

        <div class="list-action">
            <a href="/trade/index_map" class="btn-map"><span class="sr-text">지도보기</span></a>
        </div>

    </div><!-- //page-content -->

    <!-- bottom-nav -->
    <?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
    <!-- //bottom-nav -->
</div>

<!-- 알림목록 -->
<?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>
<!-- //알림목록 -->

<!-- 상세검색 패널 -->
<?php include_once (get_base_url() . '/application/shop/views/mobile/trade/index_search.php'); ?>
<!-- //상세검색 패널 -->

<!-- 리스트 템플릿 -->
<template id="tpl_list">
	<li class="list-item">
        <div class="item-info sale">
            <div class="thumb-wrap">
                <button type="button" data-bk_type="TRADE" data-bk_key="{{td_idx}}"  class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 해제</span></button>
                <div class="thumb">
                    <a href="/trade/trade_detail/{{td_idx}}"><img src="{{td_img}}" alt="" class=""></a>
                </div>
            </div>
            <div class="content-wrap">
                <p class="title"><strong><a href="/trade/trade_detail/{{td_idx}}">{{td_name}}</a></strong></p>
                <div class="meta">
                    <p class="area">{{ad_addr}}</p>
                </div>
                <div class="price-wrap">
                    <p class="amount"><em class="num">{{td_price}}</em> 원</p>
                    <p class="date">{{reg_dt}}</p>
                </div>
            </div>
        </div>
    </li>
</template>

<template id="tpl_category">
    <label class="form-toggle-btn2 sm {{active}} btn-category">
        <input type="checkbox" name="filter" value="{{category_name}}">
        <span class="cate">{{category_name}}</span>
    </label>
</template>

<script>
	$("#btnRegist").click(function(){
		location.href="/trade/trade_write";
	});
</script>

<script>
	var ad_addr = "<?=$addr_info["ad_addr"]?>";
	var list_id = "#ajaxList";
    var list_empty_id = "#ajaxEmpty";
	var list_page = 1;
	var list_loading = false;

	var search_array = {
		text : "",
		skm : "",
		ekm : "",
		category : "",
		sprice : "",
		eprice : "",
	};

	var tab_list = [];
	tab_list.push({df : "검색어",text : "",active : ""});		//0
	tab_list.push({df : "거리",text : "",active : ""});		//1
	tab_list.push({df : "카테고리",text : "",active : ""});	//2
	tab_list.push({df : "가격",text : "",active : ""});		//3

	$(document).ready(function(){
		//데이터 캐싱처리
		var params = {
			cashing_id: "trade_cashing",
			list_id: list_id,
			category_list_id : "#tab_list",
			scroll_obj : $(".page-recruit"),
			fn_cashing: initCashingData,
			fn_load: initLoadData,
		};
		$.CashingAjax(params);

        //카테고리 설정.
        initCategory();
	});

    $(document).on("click",".btn-category",function(){
        $("#advancedSearch").addClass("on");
    });


	//캐싱처리 추가 작업
	function initCashingData() {
		list_page = $(list_id).attr("data-page");
		showEmpty();
	}

	//데이터 호출 처리
	function initLoadData() {
		getBoardList("");
	}

    function initCategory(){
        var arr_category = "";
        var arr_work_type = "";
        var arr_price_type = "";
        var arr_week = "";
        var arr_sex = "";

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
		search_array.skm = $("[name='skm']").val();
		search_array.ekm = $("[name='ekm']").val();
		search_array.category = arr_category;
		search_array.sprice = $("[name='sprice']").val();
		search_array.eprice = $("[name='eprice']").val();

		//초기화
		for(var icnt=0;icnt < tab_list.length;icnt++){
			tab_list[icnt].text = "";
			tab_list[icnt].active = "";
		}

        //검색어
		if(search_array.text!=""){
            tab_list[0].text = search_array.text;
            tab_list[0].active = "on";
		}

		//거리
		if(ad_addr !="지역 선택 없음"){
			if(search_array.ekm!=""){
				tab_list[1].text = "~" + search_array.ekm + "km";
				tab_list[1].active = "on";
			}
		}

        //카테고리
        if(search_array.category!=""){
            tab_list[2].text = search_array.category.replaceAll("^",",");
            tab_list[2].active = "on";
		}

        //나이
		if(search_array.sprice!="0" || search_array.eprice!="0"){
            if(search_array.sprice!="0" && search_array.eprice=="0"){
                tab_list[3].text = search_array.sprice + "만원 ~";
                tab_list[3].active = "on";
            }else if(search_array.sprice=="0" && search_array.eprice!="0"){
                tab_list[3].text = " ~" + search_array.eprice + "만원";
                tab_list[3].active = "on";
            }else if(search_array.sprice!="0" && search_array.eprice!="0"){
                tab_list[3].text =search_array.sprice + "만원" + " ~ " + search_array.eprice + "만원";
                tab_list[3].active = "on";
            }
		}
    }

	function getBoardList(save_yn){
		var params = {};
		params["page"] = list_page;
		params["text"] = search_array.text;
		params["skm"] = search_array.skm;
		params["ekm"] = search_array.ekm;
		params["category"] = search_array.category;
		params["sprice"] = search_array.sprice;
		params["eprice"] = search_array.eprice;
        params["save_yn"] = save_yn;

		//탭 UI정리
		initTabUi();

		comAjax("/trade_proc/search_list",params,function(ret){
			if(ret.code=="00"){
				list_page++;
				$(list_id).attr("data-page",list_page);
				showBoardList(ret.data.result_list);
			}else{

			}
		});
	}

	function initTabUi(){

		//카테고리 구성
        $("#tab_list").html("");
        for(var icnt=0;icnt < tab_list.length;icnt++){
            var $clone  = $("#tpl_category").clone();
            var $content = $clone.html();

            $content = $content.replaceAll("{{active}}",tab_list[icnt].active);

            if(tab_list[icnt].active =="on"){
                $content = $content.replaceAll("{{category_name}}",tab_list[icnt].text);
            }else{
                $content = $content.replaceAll("{{category_name}}",tab_list[icnt].df);
            }
            $("#tab_list").append($content);
        }
	}

	//데이터 보여주기
	function showBoardList(list){
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

			$content = $content.replaceAll("{{td_img}}",row.td_img);
			$content = $content.replaceAll("{{td_price}}",row.td_price.format());
			$content = $content.replaceAll("{{reg_dt}}",row.reg_dt);


    		//복사한 내용 다시 넣기
    		$clone.html($content);

    		$(list_id).append($clone.html());
		});

       	showEmpty();
	}

	function showEmpty(){
		//리스트 없으면 숨김처리
        if($(list_id).find("li").length == 0){
            $(list_empty_id).show();
            $(list_id).hide();
        }else{
            $(list_id).show();
            $(list_empty_id).hide();
        }
	}

	// 페이지로딩
	$(window).scroll(function(){
		var $window = $(this);
		var scrollTop = $window.scrollTop() + $window.height();
		var windowHeight = $window.height();
		var documentHeight = $(list_id).height();

		if(list_loading==false){
			if(scrollTop+400>=documentHeight)
		    {
		    	list_loading = true;

		        //0.3초 후부터 재로딩 가능함.
		        setTimeout(function(){
		        	list_loading = false;
		        },300);

		        //리스트 로드
		        getBoardList("");
		    }
		}
	});

</script>

<script>
// 검색 완료 선택 처리
function selectAddrInfo(zipcode,doro,jibun,addr,x,y){
    var params = {};
    params["ad_zipcode"] = zipcode;
    params["ad_jibun"] = jibun;
    params["ad_doro"] = doro;
    params["ad_addr"] = addr;
    params["ad_gps_x"] = x;
    params["ad_gps_y"] = y;

    comAjax("/common_proc/set_addr_info",params,function(ret){
        if(ret.code=="00"){
            location.reload();
        }else{
            commonAlert(ret.msg,function(){});
            return;
        }
    });
}
</script>
<?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>
