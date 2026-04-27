<div class="bottom-nav">
  <!-- 비로그인 사용자 -->
  <?php if (!$this->user->isLogin()) { ?>
    <a href="/recruit/job_list" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/recruit.svg" alt="채용정보">
      <span>채용정보</span>
    </a>
    <a href="/resume/hr_list" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/resume.svg" alt="인재정보">
      <span>인재정보</span>
    </a>
    <a href="/recruit/job_map" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/map.svg" alt="채용지도">
      <span>채용지도</span>
    </a>
    <a href="/member/login" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/ic_menu2_mypage_2.svg" alt="로그인">
      <span>로그인</span>
    </a>
    <a href="#" id="btnChat" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/chat.svg" alt="채팅">
      <span>채팅</span>
    </a>

  <!-- 기업 로그인 사용자 -->
  <?php } else if ($this->user->getUserType() == "COMP") { ?>
    <a href="/recruit/job_list" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/recruit.svg" alt="채용정보">
      <span>채용정보</span>
    </a>
    <a href="/resume/hr_list" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/resume.svg" alt="인재정보">
      <span>인재정보</span>
    </a>
    <a href="/recruit/job_map" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/map.svg" alt="채용지도">
      <span>채용지도</span>
    </a>
    <button type="button" id="btnRecruitWrite" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/write_rc_rs.svg" alt="채용공고등록">
      <span>채용공고등록</span>
    </button>
    <a href="#" id="btnChat" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/chat.svg" alt="채팅">
      <span>채팅</span>
    </a>

  <!-- 개인 로그인 사용자 -->
  <?php } else if ($this->user->getUserType() == "MEM") { ?>
    <a href="/recruit/job_list" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/recruit.svg" alt="채용정보">
      <span>채용정보</span>
    </a>
    <a href="/resume/hr_list" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/resume.svg" alt="인재정보">
      <span>인재정보</span>
    </a>
    <a href="/resume/hr_map" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/map.svg" alt="인재지도">
      <span>인재지도</span>
    </a>
    <button type="button" id="btnResumeWrite" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/write_rc_rs.svg" alt="이력서등록">
      <span>이력서등록</span>
    </button>
    <a href="#" id="btnChat" class="btn-nav-item">
      <img src="/resource/shop/mobile/img/main_menu/chat.svg" alt="채팅">
      <span>채팅</span>
    </a>
  <?php } ?>

  <!-- 채팅 배지 -->
  <span id="msgNoReadCountMainBottom"></span>
</div>

<!-- 바텀시트 스타일 -->
<style>
  .bottom-nav {
    padding: 0;
    height: 56px;
  }

  .btn-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 20%;
    padding: 8px 0 6px;
    font-size: 0.7rem;
    color: #777;
    text-decoration: none;
    border: none;
    background: none;
    cursor: pointer;
    line-height: 1.2;
    gap: 4px;
    transition: color 0.2s;
  }

  .btn-nav-item img {
    width: 24px;
    height: 24px;
  }

  .btn-nav-item:active {
    color: #222;
  }

  #msgNoReadCountMainBottom {
    position: absolute;
    top: 8px;
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
  'bottom-sheet-content-container',
  'bottom-sheet-content-body'
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
  'bottom-sheet-content-body'
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
  'bottom-sheet-content-body'
);
?>

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
  });
</script>
