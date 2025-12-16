<form id="frm" class="controls" method="post">
    <input type="hidden" name="bg_idx" value="<?=getData($info,"bg_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">배너 그룹 설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
          
                </colgroup>
                <tbody>
                <tr <?=($g_setting["st_admin_type"]=="admin") ? "style='display:none;'":"" ?>>
                    <th scope="col">상품 노출 방법<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					         <?=inputRadio("bg_type","bg_type",["admin"=>"자동(시스넷 정보)","store"=>"수동설정"],getData($info,"bg_type","admin"))?>
					    </div>
					    <p class="exc1">자동(시스넷 정보) : 시스넷 설정 배너만 노출</p>
					    <p class="exc1">수동설정 : 수동등록한 배너만 노출</p>
					</td>
                </tr>
                <tr>
                    <th scope="col">노출여부<em class="ess"></em></th>
                    <td>
					  	<div class="inline-wrap">
					            <?=inputRadio("bg_status","bg_status",["Y"=>"노출","N"=>"비노출"],getData($info,"bg_status","Y"))?>
					    </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">배너 그룹명<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <?=inputText("bg_name","bg_name",getData($info,"bg_name"),"","",40)?>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col">배너 그룹 설명<em class="ess"></em></th>
                    <td>
					    <div class="input-text w400">
                            <?=inputText("bg_desc","bg_desc",getData($info,"bg_desc"),"","",200)?>
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
	        if($("#bg_name").val()==""){
	            COMMON.alert("배너그룸명을 입력해주세요.",function(){$("#bg_name").focus();});
	            return;
	        }
	        
	        var params = $("#frm").serialize();
	        comAjax("/admin/site/banner_group_edit_proc",params,function(result){
	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    location.href="/admin/site/banner_group_list"; 
	                });
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
	    });
	});
</script>

