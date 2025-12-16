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
                    <li class="on">
                        <a href="/home/search_recruit?text=<?=$text?>" class="tab"><span>채용정보</span></a>
                    </li>
                    <li>
                       <a href="/home/search_resume?text=<?=$text?>" class="tab"><span>인재정보</span></a>
                    </li>
                    <li>
                       <a href="/home/search_trade?text=<?=$text?>" class="tab"><span>중고거래</span></a>
                    </li>
                </ul>
            </div>
            <h5 class="search_text">검색결과</h5>
			<div class="search_title">
				<span class="tt">"<?=$text?>"</span>검색결과 총<span class="cnt" id="total_count"></span>입니다.
			</div>

            <!-- 검색결과 -->
            <h5 class="search_header" style="margin-top:30px;">채용정보</h5>
            <div class="result-wrap">
                <!-- 목록 -->
                <ol class="result-list" id="ajaxList_1" data-page="1" style="min-height:200px;"></ol>
                <!-- //목록 -->
                <div id="page_cont_1">

                </div>
            </div>

        </div>
    </main>
</div>


<template id="tpl_list_1">
	<li class="list-item item-hover click-item" data-link="/recruit/recruit_detail/{{rc_idx}}">
        <div class="item-info colgroup">
            <div class="thumb-wrap">
                <!-- 추천라벨 -->
                <p class="recomm-badge"><span>추천</span></p>
                <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" data-user_id="{{mm_id}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button>
                <!-- 썸네일 -->
                <div class="thumb">
                    <a href="/recruit/recruit_detail/{{rc_idx}}"><img src="{{rt_recruit_img}}" alt=""></a>
                </div>
            </div>
            <div class="content-wrap">
                <div class="inline-group">
                    <!-- 직종라벨 -->
                    <p class="category">{{rt_work_img}}</p>
                </div>
                <!-- 제목 -->
                <p class="title"><strong><a href="/recruit/recruit_detail/{{rc_idx}}">{{rc_name}}</a></strong></p>
                <!-- 추가조건 -->
                <div class="meta">
                    <!-- 근무시간 -->
                    <p class="type">{{rt_work_type}}</p>
                    <span>/</span>
                    <p>{{rc_nation_nm}}</p>
                    <span>/</span>
                    <p>{{rc_age_nm}}</p>
                </div>
            </div>
            <!-- 지역 -->
            <p class="area">{{ad_addr}}</p>
            <!-- 지불방식 -->
            <div class="pay">
                <p class="period">{{rt_price_type}}</p>
                <p class="amount"><em class="num">{{rt_price}}</em></p>
            </div>
            <!-- 목록선택 (제한20개) -->
            <div class="selection">
                <label class="form-checkbox recruit-checkbox lg">

                </label>
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

		location.href="/home/search_recruit?text=" + encodeURIComponent($("#search_total_text").val());
	}

	var text ="<?=$text?>";
	var total_cnt = 0;
	var list_id_1 = "#ajaxList_1";
	var list_page_1 = 1;

	var list_id_2 = "#ajaxList_2";
	var list_page_2 = 1;

	var list_id_3 = "#ajaxList_3";
	var list_page_3 = 1;

	getBoardList_1(false);

	function initPage_1(is_top){
		list_page_1 = 1;
		getBoardList_1(is_top);
	}

	function goPage_1(page){
		list_page_1 = page;
		getBoardList_1(true);
	}

	//검색
	function getBoardList_1(is_top){
		//파라미터 설정
		var params = {};
		params["page"] = list_page_1;
		params["pagesize"] = 10;
		params["text"] = text;
		params["addr1"] = "";
		params["addr2"] = "";
		params["addr3"] = "";
		params["skm"] = "";
		params["ekm"] = "";
		params["category"] = "";
		params["work_type"] = "";
		params["week"] = "";
		params["price_type"] = "";
		params["sex"] = "";
		params["sage"] = "";
		params["eage"] = "";
		params["order_by"] = "";

		comAjax("/recruit_proc/search_web_list",params,function(ret){
			if(ret.code=="00"){
				$(list_id_1).attr("data-page",list_page_1);
                if(ret.data.pagenation!=false){
                    $("#page_cont_1").html(ret.data.pagenation.replaceAll("goPage","goPage_1"));
                }else{
                    $("#page_cont_1").html("");
                }
				showBoardList_1(ret.data.list,is_top);

				total_cnt += parseInt(ret.data.count);
			}

			$("#total_count").html(total_cnt.format() + "건");

            if(ret.data.count==0){
                showNoData(list_id_1);
            }
		});
	}

	//데이터 보여주기
	function showBoardList_1(list,is_top){
		//모두 제거
		$(list_id_1).html("");

		//리스트 추가
		list.forEach(function(row){
			var $clone  = $("#tpl_list_1").clone();
    		var $content = $clone.html();
            var cate_type = "cate1";
            if(row.bd_cate=="업무"){
                cate_type = "cate1";
            }else if(row.bd_cate=="일상"){
                cate_type = "cate2";
            }else if(row.bd_cate=="질문"){
                cate_type = "cate3";
            }
    		$content = $content.replaceAll("{{rc_idx}}",row.rc_idx);
    		$content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");
			$content = $content.replaceAll("{{ad_addr}}",row.ad_addr);
			$content = $content.replaceAll("{{rc_name}}",row.rc_name);
			$content = $content.replaceAll("{{distance}}",row.distance + "km")
			$content = $content.replaceAll("{{rc_nation_nm}}",row.rc_nation_nm);
			$content = $content.replaceAll("{{rc_age_nm}}",row.rc_age_nm);

			$content = $content.replaceAll("{{rt_recruit_img}}",row.rt_recruit_img);
			$content = $content.replaceAll("{{rt_work_img}}",row.rt_work_img);

      $work_type = row.rt_work_type;
      $work_type = $work_type.replaceAll("주간","<font style='color:#3a70db;'>주간</font>");
      $work_type = $work_type.replaceAll("야간", "<font style='color:#F86048;'>야간</font>"); //빨강
      $work_type = $work_type.replaceAll("2교대", "<font style='color:#1B9D7D;'>2교대</font>"); //민트
      $work_type = $work_type.replaceAll("근무협의", "<font style='color:#333;'>근무협의</font>"); //검정
      $work_type = $work_type.replaceAll("시간협의", "<font style='color:#333;'>근무협의</font>"); //검정

      $content = $content.replaceAll("{{rt_work_type}}",$work_type);
      
			$content = $content.replaceAll("{{rt_price_type}}",row.rt_price_type);
			$content = $content.replaceAll("{{rt_price}}",row.rt_price);
			$content = $content.replaceAll("{{mm_id}}",row.mm_id);

    		//복사한 내용 다시 넣기
    		$clone.html($content);

    		$(list_id_1).append($clone.html());
		});

		if(is_top==true){
			var ajax_top = $(".search-result").position().top - 30;
			$( 'html, body' ).animate( { scrollTop : ajax_top }, 400 );
		}
	}
</script>
