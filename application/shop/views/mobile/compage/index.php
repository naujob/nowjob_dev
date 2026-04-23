<?php
function fBizNoMasking($bizNo)
{
  $maskedBizNo = substr($bizNo, 0, 3) . '-' .
    substr($bizNo, 3, 2) . '-' .
    str_repeat('*', 5);
  return $maskedBizNo;
}

?>
<style>
  .profile .container {
    padding-top: 1.6875rem;
    padding-bottom: 2.6875rem;
  }



  .profile .form-pic:before {
    top: auto;
    bottom: -0.75rem;
    left: auto;
    right: -0.75rem;
    width: 2.375rem;
    height: 2.375rem;
    margin: 0;
    background-color: #1B9D7D;
    z-index: 2;
  }

  .profile-detail {
    padding-top: 1.3rem;
  }
 .profile .thumb-wrap{
  position: relative;
  top:-18px;
 }
</style>
<div class="page page-mypage">
  <div class="page-content">

    <div class="profile">
      <div class="container settings-profile">
        <div class="thumb-wrap">
          <form id="frm" onsubmit="return false">
            <label class="form-pic">
              <input type="file" id="newProfile" name="mm_img1" accept="image/*" class="">
              <span class="preview"><img id="profilePhoto" src="<?= getResumeImg($mem_info) ?>" alt=""></span>
            </label>
          </form>
        </div>
        <div class="profile-detail">
          <!-- 이름 -->
          <!-- <p class="name"><em><?= ($mem_info == null || ($mem_info["mm_comp_num"] == "")) ? "기업회원" : $mem_info["mm_comp_name"] ?></em></p> -->
          <div class="meta index">
            <!-- 연락처 -->
            <p class="phone"><?= $mem_info["mm_phone"] ?></p>
            <!-- 메일주소 -->
            <!-- <p class="email"><?= $mem_info["mm_email"] ?></p> -->
            <!-- 포인트 -->
            <p class="point"><?= number_format($mem_info["mm_point"]) ?>P</p>
          </div>
          <a href="#settings" role="button" aria-haspopup="dialog" aria-controls="settings" class="btn-settings js-toggle-panel"><span class="sr-text">설정</span></a>
        </div>
      </div>
    </div>
    <div class="mypage-menus">
      <ul class="list">

        <li>
          <a href="/compage/point_promotion_buy" class="angle-link menu1">
            <strong>포인트 충전 · 광고</strong>
            <span>포인트를 구매하시면 관심인재와 1:1대화 기능및<br> 추천기업으로 등록이 가능합니다.</span>
          </a>
        </li>

        <li>
          <a href="/compage/recruit_list" class="angle-link menu2">
            <strong>채용공고</strong>
          </a>
        </li>
        <li>
          <a href="/compage/bookmark_person1" class="angle-link menu3">
            <strong>관심인재</strong>
          </a>
        </li>
        <!-- <li>
          <a href="/compage/interv_list" class="angle-link menu6">
            <strong>면접자</strong>
          </a>
        </li> -->
        <?php if ($g_ios_publish != true) : ?>
          <li>
            <a href="/compage/coupon_list?gbn=N" class="angle-link menu5">
              <strong>쿠폰</strong>
            </a>
          </li>
        <?php endif; ?>

        <!-- li>
                    <a href="/compage/bookmark_list" class="angle-link menu4" onclick="return false;">
                        <strong style="color:#ccc;">즐겨찾기</strong>
                    </a>
                </li>
                <li>
                    <a href="/compage/trade_list" class="angle-link menu3" onclick="return false;">
                        <strong style="color:#ccc;">중고거래</strong>
                    </a>
                </li -->
        <?php if ($mem_info["mm_comp_type"] == "OUT" && $mem_info["mm_comp_out_yn"] == "Y") : ?>
          <li>
            <a href="/compage/out_member" class="angle-link menu7">
              <strong>아웃소싱 멤버관리</strong>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>

  </div><!-- //page-content -->

  <!-- bottom-nav -->
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
  <!-- //bottom-nav -->

