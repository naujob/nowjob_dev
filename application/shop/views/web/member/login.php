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
                        $naver_login = "https://nid.naver.com/oauth2.0/authorize?client_id=" . $naver_client_id . "&redirect_uri=" . $naver_redirect . "&response_type=code&state=" . urlencode($unique_state) . "&prompt=login&auth_type=reauthenticate";
                    } else {
                        // 클라이언트 ID가 없으면 빈 문자열
                        $naver_login = "";
                    }

                    // 구글 로그인 URL 생성 (서버 주도 OAuth 시작)
                    $google_setting = getSettingInfo(["st_group" => "구글"]);
                    $google_client_id = isset($google_setting["WEB_CLIENT_ID"]) ? $google_setting["WEB_CLIENT_ID"] : "";
                    $google_login = !empty($google_client_id) ? ($app_base_url . "/member/google_login") : "";
                    ?>
                    <form action="/">

                        <div class="form-row">
                            <label>
                                <span class="ico-label phone">휴대폰번호</span>
                                <input type="email" name="mm_id" placeholder="휴대폰번호를 입력해 주세요" aria-required="true" autocomplete="off" required class="form-text">
                            </label>
                        </div>

                        <div class="form-row" style="position:relative;">
                            <label>
                                <span class="ico-label pwd">비밀번호</span>
                                <input type="password" name="mm_pwd" id="password" placeholder="비밀번호를 입력해 주세요" aria-required="true" autocomplete="off" required class="form-text">
                                <span class="toggle-password" id="toggle-password" style="position:absolute;right:0;"><!-- 👁 -->️
                                    <img id="eye_icon" src="/resource/shop/web/img/eye_close.png" style="margin-top:15px; margin-right: 10px; width: 24px;">
                                    <!--
                                  <svg id="eye-icon" class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M12 4.5C7.5 4.5 3.8 7.3 2 12c1.8 4.7 5.5 7.5 10 7.5s8.2-2.8 10-7.5C20.2 7.3 16.5 4.5 12 4.5zm0 12.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5zm0-7.5c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"/>
                                  </svg>
                                  -->
                                </span>
                            </label>
                        </div>

                        <ul class="account-menu" style="margin-bottom: 20px; text-align: left;">
                            <li><label><input type="checkbox" id="chk_login_Y" name="chk_login" value="Y"><span style="margin-left: 5px;"><strong>아이디 저장</strong></span></label></li>
                        </ul>

                        <button type="button" id="btnLogin" class="btn-primary">로그인</button>

                        <ul class="account-menu">
                            <li><a href="/member/join_start">회원가입</a></li>
                            <li><a href="/member/find_id">아이디찾기</a></li>
                            <li><a href="/member/find_pwd">비밀번호 찾기</a></li>
                        </ul>

                        <div class="vendor-login">
                            <h2 class="title">간편로그인</h2>
                            <div class="social-login-buttons">
                                <a href="<?= $kakao_login ?>" id="btnKakao" class="social-btn kakao-btn"></a>
                                <?php if (!empty($naver_login)) : ?>
                                    <a href="<?= $naver_login ?>" id="btnNaver" class="social-btn naver-btn"></a>
                                <?php else : ?>
                                    <a href="javascript:void(0);" id="btnNaver" class="social-btn naver-btn"></a>
                                <?php endif; ?>
                                <?php if (!empty($google_login)) : ?>
                                    <a href="<?= $google_login ?>" id="btnGoogle" class="social-btn google-btn" aria-label="Google 로그인"></a>
                                <?php else : ?>
                                    <a href="javascript:void(0);" id="btnGoogle" class="social-btn google-btn" aria-label="Google 로그인"></a>
                                <?php endif; ?>
                            </div>
                            <!--a href="#" class="btn vendor-type2">Apple 로그인</a-->
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main><!-- //page-content -->
</div><!-- //page -->


<script>
    $(document).ready(function() {
        //로그인
        $("#btnLogin").click(function() {
            goLogin();
        });

        $("[name=mm_pwd]").keyup(function(e) {
            if (e.keyCode === 13) {
                goLogin();
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
        // 구글 로그인 - 설정되지 않은 경우만 처리
        $("#btnGoogle").click(function(e) {
            <?php if (empty($google_login)) : ?>
                e.preventDefault();
                alert("구글 로그인이 설정되지 않았습니다.");
            <?php endif; ?>
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

        if (getCookie("login_id_save") != '') {
            $("[name=mm_id]").val(getCookie("login_id_save"));
            $('input:checkbox').prop('checked', true);
        }

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

    function goLogin() {
        if ($("[name=mm_id]").val() == "") {
            commonAlert("휴대폰번호를 입력해주세요.", function() {
                $("[name=mm_id]").focus();
            });
            return;
        }

        if ($("[name=mm_pwd]").val() == "") {
            commonAlert("비밀번호를 입력해주세요.", function() {
                $("[name=mm_pwd]").focus();
            });
            return;
        }

        var params = {};
        params["mm_id"] = $("[name=mm_id]").val();
        params["mm_pwd"] = $("[name=mm_pwd]").val();
        comAjax("/member_proc/login", params, function(ret) {
            console.log(ret);
            if (ret.code == "00") {
                if ($('#chk_login_Y').is(':checked')) {
                    setCookie('login_id_save', $("[name=mm_id]").val(), 365);
                } else {
                    setCookie('login_id_save', '', 365);
                }

                sessionStorage.removeItem("chat_user_connect");
                location.href = "/";
            } else {
                commonAlert(ret.msg, function() {});
            }
        });
    }

    var setCookie = function(name, value, exp) {
        var date = new Date();
        date.setTime(date.getTime() + exp * 24 * 60 * 60 * 1000);
        document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
    };
    var getCookie = function(name) {
        var value = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return value ? value[2] : null;
    };
</script>


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
