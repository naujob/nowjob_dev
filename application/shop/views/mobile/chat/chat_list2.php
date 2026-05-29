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
                <?php if ($user_type == "MEM") : ?>
                    <li><a href="/chat/chat_list" class="tab">지원한 공고<span class="tab-badge"></span></a></li>
                    <li class="on"><a href="/chat/chat_list2" class="tab">제안받은 공고<span class="tab-badge"></span></a></li>
                <?php endif; ?>
            </ul>
        </div>

        <style>
            .angle-link {
                background: none;
            }

            .angle-link::after {
                content: "";
                position: absolute;
                top: 50%;
                right: 0.2rem;
                transform: translateY(-50%) rotate(90deg);
                background: url(/resource/shop/mobile/img/ic_angle_link.svg) no-repeat center;
                width: 1rem;
                height: 1rem;
                display: block;
                background-size: contain;
            }

            .room-item .angle-link::after {
                transform: translateY(-50%) rotate(0deg);
            }

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
            <div class="container">
                <!-- 제안받은공고 탭 전용 페이지 (개인회원용) -->
                <h2 class="sr-text">제안받은공고</h2>

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

    // 페이지 로드 시 제안받은공고 목록 조회 및 실시간 업데이트 초기화
    $(document).ready(function() {
        getChatTargetList2();
        initRealTimeTabBadgeUpdate();
    });

    // 페이지 포커스 시 카운트 갱신 (chat_view에서 돌아왔을 때)
    window.addEventListener('focus', function() {
        fSearchNoReadCount();
    });

    // 제안받은공고 탭 데이터 조회 함수 (개인회원 전용)
    function getChatTargetList2() {
        comAjax("/chat_proc/get_chat_target_list2_mem", {}, function(ret) {
            $("#chat_list").html("");

            if (ret.code == "00") {
                var list = ret.data.list;
                list.forEach(function(row) {
                    var $clone = $("#tpl_chat2").clone();
                    var $content = $clone.html();

                    // 템플릿 변수 바인딩
                    $content = $content.replaceAll("{{reg_dt}}", row.reg_dt || "");
                    $content = $content.replaceAll("{{room_idx}}", row.rm_idx || "");
                    $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || "");
                    $content = $content.replaceAll("{{rc_idx}}", row.rc_idx || "");
                    $content = $content.replaceAll("{{target_id}}", row.target_id || "");
                    $content = $content.replaceAll("{{user_id}}", row.user_id || "");
                    $content = $content.replaceAll("{{target_img}}", row.target_img || "");
                    $content = $content.replaceAll("{{user_img}}", row.user_img || "");
                    $content = $content.replaceAll("{{target_name}}", row.target_name || "");
                    $content = $content.replaceAll("{{user_name}}", row.user_name || "");

                    // 공고 정보 바인딩
                    var rcName = truncateRcTitle(row.rc_name || "");
                    $content = $content.replaceAll("{{rc_name}}", rcName);
                    $content = $content.replaceAll("{{rc_thumb}}", (row.rc_thumb && row.rc_thumb.trim() !== '') ? row.rc_thumb : '/resource/shop/mobile/img/common/noimage4.png');

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

    // 제안받은공고 목록의 미확인 채팅건수 조회
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

                // 현재 페이지의 chat_list 컨테이너 내에서만 요소 찾기
                var $unreadElement = $('#chat_list #room_idx_unread_cnt_' + room.rm_idx);
                if ($unreadElement.length > 0) {
                    // 현재 페이지에 있는 채팅방 (제안받은공고 탭)
                    $unreadElement.html(count);
                    if (count > 0) {
                        $unreadElement.show();
                        tab2UnreadCount += count;
                    } else {
                        $unreadElement.hide();
                    }
                } else {
                    // 현재 페이지에 없는 채팅방 (지원한공고 탭)
                    if (count > 0) {
                        tab1UnreadCount += count;
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

    // 제안받은공고 목록 아이템 클릭 이벤트 처리
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

        // 제안받은공고 탭에서 채팅방으로 이동 (from=proposed_job 파라미터 사용)
        location.href = "/chat/chat_view?rm_idx=" + target_id + "&from=proposed_job";
    });

    // 뒤로가기 막기 - 히스토리에 현재 페이지 추가
    window.addEventListener('load', function() {
        window.history.pushState(null, null, location.href);

        // 뒤로가기 시 현재 페이지로 다시 이동
        window.addEventListener('popstate', function() {
            window.history.pushState(null, null, location.href);
        });
    });

    // 스와이프 뒤로가기 비활성화 (이 페이지에서는 스와이프로 뒤로가기 불가)
    window.DISABLE_SWIPE_BACK = true;
</script>

<!-- 제안받은공고 탭 전용 템플릿 (개인회원용) -->
<template id="tpl_chat2">
    <li class="list-item msg-item room-item room_idx_{{room_idx}}" data-target_id="{{room_idx}}" data-regdt="{{reg_dt}}">
        <a href="#" class="angle-link js-open-view angle-link-resume" style="padding:0;"
            aria-haspopup="dialog"
            data-ru_idx="{{ru_idx}}"
            data-rc_idx="{{rc_idx}}"
            data-target_id="{{target_id}}"
            data-user_id="{{user_id}}"
            data-target_img="{{target_img}}"
            data-user_img="{{user_img}}"
            data-target_name="{{target_name}}"
            data-user_name="{{user_name}}"
            data-subject="{{rc_name}}">
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
