<script src="/resource/shop/web/js/lib/proj4.js?vs=1647169429"></script>
 <div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/member/memco_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">기업회원 유료광고관리</h3>
            <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="600px"/>
                    <col width="150px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">검색어</th>
                    <td class="inline-wrap">
                         <div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",[""=>"전체","mm_name"=>"회원명","rc_name"=>"공고명","ad_addr"=>"지역"],$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <th scope="row"></th>
                    <td class="inline-wrap">
					          </td>
                 </tr>
                 <tr>
                   <th scope="row">카테고리</th>
                   <td class="inline-wrap" colspan="3">
                       <?=inputRadio("rc_work","rc_work",[""=>"전체","사무직"=>"사무직", "운전배달"=>"운전배달", "물류"=>"물류", "식당"=>"식당", "서비스"=>"서비스", "매장관리"=>"매장관리", "건설"=>"건설", "상담"=>"상담", "디자인"=>"디자인", "고객상담"=>"고객상담", "기타"=>"기타"],$search["rc_work"])?>
                   </td>
                 </tr>
                 <tr>
                   <th scope="row">유료광고</th>
                   <td class="inline-wrap" colspan="3">
                       <?=inputRadio("pm_code","pm_code",[""=>"전체","C001"=>"추천공고등록","C002"=>"점프업(4시간)","C003"=>"점프업(8시간)","C004"=>"지금뜨는 업체"],$search["pm_code"])?>
                   </td>
                 </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <input type="hidden" id="ad_gbn" name="ad_gbn" value="<?=$search["ad_gbn"]?>">
                <button type="button" class="btn-m btn-type10 w100 btnSearch">검색</button>
            </div>
        </div>
    </form>
    <div class="search-result">
      <div class="tab policy js-tab">
            <a data-tab_id="#tab_1" href="javascript:$('#ad_gbn').val('ing'); $('.btnSearch').trigger('click');" class="<?=(($search["ad_gbn"]=="ing" || $search["ad_gbn"]=="")?"on":"")?>">게재중인 광고</a>
            <a data-tab_id="#tab_2" href="javascript:$('#ad_gbn').val('yet'); $('.btnSearch').trigger('click');" class="<?=($search["ad_gbn"]=="yet"?"on":"")?>">대기중인 광고</a>
            <a data-tab_id="#tab_3" href="javascript:$('#ad_gbn').val('cls'); $('.btnSearch').trigger('click');" class="<?=($search["ad_gbn"]=="cls"?"on":"")?>">마감된 광고</a>
            <a data-tab_id="#tab_4" href="javascript:$('#ad_gbn').val('all'); $('.btnSearch').trigger('click');" class="<?=($search["ad_gbn"]=="all"?"on":"")?>">전체광고</a>
        </div>
        <table class="table-type2">
            <colgroup>
                <col width="40" />
                <col width="100" />
                <col width="70" />
                <col width="100" />
                <col width="150" />
                <col width="70" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">유료광고</th>
                <th scope="col">사업장명</th>
                <th scope="col">공고명</th>
                <th scope="col">카테고리</th>
                <th scope="col">사용포인트</th>
				        <th scope="col">등록일시</th>
                <th scope="col">광고지역</th>
                <th scope="col">광고기간</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
	                <td><?=$start - $key?></td>
                  <td><?=($row["pm_name"])?></td>
                  <td><?=$row["mm_name"]?></td>
                  <td><?=$row["rc_name"]?></td>
                  <td><?=$row["rc_work"]?></td>
                  <td><?=number_format($row["pm_point"])?></td>
	                <td>&nbsp;&nbsp;<?=substr($row["reg_dt"],0,10)?><br/><?=substr($row["reg_dt"],11,16)?></td>
	                <td><?=$row["pm_addr"]?></td>
	                <td><?=$row["pm_sdate"]?> ~<br><?=$row["pm_edate"]?><span style="color:#fff;"> ~</span></td>
	                <td>
                    <a href="/admin/member/recruit_edit?idx=<?=$row["rc_idx"]?>" class="btn-xs btn-type3" style="width:auto;">공고상세보기</a>
                    <a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit2" data-idx="<?=$row["rc_idx"]?>" data-mmidx="<?=$row["mm_idx"]?>" style="width:auto;">광고등록</a>
                    <?php
                        $today = date("Y-m-d H:i:s"); // 오늘 날짜 가져오기
                        if ($row["pm_edate"] > $today) { // pm_edate가 오늘보다 뒤인지 비교
?>                          <a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit3" data-idx="<?=$row["pm_idx"]?>" style="width:auto;">마감</a>
<?php                   } else {
                            echo "<span style='border: 1px solid #ccc; background-color: #ccc; padding: 4px 10px;'>마감</span>";
                        }
                    ?>
	                </td>
	            </tr>
	            <?php endforeach;?>
              <?php if(empty($result_list)) {
                      echo "<tr><td colspan='10' style='padding: 150px;'>조회된 내역이 없습니다</td></tr>";
                    } ?>
			</tbody>
        </table>
    </div>
    <div>
	    <?=$pagenation?>
	</div>
