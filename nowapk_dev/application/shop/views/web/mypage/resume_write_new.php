<div id="content" class="page page-mypage">
   <main class="page-content">
       <div class="container">
           <?php $tab_on = "tab2";?>
            <?php $tab_on2 = "menu2";?>
           <?php include_once (get_base_url() . '/application/shop/views/web/mypage/top_line_2025.php'); ?>
           <style>
             .foldbtn { display:none; position: absolute; top: 0px; right: 0px; cursor: pointer; }
             .foldbtn img.upside { height: 18px; margin-top: 5px; -ms-transform: rotate(270deg); -webkit-transform: rotate(270deg); transform: rotate(270deg); }
             .foldbtn img.dwside { height: 18px; margin-top: 5px; -ms-transform: rotate(90deg); -webkit-transform: rotate(90deg); transform: rotate(90deg); }
           </style>
           <div class="tab-panel on">
               <div class="manage-my-content">
                   <form id="frmAll" onsubmit="return false">
                       <input type="hidden" name="ru_idx" value="<?=getData($info,"ru_idx","0")?>" />
                       <input type="hidden" name="ru_work_img" value="" />
                   	   <input type="hidden" name="arr_certi" value="" />
                       <input type="hidden" name="arr_career" value="" />

                       <h3 class="sr-text">이력서 작성</h3>
                       <div class="addition-info-form">
                           <div class="form-table">
                               <div class="form-row">
                                   <p class="form-label" style="font-size: 0.9em;"><span class="fixed">*</span>표시는 필수 입력 항목입니다.</p>
                                   <div class="form-group" style="position:absolute; right:0px; ">
                                     <label class="form-toggle-btn2" onclick="fFoldAll('Y');">
                                       <input type="radio" name="rc_simple_yn" value="Y" checked>
                                       <span class="btn">간편등록</span>
                                     </label>
                                     <label class="form-toggle-btn2" onclick="fFoldAll('N');">
                                       <input type="radio" name="rc_simple_yn"  value="N" >
                                       <span class="btn">상세등록</span>
                                     </label>
                                   </div>
                               </div>
                               <div class="form-row">
                                 <span class="foldbtn" id="btn1" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label"><span class="fixed">*</span>이력서명 <em>이력서 제목으로 등록됩니다.</em></p>
                                   <div class="value">
                                       <label>
                                           <span class="sr-text">이력서명 입력</span>
                                           <input type="text" name="ru_name" placeholder="이력서명을 작성해주세요" value="<?=$info["ru_name"]?>" autocomplete="off" aria-required="true" required class="form-text">
                                       </label>
                                   </div>
                               </div>
                               <div class="form-row">
                                 <span class="foldbtn" id="btn2" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label"><span class="fixed">*</span>희망업무 <em>희망업무는 2개까지 선택가능합니다.</em></p>
                                   <div class="value">
                                       <div class="form-group">
                                           <?php foreach($ru_wrok_list as $row) : ?>
                                           <label class="form-toggle-btn2">
                                               <input type="checkbox" name="ru_work[]" data-select-limit="2" value="<?=$row["cd_val"]?>" data-img="<?=$row["cd_val2"]?>" <?=in_array_return($row["cd_val"],explode(",",$info["ru_work"]),"check")?>>
                                               <span class="btn"><?=$row["cd_val"]?></span>
                                           </label>
                                           <?php endforeach;?>
                                       </div>
                                   </div>
                               </div>

                               <div class="form-row">
                                 <span class="foldbtn" id="btn3" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label"><span class="fixed">*</span>희망지역 <em>희망지역은 2개까지 가능합니다.</em></p>
                                   <div class="value">
                                       <div class="form-group">
                                           <ul class="added-location"  id="addr_location">
                                               <?php foreach($addr_list as $row) : ?>
											       <?php if (!empty($row["ad_addr"])) : ?>
                                               <li>
                                                   <input type="hidden" name="ad_zipcode[]" value="<?=$row["ad_zip_code"]?>" />
                                                   <input type="hidden" name="ad_jibun[]" value="<?=$row["ad_jibun"]?>" />
                                                   <input type="hidden" name="ad_doro[]" value="<?=$row["ad_doro"]?>" />
                                                   <input type="hidden" name="ad_addr[]" value="<?=$row["ad_addr"]?>" />
                                                   <input type="hidden" name="ad_gps_x[]" value="<?=$row["ad_gps_x"]?>" />
                                                   <input type="hidden" name="ad_gps_y[]" value="<?=$row["ad_gps_y"]?>" />
                                                   <p><?=$row["ad_addr"]?></p>
                                                   <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                                               </li>
													<?php endif; ?>											   
                                               <?php endforeach; ?>
                                           </ul>
                                           <a href="javascript:void(0);" onclick="showAddrInfo(2);" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn-add-more js-toggle-panel"><span class="sr-text">추가작성</span></a>
                                       </div>
                                   </div>
                               </div>

                               <div class="form-row">
                                 <span class="foldbtn" id="btn4" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label"><span class="fixed">*</span>급여형태 <em>급여형는 2개까지 가능합니다.</em></p>
                                   <div class="value">
                                       <div class="form-group">
                                           <?php foreach($ru_price_type_list as $row) : ?>
                                           <label class="form-toggle-btn2">
                                               <input type="checkbox" name="ru_price_type[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],explode(",",$info["ru_price_type"]),"check")?>>
                                               <span class="btn"><?=$row["cd_val"]?></span>
                                           </label>
                                           <?php endforeach;?>
                                       </div>
                                       <div class="addition-option cont-price <?=(strpos($info["ru_price_type"],"월급x")!==false ? "":"hidden")?>" >
	                                        <input type="radio" name="opt" id="opt1">
	                                        <input type="radio" name="opt" id="opt3">

	                                        <div id="amount3" class="row" style="padding-left:100px;width:270px;">
	                                            <div class="label" style="width:90px;">
	                                                 <p class="on" style="width:100%;font-weight:normal;">희망연봉</p>
	                                            </div>
	                                            <label class="inline-group">
	                                                <input type="text" name="ru_month_price" value="<?=number_format($info["ru_month_price"])?>" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
	                                                <span class="is-abs">원</span>
	                                            </label>
	                                        </div>
	                                    </div>
                                       <p class="help-msg  <?=count(explode(",",$info["ru_price_type"]))==3 ? "":"hidden"?>">협의 가능</p>
                                   </div>
                               </div>

                               <div class="form-row">
                                 <span class="foldbtn" id="btn5" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label"><span class="fixed">*</span>근무형태 <em>다중선택가능</em></p>
                                   <div class="value">
                                       <div class="form-group">
                                           <?php foreach($ru_work_type_list as $row) : ?>
                                           <label class="form-toggle-btn2">
                                               <input type="checkbox" name="ru_work_type[]"  class="<?=$row["cd_val"]!="2교대" ? "js-check-time":""?>" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],explode(",",$info["ru_work_type"]),"check")?>>
                                               <span class="btn"><?=$row["cd_val"]?></span>
                                           </label>
                                           <?php endforeach;?>
                                       </div>
	                                    <p class="help-msg <?=count(explode(",",$info["ru_work_type"]))==3 ? "":"hidden"?>">협의 가능</p>
                                   </div>
                                   <script>
                                       (function () {
                                           $("[name='ru_work_type[]']").click(function(){
                                           		if($("[name='ru_work_type[]']:checked").length==3){
                                           			$(this).closest(".value").find(".help-msg").removeClass("hidden");
                                           		}else{
                                           			$(this).closest(".value").find(".help-msg").addClass("hidden");
                                           		}
                                           });

                                           $("[name='ru_price_type[]']").click(function(){
                                              //console.log($("[name='ru_price_type[]']:checked").length);
                                              if($("[name='ru_price_type[]']:checked").length==3){
                                                commonAlert("급여는 2개까지 선택 가능합니다.",function(){});
                                                return false;
                                              }
                                           		if($("[name='ru_price_type[]']:checked").length==3){
                                           			$(this).closest(".value").find(".help-msg").removeClass("hidden");
                                           		}else{
                                           			$(this).closest(".value").find(".help-msg").addClass("hidden");
                                           		}

                                              //월급 선택시 희망연봉 표시 on/off
                                           		//if($("[name='ru_price_type[]']").eq(2).is(":checked")){
                                           		//	$(".cont-price").removeClass("hidden");
                                           		//}else{
                                           		//	$(".cont-price").addClass("hidden");
                                           		//}
                                           });

                                           /* 근무시간 문구 표시 */
                                           function timeChecked (el) {
                                               var total = document.querySelectorAll('.js-check-time').length;
                                               var isChecked = document.querySelectorAll('.js-check-time:checked');
                                               var row = el.closest('.form-row');
                                               var msg = row.querySelector('.help-msg');

                                               if (!msg) {
                                                   //msg = document.createElement('P');
                                                   //msg.classList.add('help-msg');
                                                   //msg.innerHTML = '주간 고정, 야간 고정을 선택하셨습니다.';
                                               }

                                               if (isChecked.length == total) {
                                               //    row.appendChild(msg);
                                               } else {
                                               //    msg.remove();
                                               }
                                           }

                                           document.querySelectorAll('.js-check-time').forEach(function (item) {
                                               item.addEventListener('change', function () {
                                                   timeChecked(item);
                                               });

                                               timeChecked(item);
                                           });
                                       }());
                                   </script>
                               </div>

                               <div class="form-row">
                                 <span class="foldbtn" id="btn6" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label">근무희망요일 <em>다중선택가능</em></p>
                                   <div class="value">
                                       <div class="inline-group week-day">
                                           <div class="form-group">
                                               <?php foreach($ru_week_list as $row) : ?>
                                               <label class="form-toggle-btn2 sm">
                                                   <input type="checkbox" name="ru_week[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],explode(",",$info["ru_week"]),"check")?>>
                                                   <span class="btn"><?=$row["cd_val"]?></span>
                                               </label>
                                               <?php endforeach;?>
                                           </div>
                                           <div class="option">
                                               <label class="form-checkbox">
                                                   <input type="checkbox" name="ru_week_yn" value="Y" class="sr-text" <?=$info["ru_week_yn"]=="Y" ? "checked":""?>>
                                                   <span class="label">요일무관</span>
                                               </label>
                                           </div>
                                       </div>
                                   </div>
                               </div>

                               <div class="form-row">
                                 <span class="foldbtn" id="btn7" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label">근무희망일</p>
                                   <div class="value">
                                       <div class="calendar">
                                           <div class="js-datepicker"></div>
                                           <input type="hidden" name="ru_work_dt" aria-required="true" required class="js-date-value" value="<?=$info["ru_work_dt"]?>">
                                       </div>
                                   </div>

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
                                               flatpickr('.calendar .js-datepicker', {
                                                   inline: true,
                                                   locale: 'ko',
                                                   dateFormat: 'Y-m-d',
                                                   ariaDateFormat: 'Y-m-d',
                                                   defaultDate : '<?=$info["ru_work_dt"]?>',
                                                   nextArrow: '<span class="sr-text">다음</span>',
                                                   prevArrow: '<span class="sr-text">이전</span>',
                                                   disableMobile: true,
                                                   onChange: function (selectedDates, dateStr, instance) {
                                                       instance.calendarContainer.parentNode.querySelector('.js-date-value').value = dateStr;
                                                   },
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

                               <div class="form-row">
                                 <span class="foldbtn" id="btn8" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label">최종학력</p>
                                   <div class="value">
                                       <div class="form-group">
                                           <?php foreach($ru_edu_list as $row) : ?>
                                           <label class="form-toggle-btn2">
                                               <input type="radio" name="ru_edu" value="<?=$row["cd_val"]?>"  <?=equal_return($row["cd_val"],$info["ru_edu"],"check")?>>
                                               <span class="btn"><?=$row["cd_val"]?></span>
                                           </label>
                                           <?php endforeach;?>
                                       </div>
                                   </div>
                               </div>

                               <div class="form-row">
                                 <span class="foldbtn" id="btn9" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label">간단경력 <em>간단경력은 최대 10개의 경력사항을 기입가능합니다.<br> 업체명 최대 13자</em></p>

                                   <div class="value">
                                       <!-- 목록 -->
                                       <ol class="added-list career_list">
                                           <?php if (is_array($career_list) && !empty($career_list)) :
                                             foreach($career_list as $row):
                                               $rc_type = $row['rc_type'];
                                               $rc_name = $row['rc_name'];
                                               $rc_date = $row['rc_date'];
                                           ?>
                                              <li>
                                                <div class="form-group">
                                                  <div class="col sm">
                                                    <input type="text" name="category[]" value="<?=$rc_type;?>" disabled class="form-text txt-color2">
                                                  </div>
                                                  <div class="col">
                                                    <input type="text" name="company[]" maxlength="13" disabled value="<?=$rc_name;?>" class="form-text">
                                                  </div>
                                                  <div class="col">                                                              
                                                    <input type="text" name="period[]" disabled value="<?=$rc_date;?>" class="form-text num">
                                                  </div>
                                                </div>
                                                <button type="button" class="btn-clear js-del-item">삭제</button>
                                              </li>
                                           <?php endforeach; ?>
                                           <?php endif; ?>
                                       </ol>
                                       <div class="added-form added-career-form">
                                           <div class="form-group">
                                               <div class="col sm">
                                                   <label class="form-selectbox">
                                                       <span class="sr-text">업종선택</span>
                                                       <select name="category[]">
                                                           <option value="" selected disabled>업종</option>
                                                           <?php foreach($ru_wrok_list as $row) : ?>
                                                          		<option value="<?=$row["cd_val"]?>"><?=$row["cd_val"]?></option>
                                                          	<?php endforeach;?>
                                                       </select>
                                                       <span class="liner"></span>
                                                   </label>
                                               </div>
                                               <div class="col">
                                                   <label>
                                                       <span class="sr-text">업체명 입력</span>
                                                       <input type="text" name="company[]" maxlength="13" placeholder="업체명" autocomplete="off" class="form-text">
                                                   </label>
                                               </div>
                                               <div class="col">
                                                   <label>
                                                       <span class="sr-text">근무기간 입력</span>
                                                       <input type="text" name="period[]" placeholder="근무기간" autocomplete="off" class="form-text num js-datepicker">
                                                   </label>
                                               </div>
                                           </div>
                                           <button type="button" class="btn-add-item js-add-career">등록</button>
                                       </div>
                                   </div>

                                   <!-- 입력폼 템플릿 -->
                                   <template id="careerTemp">
                                       <li>
                                           <div class="form-group">
                                               <div class="col sm">
                                                   <input type="text" name="category[]" value="{$category}" disabled class="form-text txt-color2">
                                               </div>
                                               <div class="col">
                                                   <input type="text" name="company[]" maxlength="13" value="{$company}" disabled class="form-text">
                                               </div>
                                               <div class="col">
                                                   <input type="text" name="period[]" value="{$period}" disabled class="form-text num">
                                               </div>
                                           </div>
                                           <button type="button" class="btn-clear js-del-item">삭제</button>
                                       </li>
                                   </template>

                                   <script>
                                       (function () {
                                           function chgYear (instance) {
                                               var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                                                   year = instance.currentYear;
                                               $con.innerHTML = '<span class="month">' + year + '</span>';
                                           }

                                           function totalRange (rangeDate) {
                                               var diffYear, diffMonth;

                                               if(rangeDate.indexOf('~') == -1) return '';

                                               diffYear = flatpickr.parseDate(rangeDate.split(' ~ ')[1], 'Y.m').getFullYear() - flatpickr.parseDate(rangeDate.split(' ~ ')[0], 'Y.m').getFullYear();
                                               diffMonth = flatpickr.parseDate(rangeDate.split(' ~ ')[1], 'Y.m').getMonth() - flatpickr.parseDate(rangeDate.split(' ~ ')[0], 'Y.m').getMonth();
                                               diffYear = diffYear > 0 ? diffYear + '년': '';
                                               diffMonth = diffMonth > 0 ? diffMonth + '개월' : '';
                                               return '';
                                               //return '(' + diffYear + diffMonth + ')';
                                           }

                                           function initDatepicker (el) {
                                               if (typeof flatpickr == 'undefined') return;

                                               //달력 생성
                                               flatpickr(el, {
                                                   mode: 'range',
                                                   locale: 'ko',
                                                   nextArrow: '<span class="sr-text">다음</span>',
                                                   prevArrow: '<span class="sr-text">이전</span>',
                                                   disableMobile: true,
                                                   plugins: [
                                                       new monthSelectPlugin({
                                                           shorthand: true,
                                                           dateFormat: "Y.m",
                                                           altFormat: "Y.m",
                                                       })
                                                   ],
                                                   onYearChange: function (selectedDates, dateStr, instance) {
                                                       chgYear(this);
                                                   },
                                                   onReady: function () {
                                                       chgYear(this);
                                                   },
                                                   onClose: function (selectedDates, dateStr, instance) {
                                                       if (selectedDates.length > 1) {
                                                           instance.calendarContainer.querySelector('.flatpickr-next-month').click();
                                                       }
                                                   },
                                                   onOpen: function (selectedDates, dateStr, instance) {
                                                       if (selectedDates.length > 1) {
                                                           instance.calendarContainer.querySelector('.flatpickr-prev-month').click();
                                                       }
                                                   },
                                                   onChange: function (selectedDates, dateStr, instance) {
                                                       instance.calendarContainer.querySelectorAll('.flatpickr-monthSelect-month').forEach(function (month) {
                                                           month.classList.remove('startRange', 'inRange', 'endRange')
                                                       });
                                                   }
                                               });
                                           }

                                           window.addEventListener('DOMContentLoaded', function () {
                                               initDatepicker(document.querySelector('.added-career-form .js-datepicker'));

                                               //경력 목록 추가
                                               document.querySelector('.js-add-career').addEventListener('click', function (e) {
                                                   e.preventDefault();
                                                   var btn = this,
                                                       max = 9,
                                                       temp = document.getElementById('careerTemp').content.cloneNode(true),
                                                       list = btn.parentNode.parentNode.querySelector('.added-list'),
                                                       lastItem = null;

                                                   var category = btn.parentNode.querySelector('[name="category[]"]'),
                                                       company = btn.parentNode.querySelector('[name="company[]"]'),
                                                       period = btn.parentNode.querySelector('[name="period[]"]');

                                                   if(!category.value) {
                                                       category.focus();
                                                       return;
                                                   } else {
                                                       temp.children[0].innerHTML = temp.children[0].innerHTML.replace('{$category}', category.value);
                                                   }

                                                   if(!company.value) {
                                                       company.focus();
                                                       return;
                                                   } else {
                                                       temp.children[0].innerHTML = temp.children[0].innerHTML.replace('{$company}', company.value);
                                                   }

                                                   if(!period.value) {
                                                       period.focus();
                                                       return;
                                                   } else {
                                                       temp.children[0].innerHTML = temp.children[0].innerHTML.replace('{$period}', period.value + totalRange(period.value));
                                                   }

                                                   if (list.children.length < max) {
                                                       list.appendChild(temp);
                                                       lastItem = list.children[list.children.length - 1];

                                                       lastItem.querySelector('.js-del-item').addEventListener('click', function () {
                                                           if(list.children.length <= 3) {
                                                               btn.style.display = '';
                                                           }
                                                       });

                                                       category.children[0].selected = true;
                                                       category.parentNode.querySelector('.liner').textContent = category.children[0].textContent;
                                                       company.value = '';
                                                       period.value = '';
                                                   }
                                               });
                                           });

                                       }());
                                   </script>
                               </div>

                               <div class="form-row">
                                 <span class="foldbtn" id="btn10" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label">나의강점 <em>나의강점을 선택하세요(최대6개까지)</em></p>
                                   <div class="value">
                                       <div class="form-group col-8 full-width">
                                           <?php foreach($ru_my_type_list as $row) : ?>
                                           <label class="form-toggle-btn2 sm">
                                               <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],explode(",",$info["ru_my_type"]),"check")?>>
                                              <span class="btn"><?=$row["cd_val"]?></span>
                                           </label>
                                           <?php endforeach;?>
                                       </div>

                                       <div class="advantage-form">
                                           <div class="inline-group">
                                               <label class="col">
                                                   <input type="text" name="addAdvantage" placeholder="나의 강점을 입력해주세요" autocomplete="off" class="form-text has-confirm">
                                               </label>
                                               <button type="button" class="btn-round btn-tertiary is-abs js-add-advantage">추가</button>
                                           </div>

                                           <!-- 추가목록 -->
                                           <div class="form-group col-8 full-width">
                                            <!-- 추가 태그 -->
                                            <?php if(isset($info["ru_my_type"]) && $info["ru_my_type"]!="") : ?>
				                            <?php foreach(explode(",",$info["ru_my_type"]) as $row) : ?>
					                            <?php
					                            	$is_arr = false;
					                            	foreach($ru_my_type_list as $temp){
					                            		if($temp["cd_val"] == $row){
					                            			$is_arr = true;
					                            		}
					                            	}
													if($is_arr==false) :
					                            ?>
					                            <label class="form-toggle-btn2">
					                                <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?=$row?>" checked>
					                               <span class="btn"><?=$row?></span>
					                            </label>
					                            <?php endif; ?>
				                            <?php endforeach; ?>
				                            <?php endif; ?>
                                           </div>
                                       </div>

                                       <script>
                                           (function () {
                                               //목록선택 제한 설정
                                               document.querySelectorAll('input[data-select-limit]').forEach(function (item) {
                                                   item.addEventListener('change', function () {
                                                       var childItems = document.querySelectorAll('[name="'+ this.getAttribute('name') +'"]:checked');

                                                       if (this.checked == childItems.length > this.getAttribute('data-select-limit')) {
                                                           this.checked = false;
                                                       }
                                                   });
                                               });

                                               document.querySelector('.js-add-advantage').addEventListener('click', function () {
                                                   var value = document.querySelector('input[name="addAdvantage"]').value;
                                                   var label;

                                                   if(!value) return;

                                                   label = document.createElement('LABEL');
                                                   value = value.trim().replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-+<>@\#$%&\\\=\(\'\"]/g,'');

                                                   label.setAttribute('class', 'form-toggle-btn2');
                                                   label.innerHTML = '<input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="'+ value +'"> <span class="btn">'+ value +'</span>';

                                                   document.querySelector('.advantage-form .form-group').appendChild(label);

                                                   label.querySelector('input').addEventListener('change', function () {
                                                       var childItems = document.querySelectorAll('[name="'+ this.getAttribute('name') +'"]:checked');

                                                       if (this.checked == childItems.length > this.getAttribute('data-select-limit')) {
                                                           this.checked = false;
                                                       }
                                                   });

                                                   document.querySelector('input[name="addAdvantage"]').value = "";
                                               });
                                           }());
                                       </script>

                                   </div>
                               </div>

                               <div class="form-row">
                                 <span class="foldbtn" id="btn11" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label">자기소개 <em>(최대500자)</em></p>
                                   <div class="value">
                                       <label class="full-width">
                                           <span class="sr-text">자기소개 입력</span>
                                           <textarea name="ru_desc" maxlength="500" class="form-textarea js-autoheight"><?=$info["ru_desc"]?></textarea>
                                       </label>
                                   </div>
                                   <p class="help-msg text-only">*개인 연락처 및 카카오톡 아이디 첨부 시 게시물 삭제 및 이용제제 처리될 수 있습니다.</p>
                               </div>

                               <div class="form-row">
                                 <span class="foldbtn" id="btn12" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label">자격증 <em>허위사실 기재시 합격이 취소될 수 있습니다</em></p>

                                   <div class="value">
                                       <!-- 목록 -->
                                       <ol class="added-list license_list">
                                       <?php if (is_array($certi_list) && !empty($certi_list)) :
                                         foreach($certi_list as $row):
                                           $rc_name = $row['rc_name'];
                                           $rc_date = $row['rc_dt'];
                                       ?>
                                        <li>
                                          <div class="form-group">
                                            <div class="col md">
                                              <input type="text" name="license[]" value="<?=$rc_name;?>" disabled class="form-text">
                                            </div>
                                            <div class="col">
                                              <input type="text" name="regdate[]" value="<?=$rc_date;?>" disabled class="form-text num">
                                            </div>
                                          </div>
                                          <button type="button" class="btn-clear js-del-item">삭제</button>
                                        </li>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                       </ol>
                                       <div class="added-form added-license-form">
                                           <div class="form-group">
                                               <div class="col md">
                                                   <label>
                                                       <span class="sr-text">자격증명 입력</span>
                                                       <input type="text" name="license[]" placeholder="자격증명" autocomplete="off" class="form-text">
                                                   </label>
                                               </div>
                                               <div class="col">
                                                   <label>
                                                       <span class="sr-text">취득일자 입력</span>
                                                       <input type="text" name="regdate[]" placeholder="취득일자" autocomplete="off" class="form-text num js-datepicker">
                                                   </label>
                                               </div>
                                           </div>
                                           <button type="button" class="btn-add-item js-add-license">등록</button>
                                       </div>
                                   </div>

                                   <!-- 입력폼 템플릿 -->
                                   <template id="licenseTemp">
                                       <li>
                                           <div class="form-group">
                                               <div class="col md">
                                                   <input type="text" name="license[]" value="{$license}" disabled class="form-text">
                                               </div>
                                               <div class="col">
                                                   <input type="text" name="regdate[]" value="{$regdate}" disabled class="form-text num">
                                               </div>
                                           </div>
                                           <button type="button" class="btn-clear js-del-item">삭제</button>
                                       </li>
                                   </template>

                                   <script>
                                       (function () {
                                           function chgYear (instance) {
                                               var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                                                   year = instance.currentYear;
                                               $con.innerHTML = '<span class="month">' + year + '</span>';
                                           }

                                           function initDatepicker (el) {
                                               if (typeof flatpickr == 'undefined') return;

                                               //달력 생성
                                               flatpickr(el, {
                                                   locale: 'ko',
                                                   nextArrow: '<span class="sr-text">다음</span>',
                                                   prevArrow: '<span class="sr-text">이전</span>',
                                                   disableMobile: true,
                                                   plugins: [
                                                       new monthSelectPlugin({
                                                           shorthand: true,
                                                           dateFormat: "Y.m",
                                                           altFormat: "Y.m",
                                                       })
                                                   ],
                                                   onYearChange: function (selectedDates, dateStr, instance) {
                                                       chgYear(this);
                                                   },
                                                   onReady: function () {
                                                       chgYear(this);
                                                   },
                                                   onClose: function (selectedDates, dateStr, instance) {
                                                       if (selectedDates.length > 1) {
                                                           instance.calendarContainer.querySelector('.flatpickr-next-month').click();
                                                       }
                                                   },
                                                   onOpen: function (selectedDates, dateStr, instance) {
                                                       if (selectedDates.length > 1) {
                                                           instance.calendarContainer.querySelector('.flatpickr-prev-month').click();
                                                       }
                                                   },
                                                   onChange: function (selectedDates, dateStr, instance) {
                                                       instance.calendarContainer.querySelectorAll('.flatpickr-monthSelect-month').forEach(function (month) {
                                                           month.classList.remove('startRange', 'inRange', 'endRange')
                                                       });
                                                   }
                                               });
                                           }

                                           window.addEventListener('DOMContentLoaded', function () {
                                               initDatepicker(document.querySelector('.added-license-form .js-datepicker'));

                                               //자격증 목록 추가
                                               document.querySelector('.js-add-license').addEventListener('click', function (e) {
                                                   e.preventDefault();
                                                   var btn = this,
                                                       max = 4,
                                                       temp = document.getElementById('licenseTemp').content.cloneNode(true),
                                                       list = btn.parentNode.parentNode.querySelector('.added-list'),
                                                       lastItem;

                                                   var license = btn.parentNode.querySelector('[name="license[]"]'),
                                                       regdate = btn.parentNode.querySelector('[name="regdate[]"]');

                                                   if(!license.value) {
                                                       license.focus();
                                                       return;
                                                   } else {
                                                       temp.children[0].innerHTML = temp.children[0].innerHTML.replace('{$license}', license.value);
                                                   }

                                                   if(!regdate.value) {
                                                       regdate.focus();
                                                       return;
                                                   } else {
                                                       temp.children[0].innerHTML = temp.children[0].innerHTML.replace('{$regdate}', regdate.value);
                                                   }

                                                   if (list.children.length < max) {
                                                       list.appendChild(temp);
                                                       lastItem = list.children[list.children.length - 1];

                                                       lastItem.querySelector('.js-del-item').addEventListener('click', function () {
                                                           if(list.children.length <= max) {
                                                               btn.style.display = '';
                                                           }
                                                       });

                                                       license.value = '';
                                                       regdate.value = '';
                                                   }
                                               });
                                           });
                                       }());
                                   </script>
                               </div>

                               <!-- div class="form-row">
                                 <span class="foldbtn" id="btn13" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label">포트폴리오</p>
                                   <div class="value">
                                       <div class="form-file js-show-filename">
                                           <input type="text" disabled class="form-text file-name">
                                           <label class="btn-round btn-tertiary" style="margin-right: 34px;">
                                               <span class="sr-text">파일 선택</span>
                                               <input type="file" name="portfolio" accept="image/*,.zip,.pdf">
                                               <span>첨부</span>
                                           </label>
                                       </div>
                                   </div>
                               </div>

                               <div class="form-row">
                                 <span class="foldbtn" id="btn14" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                   <p class="form-label"><span class="fixed">*</span>첨부사진</p>
                                   <div class="value">
                                       <div class="form-pic-group">
                                           <div class="form-pic-group">
                                               <label class="form-pic first">
                                                   <input type="file" name="ru_img1" accept="image/*" class="js-file-preview">
                                                   <span class="label">프로필 대표사진을<br> 첨부해주세요</span>
                                                   <span class="preview">
                   	                            	<?php if(getData($info,"ru_img1")!="") : ?>
                   	                            		<img src="<?=getData($info,"ru_img1")?>" />
                   	                            		<input type='hidden' name='ru_img1_ori' value='<?=getData($info,"ru_img1")?>' />
                   	                            	<?php endif; ?>
                   	                            </span>
                                               </label>
                                               <label class="form-pic">
                                                   <input type="file" name="ru_img2" accept="image/*" class="js-file-preview">
                                                   <span class="preview">
                   	                            	<?php if(getData($info,"ru_img2")!="") : ?>
                   	                            		<img src="<?=getData($info,"ru_img2")?>" />
                   	                            		<input type='hidden' name='ru_img2_ori' value='<?=getData($info,"ru_img2")?>' />
                   	                            	<?php endif; ?>
                   	                            </span>
                                               </label>
                                               <label class="form-pic">
                                                   <input type="file" name="ru_img3" accept="image/*" class="js-file-preview">
                                                   <span class="preview">
                   	                            	<?php if(getData($info,"ru_img3")!="") : ?>
                   	                            		<img src="<?=getData($info,"ru_img3")?>" />
                   	                            		<input type='hidden' name='ru_img3_ori' value='<?=getData($info,"ru_img3")?>' />
                   	                            	<?php endif; ?>
                   	                            </span>
                                               </label>
                                               <label class="form-pic">
                                                   <input type="file" name="ru_img4" accept="image/*" class="js-file-preview">
                                                   <span class="preview">
                   	                            	<?php if(getData($info,"ru_img4")!="") : ?>
                   	                            		<img src="<?=getData($info,"ru_img4")?>" />
                   	                            		<input type='hidden' name='ru_img4_ori' value='<?=getData($info,"ru_img4")?>' />
                   	                            	<?php endif; ?>
                   	                            </span>
                                               </label>
                                               <label class="form-pic">
                                                   <input type="file" name="ru_img5" accept="image/*" class="js-file-preview">
                                                   <span class="preview">
                   	                            	<?php if(getData($info,"ru_img5")!="") : ?>
                   	                            		<img src="<?=getData($info,"ru_img5")?>" />
                   	                            		<input type='hidden' name='ru_img5_ori' value='<?=getData($info,"ru_img5")?>' />
                   	                            	<?php endif; ?>
                   	                            </span>
                                               </label>
                                           </div>
                                       </div>
                                   </div>
                                   <p class="help-msg text-only">사진을 추가하시면 취업활동에 더 좋습니다.<br> 기본프로필을 사용시 면접에 영향이 갈수도 있습니다.
                                     <span style="display: block; color: #1B9D7D;"> ※ 권장사이즈 : 200 * 200</span>
                                   </p>
                               </div -->

                           </div>
                       </div>
                       <div class="form-action-wrap">
                           <button type="button" id="btnStep" class="btn-round btn-tertiary"><?=$info!=null && $info["ru_idx"]!="0" ? "수정":"등록"?>완료</button>
                       </div>
                   </form>
               </div>
           </div>
       </div>
   </main><!-- //page-content -->
