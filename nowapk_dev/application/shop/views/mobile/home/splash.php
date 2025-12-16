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
        background: url("/resource/shop/mobile/img/splash_bg.jpg") no-repeat 50% 50%;
        background-size: cover;
        overflow: hidden;
        -webkit-perspective: 20px;
        perspective: 20px;
    }

    .logo {
        position: absolute;
        top: 21vh;
        left: 0;
        right: 0;
        width: 65.942vw;
        margin: auto;
        z-index: 1;
        -webkit-animation: logo-ani 1s cubic-bezier(0.550, 0.085, 0.680, 0.530) 1s both;
        animation: logo-ani 1s cubic-bezier(0.550, 0.085, 0.680, 0.530) 1.1s both;
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
        -webkit-animation: portrait-ani 1s cubic-bezier(.39, .575, .565, 1.000) both;
        animation: portrait-ani 1s cubic-bezier(.39, .575, .565, 1.000) both
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
    <h1 class="logo"><img src="/resource/shop/mobile/img/title_logo.png" width="273" height="96" alt=""></h1>
    <div class="visual">
        <img src="/resource/shop/mobile/img/splash_portrait.png" alt="" class="portrait">
    </div>
</div>

<script>
    //alert('splash');
    var push_yn = "N";
    var marketing_yn = "N";
    var uuid = "<?= $uuid ?>";
    var step = "<?= $step ?>";

    var hasConsent = false; // 기존 동의 정보가 있는지 확인

    //alert(uuid); //test
    //alert(step); //test

    /* UUID로 기존 동의 정보 확인 */
    if (uuid != "" && step == "grant") {
        checkExistingConsent();
    } else if (step == "grant") {
        // UUID가 없는 경우 기존 로직대로 팝업 표시
        setTimeout(function() {
            pop_alarm();
        }, 2700);
    } else if (step == "login") {
        setTimeout(function() {
            //alert(checkMobile());
            if (checkMobile() == "android") {
                APP_NOWAPK.goHomeLogin();
                //location.replace("/member/login");
            } else {
                location.replace("/member/login");
            }
        }, 3200);
    } else {
        setTimeout(function() {
            if (checkMobile() == "android") {
                //APP_NOWAPK.goHomeReal();
                location.replace("/home/index");
            } else {
                location.replace("/home/index");
            }
        }, 3200);
    }


    function pop_alarm() {
        openPopup('confirm', {
            id: 'permission',
            content: '<strong>나우잡에서 알림을 보내고자 합니다.</strong><small>경고, 사운드 및 아이콘 배지가 알림에<br> 포함될 수 있습니다<br> 설정에서 이를 변경할 수 있습니다.</small>',
            firstButton: '허용안함',
            secondButton: '허용',
            firstCallback: function() {
                push_yn = "N";
                pop_grant();
            },
            secondCallback: function() {
                push_yn = "Y";
                pop_grant();
            },
        });
    }

    function pop_grant() {
        openPopup('modal', {
            id: 'permission',
            content: '<div class="alert-msg">' +
                '<div class="permission2">' +
                '<p class="headline">고객님의 편리한<br> 나우잡 사용을 위해 아래<br> 접근권한의 허용이 필요합니다.</p>' +
                '<ul>' +
                '<li>' +
                '<span class="ico"><img src="/resource/shop/mobile/img/ic_permission1.svg" alt=""></span>' +
                '<em class="title">위치(선택)</em><span class="desc">지역 선택에서 현위치 선택 및 지도 사용시 필요한 권한입니다.</span>' +
                '</li>' +
                '<li>' +
                '<span class="ico"><img src="/resource/shop/mobile/img/ic_permission2.svg" alt=""></span>' +
                '<em class="title">카메라(선택)</em><span class="desc">사진 촬영 업로드 시 필요한 권한입니다.</span>' +
                '</li>' +
                '<li>' +
                '<span class="ico"><img src="/resource/shop/mobile/img/ic_permission3.svg" alt=""></span>' +
                '<em class="title">저장공간(선택)</em><span class="desc">사진등록, 파일등록시 필요한 권한입니다.</span>' +
                '</li>' +
                '</ul>' +
                '<p>설정&gt;나우잡 앱&gt;나우잡 접근 허용에서 권한 설정을 변경하실 수 있습니다.</p>' +
                '<p class="notice">위 접근권한은 고객님께 더 나은 서비스를<br> 제공하기 위해 사용됩니다. 허용에 동의하지<br> 않으셔도 나우잡을 사용하실 수 있습니다.</p>' +
                '</div>' +
                '</div>',
            firstButton: '확인',
            firstCallback: function() {
                //openPopup('alert',{content:'허용안함 Callback'});
                pop_marketing();

            },
            secondCallback: function() {
                //openPopup('alert',{content:'허용 Callback'});
                pop_marketing();
            },
        });
    }

    function pop_marketing() {
        openPopup('confirm', {
            id: 'permission',
            content: '<strong>나우잡 마케팅 동의</strong><small>채용정보와 이벤트 등의 알림을 받으려면<br> 수신동의를 눌러주세요<br> 알림은 알림설정에서 변경하실 수 있습니다. </small>',
            firstButton: '허용안함',
            secondButton: '허용',
            firstCallback: function() {
                marketing_yn = "N";
                //APP_NOWAPK.grantPopup();
                //location.href="/member/login";
                setUUid();
            },
            secondCallback: function() {
                //location.href="/member/login";
                marketing_yn = "Y";
                setUUid();
            },
        });
    }

    function setUUid() {
        var params = {};
        params["uuid"] = uuid;
        params["push_yn"] = push_yn;
        params["marketing_yn"] = marketing_yn;
        comAjax("/common_proc/set_uuid", params, function(ret) {
            //alert(ret);
            //alert(ret.code);
            if (ret.code == "00") {
                //alert(checkMobile());
                if (checkMobile() == "android") {
                    //APP_NOWAPK.grantPopup(); //25.04.29
                    location.replace("/member/login");
                } else {
                    var send_data = {};
                    //window.webkit.messageHandlers.grant_push.postMessage(send_data);
                    location.replace("/member/login");
                }
                //location.href="/member/join_comp_step2";
            } else {
                commonAlert(ret.msg, function() {});
            }
        });
    }

    // 기존 동의 정보 확인 함수
    function checkExistingConsent() {
        var params = {};
        params["uuid"] = uuid;

        // 디버깅용 로그
        console.log("checkExistingConsent 호출됨, UUID:", uuid);

        comAjax("/common_proc/get_uuid_consent", params, function(ret) {
            console.log("API 응답:", ret);

            if (ret.code == "00") {
                hasConsent = ret.data.has_consent;
                console.log("hasConsent:", hasConsent);

                if (hasConsent) {
                    // 이미 동의 정보가 있는 경우, 저장된 값으로 설정하고 바로 로그인 페이지로 이동
                    push_yn = ret.data.push_yn;
                    marketing_yn = ret.data.marketing_yn;
                    console.log("기존 동의 정보 있음 - push_yn:", push_yn, "marketing_yn:", marketing_yn);

                    setTimeout(function() {
                        if (checkMobile() == "android") {
                            location.replace("/member/login");
                        } else {
                            location.replace("/member/login");
                        }
                    }, 2700);
                } else {
                    // 동의 정보가 없는 경우 팝업 표시
                    console.log("동의 정보 없음 - 팝업 표시");
                    setTimeout(function() {
                        pop_alarm();
                    }, 2700);
                }
            } else {
                // API 호출 실패 시 기존 로직대로 팝업 표시
                console.log("API 호출 실패:", ret.msg);
                setTimeout(function() {
                    pop_alarm();
                }, 2700);
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

    function finishGrant(args) {
        alert(args);

        return "test";
    }
</script>