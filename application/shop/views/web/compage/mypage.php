<?php
function fBizNoMasking($bizNo)
{
  $maskedBizNo = substr($bizNo, 0, 3) . '-' .
    substr($bizNo, 3, 2) . '-' .
    str_repeat('*', 5);
  return $maskedBizNo;
}

function fPhoneMasking($arg)
{
  // // 전화번호 형식 확인 및 마스킹 처리
  // if (preg_match('/^010(\d{8})$/', $arg, $matches)) {
  //   $maskedPhoneNo = '010-' . $matches[1][0] . '***-' . $matches[1][4] . '***';
  //   return $maskedPhoneNo;
  // } else {
  //     return "";
  // }
  return $arg;
}

function fEmailMasking($email)
{
  if ($email == "") return "";
  // 이메일 주소를 "@" 기준으로 분리
  list($username, $domain) = explode('@', $email);

  // 사용자 이름의 첫 2글자를 보존하고 나머지는 별표로 마스킹
  $maskedUsername = substr($username, 0, 2) . str_repeat('*', strlen($username) - 2);

  // 마스킹된 사용자 이름과 도메인을 결합
  return $maskedEmail = $maskedUsername . '@' . $domain;
}
?>
<style>
  .form-pic {
    border-radius: 10px;
  }

  .form-pic .preview {
    border-radius: 10px;
  }
