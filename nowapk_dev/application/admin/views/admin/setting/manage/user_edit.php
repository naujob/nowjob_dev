<form id="frm" class="controls" method="post">
    <input type="hidden" id="au_idx"    name="au_idx"    value="<?=getData($info,"au_idx")?>" />
    <input type="hidden" id="proc_mode" name="proc_mode" value="<?=getProcMode($info)?>" />
    <div class="contents-frm">
        <div class="section">
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr style="display:none;">
                    <th scope="col">운영자 메뉴권한<em class="ess"></em></th>
                    <td>
					    <div>
						    <?=inputSelect("mg_idx","mg_idx",$option_grant,getData($info,"mg_idx"))?>
						</div>
						<p class="exc1">등록된 메뉴가 없을경우 권한별 메뉴관리에서 메뉴 등록후 설정 해주세요.</p>
					</td>
                </tr>
                <tr>
                    <th scope="col"><!--운영자 -->아이디<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <label>
                                <?=inputText("au_id","au_id",getData($info,"au_id"),"아이디","numEngOnly",16)?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col"><!--운영자 -->비밀번호<em class="ess"></em></th>
                    <td>
                      <!--
                    	<label class="d-inline <?php /* =isEmpty($info) ? "hidden":"" */?>" style="display:none;">
					            </label>
                      -->
                      <div style="display:none;"><?=inputCheck("chg_pwd","chg_pwd",["Y"=>"변경"],"")?></div>
                    	<div class="pwd_cont <?php /* =isEmpty($info) ? "":"hidden" */?>">
						              <div class="input-text w200">
	                            <label>
	                                <?=inputPassword("au_pwd","au_pwd","","비밀번호","",20)?>
	                            </label>
	                        </div>
	                        <div class="input-text w200 mt-5">
	                            <label>
	                                <?=inputPassword("au_pwd_c","au_pwd_c","","비밀번호 확인","",20)?>
	                            </label>
	                        </div>
                        </div>
                        <p class="exc1 d-block <?=isEmpty($info) ? "hidden":""?>">비밀번호를 입력하면 변경 저장됩니다.<!--체크시 변경가능합니다.--></p>
					          </td>
                </tr>
                <tr>
                    <th scope="col"><!--운영자 -->이름<em class="ess"></em></th>
                    <td>
					    <div class="input-text w200">
                            <label>
                                <?=inputText("au_name","au_name",getData($info,"au_name"),"홍길동","",20)?>
                            </label>
                        </div>
					</td>
                </tr>

                <tr>
                    <th scope="col"><!--운영자  -->HP</th>
                    <td>
					    <div class="input-text w200">
                            <label>
                                <?=inputText("au_hp","au_hp",getData($info,"au_hp"),"010-0000-0000","telOnly",20)?>
                            </label>
                        </div>
					</td>
                </tr>
                <tr>
                    <th scope="col"><!--운영자 -->이메일</th>
                    <td>
					    <div class="input-text w200">
                            <label>
                                <?=inputText("au_email","au_email",getData($info,"au_email"),"00000000@naver.com","",50)?>
                            </label>
                        </div>
					          </td>
                </tr>
                <tr>
                    <th scope="col">사용권한</th>
                    <td class="inline-wrap">
                      <label><input type="checkbox" id="role_mem_chk"     name="role_mem_chk"     style="appearance: auto; vertical-align: -5px; height: 18px; width: 18px;"> 회원관리</label>&nbsp;&nbsp;&nbsp;
                      <label><input type="checkbox" id="role_recresu_chk" name="role_recresu_chk" style="appearance: auto; vertical-align: -5px; height: 18px; width: 18px;"> 채용공고/이력서관리</label>&nbsp;&nbsp;&nbsp;
                      <label><input type="checkbox" id="role_trade_chk"   name="role_trade_chk"   style="appearance: auto; vertical-align: -5px; height: 18px; width: 18px;"> 중고거래관리</label>&nbsp;&nbsp;&nbsp;
                      <label><input type="checkbox" id="role_ad_chk"      name="role_ad_chk"      style="appearance: auto; vertical-align: -5px; height: 18px; width: 18px;"> 유료광고관리</label>&nbsp;&nbsp;&nbsp;
                      <label><input type="checkbox" id="role_pay_chk"     name="role_pay_chk"     style="appearance: auto; vertical-align: -5px; height: 18px; width: 18px;"> 결제/포인트관리</label>&nbsp;&nbsp;&nbsp;
                      <label><input type="checkbox" id="role_site_chk"    name="role_site_chk"    style="appearance: auto; vertical-align: -5px; height: 18px; width: 18px;"> 사이트관리</label>
                      <input type="hidden" id="role_mem"     name="role_mem"     value="<?=$info['role_mem']?>">
                      <input type="hidden" id="role_recresu" name="role_recresu" value="<?=$info['role_recresu']?>">
                      <input type="hidden" id="role_trade"   name="role_trade"   value="<?=$info['role_trade']?>">
                      <input type="hidden" id="role_ad"      name="role_ad"      value="<?=$info['role_ad']?>">
                      <input type="hidden" id="role_pay"     name="role_pay"     value="<?=$info['role_pay']?>">
                      <input type="hidden" id="role_site"    name="role_site"    value="<?=$info['role_site']?>">
					          </td>
                </tr>
                <tr>
                    <th scope="col">사용여부</th>
                    <td class="inline-wrap">
					             <div class="inline-wrap">
                           <?=inputRadio("au_status","au_status",["Y"=>"사용","N"=>"미사용"],getData($info,"au_status","Y"),"")?>
                       </div>
					          </td>
                </tr>

                </tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" class="btn-l btn-type2 w150 btnList">목록으로</button>
            <button type="button" class="btn-l btn-type10 w150 ml-10 btnSave">저장하기</button>
        </div>
    </div>
