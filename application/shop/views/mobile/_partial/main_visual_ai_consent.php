<?php
// A.I 매칭 광고성 정보 수신 동의 바텀시트 + 약관 패널 + 동의 처리 스크립트
// 쓰는 곳: _partial/main_visual_top_bar.php 가 A.I 매칭 버튼을 그릴 때 함께 include (#aiMatchLink 뒤에 나와야 한다)
if ($this->user->getUserType() == "MEM" || $this->user->getUserType() == "COMP") :
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_template.php');
$this->load->model('settingmodel', 'setting_model');
$policy5 = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "adv_m"]);
$aiMatchConsentContent = '
  <p class="ai-match-desc">' . t('서비스 이용을 위해 동의해 주세요.') . '<br>' . t('A.I매칭 서비스를 위해 고객 동의가 필요합니다.') . '</p>
  <label class="ai-match-agree-check" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
    <input type="checkbox" id="rd_agree_ad" name="rd_agree_ad" value="1" style="width: 18px; height: 18px; cursor: pointer; accent-color: #17B890; margin: 0;">
    <div class="ai-match-terms-link" style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex: 1;">
      <a href="javascript:void(0);" role="button" onclick="openAdAgreeTermsPanel();" style="color: #333; text-decoration: none; flex: 1; font-size: 13px;">' . t('광고성 정보 알림 수신 동의') . '</a>
      <i class="fa fa-angle-right" style="color: #999; flex-shrink: 0;"></i>
    </div>
  </label>';
renderSelectBottomSheet(
  'adConsentSheet', t('A.I 매칭'), $aiMatchConsentContent,
  t('닫기'), 'btnAdConsentClose', "closePanel(document.getElementById('adConsentSheet'));",
  t('동의하기'), 'btnAdConsentAgree', 'submitAdAgree();'
);
?>

<!-- 광고성 정보 알림 수신 동의 약관 상세 -->
<div id="adConsentTermsPanel" class="layer-panel card-type" tabindex="-1">
  <div class="panel-container">
    <div class="panel-header">
      <h2 class="panel-title"><?= t('광고성 정보 알림 수신 동의') ?></h2>
    </div>
    <div class="panel-body">
      <div class="panel-content" style="padding: 20px;">
        <div class="terms-wrap"><?= isset($policy5["adv_m"]) ? $policy5["adv_m"] : '' ?></div>
      </div>
    </div>
    <div class="panel-footer">
      <div class="sticky-bottom view-footer">
        <button type="button" class="btn btn-primary" onclick="checkAdAgreeTerms();"><?= t('확인') ?></button>
      </div>
    </div>
  </div>
</div>

<style>
  /* 동의 바텀시트 전용 스타일 (신규, 다른 영역에 영향 없음) */
  .ai-match-desc { margin-bottom: 20px; color: #666; line-height: 1.5; }
  .ai-match-agree-check { display: flex; align-items: center; gap: 8px; }
  .ai-match-terms-link { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex: 1; }
  .ai-match-terms-link a { color: #333; text-decoration: none; flex: 1; }
  .ai-match-terms-link i { color: #999; flex-shrink: 0; }
  .bottom-sheet-content .bottom-sheet-content-body {
    padding: 0px 20px 10px 0px;
        display: flex;
    flex-direction: column;
    gap: 11px;
  }
</style>

<script>
  // A.I 매칭 클릭 시 광고성 정보 동의 여부 확인 후 가로채기
  (function() {
    var aiMatchLink = document.getElementById('aiMatchLink');
    if (!aiMatchLink) return;
    aiMatchLink.addEventListener('click', function(e) {
      var agreeAd = aiMatchLink.getAttribute('data-agree-ad');

      if (agreeAd === 'Y') {
        return; // 이미 동의: 정상 이동
      }

      e.preventDefault();

      var chk = document.getElementById('rd_agree_ad');
      if (chk) chk.checked = true; // 체크박스 기본 체크

      var sheet = document.getElementById('adConsentSheet');
      if (sheet && typeof openPanel === 'function') {
        openPanel(sheet);
      }
    });
  })();

  // 약관 상세 패널 열기 (바텀시트는 열려있고 패널만 오픈)
  function openAdAgreeTermsPanel() {
    var panel = document.getElementById('adConsentTermsPanel');
    if (panel && typeof window.openPanel === 'function') {
      window.openPanel(panel);
    }
  }

  // 약관 상세 패널의 "확인" 클릭 시 체크박스 동의 처리 및 패널 닫기
  function checkAdAgreeTerms() {
    document.getElementById('rd_agree_ad').checked = true;
    var panel = document.getElementById('adConsentTermsPanel');
    if (panel) {
      if (typeof window.closePanel === 'function') {
        window.closePanel(panel);
      } else {
        panel.classList.remove('on');
      }
    }
  }

  // 동의하기 클릭 시 서버에 mm_agree_ad=Y 저장 후 매칭 페이지 이동
  function submitAdAgree() {
    var chk = document.getElementById('rd_agree_ad');
    if (!chk || !chk.checked) {
      commonAlert(t('광고성 정보 알림 수신에 동의해주세요.'), function() {});
      return;
    }

    var matchUrl = document.getElementById('aiMatchLink').getAttribute('data-match-url');

    comAjax('/setting_proc/agree_ad', {}, function(ret) {
      if (ret.code == '00') {
        location.href = matchUrl;
      } else {
        commonAlert(ret.msg, function() {});
      }
    });
  }
</script>
<?php endif; ?>
