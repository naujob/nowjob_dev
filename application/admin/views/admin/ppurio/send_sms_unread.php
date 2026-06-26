<form id="frm-search" class="controls" method="get">
    <div class="contents-search">
        <div class="search-box">
            <h3 class="sub-title mb-10">지원자 미확인 알림 SMS 발송</h3>
            <div style="margin-bottom: 20px;">
                <p><strong>발송 대상: <?=count($result_list)?>건</strong></p>
            </div>
        </div>

        <div class="search-result">
            <table class="table-type2">
                <colgroup>
                    <col width="60px"/>
                    <col width="200px"/>
                    <col width="120px"/>
                    <col width="120px"/>
                    <col width="120px"/>
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">기업명</th>
                        <th scope="col">전화번호</th>
                        <th scope="col">이력서 IDX</th>
                        <th scope="col">채팅방 IDX</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($result_list) > 0) : ?>
                    <?php foreach($result_list as $key => $row) : ?>
                    <tr>
                        <td class="tc"><?=$key + 1?></td>
                        <td><?=isset($row['mm_comp_name']) ? $row['mm_comp_name'] : ''?></td>
                        <td class="tc"><?=isset($row['mm_phone']) ? $row['mm_phone'] : ''?></td>
                        <td class="tc"><?=isset($row['ru_idx']) ? $row['ru_idx'] : ''?></td>
                        <td class="tc"><?=isset($row['rm_idx']) ? $row['rm_idx'] : ''?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="tc" style="padding: 20px;">발송 대상이 없습니다.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <?php if(count($result_list) > 0) : ?>
            <div class="btn-wrap inline-wrap mt-40 tc">
                <button type="button" class="btn-l btn-type11 w150 btnSendSms">SMS 발송</button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
    $(".btnSendSms").click(function(){
        var send_count = <?=count($result_list)?>;
        COMMON.confirm("총 " + send_count + "건의 SMS를 발송하시겠습니까?", function() {
            comAjaxLoading("/admin/ppurio/sendPpurioSmsUnread_proc", {}, function(result) {
                if (result.code == "00") {
                    COMMON.alert(result.msg, function() { location.reload(); });
                } else {
                    COMMON.alert(result.msg);
                }
            });
        });
    });
</script>
