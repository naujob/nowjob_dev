/**
 * AppDeepLink - 외부 브라우저에서 앱으로 강제 리다이렉트
 * 카카오톡, 라인 등 외부 브라우저에서 접속 시 네이티브 앱으로 이동
 */
window.addEventListener('DOMContentLoaded', function() {
   // console.log('[AppDeepLink] 초기화 시작');

    var userAgent = navigator.userAgent.toLowerCase();
    var isAndroid = /android/i.test(userAgent);
    var isInternalApp = /nowjobapp_android|nowjobapp_ios/i.test(userAgent);

  //  console.log('[AppDeepLink] User Agent:', userAgent);
   // console.log('[AppDeepLink] Android 감지:', isAndroid);
//console.log('[AppDeepLink] 앱 내부 접속:', isInternalApp);

    // 이미 앱 내부에서 접속한 경우 무시
    if (isInternalApp) {
       // console.log('[AppDeepLink] 이미 앱 내부에서 실행 중 - 무시');
        return;
    }

    // Android 기기에서 외부 브라우저로 접속한 경우
    if (isAndroid) {
        var currentUrl = window.location.href;
        var appSchemeUrl = currentUrl.replace('https://dev.nowjob.co.kr', 'nowjob://')
                                    .replace('https://nowjob.co.kr', 'nowjob://')
                                    .replace('http://localhost', 'nowjob://');

       // console.log('[AppDeepLink] 현재 URL:', currentUrl);
       // console.log('[AppDeepLink] 딥링크 변환:', appSchemeUrl);
       // console.log('[AppDeepLink] 앱으로 강제 리다이렉트 중...');

        // 앱 스킴으로 이동 시도
        window.location.href = appSchemeUrl;

        // 앱이 설치되지 않은 경우를 대비해 타임아웃 설정
        setTimeout(function() {
           // console.log('[AppDeepLink] 앱 열기 실패 - 웹 페이지 계속 진행');
        }, 2500);
    }
});
