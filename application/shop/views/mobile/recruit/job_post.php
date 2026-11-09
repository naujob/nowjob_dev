<?php
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_template.php');

function formatPhoneNumber($number)
{
    $number = preg_replace('/[^0-9]/', '', $number);

    if (preg_match('/^01[016789]\d{7,8}$/', $number)) {
        return preg_replace('/^(\d{3})(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
    }

    if (preg_match('/^02\d{7,8}$/', $number)) {
        return preg_replace('/^(02)(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
    }

    if (preg_match('/^0[3-9]{1}[0-9]{1}\d{7,8}$/', $number)) {
        return preg_replace('/^(0\d{2})(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
    }

    if (preg_match('/^(15|16|18)\d{6}$/', $number)) {
        return preg_replace('/^(\d{4})(\d{4})$/', '$1-$2', $number);
    }

    return $number;
}

$isPopup = isset($is_popup) ? $is_popup : false;
?>
<input type="hidden" name="rc_idx" value="<?= $info["rc_idx"] ?>" />
<style>
    /* 채용공고 확인 팝업(openPopup modal)은 role=dialog라 alertdialog 전용 크기/여백이 빠져 작게 보임 → 같은 값으로 맞춤 */
    [id^="recruitSubmitConfirm"] .popup-container {
        width: 19.375rem;
    }

    [id^="recruitSubmitConfirm"] .popup-content {
        padding: 2.75rem 1.5rem 2rem;
    }

    /* 기업회원이 자신의 채용공고를 볼 때 view-footer 버튼 스타일 */


    .view-footer .btn-edit {
        flex: 1;
        background-color: #57CCAF;
        color: #fff;
        border: 1px solid #57CCAF;
        font-weight: 700;
    }



    /* view-footer 내 버튼과 링크에 padding 및 font-size 적용 */


    .info-table .pay {
        align-items: center;
    }

    .info-table .pay span {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .info-table .pay span em {
        position: relative;
        display: inline-block;
        font-style: normal;
        color: #777;
    }

    .info-table .pay span em:not(:last-child) {
        padding-right: 5px;
    }

    .info-table .pay span em.on {
        color: #1b9d7d;
    }

    .info-table .pay span em:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 1px;
        height: 0.5em;
        margin: auto;
        border-right: 1px solid #777;
    }

    /* 근무형태 스타일 */
    .worktype {
        display: flex;
        gap: 5px;
        align-items: normal;
        flex-wrap: wrap;
        flex-direction: column;
    }

    .worktype > div {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .worktype em {
        font-style: normal;
        color: #333;
        font-weight: 600;
    }

    .worktype em.wtype0 {
        color: #999;
    }

   

   

    .worktype .work-divider {
        color: #ccc;
        font-weight: 300;
    }

    /* 상세업무정보(.desc) 핀치 줌 스타일 */
    .view-desc .desc-clip {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .view-desc .desc {
        transform-origin: 0 0;
        transition: none;
        will-change: transform;
        overflow-x: auto;
        cursor: zoom-in;
    }

    .view-desc .desc.pinching {
        cursor: grabbing;
    }

    .location .btn-round {
        min-width: 6.625rem;
        height: 3.1875rem;

        font-size: 1.2rem;
        line-height: 1.5;

    }

    /* 내공고일 때만 toggle-icon-up 숨기기 */
    #floating-actions[data-own-recruit="true"][data-state="open"] .toggle-icon-up {
        display: none;
    }

    /* 온보딩 튜토리얼 팝업2 (상세페이지) */
    #tutorial-popup2 {
        position: fixed; inset: 0; background: rgba(0,0,0,0.9);
        z-index: 1400; display: none; align-items: center; justify-content: center;
    }
    .tutorial-skip-btn2 {
        position: absolute; top: 30px; right: 20px; color: #fff;
        font-size: 18px; font-weight: 700; cursor: pointer; opacity: 0; z-index: 1401;
    }

    /* 구인정보 카드 (job_post.png) — 화면 상단 중앙 */
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
        position: relative;
        right: -38px;
    
    }
    .tutorial-call-text {
        color: #fff; font-size: 18px; font-weight: 700; line-height: 1.4; margin: 0;
        text-align: right; white-space: nowrap; opacity: 0;
    }
    .tutorial-call-text .highlight-text-arrow { font-weight: 700; font-size: 20px; color: #1B9D7D; }

    /* 지원하기 (propose.png) — call과 동일 위치 (페이지2 전용) */
    .tutorial-propose-wrapper {
        position: absolute; bottom: 40px; right: 10px;
        display: flex; flex-direction: column; align-items: center; gap: 8px;
    }
    .tutorial-propose-img { 
    width: 110px; 
    height: 110px; 
    display: block; 
    opacity: 0; 
    position: relative;
    right: -57px;

}
    .tutorial-propose-text {
        color: #fff; font-size: 18px; font-weight: 700; line-height: 1.4; margin: 0;
        text-align: right; white-space: nowrap; opacity: 0;
    }
    .tutorial-propose-text .highlight-text-arrow { font-weight: 700; font-size: 20px; color: #1B9D7D; }
    .post.page.page-view .info-table .value {
         display: flex;
        gap: 8px;
    }
    /* 공유 패널: 카카오톡 / 다른 앱으로 공유를 한 줄에 나란히 배치 (gap 대신 여백 사용: iOS 13 호환) */
    #sharePanel .vendor-links {
        flex-direction: row;
        flex-wrap: nowrap;
        justify-content: center;
    }

    #sharePanel .vendor-links a {
        float: none;
        margin: 1.0625rem 0.75rem 8px;
    }

    /* 공유 패널 항목 정렬: 두 항목의 칸 폭을 같게 고정하고 아이콘과 글자를 가운데 맞춤 */
    #sharePanel .vendor-links a {
        width: 5.5rem;
        text-align: center;
    }

    #sharePanel .vendor-links a .ico {
        margin: 0 auto 0.75rem;
    }

    #sharePanel .vendor-links .share-other span:last-child {
        display: block;
        white-space: normal;
        word-break: keep-all;
        line-height: 1.3;
    }

    /* 다른 앱으로 공유 아이콘 패딩 (카카오톡 아이콘 크기에 맞춰 조정: 값이 클수록 아이콘이 작아짐) */
    #sharePanel .vendor-links .share-other .ico img {
        box-sizing: border-box;
        padding: 0;
    }

    /* 공유 패널 바텀시트: 높이를 줄이기 위해 헤더/여백을 줄이고 최대 높이를 제한 */
    #sharePanel .bottom-sheet-container {
        max-height: 60%;
    }

    #sharePanel .bottom-sheet-header {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }

    #sharePanel .bottom-sheet-title {
        font-size: 1.125rem;
    }

    #sharePanel .panel-body {
        padding: 0.5rem 1.375rem 1.25rem;
    }

    #sharePanel .vendor-links a {
        margin: 0.5rem 0.75rem 0;
    }

    /* 길찾기 바텀시트: 제목 영역 삭제로 줄어든 높이를 기존 헤더 여백만큼 상단 패딩으로 보완 (핸들바/X 버튼 아래 여백) */
    #findLocation .panel-body {
         padding-top: 1rem;
    padding-bottom: 2rem;
    }

</style>
<?php
// 로그인 여부 판단
$isLogin = $this->user->isLogin();
$userType = $isLogin ? $this->user->getUserType() : null;

// 기업회원이 자신의 채용공고를 볼 경우를 판단
$isOwnRecruit = ($userType === "COMP" && isset($info["mm_idx"]) && $info["mm_idx"] == $this->user->getUserIdx());

// 미리보기 모드 판단
$isPreview = isset($_GET['preview']) && $_GET['preview'] == '1';

