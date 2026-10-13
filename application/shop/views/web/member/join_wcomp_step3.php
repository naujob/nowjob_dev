<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<div id="content" class="page page-join page-member-type1">
    <main class="page-content">

        <div class="container">
            <h2 class="primary-title">회원가입</h2>

            <div class="join-step2">

                <h3 class="first-title" style="display:none;">위치설정</h3>
                <div class="section" style="display:none;">
                    <div class="align-center">
                        <p class="secondary-title">회원님의 <em>위치를 설정</em>해주세요!</p>
                    </div>
                    <div class="find-loc-form">
                        <form id="frm" onsubmit="return false">
			                    <input type="hidden" id="mm_id" name="mm_id" value="<?php echo isset($_POST['mm_id']) ? htmlspecialchars($_POST['mm_id']) : ''; ?>" />
			                    <input type="hidden" id="mm_pwd" name="mm_pwd" value="<?php echo isset($_POST['mm_pwd']) ? htmlspecialchars($_POST['mm_pwd']) : ''; ?>" />
		                    <input type="hidden" id="ad_zipcode" name="ad_zipcode" value="" />
							<input type="hidden" id="ad_jibun" name="ad_jibun" value="" />
							<input type="hidden" id="ad_doro" name="ad_doro" value="" />
							<input type="hidden" id="ad_detail" name="ad_detail" value="" />
							<input type="hidden" id="ad_addr" name="ad_addr" value="" />
							<input type="hidden" id="ad_gps_x" name="ad_gps_x" value="" />
							<input type="hidden" id="ad_gps_y" name="ad_gps_y" value="" />

                            <div class="form-row">
                                <label>
                                    <span class="form-label">도로명주소</span>
                                    <input type="text" id="txt_addr" name="txt_addr"  placeholder="예시 : 주흥1길 12, 부산 센텀중앙로 97" autocomplete="off" aria-required="true" required class="form-text">
                                </label>
                            </div>

                            <button type="button" id="btnGps" class="btn-primary btn-find-loc">현재 위치로 주소찾기</button>

                            <!-- 검색결과 -->
                            <div class="result-wrap" style="max-height:300px;overflow:auto;">
                                <!-- 조회목록 -->
                                <ol class="post-list">
                                    <li class="post-item">
                                        <dl class="post-info">
                                       		현재 위치로 주소찾기및 주소를 검색해주세요.
                                        </dl>
                                    </li>
                                </ol>
                                <!-- //조회목록 -->
                            </div>
                            <!-- //검색결과 -->

                        </form>
                    </div>
                </div>

                <h3 class="first-title">이용약관동의</h3>
                <div class="section">
                    <div class="terms-list">
                        <ul class="list">
                            <li>
                                <label class="form-checkbox">
                                    <input type="checkbox" id="agree1" name="agreement" aria-required="true" required value="1">
                                    <span class="label-red"></span>
                                    <span class="sr-text">동의</span>
                                </label>
                                <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="angle-link js-open-Popup" data-idx="1"><span>서비스 이용약관<em>(필수)</em></span></a>
                                <!-- 약관내용 -->
                                <template id="terms1Temp">
                                    <div class="terms-wrap">
                                        <p class="terms-title">서비스 이용약관(필수)</p>
                                        <?=$policy1["policy2_pc"]?>
                                    </div>
                                </template>
                            </li>
                            <li>
                                <label class="form-checkbox">
                                    <input type="checkbox" id="agree2" name="agreement" aria-required="true" required value="2">
                                    <span class="label-red"></span>
                                    <span class="sr-text">동의</span>
                                </label>
                                <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="angle-link js-open-Popup" data-idx="2"><span>개인정보 처리방침<em>(필수)</em></span></a>
                                <!-- 약관내용 -->
                                <template id="terms2Temp">
                                    <div class="terms-wrap">
                                        <p class="terms-title">개인정보 처리방침(필수)</p>
                                        <?=$policy2["privacy_pc"]?>
                                    </div>
                                </template>
                            </li>
                            <li>
                                <label class="form-checkbox">
                                    <input type="checkbox" id="agree3" name="agreement" aria-required="true" required value="3">
                                    <span class="label-red"></span>
                                    <span class="sr-text">동의</span>
                                </label>
                                <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="angle-link js-open-Popup" data-idx="3"><span>위치 서비스 이용약관<em>(필수)</em></span></a>
                                <!-- 약관내용 -->
                                <template id="terms3Temp">
                                    <div class="terms-wrap">
                                        <p class="terms-title">위치 서비스 이용약관<em>(필수)</em></p>
                                        <?=$policy3["location_pc"]?>
                                    </div>
                                </template>
                            </li>
                            <li>
                                <label class="form-checkbox">
                                    <input type="checkbox" id="agree4" name="agreement" value="4">
                                    <span class="label-red"></span>
                                    <span class="sr-text">동의</span>
                                </label>
                                <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="angle-link js-open-Popup" data-idx="4"><span>개인정보 수집 및 이용 동의(선택)</span></a>
                                <!-- 약관내용 -->
                                <template id="terms4Temp">
                                    <div class="terms-wrap">
                                        <p class="terms-title">개인정보 수집 및 이용 동의(선택)</p>
                                        <?=$policy4["privacy3_pc"]?>
                                    </div>
                                </template>
                            </li>
                            <li>
                                <label class="form-checkbox">
                                    <input type="checkbox" id="agree5" name="agreement" value="5">
                                    <span class="label-red"></span>
                                    <span class="sr-text">동의</span>
                                </label>
                                <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="angle-link js-open-Popup" data-idx="5">
                                    <span>광고성 정보 이메일/SMS수신 동의(선택)</span>
                                    <small>(나우잡 소식 및 광고메일, 휴대폰 알림)</small>
                                </a>
                                <!-- 약관내용 -->
                                <template id="terms5Temp">
                                    <div class="terms-wrap">
                                        <p class="terms-title">광고성 정보 이메일/SMS수신 동의(선택)</p>
                                        <?=$policy5["adv_pc"]?>
                                    </div>
                                </template>
                            </li>
                        </ul>
                    </div>
                    <label class="form-checkbox">
                        <input type="checkbox" name="AgreementAll" value="all" class="js-check-all">
                        <span class="label-red" style="font-size: 16px; font-weight: 600; color: #000;">모두 동의하고 시작</span>
                    </label>

                    <script>
                        (function () {
                            var agreements = document.querySelectorAll('input[name="agreement"]');
                            var allCheck = document.querySelector('.js-check-all');
                            var total = 0;

                            allCheck.addEventListener('change', function () {
                                if (!agreements.length) return;

                                agreements.forEach(function (input) {
                                    input.checked = allCheck.checked;
                                    total = allCheck.checked ? agreements.length : 0;

                                    if(allCheck.checked){
                                      $('#btnNext').css('background-color', '#57CCAF'); // 버튼 활성화 색상 변경
                                    } else {
                                      $('#btnNext').css('background-color', '#ccc'); // 버튼 비활성화 색상 변경
                                    }

                                });
                            });

                            agreements.forEach(function (input) {
                                if(input.checked) total += 1;

                                input.addEventListener('change', function () {
                                    if(this.checked) {
                                        total += 1;
                                    } else {
                                        total -= 1;
                                    }

                                    if (total == agreements.length) {
                                        allCheck.checked = true;
                                    } else {
                                        allCheck.checked = false;
                                    }

                                    //check
                                    if($('#agree1').prop('checked') &&
                                       $('#agree2').prop('checked') &&
                                       $('#agree3').prop('checked')){
                                         $('#btnNext').css('background-color', '#57CCAF'); // 버튼 활성화 색상 변경
                                    }else{
                                         $('#btnNext').css('background-color', '#ccc'); // 버튼 비활성화 색상 변경
                                    }

                                });
                            });

                            window.addEventListener('DOMContentLoaded', function () {
                                document.querySelectorAll('.js-open-Popup').forEach(function (btn) {
                                    btn.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        var id = this.getAttribute('aria-controls');
                                        var title = this.textContent;
                                        var content = this.parentNode.querySelector('template').innerHTML;
                                        var agreeidx = $(this).data('idx');
                                        openPopup('modal', {
                                            id: id,
                                            title: title,
                                            content: content,
                                            firstButton: '확인',
                                            firstCallback: function () {
                                                checkContent(agreeidx);
                                                closePopup(document.getElementById(id));
                                            }
                                        });
                                    })
                                });
                            });
                        }());
                    </script>
                </div>

                <div class="form-action-wrap">
                    <button type="button" class="btn-round btn-tertiary" id="btnNext" style="background-color:#ccc;">다음</button>
                    <!-- 가입 취소: 히스토리를 교체해 이전 가입 단계로 되돌아가지 않도록 한다 -->
                    <a href="/member/login" class="btn-round btn-tertiary" style="margin-left:12px;background-color:#fff;border:1px solid #ccc;color:#666;" onclick="location.replace('/member/login'); return false;">취소</a>
                </div>

            </div>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->