</div><!-- //page -->
<script>
	// 검색 완료 선택 처리
	function selectAddrInfo(zipcode,doro,jibun,addr,x,y){

		var html = "";
		html +='<li>';
		html +='<input type="hidden" name="ad_zipcode[]" value="'+zipcode+'" />';
		html +='<input type="hidden" name="ad_jibun[]" value="'+jibun+'" />';
		html +='<input type="hidden" name="ad_doro[]" value="'+doro+'" />';
		html +='<input type="hidden" name="ad_addr[]" value="'+addr+'" />';
		html +='<input type="hidden" name="ad_gps_x[]" value="'+x+'" />';
		html +='<input type="hidden" name="ad_gps_y[]" value="'+y+'" />';
		html +='<p>'+addr+'</p>';
		html +='<button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>';
		html +='</li>';

		$("#addr_location").append(html);

		//팝업 종료
		$("#changeArea").removeClass("on");
	}

	function showAddrInfo(maxlen){
		if($("#addr_location").find("li").length >= maxlen){
			commonAlert("희망지역은 " + (maxlen -1)+ "개까지 등록 가능합니다.",function(){});
            return;
		}
		$("#changeArea").addClass("on");
	}

	$(document).ready(function(){

    <?php if($mem_info == null || ( strpos($mem_info["mm_di"],"MC0") !== 0 )){
      //$mem_info == null || strpos($mem_info["mm_di"], "010") === 0
       ?>
        commonAlert("본인인증 후에 이력서를 등록 하실 수 있습니다.",function(){
            location.href="/mypage/mypage?reqgbn=pass";
        });
    <?php } ?>

		//처리
		$("#btnStep").click(function(){
			if($("[name='ru_name']").val()==""){
                commonAlert("이력서명을 입력해주세요.",function(){
                	$("[name='ru_name']").focus();
                });
                return;
            }
            if($("[name='ru_work[]']").is(":checked")==false){
                commonAlert("희망업무는 하나이상 선택해주세요.",function(){});
                return;
            }
			
			if ($("#addr_location li").filter(function () {
				return $(this).find('input[name="ad_addr[]"]').val().trim() !== "";
			}).length == 0) {
				commonAlert("희망지역을 선택해주세요.", function () {});
				return;
			}			

            if($("[name='ru_price_type[]']").is(":checked")==false){
                commonAlert("급여형태는 하나이상 선택해주세요.",function(){});
                return;
            }

            if($("[name='ru_price_type[]']").eq(2).is(":checked")==true && ($("[name='ru_month_price']").val()=="" || $("[name='ru_month_price']").val()=="0")){
                //commonAlert("희망연봉을 입력해주세요.",function(){
                //	$("[name='ru_month_price']").focus();
                //});
                //return;
            }

            if($("[name='ru_work_type[]']").is(":checked")==false){
                commonAlert("근무형태는 하나이상 선택해주세요.",function(){});
                return;
            }

            //if($("[name='ru_week_yn']").is(":checked")==false && $("[name='ru_week[]']").is(":checked")==false){
            //    commonAlert("근무가능 요일을 하나이상 선택해주세요.",function(){});
            //    return;
            //}

            if($("[name='ru_work_dt']").val()==""){
              $("[name='ru_work_dt']").val('<?=Date("Y-m-d")?>');
            //    commonAlert("근무가능일을 선택해주세요.",function(){});
            //    return;
            }

            //if($("[name='ru_edu']").is(":checked")==false){
            //    commonAlert("최종학력을 선택해주세요.",function(){});
            //    return;
            //}

            var arr_career = "";
            var $carrer_obj = $(".career_list");
            for(var icnt=0;icnt < $carrer_obj.find("[name='category[]']").length;icnt++){
                if($carrer_obj.find("[name='category[]']").eq(icnt).val()!=""){
                    if($carrer_obj.find("[name='category[]']").eq(icnt).val()==""){
                        commonAlert("업종을 선택해주세요.",function(){
                            $carrer_obj.find("[name='category[]']").eq(icnt).focus();
                        });
                        return;
                    }

                    if($carrer_obj.find("[name='company[]']").eq(icnt).val()==""){
                        commonAlert("업체명을 입력해주세요.",function(){
                            $carrer_obj.find("[name='company[]']").eq(icnt).focus();
                        });
                        return;
                    }

                    if($carrer_obj.find("[name='period[]']").eq(icnt).val()==""){
                        commonAlert("근무기간을 선택해주세요.",function(){
                            $carrer_obj.find("[name='period[]']").eq(icnt).focus();
                        });
                        return;
                    }

                    if(arr_career==""){
                        arr_career = $carrer_obj.find("[name='category[]']").eq(icnt).val() + "|||" + $carrer_obj.find("[name='company[]']").eq(icnt).val() + "|||" + $carrer_obj.find("[name='period[]']").eq(icnt).val();
                    }else{
                        arr_career = arr_career + "&&&" + $carrer_obj.find("[name='category[]']").eq(icnt).val() + "|||" + $carrer_obj.find("[name='company[]']").eq(icnt).val() + "|||" + $carrer_obj.find("[name='period[]']").eq(icnt).val();
                    }
                }
            }
            $("[name=arr_career]").val(arr_career);

            var ru_work_img = "";
            for(var icnt=0;icnt<$("[name='ru_work[]']:checked").length;icnt++){
            	if(ru_work_img==""){
            		ru_work_img = $("[name='ru_work[]']:checked").eq(icnt).data("img");
            	}else{
            		ru_work_img += "," + $("[name='ru_work[]']:checked").eq(icnt).data("img");
            	}
            }
			      $("[name=ru_work_img]").val(ru_work_img);

            //if($("[name='ru_desc']").val()==""){
            //    commonAlert("자기소개서를 입력해주세요.",function(){});
            //    return;
            //}

            var arr_certi = "";
            var $license_obj = $(".license_list");
            for(var icnt=0;icnt < $license_obj.find("[name='license[]']").length;icnt++){
                if($license_obj.find("[name='license[]']").eq(icnt).val()!=""){
                    if($license_obj.find("[name='license[]']").eq(icnt).val()==""){
                        commonAlert("자격증명을 입력해주세요.",function(){
                            $license_obj.find("[name='license[]']").eq(icnt).focus();
                        });
                        return;
                    }

                    if($license_obj.find("[name='regdate[]']").eq(icnt).val()==""){
                        commonAlert("취득일자를 선택해주세요.",function(){
                            $license_obj.find("[name='regdate[]']").eq(icnt).focus();
                        });
                        return;
                    }

                    if(arr_certi==""){
                        arr_certi = $license_obj.find("[name='license[]']").eq(icnt).val() + "|||" + $license_obj.find("[name='regdate[]']").eq(icnt).val();
                    }else{
                        arr_certi = arr_certi + "&&&" + $license_obj.find("[name='license[]']").eq(icnt).val() + "|||" + $license_obj.find("[name='regdate[]']").eq(icnt).val();
                    }
                }
            }
            $("[name=arr_certi]").val(arr_certi);

            if($("[name=ru_img1]").val()=="" && $("[name=ru_img1_ori]").val()==""){
            	commonAlert("첨부사진을 1나이상 선택해주세요.",function(){});
                return;
            }

            var form = $('#frmAll');
            var params = new FormData(form[0]);
            params.set("ru_month_price",params.get("ru_month_price").replaceAll(",",""));
            fileAjaxLoading("/mypage_proc/resume_write",params,function(ret){
                if(ret.code=="00"){
                    showPreviewInfo(ret.data);
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
		});

		$(".comma").on("keyup", function(){
		    $(this).val($(this).val().replace(/\,/g, '').replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'));
		});


		$("[name=ru_week_yn]").click(function(){
			if($(this).is(":checked")){
				$("[name='ru_week[]']").prop("checked",true);
			}else{
				$("[name='ru_week[]']").prop("checked",false);
			}
		});

		$("[name='ru_week[]']").click(function(){
			if($("[name='ru_week[]']:checked").length == 7){
				$("[name=ru_week_yn]").prop("checked",true);
			}else{
				$("[name=ru_week_yn]").prop("checked",false);
			}
		});

    fFoldAll('Y');

        comAjax("/common_proc/get_member_info",{},function(ret){
			//console.log('get_member_info');
			//console.log(ret);
            if(ret.code=="00"){
				//console.log('00');
				//selectAddrInfo(ret.data.mm_zipcode, ret.data.mm_doro, ret.data.mm_jibun, ret.data.mm_addr, ret.data.mm_gps_x, ret.data.mm_gps_y);
            }
        });

	});//ready

    function showPreviewInfo(info){
    	var str_ru_work_img = "";
    	var str_ru_week = "";
    	var str_ru_career = "";
    	var str_ru_certi = "";
    	var str_ru_type = "";
    	var str_img = "";
    	var str_month_price = "";

    	if(info.ru_work_img){
    		for(var icnt=0;icnt < info.ru_work_img.length;icnt++){
    			str_ru_work_img += "<img src='"+info.ru_work_img[icnt]+"' alt='생산' class='category-label'>";
    		}
    	}

    	if(info.ru_week){
	    	for(var icnt=0;icnt < info.ru_week.length;icnt++){
	    		str_ru_week += "<li aria-hidden='false'>"+info.ru_week[icnt]+"</li>";
	    	}
    	}

    	if(info.arr_career){
    		for(var icnt=0;icnt < info.arr_career.split("&&&").length;icnt++){
    			var temp = info.arr_career.split("&&&")[icnt].split("|||");
    			if(temp.length  > 2){
    				str_ru_career += "<li><span>"+temp[0]+"</span><span>"+temp[1]+"</span><em>"+temp[2]+"</em></li>";
    			}
	    	}
    	}

    	if(info.arr_certi){
    		for(var icnt=0;icnt < info.arr_certi.split("&&&").length;icnt++){
    			var temp = info.arr_certi.split("&&&")[icnt].split("|||");
    			if(temp.length  > 1){
    				str_ru_certi += "<li><span>자격증</span><span>"+temp[0]+"</span><em>"+temp[1]+"</em></li>";
    			}
	    	}
    	}

    	if(info.ru_my_type){
    		for(var icnt=0;icnt < info.ru_my_type.length;icnt++){
    			str_ru_type +="<p class='tag'>"+info.ru_my_type[icnt]+"</p>";
	    	}
    	}

    	if(info.ru_img1){
    		str_img += "<div class='pic-card'><img src='"+info.ru_img1+"' alt='' style='min-width: auto; min-height: auto;'></div>";
    	}

    	if(info.ru_img2){
    		str_img += "<div class='pic-card'><img src='"+info.ru_img2+"' alt='' style='min-width: auto; min-height: auto;'></div>";
    	}

    	if(info.ru_img3){
    		str_img += "<div class='pic-card'><img src='"+info.ru_img3+"' alt='' style='min-width: auto; min-height: auto;'></div>";
    	}

    	if(info.ru_img4){
    		str_img += "<div class='pic-card'><img src='"+info.ru_img4+"' alt='' style='min-width: auto; min-height: auto;'></div>";
    	}

    	if(info.ru_img5){
    		str_img += "<div class='pic-card'><img src='"+info.ru_img5+"' alt='' style='min-width: auto; min-height: auto;'></div>";
    	}

    	if(info.ru_price_type.indexOf("월급") > -1 && info.ru_month_price !="0"){
    		 str_month_price = '<tr><td class="label">급여</td><td class="value">'+info.ru_month_price.format()+'원</td></tr>';
    	}
      if(info.ru_addr==''){
				info.ru_addr = '무관';
			}
      if(str_ru_week==''){
				str_ru_week = '무관';
			}
      if(!info.ru_work_dt){
				info.ru_work_dt = '근무일무관';
				info.ru_work_dt_yoil = '-';
			}

        openPopup('modal', {
            content: '<div class="regist-info">'+
                    '<h5 class="name">'+info.mm_name+'</h5>'+
                    '<table>'+
                        '<caption><span class="sr-text">회원가입정보</span></caption>'+
                        '<colgroup><col><col></colgroup>'+
                        '<tbody>'+
                            '<tr>'+
                                '<td class="label">업무</td>'+
                                '<td class="value">'+
                                    str_ru_work_img +
                                    '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                '<td class="label">지역</td>'+
                                '<td class="value">'+info.ru_addr+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">급여형태</td>'+
                                '<td class="value">'+(info.ru_price_type.length > 2 ? "협의가능":info.ru_price_type)+'</td>'+
                            '</tr>'+ str_month_price +
                            '<tr>'+
                                '<td class="label">근무형태</td>'+
                                '<td class="value">'+(info.ru_work_type.length > 2 ? "협의가능":info.ru_work_type)+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무요일</td>'+
                                '<td class="value">'+
                                    '<ul class="working-day">'+
                                        str_ru_week +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무시작일</td>'+
                                '<td class="value">'+info.ru_work_dt + '('+info.ru_work_dt_yoil+')' + '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">최종학력</td>'+
                                '<td class="value">'+info.ru_edu+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">경력사항</td>'+
                                '<td class="value">'+
                                    '<ul class="period-list">'+
                                       str_ru_career +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">자격증</td>'+
                                '<td class="value">'+
                                    '<ul class="period-list">'+
                                        str_ru_certi +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="label">자기소개</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value introduce"><div style="word-wrap: break-word; white-space: normal; width: 410px;">'+info.ru_desc+'</div></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value">'+
                                    '<div class="hashtag">'+
                                        str_ru_type +
                                    '</div>'+
                                '</td>'+
                            '</tr>'+
                        '</tbody>'+
                    '</table>'+
                '</div>',
            firstButton: '정보수정',
            secondButton: ($("[name=ru_idx]").val()!="0" ? '수정완료' : '등록완료'),
            firstCallback: function () {

            },
            secondCallback: function () {
				var params = {};
				params["ru_idx"] = $("[name=ru_idx]").val();
	            comAjax("/mypage_proc/resume_complete",params,function(ret){
	                if(ret.code=="00"){
	                    location.href="/mypage/resume_list";
                  }else if(ret.code=="91"){
                    //24.09.01 실명인증여부
                    commonAlert(ret.msg,function(){ fManCheck(); });
                    return;
	                }else{
	                    commonAlert(ret.msg,function(){});
	                    return;
	                }
	            });
            }
        });
    }

    function fFold(obj, req=-1){

			var act = 'close';
			if(req==1){
				act = 'open';
			}else if(req==0){
				act = 'close';
			}else{
					if($(obj).parent().css('height').replace('px','') < 30){
						act = 'open';
					}else{
						act = 'close';
					}
			}
			$(obj).parent().css('overflow','hidden');
    	if(act=='open'){ //$(obj).parent().css('height').replace('px','') < 30
    		//show
        $(obj).parent().show();
				//$(obj).parent().animate({ padding: '26px 0px'});
				//$(obj).parent().css('height', 'auto');
				//$(obj).find('img').addClass('dwside');
				//$(obj).find('img').removeClass('upside');
				//$(obj).parent().find('.form-label').css('margin-top','0px');
				//$(obj).parent().find('.form-group').css('margin-top','0px');
				//$(obj).parent().find('.value').css('margin-top','0px');
    	}else{
    		//hide
        $(obj).parent().hide();
				//$(obj).parent().css('height','28px');
				//$(obj).parent().animate({ padding: '10px 0px'});
				//$(obj).find('img').addClass('upside');
				//$(obj).find('img').removeClass('dwside');
				//$(obj).parent().find('.form-label').css('margin-top','10px');
				//$(obj).parent().find('.form-group').css('margin-top','20px');
				//$(obj).parent().find('.value').css('margin-top','20px');
    	}
    }

    function fFoldAll(gbn){
      if(gbn=='Y'){
        //간편
        fFold($('#btn1'),1);
        fFold($('#btn2'),1);
        fFold($('#btn3'),1);
        fFold($('#btn4'),1);
        fFold($('#btn5'),1);
        fFold($('#btn6'),0);
        fFold($('#btn7'),0);
        fFold($('#btn8'),0);
        fFold($('#btn9'),0);
        fFold($('#btn10'),0);
        fFold($('#btn11'),0);
        fFold($('#btn12'),0);
        fFold($('#btn13'),0);
        fFold($('#btn14'),1);
      }else{
        //상세
        fFold($('#btn1'),1);
        fFold($('#btn2'),1);
        fFold($('#btn3'),1);
        fFold($('#btn4'),1);
        fFold($('#btn5'),1);
        fFold($('#btn6'),1);
        fFold($('#btn7'),1);
        fFold($('#btn8'),1);
        fFold($('#btn9'),1);
        fFold($('#btn10'),1);
        fFold($('#btn11'),1);
        fFold($('#btn12'),1);
        fFold($('#btn13'),1);
        fFold($('#btn14'),1);
      }
    }

</script>

<?php include_once (get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>
