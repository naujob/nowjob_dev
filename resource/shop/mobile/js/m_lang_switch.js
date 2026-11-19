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

  // 공고 상세(팝업·독립 페이지) 언어 전환을 JSON 값 + 고정 라벨 번역표로 처리할지 여부 (기본 켜짐)
  // 문제가 있으면 콘솔에서 window.JOB_POST_JSON_REFRESH = false 로 끄면 이전 HTML 갱신 방식으로 돌아간다.
  if (typeof window.JOB_POST_JSON_REFRESH === 'undefined') {
    window.JOB_POST_JSON_REFRESH = true;
  }
  // 인재 상세 팝업도 같은 방식 (문제 시 콘솔에서 window.HR_POST_JSON_REFRESH = false)
  if (typeof window.HR_POST_JSON_REFRESH === 'undefined') {
    window.HR_POST_JSON_REFRESH = true;
  }

  // 고정 라벨 바꾸기 (2b): data-field 밖의 텍스트 노드와 aria-label·title·alt·placeholder 속성에서
  // 글자가 map 의 키와 정확히 같으면 map 의 값으로 바꾼다. 앞뒤 공백은 그대로 둔다.
  function applyJobPostLabels(root, map) {
    if (!root || !map) return;
    var has = function (k) { return Object.prototype.hasOwnProperty.call(map, k); };

    var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null, false);
    var nodes = [];
    while (walker.nextNode()) nodes.push(walker.currentNode);
    nodes.forEach(function (n) {
      var p = n.parentNode;
      if (!p || !p.closest || p.closest('[data-field], script, style')) return; // DB 값 영역은 건드리지 않는다
      var m = n.nodeValue.match(/^(\s*)([\s\S]*?)(\s*)$/);
      if (m && m[2] !== '' && has(m[2])) {
        n.nodeValue = m[1] + map[m[2]] + m[3];
      }
    });

    var attrs = ['aria-label', 'title', 'alt', 'placeholder'];
    var els = root.querySelectorAll('[aria-label],[title],[alt],[placeholder]');
    for (var i = 0; i < els.length; i++) {
      for (var a = 0; a < attrs.length; a++) {
        var v = els[i].getAttribute(attrs[a]);
        if (v !== null && v !== '' && has(v)) els[i].setAttribute(attrs[a], map[v]);
      }
    }
  }

  // 공고 표시값(JSON, format=data)을 data-field 노드에 채운다 (화면 HTML·CSS·스크립트는 그대로)
  function applyJobPostFields(root, d) {
    Object.keys(d).forEach(function (key) {
      var val = d[key];
      if (key === 'labels') {
        applyJobPostLabels(root, val); // 고정 라벨 번역표 (2b)
        return;
      }
      if (typeof val === 'boolean') {
        if (key === 'dday_closed') {
          var ddayEl = root.querySelector('[data-field="dday_txt"]');
          if (ddayEl) ddayEl.classList.toggle('is-closed', val);
        }
        return; // has_ben 은 섹션 구조에 쓰이므로 언어로 바뀌지 않는다
      }
      var el = root.querySelector('[data-field="' + key + '"]');
      if (!el) return; // 화면에 없는 값(1단계 키 등)은 건너뛴다
      if (key === 'dday_txt' || key === 'auto_notice_text') {
        el.style.display = (val === '') ? 'none' : ''; // 문구가 없으면 숨긴다
      }
      el.innerHTML = val; // 서버가 이스케이프한 값만 들어온다
    });
    // 길찾기 버튼이 쓰는 회사명 속성도 같이 맞춘다
    if (typeof d.comp_txt === 'string') {
      var routes = root.querySelectorAll('.jd-route');
      for (var r = 0; r < routes.length; r++) {
        routes[r].setAttribute('data-name', d.comp_txt);
      }
    }
  }

  // 열린 공고 팝업을 JSON으로 갱신한다. 실패하면 fallback(기존 HTML 갱신)을 부른다
  // root: 값을 채울 영역 (생략하면 공고 팝업). 독립 공고 페이지는 .jd-page 를 넘긴다
  // onDone: JSON 적용이 끝났을 때 부른다
  // 상세 화면 공통: url(format=data 를 붙이지 않은 주소)의 표시값 JSON을 root 안에 채운다
  window.refreshDetailPopupText = function (url, fallback, root, onDone) {
    var popup = root;
    if (!popup || !window.jQuery) {
      fallback();
      return;
    }
    $.ajax({
      url: url,
      data: { format: 'data' },
      cache: false,
      dataType: 'json'
    }).done(function (ret) {
      if (!ret || ret.code !== '00' || !ret.data) {
        fallback();
        return;
      }
      applyJobPostFields(popup, ret.data);
      if (typeof onDone === 'function') onDone();
    }).fail(function () {
      fallback();
    });
  };

  // 공고 상세 (기존 이름 유지)
  window.refreshJobPostPopupText = function (rcIdx, fallback, root, onDone) {
    window.refreshDetailPopupText('/recruit/job_post/' + rcIdx, fallback, root || document.getElementById('jobPostPopup'), onDone);
  };

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
      // 인재 팝업: JSON 표시값만 받아 채운다 (HR_POST_JSON_REFRESH). 실패하면 기존 HTML 갱신
      if (t.popupId === 'hrPostPopup' && window.HR_POST_JSON_REFRESH && typeof window.refreshDetailPopupText === 'function') {
        window.refreshDetailPopupText('/resume/hr_post/' + idx, function () { window[t.openFn](idx, true); }, popup);
        return;
      }
      // 공고 팝업: JSON 표시값만 받아 채운다 (플래그가 켜진 경우). 실패하면 기존 HTML 갱신
      if (t.popupId === 'jobPostPopup' && window.JOB_POST_JSON_REFRESH && typeof window.refreshJobPostPopupText === 'function') {
        window.refreshJobPostPopupText(idx, function () { window[t.openFn](idx, true); });
        return;
      }
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

  // 팝업 언어 재요청 응답(HTML)의 스타일시트 link를 처리한다
  // 같은 CSS를 팝업 안에서 매번 다시 받지 않도록, 아직 문서 head에 없는 것만 head로 옮기고 HTML에서는 뺀다
  // (팝업 안에 있던 link는 innerHTML 교체 때 사라지므로 head에 두어야 스타일이 유지된다)
  // 새로 붙인 CSS가 모두 로드된 뒤에 onReady(html)을 부른다 (CSS·JS 없이 HTML만 보이는 깜빡임 방지)
  // 이미 붙어 있는 CSS만 있으면 바로 부른다
  window.hoistPopupStylesheets = function (html, onReady) {
    var tpl = document.createElement('template');
    tpl.innerHTML = html;
    var links = tpl.content.querySelectorAll('link[rel="stylesheet"]');
    var pending = [];
    for (var i = 0; i < links.length; i++) {
      var href = links[i].getAttribute('href') || '';
      var loaded = href && document.head.querySelector('link[rel="stylesheet"][href="' + href + '"]');
      if (href && !loaded) {
        document.head.appendChild(links[i]); // 처음 보는 CSS: head로 옮겨 한 번만 붙인다
        pending.push(links[i]);
      } else {
        links[i].parentNode.removeChild(links[i]); // 이미 붙어 있는 CSS: 다시 받지 않도록 뺀다
      }
    }
    var out = tpl.innerHTML;
    if (!pending.length) {
      onReady(out);
      return;
    }
    var finished = false;
    var left = pending.length;
    var finish = function () {
      if (finished) return;
      left--;
      if (left <= 0) {
        finished = true;
        onReady(out);
      }
    };
    pending.forEach(function (link) {
      link.addEventListener('load', finish);
      link.addEventListener('error', finish);
    });
    setTimeout(function () { // 응답이 오지 않아도 화면이 멈추지 않도록 안전 시간
      if (!finished) { finished = true; onReady(out); }
    }, 3000);
  };

  // ---------- 플로팅 버튼 숨김·축소 ----------
  // 열린 패널·팝업, 키보드가 올라온 동안은 숨기고, 스크롤 중에는 작게 줄인다

  var floatBox = document.getElementById('langSwitchFloat');
  var compactTimer = null;
  var frameQueued = false;

  // 가리면 안 되는 레이어가 열려 있는지
  function layerOpen() {
    return !!document.querySelector('.layer-panel.on, .layer-popup.in, #hrPostPopup[data-state="open"], #jobPostPopup[data-state="open"]');
  }

  // 글 입력 중이고 키보드가 실제로 올라와 있는지 (플로팅이 입력창을 가리지 않도록)
  // 입력창 포커스만으로는 판단하지 않는다: 뒤로가기로 키보드만 내리면 포커스는 남아 있기 때문
  function keyboardOpen() {
    var a = document.activeElement;
    // 글 입력창만 본다 (type=file 등 선택 도구는 포커스가 남아도 키보드가 올라오지 않으므로 제외)
    var typing = !!(a && a.matches && a.matches('input[type="text"], input[type="search"], input[type="tel"], input[type="email"], input[type="url"], input[type="number"], input[type="password"], input:not([type]), textarea, [contenteditable="true"]'));
    if (!typing) return false;
    var vv = window.visualViewport;
    // visualViewport를 지원하지 않는 환경에서는 포커스 기준으로만 판단
    if (!vv) return true;
    // 키보드가 올라오면 보이는 영역이 줄어든다
    return vv.height < window.innerHeight * 0.75;
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