// 관리 액션(재등록/수정/마감/삭제/광고) 차단 메시지 미리 결정
$restrict_manage_msg = null; // null = 정상 동작(기업회원 본인공고)
if (!$isLogin) {
    $restrict_manage_msg = '__LOGIN_GUIDE__'; // showLoginGuideFromPopup() 사용
} elseif ($userType === 'MEM') {
    $restrict_manage_msg = '기업회원만 가능한 기능입니다.';
} elseif ($userType === 'COMP' && !$isOwnRecruit) {
    $restrict_manage_msg = '본인의 공고만 가능한 기능입니다.';
}
?>
<div class="post page page-view">
    <div class="top-bar">
        <a href="#" class="btn-back" onclick="<?= $isPopup ? 'closeJobPostPopup(event)' : 'goBackToEdit(event)' ?>"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
        <span class="rt-group">
            <!-- <a href="#" class="btn-action" id="btnResume">이력서 작성</a> -->
            <a href="#sharePanel" aria-haspopup="dialog" aria-controls="sharePanel" class="btn-share js-toggle-panel"><span class="sr-text">공유하기</span></a>
        </span>
    </div><!-- //top-bar -->

    <!-- 앱 딥링크 -->
    <?= getCashJs("/resource/shop/mobile/js/app-deeplink.js") ?>

    <script>
        var rcIdx = '<?= $info['rc_idx'] ?>';
        var customShareUrl = location.origin + '/recruit/job_post/' + rcIdx + '?shared=true';
        var isPreview = '<?= $isPreview ? "true" : "false" ?>' === "true";

        // 공고 목록 페이지에서 돌아올 때 하이라이트 처리
        sessionStorage.setItem('recruit_highlight_rc_idx', rcIdx);
        sessionStorage.setItem('recruit_came_from_job_post', 'true');

        // preview 모드에서 draft 데이터 로드
        var draftData = {};
        var previewMemInfo = {};
        if (isPreview) {
            // localStorage에서 draft 읽기 (page1, page2, page3 모두 merge)
            var page1Key = "recruit_draft_" + rcIdx + "_page1";
            var page1Stored = localStorage.getItem(page1Key);

            if (page1Stored) {
                draftData = JSON.parse(page1Stored);
            }

            var page3Key = "recruit_draft_" + rcIdx + "_page3";
            var page3Stored = localStorage.getItem(page3Key);
            if (page3Stored) {
                var page3Data = JSON.parse(page3Stored);
                draftData = Object.assign(draftData, page3Data);
            }

            // sessionStorage에서 회사정보 읽기
            var memStored = sessionStorage.getItem('previewMemInfo');
            if (memStored) {
                previewMemInfo = JSON.parse(memStored);
            }
        }

        function goBackToEdit(e) {
            e.preventDefault();

            // preview 모드일 때 작성 페이지로 이동 (기존 공고 계속 수정)
            if (isPreview) {
                window.location.href = '/compage/recruit_write1_1/' + rcIdx;
            } else {
                // 알림(from=match)으로 들어온 경우 정해진 목적지로, 아니면 공고 목록으로 (무한 루프 방지)
                var matchBackUrl = typeof getMatchEntryBackUrl === 'function' ? getMatchEntryBackUrl() : null;
                if (matchBackUrl) {
                    location.replace(matchBackUrl);
                } else {
                    location.href = '/recruit/job_list';
                }
            }
        }

        // preview 모드에서 최종 등록: 확인 팝업을 먼저 띄우고, 확인을 눌렀을 때만 서버 등록(recruit_complete) 실행
        function submitPreview() {
            if (!isPreview) {
                alert("오류: preview 모드가 아닙니다.");
                return;
            }

            openPopup('modal', {
                id: 'recruitSubmitConfirm',
                content: '<div class="msg">채용공고를 등록하시겠습니까?</div>',
                firstButton: '취소',
                secondButton: '확인',
                removeClose: false,
                firstCallback: function() {
                    // 취소: 등록하지 않고 미리보기에 머무름 (X 버튼이 없어서 직접 닫음)
                    closePopup(document.querySelector('[id^="recruitSubmitConfirm"]'));
                },
                secondCallback: function() {
                    requestRecruitComplete();
                }
            });
        }

        // 채용공고 최종 등록 요청 (확인 팝업에서 확인을 눌렀을 때만 호출)
        function requestRecruitComplete() {
            // site_log에 이미 저장된 step3을 사용하여 recruit_complete 호출
            // (미리보기 진입 시 recruit_write1_1.php의 showPreviewPage()에서 site_log에 저장됨)
            var completeParams = "rc_idx=" + rcIdx;

            var xhrComplete = new XMLHttpRequest();
            xhrComplete.open('POST', '/compage_proc/recruit_complete', true);
            xhrComplete.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhrComplete.onload = function() {
                if (xhrComplete.status === 200) {
                    try {
                        var responseComplete = JSON.parse(xhrComplete.responseText);
                        if (responseComplete.code === "00") {
                            // draft 데이터 삭제
                            localStorage.removeItem("recruit_draft_" + rcIdx + "_page1");
                            localStorage.removeItem("recruit_draft_" + rcIdx + "_page2");
                            localStorage.removeItem("recruit_draft_" + rcIdx + "_page3");
                            localStorage.removeItem("recruit_draft_0_page1");
                            localStorage.removeItem("recruit_draft_0_page2");
                            localStorage.removeItem("recruit_draft_0_page3");
                            // sessionStorage에 남은 draft와 미리보기 플래그도 제거 (안 지우면 재진입 시 방금 등록한 내용이 복원됨)
                            ["0", rcIdx].forEach(function(idx) {
                                sessionStorage.removeItem("recruit_draft_" + idx + "_page1");
                                sessionStorage.removeItem("recruit_draft_" + idx + "_page2");
                                sessionStorage.removeItem("recruit_draft_" + idx + "_page3");
                            });
                            sessionStorage.removeItem("from_preview");

                            // 채용공고 등록 완료 (확인 팝업에서 확인을 눌러 실행된 경우에만 도달)
                            // 수정(rcIdx 있음)/최초 등록(rcIdx 0) 모두 공고 목록으로 이동
                            // 현재 페이지를 대체하여 뒤로가기로 미리보기 페이지에 다시 들어오지 않도록 함
                            location.replace("/compage/recruit_list");
                        } else {
                            alert(responseComplete.msg || "등록 처리 중 오류가 발생했습니다.");
                        }
                    } catch (e) {
                        alert("응답 처리 중 오류가 발생했습니다.");
                    }
                } else {
                    alert("서버 오류가 발생했습니다.");
                }
            };
            xhrComplete.onerror = function() {
                alert("네트워크 오류가 발생했습니다.");
            };
            xhrComplete.send(completeParams);
        }
    </script>

    <div class="page-content">

        <div class="view-visual-slider">
            <div class="slider-wrap keen-slider">
                <?php
                // 슬라이더 이미지: 표시용은 md(800), 확대보기(data-mfp-src)는 원본 lg를 쓴다.
                // srcset을 함께 주면 고해상도 단말에서만 브라우저가 lg를 고른다.
                $_slider_imgs = ["rc_img1", "rc_img2", "rc_img3", "rc_img4", "rc_img5", "rc_img99"];
                foreach ($_slider_imgs as $_img_key) :
                    $_img_url = isset($info[$_img_key]) ? $info[$_img_key] : "";
                    if ($_img_url == "") {
                        continue;
                    }
                    $_img_display = get_img_variant($_img_url, "md");
                    $_img_srcset = get_img_srcset($_img_url);
                ?>
                    <div class="keen-slider__slide">
                        <img data-src="<?= $_img_display ?>"
                            <?php if ($_img_srcset != "") : ?>data-srcset="<?= $_img_srcset ?>" sizes="100vw" <?php endif; ?>
                            data-mfp-src="<?= $_img_url ?>" alt="" class="lozad">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
            window.addEventListener('DOMContentLoaded', function() {
                new KeenSlider(".view-visual-slider .slider-wrap", {
                    loop: true,
                }, [
                    sliderDots,
                    addActiveClass
                ]);
            });
        </script>

        <div class="section view-header">
            <?php if ($mm_type === "MEM") { ?>
                <!-- 즐겨찾기 -->
                <button type="button" data-bk_type="RECRUIT" data-bk_key="<?= $info["rc_idx"] ?>" ata-user_id="<?= $mem_info["mm_id"] ?>" class="btnBookMark btn-view-bookmark js-toggle-bookmark <?= $book_yn == "Y" ? "on" : "" ?>">
                    <span class="sr-text">즐겨찾기 추가</span>
                </button>
            <?php } ?>
            <div class="title-wrap">
                <div class="container">
                    <div class="meta">
                        <!-- 작성일 <?= get_yyyymmdd($info["reg_dt"]) ?> -->
                        <p class="date"><?= $info["rc_idx"] ?></p>
                        <!-- 총 즐겨찾기수 -->
                        <p class="total-bookmark">
                            <span class="sr-text">총 즐겨찾기수</span>
                            <em><?= $info["fav_cnt"] ?></em>
                        </p>
                        <!-- 조회수 -->
                        <p class="total-view">
                            <span class="sr-text">조회수</span>
                            <em><?= $info["rc_hit"] ?></em>
                        </p>
                    </div>
                    <!-- 제목 -->
                    <h2 class="view-title"><?= $info["rc_name"] ?></h2>
                    <!-- 모집기간 -->
                    <p class="recruit-period">
                        <span>모집기간</span>
                        <em><?= get_yyyymmdd($info["rc_sdate"]) ?> ~ <?= get_yyyymmdd($info["rc_edate"]) ?></em>
                    </p>
                    <!-- 신고하기 -->
                    <!-- <button type="button" aria-haspopup="dialog" aria-controls="reportForm1" class="btn-report js-report"><span class="sr-text">신고하기</span></button> -->
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
                                params["bd_idx"] = $("[name=rc_idx]").val();
                                params["br_type"] = "recruit";
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

            <div class="section summary-wrapper">
                <div class="summary">
                    <div class="container">
                        <h3 class="secondary-title jobinfo-title">구인정보</h3>
                        <table class="info-table">
                            <caption><span class="sr-text">구인정보</span></caption>
                            <colgroup>
                                <col>
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td class="label">업무구분</td>
                                    <td class="value">
                                        <!-- 직종라벨 -->
                                        <?= getWorkingImg($info["rc_work"]) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">급여</td>
                                    <td class="value">
                                        <span class="pay">
                                            <?php if (strpos($info["rc_price_type"], "협의") !== false) { ?>
                                                <em class="amount">급여협의</em>
                                            <?php } else {
                                                // recruit_view.php와 동일한 급여 표시 형식 (getConvertPriceType 사용)
                                                $rc_price_rep = isset($info["rc_price_rep"]) ? $info["rc_price_rep"] : "";
                                                $price_type_html = getConvertPriceType($info["rc_price_type"], $rc_price_rep);
                                            ?>
                                                <em class="amount"><?= number_format($info["rc_price_pay"]) ?> 원</em>
                                                <span>(<?= $price_type_html ?>)</span>
                                            <?php } ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">근무형태</td>
                                    <td class="value">
                                        <?php
                                          $work_items = [];
                                          if (preg_match_all('/<span>(.*?)<\/span>/', $info["rc_work_type"], $matches)) {
                                            $work_items = $matches[1];
                                          } else {
                                            $work_items = preg_split('/\s*,\s*/', $info["rc_work_type"]);
                                          }
                                          $item_set = array_unique(array_map('trim', $work_items));
                                          $is_consultation = in_array('근무협의', $item_set) ||
                                            (in_array('주간', $item_set) && in_array('야간', $item_set) && in_array('2교대', $item_set));

                                          if ($is_consultation) {
                                            echo '<span class="working-time"><em class="wtype0">근무협의</em></span>';
                                          } else {
                                        ?>
                                        <div class="worktype">
                                          <?php
                                            if (strpos($info["rc_work_type"], "2교대") !== false) {
                                              if ($info["rc_am_stime"] != "" && $info["rc_am_etime"]) {
                                                echo '<div><em class="wtype1">주간</em><em>(' . $info["rc_am_stime"] . '~' . $info["rc_am_etime"] . ')</em></div>';
                                              }
                                              if ($info["rc_pm_stime"] != "" && $info["rc_pm_etime"]) {
                                                echo '<div><em class="wtype2">야간</em><em>(' . $info["rc_pm_stime"] . '~' . $info["rc_pm_etime"] . ')</em></div>';
                                              }
                                            } else {
                                              if (strpos($info["rc_work_type"], "주간") !== false && $info["rc_am_stime"] != "" && $info["rc_am_etime"]) {
                                                echo '<div><em class="wtype1">주간</em><em>(' . $info["rc_am_stime"] . '~' . $info["rc_am_etime"] . ')</em></div>';
                                              }
                                              if (strpos($info["rc_work_type"], "야간") !== false && $info["rc_pm_stime"] != "" && $info["rc_pm_etime"]) {
                                                echo '<div><em class="wtype2">야간</em><em>(' . $info["rc_pm_stime"] . '~' . $info["rc_pm_etime"] . ')</em></div>';
                                              }
                                            }
                                          ?>
                                        </div>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php /* 근무시간 표시 주석처리
                            <tr>
                                <td class="label">근무시간</td>
                                <td class="value">
                                    <?php if ($info["rc_am_stime"] == "" && $info["rc_pm_stime"] == "") : ?>
                                        -
                                    <?php else : ?>
                                        <?php if (strpos($info["rc_work_type"], "2교대") !== false) : ?>

                                            <?php if ($info["rc_am_stime"] != "" && $info["rc_am_etime"]) : ?>
                                                <span class="time"><?= $info["rc_am_stime"] ?>~<?= $info["rc_am_etime"] ?>(주간)</span>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>

                                            <?php if ($info["rc_pm_stime"] != "" && $info["rc_pm_etime"]) : ?>
                                                <span class="time"><?= $info["rc_pm_stime"] ?>~<?= $info["rc_pm_etime"] ?>(야간)</span>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if (strpos($info["rc_work_type"], "주간") !== false && strpos($info["rc_work_type"], "2교대") === false) :  ?>
                                            <?php if ($info["rc_am_stime"] != "" && $info["rc_am_etime"]) : ?>
                                                <span class="time"><?= $info["rc_am_stime"] ?>~<?= $info["rc_am_etime"] ?>(주간)</span>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if (strpos($info["rc_work_type"], "야간") !== false && strpos($info["rc_work_type"], "2교대") === false) :  ?>
                                            <?php if ($info["rc_pm_stime"] != "" && $info["rc_pm_etime"]) : ?>
                                                <span class="time"><?= $info["rc_pm_stime"] ?>~<?= $info["rc_pm_etime"] ?>(야간)</span>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            */ ?>
                                <tr>
                                    <td class="label">희망국적</td>
                                    <td class="value"><?= ($info["rc_nation"] == "내국인,외국인" || trim($info["rc_nation"]) == "" ? "무관" : $info["rc_nation"]) ?></td>
                                </tr>
                                <tr>
                                    <td class="label">희망성별</td>
                                    <td class="value"><?= ($info["rc_sex"] == "남자,여자" || trim($info["rc_sex"]) == "" ? "무관" : $info["rc_sex"]) ?></td>
                                </tr>
                                <tr>
                                    <td class="label">희망나이</td>
                                    <td class="value"><?php
                                        // 저장된 시작/종료 연령을 "20 ~ 40대" 형식으로 표시 (둘 다 비어 있으면 무관)
                                        $_sage = trim((string) $info["rc_sage"]);
                                        $_eage = trim((string) $info["rc_eage"]);
                                        if (($_sage . $_eage) === "") {
                                            echo "무관";
                                        } else {
                                            echo $_sage . " ~ " . $_eage . "대";
                                        }
                                        ?></td>
                                </tr>
                                <tr>
                                    <td class="label">근무요일</td>
                                    <td class="value">
                                        <?php
                                        // recruit_view.php와 동일한 근무요일 표시 (무관 여부 / 저장값 없으면 월~금 기본값)
                                        $isWeekUnlimited = (isset($info["rc_week_yn"]) && $info["rc_week_yn"] === "Y");
                                        $hasSavedWeek = trim((string) $info["rc_week"]) !== "";
                                        $weekDisplaySource = $hasSavedWeek ? $info["rc_week"] : "월,화,수,목,금";
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="section view-benefit">
            <div class="container">
                <h3 class="secondary-title">복리후생</h3>
                <ul class="benefit-group">
                    <?= getLifeType($info["rc_life_type"], "-v2") ?>
                </ul>
            </div>
        </div>

        <div class="section view-desc">
            <div class="container">
                <div class="title-section" style="justify-content: flex-start;    gap: 15px; ">
                    <h3 class="secondary-title">상세업무정보</h3>
                    <p class="desc-guide" style="text-align: left;     width: 205px; white-space: nowrap;">상세업무를 <span class="desc-guide__highlight">확대</span>할 수 있습니다</p>
                     <div class="title-form__aside" style ="margin-left: 90px;">
                    <button class="detail-recruit-content__preview" type="button" aria-label="새 창으로 보기">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <span class="screen-out">새 창으로 보기</span>
                    </button>
                </div>
                </div>
               
                <!-- 작성내용 -->
                <div class="desc-clip">
                    <div class="desc" style="width: 100%;">
                        <p class="desc-content"><?= $info["rc_desc"] ?></p><br>

                    </div>
                </div>
                <div class="hashtag">
                    <?= getHashTag($info["rc_hash"]) ?>
                </div>
            </div>
        </div>

        <div class="section view-location">
            <div class="container">
                
                    <h3 class="secondary-title">기본정보</h3>
                  
               
                <div class="location">
                    <table class="info-table">
                        <tr>
                            <td class="label">상호명</td>
                            <td class="value">
                                <span><?= (isset($mem_info["mm_comp_name"]) && $mem_info["mm_comp_name"]) ? $mem_info["mm_comp_name"] : (isset($info["mm_comp_name"]) && $info["mm_comp_name"] ? $info["mm_comp_name"] : "-") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">연락처</td>
                            <td class="value">
                                <span><?= (isset($mem_info["mm_phone"]) && $mem_info["mm_phone"]) ? formatPhoneNumber($mem_info["mm_phone"]) : (isset($info["mm_phone"]) && $info["mm_phone"] ? formatPhoneNumber($info["mm_phone"]) : "-") ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">상세주소</td>
                            <td class="value">
                                <span><?php
                                    $displayAddr = "";
                                    if (isset($addr["ad_jibun"]) && $addr["ad_jibun"]) {
                                        $displayAddr = $addr["ad_jibun"] . (isset($addr["ad_detail"]) && $addr["ad_detail"] ? " " . $addr["ad_detail"] : "");
                                    } elseif (isset($info["ad_jibun"]) && $info["ad_jibun"]) {
                                        $displayAddr = $info["ad_jibun"] . (isset($info["ad_detail"]) && $info["ad_detail"] ? " " . $info["ad_detail"] : "");
                                    }
                                    echo $displayAddr ?: "-";
                                ?></span>
                            </td>
                        </tr>
                    </table>
                    <?php if ($g_ios_publish != true) : ?>
                        <a href="#findLocation" role="button" aria-haspopup="dialog" aria-controls="findLocation" class="btn-primary btn-round js-toggle-panel">길찾기</a>
                    <?php endif; ?>
                </div>
                <div class="map">
                    <!-- 회사 지도 좌표  -->
                    <?php
                    // preview 모드에서도 지도 초기화를 위해 좌표 설정
                    $gps_y = (isset($addr["ad_gps_y"]) && $addr["ad_gps_y"]) ? $addr["ad_gps_y"] : '';
                    $gps_x = (isset($addr["ad_gps_x"]) && $addr["ad_gps_x"]) ? $addr["ad_gps_x"] : '';

                    // preview 모드에서 $addr이 없으면 draftData로부터 복원됨 (JavaScript에서)
                    $initial_geocoder = ($gps_y && $gps_x) ? "$gps_y,$gps_x" : '';
                    ?>
                    <input type="hidden" id="geocoder" value="<?= $initial_geocoder ?>">
                    <div id="jsMap" class="js-map"></div>
                </div>

                <script>
                    // 지도 초기화 함수 (SDK 로드 완료 후 호출됨)
                    window.initKakaoMap = function() {
                        var mapContainer = document.getElementById('jsMap');
                        if (!mapContainer) return;

                        var geocoder = document.getElementById('geocoder').value;
                        var coords, map, marker;

                        if (!geocoder) {
                            return;
                        }

                        geocoder = geocoder.split(',');
                        coords = new kakao.maps.LatLng(geocoder[0], geocoder[1]);

                        map = new kakao.maps.Map(mapContainer, {
                            center: coords,
                            level: 3,
                        });

                        marker = new kakao.maps.Marker({
                            position: coords,
                            image: new kakao.maps.MarkerImage(
                                '/resource/shop/mobile/img/ic_loc_pin_on.svg',
                                new kakao.maps.Size(36, 47), {
                                    offset: new kakao.maps.Point(16, 42)
                                }
                            )
                        });

                        marker.setMap(map);
                    };

                    // SDK 로드(또는 재사용) 후 지도 초기화 — popup/non-popup 양쪽에서 동일하게 동작
                    if (typeof window.loadKakaoMapSdk === 'function') {
                        window.loadKakaoMapSdk('<?= $kakao_key["인증키"] ?>', function() {
                            window.initKakaoMap();
                        });
                    }
                </script>
            </div>
        </div>

        <!-- 미리보기 모드 footer 버튼 -->
        <?php if ($isPreview) { ?>
        <div class="view-footer">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">수정하기</button>
            <button type="button" class="btn btn-primary" onclick="submitPreview()">등록하기</button>
        </div>
        <?php } ?>

        <!-- 플로팅 액션 버튼 그룹 -->
        <?php if (!$isPreview) : ?>
        <div id="floating-actions" data-state="open" <?php echo ($userType === "COMP" && $isOwnRecruit) ? 'data-own-recruit="true"' : ''; ?>>

            <!-- 로그인 버튼 (비로그인 시에만 노출) -->
            <?php if (!$isLogin) : ?>
                <a href="/member/login" class="floating-action-item" data-action="login" title="로그인">
                    <img src="/resource/shop/mobile/img/login-post.png" alt="로그인">
                    <span class="action-label">로그인</span>
                </a>
            <?php endif; ?>

            <!-- 지원하기/채팅하기/전화하기: 기업회원 본인 공고에서는 노출하지 않음 (관리 기능만 제공) -->
            <?php if (!$isOwnRecruit) : ?>

            <!-- 지원하기 -->
            <?php if (!$isLogin) : ?>
                <!-- 비로그인: 로그인 안내 -->
                <a href="#" class="floating-action-item" onclick="window.showLoginGuideFromPopup(); return false;" data-action="propose" title="지원">
                    <img src="/resource/shop/mobile/img/propose.png" alt="지원">
                    <span class="action-label">지원하기</span>
                </a>
            <?php elseif ($userType === 'MEM') : ?>
                <!-- 개인회원: 정상 동작 -->
                <a href="#" id="btnRequest" class="floating-action-item btnRequest" data-action="propose" title="제안">
                    <img src="/resource/shop/mobile/img/propose.png" alt="지원">
                    <span class="action-label">지원하기</span>
                </a>
            <?php else : ?>
                <!-- 기업회원: 차단 -->
                <a href="#" class="floating-action-item" onclick="commonAlert('개인회원만 가능한 기능입니다.', function(){}); return false;" data-action="propose" title="지원">
                    <img src="/resource/shop/mobile/img/propose.png" alt="지원">
                    <span class="action-label">지원하기</span>
                </a>
            <?php endif; ?>

            <?php endif; /* //!$isOwnRecruit */ ?>

            <!-- 재등록/수정/마감/삭제/광고 - 내공고에서만 표시 -->
            <?php if ($restrict_manage_msg === null) : ?>
                <?php
                $manage_actions = [
                    ['id' => 'btnReregisterRecruit', 'href' => '#', 'img' => '/resource/shop/mobile/img/propose.png', 'label' => '재등록'],
                    ['id' => '', 'href' => '/compage/recruit_write1_1/' . $info["rc_idx"], 'img' => '/resource/shop/mobile/img/modify.png', 'label' => '수정'],
                    ['id' => 'btnEndRecruit', 'href' => '#', 'img' => '/resource/shop/mobile/img/due.png', 'label' => '마감'],
                    ['id' => 'btnDeleteRecruit', 'href' => '#', 'img' => '/resource/shop/mobile/img/del.png', 'label' => '삭제'],
                    ['id' => '', 'href' => '/compage/point_promotion_buy/' . $info["rc_idx"], 'img' => '/resource/shop/mobile/img/ad-post.png', 'label' => '광고']
                ];
                foreach ($manage_actions as $action) :
                ?>
                    <a href="<?= $action['href'] ?>" class="floating-action-item" <?php echo !empty($action['id']) ? 'id="' . $action['id'] . '"' : ''; ?> data-action="<?= strtolower($action['label']) ?>">
                        <img src="<?= $action['img'] ?>" alt="<?= $action['label'] ?>">
                        <span class="action-label"><?= $action['label'] ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- 전화하기 (내 공고 제외 모두에게 노출, 로그인 게이트 없음) -->
            <?php if (!$isOwnRecruit) : ?>
            <a id="callLog" href="tel:<?= isset($mem_info["mm_phone"]) ? htmlspecialchars($mem_info["mm_phone"], ENT_QUOTES, 'UTF-8') : '' ?>" class="floating-action-item js-call-log" data-rc_idx="<?= $info["rc_idx"] ?>" data-action="call" title="전화">
                <img src="/resource/shop/mobile/img/call-post.png" alt="전화">
                <span class="action-label">전화하기</span>
            </a>
            <?php endif; ?>
        </div><!-- //#floating-actions -->
        <?php endif; ?>

        <!-- 기업회원 본인 공고 바텀시트 3개 -->
        <?php if (!$isPreview && $restrict_manage_msg === null) : ?>
            <?php renderSelectBottomSheet(
                'reregisterSheet',
                '채용공고 재등록',
                '<p>모집기간이 오늘부터 60일간으로 재설정되어 공고가 다시 등록됩니다.<br>재등록하시겠습니까?</p>',
                '취소', 'btnReregisterCancel', "closePanel(document.getElementById('reregisterSheet'));",
                '재등록', 'btnReregisterConfirm', 'confirmReregisterRecruit();'
            ); ?>
            <?php renderSelectBottomSheet(
                'endRecruitSheet',
                '채용공고 마감',
                '<p>해당 공고를 마감 처리하시겠습니까?</p>',
                '취소', 'btnEndCancel', "closePanel(document.getElementById('endRecruitSheet'));",
                '마감', 'btnEndConfirm', 'confirmEndRecruit();'
            ); ?>
            <?php renderSelectBottomSheet(
                'deleteRecruitSheet',
                '채용공고 삭제',
                '<p>삭제된 공고는 복구할 수 없습니다.<br>완전 삭제하시겠습니까?</p>',
                '취소', 'btnDeleteCancel', "closePanel(document.getElementById('deleteRecruitSheet'));",
                '삭제', 'btnDeleteConfirm', 'confirmDeleteRecruit();'
            ); ?>
        <?php endif; ?>

    </div><!-- //page-content -->

<?php if (!$isPopup) { ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/app_intercept_layer.php'); ?>
<?php } ?>
</div>

<!-- 공유하기 팝업 -->
<div id="sharePanel" class="layer-panel card-type" tabindex="-1">
    <div class="bottom-sheet-container">
        <!-- 핸들바 + X 버튼: 다른 바텀시트와 같은 형태 -->
        <div class="bottom-sheet-handlebar"></div>
        <button type="button" class="bottom-sheet-close-btn" aria-label="닫기" onclick="closePanel(document.getElementById('sharePanel'));"></button>
        <div class="bottom-sheet-header">
            <h2 class="bottom-sheet-title">공유하기</h2>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="vendor-links">
                    <!-- 카카오톡: 카드로 공유 (앱이면 카카오톡 공유 화면, 브라우저면 카카오 웹 공유) -->
                    <a href="javascript:void(0);" onclick="shareKakaoFromPanel();return false;" class="share-link1">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_vendor1.svg" alt=""></span>
                        <span>카카오톡</span>
                    </a>
                    <!-- 다른 앱: 기기 공유창(메모장, 메시지 등) -->
                    <a href="javascript:void(0);" onclick="shareOtherAppsFromPanel();return false;" class="share-link2 share-other">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_copy_url.svg" alt=""></span>
                        <span>다른 앱으로 공유</span>
                    </a>
                </div>

                <!-- 카카오 SDK -->
                <script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script>
                <script>
                    /* 카카오톡 공유 */
                    // 카카오 공유 내용 (앱 공유에서도 사용)
                    function getShareKakaoData() {
                        return {
                            title: <?= json_encode(trim($info["rc_name"]), JSON_UNESCAPED_UNICODE) ?>,
                            description: <?= json_encode(implode(' | ', array_values(array_filter(array(
                                (trim($info["rc_nation"]) == "내국인,외국인" || trim($info["rc_nation"]) == "") ? "국적무관" : trim($info["rc_nation"]),
                                formatAgeRange($info["rc_sage"], $info["rc_eage"]),
                                removeBigAddr(!empty($addr["ad_jibun"]) ? $addr["ad_jibun"] : (!empty($info["ad_jibun"]) ? $info["ad_jibun"] : ""))
                            )))), JSON_UNESCAPED_UNICODE) ?>,
                            buttonTitle: '확인하기',
                            // 카드 이미지 (앱 네이티브 SDK도 이 값을 사용하므로 payload.kakao에 반드시 포함)
                            imageUrl: location.origin + '/resource/shop/web/img/nowjob_info.png',
                            url: typeof customShareUrl !== 'undefined' && customShareUrl ? customShareUrl : location.href
                        };
                    }

                    function shareMessage() {
                        if (!Kakao.isInitialized()) {
                            Kakao.init("<?= $kakao_key["인증키"] ?>");
                        }

                        var data = getShareKakaoData();

                        setTimeout(function() {
                            Kakao.Share.sendDefault({
                                objectType: 'feed',
                                content: {
                                    title: data.title,
                                    description: data.description,
                                    imageUrl: data.imageUrl,
                                    link: {
                                        mobileWebUrl: data.url,
                                        webUrl: data.url
                                    },
                                },
                                buttons: [{
                                    title: data.buttonTitle,
                                    link: {
                                        mobileWebUrl: data.url,
                                        webUrl: data.url
                                    },
                                }],
                            });
                        }, 100);
                    }

                    // 이 페이지는 공유 방법 선택 패널을 먼저 열어야 하므로 share-panel.js의 바로 공유 기능을 끈다
                    window.SHARE_PANEL_CHOOSER = true;

                    /* 네이티브 앱 공유 요청 (withKakao가 true면 카카오 카드 포함) */
                    function sendNativeShare(withKakao) {
                        var titleElement = document.querySelector('.view-title');
                        var payload = {
                            title: titleElement ? titleElement.textContent.trim() : document.title,
                            url: customShareUrl
                        };
                        if (withKakao) {
                            payload.kakao = getShareKakaoData();
                        }

                        if (window.APP_NOWAPK && typeof window.APP_NOWAPK.share === 'function') {
                            window.APP_NOWAPK.share(JSON.stringify(payload));
                            return true;
                        }
                        if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.share) {
                            window.webkit.messageHandlers.share.postMessage(payload);
                            return true;
                        }
                        return false;
                    }

                    // 카카오톡 선택: 앱이면 카드 공유, 브라우저면 기존 카카오 웹 공유
                    function shareKakaoFromPanel() {
                        closePanel(document.getElementById('sharePanel'));
                        if (!sendNativeShare(true)) {
                            shareMessage();
                        }
                    }

                    // 다른 앱 선택: 앱이면 기기 공유창, 브라우저면 웹 공유 API 또는 URL 복사
                    function shareOtherAppsFromPanel() {
                        closePanel(document.getElementById('sharePanel'));
                        if (sendNativeShare(false)) {
                            return;
                        }
                        if (navigator.share) {
                            navigator.share({ title: document.title, url: customShareUrl }).catch(function() {});
                        } else {
                            CopyUrl();
                        }
                    }

                    // 공유 패널만 body 스크롤 잠금(no-scroll)과 window.scrollTo 없이 열고 닫는다
                    // (팝업 안의 스크롤 영역이 튀는 현상 방지). 다른 패널은 기존 openPanel/closePanel을 그대로 쓴다.
                    if (!window.__sharePanelOpenerWrapped) {
                        window.__sharePanelOpenerWrapped = true;
                        var baseOpenPanel = window.openPanel;
                        var baseClosePanel = window.closePanel;
                        window.openPanel = function ($el) {
                            if ($el && $el.id === 'sharePanel') {
                                $el.classList.add('on');
                                return;
                            }
                            baseOpenPanel($el);
                        };
                        window.closePanel = function ($el) {
                            if ($el && $el.id === 'sharePanel') {
                                $el.classList.remove('on');
                                return;
                            }
                            baseClosePanel($el);
                        };
                    }

                    var shareOsType = "<?= $os_type ?>";

                </script>
            </div>
        </div>
    </div>
