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
    <form id="frm-search" class="controls" method="get" action="/admin/call/call_log_list">
        <div class="search-box">
            <h3 class="sub-title mb-10">전화/지원하기 로그 관리</h3>
            <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">회사명 검색</th>
                    <td class="inline-wrap">
                        <input type="text" id="search_value" name="search_value" value="<?=$search["search_value"]?>" placeholder="회사명을 입력해주세요.">
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="btn-content inline-wrap">
                <button type="button" class="btn-m btn-type10 w100 btnSearch">검색</button>
            </div>
        </div>
        <input type="hidden" id="cl_type" name="cl_type" value="<?=$search['cl_type']?>">
    </form>

    <div class="search-result">
        <!-- cl_type 필터 탭 -->
        <div class="log-type-tabs" style="margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
            <button type="button" class="tab-btn" data-type="ALL" style="padding: 8px 16px; margin-right: 10px; border: none; background-color: #f5f5f5; cursor: pointer; border-radius: 3px;">전체</button>
            <button type="button" class="tab-btn" data-type="CALL" style="padding: 8px 16px; margin-right: 10px; border: none; background-color: #f5f5f5; cursor: pointer; border-radius: 3px;">전화하기</button>
            <button type="button" class="tab-btn" data-type="APPLY" style="padding: 8px 16px; border: none; background-color: #f5f5f5; cursor: pointer; border-radius: 3px;">지원하기</button>
        </div>

        <div class="btn-table inline-wrap">
            <div class="left">
                <span class="total-count">전체 <strong><?=$result_count?></strong>건</span>
                <select id="sortOrder">
                    <option value="default">-- 정렬 선택 --</option>
                    <option value="number_asc">번호 (오름차순)</option>
                    <option value="number_desc">번호 (내림차순)</option>
                    <option value="total_cnt_desc">전체 클릭수 (높음순)</option>
                    <option value="total_cnt_asc">전체 클릭수 (낮음순)</option>
                    <option value="call_mem_cnt_desc">전화-회원 클릭수 (높음순)</option>
                    <option value="call_nonmem_cnt_desc">전화-비회원 클릭수 (높음순)</option>
                    <option value="apply_cnt_desc">지원 클릭수 (높음순)</option>
                    <option value="last_dt_desc">최근 클릭일 (최신순)</option>
                    <option value="last_dt_asc">최근 클릭일 (오래된순)</option>
                    <option value="comp_name_asc">회사명 (가나다순)</option>
                </select>
            </div>
        </div>
        <table class="table-type2">
            <colgroup>
                <col width="60" />
                <col width="150" />
                <col width="*" />
                <col width="100" />
                <col width="100" />
                <col width="80" />
                <col width="120" />
                <col width="160" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">회사명</th>
                <th scope="col">채용공고 제목</th>
                <th scope="col">전화-회원</th>
                <th scope="col">전화-비회원</th>
                <th scope="col">지원</th>
                <th scope="col">전체 클릭수</th>
                <th scope="col">최근 클릭일시</th>
                <th scope="col">관리</th>
            </tr>
            </thead>
            <tbody>
            <?php if(isset($result_list) && count($result_list) > 0) : ?>
                <?php foreach($result_list as $key => $row) : ?>
                <tr>
                    <td><?php echo $start - $key; ?></td>
                    <td><?php echo $row["mm_comp_name"]; ?></td>
                    <td><?php echo $row["rc_name"]; ?></td>
                    <td class="tc"><?php echo $row["call_mem_cnt"]; ?></td>
                    <td class="tc"><?php echo $row["call_nonmem_cnt"]; ?></td>
                    <td class="tc"><?php echo $row["apply_cnt"]; ?></td>
                    <td class="tc"><strong><?php echo $row["total_cnt"]; ?></strong></td>
                    <td class="tc"><?php echo $row["last_dt"]; ?></td>
                    <td class="tc">
                        <a href="javascript:void(0);" class="btnDeleteLog" data-rc_idx="<?php echo $row["rc_idx"]; ?>" data-comp_name="<?php echo $row["mm_comp_name"]; ?>">삭제</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" class="tc">데이터가 없습니다.</td>
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

    // cl_type 탭 필터링
    $(".tab-btn").on("click", function(){
        var type = $(this).data("type");
        $("#cl_type").val(type);
        $("#frm-search").submit();
    });

    // 현재 활성 탭 표시
    var currentType = "<?=$search['cl_type']?>";
    if (!currentType || currentType === "") {
        currentType = "ALL";
    }
    $(".tab-btn[data-type='" + currentType + "']").css({
        "background-color": "#3498db",
        "color": "#fff"
    });

    $("#sortOrder").on("change", function(){
        var sortValue = $(this).val();
        sortData(sortValue);
    });

    $(".btnDeleteLog").on("click", function(){
        var rc_idx = $(this).data("rc_idx");
        var comp_name = $(this).data("comp_name");

        if(confirm("'" + comp_name + "' 공고의 로그를 모두 삭제하시겠습니까?")){
            var params = {
                "rc_idx": rc_idx
            };
            comAjax("/admin/call/call_log_delete_proc", params, function(result){
                if(result.code == "00"){
                    COMMON.alert("삭제되었습니다.");
                    location.reload();
                } else {
                    COMMON.alert(result.msg);
                }
            });
        }
    });
});

