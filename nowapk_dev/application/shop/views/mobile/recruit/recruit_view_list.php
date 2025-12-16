<?php
?>
<div class="page-recruit">

			<div class="top-bar">
	        <a href="javascript:history.back();" class="btn-replace"><span class="sr-text">최근본공고</span></a>
	        <h1 class="title" id="rm_subject">최근본공고</h1>
	    </div>


    <div class="page-content" style="padding-bottom: 0;">

        <div class="section is-last">
            <div class="container">
                <h2 class="sr-text">채용공고</h2>
                <!-- 목록 -->
                <ol class="result-list" id="ajaxList" data-page="1">
								<?php if (!empty($view_list)){ ?>
									<?php foreach($view_list as $row) : ?>
										<li class="list-item">
									        <div class="item-info recruit click-item"  data-link="/recruit/recruit_detail/<?=$row["rc_idx"]?>">
									            <div class="thumb-wrap">
									                <!-- 추천라벨 -->
									                <button type="button" data-bk_type="RECRUIT" data-bk_key="<?=$row["rc_idx"]?>" data-user_id="<?=$row["mm_id"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]?>"><span class="sr-text">즐겨찾기 추가</span></button>
									                <!-- 썸네일 -->
									                <div class="thumb">
									                    <a href="/recruit/recruit_detail/<?=$row["rc_idx"]?>"><img src="<?=$row["rc_thumb"]?>" alt=""></a>
									                </div>
									            </div>

									            <div class="content-wrap">
									                <div class="meta top">
									                    <!-- 직종라벨 -->
									                    <p class="category"><?=getWorkingImg($row["rc_work"],2)?></p>

									                    <!-- 근무시간 -->
									                    <p class="working-time"><?=$row["rt_work_type"]?></p>
									                     <!-- 목록선택 (제한20개) -->
									                </div>
													<!-- 제목 -->
									                <p class="comp_name"><?=$row["mm_comp_name"]?></p>
									                <!-- 제목 -->
									                <p class="title"><strong><a href="/recruit/recruit_detail/<?=$row["rc_idx"]?>"><?=$row["rc_name"]?></a></strong></p>
																	<!-- 지역 -->
																	<p class="area"><?=$row["ad_addr"]?></p>
									                <!-- 추가조건 -->
									                <div class="meta">
									                    <p><?=$row["rc_nation_nm"]?></p>
									                    <span>/</span>
									                    <p><?=$row["rc_age_nm"]?></p>
									                </div>
									                <!-- 지불방식 -->
									                <div class="pay">
									                    <p class="period"><?=getPriceType($row["rc_price_type"])?></p>
									                    <p class="amount"><?=getRecuitPrice($row)?></p>
									                </div>
									            </div>
									        </div>
									    </li>
									<?php endforeach; ?>
								<?php }else{ ?>
									<?php if($this->user->isLogin()) : ?>
			            	<li style="margin-top: 50%; text-align: center; color: #777;">최근 본 공고가 없습니다.</li>
			            <?php else : ?>
										<li style="margin-top: 50%; text-align: center; color: #777;">로그인 하시면,<br> 최근 본 공고를 확인 하실 수 있습니다.</li>
			            <?php endif; ?>
								<?php } ?>
                </ol>
                <!-- //목록 -->
                <!-- 빈목록 -->
	            <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
	                <p>등록된 정보가 없습니다.</p>
	            </div>
	            <!-- //빈목록 -->
            </div>
        </div>

    </div><!-- //page-content -->

</div>
<script>
$(document).ready(function(){
	$('body').css('overflow','auto'); //24.11.25 임시처리
});
</script>