</div>

<script>
   $(document).ready(function(){

       $(".btnEdit2").click(function(){
         $('#popupDim').show();
         $('#popup1').show();
         $('#rc_idx').val($(this).data('idx'));
         $('#mm_idx').val($(this).data('mmidx'));
         //initDatepicker();
       });

       // $(".btnEdit2").click(function(){
       //     var idx = $(this).data("idx");
       //     location.href="/admin/member/recruit_edit?idx=" + idx;
       // });
       //

        $(".btnEdit3").click(function(){
            var idx = $(this).data("idx");
            if(confirm('마감처리 하시겠습니까?')){
                var params = {};
                params["pm_idx"] = idx;

                comAjax("/admin/member/promotion_close",params,function(ret){
                 
                    if(ret.code=="00"){
                        location.reload();
                    }else{
                      commonAlert(ret.msg,function(){});
                    }
                });
            }
        });

       $(".btnSearch").click(function(){
           $("#frm-search").attr("action","/admin/member/memco_list");
           $("#frm-search").submit();
       });

       $(".btnCloseArea").click(function(){
         $("#changeArea").hide();
       });


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
                   //console.log($('.form-control'));
                   $('.form-control').css('display','inline-block');
                   $('.form-control').css('width','130px');
                   $('.form-control').css('height','36px');
                   $('.form-control').css('margin-top','10px');
                   $('.form-control').css('border','1px solid #57ccaf');
                   $('.form-control').css('text-align','left');
                   $('.form-control').css('background-color','#fff');
                   $('.form-control').css('background-size','10px auto');
                   $('.form-control').css('padding','0 0 0 16px');
                   $('.form-control').css('background-position','112px 14px');

                   fMakeDuration();
               }
           });
       }

       //프로모 구매 안내 팝업
       window.addEventListener('DOMContentLoaded', function (e) {
           var viewBtns = document.querySelectorAll('.js-view-promo');
           if (!viewBtns) return;

           //initDatepicker();

      });



   });

   // 검색 완료 선택 처리
   function selectAddrInfo(zipcode,doro,jibun,addr,x,y){
       var params = {};
       params["ad_zipcode"] = zipcode;
       params["ad_jibun"] = jibun;
       params["ad_doro"] = doro;
       params["ad_addr"] = addr;
       params["ad_gps_x"] = x;
       params["ad_gps_y"] = y;
       $('#ad_addr1').val(addr);
       //$('#changeArea').removeClass('on');
       $('#changeArea').hide();
   }

   function fMakeDuration(){
     // 시작 날짜 가져오기
     var startDate = document.getElementById('ad_startdate1').value;
     // 기간(일 수) 가져오기
     var duration = parseInt(document.getElementById('ad_duration1').value);

     if(duration==2){ duration += 1; $('#usepoint1').html('-4,000');
     }else if(duration==5){ duration += 2; $('#usepoint1').html('-10,000');
     }else if(duration==10){ duration += 5; $('#usepoint1').html('-20,000');
     }else if(duration==20){ duration += 10; $('#usepoint1').html('-40,000');
     }else if(duration==30){ duration += 15; $('#usepoint1').html('-60,000'); }

     // 날짜 형식으로 변환
     var date = new Date(startDate);
     // 선택된 일 수 만큼 날짜에 추가
     date.setDate(date.getDate() + (duration - 1));

     // 결과 날짜를 YYYY-MM-DD 형식으로 변환
     var endDate = date.toISOString().split('T')[0];

     // 결과 문자열 만들기
     var result = startDate + " 부터 " + endDate + " 까지";

     // 결과를 ad_duration_txt 요소에 설정
     document.getElementById('ad_duration_txt1').innerHTML = result;
   }

   function fBuyFree(gbn){

      if ($('select[name="pm_addr"]').val() === "") {
          // 선택되지 않았을 때 메시지 표시
          alert("광고를 선택해주세요.");
          return;
      }
      if ($('#ad_addr1').val() == "") {
          alert("광고지역을 선택해주세요.");
          return;
      }

      const adStartDate = $('#ad_startdate1').val();

     // 1. 입력값 존재 여부 확인
     if (!adStartDate) {
       alert('광고 시작일을 입력해주세요.');
       return;
     }

     // 2. 날짜 형식 확인 (YYYY-MM-DD)
     const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
     if (!dateRegex.test(adStartDate)) {
       alert('광고 시작일을 YYYY-MM-DD 형식으로 입력해주세요.');
       return;
     }
     if ($('#ad_starthour1').val() == "") {
         alert("광고 시작일의 시간을 선택해주세요.");
         return;
     }
     if ($('#ad_startmin1').val() == "") {
         alert("광고 시작일의 분을 입력해주세요.");
         return;
     }
     if ($('#ad_duration1').val() == "") {
         alert("광고기간을 선택해주세요.");
         return;
     }

     var addDate = '';
     if($('#ad_duration1').val()=='2'){ addDate = '1'; }
     if($('#ad_duration1').val()=='5'){ addDate = '2'; }
     if($('#ad_duration1').val()=='10'){ addDate = '5'; }
     if($('#ad_duration1').val()=='20'){ addDate = '10'; }
     if($('#ad_duration1').val()=='30'){ addDate = '15'; }

     $('#popupConf1').show();
     $('#ad_prod_name').html($('select[name="pm_addr"] option:selected').text());
     $('#ad_area_pop1').html($('#ad_addr1').val());
     $('#ad_duration_pop1').html($('#ad_duration_txt1').html()+'<br><span style="margin-left:73px;">'+$('#ad_duration1').val()+'일 결제(+'+addDate+'일)</span>');

     if(gbn=='free'){
       $('#ad_point_pop1').html('0P');
       $('#ad_point').val(0);
     }else{
       var usePoint = 0;
       if($('select[name="pm_addr"]').val()=='C001'){
         if($('#ad_duration1').val()=='2'){ usePoint = 4000; }
         if($('#ad_duration1').val()=='5'){ usePoint = 10000; }
         if($('#ad_duration1').val()=='10'){ usePoint = 20000; }
         if($('#ad_duration1').val()=='20'){ usePoint = 40000; }
         if($('#ad_duration1').val()=='30'){ usePoint = 60000; }
       }
       if($('select[name="pm_addr"]').val()=='C002'){
         if($('#ad_duration1').val()=='2'){ usePoint = 1000; }
         if($('#ad_duration1').val()=='5'){ usePoint = 2500; }
         if($('#ad_duration1').val()=='10'){ usePoint = 5000; }
         if($('#ad_duration1').val()=='20'){ usePoint = 10000; }
         if($('#ad_duration1').val()=='30'){ usePoint = 15000; }
       }
       if($('select[name="pm_addr"]').val()=='C003'){
         if($('#ad_duration1').val()=='2'){ usePoint = 800; }
         if($('#ad_duration1').val()=='5'){ usePoint = 2000; }
         if($('#ad_duration1').val()=='10'){ usePoint = 4000; }
         if($('#ad_duration1').val()=='20'){ usePoint = 8000; }
         if($('#ad_duration1').val()=='30'){ usePoint = 12000; }
       }
       if($('select[name="pm_addr"]').val()=='C004'){
         if($('#ad_duration1').val()=='2'){ usePoint = 1000; }
         if($('#ad_duration1').val()=='5'){ usePoint = 2500; }
         if($('#ad_duration1').val()=='10'){ usePoint = 5000; }
         if($('#ad_duration1').val()=='20'){ usePoint = 10000; }
         if($('#ad_duration1').val()=='30'){ usePoint = 15000; }
       }
       $('#ad_point_pop1').html(usePoint+'P');
       $('#ad_point').val(usePoint);
     }
     $('#ad_area_pop1').html();

   }

   function fBuy(){
     var params = {};
     params["pm_code"] = $('select[name="pm_addr"]').val();
     params["pm_sdate"] = $('#ad_startdate1').val()+' '+$('#ad_starthour1').val()+':'+$('#ad_startmin1').val();
     params["pm_resume"] = $('#rc_idx').val();
     params["pm_duration"] = $('#ad_duration1 option:selected').val();
     params["pm_addr"] = $('#ad_addr1').val();
     params["mm_idx"] = $('#mm_idx').val();
     params["ad_point"] = $('#ad_point').val();

   
     //alert('ajax');
     comAjax("/admin/member/point_promotion",params,function(ret){
      
         //alert(ret);
         if(ret.code=="00"){
             location.reload();
         }else{
           commonAlert(ret.msg,function(){});
         }
     });
   }

