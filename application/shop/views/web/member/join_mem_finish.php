<?php
// Kakao Maps SDK 키 로드 (지오코딩 사용)
$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
// 구글 가입자용 QuickInfo 바텀시트 로드
require_once __DIR__ . '/../../mobile/_partial/bottom_sheet_template.php';
require_once __DIR__ . '/../../mobile/_partial/bottom_sheet_helpers.php';
require_once __DIR__ . '/../../mobile/_partial/join_quickinfo_bottom_sheet.php';
?>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>&libraries=services"></script>

<div id="content" class="page page-join page-member-type1">
    <main class="page-content">
        <div class="container">

            <div class="join-welcome">
                <h2 class="primary-title">가입 축하드립니다</h2>
                <p>나우잡의 제공하는 채용정보및 다양한혜택을<br> 이용하실 수 있습니다.</p>
                <div class="form-action-wrap" id="non_mem_last_cont" style="display:none;">
                </div>
                <div class="form-action-wrap">
                    <a href="/" class="btn-round btn-tertiary">메인으로 바로가기</a>
                    <?php if ($this->user->getUserType() == 'MEM') { ?>
                        <!-- <a href="/mypage/resume_list" class="btn-round btn-tertiary" style="margin-left:20px;">이력서 등록하러 가기</a> -->
                    <?php } ?>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
<div id="auto-resume-loading-web" class="auto-resume-loading-web" aria-hidden="true">
  <div class="loading-card" role="status" aria-live="polite">
    <p class="loading-title">맞춤형 공고 나우잡이 찾아드립니다.</p>
    <p class="loading-desc">딱 맞는 공고만 선별중입니다. 잠시만 기다려주세요.</p>
    <div class="loading-spinner" aria-hidden="true"></div>
  </div>
</div>
<style>
  /* join_mem_finish에서만 주소 팝업 크기 축소 */
  #changeArea .panel-container {
    max-width: 720px;
    width: 90vw;
    max-height: 88vh;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
  }

  #changeArea .panel-body {
    max-height: calc(86vh - 64px);
    overflow: auto;
  }

  /* PC용 QuickInfo 바텀시트 레이아웃 보정 */
  .layer-panel {
    display: none;
  }
  .layer-panel.on {
    display: block;
  }
  .layer-panel.card-type {
    position: fixed;
    inset: 0;
    z-index: 9998;
  }
  .layer-panel.card-type::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    opacity: 0;
    transition: opacity 0.2s ease;
  }
  .layer-panel.card-type.on::before {
    opacity: 1;
  }
  .layer-panel.card-type .bottom-sheet-container {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 420px;
    max-width: 92vw;
    background: #fff;
    border-radius: 24px;
    transform: translate(-50%, calc(-50% + 24px));
    opacity: 0;
    transition: transform 0.25s ease, opacity 0.25s ease;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.2);
  }
  .layer-panel.card-type.on .bottom-sheet-container {
    transform: translate(-50%, -50%);
    opacity: 1;
  }
  .bottom-sheet-content-container {
    background: #fff;
  }
  .bottom-sheet-content-body {
    padding: 0 24px 16px;
  }
  .bottom-sheet-actions--split {
    display: flex;
    gap: 12px;
    padding: 12px 24px 20px;
  }
  .bottom-sheet-action {
    flex: 1;
    height: 56px;
    border-radius: 28px;
    font-weight: 700;
    border: 0;
    cursor: pointer;
  }
  .bottom-sheet-action.btn-bottom-sheet-secondary {
    background: #fff;
    border: 1px solid #1B9D7D;
    color: #1B9D7D;
  }
  .bottom-sheet-action.btn-bottom-sheet-primary {
    background: #1B9D7D;
    color: #fff;
  }
  .bottom-sheet-handlebar {
    width: 100%;
    padding: 10px 0 4px;
  }
  .bottom-sheet-handlebar::before {
    content: "";
    display: block;
    width: 44px;
    height: 4px;
    background: #d9d9d9;
    border-radius: 2px;
    margin: 0 auto;
  }
  .quickinfo-sheet {
    padding: 4px 24px 12px;
  }
  .quickinfo-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin: 6px 0 16px;
    font-size: 16px;
    font-weight: 700;
    color: #222;
  }
  .quickinfo-region-field {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 10px 16px;
    border: 1px solid #d6d6d6;
    border-radius: 999px;
    background: #fff;
    font-size: 14px;
    color: #666;
  }
  .quickinfo-group {
    margin-bottom: 14px;
  }
  .quickinfo-label {
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #222;
  }
  .quickinfo-sheet .form-toggle-btn2 .btn {
    min-width: 60px;
    height: 36px;
    line-height: 36px;
    padding: 0 12px;
    border-radius: 18px;
  }
