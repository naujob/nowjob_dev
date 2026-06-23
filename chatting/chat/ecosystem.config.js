const path = require('path');

// 런타임 경로 기반 환경 감지
function detectEnv() {
	var runtimePath = (process.cwd() + '|' + __dirname).toLowerCase();
	if (runtimePath.includes('dev_nowapk')) {
		return 'development';
	}
	return 'production';
}

// 현재 디렉토리 기반으로 cwd 자동 설정
var currentDir = __dirname; // ecosystem.config.js가 있는 디렉토리
var env = detectEnv();
var cwdPath = currentDir;
var logDir = path.join(currentDir, 'logs');

module.exports = {
  apps: [
    {
      name: "chat_server_dev",
      script: "chat_server.js",
      cwd: cwdPath,
      env: {
        APP_ENV: "development"
      },
      autorestart: true,
      max_memory_restart: "500M",
      max_restarts: 10,
      min_uptime: "10s",
      watch: false,
      ignore_watch: ["node_modules", "logs", ".git"],
      error_file: path.join(logDir, "error.log"),
      out_file: path.join(logDir, "out.log"),
      log_date_format: "YYYY-MM-DD HH:mm:ss Z"
    },
    {
      name: "chat_server_prod",
      script: "chat_server.js",
      cwd: cwdPath,
      env: {
        APP_ENV: "production"
      },
      autorestart: true,
      max_memory_restart: "500M",
      max_restarts: 10,
      min_uptime: "10s",
      watch: false,
      ignore_watch: ["node_modules", "logs", ".git"],
      error_file: path.join(logDir, "error.log"),
      out_file: path.join(logDir, "out.log"),
      log_date_format: "YYYY-MM-DD HH:mm:ss Z"
    }
  ]
};
