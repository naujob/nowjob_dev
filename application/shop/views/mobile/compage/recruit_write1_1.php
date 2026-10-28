<div class="page page-write page-recruit-write" style="background: #f9f7f7; scroll-padding-top: 0px; scroll-padding-bottom: 0;">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
    </div><!-- //top-bar -->
    <!-- 단계별 진행 게이지바 -->
    <div class="step-progress-bar">
        <div class="step-progress-fill" id="stepProgressFill"></div>
    </div>
    <div class="page-content" style="padding: 0;">
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
                    <!-- 단계 1: 공고제목 -->
                    <div class="step-section" data-step="1">
                    <!-- 공고제목 섹션 -->
                    <div class="form-row">
                        <p class="step-question"><span class="step-question-main">어떤일을 담당할<br>인재를 찾으시나요?</span></p>
                        <div class="input-field-wrapper">
                            <div class="label-with-counter">
                                <label class="form-label">공고제목<span class="required-asterisk">*</span></label>
                                <span class="char-counter"><span id="rc_name_count">0</span>/50</span>
                            </div>
                            <textarea name="rc_name" placeholder="공고제목을 입력해주세요" maxlength="50" rows="1" aria-required="true" required class="form-text form-text-bordered" id="rc_name_input"><?= htmlspecialchars(isset($info["rc_name"]) ? $info["rc_name"] : "") ?></textarea>
                        </div>
                    </div>
                    </div><!-- //step-section 1 -->

                    <!-- 단계 2: 주소등록, 공고노출지역, 채용공고기간 -->
                    <div class="step-section" data-step="2" style="display:none;">
                    <!-- 질문 섹션 -->

                    <!-- 주소 입력 전체 래퍼 -->
                    <div class="address-field-wrapper">
                        <!-- 주소등록 섹션 -->
                        <div>
                            <p class="form-label">근무지<span class="required-asterisk">*</span></p>
                            <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="address-add-btn address-add-btn-bordered js-toggle-panel" id="btnAddrAdd">
                                <span class="add-icon">+</span>
                                <span class="address-text">근무지 주소를 등록해주세요</span>
                            </a>
                        </div>

                        <!-- 공고노출지역 섹션 -->
                        <div style="margin-top: 1.5rem;">
                            <p class="form-label">추가 노출지역<em>근무지외 추가 노출지역을 선택해주세요</em></p>
                            <div class="expose-addr-group">
                                <!-- 노출지역 1 -->
                                <div class="address-btn-wrapper">
                                    <a href="#changeAreaExpose" role="button" aria-haspopup="dialog" aria-controls="changeAreaExpose" class="address-add-btn address-add-btn-bordered js-toggle-panel" id="btnExposeAddr1" data-index="1">
                                        <span class="add-icon">+</span>
                                        <span class="address-text"></span>
                                    </a>
                                    <button type="button" class="address-clear-btn js-clear-expose-addr" data-index="1" aria-label="노출지역 1 삭제">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <!-- 노출지역 2 -->
                                <div class="address-btn-wrapper">
                                    <a href="#changeAreaExpose" role="button" aria-haspopup="dialog" aria-controls="changeAreaExpose" class="address-add-btn address-add-btn-bordered js-toggle-panel" id="btnExposeAddr2" data-index="2">
                                        <span class="add-icon">+</span>
                                        <span class="address-text"></span>
                                    </a>
                                    <button type="button" class="address-clear-btn js-clear-expose-addr" data-index="2" aria-label="노출지역 2 삭제">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    </div><!-- //step-section 2 -->

                    <!-- 단계 3: 채용공고기간 -->
                    <div class="step-section" data-step="3" style="display:none;">
                    <!-- 질문 섹션 -->

                    <!-- 채용공고기간 래퍼 -->
                    <div class="date-field-wrapper">
                        <!-- 채용공고기간 섹션 -->
                        <div>
                            <p class="form-label">
                               공고기간 <span class="required-asterisk">*</span>
                              
                            </p>
                            <div class="datepicker-row">
                                <label class="datepicker-wrapper">
                                    <span class="sr-text">시작일 선택</span>
                                    <input type="text" name="rc_sdate" id="rc_sdate" aria-required="true" required class="form-text js-datepicker-start" readonly placeholder="2025.01.01" value="<?= isset($info['rc_sdate']) && $info['rc_sdate'] ? $info['rc_sdate'] : '' ?>">
                                </label>
                                <span class="datepicker-separator">~</span>
                                <label class="datepicker-wrapper">
                                    <span class="sr-text">종료일 선택</span>
                                    <input type="text" name="rc_edate" id="rc_edate" aria-required="true" required class="form-text js-datepicker-end" readonly placeholder="2025.03.01" value="<?= isset($info['rc_edate']) && $info['rc_edate'] ? $info['rc_edate'] : '' ?>">
                                </label>
                            </div>
                        </div>
                    </div>
                    </div><!-- //step-section 3 -->

                    <!-- 단계 4: 업무구분 -->
                    <div class="step-section" data-step="4" style="display:none;">
                    <!-- 질문 섹션 -->

                    <!-- 업무구분 래퍼 -->
                    <div class="work-field-wrapper">
                    <!-- 업무구분 섹션 -->
                    <div>
                        <p class="form-label">업무구분 <span class="required-asterisk">*</span></p>
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
                    </div><!-- //work-field-wrapper -->
                    </div><!-- //step-section 4 -->

                    <!-- 단계 5: 해시태그 -->
                    <div class="step-section" data-step="5" style="display:none;">
                    <!-- 질문 섹션 -->

                    <!-- 해시태그 래퍼 -->
                    <div class="hashtag-field-wrapper">
                    <!-- 해시태그 섹션 -->
                    <div class="form-row">
                        <p class="form-label">해시태그<span class="required-asterisk">*</span></p>
                        <div class="form-toggle-group col-3">
                            <?php
                            $default_hash_list = ["초보가능", "경력우대", "당일지급", "동반입사", "식사제공","가불가능"];
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
                                        <label class="form-toggle-btn2 sm tag is-custom">
                                            <input type="checkbox" name="rc_hash[]" value="<?= $hash ?>" checked>
                                            <span class="btn"><?= $hash ?></span>
                                            <button type="button" class="btn-tag-remove js-remove-hashtag" aria-label="<?= $hash ?> 삭제">×</button>
                                        </label>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                        <div class="hashtag-input-area">
                            <div class="input-row">
                                <input type="text" class="form-text js-hashtag-input" placeholder="해시태그를 직접입력해주세요" maxlength="20">
                                <button type="button" class="btn-confirm js-confirm-hashtag">확인</button>
                            </div>
                        </div>
                    </div>
                    </div><!-- //hashtag-field-wrapper -->
                    </div><!-- //step-section 5 -->

                    <!-- 단계 6: 근무형태 -->
                    <div class="step-section" data-step="6" style="display:none;">
                    <!-- 질문 섹션 -->

                    <!-- 근무형태 래퍼 -->
                    <div class="schedule-field-wrapper">
                    <!-- 근무형태 섹션 -->
                    <div class="form-row">
                        <p class="form-label form-label-with-checkbox">
                            <span>근무형태<span class="required-asterisk">*</span></span>
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
                                        $amStartH = (isset($info["rc_am_stime"]) && !empty($info["rc_am_stime"])) ? substr($info["rc_am_stime"], 0, 2) : "09"; //substr 시간 문자열에서 '시(Hour)'에 해당하는 앞 2글자만 싹둑 잘라
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
                    </div><!-- //schedule-field-wrapper -->
                    </div><!-- //step-section 6 -->

                    <!-- 단계 7: 급여형태 -->
                    <div class="step-section" data-step="7" style="display:none;">
                    <!-- 질문 섹션 -->

                    <!-- 급여형태 래퍼 -->
                    <div class="salary-field-wrapper">
                    <!-- 급여형태 섹션 -->
                    <div class="form-row">
                        <p class="form-label salary-label" style="display:block;">
                            급여형태<span class="required-asterisk">*</span>
                            <!-- <span class="min-wage-info">최저임금제도 : 시급 <span class="highlight">10,320</span> 원</span> -->
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
                    </div><!-- //salary-field-wrapper -->
                    </div><!-- //step-section 7 -->

                    <!-- 단계 8: 복리후생 (항목이 길어 질문문구가 밀리므로 상단 기준으로 스크롤) -->
                    <div class="step-section" data-step="8" data-scroll="top" style="display:none;">
                    <!-- 질문 섹션 -->

                    <!-- 복리후생 래퍼 -->
                    <div class="benefit-field-wrapper">
                    <!-- 복리후생 섹션 -->
                    <div class="form-row addition-info-form">
                        <p class="form-label">복리후생<span class="required-asterisk">*</span></p>

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
                    </div><!-- //benefit-field-wrapper -->
                    </div><!-- //step-section 8 -->

                    <!-- 단계 9: 이미지등록 -->
                    <div class="step-section" data-step="9" style="display:none;">
                    <!-- 질문 섹션 -->

                    <!-- 업무정보 섹션 (이미지등록) -->
                    <div class="form-section">
                        <div class="form-table">
                            <!-- 이미지등록 래퍼 -->
                            <div class="image-field-wrapper">
                            <!-- 이미지등록 -->
                            <div class="form-row">
                                <p class="form-label">이미지등록</p>

                                <!-- 메인사진 -->
                                <div class="form-pic-group-main">
                                    <label class="form-pic-main <?= isset($info["rc_img1"]) && $info["rc_img1"] != "" ? "has-image" : "" ?>">
                                        <span class="pic-badge-main">썸네일 이미지</span>
                                        <input type="file" name="rc_img1" id="rc_img1_input" accept="image/*" class="js-file-preview">
                                        <span class="preview">
                                            <?php if (isset($info["rc_img1"]) && $info["rc_img1"] != "") : ?>
                                                <img src="<?= $info["rc_img1"] ?>" />
                                            <?php endif; ?>
                                        </span>
                                        <div class="pic-icon-wrapper" style="top:37%;">
                                            <i class="fa fa-camera"></i>
                                        </div>
                                        <button type="button" class="btn-pic-remove" style="display:<?= isset($info["rc_img1"]) && $info["rc_img1"] != "" ? "inline-block" : "none" ?>;">×</button>
                                        <input type='hidden' name='rc_img1_ori' value='<?= isset($info["rc_img1"]) ? $info["rc_img1"] : "" ?>' />
                                        <!-- 즉시 업로드된 썸네일(md) 변형 URL 보관용 (파일 재전송 없이 서버에서 복원) -->
                                        <input type='hidden' name='rc_thumb_ori' value='<?= isset($info["rc_thumb"]) ? $info["rc_thumb"] : "" ?>' />
                                    </label>
                                    <div class="pic-guide-text">
                                        <p>등록된 썸네일 이미지는 채용공고 목록 썸네일 이미지로 노출됩니다.</p>
                                        <p>(미등록시, 업종별 이미지로 대체 됩니다.)</p>
                                    </div>
                                </div>

                                <!-- 상세페이지 3개 -->
                                <div class="form-pic-group-detail">
                                    <!-- 1번: 상세페이지1 -->
                                    <label class="form-pic-detail <?= isset($info["rc_detail_img1"]) && $info["rc_detail_img1"] != "" ? "has-image" : "" ?>" data-detail-slot="1">
                                        <span class="pic-badge-detail">상세페이지 이미지</span>
                                        <input type="file" accept="image/*" class="js-detail-photo-select">
                                        <span class="preview">
                                            <?php if (isset($info["rc_detail_img1"]) && $info["rc_detail_img1"] != "") : ?>
                                                <img src="<?= $info["rc_detail_img1"] ?>" />
                                            <?php endif; ?>
                                        </span>
                                        <div class="pic-icon-wrapper">
                                            <i class="fa fa-camera"></i>
                                        </div>
                                        <button type="button" class="btn-pic-remove" style="display:<?= isset($info["rc_detail_img1"]) && $info["rc_detail_img1"] != "" ? "inline-block" : "none" ?>;">×</button>
                                        <input type='hidden' name='rc_detail_img1' value='<?= isset($info["rc_detail_img1"]) ? $info["rc_detail_img1"] : "" ?>' />
                                        <input type='hidden' name='rc_detail_img1_ori' value='<?= isset($info["rc_detail_img1"]) ? $info["rc_detail_img1"] : "" ?>' />
                                    </label>

                                    <!-- 2번: 상세페이지2 -->
                                    <label class="form-pic-detail <?= isset($info["rc_detail_img2"]) && $info["rc_detail_img2"] != "" ? "has-image" : "" ?>" data-detail-slot="2">
                                        <span class="pic-badge-detail">상세페이지 이미지</span>
                                        <input type="file" accept="image/*" class="js-detail-photo-select">
                                        <span class="preview">
                                            <?php if (isset($info["rc_detail_img2"]) && $info["rc_detail_img2"] != "") : ?>
                                                <img src="<?= $info["rc_detail_img2"] ?>" />
                                            <?php endif; ?>
                                        </span>
                                        <div class="pic-icon-wrapper">
                                            <i class="fa fa-camera"></i>
                                        </div>
                                        <button type="button" class="btn-pic-remove" style="display:<?= isset($info["rc_detail_img2"]) && $info["rc_detail_img2"] != "" ? "inline-block" : "none" ?>;">×</button>
                                        <input type='hidden' name='rc_detail_img2' value='<?= isset($info["rc_detail_img2"]) ? $info["rc_detail_img2"] : "" ?>' />
                                        <input type='hidden' name='rc_detail_img2_ori' value='<?= isset($info["rc_detail_img2"]) ? $info["rc_detail_img2"] : "" ?>' />
                                    </label>

                                    <!-- 3번: 상세페이지3 -->
                                    <label class="form-pic-detail <?= isset($info["rc_detail_img3"]) && $info["rc_detail_img3"] != "" ? "has-image" : "" ?>" data-detail-slot="3">
                                        <span class="pic-badge-detail">상세페이지 이미지</span>
                                        <input type="file" accept="image/*" class="js-detail-photo-select">
                                        <span class="preview">
                                            <?php if (isset($info["rc_detail_img3"]) && $info["rc_detail_img3"] != "") : ?>
                                                <img src="<?= $info["rc_detail_img3"] ?>" />
                                            <?php endif; ?>
                                        </span>
                                        <div class="pic-icon-wrapper">
                                            <i class="fa fa-camera"></i>
                                        </div>
                                        <button type="button" class="btn-pic-remove" style="display:<?= isset($info["rc_detail_img3"]) && $info["rc_detail_img3"] != "" ? "inline-block" : "none" ?>;">×</button>
                                        <input type='hidden' name='rc_detail_img3' value='<?= isset($info["rc_detail_img3"]) ? $info["rc_detail_img3"] : "" ?>' />
                                        <input type='hidden' name='rc_detail_img3_ori' value='<?= isset($info["rc_detail_img3"]) ? $info["rc_detail_img3"] : "" ?>' />
                                    </label>
                                </div>
                            </div>
                            </div><!-- //image-field-wrapper -->
                        </div><!-- //form-table -->
                    </div><!-- //form-section 이미지등록 -->
                    </div><!-- //step-section 9 -->

                    <!-- 단계 10: 상세업무 (에디터가 길어 끝까지 스크롤) -->
                    <div class="step-section" data-step="10" data-scroll="bottom" style="display:none;">
                    <!-- 질문 섹션 -->

                    <!-- 업무정보 섹션 (상세업무) -->
                    <div class="form-section">
                        <div class="form-table">
                            <!-- 상세업무 래퍼 -->
                            <div class="description-field-wrapper">
                            <!-- 상세업무 -->
                            <div class="form-row">
                                <div class="resume-title-header">
                                    <p class="form-label">상세업무<span class="required-asterisk">*</span></p>
                                </div>
                                <label>
                                    <span class="sr-text">상세업무정보 입력</span>
                                    <?php
                                    // contenteditable div에 표시할 내용 (이미 HTML 형태)
                                    $desc = isset($info["rc_desc"]) ? $info["rc_desc"] : "";
                                    // 에디터 높이는 고정하고 넘치는 내용은 에디터 안에서 스크롤한다(overflow-y:auto)
                                    // 내용만큼 늘어나면 안드로이드 WebView가 탭할 때마다 에디터 박스 기준으로 .page를 강제 스크롤해
                                    // 캐럿이 topbar/푸터 밑으로 숨는다. 박스가 키보드 위 보이는 영역 안에 들어오면 그 강제 스크롤이 생기지 않는다
                                    ?>
                                    <div id="rc_desc_editor"
                                         contenteditable="true"
                                         class="form-textarea"
                                         style="font-size: 17px; height: 20.25rem; min-height: 20.25rem; box-sizing: border-box; overflow-y: auto; padding: 10px; border: 1px solid #ccc; border-radius: 4px; word-wrap: break-word;"><?= $desc ?>
                                    </div>
                                    <input type="hidden" id="uploadedPhotoUrl_mobile" name="uploadedPhotoUrl_mobile" value="">
                                    <input type="hidden" name="rc_desc" id="rc_desc_input" value="">
                                </label>
                            </div>
                            </div><!-- //description-field-wrapper -->
                        </div><!-- //form-table -->
                    </div><!-- //form-section 상세업무 -->
                    </div><!-- //step-section 10 -->

                    <!-- 단계 11: 선택항목(희망요일, 희망성별, 희망국적, 희망나이) -->
                    <div class="step-section" data-step="11" data-scroll="bottom" style="display:none;">
                    <!-- 선택항목 래퍼 -->
                    <div class="optional-field-wrapper">
                    <!-- 상세등록 섹션들 -->
                    <div class="form-row">
                        <p class="form-label form-label-with-checkbox">
                            <span>근무요일</span>
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

                    <div class="form-row">
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

                    <div class="form-row">
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

                    <div class="form-row">
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
                    </div><!-- //optional-field-wrapper -->
                    </div><!-- //step-section 11 -->

                    <div class="sticky-bottom view-footer">
                        <button id="btnStepPrev" type="button" style="display:none;">이전</button>
                        <button id="btnDetailMore" type="button" style="display:none;"><span class="add-icon">+</span>상세등록</button>
                        <button id="btnStepNext" type="button">다음</button>
                        <button id="btnPreview" type="button" style="display:none;">미리보기</button>
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
       
        border-radius: var(--write-border-radius);

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

    /* 섹션 레이아웃 */
    .form-section {
        margin-bottom: 40px;
        padding-bottom: 20px;
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .form-section-title {
        font-size:20.8px;
        font-weight:700;
        color: #222;
        padding-bottom: 12px;
       
        margin-top: 16px;
    }

    .form-section .form-table {
        padding: 0;
    }

    /* 상세업무 에디터 스크롤바: 안드로이드 WebView 기본 스크롤바는 스크롤 중에만 보이므로 항상 보이도록 지정
       (바텀시트 .layer-panel 스크롤바와 같은 모양) */
    #rc_desc_editor::-webkit-scrollbar {
        width: 8px;
    }

    #rc_desc_editor::-webkit-scrollbar-track {
        background-color: transparent;
    }

    #rc_desc_editor::-webkit-scrollbar-thumb {
        border-radius: 100px;
        background-color: #DBEFEA;
    }

    /* 단계별 진행 게이지바 */
    .step-progress-bar {
        position: fixed;
        top: 58px;
        left: 0;
        right: 0;
        z-index: 99;
        height: 7px;
        background: #eee;
        width: 100%;
    }

    .step-progress-fill {
        height: 100%;
        background: #0bbf7a;
        transition: width 0.3s ease;
        width: 9.09%; /* 1 / 전체 11단계 */
    }
    /* 단계 버튼 푸터: 단계와 무관하게 항상 화면 하단에 고정
       main.css의 .page-recruit-write .sticky-bottom.view-footer(relative/bottom:-75px)보다
       선택자 우선순위가 높아야 하므로 .section.addition-info-form을 포함해 지정 */
    .page-recruit-write .section.addition-info-form .sticky-bottom.view-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: auto;
        display: flex;
        justify-content: space-between;
        gap: 8px;
        align-items: center;
        padding: 1rem;
        background: #f9f7f7; /* 페이지 배경과 동일 — 스크롤되는 내용이 버튼 뒤로 비치지 않게 불투명 처리 */
        border-top: 0;
        z-index: 50;
    }

    /* 고정 푸터에 마지막 입력 항목이 가리지 않도록 하단 여백 확보 */
    .page-recruit-write .section.addition-info-form .container {
        padding-bottom: 5.5rem;
    }
    /* 상세업무(10단계)에서는 하단 여백을 없애 키보드 올릴 때 스크롤이 과하게 내려가지 않도록 한다 */
    .page-recruit-write[data-current-step="10"] .section.addition-info-form .container {
        /* padding-bottom: 3.0rem; */
    }

    #btnStepPrev {
        flex: 0 0 auto;
    }

    /* 이전 버튼이 숨겨지는 1단계에서도 오른쪽 정렬을 유지 */
    #btnStepNext, #btnPreview {
        flex: 0 0 auto;
        position: static;
        left: auto;
        margin-left: auto;
    }

    /* 이전 / 상세등록 버튼: 테두리 스타일 */
    #btnStepPrev,
    #btnDetailMore {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        width: 7.5rem;
        height: 4.0rem;
        white-space: nowrap;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        font-size: 1.3rem;
        background: #fff;
        color: #57CCAF;
        border: 1.5px solid #57CCAF;
        padding: 0;
        border-radius: 8px;
    }

    /* 상세등록이 보일 때(이전/상세등록/다음·미리보기 3개)는 auto 여백을 풀어 space-between 으로 간격 균등 */
    #btnDetailMore:not([style*="none"]) ~ #btnStepNext,
    #btnDetailMore:not([style*="none"]) ~ #btnPreview {
        margin-left: 0;
    }

    #btnStepPrev:active,
    #btnDetailMore:active {
        background: #f0f9f6;
    }

    #btnDetailMore {
        flex: 0 0 auto;
    }

    #btnDetailMore .add-icon {
        margin-right: 0.25rem;
    }

    /* 다음 버튼 */
    #btnStepNext {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        width: 7.5rem;
        height: 4.0rem;
        white-space: nowrap;
        font-weight: 700;
        color: #fff;
        text-align: center;
        vertical-align: middle;
        border: 0;
        background: #57CCAF;
        font-size: 1.3rem;
        padding: 0;
        border-radius: 8px;
    }

    #btnStepNext:active {
        background-color: #38b898;
    }

    /* 미리보기 버튼 */
    #btnPreview {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        width: 7.5rem;
        height: 4.0rem;
        white-space: nowrap;
        font-weight: 700;
        color: #fff;
        text-align: center;
        vertical-align: middle;
        border: 0;
        background: #57CCAF;
        font-size: 1.3rem;
        padding: 0;
        border-radius: 8px;
    }

    #btnPreview:active {
        background-color: #38b898;
    }

    /* 제출 진행 중: 연타 방지 (상태는 data-state, 표현은 CSS) */
    #btnPreview[data-state="submitting"],
    #btnSubmit[data-state="submitting"] {
        opacity: 0.5;
        pointer-events: none;
    }

    /* 단계별 UI 스타일 */
    .step-question {
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 1.5rem;
        margin-top: 0;
    }

    .step-question-main {
        font-size: 28px;
        color: #1B9D7D;
        font-weight: 600;
    }

    .step-question-sub {
        font-size: 21px;
        color: #333;
        font-weight: 600;
    }

    .label-with-counter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.8rem;
    }

    /* 주소 버튼 스타일 (.char-counter, .form-text-bordered 는 main.css 공통 규칙 사용) */
    .address-add-btn-bordered:hover {
        border-color: #bbb;
        background: #fafafa;
    }

    .address-add-btn-bordered:active {
        border-color: #57CCAF;
        background: #f0f9f6;
    }

    /* 업무구분 필드 래퍼 (카드 스타일은 main.css 공통 규칙) */
    .work-field-wrapper {
        margin-bottom: 1rem;
    }

    /* 근무형태 필드 래퍼 */
    .schedule-field-wrapper {
        margin-bottom: 1rem;
    }

    /* 복리후생 버튼 글자 크기: main.css의 .benefit-label.btn(1.0rem)에 덮이지 않도록 페이지·래퍼 클래스를 붙여 우선순위를 높인다 */
    .page-recruit-write .benefit-field-wrapper .benefit-label.btn {
        font-size: 0.9rem;
    }

    /* 이미지 필드 래퍼 */
    .image-field-wrapper {
        margin-bottom: 1rem;
    }

   .page-content .section.addition-info-form {
    background: #f9f7f7;
   }
  .section.addition-info-form .view-footer::after{
     content: "";
     display: none;
   }
   .step-section{
         margin-top: 28px;
   }
   /* 이미지 업로드 레이아웃 */
   .form-pic-group-main {
       margin-bottom: 1rem;
   }

   /* 파일 입력 숨기기 */
   .form-pic-main input[type="file"],
   .form-pic-detail input[type="file"] {
       display: none;
   }

   .form-pic-main {
       position: relative;
       display: flex;
       align-items: center;
       justify-content: center;
       width: 100%;
       height: 200px;
       background: #f5f5f5;
       border: 1px solid #e0e0e0;
       border-radius: 8px;
       cursor: pointer;
       overflow: hidden;
   }

   .form-pic-main.has-image {
       background: #fff;
   }

   .form-pic-main .pic-badge-main {
       position: absolute;
       top: 10px;
       left: 164px;
       background: #333;
       color: #fff;
       padding: 4px 8px;
       border-radius: 4px;
       font-size: 12px;
       font-weight: 600;
       z-index: 2;
   }

   .form-pic-main .preview {
       width: 100%;
       height: 100%;
       display: flex;
       align-items: center;
       justify-content: center;
   }

   .form-pic-main .preview img {
       width: 100%;
       height: 100%;
       object-fit: cover;
   }

   .form-pic-main .pic-icon-wrapper {
       position: absolute;
       top: 50%;
       left: 50%;
       transform: translate(-50%, -50%);
       width: 60px;
       height: 60px;
       background: #57CCAF;
       border-radius: 50%;
       display: flex;
       align-items: center;
       justify-content: center;
       color: #fff;
       font-size: 24px;
       z-index: 1;
   }

   .form-pic-main.has-image .pic-icon-wrapper {
       display: none;
   }

   /* 이미지 가이드 텍스트 */
   .pic-guide-text {
      
       padding: 0 0.5rem;
       color: #1B9D7D;
       position: absolute;
    top: 45%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    text-align: center;
   }

   .pic-guide-text p {
       margin: 0.3rem 0;
       font-size: 13px;

       line-height: 1.4;
   }

   /* 썸네일이 등록되면 안내문구를 숨긴다
      has-image는 JS(미리보기/삭제)와 PHP(기존 이미지) 양쪽에서 붙으므로 CSS로만 처리 */
   .form-pic-main.has-image + .pic-guide-text {
       display: none;
   }

   /* 사진이 등록되면 썸네일/상세페이지 뱃지를 숨긴다 (has-image 기준, 위 안내문구와 동일 방식) */
   .form-pic-main.has-image .pic-badge-main,
   .form-pic-detail.has-image .pic-badge-detail {
       display: none;
   }

   /* 상세페이지 이미지 그리드 */
   .form-pic-group-detail {
       display: grid;
       grid-template-columns: repeat(3, 1fr);
       gap: 1rem;
   }

   .form-pic-detail {
       position: relative;
       display: flex;
       flex-direction: column;
       align-items: center;
       justify-content: center;
       aspect-ratio: 1 / 1;
       background: #f5f5f5;
       border: 1px solid #e0e0e0;
       border-radius: 8px;
       cursor: pointer;
       overflow: hidden;
   }

   .form-pic-detail.has-image {
       background: #fff;
   }

   .form-pic-detail .pic-badge-detail {
       position: absolute;
       top: 8px;
       left: 19px;
       background: #333;
       color: #fff;
       padding: 3px 6px;
       border-radius: 4px;
       font-size: 11px;
       font-weight: 600;
       z-index: 2;
   }

   .form-pic-detail .preview {
       width: 100%;
       height: 100%;
       display: flex;
       align-items: center;
       justify-content: center;
   }

   .form-pic-detail .preview img {
       width: 100%;
       height: 100%;
       object-fit: cover;
   }

   .form-pic-detail .pic-icon-wrapper {
       position: absolute;
       top: 50%;
       left: 50%;
       transform: translate(-50%, -50%);
       width: 50px;
       height: 50px;
       background: #57CCAF;
       border-radius: 50%;
       display: flex;
       align-items: center;
       justify-content: center;
       color: #fff;
       font-size: 20px;
       z-index: 1;
   }

   .form-pic-detail.has-image .pic-icon-wrapper {
       display: none;
   }

   /* 사진 삭제(X) 버튼 - 메인/상세 공통, 박스 우측 하단 원형
      main.css의 옛 규칙(.form-pic-group .form-pic .btn-pic-remove)은
      클래스명이 바뀌어 더 이상 매칭되지 않으므로 여기서 다시 정의한다.
      display는 인라인 style/jQuery show()가 제어하므로 지정하지 않는다. */
   .form-pic-main .btn-pic-remove,
   .form-pic-detail .btn-pic-remove {
       position: absolute;
       right: 6px;
       bottom: 6px;
       z-index: 3;
       width: 1.9rem;
       height: 1.9rem;
       padding: 0;
       border: none;
       border-radius: 50%;
       background-color: rgba(0, 0, 0, 0.6);
       color: #fff;
       font-size: 1rem;
       line-height: 1.9rem; /* height와 같게 두어 × 를 세로 가운데로 */
       text-align: center;
       cursor: pointer;
   }

   .form-pic-main .btn-pic-remove:active,
   .form-pic-detail .btn-pic-remove:active {
       background-color: rgba(0, 0, 0, 0.8);
   }

   /* 직접입력 해시태그의 삭제(x) 버튼: 태그 우측 상단에 붙는다 */
   .form-toggle-btn2.is-custom {
       position: relative;
   }

   .btn-tag-remove {
       position: absolute;
       top: -6px;
       right: -6px;
       width: 20px;
       height: 20px;
       padding: 0;
       border: none;
       border-radius: 50%;
       background: #666;
       color: #fff;
       font-size: 13px;
       line-height: 20px;
       text-align: center;
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
    // 복사 모드(rc_idx=0)에서도 addr 데이터가 있으면 serverData 설정
    var serverData = <?= (isset($info["rc_idx"]) && ($info["rc_idx"] != 0 || isset($info["addr"]))) ? json_encode($info_for_js, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) : 'null' ?>;
    var serverAddrData = serverData && serverData.addr ? serverData.addr : null;
    var serverAddrData1 = serverData && serverData.expose_addr1 ? serverData.expose_addr1 : null;
    var serverAddrData2 = serverData && serverData.expose_addr2 ? serverData.expose_addr2 : null;
    var detailSectionScrollPosition = 0;

    // PHP에서 rc_idx를 JavaScript 변수로 전달
    var rcIdx = '<?= isset($info["rc_idx"]) ? $info["rc_idx"] : "0" ?>';
    var origRcIdx = '<?= isset($orig_rc_idx) ? $orig_rc_idx : "0" ?>';
    var gbnMode = '<?= isset($gbn) ? $gbn : "" ?>';

    // ============================================================================
    // 단계별 진행 관련 변수 및 함수
    // ============================================================================
    let currentStep = 1;
    const TOTAL_STEPS = 11;
    // 1~10 은 필수, 11(근무요일·희망성별·희망국적·희망나이)은 "상세등록" 선택 구간
    const REQUIRED_STEPS = 10;
    // 상세등록 구간을 열었는지 여부. 전체 단계 수가 10 ↔ 11 로 달라진다
    let isDetailOpen = false;
    // 미리보기에서 돌아왔는지 여부.
    // DOMContentLoaded 핸들러가 from_preview 플래그를 지우기 전에 먼저 읽어 둬야 하므로
    // 인라인 스크립트가 파싱되는 이 시점에 확보한다
    const IS_RETURN_FROM_PREVIEW = (function() {
        try {
            return sessionStorage.getItem('from_preview') === 'true';
        } catch (e) {
            return false;
        }
    }());
    // 단계 이동 시 topbar(진행바 포함) 아래끝과 새 단계 -field-wrapper 윗면 사이 여백 (아주 살짝 띄움)
    const STEP_WRAPPER_GAP = 8;
    // 단계 마지막 항목 아래와 고정 푸터 사이 여백
    const STEP_BOTTOM_GAP = 12;
    // 주소 팝업이 닫히고 body overflow가 복원된 뒤 스크롤하기 위한 지연
    const ADDR_SCROLL_DELAY = 150;
    // 자동 이동으로 단계가 열린 뒤 스크롤하기까지의 대기 (키보드가 내려가고 viewport가 안정될 시간, ms)
    const AUTO_STEP_SCROLL_DELAY = 300;

    // 상세등록을 열기 전에는 10단계가 끝, 열면 11단계가 끝
    function getTotalSteps() {
        return isDetailOpen ? TOTAL_STEPS : REQUIRED_STEPS;
    }

    // 상세업무가 입력되어야 상세등록 버튼을 노출한다
    function isDescFilled() {
        let editor = document.getElementById('rc_desc_editor');
        return editor ? editor.innerHTML.trim() !== "" : false;
    }

    function updateProgressBar() {
        let progress = (currentStep / getTotalSteps()) * 100;
        if (progress > 100) {
            progress = 100;
        }
        $("#stepProgressFill").css("width", progress + "%");
    }

    // 미리보기 복귀 시 스크롤을 맨 위(첫 단계)로 올린다.
    // 이력서 등록 폼과 동작을 맞추기 위한 것으로, 복귀 후 처음부터 훑어보며 수정하게 한다
    function scrollPageTop() {
        let $page = $(".page");
        if ($page.length > 0) {
            $page.stop(true).scrollTop(0);
        }
    }

    // skipAnimation: 미리보기 복귀처럼 단계 이동이 아닐 때는 자동 스크롤을 생략한다
    function updateStepVisibility(skipAnimation) {
        // 현재 단계 번호를 페이지에 남겨 CSS에서 단계별 여백을 나눌 수 있게 한다 (data-current-step)
        $(".page-recruit-write").attr("data-current-step", currentStep);
        // 단계 이동 중에는 현재 단계 항목만 보이고, 미리보기 복귀·수정 모드(skipAnimation)에서는 전체 항목을 펼친다
        var visibleSteps = getTotalSteps();
        for (let i = 1; i <= TOTAL_STEPS; i++) {
            let $section = $(".step-section[data-step='" + i + "']");
            var isVisible = skipAnimation ? i <= visibleSteps : i === currentStep;
            if (isVisible) {
                $section.show();
            } else {
                $section.hide();
            }
        }
        // 단계 이동 스크롤은 섹션 표시 직후 한 번만 한다 (미리보기 복귀 등 skipAnimation 이면 생략)
        if (currentStep <= TOTAL_STEPS && !skipAnimation) {
            // 자동 이동이면 키보드가 내려가고 viewport 가 안정될 때까지 잠깐 기다린 뒤 스크롤한다 (goNextStep 쪽에서 플래그를 켠다)
            if (window.__autoAdvanceScroll) {
                setTimeout(scrollToCurrentStep, AUTO_STEP_SCROLL_DELAY);
            } else {
                scrollToCurrentStep();
            }
        }
        updateProgressBar();

        // 버튼 업데이트: 1단계에서는 "이전" 숨김, 마지막 단계에서는 "다음" 숨기고 "미리보기" 노출
        if (currentStep === 1) {
            $("#btnStepPrev").hide();
        } else {
            $("#btnStepPrev").show();
        }

        updateDetailMoreButton();

        if (currentStep >= getTotalSteps()) {
            $("#btnStepNext").hide();
            $("#btnPreview").show();
        } else {
            $("#btnStepNext").show();
            $("#btnPreview").hide();
        }

    }

    // 상세등록(11단계) 항목이 모두 기본값인지 판정한다
    // 기본값: 희망요일 월~금, 성별무관, 국적무관, 나이무관 (아래 기본 체크 IIFE들과 같은 기준)
    function isDetailDefault() {
        var weekdayDefaults = ["월", "화", "수", "목", "금"];
        var checkedDays = $("[name='rc_day[]']:checked").map(function() {
            return $(this).val();
        }).get();
        var isDayDefault = checkedDays.length === weekdayDefaults.length && weekdayDefaults.every(function(day) {
            return checkedDays.indexOf(day) !== -1;
        });
        var isSexDefault = $("[name='rc_sex_all']").is(":checked");
        var isNationDefault = $("[name='rc_nation_all']").is(":checked");
        var isAgeDefault = $("[name='rc_age_all']").is(":checked");
        return isDayDefault && isSexDefault && isNationDefault && isAgeDefault;
    }

    // 상세등록 버튼만 갱신한다. 섹션 표시는 건드리지 않으므로 글자 입력 중에 호출해도 화면 위치가 밀리지 않는다
    // 마지막 필수 단계(10)에서 상세업무를 입력했고, 아직 열지 않았을 때만 노출
    function updateDetailMoreButton() {
        if (currentStep === REQUIRED_STEPS && !isDetailOpen && isDescFilled()) {
            $("#btnDetailMore").show();
        } else {
            $("#btnDetailMore").hide();
        }
    }

    // 고정 푸터(.sticky-bottom.view-footer) 윗면의 화면 좌표
    // 푸터가 없으면 스크롤 영역(.page)의 아래끝을 기준으로 삼는다
    function getFooterTop() {
        var $footer = $(".sticky-bottom.view-footer");
        if ($footer.length) {
            return $footer[0].getBoundingClientRect().top;
        }
        var $page = $(".page");
        return $page.length ? $page[0].getBoundingClientRect().bottom : $(window).height();
    }

    // 단계 이동 스크롤 헬퍼: 이동 중에는 window.__stepScrolling을 켜서 main.js의 캐럿 보정 스크롤과 겹치지 않게 한다
    // 끝 감지는 scrollend 이벤트를 쓰고, 미지원이거나 이벤트가 오지 않으면 안전 타이머로 내린다
    var stepScrollSafetyTimer = null;
    var STEP_SCROLL_MAX_MS = 1000; // 안전 타이머 최대 대기 (ms)
    function endStepScrollFlag() {
        if (stepScrollSafetyTimer) {
            clearTimeout(stepScrollSafetyTimer);
            stepScrollSafetyTimer = null;
        }
        window.__stepScrolling = false;
    }
    // 단계 이동은 브라우저 네이티브 smooth 스크롤로 한다
    // jQuery animate는 프레임마다 scrollTop을 세팅해 CSS smooth와 충돌하므로 쓰지 않는다
    // duration은 기존 호출부와의 호환을 위해 남겨 두며, 실제 시간은 브라우저가 거리에 맞춰 정한다
    function animateStepScroll($page, scrollTop, duration) {
        var page = $page[0];
        if (!page) {
            return;
        }
        // 이동할 거리가 없으면 scrollend 가 오지 않으므로 플래그를 켜지 않는다 (켜두면 보정이 막힌 채 남음)
        if (Math.abs(scrollTop - page.scrollTop) < 2) {
            return;
        }
        window.__stepScrolling = true;
        if (stepScrollSafetyTimer) {
            clearTimeout(stepScrollSafetyTimer);
        }
        stepScrollSafetyTimer = setTimeout(endStepScrollFlag, STEP_SCROLL_MAX_MS);
        page.addEventListener('scrollend', endStepScrollFlag, { once: true });
        if (typeof page.scrollTo === 'function') {
            page.scrollTo({ top: scrollTop, behavior: 'smooth' });
        } else {
            page.scrollTop = scrollTop;
            endStepScrollFlag();
        }
    }

    // 지정한 요소의 아래쪽이 고정 푸터 위로 올라오도록 스크롤을 내린다
    // 이미 푸터 위에 보이는 경우에는 움직이지 않는다(위로 되감기 방지)
    function scrollElementAboveFooter($el) {
        var $page = $(".page");
        if ($page.length === 0 || !$el || $el.length === 0 || !$el.is(":visible")) {
            return;
        }
        // getBoundingClientRect: 스크롤 위치와 상관없이 화면 좌표를 반환하므로, .page 기준으로 환산해야 한다
        // 요소가 현재 화면에서 어디에 있는지 확인하고, 고정 푸터 위로 올라오도록 스크롤을 내린다
        var delta = $el[0].getBoundingClientRect().bottom - getFooterTop() + STEP_BOTTOM_GAP;
        if (delta <= 0) {
            return;
        }

        animateStepScroll($page, $page.scrollTop() + delta, 300);
    }

    // 주소가 채워지면 .has-address가 붙어 버튼 높이가 늘어난다(긴 주소는 두 줄로 감김).
    // 늘어난 만큼 해당 항목이 고정 푸터에 가리지 않도록 스크롤을 맞춘다.
    // 팝업이 닫히며 body overflow가 복원되는 것을 기다렸다가 실행한다.
    function scrollAfterAddrFilled($btn) {
        if (!$btn || $btn.length === 0) {
            return;
        }
        setTimeout(function() {
            scrollElementAboveFooter($btn.closest('[class*="-field-wrapper"]'));
        }, ADDR_SCROLL_DELAY);
    }

    // 새로 노출된 단계로 스크롤 (다음 버튼, 자동 이동 공통)
    // 새 단계의 첫 -field-wrapper 윗면이 topbar(진행바 포함) 아래끝에서 STEP_WRAPPER_GAP만큼 떨어지도록 맞춘다
    // 상단 가림 높이는 고정값 대신 실제 요소 위치로 잰다
    // 스크롤 컨테이너는 .page (overflow-y:auto) 이므로 .page의 scrollTop 기준으로 환산한다
    function scrollToCurrentStep() {
        var $page = $(".page");
        var $section = $(".step-section[data-step='" + currentStep + "']");
        if ($page.length === 0 || $section.length === 0) {
            return;
        }

        var $wrapper = $section.find('[class*="-field-wrapper"]').first();
        var $target = $wrapper.length ? $wrapper : $section;

        // 화면 위쪽을 덮는 영역의 아래끝: topbar와 고정 진행바 중 더 아래쪽
        var coverBottom = $page[0].getBoundingClientRect().top;
        $(".top-bar, .step-progress-bar").each(function() {
            var bottom = this.getBoundingClientRect().bottom;
            if (bottom > coverBottom) {
                coverBottom = bottom;
            }
        });

        var targetTop = $target[0].getBoundingClientRect().top;
        var scrollTop = $page.scrollTop() + (targetTop - coverBottom) - STEP_WRAPPER_GAP;

        // 스크롤 범위 제한 (내용이 짧아 끝까지 못 올리는 경우는 최대 스크롤까지만)
        var maxScroll = $page[0].scrollHeight - $page[0].clientHeight;
        scrollTop = Math.max(0, Math.min(scrollTop, maxScroll));

        animateStepScroll($page, scrollTop, 120);
    }

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

    // 급여 금액 입력: 숫자만 받고 입력 즉시 천 단위 쉼표를 표시 (커서 위치 보정 포함)
    // DOMContentLoaded 초기화 중 예외가 나도 동작하도록 document.ready에서 별도로 바인딩
    function bindSalaryAmountFormat() {
        $("#rc_price_amount").off("input.amountFormat").on("input.amountFormat", function() {
            var el = this;
            var digitsBeforeCaret = el.value.slice(0, el.selectionStart).replace(/[^0-9]/g, '').length;
            var numeric = el.value.replace(/[^0-9]/g, '');
            if (numeric !== '') {
                numeric = String(parseInt(numeric, 10));
            }
            var formatted = formatNumberWithComma(numeric);
            $(el).val(formatted);

            // 쉼표가 추가/제거되어도 입력 중이던 자리 뒤로 커서를 맞춤
            var pos = 0;
            var count = 0;
            while (pos < formatted.length && count < digitsBeforeCaret) {
                if (/[0-9]/.test(formatted.charAt(pos))) {
                    count++;
                }
                pos++;
            }
            el.setSelectionRange(pos, pos);
        });
    }

    // contenteditable 에디터 DOM을 정규화: 블록 요소(<div>, <p>)를 <br>로 변환해 줄바꿈 보존
    // 이미지 래퍼(.image-wrapper)와 인라인 서식(<b>, <span> 등)은 유지
    function normalizeEditorDom(editor) {
        if (!editor) return;

        // 재귀 헬퍼: 노드의 자식들을 순회하며 블록 요소 평탄화
        function flattenBlocks(node) {
            var children = Array.prototype.slice.call(node.childNodes); // 배열로 변환
            /**children = [
                <div>첫째 줄</div>,
                <div><br></div>,
                <div>둘째 줄</div>
                ] */
            var insertBrBefore = false; // <br>을 앞에 넣을 것인가?

            for (var i = 0; i < children.length; i++) {
                var child = children[i];

                // 텍스트 노드: 그대로 두기
                if (child.nodeType === 3) { // TEXT_NODE 텍스트 노드 처리 노드 종류를 숫자로 구분
                    insertBrBefore = false;
                    continue;
                }

                // DIV/P 블록 요소 처리
                if (child.nodeType === 1 && (child.tagName === 'DIV' || child.tagName === 'P')) {
                    // 이미지 래퍼는 건드리지 않음
                    if (child.classList.contains('image-wrapper') || child.querySelector('img')) {
                        insertBrBefore = false;
                        continue;
                    }

                    // 첫 번째 블록이 아니면 <br> 삽입
                    if (insertBrBefore && i > 0) {
                        var br = document.createElement('br');
                        node.insertBefore(br, child);
                    }

                    // 블록 내부의 자식을 재귀적으로 평탄화
                    flattenBlocks(child);

                    // 블록 내용을 부모에 펼치기
                    while (child.firstChild) {
                        node.insertBefore(child.firstChild, child);
                    }
                    node.removeChild(child);
                    insertBrBefore = true;
                } else if (child.nodeType === 1) {
                    // 다른 요소(br, span, img 등): 재귀 처리
                    flattenBlocks(child);
                    insertBrBefore = false;
                }
            }
        }

        flattenBlocks(editor);
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
            var saved = sessionStorage.getItem(oldKey);
            if (saved && !sessionStorage.getItem(newKey)) {
                sessionStorage.setItem(newKey, saved);
            }
            // 이전 키는 sessionStorage/localStorage 모두에서 제거 (남으면 다음 신규 작성 시 복원됨)
            sessionStorage.removeItem(oldKey);
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
    // 공고제목, 근무지역, 업무구분 ,근무형태 ,근무시간, 급여정보 등 1페이지 입력값을 수집
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

        sessionStorage.setItem(storageKey, JSON.stringify(formData));
    }
    // 2페이지 입력값을 수집하여 sessionStorage에 임시저장(복리후생)
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

        sessionStorage.setItem(storageKey, JSON.stringify(formData));
    }

    // 해시태그를 쉼표로 구분된 문자열로 합성하여 sessionStorage에 저장
    // 상세설명,해시태그,채용기간,희망요일,희망성별,희망국적,희망나이 등 3페이지 입력값을 수집
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

        // contenteditable div에서 내용 가져오기 (원본 HTML 그대로 저장)
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
            detail_section_visible: $(".js-detail-section").hasClass("show"),
            // 메인사진은 즉시 업로드된 URL이 숨은칸에만 있어서, 백업하지 않으면
            // 미리보기에서 새로고침으로 돌아왔을 때 사라진다 (상세사진은 rc_desc HTML에 포함됨)
            rc_img1_ori: $("[name='rc_img1_ori']").val(),
            rc_thumb_ori: $("[name='rc_thumb_ori']").val(),
            rc_detail_img1: $("[name='rc_detail_img1']").val(),
            rc_detail_img2: $("[name='rc_detail_img2']").val(),
            rc_detail_img3: $("[name='rc_detail_img3']").val()
        };

        sessionStorage.setItem(storageKey, JSON.stringify(formData));
    }
