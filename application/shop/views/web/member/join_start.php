<div id="content" class="page page-member-type">
    <main class="page-content">

        <div class="container">
            <div class="form-container">
                <form action="/">

                    <h1 class="title-logo"><img src="/resource/shop/web/img/join_top_logo.svg" alt="NOW JOB"></h1>

                    <div class="form-row">
                        <label class="form-toggle-btn">
                            <input type="radio" name="memberType" id="memberType1" value="mem">
                            <span class="btn">
                                <span class="ico member-type1"></span>
                                <span>일자리를 찾으신다면</span>
                                <strong>개인회원</strong>
                            </span>
                        </label>

                        <label class="form-toggle-btn">
                            <input type="radio" name="memberType" id="memberType2" value="comp">
                            <span class="btn">
                                <span class="ico member-type2"></span>
                                <span>인재를 찾으신다면</span>
                                <strong>기업회원</strong>
                            </span>
                        </label>
                    </div>

                    <!-- <p class="caution-msg">가입하신 기업/개인 회원구분은 <em>전환</em>이 불가합니다.</p> -->
                    <p class="caution-msg">개인/기업 회원가입 후 <em style="color:red;">전환</em>이 불가합니다.</p>

                    <!-- 기업회원 동의내용 -->
                    <template id="agreement">
                        <h2 class="title">불건전, 부적절한 회원은<br> <em>신고 및 영구정지</em> 됩니다.</h2>
                        <div class="agreement-form">
                            <p class="headline"><strong>나우잡은 불법행위에 대해 수사기관에<br> 적극 협조하고 있습니다</strong></p>
                            <p>불건전한 공고를 올리거나 타 대화 채널로<br> 유도하는 경우 경고 없이 이용이 제환됩니다.<br><span class="txt-red">(이용제한 업종 : 유흥업소, 인터넷방송, 다단계, 구매대행 등)</span></p>
                            <ul class="terms-list">
                                <li>
                                    <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="js-open-popup">공고 등록 제한 규정</a>
                                    <!-- 규정내용 -->
                                    <template id="terms5Temp">
                                        <div class="terms-wrap">
                                            <p class="terms-title">공고 등록 제한 규정</p>
                                            <p><strong>제 1 조 (목적)</strong></p>
                                            <p>본 약관은 (주)나우디앤아이 유한책임회사(이하 “회사”)가 운영하는 “서비스”를 이용함에 있어 “회사”와 회원간의 이용 조건 및 제반 절차, 권리, 의무 및 책임사항, 기타 필요한 사항을 규정함을 목적으로 한다.</p>
                                            <br>
                                            <p><strong>제 2 조 (용어의 정의)</strong></p>
                                            <p>이 약관에서 사용하는 용어의 정의는 아래와 같다.</p>
                                            <br>
                                            <p>①”사이트”라 함은 회사가 서비스를 “회원”에게 제공하기 위하여 컴퓨터 등 정보통신설비를 이용하여 설정한 가상의 영업장 또는 회사가 운영하는 웹사이트, 모바일웹, 앱 등의 서비스를 제공하는 모든 매체를 통칭하며, 통합된 하나의 회원 계정(아이디 및 비밀번호) 을 이용하여 서비스를 제공받을 수 있는 아래의 사이트를 말한다.</p>
                                        </div>
                                    </template>
                                </li>
                                <li>
                                    <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="js-open-popup">계정 이용 정지 규정</a>
                                    <!-- 규정내용 -->
                                    <template id="terms6Temp">
                                        <div class="terms-wrap">
                                            <p class="terms-title">계정 이용 정지 규정</p>
                                            <p><strong>제 1 조 (목적)</strong></p>
                                            <p>본 약관은 (주)나우디앤아이 유한책임회사(이하 “회사”)가 운영하는 “서비스”를 이용함에 있어 “회사”와 회원간의 이용 조건 및 제반 절차, 권리, 의무 및 책임사항, 기타 필요한 사항을 규정함을 목적으로 한다.</p>
                                            <br>
                                            <p><strong>제 2 조 (용어의 정의)</strong></p>
                                            <p>이 약관에서 사용하는 용어의 정의는 아래와 같다.</p>
                                            <br>
                                            <p>①”사이트”라 함은 회사가 서비스를 “회원”에게 제공하기 위하여 컴퓨터 등 정보통신설비를 이용하여 설정한 가상의 영업장 또는 회사가 운영하는 웹사이트, 모바일웹, 앱 등의 서비스를 제공하는 모든 매체를 통칭하며, 통합된 하나의 회원 계정(아이디 및 비밀번호) 을 이용하여 서비스를 제공받을 수 있는 아래의 사이트를 말한다.</p>
                                        </div>
                                    </template>
                                </li>
                            </ul>
                        </div>
                    </template>

                    <script>
                        // URL 파라미터 가져오는 함수를 전역 스코프로 정의
                        window.getUrlParameter = function(name) {
                            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                            var results = regex.exec(location.search);
                            var value = results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
                            console.log('getUrlParameter(' + name + ') = ' + value); // 디버깅 로그
                            return value;
                        };

                        window.addEventListener('DOMContentLoaded', function() {
                            /* 기업회원 선택시 동의 팝업 표시 */
                            document.getElementById('memberType2').addEventListener('click', function() {
                                if (!this.checked) return;

                                // SNS 간편로그인 경로인 경우 PASS 인증 없이 바로 회원가입 진행
                                console.log('memberType2 clicked, join_type = ' + getUrlParameter('join_type')); // 디버깅 로그
                                if (getUrlParameter('join_type') === 'sns') {
                                    console.log('SNS 간편로그인 경로 - PASS 인증 없이 바로 회원가입 진행'); // 디버깅 로그
                                    // 동의 팝업 표시 (PASS 인증 없이)
                                    var content = document.getElementById('agreement').innerHTML;
                                    openPopup('modal', {
                                        id: 'agreementPopup',
                                        content: content,
                                        firstButton: '동의합니다',
                                        firstCallback: function() {
                                            var params = {};
                                            params["type"] = "COMP";
                                            comAjax("/member_proc/join_auth", params, function(ret) {
                                                if (ret.code == "00") {
                                                    // PASS 인증 없이 바로 다음 단계로 이동 (웹용 경로)
                                                    location.href = './join_wcomp_step2';
                                                } else {
                                                    commonAlert(ret.msg, function() {});
                                                }
                                            });
                                        }
                                    });
                                    // 약관 팝업 이벤트 설정
                                    setTimeout(function() {
                                        document.querySelectorAll('.js-open-popup').forEach(function(btn) {
                                            btn.addEventListener('click', function(e) {
                                                e.preventDefault();
                                                var id = this.getAttribute('aria-controls');
                                                var title = this.textContent;
                                                var content = this.parentNode.querySelector('template').innerHTML;
                                                openPopup('modal', {
                                                    id: id,
                                                    title: title,
                                                    content: content,
                                                    firstButton: '확인',
                                                    firstCallback: function() {
                                                        closePopup(document.getElementById(id));
                                                    }
                                                });
                                            });
                                        });
                                    }, 100);
                                    return;
                                }

                                // 일반 회원가입 경로 - 동의 팝업 표시 후 PASS 인증
                                console.log('일반 회원가입 경로 - 동의 팝업 표시'); // 디버깅 로그
                                var content = document.getElementById('agreement').innerHTML;
                                openPopup('modal', {
                                    id: 'agreementPopup',
                                    content: content,
                                    firstButton: '동의합니다',
                                    firstCallback: function() {
                                        var params = {};
                                        params["type"] = "COMP";
                                        comAjax("/member_proc/join_auth", params, function(ret) {
                                            if (ret.code == "00") {
                                                document.frm2.action = './join_wcomp_step2';
                                                fnPopup();
                                            } else {
                                                commonAlert(ret.msg, function() {});
                                            }
                                        });
                                    }
                                });

                                // 약관 팝업 이벤트 설정
                                setTimeout(function() {
                                    document.querySelectorAll('.js-open-popup').forEach(function(btn) {
                                        btn.addEventListener('click', function(e) {
                                            e.preventDefault();
                                            var id = this.getAttribute('aria-controls');
                                            var title = this.textContent;
                                            var content = this.parentNode.querySelector('template').innerHTML;
                                            openPopup('modal', {
                                                id: id,
                                                title: title,
                                                content: content,
                                                firstButton: '확인',
                                                firstCallback: function() {
                                                    closePopup(document.getElementById(id));
                                                }
                                            });
                                        });
                                    });
                                }, 100);
                            });

                        });
                    </script>

                </form>
            </div>
        </div>

    </main><!-- //page-content -->
