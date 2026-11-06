<?php
/**
 * 추천인재 정보 제공 동의 팝업 (모바일/웹 공통 파티얼)
 *
 * - 대상: 로그인한 개인(MEM)/기업(COMP) 회원 중 st_member.mm_agree_ad 가 빈값인 회원
 * - 노출: 로그인 세션당 1회. 세션 키 AGREE_AD_ASKED 에 mm_idx 를 기록해 같은 세션의 다음 페이지에서는 숨김
 *         (로그아웃 시 User::clearLoginSession() 에서 세션 키를 지움)
 * - 확인 버튼: /setting_proc/agree_ad 호출 (mm_agree_ad='Y') 성공 시 팝업 닫기
 * - X 버튼: 팝업을 즉시 닫고 /setting_proc/disagree_ad 호출 (mm_agree_ad='N')
 * - 사용처: mobile/_layout/basic.php, web/_layout/basic.php 에서 include
 */

// 팝업 표시 여부 (기본값: 숨김)
$agree_ad_popup_show = false;

// 로그인한 개인(MEM)/기업(COMP) 회원에게만 검사한다
if ($this->user->isLogin() && ($this->user->getUserType() == "MEM" || $this->user->getUserType() == "COMP")) {
  // 현재 로그인한 회원의 mm_idx (세션에서 꺼낸 회원번호)
  $agree_ad_popup_idx = $this->user->getUserIdx();
  // 회원 정보 조회 (mm_agree_ad 값을 확인하기 위함)
  $agree_ad_popup_member = $this->member_model->getMember(["mm_idx" => $agree_ad_popup_idx]);

  // 빈값(미응답) 판정: 회원 행이 없거나 mm_agree_ad 가 NULL/'' 이면 미응답으로 본다
  $agree_ad_popup_value = (!empty($agree_ad_popup_member) && isset($agree_ad_popup_member["mm_agree_ad"])) ? $agree_ad_popup_member["mm_agree_ad"] : '';

  // 이번 로그인 세션에서 이미 띄웠는지 확인 (세션에 저장된 mm_idx 와 비교)
  $agree_ad_popup_asked = $this->common->getSession("AGREE_AD_ASKED");
  $agree_ad_popup_show = ($agree_ad_popup_value === '' && (string)$agree_ad_popup_asked !== (string)$agree_ad_popup_idx);

  if ($agree_ad_popup_show) {
    // 이번 로그인 세션에서 한 번 띄웠음을 기록 (값은 문자열로 저장해 비교 타입을 맞춤)
    $this->common->setSession("AGREE_AD_ASKED", (string)$agree_ad_popup_idx);
  }
}
?>
<?php if ($agree_ad_popup_show) : ?>
<style>
  /* 추천인재 정보 제공 동의 팝업 스타일 (모바일/웹 공통) */

  /* 화면 전체를 덮는 반투명 배경. 팝업을 가운데 정렬한다 */
  .agree-ad-popup {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    box-sizing: border-box;
    background: rgba(0, 0, 0, 0.5);
  }
  /* data-state="closed"이면 화면에서 숨김 */
  .agree-ad-popup[data-state="closed"] { display: none; }

  /* 팝업 박스 (흰색 카드). 상단 여백은 X 버튼 자리 */
  .agree-ad-popup-box {
    position: relative;
    width: 100%;
    max-width: 320px;
    padding: 44px 20px 20px;
    box-sizing: border-box;
    border-radius: 12px;
    background: #fff;
  }

  /* 우측 상단 X 버튼 */
  .agree-ad-popup-close {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 36px;
    height: 36px;
    padding: 0;
    border: 0;
    background: none;
    font-size: 26px;
    line-height: 1;
    color: #666;
    cursor: pointer;
  }

  /* 안내 문구 */
  .agree-ad-popup-title {
    margin: 0 0 20px;
    font-size: 16px;
    line-height: 1.5;
    color: #333;
    text-align: center;
    word-break: keep-all;
  }

  /* 하단 확인 버튼 (브랜드 색상) */
  .agree-ad-popup-confirm {
    display: block;
    width: 100%;
    height: 44px;
    border: 0;
    border-radius: 8px;
    background: #17B890;
    color: #fff;
    font-size: 15px;
    font-weight: bold;
    cursor: pointer;
  }
</style>

