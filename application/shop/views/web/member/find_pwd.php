<div id="content" class="page page-login">
	<main class="page-content">

		<div class="container">
			<div class="login-form">
				<p class="title-logo"><img src="/resource/shop/web/img/login_logo.svg" alt="NOW JOB"></p>
				<div class="form-container">

					<div class="title-wrap">
						<h2 class="secondary-title">비밀번호 찾기</h2>
						<p class="desc">비밀번호를 찾으시려면 <em>아이디(전화번호)를 입력</em>해주세요</p>
					</div>

					<div class="form-row" style="display:none;">
						<label>
							<span class="ico-label email">이메일</span>
							<input type="email" name="email" placeholder="이메일주소를 입력해 주세요" autocomplete="off" aria-required="true" required class="form-text">
						</label>
						<!-- 폼검사 실패메세지 -->
						<p class="error-1 help-msg is-fail hidden">형식에 맞지 않는 이메일주소입니다.</p>
						<!-- 폼검사 실패메세지 -->
						<p class="error-2 help-msg is-fail hidden">등록된 이메일주소가 없습니다.</p>
					</div>

					<div class="form-row">
						<div class="inline-group">
							<label class="col">
								<span class="ico-label phone">전화번호</span>
								<input type="text" name="phone" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" placeholder="전화번호를 입력해 주세요" autocomplete="off" aria-required="true" required class="form-text has-confirm" onkeyup="if(isHpFormat($(this).val())==false){ $('.error-3').removeClass('hidden'); $('#btnAuth').css('background-color','#ccc'); }else{ $('.error-3').addClass('hidden'); $('#btnAuth').css('background-color','#1B9D7D'); }">
							</label>
							<button id="btnAuth" type="button" class="btn-round btn-tertiary is-abs">인증 요청</button>
						</div>
						<p class="error-2 help-msg is-fail hidden">등록된 휴대폰번호가 없습니다.</p>
						<!-- 폼검사 실패메세지 -->
						<p class="error-3 help-msg is-fail hidden">형식에 맞지 않는 휴대폰번호입니다.</p>
						<!-- 폼검사 실패메세지 -->
						<p class="error-4 help-msg is-fail hidden">등록된 휴대폰번호가 없습니다.</p>
						<!-- 간편가입 회원 메세지 -->
						<p class="error-6 help-msg is-fail hidden">간편가입으로 가입된 휴대폰번호 입니다.</p>
					</div>

					<div class="form-row  hidden cont-1">
						<div class="inline-group">
							<label class="col">
								<span class="ico-label authcode">인증번호</span>
								<input type="text" name="authCode" placeholder="인증번호를 입력해 주세요" autocomplete="off" aria-required="true" required class="form-text has-confirm">
							</label>
							<button id="btnAuthNum" type="button" class="btn-round btn-tertiary is-abs">확인</button>
						</div>
						<!-- 인증 실패메세지 -->
						<p class="error-5 help-msg is-fail">형식에 맞지 않는 인증번호입니다.</p>
					</div>
					<button type="button" id="btnFind" aria-haspopup="dialog" class="btn btn-primary btn-find" style="display:none;">임시비밀번호 발송</button>

				</div>
			</div>
		</div>

	</main><!-- //page-content -->
</div><!-- //page -->

<script>
	var is_auth = false;
	$(document).ready(function() {
		$("#btnAuth").click(function() {
			$(".help-msg").addClass("hidden");

			// var email = $("[name=email]").val();
			// if(isEmail(email)==false){
			// 	$(".error-1").removeClass("hidden");
			// 	return;
			// }

			var phone = $("[name=phone]").val();
			if (isHpFormat(phone) == false) {
				$(".error-3").removeClass("hidden");
				return;
			}

			//연락처 확인
			var params = {};
			params["mm_phone"] = phone;
			//params["mm_email"] = email;
			comAjax("/member_proc/check_pwd", params, function(ret) {
				if (ret.code == "00") {
					$(".cont-1").removeClass("hidden");
					commonAlert("인증 번호를 발송하였습니다.", function() {

					});
				} else {
					if (ret.code == "91") {
						$(".error-2").removeClass("hidden");
						$('#btnAuth').css('background-color', '#ccc');
					} else if (ret.code == "92") {
						$(".error-4").removeClass("hidden");
					} else if (ret.code == "93") {
						$(".error-6").removeClass("hidden");
						$('#btnAuth').css('background-color', '#ccc');
					} else if (ret.code == "99") {
						commonAlert("SMS발송이 정상 처리 되지 않았습니다.", function() {});
					} else if (ret.code == "98") {
						commonAlert("하루 SMS발송횟수를 초과하여 발송이 불가능 합니다. 내일 다시 진행해주세요.", function() {});
					}
				}

				console.log(ret);
			});
		});

		var auth_count = 0;
		$("#btnAuthNum").click(function() {
			$(".help-msg").addClass("hidden");

			var phone = $("[name=phone]").val();
			var params = {};
			params["mm_phone"] = phone;
			params["auth"] = $("[name=authCode]").val();
			comAjax("/member_proc/check_pwd_auth", params, function(ret) {
				if (ret.code == "00") {
					$("#btnFind").show();
					is_auth = true;
					commonAlert("인증이 완료 되었습니다. 임시비밀번호 발송해주세요.", function() {});
				} else {
					auth_count++;

					if (auth_count > 3) {
						commonAlert("인증번확인이 3회이상 실패하여 임시 비밀번호 발송 작업을 다시 진행합니다.", function() {
							location.reload();
						});
						return;
					}

					commonAlert("잘못된 인증번호 입니다. 다시 입력해주세요.", function() {
						$("[name=authCode]").val("");
						$("[name=authCode]").focus();
					});
					is_auth = false;
				}
			});
		});

		$("#btnFind").click(function() {
			if (is_auth == false) {
				commonAlert("인증 확인후 진행해주세요.", function() {});
				return;
			}

			var phone = $("[name=phone]").val();
			//var email = $("[name=email]").val();
			var params = {};
			params["mm_phone"] = phone;
			//params["mm_email"] = email;
			comAjax("/member_proc/send_temp_pwd", params, function(ret) {
				if (ret.code == "00") {
					commonAlert("임시 비밀번호가 발송되었습니다.", function() {
						location.href = "/member/login";
					});
				} else {
					commonAlert(ret.msg, function() {
						location.reload();
					});
				}
			});
		});
	});

	function isHpFormat(hp) {
		if (hp == "") {
			return false;
		}
		var phoneRule = /^(01[016789]{1})[0-9]{3,4}[0-9]{4}$/;
		return phoneRule.test(hp);
	}

	function isEmail(email) {
		if (email == "") {
			return false;
		}

		var regExp = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;
		// 검증에 사용할 정규식 변수 regExp에 저장

		if (email.match(regExp) != null) {
			return true;
		} else {
			return false;
		}
	}
</script>