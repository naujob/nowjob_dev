<?php
// QuickInfo 바텀시트 (5초 간편 정보 입력)
// 컨트롤러(member.php::join_mem_finish)가 넘긴 $quickinfo_prefill / $ru_wrok_list 사용

// 가입 시점에 확보한 값이 있으면 미리 선택, 없으면 기존 기본값(내국인/남성/30대) 유지
$qi_prefill = isset($quickinfo_prefill) ? $quickinfo_prefill : [];
$qi_nation = isset($qi_prefill["nation"]) && $qi_prefill["nation"] != "" ? $qi_prefill["nation"] : "domestic";
$qi_gender = isset($qi_prefill["gender"]) && $qi_prefill["gender"] != "" ? $qi_prefill["gender"] : "male";
$qi_age = isset($qi_prefill["age"]) && $qi_prefill["age"] != "" ? $qi_prefill["age"] : "30s";

// 라디오 checked 속성 문자열
$qi_nation_domestic = ($qi_nation === "domestic") ? "checked" : "";
$qi_nation_foreign = ($qi_nation === "foreign") ? "checked" : "";
$qi_gender_male = ($qi_gender === "male") ? "checked" : "";
$qi_gender_female = ($qi_gender === "female") ? "checked" : "";

$qi_age_list = ["20s" => "20대", "30s" => "30대", "40s" => "40대", "50s" => "50대", "60s_plus" => "60대", "70s_plus" => "70대"];
$qi_age_html = "";
foreach ($qi_age_list as $age_key => $age_label) {
  $checked = ($qi_age === $age_key) ? "checked" : "";
  $qi_age_html .= '<label class="form-toggle-btn2">'
    . '<input type="radio" name="quickinfo_age" value="' . $age_key . '" ' . $checked . '>'
    . '<span class="btn">' . $age_label . '</span>'
    . '</label>';
}

// 업종(희망업무): 코드테이블(cd_grp='희망업무') 기반, cd_sort 순으로 노출
// 아래 업종은 간편 입력 대상에서 제외한다
$qi_work_exclude = ["매장관리", "사무직", "건설"];
// 기본 선택 업종 (사용자가 바꿀 수 있다)
$qi_work_default = "생산";
$qi_work_html = "";
$qi_work_list = isset($ru_wrok_list) && is_array($ru_wrok_list) ? $ru_wrok_list : [];
foreach ($qi_work_list as $work_row) {
  $work_val = isset($work_row["cd_val"]) ? trim($work_row["cd_val"]) : "";
  if ($work_val == "" || in_array($work_val, $qi_work_exclude, true)) {
    continue;
  }
  $work_esc = htmlspecialchars($work_val, ENT_QUOTES, 'UTF-8');
  $work_checked = ($work_val === $qi_work_default) ? "checked" : "";
  $qi_work_html .= '<label class="form-toggle-btn2">'
    . '<input type="checkbox" name="quickinfo_work[]" data-select-limit="2" value="' . $work_esc . '" ' . $work_checked . '>'
    . '<span class="btn">' . $work_esc . '</span>'
    . '</label>';
}

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
        <input type="radio" name="quickinfo_nation" value="domestic" {$qi_nation_domestic}>
        <span class="btn">내국인</span>
      </label>
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_nation" value="foreign" {$qi_nation_foreign}>
        <span class="btn">외국인</span>
      </label>
    </div>
  </div>

  <div class="quickinfo-group">
    <div class="quickinfo-label">성별</div>
    <div class="form-toggle-group col-2">
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_gender" value="male" {$qi_gender_male}>
        <span class="btn">남성</span>
      </label>
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_gender" value="female" {$qi_gender_female}>
        <span class="btn">여성</span>
      </label>
    </div>
  </div>

  <div class="quickinfo-group">
    <div class="quickinfo-label">나이</div>
    <div class="form-toggle-group col-6">
      {$qi_age_html}
    </div>
  </div>

  <div class="quickinfo-group">
    <div class="quickinfo-label">업종 <em>최대 2개 선택</em></div>
    <div class="form-toggle-group col-3">
      {$qi_work_html}
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
