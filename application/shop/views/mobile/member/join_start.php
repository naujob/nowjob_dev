<div class="page page-member-type">
	<a href="javascript:void(0);" class="btnX" id="btnX" style="display: flex; align-items: center; justify-content: center; min-width: 44px; min-height: 44px; padding: 10px;">
		<img src="/resource/shop/mobile/img/ic_alert_close.svg" />
	</a>
	<div class="container">
		<div class="form-container">
			<form action="/">
				<h1 class="title-logo"><img src="/resource/shop/mobile/img/top_logo_dark.png" alt="NOW JOB"></h1>
				<div class="form-row">
					<label class="form-toggle-btn">
						<input type="radio" name="memberType" id="memberType1" value="mem">
						<span class="btn">
							<span class="ico member-type1"></span>
							<span>일자리를 찾으신다면</span>
							<strong>개인회원</strong>
						</span>
					</label>
					<label class="form-toggle-btn">
						<input type="radio" name="memberType" id="memberType2" value="comp">
						<span class="btn">
							<span class="ico member-type2"></span>
							<span>인재를 찾으신다면</span>
							<strong>기업회원</strong>
						</span>
					</label>
				</div>
				<!-- <p class="caution-msg">가입하신 기업/개인 회원구분은 <em>전환</em>이 불가합니다.</p> -->
				<p class="caution-msg">개인/기업 회원가입 후 <em style="color:red;">전환</em>이 불가합니다.</p>

				<!-- 기업회원 동의내용 -->
				<template id="agreement">
					<h2 class="title">불건전, 부적절한 회원은<br> <em>신고 및 영구정지</em> 됩니다.</h2>
					<div class="agreement-form">
						<p class="headline"><strong>나우잡은 불법행위에 대해 수사기관에<br> 적극 협조하고 있습니다</strong></p>
						<p>불건전한 공고를 올리거나 타 대화 채널로<br> 유도하는 경우 경고 없이 이용이 제환됩니다.<br><span class="txt-red">(이용제한 업종 : 유흥업소, 인터넷방송, 다단계, 구매대행 등)</span></p>
						<ul class="terms-list">
							<li>
								<a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="js-open-popup">공고 등록 제한 규정</a>
								<!-- 규정내용 -->
								<template id="terms5Temp">
									<div class="terms-wrap">
										<p class="terms-title">공고 등록 제한 규정</p>
										<p><strong>제 1 조 (목적)</strong></p>
										<p>본 약관은 (주)나우디앤아이 유한책임회사(이하 "회사")가 운영하는 "서비스"를 이용함에 있어 "회사"와 회원간의 이용 조건 및 제반 절차, 권리, 의무 및 책임사항, 기타 필요한 사항을 규정함을 목적으로 한다.</p>
										<br>
										<p><strong>제 2 조 (용어의 정의)</strong></p>
										<p>이 약관에서 사용하는 용어의 정의는 아래와 같다.</p>
										<br>
										<p>①"사이트"라 함은 회사가 서비스를 "회원"에게 제공하기 위하여 컴퓨터 등 정보통신설비를 이용하여 설정한 가상의 영업장 또는 회사가 운영하는 웹사이트, 모바일웹, 앱 등의 서비스를 제공하는 모든 매체를 통칭하며, 통합된 하나의 회원 계정(아이디 및 비밀번호) 을 이용하여 서비스를 제공받을 수 있는 아래의 사이트를 말한다.</p>
									</div>
								</template>
							</li>
							<li>
								<a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="js-open-popup">계정 이용 정지 규정</a>
								<!-- 규정내용 -->
								<template id="terms6Temp">
									<div class="terms-wrap">
										<p class="terms-title">계정 이용 정지 규정</p>
										<p><strong>제 1 조 (목적)</strong></p>
										<p>본 약관은 (주)나우디앤아이 유한책임회사(이하 "회사")가 운영하는 "서비스"를 이용함에 있어 "회사"와 회원간의 이용 조건 및 제반 절차, 권리, 의무 및 책임사항, 기타 필요한 사항을 규정함을 목적으로 한다.</p>
										<br>
										<p><strong>제 2 조 (용어의 정의)</strong></p>
										<p>이 약관에서 사용하는 용어의 정의는 아래와 같다.</p>
										<br>
										<p>①"사이트"라 함은 회사가 서비스를 "회원"에게 제공하기 위하여 컴퓨터 등 정보통신설비를 이용하여 설정한 가상의 영업장 또는 회사가 운영하는 웹사이트, 모바일웹, 앱 등의 서비스를 제공하는 모든 매체를 통칭하며, 통합된 하나의 회원 계정(아이디 및 비밀번호) 을 이용하여 서비스를 제공받을 수 있는 아래의 사이트를 말한다.</p>
									</div>
								</template>
							</li>
						</ul>
					</div>
				</template>
			</form>
		</div>
	</div>
