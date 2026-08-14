<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>

<div class="page page-join member-type1" style="display:none;">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">개인회원 회원가입</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <div class="container">

            <h2 class="primary-title align-center">회원님의 <em>위치를 설정</em>해주세요!</h2>

            <div class="find-loc-form">
                <form id="frm" onsubmit="return false">
                    <input type="hidden" id="ad_zipcode" name="ad_zipcode" value="" />
					<input type="hidden" id="ad_jibun" name="ad_jibun" value="" />
					<input type="hidden" id="ad_doro" name="ad_doro" value="" />
					<input type="hidden" id="ad_detail" name="ad_detail" value="" />
					<input type="hidden" id="ad_addr" name="ad_addr" value="" />
					<input type="hidden" id="ad_gps_x" name="ad_gps_x" value="" />
					<input type="hidden" id="ad_gps_y" name="ad_gps_y" value="" />

                    <div class="form-row">
                        <label class="search-cont">
                            <span class="form-label">도로명주소</span>
                            <input type="text" id="txt_addr" name="txt_addr" placeholder="예시 : 주흥1길 12, 부산 센텀중앙로 97" autocomplete="off" aria-required="true" required class="form-text">
                            <a id="btnSearchAddr" class="icon" href="javascript:void(0);">
                            	<img src="/resource/shop/mobile/img/common/search.png" alt="주소 검색" />
                            </a>
                        </label>
                    </div>

                    <button type="button" id="btnGps" class="btn-primary btn-find-loc">현재 위치로 주소찾기</button>

                    <!-- 검색결과 -->
                    <div class="result-wrap">
                        <!-- 조회목록 -->
                        <ol class="post-list hidden">

                        </ol>
                        <!-- //조회목록 -->

                        <div class="post-tip tip">
                            <p class="title">주소입력TIP</p>
                            <p>아래의 조합으로 검색하시면 더 정확한 결과로 검색됩니다.</p>

                            <dl>
                                <dt>도로명+건물번호</dt>
                                <dd>예시 : 주흥1길 12, 부산 센텀중앙로 97</dd>
                                <dt>동/읍/면/리 + 번지</dt>
                                <dd>예시 : 반포동 742-16, 재송동 1208-2</dd>
                                <dt>건물명, 아파트명</dt>
                                <dd>예시 : 반포자이아파트, 센텀스카이비즈</dd>
                            </dl>

                            <p class="desc">2014년부터 도로명주소 전면 시행으로 인해 도로명주소가<br> 기본으로 보여집니다<br> 도로명 주소 확인 : https://www.juso.go.kr</p>
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div><!-- //page-content -->
</div>
<script>
//$(document).ready(function(){
window.addEventListener('load', function(){

	location.href="/member/join_mem_step3";

	$("#btnSearchAddr").click(function(){
		searchText();
	});

	$("#txt_addr").keyup(function(e){
		if(e.keyCode == 13){
		   	searchText();
		}
	})

	//위치 정보 검색 - 사용자가 버튼을 클릭했을 때만 실행
	$("#btnGps").click(function(){
		getLocation();
	});

	// 페이지 로드 시 자동으로 위치 정보를 요청하는 코드 제거
	// macOS와 iOS에서 위치 동의 팝업이 자동으로 뜨는 문제 해결
	// getLocation();

});

function searchText(){
	if($("#txt_addr").val()==""){
		commonAlert('검색하실 주소를 입력해주세요.',function(){
			$("#txt_addr").focus();
		});
		return;
	}
	searchApi($("#txt_addr").val());
}

function searchApi(keyword){
	getSearchApi($("#txt_addr").val(),"search",function(html){
		$(".post-list").removeClass("hidden");
		$(".post-list").html("");
		$(".post-list").append(html);
	})
}

// 검색 완료 선택 처리
function selectAddrInfo(zipcode,doro,jibun,addr,x,y){
	$("#ad_zipcode").val(zipcode);
	$("#ad_jibun").val(doro);
	$("#ad_doro").val(jibun);
	$("#ad_addr").val(addr);
	$("#ad_gps_x").val(x);
	$("#ad_gps_y").val(y);

	var params = $("#frm").serialize();
    comAjax("/member_proc/join_mem_step2",params,function(ret2){
        if(ret2.code=="00"){
            location.href="/member/join_mem_step3";
        }else{
            commonAlert(ret2.msg,function(){});
        }
    });
}

function getLocation() {
	showDaum("");
	//commonAlert('GPS 기능이 비활성화되었습니다. 주소를 입력하여 조회해 주세요.',function(){});

	// 위치 정보 요청과 무관하게 자동으로 페이지를 이동시키는 버그 수정
	// location.href="/member/join_mem_step3";

}
</script>
<?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/addr_search_api.php'); ?>
