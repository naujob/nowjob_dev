<?php require_once __DIR__ . '/../_partial/bottom_sheet_helpers.php'; ?>
<div class="page page-login">
  <div class="login-bg-img"></div>
  <style>
    /* 배경 이미지 CSS (img 태그 대신 배경으로 처리 - preload와 함께 사용) */
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

    /* 로그인 폼을 배경 위에 표시 */
    .page-login .login-form-wrap {
      position: relative;
      z-index: 1;
      margin-top: 10px;
    }

    /* .vendor-login .vendor-type3:before {
      background-image: url(/resource/shop/mobile/img/ic_login_naver.png);
    }

    .vendor-login .vendor-type3 {
      background: #02c75a;
    } */

    /* 로그인 태그라인 */
    .login-tagline {
      text-align: center;
      margin: 20px 0 0px;
      position: relative;
      z-index: 1;
    }

    .login-tagline .tagline-main {
      font-size: 27px;
      font-weight: 600;
      color: #fff;
      line-height: 1.4;
      margin: 0 0 12px 0;
    }

    .login-tagline .tagline-sub {
      font-size: 17px;
      color: #fff;
      margin: 0;
      font-weight: 500;
    }

    /* 로그인 로딩 오버레이 */
    #login-loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      display: none;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      z-index: 9999;
      gap: 20px;
    }

    #login-loading-overlay[data-visible="true"] {
      display: flex;
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 4px solid rgba(255, 255, 255, 0.3);
      border-top: 4px solid #fff;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    .loading-text {
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      margin: 0;
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

  // os_type이 비어 있으면 User-Agent로 판별하여 보정
  // ⚠️ 중요: iOS 앱과 iOS 웹브라우저를 구분해야 함
  if (!isset($os_type) || $os_type === '') {
    if ($iosapp) {
      // iOS 네이티브 앱
      $os_type = 'ios';
    } else if ($isIosBrowser) {
      // iOS 웹브라우저 (Safari 등)
      // 웹브라우저는 아무 플랫폼도 아님 (web으로 처리)
      $os_type = 'web';
    }
  }
  // echo "end";
  ?>
  <img src="/resource/shop/mobile/img/member/ic-nowjob-logo.svg" alt="NowJob Logo" class="login-logo login-logo-centered">
  <div class="login-tagline">
    <p class="tagline-main">지금 나우잡에서<br>일자리를 찾아보세요</p>
    <p class="tagline-sub">알바부터 정직원까지</p>
  </div>
  <!-- 로그인 로딩 오버레이 -->
  <div id="login-loading-overlay" data-visible="false">
    <div class="loading-spinner"></div>
    <p class="loading-text">로그인 중 입니다</p>
  </div>

  <div class="login-form-wrap">
    <div class="login-form">
      <div class="form-container">
        <!-- 최근 로그인 SNS 판별: 뷰에서 배지 표시용 (data-recent 속성 할당)
             우선순위: 세션 > 쿠키 (세션이 없으면 쿠키에서 읽음) -->
        <?php
        $recent_login_type = "";
        $allowed_recent_types = array("KAKAO", "NAVER", "GOOGLE", "WECHAT", "WEB");
        // 세션에서 최근 로그인 타입 조회
        $session_value = $this->common->getSession("last_login_type");
        // 세션이 없으면 쿠키에서 조회
        $login_type_value = $session_value !== null ? $session_value : (isset($_COOKIE["last_login_type"]) ? $_COOKIE["last_login_type"] : null);

        // 허용된 로그인 타입인지 검증 후 저장
        if ($login_type_value !== null && in_array($login_type_value, $allowed_recent_types)) {
            $recent_login_type = $login_type_value;
        }
        ?>

        <form id="frm" onsubmit="return false">
          <!-- 휴대폰번호 OTP 로그인 영역 -->
          <div class="form-row" id="phone-login-section">
            <label>
              <span class="ico-label phone">휴대폰번호</span>
              <input type="text" id="phone-login-input" placeholder="010-0000-0000" autocomplete="off" class="form-text">
            </label>
          </div>

          <button id="btnPhoneLogin" type="button" class="btn-primary nowjob-login" data-state="idle"></button>

          <!-- 간편로그인 구분선 -->
          <div class="login-divider">
            <span class="login-divider-text">간편로그인</span>
          </div>

          <div class="vendor-login">

            <?php
            $app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');

            // OAuth redirect_uri는 항상 HTTPS여야 함
            if (strpos($app_base_url, 'http://') === 0) {
              $app_base_url = 'https://' . substr($app_base_url, 7);
            }

            // redirect_uri 경로 생성 (URL 인코딩 하지 않음 - OAuth URL 생성 시 자동 인코딩됨)
            // 모든 redirect_uri를 app_base_url 기반으로 통일
            $apple_redirect = $app_base_url . "/member/apple_login_callback";
            $kakao_redirect = $app_base_url . "/member/kakao_login_callback";
            $naver_redirect = $app_base_url . "/member/naver_login_callback";
            $google_redirect = $app_base_url . "/member/google_login_callback";

            $apple_login = "https://appleid.apple.com/auth/authorize?client_id=com.nowapk.app.nowjob2.login&redirect_uri=" . $apple_redirect . "&response_type=code id_token&state=app_ecm_apple_sign_in&scope=name email&response_mode=form_post";

            // 카카오 설정
            $kakao_setting = getSettingInfo(["st_group" => "카카오"]);
            $kakao_client_id = isset($kakao_setting["REST_API_KEY"]) ? $kakao_setting["REST_API_KEY"] : "";
            // Kakao state 세션에 저장 (콜백에서 검증 시 사용)
            $this->common->setSession("kakao-oauth-state", $csrf);
            $kakao_login = "https://kauth.kakao.com/oauth/authorize?response_type=code&client_id=" . $kakao_client_id . "&redirect_uri=" . $kakao_redirect . "&state=" . $csrf;

            // 네이버 설정 (웹 버전과 동일한 로직)
            $naver_setting = getSettingInfo(["st_group" => "네이버"]);
            $naver_client_id = isset($naver_setting["Client ID"]) ? $naver_setting["Client ID"] : "";
            if (!empty($naver_client_id)) {
              // 웹 버전과 동일: urlencode된 redirect_uri 사용
              $naver_redirect_encoded = urlencode($naver_redirect);
              $unique_state = $csrf . "_" . time() . "_" . mt_rand(1000, 9999);
              // 네이버 state 세션에 저장 (콜백에서 검증 시 사용)
              $this->common->setSession("naver-oauth-state", $unique_state);
              $naver_login = "https://nid.naver.com/oauth2.0/authorize?client_id=" . $naver_client_id . "&redirect_uri=" . $naver_redirect_encoded . "&response_type=code&state=" . urlencode($unique_state) . "&prompt=login&auth_type=reauthenticate";
            } else {
              $naver_login = "";
            }

            // 구글 설정 (웹 버전과 동일한 로직)
            $google_setting = getSettingInfo(["st_group" => "구글"]);
            $google_client_id = isset($google_setting["WEB_CLIENT_ID"]) ? $google_setting["WEB_CLIENT_ID"] : "";
            $google_login = !empty($google_client_id) ? ($app_base_url . "/member/google_login") : "";

            // 위챗 설정 (서버 주도 OAuth 시작)
            $wechat_setting = getSettingInfo(["st_group" => "위챗"]);
            $wechat_app_id = isset($wechat_setting["WEB_APP_ID"]) ? $wechat_setting["WEB_APP_ID"] : "";
            $wechat_login = !empty($wechat_app_id) ? ($app_base_url . "/member/wechat_login") : "";
            ?>
            <!-- 간편로그인 아이콘 버튼 (플랫폼별 다르게 렌더링)
                 data-recent: 최근 로그인한 SNS에 배지 표시 (CSS에서 관리) -->
            <div class="vendor-login-icons">
              <?php if ($os_type == "android") : ?>
                <!-- 안드로이드 앱: JavaScript 이벤트 핸들러로 native 코드 호출 -->
                <a href="javascript:void(0);" id="btnKakaoAndroid" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'KAKAO' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-kakao.png')"></a>
              <?php else : ?>
                <!-- iOS 앱 또는 웹: OAuth 서버 URL로 직접 리다이렉트 -->
                <a href="<?= $kakao_login ?>" id="btnKakao" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'KAKAO' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-kakao.png')"></a>
              <?php endif; ?>

              <?php if (!empty($wechat_login)) : ?>
                <a href="<?= $wechat_login ?>" id="btnWeChat" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'WECHAT' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-wechat.png')"></a>
              <?php else : ?>
                <a href="javascript:void(0);" id="btnWeChat" class="vendor-icon-btn" data-recent="false" style="background-image: url('/resource/shop/mobile/img/member/login-sns-wechat.png')"></a>
              <?php endif; ?>

              <?php if ($iosapp) { ?>
                <!-- iOS 네이티브 앱: OAuth URL로 직접 리다이렉트 -->
                <a href="<?= $naver_login ?>" id="btnNaver" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'NAVER' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-naver.png')"></a>
              <?php } else if ($os_type == "android") { ?>
                <!-- 안드로이드 네이티브 앱: JavaScript Bridge(APP_NOWAPK)로 native SDK 호출 -->
                <a href="javascript:void(0);" id="btnNaver" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'NAVER' ? 'true' : 'false' ?>" onclick="APP_NOWAPK.naverLoginAnd(); return false;" style="background-image: url('/resource/shop/mobile/img/member/login-sns-naver.png')"></a>
              <?php } else { ?>
                <!-- 웹브라우저: OAuth URL 설정 여부 확인 후 렌더링 -->
                <?php if (!empty($naver_login)) { ?>
                  <a href="<?= $naver_login ?>" id="btnNaver" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'NAVER' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-naver.png')"></a>
                <?php } else { ?>
                  <!-- 네이버 로그인 미설정 → 경고 메시지 표시 -->
                  <a href="javascript:void(0);" id="btnNaver" class="vendor-icon-btn" data-recent="false" onclick="alert('네이버 로그인이 설정되지 않았습니다.'); return false;" style="background-image: url('/resource/shop/mobile/img/member/login-sns-naver.png')"></a>
                <?php } ?>
              <?php } ?>

              <a href="javascript:void(0);" id="btnGoogle" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'GOOGLE' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-google.png')"></a>
            </div>

            <!-- <a href="#" id="btnKakaoAndApp" class="btn-primary vendor-type1" onclick="alert(1); APP_NOWAPK.kakaoLoginAnd(); alert(2); return false;">카카오톡으로 로그인(android app)</a>
                    <a href="#" id="btnKakaoAndApp2" class="btn-primary vendor-type1" onclick="alert(1); APP_NOWAPK.callVibrate(); alert(2); return false;">진동</a> -->

            <!-- Apple 로그인 버튼 주석 처리 -->
            <?php if ($os_type == "ios" || $isIosBrowser) : ?>
              <!-- <a href="<?= $apple_login ?>" id="btnApple" class="btn-primary vendor-type2">Apple로 로그인</a> -->
            <?php endif; ?>

            <? //=$os_type
            ?>
          </div>

        </form>

        <!--
            <button onclick="window.APP_NOWAPK.kakaoLoginAnd()">카카오 로그인</button>
            <button onclick="window.APP_NOWAPK.kakaoLoginAnd()">카카오 로그인</button>
            <button onclick="APP_NOWAPK.kakaoLoginAnd()">카카오 로그인</button>
            <button onclick="testKakaoLogin()">카카오 로그인 테스트</button>
            <button onclick="NowJobBridge.getLocation()">Location</button>
          -->
      </div>
    </div>
    <div class="try-trial-container">
      <p class="try-trial"><a href="/member/join_start">회원가입</a></p>
      <a href="/recruit/job_list" class="btn-primary btn-signup" style="background-image: none; background-color: rgba(10, 10, 10, 0.51); color: white; font-size: 20px; font-weight: 600; display: flex; align-items: center; justify-content: center;">나우잡 둘러보기</a>
    </div>
  </div>
