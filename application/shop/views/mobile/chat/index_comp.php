<?php
$top_bar = array(
    'title' => '채팅방'
);
?>
<div class="page page-chat">
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/top_bar.php'); ?>
  <div class="page-content">
    <div class="tab-container">
      <ul class="tab-list">
        <li class="on"><a href="/chat/index_comp" class="tab">제안보낸 인재<span class="tab-badge"></span></a></li>
        <li><a href="/chat/chat_list3" class="tab">지원받은 인재<span class="tab-badge"></span></a></li>
        <!--<li><a href="/chat/trade_list" class="tab">중고거래</a></li>-->
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
        <!-- 제안받은 인재 탭 전용 페이지 (기업회원용) -->
        <h2 class="sr-text">제안받은 인재</h2>
        <div class="chat-list">
          <ol class="list result-list-card" id="chat_list">
          </ol>

          <div class="chat-empty" id="chat_empty1">
            <p>목록이 없습니다.</p>
          </div>

        </div>

        <!-- 받은다이렉트채팅 섹션 -->
        <div class="direct-chat-list-section" style="display: none;">
          <h3 class="section-title">받은채팅</h3>
          <div class="chat-list">
            <ol class="list result-list-card" id="direct_chat_list_received_comp">

            </ol>
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

  // (즉기업에게 제안을 보낸) 제안받은 인재 목록 조회 함수 (기업회원 전용) - 평탄화 버전
  function getChatTargetList(arg) {
    // 이미 로딩 중이면 중복 요청 방지
    if (isLoadingChatList) {
      return;
    }

    isLoadingChatList = true;

    // 공고 목록 먼저 조회
    var getUrl = "get_chat_target_list_recruit";

    comAjax("/chat_proc/" + getUrl, {}, function(ret) {
      $("#chat_list").html("");

      if (ret.code == "00") {
        // console.log("공고 목록 조회 성공:", ret);
      //  console.log("공고 목록 조회 성공:", ret.data.list);
        var recruitList = ret.data.list;
          
        if (recruitList.length == 0) {
          $(".chat-empty").show();
          $("#chat_list").hide();
          isLoadingChatList = false;
          return;
        }

        // 모든 공고의 지원자 조회 AJAX 요청들을 Promise로 묶기
        var ajaxPromises = [];

        // 각 공고에 대해 지원자 목록 조회 요청 생성 (comAjax 가드를 우회하기 위해 순수 $.ajax 사용)
        recruitList.forEach(function(recruitRow) {
          var rc_idx = recruitRow.rc_idx;

          var promise = $.ajax({
            type: "POST",
            url: "/chat_proc/get_chat_target_list",
            data: {
              ru_idx: rc_idx,
              chatgbn: 'chatPanel1'
            }
          }).then(function(data, textStatus, jqXHR) {
            // Promise 결과를 항상 [data, textStatus, jqXHR] 형태로 정규화
            // (공고가 1개일 때와 여러 개일 때 $.when의 동작이 다르므로 필요)
            return [data, textStatus, jqXHR];
          });

          ajaxPromises.push(promise);
        });

        // 모든 요청이 완료될 때까지 기다린 후 렌더링
        $.when.apply($, ajaxPromises).done(function() {
          // arguments는 각 $.ajax의 응답 배열 ([data, statusText, jqXHR], ...)
          var responses = Array.prototype.slice.call(arguments);

          // 모든 응답을 평탄화하여 하나의 배열로 통합
          var allRows = [];
          responses.forEach(function(response) {
            var talentRet = response[0]; // $.when의 결과는 [data, statusText, jqXHR] 형태
            if (talentRet && talentRet.code == "00") {
              var talentList = talentRet.data.list;
              if (talentList && Array.isArray(talentList)) {
                allRows = allRows.concat(talentList);
                


              }
            }
          });
           // console.log('allRows:', allRows);
          // 인재(mm_idx)별로 중복 제거하되, 가장 최근 지원(mq_apply_idx 최대)만 남기기
          var deduped = {};
          allRows.forEach(function(row) {
            var mmIdx = row.mm_idx;
            var applyIdx = parseInt(row.mq_apply_idx) || 0;

            if (!deduped[mmIdx] || applyIdx > parseInt(deduped[mmIdx].mq_apply_idx || 0)) {
              deduped[mmIdx] = row;
            }
          });

          // dedupe 이전의 전체 rm_idx를 추적 (탭 판별용)
          var currentTabRmIdxSet = new Set();
          allRows.forEach(function(row) {
            currentTabRmIdxSet.add(String(row.rm_idx));
          });
          window.__chatTab1RmIdxSet = currentTabRmIdxSet; // 제안보낸 인재 탭의 모든 rm_idx

          // deduped 객체를 배열로 변환
          var dedupedList = Object.keys(deduped).map(function(key) {
            return deduped[key];
          });

          // 각 지원자를 tpl_chat_by_recruite로 렌더링
          dedupedList.forEach(function(row) {
                var $clone = $("#tpl_chat_by_recruite").clone();
                if ($clone.length == 0) {
                  return;
                }

                var $content = $clone.html();

                $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
                $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
                $content = $content.replaceAll("{{mm_name}}", row.mm_name);
                $content = $content.replaceAll("{{ru_week}}", row.ru_week);
                $content = $content.replaceAll("{{distance}}", (row.distance ? row.distance + "km" : ""));
                $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);
                $content = $content.replaceAll("{{ru_recom_badge}}", row.ru_recom_badge || "");
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
                $content = $content.replaceAll("{{room_idx}}", row.rm_idx);
                $content = $content.replaceAll("{{up_dt}}", row.reg_dt);
                $content = $content.replaceAll("{{last_msg_preview}}", row.last_msg_preview || "");

                // 프로필 이미지 처리
                var maleAvatarPath = "/resource/shop/web/img/avatar_icon2_m.png";
                var femaleAvatarPath = "/resource/shop/web/img/avatar_resume_w.png";
                var noImagePath = "/resource/shop/mobile/img/common/noimage4.png";
                var profileImg = (typeof row.ru_resume_img === "string") ? row.ru_resume_img.trim() : "";
                var mmSex = (row.mm_sex || "").trim();
                var defaultAvatar = noImagePath;
                if (mmSex === "남") {
                  defaultAvatar = maleAvatarPath;
                } else if (mmSex === "여") {
                  defaultAvatar = femaleAvatarPath;
                }
                if (!profileImg || profileImg === "" || profileImg.indexOf("noimage4.png") !== -1) {
                  profileImg = defaultAvatar;
                }
                $content = $content.replaceAll("{{profile_img}}", profileImg);
                $content = $content.replaceAll("{{default_avatar}}", defaultAvatar);

                // 이름 처리
                var ruName = row.ru_name || "";
                if (ruName && ruName.length > 18) {
                  ruName = ruName.substring(0, 18) + '...';
                }
                $content = $content.replaceAll("{{ru_name}}", ruName);

                $clone.html($content);
                var resumeboxHtml = "<div class='resumebox_'>" + $clone.html() + "</div>";
                $("#chat_list").append(resumeboxHtml);
              });

          // PHP에서 이미 포맷팅된 시간을 표시
          $("#chat_list .last-chat-time").each(function() {
            $(this).text($(this).attr('data-time'));
          });

          // 목록이 없어도 일단 숨김 (다이렉트 채팅 로드 후 최종 판단)
          // 렌더링 중 깜빡임 방지
          var chatListParent = $("#chat_list").parent();
          chatListParent.show();
          chatListParent.css("display", "block");
          fSearchNoReadCount();
          isLoadingChatList = false; // 로딩 완료

          // 기본 목록 로드 완료 후 다이렉트 채팅 로드 (순서 보장)
          if (typeof fSearchDirectChatListReceivedComp === 'function') {
            fSearchDirectChatListReceivedComp();
          }
        }).fail(function() {
          commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
          isLoadingChatList = false; // 에러 발생 시에도 플래그 리셋
        });

      } else {
        commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
        isLoadingChatList = false; // 에러 발생 시에도 플래그 리셋
        return;
      }

    });

  }

  // 받은다이렉트채팅 목록 로드 (기업회원이 받은 것)
  function fSearchDirectChatListReceivedComp() {
    $.ajax({
      type: "POST",
      url: "/chat_proc/get_direct_chat_list_received_comp",
      data: {},
      dataType: "json",
      success: function(ret) {
        if (ret.code == "00") {
          var directChatList = ret.data.list || [];
          var $directList = $("#direct_chat_list_received_comp");

          $directList.empty();
          window.__chatDirectRmIdxSetReceivedComp = new Set();

          directChatList.forEach(function(row) {
            var $clone = $("#tpl_chat_by_recruite").clone();
            var $content = $clone.html();

            $content = $content.replaceAll("{{room_idx}}", row.rm_idx || "");
            $content = $content.replaceAll("{{rm_idx}}", row.rm_idx || "");
            $content = $content.replaceAll("{{reg_dt}}", row.reg_dt || "");
            $content = $content.replaceAll("{{ru_name}}", row.target_name || "");
            $content = $content.replaceAll("{{target_name}}", row.target_name || "");
            $content = $content.replaceAll("{{target_id}}", row.target_id || "");
            $content = $content.replaceAll("{{rc_name}}", row.rc_name || "");

            // 프로필 이미지 처리 (성별 기반 기본 아바타)
            var maleAvatarPath = "/resource/shop/web/img/avatar_icon2_m.png";
            var femaleAvatarPath = "/resource/shop/web/img/avatar_resume_w.png";
            var noImagePath = "/resource/shop/mobile/img/common/noimage4.png";
            var profileImg = (typeof row.target_img === "string") ? row.target_img.trim() : "";
            var mmSex = (row.mm_sex || "").trim();
            var defaultAvatar = noImagePath;
            if (mmSex === "남") {
              defaultAvatar = maleAvatarPath;
            } else if (mmSex === "여") {
              defaultAvatar = femaleAvatarPath;
            }
            if (!profileImg || profileImg === "" || profileImg.indexOf("noimage4.png") !== -1) {
              profileImg = defaultAvatar;
            }
            $content = $content.replaceAll("{{target_img}}", profileImg);
            $content = $content.replaceAll("{{default_avatar}}", defaultAvatar);

            $content = $content.replaceAll("{{ru_work_type}}", "");
            $content = $content.replaceAll("{{ru_recom_badge}}", "");
            $content = $content.replaceAll("{{ru_work_img}}", "");
            $content = $content.replaceAll("{{last_msg_preview}}", (row.last_msg_preview || "").replace(/\n/g, "<br/>"));
            $content = $content.replaceAll("{{up_dt}}", row.reg_dt || "");

            $clone.html($content);
            $directList.append("<div class='recruitbox_'>" + $clone.html() + "</div>");
            window.__chatDirectRmIdxSetReceivedComp.add(String(row.rm_idx));
          });

          if ($directList.find("li").length == 0) {
            $directList.closest('.direct-chat-list-section').hide();
          } else {
            $directList.closest('.direct-chat-list-section').show();
          }

          $("#direct_chat_list_received_comp .last-chat-time").each(function() {
            $(this).text($(this).attr('data-time'));
          });

          // 모든 렌더링 완료 후 empty 상태 재평가
          var chatListCount = $("#chat_list").find("li").length;
          var directChatCount = $("#direct_chat_list_received_comp").find("li").length;

          if (chatListCount === 0 && directChatCount === 0) {
            $("#chat_empty1").show();
          } else {
            $("#chat_empty1").hide();
          }
        }
      }
    });
  }
