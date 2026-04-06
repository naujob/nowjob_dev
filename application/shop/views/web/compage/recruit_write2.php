<script type="text/javascript" src="/resource/shop/web/smarteditor2/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<div id="content" class="page page-mypage">
	<main class="page-content">
        <div class="container">
            <?php $tab_on = "tab2";?>
            <?php $tab_on2 = "menu2";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <style>
            	.foldbtn { display:none; position: absolute; top: 0px; right: 0px; cursor: pointer; }
							.foldbtn img.upside { height: 18px; margin-top: 5px; -ms-transform: rotate(270deg); -webkit-transform: rotate(270deg); transform: rotate(270deg); }
							.foldbtn img.dwside { height: 18px; margin-top: 5px; -ms-transform: rotate(90deg); -webkit-transform: rotate(90deg); transform: rotate(90deg); }
							.form-text { font-size: 16px; }
							.noti_duration .form-text { width: 320px; }
							.form-selectbox select  { font-size: 16px; }
							.form-table .form-selectbox { width: 80px; }
							.form-row.starting-date .value input { width: 320px; padding-left: 0; text-align: left; }
						</style>
            <div class="tab-panel on">
                <div class="manage-my-content">
                    <form id="frm" onsubmit="return false" autocomplete="false">
			        	<input type="hidden" id="rc_idx" name="rc_idx" value="<?=($_GET["gbn"]=="re" ? "0" : $info["rc_idx"])?>" />
						<input type="hidden" id="ad_zipcode" name="ad_zipcode" value="<?=$info["addr"]["ad_zipcode"]?>" />
						<input type="hidden" id="ad_jibun" name="ad_jibun" value="<?=$info["addr"]["ad_jibun"]?>" />
						<input type="hidden" id="ad_doro" name="ad_doro" value="<?=$info["addr"]["ad_doro"]?>" />
						<input type="hidden" id="ad_detail" name="ad_detail" value="<?=$info["addr"]["ad_detail"]?>" />
						<input type="hidden" id="ad_addr" name="ad_addr" value="<?=$info["addr"]["ad_addr"]?>" />
						<input type="hidden" id="ad_gps_x" name="ad_gps_x" value="<?=$info["addr"]["ad_gps_x"]?>" />
						<input type="hidden" id="ad_gps_y" name="ad_gps_y" value="<?=$info["addr"]["ad_gps_y"]?>" />
						<input type="hidden" id="mm_comp_num" name="mm_comp_num" value="<?=$info["mm_comp_num"]?>" />
						<input type="hidden" id="mm_comp_num_org" name="mm_comp_num_org" value="<?=$info["mm_comp_num"]?>" />

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
                                    <p class="form-label"><span class="fixed">*</span>공개여부</p>
                                    <div class="form-group">
			                            <label class="form-toggle-btn2">
			                                <input type="radio" name="rc_show_yn" value="Y" <?=$info["rc_show_yn"]!="N" ? "checked":""?> >
			                                <span class="btn">공개</span>
			                            </label>
			                            <label class="form-toggle-btn2">
			                                <input type="radio" name="rc_show_yn"  value="N" <?=$info["rc_show_yn"]=="N" ? "checked":""?> >
			                                <span class="btn">비공개</span>
			                            </label>
                                    </div>
                                </div>
                                <div class="form-row">
                                	<span class="foldbtn" id="btn2" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label"><span class="fixed">*</span>공고제목 <em>(최대 30자)</em></p>
                                    <div class="value">
                                        <label>
                                            <span class="sr-text">공고제목 입력</span>
                                            <input style="width:580px;" type="text" name="rc_name" placeholder="공고명을 작성해주세요" value="<?=$info["rc_name"]?>" placeholder="공고명을 작성해주세요" autocomplete="off" aria-required="true" required class="form-text">
											<div onclick="$('#recruiteListBox').toggle();" style="position: absolute; top: 33px; left: 808px; /* border: 1px solid #999; */ padding: 11px 5px 0 5px; border-radius: 6px;"><img src="/resource/shop/web/img/ic_arrow_down.svg"></div>
											<div id="recruiteListBox" style="display:none; width: 580px; max-height: 120px; border: 1px solid #ccc; overflow-y: auto; overflow-x: hidden; background-color:#fff; border-radius: 8px; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2), -5px 5px 10px rgba(0, 0, 0, 0.2), 0px 10px 10px rgba(0, 0, 0, 0.2); ">
												<script>
												var recruiteJson = {};
												function fSet(idx){
													console.log(recruiteJson[idx]);
													$('input[name="rc_name"]').val(recruiteJson[idx]['rc_name']);
													console.log(recruiteJson[idx]['rc_work']);

													var rc_work = recruiteJson[idx]['rc_work'];
													var work_checkboxes = document.querySelectorAll('input[type="checkbox"][name="rc_work[]"]');
													var rc_workValues = rc_work.split(',');
													for (var i = 0; i < work_checkboxes.length; i++) {
													    if (rc_workValues.includes(work_checkboxes[i].value)) {
													        work_checkboxes[i].checked = true;
													    }else{
													        work_checkboxes[i].checked = false;
														}
													}

													var rc_work_type = recruiteJson[idx]['rc_work_type'];
													var work_type_checkboxes = document.querySelectorAll('input[type="checkbox"][name="rc_work_type[]"]');
													var rc_work_typeValues = rc_work_type.split(',');
													for (var i = 0; i < work_type_checkboxes.length; i++) {
													    if (rc_work_typeValues.includes(work_type_checkboxes[i].value)) {
													        work_type_checkboxes[i].checked = true;
													    }else{
													        work_type_checkboxes[i].checked = false;
													    }
													}

													var rc_price_type = recruiteJson[idx]['rc_price_type'];
													var price_type_checkboxes = document.querySelectorAll('input[type="checkbox"][name="rc_price_type[]"]');
													var rc_price_typeValues = rc_price_type.split(',');
													for (var i = 0; i < price_type_checkboxes.length; i++) {
													    if (rc_price_typeValues.includes(price_type_checkboxes[i].value)) {
													        price_type_checkboxes[i].checked = true;
													    }else{
													        price_type_checkboxes[i].checked = false;
													    }
													}

													$('#rc_desc').text($('#rc_desc_'+idx).text());

													$('#recruiteListBox').hide();

												}
												</script>
												<ul style=" width: 580px;">
													<?php $recruite_idx = 0; ?>
													<?php foreach($rc_last_recruite_list as $row) : ?>
														<script>
														recruiteJson[<?=$recruite_idx?>] = { idx: <?=$recruite_idx?>, rc_name: '<?=$row["rc_name"]?>', rc_work: '<?=$row["rc_work"]?>', rc_work_type: '<?=$row["rc_work_type"]?>', rc_price_type: '<?=$row["rc_price_type"]?>' };
														</script>
														<li style="list-style: none; width: 100%; height: 30px; line-height: 30px; /* border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-right: 1px solid #ccc; */ padding-left: 10px; " onclick="fSet('<?=$recruite_idx?>');"><?=$row["rc_name"]?></li>
													<?php 	$recruite_idx++; ?>
		                            				<?php endforeach;?>
												</ul>
