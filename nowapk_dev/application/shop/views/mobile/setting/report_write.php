 <div class="page page-write">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">문의하기</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <form id="frm" onsubmit="return false">
			<input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
			<input type="hidden" name="bd_idx" value="<?=getData($info,"bd_idx")?>" />
            <div class="section board-write">
                <div class="container">
					<div class="form-row">
                        <label class="form-selectbox">
                            <span class="form-label">구분</span>
                            <select  name="bd_cate" aria-required="true" required class="js-toggle-form">
                                <option value="">선택하세요</option>
                                <option value="계정" <?=getData($info,"bd_cate")=="계정" ? "selected":""?>>계정</option>
                                <option value="시스템" <?=getData($info,"bd_cate")=="시스템" ? "selected":""?>>시스템</option>
                                <option value="이력서" <?=getData($info,"bd_cate")=="이력서" ? "selected":""?>>이력서</option>
                                <option value="입사지원" <?=getData($info,"bd_cate")=="입사지원" ? "selected":""?>>입사지원</option>
                                <option value="채용정보" <?=getData($info,"bd_cate")=="채용정보" ? "selected":""?>>채용정보</option>
                                <option value="서비스" <?=getData($info,"bd_cate")=="서비스" ? "selected":""?>>서비스</option>
                                <option value="이벤트" <?=getData($info,"bd_cate")=="이벤트" ? "selected":""?>>이벤트</option>
                                <option value="세금계산서발행" <?=getData($info,"bd_cate")=="세금계산서발행" ? "selected":""?>>세금계산서발행</option>
                            </select>
                            <span class="liner"></span>
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span class="form-label">제목</span>
                            <input type="text" name="bd_subject" value="<?=getData($info,"bd_subject")?>" maxlength="40" placeholder="제목을 입력해주세요" autocomplete="off" aria-required="true" required class="form-text">
                        </label>
                    </div>

                    <div class="form-row">
                        <label>
                            <span class="form-label">내용(최대 500자)</span>
                            <textarea name="bd_content" maxlength="500" placeholder="내용을 입력해주세요" aria-required="true" required class="form-textarea js-autoheight"><?=getData($info,"bd_content")?></textarea>
                        </label>
                    </div>

                    <div class="form-row">
                        <p class="form-label">첨부사진</p>
                        <div class="form-pic-group">
                            <label class="form-pic first">
                                <input type="file" name="bd_img1" accept="image/*" class="js-file-preview">
                                <span class="preview">
                                	<?php if(getData($info,"bd_img1")!="") : ?>
                                		<img src="<?=getData($info,"bd_img1")?>" />
                                		<input type='hidden' name='bd_img1_ori' value='<?=getData($info,"bd_img1")?>' />
                                	<?php endif; ?>
                                </span>
                            </label>
                            <label class="form-pic first">
                                <input type="file" name="bd_img2" accept="image/*" class="js-file-preview">
                                <span class="preview">
                                	<?php if(getData($info,"bd_img2")!="") : ?>
                                		<img src="<?=getData($info,"bd_img2")?>" />
                                		<input type='hidden' name='bd_img2_ori' value='<?=getData($info,"bd_img2")?>' />
                                	<?php endif; ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="sticky-bottom">
                        <button type="button" id="btnSave" class="btn btn-primary"><?=getProcMode($info)=="insert" ? "작성완료":"수정완료"?></button>
                    </div>
                </div>
            </div>
        
        </form>
    </div><!-- //page-content -->
</div>


<script>
    $(document).ready(function(){
    	$("#btnSave").click(function(){
    		var proc_mode = $("[name=proc_mode]").val();
    		if($("[name=bd_cate]").val()==""){
    			commonAlert("구분을 선택해주세요.",function(){
                    $("[name=bd_cate]").focus();
                });
                return;
    		}
    		
    		if($("[name=bd_subject]").val()==""){
    			commonAlert("제목을 입력해주세요.",function(){
                    $("[name=bd_subject]").focus();
                });
                return;
    		}
    		
    		if($("[name=bd_content]").val()==""){
    			commonAlert("내용을 입력해주세요.",function(){
                    $("[name=bd_content]").focus();
                });
                return;
    		}
    		
    		if($("[name=bd_content]").val().length < 10){
    			commonAlert("내용은 10자 이상 입력해주세요.",function(){
                    $("[name=bd_content]").focus();
                });
                return;
    		}
    		
           	var form = $('#frm'); 
			var params = new FormData(form[0]);
            fileAjax("/setting_proc/report_write",params,function(ret){
                if(ret.code=="00"){
                    commonAlert("게시글이 "+(proc_mode=="insert" ? "작성":"수정")+"되었습니다.",function(){
                        location.replace("/setting/report");
                    });
                }else{
                	commonAlert(ret.msg,function(){});
                }
            });
    	});
    });


</script>
