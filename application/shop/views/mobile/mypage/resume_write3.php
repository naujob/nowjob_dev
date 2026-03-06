<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이력서 <?=$info["ru_idx"]!="0" ? "수정":"등록"?></h1>
        <ol class="progress-steps">
            <li>1</li>
            <li>2</li>
            <li title="헌재위치" class="active">3</li>
            <li>4</li>
        </ol>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="section addition-info-form">
            <div class="container">
                 <form id="frm" onsubmit="return false">
                   <input type="hidden" name="arr_career" value="" />

                    <div class="form-row">
                        <p class="form-label">간단경력 <em>간단경력은 최대 3개의 경력사항을 기입가능합니다.<br> 업체명 최대 13자</em></p>

                        <!-- 20220929 수정 -->
                        <!-- 목록 -->
                        <ol class="added-list">
                       	<?php $arr_count = 0; ?>
                       	<?php if($info["arr_career"]!="") :
                       		$arr_career = explode("&&&",$info["arr_career"]);
							$arr_count = count($arr_career);
							foreach($arr_career as $row):
								$arr_temp = explode("|||",$row);
						?>
							<li>
                               <div class="form-group">
                                   <div class="col sm">
                                       <label class="form-selectbox">
                                           <span class="sr-text">업종선택</span>
                                           <select name="category[]">
                                           		<option value="">업종</option>
												<?php foreach($ru_wrok_list as $row) : ?>
                                               		<option value="<?=$row["cd_val"]?>" <?=equal_return($row["cd_val"],$arr_temp[0],"select")?>><?=$row["cd_val"]?></option>
                                               	<?php endforeach;?>
                                           </select>
                                           <span class="liner"></span>
                                       </label>
                                   </div>
                                   <div class="col">
                                       <div class="meta">
                                           <label>
                                               <span class="sr-text">업체명 입력</span>
                                               <input type="text" name="company[]" maxlength="13" placeholder="업체명" value="<?=$arr_temp[1]?>" autocomplete="off" class="form-text">
                                           </label>
                                           <label>
                                               <span class="sr-text">근무기간 입력</span>
                                               <input type="text" name="period[]" placeholder="근무기간" autocomplete="off" value="<?=$arr_temp[2]?>" class="form-text num js-datepicker">
                                           </label>
                                       </div>
                                   </div>
                               </div>
                               <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
                           </li>
						<?php endforeach; ?>
						<?php endif; ?>
                       </ol>
                       <button type="button" class="btn-add-item js-add-career" style="<?=($arr_count>=3) ? "display:none;":""?>">등록</button>

                       <!-- 입력폼 템플릿 -->
                       <template id="careerTemp">
                           <li>
                               <div class="form-group">
                                   <div class="col sm">
                                       <label class="form-selectbox">
                                           <span class="sr-text">업종선택</span>
                                           <select name="category[]">
                                                <option value="">업종</option>
												<?php foreach($ru_wrok_list as $row) : ?>
                                               		<option value="<?=$row["cd_val"]?>"><?=$row["cd_val"]?></option>
                                               	<?php endforeach;?>
                                           </select>
                                           <span class="liner"></span>
                                       </label>
                                   </div>
                                   <div class="col">
                                       <div class="meta">
                                           <label>
                                               <span class="sr-text">업체명 입력</span>
                                               <input type="text" name="company[]" maxlength="13" placeholder="업체명" autocomplete="off" class="form-text">
                                           </label>
                                           <label>
                                               <span class="sr-text">근무기간 입력</span>
                                               <input type="text" name="period[]" placeholder="근무기간" autocomplete="off" class="form-text num js-datepicker">
                                           </label>
                                       </div>
                                   </div>
                               </div>
                               <button type="button" class="btn-clear js-del-item"><span class="sr-text">삭제</span></button>
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
                                   //diffYear = diffYear > 0 ? diffYear + '년': '';
                                   //diffMonth = diffMonth > 0 ? diffMonth + '개월' : '';

                                   //return '(' + diffYear + diffMonth + ')';
                                   return '';
                               }

                               function initDatepicker (el) {
                                   if (typeof flatpickr == 'undefined') return;

                                   //달력 생성
                                   flatpickr(el, {
                                       mode: 'range',
                                       locale: 'ko',
                                       altInput: true,
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
                                               document.querySelector('.flatpickr-next-month').click();
                                           }
                                       },
                                       onOpen: function (selectedDates, dateStr, instance) {
                                           if (selectedDates.length > 1) {
                                               document.querySelector('.flatpickr-prev-month').click();
                                           }
                                       },
                                       onChange: function (selectedDates, dateStr, instance) {
                                           instance.calendarContainer.querySelectorAll('.flatpickr-monthSelect-month').forEach(function (month) {
                                               month.classList.remove('startRange', 'inRange', 'endRange')
                                           });

                                           document.querySelector('.form-control.active').value = dateStr + totalRange(dateStr);
                                       }
                                   });
                               }

                               window.addEventListener('DOMContentLoaded', function () {
                                   //경력 목록 추가
                                   document.querySelector('.js-add-career').addEventListener('click', function (e) {
                                       e.preventDefault();
                                       var btn = this,
                                           max = 3,
                                           temp = document.getElementById('careerTemp').content.cloneNode(true),
                                           list = btn.parentNode.querySelector('.added-list'),
                                           lastItem = null;

                                       if (list.children.length <= 3) {
                                           list.appendChild(temp);
                                           lastItem = list.children[list.children.length - 1];

                                           initDatepicker(lastItem.querySelector('.js-datepicker'));
                                           initSelectbox(lastItem.querySelector('.form-selectbox'));
                                           //lastItem.querySelector('.js-del-item').addEventListener('click', function () {
                                           //    if(list.children.length <= 3) {
                                           //        btn.style.display = '';
                                           //    }
                                           //});
                                       }

                                       if (list.children.length >= max) {
                                           btn.style.display = 'none';
                                       }
                                   });

                                   //경력 삭제 수정
                                   $(document).on("click",".js-del-item",function(){
                                   		$('.js-add-career').show();
                                   });

                                   for(var icnt=0;icnt < document.querySelector('.added-list').children.length;icnt++){
                                   		initDatepicker(document.querySelector('.added-list').children[icnt].querySelector('.js-datepicker'));
                                   }
                               });
                           }());
                       </script>
                    </div>

                    <div class="form-row">
                       <p class="form-label">나의강점 <em>나의강점을 선택하세요(최대6개까지)</em></p>
                       <div class="form-toggle-group col-3">
                            <?php foreach($ru_my_type_list as $row) : ?>
                            <label class="form-toggle-btn2 sm tag">
                                <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["ru_my_type"],"check")?>>
                               <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>

                            <!-- 추가 태그 -->
                            <?php foreach($info["ru_my_type"] as $row) : ?>
	                            <?php
	                            	$is_arr = false;
	                            	foreach($ru_my_type_list as $temp){
	                            		if($temp["cd_val"] == $row){
	                            			$is_arr = true;
	                            		}
	                            	}
									if($is_arr==false) :
	                            ?>
	                            <label class="form-toggle-btn2 sm tag">
	                                <input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="<?=$row?>" <?=in_array_return($row,$info["ru_my_type"],"check")?>>
	                               <span class="btn"><?=$row?></span>
	                            </label>
	                            <?php endif; ?>
                            <?php endforeach; ?>
                       </div>
                       <!-- 추가 입력 -->
                        <div class="advantage-form">
                            <div class="inline-group">
                                <label class="col">
                                    <input type="text" name="addAdvantage" placeholder="나의 강점을 입력해주세요" autocomplete="off" class="form-text">
                                </label>
                                <button type="button" class="btn-round btn-tertiary js-add-advantage">추가</button>
                            </div>
                            <!-- 추가목록 -->
                            <div class="form-toggle-group col-3">

                            </div>
                        </div>
                   </div>

                    <!-- 나의강점 양식 -->
                   <template id="advantageTemp">
                       <div class="additional-form">
                           <form action="/">
                               <p class="popup-title">나의강점 작성</p>
                               <div class="fom-container">
                                   <label class="form-row">
                                       <span class="sr-text">나의 강점 작성</span>
                                       <textarea name="advantage" placeholder="나의 강점을입력해주세요" aria-required="true" maxlength="200" required class="form-textarea js-autoheight"></textarea>
                                       <span class="form-limit-label">200자이내</span>
                                   </label>
                               </div>
                           </form>
                       </div>
                   </template>
                   <!-- //나의강점 양식 -->

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

                            //20221121 수정
                            document.querySelector('.js-add-advantage').addEventListener('click', function () {
                                var value = document.querySelector('input[name="addAdvantage"]').value;
                                var label;

                                if(!value) return;

                                label = document.createElement('LABEL');
                                value = value.trim().replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-+<>@\#$%&\\\=\(\'\"]/g,'');

                                label.setAttribute('class', 'form-toggle-btn2 sm tag');
                                label.innerHTML = '<input type="checkbox" name="ru_my_type[]" data-select-limit="6" value="'+ value +'"> <span class="btn">'+ value +'</span>';

                                document.querySelector('.advantage-form .form-toggle-group').appendChild(label);

                                label.querySelector('input').addEventListener('change', function () {
                                    var childItems = document.querySelectorAll('[name="'+ this.getAttribute('name') +'"]:checked');

                                    if (this.checked == childItems.length > this.getAttribute('data-select-limit')) {
                                        this.checked = false;
                                    }
                                });
                            });
                        }());
                    </script>

                   <div class="sticky-bottom">
                       <button type="button" class="btn btn-secondary" id="btnPrev">이전</button>
                       <button type="button" class="btn btn-primary" id="btnStep">다음</button>
                   </div>
                </form>
            </div>
        </div>
    </div><!-- //page-content -->
