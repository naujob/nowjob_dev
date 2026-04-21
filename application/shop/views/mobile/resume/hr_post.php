<?php
function fHashSet($txt)
{
    $tags = explode(",", $txt);

    // 각 요소를 # 태그로 변환하여 출력
    $result = "";
    foreach ($tags as $tag) {
        $tag = trim($tag); // 공백 제거
        if (!empty($tag)) { // 빈 값 방지
            $result .= '<p class="tag" style="width:auto;text-align:left;">#' . htmlspecialchars($tag) . '</p>' . "\n";
        }
    }

    return $result;
}
// 주소 정리 함수 - JavaScript 함수(fRemoveBigAdrJS) 사용으로 주석처리
/*
function fRemoveBigAdr($arg)
{

    $parts1 = explode(",", $arg);
    $arg = $parts1[0];

    $replacements = [
        "서울특별시 " => "서울시 ",
        "경기도 " => "",
        "강원특별자치도 " => "",
        "충청남도 " => "",
        "충청북도 " => "",
        "경상남도 " => "",
        "경상북도 " => "",
        "전라남도 " => "",
        "전북특별자치도 " => "",
        "제주특별자치도 " => "",
        "세종특별자치시 " => "세종시 ",
        "광주광역시 " => "광주시 ",
        "인천광역시 " => "인천시 ",
        "울산광역시 " => "울산시 ",
        "부산광역시 " => "부산시 ",
        "대구광역시 " => "대구시 ",
        "대전광역시 " => "대전시 ",
        "경기 " => "",
        "강원 " => "",
        "충남 " => "",
        "충북 " => "",
        "경남 " => "",
        "경북 " => "",
        "전남 " => "",
        "전북 " => "",
        "제주 " => ""
    ];

    foreach ($replacements as $search => $replace) {
        $arg = str_replace($search, $replace, $arg);
    }

    $parts = explode(" ", $arg);
    if (count($parts) >= 3) {
        return $parts[0] . " " . $parts[2];
    } else {
        return $arg;
    }
}
*/
?>
<style>
    .info-table .working-day>li {
        width: 1.8rem;
        height: 1.8rem;
        font-size: 1rem;
        line-height: 1.5rem;
    }

    /* 개인회원이 자신의 이력서를 볼 때 view-footer 버튼 스타일 */






    /* view-footer 내 버튼에 padding, font-size, font-weight 적용 */

    .panel-body .post-list {
        position: relative;
        top: -1.75rem;
    }
