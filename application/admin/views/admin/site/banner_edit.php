<form id="frm" class="controls" method="post">
    <input type="hidden" name="bg_idx" value="<?=$bg_idx?>" />
    <input type="hidden" name="bn_idx" value="<?=getData($info,"bn_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">배너 등록/수정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
          
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">배너그룹<em class="ess"></em></th>
                    <td>
					    <div class="input-text bold">
                       		<?=$group_info["bg_name"]?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">정렬<em class="ess"></em></th>
                    <td>
					    <div class="input-text w80">
                            <label>
                                <?=inputNumber("bn_sort","bn_sort",getData($info,"bn_sort",0),"","")?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">배너명<em class="ess"></em></th>
                    <td>
					    <div class="input-text w300">
                            <?=inputText("bn_name","bn_name",getData($info,"bn_name"),"","",200)?>
                        </div>
					</td>
                </tr>
               
                <tr>
                    <th scope="col">노출여부<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					            <?=inputRadio("bn_status","bn_status",["Y"=>"노출","N"=>"비노출"],getData($info,"bn_status","Y"))?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">기간설정<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					      	<?=inputRadio("bn_period_type","bn_period_type",["A"=>"무한노출","D"=>"기간설정"],getData($info,"bn_period_type","A"))?>
					    </div>
					</td>
                </tr>
                <tr class="cont_date <?=getData($info,"pl_period_type","A")=="D" ? "":"hidden"?>">
                    <th scope="col">노출기간</th>
                     <td class="inline-wrap date-period">
                    	<label>
                            <?=inputText("bn_sdate","bn_sdate",substr(getData($info,"bn_sdate"),0,10),"","class='form-start'",200)?>
                        </label>
                        <span>~</span>
                        <label>
                             <?=inputText("bn_edate","bn_edate",substr(getData($info,"bn_edate"),0,10),"","class='form-end'",200)?>
                        </label>
					</td>
                </tr>
                <tr>
                    <th scope="col">링크설정</th>
                    <td>
					  	<div class="inline-wrap">
					            <?=inputRadio("bn_link_type","bn_link_type",["N"=>"링크없음","C"=>"동일창","B"=>"새창"],getData($info,"bn_link_type","N"))?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">링크주소</th>
                    <td>
					  	<div class="input-text w500">
							<?=inputText("bn_link","bn_link",getData($info,"bn_link"),"","",200)?>
                        </div>
					    
					</td>
                </tr>
                <tr>
                    <th scope="col">배경색 컬러코드</th>
                    <td>
					  	<div class="input-text w100">
                            <label>
                                <?=inputText("bn_bg_color","bn_bg_color",getData($info,"bn_bg_color"),"#FFFFFF","",200)?>
                            </label>
                        </div>
                        <p class="exc1">예시 : #0099ff</p>
					</td>
                </tr>
                <?php if($bg_idx=="9") : ?>
                	<tr>
	                    <th scope="col">썸네일 타이틀</th>
	                    <td>
						  	<div class="input-text w500">
								<?=inputText("bn_text1","bn_text1",getData($info,"bn_text1"),"","",200)?>
	                        </div>
						    
						</td>
	                </tr>
	                <tr>
	                    <th scope="col">텍스트1</th>
	                    <td>
						  	<div class="input-text w500">
								<?=inputText("bn_text2","bn_text2",getData($info,"bn_text2"),"","",200)?>
	                        </div>
						    
						</td>
	                </tr>
	                <tr>
	                    <th scope="col">텍스트2</th>
	                    <td>
						  	<div class="input-text w500">
								<?=inputText("bn_text3","bn_text3",getData($info,"bn_text3"),"","",200)?>
	                        </div>
						    
						</td>
	                </tr>
                	<tr>
	                    <th scope="col">썸네일 이미지<em class="ess"></em></th>
	                    <td>
						    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
							    <?=inputImage("bn_m_img","bn_m_img",getData($info,"bn_m_img"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
							</div>
						</td>
	                </tr>
	                <tr>
	                    <th scope="col">배너 이미지<em class="ess"></em></th>
	                    <td>
						    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
							    <?=inputImage("bn_pc_img","bn_pc_img",getData($info,"bn_pc_img"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
							</div>
						</td>
	                </tr>
                <?php else : ?>
                	<tr>
	                    <th scope="col">텍스트1</th>
	                    <td>
						  	<div class="input-text w500">
								<?=inputText("bn_text1","bn_text1",getData($info,"bn_text1"),"","",200)?>
	                        </div>
						    
						</td>
	                </tr>
	                <tr>
	                    <th scope="col">텍스트2</th>
	                    <td>
						  	<div class="input-text w500">
								<?=inputText("bn_text2","bn_text2",getData($info,"bn_text2"),"","",200)?>
	                        </div>
						    
						</td>
	                </tr>
                	<tr>
	                    <th scope="col">배너 이미지<em class="ess"></em></th>
	                    <td>
						    <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
							    <?=inputImage("bn_pc_img","bn_pc_img",getData($info,"bn_pc_img"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
							</div>
						</td>
	                </tr>
                <?php endif; ?>
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
	        if($("#bn_name").val()==""){
	            COMMON.alert("배너명을 입력해주세요.",function(){$("#bn_name").focus();});
	            return;
	        }
	        
	        if($("#bn_period_type").val()=="D"){
	        	if($("#bn_sdate").val()==""){
		            COMMON.alert("노출기간을 설쟁해주세요.",function(){$("#bn_sdate").focus();});
		            return;
	           }
	           
	           if($("#bn_edate").val()==""){
		            COMMON.alert("노출기간을 설쟁해주세요.",function(){$("#bn_edate").focus();});
		            return;
	           }
	        }
	        
	        <?php if($bg_idx=="9") : ?>
	        if($("#bn_m_img").val()=="" && $("#bn_m_img_ori").val()==""){
	            COMMON.alert("썸네일이미지를 설정해주세요.",function(){$("#bn_m_img").focus();});
	            return;
	        }
	        <?php endif; ?>
	        
	        if($("#bn_pc_img").val()=="" && $("#bn_pc_img_ori").val()==""){
	            COMMON.alert("배너이미지를 설정해주세요.",function(){$("#bn_pc_img").focus();});
	            return;
	        }
	        
	        var form = $('#frm'); 
			var params = new FormData(form[0]);
			fileAjaxLoading("/admin/site/banner_edit_proc",params,function(result){
				if(result.code == "00"){
					COMMON.alert(result.msg,function(){
						location.href="/admin/site/banner_list?bg_idx=" + $("[name=bg_idx]").val(); 
					});
				}else{
					COMMON.alert(result.msg);
				}
			});
	    });
	});
</script>




