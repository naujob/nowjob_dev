<div class="page page-chat">
    <div class="header">
        <h1 class="title">채팅방</h1>
    </div><!-- //header -->

    <div class="page-content">

        <div class="tab-container">
            <ul class="tab-list">
                <?php if ($user_type == "COMP") : ?>
                    <li><a href="/chat/index_comp" class="tab">제안받은 인재</a></li>
                    <li class="on"><a href="/chat/chat_list3" class="tab">제안보낸인재</a></li>
                <?php endif; ?>
                <?php if ($user_type == "MEM") : ?>
                    <li><a href="/chat/chat_list" class="tab">지원한공고</a></li>
                    <li class="on"><a href="/chat/chat_list2" class="tab">제안받은공고</a></li>
                    <!--<li><a href="/chat/index_comp" class="tab">지원자목록</a></li>
                    <li class="on"><a href="/chat/chat_list" class="tab">1:1 채팅</a></li>-->
                <?php endif; ?>
                <!--<li><a href="/chat/trade_list" class="tab">중고거래</a></li>-->
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
                <h2 class="sr-text">채용공고</h2>

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
    var g_user_id = "";
    // 뒤로가기 시 메인 페이지로 이동 (탭 간 이동 방지)
    (function() {
        if (window.history && window.history.pushState) {
            // 이전 히스토리를 메인 페이지로 교체
            window.history.replaceState(null, '', 'https://nowjob.co.kr/');
            // 현재 페이지를 히스토리에 추가
            window.history.pushState(null, '', window.location.href);
            // 뒤로가기 이벤트 감지
            window.addEventListener('popstate', function(event) {
                // 뒤로가기 시 메인 페이지로 이동
                window.location.href = 'https://nowjob.co.kr/';
            });
        }
    })();

    // 안드로이드와 웹을 구분하여 처리
    <?php if ($this->common->getSession("os_type") == "android") : ?>
        // 안드로이드 앱에서 실행
        window.addEventListener('DOMContentLoaded', function() {
            getChatTargetList(2);
        });

        // 페이지 복귀 시 미확인 메시지 갱신
        window.addEventListener('focus', function() {
            fSearchNoReadCount();
        });

        // 안드로이드에서도 소켓 이벤트 리스너 등록
        // 인재목록 클릭 시 채팅방 목록 업데이트

        /**
         * Socket.IO 이벤트: room_list_return
         * 
         * [주의] 현재 이 함수는 거의 실행되지 않습니다.
         * 
         * 실행 조건:
         * - socket.emit("room_list", ...) 호출 시 서버에서 room_list_return 이벤트를 emit하면 실행됨
         * - 현재 코드에서 socket.emit("room_list", ...) 호출이 대부분 주석 처리되어 있음 (435, 456번 라인)
         * - message_list_refresh 이벤트 핸들러(417번 라인)에서만 socket.emit("room_list", ...) 호출됨
         * 
         * 현재 데이터 로딩 방식:
         * - 실제로는 getChatTargetList() 함수가 AJAX(comAjax)로 데이터를 가져옴
         * - Socket.IO 기반 room_list 이벤트는 사용하지 않음
         * 
         * 함수 기능:
         * - 서버로부터 받은 채팅방 목록(ret)을 화면에 업데이트
         * - resumebox_가 있는 경우: 인재 목록을 보호하면서 기존 항목만 업데이트 (스마트 업데이트 모드)
         * - resumebox_가 없는 경우: 전체 리스트를 리셋하고 새로 렌더링
         * 
         * @param {Array} ret - 서버로부터 받은 채팅방 목록 배열
         *   - ret[].rm_idx: 채팅방 번호
         *   - ret[].not_read: 읽지 않은 메시지 수
         *   - ret[].rm_last_dt: 마지막 메시지 시간
         */
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

        //실시간 메시지 정보 리스트
        socket.on("message_list_refresh", function(ret) {
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
    <?php else: ?>
        // 웹에서 실행
        $(document).ready(function() {
            getChatTargetList(2);
            //룸정보 가져오기
            //g_user_id = $("#user_id").val();
            //socket.emit("room_list",{user_id : g_user_id,rm_type : "chat"});
        });

        // 페이지 복귀 시 미확인 메시지 갱신
        window.addEventListener('focus', function() {
            fSearchNoReadCount();
        });
    <?php endif; ?>

    function getChatTargetList(arg) {
        //지원자목록-공고목록
        //alert(arg);
        var getUrl = "get_chat_target_list_recruit";
        <?php if ($user_type == "MEM") { ?>
            getUrl = "get_chat_target_list_recruit_mem";
        <?php } ?>
        <?php if ($user_type == "COMP") { ?>
            getUrl = "get_chat_target_list_recruit2";
        <?php } ?>

        comAjax("/chat_proc/" + getUrl, {}, function(ret) { //_recruit


            //console.log("get_chat_target_list_recruit:"+g_user_id);
            //socket.emit("room_list2",{user_id : g_user_id,rm_type : "chat"});
            //채팅목록 call
            //socket.emit("room_list",{user_id : g_user_id,rm_type : "chat"});

            $("#chat_list").html("");
            //console.log('chat_list1');
            //console.log(ret);


            if (ret.code == "00") {
                var list = ret.data.list;

                if (list.length == 0) {
                    $(".chat-empty").show();
                    $("#chat_list").hide();
                    return;
                }

                list.forEach(function(row) {

                    <?php if ($user_type == "MEM") { ?>
                        var $clone = $("#tpl_chat_mem").clone();
                        if ($clone.length == 0) {
                            return;
                        }
                    <?php } ?>
                    <?php if ($user_type == "COMP") { ?>
                        var $clone = $("#tpl_chat").clone();
                        if ($clone.length == 0) {
                            return;
                        }
                    <?php } ?>

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

                    // 채팅방 개수 계산
                    var roomCount = 0;
                    if (row.rm_idx_list && row.rm_idx_list !== "()" && row.rm_idx_list.trim() !== "") {
                        // 괄호 제거하고 콤마로 분리
                        var roomIdxArray = row.rm_idx_list.replace(/[()]/g, '').split(',');
                        // 빈 문자열 필터링하고 숫자로 변환
                        roomIdxArray = roomIdxArray.filter(function(idx) {
                            return idx.trim() !== '';
                        });
                        roomCount = roomIdxArray.length;
                    }
                    $content = $content.replaceAll("{{room_count}}", roomCount);

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

                    var htmlContent = $clone.html();

                    $("#chat_list").append(htmlContent);

                    //미확인 채팅 있는지 확인
                    //socket.emit("room_info",{rm_idx : Number(rm_idx),con_user_id : g_user_id});
                    //socket.emit("room_list",{user_id : g_user_id,rm_type : "chat"});

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

        });
    }

    function fSearchNoReadCount() {

        // 현재 페이지의 chat_list 컨테이너 내의 content-wrap만 초기화
        $('#chat_list .content-wrap').each(function() {
            $(this).attr('data-noreadcnt', '0');
        });

        //공고리스트에 미확인 채팅건수 조회 확인
        comAjax("/chat_proc/fSearchNoReadCount", {}, function(ret) {
            console.log(ret);
            var myId = $("#user_id").val();

            let result = ret.data.result;

            // 모든 채팅방에 대한 미확인 메시지를 먼저 반영
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

                // 상위 공고 뱃지 집계
                if (count > 0) {
                    fSetNoReadCntRecruit(room.rm_idx, count);
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

    //더블클릭 이벤트 처리
    $(document).on("click", ".room-item", function(e) {
        // fShowChatListByRecruite 함수가 있는 경우에는 바로 채팅방으로 이동하지 않음
        if ($(this).find('.angle-link[onclick*="fShowChatListByRecruite"]').length > 0) {
            return; // fShowChatListByRecruite 함수가 처리하도록 함
        }

        var target_id = $(this).data("target_id");
        location.href = "/chat/chat_view?rm_idx=" + target_id + "&from=talent_sent";
    });

    // 웹에서도 소켓 이벤트 리스너 등록 (안드로이드와 동일)
    <?php if ($this->common->getSession("os_type") != "android") : ?>
        /////////////////////////////////////////////////////////
        // 리턴 처리
        /////////////////////////////////////////////////////////

        /**
         * Socket.IO 이벤트: room_list_return (웹 버전)
         * 
         * [주의] 현재 이 함수는 거의 실행되지 않습니다.
         * 
         * 실행 조건:
         * - socket.emit("room_list", ...) 호출 시 서버에서 room_list_return 이벤트를 emit하면 실행됨
         * - 현재 코드에서 socket.emit("room_list", ...) 호출이 주석 처리되어 있음 (435번 라인)
         * - message_list_refresh 이벤트 핸들러(820번 라인)에서만 socket.emit("room_list", ...) 호출됨
         * 
         * 현재 데이터 로딩 방식:
         * - 실제로는 getChatTargetList() 함수가 AJAX(comAjax)로 데이터를 가져옴
         * - Socket.IO 기반 room_list 이벤트는 사용하지 않음
         * 
         * 함수 기능:
         * - 서버로부터 받은 채팅방 목록(ret)을 화면에 업데이트
         * - resumebox_가 있는 경우: 인재 목록을 보호하면서 기존 항목만 업데이트 (스마트 업데이트 모드)
         * - resumebox_가 없는 경우: 전체 리스트를 리셋하고 새로 렌더링
         * 
         * @param {Array} ret - 서버로부터 받은 채팅방 목록 배열
         *   - ret[].rm_idx: 채팅방 번호
         *   - ret[].not_read: 읽지 않은 메시지 수
         *   - ret[].rm_last_dt: 마지막 메시지 시간
         */
        //회원리스트 가져오기
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

        //실시간 메시지 정보 리스트
        socket.on("message_list_refresh", function(ret) {
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
    <?php endif; ?>

    function fShowChatListByRecruite(ru_idx, obj) {
        // 주의: ru_idx 파라미터명이지만 실제로는 채용공고 번호(rc_idx)를 받음
        // ru_idx = Resume ID (이력서 번호)
        // rc_idx = Recruit ID (채용공고 번호)
        var rc_idx = ru_idx; // 실제로는 채용공고 번호

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

        //$('.chat-list').css('width','690px');
        //$('#chat_list1').css('width','50%');
        //$('#chat_list1').css('height','539px');
        //$('#chat_list1').css('border-right','1px solid #ccc');
        //$('#chat_list1_1').css('width','50%');

        //지원자목록-공고목록
        var params = {};
        params["ru_idx"] = rc_idx; // 백엔드에서 ru_idx로 받지만 실제로는 rc_idx 값
        params["chatgbn"] = $(obj).parent().parent().parent().attr('id');
        params["chatgbn"] = 'chatPanel2';

        comAjax("/chat_proc/get_chat_target_list", params, function(ret) {
            $("#chat_list1_1").html("");
            // 이미 토글에서 제거했으므로 여기서는 제거하지 않음


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
                        $content = $content.replaceAll("{{room_idx}}", row.rm_idx); //test row.room_idx 204
                        //$content = $content.replaceAll("{{rc_thumb}}",'/resource/uploads/member/58ae42eee368a36e2c2de2694d3d94c3.png'); //row.rc_thumb
                        $content = $content.replaceAll("{{target_name}}", row.target_name);
                        $content = $content.replaceAll("{{up_dt}}", row.up_dt);

                        // 백엔드에서 이미 처리된 ru_resume_img 사용 (hr_list.php 참고)
                        // 이미지가 없거나 공백이면 성별에 맞는 기본 아바타로 교체
                        var maleAvatarPath = "/resource/shop/web/img/avatar_icon2_m.png";
                        var femaleAvatarPath = "/resource/shop/web/img/avatar_resume_w.png";
                        var profileImg = (typeof row.ru_resume_img === "string") ? row.ru_resume_img.trim() : "";
                        var mmSex = (row.mm_sex || "").trim();
                        var defaultAvatar = femaleAvatarPath;
                        if (mmSex === "남") {
                            defaultAvatar = maleAvatarPath;
                        } else if (mmSex === "여") {
                            defaultAvatar = femaleAvatarPath;
                        }
                        if (!profileImg || profileImg.indexOf("noimage4.png") !== -1) {
                            profileImg = defaultAvatar;
                        }
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
                            ma_age = row.ma_age + "세";
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
                        if (row.ma_age != "" && row.ma_age != "0") meminfo += row.ma_age + "세";
                        $content = $content.replaceAll("{{meminfo}}", meminfo);

                        //복사한 내용 다시 넣기
                        $clone.html($content);

                        //$("#chat_list1_1").append($clone.html());

                        var resumeboxHtml = "<div id='resumebox_" + rc_idx + "' class='resumebox_" + rc_idx + " resumebox_' style='border-top: 1px solid #ccc;'>" + $clone.html() + "</div>";

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

                //hide show 처리
                /*
                if($("#chat_list1_1").find("li").length == 0){
                    $("#chat_empty1_1").show();
                    $("#chat_list1_1").hide();
                }else{
                    $("#chat_list1_1").show();
                    $("#chat_empty1_1").hide();
                }
                */

            } else {
                console.error('[fShowChatListByRecruite] AJAX 오류 - code: ' + ret.code + ', msg: ' + ret.msg);
                commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
                return;
            }

            //안 읽은 글 수 표시
            fSearchNoReadCount();

        });
    }
</script>

<template id="tpl_chat">
    <li class="list-item room-item" data-target_id="{{rc_idx}}">
        <a href="#" class="angle-link js-open-view" style="padding:0;" onclick="fShowChatListByRecruite('{{rc_idx}}',this); return false;">
            <div class="item-info person" style="width:100%; position: relative;">
                <div class="thumb-wrap">
                    <div class="thumb">
                        <img src="{{rc_thumb}}" alt="" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
                    </div>
                </div>
                <div class="content-wrap" data-room_idx_list="{{room_idx_list}}" data-noreadcnt="0">
                    <div class="timestamp" style="font-size: 12px; color: #777;">{{mm_comp_name}}</div>
                    <div class="title-wrap">
                        <p class="title"><strong>{{rc_name}}</strong></p>
                    </div>
                    <!-- 채팅방 개수 -->
                    <div class="meta" style="font-size: 12px; color: #777;">
                        <p class="room-count-text">채팅방 {{room_count}}명</p>
                    </div>
                </div>
            </div>
        </a>
        <span class="room_rec_unread_cnt"></span>
    </li>
</template>

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
                        <p>{{mm_nation}}</p>
                        <p>{{ma_age}}</p>

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