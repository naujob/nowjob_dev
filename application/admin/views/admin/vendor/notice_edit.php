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
                    <th scope="col">게시판<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
						    <label>
						        <?=inputSelect("search_type","search_type",getOptionList("member_grade"),"")?>
						    </label>
						</div>
					</td>
                    <th scope="col">공지사항 여부<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <label>
                                 <?=inputCheck("ad_id","ad_id",getOptionList("chk_use_yn"),getData($info,"ad_id","Y"))?>
                            </label>
                        </div>
					</td>
                </tr>
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
                    <th scope="col">이미지<em class="ess"></em></th>
                    <td colspan="3">
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("icon_1","icon_1","","<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png</p>
                    	<p class="exc1">권장 사이즈 : 300 x 300 px </p>
					</td>
                    
                </tr>
                <tr>
                    <th scope="col">첨부파일<em class="ess"></em></th>
                    <td colspan="3">
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputFile("icon_1","icon_1","","<tip class='fc3'>첨부 파일을 선택해주세요!</tip>")?>
                        </div>
                        <div class="input-file js-input-file mt-5"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputFile("icon_1","icon_1","","<tip class='fc3'>첨부 파일을 선택해주세요!</tip>")?>
                        </div>
                        <div class="input-file js-input-file mt-5"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputFile("icon_1","icon_1","","<tip class='fc3'>첨부 파일을 선택해주세요!</tip>")?>
                        </div>
                        <div class="input-file js-input-file mt-5"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputFile("icon_1","icon_1","","<tip class='fc3'>첨부 파일을 선택해주세요!</tip>")?>
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