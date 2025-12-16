<div class="dropdown-wrap">
	<a href="#topSearch" role="button" aria-expanded="false" aria-controls="topSearch" class="btn-top-search js-toggle-dropdown"><span class="sr-text">전체검색</span></a>
	<!-- 전체검색 -->
	<div id="topSearch" class="dropdown top-search">
        <div class="content-wrap">
            <div class="container">

                <!-- 검색어 입력 -->
                <div class="search-form">
                    <label>
                        <span class="sr-text">검색어 입력</span>
                        <input type="search" name="g_keyword" autocomplete="off" value="<?=input_get("text")?>" autofocus placeholder="검색어를 입력해 주세요" aria-required="true" >
                    </label>
                    <button type="button" id="btnTopSearch" class="btn-search"><span class="sr-text">검색하기</span></button>
                </div>
			</div>
		</div>
	</div>
</div>
<script>
	//검색 저장
	var top_search_array = {
		text : "",
		category : "",
		work_type : "",
		week : "",
		week_yn : "",
		price_type : "",
		sex : "",
		sage : "",
		eage : "",
		addr1 : "",
		addr2 : "",
		addr3 : "",
	};
	
	$("#btnTopSearch").click(function(){
		goSearch();
	});
	
	$("[name='g_keyword']").keyup(function(e){
		if (e.keyCode === 13) {
		    e.preventDefault();
		   	goSearch();
		 }
	});
	
	function goSearch(){
		if($("[name='g_keyword']").val()==""){
			commonAlert("검색어를 입력해주세요.",function(){
				$("[name='g_keyword']").focus();
			});
			return;
		}
		
		location.href="/home/search?text=" + encodeURIComponent($("[name='g_keyword']").val());
	}
	
	//검색샐정
	function setTopSearch(cate){
		//검색어 설정
		top_search_array.category = cate;
		localStorage.setItem("top_search_array",JSON.stringify(top_search_array));
		localStorage.setItem("top_search_yn","Y");
		location.href="/recruit/wlist";
	}
</script>
