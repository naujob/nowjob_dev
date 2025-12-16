<form id="frm" class="controls" method="post">
    <input type="hidden" name="" value="" />
    <input type="hidden" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">아이콘 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                    
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">URL 공유 아이콘</th>
                    <td>
                        <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("st_icon_share","st_icon_share",getData($info,"st_icon_share"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png</p>
                    	<p class="exc1">권장 사이즈 : 300 x 300 px </p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">PC 파비콘</th>
                    <td>
                        <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("st_icon_pc","st_icon_pc",getData($info,"st_icon_pc"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png</p>
                    	<p class="exc1">권장 사이즈 : 300 x 300 px </p>
                    </td>
                    
                </tr>
                <tr>
                    <th scope="col">PC 모바일</th>
                    <td>
                        <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("st_icon_m","st_icon_m",getData($info,"st_icon_m"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png</p>
                    	<p class="exc1">권장 사이즈 : 300 x 300 px </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">사이트 메타태그 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">title</th>
                    <td>
                        <div class="input-text">
                            <?=inputText("st_meta_title","st_meta_title",getData($info,"st_meta_title"),"","")?>
                        </div>
                    </td>
                </tr>
                 <tr>
                    <th scope="col">Description</th>
                    <td>
                        <div class="input-text">
                            <?=inputTextArea("st_meta_desc","st_meta_desc",getData($info,"st_meta_desc"),"","style='height:140px;'")?>
                        </div>
                    </td>
                </tr>
                 <tr>
                    <th scope="col">Keywords</th>
                    <td>
                        <div class="input-text">
                            <?=inputTextArea("st_meta_keywrod","st_meta_keywrod",getData($info,"st_meta_keywrod"),"","style='height:140px;'")?>
                        </div>
                    </td>
                </tr>
                 <tr>
                    <th scope="col">Meta Tag</th>
                    <td>
                        <div class="input-text">
                            <?=inputTextArea("st_meta_tag","st_meta_tag",getData($info,"st_meta_tag"),"","style='height:140px;'")?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>

<script>
	$("#btnSave").click(function(){
		var form = $('#frm'); 
		var params = new FormData(form[0]);
		
		fileAjaxLoading("/admin/setting/basic/icon_proc",params,function(result){
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