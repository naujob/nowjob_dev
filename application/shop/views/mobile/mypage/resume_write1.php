<?php
// 카카오 키 가져오기 for Geocoding
$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
?>
<style>
    html.autofill-hide {
        visibility: hidden;
    }

    /* ========== 단계별 진행 UI (채용공고 등록 폼과 동일 패턴) ==========
       모든 선택자를 .page-resume-write 로 스코프해 다른 페이지 오염을 막는다 */

    /* 진행 게이지바 */
    .page-resume-write .step-progress-bar {
        position: fixed;
        top: 58px;
        left: 0;
        right: 0;
        z-index: 99;
        height: 7px;
        background: #eee;
        width: 100%;
    }

    .page-resume-write .step-progress-fill {
        height: 100%;
        background: #0bbf7a;
        transition: width 0.3s ease;
        width: 12.5%; /* 1 / 필수 8단계 */
    }

    /* 단계 질문 타이포 */
    .page-resume-write .step-question {
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 1.5rem;
        margin-top: 0;
    }

    .page-resume-write .step-question-main {
        font-size: 28px;
        color: #1B9D7D;
        font-weight: 600;
    }

    .page-resume-write .step-question-sub {
        font-size: 21px;
        color: #333;
        font-weight: 600;
    }

    .page-resume-write .step-section {
        margin-top: 28px;
    }

    /* 각 단계 입력 영역 카드 스타일은 main.css 공통 규칙(.page-resume-write *-field-wrapper) 사용 */

    /* 카드 안에서는 form-row 의 상단 여백을 줄인다 */
    .page-resume-write [class*="-field-wrapper"] > .form-row:first-child {
        margin-top: 0;
    }

    /* ===== 나의강점: 채용공고 해시태그(main.css:8884~8940)와 동일한 레이아웃 =====
       해시태그는 flex 그리드가 아니라 inline-block + min-width 33.333% 방식이다.
       다른 단계(희망업무/근무형태/급여형태)에는 영향이 없도록
       .strength-field-wrapper 안으로만 한정한다 */
    .page-resume-write .strength-field-wrapper .form-toggle-group.col-3 {
        margin-top: 0.875rem;
    }

 

    .page-resume-write .strength-field-wrapper .form-toggle-group.col-3 .form-toggle-btn2.tag .btn {
        min-width: calc(33.333% - 0.5rem);
        height: var(--resume-write-input-height);
        padding: 0 0.75rem;
        font-size: var(--resume-write-input-font-size);
        font-weight: 400;
        line-height: var(--resume-write-input-height);
        color: #222;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background: #fff;
        text-align: center;
    }

    .page-resume-write .strength-field-wrapper .form-toggle-group.col-3 .form-toggle-btn2.tag input:checked~.btn {
        color: #fff;
        font-weight: 500;
        border-color: #35c8a3;
        background-color: #35c8a3;
    }

    /* + 버튼: main.css:929 의 원형 아이콘 버튼을 태그와 같은 칸 모양으로 덮어쓴다 */
    .page-resume-write .strength-field-wrapper .form-toggle-group.col-3 .btn-add-more {
        display: inline-block;
        min-width: calc(33.333% - 0.5rem);
        height: var(--resume-write-input-height);
        padding: 0;
        margin-top: 0.8rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
        font-weight: 300;
        color: #999;
        line-height: var(--resume-write-input-height);
        text-align: center;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
        vertical-align: top;
        box-sizing: border-box;
    }

    .page-resume-write .strength-field-wrapper .form-toggle-group.col-3 .btn-add-more:hover,
    .page-resume-write .strength-field-wrapper .form-toggle-group.col-3 .btn-add-more:focus {
        border-color: #1B9D7D;
        color: #1B9D7D;
    }

    /* ===== 나의강점 직접입력 (채용공고 해시태그와 동일한 패턴) ===== */
    .page-resume-write .strength-guide-text {
        cursor: pointer;
    }

    .page-resume-write .strength-input-area {
        margin-top: 0.875rem;
        display: none;
    }

    .page-resume-write .strength-input-area.show {
        display: block;
    }

    .page-resume-write .strength-input-area .input-row {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .page-resume-write .strength-input-area .form-text {
        height: var(--resume-write-input-height);
        padding: 0 0.75rem;
        font-size: var(--resume-write-input-font-size);
        font-weight: 400;
        color: #222;
        line-height: var(--resume-write-input-height);
        border: 1px solid var(--write-border-color);
        border-radius: var(--write-border-radius);
        background: #fff;
        box-sizing: border-box;
    }

    .page-resume-write .strength-input-area .form-text:focus {
        outline: none;
        border-color: #1B9D7D;
    }

    .page-resume-write .strength-input-area .btn-confirm {
        height: 3rem;
        padding: 0 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        color: #fff;
        background-color: #1B9D7D;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        white-space: nowrap;
    }

    .page-resume-write .strength-input-area .btn-confirm:hover,
    .page-resume-write .strength-input-area .btn-confirm:focus {
        background-color: #158a6f;
    }

    /* 필수 표시(*)를 항목명 오른쪽에 두므로 여백 방향을 뒤집는다
       (main.css:1743 은 왼쪽 배치 기준의 margin-right 를 갖고 있다) */
    .page-resume-write .required-asterisk {
        margin-right: 0;
        margin-left: 2px;
    }

    /* 단계 이동 푸터: main.css 의 .page-resume-write .sticky-bottom.view-footer
       (position:relative; bottom:-83px; left:-9px; width:104%) 보다 선택자 우선순위가
       높아야 하므로 .section.addition-info-form 을 포함해 지정하고 관련 속성을 모두 재선언 */
    .page-resume-write .section.addition-info-form .sticky-bottom.view-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        top: auto;
        width: auto;
        display: flex;
        gap: 8px;
        align-items: center;
        padding: 1rem;
        background: #fff;
        border-top: 0;
        z-index: 50;
    }

    .page-resume-write .section.addition-info-form .view-footer::after {
        display: none;
    }

    /* 고정 푸터에 마지막 입력 항목이 가리지 않도록 하단 여백 확보 */
    .page-resume-write .section.addition-info-form .container {
        padding-bottom: 5.5rem;
    }

    /* 이전 / 상세등록: 테두리 스타일 */
    .page-resume-write #btnStepPrev,
    .page-resume-write #btnDetailMore {
        flex: 0 0 auto;
        display: inline-block;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        font-size: 1.3rem;
        background: #fff;
        color: #57CCAF;
        border: 1.5px solid #57CCAF;
        padding: 0.8rem 1rem;
        border-radius: 8px;
    }

    .page-resume-write #btnStepPrev:active,
    .page-resume-write #btnDetailMore:active {
        background: #f0f9f6;
    }

    .page-resume-write #btnDetailMore .add-icon {
        margin-right: 0.25rem;
    }

    /* 다음 / 미리보기: 채움 스타일. 이전 버튼이 숨겨지는 1단계에서도 우측 정렬 유지 */
    .page-resume-write #btnStepNext,
    .page-resume-write #btnStep {
        flex: 0 0 auto;
        position: static;
        left: auto;
        margin-left: auto;
        display: inline-block;
        font-weight: 700;
        color: #fff;
        text-align: center;
        vertical-align: middle;
        border: 0;
        background: #57CCAF;
        font-size: 1.3rem;
        padding: 0.8rem 1rem;
        border-radius: 8px;
        width: auto;
    }

    .page-resume-write #btnStepNext:active,
    .page-resume-write #btnStep:active {
        background-color: #38b898;
    }

    /* 제출 진행 중: 연타 방지 (상태는 data-state, 표현은 CSS) */
    .page-resume-write #btnStep[data-state="submitting"] {
        opacity: 0.5;
        pointer-events: none;
    }

    /* 상세등록 구간(9~11단계)의 표시 제어권을 step JS 로 넘긴다.
       main.css:9229 의 .js-detail-section{display:none} 은 .step-section 바깥 게이트와
       이중으로 걸려 step 전환을 막으므로 이 페이지에서만 해제한다 */
    .page-resume-write .form-row.my-strength-section + .form-row.js-detail-section,
    .page-resume-write .form-row.js-detail-section {
        display: block;
    }

    /* ========== 월/일/년 날짜 휠 바텀시트 ==========
       .time-wheel-picker / .wheel-col / .wheel-highlight-bar 기본 스타일은
       main.css:13857~ 전역 정의를 그대로 상속받는다 */

    /* 날짜 선택 트리거 버튼 (main.css 의 .js-time-picker-trigger 는 .page-recruit-write 한정이라 재사용 불가) */
    .page-resume-write .js-date-picker-trigger {
        display: block;
        width: 100%;
        text-align: left;
        background: #fff;
        border: 1px solid var(--write-border-color);
        border-radius: var(--write-border-radius);
        height: var(--resume-write-input-height);
        padding: 0.9rem;
        font-size: 1.1rem;
        color: #bbb;
        
    }
    .page-resume-write .js-date-picker-trigger .date-display {
        vertical-align: sub;
    }
    .page-resume-write .js-date-picker-trigger.has-value,
    .page-resume-write .js-date-picker-trigger[data-has-value="true"] {
        color: #333;
        font-weight: 600;
    }

    .page-resume-write .js-date-picker-trigger:active {
        background: #f7f7f7;
    }

    /* 경력/자격증 행 안의 트리거는 기존 input 과 같은 크기로 */
    .page-resume-write .added-list .js-date-picker-trigger {
        border: unset;
        height: auto;
        padding: 0.7rem 0.6rem;
        font-size: 1rem;
    }

    /* 3컬럼 배분: 월 / 일 / 년 */
    .date-wheel-picker .wheel-col-month {
        flex: 1.2;
    }

    .date-wheel-picker .wheel-col-day {
        flex: 1;
    }

    .date-wheel-picker .wheel-col-year {
        flex: 1.4;
    }

    /* 범위 밖 연도·존재하지 않는 일자는 display:none 이어야 offsetTop 계산이 맞는다 */
    .date-wheel-picker .wheel-col li.is-hidden {
        display: none;
    }

    /* 년/월만 고르는 모드에서는 일 컬럼을 숨긴다 */
    #datePickerSheet[data-mode="ym"] .wheel-col-day {
        display: none;
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
    <!-- 단계별 진행 게이지바 (전체 단계 수는 상세등록 토글에 따라 8 또는 11) -->
    <div class="step-progress-bar">
        <div class="step-progress-fill" id="stepProgressFill"></div>
    </div>
    <div class="page-content">
        <form id="frm" onsubmit="return false" autocomplete="false">
            <input type="hidden" name="ru_work_img" value="" />
            <input type="hidden" name="quickinfo_gender" value="" />
            <input type="hidden" name="quickinfo_age" value="" />
            <input type="hidden" name="arr_career" value="" />
            <input type="hidden" name="arr_certi" value="" />
            <div class="section addition-info-form">
                <div class="container">
                    <!-- 단계 1: 이력서명 -->
                    <div class="step-section" data-step="1">
                        <p class="step-question">
                            <span class="step-question-main">어떤 일자리를</span><br>
                            <span class="step-question-sub">찾으시나요?</span>
                        </p>
                        <div class="title-field-wrapper">
                            <div class="form-row">
                                <div class="resume-title-header">
                                    <label>
                                        <span class="form-label">이력서명<span class="required-asterisk">*</span></span>
                                    </label>
                                    <span class="char-counter"><span id="ru_name_count">0</span>/30</span>
                                </div>
                                <label>
                                    <input type="text" name="ru_name" placeholder="이력서제목을 입력해주세요" value="<?= $info["ru_name"] ?>" maxlength="30" autocomplete="off" aria-required="true" required class="form-text form-text-bordered" id="ru_name_input">
                                </label>
                            </div>
                            <!-- 국적은 회원가입 때 받은 st_member.mm_nation 을 쓰므로 여기서 입력받지 않는다 -->
                        </div>
                    </div><!-- //step 1 -->

                    <!-- 단계 2: 희망지역 -->
                    <div class="step-section" data-step="2" style="display:none;">
                        <p class="step-question">
                            <span class="step-question-main">희망근무 지역을</span><br>
                            <span class="step-question-sub">입력해주세요</span>
                        </p>
                        <div class="area-field-wrapper">
                            <div class="form-row">
                                <p class="form-label">희망지역<span class="required-asterisk">*</span></p>
                                <div class="location-field-group">
                                    <ul class="added-location-new" id="addr_location">
                                        <!-- 주소는 JavaScript에서만 렌더링. 최대 2개이므로 빈 슬롯 2개로 시작한다 -->
                                        <li class="location-field-item location-field-add">
                                            <a href="javascript:void(0);" onclick="showAddrInfo(2);" role="button" class="location-add-btn">
                                                <span class="add-icon">+</span>
                                            </a>
                                        </li>
                                        <li class="location-field-item location-field-add">
                                            <a href="javascript:void(0);" onclick="showAddrInfo(2);" role="button" class="location-add-btn">
                                                <span class="add-icon">+</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div><!-- //step 2 -->

                    <!-- 단계 3: 희망업무 -->
                    <div class="step-section" data-step="3" style="display:none;">
                        <p class="step-question">
                            <span class="step-question-main">희망하는 업종을</span><br>
                            <span class="step-question-sub">선택해주세요</span>
                        </p>
                        <div class="work-field-wrapper">
                    <div class="form-row">
                        <p class="form-label">희망업무<span class="required-asterisk">*</span></p>
                        <div class="form-toggle-group col-3">
                            <?php
                            // 노출 순서를 화면 기준으로 고정한다 (3열 x 3행)
                            $work_order_list = ["생산", "물류", "건설", "운전배달", "식당", "서비스", "사무직", "상담", "기타"];

                            // 코드 목록을 값 기준으로 1건씩만 추려 이미지 경로를 찾는다.
                            // 코드 테이블에 같은 값이 중복 등록돼 있어도 한 번만 렌더링된다
                            $work_img_map = [];
                            foreach ($ru_wrok_list as $row) {
                                $cd_val = isset($row["cd_val"]) ? trim($row["cd_val"]) : "";
                                if ($cd_val === "" || array_key_exists($cd_val, $work_img_map)) {
                                    continue;
                                }
                                $work_img_map[$cd_val] = isset($row["cd_val2"]) ? $row["cd_val2"] : "";
                            }

                            foreach ($work_order_list as $work_val) :
                                // 코드 테이블에 없는 항목도 화면에는 노출하되 이미지 경로만 비워 둔다
                                $work_img = array_key_exists($work_val, $work_img_map) ? $work_img_map[$work_val] : "";
                            ?>
                                <label class="form-toggle-btn2 sm">
                                    <!--
                                        희망업무 체크박스
                                        name="ru_work[]": 사용자가 선택한 희망업무를 배열로 전송
                                        data-select-limit="2": 최대 2개까지 선택 가능하도록 JS에서 제어
                                        value: 업무 코드값(예: 생산, 사무직 등)
                                        data-img: 각각의 업무에 해당하는 이미지 경로(썸네일 등)
                                    -->
                                    <input type="checkbox" name="ru_work[]" data-select-limit="2" value="<?= $work_val ?>" data-img="<?= $work_img ?>" <?= in_array_return($work_val, $info["ru_work"], "check") ?>>
                                    <span class="btn"><?= $work_val ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                        </div>
                    </div><!-- //step 3 -->

                    <!-- 단계 4: 나의강점 -->
                    <div class="step-section" data-step="4" data-scroll="top" style="display:none;">
                        <p class="step-question">
                            <span class="step-question-main">희망하는 나의 강점을</span><br>
                            <span class="step-question-sub">선택해주세요</span>
                        </p>
                        <div class="strength-field-wrapper">
                            <div class="form-row my-strength-section">
                                <p class="form-label">나의강점<span class="required-asterisk">*</span></p>
                                <div class="form-toggle-group col-3">
                                    <?php
                                    // 기본 노출 강점 (채용공고 해시태그와 동일하게 고정 목록으로 관리)
                                    $default_my_type_list = ["당일지급", "초보가능", "가불가능", "경력우대", "식사제공", "동반입사"];
                                    $info_ru_my_type = isset($info["ru_my_type"]) ? (is_array($info["ru_my_type"]) ? array_map('trim', $info["ru_my_type"]) : array_map('trim', explode(",", $info["ru_my_type"]))) : [];

                                    foreach ($default_my_type_list as $my_type) :
                                    ?>
                                        <label class="form-toggle-btn2 sm tag">
                                            <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?= $my_type ?>" <?= in_array_return($my_type, $info_ru_my_type, "check") ?>>
                                            <span class="btn"><?= $my_type ?></span>
                                        </label>
                                    <?php endforeach; ?>

                                    <?php if (!empty($info_ru_my_type)) : ?>
                                        <?php foreach ($info_ru_my_type as $row) : ?>
                                            <?php
                                            // 기본 목록에 없는 값 = 직접입력으로 추가했던 강점
                                            $row = trim((string)$row);
                                            if ($row !== "" && !in_array($row, $default_my_type_list)) :
                                            ?>
                                                <label class="form-toggle-btn2 sm tag">
                                                    <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?= $row ?>" checked>
                                                    <span class="btn"><?= $row ?></span>
                                                </label>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <button type="button" aria-haspopup="dialog" class="btn-add-more js-add-strength">+<span class="sr-text">추가작성</span></button>
                                    <label class="form-toggle-btn2 sm tag" style="width: 64%;">
                                        <span class="btn strength-guide-text">나의강점을 직접입력해주세요</span>
                                    </label>
                                </div>
                                <div class="strength-input-area">
                                    <div class="input-row">
                                        <input type="text" class="form-text js-strength-input" placeholder="직접입력" maxlength="20">
                                        <button type="button" class="btn-confirm js-confirm-strength">확인</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- //step 4 -->

                    <!-- 단계 5: 근무형태 -->
                    <div class="step-section" data-step="5" style="display:none;">
                        <p class="step-question">
                            <span class="step-question-main">희망하는 근무형태를</span><br>
                            <span class="step-question-sub">선택해주세요</span>
                        </p>
                        <div class="worktype-field-wrapper">
                            <!-- 20221003 수정 -->
                            <div class="form-row">
                                <p class="form-label form-label-with-checkbox">
                                    <span>근무형태<span class="required-asterisk">*</span></span>
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
                        </div>
                    </div><!-- //step 5 -->

                    <!-- 단계 6: 급여형태 -->
                    <div class="step-section" data-step="6" style="display:none;">
                        <p class="step-question">
                            <span class="step-question-main">희망하는 급여를</span><br>
                            <span class="step-question-sub">선택해주세요</span>
                        </p>
                        <div class="salary-field-wrapper">
                            <div class="form-row">
                                <p class="form-label form-label-with-checkbox">
                                    <span>급여형태<span class="required-asterisk">*</span></span>
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
                        </div>
                    </div><!-- //step 6 -->
                    <!-- 단계 7: 자기소개 -->
                    <div class="step-section" data-step="7" data-scroll="top" style="display:none;">
                        <p class="step-question">
                            <span class="step-question-main">이력서의 자기소개란을</span><br>
                            <span class="step-question-sub">입력해주세요</span>
                        </p>
                        <div class="desc-field-wrapper">
                            <div class="form-row intro-section">
                                <p class="form-label">자기소개<span class="required-asterisk">*</span></p>
                                <label>
                                    <span class="sr-text">자기소개 입력</span>
                                    <textarea name="ru_desc" maxlength="500" class="form-textarea js-autoheight"><?= $info["ru_desc"] ?></textarea>
                                </label>
                            </div>
                        </div>
                    </div><!-- //step 7 -->

                    <!-- 단계 8: 근무시작일 (마지막 필수 단계) -->
                    <?php
                    // 근무시작일 기본값: 저장된 값이 없으면 오늘 날짜로 휠 초기 위치를 잡는다
                    $ru_work_dt_val = (isset($info["ru_work_dt"]) && $info["ru_work_dt"] != "") ? substr($info["ru_work_dt"], 0, 10) : "";
                    $ru_work_dt_seed = $ru_work_dt_val != "" ? $ru_work_dt_val : date("Y-m-d");
                    ?>
                    <div class="step-section" data-step="8" style="display:none;">
                        <p class="step-question">
                            <span class="step-question-main">근무시작일을</span><br>
                            <span class="step-question-sub">선택해주세요</span>
                        </p>
                        <div class="workdt-field-wrapper">
                            <div class="form-row">
                                <p class="form-label">근무시작일<span class="required-asterisk">*</span></p>
                                <input type="hidden" name="ru_work_dt" value="<?= $ru_work_dt_val ?>">
                                <button type="button"
                                        class="js-date-picker-trigger js-toggle-panel"
                                        aria-controls="datePickerSheet"
                                        data-date-mode="single"
                                        data-date-format="dash"
                                        data-date-title="근무시작일을 선택해주세요"
                                        data-year-min="<?= (int)date("Y") - 1 ?>"
                                        data-year-max="<?= (int)date("Y") + 2 ?>"
                                        data-date-seed="<?= $ru_work_dt_seed ?>"
                                        data-target="[name='ru_work_dt']"><span class="date-display"><?= $ru_work_dt_val != "" ? str_replace("-", ".", $ru_work_dt_val) : "날짜를 선택해주세요" ?></span></button>
                            </div>
                        </div>
                    </div><!-- //step 8 -->
                    <!-- 단계 9: 희망요일 (상세등록 구간) -->
                    <div class="step-section" data-step="9" style="display:none;">
                        <p class="step-question">
                            <span class="step-question-main">희망하는 요일을</span><br>
                            <span class="step-question-sub">선택해주세요</span>
                        </p>
                        <div class="week-field-wrapper">
            <div class="form-row js-detail-section">
                <p class="form-label">
                    희망요일<span class="required-asterisk">*</span>
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
                        </div>
                    </div><!-- //step 9 -->

                    <!-- 단계 10: 경력사항 (상세등록 구간) -->
                    <div class="step-section" data-step="10" data-scroll="bottom" style="display:none;">
                        <p class="step-question">
                            <span class="step-question-main">경력사항을</span><br>
                            <span class="step-question-sub">입력해주세요</span>
                        </p>
                        <div class="career-field-wrapper">
            <div class="form-row js-detail-section" data-section="career">
                <p class="form-label">경력사항</p>
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
                                    <div class="col">
                                        <label>
                                            <span class="sr-text">업체명 입력</span>
                                            <input type="text" name="career_company[]" maxlength="13" placeholder="업체명" value="<?= isset($arr_temp[0]) ? htmlspecialchars($arr_temp[0], ENT_QUOTES, 'UTF-8') : "" ?>" autocomplete="off" class="form-text">
                                        </label>
                                    </div>
                                    <div class="col">
                                        <?php $career_period_val = isset($arr_temp[1]) ? htmlspecialchars($arr_temp[1], ENT_QUOTES, 'UTF-8') : ""; ?>
                                        <input type="hidden" name="career_period[]" value="<?= $career_period_val ?>">
                                        <button type="button"
                                                class="js-date-picker-trigger js-toggle-panel<?= $career_period_val != "" ? " has-value" : "" ?>"
                                                aria-controls="datePickerSheet"
                                                data-date-mode="range"
                                                data-date-title="근무 시작일을 선택해주세요"
                                                data-date-title2="근무 종료일을 선택해주세요"
                                                data-year-min="1960"
                                                data-year-max="<?= (int)date("Y") ?>"><span class="date-display"><?= $career_period_val != "" ? $career_period_val : "근무기간" ?></span></button>
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
                            <!-- 업종 선택 미사용 예정
                            <div class="col">
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
                            -->
                            <div class="col">
                                <label>
                                    <span class="sr-text">업체명 입력</span>
                                    <input type="text" name="career_company[]" maxlength="13" placeholder="업체명" autocomplete="off" class="form-text">
                                </label>
                            </div>
                            <div class="col">
                                <input type="hidden" name="career_period[]" value="">
                                <button type="button"
                                        class="js-date-picker-trigger js-toggle-panel"
                                        aria-controls="datePickerSheet"
                                        data-date-mode="range"
                                        data-date-title="근무 시작일을 선택해주세요"
                                        data-date-title2="근무 종료일을 선택해주세요"
                                        data-year-min="1960"
                                        data-year-max="<?= (int)date("Y") ?>"><span class="date-display">근무기간</span></button>
                            </div>
                        </div>
                        <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                    </li>
                </template>
            </div>
                        </div>
                    </div><!-- //step 10 -->

                    <!-- 단계 11: 자격증 (상세등록 구간) -->
                    <div class="step-section" data-step="11" data-scroll="bottom" style="display:none;">
                        <p class="step-question">
                            <span class="step-question-main">자격증을</span><br>
                            <span class="step-question-sub">입력해주세요</span>
                        </p>
                        <div class="license-field-wrapper">
            <div class="form-row js-detail-section" data-section="license">
                <p class="form-label">자격증</p>
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
                                            <input type="text" name="license[]" maxlength="13" placeholder="자격증명" value="<?= isset($arr_temp[0]) ? htmlspecialchars($arr_temp[0], ENT_QUOTES, 'UTF-8') : "" ?>" autocomplete="off" class="form-text name">
                                        </label>
                                    </div>
                                    <div class="col">
                                        <?php $regdate_val = isset($arr_temp[1]) ? htmlspecialchars($arr_temp[1], ENT_QUOTES, 'UTF-8') : ""; ?>
                                        <input type="hidden" name="regdate[]" value="<?= $regdate_val ?>">
                                        <button type="button"
                                                class="js-date-picker-trigger js-toggle-panel<?= $regdate_val != "" ? " has-value" : "" ?>"
                                                aria-controls="datePickerSheet"
                                                data-date-mode="month"
                                                data-date-title="취득일자를 선택해주세요"
                                                data-year-min="1960"
                                                data-year-max="<?= (int)date("Y") ?>"><span class="date-display"><?= $regdate_val != "" ? $regdate_val : "취득일자" ?></span></button>
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
                                <input type="hidden" name="regdate[]" value="">
                                <button type="button"
                                        class="js-date-picker-trigger js-toggle-panel"
                                        aria-controls="datePickerSheet"
                                        data-date-mode="month"
                                        data-date-title="취득일자를 선택해주세요"
                                        data-year-min="1960"
                                        data-year-max="<?= (int)date("Y") ?>"><span class="date-display">취득일자</span></button>
                            </div>
                        </div>
                        <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                    </li>
                </template>
            </div>
                        </div>
                    </div><!-- //step 11 -->

            <!-- 단계 이동 푸터: 상세등록(+)은 마지막 필수 단계(8)에서만 노출 -->
            <div class="sticky-bottom view-footer">
                <button id="btnStepPrev" type="button" style="display:none;">이전</button>
                <button id="btnDetailMore" type="button" style="display:none;"><span class="add-icon">+</span>상세등록</button>
                <button id="btnStepNext" type="button">다음</button>
                <button id="btnStep" type="button" class="btn btn-primary" style="display:none;">미리보기</button>
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

                    // 단계 되돌리기는 푸터의 '이전' 버튼이 담당한다.
                    // 상단 뒤로가기는 현재 단계와 무관하게 페이지를 벗어난다
                    var ruIdx = $("[name='ru_idx']").val() || "0";
                    // clearResumeDraft 함수가 정의되어 있으면 호출
                    if (typeof clearResumeDraft === 'function') {
                        clearResumeDraft(ruIdx);
                    }
                    // 미리보기 복귀 표시도 함께 정리 (다음 진입에 잘못 적용되지 않도록)
                    if (typeof consumePreviewFlag === 'function') {
                        consumePreviewFlag();
                    }
                    // history.back() 은 미리보기를 거친 경우 엉뚱한 이력으로 되돌아가므로
                    // 목록으로 직접 이동한다. _replace 는 작성 페이지를 이력에서 대체해 재진입을 막는다
                    _replace("/recruit/job_list");
                    return false;
                }
            }
        }, true); // 캡처 단계에서 실행
    })();

    // 이력서 드래프트 저장소 키 생성
    function getResumeDraftKey(step) {
        var ruIdx = $("[name='ru_idx']").val() || "0";
        return "resume_draft_" + ruIdx + "_step" + step;
    }

    // 드래프트 데이터를 새로운 인덱스로 마이그레이션
    function migrateResumeDraftKeys(oldIdx, newIdx, steps) {
        if (!oldIdx || !newIdx || oldIdx === newIdx) return;
        (steps || []).forEach(function(step) {
            var oldKey = "resume_draft_" + oldIdx + "_step" + step;
            var newKey = "resume_draft_" + newIdx + "_step" + step;
            var saved = sessionStorage.getItem(oldKey);
            if (saved && !sessionStorage.getItem(newKey)) {
                sessionStorage.setItem(newKey, saved);
            }
            sessionStorage.removeItem(oldKey);
        });
    }

    // 이력서 드래프트 데이터 삭제
    function clearResumeDraft(ruIdx) {
        var targetIdx = ruIdx || $("[name='ru_idx']").val() || "0";
        ["resume_draft_" + targetIdx + "_step1", "resume_draft_" + targetIdx + "_step4"].forEach(function(key) {
            sessionStorage.removeItem(key);
        });
    }

    // 주소 정보를 HTML 리스트 아이템으로 변환
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
        var addr_short = (typeof fRemoveBigAdrJS === 'function') ? (fRemoveBigAdrJS(jibun) || jibun) : jibun;
        displayAddr = displayAddr + (detail ? " " + detail : "");

        return '' +
            '<li class="location-field-item">' +
            '<input type="hidden" name="ad_zipcode[]" value="' + zipcode + '" />' +
            '<input type="hidden" name="ad_jibun[]" value="' + jibun + '" />' +
            '<input type="hidden" name="ad_doro[]" value="' + doro + '" />' +
            // ad_addr에는 축약된 주소를 저장
            '<input type="hidden" name="ad_addr[]" value="' + addr_short + '" />' +
            '<input type="hidden" name="ad_detail[]" value="' + detailValue + '" />' +
            '<input type="hidden" name="ad_gps_x[]" value="' + gpsX + '" />' +
            '<input type="hidden" name="ad_gps_y[]" value="' + gpsY + '" />' +
            '<span class="location-text">' + displayAddr + '</span>' +
            '<button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>' +
            '</li>';
    }

    // 남은 자리만큼 빈 슬롯(+)을 채운다. 희망지역은 최대 2개이므로
    // 주소가 없으면 +가 2개, 1개를 고르면 +가 1개 남는다
    function ensureAddressAddButton(currentCount) {
        var $list = $("#addr_location");
        $list.find(".location-field-add").remove();

        var addHtml = '' +
            '<li class="location-field-item location-field-add">' +
            '<a href="javascript:void(0);" onclick="showAddrInfo(2);" role="button" class="location-add-btn">' +
            '<span class="add-icon">+</span>' +
            '</a>' +
            '</li>';

        for (var i = currentCount; i < 2; i++) {
            $list.append(addHtml);
        }
    }

    // 주소 목록을 초기화하고 재구성
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

    // step1 (기본정보, 근무조건) 데이터를 sessionStorage에 저장
    function saveResumeStep1Data() {
        try {
            var storageKey = getResumeDraftKey(1);
            var data = {
                ru_name: $("[name='ru_name']").val() || "",
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

            sessionStorage.setItem(storageKey, JSON.stringify(data));
        } catch (e) {
            console.error("resume step1 저장 실패:", e);
        }
    }

    // step4 (자기소개, 경력, 자격증) 데이터를 sessionStorage에 저장
    function saveResumeStep4Data() {
        try {
            var storageKey = getResumeDraftKey(4);
            var data = {
                ru_desc: $("[name='ru_desc']").val() || "",
                ru_my_type: [],
                ru_week_yn: $("[name='ru_week_yn']").is(":checked") ? "Y" : "N",
                ru_week: [],
                ru_work_dt: $("[name='ru_work_dt']").val() || "",
                detail_open: $(".js-detail-section").hasClass("show"),
                // 현재 단계를 함께 보관해 새로고침/미리보기 왕복 후 같은 위치로 복귀한다
                current_step: (typeof currentStep !== "undefined" ? currentStep : 1),
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

            sessionStorage.setItem(storageKey, JSON.stringify(data));
        } catch (e) {
            console.error("resume step4 저장 실패:", e);
        }
    }

    // 빈 폼 그룹을 가진 리스트 아이템 제거
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

    // 경력사항 데이터를 목록으로 렌더링
    function renderCareerItems(items) {
        var $list = $(".form-row[data-section='career'] .added-list");
        if ($list.length === 0 || !Array.isArray(items) || items.length === 0) return;
        $list.empty();

        items.forEach(function(item) {
            var template = $("#careerTemp").html();
            if (!template) return;
            var $node = $(template);
            $node.find("[name='career_company[]']").val(item.company || "");
            $node.find("[name='career_period[]']").val(item.period || "");
            // hidden 값에 맞춰 날짜 버튼 표시 텍스트도 갱신
            if (typeof setDateTriggerDisplay === "function") {
                setDateTriggerDisplay($node.find("[name='career_period[]']"), item.period || "", "근무기간");
            }
            if (typeof initSelectbox === 'function') {
                var $selectbox = $node.find(".js-career-category");
                if ($selectbox.length > 0) {
                    initSelectbox($selectbox[0]);
                }
            }
            $list.append($node);
        });
    }

    // 자격증 데이터를 목록으로 렌더링
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
            if (typeof setDateTriggerDisplay === "function") {
                setDateTriggerDisplay($node.find("[name='regdate[]']"), item.regdate || "", "취득일자");
            }
            $list.append($node);
        });
    }

    // sessionStorage에서 step4 데이터 로드하여 폼에 적용
    function loadResumeStep4Data() {
        var storageKey = getResumeDraftKey(4);
        var saved = sessionStorage.getItem(storageKey);

        // 수정 모드 판별 (step1과 동일한 기준)
        var ruIdx = $("[name='ru_idx']").val() || "0";
        var ruName = $("[name='ru_name']").val() || "";
        var isEditMode = (ruIdx != "0" && ruName != "");

        var defaultWeekDays = ["월", "화", "수", "목", "금"];
        var hasSavedData = false;

        // 신규 모드에서만 sessionStorage 복원 수행
        if (!isEditMode && saved) {
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
                            var $checkbox = $("[name='ru_my_type[]'][value='" + val + "']");
                            if ($checkbox.length > 0) {
                                $checkbox.prop("checked", true);
                            } else {
                                // 직접입력으로 추가했던 강점은 DOM 에 없으므로 다시 만들어 준다
                                var $newLabel = $('<label class="form-toggle-btn2 sm tag">' +
                                    '<input type="checkbox" name="ru_my_type[]" data-select-limit="6" checked>' +
                                    '<span class="btn"></span>' +
                                    '</label>');
                                $newLabel.find("input").val(val);
                                $newLabel.find(".btn").text(val);
                                if ($(".js-add-strength").length > 0) {
                                    $(".js-add-strength").before($newLabel);
                                }
                            }
                        });
                    }

                    // 근무시작일 복원 (hidden + 버튼 표시 텍스트)
                    if (formData.ru_work_dt) {
                        $("[name='ru_work_dt']").val(formData.ru_work_dt);
                        if (typeof setDateTriggerDisplay === "function") {
                            setDateTriggerDisplay($("[name='ru_work_dt']"), formData.ru_work_dt, "날짜를 선택해주세요");
                        }
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

        if (!isEditMode && !hasSavedData) {
            $("[name='ru_week[]']").prop("checked", false);
            defaultWeekDays.forEach(function(val) {
                $("[name='ru_week[]'][value='" + val + "']").prop("checked", true);
            });
            saveResumeStep4Data();
        }

        // 신규 모드에서만 career, license 데이터 복원
        if (!isEditMode && saved && hasSavedData) {
            try {
                var formData = JSON.parse(saved);
                if (formData) {
                    if (formData.detail_open) {
                        $(".js-detail-section").addClass("show").show();
                    }

                    if (Array.isArray(formData.arr_career)) {
                        // 1단계: 먼저 hidden input에 string 형식으로 설정 (이벤트 발생 전에!)
                        if (formData.arr_career.length > 0) {
                            var arr_career_str = formData.arr_career.map(function(item) {
                                return (item.company || "") + "|||" + (item.period || "");
                            }).join("&&&");
                            $("[name=arr_career]").val(arr_career_str);
                        } else {
                            $("[name=arr_career]").val("");
                        }

                        // 2단계: 그 후에 form 시각적 렌더링
                        $(".form-row[data-section='career'] .added-list").empty();
                        if (formData.arr_career.length > 0) {
                            renderCareerItems(formData.arr_career);
                        }
                    }

                    if (Array.isArray(formData.arr_certi)) {
                        // 1단계: 먼저 hidden input에 string 형식으로 설정
                        if (formData.arr_certi.length > 0) {
                            var arr_certi_str = formData.arr_certi.map(function(item) {
                                return (item.license || "") + "|||" + (item.regdate || "");
                            }).join("&&&");
                            $("[name=arr_certi]").val(arr_certi_str);
                        } else {
                            $("[name=arr_certi]").val("");
                        }

                        // 2단계: 그 후에 form 시각적 렌더링
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

    // 서버에서 받은 주소 데이터를 로드 (수정 모드일 때만)
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

    // sessionStorage에서 step1 데이터 로드하여 폼에 적용
    function loadResumeStep1Data() {
        var storageKey = getResumeDraftKey(1);
        var saved = sessionStorage.getItem(storageKey);
        if (!saved) return;

        try {
            var formData = JSON.parse(saved);
            if (!formData) return;

            if (typeof formData.ru_name !== "undefined") {
                $("[name='ru_name']").val(formData.ru_name);
                $("#ru_name_count").text($("#ru_name_input").val().length);
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

    // 주소 문자열에서 마지막 동 이름 추출
    function getDongFromAddr(addr) {
        if (!addr || typeof addr !== "string") return "";
        var tokens = addr.trim().split(/\s+/);
        return tokens.length ? tokens[tokens.length - 1] : "";
    }

    // 자동완성 파라미터에 따라 폼 자동 입력
    function applyResumeAutoFill() {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('autofill') !== '1') return;
        var isBgAutofill = (urlParams.get('bg') === '1');

        // 자동작성 모드에서는 화면을 숨겨 플리커를 방지
        document.documentElement.style.visibility = 'hidden';
        // logWebview 제거

        var payload = {};
        try {
            payload = JSON.parse(sessionStorage.getItem("resume_autofill_payload") || "{}");
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

        // 근무시작일은 필수값이므로 자동작성 경로에서도 반드시 채워야 제출이 막히지 않는다
        if (($("[name='ru_work_dt']").val() || "") === "") {
            var autoWorkDt = payload.ru_work_dt || "";
            if (autoWorkDt === "") {
                var today = new Date();
                autoWorkDt = today.getFullYear() + "-" +
                    ("0" + (today.getMonth() + 1)).slice(-2) + "-" +
                    ("0" + today.getDate()).slice(-2);
            }
            $("[name='ru_work_dt']").val(autoWorkDt);
            if (typeof setDateTriggerDisplay === "function") {
                setDateTriggerDisplay($("[name='ru_work_dt']"), autoWorkDt, "날짜를 선택해주세요");
            }
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
            sessionStorage.setItem("resume_autofill_payload", JSON.stringify(payload));
            sessionStorage.setItem("resume_autofill_ts", String(Date.now()));
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

    // 팝업에서 선택한 주소를 이력서에 추가
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
            var addr_short = (typeof fRemoveBigAdrJS === 'function') ? (fRemoveBigAdrJS(jibun) || jibun) : jibun;
            var item = {
                ad_zipcode: zipcode,
                ad_jibun: jibun,
                ad_doro: doro,
                // ad_addr에는 축약된 주소를 저장
                ad_addr: addr_short,
                ad_detail: detail,
                ad_gps_x: finalX,
                ad_gps_y: finalY
            };
            var html = createAddressListItem(item);
            if (html === "") {
                $("#changeArea").removeClass("on");
                return;
            }

            // 빈 슬롯이 여러 개이므로 반드시 first() 를 써야 중복 삽입되지 않는다
            var $addBtn = $("#addr_location .location-field-add").first();
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

    // 지역 선택 팝업 열기
    function showAddrInfo(maxlen) {
        var currentCount = $("#addr_location .location-field-item:not(.location-field-add)").length;
        if (currentCount >= maxlen) {
            commonAlert("희망지역은 " + maxlen + "개까지 등록 가능합니다.", function() {});
            return;
        }
        // 팝업 오픈 시 UI 초기화 (각 버튼마다 실행)
        window.__addrPopupPageType = 'resume_write';
        if (typeof window.initAddrPopupUI === 'function') {
            window.initAddrPopupUI();
        }
        window.__hideAllRegionsInAddrPopup = true;
        if (typeof window.applyAddrPopupAllRegionFilter === 'function') {
            window.applyAddrPopupAllRegionFilter();
        }
        $("#changeArea").addClass("on");
    }

    window.onChangeAreaApply = function(payload) { // 
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
            var addr_short = (typeof fRemoveBigAdrJS === 'function') ? (fRemoveBigAdrJS(addr) || addr) : addr;
            var item = {
                ad_zipcode: "",
                ad_jibun: addr,
                ad_doro: "",
                ad_addr: addr_short,
                ad_detail: "",
                ad_gps_x: x,
                ad_gps_y: y
            };
            var html = createAddressListItem(item);
            if (html === "") return;
            // 빈 슬롯이 여러 개이므로 반드시 first() 를 써야 중복 삽입되지 않는다
            var $addBtn = $("#addr_location .location-field-add").first();
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
        // addr_popup 페이지 타입 설정 (이력서 작성 페이지)
        window.__addrPopupPageType = 'resume_write';

        // addr_popup UI 초기화 (다른 페이지에서 돌아온 경우 선택 지역 초기화)
        if (typeof window.initAddrPopupUI === 'function') {
            window.initAddrPopupUI();
        }

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

    // 경력/자격증 문자열 생성 및 저장 (전역 함수로 정의 - saveStep4AndPreview와 showPreviewInfo의 secondCallback에서 공용으로 사용)
    function buildAndSaveCareerCertiStrings() {
        // 상세등록을 닫은 상태라면 경력/자격증은 입력하지 않은 것으로 본다.
        // (닫아도 DOM 과 입력값은 남아 있어 그대로 두면 숨은 값이 저장된다)
        if (typeof isDetailOpen === "function" && !isDetailOpen()) {
            $("[name=arr_career]").val("");
            $("[name=arr_certi]").val("");
            return;
        }

        var arr_career = "";
        var $careerList = $(".form-row[data-section='career'] .added-list");

        $careerList.children("li").each(function(idx) {
            var $li = $(this);
            var isDeleted = $li.hasClass('is-deleted');
            var company = $li.find("[name='career_company[]']").val();
            var period = $li.find("[name='career_period[]']").val();

            if (!isDeleted) {
                // "undefined" 문자열 필터링
                if (company === 'undefined' || company === undefined) company = "";
                if (period === 'undefined' || period === undefined) period = "";

                // 회사명 또는 기간 중 하나라도 있으면 저장
                if (company != "" || period != "") {
                    if (arr_career == "") {
                        arr_career = company + "|||" + period;
                    } else {
                        arr_career = arr_career + "&&&" + company + "|||" + period;
                    }
                }
            }
        });
        $("[name=arr_career]").val(arr_career);

        var arr_certi = "";
        var $licenseList = $(".form-row[data-section='license'] .added-list");

        $licenseList.children("li").each(function(idx) {
            var $li = $(this);
            var isDeleted = $li.hasClass('is-deleted');
            var license = $li.find("[name='license[]']").val();
            var regdate = $li.find("[name='regdate[]']").val();

            if (!isDeleted) {
                // "undefined" 문자열 필터링
                if (license === 'undefined' || license === undefined) license = "";
                if (regdate === 'undefined' || regdate === undefined) regdate = "";

                // 자격증명 또는 취득일 중 하나라도 있으면 저장
                if (license != "" || regdate != "") {
                    if (arr_certi == "") {
                        arr_certi = license + "|||" + regdate;
                    } else {
                        arr_certi = arr_certi + "&&&" + license + "|||" + regdate;
                    }
                }
            }
        });
        $("[name=arr_certi]").val(arr_certi);
    }

    /* ==================================================================
       단계별 진행(progress bar) 제어
       - 1~8 은 필수, 9~11(희망요일/경력/자격증)은 "상세등록" 선택 구간
       - 전체 단계 수는 상세등록 ON/OFF 에 따라 8 또는 11 로 달라진다
       ================================================================== */
    var currentStep = 1;
    var REQUIRED_STEPS = 8; // 필수 구간의 마지막 단계
    var MAX_STEPS = 11; // 상세등록 포함 전체 단계
    var STEP_TOP_OFFSET = 100; // 상단바 58 + 진행바 7 + 여유
    var STEP_TOP_OFFSET_WIDE = 130; // data-scroll="top" 단계용
    var STEP_BOTTOM_GAP = 12;

    // 상세등록 구간이 열려 있는지 (단일 진실원천)
    function isDetailOpen() {
        return $(".js-detail-section").first().hasClass("show");
    }

    function getTotalSteps() {
        return isDetailOpen() ? MAX_STEPS : REQUIRED_STEPS;
    }

    // 근무시작일을 골라야 상세등록 버튼을 노출한다
    function isWorkDtFilled() {
        return ($("[name='ru_work_dt']").val() || "") !== "";
    }

    function updateProgressBar() {
        var progress = (currentStep / getTotalSteps()) * 100;
        if (progress > 100) {
            progress = 100;
        }
        $("#stepProgressFill").css("width", progress + "%");
    }

    // 고정 푸터 상단 좌표 (스크롤 컨테이너는 .page)
    function getFooterTop() {
        var $footer = $(".sticky-bottom.view-footer");
        if ($footer.length === 0) {
            return $(window).height();
        }
        return $footer[0].getBoundingClientRect().top;
    }

    // 현재 단계가 푸터에 가리지 않도록 스크롤
    function scrollToCurrentStep() {
        var $page = $(".page");
        if ($page.length === 0) {
            return;
        }
        var $section = $(".step-section[data-step='" + currentStep + "']");
        if ($section.length === 0) {
            return;
        }

        var pageEl = $page[0];
        var pageTop = pageEl.getBoundingClientRect().top;
        var scrollMode = $section.attr("data-scroll") || "";
        var sectionTop = $section[0].getBoundingClientRect().top;

        // 질문 문구가 상단바 아래에 오도록 하는 목표 위치
        var topOffset = (scrollMode === "top") ? STEP_TOP_OFFSET_WIDE : STEP_TOP_OFFSET;
        var targetByTop = pageEl.scrollTop + (sectionTop - pageTop) - topOffset;

        if (scrollMode === "top") {
            if (targetByTop < 0) {
                targetByTop = 0;
            }
            $page.stop(true).animate({ scrollTop: targetByTop }, 300);
            return;
        }

        // 마지막 입력 항목이 푸터 위에 보이도록 하는 목표 위치
        var $wrapper = $section.find('[class*="-field-wrapper"]').last();
        var targetByBottom = targetByTop;
        if ($wrapper.length > 0) {
            var wrapRect = $wrapper[0].getBoundingClientRect();
            var overflow = (wrapRect.bottom + STEP_BOTTOM_GAP) - getFooterTop();
            if (overflow > 0) {
                targetByBottom = pageEl.scrollTop + overflow;
            }
        }

        var target = (scrollMode === "bottom") ? targetByBottom : Math.max(targetByTop, targetByBottom);
        if (target < 0) {
            target = 0;
        }
        $page.stop(true).animate({ scrollTop: target }, 300);
    }

    // 누적형 노출: 지나온 단계는 계속 보이고 현재 단계만 펼쳐진다
    // skipAnimation: 미리보기 복귀처럼 단계 이동이 아닐 때는
    //                펼침 애니메이션과 현재 단계로의 자동 스크롤 없이 즉시 표시한다
    function updateStepVisibility(skipAnimation) {
        var total = getTotalSteps();

        for (var i = 1; i <= MAX_STEPS; i++) {
            var $section = $(".step-section[data-step='" + i + "']");
            if (i <= currentStep && i <= total) {
                if (i === currentStep && !skipAnimation) {
                    $section.slideDown(300, scrollToCurrentStep);
                } else {
                    $section.show();
                }
            } else {
                $section.hide();
            }
        }

        updateProgressBar();

        // 이전 버튼
        if (currentStep === 1) {
            $("#btnStepPrev").hide();
        } else {
            $("#btnStepPrev").show();
        }

        // 상세등록 버튼: 마지막 필수 단계에서 근무시작일을 고른 뒤, 아직 열지 않았을 때만
        if (currentStep === REQUIRED_STEPS && !isDetailOpen() && isWorkDtFilled()) {
            $("#btnDetailMore").show();
        } else {
            $("#btnDetailMore").hide();
        }

        // 다음 / 미리보기
        if (currentStep >= total) {
            $("#btnStepNext").hide();
            $("#btnStep").show();
        } else {
            $("#btnStepNext").show();
            $("#btnStep").hide();
        }
    }

    // 상세등록 구간 열기 (main.css 의 .js-detail-section{display:none} 게이트 해제)
    function turnDetailOn() {
        $(".js-detail-section").addClass("show").css("display", "");
    }

    // 상세등록 구간 닫기. 입력값은 DOM 에 그대로 두어 다시 열면 복원된다
    function turnDetailOff() {
        $(".js-detail-section").removeClass("show");
    }

    /* ===================== 단계별 필수값 검증 ===================== */

    function checkRequiredName() {
        if ($("[name='ru_name']").val() == "") {
            commonAlert("이력서명을 입력해주세요.", function() {
                $("[name='ru_name']").focus();
            });
            return false;
        }
        return true;
    }

    function checkRequiredArea() {
        var addrCount = $("#addr_location .location-field-item:not(.location-field-add)").filter(function() {
            var addrValue = $(this).find('input[name="ad_addr[]"]').val();
            return addrValue && addrValue.trim() !== "";
        }).length;
        if (addrCount == 0) {
            commonAlert("희망지역을 선택해주세요.", function() {});
            return false;
        }
        return true;
    }

    function checkRequiredWork() {
        if ($("[name='ru_work[]']").is(":checked") == false) {
            commonAlert("희망업무는 하나이상 선택해주세요.", function() {});
            return false;
        }
        return true;
    }

    function checkRequiredMyType() {
        if ($("[name='ru_my_type[]']:checked").length == 0) {
            commonAlert("나의강점을 하나 이상 선택해주세요.", function() {});
            return false;
        }
        return true;
    }

    function checkRequiredWorkType() {
        if ($("[name='ru_work_type[]']").is(":checked") == false) {
            commonAlert("근무형태는 하나이상 선택해주세요.", function() {});
            return false;
        }
        return true;
    }

    function checkRequiredPriceType() {
        if ($("[name='ru_price_type[]']").is(":checked") == false) {
            commonAlert("희망급여는 하나이상 선택해주세요.", function() {});
            return false;
        }
        return true;
    }

    function checkRequiredDesc() {
        if ($("[name='ru_desc']").val() == "") {
            commonAlert("자기소개를 입력해주세요.", function() {
                $("[name='ru_desc']").focus();
            });
            return false;
        }
        return true;
    }

    function checkRequiredWorkDt() {
        var val = $("[name='ru_work_dt']").val();
        if (!val || val == "") {
            commonAlert("근무시작일을 선택해주세요.", function() {});
            return false;
        }
        return true;
    }

    // 한 행 안에서 두 항목 중 하나만 채워졌는지 확인 (둘 다 비었거나 둘 다 채워졌으면 false)
    function isPartialRowFilled(val1, val2) {
        var hasFirst = $.trim(val1 || "") !== "";
        var hasSecond = $.trim(val2 || "") !== "";
        return hasFirst !== hasSecond;
    }

    // 경력사항(선택): 입력한 행은 업체명과 근무기간을 모두 채워야 한다. 완전히 빈 행은 무시한다
    function checkOptionalCareerRows() {
        // 상세등록을 닫으면 경력 행은 저장되지 않으므로 검사하지 않는다
        if (!isDetailOpen()) {
            return true;
        }
        var hasPartial = false;
        $(".form-row[data-section='career'] .added-list").children("li").not(".is-deleted").each(function() {
            var $li = $(this);
            if (isPartialRowFilled($li.find("[name='career_company[]']").val(), $li.find("[name='career_period[]']").val())) {
                hasPartial = true;
                return false;
            }
        });
        if (hasPartial) {
            commonAlert("경력사항은 업체명과 근무기간을 모두 입력해주세요.", function() {});
            return false;
        }
        return true;
    }

    // 자격증(선택): 입력한 행은 자격증명과 취득일자를 모두 채워야 한다. 완전히 빈 행은 무시한다
    function checkOptionalLicenseRows() {
        // 상세등록을 닫으면 자격증 행은 저장되지 않으므로 검사하지 않는다
        if (!isDetailOpen()) {
            return true;
        }
        var hasPartial = false;
        $(".form-row[data-section='license'] .added-list").children("li").not(".is-deleted").each(function() {
            var $li = $(this);
            if (isPartialRowFilled($li.find("[name='license[]']").val(), $li.find("[name='regdate[]']").val())) {
                hasPartial = true;
                return false;
            }
        });
        if (hasPartial) {
            commonAlert("자격증은 자격증명과 취득일자를 모두 입력해주세요.", function() {});
            return false;
        }
        return true;
    }

    // 10~11단계(경력/자격증)는 선택 입력이지만, 입력한 행은 두 항목을 모두 채워야 한다
    function validateStepRequired(step) {
        switch (step) {
            case 1:
                return checkRequiredName();
            case 2:
                return checkRequiredArea();
            case 3:
                return checkRequiredWork();
            case 4:
                return checkRequiredMyType();
            case 5:
                return checkRequiredWorkType();
            case 6:
                return checkRequiredPriceType();
            case 7:
                return checkRequiredDesc();
            case 8:
                return checkRequiredWorkDt();
            case 10:
                return checkOptionalCareerRows();
            case 11:
                return checkOptionalLicenseRows();
            default:
                return true;
        }
    }

    // 제출 직전 전체 재검증. 실패하면 해당 단계로 되돌려 바로 고칠 수 있게 한다
    function validateRequiredForSubmit() {
        var checks = [
            [1, checkRequiredName],
            [2, checkRequiredArea],
            [3, checkRequiredWork],
            [4, checkRequiredMyType],
            [5, checkRequiredWorkType],
            [6, checkRequiredPriceType],
            [7, checkRequiredDesc],
            [8, checkRequiredWorkDt],
            [10, checkOptionalCareerRows],
            [11, checkOptionalLicenseRows]
        ];
        for (var i = 0; i < checks.length; i++) {
            if (!checks[i][1]()) {
                currentStep = checks[i][0];
                updateStepVisibility();
                return false;
            }
        }
        return true;
    }

    // 미리보기 복귀 시 스크롤을 맨 위(첫 단계)로 올린다.
    // 채용공고 등록 폼과 동작을 맞추기 위한 것으로, 복귀 후 처음부터 훑어보며 수정하게 한다
    function scrollPageTop() {
        var $page = $(".page");
        if ($page.length > 0) {
            $page.stop(true).scrollTop(0);
        }
    }

    /* 미리보기에서 돌아왔는지 여부.
       pageshow 와 ready(resolveInitialStep) 중 어느 쪽이 먼저 돌지 보장할 수 없는데,
       먼저 돈 쪽이 표시를 소비하면 나중 쪽이 판정에 실패한다.
       그래서 인라인 스크립트가 파싱되는 이 시점에 한 번 읽어 고정해 둔다 */
    var IS_RETURN_FROM_PREVIEW = (function() {
        try {
            return sessionStorage.getItem("resume_from_preview") === "true";
        } catch (e) {
            return false;
        }
    }());

    // 미리보기 복귀 표시가 남아 있는지.
    // bfcache 복원은 스크립트가 다시 파싱되지 않아 위 상수가 갱신되지 않으므로
    // 실시간 값도 함께 본다
    function isPreviewFlagSet() {
        if (IS_RETURN_FROM_PREVIEW) {
            return true;
        }
        try {
            return sessionStorage.getItem("resume_from_preview") === "true";
        } catch (e) {
            return false;
        }
    }

    // 표시는 한 번 쓰고 지운다 (다음 진입에서 잘못 적용되지 않도록)
    function consumePreviewFlag() {
        try {
            sessionStorage.removeItem("resume_from_preview");
        } catch (e) {}
    }

    // 진입 시 보여줄 단계 결정
    function resolveInitialStep() {
        var params = new URLSearchParams(window.location.search);
        var total = getTotalSteps();

        // 미리보기에서 돌아온 경우: 제출 직전 상태이므로 전체가 보이는 마지막 단계
        if (params.get("from") === "preview" || isPreviewFlagSet()) {
            consumePreviewFlag();
            return total;
        }

        // 임시저장에 남은 위치가 있으면 그 위치
        try {
            var saved = JSON.parse(sessionStorage.getItem(getResumeDraftKey(4)) || "{}");
            if (saved && saved.current_step) {
                var s = parseInt(saved.current_step, 10) || 1;
                return Math.min(Math.max(s, 1), total);
            }
        } catch (e) {}

        // 기존 이력서 수정 진입: 처음부터 다시 밟지 않도록 마지막 단계
        var ruIdx = $("[name='ru_idx']").val() || "0";
        if (ruIdx != "0" && ($("[name='ru_name']").val() || "") != "") {
            return total;
        }

        return 1;
    }

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
        // 이력서명 문자 수 카운트 (최대 30자)
        $("#ru_name_input").on("input", function() {
            $("#ru_name_count").text($(this).val().length);
        });
        // 초기값 설정 (DB/임시저장 값 반영)
        $("#ru_name_count").text($("#ru_name_input").val().length);
        $("[name='ru_work[]']").on("change", saveResumeStep1Data);
        $("[name='ru_price_type[]']").on("change", saveResumeStep1Data);
        $("[name='ru_work_type[]']").on("change", saveResumeStep1Data);
        /* 나의강점은 직접입력으로 체크박스가 동적 추가되므로 위임 바인딩으로 처리한다
           (아래 data-select-limit 통합 핸들러는 ready 시점의 요소만 잡는다) */
        $(document).on("change", "[name='ru_my_type[]']", function() {
            var limit = 6;
            if (this.checked && $("[name='ru_my_type[]']:checked").length > limit) {
                this.checked = false;
                commonAlert("최대 " + limit + "개까지 선택 가능합니다.", function() {});
                return;
            }
            saveResumeStep4Data();
        });

        /* 나의강점 직접입력: + 버튼으로 입력란을 열고 확인하면 태그로 추가한다 */
        $(".js-add-strength").on("click", function(e) {
            e.preventDefault();
            var $wrapper = $(this).closest(".form-row");
            var $inputArea = $wrapper.find(".strength-input-area");
            var $guideText = $wrapper.find(".strength-guide-text").closest("label");

            $inputArea.addClass("show").hide().slideDown(300);
            $guideText.slideUp(300);
            setTimeout(function() {
                $inputArea.find(".js-strength-input").focus();
            }, 350);
        });

        $(".js-confirm-strength").on("click", function(e) {
            e.preventDefault();
            var $input = $(this).siblings(".js-strength-input");
            var text = ($input.val() || "").trim();

            if (text == "") {
                commonAlert("나의강점을 입력해주세요.", function() {});
                return;
            }

            var exists = false;
            $("[name='ru_my_type[]']").each(function() {
                if ($(this).val() == text) {
                    exists = true;
                    return false;
                }
            });
            if (exists) {
                commonAlert("이미 추가된 강점입니다.", function() {});
                return;
            }

            if ($("[name='ru_my_type[]']:checked").length >= 6) {
                commonAlert("최대 6개까지 선택 가능합니다.", function() {});
                return;
            }

            var $newLabel = $('<label class="form-toggle-btn2 sm tag">' +
                '<input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="' + text + '" checked>' +
                '<span class="btn"></span>' +
                '</label>');
            // 사용자 입력이므로 텍스트로만 삽입한다 (HTML 주입 방지)
            $newLabel.find(".btn").text(text);
            $(".js-add-strength").before($newLabel);

            var $inputArea = $(this).closest(".strength-input-area");
            $input.val("");
            $inputArea.slideUp(300, function() {
                $(this).removeClass("show");
            });
            $(".js-add-strength").closest(".form-row").find(".strength-guide-text").closest("label").slideDown(300);

            saveResumeStep4Data();
        });

        /* 선택목록 개수 제한 (희망업무 2 / 급여 2 / 근무형태 2)
           단계 분리 전에는 뷰 중간의 인라인 script 였으나, DOM 순서상 자기 앞의
           요소에만 걸리는 문제가 있어 ready 로 옮겨 전 항목에 적용한다 */
        document.querySelectorAll('input[data-select-limit]').forEach(function(item) {
            // 나의강점은 위 위임 핸들러가 전담한다 (중복 경고 방지)
            if (item.getAttribute('name') === 'ru_my_type[]') {
                return;
            }
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

        /* 근무시간 안내 문구: 주간·야간을 모두 선택하면 표시 (20221003 수정) */
        (function() {
            function timeChecked(el) {
                var total = document.querySelectorAll('.js-check-time').length;
                var isChecked = document.querySelectorAll('.js-check-time:checked');
                var row = el.closest('.form-row');
                if (!row) {
                    return;
                }
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
        $("[name='ru_desc']").on("input", saveResumeStep4Data);
        $(document).on("input change", "[name='career_category[]'], [name='career_company[]'], [name='career_period[]'], [name='license[]'], [name='regdate[]']", function() {
            saveResumeStep4Data();
        });

        /* 상세등록(+): 마지막 필수 단계(8)에서 선택 구간(9~11)을 열고 9단계로 진입 */
        $("#btnDetailMore").click(function(e) {
            e.preventDefault();
            if (!validateStepRequired(currentStep)) {
                return;
            }
            turnDetailOn();
            currentStep = REQUIRED_STEPS + 1;
            updateStepVisibility();
            saveResumeStep4Data();
        });

        /* 다음 */
        $("#btnStepNext").click(function() {
            if (!validateStepRequired(currentStep)) {
                return;
            }
            if (currentStep < getTotalSteps()) {
                currentStep++;
                updateStepVisibility();
                saveResumeStep4Data();
            }
        });

        /* 이전: 9단계에서 뒤로 가면 상세등록 구간을 닫고 8단계로 돌아간다 */
        $("#btnStepPrev").click(function() {
            if (currentStep === REQUIRED_STEPS + 1) {
                turnDetailOff();
                currentStep = REQUIRED_STEPS;
            } else if (currentStep > 1) {
                currentStep--;
            } else {
                return;
            }
            updateStepVisibility();
            saveResumeStep4Data();
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
            var checkedCount = $("[name='ru_week[]']:checked").length;
            var totalCount = $("[name='ru_week[]']").length;

            if (checkedCount === totalCount) {
                $("[name='ru_week_yn']").prop("checked", true);
            } else {
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
            var $newItem = $($temp);
            $list.append($newItem);

            // 날짜 선택은 위임 바인딩이라 행별 초기화가 필요 없다

            if (typeof initSelectbox === 'function') {
                var $selectbox = $newItem.find(".js-career-category");
                if ($selectbox.length > 0) {
                    initSelectbox($selectbox[0]);
                }
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
            var $newItem = $($temp);
            $list.append($newItem);

            // 날짜 선택은 위임 바인딩이라 행별 초기화가 필요 없다

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

            // 공용 함수로 경력/자격증 조립 및 저장
            buildAndSaveCareerCertiStrings();

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
                                    sessionStorage.removeItem("resume_autofill_payload");
                                    sessionStorage.removeItem("resume_autofill_ts");
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
            if ($(this).attr("data-state") === "submitting") {
                return;
            }

            saveResumeStep1Data();
            saveResumeStep4Data();

            // 필수 8단계 전체 재검증 (실패하면 해당 단계로 되돌아간다)
            if (!validateRequiredForSubmit()) {
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
            var ruIdx = $("[name='ru_idx']").val() || "0";
            var ruName = $("[name='ru_name']").val() || "";
            var isEditMode = (ruIdx != "0" && ruName != "");
            var checkedCount = $("[name='ru_week[]']:checked").length;
            if (!isEditMode && checkedCount === 0) {
                var defaultWeekDays = ["월", "화", "수", "목", "금"];
                defaultWeekDays.forEach(function(val) {
                    $("[name='ru_week[]'][value='" + val + "']").prop("checked", true);
                });
                saveResumeStep4Data();
            }
        }, 200);

        // 뒤로가기 핸들러는 스크립트 시작 부분에서 이미 등록됨

        // Android 물리적 뒤로가기 버튼 및 iOS 스와이프 뒤로가기 처리
        window.addEventListener('popstate', function(e) {
            clearResumeDraft($("[name='ru_idx']").val() || "0");
        });

        /* ===== 단계 UI 초기화 (복원 로직이 모두 끝난 뒤 실행) ===== */
        setTimeout(function() {
            var isAutofill = (new URLSearchParams(window.location.search).get("autofill") === "1");

            // 자동 이력서 생성 경로는 화면을 숨긴 채 프로그램적으로 제출하므로
            // 단계 UI 가 값을 가리지 않도록 전체를 펼친 상태로 둔다
            if (isAutofill) {
                turnDetailOn();
                currentStep = getTotalSteps();
            } else {
                currentStep = resolveInitialStep();
            }

            // 1단계로 시작할 때만 펼침 애니메이션을 쓴다.
            // 중간 단계부터 복원되는 경우(미리보기 복귀·수정 진입)는 즉시 표시하고 맨 위에서 시작
            if (currentStep === 1) {
                updateStepVisibility();
            } else {
                updateStepVisibility(true);
                scrollPageTop();
            }
        }, 250);
    });

    /* ==================================================================
       월/일/년 날짜 휠 바텀시트
       - 시트 1개를 모든 날짜 입력이 공유한다. 대상은 prefix 가 아니라
         트리거 DOM 참조로 기억한다 (경력/자격증은 같은 name 이 N개라
         인덱스·prefix 방식은 행 삭제 시 어긋남)
       - 모드: single(월/일/년) | month(월/년) | range(월/년 2단계)
       ================================================================== */
    (function() {
        var $activeTrigger = null; // 현재 열려 있는 트리거
        var activeMode = "single";
        var rangePhase = 0; // range 모드: 0=시작일, 1=종료일
        var rangeStartVal = "";
        var wheelTimer = null;

        function sheetEl() {
            return document.getElementById("datePickerSheet");
        }

        function colEl(id) {
            return document.getElementById(id);
        }

        function usesDay() {
            return activeMode === "single";
        }

        // 2자리 년도를 4자리로 변환 (기존 경력 데이터가 "20.03" 형태로 저장돼 있음)
        function expandYear(yearStr) {
            var year = parseInt(yearStr, 10);
            if (isNaN(year)) {
                return "";
            }
            if (year < 100) {
                year = (year < 30) ? year + 2000 : year + 1900;
            }
            return year.toString();
        }

        // 컬럼 중앙에 가장 가까운 항목에 .active 부여 (숨김 항목 제외)
        function updateActive(col) {
            if (!col) {
                return;
            }
            var items = Array.prototype.slice.call(col.querySelectorAll("li:not(.is-hidden)"));
            if (items.length === 0) {
                return;
            }
            var rect = col.getBoundingClientRect();
            var center = rect.height / 2;
            var best = null;
            var min = Infinity;
            items.forEach(function(li) {
                var r = li.getBoundingClientRect();
                var d = Math.abs((r.top + r.height / 2 - rect.top) - center);
                if (d < min) {
                    min = d;
                    best = li;
                }
            });
            Array.prototype.forEach.call(col.querySelectorAll("li"), function(li) {
                li.classList.remove("active");
            });
            if (best) {
                best.classList.add("active");
            }
        }

        function activeVal(col) {
            var li = col ? col.querySelector("li.active") : null;
            return li ? li.getAttribute("data-val") : "";
        }

        // 지정 값 위치로 스크롤 (li 높이 44px 의 절반 = 22)
        function scrollToVal(col, val) {
            if (!col) {
                return;
            }
            var li = col.querySelector('li[data-val="' + val + '"]:not(.is-hidden)');
            if (!li) {
                li = col.querySelector("li:not(.is-hidden)");
            }
            if (!li) {
                return;
            }
            col.scrollTop = li.offsetTop - (col.clientHeight / 2 - 22);
            updateActive(col);
        }

        // 용도별 연도 범위 제한
        function applyYearRange(min, max) {
            var col = colEl("wheelYear");
            if (!col) {
                return;
            }
            Array.prototype.forEach.call(col.querySelectorAll("li"), function(li) {
                var y = parseInt(li.getAttribute("data-year"), 10);
                if (y < min || y > max) {
                    li.classList.add("is-hidden");
                } else {
                    li.classList.remove("is-hidden");
                }
            });
        }

        // 월별 말일 보정 (윤년 자동 처리). single 모드에서만 의미가 있다
        function syncDayColumn() {
            if (!usesDay()) {
                return;
            }
            var y = parseInt(activeVal(colEl("wheelYear")), 10);
            var m = parseInt(activeVal(colEl("wheelMonth")), 10);
            var dayCol = colEl("wheelDay");
            if (!y || !m || !dayCol) {
                return;
            }
            var last = new Date(y, m, 0).getDate();
            var cur = parseInt(activeVal(dayCol), 10);
            Array.prototype.forEach.call(dayCol.querySelectorAll("li"), function(li) {
                var d = parseInt(li.getAttribute("data-day"), 10);
                if (d > last) {
                    li.classList.add("is-hidden");
                } else {
                    li.classList.remove("is-hidden");
                }
            });
            if (cur > last) {
                scrollToVal(dayCol, ("0" + last).slice(-2));
            } else {
                updateActive(dayCol);
            }
        }

        function initWheel(col, onSettle) {
            if (!col) {
                return;
            }
            col.addEventListener("scroll", function() {
                clearTimeout(wheelTimer);
                wheelTimer = setTimeout(function() {
                    updateActive(col);
                    if (onSettle) {
                        onSettle();
                    }
                }, 50);
            });
        }

        // "YYYY.MM.DD" / "YYYY.MM" / "YYYY-MM-DD" / "20.03" 모두 수용
        function parseDate(str) {
            var today = new Date();
            var fallback = {
                y: today.getFullYear(),
                m: ("0" + (today.getMonth() + 1)).slice(-2),
                d: ("0" + today.getDate()).slice(-2)
            };
            if (!str) {
                return fallback;
            }
            var parts = String(str).trim().replace(/-/g, ".").split(".");
            if (parts.length < 2) {
                return fallback;
            }
            var y = expandYear(parts[0]);
            if (y === "") {
                return fallback;
            }
            return {
                y: parseInt(y, 10),
                m: ("0" + (parseInt(parts[1], 10) || 1)).slice(-2),
                d: ("0" + (parts.length > 2 ? (parseInt(parts[2], 10) || 1) : 1)).slice(-2)
            };
        }

        // 트리거에 대응하는 hidden 입력: data-target 이 있으면 그것, 없으면 같은 칸의 hidden
        function hiddenOf($trigger) {
            var sel = $trigger.attr("data-target");
            if (sel) {
                return $(sel);
            }
            return $trigger.closest(".col, .form-row").find("input[type=hidden]").first();
        }

        function setSheetTitle(txt) {
            $("#datePickerSheet").find(".bottom-sheet-title").text(txt);
        }

        function applySeed(str) {
            var v = parseDate(str);
            scrollToVal(colEl("wheelYear"), String(v.y));
            scrollToVal(colEl("wheelMonth"), v.m);
            if (usesDay()) {
                syncDayColumn();
                scrollToVal(colEl("wheelDay"), v.d);
            }
        }

        function pickedStr() {
            var y = activeVal(colEl("wheelYear"));
            var m = activeVal(colEl("wheelMonth"));
            if (!usesDay()) {
                return y + "." + m; // YYYY.MM
            }
            return y + "." + m + "." + activeVal(colEl("wheelDay")); // YYYY.MM.DD
        }

        window.openDatePicker = function(triggerEl) {
            var $t = $(triggerEl);
            var sheet = sheetEl();
            if (!sheet) {
                return;
            }

            $activeTrigger = $t;
            activeMode = $t.attr("data-date-mode") || "single";
            rangePhase = 0;
            rangeStartVal = "";

            // 일 컬럼 노출 여부는 시트의 data-mode 로 CSS 가 제어한다
            sheet.setAttribute("data-mode", usesDay() ? "ymd" : "ym");

            applyYearRange(
                parseInt($t.attr("data-year-min"), 10) || 1960,
                parseInt($t.attr("data-year-max"), 10) || (new Date().getFullYear())
            );

            var raw = hiddenOf($t).val() || "";
            if (raw === "") {
                raw = $t.attr("data-date-seed") || "";
            }
            var seed = (activeMode === "range" && raw.indexOf("~") > -1) ? raw.split("~")[0] : raw;

            setSheetTitle($t.attr("data-date-title") || "날짜를 선택해주세요");
            $("#btnDatePickerPrev").hide();

            var pageElement = document.querySelector(".page");
            if (pageElement) {
                pageElement.style.overflow = "hidden";
            }

            // 시트가 열리며 레이아웃을 가진 뒤에 스크롤 위치를 잡아야 clientHeight 가 0이 아니다
            setTimeout(function() {
                applySeed(seed);
            }, 60);
        };

        window.confirmDatePicker = function() {
            if (!$activeTrigger) {
                return;
            }
            var picked = pickedStr();

            // range 1단계: 시트를 닫지 않고 종료일 선택으로 전환
            if (activeMode === "range" && rangePhase === 0) {
                rangeStartVal = picked;
                rangePhase = 1;
                setSheetTitle($activeTrigger.attr("data-date-title2") || "종료일을 선택해주세요");
                $("#btnDatePickerPrev").show();
                applySeed(picked);
                return;
            }

            var finalVal;
            if (activeMode === "range") {
                // "YYYY.MM" 은 자리수가 고정이라 문자열 비교로 선후를 판단할 수 있다
                if (picked < rangeStartVal) {
                    commonAlert("종료일은 시작일 이후로 선택해주세요.", function() {});
                    return;
                }
                finalVal = rangeStartVal + " ~ " + picked;
            } else if ($activeTrigger.attr("data-date-format") === "dash") {
                finalVal = picked.replace(/\./g, "-"); // 서버 timestamp 용 Y-m-d
            } else {
                finalVal = picked;
            }

            var $hidden = hiddenOf($activeTrigger);
            $hidden.val(finalVal);
            $activeTrigger.addClass("has-value")
                .find(".date-display").text(finalVal.replace(/-/g, "."));

            // hidden 은 사용자 입력 이벤트가 없으므로 임시저장 핸들러를 직접 깨운다
            $hidden.trigger("change");
            if (typeof saveResumeStep4Data === "function") {
                saveResumeStep4Data();
            }

            // 근무시작일을 고른 직후 상세등록 버튼이 나타나도록 버튼 상태를 갱신한다
            if (typeof updateStepVisibility === "function" && typeof REQUIRED_STEPS !== "undefined" && currentStep === REQUIRED_STEPS) {
                updateStepVisibility(true);
            }

            closeDateSheet();
        };

        window.datePickerPrev = function() {
            if (activeMode === "range" && rangePhase === 1) {
                rangePhase = 0;
                setSheetTitle($activeTrigger.attr("data-date-title") || "시작일을 선택해주세요");
                $("#btnDatePickerPrev").hide();
                applySeed(rangeStartVal);
                return;
            }
            closeDateSheet();
        };

        function closeDateSheet() {
            var pageElement = document.querySelector(".page");
            if (pageElement) {
                pageElement.style.overflow = "";
            }
            if (typeof closePanel === "function") {
                closePanel(sheetEl());
            }
            $activeTrigger = null;
            rangePhase = 0;
        }

        // hidden 값에 맞춰 날짜 버튼 표시 텍스트 갱신 (목록 렌더링 시 사용)
        window.setDateTriggerDisplay = function($hidden, val, placeholder) {
            var $trigger = $hidden.closest(".col, .form-row").find(".js-date-picker-trigger").first();
            if ($trigger.length === 0) {
                return;
            }
            if (val && val !== "") {
                $trigger.addClass("has-value").find(".date-display").text(val.replace(/-/g, "."));
            } else {
                $trigger.removeClass("has-value").find(".date-display").text(placeholder || "");
            }
        };

        $(function() {
            if (!sheetEl()) {
                return;
            }
            initWheel(colEl("wheelYear"), syncDayColumn);
            initWheel(colEl("wheelMonth"), syncDayColumn);
            initWheel(colEl("wheelDay"), null);

            if (typeof initPanelSwipe === "function") {
                initPanelSwipe("datePickerSheet");
            }

            // 시트 열기는 main.js 의 js-toggle-panel 위임이 담당하고, 여기서는 값만 주입한다
            // 동적으로 추가되는 경력/자격증 행까지 커버하려면 위임 바인딩이어야 한다
            $(document).on("click", ".js-date-picker-trigger", function(e) {
                e.preventDefault();
                openDatePicker(this);
            });
        });
    }());

    // 이력서 미리보기 페이지로 이동 (site_log는 이미 저장됨)
    function showPreviewInfo(info, $btnElement) {
        var ru_idx = $("[name=ru_idx]").val();

        // site_log는 이미 resume_write1/4에서 저장했으므로
        // resume_complete()만 호출해서 유효성 검증 후 미리보기로 이동
        var params = {};
        params["ru_idx"] = ru_idx;
        params["preview_mode"] = "1";

        comAjax("/mypage_proc/resume_complete", params, function(ret) {
            if (ret.code == "00") {
                // 미리보기에서 돌아올 때(수정하기 / 뒤로가기 모두) 마지막 단계까지 펼치기 위한 표시.
                // 복귀 경로마다 URL 이 달라 쿼리스트링만으로는 판별할 수 없다
                try {
                    sessionStorage.setItem("resume_from_preview", "true");
                } catch (e) {}

                // add.js 의 전역 pageshow 핸들러가 뒤로가기 때 이 값으로 스크롤을 되돌린다.
                // 미리보기 버튼을 누른 위치(마지막 단계 아래)가 담겨 있으면 복귀 시 화면이 아래로 튀므로
                // 맨 위로 덮어쓴다
                try {
                    localStorage.setItem("divScrollPositon", "0");
                } catch (e) {}

                location.href = "/resume/hr_post/" + ru_idx + "?preview=1";
            } else {
                commonAlert(ret.msg || "저장 처리 중 오류가 발생했습니다.", function() {});
            }
        });
    }

    window.addEventListener('pageshow', function(event) {
        if (event.persisted || window.performance && window.performance.navigation.type == 2) {
            // 미리보기에서 돌아온 경우에는 작성 내용을 지우고 마이페이지로 보내지 않는다
            if (isPreviewFlagSet()) {
                if (event.persisted) {
                    // bfcache 복원이면 ready 가 다시 돌지 않으므로 여기서 펼치고 표시를 소비한다
                    consumePreviewFlag();
                    currentStep = getTotalSteps();
                    updateStepVisibility(true);
                    scrollPageTop();
                }
                // 새로 로드되는 경우는 resolveInitialStep() 이 표시를 보고 처리한다
                return;
            }
            clearResumeDraft($("[name='ru_idx']").val() || "0");
            _replace("/mypage/index");
        }
    }, false);

</script>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>

<?php
// ========== 월/일/년 날짜 선택 휠 바텀시트 ==========
// 시트는 1개만 두고 근무시작일(월/일/년) · 경력 근무기간 · 자격증 취득일(월/년)이 공유한다.
// 모드/제목/연도범위는 트리거 버튼의 data-* 속성을 읽어 JS 가 바꿔 끼운다.
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_helpers.php');
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_template.php');

// 월 1~12 (표시는 "1월", 저장값은 data-val 의 "01")
$dpMonthList = "";
for ($dpM = 1; $dpM <= 12; $dpM++) {
    $dpMonthList .= '<li data-val="' . str_pad($dpM, 2, "0", STR_PAD_LEFT) . '">' . $dpM . '월</li>';
}

// 일 01~31 (월별 말일 보정은 JS 가 .is-hidden 으로 처리)
$dpDayList = "";
for ($dpD = 1; $dpD <= 31; $dpD++) {
    $dpDD = str_pad($dpD, 2, "0", STR_PAD_LEFT);
    $dpDayList .= '<li data-val="' . $dpDD . '" data-day="' . $dpD . '">' . $dpDD . '</li>';
}

// 년: 경력(과거) ~ 근무시작일(미래) 을 모두 포괄하는 단일 범위. 용도별 제한은 JS 가 .is-hidden 으로 처리
$dpYearFrom = 1960;
$dpYearTo = (int)date("Y") + 5;
$dpYearList = "";
for ($dpY = $dpYearFrom; $dpY <= $dpYearTo; $dpY++) {
    $dpYearList .= '<li data-val="' . $dpY . '" data-year="' . $dpY . '">' . $dpY . '</li>';
}

$datePickerContent = '<div class="time-wheel-picker date-wheel-picker">'
    . '<div class="wheel-col wheel-col-month" id="wheelMonth"><ul>' . $dpMonthList . '</ul></div>'
    . '<div class="wheel-col wheel-col-day" id="wheelDay"><ul>' . $dpDayList . '</ul></div>'
    . '<div class="wheel-col wheel-col-year" id="wheelYear"><ul>' . $dpYearList . '</ul></div>'
    . '<div class="wheel-highlight-bar"></div>'
    . '</div>';

// 버튼 2개: 경력 근무기간의 시작일 -> 종료일 2단계 플로우에서 '이전' 이 필요하다
renderSelectBottomSheet(
    'datePickerSheet',
    '날짜를 선택해주세요',
    $datePickerContent,
    '이전', 'btnDatePickerPrev', 'datePickerPrev()',
    '확인', 'btnDatePickerConfirm', 'confirmDatePicker()',
    'bottom-sheet-time-picker',
    'time-wheel-picker-content'
);
?>
