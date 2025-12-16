<div id="content" class="page page-event">
    <main class="page-content">

        <div class="container">
            <h2 class="primary-title">이벤트</h2>

            <div class="board-view">
                <div class="view-header">
                    <!-- 게시물 번호 -->
                    <div class="no"><span><?=$info["bd_idx"]?></span></div>
                    <div class="content-wrap">
                        <!-- 게시물 카테고리 -->
                        <em class="board-category open">진행중</em>
                        <!-- <em class="board-category close">종료</em> -->
                        <div class="title-wrap">
                            <!-- 제목 -->
                            <h3 class="view-title"><?=$info["bd_subject"]?></h3>
                            <div class="meta">
                                <!-- 작성일 -->
                                <p class="ico-regdate"><?=get_yyyymmdd($info["bd_sdate"])?>~<?=get_yyyymmdd($info["bd_edate"])?></p>
                                <!-- 총조회수 -->
                                <p class="ico-viewing"><?=$info["bd_hit"]?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="view-body">
                    <!-- 내용 -->
                    <div class="view-content">
                        <?=str_replace("\n","<br/>",$info["bd_content"])?>
                        <?php if($info["bd_img1"]!="") : ?>
                        	<img src="<?=$info["bd_img1"]?>" />
                        <?php endif; ?>
                        <?php if($info["bd_img2"]!="") : ?>
                        	<img src="<?=$info["bd_img2"]?>" />
                        <?php endif; ?>
                    </div>
                    <!-- 기타정보 -->
                    <div class="view-meta">
                        <!-- 좋아요 -->
                        <button type="button" class="btn-like">
                            <span class="sr-text">좋아요</span>
                            <em class="cnt" id="favCnt"><?=$fav_cnt?></em>
                        </button>
                        <!-- 총댓글수 -->
                        <p class="total-comment"><?=count($cmt_list)?></p>
                    </div>
                </div>
                <!-- 게시글 댓글 -->
                <div class="comment-container">
                    <!-- 댓글 목록 -->
                    <ol class="comment-list">
                        <?php foreach($cmt_list as $comment) : ?>
                        <!-- 댓글 -->
                        <li id="commentID1" class="<?=$comment["reply_depth"]=="2" ? "is-reply":""?>">
                            <!-- 프로필 이미지 -->
                            <div class="thumb">
                                <img src="<?=getMemberImg($comment)?>" alt="" >
                            </div>
                            <div class="comment-body">
                                <!-- 댓글 작성자 -->
                                <p class="name"><?=$comment["mm_name"]?></p>
                                <!-- 댓글내용 -->
                                <div class="comment-content">
                                    <?=$comment["bc_content"]?>
                                </div>
                                <div class="comment-footer">
                                    <!-- 댓글 좋아요 -->
                                    <button type="button" class="btn btn-like-comment btnCmtFav" data-bc_idx="<?=$comment["bc_idx"]?>">좋아요<em class="cnt"><!-- 총횟수 --></em></button>
                                    <!-- 대댓글 쓰기 -->
                                    <button type="button" data-mm_name="<?=$comment["mm_name"]?>" data-bc_idx="<?=$comment["bc_idx"]?>" class="btn btnReply">답글쓰기</button>
                                </div>
                            </div>
                            <button type="button" class="btn-clear btnCommentDelete" data-bc_idx="<?=$comment["bc_idx"]?>" data-depth="<?=$comment["reply_depth"]?>"><span class="sr-text">삭제</span></button>
                        </li>
                        <?php endforeach; ?>
                    </ol>
                    <!-- 댓글 입력 -->
                    <div class="comment-form">
                        <form action="/">
                            <label class="input">
                                <span class="sr-text">댓글 입력</span>
                                <textarea name="bc_content" placeholder="댓글을 입력해 주세요" aria-required="true" required class="form-textarea"></textarea>
                            </label>
                            <button type="button" class="btn btn-primary btnComment">작성</button>
                        </form>
                    </div>
                </div>
                <!-- 이전,다음 이동 -->
                <ul class="view-navi">
                	<?php if($prev!=null) : ?>
                    <li class="prev">
                        <span class="dir">이전글</span>
                        <a href="/community/community_view/<?=$prev["bd_idx"]?>"><?=$prev["bd_subject"]?></a>
                    </li>
                    <?php else : ?>
					<li class="prev">
                        <span class="dir">이전글</span>
                        <a href="javascript:void(0);">-</a>
                    </li>
                    <?php endif; ?>

                    <?php if($next!=null) : ?>
                    <li class="next">
                        <span class="dir">다음글</span>
                        <a href="/community/community_view/<?=$next["bd_idx"]?>"><?=$next["bd_subject"]?></a>
                    </li>
                    <?php else : ?>
					<li class="prev">
                        <span class="dir">다음글</span>
                        <a href="javascript:void(0);">-</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="form-action-wrap">
                <a href="javascript:void(0);" id="btnList" class="btn-round btn-tertiary">목록</a>
            </div>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->

<input type="hidden" name="bd_idx" value="<?=$info["bd_idx"]?>" />
<script>
	var parent_idx = 0;
	$(document).ready(function(){
        $("#btnList").click(function(){
        	//location.href="/community/community_list";
          location.href="/community/event_list";
        });

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
        			params["br_type"] = "community";
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

		$("#btnDelete").click(function(){
			openPopup('confirm', {
                content:'문의하신 내용을<br> 삭제하시겠습니까?',
                secondCallback: function () {
                    var params = {};
    				params["bd_idx"] = $("[name=bd_idx]").val();
        			comAjax("/setting_proc/board_delete",params,function(ret){
        				if(ret.code=="00"){
        					commonAlert("삭제 처리 되었습니다.",function(){
        						location.replace("/community/community_list");
                            });
        				}else{
                            commonAlert("삭제처리시 오류가 발생하였습니다.",function(){});
        				}
        			});
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
			if($("#is_login").val()=="N"){
				commonAlert("로그인후 이용해주세요.",function(){});
				return;
			}

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
