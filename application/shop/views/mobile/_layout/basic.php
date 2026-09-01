<?php
$app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
$app_base_host = parse_url($app_base_url, PHP_URL_HOST);
$is_nowjob_domain = ($app_base_host === 'nowjob.co.kr');
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
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"> -->
  <meta name="viewport" content="width=460, user-scalable=no">
  <title>나우잡 | 내 주변 일자리. 맞춤형 구인구직 플랫폼</title>
  <?php if ($is_nowjob_domain) : ?>
    <meta name="description" content="내 주변 지금 뜨는, 좋은 일자리 나우잡(NOWJOB)! 나에게 딱맞는 채용 정보와 전국 직종별 공고를 지금 바로 확인해보세요.">
    <meta name="format-detection" content="address=no, email=no, telephone=no">
    <meta name="naver-site-verification" content="20459c5bd907c133c314dd88161a57f212fce831" />
    <meta property="og:description" content="내 주변 지금 뜨는, 좋은 일자리 나우잡(NOWJOB)! 나에게 딱맞는 채용 정보와 전국 직종별 공고를 지금 바로 확인해보세요.">
    <meta name="keywords" content="나우잡, NOWJOB,구인구직, 외국인채용, 알바, 급구, 일자리, 채용, 일당">
  <?php endif; ?>
  <?php
  $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
  $is_login_page = (strpos($request_uri, '/member/login') !== false) || (strpos($request_uri, '/member/nowjob_login') !== false);
  $is_home_page = (strpos($request_uri, '/home') !== false) || (strpos($request_uri, '/index_mem') !== false);
  $is_splash_page = (strpos($request_uri, '/home/splash') !== false);
  ?>
  <?php if ($is_login_page) : ?>
    <link rel="preload" as="image" href="/resource/shop/mobile/img/member/nowjob-back-logo.v3.jpg?vs=<?= getFileVersion('/resource/shop/mobile/img/member/nowjob-back-logo.v3.jpg') ?>" fetchpriority="high">
    <link rel="preload" as="image" href="/resource/shop/mobile/img/member/ic-naver-login.svg?vs=<?= getFileVersion('/resource/shop/mobile/img/member/ic-naver-login.svg') ?>">
    <link rel="preload" as="image" href="/resource/shop/mobile/img/member/ic-kakao-login.svg?vs=<?= getFileVersion('/resource/shop/mobile/img/member/ic-kakao-login.svg') ?>">
    <link rel="preload" as="image" href="/resource/shop/mobile/img/member/ic-google-login.svg?vs=<?= getFileVersion('/resource/shop/mobile/img/member/ic-google-login.svg') ?>">
    <link rel="preload" as="image" href="/resource/shop/mobile/img/member/ic-nowjob-login.svg?vs=<?= getFileVersion('/resource/shop/mobile/img/member/ic-nowjob-login.svg') ?>">
  <?php elseif ($is_splash_page) : ?>
    <link rel="preload" as="image" href="/resource/shop/mobile/img/splash.jpg?vs=<?= getFileVersion('/resource/shop/mobile/img/splash.jpg') ?>">
  <?php elseif ($is_home_page) : ?>
    <link rel="prefetch" as="image" href="/resource/shop/mobile/img/member/nowjob-back-logo.v3.jpg">
  <?php endif; ?>
  <link rel="shortcut icon" href="/resource/favicon.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Script -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <?= getCashJs(RES_PATH . "/js/lib/jquery-3.5.1.min.js") ?>
  <!-- <?= getCashJs(RES_PATH . "/js/lib/mobiscroll.jquery.min.js") ?> -->
  <?= getCashJs(RES_PATH . "/js/lib/proj4.js") ?>
  <?= getCashJs(RES_PATH . "/js/lib/preview/jquery.magnific-popup.min.js") ?>
  <?= getCashJs(RES_PATH . "/js/add.js") ?>
  <?= getCashJs(RES_PATH . "/js/format-utils.js") ?>
  <?= getCashJs(RES_PATH . "/js/logger.js") ?>
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

          createSocket();
        };
        script.onerror = function() {

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
      });

      socket.on('disconnect', function() {
        console.log('Socket.IO 연결 끊김');
      });

      socket.on('connect_error', function(error) {
        console.error('Socket.IO 연결 오류:', error);
      });
    }


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

  // 경로 정규화 함수 (trailing slash, 동적 ID 등 일관성 유지)
  function normalizePath(path) {
    if (!path) return '';
    // 쿼리 파라미터 제거
    return path.split('?')[0].replace(/\/$/, ''); // trailing slash 제거
  }

  // 커스텀 방문 기록 관리 같은 페이지 중복 방지(히스토리에 push 하지 않음), splash 페이지 예외 처리 포함
  (function manageHistory() {
    const rawCurrentPath = window.location.pathname;
    const currentPath = normalizePath(rawCurrentPath); // 정규화된 경로
    const currentPageFull = window.location.pathname + window.location.search;
    const currentPage = normalizePath(currentPageFull); // 정규화된 full URL ex) /chat/chat_view/123?from=applied 
    // 문자열 -> 객체로 변환 historyStack = ['/home/main', '/product/list', '/product/list/']
    const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || []; // 방문 기록 스택

    // splash 페이지는 히스토리 스택에 추가하지 않음 (뒤로가기 시 splash로 이동하는 문제 방지)
    if (currentPath === '/home/splash') {
      return;
    }

    // ⭐ 연속된 중복 제거 ['/home/main', '/product/list', '/product/list/'] => ['/home/main', '/product/list']
    while (historyStack.length > 1) {
      const last = normalizePath(historyStack[historyStack.length - 1]);
      const prev = normalizePath(historyStack[historyStack.length - 2]);
      if (last === prev) {
        historyStack.pop();
      } else {
        break;
      }
    }

    // 마지막 페이지 확인
    const lastPageRaw = historyStack[historyStack.length - 1]; // 마지막 페이지를 의미
    const lastPath = normalizePath(lastPageRaw);

    // ⭐ 같은 페이지면 무시
    if (lastPath === currentPath) {
      return;
    }

    // ⭐ 새로운 항목 추가 (모든 다른 페이지)
    historyStack.push(currentPage);

    sessionStorage.setItem('historyStack', JSON.stringify(historyStack));

  })();

  // ⭐ customBack() 함수는 add.js에서 로드됨 (모든 페이지에서 사용 가능)
  // iOS 스와이프 뒤로가기 시 네이티브에서 호출



  // Android는 네이티브 앱에서 customBack() 함수를 직접 호출하여 뒤로가기 처리
  // iOS는 네이티브 앱의 allowsBackForwardNavigationGestures 설정으로 제어

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