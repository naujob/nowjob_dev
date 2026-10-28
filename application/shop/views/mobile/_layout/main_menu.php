<?php
// 현재 URL 기반으로 nav 상태 사전 계산 (PHP 5.4 호환: isset 사용)
$_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

// 지도 메뉴 label/href
$_map_label = (strpos($_uri, '/resume/hr_list') !== false) ? '인재지도' : '채용지도';
$_map_href  = (strpos($_uri, '/resume/hr_list') !== false) ? '/resume/hr_map' : '/recruit/job_map';

// 활성 메뉴 key 결정
if      (strpos($_uri, '/recruit/job_list') !== false) { $_active = 'recruit'; }
elseif  (strpos($_uri, '/resume/hr_list')   !== false) { $_active = 'resume';  }
elseif  (strpos($_uri, '/recruit/job_map')  !== false) { $_active = 'map';     }
elseif  (strpos($_uri, '/resume/hr_map')    !== false) { $_active = 'map';     }
elseif  (strpos($_uri, '/chat/')            !== false) { $_active = 'chat';    }
elseif  (strpos($_uri, '/mypage')           !== false) { $_active = 'write';   }
elseif  (strpos($_uri, '/compage')          !== false) { $_active = 'write';   }
else                                                   { $_active = '';        }

// 아이콘 src 생성 헬퍼 (function_exists로 중복 방지)
if (!function_exists('_nav_icon_src')) {
    function _nav_icon_src($name, $active) {
        $suffix = ($name === $active) ? '_click_v2.png' : '_v2.png';
        return '/resource/shop/mobile/img/main_menu/' . $name . $suffix;
    }
}
if (!function_exists('_nav_active')) {
    function _nav_active($name, $active) {
        return ($name === $active) ? 'data-active="true"' : 'data-active="false"';
    }
}

// 채용공고 등록 여부 확인 (COMP 사용자만)
// 주의: 이 데이터는 컨트롤러에서 전달되어야 함 (뷰에서는 데이터 렌더링만 담당)
$_show_recruit_bubble = isset($show_recruit_bubble) ? $show_recruit_bubble : false;
?>

