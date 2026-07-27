<div class="page page-write page-recruit-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form id="frm" onsubmit="return false" autocomplete="false" enctype="multipart/form-data">
            <input type="hidden" id="rc_idx" name="rc_idx" value="<?= isset($info["rc_idx"]) ? $info["rc_idx"] : "0" ?>" />
            <input type="hidden" id="ad_zipcode" name="ad_zipcode" value="<?= isset($info["addr"]["ad_zipcode"]) ? $info["addr"]["ad_zipcode"] : "" ?>" />
            <input type="hidden" id="ad_jibun" name="ad_jibun" value="<?= isset($info["addr"]["ad_jibun"]) ? $info["addr"]["ad_jibun"] : "" ?>" />
            <input type="hidden" id="ad_doro" name="ad_doro" value="<?= isset($info["addr"]["ad_doro"]) ? $info["addr"]["ad_doro"] : "" ?>" />
            <input type="hidden" id="ad_detail" name="ad_detail" value="<?= isset($info["addr"]["ad_detail"]) ? $info["addr"]["ad_detail"] : "" ?>" />
            <input type="hidden" id="ad_addr" name="ad_addr" value="<?= isset($info["addr"]["ad_addr"]) ? $info["addr"]["ad_addr"] : "" ?>" />
            <input type="hidden" id="ad_gps_x" name="ad_gps_x" value="<?= isset($info["addr"]["ad_gps_x"]) ? $info["addr"]["ad_gps_x"] : "" ?>" />
            <input type="hidden" id="ad_gps_y" name="ad_gps_y" value="<?= isset($info["addr"]["ad_gps_y"]) ? $info["addr"]["ad_gps_y"] : "" ?>" />
            <!-- 공고노출지역 1 hidden 필드 -->
            <input type="hidden" id="expose_addr1_zipcode" name="expose_addr1_zipcode" value="<?= isset($info['expose_addr1']['ad_zipcode']) ? $info['expose_addr1']['ad_zipcode'] : '' ?>" />
            <input type="hidden" id="expose_addr1_jibun" name="expose_addr1_jibun" value="<?= isset($info['expose_addr1']['ad_jibun']) ? $info['expose_addr1']['ad_jibun'] : '' ?>" />
            <input type="hidden" id="expose_addr1_doro_val" name="expose_addr1_doro_val" value="<?= isset($info['expose_addr1']['ad_doro']) ? $info['expose_addr1']['ad_doro'] : '' ?>" />
            <input type="hidden" id="expose_addr1_addr" name="expose_addr1_addr" value="<?= isset($info['expose_addr1']['ad_addr']) ? $info['expose_addr1']['ad_addr'] : '' ?>" />
            <input type="hidden" id="expose_addr1_gps_x" name="expose_addr1_gps_x" value="<?= isset($info['expose_addr1']['ad_gps_x']) ? $info['expose_addr1']['ad_gps_x'] : '' ?>" />
            <input type="hidden" id="expose_addr1_gps_y" name="expose_addr1_gps_y" value="<?= isset($info['expose_addr1']['ad_gps_y']) ? $info['expose_addr1']['ad_gps_y'] : '' ?>" />
            <!-- 공고노출지역 2 hidden 필드 -->
            <input type="hidden" id="expose_addr2_zipcode" name="expose_addr2_zipcode" value="<?= isset($info['expose_addr2']['ad_zipcode']) ? $info['expose_addr2']['ad_zipcode'] : '' ?>" />
            <input type="hidden" id="expose_addr2_jibun" name="expose_addr2_jibun" value="<?= isset($info['expose_addr2']['ad_jibun']) ? $info['expose_addr2']['ad_jibun'] : '' ?>" />
            <input type="hidden" id="expose_addr2_doro_val" name="expose_addr2_doro_val" value="<?= isset($info['expose_addr2']['ad_doro']) ? $info['expose_addr2']['ad_doro'] : '' ?>" />
            <input type="hidden" id="expose_addr2_addr" name="expose_addr2_addr" value="<?= isset($info['expose_addr2']['ad_addr']) ? $info['expose_addr2']['ad_addr'] : '' ?>" />
            <input type="hidden" id="expose_addr2_gps_x" name="expose_addr2_gps_x" value="<?= isset($info['expose_addr2']['ad_gps_x']) ? $info['expose_addr2']['ad_gps_x'] : '' ?>" />
            <input type="hidden" id="expose_addr2_gps_y" name="expose_addr2_gps_y" value="<?= isset($info['expose_addr2']['ad_gps_y']) ? $info['expose_addr2']['ad_gps_y'] : '' ?>" />
            <!-- 회사정보 (preview에서 표시용) -->
            <input type="hidden" id="mm_comp_name" name="mm_comp_name" value="<?= isset($mem_info["mm_comp_name"]) ? $mem_info["mm_comp_name"] : "" ?>" />
            <input type="hidden" id="mm_phone" name="mm_phone" value="<?= isset($mem_info["mm_phone"]) ? $mem_info["mm_phone"] : "" ?>" />
            <input type="hidden" name="rc_work_img" value="" />

            <div class="section addition-info-form">
                <div class="container">
                    <!-- 공고제목 섹션 -->
                    <div class="form-row">
                        <div class="resume-title-header">
                            <label>
                                <span class="form-label"><span class="required-asterisk">*</span>공고제목 <em>최대 30자</em></span>
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
                            <span class="address-text"></span>
                        </a>
                    </div>

                    <!-- 공고노출지역 섹션 -->
                    <div class="form-row">
                        <p class="form-label">공고노출지역<em>추가 노출지역을 선택해주세요</em></p>
                        <div class="expose-addr-group">
                            <!-- 노출지역 1 -->
                            <div class="address-btn-wrapper">
                                <a href="#changeAreaExpose" role="button" aria-haspopup="dialog" aria-controls="changeAreaExpose" class="address-add-btn js-toggle-panel" id="btnExposeAddr1" data-index="1">
                                    <span class="add-icon">+</span>
                                    <span class="address-text"></span>
                                </a>
                                <button type="button" class="address-clear-btn js-clear-expose-addr" data-index="1" aria-label="노출지역 1 삭제">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <!-- 노출지역 2 -->
                            <div class="address-btn-wrapper">
                                <a href="#changeAreaExpose" role="button" aria-haspopup="dialog" aria-controls="changeAreaExpose" class="address-add-btn js-toggle-panel" id="btnExposeAddr2" data-index="2">
                                    <span class="add-icon">+</span>
                                    <span class="address-text"></span>
                                </a>
                                <button type="button" class="address-clear-btn js-clear-expose-addr" data-index="2" aria-label="노출지역 2 삭제">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 업무구분 섹션 -->
                    <div class="form-row">
                        <p class="form-label"><span class="required-asterisk">*</span>업무구분 <em>업무구분은 2개까지 선택 가능</em></p>
                        <div class="form-toggle-group col-3">
                            <?php
                            $allowed_work_list = ["생산", "물류", "건설", "운전배달", "식당", "서비스", "사무직", "상담", "기타"];
                            $rendered_works = []; // DB 중복 제거용
                            if (isset($rc_wrok_list)) {
                                foreach ($rc_wrok_list as $row) :
                                    if (!in_array($row["cd_val"], $allowed_work_list, true)) {
                                        continue;
                                    }
                                    // 이미 렌더링된 항목은 스킵 (중복 제거)
                                    if (in_array($row["cd_val"], $rendered_works, true)) {
                                        continue;
                                    }
                                    $rendered_works[] = $row["cd_val"];
                            ?>
                                    <label class="form-toggle-btn2 sm">
                                        <input type="checkbox" name="rc_work[]" data-select-limit="2" value="<?= $row["cd_val"] ?>" data-img="<?= isset($row["cd_val2"]) ? $row["cd_val2"] : "" ?>" <?= isset($info["rc_work"]) && in_array_return($row["cd_val"], $info["rc_work"], "check") ? "checked" : "" ?>>
                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                    </label>
                                <?php
                                endforeach;
                            } else {
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

                        <!-- 근무시간 섹션 (주간) -->
                        <div id="time_cont1" style="display:none;" class="work-time-section">
                            <p class="work-time-section-label">근무시간 <span class="work-time-tag day">주간</span></p>
                            <div class="work-time-group">
                                <!-- 출근 -->
                                <div class="work-time-row">
                                    <span class="work-time-label">출근</span>
                                    <div class="work-time-input-wrap">
                                        <?php
                                        $amStartH = (isset($info["rc_am_stime"]) && !empty($info["rc_am_stime"])) ? substr($info["rc_am_stime"], 0, 2) : "09";
                                        $amStartM = (isset($info["rc_am_stime"]) && !empty($info["rc_am_stime"])) ? substr($info["rc_am_stime"], 3, 2) : "00";
                                        ?>
                                        <button type="button" class="js-time-picker-trigger js-toggle-panel" aria-controls="workTimePickerSheet" data-time-prefix="rc_am_stime">
                                            <span class="time-display"><?= str_pad($amStartH, 2, "0", STR_PAD_LEFT) ?> : <?= str_pad($amStartM, 2, "0", STR_PAD_LEFT) ?></span>
                                        </button>
                                        <input type="hidden" name="rc_am_stime_h" value="<?= str_pad($amStartH, 2, "0", STR_PAD_LEFT) ?>">
                                        <input type="hidden" name="rc_am_stime_m" value="<?= str_pad($amStartM, 2, "0", STR_PAD_LEFT) ?>">
                                    </div>
                                </div>
                                <!-- 퇴근 -->
                                <div class="work-time-row">
                                    <span class="work-time-label">퇴근</span>
                                    <div class="work-time-input-wrap">
                                        <?php
                                        $amEndH = (isset($info["rc_am_etime"]) && !empty($info["rc_am_etime"])) ? substr($info["rc_am_etime"], 0, 2) : "18";
                                        $amEndM = (isset($info["rc_am_etime"]) && !empty($info["rc_am_etime"])) ? substr($info["rc_am_etime"], 3, 2) : "00";
                                        ?>
                                        <button type="button" class="js-time-picker-trigger js-toggle-panel" aria-controls="workTimePickerSheet" data-time-prefix="rc_am_etime">
                                            <span class="time-display"><?= str_pad($amEndH, 2, "0", STR_PAD_LEFT) ?> : <?= str_pad($amEndM, 2, "0", STR_PAD_LEFT) ?></span>
                                        </button>
                                        <input type="hidden" name="rc_am_etime_h" value="<?= str_pad($amEndH, 2, "0", STR_PAD_LEFT) ?>">
                                        <input type="hidden" name="rc_am_etime_m" value="<?= str_pad($amEndM, 2, "0", STR_PAD_LEFT) ?>">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="rc_am_stime" value="<?= isset($info["rc_am_stime"]) ? $info["rc_am_stime"] : "" ?>">
                            <input type="hidden" name="rc_am_etime" value="<?= isset($info["rc_am_etime"]) ? $info["rc_am_etime"] : "" ?>">
                        </div>

                        <!-- 근무시간 섹션 (야간) -->
                        <div id="time_cont2" style="display:none;" class="work-time-section">
                            <p class="work-time-section-label">근무시간 <span class="work-time-tag night">야간</span></p>
                            <div class="work-time-group">
                                <div class="work-time-row">
                                    <span class="work-time-label">출근</span>
                                    <div class="work-time-input-wrap">
                                        <?php
                                        $pmStartH = (isset($info["rc_pm_stime"]) && !empty($info["rc_pm_stime"])) ? substr($info["rc_pm_stime"], 0, 2) : "21";
                                        $pmStartM = (isset($info["rc_pm_stime"]) && !empty($info["rc_pm_stime"])) ? substr($info["rc_pm_stime"], 3, 2) : "00";
                                        ?>
                                        <button type="button" class="js-time-picker-trigger js-toggle-panel" aria-controls="workTimePickerSheet" data-time-prefix="rc_pm_stime">
                                            <span class="time-display"><?= str_pad($pmStartH, 2, "0", STR_PAD_LEFT) ?> : <?= str_pad($pmStartM, 2, "0", STR_PAD_LEFT) ?></span>
                                        </button>
                                        <input type="hidden" name="rc_pm_stime_h" value="<?= str_pad($pmStartH, 2, "0", STR_PAD_LEFT) ?>">
                                        <input type="hidden" name="rc_pm_stime_m" value="<?= str_pad($pmStartM, 2, "0", STR_PAD_LEFT) ?>">
                                    </div>
                                </div>
                                <div class="work-time-row">
                                    <span class="work-time-label">퇴근</span>
                                    <div class="work-time-input-wrap">
                                        <?php
                                        $pmEndH = (isset($info["rc_pm_etime"]) && !empty($info["rc_pm_etime"])) ? substr($info["rc_pm_etime"], 0, 2) : "06";
                                        $pmEndM = (isset($info["rc_pm_etime"]) && !empty($info["rc_pm_etime"])) ? substr($info["rc_pm_etime"], 3, 2) : "00";
                                        ?>
                                        <button type="button" class="js-time-picker-trigger js-toggle-panel" aria-controls="workTimePickerSheet" data-time-prefix="rc_pm_etime">
                                            <span class="time-display"><?= str_pad($pmEndH, 2, "0", STR_PAD_LEFT) ?> : <?= str_pad($pmEndM, 2, "0", STR_PAD_LEFT) ?></span>
                                        </button>
                                        <input type="hidden" name="rc_pm_etime_h" value="<?= str_pad($pmEndH, 2, "0", STR_PAD_LEFT) ?>">
                                        <input type="hidden" name="rc_pm_etime_m" value="<?= str_pad($pmEndM, 2, "0", STR_PAD_LEFT) ?>">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="rc_pm_stime" value="<?= isset($info["rc_pm_stime"]) ? $info["rc_pm_stime"] : "" ?>">
                            <input type="hidden" name="rc_pm_etime" value="<?= isset($info["rc_pm_etime"]) ? $info["rc_pm_etime"] : "" ?>">
                        </div>
                    </div>

                    <!-- 급여형태 섹션 -->
                    <div class="form-row">
                        <p class="form-label salary-label">
                            <span class="required-asterisk">*</span>급여형태
                            <span class="min-wage-info">최저임금제도 : 시급 <span class="highlight">10,320</span> 원</span>
                        </p>
                        <div class="salary-type-row">
                            <label class="form-selectbox">
                                <span class="sr-text">급여형태 선택</span>
                                <select name="rc_price_type_main" id="rc_price_type_main">
                                    <option value="시급" <?= (isset($info["rc_price_type_main"]) && $info["rc_price_type_main"] == "시급") ? "selected" : "" ?>>시급</option>
                                    <option value="일급" <?= (isset($info["rc_price_type_main"]) && $info["rc_price_type_main"] == "일급") ? "selected" : "" ?>>일급</option>
                                    <option value="주급" <?= (isset($info["rc_price_type_main"]) && $info["rc_price_type_main"] == "주급") ? "selected" : "" ?>>주급</option>
                                    <option value="월급" <?= (isset($info["rc_price_type_main"]) && $info["rc_price_type_main"] == "월급") ? "selected" : "" ?>>월급</option>
                                    <option value="급여협의" <?= (!isset($info["rc_price_type_main"]) || $info["rc_price_type_main"] == "" || $info["rc_price_type_main"] == "급여협의") ? "selected" : "" ?>>급여협의</option>
                                </select>
                                <span class="liner"></span>
                            </label>
                            <div class="salary-amount-wrapper">
                                <input type="text" name="rc_price_amount" id="rc_price_amount" placeholder="0" value="<?= isset($info["rc_price_amount"]) && $info["rc_price_amount"] ? number_format((int)$info["rc_price_amount"]) : "" ?>" autocomplete="off" class="form-text" <?= (!isset($info["rc_price_type_main"]) || $info["rc_price_type_main"] == "급여협의") ? "disabled" : "" ?>>
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

                    <!-- 복리후생 섹션 -->
                    <div class="form-row addition-info-form">
                        <p class="form-label"><span class="required-asterisk">*</span>복리후생</p>

                        <?php
                        // 복리후생 데이터 준비
                        $benefit_sections = [
                            [
                                'title' => '보험',
                                'items' => [
                                    ['value' => '국민연금', 'class' => 'benefit1-1'],
                                    ['value' => '고용보험', 'class' => 'benefit1-2'],
                                    ['value' => '산재보험', 'class' => 'benefit1-3'],
                                    ['value' => '건강보험', 'class' => 'benefit1-4'],
                                ]
                            ],
                            [
                                'title' => '휴가',
                                'items' => [
                                    ['value' => '연차', 'class' => 'benefit2-1'],
                                    ['value' => '월차', 'class' => 'benefit2-2'],
                                    ['value' => '여름휴가', 'class' => 'benefit2-3'],
                                ]
                            ],
                            [
                                'title' => '수당/퇴직금',
                                'items' => [
                                    ['value' => '야근근로수당', 'class' => 'benefit3-1'],
                                    ['value' => '휴일근로수당', 'class' => 'benefit3-2'],
                                    ['value' => '장기근속수당', 'class' => 'benefit3-3'],
                                    ['value' => '연장근로수당', 'class' => 'benefit3-4'],
                                    ['value' => '인센티브제', 'class' => 'benefit3-5'],
                                    ['value' => '퇴직금', 'class' => 'benefit3-6'],
                                ]
                            ],
                            [
                                'title' => '생활편의 지원',
                                'items' => [
                                    ['value' => '조식제공', 'class' => 'benefit4-1'],
                                    ['value' => '중식제공', 'class' => 'benefit4-2'],
                                    ['value' => '석식제공', 'class' => 'benefit4-3'],
                                    ['value' => '근무복지급', 'class' => 'benefit4-4'],
                                    ['value' => '통근버스운행', 'class' => 'benefit4-5', 'label' => '통근버스 운행', 'alt_values' => ['통근버스 운행']],
                                    ['value' => '차량유류비지원', 'class' => 'benefit4-6'],
                                    ['value' => '기숙사운영', 'class' => 'benefit4-7'],
                                ]
                            ],
                        ];

                        foreach ($benefit_sections as $section) { // 준비한 데이터와 db에서 가져온 rc_life_type 데이터를 병합하여 뷰에 전달하여  준비한 데이터에서 데이터를 꺼내어 db에서 가져온 rc_life_type과 비교하여 체크 여부를 결정 ( 화면에는 **"체크 안 된 항목"**도 다 보여줘야하므로, 병합 과정에서 체크 여부를 결정)
                            $this->load->view('mobile/_partial/benefit_section', [
                                'benefit_section' => array_merge($section, ['checked' => isset($info['rc_life_type']) ? $info['rc_life_type'] : []])
                            ]);
                        }
                        ?>
                    </div>

                    <!-- 상세업무정보 섹션 -->
                    <div class="form-row">
                        <div class="resume-title-header">
                            <p class="form-label"><span class="required-asterisk">*</span>상세업무정보</p>
                        </div>
                        <label>
                            <span class="sr-text">상세업무정보 입력</span>
                            <?php
                            // contenteditable div에 표시할 내용 (이미 HTML 형태)
                            $desc = isset($info["rc_desc"]) ? $info["rc_desc"] : "";
                            ?>
                            <div id="rc_desc_editor"
                                 contenteditable="true"
                                 class="form-textarea"
                                 style="min-height: 430px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;"><?= $desc ?></div>
                            <input type="hidden" id="uploadedPhotoUrl_mobile" name="uploadedPhotoUrl_mobile" value="">
                            <input type="hidden" name="rc_desc" id="rc_desc_input" value="">
                        </label>
                    </div>

                    <!-- 첨부사진 섹션 -->
                    <div class="form-row">
                        <p class="form-label">첨부사진</p>
                        <div class="form-pic-group">
                            <!-- 1번: 메인사진 -->
                            <label class="form-pic <?= isset($info["rc_img1"]) && $info["rc_img1"] != "" ? "has-image" : "" ?>">
                                <span class="pic-badge">메인사진</span>
                                <input type="file" name="rc_img1" id="rc_img1_input" accept="image/*" class="js-file-preview">
                                <span class="preview">
                                    <?php if (isset($info["rc_img1"]) && $info["rc_img1"] != "") : ?>
                                        <img src="<?= $info["rc_img1"] ?>" />
                                    <?php endif; ?>
                                </span>
                                <button type="button" class="btn-pic-remove" style="display:none;">×</button>
                                <input type='hidden' name='rc_img1_ori' value='<?= isset($info["rc_img1"]) ? $info["rc_img1"] : "" ?>' />
                            </label>

                            <!-- 2번: 상세페이지1 -->
                            <label class="form-pic">
                                <span class="pic-badge">상세페이지1</span>
                                <input type="file" accept="image/*" class="js-detail-photo-select">
                                <span class="preview"></span>
                                <button type="button" class="btn-pic-remove" style="display:none;">×</button>
                            </label>

                            <!-- 3번: 상세페이지2 -->
                            <label class="form-pic">
                                <span class="pic-badge">상세페이지2</span>
                                <input type="file" accept="image/*" class="js-detail-photo-select">
                                <span class="preview"></span>
                                <button type="button" class="btn-pic-remove" style="display:none;">×</button>
                            </label>

                            <!-- 4번: 상세페이지3 -->
                            <label class="form-pic">
                                <span class="pic-badge">상세페이지3</span>
                                <input type="file" accept="image/*" class="js-detail-photo-select">
                                <span class="preview"></span>
                                <button type="button" class="btn-pic-remove" style="display:none;">×</button>
                            </label>
                        </div>
                    </div>

                    <!-- 해시태그 섹션 -->
                    <div class="form-row">
                        <p class="form-label"><span class="required-asterisk">*</span>해시태그</p>
                        <div class="form-toggle-group col-3">
                            <?php
                            $default_hash_list = ["초보가능", "경력자우대", "당일지급", "동반가능", "서비스"];
                            $rc_hash_array = isset($info["rc_hash"]) && $info["rc_hash"] != "" ? explode(",", $info["rc_hash"]) : [];

                            foreach ($default_hash_list as $hash) :
                                $is_checked = in_array($hash, $rc_hash_array);
                            ?>
                                <label class="form-toggle-btn2 sm tag">
                                    <input type="checkbox" name="rc_hash[]" value="<?= $hash ?>" <?= $is_checked ? "checked" : "" ?>>
                                    <span class="btn"><?= $hash ?></span>
                                </label>
                            <?php endforeach; ?>

                            <?php if (!empty($rc_hash_array)) : ?>
                                <?php foreach ($rc_hash_array as $hash) : ?>
                                    <?php
                                    $hash = trim($hash);
                                    if (!in_array($hash, $default_hash_list) && $hash != "") :
                                    ?>
                                        <label class="form-toggle-btn2 sm tag">
                                            <input type="checkbox" name="rc_hash[]" value="<?= $hash ?>" checked>
                                            <span class="btn"><?= $hash ?></span>
                                        </label>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <button type="button" aria-haspopup="dialog" class="btn-add-more js-add-hashtag">+<span class="sr-text">추가작성</span></button>
                        </div>
                        <div class="hashtag-input-area">
                            <div class="input-row">
                                <input type="text" class="form-text js-hashtag-input" placeholder="직접입력" maxlength="20">
                                <button type="button" class="btn-confirm js-confirm-hashtag">확인</button>
                            </div>
                        </div>
                    </div>

                    <!-- 채용공고기간 섹션 -->
                    <div class="form-row">
                        <p class="form-label">
                            <span class="required-asterisk">*</span>채용공고기간
                            <em class="notice-text">2개월까지 공고 가능</em>
                        </p>
                        <div class="datepicker-row">
                            <label class="datepicker-wrapper">
                                <span class="sr-text">시작일 선택</span>
                                <input type="text" name="rc_sdate" id="rc_sdate" aria-required="true" required class="form-text js-datepicker-start" readonly placeholder="2025.01.01">
                            </label>
                            <span class="datepicker-separator">~</span>
                            <label class="datepicker-wrapper">
                                <span class="sr-text">종료일 선택</span>
                                <input type="text" name="rc_edate" id="rc_edate" aria-required="true" required class="form-text js-datepicker-end" readonly placeholder="2025.03.01">
                            </label>
                        </div>
                    </div>

                    <!-- 상세등록 섹션들 -->
                    <div class="form-row js-detail-section">
                        <p class="form-label form-label-with-checkbox">
                            <span>희망요일</span>
                            <span class="form-checkbox">
                                <input type="checkbox" name="rc_day_all" value="Y" <?= isset($info["rc_day_all"]) && $info["rc_day_all"] == "Y" ? "checked" : "" ?>>
                                <span class="label">요일무관</span>
                            </span>
                        </p>
                        <div class="day-of-week-group">
                            <label class="day-btn">
                                <input type="checkbox" name="rc_day[]" value="월" <?= isset($info["rc_day"]) && in_array_return("월", $info["rc_day"], "check") ? "checked" : "" ?>>
                                <span class="btn">월</span>
                            </label>
                            <label class="day-btn">
                                <input type="checkbox" name="rc_day[]" value="화" <?= isset($info["rc_day"]) && in_array_return("화", $info["rc_day"], "check") ? "checked" : "" ?>>
                                <span class="btn">화</span>
                            </label>
                            <label class="day-btn">
                                <input type="checkbox" name="rc_day[]" value="수" <?= isset($info["rc_day"]) && in_array_return("수", $info["rc_day"], "check") ? "checked" : "" ?>>
                                <span class="btn">수</span>
                            </label>
                            <label class="day-btn">
                                <input type="checkbox" name="rc_day[]" value="목" <?= isset($info["rc_day"]) && in_array_return("목", $info["rc_day"], "check") ? "checked" : "" ?>>
                                <span class="btn">목</span>
                            </label>
                            <label class="day-btn">
                                <input type="checkbox" name="rc_day[]" value="금" <?= isset($info["rc_day"]) && in_array_return("금", $info["rc_day"], "check") ? "checked" : "" ?>>
                                <span class="btn">금</span>
                            </label>
                            <label class="day-btn">
                                <input type="checkbox" name="rc_day[]" value="토" <?= isset($info["rc_day"]) && in_array_return("토", $info["rc_day"], "check") ? "checked" : "" ?>>
                                <span class="btn">토</span>
                            </label>
                            <label class="day-btn">
                                <input type="checkbox" name="rc_day[]" value="일" <?= isset($info["rc_day"]) && in_array_return("일", $info["rc_day"], "check") ? "checked" : "" ?>>
                                <span class="btn">일</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row js-detail-section">
                        <p class="form-label form-label-with-checkbox">
                            <span>희망성별</span>
                            <span class="form-checkbox">
                                <input type="checkbox" name="rc_sex_all" value="Y" <?= isset($info["rc_sex_all"]) && $info["rc_sex_all"] == "Y" ? "checked" : "" ?>>
                                <span class="label">성별무관</span>
                            </span>
                        </p>
                        <div class="form-toggle-group col-2">
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_sex[]" value="남자" <?= isset($info["rc_sex"]) && in_array_return("남자", $info["rc_sex"], "check") ? "checked" : "" ?>>
                                <span class="btn">남자</span>
                            </label>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_sex[]" value="여자" <?= isset($info["rc_sex"]) && in_array_return("여자", $info["rc_sex"], "check") ? "checked" : "" ?>>
                                <span class="btn">여자</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row js-detail-section">
                        <p class="form-label form-label-with-checkbox">
                            <span>희망국적 <em>다중선택가능</em></span>
                            <span class="form-checkbox">
                                <input type="checkbox" name="rc_nation_all" value="Y" <?= isset($info["rc_nation_all"]) && $info["rc_nation_all"] == "Y" ? "checked" : "" ?>>
                                <span class="label">국적무관</span>
                            </span>
                        </p>
                        <div class="form-toggle-group col-2">
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_nation[]" value="내국인" <?= isset($info["rc_nation"]) && in_array_return("내국인", $info["rc_nation"], "check") ? "checked" : "" ?>>
                                <span class="btn">내국인</span>
                            </label>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="rc_nation[]" value="외국인" <?= isset($info["rc_nation"]) && in_array_return("외국인", $info["rc_nation"], "check") ? "checked" : "" ?>>
                                <span class="btn">외국인</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-row js-detail-section">
                        <p class="form-label form-label-with-checkbox">
                            <span>희망나이</span>
                            <span class="form-checkbox">
                                <input type="checkbox" name="rc_age_all" value="Y" <?= isset($info["rc_age_all"]) && $info["rc_age_all"] == "Y" ? "checked" : "" ?>>
                                <span class="label">나이무관</span>
                            </span>
                        </p>
                        <div class="age-range-group">
                            <select name="rc_sage">
                                <option value="">선택</option>
                                <?php $ages = [20, 30, 40, 50, 60,70];
                                foreach ($ages as $age) : ?>
                                    <option value="<?= $age ?>" <?= isset($info["rc_sage"]) && $info["rc_sage"] == $age ? "selected" : "" ?>><?= $age ?>대</option>
                                <?php endforeach; ?>
                            </select>
                            <span class="age-separator">~</span>
                            <select name="rc_eage">
                                <option value="">선택</option>
                                <?php foreach ($ages as $age) : ?>
                                    <option value="<?= $age ?>" <?= isset($info["rc_eage"]) && $info["rc_eage"] == $age ? "selected" : "" ?>><?= $age ?>대</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <a href="javascript:void(0);" class="detail-register-btn js-detail-register">
                            상세등록
                        </a>
                    </div>

                    <div class="sticky-bottom view-footer">
                        <button id="btnPreview" type="button" class="btn btn-primary">미리보기</button>
                        <button id="btnSubmit" type="button" class="btn btn-primary">최종저장</button>
                    </div>
                </div>
            </div>
        </form>
    </div><!-- //page-content -->
</div>

<style>
    /* 주소 버튼 래퍼 */
    .address-btn-wrapper {
        position: relative;
        display: flex;
        align-items: stretch;
        width: 100%;
    }

    .address-btn-wrapper .address-add-btn {
        flex: 1;
    }

    /* 주소 삭제 버튼 */
    .address-clear-btn {
        display: none;
        width: 3rem;
        min-width: 3rem;
        padding: 0;
        background: #f5f5f5;
        border: 1px solid #BEBFC9;
        border-left: none;
        border-radius: 0 8px 8px 0;
        cursor: pointer;
        color: #999;
        font-size: 1.2rem;
        transition: all 0.2s ease;
        margin-left: -1px;
    }

    .address-clear-btn:hover {
        background: #ffe6e6;
        color: #d32f2f;
    }

    /* 주소가 있을 때만 삭제 버튼 표시 */
    .address-btn-wrapper .address-add-btn.has-address ~ .address-clear-btn {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .address-btn-wrapper .address-add-btn.has-address {
        border-radius: 8px 0 0 8px;
    }

    /* 근무시간 섹션 스타일 */
    .work-time-section {
        margin-top: 12px;
        padding: 8px 0;
    }

    .work-time-section-label {
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .work-time-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
        width: 100%;
    }

    .work-time-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .work-time-label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        height: 3rem;
        background: #57CCAF;
        color: #fff;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 500;
        white-space: nowrap;
        flex-shrink: 0;
        flex: 0.4;
    }

    .work-time-input-wrap {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        height: 3rem;
        border: 1px solid #ccc;
        border-radius: 8px;

        background: #fff;
        flex: 1;
    }

    .work-time-input-wrap span {
        font-weight: 500;
        color: #333;
    }

    .work-time-input-wrap .time-select,
    .work-time-input-wrap .time-input-m {
        height: 32px;
        border: none;
        background: none;
        font-size: 18px;
        padding: 0 4px;
    }

    .work-time-input-wrap .time-select {
        width: 50px;
        text-align: center;
    }

    .work-time-input-wrap .time-input-m {
        width: 40px;
        text-align: center;
    }

    .work-time-input-wrap .time-select:focus,
    .work-time-input-wrap .time-input-m:focus {
        outline: none;
    }

    .work-time-tag {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .work-time-tag.day {
    color: white;
    background: green;
    }

    .work-time-tag.night {
        color: white;
        background: red;
    }

    /* 시간 선택기 (wheel picker) 스타일 */
    .time-wheel-picker {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        height: 200px;
        position: relative;
    }

    .wheel-col {
        flex: 1;
        height: 100%;
        overflow-y: scroll;
        overflow-x: hidden;
        position: relative;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
    }

    .wheel-col ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 88px 0;
    }

    .wheel-col li {
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #999;
        transition: color 0.2s ease;
    }

    .wheel-col li.active {
        color: #000;
        font-weight: 600;
    }

    .wheel-col-ampm {
        flex: 1;
        min-width: 80px;
    }

    .wheel-col-hour {
        flex: 1;
        min-width: 60px;
    }

    .wheel-col-minute {
        flex: 1;
        min-width: 60px;
    }

    .wheel-col-separator {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        padding: 0 4px;
        height: 100%;
        display: flex;
        align-items: center;
    }

    .wheel-highlight-bar {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 44px;
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
        background: rgba(57, 204, 159, 0.05);
        transform: translateY(-50%);
        pointer-events: none;
        z-index: 1;
    }

    /* iframe 미리보기 스타일 */
    #recruitPreviewOverlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .preview-iframe-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px;
        height: 48px;
        background: #fff;
        border-bottom: 1px solid #eee;
        flex-shrink: 0;
    }

    .preview-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
    }

    .preview-close-btn {
        background: none;
        border: none;
        font-size: 18px;
        color: #666;
        cursor: pointer;
        padding: 0;
    }

    .preview-iframe {
        flex: 1;
        width: 100%;
        border: none;
    }

    .preview-iframe-footer {
        display: flex;
        gap: 8px;
        padding: 12px 16px;
        border-top: 1px solid #eee;
        background: #fff;
        flex-shrink: 0;
    }

    .preview-iframe-footer .btn {
        flex: 1;
    }
