<form id="frm" class="controls" method="post">
    <input type="hidden" name="sp_idx" value="<?=getData($info,"sp_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">이니시스 정보설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr class="hidden">
                    <th scope="col">결제수단<em class="ess"></em></th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("sp_name","sp_name",getData($info,"sp_name"),"결제수단","",100)?>
                            </label>
                        </div>
                    </td>
                </tr>
            	<tr class="hidden">
            		<th scope="col">기본 결제 수단 설정<em class="ess"></em></th>
            		<td>
                        <?=inputSelect("sp_default_yn","sp_default_yn",["Y"=>"기본 선택","N"=>"미선택"],getData($info,"sp_default_yn"))?>
                        <p class="exc1">기본 설정된 결제수단으로 결제가 진행됩니다.</p>
                    </td>
            	</tr>
            	
            	<tr>
                    <th scope="col">결제수단<em class="ess"></em></th>
                    <td>
                        <div class="input-label ">
                            <label class="txt-label w150">
                                <?=getData($info,"sp_name")?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">기본 결제 수단 설정<em class="ess"></em></th>
                    <td>
                        <div class="input-label ">
                            <label class="txt-label w150">
                                <?=getData($info,"sp_default_yn") =="Y" ? "기본 선택":"미선택"?>
                            </label>
                        </div>
                    </td>
                </tr>
               <tr>
            		<th scope="col">결제 모드 설정</th>
            		<td>
                        <?=inputSelect("sp_test_yn","sp_test_yn",["Y"=>"운영모드","N"=>"테스트모드"],getData($info,"sp_test_yn"),"class='w150'")?>
                        <p class="exc1">솔류션 테스트완료후 반드시 운영모드로 변경해주세요.</p>
                    </td>
            	</tr>
               
                <tr>
                    <th scope="col">이니시스 상점 ID</th>
                    <td>
                        <div class="input-text w300">
                            <label>
                                <?=inputText("sp_shop_id","sp_shop_id",getData($info,"sp_shop_id"),"상점ID","",200)?>
                            </label>
                        </div>
                        <p class="exc1">PG사에서 발급받은 상점 아이디를 입력하세요.</p>
                    </td>
                 </tr>
                 <tr>
                    <th scope="col">이니시스 SignKey</th>
                    <td>
                        <div class="input-text w300">
                            <label>
                                <?=inputText("sp_shop_key","sp_shop_key",getData($info,"sp_shop_key"),"SignKey","",200)?>
                            </label>
                        </div>
                        <p class="exc1">PG사에서 발급받은 상점 아이디 또는 사이트 코드를 입력하세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">INIAPI Key(취소용)</th>
                    <td>
                        <div class="input-text w300">
                            <label>
                                <?=inputText("sp_shop_cancel_key","sp_shop_cancel_key",getData($info,"sp_shop_cancel_key"),"","",200)?>
                            </label>
                        </div>
                        <p class="exc1">PG사에서 발급받은 상점 아이디 또는 사이트 코드를 입력하세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">이니시스 상점 Test ID</th>
                    <td>
                        <div class="input-text w300">
                            <label>
                                <?=inputText("sp_test_id","sp_test_id",getData($info,"sp_test_id"),"상점ID","",200)?>
                            </label>
                        </div>
                        <p class="exc1">이니시스 테스트 아이디는 "INIpayTest" 입니다.</p>
                    </td>
                </tr>
                 <tr>
                    <th scope="col">이니시스 Test SignKey</th>
                    <td>
                        <div class="input-text w300">
                            <label>
                                <?=inputText("sp_test_key","sp_test_key",getData($info,"sp_test_key"),"SignKey","",200)?>
                            </label>
                        </div>
                        <p class="exc1">이니시스 테스트 SignKey는 "SU5JTElURV9UUklQTEVERVNfS0VZU1RS" 입니다.</p>
                    </td>
                </tr>
                 <tr>
                    <th scope="col">Test INIAPI Key(취소용)</th>
                    <td>
                        <div class="input-text w300">
                            <label>
                                <?=inputText("sp_test_cancel_key","sp_test_cancel_key",getData($info,"sp_test_cancel_key"),"","",200)?>
                            </label>
                        </div>
                        <p class="exc1">PG사에서 발급받은 상점 아이디 또는 사이트 코드를 입력하세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">할부 설정</th>
                    <td>
                        <table class="table-form">
                    		<colgroup>
			                    <col width="150px"/>
			                    <col width="*"/>
			                </colgroup>
			                <tr>
                    			<th>할부 사용여부<em class="ess"></em></th>
                    			<td>
                    				<div class="inline-wrap">
			                        	<?=inputRadio("sp_ins_yn","sp_ins_yn",getOptionList("use_yn"),getData($info,"sp_ins_yn","Y"))?>
			                        </div>
                    			</td>
                    		</tr>
                    		<tr class="cont_ins <?=getData($info,"sp_ins_yn","Y")== "N" ? "hidden":""?>">
                    			<th>할부 설정</th>
                    			<td>
                    				<div class="input-text">
			                            <label class="inline-wrap">
			                                <?=inputCheck("sp_ins_months","sp_ins_months[]",getOptionList("month_list"),getData($info,"sp_ins_months","Y"))?>
			                            </label>
			                        </div>
                    			</td>
                    		</tr>
                    	</table>
                        <p class="exc1">카드 할부 개월을 설정해주세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">가상계좌 입금내역 통보 URL<em class="ess"></em></th>
                    <td>
                       <div class="input-text w500">
                            <label>
                                <?=inputText("sp_vbank_url","sp_vbank_url",getData($info,"sp_vbank_url"),"가상계좌 처리 URL","",200)?>
                            </label>
                        </div>
                        <p class="exc1">PG사에서 발급받은 상점 아이디 또상단의 URL을 복사하셔서</p>
                        <p class="exc1 txt-underline">가맹점관리자 > 거래내역 > 가상계좌 > 입금통보방식선택 메뉴의 입금내역 통보 URL 항목에 넣어주세요.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">가상계좌입금기한<em class="ess"></em></th>
                    <td>
                        <div class="input-text w100">
                            <label>
                                <?=inputNumber("sp_vbank_priod","sp_vbank_priod",getData($info,"sp_vbank_priod"),"개월수","",5)?>
                            </label>
                        </div>
                        <p class="exc1">가상계좌 입금기한일을 입력해 주세요</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" class="btn-l btn-type2 w150 btnList">목록으로</button>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>
<script>
	$("[name=sp_ins_yn]").change(function(){
		if($("[name=sp_ins_yn]:checked").val()=="N"){
			$(".cont_ins").addClass("hidden");
		}else{
			$(".cont_ins").removeClass("hidden");
		}
	});
	
	$(".btnList").click(function(){
		history.go(-1);
	});
	
	$("#btnSave").click(function(){
		var params = $("#frm").serialize();
		comAjax("/admin/setting/pay/setting_pg_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.href="/admin/setting/pay/setting_pg";	
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
	});
	
	$("[name=chg_pwd]").click(function(){
		if($(this).is(":checked")){
			$(".pwd_cont").removeClass("hidden");
		}else{
			$(".pwd_cont").addClass("hidden");
		}
	})
</script>
