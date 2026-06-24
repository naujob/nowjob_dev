<form id="frm-search" class="controls" method="get" action="/admin/ppurio/buy_recom">
    <div class="contents-search">
        <div class="search-box">
            <h3 class="sub-title mb-10">추천인재 구매상품 개인</h3>
            <table class="table-form">
                <colgroup>
                    <col width="200px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="col">발송일</th>
                    <td>
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
                    <th scope="col">검색어</th>
                    <td>
                        <div class="input-text w200">
                            <label>
                                <?=inputText("search_value","search_value",$search["search_value"],"이력서명","",50)?>
                            </label>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <button type="button" class="btn-m btn-type10 w100 btnSearch">검색</button>
            </div>
        </div>

        <div class="search-result">
            <div class="btn-table inline-wrap">
                <div class="right">
                    <button type="button" class="btn-xs btn-excel btnExcel">
                        <img src="/resource/admin/img/icons/icon8/icon-excel.png" />
                        <span>엑셀 다운로드</span>
                    </button>
                </div>
            </div>

            <table class="table-type2">
                <colgroup>
                    <col width="60px"/>
                    <col width="150px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="250px"/>
                    <col width="150px"/>
                    <col width="100px"/>
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">이력서명</th>
                        <th scope="col">발송일</th>
                        <th scope="col">발송대상 기업수</th>
                        <th scope="col">발송대상 기업명</th>
                        <th scope="col">로그등록일시</th>
                        <th scope="col">프로모션IDX</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($result_list) > 0) : ?>
                    <?php foreach($result_list as $key => $row) : ?>
                    <?php
                        // 기업명 JSON 디코딩
                        $comp_names_str = '';
                        if (isset($row['comp_names']) && !empty($row['comp_names'])) {
                            $comp_names_arr = json_decode($row['comp_names'], true);
                            if (is_array($comp_names_arr)) {
                                $comp_names_str = implode(', ', $comp_names_arr);
                            }
                        }
                    ?>
                    <tr>
                        <td class="tc"><?=$start - $key?></td>
                        <td><?=isset($row['ru_name']) ? $row['ru_name'] : ''?></td>
                        <td class="tc"><?=isset($row['send_date']) ? $row['send_date'] : ''?></td>
                        <td class="tc"><?=isset($row['comp_count']) ? $row['comp_count'] : 0?></td>
                        <td style="font-size:12px; line-height:1.4;"><?=$comp_names_str?></td>
                        <td class="tc"><?=isset($row['reg_dt']) ? substr($row['reg_dt'], 0, 19) : ''?></td>
                        <td class="tc"><?=isset($row['pm_idx']) ? $row['pm_idx'] : ''?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="tc" style="padding: 20px;">데이터가 없습니다.</td>
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

    $(".btnExcel").click(function(){
        var params = {};
        params["sdate"] = $("#sdate").val();
        params["edate"] = $("#edate").val();
        params["search_value"] = $("#search_value").val();

        var query_string = "";
        for(var key in params){
            if(params[key] != ""){
                query_string += (query_string == "" ? "?" : "&") + key + "=" + encodeURIComponent(params[key]);
            }
        }

        location.href = "/admin/ppurio/buy_recom_excel" + query_string;
    });
</script>
