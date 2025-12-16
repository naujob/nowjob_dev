<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab1";?>
            <?php $tab_on2 = "menu5";?>
        	<?php include_once (get_base_url() . '/application/shop/views/web/mypage/top_line.php'); ?>
            <div class="tab-panel on">

                <div class="point-logs">
                    <h2 class="sr-text">광고사용내역</h3>
                    <div class="log-list">
                        <ul class="list">
                            <?php foreach($promotion_list as $row) : ?>
	                        <li>
                                <div class="log-info">
                                    <p class="point"><strong><?=$row["pm_name"]?></strong></p>
	                                <div class="meta">
	                                    <p>사용기간 : <em><?=get_yyyymmdd($row["pm_sdate"])?>~<?=get_yyyymmdd($row["pm_edate"])?></em></p>
	                                </div>
                                </div>
                                <div class="usage" style="width: 15%;">
                                    <!-- 사용포인트 -->
                                    <p class="point txt-red">-<?=number_format($row["pm_point"])?>P</p>
                                    <!-- 지역 -->
                                    <p class="loc"><?=$row["pm_addr"]=="" ? "-":$row["pm_addr"]?></p>
                                </div>
                                 <?php if($row["pm_sdate"] > get_today() && $row["pm_edate"] > get_today()) : ?>
                                <div class="status" style="width:11.625em;">
                                    <button type="button" data-pm_idx="<?=$row["pm_idx"]?>" data-pm_name="<?=$row["pm_name"]?>" aria-haspopup="dialog" class="btnCancel btn-secondary btn-round">취소하기</button>
                                </div>
                                <?php else : ?>
                                <div class="status" style="width:11.625em;">
                                	</div>
                                <?php endif;?>
                            </li>
	                        <?php endforeach; ?>
	                      	<?php if(count($promotion_list)==0) : ?>
		                        <div class="is-empty">
					                <p>구매하신 광고 이력이 없습니다.</p>
					            </div>
				            <?php endif; ?>

                        </ul>
                    </div>
                </div>
                <div class="logs-footer">
                    <p class="help-msg">사용중이신 광고는 환불 및 취소가 불가합니다.</p>
                </div>

            </div>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->


<script>
$(document).ready(function(){
	$(".btnCancel").click(function(){
		var pm_name = $(this).attr("data-pm_name");
		var pm_idx = $(this).attr("data-pm_idx");

		openPopup('confirm', {
            content: '<span>“'+pm_name+'”</span>건<br> <em>광고 취소</em> 하시겠습니까?',
            secondCallback: function () {
                var params = {};
		        params["pm_idx"] = pm_idx;
		        comAjax("/mypage_proc/point_promotion_cancel",params,function(ret){
		            if(ret.code=="00"){
		                location.href = "/mypage/point_charge_list";
		            }else{
		            	commonAlert(ret.msg,function(){});
		            }
		        });
            }
        });
	});
});
</script>
