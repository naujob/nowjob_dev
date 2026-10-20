<style>
    .page.page-login {
        margin-top: 110px;
    }

    /* 토스트 메시지 (자동으로 사라지는 안내) */
    .gphone-toast-msg {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.75);
        color: #fff;
        padding: 14px 24px;
        border-radius: 12px;
        font-size: 15px;
        line-height: 1.5;
        z-index: 10001;
        opacity: 0;
        white-space: pre-line;
        text-align: center;
        max-width: 90%;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .gphone-toast-msg.gphone-toast-show {
        opacity: 1;
    }

    /* 팝업이 열려 있을 때는 모달 안 가운데에 표시 (gPhoneToast가 #sns-phone-sheet에 붙임) */
    .sns-phone-sheet .gphone-toast-msg {
        position: absolute;
        top: 50%;
        left: 50%;
        bottom: auto;
        transform: translate(-50%, -50%);
        width: max-content;
        max-width: 80%;
        z-index: 1;
    }
    .sns-phone-sheet .gphone-toast-msg.gphone-toast-show {
        opacity: 1;
    }

    /* SNS 전화번호 인증 팝업 — 모바일 gphone 팝업(main.css .bs-*, .inline-group, .form-text.gphone-input)과 값 통일, 가운데 모달 레이아웃 유지 */
    .sns-phone-sheet {
        position: relative;
        background: #fff;
        border-radius: 32px;
        padding: 32px 24px 36px;
        width: 400px;
        max-width: 90%;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    .sns-phone-title {
        font-size: 25px;
        font-weight: 700;
        color: #1B9D7D;
        margin: 0 0 10px;
    }
    .sns-phone-desc {
        font-size: 15px;
        color: #888;
        font-weight: 600;
        margin: 0 0 28px;
    }
    .sns-phone-field {
        margin-bottom: 20px;
    }
    .sns-phone-auth-row {
        margin-bottom: 32px;
    }
    .sns-phone-label {
        font-size: 18px;
        font-weight: 700;
        color: #222;
        margin: 0 0 14px;
    }
    .sns-phone-inline {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .sns-phone-input-wrap {
        position: relative;
        flex: 1;
        min-width: 0;
        display: flex;
    }
    /* 전역 .form-text(밑줄형)를 모바일 .form-text.gphone-input과 같은 둥근 박스형으로 덮어씀 */
    .form-text.sns-phone-input {
        flex: 1;
        min-width: 0;
        width: 100%;
        height: 64px;
        padding: 0 0 0 10px;
        border: 1px solid gray;
        border-radius: 19px;
        font-size: 20px;
        font-weight: 700;
        color: #000;
        background: transparent;
        box-shadow: none;
        outline: 0;
    }
    .form-text.sns-phone-input:focus {
        border-color: #57CCAF;
        box-shadow: inset 0 0 0 1px #57CCAF, 0 0 0 3px rgba(87, 204, 175, 0.6);
    }
    .sns-phone-input-wrap .form-text.sns-phone-input {
        padding-right: 64px;
    }
    .sns-phone-timer {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        color: #FF6D6D;
        font-size: 19px;
        font-weight: 600;
        white-space: nowrap;
        pointer-events: none;
    }
    .sns-phone-btn {
        border: none;
        cursor: pointer;
        white-space: nowrap;
    }
    /* 인증요청/확인 버튼: 모바일 .inline-group .btn:last-child 값 (90×66, radius 19, 19px/700) */
    .sns-phone-btn-tertiary {
        width: 90px;
        height: 66px;
        flex-shrink: 0;
        border-radius: 19px;
        font-size: 19px;
        font-weight: 700;
        color: #fff;
        background: #1B9D7D;
    }
    .sns-phone-btn-tertiary:active {
        background: #14715a;
    }
    .sns-phone-btn-tertiary:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    /* 하단 버튼: 모바일 .bs-actions 값 (높이 56, 24px, radius 40) */
    .sns-phone-btn-secondary {
        flex: 1;
        height: 56px;
        font-size: 24px;
        font-weight: 700;
        border-radius: 40px;
        background: #fff;
        color: #1B9D7D;
        border: 2px solid #1B9D7D;
    }
    .sns-phone-btn-secondary:active {
        background: #f0faf7;
    }
    .sns-phone-btn-primary {
        flex: 1;
        height: 56px;
        font-size: 24px;
        font-weight: 700;
        border-radius: 40px;
        color: #fff;
        background: #1B9D7D;
    }
    .sns-phone-btn-primary:disabled {
        background: #ccc;
        cursor: not-allowed;
        opacity: .6;
    }
    .sns-phone-actions {
        display: flex;
        gap: 8px;
        margin-top: 8px;
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
                    // 카카오 로그인 설정
                    $kakao_redirect = urlencode($app_base_url . "/member/kakao_login_callback");
                    $kakao_setting = getSettingInfo(["st_group" => "카카오"]);
                    // OAuth 리다이렉트용 REST API Key
                    $kakao_rest_api_key = isset($kakao_setting["REST_API_KEY"]) ? $kakao_setting["REST_API_KEY"] : "";
                    // Kakao SDK 초기화용 자바스크립트 키
                    $kakao_js_key = isset($kakao_setting["인증키"]) ? $kakao_setting["인증키"] : "";
                    // Kakao state 세션에 저장 (콜백에서 검증 시 사용)
                    $this->common->setSession("kakao-oauth-state", $csrf);
                    // OAuth 리다이렉트 URL (사용하지 않음, 대신 AJAX로 변경)
                    // 카카오 로그인 (OAuth scope 없음 - 기본값으로 phonenum이 포함될 경우 사용)
                    $kakao_login = "https://kauth.kakao.com/oauth/authorize?response_type=code&client_id=" . $kakao_rest_api_key . "&redirect_uri=" . $kakao_redirect . "&state=" . urlencode($csrf) . "&prompt=login";
                    
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
                                <a href="<?= $kakao_login ?>" id="btnKakao" data-recent="<?= isset($recent_login_type) && $recent_login_type === 'KAKAO' ? 'true' : 'false' ?>">
                                    <img src="/resource/shop/web/img/ic-kakao-login.png" alt="카카오 로그인">
                                    <?php if (isset($recent_login_type) && $recent_login_type === 'KAKAO') : ?>
                                        <span class="recent-badge">최근</span>
                                    <?php endif; ?>
                                </a>
                                <?php if (!empty($wechat_login)) : ?>
                                    <a href="<?= $wechat_login ?>" id="btnWechat" data-recent="<?= isset($recent_login_type) && $recent_login_type === 'WECHAT' ? 'true' : 'false' ?>">
                                        <img src="/resource/shop/web/img/ic-wechat-login.png" alt="WeChat 로그인">
                                        <?php if (isset($recent_login_type) && $recent_login_type === 'WECHAT') : ?>
                                            <span class="recent-badge">최근</span>
                                        <?php endif; ?>
                                    </a>
                                <?php else : ?>
                                    <a href="javascript:void(0);" id="btnWechat" data-recent="false"><img src="/resource/shop/web/img/ic-wechat-login.png" alt="WeChat 로그인"></a>
                                <?php endif; ?>
                                <!-- 2행: 네이버 + 구글 -->
                                <?php if (!empty($naver_login)) : ?>
                                    <a href="<?= $naver_login ?>" id="btnNaver" data-recent="<?= isset($recent_login_type) && $recent_login_type === 'NAVER' ? 'true' : 'false' ?>">
                                        <img src="/resource/shop/web/img/ic-naver-login.png" alt="네이버 로그인">
                                        <?php if (isset($recent_login_type) && $recent_login_type === 'NAVER') : ?>
                                            <span class="recent-badge">최근</span>
                                        <?php endif; ?>
                                    </a>
                                <?php else : ?>
                                    <a href="javascript:void(0);" id="btnNaver" data-recent="false"><img src="/resource/shop/web/img/ic-naver-login.png" alt="네이버 로그인"></a>
                                <?php endif; ?>
                                <a href="javascript:void(0);" id="btnGoogle" data-recent="<?= isset($recent_login_type) && $recent_login_type === 'GOOGLE' ? 'true' : 'false' ?>">
                                    <img src="/resource/shop/web/img/ic-google-login.png" alt="Google 로그인">
                                    <?php if (isset($recent_login_type) && $recent_login_type === 'GOOGLE') : ?>
                                        <span class="recent-badge">최근</span>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <!--a href="#" class="btn vendor-type2">Apple 로그인</a-->
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main><!-- //page-content -->
</div><!-- //page -->

<!-- Kakao SDK 제거됨: OAuth 콜백 방식으로 통일 -->
<!-- <script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script> -->

<!-- SNS 회원가입 전화번호 인증 팝업 (구글, 위챗, 애플 등 공용) -->
<div id="sns-phone-popup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0); z-index:2000; align-items:center; justify-content:center;">
    <div id="sns-phone-sheet" class="sns-phone-sheet">
        <h3 class="sns-phone-title">전화번호 인증</h3>
        <p id="sns-phone-desc" class="sns-phone-desc">회원가입을 위해 전화번호 인증이 필요합니다.</p>

        <div class="sns-phone-field">
            <p class="sns-phone-label">휴대폰번호</p>
            <div class="sns-phone-inline">
                <input type="tel" id="sns-phone-input" placeholder="휴대폰번호를 입력해 주세요" autocomplete="off" class="form-text sns-phone-input">
                <button type="button" id="sns-phone-btn-auth" class="sns-phone-btn sns-phone-btn-tertiary" disabled>인증 요청</button>
            </div>
        </div>

        <div class="sns-phone-field sns-phone-auth-row" style="display:none;">
            <p class="sns-phone-label">인증번호</p>
            <div class="sns-phone-inline">
                <div class="sns-phone-input-wrap">
                    <input type="text" id="sns-phone-auth-input" placeholder="인증번호를 입력해 주세요" autocomplete="one-time-code" inputmode="numeric" maxlength="6" class="form-text sns-phone-input">
                    <span id="sns-phone-timer" class="sns-phone-timer"></span>
                </div>
                <button type="button" id="sns-phone-btn-check" class="sns-phone-btn sns-phone-btn-tertiary">확인</button>
            </div>
        </div>

        <div class="sns-phone-actions">
            <button type="button" id="sns-phone-btn-close" class="sns-phone-btn sns-phone-btn-secondary">취소</button>
            <button type="button" id="sns-phone-btn-proceed" class="sns-phone-btn sns-phone-btn-primary" disabled>회원가입</button>
        </div>
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
    //GIS 라이브러리가 주 방식이고, 로드 실패 시에만 서버사이드 OAuth로 폴백
    window.onGoogleLibraryLoad = function() {
        if (google_client_id) {
            google.accounts.id.initialize({
                client_id: google_client_id,
                callback: handleGoogleCredentialResponse
            });
        }
    };

    // Kakao SDK 제거됨: OAuth 콜백 방식으로 통일


    $(document).ready(function() {
        // 구글 로그인 실패 코드 감지 (msg_code 쿼리파라미터)
        var msgCode = urlParams.get('msg_code');
        if (msgCode) {
            setTimeout(function() {
                var errorMessages = {
                    '91': '계정 조회에 실패했습니다.',
                    '98': '구글 로그인 요청 횟수를 초과했습니다.',
                    '99': '구글 로그인 처리 중 오류가 발생했습니다.'
                };
                var errorMsg = errorMessages[msgCode] || '구글 로그인에 실패했습니다.';
                commonAlert(errorMsg, function() {});
                window.history.replaceState({}, document.title, '/member/login');
            }, 100);
        }

        // 네이버 중복 계정 감지 (dup_join_type 쿼리파라미터)
        var dupJoinType = urlParams.get('dup_join_type');
        var dupMmPhone = urlParams.get('dup_mm_phone');
        if (dupJoinType) {
            setTimeout(function() {
                showDuplicateProviderPopup(dupJoinType, dupMmPhone || '');
                window.history.replaceState({}, document.title, '/member/login');
            }, 100);
        }

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

        // 카카오 로그인 - 설정 미비 시 안내
        $("#btnKakao").click(function(e) {
            <?php if (empty($kakao_rest_api_key)) : ?>
                e.preventDefault();
                alert("카카오 로그인이 설정되지 않았습니다.");
            <?php endif; ?>
        });

        // 네이버 로그인 - 설정 미비 시 안내
        $("#btnNaver").click(function(e) {
            <?php if (empty($naver_login)) : ?>
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

        requestPhoneLoginSms(phone);
    }

    // 휴대폰번호 로그인 SMS 발송 → 인증 페이지 이동 (입력란 로그인, 중복 계정 팝업 인증에서 공통 사용)
    function requestPhoneLoginSms(phone) {
        phone = String(phone || "").replace(/[^0-9]/g, "");
        var params = { mm_phone: phone };
        comAjax("/member_proc/check_phone_login", params, function(ret) {
            if (ret.code == "00") {
                // 테스트 번호는 SMS 인증 생략하고 바로 홈으로 이동
                if (ret.data && ret.data.skip_auth) {
                    sessionStorage.removeItem("chat_user_connect");
                    location.href = "/";
                    return;
                }
                // SMS 발송 성공 - 인증 페이지로 리다이렉트
                commonAlert("인증번호를 발송하였습니다.", function() {
                    location.href = "/member/phone_login_auth?phone=" + encodeURIComponent(phone);
                });
            } else if (ret.code == "90") {
                // 미가입 회원 - 회원가입 안내 후 로그인 페이지에 머무름
                commonAlert(ret.msg || "회원가입이 필요합니다.", function() {});
            } else if (ret.code == "98" || ret.code == "99") {
                commonAlert(ret.msg || "오류가 발생했습니다. 다시 시도해주세요.", function() {});
            } else {
                commonAlert(ret.msg || "오류가 발생했습니다.", function() {});
            }
        });
    }
</script>


<script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<!-- <script type="text/javascript" src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script> -->
<!--div id="appleid-signin" data-color="black" data-border="true" data-type="sign in" style="height:40px;margin-top:10px;"></div-->
<script type="text/javascript">
    // AppleID.auth.init({
    //     clientId: 'com.nowapk.app.nowjob2.login',
    //     scope: 'name email',
    //     redirectURI: 'https://nowjob.co.kr/member/apple_login_callback',
    //     state: 'app_ecm_apple_sign_in',
    //     nonce: 'nounce',
    //     usePopup: true //or false defaults to false
    // });

    //회원 인증 완료
    // document.addEventListener('AppleIDSignInOnSuccess', (data) => {
       
    //     var app_token = data.detail.authorization["id_token"];
    //     var decodedToken = parseJWT(app_token);
    //     var c_hash = decodedToken['c_hash'];
    //     var email = decodedToken['email'];
    //     var sub = decodedToken['sub'];

    //     var params = {};
    //     params["mm_join_type"] = "APPLE";
    //     params["mm_join_key"] = sub;
     
    //     comAjax("/member_proc/login", params, function(ret) {
    //         if (ret.code == "00") {
    //             sessionStorage.removeItem("chat_user_connect");
    //             location.href = "/";
    //         } else if (ret.code == "90") {
               
    //             openPopup('confirm', {
    //                 id: 'permission',
    //                 content: '<strong>회원으로 가입된 정보가 없습니다.</strong><small>회원 가입을 진행하시겠습니까?</small>',
    //                 firstButton: '취소',
    //                 secondButton: '회원가입',
    //                 firstCallback: function() {

    //                 },
    //                 secondCallback: function() {
                     
    //                     location.href = "/member/join_sns_start?join_key=" + sub + "&join_type=" + "APPLE";
    //                 },
    //             });
    //         } else if (ret.code == "91" && ret.data && ret.data.join_type) {
               
    //             showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || "");
    //         } else {
    //             commonAlert(ret.msg, function() {});
    //         }
    //     });
    // });

    //로그인 오류처리
    // document.addEventListener('AppleIDSignInOnFailure', (error) => {
       
    //     alert("애플 로그인시 오류가 발생하였습니다! 다시 진행부탁드립니다.");
    //     location.reload();
    // });

    // JWT 파싱
    // function parseJWT(token) {
    //     let base64Url = token.split('.')[1];
    //     let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    //     let jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
    //         return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    //     }).join(''));
    //     return JSON.parse(jsonPayload);
    // }

    // 카카오 AJAX 함수 제거됨: OAuth 콜백 방식으로 통일

    // 중복 계정 안내 팝업 (SNS 로그인 시 다른 SNS로 이미 가입된 경우)
    function showDuplicateProviderPopup(joinType, mmPhone) {
        var providerLabels = {
            'KAKAO': '카카오',
            'NAVER': '네이버',
            'GOOGLE': '구글',
            'WECHAT': '위챗',
            'WEB': '휴대폰'
        };
        var providerLabel = providerLabels[joinType] || joinType;
        var content = '이미 ' + providerLabel + '로 가입되어 있습니다.<br>' + providerLabel + ' 계정으로 로그인 하시겠습니까?';

        openPopup('confirm', {
            id: 'duplicateProvider',
            content: content,
            firstButton: '취소',
            secondButton: '로그인 하기',
            firstCallback: function() {
                // 취소: 팝업만 닫기
            },
            secondCallback: function() {
                // 로그인 하기: 이미 가입된 SNS/휴대폰 계정으로 자동 로그인
                // phone_auto_login 엔드포인트를 통일: mm_join_type 파라미터로 SNS 타입 지정
                if (joinType === 'GOOGLE') {
                    $('#btnGoogle').click();
                } else if (mmPhone) {
                    // 카카오/네이버/위챗/휴대폰 모두 동일한 엔드포인트 사용
                    comAjax('/member_proc/phone_auto_login', {
                        mm_phone: mmPhone,
                        mm_join_type: joinType
                    }, function(ret) {
                        if (ret.code == '00') {
                            sessionStorage.removeItem('chat_user_connect');
                            location.href = '/';
                        } else if (ret.code == '80') {
                            // 본인 확인이 안 된 번호 → 휴대폰 문자 인증 로그인으로 이동
                            requestPhoneLoginSms((ret.data && ret.data.phone) ? ret.data.phone : mmPhone);
                        } else {
                            commonAlert(ret.msg || '로그인 실패', function() {});
                        }
                    });
                }
            }
        });
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
                    closeSnsPhonePopup();
                }
                commonAlert(ret.msg || '구글 로그인 오류가 발생하였습니다.', function() {});
            }
        });
    }

    // 전역 변수 (SNS 회원가입 공용)
    var sPhoneJoinKey = '', sPhoneJoinType = 'GOOGLE', sPhoneEmail = '', sPhoneName = '';
    var sPhoneIsAuth = false, sPhoneTimer = null, sPhoneRemainTime = 0, sPhoneAuthCount = 0;
    var sPhoneAuthPhone = ''; // 인증 완료된 전화번호 (번호 변경 감지용)

    /**
     * 계속 버튼 활성/비활성 전환
     * @param {boolean} enabled  true면 파란색 활성, false면 회색 비활성
     */
    function setSnsProceedEnabled(enabled) {
        // 색상/커서는 CSS(.sns-phone-btn-primary:disabled)가 처리
        $('#sns-phone-btn-proceed').prop('disabled', !enabled);
    }

    /**
     * 자동으로 사라지는 토스트 메시지 (alert 대신 사용)
     * @param {string} msg       표시할 메시지
     * @param {number} duration  표시 시간(ms), 기본 2000
     */
    function gPhoneToast(msg, duration) {
        duration = duration || 2000;
        var $t = $('<div class="gphone-toast-msg">' + msg + '</div>');
        // 팝업이 열려 있으면 모달 안(가운데), 아니면 화면 하단에 표시
        var $host = $('#sns-phone-popup').is(':visible') ? $('#sns-phone-sheet') : $('body');
        $host.append($t);
        setTimeout(function() {
            $t.addClass('gphone-toast-show');
        }, 10);
        setTimeout(function() {
            $t.removeClass('gphone-toast-show');
            setTimeout(function() {
                $t.remove();
            }, 350);
        }, duration);
    }

    // SNS 전화번호 인증 팝업 오픈 (구글, 위챗, 애플 등 공용)
    function openSnsPhonePopup(joinKey, email, name, joinType) {
        sPhoneJoinKey = joinKey;
        sPhoneJoinType = joinType || 'GOOGLE';
        sPhoneEmail = email || '';
        sPhoneName = name || '';
        sPhoneIsAuth = false;
        sPhoneAuthPhone = '';
        sPhoneAuthCount = 0;

        // 팝업 열림 동안 뒤 화면 스크롤 잠금
        $('html, body').css('overflow', 'hidden');

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
        setSnsProceedEnabled(false);
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
            // 뒤 화면 스크롤 잠금 해제
            $('html, body').css('overflow', '');
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
        $('#sns-phone-timer').text(m + ' : ' + (s < 10 ? '0' + s : s));
    }

    // 전화번호 입력 실시간 검증 및 버튼 색상 변경
    $('#sns-phone-input').on('input keyup', function() {
        var phone = $(this).val().replace(/[^0-9]/g, '');
        var isValid = /^01[016789][0-9]{7,8}$/.test(phone);

        // 인증 완료 후 번호가 바뀌면 인증 무효화 + 계속 버튼 비활성
        if (sPhoneIsAuth && phone !== sPhoneAuthPhone) {
            sPhoneIsAuth = false;
            setSnsProceedEnabled(false);
        }

        if (isValid) {
            $('#sns-phone-btn-auth').prop('disabled', false).css({
                'background-color': '#1B9D7D',
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
            gPhoneToast('올바른 휴대폰번호를 입력해주세요.', 1500);
            return;
        }
        comAjax('/member_proc/check_sns_phone', {mm_phone: phone}, function(ret) {
            if (ret.code == '00') {
                $('.sns-phone-auth-row').show();
                clearInterval(sPhoneTimer);
                sPhoneRemainTime = 180;
                sPhoneTimer = setInterval(sPhoneUpdateTime, 1000);
                sPhoneUpdateTime();
                // 재발송 시 이전 인증 무효화
                sPhoneIsAuth = false;
                setSnsProceedEnabled(false);
                gPhoneToast('인증번호를 발송하였습니다.');
            } else if (ret.code == '91') {
                // 중복 계정 감지 → phone popup 닫고 중복 계정 모달 표시
                closeSnsPhonePopup();
                setTimeout(function() {
                    showDuplicateProviderPopup(ret.data.join_type, ret.data.mm_phone || '');
                }, 400);
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

        // 유효시간(3분) 초과 시 서버 요청 없이 안내
        if (sPhoneRemainTime <= 0) {
            gPhoneToast('인증번호 유효시간이 초과되었습니다.\n다시 발송해주세요.', 2000);
            return;
        }

        comAjax('/member_proc/check_sns_phone_auth', {mm_phone: phone, auth: auth}, function(ret) {
            if (ret.code == '00') {
                // 인증 성공 — 계속 버튼 활성화 + 인증된 번호 기록
                sPhoneIsAuth = true;
                sPhoneAuthPhone = phone;
                clearInterval(sPhoneTimer);
                setSnsProceedEnabled(true);
                gPhoneToast('인증이 완료되었습니다.', 1500);
            } else {
                sPhoneAuthCount++;
                if (sPhoneAuthCount > 3) {
                    var phonePopupDisplay = $('#sns-phone-popup').css('display');
                    $('#sns-phone-popup').hide();
                    commonAlert('인증 오류가 3회 초과되었습니다.', function() {
                        closeSnsPhonePopup();
                    });
                } else {
                    // 불일치 — 입력값 비우고 다시 입력 유도
                    gPhoneToast('잘못된 인증번호입니다. (' + sPhoneAuthCount + '/3)', 1500);
                    $('#sns-phone-auth-input').val('').focus();
                }
            }
        });
    });

    // Enter 키 처리: 휴대폰번호 입력칸 → 인증 요청(버튼 활성 상태일 때만), 인증번호 입력칸 → 확인
    $('#sns-phone-input').on('keydown', function(e) {
        if (e.which === 13 && !$('#sns-phone-btn-auth').prop('disabled')) {
            e.preventDefault();
            $('#sns-phone-btn-auth').click();
        }
    });
    $('#sns-phone-auth-input').on('keydown', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#sns-phone-btn-check').click();
        }
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
