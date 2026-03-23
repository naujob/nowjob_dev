<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고등록</h1>
        <ol class="progress-steps">
            <li>1</li>
            <li>2</li>
            <li>3</li>
            <li title="헌재위치" class="active">4</li>
            <li>5</li>
        </ol>
    </div><!-- //top-bar -->

    <div class="page-content">
        <form id="frm" onsubmit="return false">
        	<input type="hidden" id="rc_idx" name="rc_idx" value="<?=$info["rc_idx"]?>" />
            <div class="section addition-info-form">
                <div class="container">
                    <h2 class="form-label">복리후생</h2>
                    <div class="form-row">
                        <div class="form-row">
                            <h3 class="form-label">보험</h3>
                            <div class="form-toggle-group col-4">
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="국민연금" <?=in_array_return("국민연금",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit1-1">국민연금</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="고용보험" <?=in_array_return("고용보험",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit1-2">고용보험</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="산재보험" <?=in_array_return("산재보험",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit1-3">산재보험</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="건강보험" <?=in_array_return("건강보험",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit1-4">건강보험</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <h3 class="form-label">휴무/휴가</h3>
                            <div class="form-toggle-group col-4">
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="연차" <?=in_array_return("연차",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit2-1">연차</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="월차" <?=in_array_return("월차",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit2-2">월차</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="여름휴가" <?=in_array_return("여름휴가",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit2-3">여름휴가</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <h3 class="form-label">수당/퇴직금</h3>
                            <div class="form-toggle-group col-4">
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="야근근로수당" <?=in_array_return("야근근로수당",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit3-1">야근근로수당</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="휴일근로수당" <?=in_array_return("휴일근로수당",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit3-2">휴일근로수당</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="장기근속수당" <?=in_array_return("장기근속수당",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit3-3">장기근속수당</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="연장근로수당" <?=in_array_return("연장근로수당",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit3-4">연장근로수당</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="인센티브제" <?=in_array_return("인센티브제",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit3-5">인센티브제</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="퇴직금" <?=in_array_return("퇴직금",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit3-6">퇴직금</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-row">
                            <h3 class="form-label">생활편의지원</h3>
                            <div class="form-toggle-group col-4">
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="조식제공" <?=in_array_return("조식제공",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit4-1">조식제공</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="중식제공" <?=in_array_return("중식제공",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit4-2">중식제공</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="석식제공" <?=in_array_return("석식제공",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit4-3">석식제공</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="근무복지급" <?=in_array_return("근무복지급",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit4-4">근무복지급</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="통근버스운행" <?=in_array_return("통근버스운행",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit4-5">통근버스운행</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="차량유류비지원" <?=in_array_return("차량유류비지원",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit4-6">차량유류비지원</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="checkbox" name="rc_life_type[]" value="기숙사운영" <?=in_array_return("기숙사운영",$info["rc_life_type"],"check")?>>
                                    <span class="btn benefit-label benefit4-7">기숙사운영</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="sticky-bottom view-footer">
                        <button id="btnPrev" type="button" class="btn btn-secondary">이전</button>
                        <button id="btnStep" type="button" class="btn btn-primary">다음</button>
                    </div>
                </div>
            </div>
        </form>
    </div><!-- //page-content -->
</div>


<script>
  window.addEventListener('DOMContentLoaded', function() {  
	//$(document).ready(function(){
		//처리
		$("#btnStep").click(function(){
	        var params = $("#frm").serialize();
	        comAjax("/compage_proc/recruit_write4_new",params,function(ret){
	            if(ret.code=="00"){
	            	if($("[name=rc_idx]").val()!="0"){
	            		location.href="/compage/recruit_write5_new/" + $("[name=rc_idx]").val();
	            		//_replace("/compage/recruit_write5/" + $("[name=rc_idx]").val());
	            	}else{
	            		location.href="/compage/recruit_write5_new";
	            		//_replace("/compage/recruit_write5");
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
