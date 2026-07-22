<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="/compage/index" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">포인트 충전 · 광고 구매 / 관리</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <?php $tab_on = "tab3";?>
    	<?php include_once (get_base_url() . '/application/shop/views/mobile/compage/top_line.php'); ?>
        <div class="tab-panel">
                
            <div class="point-logs">
                <h2 class="sr-text">포인트사용내역</h3>
                <div class="log-list">
                    <ul class="list">
                    	<?php foreach($use_list as $row) : ?>
                        <li>
                            <div class="log-info">
                                <p class="point txt-red"><strong>-<?=$row["pl_point"]?>P</strong></p>
                                <div class="meta">
                                    <p>사용일 : <em><?=($row["reg_dt"])?></em></p>
                                </div>
                            </div>
                            <div class="status">
                                <div class="meta">
                                    <!-- 지역 -->
                                    <p class="loc"><?=$row["pm_addr"]=="" ? "-":$row["pm_addr"]?></p>
                                    <!-- 사용처 -->
                                    <p class="txt-color2"><strong><?=$row["pl_desc"]?></strong></p>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                        <?php if(count($use_list)==0) : ?>
	                        <div class="is-empty">
				                <p>포인트 사용내역이 없습니다.</p>
				            </div>
			            <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div><!-- //page-content -->
</div>