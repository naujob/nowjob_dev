<?php
$user_id = $this->user->getUserId();

$alarm_list = [];

if ($user_id != "") {
    $tbl_alarm = get_mongodb("alarm");

    // 7일 전 날짜 계산 (읽음 처리된 알림 중 7일이 지난 것은 제외)
    $seven_days_ago = date('Y-m-d H:i:s', strtotime('-7 days'));

    // 알림 목록 조회 조건
    // - 미읽음 알림 (show_yn != "Y") 또는
    // - 읽음 알림이지만 7일 이내인 것 (show_yn == "Y" && show_dt >= 7일전)
    $query = [
        "user_id" => $user_id,
        '$or' => [
            ["show_yn" => ['$ne' => "Y"]],  // 미읽음 알림
            [
                "show_yn" => "Y",
                '$or' => [
                    ["show_dt" => ['$exists' => false]],  // show_dt가 없는 경우 (기존 데이터 호환)
                    ["show_dt" => ['$gte' => $seven_days_ago]]  // 7일 이내 읽음 처리된 알림
                ]
            ]
        ]
    ];

    $alarm_list = $tbl_alarm->find($query)->sort(['reg_dt' => -1]);
}
?>

<!-- 알림목록 -->
<div id="alarmPanel" class="layer-panel" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="panel-title">알림</h2>
            <button type="button" aria-controls="alarmPanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <div class="section">
                    <div class="alarm-list">
                        <ol class="list">
                            <?php foreach ($alarm_list as $alarm) : ?>
                                <?php //print_r($alarm_list); 
                                ?>
                                <?php
                                // 채팅방 이동을 위한 URL 생성
                                $chat_url = "";
                                $al_type = isset($alarm["al_type"]) ? $alarm["al_type"] : "";
                                $al_key = isset($alarm["al_key"]) ? $alarm["al_key"] : "";

                                // 메시지 관련 알림인 경우 채팅방으로 이동
                                if ($al_type == "recruit" || $al_type == "chat" || $al_type == "trade") {
                                    if (!empty($al_key)) {
                                        // 알림 타입에 따라 from 파라미터 설정
                                        $from_param = "applied";
                                        if ($al_type == "chat") {
                                            $from_param = "talent_received";
                                        } else if ($al_type == "trade") {
                                            $from_param = "trade";
                                        }
                                        $chat_url = "/chat/chat_view?rm_idx=" . $al_key . "&from=" . $from_param;
                                    }
                                }
                                ?>
                                <?php
                                // 날짜 포맷팅 (시간을 두 자리로 표시)
                                $reg_dt = $alarm["reg_dt"];
                                $formatted_date = "";
                                
                                // MongoDB UTCDateTime 객체인 경우
                                if ($reg_dt instanceof MongoDB\BSON\UTCDateTime) {
                                    $formatted_date = $reg_dt->toDateTime()->format('Y-m-d H:i:s');
                                }
                                // DateTime 객체인 경우
                                else if ($reg_dt instanceof DateTime) {
                                    $formatted_date = $reg_dt->format('Y-m-d H:i:s');
                                }
                                // 문자열인 경우
                                else if (is_string($reg_dt)) {
                                    $timestamp = strtotime($reg_dt);
                                    if ($timestamp !== false) {
                                        $formatted_date = date('Y-m-d H:i:s', $timestamp);
                                    } else {
                                        $formatted_date = $reg_dt;
                                    }
                                }
                                // 기타 경우
                                else {
                                    $formatted_date = $reg_dt;
                                }
                                ?>
                                <li data-type="<?= $al_type ?>" data-rm-idx="<?= $al_key ?>" <?= !empty($chat_url) ? 'class="alarm-clickable"' : '' ?>>
                                    <p class="title"><?= str_replace("[undefined]님의 ", "", $alarm["al_text"]) ?></p>
                                    <p class="date"><?= $formatted_date ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- //알림목록 -->



