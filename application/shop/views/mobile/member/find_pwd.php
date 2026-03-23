<div class="page page-find-account">

  <div class="top-bar">
    <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
    <h1 class="title">비밀번호 찾기</h1>
  </div><!-- //top-bar -->

  <div class="page-content">
    <div class="container">
      <!-- 20221122 수정 -->
      <h2 class="primary-title">비밀번호를 찾으시려면 <br><em>휴대폰번호를</em> 입력해주세요</h2>

      <div class="form-container">

        <div class="form-row" style="display:none;">
          <label>
            <span class="form-label">이메일주소</span>
            <input type="email" name="email" placeholder="이메일주소를 입력해 주세요" autocomplete="off" aria-required="true" required class="form-text">
          </label>
          <!-- 폼검사 실패메세지 -->
          <p class="error-1 help-msg is-fail hidden">형식에 맞지 않는 이메일주소입니다.</p>
          <!-- 폼검사 실패메세지 -->
          <p class="error-2 help-msg is-fail hidden">등록된 이메일주소가 없습니다.</p>
        </div>

        <!-- 20221122 수정 -->
        <div class="form-row">
          <p class="form-label">휴대폰번호</p>
          <div class="inline-group">
            <label class="col">
              <span class="sr-text">휴대폰번호 입력</span>
              <input type="text" name="phone" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" placeholder="휴대폰번호를 입력해 주세요" autocomplete="off" aria-required="true" required class="form-text" onkeyup="fNumchk();">
            </label>
            <button id="btnAuth" type="button" class="btn btn-tertiary" style="background-color:#ccc;">인증 요청</button>
          </div>
          <!-- 폼검사 실패메세지 -->
          <p class="error-3 help-msg is-fail hidden">형식에 맞지 않는 휴대폰번호입니다.</p>
          <!-- 폼검사 실패메세지 -->
          <p class="error-4 help-msg is-fail hidden">등록된 휴대폰번호가 없습니다.</p>
          <!-- 간편가입 회원 메세지 -->
          <p class="error-6 help-msg is-fail hidden">간편가입으로 가입된 휴대폰번호 입니다.</p>
        </div>

        <!-- 20221122 수정 -->
        <div class="form-row hidden cont-1">
          <p class="form-label">인증번호</p>
          <div class="inline-group">
            <label class="col">
              <span class="sr-text">인증번호 입력</span>
              <input type="text" name="authCode" placeholder="인증번호를 입력해 주세요" autocomplete="off" aria-required="true" required class="form-text">
              <span id="remain_time" style="position: absolute; width: 48px; right: 0; top: 7px; color: #FF6D6D; text-align: right; font-weight: 600;">0 : 00</span>
            </label>
            <button id="btnAuthNum" type="button" class="btn btn-tertiary">확인</button>
          </div>
          <!-- 인증 실패메세지 -->
          <p class="error-5 help-msg is-fail">형식에 맞지 않는 인증번호입니다.</p>
        </div>

        <div class="sticky-bottom view-footer">
          <button type="button" id="btnFind" aria-haspopup="dialog" class="btn btn-primary">임시비밀번호 발송</button>
        </div>

      </div>

    </div>
  </div><!-- //page-content -->

</div>

<script>
  var is_auth = false;
  var timer;
  var remainTime = 180; // 3분 (180초)

  window.addEventListener('DOMContentLoaded', function(e) {
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
        commonAlert("인증문자 발송에 실패하였습니다.", function() {});
        return;
      }

      //연락처 확인
      var params = {};
      params["mm_phone"] = phone;
      //params["mm_email"] = email;
      comAjax("/member_proc/check_pwd", params, function(ret) {
        console.log(ret);

        if (ret.code == "00") {
          $(".cont-1").removeClass("hidden");
          commonAlert("인증 번호를 발송하였습니다.", function() {
            //$('#remain_time').html('2 : 59');
            // 1초마다 updateTime 함수 실행
            remainTime = 180; // 3분 (180초)
            timer = setInterval(updateTime, 1000);
            updateTime();
          });
        } else {
          if (ret.code == "91") {
            $(".error-4").removeClass("hidden");
            $('#btnAuth').css('background-color', '#ccc');
          } else if (ret.code == "92") {
            $(".error-4").removeClass("hidden");
            $('#btnAuth').css('background-color', '#ccc');
          } else if (ret.code == "93") {
            $(".error-6").removeClass("hidden");
            $('#btnAuth').css('background-color', '#ccc');
          } else if (ret.code == "99") {
            $('#btnAuth').css('background-color', '#ccc');
            commonAlert("SMS발송이 정상 처리 되지 않았습니다.", function() {});
          } else if (ret.code == "98") {
            $('#btnAuth').css('background-color', '#ccc');
            commonAlert("하루 SMS발송횟수를 초과하여 발송이 불가능 합니다. 내일 다시 진행해주세요.", function() {});
          } else {
            $('#btnAuth').css('background-color', '#ccc');
            commonAlert("오류가 발생하였습니다." + ret.code, function() {});
          }
        }
      });
    });

    function updateTime() {
      var minutes = Math.floor(remainTime / 60);
      var seconds = remainTime % 60;

      // 숫자가 한 자리일 때 앞에 '0'을 추가
      if (seconds < 10) {
        seconds = '0' + seconds;
      }

      $('#remain_time').html(minutes + ' : ' + seconds);

      if (remainTime > 0) {
        remainTime--; // 1초씩 감소
      } else {
        clearInterval(timer); // 타이머 중지
      }
    }

    var auth_count = 0;
    $("#btnAuthNum").click(function() {

      if ($('#remain_time').html() == '0 : 00') {
        alert('인증번호 유효시간이 초과되었습니다.\n\n다시 발송해주세요.')
        return;
      }
      $(".help-msg").addClass("hidden");

      var phone = $("[name=phone]").val();
      var params = {};
      params["mm_phone"] = phone;
      params["auth"] = $("[name=authCode]").val();
      comAjax("/member_proc/check_pwd_auth", params, function(ret) {
        if (ret.code == "00") {
          is_auth = true;
          commonAlert("인증이 완료 되었습니다. 임시비밀번호 발송해주세요.", function() {});
        } else {
          auth_count++;

          if (auth_count > 3) {
            commonAlert("인증번호확인이 3회이상 실패하여 임시 비밀번호 발송 작업을 다시 진행합니다.", function() {
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
    if (hp.indexOf('0100') > -1 || hp.indexOf('0101') > -1) {
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

  function fNumchk() {
    //console.log('fNumchk');
    $(".help-msg").addClass("hidden");

    var phone = $("[name=phone]").val();

    // 숫자와 하이픈(-)만 남기기
    //$("[name=phone]").val(phone.replace(/[^0-9\-]/g, ''));
    $("[name=phone]").val(phone.replace(/[^0-9]/g, ''));

    if (isHpFormat(phone) == false) {
      $(".error-3").removeClass("hidden");
      $('#btnAuth').css('background-color', '#ccc');
      return;
    }
    $(".error-3").addClass("hidden");
    $('#btnAuth').css('background-color', '#1B9D7D');
    return;
  }
</script>