<!-- 팝업 본체. 상태는 data-state 속성으로 관리한다 (open: 표시, closed: 숨김) -->
<div id="agreeAdPopup" class="agree-ad-popup" data-state="open" role="dialog" aria-modal="true" aria-labelledby="agreeAdPopupTitle">
  <div class="agree-ad-popup-box">
    <!-- X 버튼: 팝업을 닫고 미동의(N) 저장 -->
    <button type="button" id="btnAgreeAdClose" class="agree-ad-popup-close" aria-label="닫기">&times;</button>
    <!-- 안내 문구 -->
    <p id="agreeAdPopupTitle" class="agree-ad-popup-title">나우잡AI가 채용공고에맞춰
맞춤인재를 매칭해드려요!</p>
    <!-- 확인 버튼: 동의(Y) 저장 성공 시 팝업 닫기 -->
    <button type="button" id="btnAgreeAdConfirm" class="agree-ad-popup-confirm">확인</button>
  </div>
</div>

<script>
  // 추천인재 정보 제공 동의 팝업 제어 (상태는 data-state 속성으로 관리: open/closed)
  (function() {
    // 팝업 요소와 두 버튼을 찾는다
    var popup = document.getElementById('agreeAdPopup');
    var btnClose = document.getElementById('btnAgreeAdClose');
    var btnConfirm = document.getElementById('btnAgreeAdConfirm');
    // 요소가 하나라도 없으면 아무것도 하지 않는다
    if (!popup || !btnClose || !btnConfirm) return;

    // 중복 요청 방지 플래그: 요청 중(true)이면 버튼을 다시 눌러도 무시한다
    var busy = false;

    // 동의 값을 서버에 전송 (jQuery 로드 여부가 레이아웃마다 달라 XMLHttpRequest 사용)
    // url: 호출할 주소 (확인=agree_ad, X=disagree_ad)
    // callback(성공여부, 응답 객체): 응답이 끝났을 때 실행된다
    function postAgreeAd(url, callback) {
      var xhr = new XMLHttpRequest();
      // POST 방식으로 준비 (세 번째 인자 true = 비동기)
      xhr.open('POST', url, true);
      // 폼 데이터 형식이라고 서버에 알린다
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      // 응답이 도착했을 때 실행
      xhr.onload = function() {
        var ret = null;
        // 응답 문자열을 JSON 객체로 변환 (실패하면 null)
        try {
          ret = JSON.parse(xhr.responseText);
        } catch (e) {
          ret = null;
        }
        // HTTP 200이고 응답 code가 '00'이면 성공
        callback(xhr.status === 200 && ret !== null && ret.code == '00', ret);
      };
      // 네트워크 오류 등으로 요청 자체가 실패했을 때 실패로 처리
      xhr.onerror = function() {
        callback(false, null);
      };
      // 보낼 본문 데이터는 없으므로 빈 문자열 (회원은 서버가 세션으로 식별)
      xhr.send('');
    }

    // 팝업을 숨긴다 (data-state를 closed로 변경)
    function closePopup() {
      popup.setAttribute('data-state', 'closed');
    }

    // 확인 버튼: 동의(Y) 저장에 성공했을 때만 닫는다
    btnConfirm.addEventListener('click', function() {
      // 요청 중이면 무시
      if (busy) return;
      busy = true;
      postAgreeAd('/setting_proc/agree_ad', function(ok, ret) {
        // 응답이 왔으니 요청 중 상태를 해제
        busy = false;
        if (ok) {
          closePopup();
          return;
        }
        // 실패하면 서버 메시지(없으면 기본 문구)를 알리고 팝업은 유지
        alert((ret && ret.msg) || '요청 처리 중 오류가 발생했습니다.');
      });
    });

    // X 버튼: 팝업을 먼저 닫고, 동시에 미동의(N) 저장을 요청한다
    btnClose.addEventListener('click', function() {
      // 요청 중이면 무시
      if (busy) return;
      busy = true;
      // 저장 결과와 상관없이 팝업은 바로 닫는다
      closePopup();
      postAgreeAd('/setting_proc/disagree_ad', function(ok) {
        // 응답이 왔으니 요청 중 상태를 해제
        busy = false;
        // 저장 실패는 화면에 알리지 않고 콘솔에만 남긴다 (팝업은 이미 닫힌 상태)
        if (!ok) console.error('추천인재 정보 제공 미동의 저장 실패');
      });
    });
  })();
</script>
<?php endif; ?>
