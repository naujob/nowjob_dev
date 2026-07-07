<form id="frm-search" class="controls" method="get">
    <div class="contents-search">
        <div class="search-box">
            <h3 class="sub-title mb-10">매칭 채용공고 SMS 발송 (기업회원 대상)</h3>
            <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="600px"/>
                    <col width="150px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">검색어</th>
                    <td class="inline-wrap">
                        <div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",[""=>"전체","mm_comp_name"=>"사업장명","rc_name"=>"공고명","ad_addr"=>"지역"],$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                    <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <th scope="row">카테고리</th>
                    <td class="inline-wrap" colspan="1">
                        <?=inputCheck("","category[]",$select_category,$search["category"])?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">국적</th>
                    <td class="inline-wrap">
                        <?=inputRadio("rc_nation","rc_nation",[""=>"전체","내국인"=>"내국인","외국인"=>"외국인"],$search["rc_nation"])?>
                    </td>
                    <th scope="row">성별</th>
                    <td class="inline-wrap">
                        <?=inputRadio("rc_sex","rc_sex",[""=>"전체","남자"=>"남자","여자"=>"여자"],$search["rc_sex"])?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">근무형태</th>
                    <td class="inline-wrap">
                        <?=inputCheck("","rc_work_type[]",$select_work,$search["rc_work_type"])?>
                    </td>
                    <th scope="row">급여형태</th>
                    <td class="inline-wrap">
                        <?=inputCheck("","rc_price_type[]",$select_work_type,$search["rc_price_type"])?>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <button type="button" class="btn-m btn-type10 w100 btnSearch">검색</button>
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

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <p><strong>발송 대상: <?=$total_count?>건 (<?=$page?>/<?=$total_page > 0 ? $total_page : 1?>)</strong></p>
                <button type="button" class="btn-l btn-type3 w150 btnSelectResume">이력서 선택</button>
            </div>

            <table class="table-type2">
                <colgroup>
                    <col width="50px"/>
                    <col width="50px"/>
                    <col width="100px"/>
                    <col width="150px"/>
                    <col width="150px"/>
                    <col width="100px"/>
                    <col width="100px"/>
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
                                    <input type='checkbox' id='chk_all_Y' name='chk_all' value='Y' />
                                    <span style='padding-left:5px;'><strong></strong></span>
                                </label>
                            </div>
                        </th>
                        <th scope="col">번호</th>
                        <th scope="col">게시글상태</th>
                        <th scope="col">사업장명</th>
                        <th scope="col">공고명</th>
                        <th scope="col">국적</th>
                        <th scope="col">성별</th>
                        <th scope="col">근무형태</th>
                        <th scope="col">급여형태</th>
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
                                    <input type='checkbox' name='chk[]' value='<?=$row["mm_idx"]?>' />
                                    <span style='padding-left:5px;'><strong></strong></span>
                                </label>
                            </div>
                        </td>
                        <td class="tc"><?=$num_start + $key?></td>
                        <td class="tc"><?=(isset($row['rc_status']) && $row['rc_status'] === 'Y') ? '정상' : '비공개'?></td>
                        <td><?=isset($row['mm_comp_name']) ? $row['mm_comp_name'] : ''?></td>
                        <td><?=isset($row['rc_name']) ? $row['rc_name'] : ''?></td>
                        <td><?=isset($row['rc_nation']) ? $row['rc_nation'] : ''?></td>
                        <td><?=isset($row['rc_sex']) ? $row['rc_sex'] : ''?></td>
                        <td><?=isset($row['rc_work_type']) ? $row['rc_work_type'] : ''?></td>
                        <td><?=isset($row['rc_price_type']) ? $row['rc_price_type'] : ''?></td>
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
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
    // 전체선택 체크박스 연동
    $('#chk_all_Y').on('change', function(){
        var isChecked = $(this).is(':checked');
        $('input[name="chk[]"]').prop('checked', isChecked);
    });

    $('input[name="chk[]"]').on('change', function(){
        var allChecked = $('input[name="chk[]"]').length === $('input[name="chk[]"]:checked').length;
        $('#chk_all_Y').prop('checked', allChecked);
    });

    // 검색 버튼
    $(".btnSearch").click(function(){
        $("#frm-search").attr("action","/admin/ppurio/send_sms_corp");
        $("#frm-search").submit();
    });

    // 이력서 선택 버튼 - 채용공고 목록으로 이동
    $(".btnSelectResume").click(function(){
        // 선택된 기업 mm_idx 수집
        var selected_mm_idx = [];
        $('input[name="chk[]"]:checked').each(function(){
            selected_mm_idx.push($(this).val());
        });

        if (selected_mm_idx.length === 0) {
            COMMON.alert("기업을 선택해주세요.");
            return;
        }

        // 동적으로 hidden form을 만들어 POST로 제출
        var $form = $('<form>', { method: 'post', action: '/admin/ppurio/send_sms_corp_resume' });
        $.each(selected_mm_idx, function(i, val){
            $form.append($('<input>', { type: 'hidden', name: 'mm_idx[]', value: val }));
        });
        $form.appendTo('body').submit();
    });
});
</script>
