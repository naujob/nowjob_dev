<?php
$app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
$socket_base_url = rtrim($this->config->item('socket_base_url') ?: $app_base_url, '/');
$socket_io_script_url = $this->config->item('socket_io_script_url');
if (!$socket_io_script_url) {
  $socket_io_script_url = $socket_base_url . '/socket.io/socket.io.js';
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="UTF-8">
  <!-- meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" -->
  <meta name="viewport" content="width=460, user-scalable=no">
  <title>지금 뜨는, 좋은 일자리 나우잡(NOWJOB)</title>
  <meta name="description" content="구인구직 정보 플랫폼">
  <meta name="format-detection" content="address=no, email=no, telephone=no">
  <meta name="naver-site-verification" content="20459c5bd907c133c314dd88161a57f212fce831" />
  <link rel="shortcut icon" href="/resource/favicon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Script -->
  <?= getCashJs(RES_PATH . "/js/lib/jquery-3.5.1.min.js") ?>
  <!-- <?= getCashJs(RES_PATH . "/js/lib/mobiscroll.jquery.min.js") ?> -->
  <?= getCashJs(RES_PATH . "/js/lib/proj4.js") ?>
  <?= getCashJs(RES_PATH . "/js/lib/preview/jquery.magnific-popup.min.js") ?>
  <?= getCashJs(RES_PATH . "/js/add.js") ?>
  <script>
    window.__CHAT_BASE_URL = "<?= $socket_base_url ?>";
  </script>
  <script src="<?= $socket_io_script_url ?>"></script>
  <script>
    // Socket.IO 즉시 초기화 (DOM 로드 대기 없음)
    (function() {
      // Socket.IO 라이브러리 로드 확인
      if (typeof io === 'undefined') {
        console.error('Socket.IO 라이브러리를 로드할 수 없습니다.');
        // 대체 CDN에서 로드 시도
        var script = document.createElement('script');
        script.src = 'https://cdn.socket.io/4.5.4/socket.io.min.js';
        script.onload = function() {
          console.log('Socket.IO 라이브러리 로드 성공 (CDN)');
          createSocket();
        };
        script.onerror = function() {
          console.error('Socket.IO 라이브러리 로드 실패');
        };
        document.head.appendChild(script);
      } else {
        createSocket();
      }
    })();

    function createSocket() {
      // Socket 연결 초기화
      const base_url = "<?= $socket_base_url ?>";
      const socket = io(base_url, {
        secure: true,
        rejectUnauthorized: false, // SSL 인증서 검증 비활성화
        transports: ['websocket', 'polling']
      });

      // 전역 socket 객체 설정
      window.socket = socket;

      // 연결 상태 모니터링
      socket.on('connect', function() {
        console.log('Socket.IO 연결 성공:', socket.id);
        // 토큰 전송을 위한 플래그 설정
        window._socketReady = true;
        // 토큰 전송 시도
        if (typeof trySendTokenToServer === 'function') {
          trySendTokenToServer();
        }
      });

      socket.on('disconnect', function() {
        console.log('Socket.IO 연결 끊김');
        window._socketReady = false;
      });

      socket.on('connect_error', function(error) {
        console.error('Socket.IO 연결 오류:', error);
        window._socketReady = false;
      });

      // 연결 성공 후 사용자 상태 업데이트는 common.js의 updateSocket()에서 처리
      // (안드로이드 앱 처리를 포함한 완전한 로직이 있음)
    }

    // ============================================
    // 🔥 Release 앱 푸시/토큰 문제 완전 해결 코드
    // ============================================

    // 전역 상태 플래그 (페이지 리로드 후에도 유지되도록 window에 저장)
    window._tokenReady = false;
    window._socketReady = false;
    window._updateSent = false;
    window.fcmToken = window.fcmToken || null; // 기존 토큰 보존

    // ============================================
    // ① Native(iOS/Android)에서 토큰을 받을 때 실행 (현재 실행x)
    // ============================================
    window.setToken = function(token) {
      console.log("🔥 [setToken] 토큰 수신:", token ? token.substring(0, 20) + '...' : 'null');

      if (!token || token === '' || token === 'null') {
        console.warn("🔥 [setToken] 유효하지 않은 토큰 무시");
        return;
      }

      // 토큰 저장
      window.fcmToken = token;
      window._tokenReady = true;

      console.log("🔥 [setToken] 토큰 준비 완료, 서버 전송 시도");

      // 조건 충족 시 즉시 전송 시도
      if (typeof trySendTokenToServer === 'function') {
        trySendTokenToServer();
      }
    };

    // ============================================
    // ③ 조건 충족 시 서버로 토큰 전송 (중복 방지)(현재 실행x)
    // ============================================
    function trySendTokenToServer() {
      // 이미 전송했으면 종료 (중복 방지)
      if (window._updateSent === true) {
        console.log("🔥 [trySendToken] 이미 전송 완료, 중복 방지");
        return;
      }

      // 토큰이 준비되지 않았으면 대기
      if (!window._tokenReady || !window.fcmToken) {
        console.log("🔥 [trySendToken] 토큰 미준비, 대기 중...");
        return;
      }

      // Socket이 준비되지 않았으면 대기
      if (!window._socketReady) {
        console.log("🔥 [trySendToken] Socket 미준비, 대기 중...");
        return;
      }

      // Socket 객체 확인
      if (typeof socket === 'undefined' || !socket || !socket.connected) {
        console.log("🔥 [trySendToken] Socket 객체 없음, 대기 중...");
        return;
      }

      // 사용자 ID 확인
      var user_id = getUserId();
      if (!user_id || user_id === '') {
        console.log("🔥 [trySendToken] 사용자 ID 없음, 대기 중...");
        return;
      }

      // 모든 조건 충족 → 1번만 실행
      console.log("🔥 [trySendToken] 모든 조건 충족! update_token 전송");
      console.log("🔥 [trySendToken] user_id:", user_id);
      console.log("🔥 [trySendToken] token:", window.fcmToken.substring(0, 20) + '...');

      try {
        socket.emit("update_token", {
          user_id: user_id,
          token: window.fcmToken
        });

        window._updateSent = true;
        console.log("🔥 [trySendToken] ✅ 전송 완료!");

        // 서버 로그 기록 (PHP debug_log 함수 호출)
        if (typeof $ !== 'undefined') {
          $.ajax({
            url: '/common_proc/log_token_send',
            type: 'POST',
            data: {
              user_id: user_id,
              token_preview: window.fcmToken.substring(0, 20) + '...',
              status: 'success'
            },
            timeout: 2000,
            error: function() {
              // 로그 실패해도 무시
            }
          });
        }

      } catch (error) {
        console.error("🔥 [trySendToken] ❌ 전송 실패:", error);
        window._updateSent = false; // 실패 시 재시도 가능하도록
      }
    }

    // ============================================
    // ④ 페이지 로드 시 기존 토큰 복구 및 재시도
    // ============================================
    (function() {
      // 페이지 로드 완료 후 실행
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTokenSystem);
      } else {
        initTokenSystem();
      }

      function initTokenSystem() {
        console.log("🔥 [initTokenSystem] 토큰 시스템 초기화");

        // 기존 토큰이 있으면 복구
        if (window.fcmToken) {
          console.log("🔥 [initTokenSystem] 기존 토큰 발견, 복구");
          window._tokenReady = true;
        }

        // Socket 연결 확인 및 재시도
        if (typeof socket !== 'undefined' && socket && socket.connected) {
          console.log("🔥 [initTokenSystem] Socket 이미 연결됨");
          window._socketReady = true;
        }

        // 조건 충족 시 즉시 전송 시도
        setTimeout(function() {
          if (typeof trySendTokenToServer === 'function') {
            trySendTokenToServer();
          }
        }, 500); // Socket 연결 대기 시간

        // Socket 연결 대기 (최대 3초)
        var checkCount = 0;
        var checkInterval = setInterval(function() {
          checkCount++;

          if (typeof socket !== 'undefined' && socket && socket.connected) {
            window._socketReady = true;
            if (typeof trySendTokenToServer === 'function') {
              trySendTokenToServer();
            }
            clearInterval(checkInterval);
          }

          if (checkCount >= 30) { // 3초 후 포기
            clearInterval(checkInterval);
            console.warn("🔥 [initTokenSystem] Socket 연결 타임아웃");
          }
        }, 100);
      }
    })();

    // 전역 함수들
    function getUserId() {
      if ($("#is_login").val() == "Y") {
        return $("#user_id").val();
      } else {
        return "";
      }
    }

    function getLocation() {
      var href = window.location.href;
      if (href.indexOf("/chat/chat_list") > -1 || href.indexOf("/chat/trade_list") > -1) {
        return "chat_list";
      } else if (href.indexOf("/chat/chat_view") > -1 || href.indexOf("/chat/trade_view") > -1) {
        return "chat_room";
      } else {
        return "site";
      }
    }
  </script>
  <!-- 기존 고정 버전 번호 방식 (파일 변경 시 캐시 문제 발생 가능) -->
  <!-- <script src="/chatting/js/common.js?v=10009"></script> -->
  <!-- <script src="/chatting/js/chatting.js?v=10009"></script> -->
  
  <!-- 파일 수정 시간 기반 버전 관리 (파일 변경 시 자동으로 새 버전 로드) -->
  <script src="/chatting/js/common.js?vs=<?= getFileVersion('/chatting/js/common.js') ?>"></script>
  <script src="/chatting/js/chatting.js?vs=<?= getFileVersion('/chatting/js/chatting.js') ?>"></script>

  <!-- Style -->
  <?= getCachCss(RES_PATH . "/css/keen-slider.min.css") ?>
  <!-- <?= getCachCss(RES_PATH . "/js/lib/mobiscroll.jquery.min.css") ?> -->
  <?= getCachCss(RES_PATH . "/css/main.css") ?>
  <?= getCachCss(RES_PATH . "/js/lib/preview/magnific-popup.css") ?>
  <?= getCachCss(RES_PATH . "/css/add.css") ?>

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-PXR9Q1W94K"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'G-PXR9Q1W94K');
  </script>

  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-TQC25Z8B');
  </script>
  <!-- End Google Tag Manager -->

  <!-- Meta Pixel Code -->
  <script>
    ! function(f, b, e, v, n, t, s) {
      if (f.fbq) return;
      n = f.fbq = function() {
        n.callMethod ?
          n.callMethod.apply(n, arguments) : n.queue.push(arguments)
      };
      if (!f._fbq) f._fbq = n;
      n.push = n;
      n.loaded = !0;
      n.version = '2.0';
      n.queue = [];
      t = b.createElement(e);
      t.async = !0;
      t.src = v;
      s = b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
      'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '869026064607445');
    fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=869026064607445&ev=PageView&noscript=1" /></noscript>
  <!-- End Meta Pixel Code -->
</head>

<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TQC25Z8B"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <style>
    /* 기본 스타일 */
    body {
      margin: 0;
      overflow: hidden;
    }

    /*
      .page-transition {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: #fff;
        z-index: 9999;
        / * transform-origin: right center; * /
        transform-origin: left center; / * 반대 방향에서 시작 * /
        transform: scaleX(0);
        / * transition: transform 0.6s ease-in-out; * /
        transition: transform 0.6s ease-in-out, box-shadow 0.6s ease-in-out;
        box-shadow: none;
      }
      .page-transition.active {
        / * perspective: 1000px;
        transform: rotateY(90deg);
        transition: transform 0.6s ease-in-out; * /
        transform: scaleX(1);
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.5); / * 흐릿한 음영 효과 * /
      }
      */
  </style>
  <!-- <div id="pageTransition" class="page-transition"></div> -->

  <div id="app">
    <?= $contents ?>
  </div><!-- //#app -->

  <?php
  // 기업회원일 때 기업정보 입력 팝업 포함
  if ($this->user->isLogin() && $this->user->getUserType() == 'COMP') {
    include_once(get_base_url() . '/application/shop/views/mobile/_partial/comp_info_popup.php');
  }
  ?>


  <!-- Script -->
  <?= getCashJs(RES_PATH . "/js/lib/lozad.min.js") ?>
  <?= getCashJs(RES_PATH . "/js/lib/keen-slider.js") ?>
  <?= getCashJs(RES_PATH . "/js/lib/wheel.min.js") ?>
  <?= getCashJs(RES_PATH . "/js/lib/rolldate.min.js") ?>
  <?= getCashJs(RES_PATH . "/js/lib/flatpickr.js") ?>
  <?= getCashJs(RES_PATH . "/js/lib/l10n/ko.js") ?>
  <?= getCashJs(RES_PATH . "/js/lib/plugins/monthSelect/index.js") ?>
  <?= getCashJs(RES_PATH . "/js/main.js") ?>

  <input type="hidden" id="is_login" name="is_login" value="<?= $this->user->isLogin() == true ? "Y" : "N" ?>" />
  <input type="hidden" id="user_id" name="user_id" value="<?= $this->user->isLogin() == true ? $this->user->getUserId() : "" ?>" />
  <input type="hidden" id="os_type" name="os_type" value="<?= $this->common->getSession("os_type") ?>" />
  <?php $current_user_info = $this->user->isLogin() ? $this->user->getCurrentUserInfo() : []; ?>
  <script>
    window.NOWJOB_COMP_INFO = {
      isLogin: <?= $this->user->isLogin() ? 'true' : 'false' ?>,
      userType: '<?= $this->user->isLogin() ? $this->user->getUserType() : '' ?>',
      // hasCompCert: true면 "기업회원이고 사업자등록번호가 입력/인증된 상태"를 의미합니다.
      hasCompCert: <?= ($this->user->isLogin() && $this->user->getUserType() == 'COMP' && !empty($current_user_info["mm_comp_num"])) ? 'true' : 'false' ?>
    };
  </script>

  <!-- 푸시 알림 클릭 처리 전역 스크립트 -->
  <script>
    /**
     * 푸시 알림 클릭 시 Android/iOS 네이티브 앱에서 호출되는 함수
     * @param {string} url - 푸시 알림으로 전달받은 URL
     * 
     * Android: 네이티브 앱에서 직접 JavaScript 함수 호출
     * iOS: 네이티브 앱에서 window.onPushOpen(url) 호출 필요
     */
    window.onPushOpen = function(url) {
      // URL이 유효한지 확인
      if (!url || url.trim() === '') {
        return;
      }

      try {
        // URL 정규화 (상대 경로인 경우 절대 경로로 변환)
        var normalizedUrl = url;
        if (url.startsWith('/')) {
          normalizedUrl = window.location.origin + url;
        }

        // 페이지 이동
        window.location.href = normalizedUrl;

      } catch (error) {
        // 에러 발생 시 기본 동작으로 fallback
        try {
          window.location.href = url;
        } catch (fallbackError) {
          // Fallback도 실패하면 무시
        }
      }
    };

    console.log('📱 onPushOpen 함수가 전역에 등록되었습니다.');
    // ❌ 사용되지 않는 함수: registerFcmTokenOnAppStart
      // 상태: Android에서 호출되지만 APP_NOWAPK.getFcmToken() 함수가 없어서 실패
    // 로그 분석 결과: "Android 앱 FCM 토큰 함수가 없음" 경고만 발생
    // 앱 시작 시 FCM 토큰 등록 시도(현재 앱측에서 미구현)
    function registerFcmTokenOnAppStart() {
      console.log('📱 앱 시작 시 FCM 토큰 등록 시도');

      // Android 앱에서 FCM 토큰 요청
      if (typeof APP_NOWAPK !== 'undefined' && typeof APP_NOWAPK.getFcmToken === 'function') {
        console.log('📱 Android 앱에서 FCM 토큰 요청');
        APP_NOWAPK.getFcmToken(); // 현재는 사용하지 않음
      } else {
        console.log('📱 Android 앱 FCM 토큰 함수가 없음');
      }
    }

    // 페이지 로드 시 FCM 토큰 등록 시도 (현재 앱측에서 미구현)
    $(document).ready(function() {
      console.log('📱 페이지 로드 완료 - FCM 토큰 등록 시도');
      setTimeout(registerFcmTokenOnAppStart, 1000); // 1초 후 실행 // ❌ 사용되지 않음: APP_NOWAPK.getFcmToken() 함수가 없음
      
      // 주기적으로 FCM 토큰 갱신 (5분마다)
         // ⚠️ 주의: refreshFcmToken() 함수가 APP_NOWAPK.getFcmToken() 함수가 없어서 실패함
      setInterval(function() {
        console.log('📱 주기적 FCM 토큰 갱신 시도');
        refreshFcmToken();// ❌ 사용되지 않음: APP_NOWAPK.getFcmToken() 함수가 없음
      }, 5 * 60 * 1000); // 5분마다 실행
    });

    // 앱이 포그라운드로 돌아올 때 FCM 토큰 갱신 (현재 앱측에서 미구현)
    document.addEventListener('visibilitychange', function() {
      if (!document.hidden) {
        console.log('📱 앱이 포그라운드로 돌아옴 - FCM 토큰 갱신 시도');
        setTimeout(refreshFcmToken, 500); // 0.5초 후 실행 // ❌ 사용되지 않음: APP_NOWAPK.getFcmToken() 함수가 없음
      }
    });

    // FCM 토큰 갱신 함수 (기존 토큰과 비교하여 변경시에만 갱신) (현재 앱측에서 미구현)
    function refreshFcmToken() {
      console.log('📱 FCM 토큰 갱신 시도');

      // Android 앱에서 FCM 토큰 요청
      if (typeof APP_NOWAPK !== 'undefined' && typeof APP_NOWAPK.getFcmToken === 'function') {
        console.log('📱 Android 앱에서 FCM 토큰 갱신 요청');
        APP_NOWAPK.getFcmToken();
      } else {
        console.log('📱 Android 앱 FCM 토큰 함수가 없음');
      }
    }








    // 쿠키 가져오기 함수
    function getCookie(name) {
      var value = "; " + document.cookie;
      var parts = value.split("; " + name + "=");
      if (parts.length == 2) return parts.pop().split(";").shift();
      return '';
    }

    function setCookie(name, value, days) {
      var expires = "";
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    // ❌ 사용되지 않는 함수: onFcmTokenUpdate
    // 상태: Android에서 정의되어 있지만 네이티브 앱에서 호출되지 않음
    // 로그 분석 결과: Android 로그에서 이 함수 호출 기록 없음
    // 용도: Android 네이티브 앱에서 FCM 토큰을 받아서 서버로 전송하는 함수
    // 문제: Android 네이티브 앱에서 window.onFcmTokenUpdate(token) 호출이 없음
    // 해결: Android 네이티브 앱에서 FCM 토큰을 받으면 이 함수를 호출해야 함
    window.onFcmTokenUpdate = function(token) {
      console.log('📱 Android FCM 토큰 업데이트 - 토큰: ' + (token ? token.substring(0, 20) + '...' : '없음'));

      
      if (token) {
      
        var existingToken = getCookie('fcm_token') || '';
        var isTokenChanged = (existingToken !== token);

        console.log('📱 토큰 변경 여부: ' + (isTokenChanged ? '변경됨' : '동일함'));

        
        if (isTokenChanged || existingToken === '') {
       

          $.ajax({
            url: '/common_proc/refresh_token', 
            type: 'POST',
            data: {
              token: token,
              uuid: getCookie('uuid') || ''
            },
            success: function(response) {
              console.log('📱 FCM 토큰 갱신 성공 - 응답: ' + JSON.stringify(response));
              if (response.code === "00") {
                console.log('✅ FCM 토큰이 성공적으로 갱신되었습니다.');
              } else {
                console.error('❌ FCM 토큰 갱신 실패: ' + response.msg);
              }
            },
            error: function(xhr, status, error) {
              console.error('📱 FCM 토큰 갱신 실패:', error);
              console.error('상태 코드:', xhr.status);
              console.error('응답 텍스트:', xhr.responseText);
            }
          });
        } else {
          console.log('📱 토큰이 동일하므로 갱신 건너뜀');
        }
      } else {
        console.warn('⚠️ FCM 토큰이 비어있습니다.');
      }
    };

    // 로그인 성공 후 FCM 토큰을 로그인한 사용자와 연결하는 함수 (Promise 반환)
    window.connectFCMTokenToUser = function() {
      return new Promise(function(resolve, reject) {
        var deviceUuid = getCookie('uuid') || '';
        console.log('🔐 로그인 성공 후 FCM 토큰을 사용자와 연결 시작', deviceUuid);

        if (!deviceUuid) {
          console.warn('⚠️ 디바이스 UUID가 없어서 연결을 건너뜁니다.');
          resolve(false); // UUID 없어도 페이지 이동은 진행
          return;
        }

        $.ajax({
          url: '/common_proc/connect_token_to_user',
          type: 'POST',
          data: {
            uuid: deviceUuid
          },
          timeout: 3000, // 3초 타임아웃 설정
          success: function(response) {
            console.log('🔐 FCM 토큰 사용자 연결 성공 - 응답: ' + JSON.stringify(response));
            if (response.code === "00") {
              console.log('✅ FCM 토큰이 로그인한 사용자와 성공적으로 연결되었습니다.');
              resolve(true);
            } else {
              console.error('❌ FCM 토큰 사용자 연결 실패: ' + response.msg);
              resolve(false); // 실패해도 페이지 이동은 진행
            }
          },
          error: function(xhr, status, error) {
            console.error('🔐 FCM 토큰 사용자 연결 실패:', error);
            console.error('상태 코드:', xhr.status);
            console.error('응답 텍스트:', xhr.responseText);
            resolve(false); // 에러가 나도 페이지 이동은 진행
          }
        });
      });
    };
  </script>
</body>

</html>
<script>
  // iOS 권한 결과 콜백이 없는 페이지에서도 공통 처리
  (function initLocationPermissionHandler() {
    if (typeof window.onLocationPermissionResult === 'function') return;

    function normalizePermissionStatus(value) {
      if (value === true || value === "true" || value === "granted" || value === 1 || value === "1") {
        return "granted";
      }
      if (value === false || value === "false" || value === "denied" || value === 0 || value === "0" || value === "never_ask_again") {
        return value === "never_ask_again" ? "never_ask_again" : "denied";
      }
      if (value === "restricted") return "restricted";
      if (value === "not_determined") return "not_determined";
      return null;
    }

    window.onLocationPermissionResult = function(status) {
      var normalized = normalizePermissionStatus(status);
      if (!normalized) return;

      try {
        localStorage.setItem("nowjob_location_permission_status", normalized);
      } catch (e) {}

      if (normalized === "denied" || normalized === "never_ask_again") {
        if (window.location.pathname !== "/member/join_mem_finish") {
          window.location.href = "/member/join_mem_finish";
        }
      }
    };
  })();

  // 방문 기록 관리
  (function manageHistory() {
    const currentPath = window.location.pathname; // 현재 페이지 경로 (쿼리 파라미터 제외)
    const currentPage = window.location.pathname + window.location.search; // 현재 페이지 전체 URL (쿼리 파라미터 포함)
    const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || []; // 방문 기록 스택

    // splash 페이지는 히스토리 스택에 추가하지 않음 (뒤로가기 시 splash로 이동하는 문제 방지)
    if (currentPath === '/home/splash') {
      return;
    }

    const compagePaths1 = [
      "/compage/point_promotion_buy",
      "/compage/point_charge_step1",
      "/compage/point_charge_list",
      "/compage/point_use_list",
      "/compage/point_promotion_list"
    ];
    const compagePaths2 = [
      "/compage/bookmark_person1",
      "/compage/bookmark_person2"
    ];
    const compagePaths3 = [
      "/compage/coupon_list"
    ];
    const mypagePaths1 = [
      "/mypage/point_charge_step1",
      "/mypage/point_charge_list",
      "/mypage/point_use_list",
      "/mypage/point_promotion_buy",
      "/mypage/point_promotion_list"
    ];
    const mypagePaths2 = [
      "/mypage/bookmark_recruit1",
      "/mypage/bookmark_recruit2"
    ];

    // 마지막 페이지 확인 (경로만 추출)
    const lastPage = historyStack[historyStack.length - 1];
    const lastPath = lastPage ? lastPage.split('?')[0] : '';

    // 히스토리 대체 로직 (같은 그룹 내 이동 시 대체)
    const isInCompagePaths1 = compagePaths1.includes(currentPath) && compagePaths1.includes(lastPath);
    const isInCompagePaths2 = compagePaths2.includes(currentPath) && compagePaths2.includes(lastPath);
    const isInCompagePaths3 = compagePaths3.includes(currentPath) && compagePaths3.includes(lastPath);
    const isInMypagePaths1 = mypagePaths1.includes(currentPath) && mypagePaths1.includes(lastPath);
    const isInMypagePaths2 = mypagePaths2.includes(currentPath) && mypagePaths2.includes(lastPath);

    if (isInCompagePaths1 || isInCompagePaths2 || isInCompagePaths3 || isInMypagePaths1 || isInMypagePaths2) {
      // 조건에 맞는 경우 마지막 기록을 대체 (전체 URL 포함)
      historyStack[historyStack.length - 1] = currentPage;
      // 업데이트된 기록을 세션 저장소에 저장
      sessionStorage.setItem('historyStack', JSON.stringify(historyStack));

    } else {
      // 중복 기록 방지 (전체 URL 비교)
      if (historyStack[historyStack.length - 1] !== currentPage) {
        historyStack.push(currentPage); // 현재 페이지 추가 (전체 URL 포함)
        sessionStorage.setItem('historyStack', JSON.stringify(historyStack)); // 업데이트된 기록 저장
      }
    }

    //console.log("History Stack:", historyStack); // 디버깅용 출력

  })();

  // 뒤로 가기 제어
  function customBack() {

    //const pageTransition = document.getElementById('pageTransition');
    //pageTransition.classList.add('active'); // 애니메이션 시작

    // 페이지 이동 실행
    //setTimeout(() => {

    // chat_view 페이지는 자체 뒤로가기 로직 사용 (from 파라미터 기반)
    const currentPath = window.location.pathname;
    if (currentPath.indexOf('/chat/chat_view') > -1 || currentPath.indexOf('/chat/trade_view') > -1) {
      // URL 파라미터에서 from 값 읽기
      const params = new URLSearchParams(window.location.search);
      const from = params.get('from') || '';
      
      // from 파라미터에 따른 뒤로가기 URL 매핑
      const backMap = {
        applied: '/chat/chat_list', // 지원한 공고
        proposed_job: '/chat/chat_list2', // 제안받은 공고
        talent_received: '/chat/index_comp', // 제안받은 인재
        talent_sent: '/chat/chat_list3' // 제안보낸 인재
      };
      
      const backUrl = backMap[from] || '/chat/chat_list';
      
      // historyStack에서 현재 페이지 제거
      const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || [];
      if (historyStack.length > 0 && historyStack[historyStack.length - 1].indexOf('/chat/chat_view') > -1) {
        historyStack.pop();
        sessionStorage.setItem('historyStack', JSON.stringify(historyStack));
      }
      
      // 해당 페이지로 이동
      window.location.href = backUrl;
      return;
    }

    // 채용공고 작성 페이지들은 compage/recruit_list로 이동
    if (currentPath.indexOf('/compage/recruit_write1_1') > -1 || 
        currentPath.indexOf('/compage/recruit_write2_1') > -1 || 
        currentPath.indexOf('/compage/recruit_write3_1') > -1) {
    
      // historyStack은 사용자가 방문(이동)한 페이지들의 경로(주소)를 순서대로 담고 있는 배열입니다.
      // 이 배열은 sessionStorage에 저장되며, SPA식 동작이나 뒤로가기/앞으로가기 처리를 위해 사용됩니다.
      // 가장 최근에 방문한 페이지가 배열의 마지막에 위치합니다.
      const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || [];
      if (historyStack.length > 0) {
        // historyStack의 마지막(가장 최근) 방문 페이지를 가져옵니다.
        const lastPage = historyStack[historyStack.length - 1]; // historyStack.length - 1은 배열의 마지막 인덱스를 의미
        if (lastPage.indexOf('/compage/recruit_write1_1') > -1 || 
            lastPage.indexOf('/compage/recruit_write2_1') > -1 || 
            lastPage.indexOf('/compage/recruit_write3_1') > -1) {
          // 배열 historyStack에서 현재 페이지(마지막 요소)를 제거
          historyStack.pop();
          sessionStorage.setItem('historyStack', JSON.stringify(historyStack));
        }
      }
      
      // compage/recruit_list로 이동
      window.location.href = '/compage/recruit_list';
      return;
    }

    // 이력서 작성 페이지들은 mypage/resume_list로 이동
    if (currentPath.indexOf('/mypage/resume_write1') > -1 || 
        currentPath.indexOf('/mypage/resume_write4') > -1) {
      // historyStack에서 현재 페이지 제거
      const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || [];
      if (historyStack.length > 0) {
        const lastPage = historyStack[historyStack.length - 1];
        if (lastPage.indexOf('/mypage/resume_write1') > -1 || 
            lastPage.indexOf('/mypage/resume_write4') > -1) {
          historyStack.pop();
          sessionStorage.setItem('historyStack', JSON.stringify(historyStack));
        }
      }
      
      // mypage/resume_list로 이동
      window.location.href = '/mypage/resume_list';
      return;
    }

    const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || [];

    // 방문 기록이 2개 이상일 때만 동작
    if (historyStack.length > 1) {
      historyStack.pop(); // 현재 페이지 제거
      let previousPage = historyStack[historyStack.length - 1]; // 이전 페이지 가져오기
      
      // 이전 페이지가 splash이면 splash를 제거하고 그 다음 페이지 확인
      if (previousPage && previousPage.indexOf('/home/splash') > -1) {
        historyStack.pop(); // splash 제거
        if (historyStack.length > 0) {
          previousPage = historyStack[historyStack.length - 1]; // 그 다음 페이지 가져오기
        } else {
          // 히스토리가 없으면 홈으로 이동
          previousPage = '/home/index';
        }
      }
      
      // 이전 페이지가 여전히 splash이면 홈으로 이동
      if (previousPage && previousPage.indexOf('/home/splash') > -1) {
        previousPage = '/home/index';
      }
      
      sessionStorage.setItem('historyStack', JSON.stringify(historyStack)); // 기록 업데이트
      window.location.href = previousPage; // 이전 페이지로 이동
    } else {
      // 히스토리가 없거나 1개만 있으면 홈으로 이동
      window.location.href = '/home/index';
    }

    //}, 600); // 애니메이션 지속 시간과 일치


  }
  //manageHistory();


  // 전역 변수: 스와이프 뒤로가기 비활성화 플래그 (기본값 false)
  // 'true'는 스와이프 뒤로가기(화면을 왼쪽에서 오른쪽으로 밀어서 이전 페이지로 가는 동작)를 "비활성화"하라는 의미입니다.
  // 이 플래그가 true면 iOS 등에서 스와이프 제스처로 뒤로가기가 동작하지 않도록 막습니다.
  window.DISABLE_SWIPE_BACK = window.DISABLE_SWIPE_BACK || false;

  // 특정 페이지에서 스와이프 뒤로가기 비활성화
  (function() {
    const currentPath = window.location.pathname;

    // 홈페이지 경로 확인: "/", "/home", "/home/index"
    const isHomePage = currentPath === '/' ||
      currentPath === '/home' ||
      currentPath === '/home/index' ||
      currentPath.startsWith('/home/index');

    // 스와이프 뒤로가기 비활성화 페이지 목록
    const disableSwipePages = [
      '/mypage/resume_list',
      '/compage/recruit_list',
      '/resume/hr_list',
      '/resume/hr_map',  // 인재 지도 페이지 추가 (핀치 제스처 충돌 방지)
      '/recruit/job_list',
      '/recruit/job_map',  // 지도 페이지 추가 (핀치 제스처 충돌 방지)
      '/chat/chat_list',
      '/chat/chat_list2'
    ];
    const isDisableSwipePage = disableSwipePages.includes(currentPath);

    if (isHomePage) {
      window.DISABLE_SWIPE_BACK = true;
      console.log('🏠 홈페이지에서 스와이프 뒤로가기 비활성화');
    } else if (isDisableSwipePage) {
      window.DISABLE_SWIPE_BACK = true;
      console.log('🚫 ' + currentPath + ' 페이지에서 스와이프 뒤로가기 비활성화');
    }
  })();

  let startX = 0;
  let endX = 0;
  const screenWidth = window.innerWidth; // 화면 너비

  // iOS 감지 함수
  function isIOS() {
    return /iPhone|iPad|iPod/i.test(navigator.userAgent);
  }
// JavaScript로 구현한 iOS 스와이프 뒤로가기 방지 로직
  if (isIOS()) {
    let isSwipeBackGesture = false; // 스와이프 뒤로가기 제스처 여부

    // 사용자가 화면을 터치(손가락을 대기 시작)할 때 발생하는 이벤트입니다.
    document.addEventListener('touchstart', (event) => {
      // 스와이프 비활성화 플래그가 true이면 동작 중지
      if (window.DISABLE_SWIPE_BACK) return;

      // 핀치 제스처(2손가락 이상)는 스와이프로 인식하지 않음
      if (event.touches.length >= 2) return;

      //console.log('touchstart');
      startX = event.touches[0].clientX;
      isSwipeBackGesture = false; // 초기화
    }, { passive: true });

    document.addEventListener('touchmove', (event) => {
      // 스와이프 비활성화 플래그가 true이면 동작 중지
      if (window.DISABLE_SWIPE_BACK) return;

      // 핀치 제스처(2손가락 이상)는 스와이프로 인식하지 않음
      if (event.touches.length >= 2) {
        isSwipeBackGesture = false; // 핀치 제스처 중에는 스와이프 플래그 해제
        return;
      }

      endX = event.touches[0].clientX;
      
      // 왼쪽 가장자리에서 시작하고 오른쪽으로 스와이프하는 경우
      if (startX < screenWidth * 0.1 && endX - startX > 30) {
        isSwipeBackGesture = true;
        // iOS 네이티브 스와이프 뒤로가기 방지
        event.preventDefault();
      }
    }, { passive: false });

    document.addEventListener('touchend', (event) => {
      // 스와이프 비활성화 플래그가 true이면 동작 중지
      if (window.DISABLE_SWIPE_BACK) return;

      // 핀치 제스처 중에는 뒤로가기 실행하지 않음
      if (event.touches.length >= 2) {
        isSwipeBackGesture = false;
        return;
      }

      if (isSwipeBackGesture && startX < screenWidth * 0.1 && endX - startX > 50) {
        // iOS 네이티브 스와이프 뒤로가기 방지
        event.preventDefault();
        // 아이폰에서만 왼쪽으로 스와이프 시 뒤로 가기
        //history.back(); // 이전 페이지로 이동
        customBack();
      }
      
      // 초기화
      isSwipeBackGesture = false;
      startX = 0;
      endX = 0;
    }, { passive: false });
  }

  if (typeof socket !== 'undefined' && socket && typeof socket.on === 'function') {
    socket.on('connect_error', function(error) {
      console.error('Socket connection error:', error);

      // 사용자에게 경고 메시지 표시
      //alert('서버 연결에 실패했습니다.\n다시 연결합니다.');

      // 화면 새로고침
      //setTimeout(function () {
      //    location.reload();
      //}, 2000); // 2초 후 새로고침
    });
  }
</script>
