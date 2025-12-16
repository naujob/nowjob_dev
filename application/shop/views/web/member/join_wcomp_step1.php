<div id="content" class="page page-join page-member-type2">
    <main class="page-content">
        <div class="container">
            <h2 class="primary-title" style="display:none;">회원가입</h2>
            <div class="account-info-form" style="display:none;">
                <h2 class="title">사용하실 <em>계정정보</em>를 작성해주세요</h2>
                <form id="frm" onsubmit="return false">
                    <div class="form-row" style="display:none;">
                        <div class="form-group">
                            <label class="form-toggle-btn">
                                <input type="radio" name="mem_comp_type" id="mem_comp_type1" value="PERSON" checked>
                                <span class="btn">
                                    <span class="ico"></span>
                                    <span>사업자 번호가 없는경우</span>
                                    <strong>개인</strong>
                                </span>
                            </label>

                            <label class="form-toggle-btn">
                                <input type="radio" name="mem_comp_type" id="mem_comp_type2" value="COMP">
                                <span class="btn">
                                    <span class="ico"></span>
                                    <span>사업자 번호가 있는 경우</span>
                                    <strong>사업자</strong>
                                </span>
                            </label>
                        </div>
                    </div>

                    <script>
                    	var current_type = "type1Fieldset";
                        /* 사업자 타입별 fieldset 표시 */
                        function toggleFieldset (id) {
                        	current_type = id;

                            var targetField = document.getElementById(id);
                            document.querySelectorAll('input').forEach(function (input) {
                                input.value = '';
                                input.removeAttribute('aria-required');
                                input.required = false;
                            });;
                            document.querySelectorAll('.form-fieldset').forEach(function (fieldset) {
                                fieldset.hidden = true;
                            });
                            targetField.hidden = false;
                            targetField.querySelectorAll('input').forEach(function (input) {
                                input.setAttribute('aria-required','true');
                                input.required = true;
                            });
                        }

                        window.addEventListener('DOMContentLoaded', function () {
                            document.querySelectorAll('.form-toggle-btn input').forEach(function (btn) {
                                btn.addEventListener('change', function (e) {
                                    if (!this.checked) return;
                                    var id = this.getAttribute("id");

                                    if (id.indexOf('type1') > -1) {
                                        toggleFieldset('type1Fieldset');
                                    }

                                    if (id.indexOf('type2') > -1) {
                                        openPopup('confirm', {
                                            content:'귀하의 사업장이<br> 아웃소싱입니까?',
                                            firstButton: '아니오',
                                            secondButton: '네',
                                            firstCallback: function () {
                                                toggleFieldset('type2Fieldset');
                                            },
                                            secondCallback: function () {
                                                toggleFieldset('type3Fieldset');
                                            }
                                        });

                                        setTimeout(function () {
                                            document.querySelector('.btn-close-popup').addEventListener('click', function (e) {
                                                document.querySelector('#companyType1').checked = true;
                                            },{once:true});
                                        }, 100);
                                    }
                                });
                            });
                        });
                    </script>

                    <!-- 사업자번호가 없을때 개인 -->
                    <fieldset id="type1Fieldset" class="form-fieldset" style="display:none;">
                        <div class="form-row">
                            <label>
                                <span class="form-label">대표자성명</span>
                                <input type="text" name="mm_comp_name1" placeholder="대표자성명을 입력해주세요" autocomplete="off" aria-required="true" required class="form-text" value=" ">
                            </label>
                        </div>

                        <!--div class="form-row">
                            <p class="form-label">인증방법선택</p>
                            <a href="#" class="btn-round btn-primary btn-auth">PASS 인증</a>
                        </div-->
                    </fieldset>

                    <!-- 사업자 아웃소싱 아닐때 -->
                    <fieldset id="type2Fieldset" hidden class="form-fieldset">
                        <div class="form-row">
                            <label>
                                <span class="form-label">사업장명</span>
                                <input type="text" name="mm_comp_name2" placeholder="사업장명을 입력해주세요" autocomplete="off" class="form-text">
                            </label>
                        </div>

                        <div class="form-row">
                            <span class="form-label">사업자 등록번호</span>
                            <div class="inline-group">
                                <label class="col">
                                    <span class="sr-text">사업자 등록번호입력</span>
                                    <input type="text" name="mm_comp_num2" placeholder="사업자 등록번호를 입력해주세요" autocomplete="off" class="form-text has-confirm">
                                </label>
                                <button type="button" name="btnBusno" data-type="2" class="btn-round btn-tertiary is-abs">인증</button>
                            </div>
                            <p  style="display:none;" id="msg2" class="help-msg is-fail">유효한 사업자등록번호가 아닙니다.</p>
                        </div>

                    </fieldset>

                    <!-- 사업자 아웃소싱일때(관리자가 없을경우) -->
                    <fieldset id="type3Fieldset" hidden class="form-fieldset">
                        <div class="form-row">
                            <label>
                                <span class="form-label">사업장명<span class="txt-color2">(아웃소싱)</span></span>
                                <input type="text" name="mm_comp_name3" placeholder="사업장명을 입력해주세요" autocomplete="off" class="form-text">
                            </label>
                        </div>

                        <div class="form-row">
                            <span class="form-label">사업자 등록번호</span>
                            <div class="inline-group">
                                <label class="col">
                                    <span class="sr-text">사업자 등록번호입력</span>
                                    <input type="text" name="mm_comp_num3" placeholder="사업자 등록번호를 입력해주세요" maxlength="20" autocomplete="off" class="form-text has-confirm">
                                </label>
                                <button type="button" name="btnBusno" data-type="3" class="btn-round btn-tertiary is-abs">인증</button>
                            </div>
                            <p style="display:none;" id="msg3" class="help-msg is-fail">유효한 사업자등록번호가 아닙니다.</p>
                        </div>

                        <div class="form-row">
                            <p class="form-label">사업자 등록증 업로드</p>
                            <div class="form-file js-show-filename">
                                <input type="text" disabled class="form-text file-name">
                                <label class="btn-round btn-tertiary">
                                    <span class="sr-text">사업자 등록증 선택</span>
                                    <input type="file" name="mm_comp_auth" accept="image/*,.pdf">
                                    <span>첨부</span>
                                </label>
                            </div>
                            <p class="help-msg">나우잡 관리자가 승인한뒤에 아웃소싱 업체로 사용가능합니다</p>
                        </div>
                    </fieldset>

                    <div class="form-action-wrap">
                        <button type="button" id="btnStep" class="btn-round btn-tertiary">다음</button>
                    </div>
					<input type="hidden" name="mm_comp_name" />
					<input type="hidden" name="mm_comp_num" />
					<input type="hidden" name="mm_comp_type" />
                </form>
            </div>

        </div>
    </main><!-- //page-content -->
