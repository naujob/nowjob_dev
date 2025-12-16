<form id="frm" class="controls" method="post">
    <div class="contents-frm">
        <div class="section category_tabs">
        	<ul>
        		<li id="tab_1">
        			<div class="tr pb-5">
        				<a href="javascript:void(0);" class="btn-ss btn-type3 btnCate1">분류명 추가</a>
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
		        					<th colspan="3">분류명</th>
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
        				<a href="javascript:void(0);" class="btn-ss btn-type3 btnCate2">세부메뉴 추가</a>
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
		        					<th colspan="3">세부메뉴</th>
		        				</tr>
	        				</thead>
	        				<tbody id="menu_body_2">
			    				<tr>
									<td colspan='3' class='tc pb-20 pt-20'>
										분류명을 선택해주세요.
									</td>
								</tr>
							</tbody>
						</table>
					</div>
        		</li>
        		
        	</ul>
        </div>
        
        <div class="section  edit-cont hidden" style="clear:both;">
	        <h3 class="sub-title mb-10 menu_title">메뉴 수정</h3>
	        <table class="table-form">
	            <colgroup>
	                <col width="200px"/>
	                <col width="*"/>
	                <col width="200px"/>
	                <col width="*"/>
	            </colgroup>
	            <tbody>
	            <tr class="cont-type">
	                <th scope="col">분류명</th>
	                <td  colspan="3">
	                    <div class="input-text w200">
	                        <label>
	                            <?=inputText("fm_name","fm_name","","","",30)?>
	                        </label>
	                    </div>
	                </td>
	            </tr>
	            <tr>
	            	<th scope="col">노출여부</th>
	                <td class="inline-wrap" colspan="3">
	                    <?=inputRadio("fm_status","fm_status",getOptionList("use_yn"),"Y")?>
	                </td>
	            </tr>
	            <tr class="cont-menu hidden">
	                <th scope="col">메뉴경로</th>
	                <td class="inline-wrap"  colspan="3">
	                	<div class="">
                            <?=inputSelect("select_menu1","select_menu1",['a'=>'1차메뉴','b'=>'주문하기'],"")?>
                        </div>
                        <div class="">
                            <?=inputSelect("select_menu2","select_menu2",['a'=>'2차메뉴','b'=>'주문하기'],"")?>
                        </div>
                        <div class="">
                            <?=inputSelect("select_menu3","select_menu3",['a'=>'3차메뉴','b'=>'주문하기'],"")?>
                        </div>
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
		
		if(isInputEmpty("#fm_name","분류명을 입력해주세요.")){
			return;
		}
		
		if(isCheckEmpty("[name=fm_status]","노출여부는 를 선택해주세요.")){
			return;
		}
		
		if(current_depth=="2"){
			if(isInputEmpty("#select_menu3","메뉴경로를 선택해주세요.")){
				return;
			}
		}
		
		var params = {};
		params["proc_mode"] = (current_idx > 0 ? "update":"insert");
		params["fm_idx"] = current_idx;
		params["fm_depth"] = current_depth;
		params["fm_parent"] = parent_idx;
		params["fm_name"] = $("#fm_name").val();
		params["am_idx"] = $("#select_menu3").val();
		params["fm_status"] = $("[name=fm_status]:checked").val();
		
		comAjax("/admin/setting/manage/fav_menu_proc",params,function(result){
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
			params["fm_idx"] = current_idx;
			comAjax("/admin/setting/manage/fav_menu_proc",params,function(result){
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
		var fm_idx = $(this).closest("tr").attr("data-fm_idx");
		var fm_parent = $(this).closest("tr").attr("data-fm_parent");
		var fm_name = $(this).closest("tr").attr("data-fm_name");
		var am_idx = $(this).closest("tr").attr("data-am_idx");
		var fm_depth = $(this).closest("tr").attr("data-fm_depth");
		var fm_status = $(this).closest("tr").attr("data-fm_status");
		
		current_idx = fm_idx;
		parent_idx = fm_parent;
		current_depth = fm_depth;
		
		$(".edit-cont").removeClass("hidden");
		$("#fm_name").val(fm_name);
	
		if(fm_status=="Y"){
			$("[name=fm_status]").eq(0).prop("checked",true);
		}else{
			$("[name=fm_status]").eq(1).prop("checked",true);
		}
		
		if(fm_depth=="1"){
			$(".cont-menu").addClass("hidden");
		}else{
			$(".cont-menu").removeClass("hidden");
			
			//메뉴리스트 로드
			var menu1_idx = 0;
			var menu2_idx = 0;
			var menu3_idx = am_idx;
			for(var icnt=0;icnt < arr_menu3.length;icnt++){
				if(arr_menu3[icnt].am_idx == menu3_idx){
					menu2_idx = arr_menu3[icnt].am_parent; 
				}
			}
			
			for(var icnt=0;icnt < arr_menu2.length;icnt++){
				if(arr_menu2[icnt].am_idx == menu2_idx){
					menu1_idx = arr_menu2[icnt].am_parent; 
				}
			}
			
			loadMenu("1","",menu1_idx);
			loadMenu("2",menu1_idx,menu2_idx);
			loadMenu("3",menu2_idx,menu3_idx);
		}	
	});
	
	$(document).on("click",".btnUp",function(e){
		e.stopPropagation();
		var fm_parent = $(this).closest("tr").attr("data-fm_parent");
		var fm_depth = $(this).closest("tr").attr("data-fm_depth");
		var fm_idx = $(this).closest("tr").attr("data-fm_idx");
		var target_idx = $(this).closest("tr").attr("data-up_idx");
		
		if(target_idx==""){
			return;
		}
		
		var params = {};
		params["proc_mode"] = "sort_change";
		params["fm_idx"] = fm_idx;
		params["target_idx"] = target_idx;
		comAjax("/admin/setting/manage/fav_menu_proc",params,function(result){
			if(result.code == "00"){
				getDataList(fm_depth,fm_parent);
				
				//필드초기화
				initForm();
			}else{
				COMMON.alert(result.msg);
			}
		});
	});
	
	$(document).on("click",".btnDown",function(e){
		e.stopPropagation();
		var fm_parent = $(this).closest("tr").attr("data-fm_parent");
		var fm_depth = $(this).closest("tr").attr("data-fm_depth");
		var fm_idx = $(this).closest("tr").attr("data-fm_idx");
		var target_idx = $(this).closest("tr").attr("data-down_idx");
		
		if(target_idx==""){
			return;
		}
		
		var params = {};
		params["proc_mode"] = "sort_change";
		params["fm_idx"] = fm_idx;
		params["target_idx"] = target_idx;
		comAjax("/admin/setting/manage/fav_menu_proc",params,function(result){
			if(result.code == "00"){
				getDataList(fm_depth,fm_parent);
				
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
		$(".menu_title").text("분류명 추가");
		$(".edit-cont").removeClass("hidden");
		$(".child_cont").addClass("hidden");
		$(".cont-menu").addClass("hidden");
		
		$("#fm_name").val("");
		$("#am_url").val("");
		$("[name=fm_status]").eq(0).prop("checked",true);
	});
	
	$(".btnCate2").click(function(){
		if(depth1_idx==0){
			COMMON.alert("분류명을 선택해주세요.");
			return;
		}
		
		current_idx = 0;
		parent_idx = depth1_idx;
		current_depth = 2;
		$(".menu_title").text("세부메뉴 추가");
		$(".edit-cont").removeClass("hidden");
		$(".child_cont").addClass("hidden");
		$(".cont-menu").removeClass("hidden");
		
		$("#fm_name").val("");
		$("[name=fm_status]").eq(0).prop("checked",true);
		
		//1차메뉴 로드
		loadMenu("1","","");
		loadMenu("2","","");
		loadMenu("3","","");
	
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
		
		$("#fm_name").val("");
		$("#am_url").val("");
		$("[name=fm_status]").eq(0).prop("checked",true);
	});
	
	$(document).on("click","#menu_body_1 .clickable",function(e){
		e.stopPropagation();
		var fm_parent = $(this).attr("data-fm_parent");
		var fm_depth = $(this).attr("data-fm_depth");
		var fm_idx = $(this).attr("data-fm_idx");
		
		$("#menu_body_1 tr").removeClass("clicked");
		$(this).addClass("clicked");
		$(".edit-cont").addClass("hidden");
		
		depth1_idx = fm_idx;
		depth2_idx = 0;
		parent_idx = fm_idx;
		getDataList("2",fm_idx);
	});
	
	//1차메뉴 호출
	getDataList("1","0");
	
	function getDataList(depth,parent_id){
		var params = {};
		params["depth"] = depth;
		params["parent_id"] = parent_id;
		
		comAjax("/admin/setting/manage/fav_menu_list",params,function(result){
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
		$("#fm_name").val("");
		$("#am_url").val("");
		$("[name=fm_status]").eq(0).prop("checked",true);
	}
	
	$("#select_menu1").change(function(){
		loadMenu("2",$(this).val(),"");
	});
	
	$("#select_menu2").change(function(){
		loadMenu("3",$(this).val(),"");
	});
	
	function loadMenu(depth,parent_id,select_idx){
		if(depth=="1"){
			
			$("#select_menu1").find("option").remove().end().append("<option value=''>1차메뉴</option>");
			for(var icnt=0;icnt < arr_menu1.length;icnt++){
				$("#select_menu1").append("<option value='"+ arr_menu1[icnt].am_idx +"' " + (select_idx==arr_menu1[icnt].am_idx ? "selected":"") + ">" + arr_menu1[icnt].am_name + "</option>");	
			}
		}else if(depth=="2"){
			$("#select_menu2").find("option").remove().end().append("<option value=''>2차메뉴</option>");
			for(var icnt=0;icnt < arr_menu2.length;icnt++){
				if(arr_menu2[icnt].am_parent == parent_id){
					$("#select_menu2").append("<option value='"+ arr_menu2[icnt].am_idx +"' " + (select_idx==arr_menu2[icnt].am_idx ? "selected":"") + ">" + arr_menu2[icnt].am_name + "</option>");	
				}
			}
		}else if(depth=="3"){
			$("#select_menu3").find("option").remove().end().append("<option value=''>3차메뉴</option>");
			for(var icnt=0;icnt < arr_menu3.length;icnt++){
				if(arr_menu3[icnt].am_parent == parent_id){
					$("#select_menu3").append("<option value='"+ arr_menu3[icnt].am_idx +"' " + (select_idx==arr_menu3[icnt].am_idx ? "selected":"") + ">" + arr_menu3[icnt].am_name + "</option>");	
				}
			}
		}
	}
	
	//메뉴 리스트
	var arr_menu1 = new Array();
	var key1 = 0;
	<?php foreach($menu_list as $key1=> $menu1) : ?>
	arr_menu1[key1++] = {am_idx : "<?=$menu1["am_idx"]?>",am_parent : "<?=$menu1["am_parent"]?>",am_name : "<?=$menu1["am_name"]?>"};
	<?php endforeach; ?>
	
	var arr_menu2 = new Array();
	var key2 = 0;
	<?php foreach($menu_list as $key1=> $menu1) : ?>
	<?php foreach($menu1["child"] as $key2=> $menu2) : ?>
	arr_menu2[key2++] = {am_idx : "<?=$menu2["am_idx"]?>",am_parent : "<?=$menu2["am_parent"]?>",am_name : "<?=$menu2["am_name"]?>"};
	<?php endforeach; ?>
	<?php endforeach; ?>
	
	var arr_menu3 = new Array(); 
	var key3 = 0;
	<?php foreach($menu_list as $key1=> $menu1) : ?>
	<?php foreach($menu1["child"] as $key2=> $menu2) : ?>
	<?php foreach($menu2["child"] as $key3=> $menu3) : ?>
	arr_menu3[key3++] = {am_idx : "<?=$menu3["am_idx"]?>",am_parent : "<?=$menu3["am_parent"]?>",am_name : "<?=$menu3["am_name"]?>"};
	<?php endforeach; ?>
	<?php endforeach; ?>
	<?php endforeach; ?>
	
	//1차메뉴 로드
	loadMenu("1","","");
});
</script>

