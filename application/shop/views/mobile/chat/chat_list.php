<?php
$top_bar = array(
    'title' => '채팅방'
);
?>
<div class="page page-chat">
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/top_bar.php'); ?>

  <div class="page-content resume-chat-list">

    <div class="tab-container">
      <ul class="tab-list">
        <?php if ($user_type == "MEM") : ?>
          <li class="on"><a href="/chat/chat_list" class="tab">채팅보낸 공고<span class="tab-badge"></span></a></li>
          <li><a href="/chat/chat_list2" class="tab">채팅받은 공고<span class="tab-badge"></span></a></li>
        <?php endif; ?>
      </ul>
    </div>

    <style>
      /* 탭에 읽지 않은 메시지 알림 표시 (빨간 동그라미) */
      .page-chat .tab-container .tab-list li {
        position: relative;
      }

      .page-chat .tab-container .tab-list li .tab {
        position: relative;
      }

      .page-chat .tab-container .tab-list li .tab-badge {
        position: absolute;
        top: 8px;
        right: -12px;
        width: 8px;
        height: 8px;
        min-width: 8px;
        min-height: 8px;
        max-width: 8px;
        max-height: 8px;
        box-sizing: border-box;
        padding: 0;
        margin: 0;
        line-height: 1;
        flex-shrink: 0;
        flex-grow: 0;
        align-self: flex-start;
        background-color: rgb(248, 96, 72);
        border-radius: 50%;
        display: none;
        z-index: 10;
      }

      .page-chat .tab-container .tab-list li .tab-badge::before,
      .page-chat .tab-container .tab-list li .tab-badge::after {
        content: none;
        display: none;
      }

      .page-chat .tab-container .tab-list li.has-unread .tab-badge {
        display: block;
      }
    </style>
    <div class="tab-panel">
    <div class="container" style="padding: 0;">
        <!-- 지원한공고 탭 전용 페이지 (개인회원용) -->
        <h2 class="sr-text">지원한공고</h2>

        <div class="chat-list">
          <ol class="list result-list-card" id="chat_list">

          </ol>
          <div class="chat-empty" id="chat_empty1">
            <p>목록이 없습니다.</p>
          </div>
        </div>

        <!-- 보낸다이렉트채팅 섹션 -->
        <div class="direct-chat-list-section">
          <h3 class="section-title">보낸 다이렉트 채팅</h3>
          <div class="chat-list">
            <ol class="list result-list-card" id="direct_chat_list_sent">

            </ol>
            <div class="chat-empty" id="direct_chat_empty_sent" style="display: none;">
              <p>목록이 없습니다.</p>
            </div>
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

  // 디바운싱된 fSearchNoReadCount 함수
  function fSearchNoReadCountDebounced() {
    if (fSearchNoReadCountTimer) {
      clearTimeout(fSearchNoReadCountTimer);
    }
    fSearchNoReadCountTimer = setTimeout(function() {
      fSearchNoReadCount();
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

    // 실시간 메시지 수신 이벤트
    socket.on('realtime_message', function(ret) {
      if (ret && ret.type == "chat") {
        // 채팅 메시지 수신 시 탭 배지 업데이트
        fSearchNoReadCountDebounced();
      }
    });

    // 채팅 알림 수신 이벤트
    socket.on('chat_notification', function(ret) {
      // 채팅 알림 수신 시 탭 배지 업데이트
      fSearchNoReadCountDebounced();
    });

    // 메시지 읽음 카운트 변경 이벤트
    socket.on('message_read_count_return', function(ret) {
      // 읽음 카운트 변경 시 탭 배지 업데이트
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

  // 페이지 로드 시 지원한공고 목록 조회 및 실시간 업데이트 초기화
  window.addEventListener('DOMContentLoaded', function(e) {
    getChatTargetList(); // 콜백 내에서 fSearchDirectChatListSentMem() 호출됨
    initRealTimeTabBadgeUpdate();
  });

  // 페이지 포커스 시 카운트 갱신 (chat_view에서 돌아왔을 때)
  window.addEventListener('focus', function() {
    fSearchNoReadCount();
  });

  // 지원한공고 탭 데이터 조회 함수 (개인회원 전용)
  function getChatTargetList() {
    comAjax("/chat_proc/get_chat_target_list_recruit_mem", {}, function(ret) {
      $("#chat_list").html("");

      if (ret.code == "00") {
        var list = ret.data.list;

        // 회사(mm_idx)별 중복 제거 - 같은 회사는 마지막 대화(up_dt)가 가장 최근인 행만 남김
        var deduped = {};
        list.forEach(function(row) {
          var companyIdx = row.mm_idx;
          var rowTime = row.up_dt || row.last_msg_time || "";
          var existing = deduped[companyIdx];
          if (!existing || rowTime > (existing.up_dt || existing.last_msg_time || "")) {
            deduped[companyIdx] = row;
          }
        });

        var dedupedList = Object.keys(deduped).map(function(key) {
          return deduped[key];
        });

        // dedupe 이전의 전체 rm_idx를 추적 (탭 판별용)
        var currentTabRmIdxSet = new Set();
        list.forEach(function(row) {
          currentTabRmIdxSet.add(String(row.rm_idx));
        });
        window.__chatTab1RmIdxSet = currentTabRmIdxSet; // 지원한공고 탭의 모든 rm_idx

        dedupedList.forEach(function(row) {
          var $clone = $("#tpl_chat_mem").clone();
          var $content = $clone.html();

          // 템플릿 변수 바인딩
          $content = $content.replaceAll("{{room_idx}}", row.rm_idx || "");
          $content = $content.replaceAll("{{reg_dt}}", row.reg_dt || "");
          $content = $content.replaceAll("{{rc_thumb}}", row.rc_thumb || "");

          // 회사명으로 표시
          $content = $content.replaceAll("{{mm_comp_name}}", row.mm_comp_name || "");

          // 마지막 대화의 공고명
          $content = $content.replaceAll("{{rc_name}}", row.rc_name || "");

          $content = $content.replaceAll("{{rt_recom_badge}}", row.rt_recom_badge || "");
          $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img || "");

          // 마지막 메시지 바인딩
          $content = $content.replaceAll("{{last_msg_preview}}", (row.last_msg_preview || "").replace(/\n/g, "<br/>"));

          // 마지막 채팅 시간
          $content = $content.replaceAll("{{up_dt}}", row.up_dt || "");

          // 복사한 내용 다시 넣기
          $clone.html($content);
          var recruitboxHtml = "<div class='recruitbox_'>" + $clone.html() + "</div>";
          $("#chat_list").append(recruitboxHtml);
        });

        // 목록이 없어도 일단 숨김 (다이렉트 채팅 로드 후 최종 판단)
        // 렌더링 중 깜빡임 방지

        // PHP에서 이미 포맷팅된 시간을 표시
        $("#chat_list .last-chat-time").each(function() {
          $(this).text($(this).attr('data-time'));
        });
      } else {
        commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
        return;
      }

      // 미확인 채팅건수 조회
      fSearchNoReadCount();

      // 기본 목록 로드 완료 후 다이렉트 채팅 로드 (순서 보장)
      if (typeof fSearchDirectChatListSentMem === 'function') {
        fSearchDirectChatListSentMem();
      }

      // URL 파라미터에서 auto_open_rc_idx 확인하여 해당 채팅방 자동 진입
      var urlParams = new URLSearchParams(window.location.search);
      var autoOpenRcIdx = urlParams.get('auto_open_rc_idx');
      if (autoOpenRcIdx && ret.code == "00" && ret.data && ret.data.list) {
        // URL에서 auto_open_rc_idx 파라미터 제거 (뒤로가기 시 재진입 방지)
        urlParams.delete('auto_open_rc_idx');
        var newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
        window.history.replaceState({}, '', newUrl);
        
        // 목록 로드 완료 후 해당 rc_idx를 가진 채팅방 찾기
        setTimeout(function() {
          var targetRmIdx = null;
          var chatList = ret.data.list;
          
          // DOM에서 해당 rc_idx를 가진 채팅방 찾기
          $("#chat_list .room-item").each(function() {
            var $item = $(this);
            // data 속성이나 텍스트에서 rc_idx 확인
            var itemRcIdx = $item.data('rc_idx') || $item.find('[data-rc_idx]').data('rc_idx');
            if (itemRcIdx == autoOpenRcIdx) {
              targetRmIdx = $item.data('target_id') || $item.attr('data-target_id');
              return false; // break
            }
          });
          
          // DOM에서 찾지 못한 경우 API 응답 데이터에서 찾기
          if (!targetRmIdx) {
            for (var i = 0; i < chatList.length; i++) {
              var item = chatList[i];
              if (item.rc_idx == autoOpenRcIdx) {
                if (item.rm_idx) {
                  targetRmIdx = item.rm_idx;
                  break;
                } else if (item.target_id) {
                  targetRmIdx = item.target_id;
                  break;
                }
              }
            }
          }
          
          // 채팅방을 찾았으면 자동으로 진입 (메시지 자동 전송 플래그 포함)
          if (targetRmIdx) {
            location.href = "/chat/chat_view?rm_idx=" + targetRmIdx + "&auto_send=true&rc_idx=" + autoOpenRcIdx + "&from=applied";
          }
        }, 500); // 목록 렌더링 대기
      }
    });
  }

  // 보낸다이렉트채팅 목록 로드
  function fSearchDirectChatListSentMem() {
    $.ajax({
      url: "/chat_proc/get_direct_chat_list_sent_mem",
      type: "POST",
      dataType: "json",
      success: function(ret) {
        if (ret.code == "00") {
          var directChatList = ret.data.list || [];
          var $directList = $("#direct_chat_list_sent");

          $directList.empty();
          window.__chatDirectRmIdxSetSent = new Set();

          directChatList.forEach(function(row) {
            var $clone = $("#tpl_chat_mem").clone();
            var $content = $clone.html();

            $content = $content.replaceAll("{{room_idx}}", row.rm_idx || "");
            $content = $content.replaceAll("{{reg_dt}}", row.reg_dt || "");

            // 프로필 이미지 처리 (회사 이미지는 기본 처리만)
            var noImagePath = "/resource/shop/mobile/img/common/noimage4.png";
            var profileImg = (typeof row.target_img === "string") ? row.target_img.trim() : "";
            if (!profileImg || profileImg === "" || profileImg.indexOf("noimage4.png") !== -1) {
              profileImg = noImagePath;
            }
            $content = $content.replaceAll("{{target_img}}", profileImg);

            $content = $content.replaceAll("{{rc_thumb}}", row.rc_thumb || "");
            $content = $content.replaceAll("{{mm_comp_name}}", row.mm_comp_name || "");
            $content = $content.replaceAll("{{rc_name}}", row.rc_name || "");
            $content = $content.replaceAll("{{rt_recom_badge}}", row.rt_recom_badge || "");
            $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img || "");
            $content = $content.replaceAll("{{last_msg_preview}}", (row.last_msg_preview || "").replace(/\n/g, "<br/>"));
            $content = $content.replaceAll("{{up_dt}}", row.up_dt || "");

            $clone.html($content);
            $directList.append("<div class='recruitbox_'>" + $clone.html() + "</div>");
            window.__chatDirectRmIdxSetSent.add(String(row.rm_idx));
          });

          if ($directList.find("li").length == 0) {
            $("#direct_chat_empty_sent").show();
            $directList.hide();
          } else {
            $("#direct_chat_empty_sent").hide();
            $directList.show();
          }

          $("#direct_chat_list_sent .last-chat-time").each(function() {
            $(this).text($(this).attr('data-time'));
          });

          // 모든 렌더링 완료 후 empty 상태 재평가
          var chatListCount = $("#chat_list").find("li").length;
          var directChatCount = $("#direct_chat_list_sent").find("li").length;

          if (chatListCount === 0 && directChatCount === 0) {
            $("#chat_empty1").show();
          } else {
            $("#chat_empty1").hide();
          }
        }
      }
    });
  }

  // 지원한공고 목록의 미확인 채팅건수 조회
  function fSearchNoReadCount() {
    comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
      var myId = $("#user_id").val();
      let result = ret.data.result;

      // 탭별 읽지 않은 메시지 개수 계산
      var tab1UnreadCount = 0; // 지원한공고 탭
      var tab2UnreadCount = 0; // 제안받은공고 탭

      $.each(result, function(key, room) {
        let count = 0;
        $.each(room.rm_messages, function(index, message) {
          $.each(message.mg_reads, function(i, readEntry) {
            if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
              count++;
            }
          });
        });

        // 탭 판별: DOM 존재 여부가 아니라 dedupe 이전 리스트 기반
        var belongsToThisTab = window.__chatTab1RmIdxSet && window.__chatTab1RmIdxSet.has(String(room.rm_idx));
        var $unreadElement = $('#chat_list #room_idx_unread_cnt_' + room.rm_idx);

        // DOM에 실제 렌더링된 요소(dedupe 생존자)에만 숫자 표시
        if ($unreadElement.length > 0) {
          $unreadElement.html(count);
          if (count > 0) {
            $unreadElement.show();
          } else {
            $unreadElement.hide();
          }
        }

        // 탭별 카운팅: 추적된 리스트 기반
        if (belongsToThisTab) {
          // 지원한공고 탭
          if (count > 0) {
            tab1UnreadCount += count;
          }
        } else {
          // 제안받은공고 탭
          if (count > 0) {
            tab2UnreadCount += count;
          }
        }
      });

      // 탭1 (지원한공고) 확인
      var hasUnreadTab1 = tab1UnreadCount > 0;
      if (hasUnreadTab1) {
        $('.page-chat .tab-list li:first-child').addClass('has-unread');
      } else {
        $('.page-chat .tab-list li:first-child').removeClass('has-unread');
      }

      // 탭2 (제안받은공고) 확인
      var hasUnreadTab2 = tab2UnreadCount > 0;
      if (hasUnreadTab2) {
        $('.page-chat .tab-list li:last-child').addClass('has-unread');
      } else {
        $('.page-chat .tab-list li:last-child').removeClass('has-unread');
      }
    });
  }

  // 지원한공고 목록 아이템 클릭 이벤트 처리
  $(document).on("click", ".room-item", function(e) {
    // fShowChatListByRecruite 함수가 있는 경우에는 바로 채팅방으로 이동하지 않음
    if ($(this).find('.angle-link[onclick*="fShowChatListByRecruite"]').length > 0) {
      return; // fShowChatListByRecruite 함수가 처리하도록 함
    }

    var target_id = $(this).data("target_id");
    var $unreadElement = $('#room_idx_unread_cnt_' + target_id);

    // 채팅방 클릭 시 카운트를 즉시 0으로 설정 (시각적 피드백)
    if ($unreadElement.length > 0) {
      $unreadElement.html(0).hide();
    }

    // 지원한공고 탭에서 채팅방으로 이동 (from=applied 파라미터 사용)
    location.href = "/chat/chat_view?rm_idx=" + target_id + "&from=applied";
  });
</script>

<!-- 지원한공고 탭 전용 템플릿 (개인회원용) -->
<template id="tpl_chat_mem">
  <li class="list-item msg-item room-item room_idx_{{room_idx}}" data-target_id="{{room_idx}}" data-regdt="{{reg_dt}}">
    <a href="#" class="angle-link js-open-view" data-regdt="{{reg_dt}}">
      <div class="content-body">
        <div class="chat-time-row">
            <div class="wicon">{{rt_recom_badge}}{{rt_work_img}}</div>
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
