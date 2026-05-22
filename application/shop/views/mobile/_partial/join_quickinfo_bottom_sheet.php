<?php
$quickInfoContent = <<<HTML
<div class="quickinfo-sheet" data-quickinfo-sheet="true">
  <div class="quickinfo-title">
    <span>5초 간편 정보 입력</span>
    <i class="fa-solid fa-bolt" aria-hidden="true"></i>
  </div>

  <div class="quickinfo-group">
    <button type="button" class="quickinfo-region-field" data-action="open-region-popup">
      <span class="quickinfo-region-text">근무 지역 선택</span>
      <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
    </button>
  </div>

  <div class="quickinfo-group">
    <div class="quickinfo-label">국적</div>
    <div class="form-toggle-group col-2">
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_nation" value="domestic" checked>
        <span class="btn">내국인</span>
      </label>
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_nation" value="foreign">
        <span class="btn">외국인</span>
      </label>
    </div>
  </div>

  <div class="quickinfo-group">
    <div class="quickinfo-label">성별</div>
    <div class="form-toggle-group col-2">
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_gender" value="male" checked>
        <span class="btn">남성</span>
      </label>
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_gender" value="female">
        <span class="btn">여성</span>
      </label>
    </div>
  </div>

  <div class="quickinfo-group">
    <div class="quickinfo-label">나이</div>
    <div class="form-toggle-group col-5">
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_age" value="20s">
        <span class="btn">20대</span>
      </label>
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_age" value="30s" checked>
        <span class="btn">30대</span>
      </label>
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_age" value="40s">
        <span class="btn">40대</span>
      </label>
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_age" value="50s">
        <span class="btn">50대</span>
      </label>
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_age" value="60s_plus">
        <span class="btn">60대~</span>
      </label>
    </div>
  </div>
</div>
HTML;

renderSelectBottomSheet(
  'quickInfoPanel',
  '',
  $quickInfoContent,
  '초기화',
  'btnQuickInfoReset',
  '',
  '적용하기',
  'btnQuickInfoApply',
  'handleQuickInfoApplyClick()',
  'bottom-sheet-content-container quickinfo-bottom-sheet',
  'bottom-sheet-content-body'
);
?>
