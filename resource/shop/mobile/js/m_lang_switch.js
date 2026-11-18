/* 언어 전환 스위치(KO / 中文) 공통 동작 — 모든 모바일 화면에서 로드 (_layout/basic.php)
   마크업: _partial/lang_switch.php / 모양: css/m_lang_switch.css
   상태(data-lang, aria-pressed, data-hidden, data-compact)는 HTML 속성으로만 관리한다 */
(function () {
  'use strict';

  var moving = false; // 화면 이동 대기 중 다시 누르는 것을 막는 표시

  // 현재 언어 값 (첫 번째 스위치 기준)
  function currentLang() {
    var box = document.querySelector('.lang-switch, .lang-dropdown');
    return box ? box.getAttribute('data-lang') : '';
  }

  // 버튼 하나짜리 토글(플로팅·상세 상단·지도 상단)은 누른 뒤 버튼이 반대 언어 모양으로 바뀌어야 다음 누름이 먹는다
  // 서버는 처음 로드 때 버튼을 한 번만 그리므로, 여기서 버튼 내용과 값을 반대쪽(data-alt-*, <template>)과 맞바꾼다
  function flipSingleToggle(btn) {
    var box = btn.parentNode;
    if (!box || box.getAttribute('data-count') !== '1') return;
    var tpl = box.querySelector('template.lang-switch-alt');
    var altValue = btn.getAttribute('data-alt-value');
    if (!tpl || !altValue) return;

    var nowHtml = btn.innerHTML;          // 지금 보이는 언어 (눌러서 떠나는 쪽)
    var nowValue = btn.getAttribute('data-lang-value');
    var nowLang = btn.getAttribute('lang');

    btn.innerHTML = tpl.innerHTML;        // 반대 언어 모양으로 교체
    btn.setAttribute('data-lang-value', altValue);
    btn.setAttribute('lang', btn.getAttribute('data-alt-lang') || '');
    btn.setAttribute('data-alt-value', nowValue);
    btn.setAttribute('data-alt-lang', nowLang);
    tpl.innerHTML = nowHtml;              // 떠난 언어 모양은 다음 번 교체를 위해 보관
  }

  // 화면의 모든 스위치(헤더·플로팅·상세 상단·헤더 펼침 목록)를 같은 언어 상태로 맞춘다
  function syncBoxes(lang) {
    var boxes = document.querySelectorAll('.lang-switch, .lang-dropdown');
    for (var i = 0; i < boxes.length; i++) {
      boxes[i].setAttribute('data-lang', lang);
      var btns = boxes[i].querySelectorAll('.lang-switch-btn, .lang-dd-item');
      for (var j = 0; j < btns.length; j++) {
        btns[j].setAttribute('aria-pressed', btns[j].getAttribute('data-lang-value') === lang ? 'true' : 'false');
      }
    }
    // 펼침 목록의 현재 언어 글자: 고른 항목의 내용(국기+글자)을 그대로 복사한다 (서버가 만든 마크업)
    var cur = document.querySelector('.lang-dd-current');
    var src = document.querySelector('.lang-dd-item[data-lang-value="' + lang + '"]');
    if (cur && src) cur.innerHTML = src.innerHTML;
  }

  // 펼침 목록(헤더 3개 이상)을 모두 닫는다
  function closeDropdowns() {
    var boxes = document.querySelectorAll('.lang-dropdown');
    for (var i = 0; i < boxes.length; i++) {
      boxes[i].setAttribute('data-open', 'false');
      var ddBtn = boxes[i].querySelector('.lang-dd-btn');
      if (ddBtn) ddBtn.setAttribute('aria-expanded', 'false');
    }
  }

  // 열려 있는 인재·채용 상세 팝업의 내용만 새 언어로 바꾼다 (닫기·열기 없이 그 자리에서)
  // 팝업 HTML은 서버가 쿠키 언어로 그리므로, 열기 함수에 inPlace=true를 넘겨 다시 받아 끼운다
  function refreshOpenPostPopup() {
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
      window[t.openFn](idx, true);
      return;
    }
  }

  // 펼침 목록: 현재 언어 버튼을 누르면 열고 닫는다, 바깥을 누르면 닫는다
  document.addEventListener('click', function (e) {
    var ddBtn = e.target && e.target.closest ? e.target.closest('.lang-dd-btn') : null;
    if (ddBtn) {
      var box = ddBtn.parentNode;
      var willOpen = box.getAttribute('data-open') !== 'true';
      closeDropdowns();
      box.setAttribute('data-open', willOpen ? 'true' : 'false');
      ddBtn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
      return;
    }
    if (!(e.target && e.target.closest && e.target.closest('.lang-dropdown'))) closeDropdowns();
  }, false);

  // Esc 키로 펼침 목록을 닫는다
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' || e.key === 'Esc') closeDropdowns();
  });

  // 어떤 스위치든 누르면 처리 (이벤트 위임: 상세 팝업 안에 새로 그려진 스위치도 동작)
  document.addEventListener('click', function (e) {
    var btn = e.target && e.target.closest ? e.target.closest('.lang-switch-btn, .lang-dd-item') : null;
    if (!btn || moving) return;

    // 언어 코드는 짧은 영문 소문자만 허용 (사용 가능 여부는 서버가 버튼을 그릴 때 이미 정한다)
    var nextLang = btn.getAttribute('data-lang-value') || '';
    if (!/^[a-z]{2}$/.test(nextLang)) return;
    if (nextLang === currentLang()) {
      closeDropdowns(); // 이미 고른 언어를 다시 눌러도 펼침 목록은 닫는다
      return;
    }

    flipSingleToggle(btn);
    syncBoxes(nextLang);
    closeDropdowns();
    document.cookie = 'site_lang=' + nextLang + '; path=/; max-age=31536000';
    // 외국어를 고르면 기억해 둔다 (한국어로 돌아간 뒤에도 그 외국어 버튼을 다시 보여주기 위함)
    if (nextLang !== 'ko') {
      document.cookie = 'site_lang_pref=' + nextLang + '; path=/; max-age=31536000';
    }

    // 목록 교체 화면(job_list / hr_list / job_map / hr_map)은 새로고침 없이 카드만 새 언어로 바꾼다
    // 연타를 막지 않는다: 각 onLangSwitch 가 이전 요청을 취소하므로 마지막에 누른 언어가 남는다
    if (typeof window.onLangSwitch === 'function') {
      try {
        window.onLangSwitch(nextLang, function () {}); // 완료 콜백은 잠금을 쓰지 않으므로 할 일 없음
      } catch (err) {}
      refreshOpenPostPopup();
      return;
    }

    // 그 밖의 화면: 새로고침 중 다시 눌려 꼬이지 않도록 잠근 뒤, 보던 자리를 기억해 두고 새로고침 (민트 배경이 미끄러지는 모습이 보이도록 잠시 후)
    moving = true;
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
