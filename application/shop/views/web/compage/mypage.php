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
            <!-- proc_mode는 현재 처리 모드(예: 등록/수정 등)를 서버에 전달하여, 회원정보 처리방식을 구분하는 hidden input입니다. -->
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

                          /* 기업로고 이미지가 있을 때 3:1 비율로 표시 */
                          .thumb-wrap.compinfo .form-pic.has-logo {
                            width: 300px;
                            height: 87px;
                          }

                          .thumb-wrap.compinfo .form-pic.has-logo .preview {
                            position: relative;
                            width: 100%;
                            height: 100%;
                          }

                          .thumb-wrap.compinfo .form-pic.has-logo .preview img {
                            display: block;
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background-color: inherit;
                            
                            z-index: 1;
                          }
                        </style>
                        <div class="thumb-wrap compinfo">
                          <label class="form-pic" style="max-width:200px; max-height: 200px;">
                            <input type="file" id="newLogo" name="mm_thumb" accept="image/*">
                            <input type='hidden' name='mm_thumb_del' value='' />
                            <span class="preview">
                              <img id="companyLogo" src="<?= getData($info, "mm_thumb") ?>" alt="" onload="$(this).closest('.form-pic').addClass('has-logo');" onError="this.src='/resource/shop/mobile/img/common/noimage4.png'">
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


<!-- 기업로고 크롭 팝업 (주석 처리된 부분에서 활성화) -->
<div id="dimwrap" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; background-color: #000; opacity: 0.3;"></div>
<div id="dimcont7" style="display:none; position: fixed; top: 30%; left: calc(50% - 450px); width: 900px; height: 600px; z-index: 20; background-color: #fff; padding: 40px;">
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
        width: 800px;
        height: 400px;
        margin: 20px auto;
        display: block;
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
  // 파일 업로드 및 크롭 관련 변수
  let profileBlob = null;
  let newProfileImageSelected = false;

  $(document).ready(function() {
    const $previewImage = $('#previewImage');
    const $newInput = $('#newLogo');
    const $resultCanvas = $('#companyLogo');
    const $cancel = $('#profileCancel');
    const $crop = $('#profileCrop');
    let cropper;

    // 파일 입력 클릭 시 초기화
    $newInput.on('click', function() {
      this.value = null;
    });

    // 파일 선택 시 크롭 팝업 표시
    $newInput.on('change', function(e) {
      const file = e.target.files[0]; // file은 'input[type="file"]'에서 사용자가 선택한 첫 번째 파일(이미지 파일 등)을 가리킴
      if (!file) return;
      
      // 파일 타입 검증
      if (!file.type.match('image.*')) {
        alert('이미지 파일만 업로드 가능합니다.');
        return;
      }

      $('#dimwrap, #dimcont7').show();
      newProfileImageSelected = true;

      const url = URL.createObjectURL(file);
      $previewImage.attr('src', url);

      $previewImage.on('load', function() {
        if (cropper) cropper.destroy();
        cropper = new Cropper(this, {
          aspectRatio: 3,
          viewMode: 1,
          background: false,
          dragMode: 'move',
          guides: true,
          highlight: false,
          cropBoxMovable: true,
          cropBoxResizable: true,
          autoCropArea: 1,
          minContainerWidth: 800,
          minContainerHeight: 400,
        });
      });
    });

    // 취소 버튼 클릭
    $cancel.on('click', function() {
      profileBlob = null;
      newProfileImageSelected = false;
      if (cropper) cropper.destroy();
      $('#dimwrap, #dimcont7').hide();
    });

    // 크롭 완료 버튼 클릭
    $crop.on('click', function() {
      if (!cropper) return;

      const profileCanvas = cropper.getCroppedCanvas({
        width: 900,
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
      
      if (cropper) cropper.destroy();
      $('#dimwrap, #dimcont7').hide();
    });

    // 저장 버튼 클릭 이벤트 (기존 이벤트가 있으면 덮어쓰기)
    $("#btnSaveAll").off('click').on('click', function() {
      var form = $('#frmAll');
      var params = new FormData(form[0]);

      // 새로운 이미지가 선택되었지만 크롭이 완료되지 않은 경우
      if (newProfileImageSelected && !profileBlob) {
        alert('새로운 기업로고를 확인해주세요.');
        return;
      }
      
      // 크롭된 이미지가 있으면 추가
      if (profileBlob) {
        params.append('mm_thumb', profileBlob, 'newlogo.png');
      }

      // 이 함수는 "/compage_proc/com_biz_updateAll"로 폼 데이터를 비동기로 전송하고, 완료 시 콜백함수를 실행합니다.
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
  });

  // 기업로고 삭제 함수
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
