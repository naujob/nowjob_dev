<?php
$mem_info = $this->member_model->getMemberInfo($this->mm_idx);
?>
<style>
    .profile-status .form-pic {
        cursor: auto;
    }

    .profile-status .form-pic.on {
        cursor: pointer;
    }

    .profile-status .form-pic:before {
        display: none;
    }

    .profile-status .form-pic.on:before {
        display: block;
        top: auto;
        left: auto;
        right: -18px;
        width: 38px;
        height: 38px;
        pointer-events: inherit;
        background-image: url('/resource/shop/web/img/ic_camera2.svg');
        z-index: 2;
    }
</style>
<h2 class="primary-title">마이페이지</h2>
<div class="profile-status" style="position: relative;">
    <div class="thumb-wrap">
        <label class="form-pic">
            <input type="file" id="newProfile" name="profile2" accept="image/*" disabled>
            <span class="preview">
                <img id="profilePhoto" src="<?= getResumeImg($mem_info) ?>" alt="프로필 이미지" onError="this.src='/resource/shop/mobile/img/common/noimage4.png'">
            </span>
        </label>
    </div>
    <!-- <div class="profile-name"> -->
        <!-- 이름 -->
        <!-- <p class="name"><em><?= $mem_info["mm_name"] ?></em>님</p> -->
        <!-- 평점 -->
        <!-- <p class="star-rating v2">
           
            <span class="star" style="width:<?= ($mem_info["eval_num"] * 20) ?>%;"></span>
            <span class="sr-text">평점</span>
            <span class="sr-text"><?= $mem_info["eval_num"] ?></span>
        </p> -->
    <!-- </div> -->
    <div class="profile-detail">
        <div class="meta">
            <!-- 연락처 -->
            <p class="phone"><?= $mem_info["mm_phone"] ?></p>
            <!-- 메일주소 -->
            <!-- <p class="email"><?= ($mem_info["mm_id"] == $mem_info["mm_phone"] ? "" : $mem_info["mm_id"]) ?></p> -->
            <!-- 포인트 -->
            <p class="point">
                <em><?= number_format($mem_info["mm_point"]) ?>P</em>
                <a href="/mypage/point_charge_step1">포인트 충전하기</a>
            </p>
        </div>
    </div>
    <ul class="profile-record">
        <li><span class="label">프로필 조회</span><em class="value"><?= $mem_info["hit_cnt"] ?></em></li>
        <li><span class="label">받은제안</span><em class="value"><?= $mem_info["req_cnt"] ?></em></li>
        <li><span class="label">즐겨찾기</span><em class="value"><?= $mem_info["fav_cnt"] ?></em></li>
    </ul>
    <a href="/mypage/mypage" style="position: absolute; top: 20px; right: 20px;"><img src="/resource/shop/web/img/setting_white_down.png" style="width: 24px;"></a>
</div>

