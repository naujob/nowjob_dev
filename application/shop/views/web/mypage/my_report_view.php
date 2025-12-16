<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab1";?>
        	<?php include_once (get_base_url() . '/application/shop/views/web/mypage/top_line.php'); ?>
            <div class="tab-panel on">

                <h3 class="sr-text">나의 신고 내역</h3>

                <div class="board-view">
                    <div class="view-header">
                        <!-- 게시물 번호 -->
                        <div class="no"><span>100</span></div>
                        <div class="content-wrap">
                            <div class="title-wrap">
                                <!-- 제목 -->
                                <h3 class="view-title"><?=$info["subject"]?></h3>
                                <div class="meta">
                                    <!-- 작성일 -->
                                    <p class="ico-regdate"><?=$info["reg_dt"]?></p>
                                </div>
                            </div>
                            <div class="status">
                                <?php if($info["br_type"]=="community") : ?>
		                        	<a href="/community/community_view/<?=$info["br_key"]?>" class="btn">게시글보기</a>
		                       	<?php elseif($info["br_type"]=="trade") : ?>
		                        	<a href="/trade/trade_view/<?=$info["br_key"]?>" class="btn">게시글보기</a>
		                        <?php elseif($info["br_type"]=="resume") : ?>
		                        	<a href="/resume/resume_detail/<?=$info["br_key"]?>" class="btn">게시글보기</a>
		                        <?php elseif($info["br_type"]=="recruit") : ?>
		                        	<a href="/recruit/recruit_detail/<?=$info["br_key"]?>" class="btn">게시글보기</a>
		                        <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="view-body">
                        <!-- 내용 -->
                        <div class="view-content">
                            <?=str_replace("\n","<br/>",$info["br_desc"])?>
                        </div>
                    </div>
                </div>
                <div class="form-action-wrap align-right">
                    <button id="btnDelete" type="button" class="btn-round btn-tertiary">신고취소</button>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
<input type="hidden" id="br_idx" name="br_idx" value="<?=$info["br_idx"]?>" />

<script>
	var parent_idx = 0;
	$(document).ready(function(){
		$("#btnDelete").click(function(){
			openPopup('confirm', {
                content:'신고를 취소하시겠습니까?',
                secondCallback: function () {
                    var params = {};
    				params["br_idx"] = $("[name=br_idx]").val();
        			comAjax("/setting_proc/report_delete",params,function(ret){
        				if(ret.code=="00"){
        					commonAlert("삭제 처리 되었습니다.",function(){
        						location.replace("/mypage/my_report_list");
                            });
        				}else{
                            commonAlert("삭제처리시 오류가 발생하였습니다.",function(){});
        				}
        			});
                }
            });
		});
	});
</script>