<div class="bottom-nav">
  <!-- 비로그인 사용자 -->
  <?php if (!$this->user->isLogin()) { ?>
    <a href="/recruit/job_list" class="btn-nav-item" <?= _nav_active('recruit', $_active) ?>>
      <img src="<?= _nav_icon_src('recruit', $_active) ?>" alt="채용정보">
      <span>채용정보</span>
    </a>
    <a href="/resume/hr_list" class="btn-nav-item" <?= _nav_active('resume', $_active) ?>>
      <img src="<?= _nav_icon_src('resume', $_active) ?>" alt="인재정보">
      <span>인재정보</span>
    </a>
    <a href="<?= $_map_href ?>" class="btn-nav-item map-nav-item" <?= _nav_active('map', $_active) ?>>
      <img src="<?= _nav_icon_src('map', $_active) ?>" alt="<?= $_map_label ?>">
      <span><?= $_map_label ?></span>
    </a>
    <a href="/member/login" class="btn-nav-item" <?= _nav_active('login', $_active) ?>>
      <img src="<?= _nav_icon_src('login', $_active) ?>" alt="로그인" class="nav-icon-login">
      <span>로그인</span>
    </a>
    <a href="#" id="btnChat" class="btn-nav-item" <?= _nav_active('chat', $_active) ?>>
      <img src="<?= _nav_icon_src('chat', $_active) ?>" alt="채팅">
      <span>채팅</span>
    </a>

    <!-- 기업 로그인 사용자 -->
  <?php } else if ($this->user->getUserType() == "COMP") { ?>
    <a href="/recruit/job_list" class="btn-nav-item" <?= _nav_active('recruit', $_active) ?>>
      <img src="<?= _nav_icon_src('recruit', $_active) ?>" alt="채용정보">
      <span>채용정보</span>
    </a>
    <a href="/resume/hr_list" class="btn-nav-item" <?= _nav_active('resume', $_active) ?>>
      <img src="<?= _nav_icon_src('resume', $_active) ?>" alt="인재정보">
      <span>인재정보</span>
    </a>
    <a href="<?= $_map_href ?>" class="btn-nav-item map-nav-item" <?= _nav_active('map', $_active) ?>>
      <img src="<?= _nav_icon_src('map', $_active) ?>" alt="<?= $_map_label ?>">
      <span><?= $_map_label ?></span>
    </a>
    <button type="button" id="btnRecruitWrite" class="btn-nav-item" <?= _nav_active('write', $_active) ?>>
      <img src="<?= _nav_icon_src('write_rc_rs', $_active) ?>" alt="채용공고등록">
      <span>채용공고등록</span>
    </button>
    <!-- 채용공고 등록 안내 말풍선 (COMP) -->
    <?php if ($_show_recruit_bubble) { ?>
    <svg id="recruitBubble" class="nav-speech-bubble" data-visible="true" viewBox="0 0 500 150" xmlns="http://www.w3.org/2000/svg" style="position: absolute; bottom: 43px; left: 69%; transform: translateX(-50%); width: 260px; height: auto; z-index: 200; cursor: pointer;">
      <rect x="20" y="15" width="460" height="75" rx="28" ry="28" fill="white" stroke="#57CCAF" stroke-width="2"/>
      <path d="M 230 90 L 270 90 L 250 120 Z" fill="white" stroke="#57CCAF" stroke-width="2"/>
      <text x="250" y="58" text-anchor="middle" font-size="30" font-weight="700" fill="#57CCAF" class="speech-text">채용공고를 등록해주세요!</text>
    </svg>
    <?php } ?>
    <a href="#" id="btnChat" class="btn-nav-item" <?= _nav_active('chat', $_active) ?>>
      <img src="<?= _nav_icon_src('chat', $_active) ?>" alt="채팅">
      <span>채팅</span>
    </a>

    <!-- 개인 로그인 사용자 -->
  <?php } else if ($this->user->getUserType() == "MEM") { ?>
    <a href="/recruit/job_list" class="btn-nav-item" <?= _nav_active('recruit', $_active) ?>>
      <img src="<?= _nav_icon_src('recruit', $_active) ?>" alt="채용정보">
      <span>채용정보</span>
    </a>
    <a href="/resume/hr_list" class="btn-nav-item" <?= _nav_active('resume', $_active) ?>>
      <img src="<?= _nav_icon_src('resume', $_active) ?>" alt="인재정보">
      <span>인재정보</span>
    </a>
    <a href="<?= $_map_href ?>" class="btn-nav-item map-nav-item" <?= _nav_active('map', $_active) ?>>
      <img src="<?= _nav_icon_src('map', $_active) ?>" alt="<?= $_map_label ?>">
      <span><?= $_map_label ?></span>
    </a>
    <button type="button" id="btnResumeWrite" class="btn-nav-item" <?= _nav_active('write', $_active) ?>>
      <img src="<?= _nav_icon_src('write_rc_rs', $_active) ?>" alt="이력서등록">
      <span>이력서등록</span>
    </button>
    <!-- 이력서 등록 안내 말풍선 (MEM) -->
    <svg id="resumeRegisteredBubble" class="nav-speech-bubble" data-visible="false" viewBox="0 0 500 150" xmlns="http://www.w3.org/2000/svg" style="position: absolute; bottom: 57px; left: 69%; transform: translateX(-50%); width: 260px; height: auto; z-index: 200; cursor: pointer;">
      <rect x="20" y="15" width="460" height="75" rx="28" ry="28" fill="white" stroke="#57CCAF" stroke-width="2"/>
      <path d="M 230 90 L 270 90 L 250 120 Z" fill="white" stroke="#57CCAF" stroke-width="2"/>
      <text x="250" y="58" text-anchor="middle" font-size="30" font-weight="700" fill="#57CCAF" class="speech-text">이력서가 등록되었어요!</text>
    </svg>
    <a href="#" id="btnChat" class="btn-nav-item" <?= _nav_active('chat', $_active) ?>>
      <img src="<?= _nav_icon_src('chat', $_active) ?>" alt="채팅">
      <span>채팅</span>
    </a>
  <?php } ?>

  <!-- 채팅 배지 -->
  <span id="msgNoReadCountMainBottom"></span>
</div>

