
<!-- 설정 -->
<div id="settings" class="layer-panel on" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="panel-title">설정</h2>
            <button type="button" aria-controls="settings" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <div class="section">
                    <div class="container">
                        <h3 class="secondary-title">기본정보</h3>
                        <ul class="settings-list">
                            <li>
                                <span class="label">이름</span>
                                <em class="value">홍길동</em></p>
                            </li>
                            <li>
                                <span class="label">이메일</span>
                                <em class="value">abcd@naver.com</em>
                            </li>
                            <li>
                                <span class="label">연락처</span>
                                <em class="value">010-0000-0000</em>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="section">
                    <div class="container">
                        <h3 class="secondary-title">회원정보 수정</h3>
                        <ul class="settings-list">
                            <li>
                                <a href="/setting/change_pwd" class="menu">비밀번호 변경</a>
                            </li>
                            <li>
                                <button type="button" aria-haspopup="dialog" class="menu js-logout">로그아웃</buton>
                                <script>
                                    /*로그아웃*/
                                    window.addEventListener('DOMContentLoaded', function () {
                                        document.querySelector('.js-logout').addEventListener('click', function (e) {
                                            e.preventDefault();
                                            openPopup('confirm', {
                                                content:'로그아웃 하시겠습니까?', 
                                                secondCallback: function () {
                                                    openPopup('alert', {content:'확인 callback'});
                                                }
                                            });
                                        });
                                    });
                                </script>
                            </li>
                            <li>
                                <a href="/setting/fire_step1" class="menu">회원탈퇴</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="section">
                    <div class="container">
                        <h3 class="secondary-title">알림</h3>
                        <ul class="settings-list">
                            <li>
                                <a href="/setting/alarm" class="menu">알림설정</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="section">
                    <div class="container">
                        <ul class="settings-menus">
                            <li><a href="/setting/notice" class="menu">공지사항</a></li>
                            <li><a href="/setting/community" class="menu">커뮤니티</a></li>
                            <li><a href="/setting/event" class="menu">이벤트</a></li>
                            <li><a href="/setting/faq" class="menu">FAQ</a></li>
                            <li><a href="/setting/qna" class="menu">문의하기</a></li>
                            <li><a href="/setting/my_report" class="menu">나의신고내역</a></li>
                            <li><a href="/setting/site_introduce" class="menu">회사소개</a></li>
                        </ul>
                    </div>
                </div>

                <div class="section is-last">
                    <div class="container">
                        <h3 class="secondary-title">약관</h3>
                        <ul class="settings-list">
                            <li><a href="/setting/site_privacy" class="menu">서비스 이용약관</a></li>
                            <li><a href="/setting/site_terms" class="menu">개인정보 취급방침</a></li>
                            <li><a href="/setting/site_terms2" class="menu">제3자 개인정보 수집동의</a></li>
                            <li><a href="/setting/site_terms3" class="menu">위치정보 이용</a></li>
                        </ul>
                    </div>
                </div>

                <!-- 20220929 수정 -->
                <div class="footer">
                    <p class="title">앱정보</p>
                    <p class="name"><strong>(주)나우인터내셔널</strong></p>
                    <div class="service-provider">
                        <p>
                            <span>Business registration number 769-88-01746</span><br>
                            <span>Representative name HAN OG CHOI</span>
                        </p>
                        <p>Address 1st floor, 2, Juju-ro 80beon-gil, Namyang-eup, Hwaseong-si, Gyeonggi-do</p>
                        <p>Tel 031-255-0119</p>
                    </div>
                </div>

                <a href="#app" class="btn-top js-goto-top"><span class="sr-text">Top</span></a>
                
            </div>
        </div>
    </div>
</div>
<!-- //설정 -->