<form id="frm" class="controls" method="post">
    <input type="hidden" name="pp_idx" value="<?=getData($info,"pp_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">팝업 등록/수정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
          
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">팝업명<em class="ess"></em></th>
                    <td>
					    <div class="input-text w300">
                            <?=inputText("pp_name","pp_name",getData($info,"pp_name"),"","",200)?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">노출여부<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					      	 <?=inputRadio("pp_status","pp_status",["Y"=>"노출","N"=>"비노출"],getData($info,"pp_status","Y"))?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">노출유형<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					      	 <?=inputRadio("pp_type","pp_type",["ALL"=>"전체","MEM"=>"일반회원","COMP"=>"기업회원"],getData($info,"pp_type","ALL"))?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">노출기간</th>
                     <td class="inline-wrap date-period">
                    	<label>
                            <?=inputText("pp_sdate","pp_sdate",substr(getData($info,"pp_sdate"),0,10),"","class='form-start'",200)?>
                        </label>
                        <span>~</span>
                        <label>
                             <?=inputText("pp_edate","pp_edate",substr(getData($info,"pp_edate"),0,10),"","class='form-end'",200)?>
                        </label>
					</td>
                </tr>
                <tr style="display:none;">
                    <th scope="col">링크설정</th>
                    <td>
					  	<div class="inline-wrap ">
					       <?=inputRadio("pp_link_type","pp_link_type",["N"=>"링크없음","C"=>"동일창","B"=>"새창"],getData($info,"pp_link_type","N"))?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">웹 링크주소</th>
                    <td>
					  	<div class="input-text w500">
							<?=inputText("pp_link","pp_link",getData($info,"pp_link"),"","",200)?>
                        </div>
					    
					</td>
                </tr>
                <tr>
                    <th scope="col">앱 링크주소</th>
                    <td>
					  	<div class="input-text w500">
							<?=inputText("pp_link_m","pp_link_m",getData($info,"pp_link_m"),"","",200)?>
                        </div>
					    
					</td>
                </tr>
                <tr>
                    <th scope="col">위치(TOP,LEFT)</th>
                    <td class="inline-wrap">
					  	<span>TOP:</span>
						<div class="input-text">
							<?=inputText("pp_top","pp_top",getData($info,"pp_top"),"100px,40%","style='width:100px;'",200)?>
						</div>
						<span class="ml-10">LEFT:</span>
						<div class="input-text">
                     		<?=inputText("pp_left","pp_left",getData($info,"pp_left"),"300px,20%","style='width:100px;'",200)?>
                     	</div>
                     	<p class="exc1 d-block">TOP,LEFT는 px(픽셀) 또는 %를 반드시 넣어서 등록해주세요. </p>
					</td>
                </tr>
                <tr>
                    <th scope="col">팝업 이미지(PC)<em class="ess"></em></th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
						    <?=inputImage("pp_pc_img","pp_pc_img",getData($info,"pp_pc_img"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
						</div>
					</td>
                </tr>
                 <tr>
                    <th scope="col">팝업 이미지(모바일)<em class="ess"></em></th>
                    <td>
					    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
						    <?=inputImage("pp_m_img","pp_m_img",getData($info,"pp_m_img"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
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
	    
	    $("[name=bn_period_type]").change(function(){
			if($("[name=bn_period_type]:checked").val()=="D"){
				$(".cont_date").removeClass("hidden");
			}else{
				$(".cont_date").addClass("hidden");
			}
		});
	    
	    $("#btnSave").click(function(){
	        if($("#pp_name").val()==""){
	            COMMON.alert("팝업명을 입력해주세요.",function(){$("#pp_name").focus();});
	            return;
	        }
	        
	        if($("#pp_period_type").val()=="D"){
	        	if($("#pp_sdate").val()==""){
		            COMMON.alert("노출기간을 설쟁해주세요.",function(){$("#pp_sdate").focus();});
		            return;
	           }
	           
	           if($("#pp_edate").val()==""){
		            COMMON.alert("노출기간을 설쟁해주세요.",function(){$("#pp_edate").focus();});
		            return;
	           }
	        }
	        
	        if($("#pp_pc_img").val()=="" && $("#pp_pc_img_ori").val()==""){
	            COMMON.alert("배너이미지(PC)를 설정해주세요.",function(){$("#pp_pc_img").focus();});
	            return;
	        }
	        
	        if($("#pp_m_img").val()=="" && $("#pp_m_img_ori").val()==""){
	            COMMON.alert("배너이미지(모바일)를 설정해주세요.",function(){$("#pp_m_img").focus();});
	            return;
	        }
	        
	        if($("#pp_top").val()==""){
	            //COMMON.alert("노출될 TOP값을 설정해주세요.",function(){$("#pp_top").focus();});
	            //return;
	        }
	        
	        if($("#pp_left").val()==""){
	            //COMMON.alert("노출될 LEFT값을 설정해주세요.",function(){$("#pp_left").focus();});
	            //return;
	        }
	        
	        var form = $('#frm'); 
			var params = new FormData(form[0]);
			fileAjaxLoading("/admin/site/popup_edit_proc",params,function(result){
				if(result.code == "00"){
					COMMON.alert(result.msg,function(){
						location.href="/admin/site/popup_list"; 
					});
				}else{
					COMMON.alert(result.msg);
				}
			});
	    });
	});
</script>




