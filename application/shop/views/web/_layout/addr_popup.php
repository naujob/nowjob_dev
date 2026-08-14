<!-- 현재위치 변경 레이어팝업 -->
<div id="changeArea" class="layer-panel card-type" data-target="" tabindex="-1" style="position:fixed;">
    <div class="panel-container" style="max-width:500px;margin:0 auto;top:50%;left:20%;transform:translate(-50%, -50%);bottom:auto;">
        <div class="panel-header">
            <h2 class="sr-text">현재 위치 변경</h2>
            <button type="button" aria-controls="changeArea" class="btn-close-popup btnCloseArea"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <div class="page-join">
                    <h2 class="primary-title align-center"><em>위치를 설정</em>해주세요!</h2>
                    <div class="find-loc-form">
                        <form id="frm" onsubmit="return false" autocomplete="off">
                            <div class="form-row">
                                <label class="search-cont">
                                    <span class="form-label">도로명주소</span>
                                    <div class="search-input">
                                        <input type="text" id="txt_addr_popup" name="txt_addr" placeholder="예시 : 주흥1길 12, 부산 센텀중앙로 97" autocomplete="off" aria-required="true" required class="form-text">
                                        <a id="btnSearchAddr" class="icon" href="javascript:void(0);">
                                            <img src="/resource/shop/mobile/img/common/search.png" alt="주소 검색" />
                                        </a>
                                    </div>
                                </label>
                            </div>

                            <!-- 현재 위치로 주소찾기 기능 사용 안함 -->
                            <!-- <button type="button" id="btnGps" class="btn-primary btn-find-loc">현재 위치로 주소찾기</button> -->

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

            </div>
        </div>
    </div>
</div>
<!-- //현재위치 변경 레이어팝업 -->
<script>
    $(document).ready(function() {
        $(".btnCloseArea").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            // main.js의 closePanel 함수 사용 (hidden 속성 및 body 스크롤 처리)
            var panel = document.getElementById("changeArea");
            if (panel && typeof window.closePanel === 'function') {
                window.closePanel(panel);
            } else {
                // fallback: 직접 처리
                $("#changeArea").removeClass("on");
                $("#changeArea")[0].hidden = true;
                document.body.style.overflow = '';
            }
        });

        $("#btnSearchAddr").click(function() {
            searchText();
        });

        $("#txt_addr_popup").keyup(function(e) {
            if (e.keyCode == 13) {
                searchText();
            }
            return;
        });

        //위치 정보 검색 - 사용 안함
        // $("#btnGps").click(function(){
        // 	getLocation();
        // });
    });

    function searchText() {
        if ($("#txt_addr_popup").val() == "") {
            commonAlert('검색하실 주소를 입력해주세요.', function() {
                $("#txt_addr_popup").focus();
            });
            return;
        }
        searchApi($("#txt_addr_popup").val());
    }

    function searchApi(keyword) {
        getSearchApi($("#txt_addr_popup").val(), "search", 0, 0, function(html) {
            $(".post-list").html("");
            $(".post-list").append(html);

            if (html == "") {
                $(".post-list").addClass("hidden");
                $(".post-tip").removeClass("hidden");
            } else {
                $(".post-tip").addClass("hidden");
                $(".post-list").removeClass("hidden");
            }
        })
    }

    // GPS 위치 정보 검색 함수 - 사용 안함
    // function getLocation() {
    // 	if (navigator.geolocation) {
    // 		navigator.geolocation.getCurrentPosition(function(position) {
    // 			comAjax("/common_proc/get_gps_address",{x:position.coords.longitude,y : position.coords.latitude},function(ret){
    // 				if(ret.code=="00"){

    //           var parts = ret.data.split("^");
    //           var frontPart = "";
    //           var oldAddress = "";
    //           var loadAddress = "";
    //           var zipCode = "";
    //           var xpos = "";
    //           var ypos = "";

    //           frontPart = parts[0];
    //           if (parts.length > 1) { oldAddress = parts[1]; }
    //           if (parts.length > 2) { loadAddress = parts[2]; }
    //           if (parts.length > 3) { zipCode = parts[3]; }
    //           if (parts.length > 4) { xpos = position.coords.longitude; }
    //           if (parts.length > 5) { ypos = position.coords.latitude; }
    //           if (oldAddress.startsWith("경기")) { oldAddress = "경기도" + oldAddress.slice(2); }
    //           if (oldAddress.startsWith("서울")) { oldAddress = "서울특별시" + oldAddress.slice(2); }

    //           var gpsAddress = '<li class="post-item btnItem" style="cursor:pointer;" ';
    //           gpsAddress += 'data-doro="'+loadAddress+'" ';
    //           gpsAddress += 'data-jibun="'+oldAddress+'" ';
    //           gpsAddress += 'data-zipcode="'+zipCode+'" ';
    //           gpsAddress += 'data-xpos="'+xpos+'" ';
    //           gpsAddress += 'data-ypos="'+ypos+'" ';
    //           gpsAddress += 'data-addrshort="'+oldAddress+'" ';
    //           gpsAddress += 'data-admcd="" data-rnmgtsn="" data-udrtyn="0" data-buldmnnm="" data-buldslno="">';
    //           gpsAddress += '<dl class="post-info"><dt class="sr-text">기초구역번호(새 우편번호)</dt>';
    //           if(zipCode!='') gpsAddress += '<dd class="post-code"><button type="button" class="btn">'+zipCode+'</button></dd>';
    //           gpsAddress += '<dt class="sr-text">상세주소</dt><dd><dl class="address">';
    //           if(loadAddress!='') gpsAddress += '<dt>도로명</dt><dd><button type="button" class="btn gps_search">'+loadAddress+'</button></dd>';
    //           gpsAddress += '<dt style="width: 64px; margin-right: 10px;">지번</dt><dd><button type="button" class="btn">'+oldAddress+'</button></dd></dl></dd></dl></li>';

    //           //ret.data
    // 					getSearchApi(frontPart,"gps",position.coords.longitude,position.coords.latitude,function(html){
    // 						$(".post-list").removeClass("hidden");
    // 						$(".post-list").html("");
    //             $(".post-list").append(gpsAddress);
    // 						$(".post-list").append(html);
    // 						if(html==""){
    // 							commonAlert('주소검색이 되지 않는 지역입니다.직접 검색해주세요.',function(){});
    // 						}
    // 					})
    // 				}else{
    // 					commonAlert('주소검색이 되지 않는 지역입니다.직접 검색해주세요.',function(){});
    // 				}
    // 			});
    // 		}, function(error) {
    // 		  	if(error.message=="User denied Geolocation"){
    // 				commonAlert('위치사용을 허용 또는  주소를 입력하여 조회 해주세요.',function(){});
    //       		}
    //     	}, {
    // 	      enableHighAccuracy: true,
    //     	  timeout: 10000,
    // 	      maximumAge: 0,
    // 	      timeout: Infinity});
    //   	} else {

    // 		//alert('GPS를 지원하지 않습니다');
    //         commonAlert('GPS를 지원하지 않습니다.  주소를 입력하여 조회 해주세요.',function(){});
    //   	}
    // }
</script>
<?php include_once(get_base_url() . '/application/shop/views/web/_layout/addr_search_api.php'); ?>