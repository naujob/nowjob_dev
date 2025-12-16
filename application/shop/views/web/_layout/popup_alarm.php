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