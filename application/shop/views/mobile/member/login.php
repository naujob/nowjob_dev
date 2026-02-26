<div class="page page-login">
  <style>
    /* 자동완성 배경색 없애기 */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
      transition: background-color 5000s ease-in-out 0s;
      /* 배경색이 변경되지 않도록 설정 */
      -webkit-text-fill-color: #000 !important;
      /* 텍스트 색상을 유지 */
    }

    /* .vendor-login .vendor-type3:before {
      background-image: url(/resource/shop/mobile/img/ic_login_naver.png);
    }

    .vendor-login .vendor-type3 {
      background: #02c75a;
    } */
    /* 비회원 둘러보기 버튼 위치 조정 */
    .try-trial-container {
      position: relative;
      top: -20px;
      /* 비회원 둘러보기 위치 조정 - 네이버 버튼과 겹치지 않도록 */
      height: 56px;
      /* 버튼 높이 추가 */
      margin-top: 20px;
      /* 추가 여백으로 겹침 방지 */
    }
  </style>
  <?php
  $userAgent = $_SERVER['HTTP_USER_AGENT'];
  // echo $userAgent;
  $iosapp = false;
  if (strpos($userAgent, 'NowJobApp_iOS') !== false) {
    $iosapp = true;
  }
  $isIosBrowser = preg_match('/(iPhone|iPad|iPod)/i', $userAgent) === 1;
  // os_type이 비어 있으면 User-Agent로 iOS 판별하여 보정
  if (!isset($os_type) || $os_type === '') {
    if ($isIosBrowser || $iosapp) {
      $os_type = 'ios';
    }
  }
  // echo "end";
  ?>
  <div class="login-form">
    <div class="form-container">
      <form id="frm" onsubmit="return false">




        <div class="vendor-login">

          <?php
          $app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
          $apple_redirect = urlencode("https://nowjob.co.kr/member/apple_login_callback");
          $kakao_redirect = urlencode($app_base_url . "/member/kakao_login_callback");
          $apple_login = "https://appleid.apple.com/auth/authorize?client_id=com.nowapk.app.nowjob2.login&redirect_uri=" . $apple_redirect . "&response_type=code id_token&state=app_ecm_apple_sign_in&scope=name email&response_mode=form_post";

          // $kakao_login = "https://kauth.kakao.com/oauth/authorize?response_type=code&client_id=".$kakao_key["인증키"]."&redirect_uri=" . $kakao_redirect . "&state=" . $csrf;
          // $kakao_login = "";
          $kakao_setting = getSettingInfo(["st_group" => "카카오"]);
          $kakao_client_id = isset($kakao_setting["REST_API_KEY"]) ? $kakao_setting["REST_API_KEY"] : "";
          $kakao_login = "https://kauth.kakao.com/oauth/authorize?response_type=code&client_id=" . $kakao_client_id . "&redirect_uri=" . $kakao_redirect . "&state=" . $csrf;
          ?>
          <!--a href="javascript:void(0);" id="btnKakao" class="btn-primary vendor-type1">카카오톡으로 로그인</a-->
          <?php //echo "[".$os_type."]";
          ?>
          <a href="javascript:void(0);" id="btnGoogle" class="btn-primary vendor-type5">Google로 로그인</a>

          <?php if ($iosapp) { ?>
            <a href="<?= $naver_login ?>" id="btnNaver" class="btn-primary vendor-type4"></a>
          <?php } else if ($os_type == "android") { ?>
            <a href="javascript:void(0);" id="btnNaver" class="btn-primary vendor-type4" onclick="APP_NOWAPK.naverLoginAnd(); return false;"></a>
          <?php } else { ?>
            <a href="#" id="btnNaver" class="btn-primary vendor-type4" onclick=""></a>
          <?php } ?>

          <?php if ($os_type == "android") : ?>
            <a href="javascript:void(0);" id="btnKakaoAndroid" class="btn-primary vendor-type1"></a>
          <?php else : ?>
            <a href="<?= $kakao_login ?>" id="btnKakao" class="btn-primary vendor-type1"></a>
          <?php endif; ?>

          <!-- 전화번호로 시작하기 버튼 (가장 아래) -->
          <a href="#" id="btnPhone" class="btn-primary vendor-type3" onclick="goPhoneLogin(); return false;">
          </a>

          <!-- <a href="#" id="btnKakaoAndApp" class="btn-primary vendor-type1" onclick="alert(1); APP_NOWAPK.kakaoLoginAnd(); alert(2); return false;">카카오톡으로 로그인(android app)</a>
                    <a href="#" id="btnKakaoAndApp2" class="btn-primary vendor-type1" onclick="alert(1); APP_NOWAPK.callVibrate(); alert(2); return false;">진동</a> -->
          
           <!-- Apple 로그인 버튼 주석 처리 -->
          <?php if ($os_type == "ios" || $isIosBrowser) : ?>
            <!-- <a href="<?= $apple_login ?>" id="btnApple" class="btn-primary vendor-type2">Apple로 로그인</a> -->
          <?php endif; ?>

          <? //=$os_type
          ?>
        </div>
        <ul class="account-menu">
          <!--<li><input type="checkbox" id="idsave" name="idsave"> 아이디저장</li>  -->
          <!-- <li><a href="/member/find_id">아이디찾기</a></li> -->
          <li><a href="/member/find_pwd">비밀번호 찾기</a></li>
        </ul>

      </form>

      <p class="try-trial"><a href="/">비회원 둘러보기</a></p>
      <a href="/member/join_start" class="btn-primary btn-signup"></a>

      <!--
            <button onclick="window.APP_NOWAPK.kakaoLoginAnd()">카카오 로그인</button>
            <button onclick="window.APP_NOWAPK.kakaoLoginAnd()">카카오 로그인</button>
            <button onclick="APP_NOWAPK.kakaoLoginAnd()">카카오 로그인</button>
            <button onclick="testKakaoLogin()">카카오 로그인 테스트</button>
            <button onclick="NowJobBridge.getLocation()">Location</button>
          -->
    </div>
  </div>
