<style>
.angle-link {
    padding: 0;
    background: none;
}

</style>
<div class="page page-mypage">
    <div class="top-bar">
        <a href="/compage/index" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title" style="margin-left: 1.5rem;">채용공고</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <div class="tab-container">
            <ul class="tab-list">
                <li <?= (!isset($_GET['gbn']) || $_GET['gbn'] == '' || $_GET['gbn'] == 'all') ? 'class="on"' : '' ?>>
                    <a href="javascript:_replace('/compage/recruit_list?gbn=all');" class="tab">채용공고 <?= $recruit_count[0]['allcnt'] ?></a>
                </li>
                <li <?= (isset($_GET['gbn']) && $_GET['gbn'] == 'close') ? 'class="on"' : '' ?>>
                    <a href="javascript:_replace('/compage/recruit_list?gbn=close');" class="tab">마감공고 <?= $recruit_count[0]['closecnt'] ?></a>
                </li>
            </ul>
        </div>

        <form id="frm" onsubmit="return false" autocomplete="false">
            <div class="post-list">
                <ul class="list">
                    <?php
                    // 등록된 공고(rc_show_yn == "Y")만 필터링
                    $published_list = array_filter($recruit_list, function ($row) {
                        return isset($row["rc_show_yn"]) && $row["rc_show_yn"] == "Y";
                    });
                    ?>
                    <?php foreach ($published_list as $row) : ?>
                        <li>
                            <div class="selectable-item">
                                <!-- <label class="form-checkbox">
                                    <input type="checkbox" name="rc_show_yn[]" value="<?= $row["rc_idx"] ?>" data-rc_name="<?= $row["rc_name"] ?>" <?= $row["rc_show_yn"] == "Y" ? "checked" : "" ?>>
                                    <span class="label"></span>
                                    <span class="sr-text">선택</span>
                                </label> -->
                                <a href="/compage/recruit_view/<?= $row["rc_idx"] ?>" class="angle-link">
                                    <strong class="recruit-name"><?= $row["rc_name"] ?></strong>
                                    <span class="date"><?= get_yyyymmdd($row["rc_sdate"]) ?> ~ <?= get_yyyymmdd($row["rc_edate"]) ?></span>
                                </a>
                                <!-- <a href="/compage/applicant_list?rcidx=<?= $row["rc_idx"] ?>" class="applicant-link"><img src="/resource/shop/mobile/img/user_gray.png" class="user-icon"> <?= $row["reqcnt"] ?></a> -->
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <?php if (count($published_list) == 0) : ?>
                        <div class="is-empty">
                            <!-- <p>등록된 채용공고가 없습니다.</p> -->
                        </div>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if (count($published_list) > 0) : ?>
                <div class="container">
                    <p class="help-msg">채용공고 등록은 최대 10개까지만 가능합니다.<br> 채용공고기간이 3개월지날시 자동삭제됩니다.</p>
                </div>
            <?php endif; ?>
            <div class="sticky-bottom view-footer">
                <a href="javascript:void(0);" id="btnRecruit" class="btn-action js-add-new">채용공고등록</a>
            </div>
        </form>

        <!-- 컴페이지 바로가기 버튼 -->
        <!-- <a href="/compage/index" class="quick-compage-link" aria-label="컴페이지로 이동"></a> -->
    </div><!-- //page-content -->
</div>


<style>
    /* form#frm 스타일: main.css와 충돌 방지를 위한 구체적인 선택자 사용 */
    .page.page-mypage .page-content form#frm {
        position: relative;
        top: 0vh;
    }

    /* top-bar 내부의 btn-back 클릭 영역을 top-bar 높이만큼 확장 */
    .top-bar {
        position: relative;
        /* absolute 위치 지정을 위한 기준점 설정 */
    }

    .top-bar .btn-back {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 3rem;
        /* 클릭 영역 확장을 위한 충분한 너비 */
        background: url("/resource/shop/mobile/img/ic_back.svg") no-repeat 1.4375rem center;
        /* 아이콘을 원래 위치에 유지 */
        background-size: auto 0.9375rem;
        float: none;
        /* float 제거 */
        box-sizing: border-box;
    }

    /* 채용공고 링크 왼쪽으로 살짝 이동 */
    .angle-link {
        margin-left: -35px;
    }


    /* 지원자 링크 스타일 */
    .applicant-link {
        position: absolute;
        right: 6%;
        width: 32px;
        top: 38%;
        font-size: 0.8rem;
    }

    /* 사용자 아이콘 스타일 */
    .user-icon {
        width: 16px;
    }

    /* footer 버튼 스타일 */
    .page.page-mypage .page-content .sticky-bottom.view-footer #btnRecruit {
        display: block;
        width: 100%;
        padding: 1.3rem 0;
        background-color: #57CCAF;
        color: #fff;
        border: none;
      
        font-size: 1.3rem;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .page.page-mypage .page-content .sticky-bottom.view-footer #btnRecruit:active {
        background-color: #57CCAF;
    }

    /* is-empty 스타일: main.css와 충돌 방지를 위한 구체적인 선택자 사용 */
    .page.page-mypage .page-content .post-list .list .is-empty {
        top: 340px;
    }
</style>

