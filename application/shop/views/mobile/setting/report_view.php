<div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">문의하기</h1>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="board-view">
            <div class="board-content">
                <div class="container">
                    <div class="article-header">
                        <h2 class="title"><?=$info["bd_subject"]?></h2>
                        <div class="meta">
                            <p class="date"><?=substr($info["reg_dt"],0,10)?></p>
                        </div>
                    </div>
                    <div class="article-body">
                        <?=str_replace("\n","<br/>",$info["bd_content"])?>
                        <?php if($info["bd_img1"]!="") : ?>
                        	<img src="<?=$info["bd_img1"]?>" />
                        <?php endif; ?>
                        <?php if($info["bd_img2"]!="") : ?>
                        	<img src="<?=$info["bd_img2"]?>" />
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if($info["bd_aws_yn"]=="Y") : ?>
            <div class="board-comment">
                <!-- 댓글 목록 -->
                <div class="comment-list">
                    <ol class="list">
                        <li class="list-item">
                            <div class="comment">
                                <!-- 프로필사진 -->
                                <div class="thumb"><img data-src="/resource/shop/mobile/img/common/noimage1.png" alt="" class="lozad"></div>
                                <div class="content-wrap">
                                    <p class="type">답변</p>
                                    <!-- 아이디 -->
                                    <p class="name"><?=$info["bd_aws_name"]?></p>
                                    <!-- 작성일 -->
                                    <p class="date"><?=substr($info["bd_aws_dt"],0,10)?></p>
                                    <!-- 내용 -->
                                    <div class="message">
                                        <?=str_replace("\n","<br/>",$info["bd_aws"])?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
            <?php else : ?>
            	<div class="" style="text-align:center;margin-top:30px;">
            		답변 대기중입니다.
            	</div>
            <?php endif; ?>
            <?php if($this->user->getUserId()==$info["mm_id"]) : ?>
            <div class="sticky-bottom">
                <button type="button" id="btnDelete" class="btn btn-secondary js-remove btnDelete">삭제</button>
                <button type="button" id="btnEdit" class="btn btn-primary">수정</button>
            </div>
            <?php endif; ?>
        </div>
    </div><!-- //page-content -->
</div>
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
        						location.replace("/setting/report");
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
			location.href="/setting/report_write/" + $("#bd_idx").val();
		});
	});
</script>