</div><!-- //page -->

<form name="form_chk" method="post">
    <input type="hidden" name="m" value="checkplusService">
    <input type="hidden" name="EncodeData" value="<?= $enc_data ?>">
</form>
<!--인증 결과를 받아서 가입에 필요한 회원정보를 담아 보낼 폼
(nice_success)에서 인증 데이터를 opener.document로 밀어넣고, 그 직후 fsubmit()을 호출해 전송함으로써 처리
-->
<form id="frm2" name="frm2" action="./join_wmem_step1" method="post" onsubmit="/* return false */">
    <input type="hidden" name="mm_name" readonly placeholder="가입자명(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;" onfocus="commonAlert('본인인증을 완료하면 자동으로 입력됩니다.',function(){});">
    <input type="hidden" name="mm_phone" id="mm_phone">
    <input type="hidden" name="mm_di" id="mm_di">
    <input type="hidden" name="mm_sex" id="mm_sex">
    <input type="hidden" name="mm_birth" id="mm_birth">
    <input type="hidden" name="mm_nation" id="mm_nation">
    <input type="hidden" name="bizPhone" id="bizPhone" value="01012341234">
</form>
<script>
    // 가입 중간 단계가 뒤로/앞으로 이동으로 다시 열리면 가입을 취소하고 로그인 화면으로 보낸다
    (function() {
        function goLogin() {
            location.replace('/member/login');
        }
        var nav = (window.performance && performance.getEntriesByType) ? performance.getEntriesByType('navigation')[0] : null;
        var isBackForward = nav ? (nav.type === 'back_forward') : (performance.navigation && performance.navigation.type === 2);
        if (isBackForward) {
            goLogin();
            return;
        }
        // bfcache로 복원된 경우도 로그인으로 보낸다
        window.addEventListener('pageshow', function(e) {
            if (e.persisted) {
                goLogin();
            }
        });
    })();

    window.name = "Parent_window";
    var name_ver = 1;

    function fnPopup() {
        if ($("[name=EncodeData]").val() == "") {
            alert("본인인증 모듈생성에 문제가 있습니다. 관리자에 문의해주세요.");
            return;
        }
        window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
        document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
        document.form_chk.target = "popupChk"; // 본인인증 데이터의 결과 화면을 보여줄 팝업 창 
        document.form_chk.submit();
    }
