<form id="frm" class="controls" method="post">
    <input type="hidden" name="cd_idx" value="<?=getData($info,"cd_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">희망업무 분류추가</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
					<th scope="col">그룹<em class="ess"></em></th>
                    <td>
                       	<div class="input-text w300">
                            <label>
                                <?=inputText("cd_grp","cd_grp",getData($info,"cd_grp"),"","readonly",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
               	<tr>
					<th scope="col">코드명<em class="ess"></em></th>
                    <td>
                       	<div class="input-text w300">
                            <label>
                                <?=inputText("cd_key","cd_key",getData($info,"cd_key"),"코드명","readonly",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
					<th scope="col">내용<em class="ess"></em></th>
                    <td>
                       	<div class="input-text w300">
                            <label>
                                <?=inputText("cd_val","cd_val",getData($info,"cd_val"),"","readonly",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
					<th scope="col">포인트<em class="ess"></em></th>
                    <td>
                       	<div class="input-text w300">
                            <label>
                                <?=inputText("cd_val3","cd_val3",getData($info,"cd_val3"),"포인트","",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
	
        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>
<script>
	$(document).ready(function(){
		$("#btnList").click(function(){
	        history.go(-1);
	    });
	    
	    $("#btnSave").click(function(){
            if($("#cd_val3").val()==""){
	            COMMON.alert("포인트를 입력해주세요.",function(){$("#cd_val2").focus();});
	            return;
	        }
	        
	        var form = $('#frm'); 
			var params = new FormData(form[0]);
			fileAjaxLoading("/admin/setting/basic/config_usepoint_edit_proc",params,function(result){
				if(result.code == "00"){
					COMMON.alert(result.msg,function(){
						location.href="/admin/setting/basic/config_usepoint"; 
					});
				}else{
					COMMON.alert(result.msg);
				}
			});
	    });
	});
</script>
