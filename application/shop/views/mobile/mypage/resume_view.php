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
        padding-right: 5px;
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

                <script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script>
                <script>
                    function shareMessage() {
                        if (!Kakao.isInitialized()) {
                            Kakao.init("<?= $kakao_key["인증키"] ?>");
                        }

                        var title = document.querySelector('.view-title')?.textContent || document.title;
                        var ru_idx = document.querySelector('[name=ru_idx]').value;
                        var mobileUrl = 'nowapk://resume/hr_post/' + ru_idx;

                        Kakao.Share.sendDefault({
                            objectType: 'feed',
                            content: {
                                title: title,
                                imageUrl: '/resource/shop/web/img/nowjob_info.png',
                                link: {
                                    mobileWebUrl: mobileUrl,
                                    webUrl: location.href
                                }
                            }
                        });
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<!-- //공유하기 팝업 -->

<script src="/resource/shop/mobile/js/share-panel.js"></script>

<script>
    var shareOsType = "<?= $os_type ?>";
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