</form>
<script>
	$(".btnList").click(function(){
		history.go(-1);
	});

	$(".btnSave").click(function(){
		if($("#mg_idx").val()==""){
    		COMMON.alert("운영자 메뉴권한 선택해주세요.",function(){$("#mg_idx").focus();});
    		return;
    	}

    	if($("#au_id").val()==""){
    		COMMON.alert("운영자 아이디를 입력해주세요.",function(){$("#au_id").focus();});
    		return;
    	}

    	if($("#au_id").val().length  < 6 || $("#au_id").val().length  > 16){
    		COMMON.alert("운영자 아이디는 6-16 사이로 입력해주세요.",function(){$("#au_id").focus();});
    		return;
    	}

    	if($("#au_id").val()==""){
    		COMMON.alert("운영자 메뉴권한 선택해주세요.",function(){$("#au_id").focus();});
    		return;
    	}

      if(($("#au_idx").val()==undefined || $("#au_idx").val()=="") && $("#au_pwd").val()==""){
    		COMMON.alert("비밀번호를 입력해주세요.",function(){$("#au_pwd").focus();});
    		return;
    	}

        if($("#au_pwd").val()!="" || $("#au_pwd_c").val()!=""){
          //$("[name=proc_mode]").val()=="insert" || $("#chk_pwd").is(":checked")==true
        	if($("#au_pwd").val()==""){
        		COMMON.alert("비밀번호를 입력해주세요.",function(){$("#au_pwd").focus();});
        		return;
        	}

        	if($("#au_pwd").val().length <6 ||  $("#au_pwd").val().length > 16){
        		COMMON.alert("비밀번호는 6~16 사이로 입력해주세요.",function(){$("#au_pwd").focus();});
        		return;
        	}

        	if($("#au_pwd_c").val()==""){
        		COMMON.alert("비밀번호확인을 입력해주세요.",function(){$("#au_pwd_c").focus();});
        		return;
        	}

        	if($("#au_pwd").val() != $("#au_pwd_c").val()){
        		COMMON.alert("비밀번호와 비밀번호 확인이 다릅니다.",function(){$("#au_pwd_c").focus();});
        		return;
        	}
        }

        if($("#au_name").val()==""){
    		COMMON.alert("운영자 명을 입력해주세요.",function(){$("#au_name").focus();});
    		return;
    	}

      $("#role_mem").val($("#role_mem_chk").is(":checked") ? "Y" : "N");
      $("#role_recresu").val($("#role_recresu_chk").is(":checked") ? "Y" : "N");
      $("#role_trade").val($("#role_trade_chk").is(":checked") ? "Y" : "N");
      $("#role_ad").val($("#role_ad_chk").is(":checked") ? "Y" : "N");
      $("#role_pay").val($("#role_pay_chk").is(":checked") ? "Y" : "N");
      $("#role_site").val($("#role_site_chk").is(":checked") ? "Y" : "N");

		var params = $("#frm").serialize();
		comAjax("/admin/setting/manage/user_edit_proc",params,function(result){
			if(result.code == "00"){
				COMMON.alert(result.msg,function(){
					location.href="/admin/setting/manage/user";
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

  $(document).ready(function(){
    if($('#au_id').val()!=''){
      $('#au_id').attr('readonly','readonly');
      $('#au_id').css('background-color','#ccc');
    }

    if($('#role_mem').val()=='Y') $('#role_mem_chk').attr('checked','checked');
    if($('#role_recresu').val()=='Y') $('#role_recresu_chk').attr('checked','checked');
    if($('#role_trade').val()=='Y') $('#role_trade_chk').attr('checked','checked');
    if($('#role_ad').val()=='Y') $('#role_ad_chk').attr('checked','checked');
    if($('#role_pay').val()=='Y') $('#role_pay_chk').attr('checked','checked');
    if($('#role_site').val()=='Y') $('#role_site_chk').attr('checked','checked');

  });

</script>
