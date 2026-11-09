<?php
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_template.php');
?>
<style>
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

    .info-table .pricetype-row .pricetype em {
        position: relative;
        display: inline-block;
        font-style: normal;
        color: #777;
    }

    .info-table .pricetype-row .pricetype em:first-child,
    .info-table .pricetype-row .pricetype em.on {
        color: #1b9d7d;
    }

    .info-table .pricetype-row .pricetype em:not(:last-child) {
        padding-right: 8px;
    }

    .info-table .pricetype-row .pricetype em:not(:first-child) {
        padding-left: 2px;
    }

    /* main.css의 .page.page-view .info-table .value(flex, gap 8px)가 em 사이에 추가 간격을 만들어 끔 */
    .page.page-view .info-table .pricetype-row .pricetype {
        gap: 0;
    }

    /* 지역 목록: 쉼표 대신 구분선으로 구분 (급여형태와 같은 간격/구분선 규칙) */
    #addr-display {
        gap: 0;
    }

    #addr-display .addr-item {
        position: relative;
        display: inline-block;
    }

    #addr-display .addr-item:not(:last-child) {
        padding-right: 8px;
    }

    #addr-display .addr-item:not(:first-child) {
        padding-left: 2px;
    }

    #addr-display .addr-item:not(:last-child)::after {
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

    .info-table .pricetype-row .pricetype em:not(:last-child)::after {
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

    /* 공유 패널: 카카오톡 / 다른 앱으로 공유를 한 줄에 나란히 배치 (hr_post.php와 동일) */
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

    #sharePanel .vendor-links .share-other .ico img {
        box-sizing: border-box;
        padding: 0;
    }

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
</style>
<?php
// 개인회원이 자신의 이력서를 볼 경우를 판단
$isOwnResume = ($this->user->getUserType() === "MEM" && isset($mem_info["mm_idx"]) && $mem_info["mm_idx"] == $this->user->getUserIdx());
?>
<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이력서</h1>
        <span class="rt-group">
            <a href="#sharePanel" aria-haspopup="dialog" aria-controls="sharePanel" class="btn-share js-toggle-panel"><span class="sr-text">공유하기</span></a>
        </span>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="section view-header">
            <div class="profile">
                <div class="container">
                    <div class="thumb-wrap">
                        <label class="form-pic">
                            <input type="file" name="profile2" accept="image/*" class="js-file-preview">
                            <span class="preview"><img data-src="<?= getMemberImg($mem_info) ?>" alt="" class="lozad"></span>
                        </label>
                        <!-- 평점 -->
                        <p class="star-rating v2">
                            <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                            <span class="star" style="width:80%;"></span>
                            <span class="sr-text">평점</span>
                            <span class="sr-text">5</span>
                        </p>
                    </div>
                    <div class="profile-detail">
                        <!-- 이름 -->
                        <p class="name"><em><?= $mem_info["mm_name"] ?></em>님</p>
                        <div class="meta">
                            <!-- 연락처 -->
                            <p class="phone"><?= $mem_info["mm_phone"] ?></p>
                            <!-- 메일주소 -->
                            <!-- <p class="email"><?= $mem_info["mm_id"] ?></p> -->
                            <!-- 포인트 -->
                            <p class="point"><?= number_format($mem_info["mm_point"]) ?>P</p>
                        </div>
                    </div>
                    <ul class="profile-record">
                        <li><span class="label">프로필 조회</span><em class="value"><?= $info["ru_hit"] ?></em></li>
                        <li><span class="label">받은제안</span><em class="value"><?= $info["req_cnt"] ?></em></li>
                        <li><span class="label">관심인재</span><em class="value"><?= $info["fav_cnt"] ?></em></li>
                    </ul>
                </div>
            </div>
            <!-- 20220922 수정 -->
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
                                    <?php foreach ($addr as $key => $ad) : ?>
                                        <?php if ($ad["ad_addr"] != "") {
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
                </div>
            </div>
        </div>
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
                            <td class="label">경력사항</td>
                            <td class="value">
                                <ul class="period-list">
                                    <?php foreach ($career_list as $row) : ?>
                                            <!-- <?php
                                                echo "<pre>";
                                                print_r($row);
                                                echo "</pre>";
                                            ?> -->

                                        <li>
                                            <span><?= isset($row["rc_name"]) ? htmlspecialchars($row["rc_name"], ENT_QUOTES, 'UTF-8') : "(회사명 없음)" ?></span>
                                            <?php
                                            $is_date = false;
                                            $sdate = "";
                                            $edate = "";
                                            $period = isset($row["rc_date"]) ? $row["rc_date"] : ""; //    [rc_date] => 2026.03 ~ 2026.07
                                            $arr_date = explode("~", $period);
                                            if (count($arr_date) > 1) {
                                                $is_date = true;
                                                $sdate = str_replace(".", "-", trim($arr_date[0])) . "-01";
                                                $edate = str_replace(".", "-", trim($arr_date[1])) . "-01";
                                            }
                                            ?>
                                            <em><?= htmlspecialchars($period, ENT_QUOTES, 'UTF-8') ?><?= $is_date ? "(" . get_date_diff_txt($sdate, $edate) . ")" : "" ?></em>
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
                 
                     <p><?= nl2br(htmlspecialchars($info["ru_desc"])) ?></p>
                </div>
                <div class="hashtag">
                    <?= getMyType($info["ru_my_type"]) ?>
                </div>
            </div>
        </div>
        <!-- <div class="section view-attachment">
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
        </div> -->
        <!-- 플로팅 액션 버튼 그룹 -->
        <div id="floating-actions" data-state="open">


            <?php if ($isOwnResume) : ?>
                <!-- 개인회원이 자신의 이력서를 볼 때: 수정/삭제/광고 -->
                <a href="/mypage/resume_write1/<?= $info["ru_idx"] ?>" class="floating-action-item" data-action="edit" title="수정">
                    <img src="/resource/shop/mobile/img/modify.png" alt="수정">
                    <span class="action-label">수정</span>
                </a>
                <a href="#" class="floating-action-item" id="btnDeleteResume" data-action="delete" title="삭제">
                    <img src="/resource/shop/mobile/img/del.png" alt="삭제">
                    <span class="action-label">삭제</span>
                </a>
                <a href="/mypage/point_promotion_buy/<?= $info["ru_idx"] ?>" class="floating-action-item" data-action="promote" title="광고신청">
                    <img src="/resource/shop/mobile/img/ad-post.png" alt="광고">
                    <span class="action-label">광고</span>
                </a>
            <?php else : ?>
                <!-- 기업회원이 볼 때: 제안보내기 + 전화하기 -->
                <a href="#" class="floating-action-item btnRequest" data-action="propose" title="제안">
                    <img src="/resource/shop/mobile/img/propose.png" alt="제안">
                    <span class="action-label">제안하기</span>
                </a>
                <a href="#" class="floating-action-item btnGoChat" data-action="call" title="전화" data-rm_idx="<?= isset($chat_rm_idx) ? htmlspecialchars($chat_rm_idx, ENT_QUOTES, 'UTF-8') : '' ?>">
                    <img src="/resource/shop/mobile/img/call-post.png" alt="전화">
                    <span class="action-label">전화하기</span>
                </a>
            <?php endif; ?>
        </div><!-- //#floating-actions -->

        <!-- 개인회원 본인 이력서 삭제 바텀시트 -->
        <?php if ($isOwnResume) : ?>
            <?php renderSelectBottomSheet(
                'deleteResumeSheet',
                '이력서 삭제',
                '<p>삭제된 이력서는 복구할 수 없습니다.<br>완전 삭제하시겠습니까?</p>',
                '취소', 'btnDeleteCancel', "closePanel(document.getElementById('deleteResumeSheet'));",
                '삭제', 'btnDeleteConfirm', 'confirmDeleteResume();'
            ); ?>
        <?php endif; ?>
    </div><!-- //page-content -->
</div>

<input type="hidden" name="ru_idx" value="<?= $info["ru_idx"] ?>" />
<input type="hidden" name="ru_name" value="<?= $info["ru_name"] ?>" />

<!-- 공유하기 팝업 -->
<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
$share_url = $protocol . $domain . '/resume/hr_post/' . $info["ru_idx"];
?>
<div id="sharePanel" class="layer-panel card-type" tabindex="-1" data-share-url="<?= htmlspecialchars($share_url, ENT_QUOTES, 'UTF-8') ?>">
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
                        var descParts = <?= json_encode(array_values(array_filter(array(trim($mem_info["mm_nation"]), getKoreanAge($mem_info["mm_birth"]) == "0" ? "" : getKoreanAge($mem_info["mm_birth"]) . "대"))), JSON_UNESCAPED_UNICODE) ?>,
                            firstAddr = $.trim($('#addr-display .addr-item').first().text());
                        if (firstAddr) descParts.push(firstAddr);

                        return {
                            title: <?= json_encode(trim($info["ru_name"]) != "" ? trim($info["ru_name"]) : getAstrik($mem_info["mm_name"]), JSON_UNESCAPED_UNICODE) ?>,
                            description: descParts.join(' | '),
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

<script>
    var customShareUrl = document.getElementById('sharePanel')?.getAttribute('data-share-url') || '';

    $(document).ready(function() {
        // 주소 정리: 희망근무조건 지역(.addr-item)에 fRemoveBigAdrJS 적용 (hr_post.php와 동일)
        if (typeof fRemoveBigAdrJS === 'function') {
            $('#addr-display .addr-item').each(function() {
                var originalAddr = $(this).attr('data-addr');
                if (originalAddr) {
                    $(this).text(fRemoveBigAdrJS(originalAddr));
                }
            });
        }

        var user_type = "<?= $this->user->getUserType(); ?>";

        // 모바일 물리적 뒤로가기 (popstate) 처리
        window.addEventListener('popstate', function(e) {
            if (!e.state || e.state.page === 'resume_view') {
                location.href = "/mypage/resume_list";
            }
        });

        // 이력서 삭제 바텀시트 트리거
        $(document).on('click', '#btnDeleteResume', function(e) {
            e.preventDefault();
            openPanel(document.getElementById('deleteResumeSheet'));
        });

        // 이력서 삭제 확인
        window.confirmDeleteResume = function() {
            var ru_idx = $("[name=ru_idx]").val();
            commonAlert('삭제하시겠습니까?', function() {
            comAjax("/mypage_proc/resume_delete", { ru_idx: ru_idx }, function(ret) {
                closePanel(document.getElementById('deleteResumeSheet'));
                if (ret.code == "00") {
                    // 삭제한 이력서의 브라우저 임시저장과 미리보기 복귀 표시를 지워 다음 등록에 남지 않도록 한다
                    try {
                        sessionStorage.removeItem("resume_draft_" + ru_idx + "_step1");
                        sessionStorage.removeItem("resume_draft_" + ru_idx + "_step4");
                        sessionStorage.removeItem("resume_from_preview");
                    } catch (e) {}
                    location.replace('/mypage/resume_list');
                } else {
                    commonAlert(ret.msg, function() {});
                }
                });
            });
        };

        // 플로팅 액션 버튼 토글 기능
        (function() {
            const floatingActions = document.getElementById('floating-actions');
            const floatingToggle = document.getElementById('floating-toggle');

            if (!floatingToggle || !floatingActions) return;

            floatingToggle.addEventListener('click', function(e) {
                e.preventDefault();
                const currentState = floatingActions.getAttribute('data-state');
                const newState = currentState === 'closed' ? 'open' : 'closed';
                floatingActions.setAttribute('data-state', newState);
            });
        })();
    });
</script>