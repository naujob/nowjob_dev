<?php require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_template.php'); ?>

<input type="hidden" name="user_type" value="<?= $user_type ?>" />
<!-- iOS 포함 모든 환경에서 사용자 아이디를 일관되게 참조하기 위한 hidden 필드 -->
 <!-- user_id:현재 로그인한 사용자의 ID -->
<input type="hidden" id="user_id" name="user_id" value="<?= $this->user->getUserId(); ?>" /> 
<input type="hidden" name="rm_status" value="<?= isset($room["rm_status"]) ? $room["rm_status"] : "" ?>" />
<input type="hidden" name="recruit_phone" value="<?= isset($recruit["mm_phone"]) ? $recruit["mm_phone"] : "" ?>" />
<input type="hidden" name="recruit_name" value="<?= isset($recruit["mm_comp_name"]) ? $recruit["mm_comp_name"] : "" ?>" />
<!-- 상대방 ID (MongoDB에 입장 전 정보가 없을 때 사용) -->
<input type="hidden" id="opponent_user_id" name="opponent_user_id" value="<?= isset($opponent_user_id) ? $opponent_user_id : "" ?>" />
<!-- 개인회원 ID (전화하기 버튼 JS 실시간 반영용) -->
<input type="hidden" id="individual_user_id" value="<?= htmlspecialchars(isset($individual_user_id) ? $individual_user_id : '', ENT_QUOTES, 'UTF-8') ?>" />
<!-- 전화 걸 대상 번호 (JS에서 필요시 사용) -->
<input type="hidden" id="call_target_phone" value="<?= htmlspecialchars(isset($call_target_phone) ? $call_target_phone : '', ENT_QUOTES, 'UTF-8') ?>" />
<!-- 이 채팅방에 고정된 공고/이력서 (제안하기/지원하기 시 상대방 쪽 값 고정용) -->
<!-- room에 없으면 URL 파라미터에서 참고 (다이렉트 채팅 초기 진입 시 job_post.php에서 전달) -->
<input type="hidden" id="room_rc_idx" value="<?= isset($room['rc_idx']) && !empty($room['rc_idx']) ? $room['rc_idx'] : (isset($_GET['rc_idx']) ? $_GET['rc_idx'] : '') ?>" />
<input type="hidden" id="room_ru_idx" value="<?= isset($room['ru_idx']) && !empty($room['ru_idx']) ? $room['ru_idx'] : (isset($_GET['ru_idx']) ? $_GET['ru_idx'] : '') ?>" />
<!-- 다이렉트 채팅방 여부 (JS에서 지원/제안 시 상대방 값 선택 로직 분기) -->
<input type="hidden" id="is_direct_chat" value="<?= isset($is_direct_chat) && $is_direct_chat ? 'Y' : 'N' ?>" />
<!-- 상대방의 대표이력서 ru_idx (다이렉트 채팅에서 ru_idx 없을 때 대비용, 기업회원 전용) -->
<input type="hidden" id="opponent_main_resume_ru_idx" value="<?= isset($opponent_main_resume['ru_idx']) ? $opponent_main_resume['ru_idx'] : '' ?>" />
<!-- 상대방의 대표채용공고 rc_idx (다이렉트 채팅에서 rc_idx 없을 때 대비용, 개인회원 전용) -->
<input type="hidden" id="opponent_main_recruit_rc_idx" value="<?= isset($opponent_main_recruit['rc_idx']) ? $opponent_main_recruit['rc_idx'] : '' ?>" />

<script>
    // 뒤로/나가기 버튼 대상 페이지 결정
    (function() {

        var params = new URLSearchParams(window.location.search);
        // backUrl 파라미터로 직접 전달받은 뒤로가기 URL 사용
        var backUrl = params.get('backUrl') ? decodeURIComponent(params.get('backUrl')) : null;

        // 호환성: 이전 from 파라미터 지원
        if (!backUrl) {
            var from = params.get('from') || '';
            var currentUserType = $("input[name='user_type']").val() || "MEM";
            var backMap;
            var defaultBackUrl;

            if (currentUserType === "COMP") {
                // 기업회원용 매핑
                backMap = {
                    applied: '/chat/chat_list3', // 지원받은 인재
                    proposed_job: '/chat/index_comp', // 제안 보낸 인재
                    talent_received: '/chat/index_comp', // 제안받은 인재
                    talent_sent: '/chat/chat_list3', // 제안보낸 인재
                    hr_list: '/chat/index_comp' // 인재 리스트
                };
                defaultBackUrl = '/chat/chat_list3'; // 기업용 기본값
            } else {
                // 개인회원용 매핑 (MEM)
                backMap = {
                    applied: '/chat/chat_list', // 지원한 공고
                    proposed_job: '/chat/chat_list2', // 제안받은 공고
                    talent_received: '/chat/chat_list2', // 제안받은 공고
                    talent_sent: '/chat/chat_list', // 지원한 공고
                    hr_list: '/chat/chat_list' // 지원한 공고
                };
                defaultBackUrl = '/chat/chat_list'; // 개인용 기본값
            }

            backUrl = backMap[from] || defaultBackUrl;
        }

        // user_type이 다른 경우 backUrl 변환 (발신자와 수신자의 페이지 매핑)
        var currentUserType = $("input[name='user_type']").val() || "MEM";
        if (backUrl) {
            // 개인회원(MEM)이 기업용 backUrl을 받은 경우 변환
            if (currentUserType === "MEM") {
                if (backUrl.indexOf('chat_list3') !== -1) {
                    backUrl = '/chat/chat_list2'; // 기업의 "지원받은 인재" → 개인의 "제안받은 공고"
                } else if (backUrl.indexOf('index_comp') !== -1) {
                    backUrl = '/chat/chat_list'; // 기업의 "제안 보낸 인재" → 개인의 "지원한 공고"
                }
            }
            // 기업회원(COMP)이 개인용 backUrl을 받은 경우 변환
            else if (currentUserType === "COMP") {
                if (backUrl === '/chat/chat_list') {
                    backUrl = '/chat/chat_list3'; // 개인의 "지원한 공고" → 기업의 "지원받은 인재"
                } else if (backUrl === '/chat/chat_list2') {
                    backUrl = '/chat/index_comp'; // 개인의 "제안받은 공고" → 기업의 "제안 보낸 인재"
                }
            }
        }

        window.backUrl = backUrl;

        // iOS WebView에서 더 확실하게 동작하도록 즉시 실행 + DOMContentLoaded 둘 다 처리
        /**
         * 채팅화면 상단의 뒤로가기/나가기(.js-chat-back) 버튼에
         * 모바일(iOS/Android/웹) 환경별로 적합한 이벤트(touch/click)를 등록하고,
         * 페이지 진입 파라미터(from)에 맞는 뒤로가기 URL을 적용한다.
         * (나가기 기능은 현재 임시로 주석처리되어 있음, 단순 이동만 수행)
         */
        function initChatBackButtons() {
            let lastRun = 0;

            function handleBack(e) {
                // 중복 실행 방지 (touch → click)
                const now = Date.now();
                if (now - lastRun < 600) return;
                lastRun = now;

                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                }

                // img 태그에서 이벤트가 발생한 경우 부모 a 태그 찾기
                const a = this.closest('.js-chat-back') || this;

                // [임시 주석처리: 나중에 다시 사용 예정] 채팅방 나가기 기능
                /*
                if (a.classList.contains('btn-exit')) {
                    exitChatRoom(a.dataset.backUrl || backUrl);
                } else {
                    location.href = a.dataset.backUrl || backUrl;
                }
                */
                // 나가기 기능 주석처리로 인해 뒤로가기만 처리
                location.href = a.dataset.backUrl || window.backUrl;
                
                return false;
            }

            document.querySelectorAll('.js-chat-back').forEach(function(a) {
                a.dataset.backUrl = window.backUrl;

                // iOS WebView에서 touchstart가 더 안정적 (다른 파일들에서도 사용하는 패턴)
                a.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    handleBack.call(this, e);
                }, { passive: false });

                // touchend도 함께 처리
                a.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    handleBack.call(this, e);
                }, { passive: false });

                // click (PC/Android)
                a.addEventListener('click', handleBack);
                
                // img 태그에도 이벤트 전달 (iOS에서 img 클릭 시 이벤트가 링크로 전달되지 않을 수 있음)
                var img = a.querySelector('img');
                if (img) {
                    img.addEventListener('touchstart', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleBack.call(a, e);
                    }, { passive: false });
                    
                    img.addEventListener('touchend', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleBack.call(a, e);
                    }, { passive: false });
                    
                    img.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleBack.call(a, e);
                    });
                }
            });
        }

        // DOMContentLoaded와 즉시 실행 둘 다 처리
        // 만약 문서가 아직 로딩 중이라면 (DOMContentLoaded 이벤트 전이면)
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChatBackButtons);
        } else {
            initChatBackButtons();
        }
        
        // 추가 안전장치: 약간의 지연 후에도 시도
        setTimeout(initChatBackButtons, 100);

        // iOS 감지 함수
        function isIOS() {
            return /iPhone|iPad|iPod/i.test(navigator.userAgent);
        }

        // 채팅방 나가기 함수 - [임시 주석처리: 나중에 다시 사용 예정]
        /*
        function exitChatRoom(redirectUrl) {
            var rm_idx = "<?= $rm_idx ?>";
            var g_user_id = ensureValidUserId();

            if (!g_user_id) {
                return;
            }

            if (!rm_idx || rm_idx == "") {
                commonAlert("채팅방 정보가 없습니다.", function() {
                    location.href = redirectUrl || '/chat/chat_list';
                });
                return;
            }

            // iOS WebView에서는 confirm/alert이 touch 이벤트 내에서 막히므로 바로 실행
            if (isIOS()) {
                // iOS에서는 확인 없이 바로 실행 (WebView에서 confirm이 동작하지 않음)
                doExitRoom(rm_idx, g_user_id, redirectUrl);
                return;
            }

            // Android/Web에서는 확인 메시지 표시
            if (typeof commonConfirm === "function") {
                commonConfirm("채팅방을 나가시겠습니까?", function() {
                    doExitRoom(rm_idx, g_user_id, redirectUrl);
                });
            } else {
                if (confirm("채팅방을 나가시겠습니까?")) {
                    doExitRoom(rm_idx, g_user_id, redirectUrl);
                }
            }
        }

        // 실제 나가기 처리
        function doExitRoom(rm_idx, g_user_id, redirectUrl) {
            comAjax("/chat_proc/exit_room", {
                rm_idx: rm_idx,
                user_id: g_user_id
            }, function(ret) {
                if (ret.code == "00") {
                    // 시스템 메시지를 소켓으로 전송
                    if (ret.data && ret.data.system_message && typeof socket !== 'undefined' && socket.connected) {
                        var systemMsg = ret.data.system_message;
                        // rm_info가 없으면 기본값 설정
                        if (!rm_info || !rm_info.rm_users) {
                            rm_info = {
                                rm_users: [{
                                    user_id: $('#otherid').val()
                                }, {
                                    user_id: g_user_id
                                }]
                            };
                        }
                        // 소켓으로 시스템 메시지 전송
                        systemMsg.backUrl = window.backUrl;
                        socket.emit("message_insert", systemMsg, rm_info.rm_users);
                    }

                    // 목록 페이지로 이동 (캐시 무시를 위한 타임스탬프 추가)
                    var finalUrl = redirectUrl || '/chat/chat_list';
                    var separator = finalUrl.indexOf('?') > -1 ? '&' : '?';
                    location.href = finalUrl + separator + '_t=' + Date.now();
                } else {
                    commonAlert(ret.msg || "채팅방 나가기에 실패했습니다.", function() {});
                }
            });
        }
        */
    })();
</script>

<script>
  // 전역 함수: 이력서 카드 렌더링/재렌더링 (초기 로딩 + 지원하기 완료 후 실시간 갱신 공용)
  window.renderChatResumeCard = function(row) {
    var tpl = document.getElementById('tpl_chat_resume');
    var listContainer = document.getElementById('chatResumeList');

    if (!tpl || !listContainer || !row) {
      return;
    }

    var $c = tpl.innerHTML;

    $c = $c.replaceAll('{{ru_idx}}',        row.ru_idx        || '');
    $c = $c.replaceAll('{{mm_idx}}',        row.mm_idx        || '');
    $c = $c.replaceAll('{{ru_resume_img}}', row.ru_resume_img || '');
    $c = $c.replaceAll('{{book_yn}}',       row.book_yn       || '');
    $c = $c.replaceAll('{{ru_work_img}}',   row.ru_work_img   || '');
    $c = $c.replaceAll('{{ru_recom_badge}}', row.ru_recom_badge || '');
    $c = $c.replaceAll('{{mm_name}}',       row.mm_name       || '');
    $c = $c.replaceAll('{{ru_name}}',       row.ru_name       || '');
    $c = $c.replaceAll('{{ru_week}}',       row.ru_week       || '');
    $c = $c.replaceAll('{{mm_nation}}',     row.mm_nation     || '');
    $c = $c.replaceAll('{{ma_age}}',        row.ma_age        || '');
    $c = $c.replaceAll('{{ru_price_type}}', row.ru_price_type || '');
    $c = $c.replaceAll('{{ru_work_type}}',  row.ru_work_type  || '');
    $c = $c.replaceAll('{{ad_addr}}',       row.ad_addr       || '');
    $c = $c.replaceAll('{{mm_comp_name}}',  row.mm_comp_name  || '');
    $c = $c.replaceAll('{{status_label}}',  row.status_label  || '');
    $c = $c.replaceAll('{{has_chat}}',      'false');
    $c = $c.replaceAll('{{chat_rm_idx}}',   '');

    listContainer.innerHTML = $c;
    // simple 모드에서는 토글 버튼이 없으므로 이벤트 바인딩 불필요
  };

  (function() {
    // DOM이 준비되면 초기 렌더링 (chatResumeData는 COMP 분기에서만 PHP가 내려줌)
    function initChatResumeCard() {
      if (typeof chatResumeData !== 'undefined' && chatResumeData) {
        window.renderChatResumeCard(chatResumeData);
      }
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initChatResumeCard);
    } else {
      initChatResumeCard();
    }
  })();

  // 전역 함수: 공고 카드 재렌더링
  window.renderChatRecruitCard = function(recruitData) {
    var template = document.getElementById('tpl_chat_recruit');
    if (!template || !recruitData) return;

    // 공고 상태 계산 함수
    function getStatusLabel(sdate, edate) {
      var now = new Date();
      var startDate = new Date(sdate);
      var endDate = new Date(edate);

      if (startDate <= now && now <= endDate) {
        return "모집중";
      } else if (endDate < now) {
        return "마감";
      } else if (startDate > now) {
        return "대기중";
      }
      return "";
    }

    var statusLabel = getStatusLabel(recruitData.rc_sdate, recruitData.rc_edate);

    var html = template.innerHTML
      .replaceAll('{{rc_idx}}', recruitData.rc_idx || '')
      .replaceAll('{{rt_recruit_img}}', recruitData.rt_recruit_img || '')
      .replaceAll('{{rt_work_img}}', recruitData.rt_work_img || '')
      .replaceAll('{{rt_recom_badge}}', recruitData.rt_recom_badge || '')
      .replaceAll('{{rt_work_type}}', recruitData.rt_work_type || '')
      .replaceAll('{{rc_name}}', recruitData.rc_name || '')
      .replaceAll('{{rc_nation_nm}}', recruitData.rc_nation_nm || '')
      .replaceAll('{{rc_age_nm}}', recruitData.rc_age_nm || '')
      .replaceAll('{{ad_addr}}', fRemoveBigAdrJS(recruitData.ad_addr || ''))
      .replaceAll('{{book_yn}}', (recruitData.book_yn === 'Y') ? 'on' : '')
      .replaceAll('{{rc_price_type}}', recruitData.rt_price_type || '')
      .replaceAll('{{rc_price_pay}}', recruitData.rt_price_pay || '')
      .replaceAll('{{status_label}}', statusLabel);

    var listContainer = document.getElementById('chatRecruitCardList');
    if (listContainer) {
      listContainer.innerHTML = html;
    }
  };

  // MEM(개인) 사용자 - recruit_card_template.php 렌더링 (초기화)
  (function() {
    var recruitData = <?= isset($recruit) && !empty($recruit) ? json_encode($recruit, JSON_UNESCAPED_UNICODE) : 'null' ?>;

    if (!recruitData) {
      return; // recruit 데이터가 없으면 렌더링하지 않음
    }

    // DOM이 준비되면 렌더링
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() { window.renderChatRecruitCard(recruitData); });
    } else {
      window.renderChatRecruitCard(recruitData);
    }
  })();
</script>

