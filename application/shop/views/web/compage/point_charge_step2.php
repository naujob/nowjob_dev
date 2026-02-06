<div id="content" class="page page-mypage">
   <main class="page-content">
       <div class="container">
           <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
           <div class="tab-panel on">
               <div class="point-charge step2">
                   <h3 class="sr-text">포인트충전 결제정보</h3>
                   <form action="/">

                       <div class="charge-payment">
                           <div class="col">
                               <p class="form-label">쿠폰</p>
                               <div class="coupon-status">
                                   <p>보유 쿠폰 : <em><?=$coupon_count?></em>장</p>
                                   <p>사용 가능 쿠폰 : <em><?=$valid_count?></em>장</p>
                               </div>
                               <div class="inline-group">
                                   <label class="form-selectbox">
                                        <span class="sr-text">쿠폰선택</span>
                                        <?php if(count($coupon_list)==0) : ?>
	                                    <!-- 사용가능한 쿠폰이 없을때 : disabled -->
	                                    <select id="coupon" disabled>
	                                        <option value="" hidden>사용가능한 쿠폰이 없습니다</option>
	                                    </select>
	                                    <?php else: ?>
	                                    <select id="coupon" name="coupon">
	                                    	<option value="" >쿠폰을 선택해주세요</option>
	                                    	<?php foreach($coupon_list as $coupon) : ?>
	                                        <option value="<?=$coupon["cl_idx"]?>" data-cl_idx="<?=$coupon["cl_idx"]?>" data-cl_price="<?=$coupon["cl_price"]?>" data-cl_dan="<?=$coupon["cl_dan"]?>" data-cl_limit_price="<?=$coupon["cl_limit_price"]?>"  ><?=$coupon["cp_name"]?></option>
	                                        <?php endforeach; ?>
	                                    </select>
	                                    <?php endif; ?>

                                       <span class="liner"></span>
                                   </label>
                                   <button type="button" id="btnCoupon" class="btn-round btn-tertiary">적용</button>
                               </div>
                           </div>
                           <div class="col">
                               <p class="form-label">결제정보</p>
                               <div class="form-group">
                                   <label class="form-toggle-btn2">
                                       <input type="radio" name="payment" value="CARD" checked>
                                       <span class="btn">신용카드</span>
                                   </label>
                                   <label class="form-toggle-btn2">
                                       <input type="radio" name="payment" value="BANK">
                                       <span class="btn">계좌이체</span>
                                   </label>
                                   <!--label class="form-toggle-btn2">
                                       <input type="radio" name="payment" value="HPP">
                                       <span class="btn">휴대폰</span>
                                   </label>
                                   <label class="form-toggle-btn2">
                                       <input type="radio" name="payment" value="VBANK">
                                       <span class="btn">가상계좌</span>
                                   </label-->
                                   <label class="form-toggle-btn2 hidden payment_coupon">
                                       <input type="radio" name="payment" class="paymethod_coupon" value="COUPON">
                                       <span class="btn">쿠폰결제</span>
                                   </label>
                               </div>
                               <div class="terms-wrap">
                                   <p><strong>전자상거래법8조 2항</strong></p>
                                   <p>사업자와 전자결제업자등은 전자적 대금지급이 이루어지는 경우 소비자가 입력한 정보가 소비자의 진정 의사 표시에 의한 것인지를 확인함에 있어 주의를 다하여야 한다.</p>
                               </div>
                           </div>
                       </div>

                       <div class="charge-agreement">
                           <label class="form-checkbox lg">
                               <input type="checkbox" name="agreement" value="1" aria-required="true" required class="sr-text">
                               <span class="label">
                                   <strong>구매확인</strong>
                                   <span class="desc">구매하실 포인트명, 및 가격을 확인하였으며, 이에 동의합니다.</span>
                               </span>
                           </label>
                       </div>

                       <div class="total-cost">
                           <p class="secondary-title">최종 결제금액</p>
                           <table class="cost-table">
                               <caption><span class="sr-text">최종 결제금액</span></caption>
                               <colgroup><col><col></colgroup>
                               <tbody>
                                   <tr>
                                        <td class="label">총 상품금액</td>
                                        <td class="value"><em id="goods_price"><?=number_format($point_price);?></em>원</td>
                                    </tr>
                                    <tr>
                                        <td class="label">총 할인금액</td>
                                        <td class="value"><em id="discount_price">0</em>원</td>
                                    </tr>
                                    <tr>
                                        <td class="label">총 결제금액</td>
                                        <td class="value total"><em id="total_price"><?=number_format($point_price);?></em>원</td>
                                    </tr>
                               </tbody>
                           </table>
                           <button type="button" id="btnNext" class="btn-round btn-tertiary">결제하기</button>
                       </div>

                   </form>
               </div>
           </div>
       </div>
   </main><!-- //page-content -->
