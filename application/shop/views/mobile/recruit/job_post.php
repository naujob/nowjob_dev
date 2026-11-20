<?php
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_template.php');
require_once(get_base_url() . '/application/shop/views/mobile/_partial/detail_v2_helpers.php');
require_once(get_base_url() . '/application/shop/views/mobile/_partial/job_post_fields.php');

// formatPhoneNumber() 은 _partial/job_post_fields.php 로 옮겼다 (JSON 응답에서도 필요)

$isPopup = isset($is_popup) ? $is_popup : false;
?>
<input type="hidden" name="rc_idx" value="<?= $info["rc_idx"] ?>" />
<?= getCachCss("/resource/shop/mobile/css/m_detail_v2.css") ?>
<?= getCachCss("/resource/shop/mobile/css/m_job_post_v2.css") ?><?= getCashJs("/resource/shop/mobile/js/m_detail_v2.js") ?>
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
    $restrict_manage_msg = t('기업회원만 가능한 기능입니다.');
} elseif ($userType === 'COMP' && !$isOwnRecruit) {
    $restrict_manage_msg = t('본인의 공고만 가능한 기능입니다.');
}
?>
<div class="post page page-view jd-page">
    <div class="jd-top">
        <a href="#" class="jd-top-btn" aria-label="<?= t('이전으로') ?>" onclick="<?= $isPopup ? 'closeJobPostPopup(event)' : 'goBackToEdit(event)' ?>"><?= jdIcon("back") ?></a>
        <h1 class="jd-top-title"><?= t('채용공고') ?></h1>
        <span class="jd-top-rt">
            <?php if ($mm_type === "MEM") { ?>
                <button type="button" data-bk_type="RECRUIT" data-bk_key="<?= $info["rc_idx"] ?>" data-user_id="<?= $mem_info["mm_id"] ?>" class="jd-top-btn jd-fav btnBookMark js-toggle-bookmark <?= $book_yn == "Y" ? "on" : "" ?>"><span class="sr-text"><?= t('즐겨찾기 추가') ?></span><?= jdIcon("heart") ?></button>
            <?php } ?>
            <a href="#sharePanel" aria-haspopup="dialog" aria-controls="sharePanel" class="jd-top-btn js-toggle-panel" aria-label="<?= t('공유하기') ?>"><?= jdIcon("share") ?></a>
        </span>
    </div>

    <div class="jd-body">
    <!-- 앱 딥링크 -->
    <?= getCashJs("/resource/shop/mobile/js/app-deeplink.js") ?>

    <script>
        var rcIdx = '<?= $info['rc_idx'] ?>';
        var customShareUrl = location.origin + '/recruit/job_post/' + rcIdx + '?shared=true';
        var isPreview = '<?= $isPreview ? "true" : "false" ?>' === "true";

        // 공고 목록 페이지에서 돌아올 때 하이라이트 처리
        sessionStorage.setItem('recruit_highlight_rc_idx', rcIdx);
        sessionStorage.setItem('recruit_came_from_job_post', 'true');

        // 지도(job_map)에서 카드를 눌러 들어온 경우 표시를 한 번 읽고 지운다 (이후 다른 경로 진입에 남지 않도록)
        var jpFromJobMap = false;
        try {
            jpFromJobMap = sessionStorage.getItem('job_back_from') === 'job_map';
            sessionStorage.removeItem('job_back_from');
        } catch (jpStorageErr) {}

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
                // 공고 목록(job_list) 또는 지도(job_map)에서 들어온 경우 브라우저 히스토리대로 뒤로 이동
                } else if (jpFromJobMap && history.length > 1) {
                    // 지도에서 카드를 눌러 들어온 경우: 출발 표시 기준으로 바로 뒤로 이동 (referrer 미사용)
                    history.back();
                } else if (history.length > 1 && (document.referrer.indexOf(location.host + '/recruit/job_list') !== -1 || document.referrer.indexOf(location.host + '/recruit/job_map') !== -1)) {
                    history.back();
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

    <div class="page-content jd">
        <?php
        // 화면 표시값 (2a 단계): 뷰와 format=data 응답이 같은 함수(_partial/job_post_fields.php)로 만든다
        $jdf = jobPostFields($info, $mem_info, $addr, $auto_notice_text);
        ?>

        <div class="view-visual-slider jd-photo">
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
                    <div class="keen-slider__slide" style="--bg:url('<?= htmlspecialchars($_img_display, ENT_QUOTES, 'UTF-8') ?>')">
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

        <div class="jd-head">
            <p class="jd-meta">
                <span>No.<?= $info["rc_idx"] ?></span>
                <span><span class="sr-text"><?= t('조회수') ?></span><?= jdIcon("eye", "jd-i s") ?><?= $info["rc_hit"] ?></span>
                <span><span class="sr-text"><?= t('총 즐겨찾기수') ?></span><?= jdIcon("heart", "jd-i s") ?><?= $info["fav_cnt"] ?></span>
            </p>
            <h2 class="jd-title view-title" data-field="rc_name"><?= $info["rc_name"] ?></h2>
            <p class="jd-period">
                <span class="sr-text"><?= t('모집기간') ?></span><?= jdIcon("cal", "jd-i s") ?>
                <b><?= get_yyyymmdd($info["rc_sdate"]) ?> ~ <?= get_yyyymmdd($info["rc_edate"]) ?></b>
                <?php if ($jdf["dday_txt"] !== "") : ?><span class="jd-dday<?= $jdf["dday_closed"] ? " is-closed" : "" ?>" data-field="dday_txt"><?= $jdf["dday_txt"] ?></span><?php endif; ?>
            </p>
            <div class="jd-keys">
                <div class="jd-key wide">
                    <p class="k"><?= jdIcon("won", "jd-i k") ?><?= t('급여') ?></p>
                    <p class="v" data-field="pay_html"><?= $jdf["pay_html"] ?></p>
                </div>
                <div class="jd-key">
                    <p class="k"><?= jdIcon("clock", "jd-i k") ?><?= t('근무형태') ?></p>
                    <p class="v" data-field="wt_card_html"><?= $jdf["wt_card_html"] ?></p>
                </div>
                <div class="jd-key">
                    <p class="k"><?= jdIcon("cal", "jd-i k") ?><?= t('근무요일') ?></p>
                    <p class="v" data-field="week_html"><?= $jdf["week_html"] ?></p>
                </div>
                <div class="jd-key wide">
                    <p class="k"><?= jdIcon("pin", "jd-i k") ?><?= t('근무지') ?></p>
                    <p class="v" data-jd="area" data-field="area_txt"><?= $jdf["area_txt"] ?></p>
                </div>
            </div>
        </div>

        <nav class="jd-tabs" aria-label="<?= t('공고 내용 바로가기') ?>">
            <a onclick="return false" href="#jdInfo" class="on"><?= t('구인정보') ?></a>
            <?php if ($jdf["has_ben"]) : ?><a onclick="return false" href="#jdBenefit"><?= t('복리후생') ?></a><?php endif; ?>
            <a onclick="return false" href="#jdDesc"><?= t('상세업무') ?></a>
            <a onclick="return false" href="#jdLoc"><?= t('근무지') ?></a>
        </nav>

        <section class="jd-sec" id="jdInfo">
            <h3 class="jd-h"><?= t('구인정보') ?></h3>
            <dl class="jd-rows">
                <div class="jd-row"><dt><?= t('업무구분') ?></dt><dd class="is-list"><?= jdWorkText($info["rc_work"]) ?></dd></div>
                <div class="jd-row"><dt><?= t('급여형태') ?></dt><dd data-field="pay_detail_html"><?= $jdf["pay_detail_html"] ?></dd></div>
                <div class="jd-row"><dt><?= t('근무형태') ?></dt><dd class="jd-wt" data-field="wt_rows_html"><?= $jdf["wt_rows_html"] ?></dd></div>
                <?php // 성별·국적 값은 비교는 한국어 원본으로 하고, 화면에 낼 때만 t_list()로 항목별 번역 ?>
                <div class="jd-row"><dt><?= t('성별') ?></dt><dd data-field="sex_txt"><?= $jdf["sex_txt"] ?></dd></div>
                <div class="jd-row"><dt><?= t('국적') ?></dt><dd data-field="nation_txt"><?= $jdf["nation_txt"] ?></dd></div>
                <div class="jd-row"><dt><?= t('나이') ?></dt><dd data-field="age_txt"><?= $jdf["age_txt"] ?></dd></div>
                <div class="jd-row"><dt><?= t('근무요일') ?></dt><dd data-field="week_days_html"><?= $jdf["week_days_html"] ?></dd></div>
            </dl>
        </section>

        <?php if ($jdf["has_ben"]) : ?>
        <section class="jd-sec" id="jdBenefit">
            <h3 class="jd-h"><?= t('복리후생') ?></h3>
            <ul class="benefit-group jd-ben" data-field="ben_html"><?= $jdf["ben_html"] ?></ul>
        </section>
        <?php endif; ?>

        <section class="jd-sec view-desc" id="jdDesc">
            <div class="jd-h-row">
                <h3 class="jd-h"><?= t('상세업무정보') ?></h3>
                <button type="button" class="jd-zoom detail-recruit-content__preview" aria-label="<?= t('새 창으로 크게 보기') ?>"><?= jdIcon("zoom", "jd-i s") ?><?= t('크게 보기') ?></button>
            </div>
            <div class="jd-desc">
                <div class="desc-clip">
                    <div class="desc">
                        <div class="desc-content" data-field="rc_desc"><?= $info["rc_desc"] ?></div>
                    </div>
                </div>
                <span data-field="hash_html" style="display:contents"><?= $jdf["hash_html"] ?></span>
            </div>
            <?php // 자동번역 안내 (jd-desc 박스 밖, 아래). 문구가 없으면 숨김 상태로 두어, 언어 전환 때 JS가 채울 수 있게 한다 ?>
            <p class="auto-translate-notice" data-field="auto_notice_text"<?= $jdf["auto_notice_text"] === '' ? ' style="display:none"' : '' ?>><?= $jdf["auto_notice_text"] ?></p>
        </section>

        <!-- 신고하기 양식 -->
        <template id="ReportTemp">
            <div class="alert-msg">
                <p><?= t('해당게시글을 신고하시겠습니까?') ?><br> <?= t('신고사유를 작성해주세요') ?></p>
                <div class="report-form">
                    <label>
                        <span class="sr-text"><?= t('신고사유입력') ?></span>
                        <textarea name="br_desc" placeholder="<?= t('신고사유를 작성해주세요') ?>" class="form-textarea js-autoheight"></textarea>
                    </label>
                </div>
            </div>
        </template>
        <!-- //신고하기 양식 -->

        <script>
            /* 신고내용 작성 팝업 */
            $(document).off('click.jdReport').on('click.jdReport', '.jd-page .js-report', function() {
                var content = document.querySelector('#ReportTemp').innerHTML;
                openPopup('modal', {
                    id: 'reportForm',
                    title: t('신고하기'),
                    content: content,
                    firstButton: t('확인'),
                    firstCallback: function() {
                        if ($("[name=br_desc]").val() == "") {
                            commonAlert(t("내용을 입력해주세요."), function() {
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
                                commonAlert(t("신고 처리 되었습니다."), function() {});
                            } else {
                                commonAlert(t("신고 처리시 오류가 발생하였습니다."), function() {});
                            }
                        });
                    }
                });
            });
        </script>

        <section class="jd-sec" id="jdLoc">
            <h3 class="jd-h"><?= t('근무지') ?></h3>
            <ul class="jd-cards">
                <li><span class="jd-cic"><?= jdIcon("bld") ?></span><b data-jd="comp" data-field="comp_txt"><?= $jdf["comp_txt"] ?></b></li>
                <li><span class="jd-cic"><?= jdIcon("phone") ?></span><b data-jd="phone" data-field="phone_txt"><?= $jdf["phone_txt"] ?></b></li>
                <li><span class="jd-cic"><?= jdIcon("pin") ?></span><b data-jd="addr" data-field="addr_html"><?= $jdf["addr_html"] ?></b></li>
            </ul>
            <?php
            // preview 모드에서도 지도 초기화를 위해 좌표 설정 ($addr이 없으면 draftData로 JavaScript에서 복원)
            $gps_y = (isset($addr["ad_gps_y"]) && $addr["ad_gps_y"]) ? $addr["ad_gps_y"] : '';
            $gps_x = (isset($addr["ad_gps_x"]) && $addr["ad_gps_x"]) ? $addr["ad_gps_x"] : '';
            $initial_geocoder = ($gps_y && $gps_x) ? "$gps_y,$gps_x" : '';
            ?>
            <div class="jd-map"<?= $initial_geocoder ? '' : ' hidden' ?>>
                <input type="hidden" id="geocoder" value="<?= $initial_geocoder ?>">
                <div id="jsMap" class="js-map"></div>
            </div>
            <?php if (empty($g_ios_publish)) : ?>
                <div class="jd-route" aria-label="<?= t('길찾기') ?>" data-name="<?= htmlspecialchars($jdf["comp_txt"], ENT_QUOTES, 'UTF-8') ?>">
                    <a href="#" data-route="kakao" aria-label="<?= t('카카오맵으로 길찾기') ?>"><img src="/resource/shop/mobile/img/map_kakao.png" alt=""><?= t('카카오맵') ?></a>
                    <a href="#" data-route="naver" aria-label="<?= t('네이버지도로 길찾기') ?>"><img src="/resource/shop/mobile/img/map_naver.png" alt=""><?= t('네이버지도') ?></a>
                    <a href="#" data-route="tmap" aria-label="<?= t('티맵으로 길찾기') ?>"><img src="/resource/shop/mobile/img/mpa_tmap.png" alt=""><?= t('티맵') ?></a>
                </div>
            <?php endif; ?>
            <?php if (!$isPreview && !$isOwnRecruit) : ?>
                <button type="button" class="jd-report js-report"><?= t('이 공고 신고하기') ?></button>
            <?php endif; ?>

            <script>
                // 지도 초기화 함수 (SDK 로드 완료 후 호출됨)
                window.initKakaoMap = function() {
                    var mapContainer = document.getElementById('jsMap');
                    if (!mapContainer) return;
                    // 이미 지도가 그려진 컨테이너면 다시 만들지 않는다 (언어 전환 시 기존 지도 노드를 그대로 쓰므로 타일을 다시 받지 않게 함)
                    if (mapContainer.getAttribute('data-map-ready') === 'true') return;

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
                    // 지도가 그려졌다는 표시 (다음 제자리 갱신 때 같은 노드를 쓰면서 다시 그리지 않도록)
                    mapContainer.setAttribute('data-map-ready', 'true');
                };

                // SDK 로드(또는 재사용) 후 지도 초기화 — popup/non-popup 양쪽에서 동일하게 동작
                if (typeof window.loadKakaoMapSdk === 'function') {
                    window.loadKakaoMapSdk('<?= $kakao_key["인증키"] ?>', function() {
                        window.initKakaoMap();
                    });
                }

                // 길찾기 버튼
                $(document).off('click.jdRoute').on('click.jdRoute', '.jd-page .jd-route a', function(e) {
                    e.preventDefault();
                    var ll = (document.getElementById('geocoder').value || '').split(','),
                        name = $(this).closest('.jd-route').attr('data-name') || '',
                        fn = { kakao: window.kakaoMap, naver: window.naverMap, tmap: window.tMap }[$(this).attr('data-route')];
                    if (ll.length < 2 || !ll[0]) {
                        commonAlert(t("위치 정보가 없습니다."), function() {});
                        return;
                    }
                    if (typeof fn === 'function') fn(name, ll[0], ll[1]);
                });
            </script>
        </section>

        <!-- 미리보기 모드 footer 버튼 -->
        <?php if ($isPreview) { ?>
        <div class="view-footer">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()"><?= t('수정하기') ?></button>
            <button type="button" class="btn btn-primary" onclick="submitPreview()"><?= t('등록하기') ?></button>
        </div>
        <?php } ?>

        <!-- 플로팅 액션 버튼 그룹 -->
        <?php if (!$isPreview) : ?>
        <div id="floating-actions" data-state="open" <?php echo ($userType === "COMP" && $isOwnRecruit) ? 'data-own-recruit="true"' : ''; ?>>

            <!-- 언어 전환: 플로팅 그룹 맨 위 (floating-action-item 위쪽) -->
            <?php $lang_switch_variant = 'stack'; include(APPPATH . 'views/mobile/_partial/lang_switch.php'); ?>

            <!-- 지원하기/채팅하기/전화하기: 기업회원 본인 공고에서는 노출하지 않음 (관리 기능만 제공) -->
            <?php if (!$isOwnRecruit) : ?>

            <!-- 지원하기 -->
            <?php if (!$isLogin) : ?>
                <!-- 비로그인: 로그인 안내 -->
                <a href="#" class="floating-action-item" onclick="window.showLoginGuideFromPopup(); return false;" data-action="propose" title="지원">
                    <img src="/resource/shop/mobile/img/propose.png" alt="<?= t('지원') ?>">
                    <span class="action-label"><?= t('지원하기') ?></span>
                </a>
            <?php elseif ($userType === 'MEM') : ?>
                <!-- 개인회원: 정상 동작 -->
                <a href="#" id="btnRequest" class="floating-action-item btnRequest" data-action="propose" title="제안">
                    <img src="/resource/shop/mobile/img/propose.png" alt="<?= t('지원') ?>">
                    <span class="action-label"><?= t('지원하기') ?></span>
                </a>
            <?php else : ?>
                <!-- 기업회원: 차단 -->
                <a href="#" class="floating-action-item" onclick="commonAlert(t('개인회원만 가능한 기능입니다.'), function(){}); return false;" data-action="propose" title="지원">
                    <img src="/resource/shop/mobile/img/propose.png" alt="<?= t('지원') ?>">
                    <span class="action-label"><?= t('지원하기') ?></span>
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
                        <?php // data-action 값(위)은 한국어 원본 그대로 두고, 보이는 글자만 번역 ?>
                        <img src="<?= $action['img'] ?>" alt="<?= t($action['label']) ?>">
                        <span class="action-label"><?= t($action['label']) ?></span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- 전화하기 (내 공고 제외 모두에게 노출, 로그인 게이트 없음) -->
            <?php if (!$isOwnRecruit) : ?>
            <a id="callLog" href="tel:<?= isset($mem_info["mm_phone"]) ? htmlspecialchars($mem_info["mm_phone"], ENT_QUOTES, 'UTF-8') : '' ?>" class="floating-action-item js-call-log" data-rc_idx="<?= $info["rc_idx"] ?>" data-action="call" title="전화">
                <img src="/resource/shop/mobile/img/call-post.png" alt="<?= t('전화') ?>">
                <span class="action-label"><?= t('전화하기') ?></span>
            </a>
            <?php endif; ?>
        </div><!-- //#floating-actions -->
        <?php endif; ?>

        <!-- 기업회원 본인 공고 바텀시트 3개 -->
        <?php if (!$isPreview && $restrict_manage_msg === null) : ?>
            <?php renderSelectBottomSheet(
                'reregisterSheet',
                t('채용공고 재등록'),
                '<p>' . t('모집기간이 오늘부터 60일간으로 재설정되어 공고가 다시 등록됩니다.') . '<br>' . t('재등록하시겠습니까?') . '</p>',
                t('취소'), 'btnReregisterCancel', "closePanel(document.getElementById('reregisterSheet'));",
                t('재등록'), 'btnReregisterConfirm', 'confirmReregisterRecruit();'
            ); ?>
            <?php renderSelectBottomSheet(
                'endRecruitSheet',
                t('채용공고 마감'),
                '<p>' . t('해당 공고를 마감 처리하시겠습니까?') . '</p>',
                t('취소'), 'btnEndCancel', "closePanel(document.getElementById('endRecruitSheet'));",
                t('마감'), 'btnEndConfirm', 'confirmEndRecruit();'
            ); ?>
            <?php renderSelectBottomSheet(
                'deleteRecruitSheet',
                t('채용공고 삭제'),
                '<p>' . t('삭제된 공고는 복구할 수 없습니다.') . '<br>' . t('완전 삭제하시겠습니까?') . '</p>',
                t('취소'), 'btnDeleteCancel', "closePanel(document.getElementById('deleteRecruitSheet'));",
                t('삭제'), 'btnDeleteConfirm', 'confirmDeleteRecruit();'
            ); ?>
        <?php endif; ?>

    </div><!-- //page-content -->
    </div>

<?php if (!$isPopup) { ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/app_intercept_layer.php'); ?>
<?php } ?>
</div>

<!-- 공유하기 팝업 -->
<div id="sharePanel" class="layer-panel card-type" tabindex="-1">
    <div class="bottom-sheet-container">
        <!-- 핸들바 + X 버튼: 다른 바텀시트와 같은 형태 -->
        <div class="bottom-sheet-handlebar"></div>
        <button type="button" class="bottom-sheet-close-btn" aria-label="<?= t('닫기') ?>" onclick="closePanel(document.getElementById('sharePanel'));"></button>
        <div class="bottom-sheet-header">
            <h2 class="bottom-sheet-title"><?= t('공유하기') ?></h2>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="vendor-links">
                    <!-- 카카오톡: 카드로 공유 (앱이면 카카오톡 공유 화면, 브라우저면 카카오 웹 공유) -->
                    <a href="javascript:void(0);" onclick="shareKakaoFromPanel();return false;" class="share-link1">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_vendor1.svg" alt=""></span>
                        <span><?= t('카카오톡') ?></span>
                    </a>
                    <!-- 다른 앱: 기기 공유창(메모장, 메시지 등) -->
                    <a href="javascript:void(0);" onclick="shareOtherAppsFromPanel();return false;" class="share-link2 share-other">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_copy_url.svg" alt=""></span>
                        <span><?= t('다른 앱으로 공유') ?></span>
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
            alert(t("모바일 앱에서만 길안내가 가능합니다."));
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
            alert(t("모바일 앱에서만 길안내가 가능합니다."));
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
                    commonAlert(t("표시할 내용이 없습니다."), function() {});
                    return;
                }

                var previewWindow = window.open('', '_blank', 'width=900,height=700,scrollbars=yes');
                if (!previewWindow) {
                    commonAlert(t("팝업 차단이 해제되어야 열 수 있습니다."), function() {});
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

            // 근무지 카드 업데이트
            var jdSet = function(key, text) {
                var el = document.querySelector('.jd-page [data-jd="' + key + '"]');
                if (el && text) el.textContent = text;
            };
            jdSet('comp', previewMemInfo.mm_comp_name);
            jdSet('phone', previewMemInfo.mm_phone);
            if (draftData.ad_jibun) {
                jdSet('addr', draftData.ad_jibun + (draftData.ad_detail ? ' ' + draftData.ad_detail : ''));
                if (typeof fRemoveBigAdrJS === 'function') jdSet('area', fRemoveBigAdrJS(draftData.ad_jibun));
            }

            // 상세업무정보 (rc_desc) 업데이트
            if (draftData.rc_desc) {
                var descEl = document.querySelector('.view-desc .desc');
                if (descEl) {
                    // contenteditable div의 HTML을 그대로 사용 (\n을 <br>로 변환)
                    var descHtml = draftData.rc_desc.replace(/\n/g, '<br>');
                    descEl.innerHTML = '<div class="desc-content">' + descHtml + '</div>';
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
                    if (mapDiv && mapDiv.hasAttribute('hidden')) {
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

        <?php if (!$isPopup && !$isPreview) : ?>
        // 독립 페이지(채용공고 상세) 언어 전환: 새로고침 대신 본문(.jd-page)만 새 언어로 바꾼다
        // 슬라이드(.jd-photo)·지도(.jd-map)는 언어와 무관하므로 기존 노드를 옮겨 끼워 이미지·지도를 다시 받지 않는다
        // 팝업(openJobPostPopup)과 미리보기는 이 블록을 타지 않는다 (기존 방식 유지)
        // 언어 전환: JSON 표시값 경로(JOB_POST_JSON_REFRESH가 켜졌을 때만, 실패하면 HTML 경로로 돌아간다)
        window.onLangSwitch = function(newLang, done) {
            var jsonRoot = document.querySelector('.jd-page');
            if (window.JOB_POST_JSON_REFRESH && jsonRoot && typeof window.refreshJobPostPopupText === 'function') {
                window.refreshJobPostPopupText(rcIdx, function() { jpHtmlRefresh(done); }, jsonRoot, done);
                return;
            }
            jpHtmlRefresh(done);
        };

        // HTML 경로: .jd-page 를 새 언어 HTML로 교체하고 슬라이드·지도 노드는 그대로 옮겨 끼운다
        function jpHtmlRefresh(done) {
            var root = document.querySelector('.jd-page');
            if (!root || !window.DOMParser) {
                location.reload();
                return;
            }
            // 바꾸기 전에 스크롤 위치를 기억해 둔다 (교체 후 복원)
            var prevBody = root.querySelector('.jd-body');
            var prevBodyY = prevBody ? prevBody.scrollTop : 0;
            var prevWinY = window.pageYOffset || 0;

            // 언어 전환 재요청(lang_refresh=1)은 조회수를 올리지 않는다
            var refreshUrl = location.pathname + (location.search ? location.search + '&' : '?') + 'lang_refresh=1';
            $.ajax({ url: refreshUrl, cache: false, dataType: 'html' }).done(function(html) {
                // DOMParser 문서는 이미지·스크립트를 불러오지 않으므로 여기서 요청이 나가지 않는다
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var nextRoot = doc.querySelector('.jd-page');
                var keepSels = { photo: '.jd-photo', map: '.jd-map' };
                var keptNodes = {};
                var key;

                // 기존 슬라이드·지도 노드를 떼어 둔다 (없으면 안전하게 전체 새로고침)
                for (key in keepSels) {
                    keptNodes[key] = root.querySelector(keepSels[key]);
                    if (!keptNodes[key] || !nextRoot || !nextRoot.querySelector(keepSels[key])) {
                        location.reload();
                        return;
                    }
                }

                // 새 HTML의 슬라이드·지도 자리에는 표시(placeholder)만 남긴다
                for (key in keepSels) {
                    var nextKeep = nextRoot.querySelector(keepSels[key]);
                    var placeholder = doc.createElement('div');
                    placeholder.setAttribute('data-keep', key);
                    nextKeep.parentNode.replaceChild(placeholder, nextKeep);
                }
                // 스타일시트도 다시 받지 않는다: 이 페이지에 이미 붙어 있는 CSS이므로 응답의 link는 뺀다
                var nextLinks = nextRoot.querySelectorAll('link[rel="stylesheet"]');
                for (var l = nextLinks.length - 1; l >= 0; l--) {
                    nextLinks[l].parentNode.removeChild(nextLinks[l]);
                }
                // 스크립트는 다시 실행하지 않는다 (슬라이더·지도·이벤트는 이미 붙어 있음)
                var scripts = nextRoot.querySelectorAll('script');
                for (var s = scripts.length - 1; s >= 0; s--) {
                    scripts[s].parentNode.removeChild(scripts[s]);
                }

                // 본문 교체 후 기존 슬라이드·지도 노드를 같은 자리에 되돌려 끼운다
                var newRoot = document.importNode(nextRoot, true);
                root.parentNode.replaceChild(newRoot, root);
                for (key in keepSels) {
                    var ph = newRoot.querySelector('[data-keep="' + key + '"]');
                    if (ph) ph.parentNode.replaceChild(keptNodes[key], ph);
                }

                // 설명 영역 터치·확대 기능은 새 노드에 다시 붙인다 (이전 바인딩은 사라짐)
                if (typeof window.initJobPostDescFeatures === 'function') {
                    window.initJobPostDescFeatures();
                }

                var nextBody = newRoot.querySelector('.jd-body');
                if (nextBody) nextBody.scrollTop = prevBodyY;
                window.scrollTo(0, prevWinY);

                if (typeof done === 'function') done();
            }).fail(function() {
                location.reload();
            });
        };
        <?php endif; ?>

        $('.view-visual-slider').magnificPopup({
            delegate: 'img',
            type: 'image',
            showCloseBtn: false,
            closeOnContentClick: true,
            gallery: {
                enabled: true,
                navigateByImgClick: false
            }
        });

        var user_type = "<?= $this->user->getUserType(); ?>";
        // 상세정보 확대/핀치줌 기능 초기화
        if (typeof window.initJobPostDescFeatures === 'function') {
            window.initJobPostDescFeatures();
        }
        // 모집 마감된 공고 여부 (마감 시 지원 버튼 즉시 차단)
        var isClosedRecruit = $("#is_closed").val() === "Y";

        // 제자리 언어 갱신 시 스크립트가 다시 실행되므로 이전 핸들러를 먼저 떼고 붙인다 (중복 실행 방지)
        $(document).off('click', '#btnRecruit, #btnRequest').on('click', '#btnRecruit, #btnRequest', function(e) {
            // 채용정보 지원하기 분석
            //  if (typeof my_analytics !== 'undefined') {
            //     my_analytics.goal('recruit_recruit');
            // }
            if ($(this).attr('href') === '#') {
                e.preventDefault();
            }
            if (isClosedRecruit) {
                commonAlert(t("마감된 공고입니다"), function() {});
                return;
            }
            //로그인 여부 확인
            if ($("#is_login").val() != "Y") {
                //commonLogin();

                commonAlert(t("로그인후 이용해주세요!"), function() {
                    location.href = '/member/login';
                });

                return;
            }

            if (user_type != "MEM") {
                commonAlert(t("기업 회원은 지원 할 수 없습니다."), function() {});
                return;
            }

            //이력서 여부 확인
            if ($("[name=resume_yn]").val() == "N") {
                openPopup('alert', {
                    content: t("이력서 작성및 공개이력서 설정후 지원해주세요."),
                    firstButton: t('이력서 등록'),
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
                        content: '<em>“' + $("[name=rc_name]").val() + '”</em><br> ' + t('으로 지원하시겠습니까?'),
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
                                    commonAlert(t("지원이 완료되었습니다."), function() {});
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
                commonAlert(t("로그인 후 이용해주세요."), function() {
                    location.href = '/member/login';
                });
                return;
            }

            // 이력서 작성 확인 팝업
            openPopup('confirm', {
                content: t('이력서 작성 페이지로<br> 이동하시겠습니까?'),
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
        $(document).off('click', '#btnEditRecruit').on('click', '#btnEditRecruit', function() {
            var rcIdx = $("[name=rc_idx]").val();
            if (rcIdx) {
                location.href = "/compage/recruit_view/" + rcIdx;
            }
        });

       

        // 통화 로그 기록
        $(document).off('click', '#callLog').on('click', '#callLog', function() {
             // 채용정보 전화하기 분석
            //  if (typeof my_analytics !== 'undefined') {
            //     my_analytics.goal('recruit_call');
            // }
            var rc_idx = $(this).data('rc_idx');
            comAjax("/recruit_proc/call_log", { rc_idx: rc_idx }, function(ret) {});
        });

        // 재등록/마감/삭제 바텀시트 트리거
        $(document).off('click', '#btnReregisterRecruit').on('click', '#btnReregisterRecruit', function(e) {
            e.preventDefault();
            openPanel(document.getElementById('reregisterSheet'));
        });
        $(document).off('click', '#btnEndRecruit').on('click', '#btnEndRecruit', function(e) {
            e.preventDefault();
            openPanel(document.getElementById('endRecruitSheet'));
        });
        $(document).off('click', '#btnDeleteRecruit').on('click', '#btnDeleteRecruit', function(e) {
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

    if (typeof window.mDetailV2Init === 'function') window.mDetailV2Init();

</script>

<!-- 온보딩 튜토리얼 (DOMContentLoaded 밖, 최상위 레벨 - hr_post.php와 동일 패턴) -->
<script>
    var JP_CURRENT_PAGE = 0;
    var JP_TOTAL_PAGES = 2;
    var jpPageTimeline = null;

    function jpPreventScroll(e) { e.preventDefault(); }

    window.openJobPostTutorial = function() {
        // 스위치가 꺼져 있으면 호출 경로와 무관하게 팝업을 띄우지 않는다 (미정의 상태도 꺼진 것으로 취급)
        if (typeof TUTORIAL_POPUP_ENABLED === 'undefined' || !TUTORIAL_POPUP_ENABLED) return;
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

    // 온보딩 튜토리얼 노출 임시 중단 스위치 (false면 자동 노출 안 함, 다시 켜려면 true)
    var TUTORIAL_POPUP_ENABLED = false;

    // 트리거: 목록에서 클릭 진입 + 최초 1회만 표시 (네이티브 앱에서만, 미리보기 제외, 공유 링크 제외)
    var isPreviewMode = <?= json_encode($isPreview) ?>;
    var isSharedLink = new URLSearchParams(location.search).get('shared') === 'true';

    if (TUTORIAL_POPUP_ENABLED && !isPreviewMode && !isSharedLink && isNativeApp() && sessionStorage.getItem('recruit_came_from_job_post') === 'true') {
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
  '<p>' . t('로그인이 필요한 서비스입니다.') . '</p><p>' . t('로그인 하시겠습니까?') . '</p>',
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