<script>
$(document).ready(function(){
	$("#btnSearchAddr").click(function(){
		searchText();
	});

	$("#txt_addr").keyup(function(e){
		if(e.keyCode == 13){
		   	searchText();
		}
	})

	//위치 정보 검색
	$("#btnGps").click(function(){
		getLocation();
	});

	$("#btnNext").click(function(){
		//if($("#ad_zipcode").val()==""){
		//	commonAlert('주소 검색후 주소를 선택해주세요.',function(){
		//		$("#txt_addr").focus();
		//	});
		//	return;
		//}

    var agree1 = 'N';
    var agree2 = 'N';
    var agree3 = 'N';
    var agree4 = 'N';
    var agree5 = 'N';

		if($("#agree1").is(":checked")==false){
			commonAlert('서비스 이용약관(필수)에 동의해주세요.',function(){
				$("#agree1").focus();
			});
			return;
		}
    agree1 = 'Y';

		if($("#agree2").is(":checked")==false){
			commonAlert('개인정보 처리방침(필수)에 동의해주세요.',function(){
				$("#agree2").focus();
			});
			return;
		}
    agree2 = 'Y';

		if($("#agree3").is(":checked")==false){
			commonAlert('위치 서비스 이용약관(필수)에 동의해주세요.',function(){
				$("#agree3").focus();
			});
			return;
		}
    agree3 = 'Y';

    if($("#agree4").is(":checked")){
            agree4 = 'Y';
    }
    if($("#agree5").is(":checked")){
            agree5 = 'Y';
    }

		var params = $("#frm").serialize();
    params += '&agree1='+agree1;
    params += '&agree2='+agree2;
    params += '&agree3='+agree3;
    params += '&agree4='+agree4;
    params += '&agree5='+agree5;
    params += '&mm_id='+encodeURIComponent($("#mm_id").val());
    params += '&mm_pwd='+encodeURIComponent($("#mm_pwd").val());

	    comAjax("/member_proc/join_comp_step3",params,function(ret2){
	        if(ret2.code=="00"){
	            // step3 완료 후 곧바로 회원가입 완료 (PART)
	            fPassClickFromStep3();
	        }else{
	            commonAlert(ret2.msg,function(){});
	        }
	    });
	});

});

