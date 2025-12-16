<div class="page page-chat">
    <div class="header">
        <h1 class="title">채팅방</h1>
    </div><!-- //header -->

    <div class="page-content">

        <div class="tab-container">
            <ul class="tab-list">
                <?php if ($user_type == "COMP") : ?>
                    <li><a href="/chat/index_comp" class="tab">채용공고</a></li>
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

            .room_unread_cnt {
                position: absolute;
                width: 26px;
                height: 26px;
                background-color: #F86048;
                top: 55px;
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

    // 안드로이드와 웹을 구분하여 처리
    <?php if ($this->common->getSession("os_type") == "android") : ?>
        // 안드로이드 앱에서 실행
        window.addEventListener('DOMContentLoaded', function() {
            getChatTargetList(2);
        });

        // 안드로이드에서도 소켓 이벤트 리스너 등록
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
                list.forEach(function(row) {
                    //console.log(row);

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

                    $("#chat_list").append($clone.html());

                    //미확인 채팅 있는지 확인
                    //socket.emit("room_info",{rm_idx : Number(rm_idx),con_user_id : g_user_id});
                    //socket.emit("room_list",{user_id : g_user_id,rm_type : "chat"});

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

        $('.content-wrap').each(function() {
            $(this).attr('data-noreadcnt', '0');
        });

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
                    fSetNoReadCntRecruit(room.rm_idx, count);
                }
            });

            $('.content-wrap').each(function() {
                // 현재 div의 data-noreadcnt 값을 가져오기
                let noReadCnt = parseInt($(this).attr('data-noreadcnt')) || 0;

                // data-noreadcnt 값이 0보다 큰 경우
                if (noReadCnt > 0) {
                    // 상위 li 요소 찾기
                    let parentLi = $(this).closest('li');

                    // li 내의 room_idx_unread_cnt 클래스를 가진 요소에 data-noreadcnt 값을 설정
                    parentLi.find('.room_rec_unread_cnt').text(noReadCnt);
                    parentLi.find('.room_rec_unread_cnt').show();
                }
            });

        });
    }

    function fSetNoReadCntRecruit(room_idx, cnt) {

        $('.content-wrap').each(function() {
            // data-room_idx_list 속성 값 가져오기
            let roomIdxList = $(this).attr('data-room_idx_list');

            if (roomIdxList) {
                // 괄호와 쉼표 제거 후 공백으로 분할하여 숫자 배열 생성
                let roomIdxArray = roomIdxList.replace(/[(),]/g, '').split(' ').map(Number);

                // 배열에 125가 포함되어 있는지 확인
                if (roomIdxArray.includes(room_idx)) {
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
        location.href = "/chat/chat_view?rm_idx=" + target_id;
    });

    // 웹에서도 소켓 이벤트 리스너 등록 (안드로이드와 동일)
    <?php if ($this->common->getSession("os_type") != "android") : ?>
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
    <?php endif; ?>

    function fShowChatListByRecruite(ru_idx, obj) {
        //console.log('fShowChatListByRecruite:'+ru_idx);
        console.log($(obj).parent().parent().parent().attr('id'));

        chatDetailShow = true;

        //$('.chat-list').css('width','690px');
        //$('#chat_list1').css('width','50%');
        //$('#chat_list1').css('height','539px');
        //$('#chat_list1').css('border-right','1px solid #ccc');
        //$('#chat_list1_1').css('width','50%');

        //지원자목록-공고목록
        var params = {};
        params["ru_idx"] = ru_idx;
        params["chatgbn"] = $(obj).parent().parent().parent().attr('id');
        params["chatgbn"] = 'chatPanel2';

        comAjax("/chat_proc/get_chat_target_list", params, function(ret) {
            $("#chat_list1_1").html("");
            $("#resumebox_" + ru_idx + "").remove();

            //console.log("get_chat_target_list");
            //console.log(ret);


            if (ret.code == "00") {
                var list = ret.data.list;
                var listCnt = 0;

                $('.resumebox_' + ru_idx).remove();

                list.forEach(function(row) {
                    listCnt++;
                    //console.log(row);
                    //console.log('req:'+ru_idx+',res:'+row.rc_idx);

                    if (ru_idx == row.rc_idx) {
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

                        $content = $content.replaceAll("{{rc_name}}", row.rc_name);
                        $content = $content.replaceAll("{{room_idx}}", row.rm_idx); //test row.room_idx 204
                        //$content = $content.replaceAll("{{rc_thumb}}",'/resource/uploads/member/58ae42eee368a36e2c2de2694d3d94c3.png'); //row.rc_thumb
                        $content = $content.replaceAll("{{target_name}}", row.target_name);
                        $content = $content.replaceAll("{{up_dt}}", row.up_dt);

                        var meminfo = "";
                        if (row.ru_work != "") meminfo = row.ru_work + "<br>";
                        if (row.mm_nation != "") meminfo += row.mm_nation;
                        if (row.mm_nation != "" && row.ma_age != "" && row.ma_age != "0") meminfo += " | ";
                        if (row.ma_age != "" && row.ma_age != "0") meminfo += row.ma_age + "세";
                        $content = $content.replaceAll("{{meminfo}}", meminfo);

                        //복사한 내용 다시 넣기
                        $clone.html($content);

                        //$("#chat_list1_1").append($clone.html());

                        $(obj).parent().append("<div id='resumebox_" + ru_idx + "' class='resumebox_" + ru_idx + "' style='border-top: 1px solid #ccc;'>" + $clone.html() + "</div>");

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
                commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
                return;
            }

            //안 읽은 글 수 표시
            fSearchNoReadCount();

        });
    }
</script>

<template id="tpl_chat">
    <li class="list-item room-item" data-target_id="{{room_idx}}">
        <a href="#" class="angle-link js-open-view" style="padding:0;" onclick="fShowChatListByRecruite('{{rc_idx}}',this); return false;">
            <div class="item-info person" style="width:100%;">
                <div class="thumb-wrap">
                    <div class="thumb" style="border-radius: unset;">
                        <img src="{{rc_thumb}}" alt="">
                    </div>
                </div>
                <div class="content-wrap" data-room_idx_list="{{room_idx_list}}" data-noreadcnt="0">
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
            onclick="location.href='/chat/chat_view?rm_idx={{room_idx}}'; return false;">

            <div class="thumb" style="border-radius: unset;"><img src="{{ru_resume_img}}" alt=""></div>
            <div class="mid" style="padding-top: 0.3rem;">
                <p class="name">{{target_name}}</p>
                <p class="latest">{{meminfo}}</p>
                <!-- <p class="latest"></p> -->
                <p class="time" style="color:#777; font-size: 0.8rem; font-weight: 400; letter-spacing: 0px;">{{up_dt}}</p>
            </div>
        </a>
        <button type="button" onclick="deleteItem(this.parentNode);" class="btn-primary btn-trash js-del-item">
            <span class="sr-text">삭제</span>
        </button>
        <span class="room_unread_cnt" id="room_idx_unread_cnt_{{room_idx}}"></span>
    </li>
</template>