<div class="page page-mypage">
    <div class="page-content">
        <div class="profile">
            <div class="container">
                <div class="thumb-wrap">
                    <form id="frm" onsubmit="return false">
                    <label class="form-pic">
                        <input type="file" name="mm_img1" accept="image/*" class="">
                        <span class="preview"><img src="<?=$mem_info["mm_thumb"]?>" alt=""></span>
                    </label>
                   	</form>
                    <!-- 평점 -->
                    <p class="star-rating v2">
                        <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                        <span class="star" style="width:<?=($mem_info["eval_num"] * 20)?>%;"></span>
                        <span class="sr-text">평점</span>
                        <span class="sr-text">5</span>
                    </p>
                </div>
                <div class="profile-detail">
                    <!-- 이름 -->
                    <p class="name"><em><?=$mem_info["mm_name"]?></em>님</p>
                    <div class="meta">
                        <!-- 연락처 -->
                        <p class="phone"><?=$mem_info["mm_phone"]?></p>
                        <!-- 메일주소 -->
                        <p class="email"><?=$mem_info["mm_id"]?></p>
                        <!-- 포인트 -->
                        <p class="point"><?=number_format($mem_info["mm_point"])?>P</p>
                    </div>
                    <a href="#settings" role="button" aria-haspopup="dialog" aria-controls="settings" class="btn-settings js-toggle-panel"><span class="sr-text">설정</span></a>
                </div>
                <ul class="profile-record">
                    <li><span class="label">프로필 조회</span><em class="value"><?=$hit_cnt?></em></li>
                    <li><span class="label">받은제안</span><em class="value"><?=$req_cnt?></em></li>
                    <li><span class="label">즐겨찾기</span><em class="value"><?=$fav_cnt?></em></li>
                </ul>
            </div>
        </div>
        <div class="mypage-menus">
            <ul class="list">
                <li>
                    <a href="/mypage/point_charge_step1" class="angle-link menu1">
                        <strong>포인트 충전 · 광고 구매 / 관리</strong>
                        <span>포인트를 구매하시면 추천인재로 등록이 가능합니다.</span>
                    </a>
                </li>
                <li>
                    <a href="/mypage/resume_list" class="angle-link menu2">
                        <strong>이력서 등록및 관리</strong>
                    </a>
                </li>
                <li>
                    <a href="/mypage/bookmark_recruit1" class="angle-link menu3">
                        <strong>관심공고 관리</strong>
                    </a>
                </li>
                <li>
                    <a href="/mypage/recruit_list" class="angle-link menu3">
                        <strong>지원공고 관리</strong>
                    </a>
                </li>
                <li>
                    <a href="/mypage/trade_list" class="angle-link menu3">
                        <strong>중고거래 관리</strong>
                    </a>
                </li>
                <li>
                    <a href="/mypage/bookmark_list" class="angle-link menu4">
                        <strong>즐겨찾기 관리</strong>
                    </a>
                </li>
                <li>
                    <a href="/mypage/coupon_list" class="angle-link menu5">
                        <strong>쿠폰관리</strong>
                    </a>
                </li>
            </ul>
        </div>

    </div><!-- //page-content -->

   <!-- bottom-nav -->
    <?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
    <!-- //bottom-nav -->

</div>

<!-- 설정 -->
<div id="settings" class="layer-panel" tabindex="-1">
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
                                <em class="value"><?=$mem_info["mm_name"]?></em></p>
                            </li>
                            <li>
                                <span class="label">이메일</span>
                                <em class="value"><?=$mem_info["mm_id"]?></em>
                            </li>
                            <li>
                                <span class="label">연락처</span>
                                <em class="value"><?=$mem_info["mm_phone"]?></em>
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
                                                    sessionStorage.removeItem("chat_user_connect");
                                                    location.href="/member/logout";
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
                            <li><a href="/setting/report" class="menu">문의하기</a></li>
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


<script>
	$("[name='mm_img1']").change(function(){
		var form = $('#frm');
        var params = new FormData(form[0]);
        fileAjaxLoading("/common_proc/change_img",params,function(ret){
            if(ret.code=="00"){
                location.reload();
            }else{

                commonAlert(ret.msg,function(){});
                return;
            }
        });
	});
</script>
