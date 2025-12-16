<form id="frm" class="controls" method="post">
    <div class="article">
        <div class="tab policy js-tab">
            <a data-tab_id="#tab_1" href="javascript:void(0);" class="on">이용안내</a>
            <a data-tab_id="#tab_2" href="javascript:void(0);">회원탈퇴 주의사항</a>
        </div>
        <div class="tab-content-wrap">
            <div id="tab_1" class="tab-article on">
                <table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>
	                   
	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">이용안내(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("st_info_use_pc","st_info_use_pc",getData($info,"st_info_use_pc"),"","display:none;",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                <tr>
	                	<th scope="col">이용안내(모바일)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("st_info_use_m","st_info_use_m",getData($info,"st_info_use_m"),"","display:none;",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
            <div id="tab_2" class="tab-article">
                <table class="table-form">
	                <colgroup>
	                    <col width="200px"/>
	                    <col width="*"/>
	                  
	                </colgroup>
	                <tbody>
	                <tr>
	                    <th scope="col">회원탈퇴 정보(PC)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("st_info_fire_pc","st_info_fire_pc",getData($info,"st_info_fire_pc"),"","display:none;'",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                 <tr>
	                	<th scope="col">회원탈퇴 정보(모바일)</th>
	                    <td colspan="3">
	                        <div class="input-text">
	                        	<?=inputTextArea("st_info_fire_m","st_info_fire_m",getData($info,"st_info_fire_m"),"","display:none;",10000)?>
	                        </div>
	                    </td>
	                </tr>
	                </tbody>
	            </table>
            </div>
        </div>
    </div>
    
    <div class="btn-wrap inline-wrap mt-40 tc">
        <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
    </div>
</form>


<script>
$(document).ready(function(){
	$("#btnSave").click(function(){
		var params = {};
		params["st_info_use_pc"] = $("#st_info_use_pc").val();
		params["st_info_use_m"] = $("#st_info_use_m").val();
		params["st_info_fire_pc"] = $("#st_info_fire_pc").val();
		params["st_info_fire_m"] = $("#st_info_fire_m").val();
		
		comAjaxLoading("/admin/setting/basic/info_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.reload();	
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
	});
	
	loadEditor("#st_info_use_pc",500);
	loadEditor("#st_info_use_m",500);
	loadEditor("#st_info_fire_pc",500);
	loadEditor("#st_info_fire_m",500);
});
</script>




