<!-- 헤더 검색창: 항상 표시 -->
<div class="header-search-wrap">
	<form class="header-search-form" onsubmit="goSearch(); return false;">
		<label>
			<span class="sr-text">검색어 입력</span>
			<input type="search" name="g_keyword" autocomplete="off" value="" placeholder="내 주변 지금, 좋은 일자리" aria-required="true" >
		</label>
		<button type="submit" class="btn-search"><span class="sr-text">검색하기</span></button>
	</form>
</div>
<script>
	// 검색 저장
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

	// 헤더 검색 폼 제출
	$(".header-search-form").on('submit', function(e){
		e.preventDefault();
		goSearch();
	});

	// 검색창 Enter 키
	$("[name='g_keyword']").keyup(function(e){
		if (e.keyCode === 13) {
		    e.preventDefault();
		   	goSearch();
		 }
	});

	// 검색 실행
	function goSearch(){
		if($("[name='g_keyword']").val()==""){
			commonAlert("검색어를 입력해주세요.",function(){
				$("[name='g_keyword']").focus();
			});
			return;
		}

		location.href="/home/search?text=" + encodeURIComponent($("[name='g_keyword']").val());
	}

	// 검색 옵션 저장
	function setTopSearch(cate){
		// 검색어 설정
		top_search_array.category = cate;
		localStorage.setItem("top_search_array",JSON.stringify(top_search_array));
		localStorage.setItem("top_search_yn","Y");
		location.href="/recruit/wlist";
	}
</script>
