<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab5";?>
            <?php $tab_on2 = "menu1";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="my-bookmark">
                    <h3 class="sr-text">중고거래 관리</h3>
                    <div class="result-header">
                        <p class="total">검색결과 <em><?=$result_count?></em>건</p>
                    </div>
                    <div class="result-wrap">

                        <!-- 목록 -->
                        <ol class="result-list">
                             <?php foreach($result_list as $row) : ?>
			                    <li class="list-item">
			                        <div class="item-info sale">
			                            <div class="thumb-wrap">
			                                <!-- 썸네일 -->
			                                <div class="thumb">
			                                    <a href="/mypage/trade_edit/<?=$row["td_idx"]?>"><img data-src="<?=$row["td_thumb"]?>" alt="" class="lozad"></a>
			                                </div>
			                            </div>
			                            <div class="content-wrap">
			                                <p class="title"><strong><a href="/trade/trade_detail/<?=$row["td_idx"]?>"><?=$row["td_name"]?></a></strong></p>
			                                <div class="meta">
			                                    <p class="area"><?=$row["ad_addr"]?></p>
			                                </div>
			                                <div class="price-wrap">
			                                    <p class="amount"><em class="num"><?=number_format($row["td_price"])?></em> 원</p>
			                                    <p class="date"><?=get_yyyymmdd($row["reg_dt"])?></p>
			                                </div>
			                            </div>
                                        <?php if($row["td_status"]=="Y") : ?>
                                            <em class="important-label">공개</em>
                                        <?php else : ?>
                                            <em class="disabled-label">비공개</em>
                                        <?php endif; ?>
			                            <div class="action">
                                            <button type="button" onclick="location.href='/trade/trade_detail/<?=$row["td_idx"]?>';" class="btn-round btn-tertiary">확인</button>
                                        </div>
			                        </div>
			                    </li>
			                <?php endforeach; ?>
                        </ol>
                        <!-- //목록 -->
                    </div>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
