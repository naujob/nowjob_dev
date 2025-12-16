<div id="content" class="page page-event page-search">
    <main class="page-content">
        <div class="container">
            <div class="search-top-cont">
            	<div class="search-round">
	            	<div class="search-input">
	            		<input type="text" id="search_total_text" value="<?=$text?>" />
	            	</div>
	            	<a id="btnTotalSearch" class="search-btn" href="javascript:void(0);"><img class="icon" src="/resource/shop/web/img/search-icon.png" />검색</a>
            	</div>
            	<div class="keyword">
            		<img src="/resource/shop/web/img/fav_keyword.png" />
            		<?php foreach($tag_list as $tag) : ?>
                    	<a href="/home/search?text=<?=$tag?>"><?=$tag?></a>
                    <?php endforeach;?>
            	</div>
            </div>

            <div class="tabs-wrap tabs">
                <ul class="tabs">
                    <!-- 활성화 항목 : on 클래스 추가 -->
                    <li>
                        <a href="/home/search?text=<?=$text?>" class="tab"><span>통합검색</span></a>
                    </li>
                    <li>
                        <a href="/home/search_recruit?text=<?=$text?>" class="tab"><span>채용정보</span></a>
                    </li>
                    <li>
                       <a href="/home/search_resume?text=<?=$text?>" class="tab"><span>인재정보</span></a>
                    </li>
                    <li class="on">
                       <a href="/home/search_trade?text=<?=$text?>" class="tab"><span>중고거래</span></a>
                    </li>
                </ul>
            </div>
            <h5 class="search_text">검색결과</h5>
			<div class="search_title">
				<span class="tt">"<?=$text?>"</span>검색결과 총<span class="cnt" id="total_count"></span>입니다.
			</div>

            <h5 class="search_header">중고거래</h5>
            <div class="result-wrap">
                <!-- 목록 -->
                <ol class="result-list" id="ajaxList_3" data-page="1" style="min-height:200px;"></ol>
                <!-- //목록 -->
                <div id="page_cont_3">

                </div>
            </div>
        </div>
    </main>
</div>


<!-- 리스트 템플릿 -->
<template id="tpl_list_3">
	<li class="list-item item-hover click-item" data-link="/trade/trade_detail/{{td_idx}}">
	    <div class="item-info colgroup">
	        <div class="thumb-wrap">
	            <!-- 추천라벨 -->
	            <p class="recomm-badge"><span>추천</span></p>
	            <button type="button" data-bk_type="TRADE" data-bk_key="{{td_idx}}"  class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 해제</span></button>
                <div class="thumb">
                    <a href="/trade/trade_detail/{{td_idx}}"><img src="{{td_img}}" alt="" class=""></a>
                </div>
	        </div>
	        <div class="content-wrap">
	            <!-- 제목 -->
	            <p class="title"><strong><a href="/trade/trade_detail/{{td_idx}}">{{td_name}}</a></strong></p>
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

<script>
	$("#btnTotalSearch").click(function(){
		goSearchTotal();
	});

	$("#search_total_text").keyup(function(e){
		if (e.keyCode === 13) {
		    e.preventDefault();
		   	goSearchTotal();
		 }
	});

	function goSearchTotal(){
		if($("#search_total_text").val()==""){
			commonAlert("검색어를 입력해주세요.",function(){
				$("#search_total_text").focus();
			});
			return;
		}

		location.href="/home/search_trade?text=" + encodeURIComponent($("#search_total_text").val());
	}

	var text ="<?=$text?>";
	var total_cnt = 0;
	var list_id_1 = "#ajaxList_1";
	var list_page_1 = 1;

	var list_id_2 = "#ajaxList_2";
	var list_page_2 = 1;

	var list_id_3 = "#ajaxList_3";
	var list_page_3 = 1;

	getBoardList_3(false);

	function initPage_3(is_top){
		list_page_3 = 1;
		getBoardList_3(is_top);
	}

	function goPage_3(page){
		list_page_3 = page;
		getBoardList_3(true);
	}

	//검색
	function getBoardList_3(is_top){
		//파라미터 설정
		var params = {};
		params["page"] = list_page_3;
		params["pagesize"] = 10;
		params["text"] = text;
		params["addr1"] = "";
		params["addr2"] = "";
		params["addr3"] = "";
		params["category"] = "";
		params["sprice"] = "";
		params["eprice"] = "";
		params["order_by"] = "";
		comAjax("/trade_proc/search_web_list",params,function(ret){
			if(ret.code=="00"){
				$(list_id_3).attr("data-page",list_page_3);
                if(ret.data.pagenation!=false){
                    $("#page_cont_3").html(ret.data.pagenation.replaceAll("goPage","goPage_3"));
                }else{
                    $("#page_cont_3").html("");
                }
				showBoardList_3(ret.data.list,is_top);

				total_cnt += parseInt(ret.data.count);
			}

			$("#total_count").html(total_cnt.format() + "건");

            if(ret.data.count==0){
                showNoData(list_id_3);
            }
		});
	}

	//데이터 보여주기
	function showBoardList_3(list,is_top){
		//모두 제거
		$(list_id_3).html("");

		//리스트 추가
		list.forEach(function(row){
			var $clone  = $("#tpl_list_3").clone();
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

    		$(list_id_3).append($clone.html());
		});

		if(is_top==true){
			var ajax_top = $(".search-result").position().top - 30;
			$( 'html, body' ).animate( { scrollTop : ajax_top }, 400 );
		}
	}

</script>
