// 로딩 오버레이 표시 중 뒤 배경 스크롤 잠금 상태 보관
// (잠그기 전 값을 담아두었다가 해제할 때 그대로 되돌린다)
var _bgScrollLockState = null;

// 뒤 배경 스크롤 잠그기
// 이 프로젝트의 실제 스크롤 컨테이너는 body가 아니라 .page (overflow-y:auto) 이므로
// body만 잠그면 본문이 그대로 스크롤된다. 양쪽을 함께 잠근다.
function lockBackgroundScroll() {
    if (_bgScrollLockState) {
        return; // 이미 잠근 상태면 원래 값을 덮어쓰지 않도록 중단
    }

    var $page = $('.page');
    _bgScrollLockState = {
        bodyOverflow: document.body.style.overflow,
        pageOverflow: $page.length ? $page[0].style.overflow : "",
        pageScrollTop: $page.length ? $page.scrollTop() : 0
    };

    document.body.style.overflow = 'hidden';
    if ($page.length) {
        $page[0].style.overflow = 'hidden';
    }
}

// 뒤 배경 스크롤 잠금 해제
function unlockBackgroundScroll() {
    if (!_bgScrollLockState) {
        return;
    }

    var $page = $('.page');
    document.body.style.overflow = _bgScrollLockState.bodyOverflow;
    if ($page.length) {
        $page[0].style.overflow = _bgScrollLockState.pageOverflow;
        // 일부 브라우저는 overflow:hidden 동안 스크롤 위치를 0으로 되돌리므로 복구한다
        $page.scrollTop(_bgScrollLockState.pageScrollTop);
    }

    _bgScrollLockState = null;
}

// 동시에 진행 중인 로딩 작업 수
// (업로드 2건이 겹칠 때 먼저 끝난 쪽이 오버레이를 내려버리지 않도록 참조 카운트로 관리)
var _loadingOverlayCount = 0;

// 로딩 오버레이 표시
// @param msg : 표시할 문구 (생략 시 "로딩중..." 유지)
function showLoadingOverlay(msg) {
    var $overlay = $('#uploadLoadingOverlay');
    if ($overlay.length === 0) {
        $('body').append('<div id="uploadLoadingOverlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;"><div style="background: white; padding: 30px; border-radius: 8px; text-align: center;"><div id="uploadLoadingOverlayText" style="font-size: 16px; color: #333; font-weight: 500;">로딩중...</div></div></div>');
    }

    if (msg) {
        $('#uploadLoadingOverlayText').text(msg);
    }

    _loadingOverlayCount = _loadingOverlayCount + 1;
    $('#uploadLoadingOverlay').show();
    lockBackgroundScroll();
}

// 로딩 오버레이 숨기기
// 진행 중인 작업이 모두 끝났을 때(카운트 0)만 실제로 감춘다
function hideLoadingOverlay() {
    _loadingOverlayCount = _loadingOverlayCount - 1;
    if (_loadingOverlayCount > 0) {
        return;
    }

    _loadingOverlayCount = 0;
    $('#uploadLoadingOverlay').hide();
    $('#uploadLoadingOverlayText').text('로딩중...');
    unlockBackgroundScroll();
}

// 로딩 오버레이 강제 초기화
// 뒤로가기(bfcache) 복귀 등으로 카운트가 어긋난 채 남았을 때 사용
function resetLoadingOverlay() {
    _loadingOverlayCount = 0;
    $('#uploadLoadingOverlay').hide();
    $('#uploadLoadingOverlayText').text('로딩중...');
    unlockBackgroundScroll();
}
