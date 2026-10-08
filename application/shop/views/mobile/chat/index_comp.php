<?php
$top_bar = array(
    'title' => '채팅방'
);
?>
<div class="page page-chat">
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/top_bar.php'); ?>
  <div class="page-content">
    <style>
      html,
      body {
        overflow: auto;
        /* 스크롤 허용 */
        -webkit-overflow-scrolling: touch;
        /* 부드러운 스크롤 */
      }

      #app {
        overflow: auto;
        /* 스크롤 허용 */
        -webkit-overflow-scrolling: touch;
        /* 부드러운 스크롤 */
      }

    </style>
    <div class="tab-panel">
      <div class="container" style="padding: 0;">
        <!-- 통합 채팅목록 (기업회원용) -->
        <h2 class="sr-text">채팅목록</h2>
        <div class="chat-list">
          <ol class="list result-list-card" id="chat_list">
          </ol>

          <div class="chat-empty" id="chat_empty1">
            <p>목록이 없습니다.</p>
          </div>

        </div>

      </div>
    </div>

  </div><!-- //page-content -->

  <!-- bottom-nav -->
  <?php $select_menu = "chat"; ?>
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
  <!-- //bottom-nav -->
</div>

<script>
  var chat_point = "<?= $point_info["cd_val"] ?>";

  // 중복 호출 방지 플래그
  var isLoadingChatList = false;

  // 목록 행의 마지막 메시지 시각(원본 Y-m-d H:i:s) 구하기
  // - 정식 행: 서버가 내려준 last_msg_dt (up_dt는 "오후 3:12" 같은 표시용 문자열, reg_dt는 이력서 등록일이라 사용 불가)
  // - 다이렉트 행('채팅방'): last_msg_time(원본), 메시지가 없으면 방 갱신일(reg_dt)
  function getChatListDateTime(row) {
    if (row.last_msg_dt) return row.last_msg_dt;
    if (row.rc_name === '채팅방') return toChatListDateTime(row.last_msg_time) || toChatListDateTime(row.reg_dt);
    // last_msg_dt가 없는 경우(구버전 응답) 표시용 문자열을 원본 시각으로 복원
    return toChatListDateTime(row.last_msg_time) || toChatListDateTime(row.up_dt);
  }

  // 시각 문자열을 "YYYY-MM-DD HH:MM:SS"로 변환 ("오전/오후 h:mm" → 오늘, "어제" → 어제 날짜, "YYYY-MM-DD ..." → 그대로)
  function toChatListDateTime(value) {
    if (!value) return "";
    var str = String(value).trim();
    var pad = function(n) { return (n < 10 ? "0" : "") + n; };
    var ymd = function(d) { return d.getFullYear() + "-" + pad(d.getMonth() + 1) + "-" + pad(d.getDate()); };
    var m = str.match(/^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::(\d{2}))?)?/);
    if (m && m[1] === "0000") return ""; // MySQL 빈 날짜(0000-00-00)는 값 없음으로 처리
    if (m) return m[1] + "-" + m[2] + "-" + m[3] + " " + (m[4] || "00") + ":" + (m[5] || "00") + ":" + (m[6] || "00");
    var t = str.match(/^(오전|오후)\s*(\d{1,2}):(\d{2})/);
    if (t) {
      var hour = parseInt(t[2], 10) % 12 + (t[1] === "오후" ? 12 : 0);
      return ymd(new Date()) + " " + pad(hour) + ":" + t[3] + ":00";
    }
    if (str === "어제") return ymd(new Date(Date.now() - 86400000)) + " 00:00:00";
    return "";
  }

  // 목록 시간 표시: 시간 없이 날짜만 "YYYY-MM-DD"
  function formatChatListTime(dt) {
    if (!dt) return "";
    var m = String(dt).match(/^(\d{4})-(\d{2})-(\d{2})/);
    return m ? (m[1] + "-" + m[2] + "-" + m[3]) : "";
  }

  // 통합 채팅 목록 조회 함수 (기업회원 - 4개 데이터소스 병렬 호출)
  function getChatTargetList(arg) {
    // 이미 로딩 중이면 중복 요청 방지
    if (isLoadingChatList) {
      return;
    }

    isLoadingChatList = true;

    // Promise 1: 제안보낸인재 공고 목록
    var promise1 = $.ajax({
      url: "/chat_proc/get_chat_target_list_recruit",
      type: "POST",
      dataType: "json"
    }).then(function(data) {
      return { source: 'talent_sent', data: data };
    });

    // Promise 2: 지원받은인재 공고 목록
    var promise2 = $.ajax({
      url: "/chat_proc/get_chat_target_list_recruit2",
      type: "POST",
      dataType: "json"
    }).then(function(data) {
      return { source: 'talent_received', data: data };
    });

    // Promise 3: 보낸 다이렉트채팅
    var promise3 = $.ajax({
      url: "/chat_proc/get_direct_chat_list_sent_comp",
      type: "POST",
      dataType: "json"
    }).then(function(data) {
      return { source: 'direct_sent', data: data };
    });

    // Promise 4: 받은 다이렉트채팅
    var promise4 = $.ajax({
      url: "/chat_proc/get_direct_chat_list_received_comp",
      type: "POST",
      dataType: "json"
    }).then(function(data) {
      return { source: 'direct_received', data: data };
    });

    $.when(promise1, promise2, promise3, promise4).done(function(result1, result2, result3, result4) {
      // 4개 응답을 처리하여 통합 배열 생성
      var allRows = [];

      // Promise 1, 2: 제안보낸인재, 지원받은인재는 공고 목록을 먼저 받으므로, 각 공고별로 지원자 목록을 조회해야 함
      var processRecruitList = function(recruitList, chatgbn) {
        if (!recruitList || recruitList.length === 0) {
          return $.Deferred().resolve([]);
        }

        // 공고 idx 배열 생성
        var ru_idx_list = recruitList.map(function(r) { return r.rc_idx; });

        return $.ajax({
          type: "POST",
          url: "/chat_proc/get_chat_target_list_batch",
          data: {
            ru_idx_list: ru_idx_list,
            chatgbn: chatgbn
          }
        }).then(function(response) {
          if (response && response.code == "00" && response.data && response.data.list) {
            return response.data.list;
          }
          return [];
        });
      };

      // Promise 1과 2 처리
      var talentSentList = result1.data && result1.data.code == "00" ? result1.data.data.list : [];
      var talentReceivedList = result2.data && result2.data.code == "00" ? result2.data.data.list : [];

 

      $.when(
        processRecruitList(talentSentList, 'chatPanel1'),
        processRecruitList(talentReceivedList, 'chatPanel2')
      ).done(function(sentRows, receivedRows) {
        // 정식 지원/제안 목록 통합
    

        allRows = allRows.concat(sentRows || []);
        allRows = allRows.concat(receivedRows || []);

        // 다이렉트채팅 추가
        var sent_direct = 0, received_direct = 0;
        if (result3.data && result3.data.code == "00" && result3.data.data && result3.data.data.list) {
          sent_direct = result3.data.data.list.length;
          allRows = allRows.concat(result3.data.data.list);
        }
        if (result4.data && result4.data.code == "00" && result4.data.data && result4.data.data.list) {
          received_direct = result4.data.data.list.length;
          allRows = allRows.concat(result4.data.data.list);
        }

        

        // 마지막 메시지 시각 순 정렬 (최신이 위, 시각 없는 행은 아래)
        allRows.forEach(function(row) {
          row._list_dt = getChatListDateTime(row);
        });
        allRows.sort(function(a, b) {
          if (a._list_dt === b._list_dt) return 0;
          return b._list_dt > a._list_dt ? 1 : -1;
        });

        

        // 카테고리별 중복 제거 로직 (rm_idx 최우선, 같은 방이면 최신 1건만)
        // - rm_idx당 1개 (정식/다이렉트 혼재 방지)
        // - 같은 rm_idx 내에서는 최신 mq_apply_idx 우선
        var dedupedByRmIdx = {}; // rm_idx를 최고 우선순위로 사용

        allRows.forEach(function(row) {
          if (!row.rm_idx) {
            return; // rm_idx 없는 것은 건너뛰기 (채팅방 미생성)
          }

          var rmIdx = row.rm_idx;
          var isDirectChat = (row.rc_name === '채팅방');
          var applyIdx = parseInt(row.mq_apply_idx) || 0;

          // 같은 rm_idx가 이미 있으면: 정식을 우선(공고명 표시), 같은 타입이면 최신 지원건 우선
          // (서버에서 방 분류가 나뉘어 한 방은 한 목록에만 오지만, 레거시 데이터 대비 안전망)
          if (!dedupedByRmIdx[rmIdx]) {
            dedupedByRmIdx[rmIdx] = row;
          } else {
            var existingIsDirectChat = (dedupedByRmIdx[rmIdx].rc_name === '채팅방');
            var existingApplyIdx = parseInt(dedupedByRmIdx[rmIdx].mq_apply_idx) || 0;

            // 정식이 다이렉트(순수 채팅방)를 항상 우선
            if (!isDirectChat && existingIsDirectChat) {
              dedupedByRmIdx[rmIdx] = row;
            }
            // 같은 타입이면 최신 지원건(높은 mq_apply_idx)으로 대체
            else if (isDirectChat === existingIsDirectChat && applyIdx > existingApplyIdx) {
              dedupedByRmIdx[rmIdx] = row;
            }
          }
        });

        // 결과를 배열로 변환
        var dedupedList = [];
        Object.keys(dedupedByRmIdx).forEach(function(key) {
          dedupedList.push(dedupedByRmIdx[key]);
        });

        // 최종 결과도 마지막 메시지 시각 순으로 다시 정렬 (정식+다이렉트 통합)
        dedupedList.sort(function(a, b) {
          if (a._list_dt === b._list_dt) return 0;
          return b._list_dt > a._list_dt ? 1 : -1;
        });

     

        // 메모리에서 전체 HTML 구성 (리플로우 최소화)
        var htmlBuffer = "";
        dedupedList.forEach(function(row, idx) {
          var $clone = $("#tpl_chat_by_recruite").clone();
          if ($clone.length == 0) {
            return;
          }

          var $content = $clone.html();

          $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || "");
          $content = $content.replaceAll("{{rc_idx}}", row.rc_idx || "");
          $content = $content.replaceAll("{{mm_name}}", row.mm_name || "");
          $content = $content.replaceAll("{{ru_week}}", row.ru_week || "");
          $content = $content.replaceAll("{{distance}}", (row.distance ? row.distance + "km" : ""));
          $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img || "");
          $content = $content.replaceAll("{{ru_recom_badge}}", row.ru_recom_badge || "");
          $content = $content.replaceAll("{{mm_nation}}", row.mm_nation || "");
          $content = $content.replaceAll("{{ma_age}}", row.ma_age || "");
          $content = $content.replaceAll("{{ru_price_type}}", row.ru_price_type || "");
          $content = $content.replaceAll("{{ru_my_type}}", row.ru_my_type || "");
          $content = $content.replaceAll("{{ru_work_type}}", row.ru_work_type || "");
          $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img || "");
          $content = $content.replaceAll("{{mm_id}}", row.mm_id || "");
          $content = $content.replaceAll("{{ad_addr}}", row.ad_addr || "");
          $content = $content.replaceAll("{{target_id}}", row.target_id || "");
          $content = $content.replaceAll("{{user_id}}", row.user_id || "");
          $content = $content.replaceAll("{{target_img}}", row.target_img || "");
          $content = $content.replaceAll("{{user_img}}", row.user_img || "");
          $content = $content.replaceAll("{{target_name}}", row.target_name || "");
          $content = $content.replaceAll("{{user_name}}", row.user_name || "");
          $content = $content.replaceAll("{{rc_name}}", row.rc_name || "");
          $content = $content.replaceAll("{{room_idx}}", row.rm_idx || "");
          $content = $content.replaceAll("{{up_dt}}", formatChatListTime(row._list_dt));
          $content = $content.replaceAll("{{last_msg_preview}}", (row.last_msg_preview || "").replace(/\n/g, "<br/>"));

     
          // 프로필 이미지 처리
          var maleAvatarPath = "/resource/shop/web/img/avatar_icon2_m.png";
          var femaleAvatarPath = "/resource/shop/web/img/avatar_resume_w.png";
          var noImagePath = "/resource/shop/mobile/img/common/noimage4.png";
          var profileImg = (typeof row.ru_resume_img === "string") ? row.ru_resume_img.trim() : (typeof row.target_img === "string" ? row.target_img.trim() : "");
          var mmSex = (row.mm_sex || "").trim();
          // mm_sex를 알 수 없는 경우에도 noImagePath로 되돌아가지 않도록 기본값을 남성 아바타로 고정
          var defaultAvatar = maleAvatarPath;
          if (mmSex === "여") {
            defaultAvatar = femaleAvatarPath;
          }
          if (!profileImg || profileImg === "" || profileImg.indexOf("noimage4.png") !== -1) {
            profileImg = defaultAvatar;
          }
          $content = $content.replaceAll("{{profile_img}}", profileImg);
          $content = $content.replaceAll("{{default_avatar}}", defaultAvatar);

          // 이름 처리
          var ruName = row.ru_name || row.target_name || "";
          if (ruName && ruName.length > 18) {
            ruName = ruName.substring(0, 18) + '...';
          }
          $content = $content.replaceAll("{{ru_name}}", ruName);

          $clone.html($content);
          var resumeboxHtml = "<div class='resumebox_'>" + $clone.html() + "</div>";
          htmlBuffer += resumeboxHtml;  // 메모리에 누적
        });

        // 한 번에 DOM에 적용 (리플로우 1회만 발생)
        $("#chat_list").html(htmlBuffer);

        // 마지막 메시지 시각 표시 (템플릿에서 이미 포맷팅된 값을 data-time에 넣음)
        $("#chat_list .last-chat-time").each(function() {
          $(this).text($(this).attr('data-time') || "");
        });

       

        // 목록 표시
        var chatListParent = $("#chat_list").parent();
        chatListParent.show();
        chatListParent.css("display", "block");
        isLoadingChatList = false; // 로딩 완료

        // 미확인 채팅건수 조회
       
        fSearchNoReadCount();

        // empty 상태 재평가
        if (dedupedList.length === 0) {
   
          $("#chat_empty1").show();
        } else {
       
          $("#chat_empty1").hide();
        }
      }).fail(function() {
        commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
        isLoadingChatList = false;
      });
    }).fail(function() {
      commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
      isLoadingChatList = false;
    });
  }