</script>

<div id="popupDim" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #000; opacity: 0.5; z-index: 10;">
</div>
<div id="popup1" style="display:none; position: fixed; top: 20%; left: calc(50% - 200px); height: 560px; width: 400px; background-color: #fff; z-index: 20; padding: 50px;">
  <h4 id="popupTitle1" style="height: 2.5em; text-align: center; line-height: 2.5em; background-color: #337ab7; color: #fff; position: absolute; top: 0; left: 0; width: 100%;">광고등록</h4>
  <button type="button" onclick="$('#popupDim').hide();$('#popup1').hide();" style="position: absolute; right: 6px; top: 5px; border: 1px solid #fff; padding: 1px 7px; color: #fff; font-size: 0.9em;"><span class="sr-text">닫기</span></button>
  <div class="popup-content">
          <div class="promo-view" style="width: 300px;">
                                        <div class="promo-content">
                                            <h3 class="secondary-title" style="text-align: center; line-height: 32px; font-size: 1.2em; font-weight: 600; margin-bottom: 30px;"><font style="color:#1B9D7D;">광고</font>를 <br>구매 등록하시겠습니까?</h3>

                                            <div class="form-wrap">
                                                <form action="/">
                                                    <div class="form-row" style="margin-bottom: 15px;">
                                                        <p class="form-label">광고선택: </p>
                                                        <select name="pm_addr" class="form-text js-find-loc" style="border: 1px solid #57ccaf;">
                                                          <option value="">광고선택</option>
                                                          <option value="C001">추천공고등록</option>
                                                          <option value="C002">점프업(4시간)</option>
                                                          <option value="C003">점프업(8시간)</option>
                                                          <option value="C004">지금뜨는 업체</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-row" style="margin-bottom: 15px;">
                                                        <p class="form-label">광고지역: </p>
                                                        <input id="ad_addr1" name="ad_addr1" style="display:block; width: 300px; height: 36px; margin-top: 0px; border:1px solid #57ccaf;" onclick="$('#changeArea').show();">
                                                    </div>
                                                    <div class="form-row" style="margin-bottom: 15px;">
                                                      <span style="color: #57ccaf;">광고시작일</span><br>
                                                      <input id="ad_startdate1" name="ad_startdate1" class="form-text js-datepicker" style="display: inline-block; width: 90px; min-width: 90px; height: 36px; margin-top: 0px; border: 1px solid #57ccaf; text-align: center; background-color: #fff; background-size: 10px auto; padding: 0; background-position: 112px 14px;" value="<?=date("Y-m-d")?>" onchange="fMakeDuration();">
                                                      <span style="display: inline-block; width: 30px; text-align: center;">일</span>
                                                      <select id="ad_starthour1" name="ad_starthour1" style="display: inline-block; width: 60px; min-width: 60px; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 0px; text-align:center;">
                                                        <?php
                                                          for($h = 0; $h <=23; $h++) {
                                                              echo "<option value=\"" . $h . "\" ".($h==99?"selected":"").">" . $h . "</option>";
                                                          }
                                                        ?>
                                                      </select><span style="display: inline-block; width: 30px; text-align: center;">시</span>
                                                      <input id="ad_startmin1" name="ad_startmin1" style="display:inline-block; width: 50px; height: 36px; margin-top: 0px; border:1px solid #57ccaf; text-align:center;" value="0"><span style="display: inline-block; width: 30px; text-align: center;">분</span>
                                                    </div>
                                                    <div class="form-row" style="margin-bottom: 15px;">
                                                      <span style="color: #57ccaf;">광고기간</span><br>
                                                      <select id="ad_duration1" name="ad_duration1" style="display: inline-block; width: 70px; min-width: 70px; line-height: 36px; height: 36px; border: 1px solid #57ccaf; margin-top: 0px;" onchange="fMakeDuration();">
                                                        <option value="2">2일</option>
                                                        <option value="5">5일</option>
                                                        <option value="10">10일</option>
                                                        <option value="20">20일</option>
                                                        <option value="30">30일</option>
                                                      </select>
                                                      <span id="ad_duration_txt1"></span>
                                                    </div>
                                                    <input type="hidden" id="rc_idx">
                                                    <input type="hidden" id="mm_idx">
                                                </form>
                                            </div>
                                        </div>

                                        <div class="promo-footer" style="margin-top:20px;">
                                            <button type="button" class="btn btn-secondary" style="width:40%; float:left; margin-right: 10%; border: 1px solid #ccc; height: 54px;" onclick="fBuyFree('free');">무료로 공고등록</button>
                                            <button type="button" data-code="C001" data-point="4000" class="btnBuy btn btn-primary" style="width:50%;" onclick="fBuyFree('pay');">포인트 차감하고<br>공고등록</button>
                                        </div>
                                    </div>
                                </div>
