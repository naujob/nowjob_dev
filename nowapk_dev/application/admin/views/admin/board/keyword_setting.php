<form id="frm" class="controls" method="post">
    <input type="hidden" name="" value="" />
    <input type="hidden" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">게시판 등록 금지어 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
    
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">제목 금지어</th>
                    <td>
                    	<input id="board_subject_deny" name="board_subject_deny" type="text" data-role="tagsinput"  data-addclass='big_size'  value="<?=$info["st_board_deny_subject"]?>">
                       	<p class="exc1">설정시 키워드에 있는단어가 포함되어 있으면 등록이 제한됩니다.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">내용 금지어</th>
                    <td>
                        <input id="board_content_deny" name="board_content_deny" type="text" data-role="tagsinput"  data-addclass='big_size'  value="<?=$info["st_board_deny_content"]?>">
                        <p class="exc1">설정시 키워드에 있는단어가 포함되어 있으면 등록이 제한됩니다.</p>
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
	$("#btnSave").click(function(){
		var params = {};
		params["st_board_deny_subject"] = $("#board_subject_deny").val();
		params["st_board_deny_content"] = $("#board_content_deny").val();
		comAjax("/admin/board/keyword_setting_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.reload();	
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
	});
</script>
