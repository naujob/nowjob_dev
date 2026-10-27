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
      /* 바텀시트(.bs-overlay, 9999)보다 위, 토스트(10001)보다 아래 —
         인증 시트가 열린 상태에서도 로딩이 보여야 한다 */
      z-index: 10000;
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
    <p class="loading-text" id="login-loading-text">로그인 중 입니다</p>
  </div>

  <div class="login-form-wrap">
    <div class="login-form">
      <div class="form-container">
        <!-- 최근 로그인 방식: 세션 > 쿠키 순으로 조회 (허용 목록 검증은 헬퍼에서 처리) -->
        <?php $recent_login_type = get_last_login_type(); ?>

        <form id="frm" onsubmit="return false">
          <!-- 휴대폰번호 OTP 로그인 영역 -->
          <div class="form-row" id="phone-login-section">
            <label>
              <span class="ico-label phone">휴대폰번호</span>
              <input type="text" id="phone-login-input" placeholder="010-0000-0000" autocomplete="off" class="form-text">
            </label>
          </div>

          <!-- 휴대폰 로그인 버튼: 마지막 로그인이 휴대폰(WEB)이면 배지 표시 -->
          <button id="btnPhoneLogin" type="button" class="btn-primary nowjob-login" data-state="idle" style="position: relative;" data-recent="<?= $recent_login_type === 'WEB' ? 'true' : 'false' ?>"><?= $recent_login_type === 'WEB' ? '<span class="recent-badge">최근</span>' : '' ?></button>

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
                <a href="javascript:void(0);" id="btnKakaoAndroid" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'KAKAO' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-kakao.png')"><?= $recent_login_type === 'KAKAO' ? '<span class="recent-badge">최근</span>' : '' ?></a>
              <?php else : ?>
                <!-- iOS 앱 또는 웹: OAuth 서버 URL로 직접 리다이렉트 -->
                <a href="<?= $kakao_login ?>" id="btnKakao" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'KAKAO' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-kakao.png')"><?= $recent_login_type === 'KAKAO' ? '<span class="recent-badge">최근</span>' : '' ?></a>
              <?php endif; ?>

              <?php if (!empty($wechat_login)) : ?>
                <a href="<?= $wechat_login ?>" id="btnWeChat" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'WECHAT' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-wechat.png')"><?= $recent_login_type === 'WECHAT' ? '<span class="recent-badge">최근</span>' : '' ?></a>
              <?php else : ?>
                <a href="javascript:void(0);" id="btnWeChat" class="vendor-icon-btn" data-recent="false" style="background-image: url('/resource/shop/mobile/img/member/login-sns-wechat.png')"></a>
              <?php endif; ?>

              <?php if ($iosapp) { ?>
                <!-- iOS 네이티브 앱: OAuth URL로 직접 리다이렉트 -->
                <a href="<?= $naver_login ?>" id="btnNaver" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'NAVER' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-naver.png')"><?= $recent_login_type === 'NAVER' ? '<span class="recent-badge">최근</span>' : '' ?></a>
              <?php } else if ($os_type == "android") { ?>
                <!-- 안드로이드 네이티브 앱: JavaScript Bridge(APP_NOWAPK)로 native SDK 호출 -->
                <a href="javascript:void(0);" id="btnNaver" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'NAVER' ? 'true' : 'false' ?>" onclick="APP_NOWAPK.naverLoginAnd(); return false;" style="background-image: url('/resource/shop/mobile/img/member/login-sns-naver.png')"><?= $recent_login_type === 'NAVER' ? '<span class="recent-badge">최근</span>' : '' ?></a>
              <?php } else { ?>
                <!-- 웹브라우저: OAuth URL 설정 여부 확인 후 렌더링 -->
                <?php if (!empty($naver_login)) { ?>
                  <a href="<?= $naver_login ?>" id="btnNaver" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'NAVER' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-naver.png')"><?= $recent_login_type === 'NAVER' ? '<span class="recent-badge">최근</span>' : '' ?></a>
                <?php } else { ?>
                  <!-- 네이버 로그인 미설정 → 경고 메시지 표시 -->
                  <a href="javascript:void(0);" id="btnNaver" class="vendor-icon-btn" data-recent="false" onclick="alert('네이버 로그인이 설정되지 않았습니다.'); return false;" style="background-image: url('/resource/shop/mobile/img/member/login-sns-naver.png')"></a>
                <?php } ?>
              <?php } ?>

              <a href="javascript:void(0);" id="btnGoogle" class="vendor-icon-btn" data-recent="<?= $recent_login_type === 'GOOGLE' ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/member/login-sns-google.png')"><?= $recent_login_type === 'GOOGLE' ? '<span class="recent-badge">최근</span>' : '' ?></a>
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
      <a href="/recruit/job_list" class="btn-primary btn-signup" onclick="showLoginLoading('홈으로 이동 중입니다')" style="background-image: none; background-color: rgba(10, 10, 10, 0.51); color: white; font-size: 20px; font-weight: 600; display: flex; align-items: center; justify-content: center;">나우잡 둘러보기</a>
    </div>
  </div>