<!--												<select onchange="fSet(this.value);" style="width: 580px; line-height: 30px; height: 40px; border: 1px solid #ccc; padding: 5px;">
													<?php $recruite_idx = 0; ?>
													<option value=''>공고선택</option>
													<?php foreach($rc_last_recruite_list as $row) : ?>
														<script>
														recruiteJson[<?=$recruite_idx?>] = { idx: <?=$recruite_idx?>, rc_name: '<?=$row["rc_name"]?>', rc_work: '<?=$row["rc_work"]?>', rc_work_type: '<?=$row["rc_work_type"]?>', rc_price_type: '<?=$row["rc_price_type"]?>' };
														</script>
														<option value='<?=$recruite_idx?>'><?=$row["rc_name"]?></option>
													<?php 	$recruite_idx++; ?>
		                            				<?php endforeach;?>

												</select> -->

													<?php $recruite_idx = 0; ?>
													<?php foreach($rc_last_recruite_list as $row) : ?>
															<textarea id="rc_desc_<?=$recruite_idx?>" style="display:none;"><?=$row["rc_desc"]?></textarea>
													<?php 	$recruite_idx++; ?>
		                            				<?php endforeach;?>
											</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-row">
                                	<span class="foldbtn" id="btn3" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label"><span class="fixed">*</span>채용공고기간</p>
                                    <div class="value">
                                        <div class="inline-group">
                                            <div class="recruit-range noti_duration">
                                                <label>
                                                    <span class="sr-text">채용공고기간 입력</em></span>
                                                    <input type="text" name="rc_date" aria-required="true" required class="form-text js-datepicker2" style="font-size: 16px;">
                                                </label>
                                            </div>
                                            <p class="help-msg text-only">모집일 기준 최대 2개월까지 공고가 가능합니다.</p>
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
                                                flatpickr('.recruit-range .js-datepicker2', {
                                                    mode: 'range',
                                                    locale: 'ko',
                                                    dateFormat: 'Y-m-d',
                                                    ariaDateFormat: 'Y-m-d',
                                                    altFormat: 'Y.m.d',
                                                    altInput: true,
																										<?php if($_GET["gbn"]=="re"){ ?>
																											defaultDate : ['<?=Date("Y-m-d")?>','<?=Date("Y-m-d",strtotime("2 months"))?>'],
																										<?php }else{ ?>
																											defaultDate : ['<?=$info["rc_sdate"]!="" && get_date($info["rc_sdate"]) < Date("Y-m-d") ? Date("Y-m-d"):get_date($info["rc_sdate"]) ?>','<?=get_date($info["rc_edate"])?>'],
																										<?php } ?>
                                                    nextArrow: '<span class="sr-text">다음</span>',
                                                    prevArrow: '<span class="sr-text">이전</span>',
                                                    disableMobile: true,
                                                    minDate: 'today',
                                                    maxDate: new Date().fp_incr(60),//2개월 60일 제한
                                                    onMonthChange: function (selectedDates, dateStr, instance) {
                                                        chgMonth(this);
                                                    },
																										onYearChange: function (selectedDates, dateStr, instance) {
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
                                	<span class="foldbtn" id="btn4" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label"><span class="fixed">*</span>근무지역 <em>(최대 30자)</em></p>
                                    <div class="value">
                                        <!-- 현재 위치 -->
                                        <label class="row">
                                            <input type="text" hidden name="changeArea" value="<?=$info["addr"]["ad_addr"]?>" readonly aria-required="true" required class="form-text js-find-loc">
                                        </label>
                                        <!-- 시도 -->
                                        <label class="row">
                                            <span class="sr-text">상세주소1</span>
                                            <input type="text" style="width:580px;" name="rc_addr_doro" value="<?=$info["addr"]["ad_doro"]?>"  autocomplete="off" aria-required="true" required class="form-text">
                                        </label>
                                        <label class="row">
                                            <span class="sr-text">상세주소2</span>
                                            <input type="text" style="width:580px;" name="rc_addr_detail" value="<?=$info["addr"]["ad_detail"]?>" placeholder="상세주소 입력" autocomplete="off" aria-required="true" required class="form-text">
                                        </label>
                                    </div>
                                </div>

                                <div class="form-row">
                                	<span class="foldbtn" id="btn5" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label"><span class="fixed">*</span>업무구분 <em>최대 2개 선택가능</em></p>
                                    <div class="form-group">
                                        <?php foreach($rc_wrok_list as $row) : ?>
			                            <label class="form-toggle-btn2">
			                                <input type="checkbox" name="rc_work[]" data-select-limit="2" value="<?=$row["cd_val"]?>" data-img="<?=$row["cd_val2"]?>" <?=in_array_return($row["cd_val"],$info["rc_work"],"check")?>>
			                                <span class="btn"><?=$row["cd_val"]?></span>
			                            </label>
			                            <?php endforeach;?>
                                    </div>
                                </div>

                                <div class="form-row">
                                	<span class="foldbtn" id="btn6" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label">희망성별 <em>다중선택가능</em></p>
                                    <div class="form-group">
                                        <label class="form-toggle-btn2">
			                                <input type="checkbox" name="rc_sex[]" value="남자" <?=in_array_return("남자",$info["rc_sex"],"check")?>>
			                                <span class="btn">남자</span>
			                            </label>
			                            <label class="form-toggle-btn2">
			                                <input type="checkbox" name="rc_sex[]" value="여자" <?=in_array_return("여자",$info["rc_sex"],"check")?>>
			                                <span class="btn">여자</span>
			                            </label>
                                    </div>
                                </div>

                                <div class="form-row">
                                	<span class="foldbtn" id="btn7" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label">희망국적 <em>다중선택가능</em></p>
                                    <div class="form-group">
                                        <label class="form-toggle-btn2">
			                                <input type="checkbox" name="rc_nation[]" value="내국인" <?=in_array_return("내국인",$info["rc_nation"],"check")?>>
			                                <span class="btn">내국인</span>
			                            </label>
			                            <label class="form-toggle-btn2">
			                                <input type="checkbox" name="rc_nation[]" value="외국인" <?=in_array_return("외국인",$info["rc_nation"],"check")?>>
			                                <span class="btn">외국인</span>
			                            </label>
                                    </div>
                                </div>

                                <div class="form-row">
                                	<span class="foldbtn" id="btn8" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label">희망나이</p>
                                    <div class="value">
                                        <div class="inline-group" style="display: -webkit-box;">
                                            <label class="form-selectbox col">
                                                <span class="sr-text">최소나이 선택</span>
                                                <select name="rc_sage" style="width:80px;height:30px;padding-left:12px;">
									                <option value="">선택</option>
									                <?php for($icnt=20;$icnt <= 80;$icnt++) : ?>
									                <option value="<?=$icnt?>" <?=$info["rc_sage"]==$icnt ? "selected":""?>><?=$icnt?>세</option>
									                <?php endfor;?>
									            </select>
                                                <span class="liner"></span>
                                            </label>
                                            <span class="entity">~</span>
                                            <label class="form-selectbox col">
                                                <span class="sr-text">최대나이 선택</span>
                                                <select name="rc_eage" style="width:80px;height:30px;padding-left:12px;">
									                <option value="">선택</option>
									                <?php for($icnt=20;$icnt <= 80;$icnt++) : ?>
									                <option value="<?=$icnt?>" <?=$info["rc_eage"]==$icnt ? "selected":""?>><?=$icnt?>세</option>
									                <?php endfor;?>
									            </select>
                                                <span class="liner"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                	<span class="foldbtn" id="btn9" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label"><span class="fixed">*</span>근무형태 <em>다중선택가능</em></p>
                                    <div class="form-group">
                                        <?php foreach($rc_work_type_list as $key=> $row) : ?>
			                            <label class="form-toggle-btn2">
			                                <input type="checkbox" id="rc_work_type_<?=$key?>" name="rc_work_type[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["rc_work_type"],"check")?>>
			                                <span class="btn"><?=$row["cd_val"]?></span>
			                            </label>
			                            <?php endforeach;?>
                                    </div>
																		<span id="work_type_memo" style="display:none; margin-left: 20px;">근무협의</span>
                                </div>

																<div class="form-row" id="time_cont1"  <?=(array_search("주간",$info["rc_work_type"])!==false || array_search("2교대",$info["rc_work_type"])!==false) ? "":"hidden"?>>
                                	<span class="foldbtn" id="btn10" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label" style="text-align:right;padding-right:50px;font-weight:normal;font-size:16px;">근무시간(주간)</p>
																			<div class="value" style="position: absolute; top: 26px; left: 258px; width: 450px;">
                                        <div class="inline-group" style="/* display: block; */">
                                            <label class="form-selectbox col" style="width: 200px;">
                                                <span class="sr-text">시간 선택</span>
																								<select id="rc_am_stime_h" name="rc_am_stime_h" style="float:left; width:60px;height:30px; text-align:center; color: #000; border: 1px solid #ADAEB9; border-radius: 10px; position:relative; opacity: initial; appearance: auto;">
                                                  	<!-- <option value="">시</option> -->
																										<?php for($t1=0;$t1<=23;$t1++){ ?>
																		                	<option value="<?=str_pad($t1, 2, "0", STR_PAD_LEFT)?>" <?=substr($info["rc_am_stime"],0,2)==str_pad($t1, 2, "0", STR_PAD_LEFT) ? "selected":($t1==9?"selected":"")?>><?=str_pad($t1, 2, "0", STR_PAD_LEFT)?></option>
																										<?php } ?>
          	                                    </select>
																								<span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">시</span>
																								<input type="text" name="rc_am_stime_m" value="<?=substr($info["rc_am_stime"],3,2)?>" placeholder="직접입력" style="float:left; width: 60px; height: 30px; text-align: center; background-color: #fff; border: 1px solid #ADAEB9; border-radius: 10px;">
																								<span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">분</span>
																								<input type="hidden" name="rc_am_stime" value="<?=$info["rc_am_stime"]?>">
																								<!--
                                                <select name="rc_am_stime" style="width:100%;height:30px;padding-left:12px;">
																		                <option value="">선택</option>
																		                <?php foreach($time_list as $time) : ?>
																		                	<option value="<?=$time?>" <?=$info["rc_am_stime"]==$time ? "selected":""?>><?=$time?></option>
																										<?php endforeach;?>
																								</select> -->
                                                <span class="liner" style="display:none;"></span>
                                            </label>
                                            <span class="entity" style="display: inline-block;margin-right: 30px;">~</span>
                                            <label class="form-selectbox col">
                                                <span class="sr-text">시간 선택</span>
																								<select id="rc_am_etime_h" name="rc_am_etime_h" style="float:left; width:60px;height:30px; text-align:center; color: #000; border: 1px solid #ADAEB9; border-radius: 10px; position:relative; opacity: initial; appearance: auto;">
                                                  	<!-- <option value="">시</option> -->
																										<?php for($t2=0;$t2<=23;$t2++){ ?>
																		                	<option value="<?=str_pad($t2, 2, "0", STR_PAD_LEFT)?>" <?=substr($info["rc_am_etime"],0,2)==str_pad($t2, 2, "0", STR_PAD_LEFT) ? "selected":($t2==18?"selected":"")?>><?=str_pad($t2, 2, "0", STR_PAD_LEFT)?></option>
																										<?php } ?>
          	                                    </select>
																								<span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">시</span>
																								<input type="text" name="rc_am_etime_m" value="<?=substr($info["rc_am_etime"],3,2)?>" placeholder="직접입력" style="float:left; width: 60px; height: 30px; text-align: center; background-color: #fff; border: 1px solid #ADAEB9; border-radius: 10px;">
																								<span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">분</span>
																								<input type="hidden" name="rc_am_etime" value="<?=$info["rc_am_etime"]?>">
																								<!--
                                                <select name="rc_am_etime" style="width:100%;height:30px;padding-left:12px;">
																		                <option value="">선택</option>
																										<?php foreach($time_list as $time) : ?>
																						                <option value="<?=$time?>" <?=$info["rc_am_etime"]==$time ? "selected":""?>><?=$time?></option>
																										<?php endforeach;?>
																								</select> -->
                                                <span class="liner" style="display:none;"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
																<div class="form-row" id="time_cont2" <?=(array_search("야간",$info["rc_work_type"])!==false || array_search("2교대",$info["rc_work_type"])!==false) ? "":"hidden"?>>
                                	<span class="foldbtn" id="btn11" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label" style="text-align:right;padding-right:50px;font-weight:normal;font-size:16px;">근무시간(야간)</p>
																			<div class="value" style="position: absolute; top: 26px; left: 258px; width: 450px;">
                                        <div class="inline-group" style="/* display: block; */">
                                            <label class="form-selectbox col" style="width: 200px;">
                                                <span class="sr-text">시간 선택</span>
																								<select id="rc_pm_stime_h" name="rc_pm_stime_h" style="float:left; width:60px;height:30px; text-align:center; color: #000; border: 1px solid #ADAEB9; border-radius: 10px; position:relative; opacity: initial; appearance: auto;">
                                                  	<!-- <option value="">시</option> -->
																										<?php for($t1=0;$t1<=23;$t1++){ ?>
																		                	<option value="<?=str_pad($t1, 2, "0", STR_PAD_LEFT)?>" <?=substr($info["rc_pm_stime"],0,2)==str_pad($t1, 2, "0", STR_PAD_LEFT) ? "selected":($t1==21?"selected":"")?>><?=str_pad($t1, 2, "0", STR_PAD_LEFT)?></option>
																										<?php } ?>
          	                                    </select>
																								<span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">시</span>
																								<input type="text" name="rc_pm_stime_m" value="<?=substr($info["rc_pm_stime"],3,2)?>" placeholder="직접입력" style="float:left; width: 60px; height: 30px; text-align: center; background-color: #fff; border: 1px solid #ADAEB9; border-radius: 10px;">
																								<span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">분</span>
																								<input type="hidden" name="rc_pm_stime" value="<?=$info["rc_pm_stime"]?>">
																								<span class="liner" style="display:none;"></span>
																								<!--
                                                <select name="rc_pm_stime" style="width:100%;height:30px;padding-left:12px;">
																		                <option value="">선택</option>
																		                <?php foreach($time_list as $time) : ?>
																						                <option value="<?=$time?>" <?=$info["rc_pm_stime"]==$time ? "selected":""?>><?=$time?></option>
																										<?php endforeach;?>
																		            </select>
                                                <span class="liner"></span> -->
                                            </label>
                                            <span class="entity" style="display: inline-block;margin-right: 30px;">~</span>
                                            <label class="form-selectbox col">
                                                <span class="sr-text">시간 선택</span>
																								<select id="rc_pm_etime_h" name="rc_pm_etime_h" style="float:left; width:60px;height:30px; text-align:center; color: #000; border: 1px solid #ADAEB9; border-radius: 10px; position:relative; opacity: initial; appearance: auto;">
                                                  	<!-- <option value="">시</option> -->
																										<?php for($t2=0;$t2<=23;$t2++){ ?>
																		                	<option value="<?=str_pad($t2, 2, "0", STR_PAD_LEFT)?>" <?=substr($info["rc_pm_etime"],0,2)==str_pad($t2, 2, "0", STR_PAD_LEFT) ? "selected":($t2==6?"selected":"")?>><?=str_pad($t2, 2, "0", STR_PAD_LEFT)?></option>
																										<?php } ?>
          	                                    </select>
																								<span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">시</span>
																								<input type="text" name="rc_pm_etime_m" value="<?=substr($info["rc_pm_etime"],3,2)?>" placeholder="직접입력" style="float:left; width: 60px; height: 30px; text-align: center; background-color: #fff; border: 1px solid #ADAEB9; border-radius: 10px;">
																								<span style="float: left; height: 30px; line-height: 30px; padding: 0 7px;">분</span>
																								<input type="hidden" name="rc_pm_etime" value="<?=$info["rc_pm_etime"]?>">
																								<span class="liner" style="display:none;"></span>
																								<!--
                                                <select name="rc_pm_etime" style="width:100%;height:30px;padding-left:12px;">
																		                <option value="">선택</option>
																										<?php foreach($time_list as $time) : ?>
																						                <option value="<?=$time?>" <?=$info["rc_pm_etime"]==$time ? "selected":""?>><?=$time?></option>
																										<?php endforeach;?>
																		            </select>
                                                <span class="liner"></span> -->
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                	<span class="foldbtn" id="btn12" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label">근무요일 <em>다중선택가능</em></p>
                                    <div class="value">
                                        <div class="inline-group week-day">
                                            <div class="form-group">
                                                <?php foreach($rc_week_list as $row) : ?>
					                            <label class="form-toggle-btn2 sm">
					                                <input type="checkbox" name="rc_week[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["rc_week"],"check")?>>
					                                <span class="btn"><?=$row["cd_val"]?></span>
					                            </label>
					                            <?php endforeach;?>
                                            </div>
                                            <div class="option">
                                                <label class="form-checkbox">
                                                    <input type="checkbox" name="rc_week_yn" value="Y" class="sr-text" <?=$info["rc_week_yn"]=="Y" ? "checked":""?>>
                                                    <span class="label">요일무관</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row starting-date">
                                	<span class="foldbtn" id="btn13" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label">근무시작일</p>
                                    <div class="value">
                                        <div class="inline-group">
                                            <p class="is-abs" style="display:none;">시작일</p>
                                            <label>
                                                <span class="sr-text">시작일 입력</span>
                                                <input type="text" name="rc_work_dt" aria-required="true" required class="form-text js-datepicker">
                                            </label>
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
																								<?php
																								$rc_work_dt_defaultDate = $info["rc_work_dt"];
																								if($_GET["gbn"]=="re"){
																									$rc_work_dt_defaultDate = Date("Y-m-d");
																								}
																								?>
                                                flatpickr('.starting-date .js-datepicker', {
                                                    locale: 'ko',
                                                    dateFormat: 'Y-m-d',
                                                    ariaDateFormat: 'Y-m-d',
                                                    altFormat: 'Y.m.d',
                                                    defaultDate : '<?=$rc_work_dt_defaultDate?>',
                                                    altInput: true,
                                                    nextArrow: '<span class="sr-text">다음</span>',
                                                    prevArrow: '<span class="sr-text">이전</span>',
                                                    disableMobile: true,
                                                    position: 'bottom auto',
																										monthSelectorType: 'select', // 월 선택 활성화
        																						//static: true, // 드롭다운 대신 정적 셀렉터
																										yearSelectorType: 'select',
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
																										onReady: function (selectedDates, dateStr, instance) {
																											//오늘버튼 추가
																							        const todayButton = document.createElement("button");
																							        todayButton.type = "button";
																							        todayButton.textContent = "오늘";
																							        todayButton.className = "flatpickr-today-button";
																											todayButton.style = "position: absolute; top: 29px; right: 83px; font-size: 14px; padding: 2px 5px; background-color: #fff; border: 1px solid #ccc; color: #777;";
																											todayButton.addEventListener('click', function() {
																													const today = new Date();
																							            instance.setDate(new Date());

																													// flatpickr 인터페이스 내 연도 및 월을 오늘 날짜로 업데이트
																					                instance.currentYear = today.getFullYear();
																					                instance.currentMonth = today.getMonth();
																					                instance.redraw();  // 달력 갱신

																													$(this).parent().find('.month').html((today.getMonth()+1)+'<small>월</small>');
																													$(this).parent().find('.year').html(today.getFullYear());
																							        });
																							        instance.calendarContainer.appendChild(todayButton);

																											/* //년도선택 시 달력은 변하는데, 달력위의 년도값이 변하지 않아서 일단 remark
																											//년도선택 추가
																											const yearSelect = document.createElement("select");
																							        yearSelect.className = "flatpickr-year-select";

																							        // 현재 연도를 구하고, 전후 10년을 범위로 설정
																							        const currentYear = new Date().getFullYear();
																							        for (let i = currentYear - 10; i <= currentYear + 10; i++) {
																							            const option = document.createElement("option");
																							            option.value = i;
																							            option.text = i;
																							            if (i === currentYear) {
																							                option.selected = true;
																							            }
																							            yearSelect.appendChild(option);
																							        }

																							        yearSelect.addEventListener('change', function() {
																							            instance.currentYear = parseInt(this.value, 10);
																							            instance.redraw();
																							        });

																							        // 달력 컨테이너에 연도 선택 추가
																							        instance.calendarContainer.appendChild(yearSelect);
																											*/

																			        				chgMonth(this);
																			    					}
                                                    //onReady: function () {
                                                    //    chgMonth(this);
                                                    //}
                                                });
                                            });
                                        }());
                                    </script>
                                </div>

                                <div class="form-row">
                                	<span class="foldbtn" id="btn14" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label"><span class="fixed">*</span>급여형태 <em>다중선택가능</em></p>
                                    <div class="value">
                                        <div class="form-group">
                                            <?php foreach($rc_price_type_list as $row) : ?>
				                            <label class="form-toggle-btn2">
				                                <input  class="js-show-amount" type="checkbox"  name="rc_price_type[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["rc_price_type"],"check")?>>
				                                <span class="btn"><?=$row["cd_val"]?></span>
				                            </label>
				                            <?php endforeach;?>
                                        </div>

                                        <div class="addition-option">
                                            <input type="radio" name="opt" id="opt1">
																						<input type="radio" name="opt" id="opt2">
                                            <input type="radio" name="opt" id="opt3">

                                            <div id="amount1" class="row" hidden>
                                                <div class="label">
                                                    <p class="on">일급</p>
                                                    <p><label for="opt3">월급</label></p>
                                                </div>
                                                <label class="inline-group">
                                                    <input type="text" name="rc_day_price" value="<?=number_format($info["rc_day_price"])?>" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    <span class="is-abs">원</span>
                                                </label>
                                            </div>
                                            <div id="amount2" class="row" hidden>
                                                <div class="label">
                                                    <p>일급</p>
                                                    <p>월급</p>
                                                </div>
                                                <label>
                                                    <input type="text" name="payAmount2" value="급여협의" readonly class="form-text align-right">
                                                </label>
                                            </div>
                                            <div id="amount3" class="row" hidden>
                                                <div class="label">
                                                    <p><label for="opt1">일급</label></p>
                                                    <p class="on">월급</p>
                                                </div>
                                                <label class="inline-group">
                                                    <input type="text" name="rc_month_price" value="<?=number_format($info["rc_month_price"])?>" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    <span class="is-abs">원</span>
                                                </label>
                                            </div>
																						<div id="amount4" class="row" style="display:none;">
                                                <div class="label">
                                                    <p class="on">일급</p>
																										<p class="on">주급</p>
                                                    <p><label for="opt3">월급</label></p>
                                                </div>
                                                <label class="inline-group">
																										<input type="text" name="rc_price" value="<?=number_format($info["rc_day_price"])?>" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" style="border-bottom: none; font-size: 18px;">
                                                    <input type="hidden" name="rc_day_price" value="<?=number_format($info["rc_day_price"])?>" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
																										<input type="hidden" name="rc_week_price" value="<?=number_format($info["rc_week_price"])?>" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
																										<input type="hidden" name="rc_month_price" value="<?=number_format($info["rc_month_price"])?>" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    <span class="is-abs" id="rc_price_won">원</span>
																										<input type="hidden" name="rc_price_rep" value="<?=$info["rc_price_rep"]?>" >
																										<input type="hidden" name="rc_price_rep_org" value="<?=$info["rc_price_rep"]?>" >
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="form-row services">
                                	<span class="foldbtn" id="btn15" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label">복리후생</p>
                                    <div class="value">
                                        <p class="secondary-title">보험</p>
                                        <div class="form-group">
                                            <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="국민연금" <?=in_array_return("국민연금",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit1-1">국민연금</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="고용보험" <?=in_array_return("고용보험",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit1-2">고용보험</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="산재보험" <?=in_array_return("산재보험",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit1-3">산재보험</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="건강보험" <?=in_array_return("건강보험",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit1-4">건강보험</span>
			                                </label>
                                        </div>
                                        <p class="secondary-title">휴무/휴가</p>
                                        <div class="form-group">
                                            <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="연차" <?=in_array_return("연차",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit2-1">연차</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="월차" <?=in_array_return("월차",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit2-2">월차</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="여름휴가" <?=in_array_return("여름휴가",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit2-3">여름휴가</span>
			                                </label>
                                        </div>
                                        <p class="secondary-title">수당/퇴직금</p>
                                        <div class="form-group">
                                            <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="야근근로수당" <?=in_array_return("야근근로수당",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit3-1">야근근로수당</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="휴일근로수당" <?=in_array_return("휴일근로수당",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit3-2">휴일근로수당</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="장기근속수당" <?=in_array_return("장기근속수당",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit3-3">장기근속수당</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="연장근로수당" <?=in_array_return("연장근로수당",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit3-4">연장근로수당</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="인센티브제" <?=in_array_return("인센티브제",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit3-5">인센티브제</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="퇴직금" <?=in_array_return("퇴직금",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit3-6">퇴직금</span>
			                                </label>
                                        </div>
                                        <p class="secondary-title">생활편의지원</p>
                                        <div class="form-group">
                                            <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="조식제공" <?=in_array_return("조식제공",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit4-1">조식제공</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="중식제공" <?=in_array_return("중식제공",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit4-2">중식제공</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="석식제공" <?=in_array_return("석식제공",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit4-3">석식제공</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="근무복지급" <?=in_array_return("근무복지급",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit4-4">근무복지급</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="통근버스운행" <?=in_array_return("통근버스운행",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit4-5">통근버스운행</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="차량유류비지원" <?=in_array_return("차량유류비지원",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit4-6">차량유류비지원</span>
			                                </label>
			                                <label class="form-toggle-btn2">
			                                    <input type="checkbox" name="rc_life_type[]" value="기숙사운영" <?=in_array_return("기숙사운영",$info["rc_life_type"],"check")?>>
			                                    <span class="btn benefit-label benefit4-7">기숙사운영</span>
			                                </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                	<span class="foldbtn" id="btn16" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label">상세업무정보 <em>최대500자</em></p>
                                    <div class="value" style="min-height: 398px; height: auto;">
                                        <label>
                                            <span class="sr-text">상세업무정보 입력</span>
                                            <textarea id="rc_desc" name="rc_desc" maxlength="500" class="form-textarea js-autoheight" style="height:300px;"><?=$info["rc_desc"]?></textarea>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-row">
                                	<span class="foldbtn" id="btn17" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label">해시태그</p>
                                    <div class="value">
                                        <label>
																				</label>
                                            <span class="sr-text">상세업무정보 입력</span>
                                            <textarea style="display:none;" name="rc_hash" maxleng="100" placeholder="예) #경력무관, #나이무관 #생산" class="form-textarea sm"><?=$info["rc_hash"]?></textarea>
																						<style>
																						#initHash ul li span { background-color: #fff; color: #333; padding: 5px 15px; border-radius: 17px; }
																						#initHash ul li span.on { background-color: #57CCAF; color: #fff; }
																						</style>
																						<script>
																						function fHashSel(obj){
																							if($(obj).hasClass('on')){
																								$(obj).removeClass('on');
																							}else{
																								$(obj).addClass('on');
																							}
																						}
																						</script>
																						<div id="initHash" style="display: block; width: 100%; float: left;">
																							<ul>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">2교대</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">주간</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">야간</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">생산</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">사무직</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">운전배달</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">물류</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">식당</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">서비스</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">매장관리</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">건설</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">상담</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">디자인</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">고객상담</span></li>
																								<li style="list-style: none; width: fit-content; float: left; margin-right: 10px;"><span onclick="fHashSel(this);">기타</span></li>
																							</ul>
																						</div>
																						<script>
																						var hashInput = '<span style="margin-right:10px; border: none; height: 28px; display: inline-block; line-height: 26px; border-radius: 25px; padding: 0 10px;">#<input type="text" onkeyup="fHashKey(this,event);" style=" min-width: 90px; max-width: 150px;height: 24px; border: none; margin-left: 5px; background-color: transparen;" placeholder="태그 입력(최대 30개)"><span class="hashdel" style="margin-left: 5px; cursor: pointer;display:none;" onclick="fHashDel(this);">ⓧ</span></span>';
																						function fHashDel(obj){
																							if($('#hashbox').find('input').length==1){
																								alert('해시태그 입력란을 삭제 할 수 없습니다.');
																								return;
																							}
																							if($(obj).parent().find('input').first().val()==''){
																								alert('해시태그 입력란을 삭제 할 수 없습니다.');
																								return;
																							}
																							$(obj).parent().remove();
																						}
																						function fHashKey(obj,evt){
																							if(evt.keyCode==13 && $(obj).val().replaceAll(' ','')!=''){
																								$(obj).css('border','none');
																								$(obj).css('background-color','transparent');
																								$(obj).attr('readonly','readonly');
																								$(obj).parent().css('background-color','#ccc');
																								$(obj).parent().css('color','#333');
																								$(obj).parent().find('.hashdel').show();

																								//24.09.04 input의 가로폭 변경 시작
																								var temp = document.createElement('span');
																		            temp.style.visibility = 'hidden';
																		            temp.style.whiteSpace = 'nowrap';
																								temp.style.width = 'fit-content';
																		            temp.style.fontSize = window.getComputedStyle(obj).fontSize;
																		            temp.style.fontFamily = window.getComputedStyle(obj).fontFamily;

																		            // Add input text to the temp element
																		            temp.textContent = obj.value || obj.placeholder;

																		            document.body.appendChild(temp);

																		            // Adjust the input width based on the temp element width
																		            obj.style.width = (temp.offsetWidth + 5) + 'px';
																								obj.style.minWidth = 'auto';

																		            // Remove the temp element
																		            document.body.removeChild(temp);
																								//24.09.04 input의 가로폭 변경 종료

																								$('#hashbox').append(hashInput);
																								$('#hashbox').find('input').last().focus();
																							}
																						}
																						</script>
																						<div id="hashbox" style="display: block; width: 100%; float: left; margin-top: 15px; border: 1px solid #ADAEB9; min-height: 100px; background-color: #fff;padding: 10px;line-height: 38px;">
																							<span style="margin-right:10px; border: none; height: 28px; display: inline-block; line-height: 26px; border-radius: 25px; padding: 0 10px; color:#aaa;">#<input type="text" onkeyup="fHashKey(this,event);" style="min-width: 90px; max-width: 150px;height: 24px; border: none; margin-left: 5px;" placeholder="태그 입력(최대 30개)"><span class="hashdel" style="margin-left: 5px; cursor: pointer;display:none;" onclick="fHashDel(this);">ⓧ</span></span>

																						</div>
																						<span style="display: block; margin-top: 10px;">해시태그 입력 후 엔터키를 눌러 다른 해시태그를 작성할 수 있습니다</span>
                                    </div>
                                </div>
			         									<div class="form-row">
                                	<span class="foldbtn" id="btn18" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
			                        <p class="form-label">기업 대표 이미지</p>
			                        <div class="value">
			                        	<div class="form-pic-group">
				                            <label class="form-pic first">
				                                <input type="file" name="mm_img1" id="mm_img1" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(0);">
				                                <span class="label" style="margin-top: 20px;">기업을 대표하는<br>사진을 첨부해주세요</span>
				                                <span class="preview" id="prev0" onmouseover="$('#delimgbtn0').show();" onmouseout="$('#delimgbtn0').hide();">
				                                	<?php if(getData($mem_info,"mm_img1")!="") : ?>
					                            		<img id="imgbox0" src="<?=getData($mem_info,"mm_img1")?>"/>
					                            		<input type='hidden' name='mm_img1_ori' id='mm_img1_ori' value='<?=getData($mem_info,"mm_img1")?>' />
					                            	<?php endif; ?>
					                            </span>
				                            </label>
				                        </div>
			                        </div>
			                    </div>
                                <div class="form-row">
                                	<span class="foldbtn" id="btn19" onclick="fFold(this);"><img class="dwside" src="/resource/icon/roundtriangle.png"></span>
                                    <p class="form-label">첨부사진</p>
                                    <div class="value">
                                        <div class="form-pic-group">
                                    <label class="form-pic first">
				                                <input type="file" name="rc_img1" id="rc_img1" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(1);">
				                                <span class="label" style="margin-top: 20px;">기업을 대표하는<br>사진을 첨부해주세요</span>
				                                <span class="preview" id="prev1" onmouseover="$('#delimgbtn1').show();" onmouseout="$('#delimgbtn1').hide();">
					                            	<?php if(getData($info,"rc_img1")!="") : ?>
					                            		<img id="imgbox1" src="<?=getData($info,"rc_img1")?>" />
					                            		<input type='hidden' name='rc_img1_ori' id='rc_img1_ori' value='<?=getData($info,"rc_img1")?>' />
					                            	<?php endif; ?>
					                            </span>
				                            </label>
				                            <label class="form-pic">
				                                <input type="file" name="rc_img2" id="rc_img2" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(2);">
																				<span class="label" style="margin-top: 20px;"><!--사진등록--></span>
				                                <span class="preview" id="prev2" onmouseover="$('#delimgbtn2').show();" onmouseout="$('#delimgbtn2').hide();">
					                            	<?php if(getData($info,"rc_img2")!="") : ?>
					                            		<img id="imgbox2" src="<?=getData($info,"rc_img2")?>" onmouseover="$('#delimgbtn2').show();" onmouseout="$('#delimgbtn2').hide();"/>
					                            		<input type='hidden' name='rc_img2_ori' id='rc_img2_ori' value='<?=getData($info,"rc_img2")?>' />
					                            	<?php endif; ?>
					                            </span>
				                            </label>
				                            <label class="form-pic">
				                                <input type="file" name="rc_img3" id="rc_img3" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(3);">
																				<span class="label" style="margin-top: 20px;"><!--사진등록--></span>
				                                <span class="preview" id="prev3" onmouseover="$('#delimgbtn3').show();" onmouseout="$('#delimgbtn3').hide();">
					                            	<?php if(getData($info,"rc_img3")!="") : ?>
					                            		<img id="imgbox3" src="<?=getData($info,"rc_img3")?>" onmouseover="$('#delimgbtn3').show();" onmouseout="$('#delimgbtn3').hide();"/>
					                            		<input type='hidden' name='rc_img3_ori' id='rc_img3_ori' value='<?=getData($info,"rc_img3")?>' />
					                            	<?php endif; ?>
					                            </span>
				                            </label>
				                            <label class="form-pic">
				                                <input type="file" name="rc_img4" id="rc_img4" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(4);">
																				<span class="label" style="margin-top: 20px;"><!--사진등록--></span>
				                                <span class="preview" id="prev4" onmouseover="$('#delimgbtn4').show();" onmouseout="$('#delimgbtn4').hide();">
					                            	<?php if(getData($info,"rc_img4")!="") : ?>
					                            		<img id="imgbox4" src="<?=getData($info,"rc_img4")?>" onmouseover="$('#delimgbtn4').show();" onmouseout="$('#delimgbtn4').hide();"/>
					                            		<input type='hidden' name='rc_img4_ori' id='rc_img4_ori' value='<?=getData($info,"rc_img4")?>' />
					                            	<?php endif; ?>
					                            </span>
				                            </label>
				                            <label class="form-pic">
				                                <input type="file" name="rc_img5" id="rc_img5" accept="image/*" class="js-file-preview" onchange="fDelbtnAdd(5);">
																				<span class="label" style="margin-top: 20px;"><!--사진등록--></span>
				                                <span class="preview" id="prev5" onmouseover="$('#delimgbtn5').show();" onmouseout="$('#delimgbtn5').hide();">
					                            	<?php if(getData($info,"rc_img5")!="") : ?>
					                            		<img id="imgbox5" src="<?=getData($info,"rc_img5")?>" onmouseover="$('#delimgbtn5').show();" onmouseout="$('#delimgbtn5').hide();"/>
					                            		<input type='hidden' name='rc_img5_ori' id='rc_img5_ori' value='<?=getData($info,"rc_img5")?>' />
					                            	<?php endif; ?>
					                            </span>
				                            </label>
                                        </div>
                                    </div>
                                    <p class="help-msg">첫 번쨰 등록된 사진이 채용공고 목록의 썸네일 이미지로 등록됩니다.
																			<span style="display: block; color: #1B9D7D;"> ※ 권장사이즈 : 800 * 800</span>
																		</p>
                                </div>
                                <script>
																function fpaycheck(arg,obj){
																	if($(obj).hasClass('on')){
																		$(obj).removeClass('on');
																		$('[name="rc_price_rep"]').val('');
																	}else{
																		$(obj).addClass('on');
																		if(arg==1){
																			$('#paytype2').removeClass('on');
																			$('#paytype3').removeClass('on');
																			$('[name="rc_price_rep"]').val('일급');
																		}
																		if(arg==2){
																			$('#paytype1').removeClass('on');
																			$('#paytype3').removeClass('on');
																			$('[name="rc_price_rep"]').val('주급');
																		}
																		if(arg==3){
																			$('#paytype1').removeClass('on');
																			$('#paytype2').removeClass('on');
																			$('[name="rc_price_rep"]').val('월급');
																		}
																	}
																	//if(arg==1)
																}
                                    (function () {
                                        /* 옵션 선택시 입력폼 표시,숨김 */
                                        function showAmount (el, id) {
																						//console.log(id);
                                            var inputAll = document.querySelectorAll('.js-show-amount');
                                            var target = document.getElementById(id);
                                            var targetAll = target.parentNode.querySelectorAll('.row');
                                            //var isHide = inputAll[1].checked;//주급 옵션 선택 유무

																						    // targetAll.forEach(function (item, idx) {
                                                //     console.log(idx);
																								// 		console.log(item);
                                                // });

																								var checkcnt = 0;
																								var innerHtml = '';
																								var paytype1before = $('#paytype1').length;
																								var paytype2before = $('#paytype2').length;
																								var paytype3before = $('#paytype3').length;

																								$('.js-show-amount').each(function() {
																								        var isChecked = $(this).is(':checked');
																								        var value = $(this).val();
																								        //console.log(value + ' 체크 여부: ' + isChecked);
																												if(isChecked) checkcnt++;

																												if(value=='일급' && isChecked){
																													if(paytype1before==0 && isChecked){
																														innerHtml += '<p id="paytype1" class="on" onclick="fpaycheck(1, this);"><label for="opt1">일급</label></p>';
																													}else{
																														innerHtml += '<p id="paytype1" class="" onclick="fpaycheck(1, this);"><label for="opt1">일급</label></p>';
																													}
																												}
																												if(value=='주급' && isChecked){
																													if(paytype2before==0 && isChecked){
																														innerHtml += '<p id="paytype2" class="on" onclick="fpaycheck(2, this);"><label for="opt2">주급</label></p>';
																													}else{
																														innerHtml += '<p id="paytype2" class="" onclick="fpaycheck(2, this);"><label for="opt2">주급</label></p>';
																													}
																												}
																												if(value=='월급' && isChecked){
																													if(paytype3before==0 && isChecked){
																														innerHtml += '<p id="paytype3" class="on" onclick="fpaycheck(3, this);"><label for="opt3">월급</label></p>';
																													}else{
																														innerHtml += '<p id="paytype3" class="" onclick="fpaycheck(3, this);"><label for="opt3">월급</label></p>';
																													}
																												}
																						    });


																								if(checkcnt==0){
																									$('#amount4').hide();
																								}else{
																									$('#amount4').show();
																								}
																								if(checkcnt==3){
																									$('#amount4 .label').html('');
																									$('[name=rc_price]').val('급여협의');
																									$('[name=rc_price]').attr('readonly','readonly');
																									$('#rc_price_won').hide();
																								}else if(checkcnt==2){
																									$('#amount4 .label').html(innerHtml);
																									$('[name=rc_price]').val('');
																									$('[name=rc_price]').removeAttr('readonly');
																									$('#rc_price_won').show();
																									$('[name=rc_price]').focus();

																									if($('#paytype1').hasClass('on') && $('#paytype2').hasClass('on')){
																										$('#paytype2').removeClass('on');
																									}
																									if($('#paytype1').hasClass('on') && $('#paytype3').hasClass('on')){
																										$('#paytype3').removeClass('on');
																									}
																									if($('#paytype2').hasClass('on') && $('#paytype3').hasClass('on')){
																										$('#paytype3').removeClass('on');
																									}
																								}else{
																									$('#amount4 .label').html(innerHtml);
																									$('[name=rc_price]').val('');
																									$('[name=rc_price]').removeAttr('readonly');
																									$('#rc_price_won').show();
																									$('[name=rc_price]').focus();
																								}
																								$('[name="rc_price_rep"]').val('');

																								/*
																								<div id="amount4" class="row" hidden>
																										<div class="label">
																												<p class="on">일급</p>
																												<p class="on">주급</p>
																												<p><label for="opt3">월급</label></p>
																										</div>
																										<label class="inline-group">
																												<input type="text" name="rc_price" value="" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
																												<input type="hidden" name="rc_day_price" value="<?=number_format($info["rc_day_price"])?>" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
																												<input type="hidden" name="rc_week_price" value="<?=number_format($info["rc_week_price"])?>" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
																												<input type="hidden" name="rc_month_price" value="<?=number_format($info["rc_month_price"])?>" class="form-text is-currency comma"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
																												<span class="is-abs">원</span>
																										</label>
																								</div>
																								*/
                                            //if (isHide) {
                                            //    targetAll.forEach(function (item, idx) {
                                            //        item.hidden = (idx + 1) == 2 ? false:true;
                                            //    });
                                            //} else
																						// if (id.indexOf('amount2') > -1) { // && !isHide
                                            //     inputAll.forEach(function (item, idx) {
                                            //         targetAll[idx].hidden = item.checked ? false:true;
                                            //     });
                                            // } else {
                                            //     target.hidden = el.checked ? false:true;
                                            // }
                                        }

                                        document.querySelectorAll('.js-show-amount').forEach(function (input, idx) {
                                            var id = 'amount' + (idx + 1);
                                            input.addEventListener('change', function () {
                                                showAmount(this, id);
                                            });
                                            showAmount(input, id);
                                        });
                                    }());
                                </script>

                            </div>
                        </div>

                        <div class="form-action-wrap">
                        	<button id="btnStep" type="button" class="btn-round btn-tertiary"><?=$info["rc_idx"]!="0" ? ($_GET["gbn"]=="re" ? "등록" : "수정"):"등록"?>완료</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->

<div id="dimwrap" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; background-color: #000; opacity: 0.3;"></div>
<div id="dimcont" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 500px; height: 365px; z-index: 20; background-color: #fff; padding: 40px;">
  <form id="frm1" name="frm1" onsubmit="return false">
  <div>
    <div style="font-size: 1.4em; font-weight: 600;">사업자등록번호 <span style="color:#57CCAF">인증</span>이 필요합니다.</div>
    <a href="javascript:$('#dimwrap,#dimcont').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
  </div>
  <div style="font-size: 1.2em; margin-top: 50px;">
    <div>
      <input type="text" name="comp_num" id="bizno" placeholder="사업자 등록번호를 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: calc(100% - 100px);">
      <input type="hidden" id="biznoauth">
      <input type="hidden" name="comp_type" value="COMP">
      <button onclick="fBizAuth();" style="margin-left: 15px; font-size: 16px; padding: 5px 10px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">인증하기</button>
    </div>
    <div style="font-size: 16px; margin-top: 10px; color: #57CCAF">* 사업자등록번호 인증시 인증마크 부여</div>
		<div style="font-size: 16px; margin-top: 10px; color: #57CCAF">* 유료광고(지금뜨는 업체 7일권)</div>
  </div>
  </form>
  <div style="text-align: center;margin-top: 50px;">
      <button onclick="$('#dimcont2').show();" style="font-size: 16px; padding: 10px 16px;background-color: #fff; color: #57CCAF; border: 1px solid #57CCAF">건너뛰기</button>
			<button id="btnSave" style="font-size: 16px; padding: 10px 36px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">확인</button>
  </div>
</div>
<div id="dimcont2" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 500px; height: 365px; z-index: 20; background-color: #fff; padding: 40px;">
  <div>
    <div style="font-size: 1.4em; font-weight: 600;">사업자등록번호 인증을 안했을 경우</div>
    <a href="javascript:$('#dimcont2').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
  </div>
  <div style="font-size: 1.1em; margin-top: 50px;">
    <div style="margin-top: 26px;">1. 인증마크 미부여</div>
		<div style="margin-top: 26px;">2. 유료광고 비노출(지금뜨는 업체 7일 사용권) </div>
		<div style="margin-top: 26px;">3. 허위광고 및 신뢰 문제로 인한 지원자 감소가 우려됩니다.</div>
  </div>
  <div style="text-align: center;margin-top: 50px;">
      <button onclick="$('#dimcont2').hide();" style="font-size: 16px; padding: 10px 36px;background-color: #fff; color: #57CCAF; border: 1px solid #57CCAF">이전</button>
			<button onclick="$('#mm_comp_num').val('N'); $('#dimwrap,#dimcont,#dimcont2').hide();" style="font-size: 16px; padding: 10px 36px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">확인</button>
  </div>
</div>

<script>
	$("[name=rc_addr_doro]").click(function(){
		$("#changeArea").addClass("on");
	});

	// 검색 완료 선택 처리
	function selectAddrInfo(zipcode,doro,jibun,addr,x,y){
		$("#ad_zipcode").val(zipcode);
		$("#ad_jibun").val(doro);
		$("#ad_doro").val(jibun);
		$("#ad_addr").val(addr);
		$("#ad_gps_x").val(x);
		$("#ad_gps_y").val(y);

		$("[name=rc_addr_doro]").val(doro);
		$("[name=changeArea]").val(addr);

		$("#changeArea").removeClass("on");
	}
</script>

<style>
<?php
		if($info == null || $info["mem_info"] == null || ( strpos($info["mem_info"]["mm_di"],"MC0") !== 0 || $info["mem_info"]["mm_comp_num"] == "")){ ?>

			#defaultDialog1 .btn-close-popup { display: none; }
			#defaultDialog2 .btn-close-popup { display: none; }
			#defaultDialog3 .btn-close-popup { display: none; }
			#defaultDialog4 .btn-close-popup { display: none; }
			#defaultDialog5 .btn-close-popup { display: none; }

<?php } ?>
</style>
<script>
	function fCertReq(){
		//$('#defaultDialog1 .btn-close-popup').hide();
		//$('#defaultDialog2 .btn-close-popup').hide();

		openPopup('modal', {
				content: '<div style="padding: 50px;">인증 후에 공고를 등록 하실 수 있습니다.</div>',
				<?php if($info == null || $info["mem_info"] == null || ( $info["mem_info"]["mm_comp_num"] == "")){ ?>
								firstButton: '<div style="width: 100%; height: 56px; line-height: 56px; background: #57CCAF;">기업정보 인증</div>',
				<?php }else{ ?>
								firstButton: '<div style="width: 100%; height: 56px; line-height: 56px; cursor: no-drop; background: #BEBFC9;" onclick="return false;">기업정보 인증</div>',
				<?php } ?>
				<?php if($info == null || $info["mem_info"] == null || ( strpos($info["mem_info"]["mm_di"],"MC0") !== 0)){ ?>
								secondButton: '<div style="width: 100%; height: 56px; line-height: 56px; background: #57CCAF;">PASS 인증</div>',
				<?php }else{ ?>
								secondButton: '<div style="width: 100%; height: 56px; line-height: 56px; cursor: no-drop; background: #BEBFC9;" onclick="return false;">PASS 인증</div>',
				<?php } ?>
				<?php if($info == null || $info["mem_info"] == null || ( $info["mem_info"]["mm_comp_num"] == "")){ ?>
								firstCallback: function () {
										location.href="/compage/mypage/compcert";
								},
				<?php }else{ ?>
								firstCallback: function () {
										fCertReq();
								},
				<?php } ?>
				<?php if($info == null || $info["mem_info"] == null || ( strpos($info["mem_info"]["mm_di"],"MC0") !== 0)){ ?>
								secondCallback: function () {
										location.href="/compage/mypage/pass";
								}
				<?php }else{ ?>
								secondCallback: function () {
										fCertReq();
								}
				<?php } ?>
		});

	}

	$(document).ready(function(){

		<?php //if($info == null || $info["mem_info"] == null || ((strpos($info["mem_info"]["mm_di"], "010") === 0 || $info["mem_info"]["mm_di"] = "" ) && $info["mem_info"]["mm_comp_num"] == ""))
				if($info == null || $info["mem_info"] == null || ( strpos($info["mem_info"]["mm_di"],"MC0") !== 0 || $info["mem_info"]["mm_comp_num"] == "")){ ?>

				//commonAlert("인증 후에 공고를 등록 하실 수 있습니다.",function(){
				//		location.href="/compage/mypage";
			  //});
				fCertReq();

		<?php } ?>

		$("[name='rc_work_type[]']").change(function(){
			$("#time_cont").attr("hidden","");
			$("#time_cont1").attr("hidden","");
			$("#time_cont2").attr("hidden","");

			if($("#rc_work_type_0").is(":checked")==true){
				$("#time_cont").removeAttr("hidden");
				$("#time_cont1").removeAttr("hidden");
			}

			if($("#rc_work_type_1").is(":checked")==true){
				$("#time_cont").removeAttr("hidden");
				$("#time_cont2").removeAttr("hidden");
			}

			if($("#rc_work_type_2").is(":checked")==true){
				$("#time_cont").removeAttr("hidden");
				$("#time_cont1").removeAttr("hidden");
				$("#time_cont2").removeAttr("hidden");
			}

			if($("[name='rc_work_type[]']:checked").length > 2){
				$(this).closest(".form-row").find(".add_text").removeClass("hidden");
				$('#work_type_memo').show();
			}else{
				$(this).closest(".form-row").find(".add_text").addClass("hidden");
				$('#work_type_memo').hide();
			}
		});

		$("#btnPrev").click(function(){
			history.go(-1);
		});

		function formatDate(date) {
  		const year = date.getFullYear();
  		const month = String(date.getMonth() + 1).padStart(2, '0'); // 월은 0부터 시작하므로 +1, 두 자리로 만듭니다.
  		const day = String(date.getDate()).padStart(2, '0'); // 일, 두 자리로 만듭니다.
  		return `${year}-${month}-${day}`;
		}

		$("#btnStep").click(function(){

					if($("[name='rc_name']").val()==""){
	            commonAlert("공고제목을 입력해주세요.",function(){
	        		$("[name='rc_name']").focus();
	            });
	            return;
	        }
					//console.log($("[name='rc_date']").val());
	        if($("[name='rc_date']").val()==""){
							const today = new Date();
							// 2개월 후의 날짜 구하기
							const twoMonthsLater = new Date(today);
							twoMonthsLater.setMonth(today.getMonth() + 2);
							$("[name='rc_date']").val(formatDate(today)+' ~ '+formatDate(twoMonthsLater));
            	//commonAlert("채용공고기간을 선택해주세요.",function(){});
	            //return;
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

            if($("[name='rc_work[]']").is(":checked")==false){
                commonAlert("희망업무구분은 하나이상 선택해주세요.",function(){});
                return;
            }

            //if($("[name='rc_sex[]']").is(":checked")==false){
            //    commonAlert("희망성별은 하나이상 선택해주세요.",function(){});
            //    return;
            //}

            //if($("[name='rc_nation[]']").is(":checked")==false){
            //    commonAlert("희망국적은 하나이상 선택해주세요.",function(){});
            //    return;
            //}

            //if($("[name='ma_sage']").val()==""){
            //    commonAlert("희망나이를 선택해주세요.",function(){});
            //    return;
            //}

            //if($("[name='ma_eage']").val()==""){
            //    commonAlert("희망나이를 선택해주세요.",function(){});
            //    return;
            //}

						if($("[name='ma_sage']").val()!="" && $("[name='ma_eage']").val()!="" && $("[name='ma_sage']").val() > $("[name='ma_eage']").val()){
                commonAlert("희망나이는 앞에 설정한 나이가 뒤에 설정한 나이보다 클 수 없습니다.",function(){});
                return;
            }

            if($("[name='rc_work_type[]']").is(":checked")==false){
                commonAlert("근무형태를 하나이상 선택해주세요.",function(){});
                return;
            }

						$("[name='rc_am_stime']").val($("[name='rc_am_stime_h']").val()+":"+$("[name='rc_am_stime_m']").val());
						$("[name='rc_am_etime']").val($("[name='rc_am_etime_h']").val()+":"+$("[name='rc_am_etime_m']").val());

						$("[name='rc_pm_stime']").val($("[name='rc_pm_stime_h']").val()+":"+$("[name='rc_pm_stime_m']").val());
						$("[name='rc_pm_etime']").val($("[name='rc_pm_etime_h']").val()+":"+$("[name='rc_pm_etime_m']").val());

            //if($("[name='rc_week_yn']").is(":checked")==false && $("[name='rc_week[]']").is(":checked")==false){
            //    commonAlert("근무가능 요일을 하나이상 선택해주세요.",function(){});
            //    return;
            //}

            /* if($("#rc_work_type_0").is(":checked")==true){
							if($("[name='rc_am_stime']").val()==""){
	                commonAlert("주간 근무 시작시간을 선택해주세요.",function(){});
	                return;
	            }

	            if($("[name='rc_am_etime']").val()==""){
	                commonAlert("주간 근무 종료시간을 선택해주세요.",function(){});
	                return;
	            }
						} */

						/* if($("#rc_work_type_2").is(":checked")==true){
							if($("[name='rc_pm_stime']").val()==""){
	                commonAlert("야간 근무 시작시간을 선택해주세요.",function(){});
	                return;
	            }

	            if($("[name='rc_pm_etime']").val()==""){
	                commonAlert("야간 근무 종료시간을 선택해주세요.",function(){});
	                return;
	            }
						} */

			/* if($("#rc_work_type_2").is(":checked")==true){
				if($("[name='rc_am_stime']").val()==""){
	                commonAlert("주간 근무 시작시간을 선택해주세요.",function(){});
	                return;
	            }

	            if($("[name='rc_am_etime']").val()==""){
	                commonAlert("주간 근무 종료시간을 선택해주세요.",function(){});
	                return;
	            }

				if($("[name='rc_pm_stime']").val()==""){
	                commonAlert("야간 근무 시작시간을 선택해주세요.",function(){});
	                return;
	            }

	            if($("[name='rc_pm_etime']").val()==""){
	                commonAlert("야간 근무 종료시간을 선택해주세요.",function(){});
	                return;
	            }
			} */

						//if($("[name='rc_work_dt']").val()==""){
            //    commonAlert("근무시작일을 선택해주세요.",function(){});
            //    return;
            //}

            if($("[name='rc_price_type[]']").is(":checked")==false){
                commonAlert("급여유형을 하나이상 선택해주세요.",function(){});
                return;
            }

            if(($("#amount1").attr("hidden")!="hidden") && $("[name=rc_day_price]").val()==""){
                commonAlert("일급을 입력해주세요.",function(){});
                return;
            }

            if($("#amount3").attr("hidden")!="hidden" && $("[name=rc_month_price]").val()==""){
                commonAlert("월급을 입력해주세요.",function(){});
                return;
            }

						var isDayPay = false;
						var isWeekPay = false;
						var isMonthPay = false;
						$("[name='rc_price_type[]']:checked").each(function() {
        				console.log($(this).val());
								if($(this).val()=='일급') isDayPay = true;
								if($(this).val()=='주급') isWeekPay = true;
								if($(this).val()=='월급') isMonthPay = true;
    				});

						console.log("-----");
						console.log($("[name='rc_price']").val());
						console.log($("[name='rc_day_price']").val());
						console.log($("[name='rc_week_price']").val());
						console.log($("[name='rc_month_price']").val());
						console.log("-----");

						if(      isDayPay && isWeekPay===false && isMonthPay===false){
								if($("[name='rc_price']").val()==""){
									commonAlert("급여를 입력하세요",function(){});
									return;
								}
								$("[name='rc_day_price']").val($("[name='rc_price']").val());
								$("[name='rc_week_price']").val(0);
								$("[name='rc_month_price']").val(0);
						}else if(isDayPay===false && isWeekPay && isMonthPay===false){
							if($("[name='rc_price']").val()==""){
								commonAlert("급여를 입력하세요",function(){});
								return;
							}
							$("[name='rc_week_price']").val($("[name='rc_price']").val());
							$("[name='rc_day_price']").val(0);
							$("[name='rc_month_price']").val(0);
						}else if(isDayPay===false && isWeekPay===false && isMonthPay){
							if($("[name='rc_price']").val()==""){
								commonAlert("급여를 입력하세요",function(){});
								return;
							}
							$("[name='rc_month_price']").val($("[name='rc_price']").val());
							$("[name='rc_day_price']").val(0);
							$("[name='rc_week_price']").val(0);
						}else if(isDayPay && isWeekPay && isMonthPay===false){
							if($('#paytype1').hasClass('on') && $('#paytype2').hasClass('on')){
								$('#paytype2').removeClass('on');
							}
							if(!$('#paytype1').hasClass('on') && !$('#paytype2').hasClass('on')){
								$('#paytype1').addClass('on');
							}
							if($("[name='rc_price']").val()==""){
								commonAlert("급여를 입력하세요",function(){});
								return;
							}
							if($('#paytype1').hasClass('on')){
								$("[name='rc_day_price']").val($("[name='rc_price']").val());
								$("[name='rc_week_price']").val(0);
								$("[name='rc_month_price']").val(0);
							}
							if($('#paytype2').hasClass('on')){
								$("[name='rc_week_price']").val($("[name='rc_price']").val());
								$("[name='rc_day_price']").val(0);
								$("[name='rc_month_price']").val(0);
							}
						}else if(isDayPay && isWeekPay===false && isMonthPay){
							if($('#paytype1').hasClass('on') && $('#paytype3').hasClass('on')){
								$('#paytype3').removeClass('on');
							}
							if(!$('#paytype1').hasClass('on') && !$('#paytype3').hasClass('on')){
								$('#paytype1').addClass('on');
							}
							if($("[name='rc_price']").val()==""){
								commonAlert("급여를 입력하세요",function(){});
								return;
							}
							if($('#paytype1').hasClass('on')){
								$("[name='rc_day_price']").val($("[name='rc_price']").val());
								$("[name='rc_week_price']").val(0);
								$("[name='rc_month_price']").val(0);
							}
							if($('#paytype3').hasClass('on')){
								$("[name='rc_month_price']").val($("[name='rc_price']").val());
								$("[name='rc_day_price']").val(0);
								$("[name='rc_week_price']").val(0);
							}
						}else if(isDayPay===false && isWeekPay && isMonthPay){
							if($('#paytype2').hasClass('on') && $('#paytype3').hasClass('on')){
								$('#paytype3').removeClass('on');
							}
							if(!$('#paytype2').hasClass('on') && !$('#paytype3').hasClass('on')){
								$('#paytype2').addClass('on');
							}
							if($("[name='rc_price']").val()==""){
								commonAlert("급여를 입력하세요",function(){});
								return;
							}
							if($('#paytype2').hasClass('on')){
								$("[name='rc_week_price']").val($("[name='rc_price']").val());
								$("[name='rc_day_price']").val(0);
								$("[name='rc_month_price']").val(0);
							}
							if($('#paytype3').hasClass('on')){
								$("[name='rc_month_price']").val($("[name='rc_price']").val());
								$("[name='rc_day_price']").val(0);
								$("[name='rc_week_price']").val(0);
							}
						}else if(isDayPay && isWeekPay && isMonthPay){
							//급여협의
							$("[name='rc_day_price']").val(0);
							$("[name='rc_week_price']").val(0);
							$("[name='rc_month_price']").val(0);
						}else if(isDayPay===false && isWeekPay===false && isMonthPay===false){
							commonAlert("급여형태를 하나이상 선택해주세요.",function(){});
							return;
						}


						// if(isDayPay && !isWeekPay && !isMonthPay){ $("[name='rc_day_price']").val($("[name='rc_price']").val()); $("[name='rc_week_price']").val(0); $("[name='rc_month_price']").val(0); }
						// if(isWeekPay && !isMonthPay){ $("[name='rc_day_price']").val($("[name='rc_price']").val()); $("[name='rc_month_price']").val(0); $("[name='rc_week_price']").val(0);}
						// if(isMonthPay){ $("[name='rc_month_price']").val($("[name='rc_price']").val()); $("[name='rc_day_price']").val($("[name='rc_price']").val()); $("[name='rc_week_price']").val(0);}
						// if(isDayPay && isWeekPay && isMonthPay){ $("[name='rc_day_price']").val(0); $("[name='rc_week_price']").val(0);$("[name='rc_month_price']").val(0);}

						console.log("=====");
						console.log($("[name='rc_price']").val());
						console.log($("[name='rc_day_price']").val());
						console.log($("[name='rc_week_price']").val());
						console.log($("[name='rc_month_price']").val());
						console.log("=====");
						//return; //test

						//if($("[name='rc_desc']").val()==""){
            //    commonAlert("상세업무정보를 입력해주세요.",function(){});
            //    return;
            //}

						//if(($("[name=mm_img1]").val()==""  && $("[name=mm_img1_ori]").val()==undefined) || ($("[name=mm_img1_ori]").val()=="" && $("[name=mm_img1_ori]").val()=="")){
            //	commonAlert("기업 대표이미지를 선택해주세요.",function(){});
            //    return;
            //}

            //if(($("[name=rc_img1]").val()==""  && $("[name=rc_img1_ori]").val()==undefined) || ($("[name=rc_img1_ori]").val()=="" && $("[name=rc_img1_ori]").val()=="")){
            //	commonAlert("첨부사진을 1개 이상 선택해주세요.",function(){});
            //    return;
            //}

						if($('#mm_comp_num').val()==''){
							$('#dimwrap,#dimcont').show();
							return;
						}

						var hashTexts = []; // on 클래스를 가진 span의 내용을 저장할 배열 초기화
						var spans = document.querySelectorAll('#initHash span.on'); // on 클래스를 가진 모든 span 선택
						spans.forEach(function(span) {
						    hashTexts.push(span.textContent); // 각 span의 텍스트 내용을 배열에 추가
						});
						var hashString = hashTexts.join(', ');


						var inputValues = []; // input 태그의 값들을 저장할 배열 초기화
						var inputs = document.querySelectorAll('#hashbox input[type="text"]'); // hashbox 내의 모든 input[type="text"] 선택
						inputs.forEach(function(input) {
						    inputValues.push(input.value); // 각 input의 value를 배열에 추가
						});
						var concatenatedValues = inputValues.join(', ');
						$("[name='rc_hash']").html(hashString+', '+concatenatedValues);


						oEditors.getById["rc_desc"].exec("UPDATE_CONTENTS_FIELD", []);


            var form = $('#frm');
            var params = new FormData(form[0]);
            params.set("rc_month_price",params.get("rc_month_price").replaceAll(",",""));
						params.set("rc_week_price",params.get("rc_week_price").replaceAll(",",""));
            params.set("rc_day_price",params.get("rc_day_price").replaceAll(",",""));
            fileAjaxLoading("/compage_proc/recruit_write",params,function(ret){
                if(ret.code=="00"){
                    showPreviewInfo(ret.data);
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
		});

		 $("[name=rc_week_yn]").click(function(){
			if($(this).is(":checked")){
				$("[name='rc_week[]']").prop("checked",true);
			}else{
				$("[name='rc_week[]']").prop("checked",false);
			}
		});

		$("[name='rc_week[]']").click(function(){
			if($("[name='rc_week[]']:checked").length == 7){
				$("[name=rc_week_yn]").prop("checked",true);
			}else{
				$("[name=rc_week_yn]").prop("checked",false);
			}
		});

		$(".comma").on("keyup", function(){
		    $(this).val($(this).val().replace(/\,/g, '').replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'));
		});

		//업무구분 4개까지만 선택되도록 추가 23.11.21
		// 4-> 2개로 수정 24.08.18
    var maxLimit = 2; // 최대 선택 가능한 체크박스 개수

	    // rc_work 체크박스 변경 이벤트 처리
	    $('input[name="rc_work[]"]').change(function() {
	        var checkedCount = $('input[name="rc_work[]"]:checked').length;

	        // 체크된 개수가 최대 선택 가능한 개수를 초과하면 경고 메시지를 표시
	        if (checkedCount > maxLimit) {

	            commonAlert("업무구분은 "+maxLimit+"개까지 선택 할 수 있습니다.",function(){
	            });

	            $(this).prop('checked', false); // 체크를 해제합니다.
	        }
	    });

			fFoldAll('Y');


			$("#btnSave").click(function(){

				if($('#mm_comp_num').val()=="" || $('#mm_comp_num').val()=="N"){
					commonAlert("사업자 등록번호를 인증해 주세요.",function(){
										$("#bizno").focus();
					});
					return;
				}else{
					$('#dimwrap,#dimcont').hide();
					$("#btnStep").click();
				}
			});//btnSave


			//$('[name="rc_price"]').val($('[name="rc_day_price"]').val());
			if($('[name="rc_day_price"]').val()!="0"){ $('[name="rc_price"]').val($('[name="rc_day_price"]').val()); }
			if($('[name="rc_week_price"]').val()!="0"){ $('[name="rc_price"]').val($('[name="rc_week_price"]').val()); }
			if($('[name="rc_month_price"]').val()!="0"){ $('[name="rc_price"]').val($('[name="rc_month_price"]').val()); }



			//$('[name="rc_price_rep"]').val($('[name="rc_day_price"]').val());
			if($('[name="rc_price_rep_org"]').val()!=''){
				$('#amount4 p').each(function() {
    			if ($(this).text().trim() === $('[name="rc_price_rep_org"]').val()) {
        		$(this).addClass('on');
    			}
				});
			}

			<?php if($_GET["gbn"]=="re"){ ?>
			const today = new Date();
			// 2개월 후의 날짜 구하기
			const twoMonthsLater = new Date(today);
			twoMonthsLater.setMonth(today.getMonth() + 2);
			$('.js-datepicker2').val(formatDate(today)+' ~ '+formatDate(twoMonthsLater));
			//$("[name='rc_date']").datepicker('setDate', new Date());
			//$('.js-datepicker2').datepicker({
      //  dateFormat: 'yyyy-mm-dd', // Sets the date format to year-month-day
      //  minDate: today,        // Sets the minimum date to today
      //  maxDate: twoMonthsLater // Sets the maximum date to two weeks from today
    	//});
			setTimeout(function(){
				const today = new Date();
				const twoMonthsLater = new Date(today);
				twoMonthsLater.setMonth(today.getMonth() + 2);
				$('.js-datepicker2').val(formatDate(today)+' ~ '+formatDate(twoMonthsLater));
			}, 500);
			<?php } ?>

			var rcHashArray = $("[name='rc_hash']").html().split(',');
			var initYn = false;
			rcHashArray.forEach(function(hashValue) {
				initYn = false;
				if (hashValue.trim() === '') {
            return; // 다음 반복으로 넘어갑니다.
        }
				$('#initHash span').each(function() {
					if ($(this).html().trim() === hashValue.trim()) {
						$(this).addClass('on');
						initYn = true;
					}
				});

				if(initYn===false){
					var newInput = $('<input/>', {
						type: 'text',
						style: 'min-width: 90px; max-width: 150px; height: 24px; border: none; margin-left: 5px;',
						value: hashValue.trim()
					});

					var newSpan = $('<span/>', {
						style: 'margin-right:10px; border: 1px solid #ccc; height: 28px; display: inline-block; line-height: 26px; border-radius: 25px; padding: 0 10px;'
					}).append('#').append(newInput).append('<span style="margin-left: 5px; cursor: pointer;" onclick="fHashDel(this);">ⓧ</span>');

					$('#hashbox').prepend(newSpan);
				}

			});

			setTimeout(function(){
				const today = new Date();
				const twoMonthsLater = new Date(today);
				twoMonthsLater.setMonth(today.getMonth() + 2);
				$('.js-datepicker2').val(formatDate(today)+' ~ '+formatDate(twoMonthsLater));

				$('.js-datepicker').val(formatDate(today));
			}, 500);

			fDelbtnAdd(0);
			fDelbtnAdd(1);
			fDelbtnAdd(2);
			fDelbtnAdd(3);
			fDelbtnAdd(4);
			fDelbtnAdd(5);


			// 특정 필드에 대한 선택자를 사용하여 모든 필드에 대해 이벤트를 바인딩
	    $('[name^="rc_am_stime_m"], [name^="rc_am_etime_m"], [name^="rc_pm_stime_m"], [name^="rc_pm_etime_m"]').on('input', function() {
	        // 숫자만 입력되도록 처리
	        $(this).val($(this).val().replace(/[^0-9]/g, ''));

	        // 입력된 값이 0에서 59 범위를 벗어난 경우 조정
	        let value = parseInt($(this).val(), 10);
	        if (!isNaN(value)) {
	            if (value < 0) {
	                $(this).val(0);
	            } else if (value > 59) {
	                $(this).val(59);
	            }
	        }
	    });

			//앞에 시간 선택시 8시간 뒤로 뒷시간 설정
			$('#rc_am_stime_h').change(function() {
					var startTime = parseInt($(this).val(), 10);
					var endTime = (startTime + 8) % 24; // 24시간 이후는 다음날로 넘어가므로 나머지 연산자 사용
					$('#rc_am_etime_h').val(endTime.toString().padStart(2, '0')); // 값 설정, 1자리 숫자 앞에 0 추가
			});
			$('#rc_pm_stime_h').change(function() {
					var startTime = parseInt($(this).val(), 10);
					var endTime = (startTime + 8) % 24; // 24시간 이후는 다음날로 넘어가므로 나머지 연산자 사용
					$('#rc_pm_etime_h').val(endTime.toString().padStart(2, '0')); // 값 설정, 1자리 숫자 앞에 0 추가
			});


			var oEditors = [];
			nhn.husky.EZCreator.createInIFrame({
			 oAppRef: oEditors,
			 elPlaceHolder: "rc_desc",
			 sSkinURI: "/resource/shop/web/smarteditor2/SmartEditor2Skin_ko_KR.html",
			 fCreator: "createSEditor2"
			});

			setTimeout(function(){
					$('iframe').css('background-color','#fff');
					$('iframe').css('border-right','1px solid #ccc');
			}, 500);

			// 네 개의 입력 필드를 선택하여 순회
      $('input[name="rc_am_stime_m"], input[name="rc_am_etime_m"], input[name="rc_pm_stime_m"], input[name="rc_pm_etime_m"]').each(function() {
          // 값이 빈 문자열이면 "00"을 설정
          if ($(this).val() === "") {
              $(this).val("00");
          }
      });
	});//ready

	function fDelbtnAdd(arg){

		var imgHtml = '<a href="javascript:if(confirm(\'이미지를 삭제하시겠습니까?\')){ $(\'#rc_img'+arg+'\').val(\'\'); $(\'#rc_img'+arg+'_ori\').val(\'\'); $(\'#prev'+arg+' img\').hide(); } " id="delimgbtn'+arg+'" style="display:none; position: absolute; top: 5px; right: 5px; overflow: hidden; border-radius: 11px; width: 23px; height: 24px; z-index: 10;">';
		imgHtml += '<span style="font-size: 27px; margin-top: -5px; margin-left: -2px; display: block; background-color: #ccc;">ⓧ</span>';
		imgHtml += '</a>';

		setTimeout(function(){

			if($('#prev'+arg+' img').length > 0){
				$('#prev'+arg).append(imgHtml);
			}
		}, 500);

	}


    function showPreviewInfo(info){

    	var str_rc_work_img = "";
    	var str_rc_week = "";
    	var str_img = "";
    	var str_price = "";
    	var str_time = "";
    	var str_hash = "";
    	var str_life_type = "";

    	if(info.rc_week){
	    	for(var icnt=0;icnt < info.rc_week.length;icnt++){
	    		str_rc_week += "<li aria-hidden='false'>"+info.rc_week[icnt]+"</li>";
	    	}
    	}

    	if(info.rc_img1){
    		str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='"+info.rc_img1+"' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    	}

    	if(info.rc_img2){
    		str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='"+info.rc_img2+"' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    	}

    	if(info.rc_img3){
    		str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='"+info.rc_img3+"' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    	}

    	if(info.rc_img4){
    		str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='"+info.rc_img4+"' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    	}

    	if(info.rc_img5){
    		str_img += "<div class='pic-card' style='padding-top: 0px;'><img src='"+info.rc_img5+"' alt='' style='position: relative; top: 0; left: auto; -webkit-transform: none;'></div>";
    	}

			$('[name="rc_day_price"]').val($('[name="rc_price"]').val());
			$('[name="rc_month_price"]').val($('[name="rc_price"]').val());

    	//if(info.rc_price_type.indexOf('주급') >-1){
    	//	str_price = '<em class="currency">협의가능</em>';
    	//}else{
    		//if(info.rc_price_type.indexOf('일급') > -1){
    		//	str_price += '<em class="currency">'+info.rc_day_price.format()+'</em>(일급)';
    		//}

    		//if(info.rc_price_type.indexOf('월급') > -1){
    		//	str_price += (str_price!="" ? "<br/>":"") + '<em class="currency">'+info.rc_month_price.format()+'</em>(월급)';
    		//}
    	//}
			str_price += '<em class="currency">'+$('[name="rc_price"]').val()+'</em>';

    	if(info.rc_price_type.length > 2){
    		str_price = "협의";
    	}

		if(info.rc_work_type.indexOf('2교대') >-1 || (info.rc_work_type.indexOf('주간') >-1 && info.rc_work_type.indexOf('야간') >-1)){
			if(info.rc_am_stime != '' || info.rc_am_etime != ''){
				str_time += info.rc_am_stime + '~' + info.rc_am_etime + '(주간)';
			}else{
				str_time += '주간';
			}
			if(info.rc_pm_stime != '' || info.rc_pm_etime != ''){
				str_time += '<br/>' + info.rc_pm_stime + '~' + info.rc_pm_etime + '(야간)';
			}else{
				str_time += ',' + ' 야간';
			}
		}else if(info.rc_work_type.indexOf('주간') >-1 && info.rc_work_type.indexOf('2교대') ==-1){
			if(info.rc_am_stime != '' || info.rc_am_etime != ''){
	    		str_time += info.rc_am_stime + '~' + info.rc_am_etime + '(주간)';
			}else{
	    		str_time += '주간';
			}
    	}else if(info.rc_work_type.indexOf('야간') >-1 && info.rc_work_type.indexOf('2교대') ==-1){
			if(info.rc_pm_stime != '' || info.rc_pm_etime != ''){
	    		str_time +=info.rc_pm_stime + '~' + info.rc_pm_etime + '(야간)';
			}else{
	    		str_time += '야간';
			}
    	}

			//console.log("hash");
    	for(var icnt=0;icnt < info.rc_hash.split(",").length;icnt++){
				//console.log(info.rc_hash.split(",")[icnt]);
    		if(info.rc_hash.split(",")[icnt].trim()!=""){
    			str_hash += '<p class="tag">'+info.rc_hash.split(",")[icnt].trim()+'</p>';
    		}
    	}

    	if(info.rc_life_type){
	    	for(var icnt=0;icnt < info.rc_life_type.length;icnt++){
	    		str_life_type += '<li class="item" data-tmp=""><p class="benefit-label benefit1-1-v2">'+info.rc_life_type[icnt]+'</p></li>';
	    	}
    	}
			if(!info.rc_work_dt){
				info.rc_work_dt = '근무일무관';
				info.rc_work_dt_yoil = '-';
			}
			if(info.rc_sex==''){
				info.rc_sex = '무관';
			}
			if(info.rc_nation==''){
				info.rc_nation = '무관';
			}
			var ageStr = '';
			if(info.rc_sage=='' && info.rc_eage==''){
				ageStr = '무관';
			}else{
				ageStr = info.rc_sage+'세이상~'+info.rc_eage+'세이하';
			}
			if(str_rc_week==''){
				str_rc_week = '무관';
			}

		var preview_life = info.rc_life_type_tag;
		preview_life = preview_life.replaceAll("benefit1-1","benefit1-1-v2");
		preview_life = preview_life.replaceAll("benefit1-2","benefit1-2-v2");
		preview_life = preview_life.replaceAll("benefit1-3","benefit1-3-v2");
		preview_life = preview_life.replaceAll("benefit1-4","benefit1-4-v2");
		preview_life = preview_life.replaceAll("benefit2-1","benefit2-1-v2");
		preview_life = preview_life.replaceAll("benefit2-2","benefit2-2-v2");
		preview_life = preview_life.replaceAll("benefit2-3","benefit2-3-v2");
		preview_life = preview_life.replaceAll("benefit3-1","benefit3-1-v2");
		preview_life = preview_life.replaceAll("benefit3-2","benefit3-2-v2");
		preview_life = preview_life.replaceAll("benefit3-3","benefit3-3-v2");
		preview_life = preview_life.replaceAll("benefit3-4","benefit3-4-v2");
		preview_life = preview_life.replaceAll("benefit3-5","benefit3-5-v2");
		preview_life = preview_life.replaceAll("benefit3-6","benefit3-6-v2");
		preview_life = preview_life.replaceAll("benefit4-1","benefit4-1-v2");
		preview_life = preview_life.replaceAll("benefit4-2","benefit4-2-v2");
		preview_life = preview_life.replaceAll("benefit4-3","benefit4-3-v2");
		preview_life = preview_life.replaceAll("benefit4-4","benefit4-4-v2");
		preview_life = preview_life.replaceAll("benefit4-5","benefit4-5-v2");
		preview_life = preview_life.replaceAll("benefit4-6","benefit4-6-v2");
		preview_life = preview_life.replaceAll("benefit4-7","benefit4-7-v2");

		var preview_desc = info.rc_desc;
		preview_desc = preview_desc.replaceAll(/\n/g, "<br>");

		var rc_price_rep_txt = '';
		if($('[name="rc_price_rep"]').val()!=''){
			rc_price_rep_txt = ' (대표급여형태 : '+$('[name="rc_price_rep"]').val()+')';
		}
		//$('[name="rc_day_price"]').val($('[name="rc_price"]').val());
		//$('[name="rc_week_price"]').val($('[name="rc_price"]').val());
		//$('[name="rc_month_price"]').val($('[name="rc_price"]').val());


        openPopup('modal', {
            content: '<div class="regist-info">'+
                    '<h5 class="name">'+info.rc_name+'</h5>'+
                    '<table>'+
                        '<caption><span class="sr-text">채용공고정보</span></caption>'+
                        '<colgroup><col><col></colgroup>'+
                        '<tbody>'+
                        	'<tr>'+
                                '<td class="label">공개여부</td>'+
                                '<td class="value">'+(info.rc_show_yn == 'Y' ? '공개':'비공개')+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">업무구분</td>'+
                                '<td class="value">'+
                                    info.rc_work_img +
                                    '</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                '<td class="label">금액</td>'+
                                '<td class="value txt-color2">'+
                                   str_price +
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">급여형태</td>'+
                                '<td class="value">'+(info.rc_price_type.length > 2 ? '협의':info.rc_price_type)+rc_price_rep_txt+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무시간</td>'+
                                '<td class="value">'+str_time+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무시작일</td>'+
                                '<td class="value">'+info.rc_work_dt + '('+info.rc_work_dt_yoil+')' + '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">희망성별</td>'+
                                '<td class="value">'+info.rc_sex+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">희망국적</td>'+
                                '<td class="value">'+info.rc_nation+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">희망나이</td>'+
                                '<td class="value">'+ageStr+'</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td class="label">근무요일</td>'+
                                '<td class="value">'+
                                    '<ul class="working-day">'+
                                        str_rc_week +
                                    '</ul>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value">'+
                                    '<div class="hashtag">'+
                                        str_hash +
                                    '</div>'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="label">복리후생</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value">'+
                                '<ul class="benefit-group">'+
                                    preview_life +
                                '</ul>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="label" style="padding-top: 34px;">상세업무정보</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value" style="max-width: 400px;">'+
                                    preview_desc+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="label" style="padding-top: 34px;">첨부사진</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td colspan="2" class="value">'+
                                   str_img +
                                '</td>'+
                            '</tr>'+
                        '</tbody>'+
                    '</table>'+
                '</div>',
            firstButton: '정보수정',
            secondButton: ($("[name=rc_idx]").val()!="0" ? '수정완료' : '등록완료'),
            firstCallback: function () {

            },
            secondCallback: function () {

							var params = {};
							params["rc_idx"] = $("[name=rc_idx]").val();
							params["mm_comp_num"]     = $("[name=mm_comp_num]").val();
							params["mm_comp_num_org"] = $("[name=mm_comp_num_org]").val();
							params["rc_price_rep"] = $("[name=rc_price_rep]").val();

							//rc_am_etime

	            comAjax("/compage_proc/recruit_complete",params,function(ret){
	                if(ret.code=="00"){
	                    location.href="/compage/recruit_list";
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
				<?php //if($_GET["gbn"]=="re"){ ?>
					fFold($('#btn3'),1);//공고기간
				<?php //}else{ ?>
					//fFold($('#btn3'),0);//공고기간
				<?php //} ?>
				fFold($('#btn4'),1);//근무지역
				fFold($('#btn5'),1);
				fFold($('#btn6'),0);
				fFold($('#btn7'),0);
				fFold($('#btn8'),0);
				fFold($('#btn9'),1);
				fFold($('#btn10'),1);//주간시간
				fFold($('#btn11'),1);//야간시간
				fFold($('#btn12'),0);
				fFold($('#btn13'),0);
				fFold($('#btn14'),1);
				fFold($('#btn15'),0);
				fFold($('#btn16'),1);
				fFold($('#btn17'),0);
				fFold($('#btn18'),0);
				fFold($('#btn19'),0);
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
				fFold($('#btn15'),1);
				fFold($('#btn16'),1);
				fFold($('#btn17'),1);
				fFold($('#btn18'),1);
				fFold($('#btn19'),1);
			}
		}


		function fBizAuth(){

			if($('#bizno').val()==''){
					commonAlert("사업자 등록번호를 입력해주세요.",function(){
									$("#bizno").focus();
							});
							return;
			}

			var data = {
					"b_no": [$("#bizno").val()] // 사업자번호 "xxxxxxx" 로 조회 시,
			};
			$.ajax({
					url: "https://api.odcloud.kr/api/nts-businessman/v1/status?serviceKey=nbqW%2FFUs8GPs9gugbRl6wK2fqyEhU%2F4kDV1UzIEmA%2BgNYC9C1IyNsLwER%2FR5ALL5p20DpErEsQ2QlWm38oD%2FGA%3D%3D",  // serviceKey 값을 xxxxxx에 입력
					type: "POST",
					data: JSON.stringify(data), // json 을 string으로 변환하여 전송
					dataType: "JSON",
					contentType: "application/json",
					accept: "application/json",
					success: function(result) {
						console.log(result);
							if(result.status_code=="OK"){
									if(result.data[0].b_stt_cd == "01" || result.data[0].b_stt_cd == "01"){
											bus_yn = true;
											//$("#msg" + type).show();
											//$("#msg" + type).html("사업자번호 인증완료");
											$('#mm_comp_num').val($("#bizno").val());
											commonAlert("사업자번호 인증완료",function(){ });
											return;
									}else if(result.data[0].b_stt_cd == "03"){
											bus_yn = false;
											//$("#msg" + type).show();
											//$("#msg" + type).html("폐업된 사업자 번호입니다.");
											commonAlert("폐업된 사업자 번호입니다.",function(){
															$("#bizno").focus();
											});
											return;
									}else{
											bus_yn = false;
											//$("#msg" + type).show();
											//$("#msg" + type).html("등록되지 않은 사업자 번호입니다.");
											commonAlert("등록되지 않은 사업자 번호입니다.",function(){
															$("#bizno").focus();
											});
											return;
									}
							}else{
									bus_yn = false;
									commonAlert("사업자 번호가 유효 하지 않습니다.",function(){});
							}
					},
					error: function(result) {
							bus_yn = true;
					}
			});

		}

</script>



<?php include_once (get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>
