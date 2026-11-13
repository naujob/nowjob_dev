<div id="content" class="page page-join page-member-type1">
    <main class="page-content">

        <div class="container">
            <h2 class="primary-title"><?= t('회원가입') ?></h2>

            <div class="join-step2">

                <h3 class="first-title"><?= t('이용약관동의') ?></h3>
                <div class="section">
                    <div class="terms-list">
                        <ul class="list">
                            <li>
                                <label class="form-checkbox">
                                    <input type="checkbox" id="agree1" name="agreement" aria-required="true" required value="1">
                                    <span class="label-red"></span>
                                    <span class="sr-text"><?= t('동의') ?></span>
                                </label>
                                <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="angle-link js-open-Popup" data-idx="1"><span><?= t('서비스 이용약관') ?><em>(<?= t('필수') ?>)</em></span></a>
                                <!-- 약관내용 -->
                                <template id="terms1Temp">
                                    <div class="terms-wrap">
                                        <p class="terms-title"><?= t('서비스 이용약관(필수)') ?></p>
                                        <?=$policy1["policy2_pc"]?>
                                    </div>
                                </template>
                            </li>
                            <li>
                                <label class="form-checkbox">
                                    <input type="checkbox" id="agree2" name="agreement" aria-required="true" required value="2">
                                    <span class="label-red"></span>
                                    <span class="sr-text"><?= t('동의') ?></span>
                                </label>
                                <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="angle-link js-open-Popup" data-idx="2"><span><?= t('개인정보 처리방침') ?><em>(<?= t('필수') ?>)</em></span></a>
                                <!-- 약관내용 -->
                                <template id="terms2Temp">
                                    <div class="terms-wrap">
                                        <p class="terms-title"><?= t('개인정보 처리방침(필수)') ?></p>
                                        <?=$policy2["privacy_pc"]?>
                                    </div>
                                </template>
                            </li>
                            <li>
                                <label class="form-checkbox">
                                    <input type="checkbox" id="agree3" name="agreement" aria-required="true" required value="3">
                                    <span class="label-red"></span>
                                    <span class="sr-text"><?= t('동의') ?></span>
                                </label>
                                <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="angle-link js-open-Popup" data-idx="3"><span><?= t('위치 서비스 이용약관') ?><em>(<?= t('필수') ?>)</em></span></a>
                                <!-- 약관내용 -->
                                <template id="terms3Temp">
                                    <div class="terms-wrap">
                                        <p class="terms-title"><?= t('위치 서비스 이용약관') ?><em>(<?= t('필수') ?>)</em></p>
                                        <?=$policy3["location_pc"]?>
                                    </div>
                                </template>
                            </li>
                            <li>
                                <label class="form-checkbox">
                                    <input type="checkbox" id="agree4" name="agreement" value="4">
                                    <span class="label-red"></span>
                                    <span class="sr-text"><?= t('동의') ?></span>
                                </label>
                                <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="angle-link js-open-Popup" data-idx="4"><span><?= t('개인정보 수집 및 이용 동의(선택)') ?></span></a>
                                <!-- 약관내용 -->
                                <template id="terms4Temp">
                                    <div class="terms-wrap">
                                        <p class="terms-title"><?= t('개인정보 수집 및 이용 동의(선택)') ?></p>
                                        <?=$policy4["privacy3_pc"]?>
                                    </div>
                                </template>
                            </li>
                            <li>
                                <label class="form-checkbox">
                                    <input type="checkbox" id="agree5" name="agreement" value="5">
                                    <span class="label-red"></span>
                                    <span class="sr-text"><?= t('동의') ?></span>
                                </label>
                                <a href="#termsPopup" role="button" aria-haspopup="dialog" aria-controls="termsPopup" class="angle-link js-open-Popup" data-idx="5">
                                    <span><?= t('광고성 정보 이메일/SMS수신 동의(선택)') ?></span>
                                    <small>(<?= t('나우잡 소식 및 광고메일, 휴대폰 알림') ?>)</small>
                                </a>
                                <!-- 약관내용 -->
                                <template id="terms5Temp">
                                    <div class="terms-wrap">
                                        <p class="terms-title"><?= t('광고성 정보 이메일/SMS수신 동의(선택)') ?></p>
                                        <?=$policy5["adv_pc"]?>
                                    </div>
                                </template>
                            </li>
                        </ul>
                    </div>
                    <label class="form-checkbox">
                        <input type="checkbox" name="AgreementAll" value="all" class="js-check-all">
                        <span class="label-red" style="font-size: 16px; font-weight: 600; color: #000;"><?= t('모두 동의하고 시작') ?></span>
                    </label>

                    <script>
                        (function () {
                            var agreements = document.querySelectorAll('input[name="agreement"]');
                            var allCheck = document.querySelector('.js-check-all');
                            var total = 0;

                            allCheck.addEventListener('change', function () {
                                if (!agreements.length) return;

                                agreements.forEach(function (input) {
                                    input.checked = allCheck.checked;
                                    total = allCheck.checked ? agreements.length : 0;

                                    if(allCheck.checked){
                                      $('#btnNext').css('background-color', '#57CCAF'); // 버튼 활성화 색상 변경
                                    } else {
                                      $('#btnNext').css('background-color', '#ccc'); // 버튼 비활성화 색상 변경
                                    }

                                });
                            });

                            agreements.forEach(function (input) {
                                if(input.checked) total += 1;

                                input.addEventListener('change', function () {
                                    if(this.checked) {
                                        total += 1;
                                    } else {
                                        total -= 1;
                                    }

                                    if (total == agreements.length) {
                                        allCheck.checked = true;
                                    } else {
                                        allCheck.checked = false;
                                    }

                                    //check
                                    if($('#agree1').prop('checked') &&
                                       $('#agree2').prop('checked') &&
                                       $('#agree3').prop('checked')){
                                         $('#btnNext').css('background-color', '#57CCAF'); // 버튼 활성화 색상 변경
                                    }else{
                                         $('#btnNext').css('background-color', '#ccc'); // 버튼 비활성화 색상 변경
                                    }

                                });
                            });

                            window.addEventListener('DOMContentLoaded', function () {
                                document.querySelectorAll('.js-open-Popup').forEach(function (btn) {
                                    btn.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        var id = this.getAttribute('aria-controls');
                                        var title = this.textContent;
                                        var content = this.parentNode.querySelector('template').innerHTML;
                                        var agreeidx = $(this).data('idx');
                                        openPopup('modal', {
                                            id: id,
                                            title: title,
                                            content: content,
                                            firstButton: t('확인'),
                                            firstCallback: function () {
                                                checkContent(agreeidx);
                                                closePopup(document.getElementById(id));
                                            }
                                        });
                                    })
                                });
                            });
                        }());
                    </script>
                </div>

                <div class="form-action-wrap">
                    <button type="button" class="btn-round btn-tertiary" id="btnNext" style="background-color:#ccc;"><?= t('다음') ?></button>
                    <!-- 가입 취소: 히스토리를 교체해 이전 가입 단계로 되돌아가지 않도록 한다 -->
                    <a href="/member/login" class="btn-round btn-tertiary" style="margin-left:12px;background-color:#fff;border:1px solid #ccc;color:#666;" onclick="location.replace('/member/login'); return false;"><?= t('취소') ?></a>
                </div>

            </div>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->