</style>

<script>
    <?php
    $info_for_js = $info;
    // rc_desc는 HTML 형태로 보존 (이미지 태그 포함)
    // rc_hash를 배열로 변환 (JavaScript에서 Array.isArray() 판단용)
    if (isset($info_for_js["rc_hash"]) && $info_for_js["rc_hash"] != "") {
        $info_for_js["rc_hash"] = explode(",", $info_for_js["rc_hash"]);
    } else {
        $info_for_js["rc_hash"] = [];
    }
    // rc_day를 배열로 변환 (JavaScript에서 Array.isArray() 판단용)
    if (isset($info_for_js["rc_day"]) && $info_for_js["rc_day"] != "") {
        $info_for_js["rc_day"] = is_array($info_for_js["rc_day"]) ? $info_for_js["rc_day"] : array_map('trim', explode(",", $info_for_js["rc_day"]));
    } else {
        $info_for_js["rc_day"] = [];
    }
    // rc_sex를 배열로 변환 (JavaScript에서 Array.isArray() 판단용)
    if (isset($info_for_js["rc_sex"]) && $info_for_js["rc_sex"] != "") {
        $info_for_js["rc_sex"] = is_array($info_for_js["rc_sex"]) ? $info_for_js["rc_sex"] : array_map('trim', explode(",", $info_for_js["rc_sex"]));
    } else {
        $info_for_js["rc_sex"] = [];
    }
    // rc_nation을 배열로 변환 (JavaScript에서 Array.isArray() 판단용)
    if (isset($info_for_js["rc_nation"]) && $info_for_js["rc_nation"] != "") {
        $info_for_js["rc_nation"] = is_array($info_for_js["rc_nation"]) ? $info_for_js["rc_nation"] : array_map('trim', explode(",", $info_for_js["rc_nation"]));
    } else {
        $info_for_js["rc_nation"] = [];
    }
    // 회사정보 추가 (preview에서 표시용)
    $info_for_js["mm_comp_name"] = isset($mem_info["mm_comp_name"]) ? $mem_info["mm_comp_name"] : "";
    $info_for_js["mm_phone"] = isset($mem_info["mm_phone"]) ? $mem_info["mm_phone"] : "";
    ?>
    var serverData = <?= (isset($info["rc_idx"]) && $info["rc_idx"] != 0) ? json_encode($info_for_js, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) : 'null' ?>;
    var serverAddrData = serverData && serverData.addr ? serverData.addr : null;
    var serverAddrData1 = serverData && serverData.expose_addr1 ? serverData.expose_addr1 : null;
    var serverAddrData2 = serverData && serverData.expose_addr2 ? serverData.expose_addr2 : null;
    var detailSectionScrollPosition = 0;

    // PHP에서 rc_idx를 JavaScript 변수로 전달
    var rcIdx = '<?= isset($info["rc_idx"]) ? $info["rc_idx"] : "0" ?>';

    // ============================================================================
    // 1. 유틸리티 함수 (숫자 포맷팅, 문자열 처리)
    // ============================================================================

    // 천 단위 쉼표를 추가하는 포맷팅 함수
    function formatNumberWithComma(num) {
        if (!num) return '';
        var numStr = String(num).replace(/[^0-9]/g, '');
        return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // 쉼표를 제거하는 함수
    function removeComma(str) {
        return String(str).replace(/,/g, '');
    }

    // hidden 필드 동기화 함수 (시간 필드를 HH:MM 포맷으로 합성)
    function syncTimeHiddenFields() {
        var am_h = $("[name='rc_am_stime_h']").val() || "09";
        var am_m = String($("[name='rc_am_stime_m']").val() || "00").padStart(2, "0");
        $("[name='rc_am_stime']").val(am_h + ":" + am_m);

        var am_e_h = $("[name='rc_am_etime_h']").val() || "18";
        var am_e_m = String($("[name='rc_am_etime_m']").val() || "00").padStart(2, "0");
        $("[name='rc_am_etime']").val(am_e_h + ":" + am_e_m);

        var pm_s_h = $("[name='rc_pm_stime_h']").val() || "21";
        var pm_s_m = String($("[name='rc_pm_stime_m']").val() || "00").padStart(2, "0");
        $("[name='rc_pm_stime']").val(pm_s_h + ":" + pm_s_m);

        var pm_e_h = $("[name='rc_pm_etime_h']").val() || "06";
        var pm_e_m = String($("[name='rc_pm_etime_m']").val() || "00").padStart(2, "0");
        $("[name='rc_pm_etime']").val(pm_e_h + ":" + pm_e_m);
    }

    // ============================================================================
    // 2. 데이터 마이그레이션 & 정리
    // ============================================================================

    // localStorage의 key 이름을 rc_idx 기준으로 변경하는 함수 (페이지 구분을 위해 page1, page2, page3 suffix 추가)
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

    // localStorage에 저장된 임시데이터를 삭제하는 함수
    function clearRecruitDraftData(rcIdx) {
        if (!rcIdx) rcIdx = "0";
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page1");
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page2");
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page3");
    }

    // ============================================================================
    // 3. 페이지별 데이터 저장 (localStorage)
    // ============================================================================

    // 채용공고 작성 1페이지 내용을 localStorage에 임시저장하기 위해 데이터 묶음을 만드는 함수
    function savePage1Data() {
        syncTimeHiddenFields();
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
            // 공고노출지역 1
            expose_addr1_zipcode: $("#expose_addr1_zipcode").val(),
            expose_addr1_jibun: $("#expose_addr1_jibun").val(),
            expose_addr1_doro_val: $("#expose_addr1_doro_val").val(),
            expose_addr1_addr: $("#expose_addr1_addr").val(),
            expose_addr1_gps_x: $("#expose_addr1_gps_x").val(),
            expose_addr1_gps_y: $("#expose_addr1_gps_y").val(),
            // 공고노출지역 2
            expose_addr2_zipcode: $("#expose_addr2_zipcode").val(),
            expose_addr2_jibun: $("#expose_addr2_jibun").val(),
            expose_addr2_doro_val: $("#expose_addr2_doro_val").val(),
            expose_addr2_addr: $("#expose_addr2_addr").val(),
            expose_addr2_gps_x: $("#expose_addr2_gps_x").val(),
            expose_addr2_gps_y: $("#expose_addr2_gps_y").val(),
            rc_work: [],
            rc_work_negotiation: $("[name='rc_work_negotiation']").is(":checked") ? "Y" : "",
            rc_work_type: [],
            rc_price_type_main: $("#rc_price_type_main").val(),
            rc_price_amount: removeComma($("#rc_price_amount").val()),
            rc_price_possible: $("[name='rc_price_possible']:checked").val() || "",
            rc_am_stime_h: $("[name='rc_am_stime_h']").val(),
            rc_am_stime_m: $("[name='rc_am_stime_m']").val(),
            rc_am_etime_h: $("[name='rc_am_etime_h']").val(),
            rc_am_etime_m: $("[name='rc_am_etime_m']").val(),
            rc_pm_stime_h: $("[name='rc_pm_stime_h']").val(),
            rc_pm_stime_m: $("[name='rc_pm_stime_m']").val(),
            rc_pm_etime_h: $("[name='rc_pm_etime_h']").val(),
            rc_pm_etime_m: $("[name='rc_pm_etime_m']").val()
        };

        $("[name='rc_work[]']:checked").each(function() {
            formData.rc_work.push($(this).val());
        });
        $("[name='rc_work_type[]']:checked").each(function() {
            formData.rc_work_type.push($(this).val());
        });

        localStorage.setItem(storageKey, JSON.stringify(formData));
    }

    function savePage2Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var storageKey = "recruit_draft_" + rcIdx + "_page2";
        var rc_life_type = [];

        $("[name='rc_life_type[]']:checked").each(function() {
            rc_life_type.push($(this).val());
        });

        var formData = {
            rc_life_type: rc_life_type
        };

        localStorage.setItem(storageKey, JSON.stringify(formData));
    }

    // 해시태그를 쉼표로 구분된 문자열로 합성하여 localStorage에 저장
    function savePage3Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var storageKey = "recruit_draft_" + rcIdx + "_page3";

        var rc_hash = [];
        $("[name='rc_hash[]']:checked").each(function() {
            rc_hash.push($(this).val());
        });

        var rc_day = [];
        $("[name='rc_day[]']:checked").each(function() {
            rc_day.push($(this).val());
        });

        var rc_sex = [];
        $("[name='rc_sex[]']:checked").each(function() {
            rc_sex.push($(this).val());
        });

        var rc_nation = [];
        $("[name='rc_nation[]']:checked").each(function() {
            rc_nation.push($(this).val());
        });

        // contenteditable div에서 내용 가져오기
        var rcDescContent = document.getElementById('rc_desc_editor') ? document.getElementById('rc_desc_editor').innerHTML : "";

        var formData = {
            rc_desc: rcDescContent,
            rc_hash: rc_hash,
            rc_sdate: $("[name='rc_sdate']").val(),
            rc_edate: $("[name='rc_edate']").val(),
            rc_day_all: $("[name='rc_day_all']").is(":checked") ? "Y" : "",
            rc_day: rc_day,
            rc_sex_all: $("[name='rc_sex_all']").is(":checked") ? "Y" : "",
            rc_sex: rc_sex,
            rc_nation_all: $("[name='rc_nation_all']").is(":checked") ? "Y" : "",
            rc_nation: rc_nation,
            rc_age_all: $("[name='rc_age_all']").is(":checked") ? "Y" : "",
            rc_sage: $("[name='rc_sage']").val(),
            rc_eage: $("[name='rc_eage']").val(),
            detail_section_visible: $(".js-detail-section").hasClass("show")
        };

        localStorage.setItem(storageKey, JSON.stringify(formData));
    }
