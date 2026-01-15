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

// 상대 시간 포맷팅 함수
function formatRelativeTime($reg_dt) {
    // 현재 시간 (UTC 기준)
    $now = new DateTime('now', new DateTimeZone('UTC'));
    $alarm_time = null;
    
    // MongoDB UTCDateTime 객체인 경우
    if ($reg_dt instanceof MongoDB\BSON\UTCDateTime) {
        $alarm_time = $reg_dt->toDateTime();
        $alarm_time->setTimezone(new DateTimeZone('UTC'));
    }
    // DateTime 객체인 경우
    else if ($reg_dt instanceof DateTime) {
        $alarm_time = clone $reg_dt;
        $alarm_time->setTimezone(new DateTimeZone('UTC'));
    }
    // 문자열인 경우
    else if (is_string($reg_dt) && !empty($reg_dt)) {
        $timestamp = strtotime($reg_dt);
        if ($timestamp !== false) {
            $alarm_time = new DateTime('@' . $timestamp, new DateTimeZone('UTC'));
        }
    }
    
    // 날짜 변환 실패 시 빈 문자열 반환
    if ($alarm_time === null) {
        return "";
    }
    
    // 시간 차이 계산 (초 단위)
    $diff_seconds = $now->getTimestamp() - $alarm_time->getTimestamp();
    
    // 24시간 이내 (86400초 = 24시간)
    if ($diff_seconds < 86400) {
        $diff_minutes = floor($diff_seconds / 60);
        
        // 1시간 미만 (60분 미만)
        if ($diff_minutes < 60) {
            // 1분 미만일 경우 "방금 전"으로 표시
            if ($diff_minutes < 1) {
                return "방금 전";
            }
            return $diff_minutes . "분 전";
        }
        // 1시간 이상
        else {
            $diff_hours = floor($diff_minutes / 60);
            return $diff_hours . "시간 전";
        }
    }
    // 24시간 이후
    else {
        $diff_days = floor($diff_seconds / 86400);
        return $diff_days . "일 전";
    }
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
                            <?php 
                            /*
                                $alarm_list 는 현재 로그인한 사용자의 알림(노티피케이션) 정보를 담고 있는 배열입니다.
                                MongoDB에서 아래 조건에 맞는 알림 데이터만 추출해 정렬해서 넣어둔 것입니다.
                                - 현재 로그인된 사용자의 user_id로 필터
                                - show_yn != "Y" (아직 읽지 않은 알림) 또는, 읽음(alarm)이더라도 show_dt가 7일 이내인 것만
                                따라서, $alarm_list 는 아직 읽지 않았거나 읽은 지 7일 이내인 알림 목록의 배열입니다.
                                아래의 foreach 문은 그 리스트를 순회합니다.
                            */
                            foreach ($alarm_list as $alarm) : ?>
                                <?php
                                // 방어적 처리: 비정상 데이터는 렌더링하지 않음
                                if (empty($alarm) || !is_array($alarm)) {
                                    continue;
                                }
                                ?>
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
                                // 날짜 포맷팅 (상대 시간으로 표시)
                                $reg_dt = isset($alarm["reg_dt"]) ? $alarm["reg_dt"] : "";
                                $formatted_date = formatRelativeTime($reg_dt);
                                
                                // 알림 텍스트 (빈 데이터 필터링용)
                                $al_text = isset($alarm["al_text"]) ? trim($alarm["al_text"]) : "";

                                // 알림 고유 ID 생성 (MongoDB _id 사용)
                                $alarm_id = "";
                                if (isset($alarm["_id"])) {
                                    if (is_object($alarm["_id"])) {
                                        $alarm_id = (string)$alarm["_id"];
                                    } else {
                                        $alarm_id = $alarm["_id"];
                                    }
                                }
                                
                                // 클래스 조합
                                $li_class = "";
                                if (!empty($chat_url)) {
                                    $li_class = "alarm-clickable";
                                }

                                // 서버 데이터가 비어있으면 li 생성하지 않음
                                // 텍스트/날짜가 없으면 빈 li로 보이므로 렌더링하지 않음
                                if ($al_text === "" && $formatted_date === "") {
                                    continue;
                                }
                                ?>
                                <li data-type="<?= $al_type ?>" data-rm-idx="<?= $al_key ?>" data-alarm-id="<?= $alarm_id ?>" <?= !empty($li_class) ? 'class="' . $li_class . '"' : '' ?>>
                                    <div class="title-wrap">
                                        <p class="title"><?= str_replace("[undefined]님의 ", "", $al_text) ?></p>
                                        <p class="date"><?= $formatted_date ?></p>
                                    </div>
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

    // 클릭한 알림 ID를 localStorage에 저장하는 함수
    function saveClickedAlarm(alarmId) {
        if (!alarmId) return;
        
        try {
            var clickedAlarms = JSON.parse(localStorage.getItem('clickedAlarms') || '[]');
            if (clickedAlarms.indexOf(alarmId) === -1) {
                clickedAlarms.push(alarmId);
                localStorage.setItem('clickedAlarms', JSON.stringify(clickedAlarms));
            }
        } catch (e) {
            console.error('localStorage 저장 오류:', e);
        }
    }

    // 페이지 로드 시 클릭한 알림에 read 클래스 추가
    function markClickedAlarms() {
        try {
            var clickedAlarms = JSON.parse(localStorage.getItem('clickedAlarms') || '[]');
            clickedAlarms.forEach(function(alarmId) {
                var $li = $('.alarm-list .alarm-clickable[data-alarm-id="' + alarmId + '"]');
                if ($li.length > 0) {
                    $li.addClass('read');
                }
            });
        } catch (e) {
            console.error('localStorage 읽기 오류:', e);
        }
    }

    // 페이지 로드 시 실행
    $(document).ready(function() {
        markClickedAlarms();
    });

    // 알림 패널이 열릴 때마다 클릭한 알림 표시
    $(document).on('click', '.js-toggle-panel[aria-controls="alarmPanel"], a[href="#alarmPanel"]', function() {
        setTimeout(function() {
            markClickedAlarms();
        }, 100);
    });

    // 알림 클릭 시 채팅방으로 이동
    $(document).on("click", ".alarm-list .alarm-clickable", function(e) {
        var $li = $(this);
        var al_type = $li.data("type");
        var rm_idx = $li.data("rm-idx");
        var alarm_id = $li.data("alarm-id");

        // 클릭한 알림 ID를 localStorage에 저장 (페이지 이동 전에 저장)
        saveClickedAlarm(alarm_id);

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
</style>