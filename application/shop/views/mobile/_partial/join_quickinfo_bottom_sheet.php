<?php
// QuickInfo 바텀시트 (5초 간편 정보 입력)
// 컨트롤러(member.php::join_mem_finish)가 넘긴 $quickinfo_prefill / $ru_wrok_list 사용

// 모바일/PC 공통 스타일 (모바일 main.css와 분리된 전용 파일, 경로는 모바일 기준으로 고정)
echo getCachCss("/resource/shop/mobile/css/quickinfo.css");

// 가입 시점에 확보한 값이 있으면 미리 선택, 없으면 기존 기본값(내국인/남성) 유지
$qi_prefill = isset($quickinfo_prefill) ? $quickinfo_prefill : [];

// 서버가 실제로 확보한 값인지 여부 (확보된 항목만 수정 불가로 잠근다)
// 기본값(내국인/남성)은 서버가 알아낸 값이 아니므로 반드시 수정 가능해야 한다.
// 출생연도는 기본값이 없다(필수 직접 선택). 서버가 확보한 연도가 있을 때만 잠근다.
$qi_nation_locked = (isset($qi_prefill["nation"]) && $qi_prefill["nation"] != "");
$qi_gender_locked = (isset($qi_prefill["gender"]) && $qi_prefill["gender"] != "");
$qi_birth_locked = (isset($qi_prefill["birth_year"]) && $qi_prefill["birth_year"] != "");

$qi_nation = $qi_nation_locked ? $qi_prefill["nation"] : "domestic";
$qi_gender = $qi_gender_locked ? $qi_prefill["gender"] : "male";
$qi_birth_year = $qi_birth_locked ? $qi_prefill["birth_year"] : "";

// 잠금 처리용 속성 문자열
$qi_nation_dis = $qi_nation_locked ? " disabled" : "";
$qi_gender_dis = $qi_gender_locked ? " disabled" : "";
$qi_birth_dis = $qi_birth_locked ? " disabled" : "";

// 그룹 단위 상태(data 속성) + 라벨 보조문구
// 자동 입력 값의 출처 표시 (join_type: 가입 경로 코드, WEB은 NICE 본인인증)
$qi_source_map = ["KAKAO" => "카카오", "NAVER" => "네이버", "APPLE" => "애플", "GOOGLE" => "구글", "WECHAT" => "위챗", "WEB" => "본인인증"];
$qi_source_key = isset($join_type) ? $join_type : "";
$qi_source_name = isset($qi_source_map[$qi_source_key]) ? $qi_source_map[$qi_source_key] : "회원";
// 잠금 배지: 텍스트 뒤에 자물쇠 아이콘을 붙이고, 제목 줄 오른쪽에 배치한다
$qi_lock_note = '<span class="quickinfo-lock-badge">' . $qi_source_name . ' 정보로 자동 입력 <i class="fa-solid fa-lock" aria-hidden="true"></i></span>';

$qi_nation_lock_attr = $qi_nation_locked ? ' data-locked="true"' : '';
$qi_gender_lock_attr = $qi_gender_locked ? ' data-locked="true"' : '';
$qi_birth_lock_attr = $qi_birth_locked ? ' data-locked="true"' : '';
$qi_nation_note = $qi_nation_locked ? $qi_lock_note : '';
$qi_gender_note = $qi_gender_locked ? $qi_lock_note : '';
$qi_birth_note = $qi_birth_locked ? $qi_lock_note : '';

// 필수 입력 표시 (모든 항목이 '적용하기' 검증 대상이다)
$qi_req = '<span class="quickinfo-required">*</span>';

// 라디오 checked 속성 문자열
$qi_nation_domestic = ($qi_nation === "domestic") ? "checked" : "";
$qi_nation_foreign = ($qi_nation === "foreign") ? "checked" : "";
$qi_gender_male = ($qi_gender === "male") ? "checked" : "";
$qi_gender_female = ($qi_gender === "female") ? "checked" : "";

// 출생연도 트리거: 선택 전에는 안내 문구, 선택 후에는 "1993년" 형식으로 표시한다
$qi_birth_has_value = ($qi_birth_year != "") ? " has-value" : "";
$qi_birth_label = ($qi_birth_year != "") ? $qi_birth_year . "년" : "출생연도를 선택해주세요";
$qi_birth_year_esc = htmlspecialchars($qi_birth_year, ENT_QUOTES, 'UTF-8');

// 출생연도 휠 시트 목록: 올해-15세 ~ 올해-90세 (내림차순, 기본 위치는 JS가 정한다)
$qi_birth_now = (int)date("Y");
$qi_birth_year_list = "";
for ($qi_by = $qi_birth_now - 15; $qi_by >= $qi_birth_now - 90; $qi_by--) {
  $qi_birth_year_list .= '<li data-val="' . $qi_by . '">' . $qi_by . '년</li>';
}