</div><!-- //page -->

<form id="frmOrder" name="frmOrder" method="post">
	<input type="hidden" name="o_pg_name" value="<?=$pg_info["sp_name"]?>" />
	<input type="hidden" name="point_key" value="<?=$point_key?>" />
	<input type="hidden" name="point_price" value="<?=$point_price?>" />
	<input type="hidden" name="coupon_idx" value="0" />
	<input type="hidden" name="payment_coupon_yn" value="N" />
	<input type="hidden" name="pay_method" value="" />
</form>

<?php if($pg_info["sp_name"]=="이니시스") : ?>
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<form id="frmInicis" name="" method="POST" style="display:none;">
    <!-- 필수 -->
    <div style="border:2px #dddddd double;padding:10px;background-color:#f3f3f3;">
        <br/><input name="version" value="1.0" >
        <br/><input  type="text"  style="width:100%;" name="mid" value="" >
        <br/><input  type="text"  style="width:100%;" name="goodname" value="" >
        <br/><input  type="text"  style="width:100%;" name="oid" value="" >
        <br/><input  type="text"  style="width:100%;" name="price" value="" >
        <br/><input  type="text"  style="width:100%;" name="currency" value="WON" >
        <br/><input  type="text"  style="width:100%;" name="buyername" value="" >
        <br/><input  type="text"  style="width:100%;" name="buyertel" value="" >
        <br/><input  type="text"  style="width:100%;" name="buyeremail" value="" >
        <input type="text"  style="width:100%;" name="timestamp" value="" >
        <input type="text" style="width:100%;" name="signature" value="" >
        <br/><input  style="width:100%;" name="returnUrl" value="" >
        <input type="hidden"  name="mKey" value="" >
        <input type="hidden" style="width:100%;" name="gopaymethod" value="" >
        <input style="width:100%;" name="offerPeriod" value="" >
        <input style="width:100%;" id="billPrint_msg" name="billPrint_msg" value="" >
        <input style="width:100%;" name="languageView" value="" >
        <input style="width:100%;" name="charset" value="" >
        <input style="width:100%;" name="payViewType" value="" >
        <input style="width:100%;" name="closeUrl" value="" >
        <input style="width:100%;" name="popupUrl" value="" >
        <input  style="width:100%;" name="merchantData" value="" >
        <input  style="width:100%;" name="acceptmethod" value="Vbank(<?=$res_dt?>)" >
    </div>
</form>
<?php endif; ?>