</script>
<?php if ($this->common->getSession("os_type") == "android") : ?>
  <script>
    // 디바운싱을 위한 타이머 변수
    var fSearchNoReadCountTimer = null;
    var noreadCountPollingTimer = null;
    var chatListReloadTimer = null;

    // 디바운싱된 fSearchNoReadCount 함수
    function fSearchNoReadCountDebounced() {
      if (fSearchNoReadCountTimer) {
        clearTimeout(fSearchNoReadCountTimer);
      }
      fSearchNoReadCountTimer = setTimeout(function() {
        fSearchNoReadCount();
      }, 300); // 300ms 디바운싱
    }

    // 디바운싱된 채팅목록 재조회 함수 (새 메시지 수신 시 마지막 메시지 미리보기/정렬/신규방 반영)
    function getChatTargetListDebounced() {
      if (chatListReloadTimer) {
        clearTimeout(chatListReloadTimer);
      }
      chatListReloadTimer = setTimeout(function() {
        getChatTargetList(1);
      }, 300); // 300ms 디바운싱
    }

    // 주기적 폴링 시작 함수
    function startNoreadCountPolling() {
      if (noreadCountPollingTimer) {
        clearInterval(noreadCountPollingTimer);
      }

      // 10초마다 실행
      noreadCountPollingTimer = setInterval(function() {
        fSearchNoReadCountDebounced();
      }, 10000); // 10초마다
    }

    // 주기적 폴링 중지 함수
    function stopNoreadCountPolling() {
      if (noreadCountPollingTimer) {
        clearInterval(noreadCountPollingTimer);
        noreadCountPollingTimer = null;
      }
    }

    // 실시간 소켓 이벤트 리스너 초기화
    function initRealTimeTabBadgeUpdate() {
      // WebSocket이 존재하는지 확인
      if (typeof window.socket === 'undefined' || !window.socket) {
        // WebSocket이 아직 준비되지 않았으면 잠시 후 재시도
        setTimeout(function() {
          initRealTimeTabBadgeUpdate();
        }, 1000);
        return;
      }

      var socket = window.socket;

      // 기존 이벤트 리스너 제거 (중복 등록 방지)
      socket.off('realtime_message');
      socket.off('chat_notification');
      socket.off('message_read_count_return');
      socket.off('message_refresh');

      // ✅ 실제로 서버가 새 메시지마다 발신하는 실시간 이벤트 (수신자의 현재 접속 소켓으로 직접 전송됨)
      // chat_view가 아닌 채팅목록 화면에 있어도 수신되므로, 목록(마지막 메시지/정렬/신규방) 재조회의 핵심 트리거
      socket.on('message_refresh', function(ret) {
        fSearchNoReadCountDebounced();
        getChatTargetListDebounced();
      });

      // 실시간 메시지 수신 이벤트
      socket.on('realtime_message', function(ret) {
        if (ret && ret.type == "chat") {
          // 채팅 메시지 수신 시 배지 업데이트 + 목록(마지막 메시지/정렬/신규방) 재조회
          fSearchNoReadCountDebounced();
          getChatTargetListDebounced();
        }
      });

      // 채팅 알림 수신 이벤트
      socket.on('chat_notification', function(ret) {
        // 채팅 알림 수신 시 배지 업데이트 + 목록 재조회
        fSearchNoReadCountDebounced();
        getChatTargetListDebounced();
      });

      // 메시지 읽음 카운트 변경 이벤트
      socket.on('message_read_count_return', function(ret) {
        // 읽음 카운트 변경 시 배지 업데이트
        fSearchNoReadCountDebounced();
      });

      // 페이지가 보이지 않을 때는 폴링 중지 (성능 최적화)
      document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
          stopNoreadCountPolling();
        } else {
          // 페이지가 다시 보이면 즉시 업데이트 후 폴링 재시작
          fSearchNoReadCountDebounced();
          startNoreadCountPolling();
        }
      });

      // 주기적 폴링 시작
      startNoreadCountPolling();
    }

    // 안드로이드에서도 채팅방 목록을 가져오도록 추가
    window.addEventListener('DOMContentLoaded', function() {
      getChatTargetList(1);
      initRealTimeTabBadgeUpdate();
    });

    // 페이지 포커스 시 뱃지만 갱신 (목록 갱신은 pageshow에서 처리)
    window.addEventListener('focus', function() {
      fSearchNoReadCount();
    });

    // pageshow 이벤트 추가 (뒤로가기/앞으로가기 대응 - BFCache 복원 시에만)
    window.addEventListener('pageshow', function(event) {

      // BFCache에서 복원된 경우에만 목록 갱신
      if (event.persisted) {
        // 진행 중이 아니면 로드 시작
        if (!isLoadingChatList) {
          getChatTargetList(1);
        }
      }

      // 뱃지는 항상 갱신
      fSearchNoReadCount();
    });

    //방생성 확인 및 방번호 확인
    function room_insert_return(data) {
      var ret = JSON.parse(data);
      //채팅방 실시간 쏘기
      if (first_yn == "Y") {
        APP_NOWAPK.message_insert(JSON.stringify(first_message), JSON.stringify(first_users));
      }

      if (ret.code == "00") {
        location.href = "/chat/chat_list?view=true&rm_idx=" + ret.data;
      } else {
        commonAlert(ret.msg, function() {});
      }
    }
  </script>

  <script>
    var first_yn = "N";
    var first_message = {};
    var first_users = new Array();

    var g_rm_type = "recruit";
    var g_rc_idx = 0;
    var g_ru_idx = 0;
    var g_td_idx = 0;

    //룸생성처리
    $(document).on("click", ".btn-view-chat", function() {
      first_yn = "N";
      var target_id = $(this).data("target_id");
      var user_id = $(this).data("user_id");
      var target_img = $(this).data("target_img");
      var user_img = $(this).data("user_img");
      var target_name = $(this).data("target_name");
      var user_name = $(this).data("user_name");
      var subject = $(this).data("subject");


      g_rc_idx = $(this).data("rc_idx");
      g_ru_idx = $(this).data("ru_idx");

      var params = {};
      params["rm_type"] = g_rm_type;
      params["rc_idx"] = g_rc_idx;
      params["ru_idx"] = g_ru_idx;
      params["td_idx"] = g_td_idx;

      comAjax("/chat_proc/insert_room_check", params, function(chk) {
        if (chk.code == "10") {
          commonAlert(chk.msg, function() {});
          return;
        }

        if (chk.code == "90") {
          openPopup('confirm', {
            content: "포인트가 부족합니다.<br/><em>포인트 구매</em>를 하시겠습니까?",
            secondCallback: function() {
              location.href("/compage/point_charge_step1");
            }
          });
          return;
        }

        if (chk.data.first_yn == "Y") {
          openPopup('confirm', {
            content: '1:1채팅시 <em>-' + chat_point + '</em>포인트가 차감됩니다.<br> 채팅을 진행하시겠습니까?',
            secondCallback: function() {
              comAjax("/chat_proc/insert_room", params, function(ret2) {
                if (ret2.code == "00") {
                  var chat_suer = [];
                  chat_suer.push({
                    user_id: user_id,
                    img: user_img,
                    user_name: user_name
                  });
                  chat_suer.push({
                    user_id: target_id,
                    img: target_img,
                    user_name: target_name
                  });

                  var room_info = {
                    user_id: user_id,
                    user_list: chat_suer,
                    rm_idx: Number(ret2.data.rm_idx),
                    rm_type: "chat",
                    rm_img: "",
                    rm_subject: subject,
                    rm_desc: "",
                  };

                  //기본 문구 전송
                  first_yn = ret2.data.first_yn;
                  first_message = {
                    rm_idx: Number(ret2.data.rm_idx),
                    user_id: user_id,
                    chat_type: "recruit",
                    mg_type: "text",
                    mg_text: "1:1 채팅을 시작합니다!",
                    mg_req_dt: "",
                    mg_img: "",
                    mg_file: "",
                    mg_imoti: "",
                    mg_dt: getCurrentDate(),
                    mg_reads: new Array(),
                  };
                  first_message.mg_reads.push({
                    read_yn: "Y",
                    user_id: user_id
                  });
                  first_message.mg_reads.push({
                    read_yn: "N",
                    user_id: target_id
                  });


                  first_users.push({
                    user_id: user_id,
                    user_nick: user_name
                  });
                  first_users.push({
                    user_id: target_id,
                    user_nick: target_name
                  });

                  //방생성
                  click_type = "chat";
                  APP_NOWAPK.room_insert(JSON.stringify(room_info));
                } else {
                  commonAlert(ret2.msg, function() {});
                  return;
                }
              });
            }
          });
        } else {
          comAjax("/chat_proc/insert_room", params, function(ret2) {
            if (ret2.code == "00") {
              var chat_suer = [];
              chat_suer.push({
                user_id: user_id,
                img: user_img,
                user_name: user_name
              });
              chat_suer.push({
                user_id: target_id,
                img: target_img,
                user_name: target_name
              });

              var room_info = {
                user_id: user_id,
                user_list: chat_suer,
                rm_idx: Number(ret2.data.rm_idx),
                rm_type: "chat",
                rm_img: "",
                rm_subject: subject,
                rm_desc: "",
              };

              //기본 문구 전송
              first_yn = ret2.data.first_yn;
              first_message = {
                rm_idx: Number(ret2.data.rm_idx),
                user_id: user_id,
                chat_type: "recruit",
                mg_type: "text",
                mg_text: "1:1 채팅을 시작합니다!",
                mg_req_dt: "",
                mg_img: "",
                mg_file: "",
                mg_imoti: "",
                mg_dt: getCurrentDate(),
                mg_reads: new Array(),
              };
              first_message.mg_reads.push({
                read_yn: "Y",
                user_id: user_id
              });
              first_message.mg_reads.push({
                read_yn: "N",
                user_id: target_id
              });

              //회원정보
              first_users.push({
                user_id: user_id,
                user_nick: user_name
              });
              first_users.push({
                user_id: target_id,
                user_nick: target_name
              });

              //방생성
              click_type = "chat";
              APP_NOWAPK.room_insert(JSON.stringify(room_info));
            } else {
              commonAlert(ret2.msg, function() {});
              return;
            }
          });
        }
      });
    });

    //
    function fSearchNoReadCount() {
      // 현재 페이지의 chat_list 컨테이너 내의 li 요소만 처리
      $('#chat_list li').each(function() {
        // 개별 채팅방 배지 업데이트
      });

      //공고리스트에 미확인 채팅건수 조회 확인
      comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
        var myId = $("#user_id").val();
        let result = ret.data.result;

    

        // 모든 room의 미확인 메시지 개수를 집계
        $.each(result, function(key, room) {
          let count = 0;
          $.each(room.rm_messages, function(index, message) {
            $.each(message.mg_reads, function(i, readEntry) {
              if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                count++;
              }
            });
          });

          // 하위 인재 리스트의 개별 채팅방 뱃지 업데이트 (DOM에 있는 경우)
          var $unreadElement = $('#room_idx_unread_cnt_' + room.rm_idx);
          if ($unreadElement.length > 0) {
            // 현재 페이지에 있는 채팅방
         
            $unreadElement.html(count);
            if (count > 0) {
              $unreadElement.show();
            } else {
              $unreadElement.hide();
            }
          }
        });
      });
    }
  </script>
