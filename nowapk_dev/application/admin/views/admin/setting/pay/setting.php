<form id="frm" class="controls" method="post">
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">결제수단 노출 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
	                <tr>
	                    <th scope="col">무통장입금<em class="ess"></em></th>
	                    <td>
	                    	<div  class="inline-wrap">
	                    		<?=inputRadio("st_pay_bank_yn","st_pay_bank_yn",getOptionList("use_yn"),getData($info,"st_pay_bank_yn","Y"))?>
	                    	</div>
	                    	<p class="exc1">미사용시 무통장입금 결제 수단은 비노출 처리됩니다.</p>
	                    </td>
	                </tr>
	                <tr>
	                    <th scope="col">신용카드<em class="ess"></em></th>
	                    <td>
	                        <div  class="inline-wrap">
	                    		<?=inputRadio("st_pay_card_yn","st_pay_card_yn",getOptionList("use_yn"),getData($info,"st_pay_card_yn","Y"))?>
	                    	</div>
	                    	<p class="exc1">미사용시 신용카드 결제 수단은 비노출 처리됩니다.</p>
	                    </td>
	                </tr>
	                <tr>
	                    <th scope="col">실시간 계좌이체<em class="ess"></em></th>
	                    <td>
	                       <div  class="inline-wrap">
	                    		<?=inputRadio("st_pay_direct_yn","st_pay_direct_yn",getOptionList("use_yn"),getData($info,"st_pay_direct_yn","Y"))?>
	                    	</div>
	                    	<p class="exc1">미사용시 실시간 계좌이체 결제 수단은 비노출 처리됩니다.</p>
	                    </td>
	                </tr>
	                <tr>
	                    <th scope="col">가상계좌<em class="ess"></em></th>
	                    <td>
	                        <div  class="inline-wrap">
	                    		<?=inputRadio("st_pay_vbank_yn","st_pay_vbank_yn",getOptionList("use_yn"),getData($info,"st_pay_vbank_yn","Y"))?>
	                    	</div>
	                    	<p class="exc1">미사용시 가상계좌 결제 수단은 비노출 처리됩니다.</p>
	                    </td>
	                </tr>
	                
                </tbody>
            </table>
        </div>
        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>

    </div>
</form>


<script>
$(document).ready(function(){
	$("#btnSave").click(function(){
		var params = $("#frm").serialize();
		comAjaxLoading("/admin/setting/pay/setting_proc",params,function(result){
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