// 페이지 새로고침 후 sessionStorage에 저장된 데이터를 다시 폼에 채워 넣는 함수 (페이지 구분을 위해 page1, page2, page3 suffix 추가)
    function loadPage1Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var formData = null;

        // draft 로드: shouldLoadDraft가 true일 때만 draft를 먼저 확인
        if (window.shouldLoadDraft) {
            var storageKey = "recruit_draft_" + rcIdx + "_page1";
            var draftSaved = sessionStorage.getItem(storageKey);

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
        // 처음 작성시(rc_idx=0)에는 serverData 무시 → 주소 표시 안 함
        // 복사 모드(gbn=copy)일 때만 serverData 사용
        if (!formData && typeof serverData !== 'undefined' && serverData !== null && (
            (serverData.rc_idx && serverData.rc_idx != "0") ||
            (gbnMode === "copy" && serverData.addr && serverData.addr.ad_addr)
        )) {
            formData = serverData;
        } else if (!formData) {
            // draft도 없고 serverData도 없으면 종료
            return;
        }

        if (typeof formData.rc_name !== "undefined") {
            $("[name='rc_name']").val(formData.rc_name);
            // 복원된 값도 길이 제한/높이 조절 핸들러를 거치도록 트리거 (핸들러 바인딩 전이면 무시됨)
            $("#rc_name_input").trigger("input");
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
        // 화면 표시는 지번주소(ad_jibun)를 우선 쓰고, 지번이 없을 때만 축약 주소(ad_addr)로 대체한다 (선택 직후 표시와 같은 기준)
        var rowAddr = formData.ad_addr || (formData.addr && formData.addr.ad_addr);
        var rowJibun = formData.ad_jibun || (formData.addr && formData.addr.ad_jibun);
        var rowDetail = formData.ad_detail || (formData.addr && formData.addr.ad_detail);
        if (rowAddr) {
            $("#ad_addr").val(rowAddr);
            var displayAddr = rowJibun || rowAddr;
            if (rowDetail) {
                displayAddr += " " + rowDetail;
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

        // 급여협의는 금액이 0원으로 고정되므로 임시저장된 금액을 복원하지 않는다
        if (formData.rc_price_amount && formData.rc_price_type_main !== "급여협의") {
            // 임시저장값은 쉼표 없이 저장되므로 복원 시 다시 포맷
            $("#rc_price_amount").val(formatNumberWithComma(formData.rc_price_amount));
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
            var draftSaved = sessionStorage.getItem(storageKey);

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
        // 복사 모드(gbn=copy)에서도 serverData 사용
        if (!formData && typeof serverData !== 'undefined' && serverData !== null && (serverData.rc_idx || serverData.rc_life_type)) {
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

    // 임시저장(draft)에 백업해둔 이미지 URL을 폼에 되돌린다.
    // 메인사진은 숨은칸(rc_img1_ori)에만 URL이 있어서, 페이지가 새로고침되면
    // 서버값(아직 DB에 없으므로 빈 값)으로 덮어써져 사라진다. 그 복구용.
    function restoreDraftImages(formData) {
        if (!formData) {
            return;
        }

        // 메인사진: URL 복구 + 미리보기 이미지와 X버튼까지 되살림
        if (formData.rc_img1_ori) {
            var $mainLabel = $(".form-pic-main");
            $mainLabel.find("[name='rc_img1_ori']").val(formData.rc_img1_ori);

            if ($mainLabel.find(".preview img").length === 0) {
                $mainLabel.find(".preview").html('<img src="' + formData.rc_img1_ori + '" />');
            }
            $mainLabel.addClass("has-image");
            $mainLabel.find(".btn-pic-remove").show();
        }

        if (formData.rc_thumb_ori) {
            $("[name='rc_thumb_ori']").val(formData.rc_thumb_ori);
        }

        // 상세사진: hidden 값 복구 + 해당 슬롯 박스 상태 되살림
        for (var slot = 1; slot <= 3; slot++) {
            var detailUrl = formData["rc_detail_img" + slot];
            if (!detailUrl) {
                continue;
            }

            $("[name='rc_detail_img" + slot + "']").val(detailUrl);

            var $slotLabel = $(".form-pic-detail[data-detail-slot='" + slot + "']");
            if ($slotLabel.length === 0) {
                continue;
            }
            if ($slotLabel.find(".preview img").length === 0) {
                $slotLabel.find(".preview").html('<img src="' + detailUrl + '" />');
            }
            $slotLabel.addClass("has-image");
            $slotLabel.find(".btn-pic-remove").show();
        }
    }

    function loadPage3Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var formData = null;
        var isServerData = false;  // serverData 로드 여부 확인

        // draft 로드: shouldLoadDraft가 true일 때만 draft를 먼저 확인
        if (window.shouldLoadDraft) {
            var storageKey = "recruit_draft_" + rcIdx + "_page3";
            var draftSaved = sessionStorage.getItem(storageKey);

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
        // 복사 모드(gbn=copy)에서도 serverData 사용
        if (!formData && typeof serverData !== 'undefined' && serverData !== null && (serverData.rc_idx || serverData.rc_desc)) {
            formData = serverData;
            isServerData = true;  // serverData를 사용했음을 기록
        }

        // formData가 없으면 (새 공고) flatpickr만 초기화하고 종료
        if (!formData) {
            // flatpickr 초기화 완료 후 setDate 다시 확인
            setTimeout(function() {
                if (window.pickerInstances && window.pickerInstances.startPicker && window.pickerInstances.endPicker) {
                    var today = new Date();
                    // 오늘 날짜를 Y-m-d 포맷으로 변환  '2026-08-25'
                    var todayStr = today.getFullYear() + '-' + (today.getMonth() + 1).toString().padStart(2, '0') + '-' + today.getDate().toString().padStart(2, '0');
                    // 종료일은 오늘 기준 2개월 후로 설정
                    var endDate = new Date(today);
                    endDate.setMonth(endDate.getMonth() + 2);
                    var endDateStr = endDate.getFullYear() + '-' + (endDate.getMonth() + 1).toString().padStart(2, '0') + '-' + endDate.getDate().toString().padStart(2, '0');

                    window.pickerInstances.startPicker.setDate(todayStr, true);
                    window.pickerInstances.endPicker.setDate(endDateStr, true);

                    // input value도 직접 설정 (타임존 안전)
                    var startFormatted = today.getFullYear() + '.' + (today.getMonth() + 1).toString().padStart(2, '0') + '.' + today.getDate().toString().padStart(2, '0');
                    var endFormatted = endDate.getFullYear() + '.' + (endDate.getMonth() + 1).toString().padStart(2, '0') + '.' + endDate.getDate().toString().padStart(2, '0');

                    var sDateInput = document.querySelector('#rc_sdate');
                    var eDateInput = document.querySelector('#rc_edate');
                    if (sDateInput) {
                        sDateInput.value = todayStr;  // Y-m-d 포맷
                        var sAltInput = sDateInput.nextElementSibling;
                        if (sAltInput && sAltInput.tagName === 'INPUT') {
                            sAltInput.value = startFormatted;
                        }
                    }
                    if (eDateInput) {
                        eDateInput.value = endDateStr;  // Y-m-d 포맷
                        var eAltInput = eDateInput.nextElementSibling;
                        if (eAltInput && eAltInput.tagName === 'INPUT') {
                            eAltInput.value = endFormatted;
                        }
                    }
                }
            }, 100);
            return;
        }

        try {

            if (typeof formData.rc_desc !== "undefined") {
                // 원본 HTML을 contenteditable div에 복구
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

            // 메인사진/상세사진 URL 복구
            // 빈 값으로는 덮어쓰지 않는다 (백업에 없는데 지우면 기존 사진이 날아감)
            restoreDraftImages(formData);

            $("[name='rc_hash[]']").prop("checked", false);
            if (Array.isArray(formData.rc_hash) && formData.rc_hash.length > 0) {
                formData.rc_hash.forEach(function(val) {
                    var $checkbox = $("[name='rc_hash[]'][value='" + val + "']");
                    if ($checkbox.length === 0) {
                        var $newLabel = $('<label class="form-toggle-btn2 sm tag is-custom">' +
                            '<input type="checkbox" name="rc_hash[]" value="' + val + '" checked>' +
                            '<span class="btn">' + val + '</span>' +
                            '<button type="button" class="btn-tag-remove js-remove-hashtag" aria-label="' + val + ' 삭제">×</button>' +
                            '</label>');
                        $(".hashtag-field-wrapper .form-toggle-group").append($newLabel);
                    } else {
                        $checkbox.prop("checked", true);
                    }
                });
            }

            setTimeout(function() {
                // 사용자가 선택한 기존 데이터가 있으면 복원
                if (formData.rc_sdate && window.pickerInstances && window.pickerInstances.startPicker) {
                    window.pickerInstances.startPicker.setDate(formData.rc_sdate, false);

                    var sParts = formData.rc_sdate.split('-');
                    if (sParts.length === 3) {
                        var sFormatted = sParts[0] + '.' + sParts[1] + '.' + sParts[2];
                        var sInput = document.querySelector('#rc_sdate');
                        if (sInput) {
                            sInput.value = formData.rc_sdate;  // 원본 input: 대시 포맷 Y-m-d
                            var sAltInput = sInput.nextElementSibling;
                            if (sAltInput && sAltInput.tagName === 'INPUT') {
                                sAltInput.value = sFormatted;  // altInput: 점 포맷 Y.m.d
                            }
                        }
                    }
                }
                if (formData.rc_edate && window.pickerInstances && window.pickerInstances.endPicker) {
                    window.pickerInstances.endPicker.setDate(formData.rc_edate, false);

                    var eParts = formData.rc_edate.split('-'); //"2026-08-20"->  eParts = ["2026", "08", "20"]
                    if (eParts.length === 3) {
                        var eFormatted = eParts[0] + '.' + eParts[1] + '.' + eParts[2];
                        var eInput = document.querySelector('#rc_edate');
                        if (eInput) {
                            eInput.value = formData.rc_edate;  // 원본 input: 대시 포맷 Y-m-d
                            var eAltInput = eInput.nextElementSibling;
                            if (eAltInput && eAltInput.tagName === 'INPUT') {
                                eAltInput.value = eFormatted;  // altInput: 점 포맷 Y.m.d
                            }
                        }
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
        // draft는 sessionStorage에 저장되므로 sessionStorage도 함께 정리
        ["1", "2", "3"].forEach(function(page) {
            sessionStorage.removeItem("recruit_draft_" + rcIdx + "_page" + page);
            localStorage.removeItem("recruit_draft_" + rcIdx + "_page" + page);
        });
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
            // 급여협의는 금액이 0원으로 고정되므로 금액 칸에 0을 채우고 입력을 막는다
            $salaryAmountInput.val("0").prop("disabled", true);
            return;
        }

        // 급여협의에서 바꾼 경우 자동으로 채워진 0은 지워서 직접 입력하도록 한다
        if ($salaryAmountInput.val() === "0") {
            $salaryAmountInput.val("");
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
        var addr_short = (typeof fRemoveBigAdrJS === 'function') ? (fRemoveBigAdrJS(jibun) || jibun) : jibun;

        $("#ad_zipcode").val(zipcode);
        $("#ad_jibun").val(jibun);
        $("#ad_doro").val(doro);
        $("#ad_addr").val(addr_short);
        $("#ad_detail").val(detail);
        $("#ad_gps_x").val(x);
        $("#ad_gps_y").val(y);

        var $btnAddr = $("#btnAddrAdd");
        var displayAddr = jibun;
        if (detail && detail.trim() !== "") {
            displayAddr = jibun + " " + detail.trim();
        }
        $btnAddr.find(".address-text").text(displayAddr);
        $btnAddr.addClass("has-address");

        $("#changeArea").removeClass("on");
        savePage1Data();
        scrollAfterAddrFilled($btnAddr);
        // 근무지 + 추가노출지역 1,2가 모두 채워지면 다음 단계로 자동 이동
        if (typeof window.autoAdvanceAddrIfComplete === 'function') {
          window.autoAdvanceAddrIfComplete();
        }
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
    // 단계별 필수값 검사 함수들 (단계 번호는 txt 위자드 기준)
    // 1단계: 공고제목
    function checkRequiredTitle() {
        if ($("[name='rc_name']").val() == "") {
            commonAlert("공고제목을 입력해주세요.", function() {
                $("[name='rc_name']").focus();
            });
            return false;
        }
        return true;
    }

    // 2단계: 주소(근무지) 등록 여부
    function checkRequiredAddress() {
        var adAddr = $("#ad_addr").val();
        var adZipcode = $("#ad_zipcode").val();
        var hasAddressClass = $("#btnAddrAdd").hasClass("has-address");
        if (!adAddr || adAddr.trim() === "" || !adZipcode || adZipcode.trim() === "" || !hasAddressClass) {
            commonAlert("주소를 등록해주세요.", function() {
                $("#btnAddrAdd").focus();
            });
            return false;
        }
        return true;
    }

    // 3단계: 채용공고기간 (시작일·종료일 입력 및 순서)
    function checkRequiredPeriod() {
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

    // 4단계: 업무구분 선택 여부
    function checkRequiredWork() {
        if ($("[name='rc_work[]']").is(":checked") == false) {
            commonAlert("업무구분을 하나 이상 선택해주세요.", function() {});
            return false;
        }
        return true;
    }

    // 5단계: 해시태그 선택 여부 (직접입력으로 추가된 태그도 rc_hash[] 체크박스로 생성됨)
    function checkRequiredHash() {
        if ($("[name='rc_hash[]']").is(":checked") == false) {
            commonAlert("해시태그를 하나 이상 선택해주세요.", function() {});
            return false;
        }
        return true;
    }

    // 6단계: 근무형태 선택 여부 (근무협의 체크 시에는 통과)
    function checkRequiredWorkType() {
        if ($("[name='rc_work_negotiation']").is(":checked") == false && $("[name='rc_work_type[]']").is(":checked") == false) {
            commonAlert("근무형태를 하나 이상 선택해주세요.", function() {});
            return false;
        }
        return true;
    }

    // 7단계: 급여 금액 입력 여부 (급여협의 선택 시에는 금액 불필요)
    function checkRequiredSalary() {
        var priceType = $("#rc_price_type_main").val();
        if (priceType !== "급여협의") {
            if ($("#rc_price_amount").val() == "" || parseInt($("#rc_price_amount").val()) <= 0) {
                commonAlert("급여 금액을 입력해주세요.", function() {
                    $("#rc_price_amount").focus();
                });
                return false;
            }
        }
        return true;
    }

    // 8단계: 복리후생 선택 여부 (체크박스 하나 이상 선택 필요)
    function checkRequiredBenefit() {
        if ($("[name='rc_life_type[]']:checked").length == 0) {
            commonAlert("복리후생을 하나 이상 선택해주세요.", function() {});
            return false;
        }
        return true;
    }

    // 10단계: 상세업무정보 입력 여부 (contenteditable 에디터)
    function checkRequiredDesc() {
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
        return true;
    }

    // 단계별 필수값 검사: "다음" 클릭 시 현재 단계 항목만 확인 (9·11단계는 필수 검사 없음)
    function validateStepRequired(step) {
        switch (step) {
            case 1: return checkRequiredTitle();
            case 2: return checkRequiredAddress();
            case 3: return checkRequiredPeriod();
            case 4: return checkRequiredWork();
            case 5: return checkRequiredHash();
            case 6: return checkRequiredWorkType();
            case 7: return checkRequiredSalary();
            case 8: return checkRequiredBenefit();
            case 10: return checkRequiredDesc();
            default: return true;
        }
    }

    // 제출 시 전체 필수값 검사 (모든 단계 항목을 순서대로 확인)
    function validateRequiredForSubmit() {
        return checkRequiredTitle()
            && checkRequiredAddress()
            && checkRequiredWork()
            && checkRequiredHash()
            && checkRequiredWorkType()
            && checkRequiredSalary()
            && checkRequiredDesc()
            && checkRequiredPeriod();
    }

    // 아직 서버로 보내지 않은(전송 대기 중인) 파일이 남아 있는지 확인
    // 메인사진/상세이미지는 선택 즉시 업로드되고 URL만 hidden에 남으므로 보통 false가 된다.
    // false면 serialize + comAjax 의 빠른 경로를 타고, 같은 사진을 다시 올리지 않는다.
    function hasFileUploads() {
        // 메인사진 파일 선택 여부 확인 (즉시 업로드 실패 등으로 input이 비워지지 않은 경우)
        var fileInputs = ["rc_img1"];
        for (var i = 0; i < fileInputs.length; i++) {
            var fileInput = $("[name='" + fileInputs[i] + "']")[0];
            if (fileInput && fileInput.files && fileInput.files.length > 0) {
                return true;
            }
        }

        return false;
    }

    // 제출(미리보기/등록) 진행 중 여부
    // 저장은 여러 ajax를 순차 호출하므로, 두 번 누르면 공용 comAjax의 중복 가드가 걸려
    // "데이터 처리중입니다." 팝업이 뜬다. 가드에 도달하기 전에 호출부에서 막는다.
    var isSubmitting = false;

    // 제출 시작: 버튼 잠금 (상태는 data 속성, 표현은 CSS가 담당)
    function lockSubmit() {
        isSubmitting = true;
        $("#btnPreview, #btnSubmit").attr("data-state", "submitting").prop("disabled", true);
    }

    // 제출 종료: 버튼 잠금 해제
    function unlockSubmit() {
        isSubmitting = false;
        $("#btnPreview, #btnSubmit").attr("data-state", "idle").prop("disabled", false);
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
                unlockSubmit();
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
                    unlockSubmit();
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
        // SmartEditor2 포맷으로 정규화해서 저장
        var rcDescEditor = document.getElementById('rc_desc_editor');
        var rcDescContent = rcDescEditor ? rcDescEditor.innerHTML : "";
        var normalizedContent = normalizeContenteditableToSmartEditor(rcDescContent);
        $("[name='rc_desc']").val(normalizedContent);

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
                   // console.log("공고 저장 후 미리보기 모드로 이동: rc_idx =", savedRcIdx);
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

                            // 기존 공고 수정(gbn=copy 제외) 완료 시 해당 공고 상세(job_post)로, 최초 등록 완료 시 목록으로 이동
                            var isEditComplete = (origRcIdx && origRcIdx !== "0" && gbnMode !== "copy");
                            location.href = isEditComplete ? "/recruit/job_post/" + finalRcIdx : "/compage/recruit_list";
                        } else {
                            unlockSubmit();
                            commonAlert(ret2.msg, function() {});
                            return;
                        }
                    });
                }
            } else {
                unlockSubmit();
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
                    unlockSubmit();
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
        // 현재 폼 데이터를 draft로 저장 (돌아올 때 복원용)
        savePage1Data();
        savePage2Data();
        savePage3Data();

        // 회사정보 저장
        var memInfo = {
            mm_comp_name: '<?= isset($mem_info["mm_comp_name"]) ? $mem_info["mm_comp_name"] : "" ?>',
            mm_phone: '<?= isset($mem_info["mm_phone"]) ? $mem_info["mm_phone"] : "" ?>'
        };
        sessionStorage.setItem('previewMemInfo', JSON.stringify(memInfo));

        // 물리적 뒤로가기 대응: sessionStorage에 플래그 설정
        // 이를 통해 recruit_write1_1.php로 돌아올 때 draft 로드 여부 판단
        sessionStorage.setItem('from_preview', 'true');

        // add.js 의 전역 pageshow 핸들러가 뒤로가기 때 이 값으로 스크롤을 되돌린다.
        // 미리보기 버튼을 누른 위치(마지막 단계 아래)가 담겨 있으면 복귀 시 화면이 아래로 튀므로
        // 맨 위로 덮어쓴다
        try {
            localStorage.setItem('divScrollPositon', '0');
        } catch (e) {}

        // 이동이 취소되거나 실패해도 버튼이 잠긴 채 남지 않도록 먼저 해제
        unlockSubmit();

        window.location.href = '/recruit/job_post/' + rc_idx + '?preview=1';
    }

    // 미리보기에서 최종 저장 처리 (window.parent에서 접근 가능)
    function submitPreview() {
        handleSubmit("final");
    }

    function handleSubmit(mode) {
        // 이미 진행 중이면 무시 (연타 시 공용 comAjax 중복 가드가 걸리는 것을 방지)
        if (isSubmitting) {
            return;
        }

        if (!validateRequiredForSubmit()) {
            return;
        }

        lockSubmit();

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
    // 뒤로가기(bfcache)로 돌아오면 JS 상태가 그대로 복원되므로
    // 제출 잠금/로딩 오버레이가 남아 화면이 먹통이 되는 것을 막는다
    window.addEventListener('pageshow', function() {
        unlockSubmit();
        if (typeof resetLoadingOverlay === 'function') {
            resetLoadingOverlay();
        }
    });

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

        // 새 공고 작성일 때만 (기존 공고는 loadPage3Data에서 이미 설정됨)
        if (rcIdx === "0") {
            setTimeout(function() {
                var startInputValue = document.querySelector('#rc_sdate')?.value;

                // input이 비어있으면 오늘과 오늘+2개월로 설정
                if (!startInputValue || startInputValue === '') {
                var today = new Date();
                var todayStr = today.getFullYear() + '-' + (today.getMonth() + 1).toString().padStart(2, '0') + '-' + today.getDate().toString().padStart(2, '0');

                var endDate = new Date(today);
                endDate.setMonth(endDate.getMonth() + 2);
                var endDateStr = endDate.getFullYear() + '-' + (endDate.getMonth() + 1).toString().padStart(2, '0') + '-' + endDate.getDate().toString().padStart(2, '0');

                var startFormatted = today.getFullYear() + '.' + (today.getMonth() + 1).toString().padStart(2, '0') + '.' + today.getDate().toString().padStart(2, '0');
                var endFormatted = endDate.getFullYear() + '.' + (endDate.getMonth() + 1).toString().padStart(2, '0') + '.' + endDate.getDate().toString().padStart(2, '0');

                if (window.pickerInstances && window.pickerInstances.startPicker) {
                    window.pickerInstances.startPicker.setDate(todayStr, true);
                    // 원본 input은 Y-m-d, 보조 input은 점 포맷
                    var sInput = document.querySelector('#rc_sdate');
                    if (sInput) {
                        sInput.value = todayStr;
                        var sAltInput = sInput.nextElementSibling;
                        if (sAltInput && sAltInput.tagName === 'INPUT') {
                            sAltInput.value = startFormatted;
                        }
                    }
                }

                if (window.pickerInstances && window.pickerInstances.endPicker) {
                    window.pickerInstances.endPicker.setDate(endDateStr, true);
                    // 원본 input은 Y-m-d, 보조 input은 점 포맷
                    var eInput = document.querySelector('#rc_edate');
                    if (eInput) {
                        eInput.value = endDateStr;
                        var eAltInput = eInput.nextElementSibling;
                        if (eAltInput && eAltInput.tagName === 'INPUT') {
                            eAltInput.value = endFormatted;
                        }
                    }
                }
                }
            }, 250);
        }

        // 상세페이지 이미지 박스 초기화 (저장 후 페이지 로드 시에도 X버튼 작동하도록)
        for (var slot = 1; slot <= 3; slot++) {
            var $label = $(".form-pic-detail[data-detail-slot='" + slot + "']");
            var inputName = 'rc_detail_img' + slot;
            var uploadedUrl = $("[name='" + inputName + "']").val();
            if (uploadedUrl) {
                // 기존 이미지 URL을 메모리에 저장 (X버튼 클릭 시 사용)
                $label.data("uploadedUrl", uploadedUrl);
            }
        }

        // 근무시간 섹션 초기화
        updateTimeSection();

        (function() {
            function chgMonth(instance) {
                var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                    month = Number(instance.currentMonth) + 1,
                    year = instance.currentYear;
                $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
            }

            if (typeof flatpickr == 'undefined') return false;

            // 신규/기존 공고 모두 매번 열 때마다 오늘과 오늘+2개월로 초기화
            var today = new Date();
            var todayStr = today.getFullYear() + '-' + (today.getMonth() + 1).toString().padStart(2, '0') + '-' + today.getDate().toString().padStart(2, '0');

            // HTML에서 받은 값이 있으면 그것을 사용, 없으면 오늘 사용
            var existingStartDate = document.querySelector('#rc_sdate')?.value || "";
            // 점 포맷("2026.08.24")을 대시 포맷("2026-08-24")으로 변환
            var defaultStartDate = existingStartDate ? existingStartDate.replace(/\./g, '-') : "";
            var startDate = defaultStartDate != "" && defaultStartDate >= todayStr ? defaultStartDate : todayStr;

            // HTML에서 받은 값이 있으면 그것을 사용, 없으면 startDate + 2개월 사용
            var existingEndDate = document.querySelector('#rc_edate')?.value || "";
            // 점 포맷("2026.08.24")을 대시 포맷("2026-08-24")으로 변환
            var defaultEndDate = existingEndDate ? existingEndDate.replace(/\./g, '-') : "";

            var endDate = defaultEndDate != "" ? defaultEndDate : null;
            if (!endDate) {
                var startDateObj = new Date(startDate);
                var endDateObj = new Date(startDateObj);
                endDateObj.setMonth(endDateObj.getMonth() + 2);
                endDate = endDateObj.getFullYear() + '-' +
                    (endDateObj.getMonth() + 1).toString().padStart(2, '0') + '-' +
                    endDateObj.getDate().toString().padStart(2, '0');
            }

            var startDateObj = new Date(startDate);
            var maxEndDate = new Date(startDateObj);
            maxEndDate.setMonth(maxEndDate.getMonth() + 2);

            // flatpickr 인스턴스를 전역으로 저장 (loadPage3Data에서 재사용)
            if (!window.pickerInstances) {
                window.pickerInstances = {};
            }

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
                maxDate: (function() {
                    var d = new Date();
                    d.setMonth(d.getMonth() + 2);
                    return d;
                })(),
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
                    if (selectedDates.length > 0 && window.pickerInstances && window.pickerInstances.endPicker) {
                        var selectedStartDate = selectedDates[0];
                        window.pickerInstances.endPicker.set('minDate', selectedStartDate);

                        var maxDate = new Date(selectedStartDate);
                        maxDate.setMonth(maxDate.getMonth() + 2);
                        window.pickerInstances.endPicker.set('maxDate', maxDate);

                        var currentEndDate = window.pickerInstances.endPicker.selectedDates[0];
                        if (currentEndDate && currentEndDate > maxDate) {
                            window.pickerInstances.endPicker.setDate(maxDate);
                        }
                    }
                    savePage3Data();
                }
            });
            window.pickerInstances.startPicker = startPicker;
            startPicker.setDate(startDate, true);

            // input의 value 직접 설정 (원본 input + 보조 input)
            var startParts = startDate.split('-');
            if (startParts.length === 3) {
                var formattedStart = startParts[0] + '.' + startParts[1] + '.' + startParts[2];
                var startInput = document.querySelector('#rc_sdate');
                if (startInput) {
                    startInput.value = startDate;  // dateFormat과 일치하는 Y-m-d(대시) 포맷으로 저장
                    // 보조 input은 점 포맷으로 화면 표시
                    var startAltInput = startInput.nextElementSibling;
                    if (startAltInput && startAltInput.tagName === 'INPUT') {
                        startAltInput.value = formattedStart;
                    }
                }
            }

            var endPicker = flatpickr('.js-datepicker-end', {
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
            window.pickerInstances.endPicker = endPicker;
            endPicker.setDate(endDate, true);

            // input의 value 직접 설정 (원본 input + 보조 input)
            var endParts = endDate.split('-');
            if (endParts.length === 3) {
                var formattedEnd = endParts[0] + '.' + endParts[1] + '.' + endParts[2];
                var endInput = document.querySelector('#rc_edate');
                if (endInput) {
                    endInput.value = endDate;  // dateFormat과 일치하는 Y-m-d(대시) 포맷으로 저장
                    // 보조 input은 점 포맷으로 화면 표시
                    var endAltInput = endInput.nextElementSibling;
                    if (endAltInput && endAltInput.tagName === 'INPUT') {
                        endAltInput.value = formattedEnd;
                    }
                }
            }
        }());

        setTimeout(function() {
            // loadPage3Data()는 DOMContentLoaded의 restorePageData()에서 이미 호출됨 (중복 제거)
            syncTimeHiddenFields();
        }, 1000);

        // 페이지 로드 시 근무시간 섹션 및 근무협의 초기화
        updateTimeSection();

        updateNegotiationCheckbox();

        updateSalaryOptions();

        // 공고제목 입력칸: 줄바꿈 금지, 최대 50자, 입력 내용에 맞춰 높이 자동 조절
        // 저장 핸들러보다 먼저 바인딩해서 정제된 값이 저장되도록 함
        $("#rc_name_input").on("keydown", function(e) {
            // Enter 줄바꿈 차단
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });
        $("#rc_name_input").on("input", function() {
            var $input = $(this);
            // 줄바꿈 제거 후 50자 초과분 잘라내기 (붙여넣기 대응)
            var rcName = $input.val().replace(/[\r\n]+/g, "").substring(0, 50);
            if ($input.val() !== rcName) {
                $input.val(rcName);
            }
            $("#rc_name_count").text(rcName.length);
            // 줄바꿈 표시를 위해 내용 높이에 맞춤 (!important로 고정 높이 규칙을 덮어씀)
            this.style.setProperty("height", "auto", "important");
            this.style.setProperty("height", this.scrollHeight + "px", "important");
        });
        // 초기 높이/카운트 반영 (저장 핸들러 바인딩 전이라 저장은 발생하지 않음)
        $("#rc_name_input").trigger("input");
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

        // 숨김(1px/투명) 체크박스가 포커스를 받으면 WebView가 화면을 자동 스크롤하므로 포커스를 바로 해제
        $("[name='rc_life_type[]']").on("focus", function() {
            this.blur();
        });
        $("[name='rc_life_type[]']").on("change", savePage2Data);

        // contenteditable div의 input 이벤트 감지 및 DOM 정규화
        var rcDescEditor = document.getElementById('rc_desc_editor');
        rcDescEditor.addEventListener('input', function() {
            normalizeEditorDom(rcDescEditor);
            savePage3Data();
        });

        // 클립보드 붙여넣기 시 줄바꿈 보존 처리 (Selection/Range API 사용)
        rcDescEditor.addEventListener('paste', function(e) {
            // 클립보드에서 순수 텍스트만 추출
            var text = (e.clipboardData || window.clipboardData).getData('text/plain');

            // 클립보드 데이터가 없으면 네이티브 붙여넣기 허용
            if (!text) {
                return;
            }

            e.preventDefault();

            // 모든 종류의 줄바꿈을 정규화
            // Windows: \r\n, Mac: \n, 레거시: \r을 모두 처리
            text = text.replace(/\r\n/g, '\n').replace(/\r/g, '\n');

            // Selection/Range API로 텍스트와 <br> 삽입
            var selection = window.getSelection();
            if (selection.rangeCount === 0) {
                return;
            }

            var range = selection.getRangeAt(0);
            range.deleteContents();

            // 줄바꿈 기준으로 텍스트와 <br> 교대로 삽입
            var lines = text.split('\n');
            for (var i = 0; i < lines.length; i++) {
                if (i > 0) {
                    var br = document.createElement('br');
                    range.insertNode(br);
                    range.setStartAfter(br);
                }

                var textNode = document.createTextNode(lines[i]);
                range.insertNode(textNode);
                range.setStartAfter(textNode);
            }

            // 선택 위치 업데이트
            selection.removeAllRanges();
            range.collapse(false);
            selection.addRange(range);

            // 삽입 후 DOM 정규화 및 저장
            normalizeEditorDom(rcDescEditor);
            savePage3Data();
        });

        // WebView 친화적 설정: Enter 키 시 <div> 대신 <br>을 만들도록 유도 (1회 시도, 실패 무시)
        rcDescEditor.addEventListener('focus', function() {
            try {
                document.execCommand('defaultParagraphSeparator', false, 'br');
            } catch (e) {
                // 지원하지 않는 WebView는 무시
            }
        });

        // hidden input도 호환성을 위해 유지
        $("[name='rc_desc']").on("input", savePage3Data);
        $("[name='rc_hash[]']").on("change", savePage3Data);
        $("[name='rc_day[]'], [name='rc_day_all']").on("change", savePage3Data);
        $("[name='rc_sex[]'], [name='rc_sex_all']").on("change", savePage3Data);
        $("[name='rc_nation[]'], [name='rc_nation_all']").on("change", savePage3Data);
        $("[name='rc_sage'], [name='rc_eage'], [name='rc_age_all']").on("change", savePage3Data);

        $(document).on("change", "[name='rc_sdate'], [name='rc_edate']", function() {
            savePage3Data();
            scheduleDateChangeAdvance();
        });

        /* 선택 상한 도달 시 자동 다음 단계 이동
           - AUTO_ADVANCE_DELAY: 모든 자동 이동이 공유하는 대기 시간(ms). 너무 빨리 넘어가지 않도록 여유를 준다
           - 현재 단계일 때만 동작: 이전 단계를 고칠 때는 넘어가지 않는다
           - 타이머 실행 시 조건을 다시 확인해, 그 사이 선택이 해제되면 이동하지 않는다 */
        var AUTO_ADVANCE_DELAY = 1000;
        var autoAdvanceTimer = null;
        function autoAdvanceIfComplete(step, isComplete) {
            if (autoAdvanceTimer) {
                clearTimeout(autoAdvanceTimer);
                autoAdvanceTimer = null;
            }
            if (currentStep !== step || !isComplete()) {
                return;
            }
            autoAdvanceTimer = setTimeout(function() {
                autoAdvanceTimer = null;
                if (currentStep === step && isComplete()) {
                    // 입력 중이던 입력란의 포커스를 먼저 해제해 키보드를 내린다 (키보드 전환과 스크롤이 겹쳐 튕기는 것을 막기 위함)
                    if (document.activeElement && document.activeElement.blur) {
                        document.activeElement.blur();
                    }
                    // 이 이동에서만 새 단계의 스크롤을 지연한다 (다른 이동 방식에는 영향 없음)
                    window.__autoAdvanceScroll = true;
                    goNextStep();
                    window.__autoAdvanceScroll = false;
                }
            }, AUTO_ADVANCE_DELAY);
        }

        /* 공고기간(3단계) 자동 이동: 시작일·종료일은 기본값(오늘 ~ 2개월 뒤)이 이미 채워져 있으므로
           3단계에 처음 들어왔을 때 AUTO_ADVANCE_DELAY 뒤 자동으로 다음 단계로 넘어간다
           - 날짜 영역이나 달력을 건드려도 취소하지 않고 그대로 이동한다
           - 한 번 실행되면 이후 재진입에서는 다시 자동 이동하지 않는다 (값 변경 이동은 별도)
           - 타이머 실행 시 아직 3단계인지 확인해, 그 사이 다음 버튼으로 넘어갔으면 이동하지 않는다 */
        var dateAutoAdvanceTimer = null;
        var dateAutoAdvanceDone = false;
        function cancelDateAutoAdvance() {
            dateAutoAdvanceDone = true;
            if (dateAutoAdvanceTimer) {
                clearTimeout(dateAutoAdvanceTimer);
                dateAutoAdvanceTimer = null;
            }
        }
        function scheduleDateAutoAdvance() {
            if (dateAutoAdvanceDone || dateAutoAdvanceTimer || currentStep !== 3) {
                return;
            }
            dateAutoAdvanceTimer = setTimeout(function() {
                dateAutoAdvanceTimer = null;
                if (!dateAutoAdvanceDone && currentStep === 3) {
                    cancelDateAutoAdvance();
                    goNextStep();
                }
            }, AUTO_ADVANCE_DELAY);
        }
        /* 공고기간 값이 바뀌면(단계 재진입 포함) 잠시 후 다음 단계로 이동한다
           첫 진입 자동 이동과 달리 dateAutoAdvanceDone을 보지 않으며, 날짜를 다시 고르면 대기 시간이 새로 시작된다 */
        function scheduleDateChangeAdvance() {
            if (currentStep !== 3) {
                return;
            }
            dateAutoAdvanceDone = true;
            if (dateAutoAdvanceTimer) {
                clearTimeout(dateAutoAdvanceTimer);
            }
            dateAutoAdvanceTimer = setTimeout(function() {
                dateAutoAdvanceTimer = null;
                if (currentStep === 3) {
                    goNextStep();
                }
            }, AUTO_ADVANCE_DELAY);
        }

        /* 주소(2단계) 자동 이동: 근무지와 추가노출지역 1,2가 모두 채워졌을 때만 이동
           주소 추가 경로(selectAddrInfo, onChangeAreaApply)에서만 호출한다 */
        window.autoAdvanceAddrIfComplete = function() {
            autoAdvanceIfComplete(2, function() {
                return $("#ad_addr").val() !== "" &&
                    $("#expose_addr1_addr").val() !== "" &&
                    $("#expose_addr2_addr").val() !== "";
            });
        };

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
                        return;
                    }

                    // 선택 상한(2개)에 도달하면 자동으로 다음 단계 이동 (근무형태는 자동 이동하지 않음)
                    if (name === 'rc_work[]') {
                        autoAdvanceIfComplete(4, function() {
                            return document.querySelectorAll('[name="rc_work[]"]:checked').length === 2;
                        });
                    }
                });
            });
        }());

        /* 7단계 급여 자동 이동
           - 급여협의를 고르면 금액·옵션이 필요 없으므로 바로 다음 단계로 이동
           - 그 외 급여형태는 금액과 급여가능 옵션이 모두 채워지면 이동
           급여가능 옵션은 updateSalaryOptions()로 보이는 항목 중에서만 인정한다 */
        function isSalaryComplete() {
            var priceType = $("#rc_price_type_main").val();
            if (priceType === "급여협의") {
                return true;
            }
            var amount = parseInt(removeComma($("#rc_price_amount").val()), 10);
            if (!(amount > 0)) {
                return false;
            }
            return $("[name='rc_price_possible']:checked").closest("label").is(":visible");
        }
        $("#rc_price_type_main, #rc_price_amount, [name='rc_price_possible']").on("change input", function() {
            autoAdvanceIfComplete(7, isSalaryComplete);
        });

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

            var $newLabel = $('<label class="form-toggle-btn2 sm tag is-custom">' +
                '<input type="checkbox" name="rc_hash[]" value="' + cleanHash + '" checked>' +
                '<span class="btn">' + cleanHash + '</span>' +
                '<button type="button" class="btn-tag-remove js-remove-hashtag" aria-label="' + cleanHash + ' 삭제">×</button>' +
                '</label>');

            $(".hashtag-field-wrapper .form-toggle-group").append($newLabel);

            $input.val("");

            // 직접입력 태그를 실제 선택 변경과 같은 경로로 처리한다 (기본 태그의 change 핸들러가 상한 검사와 자동 이동을 함께 수행)
            // 저장보다 먼저 호출해, 저장 쪽에서 예외가 나더라도 자동 이동이 막히지 않게 한다
            $newLabel.find("input[name='rc_hash[]']").trigger("change");

            savePage3Data();
        });

        /* 직접입력으로 추가한 해시태그 삭제: x 버튼으로 태그를 제거하고 저장한다
           (label 안의 button 클릭이 체크박스 토글로 번지지 않도록 막는다) */
        $(document).on("click", ".js-remove-hashtag", function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).closest("label").remove();
            savePage3Data();
        });

        $(document).on("change", "[name='rc_hash[]']", function() {
            var checkedCount = $("[name='rc_hash[]']:checked").length;
            if (checkedCount > 4) {
                $(this).prop("checked", false);
                commonAlert("해시태그는 최대 4개까지 선택 가능합니다.", function() {});
            } else {
                // 해시태그 상한(4개)에 도달하면 자동으로 다음 단계 이동 (차단된 선택은 제외)
                autoAdvanceIfComplete(5, function() {
                    return $("[name='rc_hash[]']:checked").length === 4;
                });
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

            // 나이를 선택하면 나이무관은 언체크 (선택 값으로 나이무관을 자동 체크하지 않음)
            // 나이무관 체크는 사용자가 직접 누르거나 페이지 최초 진입 시 기본값으로만 적용
            if (sageVal !== "" || eageVal !== "") {
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
            var $label = $(this).closest(".form-pic-main, .form-pic-detail");
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
            var $label = $(this).closest(".form-pic-main, .form-pic-detail");
            var $oriInput = $label.find("[name='rc_img1_ori']");
            var $thumbInput = $label.find("[name='rc_thumb_ori']");
            var $input = this;

            // X 취소용 스냅샷 저장
            $label.data("prevOri", $oriInput.val());
            $label.data("prevThumb", $thumbInput.val());
            $label.data("prevPreviewHtml", $label.find(".preview").html());
            $label.data("prevHasImage", $label.hasClass("has-image"));

            var file = this.files[0]; //목록 중 첫 번째 파일 객체 (File 객체)
        

            if (!file) return;

            $label.find(".btn-pic-remove").show();

            var formData = new FormData();
            formData.append("rc_img1", file);

            showLoadingOverlay("사진 업로드 중...");
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
                        // json_return 응답은 {code, msg, data:{url, url_sm, url_md, url_lg}} 구조
                        var upload = ret.data ? ret.data : {};
                        // 최종확정 단계에서 이 값이 그대로 저장됨
                        // 서버의 rc_img1 은 lg 기준이므로 lg URL을 보관한다 (기존 이미지 삭제 비교와 단위를 맞춤)
                        $oriInput.val(upload.url_lg ? upload.url_lg : (upload.url ? upload.url : ""));
                        // 썸네일은 목록용이라 sm(400px)
                        $thumbInput.val(upload.url_sm ? upload.url_sm : (upload.url ? upload.url : ""));
                        // 이미 서버에 올라갔으므로 file input을 비운다
                        // (비우지 않으면 미리보기/등록 시 같은 사진을 FormData로 다시 업로드해 수 초가 더 걸림)
                        $input.value = "";
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
            var $label = $(this).closest(".form-pic-main, .form-pic-detail");
            var file = this.files[0];
            if (!file) return;

            // 확장자 사전 검사: 서버 왕복 전에 막는다. 0단계(기존 사진 제거)보다 먼저 실행해 기존 사진은 그대로 둔다
            if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
                var input = this;
                commonAlert('jpg, jpeg, png, gif 형식의 사진만 등록할 수 있습니다.', function() {
                    input.value = ""; // 같은 파일을 다시 골라도 change 이벤트가 발생하도록 비운다
                });
                return;
            }

            // 0. 기존 이미지 제거 (같은 슬롯에 새 사진 선택 시)
            var detailSlot = $label.attr('data-detail-slot');
            if (detailSlot) {
                var inputName = 'rc_detail_img' + detailSlot;
                var uploadedUrl = $("[name='" + inputName + "']").val();

                if (uploadedUrl) {
                    // 에디터에서 기존 이미지 제거
                    var $editor = $("#rc_desc_editor");
                    $editor.find("img").each(function() {
                        if ($(this).attr("src") === uploadedUrl || $(this).attr("data-uploaded-url") === uploadedUrl) {
                            $(this).remove();
                        }
                    });

                    // hidden input 초기화
                    $("[name='" + inputName + "']").val("");
                }
            }

            // 1. 로컬 미리보기
            var reader = new FileReader();
            reader.onload = function(e) { // e.target.result는 base64로 인코딩된 이미지 데이터 ->다 읽은 파일 데이터를 RAM(브라우저 메모리) 위에 글자 형태(data:image/png;base64,...)로 올려두고, 그 주소를 화면의 <img> 태그에 넣어서 바로 보여주기 위해 사용
                $label.find(".preview").html('<img src="' + e.target.result + '" />');
                $label.addClass("has-image");
                $label.find(".btn-pic-remove").show();
            };
            reader.readAsDataURL(file); // 하드디스크에 있는 사진 파일의 알맹이 데이터를 직접 읽기

            // 2. 즉시 업로드 + 에디터 삽입
            uploadAndInsertIntoEditor(file, $label);
        });

        // X버튼(공용): 1번은 업로드 이전 상태로 복원, 2~4번은 초기화 + 에디터에서도 제거
        $(document).on("click", ".form-pic-main .btn-pic-remove, .form-pic-detail .btn-pic-remove", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $label = $(this).closest(".form-pic-main, .form-pic-detail");
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
                $label.find("[name='rc_thumb_ori']").val($label.data("prevThumb") || "");
            } else {
                // 2~4번 박스: 미리보기 초기화 + 에디터에서 해당 이미지 제거 + hidden input 초기화
                var detailSlot = $label.attr('data-detail-slot');
                if (detailSlot) {
                    var inputName = 'rc_detail_img' + detailSlot;
                    // hidden input에서 현재 URL을 가져옴 (페이지 새로고침 후에도 유지됨)
                    var uploadedUrl = $("[name='" + inputName + "']").val();

                    if (uploadedUrl) {
                        // 상세업무정보 에디터에서 해당 URL의 이미지 제거
                        var $editor = $("#rc_desc_editor");

                        // 모든 이미지를 순회하면서 URL 일치하는 이미지 찾아 제거
                        $editor.find("img").each(function() {
                            if ($(this).attr("src") === uploadedUrl || $(this).attr("data-uploaded-url") === uploadedUrl) {
                                $(this).remove();  // image-wrapper 제거 후 img 태그 제거
                            }
                        });
                    }

                    // hidden input 값 초기화 (데이터 레벨에서도 제거)
                    $("[name='" + inputName + "']").val("");
                }

                $label.find(".preview").html("");
                $label.removeClass("has-image");
                $label.data("uploadedUrl", null);
            }
            $(this).hide();
        });

        // 파일 선택 및 에디터에 이미지 삽입 공용 함수 (2~4번 박스 전용)
        // 업로드 실패 시 상세 슬롯을 빈 상태로 되돌린다 (미리보기/has-image/X버튼/file input)
        function resetDetailSlot($label) {
            if (!$label || !$label.length) return;
            $label.data("uploadedUrl", null);
            $label.find(".preview").html("");
            $label.removeClass("has-image");
            $label.find(".btn-pic-remove").hide();
            $label.find("input[type=file]").val("");
        }

        function uploadAndInsertIntoEditor(file, $label) {
            var formData = new FormData();
            formData.append('Filedata', file);

            showLoadingOverlay("사진 업로드 중...");
            $.ajax({
                url: '/compage_proc/se2_image_upload', // 서버 업로드 처리 URL
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
                            $label.addClass("has-image");
                            $label.find(".btn-pic-remove").show();

                            // detail-slot에 맞는 hidden input에 URL 저장 (데이터 레벨에서도 기록)
                            var detailSlot = $label.attr('data-detail-slot');
                            if (detailSlot) {
                                var inputName = 'rc_detail_img' + detailSlot;
                                $("[name='" + inputName + "']").val(response.url);
                            }

                            // 업로드가 끝났으므로 file input을 비운다 (같은 사진 재선택 시 change 이벤트가 다시 발생하도록)
                            $label.find("input[type=file]").val("");
                        }
                    } else {
                        // 서버가 준 사유를 우선 표시하고, 미리보기는 빈 슬롯으로 되돌린다
                        resetDetailSlot($label);
                        commonAlert(response.message || (file.name + ' 업로드에 실패했습니다.'), function() {});
                    }
                },
                error: function() {
                    hideLoadingOverlay();
                    resetDetailSlot($label);
                    commonAlert(file.name + ' 업로드 중 오류가 발생했습니다.', function() {});
                }
            });
        }

        // 다음 이동: "다음" 버튼과 선택 상한 자동 이동이 함께 사용한다
        function goNextStep() {
            // 현재 단계의 필수 항목을 통과해야 다음 단계로 이동
            if (!validateStepRequired(currentStep)) {
                return;
            }
            if (currentStep < getTotalSteps()) {
                currentStep++;
                updateStepVisibility();
                // 공고기간 단계에 들어오면 기본값으로 충분하므로 잠시 후 자동으로 다음 단계로 넘어간다
                if (currentStep === 3) {
                    scheduleDateAutoAdvance();
                }
            }
        }

        // 단계별 진행 버튼 핸들러
        $("#btnStepNext").click(goNextStep);

        // 텍스트 입력란에서 Enter 키를 누르면 "다음" 버튼과 동일하게 다음 단계로 이동
        // (textarea/contenteditable 은 줄바꿈이 필요하므로 제외, 한글 조합 중 Enter 는 무시)
        $(document).on('keydown', 'input[type="text"], input[type="tel"], input[type="number"], input[type="date"], input:not([type])', function(e) {
            if (e.key !== 'Enter' || e.isComposing || e.keyCode === 229) {
                return;
            }
            // 주소검색 팝업(#changeArea, #changeAreaExpose) 안의 입력란은 Enter 시 주소 검색만 하므로 다음 단계로 넘기지 않는다
            if ($(this).closest('#changeArea, #changeAreaExpose').length > 0) {
                return;
            }
            // "다음" 버튼이 보이는 단계에서만 동작 (마지막 단계·상세등록 구간 제외)
            if (!$("#btnStepNext").is(':visible')) {
                return;
            }
            e.preventDefault();
            goNextStep();
        });

        /* 상세등록(+): 마지막 필수 단계(10)에서 선택 구간(11)을 열고 진입 */
        $("#btnDetailMore").click(function(e) {
            e.preventDefault();
            if (!validateStepRequired(currentStep)) {
                return;
            }
            isDetailOpen = true;
            currentStep = REQUIRED_STEPS + 1;
            updateStepVisibility();
        });

        $("#btnStepPrev").click(function() {
            // 11단계에서 뒤로 가면 상세등록 구간을 닫고 10단계로 돌아간다
            // (입력값은 DOM 에 그대로 남아 다시 열면 복원된다)
            if (currentStep === REQUIRED_STEPS + 1) {
                isDetailOpen = false;
                currentStep = REQUIRED_STEPS;
                updateStepVisibility();
                return;
            }
            if (currentStep > 1) {
                currentStep--;
                updateStepVisibility();
            }
        });

        $("#btnPreview").click(function() {
            handleSubmit("preview");
        });

        $("#btnSubmit").click(function() {
            handleSubmit("final");
        });

        // 단계별 진행 초기화
        // 미리보기에서 돌아온 경우에는 처음부터 다시 밟지 않도록 마지막 단계까지 모두 펼친다
        if (IS_RETURN_FROM_PREVIEW) {
            // 이 블록은 window DOMContentLoaded 핸들러(restorePageData 호출 이후) 안에 있으므로 값 복원이 이미 끝나 있다
            // 상세등록 항목을 기본값 그대로 두었으면 펼치지 않고, 하나라도 수정했으면 11단계까지 펼친다
            isDetailOpen = !isDetailDefault();
            currentStep = isDetailOpen ? TOTAL_STEPS : REQUIRED_STEPS;
            updateStepVisibility(true);
            scrollPageTop();
        } else if (rcIdx !== "0" && ($("[name='rc_name']").val() || "") !== "") {
            // 수정 모드(rc_idx가 있고 공고제목이 있을 때): 처음부터 다시 밟지 않도록 상세등록 구간까지 모두 펼친다
            isDetailOpen = true;
            currentStep = TOTAL_STEPS;
            updateStepVisibility(true);
            scrollPageTop();
        } else {
            updateStepVisibility();
        }

         $(".btn-back").click(function(e) {
            e.preventDefault();
            savePage1Data();
            savePage2Data();
            savePage3Data();
        clearRecruitDraftData($("[name='rc_idx']").val() || "0"); //임시저장 삭제처리

            // origRcIdx: 컨트롤러가 넘겨준 원본 공고 번호 (gbn=copy 모드에서도 항상 정확한 값)
            var backRcIdx = (typeof origRcIdx !== 'undefined' && origRcIdx && origRcIdx !== '0')
                ? origRcIdx
                : $("[name='rc_idx']").val();

            if (backRcIdx && backRcIdx !== "0") {
                location.href = '/recruit/job_post/' + backRcIdx;
            } else {
                location.href = '/recruit/job_list';
            }
        });

        // 히스토리 상태 저장 (무한루프 방지를 위해 page 상태 추가)
        window.addEventListener('popstate', function(e) {
            // recruit_write 페이지에서만 처리
            goBackFromWrite();
        });

    });

    // contenteditable HTML을 SmartEditor2 포맷으로 정규화
    function normalizeContenteditableToSmartEditor(html) {
        if (!html || !html.trim()) {
            return '';
        }

        html = html.trim();

        // body 태그 제거
        html = html.replace(/<body[^>]*>([\s\S]*?)<\/body>/i, '$1');

        // 줄바꿈 처리 (줄바꿈을 </p><p>로 변환)
        html = html.replace(/\n\n+/g, '</p><p>');
        html = html.replace(/\n/g, '<br>');

        // 기본 <p> 감싸기
        if (html.indexOf('<p>') === -1) {
            html = '<p>' + html + '</p>';
        }

        // <p> 내부 텍스트에 기본 폰트 스타일 추가
        html = html.replace(/<p>([\s\S]*?)<\/p>/g, function(match, content) {
            // 이미 <span>이 있는 경우 확인
            if (content.indexOf('<span') !== -1) {
                // span에 폰트 스타일이 없으면 추가
                content = content.replace(/<span(?![^>]*style="[^"]*font-size)([^>]*)>/gi,
                    '<span style="font-size: 15pt;"$1>');
            } else {
                // span이 없으면 생성
                content = '<span style="font-size: 15pt;">' + content + '</span>';
            }
            return '<p>' + content + '</p>';
        });

        return html;
    }

    // 폼 제출 시 contenteditable div의 내용을 숨겨진 input에 저장
    $('form').on('submit', function(e) {
        var editorContent = document.getElementById('rc_desc_editor').innerHTML;
        var normalizedContent = normalizeContenteditableToSmartEditor(editorContent);
        document.getElementById('rc_desc_input').value = normalizedContent;
    });


