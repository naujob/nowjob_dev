<style>
    html.autofill-hide {
        visibility: hidden;
    }
    /* resume_write4 페이지 스타일 */
    .required-asterisk {
        color: #ff3333;
        margin-right: 2px;
    }

    .required-label {
        font-size: 0.9375rem;
        color: rgb(0, 0, 0);
        font-weight: 400;
    }

    .top-bar .required-label {
        position: absolute;
        top: 138%;
        right: 1.4375rem;
        transform: translateY(-50%);
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
    }

    .detail-register-btn .add-icon {
        font-size: 1.5rem;
        font-weight: 300;
        margin-right: 4px;
        vertical-align: middle;
    }

    /* 날짜 선택 필드 스타일 개선 */
    .datepicker-wrapper {
        position: relative;
        display: block;
    }

    .datepicker-wrapper .form-text {
        padding-right: 2.5rem;
        cursor: pointer;
        background-color: #fff;
    }

    .datepicker-wrapper .form-text:focus {
        border-color: #57CCAF;
        outline: none;
    }

    .datepicker-icon {
        position: absolute;
        right: 0.75rem;
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

    /* 달력 팝업 개선 */
    .flatpickr-calendar {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .flatpickr-monthSelect-month.selected,
    .flatpickr-monthSelect-month.startRange,
    .flatpickr-monthSelect-month.endRange {
        background: #57CCAF;
        color: #fff;
    }

    .flatpickr-monthSelect-month.inRange {
        background: rgba(87, 204, 175, 0.2);
    }

    /* 나의강점 섹션 위치 조정 */
    .form-row.my-strength-section {
        margin-top: 1.25rem;
    }

    /* 상세등록 섹션들 위치 조정 - 나의강점 아래로 표시 */
    .form-row.my-strength-section+.form-row.js-detail-section,
    .form-row.js-detail-section {
        display: none;
        margin-top: 0.875rem;
    }

    .form-row.js-detail-section.show {
        display: block;
    }

    /* 자기소개 textarea 최소 높이 설정 */
    textarea[name="ru_desc"] {
        min-height: 20.25rem;
    }

    /* 자기소개 섹션 위치 조정 - 위로 살짝 이동 */
    .form-row:first-of-type {
        margin-top: -2.5rem;
    }

    /* form#frm 위치 조정 */
    form#frm {
        top: 6vh;
    }

    /* 스크롤 문제 해결: sticky-bottom이 콘텐츠를 가리지 않도록 하단 여백 추가 */
    .page.page-write .page-content {
        padding-bottom: 5.5rem;
        /* sticky-bottom 높이만큼 여백 확보 */
    }

    /* section.addition-info-form 하단 여백 추가 */
    .page.page-write .page-content .section.addition-info-form {
        padding-bottom: 1rem;
        /* 추가 여백 */
    }

    /* container 하단 여백 추가 */
    .page.page-write .page-content .section.addition-info-form .container {
        padding-bottom: 1rem;
        /* 추가 여백 */
    }

    /* form 내부 마지막 요소와 sticky-bottom 사이 여백 확보 */
    .page.page-write form#frm {
        padding-bottom: 0;
        /* form 자체는 패딩 없음 */
        margin-bottom: 0;
        /* 마진도 없음 */
    }

    /* sticky-bottom이 콘텐츠 위에 올라가지 않도록 z-index 조정 */
    .page.page-write .sticky-bottom {
        z-index: 5;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        /* 위쪽 그림자로 구분 */
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

<div class="page page-write">
    <div class="top-bar">
        <a href="javascript:void(0);" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이력서</h1>
        <span class="required-label"><span class="required-asterisk">*</span>필수입력</span>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="section addition-info-form">
            <div class="container">
                <form id="frm" onsubmit="return false">
                    <input type="hidden" name="arr_career" value="" />
                    <input type="hidden" name="arr_certi" value="" />
                    <div class="form-row">
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
                            // ru_my_type_list가 없으면 빈 배열로 처리
                            $ru_my_type_list = isset($ru_my_type_list) ? $ru_my_type_list : [];
                            $info_ru_my_type = isset($info["ru_my_type"]) ? (is_array($info["ru_my_type"]) ? $info["ru_my_type"] : explode(",", $info["ru_my_type"])) : [];
                            // 처음 12개만 출력하도록 제한
                            $ru_my_type_list_limited = array_slice($ru_my_type_list, 0, 12);
                            foreach ($ru_my_type_list_limited as $row) :
                            ?>
                                <label class="form-toggle-btn2 sm tag">
                                    <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info_ru_my_type, "check") ?>>
                                    <span class="btn"><?= $row["cd_val"] ?></span>
                                </label>
                            <?php endforeach; ?>

                            <!-- 추가 태그 -->
                            <?php if (!empty($info_ru_my_type)) : ?>
                                <?php foreach ($info_ru_my_type as $row) : ?>
                                    <?php
                                    $is_arr = false;
                                    foreach ($ru_my_type_list as $temp) {
                                        if ($temp["cd_val"] == $row) {
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
                    <!-- 상세등록 섹션들 -->
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
                            // 요일 리스트가 없으면 기본값으로 설정 (월~일 순서)
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
                            // 기본값이 없으면 월~금으로 설정
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
                    <!-- 경력사항 섹션 -->
                    <div class="form-row js-detail-section" data-section="career">
                        <p class="form-label">경력사항 <em>최대 3개까지만 등록가능</em></p>
                        <!-- 목록 -->
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

                        <!-- 입력폼 템플릿 -->
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
                    <!-- 자격증 섹션 -->
                    <div class="form-row js-detail-section" data-section="license">
                        <p class="form-label">자격증 <em>허위사실 기재시 합격이 취소될 수 있습니다</em></p>
                        <!-- 목록 -->
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

                        <!-- 입력폼 템플릿 -->
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
                    <!-- 상세등록 버튼 -->
                    <div class="form-row">
                        <a href="javascript:void(0);" class="detail-register-btn js-detail-register">
                            <span class="add-icon">+</span>상세등록
                        </a>
                    </div>
                    <div class="sticky-bottom">
                        <button type="button" class="btn btn-secondary" id="btnPrev">이전</button>
                        <button type="button" class="btn btn-primary" id="btnStep">미리보기</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- //page-content -->
</div>

<input type="hidden" name="ru_idx" value="<?= $info["ru_idx"] ?>" />
<script>
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
                if (currentPath.indexOf('/mypage/resume_write4') > -1) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    var ruIdx = $("[name='ru_idx']").val() || "0";
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

    // 경력사항/자격증 삭제 이벤트 리스너 (전역 리스너보다 먼저 실행되도록 캡처 단계에서 등록)
    (function() {
        document.addEventListener("click", function(e) {
            var target = e.target;
            var delBtn = null;

            // js-del-item 버튼 또는 그 자식 요소 확인
            if (target.classList && target.classList.contains('js-del-item')) {
                delBtn = target;
            } else {
                // 부모 요소 중 js-del-item 찾기
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

                // 경력사항 또는 자격증 섹션인지 확인 (data-section 속성으로 구분)
                var $formRow = $list.closest(".form-row");
                if ($formRow.length > 0 && ($formRow.data("section") === "career" || $formRow.data("section") === "license")) {
                    // 전역 리스너 실행 완전 차단
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    e.preventDefault();

                    // DOM에서 즉시 완전히 제거
                    var itemElement = $item[0];
                    if (itemElement && itemElement.parentNode) {
                        // 전역 리스너가 추가한 클래스와 스타일 제거
                        itemElement.classList.remove('is-deleted');
                        itemElement.style.height = '';
                        itemElement.style.width = '';

                        // 부모 노드에서 직접 제거
                        var parentNode = itemElement.parentNode;
                        parentNode.removeChild(itemElement);
                    }

                    // 삭제 후 빈 li 정리 및 임시 저장
                    setTimeout(function() {
                        cleanupEmptyListItems();
                        saveResumeStep4Data();
                    }, 50);

                    // 등록 버튼은 항상 표시
                    var $addBtn = $list.siblings(".js-add-career, .js-add-license");
                    if ($addBtn.length > 0) {
                        $addBtn.css('display', '').show();
                    }

                    return false;
                }
            }
        }, true); // 캡처 단계에서 실행
    })();

    // 빈 li와 중첩된 li 정리 함수
    function cleanupEmptyListItems() {
        $(".form-row[data-section='career'] .added-list, .form-row[data-section='license'] .added-list").each(function() {
            var $list = $(this);
            var listElement = this;

            // 직접 자식 li만 확인
            $list.children("li").each(function() {
                var $li = $(this);
                var liElement = this;

                // 1. is-deleted 클래스가 있으면 제거
                if ($li.hasClass('is-deleted')) {
                    if (liElement.parentNode) {
                        liElement.parentNode.removeChild(liElement);
                    }
                    return;
                }

                // 2. form-group이 없으면 빈 li이므로 제거
                if ($li.find('.form-group').length === 0) {
                    if (liElement.parentNode) {
                        liElement.parentNode.removeChild(liElement);
                    }
                    return;
                }

                // 3. 중첩된 li가 있으면 제거 (직접 자식이 아닌 li)
                if (liElement.parentNode !== listElement) {
                    if (liElement.parentNode) {
                        liElement.parentNode.removeChild(liElement);
                    }
                    return;
                }
            });
        });
    }

    $(document).ready(function() {
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

            // localStorage에 값이 없거나 ru_week가 비어있으면 기본값(월~금) 설정
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
                            // 저장된 값이 없으면 기본값 설정
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

            // localStorage에 값이 없으면 기본값 설정
            if (!hasSavedData) {
                $("[name='ru_week[]']").prop("checked", false);
                defaultWeekDays.forEach(function(val) {
                    $("[name='ru_week[]'][value='" + val + "']").prop("checked", true);
                });
                saveResumeStep4Data();
            }

            // 경력사항, 자격증 등 나머지 데이터 복원
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

        function applyResumeAutoFill() {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autofill') !== '1') return;

            window.__resumeAutoFlow = true;

            var payload = {};
            try {
                payload = JSON.parse(localStorage.getItem("resume_autofill_payload") || "{}");
            } catch (e) {
                payload = {};
            }
            // 자동작성 모드에서는 화면을 숨겨 플리커를 방지
            document.documentElement.style.visibility = 'hidden';

            var ruName = payload.ru_name || "";
            if ($("[name='ru_desc']").val() === "" && ruName) {
                $("[name='ru_desc']").val(ruName);
                if (typeof autoHeight === 'function') {
                    setTimeout(function() {
                        autoHeight($("[name='ru_desc']")[0]);
                    }, 50);
                }
            }

            // 나의강점 2개 랜덤 선택
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

            setTimeout(function() {
                $("#btnStep").trigger("click");
            }, 200);
        }
        // 페이지 로드 시 빈 li 정리 후 임시 데이터 복원
        cleanupEmptyListItems();
        loadResumeStep4Data();
        applyResumeAutoFill();

        // 페이지 로드 시 희망요일이 하나도 선택되지 않았으면 기본값(월~금) 설정
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

        $("#btnPrev").click(function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            saveResumeStep4Data();
            var ruIdx = $("[name='ru_idx']").val() || "0";
            var url = (ruIdx !== "0") 
                ? "/mypage/resume_write1/" + ruIdx 
                : "/mypage/resume_write1";
            // URL 파라미터로 복원 여부 표시
            location.href = url + "?from=step4";
        });

        $("#btnStep").click(function() {
            saveResumeStep4Data();
            var $btn = $(this);

            // 저장 버튼 상태인지 확인
            if ($btn.data('is-save-mode') === true) {
                // 저장 버튼 클릭 시 최종 저장 처리
                var params = {};
                params["ru_idx"] = $("[name=ru_idx]").val();
                comAjax("/mypage_proc/resume_complete", params, function(ret) {
                    if (ret.code == "00") {
                        clearResumeDraft(params["ru_idx"]);
                        location.href = "/mypage/resume_list";
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
                return;
            }

            // 미리보기 모드: 자기소개 검증
            if ($("[name='ru_desc']").val() == "") {
                commonAlert("자기소개를 입력해주세요.", function() {
                    $("[name='ru_desc']").focus();
                });
                return;
            }

            // 나의강점 검증
            if ($("[name='ru_my_type[]']:checked").length == 0) {
                commonAlert("나의강점을 하나 이상 선택해주세요.", function() {});
                return;
            }

            // 희망요일, 경력사항, 자격증은 선택사항이므로 검증하지 않음

            // 희망요일 데이터 처리
            var ru_week = [];
            $("[name='ru_week[]']:checked").each(function() {
                ru_week.push($(this).val());
            });
            // ru_week 배열을 hidden input에 저장 (FormData로 전송 시 배열이 자동 처리됨)
            // 또는 쉼표로 구분된 문자열로 변환
            var ru_week_str = ru_week.join(",");
            // FormData에 직접 추가
            var ru_week_yn_value = $("[name='ru_week_yn']").is(":checked") ? "Y" : "N";

            // 경력사항 데이터 처리 (children 사용하여 직접 자식만 처리)
            var arr_career = "";
            var $careerList = $(".form-row[data-section='career'] .added-list");
            $careerList.children("li").each(function() {
                // is-deleted 클래스가 없는 항목만 처리
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

            // 자격증 데이터 처리 (children 사용하여 직접 자식만 처리)
            var arr_certi = "";
            var $licenseList = $(".form-row[data-section='license'] .added-list");
            $licenseList.children("li").each(function() {
                // is-deleted 클래스가 없는 항목만 처리
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
            // ru_week_yn과 ru_week를 FormData에 추가
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
                                location.href = "/recruit/job_list";
                            } else {
                                commonAlert(ret2.msg, function() {});
                            }
                        });
                        return;
                    }
                    // 미리보기 팝업 표시 (버튼 변경은 팝업에서 확인 버튼 클릭 시 수행)
                    showPreviewInfo(ret.data, $btn);
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });

        // 상세등록 버튼 클릭 시 희망요일, 경력사항, 자격증 섹션 표시
        $(".js-detail-register").click(function(e) {
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
            setTimeout(saveResumeStep4Data, 350);
        });

        // 선택 제한 로직
        (function() {
            document.querySelectorAll('input[data-select-limit]').forEach(function(item) {
                item.addEventListener('change', function() {
                    var limit = parseInt(this.getAttribute('data-select-limit'));
                    var childItems = document.querySelectorAll('[name="' + this.getAttribute('name') + '"]:checked');

                    if (this.checked && childItems.length > limit) {
                        this.checked = false;
                        commonAlert("최대 " + limit + "개까지 선택 가능합니다.", function() {});
                    }
                });
            });
        }());

        // 희망요일 로직
        // 요일무관 클릭 시 모든 요일 체크
        $("[name='ru_week_yn']").on("change", function() {
            if ($(this).is(":checked")) {
                // 요일무관 체크 시 모든 요일 항목 체크
                $("[name='ru_week[]']").prop("checked", true);
            } else {
                // 요일무관 해제 시 모든 요일 항목 체크 해제
                $("[name='ru_week[]']").prop("checked", false);
            }
            saveResumeStep4Data();
        });

        // 요일 체크박스 변경 시 요일무관이 체크되어 있으면 해제
        $("[name='ru_week[]']").on("change", function() {
            // 요일무관이 체크되어 있으면 해제
            if ($("[name='ru_week_yn']").is(":checked")) {
                $("[name='ru_week_yn']").prop("checked", false);
            }
            saveResumeStep4Data();
        });
        $("[name='ru_my_type[]']").on("change", saveResumeStep4Data);
        $("[name='ru_desc']").on("input", saveResumeStep4Data);
        $(document).on("input change", "[name='career_category[]'], [name='career_company[]'], [name='career_period[]'], [name='license[]'], [name='regdate[]']", function() {
            saveResumeStep4Data();
        });


        // 경력사항 등록 버튼 클릭 이벤트
        $(".js-add-career").on("click", function(e) {
            e.preventDefault();
            var $list = $(this).siblings(".added-list");

            // 빈 li 정리
            cleanupEmptyListItems();

            // 유효한 li 요소만 카운트 (form-group이 있는 항목만)
            var currentCount = 0;
            var listElement = $list[0];

            $list.children("li").each(function() {
                var $li = $(this);
                var liElement = this;

                // 1. is-deleted 클래스가 없어야 함
                // 2. parentNode가 정확히 $list여야 함
                // 3. DOM에 실제로 존재해야 함
                // 4. form-group이 있어야 함 (빈 li 제외)
                if (!$li.hasClass('is-deleted') &&
                    liElement.parentNode === listElement &&
                    document.body.contains(liElement) &&
                    $li.find('.form-group').length > 0) {
                    currentCount++;
                }
            });

            // 최대 3개까지만 등록 가능
            if (currentCount >= 3) {
                commonAlert("경력사항은 최대 3개까지만 등록 가능합니다.", function() {});
                return;
            }

            // 템플릿에서 새 항목 생성
            var $temp = $("#careerTemp").html();
            var $newItem = $("<li>").html($temp);
            $list.append($newItem);

            // 새로 추가된 항목의 달력 초기화
            initCareerDatepicker($newItem.find(".js-career-datepicker")[0]);

            // 새로 추가된 항목의 셀렉트박스 초기화
            if (typeof initSelectbox === 'function') {
                initSelectbox($newItem.find(".js-career-category")[0]);
            }

            // 등록 버튼은 항상 표시
            $(this).css('display', '').show();
            saveResumeStep4Data();
        });

        // 자격증 등록 버튼 클릭 이벤트
        $(".js-add-license").on("click", function(e) {
            e.preventDefault();
            var $list = $(this).siblings(".added-list");

            // 빈 li 정리
            cleanupEmptyListItems();

            // 유효한 li 요소만 카운트 (form-group이 있는 항목만)
            var currentCount = 0;
            var listElement = $list[0];

            $list.children("li").each(function() {
                var $li = $(this);
                var liElement = this;

                // 1. is-deleted 클래스가 없어야 함
                // 2. parentNode가 정확히 $list여야 함
                // 3. DOM에 실제로 존재해야 함
                // 4. form-group이 있어야 함 (빈 li 제외)
                if (!$li.hasClass('is-deleted') &&
                    liElement.parentNode === listElement &&
                    document.body.contains(liElement) &&
                    $li.find('.form-group').length > 0) {
                    currentCount++;
                }
            });

            // 최대 5개까지만 등록 가능
            if (currentCount >= 5) {
                commonAlert("자격증은 최대 5개까지만 등록 가능합니다.", function() {});
                return;
            }

            // 템플릿에서 새 항목 생성
            var $temp = $("#licenseTemp").html();
            var $newItem = $("<li>").html($temp);
            $list.append($newItem);

            // 새로 추가된 항목의 달력 초기화
            initLicenseDatepicker($newItem.find(".js-datepicker")[0]);

            // 등록 버튼은 항상 표시
            $(this).css('display', '').show();
            saveResumeStep4Data();
        });


        // 경력사항 달력 초기화 함수
        function initCareerDatepicker(el) {
            if (typeof flatpickr == 'undefined' || !el) return;

            function chgYear(instance) {
                var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                    year = instance.currentYear;
                $con.innerHTML = '<span class="month">' + year + '</span>';
            }

            // 기존 값이 있으면 파싱하여 설정
            var existingValue = $(el).val();
            var defaultDate = null;
            if (existingValue && existingValue.indexOf('~') > -1) {
                var dates = existingValue.split(' ~ ');
                if (dates.length === 2) {
                    // Y.m 형식을 Y-m 형식으로 변환
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

                    // 입력 필드에 범위 형식으로 값 설정 (예: 2012.05 ~ 2015.01)
                    if (dateStr && dateStr.indexOf(' to ') > -1) {
                        var dates = dateStr.split(' to ');
                        if (dates.length === 2) {
                            // Y-m-d 형식을 Y.m 형식으로 변환
                            var startDate = dates[0].substring(0, 7).replace(/-/g, '.');
                            var endDate = dates[1].substring(0, 7).replace(/-/g, '.');
                            var formattedDate = startDate + ' ~ ' + endDate;
                            $(el).val(formattedDate);
                        }
                    } else if (dateStr) {
                        // 단일 날짜인 경우
                        var formattedDate = dateStr.substring(0, 7).replace(/-/g, '.');
                        $(el).val(formattedDate);
                    }
                    saveResumeStep4Data();
                }
            });
        }

        // 자격증 달력 초기화 함수
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

        // 기존 경력사항 달력 초기화 (페이지 로드 시 한 번만 실행)
        setTimeout(function() {
            $(".form-row[data-section='career'] .js-career-datepicker").each(function() {
                if (!$(this).data('flatpickr')) {
                    initCareerDatepicker(this);
                }
            });
        }, 100);

        // 기존 자격증 달력 초기화 (페이지 로드 시 한 번만 실행)
        setTimeout(function() {
            $(".form-row[data-section='license'] .js-datepicker").each(function() {
                if (!$(this).data('flatpickr')) {
                    initLicenseDatepicker(this);
                }
            });
        }, 100);
    });

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

        // 요일무관이 체크되어 있으면 "무관" 표시, 아니면 선택된 요일 표시
        // 서버 응답의 ru_week_yn 또는 현재 폼의 ru_week_yn 값을 확인
        var ru_week_yn_value = info.ru_week_yn || ($("[name='ru_week_yn']").is(":checked") ? "Y" : "N");

        if (ru_week_yn_value == "Y") {
            str_ru_week = "<li aria-hidden='false'>무관</li>";
        } else if (info.ru_week) {
            // ru_week가 문자열인 경우 배열로 변환
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
            // 문자열인 경우 배열로 변환 (쉼표로 구분)
            var ru_my_type_array = typeof info.ru_my_type === 'string' ? info.ru_my_type.split(",") : info.ru_my_type;
            if (ru_my_type_array && ru_my_type_array.length > 0) {
                for (var icnt = 0; icnt < ru_my_type_array.length; icnt++) {
                    // 각 항목에서 공백과 쉼표 제거
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
        
        // 주소 처리: fRemoveBigAdrJS 함수 적용
        var displayAddr = '';
        
        // ru_addr이 배열인 경우 처리
        if (Array.isArray(info.ru_addr)) {
            // 배열을 쉼표로 구분된 문자열로 변환
            displayAddr = info.ru_addr.filter(function(addr) {
                return addr && addr.trim() !== '';
            }).join(',');
        } else {
            // 문자열인 경우 그대로 사용
            displayAddr = info.ru_addr || '';
        }
        
        // 주소가 있고 '무관'이 아니면 fRemoveBigAdrJS 함수 적용
        if (displayAddr && displayAddr !== '' && displayAddr !== '무관' && typeof fRemoveBigAdrJS === 'function') {
            // 쉼표로 구분된 여러 주소 처리
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
        
        // 주소가 비어있으면 '무관'으로 설정
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
                // 팝업 닫기
                var $popup = document.querySelector('.layer-popup.in');
                if ($popup && typeof closePopup === 'function') {
                    closePopup($popup);
                }
            },
            secondCallback: function() {
                // 확인 버튼 클릭 시 데이터 저장 후 최종 저장 처리
                // 희망요일 데이터 처리
                var ru_week = [];
                $("[name='ru_week[]']:checked").each(function() {
                    ru_week.push($(this).val());
                });
                var ru_week_str = ru_week.join(",");
                var ru_week_yn_value = $("[name='ru_week_yn']").is(":checked") ? "Y" : "N";

                // 경력사항 데이터 처리
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

                // 자격증 데이터 처리
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

                // step4 데이터 저장
                var form = $('#frm');
                var params = new FormData(form[0]);
                params.append("ru_week_yn", ru_week_yn_value);
                if (ru_week_str) {
                    params.append("ru_week", ru_week_str);
                }
                
                fileAjaxLoading("/mypage_proc/resume_write4", params, function(ret) {
                    if (ret.code == "00") {
                        // 최종 저장 처리
                        var completeParams = {};
                        completeParams["ru_idx"] = $("[name=ru_idx]").val();
                        comAjax("/mypage_proc/resume_complete", completeParams, function(completeRet) {
                            if (completeRet.code == "00") {
                                // 저장완료 팝업 표시
                                commonAlert("저장이 완료되었습니다.", function() {
                                    clearResumeDraft($("[name=ru_idx]").val());
                                    location.href = "/mypage/resume_list";
                                });
                            } else {
                                // 에러 메시지 표시 (서버에서 반환한 메시지 또는 기본 메시지)
                                var errorMsg = completeRet.msg || "저장 중 오류가 발생했습니다. 다시 시도해주세요.";
                                commonAlert(errorMsg, function() {});
                                return;
                            }
                        });
                    } else {
                        // 에러 메시지 표시
                        var errorMsg = ret.msg || "데이터 저장 중 오류가 발생했습니다. 다시 시도해주세요.";
                        commonAlert(errorMsg, function() {});
                        return;
                    }
                });
            }
        });

        // X 버튼 클릭 시에도 미리보기 버튼 유지
        setTimeout(function() {
            var $closeBtn = $('.layer-popup .js-close-popup');
            if ($closeBtn.length > 0) {
                $closeBtn.off('click.preview').on('click.preview', function(e) {
                    // X 버튼 클릭 시 미리보기 버튼 유지 (아무 동작도 하지 않음)
                    // 팝업은 기본 동작으로 닫힘
                });
            }
        }, 100);
    }

    // Android 물리적 뒤로가기 버튼 및 iOS 스와이프 뒤로가기 처리
    history.pushState(null, null, location.href);
    
    window.addEventListener('popstate', function(e) {
        history.pushState(null, null, location.href);
        clearResumeDraft($("[name='ru_idx']").val() || "0");
        location.href = "/mypage/resume_list";
    });

    //뒤로 왔을때 마이페이지로 이동 및 데이터 삭제
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || window.performance && window.performance.navigation.type == 2) {
            clearResumeDraft($("[name='ru_idx']").val() || "0");
            _replace("/mypage/index");
        }
    }, false);
</script>