</div>


<script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script>
<script>
  /*
window.onload = function() {
  if (typeof APP_NOWAPK !== 'undefined') {
    alert('APP_NOWAPK is ready');
    if (typeof APP_NOWAPK.kakaoLoginAnd !== 'undefined') {
      APP_NOWAPK.kakaoLoginAnd();
    } else {
      alert('kakaoLoginAnd is undefined');
    }
  } else {
    alert('APP_NOWAPK is undefined');
  }
}
*/
  /*
  window.addEventListener('load', () => {
      setTimeout(() => {
          alert(typeof APP_NOWAPK);
          alert(typeof APP_NOWAPK.kakaoLoginAnd);
      }, 1000); // 1초 정도 기다린 뒤 호출
  });
  */
  /*
  function testKakaoLogin() {
    alert(typeof NowJobBridge); // 'object' or 'undefined'
    alert(typeof NowJobBridge.kakaoLoginAnd); // 'function' or 'undefined'
    if (typeof NowJobBridge !== 'undefined' && typeof NowJobBridge.kakaoLoginAnd === 'function') {
        NowJobBridge.kakaoLoginAnd();
    } else {
        alert("NowJobBridge 또는 kakaoLoginAnd 함수가 존재하지 않습니다.");
    }
  }
  */
  // $(document).ready(function() {
  document.addEventListener('DOMContentLoaded', function() {
    // WebView에서 로그 테스트
    console.log("✅ WebView에서 로그 테스트 성공!");
    //로그인
    $("#btnLogin").click(function() {
      goLogin();
    });

    $("[name=mm_pwd]").keyup(function(e) {
      if (e.keyCode === 13) {
        goLogin();
      }
    })
    // 전화번호 로그인 버튼 클릭 이벤트 추가
    $("#btnPhone").click(function() {
      goPhoneLogin();
    });
    //네이버 로그인
    $("#btnNaver").click(function() {
      if (isIOSAppNaver()) {
        window.webkit.messageHandlers.naverLogin.postMessage(null);
        return;
      }
    });

    $("#btnKakaoAndroid").click(function(e) {
      e.preventDefault(); // 기본 동작 방지 (페이지 상단 이동 방지)
      e.stopPropagation(); // 이벤트 버블링 방지
      APP_NOWAPK.kakaoLoginAnd();
      return false; // 추가 안전장치
    });

    // 구글 로그인
    $("#btnGoogle").click(function(e) {
      e.preventDefault();
      e.stopPropagation();
      <?php if ($os_type == "android") { ?>
        if (typeof APP_NOWAPK !== 'undefined' && typeof APP_NOWAPK.googleLoginAnd === 'function') {
          APP_NOWAPK.googleLoginAnd();
        } else {
          alert("구글 로그인을 사용할 수 없습니다. 앱을 최신 버전으로 업데이트해주세요.");
        }
      <?php } else if ($os_type == "ios") { ?>
        if (typeof window.webkit !== 'undefined' &&
            typeof window.webkit.messageHandlers !== 'undefined' &&
            typeof window.webkit.messageHandlers.googleLogin !== 'undefined') {
          window.webkit.messageHandlers.googleLogin.postMessage('');
        } else {
          alert("구글 로그인을 사용할 수 없습니다. 앱을 최신 버전으로 업데이트해주세요.");
        }
      <?php } else { ?>
        alert("구글 로그인은 준비중입니다.");
      <?php } ?>
      return false;
    });

    //카카오 로그인
    $("#btnKakao").click(function() {
      //alert('kakao click');
      if (isIOSApp()) {
        //alert('ios');
        window.webkit.messageHandlers.kakaoLogin.postMessage(null);
        return false;
      }

      <?php if ($os_type == "android") { ?>
        //alert('APP_NOWAPK call');
        APP_NOWAPK.kakaoLoginAnd();
        return false;
      <?php } ?>
      //alert('web call');

      if (!Kakao.isInitialized()) {
        <?php
        $kakao_setting = getSettingInfo(["st_group" => "카카오"]);
        $kakao_js_key = isset($kakao_setting["인증키"]) ? $kakao_setting["인증키"] : "";
        ?>
        Kakao.init("<?= $kakao_js_key ?>");
        <?php // =$kakao_key["인증키"]
        ?>
      }
      //alert('kakao web login');
      Kakao.Auth.login({
        scope: 'profile_nickname,profile_image,profile_name', // 스코프 추가: 이름, 별명, 프로필 이미지 접근 권한
        success: function(authObj) {
          console.log(authObj);
          //alert('success');
          // 로그인 성공시, API를 호출합니다.
          Kakao.API.request({
            url: '/v2/user/me',
            success: function(res) {
              // 카카오 사용자 정보 추출
              var kakaoid = res.id;
              // name 우선, 없으면 nickname 사용 (안드로이드 네이티브와 동일한 로직)
              var kakaoname = "";
              if (res.kakao_account && res.kakao_account.name && res.kakao_account.name.trim() !== "") {
                kakaoname = res.kakao_account.name;
              } else if (res.kakao_account && res.kakao_account.profile && res.kakao_account.profile.nickname && res.kakao_account.profile.nickname.trim() !== "") {
                kakaoname = res.kakao_account.profile.nickname;
              }
              var kakaophone = (res.kakao_account && res.kakao_account.phone_number) ? res.kakao_account.phone_number : "";
              var kakaogender = (res.kakao_account && res.kakao_account.gender) ? res.kakao_account.gender : "";
              var kakaobirthyear = (res.kakao_account && res.kakao_account.birthyear) ? res.kakao_account.birthyear : "";
              var kakaobirthday = (res.kakao_account && res.kakao_account.birthday) ? res.kakao_account.birthday : "";
              var kakaoemail = (res.kakao_account && res.kakao_account.email) ? res.kakao_account.email : "";

              // 전화번호 포맷 변환: +82 10-9002-5528 → 01090025528
              if (kakaophone) {
                kakaophone = kakaophone.replace(/\+82\s*10-/, '010').replace(/[-\s]/g, '');
              }

              // 성별 변환 (male -> MALE, female -> FEMALE)
              var gender = "";
              if (kakaogender == "male") {
                gender = "MALE";
              } else if (kakaogender == "female") {
                gender = "FEMALE";
              }

              // 서버로 전송 - PC 웹과 동일하게 7개 파라미터 전달
              var params = {};
              params["mm_join_type"] = "KAKAO";
              params["mm_join_key"] = kakaoid;
              params["kakaoid"] = kakaoid;
              params["kakaoname"] = kakaoname;
              params["kakaophone"] = kakaophone;
              params["kakaogender"] = gender;
              params["kakaobirthyear"] = kakaobirthyear;
              params["kakaobirthday"] = kakaobirthday;
              params["kakaoemail"] = kakaoemail;
              comAjax("/member_proc/login", params, function(ret) {
                if (ret.code == "00") {
                  sessionStorage.removeItem("chat_user_connect");

                  // 로그인 성공 후 FCM 토큰을 사용자와 연결 (Promise 완료 후 페이지 이동)
                  if (typeof window.connectFCMTokenToUser === 'function') {
                    window.connectFCMTokenToUser().then(function() {
                      _replace("/");
                    });
                  } else {
                    _replace("/");
                  }
                } else {
                  if (ret.code == "91") {
                    // 전화번호가 이미 가입되어 있는 경우
                    commonAlert(ret.msg, function() {
                      _replace("/member/login");
                    });
                  } else if (ret.code == "90") {
                    openPopup('confirm', {
                      id: 'permission',
                      content: '<strong>회원으로 가입된 정보가 없습니다.</strong><small>회원 가입을 진행하시겠습니까?</small>',
                      firstButton: '취소',
                      secondButton: '회원가입',
                      firstCallback: function() {
                        return false;
                      },
                      secondCallback: function() {
                        // 추가 정보와 함께 회원가입 페이지로 이동
                        var joinUrl = "/member/join_sns_start?join_key=" + kakaoid + "&join_type=KAKAO";
                        if (kakaoname) joinUrl += "&username=" + encodeURIComponent(kakaoname);
                        if (kakaoemail) joinUrl += "&email=" + encodeURIComponent(kakaoemail);
                        if (gender) joinUrl += "&gender=" + encodeURIComponent(gender);
                        if (kakaophone) joinUrl += "&phonenum=" + encodeURIComponent(kakaophone);
                        location.href = joinUrl;
                      },
                    });
                  } else {
                    commonAlert('(3)' + ret.msg, function() {});
                    return false;
                  }
                }
              });
            },
            fail: function(error) {
              alert('(2)' + JSON.stringify(error));
              return false;
            }
          });
        },
        fail: function(err) {
          alert('(1)' + JSON.stringify(err));
          return false;
        }
      });

    });

    //애플 로그인
    //$("#btnApple").click(function(){
    //alert("애플 로그인");
    //location.href="/";
    //});

    //애플 로그인
    $("#btnGuest").click(function() {
      //alert("비회원 둘러보기");
      location.href = "/";
    });

    $('#toggle-password').click(function() {
      const input = $('#mm_pwd');
      const type = input.attr('type') === 'password' ? 'text' : 'password';

      input.attr('type', type);
      if (type === 'password') {
        $('#eye_icon').attr('src', '/resource/shop/web/img/eye_close.png');
        $('#eye_icon').css('margin-top', '5px');
        $('#eye_icon').css('margin-right', '10px');
      } else {
        $('#eye_icon').attr('src', '/resource/shop/web/img/eye_view.png');
        $('#eye_icon').css('margin-top', '7px');
        $('#eye_icon').css('margin-right', '10px');
      }
    });

    function getCookie(name) {
      let matches = document.cookie.match(new RegExp("(?:^|; )" + name + "=([^;]*)"));
      return matches ? decodeURIComponent(matches[1]) : null;
    }

    // 쿠키에서 saved_id 값 가져오기
    let savedId = getCookie("saved_id");

    if (savedId && savedId != "") {
      $("#mm_id").val(savedId); // 아이디 입력 필드에 값 설정
      $("#idsave").prop("checked", true); // 체크박스 체크
    }

    if ('<?= $msg_code ?>' == '90') {
      commonAlert("회원정보를 찾을수 없습니다.", function() {});
    } else if ('<?= $msg_code ?>' == '91') {
      commonAlert("탈퇴한 회원입니다.", function() {});
    }

  }); //ready
  // 전화번호 로그인 페이지로 이동하는 함수
  function goPhoneLogin() {
    // 전화번호 로그인 페이지로 이동
    location.href = "/member/nowjob_login";
  }

  function goLogin() {
    //alert('go');
    if ($("[name=mm_id]").val() == "") {
      commonAlert("휴대폰번호를 입력해주세요.", function() {
        $("[name=mm_id]").focus();
      });
      return;
    }

    if ($("[name=mm_pwd]").val() == "") {
      commonAlert("비밀번호를 입력해주세요.", function() {
        $("[name=mm_pwd]").focus();
      });
      return;
    }

    //alert('go2');
    var params = {};
    params["mm_id"] = $("[name=mm_id]").val();
    params["mm_pwd"] = $("[name=mm_pwd]").val();
    params["idsave"] = 'N';
    if (document.getElementById("idsave").checked) {
      params["idsave"] = 'Y';
    }
    //alert('go3');
    
    comAjax("/member_proc/login", params, function(ret) {
      //alert(ret.code);
      if (ret.code == "00") {
        sessionStorage.removeItem("chat_user_connect");

        // 로그인 성공 후 FCM 토큰을 사용자와 연결 (Promise 완료 후 페이지 이동)
        if (typeof window.connectFCMTokenToUser === 'function') {
          window.connectFCMTokenToUser().then(function(result) {
            console.log('✅ connectFCMTokenToUser 완료, 결과:', result);
            _replace("/");
          }).catch(function(error) {
            console.error('❌ connectFCMTokenToUser 에러:', error);
            _replace("/"); // 에러가 나도 페이지 이동
          });
        } else {
          console.warn('⚠️ connectFCMTokenToUser 함수가 정의되지 않음');
          _replace("/");
        }
      } else {
        commonAlert(ret.msg, function() {});
      }
    });
  }

  function isIOSApp() {
    return (
      typeof window.webkit !== 'undefined' &&
      typeof window.webkit.messageHandlers !== 'undefined' &&
      typeof window.webkit.messageHandlers.kakaoLogin !== 'undefined'
    );
  }

  function isIOSAppNaver() {
    return (
      typeof window.webkit !== 'undefined' &&
      typeof window.webkit.messageHandlers !== 'undefined' &&
      typeof window.webkit.messageHandlers.naverLogin !== 'undefined'
    );
  }

  //alert('test');
  function onLoginSuccess(arg) {
    //kakao ok
    //alert('kakao ok');
    //alert(arg);
    alert('kakao ok:' + arg);
  }

  function onLoginInfo(message //kakaoid, kakaoname //, kakaophone, kakaogender, kakaobirthyear, kakaobirthday, kakaoemail
  ) {
    //alert('onLoginInfo');
    //kakao ok
    //alert('kakao info');
    //alert(message);
    //Optional(1234)^^nil^^^^
    //alert(kakaoid);
    //alert(kakaophone);
    //alert(kakaoemail);
    //alert('kakao info:' + kakaoid);
    //alert('kakao info:' + kakaoname);
    //alert('kakao info:' + kakaophone);
    //alert('kakao info:' + kakaogender);
    //alert('kakao info:' + kakaobirthyear);
    //alert('kakao info:' + kakaobirthday);
    //alert('kakao info:' + kakaoemail);
    //Optional("Optional(1234567890)")
    //Optional("nil")

    // ^ 기준으로 나누기
    var parts = message.split('^');
    // 누락된 코드 추가 2025-10-15
    var phone = parts[1]; //phone    +82 10-
    var gender = parts[2]; //gender   Male 있으면, Female
    var birthyear = parts[3] || ""; //year     생년
    var birthday = parts[4] || ""; //birthday 생일
    var email = parts[5]; //email    undefined
    var name = parts[6]; //name     undefined

    // Optional() 형식 처리 및 nil 처리
    var birthyear_clean = birthyear || "";
    var birthday_clean = birthday || "";
    if (birthyear_clean) {
      var match = birthyear_clean.match(/^Optional\((.+)\)$/);
      if (match) birthyear_clean = match[1];
      if (birthyear_clean === 'nil' || birthyear_clean === '') birthyear_clean = "";
    }
    if (birthday_clean) {
      var match = birthday_clean.match(/^Optional\((.+)\)$/);
      if (match) birthday_clean = match[1];
      if (birthday_clean === 'nil' || birthday_clean === '') birthday_clean = "";
    }

    // 전화번호 포맷 변환: +82 10-9002-5528 → 01090025528
    if (phone) {
      phone = phone.replace(/\+82\s*10-/, '010').replace(/[-\s]/g, '');
    }

    // 성별 변환 (Male/Female -> MALE/FEMALE)
    var genderConverted = "";
    if (gender && gender.indexOf('Male') >= 0) {
      genderConverted = "MALE";
    } else if (gender && gender.indexOf('Female') >= 0) {
      genderConverted = "FEMALE";
    }

    // 각 요소 처리
    var result = parts
      .map(item => {
        // "Optional(1234)" 형식이면 숫자만 추출
        let match = item.match(/^Optional\((\d+)\)$/);
        if (match) return match[1];
        // "nil" 또는 빈 문자열은 제외
        if (item === 'nil' || item === '') return null;
        return item;
      })
      .filter(item => item !== null);

    var params = {};
    params["mm_join_type"] = "KAKAO";
    params["mm_join_key"] = result[0];
    params["kakaoid"] = result[0];
    params["kakaoname"] = name || "";
    params["kakaophone"] = phone || "";
    params["kakaogender"] = genderConverted;
    params["kakaobirthyear"] = birthyear_clean || "";
    params["kakaobirthday"] = birthday_clean || "";
    params["kakaoemail"] = email || "";
    comAjax("/member_proc/login", params, function(ret) {
      if (ret.code == "00") {
        sessionStorage.removeItem("chat_user_connect");

        // 로그인 성공 후 FCM 토큰을 사용자와 연결 (Promise 완료 후 페이지 이동)
        if (typeof window.connectFCMTokenToUser === 'function') {
          window.connectFCMTokenToUser().then(function() {
            _replace("/");
          });
        } else {
          _replace("/");
        }
      } else {
        if (ret.code == "90") {
          openPopup('confirm', {
            id: 'permission',
            content: '<strong>회원으로 가입된 정보가 없습니다.</strong><small>회원 가입을 진행하시겠습니까?</small>',
            firstButton: '취소',
            secondButton: '회원가입',
            firstCallback: function() {

            },
            secondCallback: function() {
              // PASS 인증 없이 바로 회원가입 페이지로 이동
              // 생년월일 정보도 URL 파라미터로 전달
              var joinUrl = "/member/join_sns_start?join_key=" + result[0] +
                "&join_type=KAKAO" +
                "&email=" + encodeURIComponent(email || "") +
                "&gender=" + encodeURIComponent(gender || "") +
                "&phonenum=" + encodeURIComponent(phone || "") +
                "&username=" + encodeURIComponent(name || "") +
                "&birthyear=" + encodeURIComponent(birthyear_clean || "") +
                "&birthday=" + encodeURIComponent(birthday_clean || "");
              location.href = joinUrl;
            },
          });
        } else {
          commonAlert(ret.msg, function() {});
        }
      }
    });

    //alert('kakao end');
  }

  function onLoginFail(arg) {
    //kakao fail
    //alert('kakao fail');
    alert('카카오 로그인에 실패하였습니다.\n다른 방법으로 로그인 해주세요 ' + arg);
  }
