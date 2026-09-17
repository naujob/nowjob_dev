<div class="page page-join member-type2">
   <div class="top-bar">
       <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
       <h1 class="title">기업 회원가입</h1>
   </div><!-- //top-bar -->

   <div class="page-content">

       <h2 class="primary-title align-center">이용약관 동의</h2>

       <div class="terms-list">
           <ul class="list">
                 <li class="selectable-item">
                   <label class="form-checkbox">
                       <input type="checkbox" name="rd_agree1" value="1" onclick="fClickChk();">
                       <span class="label"></span>
                       <span class="sr-text">선택</span>
                   </label>
                    <a href="#terms1Panel" role="button" aria-haspopup="dialog" aria-controls="terms1Panel" class="angle-link js-toggle-panel">서비스 이용약관(필수)</a>
                </li>
                <li class="selectable-item">
                  <label class="form-checkbox">
                      <input type="checkbox" name="rd_agree2" value="2" onclick="fClickChk();">
                      <span class="label"></span>
                      <span class="sr-text">선택</span>
                  </label>
                    <a href="#terms2Panel" role="button" aria-haspopup="dialog" aria-controls="terms2Panel" class="angle-link js-toggle-panel">개인정보 처리방침(필수)</a>
                </li>
                <li class="selectable-item">
                  <label class="form-checkbox">
                      <input type="checkbox" name="rd_agree3" value="3" onclick="fClickChk();">
                      <span class="label"></span>
                      <span class="sr-text">선택</span>
                  </label>
                    <a href="#terms3Panel" role="button" aria-haspopup="dialog" aria-controls="terms3Panel" class="angle-link js-toggle-panel">위치 서비스 이용약관(필수)</a>
                </li>
                <li class="selectable-item">
                  <label class="form-checkbox">
                      <input type="checkbox" name="rd_agree4" value="4" onclick="fClickChk();">
                      <span class="label"></span>
                      <span class="sr-text">선택</span>
                  </label>
                    <a href="#terms4Panel" role="button" aria-haspopup="dialog" aria-controls="terms4Panel" class="angle-link js-toggle-panel">개인정보 수집 및 이용 동의(선택)</a>
                </li>
                <li class="selectable-item">
                  <label class="form-checkbox">
                      <input type="checkbox" name="rd_agree5" value="5" onclick="fClickChk();">
                      <span class="label"></span>
                      <span class="sr-text">선택</span>
                  </label>
                    <a href="#terms5Panel" role="button" aria-haspopup="dialog" aria-controls="terms5Panel" class="angle-link js-toggle-panel">맞춤형 서비스/광고 개인정보 수집 및 이용 동의(선택)
                    	<br/><span style="color:#999;font-weight:normal;">(AI매칭 등 다양한 혜택 알림 제공)</span></a>
                </li>
                <li class="selectable-item" style="border-bottom: none;">
                  <label class="form-checkbox">
                      <input type="checkbox" name="rd_agree0" value="0" onclick="toggleAll(this);">
                      <span class="label"></span>
                      <span class="sr-text">선택</span>
                  </label>
                  <span style="height: 60px; display: block; line-height: 54px; margin-left: 67px; font-weight: 600;">모두 동의하고 시작</span>
                </li>
            </ul>
       </div>

       <div class="sticky-bottom view-footer">
           <button type="button" id="btnStep" class="btn btn-primary" style="background-color:#ccc;">다음</button>
       </div>

   </div><!-- //page-content -->

</div>

<!-- 서비스 이용약관 -->
<div id="terms1Panel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <!-- 약관제목 -->
            <h2 class="panel-title">서비스 이용약관(필수)</h2>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <!-- 약관내용 -->
                <div class="terms-wrap">
                    <?=$policy1["policy2_m"]?>
                </div>
                <!-- //약관내용 -->

            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom view-footer">
                <button type="button" aria-controls="terms1Panel" class="btn btn-primary js-toggle-panel" onclick="checkContent(1);">확인</button>
            </div>
        </div>
    </div>
</div>
<!-- //서비스 이용약관 -->

<!-- 개인정보 처리방침 -->
<div id="terms2Panel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <!-- 약관제목 -->
            <h2 class="panel-title">개인정보 처리방침(필수)</h2>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <!-- 약관내용 -->
                <div class="terms-wrap">
                    <?=$policy2["privacy_m"]?>
                </div>
                <!-- //약관내용 -->

            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom view-footer">
                <button type="button" aria-controls="terms2Panel" class="btn btn-primary js-toggle-panel" onclick="checkContent(2);">확인</button>
            </div>
        </div>
    </div>
</div>
<!-- //개인정보 처리방침 -->

<!-- 제3자 개인정보 수집동의 -->
<div id="terms3Panel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <!-- 약관제목 -->
            <h2 class="panel-title">위치 서비스 이용약관(필수)</h2>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <!-- 약관내용 -->
                <div class="terms-wrap">
                    <?=$policy3["location_m"]?>
                </div>
                <!-- //약관내용 -->

            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom view-footer">
                <button type="button" aria-controls="terms3Panel" class="btn btn-primary js-toggle-panel" onclick="checkContent(3);">확인</button>
            </div>
        </div>
    </div>
</div>
<!-- //제3자 개인정보 수집동의 -->

<!-- 위치정보이용 -->
<div id="terms4Panel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <!-- 약관제목 -->
            <h2 class="panel-title">개인정보 수집 및 이용 동의(선택)</h2>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <!-- 약관내용 -->
                <div class="terms-wrap">
                    <?=$policy4["privacy3_m"]?>
                </div>
                <!-- //약관내용 -->

            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom view-footer">
                <button type="button" aria-controls="terms4Panel" class="btn btn-primary js-toggle-panel" onclick="checkContent(4);">확인</button>
            </div>
        </div>
    </div>
