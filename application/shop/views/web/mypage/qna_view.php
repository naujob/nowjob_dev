<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab1";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/mypage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="board-view">
                    <div class="view-header">
                        <!-- 게시물 번호 -->
                        <div class="no"><span><?=$row["bd_idx"]?></span></div>
                        <div class="content-wrap">
                            <!-- 게시물 카테고리 -->
                            <em class="board-category <?=getLabel($info["bd_cate"])?>"><?=$info["bd_cate"]?></em>
                            <div class="title-wrap">
                                <!-- 제목 -->
                                <h3 class="view-title"><?=$info["bd_subject"]?></h3>
                                <div class="meta">
                                    <!-- 작성일 -->
                                    <p class="ico-regdate"><?=get_yyyymmdd($info["reg_dt"])?></p>
                                </div>
                            </div>
                            <p class="status">
                                <em class="board-category <?=$info["bd_aws_yn"]=="Y" ? "":"dimmed"?>"><?=$info["bd_aws_yn"]=="Y" ? "답변완료":"미답변"?></em>
                            </p>
                        </div>
                    </div>
                    <div class="view-body">
                        <!-- 내용 -->
                        <div class="view-content">
                            <?=str_replace("\n","<br/>",$info["bd_content"])?>
                            <br><br>
                            <?php if($info["bd_img1"]!="") : ?>
                        	<img src="<?=$info["bd_img1"]?>" />
	                        <?php endif; ?>
	                        <?php if($info["bd_img2"]!="") : ?>
	                        	<br><img src="<?=$info["bd_img2"]?>" />
	                        <?php endif; ?>
                            <br>
                        </div>
                    </div>
                    <?php if($info["bd_aws_yn"]=="Y") : ?>
                    <div class="view-answer">
                        <div class="answer-header">
                            <p class="type">답변</p>
                            <div class="meta">
                                <p class="name"><?=$info["bd_aws_name"]?></p>
                                <p class="regdate"><?=get_yyyymmdd($info["bd_aws_dt"])?></p>
                            </div>
                        </div>
                        <div class="answer-content">
                            <?=str_replace("\n","<br/>",$info["bd_aws"])?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-action-wrap">
                    <button id="btnDelete"  type="button" class="btn-round btn-secondary">삭제</button>
                    <button id="btnEdit"  type="button" class="btn-round btn-tertiary">수정</button>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->

<input type="hidden" id="bd_aws_yn" name="bd_aws_yn" value="<?=$info["bd_aws_yn"]?>" />
<input type="hidden" id="bd_idx" name="bd_idx" value="<?=$info["bd_idx"]?>" />
<script>
	var parent_idx = 0;
	$(document).ready(function(){
		$("#btnDelete").click(function(){
			if($("[name=bd_aws_yn]").val()=="Y"){
				commonAlert("답변완료된 문의 정보는 삭제 하실수 없습니다.",function(){});
				return;
			}
			openPopup('confirm', {
                content:'문의하신 내용을<br> 삭제하시겠습니까?',
                secondCallback: function () {
                    var params = {};
    				params["bd_idx"] = $("[name=bd_idx]").val();
        			comAjax("/setting_proc/board_delete",params,function(ret){
        				if(ret.code=="00"){
        					commonAlert("삭제 처리 되었습니다.",function(){
        						location.replace("/mypage/qna_list");
                            });
        				}else{
                            commonAlert("삭제처리시 오류가 발생하였습니다.",function(){});
        				}
        			});
                }
            });
		});
		
		$("#btnEdit").click(function(){
			if($("[name=bd_aws_yn]").val()=="Y"){
				commonAlert("답변완료된 문의 정보는 수정 하실수 없습니다.",function(){});
				return;
			}
			location.href="/mypage/qna_write/" + $("#bd_idx").val();
		});
	});
</script>
