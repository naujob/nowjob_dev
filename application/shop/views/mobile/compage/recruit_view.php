<?php
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
<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
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

        <div class="view-footer">
            <?php
            // 미리보기 모드가 아니고, 등록된 공고(rc_show_yn == "Y")일 때만 마감 버튼 표시
            $preview_mode = isset($_GET["preview_mode"]) && $_GET["preview_mode"] == "1";
            if (!$preview_mode && $info["rc_show_yn"] == "Y") :
            ?>
                <button id="btnDelete" type="button" aria-haspopup="dialog" class="btn btn-secondary js-remove">마감</button>
            <?php endif; ?>
            <button id="btnEdit" type="button" class="btn btn-tertiary">수정</button>
            <?php if ($info["rc_show_yn"] == "N") : ?>
                <button id="btnPublish" type="button" class="btn btn-primary">등록하기</button>
            <?php endif; ?>
        </div>

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
<script>
    window.addEventListener('DOMContentLoaded', function() {
        // 마감 버튼 클릭 이벤트
        $("#btnDelete").click(function() {
            var rc_name = $("[name=rc_name]").val();
            var rc_idx = $("[name=rc_idx]").val();
            openPopup('confirm', {
                content: '<em>"' + rc_name + '"</em><br>채용공고를 마감 처리하시겠습니까?',
                firstButton: '취소',
                secondButton: '마감',
                secondCallback: function() {
                    var params = {};
                    params["rc_idx_list"] = rc_idx;
                    comAjax("/compage_proc/recruit_end", params, function(ret) {
                        if (ret.code == "00") {
                            commonAlert("채용공고가 마감 처리되었습니다.", function() {
                                location.replace("/compage/recruit_list");
                            });
                        } else {
                            commonAlert(ret.msg, function() {});
                            return;
                        }
                    });
                }
            });
        });

        $("#btnEdit").click(function() {
            comAjax("/compage_proc/recruit_start", {}, function(ret) {
                if (ret.code == "00" || ret.code == "01") {
                    location.href = "/compage/recruit_write1_1/" + $("[name=rc_idx]").val();
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });

        // 등록하기 버튼 (임시저장된 공고를 공개하고 목록에 표시)
        $("#btnPublish").click(function() {
            var rc_idx = $("[name=rc_idx]").val();
            var params = {
                "rc_idx": rc_idx
            };
            comAjax("/compage_proc/recruit_publish", params, function(ret) {
                if (ret.code == "00") {
                    // 등록 완료 후 localStorage 정리
                    if (rc_idx && rc_idx != "0") {
                        localStorage.removeItem("recruit_draft_" + rc_idx + "_page1");
                        localStorage.removeItem("recruit_draft_" + rc_idx + "_page2");
                        localStorage.removeItem("recruit_draft_" + rc_idx + "_page3");
                    }
                    // "0" 키도 정리 (새 등록 시 사용했을 수 있음)
                    localStorage.removeItem("recruit_draft_0_page1");
                    localStorage.removeItem("recruit_draft_0_page2");
                    localStorage.removeItem("recruit_draft_0_page3");
                    
                    commonAlert("채용공고가 등록되었습니다.", function() {
                        // 등록 후 목록 페이지로 이동
                        location.href = "/compage/recruit_list";
                    });
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });
    });
</script>