</div>
<form name="form_chk" method="post" target="frame" action="https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb">
	<input type="hidden" name="m" value="checkplusService">
	<input type="hidden" name="EncodeData" value="<?= $enc_data ?>">
</form>
<form id="frm2" name="frm2" action="/member/join_wmem_step1" method="post" onsubmit="/* return false */">
	<input type="hidden" name="mm_name" readonly placeholder="가입자명(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;" onfocus="commonAlert('본인인증을 완료하면 자동으로 입력됩니다.',function(){});">
	<input type="hidden" name="mm_phone" id="mm_phone">
	<input type="hidden" name="mm_di" id="mm_di">
	<input type="hidden" name="mm_sex" id="mm_sex">
	<input type="hidden" name="mm_birth" id="mm_birth">
	<input type="hidden" name="mm_nation" id="mm_nation">
	<input type="hidden" name="bizPhone" id="bizPhone" value="01012341234">
	<input type="hidden" name="join_type" id="join_type" value="<?= $join_type ?>">
</form>

<style>
	.iframebox {
		position: relative;
		width: 100%;
		padding-bottom: 56.25%;
	}

	.iframebox iframe {
		position: absolute;
		width: 100%;
		height: 100%;
	}

	/* 웹 버전과 동일한 동의 팝업 스타일 */
	.layer-popup[id*=agreementPopup] .popup-container {
		width: 90%;
		max-width: 500px;
	}

	.layer-popup[id*=agreementPopup] .popup-content {
		padding: 60px 42px 32px;
	}

	.layer-popup[id*=agreementPopup] .title {
		margin: 0 0 30px;
		font-size: 20px;
		text-align: center;
	}

	.layer-popup[id*=agreementPopup] .title em {
		font-style: normal;
		color: #57CCAF;
	}

	.agreement-form {
		padding: 182px 0 0;
		line-height: 1.5625;
		text-align: center;
		background: url("/resource/shop/web/img/join_unlegal.svg") no-repeat 50% 0;
		background-size: auto 150px;
	}

	.agreement-form .headline {
		margin: 0 0 10px;
		font-size: 18px;
		font-weight: 700;
		line-height: 1.3888888889;
	}

	.agreement-form .terms-list {
		display: -webkit-box;
		display: -ms-flexbox;
		display: flex;
		margin: 32px 0 0;
		padding: 0;
		list-style: none;
		font-size: 18px;
		font-weight: 700;
		line-height: 1.1;
		-webkit-box-pack: center;
		-ms-flex-pack: center;
		justify-content: center;
		text-decoration: underline;
	}

	.agreement-form .terms-list li {
		float: left;
	}

	.agreement-form .terms-list li+li {
		margin-left: 20px;
	}

	.agreement-form .terms-list a {
		text-decoration: underline;
	}

	/* 약관 상세 내용 스타일 */
	.terms-wrap {
		position: relative;
		line-height: 1.875;
		word-break: keep-all;
		overflow: hidden;
		padding: 20px;
		background: #fff;
		border-radius: 8px;
		max-height: 400px;
		overflow-y: auto;
	}

	.terms-wrap .terms-title {
		font-size: 16px;
		font-weight: bold;
		margin-bottom: 15px;
		color: #2c3e50;
		text-align: center;
	}

	.terms-wrap p {
		font-size: 13px;
		line-height: 1.6;
		margin-bottom: 10px;
		color: #555;
	}

	.terms-wrap p strong {
		color: #2c3e50;
		font-weight: bold;
	}

	.terms-wrap a:hover,
	.terms-wrap a:focus {
		text-decoration: underline;
	}
