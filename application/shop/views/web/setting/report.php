<div class="page page-settings">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">문의하기</h1>
        <a href="/setting/report_write" class="btn-action">문의하기</a>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="board-list">
            <ol class="list" id="ajaxList" data-page="1">

            </ol>
            <!-- 빈목록 -->
            <div id="ajaxEmpty" class="is-empty" style="display:none;">
                <p>등록된 문의하기 정보가 없습니다.</p>
            </div>
        </div>

    </div><!-- //page-content -->
</div>

<!-- 리스트 템플릿 -->
<template id="tpl_list">
	<li>
        <a href="#" class="angle-link">
            <span class="title">문의드립니다.</span>
            <span class="meta">
                <span class="date">2022.05.30</span>
            </span>
            <em class="status">답변완료</em>
        </a>
    </li>
</template>

<script>
	var list_tb_id = 'report';
	var list_id = "#ajaxList";
    var list_empty_id = "#ajaxEmpty";
	var list_page = 1;
	var list_loading = false;

	$(document).ready(function(){
		//데이터 캐싱처리
		var params = {
			cashing_id: "qna_cashing",	//캐싱키값
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
		showEmpty();
		//loadData(false);
	}

	//데이터 호출 처리
	function initLoadData() {
		getBoardList(list_tb_id);
	}



	function getBoardList(tb_id){
		var params = {};
		params["tb_id"] = tb_id;
		params["mm_id_yn"] = "Y";
		params["page"] = list_page;
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
    		$content.find(".title").html(row.bd_subject);
    		$content.find(".date").html(row.reg_dt.substring(0,10));
    		$content.find(".status").html(row.bd_aws_yn=="Y" ? "답변완료":"미응답");
    		if(row.bd_aws_yn=="Y"){
    			$content.find(".status").addClass("txt-color2");
    		}
    		$content.find(".angle-link").attr("href","/setting/report_view/" + row.bd_idx);

    		//복사한 내용 다시 넣기
    		$clone.html($content.prop('outerHTML'));

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

