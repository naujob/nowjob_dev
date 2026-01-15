<div class="bottom-nav">
  <!-- 활성화된 메뉴 : active 클래스 -->
  <a href="/" class="btn menu-home <?= $select_menu == "home" ? "active" : "" ?>"></a>
  <button type="button" aria-controls="menu" aria-expanded="false" class="btn-toggle-menu js-toggle-menu"><span class="sr-text">메뉴열기</span></button>
  <nav id="menu" class="menu">
    <!-- 개인 사용자 -->
    <ul>
      <?php if ($this->user->getUserType() == "COMP") { ?>
        <style>
          .bottom-nav .sub-menu1:before {
            background-image: url('/resource/shop/mobile/img/rating_stars_on_2.svg');
            background-size: 80%;
            position: relative;
           
            right: -9px;
          }

          /* .bottom-nav .menu a:before { background-size: cover; } */
        </style>
        <li><a href="/compage/bookmark_person1" class="sub-menu1"><span>관심인재</span></a></li>
        <li><a href="/recruit/job_list" class="sub-menu2"><span>채용정보</span></a></li>
        <li><a href="javascript:void(0);" class="sub-menu3" id="btnRecruitWrite"><span>채용공고</span></a></li>
        <li><a href="/resume/hr_list" class="sub-menu4"><span>인재정보</span></a></li>
        <?php if ($this->user->isLogin()) : ?>
          <li><a href="/<?= ($this->user->getUserType() == "COMP") ? "compage" : "mypage" ?>/index" class="sub-menu5"><span>마이페이지</span></a></li>
        <?php else : ?>
          <li><a href="/member/login" class="sub-menu5"><span>로그인</span></a></li>
        <?php endif; ?>

      <?php } else if ($this->user->getUserType() == "MEM") { ?>
        <style>
          .bottom-nav .sub-menu1:before {
            background-image: url('/resource/shop/mobile/img/ic_top_search_2.svg');
            position: relative;
           
            right: -12px;
          }
        </style>
        <li><a href="/recruit/job_view_list" class="sub-menu1"><span>최근본공고</span></a></li>
        <li><a href="/resume/hr_list" class="sub-menu2"><span>인재정보</span></a></li>
        <li><a href="javascript:void(0);" class="sub-menu3" id="btnResumeWrite"><span>이력서</span></a></li>
        <li><a href="/recruit/job_list" class="sub-menu4"><span>채용정보</span></a></li>
        <?php if ($this->user->isLogin()) : ?>
          <li><a href="/<?= ($this->user->getUserType() == "COMP") ? "compage" : "mypage" ?>/index" class="sub-menu5"><span>마이페이지</span></a></li>
        <?php else : ?>
          <li><a href="/member/login" class="sub-menu5"><span>로그인</span></a></li>
        <?php endif; ?>

      <?php } else { ?>

        <li><a href="/member/login" class="sub-menu1"><span>로그인</span></a></li>
        <li><a href="/resume/hr_list" class="sub-menu2"><span>인재정보</span></a></li>
        <li><a href="/compage/recruit_list" class="sub-menu3" id="btnRecruitMenu"><span>공고등록</span></a></li>
        <li><a href="/recruit/job_list" class="sub-menu4"><span>채용정보</span></a></li>
        <li><a href="/recruit/job_view_list" class="sub-menu5" id="btnJobViewList"><span>최근본공고</span></a></li>

      <?php } ?>
      <?php /* //org ?>
            <li><a href="/" class="sub-menu1">HOME</a></li>
            <?php if($this->user->getUserType()=="COMP") : ?>
            	<li><a href="/resume/hr_list" class="sub-menu2">인재정보</a></li>
            <?php else : ?>
            	<li><a href="/recruit/job_list" class="sub-menu2">채용정보</a></li>
            <?php endif; ?>
            <li><a href="/compage/recruit_list" class="sub-menu3">공고등록</a></li>
            <li><a href="/chat" class="sub-menu4">채팅</a></li>
            <?php if($this->user->isLogin()) : ?>
            	<li><a href="/<?=($this->user->getUserType()=="COMP") ? "compage":"mypage" ?>/index" class="sub-menu5">마이페이지</a></li>
            <?php else : ?>
            	<li><a href="/member/login" class="sub-menu5">로그인</a></li>
            <?php endif; ?>
          <?php */ ?>
    </ul>
    <div class="dimmed"></div>
  </nav>
  <div id="messageOverlay" style="display:none; position: fixed; bottom: 90px; right: 0; width: 180px; height: 70px; background-color: transparent; z-index: 9999;">
    <div id="messageBox" style="position: absolute; top: 0%; left: 0%; background-color: #ccc; padding: 20px; border-radius: 10px; font-size: 13px; color: #000; border: 1px solid #ccc;">준비중 입니다</div>
  </div>

  <a href="#" class="btn menu-chat <?= $select_menu == "chat" ? "active" : "" ?>" id="btnChat" style="position: relative;"><span id="msgNoReadCountMainBottom"></span></a>
  <style>
    #msgNoReadCountMainBottom {
      position: absolute;
      top: -3px;
      right: 12%;
      background-color: #F86048;
      width: 24px;
      height: 24px;
      text-align: center;
      line-height: 24px;
      color: #fff;
      font-size: 0.8rem;
      border-radius: 100%;
      display: none;
    }
  </style>
