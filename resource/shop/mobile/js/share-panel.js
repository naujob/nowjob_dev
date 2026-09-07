/* URL 복사 */
function CopyUrl() {
    var dummy = document.createElement('input'),
        url = typeof customShareUrl !== 'undefined' && customShareUrl ? customShareUrl : location.href;

    document.body.appendChild(dummy);
    dummy.value = url;
    dummy.select();
    document.execCommand('copy');
    document.body.removeChild(dummy);
    openPopup('alert', {
        content: 'URL을 복사했습니다.'
    });
}

/* 공유 패널 초기화 함수 */
function initializeSharePanel() {
    var shareButton = document.querySelector('.btn-share');
    if (!shareButton) {
        return;
    }

    var osType = typeof shareOsType !== 'undefined' ? shareOsType : "";
    var userAgent = navigator.userAgent.toLowerCase();

    if (!osType || osType === "") {
        if (/iphone|ipad|ipod/i.test(userAgent)) {
            osType = "ios";
        } else if (/android/i.test(userAgent)) {
            osType = "android";
        }
    }

    var hasAndroidBridge = window.APP_NOWAPK && typeof window.APP_NOWAPK.share === 'function';
    var hasIosBridge = window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.share;

    var nativeShareEnabled = hasAndroidBridge || hasIosBridge;

    shareButton.setAttribute('data-native-share', nativeShareEnabled ? 'true' : 'false');

    if (nativeShareEnabled) {
        shareButton.classList.remove('js-toggle-panel');
        shareButton.removeAttribute('aria-controls');
        shareButton.setAttribute('href', '#');
    }

    shareButton.addEventListener('click', function(event) {

        if (shareButton.getAttribute('data-native-share') !== 'true') {

            return;
        }

        event.preventDefault();
        event.stopImmediatePropagation();

        var titleElement = document.querySelector('.view-title');
        var title = titleElement ? titleElement.textContent.trim() : document.title;
        var url = typeof customShareUrl !== 'undefined' && customShareUrl ? customShareUrl : window.location.href;

        var payload = {
            title: title,
            url: url
        };

        if (hasAndroidBridge) {

            window.APP_NOWAPK.share(JSON.stringify(payload));
            return;
        }

        if (hasIosBridge) {

            try {
                window.webkit.messageHandlers.share.postMessage(payload);
            } catch (error) {

                window.webkit.messageHandlers.share.postMessage(JSON.stringify(payload));
            }
            return;
        }

    });
}

/* DOMContentLoaded 시점에 초기화 */
window.addEventListener('DOMContentLoaded', function() {
    initializeSharePanel();
});
