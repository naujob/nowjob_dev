<div class="page page-chat">
  <div class="header">
    <h1 class="title">채팅방</h1>
  </div><!-- //header -->

  <div class="page-content">

    <div class="tab-container">
      <ul class="tab-list">
        <?php if ($user_type == "COMP") : ?>
          <li class="on"><a href="/chat/chat_list" class="tab">채용공고</a></li>
          <li><a href="/chat/index_comp" class="tab">제안보낸인재</a></li>
        <?php endif; ?>
        <?php if ($user_type == "MEM") : ?>
          <li class="on"><a href="/chat/chat_list" class="tab">지원한공고</a></li>
          <li><a href="/chat/chat_list2" class="tab">제안받은공고</a></li>
          <!--<li><a href="/chat/index_comp" class="tab">지원자목록</a></li>
                    <li class="on"><a href="/chat/chat_list" class="tab">1:1 채팅</a></li>-->
        <?php endif; ?>
        <!--<li><a href="/chat/trade_list" class="tab">중고거래</a></li>-->
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
    </style>
    <style>
      .room_unread_cnt {
        position: absolute;
        width: 26px;
        height: 26px;
        background-color: #F86048;
        top: 51px;
        right: 30px;
        border-radius: 100%;
        color: #fff;
        text-align: center;
        line-height: 25px;
        display: none;
      }
    </style>
    <div class="tab-panel">
      <div class="container">
        <h2 class="sr-text">채용공고</h2>

        <div class="chat-list">
          <ol class="list" id="chat_list">

          </ol>
          <div class="chat-empty" id="chat_empty1" style="">
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
  var g_user_id = "";
  window.addEventListener('DOMContentLoaded', function(e) {

    getChatTargetList(1);
    //룸정보 가져오기
    //g_user_id = $("#user_id").val();
    //socket.emit("room_list",{user_id : g_user_id,rm_type : "chat"});
  });

  function getChatTargetList(arg) {

    //지원자목록-공고목록
    //alert(arg);
    var getUrl = "get_chat_target_list_recruit";
    <?php if ($user_type == "MEM") { ?>
      getUrl = "get_chat_target_list_recruit_mem";
    <?php } ?>
    <?php if ($user_type == "COMP") { ?>
      getUrl = "get_chat_target_list_recruit";
    <?php } ?>

    comAjax("/chat_proc/" + getUrl, {}, function(ret) { //_recruit

      //$("#chat_list"+arg).html("");
      $("#chat_list").html("");

      if (ret.code == "00") {
        var list = ret.data.list;
        list.forEach(function(row) {

          <?php if ($user_type == "MEM") { ?>
            var $clone = $("#tpl_chat_mem").clone();
          <?php } ?>
          <?php if ($user_type == "COMP") { ?>
            var $clone = $("#tpl_chat").clone();
          <?php } ?>

          var $content = $clone.html();

          $content = $content.replaceAll("{{room_idx}}", row.rm_idx);
          $content = $content.replaceAll("{{room_idx_list}}", row.rm_idx_list);
          $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
          $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
          $content = $content.replaceAll("{{mm_name}}", row.mm_name);
          $content = $content.replaceAll("{{ru_week}}", row.ru_week);
          $content = $content.replaceAll("{{distance}}", row.distance + "km");
          $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);
          $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
          $content = $content.replaceAll("{{ma_age}}", row.ma_age);
          $content = $content.replaceAll("{{ru_price_type}}", row.ru_price_type);
          $content = $content.replaceAll("{{ru_my_type}}", row.ru_my_type);
          $content = $content.replaceAll("{{ru_work_type}}", row.ru_work_type);
          $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
          $content = $content.replaceAll("{{mm_id}}", row.mm_id);
          $content = $content.replaceAll("{{ad_addr}}", row.ad_addr);
          $content = $content.replaceAll("{{target_id}}", row.target_id);
          $content = $content.replaceAll("{{user_id}}", row.user_id);
          $content = $content.replaceAll("{{target_img}}", row.target_img);
          $content = $content.replaceAll("{{user_img}}", row.user_img);
          $content = $content.replaceAll("{{target_name}}", row.target_name);
          $content = $content.replaceAll("{{user_name}}", row.user_name);

          $content = $content.replaceAll("{{rc_name}}", row.rc_name);
          $content = $content.replaceAll("{{rc_thumb}}", row.rc_thumb);
          $content = $content.replaceAll("{{reg_dt}}", row.reg_dt);
          $content = $content.replaceAll("{{rc_work}}", row.rc_work);

          var nationStr = '';
          nationStr = row.rc_nation;
          if (row.rc_nation != "" && (row.rc_sage != "0" || row.rc_eage != "0")) {
            nationStr += " | ";
          }
          if (row.rc_sage == "0") {
            nationStr += row.rc_sage + "세";
          }
          if (row.rc_sage == "0" && row.rc_eage == "0") {
            nationStr += "";
          } else {
            nationStr += " ~ ";
          }
          if (row.rc_eage == "0") {
            nationStr += "";
          } else {
            nationStr += row.rc_eage + "세";
          }
          $content = $content.replaceAll("{{rc_nation}}", nationStr);

          //복사한 내용 다시 넣기
          $clone.html($content);

          //$("#chat_list"+arg).append($clone.html());
          $("#chat_list").append($clone.html());

        });

        if ($("#chat_list").find("li").length == 0) {
          $(".chat-empty").show();
          $("#chat_list").hide();
        }

      } else {
        commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
        return;
      }

      //fSearchNoReadCount();
      //alert($("#user_id").val());
      //socket.emit("room_list",{user_id : $("#user_id").val(), rm_type : "chat"});
      //alert($("#user_id").val());
      fSearchNoReadCount();

    });

  } //getChatTargetList

  function fSearchNoReadCount() {

    //공고리스트에 미확인 채팅건수 조회 확인
    comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
      console.log(ret);
      var myId = $("#user_id").val();

      let result = ret.data.result;

      $.each(result, function(key, room) {
        //console.log(room);
        let count = 0;
        $.each(room.rm_messages, function(index, message) {
          $.each(message.mg_reads, function(i, readEntry) {
            if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
              count++;
            }
          });
        });
        //console.log(`rm_idx ${room.rm_idx}: ${count} unread messages`);
        //alert(room.rm_idx);

        $('#room_idx_unread_cnt_' + room.rm_idx).html(count);
        //alert($('#room_idx_unread_cnt_'+room.rm_idx).html());
        if (count > 0) {
          $('#room_idx_unread_cnt_' + room.rm_idx).show();
        }
      });


    });
  }

  //더블클릭 이벤트 처리
  $(document).on("click", ".room-item", function(e) {
    // fShowChatListByRecruite 함수가 있는 경우에는 바로 채팅방으로 이동하지 않음
    if ($(this).find('.angle-link[onclick*="fShowChatListByRecruite"]').length > 0) {
      return; // fShowChatListByRecruite 함수가 처리하도록 함
    }

    var target_id = $(this).data("target_id");
    location.href = "/chat/chat_view?rm_idx=" + target_id;
  });

  /////////////////////////////////////////////////////////
  // 리턴 처리
  /////////////////////////////////////////////////////////

  //회원리스트 가져오기
  socket.on("room_list_return", function(ret) {
    alert('room_list_return');
    g_user_id = $("#user_id").val();
    $("#chat_list").html("");
    if (ret != null) {
      for (var icnt = 0; icnt < ret.length; icnt++) {
        var img_html = getRoomImg(ret[icnt], g_user_id);
        var subject = getRoomSubject(ret[icnt], g_user_id);
        var desc = getRoomDesc(ret[icnt], "chat");
        var not_read = ret[icnt].not_read;

        var html = "";
        html += "<li class='list-item msg-item room-item'  data-target_id='" + ret[icnt].rm_idx + "'>";
        html += "    <a href='#' class='angle-link js-detect-touch'>";
        html += "        <div class='thumb'><img src='" + img_html + "' alt='' ></div>";
        html += "        <div class='mid'>";
        html += "            <p class='name'>" + subject + "</p>";
        html += "            <p class='latest'>" + desc + "</p>";
        html += "        </div>";
        html += "        <div class='tail'>";
        html += "            <p class='time'>" + getFormatDate(new Date(ret[icnt].rm_last_dt), 'chat') + "</p>";
        if (not_read > 0) {
          html += "    <p class='badge'><span>" + not_read + "</span></p>";
        }

        html += "        </div>";
        html += "    </a>";
        html += "    <button type='button' onclick='deleteItem(this.parentNode);' class='btn-primary btn-trash js-del-item'><span class='sr-text'>삭제</span></button>";
        html += "</li>";

        //적용
        $("#chat_list").append(html);
        //alert(html);
      }
    }
  });


  //실시간 메시지 정보 리스트
  socket.on("message_list_refresh", function(ret) {
    //alert('message_list_refresh');
    g_user_id = $("#user_id").val();
    socket.emit("room_list", {
      user_id: g_user_id,
      rm_type: "chat"
    });
  });

  //[공통] : 알림 메시지 수신
  socket.on("realtime_message", function(ret) {
    if (ret.type == "chat") {
      //alert(ret.not_read);
    }
  });
