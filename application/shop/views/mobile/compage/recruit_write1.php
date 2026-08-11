<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고등록</h1>
        <ol class="progress-steps">
            <li title="헌재위치" class="active">1</li>
            <li>2</li>
            <li>3</li>
            <li>4</li>
            <li>5</li>
        </ol>
    </div><!-- //top-bar -->
    <div class="page-content">
        <form id="frm" onsubmit="return false" autocomplete="false">
        	<input type="hidden" id="rc_idx" name="rc_idx" value="<?=$info["rc_idx"]?>" />
			<input type="hidden" id="ad_zipcode" name="ad_zipcode" value="<?=$info["addr"]["ad_zipcode"]?>" />
			<input type="hidden" id="ad_jibun" name="ad_jibun" value="<?=$info["addr"]["ad_jibun"]?>" />
			<input type="hidden" id="ad_doro" name="ad_doro" value="<?=$info["addr"]["ad_doro"]?>" />
			<input type="hidden" id="ad_detail" name="ad_detail" value="<?=$info["addr"]["ad_detail"]?>" />
			<input type="hidden" id="ad_addr" name="ad_addr" value="<?=$info["addr"]["ad_addr"]?>" />
			<input type="hidden" id="ad_gps_x" name="ad_gps_x" value="<?=$info["addr"]["ad_gps_x"]?>" />
			<input type="hidden" id="ad_gps_y" name="ad_gps_y" value="<?=$info["addr"]["ad_gps_y"]?>" />

            <div class="section addition-info-form">
                <div class="container">
                    <div class="form-row">
                        <p class="form-label">공개여부</p>
                        <div class="form-toggle-group col-2">
                        	<label class="form-toggle-btn2 ">
                                <input type="radio" name="rc_show_yn" value="Y" <?=$info["rc_show_yn"]!="N" ? "checked":""?> >
                                <span class="btn">공개</span>
                            </label>
                            <label class="form-toggle-btn2 ">
                                <input type="radio" name="rc_show_yn"  value="N" <?=$info["rc_show_yn"]=="N" ? "checked":""?> >
                                <span class="btn">비공개</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <label>
                            <span class="form-label">공고제목<em>최대 30자</em></span>
                            <input type="text" name="rc_name" placeholder="공고명을 작성해주세요" value="<?=$info["rc_name"]?>" maxlength="30" autocomplete="off" aria-required="true" required class="form-text">
                        </label>
                    </div>

                    <div class="form-row">
                        <label>
                            <span class="form-label">채용공고기간<em>모집일 기준 최대 2개월까지 공고가 가능합니다.</em></span>
                            <input type="text" name="rc_date" aria-required="true" required class="form-text js-datepicker">

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
                                        mode: 'range',
                                        locale: 'ko',
                                        dateFormat: 'Y-m-d',
                                        ariaDateFormat: 'Y-m-d',
                                        defaultDate : ['<?=$info["rc_sdate"]!="" && get_date($info["rc_sdate"]) < Date("Y-m-d") ? Date("Y-m-d"):get_date($info["rc_sdate"]) ?>','<?=get_date($info["rc_edate"])?>'],
                                        altFormat: 'Y.m.d',
                                        altInput: true,
                                        nextArrow: '<span class="sr-text">다음</span>',
                                        prevArrow: '<span class="sr-text">이전</span>',
                                        disableMobile: true,
                                        minDate: 'today',
                                        maxDate: new Date().fp_incr(60),//2개월 60일 제한
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

                    <div class="form-row address">
                        <p class="form-label">주소등록</p>
                        <div class="form-row">
                            <!-- 현재 위치 -->
                            <div class="current-area">
                                <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel rc_addr"><?=$info["addr"]["ad_addr"]?></a>
                            </div>
                        </div>
                        <div class="form-row">
                            <label>
                                <span class="sr-text">상세주소1</span>
                                <input type="text" name="rc_addr_doro" value="<?=$info["addr"]["ad_doro"]?>" autocomplete="off" aria-required="true" required class="form-text">
                            </label>
                        </div>
                        <div class="form-row">
                            <label>
                                <span class="sr-text">상세주소2</span>
                                <input type="text" name="rc_addr_detail" value="<?=$info["addr"]["ad_detail"]?>" placeholder="상세주소를 입력해주세요" autocomplete="off" aria-required="true" required class="form-text">
                            </label>
                        </div>
                    </div>

                    <div class="sticky-bottom view-footer">
                        <button id="btnStep" type="button" class="btn btn-primary">다음</button>
                    </div>
                </div>
            </div>
        </form>
    </div><!-- //page-content -->
</div>

<script>
	// 검색 완료 선택 처리
	function selectAddrInfo(zipcode,doro,jibun,addr,x,y){
		var addr_short = (typeof fRemoveBigAdrJS === 'function') ? (fRemoveBigAdrJS(addr) || addr) : addr;
		$("#ad_zipcode").val(zipcode);
		$("#ad_jibun").val(doro);
		$("#ad_doro").val(jibun);
		$("#ad_addr").val(addr_short);
		$("#ad_gps_x").val(x);
		$("#ad_gps_y").val(y);

		$("[name=rc_addr_doro]").val(doro);
		$(".rc_addr").text(addr_short);

		$("#changeArea").removeClass("on");
	}
  window.addEventListener('DOMContentLoaded', function() {  
		//처리
		$("#btnStep").click(function(){

			if($("[name='rc_name']").val()==""){
	            commonAlert("공고제목을 입력해주세요.",function(){
	        		$("[name='rc_name']").focus();
	            });
	            return;
	        }

	        if($("[name='rc_date']").val()==""){
	            commonAlert("채용공고기간을 선택해주세요.",function(){});
	            return;
	        }

	        if($("[name='ad_addr']").val()==""){
	            commonAlert("주소를 등록해주세요.",function(){});
	            return;
	        }

	        if($("[name='rc_addr_doro']").val()==""){
	            commonAlert("주소를 등록해주세요.",function(){});
	            return;
	        }


	        var rc_work_img = "";
            for(var icnt=0;icnt<$("[name='rc_work[]']:checked").length;icnt++){
            	if(rc_work_img==""){
            		rc_work_img = $("[name='rc_work[]']:checked").eq(icnt).data("img");
            	}else{
            		rc_work_img += "," + $("[name='rc_work[]']:checked").eq(icnt).data("img");
            	}
            }
			$("[name=rc_work_img]").val(rc_work_img);
			$("#ad_detail").val($("[name=rc_addr_detail]").val());

	        var params = $("#frm").serialize();
	        comAjax("/compage_proc/recruit_write1",params,function(ret){
	            if(ret.code=="00"){
	            	if($("[name=rc_idx]").val()!="0"){
	            		location.href="/compage/recruit_write2/" + $("[name=rc_idx]").val();
	            		//_replace("/compage/recruit_write2/" + $("[name=rc_idx]").val());
	            	}else{
	            		location.href="/compage/recruit_write2";
	            		//_replace("/compage/recruit_write2");
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
</script>
<?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>
