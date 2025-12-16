<form id="frm" class="controls" method="post">
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">포인트 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">사용가능 포인트 설정<em class="ess"></em></th>
                    <td>
                        <div class="inline-wrap">
                            <div class="input-text w100">
                                <?=inputNumber("st_point_limit_per","st_point_limit_per",getData($info,"st_point_limit_per",""),"%입력","",100)?>
                            </div>
                            <span style="font-size:12px;margin-left:5px;">%</span>
                        </div>
                        <p class="exc1">주문금액 포인트 사용 가능 퍼센트를 설정합니다(0-100%)</p>
                    </td>
                </tr>
                </tbody>
            </table>
            <h3 class="sub-title mt-20 mb-10">포인트 운영 방식</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">포인트 처리유형<em class="ess"></em></th>
                    <td>
                        <div class="inline-wrap">
                             <?=inputRadio("st_point_save_yn","st_point_save_yn",["N"=>"사용안함","P"=>"선포인트 지급방식","C"=>"배송완료 적립방식"],getData($info,"st_point_save_yn","N"))?>
                        </div>
                        <p class="exc1">선포인트 지급방식 : 회원별 사용가능 포인트를 따로 설정 가능합니다.</p>
                        <p class="exc1">배송완료 적립방식 : 배송완료시 적립되는 적립 포인트를 설정가능합니다.</p>
                    </td>
                </tr>
                <tr class="cont_point" style="<?=getData($info,"st_point_save_yn","N")!="C" ? "display:none;":""?>">
                    <th scope="col">적립 포인트 설정<em class="ess"></em></th>
                    <td>
                        <div class="inline-wrap">
                            <div class="input-text w100">
                                <?=inputTextNumber("st_point_save_per","st_point_save_per",getData($info,"st_point_save_per","0"),"%입력","",100)?>
                            </div>
                            <span style="font-size:12px;margin-left:5px;">%</span>
                        </div>
                        <p class="exc1">배송완료시 적립률을 설정합니다(0-10%)</p>
                        <p class="exc1">회원정보에 직립률 설정시 회원 정보에 설정한 적립률을 기준으로 적립됩니다.</p>
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
		if($("#st_point_limit_per").val() > 100){
			COMMON.alert("사용가능 포인트는 0-100%사이로 입력해주세요.",function(){
				$("#st_point_limit_per").focus();
			});
			return;
		}

		if($("[name=st_point_save_yn]:checked").val() =="C" && $("#st_point_save_per").val() > 10){
			COMMON.alert("적립 포인트는 0-10%사이로 입력해주세요.",function(){
				$("#st_point_save_per").focus();
			});
			return;
		}

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

	$("[name=st_point_save_yn]").change(function(){
		if($(this).val()=="C"){
			$(".cont_point").show();
		}else{
			$(".cont_point").hide();
		}
	});
});
</script>
