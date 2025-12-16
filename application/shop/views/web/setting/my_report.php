<div class="page page-settings">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">나의 신고내역</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        
        <div class="board-list">
            <ol class="list" id="ajaxList">
                
            </ol>
            <!-- 빈목록 -->
            <div id="ajaxEmpty" class="is-empty" style="display:none;">
                <p>등록된 공지사항 정보가 없습니다.</p>
            </div>
        </div>

    </div><!-- //page-content -->
</div>

<!-- 리스트 템플릿 -->
<template id="tpl_list">
    <li>
        <a href="#" class="angle-link link">
            <span class="title bd_subject">신고하는 게시물 제목입니다.</span>
            <span class="meta">
                <span class="date">2022.05.30 16:20:20</span>
            </span>
        </a>
    </li>
</template>

<script>
	var list_tb_id = 'my_report';
	var list_id = "#ajaxList";
    var list_empty_id = "#ajaxEmpty";
	var list_page = 1;
	var list_loading = false;

	$(document).ready(function(){
		//데이터 캐싱처리
		var params = {
			cashing_id: "my_report_cashing",
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
		params["page"] = list_page;
		comAjax("/setting_proc/my_report_list",params,function(ret){
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
    		
    		$content.find(".bd_subject").html(row.br_desc.substring(0,50));
    		$content.find(".date").html(row.reg_dt.substring(0,10));
    		$content.find(".link").attr("href","/setting/my_report_view/" + row.br_idx);

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
			if(scrollTop+200>=documentHeight)
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