<div class="tabs-wrap" style="overflow:visible;">
    <ul class="tabs has-sub is-focusing" style="overflow:visible;">
        <!-- 활성화 항목 : on 클래스 추가 -->
        <li class="<?= $tab_on == "tab1" ? "on" : "" ?>">
            <a href="/mypage/point_charge_step1" class="tab js-toggle"><span>포인트 충전 · 광고 구매 / 관리</span></a>
            <?php if ($tab_on == "tab1") { ?>
                <!-- 서브메뉴 -->
                <ul class="tabs is-sub">
                    <li class="<?= $tab_on2 == "menu1" ? "on" : "" ?>">
                        <a href="/mypage/point_charge_step1" class="tab">포인트충전</a>
                    </li>
                    <li class="<?= $tab_on2 == "menu2" ? "on" : "" ?>">
                        <a href="/mypage/point_charge_list" class="tab">포인트 충전내역</a>
                    </li>
                    <li class="<?= $tab_on2 == "menu3" ? "on" : "" ?>">
                        <a href="/mypage/point_use_list" class="tab">포인트 사용내역</a>
                    </li>
                    <li class="<?= $tab_on2 == "menu4" ? "on" : "" ?>">
                        <a href="/mypage/point_promotion_buy" class="tab">광고구매</a>
                    </li>
                    <li class="<?= $tab_on2 == "menu5" ? "on" : "" ?>">
                        <a href="/mypage/point_promotion_list" class="tab">광고관리</a>
                    </li>
                </ul>
            <?php } ?>
        </li>
        <li class="<?= $tab_on == "tab2" ? "on" : "" ?>">
            <a href="/mypage/resume_list" class="tab js-toggle"><span>이력서 등록 및 관리</span></a>
            <?php if ($tab_on == "tab2") { ?>
                <!-- 서브메뉴 -->
                <ul class="tabs is-sub">
                    <li class="<?= $tab_on2 == "menu2" ? "on" : "" ?>">
                        <a href="/mypage/resume_write" class="tab">이력서 등록</a>
                    </li>
                    <li class="<?= $tab_on2 == "menu1" ? "on" : "" ?>">
                        <a href="/mypage/resume_list" class="tab">이력서 관리</a>
                    </li>
                </ul>
            <?php } ?>
        </li>
        <li class="<?= $tab_on == "tab3" ? "on" : "" ?>">
            <a href="/mypage/bookmark_recruit1" class="tab js-toggle"><span>관심공고 관리</span></a>
            <?php if ($tab_on == "tab3") { ?>
                <!-- 서브메뉴 -->
                <ul class="tabs is-sub">
                    <li class="<?= $tab_on2 == "menu1" ? "on" : "" ?>">
                        <a href="/mypage/bookmark_recruit1" class="tab">즐겨찾기한 공고</a>
                    </li>
                    <li class="<?= $tab_on2 == "menu2" ? "on" : "" ?>">
                        <a href="/mypage/recruit_list" class="tab">지원한 공고</a>
                    </li>
                    <li class="<?= $tab_on2 == "menu3" ? "on" : "" ?>">
                        <a href="/mypage/bookmark_recruit2" class="tab">제안받은 공고</a>
                    </li>
                </ul>
            <?php } ?>
        </li>
        <li class="<?= $tab_on == "tab4" ? "on" : "" ?>">
            <a href="/mypage/coupon_list" class="tab"><span>쿠폰관리</span></a>
        </li>
        <!-- li class="<?= $tab_on == "tab5" ? "on" : "" ?>">
            <a href="/mypage/trade_list" class="tab"><span>중고거래 관리</span></a>
        </li -->
        <li class="<?= $tab_on == "tab6" ? "on" : "" ?>">
            <a href="/mypage/qna_list" class="tab js-toggle"><span>고객센터</span></a>
            <?php if ($tab_on == "tab6") { ?>
                <!-- 서브메뉴 -->
                <ul class="tabs is-sub" style="left:-220px;">
                    <li class="<?= $tab_on2 == "menu1" ? "on" : "" ?>">
                        <a href="/community/notice_list" class="tab">공지사항</a>
                    </li>
                    <li class="<?= $tab_on2 == "menu2" ? "on" : "" ?>">
                        <a href="/community/faq_list" class="tab">FAQ</a>
                    </li>
                    <li class="<?= $tab_on2 == "menu3" ? "on" : "" ?>">
                        <a href="/mypage/qna_list" class="tab">문의하기</a>
                    </li>
                    <li class="<?= $tab_on2 == "menu4" ? "on" : "" ?>">
                        <a href="/mypage/my_report_list" class="tab">나의 신고내역</a>
                    </li>
                </ul>
            <?php } ?>
        </li>
    </ul>
</div>
<script>
    $(".has-sub").find(".js-toggle").hover(function() {
        //$(this).closest("li").find(".is-sub").css("visibility","visibility");
        //$(this).closest("li").find(".is-sub").css("overflow","auto");
    });
</script>