</div>

<?php
// 공통 바텀시트 템플릿 로드
require_once __DIR__ . '/../_partial/bottom_sheet_template.php';

// 로그인 안내 바텀 시트 팝업
renderBottomSheet(
  'loginGuidePanel',
  '로그인 안내',
  '<p>로그인이 필요한 서비스입니다.</p><p>로그인 하시겠습니까?</p>',
  '로그인 및 회원가입',
  'btnLoginGuide',
  '',
  'login-bottom-sheet-container',
  'login-bottom-sheet-header',
  'login-bottom-sheet-title',
  'login-guide-content'
);
?>

<?php
// 이력서 선택 바텀 시트 팝업
renderSelectBottomSheet(
  'resumeSelectPanel',
  '이력서',
  '<p>이력서 페이지로<br>이동하시겠습니까?</p>',
  '이력서 목록',
  'btnResumeListPanel',
  '',
  '이력서 등록',
  'btnResumeWritePanel',
  '',
  '',
  'select-bottom-sheet-header',
  'select-bottom-sheet-title'
);

// 채용공고 선택 바텀 시트 팝업
renderSelectBottomSheet(
  'recruitSelectPanel',
  '채용공고',
  '<p>채용공고 페이지로<br>이동하시겠습니까?</p>',
  '채용공고 목록',
  'btnRecruitListPanel',
  '',
  '채용공고 등록',
  'btnRecruitWritePanel',
  '',
  '',
  'select-bottom-sheet-header',
  'select-bottom-sheet-title'
);
?>

