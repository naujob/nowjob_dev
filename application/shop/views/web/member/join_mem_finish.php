<?php
// 가입 대기 상태 여부 (SNS 가입: 계정이 아직 생성되지 않음 → '적용하기'에서 생성)
$join_pending = (isset($join_pending) && $join_pending === "Y") ? "Y" : "N";

// Kakao Maps SDK 키 로드 (지오코딩 사용)
$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
// 구글 가입자용 QuickInfo 바텀시트 로드
require_once __DIR__ . '/../../mobile/_partial/bottom_sheet_template.php';
require_once __DIR__ . '/../../mobile/_partial/bottom_sheet_helpers.php';
require_once __DIR__ . '/../../mobile/_partial/join_quickinfo_bottom_sheet.php';
?>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?= $kakao_key["인증키"] ?>&libraries=services"></script>

<div id="content" class="page page-join page-member-type1">
    <main class="page-content">
        <div class="container">

            <div class="join-welcome">
                <?php if ($join_pending === 'Y'): ?>
                    <h2 class="primary-title">거의 다 왔어요!</h2>
                    <p>추가 정보를 입력하시면<br> 회원가입이 완료됩니다.</p>
                <?php else: ?>
                    <h2 class="primary-title">가입 축하드립니다</h2>
                    <p>나우잡의 제공하는 채용정보및 다양한혜택을<br> 이용하실 수 있습니다.</p>
                <?php endif; ?>
                <div class="form-action-wrap" id="non_mem_last_cont" style="display:none;">
                </div>
                <div class="form-action-wrap">
                    <?php if ($join_pending === 'Y'): ?>
                        <!-- 가입 완료 전이므로 메인 이동 버튼은 노출하지 않는다 -->
                        <button type="button" id="btnReopenQuickInfo" class="btn-round btn-tertiary">추가 정보 입력하기</button>
                        <a href="/member/login" id="btnCancelJoin" class="btn-round btn-tertiary" style="margin-left:20px;">취소하고 돌아가기</a>
                    <?php else: ?>
                        <a href="/" class="btn-round btn-tertiary">메인으로 바로가기</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
<div id="auto-resume-loading-web" class="auto-resume-loading-web" aria-hidden="true">
    <div class="loading-card" role="status" aria-live="polite">
        <p class="loading-title">맞춤형 공고 나우잡이 찾아드립니다.</p>
        <p class="loading-desc">딱 맞는 공고만 선별중입니다. 잠시만 기다려주세요.</p>
        <div class="loading-spinner" aria-hidden="true"></div>
    </div>
</div>

<!-- 주소 팝업 최대 선택 개수 미리 설정 (addr_popup 로드 전) -->
<script>
    window.__addrPopupMaxCount = 1;
