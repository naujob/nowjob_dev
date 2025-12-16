<form id="frm" class="controls" method="post">
    <input type="hidden" name="" value="" />
    <input type="hidden" />
    <div class="contents-frm">
    	<div class="section">
            <h3 class="sub-title mb-10">회원정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="180px"/>
                    <col width="600px"/>
                    <col width="180px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
               <tr>
                    <th scope="col">아이디</th>
                    <td>
                       	<div class="input-text ">
                            <?=$mem["mm_id"]?>
                        </div>
                    </td>
                    <th scope="col">회원명(사업자명)</th>
                    <td>
                       	<div class="input-text ">
                            <label>
                                <?=$mem["mm_name"]?><?=$mem["mm_type"]=="COMP" ? "(".$mem["mm_comp_name"].")":""?>
                            </label>
                        </div>
                    </td>
                 </tr>
                 <tr>
                    <th scope="col">휴대폰번호<em class="ess"></em></th>
                   	<td class="inline-wrap">
					     <div class="input-text  mt-5">
                            <label>
                            	<?=$mem["mm_phone"]?>
                            </label>
                        </div>
					</td>
                    <th scope="col">보유포인트</th>
                    <td>
                       	 <div class="input-text  mt-5">
                            <label>
                                <?=number_format($mem["mm_point"])?>P
                            </label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="section">
            <h3 class="sub-title mb-10">주문 정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="600px"/>
                     <col width="200px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">주문번호</th>
                    <td>
                    	<div>
						    <?=$info["o_num"] ?>
						</div>
					</td>
					<th scope="col">결제수단</th>
                    <td>
                    	<div>
						     <?=getPayMethod($info["o_paymethod"]) ?>
						</div>
					</td>
                </tr>
                <tr>
                    <th scope="col">주문일시</th>
                    <td>
                    	<div>
						    <?=$info["reg_dt"] ?>
						</div>
					</td>
					 <th scope="col">결제일시</th>
                    <td>
                    	<div>
						    <?=$info["o_pay_dt"] ?>
						</div>
					</td>
                </tr>
                <tr>
                    <th scope="col">진행상태</th>
                    <td>
                    	<div>
						   	<?=getOrderStatus($info["o_status"])?>
						</div>
					</td>
					 <th scope="col">취소일시</th>
                    <td>
                    	<div>
						   	<?=$info["o_status"]=="주문취소" ? $info["o_cancel_dt"]:"-"?>
						</div>
					</td>
                </tr>
                <tr>
                	<th scope="col">주문기기</th>
                    <td>
                    	<div>
						   	<?=$info["o_con_type"] == "W" ? "웹" : "모바일" ?>
						</div>
					</td>
                   
					 <th scope="col">TID</th>
                    <td>
                    	<div>
						   	<?=$info["o_tid"]?>
						</div>
					</td>
                </tr>
                
                <?php if($info["o_pay_yn"]=="Y" &&  $info["o_tid"]!="") : ?>
                <tr>
                    <th scope="col">결제결과</th>
                    <td colspan="3">
                    	<table class="table-form">
			        		<colgroup>
			        			<col width="150px" />
			        			<col width="*" />
			        		</colgroup>
			        		<tr>
			        			<th>결과코드</th>
			        			<td><?=$info["o_result_code"] ?></td>
			        		</tr>
			        		<tr>
			        			<th>결과 메시지</th>
			        			<td><?=$info["o_result_msg"] ?></td>
			        		</tr>
			        		
			        	</table>
					</td>
                </tr>
                <?php endif; ?>
               
                <?php if($info["o_paymethod"]=="CARD") : ?>
                <tr>
                    <th scope="col">추가정보(카드결제,가상계좌)</th>
                    <td colspan="3">
                    	<table class="table-form">
			        		<colgroup>
			        			<col width="150px" />
			        			<col width="*" />
			        		</colgroup>
			        		<?php if($info["o_paymethod"]=="CARD") : ?>
			        		<tr>
			        			<th>카드사명</th>
			        			<td><?=$info["o_card_name"] ?></td>
			        		</tr>
			        		<tr>
			        			<th>할부개월수</th>
			        			<td><?=$info["o_card_ins"] ?></td>
			        		</tr>
			        		<tr>
			        			<th>카드번호</th>
			        			<td><?=$info["o_card_num"] ?></td>
			        		</tr>
			        		<?php elseif($info["o_paymethod"]=="VBANK") : ?>
			        		<tr>
			        			<th>계좌번호</th>
			        			<td><?=$info["o_bank_num"] ?></td>
			        		</tr>
			        		<tr>
			        			<th>은행명</th>
			        			<td><?=$info["o_bank_name"] ?></td>
			        		</tr>
			        		<tr>
			        			<th>입금기한</th>
			        			<td><?=$info["o_bank_res_dt"] ?></td>
			        		</tr>
			        		<?php endif; ?>
			        	</table>
					</td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($info["o_status"]=="결제대기") : ?>
    	<div class="mt-20 tc">
   			<button type="button" data-o_num="<?=$info["o_num"] ?>" class="btn-m btn-type10 btnPayed">수동 입금처리</button>
   		</div>
		<?php endif; ?>
		
		<div class="section">
            <h3 class="sub-title mb-10">주문자/상품 정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
               	<tr>
                    <th scope="col">주문자 이름</th>
                    <td>
                    	<div class="input-text">
                    		<?=$info["o_oname"] ?>
                        </div>
					</td>
                </tr>
                <tr>
                	<th scope="col">주문자 연락처</th>
                    <td>
	                	<div class="input-text">
	                	  	<?=$info["o_ophone"] ?>
	                    </div>
					</td>
                </tr>
                <tr>
                	<th scope="col">상품명</th>
                    <td>
	                	<div class="input-text">
	                	  	<?=$info["o_goods_name"] ?>
	                    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">결제금액</th>
                    <td>
                    	<div class="input-text ">
                            <?=$info["o_total_price"] ?>
                        </div>
					</td>
                </tr>
               	<tr>
                    <th scope="col">쿠폰 사용금액 </th>
                    <td>
                    	<div class="input-text ">
                            <?=$info["o_coupon_price"] ?>
                        </div>
					</td>
                </tr>
                <?php if($info["o_coupon_idx"] > 0) : ?>
                <tr>
                    <th scope="col">사용 쿠폰명</th>
                    <td>
                    	<div class="input-text ">
                            <?=$info["o_coupon_idx"] ?>
                        </div>
					</td>
                </tr>
                <?php endif; ?>
                <tr>
                	<th scope="col">충전 포인트</th>
                    <td>
                    	<div class="input-text ">
                            <?=$info["o_ophone"] ?>
                        </div>
					</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>

<script>
	
	$(".btnNoPayCancel").click(function(){
		COMMON.confirm("취소 하시겠습니까?",function(){
			var params = {};
			params["o_num"] = "<?=$order["order"]["o_num"] ?>";
			comAjax("/admin/order/order_nopay_cancel",params,function(result){
				if(result.code == "00"){
					COMMON.alert("결제전 주문취소 처리 되었습니다.",function(){
						location.reload();
					});
				}else{
					COMMON.alert(result.msg);
				}
			});
		});
	});

	$(".btnPayed").click(function(){
		var o_num = $(this).data("o_num");
		COMMON.confirm("수동 입금처리 진행하시겠습니까?.",function(){
			var params = {};
			params["o_num"] = o_num;
	        comAjax("/admin/order/order_manual_pay_proc",params,function(result){
	            if(result.code == "00"){
	                location.reload();
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
		});
	});
	
</script>