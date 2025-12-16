<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab1";?>
            <?php $tab_on2 = "menu3";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/mypage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="point-logs">
                    <h2 class="sr-text">포인트충전내역</h3>
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
                                        <p class="txt-color2"><?=$row["pl_desc"]?></p>
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
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