</div>

<input type="hidden" name="ru_idx"  value="<?=$info["ru_idx"]?>" />
<script>
	$(document).ready(function(){
        $("#btnPrev").click(function(){
			history.go(-1);
		});

		$("#btnStep").click(function(){
            var arr_career = "";
            for(var icnt=0;icnt < $(".added-list").find("li").length;icnt++){
                if($("[name='category[]']").eq(icnt).val()==""){
                    commonAlert("업종을 선택해주세요.",function(){
                        $("[name='category[]']").eq(icnt).focus();
                    });
                    return;
                }

                if($("[name='company[]']").eq(icnt).val()==""){
                    commonAlert("업체명을 입력해주세요.",function(){
                        $("[name='company[]']").eq(icnt).focus();
                    });
                    return;
                }

                if($("[name='period[]']").eq(icnt).val()==""){
                    commonAlert("근무기간을 선택해주세요.",function(){
                        $("[name='period[]']").eq(icnt).focus();
                    });
                    return;
                }

                if(arr_career==""){
                    arr_career = $("[name='category[]']").eq(icnt).val() + "|||" + $("[name='company[]']").eq(icnt).val() + "|||" + $("[name='period[]']").eq(icnt).val();
                }else{
                    arr_career = arr_career + "&&&" + $("[name='category[]']").eq(icnt).val() + "|||" + $("[name='company[]']").eq(icnt).val() + "|||" + $("[name='period[]']").eq(icnt).val();
                }
            }
            $("[name=arr_career]").val(arr_career);

            var params = $("#frm").serialize();
            comAjax("/mypage_proc/resume_write3",params,function(ret){
                if(ret.code=="00"){
                    if($("[name=ru_idx]").val()!="0"){
                		//_replace("/mypage/resume_write4/" + $("[name=ru_idx]").val());
                    location.href="/mypage/resume_write1/" + $("[name=ru_idx]").val();
                	}else{
                		//_replace("/mypage/resume_write4");
                    location.href="/mypage/resume_write1";
                	}
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
		});
	});
</script>
