<div class="page page-join member-type1" style="display:none;">
   <div class="top-bar">
       <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
       <h1 class="title">개인회원 회원가입</h1>
       <a href="#" aria-describedby="skipTooltip" class="action js-toggle-tooltip">건너뛰기</a>
       <!-- 툴팁내용 -->
       <div role="tooltip" id="skipTooltip" class="tooltip center">
           <strong>건너뛰기를 하셨네요?</strong><br>
           정보를 입력하시면 나에게 맞는 채용을<br> 더 쉽게 찾으실 수 있습니다
       </div>
   </div><!-- //top-bar -->

   <script>
       /* 건너뛰기 이동 전 툴팁표시 */
       document.querySelector('.js-toggle-tooltip').addEventListener('click', function (e) {
           if (document.querySelector('#skipTooltip').classList.contains('on')) {
               location.href = this.getAttribute('href');
           }
       });
   </script>

   <div class="page-content">
       <div class="container">

           <ol class="progress-steps">
               <li>1</li>
               <li>2</li>
               <li title="헌재위치" class="active">3</li>
               <li>4</li>
           </ol>

           <div class="addition-info-form">
                <form id="frm" onsubmit="return false">
                   <input type="hidden" name="proc_type" value="join_mem_step4_3" />
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
												<?php foreach($ma_wrok_list as $row) : ?>
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
												<?php foreach($ma_wrok_list as $row) : ?>
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
                                   diffYear = diffYear > 0 ? diffYear + '년': '';
                                   diffMonth = diffMonth > 0 ? diffMonth + '개월' : '';

                                   return '(' + diffYear + diffMonth + ')';
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
                            <?php foreach($ma_my_type_list as $row) : ?>
                            <label class="form-toggle-btn2 sm tag">
                                <input type="checkbox" name="ma_my_type[]" data-select-limit="6" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["ma_my_type"],"check")?>>
                               <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>

                            <!-- 추가 태그 -->
                            <?php foreach($info["ma_my_type"] as $row) : ?>
	                            <?php
	                            	$is_arr = false;
	                            	foreach($ma_my_type_list as $temp){
	                            		if($temp["cd_val"] == $row){
	                            			$is_arr = true;
	                            		}
	                            	}
									if($is_arr==false) :
	                            ?>
	                            <label class="form-toggle-btn2 sm tag">
	                                <input type="checkbox" name="ma_my_type[]" data-select-limit="6" value="<?=$row?>" <?=in_array_return($row,$info["ma_my_type"],"check")?>>
	                               <span class="btn"><?=$row?></span>
	                            </label>
	                            <?php endif; ?>
                            <?php endforeach; ?>
                           	<button type="button" aria-haspopup="dialog" class="btn-add-more js-add-hashtag"><span class="sr-text">추가작성</span></button>
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

                           //나의 강점 추가 팝업 표시
                           window.addEventListener('DOMContentLoaded', function () {
                               document.querySelector('.js-add-hashtag').addEventListener('click', function (e) {
                                   var content = document.querySelector('#advantageTemp').innerHTML;

                                   this.classList.add('is-trigger');

                                   openPopup('modal', {
                                       content: content,
                                       firstButton: '작성',
                                       firstCallback: function () {
                                           var btn = document.querySelector('.btn-add-more');
                                           var values = document.querySelector('.additional-form .form-textarea').value.trim();
                                           var docfrag =  document.createDocumentFragment();

                                           if (!values) return;

                                           values.split(',').forEach(function (value) {
                                               var label = document.createElement('LABEL');
                                               var txt = value.trim().replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-+<>@\#$%&\\\=\(\'\"]/g,'');

                                               label.setAttribute('class', 'form-toggle-btn2 sm tag');
                                               label.innerHTML = '<input type="checkbox" name="ma_my_type[]" data-select-limit="6" value="'+ txt +'"> <span class="btn">'+ txt +'</span>';

                                               docfrag.appendChild(label);

                                               label.querySelector('input').addEventListener('change', function () {
                                                   var childItems = document.querySelectorAll('[name="'+ this.getAttribute('name') +'"]:checked');

                                                   if (this.checked == childItems.length > this.getAttribute('data-select-limit')) {
                                                       this.checked = false;
                                                   }
                                               });
                                           });

                                           btn.parentNode.insertBefore(docfrag, btn);
                                       }
                                   });
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
<script>
    /* 건너뛰기 이동 전 툴팁표시 */
    document.querySelector('.js-toggle-tooltip').addEventListener('click', function (e) {
        //if (document.querySelector('#skipTooltip').classList.contains('on')) {
        //    location.href = this.getAttribute('href');
        //}
    });
</script>
<script>
function fPassClick(){
  setTimeout(function(){
    var params = {};
    params["ma_type"] = "MEM";
    params["proc_type"] = "PART";
    params["uuid"] = "<?=$uuid?>";
          comAjax("/member_proc/join_complete_proc",params,function(ret){
              if(ret.code=="00"){
                  location.href="/member/join_mem_finish";
              }else{
                  commonAlert(ret.msg,function(){});
                  return;
              }
          });
        },5000);

}

	$(document).ready(function(){
    fPassClick();
		//건너띄기
		$(".js-toggle-tooltip").click(function(){
      fPassClick();
		});

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
            comAjax("/member_proc/join_mem_step4_3",params,function(ret){
                if(ret.code=="00"){
                    location.href="/member/join_mem_step4_4";
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
		});
	});
</script>
