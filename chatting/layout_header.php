<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$scheme = $is_https ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$host = preg_replace('/:\d+$/', '', $host);
$chat_base_url = getenv('SOCKET_BASE_URL');
if ($chat_base_url === false || $chat_base_url === '') {
    $chat_port = getenv('CHAT_SERVER_PORT');
    if ($chat_port === false || $chat_port === '') {
        $chat_port = '3030';
    }
    $chat_base_url = $scheme . '://' . $host . ':' . $chat_port;
}
$chat_base_url = rtrim($chat_base_url, '/');
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="naver-site-verification" content="856b3094cd5577eeb70089717a6db38c3cd334f8" />
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <meta http-equiv="Cache-Control" CONTENT="no-cache">
    <meta http-equiv="Pragma" CONTENT="no-cache">

    <title>채팅룸</title>
    <link rel="stylesheet" href="/chatting/css/font.css">
    <link rel="stylesheet" href="/chatting/css/common.css">
    <link rel="stylesheet" href="/chatting/css/chatting.css">
    <link type="text/css" rel="stylesheet" href="/chatting/icon/remixicon.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script>
        window.__CHAT_BASE_URL = "<?= $chat_base_url ?>";
    </script>
    <script src="<?= $chat_base_url ?>/socket.io/socket.io.js"></script>

    <!-- Firebase SDK 추가 (안드로이드 앱에서만 필요) -->
    <?php if (isset($_GET['os_type']) && $_GET['os_type'] == 'android'): ?>
        <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>
    <?php endif; ?>

    <script src="/chatting/js/common.js"></script>
    <script src="/chatting/js/chatting.js"></script>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

</head>

<body>