</div>
<!-- 설정 -->
<div id="settings" class="layer-panel" tabindex="-1">
  <div class="panel-container">
    <?php
    $top_bar = array(
        'title'      => '설정',
        'back_type'  => 'none',
        'right_html' => '<button type="button" aria-controls="settings" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>'
    );
    include_once(get_base_url() . '/application/shop/views/mobile/_partial/top_bar.php');
    ?>
    <div class="panel-body">
      <div class="panel-content">

        <div class="section">
          <div class="container settings-profile">
            <h3 class="secondary-title">기업정보
              <?php
              if ($mem_info == null || ($mem_info["mm_comp_num"] == "")) {
                // echo "<font style='font-size: 0.8rem; color: #777; font-weight: 400; margin-left: 10px;'>미인증</font>";
              } else {
                //  echo "<font style='font-size: 0.8rem; color: #57CCAF; font-weight: 400; margin-left: 10px;'>인증완료</font>";
              }
              ?>
            </h3>
            <ul class="settings-list">
              <li style="display: block; height: 26px;">
                <span class="label">사업자명</span>
                <?php if ($mem_info == null || ($mem_info["mm_comp_num"] == "")) {   ?>
                  <button type="button" id="btnBizUpdate" class="btn-round btn-secondary" onclick="fMemUpdateType('all');" style="background-color: #57CCAF; min-width: 3.6rem; height: 1.5rem; line-height: 0.2; float:right; margin-top: -35px;">인증</button>
                <?php } else {   ?>
                  <button type="button" id="btnBizUpdate" class="btn-round btn-secondary" onclick="fMemUpdateType('all');" style="background-color: #57CCAF; min-width: 3.6rem; height: 1.5rem; line-height: 0.2; float:right; margin-top: -35px;">변경</button>
                <?php }        ?>
                <em class="value" style="display:inline-block; float:right; height: 30px; margin-top: 0px;"><?= ($mem_info == null || ($mem_info["mm_comp_num"] == "")) ? "기업회원" : $mem_info["mm_comp_name"] ?></em></p>
              </li>
              <li style="display: block; height: 26px;">
                <span class="label">사업자등록번호</span>
                <em class="value" style="height: 24px;"><?= ($mem_info["mm_comp_num"] == "" ? "<font style='color:#ccc'>사업자 인증이 필요합니다</font>" : fBizNoMasking($mem_info["mm_comp_num"])) ?></em>
              </li>
              <!-- <li style="display: block;">
                <span class="label">사업자주소</span>
                <button type="button" id="btnBizUpdate5" class="btn-round btn-secondary" onclick="fMemUpdateType('all');" style="background-color: #57CCAF; min-width: 3.6rem; height: 1.5rem; line-height: 0.2; float: right; margin-top: -24px;">입력</button>
                <em class="value" style="margin-top: 24px; word-break: keep-all;"><?= ($mem_info["mm_doro"] == "" ? "<font style='color:#ccc'>사업자주소를 입력해 주세요</font>" : $mem_info["mm_doro"]) ?></em>
              </li> -->
              <li style="display:none;">
                <span class="label">이메일</span>
                <em class="value"><?= $mem_info["mm_email"] ?></em>
              </li>
            </ul>
          </div>
        </div>

        <div class="section">
          <div class="container settings-profile">
            <h3 class="secondary-title">가입자정보
              <?php
              // SNS 로그인 여부 확인
              $is_sns_login = !empty($mem_info["mm_join_type"]) && in_array(strtoupper($mem_info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER"]);
              // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
              $is_authenticated = ($mem_info != null && $mem_info["mm_di"] != null && (strpos($mem_info["mm_di"], "MC0") === 0 || $is_sns_login));

              if (!$is_authenticated) {
                // echo "<font style='font-size: 0.8rem; color: #777; font-weight: 400; margin-left: 10px;'>미인증</font>";
              } else {
                //  echo "<font style='font-size: 0.8rem; color: #57CCAF; font-weight: 400; margin-left: 10px;'>인증완료</font>";
              }
              ?>
            </h3>
            <?php
            // SNS 로그인 여부 확인 (버튼 표시용)
            $is_sns_login_btn = !empty($mem_info["mm_join_type"]) && in_array(strtoupper($mem_info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER"]);
            // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
            $is_authenticated_btn = ($mem_info != null && $mem_info["mm_di"] != null && (strpos($mem_info["mm_di"], "MC0") === 0 || $is_sns_login_btn));

            if (!$is_authenticated_btn) { ?>
              <!-- <button type="button" id="btnInfoUpdate" onclick="$('#dimwrap,#dimcont2').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; margin-top: -32px; height: 1.5rem; line-height: 0.2; min-width: 3.6rem; width: 3.6rem; margin-left: 6.25rem">인증</button> -->
            <?php  } else { ?>
              <!-- <button type="button" id="btnInfoUpdate" onclick="$('#dimwrap,#dimcont2').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; margin-top: -32px; height: 1.5rem; line-height: 0.2; min-width: 7.6rem; width: 3.6rem; margin-left: 6.25rem">가입자정보 변경</button> -->
            <?php  }      ?>
            <ul class="settings-list">
              <li>
                <span class="label">가입자명</span>
                <em class="value"><?= ((strpos(getData($mem_info, "mm_name"), '010') !== 0 && strpos(getData($mem_info, "mm_name"), 'kakao') !== 0 && strpos(getData($mem_info, "mm_name"), 'apple') !== 0) ? getData($mem_info, "mm_name") : "guest") ?></em></p>
                <input type="hidden" name="bizName" value="<?= (getData($mem_info, "mm_name") == "" ? "guest" : getData($mem_info, "mm_name")) ?>" readonly>
              </li>
              <li style="margin-top: 5px;">
                <span class="label">연락처</span>
                <em class="value"><?= $mem_info["mm_phone"] ?></em>
                <input type="hidden" name="bizPhone" value="<?= getData($info, "mm_phone") ?>" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" readonly>

              </li>
            </ul>
          </div>
        </div>

        <div class="section">
          <div class="container settings-profile">
            <h3 class="secondary-title">회원정보 수정</h3>
            <ul class="settings-list">
              <?php
              // SNS 로그인 여부 확인 (카카오, 네이버, 애플)
              $is_sns_login_pwd = !empty($mem_info["mm_join_type"]) && in_array(strtoupper($mem_info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER"]);
              // SNS 로그인이 아닌 경우에만 비밀번호 변경 링크 표시
              if (!$is_sns_login_pwd) {
              ?>
                <li>
                  <a href="/setting/change_pwd" class="menu">비밀번호 변경</a>
                </li>
              <?php } ?>
              <li>
                <button type="button" aria-haspopup="dialog" class="menu js-logout">로그아웃</buton>
                  <script>
                    /*로그아웃*/
                    window.addEventListener('DOMContentLoaded', function() {
                      document.querySelector('.js-logout').addEventListener('click', function(e) {
                        e.preventDefault();
                        openPopup('confirm', {
                          content: '로그아웃 하시겠습니까?',
                          secondCallback: function() {
                            location.href = "/member/logout";
                          }
                        });
                      });
                    });
                  </script>
              </li>
              <li>
                <a href="/setting/fire_step1" class="menu">회원탈퇴</a>
              </li>
            </ul>
          </div>
        </div>

        <div class="section">
          <div class="container settings-profile">
            <h3 class="secondary-title">알림</h3>
            <ul class="settings-list">
              <li>
                <a href="/setting/alarm" class="menu">알림설정</a>
              </li>
            </ul>
          </div>
        </div>

        <div class="section">
          <div class="container settings-profile">
            <ul class="settings-menus">
              <li><a href="/setting/notice" class="menu">공지사항</a></li>
              <li><a href="/setting/community" class="menu">커뮤니티</a></li>
              <li><a href="/setting/event" class="menu">이벤트</a></li>
              <li><a href="/setting/faq" class="menu">FAQ</a></li>
              <li><a href="/setting/report" class="menu">문의하기</a></li>
              <li><a href="/setting/my_report" class="menu">나의신고내역</a></li>
              <li><a href="/setting/site_introduce" class="menu">회사소개</a></li>
            </ul>
          </div>
        </div>

        <div class="section is-last">
          <div class="container settings-profile">
            <h3 class="secondary-title">약관</h3>
            <ul class="settings-list">
              <li><a href="/setting/site_policy2" class="menu">기업회원 이용약관</a></li>
              <li><a href="/setting/site_privacy" class="menu">개인정보처리방침</a></li>
              <li><a href="/setting/site_terms2" class="menu">개인정보 수집 및 이용동의</a></li>
              <li><a href="/setting/site_terms3" class="menu">위치기반서비스 이용약관</a></li>
              <li><a href="/setting/site_terms5" class="menu">광고성 정보 수신 동의</a></li>
             
            </ul>
          </div>
        </div>


        <!-- <div class="footer">

          <p class="name"><strong>NAU INTERNATIONAL Co., Ltd</strong></p>
          <div class="service-provider">
            <p>
              <span>Business registration number : 769-88-01746</span><br>
              <span>Representative name : HAN OG CHOI</span>
            </p>
            <p>Adress : 1st floor, 2, Juju-ro 80beon-gil, Namyang-eup, Hwaseong-si, Gyeonggi-do</p>
            <p>Tel : 031-255-0119</p>
          </div>
        </div> -->

        <a href="#app" class="btn-top js-goto-top"><span class="sr-text">Top</span></a>

      </div>
    </div>
  </div>
</div>
<!-- //설정 -->

<form name="form_chk" method="post">
  <input type="hidden" name="m" value="checkplusService">
  <input type="hidden" name="EncodeData" value="<?= $enc_data ?>">
</form>
<div id="dimcont2" style="display:none; position: fixed; top: 30%; left: 5%; width: 90%; /* height: 360px; */ z-index: 21; background-color: #fff; padding: 40px;">
  <form id="frm2" name="frm2" onsubmit="return false">
    <div>
      <div style="font-size: 1.4em; font-weight: 600;">가입자정보 수정</div>
      <a href="javascript:$('#dimwrap,#dimcont2').hide();" style="position: absolute; right: 45px; font-size: 36px; top: 30px;">×</a>
    </div>
    <div style="font-size: 1.2em;">
      <div style="margin-top: 26px;font-size: 1rem;">연락처</div>
      <div>
        <input type="text" name="mm_phone" id="mm_phone" readonly placeholder="(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: calc(100% - 130px);">
        <button onclick="fManCheck();" style="margin-left: 9px; font-size: 16px; padding: 5px 10px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">변경(본인인증)</button>
        <input type="hidden" name="mm_di" id="mm_di">
      </div>
      <div style="margin-top: 30px;font-size: 1rem;">가입자명</div>
      <div>
        <input type="text" name="mm_name" readonly placeholder="(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;">
      </div>
    </div>
  </form>
  <div style="text-align: center;margin-top: 20px;">
    <button id="btnSave2" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">완료</button>
  </div>
</div>

<div id="dimcont3" style="display:none; position: fixed; top: 0; left: 5%; width: 90%; /* height: 252px; */ z-index: 60; background-color: #fff; padding: 40px;">
  <div>
    <div style="font-size: 1.2em; font-weight: 600;">프로필사진 변경</div>
    <div id="profileCancel" style="position: absolute; right: 45px; font-size: 30px; top: 30px;">×</div>
  </div>
  <link type="text/css" rel="stylesheet" href="/resource/shop/web/css/cropper.min.css?vs=1753162724">
  <script src='/resource/shop/web/js/lib/cropper.min.js?vs=1753162724'></script>
  <style>
    .crop-wrapper {
      position: relative;
      width: 100%;
      height: 100%;
      margin: 20px 0;
    }

    .cropper-view-box {
      border: 1px solid #333;
    }

    .cropper-crop-box {
      overflow: hidden;
    }
  </style>
  <div>
    <div class="crop-wrapper">
      <img id="previewImage" style="max-width: 100%;" />
    </div>
  </div>
  <div style="text-align: center;margin-top: 20px;">
    <button id="profileCrop" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">수정완료</button>
  </div>
</div>

<script>
  let profileBlob = null;
  let newProfileImageSelected = false;
  $(document).ready(function() {
    const $previewImage = $('#previewImage');
    const $newInput = $('#newProfile');
    const $resultCanvas = $('#profilePhoto');
    const $cancel = $('#profileCancel');
    const $crop = $('#profileCrop');
    let cropper;

    $newInput.on('click', function() {
      this.value = null;
    });

    $newInput.on('change', function(e) {
      const file = e.target.files[0];
      if (!file) return;
      $('#dimwrap, #dimcont3').show();

      newProfileImageSelected = true;

      const url = URL.createObjectURL(file);
      $previewImage.attr('src', url);

      $previewImage.on('load', function() {
        if (cropper) cropper.destroy();
        $previewImage.attr('style', '');
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
          responsive: true,
          zoomOnTouch: true,
        });
      });
    });

    $cancel.on('click', function() {
      profileBlob = null;
      newProfileImageSelected = false;
      $('#dimwrap, #dimcont3').hide();
    });

    $crop.on('click', function() {
      if (!cropper) return;

      const profileCanvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
        imageSmoothingQuality: 'high',
      });
      if (!profileCanvas) {
        commonAlert("이미지가 생성되지 않았습니다.", function() {});
        return;
      }
      const newProfile = profileCanvas.toDataURL('image/png');
      profileCanvas.toBlob(function(blob) {
        profileBlob = blob;
        var form = $('#frm');
        var params = new FormData(form[0]);

        if (newProfileImageSelected && !profileBlob) {
          commonAlert("새로운 프로필사진을 확인해주세요.", function() {});
          return;
        }
        if (profileBlob) {
          params.append('mm_img1', profileBlob, 'newProfile.png');
        }
        fileAjaxLoading("/common_proc/change_img", params, function(ret) {
          if (ret.code == "00") {
            $resultCanvas.attr('src', newProfile);
            $('#dimwrap, #dimcont3').hide();
          } else {
            commonAlert(ret.msg, function() {});
            return;
          }
        });
      }, 'image/png');
    });
  });


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

        var os_type = "<?= $os_type ?>";
        //alert('00:'+os_type);

        if (os_type == "ios") {

          location.href = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb?m=checkplusService&EncodeData=<?= $enc_data ?>";

        } else {

          window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
          document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
          document.form_chk.target = "popupChk";
          document.form_chk.submit();

        }


      } else {
        commonAlert(ret.msg, function() {});
      }
    });

  }

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
          location.href = './index?gbn=setting';
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

  $(document).ready(function() {
    if ('<?= $gbn ?>' == 'compcert' || '<?= $gbn ?>' == 'pass' || '<?= $gbn ?>' == 'setting') {
      $('#settings').addClass('on');
      if ('<?= $gbn ?>' == 'compcert') {
        setTimeout(function() {
          $('#btnBizUpdate').trigger('click');
        }, 700);
      } else if ('<?= $gbn ?>' == 'pass') {
        setTimeout(function() {
          $('#btnInfoUpdate').trigger('click');
        }, 700);
      }
    }

    // 스와이프 뒤로가기 비활성화 (이 페이지에서는 스와이프로 뒤로가기 불가)
    window.DISABLE_SWIPE_BACK = true;

  });
</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>