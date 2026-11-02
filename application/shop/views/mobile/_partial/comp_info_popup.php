<?php
// 모바일 기업정보 입력 팝업
// 기업회원일 때만 표시되는 기업정보 입력 팝업

// 공통 바텀시트 템플릿 로드
require_once __DIR__ . '/bottom_sheet_template.php';
?>
<style>
</style>

<!-- 기업정보 입력 바텀시트 -->
<div id="compInfoPanel" class="layer-panel card-type" tabindex="-1">
  <div class="bottom-sheet-container">
    <!-- 드래그 핸들바 -->
    <div class="bottom-sheet-handlebar"></div>

    <!-- 우측 상단 X 닫기 버튼 -->
    <button type="button" class="bottom-sheet-close-btn" aria-label="닫기" onclick="event.stopPropagation(); closePanel(this.closest('.layer-panel'));"></button>

    <!-- 헤더 -->
    <div class="bottom-sheet-header">
      <h2 class="bottom-sheet-title">사업자정보 인증</h2>
    </div>

    <!-- 본문 -->
    <div class="bottom-sheet-body" onclick="event.stopPropagation();">
      <div class="bottom-sheet-content">
        <form id="frm1" name="frm1" onsubmit="return false" onclick="event.stopPropagation();">
          <input type="hidden" id="compUpdateType" name="compUpdateType">

          <!-- 사업자 등록번호 -->
          <div class="biznobox" style="margin-top: 0;">
            <input type="text" name="comp_num" id="bizno" placeholder="사업자 등록번호를 입력해 주세요" style="width: 100%; height: 50px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 16px; padding: 0 15px; box-sizing: border-box; background-color: #fff;" onclick="event.stopPropagation();">
            <input type="hidden" id="biznoauth">
            <input type="hidden" name="comp_type" id="comp_type" value="COMP">
          </div>

          <!-- 사업자명 -->
          <div class="compnamebox" style="margin-top: 10px;">
            <input type="text" name="comp_name" id="comp_name" placeholder="사업자명을 입력해 주세요" style="width: 100%; height: 50px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 16px; padding: 0 15px; box-sizing: border-box; background-color: #fff;" onclick="event.stopPropagation();">
            <input type="hidden" name="mm_comp_type">
          </div>

          <?php /* 주석처리 - 사업자 등록번호와 사업장명만 사용
          <!-- 이메일주소 (XX 타입) -->
          <div class="emailboxXX" style="display:none; margin-top: 30px;">
            <div style="font-size: 1rem; margin-bottom: 15px;">
              <span style="color:red;">*</span>이메일주소
            </div>
            <input type="text" name="emailid" placeholder="" style="width: 100%; height: 32px; border: none; border-bottom: 1px solid #ccc; font-size: 16px; padding: 5px 0;">
            <input type="hidden" name="mm_comp_type">
          </div>

          <!-- 이메일주소 (일반 타입) -->
          <div class="emailbox" style="display:none; margin-top: 30px;">
            <div style="font-size: 1rem; margin-bottom: 15px;">
              <span style="color:red;">*</span>이메일주소
            </div>
            <div style="display: flex; align-items: center; gap: 5px; flex-wrap: wrap;">
              <input type="text" name="mm_email_id" id="mm_email_id" placeholder="이메일 입력" style="flex: 1; min-width: 100px; height: 32px; border: none; border-bottom: 1px solid #ccc; font-size: 16px; padding: 5px 0;">
              <span style="font-size: 16px;">@</span>
              <input type="text" name="mm_email_domain" id="mm_email_domain" placeholder="" style="flex: 1; min-width: 100px; height: 32px; border: none; border-bottom: 1px solid #ccc; font-size: 16px; padding: 5px 0;">
              <select id="mm_email_domain_sel" style="flex: 1; min-width: 120px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; color: #666;" onchange="$('#mm_email_domain').val($(this).val());">
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
            </div>
            <input type="hidden" name="mm_email" id="mm_email">
          </div>

          <!-- 아웃소싱 안내 -->
          <div class="outguide" style="display:none; margin-top: 30px; padding: 15px; background-color: #f5f5f5; border-radius: 8px;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
              <span style="font-size: 1rem; flex: 1;">아웃소싱업체일 경우 확인을 클릭해 주세요.</span>
              <button type="button" onclick="$('.outguide').hide();$('.bizimgbox').show(); $('#comp_type').val('OUT');" style="padding: 8px 16px; font-size: 16px; color: #fff; background-color: #57CCAF; border: none; border-radius: 4px; white-space: nowrap;">확인</button>
            </div>
          </div>

          <!-- 사업자 등록증 업로드 -->
          <div class="bizimgbox" style="display:none; margin-top: 30px;">
            <div style="font-size: 1rem; margin-bottom: 15px;">사업자 등록증 업로드</div>
            <div style="display: flex; align-items: center; gap: 10px;">
              <span id="file-name" style="flex: 1; font-size: 15px; color: #aaa; font-weight: 100; border-bottom: 1px solid #ccc; padding: 8px 0; min-height: 32px; line-height: 16px;">&nbsp;</span>
              <label for="file-upload" style="font-size: 16px; padding: 8px 16px; background-color: #57CCAF; color: #fff; border: none; border-radius: 4px; cursor: pointer; white-space: nowrap;">첨부</label>
              <input type="file" name="mm_comp_auth" id="file-upload" style="display: none;" onchange="updateFileName(this)">
            </div>
          </div>

          <!-- 아웃소싱 도움말 -->
          <div class="sousinghelp" style="display:none; font-size: 1rem; margin-top: 15px; padding: 15px; background-color: #e8f5f0; border-radius: 8px; color: #57CCAF; line-height: 1.6;">
            아웃소싱 업체는 하나의 사업자등록번호로 여러명이 가입이 가능합니다.<br>
            나우잡 관리자가 승인한 뒤에 아웃소싱 업체로 사용 가능합니다.
          </div>
          */ ?>

          <?php /* 주석처리 - 사용하지 않음
          <div class="addrbox" style="display:none; margin-top: 30px;">
            <div style="font-size: 1rem; margin-bottom: 15px;">사업장주소</div>
            <input type="text" id="mm_addr" name="mm_addr" placeholder="사업장 주소를 입력해 주세요" style="width: 100%; height: 32px; border: none; border-bottom: 1px solid #ccc; font-size: 16px; padding: 5px 0;" onclick="$('#changeArea').addClass('on');">
            <input type="hidden" id="mm_zipcode" name="mm_zipcode">
            <input type="hidden" id="mm_doro" name="mm_doro">
            <input type="hidden" id="mm_jibun" name="mm_jibun">
          </div>
          */ ?>
        </form>
      </div>
    </div>

    <!-- 푸터 -->
    <div class="bottom-sheet-footer">
      <div class="bottom-sheet-sticky-bottom">
        <button type="button" id="btnBizAuth" onclick="fBizAuth();" class="btn-bottom-sheet btn-bottom-sheet-primary bottom-sheet-action">확인</button>
        <?php /* 주석처리 - 완료 버튼
        <button id="btnSave" class="btn-bottom-sheet-primary" style="width: 100%; height: 50px; font-size: 16px; border-radius: 8px;">완료</button>
        */ ?>
      </div>
    </div>
  </div>
