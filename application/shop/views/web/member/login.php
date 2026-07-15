<style>
    .page.page-login {
        margin-top: 110px;
    }
</style>
<div id="content" class="page page-login">
    <main class="page-content">

        <div class="container">
            <div class="login-form">
                <h1 class="title-logo"><img src="/resource/shop/web/img/login_logo.svg" alt="NOW JOB"></h1>
                <div class="form-container">
                    <?php
                    $app_base_url = rtrim($this->config->item('app_base_url') ?: base_url(), '/');
                    // 카카오 로그인 URL 생성
                    $kakao_redirect = urlencode($app_base_url . "/member/kakao_login_callback");
                    $kakao_setting = getSettingInfo(["st_group" => "카카오"]);
                    $kakao_client_id = isset($kakao_setting["REST_API_KEY"]) ? $kakao_setting["REST_API_KEY"] : "";
                    // Kakao state 세션에 저장 (콜백에서 검증 시 사용)
                    $this->common->setSession("kakao-oauth-state", $csrf);
                    // prompt=login 파라미터 추가: 항상 로그인 화면을 표시하도록 함
                    $kakao_login = "https://kauth.kakao.com/oauth/authorize?response_type=code&client_id=" . $kakao_client_id . "&redirect_uri=" . $kakao_redirect . "&state=" . $csrf . "&prompt=login";
                    
                    // 네이버 로그인 URL 생성
                    $naver_redirect = urlencode($app_base_url . "/member/naver_login_callback");
                    // 컨트롤러에서 전달받은 네이버 클라이언트 ID 사용
                    $naver_client_id = isset($naver_client_id) ? $naver_client_id : "";
                    if (!empty($naver_client_id)) {
                        // prompt=login과 auth_type=reauthenticate 파라미터 추가: 항상 로그인 화면을 표시하도록 함
                        // 매번 고유한 state 값 사용 (타임스탬프 추가)하여 세션 우회 시도
                        $unique_state = $csrf . "_" . time() . "_" . mt_rand(1000, 9999);
                        // 네이버 state 세션에 저장 (콜백에서 검증 시 사용)
                        $this->common->setSession("naver-oauth-state", $unique_state);
                        $naver_login = "https://nid.naver.com/oauth2.0/authorize?client_id=" . $naver_client_id . "&redirect_uri=" . $naver_redirect . "&response_type=code&state=" . urlencode($unique_state) . "&prompt=login&auth_type=reauthenticate";
                    } else {
                        // 클라이언트 ID가 없으면 빈 문자열
                        $naver_login = "";
                    }

                    // 구글 로그인 URL 생성 (서버 주도 OAuth 시작)
                    $google_setting = getSettingInfo(["st_group" => "구글"]);
                    $google_client_id = isset($google_setting["WEB_CLIENT_ID"]) ? $google_setting["WEB_CLIENT_ID"] : "";
                    $google_login = !empty($google_client_id) ? ($app_base_url . "/member/google_login") : "";

                    // 위챗 로그인 설정 (서버 주도 OAuth 시작)
                    $wechat_setting = getSettingInfo(["st_group" => "위챗"]);
                    $wechat_app_id = isset($wechat_setting["WEB_APP_ID"]) ? $wechat_setting["WEB_APP_ID"] : "";
                    $wechat_login = !empty($wechat_app_id) ? ($app_base_url . "/member/wechat_login") : "";
                    ?>
                    <form action="/">

                        <div class="form-row">
                            <label>
                                <span class="ico-label phone">휴대폰번호</span>
                                <input type="text" id="phone-login-input" placeholder="휴대폰번호를 입력해 주세요" aria-required="true" autocomplete="off" required class="form-text">
                            </label>
                        </div>

                        <button type="button" id="btnPhoneLogin" class="btn-primary" data-state="idle">로그인</button>

                        <ul class="account-menu" style="margin-top: 30px;">
                            <li><a href="/member/join_start">회원가입</a></li>
                        </ul>

                        <div class="vendor-login">
                            <h2 class="title">간편로그인</h2>
                            <div class="social-login-buttons">
                                <!-- 1행: 카카오 + 위챗 -->
                                <a href="<?= $kakao_login ?>" id="btnKakao"><img src="/resource/shop/web/img/ic-kakao-login.png" alt="카카오 로그인"></a>
                                <?php if (!empty($wechat_login)) : ?>
                                    <a href="<?= $wechat_login ?>" id="btnWechat"><img src="/resource/shop/web/img/ic-wechat-login.png" alt="WeChat 로그인"></a>
                                <?php else : ?>
                                    <a href="javascript:void(0);" id="btnWechat"><img src="/resource/shop/web/img/ic-wechat-login.png" alt="WeChat 로그인"></a>
                                <?php endif; ?>
                                <!-- 2행: 네이버 + 구글 -->
                                <?php if (!empty($naver_login)) : ?>
                                    <a href="<?= $naver_login ?>" id="btnNaver"><img src="/resource/shop/web/img/ic-naver-login.png" alt="네이버 로그인"></a>
                                <?php else : ?>
                                    <a href="javascript:void(0);" id="btnNaver"><img src="/resource/shop/web/img/ic-naver-login.png" alt="네이버 로그인"></a>
                                <?php endif; ?>
                                <a href="javascript:void(0);" id="btnGoogle"><img src="/resource/shop/web/img/ic-google-login.png" alt="Google 로그인"></a>
                            </div>
                            <!--a href="#" class="btn vendor-type2">Apple 로그인</a-->
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main><!-- //page-content -->
</div><!-- //page -->

