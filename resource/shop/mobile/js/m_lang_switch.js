/* 언어 전환 스위치(KO / 中文) 공통 동작 — 모든 모바일 화면에서 로드 (_layout/basic.php)
   마크업: _partial/lang_switch.php / 모양: css/m_lang_switch.css
   상태(data-lang, aria-pressed, data-hidden, data-compact)는 HTML 속성으로만 관리한다 */
(function () {
  'use strict';

  var moving = false; // 화면 이동 대기 중 다시 누르는 것을 막는 표시

  // 현재 언어 값 (첫 번째 스위치 기준)
  function currentLang() {
    var box = document.querySelector('.lang-switch');
    return box ? box.getAttribute('data-lang') : '';
  }

  // 화면의 모든 스위치(헤더·플로팅·상세 상단)를 같은 언어 상태로 맞춘다
  function syncBoxes(lang) {
    var boxes = document.querySelectorAll('.lang-switch');
    for (var i = 0; i < boxes.length; i++) {
      boxes[i].setAttribute('data-lang', lang);
      var btns = boxes[i].querySelectorAll('.lang-switch-btn');
      for (var j = 0; j < btns.length; j++) {
        btns[j].setAttribute('aria-pressed', btns[j].getAttribute('data-lang-value') === lang ? 'true' : 'false');
      }
    }
  }

  // 열려 있는 인재·채용 상세 팝업을 새 언어로 다시 연다
  // 팝업 HTML은 서버가 쿠키 언어로 그리므로, 닫았다가 같은 열기 함수를 다시 불러 받아 온다
  // 열기 함수는 popup.data-state가 open이면 무시하므로 먼저 closed로 되돌린다
  function reopenOpenPostPopup() {
    var targets = [
      { popupId: 'hrPostPopup', inputName: 'ru_idx', openFn: 'openHrPostPopup' },
      { popupId: 'jobPostPopup', inputName: 'rc_idx', openFn: 'openJobPostPopup' }
    ];
    for (var i = 0; i < targets.length; i++) {
      var t = targets[i];
      var popup = document.getElementById(t.popupId);
      if (!popup || popup.getAttribute('data-state') !== 'open') continue;
      var input = popup.querySelector('input[name="' + t.inputName + '"]');
      var idx = input ? input.value : '';
      if (!idx || typeof window[t.openFn] !== 'function') return;
      popup.setAttribute('data-state', 'closed');
      popup.innerHTML = '';
      window[t.openFn](idx);
      return;
    }
  }

  // 어떤 스위치든 누르면 처리 (이벤트 위임: 상세 팝업 안에 새로 그려진 스위치도 동작)
  document.addEventListener('click', function (e) {
    var btn = e.target && e.target.closest ? e.target.closest('.lang-switch-btn') : null;
    if (!btn || moving) return;

    var nextLang = btn.getAttribute('data-lang-value');
    if (nextLang !== 'ko' && nextLang !== 'zh') return;
    if (nextLang === currentLang()) return;

    moving = true;
    syncBoxes(nextLang);
    document.cookie = 'site_lang=' + nextLang + '; path=/; max-age=31536000';

    // 목록 교체 화면(job_list / hr_list)은 새로고침 없이 카드만 새 언어로 바꾼다
    if (typeof window.onLangSwitch === 'function') {
      try {
        window.onLangSwitch(nextLang, function () { moving = false; }); // 완료 콜백: 버튼 다시 활성화
      } catch (err) {
        moving = false; // 오류가 나도 버튼은 다시 누를 수 있게
      }
      reopenOpenPostPopup();
      return;
    }

    // 그 밖의 화면: 보던 자리를 기억해 둔 뒤 새로고침 (민트 배경이 미끄러지는 모습이 보이도록 잠시 후)
    if (typeof window.onBeforeLangSwitch === 'function') {
      try {
        window.onBeforeLangSwitch();
      } catch (err) {}
    }
    setTimeout(function () {
      if (/^\/zh(\/|$)/.test(location.pathname)) {
        // /zh 링크로 들어온 화면: /zh 주소는 서버가 쿠키를 다시 zh 로 바꾸므로 /zh 를 뗀 주소로 이동
        var basePath = location.pathname.replace(/^\/zh(?=\/|$)/, '') || '/';
        location.replace(basePath + location.search + location.hash);
      } else {
        location.reload();
      }
    }, 250);
  }, false);

  // ---------- 플로팅 버튼 숨김·축소 ----------
  // 열린 패널·팝업, 키보드가 올라온 동안은 숨기고, 스크롤 중에는 작게 줄인다

  var floatBox = document.getElementById('langSwitchFloat');
  var compactTimer = null;
  var frameQueued = false;

  // 가리면 안 되는 레이어가 열려 있는지
  function layerOpen() {
    return !!document.querySelector('.layer-panel.on, .layer-popup.in, #hrPostPopup[data-state="open"], #jobPostPopup[data-state="open"]');
  }

  // 글 입력 중인지 (키보드가 올라오면 플로팅이 입력창을 가리지 않도록)
  function keyboardOpen() {
    var a = document.activeElement;
    if (a && a.matches && a.matches('input:not([type="checkbox"]):not([type="radio"]):not([type="button"]):not([type="submit"]), textarea, select, [contenteditable="true"]')) {
      return true;
    }
    var vv = window.visualViewport;
    return !!(vv && vv.height < window.innerHeight * 0.75);
  }

  // 상태 반영 (data-hidden만 바꾼다; CSS가 표시를 정함)
  function updateFloat() {
    frameQueued = false;
    if (!floatBox) return;
    floatBox.setAttribute('data-hidden', (layerOpen() || keyboardOpen()) ? 'true' : 'false');
  }

  // 여러 번 바뀌어도 한 프레임에 한 번만 계산
  function queueUpdate() {
    if (frameQueued) return;
    frameQueued = true;
    window.requestAnimationFrame ? window.requestAnimationFrame(updateFloat) : setTimeout(updateFloat, 16);
  }

  if (floatBox) {
    // 스크롤 중에는 작게, 멈추면 원래 크기 (capture로 안쪽 스크롤 영역도 잡음)
    document.addEventListener('scroll', function () {
      floatBox.setAttribute('data-compact', 'true');
      clearTimeout(compactTimer);
      compactTimer = setTimeout(function () {
        floatBox.setAttribute('data-compact', 'false');
      }, 900);
    }, { capture: true, passive: true });

    document.addEventListener('focusin', queueUpdate);
    document.addEventListener('focusout', function () { setTimeout(queueUpdate, 150); });
    if (window.visualViewport) window.visualViewport.addEventListener('resize', queueUpdate);

    // 패널·팝업이 열리거나 닫히는 것(class, data-state 변화)을 감시
    if (window.MutationObserver) {
      new MutationObserver(queueUpdate).observe(document.body, {
        subtree: true,
        attributes: true,
        attributeFilter: ['class', 'data-state']
      });
    }
    updateFloat();
  }
})();