<style>
  /* NotoSansKR ExtraBold 폰트 정의 */
  @font-face {
    font-family: 'NotoSansKR-ExtraBold';
    src: url('/resource/shop/mobile/font/NotoSansKR-ExtraBold.woff') format('woff');
    font-weight: 900;
    font-style: normal;
    font-display: swap;
  }

  /* ============================================
     공통 바텀시트 스타일 (모든 바텀시트 공통)
     ============================================ */

  /* Handlebar 스타일 - 상단 드래그용 가로선 */
  .bottom-sheet-handlebar {
    width: 100%;
    height: auto;
    margin: 12px auto 0;
    cursor: pointer;
    position: relative;
    /* iOS WebView 스와이프 제스처 활성화 */
    touch-action: pan-y !important;
    -webkit-user-drag: none !important;
    -webkit-touch-callout: none !important;
    -webkit-user-select: none !important;
    pointer-events: auto !important;
    /* 스와이프 감지 영역 확대 (전체 영역) */
    padding: 8px 0;
    /* 세로 드래그만 허용 */
  }

  /* Handlebar 시각적 선 (작은 높이) */
  .bottom-sheet-handlebar::before {
    content: '';
    display: block;
    width: 40px;
    height: 3px;
    background-color: #D9D9D9;
    border-radius: 2px;
    margin: 0 auto;
  }

  /* 패널 컨테이너 기본 스타일 (main.css의 .layer-panel .panel-container 속성 참고) */
  .bottom-sheet-container {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    position: relative;
    width: 100%;
    height: 100%;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    background: #F6F6F6;
    -webkit-transform: translateY(100%);
    -ms-transform: translateY(100%);
    transform: translateY(100%);
    overflow: hidden;
    z-index: 1;
    /* transform transition 딱 1개만 선언 (iOS 레이어 재생성 방지) */
    -webkit-transition: -webkit-transform 0.5s cubic-bezier(0.5, 1, 0.89, 1);
    transition: transform 0.5s cubic-bezier(0.5, 1, 0.89, 1);
  }

  /* 패널이 열렸을 때 (main.css의 .layer-panel.on .panel-container 속성 참고) */
  .layer-panel.on .bottom-sheet-container {
    -webkit-transform: translateY(0);
    -ms-transform: translateY(0);
    transform: translateY(0);
    /* transform transition 딱 1개만 선언 (iOS 레이어 재생성 방지) */
    -webkit-transition: -webkit-transform 0.5s cubic-bezier(0.5, 1, 0.89, 1);
    transition: transform 0.5s cubic-bezier(0.5, 1, 0.89, 1);
  }

  /* 카드 타입 바텀시트 컨테이너 (main.css의 .layer-panel.card-type .panel-container 속성 참고) */
  .layer-panel.card-type .bottom-sheet-container {
    position: absolute;
    bottom: 0;
    left: 0;
    height: auto;
    min-height: 0;
    max-height: 86.8%;
    border-radius: 2.5625rem 2.5625rem 0 0;
    background: #fff;
  }

  /* 바텀시트 Body 스타일 (main.css의 .layer-panel.card-type .panel-body 속성 참고) */
  .bottom-sheet-body {
    position: relative;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    background: inherit;
    overflow: hidden;
    overflow-y: auto;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    min-height: 0;
    padding: 0 1.375rem;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
  }

  /* 바텀시트 Content 스타일 */
  .bottom-sheet-content {
    position: relative;
  }

  /* 바텀시트 Footer 스타일 */
  .bottom-sheet-footer {
    /* iOS Home Indicator와 겹침 방지를 위한 Safe Area 대응 (Android 하단 시스템 UI 겹침 방지) */
    /* max() 함수로 최소값 0px 보장: Android는 0px, iOS는 Safe Area - 1rem 적용 */
    padding: 1.5rem 1.5rem calc(2.5rem + max(env(safe-area-inset-bottom) - 1rem, 0px));
  }

  /* 바텀시트 Sticky-bottom 스타일 (main.css의 .layer-panel .panel-footer .sticky-bottom 속성 참고) */
  .bottom-sheet-sticky-bottom {
    position: relative;
  }

  /* 바텀시트 버튼 기본 스타일 (main.css의 .btn 속성 참고) */
  .btn-bottom-sheet {
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    border: 0;
    background: transparent;
  }

  /* 바텀시트 Primary 버튼 스타일 (main.css의 .btn-primary 속성 참고) */
  .btn-bottom-sheet-primary {
    display: inline-block;
    font-weight: 700;
    color: #fff;
    text-align: center;
    vertical-align: middle;
    border: 0;
    background: #1B9D7D;

  }

  .btn-bottom-sheet-primary:active {
    background-color: #38b898;
  }

  /* 바텀시트 Secondary 버튼 스타일 (main.css의 .btn-secondary 속성 참고) */
  .btn-bottom-sheet-secondary {
    display: inline-block;
    font-weight: 700;
    color: #fff;
    text-align: center;
    vertical-align: middle;
    border: 0;
    background: #BEBFC9;
  }

  .btn-bottom-sheet-secondary:active {
    background-color: #a2a4b2;
  }

  /* 바텀시트 헤더 기본 스타일 (main.css의 .layer-panel.card-type .panel-header 속성 참고) */
  .bottom-sheet-header {
    position: relative;
    top: 0;
    left: 0;
    width: 100%;
    padding-top: 2.25rem;
    padding-bottom: 1.25rem;
    padding-left: 1.5rem;
    padding-right: 1.375rem;
    background: inherit;
  }

  /* 바텀시트 타이틀 기본 스타일 */
  .bottom-sheet-title {
    font-size: 25px;
    color: #1B9D7D;
    text-align: left;
    margin: 0;
    line-height: 1.2;
  }

  /* iOS WebView 터치 이벤트 전달 보장 */
  .layer-panel,
  .layer-panel * {
    pointer-events: auto !important;
  }

  /* 바텀시트 패널 컨테이너 터치 이벤트 보장 */
  .layer-panel.card-type,
  .layer-panel.card-type .bottom-sheet-container {
    pointer-events: auto !important;
  }

  /* iOS 바텀시트 영역 터치 시 배경 스크롤 차단 */
  .layer-panel.card-type {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    /* iOS가 터치 이벤트를 sheet로만 가게 하는 핵심 코드 */
    touch-action: none !important;
    /* 스크롤은 sheet 내부만 */
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }

  /* iOS 배경 깜빡임 방지를 위한 하드웨어 가속 최적화 */
  .layer-panel {
    /* 하드웨어 가속 활성화 - 배경 레이어를 GPU에서 렌더링 */
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    /* 렌더링 최적화 힌트 */
    will-change: opacity, transform;
    /* iOS Safari 렌더링 최적화 */
    -webkit-perspective: 1000;
    perspective: 1000;
  }

  /* 패널이 열릴 때 즉시 표시되도록 최적화 */
  .layer-panel.on {
    /* 하드웨어 가속 유지 */
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    /* iOS에서 배경이 즉시 표시되도록 */
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
  }

  /* iOS body 스크롤 완전 차단 (바텀시트 열릴 때) */
  body.no-scroll {
    position: fixed !important;
    width: 100% !important;
    overflow: hidden !important;
    touch-action: none !important;
    /* iOS 하드웨어 가속으로 깜빡임 방지 */
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
  }

  /* ============================================
     선택형 바텀시트 공통 스타일 (이력서/채용공고 공통)
     ============================================ */

  /* 선택형 바텀시트 헤더 스타일 (main.css의 .layer-panel.card-type .panel-header 속성 참고) */
  .select-bottom-sheet-header {
    position: relative;
    top: 0;
    left: 0;
    width: 100%;
    padding-top: 1.25rem;
    padding-bottom: 1.25rem;
    padding-left: 1.5rem;
    padding-right: 1.375rem;
    background: inherit;
  }

  /* 선택형 바텀시트 타이틀 스타일 */
  .select-bottom-sheet-title {
    font-family: 'NotoSansKR-ExtraBold', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #1B9D7D;
    text-align: left;
    margin: 0;
    line-height: 1.2;
    -webkit-text-stroke: 0.5px #1B9D7D;
    -moz-text-stroke: 0.5px #1B9D7D;
    -ms-text-stroke: 0.5px #1B9D7D;
    -o-text-stroke: 0.5px #1B9D7D;

  }

  /* 선택형 바텀시트 콘텐츠 스타일 */
  .select-bottom-sheet-content-wrapper {
    padding: -0.75rem 0;
  }

  .select-bottom-sheet-content-wrapper p {
    font-size: 1.1rem;
    color: #696060;
    line-height: 1.5;
    margin: 0;
  }

  /* 선택형 바텀시트 버튼 그룹 스타일 */
  .select-bottom-sheet-buttons {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    align-items: center;
    width: 100%;
  }

  /* 선택형 바텀시트 선택 버튼 기본 스타일 */
  .btn-select-bottom-sheet {
    height: 70px;
    font-size: 1.3rem;
    font-weight: 700;
    border-radius: 38px;
    cursor: pointer;
    transition: all 0.2s;
    flex: 1;
  }

  /* 선택형 바텀시트 Secondary 버튼 스타일 (아웃라인) */
  .btn-select-bottom-sheet.btn-bottom-sheet-secondary {
    background-color: #fff;
    color: #1B9D7D;
    border: 1px solid #1B9D7D;
  }

  .btn-select-bottom-sheet.btn-bottom-sheet-secondary:active,
  .btn-select-bottom-sheet.btn-bottom-sheet-secondary:hover {
    background-color: #f0f9f7;
  }

  /* 선택형 바텀시트 Primary 버튼 스타일 (솔리드) */
  .btn-select-bottom-sheet.btn-bottom-sheet-primary {
    background-color: #1B9D7D;
    color: #fff;
    border: none;
  }

  .btn-select-bottom-sheet.btn-bottom-sheet-primary:active,
  .btn-select-bottom-sheet.btn-bottom-sheet-primary:hover {
    background-color: #158a6f;
  }

  /* ============================================
     로그인 바텀시트 개별 스타일
     ============================================ */

  /* 로그인 바텀시트 헤더 스타일 (main.css의 .layer-panel.card-type .panel-header 속성 참고) */
  .login-bottom-sheet-header {
    position: relative;
    top: 0;
    left: 0;
    width: 100%;
    padding-top: 1.25rem;
    padding-bottom: 1.25rem;
    padding-left: 1.5rem;
    padding-right: 1.375rem;
    background: inherit;
  }

  /* 로그인 바텀시트 타이틀 스타일 (font-weight만 다름) */
  .login-bottom-sheet-title {
    font-family: 'NotoSansKR-ExtraBold', sans-serif;
    font-size: 1.5rem;
    font-weight: 900;
    color: #1B9D7D;
    text-align: left;
    margin: 0;
    line-height: 1.2;
  }

  /* 로그인 가이드 콘텐츠 스타일 */
  .login-guide-content {
    padding: 0.25rem 0;
  }

  .login-guide-content p {
    font-size: 1.1rem;
    color: #696060;
    line-height: 1.5;

  }

  .login-guide-content p:first-child {
    margin-top: 0;
  }

  .login-guide-content p:last-child {
    margin-bottom: 0;
  }

  /* 바텀시트 텍스트 콘텐츠 스타일 (공통) */
  .bottom-sheet-text-content {
    padding: 0.25rem 0;
  }

  .bottom-sheet-text-content p {
    font-size: 1.1rem;
    color: #696060;
    line-height: 1.5;
    margin: 0;
  }

  .bottom-sheet-text-content p:first-child {
    margin-top: 0;
  }

  .bottom-sheet-text-content p:last-child {
    margin-bottom: 0;
  }

  /* 로그인 가이드 버튼 스타일 */
  .btn-login-guide {
    width: 100%;
    height: 70px;
    font-size: 1.3rem;
    font-weight: 700;
    background-color: #1B9D7D;
    color: #fff;
    border-radius: 38px;
    border: none;
    margin: 0 auto;
    display: block;
    cursor: pointer;
    transition: background-color 0.2s;

  }

  .btn-login-guide:active,
  .btn-login-guide:hover {
    background-color: #4ab89a;
  }

  /* 로그인 바텀시트 컨테이너 최소 높이 설정 */
  .login-bottom-sheet-container {
    min-height: auto;
  }