</div>


<script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script>
<script src="/resource/shop/mobile/js/logger.js"></script>
<script>
  // 로그인 로딩 오버레이 제어
  function showLoginLoading() {
    $("#login-loading-overlay").attr("data-visible", "true");
  }

  function hideLoginLoading() {
    $("#login-loading-overlay").attr("data-visible", "false");
  }

  // 로그인 성공 후 안전하게 홈으로 이동 (토큰 저장 검증)
  function navigateHomeAfterLogin(loginType) {
    // 응답 처리 대기 (쿠키/세션 저장)
    setTimeout(function() {
      // 최근 로그인 타입을 백그라운드에서 저장 (실패해도 로그만 남김)
      comAjax("/member_proc/set_last_login_type", { login_type: loginType });

      // FCM 토큰을 백그라운드에서 연결 (에러는 콘솔에 기록)
      if (typeof window.connectFCMTokenToUser === 'function') {
        window.connectFCMTokenToUser().catch(function(error) {
          console.warn('FCM 토큰 연결 실패:', error);
        });
      }

      // 홈으로 이동
      _replace("/");
    }, 100);
  }

  // 페이지 로드
  window.addEventListener('load', function() {});

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
    // 휴대폰 로그인 버튼 클릭
    $("#btnPhoneLogin").click(function(e) {
      e.preventDefault();
      e.stopPropagation();

      var state = $(this).data("state");
      if (state === "idle") {
        checkPhoneLogin();
      }
      return false;
    });

    // 휴대폰번호 입력시 정규화
    $("#phone-login-input").on("keyup", function() {
      var phone = $(this).val().replace(/[^0-9]/g, "");
      $(this).val(phone);
    });

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

    // 구글 로그인: 플랫폼별로 다르게 처리 (안드로이드/iOS 앱은 네이티브 SDK, 웹은 OAuth)
    $("#btnGoogle").click(function(e) {
      e.preventDefault();
      e.stopPropagation();

      <?php if ($os_type == "android") { ?>
        // 안드로이드 네이티브 앱: JavaScript Bridge로 native Google SDK 호출
        if (typeof APP_NOWAPK !== 'undefined' && typeof APP_NOWAPK.googleLoginAnd === 'function') {
          APP_NOWAPK.googleLoginAnd();
        } else {
          alert("구글 로그인을 사용할 수 없습니다. 앱을 최신 버전으로 업데이트해주세요.");
        }
      <?php } else if ($os_type == "ios") { ?>
        // iOS 네이티브 앱: webkit 메시지 핸들러로 native 코드 호출
        if (typeof window.webkit !== 'undefined' &&
          typeof window.webkit.messageHandlers !== 'undefined' &&
          typeof window.webkit.messageHandlers.googleLogin !== 'undefined') {
          window.webkit.messageHandlers.googleLogin.postMessage('');
        } else {
          alert("구글 로그인을 사용할 수 없습니다. 앱을 최신 버전으로 업데이트해주세요.");
        }
      <?php } else { ?>
        // 모바일 웹브라우저: 구글 OAuth 서버로 리다이렉트
        <?php if (!empty($google_login)) { ?>
          window.location.href = "<?= $google_login ?>";
        <?php } else { ?>
          alert("구글 로그인이 설정되지 않았습니다.");
        <?php } ?>
      <?php } ?>
      return false;
    });

    // 위챗 로그인: 네이티브 앱(iOS/Android)에서만 지원 (웹 미지원)
    <?php if ($os_type == "android" || $os_type == "ios") : ?>
      $("#btnWeChat").click(function(e) {
        e.preventDefault();
        e.stopPropagation();

        <?php if ($os_type == "android") { ?>
          // 안드로이드 네이티브 앱: JavaScript Bridge로 native WeChat SDK 호출
          if (typeof APP_NOWAPK !== 'undefined' && typeof APP_NOWAPK.wechatLoginAnd === 'function') {
            APP_NOWAPK.wechatLoginAnd();
          } else {
            alert("위챗 로그인을 사용할 수 없습니다. 앱을 최신 버전으로 업데이트해주세요.");
          }
        <?php } else if ($os_type == "ios") { ?>
          // iOS 네이티브 앱: webkit 메시지 핸들러로 native 코드 호출
          if (typeof window.webkit !== 'undefined' &&
            typeof window.webkit.messageHandlers !== 'undefined' &&
            typeof window.webkit.messageHandlers.wechatLoginIos !== 'undefined') {
            window.webkit.messageHandlers.wechatLoginIos.postMessage('');
          } else {
            alert("위챗 로그인을 사용할 수 없습니다. 앱을 최신 버전으로 업데이트해주세요.");
          }
        <?php } ?>
        return false;
      });
    <?php endif; ?>

    // 카카오 로그인: 플랫폼별(iOS 앱/안드로이드 앱/웹)로 다르게 처리
    $("#btnKakao").click(function() {
      // iOS 네이티브 앱 → JavaScript Bridge를 통해 네이티브 코드 호출
      if (isIOSApp()) {
        window.webkit.messageHandlers.kakaoLogin.postMessage(null);
        return false;
      }

      // 안드로이드 네이티브 앱 → JavaScript Bridge(APP_NOWAPK) 호출
      <?php if ($os_type == "android") { ?>
        APP_NOWAPK.kakaoLoginAnd();
        return false;
      <?php } ?>

      // 모바일 웹브라우저 환경 → 카카오 공식 SDK 직접 사용
      if (!Kakao.isInitialized()) {
        <?php
        $kakao_setting = getSettingInfo(["st_group" => "카카오"]);
        $kakao_js_key = isset($kakao_setting["인증키"]) ? $kakao_setting["인증키"] : "";
        ?>
        Kakao.init("<?= $kakao_js_key ?>");
      }

      Kakao.Auth.login({
        scope: 'profile_nickname,profile_image,profile_name', // 요청 권한: 이름, 별명, 프로필 이미지
        success: function(authObj) {
          // 로그인 성공 → 사용자 프로필 정보 API 요청
          Kakao.API.request({
            url: '/v2/user/me',
            success: function(res) {
              // 카카오 응답에서 사용자 정보 추출 (이름, 이메일, 휴대폰, 성별, 생일)
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
                  navigateHomeAfterLogin("KAKAO");
                  return;
                } else {
                  hideLoginLoading();
                  if (ret.code == "91") {
                    // 중복 계정 - 다른 SNS로 이미 가입됨
                    if (ret.data && ret.data.join_type) {
                      showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "");
                    } else {
                      commonAlert(ret.msg, function() {
                        _replace("/member/login");
                      });
                    }
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
            _replace("/");
          }).catch(function(error) {
            _replace("/"); // 에러가 나도 페이지 이동
          });
        } else {
          _replace("/");
        }
      } else {
        commonAlert(ret.msg, function() {});
      }
    });
  }

  // iOS 네이티브 앱 여부 판별 (webkit 메시지 핸들러 존재 확인)
  function isIOSApp() {
    return (
      typeof window.webkit !== 'undefined' &&
      typeof window.webkit.messageHandlers !== 'undefined' &&
      typeof window.webkit.messageHandlers.kakaoLogin !== 'undefined'
    );
  }

  // iOS 네이티브 앱에서 네이버 로그인 지원 여부 확인
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

  // iOS 네이티브 앱에서 호출: 카카오 로그인 결과 수신 (webView.evaluateJavaScript 사용)
  // 파라미터: iOS 앱에서 "^" 구분자로 연결된 문자열 (ID^폰^성별^생년^생일^이메일^이름)
  function onLoginInfo(message) {
    showLoginLoading();

    // iOS 앱에서 받은 형식: "Optional(123456)^+82 10-1234-5678^Male^1990^01-15^email@test.com^사용자이름"

    // "^" 구분자로 사용자 정보 파싱
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
        navigateHomeAfterLogin("KAKAO");
      } else {
        hideLoginLoading();
        if (ret.code == "91") {
          // 중복 계정 - 다른 SNS(또는 휴대폰)로 이미 가입됨
          if (ret.data && ret.data.join_type) {
            showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "");
          } else {
            commonAlert(ret.msg, function() {});
          }
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
    hideLoginLoading();
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
    showLoginLoading();

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
        navigateHomeAfterLogin("APPLE");
      } else {
        hideLoginLoading();
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
    hideLoginLoading();
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

  // iOS 네이티브 앱에서 호출: 네이버 로그인 결과 수신 (webView.evaluateJavaScript 사용)
  // 파라미터: iOS 앱에서 "^" 구분자로 연결된 문자열 (ID^이름^이메일^성별^휴대폰^생년^생일)
  function onNaverLoginInfo(msg) {
    showLoginLoading();

    // iOS 앱에서 받은 형식: "123456^사용자이름^email@test.com^M^010-1234-5678^1990^01-15"

    var parts = msg.split('^');
    var uniqueval = parts[0];
    var username = parts[1];
    var email = parts[2];
    var gender = parts[3];
    var phonenum = parts[4];
    var birthyear = parts[5] || ""; // iOS 앱에서 전달한 생년 (선택사항)
    var birthday = parts[6] || ""; // iOS 앱에서 전달한 생일 (선택사항)

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
          navigateHomeAfterLogin("NAVER");
          return;
        } else {
          hideLoginLoading();
          if (ret.code == "91") {
            // 중복 계정 - 다른 SNS로 이미 가입됨
            if (ret.data && ret.data.join_type) {
              showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "");
            } else {
              commonAlert(ret.msg, function() {
                _replace("/member/login");
              });
            }
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
      hideLoginLoading();
      alert("네이버 로그인 실패");
    }

  }

  // 카카오 로그인 콜백: 안드로이드 네이티브 앱(IntroActivity.kakaoLoginStart)에서 호출됨
  // 파라미터: 로그인 상태, 카카오 ID, 이름, 휴대폰, 성별, 생년, 생일, 이메일
  function kakaoLoginAndCallback(status, id, name, phone, gender, birthyear, birthday, email) {
    if (status === "success") {
      showLoginLoading();

      // 로그인 성공 → 사용자 정보를 서버에 전송하여 로그인/회원가입 처리

      // 전화번호 포맷 정규화: "+82 10-9002-5528" → "01090025528"
      if (phone) {
        phone = phone.replace(/\+82\s*10-/, '010').replace(/[-\s]/g, '');
      }

      // 성별 변환: 안드로이드 앱에서 "Male"/"Female" 형식으로 받음 → "MALE"/"FEMALE"로 변환
      var genderConverted = "";
      if (gender && gender.indexOf('Male') >= 0) {
        genderConverted = "MALE";
      } else if (gender && gender.indexOf('Female') >= 0) {
        genderConverted = "FEMALE";
      }

      // 서버로 전송 - 사용자 정보 7개 파라미터 포함
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
          navigateHomeAfterLogin("KAKAO");
          return;
        } else {
          if (ret.code == "91") {
            // 중복 계정 - 다른 SNS로 이미 가입됨
            if (ret.data && ret.data.join_type) {
              showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "");
            } else {
              commonAlert(ret.msg, function() {});
            }
          } else if (ret.code == "90") {
            hideLoginLoading();
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
            hideLoginLoading();
            commonAlert(ret.msg, function() {});
          }
        }
      });

    } else {
      hideLoginLoading();
      alert("카카오 로그인 실패(1)");
    }
  }

  function naverLoginAnd() {
    APP_NOWAPK.naverLoginAnd(); // 네이티브 호출
  }

  // 네이버 로그인 콜백: 안드로이드 네이티브 앱(IntroActivity.naverLoginStart)에서 호출됨
  // 파라미터: 로그인 상태, 네이버 ID, 이름, 이메일, 휴대폰, 성별, 생년, 생일
  function naverLoginAndCallback(status, id, name, email, phone, gender, birthyear, birthday) {
    if (status === 'success') {
      showLoginLoading();

      // 로그인 성공 → 사용자 정보를 서버에 전송하여 로그인/회원가입 처리
      var params = {};
      params["mm_join_type"] = "NAVER";
      params["mm_join_key"] = id;
      params["naverphone"] = phone;
      params["navername"] = name;
      params["naveremail"] = email;
      params["navergender"] = gender || ""; // 안드로이드에서 전달받은 성별
      params["naverbirthyear"] = birthyear || ""; // 생년
      params["naverbirthday"] = birthday || ""; // 생일
      comAjax("/member_proc/login", params, function(ret) {
        if (ret.code == "00") {
          sessionStorage.removeItem("chat_user_connect");
          navigateHomeAfterLogin("NAVER");
          return;
        } else {
          if (ret.code == "91") {
            // 중복 계정 - 다른 SNS로 이미 가입됨
            if (ret.data && ret.data.join_type) {
              showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "");
            } else {
              commonAlert(ret.msg, function() {
                _replace("/member/login");
              });
            }
          } else if (ret.code == "90") {
            hideLoginLoading();
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
            hideLoginLoading();
            commonAlert(ret.msg, function() {});
          }
        }
      });

    } else {
      hideLoginLoading();
      alert("네이버 로그인 실패");
    }
  }

  // 구글 로그인 콜백: 안드로이드 네이티브 앱(IntroActivity.googleLoginStart)에서 호출됨
  // 파라미터: 로그인 상태, Google ID Token, 이메일, 이름
  function googleLoginAndCallback(status, idToken, email, name) {
    if (status !== "success") {
      hideLoginLoading();
      alert("구글 로그인 실패");
      return;
    }

    showLoginLoading();

    // 구글 ID Token을 서버에 전송하여 검증 및 로그인/회원가입 처리
    $.post(
      "/member_proc/google_login", {
        id_token: idToken,
        email: email || "",
        name: name || ""
      },
      function(ret) {
        if (typeof ret === "string") {
          try {
            ret = JSON.parse(ret);
          } catch (e) {
            ret = {
              code: "99",
              msg: "응답 파싱 실패"
            };
          }
        }

        if (ret.code == "00") {
          sessionStorage.removeItem("chat_user_connect");
          navigateHomeAfterLogin("GOOGLE");
          return;
        } else if (ret.code == "91" && ret.data && ret.data.join_type) {
          showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "");
        } else if (ret.code == "90" && ret.data && ret.data.join_key) {
          try {
            localStorage.setItem("join_sns_type", "GOOGLE");
            localStorage.setItem("join_sns_key", ret.data.join_key);
            sessionStorage.setItem("join_sns_type", "GOOGLE");
            sessionStorage.setItem("join_sns_key", ret.data.join_key);
            document.cookie = "join_sns_type=GOOGLE; path=/; max-age=86400";
            document.cookie = "join_sns_key=" + encodeURIComponent(ret.data.join_key) + "; path=/; max-age=86400";
          } catch (e) {}
          // 전화번호 인증 팝업 표시 (구글 회원가입 전 전화번호 중복 확인 및 인증)
          openGoogleJoinPhonePopup(ret.data.join_key, email, name);
        } else {
          hideLoginLoading();
          commonAlert(ret.msg || "로그인 실패", function() {});
        }
      },
      "json"
    ).fail(function() {
      hideLoginLoading();
      commonAlert("로그인 실패", function() {});
    });
  }

  // 위챗 로그인 콜백: 안드로이드 네이티브 앱(HomeActivity.onWeChatLoginCallback)에서 호출됨
  // 파라미터: 로그인 상태, WeChat 임시 코드 (서버에서 access token 교환용)
  function onWeChatLoginAndCallback(status, code) {
    if (status !== 'success' || !code) {
      hideLoginLoading();
      alert("위챗 로그인에 실패했습니다.");
      return;
    }

    showLoginLoading();

    // 위챗 임시 코드를 서버에 전송 → 서버에서 access token 교환 및 사용자 정보 조회
    $.post(
      "/member_proc/wechat_login", {
        code: code
      },
      function(ret) {
        if (typeof ret === "string") {
          try {
            ret = JSON.parse(ret);
          } catch (e) {
            ret = {
              code: "99",
              msg: "응답 파싱 실패"
            };
          }
        }

        if (ret.code == "00") {
          sessionStorage.removeItem("chat_user_connect");
          navigateHomeAfterLogin("WECHAT");
          return;
        } else if (ret.code == "90" && ret.data && ret.data.join_key) {
          try {
            localStorage.setItem("join_sns_type", "WECHAT");
            localStorage.setItem("join_sns_key", ret.data.join_key);
            sessionStorage.setItem("join_sns_type", "WECHAT");
            sessionStorage.setItem("join_sns_key", ret.data.join_key);
            document.cookie = "join_sns_type=WECHAT; path=/; max-age=86400";
            document.cookie = "join_sns_key=" + encodeURIComponent(ret.data.join_key) + "; path=/; max-age=86400";
          } catch (e) {}
          // 구글과 동일한 전화번호 인증 팝업 재사용 (gPhoneJoinType=WECHAT)
          openGoogleJoinPhonePopup(ret.data.join_key, "", ret.data.username || "", "WECHAT");
        } else {
          hideLoginLoading();
          commonAlert(ret.msg || "로그인 실패", function() {});
        }
      },
      "json"
    ).fail(function() {
      hideLoginLoading();
      commonAlert("로그인 실패", function() {});
    });
  }

  // 위챗 로그인 콜백 (iOS): iOS 네이티브 앱(WXApiDelegate.onResp)에서 호출됨
  // 파라미터: 로그인 상태, WeChat 임시 코드
  // → 안드로이드와 동일한 로직으로 처리 (코드 통일)
  function onWeChatLoginIosCallback(status, code) {
    onWeChatLoginAndCallback(status, code);
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

  /* =========================================================
   * 구글 회원가입 전화번호 인증 팝업
   * - 전화번호가 DB에 존재하면 "이미 가입된 회원" 알림
   * - 전화번호가 없으면 뿌리오 SMS 인증 후 회원가입 진행
   * ========================================================= */
  var gPhoneJoinKey = "";
  var gPhoneJoinType = ""; // 소셜 로그인 타입 (GOOGLE, WECHAT 등) - 팝업 열 때 설정
  var gPhoneEmail = "";
  var gPhoneName = "";
  var gPhoneTimer;
  var gPhoneRemainTime = 180;
  var gPhoneIsAuth = false;

  /**
   * Android SMS Retriever에서 인증번호 수신 시 자동 호출되는 브릿지 함수
   * @param {string} code  추출된 6자리 인증번호
   */
  function fillAuthCode(code) {
    var inputField = document.getElementById('gphone-auth-input');
    if (inputField) {
      inputField.value = code;
    }
  }

  /**
   * 구글 회원가입 전화번호 인증 팝업 열기
   * @param {string} joinKey  구글 join_key
   * @param {string} email    구글 이메일
   * @param {string} name     구글 이름
   */
  /**
   * 구글 회원가입 전화번호 인증 팝업 열기 — bottom sheet 애니메이션
   */
  function openGoogleJoinPhonePopup(joinKey, email, name, joinType) {
    gPhoneJoinKey = joinKey;
    gPhoneJoinType = joinType || "GOOGLE";
    gPhoneEmail = email || "";
    gPhoneName = name || "";

    // 로그인 유형에 따라 안내 문구 변경
    var typeLabel = gPhoneJoinType === "WECHAT" ? "위챗" : "구글";
    $("#google-phone-popup p").first().text(typeLabel + " 회원가입을 위해 전화번호 인증이 필요합니다.");
    gPhoneIsAuth = false;

    // 팝업 초기화
    $("#gphone-input").val("");
    $("#gphone-auth-input").val("");
    $("#gphone-remain-time").html("0 : 00");
    $(".gphone-error").addClass("hidden");
    $(".gphone-auth-row").addClass("hidden");
    $("#gphone-btn-auth").css("background-color", "#ccc");
    $("#gphone-btn-join").prop("disabled", true);

    // bottom에서 부드럽게 올라오는 애니메이션
    $("#google-phone-popup").css({
      display: "block",
      background: "rgba(0,0,0,0)"
    });
    setTimeout(function() {
      $("#google-phone-popup").css("background", "rgba(0,0,0,0.5)");
      $("#google-phone-sheet").css("transform", "translateY(0)");
    }, 10);
  }

  /** 팝업 닫기 — bottom으로 내려가는 애니메이션 후 display:none */
  function closeGoogleJoinPhonePopup() {
    $("#google-phone-popup").css("background", "rgba(0,0,0,0)");
    $("#google-phone-sheet").css("transform", "translateY(100%)");
    setTimeout(function() {
      $("#google-phone-popup").css("display", "none");
    }, 350);
  }

  /**
   * 자동으로 사라지는 토스트 메시지
   * @param {string} msg       표시할 메시지
   * @param {number} duration  표시 시간(ms), 기본 2000
   */
  function gPhoneToast(msg, duration) {
    duration = duration || 2000;
    var $t = $('<div class="gphone-toast-msg">' + msg + '</div>');
    $("body").append($t);
    setTimeout(function() {
      $t.addClass("gphone-toast-show");
    }, 10);
    setTimeout(function() {
      $t.removeClass("gphone-toast-show");
      setTimeout(function() {
        $t.remove();
      }, 350);
    }, duration);
  }

  /** 전화번호 형식 체크 및 인증요청 버튼 활성화 */
  function gPhoneNumCheck() {
    $(".gphone-error").addClass("hidden");
    var phone = $("#gphone-input").val().replace(/[^0-9]/g, "");
    $("#gphone-input").val(phone);

    var phoneRule = /^(01[016789]{1})[0-9]{3,4}[0-9]{4}$/;
    if (phoneRule.test(phone)) {
      $("#gphone-btn-auth").css("background-color", "#1B9D7D");
    } else {
      $("#gphone-btn-auth").css("background-color", "#ccc");
    }
  }

  /** 인증번호 남은 시간 카운트다운 */
  function gPhoneUpdateTime() {
    var minutes = Math.floor(gPhoneRemainTime / 60);
    var seconds = gPhoneRemainTime % 60;
    if (seconds < 10) {
      seconds = "0" + seconds;
    }
    $("#gphone-remain-time").html(minutes + " : " + seconds);

    if (gPhoneRemainTime > 0) {
      gPhoneRemainTime--;
    } else {
      clearInterval(gPhoneTimer);
    }
  }

  window.addEventListener("DOMContentLoaded", function() {

    // 취소 버튼 — bottom sheet 닫기 애니메이션
    $("#gphone-btn-cancel").click(function() {
      clearInterval(gPhoneTimer);
      closeGoogleJoinPhonePopup();
    });

    // 오버레이 터치 시 닫기
    $("#google-phone-popup").click(function(e) {
      if ($(e.target).is("#google-phone-popup")) {
        clearInterval(gPhoneTimer);
        closeGoogleJoinPhonePopup();
      }
    });

    // 인증요청 버튼 — 전화번호 DB 조회 후 미가입 시 SMS 발송
    $("#gphone-btn-auth").click(function() {
      $(".gphone-error").addClass("hidden");

      var phone = $("#gphone-input").val();
      var phoneRule = /^(01[016789]{1})[0-9]{3,4}[0-9]{4}$/;
      if (!phoneRule.test(phone)) {
        $(".gphone-error-format").removeClass("hidden");
        return;
      }

      var params = {
        mm_phone: phone
      };
      comAjax("/member_proc/check_google_phone", params, function(ret) {
        if (ret.code == "00") {
          // 미가입 — 인증번호 발송 완료, 인증번호 입력 영역 표시
          $(".gphone-auth-row").removeClass("hidden");
          gPhoneIsAuth = false;
          gPhoneRemainTime = 180;
          clearInterval(gPhoneTimer);
          gPhoneTimer = setInterval(gPhoneUpdateTime, 1000);
          gPhoneUpdateTime();
          gPhoneToast("인증번호를 발송하였습니다."); // 자동으로 사라지는 토스트
        } else if (ret.code == "99") {
          // 이미 가입된 회원 — 중복 계정 팝업 표시
          if (ret.data && ret.data.join_type) {
            clearInterval(gPhoneTimer);
            closeGoogleJoinPhonePopup();
            // 잠깐의 딜레이 후 중복 계정 팝업 표시 (애니메이션 충돌 방지)
            setTimeout(function() {
              showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "");
            }, 400);
          } else {
            gPhoneToast(ret.msg, 1200);
          }
        } else if (ret.code == "98") {
          commonAlert("하루 SMS 발송 횟수를 초과하였습니다. 내일 다시 진행해주세요.", function() {});
        } else {
          commonAlert("오류가 발생하였습니다. (" + ret.code + ")", function() {});
        }
      });
    });

    // 인증번호 확인 버튼
    var gPhoneAuthCount = 0;
    $("#gphone-btn-confirm").click(function() {
      if ($("#gphone-remain-time").html() == "0 : 00") {
        commonAlert("인증번호 유효시간이 초과되었습니다.\n다시 발송해주세요.", function() {});
        return;
      }

      var phone = $("#gphone-input").val();
      var params = {
        mm_phone: phone,
        auth: $("#gphone-auth-input").val()
      };
      comAjax("/member_proc/check_google_phone_auth", params, function(ret) {
        if (ret.code == "00") {
          // 인증 성공 — 회원가입 버튼 활성화 + 자동으로 사라지는 토스트
          gPhoneIsAuth = true;
          clearInterval(gPhoneTimer);
          $("#gphone-btn-join").prop("disabled", false);
          gPhoneToast("인증이 완료되었습니다.", 1500); // 1.5초 후 자동으로 사라짐
        } else {
          gPhoneAuthCount++;
          if (gPhoneAuthCount > 3) {
            commonAlert("인증번호 확인이 3회 이상 실패하였습니다.\n처음부터 다시 진행해주세요.", function() {
              clearInterval(gPhoneTimer);
              closeGoogleJoinPhonePopup();
            });
            return;
          }
          commonAlert("잘못된 인증번호입니다. 다시 입력해주세요.", function() {
            $("#gphone-auth-input").val("").focus();
          });
        }
      });
    });

    // 회원가입 버튼 — 인증 완료 후 join_sns_start 이동 (인증된 전화번호 포함 전달)
    $("#gphone-btn-join").click(function() {
      if (!gPhoneIsAuth) {
        commonAlert("인증 완료 후 진행해주세요.", function() {});
        return;
      }
      clearInterval(gPhoneTimer);
      closeGoogleJoinPhonePopup();

      var phoneVal = $("#gphone-input").val(); // 인증 완료된 전화번호
      var joinUrl = "/member/join_sns_start?join_key=" + encodeURIComponent(gPhoneJoinKey) + "&join_type=" + gPhoneJoinType;
      if (gPhoneEmail) {
        joinUrl += "&email=" + encodeURIComponent(gPhoneEmail);
      }
      if (gPhoneName) {
        joinUrl += "&username=" + encodeURIComponent(gPhoneName);
      }
      if (phoneVal) {
        joinUrl += "&phonenum=" + encodeURIComponent(phoneVal);
      } // mm_phone에 전화번호가 올바르게 저장되도록 전달
      // 팝업 닫기 애니메이션(350ms) 이후 페이지 이동
      setTimeout(function() {
        location.href = joinUrl;
      }, 360);
    });

  });