</div>
<!-- //공유하기 팝업 -->

<?= getCashJs("/resource/shop/mobile/js/share-panel.js") ?>

<!-- 온보딩 튜토리얼 팝업 (상세페이지) -->
<div id="tutorial-popup2" style="display:none;">
    <span class="tutorial-skip-btn2" onclick="window.closeJobPostTutorial();">건너뛰기</span>

    <!-- 페이지 1: 구인정보 + 전화하기 -->
    <div class="tutorial-jobpost-wrapper jp-page-1">
        <p class="tutorial-jobpost-text">채용정보 <span class="highlight-text-arrow">상세 희망 근무조건</span><br>채용공고의 근무조건을 바로 확인할 수 있어요</p>
        <img src="/resource/shop/mobile/img/tutorial/job_post.png" class="tutorial-jobpost-img" alt="구인정보">
    </div>
    <div class="tutorial-call-wrapper jp-page-1">
        <p class="tutorial-call-text">채용담당자와 다이렉트 연결<br>지금 바로 출근지원, <span class="highlight-text-arrow">전화하기</span></p>
        <img src="/resource/shop/mobile/img/tutorial/call.png" class="tutorial-call-img" alt="전화하기">
    </div>

    <!-- 페이지 2: 지원하기 -->
    <div class="tutorial-propose-wrapper jp-page-2" style="display:none;">
        <p class="tutorial-propose-text">나우잡 채팅으로 시작하는 구인구직<br>출근하고 싶은 채용공고에, <span class="highlight-text-arrow">지원하기</span></p>
        <img src="/resource/shop/mobile/img/tutorial/apply.png" class="tutorial-propose-img" alt="지원하기">
    </div>
