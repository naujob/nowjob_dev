<?php
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_template.php');

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
$isPopup = isset($is_popup) ? $is_popup : false;

// 상태 계산 블록 (블러 처리를 위해 파일 최상단에서 먼저 정의해야 함)
$isLogin = $this->user->isLogin();
$userType = $isLogin ? $this->user->getUserType() : null;
$isOwnResume = ($userType === "MEM" && isset($info["mm_idx"]) && (int)$info["mm_idx"] === (int)$this->user->getUserIdx());
$isPreview = isset($_GET['preview']) && $_GET['preview'] == '1';

// 관리 아이콘(수정/삭제/광고) 차단 메시지
$restrict_manage_msg = null; // null = 정상 동작(개인회원 자신의 이력서)
if (!$isLogin) {
    $restrict_manage_msg = '__LOGIN_GUIDE__';
} elseif ($userType === 'COMP') {
    $restrict_manage_msg = '개인회원만 가능한 기능입니다.';
} elseif ($userType === 'MEM' && !$isOwnResume) {
    // 개인회원이지만 자신의 이력서가 아닌 경우
    $restrict_manage_msg = '본인의 이력서만 가능한 기능입니다.';
}

// 액션 아이콘(제안/채팅/전화) 차단 메시지
$restrict_action_msg = null; // null = 정상 동작(기업회원)
if (!$isLogin) {
    $restrict_action_msg = '__LOGIN_GUIDE__';
} elseif ($userType === 'MEM') {
    $restrict_action_msg = '기업회원만 가능한 기능입니다.';
}
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
        
    }

    
    .worktype em:not(:last-child) {
        padding-right: 8px;
    }


    .worktype em.wtype2 {
        
        padding-left: 2px;
    }

    .worktype em {
        position: relative;
        display: inline-block;
        font-style: normal;
    }

    .worktype em:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 0;
        right: 2px;
        bottom: 0;
        width: 1px;
        height: 0.5em;
        margin: auto;
        border-right: 1px solid #777;
    }

    .worktype em.wtype3 {
      
        padding-left: 2px;
    }

    .info-table .pricetype-row .pricetype {

        right: 0;
        bottom: 0;
        
        font-weight: 600;
        letter-spacing: 0;
        color: #1b9d7d;

        flex-direction: column;
        align-items: flex-end;

    }

    .info-table .pricetype-row .pricetype em {
        font-style: normal;

    }

    .info-table .pricetype-row .pricetype em:not(:last-child) {
        padding-right: 3px;
        color: #1b9d7d;

    }

    .info-table .pricetype-row .pricetype em:last-child {
        /* padding-left: 3px; */
        color: #1b9d7d;
    }


    .info-table .pricetype-row .pricetype em:not(:last-child)::after {
        content: "";
        position: relative;
        top: 0;
        right: 0;
        bottom: 0;
        width: 1px;
        height: 0.5em;
        margin: auto;
        border-right: 1px solid #777777;
        display: inline-block;
        padding-left: 5px;
    }

    .view-footer[data-request-state="none"] #btnGoChat {
        display: none;
    }
    .profile-record{
        margin-top: 33px;
    }
    /* .addr-item {
        padding-left: 1px;
    } */

    /* 온보딩 튜토리얼 팝업2 (인재상세페이지) */
    #tutorial-popup2 {
        position: fixed; inset: 0; background: rgba(0,0,0,0.9);
        z-index: 1400; display: none; align-items: center; justify-content: center;
    }
    .tutorial-skip-btn2 {
        position: absolute; top: 30px; right: 20px; color: #fff;
        font-size: 18px; font-weight: 700; cursor: pointer; opacity: 0; z-index: 1401;
    }
  

    /* 인재정보 카드 (hr_post.png) — 화면 상단 중앙 */
    .tutorial-jobpost-wrapper {
        position: absolute; top: 80px; left: 50%; transform: translateX(-50%);
        width: 95%; display: flex; flex-direction: column;
        align-items: flex-start; gap: 10px;
    }
    .tutorial-jobpost-img { width: 100%; height: auto; display: block; opacity: 0; }
    .tutorial-jobpost-text {
        color: #fff; font-size: 18px; font-weight: 700; line-height: 1.4; margin: 0; opacity: 0;
        text-align: left;
    }
    .tutorial-jobpost-text .highlight-text-arrow { font-weight: 700; font-size: 20px; color: #1B9D7D; }

    /* 전화하기 (call.png) — 화면 우측 하단 */
    .tutorial-call-wrapper {
        position: absolute; bottom: 40px; right: 10px;
        display: flex; flex-direction: column; align-items: center; gap: 8px;
    }
    .tutorial-call-img { 
    width: 110px; 
    height: 110px; 
    display: block; 
    opacity: 0; 
    right: -50px;
    position: relative;
    
    }
    .tutorial-call-text {
        color: #fff; font-size: 18px; font-weight: 700; line-height: 1.4; margin: 0;
        text-align: right; white-space: nowrap; opacity: 0;
    }
    .tutorial-call-text .highlight-text-arrow { font-weight: 700; font-size: 20px; color: #1B9D7D; }

    /* 제안하기 (propose.png) — call과 동일 위치 (페이지2 전용) */
    .tutorial-propose-wrapper {
        position: absolute; bottom: 40px; right: 10px;
        display: flex; flex-direction: column; align-items: center; gap: 8px;
    }
    .tutorial-propose-img { 
        width: 110px; 
        height: 110px; 
        display: block; 
        opacity: 0; 
        right: -69px;
        position: relative;

}
    .tutorial-propose-text {
        color: #fff; font-size: 18px; font-weight: 700; line-height: 1.4; margin: 0;
        text-align: right; white-space: nowrap; opacity: 0;
    }
    .tutorial-propose-text .highlight-text-arrow { font-weight: 700; font-size: 20px; color: #1B9D7D; }
</style>

<input type="hidden" name="ru_idx" value="<?= $info["ru_idx"] ?>" />
<div class="post page page-view">
    <div class="top-bar">
        <a href="#" class="btn-back" onclick="<?= $isPopup ? 'closeHrPostPopup(event)' : 'goBackToEdit(event)' ?>"><span class="sr-text">이전으로</span></a>
        <h1 class="title">인재상세정보</h1>
        <span class="rt-group">
            <!-- <a href="/compage/recruit_list" class="btn-action">채용공고작성</a> -->
            <a href="#sharePanel" aria-haspopup="dialog" aria-controls="sharePanel" class="btn-share js-toggle-panel"><span class="sr-text">공유하기</span></a>
        </span>
    </div><!-- //top-bar -->

    <!-- 앱 딥링크 -->
    <script src="/resource/shop/mobile/js/app-deeplink.js"></script>

    <script>
        // 인재 목록 페이지에서 돌아올 때 하이라이트 처리
        var ruIdx = '<?= $info["ru_idx"] ?>';
        var customShareUrl = location.origin + '/resume/hr_post/' + ruIdx + '?shared=true';
        sessionStorage.setItem('resume_highlight_ru_idx', ruIdx);
        sessionStorage.setItem('resume_came_from_hr_post', 'true');

        // 뒤로가기 버튼 처리
        function goBackToEdit(e) {
            e.preventDefault();

            // preview 모드일 때 작성 페이지로 이동 (기존 이력서 계속 수정)
            var isPreviewMode = <?= json_encode($isPreview) ?>;
            if (isPreviewMode) {
                window.location.href = '/mypage/resume_write1/' + ruIdx;
            } else {
                // 일반 조회 모드: 이력서 목록으로 돌아가기 (무한 루프 방지)
                location.href = '/resume/hr_list';
            }
        }
    </script>

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
                    <div class="profile-detail" style="margin: 19px 2.375rem 0px 7.75rem;">
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
                    <ul class="profile-record">
                        <li><span class="label">프로필 조회</span><em class="value"><?= $info["ru_hit"] ?></em></li>
                        <li><span class="label">받은제안</span><em class="value"><?= $info["req_cnt"] ?></em></li>
                        <li><span class="label">관심인재</span><em class="value"><?= $info["fav_cnt"] ?></em></li>
                    </ul>
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
                            <tr class="pricetype-row">
                                <td class="label">급여형태</td>
                                <td class="value pricetype"><?= getPriceType($info["ru_price_type"]) ?></td>
                            </tr>
                            <?php if (strpos($info["ru_price_type"], "월급") !== false && $info["ru_month_price"] != "0") : ?>
                                <tr>
                                    <td class="label">희망연봉</td>
                                    <td class="value"><?= number_format($info["ru_month_price"]) ?>원</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="label">근무형태</td>

                                <td class="value"><?= getWorkTypeWithColor($info["ru_work_type"]) ?></td>
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
                            <!-- <tr>
                                <td class="label">근무시작일</td>
                                <td class="value"><?= (get_date($info["ru_work_dt"])) ?>(<?= get_yoil($info["ru_work_dt"]) ?>)</td>
                            </tr> -->
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
            /* 신고내용 작성 팝업 - 동적 로드 시에도 즉시 실행 */
            (function() {
                document.querySelector('.js-report').addEventListener('click', function() {
                    var content = document.querySelector('#ReportTemp').innerHTML;
                    $('#hrPostPopup').css('z-index', '200');
                    var restoreZIndex = function() {
                        $('#hrPostPopup').css('z-index', '1000');
                    };
                    setTimeout(function() {
                        var $popup = $('.layer-popup.in').last();
                        if ($popup.length) {
                            $popup.find('.btn-close-popup').one('click', restoreZIndex);
                            $popup.one('click.popupClose', function(e) {
                                if (e.target === this) {
                                    restoreZIndex();
                                }
                            });
                        }
                    }, 50);
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
            })();  // IIFE 종료
        </script>

        <div class="section view-career" <?php echo (!$isOwnResume && $userType !== 'COMP') ? 'style="filter: blur(3px);"' : ''; ?>>
            <div class="container">
                <h3 class="secondary-title">경력소개</h3>
                <table class="info-table">
                    <caption><span class="sr-text">경력</span></caption>
                    <colgroup>
                        <col>
                        <col>
                    </colgroup>
                    <tbody>
                        <!-- <tr>
                            <td class="label">최종학력</td>
                            <td class="value"><?= $info["ru_edu"] ?></td>
                        </tr> -->
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
        <div class="section view-introduce" <?php echo (!$isOwnResume && $userType !== 'COMP') ? 'style="filter: blur(3px);"' : ''; ?>>
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
        // 상태 변수는 파일 최상단(68줄 이후)에서 이미 정의됨
        ?>
        <!-- view-footer 플로팅 액션 버튼 그룹으로 대체됨 (라인 497~524) -->
        <!-- <div class="view-footer" data-request-state="<?= isset($already_requested) && $already_requested ? 'sent' : 'none' ?>">
            <?php if ($isOwnResume) { ?>

                <button type="button" id="btnEditResume" class="btn btn-edit">수정</button>
                <button type="button" id="btnPromotionResume" class="btn btn-promotion">광고신청</button>
            <?php } else { ?>

                <button type="button" aria-haspopup="dialog" data-ru_idx="<?= $info["ru_idx"] ?>" class="btnRequest btn btn-primary">제안보내기</button>
            <?php } ?>
        </div> -->

        <!-- 미리보기 모드 footer 버튼 -->
        <?php if ($isPreview) : ?>
        <div class="view-footer">
            <button type="button" class="btn btn-secondary" onclick="location.href='/mypage/resume_write1/<?= $info["ru_idx"] ?>?from=preview'">수정하기</button>
            <button type="button" class="btn btn-primary" id="btnPreviewRegister">등록하기</button>
        </div>
        <?php endif; ?>

        <!-- 플로팅 액션 버튼 그룹 -->
        <?php if (!$isPreview) : ?>
        <div id="floating-actions" data-state="open">

            <!-- 로그인 버튼 (비로그인 시에만 노출) -->
           <?php if (!$isLogin) : ?>
                <a href="/member/login" class="floating-action-item" data-action="login" title="로그인">
                    <img src="/resource/shop/mobile/img/login-post.png" alt="로그인">
                    <span class="action-label">로그인</span>
                </a>
            <?php endif; ?>

            <!-- 수정 - 본인이력서에서만 표시 -->
            <?php if ($isOwnResume) : ?>
                <a href="/mypage/resume_write1/<?= $info["ru_idx"] ?>" class="floating-action-item" data-action="edit" title="수정">
                    <img src="/resource/shop/mobile/img/modify.png" alt="수정">
                    <span class="action-label">수정</span>
                </a>
            <?php endif; ?>

            <!-- 삭제 - 본인이력서에서만 표시 -->
            <?php if ($isOwnResume) : ?>
                <a href="#" class="floating-action-item" id="btnDeleteResume" data-action="delete" title="삭제">
                    <img src="/resource/shop/mobile/img/del.png" alt="삭제">
                    <span class="action-label">삭제</span>
                </a>
            <?php endif; ?>

            <!-- 광고 - 본인이력서에서만 표시 -->
            <?php if ($isOwnResume) : ?>
                <a href="/mypage/point_promotion_buy/<?= $info["ru_idx"] ?>" class="floating-action-item" data-action="promote" title="광고신청">
                    <img src="/resource/shop/mobile/img/ad-post.png" alt="광고">
                    <span class="action-label">광고</span>
                </a>
            <?php endif; ?>

            <!-- 제안하기 -->
            <?php if ($isOwnResume) : ?>
                <!-- 자신의 이력서: 기업회원만 가능 -->
                <a href="#" class="floating-action-item" onclick="commonAlert('기업회원만 가능한 기능입니다.', function(){}); return false;" data-action="propose" title="제안">
                    <img src="/resource/shop/mobile/img/propose.png" alt="제안">
                    <span class="action-label">제안하기</span>
                </a>
            <?php elseif ($restrict_action_msg === '__LOGIN_GUIDE__') : ?>
                <!-- 로그인 안내 -->
                <a href="#" class="floating-action-item" onclick="window.showLoginGuideFromPopup(); return false;" data-action="propose" title="제안">
                    <img src="/resource/shop/mobile/img/propose.png" alt="제안">
                    <span class="action-label">제안하기</span>
                </a>
            <?php elseif ($restrict_action_msg !== null) : ?>
                <!-- 차단 -->
                <a href="#" class="floating-action-item" onclick="commonAlert('<?= $restrict_action_msg ?>', function(){}); return false;" data-action="propose" title="제안">
                    <img src="/resource/shop/mobile/img/propose.png" alt="제안">
                    <span class="action-label">제안하기</span>
                </a>
            <?php else : ?>
                <!-- 정상 동작: 기업회원 -->
                <a href="#" id="btnPropose" class="floating-action-item btnRequest" data-action="propose" title="제안">
                    <img src="/resource/shop/mobile/img/propose.png" alt="제안">
                    <span class="action-label">제안하기</span>
                </a>
            <?php endif; ?>

            <!-- 채팅하기 -->
            <?php if ($isOwnResume) : ?>
                <!-- 자신의 이력서: 기업회원만 가능 -->
                <a href="#" class="floating-action-item" onclick="commonAlert('기업회원만 가능한 기능입니다.', function(){}); return false;" data-action="chat" title="채팅">
                    <img src="/resource/shop/mobile/img/main_menu/chat_v2.png" alt="채팅">
                    <span class="action-label">채팅하기</span>
                </a>
            <?php elseif ($restrict_action_msg === '__LOGIN_GUIDE__') : ?>
                <!-- 로그인 안내 -->
                <a href="#" class="floating-action-item" onclick="window.showLoginGuideFromPopup(); return false;" data-action="chat" title="채팅">
                    <img src="/resource/shop/mobile/img/main_menu/chat_v2.png" alt="채팅">
                    <span class="action-label">채팅하기</span>
                </a>
            <?php elseif ($restrict_action_msg !== null) : ?>
                <!-- 차단 -->
                <a href="#" class="floating-action-item" onclick="commonAlert('<?= $restrict_action_msg ?>', function(){}); return false;" data-action="chat" title="채팅">
                    <img src="/resource/shop/mobile/img/main_menu/chat_v2.png" alt="채팅">
                    <span class="action-label">채팅하기</span>
                </a>
            <?php else : ?>
                <!-- 정상 동작: 기업회원 -->
                <a href="#" id="btnDirectChat" class="floating-action-item" data-action="chat" title="채팅">
                    <img src="/resource/shop/mobile/img/main_menu/chat_v2.png" alt="채팅">
                    <span class="action-label">채팅하기</span>
                </a>
            <?php endif; ?>

            <!-- 전화하기 -->
            <?php if ($isOwnResume) : ?>
                <!-- 자신의 이력서: 기업회원만 가능 -->
                <a href="#" class="floating-action-item" onclick="commonAlert('기업회원만 가능한 기능입니다.', function(){}); return false;" data-action="call" title="전화">
                    <img src="/resource/shop/mobile/img/call-post.png" alt="전화">
                    <span class="action-label">전화하기</span>
                </a>
            <?php elseif ($restrict_action_msg === '__LOGIN_GUIDE__') : ?>
                <!-- 로그인 안내 -->
                <a href="#" class="floating-action-item" onclick="window.showLoginGuideFromPopup(); return false;" data-action="call" title="전화">
                    <img src="/resource/shop/mobile/img/call-post.png" alt="전화">
                    <span class="action-label">전화하기</span>
                </a>
            <?php elseif ($restrict_action_msg !== null) : ?>
                <!-- 차단 -->
                <a href="#" class="floating-action-item" onclick="commonAlert('<?= $restrict_action_msg ?>', function(){}); return false;" data-action="call" title="전화">
                    <img src="/resource/shop/mobile/img/call-post.png" alt="전화">
                    <span class="action-label">전화하기</span>
                </a>
            <?php else : ?>
                <!-- 정상 동작: 기업회원 (050 가상번호로 통화) -->
                <a href="#" id="btnGoChat" class="floating-action-item btnGoChat" data-action="call" title="전화" data-rm_idx="<?= isset($chat_rm_idx) ? htmlspecialchars($chat_rm_idx, ENT_QUOTES, 'UTF-8') : '' ?>">
                    <img src="/resource/shop/mobile/img/call-post.png" alt="전화">
                    <span class="action-label">전화하기</span>
                </a>
            <?php endif; ?>
        </div><!-- //#floating-actions -->
        <?php endif; ?>

        <!-- 온보딩 튜토리얼 팝업 (인재상세페이지) -->
        <div id="tutorial-popup2" style="display:none;">
            <span class="tutorial-skip-btn2" onclick="window.closeJobPostTutorial();">건너뛰기</span>
           

            <!-- 페이지 1: 인재정보 + 전화하기 -->
            <div class="tutorial-jobpost-wrapper jp-page-1">
                <p class="tutorial-jobpost-text"><span class="highlight-text-arrow">이력서</span> 상세 희망 근무조건<br>인재가 원하는 근무조건을 바로 확인할 수 있어요</p>
                <img src="/resource/shop/mobile/img/tutorial/hr_post.png" class="tutorial-jobpost-img" alt="인재정보">
            </div>
            <div class="tutorial-call-wrapper jp-page-1">
                <p class="tutorial-call-text">인재와 다이렉트 연결<br>개인정보 보호까지 나우잡 <span class="highlight-text-arrow">안심콜</span></p>
                <img src="/resource/shop/mobile/img/tutorial/call.png" class="tutorial-call-img" alt="전화하기">
            </div>

            <!-- 페이지 2: 제안하기 -->
            <div class="tutorial-propose-wrapper jp-page-2" style="display:none;">
                <p class="tutorial-propose-text">나우잡 채팅으로 시작하는 구인구직<br>일손이 필요할땐 인재정보에, <span class="highlight-text-arrow">제안하기</span></p>
                <img src="/resource/shop/mobile/img/tutorial/propose.png" class="tutorial-propose-img" alt="제안하기">
            </div>
        </div>

        <!-- 개인회원 본인 이력서 삭제 바텀시트 -->
        <?php if (!$isPreview && $isOwnResume) : ?>
            <?php renderSelectBottomSheet(
                'deleteResumeSheet',
                '이력서 삭제',
                '<p>삭제된 이력서는 복구할 수 없습니다.<br>완전 삭제하시겠습니까?</p>',
                '취소', 'btnDeleteCancel', "closePanel(document.getElementById('deleteResumeSheet'));",
                '삭제', 'btnDeleteConfirm', 'confirmDeleteResume();'
            ); ?>
        <?php endif; ?>

    </div><!-- //page-content -->

<?php if (!$isPopup) { ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/app_intercept_layer.php'); ?>
<?php } ?>

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
            $('#hrPostPopup').css('z-index', '200');
            var restoreZIndex1 = function() { $('#hrPostPopup').css('z-index', '1000'); };
            setTimeout(function() {
                var $popup = $('.layer-popup.in').last();
                if ($popup.length) {
                    $popup.find('.btn-close-popup').one('click', restoreZIndex1);
                    $popup.one('click.popupClose', function(e) { if (e.target === this) restoreZIndex1(); });
                }
            }, 50);
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
            $('#hrPostPopup').css('z-index', '200');
            var restoreZIndex2 = function() { $('#hrPostPopup').css('z-index', '1000'); };
            setTimeout(function() {
                var $popup = $('.layer-popup.in').last();
                if ($popup.length) {
                    $popup.find('.btn-close-popup').one('click', restoreZIndex2);
                    $popup.one('click.popupClose', function(e) { if (e.target === this) restoreZIndex2(); });
                }
            }, 50);
            openPopup('alert2', {
                content: "기업회원만 채용공고 작성이 가능합니다.",
                firstButton: '확인',
                removeClose: false
            });
            return false;
        }

        // 기업 인증 확인
        if (!NOWJOB_COMP_INFO.hasCompCert) {
            $('#hrPostPopup').css('z-index', '200');
            var restoreZIndex3 = function() { $('#hrPostPopup').css('z-index', '1000'); };
            setTimeout(function() {
                var $popup = $('.layer-popup.in').last();
                if ($popup.length) {
                    $popup.find('.btn-close-popup').one('click', restoreZIndex3);
                    $popup.one('click.popupClose', function(e) { if (e.target === this) restoreZIndex3(); });
                }
            }, 50);
            openPopup('modal', {
                id: 'popupRecruitGuide',
                content: '<div class="popup-guide-msg">기업정보 입력 후 공고등록을 하실 수 있습니다.</div>',
                firstButton: '<div class="btn-comp-info">기업정보입력</div>',
                firstCallback: function() {
                    restoreZIndex3();
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
                    <a href="#" onclick="shareMessage();return false;" class="share-link1">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_vendor1.svg" alt=""></span>
                        <span>카카오톡</span>
                    </a>
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
                        imgUrl = '/resource/shop/web/img/nowjob_info.png';

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

                    var shareOsType = "<?= $os_type ?>";
                </script>
            </div>
        </div>
    </div>
</div>
<!-- //공유하기 팝업 -->

<script src="/resource/shop/mobile/js/share-panel.js"></script>

<!-- 제안 리스트 -->
<div id="selectPanel_hrPost" class="layer-panel card-type" tabindex="-1" data-disable-form-reset="true">
    <div class="panel-container">
        <div class="panel-header" style="background: #57CCAF; color: #fff; padding-top: 1.25rem; margin-bottom: 20px;">
            <!-- 약관제목 -->
            <h2 class="panel-title" style="color:#fff;">진행중인 채용공고</h2>
            <button type="button" aria-controls="selectPanel_hrPost" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body" style="padding: 2rem 1.375rem 1.5rem;">
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
            <div class="panel-footer" style="position: relative; margin-top: 60px; ">
                <div class="sticky-bottom" style="width:100%;">
                    <button type="button" style="width:100%;" aria-controls="selectPanel_hrPost" class="btnProcess btn btn-primary js-toggle-panel bottom-sheet-action">보내기</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- //제안 리스트 -->

<script>
    // 페이지 로드 시 주소 정리 함수 적용 - 동적 로드 시에도 즉시 실행
    (function() {
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
    })();  // IIFE 종료

    var check_user_type = "<?= $this->user->getUserType(); ?>";
    // 동적 로드 후에도 바로 이벤트가 등록되도록 document.on 사용
    $(document).off('click', '#btnPropose').on('click', '#btnPropose', function() {
        // 인재정보 제안하기 분석 (운영 환경에서만)
        var isProdEnvironment = !window.location.pathname.includes('/dev_nowapk');
        if (isProdEnvironment && typeof my_analytics !== 'undefined') {
            my_analytics.goal('resume_recruit');
        }

        //로그인 여부 확인
        if ($("#is_login").val() != "Y") {
            //commonLogin();

            $('#hrPostPopup').css('z-index', '200');
            var restoreZIndex4 = function() { $('#hrPostPopup').css('z-index', '1000'); };
            setTimeout(function() {
                var $popup = $('.layer-popup.in').last();
                if ($popup.length) {
                    $popup.find('.btn-close-popup').one('click', restoreZIndex4);
                    $popup.one('click.popupClose', function(e) { if (e.target === this) restoreZIndex4(); });
                }
            }, 50);

            openPopup('confirm', {
                id: 'loginconfirm',
                content: '<strong>로그인후 이용해주세요!</strong><small></small>',
                firstButton: '로그인',
                secondButton: '회원가입',
                firstCallback: function() {
                    restoreZIndex4();
                    location.href = "/member/login";
                },
                secondCallback: function() {
                    restoreZIndex4();
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
        currentActionMode = 'propose';
        $("#selectPanel_hrPost").addClass("on");

    });

    // 기업회원 다이렉트 채팅 버튼 클릭 이벤트
    $(document).off('click', '#btnDirectChat').on('click', '#btnDirectChat', function(e) {
        e.preventDefault();
        var $btn = $(this);
        if ($btn.attr('data-loading') === 'true') return;
        $btn.attr('data-loading', 'true');

        if ($("#is_login").val() != "Y") {
            // 기존 로그인 안내 팝업 로직 그대로 유지 ($btn.attr('data-loading','false') 포함)
            $btn.attr('data-loading', 'false');
            $('#hrPostPopup').css('z-index', '200');
            var restoreZIndex = function() { $('#hrPostPopup').css('z-index', '1000'); };
            setTimeout(function() {
                var $popup = $('.layer-popup.in').last();
                if ($popup.length) {
                    $popup.find('.btn-close-popup').one('click', restoreZIndex);
                    $popup.one('click.popupClose', function(e) { if (e.target === this) restoreZIndex(); });
                }
            }, 50);

            openPopup('confirm', {
                id: 'loginconfirm',
                content: '<strong>로그인후 이용해주세요!</strong><small></small>',
                firstButton: '로그인',
                secondButton: '회원가입',
                firstCallback: function() {
                    restoreZIndex();
                    location.href = "/member/login";
                },
                secondCallback: function() {
                    restoreZIndex();
                    setCookie('non_mem_last_resume', window.location.href, 1);
                    location.href = "/member/join_start";
                },
            });
            $('.btn-close-popup').show();
            return;
        }
        if (check_user_type != "COMP") {
            $btn.attr('data-loading', 'false');
            commonAlert("기업 회원만 이용 가능합니다.", function() {});
            return;
        }

        // 다이렉트 채팅: 실제 채용공고 선택 없이 바로 시작 (백엔드가 전용 더미 공고/이력서를 자동 준비)
        currentActionMode = 'direct_chat';
        submitMemberRequest("", "");
    });

    // 이미 진행 중인 채팅방으로 안내 후 이동 (auto_send 없이 이동하므로 첫 메시지 자동 전송 없음)
    var goExistingChatRoom = function(msg, rm_idx) {
        $("#btnDirectChat").attr('data-loading', 'false');
        commonAlert(msg, function() {
            $("#selectPanel_hrPost").removeClass("on");
            location.href = "/chat/chat_view?rm_idx=" + rm_idx + "&backUrl=" + encodeURIComponent('/chat/index_comp');
        });
        $('.btn-close-popup').hide(); // X 버튼 숨기기 (확인으로만 이동)
    };

    // 나갔다가 들어온 이력이 전혀 없는 중복 제안: 안내 없이 바로 기존 채팅방으로 이동 (메시지 전송 없음)
    var goExistingChatRoomSilent = function(rm_idx) {
        $("#selectPanel_hrPost").removeClass("on");
        location.href = "/chat/chat_view?rm_idx=" + rm_idx + "&backUrl=" + encodeURIComponent('/chat/index_comp');
    };

    // 같은 공고로 이미 제안한 인재: "다시 보내겠습니까?" 확인 후 기존 채팅방으로 이동하며 제안 메시지 재전송
    // (제안 기록 추가/포인트 차감 없이 채팅방에 제안 메시지만 다시 보냄)
    var confirmResendProposal = function(rm_idx, rc_idx) {
        // hrPostPopup 위에 confirm이 보이도록 z-index 임시 조정
        $('#hrPostPopup').css('z-index', '200');
        var restoreZIndex = function() {
            $('#hrPostPopup').css('z-index', '1000');
        };

        openPopup('confirm', {
            content: '이미 제안을 보냈습니다.<br>다시 보내겠습니까?',
            firstCallback: function() {
                restoreZIndex();
                $('.btn-close-popup').show();
            },
            secondCallback: function() {
                restoreZIndex();
                $("#selectPanel_hrPost").removeClass("on");
                // auto_send=true + mq_type=RESUME → chat_view에서 제안 메시지(열어보기 카드) 자동 전송
                location.href = "/chat/chat_view?rm_idx=" + rm_idx + "&auto_send=true&rc_idx=" + rc_idx + "&mq_type=RESUME&ru_idx=" + $("[name='ru_idx']").val() + "&backUrl=" + encodeURIComponent('/chat/index_comp');
            }
        });
        $('.btn-close-popup').hide();
    };

    // submitMemberRequest 함수 정의 (제안/다이렉트채팅 공통 처리)
    var submitMemberRequest = function(rc_idx, ru_title) {
        // 다이렉트 채팅은 시간 제한 없이 바로 진행 (지원/제안 기록 없는 순수 채팅)
        if (currentActionMode === 'direct_chat') {
            var params = {};
            var mq_idxs = new Array();
            mq_idxs.push($("[name='ru_idx']").val());
            params["mq_idxs"] = mq_idxs;
            params["rc_idx"] = rc_idx;
            params["mq_type"] = "RESUME";
            params["is_direct_chat"] = "Y";

            comAjax("/common_proc/member_request", params, function(ret) {
                if (ret.code == "00") {
                    if (ret.data.already_chatting == "Y") {
                        // 이미 채팅하기를 했던 상대: 기존 채팅방으로 이동 (첫 메시지 없음)
                        goExistingChatRoom("이미 채팅중입니다.", ret.data.rm_idx);
                        return;
                    }
                    // 처음 채팅하기: "채팅을 요청하였습니다." 자동 전송
                    location.href = "/chat/chat_view?rm_idx=" + ret.data.rm_idx + "&auto_send=true&rc_idx=" + ret.data.rc_idx + "&from=direct_chat&backUrl=" + encodeURIComponent('/chat/index_comp');
                } else {
                    $("#btnDirectChat").attr('data-loading', 'false');
                    commonAlert(ret.msg, function() {
                        $("#selectPanel_hrPost").removeClass("on");
                    });
                    return;
                }
            });
            return;
        }

        //오늘 제안여부 및 지원 여부 확인 (제안만 시간 제한 체크)
        var paramsToday = {};
        paramsToday["ru_idx"] = $("[name='ru_idx']").val();
        paramsToday["rc_idx"] = rc_idx;

        comAjax("/resume_proc/todayapply", paramsToday, function(ret) {
            if (ret.code == "00") {
                var mq_idxs = new Array();
                mq_idxs.push($("[name='ru_idx']").val());
                // hrPostPopup의 z-index를 임시로 낮춰서 confirm 팝업이 위에 표시되도록 함
                $('#hrPostPopup').css('z-index', '200');

                // 팝업 닫힐 때 z-index 복원하는 이벤트 리스너
                var restoreZIndex = function() {
                    $('#hrPostPopup').css('z-index', '1000');
                    $(document).off('click.popupClose', restoreZIndex);
                    // 다이렉트 채팅 경로라면 로딩 상태 해제
                    if (currentActionMode === 'direct_chat') {
                        $("#btnDirectChat").attr('data-loading', 'false');
                    }
                };

                // 팝업 배경이나 닫기 버튼 클릭 감지
                setTimeout(function() {
                    var $popup = $('.layer-popup.in').last();
                    if ($popup.length) {
                        $popup.find('.btn-close-popup').one('click', restoreZIndex);
                        $popup.one('click.popupClose', function(e) {
                            if (e.target === this) {
                                restoreZIndex();
                            }
                        });
                    }
                }, 50);

                // currentActionMode에 따라 confirm 문구 분기
                var confirmContent = currentActionMode === 'direct_chat'
                    ? '<em>' + ru_title + '</em><br>개인회원과 채팅을 시작하시겠습니까?'
                    : '<em>' + ru_title + '</em> 로<br> 제안을 보내시겠습니까?';

                openPopup('confirm', {
                    content: confirmContent,
                    firstCallback: function() {
                        restoreZIndex();
                    },
                    secondCallback: function() {
                        restoreZIndex();
                        var params = {};
                        params["mq_idxs"] = mq_idxs;
                        params["rc_idx"] = rc_idx;
                        params["mq_type"] = "RESUME";

                        // 다이렉트 채팅인 경우 is_direct_chat 파라미터 추가
                        if (currentActionMode === 'direct_chat') {
                            params["is_direct_chat"] = "Y";
                        }

                        comAjax("/common_proc/member_request", params, function(ret) {
                            //console.log(ret);
                            if (ret.code == "00") {
                                // 알림창 주석처리 - 바로 채팅방으로 이동
                                // commonAlert("선택한 채용공고로 제안하였습니다.", function() {
                                //     $("#selectPanel_hrPost").removeClass("on");
                                // });

                                $("#selectPanel_hrPost").removeClass("on");

                                // 다이렉트 채팅 또는 제안에 따라 이동 경로 분기
                                if (currentActionMode === 'direct_chat') {
                                    location.href = "/chat/chat_view?rm_idx=" + ret.data.rm_idx + "&auto_send=true&rc_idx=" + ret.data.rc_idx + "&from=direct_chat&backUrl=" + encodeURIComponent('/chat/index_comp');
                                } else {
                                    // 정식 제안도 chat_view로 직접 리다이렉트 (auto_send=true로 자동메시지 전송)
                                    location.href = "/chat/chat_view?rm_idx=" + ret.data.rm_idx + "&auto_send=true&rc_idx=" + ret.data.rc_idx + "&mq_type=" + ret.data.mq_type + "&ru_idx=" + ret.data.ru_idx + "&backUrl=" + encodeURIComponent('/chat/index_comp');
                                }
                            } else if (ret.code == "02") {
                                // 같은 공고로 이미 제안한 인재: 나갔다 들어온 이력이 있을 때만 확인 팝업+재전송, 없으면 조용히 이동
                                if (ret.data.exited_before == "Y") {
                                    confirmResendProposal(ret.data.rm_idx, rc_idx);
                                } else {
                                    goExistingChatRoomSilent(ret.data.rm_idx);
                                }
                                return;
                            } else {
                                commonAlert(ret.msg, function() {
                                    $("#selectPanel_hrPost").removeClass("on");
                                });
                                return;
                            }
                        });
                    }
                });
                $('.btn-close-popup').hide();
            } else if (ret.code == "02") {
                // 같은 공고로 이미 제안한 인재: 나갔다 들어온 이력이 있을 때만 확인 팝업+재전송, 없으면 조용히 이동
                if (ret.data.exited_before == "Y") {
                    confirmResendProposal(ret.data.rm_idx, rc_idx);
                } else {
                    goExistingChatRoomSilent(ret.data.rm_idx);
                }
                return;
            } else {
                if (currentActionMode === 'direct_chat') {
                    $("#btnDirectChat").attr('data-loading', 'false');
                }
                commonAlert(ret.msg, function() {
                    // 알림 후 패널 닫기
                    $("#selectPanel_hrPost").removeClass("on");
                });
                return;
            }
        });
    };

    // 동적 로드 후에도 바로 이벤트가 등록되도록 document.on 사용
    $(document).off('click', '.btnProcess').on('click', '.btnProcess', function() {
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
                $("#selectPanel_hrPost").removeClass("on");
            });
            return;
        }

        // submitMemberRequest 함수 호출
        submitMemberRequest(rc_idx, ru_title);
    });

    // selectable-item 클릭 시 해당 항목의 radio 버튼 선택
    $(document).off('click', '.selectable-item').on('click', '.selectable-item', function(e) {
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
    $(document).off('click', '#btnEditResume').on('click', '#btnEditResume', function() {
        var ruIdx = $("[name=ru_idx]").val();
        if (ruIdx) {
            location.href = "/mypage/resume_view/" + ruIdx;
        }
    });

    // 기업이 구직자에게 050 가상번호로 전화 연결
    $(document).off('click', '#btnGoChat').on('click', '#btnGoChat', function(e) {
        e.preventDefault();

        // 인재정보 전화하기 분석 (운영 환경에서만)
        var isProdEnvironment = !window.location.pathname.includes('/dev_nowapk');
        if (isProdEnvironment && typeof my_analytics !== 'undefined') {
            my_analytics.goal('resume_call');
        }

        var $btn = $(this);
        if ($btn.attr('data-loading') === 'true') return;
        $btn.attr('data-loading', 'true');

        var ru_idx = $("[name=ru_idx]").val();
        comAjax("/resume_proc/get_call_number", { ru_idx: ru_idx }, function(ret) {
            $btn.attr('data-loading', 'false');
        if (ret.code == "00" && ret.data && ret.data.vn) {
                location.href = "tel:" + ret.data.vn;
            } else {
                commonAlert(ret.msg || "전화 연결에 실패했습니다.", function() {});
            }
        });
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

 

    // 이력서 삭제 바텀시트 트리거
    $(document).off('click', '#btnDeleteResume').on('click', '#btnDeleteResume', function(e) {
        e.preventDefault();
        openPanel(document.getElementById('deleteResumeSheet'));
    });

    // 미리보기 등록하기: 최종 확정 (site_log는 이미 저장되어 있으므로 ru_idx만 전송)
    $(document).off('click', '#btnPreviewRegister').on('click', '#btnPreviewRegister', function(e) {
        e.preventDefault();
        var ruIdx = $("[name=ru_idx]").val();
        comAjax("/mypage_proc/resume_complete", { ru_idx: ruIdx }, function(ret) {
            if (ret.code == "00") {
                commonAlert("이력서가 등록되었습니다.", function() {
                    location.href = "/mypage/resume_list";
                });
            } else {
                commonAlert(ret.msg || "등록 처리 중 오류가 발생했습니다.", function() {});
            }
        });
    });

    // 이력서 삭제 확인
       window.confirmDeleteResume = function() {
            var ru_idx = $("[name=ru_idx]").val();
            commonAlert('삭제하시겠습니까?', function() {
            comAjax("/mypage_proc/resume_delete", { ru_idx: ru_idx }, function(ret) {
                closePanel(document.getElementById('deleteResumeSheet'));
                if (ret.code == "00") {
                    location.href = '/mypage/resume_list';
                } else {
                    commonAlert(ret.msg, function() {});
                }
                });
            });
        };

    // 온보딩 튜토리얼 팝업2 로직
    var JP_CURRENT_PAGE = 0;
    var JP_TOTAL_PAGES = 2;
    var jpPageTimeline = null;

    function jpPreventScroll(e) { e.preventDefault(); }

    window.openJobPostTutorial = function() {
        var popup = document.getElementById('tutorial-popup2');
        if (!popup) return;
        popup.style.display = 'flex';
        JP_CURRENT_PAGE = 0;
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
        document.addEventListener('touchmove', jpPreventScroll, { passive: false });
        document.addEventListener('wheel', jpPreventScroll, { passive: false });

        var skipBtn = popup.querySelector('.tutorial-skip-btn2');
        
        var jobPostImg = popup.querySelector('.tutorial-jobpost-img');
        var jobPostText = popup.querySelector('.tutorial-jobpost-text');
        var callImg = popup.querySelector('.tutorial-call-img');
        var callText = popup.querySelector('.tutorial-call-text');

        popup.querySelectorAll('.jp-page-1').forEach(function(el) { el.style.display = ''; });
        popup.querySelectorAll('.jp-page-2').forEach(function(el) { el.style.display = 'none'; });

        gsap.set([jobPostImg, jobPostText, callImg, callText,
            popup.querySelector('.tutorial-propose-img'), popup.querySelector('.tutorial-propose-text')], { opacity: 0 });

        var tl = gsap.timeline();
        tl.fromTo(jobPostImg, { opacity: 0 }, { opacity: 1, duration: 0.6, display: 'block' }, 0.5)
          .fromTo(skipBtn, { opacity: 0 }, { opacity: 1, duration: 0.3 }, 0.5)
          .fromTo(jobPostText, { opacity: 0 }, { opacity: 1, duration: 0.35 }, '-=0.1')
          .fromTo(callImg, { opacity: 0 }, { opacity: 1, duration: 0.3 }, '+=0.35')
          .fromTo(callText, { opacity: 0 }, { opacity: 1, duration: 0.35 }, '-=0.1');
    };

    window.closeJobPostTutorial = function() {
        var popup = document.getElementById('tutorial-popup2');
        if (!popup) return;
        popup.style.display = 'none';
        document.body.style.overflow = 'auto';
        document.documentElement.style.overflow = 'auto';
        document.removeEventListener('touchmove', jpPreventScroll);
        document.removeEventListener('wheel', jpPreventScroll);
        if (jpPageTimeline) jpPageTimeline.kill();
    };

    window.isTutorialOpen = function() {
        var popup = document.getElementById('tutorial-popup2');
        return popup && popup.style.display === 'flex';
    };

    window.closeTutorialPopup = function() {
        window.closeJobPostTutorial();
    };

    window.setJobPostTutorialPage = function(pageIndex) {
        var popup = document.getElementById('tutorial-popup2');
        JP_CURRENT_PAGE = pageIndex;
        if (jpPageTimeline) jpPageTimeline.kill();

        if (pageIndex === 1) {
            popup.querySelectorAll('.jp-page-1').forEach(function(el) { el.style.display = 'none'; });
            var proposeWrapper = popup.querySelector('.tutorial-propose-wrapper');
            var proposeImg = popup.querySelector('.tutorial-propose-img');
            var proposeText = popup.querySelector('.tutorial-propose-text');
            proposeWrapper.style.display = 'flex';
            gsap.set([proposeImg, proposeText], { opacity: 0 });

            jpPageTimeline = gsap.timeline({ defaults: { ease: 'power2.out' } });
            jpPageTimeline
                .fromTo(proposeImg, { opacity: 0 }, { opacity: 1, duration: 0.35 }, 0)
                .fromTo(proposeText, { opacity: 0 }, { opacity: 1, duration: 0.3 }, '+=0.35');
        }
    };

    document.addEventListener('click', function(e) {
        var popup = document.getElementById('tutorial-popup2');
        if (!popup || popup.style.display === 'none') return;

        if (e.target === popup || popup.contains(e.target)) {
            if (JP_CURRENT_PAGE < JP_TOTAL_PAGES - 1) {
                window.setJobPostTutorialPage(JP_CURRENT_PAGE + 1);
            } else {
                window.closeJobPostTutorial();
            }
        }
    });

    // 네이티브 앱 식별자 확인
    function isNativeApp() {
        return !!(window.APP_NOWAPK || (window.webkit && window.webkit.messageHandlers));
    }

    // 모바일 디바이스 판단
    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // 트리거: 최초 1회만 표시 (네이티브 앱에서만, 미리보기 제외, 공유 링크 제외)
    // 처음 설치 시에만 팝업 표시 (개발/운영 환경 상관없이)
    var isPreviewMode = <?= json_encode($isPreview) ?>;
    var isSharedLink = new URLSearchParams(location.search).get('shared') === 'true';
    if (!isPreviewMode && !isSharedLink && isNativeApp() && sessionStorage.getItem('resume_came_from_hr_post') === 'true') {
        if (!localStorage.getItem('hr_post_tutorial_seen')) {
            localStorage.setItem('hr_post_tutorial_seen', 'true');
            window.openJobPostTutorial();
        }
    }
</script>

<!-- 비회원 로그인 안내 패널 (hr_post.php 내부) -->
<?php
renderBottomSheet(
  'loginGuidePanelHrPost',
  '로그인 안내',
  '<p>로그인이 필요한 서비스입니다.</p><p>로그인 하시겠습니까?</p>',
  '로그인 및 회원가입',
  'btnLoginGuideHrPost',
  'location.href = "/member/login";',
  'bottom-sheet-content-container',
  'bottom-sheet-content-body'
);
?>

<!-- 비회원 로그인 안내 팝업 (팝업 컨텍스트 내부 패널 열기) -->
<script>
    // 팝업 내부의 로그인 패널을 직접 열기
    window.showLoginGuideFromPopup = function() {
        var loginPanel = document.getElementById('loginGuidePanelHrPost');
        if (!loginPanel) {
            console.error('[hr_post.php] loginGuidePanelHrPost를 찾을 수 없음');
            return;
        }

        var container = loginPanel.querySelector('.bottom-sheet-container');
        if (!container) return;

        // 팝업 컨텍스트에서 높이 측정: 강제 reflow + 지연
        setTimeout(function() {
            var _ = container.offsetHeight;  // Force reflow
            var fixedHeight = container.scrollHeight;
            container.style.height = fixedHeight + 'px';

            // 높이 고정 후 패널 열기
            requestAnimationFrame(function() {
                loginPanel.classList.add('on');

                // 팝업 내부 오버플로우 처리
                var popup = loginPanel.closest('#hrPostPopup') || loginPanel.parentNode;
                if (popup && popup.style) {
                    popup.style.overflow = 'visible';
                }
            });

            // 애니메이션 완료 후 높이 복구
            setTimeout(function() {
                container.style.height = '';
                if (popup && popup.style) {
                    popup.style.overflow = 'auto';
                }
            }, 400);
        }, 20);

        // Swipe 기능 초기화
        if (typeof initPanelSwipe === 'function' && !loginPanel.hasAttribute('data-swipe-initialized')) {
            initPanelSwipe('loginGuidePanelHrPost');
            loginPanel.setAttribute('data-swipe-initialized', 'true');
        }
    };
</script>