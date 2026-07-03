<form id="frm-send" class="controls" method="post">
    <div class="contents-search">
        <div class="search-box">
            <h3 class="sub-title mb-10">매칭 채용공고 SMS 발송 (개인회원 대상)</h3>
            <div style="margin-bottom: 20px;">
                <p><strong>발송 대상 채용공고: <?=count($result_list)?>건 | 선택된 회원: <?=count($selected_mm_idx)?>명</strong></p>
            </div>
        </div>

        <div class="search-result">
            <?php
            // 페이징 처리
            $pagesize = 20;
            $total_count = count($result_list);
            $total_page = ceil($total_count / $pagesize);
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($page < 1) $page = 1;
            if ($page > $total_page && $total_page > 0) $page = $total_page;

            $start_idx = ($page - 1) * $pagesize;
            $display_list = array_slice($result_list, $start_idx, $pagesize);
            ?>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 10px;">
                <div>
                    <a href="/admin/ppurio/sendMemPpurioSms?reset=1" class="btn-l btn-type10 w180">회원 목록으로</a>
                </div>
                <p style="margin: 0;"><strong><?=$total_count?>건 (<?=$page?>/<?=$total_page > 0 ? $total_page : 1?>)</strong></p>
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
                    <?php $num_start = $start_idx + 1; ?>
                    <?php foreach($display_list as $key => $row) : ?>
                    <tr>
                        <td class="tc">
                            <div class='input-checkbox'>
                                <label>
                                    <input type='checkbox' name='chk[]' value='<?=$row["rc_idx"]?>' />
                                    <span style='padding-left:5px;'><strong></strong></span>
                                </label>
                            </div>
                        </td>
                        <td class="tc"><?=$num_start + $key?></td>
                        <td class="tc"><?=(isset($row['rc_status']) && $row['rc_status'] === 'Y') ? '정상' : '비공개'?></td>
                        <td class="tc"><?=(isset($row['rc_show_yn']) && $row['rc_show_yn'] === 'Y') ? '노출' : '비노출'?></td>
                        <td><?=isset($row['mm_comp_name']) ? $row['mm_comp_name'] : ''?></td>
                        <td><?=isset($row['rc_name']) ? $row['rc_name'] : ''?></td>
                        <td class="tc"><?php
                            if (isset($row['rc_sdate']) && isset($row['rc_edate'])) {
                                echo substr($row['rc_sdate'], 0, 10) . '<br/>~' . substr($row['rc_edate'], 0, 10);
                            }
                        ?></td>
                        <td><?=isset($row['rc_nation']) ? $row['rc_nation'] : ''?></td>
                        <td><?=isset($row['rc_sex']) ? $row['rc_sex'] : ''?></td>
                        <td><?=isset($row['ad_addr']) ? $row['ad_addr'] : ''?></td>
                        <td><?=isset($row['rc_work']) ? $row['rc_work'] : ''?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="11" class="tc" style="padding: 20px;">발송 대상이 없습니다.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <!-- 페이지네이션 -->
            <?php if($total_page > 1) : ?>
            <div class="pagination" style="text-align: center; margin-top: 20px;">
                <?php
                $page_group = (int)(($page - 1) / 10) * 10;
                $loop_count = (($total_page - $page_group) >= 10) ? 10 : $total_page - $page_group;
                ?>

                <?php if($page_group > 0) : ?>
                    <a class="btn page-front" href="?<?php
                    $params = $_GET;
                    $params['page'] = 1;
                    echo http_build_query($params);
                    ?>" style="margin: 0 5px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; display: inline-block; text-decoration: none; color: #333;">맨앞</a>
                    <a class="btn page-prev" href="?<?php
                    $params = $_GET;
                    $params['page'] = max(1, $page_group - 9);
                    echo http_build_query($params);
                    ?>" style="margin: 0 5px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; display: inline-block; text-decoration: none; color: #333;">이전</a>
                <?php endif; ?>

                <?php for($p = $page_group + 1; $p <= $page_group + $loop_count; $p++) : ?>
                    <?php if($p == $page) : ?>
                        <span style="margin: 0 5px; padding: 5px 10px; background-color: #333; color: white; border-radius: 3px; display: inline-block;">
                            <strong><?=$p?></strong>
                        </span>
                    <?php else : ?>
                        <a href="?<?php
                        $params = $_GET;
                        $params['page'] = $p;
                        echo http_build_query($params);
                        ?>" style="margin: 0 5px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; display: inline-block; text-decoration: none; color: #333;">
                            <?=$p?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($total_page > 10 && $page_group + 10 < $total_page) : ?>
                    <a class="btn page-next" href="?<?php
                    $params = $_GET;
                    $params['page'] = $page_group + 11;
                    echo http_build_query($params);
                    ?>" style="margin: 0 5px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; display: inline-block; text-decoration: none; color: #333;">다음</a>
                    <a class="btn page-last" href="?<?php
                    $params = $_GET;
                    $params['page'] = $total_page;
                    echo http_build_query($params);
                    ?>" style="margin: 0 5px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; display: inline-block; text-decoration: none; color: #333;">맨뒤</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if(count($result_list) > 0) : ?>
            <div class="btn-wrap inline-wrap mt-40 tc">
                <button type="button" class="btn-l btn-type11 w150 btnSendSms">SMS 발송</button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
// 선택된 회원 mm_idx 배열 (PHP에서 내려온 값)
var selectedMmIdx = <?php echo json_encode($selected_mm_idx); ?>;

$(document).ready(function(){
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
            params["selected_mm_idx[]"] = selectedMmIdx;
            comAjaxLoading("/admin/ppurio/sendMemPpurioSmsRecruit_proc", params, function(result) {
                if (result.code == "00") {
                    // POST로 렌더링된 페이지이므로 reload 대신 1단계 목록으로 이동
                    COMMON.alert(result.msg, function() { location.href = "/admin/ppurio/sendMemPpurioSms?reset=1"; });
                } else {
                    COMMON.alert(result.msg);
                }
            });
        });
    });
});
</script>
