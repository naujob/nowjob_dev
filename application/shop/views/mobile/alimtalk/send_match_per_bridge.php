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

		/* 자동 모드: 스피너 표시 */
		.bridge-page[data-mode="auto"] .spinner,
		.bridge-page[data-mode="auto"] .text,
		.bridge-page[data-mode="auto"] .description {
			display: block;
		}

		.bridge-page[data-mode="auto"] .app-button,
		.bridge-page[data-mode="auto"] .manual-title,
		.bridge-page[data-mode="auto"] .manual-subtitle {
			display: none;
		}

		/* 수동 모드: 버튼 표시 */
		.bridge-page[data-mode="manual"] .spinner,
		.bridge-page[data-mode="manual"] .text,
		.bridge-page[data-mode="manual"] .description {
			display: none;
		}

		.bridge-page[data-mode="manual"] .app-button,
		.bridge-page[data-mode="manual"] .manual-title,
		.bridge-page[data-mode="manual"] .manual-subtitle {
			display: block;
		}

		.app-button {
			background: #667eea;
			color: white;
			border: none;
			border-radius: 8px;
			padding: 14px 32px;
			font-size: 16px;
			font-weight: 600;
			cursor: pointer;
			margin-top: 20px;
			transition: background 0.3s ease;
		}

		.app-button:active {
			background: #5568d3;
		}

		.app-button:disabled {
			background: #ccc;
			cursor: not-allowed;
		}

		.manual-title {
			font-size: 18px;
			color: #333;
			font-weight: 600;
			margin-bottom: 12px;
		}

		.manual-subtitle {
			font-size: 13px;
			color: #666;
			margin-bottom: 20px;
			line-height: 1.5;
		}
	</style>
</head>
<body class="bridge-page-body">
	<div class="container" id="bridge-page" data-mode="auto">
		<div class="spinner"></div>
		<div class="text">앱으로 이동중</div>
		<div class="description">잠시만 기다려주세요...</div>

		<div class="manual-title">앱에서 더 나은 경험을 하세요!</div>
		<div class="manual-subtitle">아래 버튼을 눌러 앱으로 이동하세요</div>
		<button class="app-button" id="open-app-btn">앱으로 열기</button>
	</div>

	<script>
		window.addEventListener('DOMContentLoaded', function() {
			var userAgent = navigator.userAgent.toLowerCase();
			var isAndroid = /android/i.test(userAgent);
			var isIos = /iphone|ipad|ipod/i.test(userAgent);

			// 카카오톡, 라인, 페이스북 메신저, 네이버앱 등 인앱 브라우저 감지
			var isKakaoTalk = /kakaotalk/i.test(userAgent);
			var isLine = /line/i.test(userAgent);
			var isMessenger = /fban|fbav/i.test(userAgent);
			var isNaverApp = /naver|navercorp/i.test(userAgent);
			var isRestrictedBrowser = isKakaoTalk || isLine || isMessenger || isNaverApp;

		
			var universalLinkUrl = '/mypage/match_list';  // iOS Safari
			var deepLinkUrl = 'nowjob://mypage/match_list';  // iOS 인앱브라우저, Androi


			var appStoreUrl;
			if (isAndroid) {
				appStoreUrl = 'market://details?id=app.nowapk.com';
			} else if (isIos) {
				appStoreUrl = 'itms-apps://apps.apple.com/app/id6446226901';
			} else {
				appStoreUrl = '/mypage/match_list';
			}

			// iOS 처리 (일반 브라우저 + 인앱 브라우저 모두)
			
			// iOS 처리
			if (isIos) {
				if (isRestrictedBrowser) {
					// 인앱 브라우저: 커스텀 scheme으로 이동
					window.location.href = deepLinkUrl;
				} else {
					// 일반 웹브라우저: Universal Links로 이동
					window.location.href = universalLinkUrl;
				}
				setTimeout(function() {
					if (!document.hidden) {
						window.location.href = appStoreUrl;
					}
				}, 1500);
				return;
			}

			// Android: 앱 URL 시도 후 앱이 없으면 Play Store로 이동
			if (isAndroid) {
				window.location.href = deepLinkUrl;
				setTimeout(function() {
					if (!document.hidden) {
						window.location.href = appStoreUrl;
					}
				}, 1500);
			}
		});

			// Android: 앱 URL 시도 후 앱이 없으면 Play Store로 이동
			if (isAndroid) {
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
