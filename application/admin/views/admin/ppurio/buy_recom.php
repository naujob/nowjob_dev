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
                    <col width="150px"/>
                    <col width="100px"/>
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">이력서명</th>
                        <th scope="col">발송일</th>
                        <th scope="col">발송대상 기업수</th>
                        <th scope="col">프로모션IDX</th>
                        <th scope="col">로그등록일시</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(count($result_list) > 0) : ?>
                    <?php foreach($result_list as $key => $row) : ?>
                    <tr>
                        <td class="tc"><?=$start - $key?></td>
                        <td><?=isset($row['ru_name']) ? $row['ru_name'] : ''?></td>
                        <td class="tc"><?=isset($row['send_date']) ? $row['send_date'] : ''?></td>
                        <td class="tc"><?=isset($row['comp_count']) ? $row['comp_count'] : 0?></td>
                        <td class="tc"><?=isset($row['pm_idx']) ? $row['pm_idx'] : ''?></td>
                        <td class="tc"><?=isset($row['reg_dt']) ? substr($row['reg_dt'], 0, 19) : ''?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="tc" style="padding: 20px;">데이터가 없습니다.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <!-- 기업별 알림동의 상태 테이블 -->
            <div style="margin-top: 40px;">
                <h3 class="sub-title mb-10">기업별 알림동의 상태</h3>
                <table class="table-type2">
                    <colgroup>
                        <col width="60px"/>
                        <col width="150px"/>
                        <col width="100px"/>
                        <col width="250px"/>
                        <col width="100px"/>
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">번호</th>
                            <th scope="col">이력서명</th>
                            <th scope="col">발송일</th>
                            <th scope="col">기업명</th>
                            <th scope="col">알림동의</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(count($result_list) > 0) : ?>
                        <?php $detail_idx = 1; ?>
                        <?php foreach($result_list as $key => $row) : ?>
                        <?php
                            // 기업명 JSON 디코딩
                            $comp_names_arr = [];
                            if (isset($row['comp_names']) && !empty($row['comp_names'])) {
                                $comp_names_arr = json_decode($row['comp_names'], true);
                                if (!is_array($comp_names_arr)) {
                                    $comp_names_arr = [];
                                }
                            }
                            $alarm_adv_count = isset($row['alarm_adv_count']) ? $row['alarm_adv_count'] : 0;
                            $total_comp_count = isset($row['comp_count']) ? $row['comp_count'] : 0;
                        ?>
                            <?php foreach($comp_names_arr as $comp_idx => $comp_name) : ?>
                            <tr>
                                <td class="tc"><?=$detail_idx?></td>
                                <td><?=isset($row['ru_name']) ? $row['ru_name'] : ''?></td>
                                <td class="tc"><?=isset($row['send_date']) ? $row['send_date'] : ''?></td>
                                <td><?=$comp_name?></td>
                                <td class="tc">
                                    <?php if($comp_idx < $alarm_adv_count) : ?>
                                        <span style="color: #4CAF50; font-weight: bold;">✓ 동의</span>
                                    <?php else : ?>
                                        <span style="color: #999;">✗ 미동의</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $detail_idx++; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="tc" style="padding: 20px;">데이터가 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
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
