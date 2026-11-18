<?php
// 맨 위로 가기 플로팅 버튼 (모바일 목록 페이지 공통: 채용정보 / 인재정보)
// 하단 메뉴(.bottom-nav, 높이 80px) 위 오른쪽에 떠 있고, 일정 높이 이상 내려갔을 때만 보인다
?>
<style>
  /* 맨 위로 버튼: 하단 메뉴(80px) 바로 위 오른쪽 */
  .scroll-top-btn {
    position: fixed;
    right: 16px;
    bottom: 96px;
    z-index: 99;
    width: 44px;
    height: 44px;
    border: none;
    border-radius: 50%;
    background: #fff;
    color: #222;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s, visibility 0.2s;
  }

  .scroll-top-btn[data-visible="true"] {
    opacity: 1;
    visibility: visible;
  }

  .scroll-top-btn .fa {
    font-size: 20px;
    line-height: 1;
  }
</style>

<button type="button" id="btnScrollTop" class="scroll-top-btn" data-visible="false" aria-label="<?= t('맨 위로') ?>">
  <i class="fa fa-arrow-up" aria-hidden="true"></i>
</button>

<script>
  // 맨 위로 버튼: 스크롤 위치에 따라 표시 여부만 data-visible로 바꾼다
  (function() {
    var SHOW_AFTER_Y = 300; // 이 높이(px)를 넘게 내려가면 버튼 표시
    var btn = document.getElementById('btnScrollTop');
    if (!btn) return;

    // 현재 세로 스크롤 위치 (window에서 스크롤되든 body에서 스크롤되든 읽힌다)
    function getScrollY() {
      return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
    }

    function updateVisible() {
      btn.setAttribute('data-visible', getScrollY() > SHOW_AFTER_Y ? 'true' : 'false');
    }

    // 버튼 클릭: 맨 위로 이동 (window / html / body 모두 0으로 맞춘다)
    btn.addEventListener('click', function() {
      window.scrollTo(0, 0);
      document.documentElement.scrollTop = 0;
      document.body.scrollTop = 0;
      updateVisible();
    });

    // 스크롤 이벤트는 body 안 요소에서 일어나도 잡도록 캡처 단계에서 듣는다
    document.addEventListener('scroll', updateVisible, { passive: true, capture: true });
    updateVisible();
  })();
</script>