<!-- SNS 회원가입 전화번호 인증 팝업 (구글, 위챗, 애플 등 공용) -->
<div id="sns-phone-popup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0); z-index:2000; align-items:center; justify-content:center;">
    <div id="sns-phone-sheet" style="background:#fff; border-radius:12px; padding:30px; width:400px; max-width:90%; box-shadow:0 4px 20px rgba(0,0,0,0.3);">
        <h3 style="margin-bottom:16px; font-size:18px;">전화번호 인증</h3>
        <p id="sns-phone-desc" style="margin-bottom:20px; color:#666; font-size:14px;">회원가입을 위해 전화번호 인증이 필요합니다.</p>
        <div style="display:flex; gap:8px; margin-bottom:12px;">
            <input type="tel" id="sns-phone-input" placeholder="휴대폰번호 (- 없이 입력)" class="form-text" style="flex:1;">
            <button type="button" id="sns-phone-btn-auth" style="white-space:nowrap; padding:0 16px; background:#ccc; color:#fff; border:none; border-radius:4px; cursor:pointer;">인증요청</button>
        </div>
        <div class="sns-phone-auth-row" style="display:none; margin-bottom:12px;">
            <div style="display:flex; gap:8px; align-items:center;">
                <input type="text" id="sns-phone-auth-input" placeholder="인증번호 6자리" class="form-text" style="flex:1;" maxlength="6">
                <span id="sns-phone-timer" style="color:#e74c3c; font-size:13px; white-space:nowrap;"></span>
            </div>
            <button type="button" id="sns-phone-btn-check" style="width:100%; margin-top:8px; padding:10px; background:#4285f4; color:#fff; border:none; border-radius:4px; cursor:pointer;">인증번호 확인</button>
        </div>
        <button type="button" id="sns-phone-btn-proceed" disabled style="width:100%; padding:12px; background:#ccc; color:#fff; border:none; border-radius:4px; cursor:pointer; margin-bottom:8px;">계속</button>
        <button type="button" id="sns-phone-btn-close" style="width:100%; padding:10px; background:#f5f5f5; color:#666; border:none; border-radius:4px; cursor:pointer;">닫기</button>
    </div>
</div>