<!-- 바텀시트 스타일 -->
<style>
  .bottom-nav {
    padding: 10px;
    height: 80px;
    position: fixed;
    bottom: -2px;
    left: 0;
    right: 0;
    z-index: 100;

  }

  .btn-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 20%;
    padding: 7px 0 6px;
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
    width: 30px;
    height: 30px;
  }

  .btn-nav-item:active {
    color: #222;
  }

  /* 활성 상태일 때 font 색상 진하게 */
  .btn-nav-item[data-active="true"] {
    color: #161616;
  }

  /* 말풍선 스타일 */
  .nav-speech-bubble {
    display: none;
  }

  .nav-speech-bubble[data-visible="true"] {
    display: block;
    animation: speechBubblePopIn 0.3s ease-out;
  }

  @keyframes speechBubblePopIn {
    0% {
      opacity: 0;
      transform: translateX(-50%) scale(0.8);
    }
    100% {
      opacity: 1;
      transform: translateX(-50%) scale(1);
    }
  }

  #msgNoReadCountMainBottom {
    position: absolute;
    top: 10px;
    right: 35px;
    background-color: #F86048;
    width: 15px;
    height: 15px;
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
    // 이력서 최대 2개 제한 확인 (하단 메뉴 → 패널 → 등록 경로 공통)
    comAjax("/mypage_proc/resume_limit_check", {}, function(ret) {
      if (ret.code != "00") {
        commonAlert(ret.msg, function() {});
        return;
      }

      // 새 이력서 작성을 위해 임시저장 데이터 초기화
      // 새 이력서는 ru_idx가 0이므로 해당 키들을 삭제 (resume_write1 의 임시저장은 sessionStorage 에 있다)
      sessionStorage.removeItem("resume_draft_0_step1");
      sessionStorage.removeItem("resume_draft_0_step4");
      sessionStorage.removeItem("resume_from_preview");

      location.href = "/mypage/resume_write1";
    });
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

  // 모든 바텀시트에 Swipe 기능 초기화 (initPanelSwipe는 main.js에서 정의됨)
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

  // 이력서 등록 안내 말풍선 (MEM)
  $(document).on('click', '#resumeRegisteredBubble', function() {
    try {
      var resumeId = localStorage.getItem('nowjob_resume_registered_id') || '';
      localStorage.removeItem('nowjob_resume_registered_bubble');
      localStorage.removeItem('nowjob_resume_registered_id');
      var navigationUrl = resumeId ? ('/mypage/resume_view/' + resumeId) : '/mypage/resume_view/';
      location.href = navigationUrl;
    } catch (e) {
      location.href = '/mypage/resume_view/';
    }
    $('#resumeRegisteredBubble').attr('data-visible', 'false');
  });

  // 채용공고 등록 안내 말풍선 (COMP) - 사업자등록 완료 후에만 등록 페이지 이동
  $(document).on('click', '#recruitBubble', function() {
    // 미인증이면 사업자정보 인증 바텀시트가 열리고 false 반환
    if (typeof requireCompCertWithGuide === 'function' && !requireCompCertWithGuide()) {
      return;
    }
    location.href = '/compage/recruit_write1_1';
  });

  // DOMContentLoaded: MEM 사용자의 이력서 등록 말풍선 상태 복구
  window.addEventListener('DOMContentLoaded', function() {
    var resumeBubble = document.getElementById('resumeRegisteredBubble');
    if (resumeBubble) {
      try {
        if (localStorage.getItem('nowjob_resume_registered_bubble') === '1') {
          resumeBubble.setAttribute('data-visible', 'true');
        }
      } catch (e) {}
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

        // 모든 방의 읽지 않은 메시지 카운트 (탈퇴 회원 제외)
        $.each(result, function(key, room) {
          if (!room || !room.rm_messages) return;

          $.each(room.rm_messages, function(index, message) {
            if (!message || !message.mg_reads) return;

            $.each(message.mg_reads, function(i, readEntry) {
              // mm_status가 'Y'인 경우만 카운트 (탈퇴 회원 제외)
              // mm_status가 없으면 레거시 데이터로 간주하여 카운트
              const isActiveUser = !readEntry.mm_status || readEntry.mm_status === 'Y';
              if (isActiveUser && readEntry.user_id === myId && readEntry.read_yn === 'N') {
                countAll++;
              }
            });
          });
        });

        // 읽지 않은 메시지 상태 표시/숨김 처리 (숫자 대신 배지로 표시)
        if (countAll > 0) {
          $('#msgNoReadCountMainBottom').html('');
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

      var $this = $(this);
      var $img = $this.find('img');
      var currentSrc = $img.attr('src');

      // 로그인 여부 확인
      var is_login = "<?= $this->user->isLogin() ? 'Y' : 'N'; ?>";
      if (is_login != "Y") {
        // 원본 src 저장 (첫 클릭일 때만)
        if (!$img.data('original-src')) {
          $img.data('original-src', currentSrc);
        }

        // 클릭 이미지로 변경 (_v2.png → _click_v2.png)
        var clickSrc = currentSrc.replace(/_v2\.png$/, '_click_v2.png');
        $img.attr('src', clickSrc);
        $img.data('active', 'true');

        // 바텀 시트 형태의 로그인 안내 팝업 표시
        openPanel(document.getElementById('loginGuidePanel'));

        // 바텀시트가 닫히면 이미지 복원
        var observer = new MutationObserver(function(mutations) {
          var loginPanel = document.getElementById('loginGuidePanel');
          if (loginPanel && !loginPanel.classList.contains('on')) {
            var originalSrc = $img.data('original-src');
            if (originalSrc) {
              $img.attr('src', originalSrc);
              $img.data('active', 'false');
            }
            observer.disconnect();
          }
        });

        observer.observe(document.getElementById('loginGuidePanel'), {
          attributes: true,
          attributeFilter: ['class']
        });
        return;
      }

      // 원본 src 저장 (첫 클릭일 때만)
      if (!$img.data('original-src')) {
        $img.data('original-src', currentSrc);
      }

      // 클릭 이미지로 변경 (_v2.png → _click_v2.png)
      var clickSrc = currentSrc.replace(/_v2\.png$/, '_click_v2.png');
      $img.attr('src', clickSrc);
      $img.data('active', 'true');

      // 로그인 상태면 바로 채팅방 페이지로 이동
      setTimeout(function() {
        location.href = '/chat';
      }, 200);
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