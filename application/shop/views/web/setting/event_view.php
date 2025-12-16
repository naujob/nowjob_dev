<div class="page page-settings-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">이벤트</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <div class="tab-container">
            <ul class="tab-list">
                <li class="<?=$date_status=="ing" ? "on":"" ?>"><a href="/setting/event?date_status=ing" class="tab">진행중 이벤트</a></li>
                <li class="<?=$date_status=="end" ? "on":"" ?>"><a href="/setting/event?date_status=end" class="tab">종료 이벤트</a></li>
            </ul>
        </div>

        <div class="tab-panel">

            <div class="board-view">
	            <div class="board-content">
	                <div class="container">
	                    <div class="article-header">
	                        <!-- 20220929 수정 -->
	                        <div class="meta">
	                            <p class="date"><?=substr($info["reg_dt"],0,10)?></p>
	                            <p class="viewing"><?=$info["bd_hit"]?></p>
	                        </div>
	                        <h2 class="title"><?=$info["bd_subject"]?></h2>
	                    </div>
	                    <div class="article-body">
	                        <?=str_replace("\n","<br/>",$info["bd_content_m"])?>
	                        <?php if($info["bd_img1"]!="") : ?>
	                        	<img src="<?=$info["bd_img1"]?>" />
	                        <?php endif; ?>
	                        <?php if($info["bd_img2"]!="") : ?>
	                        	<img src="<?=$info["bd_img2"]?>" />
	                        <?php endif; ?>
	                    </div>
	                </div>
	            </div>
	            <div class="board-aside">
	                <div class="container">
	                    <p class="total-like">
	                        <button type="button" class="btn-like"><span class="sr-text">좋아요</span></button>
	                        <em class="cnt" id="favCnt"><?=$fav_cnt?></em>
	                    </p>
	                    <p class="total-comment"><span class="sr-text">총댓글수</span><em class="cnt"><?=count($cmt_list)?></em></p>
	                </div>
	            </div>
	            <div class="board-comment">
	                <!-- 댓글 목록 -->
	                <div class="comment-list">
	                    <ol class="list">
	                        <?php foreach($cmt_list as $comment) : ?>
	                        <li class="list-item <?=$comment["reply_depth"]=="2" ? "is-reply":""?>">
	                            <div class="comment">
	                                <!-- 프로필사진 -->
	                                <div class="thumb"><img data-src="<?=getMemberImg($comment)?>" alt="" class="lozad"></div>
	                                <div class="content-wrap">
	                                    <!-- 아이디 -->
	                                    <p class="name"><?=$comment["mm_name"]?></p>
	                                    <!-- 내용 -->
	                                    <div class="message">
	                                		<?php if($comment["reply_depth"]=="2") : ?>
	                                			<a href="javascript:void(0);" class="reply-to"><?=$comment["mm_name"]?></a>
	                                		<?php endif; ?>
	                                        <?=$comment["bc_content"]?>
	                                    </div>
	                                    <?php if($comment["reply_depth"]=="1") : ?>
	                                    <div class="action">
	                                        <button type="button" data-bc_idx="<?=$comment["bc_idx"]?>" class="btn btnCmtFav">좋아요 <span></span></button>
	                                        <button type="button" data-mm_name="<?=$comment["mm_name"]?>" data-bc_idx="<?=$comment["bc_idx"]?>" class="btn btnReply">답글쓰기</button>
	                                    </div>
	                                    <?php endif; ?>
	                                </div>
	                            </div>
	                            <?php if($this->user->getUserIdx()==$comment["mm_idx"]) : ?>
								<!-- .js-del-item // 제거 -->
	                            <button type="button" class="btn-clear btnCommentDelete" data-bc_idx="<?=$comment["bc_idx"]?>" data-depth="<?=$comment["reply_depth"]?>"><span class="sr-text">삭제</span></button>
	                            <?php endif; ?>
	                        </li>
	                        <?php endforeach; ?>
	                    </ol>
	                    <?php if(count($cmt_list)==0) : ?>
	                    <!-- 빈목록 -->
	                    <div class="is-empty_v2">
	                        <p>등록된 댓글이 없습니다.</p>
	                    </div>
	                    <?php endif ?>
	                </div>
	                <!-- 입력폼 -->
	                <div class="sticky-bottom write-form">
	                    <form id="frm" onsubmit="return false">
	                        <label>
	                            <span class="sr-text">댓글입력</span>
	                            <input type="text" name="bc_content" autocomplete="off" placeholder="댓글을 입력해주세요.">
	                        </label>
	                        <button type="button" class="btn-primary btnComment">전송</button>
	                    </form>
	                </div>
	            </div>
	            <?php if($this->user->getUserId()==$info["mm_id"]) : ?>
	            <div class="sticky-bottom">
	                <button type="button" class="btn btn-secondary js-remove">삭제</button>
	                <button type="button" onclick="location.href='/setting/community_write/<?=$info["bd_idx"]?>';" class="btn btn-primary">수정</button>
	            </div>
	            <?php endif; ?>
	        </div>
        </div>
    </div><!-- //page-content -->