</style>

<script>
  // 로그인 안내 팝업 버튼 클릭 이벤트
  $(document).on('click', '#btnLoginGuide', function() {
    location.href = '/member/login';
  });

  // 이력서 선택 팝업 버튼 클릭 이벤트
  $(document).on('click', '#btnResumeListPanel', function() {
    location.href = "/mypage/resume_list";
  });

  $(document).on('click', '#btnResumeWritePanel', function() {
    // 새 이력서 작성을 위해 임시저장 데이터 초기화
    // 새 이력서는 ru_idx가 0이므로 해당 키들을 삭제
    localStorage.removeItem("resume_draft_0_step1");
    localStorage.removeItem("resume_draft_0_step2");
    localStorage.removeItem("resume_draft_0_step3");
    localStorage.removeItem("resume_draft_0_step4");
    localStorage.removeItem("resume_draft_0_step5");
    
    location.href = "/mypage/resume_write1";
  });

  // 채용공고 선택 팝업 버튼 클릭 이벤트
  $(document).on('click', '#btnRecruitListPanel', function() {
    location.href = "/compage/recruit_list";
  });

  $(document).on('click', '#btnRecruitWritePanel', function() {
    // 새 채용공고 작성을 위해 임시저장 데이터 초기화
    // 새 채용공고는 rc_idx가 0이므로 해당 키들을 삭제
    localStorage.removeItem("recruit_draft_0_page1");
    localStorage.removeItem("recruit_draft_0_page2");
    localStorage.removeItem("recruit_draft_0_page3");
    localStorage.removeItem("recruit_draft_0_page4");
    localStorage.removeItem("recruit_draft_0_page5");
    
    location.href = "/compage/recruit_write1_1";
  });

  /**
   * 바텀시트 Handlebar Swipe 기능 초기화 (공통 함수)
   * 카카오톡/네이버지도 방식의 부드러운 바텀시트 모션
   * @param {string} panelId - 바텀시트 패널 ID
   */
  function initPanelSwipe(panelId) {
    var panel = document.getElementById(panelId);
    if (!panel) return;

    // 공통 클래스명으로 컨테이너와 핸들바 찾기
    var container = panel.querySelector('.bottom-sheet-container');
    var handlebar = panel.querySelector('.bottom-sheet-handlebar');
    if (!container || !handlebar) return;

    var startY = 0;
    var currentY = 0;
    var dragging = false;
    var SWIPE_CLOSE_DISTANCE = 120; // 닫히는 기준 (px)

    // touchstart - 터치 시작 (iOS WebView 최적화)
    handlebar.addEventListener('touchstart', function(e) {
      // 패널이 열리는 중이면 스와이프 동작 차단 (깜빡임 방지)
      if (panel.isOpening) return;

      if (e.touches.length === 0) return;

      startY = e.touches[0].clientY;
      dragging = true;
      container.style.transition = 'none'; // 드래그 중에는 transition 제거

      // iOS에서 터치 이벤트가 즉시 전달되도록 보장
      e.stopPropagation();
    }, {
      passive: false // iOS에서 preventDefault 가능하도록
    });

    // touchmove - 터치 이동 (iOS WebView 최적화)
    handlebar.addEventListener('touchmove', function(e) {
      // 패널이 열리는 중이면 스와이프 동작 차단 (깜빡임 방지)
      if (panel.isOpening) {
        dragging = false;
        return;
      }

      if (!dragging || e.touches.length === 0) return;

      currentY = e.touches[0].clientY;
      var deltaY = currentY - startY;

      // 아래로만 이동
      if (deltaY > 0) {
        container.style.transform = 'translateY(' + deltaY + 'px)';
        e.preventDefault(); // iOS에서 스크롤 방지
        e.stopPropagation(); // 이벤트 전파 방지
      }
    }, {
      passive: false // preventDefault 필수
    });

    // touchend - 터치 종료 (iOS WebView 최적화)
    handlebar.addEventListener('touchend', function(e) {
      if (!dragging) return;

      var deltaY = currentY - startY;
      dragging = false;

      // 이벤트 전파 방지
      e.stopPropagation();

      // 닫힘 조건 (120px 이상)
      if (deltaY > SWIPE_CLOSE_DISTANCE) {
        container.style.transition = 'transform 0.25s ease';
        container.style.transform = ''; // 초기화

        // 실제 패널 닫기
        if (typeof closePanel === 'function') {
          closePanel(panel);
        } else {
          panel.classList.remove('on');
          document.body.style.overflow = 'auto';
        }
      } else {
        // 원래 위치로 부드럽게 복귀
        container.style.transition = 'transform 0.25s ease';
        container.style.transform = 'translateY(0)';
      }

      // 상태 초기화
      startY = 0;
      currentY = 0;
    }, {
      passive: false
    });

    // 마우스 이벤트도 지원 (데스크톱 테스트용)
    var mouseMoveHandler = function(e) {
      // 패널이 열리는 중이면 스와이프 동작 차단 (깜빡임 방지)
      if (panel.isOpening) {
        dragging = false;
        return;
      }

      if (!dragging) return;
      currentY = e.clientY;
      var deltaY = currentY - startY;

      if (deltaY > 0) {
        container.style.transform = 'translateY(' + deltaY + 'px)';
        e.preventDefault();
      }
    };

    var mouseUpHandler = function() {
      if (!dragging) return;

      var deltaY = currentY - startY;
      dragging = false;

      if (deltaY > SWIPE_CLOSE_DISTANCE) {
        container.style.transition = 'transform 0.25s ease';
        container.style.transform = '';

        if (typeof closePanel === 'function') {
          closePanel(panel);
        } else {
          panel.classList.remove('on');
          document.body.style.overflow = 'auto';
        }
      } else {
        container.style.transition = 'transform 0.25s ease';
        container.style.transform = 'translateY(0)';
      }

      startY = 0;
      currentY = 0;

      // 이벤트 리스너 제거
      document.removeEventListener('mousemove', mouseMoveHandler);
      document.removeEventListener('mouseup', mouseUpHandler);
    };

    handlebar.addEventListener('mousedown', function(e) {
      // 패널이 열리는 중이면 스와이프 동작 차단 (깜빡임 방지)
      if (panel.isOpening) return;

      startY = e.clientY;
      dragging = true;
      container.style.transition = 'none';
      e.preventDefault();

      document.addEventListener('mousemove', mouseMoveHandler);
      document.addEventListener('mouseup', mouseUpHandler);
    });
  }

  // 모든 바텀시트에 Swipe 기능 초기화
  window.addEventListener('DOMContentLoaded', function() {
    initPanelSwipe('loginGuidePanel');
    initPanelSwipe('resumeSelectPanel');
    initPanelSwipe('recruitSelectPanel');
  });

  /**
   * openPanel/closePanel 함수는 main.js에 통합되어 있습니다.
   * 모든 로직이 main.js에 있어 유지보수가 용이합니다.
   * 
   * 배경 클릭 시 패널 닫기 로직도 main.js의 setTogglePanel() 내부에 이미 구현되어 있습니다.
   */
