<form id="frm-send" class="controls" method="post">
    <div class="contents-search">
        <div class="search-box">
            <h3 class="sub-title mb-10">매칭 채용공고 SMS 발송 (개인회원 대상)</h3>
            <div style="margin-bottom: 20px;">
                <p><strong>발송 대상 채용공고: <?=count($result_list)?>건 | 선택된 회원: <?=count($selected_mm_idx)?>명</strong></p>
            </div>
        </div>

        <div class="search-box" style="margin-top: 20px;">
            <h3 class="sub-title mb-10" style="margin-top: 30px;">채용공고 검색</h3>
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
                    <td class="inline-wrap" colspan="3">
                        <div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",[""=>"전체","mm_comp_name"=>"사업장명","rc_name"=>"공고명","ad_addr"=>"지역"],isset($search["search_type"]) ? $search["search_type"] : "")?>
                            <div class="input-text">
                                <label>
                                    <?=inputText("search_value","search_value",isset($search["search_value"]) ? $search["search_value"] : "","검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">업종</th>
                    <td class="inline-wrap">
                        <div class="filter-checkbox-group">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="category[]" value="" />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach($select_category as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="category[]" value="<?=$key?>" <?=in_array($key, isset($search["category"]) ? $search["category"] : array()) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <th scope="row">근무형태</th>
                    <td class="inline-wrap">
                        <div class="filter-checkbox-group">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="rc_work_type[]" value="" />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach($select_work as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="rc_work_type[]" value="<?=$key?>" <?=in_array($key, isset($search["rc_work_type"]) ? $search["rc_work_type"] : array()) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">국적</th>
                    <td class="inline-wrap">
                        <div class="filter-checkbox-group">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="rc_nation[]" value="" />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach(["내국인"=>"내국인","외국인"=>"외국인"] as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="rc_nation[]" value="<?=$key?>" <?=in_array($key, (array)isset($search["rc_nation"]) ? $search["rc_nation"] : array()) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <th scope="row">성별</th>
                    <td class="inline-wrap">
                        <div class="filter-checkbox-group">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="rc_sex[]" value="" />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach(["남자"=>"남자","여자"=>"여자"] as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="rc_sex[]" value="<?=$key?>" <?=in_array($key, (array)isset($search["rc_sex"]) ? $search["rc_sex"] : array()) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">연령</th>
                    <td class="inline-wrap">
                        <div class="filter-checkbox-group">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="rc_age[]" value="" />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach(["20대"=>"20대","30대"=>"30대","40대"=>"40대","50대"=>"50대","60대"=>"60대","70대"=>"70대"] as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="rc_age[]" value="<?=$key?>" <?=in_array($key, (array)isset($search["rc_age"]) ? $search["rc_age"] : array()) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <th scope="row">급여형태</th>
                    <td class="inline-wrap">
                        <div class="filter-checkbox-group">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="rc_price_type[]" value="" />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach(["시급"=>"시급","일급"=>"일급","주급"=>"주급","월급"=>"월급"] as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="rc_price_type[]" value="<?=$key?>" <?=in_array($key, isset($search["rc_price_type"]) ? $search["rc_price_type"] : array()) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <button type="button" class="btn-m btn-type10 w100 btnSearchRecruit">검색</button>
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
                    <a href="/admin/ppurio/send_sms_member?reset=1" class="btn-l btn-type10 w180">회원 목록으로</a>
                </div>
                <p style="margin: 0;"><strong><?=$total_count?>건 (<?=$page?>/<?=$total_page > 0 ? $total_page : 1?>)</strong></p>
            </div>

            <table class="table-type2">
                <colgroup>
                    <col width="50px"/>
                    <col width="50px"/>
                    <col width="150px"/>
                    <col width="150px"/>
                    <col width="150px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="150px"/>
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
                        <th scope="col">사업자명</th>
                        <th scope="col">공고명</th>
                        <th scope="col">근무지역</th>
                        <th scope="col">추가 노출지역</th>
                        <th scope="col">연락처</th>
                        <th scope="col">업종</th>
                        <th scope="col">성별</th>
                        <th scope="col">국적</th>
                        <th scope="col">연령</th>
                        <th scope="col">급여형태</th>
                        <th scope="col">근무형태</th>
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
                        <td><?=isset($row['mm_comp_name']) ? $row['mm_comp_name'] : ''?></td>
                        <td><?=isset($row['rc_name']) ? $row['rc_name'] : ''?></td>
                        <td><?=isset($row['ad_addr']) ? $row['ad_addr'] : ''?></td>
                        <td><?=isset($row['expose_addrs']) && !empty($row['expose_addrs']) ? implode(', ', $row['expose_addrs']) : ''?></td>
                        <td><?=isset($row['mm_phone']) ? $row['mm_phone'] : ''?></td>
                        <td><?=isset($row['rc_work']) ? $row['rc_work'] : ''?></td>
                        <td><?=isset($row['rc_sex']) ? $row['rc_sex'] : ''?></td>
                        <td><?=isset($row['rc_nation']) ? $row['rc_nation'] : ''?></td>
                        <td><?php
                            $age_str = '';
                            if (isset($row['rc_sage']) && isset($row['rc_eage']) && $row['rc_sage'] != 0 && $row['rc_eage'] != 0) {
                                $age_str = $row['rc_sage'] . '~' . $row['rc_eage'];
                            }
                            echo $age_str;
                        ?></td>
                        <td><?=isset($row['rc_price_type']) ? $row['rc_price_type'] : ''?></td>
                        <td><?=isset($row['rc_work_type']) ? $row['rc_work_type'] : ''?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="13" class="tc" style="padding: 20px;">발송 대상이 없습니다.</td>
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
    // 전체선택 체크박스 연동
    $('#chk_all_Y').on('change', function(){
        var isChecked = $(this).is(':checked');
        $('input[name="chk[]"]').prop('checked', isChecked);
    });

    $('input[name="chk[]"]').on('change', function(){
        var allChecked = $('input[name="chk[]"]').length === $('input[name="chk[]"]:checked').length;
        $('#chk_all_Y').prop('checked', allChecked);
    });

    // Enter 키로 검색
    $('input[name="search_value"]').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $(".btnSearchRecruit").click();
        }
    });

    // 검색 버튼
    $(".btnSearchRecruit").click(function(){
        var search_type = $('select[name="search_type"]').val();
        var search_value = $('input[name="search_value"]').val();
        var category = [];
        $('input[name="category[]"]:checked').each(function(){
            category.push($(this).val());
        });
        var rc_age = [];
        $('input[name="rc_age[]"]:checked').each(function(){
            rc_age.push($(this).val());
        });
        var rc_nation = [];
        $('input[name="rc_nation[]"]:checked').each(function(){
            rc_nation.push($(this).val());
        });
        var rc_sex = [];
        $('input[name="rc_sex[]"]:checked').each(function(){
            rc_sex.push($(this).val());
        });
        var rc_work_type = [];
        $('input[name="rc_work_type[]"]:checked').each(function(){
            rc_work_type.push($(this).val());
        });
        var rc_price_type = [];
        $('input[name="rc_price_type[]"]:checked').each(function(){
            rc_price_type.push($(this).val());
        });

        // 동적으로 hidden form을 만들어 POST로 제출
        var $form = $('<form>', { method: 'post', action: '/admin/ppurio/send_sms_member_recruit' });
        if (search_type) {
            $form.append($('<input>', { type: 'hidden', name: 'search_type', value: search_type }));
        }
        if (search_value) {
            $form.append($('<input>', { type: 'hidden', name: 'search_value', value: search_value }));
        }
        $.each(selectedMmIdx, function(i, val){
            $form.append($('<input>', { type: 'hidden', name: 'mm_idx[]', value: val }));
        });
        $.each(category, function(i, val){
            $form.append($('<input>', { type: 'hidden', name: 'category[]', value: val }));
        });
        $.each(rc_age, function(i, val){
            $form.append($('<input>', { type: 'hidden', name: 'rc_age[]', value: val }));
        });
        $.each(rc_nation, function(i, val){
            $form.append($('<input>', { type: 'hidden', name: 'rc_nation[]', value: val }));
        });
        $.each(rc_sex, function(i, val){
            $form.append($('<input>', { type: 'hidden', name: 'rc_sex[]', value: val }));
        });
        $.each(rc_work_type, function(i, val){
            $form.append($('<input>', { type: 'hidden', name: 'rc_work_type[]', value: val }));
        });
        $.each(rc_price_type, function(i, val){
            $form.append($('<input>', { type: 'hidden', name: 'rc_price_type[]', value: val }));
        });
        $form.appendTo('body').submit();
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
            params["selected_mm_idx[]"] = selectedMmIdx;
            comAjaxLoading("/admin/ppurio/send_sms_member_recruit_proc", params, function(result) {
                if (result.code == "00") {
                    // POST로 렌더링된 페이지이므로 reload 대신 1단계 목록으로 이동
                    COMMON.alert(result.msg, function() { location.href = "/admin/ppurio/send_sms_member?reset=1"; });
                } else {
                    COMMON.alert(result.msg);
                }
            });
        });
    });
});
</script>

<style>
.filter-checkbox-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-checkbox {
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
}

.filter-checkbox input[type="checkbox"] {
    display: none;
}

.custom-checkbox {
    position: relative;
    padding-left: 25px;
    font-size: 14px;
    line-height: 1.5;
}

.custom-checkbox::before {
    content: "";
    position: absolute;
    top: 2px;
    left: 0;
    z-index: 2;
    width: 16px;
    height: 16px;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 16px;
    transition: all 0.3s ease;
}

.filter-checkbox input[type="checkbox"]:checked + .custom-checkbox::before {
    border: 4px solid #154993;
    background: #fff;
}
</style>
