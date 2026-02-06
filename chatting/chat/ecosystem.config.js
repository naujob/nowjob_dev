module.exports = {
  apps: [
    {
      name: "chat_server_dev",
      script: "chat_server.js",
      cwd: "/website/dev_nowapk/chatting/chat",
      env: {
        APP_ENV: "development"
      }
    },
    {
      name: "chat_server_prod",
      script: "chat_server.js",
      cwd: "/website/nowapk/chatting/chat",
      env: {
        APP_ENV: "production"
      }
    }
  ]
};
