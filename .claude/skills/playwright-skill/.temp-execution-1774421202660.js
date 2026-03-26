
const { chromium } = require('playwright');

(async () => {
  const browser = await chromium.launch({ headless: false });
  const context = await browser.newContext();
  const page = await context.newPage();

  try {
    const loginUrl = 'https://dev.nowjob.co.kr/member/login';
    await page.goto(loginUrl, { waitUntil: 'networkidle' });
    
    // 로그인 페이지 스크린샷
    await page.screenshot({ path: '/tmp/login-page.png', fullPage: true });
    
    // 모든 버튼 정보
    const buttons = await page.locator('button').all();
    console.log('🔘 Login 페이지의 버튼들:');
    for (let i = 0; i < buttons.length; i++) {
      const text = await buttons[i].textContent();
      const type = await buttons[i].getAttribute('type');
      const className = await buttons[i].getAttribute('class');
      const id = await buttons[i].getAttribute('id');
      console.log('  [' + i + '] text=' + text.trim() + ', type=' + type + ', class=' + className + ', id=' + id);
    }
    
  } catch (error) {
    console.error('❌ 오류:', error.message);
  } finally {
    await context.close();
    await browser.close();
  }
})();