</script>
<?php if ($this->common->getSession("os_type") == "android") : ?>
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

    // 안드로이드에서도 채팅방 목록을 가져오도록 추가
    window.addEventListener('DOMContentLoaded', function() {
      getChatTargetList(1);
      fSearchDirectChatListReceivedComp();
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
        } else {
        }
      }

      // 뱃지는 항상 갱신
      fSearchNoReadCount();
    });
    /**
     * [사용 안 됨] 레거시 함수 - APP_NOWAPK 콜백용
     * 안드로이드 앱에서 APP_NOWAPK.room_insert_return()으로 호출할 수 있지만,
     * 현재 로그에서 호출되지 않음
     * 채팅방 생성 후 리다이렉트 처리용이지만, 실제로는 다른 방식으로 처리됨
     * 
     * @deprecated 사용되지 않음 - 채팅방 생성 로직이 변경됨
     */
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
      // 현재 페이지의 chat_list 컨테이너 내의 content-li만 초기화
      $('#chat_list .content-li').each(function() {
        $(this).attr('data-noreadcnt', '0');
      });

      //공고리스트에 미확인 채팅건수 조회 확인
      comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
        var myId = $("#user_id").val();
        let result = ret.data.result;

        // 탭별 읽지 않은 메시지 개수 계산
        var tab1UnreadCount = 0; // 지원한 인재 탭
        var tab2UnreadCount = 0; // 제안한 인재 탭

        // 현재 페이지(#chat_list)에 실제로 렌더링된 채팅방(rm_idx) 목록 수집 (자기 탭 판별용)
        var tab1RoomIdxList = [];
        $('#chat_list li[data-target_id]').each(function() {
          var rmIdx = Number($(this).attr('data-target_id'));
          if (!isNaN(rmIdx) && rmIdx > 0 && tab1RoomIdxList.indexOf(rmIdx) === -1) {
            tab1RoomIdxList.push(rmIdx);
          }
        });

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
          var $unreadElement = $('#chat_list #room_idx_unread_cnt_' + room.rm_idx);
          if ($unreadElement.length > 0) {
            // 현재 페이지에 있는 채팅방 (지원한 인재 탭)
            $unreadElement.html(count);
            if (count > 0) {
              $unreadElement.show();
            } else {
              $unreadElement.hide();
            }
          }

          // 탭별 읽지 않은 메시지 개수 계산 (추적된 리스트 기반)
          var belongsToThisTab = window.__chatTab1RmIdxSet && window.__chatTab1RmIdxSet.has(String(room.rm_idx));
          if (belongsToThisTab) {
            // 제안보낸 인재 탭 (탭1)
            if (count > 0) {
              tab1UnreadCount += count;
              // 상위 공고 목록의 뱃지에 카운트 추가
              fSetNoReadCntRecruit(room.rm_idx, count);
            }
          } else {
            // 지원받은 인재 탭 (탭2)
            if (count > 0) {
              tab2UnreadCount += count;
            }
          }
        });

        // 모든 room 처리 완료 후 상위 공고 목록의 뱃지 업데이트
        $('#chat_list .content-li').each(function() {
          let noReadCnt = parseInt($(this).attr('data-noreadcnt')) || 0;
          let parentLi = $(this).closest('li');
          let $badge = parentLi.find('.room_rec_unread_cnt');

          if (noReadCnt > 0) {
            $badge.text(noReadCnt);
            $badge.show();
          } else {
            $badge.hide();
          }
        });

        // 탭1 (지원한 인재) 확인
        var hasUnreadTab1 = tab1UnreadCount > 0;
        if (hasUnreadTab1) {
          $('.page-chat .tab-list li:first-child').addClass('has-unread');
        } else {
          $('.page-chat .tab-list li:first-child').removeClass('has-unread');
        }

        // 탭2 (제안한 인재) 확인
        var hasUnreadTab2 = tab2UnreadCount > 0;
        if (hasUnreadTab2) {
          $('.page-chat .tab-list li:last-child').addClass('has-unread');
        } else {
          $('.page-chat .tab-list li:last-child').removeClass('has-unread');
        }
      });
    }

    function fSetNoReadCntRecruit(room_idx, cnt) {
      // 현재 페이지의 chat_list 컨테이너 내의 content-li만 처리
      $('#chat_list .content-li').each(function() {
        let roomIdxList = $(this).attr('data-room_idx_list');
        if (roomIdxList) {
          // 괄호만 제거하고 콤마로 분리하여 숫자 배열 생성
          let roomIdxArray = roomIdxList.replace(/[()]/g, '').split(',').map(function(idx) {
            return Number(idx.trim());
          }).filter(function(idx) {
            return !isNaN(idx) && idx > 0;
          });
          if (roomIdxArray.includes(Number(room_idx))) {
            let noReadCnt = parseInt($(this).attr('data-noreadcnt')) || 0;
            let additionalValue = cnt;
            let newNoReadCnt = noReadCnt + additionalValue;
            $(this).attr('data-noreadcnt', newNoReadCnt);
          }
        }
      });
    }
  </script>