</style>

<script>
    if (getCookie("non_mem_last_cont") != '' && getCookie("non_mem_last_cont") != 'null') {
        var msg = '';
        // msg = '<a href="' + getCookie("non_mem_last_cont") + '" class="btn-round btn-tertiary" style="width: 564px;">채용공고 계속보기</a><br />';
        $('#non_mem_last_cont').append(msg);
        $('#non_mem_last_cont').show();
        setCookie('non_mem_last_cont', '', 1);
    }
</script>

<script>
(function() {
    // 위치 권한 동의 시 자동 이력서 작성 흐름 (웹)
    var LOCATION_PERMISSION_KEY = "nowjob_location_permission_status";
    var isLogin = <?= $this->user->isLogin() ? 'true' : 'false' ?>;
    var isMem = <?= ($this->user->isLogin() && $this->user->getUserType() === 'MEM') ? 'true' : 'false' ?>;
    var JOIN_TYPE = "<?= $this->user->isLogin() ? $this->user->getJoinType() : '' ?>";
    var IS_GOOGLE_JOIN = (String(JOIN_TYPE || "").toUpperCase() === "GOOGLE");
    // QuickInfo 상태 플래그
    window.__quickInfoExpectLocation = false; // 위치 확인 플로우 진행 중 여부 (true면 위치 확인 UI를 기대)
    window.__quickInfoApplyFlow = false; // QuickInfo '적용하기'로 제출하는 흐름인지 여부
    window.__quickInfoSelections = null; // 사용자가 선택한 QuickInfo 값 캐시 (nation/gender/age)
    window.__quickInfoManualAddrMode = false; // 주소 팝업이 QuickInfo 수동 선택 모드로 열렸는지 여부
    window.__quickInfoManualAddr = null; // 수동 선택된 주소 payload 캐시 (있으면 GPS 결과보다 우선)
    window.__returnToQuickInfo = false; // 주소 팝업 적용 후 QuickInfo 패널을 다시 열지 여부
    window.__disableChangeAreaClose = false; // QuickInfo에서 연 changeArea를 X/배경/ESC로 닫지 않도록 제어


    if (!isLogin || !isMem) {
        return;
    }

    function getStoredPermissionStatus() {
        // 로컬스토리지에 저장된 위치 권한 상태 조회
        try {
            return localStorage.getItem(LOCATION_PERMISSION_KEY);
        } catch (e) {
            return null;
        }
    }

    function navigateToResumeWrite(addrPayload) {
        // 웹 자동 작성은 페이지 이동 없이 처리
        // Deprecated: 웹 자동 작성은 페이지 이동 없이 처리
        autoSaveResume(addrPayload);
    }

    function showAutoResumeLoading() {
        var el = document.getElementById("auto-resume-loading-web");
        if (!el) return;
        el.style.display = "flex";
        el.setAttribute("aria-hidden", "false");
    }

    function hideAutoResumeLoading() {
        var el = document.getElementById("auto-resume-loading-web");
        if (!el) return;
        el.style.display = "none";
        el.setAttribute("aria-hidden", "true");
    }

    // 주소 선택 팝업을 열고 필요한 필터/상태를 세팅
    function openManualAddressPopup() {
        // 주소 직접 입력 팝업 열기
        window.__hideAllRegionsInAddrPopup = true;
        if (typeof window.applyAddrPopupAllRegionFilter === 'function') {
            window.applyAddrPopupAllRegionFilter();
        }
        var panel = document.getElementById("changeArea");
        if (!panel) return;
        if (typeof window.openPanel === 'function') {
            window.openPanel(panel);
        } else {
            $("#changeArea").addClass("on");
            panel.hidden = false;
            document.body.style.overflow = 'hidden';
        }
    }

    // QuickInfo 영역에 표시되는 지역 텍스트 업데이트
    function setQuickInfoRegionText(text) {
        var el = document.querySelector('.quickinfo-region-text');
        if (el) el.textContent = text;
    }

    // QuickInfo에서 선택된 값(국적/성별/나이)을 수집
    function getQuickInfoSelections() {
        var nationVal = $('input[name="quickinfo_nation"]:checked').val() || "";
        var genderVal = $('input[name="quickinfo_gender"]:checked').val() || "";
        var ageVal = $('input[name="quickinfo_age"]:checked').val() || "";

        var nationText = "";
        if (nationVal === "foreign") {
            nationText = "외국인";
        } else if (nationVal === "domestic") {
            nationText = "내국인";
        }

        var genderText = "";
        if (genderVal === "male") {
            genderText = "남";
        } else if (genderVal === "female") {
            genderText = "여";
        }

        return {
            nation: nationText,
            gender: genderText,
            age: ageVal
        };
    }

    // 선택된 QuickInfo 값을 서버 전송 payload에 병합
    function applyQuickInfoToPayload(payload, selections) {
        var sel = selections || window.__quickInfoSelections || getQuickInfoSelections();
        if (!sel) return payload;

        if (sel.nation) {
            payload.ru_nationality = sel.nation;
        }
        if (sel.gender) {
            payload.quickinfo_gender = sel.gender;
        }
        if (sel.age) {
            payload.quickinfo_age = sel.age;
        }
        return payload;
    }

    // 주소 + QuickInfo 값을 합쳐 서버로 제출
    function submitQuickInfoWithAddress(addrPayload, selections) {
        var payload = applyQuickInfoToPayload(Object.assign({}, addrPayload), selections);
        submitQuickInfoToServer(payload);
    }

    // QuickInfo 최종 제출 및 자동 이력서 생성 처리
    function submitQuickInfoToServer(payload) {
        if (window.__quickInfoSubmitting) return;
        window.__quickInfoSubmitting = true; // QuickInfo 중복 제출 방지 플래그
        showAutoResumeLoading();

        var params = Object.assign({}, payload || {}); // 서버 전송 파라미터 기본값: ru_name/ru_addr, ad_addr/ad_jibun/ad_doro/ad_zipcode/ad_detail, ad_gps_x/ad_gps_y 등 주소 정보
        params.ru_name = params.ru_name || params.ru_name_seed_addr || "";
        params.ru_addr = params.ru_addr || params.ad_addr || params.ru_name_seed_addr || "";

        if (params.ad_addr && !Array.isArray(params.ad_addr)) params.ad_addr = [params.ad_addr];
        if (params.ad_jibun && !Array.isArray(params.ad_jibun)) params.ad_jibun = [params.ad_jibun];
        if (params.ad_doro && !Array.isArray(params.ad_doro)) params.ad_doro = [params.ad_doro];
        if (params.ad_zipcode && !Array.isArray(params.ad_zipcode)) params.ad_zipcode = [params.ad_zipcode];
        if (params.ad_detail && !Array.isArray(params.ad_detail)) params.ad_detail = [params.ad_detail];
        if (params.ad_gps_x && !Array.isArray(params.ad_gps_x)) params.ad_gps_x = [params.ad_gps_x];
        if (params.ad_gps_y && !Array.isArray(params.ad_gps_y)) params.ad_gps_y = [params.ad_gps_y];

        comAjax("/mypage_proc/resume_autofill_quick", params, function(ret) {
            window.__quickInfoSubmitting = false;
            hideAutoResumeLoading();
            if (ret && ret.code === "00") {
                location.href = "/recruit/job_list";
            } else {
                commonAlert((ret && ret.msg) ? ret.msg : "자동이력서 생성에 실패했습니다.", function() {});
            }
        });
    }

    function getRegionName(rawAddr) {
        // 주소 문자열에서 동/읍/면/리 단위 지역명 추출
        var addr = String(rawAddr || "").trim();
        if (!addr) return "우리 동네";
        var parts = addr.split(/\s+/);
        var last = parts[parts.length - 1];
        if (/[동면읍리]$/.test(last)) return last;
        if (parts.length > 1) return parts[parts.length - 2];
        return last;
    }

    function buildResumeTitle(rawAddr) {
        // 지역명을 기반으로 랜덤 이력서 제목 생성
        var regionName = getRegionName(rawAddr);
        var titleTemplates = [
            function(r) { return r + " 기반, 준비된 맞춤형 인재입니다!"; },
            function(r) { return "번개처럼 빠르게 적응하는 " + r + " 구직자"; },
            function(r) { return "성실함이 무기! " + r + " 근처 바로 출근 가능합니다."; },
            function(r) { return "나우잡이 추천하는" + r+ " 열정 인재"; },
            function(r) { return "복잡한 건 생략! 실력으로 보여드릴 " + r + " 지원자"; },
            function(r) { return "지금 즉시 매칭 가능한 " + r + " 최적화 인재"; },
            function(r) { return r+"에서 누구보다 빠르게, 열정적으로 일할 준비가 되었습니다!"; },
            function(r) { return r + " 거주 중! 꼼꼼함과 책임감을 겸비한 인재"; },
            function(r) { return "사장님이 찾으시던 바로 그 " + r + " 꿀알바 지원생"; },
            function(r) { return r+"에서 지금 바로 출근준비 되어있습니다."; }
        ];
        var chosen = titleTemplates[Math.floor(Math.random() * titleTemplates.length)];
        return chosen(regionName);
    }

    function safeCommonAlert(msg, callback) {
        // "처리완료" 메시지는 알럿 표시 없이 무시
        if (!msg) return;
        if (String(msg).indexOf("처리완료") !== -1) {
            if (typeof callback === 'function') {
                callback();
            }
            return;
        }
        commonAlert(msg, callback || function() {});
    }

    function autoSaveResume(addrPayload) {
        // 자동 이력서 저장 및 완료 처리
        // 자동작성 중 "처리완료" 공통 알럿 차단 (팝업 방해 방지)
        if (typeof window.commonAlert === 'function' && !window.__autoResumeAlertWrapped) {
            window.__autoResumeAlertWrapped = true;
            window.__autoResumeOriginalAlert = window.commonAlert;
            window.commonAlert = function(msg, callback) {
                if (msg === "처리완료" && window.__autoResumeFlowActive === true) {
                    if (typeof callback === 'function') {
                        callback();
                    }
                    return;
                }
                return window.__autoResumeOriginalAlert(msg, callback || function() {});
            };
        }
        window.__autoResumeFlowActive = true;

        var title = buildResumeTitle(addrPayload.ad_addr || addrPayload.ad_jibun || addrPayload.ru_name_seed_addr);
        var params = {
            ru_name: title,
            ru_work: "생산",
            ru_price_type: "월급",
            ru_work_type: "정규직",
            ru_nationality: "내국인",
            ru_addr: addrPayload.ad_addr || addrPayload.ad_jibun || "",
            ad_zipcode: addrPayload.ad_zipcode || "",
            ad_jibun: addrPayload.ad_jibun || "",
            ad_doro: addrPayload.ad_doro || "",
            ad_addr: addrPayload.ad_addr || "",
            ad_gps_x: addrPayload.ad_gps_x || "",
            ad_gps_y: addrPayload.ad_gps_y || "",
            ru_img1_ori: "/resource/shop/web/img/avatar_resume_w.png"
        };

        // 자동완성용 제목을 payload에 포함 (resume_write에서 재사용)
        addrPayload.ru_name = title;

        comAjax("/mypage_proc/resume_write", params, function(ret) {
            if (typeof ret === "string") {
                try { ret = JSON.parse(ret); } catch (e) {}
            }
            if (ret && ret.code === "00") {
                var ruIdx = (ret.data && ret.data.ru_idx) ? ret.data.ru_idx : 0;
                comAjax("/mypage_proc/resume_complete", { ru_idx: ruIdx }, function(ret2) {
                    if (typeof ret2 === "string") {
                        try { ret2 = JSON.parse(ret2); } catch (e) {}
                    }
                    if (ret2 && (ret2.code === "00" || ret2.msg === "처리완료")) {
                        location.href = "/recruit/job_list?auto_resume=1";
                        return;
                    }
                    window.__autoResumeFlowActive = false;
                    if (ret2 && ret2.code === "91") {
                        safeCommonAlert(ret2.msg, function() {
                            fManCheck();
                        });
                    } else {
                        safeCommonAlert((ret2 && ret2.msg) ? ret2.msg : "이력서 등록에 실패했습니다.", function() {});
                    }
                });
            } else {
                window.__autoResumeFlowActive = false;
                safeCommonAlert((ret && ret.msg) ? ret.msg : "이력서 등록에 실패했습니다.", function() {});
            }
        });
    }

    // 주소 선택(모바일 UI) 시 자동 이력서 작성 플로우
    // 주소 팝업 적용 시 선택된 주소를 처리
    window.onChangeAreaApply = function(payload) {
        // 주소 선택 팝업에서 선택된 주소를 받아 자동 이력서 저장 실행
        var areaList = (payload && Array.isArray(payload.searchArea)) ? payload.searchArea : []; // 주소 팝업에서 선택된 지역 목록
        if (areaList.length === 0 || !areaList[0] || !areaList[0].addr) {
            alert("주소를 선택해 주세요.");
            return true;
        }
        // addr 고덕동
        var addr = String(areaList[0].addr || "").trim(); // 선택된 첫 번째 지역 문자열(예: "서울특별시 강동구 고덕동")
        if (!addr) {
            alert("주소를 선택해 주세요.");
            return true;
        }

        // 주소 선택 직후 UI 텍스트를 먼저 갱신 (PC에서 중복 alert 방지)
        var displayAddr = (typeof fRemoveBigAdrJS === 'function' ? (fRemoveBigAdrJS(addr) || addr) : addr); // 화면에 표시할 축약 주소
        setQuickInfoRegionText(displayAddr);

        var geocoderAvailable = (window.kakao && kakao.maps && kakao.maps.services && kakao.maps.services.Geocoder); // 카카오 지오코더 사용 가능 여부
        if (!geocoderAvailable) {
            alert("주소 좌표 변환에 실패했습니다. 다시 시도해 주세요.");
            location.reload();
            return true;
        }
        // 주소 -> 좌표 / addr 고덕동
        var geocoder = new kakao.maps.services.Geocoder();
        geocoder.addressSearch(addr, function(result, status) {
            if (status !== kakao.maps.services.Status.OK || !result || !result[0] || !result[0].x || !result[0].y) {
                alert("주소 좌표 변환에 실패했습니다. 다시 시도해 주세요.");
                location.reload();
                return;
            }

            var addrPayload = { // 서버에 전달할 주소 payload
                ru_name_seed_addr: addr,
                ad_zipcode: "",
                ad_jibun: addr,
                ad_doro: "",
                ad_addr: addr,
                ad_detail: "",
                ad_gps_x: result[0].x,
                ad_gps_y: result[0].y
            };

            // QuickInfo 수동 주소 선택 모드라면: 주소만 캐시 + UI 갱신
            if (window.__quickInfoManualAddrMode) {
                window.__quickInfoManualAddrMode = false;
                window.__quickInfoManualAddr = addrPayload;
                var regionEl = document.querySelector('.quickinfo-region-text');
                if (regionEl) {
                    regionEl.textContent = (typeof fRemoveBigAdrJS === 'function' ? (fRemoveBigAdrJS(addr) || addr) : addr);
                }
                return;
            }

            // QuickInfo 적용하기 흐름이라면 자동이력서 생성
            if (window.__quickInfoApplyFlow) {
                submitQuickInfoWithAddress(addrPayload);
                return;
            }

            autoSaveResume(addrPayload);
        });

        return true;
    };

    // 브라우저 위치 권한을 요청하고 성공 시 자동 작성 시작
    function requestLocationAndStart() {
        // 브라우저 위치 가져오기 후 주소 변환 → 자동 이력서 저장
        if (!navigator.geolocation) {
            return;
        }

        var completed = false; // 위치 흐름 완료 여부(중복 처리 방지)
        var timeoutId = setTimeout(function() {
            if (completed) return;
            completed = true;
            openManualAddressPopup();
        }, 7000);

        navigator.geolocation.getCurrentPosition(
            function(position) {
                if (completed) return;
                completed = true;
                clearTimeout(timeoutId);
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                comAjax("/common_proc/get_gps_address", {
                    x: lng,
                    y: lat
                }, function(ret) {
                    if (ret.code === "00" && ret.data) {
                        var parts = String(ret.data).split("^");
                        var frontPart = (parts[0] || "").trim();
                        if (typeof fRemoveBigAdrJS === 'function') {
                            frontPart = fRemoveBigAdrJS(frontPart) || frontPart;
                        }

                        var addrPayload = {
                            ru_name_seed_addr: frontPart,
                            ad_zipcode: "",
                            ad_jibun: frontPart,
                            ad_doro: "",
                            ad_addr: frontPart,
                            ad_detail: "",
                            ad_gps_x: lng,
                            ad_gps_y: lat
                        };

                        autoSaveResume(addrPayload);
                    } else {
                        openManualAddressPopup();
                    }
                });
            },
            function(err) {
                if (completed) return;
                completed = true;
                clearTimeout(timeoutId);
                openManualAddressPopup();
            },
            {
                enableHighAccuracy: false,
                maximumAge: 0,
                timeout: 6000
            }
        );
    }

    // 자동 이력서 플로우 시작 조건 판단 및 초기 UI 세팅
    function startAutoResumeFlowIfNeeded() {
        // 자동 이력서 흐름 시작 조건 확인 (권한/이력서 개수)
        if (IS_GOOGLE_JOIN) {
            if (typeof window.openQuickInfoPanel === 'function') {
                window.openQuickInfoPanel();
            }
            // 위치 텍스트 업데이트 시도
            setQuickInfoRegionText("위치 확인 중...");
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    comAjax("/common_proc/get_gps_address", { x: lng, y: lat }, function(ret) {
                        // 사용자가 이미 수동으로 주소를 선택했으면 GPS 결과로 덮어쓰지 않음
                        if (window.__quickInfoManualAddr || window.__quickInfoManualAddrMode) {
                            return;
                        }
                        if (ret.code === "00" && ret.data) {
                            var parts = String(ret.data).split("^");
                            var frontPart = (parts[0] || "").trim();
                            if (typeof fRemoveBigAdrJS === 'function') {
                                frontPart = fRemoveBigAdrJS(frontPart) || frontPart;
                            }
                            setQuickInfoRegionText(frontPart || "근무 지역 선택");
                            window.__quickInfoManualAddr = {
                                ru_name_seed_addr: frontPart,
                                ad_zipcode: "",
                                ad_jibun: frontPart,
                                ad_doro: "",
                                ad_addr: frontPart,
                                ad_detail: "",
                                ad_gps_x: lng,
                                ad_gps_y: lat
                            };
                        } else {
                            // 수동 선택이 없을 때만 기본값으로 복원
                            if (!window.__quickInfoManualAddr && !window.__quickInfoManualAddrMode) {
                                setQuickInfoRegionText("근무 지역 선택");
                            }
                        }
                    });
                }, function() {
                    if (!window.__quickInfoManualAddr && !window.__quickInfoManualAddrMode) {
                        setQuickInfoRegionText("근무 지역 선택");
                    }
                }, { enableHighAccuracy: false, maximumAge: 0, timeout: 6000 });
            } else {
                if (!window.__quickInfoManualAddr && !window.__quickInfoManualAddrMode) {
                    setQuickInfoRegionText("근무 지역 선택");
                }
            }
            return;
        }
        var stored = getStoredPermissionStatus(); // 로컬스토리지에 저장된 위치 권한 상태
        if (stored === "granted") {
            requestLocationAndStart();
        } else {
            openManualAddressPopup();
        }
    }

    document.addEventListener('DOMContentLoaded', startAutoResumeFlowIfNeeded);

    // QuickInfo 지역 선택 클릭 시 주소 팝업 오픈
    $(document).on('click', '.quickinfo-region-field', function(e) {
        e.preventDefault();
        window.__quickInfoManualAddrMode = true;
        // 주소 팝업 적용 후 QuickInfo 패널로 돌아가기 위한 플래그
        window.__returnToQuickInfo = true;
        // QuickInfo에서 열린 주소 팝업은 배경/X로 닫히지 않게
        window.__disableChangeAreaClose = true;
        openManualAddressPopup();
    });

    // QuickInfo 적용하기
    // QuickInfo "적용하기" 클릭 핸들러
    window.handleQuickInfoApplyClick = function() {
        if (window.__quickInfoApplyClickLock) return;
        // 한 번에 여러 번 클릭되는 것을 방지하기 위한 lock 변수 (중복 클릭 방지)
        window.__quickInfoApplyClickLock = true;
        setTimeout(function() { window.__quickInfoApplyClickLock = false; }, 300);

        var selections = getQuickInfoSelections(); // QuickInfo 폼에서 수집한 선택값 (nation/gender/age)
        var regionEl = document.querySelector('.quickinfo-region-text'); // 지역 텍스트가 표시되는 DOM
        var regionText = regionEl ? regionEl.textContent.trim() : ""; // 현재 화면에 보이는 지역 문자열

        if (!regionText || regionText === "근무 지역 선택" || regionText === "위치 확인 중...") {
            // 안내 팝업 없이 바로 주소 선택 팝업 오픈 (가장 단순하고 확실)
            window.__quickInfoManualAddrMode = true;
            window.__returnToQuickInfo = true;
            if (typeof openManualAddressPopup === 'function') {
                openManualAddressPopup();
            } else {
                var panel = document.getElementById("changeArea");
                if (panel) {
                    panel.classList.add("on");
                    panel.hidden = false;
                    document.body.style.overflow = 'hidden';
                } else {
                    $('.quickinfo-region-field').trigger('click');
                }
            }
            return;
        }
        if (!selections.nation) {
            commonAlert("국적을 선택해주세요.", function() {});
            return;
        }
        if (!selections.gender) {
            commonAlert("성별을 선택해주세요.", function() {});
            return;
        }
        if (!selections.age) {
            // 가장 단순한 방식으로 안내
            alert("나이를 선택해주세요.");
            return;
        }

        window.__quickInfoSelections = selections;
        window.__quickInfoApplyFlow = true;
        window.__quickInfoExpectLocation = false;

        if (window.__quickInfoManualAddr) {
            submitQuickInfoWithAddress(window.__quickInfoManualAddr, selections);
            return;
        }
        $('.quickinfo-region-field').trigger('click');
    };

    // inline onclick이 막히는 환경 대비용 핸들러 (중복 호출은 lock으로 방지)
    $(document).on('click', '#btnQuickInfoApply', function(e) {
        e.preventDefault();
        if (typeof window.handleQuickInfoApplyClick === 'function') {
            window.handleQuickInfoApplyClick();
        }
    });

    $(document).on('click', '#btnQuickInfoReset', function(e) {
        e.preventDefault();
        $('input[name="quickinfo_nation"][value="foreign"]').prop('checked', true).trigger('change');
        $('input[name="quickinfo_gender"][value="male"]').prop('checked', true).trigger('change');
        $('input[name="quickinfo_age"]').prop('checked', false);
        setQuickInfoRegionText("근무 지역 선택");
        window.__quickInfoSelections = null;
        window.__quickInfoManualAddrMode = false;
        window.__quickInfoManualAddr = null;
        window.__quickInfoExpectLocation = false;
    });

})();
</script>

<script>
// join_mem_finish: quickInfoPanel·changeArea 강제 닫기 차단
(function() {
    function preventPanelClose() {
        // 배경 클릭 및 ESC 닫기 차단 (data 속성)
        var ids = ['quickInfoPanel', 'changeArea'];
        for (var i = 0; i < ids.length; i++) {
            var panel = document.getElementById(ids[i]);
            if (panel) {
                panel.setAttribute('data-disable-bg-close', 'true');
                panel.setAttribute('data-disable-esc-close', 'true');
            }
        }

        // closePanel 오버라이드: 두 패널 모두 외부 닫기 차단
        if (typeof window.closePanel === 'function' && !window.__closePanel_joinFinish_patched) {
            window.__closePanel_joinFinish_patched = true;
            var _origClosePanel = window.closePanel;
            window.closePanel = function(el) {
                if (el && (el.id === 'quickInfoPanel' || el.id === 'changeArea')) {
                    return;
                }
                _origClosePanel(el);
            };
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', preventPanelClose);
    } else {
        preventPanelClose();
    }
})();
</script>

<?= getCashJs("/resource/shop/mobile/js/member/join_quickinfo_bottom_sheet.js") ?>
