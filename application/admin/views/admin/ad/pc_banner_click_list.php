<style>
.total-count {
    display: inline-block;
    font-size: 14px;
    color: #333;
    font-weight: 500;
    margin-right: 15px;
}

.total-count strong {
    color: #e74c3c;
    font-weight: bold;
    font-size: 16px;
}

#sortOrder {
    border: 1px solid #ddd;
    border-radius: 3px;
    font-size: 13px;
    background-color: #fff;
    cursor: pointer;
    min-width: 180px;
}

#sortOrder:hover {
    border-color: #999;
    background-color: #f9f9f9;
}
</style>

<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/ad/pc_banner_click_list">
        <div class="search-box">
            <h3 class="sub-title mb-10">PC 배너광고 클릭수 관리</h3>
            <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">날짜 검색</th>
                    <td class="inline-wrap">
                        <input type="text" id="sdate" name="sdate" value="<?=$search["sdate"]?>" placeholder="시작일자 (yyyy-mm-dd)" class="date-input">
                        ~
                        <input type="text" id="edate" name="edate" value="<?=$search["edate"]?>" placeholder="종료일자 (yyyy-mm-dd)" class="date-input">
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <button type="button" class="btn-m btn-type10 w100 btnSearch">검색</button>
            </div>
        </div>
        <input type="hidden" id="page_type" name="page_type" value="<?=$search['page_type']?>">
        <input type="hidden" id="mm_type" name="mm_type" value="<?=$search['mm_type']?>">
    </form>

    <div class="search-result">
        <!-- page_type 필터 탭 -->
        <div class="log-type-tabs" style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
            <button type="button" class="tab-btn" data-type="ALL" style="padding: 8px 16px; margin-right: 10px; border: none; background-color: #f5f5f5; cursor: pointer; border-radius: 3px;">전체</button>
            <button type="button" class="tab-btn" data-type="recruit" style="padding: 8px 16px; margin-right: 10px; border: none; background-color: #f5f5f5; cursor: pointer; border-radius: 3px;">구인 페이지</button>
            <button type="button" class="tab-btn" data-type="hr" style="padding: 8px 16px; border: none; background-color: #f5f5f5; cursor: pointer; border-radius: 3px;">인재 페이지</button>
        </div>

        <!-- mm_type 필터 탭 -->
        <div class="log-type-tabs" style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
            <span style="margin-right: 10px; font-weight: bold;">회원유형:</span>
            <button type="button" class="mm-tab-btn" data-type="ALL" style="padding: 8px 16px; margin-right: 10px; border: none; background-color: #f5f5f5; cursor: pointer; border-radius: 3px;">전체</button>
            <button type="button" class="mm-tab-btn" data-type="MEM" style="padding: 8px 16px; margin-right: 10px; border: none; background-color: #f5f5f5; cursor: pointer; border-radius: 3px;">일반회원</button>
            <button type="button" class="mm-tab-btn" data-type="COMP" style="padding: 8px 16px; margin-right: 10px; border: none; background-color: #f5f5f5; cursor: pointer; border-radius: 3px;">기업회원</button>
            <button type="button" class="mm-tab-btn" data-type="비회원" style="padding: 8px 16px; border: none; background-color: #f5f5f5; cursor: pointer; border-radius: 3px;">비회원</button>
        </div>

        <div class="btn-table inline-wrap">
            <div class="left">
                <span class="total-count">전체 <strong><?=$result_count?></strong>건</span>
            </div>
        </div>

        <table class="table-type2">
            <colgroup>
                <col width="60" />
                <col width="120" />
                <col width="100" />
                <col width="100" />
                <col width="80" />
                <col width="150" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">날짜</th>
                <th scope="col">페이지 유형</th>
                <th scope="col">회원 유형</th>
                <th scope="col">클릭수</th>
                <th scope="col">최근 클릭일시</th>
            </tr>
            </thead>
            <tbody>
            <?php if(isset($result_list) && count($result_list) > 0) : ?>
                <?php foreach($result_list as $key => $row) : ?>
                <tr>
                    <td class="tc"><?php echo $start - $key; ?></td>
                    <td class="tc"><?php echo $row["click_date"]; ?></td>
                    <td class="tc"><?php echo $row["page_type"] == "recruit" ? "구인" : "인재"; ?></td>
                    <td class="tc">
                        <?php
                            if ($row["mm_type"] == "MEM") {
                                echo "일반회원";
                            } elseif ($row["mm_type"] == "COMP") {
                                echo "기업회원";
                            } else {
                                echo $row["mm_type"];
                            }
                        ?>
                    </td>
                    <td class="tc"><strong><?php echo $row["cnt"]; ?></strong></td>
                    <td class="tc"><?php echo substr($row["last_dt"], 0, 16); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="tc">데이터가 없습니다.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php if(isset($pagenation)) echo $pagenation; ?>
    </div>
</div>

<script>
$(document).ready(function(){
    $(".btnSearch").on("click", function(){
        $("#frm-search").submit();
    });

    // page_type 탭 필터링
    $(".tab-btn").on("click", function(){
        var type = $(this).data("type");
        $("#page_type").val(type);
        $("#frm-search").submit();
    });

    // mm_type 탭 필터링
    $(".mm-tab-btn").on("click", function(){
        var type = $(this).data("type");
        $("#mm_type").val(type);
        $("#frm-search").submit();
    });

    // 현재 활성 탭 표시
    var currentPageType = "<?=$search['page_type']?>";
    if (!currentPageType || currentPageType === "") {
        currentPageType = "ALL";
    }
    $(".tab-btn[data-type='" + currentPageType + "']").css({
        "background-color": "#3498db",
        "color": "#fff"
    });

    var currentMmType = "<?=$search['mm_type']?>";
    if (!currentMmType || currentMmType === "") {
        currentMmType = "ALL";
    }
    $(".mm-tab-btn[data-type='" + currentMmType + "']").css({
        "background-color": "#3498db",
        "color": "#fff"
    });
});

// 페이지 이동 함수
function goPage(page) {
    var pageType = $("#page_type").val() || "ALL";
    var mmType = $("#mm_type").val() || "ALL";
    var sdate = $("#sdate").val();
    var edate = $("#edate").val();

    var url = "/admin/ad/pc_banner_click_list?page=" + page;
    if (pageType !== "ALL") {
        url += "&page_type=" + pageType;
    }
    if (mmType !== "ALL") {
        url += "&mm_type=" + encodeURIComponent(mmType);
    }
    if (sdate) {
        url += "&sdate=" + sdate;
    }
    if (edate) {
        url += "&edate=" + edate;
    }
    location.href = url;
}
</script>
