 <div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab8";?>
            <?php $tab_on2 = "menu2";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="manage-list">
                    <ol class="list">
                        <li>
                            <!-- 아웃소싱 업체명 -->
                            <div class="item-info company">
                                <div class="thumb-wrap">
                                    <div class="thumb"><img data-src="<?=$mem_info["mm_thumb"]?>" alt="" class="lozad"></div>
                                </div>
                                <div class="content-wrap">
                                    <p class="name"><em><?=$mem_info["mm_comp_name"]?></em></p>
                                    <div class="meta">
                                        <p class="ico-phone"><?=$mem_info["mm_phone"]?></p>
                                        <p class="ico-email"><?=$mem_info["mm_id"]?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- 아웃소싱 멤버 -->
                            <div class="result-list">
                                <ol class="list">
                                    <?php foreach($result_list as $row) : ?>
		                            <li>
                                        <div class="item-info profile">
                                            <div class="thumb-wrap">
                                                <div class="thumb"><img data-src="<?=$row["mm_thumb"]?>" alt="" class="lozad"></div>
                                            </div>
                                            <div class="content-wrap">
                                                <div class="inline-group">
                                                    <p class="name"><?=$row["mm_comp_name"]?></p>
                                                    <div class="meta">
                                                        <p class="tel"><?=$row["mm_phone"]?></p>
                                                        <p class="email"><?=$row["mm_id"]?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="action">
                                                <a href="/compage/recruit_list" class="btn-round btn-tertiary">공고내역보기</a>
                                            </div>
                                        </div>
                                    </li>
		                            <?php endforeach; ?>
                                </ol>
                                <p class="help-msg">아웃소싱을 활성화하면 광고내역을 공유하실 수 있습니다.</p>
                            </div>
                            <!-- //아웃소싱 멤버 -->

                        </li>
                    </ol>
                </div>

            </div>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->


<script>
	$(".btnStatus").click(function(){
		var yn = $(this).data("yn");
		var mm_idx = $(this).data("mm_idx");
		var mm_comp_num = $(this).data("mm_comp_num");
		
		openPopup('confirm', {
            content:"멤버" +(yn=="Y" ? "승인":"취소")+ " 하시겠습니까?", 
            firstButton:'취소', 
            secondButton:'확인', 
            firstCallback: function () {
            }, 
            secondCallback:  function () {
                var params = {};
    			params["mm_idx"] = mm_idx;
    			params["yn"] = yn;
    			params["mm_comp_num"] = mm_comp_num;
    			comAjax("/compage_proc/out_proc",params,function(ret){
    				if(ret.code=="00"){
                        location.reload();
    				}else{
                        commonAlert("처리시 오류가 발생하였습니다.관리자에게 문의 바랍니다.",function(){});
    				}
    			});
            }, 
        });
	});
</script>