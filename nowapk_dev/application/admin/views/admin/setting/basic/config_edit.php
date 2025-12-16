<form id="frm" class="controls" method="post">
    <input type="hidden" name="cd_idx" value="<?=getData($info,"cd_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />

    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">희망업무 분류추가</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
               	<tr>
					<th scope="col">분류명<em class="ess"></em></th>
                    <td>
                       	<div class="input-text w300">
                            <label>
                                <?=inputText("cd_key","cd_key",getData($info,"cd_key"),"분류명","",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">아이콘 이미지<em class="ess"></em></th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("cd_val2","cd_val2",getData($info,"cd_val2"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png, 허용 포멧 : jpg,jpeg,png,gif</p>
                    	<p class="exc1">권장 사이즈 : 62 x 24 px </p>
					</td>
                </tr>
                <tr>
                    <th scope="col">업체이 이미지1<em class="ess"></em></th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("cd_val3","cd_val3",getData($info,"cd_val3"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png, 허용 포멧 : jpg,jpeg,png,gif</p>
                    	<p class="exc1">권장 사이즈 : 800 x 800 px </p>
					</td>
                </tr>
                <tr>
                    <th scope="col">업체이 이미지2<em class="ess"></em></th>
                    <td>
					              <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("cd_val4","cd_val4",getData($info,"cd_val4"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png, 허용 포멧 : jpg,jpeg,png,gif</p>
                    	  <p class="exc1">권장 사이즈 : 800 x 800 px </p>
					          </td>
                </tr>
                <tr>
                    <th scope="col">업체이 이미지3<em class="ess"></em></th>
                    <td>
					              <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("cd_val5","cd_val5",getData($info,"cd_val5"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png, 허용 포멧 : jpg,jpeg,png,gif</p>
                    	  <p class="exc1">권장 사이즈 : 800 x 800 px </p>
					          </td>
                </tr>
                <tr>
                    <th scope="col">업체이 이미지4<em class="ess"></em></th>
                    <td>
					              <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("cd_val6","cd_val6",getData($info,"cd_val6"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png, 허용 포멧 : jpg,jpeg,png,gif</p>
                    	  <p class="exc1">권장 사이즈 : 800 x 800 px </p>
					          </td>
                </tr>
                <tr>
	                <th>정렬<em class="ess"></em></td>
	                <td class="inline-wrap">
	                	<div class="input-text w120">
                            <label>
                                <?=inputNumber("cd_sort","cd_sort",getData($info,"cd_sort",0),"","",10)?>
                            </label>
                        </div>
	                </td>
	            </tr>
                <tr>
                    <th scope="col">사용 여부<em class="ess"></em></th>
                    <td>
                        <div class=" w200">
                            <label class="inline-wrap">
                                <?=inputRadio("use_yn","use_yn",["Y"=>"사용","N"=>"미사용"],getData($info,"use_yn","Y"))?>
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
	$(document).ready(function(){
		$("#btnList").click(function(){
	        history.go(-1);
	    });

	    $("#btnSave").click(function(){
	        if($("#cd_key").val()==""){
	            COMMON.alert("분류명을 입력해주세요.",function(){$("#cd_key").focus();});
	            return;
	        }

	        if(($("[name=cd_val2]").val()==""  && $("[name=cd_val2_ori]").val()==undefined) || ($("[name=cd_val2_ori]").val()=="")){
            	COMMON.alert("아이콘 이미지를 선택해주세요.",function(){});
                return;
            }

            if(($("[name=cd_val3]").val()==""  && $("[name=cd_val3_ori]").val()==undefined) || ($("[name=cd_val3_ori]").val()=="")){
            	COMMON.alert("업체이 이미지1를 선택해주세요.",function(){});
                return;
            }

            if(($("[name=cd_val4]").val()==""  && $("[name=cd_val4_ori]").val()==undefined) || ($("[name=cd_val4_ori]").val()=="")){
            	COMMON.alert("업체이 이미지2를 선택해주세요.",function(){});
                return;
            }

            if($("#cd_sort").val()==""){
	            COMMON.alert("정렬순서를 입력해주세요.",function(){$("#cd_key").focus();});
	            return;
	        }

	        var form = $('#frm');
			var params = new FormData(form[0]);
			fileAjaxLoading("/admin/setting/basic/config_edit_proc",params,function(result){
				if(result.code == "00"){
					COMMON.alert(result.msg,function(){
						location.href="/admin/setting/basic/config";
					});
				}else{
					COMMON.alert(result.msg);
				}
			});
	    });
	});
</script>