<script>
    // SNS 회원가입 파라미터 감지 (WeChat/Apple 콜백)
    var urlParams = new URLSearchParams(window.location.search);
    var joinSns = urlParams.get('join_sns');
    var joinKey = urlParams.get('join_key');
    var joinType = urlParams.get('join_type');
    var snsUsername = urlParams.get('username');
    var snsEmail = urlParams.get('email');
    var snsGender = urlParams.get('gender');

    // WeChat: PHP 서버에서 session으로부터 unionid를 JavaScript로 전달받음 (크로스 플랫폼 지원)
    var sPhoneUnionId = '<?= isset($sPhoneUnionId) ? $sPhoneUnionId : "" ?>';


    // 구글 클라이언트 ID 전역 선언 (onGoogleLibraryLoad 시점에 접근 가능해야 함)
    var google_client_id = '<?= isset($google_client_id) ? $google_client_id : "" ?>';

    // GIS 라이브러리 로드 완료 후 자동 호출되는 콜백 (async defer 타이밍 문제 해결)
    window.onGoogleLibraryLoad = function() {
        if (google_client_id) {
            google.accounts.id.initialize({
                client_id: google_client_id,
                callback: handleGoogleCredentialResponse
            });
        }
    };

    $(document).ready(function() {
        // WeChat/Apple 콜백으로부터의 phone popup 자동 호출
        if (joinSns && joinKey && joinType) {
            setTimeout(function() {
                openSnsPhonePopup(joinKey, snsEmail, snsUsername, joinType);
                // URL에서 파라미터 제거 (뒤로가기 시 재호출 방지)
                window.history.replaceState({}, document.title, '/member/login');
            }, 100);
        }

        // 휴대폰 로그인 버튼 클릭
        $("#btnPhoneLogin").click(function(e) {
            e.preventDefault();
            e.stopPropagation();

            var state = $(this).data("state");
            if (state === "idle") {
                checkPhoneLogin();
            }
            return false;
        });

        // 휴대폰번호 입력시 정규화
        $("#phone-login-input").on("keyup", function() {
            var phone = $(this).val().replace(/[^0-9]/g, "");
            $(this).val(phone);
        });

        // 엔터 키 처리
        $("#phone-login-input").on("keypress", function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                checkPhoneLogin();
                return false;
            }
        })

        //네이버 로그인 - href로 직접 이동하므로 클릭 이벤트는 필요 없지만, 설정되지 않은 경우만 처리
        $("#btnNaver").click(function(e) {
            <?php if (empty($naver_login)) : ?>
                // 네이버 클라이언트 ID가 설정되지 않은 경우
                e.preventDefault();
                alert("네이버 로그인이 설정되지 않았습니다.");
            <?php endif; ?>
        });
        // 위챗 로그인 - 서버사이드 OAuth로 직접 이동 (href로 직접 이동하므로 클릭 이벤트는 필요 없지만, 설정되지 않은 경우만 처리)
        $("#btnWechat").click(function(e) {
            <?php if (empty($wechat_login)) : ?>
                // 위챗 AppID가 설정되지 않은 경우
                e.preventDefault();
                alert("위챗 로그인이 설정되지 않았습니다.");
            <?php endif; ?>
        });
        // 구글 로그인 - GIS SDK 팝업
        $("#btnGoogle").click(function(e) {
            e.preventDefault();
            if (!google_client_id) {
                alert("구글 로그인이 설정되지 않았습니다.");
                return;
            }
            // google 객체 미로드 시 서버사이드 OAuth로 직접 이동
            if (typeof google === 'undefined') {
                <?php if (!empty($google_login)) : ?>
                location.href = '<?= $google_login ?>';
                <?php else : ?>
                alert("구글 로그인을 불러오는 중입니다. 잠시 후 다시 시도해주세요.");
                <?php endif; ?>
                return;
            }
            //One Tap(원터치) 로그인 팝업창을 화면에 실제로 띄우기
            google.accounts.id.prompt(function(notification) {
                // One Tap 표시 불가 시 서버사이드 OAuth로 폴백
                // googleOneTapUsed: 인증 완료 후 isDismissedMoment 오탐으로 인한 이중 리다이렉트 방지
                if (!googleOneTapUsed && notification.isNotDisplayed()) {
                    <?php if (!empty($google_login)) : ?>
                    location.href = '<?= $google_login ?>';
                    <?php endif; ?>
                }
            });
        });
        //애플 로그인
        //$("#btnApple").click(function(){
        //alert("애플 로그인");
        //location.href="/";
        //});

        //애플 로그인
        $("#btnGuest").click(function() {
            alert("비회원 둘러보기");
            location.href = "/";
        });

        $('#toggle-password').click(function() {
            // Get the current type of the password input
            const input = $('#password');
            const type = input.attr('type') === 'password' ? 'text' : 'password';

            // Set the new type (text or password)
            input.attr('type', type);

            if (type === 'password') {
                $('#eye_icon').attr('src', '/resource/shop/web/img/eye_close.png');
                $('#eye_icon').css('margin-top', '15px');
                $('#eye_icon').css('margin-right', '10px');
            } else {
                $('#eye_icon').attr('src', '/resource/shop/web/img/eye_view.png');
                $('#eye_icon').css('margin-top', '15px');
                $('#eye_icon').css('margin-right', '10px');
            }

            // Toggle the eye icon (optional)
            //$(this).text(type === 'password' ? '👁️' : '👁️‍🗨️');

            /*
            const eyeIcon = $('#eye-icon');

            if (type === 'password') {
                // Switch to the "hidden eye" icon
                eyeIcon.replaceWith(`
                    <svg id="eye-icon" class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 4.5C7.5 4.5 3.8 7.3 2 12c1.2 3.1 3.6 5.6 7 6.8L3.3 6.1 4.7 4.7 20.3 20.3 18.9 21.7l-3.1-3.1c-1.4.3-2.8.4-4.3.4-4.5 0-8.2-2.8-10-7.5C3.8 7.3 7.5 4.5 12 4.5zm7.5 7.5c-.5 1.2-1.3 2.3-2.3 3.2L15 13c0-1.7-1.3-3-3-3l-1.8-1.8c.7-.2 1.5-.2 2.3-.2 3.1 0 5.7 2 7.5 4.5zM9 11.1l3.9 3.9c-.3.1-.6.1-.9.1-1.7 0-3-1.3-3-3 0-.3 0-.6.1-.9z"/>
                    </svg>
                `);
            } else {
                // Switch to the "visible eye" icon
                eyeIcon.replaceWith(`
                    <svg id="eye-icon" class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 4.5C7.5 4.5 3.8 7.3 2 12c1.8 4.7 5.5 7.5 10 7.5s8.2-2.8 10-7.5C20.2 7.3 16.5 4.5 12 4.5zm0 12.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5zm0-7.5c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                    </svg>
                `);
            }
            */


        });

    }); //ready

    // 휴대폰번호로 로그인 (SMS 발송 요청)
    function checkPhoneLogin() {
        var phone = $("#phone-login-input").val().replace(/[^0-9]/g, "");

        if (phone.length === 0) {
            commonAlert("휴대폰번호를 입력해주세요.", function() {
                $("#phone-login-input").focus();
            });
            return;
        }

        var params = { mm_phone: phone };
        comAjax("/member_proc/check_phone_login", params, function(ret) {
            if (ret.code == "00") {
                // SMS 발송 성공 - 인증 페이지로 리다이렉트
                commonAlert("인증번호를 발송하였습니다.", function() {
                    location.href = "/member/phone_login_auth?phone=" + encodeURIComponent(phone);
                });
            } else if (ret.code == "90") {
                // 미가입 - 바로 회원 생성 및 로그인
                commonAlert("회원가입을 진행합니다.", function() {
                    createPhoneMember(phone);
                });
            } else if (ret.code == "98" || ret.code == "99") {
                commonAlert(ret.msg || "오류가 발생했습니다. 다시 시도해주세요.", function() {});
            } else {
                commonAlert(ret.msg || "오류가 발생했습니다.", function() {});
            }
        });
    }

    // 휴대폰번호로 회원 생성 및 로그인
    function createPhoneMember(phone) {
        var params = { mm_phone: phone };
        comAjax("/member_proc/create_phone_member", params, function(ret) {
            if (ret.code == "00") {
                sessionStorage.removeItem("chat_user_connect");
                comAjax("/member_proc/set_last_login_type", { login_type: "WEB" }, function() {
                    location.href = "/";
                });
                return;
            } else {
                commonAlert(ret.msg || "회원가입에 실패했습니다.", function() {});
            }
        });
    }