</script>

<template id="tpl_chat_mem">
  <li class="list-item msg-item room-item room_idx_{{room_idx}}" data-target_id="{{room_idx}}" data-regdt="{{reg_dt}}">
    <a href="#" class="angle-link js-open-view" style="padding:0;" data-regdt="{{reg_dt}}">
      <div class="item-info person" style="width:100%;">
        <div class="thumb-wrap">
          <div class="thumb" style="border-radius: unset;">
            <img src="{{rc_thumb}}" alt="">
          </div>
        </div>
        <div class="content-wrap">
          <div class="timestamp" style="font-size: 12px; color: #777;">등록일 : {{reg_dt}}</div>
          <div class="inline-group" style="height: 20px; font-size: 12px; color: #777;">
            <!-- 직종라벨 -->
            <p class="category">{{rc_work}}</p>
          </div>
          <div class="title-wrap">
            <p class="title"><strong>{{rc_name}}</strong></p>
          </div>
          <!-- 추가조건 -->
          <div class="meta" style="font-size: 12px;">
            <!-- 근무시간 -->
            <p>{{rc_nation}}</p>
          </div>
        </div>
      </div>
    </a>
    <span class="room_unread_cnt" id="room_idx_unread_cnt_{{room_idx}}"></span>
  </li>
</template>