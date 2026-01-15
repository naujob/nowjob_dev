<style>
    /* 이력서 작성 페이지 스타일 참고 */
    .required-asterisk {
        color: #ff3333;
        margin-right: 2px;
    }

    .required-label {
        font-size: 0.9375rem;
        color: rgb(0, 0, 0);
        font-weight: 400;
    }

    .resume-title-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.875rem;
    }

    .resume-title-header label {
        margin: 0;
    }

    .resume-title-header .form-label {
        margin: 0;
    }

    .form-label-with-checkbox {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-label-with-checkbox .form-checkbox {
        display: inline-block;
        vertical-align: middle;
        margin-left: auto;
    }

    .form-label-with-checkbox .form-checkbox .label {
        font-weight: normal;
    }

    .form-label-with-checkbox em {
        display: inline-block;
        margin: 1px 0 0 6px;
        font-size: 0.8125rem;
        color: #1B9D7D;
        font-style: normal;
        font-weight: 400;
        line-height: 1.1538461538;
        vertical-align: top;
    }

    /* 주소등록 + 버튼 스타일 */
    .address-add-btn {
        display: block;
        width: 100%;
        height: 3.5rem;
        padding: 0 1.25rem;
        font-size: 1rem;
        font-weight: 400;
        color: #222;
        line-height: 3.5rem;
        text-align: center;
        text-decoration: none;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
        position: relative;
    }

    .address-add-btn .add-icon {
        font-size: 2rem;
        font-weight: 300;
        vertical-align: middle;
        display: inline-block;
    }

    .address-add-btn.has-address .add-icon {
        display: none;
    }

    .address-add-btn.has-address {
        text-align: left;
    }

    .address-add-btn .address-text {
        display: none;
    }

    .address-add-btn.has-address .address-text {
        display: inline;
    }

    /* 급여형태 레이블 스타일 - salary-label 클래스가 있는 경우에만 적용 */
    .form-row>.form-label.salary-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* 최저임금 안내 스타일 */
    .min-wage-info {
        display: inline-flex;
        align-items: center;
        font-size: 0.8125rem;
        color: #222;
        margin-left: auto;
        white-space: nowrap;
    }

    .min-wage-info::before {
        content: "!";
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        margin-right: 4px;
        font-size: 0.75rem;
        font-weight: bold;
        color: #fff;
        background-color: #ff3333;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .min-wage-info .highlight {
        color: #ff3333;
        font-weight: bold;
    }

    /* 공고제목 입력 필드 스타일 */
    .form-row>label {
        display: block;
        width: 100%;
        margin-top: 0.875rem;
    }

    .form-row>label>.form-text {
        width: 100%;
        height: 3.5rem;
        padding: 0 1.25rem;
        font-size: 1rem;
        font-weight: 400;
        color: #222;
        line-height: 3.5rem;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background: #fff;
        box-sizing: border-box;
    }

    .form-row>label>.form-text::placeholder {
        color: #666;
        /* placeholder 색상을 더 진하게 조정 */
        text-align: center;
        font-weight: 400;
        font-size: 1.2rem;
    }

    .form-row>label>.form-text:focus {
        outline: none;
        border-color: #1B9D7D;
    }

    /* 급여형태 섹션 스타일 */
    .salary-type-row {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.875rem;
        margin-bottom: 0.875rem;
    }

    .salary-type-row .form-selectbox {
        flex: 1;
        min-height: 3.5rem;
    }

    .salary-type-row .form-selectbox .liner {
        min-height: 3.5rem;
        height: 3.5rem;
        padding: 0 1.25rem;
        line-height: 3.5rem;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background-color: #fff;
        background-position: calc(100% - 1.25rem) 50%;
        font-size: 1rem;
        font-weight: 400;
        color: #222;
        text-align: left;
    }

    .salary-type-row .form-selectbox select:focus~.liner {
        border-color: #1B9D7D;
        box-shadow: none;
    }

    .salary-type-row .form-text {
        flex: 1;
        height: 3.5rem;
        padding: 0 1.25rem;
        font-size: 1rem;
        font-weight: 400;
        color: #222;
        line-height: 3.5rem;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background: #fff;
        box-sizing: border-box;
    }

    .salary-type-row .form-text:focus {
        outline: none;
        border-color: #1B9D7D;
    }

    .salary-amount-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
        min-width: 0;
    }

    .salary-amount-wrapper .form-text {
        flex: 1;
    }

    .salary-amount-wrapper .unit {
        font-size: 1rem;
        color: #222;
        white-space: nowrap;
    }

    /* 급여 가능 옵션 체크박스 그룹 */
    .salary-options-group {
        display: flex;
        gap: 1rem;
        margin-top: 0.875rem;
    }

    /* 추가정보 폼 섹션 위치 조정 */
    .section.addition-info-form {
        margin-top: -10px;
        /* 원하는 만큼 조정 (음수 값으로 위로 이동) */
    }

    /* 토글 버튼 height 통일 */
    .form-toggle-group .form-toggle-btn2 .btn {
        height: 3.5rem;
        line-height: 3.5rem;
    }
