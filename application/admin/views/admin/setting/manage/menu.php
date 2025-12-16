<form id="frm" class="controls" method="post">
    <div class="contents-frm">
        <div class="section category_tabs">
        	<ul>
        		<li id="tab_1">
        			<div class="tr pb-5">
        				<a href="javascript:void(0);" class="btn-ss btn-type3 btnCate1">1차 추가</a>
        			</div>
        			<div class="inner">
	        			<table class="table-tabs">
	        				<colgroup>
	        					<col width="60px" />
	        					<col width="*" />
	        					<col width="120px" />
	        				</colgroup>
	        				<thead>
		        				<tr>
		        					<th colspan="3">1차메뉴</th>
		        				</tr>
	        				</thead>
	        				<tbody id="menu_body_1">
	        					 <tr>
									<td colspan='3' class='tc pb-20 pt-20'>
										데이터가 없습니다.
									</td>
								</tr>
	        				</tbody>
	        			</table>
        			</div>
        		</li>
        		<li id="tab_2">
        			<div class="tr pb-5">
        				<a href="javascript:void(0);" class="btn-ss btn-type3 btnCate2">2차 추가</a>
        			</div>
        			<div class="inner">
	        			<table class="table-tabs">
	        				<colgroup>
	        					<col width="60px" />
	        					<col width="*" />
	        					<col width="120px" />
	        				</colgroup>
	        				<thead>
		        				<tr>
		        					<th colspan="3">2차메뉴</th>
		        				</tr>
	        				</thead>
	        				<tbody id="menu_body_2">
			    				<tr>
									<td colspan='3' class='tc pb-20 pt-20'>
										1차메뉴를 선택해주세요.
									</td>
								</tr>
							</tbody>
						</table>
					</div>
        		</li>
        		<li id="tab_3">
        			<div class="tr pb-5">
        				<a href="javascript:void(0);" class="btn-ss btn-type3 btnCate3">3차 추가</a>
        			</div>
        			<div class="inner">
	        			<table class="table-tabs">
	        				<colgroup>
	        					<col width="60px" />
	        					<col width="*" />
	        					<col width="120px" />
	        				</colgroup>
	        				<thead>
		        				<tr>
		        					<th colspan="3">3차메뉴</th>
		        				</tr>
	        				</thead>
	        				<tbody id="menu_body_3">
			    				<tr>
									<td colspan='3' class='tc pb-20 pt-20'>
										2차메뉴를 선택해주세요.
									</td>
								</tr>
							</tbody>
						</table>
					</div>
        		</li>
        	</ul>
        </div>
        
        <div class="section hidden edit-cont" style="clear:both;">
	        <h3 class="sub-title mb-10 menu_title">메뉴 추가,수정</h3>
	        <table class="table-form">
	            <colgroup>
	                <col width="200px"/>
	                <col width="*"/>
	                <col width="200px"/>
	                <col width="*"/>
	            </colgroup>
	            <tbody>
	            <tr>
	                <th scope="col">메뉴명</th>
	                <td >
	                    <div class="input-text w200">
	                        <label>
	                            <?=inputText("am_name","am_name","","메뉴명","",20)?>
	                        </label>
	                    </div>
	                </td>
	                <th scope="col">노출여부</th>
	                <td class="inline-wrap">
	                    <?=inputRadio("am_status","am_status",["Y"=>"노출","N"=>"비노출"],"Y")?>
	                </td>
	            </tr>
	            <tr>
	                <th scope="col">메뉴경로</th>
	                <td colspan="3">
	                    <div class="input-text w400">
	                        <label>
	                            <?=inputText("am_url","am_url","","","",50)?>
	                        </label>
	                    </div>
	                </td>
	            </tr>
	            <tr class="child_cont hidden">
	                <th scope="col">관련매뉴</th>
	                <td colspan="3">
	                	<a href="javascript:void(0);" class="btn-type3 btn-ss btnAddChild mb-10">관련메뉴 추가</a>
	                    <table class="table-form">
				            <colgroup>
				            	<col width="300px" />
				                <col width="*" />
				                <col width="100px" />
				            </colgroup>
				            <thead>
				            <tr>
				                <th scope="col" class="tc pl-0">메뉴명</th>
				                <th scope="col" class="tc pl-0">메뉴경로</th>
				                <th scope="col" class="tc pl-0">관리</th>
							</tr>
				            </thead>
				            <tbody id="child_body">
								<tr>
					                <td class="tc">
					                	<div class="input-text w200">
					                        <label>
					                            <?=inputText("","child_name[]","","메뉴명","",30)?>
					                        </label>
					                    </div>
					                </td>
					                <td>
					                	<div class="input-text">
					                        <label>
					                            <?=inputText("","child_url[]","","메뉴경로","",500)?>
					                        </label>
					                    </div>
					                </td>
					                <td class='tc'>
					                	<a href='javascript:void(0);' class='btn-type4 btn-ss btnChildDelete' >삭제</a>
					                </td>
					            </tr>
							</tbody>
				        </table>
	                </td>
	            </tr>
	            </tbody>
	        </table>
	        <div class="btn-wrap inline-wrap mt-40 tc">
		        <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
		        <button type="button" id="btnDelete" class="btn-l btn-type4 w150 ml-10">삭제</button>
		        <button type="button" id="btnClose" class="btn-l btn-type2 w150 ml-10">닫기</button>
		    </div>
	    </div>
    </div>
