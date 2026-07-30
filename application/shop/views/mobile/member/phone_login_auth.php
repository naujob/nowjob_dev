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

<div class="page page-login">
    <div class="login-bg-img"></div>
    <img src="/resource/shop/mobile/img/member/ic-nowjob-logo.svg" alt="NowJob Logo" class="login-logo login-logo-centered">
    <div class="login-form">
        <div class="form-container">
            <form id="frm" onsubmit="return false">

                <div class="nowjob-login-wrapper">
                    <!-- 휴대폰번호 표시 (읽기 전용) -->
                    <div class="form-row">
                        <label>
                            <span class="ico-label phone">휴대폰번호</span>
                            <input type="text" id="display-phone" value="<?= htmlspecialchars($formatted_phone) ?>" readonly class="form-text nowjob-input" style="background-color: #f5f5f5; color: #666;">
                            <input type="hidden" id="hidden-phone" value="<?= htmlspecialchars($normalized_phone) ?>">
                        </label>
                    </div>

                    <!-- 인증번호 입력 -->
                    <div class="form-row">
                        <label>
                            <span class="ico-label auth">인증번호</span>
                            <input type="text" id="auth-code-input" placeholder="인증번호 6자리를 입력해주세요" maxlength="6" autocomplete="off" class="form-text nowjob-input">
                        </label>
                    </div>

                    <!-- 타이머 -->
                    <div style="text-align: center; margin: 15px 0; color: #fff; font-size: 15px;">
                        남은 시간: <span id="auth-timer">3 : 00</span>
                    </div>

                    <!-- 확인 버튼 -->
                    <div class="form-row">
                        <button id="btnAuthConfirm" type="button" class="btn-primary" style="font: size 1.3rem;">인증 완료</button>
                    </div>

                    <!-- 재발송 버튼 -->
                    <div style="text-align: center; margin-top: 15px;">
                        <button id="btnResendCode" type="button" style="background: none; border: none; color: #57ccaf; font-size: 15px; cursor: pointer; text-decoration: underline;">인증번호 재발송</button>
                    </div>
                </div>
            </form>
            <div class="btn-goback-container">
                <a href="/member/login" class="btn-primary btn-goback"></a>
            </div>

        </div>

    </div>

</div>

<style>
    /* 배경 이미지 CSS */
    .login-bg-img {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url(/resource/shop/mobile/img/member/nowjob-back-logo.v3.jpg?vs=<?= getFileVersion('/resource/shop/mobile/img/member/nowjob-back-logo.v3.jpg') ?>);
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      z-index: 0;
      pointer-events: none;
    }

    /* 로그인 폼을 배경 위에 표시 */
    .page-login .login-form {
      position: relative;
      z-index: 1;
    }

    /* 입력 필드 스타일 */
    .page-login .nowjob-input::placeholder {
        font-family: inherit;
        font-weight: 500;
        color: #BEBFC9;
        opacity: 1;
        text-indent: 0 !important;
        font-size: 16 !important;
    }

    .page-login .nowjob-input {
        margin-bottom: 20px;
    }

    .page.page-login .login-form .form-container #frm .form-row {
        margin-top: 11.2px !important;
    }

    /* 인증 아이콘 */
    .ico-label.auth {
        background-image: url(/resource/shop/mobile/img/ic_login_phone.svg);
    }
</style>

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

        // 최근 로그인 타입을 세션에 저장 (휴대폰 OTP 로그인)
        comAjax("/member_proc/set_last_login_type", { login_type: "WEB" }, function() {
          if (typeof window.connectFCMTokenToUser === 'function') {
            window.connectFCMTokenToUser().then(function() {
              _replace("/");
            }).catch(function(error) {
              _replace("/");
            });
          } else {
            _replace("/");
          }
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
  document.addEventListener("DOMContentLoaded", function() {
    // 인증 확인 버튼
    $("#btnAuthConfirm").on("click", function() {
      confirmAuthCode();
    });

    // 재발송 버튼
    $("#btnResendCode").on("click", function() {
      resendAuthCode();
    });

    // 입력 필드 엔터키 처리
    $("#auth-code-input").on("keypress", function(e) {
      if (e.which == 13) {
        e.preventDefault();
        confirmAuthCode();
      }
    });

    // 타이머 시작
    gAuthTimer = setInterval(updateAuthTimer, 1000);
    updateAuthTimer();

    // 입력 필드 자동포커스
    $("#auth-code-input").focus();
  });
</script>