</style>
<input type="hidden" name="ru_idx" value="<?= $info["ru_idx"] ?>" />
<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="<?= $top_button ?>"><span class="sr-text">이전으로</span></a>
        <h1 class="title">인재상세정보</h1>
        <span class="rt-group">
            <!-- <a href="/compage/recruit_list" class="btn-action">채용공고작성</a> -->
            <a href="#sharePanel" aria-haspopup="dialog" aria-controls="sharePanel" class="btn-share js-toggle-panel"><span class="sr-text">공유하기</span></a>
        </span>
    </div><!-- //top-bar -->

    <div class="page-content">
        <div class="section view-header">
            <div class="profile">
                <div class="container">
                    <div class="thumb-wrap">
                        <div class="thumb">
                            <!--
                                역할: 이 부분은 이력서의 프로필 사진(썸네일)을 출력하는 코드입니다.
                                getResumeImg($info) 함수를 사용해 해당 인물의 프로필 이미지를 불러오며,
                                만약 이미지가 없을 경우 onError 이벤트를 통해 기본 이미지를 대체하여 보여줍니다.
                            -->
                            <img src="<?= getResumeImg($info) ?>" onError="this.src='/resource/shop/mobile/img/common/noimage3.png'" alt="">
                        </div>
                        <!-- 평점 -->
                        <!-- <p class="star-rating v2"> -->
                        <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                        <!-- <span class="star" style="width:<?= ($mem_info["eval_num"] * 20) ?>%;"></span>
                            <span class="sr-text">평점</span>
                            <span class="sr-text">5</span>
                        </p> -->
                    </div>
                    <div class="profile-detail">
                        <!-- 이름 -->
                        <p class="name"><em class="view-title"><?= isset($info["ru_name"]) && trim($info["ru_name"]) != "" ? htmlspecialchars($info["ru_name"]) : getAstrik($mem_info["mm_name"]) ?></em></p>
                        <div class="meta">
                            <!-- 연락처 -->
                            <?php if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) == "") {
                                $gapbar1 = "display:none;";
                                $gapbar2 = "display:none;";
                            } else if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) != "") {
                                $gapbar1 = "display:none;";
                                $gapbar2 = "display:none;";
                            } else if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) == "") {
                                $gapbar1 = "display:none;";
                                $gapbar2 = "display:none;";
                            } else if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) != "") {
                                $gapbar1 = "display:none;";
                                $gapbar2 = "";
                            } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) == "") {
                                $gapbar1 = "display:none;";
                                $gapbar2 = "display:none;";
                            } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) != "") {
                                $gapbar1 = "";
                                $gapbar2 = "display:none;";
                            } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) == "") {
                                $gapbar1 = "";
                                $gapbar2 = "display:none;";
                            } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) != "") {
                                $gapbar1 = "";
                                $gapbar2 = "";
                            }
                            ?>
                            <p><span><?= $mem_info["mm_nation"] ?></span>
                                <span class="gapbar1" style="<?= $gapbar1 ?>">|</span>
                                <span><?= (getKoreanAge($mem_info["mm_birth"]) == "0" ? "" : getKoreanAge($mem_info["mm_birth"]) . "대") ?></span>
                            </p>
                            <!-- <p><span class="addr-display-profile" data-addr="<?= htmlspecialchars($mem_info["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($mem_info["ad_addr"], ENT_QUOTES, 'UTF-8') ?></span></p> -->
                        </div>
                        <!-- 즐겨찾기 -->
                        <button type="button" data-bk_type="RESUME" data-bk_key="<?= $info["ru_idx"] ?>" data-user_id="<?= $mem_info["mm_id"] ?>" class="btnBookMark btn-view-bookmark js-toggle-bookmark <?= $book_yn == "Y" ? "on" : "" ?>">
                            <span class="sr-text">즐겨찾기 추가</span>
                        </button>
                    </div>
                    <?php if ($this->user->getUserType() != "COMP") { ?>
                        <ul class="profile-record">
                            <li><span class="label">프로필 조회</span><em class="value">0</em></li>
                            <li><span class="label">받은제안</span><em class="value">0</em></li>
                            <li><span class="label">관심인재</span><em class="value">0</em></li>
                        </ul>
                    <?php } else { //comp 
                    ?>
                        <?php

                        // 현재 로그인한 회원의 정보
                        $current_user_info = $this->user->getCurrentUserInfo();
                        $is_sns_login = !empty($current_user_info["mm_join_type"]) && in_array(strtoupper($current_user_info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER"]);
                        // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
                        // PASS로 인증한 회원의 DI 값은 'MC0'로 시작하므로, PASS 본인인증 여부를 확인하기 위해 mm_di가 'MC0'로 시작하는지 확인함.
                        // 또는 SNS 로그인을 한 경우에도 인증된 것으로 간주함.
                        // $is_authenticated 변수는 현재 사용자가 PASS 본인인증(PASS 인증시 mm_di가 'MC0'로 시작) 또는 카카오/애플/네이버 SNS 로그인($is_sns_login) 중 하나라도 만족하면 true가 됩니다. 즉, 인증된 회원인지를 나타냄.
                        $is_authenticated = ($current_user_info["mm_di"] != null && (strpos($current_user_info["mm_di"], "MC0") === 0 || $is_sns_login));

                        if ($current_user_info["mm_comp_num"] == "" || !$is_authenticated) { ?>
                            <ul class="profile-record">
                                <li><span class="label">프로필 조회</span><em class="value">0</em></li>
                                <li><span class="label">받은제안</span><em class="value">0</em></li>
                                <li><span class="label">관심인재</span><em class="value">0</em></li>
                            </ul>
                            <!-- 기업회원 인증 안내 메시지 및 버튼 기능 주석처리 (사용 안 함) -->
                            <!--
                            <div style="position: absolute; top: 128px; left: 0px; width: 100%; height: 173px;">
                                <span style="position: absolute; top: 24px; font-size: 1.8em; font-weight: 600; left: 49px;">기업회원 인증시 <br>확인이 가능합니다.</span>
                                <?php if ($current_user_info["mm_comp_num"] == "") { ?>
                                    <a href="javascript:void(0);" id="btnCompCert" style="position: absolute; top: 65px; right: 70px; background-color: #57CCAF; color: #fff; padding: 5px 10px; border-radius: 6px;">사업자 인증</a>
                                <?php } ?>
                                <?php if (!$is_authenticated) { ?>
                                    <a href="/compage/index?gbn=pass" style="position: absolute; top: 120px; right: 180px; background-color: #57CCAF; color: #fff; padding: 5px 10px; border-radius: 6px;">PASS 인증</a>
                                <?php } ?>
                            </div>
                            -->
                        <?php     } else { ?>
                            <ul class="profile-record">
                                <li><span class="label">프로필 조회</span><em class="value"><?= $info["ru_hit"] ?></em></li>
                                <li><span class="label">받은제안</span><em class="value"><?= $info["req_cnt"] ?></em></li>
                                <li><span class="label">관심인재</span><em class="value"><?= $info["fav_cnt"] ?></em></li>
                            </ul>
                        <?php     } ?>
                    <?php } //comp 
                    ?>
                </div>
            </div>
            <!-- 20220929 수정 -->
            <div class="summary">
                <div class="container">
                    <h3 class="secondary-title">희망근무조건</h3>
                    <table class="info-table">
                        <caption><span class="sr-text">희망근무조건</span></caption>
                        <colgroup>
                            <col>
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td class="label">업무구분</td>
                                <td class="value">
                                    <?= getWorkingImg($info["ru_work"]) ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">지역</td>
                                <td class="value" id="addr-display">
                                    <?php $addr_cnt = 0; ?>
                                    <?php foreach ($addr as $key => $ad) : ?>
                                        <?php if ($ad["ad_addr"] != "") {
                                            if ($addr_cnt > 0) echo ", ";
                                            $addr_cnt++;
                                            // JavaScript 함수로 주소 정리하여 출력
                                            echo '<span class="addr-item" data-addr="' . htmlspecialchars($ad["ad_addr"], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($ad["ad_addr"], ENT_QUOTES, 'UTF-8') . '</span>';
                                        } ?>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">급여형태</td>
                                <td class="value"><?= $info["ru_price_type"] ?></td>
                            </tr>
                            <?php if (strpos($info["ru_price_type"], "월급") !== false && $info["ru_month_price"] != "0") : ?>
                                <tr>
                                    <td class="label">희망연봉</td>
                                    <td class="value"><?= number_format($info["ru_month_price"]) ?>원</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="label">근무형태</td>

                                <td class="value"><?= $info["ru_work_type"] ?></td>
                            </tr>
                            <tr>
                                <td class="label">근무요일</td>
                                <td class="value">
                                    <?php
                                    // 요일무관 체크 여부 확인
                                    $isWeekUnlimited = (isset($info["ru_week_yn"]) && $info["ru_week_yn"] === "Y");
                                    // 저장된 요일 값 확인
                                    $hasSavedWeek = isset($info["ru_week"]) && trim((string) $info["ru_week"]) !== "";
                                    // 저장된 값이 없으면 간편등록 기본값(평일) 사용
                                    $weekDisplaySource = $hasSavedWeek ? $info["ru_week"] : "월,화,수,목,금";
                                    ?>
                                    <?php if ($isWeekUnlimited) : ?>
                                        무관
                                    <?php else : ?>
                                        <ul class="working-day">
                                            <?= getWeekYoil($weekDisplaySource) ?>
                                        </ul>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">근무시작일</td>
                                <td class="value"><?= (get_date($info["ru_work_dt"])) ?>(<?= get_yoil($info["ru_work_dt"]) ?>)</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- 신고하기 -->
                    <!-- <button type="button" aria-haspopup="dialog" aria-controls="reportForm1" class="btn-report js-report"><span class="sr-text">신고하기</span></button> -->
                </div>
            </div>
        </div>

        <!-- 신고하기 양식 -->
        <template id="ReportTemp">
            <div class="alert-msg">
                <p>해당게시글을 신고하시겠습니까?<br> 신고사유를 작성해주세요</p>
                <div class="report-form">
                    <label>
                        <span class="sr-text">신고사유입력</span>
                        <textarea name="br_desc" placeholder="신고사유를 작성해주세요" class="form-textarea js-autoheight"></textarea>
                    </label>
                </div>
            </div>
        </template>
        <!-- //신고하기 양식 -->

        <script>
            /* 신고내용 작성 팝업 */
            window.addEventListener('DOMContentLoaded', function() {
                document.querySelector('.js-report').addEventListener('click', function() {
                    var content = document.querySelector('#ReportTemp').innerHTML;
                    openPopup('modal', {
                        id: 'reportForm',
                        title: '신고하기',
                        content: content,
                        firstButton: '확인',
                        firstCallback: function() {
                            if ($("[name=br_desc]").val() == "") {
                                commonAlert("내용을 입력해주세요.", function() {
                                    $("[name=br_desc]").focus();
                                });
                                return false;
                            }
                            var params = {};
                            params["bd_idx"] = $("[name=ru_idx]").val();
                            params["br_type"] = "resume";
                            params["br_desc"] = $("[name=br_desc]").val();
                            comAjax("/setting_proc/board_report", params, function(ret) {
                                if (ret.code == "00") {
                                    removeElement("#ReportWriter1");
                                    commonAlert("신고 처리 되었습니다.", function() {});
                                } else {
                                    commonAlert("신고 처리시 오류가 발생하였습니다.", function() {});
                                }
                            });
                        }
                    });
                });
            });
        </script>

        <div class="section view-career">
            <div class="container">
                <h3 class="secondary-title">경력소개</h3>
                <table class="info-table">
                    <caption><span class="sr-text">경력</span></caption>
                    <colgroup>
                        <col>
                        <col>
                    </colgroup>
                    <tbody>
                        <tr>
                            <td class="label">최종학력</td>
                            <td class="value"><?= $info["ru_edu"] ?></td>
                        </tr>
                        <tr>
                            <td class="label">경력사항</td>
                            <td class="value">
                                <ul class="period-list">
                                    <?php foreach ($career_list as $row) : ?>
                                        <li>
                                            <span><?= $row["rc_type"] ?></span>
                                            <span><?= $row["rc_name"] ?></span>
                                            <?php
                                            $is_date = false;
                                            $sdate = "";
                                            $edate = "";
                                            $arr_date = explode("~", $row["rc_date"]);
                                            if (count($arr_date) > 1) {
                                                $is_date = true;
                                                $sdate = str_replace(".", "-", trim($arr_date[0])) . "-01";
                                                $edate = str_replace(".", "-", trim($arr_date[1])) . "-01";
                                            }
                                            ?>
                                            <em><?= $row["rc_date"] ?><?= $is_date ? "(" . get_date_diff_txt($sdate, $edate) . ")" : "" ?></em>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">자격증</td>
                            <td class="value">
                                <ul class="period-list">
                                    <?php foreach ($certi_list as $row) : ?>
                                        <li>
                                            <span>자격증</span>
                                            <span><?= $row["rc_name"] ?></span>
                                            <em><?= $row["rc_dt"] ?></em>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="section view-introduce">
            <div class="container">
                <h3 class="secondary-title">자기소개</h3>
                <div class="introduce-wrap">
                    <?= str_replace("\n", "<br/>", $info["ru_desc"]) ?>
                </div>
                <div class="hashtag" style="justify-content: left;">
                    <?= fHashSet($info["ru_my_type"]) ?>
                </div>
            </div>
        </div>
        <!-- div class="section view-attachment">
            <div class="container">
                <h3 class="secondary-title">첨부사진</h3>
                <div class="pic-group">
                    <?php if ($info["ru_img1"] != "") : ?>
                    <div class="pic-card"><img data-src="<?= $info["ru_img1"] ?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if ($info["ru_img2"] != "") : ?>
                    <div class="pic-card"><img data-src="<?= $info["ru_img2"] ?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if ($info["ru_img3"] != "") : ?>
                    <div class="pic-card"><img data-src="<?= $info["ru_img3"] ?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if ($info["ru_img4"] != "") : ?>
                    <div class="pic-card"><img data-src="<?= $info["ru_img4"] ?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if ($info["ru_img5"] != "") : ?>
                    <div class="pic-card"><img data-src="<?= $info["ru_img5"] ?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div -->

        <?php
        // 개인회원이 자신의 이력서를 볼 경우를 판단
        $isOwnResume = ($this->user->getUserType() === "MEM" && isset($info["mm_idx"]) && $info["mm_idx"] == $this->user->getUserIdx());
        ?>
        <div class="view-footer">
            <?php if ($isOwnResume) { ?>
                <!-- 개인회원이 자신의 이력서를 볼 때: 수정 및 광고신청 버튼 -->
                <button type="button" id="btnEditResume" class="btn btn-edit">수정</button>
                <button type="button" id="btnPromotionResume" class="btn btn-promotion">광고신청</button>
            <?php } else { ?>
                <!-- 기업회원이 볼 때: 제안보내기 버튼 -->
                <button type="button" aria-haspopup="dialog" data-ru_idx="<?= $info["ru_idx"] ?>" class="btnRequest btn btn-primary">제안보내기</button>
            <?php } ?>
        </div>

    </div><!-- //page-content -->

</div>

<!-- 기업정보입력 관련 함수 정의 (main_menu.php 대신 직접 정의) -->
<script>
    /**
     * 기업정보 입력 팝업 호출 함수 (모바일용) - 전역 함수
     * 바텀시트 형태로 변경됨
     */
    window.openCompInfoPopup = function() {
        var $panel = document.getElementById('compInfoPanel');

        if (!$panel) {
            commonAlert("기업정보 입력 팝업을 불러올 수 없습니다.<br>페이지를 새로고침 후 다시 시도해주세요.", function() {});
            return;
        }

        // fMemUpdateType 함수가 존재하면 호출 (바텀시트 열기 포함)
        if (typeof fMemUpdateType === 'function') {
            fMemUpdateType('all');
        } else if (typeof openCompInfoPanel === 'function') {
            // fMemUpdateType이 없으면 직접 바텀시트 열기
            openCompInfoPanel();
        }
    };

    /**
     * 기업 인증 확인 함수 (모바일용) - 전역 함수
     * 기업 미인증 시 팝업 표시 및 false 반환
     */
    window.requireCompCertWithGuide = function() {
        // 로그인 여부 확인
        if (!window.NOWJOB_COMP_INFO || !NOWJOB_COMP_INFO.isLogin) {
            openPopup('alert2', {
                content: "로그인 후 이용해주세요.",
                firstButton: '확인',
                removeClose: false,
                firstCallback: function() {
                    location.href = '/member/login';
                }
            });
            return false;
        }

        // 기업회원 확인
        if (NOWJOB_COMP_INFO.userType !== 'COMP') {
            openPopup('alert2', {
                content: "기업회원만 채용공고 작성이 가능합니다.",
                firstButton: '확인',
                removeClose: false
            });
            return false;
        }

        // 기업 인증 확인
        if (!NOWJOB_COMP_INFO.hasCompCert) {
            openPopup('modal', {
                id: 'popupRecruitGuide',
                content: '<div class="popup-guide-msg">기업정보 입력 후 공고등록을 하실 수 있습니다.</div>',
                firstButton: '<div class="btn-comp-info">기업정보입력</div>',
                firstCallback: function() {
                    setTimeout(function() {
                        openCompInfoPopup();
                    }, 150);
                }
            });
            setTimeout(function() {
                $('.layer-popup').last().find('.btn-close-popup').hide();
            }, 0);
            return false;
        }

        return true;
    };
</script>

<!-- 공유하기 팝업 -->
<div id="sharePanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="sr-text">공유하기</h2>
            <button type="button" aria-controls="sharePanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="vendor-links">
                    <!-- 카카오톡 공유 기능 주석처리 -->
                    <!--
                    <a href="#" onclick="shareMessage();return false;" class="share-link1">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_vendor1.svg" alt=""></span>
                        <span>카카오톡</span>
                    </a>
                    -->
                    <a href="#" onclick="CopyUrl();return false;" class="share-link2">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_copy_url.svg" alt=""></span>
                        <span>URL복사</span>
                    </a>
                </div>

                <!-- 카카오 SDK 스크립트 주석처리 -->
                <!-- <script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script> -->
                <script>
                    /* 카카오톡 공유 기능 주석처리 */
                    /*
                    function shareMessage() {
                        if (!Kakao.isInitialized()) {
                            Kakao.init("<?= $kakao_key["인증키"] ?>");
                        }

                        var title = document.querySelector('.view-title').textContent.replace(/\s/g, ''),
                            url = location.href,
                            appUrl = 'scheme://host_path';

                        title = '나우잡 인재 - ' + title;
                        desc = ''; //'나우잡에서 인재 정보를 확인 하세요';
                        imgUrl = 'https://nowjob.co.kr/resource/shop/web/img/nowjob_info.png';

                        setTimeout(function() {
                            Kakao.Share.sendDefault({
                                objectType: 'feed',
                                content: {
                                    title: title, //글제목
                                    description: desc, //글내용
                                    imageUrl: imgUrl, //대표이미지 절대주소
                                    link: {
                                        mobileWebUrl: url,
                                    },
                                },
                                buttons: [{
                                        title: '웹으로 보기',
                                        link: {
                                            mobileWebUrl: url,
                                            webUrl: url
                                        },
                                    },
                                    {
                                        title: '앱으로 보기',
                                        link: {
                                            mobileWebUrl: appUrl,
                                            webUrl: url
                                        },
                                    },
                                ],
                            });
                        }, 100);
                    }
                    */

                    /* URL 복사 */
                    function CopyUrl() {
                        var dummy = document.createElement('input'),
                            url = location.href;

                        document.body.appendChild(dummy);
                        dummy.value = url;
                        dummy.select();
                        document.execCommand('copy');
                        document.body.removeChild(dummy);
                        openPopup('alert', {
                            content: 'URL을 복사했습니다.'
                        });
                    }

                    /* 네이티브 공유 브리지/웹쉐어 처리 */
                    window.addEventListener('DOMContentLoaded', function() {
                        var shareButton = document.querySelector('.btn-share');
                        if (!shareButton) {
                            return;
                        }

                        var osType = "<?= $os_type ?>";
                        var userAgent = navigator.userAgent.toLowerCase();

                        if (!osType || osType === "") {
                            if (/iphone|ipad|ipod/i.test(userAgent)) {
                                osType = "ios";
                            } else if (/android/i.test(userAgent)) {
                                osType = "android";
                            }
                        }

                        var hasAndroidBridge = window.APP_NOWAPK && typeof window.APP_NOWAPK.share === 'function';
                        var hasIosBridge = window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.share;
                        var hasWebShare = navigator && typeof navigator.share === 'function';

                        var nativeShareEnabled = hasAndroidBridge || hasIosBridge || hasWebShare;
                        shareButton.setAttribute('data-native-share', nativeShareEnabled ? 'true' : 'false');

                        if (nativeShareEnabled) {
                            shareButton.classList.remove('js-toggle-panel');
                            shareButton.removeAttribute('aria-controls');
                            shareButton.setAttribute('href', '#');
                        }

                        shareButton.addEventListener('click', function(event) {
                            if (shareButton.getAttribute('data-native-share') !== 'true') {
                                return;
                            }

                            event.preventDefault();
                            event.stopImmediatePropagation();

                            var ruName = <?= json_encode(isset($info["ru_name"]) ? $info["ru_name"] : "", JSON_UNESCAPED_UNICODE) ?>;
                            var titleElement = document.querySelector('.view-title');
                            var title = ruName || (titleElement ? titleElement.textContent.trim() : document.title);
                            var url = window.location.href;

                            var payload = {
                                title: title,
                                url: url

                            };

                            if (hasAndroidBridge) {
                                window.APP_NOWAPK.share(JSON.stringify(payload));
                                return;
                            }

                            if (hasIosBridge) {
                                try {
                                    window.webkit.messageHandlers.share.postMessage(payload);
                                } catch (error) {
                                    window.webkit.messageHandlers.share.postMessage(JSON.stringify(payload));
                                }
                                return;
                            }

                            if (hasWebShare) {
                                navigator.share(payload).catch(function(error) {
                                    if (error && error.name === 'AbortError') {
                                        return;
                                    }
                                    openPopup('alert', {
                                        content: '공유를 실행할 수 없습니다.'
                                    });
                                });
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
<!-- //공유하기 팝업 -->


<!-- 제안 리스트 -->
<div id="selectPanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header" style="background: #57CCAF; color: #fff; padding-top: 1.25rem; margin-bottom: 20px;">
            <!-- 약관제목 -->
            <h2 class="panel-title" style="color:#fff;">진행중인 채용공고</h2>
            <button type="button" aria-controls="selectPanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="terms-wrap">

                    <div class="post-list">
                        <ul class="list">
                            <?php
                            // 변수가 존재하고 배열인지 확인
                            $hasRecruits = isset($recruit_list) && is_array($recruit_list) && count($recruit_list) > 0;
                            $validRecruitCount = 0; // 실제로 출력된 유효한 채용공고 개수

                            if ($hasRecruits) {
                                // 유효한 채용공고 개수 먼저 계산
                                foreach ($recruit_list as $row) {
                                    if (!empty($row["rc_idx"]) && $row["rc_idx"] != "0" && $row["rc_idx"] != 0) {
                                        $validRecruitCount++;
                                    }
                                }
                            }
                            ?>
                            <?php if ($hasRecruits && $validRecruitCount > 0) : ?>
                                <?php foreach ($recruit_list as $row) : ?>
                                    <?php
                                    // rc_idx가 유효한지 확인 (null, 빈 값, 0 체크)
                                    if (empty($row["rc_idx"]) || $row["rc_idx"] == "0" || $row["rc_idx"] == 0) {
                                        continue; // 유효하지 않은 항목은 건너뛰기
                                    }
                                    ?>
                                    <li style="border: 1px solid #ccc; border-radius: 10px; margin-bottom: 10px;">
                                        <div class="selectable-item">
                                            <label class="form-checkbox">
                                                <input type="radio" name="chk_request[]" value="<?= htmlspecialchars($row["rc_idx"], ENT_QUOTES, 'UTF-8') ?>" data-rc_name="<?= htmlspecialchars(isset($row["rc_name"]) ? $row["rc_name"] : '', ENT_QUOTES, 'UTF-8') ?>">
                                                <span class="label"></span>
                                                <span class="sr-text">선택</span>
                                            </label>
                                            <a href="javascript:void(0);" class="angle-link" style="background:none;">
                                                <strong><?= $row["rc_name"] ?></strong>
                                                <span class="date"><?= get_yyyymmdd($row["rc_sdate"]) ?> ~ <?= get_yyyymmdd($row["rc_edate"]) ?></span>
                                            </a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="is-empty_v2">
                                    <p>등록된 채용공고가 없습니다.</p>
                                </div>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- //목록 -->
                </div>
            </div>
        </div>
        <?php if ($validRecruitCount > 0) : ?>
            <div class="panel-footer">
                <div class="sticky-bottom view-footer">
                    <button type="button" id="" aria-controls="terms2Panel" class="btnProcess btn btn-primary js-toggle-panel">제안 보내기</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- //제안 리스트 -->

<script>
    // 페이지 로드 시 주소 정리 함수 적용
    $(document).ready(function() {
        // 주소 정리 함수가 정의되어 있는지 확인
        if (typeof fRemoveBigAdrJS === 'function') {
            // 희망근무조건의 지역 주소 정리
            $('#addr-display .addr-item').each(function() {
                var originalAddr = $(this).attr('data-addr');
                if (originalAddr) {
                    var cleanedAddr = fRemoveBigAdrJS(originalAddr);
                    $(this).text(cleanedAddr);
                }
            });

            // 프로필의 주소 정리
            $('.addr-display-profile').each(function() {
                var originalAddr = $(this).attr('data-addr');
                if (originalAddr) {
                    var cleanedAddr = fRemoveBigAdrJS(originalAddr);
                    $(this).text(cleanedAddr);
                }
            });
        }

        // 네이티브 광고 제어 (스크롤 감지 + 메뉴 opacity 감지)
        if (typeof initNativeAdControl === 'function') {
            initNativeAdControl();
        }
    });

    var check_user_type = "<?= $this->user->getUserType(); ?>";
    $(".btnRequest").click(function() {
        //로그인 여부 확인
        if ($("#is_login").val() != "Y") {
            //commonLogin();

            openPopup('confirm', {
                id: 'loginconfirm',
                content: '<strong>로그인후 이용해주세요!</strong><small></small>',
                firstButton: '로그인',
                secondButton: '회원가입',
                firstCallback: function() {
                    location.href = "/member/login";
                },
                secondCallback: function() {
                    setCookie('non_mem_last_resume', window.location.href, 1);
                    location.href = "/member/join_start";
                },
            });
            $('.btn-close-popup').show();

            return;
        }

        if (check_user_type != "COMP") {
            commonAlert("일반 회원은 제안 할 수 없습니다.", function() {});
            return;
        }

        $("[name='chk_request[]']").prop("checked", false);
        $("#selectPanel").addClass("on");

    });

    $(".btnProcess").click(function() {
        // 체크박스 선택 상태 확인
        var $checkedBoxes = $("[name='chk_request[]']:checked");
        if ($checkedBoxes.length == 0) {
            commonAlert("제안하실 공고를 선택해주세요.", function() {});
            return;
        }

        // 선택된 첫 번째 체크박스 가져오기
        var $checkedRadio = $checkedBoxes.eq(0);

        // 체크박스 요소가 유효한지 확인
        if ($checkedRadio.length == 0) {
            commonAlert("제안하실 공고를 선택해주세요.", function() {});
            return;
        }

        var rc_idx = $checkedRadio.val();
        var ru_title = $checkedRadio.data('rc_name');

        // rc_idx 유효성 검사 (더 엄격한 검증)
        // 빈 문자열, null, undefined, 0, "0" 모두 체크
        if (!rc_idx || rc_idx === "" || rc_idx === undefined || rc_idx === null || rc_idx === "0" || rc_idx === 0 || isNaN(parseInt(rc_idx))) {
            console.error("채용공고 선택 오류 - rc_idx:", rc_idx, "checkedBoxes:", $checkedBoxes.length);
            commonAlert("제안하실 공고를 다시 선택해주세요.", function() {
                // 패널 닫기
                $("#selectPanel").removeClass("on");
            });
            return;
        }
        //오늘 제안여부 및 지원 여부 확인
        var paramsToday = {};
        paramsToday["ru_idx"] = $("[name='ru_idx']").val();
        paramsToday["rc_idx"] = rc_idx;

        comAjax("/resume_proc/todayapply", paramsToday, function(ret) {
            if (ret.code == "00") {
                var mq_idxs = new Array();
                mq_idxs.push($("[name='ru_idx']").val());
                openPopup('confirm', {
                    content: '<em>' + ru_title + '</em> 로<br> 제안을 보내시겠습니까?',
                    firstCallback: function() {

                    },
                    secondCallback: function() {
                        var params = {};
                        params["mq_idxs"] = mq_idxs;
                        params["rc_idx"] = rc_idx;
                        params["mq_type"] = "RESUME";
                        comAjax("/common_proc/member_request", params, function(ret) {
                            //console.log(ret);
                            if (ret.code == "00") {
                                // 알림창 주석처리 - 바로 채팅방으로 이동
                                // commonAlert("선택한 채용공고로 제안하였습니다.", function() {
                                //     $("#selectPanel").removeClass("on");
                                // });

                                $("#selectPanel").removeClass("on");

                                // 제안한 인재 탭으로 이동하고 해당 공고의 채팅방 자동 진입
                                // URL 파라미터로 rc_idx와 ru_idx 모두 전달하여 정확한 채팅방 찾기
                                var ru_idx = $("[name='ru_idx']").val();
                                location.href = "/chat/chat_list3?auto_open_rc_idx=" + rc_idx + "&auto_open_ru_idx=" + ru_idx;
                            } else {
                                commonAlert(ret.msg, function() {
                                    $("#selectPanel").removeClass("on");
                                });
                                return;
                            }
                        });
                    }
                });
                $('.btn-close-popup').hide();
            } else if (ret.code == "01") {
                // 5분 제한 - 확인 클릭 시 채팅 목록으로 이동
                commonAlert("<em>5분 후</em> 다시 제안이 가능합니다.", function() {
                    location.href = "/chat/chat_list3";
                });
                $('.btn-close-popup').hide(); // X 버튼 숨기기
                return;
            } else if (ret.code == "90") {
                // 지원이 이미 있는 경우
                commonAlert(ret.msg, function() {
                    // 알림 후 패널 닫기
                    $("#selectPanel").removeClass("on");
                });
                return;
            } else {
                commonAlert(ret.msg, function() {
                    // 알림 후 패널 닫기
                    $("#selectPanel").removeClass("on");
                });
                return;
            }
        });

    });

    // selectable-item 클릭 시 해당 항목의 radio 버튼 선택
    $(document).on('click', '.selectable-item', function(e) {
        // label이나 input을 직접 클릭한 경우는 기본 동작 유지
        if ($(e.target).is('label') || $(e.target).is('input') || $(e.target).closest('label').length > 0) {
            return;
        }

        // 해당 항목 내의 radio 버튼 찾기
        var $radio = $(this).find('input[type="radio"][name="chk_request[]"]');
        if ($radio.length > 0) {
            // radio 버튼 선택
            $radio.prop('checked', true);
            // radio 버튼의 change 이벤트 트리거 (필요한 경우)
            $radio.trigger('change');
        }
    });

    // 개인회원이 자신의 이력서를 볼 때: 수정 버튼 클릭 이벤트
    $(document).on('click', '#btnEditResume', function() {
        var ruIdx = $("[name=ru_idx]").val();
        if (ruIdx) {
            location.href = "/mypage/resume_view/" + ruIdx;
        }
    });

    // 개인회원이 자신의 이력서를 볼 때: 광고신청 버튼 클릭 이벤트
    $(document).on('click', '#btnPromotionResume', function() {
        location.href = "/mypage/point_promotion_buy";
    });

    // 사업자 인증 버튼 클릭 이벤트 - 기업정보입력 팝업 기능 (사용 안 함 - 주석처리)
    /*
    $(document).on('click', '#btnCompCert', function(e) {
        e.preventDefault();

        // requireCompCertWithGuide 함수 사용 (안내 팝업 먼저 표시)
        function tryRequireCompCert() {
            if (typeof window.requireCompCertWithGuide === 'function') {
                // 이 함수는 기업 인증이 안 되어 있으면 안내 팝업을 띄우고 false 반환
                // 안내 팝업의 "기업정보입력" 버튼을 클릭하면 openCompInfoPopup()이 호출됨
                window.requireCompCertWithGuide();
                return true;
            }
            return false;
        }

        // 즉시 시도
        if (tryRequireCompCert()) {
            return;
        }

        // 함수가 아직 로드되지 않았을 수 있으므로 잠시 대기 후 재시도
        var retryCount = 0;
        var maxRetries = 10;
        var retryInterval = setInterval(function() {
            retryCount++;
            if (tryRequireCompCert() || retryCount >= maxRetries) {
                clearInterval(retryInterval);
                if (retryCount >= maxRetries) {
                    // 최종 실패 시 안내 메시지
                    commonAlert("기업정보 입력 기능을 불러올 수 없습니다.<br>페이지를 새로고침 후 다시 시도해주세요.", function() {});
                }
            }
        }, 100);
    });
    */
</script>