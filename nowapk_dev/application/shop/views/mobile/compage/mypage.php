<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab1";?>
           <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="board-write">
                    <div class="form-label" style="margin-bottom: 60px;">
                        <p style="font-size: 1.3em; width: max-content;">회원정보 확인·관리</p>
                    </div>
                    <form id="frm" onsubmit="return false">
                    	<input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
						<input type="hidden" name="bd_idx" value="<?=getData($info,"bd_idx")?>" />
                        <div class="form-row toggle-form">
                            <div class="form-label">
                                <p>기업정보</p>
                            </div>
                            <div class="value">
                            </div>
                        </div>
                        <!-- 세금계산서 추가 폼 -->
                        <div class="form-row toggle-form">
                            <div class="form-label">
                            </div>
                            <div class="value">
                              <button type="button" id="btnBizUpdate" class="btn-round btn-secondary" onclick="$('#dimwrap,#dimcont').show();" style="float: right; background-color: #000; padding: 5px 10px; margin-top: 7px;">수정</button>
                              <table>
                                  <tbody>
                                      <tr>
                                          <td class="value" rowspan="2" style="width:320px;">
                                            <style>
                                            .form-pic:before { right: -256px; bottom: -15px; z-index: 2; }
                                            </style>
                                            <div class="thumb-wrap" style="display: block; width: 100%; max-width: 300px; max-height: 180px; overflow: hidden; ">
                                              <label class="form-pic" style="border-radius: 0; border:0; background-color: transparent;">
                                                  <input type="file" name="profile2" accept="image/*" class="js-file-preview">
                                                  <span class="preview" style="max-width: 300px; max-height: 180px;">
                                                      <img data-src="" alt="" class="lozad" data-loaded="true" style="max-width: 280px; max-height: 180px; z-index: auto; background-color: #fff;">
                                                  </span>
                                              </label>
                                            </div>
                                          </td>
                                          <td style="width:170px;">사업장명</td>
                                          <td >
                                              <label>
                                                  <span class="sr-text">사 업 장 명</span>
                                                  <input type="email" name="bizEmail" placeholder="사업장명을 입력해 주세요" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                                              </label>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style="width:170px;">사업자등록번호</td>
                                          <td >
                                              <label class="col">
                                                  <span class="sr-text">사업자등록번호입력</span>
                                                  <input type="text" name="bizCode" placeholder="사업자등록번호를 입력해 주세요" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                                              </label>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                            </div>
                        </div>

                        <div class="form-row toggle-form" style="margin-top:150px;">
                            <div class="form-label">
                                <p>가입자정보</p>
                            </div>
                            <div class="value">
                            </div>
                        </div>
                        <!-- 세금계산서 추가 폼 -->
                        <div class="form-row toggle-form">
                            <div class="form-label">
                            </div>
                            <div class="value">
                              <button type="button" id="btnInfoUpdate" class="btn-round btn-secondary" style="float: right; background-color: #000; padding: 5px 10px; margin-top: 7px;">수정</button>
                              <table>
                                  <tbody>
                                      <tr>
                                          <td>가입자명</td>
                                          <td>
                                              <label>
                                                  <span class="sr-text">가입자명 입력</span>
                                                  <input type="text" name="bizName" placeholder="가입자명을 입력해 주세요" aria-required="true" class="form-text" style="border: none;" readonly>
                                              </label>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td>연 락 처</td>
                                          <td>
                                              <label>
                                                  <span class="sr-text">연락처입력</span>
                                                  <input type="text" name="bizPhone" placeholder="연락처를 입력해 주세요" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                                              </label>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td>이 메 일</td>
                                          <td>
                                              <label>
                                                  <span class="sr-text">이메일입력</span>
                                                  <input type="email" name="bizEmail" placeholder="이메일을 입력해 주세요" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                                              </label>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td>최근수정일</td>
                                          <td>
                                              <label class="col">
                                                  <span class="sr-text">최근수정일</span>
                                                  <input type="text" name="bizCode" placeholder="최근수정일" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                                              </label>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                            </div>
                        </div>
                        <!-- //세금계산서 추가 폼 -->
                        <div class="form-row" style="margin-top:150px;">
                            <div class="form-label">
                                <p>비밀번호</p>
                            </div>
                            <div class="value">
                                <label>
                                    <span class="sr-text">******</span>
                                    <input type="text" name="bd_pass" value="******" class="form-text" readonly style="border: none;">
                                </label>
                                <button type="button" id="btnPWUpdate" class="btn-round btn-secondary" style="float: right; background-color: #000; padding: 5px 10px; margin-top: 7px;">수정</button>
                            </div>
                        </div>
                        <div class="form-row toggle-form" style="margin-top:150px;">
                            <div class="form-label">
                                <p>첨부사진</p>
                            </div>
                            <div class="value">
                                <div class="form-file js-show-filename">
                                    <input type="text" style="border: none;" disabled class="form-text file-name">
                                    <label class="btn-round btn-dark">
                                        <span class="sr-text">이미지 첨부하기</span>
                                        <input type="file" name="bd_img1" accept="image/*">
                                        <span>첨부하기</span>
                                    </label>
                                    <span class="preview">
	                                	<?php if(getData($info,"bd_img1")!="") : ?>
	                                		<input type='hidden' name='bd_img1_ori' value='<?=getData($info,"bd_img1")?>' />
	                                	<?php endif; ?>
                                	</span>
                                </div>
                                <div class="form-file js-show-filename">
                                    <input type="text" style="border: none;" disabled class="form-text file-name">
                                    <label class="btn-round btn-dark">
                                        <span class="sr-text">이미지 첨부하기</span>
                                        <input type="file" name="bd_img2" accept="image/*">
                                        <span>첨부하기</span>
                                    </label>
                                    <span class="preview">
                                	<?php if(getData($info,"bd_img1")!="") : ?>
                                		<input type='hidden' name='bd_img1_ori' value='<?=getData($info,"bd_img1")?>' />
                                	<?php endif; ?>
                                	</span>
                                </div>
                            </div>

                        </div>
                        <div class="form-row" style="margin-top:150px;">
                            <div class="form-label">
                                <p>회원탈퇴</p>
                            </div>
                            <div class="value">
                              <button type="button" id="btnRetire" class="btn-round btn-secondary" style="float: right; background-color: #000; padding: 5px 10px; margin-top: 7px;">탈퇴</button>
                            </div>
                        </div>

                        <!--
                        <div class="form-action-wrap">
                            <button type="button" id="btnCancel" class="btn-round btn-secondary">취소</button>
                            <button type="button" id="btnSave" class="btn-round btn-tertiary"><?=getProcMode($info)=="insert" ? "작성완료":"수정완료"?></button>
                        </div>
                        -->
                    </form>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
