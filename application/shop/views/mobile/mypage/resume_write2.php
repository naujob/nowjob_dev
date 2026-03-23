<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이력서 <?=$info["ru_idx"]!="0" ? "수정":"등록"?></h1>
        <ol class="progress-steps">
            <li>1</li>
            <li title="헌재위치" class="active">2</li>
            <li>3</li>
            <li>4</li>
        </ol>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="section addition-info-form">
            <div class="container">
                <form id="frm" onsubmit="return false">
                    <div class="form-row">
                        <p class="form-label">
                    		근무요일 <em>다중선택가능</em>
                            <span class="option">
                                <label class="form-checkbox">
                                    <input type="checkbox" name="ru_week_yn" value="Y" class="sr-text" <?=$info["ru_week_yn"]=="Y" ? "checked":""?>>
                                    <span class="label">요일무관</span>
                                </label>
                            </span>
                        </p>
                        <div class="form-toggle-group align-justify">
                        	<?php foreach($ru_week_list as $row) : ?>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ru_week[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["ru_week"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">근무시작일</p>
                        <div class="calendar">
                            <div class="js-datepicker"></div>
                            <input type="hidden" name="ru_work_dt" aria-required="true" required class="js-date-value" value="<?=$info["ru_work_dt"]?>">
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
                                    flatpickr('.js-datepicker', {
                                        inline: true,
                                        locale: 'ko',
                                        dateFormat: 'Y-m-d',
                                        defaultDate : '<?=$info["ru_work_dt"]?>',
                                        ariaDateFormat: 'Y-m-d',
                                        nextArrow: '<span class="sr-text">다음</span>',
                                        prevArrow: '<span class="sr-text">이전</span>',
                                        disableMobile: true,
                                        onMonthChange: function (selectedDates, dateStr, instance) {
                                            chgMonth(this);
                                        },
                                        onChange: function (selectedDates, dateStr, instance) {
                                            document.querySelector('.js-date-value').value = dateStr;
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
                        <p class="form-label">최종학력</p>
                        <div class="form-toggle-group col-4">
                            <?php foreach($ru_edu_list as $row) : ?>
                            <label class="form-toggle-btn2">
                                <input type="radio" name="ru_edu" value="<?=$row["cd_val"]?>"  <?=equal_return($row["cd_val"],$info["ru_edu"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>

                    <script>
                        //선택목록 제한
                        (function () {
                            document.querySelectorAll('input[data-select-limit]').forEach(function (item) {
                                item.addEventListener('change', function () {
                                    var childItems = document.querySelectorAll('[name="'+ this.getAttribute('name') +'"]:checked');

                                    if (this.checked == childItems.length > this.getAttribute('data-select-limit')) {
                                        this.checked = false;
                                    }
                                });
                            });
                        }());
                    </script>
                    <div class="sticky-bottom view-footer">
                        <button id="btnPrev" type="button" class="btn btn-secondary">이전</button>
                        <button id="btnStep" type="button" class="btn btn-primary">다음</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- //page-content -->
</div>

<input type="hidden" name="ru_idx"  value="<?=$info["ru_idx"]?>" />
<script>
	$(document).ready(function(){
		$("#btnStep").click(function(){
            if($("[name='ru_week_yn']").is(":checked")==false && $("[name='ru_week[]']").is(":checked")==false){
                commonAlert("근무가능 요일을 하나이상 선택해주세요.",function(){});
                return;
            }

            if($("[name='ru_work_dt']").val()==""){
                commonAlert("근무가능일을 선택해주세요.",function(){});
                return;
            }

            if($("[name='ru_edu']").is(":checked")==false){
                commonAlert("최종학력을 선택해주세요.",function(){});
                return;
            }

            var params = $("#frm").serialize();
            comAjax("/mypage_proc/resume_write2",params,function(ret){
                if(ret.code=="00"){
                    if($("[name=ru_idx]").val()!="0"){
                		//_replace("/mypage/resume_write3/" + $("[name=ru_idx]").val());
                    location.href="/mypage/resume_write3/" + $("[name=ru_idx]").val();
                	}else{
                		//_replace("/mypage/resume_write3");
                    location.href="/mypage/resume_write3";
                	}
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
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

		$("#btnPrev").click(function(){
			history.go(-1);
		});
	});
</script>
