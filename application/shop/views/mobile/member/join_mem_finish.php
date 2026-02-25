<?php
// Kakao Maps SDK 키 로드 (지오코딩 사용)
$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
// 공통 바텀시트 템플릿 로드
require_once __DIR__ . '/../_partial/bottom_sheet_template.php';
// 바텀시트 공통 함수(initPanelSwipe) 로드
include_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_helpers.php');
// 가입 완료 페이지 전용 바텀시트 로드 (임시 비활성화)
// require_once __DIR__ . '/../_partial/join_quickinfo_bottom_sheet.php';

// 이력서 미작성 회원 안내 바텀시트 (로그인 + 이력서 없음 조건에서 노출)
renderBottomSheet(
  'resumeGuidePanel',
  '5초 간편 이력서 등록',
  '<p class="resume-guide-desc">나우잡에서 지금 계신 곳 근처의 채용공고를 찾아드립니다!</p>',
  '내 주변 일자리 찾기',
  'btnResumeGuide',
  '',
  'bottom-sheet-content-container',
  'bottom-sheet-content-body'
);

// 위치 동의 안내 바텀시트 (권한 미동의 시 노출)
renderSelectBottomSheet(
  'locationConsentPanel',
  '내 주변 일자리를 놓칠수 있어요!',
  '<p class="location-consent-desc">나우잡에서 공고를 찾아드릴수있게<br>정확한 위치 설정이 필요합니다</p>',
  '주소 직접입력 하기',
  'btnManualAddress',
  '',
  '내 주변 일자리 찾기!',
  'btnResumeGuideLocation',
  '',
  'location-consent-bottom-sheet-container'
);
?>

<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>&libraries=services"></script>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>

<div id="auto-resume-loading" aria-hidden="true">
  <div class="loading-card" role="status" aria-live="polite">
    <p class="loading-title">맞춤형 공고 나우잡이 찾아드립니다.</p>
    <p class="loading-desc">딱 맞는 공고만 선별중입니다. 잠시만 기다려주세요.</p>
    <div class="loading-spinner" aria-hidden="true"></div>
  </div>
</div>

<div class="page page-join member-type1">
     <div class="top-bar">
         <!--a href="#" class="btn-back"><span class="sr-text">이전으로</span></a-->
         <h1 class="title">개인회원 회원가입</h1>
     </div><!-- //top-bar -->
     <div class="page-content">

         <div class="join-welcome">
             <div class="container">
                 <h2 class="primary-title">가입을 축하드립니다</h2>
                 <p>나우잡이 제공하는 채용정보 및 다양한 혜택을<br> 이용하실 수 있습니다.</p>
             </div>
         </div>
         <div class="form-action-wrap" id="non_mem_last_cont" style="text-align: center;display:none;">
             <!-- <a href="/mypage/resume_list" class="btn-round btn-tertiary" style="">이력서 등록하러 가기</a> -->
         </div>
         <div class="sticky-bottom">
             <a href="/" class="btn btn-primary">메인으로 바로가기</a>
         </div>
     </div><!-- //page-content -->
</div>
 <script>
     if (getCookie("non_mem_last_cont") != '') {
         var msg = '';
         //	msg = '<a href="'+getCookie("non_mem_last_cont")+'" class="btn-round btn-tertiary" style="margin-bottom: 20px; padding: 0.625rem 1.125rem;">채용공고 계속보기</a><br />';
         $('#non_mem_last_cont').prepend(msg);
         $('#non_mem_last_cont').show();
         setCookie('non_mem_last_cont', '', 1);
     }

 </script>

