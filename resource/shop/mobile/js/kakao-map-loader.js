// 카카오맵 SDK를 안전하게 로드하는 전역 헬퍼
// - 이미 로드됨: 콜백 즉시 실행
// - 로드 중: 대기열에 콜백만 적재 (스크립트 재삽입 금지)
// - 미로드: <script> 동적 생성 (autoload=false + kakao.maps.load로 파싱 완료 보장)
(function () {
  var state = {
    status: 'idle', // idle | loading | loaded
    callbacks: []
  };

  window.loadKakaoMapSdk = function (appKey, callback) {
    if (typeof callback !== 'function') {
      callback = function () {};
    }

    if (state.status === 'loaded' && window.kakao && window.kakao.maps) {
      callback();
      return;
    }

    state.callbacks.push(callback);
    if (state.status === 'loading') {
      return;
    }
    state.status = 'loading';

    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = '//dapi.kakao.com/v2/maps/sdk.js?appkey=' + appKey + '&libraries=services&autoload=false';
    script.onload = function () {
      window.kakao.maps.load(function () {
        state.status = 'loaded';
        var pending = state.callbacks;
        state.callbacks = [];
        pending.forEach(function (fn) {
          try {
            fn();
          } catch (e) {
            // 개별 콜백 오류가 다른 콜백 실행을 막지 않도록 방어
          }
        });
      });
    };
    script.onerror = function () {
      state.status = 'idle';
      state.callbacks = [];
    };

    document.head.appendChild(script);
  };
})();
