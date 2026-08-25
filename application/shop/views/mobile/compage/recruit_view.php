<?php
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_template.php');
?>
<style>
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


</style>
<?php
// 기업회원이 자신의 채용공고를 볼 경우를 판단
$isOwnRecruit = ($this->user->getUserType() === "COMP" && isset($info["mm_idx"]) && $info["mm_idx"] == $this->user->getUserIdx());
?>
<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
        <span class="rt-group">
            <a href="#sharePanel" aria-haspopup="dialog" aria-controls="sharePanel" class="btn-share js-toggle-panel"><span class="sr-text">공유하기</span></a>
        </span>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="view-visual-slider">
            <div class="slider-wrap keen-slider">
                <?php if ($info["rc_img1"] != "") : ?>
                    <div class="keen-slider__slide">
                        <img data-src="<?= $info["rc_img1"] ?>" alt="" class="lozad">
                    </div>
                <?php endif; ?>
                <?php if ($info["rc_img2"] != "") : ?>
                    <div class="keen-slider__slide">
                        <img data-src="<?= $info["rc_img2"] ?>" alt="" class="lozad">
                    </div>
                <?php endif; ?>
                <?php if ($info["rc_img3"] != "") : ?>
                    <div class="keen-slider__slide">
                        <img data-src="<?= $info["rc_img3"] ?>" alt="" class="lozad">
                    </div>
                <?php endif; ?>
                <?php if ($info["rc_img4"] != "") : ?>
                    <div class="keen-slider__slide">
                        <img data-src="<?= $info["rc_img4"] ?>" alt="" class="lozad">
                    </div>
                <?php endif; ?>
                <?php if ($info["rc_img5"] != "") : ?>
                    <div class="keen-slider__slide">
                        <img data-src="<?= $info["rc_img5"] ?>" alt="" class="lozad">
                    </div>
                <?php endif; ?>
                <?php if ($info["rc_img99"] != "") : ?>
                    <div class="keen-slider__slide">
                        <img data-src="<?= $info["rc_img99"] ?>" alt="" class="lozad">
                    </div>
                <?php endif; ?>
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

        <!-- 20220929 수정 -->
        <div class="section view-header">
            <!-- 즐겨찾기 -->
            <!--button type="button" data-bk_type="RECRUIT" data-bk_key="<?= $info["rc_idx"] ?>"  class="btn-bookmark btn-view-bookmark js-toggle-bookmark">
                <span class="sr-text">즐겨찾기 추가</span>
            </button-->
            <div class="top-notice">
                <div class="container">
                    <div class="meta">
                        <!-- 작성일 -->
                        <p class="date"><?= get_yyyymmdd($info["reg_dt"]) ?></p>
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
                </div>
            </div>
            <div class="title-wrap">
                <div class="container">
                    <!-- 제목 -->
                    <h2 class="view-title"><?= $info["rc_name"] ?></h2>
                    <!-- 모집기간 -->
                    <p class="recruit-period">
                        <span>모집기간</span>
                        <em><?= get_yyyymmdd($info["rc_sdate"]) ?> ~ <?= get_yyyymmdd($info["rc_edate"]) ?></em>
                    </p>
                </div>
            </div>
            <div class="summary">
                <div class="container">
                    <h3 class="secondary-title">구인정보</h3>
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
                                            // getConvertPriceType 함수를 사용하여 job_post.php와 동일한 형식으로 표시
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
                                    <span class="working-time"><?= $info["rc_work_type"] ?></span>
                                </td>
                            </tr>
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
                                                    if ($info["rc_sage"] == "0" && $info["rc_eage"] == "0") {
                                                        echo "무관";
                                                    } else {
                                                        echo $info["rc_sage"] . "세이상~" . $info["rc_eage"] . "세이하";
                                                    }
                                                    ?></td>
                            </tr>
                            <tr>
                                <td class="label">근무요일</td>
                                <td class="value">
                                    <?php
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
                <h3 class="secondary-title">상세업무정보</h3>
                <!-- 작성내용 -->
                <div class="desc">
                    <?= $info["rc_desc"] ?>
                </div>
                <div class="hashtag">
                    <?= getHashTag($info["rc_hash"]) ?>
                </div>
            </div>
        </div>

        <div class="section view-location">
            <div class="container">
                <h3 class="secondary-title">기본정보</h3>
                <!-- 상호명 및 연락처 정보 -->
                <div class="company-info">
                    <table class="info-table">
                        <caption><span class="sr-text">상호 및 연락처</span></caption>
                        <colgroup>
                            <col>
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td class="label">상호명</td>
                                <td class="value"><?= isset($mem_info["mm_comp_name"]) && $mem_info["mm_comp_name"] != "" ? $mem_info["mm_comp_name"] : "-" ?></td>
                            </tr>
                            <tr>
                                <td class="label">연락처</td>
                                <td class="value">
                                    <?php
                                    if (isset($mem_info["mm_phone"]) && $mem_info["mm_phone"] != "") {
                                        // 숫자만 추출
                                        $phone = preg_replace('/[^0-9]/', '', $mem_info["mm_phone"]);

                                        // formatPhoneNumber 함수가 있으면 사용, 없으면 직접 포맷팅
                                        if (function_exists('formatPhoneNumber')) {
                                            echo formatPhoneNumber($phone);
                                        } else {
                                            // 전화번호 길이에 따라 하이픈 추가
                                            $phoneLength = strlen($phone);
                                            if ($phoneLength == 11) {
                                                // 휴대폰 번호: 010-XXXX-XXXX
                                                echo substr($phone, 0, 3) . '-' . substr($phone, 3, 4) . '-' . substr($phone, 7);
                                            } elseif ($phoneLength == 10) {
                                                // 지역번호 2자리: 02-XXXX-XXXX 또는 지역번호 3자리: 031-XXX-XXXX
                                                if (substr($phone, 0, 2) == '02') {
                                                    // 서울: 02-XXXX-XXXX
                                                    echo substr($phone, 0, 2) . '-' . substr($phone, 2, 4) . '-' . substr($phone, 6);
                                                } else {
                                                    // 기타 지역: 031-XXX-XXXX
                                                    echo substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
                                                }
                                            } elseif ($phoneLength == 9) {
                                                // 서울 7자리: 02-XXX-XXXX
                                                echo substr($phone, 0, 2) . '-' . substr($phone, 2, 3) . '-' . substr($phone, 5);
                                            } else {
                                                // 기타 형식은 원본 표시
                                                echo $mem_info["mm_phone"];
                                            }
                                        }
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="location">
                    <table class="info-table">
                        <caption><span class="sr-text">기본주소</span></caption>
                        <tr>
                            <td class="label">상세주소</td>
                            <td class="value">
                                <!-- 도로명 -->
                                <span style="display: inline;"><?= $addr["ad_doro"] ?></span>
                                <!-- 나머지 주소 -->
                                <span style="display: inline;"><?= $addr["ad_detail"] ?></span>
                            </td>
                        </tr>
                    </table>
                    <!-- 길찾기 기능 주석처리
                    <a href="#findLocation" role="button" aria-haspopup="dialog" aria-controls="findLocation" class="btn-primary btn-round js-toggle-panel">길찾기</a>
                    -->
                </div>
                <div class="map">
                    <!-- 회사 지도 좌표  -->
                    <input type="hidden" id="geocoder" value="<?= $addr["ad_gps_y"] ?>,<?= $addr["ad_gps_x"] ?>">
                    <div id="jsMap" class="js-map"></div>
                </div>

                <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?= $kakao_key["인증키"] ?>&libraries=services"></script>
                <script>
                    window.addEventListener('load', function() {
                        var mapContainer = document.getElementById('jsMap');
                        var geocoder = document.getElementById('geocoder').value;
                        var coords, map, marker;

                        if (!geocoder) {
                            mapContainer.parentNode.setAttribute('hidden', '');
                            return;
                        }

                        geocoder = geocoder.split(',');
                        coords = new kakao.maps.LatLng(geocoder[0], geocoder[1]);

                        map = new kakao.maps.Map(mapContainer, {
                            center: coords,
                            level: 3,
                        });

                        map.setZoomable(false);

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
                    });
                </script>
            </div>
            <!-- 20220929 수정 -->

        </div>

        <!-- 플로팅 액션 버튼 그룹 -->
        <?php
        $userType = $this->user->getUserType();
        ?>
        <?php if ($userType !== "COMP" || $isOwnRecruit) : ?>
        <div id="floating-actions" data-state="open" <?php echo ($userType === "COMP" && $isOwnRecruit) ? 'data-own-recruit="true"' : ''; ?>>

            <?php if ($userType === "MEM") : ?>
                <!-- 개인회원: 제안하기 + 전화하기 -->
                <a href="#" class="floating-action-item btnRequest" data-action="propose" title="제안">
                    <img src="/resource/shop/mobile/img/propose.png" alt="지원">
                    <span class="action-label">지원하기</span>
                </a>
                <a href="tel:<?= isset($mem_info["mm_phone"]) ? htmlspecialchars($mem_info["mm_phone"], ENT_QUOTES, 'UTF-8') : '' ?>" class="floating-action-item js-call-log" data-rc_idx="<?= $info["rc_idx"] ?>" data-action="call" title="전화">
                    <img src="/resource/shop/mobile/img/call-post.png" alt="전화">
                    <span class="action-label">전화하기</span>
                </a>
            <?php elseif ($userType === "COMP" && $isOwnRecruit) : ?>
                <!-- 기업회원 & 내 공고: 재등록/수정/마감/삭제/광고 -->

                <a href="#" class="floating-action-item" id="btnReregisterRecruit" data-action="reregister" title="재등록">
                    <img src="/resource/shop/mobile/img/propose.png" alt="재등록">
                    <span class="action-label">재등록</span>
                </a>
                <a href="/compage/recruit_write1_1/<?= $info["rc_idx"] ?>" class="floating-action-item" data-action="edit" title="수정">
                    <img src="/resource/shop/mobile/img/modify.png" alt="수정">
                    <span class="action-label">수정</span>
                </a>
                <a href="#" class="floating-action-item" id="btnEndRecruit" data-action="end" title="마감">
                    <img src="/resource/shop/mobile/img/due.png" alt="마감">
                    <span class="action-label">마감</span>
                </a>
                <a href="#" class="floating-action-item" id="btnDeleteRecruit" data-action="delete" title="삭제">
                    <img src="/resource/shop/mobile/img/del.png" alt="삭제">
                    <span class="action-label">삭제</span>
                </a>
                <a href="/compage/point_promotion_buy/<?= $info["rc_idx"] ?>" class="floating-action-item" data-action="promote" title="광고신청">
                    <img src="/resource/shop/mobile/img/ad-post.png" alt="광고">
                    <span class="action-label">광고</span>
                </a>
            <?php else : ?>
                <!-- 비회원: 로그인 + 전화하기 -->
                <a href="/member/login" class="floating-action-item" data-action="login" title="로그인">
                    <img src="/resource/shop/mobile/img/login-post.png" alt="로그인">
                    <span class="action-label">로그인</span>
                </a>
                <a href="tel:<?= isset($mem_info["mm_phone"]) ? htmlspecialchars($mem_info["mm_phone"], ENT_QUOTES, 'UTF-8') : '' ?>" class="floating-action-item js-call-log" data-rc_idx="<?= $info["rc_idx"] ?>" data-action="call" title="전화">
                    <img src="/resource/shop/mobile/img/call-post.png" alt="전화">
                    <span class="action-label">전화하기</span>
                </a>
            <?php endif; ?>
        </div><!-- //#floating-actions -->
        <?php endif; ?>

        <!-- 기업회원 본인 공고 바텀시트 3개 -->
        <?php if ($userType === "COMP" && $isOwnRecruit) : ?>
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
</div>

<!-- 길찾기 팝업 주석처리
<div id="findLocation" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="sr-text">길찾기 지도선택</h2>
            <button type="button" aria-controls="findLocation" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <div class="vendor-links">
                    <a href="javascript:naverMap('<?= $info["rc_name"] ?>','<?= $addr["ad_gps_y"] ?>','<?= $addr["ad_gps_x"] ?>');" id="findNaverMap" class="vendor-link1">
                        <span class="ico"><img src="/resource/shop/mobile/img/map_vendor1.jpg" alt=""></span>
                        <span>네이버</span>
                    </a>
                    <a href="javascript:kakaoMap('<?= $info["rc_name"] ?>','<?= $addr["ad_gps_y"] ?>','<?= $addr["ad_gps_x"] ?>');" class="vendor-link2">
                        <span class="ico"><img src="/resource/shop/mobile/img/map_vendor2.jpg" alt=""></span>
                        <span>카카오</span>
                    </a>
                    <?php if ($g_ios_publish != true) : ?>
                        <a href="javascript:tMap('<?= $info["rc_name"] ?>','<?= $addr["ad_gps_y"] ?>','<?= $addr["ad_gps_x"] ?>');" class="vendor-link3">
                            <span class="ico"><img src="/resource/shop/mobile/img/map_vendor3.jpg" alt=""></span>
                            <span>티맵</span>
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>
//길찾기 팝업 -->

<!-- 길찾기 스크립트 함수 주석처리
<script>
    //티맵 길안내
    function tMap(name, lat, lng) {
        window.open("https://apis.openapi.sk.com/tmap/app/routes?appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41&name=" + name + "&lon=" + lng + "&lat=" + lat);
    }

    //카카오맵 길안내
    function kakaoMap(name, lat, lng) {
        window.open("https://map.kakao.com/link/to/" + name + "," + lat + "," + lng);
    }

    //네이버맵 길안내
    function naverMap(name, lat, lng) {
        window.open("http://app.map.naver.com/launchApp/?version=11&menu=navigation&elat=" + lat + "&elng=" + lng + "&etitle=" + name);
    }
</script>
-->


<input type="hidden" name="rc_idx" value="<?= $info["rc_idx"] ?>" />
<input type="hidden" name="rc_name" value="<?= $info["rc_name"] ?>" />

<!-- 공유하기 팝업 -->
<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
$domain = $_SERVER['HTTP_HOST'];
$share_url = $protocol . $domain . '/recruit/job_post/' . $info["rc_idx"];
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
                        var rc_idx = document.querySelector('[name=rc_idx]').value;
                        var mobileUrl = 'nowapk://recruit/job_post/' + rc_idx;

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

    window.addEventListener('DOMContentLoaded', function() {
        var user_type = "<?= $this->user->getUserType(); ?>";

        // 뒤로가기 핸들러 (recruit_write 페이지에서 복귀 시에도 목록으로 가도록 고정)
        $(".btn-back").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            location.href = "/compage/recruit_list";
        });


        // 모바일 물리적 뒤로가기 (popstate) 처리
        window.addEventListener('popstate', function(e) {
            if (!e.state || e.state.page === 'recruit_view') {
                location.href = "/compage/recruit_list";
            }
        });

        // 통화 로그 기록
        $(document).on('click', '.js-call-log', function() {
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
                    commonAlert("채용공고가 재등록되었습니다.", function() { location.href = "/compage/recruit_list"; });
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
                    commonAlert("채용공고가 마감 처리되었습니다.", function() { location.reload(); });
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });
        };

        // 삭제 확인
        window.confirmDeleteRecruit = function() {
            var rc_idx = $("[name=rc_idx]").val();
            comAjax("/compage_proc/recruit_del", { rc_idx_list: rc_idx }, function(ret) {
                closePanel(document.getElementById('deleteRecruitSheet'));
                if (ret.code == "00") {
                    commonAlert("채용공고가 삭제되었습니다.", function() { location.href = "/compage/recruit_list"; });
                } else {
                    commonAlert(ret.msg, function() {});
                }
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