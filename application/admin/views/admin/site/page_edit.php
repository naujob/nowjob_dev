<form id="frm" class="controls" method="post">
    <input type="hidden" name="vw_idx" value="<?=getData($info,"vw_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">페이지 등록/수정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
          
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">페이지명<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <?=inputText("vw_name","vw_name",getData($info,"vw_name"),"","",30)?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">페이지 코드<em class="ess"></em></th>
                    <td>
					  	<div class="input-text w200">
					     	<?=inputText("vw_code","vw_code",getData($info,"vw_code"),"","",30)?>
					    </div>
					    <p class="exc1">영어로 설정해주세요.</p>
					</td>
                </tr>
                <tr style="display:none;">
                    <th scope="col">버튼 이미지</th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
						    <?=inputImage("vw_img","vw_img",getData($info,"vw_img"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
						</div>
						<p class="exc1">권장 사이즈 : 140 x 46 px </p>
					</td>
                </tr>
                <tr style="display:none;">
                    <th scope="col">노출여부<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					     	<?=inputRadio("vw_status","vw_status",getOptionList("display_yn"),getData($info,"vw_status","Y"))?>
					    </div>
					</td>
                </tr>
                
                <tr>
                    <th scope="col">상세 내용(PC)<em class="ess"></em></th>
                    <td>
					    <div class="input-text">
						    <?=inputTextArea("vw_pc_content","vw_pc_content",getData($info,"vw_pc_content"),"","")?>
						</div>
					</td>
                </tr>
                
                <tr>
                    <th scope="col">상세 내용(모바일)<em class="ess"></em></th>
                    <td>
					    <div class="input-text">
						    <?=inputTextArea("vw_m_content","vw_m_content",getData($info,"vw_m_content"),"","")?>
						</div>
					</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>


<script>
	$(document).ready(function(){
		$("#btnList").click(function(){
	        history.go(-1);
	    });
	    
	    $("#btnSave").click(function(){
	        if($("#vw_name").val()==""){
	            COMMON.alert("페이지명을 입력해주세요.",function(){$("#vw_name").focus();});
	            return;
	        }
	        
	        if($("#vw_code").val()==""){
	            COMMON.alert("페이지코드를 입력해주세요.",function(){$("#vw_code").focus();});
	            return;
	        }
	        
	        if($("#vw_pc_content").val()==""){
	            COMMON.alert("내용을 입력해주세요.",function(){$("#vw_pc_content").focus();});
	            return;
	        }
	        
	        
	        if($("#vw_m_content").val()==""){
	            COMMON.alert("내용을 입력해주세요.",function(){$("#vw_m_content").focus();});
	            return;
	        }
	        
	        var form = $('#frm'); 
			var params = new FormData(form[0]);
			fileAjaxLoading("/admin/site/page_edit_proc",params,function(result){
				if(result.code == "00"){
					COMMON.alert(result.msg,function(){
						location.href="/admin/site/page_list"; 
					});
				}else{
					COMMON.alert(result.msg);
				}
			});
	    });
	    
	    loadEditor("#vw_pc_content",500);
	    loadEditor("#vw_m_content",500);
	});

</script>