// 업종(희망업무): 코드테이블(cd_grp='희망업무') 기반, cd_sort 순으로 노출
// 아래 업종은 간편 입력 대상에서 제외한다 (매장관리, 디자인은 9종 목록 외 항목)
$qi_work_exclude = ["매장관리", "디자인"];
// 업종 표시 순서 (지정 순서대로 먼저 노출, 목록에 없는 업종은 뒤에 기존 cd_sort 순으로 붙는다)
$qi_work_order = ["생산", "물류", "건설", "운전배달", "식당", "서비스", "사무직", "상담", "기타"];
// 기본 선택 없음 - 사용자가 직접 고르게 한다(필수 항목)
$qi_work_html = "";
$qi_work_all = isset($ru_wrok_list) && is_array($ru_wrok_list) ? $ru_wrok_list : [];
$qi_work_list = [];
// 지정 순서에 해당하는 행을 먼저 추가
foreach ($qi_work_order as $order_val) {
  foreach ($qi_work_all as $work_row) {
    if (isset($work_row["cd_val"]) && trim($work_row["cd_val"]) === $order_val) {
      $qi_work_list[] = $work_row;
    }
  }
}
// 지정 순서에 없는 행은 기존 순서를 유지해 뒤에 추가
foreach ($qi_work_all as $work_row) {
  $work_val = isset($work_row["cd_val"]) ? trim($work_row["cd_val"]) : "";
  if (!in_array($work_val, $qi_work_order, true)) {
    $qi_work_list[] = $work_row;
  }
}
// 이미 노출한 업종 값 목록 (중복 체크박스 방지용)
$qi_work_rendered = [];
foreach ($qi_work_list as $work_row) {
  $work_val = isset($work_row["cd_val"]) ? trim($work_row["cd_val"]) : "";
  // 중복 cd_val(예: DB에 같은 업종이 2건)은 체크박스를 한 번만 노출한다
  if ($work_val == "" || in_array($work_val, $qi_work_exclude, true) || in_array($work_val, $qi_work_rendered, true)) {
    continue;
  }
  $qi_work_rendered[] = $work_val;
  $work_esc = htmlspecialchars($work_val, ENT_QUOTES, 'UTF-8');
  $qi_work_html .= '<label class="form-toggle-btn2">'
    . '<input type="checkbox" name="quickinfo_work[]" data-select-limit="2" value="' . $work_esc . '">'
    . '<span class="btn">' . $work_esc . '</span>'
    . '</label>';
}

$quickInfoContent = <<<HTML
<div class="quickinfo-sheet" data-quickinfo-sheet="true">
  <div class="quickinfo-title">
    <span>5초 간편 정보 입력</span>
    <i class="fa-solid fa-bolt" aria-hidden="true"></i>
  </div>

  <div class="form-row quickinfo-row">
    <div class="form-row-header">
      <span class="form-row-title">근무 지역 {$qi_req}</span>
    </div>
    <button type="button" class="quickinfo-region-field" data-action="open-region-popup">
      <span class="quickinfo-region-text">근무 지역 선택</span>
      <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
    </button>
  </div>

  <div class="form-row quickinfo-row"{$qi_nation_lock_attr}>
    <div class="form-row-header">
      <span class="form-row-title">국적 {$qi_req}</span>{$qi_nation_note}
    </div>
    <div class="option-table-group option-table-cols-2">
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_nation" value="domestic" {$qi_nation_domestic}{$qi_nation_dis}>
        <span class="btn">내국인</span>
      </label>
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_nation" value="foreign" {$qi_nation_foreign}{$qi_nation_dis}>
        <span class="btn">외국인</span>
      </label>
    </div>
  </div>

  <div class="form-row quickinfo-row"{$qi_gender_lock_attr}>
    <div class="form-row-header">
      <span class="form-row-title">성별 {$qi_req}</span>{$qi_gender_note}
    </div>
    <div class="option-table-group option-table-cols-2">
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_gender" value="male" {$qi_gender_male}{$qi_gender_dis}>
        <span class="btn">남성</span>
      </label>
      <label class="form-toggle-btn2">
        <input type="radio" name="quickinfo_gender" value="female" {$qi_gender_female}{$qi_gender_dis}>
        <span class="btn">여성</span>
      </label>
    </div>
  </div>

  <div class="form-row quickinfo-row"{$qi_birth_lock_attr}>
    <div class="form-row-header">
      <span class="form-row-title">출생연도 {$qi_req}</span>{$qi_birth_note}
    </div>
    <input type="hidden" name="quickinfo_birth_year" id="quickinfoBirthYear" value="{$qi_birth_year_esc}">
    <button type="button" id="btnQuickInfoBirthYear" class="quickinfo-birth-trigger{$qi_birth_has_value}"{$qi_birth_dis}>
      <span class="date-display">{$qi_birth_label}</span>
      <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
    </button>
  </div>

  <div class="form-row quickinfo-row">
    <div class="form-row-header">
      <span class="form-row-title">업종 {$qi_req} <em>최대 2개 선택</em></span>
    </div>
    <div class="option-table-group option-table-cols-3">
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
  'bottom-sheet-content-body',
  false // bottom-sheet-footer 부모 태그 제거 (버튼 영역만 출력)
);

// 출생연도 휠 바텀시트: QuickInfo 시트 위에 열린다 (resume_write1.php 날짜 휠과 같은 마크업 사용)
$qi_birth_wheel_content = '<div class="time-wheel-picker">'
  . '<div class="wheel-col wheel-col-year" id="birthYearWheel"><ul>' . $qi_birth_year_list . '</ul></div>'
  . '<div class="wheel-highlight-bar"></div>'
  . '</div>';

renderSelectBottomSheet(
  'birthYearSheet',
  '출생연도를 선택해주세요',
  $qi_birth_wheel_content,
  '취소', 'btnBirthYearCancel', 'closeBirthYearSheet()',
  '확인', 'btnBirthYearConfirm', 'confirmBirthYearSheet()',
  'bottom-sheet-time-picker',
  'time-wheel-picker-content'
);
?>
