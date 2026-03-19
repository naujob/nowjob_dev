<div class="page page-login">
    <img src="/resource/shop/mobile/img/member/nowjob-back-logo.v3.jpg?vs=<?= getFileVersion('/resource/shop/mobile/img/member/nowjob-back-logo.v3.jpg') ?>" alt="" class="login-bg-img" decoding="async" fetchpriority="high">
    <img src="/resource/shop/mobile/img/member/ic-nowjob-logo.svg" alt="NowJob Logo" class="login-logo login-logo-centered">
    <div class="login-form">
        <div class="form-container">
            <form id="frm" onsubmit="return false">

                <div class="nowjob-login-wrapper">
                    <div class="form-row">
                        <label>
                            <span class="ico-label phone">휴대폰번호</span>
                            <input type="text" id="mm_id" name="mm_id" placeholder="010-0000-0000" aria-required="true" autocomplete="off" required class="form-text nowjob-input">
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span class="ico-label pwd">비밀번호</span>
                            <input type="password" id="mm_pwd" name="mm_pwd" placeholder="비밀번호를 입력해 주세요." aria-required="true" autocomplete="off" required class="form-text nowjob-input">
                            <span class="toggle-password" id="toggle-password" style="position:absolute;right:8px;top:8px;"><!-- 👁 -->️
                                <img id="eye_icon" src="/resource/shop/web/img/eye_close.png" style="margin-top:5px; margin-right: 10px; width: 24px;">
                            </span>
                        </label>
                    </div>
                    <div class="form-row">
                        <button id="btnLogin" type="button" class="btn-primary nowjob-login"></button>
                    </div>
                </div>
            </form>
            <div class="btn-goback-container">
                <a href="/member/login" class="btn-primary btn-goback"></a>
            </div>

        </div>

    </div>

</div>

<style>
    /* 로그인 페이지 입력 필드 스타일 2025-07-31 */
    .page-login .nowjob-input::placeholder {
        font-family: inherit;
        font-weight: 500;
        color: #BEBFC9;
        opacity: 1;
        text-indent: 0 !important;
        font-size: 16 !important;
        /* placeholder 폰트 사이즈 조절 (14px) */
    }

    .page-login .nowjob-input {
        margin-bottom: 20px;
    }

    .page.page-login .login-form .form-container #frm .form-row {
        margin-top: 11.2px !important;

    }
</style>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        console.log("DOM 준비 완료 (DOMContentLoaded)");

        // jQuery가 로드되었는지 확인
        if (typeof $ === 'undefined') {
            console.error("jQuery가 로드되지 않았습니다.");
            return;
        }


        //로그인 버튼 이벤트 - 더 안전한 방식으로 변경
        $(document).on('click', '#btnLogin', function(e) {
            e.preventDefault();
            console.log("로그인 버튼 클릭됨"); // 디버깅용
            goLogin();
        });


        $("[name=mm_pwd]").keyup(function(e) {
            if (e.keyCode === 13) {
                goLogin();
            }
        });

        function getCookie(name) {
            let matches = document.cookie.match(new RegExp("(?:^|; )" + name + "=([^;]*)"));
            return matches ? decodeURIComponent(matches[1]) : null;
        }

        // 쿠키에서 saved_id 값 가져오기
        let savedId = getCookie("saved_id");

        if (savedId && savedId != "") {
            $("#mm_id").val(savedId); // 아이디 입력 필드에 값 설정
            $("#idsave").prop("checked", true); // 체크박스 체크
        }

        if ('<?= $msg_code ?>' == '90') {
            commonAlert("회원정보를 찾을수 없습니다.", function() {});
        } else if ('<?= $msg_code ?>' == '91') {
            commonAlert("탈퇴한 회원입니다.", function() {});
        }

        // 비밀번호 보기 버튼 이벤트
        $('#toggle-password').click(function() {
            const input = $('#mm_pwd');
            const type = input.attr('type') === 'password' ? 'text' : 'password';

            input.attr('type', type);
            if (type === 'password') {
                $('#eye_icon').attr('src', '/resource/shop/web/img/eye_close.png');
                $('#eye_icon').css('margin-top', '5px');
                $('#eye_icon').css('margin-right', '10px');
            } else {
                $('#eye_icon').attr('src', '/resource/shop/web/img/eye_view.png');
                $('#eye_icon').css('margin-top', '7px');
                $('#eye_icon').css('margin-right', '10px');
            }
        });

    }); // DOMContentLoaded 끝

    function goLogin() {
        console.log("goLogin 함수 실행됨"); // 디버깅용

        // 입력값 검증
        var mmId = $("[name=mm_id]").val();
        var mmPwd = $("[name=mm_pwd]").val();
        if (mmId == "") {
            commonAlert("휴대폰번호를 입력해주세요.", function() {
                $("[name=mm_id]").focus();
            });
            return;
        }

        if (mmPwd == "") {
            commonAlert("비밀번호를 입력해주세요.", function() {
                $("[name=mm_pwd]").focus();
            });
            return;
        }
        // AJAX 요청 전에 함수 존재 확인
        if (typeof comAjax !== 'function') {
            alert('comAjax 함수를 찾을 수 없습니다.');
            return;
        }
        var params = {};
        params["mm_id"] = $("[name=mm_id]").val();
        params["mm_pwd"] = $("[name=mm_pwd]").val();
        console.log("로그인 요청 시작:", params); // 디버깅용

        comAjax("/member_proc/login", params, function(ret) {
            console.log("로그인 응답:", ret); // 디버깅용
            if (ret.code == "00") {
                sessionStorage.removeItem("chat_user_connect");
                //location.href = "/";
                _replace("/");
            } else {
                commonAlert(ret.msg, function() {});
            }
        });
    }
</script>
