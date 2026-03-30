<?php
// 카카오 키 가져오기 for Geocoding
$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
?>
<style>
    html.autofill-hide {
        visibility: hidden;
    }
</style>
<script>
    (function() {
        var params = new URLSearchParams(window.location.search);
        if (params.get('autofill') === '1') {
            document.documentElement.classList.add('autofill-hide');
        }
    })();
</script>
<script>
    // webview_debug 로그 제거 (서버 자동완성 방식으로 전환)
</script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>&libraries=services"></script>

<div class="page page-write page-resume-write">
    <div class="top-bar">
        <a href="javascript:void(0);" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이력서</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form id="frm" onsubmit="return false" autocomplete="false">
            <input type="hidden" name="ru_work_img" value="" />
            <input type="hidden" name="quickinfo_gender" value="" />
            <input type="hidden" name="quickinfo_age" value="" />
            <input type="hidden" name="arr_career" value="" />
            <input type="hidden" name="arr_certi" value="" />
            <div class="section addition-info-form">
                <div class="container">
                    <div class="form-row">
                        <div class="resume-title-header">
                            <label>
                                <span class="form-label"><span class="required-asterisk">*</span>이력서명</span>
                            </label>
                            <span class="required-label"><span class="required-asterisk">*</span>필수입력</span>
                        </div>
                        <label>
                            <input type="text" name="ru_name" placeholder="이력서제목을 입력해주세요" value="<?= $info["ru_name"] ?>" autocomplete="off" aria-required="true" required class="form-text resume-title-input">
                        </label>
                        <div class="form-toggle-group col-2 nationality-toggle-group">
                            <label class="form-toggle-btn2">
                                <input type="radio" name="ru_nationality" value="내국인" <?= (!isset($info["ru_nationality"]) || $info["ru_nationality"] == "내국인" || $info["ru_nationality"] == "") ? "checked" : "" ?>>
                                <span class="btn">내국인</span>
                            </label>
                            <label class="form-toggle-btn2">
                                <input type="radio" name="ru_nationality" value="외국인" <?= (isset($info["ru_nationality"]) && $info["ru_nationality"] == "외국인") ? "checked" : "" ?>>
                                <span class="btn">외국인</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label"><span class="required-asterisk">*</span>희망업무 <em>희망업무는 2개까지 선택 가능</em></p>
                        <div class="form-toggle-group col-3">
                            <?php
                            // 표시 허용 희망업무 화이트리스트
                            $allowed_work_list = ["생산", "사무직", "운전배달", "물류", "식당", "서비스", "기타", "건설", "상담"];
                            foreach ($ru_wrok_list as $row) :
                                // 허용 목록 이외 항목은 출력 제외
                                if (!in_array($row["cd_val"], $allowed_work_list, true)) {
                                    continue;
                                }
                            ?>
                                <label class="form-toggle-btn2 sm">
                                    <!--
                                        희망업무 체크박스
                                        name="ru_work[]": 사용자가 선택한 희망업무를 배열로 전송
                                        data-select-limit="2": 최대 2개까지 선택 가능하도록 JS에서 제어
                                        value="<?= $row["cd_val"] ?>": 업무 코드값(예: 생산, 사무직 등)
                                        data-img="<?= $row["cd_val2"] ?>": 각각의 업무에 해당하는 이미지 경로(썸네일 등)
                                        <?= in_array_return($row["cd_val"], $info["ru_work"], "check") ?>: 이미 선택된 값들은 checked 처리
                                    -->
                                    <input type="checkbox" name="ru_work[]" data-select-limit="2" value="<?= $row["cd_val"] ?>" data-img="<?= $row["cd_val2"] ?>" <?= in_array_return($row["cd_val"], $info["ru_work"], "check") ?>>
                                    <span class="btn"><?= $row["cd_val"] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label"><span class="required-asterisk">*</span>희망지역<em>희망지역은 2개까지 선택 가능</em></p>
                        <div class="location-field-group">
                            <ul class="added-location-new" id="addr_location">
                                <!-- 주소는 JavaScript에서만 렌더링 -->
                                <li class="location-field-item location-field-add">
                                    <a href="javascript:void(0);" onclick="showAddrInfo(2);" role="button" class="location-add-btn">
                                        <span class="add-icon">+</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label form-label-with-checkbox">
                            <span><span class="required-asterisk">*</span>희망급여 <em>희망급여는 2개까지 선택 가능</em></span>
                            <span class="form-checkbox">
                                <input type="checkbox" name="ru_salary_negotiation" value="Y" <?= (isset($info["ru_salary_negotiation"]) && $info["ru_salary_negotiation"] == "Y") || count($info["ru_price_type"]) > 2 ? "checked" : "" ?>>
                                <span class="label">급여협의</span>
                            </span>
                        </p>
                        <div class="form-toggle-group col-3">
                            <?php foreach ($ru_price_type_list as $row) : ?>
                                <label class="form-toggle-btn2">
                                    <input type="checkbox" name="ru_price_type[]" data-select-limit="2" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info["ru_price_type"], "check") ?>>
                                    <span class="btn"><?= $row["cd_val"] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>


                    </div>

                    <!-- 20221003 수정 -->
                    <div class="form-row">
                        <p class="form-label form-label-with-checkbox">
                            <span><span class="required-asterisk">*</span>근무형태 <em>근무형태는 2개까지 선택 가능</em></span>
                            <span class="form-checkbox">
                                <input type="checkbox" name="ru_work_negotiation" value="Y" <?= (isset($info["ru_work_negotiation"]) && $info["ru_work_negotiation"] == "Y") || count($info["ru_work_type"]) > 2 ? "checked" : "" ?>>
                                <span class="label">근무협의</span>
                            </span>
                        </p>
                        <div class="form-toggle-group col-3">
                            <?php foreach ($ru_work_type_list as $row) : ?>
                                <label class="form-toggle-btn2">
                                    <input type="checkbox" name="ru_work_type[]" data-select-limit="2" class="<?= $row["cd_val"] != "2교대" ? "js-check-time" : "" ?>" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info["ru_work_type"], "check") ?>>
                                    <span class="btn"><?= $row["cd_val"] ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
            <script>
                //선택목록 제한
                (function() {
                    document.querySelectorAll('input[data-select-limit]').forEach(function(item) {
                        item.addEventListener('change', function() {
                            var name = this.getAttribute('name');
                            // 협의가 체크된 경우 선택 제한 무시
                            if (name === 'ru_price_type[]' && document.querySelector('[name="ru_salary_negotiation"]:checked')) {
                                return;
                            }
                            if (name === 'ru_work_type[]' && document.querySelector('[name="ru_work_negotiation"]:checked')) {
                                return;
                            }

                            var limit = parseInt(this.getAttribute('data-select-limit'));
                            var childItems = document.querySelectorAll('[name="' + name + '"]:checked');

                            if (this.checked && childItems.length > limit) {
                                this.checked = false;
                                commonAlert("최대 " + limit + "개까지 선택 가능합니다.", function() {});
                            }
                        });
                    });

                    //20221003 수정
                    /* 근무시간 문구 표시 */
                    function timeChecked(el) {
                        var total = document.querySelectorAll('.js-check-time').length;
                        var isChecked = document.querySelectorAll('.js-check-time:checked');
                        var row = el.closest('.form-row');
                        var msg = row.querySelector('.help-msg');

                        if (!msg) {
                            msg = document.createElement('P');
                            msg.classList.add('help-msg');
                            msg.innerHTML = '주간 고정, 야간 고정을 선택하셨습니다.';
                        }

                        if (isChecked.length == total) {
                            row.appendChild(msg);
                        } else {
                            msg.remove();
                        }
                    }

                    document.querySelectorAll('.js-check-time').forEach(function(item) {
                        item.addEventListener('change', function() {
                            timeChecked(item);
                        });

                        timeChecked(item);
                    });
                }());
            </script>

            <div class="form-row intro-section">
                <p class="form-label"><span class="required-asterisk">*</span>자기소개<em>최대500자</em></p>
                <label>
                    <span class="sr-text">자기소개 입력</span>
                    <textarea name="ru_desc" maxlength="500" class="form-textarea js-autoheight"><?= $info["ru_desc"] ?></textarea>
                </label>

            </div>
            <div class="form-row my-strength-section">
                <p class="form-label"><span class="required-asterisk">*</span>나의강점 <em>나의강점을 선택하세요(최대6개까지)</em></p>
                <div class="form-toggle-group col-3">
                    <?php
                    $ru_my_type_list = isset($ru_my_type_list) ? $ru_my_type_list : [];
                    $info_ru_my_type = isset($info["ru_my_type"]) ? (is_array($info["ru_my_type"]) ? $info["ru_my_type"] : explode(",", $info["ru_my_type"])) : [];
                    $ru_my_type_list_limited = array_slice($ru_my_type_list, 0, 12);
                    foreach ($ru_my_type_list_limited as $row) :
                        if (!isset($row["cd_val"]) || trim($row["cd_val"]) === "") {
                            continue;
                        }
                    ?>
                        <label class="form-toggle-btn2 sm tag">
                            <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info_ru_my_type, "check") ?>>
                            <span class="btn"><?= $row["cd_val"] ?></span>
                        </label>
                    <?php endforeach; ?>

                    <?php if (!empty($info_ru_my_type)) : ?>
                        <?php foreach ($info_ru_my_type as $row) : ?>
                            <?php
                            if (trim((string)$row) === "") {
                                continue;
                            }
                            $is_arr = false;
                            foreach ($ru_my_type_list as $temp) {
                                if (isset($temp["cd_val"]) && $temp["cd_val"] == $row) {
                                    $is_arr = true;
                                }
                            }
                            if ($is_arr == false) :
                            ?>
                                <label class="form-toggle-btn2 sm tag">
                                    <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?= $row ?>" <?= in_array_return($row, $info_ru_my_type, "check") ?>>
                                    <span class="btn"><?= $row ?></span>
                                </label>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-row js-detail-section">
                <p class="form-label">
                    희망요일 <em>다중선택가능</em>
                    <span class="option">
                        <label class="form-checkbox">
                            <input type="checkbox" name="ru_week_yn" value="Y" class="sr-text js-week-all" <?= isset($info["ru_week_yn"]) && $info["ru_week_yn"] == "Y" ? "checked" : "" ?>>
                            <span class="label">요일무관</span>
                        </label>
                    </span>
                </p>
                <div class="form-toggle-group align-justify">
                    <?php
                    if (!isset($ru_week_list) || empty($ru_week_list)) {
                        $ru_week_list = [
                            ["cd_val" => "월"],
                            ["cd_val" => "화"],
                            ["cd_val" => "수"],
                            ["cd_val" => "목"],
                            ["cd_val" => "금"],
                            ["cd_val" => "토"],
                            ["cd_val" => "일"]
                        ];
                    }
                    $info_ru_week = isset($info["ru_week"]) ? (is_array($info["ru_week"]) ? $info["ru_week"] : explode(",", $info["ru_week"])) : [];
                    if (empty($info_ru_week)) {
                        $info_ru_week = ["월", "화", "수", "목", "금"];
                    }
                    foreach ($ru_week_list as $row) :
                    ?>
                        <label class="form-toggle-btn2 js-week-item">
                            <input type="checkbox" name="ru_week[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info_ru_week, "check") ?>>
                            <span class="btn"><?= $row["cd_val"] ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-row js-detail-section" data-section="career">
                <p class="form-label">경력사항 <em>최대 3개까지만 등록가능</em></p>
                <ol class="added-list">
                    <?php $arr_career_count = 0; ?>
                    <?php if (isset($info["arr_career"]) && $info["arr_career"] != "") :
                        $arr_career = explode("&&&", $info["arr_career"]);
                        $arr_career_count = count($arr_career);
                        foreach ($arr_career as $row) :
                            $arr_temp = explode("|||", $row);
                    ?>
                            <li>
                                <div class="form-group">
                                    <div class="col sm">
                                        <label class="form-selectbox js-career-category">
                                            <span class="sr-text">업종선택</span>
                                            <select name="career_category[]">
                                                <option value="">업종</option>
                                                <option value="생산" <?= equal_return("생산", $arr_temp[0], "select") ?>>생산</option>
                                                <option value="물류" <?= equal_return("물류", $arr_temp[0], "select") ?>>물류</option>
                                                <option value="건설" <?= equal_return("건설", $arr_temp[0], "select") ?>>건설</option>
                                                <option value="식당" <?= equal_return("식당", $arr_temp[0], "select") ?>>식당</option>
                                                <option value="사무직" <?= equal_return("사무직", $arr_temp[0], "select") ?>>사무직</option>
                                                <option value="서비스" <?= equal_return("서비스", $arr_temp[0], "select") ?>>서비스</option>
                                                <option value="상담" <?= equal_return("상담", $arr_temp[0], "select") ?>>상담</option>
                                                <option value="운전배달" <?= equal_return("운전배달", $arr_temp[0], "select") ?>>운전배달</option>
                                                <option value="기타" <?= equal_return("기타", $arr_temp[0], "select") ?>>기타</option>
                                            </select>
                                            <span class="liner"></span>
                                        </label>
                                    </div>
                                    <div class="col">
                                        <div class="meta">
                                            <label>
                                                <span class="sr-text">업체명 입력</span>
                                                <input type="text" name="career_company[]" maxlength="13" placeholder="업체명" value="<?= isset($arr_temp[1]) ? $arr_temp[1] : "" ?>" autocomplete="off" class="form-text">
                                            </label>
                                            <label class="datepicker-wrapper">
                                                <span class="sr-text">근무기간 입력</span>
                                                <input type="text" name="career_period[]" placeholder="근무기간을 선택하세요" value="<?= isset($arr_temp[2]) ? $arr_temp[2] : "" ?>" autocomplete="off" class="form-text num js-career-datepicker" readonly>
                                                <span class="datepicker-icon"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
                <button type="button" class="btn-add-item js-add-career">등록</button>

                <template id="careerTemp">
                    <li>
                        <div class="form-group">
                            <div class="col sm">
                                <label class="form-selectbox js-career-category">
                                    <span class="sr-text">업종선택</span>
                                    <select name="career_category[]">
                                        <option value="">업종</option>
                                        <option value="생산">생산</option>
                                        <option value="물류">물류</option>
                                        <option value="건설">건설</option>
                                        <option value="식당">식당</option>
                                        <option value="사무직">사무직</option>
                                        <option value="서비스">서비스</option>
                                        <option value="상담">상담</option>
                                        <option value="운전배달">운전배달</option>
                                        <option value="기타">기타</option>
                                    </select>
                                    <span class="liner"></span>
                                </label>
                            </div>
                            <div class="col">
                                <div class="meta">
                                    <label>
                                        <span class="sr-text">업체명 입력</span>
                                        <input type="text" name="career_company[]" maxlength="13" placeholder="업체명" autocomplete="off" class="form-text">
                                    </label>
                                    <label class="datepicker-wrapper">
                                        <span class="sr-text">근무기간 입력</span>
                                        <input type="text" name="career_period[]" placeholder="근무기간을 선택하세요" autocomplete="off" class="form-text num js-career-datepicker" readonly>
                                        <span class="datepicker-icon"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                    </li>
                </template>
            </div>
            <div class="form-row js-detail-section" data-section="license">
                <p class="form-label">자격증 <em>허위사실 기재시 합격이 취소될 수 있습니다</em></p>
                <ol class="added-list">
                    <?php $arr_count = 0; ?>
                    <?php if ($info["arr_certi"] != "") :
                        $arr_crtyi = explode("&&&", $info["arr_certi"]);
                        $arr_count = count($arr_crtyi);
                        foreach ($arr_crtyi as $row):
                            $arr_temp = explode("|||", $row);
                    ?>
                            <li>
                                <div class="form-group">
                                    <div class="col md">
                                        <label>
                                            <span class="sr-text">자격증명 입력</span>
                                            <input type="text" name="license[]" maxlength="13" placeholder="자격증명" value="<?= $arr_temp[0] ?>" autocomplete="off" class="form-text name">
                                        </label>
                                    </div>
                                    <div class="col">
                                        <label>
                                            <span class="sr-text">취득일자 입력</span>
                                            <input type="text" name="regdate[]" placeholder="취득일자" value="<?= $arr_temp[1] ?>" autocomplete="off" class="form-text num js-datepicker">
                                        </label>
                                    </div>
                                </div>
                                <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
                <button type="button" class="btn-add-item js-add-license">등록</button>

                <template id="licenseTemp">
                    <li>
                        <div class="form-group">
                            <div class="col md">
                                <label>
                                    <span class="sr-text">자격증명 입력</span>
                                    <input type="text" name="license[]" maxlength="13" placeholder="자격증명" autocomplete="off" class="form-text name">
                                </label>
                            </div>
                            <div class="col">
                                <label>
                                    <span class="sr-text">취득일자 입력</span>
                                    <input type="text" name="regdate[]" placeholder="취득일자" autocomplete="off" class="form-text num js-datepicker">
                                </label>
                            </div>
                        </div>
                        <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                    </li>
                </template>
            </div>
            <div class="form-row">
                <a href="javascript:void(0);" class="detail-register-btn js-detail-register">
                    <span class="add-icon">+</span>상세등록
                </a>
            </div>
            <div class="sticky-bottom view-footer">
                <button id="btnStep" type="button" class="btn btn-primary">미리보기</button>
            </div>
                </div>
            </div>
        </form>
    </div><!-- //page-content -->
