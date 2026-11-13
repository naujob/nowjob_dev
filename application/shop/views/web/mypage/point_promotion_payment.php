<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text"><?= t('이전으로') ?></span></a>
        <h1 class="title"><?= t('포인트 충전 · 광고 구매 / 관리') ?></h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <?php $tab_on = "tab4";?>
        <?php include_once (get_base_url() . '/application/shop/views/mobile/mypage/top_line.php'); ?>
        <div class="tab-panel">
            <!-- 20220929 수정 -->
            <div class="promo-view">
                <div class="container">
                    <?php if($type=="M002") : ?>
	                    <div class="promo-content">
	                        <h2 class="primary-title"><?= t('점프업') ?></h3>
	                        <p class="required-point"><?= t('차감포인트 :') ?> <em>500</em>P</p>
	                        <p class="desc"><?= t('등록하신 이력서를 상위목록으로 노출시킬 수 있습니다.') ?><br> <?= t('점프업은 광고구매하기 기준으로 업데이트 됩니다.') ?></p>
	                        <div class="sticky-bottom view-footer">
	                            <button type="button" id="btnNext" aria-haspopup="dialog" class="btn btn-primary"><?= t('광고구매하기') ?></button>
	                        </div>
	                    </div>
                    <?php elseif($type=="M001") : ?>
	                    <div class="promo-content">
	                        <h2 class="primary-title"><?= t('추천인재등록(7일권)') ?></h3>
	                        <p class="required-point"><?= t('차감포인트 :') ?> <em>500</em>P</p>
	                        <p class="desc"><?= t('포인트는 500P가 차감되며,') ?><br> <?= t('지정한 등록지역, 공고, 등록시작에 7일간 추천공고로 표시됩니다.') ?></p>
	                        <div class="form-wrap">
                                <div class="form-row">
                                    <!-- 20221107 수정 -->
                                    <div class="inline-group">
                                        <div class="label">
                                            <p class="form-label"><?= t('등록지역') ?></p>
                                        </div>
                                        <div class="col">
                                            <label class="form-selectbox">
                                                <span class="sr-text"><?= t('등록지역 선택') ?></span>
                                                <select name="pm_addr" aria-required="true">
                                                    <option value="" selected><?= t('지역선택') ?></opion>
                                                    <option value="인천 연수구" >인천 연수구</option>
                                                </select>
                                                <span class="liner"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!-- 20221107 수정 -->
                                    <div class="inline-group">
                                        <div class="label">
                                            <p class="form-label"><?= t('등록시작일') ?></p>
                                        </div>
                                        <div class="col">
                                            <label class="form-selectbox">
                                                <span class="sr-text"><?= t('등록시작일 선택') ?></span>
                                                <input type="text" name="pm_sdate" value="" aria-required="true" required readonly class="form-text js-datepicker">
                                            </label>
                                        </div>
                                    </div>
            
                                    <script>
                                        //달력생성
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
                                                    altInput: true,
                                                    position: 'auto right',
                                                    nextArrow: '<span class="sr-text"><?= t('다음') ?></span>',
                                                    prevArrow: '<span class="sr-text"><?= t('이전') ?></span>',
                                                    disableMobile: true,
                                                    defaultDate: new Date(),
                                                    onMonthChange: function (selectedDates, dateStr, instance) {
                                                        chgMonth(instance);
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
                                <div class="sticky-bottom view-footer">
                                    <button type="button" id="btnNext" aria-haspopup="dialog" class="btn btn-primary"><?= t('광고구매하기') ?></button>
                                </div>
	                        </div>
	                    </div>
	                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="code" value="<?=$promotion_info["cd_key"]?>" />
<input type="hidden" name="price" value="<?=$promotion_info["cd_val3"]?>" />
<input type="hidden" name="mm_point" value="<?=$mem_info["mm_point"]?>" />

<script>
$(document).ready(function(){
	$("#btnNext").click(function(){
		var code = $("[name=code]").val();
		var point = $("[name=price]").val();
		var sdate = "";
		var edate = "";
		var mm_point = $("[name=mm_point]").val();
		var addr = $("[name=pm_addr]").val();
		
		if(code=="M001"){
			sdate = $("[name=pm_sdate]").val();
			edate = getDateAdd(sdate,6,'d');	
		}else if(code=="M002"){
			sdate = getToday("y-m-d");
			edate = getDateAdd(sdate,1,'d');	
		}
		
		var confirm_txt = "";
		if(parseInt(point) > parseInt(mm_point)){
			commonAlert(t("보유하신 포인트가 부족합니다. 보유포인트 : ") + mm_point.format(),function(){});
			return;
		}
		
        if(code=="M001"){
        	if($("[name=pm_addr]").val()==""){
        		commonAlert(t("등록지역을 선택해주세요."),function(){
        			$("[name=pm_addr]").focus();
        		});
        		return;
        	}

        	if($("[name=pm_sdate]").val()==""){
        		commonAlert(t("등록 시작일을 선택해주세요."),function(){
        			$("[name=pm_sdate]").focus();
        		});
        		return;
        	}
        	
        	confirm_txt = '<p><span>“'+addr+'”</span> <em>'+t('추천인재')+'</em>로<br> '+t('등록하시겠습니까?')+'</p>'+
            '<ul class="dot-list">'+
            '<li>'+t('등록기간 :')+' <span>'+sdate+' ~ '+edate+t('(7일간)')+'</span></li>'+
            '<li>'+t('차감포인트 :')+' <span>'+point.format()+'P</span></li>'+
            '<li>'+t('등록지역 :')+' <span>'+addr+'</span></li>'+
            '</ul>';
    	}else if(code=="M002"){
    		confirm_txt = '<p><span>'+t('점프업')+'</span><br> '+t('등록하시겠습니까?')+'</p>'+
            '<ul class="dot-list">'+
            '<li>'+t('등록기간 :')+' <span>'+sdate+' ~ '+edate+t('(1일간)')+'</span></li>'+
            '<li>'+t('차감포인트 :')+' <span>'+point.format()+'P</span></li>'+
            '</ul>';
    	}
		 
		openPopup('confirm', {
            content: confirm_txt,
            secondCallback: function () {
                var params = {};
		        params["pm_code"] = $("[name=code]").val();
		        params["pm_sdate"] = $("[name=pm_sdate]").val();
		        params["pm_addr"] = $("[name=pm_addr]").val();
		        
		        comAjax("/mypage_proc/point_promotion",params,function(ret){
		            if(ret.code=="00"){
		                location.href="/mypage/point_promotion_list"
		            }else{
		            	commonAlert(ret.msg,function(){});
		            }
		        });
            }
        });
	});
});
</script>