</div>

<?php
// 폐업된 사업자 번호 바텀시트
renderBottomSheet(
  'bizAuthClosedPanel',
  '사업자등록 인증',
  '<p>폐업된 사업자 번호입니다.</p><p>새로운 사업자번호를 등록해주세요.</p>',
  '수정',
  'btnAuthClosedModify',
  'closeBizAuthClosedPanelAndReopen();'
);

?>

<script>
  // 파일명 업데이트 함수
  function updateFileName(input) {
    if (!input.files || !input.files[0]) return;
    var fileName = input.files[0].name;
    document.getElementById('file-name').innerText = fileName;
  }

  // 바텀시트 스와이프 기능 초기화
  if (typeof initPanelSwipe === 'function') {
    initPanelSwipe('compInfoPanel');
    initPanelSwipe('bizAuthClosedPanel');
  }
</script>

<script>
  // 사업자번호 인증 함수
  function fBizAuth() {
    if ($('#bizno').val() == '') {
      commonAlert("사업자 등록번호를 입력해주세요.", function() {
        $("#bizno").focus();
      });
      return;
    }

    // 사업자명은 외부 API 호출 전에 먼저 검사
    if ($("#comp_name").val() == '' || $("#comp_name").val().trim() == '') {
      commonAlert("사업자명을 입력해주세요.", function() {
        $("#comp_name").focus();
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
        console.log(result);
        if (result.status_code == "OK") {
          if (result.data[0].b_stt_cd == "01") {
            // 정상적인 사업자 번호인 경우 - 완료 바텀시트 없이 바로 저장
            $("#biznoauth").val($("#bizno").val());
            fBizSave();
            return;
          } else if (result.data[0].b_stt_cd == "03") {
            // 폐업된 사업자 번호인 경우
            openBizAuthClosedPanel();
            return;
          } else {
            // 등록되지 않은 사업자 번호인 경우 - 알림 후 사업자등록번호 입력란 초기화
            commonAlert("등록되지 않은 사업자등록번호입니다.<br>사업자등록번호를 다시 입력해주세요.", function() {
              $('#bizno').val('');
              $('#biznoauth').val('');
              $('#bizno').focus();
            });
            return;
          }
        } else {
          commonAlert("사업자 번호가 유효 하지 않습니다.", function() {});
        }
      },
      error: function(result) {
        commonAlert("사업자번호 인증 중 오류가 발생했습니다.", function() {});
      }
    });
  }

  // 폐업된 사업자 번호 바텀시트 열기 함수
  function openBizAuthClosedPanel() {
    var $panel = document.getElementById('bizAuthClosedPanel');
    if ($panel && typeof openPanel === 'function') {
      openPanel($panel);
    }
  }

  // 폐업된 사업자 번호 바텀시트 닫기 및 사업자등록 인증 팝업 다시 열기
  function closeBizAuthClosedPanelAndReopen() {
    var $closedPanel = document.getElementById('bizAuthClosedPanel');
    var $compInfoPanel = document.getElementById('compInfoPanel');

    // 현재 바텀시트 닫기
    if ($closedPanel && typeof closePanel === 'function') {
      closePanel($closedPanel);
    }

    // 사업자번호 입력 필드 초기화
    $('#bizno').val('');
    $('#biznoauth').val('');

    // 사업자등록 인증 팝업 다시 열기
    setTimeout(function() {
      if ($compInfoPanel && typeof openPanel === 'function') {
        openPanel($compInfoPanel);
        // 포커스 설정
        setTimeout(function() {
          $('#bizno').focus();
        }, 300);
      }
    }, 300);
  }

  // 기업정보 수정 타입 설정 함수
  function fMemUpdateType(type) {
    var $panel = document.getElementById('compInfoPanel');

    if (type == 'compname') {
      $('.compnamebox').show();
      $('.biznobox').hide();
      // 주석처리된 필드 숨기기
      // $('.bizimgbox').hide();
      // $('.sousinghelp').hide();
      // $('.addrbox').hide();
    } else if (type == 'bizno') {
      $('.compnamebox').hide();
      $('.biznobox').show();
      // 주석처리된 필드 숨기기
      // $('.bizimgbox').hide();
      // $('.sousinghelp').hide();
      // $('.addrbox').hide();
    } else if (type == 'address') {
      // 주석처리 - 주소 입력 기능 제거
      // $('.compnamebox').hide();
      // $('.biznobox').hide();
      // $('.bizimgbox').hide();
      // $('.sousinghelp').hide();
      // $('.addrbox').show();
      return; // 주소 입력 기능 미사용
    } else {
      // all 타입: 사업자 등록번호와 사업장명 모두 표시
      $('.compnamebox').show();
      $('.biznobox').show();
      // 주석처리된 필드 숨기기
      // $('.addrbox').show();
      $('input[name="comp_type"]').val('COMP');
    }
    $('#compUpdateType').val(type);

    // 바텀시트 열기
    if ($panel && typeof openPanel === 'function') {
      openPanel($panel);
    }
  }

  // 바텀시트 열기 함수 (외부에서 호출 가능)
  function openCompInfoPanel() {
    var $panel = document.getElementById('compInfoPanel');
    if ($panel && typeof openPanel === 'function') {
      openPanel($panel);
    }
  }

  // 바텀시트 닫기 함수 (외부에서 호출 가능)
  function closeCompInfoPanel() {
    var $panel = document.getElementById('compInfoPanel');
    if ($panel && typeof closePanel === 'function') {
      closePanel($panel);
    }
  }

  // 사업자번호 조회 성공 후 호출: 사업자정보를 저장하고 인증 완료 처리
  // 완료 콜백(window.__compCertDone)이 있으면 실행, 없으면 페이지 새로고침
  function fBizSave() {
    // compUpdateType이 설정되지 않은 경우 기본값 설정
    if ($('#compUpdateType').val() == '') {
      $('#compUpdateType').val('bizno');
    }

    // 기본값 설정
    $('input[name="comp_type"]').val('COMP');

    // 폼 데이터 전송
    var form = $('#frm1');
    var params = new FormData(form[0]);

    fileAjax("/compage_proc/com_biz_update", params, function(ret) {
      if (ret.code == "00") {
        // 기업정보 입력 바텀시트 닫기
        closeCompInfoPanel();
        // 기업 인증 정보 갱신
        if (window.NOWJOB_COMP_INFO) {
          window.NOWJOB_COMP_INFO.hasCompCert = true;
        }
        // 인증 후 이어갈 동작이 있으면 실행, 없으면 페이지 갱신
        var done = window.__compCertDone;
        window.__compCertDone = null;
        if (typeof done === 'function') {
          done();
        } else {
          location.reload();
        }
      } else {
        commonAlert(ret.msg || "인증 처리 중 오류가 발생했습니다.", function() {});
      }
    });
  }

  $(document).ready(function() {
    // 폐업된 사업자 번호 수정 버튼 클릭 이벤트
    $(document).on('click', '#btnAuthClosedModify', function() {
      closeBizAuthClosedPanelAndReopen();
    });


    // 기업정보 저장 버튼 클릭 이벤트 (주석처리됨 - 완료 버튼이 주석처리되어 있음)
    /*
    $(document).on('click', '#btnSave', function() {
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
        commonAlert("사업장명을 선택해주세요.", function() {
          $("[name=comp_name]").focus();
        });
        return;
      }

      // 주석처리 - 이메일 관련 검증 제거
      // $("[name=mm_email]").val($("[name=mm_email_id]").val() + '@' + $("[name=mm_email_domain]").val());

      // 주석처리 - 주소 관련 검증 제거
      // if ($('.addrbox').is(':visible') && $("[name=mm_addr]").val() == "") {
      //   commonAlert("사업장 주소를 입력해주세요.", function() {
      //     $("[name=mm_addr]").focus();
      //   });
      //   return;
      // }

      // 주석처리 - 아웃소싱 관련 검증 제거
      // if ($('input[name="comp_type"]').val() == 'OUT') {
      //   if ($("#file-name").html() == "&nbsp;") {
      //     commonAlert("사업자 등록증을 첨부해주세요.", function() {
      //       $("[name=mm_comp_auth]").focus();
      //     });
      //     return;
      //   }
      // }

      // 주석처리 - 주소 관련 처리 제거
      // if ($('.addrbox').is(':visible') && $("[name=compUpdateType]").val() != "all") {
      //   $("[name=compUpdateType]").val('addr');
      // }

      // 주석처리 - 아웃소싱 관련 처리 제거
      // if ($('.bizimgbox').is(':visible')) {
      //   $('input[name="comp_type"]').val('OUT');
      // } else {
      //   $('input[name="comp_type"]').val('COMP');
      // }

      // 기본값 설정
      $('input[name="comp_type"]').val('COMP');

      var form = $('#frm1');
      var params = new FormData(form[0]);
      fileAjax("/compage_proc/com_biz_update", params, function(ret) {
        if (ret.code == "00") {
          commonAlert("수정 되었습니다.", function() {
            // 바텀시트 닫기
            closeCompInfoPanel();
            // 기업 인증 정보 갱신
            if (window.NOWJOB_COMP_INFO) {
              window.NOWJOB_COMP_INFO.hasCompCert = true;
            }
            location.reload();
          });
        } else {
          commonAlert(ret.msg, function() {});
        }
      });
    });
    */
  });
</script>
