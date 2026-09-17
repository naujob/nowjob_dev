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
          <li><a href="/chat/chat_list" class="tab">채팅보낸 공고<span class="tab-badge"></span></a></li>
          <li><a href="/chat/chat_list2" class="tab">채팅받은 공고<span class="tab-badge"></span></a></li>
          <li class="on"><a href="/chat/chat_list_direct" class="tab">보낸 채팅<span class="tab-badge"></span></a></li>
        <?php elseif ($user_type == "COMP") : ?>
          <li><a href="/chat/index_comp" class="tab">제안보낸 인재<span class="tab-badge"></span></a></li>
          <li><a href="/chat/chat_list3" class="tab">지원받은 인재<span class="tab-badge"></span></a></li>
          <li class="on"><a href="/chat/chat_list_direct" class="tab">받은 채팅<span class="tab-badge"></span></a></li>
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
        <!-- 채팅 탭 (MEM/COMP 양쪽) -->
        <h2 class="sr-text">채팅</h2>

        <div class="chat-list">
          <ol class="list result-list-card" id="chat_list_plain">

          </ol>
          <div class="chat-empty" id="chat_empty_plain">
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
  // 페이지 로드 시 "채팅" 목록 조회
  window.addEventListener('DOMContentLoaded', function(e) {
    getChatPlainList();
  });

  // 페이지 포커스 시 목록 갱신
  window.addEventListener('focus', function() {
    getChatPlainList();
  });

  // "채팅" 목록 조회 함수
  function getChatPlainList() {
    comAjax("/chat_proc/get_chat_target_list_plain", {}, function(ret) {
      $("#chat_list_plain").html("");

      if (ret.code == "00") {
        var list = ret.data || [];

        list.forEach(function(row) {
          var $clone = $("#tpl_chat_plain").clone();
          var $content = $clone.html();

          // 템플릿 변수 바인딩
          $content = $content.replaceAll("{{room_idx}}", row.rm_idx || "");

          // 사용자 타입별 상대방 정보 표시
          var targetName = "<?= $user_type === 'MEM' ? '' : '' ?>";
          if ("<?= $user_type ?>" === "MEM") {
            // 개인회원: 기업명 표시
            targetName = row.mm_comp_name || row.mm_name || "";
          } else {
            // 기업회원: 개인명 표시
            targetName = row.mm_name || "";
          }

          $content = $content.replaceAll("{{target_name}}", targetName);

          // 썸네일/프로필 이미지 (MEM: 기업 로고, COMP: 이력서 사진)
          var targetThumb = "<?= $user_type ?>" === "MEM"
            ? (row.mm_thumb || "/resource/shop/mobile/img/common/noimage4.png")
            : (row.ru_thumb || "/resource/shop/mobile/img/common/noimage4.png");
          $content = $content.replaceAll("{{target_thumb}}", targetThumb);

          // 마지막 메시지 (미리보기)
          $content = $content.replaceAll("{{last_msg_preview}}", (row.msg || "").replace(/\n/g, "<br/>"));

          // 마지막 메시지 시간
          $content = $content.replaceAll("{{up_dt}}", row.msg_time || "");

          // 복사한 내용 다시 넣기
          $clone.html($content);
          var boxHtml = "<div class='recruitbox_'>" + $clone.html() + "</div>";
          $("#chat_list_plain").append(boxHtml);
        });

        // 안읽음 배지 실시간 갱신 (기존 chat_list.php와 동일한 함수 재사용)
        if (typeof fSearchNoReadCount === 'function') {
          fSearchNoReadCount();
        }

        // 목록이 없을 경우 빈 화면 표시
        if ($("#chat_list_plain").find("li").length == 0) {
          $(".chat-empty").show();
          $("#chat_list_plain").hide();
        } else {
          $(".chat-empty").hide();
          $("#chat_list_plain").show();
        }
      } else {
        if (ret.code !== "10") {
          commonAlert("일시적인 조회 오류가 발생하였습니다.", function() {});
        }
      }
    });
  }
</script>

<template id="tpl_chat_plain">
  <li class="list-item msg-item room-item room_idx_{{room_idx}}" data-target_id="{{room_idx}}">
    <a href="/chat/chat_view?rm_idx={{room_idx}}&from=direct_chat" class="angle-link">
      <div class="content-body">
        <div class="chat-time-row">
          <div class="wicon"></div>
          <div class="last-chat-time" data-time="{{up_dt}}"></div>
        </div>
        <div class="img" style="border-radius: 60px;">
          <img src="{{target_thumb}}" alt="" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
        </div>
        <div class="desc">
          <div class="title ellipsis">{{target_name}}</div>
          <div class="summary-with-badge">
            <div class="summary">
              <p class="last-message">{{last_msg_preview}}</p>
            </div>
            <span class="room_unread_cnt" id="room_idx_unread_cnt_{{room_idx}}"></span>
          </div>
        </div>
      </div>
    </a>
  </li>
</template>
