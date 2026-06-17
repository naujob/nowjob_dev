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

   /* "전체" 선택 시 개별 항목들의 시각적 강조 */
   .form-toggle-btn2.work-type-all-selected .btn,
   .form-toggle-btn2.week-all-selected .btn,
   .form-toggle-btn2.price-type-all-selected .btn,
   .form-toggle-btn2.sex-all-selected .btn,
   .form-toggle-btn2.nationality-all-selected .btn {
     background-color: #57CCAF;
     color: white;
   }

   /* 지역 선택 UI 스타일 */
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

   /* 상세 검색 패널 내용 아래에 고정 바 공간 확보 */
   #advancedSearch .panel-content {
     padding-bottom: calc(72px + constant(safe-area-inset-bottom));
     padding-bottom: calc(72px + env(safe-area-inset-bottom));
   }

   .advanced-search-form .container {
     padding-top: 2.125rem;
     padding-bottom: calc(2.625rem + 72px + constant(safe-area-inset-bottom));
     padding-bottom: calc(50px + env(safe-area-inset-bottom));
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
       <h2 class="panel-title">채용정보 상세검색</h2>
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
               <div class="region-step-container" style="margin-bottom: 16px;">
                 <span class="region-label">업직종</span>
                 <span class="region-counter" id="categoryCounter"><span style="color: #57CCAF;">0</span><span class="separator">/</span><span class="max-count">3</span></span>
               </div>
               <div class="form-toggle-group category-toggle-group option-table-group option-table-cols-3">
                 <?php
                  // 변수 안전성 확인
                  if (!isset($work_list)) {
                    $work_list = [];
                  }
                  if (!isset($search_txt)) {
                    $search_txt = [];
                  }

                  // 표시할 항목만 정의 (지정된 순서대로)
                  $display_items = ['생산', '물류', '건설', '운전배달', '식당', '서비스', '사무직', '상담', '기타'];

                  // 표시할 항목만 필터링하고 순서대로 정렬
                  $filtered_list = [];
                  foreach ($display_items as $item) {
                    foreach ($work_list as $row) {
                      if ($row['cd_val'] === $item) {
                        $filtered_list[] = $row;
                        break;
                      }
                    }
                  }

                  foreach ($filtered_list as $row) : ?>
                   <label class="form-toggle-btn2 sm">
                     <input type="checkbox" name="category[]" value="<?= $row["cd_val"] ?>" data-img="<?= $row["cd_val2"] ?>" <?= in_array_return($row["cd_val"], $search_txt["category"], "check") ?>>
                     <span class="btn"><?= $row["cd_val"] ?></span>
                   </label>
                 <?php endforeach; ?>
               </div>
               <script>
                 // 업직종 선택 제한 및 카운터 업데이트
                 var maxCategoryCount = 3;

                 function updateCategoryCounter() {
                   var checkedCount = $('input[name="category[]"]:checked').length;
                   $('#categoryCounter').html('<span style="color: #57CCAF;">' + checkedCount + '</span><span class="separator">/</span><span class="max-count">' + maxCategoryCount + '</span>');
                 }

                 // 초기 카운터 설정 (페이지 로드 시)
                 $(document).ready(function() {
                   updateCategoryCounter();
                 });

                 // 체크박스 변경 이벤트
                 $(document).on('change', 'input[name="category[]"]', function() {
                   var $checkbox = $(this);
                   var checkedCount = $('input[name="category[]"]:checked').length;

                   // 체크 해제하는 경우는 항상 허용
                   if (!$checkbox.is(':checked')) {
                     updateCategoryCounter();
                     return;
                   }

                   // 3개 이상 선택하려는 경우 무응답 처리 (체크 해제)
                   if (checkedCount > maxCategoryCount) {
                     $checkbox.prop('checked', false);
                     return;
                   }

                   updateCategoryCounter();
                 });
               </script>
             </div>

             <div class="form-row work-type-row">
               <div class="form-row-header">
                 <span class="form-row-title">근무형태</span>
                 <?php
                  $work_type_selected = array_filter((array)$search_txt["work_type"]);
                  $has_work_type = !empty($work_type_selected);
                  ?>
                 <label class="filter-any-toggle">
                   <input type="checkbox" name="work_type_any" class="work-type-any" <?= $has_work_type ? "" : "checked" ?>>
                   <span class="toggle-indicator"></span>
                   <span class="toggle-text">전체</span>
                 </label>
               </div>
               <div class="form-toggle-group option-table-group option-table-cols-3 work-type-select">
                 <?php
                  if (!isset($work_type_list)) {
                    $work_type_list = [];
                  }
                  foreach ($work_type_list as $key => $row) : ?>

                   <label class="form-toggle-btn2 work-type-option">
                     <input type="checkbox" name="work_type[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $search_txt["work_type"], "check") ?>>
                     <span class="btn"><?= $row["cd_val"] ?></span>
                   </label>
                 <?php endforeach; ?>
               </div>
             </div>

             <div class="form-row price-type-row">
               <div class="form-row-header">
                 <span class="form-row-title">급여형태</span>
                 <?php
                  $price_type_selected = array_filter((array)$search_txt["price_type"]);
                  $has_price_type = !empty($price_type_selected);
                  ?>
                 <label class="filter-any-toggle">
                   <input type="checkbox" name="price_type_any" class="price-type-any" <?= $has_price_type ? "" : "checked" ?>>
                   <span class="toggle-indicator"></span>
                   <span class="toggle-text">전체</span>
                 </label>
               </div>

               <div class="form-toggle-group option-table-group option-table-cols-4 price-type-select">
                 <?php
                  $price_type_values = ['시급', '일급', '주급', '월급'];
                  foreach ($price_type_values as $price_value) : ?>
                   <label class="form-toggle-btn2 price-type-option">
                     <input type="checkbox" name="price_type[]" value="<?= $price_value ?>" <?= in_array_return($price_value, $search_txt["price_type"], "check") ?>>
                     <span class="btn"><?= $price_value ?></span>
                   </label>
                 <?php endforeach; ?>
               </div>
             </div>

             <div class="form-row week-row">
               <div class="form-row-header">
                 <span class="form-row-title">근무요일</span>
                 <?php
                  $week_selected = array_filter((array)$search_txt["week"]);
                  $has_week = !empty($week_selected);
                  ?>
                 <label class="filter-any-toggle">
                   <input type="checkbox" name="week_any" class="week-any" <?= $has_week ? "" : "checked" ?>>
                   <span class="toggle-indicator"></span>
                   <span class="toggle-text">전체</span>
                 </label>
               </div>
               <div class="form-toggle-group option-table-group option-table-cols-week week-select">
                 <?php
                  if (!isset($week_list)) {
                    $week_list = [];
                  }
                  foreach ($week_list as $row) : ?>

                   <label class="form-toggle-btn2 week-option">
                     <input type="checkbox" name="week[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $search_txt["week"], "check") ?>>
                     <span class="btn"><?= $row["cd_val"] ?></span>
                   </label>
                 <?php endforeach; ?>
               </div>
             </div>

             <div class="form-row sex-row">
               <div class="form-row-header">
                 <span class="form-row-title">성별</span>
                 <?php
                  $sex_selected = array_filter((array)$search_txt["sex"]);
                  $has_sex = !empty($sex_selected);
                  ?>
                 <label class="filter-any-toggle">
                   <input type="checkbox" name="sex_any" class="sex-any" <?= $has_sex ? "" : "checked" ?>>
                   <span class="toggle-indicator"></span>
                   <span class="toggle-text">전체</span>
                 </label>
               </div>
               <div class="form-toggle-group option-table-group option-table-cols-2 sex-select">
                 <?php
                  $sex_values = ['남자', '여자'];
                  foreach ($sex_values as $sex_value) : ?>
                   <label class="form-toggle-btn2 sex-option">
                     <input type="checkbox" name="sex[]" value="<?= $sex_value ?>" <?= in_array_return($sex_value, $search_txt["sex"], "check") ?>>
                     <span class="btn"><?= $sex_value ?></span>
                   </label>
                 <?php endforeach; ?>
               </div>
             </div>

             <div class="form-row nationality-row">
               <div class="form-row-header">
                 <span class="form-row-title">국적</span>
                 <?php
                  $nationality_selected = array_filter((array)$search_txt["nationality"]);
                  $has_nationality = !empty($nationality_selected);
                  ?>
                 <label class="filter-any-toggle">
                   <input type="checkbox" name="nationality_any" class="nationality-any" <?= $has_nationality ? "" : "checked" ?>>
                   <span class="toggle-indicator"></span>
                   <span class="toggle-text">전체</span>
                 </label>
               </div>
               <div class="form-toggle-group option-table-group option-table-cols-2 nationality-select">
                 <?php
                  $nationality_values = ['내국인', '외국인'];
                  foreach ($nationality_values as $nationality_value) : ?>
                   <label class="form-toggle-btn2 nationality-option">
                     <input type="checkbox" name="nationality[]" value="<?= $nationality_value ?>" <?= in_array_return($nationality_value, $search_txt["nationality"], "check") ?>>
                     <span class="btn"><?= $nationality_value ?></span>
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
                   <?php foreach ($age_ranges as $range) :
                      $value = $range['start'] . '-' . $range['end'];
                    ?>
                     <option value="<?= $value ?>" <?= $selected_age_value === $value ? 'selected' : '' ?>><?= $range['label'] ?></option>
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

 <template id="tpl_category">
   <label class="form-toggle-btn2 sm {{active}} btn-category" data-filter-key="{{filter_key}}">
     <input type="checkbox" name="filter" value="{{category_name}}">
     <span class="cate">{{category_name}}</span>
   </label>
 </template>

 <script>
   // 현재 사용자 ID 가져오기 (로그인하지 않은 경우 빈 문자열)
   var currentUserId = "<?= $this->user->isLogin() ? $this->user->getUserIdx() : '' ?>";
   var skipSearchStateLoad = <?= (isset($skip_search_state_load) && $skip_search_state_load) ? "true" : "false" ?>;
  
   function initCategory() { // 검색 조건을 배열 형태로 정리

     var arr_category = "";
     var arr_work_type = "";
     var arr_price_type = "";
     var arr_week = "";
     var arr_sex = "";
     var arr_nationality = "";


     for (var icnt = 0; icnt < $("[name='category[]']:checked").length; icnt++) {
       var catVal = $("[name='category[]']:checked").eq(icnt).val();

       if (catVal != "") {
         if (arr_category == "") {
           arr_category = catVal;
         } else {
           arr_category += "^" + catVal;
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

     // 근무협의 체크박스가 선택된 경우
     if ($("[name='priceNegotiable']").is(":checked")) {
       if (arr_price_type == "") {
         arr_price_type = "협의";
       } else {
         arr_price_type += "^협의";
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
     //search_array는 전역 변수로, 검색 조건을 담는 객체입니다. (예: search_array.category, search_array.work_type 등)
     // job_list.php에서 정의되어 있으며, 검색 조건을 서버로 전달할 때 사용됩니다. /recruit_proc/search_save_params2025
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

     // 지역 정보 처리 (tab_list[0])
     var regions = window.selectedRegions || (typeof selectedRegions !== 'undefined' ? selectedRegions : []);

     if (regions.length > 0) {
       tab_list[0].text = regions.join(", ");
       tab_list[0].active = "on";
     }

     if (search_array.category != "") {

       tab_list[1].text = search_array.category.replaceAll("^", ",");
       tab_list[1].active = "on";
     }

     if (search_array.work_type != "") {
       tab_list[2].text = search_array.work_type.replaceAll("^", ",");
       tab_list[2].active = "on";
     }

     if (search_array.week != "") {
       if (search_array.week == "일^월^화^수^목^금^토") {
         tab_list[3].text = "일~토";
       } else {
         tab_list[3].text = search_array.week.replaceAll("^", ",");
       }
       tab_list[3].active = "on";
     }

     if (search_array.price_type != "") {
       tab_list[4].text = search_array.price_type.replaceAll("^", ",");
       tab_list[4].active = "on";
     }

     if (search_array.sex != "") {
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

   // 상세검색 조건을 세션 스토리지에 저장
   function saveSearchStateToStorage() {
     try {
       var state = {
         userId: currentUserId, // 현재 사용자 ID 저장
         addrName: addrName,
         searchAreaStr: searchAreaStr,
         search_addr: Array.isArray(search_addr) ? search_addr : [],
         selectedRegions: Array.isArray(window.selectedRegions) ? window.selectedRegions : [],
         category: search_array.category ? search_array.category.split("^") : [],
         work_type: search_array.work_type ? search_array.work_type.split("^") : [],
         week: search_array.week ? search_array.week.split("^") : [],
         price_type: search_array.price_type ? search_array.price_type.split("^") : [],
         sex: search_array.sex ? search_array.sex.split("^") : [],
         nationality: search_array.nationality ? search_array.nationality.split("^") : [],
         sage: search_array.sage || "",
         eage: search_array.eage || "",
         priceNegotiable: $("[name='priceNegotiable']").is(":checked"),
         workingDay: $("[name='workingDay']").is(":checked"),
       };
       // 사용자 ID를 키에 포함하여 저장
       var storageKey = window.getAdvancedSearchStorageKey ? window.getAdvancedSearchStorageKey() : "jobListAdvancedSearch_recruit_" + (currentUserId || "guest");
       localStorage.setItem(storageKey, JSON.stringify(state));
     } catch (e) {

     }
   }

   // 전역 등록 (addr_popup에서 호출 가능하도록)
   window.saveSearchStateToStorage = saveSearchStateToStorage;

   // 저장된 상세검색 조건을 로드해 UI에 반영
   function loadSearchStateFromStorage() {
     // 현재 사용자 ID에 해당하는 키로 불러오기
     var storageKey = window.getAdvancedSearchStorageKey ? window.getAdvancedSearchStorageKey() : "jobListAdvancedSearch_recruit_" + (currentUserId || "guest");
     var raw = localStorage.getItem(storageKey);
     if (!raw) return;

     try {
       var state = JSON.parse(raw);

       // 저장된 사용자 ID와 현재 사용자 ID가 다르면 무시 (보안)
       if (state.userId !== currentUserId) {
         return;
       }

       function applyCheckedValues(selector, values) {
         $(selector).each(function() {
           $(this).prop("checked", values.indexOf($(this).val()) !== -1);
         });
       }

       applyCheckedValues("input[name='category[]']", state.category || []);
       applyCheckedValues("input[name='work_type[]']", state.work_type || []);
       applyCheckedValues("input[name='week[]']", state.week || []);
       applyCheckedValues("input[name='price_type[]']", state.price_type || []);
       applyCheckedValues("input[name='sex[]']", state.sex || []);
       applyCheckedValues("input[name='nationality[]']", state.nationality || []);

       $("[name='priceNegotiable']").prop("checked", !!state.priceNegotiable);
       $("[name='workingDay']").prop("checked", !!state.workingDay);

       $("#sageInput").val(state.sage || "");
       $("#eageInput").val(state.eage || "");

       // 지역 복원
       if (state.selectedRegions && Array.isArray(state.selectedRegions)) {
         window.selectedRegions = state.selectedRegions.slice();
         selectedRegions = window.selectedRegions;
         updateSelectedRegionsDisplay();
       }

       // API 파라미터 전역변수 동기화 (getBoardList에서 사용)
       if (state.search_addr) search_addr = state.search_addr;
       if (state.searchAreaStr) searchAreaStr = state.searchAreaStr;
       if (state.addrName) addrName = state.addrName;

       updateCategoryCounter();
       updateWorkTypeSelectionState();
       updateWeekSelectionState();
       updatePriceTypeSelectionState();
       updateSexSelectionState();
       updateAgeSelectionState();
       updateRegionCounter();

       initCategory();
       initTabUi();

       // 검색 조건이 있으면 AJAX로 새로 로드 (초기 렌더링 데이터 사용 안 함)
       var hasSearchCondition = (state.search_addr && state.search_addr.length > 0) ||
                                 state.category ||
                                 state.work_type ||
                                 state.week ||
                                 state.price_type ||
                                 state.sex ||
                                 state.nationality ||
                                 state.sage ||
                                 state.eage;

       if (hasSearchCondition && typeof window.getBoardList === 'function') {
         setTimeout(function() {
           window.getBoardList('');
         }, 100);
       }
     } catch (e) {

     }
   }
  
   function initTabUi() {


     $("#tab_list").html("");
    
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
      
       $content = $content.replaceAll("form-toggle-btn2", "filter-tag-item");

       $("#tab_list").append($content);
     }

   }

   $("[name='workingDay']").click(function() {
     $("[name='week[]']").prop("checked", $(this).is(":checked"));
   });

   // 근무협의 체크박스 이벤트
   $("[name='priceNegotiable']").click(function() {
     $("[name='price_type[]']").prop("checked", $(this).is(":checked"));
   });

   $(".btn-close-panel").click(function() {
     // 백업이 있으면 복원 (초기화 취소)
     if (window.backupSearchState) {
       // 지역 복원
       window.selectedRegions = window.backupSearchState.selectedRegions.slice();
       selectedRegions = window.selectedRegions;
       updateSelectedRegionsDisplay();

       // 체크박스 복원
       var restoreCheckboxes = function(name, values) {
         $('input[name="' + name + '"]').each(function() {
           $(this).prop('checked', values.indexOf($(this).val()) !== -1);
         });
       };

       restoreCheckboxes('category[]', window.backupSearchState.category || []);
       restoreCheckboxes('work_type[]', window.backupSearchState.work_type || []);
       restoreCheckboxes('week[]', window.backupSearchState.week || []);
       restoreCheckboxes('price_type[]', window.backupSearchState.price_type || []);
       restoreCheckboxes('sex[]', window.backupSearchState.sex || []);
       restoreCheckboxes('nationality[]', window.backupSearchState.nationality || []);

       // 나이 입력값 복원
       $('#sageInput').val(window.backupSearchState.sage || '');
       $('#eageInput').val(window.backupSearchState.eage || '');

       // 추가 체크박스 복원
       $('[name="priceNegotiable"]').prop('checked', window.backupSearchState.priceNegotiable || false);
       $('[name="workingDay"]').prop('checked', window.backupSearchState.workingDay || false);

       // UI 상태 갱신
       updateCategoryCounter();
       updateWorkTypeSelectionState();
       updateWorkTypeVisuals();
       updateWeekSelectionState();
       updateWeekVisuals();
       updatePriceTypeSelectionState();
       updatePriceTypeVisuals();
       updateSexSelectionState();
       updateSexVisuals();
       updateNationalitySelectionState();
       updateNationalityVisuals();
       updateAgeSelectionState();
       updateRegionCounter();

       // 탭 UI 갱신
       initCategory();
       initTabUi();
     }

     // 플래그 초기화: 패널 닫기 시 main_visual 동기화 방지
     window.skipAddrNameUpdate = true;

     // 패널 닫기
     var $panel = $("#advancedSearch")[0];
     if ($panel) {
       $panel.classList.remove('on');
       document.body.style.overflow = '';
       $panel.scrollTo(0, 0);
     }

     // 플래그 해제
     setTimeout(function() {
       window.skipAddrNameUpdate = false;
       if (typeof restoreBodyScroll === 'function') restoreBodyScroll();
     }, 300);
   });

   // 지역 선택 관련 변수 (전역 변수로 선언하여 job_list.php에서도 접근 가능하도록)
   if (typeof selectedRegions === 'undefined') {
     window.selectedRegions = [];
   }
   var selectedRegions = window.selectedRegions;
   var maxRegions = 3;

   // 지역 선택 버튼 클릭 시 changeArea 팝업 열기
   $("#regionSelectBtn").click(function() {
     // 플래그 설정: 팝업에서 돌아올 때 .addrName 업데이트 무시
     window.skipAddrNameUpdate = true;

     // openPanel() 사용: body.panel-open 클래스가 추가되어 bottom-nav 정상 숨김 처리
     var changeAreaEl = document.getElementById('changeArea');
     if (changeAreaEl) {
       openPanel(changeAreaEl);
     }
   });

   // 지역 선택 개수 업데이트 함수
   function updateRegionCounter() {
     var regions = window.selectedRegions || selectedRegions || [];
     var count = regions.length;
     $("#regionCounter").html('<span style="color: #57CCAF;">' + count + '</span><span class="separator">/</span><span class="max-count">' + maxRegions + '</span>');
   }

   // 선택된 지역 표시 업데이트 함수 (전역 함수로 등록하여 main_visual.php에서 접근 가능)
   function updateSelectedRegionsDisplay() {
     var container = $("#selectedRegionsContainer");
     container.empty();

     // 전역 변수에서 지역 정보 가져오기
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

   // 전역으로 등록하여 다른 파일에서도 접근 가능
   window.updateSelectedRegionsDisplay = updateSelectedRegionsDisplay;

   // 지역 삭제 버튼 클릭 이벤트 (이벤트 위임 사용)
   $(document).on('click', '.selected-region-remove', function() {
     var index = parseInt($(this).data('index'));
     window.selectedRegions.splice(index, 1);
     selectedRegions = window.selectedRegions;
     updateSelectedRegionsDisplay();

     // addr_popup2025_new.php의 .div2 .selbtn도 동기화
     var $selbtnAll = $('.div2 .selbtn');
     var $targetSelbtn = $selbtnAll.eq(index);
     if ($targetSelbtn.length) {
       var step = $targetSelbtn.data('step');
       var addr = $targetSelbtn.data('addr');
       // 리스트에서 addr_on 해제
       $('li[data-step="' + step + '"][data-addr="' + addr + '"]').removeClass('addr_on');
       $targetSelbtn.remove();
       // updateSelectedCount 호출
       if (typeof updateSelectedCount === 'function') {
         updateSelectedCount();
       }
     }

   });

   // 팝업에서 지역 선택 완료 시 호출될 전역 함수
   window.onRegionSelectionComplete = function(regions) {
     window.selectedRegions = regions.slice(0, maxRegions); //배열에서 앞에서부터 maxRegions 개수만큼 잘라서 새 배열을 만든다
     selectedRegions = window.selectedRegions;
     updateSelectedRegionsDisplay();

     initCategory();
     initTabUi();
   };

   // 근무형태 전체/개별 선택 동기화 로직
   var $workTypeAnyCheckbox = $('input[name="work_type_any"]');
   var $workTypeItemCheckboxes = $('input[name="work_type[]"]');
   var $weekAnyCheckbox = $('input[name="week_any"]');
   var $weekItemCheckboxes = $('input[name="week[]"]');
   var $priceTypeAnyCheckbox = $('input[name="price_type_any"]');
   var $priceTypeItemCheckboxes = $('input[name="price_type[]"]');
   var $sexAnyCheckbox = $('input[name="sex_any"]');
   var $sexItemCheckboxes = $('input[name="sex[]"]');
   var $nationalityAnyCheckbox = $('input[name="nationality_any"]');
   var $nationalityItemCheckboxes = $('input[name="nationality[]"]');
   var $ageAnyCheckbox = $('input[name="age_any"]');
   var $ageRangeSelect = $('#ageRangeSelect');
   var $sageInput = $('#sageInput');
   var $eageInput = $('#eageInput');

   function updateWorkTypeSelectionState() {
     if (!$workTypeAnyCheckbox.length) return;
     if ($workTypeItemCheckboxes.filter(':checked').length === 0) {
       $workTypeAnyCheckbox.prop('checked', true);
     } else {
       $workTypeAnyCheckbox.prop('checked', false);
     }
   }

   updateWorkTypeSelectionState();

   function updateWorkTypeVisuals() {
     if ($workTypeAnyCheckbox.is(':checked')) {
       $workTypeItemCheckboxes.closest('label').addClass('work-type-all-selected');
     } else {
       $workTypeItemCheckboxes.closest('label').removeClass('work-type-all-selected');
     }
   }

   $workTypeAnyCheckbox.on('change', function() {
     var totalCount = $workTypeItemCheckboxes.length;
     if ($(this).is(':checked')) {
       $workTypeItemCheckboxes.prop('checked', true);
     } else if ($workTypeItemCheckboxes.filter(':checked').length === totalCount) {
       $workTypeItemCheckboxes.prop('checked', false);
     }
     updateWorkTypeVisuals();
   });

   $workTypeItemCheckboxes.on('change', function() {
     var totalCount = $workTypeItemCheckboxes.length;
     var checkedCount = $workTypeItemCheckboxes.filter(':checked').length;

     // 모든 항목이 선택되면 "전체" 체크 유지, 해제되면 "전체" 체크 해제
     if (checkedCount === totalCount) {
       $workTypeAnyCheckbox.prop('checked', true);
     } else {
       $workTypeAnyCheckbox.prop('checked', false);
     }
     updateWorkTypeVisuals();
   });

   updateWorkTypeVisuals();

   function updateWeekSelectionState() {
     if (!$weekAnyCheckbox.length) return;
     if ($weekItemCheckboxes.filter(':checked').length === 0) {
       $weekAnyCheckbox.prop('checked', true);
     } else {
       $weekAnyCheckbox.prop('checked', false);
     }
   }

   updateWeekSelectionState();

   function updateWeekVisuals() {
     if ($weekAnyCheckbox.is(':checked')) {
       $weekItemCheckboxes.closest('label').addClass('week-all-selected');
     } else {
       $weekItemCheckboxes.closest('label').removeClass('week-all-selected');
     }
   }

   $weekAnyCheckbox.on('change', function() {
     var totalCount = $weekItemCheckboxes.length;
     if ($(this).is(':checked')) {
       $weekItemCheckboxes.prop('checked', true);
     } else if ($weekItemCheckboxes.filter(':checked').length === totalCount) {
       $weekItemCheckboxes.prop('checked', false);
     }
     updateWeekVisuals();
   });

   $weekItemCheckboxes.on('change', function() {
     var totalCount = $weekItemCheckboxes.length;
     var checkedCount = $weekItemCheckboxes.filter(':checked').length;

     // 모든 항목이 선택되면 "전체" 체크 유지, 해제되면 "전체" 체크 해제
     if (checkedCount === totalCount) {
       $weekAnyCheckbox.prop('checked', true);
     } else {
       $weekAnyCheckbox.prop('checked', false);
     }
     updateWeekVisuals();
   });

   updateWeekVisuals();

   function updatePriceTypeSelectionState() {
     if (!$priceTypeAnyCheckbox.length) return;
     if ($priceTypeItemCheckboxes.filter(':checked').length === 0) {
       $priceTypeAnyCheckbox.prop('checked', true);
     } else {
       $priceTypeAnyCheckbox.prop('checked', false);
     }
   }

   updatePriceTypeSelectionState();

   function updatePriceTypeVisuals() {
     if ($priceTypeAnyCheckbox.is(':checked')) {
       $priceTypeItemCheckboxes.closest('label').addClass('price-type-all-selected');
     } else {
       $priceTypeItemCheckboxes.closest('label').removeClass('price-type-all-selected');
     }
   }

   $priceTypeAnyCheckbox.on('change', function() {
     var totalCount = $priceTypeItemCheckboxes.length;
     if ($(this).is(':checked')) {
       $priceTypeItemCheckboxes.prop('checked', true);
     } else if ($priceTypeItemCheckboxes.filter(':checked').length === totalCount) {
       $priceTypeItemCheckboxes.prop('checked', false);
     }
     updatePriceTypeVisuals();
   });

   $priceTypeItemCheckboxes.on('change', function() {
     var totalCount = $priceTypeItemCheckboxes.length;
     var checkedCount = $priceTypeItemCheckboxes.filter(':checked').length;

     // 모든 항목이 선택되면 "전체" 체크 유지, 해제되면 "전체" 체크 해제
     if (checkedCount === totalCount) {
       $priceTypeAnyCheckbox.prop('checked', true);
     } else {
       $priceTypeAnyCheckbox.prop('checked', false);
     }
     updatePriceTypeVisuals();
   });

   updatePriceTypeVisuals();

   function updateSexSelectionState() {
     if (!$sexAnyCheckbox.length) return;
     if ($sexItemCheckboxes.filter(':checked').length === 0) {
       $sexAnyCheckbox.prop('checked', true);
     } else {
       $sexAnyCheckbox.prop('checked', false);
     }
   }

   updateSexSelectionState();

   function updateSexVisuals() {
     if ($sexAnyCheckbox.is(':checked')) {
       $sexItemCheckboxes.closest('label').addClass('sex-all-selected');
     } else {
       $sexItemCheckboxes.closest('label').removeClass('sex-all-selected');
     }
   }

   $sexAnyCheckbox.on('change', function() {
     var totalCount = $sexItemCheckboxes.length;
     if ($(this).is(':checked')) {
       $sexItemCheckboxes.prop('checked', true);
     } else if ($sexItemCheckboxes.filter(':checked').length === totalCount) {
       $sexItemCheckboxes.prop('checked', false);
     }
     updateSexVisuals();
   });

   $sexItemCheckboxes.on('change', function() {
     var totalCount = $sexItemCheckboxes.length;
     var checkedCount = $sexItemCheckboxes.filter(':checked').length;

     // 모든 항목이 선택되면 "전체" 체크 유지, 해제되면 "전체" 체크 해제
     if (checkedCount === totalCount) {
       $sexAnyCheckbox.prop('checked', true);
     } else {
       $sexAnyCheckbox.prop('checked', false);
     }
     updateSexVisuals();
   });

   updateSexVisuals();

   function updateNationalitySelectionState() {
     if (!$nationalityAnyCheckbox.length) return;
     if ($nationalityItemCheckboxes.filter(':checked').length === 0) {
       $nationalityAnyCheckbox.prop('checked', true);
     } else {
       $nationalityAnyCheckbox.prop('checked', false);
     }
   }

   updateNationalitySelectionState();

   function updateNationalityVisuals() {
     if ($nationalityAnyCheckbox.is(':checked')) {
       $nationalityItemCheckboxes.closest('label').addClass('nationality-all-selected');
     } else {
       $nationalityItemCheckboxes.closest('label').removeClass('nationality-all-selected');
     }
   }

   $nationalityAnyCheckbox.on('change', function() {
     var totalCount = $nationalityItemCheckboxes.length;
     if ($(this).is(':checked')) {
       $nationalityItemCheckboxes.prop('checked', true);
     } else if ($nationalityItemCheckboxes.filter(':checked').length === totalCount) {
       $nationalityItemCheckboxes.prop('checked', false);
     }
     updateNationalityVisuals();
   });

   $nationalityItemCheckboxes.on('change', function() {
     var totalCount = $nationalityItemCheckboxes.length;
     var checkedCount = $nationalityItemCheckboxes.filter(':checked').length;

     // 모든 항목이 선택되면 "전체" 체크 유지, 해제되면 "전체" 체크 해제
     if (checkedCount === totalCount) {
       $nationalityAnyCheckbox.prop('checked', true);
     } else {
       $nationalityAnyCheckbox.prop('checked', false);
     }
     updateNationalityVisuals();
   });

   updateNationalityVisuals();

   function updateAgeSelectionState() {
     if (!$ageAnyCheckbox.length) return;
     var hasAge = $sageInput.val() !== "" || $eageInput.val() !== "";
     if (!hasAge) {
       $ageAnyCheckbox.prop('checked', true);
       $ageRangeSelect.val('');
     } else {
       $ageAnyCheckbox.prop('checked', false);
       var selectedValue = $sageInput.val() + '-' + $eageInput.val();
       $ageRangeSelect.val(selectedValue);
     }
   }

   updateAgeSelectionState();

   $ageRangeSelect.on('change', function() {
     var val = $(this).val();
     if (val) {
       var parts = val.split('-');
       $sageInput.val(parts[0]);
       $eageInput.val(parts[1]);
       $ageAnyCheckbox.prop('checked', false);
     } else {
       $sageInput.val('');
       $eageInput.val('');
       $ageAnyCheckbox.prop('checked', true);
     }
   });

   $ageAnyCheckbox.on('change', function() {
     if ($(this).is(':checked')) {
       $ageRangeSelect.val('');
       $sageInput.val('');
       $eageInput.val('');
     } else if ($ageRangeSelect.val() === '') {
       $(this).prop('checked', true);
     }
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
       var hasOtherOpenPanel = document.querySelector('.layer-panel.on') !== null;
       if (!hasOtherOpenPanel) {
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

   // 페이지 진입 시 저장된 상세검색 조건 복원
   $(document).ready(function() {
     if (!skipSearchStateLoad) {
       loadSearchStateFromStorage();
     }
   });

   // 패널 재오픈 시 저장된 검색 상태 복원
   $(document).on('click', '.btn-option', function() {
     setTimeout(function() {
       var storageKey = window.getAdvancedSearchStorageKey ? window.getAdvancedSearchStorageKey() : "jobListAdvancedSearch_recruit_" + (currentUserId || "guest");
       if (localStorage.getItem(storageKey)) {
         loadSearchStateFromStorage();
       }
     }, 100);
   });

   $("#btnSearch").click(function() {
     // 백업 삭제 (초기화 확정, 다음 초기화를 위해 준비)
     window.backupSearchState = null;

     // 플래그 초기화: 검색 버튼 클릭 시 .addrName 동기화 허용
     window.skipAddrNameUpdate = false;

     list_page = 1;

     // 팝업을 닫기 전에 먼저 검색 조건을 읽어야 함 (closePanel이 체크박스를 초기화함)
     // initCategory()는 체크박스 상태를 읽어서 search_array를 설정하므로 팝업 닫기 전에 호출

     initCategory();

     initTabUi();

     // 지역 UI 업데이트
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

     // body 스타일 즉시 초기화 및 지연 후 1회
     restoreBodyScroll();
     setTimeout(restoreBodyScroll, 300);
   });

   $("#btnReset").click(function() {
     // 초기화 전 현재 상태를 백업으로 저장 (X 버튼 클릭 시 복원용)
     window.backupSearchState = {
       selectedRegions: window.selectedRegions ? window.selectedRegions.slice() : [],
       category: $('input[name="category[]"]:checked').map(function() { return $(this).val(); }).get(),
       work_type: $('input[name="work_type[]"]:checked').map(function() { return $(this).val(); }).get(),
       week: $('input[name="week[]"]:checked').map(function() { return $(this).val(); }).get(),
       price_type: $('input[name="price_type[]"]:checked').map(function() { return $(this).val(); }).get(),
       sex: $('input[name="sex[]"]:checked').map(function() { return $(this).val(); }).get(),
       nationality: $('input[name="nationality[]"]:checked').map(function() { return $(this).val(); }).get(),
       sage: $('#sageInput').val(),
       eage: $('#eageInput').val(),
       priceNegotiable: $('[name="priceNegotiable"]').is(':checked'),
       workingDay: $('[name="workingDay"]').is(':checked')
     };

     // 플래그 초기화: 초기화 버튼 클릭 시 .addrName 동기화 허용
     window.skipAddrNameUpdate = false;

     // 지역
     window.selectedRegions = [];
     selectedRegions = [];
     updateSelectedRegionsDisplay();

     // job_list.php의 지역 관련 변수 및 UI 초기화
     if (typeof addrName !== 'undefined') {
       addrName = '지역을 선택해 주세요';
       $('.addrName').text('지역을 선택해 주세요').attr('data-selected', 'false');
     }
     if (typeof search_addr !== 'undefined') {
       search_addr = [];
     }
     if (typeof searchAreaStr !== 'undefined') {
       searchAreaStr = JSON.stringify([]);
     }
     // .selbtn 요소들도 초기화 (addr_popup2025_new.php에서 관리되는 요소)
     $('.selbtn').remove();
     // 지역 선택 영역(.div2)도 비우기
     $('.addr_list_sel_box .div2').empty();
     // 지역 선택 카운트도 초기화
     $('.addr_sel_cnt').text('0');
     // addr_list 영역의 선택 상태 초기화 (addr_on 클래스 제거)
     $('.addr_list li.addr_on').removeClass('addr_on');
     $('.search_addr li.addr_on').removeClass('addr_on');
     // 동적으로 로드된 시·군·구, 읍·면·동 리스트도 비우기
     $('.addr_dept2 ul').empty();
     $('.addr_dept3 ul').empty();
     // addr_popup2025_new.php의 updateSelectedCount 함수가 있으면 호출
     if (typeof updateSelectedCount === 'function') {
       updateSelectedCount();
     }

     // 업직종
     $('input[name="category[]"]').prop('checked', false);
     updateCategoryCounter();

     // 근무형태
     $workTypeItemCheckboxes.prop('checked', false);
     if ($workTypeAnyCheckbox.length) {
       $workTypeAnyCheckbox.prop('checked', true);
     }

     // 근무요일
     $weekItemCheckboxes.prop('checked', false);
     if ($weekAnyCheckbox.length) {
       $weekAnyCheckbox.prop('checked', true);
     }

     // 급여형태
     $priceTypeItemCheckboxes.prop('checked', false);
     if ($priceTypeAnyCheckbox.length) {
       $priceTypeAnyCheckbox.prop('checked', true);
     }
     $("[name='priceNegotiable']").prop('checked', false);

     // 성별
     $sexItemCheckboxes.prop('checked', false);
     if ($sexAnyCheckbox.length) {
       $sexAnyCheckbox.prop('checked', true);
     }

     // 국적
     $nationalityItemCheckboxes.prop('checked', false);
     if ($nationalityAnyCheckbox.length) {
       $nationalityAnyCheckbox.prop('checked', true);
     }

     // 나이
     $ageRangeSelect.val('');
     $sageInput.val('');
     $eageInput.val('');
     if ($ageAnyCheckbox.length) {
       $ageAnyCheckbox.prop('checked', true);
     }

     // 시각적 상태 갱신 (체크 상태 + 하이라이트 모두 업데이트)
     updateWorkTypeSelectionState();
     updateWorkTypeVisuals();
     updateWeekSelectionState();
     updateWeekVisuals();
     updatePriceTypeSelectionState();
     updatePriceTypeVisuals();
     updateSexSelectionState();
     updateSexVisuals();
     updateNationalitySelectionState();
     updateNationalityVisuals();
     updateAgeSelectionState();

     // localStorage 삭제
     var storageKey = window.getAdvancedSearchStorageKey ? window.getAdvancedSearchStorageKey() : "jobListAdvancedSearch_recruit_" + (currentUserId || "guest");
     localStorage.removeItem(storageKey);

     initCategory();
     initTabUi();

     // 지역 UI 업데이트
     if (typeof window.updateSelectedRegionsTags === 'function') {
       window.updateSelectedRegionsTags();
     }

     // 검색 실행 후 패널 닫기
     $(list_id).html("");
     getBoardList("Y");

     var $panel = $("#advancedSearch")[0];
     if ($panel) {
       $panel.classList.remove('on');
       document.body.style.overflow = '';
       $panel.scrollTo(0, 0);
     }
     setTimeout(function() {
       if (typeof restoreBodyScroll === 'function') restoreBodyScroll();
     }, 300);
   });
 </script>