<style>
  .page.page-mypage .profile .container .thumb-wrap form#frm {
    top: 0vh;
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
          <!-- 평점 -->
          <!-- <p class="star-rating v2"> -->
          <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
          <!-- <span class="star" style="width:100%;"></span>
            <span class="sr-text">평점</span>
            <span class="sr-text">5</span> -->
          <!-- </p> -->
        </div>
        <div class="profile-detail">
          <!-- 이름 -->
          <!-- <p class="name"><em><?= ((strpos(getData($mem_info, "mm_name"), '010') !== 0 && strpos(getData($mem_info, "mm_name"), 'kakao') !== 0 && strpos(getData($mem_info, "mm_name"), 'apple') !== 0) ? getData($mem_info, "mm_name") : "guest") ?></em>님</p> -->
          <div class="meta index">
            <?php $is_google_login = !empty($mem_info["mm_join_type"]) && strtoupper($mem_info["mm_join_type"]) === "GOOGLE"; ?>
            <?php if (!$is_google_login) : ?>

              <!-- 메일주소 -->
              <p class="email"><?= $mem_info["mm_email"] ?></p>

            <?php endif; ?>
            <!-- 연락처 -->
            <p class="phone"><?= $mem_info["mm_phone"] ?></p>
            <!-- 포인트 -->
            <p class="point"><?= number_format($mem_info["mm_point"]) ?>P</p>
          </div>
          <a href="#settings" role="button" aria-haspopup="dialog" aria-controls="settings" class="btn-settings js-toggle-panel"><span class="sr-text">설정</span></a>
        </div>
        <ul class="profile-record">
          <li><span class="label">프로필 조회</span><em class="value"><?= $hit_cnt ?></em></li>
          <li><span class="label">받은제안</span><em class="value"><?= $req_cnt ?></em></li>
          <li><span class="label">즐겨찾기</span><em class="value"><?= $fav_cnt ?></em></li>
        </ul>
      </div>
    </div>
    <div class="mypage-menus">
      <ul class="list">
        <li>
          <a href="/mypage/point_charge_step1" class="angle-link menu1">
            <strong>포인트 충전 · 광고</strong>
            <span>포인트를 구매하시면 추천인재로 등록이 가능합니다.</span>
          </a>
        </li>
        <li>
          <a href="/mypage/resume_list" class="angle-link menu2">
            <strong>이력서</strong>
          </a>
        </li>
        <li>
          <a href="/mypage/bookmark_recruit1" class="angle-link menu3">
            <strong>관심공고</strong>
          </a>
        </li>
        <li>
          <a href="/mypage/recruit_list" class="angle-link menu3">
            <strong>지원공고</strong>
          </a>
        </li>
        <?php if ($g_ios_publish != true) : ?>
          <li>
            <a href="/mypage/coupon_list?gbn=N" class="angle-link menu5">
              <strong>쿠폰</strong>
            </a>
          </li>
        <?php endif; ?>
        <!-- li>
                    <a href="/mypage/bookmark_list" class="angle-link menu4" onclick="return false;">
                        <strong style="color:#ccc;">즐겨찾기</strong>
                    </a>
                </li>

                <li>
                    <a href="/mypage/trade_list" class="angle-link menu3" onclick="return false;">
                        <strong style="color:#ccc;">중고거래</strong>
                    </a>
                </li -->
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
            <h3 class="secondary-title">가입자정보
              <?php
              // SNS 로그인 여부 확인
              $is_sns_login = !empty($mem_info["mm_join_type"]) && in_array(strtoupper($mem_info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER", "GOOGLE", "WECHAT"]);
              // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
              $is_authenticated = ($mem_info != null && $mem_info["mm_di"] != null && (strpos($mem_info["mm_di"], "MC0") === 0 || $is_sns_login));

              if (!$is_authenticated) {
                echo "<font style='font-size: 0.8rem; color: #777; font-weight: 400; margin-left: 10px;'>미인증</font>";
              } else {
                echo "<font style='font-size: 0.8rem; color: #57CCAF; font-weight: 400; margin-left: 10px;'>인증완료</font>";
              }
              ?>
            </h3>
            <?php
            // SNS 로그인 여부 확인 (버튼 표시용)
            $is_sns_login_btn = !empty($mem_info["mm_join_type"]) && in_array(strtoupper($mem_info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER"]);
            // 인증 완료 조건: mm_di가 "MC0"으로 시작하거나 SNS 로그인인 경우
            $is_authenticated_btn = ($mem_info != null && $mem_info["mm_di"] != null && (strpos($mem_info["mm_di"], "MC0") === 0 || $is_sns_login_btn));

            if (!$is_authenticated_btn) { ?>
              <button type="button" id="btnInfoUpdate" onclick="$('#dimwrap,#dimcont2').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; margin-top: -32px; height: 1.5rem; line-height: 0.2; min-width: 3.6rem; width: 3.6rem; margin-left: 6.25rem">인증</button>
            <?php  } else { ?>
              <!-- <button type="button" id="btnInfoUpdate" onclick="$('#dimwrap,#dimcont2').show();" class="btn-round btn-secondary" style="float: right; background-color: #57CCAF; margin-top: -32px; height: 1.5rem; line-height: 0.2; min-width: 3.6rem; width: 3.6rem; margin-left: 6.25rem">변경</button> -->
            <?php  }      ?>
            <ul class="settings-list">
              <!-- <li style="height: 34px; line-height: 20px;">
                <span class="label">가입자명</span>
                <em class="value" style="float: right;"><?= ((strpos(getData($mem_info, "mm_name"), '010') !== 0 && strpos(getData($mem_info, "mm_name"), 'kakao') !== 0 && strpos(getData($mem_info, "mm_name"), 'apple') !== 0) ? getData($mem_info, "mm_name") : "guest") ?></em></p>
                <input type="hidden" name="bizName" value="<?= (getData($mem_info, "mm_name") == "" ? "guest" : getData($mem_info, "mm_name")) ?>" readonly>
              </li> -->
              <li>
                <span class="label">연락처</span>
                <em class="value"><?= $mem_info["mm_phone"] ?></em>
                <input type="hidden" name="bizPhone" value="<?= getData($mem_info, "mm_phone") ?>" pattern="[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}" readonly>
              </li>
              <li style="height: 24px; display:none;">
                <span class="label">이메일</span>
                <button type="button" id="btnInfoUpdate" onclick="$('#dimwrap,#dimcont2').show();" class="btn-round btn-secondary" style="float: left; background-color: #57CCAF; padding: 5px 5px; margin-top: 3px; position: absolute; width: 40px; left: 69px; height: 1.3rem; font-size: 0.7rem; min-width: auto;">입력</button>
                <em class="value"><?= $mem_info["mm_email"] ?></em>
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
              $is_sns_login_pwd = !empty($mem_info["mm_join_type"]) && in_array(strtoupper($mem_info["mm_join_type"]), ["KAKAO", "APPLE", "NAVER", "GOOGLE", "WECHAT"]);
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
                            sessionStorage.removeItem("chat_user_connect");
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
              <li><a href="/setting/site_policy" class="menu">개인회원 이용약관</a></li>
              <li><a href="/setting/site_privacy" class="menu">개인정보처리방침</a></li>
              <li><a href="/setting/site_terms2" class="menu">개인정보 수집 및 이용동의</a></li>
              <li><a href="/setting/site_terms3" class="menu">위치기반서비스 이용약관</a></li>
              <li><a href="/setting/site_terms5" class="menu">광고성 정보 수신 동의</a></li>

            </ul>
          </div>
        </div>


        <!-- <div class="footer">
          
          <p class="name"><strong>NOW D&I Co., Ltd (주)나우디앤아이</strong></p>
          <div class="service-provider">
            <p>
              <span>Representative name : HAN OG CHOI</span><br>
              <span>Business registration number : 634-88-03093</span><br>
              <span>BMail order business : 2025-인천연수구-1886</span><br>
              <span>Address : 14, Songdogukje-daero 286beon-gil, Yeonsu-gu, Incheon</span><br>
              <span>Business of disseminating job information : J15000202540002</span>
            </p>
            <p>Copyright NOW D&I Co., Ltd all right reserved.</p>
          </div>
        </div> -->

        <a href="#app" class="btn-top js-goto-top"><span class="sr-text">Top</span></a>

      </div>
    </div>
  </div>
</div>
<!-- //설정 -->
<div id="dimwrap" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 50; background-color: #000; opacity: 0.3;"></div>
<div id="dimcont2" style="display:none; position: fixed; top: 30%; left: 5%; width: 90%; /* height: 252px; */ z-index: 60; background-color: #fff; padding: 40px;">
  <form id="frm21" name="frm21" onsubmit="return false">
    <div>
      <div style="font-size: 1.2em; font-weight: 600;">가입자정보 수정</div>
      <a href="javascript:$('#dimwrap,#dimcont2').hide();" style="position: absolute; right: 45px; font-size: 30px; top: 30px;">×</a>
    </div>
    <div style="font-size: 1.2em;">
      <div style="margin-top: 26px;">연락처</div>
      <div style="">
        <input type="text" name="mm_phone" id="mm_phone" readonly placeholder="연락처(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: calc(100% - 130px);" onfocus="commonAlert('본인인증을 완료하면 자동으로 입력됩니다.',function(){});">
        <button onclick="fManCheck();" style="margin-top: 15px; font-size: 16px; padding: 5px 10px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">변경(본인인증)</button>
        <input type="hidden" name="mm_di" id="mm_di">
        <input type="hidden" name="mm_sex" id="mm_sex">
      </div>
      <div style="margin-top: 30px;">가입자명</div>
      <div style="">
        <input type="text" name="mm_name" readonly placeholder="가입자명(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;" onfocus="commonAlert('본인인증을 완료하면 자동으로 입력됩니다.',function(){});">
      </div>
      <div style="display:none;margin-top: 26px; font-size: 0.8em;">이메일주소</div>
      <div style="display:none;">
        <input type="text" name="mm_email_id" id="mm_email_id" placeholder="이메일 입력" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 14px; margin-top: 15px; width: 30%;">
        <span>@</span>
        <input type="text" name="mm_email_domain" id="mm_email_domain" placeholder="" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 14px; margin-top: 15px; width: 25%;">
        <select id="mm_email_domain_sel" style="width: 33%; padding: 5px; border: 1px solid #ccc; color:#ccc;" onchange="$('#mm_email_domain').val($(this).val());">
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
    </div>
  </form>
  <div style="text-align: center;margin-top: 20px;">
    <button id="btnSave2" style="font-size: 16px; padding: 10px 16px;background-color: #57CCAF; color: #fff; border: 1px solid #fff">수정완료</button>
  </div>
</div>
<form name="form_chk" method="post">
  <input type="hidden" name="m" value="checkplusService">
  <input type="hidden" name="EncodeData" value="<?= $enc_data ?>">
</form>
<!--
<form id="frm2" name="frm2" onsubmit="return false">
  <input type="hidden" name="mm_name" readonly placeholder="가입자명(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: 100%;" onfocus="commonAlert('본인인증을 완료하면 자동으로 입력됩니다.',function(){});">
  <input type="hidden" name="mm_phone" id="mm_phone" readonly placeholder="연락처(본인인증을 통해 입력)" style="height: 32px;border: none; border-bottom: 1px solid #ccc; font-size: 16px; margin-top: 15px; width: calc(100% - 130px);" onfocus="commonAlert('본인인증을 완료하면 자동으로 입력됩니다.',function(){});">
  <input type="hidden" name="mm_di" id="mm_di">
</form>
-->

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
      border-radius: 50%;
    }

    .cropper-crop-box {
      border-radius: 50%;
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
  //$(document).ready(function(){
  window.addEventListener('DOMContentLoaded', function() {
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

  $("#btnSave2").click(function() {
    $("[name=mm_email]").val($("[name=mm_email_id]").val() + '@' + $("[name=mm_email_domain]").val());
    if ($("[name=mm_name]").val() == "") {
      commonAlert("가입자명을 입력해주세요.", function() {});
      return;
    }
    if ($("[name=mm_phone]").val() == "") {
      commonAlert("연락처를 입력해주세요.", function() {});
      return;
    }
    var form = $('#frm21');
    var params = new FormData(form[0]);
    fileAjax("/compage_proc/com_biz_update2", params, function(ret) {
      console.log(ret);
      if (ret.code == "00") {
        commonAlert("수정 되었습니다.", function() {
          location.href = '/mypage/index?gbn=setting';
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
        if (os_type == "ios") {
          location.href = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb?m=checkplusService&EncodeData=<?= $enc_data ?>";
        } else {
          window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
          document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
          document.form_chk.target = "popupChk";
          document.form_chk.submit();
        }
        //fPassSave();
      } else {
        commonAlert(ret.msg, function() {});
      }
    });
  }

  function fPassSave() {
    if ($('#mm_di').val() == '') {
      setTimeout(function() {
        fPassSave();
      }, 1000);
      return;
    }
    setTimeout(function() {
      var form = $('#frm2');
      var params = new FormData(form[0]);
      console.log(params);
      fileAjax("/compage_proc/com_biz_update2", params, function(ret) {
        console.log(ret);
        if (ret.code == "00") {
          commonAlert("인증이 완료 되었습니다.", function() {
            location.href = './index?gbn=setting';
          });
        } else {
          commonAlert(ret.msg, function() {});
        }
      });
    }, 1000);
  }

  //$( document ).ready(function() {
  window.addEventListener('DOMContentLoaded', function() {
    if ('<?= $gbn ?>' == 'pass' || '<?= $gbn ?>' == 'setting') {
      $('#settings').addClass('on');
      if ('<?= $gbn ?>' == 'pass') {
        setTimeout(function() {
          $('#btnInfoUpdate').trigger('click');
        }, 700);
      }
    }


  });

  // 스와이프 뒤로가기 비활성화 (이 페이지에서는 스와이프로 뒤로가기 불가)
  window.DISABLE_SWIPE_BACK = true;
</script>