<form id="frm-search" class="controls" method="get" action="/admin/ppurio/sms_cor_reslut">
    <div class="contents-search">
        <div class="search-box">
            <h3 class="sub-title mb-10">매칭 이력서 SMS 발송 내역</h3>
            <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="600px"/>
                    <col width="150px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">발송일</th>
                    <td class="inline-wrap">
                        <div class="input-text w100 pull-left">
                            <label>
                                <?=inputText("sdate","sdate",$search["sdate"],"시작일","date",20)?>
                            </label>
                        </div>
                        <span class="pull-left" style="padding: 0 5px; line-height: 38px;">~</span>
                        <div class="input-text w100 pull-left">
                            <label>
                                <?=inputText("edate","edate",$search["edate"],"종료일","date",20)?>
                            </label>
                        </div>
                    </td>
                    <th scope="row">검색어</th>
                    <td class="inline-wrap">
                        <div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",[""=>"전체","mm_name"=>"이력서주인","ru_name"=>"발송이력서","ad_addr"=>"지역"],$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                    <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
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
                                <input type="checkbox" name="category[]" value="" <?=empty($search["category"]) ? 'checked' : ''?> />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach($select_category as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="category[]" value="<?=$key?>" <?=in_array($key, $search["category"]) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <th scope="row">연령</th>
                    <td class="inline-wrap">
                        <div class="filter-checkbox-group">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="age_range[]" value="" <?=empty($search["age_range"]) ? 'checked' : ''?> />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach(["20대"=>"20대","30대"=>"30대","40대"=>"40대","50대"=>"50대","60대"=>"60대","70대"=>"70대"] as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="age_range[]" value="<?=$key?>" <?=in_array($key, (array)$search["age_range"]) ? 'checked' : ''?> />
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
                                <input type="checkbox" name="nationality[]" value="" <?=empty($search["nationality"]) ? 'checked' : ''?> />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach(["내국인"=>"내국인","외국인"=>"외국인"] as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="nationality[]" value="<?=$key?>" <?=in_array($key, (array)$search["nationality"]) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <th scope="row">성별</th>
                    <td class="inline-wrap">
                        <div class="filter-checkbox-group">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="gender[]" value="" <?=empty($search["gender"]) ? 'checked' : ''?> />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach(["남자"=>"남자","여자"=>"여자"] as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="gender[]" value="<?=$key?>" <?=in_array($key, (array)$search["gender"]) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">근무형태</th>
                    <td class="inline-wrap">
                        <div class="filter-checkbox-group">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="work_type[]" value="" <?=empty($search["work_type"]) ? 'checked' : ''?> />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach($select_work as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="work_type[]" value="<?=$key?>" <?=in_array($key, (array)$search["work_type"]) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <th scope="row">급여형태</th>
                    <td class="inline-wrap">
                        <div class="filter-checkbox-group">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="price_type[]" value="" <?=(isset($search["price_type"]) && !empty($search["price_type"])) ? '' : 'checked'?> />
                                <span class="custom-checkbox">전체</span>
                            </label>
                            <?php foreach(["시급"=>"시급","일급"=>"일급","주급"=>"주급","월급"=>"월급"] as $key => $val) : ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="price_type[]" value="<?=$key?>" <?=in_array($key, (array)$search["price_type"]) ? 'checked' : ''?> />
                                <span class="custom-checkbox"><?=$val?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <button type="button" class="btn-m btn-type10 w100 btnSearch">검색</button>
            </div>
        </div>

        <div style="text-align:right;margin-bottom:10px;">
            <a class="btn-m btn-type10" href="/admin/ppurio/sms_cor_chart?sdate=<?=urlencode($search["sdate"])?>&edate=<?=urlencode($search["edate"])?>">차트보기</a>
        </div>

        <div class="search-result">
            <table class="table-type2">
                <colgroup>
                    <col width="60px"/>
                    <col width="120px"/>
                    <col width="120px"/>
                    <col width="120px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="80px"/>
                    <col width="80px"/>
                    <col width="80px"/>
                    <col width="150px"/>
                    <col width="130px"/>
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">수신 채용공고</th>
                        <th scope="col">이력서주인</th>
                        <th scope="col">발송 이력서</th>
                        <th scope="col">국적</th>
                        <th scope="col">업종</th>
                        <th scope="col">성별</th>
                        <th scope="col">근무형태</th>
                        <th scope="col">급여형태</th>
                        <th scope="col">지역</th>
                        <th scope="col">발송일</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($result_list) > 0) : ?>
                    <?php foreach($result_list as $key => $row) : ?>
                    <tr>
                        <td class="tc"><?=$start - $key?></td>
                        <td class="tc"><?=isset($row['recruit_name']) && !empty($row['recruit_name']) ? $row['recruit_name'] : '-'?></td>
                        <td><?=isset($row['resume_owner_name']) ? $row['resume_owner_name'] : ''?></td>
                        <td><?=isset($row['resume_name']) ? $row['resume_name'] : ''?></td>
                        <td class="tc"><?=isset($row['resume_nation']) ? $row['resume_nation'] : ''?></td>
                        <td class="tc"><?=isset($row['resume_work']) ? $row['resume_work'] : ''?></td>
                        <td class="tc"><?=isset($row['resume_sex']) ? $row['resume_sex'] : ''?></td>
                        <td class="tc"><?=isset($row['resume_work_type']) ? $row['resume_work_type'] : ''?></td>
                        <td class="tc"><?=isset($row['resume_price_type']) ? $row['resume_price_type'] : ''?></td>
                        <td class="tc"><?=isset($row['resume_addr']) ? $row['resume_addr'] : ''?></td>
                        <td class="tc"><?=isset($row['sent_dt']) ? substr($row['sent_dt'], 0, 19) : ''?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="11" class="tc" style="padding: 20px;">데이터가 없습니다.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <?=$pagenation?>
        </div>
    </div>
</form>

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

<script>
$(document).ready(function(){
    // 검색 버튼
    $(".btnSearch").click(function(){
        $("#frm-search").submit();
    });

    // 필터 "전체" 옵션 자동 해제
    var filterNames = ['category[]', 'nationality[]', 'gender[]', 'age_range[]', 'work_type[]', 'price_type[]'];
    $.each(filterNames, function(i, filterName){
        // "전체"(value="") 선택 시 다른 옵션 해제
        $('input[name="' + filterName + '"][value=""]').on('change', function(){
            if($(this).is(':checked')){
                $('input[name="' + filterName + '"][value!=""]').prop('checked', false);
            }
        });

        // 일반 옵션 선택 시 "전체" 해제
        $('input[name="' + filterName + '"][value!=""]').on('change', function(){
            $('input[name="' + filterName + '"][value=""]').prop('checked', false);
        });
    });
});
</script>
