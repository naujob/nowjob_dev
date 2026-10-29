<style>
  /* 근무형태, 급여, 성별 버튼 그룹 스타일 */
  .work-type-group,
  .sex-group {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .work-type-group .form-toggle-btn2,
  .sex-group .form-toggle-btn2 {
    flex: 1;
    min-width: 0;
  }

  /* 카테고리, 근무형태 버튼의 span이 부모 넓이를 꽉 차도록 */
  .work-type-group .form-toggle-btn2 .btn {
    width: 100%;
    /* 부모의 넓이를 꽉 차도록 설정 */
  }

  /* 근무형태 마지막 label의 오른쪽 마진 제거 */
  .work-type-group .form-toggle-btn2:last-child {
    margin-right: 0;
  }

  /* 성별 그룹은 2개 항목이므로 각각 50% */
  .sex-group {
    flex-wrap: nowrap;
  }

  .sex-group .form-toggle-btn2 {
    flex: 0 0 calc(50% - 13px);
  }

  /* 카테고리 마지막 label의 오른쪽 마진 제거 */
  .category-toggle-group .form-toggle-btn2:last-child {
    margin-right: 0;
  }

  .category-toggle-group .form-toggle-btn2 .btn {
    width: 100%;
    /* 부모의 넓이를 꽉 차도록 설정 */
  }

  /* 카테고리 버튼 스타일 - 3개씩 정렬 */
  .category-toggle-group .form-toggle-btn2 {
    width: 32%;
    margin-right: 1%;
    margin-bottom: 8px;
  }

  /* 카테고리 카운터의 /3 부분 검정색으로 설정 */
  .category-counter .category-limit {
    color: #333;
  }

  /* 나이 선택 그룹 스타일 */
  .age-row {
    margin-bottom: 30px;
  }

  .age-single-select select {
    width: 100%;
    height: 56px;
    min-height: 56px;
    max-height: 56px;
    padding: 0 3rem 0 1rem;
    border: 1px solid #BEBFC9;
    border-radius: 12px;
    background-color: #fff;
    font-size: 15px;
    color: #333;
    box-sizing: border-box;
    line-height: 1.2;
    display: block;
    position: relative;
    opacity: 1;
    outline: 0;
    -webkit-tap-highlight-color: transparent;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 16px;
  }

  /* 근무요일 버튼 그룹 flex 스타일 */
  .form-toggle-group.align-justify {
    display: flex;
    justify-content: space-between;
    /* 요소들을 균등하게 분배 */
  }

  /* label 태그들 간의 간격을 margin으로 조절 */
  .form-toggle-group.align-justify .form-toggle-btn2 {
    margin-right: 7px;
    /* 오른쪽 마진으로 간격 조절 */
  }

  .form-toggle-group.align-justify .form-toggle-btn2:first-child {
    margin-left: 0;
    /* 첫 번째 요소는 왼쪽 마진 제거 */
  }

  .form-toggle-group.align-justify .form-toggle-btn2:last-child {
    margin-right: 0;
    /* 마지막 요소는 마진 제거 */
  }

  /* 근무요일 버튼의 span.btn width 조절 */
  .form-toggle-group.align-justify .form-toggle-btn2 .btn {
    width: 3.1rem;
    font-size: 1rem;
  }

  /* 입력 버튼 크기 통일 (56px) */
  .category-cell,
  .work-type-cell,
  .price-cell,
  .week-cell,
  .gender-cell,
  .nationality-cell {
    height: 56px;
    min-height: 56px;
    max-height: 56px;
  }

  /* 지역 선택 UI 스타일 (recruit/index_search.php 패턴과 동일하게 이식) */
  .region-section {
    margin-bottom: 30px;
  }

  .region-step-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    width: 100%;
  }

  .region-label {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-right: 8px;
  }

  .region-counter {
    font-size: 16px;
    color: #57CCAF;
    font-weight: 500;
  }

  .region-counter .separator,
  .region-counter .max-count {
    color: #333;
  }

  .region-select-btn {
    width: 100%;
    height: 56px;
    border: 1px solid #E0E0E0;
    border-radius: 12px;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    color: #333;
    cursor: pointer;
    transition: all 0.2s ease;
    gap: 6px;
    padding: 0 16px;
    box-sizing: border-box;
  }

  .region-select-btn .add-icon {
    font-size: 18px;
    font-weight: 300;
    line-height: 1;
  }

  .region-select-btn .add-text {
    font-size: 16px;
  }

  .region-select-btn:active {
    transform: translateY(1px);
  }

  /* 하단 버튼 영역(초기화/검색)은 fixed가 아니라 상세검색 콘텐츠 끝에 붙어 함께 스크롤 (main.css의 .sticky-bottom.view-footer fixed 무효화) */
  #advancedSearch .panel-body {
    padding-bottom: 0;
  }

  #advancedSearch .panel-footer .sticky-bottom.view-footer {
    position: static;
  }

  /* 선택된 지역 표시 영역 */
  .selected-regions-container {
    margin-top: 12px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .selected-region-item {
    background-color: #57CCAF;
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .selected-region-remove {
    background: none;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
    padding: 0;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.2s ease;
  }

  .selected-region-remove:hover {
    background-color: rgba(255, 255, 255, 0.2);
  }
  #advancedSearch.layer-panel .panel-content {
    padding-bottom: 0;
  }
  #advancedSearch .advanced-search-form .container {
    padding-bottom: 0;
  }
  /* 반응형 대응 */
  @media (max-width: 480px) {
    .work-type-group {
      gap: 6px;
    }

    .region-select-btn {
      height: 56px;
      font-size: 15px;
    }
  }
