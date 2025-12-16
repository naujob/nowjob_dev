<form id="frm" class="controls" method="post">
    <input type="hidden" name="cd_idx" value="<?=getData($info,"cd_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    
    <div class="contents-frm">
        <div class="section">
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
				<tr>
                    <th scope="col">영업부서<em class="ess"></em></th>
                    <td>
                        <div class="input-text w400">
                            <label>
                                <?=inputText("cd_val","cd_val",getData($info,"cd_val"),"부서명 ","",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">담당자<em class="ess"></em></th>
                    <td>
                        <div class="input-text w400">
                            <label>
                                <?=inputText("cd_val2","cd_val2",getData($info,"cd_val2"),"담당자명 ","",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150 btnList">목록으로</button>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10 btnSave">저장하기</button>
        </div>
    </div>
</form>


<script>
	$(".btnList").click(function(){
		history.go(-1);
	});
	
	$(".btnSave").click(function(){
		if($("#cd_val").val()==""){
    		COMMON.alert("영업부서를 입력해주세요.",function(){$("#cd_val").focus();});
    		return;
    	}
    	
    	if($("#cd_val2").val()==""){
    		COMMON.alert("담당자명을 입력해주세요.",function(){$("#cd_val2").focus();});
    		return;
    	}
    	
		var params = $("#frm").serialize();
		comAjax("/admin/setting/manage/sales_depth_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.href="/admin/setting/manage/sales_depth_list";	
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
	});
	
</script>