</div>
<!-- //온보딩 튜토리얼 팝업 -->

<!-- 길찾기 팝업 -->
<div id="findLocation" class="layer-panel card-type" tabindex="-1">
    <div class="bottom-sheet-container">
        <!-- 핸들바 + X 버튼: 공유하기 바텀시트와 같은 형태 -->
        <div class="bottom-sheet-handlebar"></div>
        <button type="button" class="bottom-sheet-close-btn" aria-label="닫기" onclick="closePanel(document.getElementById('findLocation'));"></button>
        <div class="panel-body">
            <div class="panel-content">

                <div class="vendor-links">
                    <a href="javascript:naverMap('<?= $info["mm_comp_name"] ?>','<?= $addr["ad_gps_y"] ?>','<?= $addr["ad_gps_x"] ?>');" id="findNaverMap" class="vendor-link1">
                        <span class="ico" style="border:none;"><img src="/resource/shop/mobile/img/map_naver.png" alt=""></span>
                        <span>네이버</span>
                    </a>
                    <a href="javascript:kakaoMap('<?= $info["mm_comp_name"] ?>','<?= $addr["ad_gps_y"] ?>','<?= $addr["ad_gps_x"] ?>');" class="vendor-link2">
                        <span class="ico" style="border:none;"><img src="/resource/shop/mobile/img/map_kakao.png" alt=""></span>
                        <span>카카오</span>
                    </a>
                    <a href="javascript:tMap('<?= $info["mm_comp_name"] ?>','<?= $addr["ad_gps_y"] ?>','<?= $addr["ad_gps_x"] ?>');" class="vendor-link3">
                        <span class="ico" style="border:none;"><img src="/resource/shop/mobile/img/mpa_tmap.png" alt=""></span>
                        <span>티맵</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- //길찾기 팝업 -->