</script>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>

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

    /* 경고/확인 팝업(commonAlert)이 QuickInfo 바텀시트(9998)·주소 팝업 뒤로 가려지지 않도록 최상단 처리 */
    .layer-popup {
        z-index: 100000;
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
#quickInfoPanel .bottom-sheet-container{
    height: 749px;
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
        // 컨트롤러가 넘긴 값을 쓴다. 가입 대기 상태는 비로그인이라 getJoinType()이 비기 때문.
        var JOIN_TYPE = "<?= isset($join_type) ? $join_type : '' ?>";
        // 가입 대기 상태: 계정이 아직 없고 '적용하기' 시점에 생성한다
        var JOIN_PENDING = ("<?= $join_pending ?>" === "Y");
        // join_complete_proc에 넘길 SNS 식별값
        var JOIN_KEY = "<?= isset($join_key) ? $join_key : '' ?>";
        var JOIN_UUID = "<?= isset($uuid) ? $uuid : '' ?>";
        // QuickInfo 바텀시트 노출 대상: SNS 가입자 4종 (모바일 SHOW_QUICKINFO와 동일)
        var SHOW_QUICKINFO = (["GOOGLE", "WECHAT", "KAKAO", "NAVER"].indexOf(String(JOIN_TYPE || "").toUpperCase()) !== -1);
        // QuickInfo 상태 플래그
        window.__quickInfoExpectLocation = false; // 위치 확인 플로우 진행 중 여부 (true면 위치 확인 UI를 기대)
        window.__quickInfoApplyFlow = false; // QuickInfo '적용하기'로 제출하는 흐름인지 여부
        window.__quickInfoSelections = null; // 사용자가 선택한 QuickInfo 값 캐시 (nation/gender/age)
        window.__quickInfoManualAddrMode = false; // 주소 팝업이 QuickInfo 수동 선택 모드로 열렸는지 여부
        window.__quickInfoManualAddr = null; // 수동 선택된 주소 payload 캐시 (있으면 GPS 결과보다 우선)
        window.__returnToQuickInfo = false; // 주소 팝업 적용 후 QuickInfo 패널을 다시 열지 여부
        window.__disableChangeAreaClose = false; // QuickInfo에서 연 changeArea를 X/배경/ESC로 닫지 않도록 제어


        // 가입 대기(SNS) 상태는 비로그인이므로 로그인 가드를 우회한다
        if (!JOIN_PENDING && (!isLogin || !isMem)) {
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

        // QuickInfo에서 선택된 값(국적/성별/출생연도/업종)을 수집
        function getQuickInfoSelections() {
            var nationVal = $('input[name="quickinfo_nation"]:checked').val() || "";
            var genderVal = $('input[name="quickinfo_gender"]:checked').val() || "";
            // 출생연도는 연도 휠이 채우는 hidden 값(YYYY)을 읽는다
            var birthYearVal = $('input[name="quickinfo_birth_year"]').val() || "";

            // 업종은 다중 선택(최대 2개)이므로 배열로 수집
            var workVals = [];
            $('input[name="quickinfo_work[]"]:checked').each(function() {
                workVals.push($(this).val());
            });

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
                birth_year: birthYearVal,
                work: workVals
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
            if (sel.birth_year) {
                payload.quickinfo_birth_year = sel.birth_year;
            }
            // 선택한 업종을 이력서 희망업무(ru_work)로 전달. 없으면 서버 기본값이 적용된다
            if (sel.work && sel.work.length > 0) {
                payload.ru_work = sel.work;
            }
            return payload;
        }

        // 주소 + QuickInfo 값을 합쳐 서버로 제출
        function submitQuickInfoWithAddress(addrPayload, selections) {
            var payload = applyQuickInfoToPayload(Object.assign({}, addrPayload), selections);
            submitQuickInfoToServer(payload);
        }

        // SNS 임시 키 정리 (가입 완료 후에만 호출한다)
        function clearJoinSnsKeys() {
            try {
                localStorage.removeItem("join_sns_type");
                localStorage.removeItem("join_sns_key");
                sessionStorage.removeItem("join_sns_type");
                sessionStorage.removeItem("join_sns_key");
                document.cookie = "join_sns_type=; path=/; max-age=0";
                document.cookie = "join_sns_key=; path=/; max-age=0";
            } catch (e) {}
        }

        // QuickInfo 최종 제출
        // 가입 대기(SNS) 상태면 계정 생성 -> 자동이력서 순으로 2단계 처리한다.
        // mypage_proc는 로그인 세션을 요구하므로 계정 생성이 반드시 선행되어야 한다.
        function submitQuickInfoToServer(payload) {
            if (window.__quickInfoSubmitting) return;
            window.__quickInfoSubmitting = true; // QuickInfo 중복 제출 방지 플래그
            showAutoResumeLoading();

            if (JOIN_PENDING) {
                var joinParams = {
                    ma_type: "MEM",
                    proc_type: "PART",
                    uuid: JOIN_UUID,
                    join_type: JOIN_TYPE,
                    join_key: JOIN_KEY
                };
                comAjax("/member_proc/join_complete_proc", joinParams, function(ret) {
                    if (ret && ret.code === "00") {
                        // 계정 생성 + 로그인 세션 확보 완료
                        clearJoinSnsKeys();
                        JOIN_PENDING = false;
                        doResumeAutofill(payload);
                        return;
                    }
                    window.__quickInfoSubmitting = false;
                    hideAutoResumeLoading();
                    // 가입 임시 데이터가 만료된 경우(code 10)는 재시도해도 소용없으므로 처음부터 다시
                    if (ret && ret.code === "10") {
                        commonAlert("가입 정보가 만료되었습니다. 다시 진행해주세요.", function() {
                            location.replace("/member/login");
                        });
                        return;
                    }
                    commonAlert((ret && ret.msg) ? ret.msg : "회원가입 처리에 실패했습니다.", function() {});
                });
                return;
            }

            doResumeAutofill(payload);
        }

        // 자동이력서 생성 (로그인 세션이 있는 상태에서만 호출된다)
        function doResumeAutofill(payload) {
            var params = Object.assign({}, payload || {}); // 서버 전송 파라미터 기본값: ru_name/ru_addr, ad_addr/ad_jibun/ad_doro/ad_zipcode/ad_detail, ad_gps_x/ad_gps_y 등 주소 정보
            params.ru_name = params.ru_name || params.ru_name_seed_addr || "";
            params.ru_addr = params.ru_addr || params.ad_addr || params.ru_name_seed_addr || "";
            params.ad_type = "resume"; // st_address 테이블에 이력서 주소로 저장

            // 희망업무는 서버가 $_POST["ru_work"]를 배열로 받으므로 배열로 보정
            if (params.ru_work && !Array.isArray(params.ru_work)) params.ru_work = [params.ru_work];

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
                    // replace: 가입 완료 페이지를 히스토리에서 교체해 홈에서 뒤로가기 시 이 페이지가 열리지 않게 한다
                    location.replace("/recruit/job_list");
                } else {
                    commonAlert((ret && ret.msg) ? ret.msg : "자동이력서 생성에 실패했습니다.", function() {});
                }
            });
        }

        // autoSaveResume / buildResumeTitle / getRegionName / safeCommonAlert 는 제거되었다.
        // 이력서 생성은 resume_autofill_quick 하나로 통일한다(제목 생성도 서버가 담당).

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

                // 좌표로 시/도가 포함된 정식 지번 주소를 조회해 ad_jibun에 사용 (실패 시 축약 주소 유지)
                comAjax("/common_proc/get_gps_address", {
                    x: result[0].x,
                    y: result[0].y
                }, function(ret) {
                    if (ret && ret.code === "00" && ret.data) {
                        var fullAddr = String(ret.data).split("^")[0].trim();
                        if (fullAddr) {
                            addrPayload.ad_jibun = fullAddr;
                            addrPayload.ru_name_seed_addr = fullAddr;
                        }
                    }

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

                    // 적용하기 흐름이든 아니든 동일하게 자동이력서를 생성한다
                    submitQuickInfoWithAddress(addrPayload);
                });
            });

            return true;
        };

        // requestLocationAndStart 는 제거되었다 - QuickInfo 바텀시트가 위치 수집을 담당한다.
        // QuickInfo 바텀시트 오픈 + 위치 자동 채우기
        // SNS/비SNS 구분 없이 항상 바텀시트를 연다 (가입 완료의 유일한 진행길)
        function startAutoResumeFlowIfNeeded() {
                if (typeof window.openQuickInfoPanel === 'function') {
                    window.openQuickInfoPanel();
                }
                // 위치 텍스트 업데이트 시도
                setQuickInfoRegionText("위치 확인 중...");
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var lat = position.coords.latitude;
                        var lng = position.coords.longitude;
                        comAjax("/common_proc/get_gps_address", {
                            x: lng,
                            y: lat
                        }, function(ret) {
                            // 사용자가 이미 수동으로 주소를 선택했으면 GPS 결과로 덮어쓰지 않음
                            if (window.__quickInfoManualAddr || window.__quickInfoManualAddrMode) {
                                return;
                            }
                            if (ret.code === "00" && ret.data) {
                                var parts = String(ret.data).split("^");
                                var fullAddr = (parts[0] || "").trim(); // 시/도 풀네임이 포함된 정식 주소 (ad_jibun용)
                                var frontPart = fullAddr; // 화면 표시/ad_addr용 축약 주소
                                if (typeof fRemoveBigAdrJS === 'function') {
                                    frontPart = fRemoveBigAdrJS(fullAddr) || fullAddr;
                                }
                                setQuickInfoRegionText(frontPart || "근무 지역 선택");
                                window.__quickInfoManualAddr = {
                                    ru_name_seed_addr: frontPart,
                                    ad_zipcode: "",
                                    ad_jibun: fullAddr,
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
                    }, {
                        enableHighAccuracy: false,
                        maximumAge: 0,
                        timeout: 6000
                    });
                } else {
                    if (!window.__quickInfoManualAddr && !window.__quickInfoManualAddrMode) {
                        setQuickInfoRegionText("근무 지역 선택");
                    }
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
            setTimeout(function() {
                window.__quickInfoApplyClickLock = false;
            }, 300);

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
            if (!selections.birth_year) {
                commonAlert("출생연도를 선택해주세요.", function() {});
                return;
            }
            if (!selections.work || selections.work.length === 0) {
                commonAlert("업종을 선택해주세요.", function() {});
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

        // 업종 선택 개수 제한 (data-select-limit) — 모바일과 동일한 규칙
        $(document).on('change', 'input[name="quickinfo_work[]"]', function() {
            var limit = parseInt($(this).attr('data-select-limit'), 10);
            if (!limit) return;

            var checkedCount = $('input[name="quickinfo_work[]"]:checked').length;
            if (this.checked && checkedCount > limit) {
                this.checked = false;
                commonAlert("최대 " + limit + "개까지 선택 가능합니다.", function() {});
            }
        });

        // QuickInfo 초기화 버튼: 모든 선택값과 지역을 선택 없음 상태로 되돌린다
        $(document).on('click', '#btnQuickInfoReset', function(e) {
            e.preventDefault();

            // 서버가 확보해 잠근 값(disabled)은 초기화 대상에서 제외한다.
            // jQuery의 .prop()는 disabled를 무시하므로 .not(':disabled')가 반드시 필요하다.
            $('input[name="quickinfo_nation"], input[name="quickinfo_gender"], input[name="quickinfo_work[]"]')
                .not(':disabled')
                .prop('checked', false).trigger('change');

            // 출생연도: 서버가 잠근 값(버튼 disabled)이 아니면 값과 트리거 문구를 비운다
            var birthBtn = document.getElementById('btnQuickInfoBirthYear');
            if (birthBtn && !birthBtn.disabled) {
                var birthHidden = document.getElementById('quickinfoBirthYear');
                if (birthHidden) birthHidden.value = '';
                birthBtn.classList.remove('has-value');
                var birthTxt = birthBtn.querySelector('.date-display');
                if (birthTxt) birthTxt.textContent = '출생연도를 선택해주세요';
            }

            setQuickInfoRegionText("근무 지역 선택");
            window.__quickInfoSelections = null;
            window.__quickInfoManualAddrMode = false;
            window.__quickInfoManualAddr = null;
            window.__quickInfoExpectLocation = false;
            window.__quickInfoApplyFlow = false;
        });

    })(); // IIFE 페이지 열리면 즉시 실행됨
</script>

<script>
    // join_mem_finish: 바텀시트 닫기 / 뒤로가기 처리
    (function() {
        var JOIN_PENDING_PANEL = ("<?= $join_pending ?>" === "Y");

        function initPanelExit() {
            // 배경 클릭·ESC로는 닫히지 않게 유지한다 (핸들바 탭만 허용)
            var ids = ['quickInfoPanel', 'changeArea'];
            for (var i = 0; i < ids.length; i++) {
                var el = document.getElementById(ids[i]);
                if (el) {
                    el.setAttribute('data-disable-bg-close', 'true');
                    el.setAttribute('data-disable-esc-close', 'true');
                }
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPanelExit);
        } else {
            initPanelExit();
        }

        // 핸들바를 탭하면 바텀시트를 닫는다 (템플릿에 닫기 버튼이 없다)
        $(document).on('click', '#quickInfoPanel .bottom-sheet-handlebar', function() {
            var panel = document.getElementById('quickInfoPanel');
            if (panel && typeof closePanel === 'function') {
                closePanel(panel);
            }
        });

        // 하단 '추가 정보 입력하기' 버튼: 닫은 바텀시트를 다시 연다
        $(document).on('click', '#btnReopenQuickInfo', function() {
            if (typeof window.openQuickInfoPanel === 'function') {
                window.openQuickInfoPanel();
            }
        });

        // 브라우저 뒤로가기: 1차는 열린 시트 닫기, 다시 누르면 가입을 취소하고 로그인 화면으로
        // 가입 대기 상태에서만 건다 - 이미 가입이 끝난 사용자의 뒤로가기는 막지 않는다.
        if (JOIN_PENDING_PANEL) {
            history.pushState(null, '', location.href);
            window.addEventListener('popstate', function() {
                var area = document.getElementById('changeArea');
                if (area && area.classList.contains('on')) {
                    if (typeof closePanel === 'function') closePanel(area);
                    history.pushState(null, '', location.href); // 소비한 항목 복구
                    return;
                }
                var panel = document.getElementById('quickInfoPanel');
                if (panel && panel.classList.contains('on')) {
                    if (typeof closePanel === 'function') closePanel(panel);
                    history.pushState(null, '', location.href);
                    return;
                }
                location.replace('/member/login');
            });
        }
    })();
</script>

<?= getCashJs("/resource/shop/mobile/js/member/join_quickinfo_bottom_sheet.js") ?>