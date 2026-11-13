/* 모바일 채용·인재 상세 v2: 이동 탭, 상단 그림자, 떠 있는 버튼 숨김 */
(function () {
    function findScroller(root) {
        var el = root;
        while (el && el !== document.body && el !== document.documentElement) {
            if (/(auto|scroll)/.test(getComputedStyle(el).overflowY) && el.scrollHeight > el.clientHeight + 1) return el;
            el = el.parentElement;
        }
        return document.scrollingElement || document.documentElement;
    }

    function init(root) {
        if (root.getAttribute('data-jd-init')) return;
        root.setAttribute('data-jd-init', '1');

        var top = root.querySelector('.jd-top'),
            bar = root.querySelector('.jd-tabs'),
            fa = root.querySelector('#floating-actions'),
            tabs = bar ? [].slice.call(bar.querySelectorAll('a')) : [],
            secs = tabs.map(function (a) { return root.querySelector(a.getAttribute('href')); }),
            sc = findScroller(root.querySelector('.jd-body') || root),
            lock = 0, stop, goId = 0,
            last = sc.scrollTop;

        function isDoc(el) { return el === document.scrollingElement || el === document.documentElement; }
        function viewTop() { return isDoc(sc) ? 0 : sc.getBoundingClientRect().top; }
        function off() { return (top && sc.contains(top) ? top.offsetHeight : 0) + (bar ? bar.offsetHeight : 0) - 1 + 12; }
        function atEnd() { return sc.scrollTop + sc.clientHeight >= sc.scrollHeight - 4; }
        function mark(i) { tabs.forEach(function (a, k) { a.classList.toggle('on', k === i); }); }

        function spy() {
            if (top) top.classList.toggle('is-scrolled', sc.scrollTop > 4);
            if (!tabs.length || Date.now() < lock) return;
            var cur = 0, vt = viewTop();
            secs.forEach(function (s, i) { if (s && s.getBoundingClientRect().top - vt - off() <= 8) cur = i; });
            if (atEnd()) cur = secs.length - 1;
            mark(cur);
        }

        function floating() {
            if (!fa) return;
            if (atEnd()) {
                clearTimeout(stop);
                fa.classList.remove('fa-gone');
                last = sc.scrollTop;
                return;
            }
            if (Math.abs(sc.scrollTop - last) < 4) return;
            last = sc.scrollTop;
            fa.classList.add('fa-gone');
            clearTimeout(stop);
            stop = setTimeout(function () { fa.classList.remove('fa-gone'); }, 1200);
        }

        function onScroll(e) {
            if (!document.documentElement.contains(root)) {
                document.removeEventListener('scroll', onScroll, true);
                return;
            }
            var t = e.target === document ? (document.scrollingElement || document.documentElement) : e.target;
            if (t !== sc) {
                sc = findScroller(root.querySelector('.jd-body') || root);
                if (t !== sc) return;
            }
            spy();
            floating();
        }

        function go(el) {
            var y0 = sc.scrollTop,
                max = sc.scrollHeight - sc.clientHeight,
                y1 = Math.max(0, Math.min(max, y0 + el.getBoundingClientRect().top - viewTop() - off() + 12)),
                d = y1 - y0,
                dur = Math.min(700, Math.max(300, Math.abs(d) * 0.4)),
                t0 = null, done = false, id = ++goId,
                sb = sc.style.scrollBehavior;
            if (!d) return;
            sc.style.scrollBehavior = 'auto';
            lock = Date.now() + dur + 300;
            function fin() {
                if (done) return;
                done = true;
                sc.scrollTop = y1;
                var fix = sc.scrollTop + el.getBoundingClientRect().top - viewTop() - off() + 12;
                if (Math.abs(fix - sc.scrollTop) > 2) sc.scrollTop = Math.max(0, Math.min(max, fix));
                sc.style.scrollBehavior = sb;
            }
            function step(t) {
                if (id !== goId || done) return;
                if (t0 === null) t0 = t;
                var p = Math.min(1, (t - t0) / dur);
                sc.scrollTop = y0 + d * (1 - Math.pow(1 - p, 3));
                if (p < 1) requestAnimationFrame(step); else fin();
            }
            requestAnimationFrame(step);
            setTimeout(function () { if (id === goId) fin(); }, dur + 150);
        }

        tabs.forEach(function (a, i) {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                if (!secs[i]) return;
                sc = findScroller(root.querySelector('.jd-body') || root);
                lock = Date.now() + 1000;
                mark(i);
                go(secs[i]);
            });
        });

        document.addEventListener('scroll', onScroll, { passive: true, capture: true });
        spy();
    }

    window.mDetailV2Init = function () {
        [].forEach.call(document.querySelectorAll('.jd-page'), init);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.mDetailV2Init);
    } else {
        window.mDetailV2Init();
    }
})();
