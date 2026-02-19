<?php
/**
 * 바텀시트 공통 헬퍼 (initPanelSwipe)
 * openPanel/closePanel은 main.js에 정의되어 있다고 가정
 */
?>
<script>
  (function() {
    if (document.getElementById('bottom-sheet-style-block')) {
      return;
    }
    var style = document.createElement('style');
    style.id = 'bottom-sheet-style-block';
    style.textContent = '\
  /* NotoSansKR ExtraBold 폰트 정의 */\n\
  @font-face {\n\
    font-family: "NotoSansKR-ExtraBold";\n\
    src: url("/resource/shop/mobile/font/NotoSansKR-ExtraBold.woff") format("woff");\n\
    font-weight: 900;\n\
    font-style: normal;\n\
    font-display: swap;\n\
  }\n\
\n\
  /* ============================================\n\
     공통 바텀시트 스타일 (모든 바텀시트 공통)\n\
     ============================================ */\n\
\n\
  /* Handlebar 스타일 - 상단 드래그용 가로선 */\n\
  .bottom-sheet-handlebar {\n\
    width: 100%;\n\
    height: auto;\n\
    margin: 12px auto 0;\n\
    cursor: pointer;\n\
    position: relative;\n\
    /* iOS WebView 스와이프 제스처 활성화 */\n\
    touch-action: pan-y !important;\n\
    -webkit-user-drag: none !important;\n\
    -webkit-touch-callout: none !important;\n\
    -webkit-user-select: none !important;\n\
    pointer-events: auto !important;\n\
    /* 스와이프 감지 영역 확대 (전체 영역) */\n\
    padding: 8px 0;\n\
    /* 세로 드래그만 허용 */\n\
  }\n\
\n\
  /* Handlebar 시각적 선 (작은 높이) */\n\
  .bottom-sheet-handlebar::before {\n\
    content: "";\n\
    display: block;\n\
    width: 40px;\n\
    height: 3px;\n\
    background-color: #D9D9D9;\n\
    border-radius: 2px;\n\
    margin: 0 auto;\n\
  }\n\
\n\
  /* 패널 컨테이너 기본 스타일 (main.css의 .layer-panel .panel-container 속성 참고) */\n\
  .bottom-sheet-container {\n\
    display: -webkit-box;\n\
    display: -ms-flexbox;\n\
    display: flex;\n\
    position: relative;\n\
    width: 100%;\n\
    height: 100%;\n\
    -webkit-box-orient: vertical;\n\
    -webkit-box-direction: normal;\n\
    -ms-flex-direction: column;\n\
    flex-direction: column;\n\
    background: #F6F6F6;\n\
    -webkit-transform: translateY(100%);\n\
    -ms-transform: translateY(100%);\n\
    transform: translateY(100%);\n\
    overflow: hidden;\n\
    z-index: 1;\n\
    /* transform transition 딱 1개만 선언 (iOS 레이어 재생성 방지) */\n\
    -webkit-transition: -webkit-transform 0.5s cubic-bezier(0.5, 1, 0.89, 1);\n\
    transition: transform 0.5s cubic-bezier(0.5, 1, 0.89, 1);\n\
  }\n\
\n\
  /* 패널이 열렸을 때 (main.css의 .layer-panel.on .panel-container 속성 참고) */\n\
  .layer-panel.on .bottom-sheet-container {\n\
    -webkit-transform: translateY(0);\n\
    -ms-transform: translateY(0);\n\
    transform: translateY(0);\n\
    /* transform transition 딱 1개만 선언 (iOS 레이어 재생성 방지) */\n\
    -webkit-transition: -webkit-transform 0.5s cubic-bezier(0.5, 1, 0.89, 1);\n\
    transition: transform 0.5s cubic-bezier(0.5, 1, 0.89, 1);\n\
  }\n\
\n\
  /* 카드 타입 바텀시트 컨테이너 (main.css의 .layer-panel.card-type .panel-container 속성 참고) */\n\
  .layer-panel.card-type .bottom-sheet-container {\n\
    position: absolute;\n\
    bottom: 0;\n\
    left: 0;\n\
    height: auto;\n\
    min-height: 0;\n\
    max-height: 86.8%;\n\
    border-radius: 2.5625rem 2.5625rem 0 0;\n\
    background: #fff;\n\
  }\n\
\n\
  /* 바텀시트 Body 스타일 (main.css의 .layer-panel.card-type .panel-body 속성 참고) */\n\
  .bottom-sheet-body {\n\
    position: relative;\n\
    -webkit-box-flex: 1;\n\
    -ms-flex: 1;\n\
    flex: 1;\n\
    background: inherit;\n\
    overflow: hidden;\n\
    overflow-y: auto;\n\
    display: -webkit-box;\n\
    display: -ms-flexbox;\n\
    display: flex;\n\
    min-height: 0;\n\
    padding: 0 1.375rem;\n\
    -webkit-box-orient: vertical;\n\
    -webkit-box-direction: normal;\n\
    -ms-flex-direction: column;\n\
    flex-direction: column;\n\
  }\n\
\n\
  /* 바텀시트 Content 스타일 */\n\
  .bottom-sheet-content {\n\
    position: relative;\n\
  }\n\
\n\
  /* 바텀시트 Footer 스타일 */\n\
  .bottom-sheet-footer {\n\
    padding: 1.5rem 1.5rem 2.5rem;\n\
  }\n\
\n\
  /* 바텀시트 Sticky-bottom 스타일 (main.css의 .layer-panel .panel-footer .sticky-bottom 속성 참고) */\n\
  .bottom-sheet-sticky-bottom {\n\
    position: relative;\n\
  }\n\
\n\
  /* 바텀시트 버튼 기본 스타일 (main.css의 .btn 속성 참고) */\n\
  .btn-bottom-sheet {\n\
    display: inline-block;\n\
    text-align: center;\n\
    vertical-align: middle;\n\
    border: 0;\n\
    background: transparent;\n\
  }\n\
\n\
  /* 바텀시트 Primary 버튼 스타일 (main.css의 .btn-primary 속성 참고) */\n\
  .btn-bottom-sheet-primary {\n\
    display: inline-block;\n\
    font-weight: 700;\n\
    color: #fff;\n\
    text-align: center;\n\
    vertical-align: middle;\n\
    border: 0;\n\
    background: #1B9D7D;\n\
\n\
  }\n\
\n\
  .btn-bottom-sheet-primary:active {\n\
    background-color: #38b898;\n\
  }\n\
\n\
  /* 바텀시트 Secondary 버튼 스타일 (main.css의 .btn-secondary 속성 참고) */\n\
  .btn-bottom-sheet-secondary {\n\
    display: inline-block;\n\
    font-weight: 700;\n\
    color: #fff;\n\
    text-align: center;\n\
    vertical-align: middle;\n\
    border: 0;\n\
    background: #BEBFC9;\n\
  }\n\
\n\
  .btn-bottom-sheet-secondary:active {\n\
    background-color: #a2a4b2;\n\
  }\n\
\n\
  /* 바텀시트 헤더 기본 스타일 (main.css의 .layer-panel.card-type .panel-header 속성 참고) */\n\
  .bottom-sheet-header {\n\
    position: relative;\n\
    top: 0;\n\
    left: 0;\n\
    width: 100%;\n\
    padding-top: 2.25rem;\n\
    padding-bottom: 1.25rem;\n\
    padding-left: 1.5rem;\n\
    padding-right: 1.375rem;\n\
    background: inherit;\n\
  }\n\
\n\
  /* 바텀시트 타이틀 기본 스타일 */\n\
  .bottom-sheet-title {\n\
    font-size: 25px;\n\
    color: #1B9D7D;\n\
    text-align: left;\n\
    margin: 0;\n\
    line-height: 1.2;\n\
  }\n\
\n\
  /* iOS WebView 터치 이벤트 전달 보장 */\n\
  .layer-panel,\n\
  .layer-panel * {\n\
    pointer-events: auto !important;\n\
  }\n\
\n\
  /* 바텀시트 패널 컨테이너 터치 이벤트 보장 */\n\
  .layer-panel.card-type,\n\
  .layer-panel.card-type .bottom-sheet-container {\n\
    pointer-events: auto !important;\n\
  }\n\
\n\
  /* iOS 바텀시트 영역 터치 시 배경 스크롤 차단 */\n\
  .layer-panel.card-type {\n\
    position: fixed;\n\
    top: 0;\n\
    left: 0;\n\
    right: 0;\n\
    bottom: 0;\n\
    height: 100%;\n\
    /* iOS가 터치 이벤트를 sheet로만 가게 하는 핵심 코드 */\n\
    touch-action: none !important;\n\
    /* 스크롤은 sheet 내부만 */\n\
    overflow-y: auto;\n\
    -webkit-overflow-scrolling: touch;\n\
  }\n\
\n\
  /* 바텀시트 배경 오버레이 (배경 조작 차단) */\n\
  .layer-panel.card-type::before {\n\
    content: "";\n\
    position: absolute;\n\
    inset: 0;\n\
    background: rgba(0, 0, 0, 0.35);\n\
    opacity: 0;\n\
    pointer-events: none;\n\
    transition: opacity 0.2s ease;\n\
  }\n\
\n\
  .layer-panel.card-type.on::before {\n\
    opacity: 1;\n\
    pointer-events: auto;\n\
  }\n\
\n\
  /* iOS 배경 깜빡임 방지를 위한 하드웨어 가속 최적화 */\n\
  .layer-panel {\n\
    /* 하드웨어 가속 활성화 - 배경 레이어를 GPU에서 렌더링 */\n\
    -webkit-transform: translateZ(0);\n\
    transform: translateZ(0);\n\
    -webkit-backface-visibility: hidden;\n\
    backface-visibility: hidden;\n\
    /* 렌더링 최적화 힌트 */\n\
    will-change: opacity, transform;\n\
    /* iOS Safari 렌더링 최적화 */\n\
    -webkit-perspective: 1000;\n\
    perspective: 1000;\n\
  }\n\
\n\
  /* 패널이 열릴 때 즉시 표시되도록 최적화 */\n\
  .layer-panel.on {\n\
    /* 하드웨어 가속 유지 */\n\
    -webkit-transform: translateZ(0);\n\
    transform: translateZ(0);\n\
    /* iOS에서 배경이 즉시 표시되도록 */\n\
    -webkit-backface-visibility: hidden;\n\
    backface-visibility: hidden;\n\
  }\n\
\n\
  /* iOS body 스크롤 완전 차단 (바텀시트 열릴 때) */\n\
  body.no-scroll {\n\
    position: fixed !important;\n\
    width: 100% !important;\n\
    overflow: hidden !important;\n\
    touch-action: none !important;\n\
    /* iOS 하드웨어 가속으로 깜빡임 방지 */\n\
    -webkit-transform: translateZ(0);\n\
    transform: translateZ(0);\n\
    -webkit-backface-visibility: hidden;\n\
    backface-visibility: hidden;\n\
  }\n\
\n\
  /* ============================================\n\
     선택형 바텀시트 공통 스타일 (이력서/채용공고 공통)\n\
     ============================================ */\n\
\n\
  /* 선택형 바텀시트 헤더 스타일 (main.css의 .layer-panel.card-type .panel-header 속성 참고) */\n\
  .select-bottom-sheet-header {\n\
    position: relative;\n\
    top: 0;\n\
    left: 0;\n\
    width: 100%;\n\
    padding-top: 1.25rem;\n\
    padding-bottom: 1.25rem;\n\
    padding-left: 1.5rem;\n\
    padding-right: 1.375rem;\n\
    background: inherit;\n\
  }\n\
\n\
  /* 선택형 바텀시트 타이틀 스타일 */\n\
  .select-bottom-sheet-title {\n\
    font-family: "NotoSansKR-ExtraBold", sans-serif;\n\
    font-size: 1.5rem;\n\
    font-weight: 600;\n\
    color: #1B9D7D;\n\
    text-align: left;\n\
    margin: 0;\n\
    line-height: 1.2;\n\
    -webkit-text-stroke: 0.5px #1B9D7D;\n\
    -moz-text-stroke: 0.5px #1B9D7D;\n\
    -ms-text-stroke: 0.5px #1B9D7D;\n\
    -o-text-stroke: 0.5px #1B9D7D;\n\
\n\
  }\n\
\n\
  /* 선택형 바텀시트 콘텐츠 스타일 */\n\
  .select-bottom-sheet-content-wrapper {\n\
    padding: -0.75rem 0;\n\
  }\n\
\n\
  .select-bottom-sheet-content-wrapper p {\n\
    font-size: 1.1rem;\n\
    color: #696060;\n\
    line-height: 1.5;\n\
    margin: 0;\n\
  }\n\
\n\
  /* 선택형 바텀시트 버튼 그룹 스타일 */\n\
  .select-bottom-sheet-buttons {\n\
    display: flex;\n\
    gap: 0.75rem;\n\
    justify-content: center;\n\
    align-items: center;\n\
    width: 100%;\n\
  }\n\
\n\
  /* 선택형 바텀시트 선택 버튼 기본 스타일 */\n\
  .btn-select-bottom-sheet {\n\
    height: 70px;\n\
    font-size: 1.3rem;\n\
    font-weight: 700;\n\
    border-radius: 38px;\n\
    cursor: pointer;\n\
    transition: all 0.2s;\n\
    flex: 1;\n\
  }\n\
\n\
  /* 선택형 바텀시트 Secondary 버튼 스타일 (아웃라인) */\n\
  .btn-select-bottom-sheet.btn-bottom-sheet-secondary {\n\
    background-color: #fff;\n\
    color: #1B9D7D;\n\
    border: 1px solid #1B9D7D;\n\
  }\n\
\n\
  .btn-select-bottom-sheet.btn-bottom-sheet-secondary:active,\n\
  .btn-select-bottom-sheet.btn-bottom-sheet-secondary:hover {\n\
    background-color: #f0f9f7;\n\
  }\n\
\n\
  /* 선택형 바텀시트 Primary 버튼 스타일 (솔리드) */\n\
  .btn-select-bottom-sheet.btn-bottom-sheet-primary {\n\
    background-color: #1B9D7D;\n\
    color: #fff;\n\
    border: none;\n\
  }\n\
\n\
  .btn-select-bottom-sheet.btn-bottom-sheet-primary:active,\n\
  .btn-select-bottom-sheet.btn-bottom-sheet-primary:hover {\n\
    background-color: #158a6f;\n\
  }\n\
\n\
  /* ============================================\n\
     로그인 바텀시트 개별 스타일\n\
     ============================================ */\n\
\n\
  /* 로그인 바텀시트 헤더 스타일 (main.css의 .layer-panel.card-type .panel-header 속성 참고) */\n\
  .login-bottom-sheet-header {\n\
    position: relative;\n\
    top: 0;\n\
    left: 0;\n\
    width: 100%;\n\
    padding-top: 1.25rem;\n\
    padding-bottom: 1.25rem;\n\
    padding-left: 1.5rem;\n\
    padding-right: 1.375rem;\n\
    background: inherit;\n\
  }\n\
\n\
  /* 로그인 바텀시트 타이틀 스타일 (font-weight만 다름) */\n\
  .login-bottom-sheet-title {\n\
    font-family: "NotoSansKR-ExtraBold", sans-serif;\n\
    font-size: 1.5rem;\n\
    font-weight: 900;\n\
    color: #1B9D7D;\n\
    text-align: left;\n\
    margin: 0;\n\
    line-height: 1.2;\n\
  }\n\
\n\
  /* 로그인 가이드 콘텐츠 스타일 */\n\
  .login-guide-content {\n\
    padding: 0.25rem 0;\n\
  }\n\
\n\
  .login-guide-content p {\n\
    font-size: 1.1rem;\n\
    color: #696060;\n\
    line-height: 1.5;\n\
\n\
  }\n\
\n\
  .login-guide-content p:first-child {\n\
    margin-top: 0;\n\
  }\n\
\n\
  .login-guide-content p:last-child {\n\
    margin-bottom: 0;\n\
  }\n\
\n\
  /* 바텀시트 텍스트 콘텐츠 스타일 (공통) */\n\
  .bottom-sheet-text-content {\n\
    padding: 0.25rem 0;\n\
  }\n\
\n\
  .bottom-sheet-text-content p {\n\
    font-size: 1.1rem;\n\
    color: #696060;\n\
    line-height: 1.5;\n\
    margin: 0;\n\
  }\n\
\n\
  .bottom-sheet-text-content p:first-child {\n\
    margin-top: 0;\n\
  }\n\
\n\
  .bottom-sheet-text-content p:last-child {\n\
    margin-bottom: 0;\n\
  }\n\
\n\
  /* 로그인 가이드 버튼 스타일 */\n\
  .btn-login-guide {\n\
    width: 100%;\n\
    height: 70px;\n\
    font-size: 1.3rem;\n\
    font-weight: 700;\n\
    background-color: #1B9D7D;\n\
    color: #fff;\n\
    border-radius: 38px;\n\
    border: none;\n\
    margin: 0 auto;\n\
    display: block;\n\
    cursor: pointer;\n\
    transition: background-color 0.2s;\n\
\n\
  }\n\
\n\
  .btn-login-guide:active,\n\
  .btn-login-guide:hover {\n\
    background-color: #4ab89a;\n\
  }\n\
\n\
  /* 로그인 바텀시트 컨테이너 최소 높이 설정 */\n\
  .login-bottom-sheet-container {\n\
    min-height: auto;\n\
  }\n\
';
    document.head.appendChild(style);
  })();

  /**
   * 바텀시트 Handlebar Swipe 기능 초기화 (공통 함수)
   * 카카오톡/네이버지도 방식의 부드러운 바텀시트 모션
   * @param {string} panelId - 바텀시트 패널 ID
   */
  function initPanelSwipe(panelId) {
    var panel = document.getElementById(panelId);
    if (!panel) return;

    // 공통 클래스명으로 컨테이너와 핸들바 찾기
    var container = panel.querySelector('.bottom-sheet-container');
    var handlebar = panel.querySelector('.bottom-sheet-handlebar');
    if (!container || !handlebar) return;

    var startY = 0;
    var currentY = 0;
    var dragging = false;
    var SWIPE_CLOSE_DISTANCE = 120; // 닫히는 기준 (px)

    // touchstart - 터치 시작 (iOS WebView 최적화)
    handlebar.addEventListener('touchstart', function(e) {
      // 패널이 열리는 중이면 스와이프 동작 차단 (깜빡임 방지)
      if (panel.isOpening) return;

      if (e.touches.length === 0) return;

      startY = e.touches[0].clientY;
      dragging = true;
      container.style.transition = 'none'; // 드래그 중에는 transition 제거

      // iOS에서 터치 이벤트가 즉시 전달되도록 보장
      e.stopPropagation();
    }, {
      passive: false // iOS에서 preventDefault 가능하도록
    });

    // touchmove - 터치 이동 (iOS WebView 최적화)
    handlebar.addEventListener('touchmove', function(e) {
      // 패널이 열리는 중이면 스와이프 동작 차단 (깜빡임 방지)
      if (panel.isOpening) {
        dragging = false;
        return;
      }

      if (!dragging || e.touches.length === 0) return;

      currentY = e.touches[0].clientY;
      var deltaY = currentY - startY;

      // 아래로만 이동
      if (deltaY > 0) {
        container.style.transform = 'translateY(' + deltaY + 'px)';
        e.preventDefault(); // iOS에서 스크롤 방지
        e.stopPropagation(); // 이벤트 전파 방지
      }
    }, {
      passive: false // preventDefault 필수
    });

    // touchend - 터치 종료 (iOS WebView 최적화)
    handlebar.addEventListener('touchend', function(e) {
      if (!dragging) return;

      var deltaY = currentY - startY;
      dragging = false;

      // 이벤트 전파 방지
      e.stopPropagation();

      // 닫힘 조건 (120px 이상)
      if (deltaY > SWIPE_CLOSE_DISTANCE) {
        container.style.transition = 'transform 0.25s ease';
        container.style.transform = ''; // 초기화

        // 실제 패널 닫기
        if (typeof closePanel === 'function') {
          closePanel(panel);
        } else {
          panel.classList.remove('on');
          document.body.style.overflow = 'auto';
        }
      } else {
        // 원래 위치로 부드럽게 복귀
        container.style.transition = 'transform 0.25s ease';
        container.style.transform = 'translateY(0)';
      }

      // 상태 초기화
      startY = 0;
      currentY = 0;
    }, {
      passive: false
    });

    // 마우스 이벤트도 지원 (데스크톱 테스트용)
    var mouseMoveHandler = function(e) {
      // 패널이 열리는 중이면 스와이프 동작 차단 (깜빡임 방지)
      if (panel.isOpening) {
        dragging = false;
        return;
      }

      if (!dragging) return;
      currentY = e.clientY;
      var deltaY = currentY - startY;

      if (deltaY > 0) {
        container.style.transform = 'translateY(' + deltaY + 'px)';
        e.preventDefault();
      }
    };

    var mouseUpHandler = function() {
      if (!dragging) return;

      var deltaY = currentY - startY;
      dragging = false;

      if (deltaY > SWIPE_CLOSE_DISTANCE) {
        container.style.transition = 'transform 0.25s ease';
        container.style.transform = '';

        if (typeof closePanel === 'function') {
          closePanel(panel);
        } else {
          panel.classList.remove('on');
          document.body.style.overflow = 'auto';
        }
      } else {
        container.style.transition = 'transform 0.25s ease';
        container.style.transform = 'translateY(0)';
      }

      startY = 0;
      currentY = 0;

      // 이벤트 리스너 제거
      document.removeEventListener('mousemove', mouseMoveHandler);
      document.removeEventListener('mouseup', mouseUpHandler);
    };

    handlebar.addEventListener('mousedown', function(e) {
      // 패널이 열리는 중이면 스와이프 동작 차단 (깜빡임 방지)
      if (panel.isOpening) return;

      startY = e.clientY;
      dragging = true;
      container.style.transition = 'none';
      e.preventDefault();

      document.addEventListener('mousemove', mouseMoveHandler);
      document.addEventListener('mouseup', mouseUpHandler);
    });
  }
</script>