</script>


<script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script type="text/javascript" src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>
<!--div id="appleid-signin" data-color="black" data-border="true" data-type="sign in" style="height:40px;margin-top:10px;"></div-->
<script type="text/javascript">
    AppleID.auth.init({
        clientId: 'com.nowapk.app.nowjob2.login',
        scope: 'name email',
        redirectURI: 'https://nowjob.co.kr/member/apple_login_callback',
        state: 'app_ecm_apple_sign_in',
        nonce: 'nounce',
        usePopup: true //or false defaults to false
    });

    //회원 인증 완료
    document.addEventListener('AppleIDSignInOnSuccess', (data) => {
        //로그인 성공시 실행
        var app_token = data.detail.authorization["id_token"];
        var decodedToken = parseJWT(app_token);
        var c_hash = decodedToken['c_hash'];
        var email = decodedToken['email'];
        var sub = decodedToken['sub'];
        //alert(sub);
        //$.request("/API/test", { b_no : business_code }, true, false, function (rst) {
        //	//회원가입 상태면 ret url 이동
        //
        //		});
        //console.log(data);
        //console.log(decodedToken);
        //console.log(sub);
        //return;

        var params = {};
        params["mm_join_type"] = "APPLE";
        params["mm_join_key"] = sub;
        //console.log(params);
        //return;
        comAjax("/member_proc/login", params, function(ret) {
            if (ret.code == "00") {
                sessionStorage.removeItem("chat_user_connect");
                location.href = "/";
            } else {
                if (ret.code == "90") {
                    openPopup('confirm', {
                        id: 'permission',
                        content: '<strong>회원으로 가입된 정보가 없습니다.</strong><small>회원 가입을 진행하시겠습니까?</small>',
                        firstButton: '취소',
                        secondButton: '회원가입',
                        firstCallback: function() {

                        },
                        secondCallback: function() {
                            // PASS 인증 없이 바로 회원가입 페이지로 이동
                            location.href = "/member/join_sns_start?join_key=" + sub + "&join_type=" + "APPLE";
                        },
                    });
                } else {
                    commonAlert(ret.msg, function() {});
                }
            }
        });
    });

    //로그인 오류처리
    document.addEventListener('AppleIDSignInOnFailure', (error) => {
        //handle error.
        alert("애플 로그인시 오류가 발생하였습니다! 다시 진행부탁드립니다.");
        location.reload();
    });

    // JWT 파싱
    function parseJWT(token) {
        let base64Url = token.split('.')[1];
        let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        let jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
        return JSON.parse(jsonPayload);
    }