// 페이지 새로고침 후 localStorage에 저장된 데이터를 다시 폼에 채워 넣는 함수 (페이지 구분을 위해 page1, page2, page3 suffix 추가)
    function loadPage1Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var formData = null;

        // draft 로드: shouldLoadDraft가 true일 때만 draft를 먼저 확인
        if (window.shouldLoadDraft) {
            var storageKey = "recruit_draft_" + rcIdx + "_page1";
            var draftSaved = localStorage.getItem(storageKey);

            if (draftSaved) {
                // draft가 있으면 draft 사용 (preview에서 돌아온 경우의 임시 수정사항)
                try {
                    formData = JSON.parse(draftSaved);
                    if (!formData) {
                        formData = null;
                    }
                } catch (e) {
                    formData = null;
                }
            }
        }

        // draft가 없거나 shouldLoadDraft가 false인 경우: 서버 데이터 사용 (DB 데이터)
        if (!formData && typeof serverData !== 'undefined' && serverData !== null && serverData.rc_idx && serverData.rc_idx != "0") {
            formData = serverData;
        } else if (!formData) {
            // draft도 없고 serverData도 없으면 종료
            return;
        }

        if (typeof formData.rc_name !== "undefined") {
            $("[name='rc_name']").val(formData.rc_name);
        }
        // 주소 데이터 복원 (addr 객체 또는 직접 속성 지원)
        if (formData.ad_zipcode) {
            $("#ad_zipcode").val(formData.ad_zipcode);
        } else if (formData.addr && formData.addr.ad_zipcode) {
            $("#ad_zipcode").val(formData.addr.ad_zipcode);
        }
        if (formData.ad_jibun) {
            $("#ad_jibun").val(formData.ad_jibun);
        } else if (formData.addr && formData.addr.ad_jibun) {
            $("#ad_jibun").val(formData.addr.ad_jibun);
        }
        if (formData.ad_doro) {
            $("#ad_doro").val(formData.ad_doro);
        } else if (formData.addr && formData.addr.ad_doro) {
            $("#ad_doro").val(formData.addr.ad_doro);
        }
        if (formData.ad_detail) {
            $("#ad_detail").val(formData.ad_detail);
        } else if (formData.addr && formData.addr.ad_detail) {
            $("#ad_detail").val(formData.addr.ad_detail);
        }
        if (formData.ad_addr) {
            $("#ad_addr").val(formData.ad_addr);
            var displayAddr = formData.ad_addr;
            var detailAddr = formData.ad_detail || (formData.addr && formData.addr.ad_detail);
            if (detailAddr) {
                displayAddr += " " + detailAddr;
            }
            $("#btnAddrAdd").find(".address-text").text(displayAddr);
            $("#btnAddrAdd").addClass("has-address");
        } else if (formData.addr && formData.addr.ad_addr) {
            $("#ad_addr").val(formData.addr.ad_addr);
            var displayAddr = formData.addr.ad_addr;
            if (formData.addr.ad_detail) {
                displayAddr += " " + formData.addr.ad_detail;
            }
            $("#btnAddrAdd").find(".address-text").text(displayAddr);
            $("#btnAddrAdd").addClass("has-address");
        }
        if (formData.ad_gps_x) {
            $("#ad_gps_x").val(formData.ad_gps_x);
        } else if (formData.addr && formData.addr.ad_gps_x) {
            $("#ad_gps_x").val(formData.addr.ad_gps_x);
        }
        if (formData.ad_gps_y) {
            $("#ad_gps_y").val(formData.ad_gps_y);
        } else if (formData.addr && formData.addr.ad_gps_y) {
            $("#ad_gps_y").val(formData.addr.ad_gps_y);
        }

        // 공고노출지역 1 복원
        if (formData.expose_addr1_zipcode) {
            $("#expose_addr1_zipcode").val(formData.expose_addr1_zipcode);
        }
        if (formData.expose_addr1_jibun) {
            $("#expose_addr1_jibun").val(formData.expose_addr1_jibun);
        }
        if (formData.expose_addr1_doro_val) {
            $("#expose_addr1_doro_val").val(formData.expose_addr1_doro_val);
        }
        if (formData.expose_addr1_addr) {
            $("#expose_addr1_addr").val(formData.expose_addr1_addr);
            $("#btnExposeAddr1").find(".address-text").text(fRemoveBigAdrJS(formData.expose_addr1_addr));
            $("#btnExposeAddr1").addClass("has-address");
        }
        if (formData.expose_addr1_gps_x) {
            $("#expose_addr1_gps_x").val(formData.expose_addr1_gps_x);
        }
        if (formData.expose_addr1_gps_y) {
            $("#expose_addr1_gps_y").val(formData.expose_addr1_gps_y);
        }

        // 공고노출지역 2 복원
        if (formData.expose_addr2_zipcode) {
            $("#expose_addr2_zipcode").val(formData.expose_addr2_zipcode);
        }
        if (formData.expose_addr2_jibun) {
            $("#expose_addr2_jibun").val(formData.expose_addr2_jibun);
        }
        if (formData.expose_addr2_doro_val) {
            $("#expose_addr2_doro_val").val(formData.expose_addr2_doro_val);
        }
        if (formData.expose_addr2_addr) {
            $("#expose_addr2_addr").val(formData.expose_addr2_addr);
            $("#btnExposeAddr2").find(".address-text").text(fRemoveBigAdrJS(formData.expose_addr2_addr));
            $("#btnExposeAddr2").addClass("has-address");
        }
        if (formData.expose_addr2_gps_x) {
            $("#expose_addr2_gps_x").val(formData.expose_addr2_gps_x);
        }
        if (formData.expose_addr2_gps_y) {
            $("#expose_addr2_gps_y").val(formData.expose_addr2_gps_y);
        }

        if (formData.rc_price_type_main) {
            $("#rc_price_type_main").val(formData.rc_price_type_main);
            updateSalaryOptions();
            setTimeout(function() {
                if (formData.rc_price_possible) {
                    $("[name='rc_price_possible'][value='" + formData.rc_price_possible + "']").prop("checked", true);
                }
            }, 200);
        } else if (formData.rc_price_possible && !$("[name='rc_price_possible']:checked").length) {
            $("[name='rc_price_possible'][value='" + formData.rc_price_possible + "']").prop("checked", true);
        }

        if (formData.rc_price_amount) {
            $("#rc_price_amount").val(formData.rc_price_amount);
        }

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

        // 근무시간 필드 복원
        if (formData.rc_am_stime_h) {
            $("[name='rc_am_stime_h']").val(formData.rc_am_stime_h);
        } else if (formData.rc_am_stime && formData.rc_am_stime.indexOf(":") > -1) {
            var amSTime = formData.rc_am_stime.split(":");
            $("[name='rc_am_stime_h']").val(amSTime[0]);
            $("[name='rc_am_stime_m']").val(amSTime[1]);
        }

        if (formData.rc_am_stime_m) {
            $("[name='rc_am_stime_m']").val(formData.rc_am_stime_m);
        }

        if (formData.rc_am_etime_h) {
            $("[name='rc_am_etime_h']").val(formData.rc_am_etime_h);
        } else if (formData.rc_am_etime && formData.rc_am_etime.indexOf(":") > -1) {
            var amETime = formData.rc_am_etime.split(":");
            $("[name='rc_am_etime_h']").val(amETime[0]);
            $("[name='rc_am_etime_m']").val(amETime[1]);
        }

        if (formData.rc_am_etime_m) {
            $("[name='rc_am_etime_m']").val(formData.rc_am_etime_m);
        }

        if (formData.rc_pm_stime_h) {
            $("[name='rc_pm_stime_h']").val(formData.rc_pm_stime_h);
        } else if (formData.rc_pm_stime && formData.rc_pm_stime.indexOf(":") > -1) {
            var pmSTime = formData.rc_pm_stime.split(":");
            $("[name='rc_pm_stime_h']").val(pmSTime[0]);
            $("[name='rc_pm_stime_m']").val(pmSTime[1]);
        }

        if (formData.rc_pm_stime_m) {
            $("[name='rc_pm_stime_m']").val(formData.rc_pm_stime_m);
        }

        if (formData.rc_pm_etime_h) {
            $("[name='rc_pm_etime_h']").val(formData.rc_pm_etime_h);
        } else if (formData.rc_pm_etime && formData.rc_pm_etime.indexOf(":") > -1) {
            var pmETime = formData.rc_pm_etime.split(":");
            $("[name='rc_pm_etime_h']").val(pmETime[0]);
            $("[name='rc_pm_etime_m']").val(pmETime[1]);
        }

        if (formData.rc_pm_etime_m) {
            $("[name='rc_pm_etime_m']").val(formData.rc_pm_etime_m);
        }

        // 서버에서 로드된 공고노출지역 데이터 처리 (draft가 없을 때)
        if (typeof serverAddrData1 !== 'undefined' && serverAddrData1 !== null && serverAddrData1.ad_addr) {
            if (!formData.expose_addr1_addr) {
                $("#expose_addr1_zipcode").val(serverAddrData1.ad_zipcode || "");
                $("#expose_addr1_jibun").val(serverAddrData1.ad_jibun || "");
                $("#expose_addr1_doro_val").val(serverAddrData1.ad_doro || "");
                $("#expose_addr1_addr").val(serverAddrData1.ad_addr || "");
                $("#expose_addr1_gps_x").val(serverAddrData1.ad_gps_x || "");
                $("#expose_addr1_gps_y").val(serverAddrData1.ad_gps_y || "");

                var $btnExposeAddr1 = $("#btnExposeAddr1");
                $btnExposeAddr1.find(".address-text").text(fRemoveBigAdrJS(serverAddrData1.ad_addr || ""));
                $btnExposeAddr1.addClass("has-address");
            }
        }

        if (typeof serverAddrData2 !== 'undefined' && serverAddrData2 !== null && serverAddrData2.ad_addr) {
            if (!formData.expose_addr2_addr) {
                $("#expose_addr2_zipcode").val(serverAddrData2.ad_zipcode || "");
                $("#expose_addr2_jibun").val(serverAddrData2.ad_jibun || "");
                $("#expose_addr2_doro_val").val(serverAddrData2.ad_doro || "");
                $("#expose_addr2_addr").val(serverAddrData2.ad_addr || "");
                $("#expose_addr2_gps_x").val(serverAddrData2.ad_gps_x || "");
                $("#expose_addr2_gps_y").val(serverAddrData2.ad_gps_y || "");

                var $btnExposeAddr2 = $("#btnExposeAddr2");
                $btnExposeAddr2.find(".address-text").text(fRemoveBigAdrJS(serverAddrData2.ad_addr || ""));
                $btnExposeAddr2.addClass("has-address");
            }
        }
    }
   // 페이지2 데이터 복원 함수 (생활편의 항목 체크박스 복원)
    function loadPage2Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var formData = null;

        // draft 로드: shouldLoadDraft가 true일 때만 draft를 먼저 확인
        if (window.shouldLoadDraft) {
            var storageKey = "recruit_draft_" + rcIdx + "_page2";
            var draftSaved = localStorage.getItem(storageKey);

            if (draftSaved) {
                // draft가 있으면 draft 사용 (preview에서 돌아온 경우의 임시 수정사항)
                try {
                    formData = JSON.parse(draftSaved);
                    if (!formData) {
                        formData = null;
                    }
                } catch (e) {
                    formData = null;
                }
            }
        }

        // draft가 없거나 shouldLoadDraft가 false인 경우: 서버 데이터 사용 (DB 데이터)
        if (!formData && typeof serverData !== 'undefined' && serverData !== null && serverData.rc_idx && serverData.rc_idx != "0") {
            formData = serverData;
        } else if (!formData) {
            // draft도 없고 serverData도 없으면 종료
            return;
        }

        if (formData.rc_life_type && formData.rc_life_type.length > 0) {
            $("[name='rc_life_type[]']").prop("checked", false);
            formData.rc_life_type.forEach(function(val) {
                $("[name='rc_life_type[]'][value='" + val + "']").prop("checked", true);
            });
        }
    }

    function loadPage3Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var formData = null;
        var isServerData = false;  // serverData 로드 여부 확인

        // draft 로드: shouldLoadDraft가 true일 때만 draft를 먼저 확인
        if (window.shouldLoadDraft) {
            var storageKey = "recruit_draft_" + rcIdx + "_page3";
            var draftSaved = localStorage.getItem(storageKey);

            if (draftSaved) {
                // draft가 있으면 draft 사용 (preview에서 돌아온 경우의 임시 수정사항)
                try {
                    formData = JSON.parse(draftSaved);
                    if (!formData) {
                        formData = null;
                    }
                } catch (e) {
                    formData = null;
                }
            }
        }

        // draft가 없거나 shouldLoadDraft가 false인 경우: 서버 데이터 사용 (DB 데이터)
        if (!formData && typeof serverData !== 'undefined' && serverData !== null && serverData.rc_idx && serverData.rc_idx != "0") {
            formData = serverData;
            isServerData = true;  // serverData를 사용했음을 기록
        } else if (!formData) {
            // draft도 없고 serverData도 없으면 종료
            return;
        }

        try {

            if (typeof formData.rc_desc !== "undefined") {
                // contenteditable div에 복구
                var rcDescEditor = document.getElementById('rc_desc_editor');
                if (rcDescEditor) {
                    rcDescEditor.innerHTML = formData.rc_desc;
                }

                // hidden input도 복구 (호환성)
                $("[name='rc_desc']").val(formData.rc_desc);
                if (typeof autoHeight === 'function') {
                    setTimeout(function() {
                        autoHeight($("[name='rc_desc']")[0]);
                    }, 100);
                }
            }

            $("[name='rc_hash[]']").prop("checked", false);
            if (Array.isArray(formData.rc_hash) && formData.rc_hash.length > 0) {
                formData.rc_hash.forEach(function(val) {
                    var $checkbox = $("[name='rc_hash[]'][value='" + val + "']");
                    if ($checkbox.length === 0) {
                        var $newLabel = $('<label class="form-toggle-btn2 sm tag">' +
                            '<input type="checkbox" name="rc_hash[]" value="' + val + '" checked>' +
                            '<span class="btn">' + val + '</span>' +
                            '</label>');
                        $(".js-add-hashtag").before($newLabel);
                    } else {
                        $checkbox.prop("checked", true);
                    }
                });
            }

            setTimeout(function() {
                if (formData.rc_sdate) {
                    var startPicker = flatpickr('.js-datepicker-start');
                    if (startPicker) {
                        startPicker.setDate(formData.rc_sdate, false);
                    }
                }
                if (formData.rc_edate) {
                    var endPicker = flatpickr('.js-datepicker-end');
                    if (endPicker) {
                        endPicker.setDate(formData.rc_edate, false);
                    }
                }
            }, 500);

            if (formData.detail_section_visible && !$(".js-detail-section").hasClass("show")) {
                $(".js-detail-section").addClass("show").show();
            }

           
            if (formData.rc_day_all === "Y") {
            
                $("[name='rc_day_all']").prop("checked", true).trigger("change");
            } else {
              
                $("[name='rc_day_all']").prop("checked", false);
                $("[name='rc_day[]']").prop("checked", false);
                if (Array.isArray(formData.rc_day) && formData.rc_day.length > 0) {
                    formData.rc_day.forEach(function(val) {
                     
                        $("[name='rc_day[]'][value='" + val + "']").prop("checked", true);
                    });
                } else {
               
                }
            }

         

            if (formData.rc_sex_all === "Y") {
           
                $("[name='rc_sex_all']").prop("checked", true).trigger("change");
            } else {
               
                $("[name='rc_sex_all']").prop("checked", false);
                $("[name='rc_sex[]']").prop("checked", false);
                if (Array.isArray(formData.rc_sex) && formData.rc_sex.length > 0) {
                    formData.rc_sex.forEach(function(val) {
                  
                        $("[name='rc_sex[]'][value='" + val + "']").prop("checked", true);
                    });
                }
            }

            if (formData.rc_nation_all === "Y") {
                $("[name='rc_nation_all']").prop("checked", true).trigger("change");
            } else {
                $("[name='rc_nation_all']").prop("checked", false);
                $("[name='rc_nation[]']").prop("checked", false);
                if (Array.isArray(formData.rc_nation) && formData.rc_nation.length > 0) {
                    formData.rc_nation.forEach(function(val) {
                        $("[name='rc_nation[]'][value='" + val + "']").prop("checked", true);
                    });
                }
            }

            if (formData.rc_age_all === "Y") {
                $("[name='rc_age_all']").prop("checked", true);
            } else {
                $("[name='rc_age_all']").prop("checked", false);
                if (formData.rc_sage) $("[name='rc_sage']").val(formData.rc_sage);
                if (formData.rc_eage) $("[name='rc_eage']").val(formData.rc_eage);
            }

            // 데이터 복원 후 각 그룹의 "모두" 상태 동기화 (이벤트 트리거 없이 복원되었으므로 수동 재계산)
            setTimeout(function() {
                // 희망요일 동기화
                var dayCheckedCount = $("[name='rc_day[]']:checked").length;
                var dayTotalCount = $("[name='rc_day[]']").length;
                if (dayCheckedCount === dayTotalCount && dayCheckedCount > 0) {
                    $("[name='rc_day_all']").prop("checked", true);
                }

                // 희망성별 동기화
                var sexCheckedCount = $("[name='rc_sex[]']:checked").length;
                var sexTotalCount = $("[name='rc_sex[]']").length;
                if (sexCheckedCount === sexTotalCount && sexCheckedCount > 0) {
                    $("[name='rc_sex_all']").prop("checked", true);
                }

                // 희망국적 동기화
                var nationCheckedCount = $("[name='rc_nation[]']:checked").length;
                var nationTotalCount = $("[name='rc_nation[]']").length;
                if (nationCheckedCount === nationTotalCount && nationCheckedCount > 0) {
                    $("[name='rc_nation_all']").prop("checked", true);
                }
            }, 100);

            // serverData가 로드된 경우, 자동으로 draft에 저장
            if (isServerData) {
                setTimeout(function() {
                    savePage3Data();
                }, 200);
            }
        } catch (e) {

        }
    }
    // localStorage에 저장된 임시데이터를 삭제하는 함수 (rc_idx 기준으로 page1, page2, page3 모두 삭제)
    function clearRecruitDraftData(rcIdx) {
        if (!rcIdx) rcIdx = "0";
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page1");
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page2");
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page3");
    }
    // 급여 유형에 따라 급여 옵션 표시/숨김 처리하는 함수
    function updateSalaryOptions() {
        var selectedType = $("#rc_price_type_main").val();
        var $optionsGroup = $("#salary-options-group");
        var $options = $optionsGroup.find("label");
        var $salaryAmountInput = $("#rc_price_amount");

        if (selectedType === "급여협의") {
            $optionsGroup.hide();
            $optionsGroup.find("input[type='radio']").prop("checked", false);
            $salaryAmountInput.prop("disabled", true);
            return;
        }

        $salaryAmountInput.prop("disabled", false);
        $optionsGroup.show();
        $options.hide();

        if (selectedType === "시급") {
            $optionsGroup.find("input[value='일급가능']").closest("label").show();
            $optionsGroup.find("input[value='주급가능']").closest("label").show();
            $optionsGroup.find("input[value='월급가능']").closest("label").show();
        } else if (selectedType === "일급") {
            $optionsGroup.find("input[value='시급가능']").closest("label").show();
            $optionsGroup.find("input[value='주급가능']").closest("label").show();
            $optionsGroup.find("input[value='월급가능']").closest("label").show();
        } else if (selectedType === "주급") {
            $optionsGroup.find("input[value='시급가능']").closest("label").show();
            $optionsGroup.find("input[value='일급가능']").closest("label").show();
            $optionsGroup.find("input[value='월급가능']").closest("label").show();
        } else if (selectedType === "월급") {
            $optionsGroup.find("input[value='시급가능']").closest("label").show();
            $optionsGroup.find("input[value='일급가능']").closest("label").show();
            $optionsGroup.find("input[value='주급가능']").closest("label").show();
        }
    }
    // 주소 검색 결과를 폼에 반영하는 함수
    function selectAddrInfo(zipcode, doro, jibun, addr, x, y, detail) {
        detail = detail || "";

        $("#ad_zipcode").val(zipcode);
        $("#ad_jibun").val(jibun);
        $("#ad_doro").val(doro);
        $("#ad_addr").val(addr);
        $("#ad_detail").val(detail);
        $("#ad_gps_x").val(x);
        $("#ad_gps_y").val(y);

        var $btnAddr = $("#btnAddrAdd");
        var displayAddr = addr;
        if (detail && detail.trim() !== "") {
            displayAddr = addr + " " + detail.trim();
        }
        $btnAddr.find(".address-text").text(displayAddr);
        $btnAddr.addClass("has-address");

        $("#changeArea").removeClass("on");
        savePage1Data();
    }

    // 해시태그 체크박스에서 선택된 값들을 쉼표로 구분된 문자열로 만들어 반환하는 함수
    function buildHashString() {
        var hashArray = [];
        $("[name='rc_hash[]']:checked").each(function() {
            hashArray.push($(this).val());
        });
        return hashArray.join(","); // 쉼표로 구분된 문자열 반환
    }
    // 업무구분 체크박스에서 선택된 값들의 이미지를 콤마로 구분된 문자열로 만들어 hidden 필드에 저장하는 함수
    function collectRcWorkImg() {
        var rc_work_img = "";
        $("[name='rc_work[]']:checked").each(function() {
            var img = $(this).data("img");
            if (!img) return;
            if (rc_work_img == "") {
                rc_work_img = img;
            } else {
                rc_work_img += "," + img;
            }
        });
        $("[name=rc_work_img]").val(rc_work_img);
    }
    // 제출 전 필수 입력 항목을 검증하는 함수
    function validateRequiredForSubmit() {
        if ($("[name='rc_name']").val() == "") {
            commonAlert("공고제목을 입력해주세요.", function() {
                $("[name='rc_name']").focus();
            });
            return false;
        }

        var adAddr = $("#ad_addr").val();
        var adZipcode = $("#ad_zipcode").val();
        var hasAddressClass = $("#btnAddrAdd").hasClass("has-address");
        if (!adAddr || adAddr.trim() === "" || !adZipcode || adZipcode.trim() === "" || !hasAddressClass) {
            commonAlert("주소를 등록해주세요.", function() {
                $("#btnAddrAdd").focus();
            });
            return false;
        }

        if ($("[name='rc_work[]']").is(":checked") == false) {
            commonAlert("업무구분을 하나 이상 선택해주세요.", function() {});
            return false;
        }

        if ($("[name='rc_work_negotiation']").is(":checked") == false && $("[name='rc_work_type[]']").is(":checked") == false) {
            commonAlert("근무형태를 하나 이상 선택해주세요.", function() {});
            return false;
        }

        var priceType = $("#rc_price_type_main").val();
        if (priceType !== "급여협의") {
            if ($("#rc_price_amount").val() == "" || parseInt($("#rc_price_amount").val()) <= 0) {
                commonAlert("급여 금액을 입력해주세요.", function() {
                    $("#rc_price_amount").focus();
                });
                return false;
            }
        }

        var rcDescEditor = document.getElementById('rc_desc_editor');
        var rcDescContent = rcDescEditor ? rcDescEditor.innerHTML.trim() : "";
        if (rcDescContent == "") {
            commonAlert("상세업무정보를 입력해주세요.", function() {
                if (rcDescEditor) {
                    rcDescEditor.focus();
                }
            });
            return false;
        }

        if ($("[name='rc_sdate']").val() == "" || $("[name='rc_edate']").val() == "") {
            commonAlert("채용공고기간을 선택해주세요.", function() {});
            return false;
        }

        var startDate = new Date($("[name='rc_sdate']").val());
        var endDate = new Date($("[name='rc_edate']").val());
        if (endDate < startDate) {
            commonAlert("종료일은 시작일보다 이후여야 합니다.", function() {});
            return false;
        }

        return true;
    }

    function hasFileUploads() {
        var fileInputs = ["rc_img1", "rc_img2", "rc_img3", "rc_img4"];
        for (var i = 0; i < fileInputs.length; i++) {
            var fileInput = $("[name='" + fileInputs[i] + "']")[0];
            if (fileInput && fileInput.files && fileInput.files.length > 0) {
                return true;
            }
        }
        return false;
    }
    // 1, 2 페이지 저장 후 rc_idx가 새로 발급되거나 변경된 경우 localStorage의 임시저장 데이터 키를 새로운 rc_idx로 옮기는 함수 호출
    function saveStep1And2(onSuccess) {
        collectRcWorkImg();
        // 급여 금액에서 쉼표 제거 후 serialize
        var currentAmount = removeComma($("#rc_price_amount").val());
        $("#rc_price_amount").val(currentAmount);
        var params = $("#frm").serialize();
        // serialize 후 쉼표 추가하여 화면에 표시
        $("#rc_price_amount").val(formatNumberWithComma(currentAmount));
        var prevIdx = $("[name='rc_idx']").val() || "0";
        
        //로그테이블에 저장 site_log 테이블에 저장
        comAjax("/compage_proc/recruit_write1_1", params, function(ret) {
            if (ret.code != "00") {
                commonAlert(ret.msg, function() {});
                return;
            }

            var newIdx = prevIdx;
            if (ret.data && ret.data.rc_idx) {
                newIdx = ret.data.rc_idx;
                if (newIdx && newIdx !== prevIdx) {
                    migrateRecruitDraftKeys(prevIdx, newIdx, [1, 2, 3]);
                    $("[name='rc_idx']").val(newIdx);
                }
            }

            var params2 = $("#frm").serialize();
            var prevIdx2 = $("[name='rc_idx']").val() || "0";
            
            comAjax("/compage_proc/recruit_write2_1", params2, function(ret2) {
                if (ret2.code != "00") {
                    commonAlert(ret2.msg, function() {});
                    return;
                }

                var newIdx2 = prevIdx2;
                if (ret2.data && ret2.data.rc_idx) {
                    newIdx2 = ret2.data.rc_idx;
                    if (newIdx2 && newIdx2 !== prevIdx2) {
                        migrateRecruitDraftKeys(prevIdx2, newIdx2, [1, 2, 3]);
                        $("[name='rc_idx']").val(newIdx2);
                    }
                }

            if (typeof onSuccess === "function") {
                onSuccess();
            }
        });
        });
    }
    // 3 페이지 저장과 최종 등록을 처리하는 함수 (mode에 따라 preview 또는 final 처리)
    function saveStep3AndComplete(mode) {
        // contenteditable div의 내용을 hidden input에 복사 (form serialize 시 포함되도록)
        var rcDescEditor = document.getElementById('rc_desc_editor');
        var rcDescContent = rcDescEditor ? rcDescEditor.innerHTML : "";
        $("[name='rc_desc']").val(rcDescContent);

        var hashString = buildHashString();
        var hasFiles = hasFileUploads();

        function handleSave(ret) {
            if (ret.code == "00") {
                var prevIdx = $("[name=rc_idx]").val() || "0";
                var savedRcIdx = (ret.data && ret.data.rc_idx) ? ret.data.rc_idx : prevIdx;
                
                if (savedRcIdx && savedRcIdx !== prevIdx && savedRcIdx != "0") {
                    migrateRecruitDraftKeys(prevIdx, savedRcIdx, [1, 2, 3]);
                    $("[name='rc_idx']").val(savedRcIdx);
                }

                if (mode === "preview") {
                    console.log("공고 저장 후 미리보기 모드로 이동: rc_idx =", savedRcIdx);
                    // 미리보기: draft에만 저장, 미리보기 페이지로 이동 (DB에 등록하지 않음)
                    showPreviewPage(savedRcIdx);

                } else {
                    // 최종 등록: recruit_complete 호출하여 DB에 정식등록
                    var completeParams = "rc_idx=" + savedRcIdx;

                    comAjax("/compage_proc/recruit_complete", completeParams, function(ret2) {
                        if (ret2.code == "00") {
                            var finalRcIdx = (ret2.data && ret2.data.rc_idx) ? ret2.data.rc_idx : savedRcIdx;

                            if (finalRcIdx && finalRcIdx != "0") {
                                clearRecruitDraftData(finalRcIdx);
                            }
                            clearRecruitDraftData("0");

                            location.href = "/compage/recruit_list";
                        } else {
                            commonAlert(ret2.msg, function() {});
                            return;
                        }
                    });
                }
            } else {
                commonAlert(ret.msg, function() {});
                return;
            }
        }

        if (hasFiles) {
            var formData = new FormData($("#frm")[0]);
            formData.append("rc_hash_string", hashString);

            $.ajax({
                url: "/compage_proc/recruit_write3_1",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(ret) {
                    handleSave(ret);
                },
                error: function(xhr, status, error) {
              
                    commonAlert("파일 업로드 중 오류가 발생했습니다.", function() {});
                }
            });
        } else {
            var params = $("#frm").serialize();
            params += "&rc_hash_string=" + encodeURIComponent(hashString);

            comAjax("/compage_proc/recruit_write3_1", params, function(ret) {
                handleSave(ret);
            });
        }
    }

    // 미리보기 페이지로 이동
    function showPreviewPage(rc_idx) {
        // 회사정보 저장
        var memInfo = {
            mm_comp_name: '<?= isset($mem_info["mm_comp_name"]) ? $mem_info["mm_comp_name"] : "" ?>',
            mm_phone: '<?= isset($mem_info["mm_phone"]) ? $mem_info["mm_phone"] : "" ?>'
        };
        sessionStorage.setItem('previewMemInfo', JSON.stringify(memInfo));

        // 물리적 뒤로가기 대응: sessionStorage에 플래그 설정
        // 이를 통해 recruit_write1_1.php로 돌아올 때 draft 로드 여부 판단
        sessionStorage.setItem('from_preview', 'true');

        window.location.href = '/recruit/job_post/' + rc_idx + '?preview=1';
    }

    // 미리보기에서 최종 저장 처리 (window.parent에서 접근 가능)
    function submitPreview() {
        handleSubmit("final");
    }

    function handleSubmit(mode) {
        if (!validateRequiredForSubmit()) {
            return;
        }

        savePage1Data();
        savePage2Data();
        savePage3Data();

        saveStep1And2(function() {
            saveStep3AndComplete(mode);
        });
    }

    // 페이지 로드 시 데이터 복원 (물리적 뒤로가기 포함)
    var updateTimeSection_fn;
    function restorePageData() {
        // draft 우선 확인: 입력 중이던 임시 데이터가 있으면 우선 사용
        // draft 없으면 serverData (마지막 저장값) 사용
        loadPage1Data();
        loadPage2Data();
        loadPage3Data();

        // 근무시간 및 급여 옵션 업데이트
        if (typeof updateTimeSection_fn === 'function') {
            updateTimeSection_fn();
        }
        if (typeof updateSalaryOptions === 'function') {
            updateSalaryOptions();
        }
    }
    // 페이지 로드 시 이벤트 리스너 등록 및 초기화
    window.addEventListener('DOMContentLoaded', function() {
      
        // URL 파라미터에서 rc_idx 확인 (preview 모드에서 돌아온 경우)
        // PHP에서 전달한 rcIdx 변수 사용 (URL 파라미터 대신)
        var fromPreviewFlag = sessionStorage.getItem('from_preview') === 'true';
        window.shouldLoadDraft = (rcIdx !== "0") || fromPreviewFlag;

        // rcIdx가 0이 아니면 form에 설정
        if (rcIdx && rcIdx !== "0") {
            $("[name='rc_idx']").val(rcIdx);
        }

        // 플래그 사용 후 제거 (다음 페이지 로드에서 실수로 사용되지 않도록)
        if (fromPreviewFlag) {
            sessionStorage.removeItem('from_preview');
        }

        // 페이지 로드 시 데이터 복원 (draft 로드 여부는 shouldLoadDraft로 제어)
        restorePageData();

        // 근무시간 섹션 초기화
        updateTimeSection();

        // 급여 금액 입력 필드 이벤트 리스너 (천 단위 쉼표 포맷팅)
        $("#rc_price_amount").on("input", function() {
            var value = $(this).val();
            var numericValue = removeComma(value);

            // 숫자만 입력되도록
            if (numericValue !== String(parseInt(numericValue) || 0)) {
                numericValue = String(parseInt(numericValue) || 0);
            }

            // 포맷팅된 값으로 업데이트
            $(this).val(formatNumberWithComma(numericValue));
        });

        (function() {
            function chgMonth(instance) {
                var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                    month = Number(instance.currentMonth) + 1,
                    year = instance.currentYear;
                $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
            }

            if (typeof flatpickr == 'undefined') return false;

            var defaultStartDate = '<?= isset($info["rc_sdate"]) && $info["rc_sdate"] != "" ? get_date($info["rc_sdate"]) : "" ?>';
            var defaultEndDate = '<?= isset($info["rc_edate"]) && $info["rc_edate"] != "" ? get_date($info["rc_edate"]) : "" ?>';
            var today = new Date();
            var todayStr = today.getFullYear() + '-' + (today.getMonth() + 1).toString().padStart(2, '0') + '-' + today.getDate().toString().padStart(2, '0');

            var startDate = defaultStartDate != "" && defaultStartDate >= todayStr ? defaultStartDate : todayStr;

            var endDate = defaultEndDate != "" ? defaultEndDate : null;
            if (!endDate) {
                var startDateObj = new Date(startDate);
                var endDateObj = new Date(startDateObj);
                endDateObj.setDate(endDateObj.getDate() + 60);
                endDate = endDateObj.getFullYear() + '-' +
                    (endDateObj.getMonth() + 1).toString().padStart(2, '0') + '-' +
                    endDateObj.getDate().toString().padStart(2, '0');
            }

            var startDateObj = new Date(startDate);
            var maxEndDate = new Date(startDateObj);
            maxEndDate.setDate(maxEndDate.getDate() + 60);

            // flatpickr 인스턴스를 변수에 저장 (재초기화 방지)
            var endPicker = null;

            var startPicker = flatpickr('.js-datepicker-start', {
                locale: 'ko',
                dateFormat: 'Y-m-d',
                ariaDateFormat: 'Y-m-d',
                defaultDate: startDate,
                altFormat: 'Y.m.d',
                altInput: true,
                nextArrow: '<span class="sr-text">다음</span>',
                prevArrow: '<span class="sr-text">이전</span>',
                disableMobile: true,
                minDate: 'today',
                maxDate: new Date().fp_incr(60),
                onMonthChange: function(selectedDates, dateStr, instance) {
                    chgMonth(this);
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    var day = new Date(dayElem.getAttribute('aria-label')).getDay();
                    if (day == 0) {
                        dayElem.classList.add('sun');
                    } else if (day == 6) {
                        dayElem.classList.add('sat');
                    }
                },
                onReady: function() {
                    chgMonth(this);
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0 && endPicker) {
                        var selectedStartDate = selectedDates[0];
                        endPicker.set('minDate', selectedStartDate);

                        var maxDate = new Date(selectedStartDate);
                        maxDate.setDate(maxDate.getDate() + 60);
                        endPicker.set('maxDate', maxDate);

                        var currentEndDate = endPicker.selectedDates[0];
                        if (currentEndDate && currentEndDate > maxDate) {
                            endPicker.setDate(maxDate);
                        }
                    }
                    savePage3Data();
                }
            });

            endPicker = flatpickr('.js-datepicker-end', {
                locale: 'ko',
                dateFormat: 'Y-m-d',
                ariaDateFormat: 'Y-m-d',
                defaultDate: endDate,
                altFormat: 'Y.m.d',
                altInput: true,
                nextArrow: '<span class="sr-text">다음</span>',
                prevArrow: '<span class="sr-text">이전</span>',
                disableMobile: true,
                minDate: startDate,
                maxDate: maxEndDate,
                onMonthChange: function(selectedDates, dateStr, instance) {
                    chgMonth(this);
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    var day = new Date(dayElem.getAttribute('aria-label')).getDay();
                    if (day == 0) {
                        dayElem.classList.add('sun');
                    } else if (day == 6) {
                        dayElem.classList.add('sat');
                    }
                },
                onReady: function() {
                    chgMonth(this);
                },
                onChange: function(selectedDates, dateStr, instance) {
                    savePage3Data();
                }
            });
        }());

        setTimeout(function() {
            // loadPage3Data()는 DOMContentLoaded의 restorePageData()에서 이미 호출됨 (중복 제거)
            syncTimeHiddenFields();
        }, 1000);

        // 페이지 로드 시 근무시간 섹션 및 근무협의 초기화
        updateTimeSection();
        updateNegotiationCheckbox();

        updateSalaryOptions();

        $("[name='rc_name']").on("input", savePage1Data);
        $("#rc_price_type_main").on("change", function() {
            updateSalaryOptions();
            savePage1Data();
        });
        $("#rc_price_amount").on("input", savePage1Data);
        $("[name='rc_price_possible']").on("change", savePage1Data);

        $("[name='rc_work[]']").on("change", savePage1Data);

        // 근무시간 섹션 표시/숨김 로직 및 근무협의 연동
        function updateTimeSection() {
            var isAM = $("[name='rc_work_type[]'][value='주간']").is(":checked");
            var isPM = $("[name='rc_work_type[]'][value='야간']").is(":checked");
            var is2 = $("[name='rc_work_type[]'][value='2교대']").is(":checked");

            if (isAM || is2) {
                $("#time_cont1").show();
            } else {
                $("#time_cont1").hide();
                // 주간 해제 시 기본값 리셋
                $("[name='rc_am_stime_h']").val("09");
                $("[name='rc_am_stime_m']").val("00");
                $("[name='rc_am_etime_h']").val("18");
                $("[name='rc_am_etime_m']").val("00");
            }

            if (isPM || is2) {
                $("#time_cont2").show();
            } else {
                $("#time_cont2").hide();
                // 야간 해제 시 기본값 리셋
                $("[name='rc_pm_stime_h']").val("21");
                $("[name='rc_pm_stime_m']").val("00");
                $("[name='rc_pm_etime_h']").val("06");
                $("[name='rc_pm_etime_m']").val("00");
            }

            // 근무협의 자동 체크: 주간, 야간, 2교대 모두 선택되면 근무협의도 자동 체크
            updateNegotiationCheckbox();
        }

        // 전역 함수로 할당 (물리적 뒤로가기 시 사용)
        updateTimeSection_fn = updateTimeSection;

        // 근무협의 체크박스 자동 업데이트 함수
        function updateNegotiationCheckbox() {
            var isAM = $("[name='rc_work_type[]'][value='주간']").is(":checked");
            var isPM = $("[name='rc_work_type[]'][value='야간']").is(":checked");
            var is2 = $("[name='rc_work_type[]'][value='2교대']").is(":checked");

            // 주간, 야간, 2교대 3개가 모두 선택되면 근무협의 자동 체크
            // 하나라도 선택되지 않으면 근무협의 자동 언체크
            var allSelected = isAM && isPM && is2;
            $("[name='rc_work_negotiation']").prop("checked", allSelected);
        }

        $("[name='rc_work_type[]']").on("change", function() {
            updateTimeSection();
            savePage1Data();
        });

        // 근무시간 입력 변경 시 저장
        $("[name='rc_am_stime_h'], [name='rc_am_stime_m'], [name='rc_am_etime_h'], [name='rc_am_etime_m']").on("change", savePage1Data);
        $("[name='rc_pm_stime_h'], [name='rc_pm_stime_m'], [name='rc_pm_etime_h'], [name='rc_pm_etime_m']").on("change", savePage1Data);

        $("[name='rc_work_negotiation']").on("change", function() {
            var isNegotiationChecked = $(this).is(":checked");

            if (isNegotiationChecked) {
                // 근무협의 체크 → 주간, 야간, 2교대 모두 체크
                $("[name='rc_work_type[]'][value='주간']").prop("checked", true);
                $("[name='rc_work_type[]'][value='야간']").prop("checked", true);
                $("[name='rc_work_type[]'][value='2교대']").prop("checked", true);
            } else {
                // 근무협의 언체크 → 근무형태 모두 언체크
                $("[name='rc_work_type[]']").prop("checked", false);
            }

            updateTimeSection();
            savePage1Data();
        });

        $("[name='rc_life_type[]']").on("change", savePage2Data);

        $("[name='rc_desc']").on("input", savePage3Data);
        $("[name='rc_hash[]']").on("change", savePage3Data);
        $("[name='rc_day[]'], [name='rc_day_all']").on("change", savePage3Data);
        $("[name='rc_sex[]'], [name='rc_sex_all']").on("change", savePage3Data);
        $("[name='rc_nation[]'], [name='rc_nation_all']").on("change", savePage3Data);
        $("[name='rc_sage'], [name='rc_eage'], [name='rc_age_all']").on("change", savePage3Data);

        $(document).on("change", "[name='rc_sdate'], [name='rc_edate']", function() {
            savePage3Data();
        });

        (function() {
            document.querySelectorAll('input[data-select-limit]').forEach(function(item) {
                item.addEventListener('change', function() {
                    var name = this.getAttribute('name');
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

        $("[name='rc_work_negotiation']").on("change", function() {
            var isChecked = $(this).is(":checked");
            if (isChecked) {
                $("[name='rc_work_type[]']").prop("checked", true);
            } else {
                $("[name='rc_work_type[]']").prop("checked", false);
            }
        });

        // 페이지 로드 시 근무협의 체크 상태에 따라 근무형태 버튼 초기화
        if ($("[name='rc_work_negotiation']").is(":checked")) {
            $("[name='rc_work_type[]']").prop("checked", true);
        }

        $("[name='rc_work_type[]']").on("change", function() {
            if ($("[name='rc_work_negotiation']").is(":checked")) {
                if (!$(this).is(":checked")) {
                    $(this).prop("checked", true);
                }
            }
        });

        $(document).on("click", ".form-checkbox .label", function(e) {
            e.preventDefault();
            var $input = $(this).siblings("input[type='checkbox'], input[type='radio']");
            if ($input.length > 0) {
                if ($input.attr("type") === "radio") {
                    if ($input.is(":checked")) {
                        
                       // console.log("이미 선택된 라디오 버튼입니다. 체크 해제합니다.");
                        $("input[name='" + $input.attr("name") + "']").prop("checked", false);
                    } else {
                        //  console.log("라디오 버튼이 선택되지 않았습니다. 체크합니다.");
                        $input.prop("checked", true);
                    }
                } else {
                  //  console.log("체크박스 상태를 토글합니다. 현재 상태: " + ($input.is(":checked") ? "체크됨" : "체크되지 않음"));
                    $input.prop("checked", !$input.prop("checked"));
                }
                $input.trigger("change");
            }
        });

        $(".js-detail-register").on("click", function(e) {
            e.preventDefault();
            var $sections = $(".js-detail-section");
            var $page = $(".page");

            var isVisible = $sections.hasClass("show");

            if (isVisible) {
                $sections.removeClass("show").slideUp(300, function() {
                    if (detailSectionScrollPosition > 0) {
                        $page.scrollTop(detailSectionScrollPosition);
                    }
                    detailSectionScrollPosition = 0;
                });
            } else {
                detailSectionScrollPosition = $page.scrollTop();
                $sections.addClass("show").slideDown(300, function() {
                    setTimeout(function() {
                        $page.scrollTop($page[0].scrollHeight);
                    }, 100);
                });
            }
            setTimeout(savePage3Data, 350);
        });

        $(".js-add-hashtag").on("click", function(e) {
            e.preventDefault();
            var $inputArea = $(this).closest(".form-row").find(".hashtag-input-area");
            var $input = $inputArea.find(".js-hashtag-input");

            $inputArea.slideDown(300);
            setTimeout(function() {
                $input.focus();
            }, 350);
        });

        $(".js-confirm-hashtag").on("click", function(e) {
            e.preventDefault();
            var $input = $(this).siblings(".js-hashtag-input");
            var hashText = $input.val().trim();

            if (hashText == "") {
                commonAlert("해시태그를 입력해주세요.", function() {});
                return;
            }

            var cleanHash = hashText.replace(/#/g, '').trim();
            if (cleanHash == "") {
                commonAlert("올바른 해시태그를 입력해주세요.", function() {});
                return;
            }

            var exists = false;
            $("[name='rc_hash[]']").each(function() {
                if ($(this).val() == cleanHash) {
                    exists = true;
                    return false;
                }
            });

            if (exists) {
                commonAlert("이미 추가된 해시태그입니다.", function() {});
                return;
            }

            var checkedCount = $("[name='rc_hash[]']:checked").length;
            if (checkedCount >= 4) {
                commonAlert("해시태그는 최대 4개까지 선택 가능합니다.", function() {});
                return;
            }

            var $newLabel = $('<label class="form-toggle-btn2 sm tag">' +
                '<input type="checkbox" name="rc_hash[]" value="' + cleanHash + '" checked>' +
                '<span class="btn">' + cleanHash + '</span>' +
                '</label>');

            var $addButton = $(".js-add-hashtag");
            $addButton.before($newLabel);

            $input.val("");
            $inputArea.slideUp(300);

            savePage3Data();
        });

        $(document).on("change", "[name='rc_hash[]']", function() {
            var checkedCount = $("[name='rc_hash[]']:checked").length;
            if (checkedCount > 4) {
                $(this).prop("checked", false);
                commonAlert("해시태그는 최대 4개까지 선택 가능합니다.", function() {});
            }
        });

        (function() {
            var weekdayDefaults = ["월", "화", "수", "목", "금"];
            var $dayCheckboxes = $("[name='rc_day[]']");
            var hasExistingSelection = $dayCheckboxes.is(":checked");
            var isAllDaysSelected = $("[name='rc_day_all']").is(":checked");

            if (!hasExistingSelection && !isAllDaysSelected) {
                $dayCheckboxes.each(function() {
                    var $checkbox = $(this);
                    if (weekdayDefaults.indexOf($checkbox.val()) !== -1) {
                        $checkbox.prop("checked", true);
                    }
                });
            }
        }());

        (function() {
            var $sexCheckboxes = $("[name='rc_sex[]']");
            var hasExistingSexSelection = $sexCheckboxes.is(":checked");
            var isSexAllSelected = $("[name='rc_sex_all']").is(":checked");

            if (!hasExistingSexSelection && !isSexAllSelected) {
                // 각 성별 항목 모두 체크
                $sexCheckboxes.prop("checked", true);
                // "성별무관" 체크 및 이벤트 발생
                $("[name='rc_sex_all']").prop("checked", true).trigger("change");
            }
        }());

        (function() {
            var $nationCheckboxes = $("[name='rc_nation[]']");
            var hasExistingNationSelection = $nationCheckboxes.is(":checked");
            var isNationAllSelected = $("[name='rc_nation_all']").is(":checked");

            if (!hasExistingNationSelection && !isNationAllSelected) {
                // 각 국적 항목 모두 체크
                $nationCheckboxes.prop("checked", true);
                // "국적무관" 체크 및 이벤트 발생
                $("[name='rc_nation_all']").prop("checked", true).trigger("change");
            }
        }());

        (function() {
            var hasSageSelected = $("[name='rc_sage']").val() !== "";
            var hasEageSelected = $("[name='rc_eage']").val() !== "";
            var isAgeAllSelected = $("[name='rc_age_all']").is(":checked");

            if (!hasSageSelected && !hasEageSelected && !isAgeAllSelected) {
                $("[name='rc_age_all']").prop("checked", true).trigger("change");
            }
        }());

        $("[name='rc_day_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_day[]']").prop("checked", true);
            } else {
                $("[name='rc_day[]']").prop("checked", false);
            }
        });

        // 희망요일 이벤트
        $("[name='rc_day[]']").on("change", function() {
            var checkedCount = $("[name='rc_day[]']:checked").length;
            var totalCount = $("[name='rc_day[]']").length;
            if (checkedCount === totalCount) {
                // 모든 요일이 선택되면 요일무관 체크
                $("[name='rc_day_all']").prop("checked", true);
            } else if ($("[name='rc_day_all']").is(":checked")) {
                // 하나라도 언체크되면 요일무관 언체크
                $("[name='rc_day_all']").prop("checked", false);
            }
        });

        // 희망성별 무관 이벤트
        $("[name='rc_sex_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_sex[]']").prop("checked", true);
            } else {
                $("[name='rc_sex[]']").prop("checked", false);
            }
        });

        // 희망성별 버튼 이벤트
        $("[name='rc_sex[]']").on("change", function() {
            var checkedCount = $("[name='rc_sex[]']:checked").length;
            var totalCount = $("[name='rc_sex[]']").length;
            if (checkedCount === totalCount) {
                // 모든 성별이 선택되면 성별무관 체크
                $("[name='rc_sex_all']").prop("checked", true);
            } else {
                // 하나라도 언체크되면 성별무관 언체크
                $("[name='rc_sex_all']").prop("checked", false);
            }
        });

        // 희망국적 무관 이벤트
        $("[name='rc_nation_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_nation[]']").prop("checked", true);
            } else {
                $("[name='rc_nation[]']").prop("checked", false);
            }
        });

        // 희망국적 버튼 이벤트
        $("[name='rc_nation[]']").on("change", function() {
            var checkedCount = $("[name='rc_nation[]']:checked").length;
            var totalCount = $("[name='rc_nation[]']").length;
            if (checkedCount === totalCount) {
                // 모든 국적이 선택되면 국적무관 체크
                $("[name='rc_nation_all']").prop("checked", true);
            } else {
                // 하나라도 언체크되면 국적무관 언체크
                $("[name='rc_nation_all']").prop("checked", false);
            }
        });

        // 희망나이 무관 이벤트
        $("[name='rc_age_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_sage']").val("");
                $("[name='rc_eage']").val("");
            }
        });

        // 희망나이 선택 이벤트
        $("[name='rc_sage'], [name='rc_eage']").on("change", function() {
            var sageVal = $("[name='rc_sage']").val();
            var eageVal = $("[name='rc_eage']").val();

            // 둘 다 선택되면 나이무관 체크
            if (sageVal !== "" && eageVal !== "") {
                $("[name='rc_age_all']").prop("checked", true);
            } else {
                // 하나라도 선택되지 않으면 나이무관 언체크
                $("[name='rc_age_all']").prop("checked", false);
            }
        });


        // 공고노출지역 1, 2 삭제 버튼 클릭
        $(document).on("click", ".js-clear-expose-addr", function(e) {
            e.preventDefault();
            var index = $(this).data("index");
            var btnId = "#btnExposeAddr" + index;
            var $btn = $(btnId);

            // 숨겨진 필드 초기화
            if (index === 1) {
                $("#expose_addr1_zipcode").val("");
                $("#expose_addr1_jibun").val("");
                $("#expose_addr1_doro_val").val("");
                $("#expose_addr1_addr").val("");
                $("#expose_addr1_gps_x").val("");
                $("#expose_addr1_gps_y").val("");
            } else if (index === 2) {
                $("#expose_addr2_zipcode").val("");
                $("#expose_addr2_jibun").val("");
                $("#expose_addr2_doro_val").val("");
                $("#expose_addr2_addr").val("");
                $("#expose_addr2_gps_x").val("");
                $("#expose_addr2_gps_y").val("");
            }

            // 버튼 상태 초기화
            $btn.find(".address-text").text("");
            $btn.removeClass("has-address");

            // 폼 데이터 저장
            savePage1Data();
        });

        $(document).on("change", ".js-file-preview", function() {
            var $label = $(this).closest(".form-pic");
            var file = this.files[0]; // 목록 중 첫 번째 파일 객체 (File 객체)

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var $preview = $label.find(".preview");
                    $preview.html('<img src="' + e.target.result + '" />');
                    $label.addClass("has-image");
                };
                reader.readAsDataURL(file);
            } else {
                var $preview = $label.find(".preview");
                $preview.html("");
                $label.removeClass("has-image");
            }
        });

        // 1번(메인사진): 선택 즉시 미리보기 + 즉시 업로드
        $(document).on("change", "#rc_img1_input", function() {
            var $label = $(this).closest(".form-pic");
            var $oriInput = $label.find("[name='rc_img1_ori']");
            var $input = this;

            // X 취소용 스냅샷 저장
            $label.data("prevOri", $oriInput.val());
            $label.data("prevPreviewHtml", $label.find(".preview").html());
            $label.data("prevHasImage", $label.hasClass("has-image"));

            var file = this.files[0]; //목록 중 첫 번째 파일 객체 (File 객체)
            if (!file) return;

            $label.find(".btn-pic-remove").show();

            var formData = new FormData();
            formData.append("rc_img1", file);

            showLoadingOverlay();
            $.ajax({
                url: "/compage_proc/recruit_main_img_upload",
                type: "POST",
                data: formData,
                processData: false, // 중요: 데이터를 쿼리 스트링으로 변환하지 않도록 설정/  jQuery는 기본적으로 데이터를 보내기 전에 문자열(key=value)로 변환하려고 시도하지만, FormData 객체는 이미 올바른 형식이므로 변환하지 않아야 함
                contentType: false,  // 중요: 브라우저가 알아서 boundary를 포함한 Content-Type을 설정하도록 함
                dataType: "json",
                success: function(ret) {
                    hideLoadingOverlay();
                    if (ret.code == "00") {
                        // 최종확정 단계에서 이 값이 그대로 저장됨
                        $oriInput.val(ret.url);
                        // 로컬 미리보기는 .js-file-preview 핸들러가 이미 처리했음
                    } else {
                        commonAlert(ret.msg, function() {
                            // 실패시 취소 처리
                            $label.find(".btn-pic-remove").trigger("click");
                        });
                    }
                },
                error: function() {
                    hideLoadingOverlay();
                    commonAlert("사진 업로드 중 오류가 발생했습니다.", function() {
                        $input.value = "";
                    });
                }
            });
        });

        // 2~4번(상세페이지): 선택시 즉시 로컬 미리보기 + 업로드 + 에디터 삽입
        $(document).on("change", ".js-detail-photo-select", function() {
            var $label = $(this).closest(".form-pic");
            var file = this.files[0];
            if (!file) return;

            // 1. 로컬 미리보기
            var reader = new FileReader();
            reader.onload = function(e) {
                $label.find(".preview").html('<img src="' + e.target.result + '" />');
                $label.addClass("has-image");
                $label.find(".btn-pic-remove").show();
            };
            reader.readAsDataURL(file);

            // 2. 즉시 업로드 + 에디터 삽입
            uploadAndInsertIntoEditor(file, $label);
        });

        // X버튼(공용): 1번은 업로드 이전 상태로 복원, 2~4번은 초기화 + 에디터에서도 제거
        $(document).on("click", ".form-pic .btn-pic-remove", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $label = $(this).closest(".form-pic");
            var $input = $label.find("input[type=file]");
            $input.val("");

            var $oriInput = $label.find("[name='rc_img1_ori']");
            if ($oriInput.length) {
                // 1번 박스: 선택 이전 스냅샷으로 복원
                var $preview = $label.find(".preview");
                $preview.html($label.data("prevPreviewHtml") || "");
                // 혹시 남은 이미지 태그 명시적으로 제거
                $preview.find("img").remove();
                $label.toggleClass("has-image", !!$label.data("prevHasImage"));
                $oriInput.val($label.data("prevOri") || "");
            } else {
                // 2~4번 박스: 미리보기 초기화 + 에디터에서 해당 이미지 제거
                var uploadedUrl = $label.data("uploadedUrl");
                if (uploadedUrl) {
                    // 상세업무정보 에디터에서 해당 URL의 이미지 제거
                    var $editor = $("#rc_desc_editor");
                    $editor.find("img[data-uploaded-url='" + uploadedUrl.replace(/'/g, "\\'") + "']").closest(".image-wrapper").remove();
                    $label.data("uploadedUrl", null);
                }
                $label.find(".preview").html("");
                $label.removeClass("has-image");
            }
            $(this).hide();
        });

        // 파일 선택 및 에디터에 이미지 삽입 공용 함수 (2~4번 박스 전용)
        function uploadAndInsertIntoEditor(file, $label) {
            var formData = new FormData();
            formData.append('Filedata', file);

            showLoadingOverlay();
            $.ajax({
                url: '/compage_proc/se2_image_upload',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    hideLoadingOverlay();
                    if (response.success === 1) {
                        var editor = document.getElementById('rc_desc_editor');
                        var imageWrapper = document.createElement('div');
                        imageWrapper.className = 'image-wrapper';
                        imageWrapper.style.cssText = 'position: relative; display: inline-block; margin: 10px 0;';
                        imageWrapper.dataset.uploadedUrl = response.url;

                        var img = document.createElement('img');
                        img.src = response.url;
                        img.alt = response.name;
                        img.style.cssText = 'max-width: 100%; height: auto; border-radius: 5px; border: 1px solid #ddd; display: block;';
                        img.setAttribute('contenteditable', 'false');
                        img.dataset.uploadedUrl = response.url;

                        imageWrapper.appendChild(img);
                        editor.appendChild(document.createElement('br'));
                        editor.appendChild(imageWrapper);
                        // 박스에 업로드된 URL 저장 (X 버튼으로 제거할 때 사용)
                        if ($label && $label.length) {
                            $label.data("uploadedUrl", response.url);
                        }
                    } else {
                        commonAlert(file.name + ' 업로드에 실패했습니다.');
                    }
                },
                error: function() {
                    hideLoadingOverlay();
                    commonAlert(file.name + ' 업로드 중 오류가 발생했습니다.');
                }
            });
        }

        $("#btnPreview").click(function() {
            handleSubmit("preview");
        });

        $("#btnSubmit").click(function() {
            handleSubmit("final");
        });

        $(".btn-back").click(function(e) {
            e.preventDefault();
            savePage1Data();
            savePage2Data();
            savePage3Data();
            clearRecruitDraftData($("[name='rc_idx']").val() || "0"); //임시저장 삭제처리
            location.href = "/compage/recruit_list";
        });

        // 히스토리 상태 저장 (무한루프 방지를 위해 page 상태 추가)
        history.pushState({page: 'recruit_write1_1'}, null, location.href); // 

        window.addEventListener('popstate', function(e) {
            // recruit_write 페이지에서만 처리
            if (!e.state || e.state.page === 'recruit_write1_1') {
                savePage1Data();
                savePage2Data();
                savePage3Data();
                clearRecruitDraftData($("[name='rc_idx']").val() || "0");
                location.href = "/compage/recruit_list";
            }
        });
    });

    // 폼 제출 시 contenteditable div의 내용을 숨겨진 input에 저장
    $('form').on('submit', function(e) {
        var editorContent = document.getElementById('rc_desc_editor').innerHTML;
        document.getElementById('rc_desc_input').value = editorContent;
     
    });