<?php else: ?>
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

    // 페이지 로드 시 제안받은 인재 목록 조회 및 실시간 업데이트 초기화
    window.addEventListener('DOMContentLoaded', function() {
      getChatTargetList(1);
      fSearchDirectChatListReceivedComp();
      initRealTimeTabBadgeUpdate();

      document.addEventListener("touchmove", function(e) {
        // 기본 동작을 차단하지 않음
      }, {
        passive: true
      }); // passive: true로 설정하면 기본 스크롤 허용
    });

    // 페이지 포커스 시 뱃지만 갱신 (목록 갱신은 pageshow에서 처리)
    window.addEventListener('focus', function() {
      fSearchNoReadCount();
    });

    // pageshow 이벤트 추가 (뒤로가기/앞으로가기 대응)
    window.addEventListener('pageshow', function(event) {

      // BFCache에서 복원된 경우에만 목록 새로고침
      if (event.persisted) {
        // 진행 중이 아니면 로드 시작
        if (!isLoadingChatList) {
          getChatTargetList(1);
        }
        fSearchNoReadCount();
      }
    });


    function fSearchNoReadCount() {

      // 현재 페이지의 chat_list 컨테이너 내의 content-li만 초기화
      $('#chat_list .content-li').each(function() {
        $(this).attr('data-noreadcnt', '0');
      });

      //공고리스트에 미확인 채팅건수 조회 확인
      comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
        var myId = $("#user_id").val();

        let result = ret.data.result;

        // 탭별 읽지 않은 메시지 개수 계산
        var tab1UnreadCount = 0; // 지원한 인재 탭
        var tab2UnreadCount = 0; // 제안한 인재 탭

        // 현재 페이지(#chat_list)에 실제로 렌더링된 채팅방(rm_idx) 목록 수집 (자기 탭 판별용)
        var tab1RoomIdxList = [];
        $('#chat_list li[data-target_id]').each(function() {
          var rmIdx = Number($(this).attr('data-target_id'));
          if (!isNaN(rmIdx) && rmIdx > 0 && tab1RoomIdxList.indexOf(rmIdx) === -1) {
            tab1RoomIdxList.push(rmIdx);
          }
        });

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
          //alert(room.rm_idx);

          // 하위 인재 리스트의 개별 채팅방 뱃지 업데이트 (DOM에 있는 경우)
          var $unreadElement = $('#chat_list #room_idx_unread_cnt_' + room.rm_idx);
          if ($unreadElement.length > 0) {
            // 현재 페이지에 있는 채팅방 (지원한 인재 탭)
            $unreadElement.html(count);
            if (count > 0) {
              $unreadElement.show();
            } else {
              $unreadElement.hide();
            }
          }

          // 탭별 읽지 않은 메시지 개수 계산 (추적된 리스트 기반)
          var belongsToThisTab = window.__chatTab1RmIdxSet && window.__chatTab1RmIdxSet.has(String(room.rm_idx));
          if (belongsToThisTab) {
            // 제안보낸 인재 탭 (탭1)
            if (count > 0) {
              tab1UnreadCount += count;
              // 상위 공고 목록의 뱃지에 카운트 추가
              fSetNoReadCntRecruit(room.rm_idx, count);
            }
          } else {
            // 지원받은 인재 탭 (탭2)
            if (count > 0) {
              tab2UnreadCount += count;
            }
          }
        });

        // 모든 room 처리 완료 후 상위 공고 목록의 뱃지 업데이트
        $('#chat_list .content-wrap').each(function() {
          // 현재 div의 data-noreadcnt 값을 가져오기
          let noReadCnt = parseInt($(this).attr('data-noreadcnt')) || 0;

          // 상위 li 요소 찾기
          let parentLi = $(this).closest('li');
          let $badge = parentLi.find('.room_rec_unread_cnt');

          // data-noreadcnt 값이 0보다 큰 경우 표시, 아니면 숨김
          if (noReadCnt > 0) {
            $badge.text(noReadCnt);
            $badge.show();
          } else {
            $badge.hide();
          }
        });

        // 탭1 (지원한 인재) 확인
        var hasUnreadTab1 = tab1UnreadCount > 0;
        if (hasUnreadTab1) {
          $('.page-chat .tab-list li:first-child').addClass('has-unread');
        } else {
          $('.page-chat .tab-list li:first-child').removeClass('has-unread');
        }

        // 탭2 (제안한 인재) 확인
        var hasUnreadTab2 = tab2UnreadCount > 0;
        if (hasUnreadTab2) {
          $('.page-chat .tab-list li:last-child').addClass('has-unread');
        } else {
          $('.page-chat .tab-list li:last-child').removeClass('has-unread');
        }
      });
    }

    function fSetNoReadCntRecruit(room_idx, cnt) {

      // 현재 페이지의 chat_list 컨테이너 내의 content-wrap만 처리
      $('#chat_list .content-wrap').each(function() {
        // data-room_idx_list 속성 값 가져오기
        let roomIdxList = $(this).attr('data-room_idx_list');

        if (roomIdxList) {
          // 괄호만 제거하고 콤마로 분리하여 숫자 배열 생성
          let roomIdxArray = roomIdxList.replace(/[()]/g, '').split(',').map(function(idx) {
            return Number(idx.trim());
          }).filter(function(idx) {
            return !isNaN(idx) && idx > 0;
          });

          // 배열에 room_idx가 포함되어 있는지 확인 (숫자로 변환하여 비교)
          if (roomIdxArray.includes(Number(room_idx))) {
            // 현재 div의 data-noreadcnt 값을 가져와 정수로 변환
            let noReadCnt = parseInt($(this).attr('data-noreadcnt')) || 0;

            let additionalValue = cnt;

            // 계산된 값을 다시 data-noreadcnt에 설정
            let newNoReadCnt = noReadCnt + additionalValue;
            $(this).attr('data-noreadcnt', newNoReadCnt);

          }
        }
      });
    }

    // BFCache를 통해 페이지 접근할 경우 새로고침
    window.onpageshow = function(event) {
      if (event.persisted) {
        window.location.reload(); // 페이지 새로고침
      }
    };

    // [사용되지 않음] 소켓 기반 채팅방 생성 - 현재는 AJAX 기반으로 동작
    // 방생성 확인 및 방번호 확인
    // socket.on("room_insert_return", function(ret) {
    //   //채팅방 실시간 쏘기
    //   if (first_yn == "Y") {
    //     socket.emit("message_insert", first_message, first_users);
    //   }
    //   if (ret.code == "00") {
    //     location.href = "/chat/chat_list?view=true&rm_idx=" + ret.data;
    //   } else {
    //     commonAlert(ret.msg, function() {});
    //   }
    // });
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
    // fShowChatListByRecruite 함수가 있는 경우에는 바로 채팅방으로 이동하지 않음
    if ($(this).find('.angle-link[onclick*="fShowChatListByRecruite"]').length > 0) {
      return; // fShowChatListByRecruite 함수가 처리하도록 함
    }

    var target_id = $(this).data("target_id");
    location.href = "/chat/chat_view?rm_idx=" + target_id + "&backUrl=" + encodeURIComponent('/chat/index_comp');
  });
  // 채용공고에 지원 or 제안 인재정보
  function fShowChatListByRecruite(ru_idx, obj) {
    // 주의: ru_idx 파라미터명이지만 실제로는 채용공고 번호(rc_idx)를 받음
    // ru_idx = Resume ID (이력서 번호)
    // rc_idx = Recruit ID (채용공고 번호)
    var rc_idx = ru_idx; // 실제로는 채용공고 번호

    // 다른 항목이 열려있으면 자동으로 닫기
    // 현재 클릭한 항목이 아닌 모든 resumebox_ 제거 및 닫기
    $('[class*="resumebox_"]').each(function() {
      var $resumebox = $(this);
      var classList = $resumebox.attr('class') || '';
      var match = classList.match(/resumebox_(\d+)/);
      if (match && match[1] != rc_idx) {
        $resumebox.hide();
        $resumebox.remove();
        // 해당 항목의 angle-link에서 'on' 클래스 제거
        $('.angle-link[onclick*="fShowChatListByRecruite(\'' + match[1] + '\'"]').removeClass('on');
      }
    });

    // 토글 기능: 이미 인재 리스트가 보이면 숨기기
    // 같은 채용공고의 모든 인재 리스트를 숨김/표시 처리 (클래스로 선택)
    var $resumebox = $('.resumebox_' + rc_idx);
    var $angleLink = $(obj);

    if ($resumebox.length > 0 && $resumebox.is(':visible')) {
      // 인재 리스트가 이미 보이면 모두 숨기기
      $resumebox.hide(); // 모든 resumebox_ div 숨김
      $angleLink.removeClass('on'); // 아이콘 원래 상태로
      return;
    }

    // 인재 리스트가 숨겨져 있으면 다시 보이기
    if ($resumebox.length > 0 && $resumebox.is(':hidden')) {
      $resumebox.show(); // 모든 resumebox_ div 표시
      $angleLink.addClass('on'); // 아이콘 아래로 회전
      return;
    }

    // 인재 리스트가 없으면 보이기
    $angleLink.addClass('on'); // 아이콘 아래로 회전

    chatDetailShow = true;

    //지원자목록-공고목록
    var params = {};
    params["ru_idx"] = rc_idx; // 백엔드에서 ru_idx로 받지만 실제로는 rc_idx 값
    params["chatgbn"] = $(obj).parent().parent().parent().attr('id');
    params["chatgbn"] = 'chatPanel1';

    comAjax("/chat_proc/get_chat_target_list", params, function(ret) { // 인재목록데이터
      $("#chat_list1_1").html("");

      if (ret.code == "00") {
        var list = ret.data.list;
        var listCnt = 0;

        $('.resumebox_' + rc_idx).remove();

        list.forEach(function(row) {
          listCnt++;

          var requestRcIdx = String(rc_idx);
          var responseRcIdx = String(row.rc_idx || '');

          if (requestRcIdx === responseRcIdx || rc_idx == row.rc_idx) {
            var $clone = $("#tpl_chat_by_recruite").clone();
            var $content = $clone.html();

            $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
            $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
            $content = $content.replaceAll("{{mm_name}}", row.mm_name);
            $content = $content.replaceAll("{{ru_week}}", row.ru_week);
            $content = $content.replaceAll("{{distance}}", row.distance + "km");
            $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);
            $content = $content.replaceAll("{{ru_recom_badge}}", row.ru_recom_badge || "");
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
            $content = $content.replaceAll("{{target_name}}", row.target_name); //지원자 이름
            $content = $content.replaceAll("{{user_name}}", row.user_name);

            $content = $content.replaceAll("{{rc_name}}", row.rc_name);
            $content = $content.replaceAll("{{room_idx}}", row.rm_idx);
            $content = $content.replaceAll("{{target_name}}", row.target_name);
            $content = $content.replaceAll("{{up_dt}}", row.reg_dt);
            $content = $content.replaceAll("{{last_msg_preview}}", row.last_msg_preview || "");

            // getResumeImg() 함수와 동일한 로직 적용 (chat_view.php 참고)
            // 컨트롤러에서 이미 getResumeImg()로 처리된 값이 오므로, 실제 이미지 경로가 있으면 그대로 사용
            var maleAvatarPath = "/resource/shop/web/img/avatar_icon2_m.png";
            var femaleAvatarPath = "/resource/shop/web/img/avatar_resume_w.png";
            var noImagePath = "/resource/shop/mobile/img/common/noimage4.png";
            var profileImg = (typeof row.ru_resume_img === "string") ? row.ru_resume_img.trim() : "";
            var mmSex = (row.mm_sex || "").trim();
            var defaultAvatar = noImagePath; // 기본값은 noimage4.png (getResumeImg 함수와 동일)
            if (mmSex === "남") {
              defaultAvatar = maleAvatarPath;
            } else if (mmSex === "여") {
              defaultAvatar = femaleAvatarPath;
            }
            // 이미지가 없거나 noimage4.png인 경우에만 기본 아바타로 교체 (실제 이미지 경로가 있으면 그대로 사용)
            // profileImg가 빈 문자열이거나, noimage4.png를 포함하는 경우에만 기본 아바타 사용
            if (!profileImg || profileImg === "" || profileImg.indexOf("noimage4.png") !== -1) {
              profileImg = defaultAvatar;
            }
            // profileImg가 실제 이미지 경로인 경우 그대로 사용 (컨트롤러에서 이미 getResumeImg()로 처리됨)
            $content = $content.replaceAll("{{profile_img}}", profileImg);
            $content = $content.replaceAll("{{default_avatar}}", defaultAvatar);

            var ruName = row.ru_name || "";
            if (ruName && ruName.length > 18) {
              ruName = ruName.substring(0, 18) + '...';
            }
            $content = $content.replaceAll("{{ru_name}}", ruName);

            if (row.mm_nation && row.mm_nation.trim() != '') {
              $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
            } else {
              $content = $content.replaceAll("<p>{{mm_nation}}</p>", "");
            }

            var maAge = "";
            if (row.ma_age && row.ma_age != '' && row.ma_age != '0') {
              maAge = row.ma_age + "대";
            }
            if (maAge != "") {
              $content = $content.replaceAll("{{ma_age}}", maAge);
            } else {
              $content = $content.replaceAll("<p>{{ma_age}}</p>", "");
            }

            var ruWorkType = row.ru_work_type || "";
            if (ruWorkType && typeof getWorkType === 'function') {
              ruWorkType = getWorkType(ruWorkType);
            }
            $content = $content.replaceAll("{{ru_work_type}}", ruWorkType);

            $clone.html($content);

            var resumeboxHtml = "<div id='resumebox_" + rc_idx + "' class='resumebox_" + rc_idx + " resumebox_'>" + $clone.html() + "</div>";

            var $li = $(obj).closest('li');
            if ($li.length > 0) {
              $li.append(resumeboxHtml);

              var $resumebox = $('#resumebox_' + rc_idx);
              if ($resumebox.length > 0) {
                $resumebox.css({
                  'display': 'block',
                  'visibility': 'visible',
                  'opacity': '1'
                });
                $resumebox.show();

                $li.css({
                  'display': 'block',
                  'visibility': 'visible'
                });
                $li.show();
              }
            } else {
              $(obj).parent().append(resumeboxHtml);
              var $resumebox = $('#resumebox_' + rc_idx);
              $resumebox.css({
                'display': 'block',
                'visibility': 'visible',
                'opacity': '1'
              });
              $resumebox.show();
            }

          }

        });

      } else {
        commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
        return;
      }

      fSearchNoReadCount();

    });
  }


