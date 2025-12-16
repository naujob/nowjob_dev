<form id="frm" class="controls" method="post">
    <input type="hidden" name="pl_idx" value="<?=getData($info,"pl_idx")?>" />
    <input type="hidden" id="mm_idx"  name="mm_idx" value="<?=getData($info,"mm_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />

    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">포인트 수동발급</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>

                </colgroup>
                <tbody>
                <tr style="display:none;">
                    <th scope="col">포인트 유형<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					      	 <?=inputRadio("pl_sub_type","pl_sub_type",["선물"=>"선물","인센티브"=>"인센티브"],getData($info,"pl_sub_type","선물"))?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">회원아이디<em class="ess"></em></th>
                    <td class="inline-wrap">
					    <div class="input-text w150">
                            <?=inputText("mm_id","mm_id",getData($info,"mm_id"),""," ",200)?>
                        </div>
                        (회원아이디 입력)
                        <a href="javascript:void(0);" class="ml-5 btn-ss btn-type2 btnPopMember" data-idx="" style="width:auto;">선택 </a>
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
                    <th scope="col">회원보유 포인트금액</th>
                    <td>
					    <div class="input-text w200" id="mm_point">
                            <?=getData($info,"mm_point",0)?>원
                        </div>
					</td>
                </tr>
                <tr style="display:none;">
                    <th scope="col">발급유형<em class="ess"></em></th>
                    <td>
					  	<div>
						    <?=inputSelect("pt_type","pt_type",["EVENT"=>"수동발행"],getData($info,"pl_type","EVENT"))?>
						</div>
					</td>
                </tr>
                <tr>
                    <th scope="col">가감여부<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					      	 <?=inputRadio("pl_type","pl_type",["plus"=>"포인트 적립","minus"=>"포인트 차감"],getData($info,"pl_type","plus"))?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">발급 포인트 금액<em class="ess"></em></th>
                    <td>
                    	<div class="inline-wrap">
						  	<div class="input-text w150">
								<?=inputNumber("pl_point","pl_point",getData($info,"pl_point"),"","",200)?>
							</div>
						</div>
					</td>
                </tr>
                <tr>
                    <th scope="col">발급 사유</th>
                    <td>
					  	<div class="input-text w500">
						    <?=inputText("pl_desc","pl_desc",getData($info,"pl_desc"),"","",200)?>
						</div>
					</td>
                </tr>
                <tr>
                    <th scope="col">발급일시</th>
                    <td>
					    <div class="input-text">
                            <label>
                                <?=getData($info,"reg_dt")?>
                            </label>
                        </div>
					</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <?php if(getProcMode($info)!="update") : ?>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
            <?php endif; ?>
        </div>
    </div>
</form>
<script>
	$(document).ready(function(){
		//회원선택
		$(".btnPopMember").click(function(){
			//PopupCenter("/admin/popup/member_select?sel_type=one_select","관련상품 선택",1200,800);

      var params = {};
      params["mm_id"] = $('#mm_id').val();
      comAjax("/admin/site/mem_point_chk",params,function(result){ /* deposit_edit_proc */
          console.log(result);
          if(result.code == "00"){
            if(result.data.data==undefined){
              $('#mm_name').html('회원없음');
              $('#mm_point').html('0원');
            }else{
              $('#mm_name').html(result.data.data.mm_name);
              $('#mm_point').html(Number(result.data.data.mm_point).toLocaleString()+'원');
            }
          //    COMMON.alert(result.msg,function(){
          //        location.href="/admin/site/deposit_list";
          //    });
          }else{
              COMMON.alert(result.msg);
          }
      });

		});

		//회원선택
		$(".btnPopCoupon").click(function(){
			if($("#mm_idx").val()==""){
	            COMMON.alert("회원을 선택후 조회 해주세요.",function(){$("#mm_id").focus();});
	            return;
	        }

			PopupCenter("/admin/popup/sales_coupon?mm_idx=" + $("#mm_idx").val(),"인센티브 금액조회",1200,800);
		});

		$("#btnList").click(function(){
	        history.go(-1);
	    });

	    $("#btnSave").click(function(){
	        // if($("#mm_idx").val()==""){
	        //     COMMON.alert("발급 대상 회원을 선택해주세요.",function(){$("#mm_id").focus();});
	        //     return;
	        // }
          if($("#mm_id").val()==""){
	            COMMON.alert("발급 대상 회원 아이디를 입력해주세요.",function(){$("#mm_id").focus();});
	            return;
	        }

	        if($("#pl_point").val()==""){
	            COMMON.alert("발급 포인트를 입력해주세요.",function(){$("#pl_point").focus();});
	            return;
	        }

	        var params = $("#frm").serialize();
	        comAjax("/admin/site/setting_deposit_edit_proc",params,function(result){ /* deposit_edit_proc */
              console.log(result);
	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    location.href="/admin/site/deposit_list";
	                });
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
	    });
	});

	function setPopMember(sel_type,mem_info){
		$("#mm_idx").val(mem_info.mm_idx);
		$("#mm_id").val(mem_info.mm_id);
		$("#mm_name").text(mem_info.mm_name);
		$("#mm_point").text(mem_info.mm_point.format());
	}

	function setPoint(point){
		$("#pl_point").val(point);
	}
</script>