</script>

<!-- 구글 회원가입 전화번호 인증 — bottom sheet 팝업 -->
<style>
  /* 토스트 메시지 */
  .gphone-toast-msg {
    position: fixed;
    bottom: 100px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.75);
    color: #fff;
    padding: 16px 28px;
    border-radius: 16px;
    font-size: 16px;
    line-height: 1.5;
    z-index: 10001;
    opacity: 0;
    white-space: pre-line;
    text-align: center;
    max-width: 95%;
    width: 95%;
    transition: opacity 0.3s ease;
    pointer-events: none;
  }

  .gphone-toast-msg.gphone-toast-show {
    opacity: 1;
  }

  /* google-phone 시트/오버레이 스타일은 main.css .bs-overlay, .bs-sheet 공통 클래스로 관리 */
</style>
<!--뿌리오 전화번호 인증 팝업-->
<div id="google-phone-popup" class="bs-overlay phone-popup">
  <div id="google-phone-sheet" class="bs-sheet">
    <!-- 핸들 바 -->
    <div class="bs-handlebar"></div>

    <h3 style="font-size:25px;font-weight:700;margin-bottom:10px;color:#1B9D7D;">전화번호 인증</h3>
    <p style="font-size:15px;color:#888;margin-bottom:28px;font-weight:600;">구글 회원가입을 위해 전화번호 인증이 필요합니다.</p>

    <div class="form-row" style="margin-bottom:20px;">
      <p class="form-label" style="font-size:18px;">휴대폰번호</p>
      <div class="inline-group">
        <label class="col">
          <span class="sr-text">휴대폰번호 입력</span>
          <input type="text" id="gphone-input" placeholder="휴대폰번호를 입력해 주세요" autocomplete="off" class="form-text gphone-input" onkeyup="gPhoneNumCheck();">
        </label>
        <button id="gphone-btn-auth" type="button" class="btn btn-tertiary" style="background-color:#ccc;">인증 요청</button>
      </div>
      <p class="gphone-error gphone-error-format help-msg is-fail hidden">형식에 맞지 않는 휴대폰번호입니다.</p>
    </div>

    <div class="form-row gphone-auth-row" style="margin-bottom:32px;">
      <p class="form-label">인증번호</p>
      <div class="inline-group">
        <label class="col" style="position:relative;">
          <span class="sr-text">인증번호 입력</span>
          <input type="text" id="gphone-auth-input" placeholder="인증번호를 입력해 주세요" autocomplete="one-time-code" inputmode="numeric" class="form-text gphone-input">
          <span id="gphone-remain-time" style="position:absolute;width:48px;right: 6px;;top:20px;color:#FF6D6D;text-align:right;font-weight:600;font-size:1.2rem">0 : 00</span>
        </label>
        <button id="gphone-btn-confirm" type="button" class="btn btn-tertiary">확인</button>
      </div>
    </div>

    <div class="bs-actions">
      <button id="gphone-btn-cancel" type="button" class="btn btn-secondary" style="flex:1;">취소</button>
      <button id="gphone-btn-join" type="button" class="btn btn-primary" style="flex:1;" disabled>회원가입</button>
    </div>
  </div>
