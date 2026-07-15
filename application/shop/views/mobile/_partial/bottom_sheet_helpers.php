<?php
/**
 * 바텀시트 공통 헬퍼 (initPanelSwipe)
 * openPanel/closePanel은 main.js에 정의되어 있다고 가정
 */
?>
<script>
  // Bottom sheet styles are in main.css

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
