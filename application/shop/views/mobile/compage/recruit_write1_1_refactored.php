<?php
// 이 파일은 recruit_write1_1.php의 JavaScript 부분을 재구성한 버전입니다.
// 원본 파일의 1-775줄(HTML+CSS)과 함께 사용하세요.
//
// 함수 그룹화 순서:
// 1. 유틸리티 함수 (숫자 포맷팅, 문자열 처리)
// 2. 데이터 마이그레이션 & 정리
// 3. 페이지별 데이터 저장 (localStorage)
// 4. 페이지별 데이터 로드 (localStorage + 서버 데이터)
// 5. 주소 처리
// 6. 폼 데이터 수집
// 7. UI 업데이트
// 8. API 호출
// 9. 화면 전환
// 10. 핵심 흐름
// 11. 초기화 & 이벤트 핸들링

?>

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

    // 채용공고 작성 1페이지 내용을 localStorage에 임시저장
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
            expose_addr1_zipcode: $("#expose_addr1_zipcode").val(),
            expose_addr1_jibun: $("#expose_addr1_jibun").val(),
            expose_addr1_doro_val: $("#expose_addr1_doro_val").val(),
            expose_addr1_addr: $("#expose_addr1_addr").val(),
            expose_addr1_gps_x: $("#expose_addr1_gps_x").val(),
            expose_addr1_gps_y: $("#expose_addr1_gps_y").val(),
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

    // 페이지2 데이터 저장 (복리후생)
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

    // 페이지3 데이터 저장 (상세정보, 해시태그, 일정 등)
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

    // ============================================================================
    // 4. 페이지별 데이터 로드 (localStorage + 서버 데이터)
    // ============================================================================

    // 페이지1 데이터 복원 (주소, 업무구분, 근무형태, 급여 등)
    function loadPage1Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var formData = null;

        // draft 로드: shouldLoadDraft가 true일 때만 draft를 먼저 확인
        if (window.shouldLoadDraft) {
            var storageKey = "recruit_draft_" + rcIdx + "_page1";
            var draftSaved = localStorage.getItem(storageKey);

            if (draftSaved) {
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
            $("#btnExposeAddr1").find(".address-text").text(formData.expose_addr1_addr);
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
            $("#btnExposeAddr2").find(".address-text").text(formData.expose_addr2_addr);
            $("#btnExposeAddr2").addClass("has-address");
        }
        if (formData.expose_addr2_gps_x) {
            $("#expose_addr2_gps_x").val(formData.expose_addr2_gps_x);
        }
        if (formData.expose_addr2_gps_y) {
            $("#expose_addr2_gps_y").val(formData.expose_addr2_gps_y);
        }

        // 급여 관련 복원
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

        // 업무구분 복원
        $("[name='rc_work[]']").prop("checked", false);
        if (Array.isArray(formData.rc_work) && formData.rc_work.length > 0) {
            formData.rc_work.forEach(function(val) {
                $("[name='rc_work[]'][value='" + val + "']").prop("checked", true);
            });
        }

        // 근무협의 복원
        if (formData.rc_work_negotiation === "Y") {
            $("[name='rc_work_negotiation']").prop("checked", true).trigger("change");
        }

        // 근무형태 복원
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
                var displayAddr1 = serverAddrData1.ad_addr || "";
                $btnExposeAddr1.find(".address-text").text(displayAddr1);
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
                var displayAddr2 = serverAddrData2.ad_addr || "";
                $btnExposeAddr2.find(".address-text").text(displayAddr2);
                $btnExposeAddr2.addClass("has-address");
            }
        }
    }

    // 페이지2 데이터 복원 (복리후생)
    function loadPage2Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var formData = null;

        if (window.shouldLoadDraft) {
            var storageKey = "recruit_draft_" + rcIdx + "_page2";
            var draftSaved = localStorage.getItem(storageKey);

            if (draftSaved) {
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

        if (!formData && typeof serverData !== 'undefined' && serverData !== null && serverData.rc_idx && serverData.rc_idx != "0") {
            formData = serverData;
        } else if (!formData) {
            return;
        }

        if (formData.rc_life_type && formData.rc_life_type.length > 0) {
            $("[name='rc_life_type[]']").prop("checked", false);
            formData.rc_life_type.forEach(function(val) {
                $("[name='rc_life_type[]'][value='" + val + "']").prop("checked", true);
            });
        }
    }

    // 페이지3 데이터 복원 (상세정보, 해시태그, 일정 등)
    function loadPage3Data() {
        var rcIdx = $("[name='rc_idx']").val() || "0";
        var formData = null;
        var isServerData = false;

        if (window.shouldLoadDraft) {
            var storageKey = "recruit_draft_" + rcIdx + "_page3";
            var draftSaved = localStorage.getItem(storageKey);

            if (draftSaved) {
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

        if (!formData && typeof serverData !== 'undefined' && serverData !== null && serverData.rc_idx && serverData.rc_idx != "0") {
            formData = serverData;
            isServerData = true;
        } else if (!formData) {
            return;
        }

        try {
            if (typeof formData.rc_desc !== "undefined") {
                var rcDescEditor = document.getElementById('rc_desc_editor');
                if (rcDescEditor) {
                    rcDescEditor.innerHTML = formData.rc_desc;
                }
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

            // 데이터 복원 후 각 그룹의 "모두" 상태 동기화
            setTimeout(function() {
                var dayCheckedCount = $("[name='rc_day[]']:checked").length;
                var dayTotalCount = $("[name='rc_day[]']").length;
                if (dayCheckedCount === dayTotalCount && dayCheckedCount > 0) {
                    $("[name='rc_day_all']").prop("checked", true);
                }

                var sexCheckedCount = $("[name='rc_sex[]']:checked").length;
                var sexTotalCount = $("[name='rc_sex[]']").length;
                if (sexCheckedCount === sexTotalCount && sexCheckedCount > 0) {
                    $("[name='rc_sex_all']").prop("checked", true);
                }

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
            //
        }
    }

    // 페이지 로드 시 모든 데이터 복원
    function restorePageData() {
        loadPage1Data();
        loadPage2Data();
        loadPage3Data();

        if (typeof updateTimeSection_fn === 'function') {
            updateTimeSection_fn();
        }
        if (typeof updateSalaryOptions === 'function') {
            updateSalaryOptions();
        }
    }

    // ============================================================================
    // 5. 주소 처리
    // ============================================================================

    // 주소 검색 결과를 폼에 반영하는 함수 (메인 주소)
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

    // 공고노출지역 1 콜백 함수
    function selectAddrInfoExpose1(zipcode, doro, jibun, addr, x, y, detail) {
        detail = detail || "";

        $("#expose_addr1_zipcode").val(zipcode);
        $("#expose_addr1_jibun").val(jibun);
        $("#expose_addr1_doro_val").val(doro);
        $("#expose_addr1_addr").val(addr);
        $("#expose_addr1_gps_x").val(x);
        $("#expose_addr1_gps_y").val(y);

        var $btnAddr = $("#btnExposeAddr1");
        var displayAddr = addr;
        if (detail && detail.trim() !== "") {
            displayAddr = addr + " " + detail.trim();
        }
        $btnAddr.find(".address-text").text(displayAddr);
        $btnAddr.addClass("has-address");

        $("#changeArea").removeClass("on");
        savePage1Data();
    }

    // 공고노출지역 2 콜백 함수
    function selectAddrInfoExpose2(zipcode, doro, jibun, addr, x, y, detail) {
        detail = detail || "";

        $("#expose_addr2_zipcode").val(zipcode);
        $("#expose_addr2_jibun").val(jibun);
        $("#expose_addr2_doro_val").val(doro);
        $("#expose_addr2_addr").val(addr);
        $("#expose_addr2_gps_x").val(x);
        $("#expose_addr2_gps_y").val(y);

        var $btnAddr = $("#btnExposeAddr2");
        var displayAddr = addr;
        if (detail && detail.trim() !== "") {
            displayAddr = addr + " " + detail.trim();
        }
        $btnAddr.find(".address-text").text(displayAddr);
        $btnAddr.addClass("has-address");

        $("#changeArea").removeClass("on");
        savePage1Data();
    }

    // ============================================================================
    // 6. 폼 데이터 수집
    // ============================================================================

    // 해시태그 체크박스에서 선택된 값들을 쉼표로 구분된 문자열로 만들어 반환
    function buildHashString() {
        var hashArray = [];
        $("[name='rc_hash[]']:checked").each(function() {
            hashArray.push($(this).val());
        });
        return hashArray.join(",");
    }

    // 업무구분 체크박스에서 선택된 값들의 이미지를 콤마로 구분된 문자열로 만들어 저장
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

    // 필수 필드 검증
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

    // 파일 업로드 여부 확인
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

    // ============================================================================
    // 7. UI 업데이트
    // ============================================================================

    // 급여 유형에 따라 급여 옵션 표시/숨김 처리
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

    // ============================================================================
    // 8. API 호출
    // ============================================================================

    // 1, 2 페이지 저장 및 rc_idx 발급/마이그레이션
    function saveStep1And2(onSuccess) {
        collectRcWorkImg();
        var currentAmount = removeComma($("#rc_price_amount").val());
        $("#rc_price_amount").val(currentAmount);
        var params = $("#frm").serialize();
        $("#rc_price_amount").val(formatNumberWithComma(currentAmount));
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

    // 3 페이지 저장 및 완료 처리 (preview 또는 final)
    function saveStep3AndComplete(mode) {
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
                    showPreviewPage(savedRcIdx);
                } else {
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

    // ============================================================================
    // 9. 화면 전환
    // ============================================================================

    // 미리보기 페이지로 이동
    function showPreviewPage(rc_idx) {
        var memInfo = {
            mm_comp_name: '<?= isset($mem_info["mm_comp_name"]) ? $mem_info["mm_comp_name"] : "" ?>',
            mm_phone: '<?= isset($mem_info["mm_phone"]) ? $mem_info["mm_phone"] : "" ?>'
        };
        sessionStorage.setItem('previewMemInfo', JSON.stringify(memInfo));
        sessionStorage.setItem('from_preview', 'true');

        window.location.href = '/recruit/job_post/' + rc_idx + '?preview=1';
    }

    // 미리보기에서 최종 저장 (window.parent에서 접근 가능)
    function submitPreview() {
        handleSubmit("final");
    }

    // ============================================================================
    // 10. 핵심 흐름
    // ============================================================================

    // 제출 처리 (모든 검증 및 저장)
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

    // ============================================================================
    // 11. 초기화 & 이벤트 핸들링
    // ============================================================================

    var updateTimeSection_fn;

    window.addEventListener('DOMContentLoaded', function() {
        // URL 파라미터 처리 및 draft 로드 여부 판단
        var urlParams = new URLSearchParams(window.location.search);
        var queryRcIdx = urlParams.get('rc_idx');

        var fromPreviewFlag = sessionStorage.getItem('from_preview') === 'true';
        window.shouldLoadDraft = (queryRcIdx !== null) || fromPreviewFlag;

        if (queryRcIdx) {
            $("[name='rc_idx']").val(queryRcIdx);
            window.history.replaceState({}, document.title, '/compage/recruit_write1_1');
        }

        if (fromPreviewFlag) {
            sessionStorage.removeItem('from_preview');
        }

        // 데이터 복원
        restorePageData();

        // 근무시간 섹션 초기화
        updateTimeSection();

        // 급여 금액 포맷팅 이벤트
        $("#rc_price_amount").on("input", function() {
            var value = $(this).val();
            var numericValue = removeComma(value);

            if (numericValue !== String(parseInt(numericValue) || 0)) {
                numericValue = String(parseInt(numericValue) || 0);
            }

            $(this).val(formatNumberWithComma(numericValue));
        });

        // flatpickr 날짜 선택기 초기화
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
            syncTimeHiddenFields();
        }, 1000);

        updateTimeSection();
        updateNegotiationCheckbox();
        updateSalaryOptions();

        // 페이지1 입력 이벤트
        $("[name='rc_name']").on("input", savePage1Data);
        $("#rc_price_type_main").on("change", function() {
            updateSalaryOptions();
            savePage1Data();
        });
        $("#rc_price_amount").on("input", savePage1Data);
        $("[name='rc_price_possible']").on("change", savePage1Data);
        $("[name='rc_work[]']").on("change", savePage1Data);

        // 근무시간 및 근무협의 처리
        function updateTimeSection() {
            var isAM = $("[name='rc_work_type[]'][value='주간']").is(":checked");
            var isPM = $("[name='rc_work_type[]'][value='야간']").is(":checked");
            var is2 = $("[name='rc_work_type[]'][value='2교대']").is(":checked");

            if (isAM || is2) {
                $("#time_cont1").show();
            } else {
                $("#time_cont1").hide();
                $("[name='rc_am_stime_h']").val("09");
                $("[name='rc_am_stime_m']").val("00");
                $("[name='rc_am_etime_h']").val("18");
                $("[name='rc_am_etime_m']").val("00");
            }

            if (isPM || is2) {
                $("#time_cont2").show();
            } else {
                $("#time_cont2").hide();
                $("[name='rc_pm_stime_h']").val("21");
                $("[name='rc_pm_stime_m']").val("00");
                $("[name='rc_pm_etime_h']").val("06");
                $("[name='rc_pm_etime_m']").val("00");
            }

            updateNegotiationCheckbox();
        }

        updateTimeSection_fn = updateTimeSection;

        function updateNegotiationCheckbox() {
            var isAM = $("[name='rc_work_type[]'][value='주간']").is(":checked");
            var isPM = $("[name='rc_work_type[]'][value='야간']").is(":checked");
            var is2 = $("[name='rc_work_type[]'][value='2교대']").is(":checked");

            var allSelected = isAM && isPM && is2;
            $("[name='rc_work_negotiation']").prop("checked", allSelected);
        }

        $("[name='rc_work_type[]']").on("change", function() {
            updateTimeSection();
            savePage1Data();
        });

        $("[name='rc_am_stime_h'], [name='rc_am_stime_m'], [name='rc_am_etime_h'], [name='rc_am_etime_m']").on("change", savePage1Data);
        $("[name='rc_pm_stime_h'], [name='rc_pm_stime_m'], [name='rc_pm_etime_h'], [name='rc_pm_etime_m']").on("change", savePage1Data);

        $("[name='rc_work_negotiation']").on("change", function() {
            var isNegotiationChecked = $(this).is(":checked");

            if (isNegotiationChecked) {
                $("[name='rc_work_type[]'][value='주간']").prop("checked", true);
                $("[name='rc_work_type[]'][value='야간']").prop("checked", true);
                $("[name='rc_work_type[]'][value='2교대']").prop("checked", true);
            } else {
                $("[name='rc_work_type[]']").prop("checked", false);
            }

            updateTimeSection();
            savePage1Data();
        });

        // 페이지2 입력 이벤트
        $("[name='rc_life_type[]']").on("change", savePage2Data);

        // 페이지3 입력 이벤트
        $("[name='rc_desc']").on("input", savePage3Data);
        $("[name='rc_hash[]']").on("change", savePage3Data);
        $("[name='rc_day[]'], [name='rc_day_all']").on("change", savePage3Data);
        $("[name='rc_sex[]'], [name='rc_sex_all']").on("change", savePage3Data);
        $("[name='rc_nation[]'], [name='rc_nation_all']").on("change", savePage3Data);
        $("[name='rc_sage'], [name='rc_eage'], [name='rc_age_all']").on("change", savePage3Data);

        $(document).on("change", "[name='rc_sdate'], [name='rc_edate']", function() {
            savePage3Data();
        });

        // 선택 제한 처리
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

        // 급여 라디오 버튼 토글 처리
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

        // 폼 체크박스 라벨 클릭 처리
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

        // 상세등록 버튼
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

        // 해시태그 추가
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

        // 기본값 설정
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
                $("[name='rc_sex_all']").prop("checked", true).trigger("change");
            }
        }());

        (function() {
            var $nationCheckboxes = $("[name='rc_nation[]']");
            var hasExistingNationSelection = $nationCheckboxes.is(":checked");
            var isNationAllSelected = $("[name='rc_nation_all']").is(":checked");

            if (!hasExistingNationSelection && !isNationAllSelected) {
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

        // 요일 선택 이벤트
        $("[name='rc_day_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_day[]']").prop("checked", true);
            } else {
                $("[name='rc_day[]']").prop("checked", false);
            }
        });

        $("[name='rc_day[]']").on("change", function() {
            var checkedCount = $("[name='rc_day[]']:checked").length;
            var totalCount = $("[name='rc_day[]']").length;
            if (checkedCount === totalCount) {
                $("[name='rc_day_all']").prop("checked", true);
            } else if ($("[name='rc_day_all']").is(":checked")) {
                $("[name='rc_day_all']").prop("checked", false);
            }
        });

        // 성별 선택 이벤트
        $("[name='rc_sex_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_sex[]']").prop("checked", true);
            } else {
                $("[name='rc_sex[]']").prop("checked", false);
            }
        });

        $("[name='rc_sex[]']").on("change", function() {
            var checkedCount = $("[name='rc_sex[]']:checked").length;
            var totalCount = $("[name='rc_sex[]']").length;
            if (checkedCount === totalCount) {
                $("[name='rc_sex_all']").prop("checked", true);
            } else {
                $("[name='rc_sex_all']").prop("checked", false);
            }
        });

        // 국적 선택 이벤트
        $("[name='rc_nation_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_nation[]']").prop("checked", true);
            } else {
                $("[name='rc_nation[]']").prop("checked", false);
            }
        });

        $("[name='rc_nation[]']").on("change", function() {
            var checkedCount = $("[name='rc_nation[]']:checked").length;
            var totalCount = $("[name='rc_nation[]']").length;
            if (checkedCount === totalCount) {
                $("[name='rc_nation_all']").prop("checked", true);
            } else {
                $("[name='rc_nation_all']").prop("checked", false);
            }
        });

        // 나이 선택 이벤트
        $("[name='rc_age_all']").on("change", function() {
            if ($(this).is(":checked")) {
                $("[name='rc_sage']").val("");
                $("[name='rc_eage']").val("");
            }
        });

        $("[name='rc_sage'], [name='rc_eage']").on("change", function() {
            var sageVal = $("[name='rc_sage']").val();
            var eageVal = $("[name='rc_eage']").val();

            if (sageVal !== "" && eageVal !== "") {
                $("[name='rc_age_all']").prop("checked", true);
            } else {
                $("[name='rc_age_all']").prop("checked", false);
            }
        });

        // 공고노출지역 버튼 처리
        $("#btnExposeAddr1").click(function(e) {
            e.preventDefault();
            window.selectAddrInfo = selectAddrInfoExpose1;
        });

        $("#btnExposeAddr2").click(function(e) {
            e.preventDefault();
            window.selectAddrInfo = selectAddrInfoExpose2;
        });

        $(document).on("click", ".js-clear-expose-addr", function(e) {
            e.preventDefault();
            var index = $(this).data("index");
            var btnId = "#btnExposeAddr" + index;
            var $btn = $(btnId);

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

            $btn.find(".address-text").text("");
            $btn.removeClass("has-address");

            savePage1Data();
        });

        // 파일 미리보기
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

        // 미리보기 및 최종저장 버튼
        $("#btnPreview").click(function() {
            handleSubmit("preview");
        });

        $("#btnSubmit").click(function() {
            handleSubmit("final");
        });

        // 뒤로가기 버튼
        $(".btn-back").click(function(e) {
            e.preventDefault();
            savePage1Data();
            savePage2Data();
            savePage3Data();
            clearRecruitDraftData($("[name='rc_idx']").val() || "0");
            location.href = "/compage/recruit_list";
        });

        // 히스토리 상태 관리
        history.pushState({page: 'recruit_write1_1'}, null, location.href);

        window.addEventListener('popstate', function(e) {
            if (!e.state || e.state.page === 'recruit_write1_1') {
                savePage1Data();
                savePage2Data();
                savePage3Data();
                clearRecruitDraftData($("[name='rc_idx']").val() || "0");
                location.href = "/compage/recruit_list";
            }
        });
    });

    // 물리적 뒤로가기 시 데이터 복원
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            restorePageData();
        }
    });

    // 폼 제출 시 contenteditable 내용 저장
    $('form').on('submit', function(e) {
        var editorContent = document.getElementById('rc_desc_editor').innerHTML;
        document.getElementById('rc_desc_input').value = editorContent;
    });

    // 모바일 사진 업로드
    $('#uploadPhotoBtn_mobile').on('click', function(e) {
        e.preventDefault();
        $('#photoFileInput_mobile').click();
    });

    $('#photoFileInput_mobile').on('change', function() {
        if (this.files && this.files.length > 0) {
            showLoadingOverlay();

            var files = Array.from(this.files);
            var uploadCount = 0;

            files.forEach(function(file) {
                var formData = new FormData();
                formData.append('Filedata', file);

                $.ajax({
                    url: '/compage_proc/se2_image_upload',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        uploadCount++;

                        if (response.success === 1) {
                            var editor = document.getElementById('rc_desc_editor');
                            var imageWrapper = document.createElement('div');
                            imageWrapper.className = 'image-wrapper';
                            imageWrapper.style.cssText = 'position: relative; display: inline-block; margin: 10px 0;';

                            var img = document.createElement('img');
                            img.src = response.url;
                            img.alt = response.name;
                            img.style.cssText = 'max-width: 100%; height: auto; border-radius: 5px; border: 1px solid #ddd; display: block;';
                            img.setAttribute('contenteditable', 'false');

                            imageWrapper.appendChild(img);
                            editor.appendChild(document.createElement('br'));
                            editor.appendChild(imageWrapper);
                        } else {
                            alert(file.name + ' 업로드에 실패했습니다.');
                        }

                        if (uploadCount === files.length) {
                            hideLoadingOverlay();
                            $('#photoFileInput_mobile').val('');
                        }
                    },
                    error: function(xhr, status, error) {
                        uploadCount++;

                        alert(file.name + ' 업로드 중 오류가 발생했습니다.');

                        if (uploadCount === files.length) {
                            hideLoadingOverlay();
                            $('#photoFileInput_mobile').val('');
                        }
                    }
                });
            });
        }
    });

</script>
