<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>앱으로 이동중</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
			/* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
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
<body>
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
			// is_app=true 파라미터로 팝업 숨김
			var isLogin = <?php echo isset($is_login) ? $is_login : 'false'; ?>;
			var isPerson = <?php echo isset($is_person) ? $is_person : 'false'; ?>;
			var appUrl = (isLogin === true || isLogin === 'true') && (isPerson === true || isPerson === 'true')
				// ? 'nowjob://recruit/job_list?is_app=true'
				? 'nowjob://chat/chat_list'
				// : 'nowjob://chat/index_comp';
				: 'nowjob://member/login?is_app=true';

			// 딥링크로 앱 실행
			window.location.href = appUrl;

			// 1.5초 후에도 여전히 페이지가 visible이면 앱 미설치로 판단 → 앱마켓으로 폴백
			setTimeout(function() {
				if (!document.hidden) {
					window.location.href = appStoreUrl;
				}
			}, 1500);
		});
	</script>
</body>
</html>
