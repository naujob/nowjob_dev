<script type="text/javascript" src="/resource/shop/web/smarteditor2/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab2"; ?>
      <?php $tab_on2 = "menu2"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
      <style>
        .form-text {
          font-size: 16px;
        }

        .noti_duration .form-text {
          width: 320px;
        }

        .form-selectbox select {
          font-size: 16px;
        }

        .form-table .form-selectbox {
          width: 80px;
        }

        .form-row.starting-date .value input {
          width: 320px;
          padding-left: 0;
          text-align: left;
        }

        .law_popup {
          width: 1050px;
          height: 550px;
          border: 1px solid #777777;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
          position: fixed;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          background: white;
          display: none;
          z-index: 999;
          flex-direction: column;
          border-radius: 10px;
        }

        .law_popup.on {
          display: flex;
        }

        .tab_menu {
          display: flex;
          height: 50px;
          align-items: center;
          font-weight: bold;
          font-size: 25px;
          color: #777777;
          flex-shrink: 0;
        }

        .tab_menu .tab1,
        .tab_menu .tab2 {
          padding: 8px 20px;
          cursor: pointer;
          border-bottom: 4px solid #777777;
        }

        .tab_menu .on {
          color: #31A182;
          border-bottom: 4px solid #31A182;
        }

        .tab_menu .close {
          margin-left: auto;
          margin-right: 0;
          margin-top: 0;
          font-size: 20px;
          cursor: pointer;
        }

        .tab_menu .close a {
          display: inline-block;
          padding: 5px 10px 4px;
          line-height: 1;
          color: #000 !important;
          font-size: 12px;
          transition: all .3s
        }

        .tab_menu .close a span {
          display: inline-block;
          background-repeat: no-repeat;
          background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMzMyIgY2xhc3M9ImJpIGJpLXgiIHZpZXdCb3g9IjAgMCAxNiAxNiI+CiAgPHBhdGggZD0iTTQuNjQ2IDQuNjQ2YS41LjUgMCAwIDEgLjcwOCAwTDggNy4yOTNsMi42NDYtMi42NDdhLjUuNSAwIDAgMSAuNzA4LjcwOEw4LjcwNyA4bDIuNjQ3IDIuNjQ2YS41LjUgMCAwIDEtLjcwOC43MDhM OCA4LjcwN2wtMi42NDYgMi42NDdhLjUuNSAwIDAgMS0uNzA4LS43MDhMNy4yOTMgOCA0LjY0NiA1LjM1NGEuNS41IDAgMCAxIDAtLjcwOCIvPgo8L3N2Zz4=");
          overflow: hidden;
          width: 30px;
          height: 30px;
          margin: -2px 0 0 3px;
          font-size: 0;
          text-indent: -3000em;
          vertical-align: middle;
          background-position: center;
          background-size: contain;
          transition: transform ease 0.2s;
        }

        .tab_menu .close a:hover span {
          -ms-transform: rotate(90deg);
          transform: rotate(90deg)
        }

        .tab_content {
          flex: 1;
          overflow-y: auto;
          box-sizing: border-box;
        }

        .tab_content>div {
          display: none;
        }

        .tab_content>.on {
          display: block;
        }

        .tab_desc {
          font-weight: normal;
          font-size: 13px;
          color: #777777;
          padding: 25px 10px;
        }

        .tab_desc em {
          color: #F86048;
        }

        .low {
          display: flex;
          background: #f4f4f4;
        }

        .low1,
        .low2 {
          width: 50%;
          padding: 30px;
          box-sizing: border-box;
        }

        .low1 {
          border-right: 1px solid #777777;
        }

        .tab_title {
          font-weight: bold;
          font-size: 15px;
          color: #777777;
          margin-bottom: 10px;
        }

        .low dt {
          font-weight: 500;
          font-size: 13px;
          color: #777777;
          margin-top: 10px;
        }

        .low dd {
          font-weight: normal;
          font-size: 13px;
          color: #777777;
          margin-left: 15px;
        }

        .low_link {
          height: 50px;
          flex-shrink: 0;
          display: flex;
          align-items: center;
          padding-left: 20px;
        }

        .low_link a {
          font-weight: 500;
          font-size: 13px;
          color: #31A182;
          text-decoration: none;
        }

        .viewLaw {
          display: inline-block;
          padding-right: 20px;
          background: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMxQTE4MiIgdmlld0JveD0iMCAwIDE2IDE2Ij4KICA8cGF0aCBkPSJNOCAxNkE4IDggMCAxIDAgOCAwYTggOCAwIDAgMCAwIDE2bS45My05LjQxMi0xIDQuNzA1Yy0uMDcuMzQuMDI5LjUzMy4zMDQuNTMzLjE5NCAwIC40ODctLjA3LjY4Ni0uMjQ2bC0uMDg4LjQxNmMtLjI4Ny4zNDYtLjkyLjU5OC0xLjQ2NS41OTgtLjcwMyAwLTEuMDAyLS40MjItLjgwOC0xLjMxOWwuNzM4LTMuNDY4Yy4wNjQtLjI5My4wMDYtLjM5OS0uMjg3LS40NzFsLS40NTEtLjA4MS4wODItLjM4MSAyLjI5LS4yODd6TTggNS41YTEgMSAwIDEgMSAwLTIgMSAxIDAgMCAxIDAgMiIvPgo8L3N2Zz4=") no-repeat right center;
          background-size: 16px 16px;
          cursor: pointer;
          margin-left: 20px;
        }

        .addition-info-form .form-row {
          padding: 18px 0;
          border-bottom: 1px solid #eee;
        }

        .addition-info-form .form-row:last-child {
          border-bottom: none;
        }

        .addition-info-form .form-label {
          margin-bottom: 10px;
          display: block;
        }

        .addition-info-form .value {
          margin-top: 4px;
        }
      </style>
      <div class="tab-panel on">
        <div class="manage-my-content">
          <form id="frm_recruit" onsubmit="return false" autocomplete="false">
            <input type="hidden" id="rc_idx" name="rc_idx" value="<?= ($_GET["gbn"] == "re" ? "0" : $info["rc_idx"]) ?>" />
            <input type="hidden" id="ad_zipcode" name="ad_zipcode" value="<?= $info["addr"]["ad_zipcode"] ?>" />
            <input type="hidden" id="ad_jibun" name="ad_jibun" value="<?= $info["addr"]["ad_jibun"] ?>" />
            <input type="hidden" id="ad_doro" name="ad_doro" value="<?= $info["addr"]["ad_doro"] ?>" />
            <input type="hidden" id="ad_detail" name="ad_detail" value="<?= $info["addr"]["ad_detail"] ?>" />
            <input type="hidden" id="ad_addr" name="ad_addr" value="<?= $info["addr"]["ad_addr"] ?>" />
            <input type="hidden" id="ad_gps_x" name="ad_gps_x" value="<?= $info["addr"]["ad_gps_x"] ?>" />
            <input type="hidden" id="ad_gps_y" name="ad_gps_y" value="<?= $info["addr"]["ad_gps_y"] ?>" />
            <input type="hidden" id="mm_comp_num" name="mm_comp_num" value="<?= $info["mm_comp_num"] ?>" />
            <input type="hidden" id="mm_comp_num_org" name="mm_comp_num_org" value="<?= $info["mm_comp_num"] ?>" />

            <div class="addition-info-form">
              <div class="form-table">
                <div class="form-row">
                  <p class="form-label" style="font-size: 0.9em;"><span class="fixed">*</span>표시는 필수 입력 항목입니다.</p>
                  <div class="form-group" style="position:static; right:auto;">
                    <span class="sr-text">간편등록 단일 폼</span>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label"><span class="fixed">*</span>공개여부</p>
                  <div class="form-group">
                    <label class="form-toggle-btn2">
                      <input type="radio" name="rc_show_yn" value="Y" <?= $info["rc_show_yn"] != "N" ? "checked" : "" ?>>
                      <span class="btn">공개</span>
                    </label>
                    <label class="form-toggle-btn2">
                      <input type="radio" name="rc_show_yn" value="N" <?= $info["rc_show_yn"] == "N" ? "checked" : "" ?>>
                      <span class="btn">비공개</span>
                    </label>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label"><span class="fixed">*</span>공고제목 <em>(최대 30자)</em></p>
                  <div class="value">
                    <label>
                      <span class="sr-text">공고제목 입력</span>
                      <input style="width:580px;" type="text" name="rc_name" placeholder="공고명을 작성해주세요" value="<?= $info["rc_name"] ?>" placeholder="공고명을 작성해주세요" autocomplete="off" aria-required="true" required class="form-text">
                      <div onclick="$('#recruiteListBox').toggle();" style="position: absolute; top: 33px; left: 808px; /* border: 1px solid #999; */ padding: 11px 5px 0 5px; border-radius: 6px;"><img src="/resource/shop/web/img/ic_arrow_down.svg"></div>
                      <div id="recruiteListBox" style="display:none; width: 580px; max-height: 120px; border: 1px solid #ccc; overflow-y: auto; overflow-x: hidden; background-color:#fff; border-radius: 8px; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2), -5px 5px 10px rgba(0, 0, 0, 0.2), 0px 10px 10px rgba(0, 0, 0, 0.2); ">
                        <script>
                          var recruiteJson = {};

                          function fSet(idx) {
                            $('input[name="rc_name"]').val(recruiteJson[idx]['rc_name']);
                            var rc_work = recruiteJson[idx]['rc_work'];
                            var work_checkboxes = document.querySelectorAll('input[type="checkbox"][name="rc_work[]"]');
                            var rc_workValues = rc_work.split(',');
                            for (var i = 0; i < work_checkboxes.length; i++) {
                              if (rc_workValues.includes(work_checkboxes[i].value)) {
                                work_checkboxes[i].checked = true;
                              } else {
                                work_checkboxes[i].checked = false;
                              }
                            }

                            var rc_work_type = recruiteJson[idx]['rc_work_type'];
                            var work_type_checkboxes = document.querySelectorAll('input[type="checkbox"][name="rc_work_type[]"]');
                            var rc_work_typeValues = rc_work_type.split(',');
                            for (var i = 0; i < work_type_checkboxes.length; i++) {
                              if (rc_work_typeValues.includes(work_type_checkboxes[i].value)) {
                                work_type_checkboxes[i].checked = true;
                              } else {
                                work_type_checkboxes[i].checked = false;
                              }
                            }
                            /*
                            var rc_price_type = recruiteJson[idx]['rc_price_type'];
                            var price_type_checkboxes = document.querySelectorAll('input[type="checkbox"][name="rc_price_type[]"]');
                            var rc_price_typeValues = rc_price_type.split(',');
                            for (var i = 0; i < price_type_checkboxes.length; i++) {
                              if (rc_price_typeValues.includes(price_type_checkboxes[i].value)) {
                                price_type_checkboxes[i].checked = true;
                              }else{
                                price_type_checkboxes[i].checked = false;
                              }
                            }
                            */
                            $('#rc_desc').text($('#rc_desc_' + idx).text());
                            $('#recruiteListBox').hide();

                          }
                        </script>
                        <ul style=" width: 580px;">
                          <?php $recruite_idx = 0; ?>
                          <?php foreach ($rc_last_recruite_list as $row) : ?>
                            <script>
                              recruiteJson[<?= $recruite_idx ?>] = {
                                idx: <?= $recruite_idx ?>,
                                rc_name: '<?= $row["rc_name"] ?>',
                                rc_work: '<?= $row["rc_work"] ?>',
                                rc_work_type: '<?= $row["rc_work_type"] ?>',
                                rc_price_type: '<?= $row["rc_price_type"] ?>'
                              };
                            </script>
                            <li style="list-style: none; width: 100%; height: 30px; line-height: 30px; /* border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc; */ padding-left: 10px; " onclick="fSet('<?= $recruite_idx ?>');"><?= $row["rc_name"] ?></li>
                            <?php $recruite_idx++; ?>
                          <?php endforeach; ?>
                        </ul>
                        <?php $recruite_idx = 0; ?>
                        <?php foreach ($rc_last_recruite_list as $row) : ?>
                          <textarea id="rc_desc_<?= $recruite_idx ?>" style="display:none;"><?= $row["rc_desc"] ?></textarea>
                          <?php $recruite_idx++; ?>
                        <?php endforeach; ?>
                      </div>
                    </label>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label"><span class="fixed">*</span>채용공고기간</p>
                  <div class="value">
                    <div class="inline-group">
                      <div class="recruit-range noti_duration">
                        <label>
                          <span class="sr-text">채용공고기간 입력</em></span>
                          <input type="text" name="rc_date" aria-required="true" required class="form-text js-datepicker2" style="font-size: 16px;">
                        </label>
                      </div>
                      <p class="help-msg text-only">모집일 기준 최대 2개월까지 공고가 가능합니다.</p>
                    </div>
                  </div>
                  <script>
                    (function() {
                      function chgMonth(instance) {
                        var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                          month = Number(instance.currentMonth) + 1,
                          year = instance.currentYear;
                        $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
                      }

                      window.addEventListener('DOMContentLoaded', function() {
                        if (typeof flatpickr == 'undefined') return false;
                        //달력 생성
                        flatpickr('.recruit-range .js-datepicker2', {
                          mode: 'range',
                          locale: 'ko',
                          dateFormat: 'Y-m-d',
                          ariaDateFormat: 'Y-m-d',
                          altFormat: 'Y.m.d',
                          altInput: true,
                          <?php if ($_GET["gbn"] == "re") { ?>
                            defaultDate: ['<?= Date("Y-m-d") ?>', '<?= Date("Y-m-d", strtotime("2 months")) ?>'],
                          <?php } else { ?>
                            defaultDate: ['<?= $info["rc_sdate"] != "" && get_date($info["rc_sdate"]) < Date("Y-m-d") ? Date("Y-m-d") : get_date($info["rc_sdate"]) ?>', '<?= get_date($info["rc_edate"]) ?>'],
                          <?php } ?>
                          nextArrow: '<span class="sr-text">다음</span>',
                          prevArrow: '<span class="sr-text">이전</span>',
                          disableMobile: true,
                          minDate: 'today',
                          maxDate: new Date().fp_incr(60), //2개월 60일 제한
                          onMonthChange: function(selectedDates, dateStr, instance) {
                            chgMonth(this);
                          },
                          onYearChange: function(selectedDates, dateStr, instance) {
                            chgMonth(this);
                          },
                          onDayCreate: function(dObj, dStr, fp, dayElem) {
                            var day = new Date(dayElem.getAttribute('aria-label')).getDay();
                            if (day == 0) {
                              dayElem.classList.add('sun');
                            } else if (day == 6) {
                              dayElem.classList.add('sat');
                            }
                          },
                          onReady: function() {
                            chgMonth(this);
                          }
                        });
                      });
                    }());
                  </script>
                </div>
                <div class="form-row address">
                  <p class="form-label"><span class="fixed">*</span>근무지역 <em>(최대 30자)</em></p>
                  <div class="value">
                    <!-- 현재 위치 -->
                    <label class="row">
                      <input type="text" hidden name="changeArea" value="<?= $info["addr"]["ad_addr"] ?>" readonly aria-required="true" required class="form-text js-find-loc">
                    </label>
                    <!-- 시도 -->
                    <label class="row">
                      <span class="sr-text">상세주소1</span>
                      <input type="text" style="width:580px;" name="rc_addr_doro" value="<?= $info["addr"]["ad_doro"] ?>" autocomplete="off" aria-required="true" required class="form-text">
                    </label>
                    <label class="row">
                      <span class="sr-text">상세주소2</span>
                      <input type="text" style="width:580px;" name="rc_addr_detail" value="<?= $info["addr"]["ad_detail"] ?>" placeholder="상세주소 입력" autocomplete="off" aria-required="true" required class="form-text">
                    </label>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label"><span class="fixed">*</span>업무구분 <em>최대 2개 선택가능</em></p>
                  <div class="form-group">
                    <?php
                    // 표시 허용 업무구분 화이트리스트
                    $allowed_work_list = ["생산", "사무직", "운전배달", "물류", "식당", "서비스", "매장관리", "건설", "상담"];
                    foreach ($rc_wrok_list as $row) :
                      // 허용 목록 이외 항목은 출력 제외
                      if (!in_array($row["cd_val"], $allowed_work_list, true)) {
                        continue;
                      }
                    ?>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_work[]" data-select-limit="2" value="<?= $row["cd_val"] ?>" data-img="<?= $row["cd_val2"] ?>" <?= in_array_return($row["cd_val"], $info["rc_work"], "check") ?>>
                        <span class="btn"><?= $row["cd_val"] ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label">희망국적 <em>다중선택가능</em></p>
                  <div class="form-group">
                    <label class="form-toggle-btn2">
                      <input type="checkbox" name="rc_nation[]" value="내국인" <?= in_array_return("내국인", $info["rc_nation"], "check") ?>>
                      <span class="btn">내국인</span>
                    </label>
                    <label class="form-toggle-btn2">
                      <input type="checkbox" name="rc_nation[]" value="외국인" <?= in_array_return("외국인", $info["rc_nation"], "check") ?>>
                      <span class="btn">외국인</span>
                    </label>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label">희망성별 <em>다중선택가능</em></p>
                  <div class="form-group">
                    <label class="form-toggle-btn2">
                      <input type="checkbox" name="rc_sex[]" value="남자" <?= in_array_return("남자", $info["rc_sex"], "check") ?>>
                      <span class="btn">남자</span>
                    </label>
                    <label class="form-toggle-btn2">
                      <input type="checkbox" name="rc_sex[]" value="여자" <?= in_array_return("여자", $info["rc_sex"], "check") ?>>
                      <span class="btn">여자</span>
                    </label>
                    <div style="margin-top: 7px;">
                      <p class="viewLaw" onClick="viewLaw(1);">남녀고용평등법 안내</p>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label">희망나이</p>
                  <div class="value">
                    <div class="inline-group" style="display: -webkit-box;">
                      <label class="form-selectbox col">
                        <span class="sr-text">최소나이 선택</span>
                        <select name="rc_sage" style="width:80px;height:30px;padding-left:12px;">
                          <option value="">선택</option>
                          <?php for ($icnt = 20; $icnt <= 80; $icnt++) : ?>
                            <option value="<?= $icnt ?>" <?= $info["rc_sage"] == $icnt ? "selected" : "" ?>><?= $icnt ?>세</option>
                          <?php endfor; ?>
                        </select>
                        <span class="liner"></span>
                      </label>
                      <span class="entity">~</span>
                      <label class="form-selectbox col">
                        <span class="sr-text">최대나이 선택</span>
                        <select name="rc_eage" style="width:80px;height:30px;padding-left:12px;">
                          <option value="">선택</option>
                          <?php for ($icnt = 20; $icnt <= 80; $icnt++) : ?>
                            <option value="<?= $icnt ?>" <?= $info["rc_eage"] == $icnt ? "selected" : "" ?>><?= $icnt ?>세</option>
                          <?php endfor; ?>
                        </select>
                        <span class="liner"></span>
                      </label>
                      <div style="margin-left: 15px;">
                        <p class="viewLaw" onClick="viewLaw(2);">연령차별금지법 안내</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label"><span class="fixed">*</span>근무형태 <em>다중선택가능</em></p>
                  <div class="form-group">
                    <?php foreach ($rc_work_type_list as $key => $row) : ?>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" id="rc_work_type_<?= $key ?>" name="rc_work_type[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info["rc_work_type"], "check") ?>>
                        <span class="btn"><?= $row["cd_val"] ?></span>
                      </label>
                    <?php endforeach; ?>
                  </div>
                  <span id="work_type_memo" style="display:none; margin-left: 20px;">근무협의</span>
                </div>
                <div class="form-row" id="time_cont1" <?= (array_search("주간", $info["rc_work_type"]) !== false || array_search("2교대", $info["rc_work_type"]) !== false) ? "" : "hidden" ?>>
                  <p class="form-label" style="text-align:right;padding-right:50px;font-weight:normal;font-size:16px;">근무시간(주간)</p>
                  <div class="value" style="position: absolute; top: 26px; left: 258px; width: 450px;">
                    <div class="inline-group" style="/* display: block; */">
                      <label class="form-selectbox col" style="width: 200px;">
                        <span class="sr-text">시간 선택</span>
                        <select id="rc_am_stime_h" name="rc_am_stime_h" style="float:left; width:60px;height:30px; text-align:center; color: #000; border: 1px solid #ADAEB9; border-radius: 10px; position:relative; opacity: initial; appearance: auto;">
                          <!-- <option value="">시</option> -->
                          <?php for ($t1 = 0; $t1 <= 23; $t1++) { ?>
                            <option value="<?= str_pad($t1, 2, "0", STR_PAD_LEFT) ?>" <?= substr($info["rc_am_stime"], 0, 2) == str_pad($t1, 2, "0", STR_PAD_LEFT) ? "selected" : ($t1 == 9 ? "selected" : "") ?>><?= str_pad($t1, 2, "0", STR_PAD_LEFT) ?></option>
                          <?php } ?>
                        </select>
                        <span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">시</span>
                        <input type="text" name="rc_am_stime_m" value="<?= substr($info["rc_am_stime"], 3, 2) ?>" placeholder="직접입력" style="float:left; width: 60px; height: 30px; text-align: center; background-color: #fff; border: 1px solid #ADAEB9; border-radius: 10px;">
                        <span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">분</span>
                        <input type="hidden" name="rc_am_stime" value="<?= $info["rc_am_stime"] ?>">
                        <span class="liner" style="display:none;"></span>
                      </label>
                      <span class="entity" style="display: inline-block;margin-right: 30px;">~</span>
                      <label class="form-selectbox col">
                        <span class="sr-text">시간 선택</span>
                        <select id="rc_am_etime_h" name="rc_am_etime_h" style="float:left; width:60px;height:30px; text-align:center; color: #000; border: 1px solid #ADAEB9; border-radius: 10px; position:relative; opacity: initial; appearance: auto;">
                          <!-- <option value="">시</option> -->
                          <?php for ($t2 = 0; $t2 <= 23; $t2++) { ?>
                            <option value="<?= str_pad($t2, 2, "0", STR_PAD_LEFT) ?>" <?= substr($info["rc_am_etime"], 0, 2) == str_pad($t2, 2, "0", STR_PAD_LEFT) ? "selected" : ($t2 == 18 ? "selected" : "") ?>><?= str_pad($t2, 2, "0", STR_PAD_LEFT) ?></option>
                          <?php } ?>
                        </select>
                        <span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">시</span>
                        <input type="text" name="rc_am_etime_m" value="<?= substr($info["rc_am_etime"], 3, 2) ?>" placeholder="직접입력" style="float:left; width: 60px; height: 30px; text-align: center; background-color: #fff; border: 1px solid #ADAEB9; border-radius: 10px;">
                        <span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">분</span>
                        <input type="hidden" name="rc_am_etime" value="<?= $info["rc_am_etime"] ?>">
                        <span class="liner" style="display:none;"></span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-row" id="time_cont2" <?= (array_search("야간", $info["rc_work_type"]) !== false || array_search("2교대", $info["rc_work_type"]) !== false) ? "" : "hidden" ?>>
                  <p class="form-label" style="text-align:right;padding-right:50px;font-weight:normal;font-size:16px;">근무시간(야간)</p>
                  <div class="value" style="position: absolute; top: 26px; left: 258px; width: 450px;">
                    <div class="inline-group" style="/* display: block; */">
                      <label class="form-selectbox col" style="width: 200px;">
                        <span class="sr-text">시간 선택</span>
                        <select id="rc_pm_stime_h" name="rc_pm_stime_h" style="float:left; width:60px;height:30px; text-align:center; color: #000; border: 1px solid #ADAEB9; border-radius: 10px; position:relative; opacity: initial; appearance: auto;">
                          <!-- <option value="">시</option> -->
                          <?php for ($t1 = 0; $t1 <= 23; $t1++) { ?>
                            <option value="<?= str_pad($t1, 2, "0", STR_PAD_LEFT) ?>" <?= substr($info["rc_pm_stime"], 0, 2) == str_pad($t1, 2, "0", STR_PAD_LEFT) ? "selected" : ($t1 == 21 ? "selected" : "") ?>><?= str_pad($t1, 2, "0", STR_PAD_LEFT) ?></option>
                          <?php } ?>
                        </select>
                        <span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">시</span>
                        <input type="text" name="rc_pm_stime_m" value="<?= substr($info["rc_pm_stime"], 3, 2) ?>" placeholder="직접입력" style="float:left; width: 60px; height: 30px; text-align: center; background-color: #fff; border: 1px solid #ADAEB9; border-radius: 10px;">
                        <span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">분</span>
                        <input type="hidden" name="rc_pm_stime" value="<?= $info["rc_pm_stime"] ?>">
                        <span class="liner" style="display:none;"></span>
                      </label>
                      <span class="entity" style="display: inline-block;margin-right: 30px;">~</span>
                      <label class="form-selectbox col">
                        <span class="sr-text">시간 선택</span>
                        <select id="rc_pm_etime_h" name="rc_pm_etime_h" style="float:left; width:60px;height:30px; text-align:center; color: #000; border: 1px solid #ADAEB9; border-radius: 10px; position:relative; opacity: initial; appearance: auto;">
                          <!-- <option value="">시</option> -->
                          <?php for ($t2 = 0; $t2 <= 23; $t2++) { ?>
                            <option value="<?= str_pad($t2, 2, "0", STR_PAD_LEFT) ?>" <?= substr($info["rc_pm_etime"], 0, 2) == str_pad($t2, 2, "0", STR_PAD_LEFT) ? "selected" : ($t2 == 6 ? "selected" : "") ?>><?= str_pad($t2, 2, "0", STR_PAD_LEFT) ?></option>
                          <?php } ?>
                        </select>
                        <span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">시</span>
                        <input type="text" name="rc_pm_etime_m" value="<?= substr($info["rc_pm_etime"], 3, 2) ?>" placeholder="직접입력" style="float:left; width: 60px; height: 30px; text-align: center; background-color: #fff; border: 1px solid #ADAEB9; border-radius: 10px;">
                        <span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">분</span>
                        <input type="hidden" name="rc_pm_etime" value="<?= $info["rc_pm_etime"] ?>">
                        <span class="liner" style="display:none;"></span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-row">
                  <p class="form-label">근무요일 <em>다중선택가능</em></p>
                  <div class="value">
                    <div class="inline-group week-day">
                      <div class="form-group">
                        <?php foreach ($rc_week_list as $row) : ?>
                          <label class="form-toggle-btn2 sm">
                            <input type="checkbox" name="rc_week[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $info["rc_week"], "check") ?>>
                            <span class="btn"><?= $row["cd_val"] ?></span>
                          </label>
                        <?php endforeach; ?>
                      </div>
                      <div class="option">
                        <label class="form-checkbox">
                          <input type="checkbox" name="rc_week_yn" value="Y" class="sr-text" <?= $info["rc_week_yn"] == "Y" ? "checked" : "" ?>>
                          <span class="label">요일무관</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-row starting-date">
                  <p class="form-label">근무시작일</p>
                  <div class="value">
                    <div class="inline-group">
                      <p class="is-abs" style="display:none;">시작일</p>
                      <label>
                        <span class="sr-text">시작일 입력</span>
                        <input type="text" name="rc_work_dt" aria-required="true" required class="form-text js-datepicker">
                      </label>
                    </div>
                  </div>

                  <script>
                    (function() {
                      function chgMonth(instance) {
                        var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                          month = Number(instance.currentMonth) + 1,
                          year = instance.currentYear;
                        $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
                      }

                      window.addEventListener('DOMContentLoaded', function() {
                        if (typeof flatpickr == 'undefined') return false;

                        //달력 생성
                        <?php
                        $rc_work_dt_defaultDate = $info["rc_work_dt"];
                        if ($_GET["gbn"] == "re") {
                          $rc_work_dt_defaultDate = Date("Y-m-d");
                        }
                        ?>
                        flatpickr('.starting-date .js-datepicker', {
                          locale: 'ko',
                          dateFormat: 'Y-m-d',
                          ariaDateFormat: 'Y-m-d',
                          altFormat: 'Y.m.d',
                          defaultDate: '<?= $rc_work_dt_defaultDate ?>',
                          altInput: true,
                          nextArrow: '<span class="sr-text">다음</span>',
                          prevArrow: '<span class="sr-text">이전</span>',
                          disableMobile: true,
                          position: 'bottom auto',
                          monthSelectorType: 'select', // 월 선택 활성화
                          //static: true, // 드롭다운 대신 정적 셀렉터
                          yearSelectorType: 'select',
                          onMonthChange: function(selectedDates, dateStr, instance) {
                            chgMonth(this);
                          },
                          onDayCreate: function(dObj, dStr, fp, dayElem) {
                            var day = new Date(dayElem.getAttribute('aria-label')).getDay();
                            if (day == 0) {
                              dayElem.classList.add('sun');
                            } else if (day == 6) {
                              dayElem.classList.add('sat');
                            }
                          },
                          onReady: function(selectedDates, dateStr, instance) {
                            //오늘버튼 추가
                            const todayButton = document.createElement("button");
                            todayButton.type = "button";
                            todayButton.textContent = "오늘";
                            todayButton.className = "flatpickr-today-button";
                            todayButton.style = "position: absolute; top: 29px; right: 83px; font-size: 14px; padding: 2px 5px; background-color: #fff; border: 1px solid #ccc; color: #777;";
                            todayButton.addEventListener('click', function() {
                              const today = new Date();
                              instance.setDate(new Date());

                              // flatpickr 인터페이스 내 연도 및 월을 오늘 날짜로 업데이트
                              instance.currentYear = today.getFullYear();
                              instance.currentMonth = today.getMonth();
                              instance.redraw(); // 달력 갱신

                              $(this).parent().find('.month').html((today.getMonth() + 1) + '<small>월</small>');
                              $(this).parent().find('.year').html(today.getFullYear());
                            });
                            instance.calendarContainer.appendChild(todayButton);

                            /* //년도선택 시 달력은 변하는데, 달력위의 년도값이 변하지 않아서 일단 remark
                            //년도선택 추가
                            const yearSelect = document.createElement("select");
                            yearSelect.className = "flatpickr-year-select";

                            // 현재 연도를 구하고, 전후 10년을 범위로 설정
                            const currentYear = new Date().getFullYear();
                            for (let i = currentYear - 10; i <= currentYear + 10; i++) {
                              const option = document.createElement("option");
                              option.value = i;
                              option.text = i;
                              if (i === currentYear) {
                                option.selected = true;
                              }
                              yearSelect.appendChild(option);
                            }

                            yearSelect.addEventListener('change', function() {
                              instance.currentYear = parseInt(this.value, 10);
                              instance.redraw();
                            });

                            // 달력 컨테이너에 연도 선택 추가
                            instance.calendarContainer.appendChild(yearSelect);
                            */

                            chgMonth(this);
                          }
                          //onReady: function () {
                          //  chgMonth(this);
                          //}
                        });
                      });
                    }());
                  </script>
                </div>

                <div class="form-row">
                  <p class="form-label"><span class="fixed">*</span>급여형태</p>
                  <div class="value">
                    <style>
                      .row {
                        display: flex;
                        gap: 8px;
                        flex-direction: column;
                      }

                      .extraField {
                        display: flex;
                        gap: 8px;
                        flex-direction: column;
                      }

                      .checks {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
                        gap: 10px;
                      }

                      .chip {
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                        width: 100%;
                        background: #fff;
                        border: 1px solid #ADAEB9;
                        border-radius: 999px;
                        padding: 10px 12px;
                        cursor: pointer;
                        justify-content: center;
                        color: #777;
                      }

                      .chip.on {
                        background: #57CCAF;
                        border: 1px solid #57CCAF;
                        color: #fff;
                      }

                      .chip input[type="radio"],
                      .chip2 input[type="radio"] {
                        display: none;
                      }

                      .checks2 {
                        display: flex;
                        gap: 12px;
                      }

                      .chip2 {
                        position: relative;
                        padding-left: 28px;
                        cursor: pointer;
                      }

                      .chip2.on {
                        color: #57CCAF;
                      }

                      .chip2::before {
                        content: "";
                        display: block;
                        position: absolute;
                        left: 5px;
                        width: 1.25rem;
                        height: 1.25rem;
                        background: url(https://nowjob.co.kr/resource/shop/mobile/img/ic_chkbox_off.svg) no-repeat 0 0;
                        background-size: contain;
                        border-radius: 100%;
                        z-index: 1;
                      }

                      .chip2.on::before {
                        background: url(https://nowjob.co.kr/resource/shop/mobile/img/ic_chkbox_on.svg) no-repeat 0 0;
                        background-size: contain;
                      }

                      .amountField {
                        display: flex;
                        gap: 8px;
                        height: 34px;
                      }

                      .amountField .amount {
                        display: flex;
                        gap: 4px;
                        align-items: center;
                        width: 40%;
                        border-radius: 999px;
                        border: 1px solid #57CCAF;
                      }

                      .amountField .amount input[type="text"] {
                        width: 100%;
                        margin-left: 16px;
                        border: none;
                        outline: none;
                      }

                      .amountField .amount input[type="text"]:focus {}

                      .amountField .amountNotice {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                      }

                      .amountField .amountNotice p {
                        display: inline-block;
                        padding-left: 20px;
                        background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMxQTE4MiIgdmlld0JveD0iMCAwIDE2IDE2Ij4KICA8cGF0aCBkPSJNOCAxNkE4IDggMCAxIDAgOCAwYTggOCAwIDAgMCAwIDE2bS45My05LjQxMi0xIDQuNzA1Yy0uMDcuMzQuMDI5LjUzMy4zMDQuNTMzLjE5NCAwIC40ODctLjA3LjY4Ni0uMjQ2bC0uMDg4LjQxNmMtLjI4Ny4zNDYtLjkyLjU5OC0xLjQ2NS41OTgtLjcwMyAwLTEuMDAyLS40MjItLjgwOC0xLjMxOWwuNzM4LTMuNDY4Yy4wNjQtLjI5My4wMDYtLjM5OS0uMjg3LS40NzFsLS40NTEtLjA4MS4wODItLjM4MSAyLjI5LS4yODd6TTggNS41YTEgMSAwIDEgMSAwLTIgMSAxIDAgMCAxIDAgMiIvPgo8L3N2Zz4=) no-repeat left center;
                        background-size: 16px 16px;
                      }

                      .amountField .amountNotice span {
                        color: red;
                      }

                      .unit {
                        white-space: nowrap;
                        padding-right: 12px;
                      }

                      .hide {
                        display: none !important;
                      }
                    </style>
                    <?php
                    $rc_price_type_list = ["시급", "일급", "주급", "월급", "협의"];
                    $rc_price_type_unit = [
                      "시급" => "원/시",
                      "일급" => "원/일",
                      "주급" => "원/주",
                      "월급" => "원/월",
                      "협의" => "-"
                    ];

                    function getConvertPriceType($rc_price_type, $rc_price_rep)
                    {

                      if ($rc_price_rep === "협의") {
                        return '';
                      }

                      $types = explode(',', $rc_price_type);

                      if (count($types) === 1) {
                        return '<em class="on">' . htmlspecialchars($types[0]) . '</em>';
                      }

                      $result = array_map(function ($type) use ($rc_price_rep) {
                        if ($type === $rc_price_rep) {
                          return '<em class="on">' . htmlspecialchars($type) . '</em>';
                        } else {
                          return '<em>' . htmlspecialchars($type) . '</em>';
                        }
                      }, $types);

                      return implode('', $result);
                    }

                    ?>
                    <div class="row">

                      <input type="hidden" class="hidden_price_value" name="rc_price_type" value="<?= $info["rc_price_type"] ?>">
                      <input type="hidden" class="hidden_price_value" name="rc_price_rep" value="<?= $info["rc_price_rep"] ?>">
                      <input type="hidden" class="hidden_price_value" name="rc_price_pay" value="<?= $info["rc_price_pay"] ?>">
                      <input type="hidden" class="hidden_price_value" name="rc_day_price" value="<?= $info["rc_day_price"] ?>">
                      <input type="hidden" class="hidden_price_value" name="rc_week_price" value="<?= $info["rc_week_price"] ?>">
                      <input type="hidden" class="hidden_price_value" name="rc_month_price" value="<?= $info["rc_month_price"] ?>">

                      <div class="checks" id="repChecks">
                        <?php foreach ($rc_price_type_list as $row) : ?>
                          <label class="chip <?= ($info['rc_price_rep'] === $row ? 'on' : '') ?>">
                            <input type="radio"
                              name="rc_price_rep_check"
                              value="<?= $row ?>"
                              <?= ($info['rc_price_rep'] === $row ? 'checked' : '') ?> />
                            <span><?= $row ?></span>
                          </label>
                        <?php endforeach; ?>
                      </div>

                      <div class="extraField <?= ($info['rc_price_rep'] === '협의' ? 'hide' : '') ?>" id="extraField">
                        <div class="amountField">
                          <div class="amount">
                            <input type="text"
                              class="form-text"
                              id="rc_price_pay"
                              value="<?= $info['rc_price_pay'] != null ? number_format($info['rc_price_pay']) : '' ?>"
                              placeholder="금액 입력" />
                            <span class="unit" id="amountUnit">
                              <?= isset($rc_price_type_unit[$info['rc_price_rep']]) ? $rc_price_type_unit[$info['rc_price_rep']] : '' ?>
                            </span>
                          </div>
                          <div class="amountNotice">
                            <p>최저임금제도 : 시급 <span>10,320</span>원</p>
                          </div>
                        </div>

                        <div class="checks2" id="extraChecks">
                          <?php foreach ($rc_price_type_list as $row) : ?>
                            <?php if ($row !== $info['rc_price_rep'] && $row !== "협의") : ?>
                              <label class="chip2 <?= (strpos($info['rc_price_type'], $row) !== false ? 'on' : '') ?>">
                                <input type="radio"
                                  name="rc_price_extra"
                                  value="<?= $row ?>"
                                  <?= (strpos($info['rc_price_type'], $row) !== false ? 'checked' : '') ?> />
                                <span><?= $row ?>가능</span>
                              </label>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        </div>
                      </div>
                    </div>
                    <script>
                      $(function() {
                        const PAY_TYPES = ["시급", "일급", "주급", "월급", "협의"];
                        const UNIT_BY_TYPE = {
                          "시급": "원/시",
                          "일급": "원/일",
                          "주급": "원/주",
                          "월급": "원/월",
                          "협의": "-"
                        };
                        const $repChecks = $("#repChecks"),
                          $amountUnit = $("#amountUnit"),
                          $amountInput = $("#rc_price_pay"),
                          $extraField = $("#extraField"),
                          $extraChecks = $("#extraChecks");

                        function getConvertPriceType(rc_price_type, rc_price_rep) {

                          if (rc_price_rep === "협의") {
                            return '';
                          }

                          const types = rc_price_type.split(',');

                          if (types.length === 1) {
                            return `<em class="on">${types[0]}</em>`;
                          }

                          return types.map(type => {
                            if (type === rc_price_rep) {
                              return `<em class="on">${type}</em>`;
                            } else {
                              return `<em>${type}</em>`;
                            }
                          }).join('');
                        }

                        function buildExtraChecks(rep) {
                          $extraChecks.empty();
                          const extras = PAY_TYPES.filter(t => t !== rep && t !== "협의");
                          extras.forEach((t, i) => {
                            $extraChecks.append(`<label class="chip2"><input type="radio" name="rc_price_extra" value="${t}" /> <span>${t}가능</span></label>`);
                          });
                        }

                        function setVisibility(rep) {
                          const isNeg = rep === "협의";
                          $extraField.toggleClass("hide", isNeg);
                          if (isNeg) $amountInput.val("");
                        }

                        $(document).on("click", 'input[name="rc_price_rep_check"], input[name="rc_price_extra"]', function(e) {
                          const $this = $(this);
                          const name = $this.attr('name');
                          const val = $this.val();

                          $(`input[name="${name}"]`).closest('label').removeClass('on');

                          if ($this.data("waschecked") === true) {
                            $this.prop("checked", false).data("waschecked", false);

                            if (name === "rc_price_rep_check") {
                              $amountInput.val("");
                              $('.hidden_price_value').val('');
                              $extraChecks.empty();
                              $extraField.addClass("hide");
                            }
                            pricePayUpdate();
                            e.preventDefault();
                            return;
                          }
                          $(`input[name="${name}"]`).data("waschecked", false);
                          $this.data("waschecked", true).closest('label').addClass('on');

                          if (name === "rc_price_rep_check") {
                            $amountInput.val("");
                            $('.hidden_price_value').val('');
                            $amountUnit.text(UNIT_BY_TYPE[val] || "원");
                            setVisibility(val);
                            if (val !== "협의") buildExtraChecks(val);
                          }
                          pricePayUpdate();
                        });

                        $amountInput.on("input", function() {
                          let val = $(this).val().replace(/,/g, "");
                          if (val && !isNaN(val)) {
                            $(this).val(Number(val).toLocaleString());
                          }
                          pricePayUpdate();
                        });

                        function pricePayUpdate() {
                          const rep = $('input[name="rc_price_rep_check"]:checked').val();
                          if (!rep) {
                            return;
                          }

                          const isNeg = rep === "협의";
                          let amount = null;
                          if (!isNeg) {
                            amount = $amountInput.val().replace(/,/g, "");
                            if (!amount) {
                              return;
                            }
                            amount = Number(amount);
                          }

                          const rc_price_type_tmp = [];
                          $('input[name="rc_price_rep_check"]:checked, input[name="rc_price_extra"]:checked').each(function() {
                            rc_price_type_tmp.push($(this).val());
                          });

                          const rc_price_type = rc_price_type_tmp
                            .sort((a, b) => PAY_TYPES.indexOf(a) - PAY_TYPES.indexOf(b))
                            .join(",");

                          $('[name="rc_price_type"]').val(rc_price_type);
                          $('[name="rc_price_rep"]').val(rep);
                          $('[name="rc_price_pay"]').val(amount);

                          // 전체 시급 적용전까지 임시처리 시작
                          if (rep == '일급') {
                            $('[name="rc_day_price"]').val(amount);
                            $('[name="rc_week_price"]').val('');
                            $('[name="rc_month_price"]').val('');
                          }
                          if (rep == '주급') {
                            $('[name="rc_day_price"]').val('');
                            $('[name="rc_week_price"]').val(amount);
                            $('[name="rc_month_price"]').val('');
                          }
                          if (rep == '월급') {
                            $('[name="rc_day_price"]').val('');
                            $('[name="rc_week_price"]').val('');
                            $('[name="rc_month_price"]').val(amount);
                          }
                          if (rep == '협의') {
                            $('[name="rc_day_price"]').val('');
                            $('[name="rc_week_price"]').val('');
                            $('[name="rc_month_price"]').val('');
                          }
                          // 전체 시급 적용전까지 임시처리 끝
                        }

                      });
                    </script>
                  </div>
                </div>

                <div class="form-row services">
                  <p class="form-label">복리후생</p>
                  <div class="value">
                    <p class="secondary-title">보험</p>
                    <div class="form-group">
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="국민연금" <?= in_array_return("국민연금", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit1-1">국민연금</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="고용보험" <?= in_array_return("고용보험", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit1-2">고용보험</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="산재보험" <?= in_array_return("산재보험", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit1-3">산재보험</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="건강보험" <?= in_array_return("건강보험", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit1-4">건강보험</span>
                      </label>
                    </div>
                    <p class="secondary-title">휴무/휴가</p>
                    <div class="form-group">
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="연차" <?= in_array_return("연차", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit2-1">연차</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="월차" <?= in_array_return("월차", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit2-2">월차</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="여름휴가" <?= in_array_return("여름휴가", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit2-3">여름휴가</span>
                      </label>
                    </div>
                    <p class="secondary-title">수당/퇴직금</p>
                    <div class="form-group">
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="야근근로수당" <?= in_array_return("야근근로수당", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-1">야근근로수당</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="휴일근로수당" <?= in_array_return("휴일근로수당", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-2">휴일근로수당</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="장기근속수당" <?= in_array_return("장기근속수당", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-3">장기근속수당</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="연장근로수당" <?= in_array_return("연장근로수당", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-4">연장근로수당</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="인센티브제" <?= in_array_return("인센티브제", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-5">인센티브제</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="퇴직금" <?= in_array_return("퇴직금", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit3-6">퇴직금</span>
                      </label>
                    </div>
                    <p class="secondary-title">생활편의지원</p>
                    <div class="form-group">
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="조식제공" <?= in_array_return("조식제공", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-1">조식제공</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="중식제공" <?= in_array_return("중식제공", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-2">중식제공</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="석식제공" <?= in_array_return("석식제공", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-3">석식제공</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="근무복지급" <?= in_array_return("근무복지급", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-4">근무복지급</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="통근버스운행" <?= in_array_return("통근버스운행", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-5">통근버스운행</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="차량유류비지원" <?= in_array_return("차량유류비지원", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-6">차량유류비지원</span>
                      </label>
                      <label class="form-toggle-btn2">
                        <input type="checkbox" name="rc_life_type[]" value="기숙사운영" <?= in_array_return("기숙사운영", $info["rc_life_type"], "check") ?>>
                        <span class="btn benefit-label benefit4-7">기숙사운영</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="form-row">
                  <p class="form-label">상세업무정보 <em>최대500자</em></p>
                  <div class="value" style="min-height: 398px; height: auto;">
                    <label>
                      <span class="sr-text">상세업무정보 입력</span>
                      <textarea id="rc_desc" name="rc_desc" maxlength="500" class="form-textarea js-autoheight" style="height:600px; min-height:600px;"><?= $info["rc_desc"] ?></textarea>
                    </label>
                  </div>
                </div>

                <div class="form-row">
                  <p class="form-label">해시태그</p>
                  <div class="value">
                    <label>
                    </label>
                    <span class="sr-text">상세업무정보 입력</span>
                    <textarea style="display:none;" name="rc_hash" maxleng="100" placeholder="예) #경력무관, #나이무관 #생산" class="form-textarea sm"><?= $info["rc_hash"] ?></textarea>
                    <script>
                      var hashInput = '<span style="margin-right:10px; border: none; height: 28px; display: inline-block; line-height: 26px; border-radius: 25px; padding: 0 10px;">#<input type="text" onkeyup="fHashKey(this,event);" style=" min-width: 90px; max-width: 150px;height: 24px; border: none; margin-left: 5px; background-color: transparen;" placeholder="태그 입력(최대 30개)"><span class="hashdel" style="margin-left: 5px; cursor: pointer;display:none;" onclick="fHashDel(this);">ⓧ</span></span>';

                      function fHashDel(obj) {
                        if ($('#hashbox').find('input').length == 1) {
                          alert('해시태그 입력란을 삭제 할 수 없습니다.');
                          return;
                        }
                        if ($(obj).parent().find('input').first().val() == '') {
                          alert('해시태그 입력란을 삭제 할 수 없습니다.');
                          return;
                        }
                        $(obj).parent().remove();
                      }

                      function fHashKey(obj, evt) {
                        if (evt.keyCode == 13 && $(obj).val().replaceAll(' ', '') != '') {
                          $(obj).css('border', 'none');
                          $(obj).css('background-color', 'transparent');
                          $(obj).attr('readonly', 'readonly');
                          $(obj).parent().css('background-color', '#ccc');
                          $(obj).parent().css('color', '#333');
                          $(obj).parent().find('.hashdel').show();

                          //24.09.04 input의 가로폭 변경 시작
                          var temp = document.createElement('span');
                          temp.style.visibility = 'hidden';
                          temp.style.whiteSpace = 'nowrap';
                          temp.style.width = 'fit-content';
                          temp.style.fontSize = window.getComputedStyle(obj).fontSize;
                          temp.style.fontFamily = window.getComputedStyle(obj).fontFamily;

                          // Add input text to the temp element
                          temp.textContent = obj.value || obj.placeholder;

                          document.body.appendChild(temp);

                          // Adjust the input width based on the temp element width
                          obj.style.width = (temp.offsetWidth + 5) + 'px';
                          obj.style.minWidth = 'auto';

                          // Remove the temp element
                          document.body.removeChild(temp);
                          //24.09.04 input의 가로폭 변경 종료

                          $('#hashbox').append(hashInput);
                          $('#hashbox').find('input').last().focus();
                        }
                      }
                    </script>
                    <div id="hashbox" style="display: block; width: 100%; float: left; margin-top: 15px; border: 1px solid #ADAEB9; min-height: 100px; background-color: #fff;padding: 10px;line-height: 38px;">
                      <span style="margin-right:10px; border: none; height: 28px; display: inline-block; line-height: 26px; border-radius: 25px; padding: 0 10px; color:#aaa;">#<input type="text" onkeyup="fHashKey(this,event);" style="min-width: 90px; max-width: 150px;height: 24px; border: none; margin-left: 5px;" placeholder="태그 입력(최대 30개)"><span class="hashdel" style="margin-left: 5px; cursor: pointer;display:none;" onclick="fHashDel(this);">ⓧ</span></span>
                    </div>
                    <span style="display: block; margin-top: 10px;">해시태그 입력 후 엔터키를 눌러 다른 해시태그를 작성할 수 있습니다</span>
                  </div>
                </div>

                <div class="form-row">
                  <p class="form-label">첨부사진</p>
                  <div class="value">
                    <div class="form-pic-group">
                      <label class="form-pic first">
                        <input type="file" name="rc_img1" id="rc_img1" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(1);">
                        <span class="label" style="margin-top: 20px;">기업을 대표하는<br>사진을 첨부해주세요</span>
                        <span class="preview" id="prev1" onmouseover="$('#delimgbtn1').show();" onmouseout="$('#delimgbtn1').hide();">
                          <?php if (getData($info, "rc_img1") != "") : ?>
                            <img id="imgbox1" src="<?= getData($info, "rc_img1") ?>" />
                            <input type='hidden' name='rc_img1_ori' id='rc_img1_ori' value='<?= getData($info, "rc_img1") ?>' />
                          <?php endif; ?>
                        </span>
                      </label>
                      <label class="form-pic">
                        <input type="file" name="rc_img2" id="rc_img2" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(2);">
                        <span class="label" style="margin-top: 20px;"><!--사진등록--></span>
                        <span class="preview" id="prev2" onmouseover="$('#delimgbtn2').show();" onmouseout="$('#delimgbtn2').hide();">
                          <?php if (getData($info, "rc_img2") != "") : ?>
                            <img id="imgbox2" src="<?= getData($info, "rc_img2") ?>" onmouseover="$('#delimgbtn2').show();" onmouseout="$('#delimgbtn2').hide();" />
                            <input type='hidden' name='rc_img2_ori' id='rc_img2_ori' value='<?= getData($info, "rc_img2") ?>' />
                          <?php endif; ?>
                        </span>
                      </label>
                      <label class="form-pic">
                        <input type="file" name="rc_img3" id="rc_img3" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(3);">
                        <span class="label" style="margin-top: 20px;"><!--사진등록--></span>
                        <span class="preview" id="prev3" onmouseover="$('#delimgbtn3').show();" onmouseout="$('#delimgbtn3').hide();">
                          <?php if (getData($info, "rc_img3") != "") : ?>
                            <img id="imgbox3" src="<?= getData($info, "rc_img3") ?>" onmouseover="$('#delimgbtn3').show();" onmouseout="$('#delimgbtn3').hide();" />
                            <input type='hidden' name='rc_img3_ori' id='rc_img3_ori' value='<?= getData($info, "rc_img3") ?>' />
                          <?php endif; ?>
                        </span>
                      </label>
                      <label class="form-pic">
                        <input type="file" name="rc_img4" id="rc_img4" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(4);">
                        <span class="label" style="margin-top: 20px;"><!--사진등록--></span>
                        <span class="preview" id="prev4" onmouseover="$('#delimgbtn4').show();" onmouseout="$('#delimgbtn4').hide();">
                          <?php if (getData($info, "rc_img4") != "") : ?>
                            <img id="imgbox4" src="<?= getData($info, "rc_img4") ?>" onmouseover="$('#delimgbtn4').show();" onmouseout="$('#delimgbtn4').hide();" />
                            <input type='hidden' name='rc_img4_ori' id='rc_img4_ori' value='<?= getData($info, "rc_img4") ?>' />
                          <?php endif; ?>
                        </span>
                      </label>
                      <label class="form-pic">
                        <input type="file" name="rc_img5" id="rc_img5" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(5);">
                        <span class="label" style="margin-top: 20px;"><!--사진등록--></span>
                        <span class="preview" id="prev5" onmouseover="$('#delimgbtn5').show();" onmouseout="$('#delimgbtn5').hide();">
                          <?php if (getData($info, "rc_img5") != "") : ?>
                            <img id="imgbox5" src="<?= getData($info, "rc_img5") ?>" onmouseover="$('#delimgbtn5').show();" onmouseout="$('#delimgbtn5').hide();" />
                            <input type='hidden' name='rc_img5_ori' id='rc_img5_ori' value='<?= getData($info, "rc_img5") ?>' />
                          <?php endif; ?>
                        </span>
                      </label>
                    </div>
                  </div>
                  <p class="help-msg">사진을 추가하여 지원자들에게 업무를 소개해 보세요.
                    <span style="display: block; color: #1B9D7D;"> ※ 권장사이즈 : 800 * 380</span>
                  </p>
                </div>

              </div>
            </div>
            <div class="form-action-wrap">
              <button id="btnStep" type="button" class="btn-round btn-tertiary"><?= $info["rc_idx"] != "0" ? ($_GET["gbn"] == "re" ? "등록" : "수정") : "등록" ?>완료</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->


<div class="law_popup">
  <div class="tab_menu">
    <div class="tab1 on">남녀고용평등법 안내</div>
    <div class="tab2">연령차별금지법 안내</div>
    <div class="close"><a href="#"><span></span></a></div>
  </div>
  <div class="tab_content">
    <div class="tab_content1 on">
      <p class="tab_desc">
        채용 시 남녀를 차별하거나, 여성 근로자를 채용할 때 직무 수행에 불필요한 용모,키,체중 등의 신체조건 및 미혼 조건을 제시 또는 요구하는 경우 남녀고용평등법 위반에 해당되어 500만원 이하의 벌금이 부과될 수 있습니다.<br>
        <em>또한 등록제한규정에 따라 사전 동의 없이 삭제 조치될 수 있습니다.</em>
      </p>

      <div class="low">
        <div class="low1">
          <p class="tab_title">차별기준</p>
          <dl>
            <dt>특정성을 지칭하는 직종명 등 사용하는 경우</dt>
            <dd>- 남성 선반공, 병역필 남자에 한함, 세일즈맨, 웨이터, 여성비서, 미용사(여성 환영) 등</dd>
            <dt>직종 등 남녀 분리모집 하거나 모집인원수를 남녀를 달리 정한 경우</dt>
            <dd>- 관리·사무직 남성 10명, 판매직 여성 5명 → (개선) 관리·사무직 10명, 판매직 5명</dd>
            <dd>- 대졸남성 100명, 대졸여성 20명 → (개선) 대졸 120명</dd>
            <dt>학력·경력 등 동일한 자격임에도 다른 성별을 불리한 고용형태로 차등 채용</dt>
            <dd>- 3급 사원:4년제 대졸 남자, 4급 사원:4년제 대졸 여자</dd>
            <dt>직무 수행상 필요하지 않은 채용조건 부과 및 특성 성에 대해서만 다른 조건을 부여</dt>
            <dd>- 남성은 키 170cm, 체중 60kg 이상인 자 → (개선) 신체기준 삭제</dd>
            <dd>- 여성은 키 160cm, 체중 50kg 미만인 자 → (개선) 신체기준 삭제</dd>
            <dt>모집·채용에 관한 정보를 제공함에 있어 성별로 다르게 취합하는 경우</dt>
            <dd>- 회사에 대한 안내 등의 자료를 특정 성에게만 송부하고 다른 성에게는 제공하지 않는 등의 행위</dd>
            <dd>- 여성은 키 160cm, 체중 50kg 미만인 자 → (개선) 신체기준 삭제</dd>
          </dl>
        </div>

        <div class="low2">
          <p class="tab_title">차별로 보지 않는 사례</p>
          <dl>
            <dt>직무 성질상 특정한 성이 불가피한 경우</dt>
            <dd>- 남성 역할 위한 배우·모델 등</dd>
            <dd>- 여성 목욕탕의 여성 목욕 관리사, 여성 장애인·여성 환자의 도우미, 기숙사 사감 등</dd>
            <dd>- 교도관, 경비원 등 방법상 특정 성에 대한 배려를 필요로 하는 경우 등</dd>
            <dt>근로기준법 등 관계법령 상 여성취업이 금지된 직종에 남성만 채용하는 경우</dt>
            <dd>- 근로기준법 제65조(사용금지), 제72조(갱내근로금지) 및 같은 법시행령 제40조<br>(임산부 등의 사용금지 직종)에 근거</dd>
            <dt>현존하는 차별 없애거나 고용 평등 촉진 위해 잠정적으로 특정성 우대하는 조치를 하는 경우 등</dt>
          </dl>
        </div>
      </div>
      <div class="low_link">
        <a href="https://www.law.go.kr/LSW/lsInfoP.do?lsId=000130&ancYnChk=0#0000" target="_blank">*남녀고용평등법 자세히보기</a>
      </div>
    </div>
    <div class="tap_content2">
      <p class="tab_desc">
        채용 시 합리적인 이유 없이 연령제한을 하는 경우 연령차별금지법 위반에 해당되어 500만원 이하의 벌금이 부과될 수 있습니다.<br>
        <em>또한 등록제한규정에 따라 사전 동의 없이 삭제 조치될 수 있습니다</em>
      </p>
      <div class="low">
        <div class="low1">
          <p class="tab_title">차별기준</p>
          <dl>
            <dt>지원자의 나이를 제한하거나 특정 연령층을 선호하는 경우</dt>
          </dl>
        </div>

        <div class="low2">
          <p class="tab_title">차별로 보지 않는 사례</p>
          <dl>
            <dt>직무 성질상 특정 연령 기준이 불가피한 경우(진정 직업 자격)</dt>
            <dt>근속 기간의 차이를 고려하여 임금이나 임금 외의 금품과 복리후생에서 합리적인 차등을 두는 경우</dt>
            <dt>법률 등에 근거한 정년의 설정</dt>
            <dt>이 법이나 다른 법률에 따라 특정 연령 집단의 고용유지·촉진을 위한 지원 조치를 하는 경우</dt>
          </dl>
        </div>
      </div>
      <div class="low_link">
        <a href="https://www.law.go.kr/LSW/lsInfoP.do?lsId=000121&ancYnChk=0#0000" target="_blank">*연령차별금지법 자세히보기</a>
      </div>
    </div>
  </div>
</div>

<script>
  function viewLaw(no) {
    $('.law_popup').addClass('on');

    $('.tab_menu > div').removeClass('on');
    $('.tab_content > div').removeClass('on');

    if (no === 1) {
      $('.tab1').addClass('on');
      $('.tab_content1').addClass('on');
    } else if (no === 2) {
      $('.tab2').addClass('on');
      $('.tap_content2').addClass('on');
    }
  }

  $(document).ready(function() {
    $('.tab1').click(function() {
      $('.tab_menu > div').removeClass('on');
      $('.tab_content > div').removeClass('on');
      $('.tab1').addClass('on');
      $('.tab_content1').addClass('on');
    });

    $('.tab2').click(function() {
      $('.tab_menu > div').removeClass('on');
      $('.tab_content > div').removeClass('on');
      $('.tab2').addClass('on');
      $('.tap_content2').addClass('on');
    });

    $('.close').click(function() {
      $('.law_popup').removeClass('on');
    });

    $(document).mouseup(function(e) {
      var container = $(".law_popup");
      if (container.hasClass('on') && !container.is(e.target) && container.has(e.target).length === 0) {
        container.removeClass('on');
      }
    });
  });
</script>

<script>
  $("[name=rc_addr_doro]").click(function() {
    var $panel = $("#changeArea");
    // 패널을 열 때 hidden 속성 제거 및 on 클래스 추가
    // $panel[0].hidden = false; 
    // 해당 코드는 jQuery 객체 '$panel'의 첫 번째 원소(일반적으로 DOM 요소)를 가져와서,
    // HTML5의 hidden 속성을 false로 설정함으로써 패널(주소변경 팝업 레이어)이 화면에 표시되도록 한다.
    // 즉, $('#changeArea')[0].hidden = false; 와 동일하며,
    // changeArea 레이어의 숨김 상태를 해제(visible)시키는 역할을 한다.
    $panel[0].hidden = false;
    $panel.addClass("on");
    // body 스크롤 잠금
    document.body.style.overflow = 'hidden';
    // 팝업을 열 때 기존 입력창 포커스를 제거하고 팝업 입력창으로 이동해 오타를 방지
    $(this).blur();
    var $popupAddrInput = $("#changeArea").find("#txt_addr_popup");
    if ($popupAddrInput.length) {
      // DOM 갱신 이후 안전하게 포커스를 옮김
      setTimeout(function() {
        $popupAddrInput.focus();
        // 패널에 포커스 설정
        $panel[0].focus();
      }, 100);
    }
  });

  // 검색 완료 선택 처리 - recruit_write 전용 함수
  function selectAddrInfoRecruit(zipcode, doro, jibun, addr, x, y) {
    $("#ad_zipcode").val(zipcode);
    $("#ad_jibun").val(jibun); // 지번 주소
    $("#ad_doro").val(doro); // 도로명 주소
    // ad_addr에는 지번을 클릭하든 도로명을 클릭하든 항상 지번을 저장
    $("#ad_addr").val(jibun);
    $("#ad_gps_x").val(x);
    $("#ad_gps_y").val(y);
    // 선택된 주소(도로명 또는 지번)를 rc_addr_doro 필드에 설정
    // addr 파라미터는 사용자가 클릭한 버튼에 따라 도로명 또는 지번 전체 주소가 전달됨
    $("[name=rc_addr_doro]").val(addr);
    $("[name=changeArea]").val(addr);
    $("#changeArea").removeClass("on");
    // 패널이 닫힐 때 스크롤 복원
    document.body.style.overflow = '';
  }

  // 기존 selectAddrInfo 함수를 recruit_write용으로 재정의
  // 이렇게 하면 다른 페이지의 함수와 충돌하지 않음
  // 페이지 로드 후 실행되도록 $(document).ready() 사용
  $(document).ready(function() {
    if (window.location.pathname.indexOf('/compage/recruit_write') !== -1) {
      window.selectAddrInfo = selectAddrInfoRecruit;
      
      // 패널이 닫힐 때 스크롤 복원을 위한 감시자 추가
      // changeArea 패널의 on 클래스가 제거될 때 스크롤 복원
      var $panel = $("#changeArea");
      if ($panel.length > 0) {
        // MutationObserver를 사용하여 패널의 클래스 변경 감지
        var observer = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
              var $target = $(mutation.target);
              // on 클래스가 제거되었고 패널이 숨겨진 경우 스크롤 복원
              if (!$target.hasClass('on') && ($target[0].hidden === true || $target.css('display') === 'none')) {
                document.body.style.overflow = '';
              }
            }
            // hidden 속성이 변경된 경우도 처리
            if (mutation.type === 'attributes' && mutation.attributeName === 'hidden') {
              var $target = $(mutation.target);
              if ($target[0].hidden === true) {
                document.body.style.overflow = '';
              }
            }
          });
        });
        
        // 패널 요소의 속성 변경 감시 시작
        observer.observe($panel[0], {
          attributes: true,
          attributeFilter: ['class', 'hidden', 'style']
        });
        
        // 패널 닫기 버튼 클릭 시에도 스크롤 복원 (추가 안전장치)
        $(document).on('click', '#changeArea .btn-close-popup, #changeArea .btnCloseArea', function() {
          setTimeout(function() {
            document.body.style.overflow = '';
          }, 100);
        });
      }
    }
  });
</script>

<style>
  <?php
  // SNS 로그인 여부 확인
  $mem_info_di = isset($info["mem_info"]["mm_di"]) ? $info["mem_info"]["mm_di"] : null;
  $mem_info_join_type = isset($info["mem_info"]["mm_join_type"]) ? $info["mem_info"]["mm_join_type"] : null;
  $is_sns_login = !empty($mem_info_join_type) && in_array(strtoupper($mem_info_join_type), ["KAKAO", "APPLE", "NAVER"]);
  // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
  $is_pass_authenticated = ($mem_info_di != null && (strpos($mem_info_di, "MC0") === 0 || $is_sns_login));
  // 기업정보 인증 여부 확인
  $is_comp_authenticated = ($info != null && $info["mem_info"] != null && !empty($info["mem_info"]["mm_comp_num"]));

  if ($info == null || $info["mem_info"] == null || (!$is_pass_authenticated || !$is_comp_authenticated)) {
  ?>#defaultDialog1 .btn-close-popup {
    display: none;
  }

  #defaultDialog2 .btn-close-popup {
    display: none;
  }

  #defaultDialog3 .btn-close-popup {
    display: none;
  }

  #defaultDialog4 .btn-close-popup {
    display: none;
  }

  #defaultDialog5 .btn-close-popup {
    display: none;
  }

  <?php } ?>
</style>

<script>
  $(document).ready(function() {

    <?php
    // SNS 로그인 여부 확인
    $mem_info_di = isset($info["mem_info"]["mm_di"]) ? $info["mem_info"]["mm_di"] : null;
    $mem_info_join_type = isset($info["mem_info"]["mm_join_type"]) ? $info["mem_info"]["mm_join_type"] : null;
    $is_sns_login = !empty($mem_info_join_type) && in_array(strtoupper($mem_info_join_type), ["KAKAO", "APPLE", "NAVER"]);
    // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
    $is_pass_authenticated = ($mem_info_di != null && (strpos($mem_info_di, "MC0") === 0 || $is_sns_login));
    // 기업정보 인증 여부 확인
    $is_comp_authenticated = ($info != null && $info["mem_info"] != null && !empty($info["mem_info"]["mm_comp_num"]));

    // recruit_list.php에서 인증 후 이동한 경우이므로 자동 팝업 표시하지 않음
    // (recruit_list.php에서 이미 팝업을 표시했으므로)
    ?>

    $("[name='rc_work_type[]']").change(function() {
      $("#time_cont").attr("hidden", "");
      $("#time_cont1").attr("hidden", "");
      $("#time_cont2").attr("hidden", "");

      if ($("#rc_work_type_0").is(":checked") == true) {
        $("#time_cont").removeAttr("hidden");
        $("#time_cont1").removeAttr("hidden");
      }

      if ($("#rc_work_type_1").is(":checked") == true) {
        $("#time_cont").removeAttr("hidden");
        $("#time_cont2").removeAttr("hidden");
      }

      if ($("#rc_work_type_2").is(":checked") == true) {
        $("#time_cont").removeAttr("hidden");
        $("#time_cont1").removeAttr("hidden");
        $("#time_cont2").removeAttr("hidden");
      }

      if ($("[name='rc_work_type[]']:checked").length > 2) {
        $(this).closest(".form-row").find(".add_text").removeClass("hidden");
        $('#work_type_memo').show();
      } else {
        $(this).closest(".form-row").find(".add_text").addClass("hidden");
        $('#work_type_memo').hide();
      }
    });

    $("#btnPrev").click(function() {
      history.go(-1);
    });

    function formatDate(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0'); // 월은 0부터 시작하므로 +1, 두 자리로 만듭니다.
      const day = String(date.getDate()).padStart(2, '0'); // 일, 두 자리로 만듭니다.
      return `${year}-${month}-${day}`;
    }

    $("#btnStep").click(function() {

      if ($("[name='rc_name']").val() == "") {
        commonAlert("공고제목을 입력해주세요.", function() {
          $("[name='rc_name']").focus();
        });
        return;
      }

      if ($("[name='rc_date']").val() == "") {
        const today = new Date();
        // 2개월 후의 날짜 구하기
        const twoMonthsLater = new Date(today);
        twoMonthsLater.setMonth(today.getMonth() + 2);
        $("[name='rc_date']").val(formatDate(today) + ' ~ ' + formatDate(twoMonthsLater));
      }

      if ($("[name='ad_addr']").val() == "") {
        commonAlert("주소를 등록해주세요.", function() {});
        return;
      }

      if ($("[name='rc_addr_doro']").val() == "") {
        commonAlert("주소를 등록해주세요.", function() {});
        return;
      }

      var rc_work_img = "";
      for (var icnt = 0; icnt < $("[name='rc_work[]']:checked").length; icnt++) {
        if (rc_work_img == "") {
          rc_work_img = $("[name='rc_work[]']:checked").eq(icnt).data("img");
        } else {
          rc_work_img += "," + $("[name='rc_work[]']:checked").eq(icnt).data("img");
        }
      }

      $("[name=rc_work_img]").val(rc_work_img);
      $("#ad_detail").val($("[name=rc_addr_detail]").val());

      if ($("[name='rc_work[]']").is(":checked") == false) {
        commonAlert("희망업무구분은 하나이상 선택해주세요.", function() {});
        return;
      }

      if ($("[name='ma_sage']").val() != "" && $("[name='ma_eage']").val() != "" && $("[name='ma_sage']").val() > $("[name='ma_eage']").val()) {
        commonAlert("희망나이는 앞에 설정한 나이가 뒤에 설정한 나이보다 클 수 없습니다.", function() {});
        return;
      }

      if ($("[name='rc_work_type[]']").is(":checked") == false) {
        commonAlert("근무형태를 하나이상 선택해주세요.", function() {});
        return;
      }

      $("[name='rc_am_stime']").val($("[name='rc_am_stime_h']").val() + ":" + $("[name='rc_am_stime_m']").val());
      $("[name='rc_am_etime']").val($("[name='rc_am_etime_h']").val() + ":" + $("[name='rc_am_etime_m']").val());

      $("[name='rc_pm_stime']").val($("[name='rc_pm_stime_h']").val() + ":" + $("[name='rc_pm_stime_m']").val());
      $("[name='rc_pm_etime']").val($("[name='rc_pm_etime_h']").val() + ":" + $("[name='rc_pm_etime_m']").val());

      if ($("[name='rc_price_rep_check']").is(":checked") == false) {
        commonAlert("급여유형을 하나이상 선택해주세요.", function() {});
        return;
      }

      if ($('#mm_comp_num').val() == '') {
        $('#dimwrap,#dimcont').show();
        return;
      }

      var hashTexts = []; // on 클래스를 가진 span의 내용을 저장할 배열 초기화
      var spans = document.querySelectorAll('#initHash span.on'); // on 클래스를 가진 모든 span 선택
      spans.forEach(function(span) {
        hashTexts.push(span.textContent); // 각 span의 텍스트 내용을 배열에 추가
      });
      var hashString = hashTexts.join(', ');


      var inputValues = []; // input 태그의 값들을 저장할 배열 초기화
      var inputs = document.querySelectorAll('#hashbox input[type="text"]'); // hashbox 내의 모든 input[type="text"] 선택
      inputs.forEach(function(input) {
        inputValues.push(input.value); // 각 input의 value를 배열에 추가
      });
      var concatenatedValues = inputValues.join(', ');
      $("[name='rc_hash']").html(hashString + ', ' + concatenatedValues);

      oEditors.getById["rc_desc"].exec("UPDATE_CONTENTS_FIELD", []);

      var form = $('#frm_recruit');
      var params = new FormData(form[0]);
      fileAjaxLoading("/compage_proc/recruit_write", params, function(ret) {
        if (ret.code == "00") {
          showPreviewInfo(ret.data);
        } else {
          commonAlert(ret.msg, function() {});
          return;
        }
      });
    });

    $("[name=rc_week_yn]").click(function() {
      if ($(this).is(":checked")) {
        $("[name='rc_week[]']").prop("checked", true);
      } else {
        $("[name='rc_week[]']").prop("checked", false);
      }
    });

    // 근무요일 기본값 설정 (월~금)
    (function() {
      var $weekCheckboxes = $("[name='rc_week[]']");
      var hasExistingSelection = $weekCheckboxes.is(":checked");
      var isAllDaysSelected = $("[name='rc_week_yn']").is(":checked");

      if (!hasExistingSelection && !isAllDaysSelected) {
        var weekdayDefaults = ["월", "화", "수", "목", "금"];
        $weekCheckboxes.each(function() {
          var $checkbox = $(this);
          if (weekdayDefaults.indexOf($checkbox.val()) !== -1) {
            $checkbox.prop("checked", true);
          }
        });
      }
    }());

    $("[name='rc_week[]']").click(function() {
      if ($("[name='rc_week[]']:checked").length == 7) {
        $("[name=rc_week_yn]").prop("checked", true);
      } else {
        $("[name=rc_week_yn]").prop("checked", false);
      }
    });

    $(".comma").on("keyup", function() {
      $(this).val($(this).val().replace(/\,/g, '').replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'));
    });

    var maxLimit = 2;

    $('input[name="rc_work[]"]').change(function() {
      var checkedCount = $('input[name="rc_work[]"]:checked').length;

      if (checkedCount > maxLimit) {
        commonAlert("업무구분은 " + maxLimit + "개까지 선택 할 수 있습니다.", function() {});
        $(this).prop('checked', false);
      }
    });

    // recruit_write.php 페이지에서만 btnSave 동작 오버라이드
    if (window.location.pathname.indexOf('/compage/recruit_write') !== -1) {
      $(document).off('click', '#btnSave').on('click', '#btnSave', function() {
        // 사업자명 입력 확인
        if ($("[name=comp_name]").val() == "" || $("[name=comp_name]").val().trim() == "") {
          commonAlert("사업자명을 입력해주세요.", function() {
            $("[name=comp_name]").focus();
          });
          return;
        }

        // 사업자 인증 처리
        fBizAuth(function() {
          var form = $('#frm1');
          var params = new FormData(form[0]);
          fileAjax("/compage_proc/com_biz_update", params, function(ret) {
            if (ret.code == "00") {
              // 팝업 닫기 후 공고 등록 진행 (페이지 새로고침하지 않음)
              $('#dimwrap').hide();
              $('#dimcont').hide();
              $("#btnStep").click();
            } else {
              commonAlert(ret.msg, function() {});
            }
          });
        });
      });
    }

    <?php if ($_GET["gbn"] == "re") { ?>
      const today = new Date();
      // 2개월 후의 날짜 구하기
      const twoMonthsLater = new Date(today);
      twoMonthsLater.setMonth(today.getMonth() + 2);
      $('.js-datepicker2').val(formatDate(today) + ' ~ ' + formatDate(twoMonthsLater));
      //$("[name='rc_date']").datepicker('setDate', new Date());
      //$('.js-datepicker2').datepicker({
      //  dateFormat: 'yyyy-mm-dd', // Sets the date format to year-month-day
      //  minDate: today,        // Sets the minimum date to today
      //  maxDate: twoMonthsLater // Sets the maximum date to two weeks from today
      //});
      setTimeout(function() {
        const today = new Date();
        const twoMonthsLater = new Date(today);
        twoMonthsLater.setMonth(today.getMonth() + 2);
        $('.js-datepicker2').val(formatDate(today) + ' ~ ' + formatDate(twoMonthsLater));
      }, 500);
    <?php } ?>

    var rcHashArray = $("[name='rc_hash']").html().split(',');
    var initYn = false;
    rcHashArray.forEach(function(hashValue) {
      initYn = false;
      if (hashValue.trim() === '') {
        return; // 다음 반복으로 넘어갑니다.
      }
      $('#initHash span').each(function() {
        if ($(this).html().trim() === hashValue.trim()) {
          $(this).addClass('on');
          initYn = true;
        }
      });

      if (initYn === false) {
        var newInput = $('<input/>', {
          type: 'text',
          style: 'min-width: 90px; max-width: 150px; height: 24px; border: none; margin-left: 5px;',
          value: hashValue.trim()
        });

        var newSpan = $('<span/>', {
          style: 'margin-right:10px; border: 1px solid #ccc; height: 28px; display: inline-block; line-height: 26px; border-radius: 25px; padding: 0 10px;'
        }).append('#').append(newInput).append('<span style="margin-left: 5px; cursor: pointer;" onclick="fHashDel(this);">ⓧ</span>');

        $('#hashbox').prepend(newSpan);
      }

    });

    setTimeout(function() {
      const today = new Date();
      const twoMonthsLater = new Date(today);
      twoMonthsLater.setMonth(today.getMonth() + 2);
      $('.js-datepicker2').val(formatDate(today) + ' ~ ' + formatDate(twoMonthsLater));
      $('.js-datepicker').val(formatDate(today));
    }, 500);

    fDelbtnAdd(0);
    fDelbtnAdd(1);
    fDelbtnAdd(2);
    fDelbtnAdd(3);
    fDelbtnAdd(4);
    fDelbtnAdd(5);

    // 특정 필드에 대한 선택자를 사용하여 모든 필드에 대해 이벤트를 바인딩
    $('[name^="rc_am_stime_m"], [name^="rc_am_etime_m"], [name^="rc_pm_stime_m"], [name^="rc_pm_etime_m"]').on('input', function() {
      // 숫자만 입력되도록 처리
      $(this).val($(this).val().replace(/[^0-9]/g, ''));

      // 입력된 값이 0에서 59 범위를 벗어난 경우 조정
      let value = parseInt($(this).val(), 10);
      if (!isNaN(value)) {
        if (value < 0) {
          $(this).val(0);
        } else if (value > 59) {
          $(this).val(59);
        }
      }
    });

    //앞에 시간 선택시 8시간 뒤로 뒷시간 설정
    $('#rc_am_stime_h').change(function() {
      var startTime = parseInt($(this).val(), 10);
      var endTime = (startTime + 8) % 24; // 24시간 이후는 다음날로 넘어가므로 나머지 연산자 사용
      $('#rc_am_etime_h').val(endTime.toString().padStart(2, '0')); // 값 설정, 1자리 숫자 앞에 0 추가
    });
    $('#rc_pm_stime_h').change(function() {
      var startTime = parseInt($(this).val(), 10);
      var endTime = (startTime + 8) % 24; // 24시간 이후는 다음날로 넘어가므로 나머지 연산자 사용
      $('#rc_pm_etime_h').val(endTime.toString().padStart(2, '0')); // 값 설정, 1자리 숫자 앞에 0 추가
    });


    // SmartEditor2 에디터 생성 및 폰트 크기 기본값을 14pt로 설정
    var oEditors = [];
    nhn.husky.EZCreator.createInIFrame({
      oAppRef: oEditors,
      elPlaceHolder: "rc_desc",
      sSkinURI: "/resource/shop/web/smarteditor2/SmartEditor2Skin_ko_KR.html",
      fCreator: "createSEditor2",
      fOnAppLoad: function() {
        setTimeout(function() {
          try {
            var oEditor = oEditors.getById["rc_desc"];
            if (!oEditor) return;
            
            // 에디터 body의 기본 폰트 크기를 14pt로 설정
            var oWYSIWYGDocument = oEditor.getWYSIWYGDocument();
            if (oWYSIWYGDocument && oWYSIWYGDocument.body) {
              oWYSIWYGDocument.body.style.fontSize = "14pt";
            }
            
            // 폰트 크기 플러그인 찾아서 기본값 설정
            var oApp = oEditor.oApp;
            if (oApp && oApp.aPlugins) {
              for (var i = 0; i < oApp.aPlugins.length; i++) {
                var oPlugin = oApp.aPlugins[i];
                if (oPlugin && oPlugin.sDefaultText !== undefined && oPlugin.elFontSizeLabel) {
                  oPlugin.sDefaultText = "14pt";
                  oPlugin.elFontSizeLabel.innerHTML = "14pt";
                  
                  // 14pt 항목 활성화
                  if (oPlugin.aLIFontSizes && oPlugin.aLIFontSizes.length > 0) {
                    for (var j = 0; j < oPlugin.aLIFontSizes.length; j++) {
                      var elLi = oPlugin.aLIFontSizes[j];
                      if (!elLi) continue;
                      
                      var elSpan = elLi.querySelector("span span");
                      if (elSpan && elSpan.style && elSpan.style.fontSize === "14pt") {
                        if (typeof oPlugin._clearFontSizeSelection === "function") {
                          oPlugin._clearFontSizeSelection();
                        }
                        elLi.className = (elLi.className + " active").trim();
                        break;
                      }
                    }
                  }
                  break;
                }
              }
            }
          } catch(e) {
            console.error("폰트 크기 기본값 설정 중 오류:", e);
          }
        }, 200);
      }
    });

    setTimeout(function() {
      $('iframe').css('background-color', '#fff');
      $('iframe').css('border-right', '1px solid #ccc');
    }, 500);

    // 네 개의 입력 필드를 선택하여 순회
    $('input[name="rc_am_stime_m"], input[name="rc_am_etime_m"], input[name="rc_pm_stime_m"], input[name="rc_pm_etime_m"]').each(function() {
      // 값이 빈 문자열이면 "00"을 설정
      if ($(this).val() === "") {
        $(this).val("00");
      }
    });
  }); //ready

  function fDelbtnAdd(arg) {
    var imgHtml = '<a href="javascript:if(confirm(\'이미지를 삭제하시겠습니까?\')){ $(\'#rc_img' + arg + '\').val(\'\'); $(\'#rc_img' + arg + '_ori\').val(\'\'); $(\'#prev' + arg + ' img\').hide(); } " id="delimgbtn' + arg + '" style="display:none; position: absolute; top: 5px; right: 5px; overflow: hidden; border-radius: 11px; width: 23px; height: 24px; z-index: 10;">';
    imgHtml += '<span style="font-size: 27px; margin-top: -5px; margin-left: -2px; display: block; background-color: #ccc;">ⓧ</span>';
    imgHtml += '</a>';
    setTimeout(function() {
      if ($('#prev' + arg + ' img').length > 0) {
        $('#prev' + arg).append(imgHtml);
      }
    }, 500);
  }

  function showPreviewInfo(info) {

    var str_rc_work_img = "";
    var str_rc_week = "";
    var str_img = "";
    var str_price = "";
    var str_time = "";
    var str_hash = "";
    var str_life_type = "";

    if (info.rc_week) {
      for (var icnt = 0; icnt < info.rc_week.length; icnt++) {
        str_rc_week += "<li aria-hidden='false'>" + info.rc_week[icnt] + "</li>";
      }
    }

    if (info.rc_img1) {
      str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='" + info.rc_img1 + "' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    }

    if (info.rc_img2) {
      str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='" + info.rc_img2 + "' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    }

    if (info.rc_img3) {
      str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='" + info.rc_img3 + "' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    }

    if (info.rc_img4) {
      str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='" + info.rc_img4 + "' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    }

    if (info.rc_img5) {
      str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='" + info.rc_img5 + "' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    }

    str_price += '<em class="currency">' + $('[name="rc_price_pay"]').val() + '</em>';

    if (info.rc_price_type == "협의") {
      str_price = "협의";
    }

    if (info.rc_work_type.indexOf('2교대') > -1 || (info.rc_work_type.indexOf('주간') > -1 && info.rc_work_type.indexOf('야간') > -1)) {
      if (info.rc_am_stime != '' || info.rc_am_etime != '') {
        str_time += info.rc_am_stime + '~' + info.rc_am_etime + '(주간)';
      } else {
        str_time += '주간';
      }
      if (info.rc_pm_stime != '' || info.rc_pm_etime != '') {
        str_time += '<br/>' + info.rc_pm_stime + '~' + info.rc_pm_etime + '(야간)';
      } else {
        str_time += ',' + ' 야간';
      }
    } else if (info.rc_work_type.indexOf('주간') > -1 && info.rc_work_type.indexOf('2교대') == -1) {
      if (info.rc_am_stime != '' || info.rc_am_etime != '') {
        str_time += info.rc_am_stime + '~' + info.rc_am_etime + '(주간)';
      } else {
        str_time += '주간';
      }
    } else if (info.rc_work_type.indexOf('야간') > -1 && info.rc_work_type.indexOf('2교대') == -1) {
      if (info.rc_pm_stime != '' || info.rc_pm_etime != '') {
        str_time += info.rc_pm_stime + '~' + info.rc_pm_etime + '(야간)';
      } else {
        str_time += '야간';
      }
    }

    for (var icnt = 0; icnt < info.rc_hash.split(",").length; icnt++) {
      if (info.rc_hash.split(",")[icnt].trim() != "") {
        str_hash += '<p class="tag">' + info.rc_hash.split(",")[icnt].trim() + '</p>';
      }
    }

    if (info.rc_life_type) {
      for (var icnt = 0; icnt < info.rc_life_type.length; icnt++) {
        str_life_type += '<li class="item" data-tmp=""><p class="benefit-label benefit1-1-v2">' + info.rc_life_type[icnt] + '</p></li>';
      }
    }
    if (!info.rc_work_dt) {
      info.rc_work_dt = '근무일무관';
      info.rc_work_dt_yoil = '-';
    }
    if (info.rc_sex == '') {
      info.rc_sex = '무관';
    }
    if (info.rc_nation == '') {
      info.rc_nation = '무관';
    }
    var ageStr = '';
    if (info.rc_sage == '' && info.rc_eage == '') {
      ageStr = '무관';
    } else {
      ageStr = info.rc_sage + '세이상~' + info.rc_eage + '세이하';
    }
    if (str_rc_week == '') {
      str_rc_week = '무관';
    }

    var preview_life = info.rc_life_type_tag;
    preview_life = preview_life.replaceAll("benefit1-1", "benefit1-1-v2");
    preview_life = preview_life.replaceAll("benefit1-2", "benefit1-2-v2");
    preview_life = preview_life.replaceAll("benefit1-3", "benefit1-3-v2");
    preview_life = preview_life.replaceAll("benefit1-4", "benefit1-4-v2");
    preview_life = preview_life.replaceAll("benefit2-1", "benefit2-1-v2");
    preview_life = preview_life.replaceAll("benefit2-2", "benefit2-2-v2");
    preview_life = preview_life.replaceAll("benefit2-3", "benefit2-3-v2");
    preview_life = preview_life.replaceAll("benefit3-1", "benefit3-1-v2");
    preview_life = preview_life.replaceAll("benefit3-2", "benefit3-2-v2");
    preview_life = preview_life.replaceAll("benefit3-3", "benefit3-3-v2");
    preview_life = preview_life.replaceAll("benefit3-4", "benefit3-4-v2");
    preview_life = preview_life.replaceAll("benefit3-5", "benefit3-5-v2");
    preview_life = preview_life.replaceAll("benefit3-6", "benefit3-6-v2");
    preview_life = preview_life.replaceAll("benefit4-1", "benefit4-1-v2");
    preview_life = preview_life.replaceAll("benefit4-2", "benefit4-2-v2");
    preview_life = preview_life.replaceAll("benefit4-3", "benefit4-3-v2");
    preview_life = preview_life.replaceAll("benefit4-4", "benefit4-4-v2");
    preview_life = preview_life.replaceAll("benefit4-5", "benefit4-5-v2");
    preview_life = preview_life.replaceAll("benefit4-6", "benefit4-6-v2");
    preview_life = preview_life.replaceAll("benefit4-7", "benefit4-7-v2");

    var preview_desc = info.rc_desc;
    preview_desc = preview_desc.replaceAll(/\n/g, "<br>");

    var rc_price_rep_txt = '';
    if ($('[name="rc_price_rep"]') != '협의') {
      rc_price_rep_txt = ' (대표급여형태 : ' + $('[name="rc_price_rep"]').val() + ')';
    }

    openPopup('modal', {
      content: '<div class="regist-info">' +
        '<h5 class="name">' + info.rc_name + '</h5>' +
        '<table>' +
        '<caption><span class="sr-text">채용공고정보</span></caption>' +
        '<colgroup><col><col></colgroup>' +
        '<tbody>' +
        '<tr>' +
        '<td class="label">공개여부</td>' +
        '<td class="value">' + (info.rc_show_yn == 'Y' ? '공개' : '비공개') + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">업무구분</td>' +
        '<td class="value">' +
        info.rc_work_img +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">금액</td>' +
        '<td class="value txt-color2">' +
        str_price +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">급여형태</td>' +
        '<td class="value">' + info.rc_price_type + rc_price_rep_txt + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">근무시간</td>' +
        '<td class="value">' + str_time + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">근무시작일</td>' +
        '<td class="value">' + info.rc_work_dt + '(' + info.rc_work_dt_yoil + ')' + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">희망성별</td>' +
        '<td class="value">' + info.rc_sex + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">희망국적</td>' +
        '<td class="value">' + info.rc_nation + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">희망나이</td>' +
        '<td class="value">' + ageStr + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="label">근무요일</td>' +
        '<td class="value">' +
        '<ul class="working-day">' +
        str_rc_week +
        '</ul>' +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="value">' +
        '<div class="hashtag">' +
        str_hash +
        '</div>' +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="label">복리후생</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="value">' +
        '<ul class="benefit-group">' +
        preview_life +
        '</ul>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="label" style="padding-top: 34px;">상세업무정보</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="value" style="max-width: 400px;">' +
        preview_desc +
        '</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="label" style="padding-top: 34px;">첨부사진</td>' +
        '</tr>' +
        '<tr>' +
        '<td colspan="2" class="value">' +
        str_img +
        '</td>' +
        '</tr>' +
        '</tbody>' +
        '</table>' +
        '</div>',
      firstButton: '정보수정',
      secondButton: ($("[name=rc_idx]").val() != "0" ? '수정완료' : '등록완료'),
      firstCallback: function() {},
      secondCallback: function() {
        var params = {};
        params["rc_idx"] = $("[name=rc_idx]").val();
        params["mm_comp_num"] = $("[name=mm_comp_num]").val();
        params["mm_comp_num_org"] = $("[name=mm_comp_num_org]").val();

        comAjax("/compage_proc/recruit_complete", params, function(ret) {
          if (ret.code == "00") {
            location.href = "/compage/recruit_list";
          } else {
            commonAlert(ret.msg, function() {});
            return;
          }
        });

      }
    });
  }

</script>

<?php include_once(get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>