<script>
$(document).ready(function(){
	$("#btnNext").click(function(){
		// 약관 동의값은 모바일과 같은 경로(/member_proc/join_comp_step5 → st_site_log 스텝5)로 저장한다.
		// 가입 완료(join_complete_proc PART)가 스텝5에서 약관을 읽으므로 이 경로여야 DB에 남는다.
		var agree1 = $("#agree1").is(":checked") ? 'Y' : 'N';
		var agree2 = $("#agree2").is(":checked") ? 'Y' : 'N';
		var agree3 = $("#agree3").is(":checked") ? 'Y' : 'N';
		var agree4 = $("#agree4").is(":checked") ? 'Y' : 'N';
		var agree5 = $("#agree5").is(":checked") ? 'Y' : 'N';

		if (agree1 != 'Y') {
			commonAlert(t('서비스 이용약관(필수)에 동의해주세요.'), function(){ $("#agree1").focus(); });
			return;
		}
		if (agree2 != 'Y') {
			commonAlert(t('개인정보 처리방침(필수)에 동의해주세요.'), function(){ $("#agree2").focus(); });
			return;
		}
		if (agree3 != 'Y') {
			commonAlert(t('위치 서비스 이용약관(필수)에 동의해주세요.'), function(){ $("#agree3").focus(); });
			return;
		}

		var params = {
			agree1: agree1,
			agree2: agree2,
			agree3: agree3,
			agree4: agree4,
			agree5: agree5
		};

		comAjax("/member_proc/join_comp_step5", params, function(ret2){
			if(ret2.code=="00"){
				// 약관 저장 완료 후 회원가입 완료 처리 (PART)
				fPassClickFromStep3();
			}else{
				commonAlert(ret2.msg,function(){});
			}
		});
	});
});

function checkContent(arg) {
    $('#agree'+arg+'').prop('checked', true); // 약관 팝업 확인 시 체크
}

// 회원가입 완료 처리 (PART)
// 기업회원은 QuickInfo 바텀시트가 없어(join_comp_finish로 이동) 계정 생성을 미룰 곳이 없다.
// 따라서 개인회원과 달리 여기서 그대로 계정을 생성한다.
function fPassClickFromStep3(){
    var params = {};
    params["ma_type"] = "COMP";
    params["proc_type"] = "PART";
    params["uuid"] = "";

    comAjax("/member_proc/join_complete_proc", params, function(ret){
        if(ret.code=="00"){
            // replace: 가입 단계 페이지를 히스토리에서 교체해 완료 후 뒤로가기로 이 페이지로 돌아오지 않게 한다
            location.replace("/member/join_comp_finish");
        }else{
            commonAlert(ret.msg, function(){});
            return;
        }
    });
}

// 브라우저 뒤로가기: 1차는 열린 약관 모달 닫기, 다시 누르면 가입을 취소하고 로그인 화면으로
// 그냥 두면 이전 가입 단계로 돌아가 흐름이 꼬인다.
(function() {
    history.pushState(null, '', location.href);
    window.addEventListener('popstate', function() {
        var openPopupEl = document.querySelector('.layer-popup.in');
        if (openPopupEl) {
            if (typeof closePopup === 'function') {
                closePopup(openPopupEl);
            }
            history.pushState(null, '', location.href); // 소비한 항목 복구
            return;
        }
        location.replace('/member/login');
    });
})();

</script>
