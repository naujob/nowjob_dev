<style>
    @-webkit-keyframes portrait-ani {
        0% {
            opacity: 0;
            -webkit-transform: translateY(100%);
            transform: translateY(100%);
        }

        100% {
            opacity: 1;
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }
    }

    @keyframes portrait-ani {
        0% {
            opacity: 0;
            -webkit-transform: translateY(100%);
            transform: translateY(100%);
        }

        100% {
            opacity: 1;
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }
    }

    @-webkit-keyframes logo-ani {
        0% {
            -webkit-filter: blur(12px);
            filter: blur(12px);
            opacity: 0;
        }

        100% {
            -webkit-filter: blur(0px);
            filter: blur(0px);
            opacity: 1;
        }
    }

    @keyframes logo-ani {
        0% {
            -webkit-filter: blur(12px);
            filter: blur(12px);
            opacity: 0;
        }

        100% {
            -webkit-filter: blur(0px);
            filter: blur(0px);
            opacity: 1;
        }
    }

    .splash {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        /* 기존 배경 이미지 주석처리 */
        /* background: url("/resource/shop/mobile/img/splash_bg.jpg") no-repeat 50% 50%; */
        background: #000;
        overflow: hidden;
        -webkit-perspective: 20px;
        perspective: 20px;
    }

    .splash-bg-img {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
        pointer-events: none;
    }

    .splash> :not(.splash-bg-img) {
        position: relative;
        z-index: 1;
    }

    .logo {
        position: absolute;
        top: 21vh;
        left: 0;
        right: 0;
        width: 65.942vw;
        margin: auto;
        z-index: 1;
        -webkit-animation: logo-ani 0.6s cubic-bezier(0.550, 0.085, 0.680, 0.530) 0.2s both;
        animation: logo-ani 0.6s cubic-bezier(0.550, 0.085, 0.680, 0.530) 0.2s both;
    }

    .logo img {
        display: block;
        width: 100%;
        height: auto;
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
    }

    .visual {
        position: absolute;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        /* 아래에서 위로 올라오는 애니메이션 비활성화 */
        /* -webkit-animation: portrait-ani 1s cubic-bezier(.39, .575, .565, 1.000) both;
        animation: portrait-ani 1s cubic-bezier(.39, .575, .565, 1.000) both */
    }

    .visual .shadow {
        display: block;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: auto;
        margin: auto 0 0;
        -o-object-fit: cover;
        object-fit: cover;
        -webkit-transform: translate(-25%, 0) translateZ(0);
        transform: translate(-25%, 0) translateZ(0);
        -webkit-animation: shadow-ani 2s ease-out 1.5s both;
        animation: shadow-ani 2s ease-out 1.5s both;
    }

    .visual .portrait {
        display: block;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: auto;
        margin: auto 0 0;
        -o-object-fit: cover;
        object-fit: cover;
        -webkit-transform: translateZ(0);
        transform: translateZ(0);
    }

    .layer-popup {
        background-color: rgba(0, 0, 0, 0.55);
    }

    .layer-popup .btn-close-popup {
        display: none;
    }
</style>

<div class="splash">
    <img src="/resource/shop/mobile/img/splash_test2.jpg" alt="" class="splash-bg-img" decoding="async" fetchpriority="high">
    <!-- 기존 로고 이미지 주석처리 -->
    <!-- <h1 class="logo"><img src="/resource/shop/mobile/img/title_logo.png" width="273" height="96" alt=""></h1> -->
    <!-- 기존 portrait 이미지 주석처리 -->
    <!-- <div class="visual">
        <img src="/resource/shop/mobile/img/splash_portrait.png" alt="" class="portrait">
    </div> -->
</div>