</div>


<?php require_once __DIR__ . '/../_partial/login_alert_sheet.php'; ?>

<script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script>
<script src="/resource/shop/mobile/js/logger.js"></script>
<script>
  // 로그인 로딩 오버레이 제어
  // 로딩 오버레이 표시 (message를 넘기지 않으면 기본 문구 사용)
  function showLoginLoading(message) {
    $("#login-loading-text").text(message || "로그인 중 입니다");
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

  // 뒤로 가기로 돌아왔을 때(bfcache 복원) 이동 중 오버레이가 남지 않도록 숨김
  window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
      hideLoginLoading();
    }
  });

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

    // 휴대폰번호 입력칸 Enter → 휴대폰 로그인(SMS 발송) 실행 (PC 로그인 화면과 동일 동작)
    $("#phone-login-input").on("keypress", function(e) {
      if (e.which === 13 || e.keyCode === 13) {
        e.preventDefault();
        checkPhoneLogin();
        return false;
      }
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
              // 서버가 카카오 API로 토큰 주인을 확인하도록 액세스 토큰 전달
              params["access_token"] = (authObj && authObj.access_token) ? authObj.access_token : Kakao.Auth.getAccessToken();
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
                      loginBottomAlert(ret.msg, function() {
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
                        // 이름·이메일·성별·전화번호는 URL로 보내지 않는다 (SNS 로그인 단계에서 서버 세션에 저장되어 있음)
                        location.href = joinUrl;
                      },
                    });
                  } else {
                    loginBottomAlert('(3)' + ret.msg, function() {});
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
      loginBottomAlert("회원정보를 찾을수 없습니다.", function() {});
    } else if ('<?= $msg_code ?>' == '91') {
      loginBottomAlert("탈퇴한 회원입니다.", function() {});
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
      loginBottomAlert("휴대폰번호를 입력해주세요.", function() {
        $("[name=mm_id]").focus();
      });
      return;
    }

    if ($("[name=mm_pwd]").val() == "") {
      loginBottomAlert("비밀번호를 입력해주세요.", function() {
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
        loginBottomAlert(ret.msg, function() {});
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
    // 액세스 토큰: 서버가 카카오 API로 토큰 주인을 확인하는 데 사용
    // 빈 값이 걸러진 result 배열이 아니라 원본 parts에서 읽어야 인덱스가 밀리지 않는다
    var accessToken = parts[7] || "";

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
    params["access_token"] = accessToken; // 서버 검증용 (없으면 기존 동작)
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
            loginBottomAlert(ret.msg, function() {});
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
              // 개인정보는 URL로 보내지 않는다 (SNS 로그인 단계에서 서버 세션에 저장되어 있음)
              var joinUrl = "/member/join_sns_start?join_key=" + result[0] + "&join_type=KAKAO";
              location.href = joinUrl;
            },
          });
        } else {
          loginBottomAlert(ret.msg, function() {});
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
          loginBottomAlert(ret.msg, function() {});
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
    var accessToken = parts[7] || ""; // 서버가 네이버 API로 토큰 주인을 확인하는 데 사용

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
      params["access_token"] = accessToken; // 서버 검증용 (없으면 기존 동작)
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
              loginBottomAlert(ret.msg, function() {
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
                // 이름·이메일·성별·생년월일은 세션 값 사용, 전화번호만 POST로 전달
                postJoinSnsStart({ join_key: uniqueval, join_type: "NAVER", phonenum: phonenum || "" });
              },
            });
          } else {
            loginBottomAlert(ret.msg, function() {});
          }
        }
      });

    } else {
      hideLoginLoading();
      alert("네이버 로그인 실패");
    }

  }

  // 카카오 로그인 콜백: 안드로이드 네이티브 앱(IntroActivity.kakaoLoginStart)에서 호출됨
  // 파라미터: 로그인 상태, 카카오 ID, 이름, 휴대폰, 성별, 생년, 생일, 이메일, 액세스 토큰
  // accessToken: 서버가 카카오 API로 토큰 주인을 확인하는 데 사용 (구버전 앱은 전달하지 않음 → undefined)
  function kakaoLoginAndCallback(status, id, name, phone, gender, birthyear, birthday, email, accessToken) {
    if (status === "success") {
      showLoginLoading();

      // 로그인 성공 → 사용자 정보를 서버에 전송하여 로그인/회원가입 처리

      // 전화번호 포맷 정규화: "+82 10-9002-5528" → "01090025528"
      if (phone) {
        phone = phone.replace(/\+82\s*10-/, '010').replace(/[-\s]/g, '');
      }

      // 성별 정규화: 앱 값(MALE/FEMALE 등)을 대문자로 통일 (대소문자 무관 비교)
      var genderUpper = (gender || "").toUpperCase();
      var genderConverted = (genderUpper === "MALE" || genderUpper === "FEMALE") ? genderUpper : "";

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
      params["access_token"] = accessToken || ""; // 서버 검증용 (없으면 기존 동작)
      comAjax("/member_proc/login", params, function(ret) {
        if (ret.code == "00") {
          sessionStorage.removeItem("chat_user_connect");
          navigateHomeAfterLogin("KAKAO");
          return;
        } else {
          if (ret.code == "91") {
            hideLoginLoading(); // 탈퇴 안내·중복 팝업 전에 로딩 화면 해제
            // 중복 계정 - 다른 SNS로 이미 가입됨
            if (ret.data && ret.data.join_type) {
              showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "");
            } else {
              loginBottomAlert(ret.msg, function() {});
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
                // 개인정보는 URL에 싣지 않음: 로그인 단계에서 서버 세션(site_log)에 저장된 값을 join_sns_start가 사용
                location.href = "/member/join_sns_start?join_type=KAKAO";
              },
            });
          } else {
            hideLoginLoading();
            loginBottomAlert(ret.msg, function() {});
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
  // 파라미터: 로그인 상태, 네이버 ID, 이름, 이메일, 휴대폰, 성별, 생년, 생일, 액세스 토큰
  // accessToken: 서버가 네이버 API로 토큰 주인을 확인하는 데 사용 (구버전 앱은 전달하지 않음 → undefined)
  function naverLoginAndCallback(status, id, name, email, phone, gender, birthyear, birthday, accessToken) {
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
      params["access_token"] = accessToken || ""; // 서버 검증용 (없으면 기존 동작)
      comAjax("/member_proc/login", params, function(ret) {
        if (ret.code == "00") {
          sessionStorage.removeItem("chat_user_connect");
          navigateHomeAfterLogin("NAVER");
          return;
        } else {
          if (ret.code == "91") {
            hideLoginLoading(); // 탈퇴 안내·중복 팝업 전에 로딩 화면 해제
            // 중복 계정 - 다른 SNS로 이미 가입됨
            if (ret.data && ret.data.join_type) {
              showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "");
            } else {
              loginBottomAlert(ret.msg, function() {
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
                // 이름·이메일은 세션 값 사용, 전화번호만 POST로 전달
                postJoinSnsStart({ join_key: id, join_type: "NAVER", phonenum: phone || "" });
              },
            });
          } else {
            hideLoginLoading();
            loginBottomAlert(ret.msg, function() {});
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
      // 사용자가 구글 로그인 창을 직접 닫은 경우(안드로이드 SIGN_IN_CANCELLED)는 실패 안내를 띄우지 않는다
      // 이 경우 idToken 자리에 실패 사유 문자열이 들어온다
      if (String(idToken || "").indexOf("SIGN_IN_CANCELLED") !== -1) {
        return;
      }
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
          loginBottomAlert(ret.msg || "로그인 실패", function() {});
        }
      },
      "json"
    ).fail(function() {
      hideLoginLoading();
      loginBottomAlert("로그인 실패", function() {});
    });
  }

  // 위챗 로그인 콜백: 안드로이드 네이티브 앱(HomeActivity.onWeChatLoginCallback)에서 호출됨
  // 파라미터: 로그인 상태, WeChat 임시 코드 (서버에서 access token 교환용)
  // 실패 시 code 자리에 안드로이드는 실패 사유 문자열을, iOS는 빈 값을 전달함
  function onWeChatLoginAndCallback(status, code) {
    if (status !== 'success' || !code) {
      hideLoginLoading();
      handleWeChatLoginFailure(status === 'success' ? '' : (code || ''));
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
          loginBottomAlert(ret.msg || "로그인 실패", function() {});
        }
      },
      "json"
    ).fail(function() {
      hideLoginLoading();
      loginBottomAlert("로그인 실패", function() {});
    });
  }

  /**
   * 위챗 로그인 실패 사유별 안내 (사유 원문은 내부 코드라 화면에 노출하지 않고 로그로만 남김)
   * @param {string} reason - 네이티브 앱이 전달한 실패 사유 (안드로이드 HomeActivity/WXEntryActivity)
   */
  function handleWeChatLoginFailure(reason) {
    if (typeof BrowserLogger !== 'undefined' && typeof BrowserLogger.info === 'function') {
      BrowserLogger.info("위챗 로그인 실패 사유: " + reason);
    }

    if (reason.indexOf("설치되어 있지 않습니다") !== -1) {
      // 위챗 미설치: 안내는 안드로이드 앱이 토스트로 이미 표시하므로 웹은 Play 스토어 위챗 페이지로만 이동
      // (WebView가 market:// 를 스토어 앱으로 연결)
      location.href = "market://details?id=com.tencent.mm";
    } else if (reason.indexOf("ERR_USER_CANCEL") !== -1) {
      // 사용자가 직접 취소한 경우 별도 안내 없음
      return;
    } else if (reason.indexOf("ERR_AUTH_DENIED") !== -1) {
      loginBottomAlert("위챗 권한 승인이 거절되었습니다.", function() {});
    } else {
      loginBottomAlert("위챗 로그인에 실패했습니다.\n잠시 후 다시 시도해주세요.", function() {});
    }
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
  var gPhoneAuthPhone = ""; // 인증 완료된 전화번호 (번호 변경 감지용)
  var gPhoneMode = "JOIN"; // 시트 용도: JOIN(구글/위챗 가입 인증) / LOGIN(휴대폰 로그인 인증)
  var gPhoneLoginAuthCount = 0; // 휴대폰 로그인 인증번호 불일치 횟수
  var gPhoneConfirmPending = false; // 인증번호 확인 요청 대기 중 여부 (연속 클릭 방지)

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
    // 로그인 중 오버레이 해제 (팝업이 열린 상태이므로 뒤 화면은 잠금 처리)
    hideLoginLoading();
    $("html, body").css("overflow", "hidden");

    gPhoneJoinKey = joinKey;
    gPhoneJoinType = joinType || "GOOGLE";
    gPhoneEmail = email || "";
    gPhoneName = name || "";

    // 가입 인증 용도로 초기화 (휴대폰 로그인은 openPhoneLoginSheet에서 LOGIN으로 바꿈)
    gPhoneMode = "JOIN";
    $("#gphone-desc").text("회원가입을 위해 전화번호 인증이 필요합니다.");
    gPhoneIsAuth = false;
    gPhoneLoginAuthCount = 0;

    // 팝업 초기화
    $("#gphone-input").val("");
    $("#gphone-auth-input").val("");
    $("#gphone-remain-time").html("0 : 00");
    $(".gphone-error").addClass("hidden");
    $(".gphone-auth-row").addClass("hidden");
    $("#gphone-btn-auth").css("background-color", "#ccc");

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
      // 뒤 화면 잠금 해제
      $("html, body").css("overflow", "");
    }, 350);
  }

  /**
   * 인증 완료 후 가입 페이지(join_sns_start)로 이동
   * 인증된 전화번호(phonenum)와 구글/위챗 정보를 함께 전달
   */
  // 가입 정보(전화번호 등 개인정보)는 URL 쿼리가 아니라 POST 폼으로 join_sns_start에 보낸다 (접근 로그 노출 방지)
  function postJoinSnsStart(fields) {
    var $form = $('<form method="post" action="/member/join_sns_start"></form>');
    $.each(fields, function(name, value) {
      $form.append($('<input type="hidden">').attr('name', name).val(value || ''));
    });
    $form.appendTo('body').submit();
  }

  function gPhoneGoJoin() {
    clearInterval(gPhoneTimer);
    closeGoogleJoinPhonePopup();
    showLoginLoading(); // 가입 페이지로 넘어갈 때까지 빈 로그인 화면이 보이지 않도록

    var phoneVal = $("#gphone-input").val(); // 인증 완료된 전화번호
    var joinFields = { join_key: gPhoneJoinKey, join_type: gPhoneJoinType };
    if (gPhoneEmail) {
      // 이메일·이름은 로그인 단계에서 서버 세션에 저장되어 있으므로 보내지 않는다
    }
    if (gPhoneName) {
      // (이름은 세션 값 사용)
    }
    if (phoneVal) {
      joinFields.phonenum = phoneVal;
    }
    // 팝업 닫기 애니메이션(350ms) 이후 페이지 이동
    setTimeout(function() {
      postJoinSnsStart(joinFields);
    }, 360);
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

    // 인증 완료 후 번호가 바뀌면 인증 무효화 + 회원가입 버튼 비활성
    if (gPhoneIsAuth && phone !== gPhoneAuthPhone) {
      gPhoneIsAuth = false;
    }

    var phoneRule = /^(01[016789]{1})[0-9]{3,4}[0-9]{4}$/;
    if (phoneRule.test(phone)) {
      $("#gphone-btn-auth").css("background-color", "#1B9D7D");
    } else {
      $("#gphone-btn-auth").css("background-color", "#ccc");
    }
  }

  /**
   * 휴대폰 로그인 인증 시트 열기 (구글/위챗 가입 시트를 LOGIN 용도로 재사용)
   * @param {string} phone  입력란 로그인에서 입력한 번호
   */
  function openPhoneLoginSheet(phone) {
    openGoogleJoinPhonePopup("", "", "", "WEB");
    gPhoneMode = "LOGIN";
    $("#gphone-desc").text("로그인을 위해 전화번호 인증이 필요합니다.");
    $("#gphone-input").val(String(phone || "").replace(/[^0-9]/g, ""));
    gPhoneNumCheck();
    // 시트가 열리자마자 인증번호 발송 (시트 안 "인증요청" 버튼을 누르지 않아도 되도록)
    requestPhoneLoginAuthSheet();
  }

  /** 휴대폰 로그인: 인증번호 발송 (check_phone_login은 가입된 회원에게만 발송) */
  function requestPhoneLoginAuthSheet() {
    var phone = $("#gphone-input").val().replace(/[^0-9]/g, "");
    var phoneRule = /^(01[016789]{1})[0-9]{3,4}[0-9]{4}$/;
    if (!phoneRule.test(phone)) {
      $(".gphone-error-format").removeClass("hidden");
      return;
    }

    comAjax("/member_proc/check_phone_login", { mm_phone: phone }, function(ret) {
      if (ret.code == "00" && ret.data && ret.data.skip_auth) {
        // 테스트 번호: 인증 없이 바로 로그인 완료
        clearInterval(gPhoneTimer);
        closeGoogleJoinPhonePopup();
        showLoginLoading(); // 시트가 닫힌 뒤 홈으로 이동할 때까지 빈 로그인 화면이 보이지 않도록
        sessionStorage.removeItem("chat_user_connect");
        _replace("/");
        return;
      }
      if (ret.code == "00") {
        // 발송 성공 → 인증번호 입력 영역 표시 및 타이머 시작
        $(".gphone-auth-row").removeClass("hidden");
        $("#gphone-auth-input").focus(); // 인증번호 입력란으로 커서 이동
        gPhoneIsAuth = false; // 재발송 시 이전 인증 무효
        gPhoneLoginAuthCount = 0;
        gPhoneRemainTime = 180;
        clearInterval(gPhoneTimer);
        gPhoneTimer = setInterval(gPhoneUpdateTime, 1000);
        gPhoneUpdateTime();
      } else if (ret.code == "90" || ret.code == "91") {
        // 미가입 또는 탈퇴 번호: 시트를 닫고 안내
        clearInterval(gPhoneTimer);
        closeGoogleJoinPhonePopup();
        loginBottomAlert(ret.msg || "회원가입이 필요합니다.", function() {});
      } else if (ret.code == "99") {
        gPhoneToast(ret.msg, 1200);
      } else if (ret.code == "98") {
        loginBottomAlert("하루 SMS 발송 횟수를 초과하였습니다. 내일 다시 진행해주세요.", function() {});
      } else {
        loginBottomAlert(ret.msg || "오류가 발생하였습니다.", function() {});
      }
    });
  }

  /** 휴대폰 로그인: 인증번호 확인 및 로그인 (응답 코드는 휴대폰 인증 페이지와 동일하게 처리) */
  function confirmPhoneLoginSheet() {
    // 로그인 완료 이동 대기 중이거나 이미 확인 요청을 보낸 상태면 중복 요청 방지
    if (gPhoneIsAuth || gPhoneConfirmPending) {
      return;
    }
    var phone = $("#gphone-input").val().replace(/[^0-9]/g, "");
    var auth = $("#gphone-auth-input").val();

    if (!auth) {
      gPhoneToast("인증번호를 입력해주세요.", 1500);
      return;
    }
    if ($("#gphone-remain-time").html() == "0 : 00") {
      loginBottomAlert("인증번호 유효시간이 초과되었습니다.\n다시 발송해주세요.", function() {});
      return;
    }

    // 응답을 기다리는 동안 화면이 멈춘 것처럼 보이지 않도록 로딩 화면을 띄운다
    gPhoneConfirmPending = true;
    showLoginLoading();

    comAjax("/member_proc/check_phone_login_auth", { mm_phone: phone, auth: auth }, function(ret) {
      // 응답 도착 → 일단 해제하고, 홈으로 이동하는 성공 분기에서만 다시 켠다
      gPhoneConfirmPending = false;
      hideLoginLoading();

      if (ret.code == "00") {
        // 로그인 성공 → 인증 시트를 닫고 로딩 화면으로 바꾼 뒤 홈으로 이동
        gPhoneIsAuth = true;
        gPhoneAuthPhone = phone;
        clearInterval(gPhoneTimer);
        closeGoogleJoinPhonePopup();
        showLoginLoading(); // 시트와 같은 z-index이지만 시트가 내려가며 투명해져 로딩이 드러난다
        sessionStorage.removeItem("chat_user_connect");
        navigateHomeAfterLogin("WEB");
      } else if (ret.code == "90") {
        // 미가입 회원 → 안내만 띄우고 현재 페이지에 머문다 (확인 시에도 이동하지 않음)
        clearInterval(gPhoneTimer);
        closeGoogleJoinPhonePopup();
        // 인증번호 입력란의 키보드를 먼저 내린 뒤, 키보드가 닫히고 뷰포트가 안정된 다음에 안내 바텀시트를 연다
        if (document.activeElement && document.activeElement.blur) {
          document.activeElement.blur();
        }
        waitForKeyboardClosed(function() {
          loginBottomAlert("가입된 회원이 아닙니다.\n회원가입 후 이용해주세요.", function() {});
        });
      } else if (ret.code == "91") {
        // 탈퇴 번호 안내
        clearInterval(gPhoneTimer);
        closeGoogleJoinPhonePopup();
        loginBottomAlert(ret.msg || "탈퇴한 회원입니다.", function() {});
      } else if (ret.code == "92") {
        // 인증번호 불일치 (3회 실패 시 처음부터 다시)
        gPhoneLoginAuthCount++;
        if (gPhoneLoginAuthCount >= 3) {
          clearInterval(gPhoneTimer);
          closeGoogleJoinPhonePopup();
          loginBottomAlert("인증번호 입력이 3회 이상 실패했습니다.\n처음부터 다시 진행해주세요.", function() {});
        } else {
          gPhoneToast("인증번호가 일치하지 않습니다. (" + (3 - gPhoneLoginAuthCount) + "회 남음)", 1500);
          $("#gphone-auth-input").val("").focus();
        }
      } else {
        loginBottomAlert(ret.msg || "오류가 발생했습니다. 다시 시도해주세요.", function() {});
      }
    });
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

    // Enter 키: 휴대폰번호 입력칸 → 인증 요청, 인증번호 입력칸 → 확인 (형식 검증은 각 버튼 클릭 핸들러에서 처리)
    $("#gphone-input").on("keydown", function(e) {
      if (e.which === 13) {
        e.preventDefault();
        $("#gphone-btn-auth").click();
      }
    });
    $("#gphone-auth-input").on("keydown", function(e) {
      if (e.which === 13) {
        e.preventDefault();
        $("#gphone-btn-confirm").click();
      }
    });

    // 취소 버튼 — bottom sheet 닫기 애니메이션
    $("#gphone-btn-cancel").click(function() {
      clearInterval(gPhoneTimer);
      closeGoogleJoinPhonePopup();
    });

    // 우상단 X 버튼 — 취소와 동일하게 닫기
    $("#gphone-btn-close").click(function() {
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

      // 휴대폰 로그인 용도: 가입 여부 확인 없이 회원 번호에만 SMS 발송
      if (gPhoneMode === "LOGIN") {
        requestPhoneLoginAuthSheet();
        return;
      }

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
          $("#gphone-auth-input").focus(); // 인증번호 입력란으로 커서 이동
          gPhoneIsAuth = false; // 재발송 시 이전 인증 무효
          gPhoneRemainTime = 180;
          clearInterval(gPhoneTimer);
          gPhoneTimer = setInterval(gPhoneUpdateTime, 1000);
          gPhoneUpdateTime();
        } else if (ret.code == "99") {
          // SMS 발송 오류 (가입 여부는 인증 확인 단계에서 안내)
          gPhoneToast(ret.msg, 1200);
        } else if (ret.code == "98") {
          loginBottomAlert("하루 SMS 발송 횟수를 초과하였습니다. 내일 다시 진행해주세요.", function() {});
        } else {
          loginBottomAlert("오류가 발생하였습니다. (" + ret.code + ")", function() {});
        }
      });
    });

    // 인증번호 확인 버튼
    var gPhoneAuthCount = 0;
    $("#gphone-btn-confirm").click(function() {
      // 휴대폰 로그인 용도: 인증번호 확인 후 로그인 처리
      if (gPhoneMode === "LOGIN") {
        confirmPhoneLoginSheet();
        return;
      }
      // 인증 완료 후 자동 이동 대기 중이면 중복 요청 방지
      if (gPhoneIsAuth) {
        return;
      }
      if ($("#gphone-remain-time").html() == "0 : 00") {
        loginBottomAlert("인증번호 유효시간이 초과되었습니다.\n다시 발송해주세요.", function() {});
        return;
      }

      var phone = $("#gphone-input").val();
      var params = {
        mm_phone: phone,
        auth: $("#gphone-auth-input").val()
      };
      // 응답 대기 중 화면이 멈춘 것처럼 보이지 않도록 로딩 화면을 띄운다
      showLoginLoading();

      comAjax("/member_proc/check_google_phone_auth", params, function(ret) {
        // 응답 도착 → 일단 해제하고, 페이지를 이동하는 분기에서만 다시 켠다
        hideLoginLoading();

        if (ret.code == "00") {
          // 인증 성공 — 회원가입 버튼 활성화 + 자동으로 사라지는 토스트
          gPhoneIsAuth = true;
          gPhoneAuthPhone = phone;
          clearInterval(gPhoneTimer);
          // 인증 성공 즉시 가입 페이지로 이동
          gPhoneGoJoin();
        } else if (ret.code == "99") {
          // 본인 인증은 성공 — 이미 가입된 번호(중복 계정) 또는 15일 이내 탈퇴 번호
          clearInterval(gPhoneTimer);
          closeGoogleJoinPhonePopup();
          if (ret.data && ret.data.join_type) {
            // 시트가 닫히는 동안 빈 화면이 보이지 않도록 로딩 화면을 먼저 띄운다
            showLoginLoading();
            // 잠깐의 딜레이 후 중복 계정 자동 로그인 시작 (애니메이션 충돌 방지)
            // gPhoneJoinType: 사용자가 누른 SNS(GOOGLE/WECHAT) → 최근 로그인 배지 기록에 사용
            setTimeout(function() {
              showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "", gPhoneJoinType);
            }, 400);
          } else {
            loginBottomAlert(ret.msg, function() {});
          }
        } else {
          gPhoneAuthCount++;
          if (gPhoneAuthCount > 3) {
            loginBottomAlert("인증번호 확인이 3회 이상 실패하였습니다.\n처음부터 다시 진행해주세요.", function() {
              clearInterval(gPhoneTimer);
              closeGoogleJoinPhonePopup();
            });
            return;
          }
          loginBottomAlert("잘못된 인증번호입니다. 다시 입력해주세요.", function() {
            $("#gphone-auth-input").val("").focus();
          });
        }
      });
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

    <!-- 제목 + 닫기(X) 버튼 -->
    <div style="display:flex;justify-content:space-between;align-items:flex-start;">
      <h3 style="font-size:25px;font-weight:700;margin-bottom:10px;color:#1B9D7D;">전화번호 인증</h3>
      <button id="gphone-btn-close" type="button" aria-label="닫기" style="background:none;border:0;padding:4px;font-size:24px;line-height:1;color:#888;cursor:pointer;">
        <i class="fa fa-times"></i>
      </button>
    </div>
    <p id="gphone-desc" style="font-size:15px;color:#888;margin-bottom:28px;font-weight:600;">회원가입을 위해 전화번호 인증이 필요합니다.</p>

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
    </div>
  </div>
</div>

<!-- 휴대폰번호 OTP 로그인 함수들 -->
<script>
  // 휴대폰번호 로그인 바텀시트 대기 중 여부 (연속 클릭/Enter로 시트가 두 번 열리는 것 방지)
  var gPhoneLoginSheetPending = false;

  // 휴대폰번호 OTP 로그인: 인증 바텀시트 열기 (SMS 발송은 시트 안 인증 요청 버튼에서 처리)
  function checkPhoneLogin() {
    var phone = $("#phone-login-input").val().replace(/[^0-9]/g, "");

    if (phone.length === 0) {
      loginBottomAlert("휴대폰번호를 입력해주세요.", function() {
        $("#phone-login-input").focus();
      });
      return;
    }

    if (gPhoneLoginSheetPending) {
      return;
    }
    gPhoneLoginSheetPending = true;

    // 키보드가 내려가는 중에 시트가 올라오면 리사이즈와 transition이 겹쳐 튕겨 보이므로,
    // 키보드가 닫힌 뒤에 시트를 연다
    waitForKeyboardClosed(function() {
      gPhoneLoginSheetPending = false;
      // 바텀시트에서 인증 (구글/위챗과 같은 방식)
      openPhoneLoginSheet(phone);
    });
  }

  /**
   * 소프트 키보드가 닫히고 뷰포트 리사이즈가 멈출 때까지 기다린 뒤 콜백 실행
   * - 휴대폰번호 입력란이 포커스되어 있으면 blur로 키보드를 먼저 내린다
   * - 리사이즈가 없으면(키보드가 이미 닫혀 있던 경우) 150ms 후 진행, 최대 700ms 안에는 반드시 진행
   * @param {function} callback  키보드 닫힘 확인 후 실행할 함수
   */
  function waitForKeyboardClosed(callback) {
    var input = document.getElementById("phone-login-input");
    if (document.activeElement === input) {
      input.blur();
    }

    var vv = window.visualViewport;
    var quietTimer = null;
    var hardTimer = null;
    var done = false;

    function finish() {
      if (done) return;
      done = true;
      clearTimeout(quietTimer);
      clearTimeout(hardTimer);
      window.removeEventListener("resize", onResize);
      if (vv) vv.removeEventListener("resize", onResize);
      callback();
    }

    // 키보드 닫힘 애니메이션 동안 리사이즈가 계속 발생하므로, 발생할 때마다 "조용한 시간"을 다시 잰다
    function onResize() {
      clearTimeout(quietTimer);
      quietTimer = setTimeout(finish, 150);
    }

    window.addEventListener("resize", onResize);
    if (vv) vv.addEventListener("resize", onResize);
    quietTimer = setTimeout(finish, 150);
    hardTimer = setTimeout(finish, 700);
  }

  // 중복 계정 처리: 다른 SNS/휴대폰으로 이미 가입된 회원이면 안내 없이 기존 가입 방식으로 바로 로그인
  // 안내 바텀시트 탭 없이 호출되므로, 네이티브 브릿지 호출과 OAuth 리다이렉트는 이 흐름에서 그대로 실행된다
  // viaType(선택): 문자 인증 가입 시트를 거친 SNS 방식(GOOGLE/WECHAT) → 서버가 최근 로그인 배지를 이 값으로 기록
  function showDuplicateProviderPopup(joinType, mmPhone, viaType) {
    // 자동 로그인이 끝날 때까지 로딩 화면을 유지한다
    // (여기서 끄면 응답을 기다리는 동안 빈 로그인 화면이 잠깐 노출됨)
    showLoginLoading();

    // 번호가 서버에서 확인되지 않았을 때(토큰 미전송 구버전 앱 등) 쓰는 기존 방식 폴백
    var fallbackAction = function() {
      if (joinType === "KAKAO") {
        $("#btnKakao").length ? $("#btnKakao").trigger("click") : $("#btnKakaoAndroid").trigger("click");
      } else if (joinType === "NAVER") {
        $("#btnNaver").trigger("click");
      } else if (joinType === "GOOGLE") {
        $("#btnGoogle").trigger("click");
      } else if (joinType === "WECHAT") {
        $("#btnWeChat").trigger("click");
      } else if (joinType === "WEB") {
        // 휴대폰 계정은 되돌아갈 SNS 버튼이 없으므로 문자 인증 바텀시트(LOGIN 모드)로 진행
        if (!mmPhone) {
          hideLoginLoading();
          loginBottomAlert("오류: 전화번호 정보를 찾을 수 없습니다. 로그인 화면으로 돌아갑니다.", function() {
            _replace("/member/login");
          });
          return;
        }
        openPhoneLoginSheet(mmPhone); // 시트가 열리면서 내부에서 로딩 화면을 해제한다
      } else {
        hideLoginLoading();
        loginBottomAlert("로그인에 실패했습니다. 다시 시도해주세요.", function() {});
      }
    };

    if (!mmPhone) {
      // 번호가 없으면 자동 로그인을 시도할 수 없다 → 기존 가입 방식으로 안내
      fallbackAction();
      return;
    }

    // 1차: 기존 계정으로 바로 로그인 시도
    // SNS 토큰 검증으로 번호가 확인된 경우(member_proc/login) 문자 인증 없이 통과된다
    var params = {};
    params["mm_phone"] = mmPhone;
    params["mm_join_type"] = joinType; // 미지정 시 WEB만 조회되므로 SNS 계정은 반드시 전달
    params["login_via"] = viaType || ""; // 구글/위챗으로 들어온 경우 배지 기록용
    comAjax("/member_proc/phone_auto_login", params, function(ret) {
      if (ret.code == "00") {
        // 로그인 성공 시 기존 채팅 연결 정보 제거
        // NOTE: 향후 다중 기기 지원 시 sessionStorage에 저장하는 로직 추가 예정
        sessionStorage.removeItem("chat_user_connect");
        _replace("/");
      } else if (ret.code == "80") {
        // 본인 확인이 안 된 번호 → 기존 가입 방식으로 다시 로그인 (로딩 해제는 각 분기에서 처리)
        fallbackAction();
      } else {
        hideLoginLoading();
        loginBottomAlert(ret.msg || "로그인에 실패했습니다.", function() {});
      }
    });
  }

  // SNS OAuth 리다이렉트 처리: 중복 계정 자동 로그인 (네이버/카카오 콜백에서 전달됨)
  $(document).ready(function() {
    <?php if (isset($_GET['dup_join_type']) && !empty($_GET['dup_join_type'])): ?>
      <?php
      $dup_join_type = $_GET['dup_join_type'];
      $dup_phone = isset($_GET['dup_mm_phone']) ? $_GET['dup_mm_phone'] : '';
      $allowed_types = ['KAKAO', 'NAVER', 'GOOGLE', 'WECHAT', 'WEB'];
      if (!in_array($dup_join_type, $allowed_types)) {
        $dup_join_type = '';
      }
      ?>
      <?php if (!empty($dup_join_type)): ?>
        // 자동 로그인이 시작되기 전까지 빈 로그인 화면이 보이지 않도록 먼저 로딩 화면을 띄운다
        showLoginLoading();
        setTimeout(function() {
          showDuplicateProviderPopup('<?= $dup_join_type ?>', '<?= $dup_phone ?>');
        }, 100);
      <?php endif; ?>
    <?php endif; ?>

    // 구글/위챗 신규 회원 가입 플로우: 전화번호 인증 팝업 표시
    <?php if (isset($_GET['join_sns']) && $_GET['join_sns'] == '1'): ?>
      <?php
      $join_key = isset($_GET['join_key']) ? $_GET['join_key'] : '';
      $join_type = isset($_GET['join_type']) ? $_GET['join_type'] : '';
      $email = isset($_GET['email']) ? $_GET['email'] : '';
      $username = isset($_GET['username']) ? $_GET['username'] : '';
      $allowed_sns_types = ['GOOGLE', 'WECHAT'];
      if (!empty($join_key) && !empty($join_type) && in_array($join_type, $allowed_sns_types)) {
      ?>
        setTimeout(function() {
          openGoogleJoinPhonePopup('<?= $join_key ?>', '<?= $email ?>', '<?= $username ?>', '<?= $join_type ?>');
          // 페이지 새로고침 시 팝업이 다시 떠서 무한 루프 방지
          if (window.history.replaceState) {
            window.history.replaceState({}, document.title, window.location.pathname);
          }
        }, 100);
      <?php } ?>
    <?php endif; ?>
  });
</script>