</style>

<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form id="frm" onsubmit="return false" autocomplete="false">
            <input type="hidden" id="rc_idx" name="rc_idx" value="<?= isset($info["rc_idx"]) ? $info["rc_idx"] : "0" ?>" />
            <input type="hidden" id="ad_zipcode" name="ad_zipcode" value="<?= isset($info["addr"]["ad_zipcode"]) ? $info["addr"]["ad_zipcode"] : "" ?>" />
            <input type="hidden" id="ad_jibun" name="ad_jibun" value="<?= isset($info["addr"]["ad_jibun"]) ? $info["addr"]["ad_jibun"] : "" ?>" />
            <input type="hidden" id="ad_doro" name="ad_doro" value="<?= isset($info["addr"]["ad_doro"]) ? $info["addr"]["ad_doro"] : "" ?>" />
            <input type="hidden" id="ad_detail" name="ad_detail" value="<?= isset($info["addr"]["ad_detail"]) ? $info["addr"]["ad_detail"] : "" ?>" />
            <input type="hidden" id="ad_addr" name="ad_addr" value="<?= isset($info["addr"]["ad_addr"]) ? $info["addr"]["ad_addr"] : "" ?>" />
            <input type="hidden" id="ad_gps_x" name="ad_gps_x" value="<?= isset($info["addr"]["ad_gps_x"]) ? $info["addr"]["ad_gps_x"] : "" ?>" />
            <input type="hidden" id="ad_gps_y" name="ad_gps_y" value="<?= isset($info["addr"]["ad_gps_y"]) ? $info["addr"]["ad_gps_y"] : "" ?>" />
            <input type="hidden" name="rc_work_img" value="" />

            <div class="section addition-info-form">
                <div class="container">
                    <!-- 공고제목 섹션 -->
                    <div class="form-row">
                        <div class="resume-title-header">
                            <label>
                                <span class="form-label"><span class="required-asterisk">*</span>공고제목<em>최대 30자</em></span>
                            </label>
                            <span class="required-label"><span class="required-asterisk">*</span>필수입력</span>
                        </div>
                        <label>
                            <input type="text" name="rc_name" placeholder="공고제목을 입력해주세요" value="<?= isset($info["rc_name"]) ? $info["rc_name"] : "" ?>" maxlength="30" autocomplete="off" aria-required="true" required class="form-text">
                        </label>
                    </div>

                    <!-- 주소등록 섹션 -->
                    <div class="form-row">
                        <p class="form-label"><span class="required-asterisk">*</span>주소등록</p>
                        <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="address-add-btn js-toggle-panel" id="btnAddrAdd">
                            <span class="add-icon">+</span>
                            <!-- 주소는 JavaScript에서만 렌더링 -->
                            <span class="address-text"></span>
                        </a>
                    </div>

                    <!-- 업무구분 섹션 -->
                    <div class="form-row">
                        <p class="form-label"><span class="required-asterisk">*</span>업무구분 <em>업무구분은 2개까지 선택 가능</em></p>
                        <div class="form-toggle-group col-3">
                            <?php
                            // 표시 허용 업무구분 화이트리스트 (스크린샷 기준)
                            $allowed_work_list = ["생산", "물류", "건설", "운전배달", "식당", "서비스", "사무직", "상담", "기타"];
                            if (isset($rc_wrok_list)) {
                                foreach ($rc_wrok_list as $row) :
                                    // 허용 목록 이외 항목은 출력 제외
                                    if (!in_array($row["cd_val"], $allowed_work_list, true)) {
                                        continue;
                                    }
                            ?>
                                    <label class="form-toggle-btn2 sm">
                                        <input type="checkbox" name="rc_work[]" data-select-limit="2" value="<?= $row["cd_val"] ?>" data-img="<?= isset($row["cd_val2"]) ? $row["cd_val2"] : "" ?>" <?= isset($info["rc_work"]) && in_array_return($row["cd_val"], $info["rc_work"], "check") ? "checked" : "" ?>>
                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                    </label>
                                <?php
                                endforeach;
                            } else {
                                // rc_wrok_list가 없을 경우 직접 출력
                                foreach ($allowed_work_list as $work) :
                                ?>
                                    <label class="form-toggle-btn2 sm">
                                        <input type="checkbox" name="rc_work[]" data-select-limit="2" value="<?= $work ?>" <?= isset($info["rc_work"]) && in_array_return($work, $info["rc_work"], "check") ? "checked" : "" ?>>
                                        <span class="btn"><?= $work ?></span>
                                    </label>
                            <?php
                                endforeach;
                            }
                            ?>
                        </div>
                    </div>

                    <!-- 근무형태 섹션 -->
                    <div class="form-row">
                        <p class="form-label form-label-with-checkbox">
                            <span><span class="required-asterisk">*</span>근무형태 <em>근무형태는 2개까지 선택 가능</em></span>
                            <span class="form-checkbox">
                                <input type="checkbox" name="rc_work_negotiation" value="Y" <?= isset($info["rc_work_negotiation"]) && $info["rc_work_negotiation"] == "Y" ? "checked" : "" ?>>
                                <span class="label">근무협의</span>
                            </span>
                        </p>
                        <div class="form-toggle-group col-3">
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_work_type[]" data-select-limit="2" value="주간" <?= isset($info["rc_work_type"]) && in_array_return("주간", $info["rc_work_type"], "check") ? "checked" : "" ?>>
                                <span class="btn">주간</span>
                            </label>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_work_type[]" data-select-limit="2" value="야간" <?= isset($info["rc_work_type"]) && in_array_return("야간", $info["rc_work_type"], "check") ? "checked" : "" ?>>
                                <span class="btn">야간</span>
                            </label>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_work_type[]" data-select-limit="2" value="2교대" <?= isset($info["rc_work_type"]) && in_array_return("2교대", $info["rc_work_type"], "check") ? "checked" : "" ?>>
                                <span class="btn">2교대</span>
                            </label>
                        </div>
                    </div>

                    <!-- 급여형태 섹션 -->
                    <div class="form-row">
                        <p class="form-label salary-label">
                            <span class="required-asterisk">*</span>급여형태
                            <span class="min-wage-info">최저임금제도 : 시급 <span class="highlight">10,030</span> 원</span>
                        </p>
                        <div class="salary-type-row">
                            <label class="form-selectbox">
                                <span class="sr-text">급여형태 선택</span>
                                <select name="rc_price_type_main" id="rc_price_type_main">
                                    <option value="시급" <?= (isset($info["rc_price_type_main"]) && $info["rc_price_type_main"] == "시급") ? "selected" : "" ?>>시급</option>
                                    <option value="일급" <?= (isset($info["rc_price_type_main"]) && $info["rc_price_type_main"] == "일급") ? "selected" : "" ?>>일급</option>
                                    <option value="주급" <?= (isset($info["rc_price_type_main"]) && $info["rc_price_type_main"] == "주급") ? "selected" : "" ?>>주급</option>
                                    <option value="월급" <?= (isset($info["rc_price_type_main"]) && $info["rc_price_type_main"] == "월급") ? "selected" : "" ?>>월급</option>
                                    <!-- 기본 선택값을 "급여협의"로 설정 -->
                                    <option value="급여협의" <?= (!isset($info["rc_price_type_main"]) || $info["rc_price_type_main"] == "" || $info["rc_price_type_main"] == "급여협의") ? "selected" : "" ?>>급여협의</option>
                                </select>
                                <span class="liner"></span>
                            </label>
                            <div class="salary-amount-wrapper">
                                <input type="text" name="rc_price_amount" id="rc_price_amount" placeholder="0" value="<?= isset($info["rc_price_amount"]) ? $info["rc_price_amount"] : "" ?>" autocomplete="off" class="form-text" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" <?= (!isset($info["rc_price_type_main"]) || $info["rc_price_type_main"] == "급여협의") ? "disabled" : "" ?>>
                                <span class="unit">원</span>
                            </div>
                        </div>
                        <div class="salary-options-group" id="salary-options-group" <?= (!isset($info["rc_price_type_main"]) || $info["rc_price_type_main"] == "급여협의") ? 'style="display:none;"' : '' ?>>
                            <label class="form-checkbox">
                                <input type="radio" name="rc_price_possible" value="시급가능" <?= isset($info["rc_price_possible"]) && $info["rc_price_possible"] == "시급가능" ? "checked" : "" ?>>
                                <span class="label">시급가능</span>
                            </label>
                            <label class="form-checkbox">
                                <input type="radio" name="rc_price_possible" value="일급가능" <?= isset($info["rc_price_possible"]) && $info["rc_price_possible"] == "일급가능" ? "checked" : "" ?>>
                                <span class="label">일급가능</span>
                            </label>
                            <label class="form-checkbox">
                                <input type="radio" name="rc_price_possible" value="주급가능" <?= isset($info["rc_price_possible"]) && $info["rc_price_possible"] == "주급가능" ? "checked" : "" ?>>
                                <span class="label">주급가능</span>
                            </label>
                            <label class="form-checkbox">
                                <input type="radio" name="rc_price_possible" value="월급가능" <?= isset($info["rc_price_possible"]) && $info["rc_price_possible"] == "월급가능" ? "checked" : "" ?>>
                                <span class="label">월급가능</span>
                            </label>
                        </div>
                    </div>

                    <div class="sticky-bottom">
                        <button id="btnStep" type="button" class="btn btn-primary">다음</button>
                    </div>
                </div>
            </div>
        </form>
    </div><!-- //page-content -->