</script>

<script>
  navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;

  $(".js-toggle-menu").click(function() {
    var os_type = "<?= $os_type ?>";

    $("#menu").addClass("open");
    if (os_type == "android") {
      APP_NOWAPK.callVibrate();
    } else if (os_type == "ios") {
      window.webkit.messageHandlers.callVibrate.postMessage({});
    } else {
      if (navigator.vibrate) {
        navigator.vibrate(20); // 진동을 울리게 한다. 1000ms = 1초
      }
    }
  });
</script>
<script>
  // 디바운싱을 위한 타이머 변수
  var fSearchNoReadCountMainBottomTimer = null;
  var noreadCountPollingTimer = null;

  $(document).ready(function() {
    fSearchNoReadCountMainBottom();
    
    // 실시간 업데이트 초기화
    initRealTimeNoReadCountUpdate();
  });

  function fSearchNoReadCountMainBottom() {

    //alert('fSearchNoReadCountMainBottom');
    if ($("#user_id").val() == '') return;

    // 읽지 않은 메시지 수 조회 (exists 체크 제거 - 모든 읽지 않은 메시지 카운트)
    $.ajax({
      url: '/chat_proc/fSearchNoReadCount',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        var myId = $("#user_id").val();
        if (!myId) {
          $('#msgNoReadCountMainBottom').hide();
          return;
        }

        let result = response.data.result;
        let countAll = 0;

        // result가 배열이 아닌 객체인 경우 처리
        if (!Array.isArray(result)) {
          // 객체인 경우 값들을 배열로 변환
          if (result && typeof result === 'object') {
            result = Object.values(result);
          } else {
            result = [];
          }
        }

        // 모든 방의 읽지 않은 메시지 카운트 (exists 체크 제거)
        $.each(result, function(key, room) {
          if (!room || !room.rm_messages) return;

          $.each(room.rm_messages, function(index, message) {
            if (!message || !message.mg_reads) return;

            $.each(message.mg_reads, function(i, readEntry) {
              if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                countAll++;
              }
            });
          });
        });

        // 읽지 않은 메시지 수 표시/숨김 처리
        if (countAll > 0) {
          $('#msgNoReadCountMainBottom').html(countAll);
          $('#msgNoReadCountMainBottom').show();
        } else {
          $('#msgNoReadCountMainBottom').hide();
        }
      },
      error: function(xhr, status, error) {
        console.error('읽지 않은 메시지 수 조회 중 오류 발생:', error);
        // 에러 발생 시에도 배지 숨김 처리
        $('#msgNoReadCountMainBottom').hide();
      }
    });


    /*
      comAjax("/chat_proc/fSearchNoReadCount",{},function(ret){
        //console.log(ret);
        var myId = $("#user_id").val();

        let result = ret.data.result;

        let countAll = 0;
        $.each(result, function(key, room) {
            //console.log(room);
            $.each(room.rm_messages, function(index, message) {
                $.each(message.mg_reads, function(i, readEntry) {
                    if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                        countAll++;
                    }
                });
            });

        });
        $('#msgNoReadCountMainBottom').html(countAll);
        $('#msgNoReadCountMainBottom').show();

      */
  }

  /**
   * 디바운싱을 적용한 fSearchNoReadCountMainBottom 호출 함수
   * 중복 호출을 방지하여 서버 부하를 줄임
   */
  function fSearchNoReadCountMainBottomDebounced() {
    if (fSearchNoReadCountMainBottomTimer) {
      clearTimeout(fSearchNoReadCountMainBottomTimer);
    }
    fSearchNoReadCountMainBottomTimer = setTimeout(function() {
      fSearchNoReadCountMainBottom();
    }, 500); // 500ms 디바운싱
  }

  /**
   * 주기적 폴링 시작 함수
   * 10초마다 읽지 않은 메시지 수를 자동으로 업데이트
   */
  function startNoreadCountPolling() {
    // 기존 타이머가 있으면 제거
    if (noreadCountPollingTimer) {
      clearInterval(noreadCountPollingTimer);
    }
    
    // 로그인 상태일 때만 폴링 시작
    if ($("#user_id").val() == '') {
      return;
    }
    
    // 10초마다 실행
    noreadCountPollingTimer = setInterval(function() {
      fSearchNoReadCountMainBottomDebounced();
    }, 10000); // 10초마다
  }

  /**
   * 주기적 폴링 중지 함수
   */
  function stopNoreadCountPolling() {
    if (noreadCountPollingTimer) {
      clearInterval(noreadCountPollingTimer);
      noreadCountPollingTimer = null;
    }
  }

  /**
   * 실시간 업데이트 초기화 함수
   * WebSocket 이벤트 리스너 등록 및 주기적 폴링 시작
   */
  function initRealTimeNoReadCountUpdate() {
    // 로그인 상태가 아니면 실시간 업데이트 불필요
    if ($("#user_id").val() == '') {
      return;
    }

    // WebSocket이 존재하는지 확인
    if (typeof window.socket === 'undefined' || !window.socket) {
      // WebSocket이 아직 준비되지 않았으면 잠시 후 재시도
      setTimeout(function() {
        initRealTimeNoReadCountUpdate();
      }, 1000);
      return;
    }

    var socket = window.socket;

    // 기존 이벤트 리스너 제거 (중복 등록 방지)
    socket.off('realtime_message');
    socket.off('chat_notification');
    socket.off('message_read_count_return');

    // 실시간 메시지 수신 이벤트
    socket.on('realtime_message', function(ret) {
      if (ret && ret.type == "chat") {
        // 채팅 메시지 수신 시 읽지 않은 메시지 카운트 업데이트
        fSearchNoReadCountMainBottomDebounced();
      }
    });

    // 채팅 알림 수신 이벤트
    socket.on('chat_notification', function(ret) {
      // 채팅 알림 수신 시 읽지 않은 메시지 카운트 업데이트
      fSearchNoReadCountMainBottomDebounced();
    });

    // 메시지 읽음 카운트 변경 이벤트
    socket.on('message_read_count_return', function(ret) {
      // 읽음 카운트 변경 시 전체 읽지 않은 메시지 수 업데이트
      fSearchNoReadCountMainBottomDebounced();
    });

    // 페이지 포커스 시 즉시 업데이트
    $(window).on('focus', function() {
      fSearchNoReadCountMainBottomDebounced();
    });

    // 페이지가 보이지 않을 때는 폴링 중지 (성능 최적화)
    document.addEventListener('visibilitychange', function() {
      if (document.hidden) {
        stopNoreadCountPolling();
      } else {
        // 페이지가 다시 보이면 즉시 업데이트 후 폴링 재시작
        fSearchNoReadCountMainBottomDebounced();
        startNoreadCountPolling();
      }
    });

    // 주기적 폴링 시작
    startNoreadCountPolling();
  }

  /**
   * 기업정보 입력 팝업 호출 함수 (모바일용) - 전역 함수
   * 바텀시트 형태로 변경됨
   */
  window.openCompInfoPopup = function() {
    var $panel = document.getElementById('compInfoPanel');

    if (!$panel) {
      commonAlert("기업정보 입력 팝업을 불러올 수 없습니다.<br>페이지를 새로고침 후 다시 시도해주세요.", function() {});
      return;
    }

    // fMemUpdateType 함수가 존재하면 호출 (바텀시트 열기 포함)
    if (typeof fMemUpdateType === 'function') {
      fMemUpdateType('all');
    } else if (typeof openCompInfoPanel === 'function') {
      // fMemUpdateType이 없으면 직접 바텀시트 열기
      openCompInfoPanel();
    }
  };

  /**
   * 기업 인증 확인 함수 (모바일용) - 전역 함수
   * 기업 미인증 시 팝업 표시 및 false 반환
   */
  window.requireCompCertWithGuide = function() {
    // 로그인 여부 확인
    if (!window.NOWJOB_COMP_INFO || !NOWJOB_COMP_INFO.isLogin) {
      openPopup('alert2', {
        content: "로그인 후 이용해주세요.",
        firstButton: '확인',
        removeClose: false,
        firstCallback: function() {
          location.href = '/member/login';
        }
      });
      return false;
    }

    // 기업회원 확인
    if (NOWJOB_COMP_INFO.userType !== 'COMP') {
      openPopup('alert2', {
        content: "기업회원만 채용공고 작성이 가능합니다.",
        firstButton: '확인',
        removeClose: false
      });
      return false;
    }

    // 기업 인증 확인
    if (!NOWJOB_COMP_INFO.hasCompCert) {
      // 안내 팝업 없이 바로 사업자등록 인증 바텀시트 노출
      openCompInfoPopup();
      return false;
    }

    return true;
  };

  /**
   * 채용공고 페이지 이동 처리 공통 함수 (전역 함수)
   * 사업자 등록 확인 후 채용공고 목록/등록 선택 팝업 표시
   */
  window.handleRecruitPageNavigation = function() {
    // 기업 인증 확인
    if (!requireCompCertWithGuide()) {
      return;
    }

    // 바텀 시트 형태의 채용공고 선택 팝업 표시
    openPanel(document.getElementById('recruitSelectPanel'));
  };

  // 채팅방 버튼 클릭 이벤트
  /**
   * 개인 회원 이력서 이동 공통 함수 (전역 함수)
   * - 로그인 및 회원 유형을 확인 후 목록/등록 선택 팝업 출력
   */
  window.openResumeNavigationPopup = function() {
    var is_login = "<?= $this->user->isLogin() ? 'Y' : 'N'; ?>";
    if (is_login !== "Y") {
      openPopup('alert2', {
        content: "로그인 후 이용해주세요.",
        firstButton: '확인',
        removeClose: false,
        firstCallback: function() {
          location.href = '/member/login';
        }
      });
      return false;
    }

    var user_type = "<?= $this->user->getUserType(); ?>";
    if (user_type !== "MEM") {
      openPopup('alert2', {
        content: "일반회원만 이력서 작성이 가능합니다.",
        firstButton: '확인',
        removeClose: false
      });
      return false;
    }

    // 바텀 시트 형태의 이력서 선택 팝업 표시
    openPanel(document.getElementById('resumeSelectPanel'));
    return true;
  };

  window.addEventListener('DOMContentLoaded', function() {
    $("#btnChat").click(function(e) {
      e.preventDefault();

      // 로그인 여부 확인
      var is_login = "<?= $this->user->isLogin() ? 'Y' : 'N'; ?>";
      if (is_login != "Y") {
        // 바텀 시트 형태의 로그인 안내 팝업 표시
        openPanel(document.getElementById('loginGuidePanel'));
        return;
      }

      // 로그인 상태면 바로 채팅방 페이지로 이동
      location.href = '/chat';
    });

    // 공고등록 버튼 클릭 이벤트 (비회원일 경우)
    $("#btnRecruitMenu").click(function(e) {
      e.preventDefault();

      // 로그인 여부 확인
      var is_login = "<?= $this->user->isLogin() ? 'Y' : 'N'; ?>";
      if (is_login != "Y") {
        // 바텀 시트 형태의 로그인 안내 팝업 표시
        openPanel(document.getElementById('loginGuidePanel'));
        return;
      }

      // 로그인 상태면 바로 공고등록 페이지로 이동
      location.href = '/compage/recruit_list';
    });

    // 공고등록 버튼 클릭 이벤트 (기업회원일 경우)
    $("#btnRecruitWrite").click(function(e) {
      e.preventDefault();
      handleRecruitPageNavigation();
    });

    // 이력서등록 버튼 클릭 이벤트 (개인회원일 경우)
    $("#btnResumeWrite").click(function(e) {
      e.preventDefault();
      openResumeNavigationPopup();
    });

    // 최근본공고 버튼 클릭 이벤트 (비로그인일 경우)
    $("#btnJobViewList").click(function(e) {
      e.preventDefault();

      // 로그인 여부 확인
      var is_login = "<?= $this->user->isLogin() ? 'Y' : 'N'; ?>";
      if (is_login != "Y") {
        // 바텀 시트 형태의 로그인 안내 팝업 표시
        openPanel(document.getElementById('loginGuidePanel'));
        return;
      }

      // 로그인 상태면 바로 최근본공고 페이지로 이동
      location.href = '/recruit/job_view_list';
    });
  });
</script>