</script>

<!-- 앱 딥링크 -->
<script src="/resource/shop/mobile/js/app-deeplink.js"></script>

<template id="tpl_chat">
  <li class="list-item room-item" data-target_id="{{rc_idx}}">
    <div class="content-li" data-rc_idx="{{rc_idx}}" data-room_idx_list="{{room_idx_list}}" data-noreadcnt="0">
      <a href="#" class="angle-link js-open-view" style="padding:0;" onclick="fShowChatListByRecruite('{{rc_idx}}',this); return false;">
        <div class="content-info">
          <div class="content-body">
            <div class="desc">
              <!-- <div class="wicon">{{rt_recom_badge}}{{rt_work_img}}</div> -->
              <div class="title ellipsis">{{rc_name}}</div>
              <div class="summary">
                <p>{{rc_nation_nm}}</p>
                <p>{{rc_age_nm}}</p>
              </div>
              <div class="summary area">
                <p>{{ad_addr}}</p>
              </div>
            </div>
            <div class="summary-container">
              <div class="summary3">{{rt_work_type}}</div>
              <div class="summary2">
                <p class="period">{{rt_price_type}}</p>
                <p class="amount">{{rt_price_pay}}</p>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <span class="room_rec_unread_cnt"></span>
  </li>
</template>



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