<div id="content" class="page page-login">
    <main class="page-content">

        <div class="container">
            <div class="login-form">
                <p class="title-logo"><img src="static/img/login_logo.svg" alt="NOW JOB"></p>
                <div class="form-container">


                        <div class="title-wrap">
                            <h2 class="secondary-title">아이디 찾기</h2>
                            <p class="desc">아이디를 찾으시려면 <em>전화번호를 입력</em>해주세요</p>
                        </div>

                        <div class="form-row">
                            <div class="inline-group">
                                <label class="col">
                                    <span class="ico-label phone">휴대폰번호</span>
                                    <input type="tel" id="phone" name="phone" placeholder="전화번호를 입력해 주세요" autocomplete="off" aria-required="true" required class="form-text">
                                </label>
                            </div>
                            <!-- 폼검사 실패메세지 -->
                            <p class="help-msg error-1 is-fail hidden">형식에 맞지 않는 휴대폰번호입니다.</p>
                            <!-- 폼검사 실패메세지 -->
                            <p class="help-msg error-2 is-fail hidden">등록된 휴대폰번호가 없습니다.</p>
                        </div>

                        <button id="btnFind" type="button" aria-haspopup="dialog" class="btn btn-primary btn-find">아이디 찾기</button>

                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
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
                    openPopup('alert', {content:'회원님의 아이디는<br> <em>'+ret.data.email+'</em>입니다.'});
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
