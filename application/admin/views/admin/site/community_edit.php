<form id="frm" class="controls" method="post">
    <input type="hidden" name="bd_idx" value="<?=getData($info,"bd_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    <input type="hidden" name="tb_id" value="<?=$tb_id?>" />

    <div class="contents-frm">
        <div class="section">
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
	                    <?php if(getProcMode($info)=="insert") : ?>
	                    <a href="javascript:void(0);" class="ml-2 btn-ss btn-type7 btnPopMember" data-idx="" style="width:auto;">회원선택</a>
	                    <?php endif; ?>
					</td>
                </tr>
                <tr style="display:none;">
					
					<th scope="col">공지여부</th>
					<td>
						<div class="input-text w200">
                            <label>
                                 <?=inputCheck("bd_notice_yn","bd_notice_yn",[],getData($info,"bd_notice_yn","N"))?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr style="<?=$setting["tb_category"]=="" ? "display:none;":""?>">
                	<th scope="col">카테고리<em class="ess"></em></th>
                	<td>
					    <div class="input-text">
                            <label>
                                <?=inputSelect("bd_cate","bd_cate",$sub_list,getData($info,"bd_cate",$admin_id))?>
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
					<th scope="col">상단고정 여부</th>
					<td>
						<div class="input-text w200">
                            <label>
                                 <?=inputCheck("bd_notice_yn","bd_notice_yn",["Y"=>"공지"],getData($info,"bd_notice_yn","N"))?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr style="<?=$setting["tb_id"]=="notice" ? "":"display:none;"?>">
                    <th scope="col">정렬순서<em class="ess"></em></th>
                    <td>
					    <div class="input-text w100">
                            <label>
                                <?=inputText("bd_sort","bd_sort",getData($info,"ad_id","0"),"","",200)?>
                            </label>
                        </div>
                        <p class="exc1 mt-4">입력시 내림차순 정렬됩니다.미입력시 등록순서 노출됩니다.</p>
					</td>
                </tr>
                <tr style="<?=$setting["tb_period_yn"]=="Y" ? "":"display:none;"?>">
                    <th scope="col">노출 기간<em class="ess"></em></th>
                    <td  class="inline-wrap date-period">
                    	<label>
                            <?=inputText("bd_sdate","bd_sdate",substr(getData($info,"bd_sdate"),0,10),"","class='form-start'",200)?>
                        </label>
                        <span>~</span>
                        <label>
                             <?=inputText("bd_edate","bd_edate",substr(getData($info,"bd_edate"),0,10),"","class='form-end'",200)?>
                        </label>
					  	
					</td>
                </tr>
                
                <tr style="<?=$setting["tb_img_yn"]=="Y" ? "":"display:none;"?>">
                    <th scope="col">리스트 이미지<em class="ess"></em></th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("bd_list_img1","bd_list_img1",getData($info,"bd_list_img1"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png, 허용 포멧 : jpg,jpeg,png,gif</p>
                    	<p class="exc1">권장 사이즈 : 300 x 300 px </p>
					</td>
                    
                </tr>
                <tr style="<?=$setting["tb_id"]=="community" ? "":"display:none;"?>">
                    <th scope="col">첨부 이미지<em class="ess"></em></th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("bd_img1","bd_img1",getData($info,"bd_img1"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png, 허용 포멧 : jpg,jpeg,png,gif</p>
                    	<p class="exc1">권장 사이즈 : 300 x 300 px </p>
					</td>
                    
                </tr>
                 <tr style="<?=$setting["tb_id"]=="community" ? "":"display:none;"?>">
                    <th scope="col">첨부 이미지2<em class="ess"></em></th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("bd_img2","bd_img2",getData($info,"bd_img2"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 포멧 : png, 허용 포멧 : jpg,jpeg,png,gif</p>
                    	<p class="exc1">권장 사이즈 : 300 x 300 px </p>
					</td>
                    
                </tr>
                <tr style="<?=$setting["tb_file_yn"]=="Y" ? "":"display:none;"?>">
                    <th scope="col">첨부파일</th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputFile("bd_file1","bd_file1",getData($info,"bd_file1"),getData($info,"bd_file1_nm"),"<tip class='fc3'>첨부 파일을 선택해주세요!</tip>")?>
                        </div>
                        <div class="input-file js-input-file mt-5"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputFile("bd_file2","bd_file2",getData($info,"bd_file2"),getData($info,"bd_file1_nm"),"<tip class='fc3'>첨부 파일을 선택해주세요!</tip>")?>
                        </div>
                        <div class="input-file js-input-file mt-5"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputFile("bd_file3","bd_file3",getData($info,"bd_file3"),getData($info,"bd_file1_nm"),"<tip class='fc3'>첨부 파일을 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">허용 포멧 : hwp, xls, doc, xlsx, docx, pdf, jpg, jpeg, gif, png, txt, ppt, pptx</p>
					</td>
                </tr>
                <tr>
                    <th scope="col">내용<em class="ess"></em></th>
                    <td>
					     <?=inputTextArea("bd_content","bd_content",getData($info,"bd_content"),"","style='width:100%;height:250px;padding:10px;border:solid 1px #eee;'")?>
					</td>
                </tr>
                <tr style="<?=$setting["tb_id"]=="inquiry" || $setting["tb_id"]=="alliance" ? "":"display:none;"?>">
                    <th scope="col">답변<em class="ess"></em></th>
                    <td>
                        	<?=inputTextArea("bd_aws","bd_aws",getData($info,"bd_aws"),"","")?>
					</td>
                </tr>
                </tbody>
            </table>
        </div>
		
        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
            <button type="button" id="btnDelete" class="btn-l btn-type4 w150 ml-10">삭제하기</button>
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

    	if($("#mm_id").val()==""){
            COMMON.alert("회원을 선택해주세요.",function(){$("#mm_id").focus();});
            return;
        }
        
        if($("#bd_subject").val()==""){
            COMMON.alert("제목을 입력해주세요.",function(){$("#bd_subject").focus();});
            return;
        }
        
        
        if(tb_period_yn=="Y"){
        	if($("#mm_sdate").val() == ""){
	            COMMON.alert("노출기간을 선택해주세요.",function(){$("#mm_sdate").focus();});
	            return;
	        }
	        
	        if($("#mm_edate").val() == ""){
	             COMMON.alert("노출기간을 선택해주세요.",function(){$("#mm_edate").focus();});
	            return;
	        }
        }
        
        if(tb_img_yn=="Y"){
        	if($("#bd_img1").val() == ""){
	            COMMON.alert("이미지를 선택해주세요.",function(){$("#bd_img1").focus();});
	            return;
	        }
        }
        
        if($("#bd_content").val()==""){
            COMMON.alert("내용을 입력해주세요.",function(){$("#bd_content").focus();});
            return;
        }
        
        var form = $('#frm'); 
		var params = new FormData(form[0]);
	
		fileAjaxLoading("/admin/board/board_edit_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.href="<?=$list_page?>"; 
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
    });
    
    //회원선택
	$(".btnPopMember").click(function(){
		PopupCenter("/admin/popup/member_select?sel_type=one_select","관련상품 선택",1200,800);
	});
	
	
	function setPopMember(sel_type,mem_info){
		//$("#mm_idx").val(mem_info.mm_idx);
		$("#mm_id").val(mem_info.mm_id);
		$("#mm_name").val(mem_info.mm_name);
	}
	
	$("#btnDelete").click(function(){
		var idx = $("[name=bd_idx]").val();
		COMMON.confirm("삭제 하시겠습니까?",function(){
			var params = {};
			params["idx"] = idx;
			params["proc_mode"] = "delete";
	        comAjax("/admin/site/board_edit_proc",params,function(result){
	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    location.href="/admin/site/community_list";
	                });
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
		},function(){
			
		});
	});
</script>