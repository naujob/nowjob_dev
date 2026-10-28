<?php
$comp_popup_info = isset($comp_popup_info) ? $comp_popup_info : [];
$comp_popup_enc_data = isset($comp_popup_enc_data) ? $comp_popup_enc_data : "";
$comp_popup_reqgbn = isset($comp_popup_reqgbn) ? $comp_popup_reqgbn : null;
?>
<div id="dimwrap" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 10; background-color: rgba(0,0,0,0.4);"></div>
<style>
    /* 사업자등록 팝업 전용 스타일 */
    .comp-popup-wrap {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 420px;
        max-width: calc(100% - 32px);
        background: #fff;
        z-index: 20;
        border-radius: 16px;
        box-shadow: 0 14px 36px rgba(0, 0, 0, 0.18);
        padding: 32px 28px 24px;
        box-sizing: border-box;
        font-family: inherit;
    }

    .comp-popup-header {
        position: relative;
        text-align: center;
        margin-bottom: 20px;
    }

    .comp-popup-title {
        font-size: 20px;
        font-weight: 700;
        color: #2b2b2b;
        margin: 0;
        line-height: 1.3;
    }

    .comp-popup-close {
        position: absolute;
        right: -21px;
        top: -28px;
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        font-size: 26px;
        line-height: 1;
        color: #333;
        cursor: pointer;
        padding: 0;
    }

    .comp-popup-body {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .comp-popup-input {
        width: 100%;
        height: 48px;
        border: 1px solid #dedede;
        border-radius: 12px;
        padding: 0 14px;
        font-size: 15px;
        box-sizing: border-box;
        background: #fff;
        color: #333;
    }

    .comp-popup-input::placeholder {
        color: #b0b0b0;
    }

    .comp-popup-footer {
        margin-top: 24px;
    }

    .comp-popup-button {
        width: 100%;
        height: 52px;
        background-color: #3ad1bc;
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        box-sizing: border-box;
    }
</style>

<div id="dimcont" class="comp-popup-wrap">
    <form id="frm1" name="frm1" onsubmit="return false">
        <input type="hidden" id="compUpdateType" name="compUpdateType">
        <input type="hidden" id="biznoauth">
        <input type="hidden" name="comp_type" id="comp_type" value="COMP">
        <div class="comp-popup-header">
            <h3 class="comp-popup-title">사업자정보 인증</h3>
            <button type="button" class="comp-popup-close" aria-label="닫기" onclick="$('#dimwrap,#dimcont').hide();">&times;</button>
        </div>
        <div class="comp-popup-body">
            <input type="text" name="comp_num" id="bizno" class="comp-popup-input" placeholder="사업자 등록번호를 입력해 주세요">
            <input type="text" name="comp_name" id="comp_name" class="comp-popup-input" placeholder="사업자명을 입력해 주세요">
            <input type="hidden" name="mm_comp_type">
        </div>
    </form>
    <div class="comp-popup-footer">
        <button id="btnSave" class="comp-popup-button">확인</button>
    </div>
</div>

<?php /* 사용하지 않는 코드 - 주석 처리
<form name="form_chk" method="post">
    <input type="hidden" name="m" value="checkplusService">
    <input type="hidden" name="EncodeData" value="<?= $comp_popup_enc_data ?>">
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
                1. 탈퇴한 전화번호는 추후 재가입시 <?= WITHDRAW_BLOCK_DAYS ?>일간 가입이 제한 됩니다. <br>
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
*/ ?>

<?php /* 사용하지 않는 코드 - 주석 처리
<div id="thumbImgPop" style="display:none; position: fixed; top: 33%; left: calc(50% - 140px); width: 280px; height: 216px; z-index: 20; background-color: #fff; padding: 0px; overflow: hidden;">
    <img src="<?= getData($comp_popup_info, "mm_thumb") ?>" style="width: 100%; ">
    <a href="javascript:$('#dimwrap,#thumbImgPop').hide();" style="position: absolute; right: 10px; font-size: 32px; top: 10px; background-color:#fff; padding: 0px 10px 2px 10px; border-radius: 20px;">×</a>
    <button style="position: absolute; top: 180px; left: 0; width: 50%; height: 36px; border: 0; background-color: #ccc; color: #fff;" onclick="fThumbDel();">삭제</button>
    <button style="position: absolute; top: 180px; right: 0; width: 50%; height: 36px; border: 0; background-color: #38b898; color: #fff;" onclick="$('#mm_thumb').trigger('click');">변경</button>
</div>
*/ ?>

<?php /* 사용하지 않는 코드 - 주석 처리
<div id="dimcont7" style="display:none; position: fixed; top: 30%; left: calc(50% - 250px); width: 380px; height: 625px; z-index: 20; background-color: #fff; padding: 40px;">
    <div>
        <div style="font-size: 1.4em; font-weight: 600;">기업로고 변경</div>
        <div id="profileCancel" style="position: absolute; right: 45px; font-size: 36px; top: 30px;cursor:pointer;">×</div>
    </div>
    <div>
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
*/ ?>

<script>
<?php /* 사용하지 않는 코드 - 주석 처리
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
*/ ?>

<?php /* 사용하지 않는 코드 - 주석 처리
    function fManCheck() {
        if ($("[name=EncodeData]").val() == "") {
            alert("본인인증 모듈생성에 문제가 있습니다. 관리자에 문의해주세요.");
            return;
        }

        var form = $('#form_chk');
        var params = new FormData(form[0]);
        fileAjax("/member_proc/com_man_update", params, function(ret) {
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
            if (ret.code == "00") {
                $('#dimcont4').hide();
                $('#dimcont5').show();
            } else {
                commonAlert(ret.msg, function() {});
            }
        });
    }
*/ ?>

    $(document).ready(function() {
<?php /* 사용하지 않는 코드 - 주석 처리
        $("#btnCancel").click(function() {
            history.back();
        });

        // btnSaveAll 이벤트를 이벤트 위임 방식으로 변경
        $(document).off('click', '#btnSaveAll').on('click', '#btnSaveAll', function() {

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
                    commonAlert("사업자정보가 인증 되었습니다.", function() {
                        location.reload();
                    });
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });
        });
*/ ?>

        // btnSave 이벤트를 이벤트 위임 방식으로 변경 (팝업이 나중에 표시되어도 작동)
        $(document).off('click', '#btnSave').on('click', '#btnSave', function() {
            // 사업자명 입력 확인 (항상 필수)
            if ($("[name=comp_name]").val() == "" || $("[name=comp_name]").val().trim() == "") {
                commonAlert("사업자명을 입력해주세요.", function() {
                    $("[name=comp_name]").focus();
                });
                return;
            }

            // 완료 버튼에서 인증까지 처리
            fBizAuth(function() {
<?php /* 사용하지 않는 코드 - 주석 처리
                // 인증번호와 입력번호 일치 확인
                if (($("[name=compUpdateType]").val() == "bizno" || $("[name=compUpdateType]").val() == "all") && $("[name=comp_num]").val() !== $("#biznoauth").val()) {
                    commonAlert("사업자등록번호를 인증해주세요.", function() {
                        $("[name=comp_num]").focus();
                    });
                    return;
                }

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

                if ($('.addrbox').is(':visible') && $("[name=mm_addr]").val() == "") {
                    commonAlert("사업자 주소를 입력해주세요.", function() {
                        $("[name=mm_addr]").focus();
                    });
                    return;
                }

                if ($('input[name="comp_type"]').val() == 'OUT') {
                    if ($("#file-name").html() == "&nbsp;") {
                        commonAlert("사업자 등록증을 첨부해주세요.", function() {
                            $("[name=mm_comp_auth]").focus();
                        });
                        return;
                    }
                }

                if ($('.addrbox').is(':visible') && $("[name=compUpdateType]").val() != "all") {
                    $("[name=compUpdateType]").val('addr');
                }

                if ($('.bizimgbox').is(':visible')) {
                    $('input[name="comp_type"]').val('OUT');
                } else {
                    $('input[name="comp_type"]').val('COMP');
                }
*/ ?>
                var form = $('#frm1');
                var params = new FormData(form[0]);
                fileAjax("/compage_proc/com_biz_update", params, function(ret) {
                    if (ret.code == "00") {
                        // 채용공고 등록 버튼을 통해 열렸는지 확인
                        var fromRecruitWrite = $('#dimcont').data('from-recruit-write');
                        
                        if (fromRecruitWrite) {
                            // 채용공고 등록 버튼을 통해 열린 경우 recruit_write로 이동
                            $('#dimwrap').hide();
                            $('#dimcont').hide();
                            $('#dimcont').removeData('from-recruit-write'); // 플래그 제거
                            location.href = "/compage/recruit_write";
                        } else {
                            // 일반적인 경우 페이지 새로고침
                            commonAlert("사업자정보가 인증 되었습니다.", function() {
                                $('#dimwrap').hide();
                                $('#dimcont').hide();
                                location.reload();
                            });
                        }
                    } else {
                        commonAlert(ret.msg, function() {});
                    }
                });
            });
        });

    }); // $(document).ready 닫기

<?php /* 사용하지 않는 코드 - 주석 처리
        // btnSave2 이벤트를 이벤트 위임 방식으로 변경
        $(document).off('click', '#btnSave2').on('click', '#btnSave2', function() {

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
            fileAjax("/compage_proc/com_biz_update2", params, function(ret) {
                if (ret.code == "00") {
                    commonAlert("사업자정보가 인증 되었습니다.", function() {
                        // 페이지 이동 없이 팝업만 닫기
                        $('#dimwrap').hide();
                        $('#dimcont').hide();
                    });
                } else if (ret.code == "01") {
                    commonAlert(ret.msg, function() {
                        location.href = "/member/logout";
                    });
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });
        });

        // btnSave3 이벤트를 이벤트 위임 방식으로 변경
        $(document).off('click', '#btnSave3').on('click', '#btnSave3', function() {

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
            fileAjax("/compage_proc/com_biz_update3", params, function(ret) {
                if (ret.code == "00") {
                    commonAlert("변경 되었습니다.", function() {
                        location.reload();
                    });
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });
        });

        $("[name=mm_pass]").on("input", function() {
            var password = $(this).val();
            var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/;

            // 비밀번호 검증 로직 (로그 제거)
        });

        // btnSave4 이벤트를 이벤트 위임 방식으로 변경
        $(document).off('click', '#btnSave4').on('click', '#btnSave4', function() {

            if (document.getElementById('retire_agree').checked === false) {
                commonAlert("유의사항을 확인 하고 동의해주세요.", function() {
                    $("[name=retire_agree]").focus();
                });
                return;
            }

            var params = new FormData();
            fileAjax("/compage_proc/com_biz_retire", params, function(ret) {
                if (ret.code == "00") {
                    $('#dimwrap, #dimcont4').hide();
                    $('#dimcont5').show();
                } else {
                    commonAlert(ret.msg, function() {});
                }
            });

        });

        // mypage.php에서만 tabs-wrap 숨김 처리 (hide_tabs 변수가 설정된 경우만)
        <?php if (isset($hide_tabs) && $hide_tabs) : ?>
            $('.tabs-wrap').hide();
        <?php endif; ?>

        <?php
        $mem_info_di = isset($comp_popup_info["mem_info"]["mm_di"]) ? $comp_popup_info["mem_info"]["mm_di"] : (isset($comp_popup_info["mm_di"]) ? $comp_popup_info["mm_di"] : null);
        $mem_info_join_type = isset($comp_popup_info["mem_info"]["mm_join_type"]) ? $comp_popup_info["mem_info"]["mm_join_type"] : (isset($comp_popup_info["mm_join_type"]) ? $comp_popup_info["mm_join_type"] : null);
        $is_sns_login = !empty($mem_info_join_type) && in_array(strtoupper($mem_info_join_type), ["KAKAO", "APPLE", "NAVER"]);
        $is_authenticated = ($mem_info_di != null && (strpos($mem_info_di, "MC0") === 0 || $is_sns_login));
        ?>
        <?php if ($comp_popup_reqgbn == "compcert") { ?>
            $('#btnBizUpdate').trigger('click');
        <?php } else if ($comp_popup_reqgbn == "pass") { ?>
            $('#btnInfoUpdate').trigger('click');
        <?php } else if (isset($comp_popup_info["mem_info"]["mm_comp_num"]) && $comp_popup_info["mem_info"]["mm_comp_num"] == "") { ?>
        <?php } else if (!$is_authenticated) { ?>
            $('#btnInfoUpdate').trigger('click');
        <?php } ?>
*/ ?>

<?php /* 사용하지 않는 코드 - 주석 처리
        $('#toggle-password0').click(function() {
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

    });
*/ ?>

<?php /* 사용하지 않는 코드 - 주석 처리
    function fPassValid() {
        var password = $("[name=mm_pass]").val();
        var regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,20}$/;

        if (regex.test(password)) {
            return true;
        } else {
            return false;
        }

    }
*/ ?>

    function fBizAuth(onSuccess) {

        if ($('#bizno').val() == '') {
            commonAlert("사업자 등록번호를 입력해주세요.", function() {
                $("#bizno").focus();
            });
            return;
        }

        var data = {
            "b_no": [$("#bizno").val()]
        };
        $.ajax({
            url: "https://api.odcloud.kr/api/nts-businessman/v1/status?serviceKey=nbqW%2FFUs8GPs9gugbRl6wK2fqyEhU%2F4kDV1UzIEmA%2BgNYC9C1IyNsLwER%2FR5ALL5p20DpErEsQ2QlWm38oD%2FGA%3D%3D",
            type: "POST",
            data: JSON.stringify(data),
            dataType: "JSON",
            contentType: "application/json",
            accept: "application/json",
            success: function(result) {
                if (result.status_code == "OK") {
                    if (result.data[0].b_stt_cd == "01" || result.data[0].b_stt_cd == "01") {
                        bus_yn = true;
                        $("#biznoauth").val($("#bizno").val());
                        if (typeof onSuccess === 'function') {
                            onSuccess();
                        } else {
                            commonAlert("사업자번호 인증완료", function() {});
                        }
                        return;
                    } else if (result.data[0].b_stt_cd == "03") {
                        bus_yn = false;
                        commonAlert("폐업된 사업자 번호입니다.", function() {
                            $("#bizno").focus();
                        });
                        return;
                    } else {
                        bus_yn = false;
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

<?php /* 사용하지 않는 코드 - 주석 처리
    function fMemUpdateType(type) {
        // 모든 타입에서 중앙 정렬 유지
        const centerPopup = () => {
            $('#dimcont').css({
                height: 'auto',
                top: '50%',
                left: '50%',
                transform: 'translate(-50%, -50%)'
            });
        };

        if (type == 'compname') {
            $('.compnamebox').show();
            $('.biznobox').hide();
            $('.bizimgbox').hide();
            $('.sousinghelp').hide();
            $('.addrbox').hide();
            centerPopup();
        } else if (type == 'bizno') {
            $('.compnamebox').hide();
            $('.biznobox').show();
            $('.bizimgbox').hide();
            $('.sousinghelp').hide();
            $('.addrbox').hide();
            centerPopup();
        } else if (type == 'address') {
            $('.compnamebox').hide();
            $('.biznobox').hide();
            $('.bizimgbox').hide();
            $('.sousinghelp').hide();
            $('.addrbox').show();
            centerPopup();
        } else {
            $('.compnamebox').show();
            $('.biznobox').show();
            $('.addrbox').show();
            $('input[name="comp_type"]').val('');
            centerPopup();
        }
        $('#compUpdateType').val(type);
    }

    function selectAddrInfo(zipcode, doro, jibun, addr, x, y) {
        $("#mm_zipcode").val(zipcode);
        $("#mm_jibun").val(doro);
        $("#mm_doro").val(jibun);
        $("#mm_addr").val(addr);
        $("#mm_gps_x").val(x);
        $("#mm_gps_y").val(y);

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
*/ ?>
</script>
<?php include_once(get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>