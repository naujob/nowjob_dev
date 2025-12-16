<div class="page page-write">
  <div class="top-bar">
    <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
    <h1 class="title">채용공고등록</h1>
    <ol class="progress-steps">
      <li>1</li>
      <li>2</li>
      <li title="헌재위치" class="active">3</li>
      <li>4</li>
      <li>5</li>
    </ol>
  </div><!-- //top-bar -->

  <div class="page-content">
		<form id="frm" onsubmit="return false">
			<input type="hidden" id="rc_idx" name="rc_idx" value="<?=$info["rc_idx"]?>" />
			<div class="section addition-info-form">
        <div class="container">

          <div class="form-row">
            <style>
              .row { display: flex; gap: 8px; flex-direction: column;}
              .extraField { display: flex; gap: 8px; flex-direction: column; }
              .checks { display: grid; grid-template-columns: repeat(auto-fit, minmax(60px, 1fr)); gap: 10px; }
              .chip2 input[type="radio"] { display: none; }
              .checks2 { display: flex; gap: 12px;}
              .chip2 { position: relative; padding-left: 28px; cursor: pointer; }
              .chip2.on{color: #57CCAF;} 
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
              .amountField{display: flex; gap: 8px; height:34px;width: 50%;}
              .amountField .amount { display: flex; gap: 4px; width: 100%; align-items: center; border-radius: 17px; border: 1px solid #57CCAF; }
              .amountField.disabled .amount{border: 1px solid #e5e7eb; background: #e5e7eb;}
              .amountField .amount input[type="text"] { width: 100%; margin-left: 16px; border: none; outline: none;}
              .amountField .amount input[type="text"]:focus {  }  
              .unit { white-space: nowrap; padding-right: 12px; }
              .hide { display: none !important; }
              .pay-select-wrap {
                display: flex;
                gap:5px;
                position: relative;
                width: 100%;
              }
              .pay-select {
                position: relative;
                width: 50%;
                font-size: 14px;
              }
              .pay-select-trigger {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #57CCAF;
                border: 1px solid #57CCAF;
                border-radius: 17px;
                height: 34px;
                padding: 0 30px;
                cursor: pointer;
                color: #fff;
                z-index: 5;
              }
              .pay-select-trigger:after {
                content: "";
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                width: 8px;
                height: 6px;
                background-repeat: no-repeat;
                background-position: center;
                background-size: contain;
                background-image: url("data:image/svg+xml;utf8,%3Csvg%20width%3D%228%22%20height%3D%226%22%20viewBox%3D%220%200%208%206%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20opacity%3D%221%22%20d%3D%22M3.19%204.876a1%201%200%200%200%201.62%200l2.376-3.29A1%201%200%200%200%206.376%200H1.624a1%201%200%200%200-.811%201.585l2.375%203.291z%22%20fill%3D%22%23fff%22/%3E%3C/svg%3E");
                transition: transform ease 0.2s;
              }
              .pay-select-trigger.active:after {
                transform: translateY(-50%) rotate(180deg);
              }
              .pay-select-options {
                display: none;
                flex-direction: column; /* 세로 배치 */
                gap: 0;
                position: absolute;
                top: 17px;
                padding-top:17px;
                left: 0;
                width: 100%;
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 0 0 17px 17px;
                overflow: hidden;
                z-index: 2;
              }
              .pay-select-option {
                height: 34px;
                padding: 7px 12px;
                cursor: pointer;
                text-align: center;
              }
              .pay-select-option:hover {
                background: #57CCAF;
                color: #fff;
              }
              .amountNotice {
                display: inline-block;
                padding-left: 20px;
                margin: 0;
                background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzMxQTE4MiIgdmlld0JveD0iMCAwIDE2IDE2Ij4KICA8cGF0aCBkPSJNOCAxNkE4IDggMCAxIDAgOCAwYTggOCAwIDAgMCAwIDE2bS45My05LjQxMi0xIDQuNzA1Yy0uMDcuMzQuMDI5LjUzMy4zMDQuNTMzLjE5NCAwIC40ODctLjA3LjY4Ni0uMjQ2bC0uMDg4LjQxNmMtLjI4Ny4zNDYtLjkyLjU5OC0xLjQ2NS41OTgtLjcwMyAwLTEuMDAyLS40MjItLjgwOC0xLjMxOWwuNzM4LTMuNDY4Yy4wNjQtLjI5My4wMDYtLjM5OS0uMjg3LS40NzFsLS40NTEtLjA4MS4wODItLjM4MSAyLjI5LS4yODd6TTggNS41YTEgMSAwIDEgMSAwLTIgMSAxIDAgMCAxIDAgMiIvPgo8L3N2Zz4=) no-repeat left center;
                background-size: 12px 12px;
                font-size: 0.8125rem;
                font-style: normal;
                font-weight: 400;
              }    
              .amountNotice span{ color:red;}
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
            ?>
            <p class="form-label">
              급여형태 
              <span class="option amountNotice">
                  최저임금제도 : 시급 <span>10,030</span>원
              </span>
            </p>                      
              
            <input type="hidden" class="hidden_price_value" name="rc_price_type" value="<?=$info["rc_price_type"]?>" >
            <input type="hidden" class="hidden_price_value" name="rc_price_rep" value="<?=$info["rc_price_rep"]?>" >
            <input type="hidden" class="hidden_price_value" name="rc_price_pay" value="<?=$info["rc_price_pay"]?>" >
            <input type="hidden" class="hidden_price_value" name="rc_day_price" value="<?=$info["rc_day_price"]?>">
            <input type="hidden" class="hidden_price_value" name="rc_week_price" value="<?=$info["rc_week_price"]?>">
            <input type="hidden" class="hidden_price_value" name="rc_month_price" value="<?=$info["rc_month_price"]?>">
            <div class="row">
              <div class="pay-select-wrap">
                <div class="pay-select" id="priceSelect">
                  <div class="pay-select-trigger"><?= $info['rc_price_rep'] ?></div>
                  <div class="pay-select-options">
                    <?php foreach($rc_price_type_list as $row): ?>
                      <div class="pay-select-option" data-value="<?= $row ?>">
                        <?= $row ?>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  <input type="hidden" name="rc_price_rep_check" id="rc_price_rep_check" value="<?= $info['rc_price_rep'] ?>">
                </div>
                <div class="amountField" id="amountField">
                  <div class="amount">
                    <input type="text" 
                           class="form-text" 
                           id="rc_price_amount" 
                           value="<?= number_format($info['rc_price_pay']) ?>" 
                           placeholder="금액 입력" />
                    <span class="unit" id="amountUnit">
                      <?= isset($rc_price_type_unit[$info['rc_price_rep']]) ? $rc_price_type_unit[$info['rc_price_rep']] : '' ?>
                    </span>
                  </div>
                </div>
              </div>
              <div class="extraField <?= ($info['rc_price_rep'] === '협의' ? 'hide' : '') ?>" id="extraField">
                <div class="checks2" id="extraChecks">
                  <?php foreach($rc_price_type_list as $row) : ?>
                    <?php if ($row !== $info['rc_price_rep'] && $row !== "협의") : ?>
                      <label class="chip2 <?= (strpos($info['rc_price_type'], $row) !== false ? 'on' : '') ?>">
                        <input type="radio" 
                               name="rc_price_extra" 
                               value="<?=$row?>" 
                               <?= (strpos($info['rc_price_type'], $row) !== false ? 'checked' : '') ?> /> 
                        <span><?=$row?>가능</span>
                      </label>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            
            <script>
              $(function(){
                const PAY_TYPES = ["시급", "일급", "주급", "월급", "협의"];
                const UNIT_BY_TYPE = { "시급": "원/시", "일급": "원/일", "주급": "원/주", "월급": "원/월", "협의": "-" };
                const $amountUnit = $("#amountUnit"), $amountField = $("#amountField"), $amountInput = $("#rc_price_amount"), $extraField = $("#extraField"), $extraChecks = $("#extraChecks");
           
                function buildExtraChecks(rep){
                  $extraChecks.empty();
                  const extras = PAY_TYPES.filter(t => t !== rep && t !== "협의");
                  extras.forEach((t,i)=>{
                    $extraChecks.append(`<label class="chip2"><input type="radio" name="rc_price_extra" value="${t}" /> <span>${t}가능</span></label>`);
                  });
                }

                function setVisibility(rep){
                  const isNeg = rep === "협의";
                  $extraField.toggleClass("hide", isNeg);
                  if(isNeg) $amountInput.val("");
                }

                $(document).on("change", "#rc_price_rep_check", function() {
                  const val = $(this).val();
                  $('.hidden_price_value').val('');
                  $amountInput.val("");
                  $amountUnit.text(UNIT_BY_TYPE[val] || "원");

                  if (!val) {
                    $extraChecks.empty();
                    $extraField.addClass("hide");
                    pricePayUpdate();
                    return;
                  }

                  setVisibility(val);

                  if (val !== "협의") {
                    buildExtraChecks(val); 
                    $amountInput.attr("placeholder", "금액 입력").prop("disabled", false);
                    $amountField.removeClass("disabled");
                  } else {
                    $extraChecks.empty();
                    $extraField.addClass("hide");
                    $amountInput.attr("placeholder", "").prop("disabled", true);
                    $amountField.addClass("disabled");
                  }
                  pricePayUpdate();
                });

                $(document).on("click", 'input[name="rc_price_extra"]', function(e){
                  const $this = $(this);
                  const name = $this.attr('name');
                  const val = $this.val();

                  $(`input[name="${name}"]`).closest('label').removeClass('on');

                  if ($this.data("waschecked") === true) {
                    $this.prop("checked", false).data("waschecked", false);

                    pricePayUpdate();
                    e.preventDefault();
                    return;
                  }
                  $(`input[name="${name}"]`).data("waschecked", false);
                  $this.data("waschecked", true).closest('label').addClass('on');

                  pricePayUpdate();
                });

                $amountInput.on("input", function(){
                  let val = $(this).val().replace(/,/g, "");
                  if(val && !isNaN(val)){
                    $(this).val(Number(val).toLocaleString());
                  }
                  pricePayUpdate();
                });
                
                function pricePayUpdate() {
                  const rep = $('#rc_price_rep_check').val();
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
                  rc_price_type_tmp.push(rep);

                  $('input[name="rc_price_extra"]:checked').each(function() {
                    rc_price_type_tmp.push($(this).val());
                  });

                  const rc_price_type = rc_price_type_tmp
                    .sort((a, b) => PAY_TYPES.indexOf(a) - PAY_TYPES.indexOf(b))
                    .join(",");
                  
                  $('[name="rc_price_type"]').val(rc_price_type);
                  $('[name="rc_price_rep"]').val(rep);
                  $('[name="rc_price_pay"]').val(amount);

                  // 전체 시급 적용전까지 임시처리 시작
                    if(rep=='일급'){
                      $('[name="rc_day_price"]').val(amount);
                      $('[name="rc_week_price"]').val('');
                      $('[name="rc_month_price"]').val('');
                    }
                    if(rep=='주급'){
                      $('[name="rc_day_price"]').val('');
                      $('[name="rc_week_price"]').val(amount);
                      $('[name="rc_month_price"]').val('');
                    }
                    if(rep=='월급'){
                      $('[name="rc_day_price"]').val('');
                      $('[name="rc_week_price"]').val('');
                      $('[name="rc_month_price"]').val(amount);
                    }
                    if(rep=='협의'||rep=='시급'){
                      $('[name="rc_day_price"]').val('');
                      $('[name="rc_week_price"]').val('');
                      $('[name="rc_month_price"]').val('');
                    }          
                  // 전체 시급 적용전까지 임시처리 끝
                }


                const $select = $('#priceSelect');
                const $trigger = $select.find('.pay-select-trigger');
                const $options = $select.find('.pay-select-options');
                const $hiddenInput = $('#rc_price_rep_check');

                $trigger.on('click', function(e) {
                  e.stopPropagation();
                  $options.toggle();
                  $trigger.toggleClass('active');
                });

                $options.on('click', '.pay-select-option', function(e) {
                  const val = $(this).data('value');
                  $trigger.text(val);
                  $hiddenInput.val(val).trigger('change');
                  $options.hide();
                  $trigger.removeClass('active');
                  $('.pay-select-option').removeClass('selected');
                  $(this).addClass('selected');
                });

                $(document).on('click', function(e) {
                  if (!$(e.target).closest('#priceSelect').length) {
                    $options.hide();
                    $trigger.removeClass('active');
                  }
                });
                
              
              });
            </script>                  
          </div> 
          
          <div class="form-row">
              <p class="form-label">근무형태 <em>다중선택가능</em>
                <span class="add_text <?=count($info["rc_work_type"]) > 2 ? "":"hidden"?>" style="margin-left:0px;font-weight:normal;">(협의가능)</span>
              </p>
              <div class="form-toggle-group col-3">
                <?php foreach($rc_work_type_list as $key=> $row) : ?>
                  <label class="form-toggle-btn2">
                      <input type="checkbox" id="rc_work_type_<?=$key?>" name="rc_work_type[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["rc_work_type"],"check")?>>
                      <span class="btn"><?=$row["cd_val"]?></span>
                  </label>
                  <?php endforeach;?>
              </div>
              <div class="form-row" id="time_cont" <?=count($info["rc_work_type"]) > 0 ? "":"hidden"?>>
                  <table class="time-table">
                      <colgroup><col> <col> <col></colgroup>
                      <thead>
                          <tr>
                              <td></td>
                              <td>시작</td>
                              <td>종료</td>
                          </tr>
                      </thead>
                      <tbody>
                          <tr id="time_cont1" <?=(array_search("주간",$info["rc_work_type"])!==false || array_search("2교대",$info["rc_work_type"])!==false) ? "":"hidden"?>>
                              <td class="label"><span>주간</span></td>
                              <td>
                                  <!-- 20221022 수정 -->
                                  <label class="wrapper">
                                      <input type="text" name="rc_am_stime" id="rc_am_stime" value="<?=$info["rc_am_stime"]?>" placeholder="09:00" readonly required class="js-time">
                                  </label>
                              </td>
                              <td>
                                  <!-- 20221022 수정 -->
                                  <label class="wrapper">
                                      <input type="text" name="rc_am_etime" id="rc_am_etime" value="<?=$info["rc_am_etime"]?>" placeholder="18:00" readonly required class="js-time">
                                  </label>
                              </td>
                          </tr>
                          <tr id="time_cont2" <?=(array_search("야간",$info["rc_work_type"])!==false || array_search("2교대",$info["rc_work_type"])!==false) ? "":"hidden"?>>
                              <td class="label"><span>야간</span></td>
                              <td>
                                  <label class="wrapper">
                                      <!-- 20221022 수정 -->
                                      <input type="text" name="rc_pm_stime" id="rc_pm_stime" value="<?=$info["rc_pm_stime"]?>" placeholder="18:00" readonly required class="js-time">
                                  </label>
                              </td>
                              <td>
                                  <label class="wrapper">
                                      <!-- 20221022 수정 -->
                                      <input type="text" name="rc_pm_etime" id="rc_pm_etime" value="<?=$info["rc_pm_etime"]?>" placeholder="8:00" readonly required class="js-time">
                                  </label>
                              </td>
                          </tr>
                      </tbody>
                  </table>
                  <script>
                      //20221022 수정
                      /* 시간설정 팝업표시 */
                      window.addEventListener('DOMContentLoaded', function () {
                          if (typeof Rolldate == 'undefined') return;
                          new Rolldate({
                              el: '#rc_am_stime',
                              format: 'hh:mm',
                              value : '<?=$info["rc_am_stime"]=="" ? "09:00":$info["rc_am_stime"]?>',
                              hour12: true,
                              lang: { title: '주간시작시간', description:'주간시작시간을 설정해주세요', cancel: '취소', confirm: '확인', hour: '', min: '', sec: '', am: '오전', pm: '오후', }
                          });
                          new Rolldate({
                              el: '#rc_am_etime',
                              format: 'hh:mm',
                              value : '<?=$info["rc_am_etime"]=="" ? "18:00":$info["rc_am_etime"]?>',
                              hour12: true,
                              lang: { title: '주간종료시간', description:'주간종료시간을 설정해주세요', cancel: '취소', confirm: '확인', hour: '', min: '', sec: '', am: '오전', pm: '오후', }
                          });
                          new Rolldate({
                              el: '#rc_pm_stime',
                              format: 'hh:mm',
                              value : '<?=$info["rc_pm_stime"]=="" ? "22:00":$info["rc_pm_stime"]?>',
                              hour12: true,
                              lang: { title: '야간시작시간', description:'야간시작시간을 설정해주세요', cancel: '취소', confirm: '확인', hour: '', min: '', sec: '', am: '오전', pm: '오후', }
                          });
                          new Rolldate({
                              el: '#rc_pm_etime',
                              format: 'hh:mm',
                              value : '<?=$info["rc_pm_etime"]=="" ? "08:00":$info["rc_pm_etime"]?>',
                              hour12: true,
                              lang: { title: '야간종료시간', description:'야간종료시간을 설정해주세요', cancel: '취소', confirm: '확인', hour: '', min: '', sec: '', am: '오전', pm: '오후', }
                          });
                      });
                  </script>
              </div>                        
          </div>
          
          <div class="form-row">
              <p class="form-label">
              근무요일 <em>다중선택가능</em>
                  <span class="option">
                      <label class="form-checkbox">
                          <input type="checkbox" name="rc_week_yn" value="Y" class="sr-text" <?=$info["rc_week_yn"]=="Y" ? "checked":""?>>
                          <span class="label">요일무관</span>
                      </label>
                  </span>
              </p>
              <div class="form-toggle-group align-justify">
                <?php foreach($rc_week_list as $row) : ?>
                  <label class="form-toggle-btn2">
                      <input type="checkbox" name="rc_week[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["rc_week"],"check")?>>
                      <span class="btn"><?=$row["cd_val"]?></span>
                  </label>
                  <?php endforeach;?>
              </div>
          </div>

          <div class="form-row">
              <label>
                  <span class="form-label">근무시작일</span>
                  <input type="text" name="rc_work_dt" aria-required="true" required class="form-text js-datepicker">
              </label>

              <script>
                  (function () {
                      function chgMonth (instance) {
                          var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                              month = Number(instance.currentMonth) + 1,
                              year = instance.currentYear;
                          $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
                      }

                      window.addEventListener('DOMContentLoaded', function () {
                          if (typeof flatpickr == 'undefined') return false;

                          //달력 생성
                          flatpickr('.js-datepicker', {
                              locale: 'ko',
                              dateFormat: 'Y-m-d',
                              ariaDateFormat: 'Y-m-d',
                              altFormat: 'Y.m.d',
                              defaultDate : '<?=$info["rc_work_dt"]?>',
                              altInput: true,
                              nextArrow: '<span class="sr-text">다음</span>',
                              prevArrow: '<span class="sr-text">이전</span>',
                              disableMobile: true,
                              position: 'bottom auto',
                              onMonthChange: function (selectedDates, dateStr, instance) {
                                  chgMonth(this);
                              },
                              onDayCreate: function(dObj, dStr, fp, dayElem){
                                  var day = new Date(dayElem.getAttribute('aria-label')).getDay();
                                  if (day == 0) {
                                      dayElem.classList.add('sun');
                                  } else if (day == 6) {
                                      dayElem.classList.add('sat');
                                  }
                              },
                              onReady: function () {
                                  chgMonth(this);
                              }
                          });
                      });
                  }());
              </script>
          </div>
          
          <div class="sticky-bottom">
            <button id="btnPrev" type="button" class="btn btn-secondary">이전</button>
            <button id="btnStep" type="button" class="btn btn-primary">다음</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
  window.addEventListener('DOMContentLoaded', function() {  
	//$(document).ready(function(){
		$("[name='rc_work_type[]']").change(function(){
			$("#time_cont").attr("hidden","");
			$("#time_cont1").attr("hidden","");
			$("#time_cont2").attr("hidden","");

			if($("#rc_work_type_0").is(":checked")==true){
				$("#time_cont").removeAttr("hidden");
				$("#time_cont1").removeAttr("hidden");
			}

			if($("#rc_work_type_1").is(":checked")==true){
				$("#time_cont").removeAttr("hidden");
				$("#time_cont2").removeAttr("hidden");
			}

			if($("#rc_work_type_2").is(":checked")==true){
				$("#time_cont").removeAttr("hidden");
				$("#time_cont1").removeAttr("hidden");
				$("#time_cont2").removeAttr("hidden");
			}

			if($("[name='rc_work_type[]']:checked").length > 2){
				$(this).closest(".form-row").find(".add_text").removeClass("hidden");
			}else{
				$(this).closest(".form-row").find(".add_text").addClass("hidden");
			}
      pricePayUpdate();
		});

		//처리
		$("#btnStep").click(function(){
      
      if($("[name='rc_price_rep']").val()==""){
        commonAlert("대표급여형태을 선택해주세요.",function(){});
        return;
      }

      if($("[name='rc_price_rep']").val()!="협의" && $("[name='rc_price_pay']").val()==""){
        commonAlert("급여액을 입력해주세요.",function(){});
        return;
      }    
      
			if($("[name='rc_work_type[]']").is(":checked")==false){
        commonAlert("근무형태를 하나이상 선택해주세요.",function(){});
        return;
      }

      if($("[name='rc_week_yn']").is(":checked")==false && $("[name='rc_week[]']").is(":checked")==false){
        commonAlert("근무가능 요일을 하나이상 선택해주세요.",function(){});
        return;
      }

      if($("#rc_work_type_0").is(":checked")==true){
				if($("[name='rc_am_stime']").val()==""){
          commonAlert("주간 근무 시작시간을 선택해주세요.",function(){});
          return;
        }

        if($("[name='rc_am_etime']").val()==""){
          commonAlert("주간 근무 종료시간을 선택해주세요.",function(){});
          return;
        }
			}

			if($("#rc_work_type_2").is(":checked")==true){
				if($("[name='rc_pm_stime']").val()==""){
          commonAlert("야간 근무 시작시간을 선택해주세요.",function(){});
          return;
        }

        if($("[name='rc_pm_etime']").val()==""){
          commonAlert("야간 근무 종료시간을 선택해주세요.",function(){});
          return;
        }
			}

			if($("#rc_work_type_2").is(":checked")==true){
				if($("[name='rc_am_stime']").val()==""){
          commonAlert("주간 근무 시작시간을 선택해주세요.",function(){});
          return;
        }

        if($("[name='rc_am_etime']").val()==""){
          commonAlert("주간 근무 종료시간을 선택해주세요.",function(){});
          return;
        }

				if($("[name='rc_pm_stime']").val()==""){
          commonAlert("야간 근무 시작시간을 선택해주세요.",function(){});
          return;
        }

        if($("[name='rc_pm_etime']").val()==""){
          commonAlert("야간 근무 종료시간을 선택해주세요.",function(){});
          return;
        }
			}

			if($("[name='rc_work_dt']").val()==""){
        commonAlert("근무시작일을 선택해주세요.",function(){});
        return;
      }

      var params = $("#frm").serialize();
      comAjax("/compage_proc/recruit_write3_new",params,function(ret){
        if(ret.code=="00"){
          if($("[name=rc_idx]").val()!="0"){
            location.href="/compage/recruit_write4_new/" + $("[name=rc_idx]").val();
            //_replace("/compage/recruit_write4/" + $("[name=rc_idx]").val());
          }else{
            location.href="/compage/recruit_write4_new";
            //_replace("/compage/recruit_write4");
          }
        }else{
          commonAlert(ret.msg,function(){});
          return;
        }
      });
		});

		// 뒤로가기
		$("#btnPrev").click(function(){
			history.go(-1);
		});
	});


	$("[name=rc_week_yn]").click(function(){
		if($(this).is(":checked")){
			$("[name='rc_week[]']").prop("checked",true);
		}else{
			$("[name='rc_week[]']").prop("checked",false);
		}
	});

	$("[name='rc_week[]']").click(function(){
		if($("[name='rc_week[]']:checked").length == 7){
			$("[name=rc_week_yn]").prop("checked",true);
		}else{
			$("[name=rc_week_yn]").prop("checked",false);
		}
	});
</script>