<script>
    function callVibrate() {
        var os_type = "<?= $os_type ?>";
        if (os_type == "android") {
            APP_NOWAPK.callVibrate();
        } else if (os_type == "ios") {
            window.webkit.messageHandlers.callVibrate.postMessage({});
        } else {
            // 네비게이션 사용해 진동 메소드 선언
            navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;

            if (navigator.vibrate) {
                navigator.vibrate(1000); // 진동 수행 1000 = 1초
            }
        }
    }

    function returnVib(ret) {
        alert(ret);
    }

    //카운터 업데이트
    $(".btn-alarm").click(function() {
        //쇼트 주소 검색
        comAjax("/common_proc/update_alarm_show", {}, function(ret) {
            if (ret.code == "00") {
                $(".btn-alarm").find(".cnt").html("0");
            }
        });
    });

    $(document).ready(function() {
        $(".btn-toggle-menu").click(function() {
            $("#menu").addClass("open");
            //callVibrate();
        });
    });
</script>

<script>
    function update_alarm_count() {
        comAjax("/common_proc/alarm_count", {}, function(ret) {
            if (ret.code == "00") {
                const count = ret.data.count || 0;
                const $cnt = $(".btn-alarm").find(".cnt");
                if ($cnt.length > 0) {
                    $cnt.html(count);
                }
            }
        });
    }

    function alarm_vibrate() {
        var os_type = "<?= $os_type ?>";
        if (os_type == "android") {
            if (typeof APP_NOWAPK !== 'undefined' && typeof APP_NOWAPK.callVibrate === 'function') {
                APP_NOWAPK.callVibrate();
            }
        } else if (os_type == "ios") {
            if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.callVibrate) {
                window.webkit.messageHandlers.callVibrate.postMessage({});
            }
        } else {
            if (navigator.vibrate) {
                navigator.vibrate(20);
            }
        }
    }

    $(document).ready(function() {
        if (typeof socket !== 'undefined') {
            socket.on("message_list_refresh", function(ret) {
                if ($("#is_login").val() == "Y") {
                    setTimeout(function() {
                        alarm_vibrate();
                        update_alarm_count();
                    }, 300);
                }
            });

            socket.on("alarm_refresh", function(data) {
                if (data && typeof data.count !== 'undefined') {
                    const count = data.count || 0;
                    const $cnt = $(".btn-alarm").find(".cnt");
                    if ($cnt.length > 0) {
                        $cnt.html(count);
                    }
                }
            });
        }
    });

    //실시간 메시지 정보 리스트
    function message_list_refresh(ret) {
        if ($("#is_login").val() == "Y") {
            setTimeout(function() {
                alarm_vibrate();
                update_alarm_count();
            }, 300);
        }
    }

    // 알림 클릭 시 채팅방으로 이동
    $(document).on("click", ".alarm-list .alarm-clickable", function() {
        var $li = $(this);
        var al_type = $li.data("type");
        var rm_idx = $li.data("rm-idx");

        // rm_idx가 있는 경우에만 이동
        if (rm_idx && rm_idx !== "") {
            // 알림 타입에 따라 from 파라미터 설정
            var from_param = "applied";
            if (al_type == "chat") {
                from_param = "talent_received";
            } else if (al_type == "trade") {
                from_param = "trade";
            }

            // 채팅방으로 이동
            location.href = "/chat/chat_view?rm_idx=" + rm_idx + "&from=" + from_param;
        }
    });
</script>

<style>
    .alarm-list .title small {
        display: block;
        font-size: 12px;
        color: #666;
        margin-top: 4px;
        font-weight: normal;
    }

    /* 클릭 가능한 알림 항목 스타일 */
    .alarm-list .alarm-clickable {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .alarm-list .alarm-clickable:hover {
        background-color: #f5f5f5;
    }

    .alarm-list .alarm-clickable:active {
        background-color: #e8e8e8;
    }
</style>