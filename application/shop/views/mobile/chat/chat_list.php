<?php
$top_bar = array(
    'title' => '채팅방'
);
?>
<div class="page page-chat">
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/top_bar.php'); ?>

  <div class="page-content resume-chat-list">
    <div class="tab-panel">
      <div class="container" style="padding: 0;">
        <!-- 통합 채팅목록 (지원한공고 + 제안받은공고 + 다이렉트채팅) -->
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
  function getChatTargetListUnifiedDebounced() {
    if (chatListReloadTimer) {
      clearTimeout(chatListReloadTimer);
    }
    chatListReloadTimer = setTimeout(function() {
      getChatTargetListUnified();
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
      getChatTargetListUnifiedDebounced();
    });

    // 실시간 메시지 수신 이벤트
    socket.on('realtime_message', function(ret) {
      if (ret && ret.type == "chat") {
        // 채팅 메시지 수신 시 배지 업데이트 + 목록(마지막 메시지/정렬/신규방) 재조회
        fSearchNoReadCountDebounced();
        getChatTargetListUnifiedDebounced();
      }
    });

    // 채팅 알림 수신 이벤트
    socket.on('chat_notification', function(ret) {
      // 채팅 알림 수신 시 배지 업데이트 + 목록 재조회
      fSearchNoReadCountDebounced();
      getChatTargetListUnifiedDebounced();
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

  // 페이지 로드 시 4개 목록 조회 및 실시간 업데이트 초기화
  window.addEventListener('DOMContentLoaded', function(e) {
    getChatTargetListUnified();
    initRealTimeTabBadgeUpdate();
  });

  // 페이지 포커스 시 카운트 갱신 (chat_view에서 돌아왔을 때)
  window.addEventListener('focus', function() {
    fSearchNoReadCount();
  });

  // 통합 채팅 목록 조회 함수 (4개 데이터소스 병렬 호출)
  function getChatTargetListUnified() {
    var promises = [];

    // Promise 1: 지원한공고 (정식 지원)
    var promise1 = $.ajax({
      url: "/chat_proc/get_chat_target_list_recruit_mem",
      type: "POST",
      dataType: "json"
    }).then(function(data) {
      return { category: 'applied', data: data };
    });

    // Promise 2: 제안받은공고 (정식 제안)
    var promise2 = $.ajax({
      url: "/chat_proc/get_chat_target_list2_mem",
      type: "POST",
      dataType: "json"
    }).then(function(data) {
      return { category: 'proposed', data: data };
    });

    // Promise 3: 보낸다이렉트채팅
    var promise3 = $.ajax({
      url: "/chat_proc/get_direct_chat_list_sent_mem",
      type: "POST",
      dataType: "json"
    }).then(function(data) {
      return { category: 'direct_sent', data: data };
    });

    // Promise 4: 받은다이렉트채팅
    var promise4 = $.ajax({
      url: "/chat_proc/get_direct_chat_list_received_mem",
      type: "POST",
      dataType: "json"
    }).then(function(data) {
      return { category: 'direct_received', data: data };
    });

    $.when(promise1, promise2, promise3, promise4).done(function(result1, result2, result3, result4) {
      // 4개 응답 결과를 하나의 배열로 합산
      var allRows = [];

      // 각 카테고리의 결과 처리
      var results = [result1, result2, result3, result4];
   

      results.forEach(function(res) {
        if (res && res.data && res.data.code == "00" && res.data.data && res.data.data.list) {
          var list = res.data.data.list;
      
          list.forEach(function(row) {
            row._category = res.category;
            allRows.push(row);
          });
        }
      });



      // 시간순 정렬 (최신이 위)
      allRows.sort(function(a, b) {
        var timeA = a.up_dt || a.reg_dt || a.last_msg_time || "";
        var timeB = b.up_dt || b.reg_dt || b.last_msg_time || "";
        return timeB > timeA ? 1 : -1;
      });

     

      // 채팅방(rm_idx) 기준 중복제거 - 같은 rm_idx는 가장 최신 기록만 유지
      var dedupedByRoom = {};
      allRows.forEach(function(row) {
        var rmIdx = row.rm_idx;
        // rm_idx가 없으면 건너뛰기 (직접채팅의 경우 처리)
        if (!rmIdx) {
          return;
        }
        // 같은 rm_idx 중 가장 먼저 나온 것(= 가장 최신)만 유지
        if (!dedupedByRoom[rmIdx]) {
          dedupedByRoom[rmIdx] = row;
        }
      });

      // dedupe 후 rm_idx가 없는 행 필터링
      // 주의: rm_idx가 없는 행은 "아직 채팅방이 생성되지 않은 지원/제안 건"을 의미
      // 이는 채팅목록에서 제외해야 함 (채팅목록 = "대화가 시작된 건만")
      var finalRows = [];
      var excludedNullRmIdx = 0;
      allRows.forEach(function(row) {
        if (!row.rm_idx) {
          excludedNullRmIdx++;
          return; // 채팅방이 생성되지 않은 지원/제안 건은 제외
        }
        if (dedupedByRoom[row.rm_idx] === row) {
          finalRows.push(row);
        }
      });

   
      

   
      var htmlBuffer = "";
      finalRows.forEach(function(row, idx) {
        var $clone = $("#tpl_chat_mem").clone();
        var $content = $clone.html();

        // 템플릿 변수 바인딩
        $content = $content.replaceAll("{{room_idx}}", row.rm_idx || "");
        $content = $content.replaceAll("{{reg_dt}}", row.reg_dt || "");
        $content = $content.replaceAll("{{rc_thumb}}", row.rc_thumb || "");
        $content = $content.replaceAll("{{mm_comp_name}}", row.mm_comp_name || "");
        $content = $content.replaceAll("{{rc_name}}", row.rc_name || "");
        $content = $content.replaceAll("{{rt_recom_badge}}", row.rt_recom_badge || "");
        $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img || "");
        $content = $content.replaceAll("{{last_msg_preview}}", (row.last_msg_preview || "").replace(/\n/g, "<br/>"));
        $content = $content.replaceAll("{{up_dt}}", row.up_dt || row.reg_dt || "");

       

        $clone.html($content);
        var recruitboxHtml = "<div class='recruitbox_'>" + $clone.html() + "</div>";
        htmlBuffer += recruitboxHtml;
      });

      // 한 번에 DOM에 적용 (리플로우 1회만 발생)
      $("#chat_list").html(htmlBuffer);

   

      // PHP에서 이미 포맷팅된 시간을 표시 (날짜만)
      $("#chat_list .last-chat-time").each(function() {
        var fullTime = $(this).attr('data-time');
        var dateOnly = fullTime.split(' ')[0];
        $(this).text(dateOnly);
      });

      // 미확인 채팅건수 조회
      
      fSearchNoReadCount();

      // URL 파라미터에서 auto_open_rc_idx 확인하여 해당 채팅방 자동 진입
      var urlParams = new URLSearchParams(window.location.search);
      var autoOpenRcIdx = urlParams.get('auto_open_rc_idx');
      var autoOpenMqType = urlParams.get('mq_type');
      var autoOpenRuIdx = urlParams.get('ru_idx');
      if (autoOpenRcIdx && allRows.length > 0) {
        // URL에서 auto_open_rc_idx 파라미터 제거 (뒤로가기 시 재진입 방지)
        urlParams.delete('auto_open_rc_idx');
        var newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
        window.history.replaceState({}, '', newUrl);

        // 렌더링된 DOM에서 해당 rc_idx를 가진 채팅방 찾기
        setTimeout(function() {
          var targetRmIdx = null;

          // DOM에서 해당 rc_idx를 가진 채팅방 찾기
          for (var i = 0; i < allRows.length; i++) {
            var item = allRows[i];
            if (item.rc_idx == autoOpenRcIdx) {
              if (item.rm_idx) {
                targetRmIdx = item.rm_idx;
                break;
              }
            }
          }

          // 채팅방을 찾았으면 자동으로 진입
          if (targetRmIdx) {
            var chatViewUrl = "/chat/chat_view?rm_idx=" + targetRmIdx + "&auto_send=true&rc_idx=" + autoOpenRcIdx + "&backUrl=" + encodeURIComponent('/chat/chat_list');
            if (autoOpenMqType) {
              chatViewUrl += "&mq_type=" + autoOpenMqType;
            }
            if (autoOpenRuIdx) {
              chatViewUrl += "&ru_idx=" + autoOpenRuIdx;
            }
            location.href = chatViewUrl;
          }
        }, 500); // 목록 렌더링 대기
      }

    }).fail(function() {
      commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
    });
  }

  // 통합 목록의 미확인 채팅건수 조회
  function fSearchNoReadCount() {
    comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
      var myId = $("#user_id").val();
      let result = ret.data.result;

    

      $.each(result, function(key, room) {
        let count = 0;
        $.each(room.rm_messages, function(index, message) {
          $.each(message.mg_reads, function(i, readEntry) {
            if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
              count++;
            }
          });
        });

        // 개별 채팅방 배지 업데이트 (DOM에 실제 렌더링된 요소만)
        var $unreadElement = $('#room_idx_unread_cnt_' + room.rm_idx);
        if ($unreadElement.length > 0) {
        
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

  // 채팅 목록 아이템 클릭 이벤트 처리
  $(document).on("click", ".room-item", function(e) {
    var target_id = $(this).data("target_id");
    var $unreadElement = $('#room_idx_unread_cnt_' + target_id);

    // 채팅방 클릭 시 카운트를 즉시 0으로 설정 (시각적 피드백)
    if ($unreadElement.length > 0) {
      $unreadElement.html(0).hide();
    }

    // 채팅방으로 이동 (backUrl 파라미터로 뒤로가기 URL 전달)
    location.href = "/chat/chat_view?rm_idx=" + target_id + "&backUrl=" + encodeURIComponent('/chat/chat_list');
  });

</script>

<!-- 통합 채팅목록 템플릿 (지원한공고 + 제안받은공고 + 다이렉트채팅) -->
<template id="tpl_chat_mem">
  <li class="list-item msg-item room-item room_idx_{{room_idx}}" data-target_id="{{room_idx}}" data-regdt="{{reg_dt}}">
    <a href="#" class="angle-link js-open-view" data-regdt="{{reg_dt}}">
      <div class="content-body">
        <div class="chat-time-row">
          <div class="last-chat-time" data-time="{{up_dt}}"></div>
        </div>
        <div class="img" style="border-radius: 60px;">
          <img src="{{rc_thumb}}" alt="" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
        </div>

        <div class="desc">
          <div class="title ellipsis">{{mm_comp_name}}</div>

          <div class="summary-with-badge">
            <div class="summary">
              <p class="last-message">{{last_msg_preview}}</p>
            </div>
          </div>
        </div>

      </div>
    </a>
    <div class="recruit-name-section">{{rc_name}}</div>

    <span class="room_rec_unread_cnt" id="room_idx_unread_cnt_{{room_idx}}"></span>
  </li>
</template>
