<form id="frm" class="controls" method="post">
    <input type="hidden" name="" value="" />
    <input type="hidden" />
    
    <div class="contents-frm">
        <div class="section">
        	 <h3 class="sub-title mb-10">무통장 입금계좌 정보 설정</h3>
			<div class="search-result">
		        <table class="table-type2">
		            <colgroup>
		                <col width="80" />
		                <col width="240" />
		                <col width="240" />
		                <col width="*" />
		            </colgroup>
		            <thead>
		            <tr>
		                <th scope="col">번호</th>
		                <th scope="col">은행이름</th>
		                <th scope="col">계좌주명</th>
		                <th scope="col">계좌번호</th>
					</tr>
		            </thead>
		            <tbody>
		            	<?php foreach($bank_list as $i=> $bank):?>
						 <tr>
			                <td><?=$i + 1?></td>
			                <td>
			                	<div class="input-text w200">
		                            <label>
		                                <?=inputText("bank","bank" ,getData($bank,"bank"),"은행명","",20)?>
		                            </label>
		                        </div>
			                </th>
			                <td>
			                	<div class="input-text w200">
		                            <label>
		                                <?=inputText("acc_name","acc_name" ,getData($bank,"acc_name"),"계좌주명","",20)?>
		                            </label>
		                        </div>
			                </td>
			                <td>
			                	<div class="input-text w200">
		                            <label>
		                                <?=inputText("acc_num" ,"acc_num" ,getData($bank,"acc_num"),"계좌번호","",40)?>
		                            </label>
		                        </div>
			                </td>
			            </tr>
			            <?php endforeach;?>
					</tbody>
		        </table>
		    </div>
		</div>
		
        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>



<script>
$(document).ready(function(){
	$("#btnSave").click(function(){
		var bank_list = "";
		
		for(var icnt = 0;icnt < 3;icnt++){
			if(bank_list==""){
				bank_list = $("[name=bank]").eq(icnt).val() + ":" + $("[name=acc_name]").eq(icnt).val() + ":" + $("[name=acc_num]").eq(icnt).val() + ":";
			}else{
				bank_list = bank_list + "#" + $("[name=bank]").eq(icnt).val() + ":" + $("[name=acc_name]").eq(icnt).val() + ":" + $("[name=acc_num]").eq(icnt).val() + ":";
			}
		}
		
		var params ={};
		params["st_bank_list"] = bank_list;
		comAjaxLoading("/admin/setting/pay/bank_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.reload();	
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
	});
});
</script>