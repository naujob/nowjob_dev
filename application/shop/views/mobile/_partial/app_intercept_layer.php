<!-- app 인터셉트 레이어 스타일 -->
<style>
  #app-intercept-layer {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    justify-content: center;
    padding: 0 1rem 1rem;
    z-index: 1300;
    pointer-events: none;
    transform: translateY(110%);
    transition: transform 0.3s ease, opacity 0.3s ease;
    opacity: 0;
  }

  #app-intercept-layer.visible {
    transform: translateY(0);
    opacity: 1;
    pointer-events: auto;
  }

  #app-intercept-layer .app-intercept-panel {
    width: 100%;
    max-width: 480px;
    background: #fff;
    border-radius: 16px 16px 0 0;
    box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.2);
    padding: 1.25rem;
    border: 1px solid #f0f0f0;
    text-align: center;
  }

  #app-intercept-layer .app-intercept-label {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #57CCAF;
    margin-bottom: 0.35rem;
  }

  #app-intercept-layer .app-intercept-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
    color: #1b1b1b;
  }

  #app-intercept-layer .app-intercept-description {
    margin: 0.35rem 0 1.15rem;
    font-size: 0.95rem;
    color: #5b5b5b;
  }

  #app-intercept-layer .app-intercept-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
  }

  #app-intercept-layer .app-intercept-actions button {
    flex: 1 1 45%;
    min-width: 105px;
    height: 60px;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    border: none;
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
  }

  #app-intercept-layer .app-intercept-actions .app-intercept-open {
    background: linear-gradient(135deg, #57CCAF, #57CCAF);
    color: #fff;
  }

  #app-intercept-layer .app-intercept-actions .app-intercept-close {
    background: #f0f0f0;
    color: #333;
  }
</style>

<!-- app 인터셉트 레이어 -->
<div id="app-intercept-layer" aria-hidden="true">
  <div class="app-intercept-panel">
    <p class="app-intercept-label">나우잡</p>
    <p class="app-intercept-title">앱에서 더 빠르게 NOW JOB</p>
    <p class="app-intercept-description">앱으로 이동하면 알림과 맞춤 추천을 바로 확인할 수 있어요.</p>
    <div class="app-intercept-actions">
      <button type="button" class="app-intercept-open">앱으로 볼래요</button>
      <button type="button" class="app-intercept-close">이대로 볼래요</button>
    </div>
  </div>
</div>

<script>
  // app 인터셉트 레이어 기능
  (function() {
    var appInterceptLayer = document.getElementById('app-intercept-layer');
    if (!appInterceptLayer) return;

    // 앱 환경 감지 함수 (app-deeplink.js와 동일한 로직)
    function isNativeApp() {
      var userAgent = navigator.userAgent.toLowerCase();

      // app-deeplink.js에서 사용하는 것과 동일한 패턴
      // 앱 내부에서 접속한 경우: User-Agent에 nowjobapp_android 또는 nowjobapp_ios 포함
      if (/nowjobapp_android|nowjobapp_ios/i.test(userAgent)) {
        return true;
      }

      return false;
    }

    // 앱 환경이면 레이어 숨기고 종료
    if (isNativeApp()) {
      appInterceptLayer.style.display = 'none';
      return;
    }

    var openBtn = appInterceptLayer.querySelector('.app-intercept-open');
    var closeBtn = appInterceptLayer.querySelector('.app-intercept-close');

    // 앱 설치 여부 확인 및 앱 열기 시도
    function openApp() {
      var userAgent = navigator.userAgent.toLowerCase();
      var isAndroid = /android/i.test(userAgent);
      var isIOS = /iphone|ipad|ipod/i.test(userAgent);

      if (isAndroid) {
        // Android: 커스텀 URL 스킴으로 앱 실행 시도
        var deepLinkUrl = 'nowjob://' + (window.location.pathname + window.location.search);
        window.location.href = deepLinkUrl;

        // 앱 미설치 시 Play Store로 이동 (2초 후)
        setTimeout(function() {
          window.location.href = 'https://play.google.com/store/apps/details?id=app.nowapk.com';
        }, 2000);
      } else if (isIOS) {
        // iOS: 커스텀 URL 스킴으로 앱 실행 시도
        var deepLinkUrl = 'nowjob://' + (window.location.pathname + window.location.search);
        window.location.href = deepLinkUrl;

        // 앱 미설치 시 App Store로 이동 (2초 후)
        setTimeout(function() {
          window.location.href = 'https://apps.apple.com/app/id6446226901';
        }, 2000);
      } else {
        // 모바일이 아니면 무시
        alert('모바일 앱에서만 사용 가능합니다.');
      }
    }

    // 닫기 버튼: 세션스토리지에 숨기기 플래그 저장
    function hideLayer() {
      sessionStorage.setItem('app_intercept_hidden', 'true');
      appInterceptLayer.classList.remove('visible');
    }

    // 이벤트 리스너
    if (openBtn) {
      openBtn.addEventListener('click', openApp);
    }
    if (closeBtn) {
      closeBtn.addEventListener('click', hideLayer);
    }

    // 페이지 로드 시 표시 여부 결정 (웹 환경에서만)
    window.addEventListener('DOMContentLoaded', function() {
      var isHidden = sessionStorage.getItem('app_intercept_hidden') === 'true';
      if (!isHidden) {
        setTimeout(function() {
          appInterceptLayer.classList.add('visible');
        }, 500);
      }
    });
  })();
</script>