</script>
<script src="/resource/shop/mobile/js/common-utils.js"></script>
<script>
  // 공고노출지역 팝업 콜백 - 현재 팝업 타입에 따라 해당 필드에만 저장
  window.onChangeAreaApply = function(data) {
    var pageType = window.__addrPopupPageType || 'default';

    BrowserLogger.info('recruit_write1_1: onChangeAreaApply 실행', {
      pageType: pageType,
      hasSearchArea: !!(data && data.searchArea && data.searchArea.length > 0),
      dataKeys: data ? Object.keys(data) : []
    });

    // 주소 등록 부분 (default)
    if (pageType === 'default') {
      BrowserLogger.info('주소 등록 처리', {
        pageType: pageType,
        selectAddrInfo: typeof selectAddrInfo
      });
    }
    // expose1 또는 expose2인 경우만 처리
    else if (pageType === 'expose1' || pageType === 'expose2') {
      // 선택된 필드 인덱스 추출 (expose1 -> 1, expose2 -> 2)
      var fieldIndex = pageType.replace('expose', '');

      // 해당 필드의 hidden 필드만 초기화
      $("#expose_addr" + fieldIndex + "_zipcode").val("");
      $("#expose_addr" + fieldIndex + "_jibun").val("");
      $("#expose_addr" + fieldIndex + "_doro_val").val("");
      $("#expose_addr" + fieldIndex + "_addr").val("");
      $("#expose_addr" + fieldIndex + "_gps_x").val("");
      $("#expose_addr" + fieldIndex + "_gps_y").val("");
      var $btn = $("#btnExposeAddr" + fieldIndex);
      $btn.find(".address-text").text("");
      $btn.removeClass("has-address");
      $btn.closest(".address-btn-wrapper").find(".address-clear-btn").hide();

      // 새 데이터를 해당 필드에만 매핑
      if (data.searchArea && data.searchArea.length > 0) {
        var area = data.searchArea[0]; // 최대 1개만 선택 가능하므로 첫 번째만 사용
        $("#expose_addr" + fieldIndex + "_addr").val(area.addr);
        var $btn = $("#btnExposeAddr" + fieldIndex);
        // 원본 주소에 fRemoveBigAdrJS를 적용하여 표시
        $btn.find(".address-text").text(fRemoveBigAdrJS(area.addr));
        $btn.addClass("has-address");
        $btn.closest(".address-btn-wrapper").find(".address-clear-btn").show();
      }
    }

    savePage1Data();
    return true;
  };

  // 공고노출지역 팝업이 닫힐 때 body overflow 복원
  $(document).ready(function() {
    var $panelExpose = $("#changeAreaExpose");
    if ($panelExpose.length > 0) {
      var observerExpose = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
            var $target = $(mutation.target);
            if (!$target.hasClass('on')) {
              document.body.style.overflow = '';
              document.body.style.paddingRight = '';
            }
          }
          if (mutation.type === 'attributes' && mutation.attributeName === 'hidden') {
            var $target = $(mutation.target);
            if ($target[0].hidden === true) {
              document.body.style.overflow = '';
              document.body.style.paddingRight = '';
            }
          }
        });
      });

      observerExpose.observe($panelExpose[0], {
        attributes: true,
        attributeFilter: ['class', 'hidden', 'style']
      });
    }
  });

  // 공고노출지역 버튼 클릭 시 body overflow 설정 및 pageType 동적 설정
  $(document).on('click', '.js-toggle-panel[aria-controls="changeAreaExpose"]', function() {
    // 클릭된 버튼의 ID에서 인덱스 추출
    var buttonId = $(this).attr('id');
    if (buttonId === 'btnExposeAddr1') {
      window.__addrPopupPageType = 'expose1';
    } else if (buttonId === 'btnExposeAddr2') {
      window.__addrPopupPageType = 'expose2';
    }
    document.body.style.overflow = 'hidden';

    // 팝업 내 선택 기록 복원
    setTimeout(function() {
      if (window.restoreExposeAddrPopup) {
        window.restoreExposeAddrPopup();
      }
    }, 50);
  });

  // ========== 근무시간 선택 바텀시트 JS 로직 ==========

  // 휠 스크롤 위치에서 중앙 항목 결정 (requestAnimationFrame 디바운싱)
  let wheelScrollTimeout;
  function updateWheelActiveItem(wheelCol) {
    var ul = wheelCol.querySelector('ul');
    if (!ul) return;

    var items = Array.from(ul.querySelectorAll('li')); // Array.from: NodeList를 배열로 변환
    var colRect = wheelCol.getBoundingClientRect(); // 컬럼의 뷰포트 위치와 크기
    var colCenterY = colRect.height / 2; // 컬럼의 중앙 Y 좌표 (뷰포트 기준)
    var minDistance = Infinity;
    var activeItem = null;

    items.forEach(function(item) {
      var itemRect = item.getBoundingClientRect();
      var itemCenterY = itemRect.top + itemRect.height / 2 - colRect.top;
      var distance = Math.abs(itemCenterY - colCenterY);

      if (distance < minDistance) {
        minDistance = distance;
        activeItem = item;
      }
    });

    // 모든 항목에서 active 클래스 제거
    items.forEach(function(item) {
      item.classList.remove('active');
    });

    // 중앙 항목에 active 클래스 추가
    if (activeItem) {
      activeItem.classList.add('active');
    }
  }

  // 휠 컬럼 스크롤 이벤트
  function initWheelScroll(wheelCol) {
    wheelCol.addEventListener('scroll', function() {
      clearTimeout(wheelScrollTimeout);
      wheelScrollTimeout = setTimeout(function() {
        updateWheelActiveItem(wheelCol);

        // wheelHour 변경 시 wheelAMPM도 함께 동기화
        if (wheelCol.id === 'wheelHour') {
          var sheet = document.getElementById('workTimePickerSheet');
          if (sheet) {
            var wheelAMPM = sheet.querySelector('#wheelAMPM');
            if (wheelAMPM) {
              // 현재 선택된 시간 가져오기 (0-23)
              var activeHourItem = wheelCol.querySelector('li.active');
              if (activeHourItem) {
                var selectedHour = parseInt(activeHourItem.textContent.trim()) || 0;
                // 시간에 따라 오전(0-11)/오후(12-23) 결정
                var ampmIndex = selectedHour >= 12 ? 1 : 0;
                // wheelAMPM 스크롤 동기화
                var ampmItems = wheelAMPM.querySelectorAll('li');
                if (ampmItems[ampmIndex]) {
                  var ul = wheelAMPM.querySelector('ul');
                  if (ul) {
                    var targetScrollTop = ampmItems[ampmIndex].offsetTop - (wheelAMPM.clientHeight / 2 - 22);
                    wheelAMPM.scrollTop = targetScrollTop;
                  }
                  updateWheelActiveItem(wheelAMPM);
                }
              }
            }
          }
        }
      }, 50);
    });

    // 초기 active 항목 설정
    updateWheelActiveItem(wheelCol);
  }

  // 바텀시트 열 때 호출 - 현재 값으로 휠 위치 설정
  window.openTimePicker = function(prefix) {
    var sheet = document.getElementById('workTimePickerSheet');
    var hourInput = document.querySelector('input[name="' + prefix + '_h"]');
    var minuteInput = document.querySelector('input[name="' + prefix + '_m"]');

    if (!sheet || !hourInput || !minuteInput) return;

    // 배경 스크롤 잠금
    var pageElement = document.querySelector('.page');
    if (pageElement) {
      pageElement.style.overflow = 'hidden';
    }

    var currentHour = parseInt(hourInput.value) || 0;
    var currentMinute = parseInt(minuteInput.value) || 0;

    // 휠 컬럼 참조 저장
    var wheelAMPM = sheet.querySelector('#wheelAMPM');
    var wheelHour = sheet.querySelector('#wheelHour');
    var wheelMinute = sheet.querySelector('#wheelMinute');

    // 시간 휠을 현재 시간으로 스크롤 (0-23)
    if (wheelHour) {
      var hourItems = wheelHour.querySelectorAll('li');
      if (hourItems[currentHour]) {
        var targetHourScrollTop = hourItems[currentHour].offsetTop - (wheelHour.clientHeight / 2 - 22);
        wheelHour.scrollTop = targetHourScrollTop; // 중앙 항목이 화면 중앙에 오도록 스크롤
        updateWheelActiveItem(wheelHour);

        // AMPM 휠도 현재 시간에 따라 동기화 (0-11: 오전, 12-23: 오후)
        if (wheelAMPM) {
          var ampmIndex = currentHour >= 12 ? 1 : 0;
          var ampmItems = wheelAMPM.querySelectorAll('li');
          if (ampmItems[ampmIndex]) {
            var targetAMPMScrollTop = ampmItems[ampmIndex].offsetTop - (wheelAMPM.clientHeight / 2 - 22);
            wheelAMPM.scrollTop = targetAMPMScrollTop;
            updateWheelActiveItem(wheelAMPM);
          }
        }
      }
    }

    // 분 휠을 현재 분(10분 단위)로 스크롤
    if (wheelMinute) {
      var minuteIndex = Math.round(currentMinute / 10);
      var minuteItems = wheelMinute.querySelectorAll('li');
      if (minuteItems[minuteIndex]) {
        var targetMinuteScrollTop = minuteItems[minuteIndex].offsetTop - (wheelMinute.clientHeight / 2 - 22);
        wheelMinute.scrollTop = targetMinuteScrollTop;
        updateWheelActiveItem(wheelMinute);
      }
    }

    // 활성 prefix 저장
    sheet.setAttribute('data-active-prefix', prefix);
  };

  // 확인 버튼 클릭 핸들러
  window.confirmTimePicker = function() {
    var sheet = document.getElementById('workTimePickerSheet');
    if (!sheet) return;

    var prefix = sheet.getAttribute('data-active-prefix');
    if (!prefix) return;

    var wheelHour = sheet.querySelector('#wheelHour');
    var wheelMinute = sheet.querySelector('#wheelMinute');

    if (!wheelHour || !wheelMinute) return;

    // 중앙(active) 항목의 값 읽기
    var activeHourItem = wheelHour.querySelector('li.active');
    var activeMinuteItem = wheelMinute.querySelector('li.active');

    if (!activeHourItem || !activeMinuteItem) return;

    var selectedHour = activeHourItem.textContent.trim();
    var selectedMinute = activeMinuteItem.textContent.trim();

    // Hidden input 값 업데이트
    var hourInput = document.querySelector('input[name="' + prefix + '_h"]');
    var minuteInput = document.querySelector('input[name="' + prefix + '_m"]');

    if (hourInput && minuteInput) {
      hourInput.value = selectedHour;
      minuteInput.value = selectedMinute;

      // 트리거 버튼의 표시 텍스트 업데이트
      var trigger = document.querySelector('.js-time-picker-trigger[data-time-prefix="' + prefix + '"]');
      if (trigger) {
        var timeDisplay = trigger.querySelector('.time-display');
        if (timeDisplay) {
          timeDisplay.textContent = selectedHour + ' : ' + selectedMinute;
        }
      }
    }

    // 데이터 저장 함수 호출
    if (typeof syncTimeHiddenFields === 'function') {
      syncTimeHiddenFields();
    }
    if (typeof savePage1Data === 'function') {
      savePage1Data();
    }

    // 배경 스크롤 복원
    var pageElement = document.querySelector('.page');
    if (pageElement) {
      pageElement.style.overflow = '';
    }

    // 바텀시트 닫기
    if (typeof closePanel === 'function') {
      closePanel(sheet);
    }
  };

  // 문서 로드 완료 후 초기화
  document.addEventListener('DOMContentLoaded', function() {
    var sheet = document.getElementById('workTimePickerSheet');
    if (!sheet) return;

    var wheelAMPM = sheet.querySelector('#wheelAMPM');
    var wheelHour = sheet.querySelector('#wheelHour');
    var wheelMinute = sheet.querySelector('#wheelMinute');

    // 휠 스크롤 이벤트 초기화
    if (wheelAMPM) initWheelScroll(wheelAMPM);
    if (wheelHour) initWheelScroll(wheelHour);
    if (wheelMinute) initWheelScroll(wheelMinute);

    // 바텀시트가 닫힐 때 스크롤 복원
    var timePickerObserver = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
          var $target = $(mutation.target);
          if (!$target.hasClass('on')) {
            // 바텀시트가 닫혔을 때 스크롤 복원
            var pageElement = document.querySelector('.page');
            if (pageElement) {
              pageElement.style.overflow = '';
            }
          }
        }
        if (mutation.type === 'attributes' && mutation.attributeName === 'hidden') {
          var $target = $(mutation.target);
          if ($target[0].hidden === true) {
            // 바텀시트가 숨겨졌을 때 스크롤 복원
            var pageElement = document.querySelector('.page');
            if (pageElement) {
              pageElement.style.overflow = '';
            }
          }
        }
      });
    });

    timePickerObserver.observe(sheet, {
      attributes: true,
      attributeFilter: ['class', 'hidden', 'style']
    });

    // 바텀시트 Swipe 초기화
    if (typeof initPanelSwipe === 'function') {
      initPanelSwipe('workTimePickerSheet');
    }

    // 시간 선택 트리거 버튼 클릭 이벤트
    var timeTriggers = document.querySelectorAll('.js-time-picker-trigger');
    timeTriggers.forEach(function(trigger) {
      trigger.addEventListener('click', function(e) {
        e.preventDefault();
        var prefix = this.getAttribute('data-time-prefix');
        if (prefix) {
          openTimePicker(prefix);
        }
      });
    });
  });
