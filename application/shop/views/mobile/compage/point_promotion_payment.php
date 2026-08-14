<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">포인트 충전 · 광고 구매 / 관리</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <?php $tab_on = "tab4";?>
        <?php include_once (get_base_url() . '/application/shop/views/mobile/compage/top_line.php'); ?>
        <div class="tab-panel">
          <script>
          <?php
          echo "const promotionBuyArray = [ ";
          foreach ($promotion_buy_list as $promotion_buy) {
              echo "{ idx: ".$promotion_buy['rc_idx']." }, ";
          }
          echo "];";
          ?>
          <?php
            echo "const recruitAddrArray = [ ";
            foreach ($recruit_list as $resume) {
                echo "{ idx: ".$resume['rc_idx'].", ad_addr: \"".$resume[ad_addr]."\" }, ";
            }
            echo "];";
          ?>
          </script>
            <!-- 20220929 수정 -->
            <div class="promo-view">
                <div class="container">
                    <?php if($type=="C001" || $type=="C002" || $type=="C003" || $type=="C004") : ?>
	                    <div class="promo-content">
                          <?php $promotion_title = $promotion_info["cd_val"]."(5일권)";
                                $promotion_title = str_replace(")(","/",$promotion_title);
                           ?>
	                        <h2 class="primary-title"><?=$promotion_title?></h3>
	                        <p class="required-point">차감포인트 : <em><?=number_format($promotion_info["cd_val3"] * 5)?></em>P</p>
	                        <?php if($type=="C001") : ?>
	                        	<p class="desc"><!--포인트는 <?=number_format($promotion_info["cd_val3"] * 5)?>P가 차감되며,<br> -->지정한 광고지역, 공고, 등록시작일부터 5일간 추천공고로 표시됩니다.</p>
	                        <?php elseif($type=="C002") : ?>
                            <p class="desc">등록하신 채용공고를 상위목록으로 노출시킬 수 있습니다.<br/>점프업은 광고구매하기 기준으로 업데이트 됩니다.</p>
	                        <?php elseif($type=="C003") : ?>
	                        	<p class="desc">등록하신 채용공고를 상위목록으로 노출시킬 수 있습니다.<br/>점프업은 광고구매하기 기준으로 업데이트 됩니다.</p>
                          <?php elseif($type=="C004") : ?>
                            <p class="desc">지정한 등록시작일 부터 5일간 지금뜨는 업체로 표시됩니다.</p>
	                        <?php endif; ?>
	                        <div class="form-wrap">
                                <?php if($type!="C004") : ?>
                                <div class="form-row">
                                    <!-- 20221107 수정 -->
                                    <div class="inline-group">
                                        <div class="label">
                                            <p class="form-label">공고선택</p>
                                        </div>
                                        <div class="col">
                                            <label class="form-selectbox">
                                                <span class="sr-text">공고선택</span>
                                                <!-- 처리 해야함 : 공고 연동 -->
                                                <select name="rc_idx" aria-required="true" onchange="fAddrSet(this.value);">
                                                  <option value="" selected>공고선택</opion>
                                                    <?php foreach($recruit_list as $row) : ?>
                                                    <option value="<?=$row["rc_idx"]?>" style="" data-ad_addr="<?=trim($row["ad_addr"])?>" ><?=$row["rc_name"]?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span class="liner"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
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
                                <?php if($type!="C004") : ?>
                                <div class="form-row">
                                    <!-- 20221107 수정 -->
                                    <div class="inline-group">
                                        <div class="label">
                                            <p class="form-label">광고지역</p>
                                        </div>
                                        <div class="col">
                                          <div class="search-input">
                                            <input type="text" id="pm_addr" name="pm_addr" placeholder="" autocomplete="off" aria-required="true" required="" class="form-text" onclick="$('#changeArea').addClass('on');" style="width: calc(100% - 26px); border-bottom: 1px solid #333;">
    		                                    <a id="btnSearchAddrX" class="icon" href="javascript:void(0);" onclick="$('#changeArea').addClass('on');" style="position: absolute; right: 0px; top: 6px; width: 24px;">
    		                            	          <img src="/resource/shop/mobile/img/common/search.png" alt="광고지역 검색">
    		                                    </a>
                                          </div>
                                          <!--
                                            <label class="form-selectbox">
                                              changeArea

                                                <span class="sr-text">광고지역 선택</span>
                                                <select name="pm_addr" aria-required="true">
                                                    <option value="" selected>지역선택</opion>
                                                    <?php foreach($addr_list as $ad_addr) : ?>
                                                    <option value="<?=$ad_addr?>" ><?=$ad_addr?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <span class="liner"></span>
                                            </label>
                                          -->
                                        </div>
                                    </div>
                                </div>
								                <?php endif; ?>
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
<input type="hidden" name="price" value="<?=($promotion_info["cd_val3"] * 5)?>" />
<input type="hidden" name="mm_point" value="<?=$mem_info["mm_point"]?>" />

