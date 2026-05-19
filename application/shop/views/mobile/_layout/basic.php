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
    <link rel="preload" as="image" href="/resource/shop/mobile/img/member/nowjob-back-logo.v3.jpg">
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

  // 방문 기록 관리
  (function manageHistory() {
    const rawCurrentPath = window.location.pathname;
    const currentPath = normalizePath(rawCurrentPath); // 정규화된 경로
    const currentPageFull = window.location.pathname + window.location.search;
    const currentPage = normalizePath(currentPageFull); // 정규화된 full URL
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
    const mypagePaths3 = [
      "/mypage/resume_list",
      "/mypage/resume_view"
    ];

    // ⭐ 연속된 중복 제거
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
    const lastPageRaw = historyStack[historyStack.length - 1];
    const lastPath = normalizePath(lastPageRaw);

    // ⭐ 같은 페이지면 무시
    if (lastPath === currentPath) {
      return;
    }

    // ⭐ 새로운 항목 추가 (모든 다른 페이지)
    historyStack.push(currentPage);

    sessionStorage.setItem('historyStack', JSON.stringify(historyStack));

  })();

  // ⭐ customBack() - 히스토리 스택 처리 후 네이티브 뒤로가기
  function customBack() {
    try {

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
        resetBackFlag(); // ⭐ return 전 플래그 초기화
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
        resetBackFlag(); // ⭐ return 전 플래그 초기화
        window.location.href = '/compage/recruit_list';
        return;
      }

      // 이력서 작성 페이지들은 mypage/resume_list로 이동
      if (currentPath.indexOf('/mypage/resume_write1') > -1) {
        // historyStack에서 현재 페이지 제거
        const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || [];
        if (historyStack.length > 0) {
          const lastPage = historyStack[historyStack.length - 1];
          if (lastPage.indexOf('/mypage/resume_write1') > -1) {
            historyStack.pop();
            sessionStorage.setItem('historyStack', JSON.stringify(historyStack));
          }
        }

        // mypage/resume_list로 이동
        resetBackFlag(); // ⭐ return 전 플래그 초기화
        window.location.href = '/mypage/resume_list';
        return;
      }

      const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || [];
      const normalizedCurrentPath = normalizePath(currentPath);

      // 현재 페이지가 스택에 있으면 제거 (중복 방지)
      while (historyStack.length > 0) {
        const lastPagePath = normalizePath(historyStack[historyStack.length - 1]);
        if (lastPagePath === normalizedCurrentPath) {
          historyStack.pop();
        } else {
          break;
        }
      }

      // 방문 기록이 1개 이상일 때만 동작
      if (historyStack.length > 0) {
        let previousPage = historyStack[historyStack.length - 1]; // 이전 페이지 가져오기
        let normalizedPreviousPath = normalizePath(previousPage); // 이전 페이지도 정규화

        // splash 페이지는 제거 (여러 개일 경우 모두 제거)
        while (historyStack.length > 0 && normalizedPreviousPath === '/home/splash') {
          historyStack.pop();
          if (historyStack.length > 0) {
            previousPage = historyStack[historyStack.length - 1];
            normalizedPreviousPath = normalizePath(previousPage);
          }
        }

        // splash 제거 후에도 스택이 있으면 그 페이지로, 없으면 홈으로
        if (historyStack.length === 0) {
          previousPage = '/home/index';
        }

        sessionStorage.setItem('historyStack', JSON.stringify(historyStack));
        window.location.href = previousPage;
      } else {
        // 히스토리가 없으면 홈으로 이동
        window.location.href = '/home/index';
      }

      //}, 600); // 애니메이션 지속 시간과 일치

    } catch (e) {
      console.error('[customBack] 오류:', e);
    }
  }
  //manageHistory();


  // 전역 변수: 스와이프 뒤로가기 비활성화 플래그 (기본값 false)
  // 'true'는 스와이프 뒤로가기(화면을 왼쪽에서 오른쪽으로 밀어서 이전 페이지로 가는 동작)를 "비활성화"하라는 의미입니다.
  // 이 플래그가 true면 iOS 등에서 스와이프 제스처로 뒤로가기가 동작하지 않도록 막습니다.
  window.DISABLE_SWIPE_BACK = window.DISABLE_SWIPE_BACK || false;

  // 특정 페이지에서 스와이프 뒤로가기 비활성화 (Android만 적용)
  // iOS는 네이티브 앱의 allowsBackForwardNavigationGestures로 제어
  (function() {
    // Android 감지
    function isAndroid() {
      return /Android/i.test(navigator.userAgent);
    }

    // Android에서만 JavaScript 스와이프 제어 적용
    if (!isAndroid()) {
      return;
    }

    const currentPath = window.location.pathname;

    // 홈페이지 경로 확인: "/", "/home", "/home/index"
    const isHomePage = currentPath === '/' ||
      currentPath === '/home' ||
      currentPath === '/home/index' ||
      currentPath.startsWith('/home/index');

    // Android에서 스와이프 뒤로가기 비활성화 페이지 목록
    const disableSwipePages = [
      '/resume/hr_list',
      '/resume/hr_map', // 인재 지도 페이지 추가 (핀치 제스처 충돌 방지)
      '/recruit/job_list',
      '/recruit/job_map', // 지도 페이지 추가 (핀치 제스처 충돌 방지)
      '/chat/chat_list',
      '/chat/chat_list2'
    ];
    const isDisableSwipePage = disableSwipePages.includes(currentPath);

    if (isHomePage || isDisableSwipePage) {
      window.DISABLE_SWIPE_BACK = true;
    }
  })();

  let startX = 0;
  let endX = 0;
  const screenWidth = window.innerWidth; // 화면 너비
  let isSwipeProcessing = false; // ⭐ 스와이프 처리 중 플래그 (중복 방지)

  // Android만 JavaScript 스와이프 리스너 등록
  // iOS는 네이티브 앱의 allowsBackForwardNavigationGestures 설정으로 제어
  function isAndroid() {
    return /Android/i.test(navigator.userAgent);
  }

  if (isAndroid()) {
    let isSwipeBackGesture = false; // 스와이프 뒤로가기 제스처 여부

    // 사용자가 화면을 터치(손가락을 대기 시작)할 때 발생하는 이벤트입니다.
    document.addEventListener('touchstart', (event) => {

      // 스와이프 비활성화 플래그가 true이면 동작 중지
      if (window.DISABLE_SWIPE_BACK) {
        return;
      }

      // 핀치 제스처(2손가락 이상)는 스와이프로 인식하지 않음
      if (event.touches.length >= 2) {
        return;
      }

      startX = event.touches[0].clientX;
      isSwipeBackGesture = false; // 초기화
    }, {
      passive: true
    });

    document.addEventListener('touchmove', (event) => {

      // 스와이프 비활성화 플래그가 true이면 동작 중지
      if (window.DISABLE_SWIPE_BACK) return;

      // 핀치 제스처(2손가형 이상)는 스와이프로 인식하지 않음
      if (event.touches.length >= 2) {
        isSwipeBackGesture = false;
        return;
      }

      endX = event.touches[0].clientX;
      var deltaX = endX - startX;

      // 왼쪽 가장자리에서 시작하고 오른쪽으로 스와이프하는 경우
      if (startX < screenWidth * 0.1 && deltaX > 30) {
        isSwipeBackGesture = true;
        event.preventDefault();
      } else {
        isSwipeBackGesture = false;
      }
    }, {
      passive: false
    });

    document.addEventListener('touchend', (event) => {
      // 스와이프 비활성화 플래그가 true이면 동작 중지
      if (window.DISABLE_SWIPE_BACK) {
        return;
      }

      // ⭐ 스와이프 중복 처리 방지
      if (isSwipeProcessing) {
        isSwipeBackGesture = false;
        startX = 0;
        endX = 0;
        return;
      }

      // 핀치 제스처 중에는 뒤로가기 실행하지 않음
      if (event.touches.length >= 2) {
        isSwipeBackGesture = false;
        return;
      }

      var deltaX = endX - startX;

      if (isSwipeBackGesture && startX < screenWidth * 0.1 && deltaX > 50) {
        // ⭐ 스와이프 처리 시작: 플래그 설정
        isSwipeProcessing = true;

        // Android에서 왼쪽으로 스와이프 시 뒤로 가기
        event.preventDefault();
        customBack();

        // 500ms 동안 추가 스와이프 무시
        setTimeout(() => {
          isSwipeProcessing = false;
        }, 500);
      }

      // 초기화
      isSwipeBackGesture = false;
      startX = 0;
      endX = 0;
    }, {
      passive: false
    });
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