<div class="page page-chat-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title" id="rm_subject"></h1>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="chat-view-header">
            <!-- 중고판매정보 -->
            <div class="item-info sale ">
                <div class="thumb-wrap">
                    <!-- 즐겨찾기 추가된 목록: on -->
                    <button type="button" data-bk_type="TRADE" data-bk_key="<?= $row["td_idx"] ?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?= $row["book_yn"] == "Y" ? "on" : "" ?>"><span class="sr-text">즐겨찾기 해제</span></button>
                    <!-- 썸네일 -->
                    <div class="thumb"><img data-src="<?= $info["td_img1"] ?>" alt="" class="lozad"></div>
                </div>
                <div class="content-wrap">
                    <p class="title"><strong><?= $info["td_name"] ?></strong></p>
                    <div class="meta">
                        <p class="area">
                            <?php foreach ($addr_list as $key => $row) : ?>
                                <?= $key != 0 ? "," : "" ?><?= $row["ad_addr"] ?>
                            <?php endforeach; ?>
                        </p>
                    </div>
                    <div class="price-wrap">
                        <p class="amount"><em class="num"><?= number_format($info["td_price"]) ?></em> 원</p>
                        <p class="date"><?= get_yyyymmdd($info["reg_dt"]) ?></p>
                    </div>
                    <div class="meta">
                        <p class="nego txt-red"><?= $info["td_price_req_yn"] == "Y" ? "가격제안불가" : "" ?></p>
                    </div>
                </div>
                <!-- 중고판매 상세 이동 -->
                <!--a href="javascript:void(0);" class="btn-wrapper"><span class="sr-text">상세보기</span></a-->
            </div>
            <!-- //중고판매정보 -->
        </div>
        <div class="chat-view-body">
            <div class="content" id="chat_body">
                <div class="container" id="chat_list">

                </div>
            </div>
            <!-- 입력폼 -->
            <div class="write-form">
                <label>
                    <span class="sr-text">메세지입력</span>
                    <input type="text" id="chatTxt" name="chatTxt" autocorrect="off" autocapitalize="off" autocomplete="off" style="text-align:left;" placeholder="메세지를 입력해주세요.">
                </label>
                <button type="button" id="btnSend" class="btn-primary">전송</button>
            </div>
        </div>
    </div><!-- //page-content -->
</div>


<script>
    var last_send_dt = "";
    var rm_idx = "<?= $rm_idx ?>";
    var rm_info = {};
    var rm_start = 0;
    var rm_length = 20;
    var rm_count = 0;

    if (rm_idx == null || rm_idx == "") {
        commonAlert("잘못된 접근입니다.", function() {
            location.href = "/chat/chat_list";
        });
    }
</script>