</div>
<input type="hidden" name="bd_idx" value="<?=$info["bd_idx"]?>" />

<!-- 신고하기 양식 -->
<template id="ReportTemp">
    <div class="alert-msg">
        <p>해당게시글을 신고하시겠습니까?<br> 신고사유를 작성해주세요</p>
        <div class="report-form">
            <label>
                <span class="sr-text">신고사유입력</span>
                <textarea name="br_desc" placeholder="신고사유를 작성해주세요" aria-required="true" required class="form-textarea js-autoheight"></textarea>
            </label>
        </div>
    </div>
</template>
<!-- //신고하기 양식 -->
<script>
	var parent_idx = 0;
	$(document).ready(function(){
        $("#btnReport").click(function(){
            var content = document.querySelector('#ReportTemp').innerHTML;
            var popup = openPopup('modal', {
                id:'ReportWriter',
                title:'신고하기',
                content: content,
                firstButton: '확인',
                removeClose : false,
                clickOnce : false,
                firstCallback: function () {
                    if($("[name=br_desc]").val()==""){
                        commonAlert("내용을 입력해주세요.",function(){
                            $("[name=br_desc]").focus();
                        });
                        return false;
                    }
                    var params = {};
        			params["bd_idx"] = $("[name=bd_idx]").val();
                    params["br_desc"] = $("[name=br_desc]").val();
        			comAjax("/setting_proc/board_report",params,function(ret){
        				if(ret.code=="00"){
                            removeElement("#ReportWriter1");
        					commonAlert("신고 처리 되었습니다.",function(){
                            });
        				}else{
                            commonAlert("신고 처리시 오류가 발생하였습니다.",function(){});
        				}
        			});
                }
            });
        });

        $("#btnFav").click(function(){
			var params = {};
			params["bd_idx"] = $("[name=bd_idx]").val();
			comAjax("/setting_proc/board_fav",params,function(ret){
				if(ret.code=="00"){
					$("#favCnt").html(ret.data.fav_cnt);
				}else{

				}
			});
		});

		$(".btnCmtFav").click(function(){
			var params = {};
			params["bd_idx"] = $("[name=bd_idx]").val();
			params["bc_idx"] = $(this).data("bc_idx");
			comAjax("/setting_proc/board_cmt_fav",params,function(ret){
				if(ret.code=="00"){
					$("#favCnt").html(ret.data.fav_cnt);
				}else{

				}
			});
		});

		$(".btnReply").click(function(){
			var mm_name = $(this).data("mm_name");
			parent_idx = $(this).data("bc_idx");
			$("[name=bc_content]").attr("placeholder",mm_name + "답글을 입력해주세요.");
			$("[name=bc_content]").focus();
		});

		$(".btnComment").click(function(){
			if($("[name=bc_content]").val()==""){
				commonAlert("댓글 내용을 입력해주세요.",function(){
					$("[name=bc_content]").focus();
                });
				return;
			}

			var params = {};
			params["proc_mode"] = "insert";
			params["bd_idx"] = $("[name=bd_idx]").val();
			params["parent_idx"] = parent_idx;
			params["bc_content"] = $("[name=bc_content]").val();

			comAjax("/setting_proc/board_comment_proc",params,function(result){
				if(result.code == "00"){
					location.reload();
					$(".page-content").scrollTop(1000000);
				}else{
					commonAlert("댓글 입력시 오류가 발생하였습니다.",function(){});
				}
			});
		});

		$(".btnCommentDelete").click(function(){
			var params = {};
			params["proc_mode"] = "delete";
			params["bc_idx"] = $(this).data("bc_idx");
			params["depth"] = $(this).data("depth");

			comAjax("/setting_proc/board_comment_proc",params,function(result){
				if(result.code == "00"){
					location.reload();
				}else{
					COMMON.alert(result.msg);
				}
			});
		});
	});
</script>
