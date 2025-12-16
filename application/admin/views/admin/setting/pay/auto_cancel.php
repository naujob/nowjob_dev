<form id="frm" class="controls" method="post">    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">가상계좌/무통장입금 자동취소</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">결제대기 주문 자동취소 설정<em class="ess"></em></th>
                    <td>
                        <div class="input-text w100">
                            <label>
                                <?=inputNumber("st_pay_cancel_day","st_pay_cancel_day",getData($info,"st_pay_cancel_day"),"일자","",100)?>
                            </label>
                        </div>
                        <p class="exc1">설정값이 0일 경우 자동취소가 작동하지 않습니다.</p>
                        <p class="exc1">결제대기 상태로 설정한 일수는 넘은 주문은 자동취소됩니다.</p>
                        <p class="exc1">결제대기는 결제수단이 무통장입금 및 가상계좌인 주문뿐 아니라 신용카드, 계좌이체, 핸드폰 결제 진행대기중인 주문도 포함됩니다.</p>
                        <p class="exc1">자동취소는 1일1회 작동되며, 주문일로부터 지정한 일수를 초단위로 판단하여 초과될 경우 취소합니다.</p>
                        <p class="exc1">예) 자동취소 1일 설정. 2020-06-10 20:08:11 주문된 건의 경우 - 2020-06-11 20:08:11에 만 하루가 되므로 2020-06-12까지 결제대기일 경우 취소됩니다.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <!--button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button-->
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
	$("#btnSave").click(function(){
		var params = $("#frm").serialize();
		comAjaxLoading("/admin/setting/pay/auto_cancel_proc",params,function(result){
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