<script>
$(document).ready(function(){
	$("#btnNext").click(function(){
		var code = $("[name=code]").val();
		var point = $("[name=price]").val();
		var rc_idx = $("[name=rc_idx]").val();
		var sdate = "";
		var edate = "";
		var mm_point = $("[name=mm_point]").val();
		var addr = $("[name=pm_addr]").val();
		var recuit_name = $("[name=rc_idx] option:checked").text();

		//if(code=="C001" || code=="C003"){
		//	sdate = $("[name=pm_sdate]").val();
		//	edate = getDateAdd(sdate,6,'d');
    //}else if(code=="C002"){
		//	sdate = getToday("y-m-d");
		//	edate = getDateAdd(sdate,1,'d');
		//}
    sdate = $("[name=pm_sdate]").val();
    edate = getDateAdd(sdate,4,'d');

		var confirm_txt = "";
		if(parseInt(point) > parseInt(mm_point)){
			commonAlert("보유하신 포인트가 부족합니다. 보유포인트 : " + mm_point.format(),function(){});
			return;
		}

    if(code!="C004"){
          if($("[name=rc_idx]").val()==""){
            commonAlert("공고를 선택해주세요.",function(){
              $("[name=rc_idx]").focus();
            });
            return;
          }
    }


    if($("[name=pm_sdate]").val()==""){
        commonAlert("등록 시작일을 선택해주세요.",function(){
          $("[name=pm_sdate]").focus();
        });
        return;
    }

        if(code=="C001" || code=="C002" || code=="C003"){
        	//if(code!="C002"){
	        	if($("[name=pm_addr]").val()==""){
	        		commonAlert("광고지역을 선택해주세요.",function(){
	        			$("[name=pm_addr]").focus();
	        		});
	        		return;
	        	}
        	//}
        }

        	var str = "";

        	if(code=="C001"){
        		confirm_txt = //'<p><span>“'+addr+'”</span> <em>추천공고등록(7일권)</em>로<br> 등록하시겠습니까?</p>'+
              '<p><?=$promotion_title?></p>' +
	            '<ul class="dot-list">'+
	            '<li>등록기간 : <span>'+sdate+' ~ '+edate+'(5일간)</span></li>'+
	            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
	            '<li>광고지역 : <span>'+addr+'</span></li>'+
	            '<li>추천공고 : <span>'+recuit_name+'</span></li>'+
	            '</ul>';
        	}else if(code=="C002"){
        		confirm_txt = //'<p><em>추천공고등록(7일권)</em>로<br> 등록하시겠습니까?</p>'+
            '<p><?=$promotion_title?></p>' +
            '<ul class="dot-list">'+
            '<li>등록기간 : <span>'+sdate+' ~ '+edate+'(5일간)</span></li>'+
            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
            '<li>광고지역 : <span>'+addr+'</span></li>'+
            '<li>추천공고 : <span>'+recuit_name+'</span></li>'+
            '</ul>';
        	}else if(code=="C003"){
        		confirm_txt = //'<p><span>“'+addr+'”</span> <em>점프업기능</em>로<br> 등록하시겠습니까?</p>'+
              '<p><?=$promotion_title?></p>' +
	            '<ul class="dot-list">'+
	            '<li>등록기간 : <span>'+sdate+' ~ '+edate+'(5일간)</span></li>'+
	            '<li>차감포인트 : <span>'+point.format()+'P</span></li>'+
	            '<li>광고지역 : <span>'+addr+'</span></li>'+
	            '<li>추천공고 : <span>'+recuit_name+'</span></li>'+
	            '</ul>';
          }else if(code=="C004"){
        		confirm_txt = //'<p><em>추천공고등록(7일권)</em>로<br> 등록하시겠습니까?</p>'+
              '<p><?=$promotion_title?></p>' +
	            '<ul class="dot-list">'+
	            '<li>등록기간 : <span>'+sdate+' ~ '+edate+'(5일간)</span></li>'+
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
		        params["rc_idx"] = $("[name=rc_idx]").val();
            params["pm_duration"] = '5';

		        comAjax("/compage_proc/point_promotion",params,function(ret){
		            if(ret.code=="00"){
		                _replace("/compage/point_promotion_list");
		            }else{
		            	commonAlert(ret.msg,function(){});
		            }
		        });
            }
        });

	  });//next click

    //공고 선택
    $("[name=pm_addr]").change(function(){
        if($(this).val()==""){
            $("[name=rc_idx] option").not(":eq(0)").hide();
            $("[name=rc_idx] option").eq(0).prop("selected",true);
            for(var icnt=0;icnt < $("[name=rc_idx] option").length;icnt++){
                if($("[name=rc_idx] option").eq(icnt).val()==""){
                    $("[name=rc_idx] option").eq(icnt).show();
                }
            }
        }else{
            $("[name=rc_idx] option").not(":eq(0)").hide();
            $("[name=rc_idx] option").eq(0).prop("selected",true);
            for(var icnt=0;icnt < $("[name=rc_idx] option").length;icnt++){
                if($(this).val()==$("[name=rc_idx] option").eq(icnt).attr("data-ad_addr")){
                    $("[name=rc_idx] option").eq(icnt).show();
                }
            }
        }
    });
});

function fAddrSet(addrIdx){

    const matchingPromotionBuyItem = promotionBuyArray.find(item => item.idx === parseInt(addrIdx));
    var rtnPromotionBuyItem = matchingPromotionBuyItem ? true : false;
    if(rtnPromotionBuyItem){
      commonAlert("해당공고는 이미 유료광고를 구매한 상태입니다",function(){ $('#pm_addr').val(''); });
      return false;
    }

    const matchingItem = recruitAddrArray.find(item => item.idx === parseInt(addrIdx));
    //return matchingItem ? matchingItem.ad_addr : null;
    var rtn = matchingItem ? matchingItem.ad_addr : null;

    //$('#ad_addr1').val(rtn);
    //alert(gbn);
    //alert(addrIdx);
    //$('#'+gbn).val(rtn);
    $('#pm_addr').val(rtn);
}

</script>
<script>
// 검색 완료 선택 처리
function selectAddrInfo(zipcode,doro,jibun,addr,x,y){
    var params = {};
    params["ad_zipcode"] = zipcode;
    params["ad_jibun"] = jibun;
    params["ad_doro"] = doro;
    params["ad_addr"] = addr;
    params["ad_gps_x"] = x;
    params["ad_gps_y"] = y;
    console.log(params);
    $('#pm_addr').val(params["ad_addr"]);
    $('#changeArea').removeClass('on');
}
</script>
<?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>
