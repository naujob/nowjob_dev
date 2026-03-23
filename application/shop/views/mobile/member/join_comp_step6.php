<div class="page page-join member-type2" style="display:none;">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">기업 회원가입</h1>
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
            //if (document.querySelector('#skipTooltip').classList.contains('on')) {
            //    location.href = this.getAttribute('href');
            //}
        });
    </script>
    <div class="page-content">
        <div class="container">

            <ol class="progress-steps">
                <li title="헌재위치" class="active">1</li>
                <li>2</li>
            </ol>

            <div class="addition-info-form">
                <form id="frm" onsubmit="return false">
                   <input type="hidden" name="proc_type" value="join_comp_step6" />
                   <input type="hidden" name="ma_work_img" value="" />
                    <div class="form-row">
                        <p class="form-label">필요업무 <em>다중선택가능</em></p>
                        <div class="form-toggle-group col-4">
                            <?php foreach($ma_wrok_list as $row) : ?>
                            <label class="form-toggle-btn2 sm">
                                <input type="checkbox" name="ma_work[]" data-select-limit="2" value="<?=$row["cd_val"]?>" data-img="<?=$row["cd_val2"]?>" <?=in_array_return($row["cd_val"],$info["ma_work"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">희망급여 <em>다중선택가능</em></p>
                        <div class="form-toggle-group col-3">
                            <?php foreach($ma_price_type_list as $row) : ?>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ma_price_type[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["ma_price_type"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">근무형태 <em>다중선택가능</em></p>
                        <div class="form-toggle-group col-3">
                            <?php foreach($ma_work_type_list as $row) : ?>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ma_work_type[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["ma_work_type"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>

                    <!-- 20220929 수정 -->
                    <div class="form-row">
                        <p class="form-label">
                            근무요일 <em>다중선택가능</em>
                            <span class="option">
                                <label class="form-checkbox">
                                    <input type="checkbox" name="ma_week_yn" value="all" class="sr-text" <?=$info["ma_week_yn"]=="Y" ? "checked":""?>>
                                    <span class="label">요일무관</span>
                                </label>
                            </span>
                        </p>
                        <div class="form-toggle-group align-justify">
                            <?php foreach($ma_week_list as $row) : ?>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ma_week[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$info["ma_week"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>

                    <div class="form-row">
                        <p class="form-label">희망성별 <em>다중선택가능</em></p>
                        <div class="form-toggle-group col-2">
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ma_sex[]" value="남자" <?=in_array_return("남자",$info["ma_sex"],"check")?>>
                                <span class="btn">남자</span>
                            </label>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ma_sex[]" value="여자" <?=in_array_return("여자",$info["ma_sex"],"check")?>>
                                <span class="btn">여자</span>
                            </label>
                        </div>
                    </div>

                    <!--div class="form-row">
                        <p class="form-label">희망나이</p>
                        <div class="inline-group">
                            <div class="col">
                                <label>
                                    <span class="sr-text">최소나이 선택</span>
                                    <input type="text" name="ma_sage" placeholder="최소" value="<?=$info["ma_sage"]?>" readonly required class="form-text js-wheel">
                                </label>
                                <div class="wheel-wrapper"><div id="minWheel" class="wheel"></div></div>
                            </div>
                            <span class="entity">~</span>
                            <div class="col">
                                <label>
                                    <span class="sr-text">최대나이 선택</span>
                                    <input type="text" name="ma_eage" placeholder="최대" value="<?=$info["ma_eage"]?>" readonly required class="form-text js-wheel">
                                </label>
                                <div class="wheel-wrapper"><div id="maxWheel" class="wheel"></div></div>
                            </div>
                        </div>
                    </div-->
                    <div class="form-row">
					    <p class="form-label">나이</p>
					    <div class="inline-group">
					        <div class="col">
					            <select name="ma_sage" style="width:100%;height:30px;padding-left:12px;">
					                <option value="">선택</option>
					                <?php for($icnt=20;$icnt <= 80;$icnt++) : ?>
					                <option value="<?=$icnt?>" <?=$info["ma_sage"]==$icnt ? "selected":""?>><?=$icnt?>세</option>
					                <?php endfor;?>
					            </select>
					        </div>
					        <span class="entity">~</span>
					        <div class="col">
					            <select name="ma_eage" style="width:100%;height:30px;padding-left:12px;">
					                <option value="">선택</option>
					                <?php for($icnt=20;$icnt <= 80;$icnt++) : ?>
					                <option value="<?=$icnt?>" <?=$info["ma_eage"]==$icnt ? "selected":""?>><?=$icnt?>세</option>
					                <?php endfor;?>
					            </select>
					        </div>
					    </div>
					</div>

					<div class="form-row">
                        <h3 class="form-label">보험</h3>
                        <div class="form-toggle-group col-4">
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="국민연금" <?=in_array_return("국민연금",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit1-1">국민연금</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="고용보험" <?=in_array_return("고용보험",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit1-2">고용보험</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="산재보험" <?=in_array_return("산재보험",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit1-3">산재보험</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="건강보험" <?=in_array_return("건강보험",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit1-4">건강보험</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <h3 class="form-label">휴무/휴가</h3>
                        <div class="form-toggle-group col-4">
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="연차" <?=in_array_return("연차",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit2-1">연차</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="월차" <?=in_array_return("월차",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit2-2">월차</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="여름휴가" <?=in_array_return("여름휴가",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit2-3">여름휴가</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <h3 class="form-label">보험</h3>
                        <div class="form-toggle-group col-4">
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="야근근로수당" <?=in_array_return("야근근로수당",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit3-1">야근근로수당</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="휴일근로수당" <?=in_array_return("휴일근로수당",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit3-2">휴일근로수당</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="장기근속수당" <?=in_array_return("장기근속수당",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit3-3">장기근속수당</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="연장근로수당" <?=in_array_return("연장근로수당",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit3-4">연장근로수당</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="인센티브제" <?=in_array_return("인센티브제",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit3-5">인센티브제</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="퇴직금" <?=in_array_return("퇴직금",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit3-6">퇴직금</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-row">
                        <h3 class="form-label">생활편의지원</h3>
                        <div class="form-toggle-group col-4">
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="조식제공" <?=in_array_return("조식제공",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit4-1">조식제공</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="중식제공" <?=in_array_return("중식제공",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit4-2">중식제공</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="석식제공" <?=in_array_return("석식제공",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit4-3">석식제공</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="근무복지급" <?=in_array_return("근무복지급",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit4-4">근무복지급</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="통근버스운행" <?=in_array_return("통근버스운행",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit4-5">통근버스운행</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="차량유류비지원" <?=in_array_return("차량유류비지원",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit4-6">차량유류비지원</span>
                            </label>
                            <label class="form-toggle-btn3">
                                <input type="checkbox" name="ma_life_type[]" value="기숙사운영" <?=in_array_return("기숙사운영",$info["ma_life_type"],"check")?>>
                                <span class="btn benefit-label benefit4-7">기숙사운영</span>
                            </label>
                        </div>
                    </div>

                    <!-- 20221122 수정 -->
                    <div class="form-row">
                        <p class="form-label">희망국적 <em>다중선택가능</em></p>
                        <div class="form-toggle-group col-2">
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ma_nation[]" value="내국인" <?=in_array_return("내국인",$info["ma_nation"],"check")?>>
                                <span class="btn">내국인</span>
                            </label>
                            <label class="form-toggle-btn2">
                                <input type="checkbox" name="ma_nation[]" value="외국인" <?=in_array_return("외국인",$info["ma_nation"],"check")?>>
                                <span class="btn">외국인</span>
                            </label>
                        </div>
                    </div>

                    <!-- 20221122 수정 -->
                    <div class="form-row">
                        <p class="form-label">최종학력</p>
                        <div class="form-toggle-group col-4">
                            <?php foreach($ma_edu_list as $row) : ?>
                            <label class="form-toggle-btn2">
                                <input type="radio" name="ma_edu" value="<?=$row["cd_val"]?>"  <?=equal_return($row["cd_val"],$info["ma_edu"],"check")?>>
                                <span class="btn"><?=$row["cd_val"]?></span>
                            </label>
                            <?php endforeach;?>
                        </div>
                    </div>

                    <!-- 20221122 수정 -->
                    <div class="form-row">
                         <p class="form-label">기업 대표 이미지</p>
                        <div class="form-pic-group">
                            <label class="form-pic first">
                                <input type="file" name="mm_img1" accept="image/*" class="js-file-preview">
                                <span class="label">프로필 기업 사진을<br> 첨부해주세요</span>
                                <span class="preview">
                                	<?php if(getData($info,"mm_img1")!="") : ?>
	                            		<img src="<?=getData($info,"mm_img1")?>" />
	                            		<input type='hidden' name='mm_img1_ori' value='<?=getData($info,"mm_img1")?>' />
	                            	<?php endif; ?>
	                            </span>
                            </label>
                        </div>
                    </div>

                    <div class="sticky-bottom view-footer">
                        <button type="button" id="btnStep" class="btn btn-primary">다음</button>
                    </div>
                </form>
            </div>

        </div>
    </div><!-- //page-content -->
</div>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        if (typeof Wheel == undefined) return;

        function closeActive () {
            var active = document.querySelector('.js-wheel.is-focus');
            var activeWheel = document.querySelector('.wheel-wrapper.on');

            if (active) {
                active.classList.remove('is-focus');
            }

            if (activeWheel) {
                activeWheel.classList.remove('on');
                activeWheel.hidden = true;
            }
        }

		/*
        document.querySelector('[name="ma_sage"]').addEventListener('change', function (e) {
            var min = parseInt(this.value);
            var max = document.querySelector('[name="ma_eage"]');

            if (parseInt(max.value) < min) {
                max.value = min + '세';
            }
        });

        document.querySelector('[name="ma_eage"]').addEventListener('change', function (e) {
            var max = parseInt(this.value);
            var min = document.querySelector('[name="ma_sage"]');

            if (parseInt(min.value) > max) {
                min.value = max + '세';
            }
        });
        */

        document.querySelectorAll('.js-wheel').forEach(function (el) {
            var wheel = el.parentNode.parentNode.querySelector('.wheel');

            new Wheel(wheel, {
                initIdx: 0,
                loop: true,
                width: 24,
                length: 60,//총갯수
                start: 20,//시작숫자
                altInput: el,
                label: '세',
            });

            el.addEventListener('focus', function (e) {
                /* 옵션창 표시 */
                var parent = el.parentNode;
                var xpos = parent.offsetLeft;
                var ypos = parent.offsetTop + el.offsetHeight + 10;
                var max = {
                    xpos: window.innerWidth - 100,
                    ypos: document.querySelector('.page-content').scrollHeight - 200,
                };

                closeActive();

                this.classList.add('is-focus');

                if (max.ypos <= parent.getBoundingClientRect().top + el.offsetHeight + 10) {
                    ypos = parent.offsetTop + 10;
                    wheel.parentNode.style.top = '';
                    wheel.parentNode.style.bottom = ypos + 'px';
                } else {
                    wheel.parentNode.style.bottom = '';
                    wheel.parentNode.style.top = ypos + 'px';
                }

                if (max.xpos <= parent.getBoundingClientRect().left + el.offsetWidth) {
                    wheel.parentNode.style.left = '';
                    wheel.parentNode.style.right = 0;
                } else {
                    wheel.parentNode.style.right = '';
                    wheel.parentNode.style.left = xpos + 'px';
                }

                wheel.parentNode.hidden = false;
                wheel.parentNode.classList.add('on');
            });
        });

        window.addEventListener('click', function (e) {
            /* 옵션창 숨김 */
            var target = e.target;

            if (target.classList && (target.classList.contains('js-wheel') || target.closest('.wheel-wrapper'))) return;

            closeActive();
        });
    });
</script>
<script>
function fPassClick(){
  setTimeout(function(){
    var params = {};
    params["ma_type"] = "COMP";
    params["proc_type"] = "PART";
    params["uuid"] = "<?=$uuid?>";
    try {
      var joinType = localStorage.getItem("join_sns_type") || sessionStorage.getItem("join_sns_type") || "";
      var joinKey = localStorage.getItem("join_sns_key") || sessionStorage.getItem("join_sns_key") || "";
      if (joinType) params["join_type"] = joinType;
      if (joinKey) params["join_key"] = joinKey;
    } catch (e) {}
          comAjax("/member_proc/join_complete_proc",params,function(ret){
              if(ret.code=="00"){
                  try {
                      localStorage.removeItem("join_sns_type");
                      localStorage.removeItem("join_sns_key");
                      sessionStorage.removeItem("join_sns_type");
                      sessionStorage.removeItem("join_sns_key");
                      document.cookie = "join_sns_type=; path=/; max-age=0";
                      document.cookie = "join_sns_key=; path=/; max-age=0";
                  } catch (e) {}
                  location.href="/member/join_comp_finish";
              }else{
                  commonAlert(ret.msg,function(){});
                  return;
              }
          });
        },10); //5000

}

	//$(document).ready(function(){
  window.addEventListener('load', function(){
    fPassClick();
		//건너띄기
		$(".js-toggle-tooltip").click(function(){
      fPassClick();
		});

		$("#btnStep").click(function(){
            if($("[name='ma_work[]']").is(":checked")==false){
                commonAlert("희망업무는 하나이상 선택해주세요.",function(){});
                return;
            }

            if($("[name='ma_price_type[]']").is(":checked")==false){
                commonAlert("희망급여는 하나이상 선택해주세요.",function(){});
                return;
            }

            if($("[name='ma_work_type[]']").is(":checked")==false){
                commonAlert("근무형태는 하나이상 선택해주세요.",function(){});
                return;
            }

            if($("[name='ma_week_yn']").is(":checked")==false && $("[name='ma_week[]']").is(":checked")==false){
                commonAlert("근무가능 요일을 하나이상 선택해주세요.",function(){});
                return;
            }

            if($("[name='ma_sex[]']").is(":checked")==false){
                commonAlert("희망성별은 하나이상 선택해주세요.",function(){});
                return;
            }

            if($("[name='ma_sage']").val()!="" && $("[name='ma_eage']").val()!="" && $("[name='ma_sage']").val() > $("[name='ma_eage']").val()){
                commonAlert("희망나이는 앞에 설정한 나이가 뒤에 설정한 나이보다 클 수 없습니다.",function(){});
                return;
            }

            if(($("[name=mm_img1]").val()==""  && $("[name=mm_img1_ori]").val()==undefined) || ($("[name=mm_img1_ori]").val()=="" && $("[name=mm_img1_ori]").val()=="")){
            	commonAlert("기업 대표이미지를 선택해주세요.",function(){});
                return;
            }

            var ma_work_img = "";
            for(var icnt=0;icnt<$("[name='ma_work[]']:checked").length;icnt++){
            	if(ma_work_img==""){
            		ma_work_img = $("[name='ma_work[]']:checked").eq(icnt).data("img");
            	}else{
            		ma_work_img += "," + $("[name='ma_work[]']:checked").eq(icnt).data("img");
            	}
            }
			$("[name=ma_work_img]").val(ma_work_img);

            var form = $('#frm');
            var params = new FormData(form[0]);
            fileAjax("/member_proc/join_comp_step6",params,function(ret){
                if(ret.code=="00"){
                    showPreviewInfo(ret.data);
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
		});


		$("[name=ma_week_yn]").click(function(){
			if($(this).is(":checked")){
				$("[name='ma_week[]']").prop("checked",true);
			}else{
				$("[name='ma_week[]']").prop("checked",false);
			}
		});

		$("[name='ma_week[]']").click(function(){
			if($("[name='ma_week[]']:checked").length == 7){
				$("[name=ma_week_yn]").prop("checked",true);
			}else{
				$("[name=ma_week_yn]").prop("checked",false);
			}
		});
	});

    function showPreviewInfo(info){

    	var str_ma_week = "";
    	var str_ma_career = "";
    	var str_ma_certi = "";
    	var str_ma_type = "";
    	var str_img = "";
    	var str_age = "";

    	if(info.ma_week){
	    	for(var icnt=0;icnt < info.ma_week.length;icnt++){
	    		str_ma_week += "<li aria-hidden='false'>"+info.ma_week[icnt]+"</li>";
	    	}
    	}

    	if(info.arr_career){
    		for(var icnt=0;icnt < info.arr_career.split("&&&").length;icnt++){
    			var temp = info.arr_career.split("&&&")[icnt].split("|||");
    			if(temp.length  > 2){
    				str_ma_career += "<li><span>"+temp[0]+"</span><span>"+temp[1]+"</span><em>"+temp[2]+"(1년 2개월)</em></li>";
    			}
	    	}
    	}

    	if(info.arr_certi){
    		for(var icnt=0;icnt < info.arr_certi.split("&&&").length;icnt++){
    			var temp = info.arr_certi.split("&&&")[icnt].split("|||");
    			if(temp.length  > 1){
    				str_ma_certi += "<li><span>"+temp[0]+"</span><em>"+temp[1]+"</em></li>";
    			}
	    	}
    	}

    	if(info.ma_my_type){
    		for(var icnt=0;icnt < info.ma_my_type.length;icnt++){
    			str_ma_type +="<p class='tag'>"+info.ma_my_type[icnt]+"</p>";
	    	}
    	}

    	if(info.mm_img1){
    		str_img += "<div class='pic-card'><img src='"+info.mm_img1+"' alt=''></div>";
    	}

    	if(info.mm_img2){
    		str_img += "<div class='pic-card'><img src='"+info.mm_img2+"' alt=''></div>";
    	}

    	if(info.mm_img3){
    		str_img += "<div class='pic-card'><img src='"+info.mm_img3+"' alt=''></div>";
    	}

    	if(info.mm_img4){
    		str_img += "<div class='pic-card'><img src='"+info.mm_img4+"' alt=''></div>";
    	}

    	if(info.mm_img5){
    		str_img += "<div class='pic-card'><img src='"+info.mm_img5+"' alt=''></div>";
    	}

    	if(info.ma_sage!="" && info.ma_eage!=""){
    		str_age = info.ma_sage+'~'+info.ma_eage+'세';
    	}else if(info.ma_sage!=""){
    		str_age = info.ma_sage+'세 이상';
    	}else if(info.ma_eage!=""){
    		str_age = info.ma_eage+'세 이하';
    	}

        openPopup('modal', {
            content: '<div class="regist-info">'+
                '<h5 class="name">'+info.mm_comp_name+'</h5>'+
                '<table>'+
                    '<caption><span class="sr-text">회원가입정보</span></caption>'+
                    '<colgroup><col><col></colgroup>'+
                    '<tbody>'+
                        '<tr>'+
                            '<td class="label">필요업무</td>'+
                            '<td class="value">'+
                                info.ma_work_img +
                                '</td>'+
                                '</tr>'+
                                '<tr>'+
                            '<td class="label">희망급여</td>'+
                            '<td class="value">'+info.ma_price_type+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td class="label">근무형태</td>'+
                            '<td class="value">'+info.ma_work_type+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td class="label">근무요일</td>'+
                            '<td class="value">'+
                                '<ul class="working-day">'+
                                    str_ma_week +
                                '</ul>'+
                            '</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td class="label">희망성별</td>'+
                            '<td class="value">'+info.ma_sex+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td class="label">희망나이</td>'+
                            '<td class="value">'+str_age+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<td class="label">기업 대표 이미지</td>'+
                            '<td class="value">'+str_img+'</td>'+
                        '</tr>'+
                    '</tbody>'+
                '</table>'+
            '</div>',
            firstButton: '정보수정',
            secondButton: '가입완료',
            firstCallback: function () {

            },
            secondCallback: function () {
                var params = {};
				params["ma_type"] = "COMP";
				params["proc_type"] = "ALL";
				params["uuid"] = "<?=$uuid?>";
				try {
				  var joinType = localStorage.getItem("join_sns_type") || sessionStorage.getItem("join_sns_type") || "";
				  var joinKey = localStorage.getItem("join_sns_key") || sessionStorage.getItem("join_sns_key") || "";
				  if (joinType) params["join_type"] = joinType;
				  if (joinKey) params["join_key"] = joinKey;
				} catch (e) {}
	            comAjax("/member_proc/join_complete_proc",params,function(ret){
	                if(ret.code=="00"){
                        try {
                            localStorage.removeItem("join_sns_type");
                            localStorage.removeItem("join_sns_key");
                            sessionStorage.removeItem("join_sns_type");
                            sessionStorage.removeItem("join_sns_key");
                            document.cookie = "join_sns_type=; path=/; max-age=0";
                            document.cookie = "join_sns_key=; path=/; max-age=0";
                        } catch (e) {}
	                    location.href="/member/join_comp_finish";
	                }else{
	                    commonAlert(ret.msg,function(){});
	                    return;
	                }
	            });
            }
        });
    }
</script>
