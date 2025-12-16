<form id="frm" class="controls" method="post">
    <input type="hidden" name="tb_idx" value="<?=getData($info,"tb_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
  
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">게시판 기본 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">게시판 아이디<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <label>
                                <?=inputText("tb_id","tb_id",getData($info,"tb_id"),"","",30)?>
                            </label>
                        </div>
					</td>
                    <th scope="col">게시판명<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <label>
                                <?=inputText("tb_name","tb_name",getData($info,"tb_name"),"","",50)?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">노출여부<em class="ess"></em></th>
                    <td class="inline-wrap">
					   <div class="inline-wrap">
                           <?=inputRadio("tb_show_yn","tb_show_yn",getOptionList("display_yn"),getData($info,"tb_show_yn","Y"))?>
                        </div>
					</td>
                    <th scope="col">노출위치<em class="ess"></em></th>
                    <td>
					     <div class="input-text w200">
                            <label>
                                <?=inputSelect("tb_location","tb_location",getOptionList("board_show_location"),getData($info,"tb_location"))?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">기간 사용 여부<em class="ess"></em></th>
                    <td>
					     <div class="inline-wrap">
							<?=inputRadio("tb_period_yn","tb_period_yn",getOptionList("use_yn"),getData($info,"tb_period_yn","Y"))?>
                        </div>
					</td>
					 <th scope="col">기본게시판여부<em class="ess"></em></th>
                    <td>
					     <span class="status-<?=getData($info,"tb_basic_yn","N")=="Y" ? "danger":"primary"?>"><?=getData($info,"tb_basic_yn","N")=="Y" ? "기본":"일반"?></span> 
					     <p class="exc1">기본 게시판은 수정이 불가능합니다.</p>
					</td>
                </tr>
                <tr>
                    <th scope="col">이미지 등록 여부<em class="ess"></em></th>
                    <td>
                    	<div class="inline-wrap">
                    		<?=inputRadio("tb_img_yn","tb_img_yn",getOptionList("use_yn"),getData($info,"tb_img_yn","Y"))?>
                    	</div>
					</td>
					<th>첨부파일 사용여부<em class="ess"></em></th>
                    <td>
                    	<div class="inline-wrap">
                    		<?=inputRadio("tb_file_yn","tb_file_yn",getOptionList("use_yn"),getData($info,"tb_file_yn","Y"))?>
                    	</div>
					</td>
                </tr>
                 <tr>
                 	<th scope="col">카테고리</th>
                    <td>
					     <div class="input-text w400">
                            <label>
                                <?=inputText("tb_category","tb_category",getData($info,"tb_category"),"","",500)?>
                            </label>
                        </div>
                        <p class="exc1">;로 분리해서 등록해주세요.</p>
					</td>
                    <th scope="col">게시물 노출수<em class="ess"></em></th>
                    <td>
					     <div class="input-text w100">
                            <label>
                                <?=inputNumber("tb_page_cnt","tb_page_cnt",getData($info,"tb_page_cnt"),"","",10)?>
                            </label>
                        </div>
                        <p class="exc1">게시물노출수는 1-500사이로 입력해주세요.</p>
					</td>
                </tr>
                </tbody>
            </table>
        </div>
		<div class="section">
            <h3 class="sub-title mb-10">권한 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                
                <tr>
                    <th scope="col">댓글 사용여부<em class="ess"></em></th>
                    <td>
                    	<div class="inline-wrap">
                    		<?=inputRadio("tb_grant_add","tb_grant_add",getOptionList("use_yn"),getData($info,"tb_grant_add","N"))?>
                    	</div>
					</td>
					<th scope="col">답글 사용여부<em class="ess"></em></th>
                    <td>
                    	<div class="inline-wrap">
                    		<?=inputRadio("tb_grant_cmt","tb_grant_cmt",getOptionList("use_yn"),getData($info,"tb_grant_cmt","N"))?>
                    	</div>
					</td>
                </tr>
             	<tr>
                    <th scope="col">리스트 보기 권한<em class="ess"></em></th>
                    <td>
                    	<div class="inline-wrap">
                    		<?=inputRadio("tb_grant_list","tb_grant_list",getOptionList("board_grant"),getData($info,"tb_grant_list","N"))?>
                    	</div>
					</td>
					<th scope="col">상세 글 보기 권한<em class="ess"></em></th>
                    <td>
                    	<div class="inline-wrap">
                    		<?=inputRadio("tb_grant_detail","tb_grant_detail",getOptionList("board_grant"),getData($info,"tb_grant_detail","N"))?>
                    	</div>
					</td>
                </tr>
                <tr>
                    <th scope="col">등록/수정/삭제 권한<em class="ess"></em></th>
                    <td colspan="3">
                    	<div class="inline-wrap">
                    		<?=inputRadio("tb_grant_edit","tb_grant_edit",getOptionList("board_grant"),getData($info,"tb_grant_edit","A"))?>
                    	</div>
					</td>
                </tr>
                
                </tbody>
            </table>
        </div>
        
        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150 btnList">목록으로</button>
            <?php if(getData($info,"tb_basic_yn","N")=="N") : ?>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
            <?php endif; ?>
        </div>
    </div>
</form>
<script>
	$(document).ready(function(){
		$("#btnList").click(function(){
	        history.go(-1);
	    });
	    
	    $("#btnSave").click(function(){
	        if($("#tb_id").val()==""){
	            COMMON.alert("게시판 아이디를 입력해주세요.",function(){$("#tb_id").focus();});
	            return;
	        }
	        
	        if($("#tb_name").val()==""){
	            COMMON.alert("게시판명을 입력해주세요.",function(){$("#tb_name").focus();});
	            return;
	        }
	        
	        if($("#tb_page_cnt").val()==""){
	            COMMON.alert("게시물 노출수를 1-500 사이로 입력해주세요.",function(){$("#tb_page_cnt").focus();});
	            return;
	        }
	        
	        if($("#tb_page_cnt").val() < 1 || $("#tb_page_cnt").val() > 500){
	            COMMON.alert("게시물 노출수를 1-500 사이로 입력해주세요.",function(){$("#tb_page_cnt").focus();});
	            return;
	        }
	        
	        var params = $("#frm").serialize();
	        comAjax("/admin/board/table_edit_proc",params,function(result){
	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    location.href="/admin/board/table_list"; 
	                });
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
	    });
    
		$(".js-tab").click(function(){
			$('.SEditor').each(function(){
				oEditors.getById[$(this).attr("id")].exec('RESIZE_EDITING_AREA_BY',[true]);
			});
		});
	});
</script>