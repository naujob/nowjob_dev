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

    .top-bar .required-label {
        position: absolute;
        top: 50%;
        right: 1.4375rem;
        transform: translateY(-50%);
    }

    /* container 안의 required-label을 오른쪽 끝으로 정렬 */
    .container>.required-label {
        display: block;
        text-align: right;
        margin-bottom: -0.125rem;
    }

    /* 복리후생 제목과 필수입력 레이블을 같은 줄에 배치 */
    .resume-title-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.875rem;
    }

    .resume-title-header .form-label {
        margin: 0;
    }

    /* 복리후생 섹션 간격 조정 */
    .addition-info-form .form-row .form-row {
        margin-top: 2rem;
        /* 기존 1.25rem에서 2rem으로 증가 */
    }

    /* 첫 번째 섹션의 상단 마진 제거 */
    .addition-info-form .form-row>.form-row:first-child {
        margin-top: 0;
    }

    /* 섹션 제목과 버튼 그룹 사이 간격 조정 */
    .addition-info-form .form-row .form-label {
        margin-bottom: 1rem;
        /* 기존 0.875rem에서 1rem으로 증가 */
    }

    /* form-toggle-group 상단 여백 추가 */
    .addition-info-form .form-toggle-group {
        margin-top: 0.5rem;
    }

    /* form-toggle-btn3 버튼 높이 증가 */
    .addition-info-form .form-toggle-btn3 .btn {
        min-height: 5rem;
        /* 최소 높이 설정 */
        padding: 1.5rem 0.625rem;
        /* 상하 패딩 증가 (기존 1rem에서 1.5rem으로) */
    }

    /* 복리후생 제목 폰트 사이즈 조정 */
    .addition-info-form h2.form-label {
        font-size: 1.125rem;
        /* 기존 0.9375rem에서 1.125rem으로 증가 */
    }

    /* 추가정보 폼 섹션 위치 조정 */
    .section.addition-info-form {
        margin-top: -10px;
        /* 원하는 만큼 조정 (음수 값으로 위로 이동) */
    }
</style>

<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <form id="frm" onsubmit="return false">
            <input type="hidden" id="rc_idx" name="rc_idx" value="<?= isset($info["rc_idx"]) ? $info["rc_idx"] : "0" ?>" />
            <div class="section addition-info-form">
                <div class="container">
                    
                    <div class="form-row">
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

                    <div class="sticky-bottom">
                        <button id="btnPrev" type="button" class="btn btn-secondary">이전</button>
                        <button id="btnStep" type="button" class="btn btn-primary">다음</button>
                    </div>
                </div>
            </div>
        </form>
    </div><!-- //page-content -->
</div>

<script>
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

    function loadPage2Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var storageKey = "recruit_draft_" + rcIdx + "_page2";
        var saved = localStorage.getItem(storageKey);

        if (!saved) return;

        try {
            var formData = JSON.parse(saved);
            if (!formData) return;

            // 체크박스 복원
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

    // localStorage 초기화 함수 (등록 완료 후 또는 새 등록 시작 시)
    function clearRecruitDraftData(rcIdx) {
        if (!rcIdx) rcIdx = "0";
        // 해당 rc_idx의 모든 페이지 데이터 삭제
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page1");
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page2");
        localStorage.removeItem("recruit_draft_" + rcIdx + "_page3");
    }

    window.addEventListener('DOMContentLoaded', function() {
        // 페이지 로드 시 데이터 복원
        loadPage2Data();

        // 체크박스 변경 시 실시간 저장
        $("[name='rc_life_type[]']").on("change", savePage2Data);

        // 다음 버튼 클릭 처리
        $("#btnStep").click(function() {
            savePage2Data();

            var params = $("#frm").serialize();
            var prevIdx = $("[name='rc_idx']").val() || "0";
            comAjax("/compage_proc/recruit_write2_1", params, function(ret) {
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
                        location.href = "/compage/recruit_write3_1/" + newIdx;
                    } else {
                        location.href = "/compage/recruit_write3_1";
                    }
                } else {
                    commonAlert(ret.msg, function() {});
                    return;
                }
            });
        });

        // 이전 버튼
        $("#btnPrev").click(function() {
            savePage2Data();
            if ($("[name=rc_idx]").val() != "0") {
                location.href = "/compage/recruit_write1_1/" + $("[name=rc_idx]").val();
            } else {
                location.href = "/compage/recruit_write1_1";
            }
        });

        // 뒤로가기 - compage/recruit_list로 이동
        $(".btn-back").click(function(e) {
            e.preventDefault();
            savePage2Data();
            clearRecruitDraftData($("[name='rc_idx']").val() || "0");
            location.href = "/compage/recruit_list";
        });

        // Android 물리적 뒤로가기 버튼 및 iOS 스와이프 뒤로가기 처리
        // 히스토리 스택에 항목 추가하여 popstate 이벤트 감지 가능하게 함
        history.pushState(null, null, location.href);
        
        // popstate 이벤트로 뒤로가기 버튼 및 iOS 스와이프 제스처 감지
        window.addEventListener('popstate', function(e) {
            history.pushState(null, null, location.href);
            savePage2Data();
            clearRecruitDraftData($("[name='rc_idx']").val() || "0");
            location.href = "/compage/recruit_list";
        });

    });
</script>