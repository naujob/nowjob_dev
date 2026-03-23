<div class="page page-find-account">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">비밀번호 변경</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="container">
            <h2 class="primary-title align-center"><em>비밀번호 변경</em>해주세요</h2>
            <div class="form-container">
                 <form id="frm" onsubmit="return false">
                 	<input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
                 	<input type="hidden" name="bd_idx" value="<?=getData()?>" />
                 	
                    <div class="form-row">
                        <label>
                            <span class="form-label">기존 비밀번호</span>
                            <input type="password" id="mm_pwd" name="mm_pwd" autocomplete="off" aria-required="true" required class="form-text">
                        </label>
                    </div>

                    <div class="form-row">
                        <label>
                            <span class="form-label">새 비밀번호</span>
                            <input type="password" id="change_pwd" name="change_pwd" autocomplete="off" aria-required="true" required class="form-text">
                        </label>
                        <p class="help-msg rule">비밀번호는 8~20자 이내, 숫자/특수문자/영문대소문자를<br> 사용해 주세요.</p>
                    </div>

                    <div class="form-row">
                        <label>
                            <span class="form-label">새 비밀번호 확인</span>
                            <input type="password" id="change_pwd_c" name="change_pwd_c" autocomplete="off" aria-required="true" required class="form-text">
                        </label>
                    </div>

                    <div class="sticky-bottom view-footer">
                        <button type="button" id="btnChange" class="btn btn-primary">변경하기</button>
                    </div>

                </form>
            </div>
        </div>
    </div><!-- //page-content -->
</div>
<script>
$(document).ready(function(){
	$("#btnChange").click(function(){
        if($("#mm_pwd").val()==""){
            commonAlert("기존 비밀번호를 입력해주세요.",function(){
                $("#mm_pwd").focus();
            });
            return;
        }

        if($("#change_pwd").val()==""){
            commonAlert("새 비밀번호를 입력해주세요.",function(){
                $("#change_pwd").focus();
            });
            return;
        }

        //데이터 확인
        if(chkPassword($("#change_pwd").val())==false || $("#change_pwd").val()==""){
            commonAlert("비밀번호는 8~20자 이내, 숫자/특수문자/영문대소문자를 사용해주세요.",function(){
                $("#change_pwd").focus();
            });
            return;
        }

        if($("#change_pwd_c").val()==""){
            commonAlert("새 비밀번호 확인을 입력해주세요.",function(){
                $("#change_pwd_c").focus();
            });
            return;
        }

        if($("#change_pwd").val()!=$("#change_pwd_c").val()){
            commonAlert("새 비밀번호와 새 비밀번호 확인이 다릅니다.",function(){
                $("#change_pwd_c").focus();
            });
            return;
        }

		var params = $("#frm").serialize();
        comAjax("/setting_proc/change_pwd",params,function(ret){
            if(ret.code=="00"){
                commonAlert("비밀번호가 변경되었습니다.",function(){
                    location.replace("/mypage/index");
                });
            }else{
            	commonAlert(ret.msg,function(){});
            }
        });
	});
});

//비밀번호체크
function chkPassword(value){
    var pattern1 = /[0-9]/;
    var pattern2 = /[a-zA-Z]/;
    var pattern3 = /[~!@\#$%<>^&*]/;     // 원하는 특수문자 추가 제거
    if(!pattern1.test(value)||!pattern2.test(value)||!pattern3.test(value)||value.length<8||value.length>20){
        return false;
    }
    return true;
}
</script>