<?php else: ?>
  <script>
    // 디바운싱을 위한 타이머 변수
    var fSearchNoReadCountTimer = null;
    var noreadCountPollingTimer = null;
    var chatListReloadTimer = null;

    // 디바운싱된 fSearchNoReadCount 함수
    function fSearchNoReadCountDebounced() {
      if (fSearchNoReadCountTimer) {
        clearTimeout(fSearchNoReadCountTimer);
      }
      fSearchNoReadCountTimer = setTimeout(function() {
        fSearchNoReadCount();
      }, 300); // 300ms 디바운싱
    }

    // 디바운싱된 채팅목록 재조회 함수 (새 메시지 수신 시 마지막 메시지 미리보기/정렬/신규방 반영)
    function getChatTargetListDebounced() {
      if (chatListReloadTimer) {
        clearTimeout(chatListReloadTimer);
      }
      chatListReloadTimer = setTimeout(function() {
        getChatTargetList(1);
      }, 300); // 300ms 디바운싱
    }

    // 주기적 폴링 시작 함수
    function startNoreadCountPolling() {
      if (noreadCountPollingTimer) {
        clearInterval(noreadCountPollingTimer);
      }

      // 10초마다 실행
      noreadCountPollingTimer = setInterval(function() {
        fSearchNoReadCountDebounced();
      }, 10000); // 10초마다
    }

    // 주기적 폴링 중지 함수
    function stopNoreadCountPolling() {
      if (noreadCountPollingTimer) {
        clearInterval(noreadCountPollingTimer);
        noreadCountPollingTimer = null;
      }
    }

    // 실시간 소켓 이벤트 리스너 초기화
    function initRealTimeTabBadgeUpdate() {
      // WebSocket이 존재하는지 확인
      if (typeof window.socket === 'undefined' || !window.socket) {
        // WebSocket이 아직 준비되지 않았으면 잠시 후 재시도
        setTimeout(function() {
          initRealTimeTabBadgeUpdate();
        }, 1000);
        return;
      }

      var socket = window.socket;

      // 기존 이벤트 리스너 제거 (중복 등록 방지)
      socket.off('realtime_message');
      socket.off('chat_notification');
      socket.off('message_read_count_return');
      socket.off('message_refresh');

      // ✅ 실제로 서버가 새 메시지마다 발신하는 실시간 이벤트 (수신자의 현재 접속 소켓으로 직접 전송됨)
      // chat_view가 아닌 채팅목록 화면에 있어도 수신되므로, 목록(마지막 메시지/정렬/신규방) 재조회의 핵심 트리거
      socket.on('message_refresh', function(ret) {
        fSearchNoReadCountDebounced();
        getChatTargetListDebounced();
      });

      // 실시간 메시지 수신 이벤트
      socket.on('realtime_message', function(ret) {
        if (ret && ret.type == "chat") {
          // 채팅 메시지 수신 시 배지 업데이트 + 목록(마지막 메시지/정렬/신규방) 재조회
          fSearchNoReadCountDebounced();
          getChatTargetListDebounced();
        }
      });

      // 채팅 알림 수신 이벤트
      socket.on('chat_notification', function(ret) {
        // 채팅 알림 수신 시 배지 업데이트 + 목록 재조회
        fSearchNoReadCountDebounced();
        getChatTargetListDebounced();
      });

      // 메시지 읽음 카운트 변경 이벤트
      socket.on('message_read_count_return', function(ret) {
        // 읽음 카운트 변경 시 배지 업데이트
        fSearchNoReadCountDebounced();
      });

      // 페이지가 보이지 않을 때는 폴링 중지 (성능 최적화)
      document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
          stopNoreadCountPolling();
        } else {
          // 페이지가 다시 보이면 즉시 업데이트 후 폴링 재시작
          fSearchNoReadCountDebounced();
          startNoreadCountPolling();
        }
      });

      // 주기적 폴링 시작
      startNoreadCountPolling();
    }

    // 페이지 로드 시 제안받은 인재 목록 조회 및 실시간 업데이트 초기화
    window.addEventListener('DOMContentLoaded', function() {
      getChatTargetList(1);
      initRealTimeTabBadgeUpdate();

      document.addEventListener("touchmove", function(e) {

      }, {
        passive: true
      });
    });

    // 페이지 포커스 시 뱃지만 갱신 (목록 갱신은 pageshow에서 처리)
    window.addEventListener('focus', function() {
      fSearchNoReadCount();
    });


    window.addEventListener('pageshow', function(event) {


      if (event.persisted) {

        if (!isLoadingChatList) {
          getChatTargetList(1);
        }
        fSearchNoReadCount();
      }
    });


    function fSearchNoReadCount() {

      // 현재 페이지의 chat_list 컨테이너 내의 li 요소만 처리
      $('#chat_list li').each(function() {
        // 개별 채팅방 배지 업데이트
      });

      //공고리스트에 미확인 채팅건수 조회 확인
      comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
        var myId = $("#user_id").val();

        let result = ret.data.result;

     

        // 모든 room의 미확인 메시지 개수를 먼저 집계
        $.each(result, function(key, room) {
          let count = 0;
          $.each(room.rm_messages, function(index, message) {
            $.each(message.mg_reads, function(i, readEntry) {
              if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                count++;
              }
            });
          });

          // 하위 인재 리스트의 개별 채팅방 뱃지 업데이트 (DOM에 있는 경우)
          var $unreadElement = $('#room_idx_unread_cnt_' + room.rm_idx);
          if ($unreadElement.length > 0) {
            // 현재 페이지에 있는 채팅방
         
            $unreadElement.html(count);
            if (count > 0) {
              $unreadElement.show();
            } else {
              $unreadElement.hide();
            }
          }
        });
      });
    }

  </script>

  <script>
    var first_yn = "N";
    var first_message = {};
    var first_users = new Array();

    var g_rm_type = "recruit";
    var g_rc_idx = 0;
    var g_ru_idx = 0;
    var g_td_idx = 0;

    //룸생성처리
    $(document).on("click", ".btn-view-chat", function() {
      first_yn = "N";
      var target_id = $(this).data("target_id");
      var user_id = $(this).data("user_id");
      var target_img = $(this).data("target_img");
      var user_img = $(this).data("user_img");
      var target_name = $(this).data("target_name");
      var user_name = $(this).data("user_name");
      var subject = $(this).data("subject");

      //키값 설정
      g_rc_idx = $(this).data("rc_idx");
      g_ru_idx = $(this).data("ru_idx");

      var params = {};
      params["rm_type"] = g_rm_type;
      params["rc_idx"] = g_rc_idx;
      params["ru_idx"] = g_ru_idx;
      params["td_idx"] = g_td_idx;

      comAjax("/chat_proc/insert_room_check", params, function(chk) {
        if (chk.code == "10") {
          commonAlert(chk.msg, function() {});
          return;
        }

        if (chk.code == "90") {
          openPopup('confirm', {
            content: "포인트가 부족합니다.<br/><em>포인트 구매</em>를 하시겠습니까?",
            secondCallback: function() {
              location.href("/compage/point_charge_step1");
            }
          });
          return;
        }

        if (chk.data.first_yn == "Y") {
          openPopup('confirm', {
            content: '1:1채팅시 <em>-' + chat_point + '</em>포인트가 차감됩니다.<br> 채팅을 진행하시겠습니까?',
            secondCallback: function() {
              comAjax("/chat_proc/insert_room", params, function(ret2) {
                if (ret2.code == "00") {
                  var chat_suer = [];
                  chat_suer.push({
                    user_id: user_id,
                    img: user_img,
                    user_name: user_name
                  });
                  chat_suer.push({
                    user_id: target_id,
                    img: target_img,
                    user_name: target_name
                  });

                  var room_info = {
                    user_id: user_id,
                    user_list: chat_suer,
                    rm_idx: Number(ret2.data.rm_idx),
                    rm_type: "chat",
                    rm_img: "",
                    rm_subject: subject,
                    rm_desc: "",
                  };

                  //기본 문구 전송
                  first_yn = ret2.data.first_yn;
                  first_message = {
                    rm_idx: Number(ret2.data.rm_idx),
                    user_id: user_id,
                    chat_type: "recruit",
                    mg_type: "text",
                    mg_text: "1:1 채팅을 시작합니다!",
                    mg_req_dt: "",
                    mg_img: "",
                    mg_file: "",
                    mg_imoti: "",
                    mg_dt: getCurrentDate(),
                    mg_reads: new Array(),
                  };
                  first_message.mg_reads.push({
                    read_yn: "Y",
                    user_id: user_id
                  });
                  first_message.mg_reads.push({
                    read_yn: "N",
                    user_id: target_id
                  });

                  //회원정보
                  first_users.push({
                    user_id: user_id,
                    user_nick: user_name
                  });
                  first_users.push({
                    user_id: target_id,
                    user_nick: target_name
                  });

                  //방생성 (소켓 기반 - 웹 버전)
                  click_type = "chat";
                  socket.emit("room_insert", room_info);
                } else {
                  commonAlert(ret2.msg, function() {});
                  return;
                }
              });
            }
          });
        } else {
          comAjax("/chat_proc/insert_room", params, function(ret2) {
            if (ret2.code == "00") {
              var chat_suer = [];
              chat_suer.push({
                user_id: user_id,
                img: user_img,
                user_name: user_name
              });
              chat_suer.push({
                user_id: target_id,
                img: target_img,
                user_name: target_name
              });

              var room_info = {
                user_id: user_id,
                user_list: chat_suer,
                rm_idx: Number(ret2.data.rm_idx),
                rm_type: "chat",
                rm_img: "",
                rm_subject: subject,
                rm_desc: "",
              };

              //기본 문구 전송
              first_yn = ret2.data.first_yn;
              first_message = {
                rm_idx: Number(ret2.data.rm_idx),
                user_id: user_id,
                chat_type: "recruit",
                mg_type: "text",
                mg_text: "1:1 채팅을 시작합니다!",
                mg_req_dt: "",
                mg_img: "",
                mg_file: "",
                mg_imoti: "",
                mg_dt: getCurrentDate(),
                mg_reads: new Array(),
              };
              first_message.mg_reads.push({
                read_yn: "Y",
                user_id: user_id
              });
              first_message.mg_reads.push({
                read_yn: "N",
                user_id: target_id
              });

              //회원정보
              first_users.push({
                user_id: user_id,
                user_nick: user_name
              });
              first_users.push({
                user_id: target_id,
                user_nick: target_name
              });

              //방생성 (소켓 기반 - 웹 버전)
              click_type = "chat";
              socket.emit("room_insert", room_info);
            } else {
              commonAlert(ret2.msg, function() {});
              return;
            }
          });
        }
      });
    });
  </script>
