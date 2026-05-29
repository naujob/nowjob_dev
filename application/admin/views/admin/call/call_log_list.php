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
            <h3 class="sub-title mb-10">전화걸기 로그 관리</h3>
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
    </form>

    <div class="search-result">
        <div class="btn-table inline-wrap">
            <div class="left">
                <span class="total-count">전체 <strong><?=$result_count?></strong>건</span>
                <select id="sortOrder">
                    <option value="default">-- 정렬 선택 --</option>
                    <option value="number_asc">번호 (오름차순)</option>
                    <option value="number_desc">번호 (내림차순)</option>
                    <option value="total_cnt_desc">전체 클릭수 (높음순)</option>
                    <option value="total_cnt_asc">전체 클릭수 (낮음순)</option>
                    <option value="mem_cnt_desc">회원 클릭수 (높음순)</option>
                    <option value="nonmem_cnt_desc">비회원 클릭수 (높음순)</option>
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
                <col width="120" />
                <col width="120" />
                <col width="120" />
                <col width="160" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">회사명</th>
                <th scope="col">채용공고 제목</th>
                <th scope="col">회원 클릭수</th>
                <th scope="col">비회원 클릭수</th>
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
                    <td class="tc"><strong><?php echo $row["mem_cnt"]; ?></strong></td>
                    <td class="tc"><?php echo $row["nonmem_cnt"]; ?></td>
                    <td class="tc"><strong><?php echo $row["total_cnt"]; ?></strong></td>
                    <td class="tc"><?php echo $row["last_dt"]; ?></td>
                    <td class="tc">
                        <a href="javascript:void(0);" class="btnDeleteLog" data-rc_idx="<?php echo $row["rc_idx"]; ?>" data-comp_name="<?php echo $row["mm_comp_name"]; ?>">삭제</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="tc">데이터가 없습니다.</td>
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

    $("#sortOrder").on("change", function(){
        var sortValue = $(this).val();
        sortData(sortValue);
    });

    $(".btnDeleteLog").on("click", function(){
        var rc_idx = $(this).data("rc_idx");
        var comp_name = $(this).data("comp_name");

        if(confirm("'" + comp_name + "' 공고의 전화걸기 로그를 모두 삭제하시겠습니까?")){
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
                valueA = parseInt(cellsA[5].textContent) || 0;
                valueB = parseInt(cellsB[5].textContent) || 0;
                return valueB - valueA;
            case "total_cnt_asc":
                valueA = parseInt(cellsA[5].textContent) || 0;
                valueB = parseInt(cellsB[5].textContent) || 0;
                return valueA - valueB;
            case "mem_cnt_desc":
                valueA = parseInt(cellsA[3].textContent) || 0;
                valueB = parseInt(cellsB[3].textContent) || 0;
                return valueB - valueA;
            case "nonmem_cnt_desc":
                valueA = parseInt(cellsA[4].textContent) || 0;
                valueB = parseInt(cellsB[4].textContent) || 0;
                return valueB - valueA;
            case "last_dt_desc":
                valueA = new Date(cellsA[6].textContent);
                valueB = new Date(cellsB[6].textContent);
                return valueB - valueA;
            case "last_dt_asc":
                valueA = new Date(cellsA[6].textContent);
                valueB = new Date(cellsB[6].textContent);
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