</div>
<!-- //위치정보이용 -->


<!-- 위치정보이용 -->
<div id="terms5Panel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <!-- 약관제목 -->
            <h2 class="panel-title">개인정보 수집 및 이용 동의(선택)</h2>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <!-- 약관내용 -->
                <div class="terms-wrap">
                     <?=$policy5["adv_m"]?>
                </div>
                <!-- //약관내용 -->

            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom view-footer">
                <button type="button" aria-controls="terms5Panel" class="btn btn-primary js-toggle-panel" onclick="checkContent(5);">확인</button>
            </div>
        </div>
    </div>
</div>
<!-- //위치정보이용 -->

<!-- //계정 이용 정지 규정 -->
<script>
	//$(document).ready(function(){
  window.addEventListener('load', function(){
		$("#btnStep").click(function(){
      var agree1 = 'N';
      var agree2 = 'N';
      var agree3 = 'N';
      var agree4 = 'N';
      var agree5 = 'N';
      if ($('input[name="rd_agree1"]:checked').length > 0) {
              agree1 = 'Y';
              console.log('rd_agree1 is checked');
      } else {
        commonAlert("서비스 이용약관(필수)을 동의해주세요",function(){});
        return;
      }
      if ($('input[name="rd_agree2"]:checked').length > 0) {
              agree2 = 'Y';
              console.log('rd_agree2 is checked');
      } else {
        commonAlert("개인정보 처리방침(필수)을 동의해주세요",function(){});
        return;
      }
      if ($('input[name="rd_agree3"]:checked').length > 0) {
              agree3 = 'Y';
              console.log('rd_agree3 is checked');
      } else {
        commonAlert("위치 서비스 이용약관(필수)을 동의해주세요",function(){});
        return;
      }
      if ($('input[name="rd_agree4"]:checked').length > 0) {
              agree4 = 'Y';
              console.log('rd_agree4 is checked');
      }
      if ($('input[name="rd_agree5"]:checked').length > 0) {
              agree5 = 'Y';
              console.log('rd_agree5 is checked');
      }

			var params = {};
      params["proc_type"] = "join_comp_step5";
      params["agree1"] = agree1;
      params["agree2"] = agree2;
      params["agree3"] = agree3;
      params["agree4"] = agree4;
      params["agree5"] = agree5;
      comAjax("/member_proc/join_comp_step5",params,function(ret){
        if (ret.code === "00") {
          // step5 완료 후 곧바로 회원가입 완료 (PART)
          fPassClickFromStep5();
        } else {
          commonAlert((ret && ret.msg) ? ret.msg : "약관 동의 처리에 실패했습니다.", function(){});
        }
      });

		});
	});

  function fClickChk() {
      // 모든 체크박스를 선택
      const checkboxes = $('input[name^="rd_agree"]');
      const agreeCheckboxes = checkboxes.not('[value="0"]'); // value="0" 제외
      const agreeAllChecked = agreeCheckboxes.filter(':checked').length === agreeCheckboxes.length; // 1~5 모두 체크 확인

      if (agreeAllChecked) {
          // 1~5 모두 체크된 경우
          $('input[name="rd_agree0"]').prop('checked', true); // 0번 체크 활성화
      } else {
          // 1~5 중 하나라도 체크되지 않은 경우
          $('input[name="rd_agree0"]').prop('checked', false); // 0번 체크 비활성화
      }

      //check
      if($('input[name="rd_agree1"]').prop('checked') &&
         $('input[name="rd_agree2"]').prop('checked') &&
         $('input[name="rd_agree3"]').prop('checked')){
           $('#btnStep').css('background-color', '#57CCAF'); // 버튼 활성화 색상 변경
      }else{
           $('#btnStep').css('background-color', '#ccc'); // 버튼 비활성화 색상 변경
      }

  }

  // toggleAll 함수: rd_agree0 상태에 따라 전체 체크/해제
  function toggleAll(checkbox) {
      const isChecked = $(checkbox).prop('checked');
      $('input[name^="rd_agree"]').not('[value="0"]').prop('checked', isChecked); // 1~5 체크 상태 변경
      fClickChk(); // 상태 변경 후 fClickChk 호출
  }

  // checkContent 함수: rd_agree1 체크하고 fClickChk 호출
  function checkContent(arg) {
      $('input[name="rd_agree'+arg+'"]').prop('checked', true); // rd_agree1 체크
      fClickChk(); // 상태 변경 후 fClickChk 호출
  }

  // step5에서 회원가입 완료 처리 (PART)
  function fPassClickFromStep5(){
    var params = {};
    params["ma_type"] = "COMP";
    params["proc_type"] = "PART";
    params["uuid"] = "<?=$uuid?>";
    try {
      var joinType = localStorage.getItem("join_sns_type") || sessionStorage.getItem("join_sns_type") || "";
      var joinKey = localStorage.getItem("join_sns_key") || sessionStorage.getItem("join_sns_key") || "";
      if (joinType) params["join_type"] = joinType;
      if (joinKey) params["join_key"] = joinKey;
    } catch (e) {}

    comAjax("/member_proc/join_complete_proc",params,function(ret){
      if(ret.code=="00"){
        try {
          localStorage.removeItem("join_sns_type");
          localStorage.removeItem("join_sns_key");
          sessionStorage.removeItem("join_sns_type");
          sessionStorage.removeItem("join_sns_key");
          document.cookie = "join_sns_type=; path=/; max-age=0";
          document.cookie = "join_sns_key=; path=/; max-age=0";
        } catch (e) {}
        location.href="/member/join_comp_finish";
      }else{
        commonAlert(ret.msg,function(){});
        return;
      }
    });
  }

</script>
