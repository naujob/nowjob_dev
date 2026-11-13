/* 하단 메뉴: 내리면 숨기고, 올리거나 맨 아래에 닿으면 다시 보이기 */
(function () {
  var last = typeof WeakMap === 'function' ? new WeakMap() : null;
  var nav = null;

  function show() {
    if (nav) nav.classList.remove('nav-hide');
  }

  document.addEventListener('scroll', function (e) {
    nav = nav || document.querySelector('.bottom-nav');
    if (!nav || !last) return;
    var root = document.scrollingElement || document.documentElement;
    var el = (e.target === document) ? root : e.target;
    if (el === document.body && el.scrollHeight <= el.clientHeight + 4) el = root;
    if (!el || typeof el.scrollTop !== 'number') return;
    var page = (el === root || el === document.body);
    if (!page && el.scrollHeight <= el.clientHeight + 4) return;
    if (!page && el.closest && el.closest('.layer-panel, .job-post-popup, .hr-post-popup')) return;

    var y = el.scrollTop;
    var prev = last.has(el) ? last.get(el) : y;
    last.set(el, y);
    var dy = y - prev;
    var atEnd = y + el.clientHeight >= el.scrollHeight - 4;
    var bubbleOn = !!nav.querySelector('.nav-speech-bubble[data-visible="true"]');

    if (dy > 4 && y > 60 && !atEnd && !bubbleOn) {
      nav.classList.add('nav-hide');
    } else if (dy < -4 || atEnd) {
      show();
    }
  }, true);
})();