</div><!-- //page -->

<script>
	$(document).ready(function(){
        var bus_yn = false;
        $("[name=btnBusno]").click(function(){
            var type = $(this).attr("data-type");
            var data = {
                "b_no": [$("[name=mm_comp_num"+type+"]").val()] // 사업자번호 "xxxxxxx" 로 조회 시,
            };

            //데이터 확인
            if($("[name=mm_comp_num"+type+"]").val()==""){
                commonAlert("사업자번호를 입력해주세요.",function(){
                    $("[name=mm_comp_num"+type+"]").focus();
                });
                return;
            }

            $.ajax({
                url: "https://api.odcloud.kr/api/nts-businessman/v1/status?serviceKey=<?=$bus_key["인증키"]?>",  // serviceKey 값을 xxxxxx에 입력
                type: "POST",
                data: JSON.stringify(data), // json 을 string으로 변환하여 전송
                dataType: "JSON",
                contentType: "application/json",
                accept: "application/json",
                success: function(result) {
                    if(result.status_code=="OK"){
                        if(result.data[0].b_stt_cd == "01" || result.data[0].b_stt_cd == "01"){
                            bus_yn = true;
                            $("#msg" + type).show();
                            $("#msg" + type).html("사업자번호 인증완료");
                        }else if(result.data[0].b_stt_cd == "03"){
                            bus_yn = false;
                            $("#msg" + type).show();
                            $("#msg" + type).html("폐업된 사업자 번호입니다.");
                        }else{
                            bus_yn = false;
                            $("#msg" + type).show();
                            $("#msg" + type).html("등록되지 않은 사업자 번호입니다.");
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
        });

        $("[name=mm_comp_num2]").keyup(function(){
            bus_yn = false;
            $("#msg2").hide();
        });

        $("[name=mm_comp_num3]").keyup(function(){
            bus_yn = false;
            $("#msg3").hide();
        });


		$("#btnStep").click(function(){
			fNextClick();
		});
		
		function fNextClick(){
			var mm_comp_name = "";
			var mm_comp_num = "";
			var params = {};

			params["proc_type"] = "join_comp_step2";

			if($("[name='mem_comp_type']").is(":checked")==false){
				commonAlert("개인또는 사업자를 선택해주세요.",function(){
				});
				return;
			}

			if(current_type == "type1Fieldset"){
				//데이터 확인
				if($("[name=mm_comp_name1]").val()==""){
					commonAlert("대표자성명을 입력해주세요.",function(){
						$("[name=mm_comp_name1]").focus();
					});
					return;
				}

				$("[name=mm_comp_type]").val("PERSON");
				$("[name=mm_comp_name]").val($("[name=mm_comp_name1]").val());
				$("[name=mm_comp_num]").val("");
			}else if(current_type == "type2Fieldset"){
				//데이터 확인
				if($("[name=mm_comp_name2]").val()==""){
					commonAlert("사업자명을 입력해주세요.",function(){
						$("[name=mm_comp_name2]").focus();
					});

					return;
				}

				//데이터 확인
				if($("[name=mm_comp_num2]").val()==""){
					commonAlert("사업자번호를 입력해주세요.",function(){
						$("[name=mm_comp_num2]").focus();
					});
					return;
				}

                if(bus_yn==false){
                    commonAlert("사업자 번호 인증을 진행해주세요.",function(){
						$("[name=mm_comp_num2]").focus();
					});
					return;
                }

				$("[name=mm_comp_type]").val("COMP");
				$("[name=mm_comp_name]").val($("[name=mm_comp_name2]").val());
				$("[name=mm_comp_num]").val($("[name=mm_comp_num2]").val());
			}else if(current_type == "type3Fieldset"){
				//데이터 확인
				if($("[name=mm_comp_name3]").val()==""){
					commonAlert("사업자명을 입력해주세요.",function(){
						$("[name=mm_comp_name3]").focus();
					});
					return;
				}

				//데이터 확인
				if($("[name=mm_comp_num3]").val()==""){
					commonAlert("사업자번호를 입력해주세요.",function(){
						$("[name=mm_comp_num3]").focus();
					});
					return;
				}

                if(bus_yn==false){
                    commonAlert("사업자 번호 인증을 진행해주세요.",function(){
						$("[name=mm_comp_num3]").focus();
					});
					return;
                }

				//데이터 확인
				if($("[name=mm_comp_auth]").val()==""){
					commonAlert("사업자 등록증을 업로드해주세요.",function(){
						$("[name=mm_comp_auth]").focus();
					});
					return;
				}

				$("[name=mm_comp_type]").val("OUT");
				$("[name=mm_comp_name]").val($("[name=mm_comp_name3]").val());
				$("[name=mm_comp_num]").val($("[name=mm_comp_num3]").val());
			}

			var form = $('#frm');
            var params = new FormData(form[0]);
            fileAjax("/member_proc/join_comp_step2",params,function(ret){
                if(ret.code=="00"){
                    location.href="/member/join_wcomp_step2";
                }else{
                	commonAlert(ret.msg,function(){});
                }
            });
		}

		fNextClick();
	});
</script>
