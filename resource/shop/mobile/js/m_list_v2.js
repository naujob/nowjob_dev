/* 모바일 채용목록·인재목록 새 디자인 동작 (배너·추천 슬라이더·A.I 버튼·검색 조건 줄·상단 그림자) */
(function () {
  var pageType = document.getElementById('resumeStatusBanner') ? 'resume' : 'recruit';
  var NO_REGION = '지역을 선택해 주세요'; // '지역 미선택' 판단에 쓰는 값이라 번역하지 않는다

  // 화면 문구 번역 (i18n-core.js 의 window.t 사용, 없으면 원문 그대로)
  function tr(s) {
    return (typeof window.t === 'function') ? window.t(s) : s;
  }

  function each(list, fn) {
    Array.prototype.forEach.call(list, fn);
  }

  // 끝에 붙인 첫 장 복제본에 닿으면 소리 없이 처음으로 되돌림
  function loopBack(track, count, step) {
    if (Math.round(track.scrollLeft / step()) < count) return;
    track.style.scrollSnapType = 'none';
    track.style.scrollBehavior = 'auto';
    track.scrollLeft = track.scrollLeft - count * step();
    setTimeout(function () {
      track.style.scrollSnapType = '';
      track.style.scrollBehavior = '';
    }, 40);
  }

  // 상단 배너: 10초마다 다음 장, 손가락으로 넘김, 마지막 다음은 첫 장
  function initBanner() {
    var box = document.querySelector('.main-visual .visual-slider');
    var track = box ? box.querySelector('.mvb-track') : null;
    if (!track) return;
    var slides = track.querySelectorAll('.mvb-slide');
    var count = slides.length;
    if (count < 2) return;

    var clone = slides[0].cloneNode(true);
    clone.setAttribute('aria-hidden', 'true');
    clone.setAttribute('tabindex', '-1');
    track.appendChild(clone);

    var dots = box.querySelectorAll('.mvb-dots i');
    var timer = null;
    var hold = false;
    var settle = null;
    var step = function () { return track.clientWidth || 1; };
    var index = function () { return Math.round(track.scrollLeft / step()); };

    track.addEventListener('scroll', function () {
      var cur = index() % count;
      each(dots, function (d, i) { d.classList.toggle('on', i === cur); });
      clearTimeout(settle);
      settle = setTimeout(function () { loopBack(track, count, step); }, 140);
    }, { passive: true });

    function next() {
      if (hold || document.hidden) return;
      track.scrollTo({ left: (index() + 1) * step(), behavior: 'smooth' });
    }

    function play() {
      clearInterval(timer);
      if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
      timer = setInterval(next, 10000);
    }

    track.addEventListener('touchstart', function () { hold = true; }, { passive: true });
    track.addEventListener('touchend', function () { hold = false; play(); }, { passive: true });
    play();
  }

  // 추천기업·추천인재: 한 장씩 넘김, 끝에서 처음으로 이어짐, 점 표시
  function initRecommendSliders() {
    each(document.querySelectorAll('.hot-list'), function (list) {
      var track = list.querySelector('.slider-wrap');
      if (!track) return;
      var slides = track.querySelectorAll('.keen-slider__slide');
      var count = slides.length;
      var dots = list.querySelectorAll('.slider-dots .dot');
      var settle = null;
      var step = function () { return (track.clientWidth || 1) + 12; };
      if (count < 2) return;

      var clone = slides[0].cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      track.appendChild(clone);

      track.addEventListener('scroll', function () {
        var k = Math.round(track.scrollLeft / step());
        var cur = k >= count ? 0 : k;
        each(dots, function (d, i) { d.classList.toggle('active', i === cur); });
        clearTimeout(settle);
        settle = setTimeout(function () { loopBack(track, count, step); }, 140);
      }, { passive: true });

      each(dots, function (d, i) {
        d.addEventListener('click', function (e) {
          e.stopPropagation();
          track.scrollTo({ left: i * step(), behavior: 'smooth' });
        });
      });
    });
  }

  // A.I 매칭 버튼: 새 매칭이 있으면 펼침, AI 칩을 누르면 접고 펼침, 글자를 누르면 기존 이동(광고 동의 확인 포함)
  function initAiButton() {
    var link = document.getElementById('aiMatchLink');
    if (!link) return;
    var menu = link.closest ? link.closest('.profile-menu') : null;
    var hasMatch = !!menu && menu.getAttribute('data-badge') === 'true';
    var back = null;

    if (hasMatch) {
      setTimeout(function () { link.classList.remove('is-collapsed'); }, 350);
    }

    function arm() {
      clearTimeout(back);
      if (!hasMatch && !link.classList.contains('is-collapsed')) {
        back = setTimeout(function () { link.classList.add('is-collapsed'); }, 10000);
      }
    }

    (menu || link).addEventListener('click', function (e) {
      if (!e.target.closest || !e.target.closest('#aiMatchLink .ai-chip')) return;
      e.preventDefault();
      e.stopPropagation();
      link.classList.toggle('is-collapsed');
      arm();
    }, true);
  }

  // 언어 전환 스위치(KO / 中文): 고른 언어를 site_lang 쿠키에 저장하고 화면을 새로 고침
  // 모바일은 주소를 바꾸지 않는다(서버가 쿠키로 언어 결정 — i18n_helper::site_lang)
  function initLangSwitch() {
    var box = document.querySelector('.main-visual-header .lang-switch');
    if (!box) return;
    var moving = false; // 화면 이동 대기 중 다시 누르는 것을 막는 표시

    box.addEventListener('click', function (e) {
      var btn = e.target.closest ? e.target.closest('.lang-switch-btn') : null;
      if (!btn || moving) return;
      var nextLang = btn.getAttribute('data-lang-value');
      if (nextLang !== 'ko' && nextLang !== 'zh') return;
      if (nextLang === box.getAttribute('data-lang')) return;

      moving = true;
      box.setAttribute('data-lang', nextLang);
      each(box.querySelectorAll('.lang-switch-btn'), function (b) {
        b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
      });
      document.cookie = 'site_lang=' + nextLang + '; path=/; max-age=31536000';

      // 새로고침 뒤에도 보던 자리를 지킬 수 있게, 화면이 지금 위치를 기억해 두도록 알린다 (job_list.php / hr_list.php 가 정의)
      if (typeof window.onBeforeLangSwitch === 'function') {
        try {
          window.onBeforeLangSwitch();
        } catch (err) {}
      }

      // 민트 배경이 미끄러지는 모습이 보이도록 잠시 후 새로 고침
      setTimeout(function () {
        if (/^\/zh(\/|$)/.test(location.pathname)) {
          // /zh 링크로 들어온 화면: /zh 주소는 서버가 쿠키를 다시 zh 로 바꾸므로 /zh 를 뗀 주소로 이동
          var basePath = location.pathname.replace(/^\/zh(?=\/|$)/, '') || '/';
          location.replace(basePath + location.search + location.hash);
        } else {
          location.reload();
        }
      }, 250);
    });
  }

  // 스크롤하면 고정 검색 바 아래 그림자
  function initHeadShadow() {
    var root = document.documentElement;
    function update() {
      var y = window.pageYOffset || (document.scrollingElement || root).scrollTop || 0;
      root.classList.toggle('mlv2-scrolled', y > 4);
    }
    window.addEventListener('scroll', update, { passive: true });
    update();
  }

  // 검색 조건 한 줄: 지역 칩 + 조건 칩(×) + 초기화
  function initSelRow() {
    var bar = document.querySelector('.search-option');
    var row = document.getElementById('selRow');
    if (!bar || !row) return;
    var tabList = document.getElementById('tab_list');
    var regionList = document.getElementById('selected-regions-list');
    var lastSig = null;
    var queued = false;

    function readRegions() {
      var regions = Array.isArray(window.selectedRegions) ? window.selectedRegions : [];
      regions = regions.filter(function (r) { return typeof r === 'string' && r.trim() !== '' && r !== NO_REGION; });
      if (!regions.length && regionList && regionList.getAttribute('data-has-regions') === 'true') {
        each(regionList.querySelectorAll('.region-tag'), function (tag) {
          var label = tag.querySelector('span') || tag;
          var text = label.textContent.replace('×', '').trim();
          if (text) regions.push(text);
        });
      }
      return regions;
    }

    function readConds() {
      var conds = [];
      if (!tabList) return conds;
      each(tabList.querySelectorAll('.filter-tag-item.on'), function (tag) {
        var key = tag.getAttribute('data-filter-key') || (typeof window.inferFilterKeyFromTag === 'function' ? window.inferFilterKeyFromTag(tag) : '');
        var label = tag.querySelector('.cate') || tag;
        // 조건 값(한국어, 검색에 쓰는 값)은 그대로 두고 화면에 보이는 글자만 항목별로 번역
        var text = label.textContent.trim().split(/\s*,\s*/).map(tr).join('·');
        if (key && text) conds.push({ key: key, text: text });
      });
      return conds;
    }

    function updateEnd() {
      row.classList.toggle('is-end', row.scrollLeft + row.clientWidth >= row.scrollWidth - 2);
    }

    function button(cls, text) {
      var b = document.createElement('button');
      b.type = 'button';
      b.className = cls;
      if (text) b.appendChild(document.createTextNode(text));
      return b;
    }

    function render() {
      queued = false;
      var regions = readRegions();
      var conds = readConds();
      var on = regions.length > 0 || conds.length > 0;
      bar.classList.toggle('is-sel', on);
      bar.classList.toggle('has-cond', conds.length > 0);

      var sig = JSON.stringify([regions, conds]);
      if (sig === lastSig) return;
      lastSig = sig;
      row.innerHTML = '';
      if (!on) return;

      var area;
      if (regions.length) {
        area = button('sel-area', regions[0]);
        if (regions.length > 1) {
          var more = document.createElement('em');
          more.textContent = ' ' + tr('외') + ' ' + (regions.length - 1);
          area.appendChild(more);
        }
        var x = document.createElement('i');
        x.className = 'sel-x';
        x.setAttribute('aria-label', tr('지역 삭제'));
        area.appendChild(x);
        row.appendChild(area);
      }

      conds.forEach(function (c) {
        var chip = button('sel-cond', c.text);
        chip.setAttribute('data-key', c.key);
        chip.setAttribute('aria-label', c.text + ' ' + tr('조건 삭제'));
        chip.appendChild(document.createElement('i'));
        row.appendChild(chip);
      });

      row.appendChild(button('sel-reset', tr('초기화')));
      row.scrollLeft = 0;
      updateEnd();
    }

    function schedule() {
      if (queued) return;
      queued = true;
      setTimeout(render, 0);
    }

    function removeAllRegions() {
      var regions = Array.isArray(window.selectedRegions) ? window.selectedRegions.slice() : [];
      if (!regions.length || typeof window.removeSelectedRegion !== 'function') return;
      if (regions.length > 1) {
        var area = [];
        try {
          area = JSON.parse(window.searchAreaStr || '[]') || [];
        } catch (e) {
          area = [];
        }
        window.selectedRegions = regions.slice(0, 1);
        window.searchAreaStr = JSON.stringify(area.slice(0, 1));
        if (Array.isArray(window.search_addr)) window.search_addr = window.search_addr.slice(0, 1);
      }
      window.removeSelectedRegion(0);
    }

    function resetAll() {
      var reset = document.getElementById('btnReset');
      if (!reset) return;
      reset.click();
      if (pageType === 'resume') {
        var search = document.getElementById('btnSearch');
        if (search) search.click();
      } else {
        window.backupSearchState = null;
      }
    }

    row.addEventListener('click', function (e) {
      var t = e.target;
      if (t.closest('.sel-x')) {
        removeAllRegions();
        return;
      }
      if (t.closest('.sel-area')) {
        var areaBtn = document.querySelector('.search-option a.js-toggle-panel[aria-controls="advancedSearch"]');
        if (areaBtn) areaBtn.click();
        return;
      }
      var cond = t.closest('.sel-cond');
      if (cond) {
        if (typeof window.removeFilterTag === 'function') window.removeFilterTag(cond.getAttribute('data-key'));
        return;
      }
      if (t.closest('.sel-reset')) resetAll();
    });
    row.addEventListener('scroll', updateEnd, { passive: true });

    if (window.MutationObserver) {
      var observer = new MutationObserver(schedule);
      if (tabList) observer.observe(tabList, { childList: true, subtree: true, attributes: true, attributeFilter: ['class'] });
      if (regionList) observer.observe(regionList, { childList: true, subtree: true, attributes: true, attributeFilter: ['data-has-regions'] });
    }
    render();
    setTimeout(schedule, 400);
  }

  function init() {
    initBanner();
    initRecommendSliders();
    initAiButton();
    initLangSwitch();
    initHeadShadow();
    initSelRow();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
