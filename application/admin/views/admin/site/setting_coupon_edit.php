<form id="frm" class="controls" method="post">
    <input type="hidden" name="cp_idx" value="<?=getData($info,"cp_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">쿠폰발급 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
          
                </colgroup>
                <tbody>
                	 <tr>
                    <th scope="col">쿠폰 상태<em class="ess"></em></th>
                    <td>
					    <div class="inline-wrap">
					    	<?=inputRadio("cp_status","cp_status",["Y"=>"노출","N"=>"비노출"],getData($info,"cp_status","Y"))?>
					    </div>
					</td>
                </tr>
              
                <tr >
                    <th scope="col">발행방법<em class="ess"></em></th>
                    <td>
					    <div class="inline-wrap">
					    	<?=inputRadio("cp_auto_yn","cp_auto_yn",["Y"=>"자동발행","N"=>"수동발행"],getData($info,"cp_auto_yn","Y"))?>
					    </div>
					</td>
                </tr>
                 <tr>
                    <th scope="col">쿠폰명<em class="ess"></em></th>
                    <td class="inline-wrap">
					  	<div class="input-text w300">
						    <?=inputText("cp_name","cp_name",getData($info,"cp_name"),"","",40)?>
						</div>
					</td>
                </tr>
                <tr style="display:none;">
                    <th scope="col">자동 발행 기간 유형<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					     	<?=inputRadio("cp_auto_type","cp_auto_type",["A"=>"기간없음","D"=>"기간설정"],getData($info,"cp_auto_type","D"))?>
					    </div>
					</td>
                </tr>
                <tr class="cont_date_auto <?=getData($info,"cp_auto_type","D")=="D" ? "":"hidden"?>"  style="display:none;">
                    <th scope="col">자동 발행 기간설정<em class="ess"></em></th>
                    <td class="inline-wrap date-period">
                    	<label>
                            <?=inputText("cp_auto_sdate","cp_auto_sdate",substr(getData($info,"cp_auto_sdate"),0,10),"","class='form-start'",200)?>
                        </label>
                        <span>~</span>
                        <label>
                             <?=inputText("cp_auto_edate","cp_auto_edate",substr(getData($info,"cp_auto_edate"),0,10),"","class='form-end'",200)?>
                        </label>
					</td>
                </tr>
                <tr style="display:none;">
                    <th scope="col">사용 기간 유형<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					     	<?=inputRadio("cp_use_type","cp_use_type",["A"=>"기간없음","D"=>"기간설정"],getData($info,"cp_use_type","D"))?>
					    </div>
					</td>
                </tr>
                
                <tr class="cont_date_use <?=getData($info,"cp_use_type","D")=="D" ? "":"hidden"?>" style="display:none;">
                    <th scope="col">사용 기간설정<em class="ess"></em></th>
                    <td class="inline-wrap date-period">
                    	<label>
                            <?=inputText("cp_use_sdate","cp_use_sdate",substr(getData($info,"cp_use_sdate"),0,10),"","class='form-start'",200)?>
                        </label>
                        <span>~</span>
                        <label>
                             <?=inputText("cp_use_edate","cp_use_edate",substr(getData($info,"cp_use_edate"),0,10),"","class='form-end'",200)?>
                        </label>
					</td>
                </tr>
                <tr class="cont_date_use <?=getData($info,"cp_use_type","D")=="D" ? "":"hidden"?>">
                    <th scope="col">사용 기간설정<em class="ess"></em></th>
                    <td>
					   <div class="input-text w150">
                            <label>
                                <?=inputNumber("cp_use_day","cp_use_day",getData($info,"cp_use_day",30),"","",200)?>
                            </label>
                        </div>
                         <p class="exc1">발행일로 부터 사용기간</p>
					</td>
                </tr>
                <tr>
                    <th scope="col">쿠폰 금액<em class="ess"></em></th>
                    <td class="inline-wrap">
					  	<div class="input-text w150">
                            <label>
                                <?=inputNumber("cp_price","cp_price",getData($info,"cp_price"),"","",200)?>
                            </label>
                        </div>
                        <div class="input-text w80">
                            <label>
                                <?=inputSelect("cp_dan","cp_dan",["won"=>"원","per"=>"%"],getData($info,"cp_dan"))?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">쿠폰 최소사용금액<em class="ess"></em></th>
                    <td>
					    <div class="input-text w150">
                            <label>
                                <?=inputNumber("cp_limit_price","cp_limit_price",getData($info,"cp_limit_price"),"","",200)?>
                            </label>
                        </div>
					</td>
                </tr>
                <t>
                    <th scope="col">발급관리</th>
                    <td>
					    <div>
                         	총 쿠폰 발급수 : 0명
                        </div>
                        <div class="mt-10">
                        	<a href="javascript:void(0);" class="btn-xs btn-type7 btnAddCoupon" style="width:auto;">수동발급 바로가기</a>	
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
		//금액상태 확인
		if($("[name=cp_type]:checked").val()=="delivery"){
			$("#cp_price").val("0");
			$("#cp_price").prop("readonly",true);
		}else{
			$("#cp_price").prop("readonly",false);
		}
		
		$("#btnList").click(function(){
	        history.go(-1);
	    });
	    
	    $("[name=cp_type]").change(function(){
    		if($("[name=cp_type]:checked").val()=="delivery"){
    			$("#cp_price").val("0");
    			$("#cp_price").prop("readonly",true);
    		}else{
    			$("#cp_price").prop("readonly",false);
    		}
	    });
	    
	    $("[name=cp_auto_type]").change(function(){
    		if($("[name=cp_auto_type]:checked").val()=="D"){
    			$(".cont_date_auto").removeClass("hidden");
    		}else{
    			$(".cont_date_auto").addClass("hidden");
    		}
	    });
	    
	    $("[name=cp_use_type]").change(function(){
    		if($("[name=cp_use_type]:checked").val()=="D"){
    			$(".cont_date_use").removeClass("hidden");
    		}else{
    			$(".cont_date_use").addClass("hidden");
    		}
	    });
	    
	    $("#btnSave").click(function(){
	        if($("#cp_name").val()==""){
	            COMMON.alert("쿠폰명을 입력해주세요.",function(){$("#cp_name").focus();});
	            return;
	        }
	        
	        if($("#cp_price").val()==""){
	            COMMON.alert("쿠폰 금액을 입력해주세요.",function(){$("#cp_price").focus();});
	            return;
	        }
	        
	        if($("#cp_dan").val()=="won" && ($("#cp_price").val() < 100 || $("#cp_price").val() > 100000)){
	        	COMMON.alert("쿠폰 금액은 100 ~ 100,000원 사이<br/>값으로 입력해주세요.",function(){$("#cp_price").focus();});
	            return;
	        }
	        
	        if($("#cp_dan").val()=="per" && ($("#cp_price").val() < 1 || $("#cp_price").val() > 80)){
	        	COMMON.alert("쿠폰 할인률은 1%~ 80% 사이<br/>값으로 입력해주세요.",function(){$("#cp_price").focus();});
	            return;
	        }
	        
	        if($("#cp_limit_price").val()==""){
	            COMMON.alert("쿠폰 최소 사용금액을 입력해주세요.",function(){$("#cp_limit_price").focus();});
	            return;
	        }
	        
	        var params = $("#frm").serialize();
	        comAjax("/admin/site/setting_coupon_edit_proc",params,function(result){
	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    location.href="/admin/site/setting_coupon"; 
	                });
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
	    });
	});
</script>