// 페이지 이동 함수
function goPage(page) {
    var currentType = $("#cl_type").val() || "ALL";
    var searchValue = $("#search_value").val();
    var url = "/admin/call/call_log_list?page=" + page;
    if (searchValue) {
        url += "&search_value=" + encodeURIComponent(searchValue);
    }
    if (currentType !== "ALL") {
        url += "&cl_type=" + currentType;
    }
    location.href = url;
}

// 테이블 정렬 함수
function sortData(sortType) {
    var tableBody = document.querySelector("table.table-type2 tbody");
    var rows = Array.from(tableBody.querySelectorAll("tr:not(:last-child)"));

    if(sortType === "default") {
        return; // 정렬하지 않음
    }

    rows.sort(function(rowA, rowB) {
        var cellsA = rowA.querySelectorAll("td");
        var cellsB = rowB.querySelectorAll("td");

        var valueA, valueB;

        switch(sortType) {
            case "number_asc":
                valueA = parseInt(cellsA[0].textContent) || 0;
                valueB = parseInt(cellsB[0].textContent) || 0;
                return valueA - valueB;
            case "number_desc":
                valueA = parseInt(cellsA[0].textContent) || 0;
                valueB = parseInt(cellsB[0].textContent) || 0;
                return valueB - valueA;
            case "total_cnt_desc":
                valueA = parseInt(cellsA[6].textContent) || 0;
                valueB = parseInt(cellsB[6].textContent) || 0;
                return valueB - valueA;
            case "total_cnt_asc":
                valueA = parseInt(cellsA[6].textContent) || 0;
                valueB = parseInt(cellsB[6].textContent) || 0;
                return valueA - valueB;
            case "call_mem_cnt_desc":
                valueA = parseInt(cellsA[3].textContent) || 0;
                valueB = parseInt(cellsB[3].textContent) || 0;
                return valueB - valueA;
            case "call_nonmem_cnt_desc":
                valueA = parseInt(cellsA[4].textContent) || 0;
                valueB = parseInt(cellsB[4].textContent) || 0;
                return valueB - valueA;
            case "apply_cnt_desc":
                valueA = parseInt(cellsA[5].textContent) || 0;
                valueB = parseInt(cellsB[5].textContent) || 0;
                return valueB - valueA;
            case "last_dt_desc":
                valueA = new Date(cellsA[7].textContent);
                valueB = new Date(cellsB[7].textContent);
                return valueB - valueA;
            case "last_dt_asc":
                valueA = new Date(cellsA[7].textContent);
                valueB = new Date(cellsB[7].textContent);
                return valueA - valueB;
            case "comp_name_asc":
                valueA = cellsA[1].textContent.trim();
                valueB = cellsB[1].textContent.trim();
                return valueA.localeCompare(valueB, 'ko');
            default:
                return 0;
        }
    });

    // 정렬 후 번호 업데이트 (정렬 유형에 따라 처리)
    var baseNum = parseInt(document.querySelector(".total-count strong").textContent) || 0;
    var pagesize = 20;
    var currentPage = new URLSearchParams(window.location.search).get('page') || 1;
    var startNum = baseNum - ((currentPage - 1) * pagesize);

    if(sortType === "number_asc" || sortType === "number_desc") {
        // 번호 기반 정렬일 때는 번호를 유지
        rows.forEach(function(row) {
            // 번호는 이미 정렬되어 있음
        });
    } else {
        // 다른 정렬일 때는 번호를 재정렬
        rows.forEach(function(row, index) {
            var cells = row.querySelectorAll("td");
            cells[0].textContent = startNum - index;
        });
    }

    // 정렬된 행을 테이블에 다시 삽입
    rows.forEach(function(row) {
        tableBody.insertBefore(row, tableBody.lastChild);
    });
}
</script>