</style>
<div id="advancedSearch" class="layer-panel" tabindex="-1" data-disable-form-reset="true">
  <div class="panel-container">
    <div class="panel-header top-bar">
      <h2 class="panel-title">인재정보 상세검색</h2>
      <button type="button" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
    </div>
    <div class="panel-body">
      <div class="panel-content">

        <div class="section advanced-search-form">
          <div class="container">

            <!-- 지역 선택 섹션 -->
            <div class="region-section">
              <div class="region-step-container">
                <span class="region-label">근무지역</span>
                <span class="region-counter" id="regionCounter"><span style="color: #57CCAF;">0</span><span class="separator">/</span><span class="max-count">3</span></span>
              </div>
              <button type="button" class="region-select-btn" id="regionSelectBtn">
                <span class="add-icon">+</span>
                <span class="add-text">추가하기</span>
              </button>
              <div class="selected-regions-container" id="selectedRegionsContainer">
                <!-- 선택된 지역들이 여기에 동적으로 추가됩니다 -->
              </div>
            </div>

            <div class="form-row">
              <div class="category-label-row">
                <p class="form-label">업직종</p>
                <span class="category-counter"><span id="category-selected-count">0</span><span class="category-limit">/3</span></span>
              </div>
              <div class="category-grid">
                <div class="category-grid__body">
                  <?php
                  // 변수 안전성 확인
                  if (!isset($work_list)) {
                    $work_list = [];
                  }
                  if (!isset($search_txt)) {
                    $search_txt = [];
                  }

                  $desired_categories = ['생산', '물류', '건설', '운전배달', '식당', '서비스', '사무직', '상담', '기타'];
                  foreach ($desired_categories as $category_name) {
                    $found_category = null;
                    foreach ($work_list as $row) {
                      // $row 예: ['cd_idx' => '2', 'cd_val' => '생산', 'cd_key' => '생산', 'cd_sort' => '1', ...]
                      if ($row["cd_val"] == $category_name) {
                        $found_category = $row;
                        break;
                      }
                    }
                    if ($found_category) {
                      $isChecked = in_array_return($found_category["cd_val"], $search_txt["category"], "check");
                      $isActiveClass = $isChecked ? ' selected' : '';
                  ?>
                      <label class="category-cell<?= $isActiveClass ?>">
                        <input type="checkbox" name="category[]" value="<?= $found_category["cd_val"] ?>" data-img="<?= $found_category["cd_val2"] ?>" <?= $isChecked ?> data-category-checkbox>
                        <span><?= $found_category["cd_val"] ?></span>
                      </label>
                  <?php
                    }
                  }
                  ?>
                </div>
              </div>
            </div>

            <div class="form-row">
              <?php
              $has_work_selected = false;
              if (!empty($search_txt["work_type"])) {
                if (is_array($search_txt["work_type"])) {
                  foreach ($search_txt["work_type"] as $wt) {
                    if (trim($wt) !== "") {
                      $has_work_selected = true;
                      break;
                    }
                  }
                } else if (trim($search_txt["work_type"]) !== "") {
                  $has_work_selected = true;
                }
              }
              ?>
              <div class="work-type-header">
                <p class="form-label">근무형태</p>
                <label class="filter-any-toggle">
                  <input type="checkbox" class="work-any" <?= $has_work_selected ? '' : 'checked' ?> data-work-any>
                  <span class="toggle-indicator"></span>
                  <span class="toggle-text">전체</span>
                </label>
              </div>
              <div class="work-type-grid">
                <?php
                if (!isset($work_type_list)) {
                  $work_type_list = [];
                }
                foreach ($work_type_list as $key => $row) : ?>

                  <?php

                  $checkedAttr = in_array_return($row["cd_val"], $search_txt["work_type"], "check");
                  $isChecked = !empty($checkedAttr);
                  ?>
                  <label class="work-type-cell<?= $isChecked ? ' selected' : '' ?>">
                    <input type="checkbox" name="work_type[]" value="<?= $row["cd_val"] ?>" <?= $checkedAttr ?> data-work-checkbox>
                    <span><?= $row["cd_val"] ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="form-row">
              <?php
              $available_price = ['시급', '일급', '주급', '월급'];
              $has_price_selected = false;
              if (!empty($search_txt["price_type"])) {
                if (is_array($search_txt["price_type"])) {
                  foreach ($search_txt["price_type"] as $pt) {
                    if (trim($pt) !== "") {
                      $has_price_selected = true;
                      break;
                    }
                  }
                } else if (trim($search_txt["price_type"]) !== "") {
                  $has_price_selected = true;
                }
              }
              ?>
              <div class="price-header">
                <p class="form-label">급여형태</p>
                <label class="filter-any-toggle">
                  <input type="checkbox" class="price-any" <?= $has_price_selected ? '' : 'checked' ?> data-price-any>
                  <span class="toggle-indicator"></span>
                  <span class="toggle-text">전체</span>
                </label>
              </div>
              <div class="price-grid">
                <?php foreach ($available_price as $price_label) : ?>
                  <?php
                  $is_checked = in_array_return($price_label, $search_txt["price_type"], "check");
                  ?>
                  <label class="price-cell<?= !empty($is_checked) ? ' selected' : '' ?>">
                    <input type="checkbox" name="price_type[]" value="<?= $price_label ?>" <?= $is_checked ?> data-price-checkbox>
                    <span><?= $price_label ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="form-row">
              <?php
              $has_week_selected = false;
              if (!empty($search_txt["week"])) {
                if (is_array($search_txt["week"])) {
                  foreach ($search_txt["week"] as $wk) {
                    if (trim($wk) !== "") {
                      $has_week_selected = true;
                      break;
                    }
                  }
                } else if (trim($search_txt["week"]) !== "") {
                  $has_week_selected = true;
                }
              }
              ?>
              <div class="week-header">
                <p class="form-label">근무요일</p>
                <label class="filter-any-toggle">
                  <input type="checkbox" class="week-any" <?= $has_week_selected ? '' : 'checked' ?> data-week-any>
                  <span class="toggle-indicator"></span>
                  <span class="toggle-text">전체</span>
                </label>
              </div>
              <div class="week-grid">
                <?php
                if (!isset($week_list)) {
                  $week_list = [];
                }
                foreach ($week_list as $row) : ?>

                  <?php
                  $checkedWeek = in_array_return($row["cd_val"], $search_txt["week"], "check");
                  $isWeekChecked = !empty($checkedWeek);
                  ?>
                  <label class="week-cell<?= $isWeekChecked ? ' selected' : '' ?>">
                    <input type="checkbox" name="week[]" value="<?= $row["cd_val"] ?>" <?= $checkedWeek ?> data-week-checkbox>
                    <span><?= $row["cd_val"] ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="form-row">
              <?php
              $has_sex_selected = false;
              if (!empty($search_txt["sex"])) {
                if (is_array($search_txt["sex"])) {
                  foreach ($search_txt["sex"] as $sx) {
                    if (trim($sx) !== "") {
                      $has_sex_selected = true;
                      break;
                    }
                  }
                } else if (trim($search_txt["sex"]) !== "") {
                  $has_sex_selected = true;
                }
              }
              ?>
              <div class="gender-header">
                <p class="form-label">성별</p>
                <label class="filter-any-toggle">
                  <input type="checkbox" class="gender-any" <?= $has_sex_selected ? '' : 'checked' ?> data-gender-any>
                  <span class="toggle-indicator"></span>
                  <span class="toggle-text">전체</span>
                </label>
              </div>
              <div class="gender-grid">
                <?php $gender_options = ['남자', '여자']; ?>
                <?php foreach ($gender_options as $gender_label) : ?>
                  <?php
                  $genderChecked = in_array_return($gender_label, $search_txt["sex"], "check");
                  ?>
                  <label class="gender-cell<?= !empty($genderChecked) ? ' selected' : '' ?>">
                    <input type="checkbox" name="sex[]" value="<?= $gender_label ?>" <?= $genderChecked ?> data-gender-checkbox>
                    <span><?= $gender_label ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="form-row">
              <?php
              $has_nationality_selected = false;
              if (!empty($search_txt["nationality"])) {
                if (is_array($search_txt["nationality"])) {
                  foreach ($search_txt["nationality"] as $nat) {
                    if (trim($nat) !== "") {
                      $has_nationality_selected = true;
                      break;
                    }
                  }
                } else if (trim($search_txt["nationality"]) !== "") {
                  $has_nationality_selected = true;
                }
              }
              ?>
              <div class="nationality-header">
                <p class="form-label">국적</p>
                <label class="filter-any-toggle">
                  <input type="checkbox" class="nationality-any" <?= $has_nationality_selected ? '' : 'checked' ?> data-nationality-any>
                  <span class="toggle-indicator"></span>
                  <span class="toggle-text">전체</span>
                </label>
              </div>
              <div class="nationality-grid">
                <?php $nationality_options = ['내국인', '외국인']; ?>
                <?php foreach ($nationality_options as $nationality_label) : ?>
                  <?php
                  $nationalityChecked = in_array_return($nationality_label, $search_txt["nationality"], "check");
                  ?>
                  <label class="nationality-cell<?= !empty($nationalityChecked) ? ' selected' : '' ?>">
                    <input type="checkbox" name="nationality[]" value="<?= $nationality_label ?>" <?= $nationalityChecked ?> data-nationality-checkbox>
                    <span><?= $nationality_label ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="form-row age-row">
              <div class="form-row-header">
                <span class="form-row-title">나이</span>
                <?php
                $has_age = !empty($search_txt["sage"]) || !empty($search_txt["eage"]);
                ?>
                <label class="filter-any-toggle">
                  <input type="checkbox" name="age_any" class="age-any" <?= $has_age ? "" : "checked" ?>>
                  <span class="toggle-indicator"></span>
                  <span class="toggle-text">전체</span>
                </label>
              </div>
              <?php
              $age_ranges = [
                ['label' => '20대', 'start' => 20, 'end' => 29],
                ['label' => '30대', 'start' => 30, 'end' => 39],
                ['label' => '40대', 'start' => 40, 'end' => 49],
                ['label' => '50대', 'start' => 50, 'end' => 59],
                ['label' => '60대', 'start' => 60, 'end' => 69],
                ['label' => '70대', 'start' => 70, 'end' => 79],
              ];
              $selected_age_value = '';
              foreach ($age_ranges as $range) {
                if (
                  (string)$search_txt["sage"] === (string)$range['start'] &&
                  (string)$search_txt["eage"] === (string)$range['end']
                ) {
                  $selected_age_value = $range['start'] . '-' . $range['end'];
                  break;
                }
              }
              ?>
              <div class="age-single-select">
                <select id="ageRangeSelect">
                  <option value="">나이선택</option>
                  <?php foreach ($age_ranges as $range) : ?>
                    <option value="<?= $range['start'] ?>-<?= $range['end'] ?>" <?= $selected_age_value === ($range['start'] . '-' . $range['end']) ? 'selected' : '' ?>><?= $range['label'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <input type="hidden" name="sage" id="sageInput" value="<?= $search_txt["sage"] ?>">
              <input type="hidden" name="eage" id="eageInput" value="<?= $search_txt["eage"] ?>">
            </div>

          </div>
        </div>

        <div class="panel-footer">
          <div class="sticky-bottom view-footer">
            <button id="btnReset" type="button" class="btn btn-reset">초기화</button>
            <button id="btnSearch" type="button" class="btn btn-primary btn-search">검색</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  var currentUserId = "<?= $this->user->isLogin() ? $this->user->getUserIdx() : '' ?>";

  // 상세검색 UI 초기화 함수 (저장된 상태가 없을 때 모든 항목 초기화)
  function resetAdvancedSearchUI() {
    // 업직종 초기화
    document.querySelectorAll('[data-category-checkbox]').forEach(function(input) {
      input.checked = false;
      var cell = input.closest('.category-cell');
      if (cell) cell.classList.remove('selected');
    });
    var categoryCounter = document.getElementById('category-selected-count');
    if (categoryCounter) categoryCounter.textContent = '0';

    // 근무형태/근무요일/급여형태/성별 초기화 (전역 함수 호출 - 체크박스 해제 후 "전체" 버튼 상태 자동 갱신)
    document.querySelectorAll('[data-work-checkbox]').forEach(function(inp) {
      inp.checked = false; // 찾아낸 체크박스들을 하나씩 꺼내서(forEach), 체크를 해제(checked = false)
    });
    updateWorkTypeState();

    document.querySelectorAll('[data-week-checkbox]').forEach(function(inp) {
      inp.checked = false;
    });
    updateWeekState();

    document.querySelectorAll('[data-price-checkbox]').forEach(function(inp) {
      inp.checked = false;
    });
    updatePriceState();

    document.querySelectorAll('[data-gender-checkbox]').forEach(function(inp) {
      inp.checked = false;
    });
    updateGenderState();

    document.querySelectorAll('[data-nationality-checkbox]').forEach(function(inp) {
      inp.checked = false;
    });
    updateNationalityState();

    // 나이 초기화
    $('#ageRangeSelect').val('');
    $('input[name="sage"]').val('');
    $('input[name="eage"]').val('');
    $('input[name="age_any"]').prop('checked', true);

    // tab_list 초기화
    initCategory();
    initTabUi();
  }

  // 그룹의 모든 칸이 체크된 상태(= "전체")인지 확인: 이 경우 필터 탭을 표시하지 않음
  function isGroupAllChecked(inputSelector) {
    var $inputs = $(inputSelector);
    return $inputs.length > 0 && $inputs.filter(':checked').length === $inputs.length;
  }

  function initCategory() {
    var arr_category = "";
    var arr_work_type = "";
    var arr_price_type = "";
    var arr_week = "";
    var arr_sex = "";
    var arr_nationality = "";

    for (var icnt = 0; icnt < $("[name='category[]']:checked").length; icnt++) {
      if ($("[name='category[]']:checked").eq(icnt).val() != "") {
        if (arr_category == "") {
          arr_category = $("[name='category[]']:checked").eq(icnt).val();
        } else {
          arr_category += "^" + $("[name='category[]']:checked").eq(icnt).val();
        }
      }
    }

    for (var icnt = 0; icnt < $("[name='work_type[]']:checked").length; icnt++) {
      if ($("[name='work_type[]']:checked").eq(icnt).val() != "") {
        if (arr_work_type == "") {
          arr_work_type = $("[name='work_type[]']:checked").eq(icnt).val();
        } else {
          arr_work_type += "^" + $("[name='work_type[]']:checked").eq(icnt).val();
        }
      }
    }

    for (var icnt = 0; icnt < $("[name='price_type[]']:checked").length; icnt++) {
      if ($("[name='price_type[]']:checked").eq(icnt).val() != "") {
        if (arr_price_type == "") {
          arr_price_type = $("[name='price_type[]']:checked").eq(icnt).val();
        } else {
          arr_price_type += "^" + $("[name='price_type[]']:checked").eq(icnt).val();
        }
      }
    }

    for (var icnt = 0; icnt < $("[name='week[]']:checked").length; icnt++) {
      if ($("[name='week[]']:checked").eq(icnt).val() != "") {
        if (arr_week == "") {
          arr_week = $("[name='week[]']:checked").eq(icnt).val();
        } else {
          arr_week += "^" + $("[name='week[]']:checked").eq(icnt).val();
        }
      }
    }

    for (var icnt = 0; icnt < $("[name='sex[]']:checked").length; icnt++) {
      if ($("[name='sex[]']:checked").eq(icnt).val() != "") {
        if (arr_sex == "") {
          arr_sex = $("[name='sex[]']:checked").eq(icnt).val();
        } else {
          arr_sex += "^" + $("[name='sex[]']:checked").eq(icnt).val();
        }
      }
    }

    for (var icnt = 0; icnt < $("[name='nationality[]']:checked").length; icnt++) {
      if ($("[name='nationality[]']:checked").eq(icnt).val() != "") {
        if (arr_nationality == "") {
          arr_nationality = $("[name='nationality[]']:checked").eq(icnt).val();
        } else {
          arr_nationality += "^" + $("[name='nationality[]']:checked").eq(icnt).val();
        }
      }
    }

    search_array.category = arr_category;
    search_array.work_type = arr_work_type;
    search_array.week = arr_week;
    search_array.price_type = arr_price_type;
    search_array.sex = arr_sex;
    search_array.nationality = arr_nationality;
    search_array.sage = $("[name='sage']").val();
    search_array.eage = $("[name='eage']").val();

    for (var icnt = 0; icnt < tab_list.length; icnt++) {
      tab_list[icnt].text = "";
      tab_list[icnt].active = "";
    }

    // 지역 정보 처리 (tab_list[0]) — recruit/index_search.php와 동일 패턴
    var regions = window.selectedRegions || (typeof selectedRegions !== 'undefined' ? selectedRegions : []);

    if (regions.length > 0) {
      tab_list[0].text = regions.join(", ");
      tab_list[0].active = "on";
    }

    if (search_array.category != "") {
      tab_list[1].text = search_array.category.replaceAll("^", ",");
      tab_list[1].active = "on";
    }

    if (search_array.work_type != "" && !isGroupAllChecked('[data-work-checkbox]')) {
      tab_list[2].text = search_array.work_type.replaceAll("^", ",");
      tab_list[2].active = "on";
    }

    if (search_array.week != "" && !isGroupAllChecked('[data-week-checkbox]')) {
      if (search_array.week == "일^월^화^수^목^금^토") {
        tab_list[3].text = "일~토";
      } else {
        tab_list[3].text = search_array.week.replaceAll("^", ",");
      }
      tab_list[3].active = "on";
    }

    if (search_array.price_type != "" && !isGroupAllChecked('[data-price-checkbox]')) {
      tab_list[4].text = search_array.price_type.replaceAll("^", ",");
      tab_list[4].active = "on";
    }

    if (search_array.sex != "" && !isGroupAllChecked('[data-gender-checkbox]')) {
      tab_list[5].text = search_array.sex.replaceAll("^", ",");
      tab_list[5].active = "on";
    }

    if (search_array.sage != "" || search_array.eage != "") {
      if (search_array.sage != "" && search_array.eage == "") {
        tab_list[6].text = search_array.sage + "세 ~";
        tab_list[6].active = "on";
      } else if (search_array.sage == "" && search_array.eage != "") {
        tab_list[6].text = " ~" + search_array.eage + "대";
        tab_list[6].active = "on";
      } else if (search_array.sage != "" && search_array.eage != "") {
        tab_list[6].text = search_array.sage + "대" + " ~ " + search_array.eage + "대";
        tab_list[6].active = "on";
      }
    }
  }

  // ========================================
  // 지역 선택 (근무지역) 관련 로직 — recruit/index_search.php와 동일 패턴 (resume 페이지 전용, pageType='resume'으로 격리됨)
  // ========================================

  // 전역 변수로 선언하여 hr_list.php에서도 접근 가능하도록 함 (recruit의 job_list.php와 동일한 규약)
  if (typeof selectedRegions === 'undefined') {
    window.selectedRegions = window.selectedRegions || [];
  }
  var selectedRegions = window.selectedRegions;
  var maxRegions = 3;

  // 지역 선택 개수 카운터 갱신 함수
  function updateRegionCounter() {
    var regions = window.selectedRegions || selectedRegions || [];
    var count = regions.length;
    $("#regionCounter").html('<span style="color: #57CCAF;">' + count + '</span><span class="separator">/</span><span class="max-count">' + maxRegions + '</span>');
  }

  // 선택된 지역 표시(칩) 갱신 함수 — 전역 등록하여 main_visual.php/hr_list.php에서도 호출 가능
  function updateSelectedRegionsDisplay() {
    var container = $("#selectedRegionsContainer");
    container.empty();

    var regions = window.selectedRegions || selectedRegions || [];
    regions.forEach(function(region, index) {
      var regionItem = $('<div class="selected-region-item">' +
        '<span>' + region + '</span>' +
        '<button type="button" class="selected-region-remove" data-index="' + index + '">×</button>' +
        '</div>');
      container.append(regionItem);
    });

    updateRegionCounter();
  }
  window.updateSelectedRegionsDisplay = updateSelectedRegionsDisplay;

  // window.selectedRegions / search_addr를 기준으로 addrName, searchAreaStr,
  // addr_popup sessionStorage, (가능하면) .div2 .selbtn DOM까지 재동기화하는 함수
  // - 칩 삭제 직후, 그리고 #btnSearch 클릭 시 안전망으로 호출
  function syncAddrPopupRegionState() {
    var regions = window.selectedRegions || [];
    var addrs = (typeof search_addr !== 'undefined' && Array.isArray(search_addr)) ? search_addr : [];

    // search_addr 배열 길이를 regions 배열 길이에 맞춤 (칩 삭제로 인덱스가 어긋난 경우 대비)
    if (addrs.length > regions.length) {
      addrs = addrs.slice(0, regions.length);
    }

    // addr 문자열의 공백 개수(+1)로 step(1~3) 유추 — addr_popup2025_new.php의 data-step 규칙과 동일
    var searchArea = addrs.map(function(addr) {
      return { addr: addr, step: addr.trim().split(' ').length };
    });

    addrName = regions.length > 0 ? regions.join(', ') : '지역을 선택해 주세요';
    search_addr = addrs;
    searchAreaStr = JSON.stringify(searchArea);

    // addr_popup의 sessionStorage 갱신 (팝업이 닫혀 있어도 다음에 열 때 정확한 상태로 복원되도록)
    var pageType = window.__addrPopupPageType || 'default';
    sessionStorage.setItem('selectedRegions_' + pageType, JSON.stringify(regions));
    sessionStorage.setItem('searchAreaStr_' + pageType, searchAreaStr);
    sessionStorage.setItem('addrName_' + pageType, addrName);

    // .addrName 표시 텍스트도 함께 갱신 (main_visual.php 상단 버튼)
    $('.addrName').text(addrName).attr('data-selected', regions.length > 0 ? 'true' : 'false');

    // .div2가 존재하면(팝업 DOM은 항상 렌더링되어 있음) 항상 완전히 재구성 — 인덱스 매칭 대신 전체 재생성으로
    // "팝업이 닫혀 있어 .div2 .selbtn을 못 찾는" 상황과 무관하게 항상 정확한 상태 보장
    var $div2 = $('.div2');
    if ($div2.length) {
      $div2.find('.selbtn').remove();
      $('li[data-step][data-addr]').removeClass('addr_on');

      searchArea.forEach(function(area, idx) {
        var $selbtn = $('<div>').addClass('selbtn')
          .attr('data-step', area.step)
          .attr('data-addr', area.addr)
          .text(regions[idx] || '')
          .append($('<div>').addClass('selbtnclose').text('x'));
        $div2.append($selbtn);
        $('li[data-step="' + area.step + '"][data-addr="' + area.addr + '"]').addClass('addr_on');
      });

      if (typeof updateSelectedCount === 'function') {
        updateSelectedCount();
      }
    }
  }
  window.syncAddrPopupRegionState = syncAddrPopupRegionState;

  // 지역 칩 삭제 버튼 클릭 (이벤트 위임) — UI에서만 임시 제거, 확정은 검색 버튼에서
  $(document).on('click', '.selected-region-remove', function() {
    var index = parseInt($(this).data('index'));
    window.selectedRegions.splice(index, 1); // 선택된 지역 배열에서 제거 window.selectedRegions = ["강남", "서초", "종로"] ->window.selectedRegions = ["서초", "종로"]
    // search_addr도 같은 index에서 제거해 지역명과 주소 배열이 어긋나지 않도록 함
    if (typeof search_addr !== 'undefined' && Array.isArray(search_addr) && index < search_addr.length) {
      search_addr.splice(index, 1);
    }
    selectedRegions = window.selectedRegions;
    updateSelectedRegionsDisplay(); // 화면에서만 칩 제거
  });

  // 지역 선택 버튼 클릭 시 changeArea 팝업 열기
  $("#regionSelectBtn").click(function() {
    window.skipAddrNameUpdate = true;

    var changeAreaEl = document.getElementById('changeArea');
    if (changeAreaEl) {
      openPanel(changeAreaEl);
    }
  });

  // addr_popup2025_new.php에서 지역 선택 완료(적용하기) 시 호출되는 전역 함수
  window.onRegionSelectionComplete = function(regions) {
    window.selectedRegions = regions.slice(0, maxRegions);
    selectedRegions = window.selectedRegions;
    updateSelectedRegionsDisplay();

    initCategory();
    initTabUi();
  };

  document.addEventListener('DOMContentLoaded', function() {
    var CATEGORY_LIMIT = 3;
    var categoryInputs = document.querySelectorAll('[data-category-checkbox]');
    var counterEl = document.getElementById('category-selected-count');

    if (!categoryInputs.length || !counterEl) {
      return;
    }

    var getSelectedCount = function() {
      return Array.from(categoryInputs).filter(function(input) {
        return input.checked;
      }).length;
    };

    var updateCategoryState = function() {
      var selectedCount = 0;
      categoryInputs.forEach(function(input) {
        var cell = input.closest('.category-cell');
        if (input.checked) {
          selectedCount++;
          if (cell) {
            cell.classList.add('selected');
          }
        } else if (cell) {
          cell.classList.remove('selected');
        }
      });
      counterEl.textContent = selectedCount;
    };

    categoryInputs.forEach(function(input) {
      input.addEventListener('change', function() {
        if (this.checked) {
          var currentCount = getSelectedCount();
          if (currentCount > CATEGORY_LIMIT) {
            this.checked = false;
            updateCategoryState();
            return;
          }
        }
        updateCategoryState();
      });
    });

    updateCategoryState();
  });

  // 그룹별 상태 업데이트 함수 (전역) - loadSearchStateFromStorage 등 어디서도 호출 가능
  // 체크 그룹 공통 상태 갱신 (근무형태/근무요일/급여형태/성별/국적 공통)
  // - 칸의 selected는 checked와 1:1로 맞춤
  // - "전체"는 모든 칸이 체크됐을 때 체크됨
  // - emptyIsAll: 아무것도 체크 안 된 상태를 검색 기본값("전체")으로 볼지 여부 (true면 "전체" 체크 + 모든 칸 강조)
  function syncCheckGroup(anyBtn, inputs, cellSelector, emptyIsAll) {
    var hasSelection = Array.prototype.some.call(inputs, function(input) {
      return input.checked;
    });
    var showAll = emptyIsAll && !hasSelection; // 기본 "전체" 표시 상태
    inputs.forEach(function(input) {
      var cell = input.closest(cellSelector);
      if (cell) cell.classList.toggle('selected', showAll || input.checked);
    });
    anyBtn.checked = showAll || Array.prototype.every.call(inputs, function(input) {
      return input.checked;
    });
  }

  // 체크 그룹 이벤트 바인딩 + 초기 상태 적용
  function bindCheckGroup(anyBtnSelector, inputSelector, cellSelector) {
    var anyBtn = document.querySelector(anyBtnSelector);
    var inputs = document.querySelectorAll(inputSelector);
    if (!anyBtn || !inputs.length) return;

    inputs.forEach(function(input) {
      input.addEventListener('change', function() {
        // 기본 "전체" 상태(아무것도 체크 안 됨)에서 한 칸을 누르면 그 칸만 해제되도록 나머지 칸을 체크
        var onlyThisChecked = Array.prototype.every.call(inputs, function(inp) {
          return inp === input ? inp.checked : !inp.checked;
        });
        if (anyBtn.checked && onlyThisChecked) {
          inputs.forEach(function(inp) {
            inp.checked = (inp !== input);
          });
        }
        syncCheckGroup(anyBtn, inputs, cellSelector, false);
      });
    });
    anyBtn.addEventListener('change', function() {
      var checked = this.checked;
      inputs.forEach(function(input) {
        input.checked = checked;
      });
      syncCheckGroup(anyBtn, inputs, cellSelector, false);
    });
    syncCheckGroup(anyBtn, inputs, cellSelector, true);
  }

  function updateWorkTypeState() {
    var anyBtn = document.querySelector('[data-work-any]');
    var inputs = document.querySelectorAll('[data-work-checkbox]');
    if (!anyBtn || !inputs.length) return;
    syncCheckGroup(anyBtn, inputs, '.work-type-cell', true);
  }

  // 근무요일 상태 갱신: 칸의 selected는 checked와 1:1로 맞추고, "전체"는 아무것도 선택 안 됐을 때만 체크
  function updateWeekState() {
    var anyBtn = document.querySelector('[data-week-any]');
    var inputs = document.querySelectorAll('[data-week-checkbox]');
    if (!anyBtn || !inputs.length) return;
    syncCheckGroup(anyBtn, inputs, '.week-cell', true);
  }

  // 급여형태 상태 갱신: 칸의 selected는 checked와 1:1로 맞추고, "전체"는 아무것도 선택 안 됐을 때만 체크
  function updatePriceState() {
    var anyBtn = document.querySelector('[data-price-any]');
    var inputs = document.querySelectorAll('[data-price-checkbox]');
    if (!anyBtn || !inputs.length) return;
    syncCheckGroup(anyBtn, inputs, '.price-cell', true);
  }

  // 성별 상태 갱신: 칸의 selected는 checked와 1:1로 맞추고, "전체"는 아무것도 선택 안 됐을 때만 체크
  function updateGenderState() {
    var anyBtn = document.querySelector('[data-gender-any]');
    var inputs = document.querySelectorAll('[data-gender-checkbox]');
    if (!anyBtn || !inputs.length) return;
    syncCheckGroup(anyBtn, inputs, '.gender-cell', true);
  }

  // 국적 상태 갱신: 칸의 selected는 checked와 1:1로 맞추고, "전체"는 아무것도 선택 안 됐을 때만 체크
  function updateNationalityState() {
    var anyBtn = document.querySelector('[data-nationality-any]');
    var inputs = document.querySelectorAll('[data-nationality-checkbox]');
    if (!anyBtn || !inputs.length) return;
    syncCheckGroup(anyBtn, inputs, '.nationality-cell', true);
  }

  // 각 그룹 이벤트 바인딩 + 초기 상태 적용
  document.addEventListener('DOMContentLoaded', function() {
    bindCheckGroup('[data-work-any]', '[data-work-checkbox]', '.work-type-cell');
    bindCheckGroup('[data-week-any]', '[data-week-checkbox]', '.week-cell');

    bindCheckGroup('[data-price-any]', '[data-price-checkbox]', '.price-cell');
    bindCheckGroup('[data-gender-any]', '[data-gender-checkbox]', '.gender-cell');
    bindCheckGroup('[data-nationality-any]', '[data-nationality-checkbox]', '.nationality-cell');
  });

  document.addEventListener('DOMContentLoaded', function() {
    var ageSelect = document.getElementById('ageRangeSelect');
    var sageInput = document.getElementById('sageInput');
    var eageInput = document.getElementById('eageInput');
    var ageAnyCheckbox = document.querySelector('input[name="age_any"]');

    if (!ageSelect || !sageInput || !eageInput || !ageAnyCheckbox) {
      return;
    }

    function syncSelectFromInputs() {
      if (sageInput.value && eageInput.value) {
        ageSelect.value = sageInput.value + '-' + eageInput.value;
        ageAnyCheckbox.checked = false;
      } else {
        ageSelect.value = '';
        if (!sageInput.value && !eageInput.value) {
          ageAnyCheckbox.checked = true;
        }
      }
    }

    ageSelect.addEventListener('change', function() {
      var value = this.value;
      if (value) {
        var parts = value.split('-');
        sageInput.value = parts[0];
        eageInput.value = parts[1];
        ageAnyCheckbox.checked = false;
      } else {
        sageInput.value = '';
        eageInput.value = '';
        ageAnyCheckbox.checked = true;
      }
    });

    ageAnyCheckbox.addEventListener('change', function() {
      if (this.checked) {
        ageSelect.value = '';
        sageInput.value = '';
        eageInput.value = '';
      } else if (!ageSelect.value) {
        this.checked = true;
      }
    });

    syncSelectFromInputs();
  });

  document.addEventListener('DOMContentLoaded', function() {
    var resetBtn = document.getElementById('btnReset');
    if (!resetBtn) return;

    resetBtn.addEventListener('click', function() {
      // 백업 저장 (초기화 취소를 위해)
      window.backupSearchState = {
        selectedRegions: window.selectedRegions ? window.selectedRegions.slice() : [],
        search_addr: (typeof search_addr !== 'undefined' && Array.isArray(search_addr)) ? search_addr.slice() : [],
        category: Array.from(document.querySelectorAll('[data-category-checkbox]:checked')).map(function(el) { return el.value; }),
        work_type: Array.from(document.querySelectorAll('[data-work-checkbox]:checked')).map(function(el) { return el.value; }),
        week: Array.from(document.querySelectorAll('[data-week-checkbox]:checked')).map(function(el) { return el.value; }),
        price_type: Array.from(document.querySelectorAll('[data-price-checkbox]:checked')).map(function(el) { return el.value; }),
        sex: Array.from(document.querySelectorAll('[data-gender-checkbox]:checked')).map(function(el) { return el.value; }),
        nationality: Array.from(document.querySelectorAll('[data-nationality-checkbox]:checked')).map(function(el) { return el.value; }),
        sage: document.getElementById('sageInput') ? document.getElementById('sageInput').value : '',
        eage: document.getElementById('eageInput') ? document.getElementById('eageInput').value : ''
      };

      // UI만 초기화 (localStorage 유지, 검색 실행 안 함, 패널 닫기 안 함)
      resetAdvancedSearchUI();

      // 지역 초기화
      window.selectedRegions = [];
      selectedRegions = window.selectedRegions;
      if (typeof window.updateSelectedRegionsDisplay === 'function') {
        window.updateSelectedRegionsDisplay();
      }
      if (typeof updateRegionCounter === 'function') {
        updateRegionCounter();
      }
      if (typeof window.updateSelectedRegionsTags === 'function') {
        window.updateSelectedRegionsTags();
      }
    });
  });

  // 저장된 상세검색 조건을 로드해 UI에 반영
  function loadSearchStateFromStorage() {
    var storageKey = window.getAdvancedSearchStorageKey ? window.getAdvancedSearchStorageKey() : "jobListAdvancedSearch_resume_" + (currentUserId || "guest");
    var raw = sessionStorage.getItem(storageKey);
    if (!raw) return;

    try {
      var state = JSON.parse(raw);

      // 저장된 사용자 ID와 현재 사용자 ID가 다르면 무시 (보안)
      if (state.userId !== currentUserId) {
        return;
      }

      // 주소 정보 복원
      if (state.addrName) {
        addrName = state.addrName;
        $(".addrName").text(state.addrName).attr('data-selected', state.addrName !== '지역을 선택해 주세요' ? 'true' : 'false');
      }
      if (Array.isArray(state.search_addr)) {
        search_addr = state.search_addr.slice();
      }
      if (state.searchAreaStr) {
        searchAreaStr = state.searchAreaStr;
      }

      // selectedRegions 복원 — 저장된 state.selectedRegions을 우선 사용, 없으면 addrName에서 추출 (하위호환)
      var names = [];
      if (Array.isArray(state.selectedRegions) && state.selectedRegions.length > 0) {
        names = state.selectedRegions.slice();
      } else if (state.addrName && state.addrName !== '지역을 선택해 주세요') {
        names = state.addrName.split(',').map(function(name) {
          return name.trim();
        }).filter(function(name) {
          return name.length > 0;
        });
      }
      window.selectedRegions = names.slice(0, 3);
      selectedRegions = window.selectedRegions;
      updateSelectedRegionsDisplay();

      // 선택된 지역 태그 표시 (main_visual.php의 updateSelectedRegionsTags와 동일 로직)
      var addrBtn = document.querySelector('.addrName');
      var regionsList = document.getElementById('selected-regions-list');

      if (regionsList) {
        regionsList.innerHTML = '';

        // data-selected가 'true'이고 텍스트가 '지역을 선택해 주세요'가 아닐 때만 태그 생성
        if (addrBtn && addrBtn.getAttribute('data-selected') === 'true' &&
          addrBtn.textContent.trim() !== '지역을 선택해 주세요') {
          var regions = addrBtn.textContent.trim().split(',').map(function(r) {
            return r.trim();
          });

          regions.forEach(function(region, index) {
            if (region && region !== '지역을 선택해 주세요') {
              var tag = document.createElement('div');
              tag.className = 'region-tag';
              tag.innerHTML = region;

              // 지역 태그 클릭 시 팝업 열기
              tag.addEventListener('click', function(e) {
                e.stopPropagation();
                var changeAreaBtn = document.querySelector('a.js-toggle-panel[aria-controls="changeArea"]');
                if (changeAreaBtn) {
                  changeAreaBtn.click();
                }
              });

              regionsList.appendChild(tag);
            }
          });
        }
      }

      // 체크박스 상태 복원
      function applyCheckedValues(selector, values) {
        $(selector).each(function() {
          var isChecked = values.indexOf($(this).val()) !== -1;
          $(this).prop('checked', isChecked);
          var cell = $(this).closest('[class*="-cell"]');
          if (cell) {
            if (isChecked) {
              cell.addClass('selected');
            } else {
              cell.removeClass('selected');
            }
          }
        });
      }

      applyCheckedValues("[data-category-checkbox]", state.category || []);
      applyCheckedValues("[data-work-checkbox]", state.work_type || []);
      applyCheckedValues("[data-week-checkbox]", state.week || []);
      applyCheckedValues("[data-price-checkbox]", state.price_type || []);
      applyCheckedValues("[data-gender-checkbox]", state.sex || []);
      applyCheckedValues("[data-nationality-checkbox]", state.nationality || []);

      // "전체" 버튼 상태 동기화 (전역 함수 호출)
      updateWorkTypeState();
      updateWeekState();
      updatePriceState();
      updateGenderState();
      updateNationalityState();

      // 나이 복원
      $("#sageInput").val(state.sage || "");
      $("#eageInput").val(state.eage || "");
      if ((state.sage && state.sage.trim() !== '') || (state.eage && state.eage.trim() !== '')) {
        $('input[name="age_any"]').prop('checked', false);
        $('#ageRangeSelect').val(state.sage + '-' + state.eage);
      } else {
        $('input[name="age_any"]').prop('checked', true);
        $('#ageRangeSelect').val('');
      }

      // UI 상태 업데이트 함수들 호출 (있으면)
      if (document.querySelector('[data-category-checkbox]')) {
        var selectedCount = document.querySelectorAll('[data-category-checkbox]:checked').length;
        var counterEl = document.getElementById('category-selected-count');
        if (counterEl) counterEl.textContent = selectedCount;
      }

      // 초기 상태 설정
      initCategory();
      initTabUi();

      // 검색 조건이 있으면 AJAX로 새로 로드 (초기 렌더링 데이터 사용 안 함)
      var hasSearchCondition = (state.search_addr && state.search_addr.length > 0) ||
                                 state.category.length > 0 ||
                                 state.work_type.length > 0 ||
                                 state.week.length > 0 ||
                                 state.price_type.length > 0 ||
                                 state.sex.length > 0 ||
                                 state.nationality.length > 0 ||
                                 state.sage ||
                                 state.eage;

      if (hasSearchCondition && typeof window.getBoardList === 'function') {
        window.searchStateLoaded = true; // 플래그 설정 (initLoadData에서 중복 호출 방지)
        // 검색 조건이 있으면 기존 초기 렌더링 데이터 제거 (깜빡임 방지)
        if (typeof jQuery !== 'undefined' && jQuery('#ajaxList').length) {
          jQuery('#ajaxList').empty();
        }
        setTimeout(function() {
          window.getBoardList('');
        }, 100);
      }
    } catch (e) {

    }
  }
  // 현재 검색 조건을 세션 스토리지에 저장 (main_visual.php에서 검색 버튼 클릭 시 호출)
  function saveSearchStateToStorage() {
    try {
      var categoryArray = search_array.category ? search_array.category.split("^").filter(function(cat) {
        return cat.trim() !== '';
      }) : [];
      var workTypeArray = search_array.work_type ? search_array.work_type.split("^").filter(function(wt) {
        return wt.trim() !== '';
      }) : [];
      var weekArray = search_array.week ? search_array.week.split("^").filter(function(w) {
        return w.trim() !== '';
      }) : [];
      var priceTypeArray = search_array.price_type ? search_array.price_type.split("^").filter(function(pt) {
        return pt.trim() !== '';
      }) : [];
      var sexArray = search_array.sex ? search_array.sex.split("^").filter(function(s) {
        return s.trim() !== '';
      }) : [];
      var nationalityArray = search_array.nationality ? search_array.nationality.split("^").filter(function(n) {
        return n.trim() !== '';
      }) : [];

      var state = {
        userId: currentUserId,
        addrName: (typeof addrName !== 'undefined') ? addrName : '',
        searchAreaStr: (typeof searchAreaStr !== 'undefined') ? searchAreaStr : '',
        search_addr: (typeof search_addr !== 'undefined' && Array.isArray(search_addr)) ? search_addr.slice() : [],
        selectedRegions: Array.isArray(window.selectedRegions) ? window.selectedRegions.slice() : [],
        category: categoryArray,
        work_type: workTypeArray,
        week: weekArray,
        price_type: priceTypeArray,
        sex: sexArray,
        nationality: nationalityArray,
        sage: search_array.sage || "",
        eage: search_array.eage || ""
      };

      var storageKey = window.getAdvancedSearchStorageKey ? window.getAdvancedSearchStorageKey() : "jobListAdvancedSearch_resume_" + (currentUserId || "guest");
      sessionStorage.setItem(storageKey, JSON.stringify(state));

    } catch (e) {

    }
  }

  function initTabUi() {
    $("#tab_list").html("");
    // tab_list[0]은 지역(area) 정보이며 main_visual.php의 addrName/selected-regions-list에서 별도 표시되므로 1부터 순회
    for (var icnt = 1; icnt < tab_list.length; icnt++) {
      var $clone = $("#tpl_category").clone();
      var $content = $clone.html();
      $content = $content.replaceAll("{{active}}", tab_list[icnt].active);
      $content = $content.replaceAll("{{filter_key}}", tab_list[icnt].key || "");
      if (tab_list[icnt].active == "on") {
        $content = $content.replaceAll("{{category_name}}", tab_list[icnt].text);
      } else {
        $content = $content.replaceAll("{{category_name}}", tab_list[icnt].df);
      }
      $("#tab_list").append($content);
    }
  }

  // 전역 함수로 등록 (main_visual.php에서 접근 가능하도록)
  window.initCategory = initCategory;
  window.initTabUi = initTabUi;
  window.loadSearchStateFromStorage = loadSearchStateFromStorage;
  window.saveSearchStateToStorage = saveSearchStateToStorage;

  // 페이지 로드 완료 후 저장된 검색 조건 복원
  $(document).ready(function() {
    try {
      if (typeof loadSearchStateFromStorage === 'function') {
        loadSearchStateFromStorage();
      }
     
    } catch (e) {
      console.error('[index_search2025_new.php] 복원 중 오류:', e);
    }
  });

  $("[name='workingDay']").click(function() {
    $("[name='week[]']").prop("checked", $(this).is(":checked"));
  });

  // body 스타일 초기화 함수 (재사용) - iOS 웹뷰 대응 (개선 버전)
  function restoreBodyScroll() {
    forceRestoreBodyScroll();
  }

  // iOS 웹뷰 대응: 강제 스크롤 복원 함수 (개선 버전)
  // - removeProperty() 사용으로 더 확실한 스타일 제거
  // - 조건부 overflow 복원 (잠금 흔적이 있을 때만)
  // - 실제 스크롤 컨테이너에도 -webkit-overflow-scrolling 적용
  function forceRestoreBodyScroll() {
    const b = document.body;
    const h = document.documentElement;

    // 잠금 흔적이 없으면 조기 리턴 (선택적 - 주석 처리하면 항상 복원)
    // const hasLock = b && (b.classList.contains('no-scroll') || b.style.position === 'fixed');
    // if (!hasLock && (!b || b.style.overflow !== 'hidden')) return;

    // 인라인 스타일 제거 (removeProperty로 더 확실하게)
    const propsToRemove = ['position', 'top', 'left', 'right', 'overflow', 'width', 'height'];

    if (b) {
      propsToRemove.forEach(p => {
        if (b.style.getPropertyValue(p)) {
          b.style.removeProperty(p);
        }
      });
      b.classList.remove('no-scroll');
      // 다른 패널이 열려있으면 panel-open 유지
      var hasOtherOpenPanel = document.querySelector('.layer-panel.on') !== null; // 다른 패널이 열려있는지 체크
      if (!hasOtherOpenPanel) { // 다른 패널이 없으면 panel-open 제거
        b.classList.remove('panel-open');
      }
    }

    if (h) {
      propsToRemove.forEach(p => {
        if (h.style.getPropertyValue(p)) {
          h.style.removeProperty(p);
        }
      });
    }

    // 조건부 overflow 복원 (잠금 흔적이 있거나 기본이 hidden인 경우만)
    const shouldForceOverflow = (b && (b.classList.contains('no-scroll') ||
        b.style.position === 'fixed' ||
        getComputedStyle(b).overflow === 'hidden')) ||
      (h && getComputedStyle(h).overflow === 'hidden');

    if (shouldForceOverflow) {
      $("body").css({
        "overflow": "auto",
        "overflow-y": "auto",
        "-webkit-overflow-scrolling": "touch"
      }).removeClass('no-scroll');

      $("html").css({
        "overflow": "auto",
        "overflow-y": "auto"
      });
    }

    // 실제 스크롤 컨테이너에도 iOS 관성 스크롤 적용
    const scrollContainers = ['.page-recruit', '.page-content', '#app'];
    scrollContainers.forEach(selector => {
      const $container = $(selector);
      if ($container.length && $container.css('overflow') !== 'hidden') {
        $container.css('-webkit-overflow-scrolling', 'touch');
      }
    });
  }

  // panel 열릴 때 검색 상태 복원
  $(document).on('click', '.btn-option', function(e) {
    setTimeout(function() {
      var storageKey = window.getAdvancedSearchStorageKey ? window.getAdvancedSearchStorageKey() : "jobListAdvancedSearch_resume_" + (currentUserId || "guest");
      var hasSavedState = sessionStorage.getItem(storageKey);
      if (hasSavedState) {
        // 저장된 검색 상태가 있으면 localStorage 기준으로 복원
        loadSearchStateFromStorage();
      } else {
        // 저장된 상태 없으면 search_array 기준으로 복원
        restoreCheckboxStateFromSearch();
      }
    }, 100);
  });

  function restoreCheckboxStateFromSearch() {
    // search_array 기반으로 체크박스 복원

    // 업직종 복원
    if (search_array.category && search_array.category.trim() !== '') {
      var categories = search_array.category.split('^').filter(function(cat) {
        return cat.trim() !== '';
      });

      $('[data-category-checkbox]').each(function() {
        var $checkbox = $(this);
        var isChecked = categories.indexOf($checkbox.val()) !== -1;
        $checkbox.prop('checked', isChecked);
        var $label = $checkbox.closest('label');
        if (isChecked) {
          $label.addClass('selected');
        } else {
          $label.removeClass('selected');
        }
      });

      // 카운터 업데이트
      var counterEl = document.getElementById('category-selected-count');
      if (counterEl) {
        counterEl.textContent = categories.length;
      }
    }

    // 근무형태 체크박스 복원
    var workTypes = search_array.work_type ? search_array.work_type.split('^').filter(function(v) {
      return v.trim() !== '';
    }) : [];
    $('[data-work-checkbox]').each(function() {
      $(this).prop('checked', workTypes.indexOf($(this).val()) !== -1);
    });
    updateWorkTypeState();

    // 근무요일 체크박스 복원
    var weeks = search_array.week ? search_array.week.split('^').filter(function(v) {
      return v.trim() !== '';
    }) : [];
    $('[data-week-checkbox]').each(function() {
      $(this).prop('checked', weeks.indexOf($(this).val()) !== -1);
    });
    updateWeekState();

    // 급여형태 체크박스 복원
    var prices = search_array.price_type ? search_array.price_type.split('^').filter(function(v) {
      return v.trim() !== '';
    }) : [];
    $('[data-price-checkbox]').each(function() {
      $(this).prop('checked', prices.indexOf($(this).val()) !== -1);
    });
    updatePriceState();

    // 성별 체크박스 복원
    var sexes = search_array.sex ? search_array.sex.split('^').filter(function(v) {
      return v.trim() !== '';
    }) : [];
    $('[data-gender-checkbox]').each(function() {
      $(this).prop('checked', sexes.indexOf($(this).val()) !== -1);
    });
    updateGenderState();

    // 국적 체크박스 복원
    var nationalities = search_array.nationality ? search_array.nationality.split('^').filter(function(v) {
      return v.trim() !== '';
    }) : [];
    $('[data-nationality-checkbox]').each(function() {
      $(this).prop('checked', nationalities.indexOf($(this).val()) !== -1);
    });
    updateNationalityState();

    // 나이 복원
    $('#sageInput').val(search_array.sage || '');
    $('#eageInput').val(search_array.eage || '');
    if ((search_array.sage && search_array.sage.trim() !== '') || (search_array.eage && search_array.eage.trim() !== '')) {
      $('input[name="age_any"]').prop('checked', false);
      $('#ageRangeSelect').val(search_array.sage + '-' + search_array.eage);
    } else {
      $('input[name="age_any"]').prop('checked', true);
      $('#ageRangeSelect').val('');
    }

    // 지역 UI 복원 (window.selectedRegions는 hr_list.php의 sessionStorage 복원 로직에서 이미 최신 상태)
    selectedRegions = window.selectedRegions || [];
    if (typeof updateSelectedRegionsDisplay === 'function') {
      updateSelectedRegionsDisplay();
    }
  }

  $(".btn-close-panel").click(function() {
    if (window.backupSearchState) {
      // 백업 복원 (초기화 취소)
      window.selectedRegions = window.backupSearchState.selectedRegions.slice();
      selectedRegions = window.selectedRegions;
      // 지역명과 같은 시점의 주소 배열로 되돌려 정합성 유지
      if (Array.isArray(window.backupSearchState.search_addr)) {
        search_addr = window.backupSearchState.search_addr.slice();
      }
      updateSelectedRegionsDisplay();

      // 체크박스 복원 헬퍼
      var restoreCheckboxes = function(selector, values) {
        document.querySelectorAll(selector).forEach(function(input) {
          input.checked = values.indexOf(input.value) !== -1;
        });
      };
      restoreCheckboxes('[data-category-checkbox]', window.backupSearchState.category || []);
      restoreCheckboxes('[data-work-checkbox]', window.backupSearchState.work_type || []);
      restoreCheckboxes('[data-week-checkbox]', window.backupSearchState.week || []);
      restoreCheckboxes('[data-price-checkbox]', window.backupSearchState.price_type || []);
      restoreCheckboxes('[data-gender-checkbox]', window.backupSearchState.sex || []);
      restoreCheckboxes('[data-nationality-checkbox]', window.backupSearchState.nationality || []);

      // 나이 복원
      document.getElementById('sageInput').value = window.backupSearchState.sage || '';
      document.getElementById('eageInput').value = window.backupSearchState.eage || '';

      // UI 상태 갱신 (resume 버전 함수명 사용)
      updateWorkTypeState();
      updateWeekState();
      updatePriceState();
      updateGenderState();
      updateNationalityState();

      // 카테고리 카운터 갱신
      var counterEl = document.getElementById('category-selected-count');
      if (counterEl) {
        counterEl.textContent = document.querySelectorAll('[data-category-checkbox]:checked').length;
      }

      // tab_list 갱신
      initCategory();
      initTabUi();

      // 지역 UI 갱신
      if (typeof window.updateSelectedRegionsDisplay === 'function') {
        window.updateSelectedRegionsDisplay();
      }
      if (typeof window.updateSelectedRegionsTags === 'function') {
        window.updateSelectedRegionsTags();
      }

      // 백업 초기화
      window.backupSearchState = null;
    } else {
      // 기존 로직: localStorage에서 복원 (백업이 없을 때만)
      var storageKey = window.getAdvancedSearchStorageKey ? window.getAdvancedSearchStorageKey() : "jobListAdvancedSearch_resume_" + (currentUserId || "guest");
      var hasSavedState = sessionStorage.getItem(storageKey);

      if (hasSavedState) {
        // 저장된 검색 상태가 있으면 UI를 저장된 상태로 복원
        loadSearchStateFromStorage();
      } else {
        // 저장된 상태가 없으면 UI 초기화 (모든 임시 선택사항 제거)
        resetAdvancedSearchUI();
      }
    }

    var $panel = $("#advancedSearch")[0];
    $("#advancedSearch").removeClass("on");

    // closePanel 함수 호출 (main.js의 함수 활용)
    if (typeof window.closePanel === 'function' && $panel) {
      window.closePanel($panel);
    }

    // body 스타일 복구
    setTimeout(function() {
      restoreBodyScroll();
    }, 50);
    setTimeout(function() {
      restoreBodyScroll();
    }, 200);
  });

  $("[name='sage']").change(function() {
    if ($("[name='sage']").val() != "" && $("[name='eage']").val() != "") {
      if ($("[name='sage']").val() > $("[name='eage']").val()) {
        $("[name='eage']").val($("[name='sage']").val());
      }
    }
  });

  $("[name='eage']").change(function() {
    if ($("[name='sage']").val() != "" && $("[name='eage']").val() != "") {
      if ($("[name='eage']").val() < $("[name='sage']").val()) {
        $("[name='sage']").val($("[name='eage']").val());
      }
    }
  });

  $("#btnSearch").click(function() {
    // 백업 삭제 (초기화 확정)
    window.backupSearchState = null;

    list_page = 1;

    // 팝업을 닫기 전에 먼저 검색 조건을 읽어야 함 (closePanel이 체크박스를 초기화함)
    // initCategory()는 체크박스 상태를 읽어서 search_array를 설정하므로 팝업 닫기 전에 호출
    initCategory();
    initTabUi();

    // 안전망: search_addr/addr_popup sessionStorage가 최신 window.selectedRegions와
    // 어긋나 있을 수 있으므로(팝업을 안 열고 칩만 삭제한 경우 등) 검색 직전 재동기화
    if (typeof syncAddrPopupRegionState === 'function') {
      syncAddrPopupRegionState();
    }

    // 지역 UI 업데이트 (main_visual.php의 상단 지역 태그와 동기화)
    if (typeof window.updateSelectedRegionsTags === 'function') {
      window.updateSelectedRegionsTags();
    }

    // 검색 조건 저장
    saveSearchStateToStorage();

    // 검색 실행 (팝업 닫기 전에 먼저 실행 - closePanel이 체크박스를 초기화함)
    $(list_id).html("");
    getBoardList("Y");

    // 팝업 닫기 (검색 실행 후에 닫기)
    // closePanel을 호출하지 않고 수동으로 처리 (closePanel이 체크박스를 초기화함)
    var $panel = $("#advancedSearch")[0];
    if ($panel) {
      $panel.classList.remove('on');
      document.body.style.overflow = '';
      $panel.scrollTo(0, 0);
    }

    // body 스타일 즉시 초기화
    restoreBodyScroll();

    // 여러 시점에서 body 스타일 확인 및 복구
    setTimeout(restoreBodyScroll, 50);
    setTimeout(restoreBodyScroll, 100);
    setTimeout(restoreBodyScroll, 200);
    setTimeout(restoreBodyScroll, 500);

    // 검색 완료 후에도 확인
    setTimeout(restoreBodyScroll, 1000);
  });

  // [스크롤 디버그] 칸(태그) 선택/해제 시 스크롤 위치 로그 - 원인 확인 후 제거
  (function() {
    if (typeof BrowserLogger === 'undefined') return;

    var CELL_SELECTOR = '.work-type-cell, .price-cell, .week-cell, .gender-cell, .nationality-cell';
    var recentInteraction = 0; // 마지막 칸 입력 시각 (이 시각 이후 1초 동안의 스크롤/포커스만 기록)

    // 현재 스크롤 위치 스냅샷 (존재하는 후보 요소만 기록)
    function getScrollSnapshot() {
      var snap = {
        winY: window.pageYOffset,
        docTop: document.documentElement.scrollTop,
        bodyTop: document.body.scrollTop
      };
      ['#advancedSearch', '#advancedSearch .panel-content', '.page-recruit', '.page-content', '#app'].forEach(function(sel) {
        var el = document.querySelector(sel);
        if (el) snap[sel] = el.scrollTop;
      });
      // 하단 고정 영역(footer)이 포커스 요소를 가리는지 확인용 rect
      var footer = document.querySelector('#advancedSearch .panel-footer');
      if (footer) {
        var fr = footer.getBoundingClientRect();
        snap.footerTop = fr.top;
        snap.footerBottom = fr.bottom;
      }
      // 스크롤 컨테이너(panel-content)와 상위 영역의 화면상 위치 (포커스 요소가 보이는 영역 판단용)
      ['#advancedSearch .panel-container', '#advancedSearch .panel-body', '#advancedSearch .panel-content'].forEach(function(sel) {
        var box = document.querySelector(sel);
        if (!box) return;
        var br = box.getBoundingClientRect();
        snap[sel + '.top'] = br.top;
        snap[sel + '.bottom'] = br.bottom;
        snap[sel + '.clientH'] = box.clientHeight;
        snap[sel + '.scrollH'] = box.scrollHeight;
      });
      snap.innerH = window.innerHeight;
      snap.vvH = window.visualViewport ? window.visualViewport.height : null;
      var active = document.activeElement;
      snap.active = active ? active.tagName + (active.name ? '[' + active.name + ']' : '') : '';
      return snap;
    }

    // 터치/클릭 직전 상태 기록 (pointerdown은 click/change보다 먼저 발생)
    var lastBefore = null;
    document.addEventListener('pointerdown', function(e) {
      var cell = e.target.closest ? e.target.closest(CELL_SELECTOR) : null;
      if (!cell) return;
      recentInteraction = Date.now();
      lastBefore = getScrollSnapshot();
      BrowserLogger.info('[스크롤디버그] pointerdown', {
        pointerType: e.pointerType,
        cell: cell.textContent.trim(),
        before: lastBefore
      });
    }, true);

    // 체크 상태 변경 직후 + 지연 시점의 스크롤 위치 기록
    document.addEventListener('change', function(e) {
      var input = e.target;
      if (!input.closest || !input.closest(CELL_SELECTOR)) return;
      BrowserLogger.info('[스크롤디버그] change', {
        name: input.name,
        value: input.value,
        checked: input.checked,
        before: lastBefore,
        immediate: getScrollSnapshot()
      });
      [0, 100, 300].forEach(function(ms) {
        setTimeout(function() {
          BrowserLogger.info('[스크롤디버그] after ' + ms + 'ms', getScrollSnapshot());
        }, ms);
      });
    }, true);

    // 칸 입력 직후 1초 안에 발생한 포커스 이동 기록
    document.addEventListener('focusin', function(e) {
      if (Date.now() - recentInteraction > 1000) return;
      var rect = e.target.getBoundingClientRect();
      BrowserLogger.info('[스크롤디버그] focusin', {
        tag: e.target.tagName,
        name: e.target.name || '',
        rectTop: rect.top,
        rectBottom: rect.bottom
      });
    }, true);

    // 칸 입력 직후 1초 안에 발생한 스크롤 이벤트 기록 (어느 요소가 스크롤됐는지 포함)
    window.addEventListener('scroll', function(e) {
      if (Date.now() - recentInteraction > 1000) return;
      var target = e.target === document ? 'document' : String(e.target.id || e.target.className || e.target.tagName);
      BrowserLogger.info('[스크롤디버그] scroll', {
        target: target,
        snapshot: getScrollSnapshot()
      });
    }, true);
  })();

</script>