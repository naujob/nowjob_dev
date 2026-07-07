<form id="frm-search" class="controls" method="get" action="/admin/ppurio/sms_mem_reslut">
    <div class="contents-search">
        <div class="search-box">
            <h3 class="sub-title mb-10">매칭 채용공고 SMS 발송 내역</h3>
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
                    <td class="inline-wrap" colspan="3">
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
                </tr>
                <tr>
                    <th scope="row">검색어</th>
                    <td class="inline-wrap">
                        <div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",[""=>"전체","rc_name"=>"발송채용공고","mm_name"=>"수신개인","ad_addr"=>"지역"],$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                    <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <th scope="row">카테고리</th>
                    <td class="inline-wrap">
                        <?=inputCheck("","category[]",$select_category,$search["category"])?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">국적</th>
                    <td class="inline-wrap">
                        <?=inputRadio("nationality","nationality",[""=>"전체","내국인"=>"내국인","외국인"=>"외국인"],$search["nationality"])?>
                    </td>
                    <th scope="row">성별</th>
                    <td class="inline-wrap">
                        <?=inputRadio("gender","gender",[""=>"전체","남자"=>"남자","여자"=>"여자"],$search["gender"])?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">연령</th>
                    <td class="inline-wrap">
                        <?=inputCheck("","age_range[]",["20s"=>"20대","30s"=>"30대","40s"=>"40대","50s"=>"50대 이상"],$search["age_range"])?>
                    </td>
                    <th scope="row">근무형태</th>
                    <td class="inline-wrap">
                        <?=inputCheck("","work_type[]",$select_work,$search["work_type"])?>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <button type="button" class="btn-m btn-type10 w100 btnSearch">검색</button>
            </div>
        </div>

        <div class="search-result">
            <table class="table-type2">
                <colgroup>
                    <col width="60px"/>
                    <col width="120px"/>
                    <col width="120px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="80px"/>
                    <col width="80px"/>
                    <col width="150px"/>
                    <col width="130px"/>
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">수신 개인</th>
                        <th scope="col">발송 채용공고</th>
                        <th scope="col">국적</th>
                        <th scope="col">업종</th>
                        <th scope="col">성별</th>
                        <th scope="col">근무형태</th>
                        <th scope="col">지역</th>
                        <th scope="col">발송일</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($result_list) > 0) : ?>
                    <?php foreach($result_list as $key => $row) : ?>
                    <tr>
                        <td class="tc"><?=$start - $key?></td>
                        <td><?=isset($row['receiver_name']) ? $row['receiver_name'] : ''?></td>
                        <td><?=isset($row['recruit_name']) ? $row['recruit_name'] : ''?></td>
                        <td class="tc"><?=isset($row['recruit_nation']) ? $row['recruit_nation'] : ''?></td>
                        <td class="tc"><?=isset($row['recruit_work']) ? $row['recruit_work'] : ''?></td>
                        <td class="tc"><?=isset($row['recruit_sex']) ? $row['recruit_sex'] : ''?></td>
                        <td class="tc"><?=isset($row['recruit_work_type']) ? $row['recruit_work_type'] : ''?></td>
                        <td class="tc"><?=isset($row['recruit_addr']) ? $row['recruit_addr'] : ''?></td>
                        <td class="tc"><?=isset($row['sent_dt']) ? substr($row['sent_dt'], 0, 19) : ''?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9" class="tc" style="padding: 20px;">데이터가 없습니다.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <?=$pagenation?>
        </div>
    </div>
</form>

<script>
    $(".btnSearch").click(function(){
        $("#frm-search").submit();
    });
</script>
