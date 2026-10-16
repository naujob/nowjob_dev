<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab2";?>
            <?php $tab_on2 = "menu1";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/mypage/top_line_2025.php'); ?>
            <div class="tab-panel on">
                <div class="manage-my-content">
                    <form action="/">
                        <h3 class="sr-text">이력서 관리</h3>
                        <div class="board-wrap">
                            <table class="board-list">
                                <colgroup><col><col style="width:12%"><col style="width:11%"><col></colgroup>
                                <tbody>
                                    <?php foreach($resume_list as $row) : ?>

                                    <tr>
                                        <td class="selectable-item">
                                            <label class="form-checkbox lg">
                                                <input type="radio" name="rd_show_yn" value="<?=$row["ru_idx"]?>" data-ru_name="<?=$row["ru_name"]?>" <?=$row["ru_show_yn"]=="Y" ? "checked":""?> >
                                                <span class="label"><?=$row["ru_name"]?></span>
                                                <span class="sr-text">선택</span>
                                            </label>
                                            <?php if($row["ru_show_yn"]=="Y") : ?>
                                                <em class="important-label">대표이력서</em>
                                            <?php endif; ?>
                                        </td>
                                        <td class="promo">점프업</td>
                                        <td class="regdate"><?=get_yyyymmdd($row["reg_dt"])?></td>
                                        <td class="action">
                                            <a href="/mypage/resume_view_aa/<?=$row["ru_idx"]?>" class="btn">상세보기</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php if(count($resume_list) > 0) : ?>
                            <p class="help-msg">이력서 등록은 최대 2개까지만 가능합니다.</p>
                            <?php else : ?>
                                <p style="padding:20px;text-align:center;line-height:120px;width:100%;background:#eee;border-radius:10px;">등록된 이력서가 없습니다.</p>
                            <?php endif; ?>
                        </div>

                        <div class="form-action-wrap align-right">
                            <a href="/mypage/resume_write_aa" class="btn-round btn-tertiary">이력서 등록하기</a> <button id="btnDefault" type="button" class="btn-round btn-tertiary">공개 이력서로 설정</button>
                        </div>

                    </form>
                </div>
                <div class="form-action-wrap align-right">

                </div>
            </div>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->


<script>
	$(document).ready(function(){
		//처리
		$("#btnResume").click(function(){
            comAjax("/mypage_proc/resume_start",{},function(ret){
                if(ret.code=="00"){
                    location.href="/mypage/resume_write1";
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
		});

		//이력서 설정
		$("#btnDefault").click(function(){
			var ru_name = $("[name=rd_show_yn]:checked").attr("data-ru_name");
			if($("[name=rd_show_yn]:checked").length ==0){
				commonAlert("공개 이력서로 설정하실 이력서를 선택해주세요.",function(){

				});
				return;
			}

			openPopup('confirm', {
                content: '<em>“'+ru_name+'”</em><br> 이력서를 공개 이력서로 설정하시겠습니까?',
                firstButton: '취소',
                secondButton: '설정',
                secondCallback: function () {
                    var params = {};
					params["ru_idx"] = $("[name=rd_show_yn]:checked").val();
					comAjax("/mypage_proc/resume_default",params,function(ret){
		                if(ret.code=="00"){
		                    location.reload();
		                }else{
		                    commonAlert(ret.msg,function(){});
		                    return;
		                }
		            });
                }
            });
		});
	});
</script>
