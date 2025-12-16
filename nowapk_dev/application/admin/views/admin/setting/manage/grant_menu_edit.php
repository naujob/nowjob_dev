<form id="frm" class="controls" method="post">
    <input type="hidden" name="mg_idx" value="<?=getData($info,"mg_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
    <div class="contents-frm">
        <div class="section">
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                   
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">권한명<em class="ess"></em></th>
                    <td colspan="3">
                        <div class="input-text w200">
                            <label>
                                <?=inputText("mg_name","mg_name",getData($info,"mg_name"),"권한명","",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="section">
			<div class="search-result">
		        <table class="table-type2">
		            <colgroup>
		                <col width="50" />		                
		                <col width="200" />
		                <col width="200" />
		                <col width="200" />
		                <col width="100" />
		            </colgroup>
		            <thead>
		            <tr>
		                <th scope="col" class="tc">
		                	사용여부 선택<?=inputCheck("chk_all","chk_all",["Y"=>""],"")?>
		                </th>
		                <th scope="col">1차메뉴명</th>
		                <th scope="col">2차메뉴명</th>
		                <th scope="col">3차메뉴명</th>
		                 <th scope="col">사용여부</th>
					</tr>
		            </thead>
		            <tbody>
		            	<?php foreach($list as $key=> $row) : ?>
		            	<tr>
			                <td class="tc"><?=inputCheck("chk"  ,"chk",[$row["am_idx"]=>""],($row["use_yn"]=="Y" ? $row["am_idx"]:""))?></td>
			                <td class="tl">
			                	<?=($row["am_name"])?>
			                </td>
			                <td class="tl">
			                	
			                </td>
			                <td class="tl">
			                	
			                </td>
			                <td>
			                	<?php if($row["use_yn"]=="Y") : ?>
			                		<span class="status-able">사용중</span>
			                	<?php else :  ?>
			                		<span class="status-disable">미사용</span>
			                	<?php endif; ?>
			                </td>
			            </tr>
			            <?php if(isset($row["child"])) : ?>
		            	<?php foreach($row["child"] as $key=> $row2) : ?>
		            	<tr>
			                <td class="tc"><?=inputCheck("chk"  ,"chk",[$row2["am_idx"]=>""],($row2["use_yn"]=="Y" ? $row2["am_idx"]:""))?></td>
			                <td class="tl">
			                	<?=($row["am_name"])?>
			                </td>
			                <td class="tl">
			                	<?=( $row2["am_name"])?>
			                </td>
			                <td class="tl">
			                	
			                </td>
			                <td>
			                	<?php if($row2["use_yn"]=="Y") : ?>
			                		<span class="status-able">사용중</span>
			                	<?php else :  ?>
			                		<span class="status-disable">미사용</span>
			                	<?php endif; ?>
			                </td>
			            </tr>
			            <?php if(isset($row2["child"])) : ?>
		            	<?php foreach($row2["child"] as $key=> $row3) : ?>
						<tr>
			                <td class="tc"><?=inputCheck("chk"  ,"chk",[$row3["am_idx"]=>""],($row3["use_yn"]=="Y" ? $row3["am_idx"]:""))?></td>
			                <td class="tl">
			                	<?=($row["am_name"])?>
			                </td>
			                <td class="tl">
			                	<?=( $row2["am_name"])?>
			                </td>
			                <td class="tl">
			                	<?=($row3["am_name"])?>
			                </td>
			                <td>
			                	<?php if($row3["use_yn"]=="Y") : ?>
			                		<span class="status-able">사용중</span>
			                	<?php else :  ?>
			                		<span class="status-disable">미사용</span>
			                	<?php endif; ?>
			                </td>
			            </tr>
			            
			            <?php endforeach;?>
			            <?php endif; ?>
			            <?php endforeach;?>
			            <?php endif; ?>
			            <?php endforeach;?>
					</tbody>
		        </table>
		    </div>
		</div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <!--button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button-->
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>


<script>
$(document).ready(function(){
	$("#btnSave").click(function(){
		if($("#mg_name").val()==""){
    		COMMON.alert("권한명을 입력해주세요.",function(){$("#mg_name").focus();});
    		return;
    	}
    	
    	var params = {};
    	
    	var menu_ids = "";
    	
    	for(var icnt=0;icnt < $("[name=chk]:checked").length;icnt++){
    		if(menu_ids==""){
    			menu_ids = $("[name=chk]:checked").eq(icnt).val();
    		}else{
    			menu_ids = menu_ids + "," + $("[name=chk]:checked").eq(icnt).val();
    		}
    	}
    	
    	params["proc_mode"] = $("[name=proc_mode]").val();
    	params["mg_name"] = $("#mg_name").val();
    	params["mg_idx"] = $("[name=mg_idx]").val();
    	params["menu_ids"] = menu_ids; 
		comAjaxLoading("/admin/setting/manage/grant_menu_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.href="/admin/setting/manage/grant_menu";	
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
	});

});
</script>