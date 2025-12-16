<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">즐겨찾기 관리</h1>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="section my-bookmark">
            <div class="container">

                <p class="result-summary">
                    검색결과 <em><?=$count?></em>건
                </p>
                <div class="result-wrap">

                    <!-- 목록 -->
                    <ol class="result-list">
                        <?php foreach($list as $row) : ?>
                        <li class="list-item">
                            <div class="item-info sale">
                                <div class="thumb-wrap">
                                    <!-- 추천라벨 -->
                                    <p class="recomm-badge"><span>추천</span></p>
                                    <!-- 즐겨찾기 추가된 목록: on -->
                                    <button type="button" data-bk_type="TRADE" data-bk_key="<?=$row["td_idx"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 해제</span></button>
                                    <!-- 썸네일 -->
                                    <div class="thumb">
                                        <a href="/trade/trade_detail/<?=$row["td_idx"]?>"><img data-src="<?=getTradeImg($row)?>" alt="" class="lozad"></a>
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
                                    <label class="form-checkbox">
                                        <input type="radio" name="chk" value="<?=$row["td_idx"]?>" class="sr-text">
                                        <span class="label-color1"></span>
                                        <span class="sr-text">목록 선택</span>
                                    </label>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ol>
                    <!-- //목록 -->
                </div>

                <div class="sticky-bottom">
                    <button type="button" class="btnRequest btn btn-primary">채팅으로 거래하기</button>
                </div>
            </div>
        </div>
    </div><!-- //page-content -->
</div>
<script>
	$(".btnRequest").click(function(){
        //로그인 여부 확인
        if($("#is_login").val()!="Y"){
            commonLogin();
            return;
        }

		if($("[name=chk]").is(":checked")==false){
            commonAlert("거래하실 상품을 선택해주세요.",function(){});
            return;
        }
	});
</script>
