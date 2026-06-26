<form id="frm-search" class="controls" method="get">
    <div class="contents-search">
        <div class="search-box">
            <h3 class="sub-title mb-10">매칭 이력서 SMS 발송 (기업회원 대상)</h3>
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
                    <col width="250px"/>
                    <col width="100px"/>
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">기업명</th>
                        <th scope="col">전화번호</th>
                        <th scope="col">매칭 이력서명</th>
                        <th scope="col">이력서 IDX</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($result_list) > 0) : ?>
                    <?php foreach($result_list as $key => $row) : ?>
                    <tr>
                        <td class="tc"><?=$key + 1?></td>
                        <td><?=isset($row['recruit']['mm_comp_name']) ? $row['recruit']['mm_comp_name'] : ''?></td>
                        <td class="tc"><?=isset($row['recruit']['mm_phone']) ? $row['recruit']['mm_phone'] : ''?></td>
                        <td><?=isset($row['resume']['ru_name']) ? $row['resume']['ru_name'] : ''?></td>
                        <td class="tc"><?=isset($row['resume']['ru_idx']) ? $row['resume']['ru_idx'] : ''?></td>
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
            comAjaxLoading("/admin/ppurio/sendCorPpurioSms_proc", {}, function(result) {
                if (result.code == "00") {
                    COMMON.alert(result.msg, function() { location.reload(); });
                } else {
                    COMMON.alert(result.msg);
                }
            });
        });
    });
</script>
