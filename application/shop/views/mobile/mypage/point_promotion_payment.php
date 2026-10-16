<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">포인트 충전 · 광고 구매 / 관리</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <?php $tab_on = "tab4";?>
        <?php include_once (get_base_url() . '/application/shop/views/mobile/mypage/top_line.php'); ?>
        <div class="tab-panel">
            <!-- 20220929 수정 -->
            <div class="promo-view">
                <div class="container">
                    <input type="hidden" id="ru_idx" value="<?=$resume_list[0]["ru_idx"]?>">
                    <?php if($type=="M002") : ?>
	                    <div class="promo-content">
	                        <h2 class="primary-title">점프업(4시간)</h3>
	                        <p class="required-point">차감포인트 : <em class="js-point-txt">1,000</em>P</p><div class="form-wrap"><div class="form-row"><div class="inline-group"><div class="label"><p class="form-label">광고기간</p></div><div class="col"><label class="form-selectbox"><span class="sr-text">광고기간 선택</span><select name="pm_duration_sel" aria-required="true" class="js-duration"><option value="2">2일 (추가혜택 1일)</option><option value="5">5일 (추가혜택 2일)</option><option value="10">10일 (추가혜택 5일)</option><option value="20">20일 (추가혜택 10일)</option><option value="30">30일 (추가혜택 15일)</option></select><span class="liner"></span></label></div></div></div></div>
	                        <p class="desc">등록하신 이력서를 상위목록으로 노출시킬 수 있습니다.<br> 점프업은 광고구매하기 기준으로 업데이트 됩니다.</p>
                          <div class="form-wrap">
                                <div class="form-row">
                                    <div class="inline-group">
                                        <div class="label">
                                            <p class="form-label">이력서</p>
                                        </div>
                                        <div class="col">
                                            <label class="form-selectbox">
                                                <?php //print_r($resume_list); ?>
                                                <span class="liner" style="background: none;"><?=$resume_list[0]["ru_name"]?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row" style="display:none;">
                                    <!-- 20221107 수정 -->
                                    <div class="inline-group">
                                        <div class="label">
                                            <p class="form-label">등록지역</p>
                                        </div>
                                        <div class="col">
                                            <label class="form-selectbox">
                                                <span class="sr-text">등록지역 선택</span>
                                                <select name="pm_addr" aria-required="true">
                                                    <option value="" selected>지역선택</opion>
                                                   	<?php foreach($addr_list as $addr) : ?>
                                                	<option value="<?=$addr["ad_addr"]?>"><?=$addr["ad_addr"]?></option>
                                                	<?php endforeach; ?>
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
                                            <p class="form-label">등록시작일</p>
                                        </div>
                                        <div class="col">
                                            <label class="form-selectbox">
                                                <span class="sr-text">등록시작일 선택</span>
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
                                                    nextArrow: '<span class="sr-text">다음</span>',
                                                    prevArrow: '<span class="sr-text">이전</span>',
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
                                    <button type="button" id="btnNext" aria-haspopup="dialog" class="btn btn-primary">광고구매하기</button>
                                </div>
	                        </div>
	                    </div>
                    <?php elseif($type=="M003") : ?>
  	                    <div class="promo-content">
  	                        <h2 class="primary-title">점프업(8시간)</h3>
  	                        <p class="required-point">차감포인트 : <em class="js-point-txt">800</em>P</p><div class="form-wrap"><div class="form-row"><div class="inline-group"><div class="label"><p class="form-label">광고기간</p></div><div class="col"><label class="form-selectbox"><span class="sr-text">광고기간 선택</span><select name="pm_duration_sel" aria-required="true" class="js-duration"><option value="2">2일 (추가혜택 1일)</option><option value="5">5일 (추가혜택 2일)</option><option value="10">10일 (추가혜택 5일)</option><option value="20">20일 (추가혜택 10일)</option><option value="30">30일 (추가혜택 15일)</option></select><span class="liner"></span></label></div></div></div></div>
  	                        <p class="desc">등록하신 이력서를 상위목록으로 노출시킬 수 있습니다.<br> 점프업은 광고구매하기 기준으로 업데이트 됩니다.</p>
                            <div class="form-wrap">
                                  <div class="form-row">
                                      <div class="inline-group">
                                          <div class="label">
                                              <p class="form-label">이력서</p>
                                          </div>
                                          <div class="col">
                                              <label class="form-selectbox">
                                                  <?php //print_r($resume_list); ?>
                                                  <span class="liner" style="background: none;"><?=$resume_list[0]["ru_name"]?></span>
                                              </label>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-row" style="display:none;">
                                      <!-- 20221107 수정 -->
                                      <div class="inline-group">
                                          <div class="label">
                                              <p class="form-label">등록지역</p>
                                          </div>
                                          <div class="col">
                                              <label class="form-selectbox">
                                                  <span class="sr-text">등록지역 선택</span>
                                                  <select name="pm_addr" aria-required="true">
                                                      <option value="" selected>지역선택</opion>
                                                     	<?php foreach($addr_list as $addr) : ?>
                                                  	<option value="<?=$addr["ad_addr"]?>"><?=$addr["ad_addr"]?></option>
                                                  	<?php endforeach; ?>
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
                                              <p class="form-label">등록시작일</p>
                                          </div>
                                          <div class="col">
                                              <label class="form-selectbox">
                                                  <span class="sr-text">등록시작일 선택</span>
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
                                                      nextArrow: '<span class="sr-text">다음</span>',
                                                      prevArrow: '<span class="sr-text">이전</span>',
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
                                      <button type="button" id="btnNext" aria-haspopup="dialog" class="btn btn-primary">광고구매하기</button>
                                  </div>
  	                        </div>
  	                    </div>
                    <?php elseif($type=="M001") : ?>
	                    <div class="promo-content">
	                        <h2 class="primary-title">추천인재등록</h3>
	                        <p class="required-point">차감포인트 : <em class="js-point-txt">2,000</em>P</p><div class="form-wrap"><div class="form-row"><div class="inline-group"><div class="label"><p class="form-label">광고기간</p></div><div class="col"><label class="form-selectbox"><span class="sr-text">광고기간 선택</span><select name="pm_duration_sel" aria-required="true" class="js-duration"><option value="2">2일 (추가혜택 1일)</option><option value="5">5일 (추가혜택 2일)</option><option value="10">10일 (추가혜택 5일)</option><option value="20">20일 (추가혜택 10일)</option><option value="30">30일 (추가혜택 15일)</option></select><span class="liner"></span></label></div></div></div></div>
	                        <p class="desc">포인트는 <span class="js-point-txt">2,000</span>P가 차감되며,<br> 지정한 등록지역, 공고, 등록시작에 <span class="js-days">2</span>일간 추천공고로 표시됩니다.
                          </p>
	                        <div class="form-wrap">
                                <div class="form-row">
                                    <div class="inline-group">
                                        <div class="label">
                                            <p class="form-label">이력서</p>
                                        </div>
                                        <div class="col">
                                            <label class="form-selectbox">
                                                <?php //print_r($resume_list); ?>
                                                <span class="liner" style="background: none;"><?=$resume_list[0]["ru_name"]?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row" style="display:none;">
                                    <!-- 20221107 수정 -->
                                    <div class="inline-group">
                                        <div class="label">
                                            <p class="form-label">등록지역</p>
                                        </div>
                                        <div class="col">
                                            <label class="form-selectbox">
                                                <span class="sr-text">등록지역 선택</span>
                                                <select name="pm_addr" aria-required="true">
                                                    <option value="" selected>지역선택</opion>
                                                   	<?php foreach($addr_list as $addr) : ?>
                                                	<option value="<?=$addr["ad_addr"]?>"><?=$addr["ad_addr"]?></option>
                                                	<?php endforeach; ?>
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
                                            <p class="form-label">등록시작일</p>
                                        </div>
                                        <div class="col">
                                            <label class="form-selectbox">
                                                <span class="sr-text">등록시작일 선택</span>
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
                                                    nextArrow: '<span class="sr-text">다음</span>',
                                                    prevArrow: '<span class="sr-text">이전</span>',
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
                                    <button type="button" id="btnNext" aria-haspopup="dialog" class="btn btn-primary">광고구매하기</button>
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
<input type="hidden" name="pm_duration" value="">
<script>
/* 상품코드별 기간(일)별 차감포인트 - mypage_proc.php point_promotion과 동일 값 유지 */
var DURATION_POINT = {M001:{'2':2000,'5':5000,'10':10000,'20':20000,'30':30000},M002:{'2':1000,'5':2500,'10':5000,'20':10000,'30':15000},M003:{'2':800,'5':2000,'10':4000,'20':8000,'30':12000}};
/* 기간(일)별 추가혜택일 */
var DURATION_BONUS = {'2':1,'5':2,'10':5,'20':10,'30':15};
/* 기간(일)별 종료일 가산일 - 서버 strtotime 계산과 동일 */
var SERVER_EDATE_ADD = {'2':2,'5':6,'10':14,'20':29,'30':44};
/* 광고기간 변경 시 차감포인트/설명 문구 갱신 */
$(document).on('change', '[name=pm_duration_sel]', function () {
  var d = $(this).val();
  var code = $("[name=code]").val();
  $("[name=price]").val(DURATION_POINT[code][d]);
  $(".js-point-txt").text(Number(DURATION_POINT[code][d]).toLocaleString());
  $(".js-days").text(d);
});
</script>

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
      edate = getDateAdd(sdate,SERVER_EDATE_ADD[$("[name=pm_duration_sel]").val()],'d');
			//edate = getDateAdd(sdate,6,'d');
      $("[name=pm_duration]").val($("[name=pm_duration_sel]").val());
		}else if(code=="M002"){
      sdate = $("[name=pm_sdate]").val();
			//sdate = getToday("y-m-d");
			edate = getDateAdd(sdate,SERVER_EDATE_ADD[$("[name=pm_duration_sel]").val()],'d');
      $("[name=pm_duration]").val($("[name=pm_duration_sel]").val());
    }else if(code=="M003"){
      sdate = $("[name=pm_sdate]").val();
			//sdate = getToday("y-m-d");
			edate = getDateAdd(sdate,SERVER_EDATE_ADD[$("[name=pm_duration_sel]").val()],'d');
      $("[name=pm_duration]").val($("[name=pm_duration_sel]").val());
		}

		var confirm_txt = "";
		if(parseInt(point) > parseInt(mm_point)){
			commonAlert("보유하신 포인트가 부족합니다. 보유포인트 : " + mm_point.format(),function(){});
			return;
		}

        if(code=="M001"){
        	//if($("[name=pm_addr]").val()==""){
        	//	commonAlert("등록지역을 선택해주세요.",function(){
        	//		$("[name=pm_addr]").focus();
        	//	});
        	//	return;
        	//}

        	if($("[name=pm_sdate]").val()==""){
        		commonAlert("등록 시작일을 선택해주세요.",function(){
        			$("[name=pm_sdate]").focus();
        		});
        		return;
        	}

          //<em>“'+addr+'”</em>
        	confirm_txt = '<p><span>추천인재</span>로<br> 등록하시겠습니까?</p>'+
            '<ul class="dot-list">'+
            '<li>등록기간 : <span>'+sdate+' ~ '+edate+''+$("[name=pm_duration_sel]").val()+'일 결제 / 추가혜택 '+DURATION_BONUS[$("[name=pm_duration_sel]").val()]+'일)</span></li>'+
            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
            //'<li>등록지역 : <span>'+addr+'</span></li>'+
            '</ul>';
    	}else if(code=="M002"){
        if($("[name=pm_sdate]").val()==""){
          commonAlert("등록 시작일을 선택해주세요.",function(){
            $("[name=pm_sdate]").focus();
          });
          return;
        }
    		confirm_txt = '<p><span>점프업</span><br> 등록하시겠습니까?</p>'+
            '<ul class="dot-list">'+
            '<li>등록기간 : <span>'+sdate+' ~ '+edate+''+$("[name=pm_duration_sel]").val()+'일 결제 / 추가혜택 '+DURATION_BONUS[$("[name=pm_duration_sel]").val()]+'일)</span></li>'+
            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
            '</ul>';
      }else if(code=="M003"){
        if($("[name=pm_sdate]").val()==""){
          commonAlert("등록 시작일을 선택해주세요.",function(){
            $("[name=pm_sdate]").focus();
          });
          return;
        }
    		confirm_txt = '<p><span>점프업</span><br> 등록하시겠습니까?</p>'+
            '<ul class="dot-list">'+
            '<li>등록기간 : <span>'+sdate+' ~ '+edate+''+$("[name=pm_duration_sel]").val()+'일 결제 / 추가혜택 '+DURATION_BONUS[$("[name=pm_duration_sel]").val()]+'일)</span></li>'+
            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
            '</ul>';
    	}

		openPopup('confirm', {
            content: confirm_txt,
            secondCallback: function () {
                var params = {};
		        params["pm_code"] = $("[name=code]").val();
		        params["pm_sdate"] = $("[name=pm_sdate]").val()+' 00:00';
		        params["pm_addr"] = $("[name=pm_addr]").val();
            params["pm_resume"] = $("#ru_idx").val();
            params["pm_duration"] = $("[name=pm_duration]").val();

		        comAjax("/mypage_proc/point_promotion",params,function(ret){
		            if(ret.code=="00"){
		                _replace("/mypage/point_promotion_list");
		            }else{
		            	commonAlert(ret.msg,function(){});
		            }
		        });
            }
        });
	});

  <?php if($resume_list == null || $resume_list[0] == null || $resume_list[0]["ru_name"] == null){ ?>
    commonAlert("대표이력서가 설정되지 않았습니다. 이력서 등록, 대표이력서 설정 후 광고를 구매해주세요",function(){ location.href='/mypage/resume_list'; });
  <?php }  ?>

});
</script>
