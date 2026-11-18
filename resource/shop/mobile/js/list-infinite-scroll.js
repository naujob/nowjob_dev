/* 목록 이어 불러오기 공통 로직 (채용목록 job_list.php, 인재목록 hr_list.php 공통 사용)
 *
 * 하는 일
 *  1) 끝 감지      : 목록 아래 표지판(moreSelector)이 화면에 들어오면 onNeedMore() 호출
 *  2) 근처만 남기기 : 20건 묶음 단위로, 화면에 보이는 묶음 + 앞뒤 1묶음만 DOM에 두고
 *                    나머지는 같은 높이의 빈 칸으로 바꿔 둔다(카드 HTML은 변수에 보관, 서버 재조회 없음)
 *  3) 중복 제거    : 이미 받은 카드 번호는 다시 그리지 않는다
 *  4) 자리 기억·복원: 언어 전환 새로고침 전후로 보던 카드의 위치를 맞춘다
 *
 * 묶음 번호 = 서버 페이지 번호 (묶음 n 은 page=n 응답). 카드가 0장인 묶음도 번호는 유지한다.
 * 화면 전용 이름에 묶이지 않도록 선택자·키 속성은 모두 인자로 받는다.
 */
(function (global) {
  'use strict';

  var SPACER_CLASS = 'mlv2-chunk-spacer'; // 떼어 낸 묶음 자리를 채우는 빈 칸

  function noop() {}

  // 현재 세로 스크롤 위치 (스크롤이 window 에서 일어나든 body 에서 일어나든 읽힌다)
  function getScrollTop() {
    return global.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
  }

  // 세로 스크롤 위치 지정
  function setScrollTop(y) {
    global.scrollTo(0, y);
    if (getScrollTop() !== y) {
      document.body.scrollTop = y;
    }
  }

  // 세로 스크롤을 delta(px)만큼 이동
  function scrollByDelta(delta) {
    if (!delta) return;
    var before = getScrollTop();
    global.scrollBy(0, delta);
    if (getScrollTop() === before) {
      document.body.scrollTop = document.body.scrollTop + delta;
    }
  }

  /**
   * 목록 하나를 관리하는 객체를 만든다
   * @param {Object} opts
   *   listSelector - 카드 목록(ol/ul) 선택자
   *   moreSelector - 목록 아래 표지판 선택자 (data-state: idle | loading | end | error)
   *   keyAttr      - 카드 고유 번호가 담긴 속성 이름 (예: 'data-rc_idx')
   *   onNeedMore   - 표지판이 화면에 들어왔을 때 호출 (다음 묶음 요청)
   *   onNeedChunk  - 아직 받지 않은 묶음이 화면 근처에 왔을 때 호출 (인자: 묶음 번호)
   */
  function create(opts) {
    var list = document.querySelector(opts.listSelector);
    var more = document.querySelector(opts.moreSelector);
    var keyAttr = opts.keyAttr;
    var onNeedMore = opts.onNeedMore || noop;
    var onNeedChunk = opts.onNeedChunk || noop;

    // chunks[n] = { els: 화면에 있는 li 배열, html: 보관한 HTML(null = 아직 안 받음), height: 차지하는 높이,
    //               attached: 화면에 있는지, spacer: 빈 칸 li, requested: 서버 요청 중인지 }
    var chunks = [null]; // 0번은 쓰지 않는다 (묶음 번호 = 페이지 번호)
    var seen = {};       // 이미 받은 카드 번호 기록 (중복 제거용)
    var syncQueued = false;
    var restore = null;  // 언어 전환 복원 진행 정보 { chunk, key, offset, done }
    var observer = null;

    if (!list || !more) {
      return null;
    }

    // ---------- 변화 감지 ----------
    // 스크롤 이벤트를 계속 받지 않고, 지켜보는 요소가 화면 경계를 넘을 때만 알림을 받는다
    if (typeof global.IntersectionObserver === 'function') {
      // 알림이 오면 sync()에서 "어느 묶음이 보이는지 / 표지판이 보이는지"를 한 번에 판단한다
      observer = new global.IntersectionObserver(function () {
        queueSync();
      });
      observer.observe(more);
    } else {
      // 구형 WebView 대비: 스크롤 이벤트를 쓰되 화면을 한 번 그릴 때 최대 1회만 검사
      var onScroll = function () {
        queueSync();
      };
      global.addEventListener('scroll', onScroll, { passive: true });
      document.addEventListener('scroll', onScroll, { passive: true, capture: true });
    }

    function observe(el) {
      if (observer && el) observer.observe(el);
    }

    function unobserve(el) {
      if (observer && el) observer.unobserve(el);
    }

    // 묶음의 첫 카드·마지막 카드만 지켜본다 (묶음이 화면에 들어오고 나가는 순간은 이 둘이 경계를 넘을 때)
    function observeChunk(chunk) {
      if (!chunk.els.length) return;
      observe(chunk.els[0]);
      if (chunk.els.length > 1) observe(chunk.els[chunk.els.length - 1]);
    }

    function unobserveChunk(chunk) {
      if (!chunk.els.length) return;
      unobserve(chunk.els[0]);
      if (chunk.els.length > 1) unobserve(chunk.els[chunk.els.length - 1]);
    }

    // ---------- 묶음 만들기 ----------

    // HTML 글자를 li 배열로 바꾼다. 이미 받은 카드는 빼고, 받은 카드 번호를 기록한다
    // @return {{els: Array, count: number}} count = 실제 카드 수(광고 자리 등 번호 없는 li 제외)
    function parseCards(html, n) {
      var tmp = document.createElement('ol');
      tmp.innerHTML = html || '';
      var els = [];
      var count = 0;
      var child = tmp.firstElementChild;
      while (child) {
        var next = child.nextElementSibling;
        var keyEl = child.hasAttribute(keyAttr) ? child : child.querySelector('[' + keyAttr + ']');
        var key = keyEl ? keyEl.getAttribute(keyAttr) : '';
        if (key && seen[key]) {
          child = next; // 이미 받은 카드: 버림
          continue;
        }
        if (key) {
          seen[key] = true;
          count++;
        }
        child.setAttribute('data-chunk', n);
        els.push(child);
        child = next;
      }
      // 번호 있는 카드가 하나도 없으면(전부 중복) 광고 자리만 남기지 않는다
      if (count === 0) els = [];
      return { els: els, count: count };
    }

    // 보관한 HTML을 li 배열로 되돌린다 (중복 검사 없이 그대로)
    function restoreEls(html, n) {
      var tmp = document.createElement('ol');
      tmp.innerHTML = html || '';
      var els = [];
      var child = tmp.firstElementChild;
      while (child) {
        var next = child.nextElementSibling;
        child.setAttribute('data-chunk', n);
        els.push(child);
        child = next;
      }
      return els;
    }

    function makeSpacer(n, height) {
      var spacer = document.createElement('li');
      spacer.className = SPACER_CLASS;
      spacer.setAttribute('data-chunk', n);
      spacer.setAttribute('aria-hidden', 'true');
      spacer.style.height = Math.max(0, Math.round(height)) + 'px';
      return spacer;
    }

    // 묶음이 차지하는 높이: 첫 요소의 위쪽 ~ 다음 요소의 위쪽 (여백 포함). 마지막 묶음은 목록 아래쪽까지
    function measureSpan(firstEl, lastEl) {
      var start = firstEl.getBoundingClientRect().top;
      var nextEl = lastEl.nextElementSibling;
      var end = nextEl ? nextEl.getBoundingClientRect().top : list.getBoundingClientRect().bottom;
      return end - start;
    }

    // ---------- 떼어 내기 / 되돌리기 ----------

    // 묶음의 카드를 화면에서 지우고 같은 높이의 빈 칸으로 바꾼다
    function detach(n) {
      var chunk = chunks[n];
      if (!chunk || !chunk.attached || !chunk.els.length) return;
      var first = chunk.els[0];
      var last = chunk.els[chunk.els.length - 1];
      if (first.parentNode !== list) return; // 다른 코드가 목록을 비운 경우

      var height = measureSpan(first, last);
      var html = '';
      for (var i = 0; i < chunk.els.length; i++) {
        html += chunk.els[i].outerHTML; // 지금 상태 그대로 보관 (하이라이트·배지 포함)
      }
      unobserveChunk(chunk);

      var spacer = makeSpacer(n, height);
      list.insertBefore(spacer, first);
      for (var j = 0; j < chunk.els.length; j++) {
        list.removeChild(chunk.els[j]);
      }
      // 여백 규칙 때문에 빈 칸이 차지하는 높이가 원래와 다르면 그 차이만큼 맞춘다
      var actual = measureSpan(spacer, spacer);
      if (Math.abs(actual - height) >= 1) {
        spacer.style.height = Math.max(0, Math.round(height - (actual - height))) + 'px';
      }

      chunk.html = html;
      chunk.height = height;
      chunk.els = [];
      chunk.attached = false;
      chunk.spacer = spacer;
      observe(spacer);
    }

    // 빈 칸을 보관해 둔 카드로 되돌린다. 높이가 달라졌고 그 묶음이 화면 위쪽에서 시작하면 스크롤을 보정한다
    function attach(n) {
      var chunk = chunks[n];
      if (!chunk || chunk.attached || chunk.html === null) return;
      var spacer = chunk.spacer;
      if (!spacer || spacer.parentNode !== list) return;

      var spacerTop = spacer.getBoundingClientRect().top;
      var oldSpan = measureSpan(spacer, spacer);
      var els = restoreEls(chunk.html, n);
      unobserve(spacer);

      if (!els.length) {
        // 카드가 없는 묶음(받아 보니 전부 중복이거나 비어 있음): 빈 칸만 치운다
        list.removeChild(spacer);
        chunk.spacer = null;
        chunk.attached = true;
        chunk.els = [];
        if (spacerTop < 0) scrollByDelta(-oldSpan);
        return;
      }

      var frag = document.createDocumentFragment();
      for (var i = 0; i < els.length; i++) {
        frag.appendChild(els[i]);
      }
      list.insertBefore(frag, spacer);
      list.removeChild(spacer);

      chunk.els = els;
      chunk.attached = true;
      chunk.spacer = null;
      observeChunk(chunk);

      var newSpan = measureSpan(els[0], els[els.length - 1]);
      if (spacerTop < 0 && Math.abs(newSpan - oldSpan) >= 1) {
        scrollByDelta(newSpan - oldSpan);
      }
    }

    // ---------- 화면 근처만 남기기 ----------

    function queueSync() {
      if (syncQueued) return;
      syncQueued = true;
      var raf = global.requestAnimationFrame || function (fn) { return setTimeout(fn, 16); };
      raf(function () {
        syncQueued = false;
        sync();
      });
    }

    // 묶음의 화면상 위·아래 위치. 화면에 없는(목록에서 빠진) 묶음은 null
    function chunkRect(chunk) {
      if (chunk.attached) {
        if (!chunk.els.length || chunk.els[0].parentNode !== list) return null;
        return {
          top: chunk.els[0].getBoundingClientRect().top,
          bottom: chunk.els[chunk.els.length - 1].getBoundingClientRect().bottom
        };
      }
      if (!chunk.spacer || chunk.spacer.parentNode !== list) return null;
      var r = chunk.spacer.getBoundingClientRect();
      return { top: r.top, bottom: r.bottom };
    }

    // 화면에 보이는 묶음 + 앞뒤 1묶음만 카드로 두고, 나머지는 빈 칸으로 바꾼다
    function sync() {
      var vh = global.innerHeight || document.documentElement.clientHeight;
      var first = 0;
      var last = 0;
      var n;

      // 1) 읽기: 어느 묶음이 화면에 걸쳐 있는지
      for (n = 1; n < chunks.length; n++) {
        var rect = chunkRect(chunks[n]);
        if (rect && rect.bottom > 0 && rect.top < vh) {
          if (!first) first = n;
          last = n;
        }
      }

      // 목록이 화면에 전혀 없으면(맨 위 영역을 보는 중 등) 그대로 둔다
      if (first) {
        var keepFrom = Math.max(1, first - 1);
        var keepTo = Math.min(chunks.length - 1, last + 1);

        // 2) 쓰기: 멀어진 묶음은 떼어 내고, 가까워진 묶음은 되돌린다
        for (n = 1; n < chunks.length; n++) {
          var chunk = chunks[n];
          var near = (n >= keepFrom && n <= keepTo);
          if (!near && chunk.attached) {
            detach(n);
          } else if (near && !chunk.attached) {
            if (chunk.html !== null) {
              attach(n);
            } else if (!chunk.requested && !restore) {
              // 아직 받지 않은 묶음(언어 전환 직후): 서버에 요청
              // 자리 복원이 끝나기 전에는 요청하지 않는다 (화면이 아직 맨 위에 있어 보던 자리와 무관한 묶음을 받게 됨)
              chunk.requested = true;
              onNeedChunk(n);
            }
          }
        }
      }

      // 표지판이 화면 안에 있으면 다음 묶음 요청 (요청 여부 판단은 화면 쪽 onNeedMore가 한다)
      if (isMoreVisible()) {
        onNeedMore();
      }
    }

    function isMoreVisible() {
      var vh = global.innerHeight || document.documentElement.clientHeight;
      var r = more.getBoundingClientRect();
      if (r.width === 0 && r.height === 0) return false; // 숨겨진 상태
      return r.top < vh && r.bottom >= 0;
    }

    // ---------- 바깥에서 쓰는 함수 ----------

    function clearAll() {
      for (var n = 1; n < chunks.length; n++) {
        unobserveChunk(chunks[n]);
        unobserve(chunks[n].spacer);
      }
      chunks = [null];
      seen = {};
      restore = null;
    }

    // 묶음 n 을 새로 만들어 목록 맨 뒤에 붙인다. 붙인 카드 수를 돌려준다
    function pushChunk(html) {
      var n = chunks.length;
      var parsed = parseCards(html, n);
      var chunk = { els: parsed.els, html: '', height: 0, attached: true, spacer: null, requested: false };
      chunks.push(chunk);
      if (parsed.els.length) {
        var frag = document.createDocumentFragment();
        for (var i = 0; i < parsed.els.length; i++) {
          frag.appendChild(parsed.els[i]);
        }
        list.appendChild(frag);
        observeChunk(chunk);
      }
      queueSync();
      return parsed.count;
    }

    // 첫 진입: 서버가 이미 그려 둔 카드를 다시 그리지 않고 묶음 1로 등록만 한다
    function startFromPage() {
      clearAll();
      var els = [];
      var child = list.firstElementChild;
      while (child) {
        var keyEl = child.hasAttribute(keyAttr) ? child : child.querySelector('[' + keyAttr + ']');
        var key = keyEl ? keyEl.getAttribute(keyAttr) : '';
        if (key) seen[key] = true;
        child.setAttribute('data-chunk', 1);
        els.push(child);
        child = child.nextElementSibling;
      }
      if (!els.length) return 0;
      var chunk = { els: els, html: '', height: 0, attached: true, spacer: null, requested: false };
      chunks.push(chunk);
      observeChunk(chunk);
      return els.length;
    }

    // 검색·지역 변경: 화면의 카드, 보관한 묶음, 받은 번호 기록을 모두 비우고 받은 HTML을 묶음 1로 그린다
    function replaceAll(html) {
      clearAll();
      list.innerHTML = '';
      return pushChunk(html);
    }

    // 스크롤 끝: 받은 HTML을 새 묶음으로 뒤에 붙인다 (기존 목록은 그대로)
    function append(html) {
      return pushChunk(html);
    }

    // 아직 받지 않았던 묶음 n 의 HTML이 도착했을 때 채운다 (언어 전환 직후에만 쓰인다)
    function fillChunk(n, html) {
      var chunk = chunks[n];
      if (!chunk || chunk.html !== null) return 0;
      var parsed = parseCards(html, n);
      var stored = '';
      for (var i = 0; i < parsed.els.length; i++) {
        stored += parsed.els[i].outerHTML;
      }
      chunk.html = stored;
      chunk.requested = false;

      if (restore && restore.chunk === n) {
        attach(n);         // 복원 대상 묶음은 바로 그리고 자리를 맞춘다
        finishRestore();
      } else {
        queueSync();
      }
      return parsed.count;
    }

    // 묶음 n 요청이 실패했을 때: 다음에 가까워지면 다시 요청할 수 있게 표시를 푼다
    function failChunk(n) {
      var chunk = chunks[n];
      if (chunk) chunk.requested = false;
      if (restore && restore.chunk === n) {
        // 복원 실패: 자리만 잡아 둔 빈 칸들을 치워 묶음 1만 있는 처음 상태로 되돌린다
        // (남겨 두면 다음 묶음 번호와 서버 페이지 번호가 어긋난다)
        while (chunks.length > 1 && chunks[chunks.length - 1].html === null) {
          var dropped = chunks.pop();
          unobserve(dropped.spacer);
          if (dropped.spacer && dropped.spacer.parentNode === list) {
            list.removeChild(dropped.spacer);
          }
        }
        var done = restore.done;
        restore = null;
        done(false);
      }
    }

    function setState(state) {
      more.setAttribute('data-state', state);
    }

    // 표지판이 아직 화면 안에 있는지 다시 확인 (카드가 적어 화면이 안 찼거나, 받은 묶음이 전부 중복이었을 때)
    function check() {
      queueSync();
    }

    function scrollToTop() {
      setScrollTop(0);
    }

    // ---------- 언어 전환 시 자리 기억·복원 ----------

    // 지금 화면 맨 위에 걸쳐 있는 카드의 위치를 돌려준다 (새로고침 전에 저장할 값)
    // @return {{chunk:number, key:string, offset:number, scrollTop:number, heights:Array}}
    function getAnchor() {
      var vh = global.innerHeight || document.documentElement.clientHeight;
      var anchor = { chunk: 1, key: '', offset: 0, scrollTop: getScrollTop(), heights: [] };
      var found = false;

      for (var n = 1; n < chunks.length; n++) {
        var chunk = chunks[n];
        var height = chunk.height;
        if (chunk.attached && chunk.els.length && chunk.els[0].parentNode === list) {
          height = measureSpan(chunk.els[0], chunk.els[chunk.els.length - 1]);
          for (var i = 0; !found && i < chunk.els.length; i++) {
            var rect = chunk.els[i].getBoundingClientRect();
            if (rect.bottom > 0 && rect.top < vh) {
              var keyEl = chunk.els[i].hasAttribute(keyAttr) ? chunk.els[i] : chunk.els[i].querySelector('[' + keyAttr + ']');
              anchor.chunk = n;
              anchor.key = keyEl ? keyEl.getAttribute(keyAttr) : '';
              anchor.offset = Math.round(rect.top);
              found = true;
            }
          }
        }
        anchor.heights[n] = Math.round(height || 0);
      }
      return anchor;
    }

    // 묶음 안에서 번호가 key 인 카드 li 를 찾는다 (없으면 그 묶음의 첫 li)
    function findAnchorEl(n, key) {
      var chunk = chunks[n];
      if (!chunk || !chunk.els.length) return null;
      if (key) {
        for (var i = 0; i < chunk.els.length; i++) {
          var keyEl = chunk.els[i].hasAttribute(keyAttr) ? chunk.els[i] : chunk.els[i].querySelector('[' + keyAttr + ']');
          if (keyEl && keyEl.getAttribute(keyAttr) === String(key)) return chunk.els[i];
        }
      }
      return chunk.els[0];
    }

    function finishRestore() {
      if (!restore) return;
      var info = restore;
      restore = null;
      var el = findAnchorEl(info.chunk, info.key);
      if (el) {
        scrollByDelta(el.getBoundingClientRect().top - info.offset); // 그 카드를 전과 같은 높이에 맞춘다
      }
      queueSync();
      info.done(!!el);
    }

    // 새로고침 뒤: 저장해 둔 자리로 맞춘다. startFromPage() 다음에 부른다
    // @param {Object} ticket getAnchor()가 돌려준 값
    // @param {Function} done 끝났을 때 호출 (인자: 성공 여부)
    function restoreAt(ticket, done) {
      done = done || noop;
      var target = parseInt(ticket && ticket.chunk, 10) || 1;
      var offset = parseInt(ticket && ticket.offset, 10) || 0;
      var key = ticket && ticket.key ? String(ticket.key) : '';

      if (chunks.length < 2) { // 첫 묶음이 없으면(0건) 맞출 곳이 없다
        done(false);
        return;
      }

      if (target <= 1) {
        if (key) {
          restore = { chunk: 1, key: key, offset: offset, done: done };
          finishRestore();
        } else {
          setScrollTop(parseInt(ticket && ticket.scrollTop, 10) || 0); // 카드가 없던 맨 위 구간
          done(true);
        }
        return;
      }

      // 묶음 2 ~ target 은 "아직 받지 않은 빈 칸"으로 자리만 잡아 둔다 (높이는 전에 재 둔 값, 없으면 묶음 1 높이)
      var first = chunks[1];
      var fallback = first.els.length ? measureSpan(first.els[0], first.els[first.els.length - 1]) : 0;
      var heights = (ticket && ticket.heights) || [];
      for (var n = chunks.length; n <= target; n++) {
        var height = parseInt(heights[n], 10) || fallback;
        var spacer = makeSpacer(n, height);
        list.appendChild(spacer);
        chunks.push({ els: [], html: null, height: height, attached: false, spacer: spacer, requested: false });
        observe(spacer);
      }

      // 보던 묶음 하나만 먼저 받는다. 도착하면 fillChunk()가 그리고 자리를 맞춘다
      restore = { chunk: target, key: key, offset: offset, done: done };
      chunks[target].requested = true;
      onNeedChunk(target);
    }

    return {
      startFromPage: startFromPage,
      replaceAll: replaceAll,
      append: append,
      fillChunk: fillChunk,
      failChunk: failChunk,
      setState: setState,
      check: check,
      scrollToTop: scrollToTop,
      getAnchor: getAnchor,
      restoreAt: restoreAt
    };
  }

  global.ListInfiniteScroll = { create: create };
})(window);