</script>


<script type="text/javascript" src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/ko_KR/appleid.auth.js"></script>
<!--div id="appleid-signin" data-color="black" data-border="true" data-type="sign in" style="height:40px;margin-top:10px;"></div-->
<script type="text/javascript">
  AppleID.auth.init({
    clientId: 'com.nowapk.app.nowjob2.login',
    scope: 'name email',
    redirectURI: 'https://nowjob.co.kr/member/apple_login_callback',
    state: 'app_ecm_apple_sign_in',
    nonce: 'nounce',
    usePopup: true //or false defaults to false
  });

  //회원 인증 완료
  document.addEventListener('AppleIDSignInOnSuccess', (data) => {
    //로그인 성공시 실행
    var app_token = data.detail.authorization["id_token"];
    var decodedToken = parseJWT(app_token);
    var c_hash = decodedToken['c_hash'];
    var email = decodedToken['email'];
    var sub = decodedToken['sub'];
    //alert(sub);
    //$.request("/API/test", { b_no : business_code }, true, false, function (rst) {
    //	//회원가입 상태면 ret url 이동
    //
    //		});

    var params = {};
    params["mm_join_type"] = "APPLE";
    params["mm_join_key"] = sub;

    comAjax("/member_proc/login", params, function(ret) {
      if (ret.code == "00") {
        sessionStorage.removeItem("chat_user_connect");

        // 로그인 성공 후 FCM 토큰을 사용자와 연결 (Promise 완료 후 페이지 이동)
        if (typeof window.connectFCMTokenToUser === 'function') {
          window.connectFCMTokenToUser().then(function() {
            _replace("/");
          });
        } else {
          _replace("/");
        }
      } else {
        if (ret.code == "90") {
          openPopup('confirm', {
            id: 'permission',
            content: '<strong>회원으로 가입된 정보가 없습니다.</strong><small>회원 가입을 진행하시겠습니까?</small>',
            firstButton: '취소',
            secondButton: '회원가입',
            firstCallback: function() {

            },
            secondCallback: function() {
              // PASS 인증 없이 바로 회원가입 페이지로 이동
              location.href = "/member/join_sns_start?join_key=" + sub + "&join_type=" + "APPLE";
            },
          });
        } else {
          commonAlert(ret.msg, function() {});
        }
      }
    });
  });

  //로그인 오류처리
  document.addEventListener('AppleIDSignInOnFailure', (error) => {
    //handle error.
    alert("로그인에 실패했습니다. 다시 시도하세요.");
    //alert("내용:" + error.detail.error);
    location.reload();
  });

  //$(document).ready(function() {
  document.addEventListener('DOMContentLoaded', function() {
    // setTimeout(function() {
    $('#appleid-signin > div').each(function() {
      $(this).css({
        'min-width': '',
        'max-width': '',
        'min-height': '',
        'max-height': ''
      });
    });
    // }, 200);
  });

  // JWT 파싱
  function parseJWT(token) {
    let base64Url = token.split('.')[1];
    let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    let jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
      return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
    return JSON.parse(jsonPayload);
  }

  // ios all 용
  function onNaverLoginInfo(msg) {
    // alert('onNaverLoginInfo');
    //alert(msg); //test

    var parts = msg.split('^');
    var uniqueval = parts[0];
    var username = parts[1];
    var email = parts[2];
    var gender = parts[3];
    var phonenum = parts[4];
    var birthyear = parts[5] || ""; // iOS 앱에서 생년 전달 (있는 경우)
    var birthday = parts[6] || ""; // iOS 앱에서 생일 전달 (있는 경우)

    if (gender == 'M') gender = "남";
    if (gender == 'F') gender = "여";

    if (uniqueval.length > 10) {
      // 서버로 전송
      var params = {};
      params["mm_join_type"] = "NAVER";
      params["mm_join_key"] = uniqueval;
      params["naverphone"] = phonenum;
      params["navername"] = username;
      params["naveremail"] = email;
      params["navergender"] = gender;
      params["naverbirthyear"] = birthyear || ""; // iOS 앱에서 전달받은 생년
      params["naverbirthday"] = birthday || ""; // iOS 앱에서 전달받은 생일
      comAjax("/member_proc/login", params, function(ret) {
        if (ret.code == "00") {
          sessionStorage.removeItem("chat_user_connect");

          // 로그인 성공 후 FCM 토큰을 사용자와 연결 (Promise 완료 후 페이지 이동)
          if (typeof window.connectFCMTokenToUser === 'function') {
            window.connectFCMTokenToUser().then(function() {
              _replace("/");
            });
          } else {
            _replace("/");
          }
        } else {
          if (ret.code == "91") {
            // 전화번호가 이미 가입되어 있는 경우
            commonAlert(ret.msg, function() {
              _replace("/member/login");
            });
          } else if (ret.code == "90") {
            openPopup('confirm', {
              id: 'permission',
              content: '<strong>회원으로 가입된 정보가 없습니다.</strong><small>회원 가입을 진행하시겠습니까?</small>',
              firstButton: '취소',
              secondButton: '회원가입',
              firstCallback: function() {

              },
              secondCallback: function() {
                // PASS 인증 없이 바로 회원가입 페이지로 이동
                // 생년월일 정보도 URL 파라미터로 전달
                var joinUrl = "/member/join_sns_start?join_key=" + uniqueval +
                  "&join_type=NAVER" +
                  "&email=" + encodeURIComponent(email || "") +
                  "&gender=" + encodeURIComponent(gender || "") +
                  "&phonenum=" + encodeURIComponent(phonenum || "") +
                  "&username=" + encodeURIComponent(username || "") +
                  "&birthyear=" + encodeURIComponent(birthyear || "") +
                  "&birthday=" + encodeURIComponent(birthday || "");
                location.href = joinUrl;
              },
            });
          } else {
            commonAlert(ret.msg, function() {});
          }
        }
      });

    } else {
      alert("네이버 로그인 실패");
    }

  }

  // android app 용
  function kakaoLoginAndCallback(status, token) {
    if (status === "success") {
      //alert("카카오 로그인 성공: " + token);
      // token 서버로 전송하거나 처리
    } else {
      alert("카카오 로그인 실패(2) " + token);
    }
  }

  // Android 앱에서 호출됨
  function kakaoLoginAndCallback(status, id, name, phone, gender, birthyear, birthday, email) {
    if (status === "success") {
      //alert(
      //  "카카오 로그인 성공\n" +
      //  "ID: " + id + "\n" +
      //  "이름: " + name + "\n" +
      //  "전화번호: " + phone + "\n" +
      //  "성별: " + gender + "\n" +
      //  "출생연도: " + birthyear + "\n" +
      //  "생일: " + birthday + "\n" +
      //  "이메일: " + email
      //);

      // 전화번호 포맷 변환: +82 10-9002-5528 → 01090025528
      if (phone) {
        phone = phone.replace(/\+82\s*10-/, '010').replace(/[-\s]/g, '');
      }

      // 성별 변환 (Male/Female -> MALE/FEMALE)
      var genderConverted = "";
      if (gender && gender.indexOf('Male') >= 0) {
        genderConverted = "MALE";
      } else if (gender && gender.indexOf('Female') >= 0) {
        genderConverted = "FEMALE";
      }

      // 서버로 전송 - PC 웹과 동일하게 7개 파라미터 전달
      var params = {};
      params["mm_join_type"] = "KAKAO";
      params["mm_join_key"] = id;
      params["kakaoid"] = id;
      params["kakaoname"] = name || "";
      params["kakaophone"] = phone || "";
      params["kakaogender"] = genderConverted;
      params["kakaobirthyear"] = birthyear || "";
      params["kakaobirthday"] = birthday || "";
      params["kakaoemail"] = email || "";
      comAjax("/member_proc/login", params, function(ret) {
        if (ret.code == "00") {
          sessionStorage.removeItem("chat_user_connect");

          // 로그인 성공 후 FCM 토큰을 사용자와 연결 (Promise 완료 후 페이지 이동)
          if (typeof window.connectFCMTokenToUser === 'function') {
            window.connectFCMTokenToUser().then(function() {
              _replace("/");
            });
          } else {
            _replace("/");
          }
        } else {
          if (ret.code == "90") {
            openPopup('confirm', {
              id: 'permission',
              content: '<strong>회원으로 가입된 정보가 없습니다.</strong><small>회원 가입을 진행하시겠습니까?</small>',
              firstButton: '취소',
              secondButton: '회원가입',
              firstCallback: function() {

              },
              secondCallback: function() {
                // PASS 인증 없이 바로 회원가입 페이지로 이동
                location.href = "/member/join_sns_start?join_key=" + id + "&join_type=" + "KAKAO" + "&email=" + email + "&gender=" + gender + "&phonenum=" + phone + "&username=" + name;
              },
            });
          } else {
            commonAlert(ret.msg, function() {});
          }
        }
      });

    } else {
      alert("카카오 로그인 실패(1)");
    }
  }

  function naverLoginAnd() {
    APP_NOWAPK.naverLoginAnd(); // 네이티브 호출
  }

  function naverLoginAndCallback(status, id, name, email, phone, gender, birthyear, birthday) {
    if (status === 'success') {
      //alert("네이버 로그인 성공!\nID: " + id + "\n이름: " + name);

      // 서버로 전송
      var params = {};
      params["mm_join_type"] = "NAVER";
      params["mm_join_key"] = id;
      params["naverphone"] = phone;
      params["navername"] = name;
      params["naveremail"] = email;
      params["navergender"] = gender || ""; // 안드로이드에서 전달받은 성별 (M/F)
      params["naverbirthyear"] = birthyear || ""; // 안드로이드에서 전달받은 생년
      params["naverbirthday"] = birthday || ""; // 안드로이드에서 전달받은 생일 (MM-DD 형식)
      comAjax("/member_proc/login", params, function(ret) {
        if (ret.code == "00") {
          sessionStorage.removeItem("chat_user_connect");

          // 로그인 성공 후 FCM 토큰을 사용자와 연결 (Promise 완료 후 페이지 이동)
          if (typeof window.connectFCMTokenToUser === 'function') {
            window.connectFCMTokenToUser().then(function() {
              _replace("/");
            });
          } else {
            _replace("/");
          }
        } else {
          if (ret.code == "91") {
            // 전화번호가 이미 가입되어 있는 경우
            commonAlert(ret.msg, function() {
              _replace("/member/login");
            });
          } else if (ret.code == "90") {
            openPopup('confirm', {
              id: 'permission',
              content: '<strong>회원으로 가입된 정보가 없습니다.</strong><small>회원 가입을 진행하시겠습니까?</small>',
              firstButton: '취소',
              secondButton: '회원가입',
              firstCallback: function() {

              },
              secondCallback: function() {
                // PASS 인증 없이 바로 회원가입 페이지로 이동
                // 안드로이드 앱에서 네이버 로그인 시 받은 정보 전달
                var url = "/member/join_sns_start?join_key=" + id + "&join_type=NAVER";
                if (name) url += "&username=" + encodeURIComponent(name);
                if (email) url += "&email=" + encodeURIComponent(email);
                if (phone) url += "&phonenum=" + encodeURIComponent(phone);
                location.href = url;
              },
            });
          } else {
            commonAlert(ret.msg, function() {});
          }
        }
      });

    } else {
      alert("네이버 로그인 실패");
    }
  }

  // Android 앱에서 호출됨 (Google)
  function googleLoginAndCallback(status, idToken, email, name) {
    if (status !== "success") {
      alert("구글 로그인 실패");
      return;
    }

    $.post(
      "/member_proc/google_login",
      {
        id_token: idToken,
        email: email || "",
        name: name || ""
      },
      function(ret) {
        if (typeof ret === "string") {
          try {
            ret = JSON.parse(ret);
          } catch (e) {
            ret = { code: "99", msg: "응답 파싱 실패" };
          }
        }

        if (ret.code == "00") {
          sessionStorage.removeItem("chat_user_connect");

          // 로그인 성공 후 FCM 토큰을 사용자와 연결 (Promise 완료 후 페이지 이동)
          if (typeof window.connectFCMTokenToUser === 'function') {
            window.connectFCMTokenToUser().then(function() {
              _replace("/");
            });
          } else {
            _replace("/");
          }
        } else if (ret.code == "90" && ret.data && ret.data.join_key) {
          openPopup('confirm', {
            id: 'permission',
            content: '<strong>회원으로 가입된 정보가 없습니다.</strong><small>회원 가입을 진행하시겠습니까?</small>',
            firstButton: '취소',
            secondButton: '회원가입',
            firstCallback: function() {
              return false;
            },
            secondCallback: function() {
              var joinUrl = "/member/join_sns_start?join_key=" + encodeURIComponent(ret.data.join_key) +
                "&join_type=GOOGLE";
              if (email) joinUrl += "&email=" + encodeURIComponent(email);
              if (name) joinUrl += "&username=" + encodeURIComponent(name);
              location.href = joinUrl;
            },
          });
        } else {
          commonAlert(ret.msg || "로그인 실패", function() {});
        }
      },
      "json"
    ).fail(function() {
      commonAlert("로그인 실패", function() {});
    });
  }

  /*
  window.onload = function() {
      if (typeof APP_NOWAPK !== 'undefined') {
          APP_NOWAPK.kakaoLoginAnd();
      } else {
          console.error("APP_NOWAPK가 정의되어 있지 않음");
      }
  };
  */
  // alert(navigator.userAgent.toLowerCase());
</script>
