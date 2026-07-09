<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>앱으로 이동중</title>
	<link rel="stylesheet" href="/resource/shop/mobile/css/main.css">
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.container {
			text-align: center;
			background: white;
			border-radius: 16px;
			padding: 40px 30px;
			box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
			max-width: 300px;
		}

		.spinner {
			width: 50px;
			height: 50px;
			margin: 0 auto 20px;
			border: 4px solid #f3f3f3;
			border-top: 4px solid #667eea;
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}

		.text {
			font-size: 16px;
			color: #333;
			font-weight: 600;
			letter-spacing: -0.5px;
		}

		.description {
			font-size: 13px;
			color: #999;
			margin-top: 10px;
			line-height: 1.5;
		}
	</style>
</head>
<body class="bridge-page-body">
	<div class="container">
		<div class="spinner"></div>
		<div class="text">앱으로 이동중</div>
		<div class="description">잠시만 기다려주세요...</div>
	</div>

	<script>
		window.addEventListener('DOMContentLoaded', function() {


			// 기기 감지
			var userAgent = navigator.userAgent.toLowerCase();
			var isAndroid = /android/i.test(userAgent);
			var isIos = /iphone|ipad|ipod/i.test(userAgent);

			// 앱마켓 URL 결정 (폴백용)
			var appStoreUrl;
			if (isAndroid) {
				appStoreUrl = 'market://details?id=app.nowapk.com';
			} else if (isIos) {
				appStoreUrl = 'itms-apps://apps.apple.com/app/id6446226901';
			} else {
				// 데스크톱 등 - 웹 페이지로 폴백
				appStoreUrl = '/recruit/job_list';
			}

			// 로그인 상태에 따른 딥링크 생성
			var isLogin = <?php echo isset($is_login) ? $is_login : 'false'; ?>;
			var isCorp = <?php echo isset($is_corp) ? $is_corp : 'false'; ?>;
			var isAppWebview = <?php echo $this->common->getSession("os_type") ? 'true' : 'false'; ?>;

			var appUrl = (isLogin === true || isLogin === 'true') && (isCorp === true || isCorp === 'true')
				? 'nowjob://chat/index_comp'
				: 'nowjob://member/login?is_app=true';

			// 이미 앱 내부 웹뷰에서 로드되었다면 커스텀 스킴 호출 대신 내부 라우트로 직접 이동
			if (isAppWebview) {
				// 로딩 UI가 표시되도록 지연 추가
				setTimeout(function() {
					window.location.replace(appUrl.replace('nowjob://', '/'));
				}, 800);
			} else {
				// Safari 등 외부 웹뷰: 커스텀 스킴 딥링크 + 폴백
				window.location.href = appUrl;
				setTimeout(function() {
					if (!document.hidden) {
						window.location.href = appStoreUrl;
					}
				}, 1500);
			}
		});
	</script>
</body>
</html>