<div class="page page-chat-view">
    <!-- 제안/지원 안내 말풍선 클릭 시 열리는 이력서/채용공고 상세 팝업 (hr_list.php/job_list.php와 동일 패턴) -->
    <div id="hrPostPopup" class="hr-post-popup" data-state="closed"></div>
    <div id="jobPostPopup" class="job-post-popup" data-state="closed"></div>

    <div class="top-bar top-bar--chat">
        <!--<a href="javascript:_replace('/chat/chat_list');" class="btn-replace"><span class="sr-text">이전으로</span></a>-->
        <a href="javascript:void(0);" class="btn-replace js-chat-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title" id="rm_subject" style="display: flex; align-items: center; gap: 0.7rem; font-size: 1.1rem;max-width: 70%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;margin: -10px 0 0;">
          <?php if ($user_type == "COMP"): ?>
            <?php
            // 다이렉트 채팅의 경우 대표이력서 사용, 정식 제안의 경우 현재 이력서 사용
            $displayResume = ($is_direct_chat && isset($opponent_main_resume) && !empty($opponent_main_resume)) ? $opponent_main_resume : $resume;
            $profileImg = getResumeImg($displayResume);
            $mmSex = isset($displayResume["mm_sex"]) ? trim($displayResume["mm_sex"]) : "";
            $defaultAvatar = "/resource/shop/web/img/avatar_resume_w.png";
            if ($mmSex === "남") {
              $defaultAvatar = "/resource/shop/web/img/avatar_icon2_m.png";
            }
            ?>
            <div class="img" style="width: 2.5rem; height: 2.5rem; border-radius: 60px; overflow: hidden; flex-shrink: 0;">
              <img src="<?= $profileImg ?>" alt="프로필" style="width: 100%; height: 100%; object-fit: cover;" onerror="if(this.src.indexOf('avatar_icon2_m.png')===-1 && this.src.indexOf('avatar_resume_w.png')===-1){this.onerror=null; this.src='<?= $defaultAvatar ?>';}">
            </div>
            <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0;"><?= isset($displayResume["ru_name"]) ? $displayResume["ru_name"] : "" ?></span>
          <?php else: ?>
            <div class="img" style="width: 2.5rem; height: 2.5rem; border-radius: 50%; overflow: hidden; flex-shrink: 0;">
              <img src="<?= !empty($recruit["rc_thumb"]) ? $recruit["rc_thumb"] : "" ?>" alt="공고이미지" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
            </div>
            <span><?= !empty($recruit["mm_comp_name"]) ? $recruit["mm_comp_name"] : "1:1채팅" ?></span>
          <?php endif; ?>
        </h1>
        <div class="rt-group">
            <button type="button" class="attach-item js-attach-call" data-attach="call" aria-label="안심콜" style="position: relative;left: -15px;">
                <img src="/resource/shop/mobile/img/chat_top_call.png" alt="안심콜" style="width: 2rem; height: 2rem;">
            </button>
            <button type="button" class="btn-leave-list" id="btnLeaveChatRoom" aria-label="채팅방 나가기">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </button>
        </div>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="chat-view-header">
            <!-- 채용정보 -->
            <?php if ($user_type == "COMP") : ?>
                <!-- 기업회원: 채용공고정보 -->
                <input type="hidden" id="otherid" value="<?= $recruit["mm_id"] ?>">
                <?php
                // 공고 상태 계산 함수
                function getStatusLabel($sdate, $edate) {
                    $now = new DateTime();
                    $startDate = new DateTime($sdate);
                    $endDate = new DateTime($edate);

                    if ($startDate <= $now && $now <= $endDate) {
                        return "모집중";
                    } else if ($endDate < $now) {
                        return "마감";
                    } else if ($startDate > $now) {
                        return "대기중";
                    }
                    return "";
                }

                $statusLabel = getStatusLabel($recruit["rc_sdate"], $recruit["rc_edate"]);
                ?>
                <!-- 채팅 공고 카드 데이터를 JS에 전달 -->
                <script>
                  var recruitData = <?= isset($recruit) && !empty($recruit) ? json_encode($recruit, JSON_UNESCAPED_UNICODE) : 'null' ?>;
                </script>

                <!-- 공고 카드 템플릿 포함 -->
                <?php
                  $from = input_get("from");
                  $tpl_data_label = ($from === "proposed_job") ? "제안받은 공고" : "지원한 공고";
                ?>
                <?php $tpl_id = 'tpl_chat_recruit'; $tpl_simple_mode = true; ?>
                <?php include(APPPATH . 'views/mobile/_partial/recruit_card_template.php'); ?>
                <ul class="result-list result-list-card" id="chatRecruitCardList" data-visible="<?= (!empty($is_direct_chat_room)) ? 'false' : 'true' ?>"></ul>
                <!-- //기업회원 공고정보 -->

                <div id="comp_top_2" class="chat-appointment" style="display:none;">
                    <div class="container">
                        <div class="inline-group">
                            <p class="col date" id="top_dt1"><?= substr($room["rm_itv_dt"], 0, 4) ?>년 <?= substr($room["rm_itv_dt"], 5, 2) ?>월 <?= substr($room["rm_itv_dt"], 8, 2) ?>일</p>
                            <button type="button" class="btn-round btn-secondary bntInterviewCompCancel">면접취소</button>
                        </div>
                    </div>
                </div>

                <div id="comp_top_3" class="chat-appointment" style="display:none;">
                    <div class="container">
                        <div class="inline-group">
                            <p class="col date" id="top_dt2"><?= substr($room["rm_rec_dt"], 0, 4) ?>년 <?= substr($room["rm_rec_dt"], 5, 2) ?>월 <?= substr($room["rm_rec_dt"], 8, 2) ?>일</p>
                            <button type="button" class="btn-round btn-secondary bntRecruitCompCancel">채용취소</button>
                        </div>
                    </div>
                </div>

                <!-- 일정 -->
                <!-- 사용 안 함 - 주석처리 시작 -->
                <!--
                <div id="comp_top_1" class="chat-appointment" style="display:none;">
                    <div class="container">
                        <div class="add-new">
                            <p class="title">* 면접일을 확정하셔야 연락처가 공유됩니다.</p>
                            <div class="btn-wrap">
                                <button type="button" aria-expanded="false" aria-controls="interviewForm" id="interviewShow" class="btn-round btn-primary js-toggle-form">면접일 확정</button>
                                <button type="button" aria-expanded="false" aria-controls="startDateForm" id="dateShow" class="btn-round btn-light js-toggle-form">채용확정</button>
                            </div>
                        </div>
                        <div id="interviewForm" class="appointment-form" hidden>
                            <div class="container">
                                <form action="/">
                                    <p class="form-label align-center">면접일을 입력해주세요.</p>
                                    <div class="calendar">
                                        <div class="js-datepicker"></div>
                                        <label>
                                            <span class="sr-text">날짜 선택</span>
                                            <input type="text" name="interviewDate" readonly aria-required="true" required class="form-text js-date-value">
                                        </label>
                                    </div>
                                    <div class="btn-wrap">
                                        <button type="reset" aria-expanded="false" aria-controls="interviewForm" class="btn-round btn-secondary js-toggle-form">취소</button>
                                        <button type="button" class="btn-round btn-tertiary" id="btnInerview">확인</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="startDateForm" class="appointment-form" hidden>
                            <div class="container">
                                <form action="/">
                                    <p class="form-label align-center">근로일을 입력해주세요.</p>
                                    <div class="calendar">
                                        <div class="js-datepicker"></div>
                                        <label>
                                            <span class="sr-text">날짜 선택</span>
                                            <input type="text" name="startDate" readonly aria-required="true" required class="form-text js-date-value">
                                        </label>
                                    </div>
                                    <div class="btn-wrap">
                                        <button type="reset" aria-expanded="false" aria-controls="startDateForm" class="btn-round btn-secondary js-toggle-form">취소</button>
                                        <button type="button" class="btn-round btn-tertiary" id="btnStartWrok">확인</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <script>
                            (function() {
                                var calendars = [];
                                document.querySelectorAll('.js-toggle-form').forEach(function(btn) {
                                    btn.addEventListener('click', function() {
                                        var id = this.getAttribute('aria-controls'),
                                            isOpen = this.getAttribute('aria-expanded'),
                                            formCon = null;
                                        if (!id || !document.getElementById(id)) return;
                                        formCon = document.getElementById(id);
                                        if (isOpen == 'false') {
                                            document.querySelectorAll('.appointment-form').forEach(function(el, idx) {
                                                el.hidden = true;
                                                el.querySelector('.js-date-value').value = '';
                                                if (calendars[idx]) {
                                                    calendars[idx].clear();
                                                }
                                            });
                                            document.querySelectorAll('.js-toggle-form').forEach(function(el) {
                                                el.setAttribute('aria-expanded', 'false');
                                            });
                                            formCon.hidden = false;
                                            formCon.querySelector('.js-toggle-form').setAttribute('aria-expanded', 'true');
                                            this.setAttribute('aria-expanded', 'true');
                                        } else {
                                            formCon.hidden = true;
                                            formCon.querySelector('.js-toggle-form').setAttribute('aria-expanded', 'false');
                                            this.setAttribute('aria-expanded', 'false');
                                        }
                                    });
                                });
                                function chgMonth(instance) {
                                    var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                                        month = Number(instance.currentMonth) + 1,
                                        year = instance.currentYear;
                                    $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
                                }
                                window.addEventListener('DOMContentLoaded', function() {
                                    if (typeof flatpickr == 'undefined') return false;
                                    calendars = flatpickr('.js-datepicker', {
                                        inline: true,
                                        locale: 'ko',
                                        dateFormat: 'Y-m-d',
                                        ariaDateFormat: 'Y-m-d',
                                        altInput: true,
                                        nextArrow: '<span class="sr-text">다음</span>',
                                        prevArrow: '<span class="sr-text">이전</span>',
                                        disableMobile: true,
                                        onChange: function(selectedDates, dateStr, instance) {
                                            if (!dateStr) return;
                                            var dayTxt = new Date(selectedDates).getDay();
                                            var dayArr = ['일', '월', '화', '수', '목', '금', '토'];
                                            instance.element.parentNode.querySelector('.js-date-value').value = dateStr + '(' + dayArr[dayTxt] + ')';
                                        },
                                        onMonthChange: function(selectedDates, dateStr, instance) {
                                            chgMonth(this);
                                        },
                                        onDayCreate: function(dObj, dStr, fp, dayElem) {
                                            var day = new Date(dayElem.getAttribute('aria-label')).getDay();
                                            if (day == 0) {
                                                dayElem.classList.add('sun');
                                            } else if (day == 6) {
                                                dayElem.classList.add('sat');
                                            }
                                        },
                                        onReady: function() {
                                            chgMonth(this);
                                        }
                                    });
                                });
                            }());
                        </script>
                    </div>
                </div>
                -->
                <!-- 사용 안 함 - 주석처리 끝 -->
                <!-- //일정 -->
            <?php else : ?>
                <!-- 공고정보 -->
                <input type="hidden" id="otherid" value="<?= $recruit["mm_id"] ?>">
                <?php
                // 공고 상태 계산 함수
                function getStatusLabel($sdate, $edate) {
                    $now = new DateTime();
                    $startDate = new DateTime($sdate);
                    $endDate = new DateTime($edate);

                    if ($startDate <= $now && $now <= $endDate) {
                        return "모집중";
                    } else if ($endDate < $now) {
                        return "마감";
                    } else if ($startDate > $now) {
                        return "대기중";
                    }
                    return "";
                }

                $statusLabel = getStatusLabel($recruit["rc_sdate"], $recruit["rc_edate"]);
                ?>
                <!-- 채팅 공고 카드 데이터를 JS에 전달 -->
                <script>
                  var recruitData = <?= isset($recruit) && !empty($recruit) ? json_encode($recruit, JSON_UNESCAPED_UNICODE) : 'null' ?>;
                </script>

                <!-- 공고 카드 템플릿 포함 -->
                <?php
                  $from = input_get("from");
                  $tpl_data_label = ($from === "proposed_job") ? "제안받은 공고" : "지원한 공고";
                ?>
                <?php $tpl_id = 'tpl_chat_recruit'; $tpl_simple_mode = true; ?>
                <?php include(APPPATH . 'views/mobile/_partial/recruit_card_template.php'); ?>
                <ul class="result-list result-list-card" id="chatRecruitCardList" data-visible="<?= (!empty($is_direct_chat_room)) ? 'false' : 'true' ?>"></ul>
                <!-- //공고정보 -->
                <div id="mem_top_2" class="chat-appointment" style="display:none;">
                    <div class="container">
                        <div class="inline-group">
                            <p class="col date" id="top_dt1"><?= substr($room["rm_itv_dt"], 0, 4) ?>년 <?= substr($room["rm_itv_dt"], 5, 2) ?>월 <?= substr($room["rm_itv_dt"], 8, 2) ?>일</p>
                            <button type="button" class="btn-round btn-secondary bntInterviewCancel">면접취소</button>
                        </div>
                    </div>
                </div>

                <div id="mem_top_3" class="chat-appointment" style="display:none;">
                    <div class="container">
                        <div class="inline-group">
                            <p class="col date" id="top_dt2"><?= substr($room["rm_rec_dt"], 0, 4) ?>년 <?= substr($room["rm_rec_dt"], 5, 2) ?>월 <?= substr($room["rm_rec_dt"], 8, 2) ?>일</p>
                            <button type="button" class="btn-round btn-secondary bntRecruitCancel">채용취소</button>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </div>
        <div class="chat-view-body">
            <div class="content" id="chat_body">
                <div class="container" id="chat_list" style="    padding-top: 2.1875rem;">
                  

                </div>
            </div>
            <!-- 숨겨진 파일 input (여러 개 선택 가능) -->
            <input type="file" id="chatPhotoInput" accept="image/*" multiple style="display:none;">
            <input type="file" id="chatFileInput" multiple style="display:none;">

            <!-- 파일 업로드 진행률 표시 (화면 중앙) -->
            <div id="uploadProgressContainer" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; width: 80%; max-width: 300px; padding: 2rem; background: rgba(0,0,0,0.9); color: white; border-radius: 12px; text-align: center;">
                <div style="font-size: 14px; margin-bottom: 1rem;">
                    <span id="uploadFileName" style="font-weight: bold; display: block; margin-bottom: 0.5rem; word-break: break-all;"></span>
                    <span>업로드 중... </span><span id="uploadPercent" style="font-weight: bold;">0</span>%
                </div>
                <div style="background: rgba(255,255,255,0.2); height: 6px; border-radius: 3px; overflow: hidden;">
                    <div id="uploadProgressBar" style="background: #1a9e7c; height: 100%; width: 0%; transition: width 0.2s ease;"></div>
                </div>
            </div>

            <!-- 첨부메뉴 -->
            <div id="attachMenu" class="attach-menu" data-state="closed">
                <button type="button" class="attach-item" data-attach="photo">
                    <svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <rect x="3" y="12" width="42" height="30" rx="6" fill="#1a9e7c"></rect>
                        <path d="M17 12l3.2-4.6A2 2 0 0 1 21.8 6.5h4.4a2 2 0 0 1 1.6.9L31 12z" fill="#1a9e7c"></path>
                        <circle cx="24" cy="27" r="9" fill="#ffffff"></circle>
                        <circle cx="24" cy="27" r="5.2" fill="#1a9e7c"></circle>
                    </svg>
                    <span>사진</span>
                </button>
                <button type="button" class="attach-item" data-attach="file">
                    <svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <path d="M4 12a5 5 0 0 1 5-5h9l4 5h13a5 5 0 0 1 5 5v20a5 5 0 0 1-5 5H9a5 5 0 0 1-5-5z" fill="#1a9e7c"></path>
                        <rect x="21.8" y="17" width="4.4" height="17" rx="2.2" fill="#ffffff"></rect>
                        <rect x="15.5" y="23.3" width="17" height="4.4" rx="2.2" fill="#ffffff"></rect>
                    </svg>
                    <span>파일</span>
                </button>
                <button type="button" class="attach-item" data-attach="propose">
                    <svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <path d="M9 8a4 4 0 0 1 4-4h15l11 11v25a4 4 0 0 1-4 4H13a4 4 0 0 1-4-4z" fill="#1a9e7c"></path>
                        <path d="M28 4l11 11H30a2 2 0 0 1-2-2z" fill="#ffffff" opacity="0.55"></path>
                        <rect x="16" y="22" width="16" height="3.4" rx="1.7" fill="#ffffff"></rect>
                        <rect x="16" y="29" width="11" height="3.4" rx="1.7" fill="#ffffff"></rect>
                    </svg>
                    <span><?= ($user_type == "COMP") ? "제안하기" : "지원하기" ?></span>
                </button>
                 <button type="button" class="attach-item js-attach-call" data-attach="call">
                    <svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <path d="M13.6 5.5c1.6-.9 3.6-.4 4.6 1.2l3.4 5.5c1 1.6.6 3.6-.9 4.7l-2.6 2c-.5.4-.7 1.1-.4 1.7 1.9 4.2 5.2 7.5 9.4 9.4.6.3 1.3.1 1.7-.4l2-2.6c1.1-1.5 3.1-1.9 4.7-.9l5.5 3.4c1.6 1 2.1 3 1.2 4.6l-2 3.5c-1.1 1.9-3.3 2.9-5.5 2.4C22.6 38 10 25.4 7.7 12.6c-.4-2.2.6-4.4 2.5-5.5z" fill="#1a9e7c"></path>
                    </svg>
                    <span>안심콜</span>
                </button>
            </div>

            <!-- 입력폼 -->
            <div class="write-form">
                <button type="button" id="btnAttachToggle" class="btn-attach-toggle" data-state="closed" aria-label="첨부 메뉴 열기/닫기">
                    <svg class="icon-add" width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <circle cx="24" cy="24" r="24" fill="#57CCAF"></circle>
                        <path d="M24 14v20M14 24h20" stroke="#ffffff" stroke-width="4.6" stroke-linecap="round"></path>
                    </svg>
                    <svg class="icon-close" width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <circle cx="24" cy="24" r="24" fill="#57CCAF"></circle>
                        <path d="M16.5 16.5l15 15M31.5 16.5l-15 15" stroke="#ffffff" stroke-width="4.6" stroke-linecap="round"></path>
                    </svg>
                </button>
                <label>
                    <span class="sr-text">메세지입력</span>
                    <textarea id="chatTxt" name="chatTxt" autocorrect="off" autocapitalize="off" autocomplete="off" placeholder="메세지를 입력해주세요." rows="1"></textarea>
                </label>
                <button type="button" id="btnSend" class="btn-primary">전송</button>
            </div>
        </div>

    </div><!-- //page-content -->
</div>

<!-- 이미지 라이트박스 -->
<div id="chatImgLightbox" class="chat-lightbox" style="display:none;">
    <div class="chat-lightbox-main">
        <img id="chatImgLightboxImg" src="" alt="">
    </div>
    <div id="chatImgLightboxThumbs" class="chat-lightbox-thumbs"></div>
    <p id="chatImgLightboxCounter" class="chat-lightbox-counter"></p>
    <button type="button" id="chatImgLightboxDownloadBtn" class="chat-lightbox-download-arrow" aria-label="사진 저장">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 4v11m0 0l-4.5-4.5M12 15l4.5-4.5M5 19h14" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </button>
</div>


<?php if ($user_type == "COMP") : ?>
    <!-- 채용정보 + 인재정보 -->
    <div id="chatSummary" class="layer-panel card-type" tabindex="-1">
        <div class="panel-container">
            <div class="panel-header">
                <h2 class="sr-text">채용주요정보</h2>
            </div>
            <div class="panel-body">
                <div class="panel-content">
                    <!-- 인재 정보 헤더 (가로 레이아웃) -->
                    <div class="talent-info-header" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background-color: #f5f5f5; border-radius: 8px; margin-bottom: 1.5rem;">
                        <?php
                        $resumeImg = getResumeImg($resume);
                        $mmSex = isset($resume["mm_sex"]) ? trim($resume["mm_sex"]) : "";
                        $defaultAvatar = "/resource/shop/web/img/avatar_resume_w.png";
                        if ($mmSex === "남") {
                            $defaultAvatar = "/resource/shop/web/img/avatar_icon2_m.png";
                        } else if ($mmSex === "여") {
                            $defaultAvatar = "/resource/shop/web/img/avatar_resume_w.png";
                        }
                        ?>
                        <img src="<?= $resumeImg ?>" alt="인재프로필" style="width: 3.5rem; height: 3.5rem; object-fit: cover; border-radius: 50%; flex-shrink: 0;" onerror="if(this.src.indexOf('avatar_icon2_m.png')===-1 && this.src.indexOf('avatar_resume_w.png')===-1){this.onerror=null; this.src='<?= $defaultAvatar ?>';}">
                        <div style="flex: 1; min-width: 0;">
                            <p style="margin: 0; font-size: 0.875rem; color: #666;">지원자</p>
                            <p style="margin: 0.25rem 0 0 0; font-size: 1rem; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?= !empty($resume["ru_name"]) ? $resume["ru_name"] : $resume["mm_name"] ?>
                            </p>
                        </div>
                    </div>

                    <div class="item-info-summary">
                        <div class="title-wrap">
                            <div class="label-row">
                                <p class="line-label">채용정보</p>
                                <a href="/recruit/job_post/<?= $recruit["rc_idx"] ?>" class="view-all">상세보기</a>
                            </div>
                            <!-- 제목 -->
                            <h3 class="secondary-title"><?= $recruit["rc_name"] ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="sticky-bottom view-footer">
                    <button type="button" aria-controls="chatSummary" class="btn btn-primary js-toggle-panel">확인</button>
                </div>
            </div>
        </div>
    </div>
    <!-- //채용정보 + 인재정보 -->
<?php else : ?>
    <!-- 인재정보 -->
    <div id="chatSummary" class="layer-panel card-type" tabindex="-1">
        <div class="panel-container">
            <div class="panel-header">
                <h2 class="sr-text">인재정보</h2>
            </div>
            <div class="panel-body">
                <div class="panel-content">

                    <div class="item-info-summary">
                        <div class="title-wrap">
                            <div class="label-row">
                                <p class="line-label">인재정보</p>
                                <a href="/resume/hr_post/<?= $resume["ru_idx"] ?>" class="view-all">상세보기</a>
                            </div>
                            <!-- 제목: 이력서 제목 우선, 없으면 이름 표시 -->
                            <h3 class="secondary-title">
                                <?php
                                $resumeImg = getResumeImg($resume);
                                $mmSex = isset($resume["mm_sex"]) ? trim($resume["mm_sex"]) : "";
                                $defaultAvatar = "/resource/shop/web/img/avatar_resume_w.png";
                                if ($mmSex === "남") {
                                    $defaultAvatar = "/resource/shop/web/img/avatar_icon2_m.png";
                                } else if ($mmSex === "여") {
                                    $defaultAvatar = "/resource/shop/web/img/avatar_resume_w.png";
                                }
                                ?>
                                <img src="<?= $resumeImg ?>" alt="프로필" style="width: 2.5rem; height: 2.5rem; object-fit: cover; border-radius: 50%; margin-right: 0.7rem; vertical-align: middle;" onerror="if(this.src.indexOf('avatar_icon2_m.png')===-1 && this.src.indexOf('avatar_resume_w.png')===-1){this.onerror=null; this.src='<?= $defaultAvatar ?>';}">
                                <?= !empty($resume["ru_name"]) ? $resume["ru_name"] : $resume["mm_name"] ?>
                            </h3>
                            <!-- 지원한/제안받은 이력서 구분 -->
                            <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #999;">
                              <?php
                              $from = input_get("from");
                              echo ($from === "proposed_job") ? "제안받은 이력서" : "지원한 이력서";
                              ?>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel-footer">
                <div class="sticky-bottom view-footer">
                    <button type="button" aria-controls="chatSummary" class="btn btn-primary js-toggle-panel">확인</button>
                </div>
            </div>
        </div>
    </div>
    <!-- //인재정보 -->
<?php endif; ?>

<!-- 채팅방 내 제안하기(COMP)/지원하기(MEM) 선택 바텀시트 (1단계: 내 항목 선택) -->
<input type="hidden" id="proposeMqType" value="<?= ($user_type == "COMP") ? "RESUME" : "RECRUIT" ?>" />
<div id="proposeSelectPanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header" style="background: #57CCAF; color: #fff; padding-top: 1.25rem; margin-bottom: 20px;">
            <h2 class="panel-title" style="color:#fff;"><?= ($user_type == "COMP") ? "제안할 채용공고 선택" : "지원할 이력서 선택" ?></h2>
            <button type="button" aria-controls="proposeSelectPanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body" style="padding: 2rem 1.375rem 1.5rem;">
            <div class="panel-content">
                <div class="terms-wrap">
                    <div class="post-list">
                        <ul class="list">
                            <?php if ($user_type == "COMP") : ?>
                                <?php $hasProposeItems = isset($my_recruit_list) && is_array($my_recruit_list) && count($my_recruit_list) > 0; ?>
                                <?php if ($hasProposeItems) : ?>
                                    <?php foreach ($my_recruit_list as $row) : ?>
                                        <?php if (empty($row["rc_idx"])) { continue; } ?>
                                        <li style="border: 1px solid #ccc; border-radius: 10px; margin-bottom: 10px;">
                                            <div class="selectable-item">
                                                <label class="form-checkbox">
                                                    <input type="radio" name="chk_propose[]" value="<?= htmlspecialchars($row["rc_idx"], ENT_QUOTES, 'UTF-8') ?>" data-propose-id="<?= htmlspecialchars($row["rc_idx"], ENT_QUOTES, 'UTF-8') ?>" data-name="<?= htmlspecialchars(isset($row["rc_name"]) ? $row["rc_name"] : '', ENT_QUOTES, 'UTF-8') ?>">
                                                    <span class="label"></span>
                                                    <span class="sr-text">선택</span>
                                                </label>
                                                <a href="javascript:void(0);" class="angle-link" style="background:none;">
                                                    <strong><?= isset($row["rc_name"]) ? $row["rc_name"] : '' ?></strong>
                                                </a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="is-empty_v2">
                                        <p>등록된 채용공고가 없습니다.</p>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php $hasProposeItems = isset($my_resume_list) && is_array($my_resume_list) && count($my_resume_list) > 0; ?>
                                <?php if ($hasProposeItems) : ?>
                                    <?php foreach ($my_resume_list as $row) : ?>
                                        <?php if (empty($row["ru_idx"])) { continue; } ?>
                                        <li style="border: 1px solid #ccc; border-radius: 10px; margin-bottom: 10px;">
                                            <div class="selectable-item">
                                                <label class="form-checkbox">
                                                    <input type="radio" name="chk_propose[]" value="<?= htmlspecialchars($row["ru_idx"], ENT_QUOTES, 'UTF-8') ?>" data-propose-id="<?= htmlspecialchars($row["ru_idx"], ENT_QUOTES, 'UTF-8') ?>" data-name="<?= htmlspecialchars(isset($row["ru_name"]) ? $row["ru_name"] : '', ENT_QUOTES, 'UTF-8') ?>">
                                                    <span class="label"></span>
                                                    <span class="sr-text">선택</span>
                                                </label>
                                                <a href="javascript:void(0);" class="angle-link" style="background:none;">
                                                    <strong><?= isset($row["ru_name"]) ? $row["ru_name"] : '' ?></strong>
                                                </a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="is-empty_v2">
                                        <p>등록된 이력서가 없습니다.</p>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
             <div class="panel-footer" style="position: relative; margin-top: 60px; ">
                <div class="sticky-bottom" style="width:100%;">
                    <button type="button" id="btnProposeNext" style="width:100%;" class="btn btn-primary bottom-sheet-action"><?= (isset($is_direct_chat) && $is_direct_chat) ? "다음" : "보내기" ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- //1단계: 내 항목 선택 -->

<?php if (isset($is_direct_chat) && $is_direct_chat) : ?>
<!-- 채팅방 내 제안하기(COMP)/지원하기(MEM) 선택 바텀시트 (2단계: 상대방 항목 선택, 다이렉트 채팅방 전용) -->
<div id="opponentSelectPanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header" style="background: #57CCAF; color: #fff; padding-top: 1.25rem; margin-bottom: 20px;">
            <h2 class="panel-title" style="color:#fff;"><?= ($user_type == "COMP") ? "상대방 이력서 선택" : "상대방 채용공고 선택" ?></h2>
            <button type="button" aria-controls="opponentSelectPanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body" style="padding: 2rem 1.375rem 1.5rem;">
            <div class="panel-content">
                <div class="terms-wrap">
                    <div class="post-list">
                        <ul class="list">
                            <?php if ($user_type == "COMP") : ?>
                                <?php $hasOpponentItems = isset($opponent_resume_list) && is_array($opponent_resume_list) && count($opponent_resume_list) > 0; ?>
                                <?php if ($hasOpponentItems) : ?>
                                    <?php foreach ($opponent_resume_list as $row) : ?>
                                        <?php if (empty($row["ru_idx"])) { continue; } ?>
                                        <li style="border: 1px solid #ccc; border-radius: 10px; margin-bottom: 10px;">
                                            <div class="selectable-item">
                                                <label class="form-checkbox">
                                                    <input type="radio" name="chk_opponent[]" value="<?= htmlspecialchars($row["ru_idx"], ENT_QUOTES, 'UTF-8') ?>" data-opponent-id="<?= htmlspecialchars($row["ru_idx"], ENT_QUOTES, 'UTF-8') ?>" data-name="<?= htmlspecialchars(isset($row["ru_name"]) ? $row["ru_name"] : '', ENT_QUOTES, 'UTF-8') ?>">
                                                    <span class="label"></span>
                                                    <span class="sr-text">선택</span>
                                                </label>
                                                <a href="javascript:void(0);" class="angle-link" style="background:none;">
                                                    <strong><?= isset($row["ru_name"]) ? $row["ru_name"] : '' ?></strong>
                                                </a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="is-empty_v2">
                                        <p>상대방이 아직 이력서를 등록하지 않아 제안할 수 없습니다.</p>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php $hasOpponentItems = isset($opponent_recruit_list) && is_array($opponent_recruit_list) && count($opponent_recruit_list) > 0; ?>
                                <?php if ($hasOpponentItems) : ?>
                                    <?php foreach ($opponent_recruit_list as $row) : ?>
                                        <?php if (empty($row["rc_idx"])) { continue; } ?>
                                        <li style="border: 1px solid #ccc; border-radius: 10px; margin-bottom: 10px;">
                                            <div class="selectable-item">
                                                <label class="form-checkbox">
                                                    <input type="radio" name="chk_opponent[]" value="<?= htmlspecialchars($row["rc_idx"], ENT_QUOTES, 'UTF-8') ?>" data-opponent-id="<?= htmlspecialchars($row["rc_idx"], ENT_QUOTES, 'UTF-8') ?>" data-name="<?= htmlspecialchars(isset($row["rc_name"]) ? $row["rc_name"] : '', ENT_QUOTES, 'UTF-8') ?>">
                                                    <span class="label"></span>
                                                    <span class="sr-text">선택</span>
                                                </label>
                                                <a href="javascript:void(0);" class="angle-link" style="background:none;">
                                                    <strong><?= isset($row["rc_name"]) ? $row["rc_name"] : '' ?></strong>
                                                </a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="is-empty_v2">
                                        <p>상대방이 아직 채용공고를 등록하지 않아 지원할 수 없습니다.</p>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
             <div class="panel-footer" style="position: relative; margin-top: 60px; ">
                <div class="sticky-bottom" style="width:100%;">
                    <button type="button" id="btnOpponentSend" style="width:100%;" class="btn btn-primary bottom-sheet-action">보내기</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- //2단계: 상대방 항목 선택 -->
<?php endif; ?>
<!-- //채팅방 내 제안하기/지원하기 선택 바텀시트 -->

<!-- 채팅방 나가기 바텀시트 -->
<?php renderSelectBottomSheet(
    'leaveChatRoomSheet',
    '채팅방 나가기',
    '<p>채팅방을 나가면 목록에서 삭제됩니다.<br>상대방에게는 나가기 알림이 전송되며, 채팅방은 상대방 목록에 그대로 유지됩니다.</p>',
    '취소', 'btnLeaveCancel', '',
    '나가기', 'btnLeaveConfirm', ''
); ?>

<!-- 사진 저장 방식 선택 바텀시트 -->
<?php renderSelectBottomSheet(
    'chatImgSaveSheet',
    '사진 저장',
    '',
    '이 사진만 저장하기', 'btnSaveThisImg', '',
    '모든 사진 저장하기', 'btnSaveAllImg', '',
    'chat-img-save-sheet'
); ?>

<!-- 메시지 삭제 확인 바텀시트 -->
<?php renderSelectBottomSheet(
    'deleteMessageSheet',
    '메시지 삭제',
    '<p>이 메시지를 삭제하시겠습니까?<br>상대방이 이미 읽은 메시지는 삭제할 수 없습니다.</p>',
    '취소', 'btnDeleteMsgCancel', '',
    '삭제', 'btnDeleteMsgConfirm', ''
); ?>

