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
  .gender-cell {
    height: 56px;
    min-height: 56px;
    max-height: 56px;
  }

  /* 반응형 대응 */
  @media (max-width: 480px) {
    .work-type-group {
      gap: 6px;
    }
  }
</style>
<div id="advancedSearch" class="layer-panel" tabindex="-1">
  <div class="panel-container">
    <div class="panel-header top-bar">
      <h2 class="panel-title">인재정보 상세검색</h2>
      <button type="button" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
    </div>
    <div class="panel-body">
      <div class="panel-content">

        <div class="section advanced-search-form">
          <div class="container">

            <div class="form-row">
              <div class="category-label-row">
                <p class="form-label">업직종</p>
                <span class="category-counter"><span id="category-selected-count">0</span><span class="category-limit">/3</span></span>
              </div>
              <div class="category-grid">
                <div class="category-grid__body">
                  <?php
                  $desired_categories = ['생산', '물류', '건설', '운전배달', '식당', '서비스', '사무직', '상담', '기타'];
                  foreach ($desired_categories as $category_name) {
                    $found_category = null;
                    foreach ($wrok_list as $row) {
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
                <button type="button" class="work-type-any <?= $has_work_selected ? '' : 'selected' ?>" data-work-any>
                  <span>전체</span>
                </button>
              </div>
              <div class="work-type-grid">
                <?php foreach ($work_type_list as $key => $row) : ?>
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
                <button type="button" class="price-any <?= $has_price_selected ? '' : 'selected' ?>" data-price-any>
                  <span>전체</span>
                </button>
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
                <button type="button" class="week-any <?= $has_week_selected ? '' : 'selected' ?>" data-week-any>
                  <span>전체</span>
                </button>
              </div>
              <div class="week-grid">
                <?php foreach ($week_list as $row) : ?>
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
                <button type="button" class="gender-any <?= $has_sex_selected ? '' : 'selected' ?>" data-gender-any>
                  <span>전체</span>
                </button>
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

            <div class="form-row age-row">
              <div class="form-row-header">
                <span class="form-row-title">나이</span>
                <?php
                $has_age = !empty($search_txt["sage"]) || !empty($search_txt["eage"]);
                ?>
                <label class="work-type-any-toggle">
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

<script>
  function initCategory() {
    var arr_category = "";
    var arr_work_type = "";
    var arr_price_type = "";
    var arr_week = "";
    var arr_sex = "";

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

    search_array.category = arr_category;
    search_array.work_type = arr_work_type;
    search_array.week = arr_week;
    search_array.price_type = arr_price_type;
    search_array.sex = arr_sex;
    search_array.sage = $("[name='sage']").val();
    search_array.eage = $("[name='eage']").val();

    for (var icnt = 0; icnt < tab_list.length; icnt++) {
      tab_list[icnt].text = "";
      tab_list[icnt].active = "";
    }

    if (search_array.category != "") {
      tab_list[0].text = search_array.category.replaceAll("^", ",");
      tab_list[0].active = "on";
    }

    if (search_array.work_type != "") {
      tab_list[1].text = search_array.work_type.replaceAll("^", ",");
      tab_list[1].active = "on";
    }

    if (search_array.week != "") {
      if (search_array.week == "일^월^화^수^목^금^토") {
        tab_list[2].text = "일~토";
      } else {
        tab_list[2].text = search_array.week.replaceAll("^", ",");
      }
      tab_list[2].active = "on";
    }

    if (search_array.price_type != "") {
      tab_list[3].text = search_array.price_type.replaceAll("^", ",");
      tab_list[3].active = "on";
    }

    if (search_array.sex != "") {
      tab_list[4].text = search_array.sex.replaceAll("^", ",");
      tab_list[4].active = "on";
    }

    if (search_array.sage != "" || search_array.eage != "") {
      if (search_array.sage != "" && search_array.eage == "") {
        tab_list[5].text = search_array.sage + "세 ~";
        tab_list[5].active = "on";
      } else if (search_array.sage == "" && search_array.eage != "") {
        tab_list[5].text = " ~" + search_array.eage + "세";
        tab_list[5].active = "on";
      } else if (search_array.sage != "" && search_array.eage != "") {
        tab_list[5].text = search_array.sage + "세" + " ~ " + search_array.eage + "세";
        tab_list[5].active = "on";
      }
    }
  }

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

  document.addEventListener('DOMContentLoaded', function() {
    var workAnyBtn = document.querySelector('[data-work-any]');
    var workInputs = document.querySelectorAll('[data-work-checkbox]');

    if (!workAnyBtn || !workInputs.length) {
      return;
    }

    var updateWorkTypeState = function() {
      var hasSelection = false;
      workInputs.forEach(function(input) {
        var cell = input.closest('.work-type-cell');
        if (input.checked) {
          hasSelection = true;
          if (cell) {
            cell.classList.add('selected');
          }
        } else if (cell) {
          cell.classList.remove('selected');
        }
      });

      if (hasSelection) {
        workAnyBtn.classList.remove('selected');
      } else {
        workAnyBtn.classList.add('selected');
      }
    };

    workInputs.forEach(function(input) {
      input.addEventListener('change', function() {
        var totalCount = workInputs.length;
        var checkedCount = Array.from(workInputs).filter(function(inp) {
          return inp.checked;
        }).length;

        // 모든 항목이 선택되면 "전체" 체크하고 모든 개별 항목 해제
        if (checkedCount === totalCount) {
          workAnyBtn.classList.add('selected');
          workInputs.forEach(function(inp) {
            inp.checked = false;
            var cell = inp.closest('.work-type-cell');
            if (cell) {
              cell.classList.remove('selected');
            }
          });
        } else {
          updateWorkTypeState();
        }
      });
    });

    workAnyBtn.addEventListener('click', function() {
      if (workAnyBtn.classList.contains('selected')) {
        return;
      }
      workInputs.forEach(function(input) {
        input.checked = false;
      });
      updateWorkTypeState();
    });

    updateWorkTypeState();
  });

  document.addEventListener('DOMContentLoaded', function() {
    var weekAnyBtn = document.querySelector('[data-week-any]');
    var weekInputs = document.querySelectorAll('[data-week-checkbox]');

    if (!weekAnyBtn || !weekInputs.length) {
      return;
    }

    var updateWeekState = function() {
      var hasSelection = false;
      weekInputs.forEach(function(input) {
        var cell = input.closest('.week-cell');
        if (input.checked) {
          hasSelection = true;
          if (cell) {
            cell.classList.add('selected');
          }
        } else if (cell) {
          cell.classList.remove('selected');
        }
      });

      if (hasSelection) {
        weekAnyBtn.classList.remove('selected');
      } else {
        weekAnyBtn.classList.add('selected');
      }
    };

    weekInputs.forEach(function(input) {
      input.addEventListener('change', function() {
        var totalCount = weekInputs.length;
        var checkedCount = Array.from(weekInputs).filter(function(inp) {
          return inp.checked;
        }).length;

        // 모든 항목이 선택되면 "전체" 체크하고 모든 개별 항목 해제
        if (checkedCount === totalCount) {
          weekAnyBtn.classList.add('selected');
          weekInputs.forEach(function(inp) {
            inp.checked = false;
            var cell = inp.closest('.week-cell');
            if (cell) {
              cell.classList.remove('selected');
            }
          });
        } else {
          updateWeekState();
        }
      });
    });

    weekAnyBtn.addEventListener('click', function() {
      if (weekAnyBtn.classList.contains('selected')) {
        return;
      }
      weekInputs.forEach(function(input) {
        input.checked = false;
      });
      updateWeekState();
    });

    updateWeekState();
  });

  document.addEventListener('DOMContentLoaded', function() {
    var priceAnyBtn = document.querySelector('[data-price-any]');
    var priceInputs = document.querySelectorAll('[data-price-checkbox]');

    if (!priceAnyBtn || !priceInputs.length) {
      return;
    }

    var updatePriceState = function() {
      var hasSelection = false;
      priceInputs.forEach(function(input) {
        var cell = input.closest('.price-cell');
        if (input.checked) {
          hasSelection = true;
          if (cell) {
            cell.classList.add('selected');
          }
        } else if (cell) {
          cell.classList.remove('selected');
        }
      });

      if (hasSelection) {
        priceAnyBtn.classList.remove('selected');
      } else {
        priceAnyBtn.classList.add('selected');
      }
    };

    priceInputs.forEach(function(input) {
      input.addEventListener('change', function() {
        var totalCount = priceInputs.length;
        var checkedCount = Array.from(priceInputs).filter(function(inp) {
          return inp.checked;
        }).length;

        // 모든 항목이 선택되면 "전체" 체크하고 모든 개별 항목 해제
        if (checkedCount === totalCount) {
          priceAnyBtn.classList.add('selected');
          priceInputs.forEach(function(inp) {
            inp.checked = false;
            var cell = inp.closest('.price-cell');
            if (cell) {
              cell.classList.remove('selected');
            }
          });
        } else {
          updatePriceState();
        }
      });
    });

    priceAnyBtn.addEventListener('click', function() {
      if (priceAnyBtn.classList.contains('selected')) {
        return;
      }
      priceInputs.forEach(function(input) {
        input.checked = false;
      });
      updatePriceState();
    });

    updatePriceState();
  });

  document.addEventListener('DOMContentLoaded', function() {
    var genderAnyBtn = document.querySelector('[data-gender-any]');
    var genderInputs = document.querySelectorAll('[data-gender-checkbox]');

    if (!genderAnyBtn || !genderInputs.length) {
      return;
    }

    var updateGenderState = function() {
      var hasSelection = false;
      genderInputs.forEach(function(input) {
        var cell = input.closest('.gender-cell');
        if (input.checked) {
          hasSelection = true;
          if (cell) {
            cell.classList.add('selected');
          }
        } else if (cell) {
          cell.classList.remove('selected');
        }
      });

      if (hasSelection) {
        genderAnyBtn.classList.remove('selected');
      } else {
        genderAnyBtn.classList.add('selected');
      }
    };

    genderInputs.forEach(function(input) {
      input.addEventListener('change', function() {
        var totalCount = genderInputs.length;
        var checkedCount = Array.from(genderInputs).filter(function(inp) {
          return inp.checked;
        }).length;

        // 모든 항목이 선택되면 "전체" 체크하고 모든 개별 항목 해제
        if (checkedCount === totalCount) {
          genderAnyBtn.classList.add('selected');
          genderInputs.forEach(function(inp) {
            inp.checked = false;
            var cell = inp.closest('.gender-cell');
            if (cell) {
              cell.classList.remove('selected');
            }
          });
        } else {
          updateGenderState();
        }
      });
    });

    genderAnyBtn.addEventListener('click', function() {
      if (genderAnyBtn.classList.contains('selected')) {
        return;
      }
      genderInputs.forEach(function(input) {
        input.checked = false;
      });
      updateGenderState();
    });

    updateGenderState();
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
      document.querySelectorAll('[data-category-checkbox]').forEach(function(input) {
        input.checked = false;
        var cell = input.closest('.category-cell');
        if (cell) {
          cell.classList.remove('selected');
        }
      });
      var categoryCounter = document.getElementById('category-selected-count');
      if (categoryCounter) {
        categoryCounter.textContent = '0';
      }

      document.querySelectorAll('[data-work-checkbox]').forEach(function(input) {
        input.checked = false;
        var cell = input.closest('.work-type-cell');
        if (cell) {
          cell.classList.remove('selected');
        }
      });
      var workAnyBtn = document.querySelector('[data-work-any]');
      if (workAnyBtn) {
        workAnyBtn.classList.add('selected');
      }

      document.querySelectorAll('[data-week-checkbox]').forEach(function(input) {
        input.checked = false;
        var cell = input.closest('.week-cell');
        if (cell) {
          cell.classList.remove('selected');
        }
      });
      var weekAnyBtn = document.querySelector('[data-week-any]');
      if (weekAnyBtn) {
        weekAnyBtn.classList.add('selected');
      }

      document.querySelectorAll('[data-price-checkbox]').forEach(function(input) {
        input.checked = false;
        var cell = input.closest('.price-cell');
        if (cell) {
          cell.classList.remove('selected');
        }
      });
      var priceAnyBtn = document.querySelector('[data-price-any]');
      if (priceAnyBtn) {
        priceAnyBtn.classList.add('selected');
      }

      document.querySelectorAll('[data-gender-checkbox]').forEach(function(input) {
        input.checked = false;
        var cell = input.closest('.gender-cell');
        if (cell) {
          cell.classList.remove('selected');
        }
      });
      var genderAnyBtn = document.querySelector('[data-gender-any]');
      if (genderAnyBtn) {
        genderAnyBtn.classList.add('selected');
      }

      $('#ageRangeSelect').val('').trigger('change');
      $('input[name="sage"]').val('');
      $('input[name="eage"]').val('');
      $('input[name="age_any"]').prop('checked', true);

      initCategory();
      initTabUi();
    });
  });

  function initTabUi() {
    $("#tab_list").html("");
    for (var icnt = 0; icnt < tab_list.length; icnt++) {
      var $clone = $("#tpl_category").clone();
      var $content = $clone.html();
      $content = $content.replaceAll("{{active}}", tab_list[icnt].active);
      if (tab_list[icnt].active == "on") {
        $content = $content.replaceAll("{{category_name}}", tab_list[icnt].text);
      } else {
        $content = $content.replaceAll("{{category_name}}", tab_list[icnt].df);
      }
      $("#tab_list").append($content);
    }
  }

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

  $(".btn-close-panel").click(function() {
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
    list_page = 1;

    // 팝업을 닫기 전에 먼저 검색 조건을 읽어야 함 (closePanel이 체크박스를 초기화함)
    // initCategory()는 체크박스 상태를 읽어서 search_array를 설정하므로 팝업 닫기 전에 호출
    initCategory();
    initTabUi();

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
</script>