<script>
    //alert('splash');
    var push_yn = "N";
    var marketing_yn = "N";
    var uuid = <?= json_encode($uuid) ?>;
    var step = <?= json_encode($step) ?>;
    var hasConsent = false; // 기존 동의 정보가 있는지 확인
    var grantPopupTimeout = null; // 권한 팝업 완료 후 페이지 이동을 위한 타임아웃


    // ✅ 푸시 알림 URL 확인 (URL 파라미터 또는 네이티브에서 전달받은 값)
    var pushURL = null;

    // 1. URL 파라미터에서 확인
    var urlParams = new URLSearchParams(window.location.search);
    var urlParam = urlParams.get('push_url');
    if (urlParam) {
        pushURL = decodeURIComponent(urlParam);
        console.log('📱 URL 파라미터에서 푸시 URL 발견:', pushURL);
    }

    // 2. 전역 변수로 저장된 푸시 URL 확인 (네이티브 앱에서 설정한 경우)
    if (window.pendingPushURL) {
        pushURL = window.pendingPushURL;
        window.pendingPushURL = null; // 사용 후 초기화
        console.log('📱 전역 변수에서 푸시 URL 발견:', pushURL);
    }

    // ✅ 푸시 URL이 있으면 splash를 건너뛰고 해당 URL로 이동
    if (pushURL) {
        console.log('📱 푸시 알림 URL로 이동:', pushURL);

        // URL 정규화
        var normalizedURL = pushURL;
        if (pushURL.startsWith('/')) {
            normalizedURL = window.location.origin + pushURL;
        }

        // splash 화면을 건너뛰고 바로 이동
        window.location.replace(normalizedURL);
        // 이후 로직 실행 중단 (return 없이 스크립트 종료)
    } else {
        // 푸시 URL이 없는 경우에만 기존 로직 실행
        //alert(uuid); //test
        //alert(step); //test

        /* UUID로 기존 동의 정보 확인 */
        // step == "grant"는 현재 splash 로딩 단계가 '권한(동의) 화면'임을 의미함
        if (uuid != "" && step == "grant") {
            checkExistingConsent();
        } else if (step == "grant") {
            // UUID가 없는 경우 팝업 없이 기본값으로 설정하고 바로 진행
            push_yn = "N";
            marketing_yn = "N";
            setTimeout(function() {
                setUUid();
            }, 300);
        } else if (step == "login") {
            setTimeout(function() {
                navigateToHome();
            }, 300);
        } else {
            setTimeout(function() {
                navigateToHome();
            }, 300);
        }
    }


    // 팝업 함수들 비활성화 (더 이상 사용하지 않음)
    // function pop_alarm() {
    //     openPopup('confirm', {
    //         id: 'permission',
    //         content: '<strong>나우잡에서 알림을 보내고자 합니다.</strong><small>경고, 사운드 및 아이콘 배지가 알림에<br> 포함될 수 있습니다<br> 설정에서 이를 변경할 수 있습니다.</small>',
    //         firstButton: '허용안함',
    //         secondButton: '허용',
    //         firstCallback: function() {
    //             push_yn = "N";
    //             pop_grant();
    //         },
    //         secondCallback: function() {
    //             push_yn = "Y";
    //             pop_grant();
    //         },
    //     });
    // }

    // function pop_grant() {
    //     openPopup('modal', {
    //         id: 'permission',
    //         content: '<div class="alert-msg">' +
    //             '<div class="permission2">' +
    //             '<p class="headline">고객님의 편리한<br> 나우잡 사용을 위해 아래<br> 접근권한의 허용이 필요합니다.</p>' +
    //             '<ul>' +
    //             '<li>' +
    //             '<span class="ico"><img src="/resource/shop/mobile/img/ic_permission1.svg" alt=""></span>' +
    //             '<em class="title">위치(선택)</em><span class="desc">지역 선택에서 현위치 선택 및 지도 사용시 필요한 권한입니다.</span>' +
    //             '</li>' +
    //             '<li>' +
    //             '<span class="ico"><img src="/resource/shop/mobile/img/ic_permission2.svg" alt=""></span>' +
    //             '<em class="title">카메라(선택)</em><span class="desc">사진 촬영 업로드 시 필요한 권한입니다.</span>' +
    //             '</li>' +
    //             '<li>' +
    //             '<span class="ico"><img src="/resource/shop/mobile/img/ic_permission3.svg" alt=""></span>' +
    //             '<em class="title">저장공간(선택)</em><span class="desc">사진등록, 파일등록시 필요한 권한입니다.</span>' +
    //             '</li>' +
    //             '</ul>' +
    //             '<p>설정&gt;나우잡 앱&gt;나우잡 접근 허용에서 권한 설정을 변경하실 수 있습니다.</p>' +
    //             '<p class="notice">위 접근권한은 고객님께 더 나은 서비스를<br> 제공하기 위해 사용됩니다. 허용에 동의하지<br> 않으셔도 나우잡을 사용하실 수 있습니다.</p>' +
    //             '</div>' +
    //             '</div>',
    //         firstButton: '확인',
    //         firstCallback: function() {
    //             //openPopup('alert',{content:'허용안함 Callback'});
    //             pop_marketing();

    //         },
    //         secondCallback: function() {
    //             //openPopup('alert',{content:'허용 Callback'});
    //             pop_marketing();
    //         },
    //     });
    // }

    // function pop_marketing() {
    //     openPopup('confirm', {
    //         id: 'permission',
    //         content: '<strong>나우잡 마케팅 동의</strong><small>채용정보와 이벤트 등의 알림을 받으려면<br> 수신동의를 눌러주세요<br> 알림은 알림설정에서 변경하실 수 있습니다. </small>',
    //         firstButton: '허용안함',
    //         secondButton: '허용',
    //         firstCallback: function() {
    //             marketing_yn = "N";
    //             
    //             setUUid();
    //         },
    //         secondCallback: function() {
    //            
    //             marketing_yn = "Y";
    //             setUUid();
    //         },
    //     });
    // }

    /**
     * 단말기 UUID, 푸시/마케팅 수신여부를 서버에 저장한 뒤
     * 단말 OS(android/ios)에 따라 권한 팝업 또는 위치 권한 요청을 진행합니다.
     * 서버 응답 성공 시, android는 앱 객체 기능 호출, ios는 webkit 메시지 전송.
     * 각 권한 팝업에 응답하지 않거나 예외 발생 시 10초 후 로그인 페이지로 자동 이동합니다.
     */
    function setUUid() {
        var params = {};
        params["uuid"] = uuid;
        params["push_yn"] = push_yn;
        params["marketing_yn"] = marketing_yn;

        comAjax("/common_proc/set_uuid", params, function(ret) {
            if (ret.code == "00") {
                var mobileType = checkMobile();

                if (mobileType == "android") {
                    // APP_NOWAPK 객체 존재 여부 확인
                    if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK) {
                        try {
                            // Android: 위치 권한만 요청
                            if (typeof APP_NOWAPK.requestLocationPermission === 'function') {
                                APP_NOWAPK.requestLocationPermission();
                            }

                            // 권한 팝업 표시
                            if (typeof APP_NOWAPK.grantPopup === 'function') {
                                APP_NOWAPK.grantPopup();
                            }

                            // finishGrant 콜백이 호출되지 않는 경우를 대비한 백업 타임아웃 (0.5초 후)
                            grantPopupTimeout = setTimeout(function() {
                                navigateToHome();
                            }, 500);
                        } catch (error) {
                            // 에러 발생 시에도 홈으로 이동
                            navigateToHome();
                        }
                    } else {
                        // APP_NOWAPK가 없으면 바로 홈으로 이동
                        navigateToHome();
                    }
                } else if (mobileType == "ios") {
                    // iOS: 위치 권한만 요청
                    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.requestLocationPermission) {
                        window.webkit.messageHandlers.requestLocationPermission.postMessage({});
                    } else {
                        navigateToHome();
                        return;
                    }

                    grantPopupTimeout = setTimeout(function() {
                        navigateToHome();
                    }, 3000);
                } else {
                    navigateToHome();
                }
            } else {
                commonAlert(ret.msg, function() {});
            }
        });
    }

    // 기존 동의 정보 확인 함수
    function checkExistingConsent() {
        var params = {};
        params["uuid"] = uuid;

        comAjax("/common_proc/get_uuid_consent", params, function(ret) {
            if (ret.code == "00") {
                hasConsent = ret.data.has_consent;

                if (hasConsent) {
                    // 이미 동의 정보가 있는 경우, 저장된 값으로 설정하고 바로 로그인 페이지로 이동
                    push_yn = ret.data.push_yn;
                    marketing_yn = ret.data.marketing_yn;

                    setTimeout(function() {
                        var mobileType = checkMobile();

                        if (mobileType == "android") {
                            if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK) {
                                try {
                                    if (typeof APP_NOWAPK.requestLocationPermission === 'function') {
                                        APP_NOWAPK.requestLocationPermission();
                                    }

                                    if (typeof APP_NOWAPK.grantPopup === 'function') {
                                        APP_NOWAPK.grantPopup();
                                    }

                                    grantPopupTimeout = setTimeout(function() {
                                        location.replace("/recruit/job_list");
                                    }, 3000);
                                } catch (error) {
                                    navigateToHome();
                                }
                            } else {
                                navigateToHome();
                            }
                        } else if (mobileType == "ios") {
                            if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.requestLocationPermission) {
                                window.webkit.messageHandlers.requestLocationPermission.postMessage({});
                            } else {
                                navigateToHome();
                                return;
                            }

                            grantPopupTimeout = setTimeout(function() {
                                navigateToHome();
                            }, 3000);
                        } else {
                            navigateToHome();
                        }
                    }, 300); // 빠르게 이동
                } else {
                    // 동의 정보가 없는 경우 팝업 없이 기본값으로 설정하고 바로 진행
                    push_yn = "N";
                    marketing_yn = "N";
                    setTimeout(function() {
                        setUUid();
                    }, 1000);
                }
            } else {
                // API 호출 실패 시 팝업 없이 기본값으로 설정하고 바로 진행
                push_yn = "N";
                marketing_yn = "N";
                setTimeout(function() {
                    setUUid();
                }, 1000);
            }
        });
    }

    function checkMobile() {
        var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
        if (varUA.indexOf('android') > -1) {
            //안드로이드
            return "android";
        } else if (varUA.indexOf("iphone") > -1 || varUA.indexOf("ipad") > -1 || varUA.indexOf("ipod") > -1) {
            //IOS
            return "ios";
        } else {
            //아이폰, 안드로이드 외
            return "other";
        }
    }

    // getLocation() 호출 후 위치 권한 처리 콜백 함수

    // 로그인 상태에 따라 홈 페이지 결정
    function navigateToHome() {
        var params = {};
        comAjax("/common_proc/get_login_status", params, function(ret) {
            if (ret.code == "00") {
                if (ret.data.is_login) {
                    // 로그인됨 - 공고 목록으로 이동
                    location.replace("/recruit/job_list");
                } else {
                    // 미로그인 - 공고 목록으로 이동
                    location.replace("/recruit/job_list");
                }
            } else {
                // API 호출 실패 시 공고 목록으로 이동
                location.replace("/recruit/job_list");
            }
        });
    }

    function returnLocation(lat, lng) {
        // 위치 팝업이 완료되었고, 권한 팝업도 이미 표시되었다면 바로 페이지 이동
        // (권한 팝업이 먼저 뜨고 위치 팝업이 나중에 뜨는 경우)
        if (grantPopupTimeout) {
            // 타임아웃 취소
            clearTimeout(grantPopupTimeout);
            grantPopupTimeout = null;
            // 페이지 이동
            navigateToHome();
        }
    }

    // iOS에서 위치 권한 결과를 받으면 적절한 홈으로 이동
    function onLocationPermissionResult(status) {
        if (grantPopupTimeout) {
            clearTimeout(grantPopupTimeout);
            grantPopupTimeout = null;
        }
        navigateToHome();
    }

    function finishGrant(args) {
        // 타임아웃 취소 (finishGrant가 호출되면 백업 타임아웃 불필요)
        if (grantPopupTimeout) {
            clearTimeout(grantPopupTimeout);
            grantPopupTimeout = null;
        }

        // 권한 팝업 완료 후 로그인 상태에 따라 홈으로 이동
        navigateToHome();
    }

    // ✅ 웹뷰 로드 완료 후 푸시 URL 확인 (네이티브 앱에서 지연 전달된 경우 대비)
    window.addEventListener('load', function() {
        // 페이지 로드 완료 후 짧은 딜레이를 두고 푸시 URL 재확인
        setTimeout(function() {
            // 전역 변수로 저장된 푸시 URL 확인
            if (window.pendingPushURL) {
                var delayedPushURL = window.pendingPushURL;
                window.pendingPushURL = null;

                console.log('📱 페이지 로드 완료 후 푸시 URL 발견:', delayedPushURL);

                // URL 정규화
                var normalizedURL = delayedPushURL;
                if (delayedPushURL.startsWith('/')) {
                    normalizedURL = window.location.origin + delayedPushURL;
                }

                // 푸시 URL로 이동
                window.location.replace(normalizedURL);
            }
        }, 500); // 0.5초 후 확인
    });

    // ✅ iOS 네이티브 앱에서 푸시 URL을 전달받을 수 있도록 전역 함수 제공
    window.setPendingPushURL = function(url) {
        if (url && url.trim() !== '') {
            console.log('📱 네이티브 앱에서 푸시 URL 전달받음:', url);

            // 현재 splash 페이지인 경우 즉시 이동
            if (window.location.pathname === '/home/splash') {
                var normalizedURL = url;
                if (url.startsWith('/')) {
                    normalizedURL = window.location.origin + url;
                }
                window.location.replace(normalizedURL);
            } else {
                // 다른 페이지인 경우 전역 변수에 저장 (onPushOpen에서 처리)
                window.pendingPushURL = url;
            }
        }
    };
</script>