</form>

<script>
$(document).ready(function(){
	var current_idx = 0;
	var current_depth = 1;
	var parent_idx = 0;
	
	var depth1_idx = 0;
	var depth2_idx = 0;
	
	$("#btnSave").click(function(){
		//검증
		if(isInputEmpty("#am_name","메뉴명을 입력해주세요.")){
			return;
		}
		
		if(isCheckEmpty("[name=am_status]","노출여부는 를 선택해주세요.")){
			return;
		}
		
		if(isInputEmpty("#am_url","메뉴경로를 입력해주세요.")){
			return;
		}
		
		var params = {};
		params["proc_mode"] = (current_idx > 0 ? "update":"insert");
		params["am_idx"] = current_idx;
		params["am_depth"] = current_depth;
		params["am_parent"] = parent_idx;
		params["am_name"] = $("#am_name").val();
		params["am_url"] = $("#am_url").val();
		params["am_status"] = $("[name=am_status]:checked").val();
		
		//3댑스인경우 서브메뉴 추가로 등록한다.
		if(current_depth=="3"){
			var child_list = "";
			for(var icnt=0;icnt<$("[name='child_name[]']").length;icnt++){
				if($("[name='child_name[]']").eq(icnt).val()!=""){
					if(child_list==""){
						child_list = $("[name='child_name[]']").eq(icnt).val() + ":" + $("[name='child_url[]']").eq(icnt).val();
					}else{
						child_list += "#" + $("[name='child_name[]']").eq(icnt).val() + ":" + $("[name='child_url[]']").eq(icnt).val();	
					}
				}
			}
			
			params["am_child_list"] = child_list;
		}else{
			params["am_child_list"] = "";
		}
		
		comAjax("/admin/setting/manage/menu_proc",params,function(result){
			if(result.code == "00"){
				getDataList(current_depth,parent_idx);
				
				//필드초기화
				initForm();
			}else{
				COMMON.alert(result.msg);
			}
		});
	});
	
	$("#btnClose").click(function(){
		initForm();
	});
	
	$("#btnDelete").click(function(){
		if(current_idx==0){
			COMMON.alert("삭제하실 메뉴명을 선택해주세요.");
			return;	
		}
		
		COMMON.confirm("삭제하시겠습니까?",function(){
			var params = {};
			params["proc_mode"] = "delete";
			params["am_idx"] = current_idx;
			comAjax("/admin/setting/manage/menu_proc",params,function(result){
				if(result.code == "00"){
					getDataList(current_depth,parent_idx);
					
					//필드초기화
					initForm();
				}else{
					COMMON.alert(result.msg);
				}
			});
		},function(){
			
		});
	});
	
	$(document).on("click",".btnEdit",function(e){
		e.stopPropagation();
		var am_idx = $(this).closest("tr").attr("data-am_idx");
		var am_parent = $(this).closest("tr").attr("data-am_parent");
		var am_name = $(this).closest("tr").attr("data-am_name");
		var am_url = $(this).closest("tr").attr("data-am_url");
		var am_depth = $(this).closest("tr").attr("data-am_depth");
		var am_status = $(this).closest("tr").attr("data-am_status");
		var am_child_list = $(this).closest("tr").attr("data-am_child_list");
		
		current_idx = am_idx;
		parent_idx = am_parent;
		current_depth = am_depth;
		
		$(".edit-cont").removeClass("hidden");
		$("#am_name").val(am_name);
		$("#am_url").val(am_url);
		
		if(am_status=="Y"){
			$("[name=am_status]").eq(0).prop("checked",true);
		}else{
			$("[name=am_status]").eq(1).prop("checked",true);
		}
		
		if(am_depth=="3"){
			$(".child_cont").removeClass("hidden");
			//메뉴설정
			if(am_child_list!=""){
				var is_append = false;
				var arr_list = am_child_list.split("#");
				for(var icnt=0;icnt < arr_list.length;icnt++){
					var arr_item = arr_list[icnt].split(":");
					if(arr_item.length > 1){
						var html = "";
						html +="<tr>";
						html +="    <td class='tc'>";
						html +="        <div class='input-text w200'>";
						html +="            <label>";
						html +="                <input type='text' id='' name='child_name[]' placeholder='메뉴명' maxlength='30' value='"+arr_item[0]+"'  />";
						html +="            </label>";
						html +="        </div>";
						html +="    </td>";
						html +="    <td>";
						html +="        <div class='input-text'>";
						html +="            <label>";
						html +="                <input type='text' id='' name='child_url[]' placeholder='메뉴경로' maxlength='500' value='"+arr_item[1]+"'  />";
						html +="            </label>";
						html +="        </div>";
						html +="    </td>";
						html +="    <td class='tc'>";
					    html +="        <a href='javascript:void(0);' class='btn-type4 btn-ss btnChildDelete' >삭제</a>";
					    html +="    </td>";
						html +="</tr>";
						
						if(is_append==false){
							$("#child_body").html("");
							is_append = true;
						}
						
						$("#child_body").append(html);
					}
				}
			}else{
				initChild();
			}
		}else{
			$(".child_cont").addClass("hidden");
		}
	});
	
	$(document).on("click",".btnUp",function(e){
		e.stopPropagation();
		var am_parent = $(this).closest("tr").attr("data-am_parent");
		var am_depth = $(this).closest("tr").attr("data-am_depth");
		var am_idx = $(this).closest("tr").attr("data-am_idx");
		var target_idx = $(this).closest("tr").attr("data-up_idx");
		
		if(target_idx==""){
			return;
		}
		
		var params = {};
		params["proc_mode"] = "sort_change";
		params["am_idx"] = am_idx;
		params["target_idx"] = target_idx;
		comAjax("/admin/setting/manage/menu_proc",params,function(result){
			if(result.code == "00"){
				getDataList(am_depth,am_parent);
				
				//필드초기화
				initForm();
			}else{
				COMMON.alert(result.msg);
			}
		});
	});
	
	$(document).on("click",".btnDown",function(e){
		e.stopPropagation();
		var am_parent = $(this).closest("tr").attr("data-am_parent");
		var am_depth = $(this).closest("tr").attr("data-am_depth");
		var am_idx = $(this).closest("tr").attr("data-am_idx");
		var target_idx = $(this).closest("tr").attr("data-down_idx");
		
		if(target_idx==""){
			return;
		}
		
		var params = {};
		params["proc_mode"] = "sort_change";
		params["am_idx"] = am_idx;
		params["target_idx"] = target_idx;
		comAjax("/admin/setting/manage/menu_proc",params,function(result){
			if(result.code == "00"){
				getDataList(am_depth,am_parent);
				
				//필드초기화
				initForm();
			}else{
				COMMON.alert(result.msg);
			}
		});
	});
	
	$(".btnCate1").click(function(){
		current_idx = 0;
		current_depth = 1;
		parent_idx = 0;
		$(".menu_title").text("1차 메뉴 추가");
		$(".edit-cont").removeClass("hidden");
		$(".child_cont").addClass("hidden");
		
		$("#am_name").val("");
		$("#am_url").val("");
		$("[name=am_status]").eq(0).prop("checked",true);
		initChild();
	});
	
	$(".btnCate2").click(function(){
		if(depth1_idx==0){
			COMMON.alert("1차카테고리를 선택해주세요.");
			return;
		}
		
		current_idx = 0;
		parent_idx = depth1_idx;
		current_depth = 2;
		$(".menu_title").text("2차 메뉴 추가");
		$(".edit-cont").removeClass("hidden");
		$(".child_cont").addClass("hidden");
		
		$("#am_name").val("");
		$("#am_url").val("");
		$("[name=am_status]").eq(0).prop("checked",true);
		initChild();
	});
	
	$(".btnCate3").click(function(){
		
		if(depth2_idx==0){
			COMMON.alert("2차카테고리를 선택해주세요.");
			return;
		}
		
		current_idx = 0;
		parent_idx = depth2_idx;
		current_depth = 3;
		$(".menu_title").text("3차 메뉴 추가");
		$(".edit-cont").removeClass("hidden");
		$(".child_cont").removeClass("hidden");
		
		$("#am_name").val("");
		$("#am_url").val("");
		$("[name=am_status]").eq(0).prop("checked",true);
		initChild();
	});
	
	$(document).on("click","#menu_body_1 .clickable",function(e){
		e.stopPropagation();
		var am_parent = $(this).attr("data-am_parent");
		var am_depth = $(this).attr("data-am_depth");
		var am_idx = $(this).attr("data-am_idx");
		
		$("#menu_body_1 tr").removeClass("clicked");
		$(this).addClass("clicked");
		$(".edit-cont").addClass("hidden");
		
		depth1_idx = am_idx;
		depth2_idx = 0;
		parent_idx = am_idx;
		getDataList("2",am_idx);
	});
	
	$(document).on("click","#menu_body_2 .clickable",function(e){
		e.stopPropagation();
		var am_parent = $(this).attr("data-am_parent");
		var am_depth = $(this).attr("data-am_depth");
		var am_idx = $(this).attr("data-am_idx");
		
		$("#menu_body_2 tr").removeClass("clicked");
		$(this).addClass("clicked");
		$(".edit-cont").addClass("hidden");
		
		depth2_idx = am_idx;
		parent_idx = am_idx;
		getDataList("3",am_idx);
	});
	
	$(document).on("click",".btnAddChild",function(){
		var html = "";
		html +="<tr>";
		html +="    <td class='tc'>";
		html +="        <div class='input-text w200'>";
		html +="            <label>";
		html +="                <input type='text' id='' name='child_name[]' placeholder='메뉴명' maxlength='30' value=''  />";
		html +="            </label>";
		html +="        </div>";
		html +="    </td>";
		html +="    <td>";
		html +="        <div class='input-text'>";
		html +="            <label>";
		html +="                <input type='text' id='' name='child_url[]' placeholder='메뉴경로' maxlength='500' value=''  />";
		html +="            </label>";
		html +="        </div>";
		html +="    </td>";
		html +="    <td class='tc'>";
	    html +="        <a href='javascript:void(0);' class='btn-type4 btn-ss btnChildDelete' >삭제</a>";
	    html +="    </td>";
		html +="</tr>";
		
		$("#child_body").append(html);
	});
	
	$(document).on("click",".btnChildDelete",function(){
		$(this).closest("tr").remove()
	})
	
	//1차메뉴 호출
	getDataList("1","0");
	
	function getDataList(depth,parent_id){
		var params = {};
		params["depth"] = depth;
		params["parent_id"] = parent_id;
		
		comAjax("/admin/setting/manage/menu_depth_list",params,function(result){
			if(result.code == "00"){
				$("#menu_body_" + depth).html(result.data)
			}else{
				COMMON.alert(result.msg);
			}
		});
	}
	
	//초기화
	function initForm(){
		current_idx = 0;
		current_depth = 1;
		$(".edit-cont").addClass("hidden");
		$("#am_name").val("");
		$("#am_url").val("");
		$("[name=am_status]").eq(0).prop("checked",true);
	}
	
	function initChild(){
		var html = "";
		html +="<tr>";
		html +="    <td class='tc'>";
		html +="        <div class='input-text w200'>";
		html +="            <label>";
		html +="                <input type='text' id='' name='child_name[]' placeholder='메뉴명' maxlength='30' value=''  />";
		html +="            </label>";
		html +="        </div>";
		html +="    </td>";
		html +="    <td>";
		html +="        <div class='input-text'>";
		html +="            <label>";
		html +="                <input type='text' id='' name='child_url[]' placeholder='메뉴경로' maxlength='500' value=''  />";
		html +="            </label>";
		html +="        </div>";
		html +="    </td>";
		html +="    <td class='tc'>";
	    html +="        <a href='javascript:void(0);' class='btn-type4 btn-ss btnChildDelete' >삭제</a>";
	    html +="    </td>";
		html +="</tr>";
		
		$("#child_body").html("");
		$("#child_body").append(html);
	}
});
</script>