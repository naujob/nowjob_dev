<div class="page page-join member-type1">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <!-- <h1 class="title">기업회원 회원가입</h1> -->
		    <!-- <h1 class="title">비밀번호 만들기</h1> -->
        <h1 class="title">전화번호 인증</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div id="passwordbox" class="container" style="/* display:none; */">
            <!-- <h2 class="primary-title align-center">사용하실 <em>계정정보</em>를 작성해주세요</h2> -->
			<h2 class="primary-title align-center">사용하실 <em>비밀번호</em>를 만들어 주세요</h2>
            <div class="account-info-form">
                <form id="frm" onsubmit="return false">
                    <input type="hidden" name="proc_type" value="join_comp_step3" />
                    <div class="form-row" style="display:none;">
                        <label>
                            <span class="form-label">이메일주소</span>
                            <input type="email" id="mm_id" name="mm_id" value="<?=$mm_phone?>" placeholder="이메일주소를 입력해주세요" autocomplete="off" aria-required="true" required class="form-text">
                        </label>
                        <!-- 폼검사 실패메세지 -->
                        <p class="msg-mm_id help-msg is-fail hidden" style="display:none;">이메일 형식에 맞지 않는 주소입니다.</p>
                    </div>
                    <div class="form-row">
                        <label>
                            <span class="form-label">전화번호</span>
                            <input type="text" id="mm_phone" name="mm_phone" value="<?=$mm_phone?>" required class="form-text"  readonly style="background-color:#cdcdcd; padding-top: 10px; padding-left: 10px;">
                        </label>
                        <!-- 폼검사 실패메세지 -->
                        <p class="msg-mm_id help-msg is-fail hidden" style="display:none;">이메일 형식에 맞지 않는 주소입니다.</p>
                    </div>

                    <style>
                    input[type="password"]:-webkit-autofill {
                      -webkit-box-shadow: 0 0 0px 1000px white inset !important; /* 배경색 덮어쓰기 */
                      -webkit-text-fill-color: #000 !important; /* 텍스트 색상 복원 */
                    }
                    </style>
                    <div class="form-row" style="margin-top: 60px;">
                        <label>
                            <span class="form-label">비밀번호</span>
                            <input type="password"  id="mm_pwd" name="mm_pwd" placeholder="비밀번호를 입력해주세요" autocomplete="off" aria-required="true" required class="form-text" onkeyup="fPassChk();" style="background-color:#fff!important;" autocomplete="new-password">
                            <span class="toggle-password" id="toggle-password" style="position:absolute;right:0;"><!-- 👁 -->️
                              <img id="eye_icon" src="/resource/shop/web/img/eye_close.png" style="margin-top:5px; margin-right: 10px; width: 24px;">
                            </span>
                        </label>
                        <!-- 폼검사 실패메세지 -->
                        <!-- <p class="msg-mm_pwd help-msg is-fail hidden">영문,숫자,특수문자를 혼합하여 8자리 이상으로 입력해주세요.</p> -->
						            <p class="msg-mm_pwd help-msg is-fail hidden">비밀번호는 8~20자의 영문,숫자,최소한 하나의 특수문자(!,@,#,$,%,*,?,&)를 포함해야 합니다.</p>
                    </div>

                    <div class="form-row">
                        <label>
                            <span class="form-label">비밀번호 확인</span>
                            <input type="password"  id="mm_pwd_c" name="mm_pwd_c" placeholder="비밀번호를 입력해주세요" autocomplete="off" aria-required="true" required class="form-text" onkeyup="fPassChk2();">
                            <span class="toggle-password" id="toggle-password2" style="position:absolute;right:0;"><!-- 👁 -->️
                              <img id="eye_icon2" src="/resource/shop/web/img/eye_close.png" style="margin-top:5px; margin-right: 10px; width: 24px;">
                            </span>
                        </label>
                        <!-- 폼검사 실패메세지 -->
                        <p class="msg-mm_pwd_c help-msg is-fail hidden">비밀번호가 일치하지 않습니다.</p>
                    </div>

                    <div class="sticky-bottom">
                        <button id="btnStep" type="button" class="btn btn-primary">다음</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- //page-content -->
</div>
<div id="smsAuthBox" style="display:none; position: fixed; top: 15%; width: 400px; left: calc(50% - 200px); background-color: #fff; padding: 50px;">
	<div>
		<span style="font-size: 1.6em; font-weight: 600;">전화번호 <span style="color: #14715a;">인증</span>이 필요합니다.</span>
		<a href="javascript:history.back();" style="display:none; position: absolute; top: 10px; right: 20px; font-size: 2.0em;">×</a>
		<div style="display: block; margin-top: 50px; margin-bottom: 10px;">
			<input id="hpno" type="text" placeholder="전화번호 입력" style="width: 180px; border: 2px solid #14715a; height: 36px; padding: 5px; margin-right: 5px;">
			<a href="javascript:fSendAuthNo();" style="background-color: #14715a; color: #fff; padding: 0px 0px; height: 36px; line-height: 36px; width:calc(100% - 190px); text-align: center; display: inline-block; margin-top:-2px;">인증번호 받기</a>
		</div>
		<div style="display: block; margin-top: 10px; margin-bottom: 10px;">
			<input id="confno" type="text" placeholder="인증번호 입력" style="width: 180px; border: 2px solid #14715a; height: 36px; padding: 5px; margin-right: 5px;" oninput="validateInput(this);">
			<a href="javascript:fConfAuthNo();" style="background-color: #14715a; color: #fff; padding: 0px 0px; height: 36px; line-height: 35px; width:calc(100% - 190px); text-align: center; display: inline-block; margin-top:-2px;">확인</a>
			<span id="remaintime" style="display:none; position: absolute; left: 173px; top: 187px; font-size: 14px; color: red;">2:59</span>
		</div>
		<div style="display: block; margin-top: 26px;">
			<span style="display: block; color: #777; margin-bottom: 10px;">인증번호 문자를 못 받으셨나요?</span>
			<span style="color: #777; font-size: 14px;">
				* 인증번호가 문자로 수신되지  않을 경우 <br>
&nbsp;&nbsp;				  정확한 정보로 재시도해 주시기 바랍니다.
			</span>
		</div>
	</div>
</div>

<script>
function validateInput(input) {
//    // 한글 문자 제거
//    input.value = input.value.replace(/[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/g, '');

    // 숫자가 아닌 문자는 제거
    input.value = input.value.replace(/[^0-9]/g, '');

    // 최대 길이 제한 (6자)
    if (input.value.length > 6) {
        input.value = input.value.slice(0, 6);
    }
}

var remaintimeNo = 3*60;
function fSendAuthNo(){
  if(remaintimeNo < 3*60 && remaintimeNo > 0){
    commonAlert('이미 발송된 인증번호가 있습니다. 잠시후 다시 시도해 주세요.',function(){});
		return;
  }
	if($('#hpno').val() == ''){
		commonAlert('전화번호를 입력하세요.',function(){});
		return;
  }else if (!$('#hpno').val().startsWith('010')) {
    commonAlert('전화번호를 바르게 입력하세요.', function() {});
    return;
	}else{
		var params = {};
		params["hpno"] = $("#hpno").val();
        comAjax("/member_proc/sendSmsAuth",params,function(ret){
            if(ret.code=="00"){
				commonAlert('인증문자가 발송되었습니다.',function(){});
				$('#remaintime').show();
				remaintimeNo = 3*60;
				$("#remaintime").html("3:00");

				var timer = setInterval(function() {
				    var time = $("#remaintime").text().split(":");
				    var minutes = parseInt(time[0], 10);
				    var seconds = parseInt(time[1], 10);
				    if (seconds === 0) {
				        if (minutes === 0) {
				            clearInterval(timer);
				            return;
				        }
				        minutes--;
				        seconds = 59;
				    } else {
				        seconds--;
				    }
					remaintimeNo--;
				    $("#remaintime").text(minutes + ":" + (seconds < 10 ? "0" : "") + seconds);
				}, 1000);

            }else{
            	commonAlert(ret.msg,function(){});
            }
        });

	}
}

function fConfAuthNo(){
	if(remaintimeNo== (3*60)){
		commonAlert('인증문자를 발송하세요.',function(){});
		return;
	}
	if(remaintimeNo== (0)){
		commonAlert('유효시간이 경과했습니다.',function(){});
		return;
	}
	if($('#hpno').val() == ''){
		commonAlert('전화번호를 입력하세요.',function(){});
		return;
	}else if($('#confno').val() == ''){
		commonAlert('인증번호를 입력하세요.',function(){});
		return;
	}else{
		var params = {};
		params["mm_phone"] = $("#hpno").val();
		params["auth"] = $("#confno").val();
        comAjax("/member_proc/check_pwd_auth",params,function(ret){
            if(ret.code=="00"){
        				remaintimeNo = 0;
        				$("#remaintime").html("0:00");
        				$('#mm_phone').val($("#hpno").val());
        				$('#mm_id').val($("#hpno").val());
        				commonAlert('인증문자가 확인되었습니다.',function(){});
        				$('#smsAuthBox').hide();
        				$('#passwordbox').show();
                $('.title').html('비밀번호 만들기');

            }else{
            	commonAlert(ret.msg,function(){});
            }
        });
	}

}

	//$(document).ready(function(){
  window.addEventListener('load', function(){
		$("#btnStep").click(function(){
			//데이터 확인
			//if(chkEmail($("#mm_id").val())==false || $("#mm_id").val()==""){
			//	$("#mm_id").focus();
			//	$(".msg-mm_id").removeClass("hidden");
			//	return;
			//}

			//데이터 확인
			if(chkPassword($("#mm_pwd").val())==false || $("#mm_pwd").val()==""){
				$("#mm_pwd").focus();
				$(".msg-mm_pwd").removeClass("hidden");
				return;
			}

			//데이터 확인
			if($("#mm_pwd").val()!=$("#mm_pwd_c").val()){
				$("#mm_pwd_c").focus();
				$(".msg-mm_pwd_c").removeClass("hidden");
				return;
			}

            var params = $("#frm").serialize();
            comAjax("/member_proc/join_comp_step3",params,function(ret){
                if(ret.code=="00"){
                    //location.href="/member/join_comp_step4";
                    location.href="/member/join_comp_step5";
                }else{
                	commonAlert(ret.msg,function(){});
                }
            });
		});

		$("#mm_id").blur(function(){
			if(chkEmail($("#mm_id").val())==false || $("#mm_id").val()==""){
				$(".msg-mm_id").removeClass("hidden");
			}else{
				$(".msg-mm_id").addClass("hidden");
			}
		});

		$("#mm_pwd").blur(function(){
			if(chkPassword($("#mm_pwd").val())==false || $("#mm_pwd").val()==""){
				$(".msg-mm_pwd").removeClass("hidden");
			}else{
				$(".msg-mm_pwd").addClass("hidden");
			}
		});

		$("#mm_pwd_c").blur(function(){
			if($("#mm_pwd").val()!=$("#mm_pwd_c").val()){
				$(".msg-mm_pwd_c").removeClass("hidden");
			}else{
				$(".msg-mm_pwd_c").addClass("hidden");
			}
		});

    $('#toggle-password').click(function() {
          const input = $('#mm_pwd');
          const type = input.attr('type') === 'password' ? 'text' : 'password';

          input.attr('type', type);
          if (type === 'password') {
            $('#eye_icon').attr('src','/resource/shop/web/img/eye_close.png');
            $('#eye_icon').css('margin-top','5px');
            $('#eye_icon').css('margin-right','10px');
          } else {
            $('#eye_icon').attr('src','/resource/shop/web/img/eye_view.png');
            $('#eye_icon').css('margin-top','7px');
            $('#eye_icon').css('margin-right','10px');
          }
    });

    $('#toggle-password2').click(function() {
          const input = $('#mm_pwd_c');
          const type = input.attr('type') === 'password' ? 'text' : 'password';

          input.attr('type', type);
          if (type === 'password') {
            $('#eye_icon2').attr('src','/resource/shop/web/img/eye_close.png');
            $('#eye_icon2').css('margin-top','5px');
            $('#eye_icon2').css('margin-right','10px');
          } else {
            $('#eye_icon2').attr('src','/resource/shop/web/img/eye_view.png');
            $('#eye_icon2').css('margin-top','7px');
            $('#eye_icon2').css('margin-right','10px');
          }
    });

    $('#mm_pwd').val('');
    $(".msg-mm_pwd").removeClass("hidden");//show
    $(".msg-mm_pwd_c").removeClass("hidden");

	});//ready

	//이메일체크
	function chkEmail(value){
		var regExp = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;
		if(!regExp.test(value)){
			return false;
		}
		return true;
	}

	//비밀번호체크
	function chkPassword(value){
		var pattern1 = /[0-9]/;
    var pattern2 = /[a-zA-Z]/;
    //var pattern3 = /[~!@\#$%<>^&*]/;     // 원하는 특수문자 추가 제거
    var pattern3 = /[!@#$%*?&]/;     // 원하는 특수문자 추가 제거
		if(!pattern1.test(value)||!pattern2.test(value)||!pattern3.test(value)||value.length<8||value.length>20){
			return false;
        }
		return true;
	}


    function fPassChk(){
      if($("#mm_pwd").val().length >= 8 && $("#mm_pwd").val().length <= 20){
        if(chkPassword($("#mm_pwd").val())==false || $("#mm_pwd").val()==""){
          $(".msg-mm_pwd").removeClass("hidden");//show
        }else{
          $(".msg-mm_pwd").addClass("hidden");//hide
        }
      }else{
        $(".msg-mm_pwd").removeClass("hidden");//show
      }

    }

    function fPassChk2(){
      if($("#mm_pwd").val()!=$("#mm_pwd_c").val()){
        $(".msg-mm_pwd_c").removeClass("hidden");
      }else{
        $(".msg-mm_pwd_c").addClass("hidden");
      }
    }

</script>
