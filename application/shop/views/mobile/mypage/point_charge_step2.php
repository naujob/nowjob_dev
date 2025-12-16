<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">포인트 충전 · 광고 구매 / 관리</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <?php $tab_on = "tab1";?>
        <?php include_once (get_base_url() . '/application/shop/views/mobile/mypage/top_line.php'); ?>
        <div class="tab-panel">
            <div class="section point-charge step2">
                <h2 class="sr-text">포인트충전 결제정보</h3>
                <form action="/">

                    <div class="container">
                        <div class="form-row">
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
                                <button id="btnCoupon" type="button" class="btn btn-tertiary">적용</button>
                            </div>
                        </div>

                        <div class="form-row">
                            <p class="form-label">결제정보</p>
                            <div class="form-toggle-group col-2">
                                <label class="form-toggle-btn3">
                                    <input type="radio" name="payment" value="CARD" checked>
                                    <span class="btn">신용카드</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="radio" name="payment" value="BANK">
                                    <span class="btn">계좌이체</span>
                                </label>
                                <!--label class="form-toggle-btn3">
                                    <input type="radio" name="payment" value="HPP">
                                    <span class="btn">휴대폰</span>
                                </label>
                                <label class="form-toggle-btn3">
                                    <input type="radio" name="payment" value="VBANK">
                                    <span class="btn">가상계좌</span>
                                </label-->
                                <label class="form-toggle-btn3 hidden payment_coupon">
                                    <input type="radio" name="payment" class="paymethod_coupon" value="COUPON">
                                    <span class="btn">쿠폰결제</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-row">
                            <label class="form-checkbox">
                                <input type="checkbox" name="agreement" value="1" aria-required="true" required class="sr-text">
                                <span class="label">구매확인</span>
                                <span class="desc">구매하실 포인트명, 및 가격을 확인하였으며,<br> 이에 동의합니다.</span>
                            </label>
                            <div class="terms-wrap">
                                <p>전자상거래법8조 2항</p>
                                <p>사업자와 전자결제업자등은 전자적 대금지급이 이루어지는 경우 소비자가 입력한 정보가 소비자의 진정 의사 표시에 의한 것인지를 확인함에 있어 주의를 다하여야 한다.</p>
                            </div>
                        </div>
                    </div>

                    <div class="total-cost">
                        <div class="container">
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
                        </div>
                    </div>
                    <div class="sticky-bottom">
                        <button type="button" id="btnPrev" class="btn btn-secondary">이전단계</button>
                        <button type="button" id="btnNext" class="btn btn-primary">결제하기</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- //page-content -->
</div>
<form id="frmOrder" name="frmOrder" method="post">
	<input type="hidden" name="o_pg_name" value="<?=$pg_info["sp_name"]?>" />
	<input type="hidden" name="point_key" value="<?=$point_key?>" />
	<input type="hidden" name="point_price" value="<?=$point_price?>" />
	<input type="hidden" name="coupon_idx" value="0" />
	<input type="hidden" name="payment_coupon_yn" value="N" />
	<input type="hidden" name="pay_method" value="" />
</form>

<?php if($pg_info["sp_name"]=="이니시스") : ?>
<form id="frmInicis" name="" method="POST" target="_self" accept-charset="euc-kr" style="display:none;">
    <!-- 필수 -->
    <div style="border:2px #dddddd double;padding:10px;background-color:#f3f3f3;">
        <input type="hidden" name="P_INI_PAYMENT" value="" />
        <input type="hidden" name="P_MID" value="" />
		<input type="hidden" name="P_OID" value="" />
		<input type="hidden" name="P_AMT" value="" />
		<input type="hidden" name="P_GOODS" value="" />
		<input type="hidden" name="P_UNAME" value="" />
		<input type="hidden" name="P_NEXT_URL" value="" />
		<input type="hidden" name="P_NOTI_URL" value="" />
		<input type="hidden" name="P_HPP_METHOD" value="1" />
		<input type="hidden" name="P_RESERVED" value="" />
		<input type="hidden" name="P_CHARSET" value="utf8" />
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
        return false;
            });
        return false;
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
            commonAlert("구매확인에 동의해주세요.",function(){
            });
            return;
        }

        if($("[name=payment]:checked").val()=="BANK"){
            commonAlert("계좌이체(현금결제)는 고객센터로 문의 바랍니다.(*현금영수증 발행을 희망하는 경우 꼭 말씀해주세요.)<br/>고객센터 : 031-255-0119",function(){});
            return;
        }

        $("[name=pay_method]").val($("[name=payment]:checked").val());

      	//주문번호 생성후 결제 진행.
		var form = $('#frmOrder');
		var params = new FormData(form[0]);
		fileAjax("/order_proc/order_insert_proc",params,function(result){
			if(result.code == "00"){
				//이니시스 데이터 만들어서 결제 진행.
				var ini_params = {};
				ini_params["o_num"] = result.data.o_num;

				comAjax("/order_proc/order_pg_proc",ini_params,function(ini_result){
					if(ini_result.code == "00"){
						//쿠폰결제인 경우 바이패스
						if(ini_result.data.paytype=="Coupon"){
							_replace("/order_proc/order_online_proc/" + result.data.o_num);
							return;
						}

						//여기서 데이터 설정
						$("#frmInicis").find("[name=P_INI_PAYMENT ]").val(ini_result.data.paytype);
						$("#frmInicis").find("[name=P_MID]").val(ini_result.data.mid);
						$("#frmInicis").find("[name=P_GOODS]").val(ini_result.data.goodsname);
						$("#frmInicis").find("[name=P_OID]").val(ini_result.data.o_num);
						$("#frmInicis").find("[name=P_AMT]").val(ini_result.data.price);
						$("#frmInicis").find("[name=P_NEXT_URL]").val(ini_result.data.nextUrl);
						$("#frmInicis").find("[name=P_NOTI_URL]").val(ini_result.data.notiUrl);
                        $("#frmInicis").find("[name=P_RESERVED]").val("ismart_use_sign=Y&twotrs_isp=Y&twotrs_isp_noti=N&block_isp=Y&vbank_receipt=Y&bank_receipt=Y&apprun_check=Y&app_scheme=nowjob2://&extension_enable=Y");
						$("#frmInicis").attr("action","https://mobile.inicis.com/smart/payment/");
						$("#frmInicis").attr("target","_self");
						$("#frmInicis").submit();
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
