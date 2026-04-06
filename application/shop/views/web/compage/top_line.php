<?php
$mem_info = $this->member_model->getMemberInfo($this->mm_idx);
// $tab_on2는 각 페이지에서 설정하므로 여기서 초기화하지 않음
?>
<h2 class="primary-title">마이페이지</h2>
<style>
    .profile-status .form-pic:before {
        background-image: none;
    }

    /* 서브메뉴가 다른 메뉴 항목을 가리지 않도록 수정 */
    .tabs-wrap .tabs li {
        position: relative;
        z-index: 1;
    }

    .tabs-wrap .tabs li.on {
        z-index: 2;
    }

    /* 서브메뉴가 다른 메뉴 항목 위에 겹쳐서 표시되지 않도록 */
    .tabs-wrap .tabs.is-sub {
        z-index: 3;
        /* 서브메뉴가 부모 li 요소의 범위를 벗어나지 않도록 */
        overflow: visible;
    }

    /* 서브메뉴가 표시될 때 다른 메뉴 항목도 클릭 가능하도록 */
    .tabs-wrap .tabs li:not(.on) {
        pointer-events: auto;
    }

    .tabs-wrap .tabs li:not(.on)>.tab {
        pointer-events: auto;
        position: relative;
        z-index: 1;
    }

    /* 서브메뉴의 각 항목이 클릭 가능하도록 */
    .tabs-wrap .tabs.is-sub li {
        pointer-events: auto;
    }

    .tabs-wrap .tabs.is-sub .tab {
        pointer-events: auto;
    }
</style>
<div class="profile-status" style="position: relative;">
    <div class="thumb-wrap">
        <label class="form-pic" style="border-radius: 10%;" onclick="return false;">
            <input type="file" name="profile2" accept="image/*" class="js-file-preview">
            <span class="preview">
                <img src="<?= getResumeImg($mem_info) ?>" alt="" onload="$(this).closest('.form-pic').addClass('has-logo');" onError="this.src='/resource/shop/mobile/img/common/noimage4.png'">
            </span>
        </label>
    </div>
    <div class="profile-name">
        <?php
        // 기업회원 표기: 미인증 시 "기업회원", 인증 후 기업명 표시
        $is_comp_user = (isset($mem_info['mm_type']) && $mem_info['mm_type'] === 'COMP') || (!empty($mem_info['mm_comp_num']) || !empty($mem_info['mm_comp_name']));
        $comp_certified = $is_comp_user && !empty($mem_info['mm_comp_num']);
        $comp_name = ($is_comp_user && isset($mem_info['mm_comp_name'])) ? $mem_info['mm_comp_name'] : '';
        $display_name = $is_comp_user
            ? (($comp_certified && $comp_name) ? $comp_name : '기업회원')
            : $mem_info["mm_name"];
        ?>
        <!-- 이름 -->
        <p class="name"><em><?= $display_name ?></em>님</p>

    </div>
    <div class="profile-detail">
        <div class="meta">
            <!-- 연락처 -->
            <p class="phone"><?= $mem_info["mm_phone"] ?></p>
            <!-- 메일주소 -->
            <!-- <p class="email"><?= ($mem_info["mm_id"] == $mem_info["mm_phone"] ? "" : $mem_info["mm_id"]) ?></p> -->
            <!-- 포인트 -->
            <p class="point">
                <em><?= number_format($mem_info["mm_point"]) ?>P</em>
                <a href="/compage/point_charge_step1">포인트 충전하기</a>
            </p>
        </div>
    </div>
    <a href="/compage/mypage" style="position: absolute; top: 20px; right: 20px;"><img src="/resource/shop/web/img/setting_white_down.png" style="width: 24px;"></a>
</div>

