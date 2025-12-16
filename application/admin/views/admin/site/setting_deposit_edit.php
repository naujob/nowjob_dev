<form id="frm" class="controls" method="post">
    <input type="hidden" name="pt_idx" value="<?=getData($info,"pt_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">적립금 수정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
          
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">발행처<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
							<?=$info["pt_type_nm"]?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">설명</th>
                    <td class="inline-wrap">
					  	<div class="input-text w700">
							<?=$info["pt_desc"]?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">사용여부<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
							<?=inputRadio("pt_status","pt_status",getOptionList("use_yn"),getData($info,"pt_status"))?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">적립금 금액<em class="ess"></em></th>
                    <td>
                    	<?php if($info["pt_type"]=="ORDER") : ?>
                    		상품구매시 적립금은 상품단위 적립금으로 대신합니다.
                    		<input type="hidden" id="pt_point" name="pt_point" value="0" />
                    	<?php else : ?>
	                    	<div class="input-text w120">
	                             <?=inputNumber("pt_point","pt_point",getData($info,"pt_point"),"")?>
	                        </div>	
                    	<?php endif;?>
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
	        if($("#pt_point").val()==""){
	            COMMON.alert("포인트를 입력해주세요.",function(){$("#pt_point").focus();});
	            return;
	        }
	        
	        var params = $("#frm").serialize();
	        comAjax("/admin/site/setting_deposit_edit_proc",params,function(result){
	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    location.href="/admin/site/setting_deposit"; 
	                });
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
	    });
	});
</script>



