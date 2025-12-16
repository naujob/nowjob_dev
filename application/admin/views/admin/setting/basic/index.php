<form id="frm" class="controls" method="post">
    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">관리자 정보설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="600px"/>
                    <col width="200px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">관리자 아이디<em class="ess"></em></th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("admin_id","admin_id",getData($info,"admin_id"),"아이디"," readonly='readonly' ",20)?>
                            </label>
                        </div>
                        <p class="exc1">최종관리자는 수정할 수 없습니다.</p>
                    </td>
                    <th scope="col">관리자 비밀번호</th>
                    <td>
                        <label class="d-inline <?=isEmpty($info) ? "hidden":""?>">
					        <?=inputCheck("chg_pwd","chg_pwd",["Y"=>"변경하기"],"")?>
					    </label>
                    	<div class="pwd_cont <?=isEmpty($info) ? "":"hidden"?>" style="width:200px;">
						    <div class="input-text w200">
	                            <label>
	                                <?=inputPassword("st_admin_pwd","st_admin_pwd","","비밀번호","",20)?>
	                            </label>
	                        </div>
	                        <div class="input-text w200 mt-5">
	                            <label>
	                                <?=inputPassword("st_admin_pwd_c","st_admin_pwd_c","","비밀번호 확인","",20)?>
	                            </label>
	                        </div>
                        </div>
                        <p class="exc1 d-block <?=isEmpty($info) ? "hidden":""?>">체크시 변경가능합니다.</p>
                    </td>
                </tr>
                <!--tr>
                    <th scope="col">대표이메일<em class="ess"></em></th>
                    <td>
                        <div class="input-text w50p">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",20)?>
                            </label>
                        </div>
                    </td>
                    <th scope="col">관리자 휴대폰</th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("ad_id","ad_id",getData($info,"ad_id"),"","",20)?>
                            </label>
                        </div>
                    </td>
                </tr-->
                </tbody>
            </table>
        </div>

        <div class="section">
            <h3 class="sub-title mb-10">싸이트 정보설정</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">싸이트명<em class="ess"></em></th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("st_site_name","st_site_name",getData($info,"st_site_name"),"","",50)?>
                            </label>
                        </div>
                    </td>
                    <th scope="col">도메인<em class="ess"></em></th>
                    <td>
                        <div class="input-text w200">
                            <label>
                            	<?=inputText("st_domain","st_domain",getData($info,"st_domain"),"","",200)?>
                            </label>
                        </div>
                        <p class="exc1">현재 도메인을 입력해주세요.</p>
                        <p class="exc1">설정 시 http(s):// 를 제외한 도메인만 입력해 주시기 바랍니다.</p>
                    </td>
                </tr>
                 <tr>
                    <th scope="col">알림톡 수신 연락처<em class="ess"></em></th>
                    <td colspan="3">
                        <div class="input-text w50p">
                            <label>
                                <?=inputText("st_hp","st_hp",getData($info,"st_hp",""))?>
                            </label>
                            <label class="ml-5">
                                <?=inputText("st_hp2","st_hp2",getData($info,"st_hp2",""))?>
                            </label>
                            <label class="ml-5">
                                <?=inputText("st_hp3","st_hp3",getData($info,"st_hp3",""))?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">PC 로고</th>
                    <td>
                        <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("st_logo_pc","st_logo_pc",getData($info,"st_logo_pc"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 사이즈 : 210 x 70 px </p>
                        <p class="exc1">로고 미지정시 싸이트명이 노출됩니다.</p>
                    </td>
                    <th scope="col">모바일 로고</th>
                    <td>
                        <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("st_logo_m","st_logo_m",getData($info,"st_logo_m"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 사이즈 : 210 x 70 px </p>
                        <p class="exc1">로고 미지정시 싸이트명이 노출됩니다.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="col">견적서 도장</th>
                    <td>
                        <div class="input-file js-input-file"><!-- input file 텍스트 삽입 스크립트 : js-input-file -->
                        	<?=inputImage("st_stamp_img","st_stamp_img",getData($info,"st_stamp_img"),"<tip class='fc3'>이미지를 선택해주세요!</tip>")?>
                        </div>
                        <p class="exc1">권장 사이즈 : 60 x 60 px </p>
                    </td>
                </tr>
                <tr style="display:none;">
                    <th scope="col">사업자 정보 노출 여부</th>
                    <td colspan="3">
                        <div class="input-text w50p">
                            <label>
                                <?=inputCheck("st_bus_is_yn","st_bus_is_yn",$chklist_bns,getData($info,"st_bus_is_yn","Y"))?>
                            </label>
                        </div>
                    </td>
                   
                </tr>
                <tr>
                    <th scope="col">상호명<em class="ess"></em></th>
                    <td>
                        <div class="input-text w50p">
                            <label>
                                <?=inputText("st_comp_name","st_comp_name",getData($info,"st_comp_name"),"","",30)?>
                            </label>
                        </div>
                    </td>
                    <th scope="col">대표자명<em class="ess"></em></th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("st_comp_owner_name","st_comp_owner_name",getData($info,"st_comp_owner_name"),"","",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">사업자등록번호</th>
                    <td>
                        <div class="input-text w50p">
                            <label>
                                <?=inputText("st_bus_num","st_bus_num",getData($info,"st_bus_num"),"","",30)?>
                            </label>
                        </div>
                    </td>
                    <th scope="col">통신판매신고번호</th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("st_bus_net_num","st_bus_net_num",getData($info,"st_bus_net_num"),"","",20)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">업태</th>
                    <td>
                        <div class="input-text w50p">
                            <label>
                                <?=inputText("st_bus_type","st_bus_type",getData($info,"st_bus_type"),"","",30)?>
                            </label>
                        </div>
                    </td>
                    <th scope="col">종목</th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("st_bus_item","st_bus_item",getData($info,"st_bus_item"),"","",30)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">대표번호</th>
                    <td>
                        <div class="input-text w50p">
                            <label>
                                <?=inputText("st_main_tel","st_main_tel",getData($info,"st_main_tel"),"","",30)?>
                            </label>
                        </div>
                    </td>
                    <th scope="col">FAX</th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("st_main_fax","st_main_fax",getData($info,"st_main_fax"),"","",30)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">주소</th>
                    <td>
                        <div class="input-text">
                            <label>
                                <?=inputText("st_bus_address","st_bus_address",getData($info,"st_bus_address"),"","",200)?>
                            </label>
                        </div>
                    </td>
                    <th scope="col">물류센터 주소</th>
                    <td>
                        <div class="input-text">
                            <label>
                                <?=inputText("st_stock_address","st_stock_address",getData($info,"st_stock_address"),"","",200)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">고객센터</th>
                    <td>
                    	<table class="table-form">
				            <colgroup>
				            	<col width="150px" />
				                <col width="*" />
				            </colgroup>
				            
				            <tbody>
								<tr>
					                <th>고객센터 전화번호</td>
					                <td>
					                	<div class="input-text">
				                            <label>
				                                <?=inputText("st_service_center_tel1","st_service_center_tel1",getData($info,"st_service_center_tel1"),"","",30)?>
				                            </label>
				                        </div>
					                </td>
					            </tr>
					            <tr>
					                <th>고객센터 이메일주소</th>
					                <td>
					                	<div class="input-text ">
				                            <label>
				                                <?=inputText("st_service_center_email","st_service_center_email",getData($info,"st_service_center_email"),"","",30)?>
				                            </label>
				                        </div>
					                </td>
					            </tr>
							</tbody>
				        </table>
                    </td>
                    <th scope="col">고객센터 운영시간</th>
                    <td>
                        <div class="input-text">
                            <label>
                                <?=inputTextArea("st_service_center_info","st_service_center_info",getData($info,"st_service_center_info"),"","style='width:300px;'",2000)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">개인정보관리책임자</th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("st_bus_suc_name","st_bus_suc_name",getData($info,"st_bus_suc_name"),"","",20)?>
                            </label>
                        </div>
                    </td>
                    <th scope="col">COPY RIGHT</th>
                    <td>
                        <div class="input-text">
                            <label>
                                <?=inputText("st_copyright","st_copyright",getData($info,"st_copyright"),"","style='width:500px;'",100)?>
                            </label>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnSave" class="btn-l btn-type10 w150 ml-10">저장하기</button>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
	$("#btnSave").click(function(){
		if($("#st_admin_id").val()==""){
    		COMMON.alert("관리자 아이디를 입력해주세요.",function(){$("#st_admin_id").focus();});
    		return;
    	}
    	
    	if($("#st_admin_id").val().length  < 5 || $("#st_admin_id").val().length  > 16){
    		COMMON.alert("관리자 아이디는 5-16 사이로 입력해주세요.",function(){$("#st_admin_id").focus();});
    		return;
    	}
    	
    	if($("#au_id").val()==""){
    		COMMON.alert("운영자 메뉴권한 선택해주세요.",function(){$("#au_id").focus();});
    		return;
    	}
        
        if($("#chk_pwd").is(":checked")==true){
        	if($("#st_admin_pwd").val()==""){
        		COMMON.alert("비밀번호를 입력해주세요.",function(){$("#st_admin_pwd").focus();});
        		return;
        	}
        	
        	if($("#st_admin_pwd").val().length <6 ||  $("#st_admin_pwd").val().length > 16){
        		COMMON.alert("비밀번호는 6~16 사이로 입력해주세요.",function(){$("#st_admin_pwd").focus();});
        		return;
        	}
        	
        	if($("#st_admin_pwd_c").val()==""){
        		COMMON.alert("비밀번호확인을 입력해주세요.",function(){$("#st_admin_pwd_c").focus();});
        		return;
        	}
        	
        	if($("#st_admin_pwd").val() != $("#st_admin_pwd_c").val()){
        		COMMON.alert("비밀번호와 비밀번호 확인이 다릅니다.",function(){$("#st_admin_pwd_c").focus();});
        		return;
        	}
        }
        
        if($("#st_site_name").val()==""){
    		COMMON.alert("싸이트명을 입력해주세요.",function(){$("#st_site_name").focus();});
    		return;
    	}
    	
    	 if($("#st_domain").val()==""){
    		COMMON.alert("도메인을 입력해주세요.",function(){$("#st_domain").focus();});
    		return;
    	}
    	
    	 if($("#st_hp").val()==""){
    		COMMON.alert("알림톡 수신 연락처를 입력해주세요.",function(){$("#st_hp").focus();});
    		return;
    	}
    	
    	 if($("#st_comp_name").val()==""){
    		COMMON.alert("회사명을 입력해주세요.",function(){$("#st_comp_name").focus();});
    		return;
    	}
    	
    	 if($("#st_comp_owner_name").val()==""){
    		COMMON.alert("대표자명을 입력해주세요.",function(){$("#st_comp_owner_name").focus();});
    		return;
    	}
        
		var form = $('#frm'); 
		var params = new FormData(form[0]);
		
		fileAjaxLoading("/admin/setting/basic/index_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.reload();	
				});
			}else{
				COMMON.alert(result.msg);
			}
		});
	});

	$("[name=chg_pwd]").click(function(){
		if($(this).is(":checked")){
			$(".pwd_cont").removeClass("hidden");
		}else{
			$(".pwd_cont").addClass("hidden");
		}
	});
});
</script>