<?php if (!isset($hide_tabs) || !$hide_tabs) : ?>
    <div class="tabs-wrap" style="overflow:visible;">
        <ul class="tabs has-sub is-focusing" style="overflow:visible;">
            <!-- 활성화 항목 : on 클래스 추가 -->
            <li class="<?= $tab_on == "tab1" ? "on" : "" ?>">
                <a href="/compage/point_charge_step1" class="tab js-toggle"><span>포인트 충전 · 광고 구매 / 관리</span></a>
                <?php if ($tab_on == "tab1") { ?>
                    <!-- 서브메뉴 -->
                    <ul class="tabs is-sub">
                        <li class="<?= $tab_on2 == "menu1" ? "on" : "" ?>">
                            <a href="/compage/point_charge_step1" class="tab">포인트충전</a>
                        </li>
                        <li class="<?= $tab_on2 == "menu2" ? "on" : "" ?>">
                            <a href="/compage/point_charge_list" class="tab">포인트 충전내역</a>
                        </li>
                        <li class="<?= $tab_on2 == "menu3" ? "on" : "" ?>">
                            <a href="/compage/point_use_list" class="tab">포인트 사용내역</a>
                        </li>
                        <li class="<?= $tab_on2 == "menu4" ? "on" : "" ?>">
                            <a href="/compage/point_promotion_buy" class="tab">광고구매</a>
                        </li>
                        <li class="<?= $tab_on2 == "menu5" ? "on" : "" ?>">
                            <a href="/compage/point_promotion_list" class="tab">광고관리</a>
                        </li>
                    </ul>
                <?php } ?>
            </li>
            <li class="<?= $tab_on == "tab2" ? "on" : "" ?>">
                <a href="/compage/recruit_list" class="tab js-toggle"><span>채용공고 등록 및 관리</span></a>
                <?php if ($tab_on == "tab2") { ?>
                    <!-- 서브메뉴 -->
                    <ul class="tabs is-sub">
                        <li class="<?= $tab_on2 == "menu2" ? "on" : "" ?>">
                            <a href="javascript:void(0);" id="btnWrite" class="tab">채용공고 등록</a>
                        </li>
                        <li class="<?= $tab_on2 == "menu1" ? "on" : "" ?>">
                            <a href="/compage/recruit_list" class="tab">채용공고 관리</a>
                        </li>
                        <!-- <li class="<?= $tab_on2 == "menu1" ? "on" : "" ?>">
                        <a href="/compage/applicant_list" class="tab">지원자 관리</a>
                    </li> -->
                    </ul>
                <?php } ?>
            </li>
            <li class="<?= $tab_on == "tab3" ? "on" : "" ?>">
                <a href="/compage/bookmark_person1" class="tab js-toggle"><span>관심인재 관리</span></a>
                <?php if ($tab_on == "tab3") { ?>
                    <!-- 서브메뉴 -->
                    <ul class="tabs is-sub">
                        <li class="<?= $tab_on2 == "menu1" ? "on" : "" ?>">
                            <a href="/compage/bookmark_person1" class="tab">즐겨찾기한 인재</a>
                        </li>
                        <li class="<?= $tab_on2 == "menu2" ? "on" : "" ?>">
                            <a href="/compage/bookmark_person2" class="tab">지원받은 인재</a>
                        </li>
                        <li class="<?= $tab_on2 == "menu3" ? "on" : "" ?>">
                            <a href="/compage/bookmark_person3" class="tab">제안보낸 인재</a>
                        </li>
                    </ul>
                <?php } ?>
            </li>
            <!-- <li class="<?= $tab_on == "tab4" ? "on" : "" ?>">
            <a href="/compage/bookmark_list" class="tab"><span>즐겨찾기 관리</span></a>
        </li> -->
            <li class="<?= $tab_on == "tab6" ? "on" : "" ?>">
                <a href="/compage/coupon_list" class="tab"><span>쿠폰관리</span></a>
            </li>
            <!-- li class="<?= $tab_on == "tab5" ? "on" : "" ?>">
            <a href="/compage/trade_list" class="tab"><span>중고거래 관리</span></a>
        </li -->

            <!-- <li class="<?= $tab_on == "tab7" ? "on" : "" ?>">
                <a href="/compage/interv_list" class="tab"><span>아웃소싱 관리</span></a>
                서브메뉴
                <ul class="tabs is-sub">
                <li>
                    <a href="/compage/interv_list" class="tab">전체</a>
                </li>
                <li>
                    <a href="/compage/interv_list?status=" class="tab">미채용</a>
                </li>
                <li>
                    <a href="/compage/interv_list?status=" class="tab">면접불참</a>
                </li>
                <li>
                    <a href="/compage/interv_list?status=" class="tab">무단결근</a>
                </li>
                <li>
                    <a href="/compage/interv_list?status=" class="tab">퇴사</a>
                </li>
                <li>
                    <a href="/compage/interv_list?status=" class="tab">성실근무</a>
                </li>
            </ul>
            </li> -->
            <?php if ($mem_info["mm_comp_type"] == "OUT" && $mem_info["mm_comp_out_yn"] == "Y") : ?>
                <li class="<?= $tab_on == "tab8" ? "on" : "" ?>">
                    <a href="/compage/out_member" class="tab"><span>아웃소싱관리</span></a>
                    <?php if ($tab_on == "tab8") { ?>
                        <!-- 서브메뉴 -->
                        <ul class="tabs is-sub">
                            <li class="<?= $tab_on2 == "menu1" ? "on" : "" ?>">
                                <a href="/compage/out_member" class="tab">멤버관리</a>
                            </li>
                            <li class="<?= $tab_on2 == "menu2" ? "on" : "" ?>">
                                <a href="/compage/out_promotion" class="tab">공고 관리</a>
                            </li>
                        </ul>
                    <?php } ?>
                </li>
            <?php endif; ?>
            <li class="<?= $tab_on == "tab9" ? "on" : "" ?>">
                <a href="/compage/qna_list" class="tab js-toggle"><span>고객센터</span></a>
                <?php if ($tab_on == "tab9") { ?>
                    <!-- 서브메뉴 -->
                    <ul class="tabs is-sub" style="left:-62px;">
                        <li class="<?= $tab_on2 == "menu1" ? "on" : "" ?>">
                            <a href="/community/notice_list" class="tab">공지사항</a>
                        </li>
                        <li class="<?= $tab_on2 == "menu2" ? "on" : "" ?>">
                            <a href="/community/faq_list" class="tab">FAQ</a>
                        </li>
                        <li class="<?= $tab_on2 == "menu3" ? "on" : "" ?>">
                            <a href="/compage/qna_list" class="tab">문의하기</a>
                        </li>
                        <li class="<?= $tab_on2 == "menu" ? "on" : "" ?>">
                            <a href="/compage/my_report_list" class="tab">나의 신고내역</a>
                        </li>
                    </ul>
                <?php } ?>
            </li>
        </ul>
    </div>
