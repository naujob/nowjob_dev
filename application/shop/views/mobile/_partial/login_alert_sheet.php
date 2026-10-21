<?php
/**
 * 로그인 페이지 알림 바텀시트 (commonAlert 대체)
 * loginBottomAlert(msg, callback)으로 호출하며, 확인 버튼 클릭 시 callback 실행
 */
require_once __DIR__ . '/bottom_sheet_template.php';

renderBottomSheet(
  'loginAlertPanel',
  '알림',
  '<p id="loginAlertMessage" class="login-alert-message" style="white-space: pre-line; font-weight: 700;"></p>',
  '확인',
  'btnLoginAlertOk'
);
?>
<script>
  // 현재 표시 중인 알림의 확인 콜백 (확인 버튼 클릭 시 1회 실행 후 해제)
  var loginAlertCallback = null;

  /**
   * 바텀시트로 알림 표시
   * @param {string} msg - 알림 메시지 (\n 줄바꿈 지원)
   * @param {function} callback - 확인 버튼 클릭 시 실행할 함수 (선택)
   */
  function loginBottomAlert(msg, callback) {
    var panel = document.getElementById('loginAlertPanel');
    var message = document.getElementById('loginAlertMessage');
    if (!panel || !message) return;

    message.textContent = msg;
    loginAlertCallback = typeof callback === 'function' ? callback : null;
    openPanel(panel);
  }

  document.getElementById('btnLoginAlertOk').addEventListener('click', function() {
    var panel = document.getElementById('loginAlertPanel');
    var callback = loginAlertCallback;
    loginAlertCallback = null;
    closePanel(panel);
    if (callback) callback();
  });
</script>
