<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab1";?>
            <?php $tab_on2 = "menu2";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/mypage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="point-logs">
                    <h2 class="sr-text">포인트충전내역</h3>
                    <div class="log-list">
                        <ul class="list">
                            <?php foreach($order_list as $row) : ?>
                            <li>
                                <div class="log-info">
                                    <p class="point"><strong><?=number_format($row["pl_point"])?>P</strong></p>
                                    <div class="meta">
                                        <p>충전일 : <em><?=$row["pl_reg_dt"]?></em></p>
                                        <?php if($mem_info["mm_point"] > $row["pl_point"] && date("Y-m-d H:i:s") <= $row["o_refund_allow_dt"] && $row["o_status"]=="결제완료") : ?>
                                        	<p>환불가능기간 : <em><?=get_yyyymmdd($row["o_pay_dt"])?> ~ <?=get_yyyymmdd($row["o_refund_allow_dt"])?></em></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="status">
                                	<p class="txt-color2"><?=$row["pl_desc"]?></p>
                                     <?php if(date("Y-m-d H:i:s") <= $row["o_refund_allow_dt"] && $row["o_status"]=="결제완료") : ?>
                                    <button type="button" aria-haspopup="dialog" class="btn-dark btn-round btnRefund" data-date="<?=get_yyyymmdd($row["o_pay_dt"])?>" data-point="<?=($row["pl_point"])?>" data-o_num="<?=$row["o_num"]?>">환불가능</button>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <?php endforeach; ?>
                            <?php if(count($order_list)==0) : ?>
    	                        <div class="is-empty">
    				                <p>포인트 충전내역이 없습니다.</p>
    				            </div>
    			            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <?php if(count($order_list) > 0) : ?>
                <div class="logs-footer">
                    <p class="help-msg">충전후 미사용시에만 환불이 가능합니다.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
<input type="hidden" name="mm_point" value="<?=$mem_info["mm_point"]?>" />

<script>
$(document).ready(function(){
	$(".btnRefund").click(function(){
		var o_num = $(this).attr("data-o_num");
		var date = $(this).attr("data-date");
		var point = $(this).attr("data-point");
		var mm_point = $("[name=mm_point]").val();

		if(parseInt(mm_point) < parseInt(point)){
			commonAlert("보유하신 포인트가 부족합니다. 보유포인트 : " + mm_point.format(),function(){});
			return;
		}
        openPopup('confirm', {
	        content: '<span>“'+date+'”</span>건 <em>'+point.format()+'P</em> 환불하시겠습니까?',
	        secondCallback: function () {
	        	var params = {};
		        params["o_num"] = o_num;
		        comAjax("/order_proc/order_refund",params,function(ret){
		            if(ret.code=="00"){
		                location.reload();
		            }else{
		            	commonAlert(ret.msg,function(){});
		            }
		        });
	        },
	    });
	});
});
</script>
