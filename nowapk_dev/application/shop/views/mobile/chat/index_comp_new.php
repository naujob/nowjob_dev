<div class="page page-chat">
    <div class="header">
        <h1 class="title">채팅방</h1>
    </div><!-- //header -->
    <div class="page-content">
        <div class="tab-container">
            <ul class="tab-list">
                <li class="on"><a href="#" class="tab" data-tab="sent">제안보낸 인재</a></li>
                <li><a href="#" class="tab" data-tab="received">제안받은 인재</a></li>
            </ul>
        </div>

        <style>
            .chat-new-recruit-item {
                position: relative;
                padding: 1rem;
                border-bottom: 1px solid #eee;
            }

            .chat-new-recruit-item .recruit-header {
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                margin-bottom: 0.5rem;
            }

            .chat-new-recruit-item .recruit-thumb {
                width: 60px;
                height: 60px;
                border-radius: 8px;
                overflow: hidden;
                flex-shrink: 0;
            }

            .chat-new-recruit-item .recruit-thumb img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .chat-new-recruit-item .recruit-content {
                flex: 1;
                min-width: 0;
            }

            .chat-new-recruit-item .company-name {
                font-size: 0.75rem;
                color: #777;
                margin-bottom: 0.25rem;
            }

            .chat-new-recruit-item .recruit-title {
                font-size: 1rem;
                font-weight: 700;
                color: #333;
                margin-bottom: 0.25rem;
                word-break: break-word;
            }

            .chat-new-recruit-item .chat-preview {
                font-size: 0.875rem;
                color: #999;
                margin-bottom: 0.25rem;
                word-break: break-word;
            }

            .chat-new-recruit-item .chat-info {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 0.5rem;
            }

            .chat-new-recruit-item .chat-participants {
                font-size: 0.75rem;
                color: #777;
            }

            .chat-new-recruit-item .chat-time {
                font-size: 0.75rem;
                color: #777;
            }

            .chat-new-recruit-item .unread-badge {
                position: absolute;
                top: 1rem;
                right: 1rem;
                width: 24px;
                height: 24px;
                background-color: #F86048;
                border-radius: 50%;
                color: #fff;
                font-size: 0.75rem;
                display: none;
                align-items: center;
                justify-content: center;
                font-weight: 700;
            }

            .chat-new-recruit-item .expand-icon {
                position: absolute;
                bottom: 1rem;
                right: 1rem;
                width: 20px;
                height: 20px;
                background: url(/resource/shop/mobile/img/ic_angle_link.svg) no-repeat center;
                background-size: contain;
                transform: rotate(90deg);
            }

            .chat-new-talent-item {
                position: relative;
                padding: 1rem;
                border-bottom: 1px solid #eee;
                display: flex;
                align-items: flex-start;
                gap: 1rem;
            }

            .chat-new-talent-item .talent-thumb {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                overflow: hidden;
                flex-shrink: 0;
            }

            .chat-new-talent-item .talent-thumb img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .chat-new-talent-item .talent-content {
                flex: 1;
                min-width: 0;
            }

            .chat-new-talent-item .work-tags {
                display: flex;
                gap: 0.25rem;
                margin-bottom: 0.25rem;
                flex-wrap: wrap;
            }

            .chat-new-talent-item .work-tag {
                font-size: 0.75rem;
                padding: 0.125rem 0.5rem;
                border-radius: 4px;
                background-color: #f0f0f0;
                color: #666;
            }

            .chat-new-talent-item .talent-title {
                font-size: 1rem;
                font-weight: 700;
                color: #333;
                margin-bottom: 0.25rem;
                word-break: break-word;
            }

            .chat-new-talent-item .talent-info {
                font-size: 0.875rem;
                color: #777;
                margin-bottom: 0.25rem;
            }

            .chat-new-talent-item .talent-time {
                font-size: 0.75rem;
                color: #777;
                text-align: right;
            }

            .chat-new-talent-item .unread-badge {
                position: absolute;
                top: 1rem;
                right: 1rem;
                width: 24px;
                height: 24px;
                background-color: #F86048;
                border-radius: 50%;
                color: #fff;
                font-size: 0.75rem;
                display: none;
                align-items: center;
                justify-content: center;
                font-weight: 700;
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

            .talent-expanded-list {
                display: none;
                background-color: #f9f9f9;
            }

            .talent-expanded-list.show {
                display: block;
            }
        </style>

        <div class="tab-panel">
            <div class="container">
                <h2 class="sr-text">채팅 목록</h2>
                <div class="chat-list">
                    <ol class="list" id="chat_list_new">
                        <!-- 채팅방 목록이 여기에 동적으로 추가됩니다 -->
                    </ol>

                    <div class="chat-empty" id="chat_empty_new" style="display: none;">
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
    var chat_point = "<?= isset($point_info["cd_val"]) ? $point_info["cd_val"] : ""; ?>";
    var current_tab = "sent"; // 기본 탭: 제안보낸 인재

    // 탭 전환
    $(document).on("click", ".tab-list .tab", function(e) {
        e.preventDefault();
        var tab = $(this).data("tab");
        if (tab) {
            current_tab = tab;
            $(".tab-list li").removeClass("on");
            $(this).parent().addClass("on");

            // 해당 탭 데이터 로드
            if (tab == "sent") {
                getChatTargetListNewSent();
            } else if (tab == "received") {
                getChatTargetListNewReceived();
            }
        }
    });

    // 페이지 로드 시 초기 데이터 로드
    window.addEventListener('DOMContentLoaded', function() {
        getChatTargetListNewSent();
    });

    // HTML 이스케이프 헬퍼 함수
    function escapeHtml(text) {
        if (!text) return "";
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) {
            return map[m];
        });
    }

    // 문자열 치환 헬퍼 함수 (replaceAll 대신 사용)
    function replaceAll(str, search, replace) {
        return str.split(search).join(replace);
    }

    // 제안보낸 인재 목록 조회
    function getChatTargetListNewSent() {
        comAjax("/chat_proc/get_chat_target_list_new_sent", {}, function(ret) {
            $("#chat_list_new").html("");

            if (ret.code == "00") {
                var list = ret.data.list;
                if (list && list.length > 0) {
                    list.forEach(function(row) {
                        var $clone = $("#tpl_chat_new_recruit").clone();
                        var $content = $clone.html();

                        // 데이터 바인딩
                        $content = replaceAll($content, "{{rc_idx}}", row.rc_idx || "");
                        $content = replaceAll($content, "{{company_name}}", escapeHtml(row.company_name || ""));
                        $content = replaceAll($content, "{{rc_name}}", escapeHtml(row.rc_name || ""));
                        $content = replaceAll($content, "{{company_img}}", row.company_img || "/resource/shop/mobile/img/common/noimage4.png");
                        $content = replaceAll($content, "{{last_msg_preview}}", escapeHtml(row.last_msg_preview || ""));
                        $content = replaceAll($content, "{{last_msg_time}}", row.last_msg_time || "");
                        $content = replaceAll($content, "{{chat_participants}}", row.chat_participants || 0);
                        $content = replaceAll($content, "{{unread_count}}", row.unread_count || 0);
                        $content = replaceAll($content, "{{rm_idx_list}}", row.rm_idx_list || "");

                        $clone.html($content);
                        $("#chat_list_new").append($clone.html());

                        // 미확인 메시지 카운트 표시
                        if (row.unread_count && row.unread_count > 0) {
                            var $item = $("#chat_list_new").find(".chat-new-recruit-item").last();
                            $item.find(".unread-badge").text(row.unread_count).show();
                        }
                    });
                } else {
                    $("#chat_empty_new").show();
                    $("#chat_list_new").hide();
                }
            } else {
                commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
            }
        });
    }

    // 제안받은 인재 목록 조회
    function getChatTargetListNewReceived() {
        comAjax("/chat_proc/get_chat_target_list_new_received", {}, function(ret) {
            $("#chat_list_new").html("");

            if (ret.code == "00") {
                var list = ret.data.list;
                if (list && list.length > 0) {
                    list.forEach(function(row) {
                        var $clone = $("#tpl_chat_new_talent").clone();
                        var $content = $clone.html();

                        // 데이터 바인딩
                        $content = replaceAll($content, "{{rm_idx}}", row.rm_idx || "");
                        $content = replaceAll($content, "{{ru_idx}}", row.ru_idx || "");
                        $content = replaceAll($content, "{{ru_resume_img}}", row.ru_resume_img || "");
                        $content = replaceAll($content, "{{ru_work_type}}", row.ru_work_type || "");
                        $content = replaceAll($content, "{{mm_name}}", escapeHtml(row.mm_name || ""));
                        $content = replaceAll($content, "{{mm_nation}}", row.mm_nation || "");
                        $content = replaceAll($content, "{{ma_age}}", row.ma_age || "");
                        $content = replaceAll($content, "{{last_msg_time}}", row.last_msg_time || "");
                        $content = replaceAll($content, "{{unread_count}}", row.unread_count || 0);

                        $clone.html($content);
                        $("#chat_list_new").append($clone.html());

                        // 미확인 메시지 카운트 표시
                        if (row.unread_count && row.unread_count > 0) {
                            var $item = $("#chat_list_new").find(".chat-new-talent-item").last();
                            $item.find(".unread-badge").text(row.unread_count).show();
                        }
                    });
                } else {
                    $("#chat_empty_new").show();
                    $("#chat_list_new").hide();
                }
            } else {
                commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
            }
        });
    }

    // 채용공고 클릭 시 확장 기능
    function fShowChatListByRecruitNew(rc_idx, obj) {
        var $item = $(obj).closest(".chat-new-recruit-item");
        var $expandedList = $item.next(".talent-expanded-list");

        // 이미 확장되어 있으면 닫기
        if ($expandedList.length > 0 && $expandedList.hasClass("show")) {
            $expandedList.removeClass("show").html("");
            return;
        }

        // 확장 리스트가 없으면 생성
        if ($expandedList.length == 0) {
            $expandedList = $("<div class='talent-expanded-list'></div>");
            $item.after($expandedList);
        }

        // 인재 목록 조회
        comAjax("/chat_proc/get_chat_target_list_by_recruit_new", {
            rc_idx: rc_idx
        }, function(ret) {
            $expandedList.html("");

            if (ret.code == "00") {
                var list = ret.data.list;
                if (list && list.length > 0) {
                    list.forEach(function(row) {
                        var $clone = $("#tpl_chat_new_talent_expanded").clone();
                        var $content = $clone.html();

                        // 데이터 바인딩
                        $content = replaceAll($content, "{{rm_idx}}", row.rm_idx || "");
                        $content = replaceAll($content, "{{ru_idx}}", row.ru_idx || "");
                        $content = replaceAll($content, "{{ru_resume_img}}", row.ru_resume_img || "");
                        $content = replaceAll($content, "{{ru_work_type}}", row.ru_work_type || "");
                        $content = replaceAll($content, "{{mm_name}}", escapeHtml(row.mm_name || ""));
                        $content = replaceAll($content, "{{mm_nation}}", row.mm_nation || "");
                        $content = replaceAll($content, "{{ma_age}}", row.ma_age || "");
                        $content = replaceAll($content, "{{last_msg_time}}", row.last_msg_time || "");
                        $content = replaceAll($content, "{{unread_count}}", row.unread_count || 0);

                        $clone.html($content);
                        $expandedList.append($clone.html());

                        // 미확인 메시지 카운트 표시
                        if (row.unread_count && row.unread_count > 0) {
                            var $talentItem = $expandedList.find(".chat-new-talent-item").last();
                            $talentItem.find(".unread-badge").text(row.unread_count).show();
                        }
                    });
                }

                $expandedList.addClass("show");
            } else {
                commonAlert("일시적인 조회 오류가 발생하였습니다. 관리자에 문의 바랍니다.", function() {});
            }
        });
    }

    // 채팅방 클릭 이벤트
    $(document).on("click", ".chat-new-talent-item", function(e) {
        var rm_idx = $(this).data("rm_idx");
        if (rm_idx) {
            location.href = "/chat/chat_view?rm_idx=" + rm_idx;
        }
    });