<script>
   

    window.addEventListener('DOMContentLoaded', function() {
      

        // 뒤로가기 버튼 클릭 시 /compage/index로 이동
        $(".btn-back").off('click').on('click', function(e) {
            e.preventDefault();
            location.href = "/compage/index";
            return false;
        });

        //디폴트 설정
        $("#btnDefault").click(function() {
            var rc_idx_list = "";
            if ($("[name='rc_show_yn[]']:checked").length == 0) {
                commonAlert("공개 채용공고로 설정하실 공고를 선택해주세요.", function() {});
                return;
            }

            for (var icnt = 0; icnt < $("[name='rc_show_yn[]']:checked").length; icnt++) {
                if (rc_idx_list == "") {
                    rc_idx_list = $("[name='rc_show_yn[]']:checked").eq(icnt).val();
                } else {
                    rc_idx_list += "," + $("[name='rc_show_yn[]']:checked").eq(icnt).val();
                }
            }

            openPopup('confirm', {
                content: '<p>공개 채용공고로 설정 하시겠습니까?</p>',
                firstButton: '취소',
                secondButton: '설정',
                secondCallback: function() {
                    var params = {};
                    params["rc_idx_list"] = rc_idx_list;
                    comAjax("/compage_proc/recruit_default", params, function(ret) {
                        if (ret.code == "00") {
                            location.reload();
                        } else {
                            commonAlert(ret.msg, function() {});
                            return;
                        }
                    });
                }
            });
        });

        $("#btnRecruit").click(function() {
       

            // 기업 인증 확인 (main_menu.php의 전역 함수 사용)
            // 기업 미인증 시 사업자정보 인증 바텀시트가 자동으로 열림
          

            if (typeof window.requireCompCertWithGuide === 'function') {
             
                if (!window.requireCompCertWithGuide()) {
                
                    return;
                }
            } else {
          
                // 전역 함수가 없는 경우 (안전장치)
                if (!window.NOWJOB_COMP_INFO || !NOWJOB_COMP_INFO.isLogin) {
              
                    openPopup('alert2', {
                        content: "로그인 후 이용해주세요.",
                        firstButton: '확인',
                        removeClose: false,
                        firstCallback: function() {
                            location.href = '/member/login';
                        }
                    });
                    return;
                }
                if (NOWJOB_COMP_INFO.userType !== 'COMP') {
                  
                    openPopup('alert2', {
                        content: "기업회원만 채용공고 작성이 가능합니다.",
                        firstButton: '확인',
                        removeClose: false
                    });
                    return;
                }
                if (!NOWJOB_COMP_INFO.hasCompCert) {
             
                    if (typeof window.openCompInfoPopup === 'function') {
                        window.openCompInfoPopup();
                    }
                    return;
                }
            }

       
            comAjax("/compage_proc/recruit_start", {}, function(ret) {
              

                // 조건문 상세 로깅
                var cond1 = (ret.code == "00" || ret.code == "01");
                var cond2 = ret.data && (ret.data.comAuthStat == "N" || ret.data.passAuthStat == "N");
            

                if ((ret.code == "00" || ret.code == "01") && (ret.data.comAuthStat == "N" || ret.data.passAuthStat == "N")) {
                
                    // 기업 미인증 시 사업자정보 인증 바텀시트 열기
                    if (ret.data.comAuthStat == "N") {
                  
                        if (typeof window.openCompInfoPopup === 'function') {
                            window.openCompInfoPopup();
                        }
                        return;
                    } else if (ret.data.comAuthStat == "Y" && ret.data.passAuthStat == "N") {
                     
                        // PASS 인증 버튼이 삭제되어 이 경우 공고 작성 페이지로 바로 이동
                        location.href = "/compage/recruit_write1_1";
                        return;
                    }

                } else if (ret.code == "00") {
                 
                    //_replace("/compage/recruit_write1_1");
                    location.href = "/compage/recruit_write1_1";
                } else if (ret.code == "01") {
                
                    openPopup('confirm', {
                        //20220929 수정
                        content: '<p>채용공고등록은 <em>1일 2건 무료</em>입니다<br> 추가로 공고를 등록하시려면<br> 포인트에서 차감됩니다.</p><p class="required-point">채용공고등록: <span>' + ret.data.use_point + 'P</span>차감</p>',
                        secondButton: '유료등록',
                        secondCallback: function() {
                            comAjax("/compage_proc/recruit_start_point", {}, function(ret2) {
                                if (ret2.code == "00") {
                                    //_replace("/compage/recruit_write1_1");
                                    location.href = "/compage/recruit_write1_1";
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

        // 바닐라 JS 방식으로도 이벤트 등록
      
        var btnRecruit = document.getElementById('btnRecruit');
        if (btnRecruit) {
         
            btnRecruit.addEventListener('click', function(e) {
             
                e.preventDefault();
                // jQuery 클릭 이벤트 수동 트리거
                if (typeof jQuery !== 'undefined') {
                
                    jQuery('#btnRecruit').click();
                }
            });
        } else {
           
        }


        window.addEventListener('popstate', function(e) {
            // recruit_list에서 뒤로가기 → compage/index로 이동
            if (!e.state || e.state.page === 'recruit_list') {
                location.href = "/compage/index";
            }
        });
    });

</script>