<?php endif; ?>
<script>
    $(".has-sub").find(".js-toggle").hover(function() {
        //$(this).closest("li").find(".is-sub").css("visibility","visibility");
        //$(this).closest("li").find(".is-sub").css("overflow","auto");
    });

    //처리
    $("#btnWrite").click(function() {
        // 사업자 인증 여부 확인
        <?php
        $is_comp_authenticated = ($mem_info != null && !empty($mem_info["mm_comp_num"]));
        ?>
        var isCompAuthenticated = <?= $is_comp_authenticated ? 'true' : 'false' ?>;
        
        // 사업자 인증이 안 된 경우 팝업 표시
        if (!isCompAuthenticated) {
            // 채용공고 등록 버튼을 통해 열렸음을 표시하는 플래그 설정
            $('#dimcont').data('from-recruit-write', true);
            $('#dimwrap, #dimcont').show();
            return;
        }
        
        // 사업자 인증이 완료된 경우에만 공고 등록 진행
        comAjax("/compage_proc/recruit_start", {}, function(ret) {
            if (ret.code == "00") {
                location.href = "/compage/recruit_write";
            } else if (ret.code == "01") {
                openPopup('confirm', {
                    //20220929 수정
                    content: '<p>채용공고등록은 <em>1일 5건 무료</em>입니다<br> 추가로 공고를 등록하시려면<br> 포인트에서 차감됩니다.</p><p class="required-point">채용공고등록: <span>' + ret.data.use_point + 'P</span>차감</p>',
                    secondButton: '유료등록',
                    secondCallback: function() {
                        comAjax("/compage_proc/recruit_start_point", {}, function(ret2) {
                            if (ret2.code == "00") {
                                location.href = "/compage/recruit_write";
                            } else if (ret2.code == "01") {
                                commonAlert("보유하신 포인트가 부족합니다.(보유포인트 : " + ret2.data.mm_point.format() + "P)<br/>포인트 구매후 등록부탁드립니다.", function() {});
                            } else {
                                commonAlert(ret2.msg, function() {});
                                return;
                            }
                        });
                    }
                });
            } else {
                commonAlert(ret.msg, function() {});
                return;
            }
        });
    });
</script>