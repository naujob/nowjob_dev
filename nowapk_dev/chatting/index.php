
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="naver-site-verification" content="856b3094cd5577eeb70089717a6db38c3cd334f8"/>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/resource/site/img/favi/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <title>식봇</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/resource/site/img/favi/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/resource/site/img/favi/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/resource/site/img/favi/favicon-96x96.png">
    <link rel="manifest" href="/resource/site/img/favi/manifest.json?v=0001">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/bot/css/common.css">
    <link rel="stylesheet" href="/bot/css/bot.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simpler-sidebar@2.2.5/dist/jquery.simpler-sidebar.min.js"></script>
    <script type="text/javascript">
        var g_is_login = "FALSE";
        var g_user_idx = "";
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-49582342-3"></script>

</head>
<body>

<div id="wrap">
    <div class="intro">
        <div>
            <img src="/bot/img/bot2.png" style="width:200px;" />
        </div>
        <div class="title">
           식자재는 식봇!!
        </div>
        <button class="btn_start">시작하기</button>
    </div>
</div>

<script>
    $(".btn_start").click(function(){
       location.href="/bot/step1.php";
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous" type="text/javascript"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous" type="text/javascript"></script>
<script src="https://popoling.com/resource/site/js/common.js" type="text/javascript"></script>
<script src="https://popoling.com/resource/site/js/howler.js" type="text/javascript"></script>
<script src="https://popoling.com/resource/site/lib/menu/mmenu-light.js" type="text/javascript"></script>
</body>
</html>