</style>
<div id="content" class="page page-mypage mypage-info">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab1"; ?>
      <?php $hide_tabs = true; // mypage.php에서만 tabs-wrap 숨김 
      ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
      <div class="tab-panel on">
        <div class="board-write">
          <div class="form-label" style="margin-bottom: 60px; max-width: 900px; margin: 0 auto; width: 100%;">
            <p style="font-size: 1.3em; width: max-content;">회원정보 확인·관리</p>
          </div>
          <form id="frmAll" onsubmit="return false" style="max-width: 900px; display: block; margin: 0 auto;">
            <input type="hidden" name="proc_mode" value="<?= getProcMode($info) ?>" />
            <input type="hidden" name="bd_idx" value="<?= getData($info, "bd_idx") ?>" />
            <div class="form-row toggle-form" style=" border-top:1px solid #ccc;">
              <div class="form-label" style="font-size: 22px;">
                <p>기업정보
                  <?php if ($info == null || $info["mm_comp_num"] == null || ($info["mm_comp_num"] == "")) { ?>
                    <!-- <span style="font-size: 0.9rem; color: #777; margin-left: 10px;">미인증</span> -->
                  <?php } else { ?>
                    <!-- <span style="font-size: 0.9rem; color: #57CCAF; margin-left: 10px;">인증완료</span> -->
                  <?php } ?>

                </p>
              </div>
              <div class="value">
              </div>
            </div>
            <div class="form-row toggle-form">
              <div class="value">
                <!-- <button type="button" id="btnBizUpdate" class="btn-round btn-secondary" onclick="$('#dimwrap,#dimcont').show();fMemUpdateType('all');" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: -60px;">수정</button> -->
                <table>
                  <tbody>
                    <tr>
                      <td class="value" rowspan="4" style="width:220px;">
                        <style>
                          .thumb-wrap.compinfo .form-pic {
                            position: relative;
                            display: inline-block;
                            width: 200px;
                            height: 200px;
                            max-width: 100%;
                            border: 1px solid #ddd;
                          }

                          .thumb-wrap.compinfo .form-pic:before {
                            top: auto;
                            left: auto;
                            bottom: -18px;
                            right: -18px;
                            width: 38px;
                            height: 38px;
                            pointer-events: inherit;
                            background-image: url('/resource/shop/web/img/ic_camera2.svg');
                            z-index: 10;
                          }

                          .thumb-wrap.compinfo .preview {
                            position: relative;
                            width: 100%;
                            height: 100%;
                          }

                          .thumb-wrap.compinfo .preview img {
                            display: block;
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background-color: inherit;
                            object-fit: cover;
                            z-index: 1;
                          }

                          .thumb-wrap.compinfo .preview .delete-btn {
                            display: none;
                            position: absolute;
                            width: 100%;
                            height: 100%;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            background: rgba(0, 0, 0, 0.4);
                            color: white;
                            border: none;
                            padding: 6px 12px;
                            font-size: 28px;
                            cursor: pointer;
                            border-radius: 4px;
                            z-index: 3;
                          }

                          .thumb-wrap.compinfo .preview:hover .delete-btn {
                            display: block;
                          }
                        </style>
                        <div class="thumb-wrap compinfo">
                          <label class="form-pic" style="max-width:200px; max-height: 200px;">
                            <input type="file" id="newLogo" name="mm_thumb" accept="image/*">
                            <input type='hidden' name='mm_thumb_del' value='' />
                            <span class="preview">
                              <img id="companyLogo" src="<?= getData($info, "mm_thumb") ?>" alt="" onError="this.src='/resource/shop/mobile/img/common/noimage4.png'">
                              <?php if (getData($info, "mm_thumb") != "") : ?>
                                <button type="button" class="delete-btn" onclick="fThumbDel()">삭제</button>
                              <?php endif; ?>
                            </span>
                          </label>
                        </div>
                      </td>
                      <td style="width:170px; font-size: 18px; font-weight: 600; color: #777;">사업자명 :</td>
                      <td>
                        <label>
                          <span class="sr-text">사 업 장 명</span>
                          <input type="email" name="bizEmail" placeholder="사업자 인증이 필요합니다." value="<?= ($info == null || $info["mm_comp_num"] == null || ($info["mm_comp_num"] == "")) ? "기업회원" : getData($info, "mm_comp_name") ?>" autocomplete="off" aria-required="true" class="form-text" style="border: none; width: 300px;" readonly>
                          <?php if ($info == null || $info["mm_comp_num"] == null || ($info["mm_comp_num"] == "")) { ?>
                            <button type="button" id="btnBizUpdate" class="btn-round btn-secondary" onclick="$('#dimwrap,#dimcont').show();fMemUpdateType('all');" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: 7px;">인증</button>
                          <?php } else { ?>
                            <button type="button" id="btnBizUpdate" class="btn-round btn-secondary" onclick="$('#dimwrap,#dimcont').show();fMemUpdateType('all');" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: 7px;">변경</button>
                          <?php } ?>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td style="width:170px; font-size: 18px; font-weight: 600; color: #777;">사업자등록번호 :</td>
                      <td>
                        <label class="col">
                          <span class="sr-text">사업자등록번호입력</span>
                          <input type="text" name="bizCode" placeholder="사업자 인증이 필요합니다." value="<?= fBizNoMasking(getData($info, "mm_comp_num")) ?>" autocomplete="off" aria-required="true" class="form-text" style="border: none;width: 260px;" readonly>
                          <!-- <button type="button" id="btnBizUpdate" class="btn-round btn-secondary" onclick="$('#dimwrap,#dimcont').show();fMemUpdateType('bizno');" style="float: right; padding: 10px 20px; margin-top: 7px;margin-left: 10px;">변경</button> -->
                          <!-- <button type="button" id="btnBizUpdate" class="btn-round btn-secondary" onclick="$('#dimwrap,#dimcont').show();fMemUpdateType('bizno');" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: 7px;">인증</button> -->
                        </label>
                      </td>
                    </tr>
                    <!-- <tr>
                      <td style="width:170px; font-size: 18px; font-weight: 600; color: #777;">사업자 주소 :</td>
                      <td>
                        <label class="col">
                          <span class="sr-text">사업자 주소</span>
                          <input type="text" name="bizCode" placeholder="사업자주소를 입력해 주세요" value="<?= getData($info, "mm_doro") ?>" autocomplete="off" aria-required="true" class="form-text" style="border: none; width: 340px;" readonly>
                        </label>
                      </td>
                    </tr> -->
                    <tr style="display:none;">
                      <td style="width:170px; font-size: 18px; font-weight: 600; color: #777;">이 메 일 :</td>
                      <td>
                        <label>
                          <span class="sr-text">이메일입력</span>
                          <input type="email" name="bizEmail" placeholder="이메일을 입력해 주세요" value="<?= fEmailMasking(getData($info, "mm_email")) ?>" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                        </label>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

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
                    <!-- <span style="font-size: 0.9rem; color: #777; margin-left: 10px;">미인증</span> -->
                  <?php } else { ?>
                    <!-- <span style="font-size: 0.9rem; color: #57CCAF; margin-left: 10px;">인증완료</span> -->
                  <?php } ?>
                </p>
              </div>
              <div class="value">
              </div>
            </div>
            <div class="form-row toggle-form">
              <div class="value">
                <?php
                // SNS 로그인 여부 확인
                $is_sns_login = !empty($info["mm_join_type"]) && in_array(strtoupper($info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER"]);
                // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
                $is_authenticated = ($info != null && $info["mm_di"] != null && (strpos($info["mm_di"], "MC0") === 0 || $is_sns_login));

                if (!$is_authenticated) {
                ?>
                  <!-- <button type="button" id="btnInfoUpdate" onclick="$('#dimwrap,#dimcont2').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: -60px;">인증</button> -->
                <?php } else { ?>
                  <!-- <button type="button" id="btnInfoUpdate" onclick="$('#dimwrap,#dimcont2').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: -60px;">변경</button> -->
                <?php } ?>
                <table>
                  <tbody>
                    <tr>
                      <td style=" font-size: 18px; font-weight: 600; color: #777;">가입자명</td>
                      <td>
                        <label>
                          <span class="sr-text">가입자명 입력</span>
                          <input type="text" name="bizName" placeholder="가입자명을 입력해 주세요" value="<?= ((strpos(getData($info, "mm_name"), '010') !== 0 && strpos(getData($info, "mm_name"), 'kakao') !== 0 && strpos(getData($info, "mm_name"), 'apple') !== 0) ? getData($info, "mm_name") : "guest") ?>" aria-required="true" class="form-text" style="border: none;" readonly>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td style=" font-size: 18px; font-weight: 600; color: #777;">연 락 처</td>
                      <td>
                        <label>
                          <span class="sr-text">연락처입력</span>
                          <input type="text" name="bizPhoneView" placeholder="연락처를 입력해 주세요" value="<?= fPhoneMasking(getData($info, "mm_phone")) ?>" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                          <input type="hidden" name="bizPhone" placeholder="연락처를 입력해 주세요" value="<?= getData($info, "mm_phone") ?>" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" autocomplete="off" aria-required="true" class="form-text" style="border: none;" readonly>
                        </label>
                      </td>
                    </tr>
                    
                    <!-- <tr>
                      <td style=" font-size: 20px; font-weight: 700;"></td>
                      <td>
                        <label class="col" style="float: right; text-align: right; color: #aaa; margin-top: 30px;"> -->
                    <!-- PASS 인증과 사업자 인증을 해야 공고 등록이 가능합니다. -->
                    <!--안전한 정보보호를 위해 연락처 일부만 확인 가능하며,<br>수정화면에서 정확한 연락처 확인이 가능합니다.-->
                    <!-- </label>
                      </td>
                    </tr> -->
                  </tbody>
                </table>
              </div>
            </div>
            <!-- //세금계산서 추가 폼 -->
            <div class="form-row" style="border-top:1px solid #ccc;">
              <div class="form-label" style="font-size: 22px;">
                <p>비밀번호</p>
              </div>
              <div class="value" style="margin-top:10px;">
                <label>
                  <span class="sr-text">******</span>
                  <input type="text" name="bd_pass" value="******" class="form-text" readonly style="border: none;">
                </label>
                <?php
                // SNS 로그인 여부 확인 (카카오, 네이버, 애플)
                $is_sns_login_pw = !empty($info["mm_join_type"]) && in_array(strtoupper($info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER"]);
                $btn_disabled = $is_sns_login_pw ? 'disabled' : '';
                $btn_style = $is_sns_login_pw ? 'float: right; background-color: #ccc; padding: 10px 20px; margin-top: 0px; position: absolute; right: 0; cursor: not-allowed;' : 'float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: 0px; position: absolute; right: 0;';
                ?>
                <button type="button" id="btnPWUpdate" onclick="<?= $is_sns_login_pw ? '' : "$('#dimwrap,#dimcont3').show();" ?>" class="btn-round btn-secondary" style="<?= $btn_style ?>" <?= $btn_disabled ?>>변경</button>
              </div>
            </div>
            <div class="form-row" style="margin-top:10px;">
              <div class="form-label" style="font-size: 22px;">
              </div>
              <div class="value">
                <label style="float: right; text-align: right; color: #aaa; margin-top: 0px;">
                  <span style="color: rgb(248, 96, 72); ">
                    <span style="font-size: 18px; border: 1px solid rgb(248, 96, 72); width: 22px; height: 22px; text-align: center; line-height: 22px; border-radius: 16px; display: inline-block; font-weight: 600;">！</span>
                    비밀번호는 6~12자의 영문,숫자,최소한 하나의 특수문자(@,$,!,%,*,?,&)를 포함해야 합니다.</span><br>
                  비밀번호를 주기적으로 변경하여 소중한 개인정보를 안전하게 보호하세요.
                </label>
              </div>
            </div>
            <div class="form-row" style="margin-top:100px; border-top:1px solid #ccc;">
              <div class="form-label" style="font-size: 22px;">
                <p>회원탈퇴</p>
              </div>
              <div class="value">
                <button type="button" id="btnRetire" onclick="$('#dimwrap,#dimcont4').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: 20px;">탈퇴</button>
              </div>
            </div>

            <div class="form-action-wrap">
              <!-- <button type="button" id="btnCancel" class="btn-round btn-secondary">취소</button> -->
              <button type="button" id="btnSaveAll" class="btn-round btn-tertiary"><?= getProcMode($info) == "insert" ? "작성완료" : "저장" ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->
<?php /* 기존 개별 팝업 마크업 (전역 파셜로 대체됨)
<div id="dimwrap" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; background-color: #000; opacity: 0.3;"></div>
<div id="dimcont" style="display:none; position: fixed; top: 20%; left: calc(50% - 250px); width: 500px; height: 550px; z-index: 10; background-color: #fff; padding: 40px;">
  <form id="frm1" name="frm1" onsubmit="return false">
    <input type="hidden" id="compUpdateType" name="compUpdateType">
    <div>
      <div style="font-size: 1.4em; font-weight: 600;">기업정보 수정</div>
      <a href="javascript:$('#dimwrap,#dimcont').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
    </div>
    <div style="font-size: 1.2em;">
      <div class="biznobox" style="margin-top: 26px;">사업자 등록번호</div>
      <div class="biznobox">
        <input type="text" name="comp_num" id="bizno" placeholder="사업자 등록번호를 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: calc(100% - 70px);">
        <input type="hidden" id="biznoauth">
        <input type="hidden" name="comp_type" id="comp_type" value="COMP">
        <button onclick="fBizAuth();" style="margin-left: 15px; font-size: 16px; padding: 5px 10px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">인증</button>
      </div>
      <div class="compnamebox" style="display:none;margin-top: 30px;">사업자명<!-- ( <input type="checkbox" id="outyn" style="margin-left:10px;"> <span>아웃소싱 확인</span>&nbsp;&nbsp;&nbsp; ) --></div>
      <div class="compnamebox" style="display:none;">
        <input type="text" name="comp_name" placeholder="사업자명을 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
        <input type="hidden" name="mm_comp_type">
      </div>
      <!-- <div class="addrbox" style="margin-top: 30px;">사업자주소</div>
      <div class="addrbox">
        <input type="text" id="mm_addr" name="mm_addr" placeholder="사업자 주소를 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;" onclick="$('#changeArea').addClass('on');">
        <input type="hidden" id="mm_zipcode" name="mm_zipcode">
        <input type="hidden" id="mm_doro" name="mm_doro">
        <input type="hidden" id="mm_jibun" name="mm_jibun">
      </div> -->
      <!-- 인증 완료 후 표시되던 이메일 입력 및 아웃소싱 가이드 영역 - 주석처리 -->
      <!--
      <div class="emailbox" style="display:none;margin-top: 26px;"><span style="color:red;">*</span>이메일주소</div>
      <div class="emailbox" style="display:none;">
        <input type="text" name="mm_email_id" id="mm_email_id" placeholder="이메일 입력" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 30%;">
        <span>@</span>
        <input type="text" name="mm_email_domain" id="mm_email_domain" placeholder="" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 25%;">
        <select id="mm_email_domain_sel" style="width: 38%; padding: 5px; border: 1px solid #ccc; color:#ccc;" onchange="$('#mm_email_domain').val($(this).val());">
          <option value="">직접입력</option>
          <option value="naver.com">naver.com</option>
          <option value="hanmail.net">hanmail.net</option>
          <option value="daum.net">daum.net</option>
          <option value="nate.com">nate.com</option>
          <option value="gmail.com">gmail.com</option>
          <option value="hotmail.com">hotmail.com</option>
          <option value="lycos.com.kr">lycos.com.kr</option>
          <option value="empal.com">empal.com</option>
          <option value="cyworld.com">cyworld.com</option>
          <option value="yahoo.com">yahoo.com</option>
          <option value="paran.com">paran.com</option>
          <option value="dreamwiz.com">dreamwiz.com</option>
        </select>
        <input type="hidden" name="mm_email" id="mm_email" placeholder="이메일을 입력해 주세요" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
      </div>
      <div class="outguide" style="margin-top: 30px;display:none;">
        <span>아웃소싱업체일 경우 확인을 클릭해 주세요.</span>
        <button onclick="$('.outguide').hide();$('.bizimgbox').show();$('#dimcont').css('height','600px'); $('#comp_type').val('OUT');" style="float: right; padding: 5px 9px; font-size: 16px; color: #fff; background-color: #57CCAF; border: 1px solid #57CCAF; margin-top: -6px;">확인</button>
        <span style="display:none;">
          <input type="checkbox" id="outyn" style="margin-left:10px;"> <span>아웃소싱 확인</span>&nbsp;&nbsp;&nbsp; )
        </span>
      </div>
      -->
      <div class="bizimgbox" style="display:none;margin-top: 26px;">사업자 등록증 업로드</div>
      <div class="bizimgbox" style="display:none;">
        <label for="file-upload" style="float:right; margin-left: 15px; font-size: 16px; padding: 5px 10px;background-color: #57CCAF; color: #fff; border: 1px solid #fff; cursor: pointer;">첨부</label>
        <span id="file-name" style="font-size: 15px; color: #aaa; font-weight: 100; border-bottom: 1px solid #ccc; width: calc(100% - 70px); display: inline-block; margin-top: 8px; line-height: 27px;">&nbsp;</span>
        <input type="file" name="mm_comp_auth" id="file-upload" style="display: none;" onchange="updateFileName(this)">
        <script>
          function updateFileName(input) {
            var fileName = input.files[0].name;
            document.getElementById('file-name').innerText = fileName;
          }
        </script>
      </div>
      <!--ⓘ 나우잡 관리자가 승인한 뒤에 아웃소싱 업체로 사용 가능합니다.-->
      <!-- <div class="sousinghelp" style="display:none;font-size: 16px; margin-top: 10px; color: #57CCAF;">
        아웃소싱 업체는 하나의 사업자등록번호로 여러명이 가입이 가능합니다.<br>
        나우잡 관리자가 승인한 뒤에 아웃소싱 업체로 사용 가능합니다.
      </div> -->
    </div>
  </form>
  <div style="text-align: center;margin-top: 20px;">
    <button id="btnSave" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">완료</button>
  </div>
</div>

<form name="form_chk" method="post">
  <input type="hidden" name="m" value="checkplusService">
  <input type="hidden" name="EncodeData" value="<?= $enc_data ?>">
</form>
<div id="dimcont2" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 500px; height: 360px; z-index: 20; background-color: #fff; padding: 40px;">
  <form id="frm2" name="frm2" onsubmit="return false">
    <div>
      <div style="font-size: 1.4em; font-weight: 600;">가입자정보 수정</div>
      <a href="javascript:$('#dimwrap,#dimcont2').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
    </div>
    <div style="font-size: 1.2em;">
      <div style="margin-top: 26px;">연락처</div>
      <div>
        <input type="text" name="mm_phone" id="mm_phone" readonly placeholder="연락처(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: calc(100% - 130px);">
        <button onclick="fManCheck();" style="margin-left: 15px; font-size: 16px; padding: 5px 10px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">변경(본인인증)</button>
        <input type="hidden" name="mm_di" id="mm_di">
      </div>
      <div style="margin-top: 30px;">가입자명</div>
      <div>
        <input type="text" name="mm_name" readonly placeholder="가입자명(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
      </div>
    </div>
  </form>
  <div style="text-align: center;margin-top: 20px;">
    <button id="btnSave2" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">완료</button>
  </div>
</div>
<div id="dimcont3" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 500px; height: 500px; z-index: 20; background-color: #fff; padding: 40px;">
  <form id="frm3" name="frm3" onsubmit="return false">
    <div>
      <div style="font-size: 1.4em; font-weight: 600;">비밀번호 변경</div>
      <a href="javascript:$('#dimwrap,#dimcont3').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
    </div>
    <div style="font-size: 1.2em;">
      <div style="margin-top: 30px;">기존 비밀번호</div>
      <div style="position:relative;">
        <input type="password" name="mm_pass_old" id="mm_pass_old" placeholder="현재 비밀번호 입력" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
        <span class="toggle-password" id="toggle-password0" style="position:absolute;right:0;top:3px;">
          <img id="eye_icon0" src="/resource/shop/web/img/eye_close.png" style="margin-top:15px; margin-right: 10px; width: 24px;">
        </span>
      </div>
      <div style="margin-top: 26px;">새 비밀번호</div>
      <div style="position:relative;">
        <input type="password" name="mm_pass" id="mm_pass" placeholder="새 비밀번호 입력" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
        <span class="toggle-password" id="toggle-password1" style="position:absolute;right:0;top:3px;">
          <img id="eye_icon1" src="/resource/shop/web/img/eye_close.png" style="margin-top:15px; margin-right: 10px; width: 24px;">
        </span>
      </div>
      <div style="font-size: 16px; margin-top: 10px; color: #F86048;">비밀번호는 8~20자의 영문,숫자,최소한 하나의 특수문자(@,$,!,%,*,?,&)를 포함해야 합니다.</div>
      <div style="margin-top: 26px;">새 비밀번호 확인</div>
      <div style="position:relative;">
        <input type="password" name="mm_pass2" id="mm_pass2" placeholder="새 비밀번호 확인" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
        <span class="toggle-password" id="toggle-password2" style="position:absolute;right:0;top:3px;">
          <img id="eye_icon2" src="/resource/shop/web/img/eye_close.png" style="margin-top:15px; margin-right: 10px; width: 24px;">
        </span>
      </div>
    </div>
  </form>
  <div style="text-align: center;margin-top: 20px;">
    <button id="btnSave3" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">변경완료</button>
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
        1. 탈퇴한 전화번호는 추후 재가입시 15일간 가입이 제한 됩니다. <br>
        2. 채용정보, 인재정보, 중고거래, 유료서비스, 포인트 모두 삭제되며, <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;삭제된 데이터는 복구 되지 않습니다. <br>
        3. 회원탈퇴 관련 고객응대를 목적으로 1년간 탈퇴 신청자, 이메일 <br>
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

<div id="thumbImgPop" style="display:none; position: fixed; top: 33%; left: calc(50% - 140px); width: 280px; height: 216px; z-index: 20; background-color: #fff; padding: 0px; overflow: hidden;">
  <img src="<?= getData($info, "mm_thumb") ?>" style="width: 100%; ">
  <a href="javascript:$('#dimwrap,#thumbImgPop').hide();" style="position: absolute; right: 10px; font-size: 32px; top: 10px; background-color:#fff; padding: 0px 10px 2px 10px; border-radius: 20px;">×</a>
  <button style="position: absolute; top: 180px; left: 0; width: 50%; height: 36px; border: 0; background-color: #ccc; color: #fff;" onclick="fThumbDel();">삭제</button>
  <button style="position: absolute; top: 180px; right: 0; width: 50%; height: 36px; border: 0; background-color: #38b898; color: #fff;" onclick="$('#mm_thumb').trigger('click');">변경</button>
</div>

<div id="dimcont7" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 380px; height: 625px; z-index: 20; background-color: #fff; padding: 40px;">
  <div>
    <div style="font-size: 1.4em; font-weight: 600;">기업로고 변경</div>
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
        margin: 20px 0;
      }

      .cropper-crop-box {
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
  $(document).ready(function() {
    const $previewImage = $('#previewImage');
    const $newInput = $('#newLogo');
    const $resultCanvas = $('#companyLogo');
    const $cancel = $('#profileCancel');
    const $crop = $('#profileCrop');
    let cropper;

    $newInput.on('click', function() {
      this.value = null;
    });

    $newInput.on('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;
      $('#dimwrap, #dimcont7').show();

      newProfileImageSelected = true;

      const url = URL.createObjectURL(file);
      $previewImage.attr('src', url);

      $previewImage.on('load', function() {
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

    $cancel.on('click', function() {
      profileBlob = null;
      newProfileImageSelected = false;
      $('#dimwrap, #dimcont7').hide();
    });

    $crop.on('click', function() {
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

      profileCanvas.toBlob(function(blob) {
        profileBlob = blob;
      }, 'image/png');
      $('#dimwrap, #dimcont7').hide();
    });
  });


  function fManCheck() {
    if ($("[name=EncodeData]").val() == "") {
      alert("본인인증 모듈생성에 문제가 있습니다. 관리자에 문의해주세요.");
      return;
    }

    var form = $('#form_chk');
    var params = new FormData(form[0]);
    fileAjax("/member_proc/com_man_update", params, function(ret) {
      console.log(ret);
      if (ret.code == "00") {

        window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
        document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
        document.form_chk.target = "popupChk";
        document.form_chk.submit();

      } else {
        commonAlert(ret.msg, function() {});
      }
    });

  }

  function fRetire() {
    var form = $('#form_chk');
    var params = new FormData(form[0]);
    fileAjax("/compage_proc/com_biz_retire", params, function(ret) {
      //alert(ret);
      console.log(ret);
      if (ret.code == "00") {
        $('#dimcont4').hide();
        $('#dimcont5').show();
        //commonAlert("탈퇴 되었습니다.",function(){ });
      } else {
        commonAlert(ret.msg, function() {});
      }
    });
  }

  $(document).ready(function() {
    $("#btnCancel").click(function() {
      history.back();
    });

    $("#btnSaveAll").click(function() {

      var form = $('#frmAll');
      var params = new FormData(form[0]);

      if (newProfileImageSelected && !profileBlob) {
        alert('새로운 기업로고를 확인해주세요.');
        return;
      }
      if (profileBlob) {
        params.append('mm_thumb', profileBlob, 'newlogo.png');
      }

      fileAjax("/compage_proc/com_biz_updateAll", params, function(ret) {
        if (ret.code == "00") {
          commonAlert("수정 되었습니다.", function() {
            location.reload();
          });
        } else {
          commonAlert(ret.msg, function() {});
        }
      });
    });

    $("#btnSave").click(function() {

      if (($("[name=compUpdateType]").val() == "bizno" || $("[name=compUpdateType]").val() == "all") && $("[name=comp_num]").val() == "") {
        commonAlert("사업자등록번호를 입력해주세요.", function() {
          $("[name=comp_num]").focus();
        });
        return;
      }
      if (($("[name=compUpdateType]").val() == "bizno" || $("[name=compUpdateType]").val() == "all") && $("[name=comp_num]").val() !== $("#biznoauth").val()) {
        commonAlert("사업자등록번호를 인증해주세요.", function() {
          $("[name=comp_num]").focus();
        });
        return;
      }

      if (($("[name=compUpdateType]").val() == "compname" || $("[name=compUpdateType]").val() == "all") && $("[name=comp_name]").val() == "") {
        commonAlert("사업자명을 선택해주세요.", function() {
          $("[name=comp_name]").focus();
        });
        return;
      }

      // 이메일 입력 필드가 표시되어 있을 때만 검증
      if ($('.emailbox').is(':visible')) {
        if ($("[name=mm_email_id]").val() == "") {
          commonAlert("이메일을 입력 해주세요.", function() {
            $("[name=mm_email_id]").focus();
          });
          return;
        }

        if ($("[name=mm_email_domain]").val() == "") {
          commonAlert("이메일을 입력 해주세요.", function() {
            $("[name=mm_email_domain]").focus();
          });
          return;
        }
        $("[name=mm_email]").val($("[name=mm_email_id]").val() + '@' + $("[name=mm_email_domain]").val());
      }

      // if($("[name=compUpdateType]").val()=="all" && $("[name=mm_comp_auth]").val()==""){
      // 	commonAlert("사업자등록증을 첨부 해주세요.",function(){
      //             $("[name=mm_comp_auth]").focus();
      //         });
      //         return;
      // }

      if ($('.addrbox').is(':visible') && $("[name=mm_addr]").val() == "") {
        commonAlert("사업자 주소를 입력해주세요.", function() {
          $("[name=mm_addr]").focus();
        });
        return;
      }

      //alert($('input[name="comp_type"]').val());
      //alert($("#file-name").html());

      if ($('input[name="comp_type"]').val() == 'OUT') {
        if ($("#file-name").html() == "&nbsp;") {
          commonAlert("사업자 등록증을 첨부해주세요.", function() {
            $("[name=mm_comp_auth]").focus();
          });
          return;
        }
      }

      // if($('.addrbox').is(':visible') && $("[name=mm_addr]").val()==""){
      // 	commonAlert("사업장 주소를 입력해주세요.",function(){
      //         $("[name=mm_addr]").focus();
      //   });
      //   return;
      // }

      // compUpdateType이 'all'일 때는 주소가 있어도 'all'로 유지하여 모든 정보 저장
      if ($('.addrbox').is(':visible') && $("[name=compUpdateType]").val() != "all") {
        $("[name=compUpdateType]").val('addr');
      }

      //아웃소싱이면
      //mm_comp_type = OUT
      //outyn
      if ($('.bizimgbox').is(':visible')) { //$('#outyn').is(':checked')
        $('input[name="comp_type"]').val('OUT');
      } else {
        $('input[name="comp_type"]').val('COMP');
      }

      var form = $('#frm1');
      var params = new FormData(form[0]);
      //console.log(params);
      //alert('submit');
      fileAjax("/compage_proc/com_biz_update", params, function(ret) {
        //alert(ret);
        //console.log(ret);
        if (ret.code == "00") {
          commonAlert("수정 되었습니다.", function() {
            //location.reload();
            location.replace('/compage/mypage');
          });
        } else {
          commonAlert(ret.msg, function() {});
        }
      });
    });


    $("#btnSave2").click(function() {

      if ($("[name=mm_name]").val() == "") {
        commonAlert("가입자명을 입력해주세요.", function() {
          $("[name=mm_name]").focus();
        });
        return;
      }

      if ($("[name=mm_phone]").val() == "") {
        commonAlert("연락처를 입력해주세요.", function() {
          $("[name=mm_phone]").focus();
        });
        return;
      }


      var form = $('#frm2');
      var params = new FormData(form[0]);
      console.log(params);
      //alert('submit');
      fileAjax("/compage_proc/com_biz_update2", params, function(ret) {
        //alert(ret);
        console.log(ret);
        if (ret.code == "00") {
          commonAlert("수정 되었습니다.", function() {
            //location.reload();
            location.replace('/compage/mypage');
          });
        } else if (ret.code == "01") {
          commonAlert(ret.msg, function() {
            location.href = "/member/logout";
          });
        } else {
          commonAlert(ret.msg, function() {});
        }
      });
    }); //btnSave2

    $("#btnSave3").click(function() {

      if ($("[name=mm_pass_old]").val() == "") {
        commonAlert("기존 비밀번호을 입력해주세요.", function() {
          $("[name=mm_pass_old]").focus();
        });
        return;
      }

      if ($("[name=mm_pass]").val() == "") {
        commonAlert("새 비밀번호를 입력해주세요.", function() {
          $("[name=mm_pass]").focus();
        });
        return;
      }

      if (fPassValid() === false) {
        commonAlert("비밀번호 규칙에 맞지 않습니다.<br>비밀번호는 8~20자의 영문,숫자,최소한 하나의 특수문자(@,$,!,%,*,?,&)를 포함해야 합니다.", function() {
          $("[name=mm_pass]").focus();
        });
        return;
      }

      if ($("[name=mm_pass2]").val() == "") {
        commonAlert("새 비밀번호 확인을 입력 해주세요.", function() {
          $("[name=mm_pass2]").focus();
        });
        return;
      }

      if ($("[name=mm_pass]").val() != $("[name=mm_pass2]").val()) {
        commonAlert("새 비밀번호 확인이 일치하지 않습니다.", function() {
          $("[name=mm_pass2]").focus();
        });
        return;
      }


      var form = $('#frm3');
      var params = new FormData(form[0]);
      console.log(params);
      //alert('submit');
      fileAjax("/compage_proc/com_biz_update3", params, function(ret) {
        //alert(ret);
        console.log(ret);
        if (ret.code == "00") {
          commonAlert("변경 되었습니다.", function() {
            location.reload();
          });
        } else {
          commonAlert(ret.msg, function() {});
        }
      });
    }); //btnSave3

    $("[name=mm_pass]").on("input", function() {
      var password = $(this).val();
      var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

      if (regex.test(password)) {
        console.log("비밀번호 조건에 만족합니다.");
        // 조건에 만족할 경우의 추가적인 동작을 여기에 구현합니다.
      } else {
        console.log("비밀번호는 8~20자의 영문, 숫자, 최소한 하나의 특수문자(@, $, !, %, *, ?, &)를 포함해야 합니다.");
        // 조건에 만족하지 않을 경우의 추가적인 동작을 여기에 구현합니다.
      }
    });

    $("#btnSave4").click(function() {

      if (document.getElementById('retire_agree').checked === false) {
        commonAlert("유의사항을 확인 하고 동의해주세요.", function() {
          $("[name=retire_agree]").focus();
        });
        return;
      }

      // PASS 인증 제거하고 바로 탈퇴 처리
      var params = new FormData();
      fileAjax("/compage_proc/com_biz_retire", params, function(ret) {
        if (ret.code == "00") {
          $('#dimwrap, #dimcont4').hide();
          $('#dimcont5').show();
        } else {
          commonAlert(ret.msg, function() {});
        }
      });

    }); //btnSave4

    <?php //if($info == null || strpos($info["mm_di"], "010") === 0){ 
    ?>
    //$('#btnInfoUpdate').trigger('click');
    <?php //} 
    ?>
    <?php
    // SNS 로그인 여부 확인 (mem_info 구조 확인)
    $mem_info_di = isset($info["mem_info"]["mm_di"]) ? $info["mem_info"]["mm_di"] : (isset($info["mm_di"]) ? $info["mm_di"] : null);
    $mem_info_join_type = isset($info["mem_info"]["mm_join_type"]) ? $info["mem_info"]["mm_join_type"] : (isset($info["mm_join_type"]) ? $info["mm_join_type"] : null);
    $is_sns_login = !empty($mem_info_join_type) && in_array(strtoupper($mem_info_join_type), ["KAKAO", "APPLE", "NAVER"]);
    // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
    $is_authenticated = ($mem_info_di != null && (strpos($mem_info_di, "MC0") === 0 || $is_sns_login));
    ?>
    <?php if ($reqgbn == "compcert") { ?>
      $('#btnBizUpdate').trigger('click');
    <?php } else if ($reqgbn == "pass") { ?>
      $('#btnInfoUpdate').trigger('click');
    <?php } else if (isset($info["mem_info"]["mm_comp_num"]) && $info["mem_info"]["mm_comp_num"] == "") { ?>
      //$('#btnBizUpdate').trigger('click');
    <?php } else if (!$is_authenticated) { ?>
      $('#btnInfoUpdate').trigger('click');
    <?php } ?>

    $('#toggle-password0').click(function() {
      // Get the current type of the password input
      const input = $('#mm_pass_old');
      const type = input.attr('type') === 'password' ? 'text' : 'password';

      input.attr('type', type);

      if (type === 'password') {
        $('#eye_icon0').attr('src', '/resource/shop/web/img/eye_close.png');
        $('#eye_icon0').css('margin-top', '15px');
        $('#eye_icon0').css('margin-right', '10px');
      } else {
        $('#eye_icon0').attr('src', '/resource/shop/web/img/eye_view.png');
        $('#eye_icon0').css('margin-top', '17px');
        $('#eye_icon0').css('margin-right', '10px');
      }

    });

    $('#toggle-password1').click(function() {
      // Get the current type of the password input
      const input = $('#mm_pass');
      const type = input.attr('type') === 'password' ? 'text' : 'password';

      input.attr('type', type);

      if (type === 'password') {
        $('#eye_icon1').attr('src', '/resource/shop/web/img/eye_close.png');
        $('#eye_icon1').css('margin-top', '15px');
        $('#eye_icon1').css('margin-right', '10px');
      } else {
        $('#eye_icon1').attr('src', '/resource/shop/web/img/eye_view.png');
        $('#eye_icon1').css('margin-top', '17px');
        $('#eye_icon1').css('margin-right', '10px');
      }

    });

    $('#toggle-password2').click(function() {
      // Get the current type of the password input
      const input = $('#mm_pass2');
      const type = input.attr('type') === 'password' ? 'text' : 'password';

      input.attr('type', type);

      if (type === 'password') {
        $('#eye_icon2').attr('src', '/resource/shop/web/img/eye_close.png');
        $('#eye_icon2').css('margin-top', '15px');
        $('#eye_icon2').css('margin-right', '10px');
      } else {
        $('#eye_icon2').attr('src', '/resource/shop/web/img/eye_view.png');
        $('#eye_icon2').css('margin-top', '17px');
        $('#eye_icon2').css('margin-right', '10px');
      }

    });

  }); //ready

  function fPassValid() {
    var password = $("[name=mm_pass]").val();
    var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&#]{8,20}$/;

    if (regex.test(password)) {
      console.log("비밀번호 조건에 만족합니다.");
      // 조건에 만족할 경우의 추가적인 동작을 여기에 구현합니다.
      return true;
    } else {
      console.log("비밀번호는 8~20자의 영문, 숫자, 최소한 하나의 특수문자(@, $, !, %, *, ?, &, #)를 포함해야 합니다.");
      // 조건에 만족하지 않을 경우의 추가적인 동작을 여기에 구현합니다.
      return false;
    }

  }

  function fBizAuth() {

    if ($('#bizno').val() == '') {
      commonAlert("사업자 등록번호를 입력해주세요.", function() {
        $("#bizno").focus();
      });
      return;
    }

    var data = {
      "b_no": [$("#bizno").val()] // 사업자번호 "xxxxxxx" 로 조회 시,
    };
    $.ajax({
      url: "https://api.odcloud.kr/api/nts-businessman/v1/status?serviceKey=nbqW%2FFUs8GPs9gugbRl6wK2fqyEhU%2F4kDV1UzIEmA%2BgNYC9C1IyNsLwER%2FR5ALL5p20DpErEsQ2QlWm38oD%2FGA%3D%3D", // serviceKey 값을 xxxxxx에 입력
      type: "POST",
      data: JSON.stringify(data), // json 을 string으로 변환하여 전송
      dataType: "JSON",
      contentType: "application/json",
      accept: "application/json",
      success: function(result) {
        console.log(result);
        if (result.status_code == "OK") {
          if (result.data[0].b_stt_cd == "01" || result.data[0].b_stt_cd == "01") {
            bus_yn = true;
            //$("#msg" + type).show();
            //$("#msg" + type).html("사업자번호 인증완료");
            $("#biznoauth").val($("#bizno").val());
            commonAlert("사업자번호 인증완료", function() {});
            // $('.emailbox').show(); // 주석처리됨
            // $('.outguide').show(); // 주석처리됨
            // $('.sousinghelp').show(); // 인증 시 숨겨진 HTML 표시 이벤트 삭제
            //$('.addrbox').show();
            // $('#dimcont').css('height', '570px'); // 인증 시 높이 변경 이벤트 삭제
            return;
          } else if (result.data[0].b_stt_cd == "03") {
            bus_yn = false;
            //$("#msg" + type).show();
            //$("#msg" + type).html("폐업된 사업자 번호입니다.");
            commonAlert("폐업된 사업자 번호입니다.", function() {
              $("#bizno").focus();
            });
            return;
          } else {
            bus_yn = false;
            //$("#msg" + type).show();
            //$("#msg" + type).html("등록되지 않은 사업자 번호입니다.");
            commonAlert("등록되지 않은 사업자 번호입니다.", function() {
              $("#bizno").focus();
            });
            return;
          }
        } else {
          bus_yn = false;
          commonAlert("사업자 번호가 유효 하지 않습니다.", function() {});
        }
      },
      error: function(result) {
        bus_yn = true;
      }
    });

  }

  function fMemUpdateType(type) {
    //alert(type);
    if (type == 'compname') {
      $('.compnamebox').show();
      $('.biznobox').hide();
      $('.bizimgbox').hide();
      $('.sousinghelp').hide();
      $('.addrbox').hide();
      $('#dimcont').css('height', '270px');
      $('#dimcont').css('top', '30%');
    } else if (type == 'bizno') {
      $('.compnamebox').hide();
      $('.biznobox').show();
      $('.bizimgbox').hide();
      $('.sousinghelp').hide();
      $('.addrbox').hide();
      $('#dimcont').css('height', '270px');
      $('#dimcont').css('top', '30%');
    } else if (type == 'address') {
      $('.compnamebox').hide();
      $('.biznobox').hide();
      $('.bizimgbox').hide();
      $('.sousinghelp').hide();
      $('.addrbox').show();
      $('#dimcont').css('height', '270px');
      $('#dimcont').css('top', '30%');
    } else {
      $('.compnamebox').show();
      $('.biznobox').show();
      //$('.bizimgbox').show();
      //$('.sousinghelp').show();
      $('.addrbox').show(); // 주소 입력 박스 표시
      $('#dimcont').css('height', '350px'); // 주소 입력 필드 추가로 높이 증가
      $('#dimcont').css('top', '20%');
      $('input[name="comp_type"]').val('');
    }
    $('#compUpdateType').val(type);
  }
  // 검색 완료 선택 처리
  function selectAddrInfo(zipcode, doro, jibun, addr, x, y) {
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

  function fThumbDel() {
    openPopup('confirm', {
      content: "삭제 하시겠습니까?",
      firstButton: '취소',
      secondButton: '확인',
      firstCallback: function() {},
      secondCallback: function() {
        $('input[name=mm_thumb_del]').val('Y');
        $('#btnSaveAll').trigger('click');
      },
    });

  }
</script>
<?php include_once(get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>
*/ ?>