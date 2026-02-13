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
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>&libraries=services"></script>

<style>
    /* 이력서 작성 페이지 스타일 */
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

    .required-asterisk {
        color: #ff3333;
        margin-right: 2px;
    }

    .required-label {
        font-size: 0.9375rem;
        color: rgb(0, 0, 0);
        font-weight: 400;
    }

    .nationality-toggle-group {
        margin-top: 0.875rem;
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

    .cont-price-row {
        padding-left: 60px;
        width: 270px;
    }

    .cont-price-row .label {
        width: 90px;
        position: absolute;
        left: 0px;
        top: 10px;
    }

    form#frm {
        top: 0vh;
    }

</style>

<div class="page page-write">
    <div class="top-bar">
        <a href="javascript:void(0);" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이력서</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form id="frm" onsubmit="return false" autocomplete="false">
            <input type="hidden" name="ru_work_img" value="" />
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

            <div class="sticky-bottom">
                <button id="btnStep" type="button" class="btn btn-primary">다음</button>
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
        // URL 파라미터로 복원 여부 확인 (더 확실한 방법)
        var urlParams = new URLSearchParams(window.location.search);
        var fromStep4 = urlParams.get('from') === 'step4';
        
        // resume_write4에서 온 경우가 아니면 복원하지 않음
        if (!fromStep4) {
            return;
        }
        
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

        // 자동작성 모드에서는 화면을 숨겨 플리커를 방지
        document.documentElement.style.visibility = 'hidden';

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
            "[지역명] 기반, 준비된 맞춤형 인재입니다!",
            "번개처럼 빠르게 적응하는 [지역명] 구직자",
            "성실함이 무기! [지역명] 근처 바로 출근 가능합니다.",
            "나우잡이 추천하는 우리 동네 열정 인재 프로필",
            "복잡한 건 생략! 실력으로 보여드릴 [지역명] 지원자",
            "지금 즉시 매칭 가능한 [지역명] 최적화 이력서",
            "누구보다 빠르게, 열정적으로 일할 준비가 되었습니다!",
            "[지역명] 거주 중! 꼼꼼함과 책임감을 겸비한 인재",
            "사장님이 찾으시던 바로 그 [지역명] 꿀알바 지원생",
            "지금바로 출근준비되어있습니다."
        ];

        var chosenTitle = titleTemplates[Math.floor(Math.random() * titleTemplates.length)];
        if (chosenTitle.indexOf("[지역명]") > -1) {
            chosenTitle = chosenTitle.replace("[지역명]", regionName || dongName || "우리 동네");
        }

        if ($("[name='ru_name']").val() === "") {
            $("[name='ru_name']").val(chosenTitle);
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
        $("#changeArea").addClass("on");
    }

    // DOMContentLoaded 이벤트로 더 빠르게 처리 (깜빡임 방지)
    document.addEventListener('DOMContentLoaded', function() {
        // 먼저 서버에서 받은 주소 데이터를 로드
        var hasServerData = loadServerAddressData();
        
        // resume_write4에서 온 경우에만 로컬스토리지 임시 데이터 복원
        // 서버 데이터가 없을 때만 로컬스토리지에서 복원
        if (!hasServerData) {
            loadResumeStep1Data();
        }
        
        ensureAddressAddButton($("#addr_location .location-field-item:not(.location-field-add)").length);
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

        $("[name='ru_price_type[]']").change(function() {
            if ($("[name='ru_price_type[]']").eq(2).is(":checked")) {
                $(".cont-price").removeClass("hidden");
            } else {
                $(".cont-price").addClass("hidden");
            }
        });

        //처리
        $("#btnStep").click(function() {
            saveResumeStep1Data();

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

            var autoParam = (window.__resumeAutoFlow === true) ? "?autofill=1" : "";
            if (newIdx != "0") {
                location.href = "/mypage/resume_write4/" + newIdx + autoParam;
            } else {
                location.href = "/mypage/resume_write4" + autoParam;
            }
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

        // 뒤로가기 핸들러는 스크립트 시작 부분에서 이미 등록됨

        // Android 물리적 뒤로가기 버튼 및 iOS 스와이프 뒤로가기 처리
        history.pushState(null, null, location.href);
        
        window.addEventListener('popstate', function(e) {
            history.pushState(null, null, location.href);
            clearResumeDraft($("[name='ru_idx']").val() || "0");
            location.href = "/mypage/resume_list";
        });
    });

</script>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>