<script>
    var g_user_id = "";
    $(document).ready(function() {
        //룸정보 가져오기.
        g_user_id = $("#user_id").val();
        socket.emit("room_join", {
            rm_idx: Number(rm_idx),
            con_user_id: g_user_id
        });
        socket.emit("room_info", {
            rm_idx: Number(rm_idx),
            con_user_id: g_user_id
        });
    });

    //방생성 확인 및 방번호 확인
    socket.on("room_info_return", function(ret) {
        if (ret.code != "00") {
            alert("데이터 조회시 문제가 발생하였습니다. 관리자에게 문의 해주세요.");
        } else {
            //채팅방 정보
            rm_info = ret.data;

            $("#rm_subject").html(getRoomSubject(rm_info, g_user_id));

            //메시지 리스트 조회
            var params = {
                rm_idx: Number(rm_idx),
                skip: rm_start,
                limit: rm_length
            };
            socket.emit("message_list", params);
        }
    });

    //방접속시 알림
    socket.on("room_join_alarm", function(ret_user_id) {
        $(".readcount").html("");
    });

    var pagging_yn = false;
    $("#chat_body").scroll(function() {
        var scroll_top = $(this).scrollTop();
        if ($(this).scrollTop() < 300 && pagging_yn == false) {
            pagging_yn = true;

            //메시지 리스트 조회
            var params = {
                rm_idx: Number(rm_idx),
                skip: (++rm_start * rm_length),
                limit: rm_length
            };
            socket.emit("message_list", params);
        }
    });

    $("#btnSend").click(function() {
        var txt = $("#chatTxt").val();
        if (txt == "") {
            return;
        }

        var message = {
            rm_idx: Number(rm_idx),
            user_id: g_user_id,
            chat_type: "trade",
            mg_type: "text",
            mg_text: txt,
            mg_req_dt: "",
            mg_img: "",
            mg_file: "",
            mg_imoti: "",
            mg_dt: getCurrentDate(),
            mg_reads: new Array(),
        };

        //리드 카운터 초기화 한다.
        for (var icnt = 0; icnt < rm_info.rm_users.length; icnt++) {
            if (g_user_id == rm_info.rm_users[icnt].user_id) {
                message.mg_reads.push({
                    read_yn: "Y",
                    user_id: rm_info.rm_users[icnt].user_id
                });
            } else {
                message.mg_reads.push({
                    read_yn: "N",
                    user_id: rm_info.rm_users[icnt].user_id
                });
            }
        }

        //채팅방 실시간 쏘기
        socket.emit("message_insert", message, rm_info.rm_users);

        //채팅창 클리어
        $("#chatTxt").val("");

        //setTimeout(function(){
        //    $("#chatTxt").focus();
        //},200);
    });

    /////////////////////////////////////////////////////////
    // 리턴 처리
    /////////////////////////////////////////////////////////

    //메시지 정보 리턴
    socket.on("message_insert_return", function(ret) {
        var message_list = ret.data;
        for (var icnt = 0; icnt < message_list.length; icnt++) {
            showMessage(message_list[icnt], false);
            //메시지 리스트 조회
            var params = {
                rm_idx: Number(rm_idx),
                mg_idx: Number(message_list[icnt].mg_idx),
                user_id: g_user_id
            };
            socket.emit("message_read_return", params);
        }
        scrollBottom();
    });

    //메시지 정보 리턴
    socket.on("message_read_count_return", function(ret) {
        //리턴받은 카운트 메시지 정보 업데이트
        $("[id^=mg_idx_]").last().prop("id", "mg_idx_" + ret.data.mg_idx)
        if (ret.data.count == 0) {
            $("[id^=mg_idx_]").last().find(".readcount").html("");
        } else {

            $("[id^=mg_idx_]").last().find(".readcount").html(ret.data.count);
        }
    });


    //방생성 확인 및 방번호 확인
    socket.on("message_list_return", function(ret) {
        //메시지 리스트 가져오기
        var message_list = ret.data;
        for (var icnt = 0; icnt < message_list.length; icnt++) {
            showMessage(message_list[icnt].rm_messages, true);
        }
        if (pagging_yn == false) {
            scrollBottom();
        }
        pagging_yn = false;
    });

    //방 조인
    socket.on("room_join_return", function(ret) {
        $(".readcount").html("");
    });

    //접속 끊길때 방 정리
    socket.on("room_leave_alarm", function(ret) {
        alert("접속이 끊겼습니다.");
        socket.emit("room_leave", {
            rm_idx: Number(rm_idx)
        });
    });

    //방해제 처리
    socket.on("room_leave_return", function(ret) {
        alert("방에 접속하셨습니다.");
    });

    //메시지 출력 처리
    var user_type = $("[name='user_type']").val();

    function showMessage(info, is_prepend) {
        //유형에 따른 처리
        if (info.mg_type == "timeline") {
            var content = "<p class='chat-divider'>";
            content += "         " + getFormatDate(new Date(info.mg_dt), 'kor');
            content += "</p>";
        } else {
            var your_text_yn = (info.user_id == g_user_id ? "Y" : "N");
            var item = info.user_id + "^" + getFormatDate(new Date(info.mg_dt), 'ymd') + "^" + getFormatDate(new Date(info.mg_dt), 'hh:mm');

            if (info.mg_type == "text") {
                if (your_text_yn == "Y") {
                    var readcount = 0;
                    for (var icnt = 0; icnt < info.mg_reads.length; icnt++) {
                        if (info.mg_reads[icnt].read_yn == "N") {
                            readcount++;
                        }
                    }
                    var content = "<div class='msg-item chat-bubble' id='mg_idx_" + info.mg_idx + "' data-info='" + item + "' >";
                    content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />");
                    if (readcount > 0) {
                        content += "    <div class='readcount'>" + readcount + "</div>";
                    } else {
                        content += "    <div class='readcount'></div>";
                    }
                    content += "    </div>";
                    content += "    <div class='meta'>";
                    content += "        <p class='date-time'>" + getFormatDate(new Date(info.mg_dt), 'hh:mm') + "</p>";
                    content += "    </div>";
                    content += "</div>";
                } else {
                    var content = "<div class='msg-item chat-bubble diff' data-info='" + item + "' >";
                    content += "    <div class='bubble-content'>" + info.mg_text.replace(/\n/g, "<br />") + "</div>";
                    content += "    <div class='meta'>";
                    content += "        <p class='date-time'>" + getFormatDate(new Date(info.mg_dt), 'hh:mm') + "</p>";
                    content += "    </div>";
                    content += "</div>";
                }
            } else if (info.mg_type == "file") {

            } else if (info.mg_type == "img") {

            }
        }

        if (is_prepend == true) {
            $("#chat_list").prepend(content);
        } else {
            $("#chat_list").append(content);
        }
    }

    function scrollBottom() {
        $("#chat_body").scrollTop($("#chat_body")[0].scrollHeight);
    }
</script>