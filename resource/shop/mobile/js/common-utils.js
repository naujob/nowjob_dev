// 로딩 오버레이 표시
function showLoadingOverlay() {
    var $overlay = $('#uploadLoadingOverlay');
    if ($overlay.length === 0) {
        $('body').append('<div id="uploadLoadingOverlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 9999;"><div style="background: white; padding: 30px; border-radius: 8px; text-align: center;"><div style="font-size: 16px; color: #333; font-weight: 500;">로딩중...</div></div></div>');
    }
    $('#uploadLoadingOverlay').show();
}

// 로딩 오버레이 숨기기
function hideLoadingOverlay() {
    $('#uploadLoadingOverlay').hide();
}
