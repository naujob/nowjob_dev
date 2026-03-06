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

    .page-content {
        padding-bottom: 7rem;
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

    /* 복리후생 섹션 간격 조정 */
    .addition-info-form .form-row .form-row {
        margin-top: 1.5rem;
    }

    .addition-info-form .form-row>.form-row:first-child {
        margin-top: 0;
    }

    .addition-info-form .form-row .form-label {
        margin-bottom: 1rem;
    }

    .addition-info-form .form-toggle-group {
        margin-top: 0.5rem;
    }

    .addition-info-form .form-toggle-btn3 .btn {
        min-height: 5rem;
        padding: 1.5rem 0.625rem;
    }

    .addition-info-form h2.form-label {
        font-size: 1.125rem;
    }

    .section.addition-info-form {
        margin-top: 0;
    }

    /* 토글 버튼 height 통일 */
    .form-toggle-group .form-toggle-btn2 .btn {
        height: 3.5rem;
        line-height: 3.5rem;
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

    .form-label em {
        color: #1B9D7D;
        font-style: normal;
    }

    .form-row.js-detail-section {
        display: none;
        margin-top: 0.875rem;
    }

    .form-row.js-detail-section.show {
        display: block;
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

    .form-toggle-group.col-3 .btn-add-more {
        display: inline-block;
        min-width: calc(33.333% - 0.5rem);
        height: 3.5rem;
        padding: 0;
        margin-top: 0.8rem;
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

    .sticky-bottom {
        display: flex;
        gap: 0.5rem;
    }

    .sticky-bottom .btn {
        flex: 1;
    }

    /* 이 페이지에서만 form 위치 보정 */
    .page.page-write #frm {
        top: 0;
    }

    /* 미리보기/최종저장 버튼 톤 분리 */
    .sticky-bottom #btnPreview {
        background: #ffffff;
        color: #1B9D7D;
        border: 1px solid #1B9D7D;
    }

    .sticky-bottom #btnSubmit {
        background: #1B9D7D;
        color: #ffffff;
        border: 1px solid #1B9D7D;
    }
</style>

<div class="page page-write">
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
                            <span class="address-text"></span>
                        </a>
                    </div>

                    <!-- 업무구분 섹션 -->
                    <div class="form-row">
                        <p class="form-label"><span class="required-asterisk">*</span>업무구분 <em>업무구분은 2개까지 선택 가능</em></p>
                        <div class="form-toggle-group col-3">
                            <?php
                            $allowed_work_list = ["생산", "물류", "건설", "운전배달", "식당", "서비스", "사무직", "상담", "기타"];
                            if (isset($rc_wrok_list)) {
                                foreach ($rc_wrok_list as $row) :
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

                    <!-- 복리후생 섹션 -->
                    <div class="form-row">
                        <h2 class="form-label">복리후생</h2>

                        <!-- 보험 섹션 -->
                        <div class="form-row">
                            <h3 class="form-label">보험</h3>
                            <div class="form-toggle-group col-4">
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="국민연금" <?= isset($info["rc_life_type"]) && in_array_return("국민연금", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit1-1">국민연금</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="고용보험" <?= isset($info["rc_life_type"]) && in_array_return("고용보험", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit1-2">고용보험</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="산재보험" <?= isset($info["rc_life_type"]) && in_array_return("산재보험", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit1-3">산재보험</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="건강보험" <?= isset($info["rc_life_type"]) && in_array_return("건강보험", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit1-4">건강보험</span>
                                </label>
                            </div>
                        </div>

                        <!-- 휴가 섹션 -->
                        <div class="form-row">
                            <h3 class="form-label">휴가</h3>
                            <div class="form-toggle-group col-4">
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="연차" <?= isset($info["rc_life_type"]) && in_array_return("연차", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit2-1">연차</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="월차" <?= isset($info["rc_life_type"]) && in_array_return("월차", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit2-2">월차</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="여름휴가" <?= isset($info["rc_life_type"]) && in_array_return("여름휴가", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit2-3">여름휴가</span>
                                </label>
                            </div>
                        </div>

                        <!-- 수당/퇴직금 섹션 -->
                        <div class="form-row">
                            <h3 class="form-label">수당/퇴직금</h3>
                            <div class="form-toggle-group col-4">
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="야근근로수당" <?= isset($info["rc_life_type"]) && in_array_return("야근근로수당", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit3-1">야근근로수당</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="휴일근로수당" <?= isset($info["rc_life_type"]) && in_array_return("휴일근로수당", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit3-2">휴일근로수당</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="장기근속수당" <?= isset($info["rc_life_type"]) && in_array_return("장기근속수당", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit3-3">장기근속수당</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="연장근로수당" <?= isset($info["rc_life_type"]) && in_array_return("연장근로수당", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit3-4">연장근로수당</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="인센티브제" <?= isset($info["rc_life_type"]) && in_array_return("인센티브제", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit3-5">인센티브제</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="퇴직금" <?= isset($info["rc_life_type"]) && in_array_return("퇴직금", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit3-6">퇴직금</span>
                                </label>
                            </div>
                        </div>

                        <!-- 생활편의 지원 섹션 -->
                        <div class="form-row">
                            <h3 class="form-label">생활편의 지원</h3>
                            <div class="form-toggle-group col-4">
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="조식제공" <?= isset($info["rc_life_type"]) && in_array_return("조식제공", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit4-1">조식제공</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="중식제공" <?= isset($info["rc_life_type"]) && in_array_return("중식제공", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit4-2">중식제공</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="석식제공" <?= isset($info["rc_life_type"]) && in_array_return("석식제공", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit4-3">석식제공</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="근무복지급" <?= isset($info["rc_life_type"]) && in_array_return("근무복지급", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit4-4">근무복지급</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="통근버스운행" <?= isset($info["rc_life_type"]) && (in_array_return("통근버스운행", $info["rc_life_type"], "check") || in_array_return("통근버스 운행", $info["rc_life_type"], "check")) ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit4-5">통근버스 운행</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="차량유류비지원" <?= isset($info["rc_life_type"]) && in_array_return("차량유류비지원", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit4-6">차량유류비지원</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="기숙사운영" <?= isset($info["rc_life_type"]) && in_array_return("기숙사운영", $info["rc_life_type"], "check") ? "checked" : "" ?>>
                                    <span class="btn benefit-label benefit4-7">기숙사운영</span>
                                </label>
                            </div>
                        </div>
                    </div>

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

                    <div class="form-row">
                        <a href="javascript:void(0);" class="detail-register-btn js-detail-register">
                            <span class="add-icon">+</span>상세등록
                        </a>
                    </div>

                    <div class="sticky-bottom">
                        <button id="btnPreview" type="button" class="btn btn-primary">미리보기</button>
                        <button id="btnSubmit" type="button" class="btn btn-primary">최종저장</button>
                    </div>
                </div>
            </div>
        </form>
    </div><!-- //page-content -->
</div>

<script>
    var serverAddrData = <?= (isset($info["rc_idx"]) && $info["rc_idx"] != 0 && !empty($info["rc_name"]) && isset($info["addr"]) && is_array($info["addr"]) && !empty($info["addr"]["ad_addr"])) ? json_encode($info["addr"], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) : 'null' ?>;

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

    function loadServerAddressData() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var rcName = $("[name='rc_name']").val() || "";

        if (rcIdx == "0" || rcName == "") {
            return false;
        }

        if (typeof serverAddrData !== 'undefined' && serverAddrData !== null && serverAddrData.ad_addr) {
            $("#ad_zipcode").val(serverAddrData.ad_zipcode || "");
            $("#ad_jibun").val(serverAddrData.ad_jibun || "");
            $("#ad_doro").val(serverAddrData.ad_doro || "");
            $("#ad_addr").val(serverAddrData.ad_addr || "");
            $("#ad_detail").val(serverAddrData.ad_detail || "");
            $("#ad_gps_x").val(serverAddrData.ad_gps_x || "");
            $("#ad_gps_y").val(serverAddrData.ad_gps_y || "");

            var $btnAddr = $("#btnAddrAdd");
            var displayAddr = serverAddrData.ad_addr || "";
            if (serverAddrData.ad_detail && serverAddrData.ad_detail.trim() !== "") {
                displayAddr = displayAddr + " " + serverAddrData.ad_detail.trim();
            }
            $btnAddr.find(".address-text").text(displayAddr);
            $btnAddr.addClass("has-address");

            return true;
        }
        return false;
    }

    function loadPage1Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var storageKey = "recruit_draft_" + rcIdx + "_page1";
        var saved = localStorage.getItem(storageKey);

        if (!saved) return;

        try {
            var formData = JSON.parse(saved);
            if (!formData) return;

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
        } catch (e) {
            console.error("페이지1 데이터 복원 오류:", e);
        }
    }

    function loadPage2Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var storageKey = "recruit_draft_" + rcIdx + "_page2";
        var saved = localStorage.getItem(storageKey);

        if (!saved) return;

        try {
            var formData = JSON.parse(saved);
            if (!formData) return;

            if (formData.rc_life_type && formData.rc_life_type.length > 0) {
                $("[name='rc_life_type[]']").prop("checked", false);
                formData.rc_life_type.forEach(function(val) {
                    $("[name='rc_life_type[]'][value='" + val + "']").prop("checked", true);
                });
            }
        } catch (e) {
            console.error("페이지2 데이터 복원 오류:", e);
        }
    }

    function loadPage3Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var storageKey = "recruit_draft_" + rcIdx + "_page3";
        var saved = localStorage.getItem(storageKey);

        if (!saved) return;

        try {
            var formData = JSON.parse(saved);
            if (!formData) return;

            if (typeof formData.rc_desc !== "undefined") {
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
        } catch (e) {
            console.error("페이지3 데이터 복원 오류:", e);
        }
    }

    function clearRecruitDraftData(rcIdx) {
        if (!rcIdx) rcIdx = "0";
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page1");
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page2");
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page3");
    }

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

    function selectAddrInfo(zipcode, doro, jibun, addr, x, y, detail) {
        detail = detail || "";

        $("#ad_zipcode").val(zipcode);
        $("#ad_jibun").val(jibun);
        $("#ad_doro").val(doro);
        $("#ad_addr").val(jibun);
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

    function buildHashString() {
        var hashArray = [];
        $("[name='rc_hash[]']:checked").each(function() {
            hashArray.push($(this).val());
        });
        return hashArray.join(",");
    }

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

        if ($("[name='rc_desc']").val() == "") {
            commonAlert("상세업무정보를 입력해주세요.", function() {
                $("[name='rc_desc']").focus();
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

    function saveStep1And2(onSuccess) {
        collectRcWorkImg();
        var params = $("#frm").serialize();
        var prevIdx = $("[name='rc_idx']").val() || "0";

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

    function saveStep3AndComplete(mode) {
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

                var completeParams = "rc_idx=" + savedRcIdx;
                if (mode === "preview") {
                    completeParams = "rc_idx=" + savedRcIdx + "&mm_comp_num=0&mm_comp_num_org=0&preview_mode=1";
                }

                comAjax("/compage_proc/recruit_complete", completeParams, function(ret2) {
                    if (ret2.code == "00") {
                        var finalRcIdx = (ret2.data && ret2.data.rc_idx) ? ret2.data.rc_idx : savedRcIdx;

                        if (finalRcIdx && finalRcIdx != "0") {
                            clearRecruitDraftData(finalRcIdx);
                        }
                        clearRecruitDraftData("0");

                        if (mode === "preview") {
                            if (finalRcIdx != "0" && finalRcIdx != "") {
                                location.href = "/compage/recruit_view/" + finalRcIdx + "?preview_mode=1";
                            } else {
                                location.href = "/compage/recruit_list";
                            }
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
                    console.error("파일 업로드 오류:", error);
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

    window.addEventListener('DOMContentLoaded', function() {
        var hasServerData = loadServerAddressData();

        if (!hasServerData) {
            loadPage1Data();
        }
        loadPage2Data();

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

            flatpickr('.js-datepicker-start', {
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
                    if (selectedDates.length > 0) {
                        var endPicker = flatpickr('.js-datepicker-end');
                        if (endPicker) {
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
                    }
                    savePage3Data();
                }
            });

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
            loadPage3Data();
        }, 1000);

        updateSalaryOptions();

        $("[name='rc_name']").on("input", savePage1Data);
        $("#rc_price_type_main").on("change", function() {
            updateSalaryOptions();
            savePage1Data();
        });
        $("#rc_price_amount").on("input", savePage1Data);
        $("[name='rc_price_possible']").on("change", savePage1Data);

        $("[name='rc_work[]']").on("change", savePage1Data);
        $("[name='rc_work_type[]']").on("change", savePage1Data);
        $("[name='rc_work_negotiation']").on("change", function() {
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

        $("[name='rc_work_type[]']").on("change", function() {
            if ($("[name='rc_work_negotiation']").is(":checked")) {
                if (!$(this).is(":checked")) {
                    $(this).prop("checked", true);
                }
            }
        });

        $(document).on("mousedown", "#salary-options-group input[type='radio']", function(e) {
            var $radio = $(this);
            $radio.data("wasChecked", $radio.is(":checked"));
        });

        $(document).on("click", "#salary-options-group input[type='radio']", function(e) {
            var $radio = $(this);
            var wasChecked = $radio.data("wasChecked");

            if (wasChecked) {
                e.preventDefault();
                e.stopPropagation();
                $("input[name='" + $radio.attr("name") + "']").prop("checked", false);
                $radio.trigger("change");
            }
        });

        $(document).on("click", ".form-checkbox .label", function(e) {
            e.preventDefault();
            var $input = $(this).siblings("input[type='checkbox'], input[type='radio']");
            if ($input.length > 0) {
                if ($input.attr("type") === "radio") {
                    if ($input.is(":checked")) {
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

        $(".js-detail-register").on("click", function(e) {
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

        $("[name='rc_day_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_day[]']").prop("checked", true);
            } else {
                $("[name='rc_day[]']").prop("checked", false);
            }
        });

        $("[name='rc_day[]']").on("change", function() {
            if ($("[name='rc_day_all']").is(":checked")) {
                $("[name='rc_day_all']").prop("checked", false);
            }
        });

        $("[name='rc_sex_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_sex[]']").prop("checked", true);
            } else {
                $("[name='rc_sex[]']").prop("checked", false);
            }
        });

        $("[name='rc_sex[]']").on("change", function() {
            var $clicked = $(this);
            var isChecked = $clicked.is(":checked");

            if (isChecked) {
                $("[name='rc_sex[]']").not($clicked).prop("checked", false);
                $("[name='rc_sex_all']").prop("checked", false);
            }
        });

        $("[name='rc_nation_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_nation[]']").prop("checked", true);
            } else {
                $("[name='rc_nation[]']").prop("checked", false);
            }
        });

        $("[name='rc_nation[]']").on("change", function() {
            var $clicked = $(this);
            var isChecked = $clicked.is(":checked");

            if (isChecked) {
                $("[name='rc_nation[]']").not($clicked).prop("checked", false);
                $("[name='rc_nation_all']").prop("checked", false);
            }
        });

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
                var $preview = $label.find(".preview");
                $preview.html("");
                $label.removeClass("has-image");
            }
        });

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
            clearRecruitDraftData($("[name='rc_idx']").val() || "0");
            location.href = "/compage/recruit_list";
        });

        history.pushState(null, null, location.href);

        window.addEventListener('popstate', function(e) {
            history.pushState(null, null, location.href);
            savePage1Data();
            savePage2Data();
            savePage3Data();
            clearRecruitDraftData($("[name='rc_idx']").val() || "0");
            location.href = "/compage/recruit_list";
        });
    });
</script>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>