<?php endif; ?>

<script>
  $(document).on("click", ".room-item", function(e) {
    var target_id = $(this).data("target_id");
    location.href = "/chat/chat_view?rm_idx=" + target_id + "&backUrl=" + encodeURIComponent('/chat/index_comp');
  });
</script>

<!-- 앱 딥링크 -->
<script src="/resource/shop/mobile/js/app-deeplink.js"></script>

<template id="tpl_chat_by_recruite">
  <li class="list-item msg-item room-item aa room_idx_{{room_idx}}" data-target_id="{{room_idx}}">

    <a href="#" class="angle-link js-open-view angle-link-resume"
      aria-haspopup="dialog"
      data-ru_idx="{{ru_idx}}"
      data-rc_idx="{{rc_idx}}"
      data-target_id="{{target_id}}"
      data-user_id="{{user_id}}"
      data-target_img="{{target_img}}"
      data-user_img="{{user_img}}"
      data-target_name="{{target_name}}"
      data-user_name="{{user_name}}"
      data-subject="{{rc_name}}"
      onclick="location.href='/chat/chat_view?rm_idx={{room_idx}}&backUrl=' + encodeURIComponent('/chat/index_comp'); return false;">

      <div class="content-body">
        <div class="chat-time-row">
          <!-- <div class="wicon">{{ru_recom_badge}}{{ru_work_img}}</div> -->
          <div class="last-chat-time" data-time="{{up_dt}}"></div>
        </div>
        <div class="img" style="border-radius: 60px;">
          <img src="{{profile_img}}" alt="">
        </div>
        <div class="desc">
           <!-- ㅁㅁㅁㅁ -->
          <div class="title ellipsis">{{ru_name}}</div>

          <div class="summary-with-badge">
            <div class="summary">
              <p class="last-message">{{last_msg_preview}}</p>
            </div>
            <span class="room_unread_cnt" id="room_idx_unread_cnt_{{room_idx}}"></span>
          </div>
          <!-- <div class="summary3">
            <p class="worktype">{{ru_work_type}}</p>
          </div> -->
        </div>
      </div>
    </a>
    <div class="recruit-name-section">{{rc_name}}</div>
    <button type="button" onclick="deleteItem(this.parentNode);" class="btn-primary btn-trash js-del-item">
      <span class="sr-text">삭제</span>
    </button>

  </li>
</template>