<div id="dimwrap" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; background-color: #000; opacity: 0.3;"></div>
<div id="dimcont" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 500px; height: 452px; z-index: 20; background-color: #fff; padding: 40px;">
  <form id="frm1" name="frm1" onsubmit="return false">
  <div>
    <div style="font-size: 1.4em; font-weight: 600;">기업정보 수정</div>
    <a href="javascript:$('#dimwrap,#dimcont').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
  </div>
  <div style="font-size: 1.2em;">
    <div style="margin-top: 30px;">사업장명 ( <input type="checkbox" id="outyn" style="margin-left:10px;"> <span>아웃소싱 확인</span>&nbsp;&nbsp;&nbsp; )</div>
    <div>
      <input type="text" name="comp_name" placeholder="사업장명을 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
      <input type="hidden" name="mm_comp_type">
    </div>
    <div style="margin-top: 26px;">사업자 등록번호</div>
    <div>
      <input type="text" name="comp_num" id="bizno" placeholder="사업자 등록번호를 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: calc(100% - 70px);">
      <input type="hidden" id="biznoauth">
      <input type="hidden" name="comp_type" value="COMP">
      <button onclick="fBizAuth();" style="margin-left: 15px; font-size: 16px; padding: 5px 10px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">인증</button>
    </div>
    <div style="margin-top: 26px;">사업자 등록증 업로드</div>
    <div>
      <label for="file-upload" style="float:right; margin-left: 15px; font-size: 16px; padding: 5px 10px;background-color: #57CCAF; color: #fff; border: 1px solid #fff; cursor: pointer;">첨부</label>
      <span id="file-name" style="font-size: 15px;
    color: #aaa;
    font-weight: 100;
    border-bottom: 1px solid #ccc;
    width: calc(100% - 70px);
    display: inline-block;
    margin-top: 8px;
    line-height: 27px;">&nbsp;</span>
      <input type="file" name="mm_comp_auth" id="file-upload" style="display: none;" onchange="updateFileName(this)">
      <script>
          function updateFileName(input) {
              var fileName = input.files[0].name;
              document.getElementById('file-name').innerText = fileName;
          }
      </script>
    </div>
    <div style="font-size: 16px; margin-top: 10px">ⓘ 나우잡 관리자가 승인한 뒤에 아웃소싱 업체로 사용 가능합니다.</div>
  </div>
  </form>
  <div style="text-align: center;margin-top: 20px;">
      <button id="btnSave" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">수정완료</button>
  </div>
