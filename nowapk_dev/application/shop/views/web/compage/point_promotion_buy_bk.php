<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab1";?>
            <?php $tab_on2 = "menu4";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="purchage-promo">
                    <h2 class="sr-text">광고구매</h3>
                    <ul class="promo-list">
                        <li>
                            <div class="promo-card">
                                <div class="card-body">
                                    <p class="title"><strong>추천공고등록(7일권)</strong></p>
                                    <p class="required-point">차감포인트 : <em><?=$point1["cd_val3"]?></em>P</p>
                                </div>
                                <div class="card-footer">
                                    <a href="#promo1" aria-haspopup="dialog" class="btn-round btn-tertiary js-view-promo">구매</a>
                                </div>

                                <!-- 구매팝업템플릿 -->
                                <template id="promo1Temp">
                                    <div class="promo-view">
                                        <div class="promo-content">
                                            <h3 class="secondary-title">추천공고등록(7일권)</h3>
                                            <p class="required-point">차감포인트 : <em><?=$point1["cd_val3"]?></em>P</p>
                                            <p class="desc">포인트는 500P가 차감되며,<br> 지정한 등록지역, 공고, 등록시작에 7일간 추천공고로 표시됩니다.</p>
                                            <div class="form-wrap">
                                                <form action="/">
                                                    <div class="form-row">
                                                        <p class="form-label">등록지역</p>
                                                        <label>
                                                            <span class="sr-text">등록지역 선택</span>
                                                            <select name="pm_addr1" class="form-text js-find-loc" style="text-align:right;">
                                                            	<option value="">지역선택</option>
                                                            	<?php foreach($addr_list as $ad_addr) : ?>
			                                                    <option value="<?=$ad_addr?>" ><?=$ad_addr?></option>
			                                                    <?php endforeach; ?>
                                                            </select>
                                                        </label>
                                                    </div>
                                                    <div class="form-row">
                                                        <p class="form-label">등록시작일</p>
                                                        <label>
                                                            <span class="sr-text">등록시작일 선택</span>
                                                            <input type="text" name="pm_sdate1" value="" aria-required="true" required readonly class="form-text js-datepicker">
                                                        </label>

                                                    </div>
                                                    <div class="form-row">
                                                   		<p class="form-label">공고선택</p>
                                                     	<label>
			                                                <!-- 처리 해야함 : 공고 연동 -->
			                                                <select name="rc_idx1"  class="form-text js-find-loc" style="text-align:right;">
			                                                	<option value="" selected>공고선택</opion>
			                                                    <?php foreach($recruit_list as $row) : ?>
			                                                    <option value="<?=$row["rc_idx"]?>" style="display:none;" data-ad_addr="<?=$row["ad_addr"]?>" ><?=$row["rc_name"]?></option>
			                                                    <?php endforeach; ?>
			                                                </select>
		                                               </label>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="promo-footer">
                                            <button type="button" data-code="C001" data-point="<?=$point1["cd_val3"]?>" data-num="1"  class="btnBuy btn btn-primary">광고구매하기</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </li>
                        <li>
                            <div class="promo-card">
                                <div class="card-body">
                                    <p class="title"><strong>점프업기능</strong></p>
                                    <p>*공고를 목록 상단에 노출시킵니다.</p>
                                    <p class="required-point">차감포인트 : <em><?=$point3["cd_val3"]?></em>P</p>
                                </div>
                                <div class="card-footer">
                                    <a href="#promo2" aria-haspopup="dialog" class="btn-round btn-tertiary js-view-promo">구매</a>
                                </div>

                                <!-- 구매팝업템플릿 -->
                                <template id="promo2Temp">
                                    <div class="promo-view">
                                        <div class="promo-content">
                                            <h3 class="secondary-title">점프업기능</h3>
                                            <p class="required-point">차감포인트 : <em><?=$point3["cd_val3"]?></em>P</p>
                                            <p class="desc">등록하신 이력서를 상위목록으로 노출시킬 수 있습니다.<br>점프업은 광고구매하기 기준으로 업데이트 됩니다.</p>
                                            <div class="form-wrap">
                                                <form action="/">
                                                    <div class="form-row">
                                                        <p class="form-label">등록지역</p>
                                                        <label>
                                                            <span class="sr-text">등록지역 선택</span>
                                                            <select name="pm_addr2" class="form-text js-find-loc" style="text-align:right;">
                                                            	<option value="">지역선택</option>
                                                            	<?php foreach($addr_list as $ad_addr) : ?>
			                                                    <option value="<?=$ad_addr?>" ><?=$ad_addr?></option>
			                                                    <?php endforeach; ?>
                                                            </select>
                                                        </label>
                                                    </div>
                                                    <div class="form-row">
                                                        <p class="form-label">등록시작일</p>
                                                        <label>
                                                            <span class="sr-text">등록시작일 선택</span>
                                                            <input type="text" name="pm_sdate2" value="" aria-required="true" required readonly class="form-text js-datepicker">
                                                        </label>
                                                    </div>
                                                  	<div class="form-row">
                                                   		<p class="form-label">공고선택</p>
                                                     	<label>
			                                                <!-- 처리 해야함 : 공고 연동 -->
			                                                <select name="rc_idx2"  class="form-text js-find-loc" style="text-align:right;">
			                                                	<option value="" selected>공고선택</opion>
			                                                    <?php foreach($recruit_list as $row) : ?>
			                                                    <option value="<?=$row["rc_idx"]?>" style="display:none;" data-ad_addr="<?=$row["ad_addr"]?>" ><?=$row["rc_name"]?></option>
			                                                    <?php endforeach; ?>
			                                                </select>
		                                               </label>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="promo-footer">
                                            <button type="button" data-code="C003" data-point="<?=$point3["cd_val3"]?>" data-num="2"  class="btnBuy btn btn-primary">광고구매하기</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </li>
                        <li>
						    <div class="promo-card">
						        <div class="card-body">
						            <p class="title"><strong>지금뜨는 업체(7일권)</strong></p>
						            <p class="required-point">차감포인트 : <em><?=$point2["cd_val3"]?></em>P</p>
						        </div>
						        <div class="card-footer">
						            <a href="#promo3" aria-haspopup="dialog" class="btn-round btn-tertiary js-view-promo">구매</a>
						        </div>

						        <!-- 구매팝업템플릿 -->
						        <template id="promo3Temp">
						            <div class="promo-view">
						                <div class="promo-content">
						                    <h3 class="secondary-title">지금뜨는 업체</h3>
						                    <p class="required-point">차감포인트 : <em><?=$point2["cd_val3"]?></em>P</p>
						                    <p class="desc">메인에 지금뜨는 업체에 등록이 가능합니다</p>
						                    <div class="form-wrap">
						                        <form action="/">
						                            <div class="form-row">
						                                <p class="form-label">등록시작일</p>
						                                <label>
						                                    <span class="sr-text">등록시작일 선택</span>
						                                    <input type="text" name="pm_sdate3" value="" aria-required="true" required readonly class="form-text js-datepicker">
						                                </label>
						                            </div>
						                        </form>
						                    </div>
						                </div>

						                <div class="promo-footer">
						                    <button type="button" aria-haspopup="dialog" data-point="<?=$point2["cd_val3"]?>" data-code="C002" data-num="3" data-point="<?=$point2["cd_val2"]?>" class="btnBuy btn btn-primary">광고구매하기</button>
						                </div>
						            </div>
						        </template>
						    </div>
						 </li>
                    </ul>
                    <p class="help-msg">메인배너 광고건은 나우잡 고객센터 031-255-0119에 문의바랍니다. (별도협의필요)</p>

                    <script>
                        //구매 내용 팝업
                        (function () {

                            //달력 제어
                            function chgMonth (instance) {
                                var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                                    month = Number(instance.currentMonth) + 1,
                                    year = instance.currentYear;
                                $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
                            }
                            function initDatepicker () {
                                if (typeof flatpickr == 'undefined') return false;

                                flatpickr('.js-datepicker', {
                                    locale: 'ko',
                                    dateFormat: 'Y-m-d',
                                    ariaDateFormat: 'Y-m-d',
                                    altFormat: 'Y.m.d',
                                    altInput: true,
                                    position: 'auto right',
                                    nextArrow: '<span class="sr-text">다음</span>',
                                    prevArrow: '<span class="sr-text">이전</span>',
                                    disableMobile: true,
                                    position: 'above center',
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
                            }

                            //프로모 구매 안내 팝업
                            window.addEventListener('DOMContentLoaded', function (e) {
                                var viewBtns = document.querySelectorAll('.js-view-promo');

                                if (!viewBtns) return;

                                viewBtns.forEach(function (btn) {

                                    btn.addEventListener('click', function (e) {
                                        e.preventDefault();

                                        var id = e.target.getAttribute('href');
                                        var content = document.querySelector(id + 'Temp') ? document.querySelector(id + 'Temp').innerHTML : '';

                                        if (!id) return;

                                        openPopup('modal', {
                                            id: id + 'Dialog',
                                            title:'광고구매',
                                            content: content,
                                        });

                                        //달력 생성
                                        if (content.indexOf('js-datepicker') > -1) {
                                            initDatepicker();
                                        }

                                        //커스텀 목록 박스 생성
                                        if (content.indexOf('form-selectbox') > -1) {
                                            initSelectbox(document.querySelector('.layer-popup.in .form-selectbox'));
                                        }

                                        //팝업내 추가 스크립트 생성
                                        if (content.indexOf('script') > -1) {
                                            var currentEl = document.querySelector('.layer-popup.in script');
                                            var newEl = document.createElement('SCRIPT');

                                            newEl.innerHTML = currentEl.innerHTML;
                                            currentEl.remove();
                                            document.querySelector('.layer-popup.in').appendChild(newEl);
                                        }
                                    });
                                });
                            });
                        }());
                    </script>
                </div>

            </div>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->


<input type="hidden" name="mm_point" value="<?=$mem_info["mm_point"]?>" />

<script>
$(document).ready(function(){
	//공고 선택
	$(document).on("change","[name=pm_addr1]",function(){
        if($(this).val()==""){
            $("[name=rc_idx1] option").not(":eq(0)").hide();
            $("[name=rc_idx1] option").eq(0).prop("selected",true);
            for(var icnt=0;icnt < $("[name=rc_idx1] option").length;icnt++){
                if($("[name=rc_idx1] option").eq(icnt).val()==""){
                    $("[name=rc_idx1] option").eq(icnt).show();
                }
            }
        }else{
            $("[name=rc_idx1] option").not(":eq(0)").hide();
            $("[name=rc_idx1] option").eq(0).prop("selected",true);
            for(var icnt=0;icnt < $("[name=rc_idx1] option").length;icnt++){
                if($(this).val()==$("[name=rc_idx1] option").eq(icnt).attr("data-ad_addr")){
                    $("[name=rc_idx1] option").eq(icnt).show();
                }
            }
        }
    });

    //공고 선택
    $(document).on("change","[name=pm_addr2]",function(){
        if($(this).val()==""){
            $("[name=rc_idx2] option").not(":eq(0)").hide();
            $("[name=rc_idx2] option").eq(0).prop("selected",true);
            for(var icnt=0;icnt < $("[name=rc_idx2] option").length;icnt++){
                if($("[name=rc_idx2] option").eq(icnt).val()==""){
                    $("[name=rc_idx2] option").eq(icnt).show();
                }
            }
        }else{
            $("[name=rc_idx2] option").not(":eq(0)").hide();
            $("[name=rc_idx2] option").eq(0).prop("selected",true);
            for(var icnt=0;icnt < $("[name=rc_idx2] option").length;icnt++){
                if($(this).val()==$("[name=rc_idx2] option").eq(icnt).attr("data-ad_addr")){
                    $("[name=rc_idx2] option").eq(icnt).show();
                }
            }
        }
    });

	$(document).on("click",".btnBuy",function(){
		var code = $(this).attr("data-code");
		var point = $(this).attr("data-point");
		var num = $(this).attr("data-num");
		var mm_point = $("[name=mm_point]").val();
		var addr = $("[name=pm_addr"+num+"]").val();
		var rc_idx = $("[name=rc_idx"+num+"]").val();
		var sdate = "";
		var edate = "";

		var sdate = $("[name=pm_sdate"+num+"]").val();

		if(parseInt(point) > parseInt(mm_point)){
			commonAlert("보유하신 포인트가 부족합니다. 보유포인트 : " + mm_point.format(),function(){});
			return;
		}

        if(code=="C001" || code=="C003"){
        	sdate = $("[name=pm_sdate"+num+"]").val();
			edate = getDateAdd(sdate,6,'d');

        	if($("[name=pm_addr"+num+"]").val()==""){
        		commonAlert("등록지역을 선택해주세요.",function(){
        			$("[name=pm_addr"+num+"]").focus();
        		});
        		return;
        	}

        	if($("[name=pm_sdate]").val()==""){
        		commonAlert("등록 시작일을 선택해주세요.",function(){
        			$("[name=pm_sdate"+num+"]").focus();
        		});
        		return;
        	}

        	if($("[name=rc_idx"+num+"]").val()==""){
        		commonAlert("공고를 선택해주세요.",function(){
        			$("[name=rc_idx"+num+"]").focus();
        		});
        		return;
        	}

			if(code=="C001"){
				confirm_txt = '<p><span>“'+addr+'”</span> <em>추천공고</em>로<br> 등록하시겠습니까?</p>'+
	            '<ul class="dot-list">'+
	            '<li>등록기간 : <span>'+sdate+' ~ '+edate+'(7일간)</span></li>'+
	            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
	            '<li>등록지역 : <span>'+addr+'</span></li>'+
	            '</ul>';
			}else if(code =="C003"){
				confirm_txt = '<p><span>점프업</span><br> 등록하시겠습니까?</p>'+
	            '<ul class="dot-list">'+
	            '<li>등록기간 : <span>'+sdate+' ~ '+edate+'(7일간)</span></li>'+
	            '<li>등록지역 : <span>'+addr+'</span></li>'+
	            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
	            '</ul>';
			}
    	}else if(code=="C002"){
    		if($("[name=pm_sdate"+num+"]").val()==""){
        		commonAlert("등록 시작일을 선택해주세요.",function(){
        			$("[name=pm_sdate"+num+"]").focus();
        		});
        		return;
        	}

          sdate = $("[name=pm_sdate"+num+"]").val();
			edate = getDateAdd(sdate,6,'d');

    		//sdate = getToday("y-m-d");
			//edate = getDateAdd(sdate,6,'d');
    		confirm_txt = '<p><span>지금뜨는 업체로</span><br> 등록하시겠습니까?</p>'+
            '<ul class="dot-list">'+
            '<li>등록기간 : <span>'+sdate+' ~ '+edate+'(7일간)</span></li>'+
            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
            '</ul>';
    	}

        openPopup('confirm', {
            content: confirm_txt,
            secondCallback: function () {
                var params = {};
		        params["pm_code"] = code;
		        params["pm_sdate"] = sdate;
		        params["pm_addr"] = addr;
		        params["rc_idx"] = rc_idx;

		        comAjax("/compage_proc/point_promotion",params,function(ret){
		            if(ret.code=="00"){
		                location.href="/compage/point_promotion_list"
		            }else{
		            	commonAlert(ret.msg,function(){});
		            }
		        });
            }
        });
	});
});
</script>
