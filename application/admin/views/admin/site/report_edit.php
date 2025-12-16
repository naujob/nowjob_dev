<form id="frm" class="controls" method="post">
    <input type="hidden" name="bd_idx" value="<?=getData($info,"bd_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    <input type="hidden" name="tb_id" value="<?=$tb_id?>" />

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
					<th scope="col">게시글 확인<em class="ess"></em></th>
	                <td >
					    <a href="javascript:void(0);" class="btn-xs btn-type3 btnConfirm" data-tb_id="<?=$info["tb_id"]?>" data-idx="<?=$info["bd_idx"]?>" style="width:auto;">게시글 보기</a>
					</td>
                </tr>
                <tr>
					<th scope="col">작성자<em class="ess"></em></th>
	                <td >
					    <div class="input-text w200">
	                        <label>
	                            <?=inputText("mm_name","mm_name",getData($info,"mm_name",""),"","readonly",200)?>
	                        </label>
	                    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">제목<em class="ess"></em></th>
                    <td>
					    <div class="input-text w500">
							<?=inputText("bd_subject","bd_subject",getData($info,"bd_subject"),"","",200)?>
                        </div>
					</td>
                </tr>
                
                <tr>
                    <th scope="col">신고내용<em class="ess"></em></th>
                    <td>
					     <?=inputTextArea("br_desc","br_desc",getData($info,"br_desc"),"","style='width:100%;height:200px;background:#f9f9f9;' readonly")?>
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
		$(".btnConfirm").click(function(){
			var idx = $(this).data("idx");
			var tb_id = $(this).data("tb_id");
			
			if(tb_id=="community"){
				location.href = "/admin/site/community_edit?idx=" + idx;	
			}
		});
	});
</script>