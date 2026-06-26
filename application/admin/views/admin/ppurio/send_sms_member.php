<form id="frm-search" class="controls" method="get">
    <div class="contents-search">
        <div class="search-box">
            <h3 class="sub-title mb-10">매칭 채용공고 SMS 발송 (개인회원 대상)</h3>
            <div style="margin-bottom: 20px;">
                <p><strong>발송 대상: <?=count($result_list)?>건</strong></p>
            </div>
        </div>

        <div class="search-result">
            <table class="table-type2">
                <colgroup>
                    <col width="60px"/>
                    <col width="150px"/>
                    <col width="120px"/>
                    <col width="250px"/>
                    <col width="100px"/>
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">회원명</th>
                        <th scope="col">전화번호</th>
                        <th scope="col">매칭 공고명</th>
                        <th scope="col">공고 IDX</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($result_list) > 0) : ?>
                    <?php foreach($result_list as $key => $row) : ?>
                    <tr>
                        <td class="tc"><?=$key + 1?></td>
                        <td><?=isset($row['resume']['mm_name']) ? $row['resume']['mm_name'] : ''?></td>
                        <td class="tc"><?=isset($row['resume']['mm_phone']) ? $row['resume']['mm_phone'] : ''?></td>
                        <td><?=isset($row['recruit']['rc_name']) ? $row['recruit']['rc_name'] : ''?></td>
                        <td class="tc"><?=isset($row['recruit']['rc_idx']) ? $row['recruit']['rc_idx'] : ''?></td>
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
            comAjaxLoading("/admin/ppurio/sendMemPpurioSms_proc", {}, function(result) {
                if (result.code == "00") {
                    COMMON.alert(result.msg, function() { location.reload(); });
                } else {
                    COMMON.alert(result.msg);
                }
            });
        });
    });
</script>
