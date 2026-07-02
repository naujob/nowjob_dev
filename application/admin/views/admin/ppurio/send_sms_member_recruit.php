<form id="frm-send" class="controls" method="post">
    <div class="contents-search">
        <div class="search-box">
            <h3 class="sub-title mb-10">매칭 채용공고 SMS 발송 (개인회원 대상)</h3>
            <div style="margin-bottom: 20px;">
                <p><strong>발송 대상: <?=count($result_list)?>건</strong></p>
            </div>
        </div>

        <div class="search-result">
            <div style="margin-bottom: 10px;">
                <a href="/admin/ppurio/sendMemPpurioSms" class="btn-l btn-type10 w180">회원 목록으로</a>
            </div>

            <table class="table-type2">
                <colgroup>
                    <col width="50px"/>
                    <col width="50px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="150px"/>
                    <col width="120px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="150px"/>
                    <col width="150px"/>
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">
                            <div class='input-checkbox'>
                                <label>
                                    <input type='checkbox' id='chk_all_Y' name='chk_all' value='Y' disabled style='display:none;' />
                                    <span style='padding-left:5px;'><strong></strong></span>
                                </label>
                            </div>
                        </th>
                        <th scope="col">번호</th>
                        <th scope="col">게시글상태</th>
                        <th scope="col">노출여부</th>
                        <th scope="col">회원명</th>
                        <th scope="col">공고명</th>
                        <th scope="col">기간</th>
                        <th scope="col">국적</th>
                        <th scope="col">성별</th>
                        <th scope="col">지역</th>
                        <th scope="col">카테고리</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($result_list) > 0) : ?>
                    <?php foreach($result_list as $key => $row) : ?>
                    <tr>
                        <td class="tc">
                            <div class='input-checkbox'>
                                <label>
                                    <input type='checkbox' name='chk[]' value='<?=$row["recruit"]["rc_idx"]?>' />
                                    <span style='padding-left:5px;'><strong></strong></span>
                                </label>
                            </div>
                        </td>
                        <td class="tc"><?=$key + 1?></td>
                        <td class="tc"><?=(isset($row['recruit']['rc_status']) && $row['recruit']['rc_status'] === 'Y') ? '정상' : '비공개'?></td>
                        <td class="tc"><?=(isset($row['recruit']['rc_show_yn']) && $row['recruit']['rc_show_yn'] === 'Y') ? '노출' : '비노출'?></td>
                        <td><?=isset($row['resume']['mm_name']) ? $row['resume']['mm_name'] : ''?></td>
                        <td><?=isset($row['recruit']['rc_name']) ? $row['recruit']['rc_name'] : ''?></td>
                        <td class="tc"><?php
                            if (isset($row['recruit']['rc_sdate']) && isset($row['recruit']['rc_edate'])) {
                                echo substr($row['recruit']['rc_sdate'], 0, 10) . '<br/>~' . substr($row['recruit']['rc_edate'], 0, 10);
                            }
                        ?></td>
                        <td><?=isset($row['recruit']['rc_nation']) ? $row['recruit']['rc_nation'] : ''?></td>
                        <td><?=isset($row['recruit']['rc_sex']) ? $row['recruit']['rc_sex'] : ''?></td>
                        <td><?=isset($row['recruit']['ad_addr']) ? $row['recruit']['ad_addr'] : ''?></td>
                        <td><?=isset($row['recruit']['rc_work']) ? $row['recruit']['rc_work'] : ''?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="11" class="tc" style="padding: 20px;">발송 대상이 없습니다.</td>
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
$(document).ready(function(){
    // 채용공고 체크박스: 한 개만 선택되도록
    $('input[name="chk[]"]').on('change', function(){
        if ($(this).is(':checked')) {
            // 현재 선택된 항목을 제외한 나머지 모두 해제
            $('input[name="chk[]"]').not(this).prop('checked', false);
        }
    });

    // SMS 발송 버튼
    $(".btnSendSms").click(function(){
        // 선택된 채용공고 rc_idx 수집
        var selected_rc_idx = [];
        $('input[name="chk[]"]:checked').each(function(){
            selected_rc_idx.push($(this).val());
        });

        if (selected_rc_idx.length === 0) {
            COMMON.alert("채용공고를 선택해주세요.");
            return;
        }

        COMMON.confirm("선택한 채용공고로 SMS를 발송하시겠습니까?", function() {
            var params = {};
            params["selected_rc_idx[]"] = selected_rc_idx;
            comAjaxLoading("/admin/ppurio/sendMemPpurioSmsRecruit_proc", params, function(result) {
                if (result.code == "00") {
                    COMMON.alert(result.msg, function() { location.reload(); });
                } else {
                    COMMON.alert(result.msg);
                }
            });
        });
    });
});
</script>
