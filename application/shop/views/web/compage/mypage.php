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
                          <label class="form-pic" style="max-width:265px; max-height: 200px; margin-right: 20px;">
                            <input type="file" id="newLogo" accept="image/*">
                            <input type='hidden' name='mm_thumb_del' value='' />
                            <span class="preview">
                              <img id="companyLogo" src="<?= getData($info, "mm_thumb") ?>" alt="회사 로고" onload="$(this).closest('.form-pic').addClass('has-logo');" onError="this.src='/resource/shop/mobile/img/common/noimage4.png'">
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
                <tr>
                  <div style="margin-top: 12px;">
                            <div style="font-size: 0.9em; font-weight: 600; color: #1B9D7D; margin-bottom: 6px;">
                              기업로고 이미지 등록
                            </div>
                            <div style="font-size: 0.85em; color: #1B9D7D; line-height: 1.5;">
                              권장사이즈: 900×300 (가로×세로)<br>
                              💡 선택한 이미지는 자동으로 900×300 규격으로 조정됩니다.
                            </div>
                    </div>
                  </tr>
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
                $btn_style = $is_sns_login_pw ? 'float: right; background-color: #ccc; padding: 10px 20px; margin-top: 0px; cursor: not-allowed;' : 'float: right; background-color: #57CCAF; padding: 10px 20px; margin-top: 0px;';
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



<script>
  // 파일 업로드 관련 변수
  let profileBlob = null;

  $(document).ready(function() {
    const $newInput = $('#newLogo');
    const $resultCanvas = $('#companyLogo');

    // 파일 입력 클릭 시 초기화
    $newInput.on('click', function() {
      this.value = null;
    });

    // 파일 선택 시 바로 이미지 처리 (Cropper 팝업 없음)
    $newInput.on('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;

      // 파일 타입 검증
      if (!file.type.match('image.*')) {
        alert('이미지 파일만 업로드 가능합니다.');
        return;
      }

      // 원본 이미지를 읽어서 처리
      const reader = new FileReader();
      reader.onload = function(event) {
        const img = new Image();
        img.onload = function() {
          // 원본 이미지를 캔버스에 그리기
          const originalCanvas = document.createElement('canvas');
          originalCanvas.width = img.width;
          originalCanvas.height = img.height;
          const origCtx = originalCanvas.getContext('2d');
          origCtx.drawImage(img, 0, 0);

          // 서버 규격: 정확히 900x300으로 리사이즈
          const targetWidth = 900;
          const targetHeight = 300;

          // 최종 캔버스 생성
          const profileCanvas = document.createElement('canvas');
          profileCanvas.width = targetWidth;
          profileCanvas.height = targetHeight;
          const ctx = profileCanvas.getContext('2d');

          // 흰색 배경
          ctx.fillStyle = '#ffffff';
          ctx.fillRect(0, 0, targetWidth, targetHeight);

          // 원본 이미지 전체를 무조건 900x300에 꽉 채우기 (비율 무시, 왜곡 가능)
          // 잘림 없음 + 여백 없음 = 강제 리사이즈
          ctx.drawImage(originalCanvas, 0, 0, targetWidth, targetHeight);

          // 결과 이미지 미리보기
          const newProfile = profileCanvas.toDataURL('image/png');
          $resultCanvas.attr('src', newProfile);

          // Blob으로 변환해서 저장
          profileCanvas.toBlob(function(blob) {
            if (!blob) {
              alert('이미지 생성 실패. 다시 시도해주세요.');
              return;
            }
            profileBlob = blob;
            alert('이미지가 자동으로 900×300 규격으로 조정되었습니다.');
          }, 'image/png');
        };
        img.src = event.target.result;
      };
      reader.readAsDataURL(file);
    });

    // 저장 버튼 클릭 이벤트
    $("#btnSaveAll").off('click').on('click', function() {
      var form = $('#frmAll');
      var params = new FormData(form[0]);

      // 새로운 이미지가 있으면 추가
      if (profileBlob) {
        params.append('mm_thumb', profileBlob, 'newlogo.png');
      }

      // 폼 데이터를 서버에 전송
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

  // 비밀번호 유효성 검사 함수
  function fPassValid() {
    var password = $("[name=mm_pass]").val();
    var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;
    return regex.test(password);
  }

  // 비밀번호 변경 버튼 클릭 이벤트
  $(document).ready(function() {
    $("#btnSave3").click(function() {
      // 기존 비밀번호 확인
      if ($("[name=mm_pass_old]").val() == "") {
        commonAlert("기존 비밀번호를 입력해주세요.", function() {
          $("[name=mm_pass_old]").focus();
        });
        return;
      }

      // 새 비밀번호 확인
      if ($("[name=mm_pass]").val() == "") {
        commonAlert("새 비밀번호를 입력해주세요.", function() {
          $("[name=mm_pass]").focus();
        });
        return;
      }

      // 비밀번호 규칙 검증
      if (fPassValid() === false) {
        commonAlert("비밀번호 규칙에 맞지 않습니다.<br>비밀번호는 8~20자의 영문,숫자,최소한 하나의 특수문자(@,$,!,%,*,?,&)를 포함해야 합니다.", function() {
          $("[name=mm_pass]").focus();
        });
        return;
      }

      // 새 비밀번호 확인 입력 확인
      if ($("[name=mm_pass2]").val() == "") {
        commonAlert("새 비밀번호 확인을 입력해주세요.", function() {
          $("[name=mm_pass2]").focus();
        });
        return;
      }

      // 비밀번호 일치 확인
      if ($("[name=mm_pass]").val() != $("[name=mm_pass2]").val()) {
        commonAlert("새 비밀번호 확인이 일치하지 않습니다.", function() {
          $("[name=mm_pass2]").focus();
        });
        return;
      }

      // 서버에 전송
      var form = $('#frm3');
      var params = new FormData(form[0]);
      fileAjax("/compage_proc/com_biz_update3", params, function(ret) {
        if (ret.code == "00") {
          commonAlert("수정 되었습니다.", function() {
            location.reload();
          });
        } else {
          commonAlert(ret.msg, function() {});
        }
      });
    });

    // 비밀번호 입력 시 실시간 규칙 검증
    $("[name=mm_pass]").on("input", function() {
      var password = $(this).val();
      var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

      if (regex.test(password)) {
        $(this).css("border-bottom-color", "#57CCAF");
      } else {
        $(this).css("border-bottom-color", "#F86048");
      }
    });

    // 회원탈퇴 버튼 클릭 이벤트
    $("#btnSave4").click(function() {
      // 동의 확인
      if (document.getElementById('retire_agree').checked === false) {
        commonAlert("유의사항을 확인 하고 동의해주세요.", function() {
          $("[name=retire_agree]").focus();
        });
        return;
      }

      // 서버에 탈퇴 요청
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
  });
</script>

<!-- 팝업 배경 -->
<div id="dimwrap" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 15;"></div>

<!-- 비밀번호 변경 팝업 -->
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

<!-- 회원탈퇴 팝업 -->
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

<!-- 회원탈퇴 완료 팝업 -->
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
