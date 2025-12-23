<div class="page page-chat">
  <div class="header">
    <h1 class="title">채팅방</h1>
  </div><!-- //header -->

  <div class="page-content">

    <div class="tab-container">
      <ul class="tab-list">
        <?php if ($user_type == "MEM") : ?>
          <li class="on"><a href="/chat/chat_list" class="tab">지원한공고</a></li>
          <li><a href="/chat/chat_list2" class="tab">제안받은공고</a></li>
        <?php endif; ?>
      </ul>
    </div>

    <style>
      .chat-empty {
        position: absolute;
        top: 28.518%;
        left: 0;
        right: 0;
        font-size: 0;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        display: none;
      }

      .chat-empty:before {
        content: "";
        display: inline-block;
        height: 100%;
        vertical-align: middle;
      }

      .chat-empty p {
        display: inline-block;
        padding: 138px 0 0;
        font-size: 20px;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        background: url(/resource/shop/web/img/empty_chat.svg) no-repeat 50% 0;
        background-size: 115px auto;
        color: #777;
      }

      /* content-wrap 클래스에 왼쪽 마진 추가 */
      .content-wrap {
        margin-left: 1rem;
      }
    </style>
    <div class="tab-panel">
      <div class="container">
        <!-- 지원한공고 탭 전용 페이지 (개인회원용) -->
        <h2 class="sr-text">지원한공고</h2>

        <div class="chat-list">
          <ol class="list" id="chat_list">

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
  // 페이지 로드 시 지원한공고 목록 조회
  window.addEventListener('DOMContentLoaded', function(e) {
    getChatTargetList();
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
        list.forEach(function(row) {
          var $clone = $("#tpl_chat_mem").clone();
          var $content = $clone.html();

          // 템플릿 변수 바인딩
          $content = $content.replaceAll("{{room_idx}}", row.rm_idx || "");
          $content = $content.replaceAll("{{reg_dt}}", row.reg_dt || "");
          $content = $content.replaceAll("{{rc_thumb}}", row.rc_thumb || "");
          $content = $content.replaceAll("{{user_name}}", row.user_name || "");
          
          var rcName = truncateRcTitle(row.rc_name || "");
          $content = $content.replaceAll("{{rc_name}}", rcName);
          
          // 마지막 메시지 바인딩
          $content = $content.replaceAll("{{last_msg_preview}}", (row.last_msg_preview || "").replace(/\n/g, "<br/>"));

          // 복사한 내용 다시 넣기
          $clone.html($content);
          $("#chat_list").append($clone.html());
        });

        // 목록이 없을 경우 빈 화면 표시
        if ($("#chat_list").find("li").length == 0) {
          $(".chat-empty").show();
          $("#chat_list").hide();
        }
      } else {
        commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
        return;
      }

      // 미확인 채팅건수 조회
      fSearchNoReadCount();
    });
  }

  // 지원한공고 목록의 미확인 채팅건수 조회
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

        // 현재 페이지의 chat_list 컨테이너 내에서만 요소 찾기
        var $unreadElement = $('#chat_list #room_idx_unread_cnt_' + room.rm_idx);
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
    <a href="#" class="angle-link js-open-view" style="padding:0;" data-regdt="{{reg_dt}}">
      <div class="item-info person" style="width:100%; position: relative;">
        <div class="thumb-wrap">
          <div class="thumb" style="border-radius: 50%; overflow: hidden;">
            <img src="{{rc_thumb}}" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
          </div>
        </div>
        <div class="content-wrap">
          <div style="font-size: 12px; color: #777; margin-top: 4px;">
            <!-- 회사이름 -->
            <p>{{user_name}}</p>
          </div>
          <div class="title-wrap">
            <p class="title"><strong>{{rc_name}}</strong></p>
          </div>
          <!-- 추가조건 -->
          <div class="meta" style="font-size: 12px;">
            <!-- 마지막 메시지 -->
            <p class="latest-msg-text" style="color: #999; margin-top: 4px;">{{last_msg_preview}}</p>
          </div>
        </div>
      </div>
    </a>
    <span class="room_rec_unread_cnt" id="room_idx_unread_cnt_{{room_idx}}"></span>
  </li>
</template>
