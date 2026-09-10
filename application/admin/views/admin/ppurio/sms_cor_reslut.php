<form id="frm-search" class="controls" method="get" action="/admin/ppurio/sms_cor_reslut">
    <div class="contents-search">
        <div class="search-box">
            <!-- <h3 class="sub-title mb-10">매칭 이력서 SMS 발송 내역</h3> -->
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
                        <div class="sum-select-input" style="top: -16px;">
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
                  <tr>
                    <th scope="row">정렬</th>
                    <td class="inline-wrap">
                        <div class="sum-select-input" style="top: -16px;">
                            <?=inputSelect("sort_order","sort_order",["desc"=>"최신순","asc"=>"과거순"],(isset($search["sort_order"]) ? $search["sort_order"] : "desc"))?>
                        </div>
                    </td>
                    <td colspan="2"></td>
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
            <h4 class="sub-title mb-10">매칭 이력서 SMS 발송 내역</h4>
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

        <div class="search-result alimtalk-section">
            <h4 class="sub-title mb-10">매칭 이력서 알림톡 발송 내역</h4>
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
                <?php if(count($alimtalk_result_list) > 0) : ?>
                    <?php foreach($alimtalk_result_list as $key => $row) : ?>
                    <tr>
                        <td class="tc"><?=$alimtalk_start - $key?></td>
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
            <div class="pagination" style="text-align: center; margin-top: 20px;">
                <?php
                    $alimtalk_last_page = ceil($alimtalk_result_count / $alimtalk_pagesize);
                    if ($alimtalk_last_page > 1) {
                        $search_params = '?' . http_build_query([
                            'page' => isset($search['page']) ? $search['page'] : 1,
                            'sdate' => isset($search['sdate']) ? $search['sdate'] : '',
                            'edate' => isset($search['edate']) ? $search['edate'] : '',
                            'search_type' => isset($search['search_type']) ? $search['search_type'] : '',
                            'search_value' => isset($search['search_value']) ? $search['search_value'] : '',
                            'sort_order' => isset($search['sort_order']) ? $search['sort_order'] : 'desc',
                            'category' => isset($search['category']) ? $search['category'] : [],
                            'age_range' => isset($search['age_range']) ? $search['age_range'] : [],
                            'nationality' => isset($search['nationality']) ? $search['nationality'] : [],
                            'gender' => isset($search['gender']) ? $search['gender'] : [],
                            'work_type' => isset($search['work_type']) ? $search['work_type'] : [],
                            'price_type' => isset($search['price_type']) ? $search['price_type'] : [],
                        ]);
                        $url_base = '/admin/ppurio/sms_cor_reslut' . $search_params . '&apage=';

                        // 5페이지씩 블록 단위로 표시
                        $pages_per_block = 5;
                        $current_block = ceil($alimtalk_page / $pages_per_block);
                        $block_start = ($current_block - 1) * $pages_per_block + 1;
                        $block_end = min($current_block * $pages_per_block, $alimtalk_last_page);
                        $total_blocks = ceil($alimtalk_last_page / $pages_per_block);
                ?>
                    <?php if ($alimtalk_page > 1) { ?>
                        <a href="<?=$url_base?>1" style="margin: 0 5px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; display: inline-block; text-decoration: none; color: #333;">[처음]</a>
                        <a href="<?=$url_base?><?=$alimtalk_page-1?>" style="margin: 0 5px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; display: inline-block; text-decoration: none; color: #333;">[이전]</a>
                    <?php } ?>
                    <?php for ($i = $block_start; $i <= $block_end; $i++) { ?>
                        <?php if ($i == $alimtalk_page) { ?>
                            <span>
                                <strong><?=$i?></strong>
                            </span>
                        <?php } else { ?>
                            <a href="<?=$url_base?><?=$i?>" style="margin: 0 5px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; display: inline-block; text-decoration: none; color: #333;">
                                <?=$i?>
                            </a>
                        <?php } ?>
                    <?php } ?>
                    <?php if ($alimtalk_page < $alimtalk_last_page) { ?>
                        <a href="<?=$url_base?><?=$alimtalk_page+1?>" style="margin: 0 5px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; display: inline-block; text-decoration: none; color: #333;">[다음]</a>
                        <a href="<?=$url_base?><?=$alimtalk_last_page?>" style="margin: 0 5px; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; display: inline-block; text-decoration: none; color: #333;">[끝]</a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</form>


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
