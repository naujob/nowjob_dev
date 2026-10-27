<?php
// URL 파라미터에서 phone 가져오기
$phone = isset($_GET['phone']) ? $_GET['phone'] : '';

// 전화번호 정규화 및 유효성 검증
$normalized_phone = preg_replace('/[^0-9]/', '', $phone);
if (empty($normalized_phone) || strlen($normalized_phone) < 10) {
    // 잘못된 파라미터 - 로그인 페이지로 리다이렉트
    header("Location: /member/login");
    exit;
}

// 전화번호 포매팅 (010-0000-0000 형식)
$formatted_phone = preg_replace('/^(\d{3})(\d{3,4})(\d{4})$/', '$1-$2-$3', $normalized_phone);
?>

<div id="content" class="page page-login">
    <main class="page-content">
        <div class="container">
            <div class="login-form">
                <h1 class="title-logo"><img src="/resource/shop/web/img/login_logo.svg" alt="NOW JOB"></h1>
                <div class="form-container">
                    <form id="frm" onsubmit="return false">

                        <div class="form-row">
                            <label>
                                <span class="ico-label phone">휴대폰번호</span>
                                <input type="text" id="display-phone" value="<?= htmlspecialchars($formatted_phone) ?>" readonly class="form-text" style="background-color: #f5f5f5; color: #666;">
                                <input type="hidden" id="hidden-phone" value="<?= htmlspecialchars($normalized_phone) ?>">
                            </label>
                        </div>

                        <div class="form-row">
                            <label>
                                <span class="ico-label authcode">인증번호</span>
                                <input type="text" id="auth-code-input" placeholder="인증번호 6자리를 입력해주세요" maxlength="6" autocomplete="off" class="form-text">
                            </label>
                        </div>

                        <div style="text-align: center; margin: 20px 0; color: #888; font-size: 14px;">
                            남은 시간: <span id="auth-timer">3 : 00</span>
                        </div>

                        <button id="btnAuthConfirm" type="button" class="btn-primary" style="margin-bottom: 12px;">인증 완료</button>
                        <button id="btnResendCode" type="button" class="btn-secondary" style="background-color: #f5f5f5; color: #666;">인증번호 재발송</button>

                    </form>

                </div>
            </div>
        </div>

    </main>
</div>

<script>
  var gAuthRemainTime = 180;
  var gAuthTimer = null;
  var gAuthCount = 0;
  var gAuthMaxRetry = 3;
  var gPhone = '<?= htmlspecialchars($normalized_phone) ?>';

  // 타이머 업데이트
  function updateAuthTimer() {
    var minutes = Math.floor(gAuthRemainTime / 60);
    var seconds = gAuthRemainTime % 60;
    $("#auth-timer").text(minutes + " : " + (seconds < 10 ? "0" : "") + seconds);

    if (gAuthRemainTime <= 0) {
      clearInterval(gAuthTimer);
      commonAlert("인증 유효시간이 초과되었습니다.\n인증번호를 재발송해주세요.", function() {});
      $("#btnAuthConfirm").prop("disabled", true);
      return;
    }

    gAuthRemainTime--;
  }

  // 인증번호 확인
  function confirmAuthCode() {
    var authCode = $("#auth-code-input").val().trim();

    if (authCode.length !== 6 || !/^\d+$/.test(authCode)) {
      commonAlert("인증번호는 6자리 숫자입니다.", function() {
        $("#auth-code-input").focus();
      });
      return;
    }

    var params = { mm_phone: gPhone, auth: authCode };
    comAjax("/member_proc/check_phone_login_auth", params, function(ret) {
      if (ret.code == "00") {
        // 로그인 성공 (기존 회원)
        sessionStorage.removeItem("chat_user_connect");
        comAjax("/member_proc/set_last_login_type", { login_type: "WEB" }, function() {
          // replace: 인증 화면을 히스토리에 남기지 않아 홈에서 뒤로가기 시 인증 화면으로 돌아가지 않게 한다
          location.replace("/");
        });
        return;
      } else if (ret.code == "90") {
        // 미가입 회원 - 회원가입 페이지로 이동
        commonAlert("회원가입을 진행합니다.", function() {
          location.href = "/member/join_start?phone=" + encodeURIComponent(gPhone);
        });
        return;
      } else if (ret.code == "92") {
        // 인증번호 불일치
        gAuthCount++;
        if (gAuthCount >= gAuthMaxRetry) {
          commonAlert("인증번호 입력이 3회 이상 실패했습니다.\n인증번호를 재발송해주세요.", function() {
            gAuthCount = 0;
            $("#auth-code-input").val("");
          });
        } else {
          var remainRetry = gAuthMaxRetry - gAuthCount;
          commonAlert("인증번호가 일치하지 않습니다. (" + remainRetry + "회 남음)", function() {
            $("#auth-code-input").val("").focus();
          });
        }
      } else {
        commonAlert(ret.msg || "오류가 발생했습니다. 다시 시도해주세요.", function() {});
      }
    });
  }

  // 인증번호 재발송
  function resendAuthCode() {
    commonAlert("인증번호를 다시 발송하시겠습니까?", function() {
      var params = { mm_phone: gPhone };
      comAjax("/member_proc/check_phone_login", params, function(ret) {
        if (ret.code == "00") {
          // 재발송 성공
          gAuthRemainTime = 180;
          gAuthCount = 0;
          $("#auth-code-input").val("").focus();
          clearInterval(gAuthTimer);
          gAuthTimer = setInterval(updateAuthTimer, 1000);
          updateAuthTimer();
          commonAlert("인증번호를 재발송했습니다.", function() {});
        } else {
          commonAlert(ret.msg || "재발송에 실패했습니다.", function() {});
        }
      });
    });
  }

  // DOM 로드 후 이벤트 바인딩
  $(document).ready(function() {
    // 인증 확인 버튼
    $("#btnAuthConfirm").click(function(e) {
      e.preventDefault();
      e.stopPropagation();
      confirmAuthCode();
      return false;
    });

    // 재발송 버튼
    $("#btnResendCode").click(function(e) {
      e.preventDefault();
      e.stopPropagation();
      resendAuthCode();
      return false;
    });

    // 입력 필드 엔터키 처리
    $("#auth-code-input").keypress(function(e) {
      if (e.keyCode == 13) {
        e.preventDefault();
        confirmAuthCode();
        return false;
      }
    });

    // 타이머 시작
    gAuthTimer = setInterval(updateAuthTimer, 1000);
    updateAuthTimer();

    // 입력 필드 자동포커스
    $("#auth-code-input").focus();
  });
</script>
