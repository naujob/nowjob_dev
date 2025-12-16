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
                                <?php //print_r($alarm_list); 
                                ?>
                                <li data-type="<?= $alarm["al_type"] ?>">
                                    <p class="title"><?= str_replace("[undefined]님의 ", "", $alarm["al_text"]) ?></p>
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