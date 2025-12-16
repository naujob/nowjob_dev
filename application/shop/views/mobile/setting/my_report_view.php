<div class="page page-settings-view">    
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">나의 신고내역</h1>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="board-view">
            <div class="board-content">
                <div class="container">
                    <div class="article-header">
                        <div class="title-wrap">
                            <h2 class="title">신고내역</h2>
                            <div class="meta">
                                <p class="date"><?=$info["reg_dt"]?></p>
                            </div>
                        </div>
                        <?php if($info["br_type"]=="community") : ?>
                        	<a href="/setting/community_view/<?=$info["br_key"]?>" class="btn-round btn-secondary">게시글보기</a>
                       	<?php elseif($info["br_type"]=="trade") : ?>
                        	<a href="/trade/trade_view/<?=$info["br_key"]?>" class="btn-round btn-secondary">게시글보기</a>
                        <?php elseif($info["br_type"]=="resume") : ?>
                        	<a href="/resume/resume_detail/<?=$info["br_key"]?>" class="btn-round btn-secondary">게시글보기</a>
                        <?php elseif($info["br_type"]=="recruit") : ?>
                        	<a href="/recruit/recruit_detail/<?=$info["br_key"]?>" class="btn-round btn-secondary">게시글보기</a>
                        <?php endif; ?>
                    </div>
                    <div class="article-body">
                      <?=str_replace("\n","<br/>",$info["br_desc"])?>
                    </div>
                </div>
            </div>
            <div class="sticky-bottom">
                <button type="button" id="btnDelete" aria-haspopup="dialog" class="btn btn-secondary js-action1">신고취소</button>
            </div>
          
        </div>
    </div><!-- //page-content -->
</div>
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
        						location.replace("/setting/my_report");
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
