<div id="wrap">
    <div id="container" class="login">
        
        <div class="ds-table">
            <div class="ds-table-cell">
                <h1 class="main_title">
                	NOWJOB 관리자
                </h1>
                <div class="content">
                    <h2>관리자 로그인</h2>
                    <form id="frm" class="controls" method="post">
                        <div class="login-box">
                            <div class="input-text size-m">

                                    <input type="text" placeholder="아이디를 입력해주세요." id="admin_id" name="admin_id" value="">

                            </div>
                            <div class="input-text size-m mt-5">

                                    <input type="password" placeholder="비밀번호를 입력해주세요." id="admin_pwd" name="admin_pwd">

                            </div>
                            <input type="hidden" name="redirect" value="">
                            <button type="button" class="btn-login btn-m btn-type6" id="btnLogin">로그인</button>
                        </div>
                        <div class="bottom clear">
                            <div class="fl">
                                <div class="input-checkbox">
                                    <label class="inline-wrap">
								        <?=inputCheck("chk_login","chk_login",["Y"=>"아이디 저장하기"],"")?>
								    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
$(document).ready(function(){
	//아이디 체크 확인
	var save_admin_id_yn = getCookie("save_admin_id_yn");
	if(save_admin_id_yn=="Y"){
		var save_admin_id = getCookie("save_admin_id");
		$("[name=chk_login]").prop("checked",true);
		$("#admin_id").val(save_admin_id);
	}

	//엔터키 처리
	$("#admin_pwd").keydown(function(key) {
		if (key.keyCode == 13) {
			goLogin();
		}
	});

	//로그인 처리
	$("#btnLogin").click(function(){
		goLogin();
	});

	function goLogin(){
		if($("#admin_id").val()==""){
    		COMMON.alert("아이디를 입력해주세요.",function(){$("#admin_id").focus();});
    		return;
    	}

    	if($("#admin_pwd").val()==""){
    		COMMON.alert("비밀번호를 입력해주세요.",function(){$("#admin_pwd").focus();});
    		return;
    	}

		var params = $("#frm").serialize();
		comAjaxLoading("/admin/home/admin_login_proc",params,function(result){
			if(result.code == "00"){
				if($("[name=chk_login]").is(":checked")){
		    		setCookie("save_admin_id_yn","Y",365);
		    		setCookie("save_admin_id",$("#admin_id").val(),365);
		    	}else{
		    		deleteCookie("save_admin_id_yn");
		    		deleteCookie("save_admin_id");
		    	}

		    	if($("[name=auto_login]").is(":checked")){
		    		setCookie("save_admin_auto_yn","Y",365);
		    	}else{
		    		deleteCookie("save_admin_auto_yn");
		    	}

				location.href="/admin/home";
			}else{
				COMMON.alert(result.msg);
			}
		});
	}
});
</script>