</div>

<div id="popupConf1" style="display:none; position: fixed; top: 49%; left: calc(50% - 150px); height: 250px; width: 300px; background-color: #fff; z-index: 20; padding: 20px; border: 1px solid #ccc; box-shadow: 0px 0px 20px 10px;">
  <div class="popup-content">
      <div class="promo-view" style="width: 280px;">
        <div class="promo-content">
            <h3 class="secondary-title" style="text-align: center; line-height: 24px; margin-bottom: 20px; font-size: 1.2em; font-weight: 600;"><font style="color:#1B9D7D;" id="ad_prod_name">추천공고등록</font>을 <br>구매하시겠습니까?</h3>

            <div class="form-wrap">
                <form action="/">
                    <div class="form-row">
                        <p class="form-label">*광고지역: <span id="ad_area_pop1">경기 군포시 금정동</span></p>
                    </div>
                    <div class="form-row">
                        <p class="form-label">*광고기간: <span id="ad_duration_pop1">2024-05-27 부터 2024-05-29 까지<br><span style="margin-left:73px;">2일 결제(+1일)</span></span></p>
                    </div>
                    <div class="form-row">
                        <p class="form-label">*차감포인트: <span id="ad_point_pop1">1000P</span></p>
                        <input type="hidden" id="ad_point">
                    </div>
                </form>
            </div>
        </div>

        <div class="promo-footer" style="margin-left: -21px; margin-top: 37px; width: 300px;">
            <button type="button" class="btn btn-secondary" style="width:calc(50% - 0px); float:left; background-color: #ccc; border-radius: 0;" onclick="$('#popupConf1').hide();">취소</button>
            <button type="button" class="btnBuy btn btn-primary" style="width:calc(50% - 0px); border-radius: 0;" onclick="fBuy();">확인</button>
        </div>
    </div>
  </div>
