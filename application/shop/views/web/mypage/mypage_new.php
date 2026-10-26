<?php
function fBizNoMasking($bizNo){
  $maskedBizNo = substr($bizNo, 0, 3) . '-' .
               substr($bizNo, 3, 2) . '-' .
               str_repeat('*', 5);
  return $maskedBizNo;
}

function fPhoneMasking($arg){
  return $arg;
  // // 전화번호 형식 확인 및 마스킹 처리
  // if (preg_match('/^010(\d{8})$/', $arg, $matches)) {
  //   $maskedPhoneNo = '010-' . $matches[1][0] . '***-' . $matches[1][4] . '***';
  //   return $maskedPhoneNo;
  // } else {
  //     return "";
  // }
}

function fEmailMasking($email){
  if($email==""){
    return "";
  }else{
    // 이메일 주소를 "@" 기준으로 분리
    list($username, $domain) = explode('@', $email);

    // 사용자 이름의 첫 2글자를 보존하고 나머지는 별표로 마스킹
    $maskedUsername = substr($username, 0, 2) . str_repeat('*', strlen($username) - 2);

    // 마스킹된 사용자 이름과 도메인을 결합
    return $maskedEmail = $maskedUsername . '@' . $domain;
  }
}
?>
<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab1";?>
           <?php include_once (get_base_url() . '/application/shop/views/web/mypage/top_line_2025.php'); ?>
            <div class="tab-panel on">
                <div class="board-write">
                    <div class="form-label" style="margin-bottom: 60px; max-width: 900px; margin: 0 auto; width: 100%;">
                        <p style="font-size: 1.3em; width: max-content;">회원정보 확인·관리</p>
                    </div>
                    <form id="frmAll" onsubmit="return false" style="max-width: 900px; display: block; margin: 0 auto;">
                    	<input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />
						          <input type="hidden" name="bd_idx" value="<?=getData($info,"bd_idx")?>" />

                        <div class="form-row toggle-form" style="margin-top:70px; border-top:1px solid #ccc;">
                            <div class="form-label" style="font-size: 22px;">
                                <p>가입자정보
                                  <?php
                                  // SNS 로그인 여부 확인
                                  $is_sns_login = !empty($info["mm_join_type"]) && in_array(strtoupper($info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER"]);
                                  // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
                                  $is_authenticated = ($info != null && $info["mm_di"] != null && (strpos($info["mm_di"], "MC0") === 0 || $is_sns_login));

                                  if (!$is_authenticated) {
                                  ?>
                                    <span style="font-size: 0.9rem; color: #777; margin-left: 10px;">미인증</span>
                              		<?php }else{ ?>
                                    <span style="font-size: 0.9rem; color: #57CCAF; margin-left: 10px;">인증완료</span>
                                  <?php } ?>
                                </p>
                            </div>
                            <div class="value">
                            </div>
                        </div>
                        <div class="form-row toggle-form">
                            <div class="value">
                              <?php
                              // SNS 로그인 여부 확인 (버튼 표시용)
                              $is_sns_login_btn = !empty($info["mm_join_type"]) && in_array(strtoupper($info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER"]);
                              // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
                              $is_authenticated_btn = ($info != null && $info["mm_di"] != null && (strpos($info["mm_di"], "MC0") === 0 || $is_sns_login_btn));
                              
                              if (!$is_authenticated_btn) { ?>
                                <button type="button" onclick="fManCheck();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: -60px;">인증</button>
                              <?php }else{ ?>
                                <button type="button" onclick="fManCheck();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: -60px;">변경</button>
                              <?php } ?>
                              <!--<button type="button" id="btnInfoUpdate" onclick="$('#dimwrap,#dimcont2').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: -60px;">인증</button>-->
                              <table>
                                  <tbody>
                                      <tr>
                                          <td style=" font-size: 18px; font-weight: 600; color: #777;">가입자명</td>
                                          <td>
                                              <label>
                                                  <span class="sr-text">가입자명 입력</span>
                                                  <input type="text" name="bizName" placeholder="guest" value="<?=(( strpos(getData($info,"mm_name"), '010') !== 0 && strpos(getData($info,"mm_name"), 'kakao') !== 0 && strpos(getData($info,"mm_name"), 'apple') !== 0) ? getData($info,"mm_name") : "guest")?>" aria-required="true" class="form-text" style="border: none;" readonly>
                                              </label>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style=" font-size: 18px; font-weight: 600; color: #777;">연 락 처</td>
                                          <td>
                                              <label>
                                                  <span class="sr-text">연락처입력</span>
                                                  <input type="text" name="bizPhoneView" placeholder="연락처를 입력해 주세요" value="<?=fPhoneMasking(getData($info,"mm_phone"))?>" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                                                  <input type="hidden" name="bizPhone" placeholder="연락처를 입력해 주세요" value="<?=getData($info,"mm_phone")?>" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                                              </label>
                                          </td>
                                      </tr>
                                      <tr style="display:none;">
                                          <td style=" font-size: 18px; font-weight: 600; color: #777;">이 메 일</td>
                                          <td>
                                              <label>
                                                  <span class="sr-text">이메일입력</span>
                                                  <input type="email" name="bizEmail" placeholder="이메일을 입력해 주세요" value="<?=fEmailMasking(getData($info,"mm_email"))?>" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                                              </label>
                                              <button type="button" id="btnInfoUpdate" onclick="$('#dimwrap,#dimcont2').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; padding: 10px 10px; margin-top: 0px; position: absolute; width: 64px; right: 0px;">입력</button>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style=" font-size: 18px; font-weight: 600; color: #777;">최근수정일</td>
                                          <td>
                                              <label class="col">
                                                  <span class="sr-text">최근수정일</span>
                                                  <input type="text" name="bizCode" placeholder="최근수정일" value="<?=(getData($info,"mem_info_up_dt")=='' ? getData($info,"up_dt") : getData($info,"mem_info_up_dt"))?>" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                                              </label>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td style=" font-size: 20px; font-weight: 700;"></td>
                                          <td>
                                              <label class="col" style="float: right; text-align: right; color: #aaa; margin-top: 30px;">
                                                <!--안전한 정보보호를 위해 연락처 일부만 확인 가능하며,<br>수정화면에서 정확한 연락처 확인이 가능합니다.-->
                                                PASS 인증을 해야 이력서 등록이 가능합니다.
                                              </label>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                            </div>
                        </div>

                        <div class="form-row" style="margin-top:70px; border-top:1px solid #ccc;">
                            <div class="form-label" style="font-size: 22px;">
                                <p>비밀번호</p>
                            </div>
                            <div class="value" style="margin-top:10px;">
                                <label>
                                    <span class="sr-text">******</span>
                                    <input type="text" name="bd_pass" value="******" class="form-text" readonly style="border: none;">
                                </label>
                                <button type="button" id="btnPWUpdate" onclick="$('#dimwrap,#dimcont3').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: 0px; position: absolute; right: 0;">수정</button>
                            </div>
                        </div>
                        <div class="form-row" style="margin-top:10px;">
                            <div class="form-label" style="font-size: 22px;">
                            </div>
                            <div class="value">
                                <label style="float: right; text-align: right; color: #aaa; margin-top: 0px;">
                                  <span style="color: rgb(248, 96, 72); ">
                                    <span style="font-size: 18px; border: 1px solid rgb(248, 96, 72); width: 22px; height: 22px; text-align: center; line-height: 22px; border-radius: 16px; display: inline-block; font-weight: 600;">！</span>
                                    비밀번호는 8~20자의 영문,숫자,최소한 하나의 특수문자(@,$,!,%,*,?,&)를 포함해야 합니다.</span><br>
                                  비밀번호를 주기적으로 변경하여 소중한 개인정보를 안전하게 보호하세요.
                                </label>
                            </div>
                        </div>

                        <div class="form-row" style="margin-top:70px; border-top:1px solid #ccc;">
                            <div class="form-label" style="font-size: 22px;">
                                <p>회원탈퇴</p>
                            </div>
                            <div class="value">
                              <button type="button" id="btnRetire" onclick="$('#dimwrap,#dimcont4').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: 20px;">탈퇴</button>
                            </div>
                        </div>

                        <div class="form-action-wrap">
                            <!-- <button type="button" id="btnCancel" class="btn-round btn-secondary">취소</button> -->
                            <button type="button" id="btnSaveAll" class="btn-round btn-tertiary"><?=getProcMode($info)=="insert" ? "작성완료":"저장"?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
<div id="dimwrap" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; background-color: #000; opacity: 0.3;"></div>
<div id="dimcont" style="display:none; position: fixed; top: 20%; left: calc(50% - 250px); width: 500px; height: 570px; z-index: 10; background-color: #fff; padding: 40px;">
  <form id="frm1" name="frm1" onsubmit="return false">
    <input type="hidden" id="compUpdateType" name="compUpdateType">
  <div>
    <div style="font-size: 1.4em; font-weight: 600;">가입자정보 수정</div>
    <a href="javascript:$('#dimwrap,#dimcont').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
  </div>
  <div style="font-size: 1.2em;">
    <div class="compnamebox" style="margin-top: 30px;">사업장명 ( <input type="checkbox" id="outyn" style="margin-left:10px;"> <span>아웃소싱 확인</span>&nbsp;&nbsp;&nbsp; )</div>
    <div class="compnamebox" >
      <input type="text" name="comp_name" placeholder="사업장명을 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
      <input type="hidden" name="mm_comp_type">
    </div>
    <div class="biznobox" style="margin-top: 26px;">사업자 등록번호</div>
    <div class="biznobox" >
      <input type="text" name="comp_num" id="bizno" placeholder="사업자 등록번호를 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: calc(100% - 70px);">
      <input type="hidden" id="biznoauth">
      <input type="hidden" name="comp_type" value="COMP">
      <button onclick="fBizAuth();" style="margin-left: 15px; font-size: 16px; padding: 5px 10px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">인증</button>
    </div>
    <div class="bizimgbox" style="margin-top: 26px;">사업자 등록증 업로드</div>
    <div class="bizimgbox" >
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
    <div class="sousinghelp"  style="font-size: 16px; margin-top: 10px; color: #57CCAF;">ⓘ 나우잡 관리자가 승인한 뒤에 아웃소싱 업체로 사용 가능합니다.</div>
    <div class="addrbox" style="margin-top: 30px;">사업장주소</div>
    <div class="addrbox" >
      <input type="text"   id="mm_addr"    name="mm_addr" placeholder="사업장 주소를 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;" onclick="$('#changeArea').addClass('on');">
      <input type="hidden" id="mm_zipcode" name="mm_zipcode">
      <input type="hidden" id="mm_doro"    name="mm_doro">
      <input type="hidden" id="mm_jibun"   name="mm_jibun">
    </div>
  </div>
  </form>
  <div style="text-align: center;margin-top: 20px;">
      <button id="btnSave" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">수정완료</button>
  </div>
</div>

<form name="form_chk" method="post">
	<input type="hidden" name="m" value="checkplusService">
	<input type="hidden" name="EncodeData" value="<?= $enc_data ?>">
</form>
<form id="frm2" name="frm2" onsubmit="return false">
  <input type="hidden" name="mm_name" readonly placeholder="가입자명(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;" onfocus="commonAlert('본인인증을 완료하면 자동으로 입력됩니다.',function(){});">
  <input type="hidden" name="mm_phone" id="mm_phone" readonly placeholder="연락처(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: calc(100% - 130px);" onfocus="commonAlert('본인인증을 완료하면 자동으로 입력됩니다.',function(){});">
  <input type="hidden" name="mm_di" id="mm_di">
  <input type="hidden" name="mm_sex" id="mm_sex">
</form>

<div id="dimcont2" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 500px; height: 252px; z-index: 20; background-color: #fff; padding: 40px;">
  <form id="frm21" name="frm21" onsubmit="return false">
  <div>
    <div style="font-size: 1.4em; font-weight: 600;">가입자정보 수정</div>
    <a href="javascript:$('#dimwrap,#dimcont2').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
  </div>
  <div style="font-size: 1.2em;">
    <div style="display:none;margin-top: 30px;">가입자명</div>
    <div style="display:none;">
      <!--<input type="text" name="mm_name" readonly placeholder="가입자명(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;" onfocus="commonAlert('본인인증을 완료하면 자동으로 입력됩니다.',function(){});">-->
    </div>
    <div style="display:none;margin-top: 26px;">연락처</div>
    <div style="display:none;">
      <!--<input type="text" name="mm_phone" id="mm_phone" readonly placeholder="연락처(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: calc(100% - 130px);" onfocus="commonAlert('본인인증을 완료하면 자동으로 입력됩니다.',function(){});">-->
      <button onclick="fManCheck();" style="margin-left: 15px; font-size: 16px; padding: 5px 10px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">변경(본인인증)</button>
      <!--<input type="hidden" name="mm_di" id="mm_di">-->
    </div>
    <div style="margin-top: 26px;">이메일주소</div>
    <div>
      <input type="text" name="mm_email" id="mm_email" placeholder="이메일을 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
    </div>
  </div>
  </form>
  <div style="text-align: center;margin-top: 20px;">
      <button id="btnSave2" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">수정완료</button>
  </div>
</div>
<div id="dimcont3" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 500px; height: 500px; z-index: 20; background-color: #fff; padding: 40px;">
  <form id="frm3" name="frm3" onsubmit="return false">
  <div>
    <div style="font-size: 1.4em; font-weight: 600;">비밀번호 수정</div>
    <a href="javascript:$('#dimwrap,#dimcont3').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
  </div>
  <div style="font-size: 1.2em;">
    <div style="margin-top: 30px;">기존 비밀번호</div>
    <div>
      <input type="password" name="mm_pass_old" placeholder="현재 비밀번호 입력" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
    </div>
    <div style="margin-top: 26px;">새 비밀번호</div>
    <div>
      <input type="password" name="mm_pass" id="mm_pass" placeholder="새 비밀번호 입력" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
    </div>
    <div style="font-size: 16px; margin-top: 10px; color: #F86048;">비밀번호는 8~20자의 영문,숫자,최소한 하나의 특수문자(@,$,!,%,*,?,&)를 포함해야 합니다.</div>
    <div style="margin-top: 26px;">새 비밀번호 확인</div>
    <div>
      <input type="password" name="mm_pass2" id="mm_pass2" placeholder="새 비밀번호 확인" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
    </div>
  </div>
  </form>
  <div style="text-align: center;margin-top: 20px;">
      <button id="btnSave3" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">수정완료</button>
  </div>
</div>
<div id="dimcont4" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 500px; height: 426px; z-index: 20; background-color: #fff; padding: 40px;">
  <form id="frm4" name="frm4" onsubmit="return false">
  <div>
    <div style="font-size: 1.4em; font-weight: 600;">회원탈퇴</div>
    <a href="javascript:$('#dimwrap,#dimcont4').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
  </div>
  <div style="font-size: 1.2em;">
    <div style="margin-top: 30px;">유의사항</div>
    <div style="font-size: 0.8em; line-height: 26px; padding-top: 15px;">
      1. 탈퇴한 전화번호는 추후 재가입시 <?= WITHDRAW_BLOCK_DAYS ?>일간 가입이 제한 됩니다. <br>
      2. 채용정보, 인재정보, 중고거래, 유료서비스, 포인트 모두 삭제되며,  <br>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;삭제된 데이터는 복구 되지 않습니다. <br>
      3. 회원탈퇴 관련 고객응대를 목적으로 1년간 탈퇴 신청자, 이메일     <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;정보를 보관 후 파기 합니다. <br>
    </div>
    <div style="display: flex; padding-top: 20px;">
      <input type="checkbox" name="retire_agree" id="retire_agree" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; width: 20px;display: inline-block;">
      <span style="font-size: 0.9em; display: inline; margin-top: 5px; margin-left: 10px;">유의 사항을 모두 확인했으며, 이에 동의합니다.</span>
    </div>
  </div>
  </form>
  <div style="text-align: center;margin-top: 20px;">
      <button id="btnSave4" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">탈퇴신청</button>
  </div>
</div>
<div id="dimcont5" style="display:none; position: fixed; top: 30%; left: calc(50% - 220px); width: 440px; height: 410px; z-index: 20; background-color: #fff; padding: 40px;">
  <div style="font-size: 1.2em;">
    <div style="margin-top: 30px;"><img src=''></div>
    <div style="margin-top: 30px; font-size: 1.2em; font-weight: 600; text-align: center; margin-bottom: 50px;">회원탈퇴가 완료되었습니다.</div>
    <div style="font-size: 0.9em;">
      NOWJOB을 이용해주시고 사랑해주셔서 감사합니다.<br>
      더욱더 노력하고 발전하는 NOWJOB이 되겠습니다.
    </div>
  </div>
  <div style="text-align: center;margin-top: 20px;">
      <button id="btnSave5" onclick="location.href='./';" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">확인</button>
  </div>
</div>
<div id="dimcont6" style="display:none; position: fixed; top: 33%; left: calc(50% - 230px); width: 460px; height: 410px; z-index: 20; background-color: #fff; padding: 40px;">
  <div style="font-size: 1.2em;">
    <div style="margin-top: 30px; font-size: 1.2em; font-weight: 600; text-align: center; margin-bottom: 50px;">인증된 전화번호와 <font style="color:red;">일치</font>하지 않습니다.</div>
    <div style="font-size: 0.9em;">
      인증된 전화번호로 PASS 인증을 진행해주세요.<br>
      기타 사유로 전화번호 변경시 고객센터(031-255-0119)에<br>문의해주세요.
    </div>
  </div>
  <div style="text-align: center;margin-top: 20px;">
      <button onclick="$('#dimcont6').hide();" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">확인</button>
  </div>
</div>
<div id="dimcont7" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 380px; height: 625px; z-index: 20; background-color: #fff; padding: 40px;">
  <div>
    <div style="font-size: 1.4em; font-weight: 600;">프로필사진 변경</div>
    <div id="profileCancel" style="position: absolute; right: 45px; font-size: 36px; top: 30px;cursor:pointer;">×</div>
  </div>
  <div style="">
    <link type='text/css' rel='stylesheet' href='/resource/shop/web/css/cropper.min.css?vs=1753162724' />
    <script src='/resource/shop/web/js/lib/cropper.min.js?vs=1753162724'></script>
    <style>
      .crop-wrapper {
        position: relative;
        width: 300px;
        height: 450px;
        margin:20px 0;
      }
      .cropper-view-box {
        border-radius: 50%;
      }
      .cropper-crop-box {
        border-radius: 50%;
        overflow: hidden;
      }
    </style>
    <div class="crop-wrapper">
      <img id="previewImage" style="max-width: 100%;" /> 
    </div>
    <div style="text-align: center;margin-top: 20px;">
      <button id="profileCrop" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">수정완료</button>
    </div>    
  </div>
</div>
<script>
  let profileBlob = null;
  let newProfileImageSelected = false;
  $(document).ready(function(){
    $('.profile-status .form-pic').addClass('on');
    $('input[name="profile2"]').removeAttr('disabled');

    const $previewImage = $('#previewImage');
    const $newInput = $('#newProfile');
    const $resultCanvas = $('#profilePhoto');
    const $cancel = $('#profileCancel');
    const $crop = $('#profileCrop');
    let cropper;

    $newInput.on('click', function () {
      this.value = null;
    });

    $newInput.on('change', function (e) {
      const file = e.target.files[0];
      if (!file) return;
      $('#dimwrap, #dimcont7').show();

      newProfileImageSelected = true;

      const url = URL.createObjectURL(file);
      $previewImage.attr('src', url);

      $previewImage.on('load', function () {
        if (cropper) cropper.destroy(); 
        cropper = new Cropper(this, {
          aspectRatio: 1,
          viewMode: 1,
          background: false,
          dragMode: 'move',
          guides: true,
          highlight: false,
          cropBoxMovable: false,
          cropBoxResizable: false,
          autoCropArea: 1,
        });
      });
    });

    $cancel.on('click', function () {
      profileBlob = null;
      newProfileImageSelected = false;
      $('#dimwrap, #dimcont7').hide();
    });

    $crop.on('click', function () {
      if (!cropper) return;

      const profileCanvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
        imageSmoothingQuality: 'high',
      });
      if (!profileCanvas) {
        alert('이미지가 생성되지 않았습니다.');
        return;
      }
      const newProfile = profileCanvas.toDataURL('image/png');
      $resultCanvas.attr('src', newProfile);

      profileCanvas.toBlob(function (blob) {
        profileBlob = blob;
      }, 'image/png');
      $('#dimwrap, #dimcont7').hide();
    });
  });

  $(document).ready(function(){
    $("#btnCancel").click(function(){
      history.back();
    });

    $("#btnSaveAll").click(function(){

      var form = $('#frmAll');
      var params = new FormData(form[0]);

      if (newProfileImageSelected && !profileBlob) {
        alert('새로운 프로필사진을 확인해주세요.');
        return;
      }
      if (profileBlob) {
        params.append('mm_thumb', profileBlob, 'newProfile.png');
      }

      fileAjax("/compage_proc/com_biz_updateAll",params,function(ret){
          if(ret.code=="00"){
            commonAlert("수정 되었습니다.",function(){
                location.reload();
            });
          }else{
            commonAlert(ret.msg,function(){});
          }
      });
  });
  
    function fManCheck(){
      if($("[name=EncodeData]").val()==""){
        alert("본인인증 모듈생성에 문제가 있습니다. 관리자에 문의해주세요.");
        return;
      }

      var form = $('#form_chk');
      var params = new FormData(form[0]);
      fileAjax("/member_proc/com_man_update",params,function(ret){
          console.log(ret);
          if(ret.code=="00"){

            window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
            document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
            document.form_chk.target = "popupChk";
            document.form_chk.submit();

            fPassSave();

          }else{
            commonAlert(ret.msg,function(){});
          }
      });

    }

    function fRetire(){
      var form = $('#form_chk');
      var params = new FormData(form[0]);
      fileAjax("/compage_proc/com_biz_retire",params,function(ret){
          //alert(ret);
          console.log(ret);
          if(ret.code=="00"){
            $('#dimcont4').hide();
            $('#dimcont5').show();
            //commonAlert("탈퇴 되었습니다.",function(){ });
          }else{
            commonAlert(ret.msg,function(){});
          }
      });
    }

    	$("#btnSave").click(function(){

    		if(($("[name=compUpdateType]").val()=="compname" || $("[name=compUpdateType]").val()=="all") && $("[name=comp_name]").val()==""){
    			commonAlert("사업장명을 선택해주세요.",function(){
                    $("[name=comp_name]").focus();
                });
                return;
    		}

    		if(($("[name=compUpdateType]").val()=="bizno" || $("[name=compUpdateType]").val()=="all") && $("[name=comp_num]").val()==""){
    			commonAlert("사업자등록번호를 입력해주세요.",function(){
                    $("[name=comp_num]").focus();
                });
                return;
    		}
        if(($("[name=compUpdateType]").val()=="bizno" || $("[name=compUpdateType]").val()=="all") && $("[name=comp_num]").val()!==$("#biznoauth").val()){
    			commonAlert("사업자등록번호를 인증해주세요.",function(){
                    $("[name=comp_num]").focus();
                });
                return;
    		}

    		if($("[name=compUpdateType]").val()=="all" && $("[name=mm_comp_auth]").val()==""){
    			commonAlert("사업자등록증을 첨부 해주세요.",function(){
                    $("[name=mm_comp_auth]").focus();
                });
                return;
    		}

        if($("[name=compUpdateType]").val()=="all" && $("[name=mm_addr]").val()==""){
    			commonAlert("사업장 주소를 입력해주세요.",function(){
                $("[name=mm_addr]").focus();
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
        //console.log(params);
        //alert('submit');
        fileAjax("/compage_proc/com_biz_update",params,function(ret){
            //alert(ret);
            //console.log(ret);
            if(ret.code=="00"){
              commonAlert("수정 되었습니다.",function(){
                  location.reload();
              });
            }else{
              commonAlert(ret.msg,function(){});
            }
    	  });
    });


    $("#btnSave2").click(function(){

      // if($("[name=mm_name]").val()==""){
      //   commonAlert("가입자명을 입력해주세요.",function(){
      //             $("[name=mm_name]").focus();
      //         });
      //         return;
      // }

      // if($("[name=mm_phone]").val()==""){
      //   commonAlert("연락처를 입력해주세요.",function(){
      //             $("[name=mm_phone]").focus();
      //         });
      //         return;
      // }

      if($("[name=mm_email]").val()==""){
        commonAlert("이메일을 입력 해주세요.",function(){
                  $("[name=mm_email]").focus();
              });
              return;
      }


      var form = $('#frm21');
      var params = new FormData(form[0]);
      console.log(params);
      //alert('submit');
      fileAjax("/compage_proc/com_biz_update4",params,function(ret){
          //alert(ret);
          console.log(ret);
          if(ret.code=="00"){
            commonAlert("수정 되었습니다.",function(){
                location.reload();
            });
          }else if(ret.code=="01"){
            commonAlert(ret.msg,function(){
              location.href ="/member/logout";
            });
          }else{
            commonAlert(ret.msg,function(){});
          }
      });
    });//btnSave2

    $("#btnSave3").click(function(){

      if($("[name=mm_pass_old]").val()==""){
        commonAlert("기존 비밀번호을 입력해주세요.",function(){
                  $("[name=mm_pass_old]").focus();
              });
              return;
      }

      if($("[name=mm_pass]").val()==""){
        commonAlert("새 비밀번호를 입력해주세요.",function(){
                  $("[name=mm_pass]").focus();
              });
              return;
      }

      if(fPassValid() === false){
        commonAlert("비밀번호 규칙에 맞지 않습니다.<br>비밀번호는 8~20자의 영문,숫자,최소한 하나의 특수문자(@,$,!,%,*,?,&)를 포함해야 합니다.",function(){
                  $("[name=mm_pass]").focus();
              });
              return;
      }

      if($("[name=mm_pass2]").val()==""){
        commonAlert("새 비밀번호 확인을 입력 해주세요.",function(){
                  $("[name=mm_pass2]").focus();
              });
              return;
      }

      if($("[name=mm_pass]").val() != $("[name=mm_pass2]").val()){
        commonAlert("새 비밀번호 확인이 일치하지 않습니다.",function(){
                  $("[name=mm_pass2]").focus();
              });
              return;
      }


      var form = $('#frm3');
      var params = new FormData(form[0]);
      console.log(params);
      //alert('submit');
      fileAjax("/compage_proc/com_biz_update3",params,function(ret){
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
    });//btnSave3

    $("[name=mm_pass]").on("input", function() {
        var password = $(this).val();
        var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

        if(regex.test(password)) {
            console.log("비밀번호 조건에 만족합니다.");
            // 조건에 만족할 경우의 추가적인 동작을 여기에 구현합니다.
        } else {
            console.log("비밀번호는 8~20자의 영문, 숫자, 최소한 하나의 특수문자(@, $, !, %, *, ?, &)를 포함해야 합니다.");
            // 조건에 만족하지 않을 경우의 추가적인 동작을 여기에 구현합니다.
        }
    });

    $("#btnSave4").click(function(){

      if(document.getElementById('retire_agree').checked === false) {
        commonAlert("유의사항을 확인 하고 동의해주세요.",function(){
                  $("[name=retire_agree]").focus();
              });
              return;
      }

      if($("[name=EncodeData]").val()==""){
        alert("본인인증 모듈생성에 문제가 있습니다. 관리자에 문의해주세요.");
        return;
      }

      var form = $('#form_chk');
      var params = new FormData(form[0]);
      fileAjax("/member_proc/com_man_retire",params,function(ret){
          console.log(ret);
          if(ret.code=="00"){

            window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
            document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
            document.form_chk.target = "popupChk";
            document.form_chk.submit();

          }else{
            commonAlert(ret.msg,function(){});
          }
      });

    });//btnSave4

    $('.tabs-wrap').hide();

    <?php if($info == null || strpos($info["mm_di"],"MC0") !== 0){ ?>
        //$('#btnInfoUpdate').trigger('click');
		<?php }else if($reqgbn == "pass"){ ?>
        $('#btnInfoUpdate').trigger('click');
    <?php } ?>

  });//ready

  function fPassValid(){
    var password = $("[name=mm_pass]").val();
    var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&#]{8,20}$/;

    if(regex.test(password)) {
        console.log("비밀번호 조건에 만족합니다.");
        // 조건에 만족할 경우의 추가적인 동작을 여기에 구현합니다.
        return true;
    } else {
        console.log("비밀번호는 8~20자의 영문, 숫자, 최소한 하나의 특수문자(@, $, !, %, *, ?, &, #)를 포함해야 합니다.");
        // 조건에 만족하지 않을 경우의 추가적인 동작을 여기에 구현합니다.
        return false;
    }

  }

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

  function fMemUpdateType(type){
    if(type=='compname'){
      $('.compnamebox').show();
      $('.biznobox').hide();
      $('.bizimgbox').hide();
      $('.sousinghelp').hide();
      $('.addrbox').hide();
      $('#dimcont').css('height','270px');
      $('#dimcont').css('top','30%');
    }else if(type=='bizno'){
      $('.compnamebox').hide();
      $('.biznobox').show();
      $('.bizimgbox').hide();
      $('.sousinghelp').hide();
      $('.addrbox').hide();
      $('#dimcont').css('height','270px');
      $('#dimcont').css('top','30%');
    }else{
      $('.compnamebox').show();
      $('.biznobox').show();
      $('.bizimgbox').show();
      $('.sousinghelp').show();
      $('.addrbox').show();
      $('#dimcont').css('height','570px');
      $('#dimcont').css('top','20%');
    }
    $('#compUpdateType').val(type);
  }
  // 검색 완료 선택 처리
	function selectAddrInfo(zipcode,doro,jibun,addr,x,y){
		$("#mm_zipcode").val(zipcode);
		$("#mm_jibun").val(doro);
		$("#mm_doro").val(jibun);
		$("#mm_addr").val(addr);
		$("#mm_gps_x").val(x);
		$("#mm_gps_y").val(y);

		//$("[name=rc_addr_doro]").val(doro);
		//$("[name=changeArea]").val(addr);

		$("#changeArea").removeClass("on");
	}

  function fPassSave(){
    console.log('fPassSave');

    if($('#mm_di').val()==''){
      setTimeout(function(){
        fPassSave();
      }, 1000);
      return;
    }

    setTimeout(function(){
      //alert('settimeout');
      var form = $('#frm2');
      var params = new FormData(form[0]);
      console.log(params);
      //alert('submit');
      fileAjax("/compage_proc/com_biz_update2",params,function(ret){
          //alert(ret);
          console.log(ret);
          if(ret.code=="00"){
            commonAlert("인증이 완료 되었습니다.",function(){
                location.reload();
            });
          }else{
            commonAlert(ret.msg,function(){});
          }
      });
    }, 1000);

  }
</script>
<?php include_once (get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>
