<style>
  /* 근무형태, 급여, 성별 버튼 그룹 스타일 */
  .work-type-group,
  .price-type-group,
  .sex-group {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .work-type-group .form-toggle-btn2,
  .price-type-group .form-toggle-btn2,
  .sex-group .form-toggle-btn2 {
    flex: 1;
    min-width: 0;
  }

  /* ㅋ근무형태와 급여 버튼의 span이 부모 넓이를 꽉 차도록 */
  .work-type-group .form-toggle-btn2 .btn,
  .price-type-group .form-toggle-btn2 .btn {
    width: 100%;
    /* 부모의 넓이를 꽉 차도록 설정 */
  }

  /* 근무형태 마지막 label의 오른쪽 마진 제거 */
  .work-type-group .form-toggle-btn2:last-child {
    margin-right: 0;
  }

  /* 급여 마지막 label의 오른쪽 마진 제거 */
  .price-type-group .form-toggle-btn2:last-child {
    margin-right: 0;
  }

  /* 카테고리 마지막 label의 오른쪽 마진 제거 */
  .category-toggle-group .form-toggle-btn2:last-child {
    margin-right: 0;
  }

  .category-toggle-group .form-toggle-btn2 .btn {
    width: 100%;
    /* 부모의 넓이를 꽉 차도록 설정 */
  }

  /* 성별 그룹은 2개 항목이므로 각각 50% */
  .sex-group {
    flex-wrap: nowrap;
  }

  .sex-group .form-toggle-btn2 {
    flex: 0 0 calc(50% - 13px);
  }

  /* 카테고리 버튼 스타일 - 3개씩 정렬 */
  .category-toggle-group .form-toggle-btn2 {
    width: 32%;
    margin-right: 1%;
    margin-bottom: 8px;
  }



  /* 나이 선택 그룹 스타일 */
  .age-row {
    margin-bottom: 50px;
  }

  .age-group {
    display: flex;
    align-items: center;
    gap: 16px;
  }

  .age-select {
    flex: 0 0 47.69%;
    /* 전체 너비의 47% */
    min-width: 0;
  }

  #advancedSearch .age-select select {
    width: 100%;
    height: 3rem;
    min-height: 3rem;
    max-height: 3rem;
    padding: 8px 12px;
    border: 1px solid #BEBFC9;
    border-radius: 100px;
    background-color: #fff;
    font-size: 14px;
    color: #333;
    box-sizing: border-box;
    line-height: 1.2;
    display: block;
    position: relative;
    opacity: 1;
    outline: 0;
    -webkit-tap-highlight-color: transparent;

    /* 나이 선택 select만 기본 화살표 숨기기 */
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;

    /* 커스텀 화살표 추가 */
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 40px;
  }

  /* 화살표 공간 확보 */
  .age-separator {
    flex: 0 0 auto;
    font-size: 16px;
    color: #666;
    font-weight: 500;
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
</style>
<div id="advancedSearch" class="layer-panel" tabindex="-1">
  <div class="panel-container">
    <div class="panel-header">
      <h2 class="panel-title">인재정보 상세검색</h2>
      <button type="button" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
    </div>
    <div class="panel-body">
      <div class="panel-content">

        <div class="section advanced-search-form">
          <div class="container" style="padding: 30px;">

            <div class="form-row">
              <p class="form-label">카테고리 <em>다중선택가능</em>
                <span class="option">
                  <label class="form-checkbox">
                    <input type="checkbox" name="category[]" value="" class="sr-text js-sel-all">
                    <span class="label">전체선택</span>
                  </label>
                </span>
              </p>
              <div class="form-toggle-group category-toggle-group">
                <?php
                // 원하는 순서대로 카테고리 정의
                $desired_categories = ['생산', '물류', '건설', '운전배달', '식당', '서비스', '사무직', '상담', '기타'];

                // 원하는 순서대로 카테고리 출력
                foreach ($desired_categories as $category_name) {
                  // wrok_list에서 해당 카테고리 찾기
                  $found_category = null;
                  foreach ($wrok_list as $row) {
                    if ($row["cd_val"] == $category_name) {
                      $found_category = $row;
                      break;
                    }
                  }

                  // 찾은 카테고리가 있으면 출력
                  if ($found_category) {
                ?>
                    <label class="form-toggle-btn2 sm">
                      <input type="checkbox" name="category[]" data-select-limit="2" value="<?= $found_category["cd_val"] ?>" data-img="<?= $found_category["cd_val2"] ?>" <?= in_array_return($found_category["cd_val"], $search_txt["category"], "check") ?>>
                      <span class="btn"><?= $found_category["cd_val"] ?></span>
                    </label>
                <?php
                  }
                }
                ?>
              </div>
              <script>
                document.querySelectorAll('.js-sel-all').forEach(function(el) {
                  var name = el.getAttribute('name');
                  var items = document.querySelectorAll('input[name="' + name + '"]');

                  el.addEventListener('change', function() {
                    if (!items) return;
                    items.forEach(function(item) {
                      if (item !== el) {
                        item.checked = el.checked;
                      }
                    });
                  });

                });
              </script>
            </div>

            <div class="form-row">
              <p class="form-label">근무형태 <em>다중선택가능</em></p>
              <div class="form-toggle-group work-type-group">
                <?php foreach ($work_type_list as $key => $row) : ?>
                  <label class="form-toggle-btn2">
                    <input type="checkbox" name="work_type[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $search_txt["work_type"], "check") ?>>
                    <span class="btn"><?= $row["cd_val"] ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="form-row">
              <p class="form-label">
                근무요일 <em>다중선택가능</em>
                <span class="option">
                  <label class="form-checkbox">
                    <input type="checkbox" name="workingDay" value="all" class="sr-text">
                    <span class="label">요일무관</span>
                  </label>
                </span>
              </p>
              <div class="form-toggle-group align-justify">
                <?php foreach ($week_list as $row) : ?>
                  <label class="form-toggle-btn2">
                    <input type="checkbox" name="week[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $search_txt["week"], "check") ?>>
                    <span class="btn"><?= $row["cd_val"] ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="form-row">
              <p class="form-label">급여 <em>다중선택가능</em></p>
              <div class="form-toggle-group price-type-group">
                <?php foreach ($price_type_list as $key => $row) : ?>
                  <label class="form-toggle-btn2">
                    <input type="checkbox" name="price_type[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $search_txt["price_type"], "check") ?>>
                    <span class="btn"><?= $row["cd_val"] ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="form-row">
              <p class="form-label">성별 <em>다중선택가능</em></p>
              <div class="form-toggle-group sex-group">
                <label class="form-toggle-btn2">
                  <input type="checkbox" name="sex[]" value="남자" <?= in_array_return("남자", $search_txt["sex"], "check") ?>>
                  <span class="btn">남자</span>
                </label>
                <label class="form-toggle-btn2">
                  <input type="checkbox" name="sex[]" value="여자" <?= in_array_return("여자", $search_txt["sex"], "check") ?>>
                  <span class="btn">여자</span>
                </label>
              </div>
            </div>

            <div class="form-row">
              <p class="form-label">나이</p>
              <div class="age-group">
                <div class="age-select">
                  <select name="sage">
                    <option value="">선택</option>
                    <?php for ($icnt = 20; $icnt <= 80; $icnt++) : ?>
                      <option value="<?= $icnt ?>" <?= $search_txt["sage"] == $icnt ? "selected" : "" ?>><?= $icnt ?>세</option>
                    <?php endfor; ?>
                  </select>
                </div>
                <!-- <span class="entity" style="display: inline-block; float: left; margin-top: 7px;">~</span> -->
                <div class="age-select">
                  <select name="eage">
                    <option value="">선택</option>
                    <?php for ($icnt = 20; $icnt <= 80; $icnt++) : ?>
                      <option value="<?= $icnt ?>" <?= $search_txt["eage"] == $icnt ? "selected" : "" ?>><?= $icnt ?>세</option>
                    <?php endfor; ?>
                  </select>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
    <div class="panel-footer">
      <div class="sticky-bottom">
        <button id="btnSearch" type="button" class="btn btn-primary">검색하기</button>
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

  $(".btn-close-panel").click(function() {
    $("body").css("overflow", "auto");
    setTimeout(function() {
      $("body").css("overflow", "auto");
    }, 500);
    $("#advancedSearch").removeClass("on");
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

    initCategory();
    initTabUi();
    //닫기
    $("#advancedSearch").removeClass("on");
    $("body").css("overflow", "auto");

    //검색
    $(list_id).html("");
    getBoardList("Y");
  });
</script>