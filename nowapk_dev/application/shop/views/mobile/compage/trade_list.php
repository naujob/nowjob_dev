<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">중고상품 관리</h1>
        <a href="/trade/trade_write" class="btn-action">중고거래 등록</a>
    </div><!-- //top-bar -->

    <div class="page-content">
        <div class="section is-last">
            <div class="container">
                <!-- 목록 -->
                <ol class="result-list">
                    <?php foreach($result_list as $row) : ?>
                    <li class="list-item">
                        <div class="item-info sale">
                            <div class="thumb-wrap">
                                <!-- 썸네일 -->
                                <div class="thumb">
                                    <a href="/compage/trade_edit/<?=$row["td_idx"]?>"><img data-src="<?=$row["td_thumb"]?>" alt="" class="lozad"></a>
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
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ol>
                <!-- //목록 -->
            </div>
        </div>
    </div><!-- //page-content -->
</div>