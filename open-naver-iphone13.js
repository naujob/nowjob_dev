const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: false });

  // iPhone 13 화면 크기 (390x844)와 User-Agent 설정
  const context = await browser.newContext({
    viewport: { width: 390, height: 844 },
    userAgent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1'
  });

  const page = await context.newPage();
  await page.goto('https://www.naver.com');

  console.log('Naver 페이지가 iPhone 13 화면 크기(390x844)로 열렸습니다.');
})();
