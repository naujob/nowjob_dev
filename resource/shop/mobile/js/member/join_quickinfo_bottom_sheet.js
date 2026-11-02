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

  /* ===== 출생연도 휠 바텀시트 =====
     resume_write1.php 날짜 휠과 같은 방식으로 동작한다 (중앙에 가장 가까운 li 가 active)
     확인 시 hidden(#quickinfoBirthYear)에 YYYY 값을 넣고 트리거 문구를 갱신한다 */
  var birthWheelTimer = null;

  // 연도 휠 컬럼 요소
  function getBirthWheelCol() {
    return document.getElementById('birthYearWheel');
  }

  // 컬럼 중앙에 가장 가까운 항목에 .active 부여
  function updateBirthActive(col) {
    if (!col) return;
    var items = Array.prototype.slice.call(col.querySelectorAll('li'));
    if (items.length === 0) return;
    var rect = col.getBoundingClientRect();
    var center = rect.height / 2;
    var best = null;
    var min = Infinity;
    items.forEach(function(li) {
      var r = li.getBoundingClientRect();
      var d = Math.abs((r.top + r.height / 2 - rect.top) - center);
      if (d < min) {
        min = d;
        best = li;
      }
    });
    items.forEach(function(li) {
      li.classList.remove('active');
    });
    if (best) best.classList.add('active');
  }

  // 지정 연도 위치로 스크롤 (li 높이 44px 의 절반 = 22)
  function scrollBirthTo(val) {
    var col = getBirthWheelCol();
    if (!col) return;
    var li = col.querySelector('li[data-val="' + val + '"]');
    if (!li) li = col.querySelector('li');
    if (!li) return;
    col.scrollTop = li.offsetTop - (col.clientHeight / 2 - 22);
    updateBirthActive(col);
  }

  // 현재 중앙에 있는 연도 값
  function getBirthActiveVal() {
    var col = getBirthWheelCol();
    var li = col ? col.querySelector('li.active') : null;
    return li ? li.getAttribute('data-val') : '';
  }

  // 출생연도 시트 열기: 이미 고른 값이 있으면 그 위치, 없으면 약 30세 근처에서 시작
  function openBirthYearSheet() {
    var sheet = document.getElementById('birthYearSheet');
    var hidden = document.getElementById('quickinfoBirthYear');
    if (!sheet) return;
    // 배경 클릭·ESC로는 닫히지 않게 한다 (확인/취소로만 닫는다)
    sheet.setAttribute('data-disable-bg-close', 'true');
    sheet.setAttribute('data-disable-esc-close', 'true');
    var seed = (hidden && hidden.value) ? hidden.value : String(new Date().getFullYear() - 30);
    if (typeof openPanel === 'function') {
      openPanel(sheet);
    }
    // 시트가 열리며 레이아웃을 가진 뒤에 스크롤 위치를 잡아야 clientHeight 가 0이 아니다
    setTimeout(function() {
      scrollBirthTo(seed);
    }, 60);
  }
  window.openBirthYearSheet = openBirthYearSheet;

  function closeBirthYearSheet() {
    var sheet = document.getElementById('birthYearSheet');
    if (sheet && typeof closePanel === 'function') {
      closePanel(sheet);
    }
  }
  window.closeBirthYearSheet = closeBirthYearSheet;

  // 확인: 선택한 연도를 hidden 과 트리거 문구에 반영하고 시트를 닫는다
  function confirmBirthYearSheet() {
    var val = getBirthActiveVal();
    if (!val) {
      closeBirthYearSheet();
      return;
    }
    var hidden = document.getElementById('quickinfoBirthYear');
    var btn = document.getElementById('btnQuickInfoBirthYear');
    if (hidden) {
      hidden.value = val;
    }
    if (btn) {
      btn.classList.add('has-value');
      var txt = btn.querySelector('.date-display');
      if (txt) txt.textContent = val + '년';
    }
    closeBirthYearSheet();
  }
  window.confirmBirthYearSheet = confirmBirthYearSheet;

  // 페이지 준비 완료 시 초기화 (자동 오픈 제거)
  onReady(function() {
    var panel = document.getElementById('quickInfoPanel');
    if (panel) {
      panel.setAttribute('data-disable-esc-close', 'true');
    }
    document.addEventListener('click', handleActionClick);

    // 출생연도 트리거 클릭 시 연도 휠 시트를 연다 (잠금된 경우 버튼이 disabled 라 눌리지 않는다)
    document.addEventListener('click', function(e) {
      var trigger = e.target && e.target.closest ? e.target.closest('#btnQuickInfoBirthYear') : null;
      if (trigger) openBirthYearSheet();
    });

    // 연도 휠 스크롤이 멈추면 중앙 항목을 active 로 갱신
    var birthCol = getBirthWheelCol();
    if (birthCol) {
      birthCol.addEventListener('scroll', function() {
        clearTimeout(birthWheelTimer);
        birthWheelTimer = setTimeout(function() {
          updateBirthActive(birthCol);
        }, 50);
      });

      // li 클릭으로 해당 연도를 직접 선택 (smooth scroll 완료 후 active 갱신)
      birthCol.addEventListener('click', function(e) {
        var li = e.target && e.target.closest ? e.target.closest('li') : null;
        if (!li) return;
        var val = li.getAttribute('data-val');
        if (!val) return;
        scrollBirthTo(val);
        clearTimeout(birthWheelTimer);
        birthWheelTimer = setTimeout(function() {
          updateBirthActive(birthCol);
        }, 300);
      });

      // PC 마우스 휠: 기본 스크롤량이 44px을 초과해 여러 해가 한 번에 건너뛰는 문제 방지
      // wheel 이벤트를 가로채 1항목(44px) 단위로만 이동한다
      birthCol.addEventListener('wheel', function(e) {
        e.preventDefault();
        var ITEM_HEIGHT = 44;
        var direction = e.deltaY > 0 ? 1 : -1;
        // 현재 위치를 44px 단위로 스냅한 뒤 1칸 이동 (스크롤 관성 잔량 제거)
        var snapped = Math.round(birthCol.scrollTop / ITEM_HEIGHT) * ITEM_HEIGHT;
        birthCol.scrollTop = snapped + direction * ITEM_HEIGHT;
        clearTimeout(birthWheelTimer);
        birthWheelTimer = setTimeout(function() {
          updateBirthActive(birthCol);
        }, 50);
      }, { passive: false });
    }
  });
})();
