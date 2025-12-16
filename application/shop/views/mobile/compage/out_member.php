<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">아웃소싱 관리</h1>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="tab-container">
            <ul class="tab-list">
                <li class="on"><span class="tab">아웃소싱 멤버관리</span></li>
                <li><a href="/compage/out_promotion" class="tab">아웃소싱 광고관리</a></li>
            </ul>
        </div>

        <div class="tab-panel">
            <h2 class="sr-text">아웃소싱 멤버관리</h2>

            <div class="profile-list">
                <ol class="list">
                    <li>
                        <!-- 아웃소싱 업체명 -->
                        <div class="item-info company">
                            <div class="thumb-wrap">
                                <div class="thumb"><img data-src="<?=$mem_info["mm_thumb"]?>" alt="" class="lozad"></div>
                                <p class="outsourcing-badge"><span class="sr-text">아웃소싱</span></p>
                            </div>
                            <div class="content-wrap">
                                <p class="name"><em><?=$mem_info["mm_comp_name"]?></em></p>
                                <div class="meta">
                                    <p class="tel"><?=$mem_info["mm_phone"]?></p>
                                    <p class="email"><?=$mem_info["mm_id"]?></p>
                                </div>
                            </div>
                        </div>

                        <!-- 아웃소싱 멤버 -->
                        <ol class="list">
                        	<?php foreach($result_list as $row) : ?>
                            <li>
                                <div class="item-info person">
                                    <div class="thumb-wrap">
                                        <div class="thumb"><img data-src="<?=$row["mm_thumb"]?>" alt="" class="lozad"></div>
                                    </div>
                                    <div class="content-wrap">
                                        <p class="name"><em><?=$row["mm_comp_name"]?></em>님</p>
                                        <div class="meta">
                                            <p class="tel"><?=$row["mm_phone"]?></p>
                                            <p class="email"><?=$row["mm_id"]?></p>
                                        </div>
                                        <div class="action">
                                        	<?php if($row["mm_comp_num_yn"]=="N") : ?>
                                            	<button type="button" class="btnStatus btn-round btn-tertiary" data-yn="Y" data-mm_idx="<?=$row["mm_idx"]?>" data-mm_comp_num="<?=$row["mm_comp_num"]?>" >멤버승인</button>
                                            <?php else : ?>
												<button type="button" class="btnStatus btn-round btn-secondary" data-yn="N" data-mm_idx="<?=$row["mm_idx"]?>" data-mm_comp_num="<?=$row["mm_comp_num"]?>">멤버취소</button>
											<?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ol>
                        <!-- //아웃소싱 멤버 -->
                    </li>
                </ol>
            </div>
            <div class="container">
                <p class="help-msg">아웃소싱을 활성화하면 광고내역을 공유하실수 있습니다.</p>
            </div>
        </div>
    </div><!-- //page-content -->
</div>

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