</script>
<script>
    $(document).ready(function() {
        $("[id=memberType1]").click(function() {
            // SNS 간편로그인 경로인 경우 PASS 인증 없이 바로 회원가입 진행
            if (getUrlParameter('join_type') === 'sns') {
                console.log('SNS 간편로그인 경로 - PASS 인증 없이 바로 회원가입 진행'); // 디버깅 로그
                var params = {};
                params["type"] = "MEM";
                comAjax("/member_proc/join_auth", params, function(ret) {
                    if (ret.code == "00") {
                        // PASS 인증 없이 바로 다음 단계로 이동 (웹용 경로)
                        location.href = './join_wmem_step1';
                    } else {
                        commonAlert(ret.msg, function() {});
                    }
                });
                return;
            }

            // 일반 회원가입 경로 - NICE 본인인증 팝업 호출
            console.log('개인회원 선택 - NICE 본인인증 팝업 호출'); // 디버깅 로그
            var params = {};
            params["type"] = "MEM";
            comAjax("/member_proc/join_auth", params, function(ret) {
                if (ret.code == "00") {
                    document.frm2.action = './join_wmem_step1';
                    fnPopup();
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });
        });
    });

    function fsubmit() {
        //location.href = '/member/join_wmem_step1';
        $('#frm2').submit();
    }
</script>