</script>

<script>
    // One Tap 인증 완료 플래그 (isDismissedMoment 오탐 방지)
    var googleOneTapUsed = false;

    // 구글 로그인 콜백 함수
    //  id_token (JWT) 발급
    function handleGoogleCredentialResponse(response) {
        googleOneTapUsed = true;
        var params = {};
        params['id_token'] = response.credential;
        comAjax('/member_proc/google_login', params, function(ret) {
            if (ret.code == '00') {
                sessionStorage.removeItem('chat_user_connect');
                location.href = '/';
            } else if (ret.code == '90') {
                // 신규 회원 → SMS 인증 팝업
                var joinKey  = ret.data ? ret.data.join_key : '';
                var email    = ret.data ? ret.data.email  : '';
                var name     = ret.data ? ret.data.name   : '';
                openSnsPhonePopup(joinKey, email, name, 'GOOGLE');
            } else {
                // phone popup이 띠워져있으면 닫고 alert 띄우기
                if ($('#sns-phone-popup').css('display') != 'none') {
                    $('#sns-phone-popup').hide();
                }
                commonAlert(ret.msg || '구글 로그인 오류가 발생하였습니다.', function() {});
            }
        });
    }

    // 전역 변수 (SNS 회원가입 공용)
    var sPhoneJoinKey = '', sPhoneJoinType = 'GOOGLE', sPhoneEmail = '', sPhoneName = '';
    var sPhoneIsAuth = false, sPhoneTimer = null, sPhoneRemainTime = 0, sPhoneAuthCount = 0;

    // SNS 전화번호 인증 팝업 오픈 (구글, 위챗, 애플 등 공용)
    function openSnsPhonePopup(joinKey, email, name, joinType) {
        sPhoneJoinKey = joinKey;
        sPhoneJoinType = joinType || 'GOOGLE';
        sPhoneEmail = email || '';
        sPhoneName = name || '';
        sPhoneIsAuth = false;
        sPhoneAuthCount = 0;

        // 팝업 설명 변경
        var desc = '회원가입을 위해 전화번호 인증이 필요합니다.';
        if (joinType === 'GOOGLE') {
            desc = '구글 회원가입을 위해 전화번호 인증이 필요합니다.';
        } else if (joinType === 'WECHAT') {
            desc = '위챗 회원가입을 위해 전화번호 인증이 필요합니다.';
        } else if (joinType === 'APPLE') {
            desc = '애플 회원가입을 위해 전화번호 인증이 필요합니다.';
        }
        $('#sns-phone-desc').text(desc);

        $('#sns-phone-input').val('');
        $('#sns-phone-auth-input').val('');
        $('.sns-phone-auth-row').hide();
        $('#sns-phone-btn-auth').css('background-color', '#ccc');
        $('#sns-phone-btn-proceed').prop('disabled', true).css('background-color', '#ccc');
        $('#sns-phone-timer').text('');
        $('#sns-phone-popup').css({display:'flex', background:'rgba(0,0,0,0)'});
        setTimeout(function() {
            $('#sns-phone-popup').css('background', 'rgba(0,0,0,0.5)');
        }, 10);
    }

    function closeSnsPhonePopup() {
        clearInterval(sPhoneTimer);
        $('#sns-phone-popup').css('background', 'rgba(0,0,0,0)');
        setTimeout(function() {
            $('#sns-phone-popup').hide();
        }, 200);
    }

    function sPhoneUpdateTime() {
        sPhoneRemainTime--;
        if (sPhoneRemainTime <= 0) {
            clearInterval(sPhoneTimer);
            $('#sns-phone-timer').text('만료');
            return;
        }
        var m = Math.floor(sPhoneRemainTime / 60);
        var s = sPhoneRemainTime % 60;
        $('#sns-phone-timer').text(m + ':' + (s < 10 ? '0' + s : s));
    }

    // 전화번호 입력 실시간 검증 및 버튼 색상 변경
    $('#sns-phone-input').on('input keyup', function() {
        var phone = $(this).val().replace(/[^0-9]/g, '');
        var isValid = /^01[016789][0-9]{7,8}$/.test(phone);

        if (isValid) {
            $('#sns-phone-btn-auth').prop('disabled', false).css({
                'background-color': '#57CCAF',
                'cursor': 'pointer'
            });
        } else {
            $('#sns-phone-btn-auth').prop('disabled', true).css({
                'background-color': '#ccc',
                'cursor': 'not-allowed'
            });
        }
    });

    // 인증번호 요청
    $('#sns-phone-btn-auth').click(function() {
        var phone = $('#sns-phone-input').val().replace(/[^0-9]/g, '');
        if (!/^01[016789][0-9]{7,8}$/.test(phone)) {
            alert('올바른 휴대폰번호를 입력해주세요.');
            return;
        }
        comAjax('/member_proc/check_sns_phone', {mm_phone: phone}, function(ret) {
            if (ret.code == '00') {
                $('.sns-phone-auth-row').show();
                clearInterval(sPhoneTimer);
                sPhoneRemainTime = 180;
                sPhoneTimer = setInterval(sPhoneUpdateTime, 1000);
                sPhoneUpdateTime();
                alert('인증번호를 발송하였습니다.');
            } else if (ret.code == '99') {
                // phone popup 임시 숨기고 alert 띄우기
                var phonePopupDisplay = $('#sns-phone-popup').css('display');
                $('#sns-phone-popup').hide();
                commonAlert(ret.msg, function() {
                    $('#sns-phone-popup').css('display', phonePopupDisplay);
                });
            } else if (ret.code == '98') {
                var phonePopupDisplay = $('#sns-phone-popup').css('display');
                $('#sns-phone-popup').hide();
                commonAlert('하루 SMS 발송 횟수를 초과하였습니다.', function() {
                    $('#sns-phone-popup').css('display', phonePopupDisplay);
                });
            } else {
                var phonePopupDisplay = $('#sns-phone-popup').css('display');
                $('#sns-phone-popup').hide();
                commonAlert(ret.msg || '오류가 발생하였습니다.', function() {
                    $('#sns-phone-popup').css('display', phonePopupDisplay);
                });
            }
        });
    });

    // 인증번호 확인
    $('#sns-phone-btn-check').click(function() {
        var phone = $('#sns-phone-input').val().replace(/[^0-9]/g, '');
        var auth = $('#sns-phone-auth-input').val();
        comAjax('/member_proc/check_sns_phone_auth', {mm_phone: phone, auth: auth}, function(ret) {
            if (ret.code == '00') {
                sPhoneIsAuth = true;
                clearInterval(sPhoneTimer);
                $('#sns-phone-btn-proceed').prop('disabled', false).css('background-color', '#4285f4');
                alert('인증이 완료되었습니다.');
            } else {
                sPhoneAuthCount++;
                if (sPhoneAuthCount > 3) {
                    var phonePopupDisplay = $('#sns-phone-popup').css('display');
                    $('#sns-phone-popup').hide();
                    commonAlert('인증 오류가 3회 초과되었습니다.', function() {
                        closeSnsPhonePopup();
                    });
                } else {
                    alert('인증번호가 일치하지 않습니다. (' + sPhoneAuthCount + '/3)');
                }
            }
        });
    });

    // 계속 버튼 - join_sns_start로 이동 (phonenum 포함)
    $('#sns-phone-btn-proceed').click(function() {
        if (!sPhoneIsAuth) { return; }
        var phone = $('#sns-phone-input').val().replace(/[^0-9]/g, '');
        // WeChat: unionid가 있으면 우선 사용 (크로스 플랫폼 지원)
        var joinKey = (sPhoneJoinType === 'WECHAT' && sPhoneUnionId) ? sPhoneUnionId : sPhoneJoinKey;
        var joinUrl = '/member/join_sns_start'
            + '?join_key=' + encodeURIComponent(joinKey)
            + '&join_type=' + sPhoneJoinType
            + (sPhoneEmail ? '&email=' + encodeURIComponent(sPhoneEmail) : '')
            + (sPhoneName  ? '&username=' + encodeURIComponent(sPhoneName) : '')
            + '&phonenum=' + encodeURIComponent(phone);
        location.href = joinUrl;
    });

    // 닫기
    $('#sns-phone-btn-close').click(function() {
        closeSnsPhonePopup();
    });
</script>