<script>
    // background-image 이미지 로드 실패 시 기본 이미지로 교체
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            // thumb-bg 클래스를 가진 모든 요소에 대해 이미지 로드 확인
            document.querySelectorAll('.thumb.thumb-bg').forEach(function(thumb) {
                var bgImage = thumb.style.backgroundImage;
                if (!bgImage) return;

                // background-image URL 추출
                var imageUrl = bgImage.replace(/url\(['"]?([^'"]+)['"]?\)/, '$1');
                var defaultImg = thumb.getAttribute('data-default-img');

                if (!imageUrl || !defaultImg) return;

                // 이미지 로드 테스트
                var img = new Image();
                img.onerror = function() {
                    // 이미지 로드 실패 시 기본 이미지로 교체
                    thumb.style.backgroundImage = "url('" + defaultImg + "')";
                };
                img.src = imageUrl;
            });
        });
    })();

    // ===== 네이티브 통신: 팝업 상태 확인/제어 (뒤로가기 제어용) =====
    window.isPopupOpen = function() {
        return ($("#chatImgLightbox").is(':visible') ||
                $("#chatImgSaveSheet").hasClass('on'));
    };

    window.closeAllPopups = function() {
        if ($("#chatImgLightbox").is(':visible')) {
            $("#chatImgLightbox").hide();
            lightboxImages = [];
            lightboxIndex = 0;
        }
        if ($("#chatImgSaveSheet").hasClass('on')) {
            closePanel(document.getElementById("chatImgSaveSheet"));
        }
    };

    var last_send_dt = "";
    var requested_rm_idx = "<?= $rm_idx ?>"; // 페이지가 요청한 원래 방 번호 (절대 변경 금지)
    var rm_idx = "<?= $rm_idx ?>";
    var is_direct_chat = "<?= isset($is_direct_chat) && $is_direct_chat ? 'Y' : 'N' ?>" === "Y";
    // 다이렉트 채팅방에서 상대방의 실제 이력서/채용공고가 하나라도 있는지 (없으면 지원/제안 자체를 막음)
    var opponent_has_items = "<?php
        if ($user_type == 'COMP') {
            echo (isset($opponent_resume_list) && is_array($opponent_resume_list) && count($opponent_resume_list) > 0) ? 'Y' : 'N';
        } else {
            echo (isset($opponent_recruit_list) && is_array($opponent_recruit_list) && count($opponent_recruit_list) > 0) ? 'Y' : 'N';
        }
    ?>" === "Y";
    // 1부: 상대방의 대표이력서 자동 선택용 변수 (다이렉트 채팅, COMP만 사용)
    var opponentMainResumeIdx = "<?= (isset($opponent_main_resume) && $opponent_main_resume) ? $opponent_main_resume['ru_idx'] : '' ?>";
    var opponentMainResumeName = <?= (isset($opponent_main_resume) && $opponent_main_resume) ? json_encode($opponent_main_resume['ru_name'], JSON_UNESCAPED_UNICODE) : '""' ?>;

    // 2부: 본인(MEM) 대표이력서 자동 선택용
    var myMainResumeIdx = "<?= (isset($my_main_resume) && $my_main_resume) ? $my_main_resume['ru_idx'] : '' ?>";
    var myMainResumeName = <?= (isset($my_main_resume) && $my_main_resume) ? json_encode($my_main_resume['ru_name'], JSON_UNESCAPED_UNICODE) : '""' ?>;

    // 2부: 상대(COMP) 채용공고 자동 선택용 (시작 공고 우선, 없으면 첫 번째)
    var opponentMainRecruitIdx = "<?= (isset($opponent_main_recruit) && $opponent_main_recruit) ? $opponent_main_recruit['rc_idx'] : '' ?>";
    var opponentMainRecruitName = <?= (isset($opponent_main_recruit) && $opponent_main_recruit) ? json_encode($opponent_main_recruit['rc_name'], JSON_UNESCAPED_UNICODE) : '""' ?>;
    var rm_info = {};
    var rm_start = 0;
    var rm_length = 20;
    var rm_count = 0;
    var userIdAlertShown = false;

    // ✅ 페이지 이탈 시 소켓 정리: 이전 방에 대한 join 상태와 이벤트 리스너 제거
    window.addEventListener('pagehide', function() {
        if (typeof socket !== 'undefined' && socket) {
            // 현재 방에서 나가기
            socket.emit("room_leave", { rm_idx: Number(requested_rm_idx) });

            // 이전 방에 대한 이벤트 리스너 정리
            socket.off("room_info_return");
            socket.off("message_list_return");
            socket.off("message_insert_return");
            socket.off("room_join_return");
            socket.off("message_read_count_return");

            console.log("[chat_view] 페이지 이탈: room_leave 발송 (rm_idx=" + requested_rm_idx + "), 이벤트 리스너 정리 완료");
        }
    });

    // 키보드가 올라올 때 입력창 아래 빈 공간 방지 + 스크롤 복원 (mobile webview 호환성)
    (function() {
        var viewportSyncTimeout = null;
        var scrollRestoreTimeout = null;
        var layoutViewportHeight = null; // 초기값을 null로 설정, DOMContentLoaded 후에 초기화

        // 💪 Body의 overflow를 항상 hidden으로 유지 (네이티브 override 대응)
        if (document.body) {
            document.body.style.overflow = 'hidden';
            document.body.style.overflowY = 'hidden';
        }
        if (document.documentElement) {
            document.documentElement.style.overflow = 'hidden';
            document.documentElement.style.overflowY = 'hidden';
        }

        // ✅ DOMContentLoaded 후에 초기 높이 정확히 캡처
        window.addEventListener('DOMContentLoaded', function() {
            layoutViewportHeight = window.innerHeight;
        });

        function syncChatViewportHeight() {
            if (viewportSyncTimeout) {
                clearTimeout(viewportSyncTimeout);
            }

            viewportSyncTimeout = setTimeout(function() {
                var pageChatView = document.querySelector('.page-chat-view');
                var chatViewBody = document.querySelector('.chat-view-body');
                var chatContent = document.querySelector('.chat-view-body .content');
                var writeForm = document.querySelector('.write-form');

                if (!pageChatView) return;

                var currentVisualHeight = window.visualViewport ? window.visualViewport.height : window.innerHeight;

                // 첫 호출일 때 layoutViewportHeight가 아직 설정 안 됨 → window.innerHeight로 초기화
                if (!layoutViewportHeight) {
                    layoutViewportHeight = window.innerHeight;
                }

                var isKeyboardVisible = currentVisualHeight < layoutViewportHeight - 50;

                if (isKeyboardVisible) {
                    // 키보드가 열려 있음: 실제 보이는 영역에 맞춰 높이 강제 설정 + 스크롤 활성화
                    pageChatView.style.height = currentVisualHeight + 'px';
                    pageChatView.style.overflowY = 'auto';

                    // 스크롤 강제 복원 (여러 번 실행하여 확실히 함)
                    restoreScrollPosition();
                } else {
                    // 키보드가 닫혀 있음: 원래 CSS로 복귀
                    pageChatView.style.height = '';
                    pageChatView.style.overflowY = '';
                }
            }, 50);
        }

        function restoreScrollPosition() {
            // 강력한 스크롤 복원 (multiple fallbacks)
            scrollBottom();

            // 직접 스크롤 컨테이너 제어 - .page-chat-view 기준으로 통일
            var pageChatView = document.querySelector('.page-chat-view') || document.querySelector('.page.page-chat-view');
            if (pageChatView) {
                pageChatView.scrollTop = pageChatView.scrollHeight; // 스크롤 위치를 아래로 이동
            }

            // 레이아웃 리플로우 후 다시 스크롤 (연속 조정)
            if (scrollRestoreTimeout) {
                clearTimeout(scrollRestoreTimeout);
            }

            scrollRestoreTimeout = setTimeout(function() {
                scrollBottom();

                if (chatBody) {
                    chatBody.scrollTop = chatBody.scrollHeight;
                }

                // 한 번 더 (애니메이션 완료 후)
                setTimeout(function() {
                    scrollBottom();
                    if (chatBody) {
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }
                }, 100);
            }, 50);
        }

        // visualViewport 지원 환경 (최신 안드로이드 WebView)
        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', syncChatViewportHeight);
        }

        // window.resize 폴백 (구형 웹뷰)
        window.addEventListener('resize', syncChatViewportHeight);

        // 입력창 포커스 시 즉시 스크롤 복원
        document.addEventListener('DOMContentLoaded', function() {
            var chatInput = document.getElementById('chatTxt');
            if (chatInput) {
                chatInput.addEventListener('focus', function() {
                    // 즉시 한 번 실행
                    syncChatViewportHeight();
                    restoreScrollPosition();

                    // 키보드 애니메이션 완료 후 추가 조정
                    setTimeout(function() {
                        syncChatViewportHeight();
                        restoreScrollPosition();
                    }, 100);
                });

                chatInput.addEventListener('focusout', function() {
                    setTimeout(function() {
                        syncChatViewportHeight();
                    }, 100);
                });
            }
        });
    })();

    // 주소 정리: 채팅 헤더에 표시되는 채용지 주소와 인재정보 주소를 공용 헬퍼로 축약
    document.addEventListener('DOMContentLoaded', function() {
        // 채용지 주소 처리 (recruit_card_row 파셜의 area-addr 클래스)
        document.querySelectorAll('.chat-view-header .area-addr').forEach(function(el) {
            var addr = el.dataset.addr || '';
            el.textContent = fRemoveBigAdrJS(addr);
        });

        // 상단 제목 클릭 시 팝업으로 이력서/공고 열기 (기업회원) 또는 페이지 이동 (개인회원)
        var rmSubject = document.getElementById('rm_subject');
        if (rmSubject) {
            rmSubject.style.cursor = 'pointer';
            rmSubject.addEventListener('click', function(e) {
                e.preventDefault();
                var userType = $("[name='user_type']").val();
                var isDirectChat = document.getElementById('is_direct_chat').value === 'Y';

                if (userType === "COMP") {
                    // 기업회원: 개인회원의 대표이력서를 팝업으로 열기
                    var ruIdx = "";

                    if (isDirectChat) {
                        // 다이렉트 채팅: 더미 ru_idx 무시하고 opponent_main_resume_ru_idx 사용
                        ruIdx = document.getElementById('opponent_main_resume_ru_idx').value;
                    } else {
                        // 정식 제안/지원: 실제 ru_idx 사용
                        ruIdx = "<?= isset($resume['ru_idx']) ? $resume['ru_idx'] : '' ?>";
                    }

                    if (ruIdx && typeof window.openHrPostPopup === 'function') {
                        window.openHrPostPopup(ruIdx);
                    }
                } else {
                    // 개인회원: 기업회원의 채용공고 상세페이지로 이동
                    var rcIdx = "";

                    if (isDirectChat) {
                        // 다이렉트 채팅: 더미 rc_idx 무시하고 opponent_main_recruit_rc_idx 사용
                        rcIdx = document.getElementById('opponent_main_recruit_rc_idx').value;
                    } else {
                        // 정식 지원: 실제 rc_idx 사용
                        rcIdx = "<?= isset($recruit['rc_idx']) ? $recruit['rc_idx'] : '' ?>";
                    }

                    if (rcIdx && typeof window.openJobPostPopup === 'function') {
                        window.openJobPostPopup(rcIdx);
                    }
                }
            });
        }

        // 채팅 뷰 헤더의 채용정보/인재정보 카드 클릭 이벤트 처리
        // 링크나 버튼을 클릭한 경우는 기본 동작 유지, 그 외 영역 클릭 시 상세페이지로 이동
        $(document).on('click', '.chat-view-header .result-list-card .btn-detail', function(e) {
            e.preventDefault();
            var contentLi = $(this).closest('.content-li');

            // 인재정보 카드인 경우 (data-ru_idx가 있으면)
            var ru_idx = contentLi.attr('data-ru_idx');
            if (ru_idx) {
                window.location.href = '/resume/hr_post/' + ru_idx;
                return;
            }

            // 채용정보 카드인 경우 (data-rc_idx가 있으면)
            var rc_idx = contentLi.attr('data-rc_idx');
            if (rc_idx) {
                window.location.href = '/recruit/job_post/' + rc_idx;
                return;
            }
        });
    });

    // 채팅방 나가기 바텀시트 트리거
    // 나가기 버튼 클릭
    $(document).on('click', '#btnLeaveChatRoom', function(e) {
        e.preventDefault();
        var userId = ensureValidUserId();
        if (!userId) return;

        var sheet = document.getElementById('leaveChatRoomSheet');
        if (sheet && typeof openPanel === 'function') {
            openPanel(sheet);
        }
    });

    // 바텀시트 버튼 이벤트
    $(document).on('click', '#btnLeaveCancel', function() {
        var sheet = document.getElementById('leaveChatRoomSheet');
        if (typeof closePanel === 'function') {
            closePanel(sheet);
        }
    });

    $(document).on('click', '#btnLeaveConfirm', function() {
        if (typeof confirmLeaveChatRoom === 'function') {
            confirmLeaveChatRoom();
        }
    });

    // 채팅방 나가기 확정 처리
    function confirmLeaveChatRoom() {
        var g_user_id = ensureValidUserId();
        if (!g_user_id || !rm_idx) return;

       //console.log('나가기 요청 시작', { user_id: g_user_id, rm_idx: rm_idx });
        //g_user_id, rm_idx를 서버로 전송하여 채팅방 나가기 처리 g_user_id:나가는 주체
        comAjax("/chat_proc/exit_room", { rm_idx: rm_idx }, function(ret) {
            closePanel(document.getElementById('leaveChatRoomSheet'));

           // console.log('나가기 응답', ret);

            if (ret.code != "00") {
                BrowserLogger.error('나가기 실패', { msg: ret.msg });
                commonAlert(ret.msg || "채팅방 나가기에 실패했습니다.", function() {});
                return;
            }

            //console.log('나가기 성공', {});

            // 상대방에게 나가기 시스템 메시지 브로드캐스트
            if (ret.data && ret.data.system_message && typeof socket !== 'undefined' && socket.connected) {
                var systemMsg = ret.data.system_message;
                var users = (rm_info && rm_info.rm_users) ? rm_info.rm_users : [
                    { user_id: $('#otherid').val() },
                    { user_id: g_user_id }
                ];
                systemMsg.backUrl = window.backUrl;
                socket.emit("message_insert", systemMsg, users);
            }

            // 원래 들어온 목록으로 이동 (backUrl은 파일 최상단 IIFE에서 계산)
            if (typeof backUrl !== 'undefined') {
                window.location.href = backUrl;
            } else {
                window.location.href = '/chat/chat_list';
            }
        });
    }

    // ✅ 중복 메시지 방지는 DOM 체크로 처리 (processedIdx 제거)
    // DOM에 이미 있는 메시지는 showMessage에서 자동으로 건너뛰기
    
    // 🔥 채팅방 입장 시 읽음 카운트 정보 저장 (room_join_return에서 받은 정보)
    let pendingReadCounts = {};

    // 제안하기/지원하기 완료 후 자동 메시지 전송 플래그 (URL 파라미터 기반)
    var urlParams = new URLSearchParams(window.location.search);
    var autoSend = urlParams.get('auto_send') === 'true';
    var autoSendRcIdx = urlParams.get('rc_idx');
    var autoSendMqType = urlParams.get('mq_type');
    var autoSendRuIdx = urlParams.get('ru_idx');
    window.autoSendFirstMessage = autoSend ? {
        enabled: true,
        rm_idx: rm_idx,
        rc_idx: autoSendRcIdx,
        mq_type: autoSendMqType,
        ru_idx: autoSendRuIdx,
        messageListChecked: false,
        targetUserId: null,
        rm_info_received: false
    } : null;

    // 채팅방 진입 시 processedIdx 초기화
    function resetProcessedIdx() {
        processedIdx = [];
    }
    
    // 🔥 읽음 카운트 업데이트 함수 (메시지 요소가 있을 때 사용)
    function updateReadCount(mg_idx, count) {
        var $msgElement = $("#mg_idx_" + mg_idx);
        if ($msgElement.length > 0) {
            if (count == 0) {
                $msgElement.find(".readcount").html("");
            } else {
                $msgElement.find(".readcount").html(count);
            }
            return true; // 업데이트 성공
        }
        return false; // 메시지 요소 없음
    }

    // 메시지 삭제 관련 이벤트 바인딩 ---------------------------------------------------------------
    var pendingDeleteMgIdx = null;
    var longPressTimer = null;

    $(document).on('touchstart mousedown', '.msg-item.chat-bubble[id^="mg_idx_"]', function (e) {
        var $el = $(this);
        longPressTimer = setTimeout(function () {
            pendingDeleteMgIdx = $el.attr('id').replace('mg_idx_', '');
            var sheet = document.getElementById('deleteMessageSheet');
            if (typeof openPanel === 'function') {
                openPanel(sheet);
            }
        }, 500);
    });

    $(document).on('touchend touchmove mouseup mouseleave', '.msg-item.chat-bubble[id^="mg_idx_"]', function () {
        clearTimeout(longPressTimer);
    });

    $(document).on('click', '#btnDeleteMsgCancel', function() {
        var sheet = document.getElementById('deleteMessageSheet');
        pendingDeleteMgIdx = null;
        if (typeof closePanel === 'function') {
            closePanel(sheet);
        }
    });

    $(document).on('click', '#btnDeleteMsgConfirm', function() {
        if (!pendingDeleteMgIdx || !rm_idx) return;

        socket.emit('message_delete', {
            rm_idx: rm_idx,
            mg_idx: parseInt(pendingDeleteMgIdx),
            user_id: g_user_id
        });

        var sheet = document.getElementById('deleteMessageSheet');
        if (typeof closePanel === 'function') {
            closePanel(sheet);
        }
        pendingDeleteMgIdx = null;
    });

    // 메시지 삭제 결과 수신 핸들러
    if (typeof socket !== 'undefined') {
        socket.on('message_delete_return', function (payload) {
            var mg_idx = payload.data ? payload.data.mg_idx : payload.mg_idx;
            $('#mg_idx_' + mg_idx).fadeOut(300, function() {
                $(this).remove();
            });
        });

        socket.on('message_delete_fail', function (payload) {
            var reason = payload.data ? payload.data.reason : null;
            var msg = '';
            if (reason === 'already_read') {
                msg = '상대방이 이미 읽은 메시지는 삭제할 수 없습니다.';
            } else if (reason === 'not_owner') {
                msg = '본인이 보낸 메시지만 삭제할 수 있습니다.';
            } else if (reason === 'not_found') {
                msg = '메시지를 찾을 수 없습니다.';
            } else {
                msg = '메시지를 삭제할 수 없습니다.';
            }
            commonAlert(msg, function() {});
        });
    }

    // 공통 날짜/시간 유틸 ---------------------------------------------------------------------------------

    // getCurrentDate 함수 정의 (iOS/Android 공통 사용)
    function getCurrentDate() {
        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth();
        var today = date.getDate();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        var milliseconds = date.getMilliseconds();
        return new Date(year, month, today, hours, minutes, seconds, milliseconds);
    }

    // 제안하기/지원하기 완료 후 첫 메시지 자동 전송 함수 (전역 함수)
    window.sendFirstMessageAfterProposal = function(targetUserId, targetRmIdx) {
        if (!targetUserId || !targetRmIdx || typeof socket === 'undefined' || !socket) {
            return;
        }
        
        var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();

        // 항상 targetRmIdx 사용 (서버가 다른 rm_idx로 응답해도 신뢰하지 않기)
        var actualRmIdx = targetRmIdx;
        
        // 사용자 타입에 따른 메시지 내용 결정
        var current_user_type = typeof user_type !== 'undefined' ? user_type : $("[name='user_type']").val();
        var messageText = "";

        // URL 파라미터에서 from 값 확인
        var fromParam = new URLSearchParams(window.location.search).get('from');

        if (fromParam === 'direct_chat') {
            // 다이렉트 채팅인 경우
            messageText = current_user_type === "COMP"
                ? "기업회원이 채팅을 요청하였습니다."
                : "개인회원이 채팅을 요청하였습니다.";
        } else if (current_user_type === "COMP") {
            // 기업이 개인에게 제안보낼 경우
            messageText = "안녕하세요.\n채용중인 일자리를 제안드립니다.";
        } else {
            // 개인이 기업에게 지원할 경우
            messageText = "안녕하세요.\n채용공고에 이력서를 지원합니다.";
        }
        
        // 첫 메시지 생성
        // 다이렉트 채팅이 아닌 경우 (정식 제안/지원) mg_type을 "propose"로 설정하여 "열어보기" 버튼 표시
        var msgType = (fromParam === 'direct_chat') ? "text" : "propose";

        var firstMessage = {
            rm_idx: Number(actualRmIdx),
            user_id: g_user_id,
            chat_type: "recruit",
            mg_type: msgType,
            mg_text: messageText,
            mg_req_dt: "",
            mg_img: "",
            mg_file: "",
            mg_imoti: "",
            mg_dt: getCurrentDate(),
            mg_reads: []
        };

        // mg_type이 "propose"인 경우 공고/이력서 정보 추가
        if (msgType === "propose") {
            firstMessage.mg_mq_type = window.autoSendFirstMessage && window.autoSendFirstMessage.mq_type ? window.autoSendFirstMessage.mq_type : "";
            firstMessage.mg_rc_idx = window.autoSendFirstMessage && window.autoSendFirstMessage.rc_idx ? window.autoSendFirstMessage.rc_idx : "";
            firstMessage.mg_ru_idx = window.autoSendFirstMessage && window.autoSendFirstMessage.ru_idx ? window.autoSendFirstMessage.ru_idx : "";
        }
        
        // 읽음 상태 설정
        firstMessage.mg_reads.push({
            read_yn: "Y",
            user_id: g_user_id
        });
        firstMessage.mg_reads.push({
            read_yn: "N",
            user_id: targetUserId
        });
        
        // 사용자 목록 생성 (MongoDB에 상대방이 없어도 클라이언트에서 확실히 포함)
        var firstUsers = [];
        var userIdsInList = []; // 중복 체크용
        
        // 1. rm_info에서 사용자 목록 가져오기 (있는 경우)
        if (typeof rm_info !== 'undefined' && rm_info && rm_info.rm_users && Array.isArray(rm_info.rm_users)) {
            rm_info.rm_users.forEach(function(user) {
                if (user && user.user_id && userIdsInList.indexOf(user.user_id) === -1) {
                    firstUsers.push({
                        user_id: user.user_id,
                        user_nick: user.user_nick || ""
                    });
                    userIdsInList.push(user.user_id);
                }
            });
        }
        
        // 2. 현재 사용자 추가 (없는 경우)
        if (userIdsInList.indexOf(g_user_id) === -1) {
            firstUsers.push({
                user_id: g_user_id,
                user_nick: ""
            });
            userIdsInList.push(g_user_id);
        }
        
        // 3. 상대방 추가 (없는 경우 - 가장 중요! MongoDB에 없어도 클라이언트에서 확실히 포함)
        if (userIdsInList.indexOf(targetUserId) === -1) {
            firstUsers.push({
                user_id: targetUserId,
                user_nick: ""
            });
            userIdsInList.push(targetUserId);
        }
        
        // 메시지 전송
        firstMessage.backUrl = window.backUrl;
        socket.emit("message_insert", firstMessage, firstUsers);
    };

    // getFormatDate 함수 정의 (iOS/Android 공통 사용)
    function getFormatDate(date, format) {
        if (date == undefined) {
            return "";
        }
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();

        if (format == 'kor') {
            return year + "년 " + month + "월 " + day + "일";
        } else if (format == 'ymd') {
            return year + "-" + (month < 10 ? "0" + month : month) + "-" + (day < 10 ? "0" + day : day);
        } else if (format == 'hh:mm') {
            return (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes);
        }
        return "";
    }

    // 시:분을 오전/오후 형식으로 반환
    function getAmPmTime(date) {
        if (!date) return "";
        var d = new Date(date);
        var hours = d.getHours();
        var minutes = d.getMinutes();
        var period = hours < 12 ? "오전" : "오후";
        var displayHour = hours % 12 || 12;
        var mm = minutes < 10 ? "0" + minutes : minutes;
        return period + " " + displayHour + ":" + mm;
    }

    // 모든 환경에서 user_id 누락 여부를 확인하는 공통 함수 -------------------------------------------
    function ensureValidUserId() {
        var currentUserId = ($("#user_id").val() || "").trim();
        if (currentUserId === "") {
            if (!userIdAlertShown) {
                userIdAlertShown = true;
                if (typeof commonAlert === "function") {
                    commonAlert("로그인 정보가 없습니다. 다시 로그인해주세요.", function() {
                        location.href = '/member/login';
                    });
                } else {
                    alert("로그인 정보가 없습니다. 다시 로그인해주세요.");
                    location.href = '/member/login';
                }
            }
            return "";
        }
        return currentUserId;
    }

    // 공통 메시지 출력 처리 함수 (안드로이드/웹 공통 사용) -------------------------------------------
    var user_type = $("[name='user_type']").val();

    function showMessage(info, is_prepend) { // info는 showMessage(info, ...) 함수의 파라미터로, 서버(MongoDB)에서 온 개별 메시지 객체
        var mg_idx = info.mg_idx || 'unknown';

        // ✅ 중복 메시지 체크: DOM에 이미 있으면 건너뛰기 (단순하고 확실한 방법)
        if ($('#chat_list #mg_idx_' + mg_idx).length > 0) {
            return;
        }

        //유형에 따른 처리
        if (info.mg_type == "timeline") {
            var content = "<p class='chat-divider' id='mg_idx_" + info.mg_idx + "'>";
            content += "         " + getFormatDate(new Date(info.mg_dt), 'kor');
            content += "</p>";
        } else {
            var your_text_yn = (info.user_id == g_user_id ? "Y" : "N"); // 내가 보낸 메시지인지 여부 g_user_id = ""; 현재 로그인한 사용자의 ID를 저장 ,info.user_id — 메시지를 보낸 사람 ID
            var item = info.user_id + "^" + getFormatDate(new Date(info.mg_dt), 'ymd') + "^" + getFormatDate(new Date(info.mg_dt), 'hh:mm');

            if (info.mg_type == "text") {
                if (your_text_yn == "Y") {
                    var readcount = 0;
                    for (var icnt = 0; icnt < info.mg_reads.length; icnt++) {
                        if (info.mg_reads[icnt].read_yn == "N") {
                            readcount++;
                        }
                    }
                    var content = "<div class='msg-item chat-bubble' id='mg_idx_" + info.mg_idx + "' data-info='" + item + "'>";
                    content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />");
                    if (readcount > 0) {
                        content += "    <div class='readcount'>" + readcount + "</div>";
                    } else {
                        content += "    <div class='readcount'></div>";
                    }
                    content += "    </div>";
                    content += "    <div class='meta'>";
                    content += "        <p class='date-time'>" + getAmPmTime(info.mg_dt) + "</p>";
                    content += "    </div>";
                    content += "</div>";
                } else {
                    var content = "<div class='msg-item chat-bubble diff' data-info='" + item + "' >";
                    content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />") + "</div>";
                    content += "    <div class='meta'>";
                    content += "        <p class='date-time'>" + getAmPmTime(info.mg_dt) + "</p>";
                    content += "    </div>";
                    content += "</div>";
                }
            } else if (info.mg_type == "img") {
                console.log("[CHAT_RENDER] 이미지 메시지 렌더링:", { mg_img: info.mg_img });
                var imgUrls = [];
                try {
                    // JSON 배열이면 파싱, 아니면 단일 URL 처리 (하위호환성)
                    var parsed = JSON.parse(info.mg_img || "[]");
                    imgUrls = Array.isArray(parsed) ? parsed : [info.mg_img];
                } catch(e) {
                    imgUrls = info.mg_img ? [info.mg_img] : [];
                }

                var imgUrlsJson = JSON.stringify(imgUrls).replace(/'/g, "&#39;");

                var bubbleClass = (your_text_yn == "Y") ? "chat-bubble" : "chat-bubble diff";
                var content = "<div class='msg-item " + bubbleClass + " chat-bubble-img' id='mg_idx_" + info.mg_idx + "' data-info='" + item + "'>";
                content += "    <div class='bubble-content chat-img-wrap' data-images='" + imgUrlsJson + "'>";

                for (var i = 0; i < imgUrls.length; i++) {
                    // URL과 index에 특수문자 이스케이프 (file 분기와 일관성)
                    var escapedUrl = imgUrls[i].replace(/'/g, "&#39;");
                    var escapedIndex = String(i).replace(/'/g, "&#39;");
                    content += "        <img src='" + escapedUrl + "' class='chat-img' data-full='" + escapedUrl + "' data-index='" + escapedIndex + "' alt='첨부 이미지'>";
                }

                if (your_text_yn == "Y") {
                    var readcount = 0;
                    for (var icnt = 0; icnt < info.mg_reads.length; icnt++) {
                        if (info.mg_reads[icnt].read_yn == "N") {
                            readcount++;
                        }
                    }
                    content += "    <div class='readcount'>" + (readcount > 0 ? readcount : "") + "</div>";
                }

                content += "    </div>";
                content += "    <div class='meta'><p class='date-time'>" + getAmPmTime(info.mg_dt) + "</p></div>";
                content += "</div>";
            } else if (info.mg_type == "file") {
                console.log("[CHAT_RENDER] 파일 메시지 렌더링:", { mg_file: info.mg_file, mg_text: info.mg_text });
                var fileUrls = [];
                var fileNames = [];

                try {
                    // JSON 배열이면 파싱, 아니면 단일 파일 처리 (하위호환성)
                    var parsedUrls = JSON.parse(info.mg_file || "[]");
                    fileUrls = Array.isArray(parsedUrls) ? parsedUrls : [info.mg_file];
                } catch(e) {
                    fileUrls = info.mg_file ? [info.mg_file] : [];
                }

                try {
                    var parsedNames = JSON.parse(info.mg_text || "[]");
                    fileNames = Array.isArray(parsedNames) ? parsedNames : (info.mg_text ? [info.mg_text] : []);
                } catch(e) {
                    fileNames = info.mg_text ? [info.mg_text] : [];
                }

                // 파일 개수만큼 파일명 배열 채우기
                while (fileNames.length < fileUrls.length) {
                    fileNames.push("파일");
                }

                var bubbleClass = (your_text_yn == "Y") ? "chat-bubble" : "chat-bubble diff";
                var content = "<div class='msg-item " + bubbleClass + " chat-bubble-file' id='mg_idx_" + info.mg_idx + "' data-info='" + item + "'>";
                content += "    <div class='bubble-content chat-files'>";

                for (var i = 0; i < fileUrls.length; i++) {
                    var fileName = fileNames[i] || "파일";
                    // data-url과 data-filename의 특수문자 이스케이프 (img 분기와 일관성)
                    var escapedUrl = fileUrls[i].replace(/'/g, "&#39;");
                    var escapedFilename = fileName.replace(/'/g, "&#39;");
                    content += "        <div class='chat-file' data-url='" + escapedUrl + "' data-filename='" + escapedFilename + "'>";
                    content += "            <span class='chat-file-icon'></span>";
                    content += "            <span class='chat-file-name'>" + fileName + "</span>";
                    content += "            <button type='button' class='chat-file-open-btn' data-url='" + escapedUrl + "' data-filename='" + escapedFilename + "'>열기</button>";
                    content += "        </div>";
                }

                if (your_text_yn == "Y") {
                    var readcount = 0;
                    for (var icnt = 0; icnt < info.mg_reads.length; icnt++) {
                        if (info.mg_reads[icnt].read_yn == "N") {
                            readcount++;
                        }
                    }
                    content += "    <div class='readcount'>" + (readcount > 0 ? readcount : "") + "</div>";
                }

                content += "    </div>";
                content += "    <div class='meta'><p class='date-time'>" + getAmPmTime(info.mg_dt) + "</p></div>";
                content += "</div>";
            } else if (info.mg_type == "system_exit") {
                // 시스템 메시지: 채팅방 나가기 알림
                var content = "<p class='msg-item chat-notify' data-info='" + item + "'>";
                content += info.mg_text;
                content += "</p>";
            } else if (info.mg_type == "propose") {
                // 채팅방 내 제안하기/지원하기 완료 안내: 일반 텍스트 메시지와 동일한 말풍선으로 표시
                if (your_text_yn == "Y") {
                    var readcount = 0;
                    for (var icnt = 0; icnt < info.mg_reads.length; icnt++) {
                        if (info.mg_reads[icnt].read_yn == "N") {
                            readcount++;
                        }
                    }
                    var content = "<div class='msg-item chat-bubble propose-bubble' id='mg_idx_" + info.mg_idx + "' data-info='" + item + "' data-mq_type='" + (info.mg_mq_type || "") + "' data-rc_idx='" + (info.mg_rc_idx || "") + "' data-ru_idx='" + (info.mg_ru_idx || "") + "'>";
                    content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />");
                    content += "        <button type='button' class='btn-propose-view'>열어보기</button>";
                    if (readcount > 0) {
                        content += "    <div class='readcount'>" + readcount + "</div>";
                    } else {
                        content += "    <div class='readcount'></div>";
                    }
                    content += "    </div>";
                    content += "    <div class='meta'>";
                    content += "        <p class='date-time'>" + getAmPmTime(info.mg_dt) + "</p>";
                    content += "    </div>";
                    content += "</div>";
                } else {
                    var content = "<div class='msg-item chat-bubble diff propose-bubble' id='mg_idx_" + info.mg_idx + "' data-info='" + item + "' data-mq_type='" + (info.mg_mq_type || "") + "' data-rc_idx='" + (info.mg_rc_idx || "") + "' data-ru_idx='" + (info.mg_ru_idx || "") + "'>";
                    content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />");
                    content += "        <button type='button' class='btn-propose-view'>열어보기</button>";
                    content += "    </div>";
                    content += "    <div class='meta'>";
                    content += "        <p class='date-time'>" + getAmPmTime(info.mg_dt) + "</p>";
                    content += "    </div>";
                    content += "</div>";
                }
            } else if (info.mg_type == "면접일정확인") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                $("#top_dt1").html(dt);
                if (user_type == "COMP") {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 면접일 확정 요청 하였습니다.";
                    content += "</p>";
                } else {
                    var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
                    content += "    <div class='content'>" + dt + "로 면접일 요청이 왔습니다.수락하시겠습니까?(수락시 연락처 공개)</div>";
                    content += "    <div class='alert-footer'>";
                    if (info.mg_imoti == null) {
                        content += "        <button type='button' class='btn-secondary btn-round bntInterviewCancel' data-mg_idx='" + info.mg_idx + "'>면접취소</button>";
                        content += "        <button type='button' class='btn-primary btn-round bntInterviewChange' data-mg_idx='" + info.mg_idx + "'>변경신청</button>";
                        content += "        <button type='button' class='btn-tertiary btn-round bntInterviewOK' data-mg_idx='" + info.mg_idx + "'>면접수락</button>";
                    } else {
                        content += "        <button type='button' class='btn-secondary btn-round bntInterviewCancel' data-mg_idx='" + info.mg_idx + "' disabled>면접취소</button>";
                        content += "        <button type='button' class='btn-primary btn-round bntInterviewChange' data-mg_idx='" + info.mg_idx + "' disabled>변경신청</button>";
                        content += "        <button type='button' class='btn-tertiary btn-round bntInterviewOK' data-mg_idx='" + info.mg_idx + "' disabled>면접수락</button>";
                    }
                    content += "    </div>";
                    content += "</div>";
                }
            } else if (info.mg_type == "개인면접수락") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 면접 수락 하였습니다.";
                    content += "</p>";
                } else {
                    var recruit_name = $("[name=recruit_name]").val();
                    var recruit_phone = $("[name=recruit_phone]").val();
                    var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
                    content += "    <div class='content'>" + dt + "로 면접이 확정되었습니다.<br> 연락처 공유드립니다.</div>";
                    content += "    <div class='alert-footer-bar'>";
                    content += "         <p class='call'>" + recruit_name + " : <a href='tel:" + recruit_phone + "'>" + recruit_phone + "</a></p>";
                    content += "    </div>";
                    content += "</div>";
                }
            } else if (info.mg_type == "개인면접변경요청") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
                    content += "    <div class='content'>면접일 변경 요청이 왔습니다<br> 수락하시겠습니까?</div>";
                    content += "    <div class='alert-footer'>";

                    if (info.mg_imoti == null) {
                        content += "        <button type='button' class='btn-secondary btn-round bntInterviewCompCancel' data-mg_idx='" + info.mg_idx + "'>면접취소</button>";
                        content += "        <button type='button' class='btn-primary btn-round bntInterviewChangeOK' data-mg_idx='" + info.mg_idx + "'>변경수락</button>";
                    } else {
                        content += "        <button type='button' class='btn-secondary btn-round bntInterviewCompCancel' data-mg_idx='" + info.mg_idx + "' disabled>면접취소</button>";
                        content += "        <button type='button' class='btn-primary btn-round bntInterviewChangeOK' data-mg_idx='" + info.mg_idx + "' disabled>변경수락</button>";
                    }
                    content += "    </div>";
                    content += "</div>";
                } else {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 면접일 변경요청 하였습니다.";
                    content += "</p>";
                }
            } else if (info.mg_type == "개인면접변경수락") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 면접일 변경 수락 하였습니다.";
                    content += "</p>";
                } else {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 면접일 변경 수락 하였습니다.";
                    content += "</p>";
                }
            } else if (info.mg_type == "개인면접취소요청") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
                    content += "    <div class='content'>" + dt + "면접 취소 요청이 왔습니다.<br> 수락하시겠습니까?</div>";
                    content += "    <div class='alert-footer'>";
                    if (info.mg_imoti == null) {
                        content += "        <button type='button' class='btn-tertiary btn-round btnCancelOK' data-mg_idx='" + info.mg_idx + "'>확인</button>";
                    } else {
                        content += "        <button type='button' class='btn-tertiary btn-round btnCancelOK' data-mg_idx='" + info.mg_idx + "' disabled>확인</button>";
                    }
                    content += "    </div>";
                    content += "</div>";
                } else {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 면접 취소 신청 하였습니다.";
                    content += "</p>";
                }
            } else if (info.mg_type == "기업면접취소요청") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 면접 취소 신청 하였습니다.";
                    content += "</p>";
                } else {
                    var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
                    content += "    <div class='content'>" + dt + "면접 취소 요청이 왔습니다.<br> 수락하시겠습니까?</div>";
                    content += "    <div class='alert-footer'>";
                    if (info.mg_imoti == null) {
                        content += "        <button type='button' class='btn-tertiary btn-round btnCancelOK' data-mg_idx='" + info.mg_idx + "'>확인</button>";
                    } else {
                        content += "        <button type='button' class='btn-tertiary btn-round btnCancelOK' data-mg_idx='" + info.mg_idx + "' disabled>확인</button>";
                    }
                    content += "    </div>";
                    content += "</div>";
                }
            } else if (info.mg_type == "면접취소") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 면접이 취소 되었습니다.";
                    content += "</p>";
                } else {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 면접이 취소 되었습니다.";
                    content += "</p>";
                }
            } else if (info.mg_type == "채용일정확인") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                $("#top_dt2").html(dt);
                if (user_type == "COMP") {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 채용 확정 요청 하였습니다.";
                    content += "</p>";
                } else {
                    var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
                    content += "    <div class='content'>" + dt + "로 근로예정일 요청이 왔습니다<br> 수락하시겠습니까?</div>";
                    content += "    <div class='alert-footer'>";
                    if (info.mg_imoti == null) {
                        content += "        <button type='button' class='btn-secondary btn-round bntRecruitCancel' data-mg_idx='" + info.mg_idx + "'>요청취소</button>";
                        content += "        <button type='button' class='btn-primary btn-round bntRecruitChange' data-mg_idx='" + info.mg_idx + "'>변경신청</button>";
                        content += "        <button type='button' class='btn-tertiary btn-round bntRecruitOK' data-mg_idx='" + info.mg_idx + "'>요청수락</button>";
                    } else {
                        content += "        <button type='button' class='btn-secondary btn-round bntRecruitCancel' data-mg_idx='" + info.mg_idx + "' disabled>요청취소</button>";
                        content += "        <button type='button' class='btn-primary btn-round bntRecruitChange' data-mg_idx='" + info.mg_idx + "' disabled>변경신청</button>";
                        content += "        <button type='button' class='btn-tertiary btn-round bntRecruitOK' data-mg_idx='" + info.mg_idx + "' disabled>요청수락</button>";
                    }
                    content += "    </div>";
                    content += "</div>";
                }
            } else if (info.mg_type == "개인채용수락") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 채용 수락 하였습니다.";
                    content += "</p>";
                } else {
                    var recruit_name = $("[name=recruit_name]").val();
                    var recruit_phone = $("[name=recruit_phone]").val();
                    var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
                    content += "    <div class='content'>" + dt + "로 채용이 확정되었습니다.<br> 연락처 공유드립니다.</div>";
                    content += "    <div class='alert-footer-bar'>";
                    content += "         <p class='call'>" + recruit_name + " : <a href='tel:" + recruit_phone + "'>" + recruit_phone + "</a></p>";
                    content += "    </div>";
                    content += "</div>";
                }
            } else if (info.mg_type == "개인채용변경요청") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
                    content += "    <div class='content'>근로예정일 변경 요청이 왔습니다<br> 수락하시겠습니까?</div>";
                    content += "    <div class='alert-footer'>";
                    if (info.mg_imoti == null) {
                        content += "        <button type='button' class='btn-secondary btn-round bntRecruitCompCancel' data-mg_idx='" + info.mg_idx + "'>채용취소</button>";
                        content += "        <button type='button' class='btn-primary btn-round bntRecruitChangeOK' data-mg_idx='" + info.mg_idx + "'>변경수락</button>";
                    } else {
                        content += "        <button type='button' class='btn-secondary btn-round bntRecruitCompCancel' data-mg_idx='" + info.mg_idx + "' disabled>채용취소</button>";
                        content += "        <button type='button' class='btn-primary btn-round bntRecruitChangeOK' data-mg_idx='" + info.mg_idx + "' disabled>변경수락</button>";
                    }
                    content += "    </div>";
                    content += "</div>";
                } else {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 근로예정일 변경 요청 하였습니다.";
                    content += "</p>";
                }
            } else if (info.mg_type == "개인채용변경수락") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 근로예정일 변경 수락 하였습니다.";
                    content += "</p>";
                } else {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 근로예정일 변경 수락 하였습니다.";
                    content += "</p>";
                }
            } else if (info.mg_type == "개인채용취소요청") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
                    content += "    <div class='content'>" + dt + "채용 취소 요청이 왔습니다.<br> 수락하시겠습니까?</div>";
                    content += "    <div class='alert-footer'>";
                    if (info.mg_imoti == null) {
                        content += "        <button type='button' class='btn-tertiary btn-round btnRecruitCancelOK' data-mg_idx='" + info.mg_idx + "'>확인</button>";
                    } else {
                        content += "        <button type='button' class='btn-tertiary btn-round btnRecruitCancelOK' data-mg_idx='" + info.mg_idx + "' disabled>확인</button>";
                    }
                    content += "    </div>";
                    content += "</div>";
                } else {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 채용 취소 신청 하였습니다.";
                    content += "</p>";
                }
            } else if (info.mg_type == "기업채용취소요청") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 채용 취소 신청 하였습니다.";
                    content += "</p>";
                } else {
                    var content = "<div class='msg-item chat-alert' data-info='" + item + "'>";
                    content += "    <div class='content'>" + dt + "채용 취소 요청이 왔습니다.<br> 수락하시겠습니까?</div>";
                    content += "    <div class='alert-footer'>";
                    if (info.mg_imoti == null) {
                        content += "        <button type='button' class='btn-tertiary btn-round btnRecruitCancelOK' data-mg_idx='" + info.mg_idx + "'>확인</button>";
                    } else {
                        content += "        <button type='button' class='btn-tertiary btn-round btnRecruitCancelOK' data-mg_idx='" + info.mg_idx + "' disabled>확인</button>";
                    }
                    content += "    </div>";
                    content += "</div>";
                }
            } else if (info.mg_type == "채용취소") {
                var dt = getFormatDate(new Date(info.mg_req_dt), "kor");
                if (user_type == "COMP") {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 채용을 취소하였습니다.";
                    content += "</p>";
                } else {
                    var content = "<p class='msg-item chat-notify' data-info='" + item + "' >";
                    content += dt + " 채용을 취소하였습니다.";
                    content += "</p>";
                }
            }
        }

        // content가 정의되지 않은 경우 리턴
        if (typeof content === 'undefined') {
            return;
        }

        if (is_prepend == true) {
            $("#chat_list").prepend(content);
        } else {
            $("#chat_list").append(content);
        }
    }

    // 채팅 화면 스크롤을 맨 아래로 이동하는 공통 함수 (깜빡임 방지)
    function scrollBottom() {
        // ✅ requestAnimationFrame을 사용하여 다음 프레임에 스크롤 실행 (깜빡임 방지)
        // ✅ 실제 스크롤 컨테이너는 .page-chat-view이다 (CSS에서 .chat-view-body를 overflow: visible로 오버라이드함)
        requestAnimationFrame(function() {
            var $pageChatView = document.querySelector('.page-chat-view') || document.querySelector('.page.page-chat-view');
            if ($pageChatView) {
                $pageChatView.scrollTop = $pageChatView.scrollHeight;
            }
        });
    }

    if (rm_idx == null || rm_idx == "") {
        commonAlert("잘못된 접근입니다.", function() {
            location.href = "/chat/chat_list";
        });
        // 빈 rm_idx로 진입한 경우 이후 초기화 코드 실행하지 않음
        throw new Error("Invalid rm_idx");
    }

    // textarea 자동 높이 조정 함수
    function adjustTextareaHeight() {
        var textarea = document.getElementById('chatTxt');
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px'; // 최대 높이 120px 제한
    }

    // textarea 입력 이벤트: 실시간 높이 조정
    document.getElementById('chatTxt').addEventListener('input', function() {
        adjustTextareaHeight();
    });

    // textarea keydown: Shift+Enter로 메시지 전송
    $("#chatTxt").keydown(function(e) {
        if (e.keyCode === 13) { // Enter 키
            if (e.shiftKey) {
                // Shift+Enter: 메시지 전송
                e.preventDefault();
                send_message();
            }
            // 일반 Enter: 기본 동작 (줄바꿈)
        }
    });

    // ✅ 공통 변수: pagging_yn (페이지네이션 플래그)
    var pagging_yn = false;

    // ✅ 공통 핸들러: message_list_return (안드로이드/iOS 공통)
    // 중복 등록 방지: 기존 핸들러 제거 후 등록
    // 기존에 등록된 "message_list_return" 이벤트 리스너를 모두 제거하여 중복 실행을 방지한다.
    socket.off("message_list_return");

    /**
     * socket.on("message_list_return", function(ret) { ... })
     * 
     * [함수 설명]
     * 이 함수는 서버로부터 'message_list_return' 이벤트가 도착했을 때 호출된다.
     * 서버에서는 현재 채팅방에 저장된 메시지들의 리스트를 응답 데이터(ret.data)로 보내주며,
     * 클라이언트는 이 응답을 받아 채팅 화면에 메시지들을 표시한다.
     * 
     * [주요 동작 과정]
     * 1. message_list(메시지 목록) 배열을 순회하여, 아직 DOM(화면)에 없는 메시지(mg_idx 기준)만을 골라낸다.
     * 2. 불필요한 중복 추가를 막기 위해, 이미 채팅 화면에 추가된 메시지는 건너뛴다.
     * 3. 새로 추가해야 할 메시지 목록을 한 번에 준비(messagesToAdd 배열).
     * 4. requestAnimationFrame을 사용하여 다음 브라우저 렌더링 타이밍에 일괄적으로 DOM 업데이트(append/prepend)한다.
     *    (이를 통해 깜빡임이나 화면 밀림을 최소화함)
     * 5. 메시지들이 추가된 후, 서버에서 받은 읽음 카운트 정보(pendingReadCounts)가 남아있으면,
     *    각 메시지의 읽음 수를 화면에도 반영(updateReadCount).
     * 6. 메시지 추가 후, pagination이 아닐 경우(scrollBottom) 채팅 화면을 자동으로 맨 아래로 스크롤한다.
     * 7. 마지막에 pagging_yn 값을 false로 리셋한다.
     *
     * [사용 맥락]
     * - 채팅방 최초 진입 및 스크롤/페이지네이션으로 메시지 목록을 불러올 때,
     *   서버에서 보내주는 전체 메시지 리스트를 받아 화면에 보여줄 때 사용.
     */
    socket.on("message_list_return", function(ret) {
        // 🔥 DEBUG: ret 구조 확인
        console.log('[message_list_return] code=' + ret.code + ', data_length=' + (ret.data ? ret.data.length : 0));

        //메시지 리스트 가져오기
        var message_list = ret.data;
        
        // 제안하기/지원하기 완료 후 자동 메시지 전송 체크
        if (window.autoSendFirstMessage && window.autoSendFirstMessage.enabled) {
            var autoSendInfo = window.autoSendFirstMessage;
            
            // 메시지 리스트 확인
            var hasMessages = false;
            if (Array.isArray(message_list) && message_list.length > 0) {
                for (var i = 0; i < message_list.length; i++) {
                    if (message_list[i].rm_messages && Array.isArray(message_list[i].rm_messages) && message_list[i].rm_messages.length > 0) {
                        hasMessages = true;
                        break;
                    }
                }
            }
            
            // 🔥 메시지가 없고 rm_info를 받았으면 자동으로 첫 메시지 전송
            // room_info_return에서 rm_info를 받은 후 message_list를 요청했으므로,
            // message_list_return에서 메시지가 없으면 바로 전송
            if (!hasMessages && window.autoSendFirstMessage && window.autoSendFirstMessage.rm_info_received) {
                window.autoSendFirstMessage.messageListChecked = true;
                
                var targetUserId = autoSendInfo.targetUserId;
                var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();
                
                // 상대방 ID 찾기 (우선순위: 1. 이미 저장된 targetUserId, 2. hidden input의 opponent_user_id, 3. rm_info에서 찾기)
                if (!targetUserId) {
                    // hidden input에서 상대방 ID 가져오기 (서버에서 미리 찾아서 전달한 값)
                    var opponentUserIdFromInput = $("#opponent_user_id").val();
                    if (opponentUserIdFromInput && opponentUserIdFromInput.trim() !== "") {
                        targetUserId = opponentUserIdFromInput.trim();
                    }
                }
                
                if (!targetUserId && typeof rm_info !== 'undefined' && rm_info && rm_info.rm_users && Array.isArray(rm_info.rm_users)) {
                    for (var i = 0; i < rm_info.rm_users.length; i++) {
                        if (rm_info.rm_users[i].user_id && rm_info.rm_users[i].user_id != g_user_id) {
                            targetUserId = rm_info.rm_users[i].user_id;
                            break;
                        }
                    }
                }
                
                if (targetUserId && window.sendFirstMessageAfterProposal) {
                    window.sendFirstMessageAfterProposal(targetUserId, autoSendInfo.rm_idx);
                    window.autoSendFirstMessage = null;
                    
                } else {
                    // 상대방 ID를 찾지 못한 경우 재시도
                    setTimeout(function() {
                        if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) {
                            return;
                        }
                        
                        // 재시도 시 우선순위: 1. room_info_return에서 저장된 targetUserId, 2. hidden input, 3. rm_info
                        var retryTargetUserId = window.autoSendFirstMessage.targetUserId;
                        
                        if (!retryTargetUserId) {
                            var opponentUserIdFromInput = $("#opponent_user_id").val();
                            if (opponentUserIdFromInput && opponentUserIdFromInput.trim() !== "") {
                                retryTargetUserId = opponentUserIdFromInput.trim();
                            }
                        }
                        
                        if (!retryTargetUserId && typeof rm_info !== 'undefined' && rm_info && rm_info.rm_users && Array.isArray(rm_info.rm_users)) {
                            var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();
                            for (var i = 0; i < rm_info.rm_users.length; i++) {
                                if (rm_info.rm_users[i].user_id && rm_info.rm_users[i].user_id != g_user_id) {
                                    retryTargetUserId = rm_info.rm_users[i].user_id;
                                    break;
                                }
                            }
                        }
                        
                        if (retryTargetUserId && window.sendFirstMessageAfterProposal) {
                            window.sendFirstMessageAfterProposal(retryTargetUserId, window.autoSendFirstMessage.rm_idx);
                            window.autoSendFirstMessage = null;
                        } else {
                            // 최종 실패 시 플래그 해제
                            window.autoSendFirstMessage = null;
                        }
                    }, 2000);
                }
            } else {
                window.autoSendFirstMessage = null;
            }
        }
        
        // ✅ 여러 메시지를 배치 처리 (DOM 업데이트 최소화, 중복 방지)
        var messagesToAdd = [];

        for (var icnt = 0; icnt < message_list.length; icnt++) {
            // ✅ 이 메시지 그룹의 rm_idx 확인 (다른 방의 메시지면 무시)
            var itemRmIdx = message_list[icnt].rm_idx;
            if (itemRmIdx && String(itemRmIdx) !== String(requested_rm_idx)) {
                console.log('[message_list_return] 다른 방의 메시지 그룹 무시. 요청: ' + requested_rm_idx + ', 수신: ' + itemRmIdx);
                continue; // 다른 방 메시지는 건너뜀
            }

            var messageData = message_list[icnt].rm_messages;
            if (!messageData) continue;

            var mg_idx = messageData.mg_idx;

            // ✅ DOM에 이미 있는지 확인 (중복 방지)
            if ($('#chat_list #mg_idx_' + mg_idx).length > 0) {
                continue;
            }

            messagesToAdd.push(messageData);
        }
        
        // ✅ requestAnimationFrame으로 DOM 업데이트를 다음 프레임으로 지연 (깜빡임 방지, 중복 방지)
        requestAnimationFrame(function() {
            // 배치로 메시지 추가 (DOM 체크는 showMessage 내부에서도 수행)
            for (var i = 0; i < messagesToAdd.length; i++) {
                showMessage(messagesToAdd[i], true);
            }
            
            // 🔥 메시지 로드 후 저장된 읽음 카운트 정보 업데이트
            if (Object.keys(pendingReadCounts).length > 0) {
                for (var mg_idx in pendingReadCounts) {
                    if (pendingReadCounts.hasOwnProperty(mg_idx)) {
                        var count = pendingReadCounts[mg_idx];
                        updateReadCount(mg_idx, count);
                    }
                }
                // 업데이트 완료 후 초기화
                pendingReadCounts = {};
            }
            
            if (pagging_yn == false) {
                scrollBottom();
            }
            pagging_yn = false;
        });
    });

    // ✅ 공통 핸들러: message_insert_return (안드로이드/iOS 공통)
    // 중복 등록 방지: 기존 핸들러 제거 후 등록
    socket.off("message_insert_return");
    // 사용자가 채팅 입력창에 메시지를 작성하고 "전송" 버튼(혹은 엔터 키 등)으로 메시지 전송을 시도할 때,
    // 서버에서 메시지 전송(입력) 결과가 돌아오면 실행됨
    // ret: 서버에서 전달된 메시지 전송 결과(response event object)
    // 어떤 구조의 데이터야? (message_insert_return의 ret 예시 구조)
    /*
        ret = {
            code: "00", // 성공/실패 코드
            msg: "성공 메시지 또는 실패 원인",
            data: [
                {
                    mg_idx: "12345",      // 메시지 고유 번호 (primary key)
                    rm_idx: "4321",       // 채팅방 번호
                    user_id: "sample",    // 보낸 유저 ID
                    user_nm: "닉네임",    // 보낸 유저 닉네임/이름
                    msg: "내용",          // 메시지 내용
                    msg_type: "text",     // 메시지 타입 (text, file 등)
                    mg_type: "chat",      // 메시지 구분 (chat:일반, timeline:타임라인/알림)
                    send_dt: "2024-06-01 15:43:22", // 전송일시
                    read_cnt: 1,          // 읽음 유저 수
                    // 기타 메시지에 필요한 부가정보 (ex: 파일경로, 이미지, ... 이하 생략)
                },
                // ... 여러 메시지가 배열로 올 수 있음(특히 타임라인 메시지나 그룹채팅 등)
            ]
        }
    */
    socket.on("message_insert_return", function(ret) {
        if (ret.code != "00") {
            // 전송 실패 시 알림 및 버튼 활성화
            if (typeof APP_NOWAPK !== 'undefined') {
                alert('메시지 전송에 실패했습니다: ' + ret.msg);
            } else if (typeof commonAlert === "function") {
                commonAlert('메시지 전송에 실패했습니다: ' + ret.msg, function() {});
            } else {
                alert('메시지 전송에 실패했습니다: ' + ret.msg);
            }

            // 전송 버튼 활성화
            var $sendBtn = $("#chatTxt").closest('form').find('button[type="submit"], .btn-send, #sendBtn');
            if ($sendBtn.length > 0) {
                $sendBtn.prop('disabled', false);
            }
            return;
        }

        // 🔥 실시간 채팅: 보내는 사람과 받는 사람 모두 처리
        var message_list = ret.data || [];
        var processedInThisCall = {}; // 이번 호출에서 처리한 메시지 추적

        // ✅ 여러 메시지를 배치 처리 (DOM 업데이트 최소화)
        var messagesToAdd = [];
        var timelineMessages = [];

        for (var icnt = 0; icnt < message_list.length; icnt++) {
            var received_message = message_list[icnt];
            var mg_idx = received_message.mg_idx;
            var is_my_message = (received_message.user_id == g_user_id);
            var is_timeline = (received_message.mg_type == "timeline");

            // ✅ 다른 방의 메시지 필터링 (실시간 메시지 오염 방지)
            if (received_message.rm_idx && String(received_message.rm_idx) !== String(requested_rm_idx)) {
                console.warn('[message_insert_return] 다른 방 메시지 무시. 요청방=' + requested_rm_idx + ', 수신방=' + received_message.rm_idx);
                continue;
            }

            // 이번 호출에서 이미 처리한 메시지 건너뛰기
            if (processedInThisCall[mg_idx]) {
                continue;
            }
            processedInThisCall[mg_idx] = true;

            // ✅ 타임라인 메시지는 별도 처리
            if (is_timeline) {
                timelineMessages.push(received_message);
                continue;
            }

            // 일반 메시지 추가
            messagesToAdd.push(received_message);
        }
        
        // ✅ requestAnimationFrame으로 DOM 업데이트를 다음 프레임으로 지연 (깜빡임 방지)
        requestAnimationFrame(function() {
            // 타임라인 메시지 먼저 추가
            for (var i = 0; i < timelineMessages.length; i++) {
                showMessage(timelineMessages[i], false);
            }
            
            // 일반 메시지 추가
            for (var i = 0; i < messagesToAdd.length; i++) {
                var msg = messagesToAdd[i];
                var is_my_message = (msg.user_id == g_user_id);

                showMessage(msg, false);

                // 지원하기(RECRUIT) 완료 안내 메시지에 이력서 데이터가 실려있으면
                // 기업회원 화면의 이력서 카드를 새로고침 없이 즉시 갱신 (MEM 본인 화면에는 #chatResumeList가 없어 자동 무시됨)
                if (msg.mg_type === "propose" && msg.mg_mq_type === "RECRUIT" && msg.mg_resume_data) {
                    try {
                        var resumeData = JSON.parse(msg.mg_resume_data);
                        if (typeof window.renderChatResumeCard === 'function') {
                            window.renderChatResumeCard(resumeData);
                            $('#chatResumeList').attr('data-visible', 'true');
                        }
                    } catch (e) {
                        console.error('[message_insert_return] mg_resume_data 파싱 실패:', e);
                    }
                }

                // 전화하기 버튼 실시간 반영 (개인회원이 일반 메시지를 보냈을 때)
                var individualUserId = $("#individual_user_id").val();
                if (individualUserId && msg && !msg.chat_type && msg.mg_type === "text" && String(msg.user_id) === String(individualUserId)) {
                    $(".btn-call").attr("data-show-call", "true");
                }

                // 상대방이 보낸 메시지는 읽음 처리
                // 단, 현재 열려있는 채팅방의 메시지이고 채팅 페이지가 보일 때만 읽음 처리
                // message_insert_return은 해당 채팅방의 모든 클라이언트에게 전송되므로,
                // 현재 열려있는 채팅방(rm_idx)이 있으면 해당 채팅방의 메시지로 간주
                if (!is_my_message) {
                    var is_current_room = (rm_idx && rm_idx !== "" && rm_idx !== null);
                    var is_chat_page_visible = $("#chat_body").is(":visible") || $("#chat_view").is(":visible");
                    
                    if (is_current_room && is_chat_page_visible) {
                        var readParams = {
                            rm_idx: Number(rm_idx),
                            mg_idx: Number(msg.mg_idx),
                            user_id: g_user_id
                        };
                        // 상대방이 보낸 메시지에 대해 읽음 처리를 서버에 요청합니다.
                        // socket.emit("message_read_return", readParams)는 해당 메시지를 내가 읽었음을 서버에 알리기 위해 호출됩니다.
                        // 서버는 이를 바탕으로 읽음 카운트 등을 갱신한 뒤 클라이언트들에게 실시간 전파합니다.
                        socket.emit("message_read_return", readParams);
                        // 이 이벤트를 emit하면, 서버는 다음과 같은 순서로 응답합니다:
                        // 1. 서버는 해당 메시지(mg_idx)에 대해 사용자가 읽었음을 기록(데이터베이스 업데이트 등)합니다.
                        // 2. 그리고 관련 채팅방(rm_idx) 내의 다른 클라이언트(참여 사용자)에게 실시간으로 읽음 상태 업데이트 정보를 전송합니다.
                        //    - 이때 서버는 일반적으로 "message_read_count_return" 또는 유사한 이름의 이벤트를 emit하여
                        //      메시지의 읽음 카운트를 모든 클라이언트에게 브로드캐스트(broadcast)합니다.
                        // 3. 클라이언트(프론트)에서는 "message_read_count_return" 이벤트를 수신하면, 해당 메시지의 읽음 카운트 혹은 UI 뱃지 등을 업데이트합니다.
                        // 예시:
                        // socket.on("message_read_count_return", function(data) {
                        //     // data: { rm_idx, mg_idx, read_count, ... }
                        //     // 읽음 카운트 UI 업데이트 처리
                        // });
                    }
                }
            }
            
            // ✅ 스크롤은 모든 메시지 추가 후 한 번만 실행
            scrollBottom();
        });
    });

    // ===== 첨부 메뉴 토글 (공통: 안드로이드/iOS 양쪽 모두에서 동작) =====
    $(document).on("click", "#btnAttachToggle", function() {
        var opening = $(this).attr("data-state") === "closed";
        $(this).attr("data-state", opening ? "open" : "closed");
        $("#attachMenu").attr("data-state", opening ? "open" : "closed");
    });

    // 안심콜 버튼 클릭 (헤더, 첨부메뉴 통합)
    $(document).on("click", ".js-attach-call", function() {
        var $btn = $(this);

        // 중복 클릭 방지
        if ($btn.attr('data-loading') === 'true') return;

        var userType = $("input[name='user_type']").val();

        // 첨부메뉴 닫기
        var closeMenu = function() {
            $("#btnAttachToggle").attr("data-state", "closed");
            $("#attachMenu").attr("data-state", "closed");
        };

        if (userType === "COMP") {
            // 기업회원 -> 구직자: 050 안심번호로 연결
            var ruIdx = $("#room_ru_idx").val();
            if (!ruIdx) {
                commonAlert("현재 통화 가능한 상태가 아닙니다.", function() {});
                closeMenu();
                return;
            }

            $btn.attr('data-loading', 'true');
            comAjax("/resume_proc/get_call_number", { ru_idx: ruIdx }, function(ret) {
                $btn.attr('data-loading', 'false');
                if (ret.code == "00" && ret.data && ret.data.vn) {
                    window.location.href = "tel:" + ret.data.vn;
                } else {
                    commonAlert(ret.msg || "전화 연결에 실패했습니다.", function() {});
                }
            });
        } else {
            // 개인회원 -> 기업: 가상번호 미지원, 실번호로 연결 (현행 유지)
            var phoneNumber = $("#call_target_phone").val() || $("input[name='recruit_phone']").val();

            if (phoneNumber) {
                // 실제 전화번호로 연결
                window.location.href = "tel:" + phoneNumber;
            } else {
                alert("현재 통화 가능한 상태가 아닙니다.");
            }
        }

        closeMenu();
    });

    // ===== 사진/파일 첨부 기능 =====
    var CHAT_IMG_MAX_SIZE = 8 * 1024 * 1024;
    var CHAT_FILE_MAX_SIZE = 40 * 1024 * 1024;
    var CHAT_FILE_ALLOWED_EXT = ["hwp","xls","doc","xlsx","docx","pdf","jpg","jpeg","gif","png","txt","ppt","pptx","zip"];
    var CHAT_IMG_ALLOWED_EXT = ["jpg","jpeg","gif","png"];

    $(document).on("click", ".attach-item[data-attach='photo']", function() {
        $("#chatPhotoInput").val("").trigger("click");
    });
    $(document).on("click", ".attach-item[data-attach='file']", function() {
        $("#chatFileInput").val("").trigger("click");
    });
    $(document).on("change", "#chatPhotoInput", function() {
        handleAttachUploadMultiple(this.files, "img");
    });
    $(document).on("change", "#chatFileInput", function() {
        handleAttachUploadMultiple(this.files, "file");
    });

    // 여러 첨부파일 업로드 처리
    function handleAttachUploadMultiple(files, kind) {
        if (!files || files.length === 0) { return; }

        console.log("[CHAT_ATTACH] " + kind + " 타입으로 " + files.length + "개 파일 선택됨");

        // 이미지 선택 시 콘솔에 표시
        if (kind === "img") {
            console.log("[CHAT_ATTACH] 이미지 업로드 시작", files);
        }

        var fileList = [];
        for (var i = 0; i < files.length; i++) {
            fileList.push(files[i]);
        }

        // 메뉴 닫기
        $("#btnAttachToggle").attr("data-state", "closed");
        $("#attachMenu").attr("data-state", "closed");

        // 각 파일을 순차적으로 처리 (첫 파일일 때만 로딩바 표시)
        uploadFilesSequentially(fileList, kind, 0, true, []);
    }

    // 파일을 순차적으로 업로드 (모든 파일 완료 후 한 번에 전송)
    function uploadFilesSequentially(fileList, kind, index, isFirstFile, uploadedUrls) {
        if (index >= fileList.length) {
            console.log("[CHAT_ATTACH] 모든 파일 업로드 완료 (총 " + uploadedUrls.length + "개)");
            // 진행률 UI 숨기기 (완료 표시를 1.5초 동안 유지)
            setTimeout(function() {
                $("#uploadProgressContainer").fadeOut(300);
            }, 1500);
            // 업로드 성공한 파일이 있으면 한 번에 전송
            if (uploadedUrls && uploadedUrls.length > 0) {
                sendAttachMessage(kind, uploadedUrls);
            }
            return;
        }

        var file = fileList[index];
        var ext = (file.name.split(".").pop() || "").toLowerCase();
        var maxSize = (kind == "img") ? CHAT_IMG_MAX_SIZE : CHAT_FILE_MAX_SIZE;
        var allowedExt = (kind == "img") ? CHAT_IMG_ALLOWED_EXT : CHAT_FILE_ALLOWED_EXT;

        console.log("[CHAT_ATTACH] [" + (index + 1) + "/" + fileList.length + "] 파일 처리:", {
            fileName: file.name,
            fileSize: (file.size / 1024 / 1024).toFixed(2) + "MB",
            ext: ext
        });

        if (allowedExt.indexOf(ext) === -1) {
            console.log("[CHAT_ATTACH] 확장자 검증 실패:", ext);
            alert("[" + (index + 1) + "] " + file.name + " - 허용되지 않는 파일 형식입니다.");
            uploadFilesSequentially(fileList, kind, index + 1, false, uploadedUrls);
            return;
        }
        if (file.size > maxSize) {
            console.log("[CHAT_ATTACH] 용량 초과:", (file.size / 1024 / 1024).toFixed(2) + "MB");
            alert("[" + (index + 1) + "] " + file.name + " - 파일 용량은 " + (maxSize / (1024 * 1024)) + "MB 이하만 첨부 가능합니다.");
            uploadFilesSequentially(fileList, kind, index + 1, false, uploadedUrls);
            return;
        }
        if (typeof rm_info_ready !== 'undefined' && !rm_info_ready) {
            console.log("[CHAT_ATTACH] 채팅방 미준비");
            alert("채팅방 정보를 불러오는 중입니다. 잠시 후 다시 시도해주세요.");
            return;
        }

        console.log("[CHAT_ATTACH] [" + (index + 1) + "] 검증 통과, 업로드 요청 시작");
        var formData = new FormData();
        formData.append("attach_file", file);
        formData.append("attach_type", kind);

        // 진행률 표시 UI 표시
        if (isFirstFile) {
            $("#uploadProgressContainer").show();
            $("#uploadFileName").text(file.name);
            $("#uploadPercent").text("0");
            $("#uploadProgressBar").css("width", "0%");
        }

        // XMLHttpRequest를 사용하여 진행률 추적
        var xhr = new XMLHttpRequest();

        // 진행률 이벤트 처리
        xhr.upload.addEventListener("progress", function(e) {
            if (e.lengthComputable) {
                var percentComplete = Math.round((e.loaded / e.total) * 100);
                console.log("[CHAT_ATTACH] [" + (index + 1) + "] 업로드 진행률:", percentComplete + "%");
                $("#uploadPercent").text(percentComplete);
                $("#uploadProgressBar").css("width", percentComplete + "%");
            }
        }, false);

        // 완료 이벤트
        xhr.addEventListener("load", function() {
            try {
                var ret = JSON.parse(xhr.responseText);
                console.log("[CHAT_ATTACH] [" + (index + 1) + "] 업로드 응답:", ret);

                if (ret.code != "00") {
                    console.log("[CHAT_ATTACH] [" + (index + 1) + "] 업로드 실패:", ret.msg);
                    alert("[" + (index + 1) + "] " + file.name + " - " + (ret.msg || "업로드에 실패했습니다."));
                    uploadFilesSequentially(fileList, kind, index + 1, false, uploadedUrls);
                    return;
                }

                console.log("[CHAT_ATTACH] [" + (index + 1) + "] 업로드 성공:", {
                    url: ret.data.url,
                    file_name: ret.data.file_name
                });

                // 업로드된 파일 정보를 배열에 추가
                uploadedUrls.push({
                    url: ret.data.url,
                    fileName: ret.data.file_name
                });

                // 다음 파일 업로드
                uploadFilesSequentially(fileList, kind, index + 1, false, uploadedUrls);
            } catch (e) {
                console.error("[CHAT_ATTACH] [" + (index + 1) + "] JSON 파싱 오류:", e);
                alert("[" + (index + 1) + "] " + file.name + " - 업로드에 실패했습니다.");
                uploadFilesSequentially(fileList, kind, index + 1, false, uploadedUrls);
            }
        }, false);

        // 에러 이벤트
        xhr.addEventListener("error", function() {
            console.error("[CHAT_ATTACH] [" + (index + 1) + "] 업로드 중 오류 발생");
            alert("[" + (index + 1) + "] " + file.name + " - 업로드 중 오류가 발생했습니다.");
            uploadFilesSequentially(fileList, kind, index + 1, false, uploadedUrls);
        }, false);

        // 업로드 취소
        xhr.addEventListener("abort", function() {
            console.log("[CHAT_ATTACH] [" + (index + 1) + "] 업로드 취소됨");
            alert("[" + (index + 1) + "] " + file.name + " - 업로드가 취소되었습니다.");
            uploadFilesSequentially(fileList, kind, index + 1, false, uploadedUrls);
        }, false);

        // 요청 전송
        xhr.open("POST", "/chat_proc/upload_chat_file", true);
        xhr.send(formData);
    }

    // 첨부 메시지 전송 (배열 형식으로 여러 파일 지원)
    function sendAttachMessage(kind, urlArray) {
        console.log("[CHAT_ATTACH] sendAttachMessage 시작:", { kind: kind, count: urlArray.length });

        var chatotherid = $('#otherid').val();

        // URL 배열과 파일명 배열을 JSON 문자열로 직렬화
        var imgUrls = [];
        var fileUrls = [];
        var fileNames = [];

        if (kind == "img") {
            imgUrls = urlArray.map(function(item) { return item.url; });
        } else if (kind == "file") {
            fileUrls = urlArray.map(function(item) { return item.url; });
            fileNames = urlArray.map(function(item) { return item.fileName; });
        }

        var message = {
            rm_idx: Number(rm_idx),
            user_id: g_user_id,
            mg_type: kind,
            mg_text: (kind == "file") ? JSON.stringify(fileNames) : "",
            mg_req_dt: "",
            mg_img: (kind == "img") ? JSON.stringify(imgUrls) : "",
            mg_file: (kind == "file") ? JSON.stringify(fileUrls) : "",
            mg_imoti: "",
            mg_dt: getCurrentDate(),
            mg_reads: new Array(),
        };

        for (var icnt = 0; icnt < rm_info.rm_users.length; icnt++) {
            if (g_user_id == rm_info.rm_users[icnt].user_id) {
                message.mg_reads.push({ read_yn: "Y", user_id: rm_info.rm_users[icnt].user_id });
            } else {
                message.mg_reads.push({ read_yn: "N", user_id: rm_info.rm_users[icnt].user_id });
            }
        }

        console.log("[CHAT_ATTACH] 소켓 emit 호출:", { mg_type: kind, imgCount: imgUrls.length, fileCount: fileUrls.length });
        message.backUrl = window.backUrl;
        socket.emit("message_insert", message, rm_info.rm_users);
    }

    // ===== 채팅방 안 제안하기(COMP)/지원하기(MEM) 기능 =====
    $(document).on("click", ".attach-item[data-attach='propose']", function() {
        if (typeof rm_info_ready !== 'undefined' && !rm_info_ready) {
            alert("채팅방 정보를 불러오는 중입니다. 잠시 후 다시 시도해주세요.");
            return;
        }

        var userType = $("input[name='user_type']").val();

        if (userType !== "COMP") {
            // 개인회원(MEM): 팝업 없이 본인 대표이력서로 즉시 처리
            $("#btnAttachToggle").attr("data-state", "closed");
            $("#attachMenu").attr("data-state", "closed");

            if (!myMainResumeIdx) {
                openPopup('confirm', {
                    content: '등록된 이력서가 없습니다.<br>이력서를 등록해주세요.',
                    firstButton: '취소',
                    secondButton: '이력서 등록',
                    secondCallback: function() {
                        location.href = "/mypage/resume_write1";
                    }
                });
                return;
            }

            proceedProposeWithOwnSelection("RECRUIT", myMainResumeIdx, myMainResumeName);
            return;
        }

        // 기업회원(COMP): 기존과 동일하게 본인 채용공고 선택 패널을 연다
        var $proposeItems = $("[name='chk_propose[]']");
        if ($proposeItems.length === 0) {
            openPopup('confirm', {
                content: '등록된 채용공고가 없습니다.<br>채용공고를 등록해주세요.',
                firstButton: '취소',
                secondButton: '채용공고 등록',
                secondCallback: function() {
                    location.href = "/compage/recruit_write1_1";
                }
            });
            return;
        }

        // 첨부 메뉴 닫기
        $("#btnAttachToggle").attr("data-state", "closed");
        $("#attachMenu").attr("data-state", "closed");

        // 라디오 선택 초기화 후 바텀시트 열기
        $proposeItems.prop("checked", false);
        var $panel = document.getElementById("proposeSelectPanel");
        if ($panel && typeof openPanel === "function") {
            openPanel($panel);
        }
    });

    // 1단계(내 항목) → 2단계(상대방 항목) 사이에 선택값을 잠시 들고 있기 위한 변수
    var proposeStep1Selection = null;

    // 실제 API 호출 + 완료 후처리 (일반 채팅방/다이렉트 채팅방 공통)
    function submitPropose(mqType, selectedIdx, selectedName, opponentIdx, $panelToClose) {
        var params = {
            rm_idx: rm_idx,
            mq_type: mqType,
            rc_idx: (mqType == "RESUME") ? selectedIdx : opponentIdx,
            ru_idx: (mqType == "RESUME") ? opponentIdx : selectedIdx
        };

        comAjax("/chat_proc/propose_from_room", params, function(ret) {
            if ($panelToClose && $panelToClose.length > 0 && typeof closePanel === "function") {
                closePanel($panelToClose[0]);
            }

            if (ret.code == "00") {
                // 갱신된 공고 데이터가 있으면 카드 재렌더링 (RESUME/RECRUIT 공통)
                if (ret.data && ret.data.recruit) {
                    window.renderChatRecruitCard(ret.data.recruit);
                }
                // 카드 표시
                $('#chatRecruitCardList').attr('data-visible', 'true');
                var msgText = (mqType == "RESUME")
                    ? ("\"" + selectedName + "\" 공고를 제안했습니다.")
                    : ("\"" + selectedName + "\" 이력서로 지원했습니다.");
                // ret.data.resume: 지원하기(RECRUIT)로 실제 이력서를 선택한 경우에만 채워짐
                // 메시지에 함께 실어 보내면, 소켓 브로드캐스트로 상대방(기업회원) 화면의 이력서 카드도
                // 새로고침 없이 실시간으로 갱신됨
                sendProposeSystemMessage(msgText, mqType, params.rc_idx, params.ru_idx, ret.data ? ret.data.resume : null);
            } else {
                // 이미 지원/제안한 이력이 있는 경우(code=90) 등: 안내만 표시, 채팅 메시지 전송 없음
                alert(ret.msg || "처리에 실패했습니다.");
            }
        });
    }

    // 본인 항목 선택 이후 처리 (1부 COMP + 2부 MEM 자동 선택)
    // 상대방의 mm_idx 기준으로 조회한 대표 이력서/공고를 항상 사용 (is_direct_chat 판정에 의존하지 않음)
    function proceedProposeWithOwnSelection(mqType, selectedIdx, selectedName) {
        if (mqType == "RESUME") {
            // COMP → 상대방 대표이력서 자동 선택, 팝업 없이 즉시 전송
            if (!opponentMainResumeIdx) {
                alert("상대방이 대표 이력서를 등록하지 않아 제안할 수 없습니다.");
                return;
            }
            submitPropose(mqType, selectedIdx, selectedName, opponentMainResumeIdx, $("#proposeSelectPanel"));
        } else {
            // MEM → 상대방 채용공고 자동 선택, 팝업 없이 즉시 전송
            if (!opponentMainRecruitIdx) {
                alert("상대방이 채용공고를 등록하지 않아 지원할 수 없습니다.");
                return;
            }
            submitPropose(mqType, selectedIdx, selectedName, opponentMainRecruitIdx, $("#proposeSelectPanel"));
        }
    }

    // 1단계(COMP 전용): 본인 채용공고 선택 후 "다음"/"보내기"
    $(document).on("click", "#btnProposeNext", function() {
        var $checked = $("[name='chk_propose[]']:checked");
        if ($checked.length === 0) {
            alert("항목을 선택해주세요.");
            return;
        }

        var mqType = $("#proposeMqType").val(); // RESUME: 제안하기(COMP) / RECRUIT: 지원하기(MEM)
        // value 대신 data-propose-id 속성으로 값을 안전하게 추출 (name 배열 형식 jQuery 호환성 문제 회피)
        var selectedIdx = $checked.data("propose-id") || $checked.val();
        var selectedName = $checked.data("name") || "";

        proceedProposeWithOwnSelection(mqType, selectedIdx, selectedName);
    });

    // 2단계: 상대방 항목 선택 후 최종 "보내기" (다이렉트 채팅방 전용)
    $(document).on("click", "#btnOpponentSend", function() {
        if (!proposeStep1Selection) {
            alert("항목을 다시 선택해주세요.");
            return;
        }

        var $checkedOpponent = $("[name='chk_opponent[]']:checked");
        if ($checkedOpponent.length === 0) {
            alert("상대방 항목을 선택해주세요.");
            return;
        }
        var opponentIdx = $checkedOpponent.data("opponent-id") || $checkedOpponent.val();

        submitPropose(
            proposeStep1Selection.mqType,
            proposeStep1Selection.selectedIdx,
            proposeStep1Selection.selectedName,
            opponentIdx,
            $("#opponentSelectPanel")
        );
        proposeStep1Selection = null;
    });

    // 제안/지원 완료 안내를 채팅창에 시스템 메시지로 전송
    // mqType/rcIdx/ruIdx: 말풍선 클릭 시 해당 공고(rcIdx)/이력서(ruIdx) 팝업을 열기 위해 함께 저장
    function sendProposeSystemMessage(msgText, mqType, rcIdx, ruIdx, resumeData) {
        var message = {
            rm_idx: Number(rm_idx),
            user_id: g_user_id,
            mg_type: "propose",
            mg_text: msgText,
            mg_req_dt: "",
            mg_img: "",
            mg_file: "",
            mg_imoti: "",
            mg_dt: getCurrentDate(),
            mg_reads: new Array(),
            mg_mq_type: mqType || "",
            mg_rc_idx: rcIdx || "",
            mg_ru_idx: ruIdx || "",
            // 기업회원 화면 이력서 카드 실시간 갱신용 (지원하기로 실제 이력서 선택 시에만 값 존재)
            mg_resume_data: resumeData ? JSON.stringify(resumeData) : "",
        };

        for (var icnt = 0; icnt < rm_info.rm_users.length; icnt++) {
            if (g_user_id == rm_info.rm_users[icnt].user_id) {
                message.mg_reads.push({ read_yn: "Y", user_id: rm_info.rm_users[icnt].user_id });
            } else {
                message.mg_reads.push({ read_yn: "N", user_id: rm_info.rm_users[icnt].user_id });
            }
        }

        message.backUrl = window.backUrl;
        socket.emit("message_insert", message, rm_info.rm_users);
    }

    // 제안하기/지원하기 안내 말풍선 클릭 시 해당 공고/이력서 팝업 열기
    $(document).on('click', '.propose-bubble', function(e) {
        var mqType = $(this).data('mq_type');
        var rcIdx = $(this).data('rc_idx');
        var ruIdx = $(this).data('ru_idx');

        if (mqType === 'RESUME' && rcIdx) {
            if (typeof window.openJobPostPopup === 'function') {
                window.openJobPostPopup(rcIdx);
            }
        } else if (mqType === 'RECRUIT' && ruIdx) {
            if (typeof window.openHrPostPopup === 'function') {
                window.openHrPostPopup(ruIdx);
            }
        }
    });

    // 상단 공고 카드(#chatRecruitCardList) 클릭 시 채용공고 상세 팝업 열기
    $(document).on('click', '#chatRecruitCardList .content-li', function(e) {
        var rcIdx = $(this).data('rc_idx');
        if (rcIdx && typeof window.openJobPostPopup === 'function') {
            window.openJobPostPopup(rcIdx);
        }
    });

    // 상단 이력서 카드(#chatResumeList) 클릭 시 이력서 상세 팝업 열기
    $(document).on('click', '#chatResumeList .content-li', function(e) {
        var ruIdx = $(this).data('ru_idx');
        if (ruIdx && typeof window.openHrPostPopup === 'function') {
            window.openHrPostPopup(ruIdx);
        }
    });

    // 이력서 상세 팝업 함수 (resume/hr_list.php와 동일 패턴)
    window.openHrPostPopup = function(ruIdx) {
        $.ajax({
            url: '/resume/hr_post/' + ruIdx,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(html) {
            var popup = document.getElementById('hrPostPopup');
            popup.innerHTML = html;

            // 로드된 HTML 내 script 태그 실행
            var scripts = popup.querySelectorAll('script');
            scripts.forEach(function(script) {
                if (script.src) {
                    var newScript = document.createElement('script');
                    newScript.src = script.src;
                    newScript.async = script.async;

                    if (script.src.indexOf('share-panel.js') !== -1) {
                        newScript.onload = function() {
                            if (typeof initializeSharePanel === 'function') {
                                initializeSharePanel();
                            }
                        };
                        newScript.onerror = function() {
                            console.error('[openHrPostPopup] share-panel.js 로드 실패');
                        };
                    }

                    popup.appendChild(newScript);
                } else {
                    var newScript = document.createElement('script');
                    newScript.textContent = script.textContent;
                    popup.appendChild(newScript);
                }
            });

            document.body.style.overflow = 'hidden';
            localStorage.setItem('resume_came_from_hr_post', 'true');
            localStorage.setItem('came_from_hr_list', 'true');
            sessionStorage.setItem('resume_highlight_ru_idx', ruIdx);

            // GSAP으로 팝업 열기 애니메이션
            gsap.fromTo('#hrPostPopup',
                { x: '100%' },
                {
                    duration: 0.5,
                    x: '0%',
                    ease: "power1.out",
                    clearProps: "transform",
                    onComplete: function() {
                        document.getElementById('hrPostPopup').setAttribute('data-state', 'open');
                    }
                }
            );

            if (typeof initLazyLoad === 'function') {
                initLazyLoad();
            }
            if (typeof window.initJobPostDescFeatures === 'function') {
                window.initJobPostDescFeatures();
            }
        }).fail(function(xhr, status, error) {
            console.error('[openHrPostPopup] AJAX 실패 - status:', status, 'error:', error);
        });
    };

    window.closeHrPostPopup = function(e) {
        if (e) e.preventDefault();

        var $selectPanel = $("#selectPanel");
        if ($selectPanel.length > 0) {
            $selectPanel.removeClass("on");
        }

        gsap.to('#hrPostPopup', {
            duration: 0.5,
            x: '100%',
            ease: "power1.out",
            onComplete: function() {
                document.getElementById('hrPostPopup').setAttribute('data-state', 'closed');
                $('#hrPostPopup').html('');
            }
        });

        document.body.style.overflow = 'auto';
        document.documentElement.style.overflow = 'auto';
        if (typeof applyLastClickedHighlight === 'function') {
            applyLastClickedHighlight();
        }
    };

    window.isHrPostPopupOpen = function() {
        return $('#hrPostPopup').attr('data-state') === 'open';
    };

    // 채용공고 상세 팝업 함수 (recruit/job_list.php와 동일 패턴)
    window.openJobPostPopup = function(rcIdx) {
        $.ajax({
            url: '/recruit/job_post/' + rcIdx,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(html) {
            var popup = document.getElementById('jobPostPopup');
            popup.innerHTML = html;

            var scripts = popup.querySelectorAll('script');
            scripts.forEach(function(script) {
                if (script.src) {
                    var newScript = document.createElement('script');
                    newScript.src = script.src;
                    newScript.async = script.async;

                    if (script.src.indexOf('share-panel.js') !== -1) {
                        newScript.onload = function() {
                            if (typeof initializeSharePanel === 'function') {
                                initializeSharePanel();
                            }
                        };
                        newScript.onerror = function() {
                            console.error('[openJobPostPopup] share-panel.js 로드 실패');
                        };
                    }

                    popup.appendChild(newScript);
                } else {
                    var newScript = document.createElement('script');
                    newScript.textContent = script.textContent;
                    popup.appendChild(newScript);
                }
            });

            document.body.style.overflow = 'hidden';
            sessionStorage.setItem('recruit_came_from_job_post', 'true');
            sessionStorage.setItem('recruit_highlight_rc_idx', rcIdx);

            gsap.fromTo('#jobPostPopup',
                { x: '100%' },
                {
                    duration: 0.5,
                    x: '0%',
                    ease: "power1.out",
                    clearProps: "transform",
                    onComplete: function() {
                        document.getElementById('jobPostPopup').setAttribute('data-state', 'open');
                    }
                }
            );

            if (typeof initLazyLoad === 'function') {
                initLazyLoad();
            }
            if (typeof KeenSlider !== 'undefined' && typeof sliderDots === 'function' && typeof addActiveClass === 'function') {
                new KeenSlider(".view-visual-slider .slider-wrap", {
                    loop: true,
                }, [sliderDots, addActiveClass]);
            }
            if (typeof jQuery !== 'undefined') {
                $('#jobPostPopup .view-visual-slider').magnificPopup({
                    delegate: 'img',
                    type: 'image',
                    gallery: { enabled: true }
                });
            }
            if (typeof window.initJobPostDescFeatures === 'function') {
                window.initJobPostDescFeatures();
            }
        }).fail(function(xhr, status, error) {
            console.error('[openJobPostPopup] AJAX 실패 - status:', status, 'error:', error);
        });
    };

    window.closeJobPostPopup = function(e) {
        if (e) e.preventDefault();

        var popup = document.getElementById('jobPostPopup');
        if (popup) {
            var openPanels = popup.querySelectorAll('.layer-panel.on');
            openPanels.forEach(function(panel) {
                if (typeof closePanel === 'function') {
                    closePanel(panel);
                }
            });
        }

        gsap.to('#jobPostPopup', {
            duration: 0.5,
            x: '100%',
            ease: "power1.out",
            onComplete: function() {
                document.getElementById('jobPostPopup').setAttribute('data-state', 'closed');
                $('#jobPostPopup').html('');
            }
        });

        document.body.style.overflow = 'auto';
        document.body.style.overflowY = 'auto';
        document.documentElement.style.overflow = 'auto';
        document.documentElement.style.overflowY = 'auto';

        if (typeof restoreBodyScroll === 'function') {
            restoreBodyScroll();
        }
        if (typeof applyLastClickedHighlight === 'function') {
            applyLastClickedHighlight();
        }
    };

    window.isJobPostPopupOpen = function() {
        return $('#jobPostPopup').attr('data-state') === 'open';
    };

    // 제안/지원 선택 바텀시트: 항목 클릭 시 해당 항목의 radio 버튼 선택
    $(document).on("click", "#proposeSelectPanel .selectable-item, #opponentSelectPanel .selectable-item", function(e) {
        if ($(e.target).is("label") || $(e.target).is("input") || $(e.target).closest("label").length > 0) {
            return;
        }
        var $radio = $(this).find("input[type='radio']");
        if ($radio.length > 0) {
            $radio.prop("checked", true);
        }
    });

    // 이미지 라이트박스 (묶음 사진 썸네일 네비게이션 지원)
    var lightboxImages = [];
    var lightboxIndex = 0;

    function openImageLightbox(images, index) {
        lightboxImages = images;
        lightboxIndex = index;
        renderLightboxState();
        $("#chatImgLightbox").css("display", "flex");
        // 핀치 줌 초기화
        initChatLightboxPinch();
    }

    // 라이트박스 이미지 핀치 줌 기능 (job_post.php 패턴 기반)
    function initChatLightboxPinch() {
        var imgEl = document.getElementById('chatImgLightboxImg');
        if (!imgEl || imgEl.dataset.pinchInit) return;
        imgEl.dataset.pinchInit = '1';

        var scale = 1;
        var lastScale = 1;
        var startDist = 0;
        var originX = 0, originY = 0;
        var translateX = 0, translateY = 0;
        var isPinching = false;
        var isPanning = false;
        var wasPinching = false;
        var panStartX = 0, panStartY = 0;

        var MIN_SCALE = 1;
        var MAX_SCALE = 3;

        function getDistance(t1, t2) {
            var dx = t2.clientX - t1.clientX;
            var dy = t2.clientY - t1.clientY;
            return Math.sqrt(dx * dx + dy * dy);
        }

        function applyTransform() {
            var cw = imgEl.offsetWidth;
            var ch = imgEl.offsetHeight;
            var minTx = -(cw * (scale - 1));
            var maxTx = 0;
            var minTy = -(ch * (scale - 1));
            var maxTy = 0;
            translateX = Math.max(minTx, Math.min(maxTx, translateX));
            translateY = Math.max(minTy, Math.min(maxTy, translateY));

            imgEl.style.transform = 'translate(' + translateX + 'px, ' + translateY + 'px) scale(' + scale + ')';
        }

        imgEl.addEventListener('touchstart', function(e) {
            if (e.touches.length === 2) {
                isPinching = true;
                isPanning = false;
                wasPinching = false;
                startDist = getDistance(e.touches[0], e.touches[1]);
                lastScale = scale;

                var rect = imgEl.getBoundingClientRect();
                originX = (e.touches[0].clientX + e.touches[1].clientX) / 2 - rect.left;
                originY = (e.touches[0].clientY + e.touches[1].clientY) / 2 - rect.top;

                imgEl.classList.add('pinching');
                e.preventDefault();
            } else if (e.touches.length === 1 && scale > 1) {
                isPanning = true;
                isPinching = false;
                panStartX = e.touches[0].clientX - translateX;
                panStartY = e.touches[0].clientY - translateY;
            }
        }, { passive: false });

        imgEl.addEventListener('touchmove', function(e) {
            if (isPinching && e.touches.length === 2) {
                var dist = getDistance(e.touches[0], e.touches[1]);
                var newScale = lastScale * (dist / startDist);
                newScale = Math.min(MAX_SCALE, Math.max(MIN_SCALE, newScale));

                var scaleChange = newScale / scale;
                translateX = originX - scaleChange * (originX - translateX);
                translateY = originY - scaleChange * (originY - translateY);

                scale = newScale;
                applyTransform();
                e.preventDefault();
            } else if (isPanning && e.touches.length === 1 && scale > 1) {
                translateX = e.touches[0].clientX - panStartX;
                translateY = e.touches[0].clientY - panStartY;
                applyTransform();
                e.preventDefault();
            }
        }, { passive: false });

        imgEl.addEventListener('touchend', function(e) {
            if (e.touches.length < 2) {
                if (isPinching) wasPinching = true;
                isPinching = false;
                imgEl.classList.remove('pinching');
            }
            if (e.touches.length === 0) {
                isPanning = false;
                if (scale <= MIN_SCALE) {
                    scale = 1;
                    translateX = 0;
                    translateY = 0;
                    applyTransform();
                }
            }
        }, { passive: true });
    }

    function resetChatLightboxPinch() {
        var imgEl = document.getElementById('chatImgLightboxImg');
        if (!imgEl) return;
        imgEl.style.transform = 'translate(0px, 0px) scale(1)';
        imgEl.dataset.pinchInit = '';
    }

    function renderLightboxState() {
        var url = lightboxImages[lightboxIndex];
        $("#chatImgLightboxImg").attr("src", url);
        $("#chatImgLightboxCounter").text(lightboxImages.length + "장 중 " + (lightboxIndex + 1) + "번");

        var thumbHtml = "";
        for (var i = 0; i < lightboxImages.length; i++) {
            var activeCls = (i === lightboxIndex) ? " active" : "";
            thumbHtml += "<img src='" + lightboxImages[i] + "' class='chat-lightbox-thumb" + activeCls + "' data-index='" + i + "' alt=''>";
        }
        $("#chatImgLightboxThumbs").html(thumbHtml).toggle(lightboxImages.length > 1);
    }

    $(document).on("click", ".chat-img", function() {
        var $wrap = $(this).closest(".chat-img-wrap");
        var images = [];
        try {
            images = JSON.parse($wrap.attr("data-images") || "[]");
        } catch(e) {
            images = [$(this).data("full")];
        }
        var index = Number($(this).data("index")) || 0;
        openImageLightbox(images, index);
    });

    $(document).on("click", ".chat-lightbox-thumb", function(e) {
        e.stopPropagation();
        lightboxIndex = Number($(this).data("index"));
        renderLightboxState();
    });

    $(document).on("click", "#chatImgLightbox", function() {
        $(this).hide();
        $("#chatImgLightboxImg").attr("src", "");
        lightboxImages = [];
        lightboxIndex = 0;
        // 핀치 줌 상태 리셋
        resetChatLightboxPinch();
    });

    $(document).on("click", "#chatImgLightboxDownloadBtn", function(e) {
        e.stopPropagation();
        openPanel(document.getElementById("chatImgSaveSheet"));
    });

    $(document).on("click", "#btnSaveThisImg", function() {
        var $btn = $(this);

        // 이미 다운로드 진행 중이면 무시
        if ($btn.attr('data-downloading') === 'true') {
            console.log("[SAVE_THIS_IMG] 다운로드 진행 중, 무시됨");
            return;
        }

        $btn.attr('data-downloading', 'true');
        var url = lightboxImages[lightboxIndex];
        console.log("[SAVE_THIS_IMG] 사진 다운로드 시작:", { url: url });
        triggerNativeDownload(url, url.split("/").pop());
        closePanel(document.getElementById("chatImgSaveSheet"));

        // 2.5초 후 재클릭 가능하도록 해제
        setTimeout(function() {
            $btn.attr('data-downloading', 'false');
        }, 2500);
    });

    $(document).on("click", "#btnSaveAllImg", function() {
        var $btn = $(this);

        // 이미 다운로드 진행 중이면 무시
        if ($btn.attr('data-downloading') === 'true') {
            console.log("[SAVE_ALL_IMG] 다운로드 진행 중, 무시됨");
            return;
        }

        $btn.attr('data-downloading', 'true');
        console.log("[SAVE_ALL_IMG] 모든 사진 다운로드 시작:", { count: lightboxImages.length });

        for (var i = 0; i < lightboxImages.length; i++) {
            triggerNativeDownload(lightboxImages[i], lightboxImages[i].split("/").pop());
        }
        closePanel(document.getElementById("chatImgSaveSheet"));

        // 2.5초 후 재클릭 가능하도록 해제
        setTimeout(function() {
            $btn.attr('data-downloading', 'false');
        }, 2500);
    });

    // 파일 다운로드 (중복 클릭 방지)
    $(document).on("click", ".chat-file", function() {
        var $this = $(this);

        // 이미 다운로드 진행 중이면 무시
        if ($this.attr('data-downloading') === 'true') {
            console.log("[CHAT_FILE_CLICK] 다운로드 진행 중, 무시됨");
            return;
        }

        $this.attr('data-downloading', 'true');
        var url = $this.data("url");
        var fileName = $this.data("filename");
        console.log("[CHAT_FILE_CLICK] 파일 탭됨:", { url: url, fileName: fileName });
        triggerNativeDownload(url, fileName);

        // 2.5초 후 재클릭 가능하도록 해제
        setTimeout(function() {
            $this.attr('data-downloading', 'false');
        }, 2500);
    });

    // 파일 열기 (열기 버튼 클릭)
    $(document).on("click", ".chat-file-open-btn", function(e) {
        e.stopPropagation();
        var url = $(this).data("url");
        var fileName = $(this).data("filename");
        console.log("[CHAT_FILE_OPEN_CLICK] 파일 열기 탭됨:", { url: url, fileName: fileName });
        triggerNativeOpen(url, fileName);
    });

    // 바텀시트 Swipe 기능 초기화 (main.js에서 정의된 전역 함수 사용)
    window.addEventListener('DOMContentLoaded', function() {
        if (typeof initPanelSwipe === 'function') {
            initPanelSwipe('chatImgSaveSheet');
        }
    });

</script>
<!-- ===== 앱(WebView) 전용 스크립트 : 안드로이드 WebView에서만 실행 ===== -->
<?php if ($this->common->getSession("os_type") == "android") : ?>
    <script>
        /**
         * 페이지 로드 → g_user_id 조회 → room_info 요청 
            → room_info_return 수신 (rm_info_ready = true로 변경) 
            → first_yn 체크하여 초기 메시지 로드 여부 결정
         */

        var g_user_id = ""; // 현재 로그인한 사용자의 ID를 저장
        var first_yn = "N"; // 채팅방 첫 진입 여부 플래그
        var rm_info_ready = false; // false = 아직 준비 중 / true = 채팅방 정보 로드 완료 (전송 버튼 활성화 등)

        // 안드로이드 앱에서 실행
        window.addEventListener('DOMContentLoaded', function(e) {
            //룸정보 가져오기.
            g_user_id = ensureValidUserId();
            if (!g_user_id) {
                return;
            }

            // 🔥 DEBUG: DOMContentLoaded - 페이지 로드됨
            console.log('[chat_view] DOMContentLoaded - requested_rm_idx: ' + requested_rm_idx + ', g_user_id: ' + g_user_id);

            // 안드로이드에서도 소켓을 사용하여 룸 정보 조회
            // socket.emit("room_join", ...)와 socket.emit("room_info", ...)는 기능이 다르다.
            // 1. room_join:
            //    - 사용자를 특정 채팅방(room)에 참여(join)시키는 역할.
            //    - 소켓 서버가 해당 사용자를 해당 채팅방의 멤버로 추가하여
            //      이후 실시간 메시지, 알림 등 이벤트를 해당 room에서 받을 수 있게 함.
            console.log('[chat_view] room_join emit - rm_idx: ' + rm_idx);
            socket.emit("room_join", {
                rm_idx: Number(rm_idx),
                con_user_id: g_user_id
            });

            // 2. room_info:
            //    - 채팅방의 상세 정보(예: 방 제목, 참여자, 설정 등)를 요청하는 역할.
            //    - 서버에 해당 room의 정보를 요청하면 서버가 정보를 반환(room_info_return 이벤트로)함.
            console.log('[chat_view] room_info emit - rm_idx: ' + rm_idx);
            socket.emit("room_info", {
                rm_idx: Number(rm_idx),
                con_user_id: g_user_id
            });
            
            // 전송 버튼 비활성화 (room_info_return 수신 후 활성화)
            $("#btnSend").prop("disabled", true);

            // ✅ 메시지 리스트는 room_info_return에서 조회하도록 변경 (중복 조회 방지)
            // DOMContentLoaded에서 바로 조회하면 room_info_return에서도 조회하여 중복 발생
        });
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("room_info_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.room_info_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         * 
         * 레거시 함수 주석처리 - Socket.IO 방식 사용
         */
        /*
        //방생성 확인 및 방번호 확인
        function room_info_return(data) {
            var ret = JSON.parse(data);
            if (ret.code != "00") {
                alert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.");
            } else {
                //채팅방 정보
                rm_info = ret.data;

                //$("#rm_subject").html(getRoomSubject(rm_info,g_user_id));

                //메시지 리스트 조회
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: rm_start,
                    limit: rm_length
                };

                // 안드로이드에서도 소켓을 사용하여 메시지 리스트 조회
                socket.emit("message_list", params);
            }
        }
        */

        // 안드로이드에서도 소켓 이벤트 리스너 추가
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("room_info_return");
        socket.on("room_info_return", function(ret) {
            if (ret.code != "00") {
                if (typeof commonAlert === "function") {
                    commonAlert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.", function() {});
                } else {
                    alert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.");
                }
            } else {
                // ✅ 응답 검증: 이 응답이 내가 요청한 방(requested_rm_idx)에 대한 것인지 확인
                if (String(ret.data.rm_idx) !== String(requested_rm_idx)) {
                    console.warn("[room_info_return] 다른 방의 응답 무시됨. 요청: " + requested_rm_idx + ", 수신: " + ret.data.rm_idx);
                    return; // 다른 방에 대한 응답은 버림
                }

                // 채팅방 진입 시 중복 메시지 방지 배열 초기화
                resetProcessedIdx();

                //채팅방 정보
                rm_info = ret.data;

                // 🔥 중요: 서버에서 받은 올바른 rm_idx로 업데이트 (rc_idx가 아닌 실제 rm_idx)
                if (ret.data && ret.data.rm_idx) {
                    var old_rm_idx = rm_idx; // 업데이트 전 값 저장
                    rm_idx = ret.data.rm_idx;
                }
                
                // 채팅방 정보 준비 완료 플래그 설정
                rm_info_ready = true;
                
                // 전송 버튼 활성화
                $("#btnSend").prop("disabled", false);

                // ✅ 메시지 리스트 조회 (room_info_return에서만 조회하여 중복 방지)
                var current_g_user_id = typeof g_user_id !== 'undefined' && g_user_id ? g_user_id : $("#user_id").val();
                if (!current_g_user_id) {
                    console.log('[chat_view] ⚠️ message_list emit 직전 g_user_id 비어있음 (Android 최초 조회) - rm_idx=' + rm_idx);
                } else {
                    console.log('[chat_view] message_list emit (Android 최초 조회) - rm_idx=' + rm_idx + ', con_user_id=' + current_g_user_id);
                }
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: rm_start,
                    limit: rm_length,
                    con_user_id: current_g_user_id
                };
                socket.emit("message_list", params);
                
                // 🔥 제안하기/지원하기 완료 후 자동 메시지 전송 체크
                // room_info_return에서 rm_info를 받았으므로, targetUserId를 저장하고
                // message_list_return에서 메시지가 없으면 전송하도록 함
                if (window.autoSendFirstMessage && window.autoSendFirstMessage.enabled) {
                    var autoSendInfo = window.autoSendFirstMessage;
                    var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();
                    
                    // 상대방 ID 찾기 (우선순위: 1. hidden input의 opponent_user_id, 2. rm_users에서 찾기)
                    var targetUserId = null;
                    
                    // hidden input에서 상대방 ID 가져오기 (서버에서 미리 찾아서 전달한 값)
                    var opponentUserIdFromInput = $("#opponent_user_id").val();
                    if (opponentUserIdFromInput && opponentUserIdFromInput.trim() !== "") {
                        targetUserId = opponentUserIdFromInput.trim();
                    }
                    
                    // hidden input에 없으면 rm_users에서 찾기
                    if (!targetUserId && ret.data && ret.data.rm_users && Array.isArray(ret.data.rm_users)) {
                        for (var i = 0; i < ret.data.rm_users.length; i++) {
                            if (ret.data.rm_users[i].user_id && ret.data.rm_users[i].user_id != g_user_id) {
                                targetUserId = ret.data.rm_users[i].user_id;
                                break;
                            }
                        }
                    }
                    
                    // targetUserId를 저장 (message_list_return에서 사용)
                    if (targetUserId) {
                        window.autoSendFirstMessage.targetUserId = targetUserId;
                        // rm_info도 받았음을 표시 (message_list_return에서 메시지가 없으면 바로 전송)
                        window.autoSendFirstMessage.rm_info_received = true;
                        
                    } else {
                    }
                }
            }
        });

        // ✅ message_list_return 핸들러는 공통 스크립트 영역으로 이동됨 (중복 방지)
        // ✅ message_insert_return 핸들러는 공통 스크립트 영역으로 이동됨 (중복 방지)
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("room_join_alarm") 이벤트 핸들러를 사용하지 않음
         * 대신 socket.on("room_join_return")에서 처리함
         * 
         * @deprecated 사용되지 않음
         * 
         * 레거시 함수 주석처리 - Socket.IO 방식 사용
         */
        /*
        //방접속시 알림
        function room_join_alarm(data) {
            $(".readcount").html("");
        }
        */

        //방 조인 리턴 처리
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("room_join_return");
        socket.on("room_join_return", function(ret) {
            // 🔥 채팅방 입장 시 읽음 카운트 정보 저장 (메시지 로드 후 업데이트)
            // 서버에서 ret.list에 { _id: mg_idx, count: 읽지 않은 개수 } 형태의 배열을 보냄
            if (ret && ret.list && Array.isArray(ret.list)) {
                // 읽음 카운트 정보를 저장 (메시지 로드 후 업데이트)
                pendingReadCounts = {};
                for (var i = 0; i < ret.list.length; i++) {
                    var msgInfo = ret.list[i];
                    var mg_idx = msgInfo._id;
                    var count = msgInfo.count || 0;
                    pendingReadCounts[mg_idx] = count;
                    
                    // 메시지가 이미 DOM에 있으면 즉시 업데이트
                    updateReadCount(mg_idx, count);
                }
            }
        });

        //메시지 읽음 카운트 리턴 처리 (안드로이드/웹/iOS 공통 핸들러)
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("message_read_count_return");
        /**
         * 메시지 읽음 카운트 정보를 서버로부터 수신했을 때 실행되는 핸들러입니다.
         * 서버로부터 특정 메시지(mg_idx)의 읽음 카운트(count)가 업데이트되어 전달되면,
         * 해당 메시지의 읽음 카운트 UI를 갱신합니다.
         * - ret.data.count, ret.data.mg_idx 또는 ret.count, ret.mg_idx에서 값 추출
         * - 만약 해당 메시지가 아직 DOM에 없다면 마지막 메시지에 반영(하위 호환)
         * - count가 0이면 카운트 영역을 비우고, 1 이상이면 숫자 표시
         */
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("message_read_count_return");
        socket.on("message_read_count_return", function(ret) {
            //리턴받은 카운트 메시지 정보 업데이트
            // ret.data 또는 ret에서 count와 mg_idx 추출
            var count = (ret.data && ret.data.count !== undefined) ? ret.data.count : (ret.count !== undefined ? ret.count : 0);
            var mg_idx = (ret.data && ret.data.mg_idx !== undefined) ? ret.data.mg_idx : (ret.mg_idx !== undefined ? ret.mg_idx : null);
            
            // 🔥 mg_idx가 null이면 처리하지 않음
            if (!mg_idx || mg_idx === null || mg_idx === undefined) {
                return;
            }
            
            if (mg_idx) {
                // 특정 메시지의 읽음 카운트 업데이트
                var $msgElement = $("#mg_idx_" + mg_idx);
                if ($msgElement.length > 0) {
                    if (count == 0) {
                        $msgElement.find(".readcount").html("");
                    } else {
                        $msgElement.find(".readcount").html(count);
                    }
                } else {
                    // 메시지가 아직 DOM에 없으면 마지막 메시지 업데이트 (하위 호환성)
                    $("[id^=mg_idx_]").last().prop("id", "mg_idx_" + mg_idx);
                    if (count == 0) {
                        $("[id^=mg_idx_]").last().find(".readcount").html("");
                    } else {
                        $("[id^=mg_idx_]").last().find(".readcount").html(count);
                    }
                }
            } else {
                // mg_idx가 없으면 마지막 메시지 업데이트 (기존 로직 유지)
                if (count == 0) {
                    $("[id^=mg_idx_]").last().find(".readcount").html("");
                } else {
                    $("[id^=mg_idx_]").last().find(".readcount").html(count);
                }
            }
        });

        // ✅ pagging_yn은 공통 영역에 정의됨
        $("#chat_body").scroll(function() {
            var scroll_top = $(this).scrollTop();
            if ($(this).scrollTop() < 300 && pagging_yn == false) {
                pagging_yn = true;

                //메시지 리스트 조회
                var current_g_user_id = typeof g_user_id !== 'undefined' && g_user_id ? g_user_id : $("#user_id").val();
                if (!current_g_user_id) {
                    console.log('[chat_view] ⚠️ message_list emit 직전 g_user_id 비어있음 (Android 페이지네이션) - rm_idx=' + rm_idx);
                } else {
                    console.log('[chat_view] message_list emit (Android 페이지네이션) - rm_idx=' + rm_idx + ', con_user_id=' + current_g_user_id);
                }
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: (++rm_start * rm_length),
                    limit: rm_length,
                    con_user_id: current_g_user_id
                };
                // 안드로이드에서도 소켓을 사용하여 메시지 리스트 조회
                socket.emit("message_list", params);
            }
        });

        $("#btnSend").click(function() {
            send_message();
        });

        /////////////////////////////////////////////////////////
        // 리턴 처리
        /////////////////////////////////////////////////////////
        //alert(1);
        function send_message() {
            var txt = $("#chatTxt").val();
            if (txt == "") {
                return;
            }
            if (!g_user_id) {
                g_user_id = ensureValidUserId();
                if (!g_user_id) {
                    return;
                }
            }

            // 채팅 메시지 전송 시작

            // 실무 방법: 플래그 변수로 채팅방 정보 준비 상태 확인
            var chatotherid = $('#otherid').val();
            if (!rm_info_ready || !rm_info) {
                if (typeof commonAlert === "function") {
                    commonAlert("채팅방 정보를 불러오는 중입니다. 잠시 후 다시 시도해주세요.", function() {});
                } else {
                    alert("채팅방 정보를 불러오는 중입니다. 잠시 후 다시 시도해주세요.");
                }
                return;
            }
            if (!rm_info.rm_users && !chatotherid) {
                if (typeof commonAlert === "function") {
                    commonAlert("채팅방 정보를 불러오는 중입니다. 잠시 후 다시 시도해주세요.", function() {});
                } else {
                    alert("채팅방 정보를 불러오는 중입니다. 잠시 후 다시 시도해주세요.");
                }
                return;
            }

            var message = {
                rm_idx: Number(rm_idx),
                user_id: g_user_id,
                mg_type: "text",
                mg_text: txt,
                mg_req_dt: "",
                mg_img: "",
                mg_file: "",
                mg_imoti: "",
                mg_dt: getCurrentDate(),
                mg_reads: new Array(),
            };

            //리드 카운터 초기화 한다.
            // rm_info.rm_users에 있는 사용자들 처리
            var found_other_user = false;
            var found_current_user = false;
            
            if (rm_info.rm_users && rm_info.rm_users.length > 0) {
                for (var icnt = 0; icnt < rm_info.rm_users.length; icnt++) {
                    if (g_user_id == rm_info.rm_users[icnt].user_id) {
                        message.mg_reads.push({
                            read_yn: "Y",
                            user_id: rm_info.rm_users[icnt].user_id
                        });
                        found_current_user = true;
                    } else {
                        message.mg_reads.push({
                            read_yn: "N",
                            user_id: rm_info.rm_users[icnt].user_id
                        });
                        // 상대방이 rm_info.rm_users에 있는지 확인
                        if (chatotherid && rm_info.rm_users[icnt].user_id == chatotherid) {
                            found_other_user = true;
                        }
                    }
                }
            }
            
            // 현재 사용자가 rm_info.rm_users에 없으면 추가
            if (!found_current_user) {
                message.mg_reads.push({
                    read_yn: "Y",
                    user_id: g_user_id
                });
            }
            
            // 상대방이 rm_info.rm_users에 없으면 직접 추가 (상대방이 아직 채팅방에 입장하지 않은 경우)
            if (chatotherid && !found_other_user) {
                message.mg_reads.push({
                    read_yn: "N",
                    user_id: chatotherid
                });
                // ✅ rm_info.rm_users에도 추가하여 서버로 전송 (서버에서 mg_reads 생성 시 사용)
                if (!rm_info.rm_users) {
                    rm_info.rm_users = [];
                }
                rm_info.rm_users.push({
                    user_id: chatotherid
                });
            }

            // ✅ 실무 방법: 서버 응답 후에만 메시지 표시 (안정적)
            // 임시 메시지 제거 - 버그 가능성 제거
            // 전송 버튼 비활성화로 중복 전송 방지
            var $sendBtn = $("#chatTxt").closest('form').find('button[type="submit"], .btn-send, #sendBtn');
            var sendBtnDisabled = false;
            if ($sendBtn.length > 0) {
                sendBtnDisabled = $sendBtn.prop('disabled');
                $sendBtn.prop('disabled', true);
            }

            //채팅방 실시간 쏘기 - Socket.IO만 사용
            message.backUrl = window.backUrl;
            socket.emit("message_insert", message, rm_info.rm_users);

            //채팅창 클리어
            $("#chatTxt").val(""); //.focus();
            document.getElementById('chatTxt').style.height = '3rem'; // textarea 높이 초기화

            // 서버 응답 후 버튼 활성화는 message_insert_return 핸들러에서 처리
        }
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("message_insert_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.message_insert_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         * 
         * 레거시 함수 주석처리 - Socket.IO 방식 사용
         */
        /*
        //메시지 정보 리턴
        function message_insert_return(data) {
            var ret = JSON.parse(data);

            var message_list = ret.data;
            for (var icnt = 0; icnt < message_list.length; icnt++) {
                showMessage(message_list[icnt], false);
                // 메시지를 보낸 직후 읽음 처리하지 않음
                // 상대방이 실제로 메시지를 읽었을 때만 message_read_return이 호출되어야 함
                // var params = {
                //     rm_idx: Number(rm_idx),
                //     mg_idx: Number(message_list[icnt].mg_idx),
                //     user_id: g_user_id
                // };
                // APP_NOWAPK.message_read_return(JSON.stringify(params));
            }
            scrollBottom();
        }
        */

        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("message_read_count_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.message_read_count_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         * 
         * 레거시 함수 주석처리 - Socket.IO 방식 사용
         */
        /*
        function message_read_count_return(data) {
            var ret = JSON.parse(data);

            //리턴받은 카운트 메시지 정보 업데이트
            $("[id^=mg_idx_]").last().prop("id", "mg_idx_" + ret.data.mg_idx)
            if (ret.data.count == 0) {
                $("[id^=mg_idx_]").last().find(".readcount").html("");
            } else {

                $("[id^=mg_idx_]").last().find(".readcount").html(ret.data.count);
            }
        }
        */

        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("message_list_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.message_list_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         * 
         * 레거시 함수 주석처리 - Socket.IO 방식 사용
         */
        /*
        //방생성 확인 및 방번호 확인
        function message_list_return(data) {
            //alert('message_list_return');

            var ret = JSON.parse(data);

            //메시지 리스트 가져오기
            var message_list = ret.data;
            for (var icnt = 0; icnt < message_list.length; icnt++) {
                showMessage(message_list[icnt].rm_messages, true);
            }
            if (pagging_yn == false) {
                scrollBottom();
            }
            pagging_yn = false;
        }
        */
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("room_join_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.room_join_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         * 
         * 레거시 함수 주석처리 - Socket.IO 방식 사용
         */
        /*
        //방 조인
        function room_join_return(data) {
            //alert('join_return:685');
            $(".readcount").html("");
        }
        */
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("room_leave_alarm") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.room_leave_alarm()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         * 
         * 레거시 함수 주석처리 - Socket.IO 방식 사용
         */
        /*
        //접속 끊길때 방 정리
        function room_leave_alarm(ret) {
            alert("접속이 끊겼습니다.");
            socket.emit("room_leave", {
                rm_idx: Number(rm_idx)
            });
        }
        */
        /**
         * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
         * 현재는 socket.on("room_leave_return") 이벤트 핸들러를 사용함
         * 안드로이드 앱에서 APP_NOWAPK.room_leave_return()으로 호출할 수 있지만,
         * 실제로는 Socket.IO 이벤트로 처리되므로 이 함수는 호출되지 않음
         * 
         * @deprecated Socket.IO 이벤트 핸들러 사용 권장
         * 
         * 레거시 함수 주석처리 - Socket.IO 방식 사용
         */
        /*
        //방해제 처리
        function room_leave_return(ret) {
            alert("방에 접속하셨습니다.");
        }
        */


        $("#btnInerview").click(function() {
            if ($("[name='interviewDate']").val() == "") {
                commonAlert("날짜를 선택해주세요.", function() {

                });
                return;
            }

            $("#interviewForm").attr("hidden", "");
            $("#interviewShow").attr("aria-expanded", false);
            sendResume("면접일정확인", "", $("[name='interviewDate']").val().substr(0, 10));
        });

        //면접 취소
        $(document).on("click", ".bntInterviewCancel", function() {
            sendResume("개인면접취소요청", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
        });

        //면접 취소
        $(document).on("click", ".bntInterviewCompCancel", function() {
            sendResume("기업면접취소요청", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
        });

        //면접 날짜 변경
        $(document).on("click", ".bntInterviewChange", function() {
            sendResume("개인면접변경요청", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "변경요청");
        });

        //면접 날짜 변경
        $(document).on("click", ".bntInterviewChangeOK", function() {
            sendResume("개인면접변경수락", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "변경수락");
        });

        //면접 수락
        $(document).on("click", ".bntInterviewOK", function() {
            sendResume("개인면접수락", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "면접수락");
        });

        //면접 수락
        $(document).on("click", ".btnCancelOK", function() {
            sendResume("면접취소", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "면접취소");
        });


        $("#btnStartWrok").click(function() {
            if ($("[name='startDate']").val() == "") {
                commonAlert("날짜를 선택해주세요.", function() {

                });
                return;
            }

            $("#startDateForm").attr("hidden", "");
            $("#dateShow").attr("aria-expanded", false);
            sendResume("채용일정확인", "", $("[name='startDate']").val().substr(0, 10));
        });

        //면접 취소
        $(document).on("click", ".bntRecruitCancel", function() {
            sendResume("개인채용취소요청", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
        });

        //면접 취소
        $(document).on("click", ".bntRecruitCompCancel", function() {
            sendResume("기업채용취소요청", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
        });

        //면접 날짜 변경
        $(document).on("click", ".bntRecruitChange", function() {
            sendResume("개인채용변경요청", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "변경요청");
        });

        //면접 날짜 변경
        $(document).on("click", ".bntRecruitChangeOK", function() {
            sendResume("개인채용변경수락", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "변경수락");
        });

        //면접 수락
        $(document).on("click", ".bntRecruitOK", function() {
            sendResume("개인채용수락", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "채용수락");
        });

        //면접 수락
        $(document).on("click", ".btnRecruitCancelOK", function() {
            sendResume("채용취소", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "채용취소");
        });

        function changeStatus() {
            var status = $("[name=rm_status]").val();
            $("#comp_top_1").show();

            $("#mem_top_2").hide();
            $("#mem_top_3").hide();
            if (status == "개인면접수락") {
                $("#mem_top_2").show();
                $("#mem_top_3").hide();
            } else if (status == "개인채용수락") {
                $("#mem_top_3").show();
                $("#mem_top_2").hide();
            }
        }

        changeStatus();

        function sendResume(mg_type, mg_text, mg_req_dt) {
            //수락인경우 날짜 업데이트
            var rm_dt = mg_req_dt;

            if (!g_user_id) {
                g_user_id = ensureValidUserId();
                if (!g_user_id) {
                    return;
                }
            }

            if (!g_user_id) {
                g_user_id = ensureValidUserId();
                if (!g_user_id) {
                    return;
                }
            }

            comAjax("/chat_proc/set_status", {
                "rm_status": mg_type,
                "rm_idx": rm_idx,
                rm_dt: rm_dt
            }, function(ret) {

                //상태값 변경
                $("[name=rm_status]").val(mg_type);
                changeStatus();

                var message = {
                    rm_idx: Number(rm_idx),
                    user_id: g_user_id,
                    mg_type: mg_type,
                    mg_req_dt: mg_req_dt,
                    mg_text: mg_text,
                    mg_dt: getCurrentDate(),
                    mg_reads: new Array(),
                };

                //리드 카운터 초기화 한다.
                for (var icnt = 0; icnt < rm_info.rm_users.length; icnt++) {
                    if (g_user_id == rm_info.rm_users[icnt].user_id) {
                        message.mg_reads.push({
                            read_yn: "Y",
                            user_id: rm_info.rm_users[icnt].user_id
                        });
                    } else {
                        message.mg_reads.push({
                            read_yn: "N",
                            user_id: rm_info.rm_users[icnt].user_id
                        });
                    }
                }

                //채팅방 실시간 쏘기 - Socket.IO 사용
                message.backUrl = window.backUrl;
                socket.emit("message_insert", message, rm_info.rm_users);
            });
        }

        //메시지 출력 처리 함수는 공통 스크립트 영역에 정의되어 있음 (showMessage, scrollBottom)

        function setEnabledButton($this, rm_idx, mg_idx, text) {
            // Socket.IO 사용 - 메시지 상태 업데이트
            socket.emit("message_update_status", {
                rm_idx: parseInt(rm_idx),
                mg_idx: parseInt(mg_idx),
                mg_imoti: text
            });

            //상태 변경 UI 처리
            $this.closest("div").find("button").prop("disabled", true);
        }
    </script>
<?php else : ?>
    <!-- ===== iOS Safari / 모바일 웹 브라우저에서 실행 (안드로이드가 아닌 경우) ===== -->
    <script>
        // ✅ iOS 섹션 시작 시 안드로이드 섹션의 room_info_return 핸들러 제거 (중복 호출 방지)
        socket.off("room_info_return");
        
        var g_user_id = "";
        // 첫번째 메시지(초기 진입) 여부를 저장하는 변수, "N"이면 첫 메시지가 아님을 의미, "Y"로 바꿔서 이후 동작을 제어할 때 사용
        // 채팅방에서 첫 번째 메시지(최초 입장시 여부)를 판단하는 변수
        var first_yn = "N";
        var rm_info_ready = false; // 채팅방 정보 준비 상태 플래그

        // iOS Safari 또는 모바일 웹 브라우저에서 실행
        window.addEventListener('DOMContentLoaded', function(e) {
            //룸정보 가져오기.
            g_user_id = ensureValidUserId();
            if (!g_user_id) {
                return;
            }
            //alert(Number(rm_idx));//test
            //alert(g_user_id);//test

            // 채팅방에 입장할 때 서버에 현재 방번호(rm_idx)와 접속한 사용자 ID(g_user_id)를 전달하여 
            // 사용자가 해당 채팅방('room')에 조인하도록 요청하는 socket.io 메시지입니다.
            // 서버는 이 요청을 받아 사용자를 해당 채팅방에 참여시킵니다.
            socket.emit("room_join", {
                rm_idx: Number(rm_idx),
                con_user_id: g_user_id
            });
            // socket.emit("room_info") 함수는 현재 채팅방 번호(rm_idx)와 접속중인 사용자 ID(con_user_id)를 서버로 전송하여
            // 해당 채팅방의 정보를 요청하는 역할을 합니다. 서버는 이 정보를 바탕으로 방 정보를 반환합니다.
            socket.emit("room_info", {
                rm_idx: Number(rm_idx),
                con_user_id: g_user_id
            });
            //alert('room_info_call');//test

            // 전송 버튼 비활성화 (room_info_return 수신 후 활성화)
            $("#btnSend").prop("disabled", true);

            // ✅ 메시지 리스트는 room_info_return에서 조회하도록 변경 (중복 조회 방지)
            // DOMContentLoaded에서 바로 조회하면 room_info_return에서도 조회하여 중복 발생

            // iOS Safari 또는 모바일 웹 브라우저에서도 전송 버튼 클릭 시 메시지를 보낼 수 있도록 바인딩
            $("#btnSend").off("click.chatSend").on("click.chatSend", function() {
                send_message();
            });

        });

         // socket.on("room_info_return") 함수는 서버에서 방 정보를 반환받았을 때 호출되는 이벤트 핸들러입니다.
        // 이 핸들러는 서버에서 받은 방 정보(ret)를 처리하고, 필요한 경우 메시지 리스트를 조회하는 작업을 수행합니다.
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("room_info_return");
        socket.on("room_info_return", function(ret) {
            if (ret.code != "00") {
                if (typeof commonAlert === "function") {
                    commonAlert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.", function() {});
                } else {
                    alert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.");
                }
            } else {
                // ✅ 응답 검증: 이 응답이 내가 요청한 방(requested_rm_idx)에 대한 것인지 확인
                if (String(ret.data.rm_idx) !== String(requested_rm_idx)) {
                    console.warn("[room_info_return] 다른 방의 응답 무시됨. 요청: " + requested_rm_idx + ", 수신: " + ret.data.rm_idx);
                    return; // 다른 방에 대한 응답은 버림
                }

                // 채팅방 진입 시 중복 메시지 방지 배열 초기화
                resetProcessedIdx();

                // 채팅방 정보를 저장
                rm_info = ret.data;

                // 🔥 중요: 서버에서 받은 올바른 rm_idx로 업데이트 (rc_idx가 아닌 실제 rm_idx)
                if (ret.data && ret.data.rm_idx) {
                    var old_rm_idx = rm_idx; // 업데이트 전 값 저장
                    rm_idx = ret.data.rm_idx;

                }
                
                // 채팅방 정보 준비 완료 플래그 설정
                rm_info_ready = true;
                
                // 전송 버튼 활성화
                $("#btnSend").prop("disabled", false);

                //메시지 리스트 조회
                var current_g_user_id = typeof g_user_id !== 'undefined' && g_user_id ? g_user_id : $("#user_id").val();
                if (!current_g_user_id) {
                    console.log('[chat_view] ⚠️ message_list emit 직전 g_user_id 비어있음 (iOS 최초 조회) - rm_idx=' + rm_idx);
                } else {
                    console.log('[chat_view] message_list emit (iOS 최초 조회) - rm_idx=' + rm_idx + ', con_user_id=' + current_g_user_id);
                }
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: rm_start,
                    limit: rm_length,
                    con_user_id: current_g_user_id
                };
                // socket.emit("message_list") 함수는 현재 채팅방 번호(rm_idx)와 페이지 번호(skip) 및 페이지 크기(limit)를 서버로 전송하여
                // 해당 채팅방의 메시지 리스트를 요청하는 역할을 합니다. 서버는 이 정보를 바탕으로 메시지 리스트를 반환합니다.
                socket.emit("message_list", params);
                
                // 🔥 제안하기/지원하기 완료 후 자동 메시지 전송 체크
                // room_info_return에서 rm_info를 받았으므로, targetUserId를 저장하고
                // message_list_return에서 메시지가 없으면 전송하도록 함
                if (window.autoSendFirstMessage && window.autoSendFirstMessage.enabled) {
                    var autoSendInfo = window.autoSendFirstMessage;
                    var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();
                    
                    // 상대방 ID 찾기 (우선순위: 1. hidden input의 opponent_user_id, 2. rm_users에서 찾기)
                    var targetUserId = null;
                    
                    // hidden input에서 상대방 ID 가져오기 (서버에서 미리 찾아서 전달한 값)
                    var opponentUserIdFromInput = $("#opponent_user_id").val();
                    if (opponentUserIdFromInput && opponentUserIdFromInput.trim() !== "") {
                        targetUserId = opponentUserIdFromInput.trim();
                    }
                    
                    // hidden input에 없으면 rm_users에서 찾기
                    if (!targetUserId && ret.data && ret.data.rm_users && Array.isArray(ret.data.rm_users)) {
                        for (var i = 0; i < ret.data.rm_users.length; i++) {
                            if (ret.data.rm_users[i].user_id && ret.data.rm_users[i].user_id != g_user_id) {
                                targetUserId = ret.data.rm_users[i].user_id;
                                break;
                            }
                        }
                    }
                    
                    // targetUserId를 저장 (message_list_return에서 사용)
                    if (targetUserId) {
                        window.autoSendFirstMessage.targetUserId = targetUserId;
                        // 🔥 중요: rm_info도 받았음을 표시 (message_list_return에서 메시지가 없으면 바로 전송)
                        // 안드로이드와 동일하게 rm_info_received 설정 추가
                        window.autoSendFirstMessage.rm_info_received = true;
                        
                    } else {
                    }
                }
            }
        });

        //방접속시 알림
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("room_join_alarm");
        socket.on("room_join_alarm", function(ret_user_id) {
            //alert('alarm');
            // 🔥 읽음 카운트 초기화 제거: 서버에서 message_read_count_return 이벤트로 실시간 업데이트하므로
            // $(".readcount").html(""); // 제거: 이 코드가 서버에서 보낸 읽음 카운트를 덮어쓰는 문제 발생
        });

        // ✅ pagging_yn은 공통 영역에 정의됨
        $("#chat_body").scroll(function() {
            var scroll_top = $(this).scrollTop();
            if ($(this).scrollTop() < 300 && pagging_yn == false) {
                pagging_yn = true;

                //메시지 리스트 조회
                var current_g_user_id = typeof g_user_id !== 'undefined' && g_user_id ? g_user_id : $("#user_id").val();
                if (!current_g_user_id) {
                    console.log('[chat_view] ⚠️ message_list emit 직전 g_user_id 비어있음 (iOS 페이지네이션) - rm_idx=' + rm_idx);
                } else {
                    console.log('[chat_view] message_list emit (iOS 페이지네이션) - rm_idx=' + rm_idx + ', con_user_id=' + current_g_user_id);
                }
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: (++rm_start * rm_length),
                    limit: rm_length,
                    con_user_id: current_g_user_id
                };
                socket.emit("message_list", params);
            }
        });

        /////////////////////////////////////////////////////////
        // 리턴 처리
        /////////////////////////////////////////////////////////

        function send_message() {
            //android-else
            var txt = $("#chatTxt").val();
            if (txt == "") {
                return;
            }
            if (!g_user_id) {
                g_user_id = ensureValidUserId();
                if (!g_user_id) {
                    return;
                }
            }
            
            // 실무 방법: 플래그 변수로 채팅방 정보 준비 상태 확인
            var chatotherid = $('#otherid').val();
            if (!rm_info_ready || !rm_info) {
                if (typeof commonAlert === "function") {
                    commonAlert("채팅방 정보를 불러오는 중입니다. 잠시 후 다시 시도해주세요.", function() {});
                } else {
                    alert("채팅방 정보를 불러오는 중입니다. 잠시 후 다시 시도해주세요.");
                }
                return;
            }
            if (!rm_info.rm_users && !chatotherid) {
                if (typeof commonAlert === "function") {
                    commonAlert("채팅방 정보를 불러오는 중입니다. 잠시 후 다시 시도해주세요.", function() {});
                } else {
                    alert("채팅방 정보를 불러오는 중입니다. 잠시 후 다시 시도해주세요.");
                }
                return;
            }
       
            var message = {
                rm_idx: Number(rm_idx),
                user_id: g_user_id,
                mg_type: "text",
                mg_text: txt,
                mg_req_dt: "",
                mg_img: "",
                mg_file: "",
                mg_imoti: "",
                mg_dt: getCurrentDate(),
                mg_reads: new Array(),
            };

            //리드 카운터 초기화 한다.
            // rm_info.rm_users에 있는 사용자들 처리
            var found_other_user = false;
            var found_current_user = false;
            
            if (rm_info.rm_users && rm_info.rm_users.length > 0) {
                for (var icnt = 0; icnt < rm_info.rm_users.length; icnt++) {
                    if (g_user_id == rm_info.rm_users[icnt].user_id) {
                        message.mg_reads.push({
                            read_yn: "Y",
                            user_id: rm_info.rm_users[icnt].user_id
                        });
                        found_current_user = true;
                    } else {
                        message.mg_reads.push({
                            read_yn: "N",
                            user_id: rm_info.rm_users[icnt].user_id
                        });
                        // 상대방이 rm_info.rm_users에 있는지 확인
                        if (chatotherid && rm_info.rm_users[icnt].user_id == chatotherid) {
                            found_other_user = true;
                        }
                    }
                }
            }
            
            // 현재 사용자가 rm_info.rm_users에 없으면 추가
            if (!found_current_user) {
                message.mg_reads.push({
                    read_yn: "Y",
                    user_id: g_user_id
                });
            }
            
            // 상대방이 rm_info.rm_users에 없으면 직접 추가 (상대방이 아직 채팅방에 입장하지 않은 경우)
            if (chatotherid && !found_other_user) {
                message.mg_reads.push({
                    read_yn: "N",
                    user_id: chatotherid
                });
                // ✅ rm_info.rm_users에도 추가하여 서버로 전송 (서버에서 mg_reads 생성 시 사용)
                if (!rm_info.rm_users) {
                    rm_info.rm_users = [];
                }
                rm_info.rm_users.push({
                    user_id: chatotherid
                });
            }

            // ✅ 실무 방법: 서버 응답 후에만 메시지 표시 (안정적)
            // 임시 메시지 제거 - 버그 가능성 제거
            // 전송 버튼 비활성화로 중복 전송 방지
            var $sendBtn = $("#chatTxt").closest('form').find('button[type="submit"], .btn-send, #sendBtn');
            var sendBtnDisabled = false;
            if ($sendBtn.length > 0) {
                sendBtnDisabled = $sendBtn.prop('disabled');
                $sendBtn.prop('disabled', true);
            }

            //채팅방 실시간 쏘기
            message.backUrl = window.backUrl;
            socket.emit("message_insert", message, rm_info.rm_users);
            $("#chatTxt").val(""); //.focus();
            document.getElementById('chatTxt').style.height = '3rem'; // textarea 높이 초기화
            
            // 서버 응답 후 버튼 활성화는 message_insert_return 핸들러에서 처리

        }

        //메시지 읽음 카운트 리턴 처리 (안드로이드/웹/iOS 공통 핸들러)
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("message_read_count_return");
        socket.on("message_read_count_return", function(ret) {
            //리턴받은 카운트 메시지 정보 업데이트
            // ret.data 또는 ret에서 count와 mg_idx 추출
            var count = (ret.data && ret.data.count !== undefined) ? ret.data.count : (ret.count !== undefined ? ret.count : 0);
            var mg_idx = (ret.data && ret.data.mg_idx !== undefined) ? ret.data.mg_idx : (ret.mg_idx !== undefined ? ret.mg_idx : null);
            
            if (mg_idx) {
                // 특정 메시지의 읽음 카운트 업데이트
                var $msgElement = $("#mg_idx_" + mg_idx);
                if ($msgElement.length > 0) {
                    if (count == 0) {
                        $msgElement.find(".readcount").html("");
                    } else {
                        $msgElement.find(".readcount").html(count);
                    }
                } else {
                    // 메시지가 아직 DOM에 없으면 마지막 메시지 업데이트 (하위 호환성)
                    $("[id^=mg_idx_]").last().prop("id", "mg_idx_" + mg_idx);
                    if (count == 0) {
                        $("[id^=mg_idx_]").last().find(".readcount").html("");
                    } else {
                        $("[id^=mg_idx_]").last().find(".readcount").html(count);
                    }
                }
            } else {
                // mg_idx가 없으면 마지막 메시지 업데이트 (기존 로직 유지)
                if (count == 0) {
                    $("[id^=mg_idx_]").last().find(".readcount").html("");
                } else {
                    $("[id^=mg_idx_]").last().find(".readcount").html(count);
                }
            }
        });

        // ✅ message_list_return 핸들러는 공통 스크립트 영역으로 이동됨 (중복 방지)
        // ✅ message_insert_return 핸들러는 공통 스크립트 영역으로 이동됨 (중복 방지)

        //방 조인
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("room_join_return");
        socket.on("room_join_return", function(ret) {
            // 🔥 채팅방 입장 시 읽음 카운트 정보 저장 (메시지 로드 후 업데이트)
            // 서버에서 ret.list에 { _id: mg_idx, count: 읽지 않은 개수 } 형태의 배열을 보냄
            if (ret && ret.list && Array.isArray(ret.list)) {
                // 읽음 카운트 정보를 저장 (메시지 로드 후 업데이트)
                pendingReadCounts = {};
                for (var i = 0; i < ret.list.length; i++) {
                    var msgInfo = ret.list[i];
                    var mg_idx = msgInfo._id;
                    var count = msgInfo.count || 0;
                    pendingReadCounts[mg_idx] = count;
                    
                    // 메시지가 이미 DOM에 있으면 즉시 업데이트
                    updateReadCount(mg_idx, count);
                }
            }
        });

        //접속 끊길때 방 정리
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("room_leave_alarm");
        socket.on("room_leave_alarm", function(ret) {
            alert("접속이 끊겼습니다.");
            // Socket.IO 사용 - 채팅방 나가기
            socket.emit("room_leave", {
                rm_idx: Number(rm_idx)
            });
        });

        //방해제 처리
        // 중복 등록 방지: 기존 핸들러 제거 후 등록
        socket.off("room_leave_return");
        socket.on("room_leave_return", function(ret) {
            alert("방에 접속하셨습니다.");
        });

        $("#btnInerview").click(function() {
            if ($("[name='interviewDate']").val() == "") {
                commonAlert("날짜를 선택해주세요.", function() {

                });
                return;
            }

            $("#interviewForm").attr("hidden", "");
            $("#interviewShow").attr("aria-expanded", false);
            sendResume("면접일정확인", "", $("[name='interviewDate']").val().substr(0, 10));
        });

        //면접 취소
        $(document).on("click", ".bntInterviewCancel", function() {
            sendResume("개인면접취소요청", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
        });

        //면접 취소
        $(document).on("click", ".bntInterviewCompCancel", function() {
            sendResume("기업면접취소요청", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
        });

        //면접 날짜 변경
        $(document).on("click", ".bntInterviewChange", function() {
            sendResume("개인면접변경요청", "", getCurrentDate());

            //상태 변경 처리
            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "변경요청");
        });

        //면접 날짜 변경
        $(document).on("click", ".bntInterviewChangeOK", function() {
            sendResume("개인면접변경수락", "", getCurrentDate());

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "변경수락");
        });

        //면접 수락
        $(document).on("click", ".bntInterviewOK", function() {
            sendResume("개인면접수락", "", getCurrentDate());

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "면접수락");
        });

        //면접 수락
        $(document).on("click", ".btnCancelOK", function() {
            sendResume("면접취소", "", getCurrentDate());

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "면접취소");
        });


        $("#btnStartWrok").click(function() {
            if ($("[name='startDate']").val() == "") {
                commonAlert("날짜를 선택해주세요.", function() {

                });
                return;
            }

            $("#startDateForm").attr("hidden", "");
            $("#dateShow").attr("aria-expanded", false);
            sendResume("채용일정확인", "", $("[name='startDate']").val().substr(0, 10));
        });

        //면접 취소
        $(document).on("click", ".bntRecruitCancel", function() {
            sendResume("개인채용취소요청", "", getCurrentDate());

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
        });

        //면접 취소
        $(document).on("click", ".bntRecruitCompCancel", function() {
            sendResume("기업채용취소요청", "", getCurrentDate());

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "취소요청");
        });

        //면접 날짜 변경
        $(document).on("click", ".bntRecruitChange", function() {
            sendResume("개인채용변경요청", "", getCurrentDate());

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "변경요청");
        });

        //면접 날짜 변경
        $(document).on("click", ".bntRecruitChangeOK", function() {
            sendResume("개인채용변경수락", "", getCurrentDate());

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "변경수락");
        });

        //면접 수락
        $(document).on("click", ".bntRecruitOK", function() {
            sendResume("개인채용수락", "", getCurrentDate());

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "채용수락");
        });

        //면접 수락
        $(document).on("click", ".btnRecruitCancelOK", function() {
            sendResume("채용취소", "", getCurrentDate());

            var mg_idx = $(this).attr("data-mg_idx");
            setEnabledButton($(this), rm_idx, mg_idx, "채용취소");
        });

        function changeStatus() {
            var status = $("[name=rm_status]").val();
            $("#comp_top_1").show();

            $("#mem_top_2").hide();
            $("#mem_top_3").hide();
            if (status == "개인면접수락") {
                $("#mem_top_2").show();
                $("#mem_top_3").hide();
            } else if (status == "개인채용수락") {
                $("#mem_top_3").show();
                $("#mem_top_2").hide();
            }
        }

        changeStatus();

        function sendResume(mg_type, mg_text, mg_req_dt) {
            //수락인경우 날짜 업데이트
            var rm_dt = mg_req_dt;

            comAjax("/chat_proc/set_status", {
                "rm_status": mg_type,
                "rm_idx": rm_idx,
                rm_dt: rm_dt
            }, function(ret) {

                //상태값 변경
                $("[name=rm_status]").val(mg_type);
                changeStatus();

                var message = {
                    rm_idx: Number(rm_idx),
                    user_id: g_user_id,
                    mg_type: mg_type,
                    mg_req_dt: mg_req_dt,
                    mg_text: mg_text,
                    mg_dt: getCurrentDate(),
                    mg_reads: new Array(),
                };

                //리드 카운터 초기화 한다.
                for (var icnt = 0; icnt < rm_info.rm_users.length; icnt++) {
                    if (g_user_id == rm_info.rm_users[icnt].user_id) {
                        message.mg_reads.push({
                            read_yn: "Y",
                            user_id: rm_info.rm_users[icnt].user_id
                        });
                    } else {
                        message.mg_reads.push({
                            read_yn: "N",
                            user_id: rm_info.rm_users[icnt].user_id
                        });
                    }
                }

                //채팅방 실시간 쏘기
                message.backUrl = window.backUrl;
                socket.emit("message_insert", message, rm_info.rm_users);
            });
        }

        //메시지 출력 처리 함수는 공통 스크립트 영역에 정의되어 있음 (showMessage, scrollBottom)

        function setEnabledButton($this, rm_idx, mg_idx, text) {
            socket.emit("message_update_status", {
                rm_idx: parseInt(rm_idx),
                mg_idx: parseInt(mg_idx),
                mg_imoti: text
            });

            //상태 변경 UI 처리
            $this.closest("div").find("button").prop("disabled", true);
        }

        // 전화하기 버튼 실시간 반영 (개인회원이 일반 메시지를 보냈을 때)
        var originalShowMessage = showMessage;
        showMessage = function(data, messageList) {
            originalShowMessage.call(this, data, messageList);

            var individualUserId = $("#individual_user_id").val();
            // chat_type이 없거나 빈 문자열(recruit이 아님), text 메시지, 개인회원이 보낸 경우만
            if (individualUserId && data && !data.chat_type && data.mg_type === "text" && String(data.user_id) === String(individualUserId)) {
                $(".btn-call").attr("data-show-call", "true");
            }
        };

    </script>
<?php endif; ?>

<style>
    [class*="recruitbox_"] .content-body .desc .title.ellipsis, [class*="resumebox_"] .content-body .desc .title.ellipsis {
            max-width: 240px;
    }
    /* 제안/지원 안내 말풍선 클릭 팝업 (hr_list.php/job_list.php와 동일 패턴) */
    #hrPostPopup,
    #jobPostPopup {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: #fff;
        overflow-y: auto;
    }

    #hrPostPopup[data-state="closed"],
    #jobPostPopup[data-state="closed"] {
        transform: translateX(100%);
        pointer-events: none;
    }

    #hrPostPopup[data-state="open"],
    #jobPostPopup[data-state="open"] {
        transform: none;
        pointer-events: auto;
    }

    .propose-bubble {
        cursor: pointer;
    }

    .btn-propose-view {
        display: block;
        margin-top: 8px;
        padding: 6px 14px;
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.15);
        color: inherit;
        font-size: 1.2rem;
        font-weight: 600;
        line-height: 1.4;
        cursor: pointer;
        pointer-events: none; /* 클릭 이벤트는 상위 .propose-bubble에서 처리, 버튼은 시각적 안내용 */
    }

    .chat-bubble.diff .btn-propose-view {
        border-color: #cfcfcf;
        background: #f5f5f5;
        color: #333;
    }

    #chatRecruitCardList .content-li {
        cursor: pointer;
    }

    #chatResumeList .content-li {
        cursor: pointer;
    }

    .btn-call {
        display: none;
    }

    .btn-call[data-show-call="true"] {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            cursor: pointer;
            color: #333;
            text-decoration: none;
            top: 630px;
            position: absolute;
            right: 13px;
            width: 50px;
            height: 50px;
            
            padding: 8px;
            border-radius: 50%;
    }

    .btn-call img {
        width: 28px;
        height: 28px;
        object-fit: contain;
    }

    .btn-call i {
        font-size: 18px;
    }

    .btn-call .sr-text {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
    }

    .btn-attach-toggle {
        flex: 0 0 3rem;
        
        height: 3rem;
        padding: 0;
        border: none;
        background: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .btn-attach-toggle svg {
        width: 100%;
        height: 100%;
    }

    .btn-attach-toggle .icon-close {
        display: none;
    }

    .btn-attach-toggle[data-state="open"] .icon-add {
        display: none;
    }

    .btn-attach-toggle[data-state="open"] .icon-close {
        display: inline;
    }

    .attach-menu {
        display: none;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        padding: 1rem 0.875rem;
        background: #fff;
        position: sticky;
        bottom: 5.75rem;
        z-index: 9;
        border-top: 1px solid #eee;
            width: 288px;
        margin-bottom: 10px;
        margin-left: 26px;
        border-radius: 13px;
    }

    .attach-menu[data-state="open"] {
        display: grid;
    }

    .attach-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.375rem;
        background: none;
        border: none;
        padding: 0;
        font-size: 0.75rem;
        color: #333;
        cursor: pointer;
    }

    .attach-item svg {
        width: 2.875rem;
        height: 2.875rem;
    }

    .chat-bubble-img .bubble-content.chat-img-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.25rem;
        background: transparent;
    }
    .chat-img {
        max-width: 200px;
        max-height: 260px;
        border-radius: 10px;
        display: block;
        cursor: pointer;
        object-fit: cover;
    }

    .chat-bubble-file .bubble-content.chat-files {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .chat-bubble-file .chat-file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        max-width: 14rem;
        cursor: pointer;
    }
    .chat-file-icon {
        flex-shrink: 0;
        width: 1.75rem;
        height: 1.75rem;
        background: rgba(255,255,255,0.25);
        border-radius: 4px;
    }
    .chat-bubble-file.diff .chat-file-icon {
        background: #eee;
    }
    .chat-file-name {
        font-size: 0.8125rem;
        word-break: break-all;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        flex: 1;
    }

    .chat-file-open-btn {
        flex-shrink: 0;
        padding: 0.35rem 0.6rem;
        font-size: 0.75rem;
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 3px;
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.9);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .chat-file-open-btn:active {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.6);
    }
    .chat-bubble-file.diff .chat-file-open-btn {
        border-color: #ccc;
        background: #f5f5f5;
        color: #333;
    }
    .chat-bubble-file.diff .chat-file-open-btn:active {
        background: #eee;
        border-color: #999;
    }

    /* 사진 저장 방식 선택 바텀시트 스타일 */
    #chatImgSaveSheet .bottom-sheet-header,
    #chatImgSaveSheet .bottom-sheet-body {
        display: none;
    }
    #chatImgSaveSheet .bottom-sheet-actions--split {
        flex-direction: column;
        gap: 0.75rem;
    }
   #chatImgSaveSheet #btnSaveAllImg {
    background-color: #57CCAF;
}
    #chatImgSaveSheet .btn-bottom-sheet{
        width: 100%;
        font-size: 23px;
    padding: 25px;
    }
    #chatImgSaveSheet .btn-bottom-sheet-secondary {
        background-color: #1B9D7D;
        color: #fff;
        border: none;

    }
    #chatImgSaveSheet .btn-bottom-sheet-secondary:active,
    #chatImgSaveSheet .btn-bottom-sheet-secondary:hover {
        background-color: #158a6f;
    }

    .chat-lightbox {
        position: fixed;
        inset: 0;
        z-index: 100;
        background: rgba(0,0,0,0.85);
        flex-direction: column;
        gap: 0.75rem;
        display: flex;
    }
    .chat-lightbox-main {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
        min-height: 0;
    }
    .chat-lightbox-main img {
        max-width: 92vw;
        max-height: 70vh;
        object-fit: contain;
        transform-origin: center center;
        transition: none;
        will-change: transform;
        cursor: zoom-in;
    }
    .chat-lightbox-main img.pinching {
        cursor: grabbing;
    }
    .chat-lightbox-thumbs {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding: 0 1rem;
        max-width: 100vw;
        justify-content: center;
    }
    .chat-lightbox-thumb {
        width: 3rem;
        height: 3rem;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
        opacity: 0.5;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .chat-lightbox-thumb.active {
        opacity: 1;
        border-color: #1a9e7c;
    }
    .chat-lightbox-counter {
        color: #fff;
        font-size: 0.8125rem;
        text-align: center;
        margin: 0;
    }
    .chat-lightbox-download-arrow {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        cursor: pointer;
    }

    .btn-leave-list {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        color: #fff;
        font-size: 1.1rem;
        cursor: pointer;
        border: none;
        background: none;
        padding: 0;
        text-decoration: none;
    }

    .chat-view-header {
     
        border-bottom: none;
    }
    .chat-view-header .result-list-card{
          padding: 0;

    }
    .chat-view-header .result-list-card[data-visible="false"] {
        display: none;
    }
    .chat-view-header .result-list-card .list-item {
        height:  100%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        transition: box-shadow 0.3s ease;
    }

    .chat-view-header .result-list-card .list-item:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }
    .chat-view-header .result-list-card .list-item .content-li {
         height: auto;
         border-radius: unset;
         background: #ccc;
    }
    .chat-view-header .result-list-card .content-info .content-body{
        padding: 0;
        min-height: unset;
        height: unset;
    }
    .result-list-card .content-info .desc{
        height: 105%;
    padding-right:0;
    width: 100%;
    }
      .result-list-card .content-info .desc .ellipsis,
   .result-list-card .data-label{
    padding-left: 15px;
  }

</style>