</script>
<script src="/resource/shop/mobile/js/common-utils.js"></script>
<script>
  // 공고노출지역 팝업 콜백 - 현재 팝업 타입에 따라 해당 필드에만 저장
  window.onChangeAreaApply = function(data) {
    var pageType = window.__addrPopupPageType || 'default';

    // BrowserLogger.info('recruit_write1_1: onChangeAreaApply 실행', {
    //   pageType: pageType,
    //   hasSearchArea: !!(data && data.searchArea && data.searchArea.length > 0),
    //   dataKeys: data ? Object.keys(data) : []
    // });

    // expose1 또는 expose2인 경우만 처리
    if (pageType === 'expose1' || pageType === 'expose2') {
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
        var addr_short = (typeof fRemoveBigAdrJS === 'function') ? (fRemoveBigAdrJS(area.addr) || area.addr) : area.addr;
        // PC(recruit_write.php)와 동일하게 zipcode/jibun/doro_val에는 축약 전 전체 문자열을 저장
        $("#expose_addr" + fieldIndex + "_zipcode").val(area.addr);
        $("#expose_addr" + fieldIndex + "_jibun").val(area.addr);
        $("#expose_addr" + fieldIndex + "_doro_val").val(area.addr);
        $("#expose_addr" + fieldIndex + "_addr").val(addr_short);
        var $btn = $("#btnExposeAddr" + fieldIndex);
        // 변환된 주소로 표시
        $btn.find(".address-text").text(addr_short);
        $btn.addClass("has-address");
        $btn.closest(".address-btn-wrapper").find(".address-clear-btn").show();
        scrollAfterAddrFilled($btn);
        // 근무지 + 추가노출지역 1,2가 모두 채워지면 다음 단계로 자동 이동
        if (typeof window.autoAdvanceAddrIfComplete === 'function') {
          window.autoAdvanceAddrIfComplete();
        }
      }
    }

    savePage1Data();
    return true;
  };

  // 공고노출지역 팝업이 닫힐 때 body overflow 복원
  $(document).ready(function() {
    bindSalaryAmountFormat();

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

  // 휠 항목 탭 선택: 누른 항목을 중앙으로 스크롤 (휠 스크롤과 같은 결과, scroll 리스너가 시간-오전/오후 동기화를 처리)
  function initWheelClick(wheelCol) {
    wheelCol.addEventListener('click', function(e) {
      var item = e.target.closest('li');
      if (!item || !wheelCol.contains(item)) return;

      wheelCol.scrollTop = item.offsetTop - (wheelCol.clientHeight / 2 - 22);
      updateWheelActiveItem(wheelCol);
    });
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

    // 분 휠을 현재 분(5분 단위)로 스크롤
    if (wheelMinute) {
      var minuteIndex = Math.round(currentMinute / 5);
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

    // 휠 항목 탭 선택 초기화
    if (wheelAMPM) initWheelClick(wheelAMPM);
    if (wheelHour) initWheelClick(wheelHour);
    if (wheelMinute) initWheelClick(wheelMinute);

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

    // rc_desc_editor에서 Enter 키를 <br> 태그로 변환
    var rcDescEditor = document.getElementById('rc_desc_editor');
    if (rcDescEditor) {
      // 상세업무를 입력하는 즉시 상세등록 버튼이 나타나도록 갱신
      // 글자 입력 시 캐럿이 에디터 안과 푸터 위에 보이도록 한 번 맞춘다
      // (브라우저 기본 캐럿 따라가기는 고정 푸터를 모르기 때문에 푸터 밑까지 내려갈 수 있다)
      rcDescEditor.addEventListener('input', function() {
        // 입력 중에는 상세등록 버튼만 갱신한다 (updateStepVisibility(true)는 숨겨 둔 섹션을 한꺼번에 펼쳐 화면이 밀림)
        updateDetailMoreButton();
        revealDescCaret(getDescCaretRect());
      });

      // 현재 캐럿 줄의 화면 좌표. 접힌 selection의 좌표를 쓰고, 못 구하면(빈 줄 등) null
      // 한글 조합을 깨지 않도록 여기서는 DOM에 마커를 넣지 않는다
      function getDescCaretRect() {
        var selection = window.getSelection();
        if (!selection || selection.rangeCount === 0) return null;
        var range = selection.getRangeAt(0);
        if (!rcDescEditor.contains(range.endContainer)) return null;
        var rects = range.getClientRects();
        var rect = rects.length ? rects[rects.length - 1] : range.getBoundingClientRect();
        return (rect && rect.height > 0) ? rect : null;
      }

      // 상세업무 캐럿 줄(rect)이 보이도록 한 번만 맞춘다. 타이머·재판정 없음
      // 1) 에디터 자체 스크롤: 캐럿 줄이 에디터 안쪽(패딩 제외) 밖이면 에디터만 먼저 스크롤
      //    (Enter는 기본 동작을 막고 <br>을 직접 넣으므로 브라우저가 에디터 안 스크롤을 따라가지 않는다)
      // 2) 페이지 스크롤: 그래도 보이는 영역(topbar 아래 ~ 하단 푸터 위) 밖이면 벗어난 만큼만 .page를 스크롤
      //    아래 끝은 푸터 윗면과 키보드 윗면(visualViewport) 중 더 위, 위 끝은 topbar 실제 아래끝으로 잰다
      function revealDescCaret(rect) {
        var page = rcDescEditor.closest('.page');
        if (!page || !rect || rect.height === 0) return;
        var caretTop = rect.top;       // 캐럿 줄 윗면 (에디터 스크롤 후 보정된 값)
        var caretBottom = rect.bottom; // 캐럿 줄 아랫면 (에디터 스크롤 후 보정된 값)

        var editorRect = rcDescEditor.getBoundingClientRect();
        var editorStyle = window.getComputedStyle(rcDescEditor);
        var innerTop = editorRect.top + rcDescEditor.clientTop + (parseFloat(editorStyle.paddingTop) || 0);
        var innerBottom = editorRect.top + rcDescEditor.clientTop + rcDescEditor.clientHeight - (parseFloat(editorStyle.paddingBottom) || 0);
        var innerDelta = 0;
        if (caretBottom > innerBottom) {
          innerDelta = caretBottom - innerBottom;
        } else if (caretTop < innerTop) {
          innerDelta = caretTop - innerTop;
        }
        if (Math.abs(innerDelta) >= 1) {
          var prevInnerScroll = rcDescEditor.scrollTop;
          rcDescEditor.scrollTop = prevInnerScroll + innerDelta;
          var innerMoved = rcDescEditor.scrollTop - prevInnerScroll; // 스크롤 끝에서 잘린 경우를 반영한 실제 이동량
          caretTop -= innerMoved;
          caretBottom -= innerMoved;
        }

        var gap = 16; // 캐럿과 푸터/topbar 사이 여유 (px)
        var topBar = document.querySelector('.top-bar');
        var visibleTop = (topBar ? topBar.getBoundingClientRect().bottom : page.getBoundingClientRect().top) + gap;
        var visibleBottom = window.visualViewport
          ? window.visualViewport.offsetTop + window.visualViewport.height
          : window.innerHeight;
        // 키보드가 올라와도 푸터는 키보드 바로 위에 보이므로, 푸터 윗면을 실제 아래 끝으로 본다
        var footer = document.querySelector('.sticky-bottom.view-footer');
        if (footer) {
          var footerRect = footer.getBoundingClientRect();
          if (footerRect.height > 0 && footerRect.top < visibleBottom) {
            visibleBottom = footerRect.top;
          }
        }
        visibleBottom -= gap;
        var delta = 0;
        if (caretBottom > visibleBottom) {
          delta = caretBottom - visibleBottom;
        } else if (caretTop < visibleTop) {
          delta = caretTop - visibleTop;
        }
        if (Math.abs(delta) < 1) return;
        // .page의 smooth 스크롤이 끼면 대입 직후 위치가 밀리므로 이 보정만 즉시 적용한다
        page.style.scrollBehavior = 'auto';
        page.scrollTop = page.scrollTop + delta;
        page.style.scrollBehavior = '';
      }

      rcDescEditor.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          // 선택 영역이 있으면 제거하고 br 삽입
          var selection = window.getSelection();
          if (selection.rangeCount > 0) {
            var range = selection.getRangeAt(0);
            range.deleteContents();
            var br = document.createElement('br');
            range.insertNode(br);

            // Enter는 기본 동작을 막고 직접 <br>을 넣으므로 브라우저가 캐럿을 따라 스크롤하지 않는다
            // 캐럿 자리에 보이지 않는 마커를 잠깐 넣어 위치를 재고, 보이는 영역을 벗어난 만큼만 한 번 스크롤한 뒤 바로 지운다
            var caretMarker = document.createElement('span');
            caretMarker.textContent = '​';
            range.setStartAfter(br);
            range.collapse(true);
            range.insertNode(caretMarker);
            revealDescCaret(caretMarker.getBoundingClientRect());
            caretMarker.parentNode.removeChild(caretMarker);

            range.setStartAfter(br);
            range.collapse(true);
            selection.removeAllRanges();
            selection.addRange(range);
          }
        }
      });
    }
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
for ($m = 0; $m <= 55; $m += 5) {
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