</div>

<script>
    // 서버에서 받은 주소 데이터를 JavaScript 변수로 전달
    // 수정 모드(rc_idx가 0이 아니고 공고명이 있을 때)일 때만 주소 데이터 전달
    var serverAddrData = <?= (isset($info["rc_idx"]) && $info["rc_idx"] != 0 && !empty($info["rc_name"]) && isset($info["addr"]) && is_array($info["addr"]) && !empty($info["addr"]["ad_addr"])) ? json_encode($info["addr"], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) : 'null' ?>;
    
    // rc_idx 변경 시 localStorage 키 마이그레이션 함수
    function migrateRecruitDraftKeys(oldIdx, newIdx, pages) {
        if (!oldIdx || !newIdx || oldIdx === newIdx) return;
        (pages || []).forEach(function(page) {
            var oldKey = "recruit_draft_" + oldIdx + "_page" + page;
            var newKey = "recruit_draft_" + newIdx + "_page" + page;
            var saved = localStorage.getItem(oldKey);
            if (saved && !localStorage.getItem(newKey)) {
                localStorage.setItem(newKey, saved);
            }
            localStorage.removeItem(oldKey);
        });
    }

    // localStorage 저장/복원 함수
    function savePage1Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var storageKey = "recruit_draft_" + rcIdx + "_page1";
        var formData = {
            rc_name: $("[name='rc_name']").val(),
            ad_zipcode: $("#ad_zipcode").val(),
            ad_jibun: $("#ad_jibun").val(),
            ad_doro: $("#ad_doro").val(),
            ad_detail: $("#ad_detail").val(),
            ad_addr: $("#ad_addr").val(),
            ad_gps_x: $("#ad_gps_x").val(),
            ad_gps_y: $("#ad_gps_y").val(),
            rc_work: [],
            rc_work_negotiation: $("[name='rc_work_negotiation']").is(":checked") ? "Y" : "",
            rc_work_type: [],
            rc_price_type_main: $("#rc_price_type_main").val(),
            rc_price_amount: $("#rc_price_amount").val(),
            rc_price_possible: $("[name='rc_price_possible']:checked").val() || ""
        };

        // 체크박스 배열 저장
        $("[name='rc_work[]']:checked").each(function() {
            formData.rc_work.push($(this).val());
        });
        $("[name='rc_work_type[]']:checked").each(function() {
            formData.rc_work_type.push($(this).val());
        });

        localStorage.setItem(storageKey, JSON.stringify(formData));
    }

    // 서버에서 받은 주소 데이터를 로드하는 함수
    // 수정 모드(rc_idx가 0이 아니고 공고명이 있을 때)일 때만 로드
    function loadServerAddressData() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var rcName = $("[name='rc_name']").val() || "";
        
        // 수정 모드가 아니면 주소 데이터를 로드하지 않음
        if (rcIdx == "0" || rcName == "") {
            return false;
        }
        
        if (typeof serverAddrData !== 'undefined' && serverAddrData !== null && serverAddrData.ad_addr) {
            // 주소 필드에 값 설정
            $("#ad_zipcode").val(serverAddrData.ad_zipcode || "");
            $("#ad_jibun").val(serverAddrData.ad_jibun || "");
            $("#ad_doro").val(serverAddrData.ad_doro || "");
            $("#ad_addr").val(serverAddrData.ad_addr || "");
            $("#ad_detail").val(serverAddrData.ad_detail || "");
            $("#ad_gps_x").val(serverAddrData.ad_gps_x || "");
            $("#ad_gps_y").val(serverAddrData.ad_gps_y || "");
            
            // 주소 버튼 업데이트
            var $btnAddr = $("#btnAddrAdd");
            var displayAddr = serverAddrData.ad_addr || "";
            if (serverAddrData.ad_detail && serverAddrData.ad_detail.trim() !== "") {
                displayAddr = displayAddr + " " + serverAddrData.ad_detail.trim();
            }
            $btnAddr.find(".address-text").text(displayAddr);
            $btnAddr.addClass("has-address");
            
            return true; // 서버 데이터가 있음을 반환
        }
        return false; // 서버 데이터가 없음을 반환
    }

    function loadPage1Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var storageKey = "recruit_draft_" + rcIdx + "_page1";
        var saved = localStorage.getItem(storageKey);

        if (!saved) return;

        try {
            var formData = JSON.parse(saved);
            if (!formData) return;

            // 입력 필드 복원
            if (typeof formData.rc_name !== "undefined") {
                $("[name='rc_name']").val(formData.rc_name);
            }
            if (formData.ad_zipcode) {
                $("#ad_zipcode").val(formData.ad_zipcode);
            }
            if (formData.ad_jibun) {
                $("#ad_jibun").val(formData.ad_jibun);
            }
            if (formData.ad_doro) {
                $("#ad_doro").val(formData.ad_doro);
            }
            if (formData.ad_detail) {
                $("#ad_detail").val(formData.ad_detail);
            }
            if (formData.ad_addr) {
                $("#ad_addr").val(formData.ad_addr);
                // 주소 버튼 업데이트
                var displayAddr = formData.ad_addr;
                if (formData.ad_detail) {
                    displayAddr += " " + formData.ad_detail;
                }
                $("#btnAddrAdd").find(".address-text").text(displayAddr);
                $("#btnAddrAdd").addClass("has-address");
            }
            if (formData.ad_gps_x) {
                $("#ad_gps_x").val(formData.ad_gps_x);
            }
            if (formData.ad_gps_y) {
                $("#ad_gps_y").val(formData.ad_gps_y);
            }

            // 급여형태 복원
            if (formData.rc_price_type_main) {
                $("#rc_price_type_main").val(formData.rc_price_type_main);
                // 급여형태 변경 후 옵션 업데이트
                updateSalaryOptions();
                // 옵션 업데이트 후 라디오 버튼 복원
                setTimeout(function() {
                    if (formData.rc_price_possible) {
                        $("[name='rc_price_possible'][value='" + formData.rc_price_possible + "']").prop("checked", true);
                    }
                }, 200);
            } else if (formData.rc_price_possible && !$("[name='rc_price_possible']:checked").length) {
                // 급여형태는 서버 데이터가 있지만 가능 옵션만 복원 (체크된 것이 없을 때만)
                $("[name='rc_price_possible'][value='" + formData.rc_price_possible + "']").prop("checked", true);
            }

            if (formData.rc_price_amount) {
                $("#rc_price_amount").val(formData.rc_price_amount);
            }

            // 체크박스 복원
            $("[name='rc_work[]']").prop("checked", false);
            if (Array.isArray(formData.rc_work) && formData.rc_work.length > 0) {
                formData.rc_work.forEach(function(val) {
                    $("[name='rc_work[]'][value='" + val + "']").prop("checked", true);
                });
            }

            if (formData.rc_work_negotiation === "Y") {
                $("[name='rc_work_negotiation']").prop("checked", true).trigger("change");
            }

            $("[name='rc_work_type[]']").prop("checked", false);
            if (Array.isArray(formData.rc_work_type) && formData.rc_work_type.length > 0) {
                formData.rc_work_type.forEach(function(val) {
                    $("[name='rc_work_type[]'][value='" + val + "']").prop("checked", true);
                });
            }
            
        } catch (e) {
            console.error("페이지1 데이터 복원 오류:", e);
        }
    }

    // 검색 완료 선택 처리
    function selectAddrInfo(zipcode, doro, jibun, addr, x, y, detail) {
        // detail 파라미터가 없으면 빈 문자열로 처리
        detail = detail || "";

        $("#ad_zipcode").val(zipcode);
        $("#ad_jibun").val(jibun);
        $("#ad_doro").val(doro);
        // ad_addr에는 지번을 클릭하든 도로명을 클릭하든 항상 지번을 저장
        $("#ad_addr").val(jibun);
        $("#ad_detail").val(detail); // 상세주소 저장
        $("#ad_gps_x").val(x);
        $("#ad_gps_y").val(y);

        // 주소 텍스트 업데이트 및 스타일 변경 (상세주소 포함)
        var $btnAddr = $("#btnAddrAdd");
        var displayAddr = addr;
        if (detail && detail.trim() !== "") {
            displayAddr = addr + " " + detail.trim(); // 주소 + 상세주소
        }
        $btnAddr.find(".address-text").text(displayAddr);
        $btnAddr.addClass("has-address");

        $("#changeArea").removeClass("on");

        // 주소 저장 후 localStorage에 저장
        savePage1Data();
    }

    // 급여형태에 따른 옵션 그룹 업데이트 함수
    function updateSalaryOptions() {
        var selectedType = $("#rc_price_type_main").val();
        var $optionsGroup = $("#salary-options-group");
        var $options = $optionsGroup.find("label");
        var $salaryAmountInput = $("#rc_price_amount");

        // 급여협의 선택 시 옵션 그룹 숨김 및 입력 필드 비활성화
        if (selectedType === "급여협의") {
            $optionsGroup.hide();
            // 모든 라디오 버튼 체크 해제
            $optionsGroup.find("input[type='radio']").prop("checked", false);
            // 급여 금액 입력 필드 비활성화
            $salaryAmountInput.prop("disabled", true);
            return;
        }

        // 급여협의가 아닌 경우 입력 필드 활성화
        $salaryAmountInput.prop("disabled", false);

        // 옵션 그룹 표시
        $optionsGroup.show();

        // 모든 옵션 숨김
        $options.hide();

        // 선택된 급여형태에 따라 해당하는 옵션만 표시
        if (selectedType === "시급") {
            // 시급 선택 시: 일급가능, 주급가능, 월급가능 표시
            $optionsGroup.find("input[value='일급가능']").closest("label").show();
            $optionsGroup.find("input[value='주급가능']").closest("label").show();
            $optionsGroup.find("input[value='월급가능']").closest("label").show();
        } else if (selectedType === "일급") {
            // 일급 선택 시: 시급가능, 주급가능, 월급가능 표시
            $optionsGroup.find("input[value='시급가능']").closest("label").show();
            $optionsGroup.find("input[value='주급가능']").closest("label").show();
            $optionsGroup.find("input[value='월급가능']").closest("label").show();
        } else if (selectedType === "주급") {
            // 주급 선택 시: 시급가능, 일급가능, 월급가능 표시
            $optionsGroup.find("input[value='시급가능']").closest("label").show();
            $optionsGroup.find("input[value='일급가능']").closest("label").show();
            $optionsGroup.find("input[value='월급가능']").closest("label").show();
        } else if (selectedType === "월급") {
            // 월급 선택 시: 시급가능, 일급가능, 주급가능 표시
            $optionsGroup.find("input[value='시급가능']").closest("label").show();
            $optionsGroup.find("input[value='일급가능']").closest("label").show();
            $optionsGroup.find("input[value='주급가능']").closest("label").show();
        }
    }

    // localStorage 초기화 함수 (등록 완료 후 또는 새 등록 시작 시)
    function clearRecruitDraftData(rcIdx) {
        if (!rcIdx) rcIdx = "0";
        // 해당 rc_idx의 모든 페이지 데이터 삭제
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page1");
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page2");
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page3");
    }

    window.addEventListener('DOMContentLoaded', function() {
        // 먼저 서버에서 받은 주소 데이터를 로드
        var hasServerData = loadServerAddressData();
        
        // 페이지 로드 시 데이터 복원
        // 서버 데이터가 없을 때만 로컬스토리지에서 복원
        if (!hasServerData) {
            loadPage1Data();
        }

        // 페이지 로드 시 초기 옵션 설정
        updateSalaryOptions();

        // 실시간 저장: 입력 필드 변경 시
        $("[name='rc_name']").on("input", savePage1Data);
        $("#rc_price_type_main").on("change", function() {
            updateSalaryOptions();
            savePage1Data();
        });
        $("#rc_price_amount").on("input", savePage1Data);
        $("[name='rc_price_possible']").on("change", savePage1Data);

        // 체크박스 변경 시 저장
        $("[name='rc_work[]']").on("change", savePage1Data);
        $("[name='rc_work_type[]']").on("change", savePage1Data);
        $("[name='rc_work_negotiation']").on("change", function() {
            savePage1Data();
        });
        // 선택 제한 로직
        (function() {
            document.querySelectorAll('input[data-select-limit]').forEach(function(item) {
                item.addEventListener('change', function() {
                    var name = this.getAttribute('name');
                    // 협의가 체크된 경우 선택 제한 무시
                    if (name === 'rc_work_type[]' && document.querySelector('[name="rc_work_negotiation"]:checked')) {
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
        }());

        // 근무협의 체크박스 클릭 이벤트
        $("[name='rc_work_negotiation']").on("change", function() {
            var isChecked = $(this).is(":checked");
            if (isChecked) {
                // 근무협의 체크 시 모든 근무형태 항목 체크
                $("[name='rc_work_type[]']").prop("checked", true);
            } else {
                // 근무협의 해제 시 모든 근무형태 항목 체크 해제
                $("[name='rc_work_type[]']").prop("checked", false);
            }
        });

        // 협의가 체크된 경우 개별 항목 체크 해제 방지
        $("[name='rc_work_type[]']").on("change", function() {
            if ($("[name='rc_work_negotiation']").is(":checked")) {
                // 근무협의가 체크된 상태에서는 개별 항목 체크 해제 불가
                if (!$(this).is(":checked")) {
                    $(this).prop("checked", true);
                }
            }
        });

        // salary-options-group 내 라디오 버튼 클릭 시 토글 기능
        $(document).on("mousedown", "#salary-options-group input[type='radio']", function(e) {
            var $radio = $(this);
            // 클릭 전 체크 상태 저장
            $radio.data("wasChecked", $radio.is(":checked"));
        });

        $(document).on("click", "#salary-options-group input[type='radio']", function(e) {
            var $radio = $(this);
            var wasChecked = $radio.data("wasChecked");

            // 클릭 전에 이미 체크되어 있었으면 해제
            if (wasChecked) {
                e.preventDefault();
                e.stopPropagation();
                // 같은 name을 가진 모든 라디오 버튼 해제
                $("input[name='" + $radio.attr("name") + "']").prop("checked", false);
                $radio.trigger("change");
            }
        });

        // form-checkbox의 label 클릭 시 체크박스/라디오 버튼 토글
        $(document).on("click", ".form-checkbox .label", function(e) {
            e.preventDefault();
            var $input = $(this).siblings("input[type='checkbox'], input[type='radio']");
            if ($input.length > 0) {
                if ($input.attr("type") === "radio") {
                    // 라디오 버튼: 이미 체크되어 있으면 해제, 아니면 체크
                    if ($input.is(":checked")) {
                        // 같은 name을 가진 모든 라디오 버튼 해제
                        $("input[name='" + $input.attr("name") + "']").prop("checked", false);
                    } else {
                        $input.prop("checked", true);
                    }
                } else {
                    $input.prop("checked", !$input.prop("checked"));
                }
                $input.trigger("change");
            }
        });

        // 다음 버튼 클릭 처리
        $("#btnStep").click(function() {
            // 공고제목 검증
            if ($("[name='rc_name']").val() == "") {
                commonAlert("공고제목을 입력해주세요.", function() {
                    $("[name='rc_name']").focus();
                });
                return;
            }

            // 주소 검증 - 주소가 입력되지 않았으면 다음 단계로 이동 불가
            var adAddr = $("#ad_addr").val();
            var adZipcode = $("#ad_zipcode").val();
            var hasAddressClass = $("#btnAddrAdd").hasClass("has-address");
            
            // 주소가 입력되지 않은 경우 (주소 값이 없거나, 주소 버튼에 has-address 클래스가 없는 경우)
            if (!adAddr || adAddr.trim() === "" || !adZipcode || adZipcode.trim() === "" || !hasAddressClass) {
                commonAlert("주소를 등록해주세요.", function() {
                    // 주소 등록 버튼으로 포커스 이동
                    $("#btnAddrAdd").focus();
                });
                return;
            }

            // 업무구분 검증
            if ($("[name='rc_work[]']").is(":checked") == false) {
                commonAlert("업무구분을 하나 이상 선택해주세요.", function() {});
                return;
            }

            // 근무형태 검증
            if ($("[name='rc_work_negotiation']").is(":checked") == false && $("[name='rc_work_type[]']").is(":checked") == false) {
                commonAlert("근무형태를 하나 이상 선택해주세요.", function() {});
                return;
            }

            // 급여형태 검증 (급여협의가 아닌 경우에만 금액 검증)
            var priceType = $("#rc_price_type_main").val();
            if (priceType !== "급여협의") {
                if ($("#rc_price_amount").val() == "" || parseInt($("#rc_price_amount").val()) <= 0) {
                    commonAlert("급여 금액을 입력해주세요.", function() {
                        $("#rc_price_amount").focus();
                    });
                    return;
                }
            }

            // 업무구분 이미지 처리
            var rc_work_img = "";
            for (var icnt = 0; icnt < $("[name='rc_work[]']:checked").length; icnt++) {
                if (rc_work_img == "") {
                    rc_work_img = $("[name='rc_work[]']:checked").eq(icnt).data("img");
                } else {
                    rc_work_img += "," + $("[name='rc_work[]']:checked").eq(icnt).data("img");
                }
            }
            $("[name=rc_work_img]").val(rc_work_img);

            savePage1Data();

            var params = $("#frm").serialize();
            var prevIdx = $("[name='rc_idx']").val() || "0";
            comAjax("/compage_proc/recruit_write1_1", params, function(ret) {
                if (ret.code == "00") {
                    var newIdx = prevIdx;
                    if (ret.data && ret.data.rc_idx) {
                        newIdx = ret.data.rc_idx;
                        if (newIdx && newIdx !== prevIdx) {
                            // rc_idx가 변경되면 localStorage 키 마이그레이션
                            migrateRecruitDraftKeys(prevIdx, newIdx, [1, 2, 3]);
                            $("[name='rc_idx']").val(newIdx);
                        }
                    }

                    if (newIdx != "0") {
                        location.href = "/compage/recruit_write2_1/" + newIdx;
                    } else {
                        location.href = "/compage/recruit_write2_1";
                    }
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });

        // 뒤로가기 - compage/recruit_list로 이동
        $(".btn-back").click(function(e) {
            e.preventDefault();
            savePage1Data();
            clearRecruitDraftData($("[name='rc_idx']").val() || "0");
            location.href = "/compage/recruit_list";
        });

        // Android 물리적 뒤로가기 버튼 및 iOS 스와이프 뒤로가기 처리
        // 히스토리 스택에 항목 추가하여 popstate 이벤트 감지 가능하게 함
        history.pushState(null, null, location.href);
        
        // popstate 이벤트로 뒤로가기 버튼 및 iOS 스와이프 제스처 감지
        window.addEventListener('popstate', function(e) {
            // 뒤로가기를 막기 위해 다시 히스토리 스택에 추가
            history.pushState(null, null, location.href);
            // 데이터 저장 후 원하는 페이지로 이동
            savePage1Data();
            // 페이지를 벗어나므로 데이터 삭제
            clearRecruitDraftData($("[name='rc_idx']").val() || "0");
            location.href = "/compage/recruit_list";
        });

    });
</script>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>