<form id="frm" class="controls" method="post">
    <input type="hidden" name="bd_idx" value="<?=getData($info,"bd_idx")?>" />
    <input type="hidden" name="proc_mode" value="aws" />
    <input type="hidden" name="tb_id" value="<?=$tb_id?>" />

    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">게시판 기본 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                  
                </colgroup>
                <tbody>
                <tr style="display:none;">
					<th scope="col">작성자<em class="ess"></em></th>
	                <td class="inline-wrap">
					    <div class="input-text w180">
	                        <label>
	                            <?=inputText("mm_name","mm_name",getData($info,"mm_name",$admin_id),"","readonly",200)?>
	                            <input type="hidden" id="mm_id" name="mm_id" value="<?=getData($info,"mm_id")?>" />
	                        </label>
	                    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">제목<em class="ess"></em></th>
                    <td>
					    <div class="input-text w500">
							<?=inputText("bd_subject","bd_subject",getData($info,"bd_subject"),"","readonly",200)?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">내용<em class="ess"></em></th>
                    <td class="input-text">
					     <?=inputTextArea("bd_content","bd_content",getData($info,"bd_content"),"","style='height:200px;background:#f9f9f9;' readonly")?>
					</td>
                </tr>
                <tr>
                    <th scope="col">첨부 이미지</th>
                    <td class="img-cont">
					    <?php if(getData($info,"bd_img1")!="") : ?>
					    	<a href="<?=getData($info,"bd_img1")?>"><img src="<?=getData($info,"bd_img1")?>" style="height:100px;" /></a>
					    <?php endif; ?>
					    <?php if(getData($info,"bd_img2")!="") : ?>
					    	<a href="<?=getData($info,"bd_img2")?>"><img src="<?=getData($info,"bd_img2")?>" style="height:100px;" /></a>
					    <?php endif; ?>
					</td>
                </tr>
                 <tr>
                    <th scope="col">첨부 이미지2<em class="ess"></em></th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("bd_img2","bd_img2",getData($info,"bd_img2"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
					</td>
                    
                </tr>
                <tr>
					<th scope="col">답변자명<em class="ess"></em></th>
	                <td>
					    <div class="input-text w200">
	                         <?=inputText("bd_aws_name","bd_aws_name",isset($info["bd_aws_name"]) && $info["bd_aws_name"]!=""  ? $info["bd_aws_name"]:"나우잡관리자","","",200)?>
	                    </div>
					</td>
					
                </tr>
                <tr style="<?=$setting["tb_id"]=="report"  ? "":"display:none;"?>">
                    <th scope="col">답변<em class="ess"></em></th>
                    <td class="input-text">
                        	<?=inputTextArea("bd_aws","bd_aws",getData($info,"bd_aws"),"","style='height:200px;'")?>
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
		var proc_type = "<?=getProcMode($info)?>";
		
		if(proc_type=="update"){
			$("#tb_id option").not(":selected").attr("disabled", "disabled");
			$("#tb_id").css("background-color","#eeeeee");
		}
		
		//loadEditor("#bd_content",500);
		//loadEditor("#bd_aws",500);
	});
	 
	var tb_period_yn = "<?=$setting["tb_period_yn"]?>";
	var tb_img_yn = "<?=$setting["tb_img_yn"]?>";
	var tb_file_yn = "<?=$setting["tb_file_yn"]?>";

	//테이블 변경처리
	$("#tb_id").change(function(){
		COMMON.confirm("게시판을 변경하시면 등록하시던 데이터가 초기화 됩니다.",function(){
			location.href = "/admin/board/board_edit?tb_id=" + $("#tb_id").val();
		},function(){
			
		});
	});
	
	//리스트이동
    $("#btnList").click(function(){
        history.go(-1);
    });
    
    //저장
    $("#btnSave").click(function(){
    	if($("#bd_aws_name").val()==""){
            COMMON.alert("답변자명을 입력해주세요.",function(){$("#bd_aws_name").focus();});
            return;
        }
        
    	if($("#bd_aws").val()==""){
            COMMON.alert("답변내용을 입력해주세요.",function(){$("#bd_aws").focus();});
            return;
        }
        
        var form = $('#frm'); 
		var params = new FormData(form[0]);
		fileAjaxLoading("/admin/board/board_add_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.href="<?=$list_page?>"; 
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
    });
    
    $(".img-cont").magnificPopup({
		  delegate: 'a',
		  type: 'image',
		  gallery: {
	      enabled: true
	    },
	});;
</script>