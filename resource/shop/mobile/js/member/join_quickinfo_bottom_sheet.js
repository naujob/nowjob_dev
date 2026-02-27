// join_quickinfo_bottom_sheet: 페이지 전용 UI 동작
(function() {
  // DOM 준비 상태에서 콜백 실행
  function onReady(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  // 바텀시트를 열기 (QuickInfo는 닫기 스와이프 비활성)
  function openQuickInfoPanel() {
    var panel = document.getElementById('quickInfoPanel');
    if (!panel) return;
    if (typeof openPanel === 'function') {
      openPanel(panel);
    }
  }

  // 이 페이지의 기존 주소 팝업 열기
  function openRegionPopup() {
    window.__quickInfoManualAddrMode = true;
    window.__hideAllRegionsInAddrPopup = true;
    if (typeof window.applyAddrPopupAllRegionFilter === 'function') {
      window.applyAddrPopupAllRegionFilter();
    }
    if (typeof window.$ === 'function') {
      // 제이쿼리가 준비됐을 때 실행할 내용
      window.$('#changeArea').addClass('on');
    } else {
      var el = document.getElementById('changeArea');
      if (el) el.classList.add('on');
    }
  }

  // data-action 기준으로 클릭 동작 위임 처리
  function handleActionClick(e) {
    var target = e.target;
    if (!target) return;
    var actionEl = target.closest('[data-action]');
    if (!actionEl) return;

    var action = actionEl.getAttribute('data-action');
    if (action === 'open-region-popup') {
      openRegionPopup();
    }
  }

  // 외부에서 바텀시트를 열 수 있도록 전역으로 노출
  window.openQuickInfoPanel = openQuickInfoPanel;

  // 페이지 준비 완료 시 초기화 (자동 오픈 제거)
  onReady(function() {
    var panel = document.getElementById('quickInfoPanel');
    if (panel) {
      panel.setAttribute('data-disable-esc-close', 'true');
    }
    document.addEventListener('click', handleActionClick);
  });
})();
