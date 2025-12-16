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
					<th scope="col">분류명<em class="ess"></em></th>
                    <td>
                       	<div class="input-text w300">
                            <label>
                                <?=inputText("cd_key","cd_key",getData($info,"cd_key"),"분류명","",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
	                <th>정렬<em class="ess"></em></td>
	                <td class="inline-wrap">
	                	<div class="input-text w120">
                            <label>
                                <?=inputNumber("cd_sort","cd_sort",getData($info,"cd_sort",0),"","",10)?>
                            </label>
                        </div>
	                </td>
	            </tr>
                <tr>
                    <th scope="col">사용 여부<em class="ess"></em></th>
                    <td>
                        <div class=" w200">
                            <label class="inline-wrap">
                                <?=inputRadio("use_yn","use_yn",["Y"=>"사용","N"=>"미사용"],getData($info,"use_yn","Y"))?>
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
	        if($("#cd_key").val()==""){
	            COMMON.alert("분류명을 입력해주세요.",function(){$("#cd_key").focus();});
	            return;
	        }
	        
	       
            if($("#cd_sort").val()==""){
	            COMMON.alert("정렬순서를 입력해주세요.",function(){$("#cd_key").focus();});
	            return;
	        }
	        
	        var form = $('#frm'); 
			var params = new FormData(form[0]);
			fileAjaxLoading("/admin/setting/basic/config_edit_proc",params,function(result){
				if(result.code == "00"){
					COMMON.alert(result.msg,function(){
						location.href="/admin/setting/basic/config_trade"; 
					});
				}else{
					COMMON.alert(result.msg);
				}
			});
	    });
	});
</script>
