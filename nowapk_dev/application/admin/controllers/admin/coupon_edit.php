<form id="frm" class="controls" method="post">
    <input type="hidden" name="cl_idx" value="<?=getData($info,"cl_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    <input type="hidden" id="mm_idx"  name="mm_idx" value="<?=getData($info,"mm_idx")?>" />
    <input type="hidden" id="cl_use_day"  name="cl_use_day" value="<?=getData($info,"cl_use_day")?>" />

    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">적립금 발급</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>

                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">쿠폰사용 상태<em class="ess"></em></th>
                    <td>
					    <div class="inline-wrap">
					    	<?=inputRadio("cl_use_yn","cl_use_yn",["N"=>"미사용","Y"=>"사용완료"],getData($info,"cl_use_yn","N"))?>
					    </div>
					    <p class="exc1 d-block">사용상태가 사용완료인경우는 쿠폰 수정이 불가능합니다. </p>
					</td>
                </tr>
                <tr>
                    <th scope="col">회원아이디<em class="ess"></em></th>
                    <td class="inline-wrap">
					    <div class="input-text w200">
                            <?=inputText("mm_id","mm_id",getData($info,"mm_id"),""," ",200)?>
                        </div>
                        (회원아이디 입력)
                        <!-- <a href="javascript:void(0);" class="ml-2 btn-ss btn-type2 btnPopMember" data-idx="" style="width:auto;">선택 </a> -->
					</td>
                </tr>
                <tr>
                    <th scope="col">회원명</th>
                    <td>
					    <div class="input-text w200" id="mm_name">
                            <?=getData($info,"mm_name")?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">쿠폰선택<em class="ess"></em></th>
                    <td>
					    <div>
						   	<select id="cp_idx" name="cp_idx" style="width:300px;">
						   		<option value="">-- 쿠폰선택 --</option>
						   		<?php foreach($coupon_list as $cp) : ?>
							   		<option <?=(getData($info,"cp_idx")==$cp["cp_idx"]) ? "selected":""?> value="<?=$cp["cp_idx"]?>" data-cp_use_type="<?=$cp["cp_use_type"]?>" data-cp_use_sdate="<?=substr($cp["cp_use_sdate"],0,10)?>" data-cp_use_edate="<?=substr($cp["cp_use_edate"],0,10)?>" data-cp_use_day="<?=($cp["cp_use_day"])?>"  data-cp_price="<?=$cp["cp_price"]?>" data-cp_limit_price="<?=$cp["cp_limit_price"]?>"  >
							   			<?=$cp["cp_name"]?>
							   		</option>
						   		<?php endforeach;?>
						   	</select>
						</div>
					</td>
                </tr>
                <tr style="display:none;">
                    <th scope="col">사용 기간 유형<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					     	<?=inputRadio("cl_use_type","cl_use_type",["A"=>"기간없음","D"=>"기간설정"],getData($info,"cl_use_type","D"))?>
					    </div>
					</td>
                </tr>
                <tr class="cont_date_use <?=getData($info,"cp_use_sdate","D")=="D" ? "":"hidden"?>" style="display:none;">
                    <th scope="col">사용 기간설정<em class="ess"></em></th>
                    <td class="inline-wrap date-period">
                    	<label>
                            <?=inputText("cl_use_sdate","cl_use_sdate",substr(getData($info,"cl_use_sdate"),0,10),"","class='form-start'",200)?>
                        </label>
                        <span>~</span>
                        <label>
                             <?=inputText("cl_use_edate","cl_use_edate",substr(getData($info,"cl_use_edate"),0,10),"","class='form-end'",200)?>
                        </label>
					</td>
                </tr>
                <tr class="cont_date_use">
                    <th scope="col">사용기간<em class="ess"></em></th>
                    <td class="inline-wrap date-period cont_days" >

					</td>
                </tr>
                 <tr>
                    <th scope="col">쿠폰 금액<em class="ess"></em></th>
                    <td>
					  	<div class="input-text w150">
                            <label>
                                <?=inputNumber("cl_price","cl_price",getData($info,"cl_price"),"","readonly",200)?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">쿠폰 최소사용금액<em class="ess"></em></th>
                    <td>
					    <div class="input-text w150">
                            <label>
                                <?=inputNumber("cl_limit_price","cl_limit_price",getData($info,"cl_limit_price"),"","readonly",200)?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">발급일시</th>
                    <td>
					    <div class="input-text">
                            <?=getData($info,"reg_dt")?>
                        </div>
					</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <?php if(getData($info,"cl_use_yn","N")!="Y") : ?>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
	$(document).ready(function(){
		$("#btnList").click(function(){
	        history.go(-1);
	    });

	    //회원선택
		$(".btnPopMember").click(function(){
			PopupCenter("/admin/popup/member_select?sel_type=one_select","관련상품 선택",1200,800);
		});

	    $("[name=cl_use_type]").change(function(){
    		if($("[name=cl_use_type]:checked").val()=="D"){
    			$(".cont_date_use").removeClass("hidden");
    		}else{
    			$(".cont_date_use").addClass("hidden");
    		}
	    });

	    $("[name=cp_idx]").change(function(){
	    	changeCoupon();
	    });


	    $("[name=cp_idx]").val("<?=$cp_idx=="0" ? "":$cp_idx?>");
	    changeCoupon();

	    $("#btnSave").click(function(){
	        // if($("#mm_idx").val()==""){
	        //     COMMON.alert("회원을 선택해주세요.",function(){$("#mm_idx").focus();});
	        //     return;
	        // }

          if($("#mm_id").val()==""){
	            COMMON.alert("회원 아이디를 입력해주세요.",function(){$("#mm_id").focus();});
	            return;
	        }

	        if($("#cp_idx").val()==""){
	            COMMON.alert("쿠폰을 선택해주세요.",function(){$("#cp_idx").focus();});
	            return;
	        }

	        if($("#cl_use_type").val()=="D"){
	        	if($("#cl_use_sdate").val()==""){
	        		COMMON.alert("사용 기간을 설정해주세요.",function(){$("#cl_use_sdate").focus();});
	            	return;
	        	}

	        	if($("#cl_use_edate").val()=="D"){
	        		COMMON.alert("사용 기간을 설정해주세요.",function(){$("#cl_use_edate").focus();});
	            	return;
	        	}
	        }

	        if($("#cl_price").val()==""){
	            COMMON.alert("쿠폰 금액을 입력해주세요.",function(){$("#cl_price").focus();});
	            return;
	        }

	        if($("#cl_limit_price").val()==""){
	            COMMON.alert("쿠폰 최소 사용금액을 입력해주세요.",function(){$("#cl_limit_price").focus();});
	            return;
	        }

	        var params = $("#frm").serialize();
	        comAjax("/admin/site/coupon_edit_proc",params,function(result){
              //console.log(result);

	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    location.href="/admin/site/coupon_list";
	                });
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
	    });
	});

	function changeCoupon(){
		if($("[name=cp_idx]").val()==""){
    		return;
    	}
		var cp_use_type = $("[name=cp_idx]").find(":selected").data("cp_use_type");
		var cp_use_sdate = $("[name=cp_idx]").find(":selected").data("cp_use_sdate");
		var cp_use_edate = $("[name=cp_idx]").find(":selected").data("cp_use_edate");
		var cp_price = $("[name=cp_idx]").find(":selected").data("cp_price");
		var cp_limit_price = $("[name=cp_idx]").find(":selected").data("cp_limit_price");
		var cp_use_day = $("[name=cp_idx]").find(":selected").data("cp_use_day");

		if(cp_use_type=="A"){
			$("#cl_use_type_A").prop("checked",true);
		}else{
			$("#cl_use_type_D").prop("checked",true);
		}
		$("#cl_use_sdate").val(cp_use_sdate);
		$("#cl_use_edate").val(cp_use_edate);
		$("#cl_price").val(cp_price);
		$("#cl_limit_price").val(cp_limit_price);

		if($("[name=cl_use_type]:checked").val()=="D"){
			$(".cont_date_use").removeClass("hidden");
		}else{
			$(".cont_date_use").addClass("hidden");
		}

		$(".cont_days").text("발급일로부터 " + cp_use_day + "일");
		$("[name=cl_use_day]").val(cp_use_day);
	}

	function setPopMember(sel_type,mem_info){
		$("#mm_idx").val(mem_info.mm_idx);
		$("#mm_id").val(mem_info.mm_id);
		$("#mm_name").text(mem_info.mm_name);
	}
</script>