<script>
  (function() {
    var LOCATION_PERMISSION_KEY = "nowjob_location_permission_status";

    function normalizePermissionStatus(value) {
      if (value === true || value === "true" || value === "granted" || value === 1 || value === "1") {
        return "granted";
      }
      if (value === false || value === "false" || value === "denied" || value === 0 || value === "0" || value === "never_ask_again") {
        return value === "never_ask_again" ? "never_ask_again" : "denied";
      }
      return null;
    }

    function setStoredPermissionStatus(status) {
      try {
        if (status) {
          localStorage.setItem(LOCATION_PERMISSION_KEY, status);
        }
      } catch (e) {}
    }

    function getStoredPermissionStatus() {
      try {
        return localStorage.getItem(LOCATION_PERMISSION_KEY);
      } catch (e) {
        return null;
      }
    }

    // 네이티브가 호출할 콜백 함수
    window.onLocationPermissionResult = function(status) {
      var normalized = normalizePermissionStatus(status);
      if (!normalized) return;
      setStoredPermissionStatus(normalized);
      try {
        sessionStorage.setItem("ios_permission_resumed", "1");
      } catch (e) {}
    };

    // 페이지 초기화 시 전역으로 노출 (DOM 로드 전에도 접근 가능)
    // 위치 권한 동의 여부 값 가져오기
    window.getLocationPermissionStatus = getStoredPermissionStatus;
  })();

  window.showAutoResumeLoading = function() {
    var el = document.getElementById('auto-resume-loading');
    if (!el) return;
    el.style.display = 'flex';
    el.setAttribute('aria-hidden', 'false');
  };

  window.hideAutoResumeLoading = function() {
    var el = document.getElementById('auto-resume-loading');
    if (!el) return;
    el.style.display = 'none';
    el.setAttribute('aria-hidden', 'true');
  };

  (function() {
    var RESUME_AUTOFILL_KEY = "resume_autofill_payload";
    var RESUME_AUTOFILL_TS_KEY = "resume_autofill_ts";
    var RESUME_AUTOFILL_MAX_AGE = 5 * 60 * 1000;

    function setAutofillPayload(payload) {
      try {
        localStorage.setItem(RESUME_AUTOFILL_KEY, JSON.stringify(payload || {}));
        localStorage.setItem(RESUME_AUTOFILL_TS_KEY, String(Date.now()));
      } catch (e) {
        // noop
      }
    }

    function navigateToResumeWrite1(payload) {
      if (window.__resumeAutoNavigated) return;
      window.__resumeAutoNavigated = true;
      setAutofillPayload(payload);
      location.href = "/mypage/resume_write1?autofill=1";
    }

    window.proceedResumeAutoFlowWithoutLocation = function() {
      commonAlert("위치 정보를 가져오는 데 실패했습니다.", function() {
        location.href = "/home/index";
      });
    };

    window.returnLocation = function(lat, lng) {
      try {
        sessionStorage.setItem("ios_permission_resumed", "1");
      } catch (e) {}

      // 위치 권한이 없거나 거부된 경우
      if (!lat || !lng || lat === "0" || lng === "0") {
        proceedResumeAutoFlowWithoutLocation();
        return;
      }

      comAjax("/common_proc/get_gps_address", {
        x: lng,
        y: lat
      }, function(ret) {
        if (ret.code === "00" && ret.data) {
          var parts = String(ret.data).split("^");
          // 개인정보 보호: 시/구/동까지만 사용 + 주소 정리
          var frontPart = (parts[0] || "").trim();
          if (typeof fRemoveBigAdrJS === 'function') {
            frontPart = fRemoveBigAdrJS(frontPart) || frontPart;
          }
          var ad_jibun = frontPart;
          var ad_doro = "";
          var ad_zipcode = "";

          var payload = {
            ru_name_seed_addr: frontPart,
            ad_addr: ad_jibun,
            ad_jibun: ad_jibun,
            ad_doro: ad_doro,
            ad_zipcode: ad_zipcode,
            ad_gps_x: lng,
            ad_gps_y: lat
          };

          comAjax("/common_proc/set_addr_info", {
            ad_zipcode: ad_zipcode,
            ad_jibun: ad_jibun,
            ad_doro: ad_doro,
            ad_addr: payload.ad_addr,
            ad_detail: "",
            ad_gps_x: lng,
            ad_gps_y: lat
          }, function() {
            navigateToResumeWrite1(payload);
          });
        } else {
          proceedResumeAutoFlowWithoutLocation();
        }
      });
    };

    // 오래된 자동작성 payload 정리
    try {
      var ts = parseInt(localStorage.getItem(RESUME_AUTOFILL_TS_KEY), 10) || 0;
      if (ts && Date.now() - ts > RESUME_AUTOFILL_MAX_AGE) {
        localStorage.removeItem(RESUME_AUTOFILL_KEY);
        localStorage.removeItem(RESUME_AUTOFILL_TS_KEY);
      }
    } catch (e) {}
  })();

  window.addEventListener('DOMContentLoaded', function() {
    var isLogin = <?php echo $this->user->isLogin() ? 'true' : 'false'; ?>;
    var isMem = <?php echo (isset($mm_type) && $mm_type === "MEM") ? 'true' : 'false'; ?>;
    var hasOpenPanel = (typeof window.openPanel === 'function');
    var DEBUG_PREFIX = '[join_mem_finish][bottomsheet]';
    var RESUME_AUTO_ONCE_KEY = "resume_auto_once_started";

    if (!isLogin || !isMem) return;

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

    function isLocationBridgeReady() {
      return (
        (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK && typeof APP_NOWAPK.getLocation === 'function') ||
        (window.webkit && window.webkit.messageHandlers && (window.webkit.messageHandlers.startGettingLocation || window.webkit.messageHandlers.getLocation))
      );
    }

    function waitForLocationBridgeReady(timeoutMs, intervalMs, onReady, onTimeout) {
      var startedAt = Date.now();
      (function checkReady() {
        if (isLocationBridgeReady()) {
          onReady();
          return;
        }
        if (Date.now() - startedAt >= timeoutMs) {
          onTimeout();
          return;
        }
        setTimeout(checkReady, intervalMs);
      })();
    }

    function startAutoResumeFlowOnce() {
      if (window.__resumeAutoStartTriggered) return;
      window.__resumeAutoStartTriggered = true;

      var tryTrigger = function() {
        $("#btnResumeGuide").trigger("click");
      };

      // 즉시 시도 + 브릿지 준비 대기 후 1회 재시도
      if (isLocationBridgeReady()) {
        tryTrigger();
      } else {
        waitForLocationBridgeReady(1500, 100, tryTrigger, function() {});
      }

      // 사용자 제스처가 필요한 경우를 대비한 1회 재시도
      var retryOnGesture = function() {
        if (window.__resumeAutoNavigated) return;
        tryTrigger();
      };
      document.addEventListener('touchstart', retryOnGesture, { once: true, passive: true });
      document.addEventListener('mousedown', retryOnGesture, { once: true, passive: true });
    }

    function openGuidePanelByPermission() {
      var storedPermission = (typeof window.getLocationPermissionStatus === 'function')
        ? window.getLocationPermissionStatus()
        : null;
      var hasLocationConsent = storedPermission === 'granted';

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
            if (hasLocationConsent && !hasAutoStartedOnce()) {
              markAutoStartedOnce();
              startAutoResumeFlowOnce();
              return;
            }
            if (hasLocationConsent) {
              return;
            }
            var targetPanelId = hasLocationConsent ? 'resumeGuidePanel' : 'locationConsentPanel';
            var panel = document.getElementById(targetPanelId);
            if (!panel) {
              return;
            }
            if (typeof openPanel !== 'function') {
              return;
            }

            openPanel(panel);
          } else {
          }
        }
        ,error: function(xhr, status, error) {
        }
      });
    }

    openGuidePanelByPermission();

    // iOS: 설정으로 이동 후 복귀 시 권한 상태 재확인
    window.addEventListener('pageshow', function() {
      openGuidePanelByPermission();
    });

    document.addEventListener('visibilitychange', function() {
      if (document.visibilityState === 'visible') {
        openGuidePanelByPermission();
      }
    });
  });

  $(document).on('click', '#btnResumeGuideLocation', function() {
    if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK && typeof APP_NOWAPK.requestLocationPermission === 'function') {
      APP_NOWAPK.requestLocationPermission();
      return;
    }
    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.requestLocationPermission) {
      window.webkit.messageHandlers.requestLocationPermission.postMessage({});
    }
  });

  $(document).on('click', '#btnResumeGuide', function() {
    // 새 이력서 작성을 위해 임시저장 데이터 초기화
    localStorage.removeItem("resume_draft_0_step1");
    localStorage.removeItem("resume_draft_0_step2");
    localStorage.removeItem("resume_draft_0_step3");
    localStorage.removeItem("resume_draft_0_step4");
    localStorage.removeItem("resume_draft_0_step5");

    if (typeof window.showAutoResumeLoading === 'function') {
      window.showAutoResumeLoading();
    }
    // 중복 이동 방지용 플래그
    window.__resumeAutoNavigated = false;

    if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK && typeof APP_NOWAPK.getLocation === 'function') {
      APP_NOWAPK.getLocation();
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.startGettingLocation) {
      window.webkit.messageHandlers.startGettingLocation.postMessage({});
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.getLocation) {
      window.webkit.messageHandlers.getLocation.postMessage({});
    } else {
      proceedResumeAutoFlowWithoutLocation();
    }
  });

  $(document).on('click', '#btnManualAddress', function() {
    window.__hideAllRegionsInAddrPopup = true;
    if (typeof window.applyAddrPopupAllRegionFilter === 'function') {
      window.applyAddrPopupAllRegionFilter();
    }
    $("#changeArea").addClass("on");
  });

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

      var autoPayload = {
        ru_name_seed_addr: addr,
        ad_addr: addr,
        ad_jibun: addr,
        ad_doro: "",
        ad_zipcode: "",
        ad_detail: "",
        ad_gps_x: result[0].x,
        ad_gps_y: result[0].y
      };

      try {
        localStorage.setItem("resume_autofill_payload", JSON.stringify(autoPayload));
        localStorage.setItem("resume_autofill_ts", String(Date.now()));
      } catch (e) {}

      if (typeof window.showAutoResumeLoading === 'function') {
        window.showAutoResumeLoading();
      }
      window.__resumeAutoNavigated = false;
      location.href = "/mypage/resume_write1?autofill=1";
    });

    return true;
  };
</script>

<?php // <?= getCashJs(RES_PATH . "/js/member/join_quickinfo_bottom_sheet.js") ?> ?>
