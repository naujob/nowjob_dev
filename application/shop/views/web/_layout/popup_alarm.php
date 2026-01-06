<?php
$user_id = $this->user->getUserId();

$alarm_list = [];

if ($user_id != "") {
    $tbl_alarm = get_mongodb("alarm");
    $alarm_list = $tbl_alarm->find(["user_id" => $user_id])->sort(['reg_dt' => -1]);
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
                                <li data-type="<?= $alarm["al_type"] ?>">
                                    <p class="title"><?= $alarm["al_text"] ?></p>
                                    <p class="date"><?= $alarm["reg_dt"] ?></p>
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
    //카운터 업데이트
    $(".btn-alarm").click(function() {
        //쇼트 주소 검색
        comAjax("/common_proc/update_alarm_show", {}, function(ret) {
            if (ret.code == "00") {
                $(".btn-alarm").find(".cnt").html("0");
            }
        });
    });

    // 알림 카운트 업데이트 함수
    function update_alarm_count() {
        // 로그인 체크
        if ($("#is_login").val() != "Y") {
            return;
        }

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

    // Socket.IO 실시간 업데이트 리스너 등록 함수
    function setupAlarmSocketListeners() {
        if (typeof window.socket !== 'undefined' && window.socket) {
            // 기존 리스너 제거 (중복 방지)
            window.socket.off("message_list_refresh");
            window.socket.off("alarm_refresh");

            // 1. 메시지 리스트 새로고침 이벤트
            window.socket.on("message_list_refresh", function(ret) {
                if ($("#is_login").val() == "Y") {
                    setTimeout(function() {
                        update_alarm_count();
                    }, 300);
                }
            });

            // 2. 알림 새로고침 이벤트 (직접 카운트 전달)
            window.socket.on("alarm_refresh", function(data) {
                if (data && typeof data.count !== 'undefined') {
                    const count = data.count || 0;
                    const $cnt = $(".btn-alarm").find(".cnt");
                    if ($cnt.length > 0) {
                        $cnt.html(count);
                    }
                }
            });

            console.log('알림 Socket.IO 리스너 등록 완료');
            return true;
        }
        return false;
    }

    // Socket.IO 연결 대기 및 리스너 등록
    $(document).ready(function() {
        // Socket.IO 연결 확인 및 리스너 등록
        function trySetupListeners() {
            if (setupAlarmSocketListeners()) {
                // 연결 성공 시 초기 카운트 조회
                if ($("#is_login").val() == "Y") {
                    setTimeout(function() {
                        update_alarm_count();
                    }, 500);
                }
            } else {
                // Socket이 아직 준비되지 않았으면 재시도
                setTimeout(trySetupListeners, 100);
            }
        }

        // 즉시 시도
        trySetupListeners();

        // Socket.IO connect 이벤트에서도 등록 (연결이 늦게 완료될 경우 대비)
        if (typeof window.socket !== 'undefined') {
            window.socket.on('connect', function() {
                console.log('Socket 연결 완료, 알림 리스너 등록');
                setupAlarmSocketListeners();
                if ($("#is_login").val() == "Y") {
                    setTimeout(function() {
                        update_alarm_count();
                    }, 500);
                }
            });
        }

        // connection_return 이벤트에서도 등록
        if (typeof window.socket !== 'undefined') {
            window.socket.on('connection_return', function() {
                console.log('Socket connection_return, 알림 리스너 등록');
                setupAlarmSocketListeners();
                if ($("#is_login").val() == "Y") {
                    setTimeout(function() {
                        update_alarm_count();
                    }, 500);
                }
            });
        }
    });

    // 실시간 메시지 정보 리스트 (글로벌 함수로 유지)
    function message_list_refresh(ret) {
        if ($("#is_login").val() == "Y") {
            setTimeout(function() {
                update_alarm_count();
            }, 300);
        }
    }
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