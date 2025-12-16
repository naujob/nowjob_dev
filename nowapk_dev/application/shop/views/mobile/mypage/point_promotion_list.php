<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">포인트 충전 · 광고 구매 / 관리</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <?php $tab_on = "tab5";?>
        <?php include_once (get_base_url() . '/application/shop/views/mobile/mypage/top_line.php'); ?>
        <div class="tab-panel">
            <div class="point-logs">
                <h2 class="sr-text">광고사용내역</h3>
                <div class="log-list">
                    <ul class="list">
                    	<?php foreach($promotion_list as $row) : ?>
                        <li>
                            <div class="log-info top">
                                <p class="point"><strong><?=$row["pm_name"]?></strong></p>
                                <div class="meta">
                                  <?php
                                  $pm_edate = $row["pm_edate"];
                                  if (substr($pm_edate, -8) === "00:00:59") {
                                      $pm_edate = date("Y-m-d H:i:s", strtotime($pm_edate) - 60);
                                  }
                                  ?>
                                    <p>사용기간 : <em><?=get_yyyymmdd($row["pm_sdate"])?>~<?=get_yyyymmdd($pm_edate)?></em></p>
                                </div>
                            </div>
                            <div class="status top">
                                <div class="meta">
                                    <!-- 사용포인트 -->
                                    <p class="point txt-red">-<?=number_format($row["pm_point"])?>P</p>
                                    <!-- 지역 -->
                                    <p class="loc"><?=$row["pm_addr"]=="" ? "-":$row["pm_addr"]?></p>
                                </div>
                                <?php if($row["pm_sdate"] > get_today() && $row["pm_edate"] > get_today()) : ?>
                                <button type="button" data-pm_idx="<?=$row["pm_idx"]?>" data-pm_name="<?=$row["pm_name"]?>" aria-haspopup="dialog" class="btnCancel btn-secondary btn-round">취소하기</button>
                                <?php endif;?>
                            </div>
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
            <?php if(count($promotion_list) > 0) : ?>
            <div class="logs-footer">
                <div class="container">
                    <p class="help-msg">사용중이신 광고는 취소가 불가합니다.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div><!-- //page-content -->
</div>

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
