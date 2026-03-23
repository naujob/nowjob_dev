<div class="page page-find-account">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">아이디 찾기</h1>
    </div>
    <div class="page-content">
        <div class="container">

            <h2 class="primary-title">아이디를 찾으실려면<br> <em>휴대폰번호를 입력</em>해주세요</h2>

            <div class="form-container">

                    <div class="form-row">
                        <label>
                            <span class="form-label">휴대폰번호</span>
                            <input type="tel" name="phone" placeholder=" -  제외 휴대폰번호를 입력해 주세요"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" autocomplete="off" aria-required="true" required class="form-text">
                        </label>
                        <!-- 폼검사 실패메세지 -->
                        <p class="error-1 help-msg is-fail hidden">형식에 맞지 않는 휴대폰번호입니다.</p>
                        <!-- 폼검사 실패메세지 -->
                        <p class="error-2 help-msg is-fail hidden">등록된 휴대폰번호가 없습니다.</p>
                    </div>

                    <div class="sticky-bottom view-footer">
                        <button id="btnFind" type="button" aria-haspopup="dialog" class="btn btn-primary">찾기</button>
                    </div>

            </div>

        </div>
    </div><!-- //page-content -->
</div>

<script>
    $(document).ready(function(){
    	$("#btnFind").click(function(){
    		$(".help-msg").addClass("hidden");
    		var phone = $("[name=phone]").val();
    		if(isHpFormat(phone)==false){
    			$(".error-1").removeClass("hidden");
    			return;
    		}

    		//연락처 확인
    		var params = {};
    		params["mm_phone"] = phone;
            comAjax("/member_proc/check_phone",params,function(ret){
                if(ret.code=="00"){
                    openPopup('alert', {content:'회원님의 이메일주소는<br> <em>'+ret.data.email+'</em>입니다.'});
                }else{
                	$(".error-2").removeClass("hidden");
                }
            });
    	});
    });

    function isHpFormat(hp){
		if(hp == ""){
			return false;
		}
		var phoneRule = /^(01[016789]{1})[0-9]{3,4}[0-9]{4}$/;
		return phoneRule.test(hp);
	}
</script>
