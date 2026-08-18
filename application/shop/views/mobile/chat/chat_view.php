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

<script>
    // 뒤로/나가기 버튼 대상 페이지 결정
    (function() {
        
        var params = new URLSearchParams(window.location.search);
        var from = params.get('from') || '';
        var backMap = {
            applied: '/chat/chat_list', // 지원한 공고
            proposed_job: '/chat/chat_list2', // 제안받은 공고
            talent_received: '/chat/index_comp', // 제안받은 인재
            talent_sent: '/chat/chat_list3', // 제안보낸 인재
            hr_list: '/chat/index_comp' // 인재 리스트
        };

        var backUrl = backMap[from] || '/chat/chat_list';

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
                location.href = a.dataset.backUrl || backUrl;
                
                return false;
            }

            document.querySelectorAll('.js-chat-back').forEach(function(a) {
                a.dataset.backUrl = backUrl;
                
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
  // 채팅 뷰의 인재 카드 렌더링 함수
  (function() {
    function renderChatResumeCard() {
      var tpl = document.getElementById('tpl_chat_resume');
      var listContainer = document.getElementById('chatResumeList');

      if (!tpl || !listContainer || typeof chatResumeData === 'undefined') {
        return;
      }

      var row = chatResumeData;
      var $c = tpl.innerHTML;

      $c = $c.replaceAll('{{ru_idx}}',        row.ru_idx        || '');
      $c = $c.replaceAll('{{mm_idx}}',        row.mm_idx        || '');
      $c = $c.replaceAll('{{ru_resume_img}}', row.ru_resume_img || '');
      $c = $c.replaceAll('{{book_yn}}',       row.book_yn       || '');
      $c = $c.replaceAll('{{ru_work_img}}',   row.ru_work_img   || '');
      $c = $c.replaceAll('{{ru_recom_badge}}', row.ru_recom_badge || '');
      $c = $c.replaceAll('{{mm_name}}',       row.mm_name       || '');
      $c = $c.replaceAll('{{ru_week}}',       row.ru_week       || '');
      $c = $c.replaceAll('{{mm_nation}}',     row.mm_nation     || '');
      $c = $c.replaceAll('{{ma_age}}',        row.ma_age        || '');
      $c = $c.replaceAll('{{ru_price_type}}', row.ru_price_type || '');
      $c = $c.replaceAll('{{ru_work_type}}',  row.ru_work_type  || '');
      $c = $c.replaceAll('{{ad_addr}}',       row.ad_addr       || '');

      listContainer.innerHTML = $c;
    }

    // DOM이 준비되면 렌더링
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', renderChatResumeCard);
    } else {
      renderChatResumeCard();
    }
  })();

  // MEM(개인) 사용자 - recruit_card_template.php 렌더링
  (function() {
    var recruitData = <?= isset($recruit) && !empty($recruit) ? json_encode($recruit, JSON_UNESCAPED_UNICODE) : 'null' ?>;

    if (!recruitData) {
      return; // recruit 데이터가 없으면 렌더링하지 않음
    }

    function renderRecruitCard() {
      var template = document.getElementById('tpl_chat_recruit');
      if (!template) {
        return;
      }

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
    }

    // DOM이 준비되면 렌더링
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', renderRecruitCard);
    } else {
      renderRecruitCard();
    }
  })();
</script>

<div class="page page-chat-view">
    <div class="top-bar top-bar--chat">
        <!--<a href="javascript:_replace('/chat/chat_list');" class="btn-replace"><span class="sr-text">이전으로</span></a>-->
        <a href="javascript:void(0);" class="btn-replace js-chat-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title" id="rm_subject" style="display: flex; align-items: center; gap: 0.7rem; font-size: 1.1rem;">
          <?php if ($user_type == "COMP"): ?>
            <?php
            $profileImg = getResumeImg($resume);
            $mmSex = isset($resume["mm_sex"]) ? trim($resume["mm_sex"]) : "";
            $defaultAvatar = "/resource/shop/web/img/avatar_resume_w.png";
            if ($mmSex === "남") {
              $defaultAvatar = "/resource/shop/web/img/avatar_icon2_m.png";
            }
            ?>
            <div class="img" style="width: 2.5rem; height: 2.5rem; border-radius: 60px; overflow: hidden; flex-shrink: 0;">
              <img src="<?= $profileImg ?>" alt="프로필" style="width: 100%; height: 100%; object-fit: cover;" onerror="if(this.src.indexOf('avatar_icon2_m.png')===-1 && this.src.indexOf('avatar_resume_w.png')===-1){this.onerror=null; this.src='<?= $defaultAvatar ?>';}">
            </div>
            <span><?= !empty($resume["ru_name"]) ? $resume["ru_name"] : $resume["mm_name"] ?></span>
          <?php else: ?>
            <div class="img" style="width: 2.5rem; height: 2.5rem; border-radius: 50%; overflow: hidden; flex-shrink: 0;">
              <img src="<?= !empty($recruit["rc_thumb"]) ? $recruit["rc_thumb"] : "" ?>" alt="공고이미지" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
            </div>
            <span><?= !empty($recruit["rc_name"]) ? $recruit["rc_name"] : "1:1채팅" ?></span>
          <?php endif; ?>
        </h1>
        <?php if (!empty($call_target_phone)) : ?>
            <a href="tel:<?= htmlspecialchars($call_target_phone, ENT_QUOTES, 'UTF-8') ?>" class="btn-call js-call-log" data-show-call="<?= !empty($show_call_btn) ? 'true' : 'false' ?>" style="background-image: url('/resource/shop/mobile/img/phone.png'); background-size: contain; background-repeat: no-repeat; background-position: center;">
                <span class="sr-text">전화하기</span>
            </a>
        <?php endif; ?>
        <!-- [임시 주석처리: 나중에 다시 사용 예정] 채팅방 나가기 버튼 -->
        <!--
        <a href="javascript:void(0);" class="btn-exit js-chat-back" aria-label="채팅방 나가기">
            <img src="/resource/shop/mobile/img/chat/rm_exit_btn.svg" alt="채팅방 나가기">
        </a>
        -->
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="chat-view-header">
            <!-- 채용정보 -->
            <?php if ($user_type == "COMP") : ?>
                <!-- 채용정보 -->
                <input type="hidden" id="otherid" value="<?= $recruit["mm_id"] ?>">
                <?php
                $roomType = isset($room["rm_type"]) ? trim($room["rm_type"]) : "";
                $tpl_data_label = ($roomType === "propose") ? "채팅보낸 공고" : "지원받은 공고";
                ?>
                <?php $tpl_id = 'tpl_chat_recruit'; $tpl_simple_mode = true; ?>
                <?php include(APPPATH . 'views/mobile/_partial/recruit_card_template.php'); ?>
                <ul class="result-list result-list-card" id="chatRecruitCardList"></ul>
                <!-- //채용정보 -->

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
                <!-- 인재정보 -->
                <input type="hidden" id="otherid" value="<?= $resume["mm_id"] ?>">
                <?php
                // 이름 정규화: ru_name이 없으면 mm_name 사용
                $chatRow = $resume;
                if (empty($chatRow['ru_name'])) {
                    $chatRow['ru_name'] = isset($chatRow['mm_name']) ? $chatRow['mm_name'] : '';
                }

                // 나이 계산 (resume_card.php와 동일한 로직)
                $_chat_age = '';
                if (!empty($chatRow['mm_birth'])) {
                    $_chat_age = getKoreanAge($chatRow['mm_birth']);
                } else {
                    $_chat_age = isset($chatRow['ma_age']) ? $chatRow['ma_age'] : '';
                }
                ?>
                <!-- 채팅 인재 카드 데이터를 JS에 전달 -->
                <script>
                  var chatResumeData = <?php
                      // resume_proc.php와 동일한 변환 적용
                      $ru_work_type_html = getWorkTypeWithColor(isset($chatRow['ru_work_type']) ? $chatRow['ru_work_type'] : '');
                      $ru_price_type_html = getPriceType(isset($chatRow['ru_price_type']) ? $chatRow['ru_price_type'] : '');

                      echo json_encode([
                          'ru_idx'        => isset($chatRow['ru_idx']) ? $chatRow['ru_idx'] : '',
                          'mm_idx'        => isset($chatRow['mm_idx']) ? $chatRow['mm_idx'] : '',
                          'ru_resume_img' => getResumeImg($chatRow),
                          'ru_work_img'   => getWorkingImg(isset($chatRow['ru_work']) ? $chatRow['ru_work'] : ''),
                          'mm_name'       => isset($chatRow['ru_name']) ? $chatRow['ru_name'] : '',
                          'ru_week'       => getWeekYoil(isset($chatRow['ru_week']) ? $chatRow['ru_week'] : ''),
                          'mm_nation'     => isset($chatRow['mm_nation']) && !empty($chatRow['mm_nation']) ? $chatRow['mm_nation'] : '',
                          'ma_age'        => ($_chat_age !== '' && $_chat_age != 0) ? $_chat_age . '대' : '',
                          'ru_price_type' => $ru_price_type_html,
                          'ru_work_type'  => $ru_work_type_html,
                          'ad_addr'       => fRemoveBigAdr(isset($chatRow['ad_addr']) ? $chatRow['ad_addr'] : ''),
                          'book_yn'       => (isset($chatRow['book_yn']) && $chatRow['book_yn'] == 'Y') ? 'on' : '',
                          'ru_recom_badge' => getRecomBadgeHtml(isset($chatRow['pm_idx']) ? $chatRow['pm_idx'] : null),
                          'pm_idx' => isset($chatRow['pm_idx']) ? $chatRow['pm_idx'] : null,
                      ]);
                  ?>;
                </script>

                <!-- 인재 카드 템플릿 포함 -->
                <?php
                  $roomType = isset($room["rm_type"]) ? trim($room["rm_type"]) : "";
                  $tpl_data_label = ($roomType === "propose") ? "제안받은 인재" : "지원한 이력서";
                ?>
                <?php
                  $tpl_id           = 'tpl_chat_resume';
                  $tpl_recomm_class = true;
                  $tpl_show_mm_idx  = false;
                  $tpl_simple_mode  = true;
                  include(APPPATH . 'views/mobile/_partial/resume_card_template.php');
                ?>

                <!-- 채팅 인재 카드 렌더링 컨테이너 -->
                <ul class="result-list result-list-card" id="chatResumeList"></ul>
                <!-- //인재정보 -->
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
                <div class="container" id="chat_list">

                </div>
            </div>
            <!-- 입력폼 -->
            <div class="write-form">
                <label>
                    <span class="sr-text">메세지입력</span>
                    <input type="text" id="chatTxt" name="chatTxt" autocorrect="off" autocapitalize="off" autocomplete="off" style="text-align:left;" placeholder="메세지를 입력해주세요.">
                </label>
                <button type="button" id="btnSend" class="btn-primary">전송</button>
            </div>
        </div>

    </div><!-- //page-content -->
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
                              $roomType = isset($room["rm_type"]) ? trim($room["rm_type"]) : "";
                              if ($roomType === "apply") {
                                echo "지원한 이력서";
                              } elseif ($roomType === "propose") {
                                echo "제안받은 이력서";
                              }
                              ?>
                            </p>
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

    var last_send_dt = "";
    var rm_idx = "<?= $rm_idx ?>";
    var rm_info = {};
    var rm_start = 0;
    var rm_length = 20;
    var rm_count = 0;
    var userIdAlertShown = false;

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
                    // 키보드가 열려 있음: 실제 보이는 영역에 맞춰 높이 강제 설정
                    pageChatView.style.height = currentVisualHeight + 'px';
                    pageChatView.style.overflowY = 'hidden';

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

            // 직접 스크롤 컨테이너 제어
            var chatBody = document.getElementById('chat_body');
            if (chatBody) {
                chatBody.scrollTop = chatBody.scrollHeight; // 스크롤 위치를 아래로 이동
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

        // 채팅 뷰 헤더의 채용정보/인재정보 카드 클릭 이벤트 처리
        // 링크나 버튼을 클릭한 경우는 기본 동작 유지, 그 외 영역 클릭 시 상세페이지로 이동
        $(document).on('click', '.chat-view-header .result-list-card .content-li', function(e) {
            // 링크나 버튼을 클릭한 경우는 기본 동작 유지
            if ($(e.target).is('a') || $(e.target).closest('a').length > 0 || 
                $(e.target).is('button') || $(e.target).closest('button').length > 0) {
                return;
            }

            // 인재정보 카드인 경우 (data-ru_idx가 있으면)
            var ru_idx = $(this).attr('data-ru_idx');
            if (ru_idx) {
                window.location.href = '/resume/hr_post/' + ru_idx;
                return;
            }

            // 채용정보 카드인 경우 (data-rc_idx가 있으면)
            var rc_idx = $(this).attr('data-rc_idx');
            if (rc_idx) {
                window.location.href = '/recruit/job_post/' + rc_idx;
                return;
            }
        });
    });

    // ✅ 중복 메시지 방지는 DOM 체크로 처리 (processedIdx 제거)
    // DOM에 이미 있는 메시지는 showMessage에서 자동으로 건너뛰기
    
    // 🔥 채팅방 입장 시 읽음 카운트 정보 저장 (room_join_return에서 받은 정보)
    let pendingReadCounts = {};

    // 제안하기/지원하기 완료 후 자동 메시지 전송 플래그 (URL 파라미터 기반)
    var urlParams = new URLSearchParams(window.location.search);
    var autoSend = urlParams.get('auto_send') === 'true';
    var autoSendRcIdx = urlParams.get('rc_idx');
    window.autoSendFirstMessage = autoSend ? {
        enabled: true,
        rm_idx: rm_idx,
        rc_idx: autoSendRcIdx,
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
        
        // 🔥 rm_info에서 올바른 rm_idx 가져오기 (room_info_return에서 받은 값 우선 사용)
        var actualRmIdx = targetRmIdx;
        if (typeof rm_info !== 'undefined' && rm_info && rm_info.rm_idx) {
            actualRmIdx = rm_info.rm_idx;
        }
        
        // 사용자 타입에 따른 메시지 내용 결정
        var current_user_type = typeof user_type !== 'undefined' ? user_type : $("[name='user_type']").val();
        var messageText = "";
        
        if (current_user_type === "COMP") {
            // 기업이 개인에게 제안보낼 경우
            messageText = "안녕하세요.\n채용중인 일자리를 제안드립니다.";
        } else {
            // 개인이 기업에게 지원할 경우
            messageText = "안녕하세요.\n채용공고에 이력서를 지원합니다.";
        }
        
        // 첫 메시지 생성
        var firstMessage = {
            rm_idx: Number(actualRmIdx),
            user_id: g_user_id,
            chat_type: "recruit",
            mg_type: "text",
            mg_text: messageText,
            mg_req_dt: "",
            mg_img: "",
            mg_file: "",
            mg_imoti: "",
            mg_dt: getCurrentDate(),
            mg_reads: []
        };
        
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

    function showMessage(info, is_prepend) {
        var mg_idx = info.mg_idx || 'unknown';

        // ✅ 중복 메시지 체크: DOM에 이미 있으면 건너뛰기 (단순하고 확실한 방법)
        if ($('#chat_list #mg_idx_' + mg_idx).length > 0) {
            return;
        }

        //유형에 따른 처리
        if (info.mg_type == "timeline") {
            var content = "<p class='chat-divider'>";
            content += "         " + getFormatDate(new Date(info.mg_dt), 'kor');
            content += "</p>";
        } else {
            var your_text_yn = (info.user_id == g_user_id ? "Y" : "N");
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
            } else if (info.mg_type == "file") {

            } else if (info.mg_type == "img") {

            } else if (info.mg_type == "system_exit") {
                // 시스템 메시지: 채팅방 나가기 알림
                var content = "<p class='msg-item chat-notify' data-info='" + item + "'>";
                content += info.mg_text;
                content += "</p>";
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
        requestAnimationFrame(function() {
            var $chatBody = $("#chat_body");
            if ($chatBody.length > 0 && $chatBody[0]) {
                $chatBody[0].scrollTop = $chatBody[0].scrollHeight;
            }
        });
    }

    if (rm_idx == null || rm_idx == "") {
        commonAlert("잘못된 접근입니다.", function() {
            location.href = "/chat/chat_list";
        });
    }

    $("#chatTxt").keyup(function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            send_message();
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

            // 안드로이드에서도 소켓을 사용하여 룸 정보 조회
            // socket.emit("room_join", ...)와 socket.emit("room_info", ...)는 기능이 다르다.
            // 1. room_join:
            //    - 사용자를 특정 채팅방(room)에 참여(join)시키는 역할.
            //    - 소켓 서버가 해당 사용자를 해당 채팅방의 멤버로 추가하여
            //      이후 실시간 메시지, 알림 등 이벤트를 해당 room에서 받을 수 있게 함.
            socket.emit("room_join", {
                rm_idx: Number(rm_idx),
                con_user_id: g_user_id
            });

            // 2. room_info:
            //    - 채팅방의 상세 정보(예: 방 제목, 참여자, 설정 등)를 요청하는 역할.
            //    - 서버에 해당 room의 정보를 요청하면 서버가 정보를 반환(room_info_return 이벤트로)함.
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
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: rm_start,
                    limit: rm_length
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
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: (++rm_start * rm_length),
                    limit: rm_length
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
            socket.emit("message_insert", message, rm_info.rm_users);

            //채팅창 클리어
            $("#chatTxt").val(""); //.focus();
            
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
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: rm_start,
                    limit: rm_length
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
                var params = {
                    rm_idx: Number(rm_idx),
                    skip: (++rm_start * rm_length),
                    limit: rm_length
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
            socket.emit("message_insert", message, rm_info.rm_users);
            $("#chatTxt").val(""); //.focus();
            
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

    .chat-view-header {
        height: 120px;
        border-bottom: none;
    }
    .chat-view-header .result-list-card{
          

    }
    .chat-view-header .result-list-card .list-item {
        height: 50%;
    }
    .chat-view-header .result-list-card .list-item .content-li {
        height: 100%;
    }
    .chat-view-header .result-list-card .content-info .content-body{
        height: 118px;
    }
    .result-list-card .content-info .desc{
        height: 98%;
    padding-right:0;
    }
      .result-list-card .content-info .desc .ellipsis,
   .result-list-card .data-label{
    padding-left: 15px;
  }
</style>