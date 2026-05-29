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
                <?php if ($user_type == "COMP") : ?>
                    <li><a href="/chat/index_comp" class="tab">지원한 인재<span class="tab-badge"></span></a></li>
                    <li class="on"><a href="/chat/chat_list3" class="tab">제안한 인재<span class="tab-badge"></span></a></li>
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
                /* 아이콘을 아래로 회전 */
                background: url(/resource/shop/mobile/img/ic_angle_link.svg) no-repeat center;
                width: 1rem;
                /* 아이콘의 너비 */
                height: 1rem;
                /* 아이콘의 높이 */
                display: block;
                background-size: contain;
            }

            .room-item .angle-link::after {
                transform: translateY(-50%) rotate(0deg);
                /* 아이콘 기본 상태 (오른쪽) */
                transition: transform 0.3s ease;
                /* 부드러운 회전 애니메이션 */
            }

            .room-item .angle-link.on::after {
                transform: translateY(-50%) rotate(90deg);
                /* 아이콘을 아래로 회전 (펼쳐짐 상태) */
            }

            /* 프로필 이미지 border-radius */
            /* 전역 CSS의 .item-info .thumb img { border-radius: inherit; } 스타일을 덮어쓰기 위해 더 구체적인 선택자 사용 */
            .page-chat .item-info .thumb img,
            .page-chat .room-item .thumb img,
            .page-chat .thumb img {
                border-radius: 5px;
            }
        </style>
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
            .room_rec_unread_cnt {
                position: absolute;
                width: 26px;
                height: 26px;
                background-color: #F86048;
                top: 49px;
                right: 30px;
                border-radius: 100%;
                color: #fff;
                text-align: center;
                line-height: 25px;
                display: none;
            }


            /* 지원자 목록 들여쓰기 스타일 */
            [class*="resumebox_"] {
                margin-left: 1rem;
                /* 한 칸 정도 들여쓰기 */
            }

            [class*="resumebox_"] .list-item {
                margin-left: 8px;
            }

            /* hr_list.php 형식 스타일 */
            [class*="resumebox_"] .content-body {
                position: relative;
                overflow: hidden;
                height: 95px;
                padding: 10px;
            }

            [class*="resumebox_"] .content-body .img {
                position: absolute;
                width: 95px;
                height: 95px;
                border: 0;
                border-radius: 5px;
                color: #888;
                overflow: hidden;
            }

            [class*="resumebox_"] .content-body .img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            [class*="resumebox_"] .content-body .desc {
                position: relative;
                height: 100%;
                margin: 0 0 0 110px;
                cursor: pointer;
            }

            [class*="resumebox_"] .content-body .desc .wicon img {
                height: 19px;
            }

            [class*="resumebox_"] .content-body .desc .title {
                font-size: 18px;
                font-weight: bold;
                margin: 1px 0px;
            }

            [class*="resumebox_"] .content-body .desc .title.ellipsis {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            [class*="resumebox_"] .content-body .desc .summary {
                display: flex;
                font-size: 16px;
                gap: 5px;
                position: relative;
            }

            [class*="resumebox_"] .content-body .desc .summary p {
                position: relative;
                padding-right: 5px;
                margin: 0;
                color: #000;
                font-weight: 600;
            }

            [class*="resumebox_"] .content-body .desc .summary p:not(:last-child)::after {
                content: "";
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                width: 1px;
                height: 0.5em;
                margin: auto;
                border-right: 1px solid #ACACAC;
            }

            [class*="resumebox_"] .content-body .desc .summary2 {
                position: absolute;
                right: 0px;
                bottom: 0px;
                font-size: 16px;
                font-weight: 600;
                letter-spacing: 0;
                color: #1b9d7d;
                display: flex;
                flex-direction: column;
                align-items: flex-end;
            }

            [class*="resumebox_"] .content-body .desc .summary2 p {
                margin: 0;
                text-align: right;
            }

            [class*="resumebox_"] .content-body .desc .summary3 {
                position: absolute;
                right: 0px;
                top: 0px;
                font-size: 16px;
                font-weight: 600;
                letter-spacing: 0;
                color: #333;
                display: flex;
                flex-direction: column;
                align-items: flex-end;
            }

            [class*="resumebox_"] .content-body .desc .summary3 p {
                margin: 0;
                text-align: right;
            }

            /* content-wrap 클래스에 왼쪽 마진 추가 */
            .content-wrap {
                margin-left: 1rem;
            }
        </style>
        <div class="tab-panel">
            <div class="container">
                <!-- 제안보낸 인재 탭 전용 페이지 (기업회원용) -->
                <h2 class="sr-text">제안보낸 인재</h2>

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

    // 안드로이드와 웹을 구분하여 처리
    <?php if ($this->common->getSession("os_type") == "android") : ?>
        // 안드로이드 앱에서 실행
        window.addEventListener('DOMContentLoaded', function() {
            getChatTargetList(2);
            initRealTimeTabBadgeUpdate();
        });

        // 페이지 복귀 시 미확인 메시지 갱신
        window.addEventListener('focus', function() {
            fSearchNoReadCount();
        });

        // [사용되지 않음] 소켓 기반 채팅방 목록 조회 - 현재는 AJAX 기반으로 동작
        // Socket.IO 이벤트: room_list_return
        // 현재 데이터 로딩 방식: getChatTargetList() 함수가 AJAX(comAjax)로 데이터를 가져옴
        // Socket.IO 기반 room_list 이벤트는 사용하지 않음
        /*
        socket.on("room_list_return", function(ret) {
            g_user_id = $("#user_id").val();

            // resumebox_가 있으면 스마트 업데이트 모드 (인재 목록 보호)
            var resumeboxCount = $('.resumebox_').length;
            var isRecruitListMode = resumeboxCount > 0;

            if (isRecruitListMode) {
                // resumebox_가 있는 경우: 기존 li만 업데이트, resumebox_는 보호
                if (ret != null && ret.length > 0) {
                    var updatedRoomIds = [];

                    for (var icnt = 0; icnt < ret.length; icnt++) {
                        var rm_idx = ret[icnt].rm_idx;
                        updatedRoomIds.push(rm_idx);

                        // resumebox_가 포함된 li는 건드리지 않음
                        var $existingLi = $("#chat_list > li.room-item[data-target_id='" + rm_idx + "']");

                        if ($existingLi.length > 0 && $existingLi.find('.resumebox_').length === 0) {
                            // 기존 항목 업데이트 (resumebox_가 없는 li만)
                            var img_html = getRoomImg(ret[icnt], g_user_id);
                            var subject = getRoomSubject(ret[icnt], g_user_id);
                            var desc = getRoomDesc(ret[icnt], "chat");
                            var not_read = ret[icnt].not_read;

                            // 기존 li 내용 업데이트
                            $existingLi.find('.name').text(subject);
                            $existingLi.find('.latest').text(desc);
                            $existingLi.find('.thumb img').attr('src', img_html);
                            $existingLi.find('.time').text(getFormatDate(new Date(ret[icnt].rm_last_dt), 'chat'));

                            // 안 읽은 메시지 수 업데이트
                            var $badge = $existingLi.find('.badge span');
                            if (not_read > 0) {
                                if ($badge.length === 0) {
                                    $existingLi.find('.tail').append('<p class="badge"><span>' + not_read + '</span></p>');
                                } else {
                                    $badge.text(not_read);
                                }
                            } else {
                                $existingLi.find('.badge').remove();
                            }
                        } else if ($existingLi.length === 0) {
                            // 새 항목 추가 (resumebox_가 있는 li 다음에 추가)
                            var img_html = getRoomImg(ret[icnt], g_user_id);
                            var subject = getRoomSubject(ret[icnt], g_user_id);
                            var desc = getRoomDesc(ret[icnt], "chat");
                            var not_read = ret[icnt].not_read;

                            var html = "";
                            html += "<li class='list-item msg-item room-item' data-target_id='" + rm_idx + "'>";
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

                            // resumebox_가 있는 li 다음에 추가
                            var $lastResumebox = $('.resumebox_').last();
                            if ($lastResumebox.length > 0) {
                                $lastResumebox.closest('li').after(html);
                            } else {
                                $("#chat_list").append(html);
                            }
                        }
                    }

                    // 서버에 없는 기존 항목 제거 (resumebox_가 없는 것만)
                    $("#chat_list > li.room-item").each(function() {
                        var $li = $(this);
                        var rm_idx = $li.data("target_id");

                        // resumebox_가 있는 li는 건드리지 않음
                        if ($li.find('.resumebox_').length > 0) {
                            return; // continue
                        }

                        // 서버에 없는 항목이면 제거
                        if (rm_idx && updatedRoomIds.indexOf(rm_idx) === -1) {
                            $li.remove();
                        }
                    });
                }
            } else {
                // resumebox_가 없는 경우: 기존 방식대로 전체 리셋
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
                    }
                }
            }
        });
        */

        // [사용되지 않음] 소켓 기반 실시간 메시지 정보 리스트 갱신 - 현재는 AJAX 기반으로 동작
        /*
        socket.on("message_list_refresh", function(ret) {
            g_user_id = $("#user_id").val();
            socket.emit("room_list", {
                user_id: g_user_id,
                rm_type: "chat"
            });
        });

        // [사용되지 않음] 소켓 기반 실시간 알림 메시지 수신 - 현재는 AJAX 기반으로 동작
        socket.on("realtime_message", function(ret) {
            if (ret.type == "chat") {
                //alert(ret.not_read);
            }
        });
        */
    <?php else: ?>
        // 웹에서 실행
        $(document).ready(function() {
            getChatTargetList(2);
            
            initRealTimeTabBadgeUpdate();
        });

        // 페이지 복귀 시 미확인 메시지 갱신
        window.addEventListener('focus', function() {
            fSearchNoReadCount();
        });
    <?php endif; ?>

    // 제안한 인재탭의 전체 공고 목록 조회 함수 (템플릿: #tpl_chat (공고 목록 템플릿))
    function getChatTargetList(arg) {
        // 제안한 인재탭의 전체 공고 목록 조회 API 엔드포인트
        var getUrl = "get_chat_target_list_recruit2";
        comAjax("/chat_proc/" + getUrl, {}, function(ret) {
            $("#chat_list").html("");

            if (ret.code == "00") {
                var list = ret.data.list;

                if (list.length == 0) {
                    $(".chat-empty").show();
                    $("#chat_list").hide();
                    return;
                }

                list.forEach(function(row) {
                    // 기업회원 전용 템플릿 사용
                    var $clone = $("#tpl_chat").clone();
                    if ($clone.length == 0) {
                        return;
                    }

                    var $content = $clone.html();

                    $content = $content.replaceAll("{{room_idx}}", row.rc_idx || row.rm_idx || "");
                    $content = $content.replaceAll("{{room_idx_list}}", row.rm_idx_list || "()");
                    $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || "");
                    $content = $content.replaceAll("{{rc_idx}}", row.rc_idx || "");
                    $content = $content.replaceAll("{{mm_name}}", row.mm_name || "");
                    $content = $content.replaceAll("{{ru_week}}", row.ru_week || "");
                    $content = $content.replaceAll("{{distance}}", (row.distance ? row.distance + "km" : ""));
                    $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img || "");
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

                    var rcName = truncateRcTitle(row.rc_name || "");
                    $content = $content.replaceAll("{{rc_name}}", rcName);
                    $content = $content.replaceAll("{{rc_thumb}}", row.rc_thumb || "");
                    $content = $content.replaceAll("{{reg_dt}}", row.reg_dt || "");
                    $content = $content.replaceAll("{{rc_work}}", row.rc_work || "");
                    $content = $content.replaceAll("{{mm_comp_name}}", row.mm_comp_name || "");
                    $content = $content.replaceAll("{{last_msg_preview}}", row.last_msg_preview || "");

                    var nationStr = '';
                    nationStr = row.rc_nation;
                    if (row.rc_nation != "" && (row.rc_sage != "0" || row.rc_eage != "0")) {
                        nationStr += " | ";
                    }
                    if (row.rc_sage == "0") {
                        nationStr += row.rc_sage + "대";
                    }
                    if (row.rc_sage == "0" && row.rc_eage == "0") {
                        nationStr += "";
                    } else {
                        nationStr += " ~ ";
                    }
                    if (row.rc_eage == "0") {
                        nationStr += "";
                    } else {
                        nationStr += row.rc_eage + "대";
                    }
                    $content = $content.replaceAll("{{rc_nation}}", nationStr);

                    //복사한 내용 다시 넣기
                    $clone.html($content);

                    var htmlContent = $clone.html();

                    $("#chat_list").append(htmlContent);
                });

                //hide show 처리
                var itemCount = $("#chat_list").find("li").length;

                if (itemCount == 0) {
                    $(".chat-empty").show();
                    $("#chat_list").hide();
                } else {
                    $(".chat-empty").hide();
                    $("#chat_list").show();

                    // 강제로 표시 (CSS 우선순위 문제 해결)
                    $("#chat_list").css("display", "block");
                    $(".chat-empty").css("display", "none");

                    // 강제로 부모 요소도 표시
                    var chatListParent = $("#chat_list").parent();
                    chatListParent.show();
                    chatListParent.css("display", "block");
                }

            } else {
                commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
                return;
            }

            fSearchNoReadCount();

            // URL 파라미터에서 auto_open_rc_idx와 auto_open_ru_idx 확인하여 해당 채팅방 자동 진입
            var urlParams = new URLSearchParams(window.location.search);
            var autoOpenRcIdx = urlParams.get('auto_open_rc_idx');
            var autoOpenRuIdx = urlParams.get('auto_open_ru_idx'); // ru_idx 파라미터 가져오기
            if (autoOpenRcIdx && ret.code == "00" && ret.data && ret.data.list) {
                // URL에서 auto_open_rc_idx와 auto_open_ru_idx 파라미터 제거 (뒤로가기 시 재진입 방지)
                urlParams.delete('auto_open_rc_idx');
                urlParams.delete('auto_open_ru_idx');
                var newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                window.history.replaceState({}, '', newUrl);
                
                // 목록 로드 완료 후 해당 rc_idx와 ru_idx 모두 일치하는 채팅방 찾기
                setTimeout(function() {
                    var targetRmIdx = null;
                    var chatList = ret.data.list;
                    var recruitClicked = false;
                    var checkCount = 0;
                    var maxCheckCount = 30;
                    
                    var checkInterval = setInterval(function() {
                        checkCount++;
                        
                        // 이미 펼쳐진 인재 목록에서 채팅방 찾기 (rc_idx와 ru_idx 모두 확인)
                        $("#chat_list .room-item").each(function() {
                            var $item = $(this);
                            var itemRcIdx = $item.find('.angle-link-resume').data('rc_idx') || 
                                          $item.find('a[data-rc_idx]').data('rc_idx') ||
                                          $item.data('rc_idx');
                            var itemRuIdx = $item.find('.angle-link-resume').data('ru_idx') || 
                                          $item.find('a[data-ru_idx]').data('ru_idx') ||
                                          $item.data('ru_idx');
                            // autoOpenRuIdx가 있으면 rc_idx와 ru_idx 모두 일치해야 함
                            // autoOpenRuIdx가 없으면 rc_idx만 확인 (기존 로직 유지)
                            if (itemRcIdx == autoOpenRcIdx) {
                                if (autoOpenRuIdx) {
                                    // ru_idx가 전달된 경우: rc_idx와 ru_idx 모두 일치해야 함
                                    if (itemRuIdx == autoOpenRuIdx) {
                                        targetRmIdx = $item.data('target_id');
                                        return false; // break
                                    }
                                } else {
                                    // ru_idx가 전달되지 않은 경우: rc_idx만 확인 (기존 로직)
                                    targetRmIdx = $item.data('target_id');
                                    return false; // break
                                }
                            }
                        });
                        
                        // 채팅방을 찾았으면 자동으로 진입
                        if (targetRmIdx) {
                            clearInterval(checkInterval);
                            location.href = "/chat/chat_view?rm_idx=" + targetRmIdx + "&auto_send=true&rc_idx=" + autoOpenRcIdx + "&from=talent_sent";
                            return;
                        }
                        
                        // 공고를 아직 클릭하지 않은 경우
                        if (!recruitClicked) {
                            var $targetRecruitItem = null;
                            $("#chat_list .list-item").each(function() {
                                var $item = $(this);
                                var $link = $item.find('a[onclick*="fShowChatListByRecruite"]');
                                if ($link.length > 0) {
                                    var onclickAttr = $link.attr('onclick') || '';
                                    var match = onclickAttr.match(/fShowChatListByRecruite\('(\d+)'/);
                                    if (match && match[1] == autoOpenRcIdx) {
                                        $targetRecruitItem = $link;
                                        return false; // break
                                    }
                                }
                            });
                            
                            // 공고를 찾았으면 한 번만 클릭하여 인재 목록 펼치기
                            if ($targetRecruitItem && $targetRecruitItem.length > 0) {
                                recruitClicked = true;
                                $targetRecruitItem.trigger('click');
                                return;
                            }
                        }
                        
                        // 최대 시도 횟수에 도달한 경우
                        if (checkCount >= maxCheckCount) {
                            clearInterval(checkInterval);
                            //console.error("채팅방을 찾을 수 없음 - rc_idx:", autoOpenRcIdx, "ru_idx:", autoOpenRuIdx);
                            // ru_idx와 rc_idx 모두 일치하는 채팅방 찾기 (마지막 시도)
                            var $matchedChatItem = null;
                            // $("#chat_list .room-item")가 가리키는 태그는?
                            // <li class="list-item ... room-item ..."> 인 li 태그임 (채팅방/공고 항목 리스트의 각 아이템)
                            $("#chat_list .room-item").each(function() {
                                var $item = $(this);
                                // itemRcIdx: 각 채팅방 아이템(li)이 나타내는 공고(rc_idx) 값
                                var itemRcIdx = $item.find('.angle-link-resume').data('rc_idx') || 
                                              $item.find('a[data-rc_idx]').data('rc_idx') ||
                                              $item.data('rc_idx');
                                // itemRuIdx는 각 채팅방(li)의 data-ru_idx 값 (즉, 인재의 ru_idx), 없으면 undefined
                                var itemRuIdx = $item.find('.angle-link-resume').data('ru_idx') || 
                                              $item.find('a[data-ru_idx]').data('ru_idx') ||
                                              $item.data('ru_idx');
                               
                                // itemRcIdx가 autoOpenRcIdx(자동으로 열고자 하는 공고의 rc_idx)와 같은지 확인
                                if (itemRcIdx == autoOpenRcIdx) {
                                    // autoOpenRuIdx가 전달된 경우: rc_idx와 ru_idx 모두 일치해야 함
                                    if (autoOpenRuIdx) {
                                        // ru_idx가 전달된 경우: rc_idx와 ru_idx 모두 일치해야 함
                                        if (itemRuIdx == autoOpenRuIdx) {
                                            $matchedChatItem = $item;
                                            return false; // break
                                        }
                                    } else {
                                        // ru_idx가 전달되지 않은 경우: rc_idx만 확인 (기존 로직)
                                        $matchedChatItem = $item;
                                        return false; // break
                                    }
                                }
                            });
                            
                            if ($matchedChatItem && $matchedChatItem.length > 0) {
                                var matchedRmIdx = $matchedChatItem.data('target_id');
                                if (matchedRmIdx) {
                                    location.href = "/chat/chat_view?rm_idx=" + matchedRmIdx + "&auto_send=true&rc_idx=" + autoOpenRcIdx + "&from=talent_sent";
                                }
                            }
                            // 매칭되는 채팅방을 찾지 못한 경우: 아무 동작도 하지 않음
                            // 사용자가 채팅 목록에서 수동으로 선택할 수 있도록 함
                        }
                    }, 500); // 0.5초마다 확인
                }, 500); // 목록 렌더링 대기
            }

        });
    }

    // 제안보낸 인재 목록의 미확인 채팅건수 조회
    function fSearchNoReadCount() {
        // 현재 페이지의 chat_list 컨테이너 내의 content-wrap만 초기화
        $('#chat_list .content-wrap').each(function() {
            $(this).attr('data-noreadcnt', '0');
        });

        // 공고리스트에 미확인 채팅건수 조회 확인
        comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
            var myId = $("#user_id").val();

            let result = ret.data.result;

            // 탭별 읽지 않은 메시지 개수 계산
            var tab1UnreadCount = 0; // 지원한 인재 탭
            var tab2UnreadCount = 0; // 제안한 인재 탭

            // 공고별 room_idx_list 수집 (공고명 클릭 전에도 확인 가능)
            var tab2RoomIdxList = [];
            // 제안한 인재탭의 공고 목록에 대한 room_idx_list 수집
            // 이 채팅방이 제안한 인재 탭에 속하는가?를 빠르게 판별하기 위해 room_idx_list를 수집
            $('#chat_list .content-wrap').each(function() {
                var roomIdxList = $(this).attr('data-room_idx_list');
                if (roomIdxList) {
                    // roomIdxArray는 data-room_idx_list 속성에서 ()을 제거하고 콤마(,)로 분리한 후, 각 값을 숫자로 변환한 채팅방(room_idx) ID 배열입니다.
                    var roomIdxArray = roomIdxList.replace(/[()]/g, '').split(',').map(function(idx) {
                        return Number(idx.trim());
                    }).filter(function(idx) {
                        return !isNaN(idx) && idx > 0;
                    });
                    roomIdxArray.forEach(function(rmIdx) {
                        // tab2RoomIdxList 배열에 rmIdx가 없으면 추가
                        if (tab2RoomIdxList.indexOf(rmIdx) === -1) {
                            tab2RoomIdxList.push(rmIdx);
                        }
                    });
                }
            });

           
            // result: 서버에서 반환된, 채팅방별 데이터 객체의 배열 또는 객체입니다.
            // 각 room은 단일 채팅방의 정보, 해당 채팅방의 메시지, 읽음 여부 등을 포함합니다.
            // 예시: [{
            //   rm_idx: 101, // 채팅방(룸) ID
            //   rm_messages: [
            //      {
            //          mg_idx: 205, // 메시지 ID
            //          mg_reads: [{user_id: "abc", read_yn: "N"}, ...] // 메시지별 읽음 정보
            //      }, ...
            //   ]
            // }]
            $.each(result, function(key, room) {
                let count = 0; // 현재 채팅방(room)의 미확인 메시지 개수
                // room.rm_messages는 현재 채팅방(room)에 포함된 모든 메시지 배열입니다.
                $.each(room.rm_messages, function(index, message) {
                    // message.mg_reads는 현재 메시지(message)에 대한 읽음 처리 상태 배열입니다.
                    $.each(message.mg_reads, function(i, readEntry) {
                        // readEntry.user_id === myId && readEntry.read_yn === 'N'는 현재 사용자(myId)가 메시지를 읽지 않은 상태인지 확인하는 조건입니다.
                        if (readEntry.user_id === myId && readEntry.read_yn === 'N') {
                            count++;
                        }
                    });
                });

                // 하위 인재 리스트의 개별 채팅방 뱃지 업데이트 (DOM에 있는 경우)
                var $unreadElement = $('#chat_list #room_idx_unread_cnt_' + room.rm_idx);
                if ($unreadElement.length > 0) {
                    // 현재 페이지에 있는 채팅방 (제안한 인재 탭)
                    $unreadElement.html(count);
                    if (count > 0) {
                        $unreadElement.show();
                    } else {
                        $unreadElement.hide();
                    }
                }

                // 탭별 읽지 않은 메시지 개수 계산 (data-room_idx_list 기반)
                if (tab2RoomIdxList.indexOf(room.rm_idx) !== -1) {
                    // 탭2에 속한 채팅방 (제안한 인재)
                    if (count > 0) {
                        tab2UnreadCount += count;
                        // 상위 공고 목록의 뱃지에 카운트 추가 (DOM에 요소가 없어도 data-room_idx_list 기반으로 처리)
                        fSetNoReadCntRecruit(room.rm_idx, count);
                    }
                } else {
                    // 탭1에 속한 채팅방 (지원한 인재)
                    if (count > 0) {
                        tab1UnreadCount += count;
                    }
                }
            });

            // 모든 room 처리 후 상위 공고 뱃지를 일괄 반영
            $('#chat_list .content-wrap').each(function() {
                // 현재 div의 data-noreadcnt 값을 가져오기
                let noReadCnt = parseInt($(this).attr('data-noreadcnt')) || 0;

                // 상위 li 요소 찾기
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
    // fSetNoReadCntRecruit: 제안한 인재탭의 공고 목록에 대한 미확인 메시지 카운트 업데이트 함수
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

                // 배열에 room_idx가 포함되어 있는지 확인 (숫자로 비교)
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

    // 제안보낸 인재 목록 아이템 클릭 이벤트 처리
    $(document).on("click", ".room-item", function(e) {
        // fShowChatListByRecruite 함수가 있는 경우에는 바로 채팅방으로 이동하지 않음
        if ($(this).find('.angle-link[onclick*="fShowChatListByRecruite"]').length > 0) {
            return; // fShowChatListByRecruite 함수가 처리하도록 함
        }

        // target_id는 채팅방의 고유 식별자인 room_idx(채팅방 인덱스)를 의미합니다.
        var target_id = $(this).data("target_id"); // 즉, 해당 li.room-item의 data-target_id 속성값 = room_idx
        // 제안보낸 인재 탭에서 채팅방으로 이동 (from=talent_sent 파라미터 사용)
        location.href = "/chat/chat_view?rm_idx=" + target_id + "&from=talent_sent";
    });

    // [사용되지 않음] 웹 버전 소켓 기반 채팅방 목록 조회 - 현재는 AJAX 기반으로 동작
    <?php if ($this->common->getSession("os_type") != "android") : ?>
        // Socket.IO 이벤트: room_list_return (웹 버전)
        // 현재 데이터 로딩 방식: getChatTargetList() 함수가 AJAX(comAjax)로 데이터를 가져옴
        // Socket.IO 기반 room_list 이벤트는 사용하지 않음
        /*
        socket.on("room_list_return", function(ret) {
            g_user_id = $("#user_id").val();

            // resumebox_가 있으면 스마트 업데이트 모드 (인재 목록 보호)
            var resumeboxCount = $('.resumebox_').length;
            var isRecruitListMode = resumeboxCount > 0;

            if (isRecruitListMode) {
                // resumebox_가 있는 경우: 기존 li만 업데이트, resumebox_는 보호
                if (ret != null && ret.length > 0) {
                    var updatedRoomIds = [];

                    for (var icnt = 0; icnt < ret.length; icnt++) {
                        var rm_idx = ret[icnt].rm_idx;
                        updatedRoomIds.push(rm_idx);

                        // resumebox_가 포함된 li는 건드리지 않음
                        var $existingLi = $("#chat_list > li.room-item[data-target_id='" + rm_idx + "']");

                        if ($existingLi.length > 0 && $existingLi.find('.resumebox_').length === 0) {
                            // 기존 항목 업데이트 (resumebox_가 없는 li만)
                            var img_html = getRoomImg(ret[icnt], g_user_id);
                            var subject = getRoomSubject(ret[icnt], g_user_id);
                            var desc = getRoomDesc(ret[icnt], "chat");
                            var not_read = ret[icnt].not_read;

                            // 기존 li 내용 업데이트
                            $existingLi.find('.name').text(subject);
                            $existingLi.find('.latest').text(desc);
                            $existingLi.find('.thumb img').attr('src', img_html);
                            $existingLi.find('.time').text(getFormatDate(new Date(ret[icnt].rm_last_dt), 'chat'));

                            // 안 읽은 메시지 수 업데이트
                            var $badge = $existingLi.find('.badge span');
                            if (not_read > 0) {
                                if ($badge.length === 0) {
                                    $existingLi.find('.tail').append('<p class="badge"><span>' + not_read + '</span></p>');
                                } else {
                                    $badge.text(not_read);
                                }
                            } else {
                                $existingLi.find('.badge').remove();
                            }
                        } else if ($existingLi.length === 0) {
                            // 새 항목 추가 (resumebox_가 있는 li 다음에 추가)
                            var img_html = getRoomImg(ret[icnt], g_user_id);
                            var subject = getRoomSubject(ret[icnt], g_user_id);
                            var desc = getRoomDesc(ret[icnt], "chat");
                            var not_read = ret[icnt].not_read;

                            var html = "";
                            html += "<li class='list-item msg-item room-item' data-target_id='" + rm_idx + "'>";
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

                            // resumebox_가 있는 li 다음에 추가
                            var $lastResumebox = $('.resumebox_').last();
                            if ($lastResumebox.length > 0) {
                                $lastResumebox.closest('li').after(html);
                            } else {
                                $("#chat_list").append(html);
                            }
                        }
                    }

                    // 서버에 없는 기존 항목 제거 (resumebox_가 없는 것만)
                    $("#chat_list > li.room-item").each(function() {
                        var $li = $(this);
                        var rm_idx = $li.data("target_id");

                        // resumebox_가 있는 li는 건드리지 않음
                        if ($li.find('.resumebox_').length > 0) {
                            return; // continue
                        }

                        // 서버에 없는 항목이면 제거
                        if (rm_idx && updatedRoomIds.indexOf(rm_idx) === -1) {
                            $li.remove();
                        }
                    });
                }
            } else {
                // resumebox_가 없는 경우: 기존 방식대로 전체 리셋
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
            }
        });

        // [사용되지 않음] 소켓 기반 실시간 메시지 정보 리스트 갱신 - 현재는 AJAX 기반으로 동작
        /*
        socket.on("message_list_refresh", function(ret) {
            g_user_id = $("#user_id").val();
            socket.emit("room_list", {
                user_id: g_user_id,
                rm_type: "chat"
            });
        });

        // [사용되지 않음] 소켓 기반 실시간 알림 메시지 수신 - 현재는 AJAX 기반으로 동작
        socket.on("realtime_message", function(ret) {
            if (ret.type == "chat") {
                //alert(ret.not_read);
            }
        });
        */
    <?php endif; ?>

    function fShowChatListByRecruite(ru_idx, obj) {
        // 주의: ru_idx 파라미터명이지만 실제로는 채용공고 번호(rc_idx)를 받음
        // ru_idx = Resume ID (이력서 번호)
        // rc_idx = Recruit ID (채용공고 번호)
        var rc_idx = ru_idx; // 실제로는 채용공고 번호

        // 다른 항목이 열려있으면 자동으로 닫기
        // 현재 클릭한 항목이 아닌 모든 resumebox_ 제거 및 닫기
        $('[class*="resumebox_"]').each(function() {
            var $resumebox = $(this);
            // $resumebox.attr('class')는 현재 jQuery 객체 $resumebox가 가진 class attribute의 문자열 값을 가져온다. 즉, 해당 요소에 설정된 클래스 목록을 문자열로 반환한다.
            var classList = $resumebox.attr('class') || '';
            // classList.match(/resumebox_(\d+)/)는 classList 문자열에서 resumebox_ 클래스가 있는지 확인하고, 그 뒤에 숫자가 있는지 확인한다.
            var match = classList.match(/resumebox_(\d+)/);
            // match가 존재하고 match[1]이 rc_idx와 다른 경우
            if (match && match[1] != rc_idx) {
                // match[1]은 현재 resumebox_ 클래스가 있는 요소의 클래스 목록 중에서 숫자만 추출한 값이다.
                $resumebox.hide();
                // 해당 항목의 요소를 제거하여 화면에서 숨김/삭제   
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

        // 제안보낸 인재 목록 조회 (특정 채용공고의 인재 목록)
        var params = {};
        params["ru_idx"] = rc_idx; // 백엔드에서 ru_idx로 받지만 실제로는 rc_idx 값
        params["chatgbn"] = $(obj).parent().parent().parent().attr('id');
        params["chatgbn"] = 'chatPanel2';
        //get_chat_target_list: 제안한 인재 목록 조회
        comAjax("/chat_proc/get_chat_target_list", params, function(ret) {
            if (ret.code == "00") {
                var list = ret.data.list;
                var listCnt = 0;

                $('.resumebox_' + rc_idx).remove();

                list.forEach(function(row) {
                    listCnt++;

                    // 선택한 채용공고 번호와 응답 데이터의 채용공고 번호가 일치하는지 확인
                    // 타입 변환하여 비교 (문자열/숫자 모두 처리)
                    var requestRcIdx = String(rc_idx);
                    var responseRcIdx = String(row.rc_idx || '');

                    if (requestRcIdx === responseRcIdx || rc_idx == row.rc_idx) {
                        //요청한 공고와 채팅방 공고번호가 일치하면 리스트 표시

                        var $clone = $("#tpl_chat_by_recruite").clone();
                        var $content = $clone.html();

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

                        var rcNameByRecruit = truncateRcTitle(row.rc_name || "");
                        $content = $content.replaceAll("{{rc_name}}", rcNameByRecruit);
                        $content = $content.replaceAll("{{room_idx}}", row.rm_idx);
                        $content = $content.replaceAll("{{target_name}}", row.target_name);
                        $content = $content.replaceAll("{{up_dt}}", row.up_dt);
                        $content = $content.replaceAll("{{last_msg_preview}}", row.last_msg_preview || "");

                        // getResumeImg() 함수와 동일한 로직 적용 (chat_view.php 참고)
                        // 컨트롤러에서 이미 getResumeImg()로 처리된 값이 오므로, 실제 이미지 경로가 있으면 그대로 사용
                        var maleAvatarPath = "/resource/shop/web/img/avatar_icon2_m.png";
                        var femaleAvatarPath = "/resource/shop/web/img/avatar_resume_w.png";
                        var noImagePath = "/resource/shop/mobile/img/common/noimage4.png";
                        var profileImg = (typeof row.ru_resume_img === "string") ? row.ru_resume_img.trim() : "";
                        var mmSex = (row.mm_sex || "").trim();
                        
                        // 디버깅: 실제 전달되는 값 확인 (개발 후 제거 가능)
                        // console.log("ru_resume_img:", profileImg, "mm_sex:", mmSex);
                        
                        var defaultAvatar = noImagePath; // 기본값은 noimage4.png (getResumeImg 함수와 동일)
                        if (mmSex === "남") {
                            defaultAvatar = maleAvatarPath;
                        } else if (mmSex === "여") {
                            defaultAvatar = femaleAvatarPath;
                        }
                        // 이미지가 없거나 noimage4.png인 경우에만 기본 아바타로 교체
                        // profileImg가 빈 문자열이거나, noimage4.png를 포함하는 경우에만 기본 아바타 사용
                        // 실제 이미지 경로(/resource/uploads/...)가 있으면 그대로 사용
                        if (!profileImg || profileImg === "" || profileImg.indexOf("noimage4.png") !== -1) {
                            profileImg = defaultAvatar;
                        }
                        // profileImg가 실제 이미지 경로인 경우 그대로 사용 (컨트롤러에서 이미 getResumeImg()로 처리됨)
                        $content = $content.replaceAll("{{profile_img}}", profileImg);
                        $content = $content.replaceAll("{{default_avatar}}", defaultAvatar);

                        // hr_list.php 형식으로 데이터 처리
                        // 이력서명이 18자 이상이면 ...으로 자르기
                        var ru_name = row.ru_name || "";
                        if (ru_name && ru_name.length > 18) {
                            ru_name = ru_name.substring(0, 18) + '...';
                        }
                        $content = $content.replaceAll("{{ru_name}}", ru_name);


                        // 국적 처리
                        if (row.mm_nation && row.mm_nation.trim() != '') {
                            $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
                        } else {
                            $content = $content.replaceAll("<p>{{mm_nation}}</p>", "");
                        }

                        // 나이 처리
                        var ma_age = "";
                        if (row.ma_age && row.ma_age != '' && row.ma_age != '0') {
                            ma_age = row.ma_age + "대";
                        }
                        if (ma_age != "") {
                            $content = $content.replaceAll("{{ma_age}}", ma_age);
                        } else {
                            $content = $content.replaceAll("<p>{{ma_age}}</p>", "");
                        }

                        // 주소 처리
                        var ad_addr = row.ad_addr || "";
                        if (ad_addr && ad_addr != "") {
                            // 주소 간소화 함수가 있다면 사용 (hr_list.php 참고)
                            if (typeof fRemoveBigAdrJS === 'function') {
                                ad_addr = fRemoveBigAdrJS(ad_addr);
                            }
                            $content = $content.replaceAll("{{ad_addr}}", ad_addr);
                        } else {
                            $content = $content.replaceAll("<p>{{ad_addr}}</p>", "");
                        }

                        // 급여 타입 처리
                        var ru_price_type = row.ru_price_type || "";
                        if (ru_price_type && typeof getPriceType === 'function') {
                            ru_price_type = getPriceType(ru_price_type);
                        }
                        $content = $content.replaceAll("{{ru_price_type}}", ru_price_type);

                        // 근무 타입 처리
                        var ru_work_type = row.ru_work_type || "";
                        if (ru_work_type && typeof getWorkType === 'function') {
                            ru_work_type = getWorkType(ru_work_type);
                        }
                        $content = $content.replaceAll("{{ru_work_type}}", ru_work_type);

                        // 기존 meminfo는 제거하고 개별 필드로 분리
                        var meminfo = "";
                        if (row.ru_work != "") meminfo = row.ru_work + "<br>";
                        if (row.mm_nation != "") meminfo += row.mm_nation;
                        if (row.mm_nation != "" && row.ma_age != "" && row.ma_age != "0") meminfo += " | ";
                        if (row.ma_age != "" && row.ma_age != "0") meminfo += row.ma_age + "대";
                        $content = $content.replaceAll("{{meminfo}}", meminfo);

                        // 복사한 내용 다시 넣기
                        $clone.html($content);

                        var resumeboxHtml = "<div id='resumebox_" + rc_idx + "' class='resumebox_" + rc_idx + " resumebox_' style='border-top: 1px solid #ccc; background-color: #f9f9f9;'>" + $clone.html() + "</div>";

                        // DOM 추가 위치: li 요소 안에 추가 (angle-link 다음에)
                        var $li = $(obj).closest('li');
                        if ($li.length > 0) {
                            // li 요소 안에 추가 (angle-link 다음에)
                            $li.append(resumeboxHtml);

                            // 추가 후 강제로 표시
                            var $resumebox = $('#resumebox_' + rc_idx);
                            if ($resumebox.length > 0) {
                                $resumebox.css({
                                    'display': 'block',
                                    'visibility': 'visible',
                                    'opacity': '1'
                                });
                                $resumebox.show();

                                // 부모 li도 표시 확인
                                $li.css({
                                    'display': 'block',
                                    'visibility': 'visible'
                                });
                                $li.show();
                            }
                        } else {
                            // fallback: 기존 방식
                            $(obj).parent().append(resumeboxHtml);
                            var $resumebox = $('#resumebox_' + rc_idx);
                            $resumebox.css({
                                'display': 'block',
                                'visibility': 'visible',
                                'opacity': '1'
                            });
                            $resumebox.show();
                        }

                    } //if

                });
            } else {
                console.error('[fShowChatListByRecruite] AJAX 오류 - code: ' + ret.code + ', msg: ' + ret.msg);
                commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
                return;
            }

            // 미확인 채팅건수 조회
            fSearchNoReadCount();

        });
    }

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
<!-- 제안한 인재탭의 공고 템플릿 -->
<template id="tpl_chat">
    <li class="list-item room-item" data-target_id="{{rc_idx}}">
        <a href="#" class="angle-link js-open-view" style="padding:0;" onclick="fShowChatListByRecruite('{{rc_idx}}',this); return false;">
            <div class="item-info person" style="width:100%; position: relative;">
                <div class="thumb-wrap">
                    <div class="thumb">
                        <img src="{{rc_thumb}}" alt="" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
                    </div>
                </div>
                <!-- 
                    data-room_idx_list="{{room_idx_list}}"의 의미:
                    공고(rc_idx)별로 관련된 채팅방(room_idx)들의 리스트(콤마 분리 문자열 등)가 이 속성에 세팅됩니다.
                    예: data-room_idx_list="101,102,103"이면, 해당 공고를 통해 오간 채팅방 room_idx가 101, 102, 103 총 3개 있음을 의미함.
                    여러 명에게 제안했을 때 생기는 채팅방들의 room_idx 식별용 데이터입니다.
                 -->
                <div class="content-wrap" data-room_idx_list="{{room_idx_list}}" data-noreadcnt="0">
                    <div class="timestamp" style="font-size: 12px; color: #777;">{{mm_comp_name}}</div>
                    <div class="title-wrap">
                        <p class="title"><strong>{{rc_name}}</strong></p>
                    </div>
                </div>
            </div>
        </a>
        <span class="room_rec_unread_cnt"></span>
    </li>
</template>
<!-- 제안한 인재탭의 인재 템플릿 -->
<template id="tpl_chat_by_recruite">
    <li class="list-item msg-item room-item room_idx_{{room_idx}}" data-target_id="{{room_idx}}">
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
            onclick="location.href='/chat/chat_view?rm_idx={{room_idx}}&from=talent_sent'; return false;">

            <div class="content-body" style="position: relative;">
                <div class="img" style="border-radius: 60px;">
                    <img src="{{profile_img}}" alt="" onerror="if(this.src.indexOf('avatar_icon2_m.png')===-1 && this.src.indexOf('avatar_resume_w.png')===-1){this.onerror=null; this.src='{{default_avatar}}';}">
                </div>
                <div class="desc">
                    <div class="wicon">{{ru_work_img}}</div>
                    <div class="title ellipsis">{{ru_name}}</div>

                    <div class="summary">
                        <p class="last-message">{{last_msg_preview}}</p>
                    </div>

                    <div class="summary3">
                        <p class="worktype">{{ru_work_type}}</p>
                    </div>
                </div>
            </div>
        </a>
        <button type="button" onclick="deleteItem(this.parentNode);" class="btn-primary btn-trash js-del-item">
            <span class="sr-text">삭제</span>
        </button>
        <span class="room_unread_cnt" id="room_idx_unread_cnt_{{room_idx}}"></span>
    </li>
</template>