</div>

<!-- 중복 계정 안내 바텀시트 -->
<div id="duplicateProviderPanel" class="layer-panel card-type" tabindex="-1">
  <div class="bottom-sheet-container">
    <div class="bottom-sheet-handlebar"></div>
    <div class="bottom-sheet-body">
      <div class="bottom-sheet-content" style="text-align:center;">
        <img id="duplicateProviderIcon" src="" alt="" style="width:56px;height:56px;border-radius:50%;margin:8px auto 20px;display:block;">
        <p style="font-size:17px;font-weight:700;color:#222;margin:0 0 8px;" id="duplicateProviderText1"></p>
        <p style="font-size:17px;color:#222;font-weight:700;margin:0;" id="duplicateProviderText2"></p>
      </div>
    </div>
    <div class="bottom-sheet-footer">
      <div class="bottom-sheet-sticky-bottom">
        <button type="button" id="btnDuplicateProviderLogin" class="btn-bottom-sheet btn-bottom-sheet-primary bottom-sheet-action">로그인 하기</button>
      </div>
    </div>
  </div>
</div>

<!-- 휴대폰번호 OTP 로그인 함수들 -->
<script>
  // 휴대폰번호 OTP 로그인: SMS 발송 요청 및 로그인 처리
  function checkPhoneLogin() {
    var phone = $("#phone-login-input").val().replace(/[^0-9]/g, "");

    if (phone.length === 0) {
      commonAlert("휴대폰번호를 입력해주세요.", function() {
        $("#phone-login-input").focus();
      });
      return;
    }

    // 서버에서 전화번호 존재 여부 확인 및 SMS 발송
    var params = { mm_phone: phone };
    comAjax("/member_proc/check_phone_login", params, function(ret) {

      if (ret.code == "00") {
        // 테스트 번호는 SMS 인증 생략하고 바로 홈으로 이동
        if (ret.data && ret.data.skip_auth) {
          sessionStorage.removeItem("chat_user_connect");
          _replace("/");
          return;
        }
        // SMS 발송 성공 → 인증 페이지로 이동
        commonAlert("인증번호를 발송하였습니다.", function() {
          location.href = "/member/phone_login_auth?phone=" + encodeURIComponent(phone);
        });
      } else if (ret.code == "90") {
        // 미가입 회원 - 회원가입 안내 후 로그인 페이지에 머무름
        commonAlert(ret.msg || "회원가입이 필요합니다.", function() {});
      } else if (ret.code == "98" || ret.code == "99") {
        commonAlert(ret.msg || "오류가 발생했습니다. 다시 시도해주세요.", function() {});
      } else {
        commonAlert(ret.msg || "오류가 발생했습니다.", function() {});
      }
    });
  }

  // 중복 계정 팝업 (바텀시트): 다른 SNS로 이미 가입된 회원 시 표시
  // 사용자에게 기존 로그인 방식으로 재로그인하도록 유도
  function showDuplicateProviderPopup(joinType, mmPhone) {
    hideLoginLoading();

    var label = "";
    var iconSrc = "";
    var buttonAction = function() {};

    // SNS 타입별 버튼 동작 및 아이콘 설정
    if (joinType === "KAKAO") {
      label = "카카오";
      iconSrc = "/resource/shop/mobile/img/member/login-sns-kakao.png";
      buttonAction = function() {
        $("#btnKakao").length ? $("#btnKakao").trigger("click") : $("#btnKakaoAndroid").trigger("click");
      };
    } else if (joinType === "NAVER") {
      label = "네이버";
      iconSrc = "/resource/shop/mobile/img/member/login-sns-naver.png";
      buttonAction = function() {
        $("#btnNaver").trigger("click");
      };
    } else if (joinType === "GOOGLE") {
      label = "구글";
      iconSrc = "/resource/shop/mobile/img/member/login-sns-google.png";
      buttonAction = function() {
        $("#btnGoogle").trigger("click");
      };
    } else if (joinType === "WECHAT") {
      label = "위챗";
      iconSrc = "/resource/shop/mobile/img/member/login-sns-wechat.png";
      buttonAction = function() {
        $("#btnWeChat").trigger("click");
      };
    } else if (joinType === "WEB") {
      label = "휴대폰";
      iconSrc = "";
      buttonAction = function() {
        // 휴대폰 번호로 기존 계정 자동 로그인
        if (!mmPhone) {
          commonAlert("오류: 전화번호 정보를 찾을 수 없습니다. 로그인 화면으로 돌아갑니다.", function() {
            _replace("/member/login");
          });
          return;
        }
        var params = {};
        params["mm_phone"] = mmPhone;
        comAjax("/member_proc/phone_auto_login", params, function(ret) {
          if (ret.code == "00") {
            // 로그인 성공 시 기존 채팅 연결 정보 제거
            // NOTE: 향후 다중 기기 지원 시 sessionStorage에 저장하는 로직 추가 예정
            sessionStorage.removeItem("chat_user_connect");
            _replace("/");
          } else {
            commonAlert(ret.msg || "로그인에 실패했습니다.", function() {});
          }
        });
      };
    }

    gDupButtonAction = buttonAction;

    if (iconSrc) {
      $("#duplicateProviderIcon").attr("src", iconSrc).show();
    } else {
      $("#duplicateProviderIcon").hide();
    }
    $("#duplicateProviderText1").text("이미 " + label + "로 회원가입이 되어 있습니다.");
    $("#duplicateProviderText2").text(label + " 계정으로 로그인 하시겠습니까?");

    openDuplicateProviderPanel();
  }

  // 중복 계정 바텀시트 전역 변수
  var gDupButtonAction = function() {};

  function openDuplicateProviderPanel() { // 중복 계정 바텀시트 열기
    var $panel = document.getElementById('duplicateProviderPanel');
    if ($panel && typeof openPanel === 'function') {
      openPanel($panel);
    }
  }

  function closeDuplicateProviderPanel() { // 중복 계정 바텀시트 닫기
    var $panel = document.getElementById('duplicateProviderPanel');
    if ($panel && typeof closePanel === 'function') {
      closePanel($panel);
    }
  }

  $(document).ready(function() { // 중복 계정 바텀시트 초기화
    if (typeof initPanelSwipe === 'function') {
      initPanelSwipe('duplicateProviderPanel');
    }

    $('#btnDuplicateProviderLogin').click(function() {
      closeDuplicateProviderPanel();
      gDupButtonAction();
    });
  });

  // Google/WeChat 웹 리다이렉트 채널에서 msg_code=93인 경우 처리
  $(document).ready(function() {
    <?php if (isset($_GET['msg_code']) && $_GET['msg_code'] == '93'): ?>
      <?php
      $dup_join_type = isset($_GET['dup_join_type']) ? $_GET['dup_join_type'] : '';
      $dup_phone = isset($_GET['dup_phone']) ? $_GET['dup_phone'] : '';
      $allowed_types = ['KAKAO', 'NAVER', 'GOOGLE', 'WECHAT', 'WEB'];
      if (!in_array($dup_join_type, $allowed_types)) {
        $dup_join_type = '';
      }
      ?>
      <?php if (!empty($dup_join_type)): ?>
        setTimeout(function() {
          showDuplicateProviderPopup('<?= $dup_join_type ?>', '<?= $dup_phone ?>');
        }, 100);
      <?php endif; ?>
    <?php endif; ?>
  });
</script>