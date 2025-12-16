<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>마이페이지 - 지금 뜨는, 좋은 일자리 NOW JOB</title>
    <meta name="description" content="구인구직 정보 플랫폼">
    <meta name="format-detection" content="address=no, email=no, telephone=no">
    <link rel="shortcut icon" href="/resource/favicon.ico">
    <!-- Script -->
    <?=getCashJs(RES_PATH . "/js/lib/jquery-3.5.1.min.js")?>
    <?=getCashJs(RES_PATH . "/js/add.js")?>
    
    <!-- Style -->
    <?=getCachCss(RES_PATH . "/css/keen-slider.min.css")?>
    <?=getCachCss(RES_PATH . "/css/main.css")?>
    <?=getCachCss(RES_PATH . "/css/add.css")?>
</head>

<body>
	<div id="app">
		<?=$contents?>
	</div><!-- //#app -->
	<a target="_blank" style="position:fixed;top:10px;left:10px;z-index:99999;" href="/outer/design/_codinglist.html">코딩</a>
	<!-- Script -->
	<?=getCashJs(RES_PATH . "/js/lib/lozad.min.js")?>
	<?=getCashJs(RES_PATH . "/js/lib/keen-slider.js")?>
    <?=getCashJs(RES_PATH . "/js/lib/wheel.min.js")?>
    <?=getCashJs(RES_PATH . "/js/lib/rolldate.min.js")?>
	<?=getCashJs(RES_PATH . "/js/lib/flatpickr.js")?>
	<?=getCashJs(RES_PATH . "/js/lib/l10n/ko.js")?>
	<?=getCashJs(RES_PATH . "/js/lib/plugins/monthSelect/index.js")?>
	<?=getCashJs(RES_PATH . "/js/main.js")?>
</body>
</html>
