// 브라우저 로거 - AJAX로 서버에 저장
var BrowserLogger = {
  log: function(level, message, data) {
    var logData = {
      level: level,
      message: message,
      timestamp: new Date().toISOString(),
      url: window.location.href,
      userAgent: navigator.userAgent,
      data: data || {}
    };

    // 서버에 비동기 전송
    try {
      if (typeof fetch !== 'undefined') {
        // fetch API 사용 (모던 브라우저)
        fetch('/logger_proc/log_browser', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(logData),
          keepalive: true
        }).catch(function(err) {
          // 로그 전송 실패는 무시
        });
      } else {
        // jQuery AJAX 폴백
        $.ajax({
          url: '/logger_proc/log_browser',
          type: 'POST',
          contentType: 'application/json',
          data: JSON.stringify(logData),
          async: true,
          timeout: 5000
        });
      }
    } catch (e) {
      // 로그 전송 중 예외는 무시
    }
  },

  debug: function(message, data) {
    this.log('DEBUG', message, data);
  },

  info: function(message, data) {
    this.log('INFO', message, data);
  },

  warn: function(message, data) {
    this.log('WARN', message, data);
  },

  error: function(message, data) {
    this.log('ERROR', message, data);
  }
};