</div>
<script>
    $(document).ready(function(){
    	$("#btnCancel").click(function(){
    		history.back();
    	});

    	$("#btnSave").click(function(){

    		if($("[name=comp_name]").val()==""){
    			commonAlert("사업장명을 선택해주세요.",function(){
                    $("[name=comp_name]").focus();
                });
                return;
    		}

    		if($("[name=comp_num]").val()==""){
    			commonAlert("사업자등록번호를 입력해주세요.",function(){
                    $("[name=comp_num]").focus();
                });
                return;
    		}
        if($("[name=comp_num]").val()!==$("#biznoauth").val()){
    			commonAlert("사업자등록번호를 인증해주세요.",function(){
                    $("[name=comp_num]").focus();
                });
                return;
    		}

    		if($("[name=mm_comp_auth]").val()==""){
    			commonAlert("사업자등록증을 첨부 해주세요.",function(){
                    $("[name=mm_comp_auth]").focus();
                });
                return;
    		}

        //아웃소싱이면
        //mm_comp_type = OUT
        //outyn
        if ($('#outyn').is(':checked')) {
            $('input[name="comp_type"]').val('OUT');
        } else {
            $('input[name="comp_type"]').val('COMP');
        }


       	var form = $('#frm1');
			  var params = new FormData(form[0]);
        console.log(params);
        //alert('submit');
        fileAjax("/compage_proc/com_biz_update",params,function(ret){
            //alert(ret);
            console.log(ret);
            if(ret.code=="00"){
              commonAlert("수정 되었습니다.",function(){
                  location.reload();
              });
            }else{
              commonAlert(ret.msg,function(){});
            }
    	  });
    });

  });//ready

  function fBizAuth(){

    if($('#bizno').val()==''){
        commonAlert("사업자 등록번호를 입력해주세요.",function(){
                $("#bizno").focus();
            });
            return;
    }

    var data = {
        "b_no": [$("#bizno").val()] // 사업자번호 "xxxxxxx" 로 조회 시,
    };
    $.ajax({
        url: "https://api.odcloud.kr/api/nts-businessman/v1/status?serviceKey=nbqW%2FFUs8GPs9gugbRl6wK2fqyEhU%2F4kDV1UzIEmA%2BgNYC9C1IyNsLwER%2FR5ALL5p20DpErEsQ2QlWm38oD%2FGA%3D%3D",  // serviceKey 값을 xxxxxx에 입력
        type: "POST",
        data: JSON.stringify(data), // json 을 string으로 변환하여 전송
        dataType: "JSON",
        contentType: "application/json",
        accept: "application/json",
        success: function(result) {
          console.log(result);
            if(result.status_code=="OK"){
                if(result.data[0].b_stt_cd == "01" || result.data[0].b_stt_cd == "01"){
                    bus_yn = true;
                    //$("#msg" + type).show();
                    //$("#msg" + type).html("사업자번호 인증완료");
                    $("#biznoauth").val($("#bizno").val());
                    commonAlert("사업자번호 인증완료",function(){ });
                    return;
                }else if(result.data[0].b_stt_cd == "03"){
                    bus_yn = false;
                    //$("#msg" + type).show();
                    //$("#msg" + type).html("폐업된 사업자 번호입니다.");
                    commonAlert("폐업된 사업자 번호입니다.",function(){
                            $("#bizno").focus();
                    });
                    return;
                }else{
                    bus_yn = false;
                    //$("#msg" + type).show();
                    //$("#msg" + type).html("등록되지 않은 사업자 번호입니다.");
                    commonAlert("등록되지 않은 사업자 번호입니다.",function(){
                            $("#bizno").focus();
                    });
                    return;
                }
            }else{
                bus_yn = false;
                commonAlert("사업자 번호가 유효 하지 않습니다.",function(){});
            }
        },
        error: function(result) {
            bus_yn = true;
        }
    });

  }

</script>
