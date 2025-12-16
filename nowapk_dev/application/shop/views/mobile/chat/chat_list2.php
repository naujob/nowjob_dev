<div class="page page-chat">
    <div class="header">
        <h1 class="title">채팅방</h1>
    </div><!-- //header -->

    <div class="page-content">

        <div class="tab-container">
            <ul class="tab-list">
                <?php if ($user_type == "COMP") : ?>
                    <li class="on"><a href="/chat/chat_list" class="tab">1:1 채팅</a></li>
                    <li><a href="/chat/index_comp" class="tab">지원자목록</a></li>
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
                /* 아이콘을 아래로 회전 */
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
    $(document).ready(function() {

        getChatTargetList2();
        //룸정보 가져오기
        //g_user_id = $("#user_id").val();
        //socket.emit("room_list",{user_id : g_user_id,rm_type : "chat"});
    });

    function getChatTargetList2() {
        //지원자목록-org

        var getUrl = "get_chat_target_list2";
        <?php if ($user_type == "MEM") { ?>
            getUrl = "get_chat_target_list2_mem";
        <?php } ?>
        <?php if ($user_type == "COMP") { ?>
            getUrl = "get_chat_target_list2";
        <?php } ?>

        comAjax("/chat_proc/" + getUrl, {}, function(ret) {
            //console.log('get_chat_target_list2');
            //console.log(ret);
            $("#chat_list").html("");
            if (ret.code == "00") {
                var list = ret.data.list;
                list.forEach(function(row) {
                    var $clone = $("#tpl_chat2").clone();
                    var $content = $clone.html();
                    $content = $content.replaceAll("{{reg_dt}}", row.reg_dt);

                    $content = $content.replaceAll("{{room_idx}}", row.rm_idx);
                    $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
                    $content = $content.replaceAll("{{target_id}}", row.target_id);
                    $content = $content.replaceAll("{{user_id}}", row.user_id);
                    $content = $content.replaceAll("{{user_img}}", row.user_img);
                    $content = $content.replaceAll("{{user_name}}", row.user_name);
                    $content = $content.replaceAll("{{target_img}}", row.target_img);
                    $content = $content.replaceAll("{{target_name}}", row.target_name);

                    <?php if ($user_type == "MEM") { ?>
                        $content = $content.replaceAll("{{rc_thumb}}", (row.rc_thumb && row.rc_thumb.trim() !== '') ? row.rc_thumb : '/resource/shop/mobile/img/common/noimage4.png');
                        $content = $content.replaceAll("{{rc_work}}", row.rc_work);
                        $content = $content.replaceAll("{{rc_name}}", row.rc_name);
                        $content = $content.replaceAll("{{rc_nation}}", row.rc_nation);
                        $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
                    <?php } ?>
                    <?php if ($user_type == "COMP") { ?>
                        $content = $content.replaceAll("{{rc_thumb}}", (row.ru_img1 && row.ru_img1.trim() !== '') ? row.ru_img1 : '/resource/shop/mobile/img/common/noimage4.png');
                        $content = $content.replaceAll("{{rc_work}}", row.ru_work);
                        $content = $content.replaceAll("{{rc_name}}", row.ru_name);
                        $content = $content.replaceAll("{{rc_nation}}", row.mm_nation);
                        $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
                    <?php } ?>

                    //복사한 내용 다시 넣기
                    $clone.html($content);

                    $("#chat_list").append($clone.html());

                    //최신순으로 정렬
                    //var $list = $('#chat_list2');
                    //var $listItems = $list.children('.list-item');
                    //$listItems.sort(function(a, b) {
                    //    var dateA = new Date($(a).data('regdt'));
                    //    var dateB = new Date($(b).data('regdt'));
                    //    return dateB - dateA; // 최신순 정렬
                    //});
                    //$list.append($listItems);


                    //fSearchNoReadCount();

                    /*
                    //공고리스트에 미확인 채팅건수 조회 확인
                    comAjax("/chat_proc/fSearchNoReadCount",{},function(ret){

                    					const results = ret.data.result;

                    					// Iterate over each rm_idx and log the required information
                    					for (const key in results) {
                    							if (results.hasOwnProperty(key)) {
                    									const result = results[key];
                    									const { rm_idx, rm_subject, rm_messages } = result;
                    									let readNCount = 0;

                    									// Count the number of read_yn === 'Y'
                    									rm_messages.forEach(message => {
                    											message.mg_reads.forEach(read => {
                    													if (read.read_yn === 'N') {
                    															readNCount++;
                    													}
                    											});
                    									});

                    									// Log the information
                    									//console.log(`rm_idx: ${rm_idx}, rm_subject: ${rm_subject}, read_yn N count: ${readNCount}`);

                    									$('#chat_list2 .room-item').each(function() {
                    											//console.log($(this).html());
                    											if($(this).hasClass('room_idx_'+rm_idx) && $(this).data('noread')==undefined){
                    												//console.log('has');

                    												<?php if ($user_type == "MEM") { ?>
                    													$(this).append('<span class="chat_noread_cnt" style="top:45px;right:30px;">'+readNCount+'</span>');
                    												<?php } ?>
                    												<?php if ($user_type == "COMP") { ?>
                    													$(this).append('<span class="chat_noread_cnt" style="top:38px;right:30px;">'+readNCount+'</span>');
                    												<?php } ?>

                    												$(this).data('noread',readNCount);
                    											}
                    									});

                    							}
                    					}


                    }); //comAjax
                    */

                });

                //hide show 처리
                if ($("#chat_list").find("li").length == 0) {
                    $(".chat-empty").show();
                    $("#chat_list").hide();
                }

            } else {
                commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
                return;
            }

            fSearchNoReadCount();

        });
    }

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
            <div class="item-info person" style="width:100%;">
                <div class="thumb-wrap">
                    <div class="thumb" style="border-radius: unset;">
                        <img src="{{rc_thumb}}" alt="" onerror="this.src='/resource/shop/mobile/img/common/noimage4.png'">
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