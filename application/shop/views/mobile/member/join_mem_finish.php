<?php
// 가입 대기 상태 여부 (SNS 가입: 계정이 아직 생성되지 않음 → '적용하기'에서 생성)
$join_pending = (isset($join_pending) && $join_pending === "Y") ? "Y" : "N";

// Kakao Maps SDK 키 로드 (지오코딩 사용)
$kakao_key = $this->setting_model->getSettingInfo(["st_group" => "카카오", "st_key" => "인증키"]);
// 공통 바텀시트 템플릿 로드
require_once __DIR__ . '/../_partial/bottom_sheet_template.php';
// 바텀시트 공통 함수(initPanelSwipe) 로드
include_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_helpers.php');
// 가입 완료 페이지 전용 바텀시트 로드
require_once __DIR__ . '/../_partial/join_quickinfo_bottom_sheet.php';

// 비SNS 전용이던 resumeGuidePanel / locationConsentPanel은 제거되었다.
// 이제 SNS/비SNS 모두 QuickInfo 바텀시트 하나로 통일된다.
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
<?php
// 가입 대기 상태에서는 가입을 취소하고 로그인 화면으로 돌아갈 수 있어야 한다
// back_type='replace' → 히스토리 항목을 교체하므로 뒤로가기로 이 페이지에 되돌아오지 않는다
$top_bar = array(
  'title'     => '개인회원 회원가입',
  'back_type' => ($join_pending === 'Y') ? 'replace' : 'none',
  'back_href' => '/member/login'
);
include(get_base_url() . '/application/shop/views/mobile/_partial/top_bar.php');
?>
  <div class="page-content">

    <div class="join-welcome">
      <div class="container">
        <?php if ($join_pending === 'Y'): ?>
        <h2 class="primary-title">거의 다 왔어요!</h2>
        <p>추가 정보를 입력하시면<br> 회원가입이 완료됩니다.</p>
        <?php else: ?>
        <h2 class="primary-title">가입을 축하드립니다</h2>
        <p>나우잡이 제공하는 채용정보 및 다양한 혜택을<br> 이용하실 수 있습니다.</p>
        <?php endif; ?>
      </div>
    </div>
    <div class="form-action-wrap" id="non_mem_last_cont" style="text-align: center;display:none;">
      <!-- <a href="/mypage/resume_list" class="btn-round btn-tertiary" style="">이력서 등록하러 가기</a> -->
    </div>
    <div class="sticky-bottom view-footer">
      <?php if ($join_pending === 'Y'): ?>
      <!-- 바텀시트를 닫았을 때 다시 열기 위한 버튼 (가입 완료 전이므로 메인 이동 버튼은 노출하지 않는다) -->
      <button type="button" id="btnReopenQuickInfo" class="btn btn-primary">추가 정보 입력하기</button>
      <?php else: ?>
      <a href="/recruit/job_list" class="btn btn-primary">메인으로 바로가기</a>
      <?php endif; ?>
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

  var JOIN_TYPE = "<?= isset($join_type) ? $join_type : '' ?>";
  // 가입 대기 상태: 계정이 아직 없고 '적용하기' 시점에 생성한다
  var JOIN_PENDING = ("<?= $join_pending ?>" === "Y");
  // join_complete_proc에 넘길 SNS 식별값 (쿠키 폴백보다 서버 값이 정확하다)
  var JOIN_KEY = "<?= isset($join_key) ? $join_key : '' ?>";
  var JOIN_UUID = "<?= isset($uuid) ? $uuid : '' ?>";
  // QuickInfo 바텀시트는 SNS/비SNS 구분 없이 항상 노출한다 (가입 완료의 유일한 진행길)
  var SHOW_QUICKINFO = true;
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
  // GPS로 받아온 주소 캐시 (폴백 자동이력서에서 사용)
  window.__quickInfoGpsAddr = null;
  // 자동이력서 제출 흐름(#btnResumeGuide)으로 위치를 요청했는지 여부.
  // 위치 콜백이 "화면 표시용"인지 "제출용"인지 구분하는 데 쓴다.
  window.__resumeAutoFlow = false;

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
     * QuickInfo 하단시트에서 선택된 국적, 성별, 나이, 업종 값을 객체로 반환한다.
     * 국적/성별은 value와 이에 대응하는 한글 라벨로 치환되어 반환된다.
     * @returns {Object} { nation: "내국인"|"외국인"|"", gender: "남"|"여"|"", age: string, work: string[] }
     */
    function getQuickInfoSelections() {
      var nationVal = $('input[name="quickinfo_nation"]:checked').val() || "";
      var genderVal = $('input[name="quickinfo_gender"]:checked').val() || "";
      var ageVal = $('input[name="quickinfo_age"]:checked').val() || "";

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
        age: ageVal,
        work: workVals
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
      // 선택한 업종을 이력서 희망업무(ru_work)로 전달. 없으면 서버 기본값이 적용된다
      if (sel.work && sel.work.length > 0) {
        payload.ru_work = sel.work;
      }
      return payload;
    }

    // 주소 payload + QuickInfo 선택값을 합쳐 서버 자동완성 호출
    function submitQuickInfoWithAddress(addrPayload, selections) {
      // 주소/좌표 payload에 QuickInfo(국적/성별/나이) 합치기
      var payload = applyQuickInfoToPayload(Object.assign({}, addrPayload), selections);
      submitQuickInfoToServer(payload);
    }

    // SNS 임시 키 정리 (가입 완료 후에만 호출한다 - 그 전에 지우면 쿠키 폴백까지 끊긴다)
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
    window.clearJoinSnsKeys = clearJoinSnsKeys;

    // 서버 자동완성 호출 (iOS/Android 공통)
    // 가입 대기(SNS) 상태면 계정 생성 -> 자동이력서 순으로 2단계 처리한다.
    // mypage_proc는 로그인 세션을 요구하므로 계정 생성이 반드시 선행되어야 한다.
    function submitQuickInfoToServer(payload) {
      // 서버 자동완성 중복 호출 방지
      if (window.__quickInfoSubmitting) return;
      window.__quickInfoSubmitting = true;

      if (typeof window.showAutoResumeLoading === 'function') {
        window.showAutoResumeLoading();
      }

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
          if (typeof window.hideAutoResumeLoading === 'function') {
            window.hideAutoResumeLoading();
          }
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
      var params = Object.assign({}, payload || {});
      params.ru_name = params.ru_name || params.ru_name_seed_addr || "";
      params.ru_addr = params.ru_addr || params.ad_addr || params.ru_name_seed_addr || "";

      // 희망업무는 서버가 $_POST["ru_work"]를 배열로 받으므로 배열로 보정
      if (params.ru_work && !Array.isArray(params.ru_work)) {
        params.ru_work = [params.ru_work];
      }

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
        // 제출 흐름(적용하기/자동이력서)이 아니면 텍스트만 초기화하고 끝낸다
        if (!window.__quickInfoApplyFlow && !window.__resumeAutoFlow) {
          window.__quickInfoExpectLocation = false;
          // 직접 고른 주소가 있으면 그 표시를 유지한다
          if (!window.__quickInfoManualAddr) {
            var regionEl = document.querySelector('.quickinfo-region-text');
            if (regionEl) regionEl.textContent = "근무 지역 선택";
          }
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

          // 제출 흐름(적용하기/자동이력서)이 아니면 UI 텍스트만 갱신하고 종료 (화면 표시용으로만 시/도 축약)
          // 위치 콜백이 늦게/여러 번 도착해도 여기서 멈추므로 "적용하기" 없이 제출되지 않는다
          if (!window.__quickInfoApplyFlow && !window.__resumeAutoFlow) {
            window.__quickInfoExpectLocation = false;
            // 직접 고른 주소가 있으면 GPS로 덮어쓰지 않는다
            if (window.__quickInfoManualAddr) {
              return;
            }
            var regionEl = document.querySelector('.quickinfo-region-text');
            if (regionEl) {
              var displayPart = frontPart;
              if (typeof fRemoveBigAdrJS === 'function') {
                displayPart = fRemoveBigAdrJS(frontPart) || frontPart;
              }
              regionEl.textContent = displayPart || "근무 지역 선택";
            }
            // 폴백(앱 백그라운드 전환) 시에도 이 주소를 쓸 수 있도록 캐시
            window.__quickInfoGpsAddr = {
              ru_name_seed_addr: frontPart,
              ad_addr: (typeof fRemoveBigAdrJS === 'function') ? (fRemoveBigAdrJS(frontPart) || frontPart) : frontPart,
              ad_jibun: frontPart,
              ad_doro: "",
              ad_zipcode: "",
              ad_gps_x: lng,
              ad_gps_y: lat
            };
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
    // SNS 임시 키는 여기서 지우지 않는다.
    // 가입 대기 상태에서는 '적용하기'의 join_complete_proc이 아직 이 값을 필요로 한다.
    // 정리는 clearJoinSnsKeys()가 가입 성공 직후에 수행한다.

    var isLogin = <?php echo $this->user->isLogin() ? 'true' : 'false'; ?>;
    var isMem = <?php echo (isset($mm_type) && $mm_type === "MEM") ? 'true' : 'false'; ?>;
    var hasOpenPanel = (typeof window.openPanel === 'function');
    var DEBUG_PREFIX = '[join_mem_finish][bottomsheet]';

    // 가입 대기(SNS) 상태는 비로그인이므로 로그인 가드를 우회한다
    if (!JOIN_PENDING && (!isLogin || !isMem)) return;

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

    // SNS 가입자 QuickInfo 오픈 흐름 (GOOGLE/WECHAT/KAKAO/NAVER)
    // hasLocationConsent: 위치권한 동의 여부(granted면 true)
    // 위치권한이 있어도 바텀시트는 반드시 뜨며, 위치는 지역 칸을 채우는 용도로만 쓴다
    function openQuickInfoForSns(hasLocationConsent) {
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
        window.__resumeAutoFlow = false;
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

      // SNS/비SNS 구분 없이 QuickInfo 바텀시트만 연다
      openQuickInfoForSns(hasLocationConsent);
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

  // 자동이력서 제출용 위치 요청 (QuickInfo '적용하기'에서 직접 호출한다)
  // 과거에는 #btnResumeGuide 버튼 클릭으로 트리거했으나, 그 버튼을 만들던
  // resumeGuidePanel이 제거되어 함수로 분리했다.
  window.requestLocationForSubmit = function() {
    // 이 경로로 받은 위치 콜백은 제출용이라는 표시
    window.__resumeAutoFlow = true;

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
  };

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

    if (!selections.work || selections.work.length === 0) {
      commonAlert("업종을 선택해주세요.", function() {});
      return;
    }

    // QuickInfo 선택값 캐시
    window.__quickInfoSelections = selections;
    // 자동이력서로 넘어가는 모드 플래그 / returnLocation 콜백을 상황별로 다르게 처리하기 위한 
    window.__quickInfoApplyFlow = true;
    // 위치 콜백 대기 안 함 / returnLocation 콜백을 상황별로 다르게 처리하기 위한 
    window.__quickInfoExpectLocation = false;

    // 사용자가 직접 고른 주소가 있으면 위치권한 유무와 무관하게 그 주소를 쓴다
    // (권한이 있다고 GPS를 다시 받아오면 직접 선택한 주소가 덮어써진다)
    if (window.__quickInfoManualAddr) {
      submitQuickInfoWithAddress(window.__quickInfoManualAddr, selections);
      return;
    }

    var storedPermission = (typeof window.getLocationPermissionStatus === 'function') ?
      window.getLocationPermissionStatus() :
      null;
    var hasLocationConsent = storedPermission === 'granted';

    if (!hasLocationConsent) {
      $('.quickinfo-region-field').trigger('click');
      return;
    }

    // 직접 선택한 주소가 없으면 GPS로 받아온다
    window.requestLocationForSubmit();
  };

  // 적용하기 버튼은 바텀시트 템플릿이 심은 onclick="handleQuickInfoApplyClick()"으로 동작한다.
  // 여기에 jQuery 핸들러를 추가로 걸면 한 번 눌러도 두 번 실행되어 알림이 겹친다.

  // 업종 선택 개수 제한 (data-select-limit) — 이력서 작성 화면과 동일한 규칙
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

    // 국적/성별/나이/업종 해제
    // 서버가 확보해 잠근 값(disabled)은 초기화 대상에서 제외한다.
    // jQuery의 .prop()는 disabled를 무시하므로 .not(':disabled')가 반드시 필요하다.
    $('input[name="quickinfo_nation"], input[name="quickinfo_gender"], input[name="quickinfo_age"], input[name="quickinfo_work[]"]')
      .not(':disabled')
      .prop('checked', false).trigger('change');

    // 지역 텍스트 초기화
    var regionEl = document.querySelector('.quickinfo-region-text');
    if (regionEl) regionEl.textContent = "근무 지역 선택";

    // QuickInfo 캐시/상태 초기화
    window.__quickInfoSelections = null;
    window.__quickInfoManualAddrMode = false;
    window.__quickInfoManualAddr = null;
    window.__quickInfoGpsAddr = null;
    window.__quickInfoExpectLocation = false;
    window.__quickInfoApplyFlow = false;
    window.__resumeAutoFlow = false;
  });
</script>

<script>
  // join_mem_finish: 바텀시트 닫기 / 뒤로가기 처리
  (function() {
    function initPanelExit() {
      var panel = document.getElementById('quickInfoPanel');

      // 배경 클릭·ESC로는 닫히지 않게 유지한다 (핸들바 탭만 허용)
      var ids = ['quickInfoPanel', 'changeArea'];
      for (var i = 0; i < ids.length; i++) {
        var el = document.getElementById(ids[i]);
        if (el) {
          el.setAttribute('data-disable-bg-close', 'true');
          el.setAttribute('data-disable-esc-close', 'true');
        }
      }

      // closePanel의 폼 자동 초기화를 막는다.
      // 닫았다 다시 열었을 때 선택값(특히 서버가 채운 잠금 값)이 날아가면 안 된다.
      if (panel) {
        panel.setAttribute('data-disable-form-reset', 'true');
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

    // 물리 뒤로가기는 add.js의 공용 핸들러(customBack / handleAndroidBack)가 처리한다.
    // 1차: closeOpenLayerPanel()이 열린 바텀시트(quickInfoPanel, changeArea)를 닫고
    // 2차: 경로 분기가 JOIN_PENDING을 보고 /member/login으로 이동시킨다.
  })();
</script>

<?= getCashJs(RES_PATH . "/js/member/join_quickinfo_bottom_sheet.js") ?>