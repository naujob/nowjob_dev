<?php
// Kakao Maps SDK 키 로드 (지오코딩 사용)
$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
// 공통 바텀시트 템플릿 로드
require_once __DIR__ . '/../_partial/bottom_sheet_template.php';
// 바텀시트 공통 함수(initPanelSwipe) 로드
include_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_helpers.php');
// 가입 완료 페이지 전용 바텀시트 로드
require_once __DIR__ . '/../_partial/join_quickinfo_bottom_sheet.php';

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

<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?= $kakao_key["인증키"] ?>&libraries=services"></script>

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
    <div class="sticky-bottom view-footer">
      <a href="/recruit/job_list" class="btn btn-primary">메인으로 바로가기</a>
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

  var JOIN_TYPE = "<?php echo $this->user->isLogin() ? $this->user->getJoinType() : ''; ?>";
  var IS_GOOGLE_JOIN = (["GOOGLE", "WECHAT"].indexOf(String(JOIN_TYPE || "").toUpperCase()) !== -1);
  // 위치 콜백을 “UI 텍스트만 갱신”할지, “자동 이력서 흐름”으로 이어갈지 구분.
  window.__quickInfoExpectLocation = false;
  // QuickInfo 적용하기 버튼으로 시작된 흐름인지 여부
  window.__quickInfoApplyFlow = false;
  // QuickInfo에서 선택된 값(국적/성별/나이) 캐시
  window.__quickInfoSelections = null;
  // 주소 팝업을 QuickInfo 수동 주소 선택 모드로 열었는지 여부
  window.__quickInfoManualAddrMode = false;
  //  수동 주소 선택 결과 캐시 (권한 없음 + 적용하기 시 사용).
  window.__quickInfoManualAddr = null;

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
    /**
     * QuickInfo 하단시트에서 선택된 국적, 성별, 나이 값을 객체로 반환한다.
     * 각 항목은 value와 이에 대응하는 한글 라벨로 치환되어 반환된다.
     * @returns {Object} { nation: "내국인"|"외국인"|"", gender: "남"|"여"|"", age: string }
     */
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

    // QuickInfo에서 고른 값(국적/성별/나이)**이 자동이력서 생성 흐름에 “주소 payload”와 별도로 존재하기 때문
    // QuickInfo 선택값(국적/성별/나이)을 자동이력서 payload에 합치는 함수
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

    // 주소 payload + QuickInfo 선택값을 합쳐 서버 자동완성 호출
    function submitQuickInfoWithAddress(addrPayload, selections) {
      // 주소/좌표 payload에 QuickInfo(국적/성별/나이) 합치기
      var payload = applyQuickInfoToPayload(Object.assign({}, addrPayload), selections);
      submitQuickInfoToServer(payload);
    }

    // 서버 자동완성 호출 (iOS/Android 공통)
    function submitQuickInfoToServer(payload) {
      // 서버 자동완성 중복 호출 방지
      if (window.__quickInfoSubmitting) return;
      window.__quickInfoSubmitting = true;

      if (typeof window.showAutoResumeLoading === 'function') {
        window.showAutoResumeLoading();
      }

      var params = Object.assign({}, payload || {});
      params.ru_name = params.ru_name || params.ru_name_seed_addr || "";
      params.ru_addr = params.ru_addr || params.ad_addr || params.ru_name_seed_addr || "";

      // 주소 필드는 배열 형태로 전달되도록 보정
      if (params.ad_addr && !Array.isArray(params.ad_addr)) {
        params.ad_addr = [params.ad_addr];
      }
      if (params.ad_jibun && !Array.isArray(params.ad_jibun)) {
        params.ad_jibun = [params.ad_jibun];
      }
      if (params.ad_doro && !Array.isArray(params.ad_doro)) {
        params.ad_doro = [params.ad_doro];
      }
      if (params.ad_zipcode && !Array.isArray(params.ad_zipcode)) {
        params.ad_zipcode = [params.ad_zipcode];
      }
      if (params.ad_detail && !Array.isArray(params.ad_detail)) {
        params.ad_detail = [params.ad_detail];
      }
      if (params.ad_gps_x && !Array.isArray(params.ad_gps_x)) {
        params.ad_gps_x = [params.ad_gps_x];
      }
      if (params.ad_gps_y && !Array.isArray(params.ad_gps_y)) {
        params.ad_gps_y = [params.ad_gps_y];
      }

      params.ad_type = "resume"; // st_address 테이블에 이력서 주소로 저장
      comAjax("/mypage_proc/resume_autofill_quick", params, function(ret) {
        window.__quickInfoSubmitting = false;
        if (typeof window.hideAutoResumeLoading === 'function') {
          window.hideAutoResumeLoading();
        }
        if (ret && ret.code === "00") {
          window.__fallbackResumeTriggered = true;
          try {
            localStorage.setItem('nowjob_resume_registered_bubble', '1');
            // 생성된 이력서 ID 저장 (말풍선 클릭 시 사용)
            if (ret.data && ret.data.ru_idx) {
              localStorage.setItem('nowjob_resume_registered_id', ret.data.ru_idx);
            }
          } catch (e) {}
          location.href = "/recruit/job_list";
        } else {
          commonAlert((ret && ret.msg) ? ret.msg : "자동이력서 생성에 실패했습니다.", function() {});
        }
      });
    }


    // - getQuickInfoSelections: DOM에서 국적/성별/나이 선택값을 읽음
    // - applyQuickInfoToPayload: 선택값을 주소 payload에 합침
    // - submitQuickInfoWithAddress: payload 저장 후 자동이력서 화면으로 이동
    window.getQuickInfoSelections = getQuickInfoSelections;
    window.applyQuickInfoToPayload = applyQuickInfoToPayload;
    window.submitQuickInfoWithAddress = submitQuickInfoWithAddress;
    window.submitQuickInfoToServer = submitQuickInfoToServer;

    // 위치 실패 시 안내 후 홈으로 이동
    window.proceedResumeAutoFlowWithoutLocation = function() {
      commonAlert("위치 정보를 가져오는 데 실패했습니다.", function() {
        location.href = "/home/index";
      });
    };
    /**
     * 1) QuickInfo 열릴 때 “위치 표시만” 하는 경우
        목적: 화면에 지역명만 표시
        동작: .quickinfo-region-text 업데이트만 하고 끝
        조건:
        __quickInfoExpectLocation = true
        __quickInfoApplyFlow = false
        2) QuickInfo에서 “적용하기” 눌러 자동이력서 생성하는 경우
        목적: 주소 + QuickInfo 선택값을 payload로 만들어 자동이력서로 이동
        동작:
        applyQuickInfoToPayload() → set_addr_info → /mypage/resume_write1
        조건:
        __quickInfoApplyFlow = true
     * 
     * 
     */
    // 네이티브 위치 콜백: 위도/경도 전달받아 주소 변환 및 자동이력서 흐름 연결
    window.returnLocation = function(lat, lng) {
      // iOS 권한 재개 플래그 저장(앱 권한 팝업 후 복귀 처리용)
      try {
        sessionStorage.setItem("ios_permission_resumed", "1");
      } catch (e) {}

      // 위치 권한이 없거나 거부된 경우
      if (!lat || !lng || lat === "0" || lng === "0") {
        // QuickInfo 위치 표시만 기다리는 상태라면 텍스트만 초기화
        if (window.__quickInfoExpectLocation && !window.__quickInfoApplyFlow) {
          window.__quickInfoExpectLocation = false;
          var regionEl = document.querySelector('.quickinfo-region-text');
          if (regionEl) regionEl.textContent = "근무 지역 선택";
          return;
        }
        // 자동이력서 흐름이라면 위치 없이 진행
        proceedResumeAutoFlowWithoutLocation();
        return;
      }

      // 좌표 -> 주소 변환 요청
      comAjax("/common_proc/get_gps_address", {
        x: lng,
        y: lat
      }, function(ret) {
        if (ret.code === "00" && ret.data) {
          var parts = String(ret.data).split("^");
          // 카카오 region_1~3depth_name 조합 (시도 시군구 읍면동, 지번/건물번호 없음 = 개인정보 보호 충족)
          var frontPart = (parts[0] || "").trim();

          // QuickInfo 위치 표시만 기다리는 상태라면 UI 텍스트만 갱신하고 종료 (화면 표시용으로만 시/도 축약)
          if (window.__quickInfoExpectLocation && !window.__quickInfoApplyFlow) {
            window.__quickInfoExpectLocation = false;
            var regionEl = document.querySelector('.quickinfo-region-text');
            if (regionEl) {
              var displayPart = frontPart;
              if (typeof fRemoveBigAdrJS === 'function') {
                displayPart = fRemoveBigAdrJS(frontPart) || frontPart;
              }
              regionEl.textContent = displayPart || "근무 지역 선택";
            }
            return;
          }
          // 자동이력서용 주소 필드 구성 (ad_addr만 축약, ad_jibun/ru_name_seed_addr는 원본 유지)
          var ad_jibun = frontPart;
          var ad_doro = "";
          var ad_zipcode = "";
          var ad_addr_short = (typeof fRemoveBigAdrJS === 'function') ? (fRemoveBigAdrJS(frontPart) || frontPart) : frontPart;

          var payload = {
            ru_name_seed_addr: frontPart,
            ad_addr: ad_addr_short,
            ad_jibun: ad_jibun,
            ad_doro: ad_doro,
            ad_zipcode: ad_zipcode,
            ad_gps_x: lng,
            ad_gps_y: lat
          };
          // QuickInfo에서 넘어온 경우 국적/성별/나이도 payload에 합침
          if (window.__quickInfoApplyFlow) {
            applyQuickInfoToPayload(payload);
          }

          // 자동이력서 생성 (RESUME 주소만 저장)
          submitQuickInfoToServer(payload);
        } else {
          // 주소 변환 실패 시 위치 없이 진행
          proceedResumeAutoFlowWithoutLocation();
        }
      });
    };

  })();

  window.addEventListener('DOMContentLoaded', function() {
    // 가입 완료 페이지 진입 시 SNS 임시 키 정리 (iOS WebView 안정화)
    try {
      localStorage.removeItem("join_sns_type");
      localStorage.removeItem("join_sns_key");
      sessionStorage.removeItem("join_sns_type");
      sessionStorage.removeItem("join_sns_key");
      document.cookie = "join_sns_type=; path=/; max-age=0";
      document.cookie = "join_sns_key=; path=/; max-age=0";
    } catch (e) {}

    var isLogin = <?php echo $this->user->isLogin() ? 'true' : 'false'; ?>;
    var isMem = <?php echo (isset($mm_type) && $mm_type === "MEM") ? 'true' : 'false'; ?>;
    var hasOpenPanel = (typeof window.openPanel === 'function');
    var DEBUG_PREFIX = '[join_mem_finish][bottomsheet]';

    if (!isLogin || !isMem) return;

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
      // 자동 트리거/이동 중복 방지
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
        tryTrigger();
      };
      document.addEventListener('touchstart', retryOnGesture, {
        once: true,
        passive: true
      });
      document.addEventListener('mousedown', retryOnGesture, {
        once: true,
        passive: true
      });
    }

    function setQuickInfoRegionText(text) {
      var el = document.querySelector('.quickinfo-region-text');
      if (el) el.textContent = text;
    }

    function requestQuickInfoLocation() {
      // Android WebView 브릿지
      if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK && typeof APP_NOWAPK.getLocation === 'function') {
        APP_NOWAPK.getLocation();
        return;
      }
      // iOS WebView 브릿지 (startGettingLocation)
      if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.startGettingLocation) {
        window.webkit.messageHandlers.startGettingLocation.postMessage({});
        return;
      }
      // iOS WebView 브릿지 (getLocation)
      if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.getLocation) {
        window.webkit.messageHandlers.getLocation.postMessage({});
        return;
      }
    }

    // Google 가입자 전용 QuickInfo 오픈 흐름
    // hasLocationConsent: 위치권한 동의 여부(granted면 true)
    function openQuickInfoForGoogle(hasLocationConsent) {
      // QuickInfo 바텀시트 열기
      if (typeof window.openQuickInfoPanel === 'function') {
        window.openQuickInfoPanel();
      }
      // 위치권한이 있으면 네이티브 위치 요청, 없으면 수동 선택 안내
      if (hasLocationConsent) {
        // 지역 텍스트를 로딩 상태로 표시
        setQuickInfoRegionText("위치 확인 중...");
        // 지금은 위치 콜백을 받아서 QuickInfo UI 텍스트만 갱신하려는 상태다”**라는 의미를 나타내는 플래그
        window.__quickInfoExpectLocation = true;
        // 적용하기 흐름이 아님(위치 수집 단계) 
        // returnLocation에서 단순 UI 표시 vs 자동이력서 이동을 구분 하기 위해 필요
        window.__quickInfoApplyFlow = false;
        // 네이티브 위치 요청
        requestQuickInfoLocation();
      } else {
        // 권한 미동의 시 수동 선택 유도
        setQuickInfoRegionText("근무 지역 선택");
      }
    }

    function openGuidePanelByPermission() {
      var storedPermission = (typeof window.getLocationPermissionStatus === 'function') ?
        window.getLocationPermissionStatus() :
        null;
      var hasLocationConsent = storedPermission === 'granted';

      if (IS_GOOGLE_JOIN) {
        openQuickInfoForGoogle(hasLocationConsent);
        return;
      }

      if (hasLocationConsent) {
        startAutoResumeFlowOnce();
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

      // 자동이력서 작성 화면으로 넘길 주소/좌표 payload
      var autoPayload = {
        ru_name_seed_addr: addr,
        ad_type: "resume",
        ad_addr: addr,
        ad_jibun: addr,
        ad_doro: "",
        ad_zipcode: "",
        ad_detail: "",
        ad_gps_x: result[0].x,
        ad_gps_y: result[0].y
      };
      // QuickInfo 수동 주소 선택 모드라면:
      // - 자동이력서로 즉시 이동하지 않고
      // - 선택한 주소만 캐시 + UI 갱신 후 종료
      if (window.__quickInfoManualAddrMode) {
        // 1회성 모드 해제
        window.__quickInfoManualAddrMode = false;

        // 수동 주소 선택 결과를 캐시(적용하기 클릭 시 사용)
        window.__quickInfoManualAddr = autoPayload;
        var regionEl = document.querySelector('.quickinfo-region-text');
        if (regionEl) {
          // 선택된 주소를 화면에 반영(간단 정리)
          regionEl.textContent = (typeof fRemoveBigAdrJS === 'function' ? (fRemoveBigAdrJS(addr) || addr) : addr);
        }
        // 여기서 종료(자동이력서 흐름 진행하지 않음)
        return;
      }

      // QuickInfo "적용하기" 흐름이라면
      // - 선택한 주소/좌표 + QuickInfo 선택값을 합쳐
      // - 자동이력서 작성 화면으로 이동
      if (window.__quickInfoApplyFlow) {
        submitQuickInfoWithAddress(autoPayload);
        return;
      }
      if (typeof window.submitQuickInfoToServer === 'function') {
        window.submitQuickInfoToServer(autoPayload);
      }
    });

    return true;
  };

  window.handleQuickInfoApplyClick = function() {
    var selections = getQuickInfoSelections();
    var regionEl = document.querySelector('.quickinfo-region-text');
    var regionText = regionEl ? regionEl.textContent.trim() : "";

    if (!regionText || regionText === "근무 지역 선택" || regionText === "위치 확인 중...") {
      commonAlert("근무 지역을 선택해주세요.", function() {
        $('.quickinfo-region-field').trigger('click');
      });
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
      commonAlert("나이를 선택해주세요.", function() {});
      return;
    }

    // QuickInfo 선택값 캐시
    window.__quickInfoSelections = selections;
    // 자동이력서로 넘어가는 모드 플래그 / returnLocation 콜백을 상황별로 다르게 처리하기 위한 
    window.__quickInfoApplyFlow = true;
    // 위치 콜백 대기 안 함 / returnLocation 콜백을 상황별로 다르게 처리하기 위한 
    window.__quickInfoExpectLocation = false;

    var storedPermission = (typeof window.getLocationPermissionStatus === 'function') ?
      window.getLocationPermissionStatus() :
      null;
    var hasLocationConsent = storedPermission === 'granted';

    if (!hasLocationConsent) {
      if (window.__quickInfoManualAddr) {
        submitQuickInfoWithAddress(window.__quickInfoManualAddr, selections);
        return;
      }
      $('.quickinfo-region-field').trigger('click');
      return;
    }

    $("#btnResumeGuide").trigger("click");
  };

  $(document).on('click', '#btnQuickInfoApply', function(e) {
    e.preventDefault();
    if (typeof window.handleQuickInfoApplyClick === 'function') {
      window.handleQuickInfoApplyClick();
    }
  });

  // QuickInfo 초기화 버튼: 기본값(외국인/남성/30대)으로 복원, 주소는 초기화
  $(document).on('click', '#btnQuickInfoReset', function(e) {
    e.preventDefault();

    // 기본 선택값 복원
    $('input[name="quickinfo_nation"][value="foreign"]').prop('checked', true).trigger('change');
    $('input[name="quickinfo_gender"][value="male"]').prop('checked', true).trigger('change');
    $('input[name="quickinfo_age"][value="30s"]').prop('checked', true).trigger('change');

    // 지역 텍스트 초기화
    var regionEl = document.querySelector('.quickinfo-region-text');
    if (regionEl) regionEl.textContent = "근무 지역 선택";

    // QuickInfo 캐시/상태 초기화
    window.__quickInfoSelections = null;
    window.__quickInfoManualAddrMode = false;
    window.__quickInfoManualAddr = null;
    window.__quickInfoExpectLocation = false;
  });
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

