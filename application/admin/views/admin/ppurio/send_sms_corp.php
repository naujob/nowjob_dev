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
                    <th scope="row">지역</th>
                    <td class="inline-wrap">
                        <input  type="text" name="ad_addr" placeholder="지역을 입력해주세요." value="<?=isset($search["ad_addr"]) ? htmlspecialchars($search["ad_addr"]) : ""?>" />
                    </td>
                    <th scope="row">노출여부</th>
                    <td class="inline-wrap">
                        <label class="input-radio">
                            <input type="radio" name="rc_show_yn" value="" <?=(!isset($search["rc_show_yn"]) || $search["rc_show_yn"] === "") ? "checked" : ""?> />
                            <span>전체</span>
                        </label>
                        <label class="input-radio">
                            <input type="radio" name="rc_show_yn" value="Y" <?=(isset($search["rc_show_yn"]) && $search["rc_show_yn"] === "Y") ? "checked" : ""?> />
                            <span>노출</span>
                        </label>
                        <label class="input-radio">
                            <input type="radio" name="rc_show_yn" value="N" <?=(isset($search["rc_show_yn"]) && $search["rc_show_yn"] === "N") ? "checked" : ""?> />
                            <span>비노출</span>
                        </label>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <button type="button" class="btn-m btn-type10 w100 btnSearch">검색</button>
            </div>
        </div>

        <div class="search-result">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <p><strong>발송 대상: <?=count($result_list)?>건</strong></p>
                <button type="button" class="btn-l btn-type3 w150 btnSelectResume">이력서 선택</button>
            </div>

            <table class="table-type2">
                <colgroup>
                    <col width="50px"/>
                    <col width="50px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="150px"/>
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
                                    <input type='checkbox' id='chk_all_Y' name='chk_all' value='Y' />
                                    <span style='padding-left:5px;'><strong></strong></span>
                                </label>
                            </div>
                        </th>
                        <th scope="col">번호</th>
                        <th scope="col">게시글상태</th>
                        <th scope="col">노출여부</th>
                        <th scope="col">사업장명</th>
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
                                    <input type='checkbox' name='chk[]' value='<?=$row["recruit"]["mm_idx"]?>' />
                                    <span style='padding-left:5px;'><strong></strong></span>
                                </label>
                            </div>
                        </td>
                        <td class="tc"><?=$key + 1?></td>
                        <td class="tc"><?=(isset($row['recruit']['rc_status']) && $row['recruit']['rc_status'] === 'Y') ? '정상' : '비공개'?></td>
                        <td class="tc"><?=(isset($row['recruit']['rc_show_yn']) && $row['recruit']['rc_show_yn'] === 'Y') ? '노출' : '비노출'?></td>
                        <td><?=isset($row['recruit']['mm_comp_name']) ? $row['recruit']['mm_comp_name'] : ''?></td>
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
        $("#frm-search").attr("action","/admin/ppurio/sendCorPpurioSms");
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

        if (selected_mm_idx.length > 1) {
            COMMON.alert("기업은 1개만 선택해주세요.");
            return;
        }

        // 선택된 기업을 쿼리스트링으로 전달
        var queryString = "?mm_idx=" + selected_mm_idx[0];
        location.href="/admin/ppurio/sendCorPpurioSmsResume" + queryString;
    });
});
</script>