</div>

<input type="hidden" name="ru_idx" value="<?= $info["ru_idx"] ?>" />
<script>
    // 서버에서 받은 주소 데이터를 JavaScript 변수로 전달
    // 수정 모드(ru_idx가 0이 아니고 이력서명이 있을 때)일 때만 주소 데이터 전달
    var serverAddrList = <?= (isset($info["ru_idx"]) && $info["ru_idx"] != 0 && !empty($info["ru_name"]) && isset($addr_list) && is_array($addr_list)) ? json_encode($addr_list, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) : '[]' ?>;
    
    // 이력서 작성 페이지의 뒤로가기 버튼 핸들러를 가장 먼저 등록 (전역 핸들러보다 우선)
    // 캡처 단계에서 등록하여 다른 핸들러보다 먼저 실행되도록 함
    (function() {
        // 네이티브 이벤트 리스너를 캡처 단계에서 등록
        document.addEventListener("click", function(e) {
            var target = e.target;
            // btn-back 요소 또는 그 자식 요소인지 확인
            var btnBack = target.closest(".btn-back");
            if (btnBack) {
                var currentPath = window.location.pathname;
                if (currentPath.indexOf('/mypage/resume_write1') > -1) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    var ruIdx = $("[name='ru_idx']").val() || "0";
                    // clearResumeDraft 함수가 정의되어 있으면 호출
                    if (typeof clearResumeDraft === 'function') {
                        clearResumeDraft(ruIdx);
                    }
                    location.href = "/mypage/resume_list";
                    return false;
                }
            }
        }, true); // 캡처 단계에서 실행
    })();

    function getResumeDraftKey(step) {
        var ruIdx = $("[name='ru_idx']").val() || "0";
        return "resume_draft_" + ruIdx + "_step" + step;
    }

    function migrateResumeDraftKeys(oldIdx, newIdx, steps) {
        if (!oldIdx || !newIdx || oldIdx === newIdx) return;
        (steps || []).forEach(function(step) {
            var oldKey = "resume_draft_" + oldIdx + "_step" + step;
            var newKey = "resume_draft_" + newIdx + "_step" + step;
            var saved = localStorage.getItem(oldKey);
            if (saved && !localStorage.getItem(newKey)) {
                localStorage.setItem(newKey, saved);
            }
            localStorage.removeItem(oldKey);
        });
    }

    function clearResumeDraft(ruIdx) {
        var targetIdx = ruIdx || $("[name='ru_idx']").val() || "0";
        ["resume_draft_" + targetIdx + "_step1", "resume_draft_" + targetIdx + "_step4"].forEach(function(key) {
            localStorage.removeItem(key);
        });
    }

    function createAddressListItem(addrData) {
        if (!addrData || !addrData.ad_addr) return "";
        var detail = addrData.ad_detail ? addrData.ad_detail : "";
        var zipcode = addrData.ad_zipcode ? addrData.ad_zipcode : "";
        var jibun = addrData.ad_jibun ? addrData.ad_jibun : "";
        var doro = addrData.ad_doro ? addrData.ad_doro : "";
        var gpsX = addrData.ad_gps_x ? addrData.ad_gps_x : "";
        var gpsY = addrData.ad_gps_y ? addrData.ad_gps_y : "";
        var detailValue = detail ? detail : "";
        
        // 주소 정리 함수로 지번 주소를 정리하여 표시
        var displayAddr = (typeof fRemoveBigAdrJS === 'function') ? fRemoveBigAdrJS(jibun) : jibun;
        displayAddr = displayAddr + (detail ? " " + detail : "");

        return '' +
            '<li class="location-field-item">' +
            '<input type="hidden" name="ad_zipcode[]" value="' + zipcode + '" />' +
            '<input type="hidden" name="ad_jibun[]" value="' + jibun + '" />' +
            '<input type="hidden" name="ad_doro[]" value="' + doro + '" />' +
            // ad_addr에는 지번을 클릭하든 도로명을 클릭하든 항상 지번을 저장
            '<input type="hidden" name="ad_addr[]" value="' + jibun + '" />' +
            '<input type="hidden" name="ad_detail[]" value="' + detailValue + '" />' +
            '<input type="hidden" name="ad_gps_x[]" value="' + gpsX + '" />' +
            '<input type="hidden" name="ad_gps_y[]" value="' + gpsY + '" />' +
            '<span class="location-text">' + displayAddr + '</span>' +
            '<button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>' +
            '</li>';
    }

    function ensureAddressAddButton(currentCount) {
        var $list = $("#addr_location");
        $list.find(".location-field-add").remove();
        if (currentCount < 2) {
            var addHtml = '' +
                '<li class="location-field-item location-field-add">' +
                '<a href="javascript:void(0);" onclick="showAddrInfo(2);" role="button" class="location-add-btn">' +
                '<span class="add-icon">+</span>' +
                '</a>' +
                '</li>';
            $list.append(addHtml);
        }
    }

    function rebuildAddressList(addrList) {
        if (!Array.isArray(addrList)) {
            ensureAddressAddButton($("#addr_location .location-field-item:not(.location-field-add)").length);
            return;
        }
        var $list = $("#addr_location");
        $list.find(".location-field-item").remove();
        var count = 0;
        addrList.forEach(function(item) {
            if (!item || !item.ad_addr) {
                return;
            }
            $list.append(createAddressListItem(item));
            count++;
        });
        ensureAddressAddButton(count);
    }

    function saveResumeStep1Data() {
        try {
            var storageKey = getResumeDraftKey(1);
            var data = {
                ru_name: $("[name='ru_name']").val() || "",
                ru_nationality: $("[name='ru_nationality']:checked").val() || "",
                ru_work: [],
                ru_salary_negotiation: $("[name='ru_salary_negotiation']").is(":checked") ? "Y" : "N",
                ru_price_type: [],
                ru_work_negotiation: $("[name='ru_work_negotiation']").is(":checked") ? "Y" : "N",
                ru_work_type: [],
                addr_list: []
            };

            $("[name='ru_work[]']:checked").each(function() {
                data.ru_work.push($(this).val());
            });
            $("[name='ru_price_type[]']:checked").each(function() {
                data.ru_price_type.push($(this).val());
            });
            $("[name='ru_work_type[]']:checked").each(function() {
                data.ru_work_type.push($(this).val());
            });

            $("#addr_location .location-field-item:not(.location-field-add)").each(function() {
                var $item = $(this);
                data.addr_list.push({
                    ad_zipcode: $item.find("input[name='ad_zipcode[]']").val() || "",
                    ad_jibun: $item.find("input[name='ad_jibun[]']").val() || "",
                    ad_doro: $item.find("input[name='ad_doro[]']").val() || "",
                    ad_addr: $item.find("input[name='ad_addr[]']").val() || "",
                    ad_detail: $item.find("input[name='ad_detail[]']").val() || "",
                    ad_gps_x: $item.find("input[name='ad_gps_x[]']").val() || "",
                    ad_gps_y: $item.find("input[name='ad_gps_y[]']").val() || ""
                });
            });

            localStorage.setItem(storageKey, JSON.stringify(data));
        } catch (e) {
            console.error("resume step1 저장 실패:", e);
        }
    }

    function saveResumeStep4Data() {
        try {
            var storageKey = getResumeDraftKey(4);
            var data = {
                ru_desc: $("[name='ru_desc']").val() || "",
                ru_my_type: [],
                ru_week_yn: $("[name='ru_week_yn']").is(":checked") ? "Y" : "N",
                ru_week: [],
                detail_open: $(".js-detail-section").hasClass("show"),
                arr_career: [],
                arr_certi: []
            };

            $("[name='ru_my_type[]']:checked").each(function() {
                data.ru_my_type.push($(this).val());
            });
            $("[name='ru_week[]']:checked").each(function() {
                data.ru_week.push($(this).val());
            });

            $(".form-row[data-section='career'] .added-list").children("li").each(function() {
                if ($(this).find(".form-group").length === 0) return;
                data.arr_career.push({
                    category: $(this).find("[name='career_category[]']").val() || "",
                    company: $(this).find("[name='career_company[]']").val() || "",
                    period: $(this).find("[name='career_period[]']").val() || ""
                });
            });

            $(".form-row[data-section='license'] .added-list").children("li").each(function() {
                if ($(this).find(".form-group").length === 0) return;
                data.arr_certi.push({
                    license: $(this).find("[name='license[]']").val() || "",
                    regdate: $(this).find("[name='regdate[]']").val() || ""
                });
            });

            localStorage.setItem(storageKey, JSON.stringify(data));
        } catch (e) {
            console.error("resume step4 저장 실패:", e);
        }
    }

    function cleanupEmptyListItems() {
        $(".form-row[data-section='career'] .added-list, .form-row[data-section='license'] .added-list").each(function() {
            var $list = $(this);
            var listElement = this;

            $list.children("li").each(function() {
                var $li = $(this);
                var liElement = this;

                if ($li.hasClass('is-deleted')) {
                    if (liElement.parentNode) {
                        liElement.parentNode.removeChild(liElement);
                    }
                    return;
                }

                if ($li.find('.form-group').length === 0) {
                    if (liElement.parentNode) {
                        liElement.parentNode.removeChild(liElement);
                    }
                    return;
                }

                if (liElement.parentNode !== listElement) {
                    if (liElement.parentNode) {
                        liElement.parentNode.removeChild(liElement);
                    }
                    return;
                }
            });
        });
    }

    (function() {
        document.addEventListener("click", function(e) {
            var target = e.target;
            var delBtn = null;

            if (target.classList && target.classList.contains('js-del-item')) {
                delBtn = target;
            } else {
                var parent = target.parentElement;
                while (parent && parent !== document.body) {
                    if (parent.classList && parent.classList.contains('js-del-item')) {
                        delBtn = parent;
                        break;
                    }
                    parent = parent.parentElement;
                }
            }

            if (delBtn) {
                var $delBtn = $(delBtn);
                var $item = $delBtn.closest("li");
                var $list = $item.closest(".added-list");

                var $formRow = $list.closest(".form-row");
                if ($formRow.length > 0 && ($formRow.data("section") === "career" || $formRow.data("section") === "license")) {
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    e.preventDefault();

                    var itemElement = $item[0];
                    if (itemElement && itemElement.parentNode) {
                        itemElement.classList.remove('is-deleted');
                        itemElement.style.height = '';
                        itemElement.style.width = '';

                        var parentNode = itemElement.parentNode;
                        parentNode.removeChild(itemElement);
                    }

                    setTimeout(function() {
                        cleanupEmptyListItems();
                        saveResumeStep4Data();
                    }, 50);

                    var $addBtn = $list.siblings(".js-add-career, .js-add-license");
                    if ($addBtn.length > 0) {
                        $addBtn.css('display', '').show();
                    }

                    return false;
                }
            }
        }, true);
    })();

    function renderCareerItems(items) {
        var $list = $(".form-row[data-section='career'] .added-list");
        if ($list.length === 0 || !Array.isArray(items) || items.length === 0) return;
        $list.empty();

        items.forEach(function(item) {
            var template = $("#careerTemp").html();
            if (!template) return;
            var $node = $(template);
            $node.find("[name='career_category[]']").val(item.category || "");
            $node.find("[name='career_company[]']").val(item.company || "");
            $node.find("[name='career_period[]']").val(item.period || "");
            initCareerDatepicker($node.find(".js-career-datepicker")[0]);
            if (typeof initSelectbox === 'function') {
                initSelectbox($node.find(".js-career-category")[0]);
            }
            $list.append($node);
        });
    }

    function renderLicenseItems(items) {
        var $list = $(".form-row[data-section='license'] .added-list");
        if ($list.length === 0 || !Array.isArray(items) || items.length === 0) return;
        $list.empty();

        items.forEach(function(item) {
            var template = $("#licenseTemp").html();
            if (!template) return;
            var $node = $(template);
            $node.find("[name='license[]']").val(item.license || "");
            $node.find("[name='regdate[]']").val(item.regdate || "");
            initLicenseDatepicker($node.find(".js-datepicker")[0]);
            $list.append($node);
        });
    }

    function loadResumeStep4Data() {
        var storageKey = getResumeDraftKey(4);
        var saved = localStorage.getItem(storageKey);

        var defaultWeekDays = ["월", "화", "수", "목", "금"];
        var hasSavedData = false;

        if (saved) {
            try {
                var formData = JSON.parse(saved);
                if (formData) {
                    hasSavedData = true;

                    if (typeof formData.ru_desc !== "undefined") {
                        $("[name='ru_desc']").val(formData.ru_desc);
                        if (typeof autoHeight === 'function') {
                            setTimeout(function() {
                                autoHeight($("[name='ru_desc']")[0]);
                            }, 50);
                        }
                    }

                    $("[name='ru_my_type[]']").prop("checked", false);
                    if (Array.isArray(formData.ru_my_type)) {
                        formData.ru_my_type.forEach(function(val) {
                            $("[name='ru_my_type[]'][value='" + val + "']").prop("checked", true);
                        });
                    }

                    $("[name='ru_week_yn']").prop("checked", formData.ru_week_yn === "Y");
                    $("[name='ru_week[]']").prop("checked", false);
                    if (Array.isArray(formData.ru_week) && formData.ru_week.length > 0) {
                        formData.ru_week.forEach(function(val) {
                            $("[name='ru_week[]'][value='" + val + "']").prop("checked", true);
                        });
                    } else {
                        defaultWeekDays.forEach(function(val) {
                            $("[name='ru_week[]'][value='" + val + "']").prop("checked", true);
                        });
                        saveResumeStep4Data();
                    }
                }
            } catch (e) {
                console.error("resume step4 복원 실패:", e);
            }
        }

        if (!hasSavedData) {
            $("[name='ru_week[]']").prop("checked", false);
            defaultWeekDays.forEach(function(val) {
                $("[name='ru_week[]'][value='" + val + "']").prop("checked", true);
            });
            saveResumeStep4Data();
        }

        if (saved && hasSavedData) {
            try {
                var formData = JSON.parse(saved);
                if (formData) {
                    if (formData.detail_open) {
                        $(".js-detail-section").addClass("show").show();
                    }

                    if (Array.isArray(formData.arr_career)) {
                        $(".form-row[data-section='career'] .added-list").empty();
                        if (formData.arr_career.length > 0) {
                            renderCareerItems(formData.arr_career);
                        }
                    }

                    if (Array.isArray(formData.arr_certi)) {
                        $(".form-row[data-section='license'] .added-list").empty();
                        if (formData.arr_certi.length > 0) {
                            renderLicenseItems(formData.arr_certi);
                        }
                    }

                    cleanupEmptyListItems();
                }
            } catch (e) {
                console.error("resume step4 복원 실패:", e);
            }
        }
    }

    // 서버에서 받은 주소 데이터를 로드하는 함수
    // 수정 모드(ru_idx가 0이 아니고 이력서명이 있을 때)일 때만 로드
    function loadServerAddressData() {
        var ruIdx = $("[name='ru_idx']").val() || "0";
        var ruName = $("[name='ru_name']").val() || "";
        
        // 수정 모드가 아니면 주소 데이터를 로드하지 않음
        if (ruIdx == "0" || ruName == "") {
            return false;
        }
        
        if (typeof serverAddrList !== 'undefined' && Array.isArray(serverAddrList) && serverAddrList.length > 0) {
            rebuildAddressList(serverAddrList);
            
            // 주소 정리 함수로 처리 (서버 데이터 로드 후 표시 형식 정리)
            if (typeof fRemoveBigAdrJS === 'function') {
                setTimeout(function() {
                    $("#addr_location .location-field-item:not(.location-field-add)").each(function() {
                        var $li = $(this);
                        var $display = $li.find('.location-text');
                        var jibun = $li.find('input[name="ad_jibun[]"]').val();
                        var detail = $li.find('input[name="ad_detail[]"]').val();
                        if (jibun) {
                            var displayAddr = fRemoveBigAdrJS(jibun);
                            displayAddr = displayAddr + (detail ? " " + detail : "");
                            $display.text(displayAddr);
                        }
                    });
                }, 50);
            }
            return true; // 서버 데이터가 있음을 반환
        }
        return false; // 서버 데이터가 없음을 반환
    }

    function loadResumeStep1Data() {
        var storageKey = getResumeDraftKey(1);
        var saved = localStorage.getItem(storageKey);
        if (!saved) return;

        try {
            var formData = JSON.parse(saved);
            if (!formData) return;

            if (typeof formData.ru_name !== "undefined") {
                $("[name='ru_name']").val(formData.ru_name);
            }
            if (formData.ru_nationality) {
                $("[name='ru_nationality'][value='" + formData.ru_nationality + "']").prop("checked", true);
            }

            $("[name='ru_work[]']").prop("checked", false);
            if (Array.isArray(formData.ru_work)) {
                formData.ru_work.forEach(function(val) {
                    $("[name='ru_work[]'][value='" + val + "']").prop("checked", true);
                });
            }

            $("[name='ru_salary_negotiation']").prop("checked", formData.ru_salary_negotiation === "Y");

            $("[name='ru_price_type[]']").prop("checked", false);
            if (Array.isArray(formData.ru_price_type)) {
                formData.ru_price_type.forEach(function(val) {
                    $("[name='ru_price_type[]'][value='" + val + "']").prop("checked", true);
                });
            }

            $("[name='ru_work_negotiation']").prop("checked", formData.ru_work_negotiation === "Y");

            $("[name='ru_work_type[]']").prop("checked", false);
            if (Array.isArray(formData.ru_work_type)) {
                formData.ru_work_type.forEach(function(val) {
                    $("[name='ru_work_type[]'][value='" + val + "']").prop("checked", true);
                });
            }

            if (Array.isArray(formData.addr_list)) {
                rebuildAddressList(formData.addr_list);
                
                // 주소 정리 함수로 처리 (복원 후 표시 형식 정리)
                if (typeof fRemoveBigAdrJS === 'function') {
                    setTimeout(function() {
                        $("#addr_location .location-field-item:not(.location-field-add)").each(function() {
                            var $li = $(this);
                            var $display = $li.find('.location-text');
                            var jibun = $li.find('input[name="ad_jibun[]"]').val();
                            var detail = $li.find('input[name="ad_detail[]"]').val();
                            if (jibun) {
                                var displayAddr = fRemoveBigAdrJS(jibun);
                                displayAddr = displayAddr + (detail ? " " + detail : "");
                                $display.text(displayAddr);
                            }
                        });
                    }, 50);
                }
            } else {
                ensureAddressAddButton($("#addr_location .location-field-item:not(.location-field-add)").length);
            }
        } catch (e) {
            console.error("resume step1 복원 실패:", e);
        }
    }

    function getDongFromAddr(addr) {
        if (!addr || typeof addr !== "string") return "";
        var tokens = addr.trim().split(/\s+/);
        return tokens.length ? tokens[tokens.length - 1] : "";
    }

    function applyResumeAutoFill() {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('autofill') !== '1') return;
        var isBgAutofill = (urlParams.get('bg') === '1');

        // 자동작성 모드에서는 화면을 숨겨 플리커를 방지
        document.documentElement.style.visibility = 'hidden';
        // logWebview 제거

        var payload = {};
        try {
            payload = JSON.parse(localStorage.getItem("resume_autofill_payload") || "{}");
        } catch (e) {
            payload = {};
        }

        var adAddr = payload.ad_addr || payload.ad_jibun || payload.ru_name_seed_addr || "";
        var regionName = (adAddr || "").trim();
        var dongName = getDongFromAddr(adAddr);

        var titleTemplates = [
            "[지역명]준비된 인재입니다!",
            "[지역명]바로 출근 가능합니다.",
            "[지역명]추천인재",
            "열심히 할수있습니다!",
            "최선을 다하는 인재이력서",
            "성실함과 꾸준함을 갖춘 인재",
            "책임감있는 최고의 인재",
            "찾고있는[지역명]의 인재",
            "출근준비되어있습니다.",
            "즉시줄근가능!추천인재입니다.",
            "바로 출근부터 면접까지!",
            "[지역명]에서 일찾는중",
            "[지역명]근처 일자리 찾는중"
        ];

        var chosenTitle = titleTemplates[Math.floor(Math.random() * titleTemplates.length)];
        if (chosenTitle.indexOf("[지역명]") > -1) {
            chosenTitle = chosenTitle.replace("[지역명]", regionName || dongName || "우리 동네");
        }

        if ($("[name='ru_name']").val() === "") {
            $("[name='ru_name']").val(chosenTitle);
        }

        if (payload.ru_nationality) {
            $("[name='ru_nationality'][value='" + payload.ru_nationality + "']").prop("checked", true);
        }
        if (payload.quickinfo_gender) {
            $("[name='quickinfo_gender']").val(payload.quickinfo_gender);
        }
        if (payload.quickinfo_age) {
            $("[name='quickinfo_age']").val(payload.quickinfo_age);
        }

        // 희망업무 자동 선택: 생산, 물류
        $("[name='ru_work[]']").prop("checked", false);
        ["생산", "물류"].forEach(function(val) {
            $("[name='ru_work[]'][value='" + val + "']").prop("checked", true);
        });

        // 희망급여: 급여협의 자동 선택 (전체 급여 타입 자동 체크)
        $("[name='ru_salary_negotiation']").prop("checked", true).trigger("change");

        // 근무형태: 근무협의 자동 선택 (전체 근무형태 자동 체크)
        $("[name='ru_work_negotiation']").prop("checked", true).trigger("change");

        // 희망지역 자동 선택
        if (adAddr) {
            var addrItem = {
                ad_zipcode: payload.ad_zipcode || "",
                ad_jibun: payload.ad_jibun || adAddr,
                ad_doro: payload.ad_doro || "",
                ad_addr: payload.ad_addr || adAddr,
                ad_detail: "",
                ad_gps_x: payload.ad_gps_x || "",
                ad_gps_y: payload.ad_gps_y || ""
            };
            rebuildAddressList([addrItem]);
        }

        // step4에서 사용할 수 있도록 ru_name 저장
        payload.ru_name = $("[name='ru_name']").val() || chosenTitle;
        try {
            localStorage.setItem("resume_autofill_payload", JSON.stringify(payload));
            localStorage.setItem("resume_autofill_ts", String(Date.now()));
        } catch (e) {}

        saveResumeStep1Data();
        window.__resumeAutoFlow = true;
        window.__resumeAutoBg = isBgAutofill;

        // step4 자동작성
        var ruName = payload.ru_name || "";
        if ($("[name='ru_desc']").val() === "" && ruName) {
            $("[name='ru_desc']").val(ruName);
            if (typeof autoHeight === 'function') {
                setTimeout(function() {
                    autoHeight($("[name='ru_desc']")[0]);
                }, 50);
            }
        }

        var $allTypes = $("[name='ru_my_type[]']");
        if ($allTypes.length > 0) {
            $allTypes.prop("checked", false);
            var candidates = $allTypes.toArray();
            for (var i = candidates.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var temp = candidates[i];
                candidates[i] = candidates[j];
                candidates[j] = temp;
            }
            var pickCount = Math.min(2, candidates.length);
            for (var k = 0; k < pickCount; k++) {
                $(candidates[k]).prop("checked", true);
            }
        }

        saveResumeStep4Data();

        // 자동 이동이 지연되거나 실패할 경우 화면이 계속 숨겨지는 문제 방지
        setTimeout(function() {
            document.documentElement.style.visibility = 'visible';
            document.documentElement.classList.remove('autofill-hide');
        }, 700);

        setTimeout(function() {
            $("#btnStep").trigger("click");
        }, 150);
    }

    // 검색 완료 선택 처리 (상세주소 파라미터 추가)
    function selectAddrInfo(zipcode, doro, jibun, addr, x, y, detail) {
        var currentCount = $("#addr_location .location-field-item:not(.location-field-add)").length;

        if (currentCount >= 2) {
            commonAlert("희망지역은 2개까지 등록 가능합니다.", function() {});
            $("#changeArea").removeClass("on");
            return;
        }

        detail = detail || "";

        // 2025.02.10 위치 보안: 상세 주소 대신 동네 중심점 좌표 저장
        var geocoderAvailable = (window.kakao && kakao.maps && kakao.maps.services && kakao.maps.services.Geocoder);
        if (!geocoderAvailable) {
            finalizeAddrInfo(x, y);
            return;
        }

        var geocoder = new kakao.maps.services.Geocoder();
        // 지번 주소에서 번지 및 그 뒤의 건물명 등을 모두 제거하고 동(읍/면)까지만 추출
        // 예: "경기도 시흥시 정왕동 1866-1 정왕3동주민센터" -> "경기도 시흥시 정왕동"
        // 숫자(번지)가 나오기 직전까지만 잘라냄
        var dongAddr = jibun;
        var match = jibun.match(/^(.*?)(\d+[\d\-]*)/);
        if (match) {
            dongAddr = match[1].trim();
        }

        geocoder.addressSearch(dongAddr, function(result, status) {
            var finalX = x;
            var finalY = y;

            if (status === kakao.maps.services.Status.OK) {
                if (result[0].x && result[0].y) {
                    finalX = result[0].x;
                    finalY = result[0].y;
                }
            }

            finalizeAddrInfo(finalX, finalY);
        });

        function finalizeAddrInfo(finalX, finalY) {
            var item = {
                ad_zipcode: zipcode,
                ad_jibun: jibun,
                ad_doro: doro,
                // ad_addr에는 지번을 클릭하든 도로명을 클릭하든 항상 지번을 저장
                ad_addr: jibun,
                ad_detail: detail,
                ad_gps_x: finalX,
                ad_gps_y: finalY
            };
            var html = createAddressListItem(item);
            if (html === "") {
                $("#changeArea").removeClass("on");
                return;
            }

            var $addBtn = $("#addr_location .location-field-add");
            if ($addBtn.length > 0) {
                $addBtn.before(html);
            } else {
                $("#addr_location").append(html);
            }

            ensureAddressAddButton($("#addr_location .location-field-item:not(.location-field-add)").length);
            $("#changeArea").removeClass("on");
            saveResumeStep1Data();
        }
    }

    function showAddrInfo(maxlen) {
        var currentCount = $("#addr_location .location-field-item:not(.location-field-add)").length;
        if (currentCount >= maxlen) {
            commonAlert("희망지역은 " + maxlen + "개까지 등록 가능합니다.", function() {});
            return;
        }
        window.__hideAllRegionsInAddrPopup = true;
        if (typeof window.applyAddrPopupAllRegionFilter === 'function') {
            window.applyAddrPopupAllRegionFilter();
        }
        $("#changeArea").addClass("on");
    }

    window.onChangeAreaApply = function(payload) {
        // 선택된 지역을 searchArea 배열에서 가져오기
        var searchArea = payload.searchArea || [];
        var maxCount = 2;
        var currentCount = $("#addr_location .location-field-item:not(.location-field-add)").length;
        var remaining = maxCount - currentCount;

        if (searchArea.length === 0) return true;

        // 2개 초과 선택 시 앞에서 remaining개만 처리
        var toProcess = searchArea.slice(0, remaining);
        if (toProcess.length === 0) {
            commonAlert("희망지역은 2개까지 등록 가능합니다.", function() {});
            return true;
        }
        if (searchArea.length > toProcess.length) {
            commonAlert("희망지역은 최대 2개까지 등록됩니다. 앞의 " + toProcess.length + "개만 적용됩니다.", function() {});
        }

        var geocoderAvailable = (window.kakao && kakao.maps && kakao.maps.services && kakao.maps.services.Geocoder);

        function addAddrFromRegion(addr, x, y) {
            var item = {
                ad_zipcode: "",
                ad_jibun: addr,
                ad_doro: "",
                ad_addr: addr,
                ad_detail: "",
                ad_gps_x: x,
                ad_gps_y: y
            };
            var html = createAddressListItem(item);
            if (html === "") return;
            var $addBtn = $("#addr_location .location-field-add");
            if ($addBtn.length > 0) {
                $addBtn.before(html);
            } else {
                $("#addr_location").append(html);
            }
            var newCount = $("#addr_location .location-field-item:not(.location-field-add)").length;
            ensureAddressAddButton(newCount);
            saveResumeStep1Data();
        }

        function processItem(addr) {
            if (!geocoderAvailable) {
                addAddrFromRegion(addr, "", "");
                return;
            }
            var geocoder = new kakao.maps.services.Geocoder();
            geocoder.addressSearch(addr, function(result, status) {
                var x = "", y = "";
                if (status === kakao.maps.services.Status.OK && result[0]) {
                    x = result[0].x;
                    y = result[0].y;
                }
                addAddrFromRegion(addr, x, y);
            });
        }

        toProcess.forEach(function(item) {
            processItem(item.addr);
        });

        return true; // 기본 목록 갱신(getBoardList) 생략
    };

    // DOMContentLoaded 이벤트로 더 빠르게 처리 (깜빡임 방지)
    document.addEventListener('DOMContentLoaded', function() {
        // 먼저 서버에서 받은 주소 데이터를 로드
        var hasServerData = loadServerAddressData();
        
        // 서버 데이터가 없을 때만 로컬스토리지에서 복원
        if (!hasServerData) {
            loadResumeStep1Data();
        }
        
        ensureAddressAddButton($("#addr_location .location-field-item:not(.location-field-add)").length);
        cleanupEmptyListItems();
        loadResumeStep4Data();
        applyResumeAutoFill();
    });

    $(document).ready(function() {

        // 희망지역 삭제 버튼 이벤트
        $(document).on("click", "#addr_location .js-del-item", function() {
            var $item = $(this).closest(".location-field-item");
            var currentCount = $("#addr_location .location-field-item:not(.location-field-add)").length;

            $item.remove();

            ensureAddressAddButton(currentCount - 1);
            saveResumeStep1Data();
        });

        // 급여협의 체크박스 클릭 이벤트
        $("[name='ru_salary_negotiation']").on("change", function() {
            var isChecked = $(this).is(":checked");
            if (isChecked) {
                $("[name='ru_price_type[]']").prop("checked", true);
            } else {
                $("[name='ru_price_type[]']").prop("checked", false);
            }
            saveResumeStep1Data();
        });

        // 근무협의 체크박스 클릭 이벤트
        $("[name='ru_work_negotiation']").on("change", function() {
            var isChecked = $(this).is(":checked");
            if (isChecked) {
                $("[name='ru_work_type[]']").prop("checked", true);
            } else {
                $("[name='ru_work_type[]']").prop("checked", false);
            }
            saveResumeStep1Data();
        });

        // 협의가 체크된 경우 개별 항목 체크 해제 방지
        $("[name='ru_price_type[]']").on("change", function() {
            if ($("[name='ru_salary_negotiation']").is(":checked")) {
                if (!$(this).is(":checked")) {
                    $(this).prop("checked", true);
                }
            }
            saveResumeStep1Data();
        });

        $("[name='ru_work_type[]']").on("change", function() {
            if ($("[name='ru_work_negotiation']").is(":checked")) {
                if (!$(this).is(":checked")) {
                    $(this).prop("checked", true);
                }
            }
            saveResumeStep1Data();
        });

        // form-checkbox의 label 클릭 시 체크박스 토글
        $(document).on("click", ".form-checkbox .label", function(e) {
            e.preventDefault();
            var $checkbox = $(this).siblings("input[type='checkbox']");
            if ($checkbox.length > 0) {
                $checkbox.prop("checked", !$checkbox.prop("checked"));
                $checkbox.trigger("change");
            }
        });

        $("[name='ru_name']").on("input", saveResumeStep1Data);
        $("[name='ru_nationality']").on("change", saveResumeStep1Data);
        $("[name='ru_work[]']").on("change", saveResumeStep1Data);
        $("[name='ru_price_type[]']").on("change", saveResumeStep1Data);
        $("[name='ru_work_type[]']").on("change", saveResumeStep1Data);
        $("[name='ru_my_type[]']").on("change", function() {
            var limit = 6;
            var childItems = document.querySelectorAll('[name="ru_my_type[]"]:checked');

            if (this.checked && childItems.length > limit) {
                this.checked = false;
                commonAlert("최대 " + limit + "개까지 선택 가능합니다.", function() {});
                return;
            }
            saveResumeStep4Data();
        });
        $("[name='ru_desc']").on("input", saveResumeStep4Data);
        $(document).on("input change", "[name='career_category[]'], [name='career_company[]'], [name='career_period[]'], [name='license[]'], [name='regdate[]']", function() {
            saveResumeStep4Data();
        });

        $(".js-detail-register").click(function(e) {
            e.preventDefault();
            var $sections = $(".js-detail-section");
            var $button = $(this);

            var isVisible = $sections.hasClass("show");

            if (isVisible) {
                $sections.removeClass("show").slideUp(300);
            } else {
                $sections.addClass("show").slideDown(300, function() {
                    var buttonTop = $button.offset().top;
                    var windowHeight = $(window).height();
                    var scrollTarget = buttonTop - (windowHeight / 2);
                    $('html, body').animate({
                        scrollTop: scrollTarget
                    }, 400);
                });
            }
            setTimeout(saveResumeStep4Data, 350);
        });

        $("[name='ru_week_yn']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='ru_week[]']").prop("checked", true);
            } else {
                $("[name='ru_week[]']").prop("checked", false);
            }
            saveResumeStep4Data();
        });

        $("[name='ru_week[]']").on("change", function() {
            if ($("[name='ru_week_yn']").is(":checked")) {
                $("[name='ru_week_yn']").prop("checked", false);
            }
            saveResumeStep4Data();
        });

        $(".js-add-career").on("click", function(e) {
            e.preventDefault();
            var $list = $(this).siblings(".added-list");

            cleanupEmptyListItems();

            var currentCount = 0;
            var listElement = $list[0];

            $list.children("li").each(function() {
                var $li = $(this);
                var liElement = this;

                if (!$li.hasClass('is-deleted') &&
                    liElement.parentNode === listElement &&
                    document.body.contains(liElement) &&
                    $li.find('.form-group').length > 0) {
                    currentCount++;
                }
            });

            if (currentCount >= 3) {
                commonAlert("경력사항은 최대 3개까지만 등록 가능합니다.", function() {});
                return;
            }

            var $temp = $("#careerTemp").html();
            var $newItem = $("<li>").html($temp);
            $list.append($newItem);

            initCareerDatepicker($newItem.find(".js-career-datepicker")[0]);

            if (typeof initSelectbox === 'function') {
                initSelectbox($newItem.find(".js-career-category")[0]);
            }

            $(this).css('display', '').show();
            saveResumeStep4Data();
        });

        $(".js-add-license").on("click", function(e) {
            e.preventDefault();
            var $list = $(this).siblings(".added-list");

            cleanupEmptyListItems();

            var currentCount = 0;
            var listElement = $list[0];

            $list.children("li").each(function() {
                var $li = $(this);
                var liElement = this;

                if (!$li.hasClass('is-deleted') &&
                    liElement.parentNode === listElement &&
                    document.body.contains(liElement) &&
                    $li.find('.form-group').length > 0) {
                    currentCount++;
                }
            });

            if (currentCount >= 5) {
                commonAlert("자격증은 최대 5개까지만 등록 가능합니다.", function() {});
                return;
            }

            var $temp = $("#licenseTemp").html();
            var $newItem = $("<li>").html($temp);
            $list.append($newItem);

            initLicenseDatepicker($newItem.find(".js-datepicker")[0]);

            $(this).css('display', '').show();
            saveResumeStep4Data();
        });

        $("[name='ru_price_type[]']").change(function() {
            if ($("[name='ru_price_type[]']").eq(2).is(":checked")) {
                $(".cont-price").removeClass("hidden");
            } else {
                $(".cont-price").addClass("hidden");
            }
        });

        function saveStep4AndPreview($btn) {
            var ru_week = [];
            $("[name='ru_week[]']:checked").each(function() {
                ru_week.push($(this).val());
            });
            var ru_week_str = ru_week.join(",");
            var ru_week_yn_value = $("[name='ru_week_yn']").is(":checked") ? "Y" : "N";

            var arr_career = "";
            var $careerList = $(".form-row[data-section='career'] .added-list");
            $careerList.children("li").each(function() {
                if (!$(this).hasClass('is-deleted')) {
                    var category = $(this).find("[name='career_category[]']").val();
                    var company = $(this).find("[name='career_company[]']").val();
                    var period = $(this).find("[name='career_period[]']").val();

                    if (category != "" && company != "" && period != "") {
                        if (arr_career == "") {
                            arr_career = category + "|||" + company + "|||" + period;
                        } else {
                            arr_career = arr_career + "&&&" + category + "|||" + company + "|||" + period;
                        }
                    }
                }
            });
            $("[name=arr_career]").val(arr_career);

            var arr_certi = "";
            var $licenseList = $(".form-row[data-section='license'] .added-list");
            $licenseList.children("li").each(function() {
                if (!$(this).hasClass('is-deleted')) {
                    var license = $(this).find("[name='license[]']").val();
                    var regdate = $(this).find("[name='regdate[]']").val();

                    if (license != "" && regdate != "") {
                        if (arr_certi == "") {
                            arr_certi = license + "|||" + regdate;
                        } else {
                            arr_certi = arr_certi + "&&&" + license + "|||" + regdate;
                        }
                    }
                }
            });
            $("[name=arr_certi]").val(arr_certi);

            var form = $('#frm');
            var params = new FormData(form[0]);
            params.append("ru_idx", $("[name=ru_idx]").val() || "0");
            params.append("ru_week_yn", ru_week_yn_value);
            if (ru_week_str) {
                params.append("ru_week", ru_week_str);
            }
            fileAjaxLoading("/mypage_proc/resume_write4", params, function(ret) {
                if (ret.code == "00") {
                    var prevIdx = $("[name=ru_idx]").val() || "0";
                    if (ret.data && ret.data.ru_idx) {
                        var newIdx = ret.data.ru_idx;
                        if (newIdx && newIdx !== prevIdx) {
                            migrateResumeDraftKeys(prevIdx, newIdx, [1, 4]);
                            $("[name=ru_idx]").val(newIdx);
                        }
                    }
                    if (window.__resumeAutoFlow === true) {
                        var finalIdx = $("[name=ru_idx]").val() || "0";
                        comAjax("/mypage_proc/resume_complete", {
                            ru_idx: finalIdx
                        }, function(ret2) {
                            if (ret2.code == "00") {
                                clearResumeDraft(finalIdx);
                                try {
                                    localStorage.removeItem("resume_autofill_payload");
                                    localStorage.removeItem("resume_autofill_ts");
                                    sessionStorage.setItem("resume_auto_completed", "1");
                                } catch (e) {}
                                if (window.__resumeAutoBg && window.parent) {
                                    window.parent.postMessage({
                                        type: 'resume_auto_complete',
                                        ok: true
                                    }, '*');
                                } else {
                                    location.href = "/recruit/job_list";
                                }
                            } else {
                                if (window.__resumeAutoBg && window.parent) {
                                    window.parent.postMessage({
                                        type: 'resume_auto_complete',
                                        ok: false,
                                        msg: ret2.msg
                                    }, '*');
                                } else {
                                    commonAlert(ret2.msg, function() {});
                                }
                            }
                        });
                        return;
                    }
                    showPreviewInfo(ret.data, $btn);
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        }

        //처리
        $("#btnStep").click(function() {
            saveResumeStep1Data();
            saveResumeStep4Data();

            if ($("[name='ru_name']").val() == "") {
                commonAlert("이력서명을 입력해주세요.", function() {
                    $("[name='ru_name']").focus();
                });
                return;
            }
            if ($("[name='ru_work[]']").is(":checked") == false) {
                commonAlert("희망업무는 하나이상 선택해주세요.", function() {});
                return;
            }

            var addrCount = $("#addr_location .location-field-item:not(.location-field-add)").filter(function() {
                var addrValue = $(this).find('input[name="ad_addr[]"]').val();
                return addrValue && addrValue.trim() !== "";
            }).length;

            if (addrCount == 0) {
                commonAlert("희망지역을 선택해주세요.", function() {});
                return;
            }

            if ($("[name='ru_price_type[]']").is(":checked") == false) {
                commonAlert("희망급여는 하나이상 선택해주세요.", function() {});
                return;
            }

            if ($("[name='ru_work_type[]']").is(":checked") == false) {
                commonAlert("근무형태는 하나이상 선택해주세요.", function() {});
                return;
            }

            if ($("[name='ru_desc']").val() == "") {
                commonAlert("자기소개를 입력해주세요.", function() {
                    $("[name='ru_desc']").focus();
                });
                return;
            }

            if ($("[name='ru_my_type[]']:checked").length == 0) {
                commonAlert("나의강점을 하나 이상 선택해주세요.", function() {});
                return;
            }

            var ru_work_img = "";
            for (var icnt = 0; icnt < $("[name='ru_work[]']:checked").length; icnt++) {
                if (ru_work_img == "") {
                    ru_work_img = $("[name='ru_work[]']:checked").eq(icnt).data("img");
                } else {
                    ru_work_img += "," + $("[name='ru_work[]']:checked").eq(icnt).data("img");
                }
            }
            $("[name=ru_work_img]").val(ru_work_img);

            var params = $("#frm").serialize();
            var prevIdx = $("[name=ru_idx]").val() || "0";
            var $btn = $(this);
            comAjax("/mypage_proc/resume_write1", params, function(ret) {
                if (ret.code == "00") {
                    var newIdx = prevIdx;
                    if (ret.data && ret.data.ru_idx) {
                        newIdx = ret.data.ru_idx;
                        if (newIdx && newIdx !== prevIdx) {
                            migrateResumeDraftKeys(prevIdx, newIdx, [1, 4]);
                            $("[name=ru_idx]").val(newIdx);
                        }
                    }
                    saveStep4AndPreview($btn);
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });

        $(".comma").on("keyup", function() {
            $(this).val($(this).val().replace(/\,/g, '').replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'));
            saveResumeStep1Data();
        });

        setTimeout(function() {
            $(".form-row[data-section='career'] .js-career-datepicker").each(function() {
                if (!$(this).data('flatpickr')) {
                    initCareerDatepicker(this);
                }
            });
        }, 100);

        setTimeout(function() {
            $(".form-row[data-section='license'] .js-datepicker").each(function() {
                if (!$(this).data('flatpickr')) {
                    initLicenseDatepicker(this);
                }
            });
        }, 100);

        setTimeout(function() {
            var checkedCount = $("[name='ru_week[]']:checked").length;
            if (checkedCount === 0) {
                var defaultWeekDays = ["월", "화", "수", "목", "금"];
                defaultWeekDays.forEach(function(val) {
                    $("[name='ru_week[]'][value='" + val + "']").prop("checked", true);
                });
                saveResumeStep4Data();
            }
        }, 200);

        // 뒤로가기 핸들러는 스크립트 시작 부분에서 이미 등록됨

        // Android 물리적 뒤로가기 버튼 및 iOS 스와이프 뒤로가기 처리
        history.pushState(null, null, location.href);
        
        window.addEventListener('popstate', function(e) {
            history.pushState(null, null, location.href);
            clearResumeDraft($("[name='ru_idx']").val() || "0");
            location.href = "/mypage/resume_list";
        });
    });

    function initCareerDatepicker(el) {
        if (typeof flatpickr == 'undefined' || !el) return;

        function chgYear(instance) {
            var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                year = instance.currentYear;
            $con.innerHTML = '<span class="month">' + year + '</span>';
        }

        var existingValue = $(el).val();
        var defaultDate = null;
        if (existingValue && existingValue.indexOf('~') > -1) {
            var dates = existingValue.split(' ~ ');
            if (dates.length === 2) {
                var startDate = dates[0].trim().replace(/\./g, '-') + '-01';
                var endDate = dates[1].trim().replace(/\./g, '-') + '-01';
                defaultDate = [startDate, endDate];
            }
        }

        flatpickr(el, {
            mode: 'range',
            locale: 'ko',
            altInput: true,
            altFormat: 'Y.m',
            dateFormat: 'Y-m-d',
            defaultDate: defaultDate,
            nextArrow: '<span class="sr-text">다음</span>',
            prevArrow: '<span class="sr-text">이전</span>',
            disableMobile: true,
            clickOpens: true,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y.m",
                    altFormat: "Y.m",
                })
            ],
            onYearChange: function(selectedDates, dateStr, instance) {
                chgYear(this);
            },
            onReady: function() {
                chgYear(this);
            },
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 1) {
                    var nextBtn = instance.calendarContainer.querySelector('.flatpickr-next-month');
                    if (nextBtn) {
                        nextBtn.click();
                    }
                }
            },
            onOpen: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 1) {
                    var prevBtn = instance.calendarContainer.querySelector('.flatpickr-prev-month');
                    if (prevBtn) {
                        prevBtn.click();
                    }
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                instance.calendarContainer.querySelectorAll('.flatpickr-monthSelect-month').forEach(function(month) {
                    month.classList.remove('startRange', 'inRange', 'endRange')
                });

                if (dateStr && dateStr.indexOf(' to ') > -1) {
                    var dates = dateStr.split(' to ');
                    if (dates.length === 2) {
                        var startDate = dates[0].substring(0, 7).replace(/-/g, '.');
                        var endDate = dates[1].substring(0, 7).replace(/-/g, '.');
                        var formattedDate = startDate + ' ~ ' + endDate;
                        $(el).val(formattedDate);
                    }
                } else if (dateStr) {
                    var formattedDate = dateStr.substring(0, 7).replace(/-/g, '.');
                    $(el).val(formattedDate);
                }
                saveResumeStep4Data();
            }
        });
    }

    function initLicenseDatepicker(el) {
        if (typeof flatpickr == 'undefined' || !el) return;

        function chgYear(instance) {
            var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                year = instance.currentYear;
            $con.innerHTML = '<span class="month">' + year + '</span>';
        }

        flatpickr(el, {
            locale: 'ko',
            nextArrow: '<span class="sr-text">다음</span>',
            prevArrow: '<span class="sr-text">이전</span>',
            disableMobile: true,
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "Y.m",
                    altFormat: "Y.m",
                })
            ],
            onYearChange: function(selectedDates, dateStr, instance) {
                chgYear(this);
            },
            onReady: function() {
                chgYear(this);
            },
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 1) {
                    document.querySelector('.flatpickr-next-month').click();
                }
            },
            onOpen: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 1) {
                    document.querySelector('.flatpickr-prev-month').click();
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                instance.calendarContainer.querySelectorAll('.flatpickr-monthSelect-month').forEach(function(month) {
                    month.classList.remove('startRange', 'inRange', 'endRange')
                });
                saveResumeStep4Data();
            }
        });
    }

    function showPreviewInfo(info, $btnElement) {
        var str_ru_work_img = "";
        var str_ru_week = "";
        var str_ru_career = "";
        var str_ru_certi = "";
        var str_ru_type = "";
        var str_month_price = "";

        if (info.ru_work_img) {
            for (var icnt = 0; icnt < info.ru_work_img.length; icnt++) {
                str_ru_work_img += "<img src='" + info.ru_work_img[icnt] + "' alt='생산' class='category-label'>";
            }
        }

        var ru_week_yn_value = info.ru_week_yn || ($("[name='ru_week_yn']").is(":checked") ? "Y" : "N");

        if (ru_week_yn_value == "Y") {
            str_ru_week = "<li aria-hidden='false'>무관</li>";
        } else if (info.ru_week) {
            var weekArray = typeof info.ru_week === 'string' ? info.ru_week.split(",") : info.ru_week;
            if (weekArray && weekArray.length > 0) {
                for (var icnt = 0; icnt < weekArray.length; icnt++) {
                    var weekValue = weekArray[icnt].trim();
                    if (weekValue) {
                        str_ru_week += "<li aria-hidden='false'>" + weekValue + "</li>";
                    }
                }
            }
        }

        if (info.arr_career) {
            for (var icnt = 0; icnt < info.arr_career.split("&&&").length; icnt++) {
                var temp = info.arr_career.split("&&&")[icnt].split("|||");
                if (temp.length > 2) {
                    str_ru_career += "<li><span>" + temp[0] + "</span><span>" + temp[1] + "</span><em>" + temp[2] + "(1년 2개월)</em></li>";
                }
            }
        }

        if (info.arr_certi) {
            for (var icnt = 0; icnt < info.arr_certi.split("&&&").length; icnt++) {
                var temp = info.arr_certi.split("&&&")[icnt].split("|||");
                if (temp.length > 1) {
                    str_ru_certi += "<li><span>자격증</span><span>" + temp[0] + "</span><em>" + temp[1] + "</em></li>";
                }
            }
        }

        if (info.ru_my_type) {
            var ru_my_type_array = typeof info.ru_my_type === 'string' ? info.ru_my_type.split(",") : info.ru_my_type;
            if (ru_my_type_array && ru_my_type_array.length > 0) {
                for (var icnt = 0; icnt < ru_my_type_array.length; icnt++) {
                    var typeValue = ru_my_type_array[icnt].trim().replace(/,/g, '');
                    if (typeValue) {
                        str_ru_type += "<p class='tag'>" + typeValue + "</p>";
                    }
                }
            }
        }

        if (info.ru_price_type.indexOf("월급") > -1 && info.ru_month_price != "0") {
            str_month_price = '<tr><td class="label">희망급여</td><td class="value">' + info.ru_month_price.format() + '원</td></tr>';
        }

        var displayAddr = '';

        if (Array.isArray(info.ru_addr)) {
            displayAddr = info.ru_addr.filter(function(addr) {
                return addr && addr.trim() !== '';
            }).join(',');
        } else {
            displayAddr = info.ru_addr || '';
        }

        if (displayAddr && displayAddr !== '' && displayAddr !== '무관' && typeof fRemoveBigAdrJS === 'function') {
            if (displayAddr.indexOf(',') > -1) {
                var addrArray = displayAddr.split(',').map(function(addr) {
                    return addr.trim();
                }).filter(function(addr) {
                    return addr !== '';
                });
                var formattedAddrs = [];
                addrArray.forEach(function(addr) {
                    var formatted = fRemoveBigAdrJS(addr);
                    if (formatted && formattedAddrs.indexOf(formatted) === -1) {
                        formattedAddrs.push(formatted);
                    }
                });
                displayAddr = formattedAddrs.length > 0 ? formattedAddrs.join(' | ') : '';
            } else {
                displayAddr = fRemoveBigAdrJS(displayAddr);
            }
        }

        if (!displayAddr || displayAddr === '') {
            displayAddr = '무관';
        }

        openPopup('modal', {
            removeClose: false,
            content: '<div class="regist-info">' +
                '<h5 class="name">' + info.mm_name + '</h5>' +
                '<table>' +
                '<caption><span class="sr-text">회원가입정보</span></caption>' +
                '<colgroup><col><col></colgroup>' +
                '<tbody>' +
                '<tr>' +
                '<td class="label">업무</td>' +
                '<td class="value">' +
                str_ru_work_img +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">지역</td>' +
                '<td class="value">' + displayAddr + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">급여형태</td>' +
                '<td class="value">' + info.ru_price_type + '</td>' +
                '</tr>' + str_month_price +
                '<tr>' +
                '<td class="label">근무형태</td>' +
                '<td class="value">' + info.ru_work_type + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">희망요일</td>' +
                '<td class="value">' +
                '<ul class="working-day">' +
                str_ru_week +
                '</ul>' +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">경력사항</td>' +
                '<td class="value">' +
                '<ul class="period-list">' +
                str_ru_career +
                '</ul>' +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="label">자격증</td>' +
                '<td class="value">' +
                '<ul class="period-list">' +
                str_ru_certi +
                '</ul>' +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td colspan="2" class="label">자기소개</td>' +
                '</tr>' +
                '<tr>' +
                '<td colspan="2" class="value introduce">' + info.ru_desc + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td colspan="2" class="label">나의강점</td>' +
                '</tr>' +
                '<tr>' +
                '<td colspan="2" class="value">' +
                '<div class="hashtag">' +
                str_ru_type +
                '</div>' +
                '</td>' +
                '</tr>' +
                '</tbody>' +
                '</table>' +
                '</div>',
            firstButton: '정보수정',
            secondButton: '등록',
            firstCallback: function() {
                var $popup = document.querySelector('.layer-popup.in');
                if ($popup && typeof closePopup === 'function') {
                    closePopup($popup);
                }
            },
            secondCallback: function() {
                var ru_week = [];
                $("[name='ru_week[]']:checked").each(function() {
                    ru_week.push($(this).val());
                });
                var ru_week_str = ru_week.join(",");
                var ru_week_yn_value = $("[name='ru_week_yn']").is(":checked") ? "Y" : "N";

                var arr_career = "";
                var $careerList = $(".form-row[data-section='career'] .added-list");
                $careerList.children("li").each(function() {
                    if (!$(this).hasClass('is-deleted')) {
                        var category = $(this).find("[name='career_category[]']").val();
                        var company = $(this).find("[name='career_company[]']").val();
                        var period = $(this).find("[name='career_period[]']").val();

                        if (category != "" && company != "" && period != "") {
                            if (arr_career == "") {
                                arr_career = category + "|||" + company + "|||" + period;
                            } else {
                                arr_career = arr_career + "&&&" + category + "|||" + company + "|||" + period;
                            }
                        }
                    }
                });
                $("[name=arr_career]").val(arr_career);

                var arr_certi = "";
                var $licenseList = $(".form-row[data-section='license'] .added-list");
                $licenseList.children("li").each(function() {
                    if (!$(this).hasClass('is-deleted')) {
                        var license = $(this).find("[name='license[]']").val();
                        var regdate = $(this).find("[name='regdate[]']").val();

                        if (license != "" && regdate != "") {
                            if (arr_certi == "") {
                                arr_certi = license + "|||" + regdate;
                            } else {
                                arr_certi = arr_certi + "&&&" + license + "|||" + regdate;
                            }
                        }
                    }
                });
                $("[name=arr_certi]").val(arr_certi);

                var form = $('#frm');
                var params = new FormData(form[0]);
                params.append("ru_idx", $("[name=ru_idx]").val() || "0");
                params.append("ru_week_yn", ru_week_yn_value);
                if (ru_week_str) {
                    params.append("ru_week", ru_week_str);
                }

                fileAjaxLoading("/mypage_proc/resume_write4", params, function(ret) {
                    if (ret.code == "00") {
                        var completeParams = {};
                        completeParams["ru_idx"] = $("[name=ru_idx]").val();
                        comAjax("/mypage_proc/resume_complete", completeParams, function(completeRet) {
                            if (completeRet.code == "00") {
                                commonAlert("저장이 완료되었습니다.", function() {
                                    clearResumeDraft($("[name=ru_idx]").val());
                                    location.href = "/mypage/resume_list";
                                });
                            } else {
                                var errorMsg = completeRet.msg || "저장 중 오류가 발생했습니다. 다시 시도해주세요.";
                                commonAlert(errorMsg, function() {});
                                return;
                            }
                        });
                    } else {
                        var errorMsg = ret.msg || "데이터 저장 중 오류가 발생했습니다. 다시 시도해주세요.";
                        commonAlert(errorMsg, function() {});
                        return;
                    }
                });
            }
        });

        setTimeout(function() {
            var $closeBtn = $('.layer-popup .js-close-popup');
            if ($closeBtn.length > 0) {
                $closeBtn.off('click.preview').on('click.preview', function(e) {
                });
            }
        }, 100);
    }

    window.addEventListener('pageshow', function(event) {
        if (event.persisted || window.performance && window.performance.navigation.type == 2) {
            clearResumeDraft($("[name='ru_idx']").val() || "0");
            _replace("/mypage/index");
        }
    }, false);

</script>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