<input type="hidden" name="rc_idx" value="<?= $info["rc_idx"] ?>" />
<input type="hidden" name="rc_name" value="<?= $info["rc_name"] ?>" />
<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<?php
// 모집 마감 여부 계산 (종료일이 현재 시각 이전이면 마감)
$is_closed = (!empty($info["rc_edate"]) && strtotime($info["rc_edate"]) <= time());
?>
<input type="hidden" id="is_closed" value="<?= $is_closed ? "Y" : "N" ?>" />
<script>
    var sel_lat = 0;
    var sel_lng = 0;
    var sel_name = ""; // 목적지 이름 저장
    var map_type = "kakao"; // 사용할 맵 앱 타입 (kakao, tmap)

    //티맵 길안내
    function tMap(name, lat, lng) {
        // 팝업 닫기
        var findLocationPanel = document.getElementById('findLocation');
        if (findLocationPanel && typeof closePanel === 'function') {
            closePanel(findLocationPanel);
        }

        // 현재 페이지 URL 저장 (iOS 네이티브 뒤로가기 버튼에서 사용)
        sessionStorage.setItem('beforeMapAppUrl', window.location.href);

        var os_type = "<?= $os_type ?>";
        var userAgent = navigator.userAgent.toLowerCase();

        // os_type이 없거나 빈 값이면 User Agent로 판단
        if (!os_type || os_type === "") {
            if (/iphone|ipad|ipod/i.test(userAgent)) {
                os_type = "ios";
            } else if (/android/i.test(userAgent)) {
                os_type = "android";
            }
        }

        if (os_type == "android") {
            // Android일 때 네이티브에서 처리 가능한 형식으로 변경 (ERR_UNKNOWN_URL_SCHEME 오류 방지)
            location.href = "https://apis.openapi.sk.com/tmap/app/routes?appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41&name=" + encodeURIComponent(name) + "&lon=" + lng + "&lat=" + lat;
        } else if (os_type == "ios") {
            // iOS일 때 커스텀 URL 스킴 사용
            // 네이티브 코드에서 앱 설치 여부 확인 후 App Store로 이동 처리
            location.href = "tmap://route?goalx=" + lng + "&goaly=" + lat + "&goalname=" + encodeURIComponent(name);
        } else {
            // 웹 브라우저에서는 웹 URL 호출 금지
            alert("모바일 앱에서만 길안내가 가능합니다.");
        }

        //var tmapUrl = 'tmap://route?rGoName='+encodeURIComponent(name)+'&rGoX='+lng+'&rGoY='+lat;
        //lng = 127.032423;
        //lat = 37.490928;
        //var tmapUrl = 'tmap://route?rGoName='+encodeURIComponent(name)+'&rGoX='+lng+'&rGoY='+lat;
        //alert(tmapUrl);
        //window.location.href = tmapUrl;

        //tmap://route?goalx=" + lon + "&goaly=" + lat + "&goalname=" + name + query;
        //?appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41&lat=37.0702312017&lon=126.8771905406&name=주식회사+부강제이엠
        //var url = "tmap://route?goalx=" + lng + "&goaly=" + lat + "&goalname=" + name + "&appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41";

        //tmap 중계 페이지에서 실행되는 코드
        // tmap://route?goalx=126.8771905406&goaly=37.0702312017&goalname=%EC%A3%BC%EC%8B%9D%ED%9A%8C%EC%82%AC %EB%B6%80%EA%B0%95%EC%A0%9C%EC%9D%B4%EC%97%A0&appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41
        //         tmap://route?goalx=126.8771906&goaly=37.07312017&goalname=%E%EC%9 7%A0&appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41
        //var url = "tmap://route?goalx=" + lng + "&goaly=" + lat + "&goalname=" + encodeURIComponent(name) + "&appkey=l7xx7179ddde21ca4bfb8e6b03c710138f41";
        //var url = 'tmap://route?goalx=126.8771905406&goaly=37.0702312017&goalname=%EC%A3%BC%EC%8B%9D%ED%9A%8C%EC%82%AC%EB%B6%80%EA%B0%95%EC%A0%9C%EC%9D%B4%EC%97%A0&appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41';
        //alert(url);
        //window.location = url;

    }

    //카카오맵 길안내
    function kakaoMap(name, lat, lng) {
        // 팝업 닫기
        var findLocationPanel = document.getElementById('findLocation');
        if (findLocationPanel && typeof closePanel === 'function') {
            closePanel(findLocationPanel);
        }

        // 현재 페이지 URL 저장 (iOS 네이티브 뒤로가기 버튼에서 사용)
        sessionStorage.setItem('beforeMapAppUrl', window.location.href);

        var os_type = "<?= $os_type ?>";
        var userAgent = navigator.userAgent.toLowerCase();

        // os_type이 없거나 빈 값이면 User Agent로 판단
        if (!os_type || os_type === "") {
            if (/iphone|ipad|ipod/i.test(userAgent)) {
                os_type = "ios";
            } else if (/android/i.test(userAgent)) {
                os_type = "android";
            }
        }

        if (os_type == "android") {
            // Android일 때 커스텀 URL 스킴 사용
            location.href = "kakaomap://route?ep=" + lat + "," + lng + "&by=CAR";
        } else if (os_type == "ios") {
            // iOS일 때 커스텀 URL 스킴 사용
            // 네이티브 코드에서 앱 설치 여부 확인 후 App Store로 이동 처리
            location.href = "kakaomap://route?ep=" + lat + "," + lng + "&by=CAR";
        } else {
            // 웹 브라우저에서는 웹 URL 호출 금지
            alert("모바일 앱에서만 길안내가 가능합니다.");
        }

        //location.href="kakaomap://route?sp=",127.005515&ep=37.4979502,127.0276368&by=" + name;
    }

    // 네이버맵 길안내
    function naverMap(name, lat, lng) {
        // 팝업 닫기
        var findLocationPanel = document.getElementById('findLocation');
        if (findLocationPanel && typeof closePanel === 'function') {
            closePanel(findLocationPanel);
        }

        // 현재 페이지 URL 저장 (iOS 네이티브 뒤로가기 버튼에서 사용)
        sessionStorage.setItem('beforeMapAppUrl', window.location.href);

        var os_type = "<?= $os_type ?>";
        var userAgent = navigator.userAgent.toLowerCase();

        // os_type이 없거나 빈 값이면 User Agent로 판단
        if (!os_type || os_type === "") {
            if (/iphone|ipad|ipod/i.test(userAgent)) {
                os_type = "ios";
            } else if (/android/i.test(userAgent)) {
                os_type = "android";
            }
        }

        if (os_type == "android") {
            // Android: nmap 스킴 사용
            location.href = "nmap://route?dlat=" + lat + "&dlng=" + lng + "&dname=" + encodeURIComponent(name);
        } else if (os_type == "ios") {
            // iOS: nmap 스킴 사용
            location.href = "nmap://route?dlat=" + lat + "&dlng=" + lng + "&dname=" + encodeURIComponent(name);
        } else {
            // 웹 브라우저에서는 웹 URL 호출
            location.href = "https://app.map.naver.com/launchApp/?version=11&menu=navigation&elat=" + lat + "&elng=" + lng + "&etitle=" + encodeURIComponent(name);
        }
    }