</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>
<?php
$addrPopupId = 'changeAreaExpose';
include(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new_tpl.php');
?>

<!-- 근무시간 선택 바텀시트 -->
<?php
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_helpers.php');
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_template.php');

// 시/분 휠피커 콘텐츠 생성
// 오전/오후 리스트
$ampmList = '<li>오전</li><li>오후</li>';

// 시간 리스트 (0-23)
$hoursList = '';
for ($h = 0; $h <= 23; $h++) {
    $hoursList .= '<li>' . str_pad($h, 2, "0", STR_PAD_LEFT) . '</li>';
}

$minutesList = '';
for ($m = 0; $m <= 50; $m += 10) {
    $minutesList .= '<li>' . str_pad($m, 2, "0", STR_PAD_LEFT) . '</li>';
}

$timePickerContent = '
<div class="time-wheel-picker">
    <div class="wheel-col wheel-col-ampm" id="wheelAMPM">
        <ul>' . $ampmList . '</ul>
    </div>
    <div class="wheel-col wheel-col-hour" id="wheelHour">
        <ul>' . $hoursList . '</ul>
    </div>
    <div class="wheel-col-separator">:</div>
    <div class="wheel-col wheel-col-minute" id="wheelMinute">
        <ul>' . $minutesList . '</ul>
    </div>
    <div class="wheel-highlight-bar"></div>
</div>';

renderBottomSheet(
    'workTimePickerSheet',
    '근무시간을 선택해 주세요',
    $timePickerContent,
    '확인',
    'btnTimePickerConfirm',
    'confirmTimePicker()',
    'bottom-sheet-time-picker',
    'time-wheel-picker-content'
);
?>