<script>
// join_mem_finish: 페이지 이탈 시 폴백 자동이력서 생성 (모바일)
(function() {
    var isLogin = <?php echo $this->user->isLogin() ? 'true' : 'false'; ?>;
    var isMem = <?php echo (isset($mm_type) && $mm_type === "MEM") ? 'true' : 'false'; ?>;
    if (!isLogin || !isMem) return;

    var DEFAULT_ADDR = "서울특별시 강남구 개포동";
    var DEFAULT_GPS_X = "127.0748";
    var DEFAULT_GPS_Y = "37.4855";
    var DEFAULT_ZIPCODE = "06336";

    // BFCache 대응: 명시적 false 초기화 필수
    window.__fallbackResumeTriggered = false;

    // 페이지 로드 시 이력서 수 사전 조회 ($.ajax 직접 사용: comAjax의 ajax_first_num 카운터 충돌 방지)
    var __cachedResumeCount = null;
    $.ajax({
        type: "POST",
        url: "/mypage_proc/getResumeCount",
        success: function(ret) {
            if (typeof ret === 'string') { try { ret = JSON.parse(ret); } catch(e) {} }
            if (ret && ret.code === "00") {
                __cachedResumeCount = parseInt(ret.data.count, 10) || 0;
            }
        },
        error: function() {}
    });

    function canTriggerFallback() {
        if (window.__fallbackResumeTriggered) return false;
        if (window.__quickInfoSubmitting) return false;
        if (__cachedResumeCount !== null && __cachedResumeCount > 0) return false;
        return true;
    }

    function buildFallbackFormData() {
        var fd = new FormData();
        fd.append("ru_name", "");
        fd.append("ru_addr", DEFAULT_ADDR);
        fd.append("ru_nationality", "내국인");
        fd.append("quickinfo_gender", "남");
        fd.append("quickinfo_age", "30s");
        fd.append("ad_type", "resume");
        fd.append("ad_addr[]", DEFAULT_ADDR);
        fd.append("ad_jibun[]", DEFAULT_ADDR);
        fd.append("ad_doro[]", "");
        fd.append("ad_zipcode[]", DEFAULT_ZIPCODE);
        fd.append("ad_detail[]", "");
        fd.append("ad_gps_x[]", DEFAULT_GPS_X);
        fd.append("ad_gps_y[]", DEFAULT_GPS_Y);
        return fd;
    }

    // sendBeacon으로 언로드 중에도 전송 보장 (comAjax는 취소됨)
    function triggerFallbackResume() {
        if (!canTriggerFallback()) return;
        window.__fallbackResumeTriggered = true;
        var url = "/mypage_proc/resume_autofill_quick";
        var fd = buildFallbackFormData();
        if (typeof navigator.sendBeacon === 'function') {
            navigator.sendBeacon(url, fd);
            return;
        }
        if (typeof fetch === 'function') {
            try { fetch(url, { method: 'POST', body: fd, keepalive: true }); } catch(e) {}
            return;
        }
        try {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', url, false);
            xhr.send(fd);
        } catch(e) {}
    }

    // 네이티브 앱 백그라운드 진입 콜백 (Android onPause / iOS sceneDidEnterBackground에서 호출)
    window.onAppBackground = function() {
        triggerFallbackResume();
    };

    // Android WebView 홈 버튼 백그라운드 전환 감지 (브라우저 환경 보조)
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'hidden') { triggerFallbackResume(); }
    });
    // 링크 이동/뒤로가기 감지
    window.addEventListener('pagehide', function() { triggerFallbackResume(); });

})();
</script>

<?= getCashJs(RES_PATH . "/js/member/join_quickinfo_bottom_sheet.js") ?>