</div>
<style>
#changeArea {
    position: fixed;
    display: none;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: 100%;
    z-index: 30;
}
.panel-container {
    max-width: 500px;
    margin: 0 auto;
    bottom: 0;
    position: absolute;
    left: 0;
    right: 0;
    -webkit-box-orient: vertical;
    background-color: #fff;
    color: #333;
    border-radius: 10px 10px 0 0;
}
.panel-header h2 { display: none; }
.panel-body { height: 700px; overflow-y: auto; }
.btnCloseArea { position: absolute;
    top: 18px;
    right: 19px;
    width: 16px;
    height: 16px;
    border: 0;
    background: url(https://nowjob.co.kr/resource/shop/web/img/ic_alert_close.svg) no-repeat 0 0;
    background-size: contain;
    z-index: 1;}
.btnCloseArea span { display: none; }
.primary-title { font-size: 35px; font-weight: 700; text-align: center; margin: 0 0 62px; }
.primary-title em { color: #1B9D7D; }
.panel-container .form-label {
    display: block;
    margin: 0 0 12px;
    font-weight: 700;
    word-break: keep-all;
}
.search-input { position: relative; }
#txt_addr { border: none;
    border-bottom: 1px solid #aaa;
    width: 450px;
    font-size: 16px;
}
#btnSearchAddr { position: absolute; right: 0px; top: 4px; }
#btnSearchAddr img { width: 24px; }
#btnGps { display: none; }
.post-tip { padding: 20px 0; }
.post-tip .title { font-weight: 600; }
.post-tip dl { margin-top: 20px; }
.post-tip dl dt { font-weight: 600; }
.post-tip dl dd { margin-bottom: 20px; }
.post-tip .desc { color: #777; font-size: 13px; line-height: 1.36; }
.result-wrap { /* max-height: 300px; overflow-y: scroll; */ }
.result-wrap .sr-text { display: none; }
.result-wrap .post-code button { font-weight: 600; color: #1B9D7D; }
.result-wrap .address dt { float:left; width: 50px; color: #1B9D7D; border: 1px solid #1B9D7D; line-height: 28px; height: 33px; padding: 3px 5px; font-size: 12px; text-align: center; }
.result-wrap .address dd { float:left; width:calc(100% - 50px); }
.result-wrap .post-list li { float:left; width:100%; }
</style>
<?php include_once (get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>
