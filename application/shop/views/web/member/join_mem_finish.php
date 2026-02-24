<?php
// Kakao Maps SDK 키 로드 (지오코딩 사용)
$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
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
    var RESUME_AUTO_ONCE_KEY = "web_resume_auto_once_started";
    var isLogin = <?= $this->user->isLogin() ? 'true' : 'false' ?>;
    var isMem = <?= ($this->user->isLogin() && $this->user->getUserType() === 'MEM') ? 'true' : 'false' ?>;

    if (!isLogin || !isMem) {
        return;
    }

    function getStoredPermissionStatus() {
        try {
            return localStorage.getItem(LOCATION_PERMISSION_KEY);
        } catch (e) {
            return null;
        }
    }

    function hasAutoStartedOnce() {
        try {
            return localStorage.getItem(RESUME_AUTO_ONCE_KEY) === "1";
        } catch (e) {
            return false;
        }
    }

    function markAutoStartedOnce() {
        try {
            localStorage.setItem(RESUME_AUTO_ONCE_KEY, "1");
        } catch (e) {}
    }

    function navigateToResumeWrite(addrPayload) {
        // Deprecated: 웹 자동 작성은 페이지 이동 없이 처리
        autoSaveResume(addrPayload);
    }

    function openManualAddressPopup() {
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

    function getRegionName(rawAddr) {
        var addr = String(rawAddr || "").trim();
        if (!addr) return "우리 동네";
        var parts = addr.split(/\s+/);
        var last = parts[parts.length - 1];
        if (/[동면읍리]$/.test(last)) return last;
        if (parts.length > 1) return parts[parts.length - 2];
        return last;
    }

    function buildResumeTitle(rawAddr) {
        var regionName = getRegionName(rawAddr);
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
        var chosen = titleTemplates[Math.floor(Math.random() * titleTemplates.length)];
        return chosen.replace("[지역명]", regionName);
    }

    function safeCommonAlert(msg, callback) {
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
    window.onChangeAreaApply = function(payload) {
        var areaList = (payload && Array.isArray(payload.searchArea)) ? payload.searchArea : [];
        if (areaList.length === 0 || !areaList[0] || !areaList[0].addr) {
            alert("주소를 선택해 주세요.");
            return true;
        }

        var addr = String(areaList[0].addr || "").trim();
        if (!addr) {
            alert("주소를 선택해 주세요.");
            return true;
        }

        var geocoderAvailable = (window.kakao && kakao.maps && kakao.maps.services && kakao.maps.services.Geocoder);
        if (!geocoderAvailable) {
            alert("주소 좌표 변환에 실패했습니다. 다시 시도해 주세요.");
            location.reload();
            return true;
        }

        var geocoder = new kakao.maps.services.Geocoder();
        geocoder.addressSearch(addr, function(result, status) {
            if (status !== kakao.maps.services.Status.OK || !result || !result[0] || !result[0].x || !result[0].y) {
                alert("주소 좌표 변환에 실패했습니다. 다시 시도해 주세요.");
                location.reload();
                return;
            }

            var addrPayload = {
                ru_name_seed_addr: addr,
                ad_zipcode: "",
                ad_jibun: addr,
                ad_doro: "",
                ad_addr: addr,
                ad_detail: "",
                ad_gps_x: result[0].x,
                ad_gps_y: result[0].y
            };

            autoSaveResume(addrPayload);
        });

        return true;
    };

    function requestLocationAndStart() {
        if (!navigator.geolocation) {
            return;
        }

        var completed = false;
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

    function startAutoResumeFlowIfNeeded() {
        var stored = getStoredPermissionStatus();
        if (hasAutoStartedOnce()) return;

        $.ajax({
            url: '/mypage_proc/getResumeCount',
            type: 'GET',
            dataType: 'json',
            success: function(ret) {
                var count = 0;
                if (ret && ret.data && typeof ret.data.count !== 'undefined') {
                    count = parseInt(ret.data.count, 10) || 0;
                }
                if (count <= 0) {
                    if (stored === "granted") {
                        markAutoStartedOnce();
                        requestLocationAndStart();
                    } else {
                        openManualAddressPopup();
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', startAutoResumeFlowIfNeeded);
 
})();
</script>
