/* 지도 하단 카드: 몇 번째 카드인지 표시, 끝에서 넘기면 반대쪽 끝으로 이어짐 */
(function () {
  function setup() {
    var slider = document.querySelector('.page-map .card-slider'),
        track = document.getElementById('ajaxList');
    if (!slider || !track) return;
    var cnt = document.createElement('span');
    cnt.className = 'map-cnt';
    cnt.setAttribute('aria-hidden', 'true');
    cnt.innerHTML = '<b></b><span></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>';
    slider.appendChild(cnt);
    var busy = false, settle;

    function real() {
      return [].filter.call(track.children, function (el) { return !el.classList.contains('map-clone'); });
    }

    function step() {
      var first = track.children[0];
      return first ? first.getBoundingClientRect().width + (parseFloat(getComputedStyle(track).columnGap) || 0) : 1;
    }

    function rebuild() {
      busy = true;
      [].forEach.call(track.querySelectorAll('.map-clone'), function (el) { el.remove(); });
      var items = real(), n = items.length;
      slider.classList.toggle('is-single', n < 2);
      if (n > 1) {
        var head = items[n - 1].cloneNode(true), tail = items[0].cloneNode(true);
        [head, tail].forEach(function (c) { c.classList.add('map-clone'); c.setAttribute('aria-hidden', 'true'); });
        track.insertBefore(head, items[0]);
        track.appendChild(tail);
        jump(step());
        var svg = cnt.querySelector('svg');
        svg.style.animation = 'none';
        void svg.offsetWidth;
        svg.style.animation = '';
      }
      update();
      setTimeout(function () { busy = false; }, 0);
    }

    function update() {
      var n = real().length;
      if (n < 2) return;
      var i = Math.round(track.scrollLeft / step());
      cnt.querySelector('b').textContent = i < 1 ? n : (i > n ? 1 : i);
      cnt.querySelector('span').textContent = ' / ' + n;
    }

    function jump(x) {
      track.style.scrollSnapType = 'none';
      track.style.scrollBehavior = 'auto';
      track.scrollLeft = x;
      setTimeout(function () { track.style.scrollSnapType = ''; track.style.scrollBehavior = ''; }, 40);
    }

    function wrap() {
      var n = real().length, w = step();
      if (n < 2) return;
      var i = Math.round(track.scrollLeft / w);
      if (i < 1) jump(track.scrollLeft + n * w);
      else if (i > n) jump(track.scrollLeft - n * w);
    }

    track.addEventListener('scroll', function () {
      requestAnimationFrame(update);
      clearTimeout(settle);
      settle = setTimeout(wrap, 140);
    }, { passive: true });
    if (window.MutationObserver) new MutationObserver(function () { if (!busy) rebuild(); }).observe(track, { childList: true });
    rebuild();
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setup);
  else setup();
})();
