<div class="page page-settings">    
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이벤트</h1>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="tab-container">
            <ul class="tab-list">
            	
                <li class="<?=$date_status=="ing" ? "on":"" ?>"><a href="/setting/event?date_status=ing" class="tab">진행중 이벤트</a></li>
                <li class="<?=$date_status=="end" ? "on":"" ?>"><a href="/setting/event?date_status=end" class="tab">종료 이벤트</a></li>
            </ul>
        </div>

        <div class="tab-panel">

            <div class="event-list">
                <ol class="list" id="ajaxList">
                    
                </ol>
                <!-- 빈목록 -->
	            <div id="ajaxEmpty" class="is-empty" style="display:none;">
	                <p>등록된 공지사항 정보가 없습니다.</p>
	            </div>
            </div>

        </div>

    </div><!-- //page-content -->
</div>

<input type="hidden" name="date_status" value="<?=$date_status?>" />

<!-- 리스트 템플릿 -->
<template id="tpl_list">
	<li class="list-item">
        <div class="thumb"><a class="link" href="#"><img class="bd_list_img1" src="_temp/event_banner1.png" alt=""></a></div>
        <p class="title"><strong><a class="link bd_subject" href="#">나에게 딱 맞는 구인구직</a></strong></p>
        <p class="summary bd_subtext">나우잡 이벤트 내용입니다. 이벤트 내용입니다.</p>
        <p class="date">2022.05.30~2022.06.30</p>
    </li>
</template>

<script>
	var list_tb_id = 'event';
	var list_id = "#ajaxList";
    var list_empty_id = "#ajaxEmpty";
	var list_page = 1;
	var list_loading = false;

	$(document).ready(function(){
		//데이터 캐싱처리
		var params = {
			cashing_id: "event_cashing",	//캐싱키값
			list_id: list_id,
			scroll_obj : $(".page-settings"),
			fn_cashing: initCashingData,
			fn_load: initLoadData,
		};
		$.CashingAjax(params);
	});


	//캐싱처리 추가 작업
	function initCashingData() {
		list_page = $(list_id).attr("data-page");
		//loadData(false);
	}

	//데이터 호출 처리
	function initLoadData() {
		getBoardList(list_tb_id);
	}



	function getBoardList(tb_id){
		var params = {};
		params["tb_id"] = tb_id;
		params["page"] = list_page;
		params["date_status"] = $("[name=date_status]").val();
		comAjax("/setting_proc/board_list",params,function(ret){
			if(ret.code=="00"){
				list_page++;
				$(list_id).attr("data-page",list_page);
				showBoardList(ret.data.result_list);
			}else{

			}
		});
	}

	//데이터 보여주기
	function showBoardList(list){
		list.forEach(function(row){
			var $clone  = $("#tpl_list").clone();
    		var $content = $($clone.html());
    		
    		$content.find(".bd_subject").html(row.bd_subject);
    		$content.find(".bd_subtext").html(row.bd_subtext);
    		$content.find(".bd_list_img1").attr("src",row.bd_list_img1);
    		$content.find(".date").html(row.reg_dt.substring(0,10));
    		$content.find(".link").attr("href","/setting/event_view/" + row.bd_idx + "?date_status=" + $("[name=date_status]").val());

    		//복사한 내용 다시 넣기
    		$clone.html($content.prop('outerHTML'));

    		$(list_id).append($clone.html());
		});

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
	//$(".page-settings").scroll(function(){
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
		        },150);

		        //리스트 로드
		        getBoardList(list_tb_id);
		    }
		}
	});
</script>
