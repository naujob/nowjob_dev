<?php
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

function getWorkType2025($work_type)
{
  if (!$work_type) {
    return "";
  }

  $items = [];

  if (preg_match_all('/<span>(.*?)<\/span>/', $work_type, $matches)) {
    $items = $matches[1];
  } else {
    $items = preg_split('/\s*,\s*/', $work_type);
  }

  $item_set = array_unique(array_map('trim', $items));

  $has_jugan = in_array('주간', $item_set);
  $has_yagan = in_array('야간', $item_set);
  $has_2shift = in_array('2교대', $item_set);

  if ($has_jugan && $has_yagan && $has_2shift) {
    return '<em class="wtype0">근무협의</em>';
  }

  $result = '';
  foreach ($item_set as $text) {
    if ($text === '') continue;

    $cls = '';
    if ($text === '주간') {
      $cls = 'wtype1';
    } else if ($text === '야간') {
      $cls = 'wtype2';
    } else if ($text === '2교대') {
      $cls = 'wtype3';
    }

    $result .= '<em class="' . $cls . '">' . $text . '</em>';
  }

  return $result;
}

function getConvertPriceType($rc_price_type, $rc_price_rep)
{
  $allTypes = ['일급', '주급', '월급'];

  // 모든 급여 유형이 포함된 경우
  $containsAll = true;
  foreach ($allTypes as $type) {
    if (strpos($rc_price_type, $type) === false) {
      $containsAll = false;
      break;
    }
  }

  if ($containsAll) {
    return '<em>급여협의</em>';
  }

  // 쉼표로 분리
  $types = preg_split('/\s*,\s*/', $rc_price_type);

  if (count($types) === 1) {
    return '<em class="on">' . $types[0] . '</em>';
  }

  // 다중 유형 처리
  $result = '';
  foreach ($types as $type) {
    $type = trim($type);
    if ($type === $rc_price_rep) {
      $result .= '<em class="on">' . $type . '</em>';
    } else {
      $result .= '<em>' . $type . '</em>';
    }
  }

  return $result;
}
?>
<input type="hidden" name="rc_idx" value="<?= $info["rc_idx"] ?>" />
<style>
    .info-table .working-day>li {
        width: 1.8rem;
        height: 1.8rem;
        font-size: 1rem;
        line-height: 1.5rem;
    }

    /* 기업회원이 자신의 채용공고를 볼 때 view-footer 버튼 스타일 */
    .view-footer {
        display: flex;
        gap: 0;
    }

    .view-footer .btn-edit {
        flex: 1;
        background-color: #57CCAF;
        color: #fff;
        border: 1px solid #57CCAF;
        font-weight: 700;
    }

    .view-footer .btn-promotion {
        flex: 1;
        background-color: #1B9D7D;
        color: #fff;
        font-weight: 700;
    }

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
    .working-time em {
        position: relative;
        display: inline-block;
        font-style: normal;
    }

    .working-time em:not(:last-child) {
        padding-right: 5px;
    }

    .working-time em:not(:last-child)::after {
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

    .working-time em.wtype0 {
        color: #333;
    }

    .working-time em.wtype1 {
        color: #3a70db;
    }

    .working-time em.wtype2 {
        color: #F86048;
    }

    .working-time em.wtype3 {
        color: #1B9D7D;
    }
</style>
<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
        <span class="rt-group">
            <!-- <a href="#" class="btn-action" id="btnResume">이력서 작성</a> -->
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
            <a href="javascript:void(0);" class="bigsize"><img src="/resource/shop/web/img/bigsize.png" /></a>
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
                    <button type="button" aria-haspopup="dialog" aria-controls="reportForm1" class="btn-report js-report"><span class="sr-text">신고하기</span></button>
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
                                            // getConvertPriceType 함수를 사용하여 job_list.php와 동일한 형식으로 표시
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
                                    <span class="working-time"><?= getWorkType2025($info["rc_work_type"]) ?></span>
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
                                <td class="value"><?= ($info["rc_nation"] == "내국인,외국인" || trim($info["rc_nation"]) == "" ? "국적무관" : $info["rc_nation"]) ?></td>
                            </tr>
                            <tr>
                                <td class="label">희망성별</td>
                                <td class="value"><?= ($info["rc_sex"] == "남자,여자" || trim($info["rc_sex"]) == "" ? "성별무관" : $info["rc_sex"]) ?></td>
                            </tr>
                            <tr>
                                <td class="label">희망나이</td>
                                <td class="value"><?php
                                                    if ($info["rc_sage"] == "0" && $info["rc_eage"] == "0") {
                                                        echo "나이무관";
                                                    } else {
                                                        echo $info["rc_sage"] . "세이상~" . $info["rc_eage"] . "세이하";
                                                    }
                                                    ?></td>
                            </tr>
                            <tr>
                                <td class="label">근무요일</td>
                                <td class="value">
                                    <ul class="working-day">
                                        <?= getWeekYoil($info["rc_week"]) ?>
                                    </ul>
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
                <h3 class="secondary-title" onclick="alert('<?= $user_agent ?>');">상세업무정보</h3>
                <!-- 작성내용 -->
                <div class="desc" style="width: 100%; overflow-x: auto;">
                    <p><?= str_replace("\n", "<br/>", $info["rc_desc"]) ?></p><br>
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
                                <span><?= $mem_info["mm_comp_name"] ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">연락처</td>
                            <td class="value">
                                <span><?= formatPhoneNumber($mem_info["mm_phone"]) ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">상세주소</td>
                            <td class="value">
                                <span><?= $addr["ad_doro"] ?> <?= $addr["ad_detail"] ?></span>
                            </td>
                        </tr>
                    </table>
                    <?php if ($g_ios_publish != true) : ?>
                        <a href="#findLocation" role="button" aria-haspopup="dialog" aria-controls="findLocation" class="btn-primary btn-round js-toggle-panel">길찾기</a>
                    <?php endif; ?>
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
        </div>
        <?php 
        // 기업회원이 자신의 채용공고를 볼 경우를 판단
        $isOwnRecruit = ($this->user->getUserType() === "COMP" && isset($info["mm_idx"]) && $info["mm_idx"] == $this->user->getUserIdx());
        ?>
        <div class="view-footer">
            <?php if ($isOwnRecruit) { ?>
                <!-- 기업회원이 자신의 채용공고를 볼 때: 수정 및 광고신청 버튼 -->
                <button type="button" id="btnEditRecruit" class="btn btn-edit">수정</button>
                <button type="button" id="btnPromotionRecruit" class="btn btn-promotion">광고신청</button>
            <?php } else { ?>
                <!-- 개인회원이 볼 때: 지원하기 버튼 -->
                <button id="btnRecruit" type="button" aria-haspopup="dialog" class="btn btn-secondary">지원하기</button>
                <!-- <button type="button" aria-haspopup="dialog" class="btn btn-primary" onclick="location.href='tel:<?= $info["mm_phone"] ?>';">전화하기</button> -->
                <a href="tel:<?= $info["mm_phone"] ?>" class="btn btn-primary">전화하기</a>
            <?php } ?>
        </div>

    </div><!-- //page-content -->
</div>

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
                            desc = ''; //document.querySelector('.view-desc .desc').textContent.replace(/\s/g,''),
                        imgUrl = 'https://nowjob.co.kr/resource/shop/web/img/nowjob_info.png'; //document.querySelector('.view-visual-slider img').getAttribute('src');
                        url = location.href,
                            appUrl = 'nowjob://recruit/recruit_detail/<?= $info["rc_idx"] ?>'; //'scheme://host_path';

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
                </script>
            </div>
        </div>
    </div>
</div>
<!-- //공유하기 팝업 -->

<!-- 길찾기 팝업 -->
<div id="findLocation" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="sr-text">길찾기 지도선택</h2>
            <button type="button" aria-controls="findLocation" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <div class="vendor-links">
                    <?php //todo //print_r($info);
                    //echo "[".$os_type."]";
                    if ($os_type != "android") { ?>
                        <a href="javascript:naverMap('<?= $info["mm_comp_name"] ?>','<?= $addr["ad_gps_y"] ?>','<?= $addr["ad_gps_x"] ?>');" id="findNaverMap" class="vendor-link1">
                            <!-- <span class="ico" style="border:none;"><img src="/resource/shop/mobile/img/map_naver.png" alt=""></span> -->
                            <!-- <span>네이버</span> -->
                        </a>
                    <?php } ?>
                    <?php if (true) { ?>
                        <a href="javascript:kakaoMap('<?= $info["mm_comp_name"] ?>','<?= $addr["ad_gps_y"] ?>','<?= $addr["ad_gps_x"] ?>');" class="vendor-link2">
                            <span class="ico" style="border:none;"><img src="/resource/shop/mobile/img/map_kakao.png" alt=""></span>
                            <span>카카오</span>
                        </a>
                    <?php } ?>
                    <?php if (true) {  ?>
                        <a href="javascript:tMap('<?= $info["mm_comp_name"] ?>','<?= $addr["ad_gps_y"] ?>','<?= $addr["ad_gps_x"] ?>');" class="vendor-link3">
                            <span class="ico" style="border:none;"><img src="/resource/shop/mobile/img/mpa_tmap.png" alt=""></span>
                            <span>티맵</span>
                        </a>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- //길찾기 팝업 -->

<input type="hidden" name="rc_idx" value="<?= $info["rc_idx"] ?>" />
<input type="hidden" name="rc_name" value="<?= $info["rc_name"] ?>" />
<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
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

    //네이버맵 길안내
    function naverMap(name, lat, lng) {

        //네이버앱이 있으면 바로 실행
        // var url = 'nmap://route/walk?'+
        //          'dlat=' + lat +
        //         '&dlng=' + lng +
        //         '&dname=' + encodeURIComponent(name);
        //net::ERR_UNKOWN_URL_SCHEME

        // var url = 'nmap://route/walk?slat=' + startLat +
        //         '&slng=' + startLng +
        //         '&dlat=' + endLat +
        //         '&dlng=' + endLng +
        //         '&dname=' + encodeURIComponent(endName);

        // 네이버맵 앱 실행 시도
        //window.location.href = url;

        // 앱이 설치되어 있지 않은 경우를 대비해 타임아웃 설정
        // setTimeout(function() {
        //   // 웹 브라우저에서 네이버 길찾기 페이지 열기
        //   //window.location.href = 'https://map.naver.com/v5/directions/' + startLat + ',' + startLng + ',' + endLat + ',' + endLng + ',' + encodeURIComponent(endName);
        //
        //   location.href = ("https://app.map.naver.com/launchApp/?version=11&menu=navigation&elat="+lat+"&elng="+lng+"&etitle="+name);
        //
        // }, 2000);

        location.href = ("https://app.map.naver.com/launchApp/?version=11&menu=navigation&elat=" + lat + "&elng=" + lng + "&etitle=" + name);
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
    // $(document).ready(function(){
    window.addEventListener('DOMContentLoaded', function() {
        $('.bigsize').magnificPopup({
            items: [
                <?php if ($info["rc_img1"] != "") : ?> {
                        src: '<?= $info["rc_img1"] ?>'
                    },
                <?php endif; ?>
                <?php if ($info["rc_img2"] != "") : ?> {
                        src: '<?= $info["rc_img2"] ?>'
                    },
                <?php endif; ?>
                <?php if ($info["rc_img3"] != "") : ?> {
                        src: '<?= $info["rc_img3"] ?>'
                    },
                <?php endif; ?>
                <?php if ($info["rc_img4"] != "") : ?> {
                        src: '<?= $info["rc_img4"] ?>'
                    },
                <?php endif; ?>
                <?php if ($info["rc_img5"] != "") : ?> {
                        src: '<?= $info["rc_img5"] ?>'
                    },
                <?php endif; ?>
            ],
            gallery: {
                enabled: true
            },
            type: 'image' // this is default type
        });

        var user_type = "<?= $this->user->getUserType(); ?>";
        $("#btnRecruit").click(function() {
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
                        console.log(ret);
                        $('.btn-close-popup').show();

                        if (ret.code == "00") {
                            // 알림창 주석처리 - 바로 채팅 목록으로 이동
                            // commonAlert("지원 신청 되었습니다.", function() {
                            //     location.reload();
                            // });
                            
                            // 지원한 공고 탭으로 이동하고 해당 공고의 채팅방 자동 진입
                            // URL 파라미터로 rc_idx 전달하여 목록 페이지에서 자동으로 채팅방 찾아서 열기
                            location.href = "/chat/chat_list?auto_open_rc_idx=" + rc_idx;
                        } else {
                            commonAlert(ret.msg, function() {});
                            return;
                        }
                    });
                }
                    });
                    $('.btn-close-popup').hide();
                } else if (ret.code == "01") {
                    commonAlert("<em>5분 후</em> 다시 지원이 가능합니다.", function() {});
                    return;
                } else if (ret.code == "90") {
                    // 제안이 이미 있는 경우
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

        // 기업회원이 자신의 채용공고를 볼 때: 광고신청 버튼 클릭 이벤트
        $(document).on('click', '#btnPromotionRecruit', function() {
            location.href = "/compage/point_promotion_buy";
        });
    });
</script>