</script>

<script>
    function returnLocation(lat, lng) {
        //안드로이드 앱에 위치를 요청한 후
        //콜백받는 부분임
        // 위치 권한이 없거나 거부된 경우 조용히 종료 (팝업 표시 안 함)
        if (lat == "0" && lng == "0") {
            // 위치 권한이 없는 경우 팝업 없이 조용히 종료
            return;
        }

        // OS 타입 확인
        var os_type = "<?= $os_type ?>";
        var userAgent = navigator.userAgent.toLowerCase();
        if (!os_type || os_type === "") {
            if (/iphone|ipad|ipod/i.test(userAgent)) {
                os_type = "ios";
            } else if (/android/i.test(userAgent)) {
                os_type = "android";
            }
        }

        // 선택된 맵 앱 타입에 따라 다른 URL 실행
        if (map_type == "tmap") {
            if (os_type == "android") {
                // Android일 때 네이티브에서 처리 가능한 형식으로 변경 (ERR_UNKNOWN_URL_SCHEME 오류 방지)
                location.href = "https://apis.openapi.sk.com/tmap/app/routes?appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41&name=" + encodeURIComponent(sel_name) + "&lon=" + sel_lng + "&lat=" + sel_lat;
            } else {
                // iOS일 때 티맵 앱 스킴 사용
                location.href = "tmap://route?goalx=" + sel_lng +
                    "&goaly=" + sel_lat +
                    "&goalname=" + encodeURIComponent(sel_name);
            }
        } else {
            // 카카오맵 앱 스킴만 실행
            location.href = "kakaomap://route?sp=" + lat + "," + lng +
                "&ep=" + sel_lat + "," + sel_lng + "&by=CAR";
        }
    }
</script>

