<form id="frm" class="controls" method="post">
    <input type="hidden" name="" value="" />
    <input type="hidden" />
    
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
                    <th scope="col">정렬순서<em class="ess"></em></th>
                    <td>
					    <div class="input-text w100">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
                        <p class="exc1 mt-4">입력시 내림차순 정렬됩니다.미입력시 등록순서 노출됩니다.</p>
					</td>
					 <th scope="col">작성자<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
					</td>
                </tr>
               <tr>
                    <th scope="col">제목<em class="ess"></em></th>
                    <td colspan="3">
					    <div class="input-text w500">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",200)?>
                            </label>
                        </div>
					</td>
                </tr>
                
                <tr>
                    <th scope="col">내용<em class="ess"></em></th>
                    <td colspan="3">
					    <div class="input-text ">
                            <label>
                                <?=inputTextArea("bd_content","bd_content",getData($info,"bd_content"),"","style='height:400px;' class='SEditor'")?>
                            </label>
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
	$(".js-tab").click(function(){
		$('.SEditor').each(function(){
			oEditors.getById[$(this).attr("id")].exec('RESIZE_EDITING_AREA_BY',[true]);
		});
	});
</script>