</style>
<script>
	//alert('start');
	window.name = "Parent_window";
	var name_ver = 1;
	var os_type = "<?= $os_type ?>";
	// alert(navigator.userAgent);
	if (navigator.userAgent.includes("NowJobApp_iOS")) {
		os_type = "ios";
	} else if (navigator.userAgent.includes("NowJobApp_Android") || navigator.userAgent.includes("Android")) {
		// 2025-01-17: 안드로이드 앱 감지 로직 추가 - WebView 보안 정책 문제 해결을 위해
		os_type = "android";
	}
	// alert('os_type: ' + os_type); // 디버깅용
	//alert(os_type);
	function fnPopup() {
		if ($("[name=EncodeData]").val() == "") {
			alert("본인인증 모듈생성에 문제가 있습니다. 관리자에 문의해주세요.");
			return;
		}

		//alert(os_type);
		//alert("이거 보이지?");

		if (os_type == "ios") {
			//alert(os_type);
			//alert($('#join_type').val());
			// SNS 인증에서도 NICE 창이 뜨도록 수정
			location.href = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb?m=checkplusService&EncodeData=<?= $enc_data ?>";
	} else if (os_type == "android") {
		// 2025-01-17: 안드로이드 앱 전용 처리 - 팝업 없이 직접 이동 (WebView 보안 정책 문제 해결)
		// SNS 인증에서도 NICE 창이 뜨도록 수정
		// Android 뒤로가기를 위해 히스토리에 남도록 처리
		location.href = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb?m=checkplusService&EncodeData=<?= $enc_data ?>";
	} else {
			// 웹 브라우저용 기존 로직
			//alert('open');
			//window.open('', 'popupChk' + name_ver, 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			//document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
			//document.form_chk.submit();
			//alert('popup');
			// SNS 인증에서도 NICE 창이 뜨도록 수정
			window.open('/member/nice_popup', '', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			//window.open('https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb?m=checkplusService&EncodeData=<?= $enc_data ?>', 'popupChk' + name_ver, 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			//alert("실행함");
		}
		//document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
		//document.form_chk.target = "popupChk" + name_ver;
		//document.form_chk.submit();
		//window.open('/member/nice_popup', '', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		//window.open('https://nowapk.co.kr/member/nice_popup');


		//alert("이거외안되~~~");
		//window.open('', 'popupChk' + name_ver, 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		//document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
		//document.form_chk.submit();
		//window.open('https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb?m=checkplusService&EncodeData=<?= $enc_data ?>', 'popupChk' + name_ver, 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
	}
</script>
<script>
	// URL 파라미터 가져오는 함수를 전역 스코프로 정의
	window.getUrlParameter = function(name) {
		name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
		var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
		var results = regex.exec(location.search);
		var value = results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
		console.log('getUrlParameter(' + name + ') = ' + value); // 디버깅 로그
		return value;
	};
	// 약관 상세 팝업 이벤트 바인딩 공통 함수
	function bindTermsPopupEvents() {
		setTimeout(function() {
			document.querySelectorAll('.js-open-popup').forEach(function(btn) {
				// 이미 이벤트가 바인딩되어 있는지 확인 (중복 방지)
				if (btn.hasAttribute('data-terms-bound')) {
					return;
				}
				btn.setAttribute('data-terms-bound', 'true');

				btn.addEventListener('click', function(e) {
					e.preventDefault();
					var id = this.getAttribute('aria-controls');
					var title = this.textContent;
					var content = this.parentNode.querySelector('template').innerHTML;
					openPopup('modal', {
						id: id,
						title: title,
						content: content,
						firstButton: '확인',
						firstCallback: function() {
							closePopup(document.getElementById(id));
						}
					});
				});
			});
		}, 100);
	}

	//$(document).ready(function(){
	window.addEventListener('DOMContentLoaded', function() {
		// [기업회원] 본인인증 완료 후 동의 팝업 자동 표시 (SNS 회원가입 경로)
		// 주석처리: 기업회원 SNS는 NICE 본인인증을 하지 않으므로 이 코드는 실행되지 않음
		/*
		if (getUrlParameter('show_agreement') === 'comp' && getUrlParameter('join_type') === 'sns') {
			console.log('본인인증 완료 후 동의 팝업 자동 표시'); // 디버깅 로그
			// 기업회원 라디오 버튼 자동 선택
			$("[id=memberType2]").prop('checked', true);
			// 동의 팝업 표시
			var content = document.getElementById('agreement').innerHTML;
			openPopup('modal', {
				id: 'agreementPopup',
				content: content,
				firstButton: '동의합니다',
				firstCallback: function() {
					// SNS 회원가입 경로: 동의 정보 저장 후 바로 다음 단계로 이동
					var params = {};
					comAjax("/member_proc/join_comp_step1", params, function(ret) {
						if (ret.code == "00") {
							// 동의 정보 저장 완료 후 바로 다음 단계로 이동
							location.href = './join_comp_step3';
						} else {
							commonAlert(ret.msg, function() {});
						}
					});
				}
			});
			// 약관 상세 팝업 이벤트 바인딩
			bindTermsPopupEvents();
		}
		*/

		// [개인회원] 개인회원 라디오 버튼 클릭 이벤트
		$("[id=memberType1]").click(function() {
			// [개인회원] SNS 간편로그인 경로인 경우 PASS 인증 없이 바로 회원가입 진행
			if (getUrlParameter('join_type') === 'sns') {
				console.log('SNS 간편로그인 경로 - PASS 인증 없이 바로 회원가입 진행'); // 디버깅 로그
				var params = {};
				params["type"] = "MEM"; // 개인회원 타입
				comAjax("/member_proc/join_auth", params, function(ret) {
					if (ret.code == "00") {
						// PASS 인증 없이 바로 다음 단계로 이동 (모바일용 경로)
						location.href = './join_mem_step1';
					} else {
						commonAlert(ret.msg, function() {});
					}
				});
				return;
			}

			// [개인회원] 일반 회원가입 경로 - NICE 본인인증 팝업 호출
			console.log('개인회원 선택 - NICE 본인인증 팝업 호출'); // 디버깅 로그
			var params = {};
			params["type"] = "MEM"; // 개인회원 타입
			comAjax("/member_proc/join_auth", params, function(ret) {
				if (ret.code == "00") {
					//	document.frm2.action = './join_mem_step1';
					fnPopup();
				} else {
					commonAlert(ret.msg, function() {});
				}
			});
		});

		// [기업회원] 기업회원 라디오 버튼 클릭 이벤트
		$("[id=memberType2]").click(function() {
			if (!this.checked) return;

			// [기업회원] SNS 간편로그인 경로인 경우 PASS 인증 없이 바로 회원가입 진행
			console.log('memberType2 clicked, join_type = ' + getUrlParameter('join_type')); // 디버깅 로그
			if (getUrlParameter('join_type') === 'sns') {
				console.log('SNS 간편로그인 경로 - PASS 인증 없이 바로 회원가입 진행'); // 디버깅 로그
				// 동의 팝업 표시 (PASS 인증 없이)
				var content = document.getElementById('agreement').innerHTML;
				openPopup('modal', {
					id: 'agreementPopup',
					content: content,
					firstButton: '동의합니다',
					firstCallback: function() {
						var params = {};
						params["type"] = "COMP"; // 기업회원 타입
						comAjax("/member_proc/join_auth", params, function(ret) {
							if (ret.code == "00") {
								// PASS 인증 없이 바로 다음 단계로 이동 (모바일용 경로)
								location.href = './join_comp_step3';
							} else {
								commonAlert(ret.msg, function() {});
							}
						});
					}
				});
				// 약관 상세 팝업 이벤트 바인딩
				bindTermsPopupEvents();
				return;
			}

			// [기업회원] 일반 회원가입 경로 - 동의 팝업 표시 후 PASS 인증
			console.log('일반 회원가입 경로 - 동의 팝업 표시'); // 디버깅 로그
			var content = document.getElementById('agreement').innerHTML;
			openPopup('modal', {
				id: 'agreementPopup',
				content: content,
				firstButton: '동의합니다',
				firstCallback: function() {
					var params = {};
					params["type"] = "COMP"; // 기업회원 타입
					comAjax("/member_proc/join_auth", params, function(ret) {
						if (ret.code == "00") {
							//	document.frm2.action = './join_comp_step1';
							fnPopup();
						} else {
							commonAlert(ret.msg, function() {});
						}
					});
				}
			});
			bindTermsPopupEvents();
		});
	});

	//뒤로가기 처리
	$(".btnX").click(function() {
		_replace("/member/login");
	});

	// 브라우저 뒤로가기 시 로그인 페이지로 이동
	window.addEventListener('load', function() {
		// 히스토리 완전 초기화 (PASS 인증 관련 히스토리 제거)
		// 현재 페이지를 히스토리 스택의 첫 번째 항목으로 만들기 위해 pushState를 여러 번 호출
		window.history.pushState(null, '', location.href);
		window.history.pushState(null, '', location.href);

		// 뒤로가기 이벤트 처리 (한 번만 실행되도록 플래그 사용)
		var isHandlingBack = false;
		window.addEventListener('popstate', function(e) {
			if (isHandlingBack) return;
			isHandlingBack = true;

			// 히스토리를 완전히 초기화하고 로그인 페이지로 이동
			window.history.replaceState(null, '', "/member/login");
			location.replace("/member/login");
		});
	});

	function fsubmit() {
		//location.href = '/member/join_wmem_step1';
		$('#frm2').submit();
		//location.href = '/member/join_mem_toturial';
	}

</script>