</script>

<!-- 채용공고 타입 채팅방 템플릿 -->
<template id="tpl_chat_new_recruit">
    <li class="list-item">
        <div class="chat-new-recruit-item" onclick="fShowChatListByRecruitNew('{{rc_idx}}', this);">
            <div class="recruit-header">
                <div class="recruit-thumb">
                    <img src="{{company_img}}" alt="{{company_name}}">
                </div>
                <div class="recruit-content">
                    <div class="company-name">{{company_name}}</div>
                    <div class="recruit-title">{{rc_name}}</div>
                    <div class="chat-preview">{{last_msg_preview}}</div>
                    <div class="chat-info">
                        <div class="chat-participants">채팅방 {{chat_participants}}명</div>
                        <div class="chat-time">{{last_msg_time}}</div>
                    </div>
                </div>
            </div>
            <div class="unread-badge">{{unread_count}}</div>
            <div class="expand-icon"></div>
        </div>
    </li>
</template>

<!-- 인재 타입 채팅방 템플릿 -->
<template id="tpl_chat_new_talent">
    <li class="list-item">
        <div class="chat-new-talent-item" data-rm_idx="{{rm_idx}}">
            <div class="talent-thumb">
                <img src="{{ru_resume_img}}" alt="{{mm_name}}">
            </div>
            <div class="talent-content">
                <div class="work-tags">{{ru_work_type}}</div>
                <div class="talent-title">{{mm_name}}</div>
                <div class="talent-info">{{mm_nation}} / {{ma_age}}세</div>
                <div class="talent-time">{{last_msg_time}}</div>
            </div>
            <div class="unread-badge">{{unread_count}}</div>
        </div>
    </li>
</template>

<!-- 확장된 인재 목록 템플릿 -->
<template id="tpl_chat_new_talent_expanded">
    <div class="chat-new-talent-item" data-rm_idx="{{rm_idx}}">
        <div class="talent-thumb">
            <img src="{{ru_resume_img}}" alt="{{mm_name}}">
        </div>
        <div class="talent-content">
            <div class="work-tags">{{ru_work_type}}</div>
            <div class="talent-title">{{mm_name}}</div>
            <div class="talent-info">{{mm_nation}} / {{ma_age}}세</div>
            <div class="talent-time">{{last_msg_time}}</div>
        </div>
        <div class="unread-badge">{{unread_count}}</div>
    </div>
</template>