function searchText(){
	if($("#txt_addr").val()==""){
		commonAlert('검색하실 주소를 입력해주세요.',function(){
			$("#txt_addr").focus();
		});
		return;
	}

	$("#ad_zipcode").val("");
	$("#ad_jibun").val("");
	$("#ad_doro").val("");
	$("#ad_addr").val("");
	$("#ad_gps_x").val("");
	$("#ad_gps_y").val("");

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
}

function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
            //alert(position.coords.longitude + "::" + position.coords.latitude);
			comAjax("/common_proc/get_gps_address",{x:position.coords.longitude,y : position.coords.latitude},function(ret){
				if(ret.code=="00"){

					getSearchApi(ret.data,"gps",function(html){
						$(".post-list").removeClass("hidden");
						$(".post-list").html("");
						$(".post-list").append(html);
					})
				}else{
					commonAlert('주소검색이 되지 않는 지역입니다.직접 검색해주세요.',function(){});
				}
			});
		}, function(error) {
		  	if(error.message=="User denied Geolocation"){
				commonAlert('위치사용을 허용 또는  주소를 입력하여 조회 해주세요.',function(){});
      		}
    	}, {
	      enableHighAccuracy: false,
	      maximumAge: 0,
	      timeout: Infinity});
  	} else {
        showDaum("");
		//alert('GPS를 지원하지 않습니다');
        commonAlert('GPS를 지원하지 않습니다.  주소를 입력하여 조회 해주세요.',function(){});
  	}
}

function checkContent(arg) {
    $('#agree'+arg+'').prop('checked', true); // rd_agree1 체크
    //fClickChk(); // 상태 변경 후 fClickChk 호출
}

// step3에서 회원가입 완료 처리 (PART)
// 기업회원은 QuickInfo 바텀시트가 없어(join_comp_finish로 이동) 계정 생성을 미룰 곳이 없다.
// 따라서 개인회원과 달리 여기서 그대로 계정을 생성한다.
function fPassClickFromStep3(){
    var params = {};
    params["ma_type"] = "COMP";
    params["proc_type"] = "PART";
    params["uuid"] = "";

    comAjax("/member_proc/join_complete_proc", params, function(ret){
        if(ret.code=="00"){
            location.href="/member/join_comp_finish";
        }else{
            commonAlert(ret.msg, function(){});
            return;
        }
    });
}

// 브라우저 뒤로가기: 1차는 열린 약관 모달 닫기, 다시 누르면 가입을 취소하고 로그인 화면으로
// 그냥 두면 이전 가입 단계로 돌아가 흐름이 꼬인다.
(function() {
    history.pushState(null, '', location.href);
    window.addEventListener('popstate', function() {
        var openPopupEl = document.querySelector('.layer-popup.in');
        if (openPopupEl) {
            if (typeof closePopup === 'function') {
                closePopup(openPopupEl);
            }
            history.pushState(null, '', location.href); // 소비한 항목 복구
            return;
        }
        location.replace('/member/login');
    });
})();

</script>
<?php include_once (get_base_url() . '/application/shop/views/web/_layout/addr_search_api.php'); ?>
