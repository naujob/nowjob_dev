<div class="page page-join member-type2">

    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <!-- <h1 class="title">기업회원 회원가입</h1> -->
		<h1 class="title">비밀번호 만들기</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <div class="container">

            <!-- <h2 class="primary-title align-center">사용하실 <em>계정정보</em>를 작성해주세요</h2> -->
			<h2 class="primary-title align-center">사용하실 <em>비밀번호</em>를 만들어 주세요</h2>

            <div class="account-info-form">
               <form id="frm" onsubmit="return false">
					<input type="hidden" name="proc_type" value="join_comp_step2" />
                    <div class="form-row" style="display:none;">
                        <div class="form-toggle-group">
                            <label class="form-toggle-btn">
                                <input type="radio" name="mem_comp_type" id="mem_comp_type1" value="PERSON" onchange="toggleFieldset('type1Fieldset');">
                                <span class="btn">
                                    <!-- 20221022 수정 -->
                                    <span class="ico member-type3"></span>
                                    <span>사업자 번호가 없는 경우</span>
                                    <strong>개인</strong>
                                </span>
                            </label>

                            <label class="form-toggle-btn">
                                <input type="radio" name="mem_comp_type" id="mem_comp_type2" value="COMP" onchange="openPanel(document.getElementById('chkTypePanel'));">
                                <span class="btn">
                                    <!-- 20221022 수정 -->
                                    <span class="ico member-type2"></span>
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
                        	// 타입설정
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
                        document.querySelector('form').reset();
                    </script>

                    <!-- 사업자번호가 없을때 개인 -->
                    <fieldset id="type1Fieldset"  class="form-fieldset">
                        <div class="form-row">
                            <label>
                                <span class="form-label">사업장명</span>
                                <input type="text" name="mm_comp_name1" placeholder="사업장명을 입력해주세요" autocomplete="off" class="form-text">
                            </label>
                        </div>

                        <!--div class="form-row">
                            <p class="form-label">인증방법선택</p>
                            <a href="#" class="btn btn-tertiary btn-auth">Pass 인증</a>
                        </div-->
                    </fieldset>

                    <!-- 사업자 아웃소싱 아닐때 -->
                    <fieldset id="type2Fieldset" hidden class="form-fieldset">
                        <div class="form-row">
                            <label>
                                <span class="form-label">사업장명</span>
                                <input type="text" name="mm_comp_name2" placeholder="사업장명을 입력해주세요" autocomplete="off" aria-required="true" required class="form-text">
                            </label>
                        </div>

                        <div class="form-row">
                            <span class="form-label">사업자 등록번호</span>
                            <div class="inline-group">
                                <label class="col">
                                    <span class="sr-text">사업자 등록번호입력</span>
                                    <input type="text" name="mm_comp_num2" maxlength="14" placeholder="사업자 등록번호를 입력해주세요" autocomplete="off" aria-required="true" required class="form-text num">
                                </label>
                                <button type="button" name="btnBusno" data-type="2"  class="btn btn-tertiary">인증</button>
                            </div>
                            <!-- 폼입력 실패 안내 -->
                            <p style="display:none;" id="msg2"  class="help-msg is-fail hidden">유효한 사업자등록번호가 아닙니다.</p>

                        </div>

                    </fieldset>

                    <!-- 사업자 아웃소싱일때 2 -->
                    <fieldset id="type3Fieldset" hidden class="form-fieldset">
                        <div class="form-row">
                            <label>
                                <span class="form-label">사업장명<span class="important-word">(아웃소싱)</span></span>
                                <input type="text" name="mm_comp_name3" placeholder="사업장명을 입력해주세요" autocomplete="off" aria-required="true" required class="form-text">
                            </label>
                        </div>

                        <div class="form-row">
                            <span class="form-label">사업자 등록번호</span>
                            <div class="inline-group">
                                <label class="col">
                                    <span class="sr-text">사업자 등록번호입력</span>
                                    <input type="text" name="mm_comp_num3" maxlength="14"   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="사업자 등록번호를 입력해주세요" autocomplete="off" aria-required="true" required class="form-text num">
                                </label>
                                <button type="button" name="btnBusno" data-type="3"  class="btn btn-tertiary">인증</button>
                            </div>
                            <p style="display:none;" id="msg3"  class="help-msg is-fail hidden">유효한 사업자등록번호가 아닙니다.</p>
                        </div>

                        <div class="form-row">
                            <p class="form-label">사업자 등록증 업로드</p>
                            <div class="form-file js-show-filename">
                                <input type="text" disabled class="form-text file-name">
                                <label class="btn btn-tertiary">
                                    <span class="sr-text">사업자 등록증 선택</span>
                                    <input type="file" name="mm_comp_auth" accept="image/*,.pdf">
                                    <span>첨부</span>
                                </label>
                            </div>
                            <p class="help-msg text-only">나우잡 관리자가 승인한 뒤에 아웃소싱 업체로 사용가능합니다</p>
                        </div>
                    </fieldset>

                    <div class="sticky-bottom view-footer">
                        <button type="button" id="btnStep" class="btn btn-primary">다음</button>
                    </div>
					<input type="hidden" name="mm_comp_name" />
					<input type="hidden" name="mm_comp_num" />
					<input type="hidden" name="mm_comp_type" />
                </form>
            </div>

        </div>
    </div><!-- //page-content -->

</div>

<!-- 아웃소싱 사업자 확인 -->
<div id="chkTypePanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="panel-title sr-text">아웃소싱 사업자 확인</h2>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="confirm-msg">
                    <p>귀하의 사업장이<br> <em class="point-word">아웃소싱</em>입니까?</p>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom view-footer">
                <button type="button" aria-controls="chkTypePanel" onclick="toggleFieldset('type2Fieldset');" class="btn btn-secondary js-toggle-panel">아니오</button>
                <button type="button" aria-controls="chkTypePanel" onclick="toggleFieldset('type3Fieldset');" class="btn btn-primary js-toggle-panel">네</button>
            </div>
        </div>
    </div>
</div>
<!-- //아웃소싱 사업자 확인 -->
<script>
	//$(document).ready(function(){
  window.addEventListener('load', function(){

		location.href = './join_comp_step3';

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
					commonAlert("사업자명을 입력해주세요.",function(){
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
                    location.href="/member/join_comp_step3";
                }else{
                	commonAlert(ret.msg,function(){});
                }
            });
		});
	});
</script>
