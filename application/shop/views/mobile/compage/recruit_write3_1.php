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

    .detail-register-btn {
        display: block;
        width: 100%;
        height: 3.5rem;
        margin-top: 0.875rem;
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
    }

    .detail-register-btn .add-icon {
        font-size: 1.5rem;
        font-weight: 300;
        margin-right: 4px;
        vertical-align: middle;
    }

    /* 날짜 선택 필드 스타일 */
    .datepicker-wrapper {
        position: relative;
        display: block;
        flex: 1;
    }

    .datepicker-wrapper .form-text {
        width: 100%;
        height: 3.5rem;
        padding: 0 1.25rem;
        padding-right: 2.5rem;
        font-size: 1rem;
        font-weight: 400;
        color: #222;
        line-height: 3.5rem;
        text-align: center;
        cursor: pointer;
        background-color: #fff;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        box-sizing: border-box;
    }

    .datepicker-wrapper .form-text:focus {
        outline: none;
        border-color: #1B9D7D;
    }

    /* flatpickr altInput 가운데 정렬 */
    .datepicker-wrapper .flatpickr-input {
        text-align: center;
    }

    .datepicker-icon {
        position: absolute;
        right: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.25rem;
        height: 1.25rem;
        pointer-events: none;
        color: #999;
    }

    .datepicker-icon::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'%3E%3C/rect%3E%3Cline x1='16' y1='2' x2='16' y2='6'%3E%3C/line%3E%3Cline x1='8' y1='2' x2='8' y2='6'%3E%3C/line%3E%3Cline x1='3' y1='10' x2='21' y2='10'%3E%3C/line%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    .datepicker-wrapper .form-text[readonly] {
        cursor: pointer;
        background-color: #fff;
    }

    /* 해시태그 안내 텍스트 */
    .form-label em {
        color: #1B9D7D;
        font-style: normal;
    }

    /* 상세등록 섹션 숨김/표시 */
    .form-row.js-detail-section {
        display: none;
        margin-top: 0.875rem;
    }

    .form-row.js-detail-section.show {
        display: block;
    }

    /* 성별/국적/나이 무관 체크박스 스타일 */
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

    /* 희망나이 섹션 스타일 */
    .age-range-group {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin-top: 0.875rem;
    }

    .age-range-group select {
        flex: 1;
        height: 3.5rem;
        padding: 0 1.25rem;
        padding-right: 2.5rem;
        font-size: 1rem;
        font-weight: 400;
        color: #222;
        text-align: center;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background: #fff;
        box-sizing: border-box;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23999' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: calc(100% - 1.25rem) 50%;
        background-size: 12px 8px;
        cursor: pointer;
    }

    .age-range-group select:focus {
        outline: none;
        border-color: #1B9D7D;
    }

    .age-range-group .age-separator {
        font-size: 1rem;
        color: #222;
        font-weight: 400;
        line-height: 3.5rem;
    }

    /* 첨부사진 섹션 스타일 */
    .form-pic-group {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.875rem;
    }

    .form-pic-group .form-pic {
        flex: 1;
        position: relative;
        cursor: pointer;
        border: 1px solid #BEBFC9;
        background-color: #f5f5f5;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1;
    }

    .form-pic-group .form-pic:before {
        content: "";
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 3rem;
        height: 3rem;
        border-radius: 50%;

        background-image: url("/resource/shop/mobile/img/camera.svg");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 2.5rem 2.5rem;
        z-index: 1;
        pointer-events: none;
    }

    .form-pic-group .form-pic.has-image:before {
        display: none;
    }

    .form-pic-group .form-pic input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        outline: 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .form-pic-group .form-pic .label {
        display: none;
    }

    .form-pic-group .form-pic .preview {
        display: block;
        position: relative;
        width: 100%;
        padding-top: 100%;
        overflow: hidden;
    }

    .form-pic-group .form-pic .preview img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 2;
    }

    .form-pic-group .form-pic.first {
        flex: 1;
    }

    /* 해시태그 섹션 스타일 */
    .form-toggle-group.col-3 {
        margin-top: 0.875rem;
    }

    .form-toggle-group.col-3 .form-toggle-btn2.tag {
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        vertical-align: top;
    }

    .form-toggle-group.col-3 .form-toggle-btn2.tag .btn {
        min-width: calc(33.333% - 0.5rem);
        height: 3.5rem;
        padding: 0 0.75rem;
        font-size: 0.9375rem;
        font-weight: 400;
        line-height: 3.5rem;
        color: #222;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background: #fff;
        text-align: center;
    }

    .form-toggle-group.col-3 .form-toggle-btn2.tag input:checked~.btn {
        color: #fff;
        font-weight: 500;
        border-color: #35c8a3;
        background-color: #35c8a3;
    }

    /* 추가 버튼 스타일 - 해시태그 버튼과 동일한 정렬 */
    .form-toggle-group.col-3 .btn-add-more {
        display: inline-block;
        min-width: calc(33.333% - 0.5rem);
        height: 3.5rem;
        padding: 0;
        margin-top: 0;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
        font-weight: 300;
        color: #999;
        line-height: 3.5rem;
        text-align: center;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
        vertical-align: top;
        box-sizing: border-box;
    }

    .form-toggle-group.col-3 .btn-add-more:hover,
    .form-toggle-group.col-3 .btn-add-more:focus {
        border-color: #1B9D7D;
        color: #1B9D7D;
    }

    /* 해시태그 직접입력 영역 스타일 */
    .hashtag-input-area {
        margin-top: 0.875rem;
    }

    .hashtag-input-area .form-text {
        height: 3.5rem;
        padding: 0 0.75rem;
        font-size: 0.9375rem;
        font-weight: 400;
        color: #222;
        line-height: 3.5rem;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background: #fff;
        box-sizing: border-box;
    }

    .hashtag-input-area .form-text:focus {
        outline: none;
        border-color: #1B9D7D;
    }

    .hashtag-input-area .btn-confirm {
        height: 3.5rem;
        padding: 0 1.5rem;
        font-size: 0.9375rem;
        font-weight: 500;
        color: #fff;
        background-color: #1B9D7D;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        white-space: nowrap;
    }

    .hashtag-input-area .btn-confirm:hover,
    .hashtag-input-area .btn-confirm:focus {
        background-color: #158a6f;
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

    /* 희망요일 원형 버튼 스타일 */
    .day-of-week-group {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.875rem;
        justify-content: space-between;
    }

    .day-of-week-group .day-btn {
        flex: 1;
        position: relative;
        cursor: pointer;
        max-width: 3.5rem;
    }

    .day-of-week-group .day-btn input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        outline: 0;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .day-of-week-group .day-btn .btn {
        display: block;
        width: 100%;
        height: 3.5rem;
        min-width: 3.5rem;
        padding: 0;
        font-size: 1rem;
        font-weight: 400;
        color: #999;
        line-height: 3.5rem;
        text-align: center;
        border: 1px solid #BEBFC9;
        border-radius: 8px;
        background: #fff;
        box-sizing: border-box;
    }

    .day-of-week-group .day-btn input:checked~.btn {
        color: #fff;
        font-weight: 500;
        border-color: #57CCAF;
        background-color: #57CCAF;
    }
</style>

<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form id="frm" onsubmit="return false">
            <input type="hidden" name="rc_idx" value="<?= isset($info["rc_idx"]) ? $info["rc_idx"] : "0" ?>" />
            <div class="section addition-info-form">
                <div class="container">
                    <!-- 상세업무정보 섹션 -->
                    <div class="form-row">
                        <div class="resume-title-header">
                            <p class="form-label"><span class="required-asterisk">*</span>상세업무정보<em>최대500자</em></p>
                            <span class="required-label"><span class="required-asterisk">*</span>필수입력</span>
                        </div>
                        <label>
                            <span class="sr-text">상세업무정보 입력</span>
                            <textarea name="rc_desc" maxlength="500" class="form-textarea js-autoheight"><?= isset($info["rc_desc"]) ? strip_tags($info["rc_desc"]) : "" ?></textarea>
                        </label>
                    </div>

                    <!-- 해시태그 섹션 -->
                    <div class="form-row">
                        <p class="form-label"><span class="required-asterisk">*</span>해시태그</p>
                        <div class="form-toggle-group col-3">
                            <?php
                            // 기본 해시태그 리스트 (스크린샷 기준)
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

                            <!-- 추가 태그 -->
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
                        <!-- 직접입력 영역 (초기 숨김) -->
                        <div class="hashtag-input-area" style="display: none; margin-top: 0.875rem;">
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="text" class="form-text js-hashtag-input" placeholder="직접입력" maxlength="20" style="flex: 1; height: 3.5rem; padding: 0 0.75rem; font-size: 0.9375rem; border: 1px solid #BEBFC9; border-radius: 8px;">
                                <button type="button" class="btn-confirm js-confirm-hashtag" style="height: 3.5rem; padding: 0 1.5rem; font-size: 0.9375rem; font-weight: 500; color: #fff; background-color: #1B9D7D; border: none; border-radius: 8px; cursor: pointer; white-space: nowrap;">확인</button>
                            </div>
                        </div>
                    </div>

                    <!-- 채용공고기간 섹션 -->
                    <div class="form-row">
                        <p class="form-label">
                            <span class="required-asterisk">*</span>채용공고기간
                            <em style="color: #1B9D7D; margin-left: 8px;">2개월까지 공고 가능</em>
                        </p>
                        <div style="display: flex; gap: 0.5rem; margin-bottom: 0.875rem; align-items: center;">
                            <label class="datepicker-wrapper" style="flex: 1;">
                                <span class="sr-text">시작일 선택</span>
                                <input type="text" name="rc_sdate" id="rc_sdate" aria-required="true" required class="form-text js-datepicker-start" readonly placeholder="2025.01.01">
                            </label>
                            <span style="line-height: 3.5rem; color: #222; font-size: 1rem; font-weight: 400;">~</span>
                            <label class="datepicker-wrapper" style="flex: 1;">
                                <span class="sr-text">종료일 선택</span>
                                <input type="text" name="rc_edate" id="rc_edate" aria-required="true" required class="form-text js-datepicker-end" readonly placeholder="2025.03.01">
                            </label>
                        </div>
                    </div>

                    <!-- 상세등록 섹션들 -->
                    <!-- 희망요일 섹션 -->
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

                    <!-- 희망성별 섹션 -->
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

                    <!-- 희망국적 섹션 -->
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

                    <!-- 희망나이 섹션 -->
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
                                <?php for ($icnt = 20; $icnt <= 80; $icnt++) : ?>
                                    <option value="<?= $icnt ?>" <?= isset($info["rc_sage"]) && $info["rc_sage"] == $icnt ? "selected" : "" ?>><?= $icnt ?>세</option>
                                <?php endfor; ?>
                            </select>
                            <span class="age-separator">~</span>
                            <select name="rc_eage">
                                <option value="">선택</option>
                                <?php for ($icnt = 20; $icnt <= 80; $icnt++) : ?>
                                    <option value="<?= $icnt ?>" <?= isset($info["rc_eage"]) && $info["rc_eage"] == $icnt ? "selected" : "" ?>><?= $icnt ?>세</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <!-- 첨부사진 섹션 -->
                    <div class="form-row js-detail-section">
                        <p class="form-label">첨부사진</p>
                        <div class="form-pic-group">
                            <label class="form-pic <?= isset($info["rc_img1"]) && $info["rc_img1"] != "" ? "has-image" : "" ?>">
                                <input type="file" name="rc_img1" accept="image/*" class="js-file-preview">
                                <span class="preview">
                                    <?php if (isset($info["rc_img1"]) && $info["rc_img1"] != "") : ?>
                                        <img src="<?= $info["rc_img1"] ?>" />
                                        <input type='hidden' name='rc_img1_ori' value='<?= $info["rc_img1"] ?>' />
                                    <?php endif; ?>
                                </span>
                            </label>
                            <label class="form-pic <?= isset($info["rc_img2"]) && $info["rc_img2"] != "" ? "has-image" : "" ?>">
                                <input type="file" name="rc_img2" accept="image/*" class="js-file-preview">
                                <span class="preview">
                                    <?php if (isset($info["rc_img2"]) && $info["rc_img2"] != "") : ?>
                                        <img src="<?= $info["rc_img2"] ?>" />
                                        <input type='hidden' name='rc_img2_ori' value='<?= $info["rc_img2"] ?>' />
                                    <?php endif; ?>
                                </span>
                            </label>
                            <label class="form-pic <?= isset($info["rc_img3"]) && $info["rc_img3"] != "" ? "has-image" : "" ?>">
                                <input type="file" name="rc_img3" accept="image/*" class="js-file-preview">
                                <span class="preview">
                                    <?php if (isset($info["rc_img3"]) && $info["rc_img3"] != "") : ?>
                                        <img src="<?= $info["rc_img3"] ?>" />
                                        <input type='hidden' name='rc_img3_ori' value='<?= $info["rc_img3"] ?>' />
                                    <?php endif; ?>
                                </span>
                            </label>
                            <label class="form-pic <?= isset($info["rc_img4"]) && $info["rc_img4"] != "" ? "has-image" : "" ?>">
                                <input type="file" name="rc_img4" accept="image/*" class="js-file-preview">
                                <span class="preview">
                                    <?php if (isset($info["rc_img4"]) && $info["rc_img4"] != "") : ?>
                                        <img src="<?= $info["rc_img4"] ?>" />
                                        <input type='hidden' name='rc_img4_ori' value='<?= $info["rc_img4"] ?>' />
                                    <?php endif; ?>
                                </span>
                            </label>
                        </div>

                    </div>

                    <!-- 상세등록 버튼 -->
                    <div class="form-row">
                        <a href="javascript:void(0);" class="detail-register-btn js-detail-register">
                            <span class="add-icon">+</span>상세등록
                        </a>
                    </div>

                    <div class="sticky-bottom">
                        <button id="btnPrev" type="button" class="btn btn-secondary">이전</button>
                        <button id="btnStep" type="button" class="btn btn-primary">미리보기</button>
                    </div>
                </div>
            </div>
        </form>
    </div><!-- //page-content -->
</div>

<script>
    // localStorage 저장/복원 함수
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

        var formData = {
            rc_desc: $("[name='rc_desc']").val(),
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

    function loadPage3Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var storageKey = "recruit_draft_" + rcIdx + "_page3";
        var saved = localStorage.getItem(storageKey);

        if (!saved) return;

        try {
            var formData = JSON.parse(saved);
            if (!formData) return;

            // 상세업무정보 복원 (서버 데이터가 없을 때만)
            if (formData.rc_desc && $("[name='rc_desc']").val() == "") {
                $("[name='rc_desc']").val(formData.rc_desc);
                // textarea 자동 높이 조정
                if (typeof autoHeight === 'function') {
                    setTimeout(function() {
                        autoHeight($("[name='rc_desc']")[0]);
                    }, 100);
                }
            }

            // 해시태그 복원 (서버 데이터가 없을 때만)
            if (formData.rc_hash && formData.rc_hash.length > 0 && $("[name='rc_hash[]']:checked").length == 0) {
                $("[name='rc_hash[]']").prop("checked", false);
                formData.rc_hash.forEach(function(val) {
                    var $checkbox = $("[name='rc_hash[]'][value='" + val + "']");
                    if ($checkbox.length === 0) {
                        // 동적으로 추가된 해시태그인 경우 다시 생성
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

            // 날짜 복원 (flatpickr 초기화 후, 서버 데이터가 없을 때만)
            setTimeout(function() {
                if (formData.rc_sdate && $("[name='rc_sdate']").val() == "") {
                    var startPicker = flatpickr('.js-datepicker-start');
                    if (startPicker) {
                        startPicker.setDate(formData.rc_sdate, false);
                    }
                }
                if (formData.rc_edate && $("[name='rc_edate']").val() == "") {
                    var endPicker = flatpickr('.js-datepicker-end');
                    if (endPicker) {
                        endPicker.setDate(formData.rc_edate, false);
                    }
                }
            }, 500);

            // 상세등록 섹션 표시 상태 복원
            if (formData.detail_section_visible && !$(".js-detail-section").hasClass("show")) {
                $(".js-detail-section").addClass("show").show();
            }

            // 희망요일 복원 (서버 데이터가 없을 때만)
            if ($("[name='rc_day[]']:checked").length == 0 && !$("[name='rc_day_all']").is(":checked")) {
                if (formData.rc_day_all === "Y") {
                    $("[name='rc_day_all']").prop("checked", true).trigger("change");
                } else if (formData.rc_day && formData.rc_day.length > 0) {
                    $("[name='rc_day[]']").prop("checked", false);
                    formData.rc_day.forEach(function(val) {
                        $("[name='rc_day[]'][value='" + val + "']").prop("checked", true);
                    });
                }
            }

            // 희망성별 복원 (서버 데이터가 없을 때만)
            if ($("[name='rc_sex[]']:checked").length == 0 && !$("[name='rc_sex_all']").is(":checked")) {
                if (formData.rc_sex_all === "Y") {
                    $("[name='rc_sex_all']").prop("checked", true).trigger("change");
                } else if (formData.rc_sex && formData.rc_sex.length > 0) {
                    $("[name='rc_sex[]']").prop("checked", false);
                    formData.rc_sex.forEach(function(val) {
                        $("[name='rc_sex[]'][value='" + val + "']").prop("checked", true);
                    });
                }
            }

            // 희망국적 복원 (서버 데이터가 없을 때만)
            if ($("[name='rc_nation[]']:checked").length == 0 && !$("[name='rc_nation_all']").is(":checked")) {
                if (formData.rc_nation_all === "Y") {
                    $("[name='rc_nation_all']").prop("checked", true).trigger("change");
                } else if (formData.rc_nation && formData.rc_nation.length > 0) {
                    $("[name='rc_nation[]']").prop("checked", false);
                    formData.rc_nation.forEach(function(val) {
                        $("[name='rc_nation[]'][value='" + val + "']").prop("checked", true);
                    });
                }
            }

            // 희망나이 복원 (서버 데이터가 없을 때만)
            if ($("[name='rc_sage']").val() == "" && $("[name='rc_eage']").val() == "" && !$("[name='rc_age_all']").is(":checked")) {
                if (formData.rc_age_all === "Y") {
                    $("[name='rc_age_all']").prop("checked", true);
                } else {
                    if (formData.rc_sage) $("[name='rc_sage']").val(formData.rc_sage);
                    if (formData.rc_eage) $("[name='rc_eage']").val(formData.rc_eage);
                }
            }
        } catch (e) {
            console.error("페이지3 데이터 복원 오류:", e);
        }
    }

    window.addEventListener('DOMContentLoaded', function() {
        // 날짜 선택 초기화
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

            // 시작일이 없으면 오늘 날짜를 기본값으로 설정
            var startDate = defaultStartDate != "" && defaultStartDate >= todayStr ? defaultStartDate : todayStr;

            // 종료일이 없으면 시작일 기준 60일 후를 기본값으로 설정
            var endDate = defaultEndDate != "" ? defaultEndDate : null;
            if (!endDate) {
                var startDateObj = new Date(startDate);
                var endDateObj = new Date(startDateObj);
                endDateObj.setDate(endDateObj.getDate() + 60);
                endDate = endDateObj.getFullYear() + '-' +
                    (endDateObj.getMonth() + 1).toString().padStart(2, '0') + '-' +
                    endDateObj.getDate().toString().padStart(2, '0');
            }

            // 시작일 기준 최대 날짜 계산 (60일 후)
            var startDateObj = new Date(startDate);
            var maxEndDate = new Date(startDateObj);
            maxEndDate.setDate(maxEndDate.getDate() + 60);

            // 시작일 달력
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
                maxDate: new Date().fp_incr(60), // 오늘부터 최대 60일까지
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
                    // 시작일이 변경되면 종료일의 최소/최대 날짜 업데이트
                    if (selectedDates.length > 0) {
                        var endPicker = flatpickr('.js-datepicker-end');
                        if (endPicker) {
                            var selectedStartDate = selectedDates[0];
                            // 종료일의 최소 날짜는 시작일
                            endPicker.set('minDate', selectedStartDate);

                            // 종료일의 최대 날짜는 시작일 기준 60일 후
                            var maxDate = new Date(selectedStartDate);
                            maxDate.setDate(maxDate.getDate() + 60);
                            endPicker.set('maxDate', maxDate);

                            // 현재 종료일이 새로운 최대 날짜를 초과하면 최대 날짜로 설정
                            var currentEndDate = endPicker.selectedDates[0];
                            if (currentEndDate && currentEndDate > maxDate) {
                                endPicker.setDate(maxDate);
                            }
                        }
                    }
                    // 날짜 변경 시 저장
                    savePage3Data();
                }
            });

            // 종료일 달력
            flatpickr('.js-datepicker-end', {
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
                maxDate: maxEndDate, // 시작일 기준 60일 후
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

        // 페이지 로드 시 데이터 복원 (flatpickr 초기화 후)
        setTimeout(function() {
            loadPage3Data();
        }, 1000);

        // 실시간 저장: 입력 필드 변경 시
        $("[name='rc_desc']").on("input", savePage3Data);
        $("[name='rc_hash[]']").on("change", savePage3Data);
        $("[name='rc_day[]'], [name='rc_day_all']").on("change", savePage3Data);
        $("[name='rc_sex[]'], [name='rc_sex_all']").on("change", savePage3Data);
        $("[name='rc_nation[]'], [name='rc_nation_all']").on("change", savePage3Data);
        $("[name='rc_sage'], [name='rc_eage'], [name='rc_age_all']").on("change", savePage3Data);

        // 날짜 변경 시 저장
        $(document).on("change", "[name='rc_sdate'], [name='rc_edate']", function() {
            savePage3Data();
        });

        // 상세등록 섹션 표시/숨김 시 저장
        $(".js-detail-register").on("click", function(e) {
            e.preventDefault();
            var $sections = $(".js-detail-section");
            var $button = $(this);

            // show 클래스로 표시 상태 확인
            var isVisible = $sections.hasClass("show");

            if (isVisible) {
                // 숨기기
                $sections.removeClass("show").slideUp(300);
            } else {
                // 보이기
                $sections.addClass("show").slideDown(300, function() {
                    // 상세등록 버튼 위치로 스크롤
                    var buttonTop = $button.offset().top;
                    var windowHeight = $(window).height();
                    var scrollTarget = buttonTop - (windowHeight / 2);
                    $('html, body').animate({
                        scrollTop: scrollTarget
                    }, 400);
                });
            }
            setTimeout(savePage3Data, 350);
        });

        // 해시태그 추가 작성 팝업
        $(".js-add-hashtag").on("click", function(e) {
            e.preventDefault();
            var $inputArea = $(this).closest(".form-row").find(".hashtag-input-area");
            var $input = $inputArea.find(".js-hashtag-input");

            // 직접입력 영역 표시
            $inputArea.slideDown(300);
            // 입력 필드에 포커스
            setTimeout(function() {
                $input.focus();
            }, 350);
        });

        // 해시태그 확인 버튼 클릭
        $(".js-confirm-hashtag").on("click", function(e) {
            e.preventDefault();
            var $input = $(this).siblings(".js-hashtag-input");
            var hashText = $input.val().trim();

            if (hashText == "") {
                commonAlert("해시태그를 입력해주세요.", function() {});
                return;
            }

            // # 제거 후 추가
            var cleanHash = hashText.replace(/#/g, '').trim();
            if (cleanHash == "") {
                commonAlert("올바른 해시태그를 입력해주세요.", function() {});
                return;
            }

            // 이미 존재하는지 확인
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

            // 최대 4개까지 제한 확인
            var checkedCount = $("[name='rc_hash[]']:checked").length;
            if (checkedCount >= 4) {
                commonAlert("해시태그는 최대 4개까지 선택 가능합니다.", function() {});
                return;
            }

            // 해시태그 추가
            var $newLabel = $('<label class="form-toggle-btn2 sm tag">' +
                '<input type="checkbox" name="rc_hash[]" value="' + cleanHash + '" checked>' +
                '<span class="btn">' + cleanHash + '</span>' +
                '</label>');

            var $addButton = $(".js-add-hashtag");
            $addButton.before($newLabel);

            // 입력 필드 초기화 및 영역 숨김
            $input.val("");
            $inputArea.slideUp(300);

            // 해시태그 추가 후 저장
            savePage3Data();
        });

        // 해시태그 체크박스 클릭 시 최대 4개 제한
        $(document).on("change", "[name='rc_hash[]']", function() {
            var checkedCount = $("[name='rc_hash[]']:checked").length;
            if (checkedCount > 4) {
                $(this).prop("checked", false);
                commonAlert("해시태그는 최대 4개까지 선택 가능합니다.", function() {});
            }
        });


        // 희망요일 기본값 설정 (월~금이 보이도록 기본 체크)
        (function() {
            var weekdayDefaults = ["월", "화", "수", "목", "금"];
            var $dayCheckboxes = $("[name='rc_day[]']");
            var hasExistingSelection = $dayCheckboxes.is(":checked");
            var isAllDaysSelected = $("[name='rc_day_all']").is(":checked");

            // 기존 데이터가 없고 요일무관도 아닐 때만 기본값 적용
            if (!hasExistingSelection && !isAllDaysSelected) {
                $dayCheckboxes.each(function() {
                    var $checkbox = $(this);
                    if (weekdayDefaults.indexOf($checkbox.val()) !== -1) {
                        $checkbox.prop("checked", true);
                    }
                });
            }
        }());

        // 요일무관 체크박스 처리
        $("[name='rc_day_all']").on("change", function() {
            if ($(this).is(":checked")) {
                // 무관 클릭 시: 모든 요일 체크
                $("[name='rc_day[]']").prop("checked", true);
            } else {
                // 무관 해제 시: 모든 요일 체크 해제
                $("[name='rc_day[]']").prop("checked", false);
            }
        });

        $("[name='rc_day[]']").on("change", function() {
            // 개별 요일 선택 시 요일무관 체크 해제
            if ($(this).is(":checked")) {
                $("[name='rc_day_all']").prop("checked", false);
            }
        });

        // 성별무관 체크박스 처리
        $("[name='rc_sex_all']").on("change", function() {
            if ($(this).is(":checked")) {
                // 무관 클릭 시: 남자, 여자 모두 체크
                $("[name='rc_sex[]']").prop("checked", true);
            } else {
                // 무관 해제 시: 모든 성별 체크 해제
                $("[name='rc_sex[]']").prop("checked", false);
            }
        });

        $("[name='rc_sex[]']").on("change", function() {
            var $clicked = $(this);
            var isChecked = $clicked.is(":checked");

            if (isChecked) {
                // 체크되지 않은 칸을 클릭한 경우
                // 다른 모든 성별 체크 해제 (2개 중복 체크 불가)
                $("[name='rc_sex[]']").not($clicked).prop("checked", false);
                // 무관 체크 해제
                $("[name='rc_sex_all']").prop("checked", false);
            }
        });

        // 국적무관 체크박스 처리
        $("[name='rc_nation_all']").on("change", function() {
            if ($(this).is(":checked")) {
                // 무관 클릭 시: 내국인, 외국인 모두 체크
                $("[name='rc_nation[]']").prop("checked", true);
            } else {
                // 무관 해제 시: 모든 국적 체크 해제
                $("[name='rc_nation[]']").prop("checked", false);
            }
        });

        $("[name='rc_nation[]']").on("change", function() {
            var $clicked = $(this);
            var isChecked = $clicked.is(":checked");

            if (isChecked) {
                // 체크되지 않은 칸을 클릭한 경우
                // 다른 모든 국적 체크 해제 (2개 중복 체크 불가)
                $("[name='rc_nation[]']").not($clicked).prop("checked", false);
                // 무관 체크 해제
                $("[name='rc_nation_all']").prop("checked", false);
            }
        });

        // 나이무관 체크박스 처리
        $("[name='rc_age_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_sage']").val("");
                $("[name='rc_eage']").val("");
            }
        });

        $("[name='rc_sage'], [name='rc_eage']").on("change", function() {
            if ($(this).val() != "") {
                $("[name='rc_age_all']").prop("checked", false);
            }
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

        // 파일 미리보기 처리
        $(document).on("change", ".js-file-preview", function() {
            var $label = $(this).closest(".form-pic");
            var file = this.files[0];

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var $preview = $label.find(".preview");
                    $preview.html('<img src="' + e.target.result + '" />');
                    $label.addClass("has-image");
                };
                reader.readAsDataURL(file);
            } else {
                // 파일이 없으면 이미지 제거 및 카메라 아이콘 표시
                var $preview = $label.find(".preview");
                $preview.html("");
                $label.removeClass("has-image");
            }
        });

        // 이전 버튼
        $("#btnPrev").click(function() {
            savePage3Data(); // 저장 후 이동
            if ($("[name=rc_idx]").val() != "0") {
                location.href = "/compage/recruit_write2_1/" + $("[name=rc_idx]").val();
            } else {
                location.href = "/compage/recruit_write2_1";
            }
        });

        // 미리보기 버튼 (임시 저장 후 미리보기 표시, rc_show_yn="N"으로 저장)
        $("#btnStep").click(function() {
            // 상세업무정보 검증
            if ($("[name='rc_desc']").val() == "") {
                commonAlert("상세업무정보를 입력해주세요.", function() {
                    $("[name='rc_desc']").focus();
                });
                return;
            }

            // 채용공고기간 검증
            if ($("[name='rc_sdate']").val() == "" || $("[name='rc_edate']").val() == "") {
                commonAlert("채용공고기간을 선택해주세요.", function() {});
                return;
            }

            // 종료일이 시작일보다 이전인지 확인
            var startDate = new Date($("[name='rc_sdate']").val());
            var endDate = new Date($("[name='rc_edate']").val());
            if (endDate < startDate) {
                commonAlert("종료일은 시작일보다 이후여야 합니다.", function() {});
                return;
            }

            // localStorage에 저장
            savePage3Data();

            // 해시태그 배열로 변환
            var hashArray = [];
            $("[name='rc_hash[]']:checked").each(function() {
                hashArray.push($(this).val());
            });
            var hashString = hashArray.join(",");

            // 미리보기를 위해 서버에 임시 저장 (rc_show_yn="N")
            // 파일 업로드가 있는지 확인
            var hasFiles = false;
            var fileInputs = ["rc_img1", "rc_img2", "rc_img3", "rc_img4"];
            for (var i = 0; i < fileInputs.length; i++) {
                var fileInput = $("[name='" + fileInputs[i] + "']")[0];
                if (fileInput && fileInput.files && fileInput.files.length > 0) {
                    hasFiles = true;
                    break;
                }
            }

            // 저장 응답 처리 함수
            function handlePreviewSave(ret) {
                if (ret.code == "00") {
                    // 서버 저장 성공 시 rc_idx 업데이트
                    var currentRcIdx = $("[name=rc_idx]").val() || "0";
                    var savedRcIdx = (ret.data && ret.data.rc_idx) ? ret.data.rc_idx : currentRcIdx;

                    if (savedRcIdx && savedRcIdx != "0") {
                        $("[name='rc_idx']").val(savedRcIdx);
                    }

                    // 미리보기를 위해 채용공고 임시 저장(rc_show_yn="N") 후 미리보기 페이지로 이동
                    var completeParams = "rc_idx=" + savedRcIdx + "&mm_comp_num=0&mm_comp_num_org=0&preview_mode=1";
                    comAjax("/compage_proc/recruit_complete", completeParams, function(ret2) {
                        if (ret2.code == "00") {
                            // 저장된 rc_idx로 미리보기 페이지 이동
                            var finalRcIdx = (ret2.data && ret2.data.rc_idx) ? ret2.data.rc_idx : savedRcIdx;

                            if (finalRcIdx != "0" && finalRcIdx != "") {
                                location.href = "/compage/recruit_view/" + finalRcIdx + "?preview_mode=1";
                            } else {
                                location.href = "/compage/recruit_list";
                            }
                        } else {
                            commonAlert(ret2.msg, function() {});
                            return;
                        }
                    });
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            }

            // 파일이 있으면 FormData 사용, 없으면 기존 방식 사용
            if (hasFiles) {
                // FormData를 사용하여 파일과 함께 전송
                var formData = new FormData($("#frm")[0]);
                // 해시태그를 쉼표로 구분된 문자열로 추가
                formData.append("rc_hash_string", hashString);

                // FormData를 사용한 AJAX 전송
                $.ajax({
                    url: "/compage_proc/recruit_write3_1",
                    type: "POST",
                    data: formData,
                    processData: false, // FormData 사용 시 필수
                    contentType: false, // FormData 사용 시 필수
                    dataType: "json",
                    success: function(ret) {
                        handlePreviewSave(ret);
                    },
                    error: function(xhr, status, error) {
                        console.error("파일 업로드 오류:", error);
                        commonAlert("파일 업로드 중 오류가 발생했습니다.", function() {});
                    }
                });
            } else {
                // 파일이 없으면 기존 방식 사용
                var params = $("#frm").serialize();
                // 해시태그를 쉼표로 구분된 문자열로 추가
                params += "&rc_hash_string=" + encodeURIComponent(hashString);

                comAjax("/compage_proc/recruit_write3_1", params, function(ret) {
                    handlePreviewSave(ret);
                });
            }
        });
    });
</script>