<script>
    // 상세정보 확대 및 핀치줌 기능 전역 함수
    window.initJobPostDescFeatures = function() {
        // .desc 영역 핀치 줌 기능
        (function() {
            var descEl = document.querySelector('.view-desc .desc');
            if (!descEl) return;
            if (descEl.dataset.pinchInit) return;   // 중복 바인딩 가드
            descEl.dataset.pinchInit = '1';

            var scale = 1;
            var lastScale = 1;
            var startDist = 0;
            var originX = 0,
                originY = 0;
            var translateX = 0,
                translateY = 0;
            var isPinching = false;
            var isPanning = false;
            var wasPinching = false;
            var panStartX = 0,
                panStartY = 0;
            var lastTap = 0;

            var MIN_SCALE = 1;
            var MAX_SCALE = 5;

            function getDistance(t1, t2) {
                var dx = t2.clientX - t1.clientX;
                var dy = t2.clientY - t1.clientY;
                return Math.sqrt(dx * dx + dy * dy);
            }

            function applyTransform() {
                var cw = descEl.offsetWidth;
                var ch = descEl.offsetHeight;
                var minTx = -(cw * (scale - 1));
                var maxTx = 0;
                var minTy = -(ch * (scale - 1));
                var maxTy = 0;
                translateX = Math.max(minTx, Math.min(maxTx, translateX));
                translateY = Math.max(minTy, Math.min(maxTy, translateY));

                descEl.style.transform =
                    'translate(' + translateX + 'px, ' + translateY + 'px) scale(' + scale + ')';
            }

            descEl.addEventListener('touchstart', function(e) {
                if (e.touches.length === 2) {
                    isPinching = true;
                    isPanning = false;
                    wasPinching = false;
                    lastTap = 0;
                    startDist = getDistance(e.touches[0], e.touches[1]);
                    lastScale = scale;

                    var rect = descEl.getBoundingClientRect();
                    originX = (e.touches[0].clientX + e.touches[1].clientX) / 2 - rect.left;
                    originY = (e.touches[0].clientY + e.touches[1].clientY) / 2 - rect.top;

                    descEl.classList.add('pinching');
                    e.preventDefault();
                } else if (e.touches.length === 1 && scale > 1) {
                    isPanning = true;
                    isPinching = false;
                    panStartX = e.touches[0].clientX - translateX;
                    panStartY = e.touches[0].clientY - translateY;
                }
            }, {
                passive: false
            });

            descEl.addEventListener('touchmove', function(e) {
                if (isPinching && e.touches.length === 2) {
                    var dist = getDistance(e.touches[0], e.touches[1]);
                    var newScale = lastScale * (dist / startDist);
                    newScale = Math.min(MAX_SCALE, Math.max(MIN_SCALE, newScale));

                    var scaleChange = newScale / scale;
                    translateX = originX - scaleChange * (originX - translateX);
                    translateY = originY - scaleChange * (originY - translateY);

                    scale = newScale;
                    applyTransform();
                    e.preventDefault();
                } else if (isPanning && e.touches.length === 1 && scale > 1) {
                    translateX = e.touches[0].clientX - panStartX;
                    translateY = e.touches[0].clientY - panStartY;
                    applyTransform();
                    e.preventDefault();
                }
            }, {
                passive: false
            });

            descEl.addEventListener('touchend', function(e) {
                if (e.touches.length < 2) {
                    if (isPinching) wasPinching = true;
                    isPinching = false;
                    descEl.classList.remove('pinching');
                }
                if (e.touches.length === 0) {
                    isPanning = false;
                    if (scale <= MIN_SCALE) {
                        scale = 1;
                        translateX = 0;
                        translateY = 0;
                        applyTransform();
                    }
                }
            }, {
                passive: true
            });

            descEl.addEventListener('touchend', function(e) {
                if (wasPinching) {
                    wasPinching = false;
                    lastTap = 0;
                    return;
                }
                var now = Date.now();
                if (now - lastTap < 300 && e.changedTouches.length === 1) {
                    scale = 1;
                    translateX = 0;
                    translateY = 0;
                    applyTransform();
                }
                lastTap = now;
            }, {
                passive: true
            });
        })();

        // 새 창으로 크게 보기 버튼 처리
        $(document).off('click.jobPostPreview', '.detail-recruit-content__preview')
            .on('click.jobPostPreview', '.detail-recruit-content__preview', function() {
                var descHtml = $('.view-desc .desc').html();
                if (!descHtml) {
                    commonAlert("표시할 내용이 없습니다.", function() {});
                    return;
                }

                var previewWindow = window.open('', '_blank', 'width=900,height=700,scrollbars=yes');
                if (!previewWindow) {
                    commonAlert("팝업 차단이 해제되어야 열 수 있습니다.", function() {});
                    return;
                }

                previewWindow.document.open();
                previewWindow.document.write(
                    '<!doctype html>' +
                    '<html lang="ko">' +
                    '<head>' +
                    '<meta charset="utf-8">' +
                    '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">' +
                    '<title>상세업무정보</title>' +
                    '<style>' +
                    'body{margin:20px;font-family:Apple SD Gothic Neo,Malgun Gothic,system-ui,-apple-system,Segoe UI,sans-serif;font-size:16px;line-height:1.6;color:#222;}' +
                    '.desc{width:100%;}' +
                    '</style>' +
                    '</head>' +
                    '<body>' +
                    '<div class="desc">' + descHtml + '</div>' +
                    '</body>' +
                    '</html>'
                );
                previewWindow.document.close();
            });
    };

    // 동적 로드 시에도 즉시 실행되도록 IIFE로 감싼다 (DOMContentLoaded는 이미 지나간 상태)
    (function() {
        // preview 모드에서 draft 및 sessionStorage 데이터를 DOM에 적용
        if (isPreview) {
            // 제목 (rc_name)
            if (draftData.rc_name) {
                var titleEl = document.querySelector('.view-title');
                if (titleEl) {
                    titleEl.textContent = draftData.rc_name;
                }
            }

            // 기본정보 테이블 업데이트
            var rows = document.querySelectorAll('.view-location .info-table tr');

            // 상호명
            if (previewMemInfo.mm_comp_name) {
                for (var i = 0; i < rows.length; i++) {
                    var label = rows[i].querySelector('.label');
                    if (label && label.textContent.includes('상호명')) {
                        rows[i].querySelector('.value span').textContent = previewMemInfo.mm_comp_name;
                        break;
                    }
                }
            }

            // 연락처
            if (previewMemInfo.mm_phone) {
                for (var i = 0; i < rows.length; i++) {
                    var label = rows[i].querySelector('.label');
                    if (label && label.textContent.includes('연락처')) {
                        rows[i].querySelector('.value span').textContent = previewMemInfo.mm_phone;
                        break;
                    }
                }
            }

            // 상세주소 (화면 표시는 지번주소를 우선 사용 - recruit_write1_1.php와 동일한 기준)
            if (draftData.ad_jibun) {
                for (var i = 0; i < rows.length; i++) {
                    var label = rows[i].querySelector('.label');
                    if (label && label.textContent.includes('상세주소')) {
                        var addrText = draftData.ad_jibun + (draftData.ad_detail ? ' ' + draftData.ad_detail : '');
                        rows[i].querySelector('.value span').textContent = addrText;
                        break;
                    }
                }
            }

            // 상세업무정보 (rc_desc) 업데이트
            if (draftData.rc_desc) {
                var descEl = document.querySelector('.view-desc .desc');
                if (descEl) {
                    // contenteditable div의 HTML을 그대로 사용 (\n을 <br>로 변환)
                    var descHtml = draftData.rc_desc.replace(/\n/g, '<br>');
                    descEl.innerHTML = '<p class="desc-content" style="font-size: 1.1rem;">' + descHtml + '</p><br>';
                }
            }

            // 지도 좌표 업데이트 (미리보기 모드에서 지도 표시용)
            if (draftData.ad_gps_y && draftData.ad_gps_x) {
                var geocoderEl = document.getElementById('geocoder');
                if (geocoderEl) {
                    var updatedGeocode = draftData.ad_gps_y + ',' + draftData.ad_gps_x;
                    geocoderEl.value = updatedGeocode;

                    // 지도 div의 hidden 속성 제거
                    var mapDiv = geocoderEl.parentNode;
                    if (mapDiv && mapDiv.getAttribute('hidden')) {
                        mapDiv.removeAttribute('hidden');
                    }

                    // SDK 로드 후 지도 초기화
                    if (typeof window.loadKakaoMapSdk === 'function') {
                        window.loadKakaoMapSdk('<?= $kakao_key["인증키"] ?>', function() {
                            if (typeof window.initKakaoMap === 'function') {
                                window.initKakaoMap();
                            }
                        });
                    }
                }
            }
        }

        $('.view-visual-slider').magnificPopup({
            delegate: 'img',
            type: 'image',
            gallery: {
                enabled: true
            }
        });

        var user_type = "<?= $this->user->getUserType(); ?>";
        // 상세정보 확대/핀치줌 기능 초기화
        if (typeof window.initJobPostDescFeatures === 'function') {
            window.initJobPostDescFeatures();
        }
        // 모집 마감된 공고 여부 (마감 시 지원 버튼 즉시 차단)
        var isClosedRecruit = $("#is_closed").val() === "Y";

        $(document).on('click', '#btnRecruit, #btnRequest', function(e) {
            // 채용정보 지원하기 분석
            //  if (typeof my_analytics !== 'undefined') {
            //     my_analytics.goal('recruit_recruit');
            // }
            if ($(this).attr('href') === '#') {
                e.preventDefault();
            }
            if (isClosedRecruit) {
                commonAlert("마감된 공고입니다", function() {});
                return;
            }
            //로그인 여부 확인
            if ($("#is_login").val() != "Y") {
                //commonLogin();

                commonAlert("로그인후 이용해주세요!", function() {
                    location.href = '/member/login';
                });

                return;
            }

            if (user_type != "MEM") {
                commonAlert("기업 회원은 지원 할 수 없습니다.", function() {});
                return;
            }

            //이력서 여부 확인
            if ($("[name=resume_yn]").val() == "N") {
                openPopup('alert', {
                    content: "이력서 작성및 공개이력서 설정후 지원해주세요.",
                    firstButton: '이력서 등록',
                    firstCallback: function() {
                        location.href = '/mypage/resume_write1';
                    }
                });
                return;
            }

            //오늘 지원여부 및 제안 여부 확인
            var paramsToday = {};
            paramsToday["rc_idx"] = $("[name=rc_idx]").val();

            comAjax("/recruit_proc/todayapply", paramsToday, function(ret) {
                if (ret.code == "00") {
                    //$('.btn-close-popup').hide();
                    openPopup('confirm', {
                        content: '<em>“' + $("[name=rc_name]").val() + '”</em>으로<br> 지원하시겠습니까?',
                        firstCallback: function() {
                            $('.btn-close-popup').show();
                        },
                        secondCallback: function() {
                            var params = {};
                            var mq_idxs = [];
                            mq_idxs.push($("[name=rc_idx]").val());
                            params["mq_idxs"] = mq_idxs;
                            params["mq_type"] = "RECRUIT";
                            var rc_idx = $("[name=rc_idx]").val();

                            comAjax("/common_proc/member_request", params, function(ret) {
                            
                                $('.btn-close-popup').show();

                                if (ret.code == "00") {
                                    // 지원하기 클릭 로그 기록
                                    // 지원 로그는 comAjax를 쓰지 않는다: comAjax 성공 시 모든 팝업을 지워 완료 알림이 사라지므로
                                    // 결과를 쓰지 않는 기록이라 $.ajax로 보내고 실패해도 지원 흐름에 영향 없음
                                    $.ajax({ type: "POST", url: "/recruit_proc/call_log", data: { rc_idx: rc_idx, cl_type: "APPLY" } });

                                    // 채팅 기능 중단: 채팅방 이동 없이 완료 안내만 표시, 현재 페이지 유지
                                    commonAlert("지원이 완료되었습니다.", function() {});
                                } else if (ret.code == "02") {
                                    // 이미 지원한 공고: 채팅방 이동 없이 안내만 표시
                                    commonAlert(ret.msg, function() {});
                                    return;
                                } else {
                                    commonAlert(ret.msg, function() {});
                                    return;
                                }
                            });
                        }
                    });
                    $('.btn-close-popup').hide();
                } else if (ret.code == "02") {
                    // 이미 지원한 공고: 채팅방 이동 없이 안내만 표시
                    commonAlert(ret.msg, function() {});
                    return;
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });

        // 이력서 작성 버튼 클릭 이벤트 20250919
        $("#btnResume").click(function(e) {
            e.preventDefault();

            // 로그인 여부 확인
            if (user_type != "MEM") {
                commonAlert("로그인 후 이용해주세요.", function() {
                    location.href = '/member/login';
                });
                return;
            }

            // 이력서 작성 확인 팝업
            openPopup('confirm', {
                content: '이력서 작성 페이지로<br> 이동하시겠습니까?',
                firstCallback: function() {
                    $('.btn-close-popup').show();
                },
                secondCallback: function() {
                    // 이력서 작성 페이지로 이동
                    location.href = '/mypage/resume_list';
                }
            });
            $('.btn-close-popup').hide();
        });

        // 기업회원이 자신의 채용공고를 볼 때: 수정 버튼 클릭 이벤트
        $(document).on('click', '#btnEditRecruit', function() {
            var rcIdx = $("[name=rc_idx]").val();
            if (rcIdx) {
                location.href = "/compage/recruit_view/" + rcIdx;
            }
        });

       

        // 통화 로그 기록
        $(document).on('click', '#callLog', function() {
             // 채용정보 전화하기 분석
            //  if (typeof my_analytics !== 'undefined') {
            //     my_analytics.goal('recruit_call');
            // }
            var rc_idx = $(this).data('rc_idx');
            comAjax("/recruit_proc/call_log", { rc_idx: rc_idx }, function(ret) {});
        });

        // 재등록/마감/삭제 바텀시트 트리거
        $(document).on('click', '#btnReregisterRecruit', function(e) {
            e.preventDefault();
            openPanel(document.getElementById('reregisterSheet'));
        });
        $(document).on('click', '#btnEndRecruit', function(e) {
            e.preventDefault();
            openPanel(document.getElementById('endRecruitSheet'));
        });
        $(document).on('click', '#btnDeleteRecruit', function(e) {
            e.preventDefault();
            openPanel(document.getElementById('deleteRecruitSheet'));
        });

        // 재등록 확인
        window.confirmReregisterRecruit = function() {
            var rc_idx = $("[name=rc_idx]").val();
            comAjax("/compage_proc/recruit_reregister", { rc_idx: rc_idx }, function(ret) {
                closePanel(document.getElementById('reregisterSheet'));
                if (ret.code == "00") {
                    location.reload();
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });
        };

        // 마감 확인
        window.confirmEndRecruit = function() {
            var rc_idx = $("[name=rc_idx]").val();
            comAjax("/compage_proc/recruit_end", { rc_idx_list: rc_idx }, function(ret) {
                closePanel(document.getElementById('endRecruitSheet'));
                if (ret.code == "00") {
                    // 뒤로가기 히스토리 남기지 않음 (현재 페이지 대체)
                    location.replace("/compage/recruit_list");
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });
        };

        // 삭제 확인
          window.confirmDeleteRecruit = function() {
            var rc_idx = $("[name=rc_idx]").val();
            commonAlert('삭제하시겠습니까?', function() {
                comAjax("/compage_proc/recruit_del", { rc_idx_list: rc_idx }, function(ret) {
                    closePanel(document.getElementById('deleteRecruitSheet'));
                    if (ret.code == "00") {
                        location.replace("/compage/recruit_list");
                    } else {
                        commonAlert(ret.msg, function() {});
                    }
                });
            });
        };

    })();  // IIFE 종료 - 동적 로드 시 즉시 실행

</script>

<!-- 온보딩 튜토리얼 (DOMContentLoaded 밖, 최상위 레벨 - hr_post.php와 동일 패턴) -->
<script>
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

    function isNativeApp() {
        return !!(window.APP_NOWAPK || (window.webkit && window.webkit.messageHandlers));
    }

    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // 트리거: 목록에서 클릭 진입 + 최초 1회만 표시 (네이티브 앱에서만, 미리보기 제외, 공유 링크 제외)
    var isPreviewMode = <?= json_encode($isPreview) ?>;
    var isSharedLink = new URLSearchParams(location.search).get('shared') === 'true';
   
    if (!isPreviewMode && !isSharedLink && isNativeApp() && sessionStorage.getItem('recruit_came_from_job_post') === 'true') {
        if (!localStorage.getItem('job_post_tutorial_seen')) {
            localStorage.setItem('job_post_tutorial_seen', 'true');
            window.openJobPostTutorial();
        }
    }
</script>

<!-- 비회원 로그인 안내 패널 (job_post.php 내부) -->
<?php
renderBottomSheet(
  'loginGuidePanelJobPost',
  '로그인 안내',
  '<p>로그인이 필요한 서비스입니다.</p><p>로그인 하시겠습니까?</p>',
  '로그인 및 회원가입',
  'btnLoginGuideJobPost',
  'location.href = "/member/login";',
  'bottom-sheet-content-container',
  'bottom-sheet-content-body'
);
?>

<!-- 비회원 로그인 안내 팝업 (팝업 컨텍스트 내부 패널 열기) -->
<script>
    // 팝업 내부의 로그인 패널을 직접 열기
    window.showLoginGuideFromPopup = function() {
        var loginPanel = document.getElementById('loginGuidePanelJobPost');
        if (!loginPanel) {
            console.error('[job_post.php] loginGuidePanelJobPost를 찾을 수 없음');
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
                var popup = loginPanel.closest('#jobPostPopup') || loginPanel.parentNode;
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
            initPanelSwipe('loginGuidePanelJobPost');
            loginPanel.setAttribute('data-swipe-initialized', 'true');
        }
    };
</script>