<script>
$(document).ready(function(){
	$("[name=payment]").click(function(){
		if($(this).val()!="COUPON" && $("[name=payment_coupon_yn]").val()=="Y"){
			commonAlert("결제수단이 변경되었습니다.<br/>쿠폰을 다시 선택해주세요.",function(){
				location.reload();
			});
		}
	});

	$("#btnCoupon").click(function(){
		var $coupon = $("#coupon");

		if($coupon.val()==""){
			commonAlert("적용할 쿠폰을 선택해주세요.",function(){
				$("#coupon").focus();
            });
      return;
		}

		var goods_price = $("[name=point_price]").val();
		var discount_price = 0;
		var cl_price = $coupon.find('option:selected').attr('data-cl_price');
		var cl_limit_price = $coupon.find('option:selected').attr('data-cl_limit_price');
		var cl_dan = $coupon.find('option:selected').attr('data-cl_dan');
		var cl_idx = $coupon.find('option:selected').attr('data-cl_idx');

		if(cl_dan=="won"){
			discount_price = cl_price;
		}else{
			discount_price = Math.round(goods_price * (cl_price/100));
		}
		calcTotalPrice(parseInt(goods_price),parseInt(discount_price),cl_idx);
	});

	$("#btnNext").click(function(){
        if($("[name=payment]").is(":checked")==false){
            commonAlert("결제 수단을 선택해주세요.",function(){
            });
            return;
        }

        if($("[name=agreement]").is(":checked")==false){
            commonAlert("구매 약관에 동의해주세요.",function(){
            });
            return;
        }

        if($("[name=payment]:checked").val()=="BANK"){
            commonAlert("계좌이체(현금결제)는 고객센터로 문의 바랍니다.(*현금영수증 발행을 희망하는 경우 꼭 말씀해주세요.)<br/>고객센터 : 032-833-0018",function(){});
            return;
        }

        $("[name=pay_method]").val($("[name=payment]:checked").val());

        $("[name=o_escrow_yn]").val("N");

      	///주문번호 생성후 결제 진행.
		var form = $('#frmOrder');
		var params = new FormData(form[0]);
		fileAjax("/order_proc/order_insert_proc",params,function(result){
			if(result.code == "00"){
				//이니시스 데이터 만들어서 결제 진행.
				var ini_params = {};
				ini_params["o_num"] = result.data.o_num;
				comAjax("/order_proc/order_pg_proc",ini_params,function(ini_result){
					if(result.code == "00"){
						//온라인주문인경우 결제 대기 처리.
                        if(ini_result.data.paytype=="Coupon"){
							location.href="/order_proc/order_online_proc/" + result.data.o_num;
							return;
						}

						//여기서 데이터 설정
						$("#frmInicis").find("[name=gopaymethod ]").val(ini_result.data.paytype);
						$("#frmInicis").find("[name=mid]").val(ini_result.data.mid);//$info["sp_test_yn"] == "N" ? $info["sp_test_id"] : $info["sp_shop_id"]
						$("#frmInicis").find("[name=goodname]").val(ini_result.data.goodsname);
						$("#frmInicis").find("[name=oid]").val(ini_result.data.o_num);
						$("#frmInicis").find("[name=price]").val(ini_result.data.price);
						$("#frmInicis").find("[name=signature]").val(ini_result.data.sign);
						$("#frmInicis").find("[name=buyername]").val(ini_result.data.buyername);
						$("#frmInicis").find("[name=buyertel]").val(ini_result.data.buyertel);
						$("#frmInicis").find("[name=buyeremail]").val(ini_result.data.buyeremail);
						$("#frmInicis").find("[name=timestamp]").val(ini_result.data.timestamp);
						$("#frmInicis").find("[name=returnUrl]").val(ini_result.data.returnUrl);
						$("#frmInicis").find("[name=closeUrl]").val(ini_result.data.closeUrl);
						$("#frmInicis").find("[name=mKey]").val(ini_result.data.mKey);

						INIStdPay.pay('frmInicis');
					}else{
                        commonAlert("데이터 처리시 오류가 발생하였습니다.",function(){});
					}
				});

			}else{
                commonAlert(result.msg,function(){});
			}
		});
	});

  var reqidx = '<?=$reqidx?>';
  if(reqidx != '0'){
      $('#coupon').val(reqidx);
      var optionText = $('#coupon option:selected').text();
      $('.liner').html(optionText);
      $('#btnCoupon').trigger('click');
  }

});//ready

function calcTotalPrice(goods_price,discount_price,coupon_idx){
	$("[name=coupon_idx]").val(coupon_idx);
	$("#discount_price").html(discount_price.format());

	if(goods_price > discount_price){
		if($("[name=payment_coupon_yn]").val()=="Y"){
			$("[name=payment]").eq(0).prop("checked",true);
		}

		$("[name=payment_coupon_yn]").val("N");
		$(".payment_coupon").addClass("hidden");
		$("#total_price").html((goods_price - discount_price).format());
	}else{
		//결제 수단 모두 제거
		$("[name=payment_coupon_yn]").val("Y");
		$(".payment_coupon").removeClass("hidden");
		$(".paymethod_coupon").prop("checked",true);
		$("#total_price").html((0).format());
	}

}
</script>
