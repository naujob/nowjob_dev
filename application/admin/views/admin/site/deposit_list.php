<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/site/deposit_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">포인트 적립/사용 리스트</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w150 btnNew">포인트 수동발급</button>
	        </div>
	        <table>
	            <colgroup>
	                <col width="150px"/>
	                <col width="1350px"/>
	            </colgroup>
	            <tbody>
	            <tr>
	                <th scope="row">검색어</th>
	                <td class="inline-wrap" colspan="3">
	                     <div class="sum-select-input" style="padding-left:0;">
	                        <!-- <?=inputSelect("search_type","search_type",[""=>"-- 선택 --","all"=>"전체검색","mm_id"=>"회원 아이디","mm_name"=>"회원명","add_sangho"=>"상호명","pl_desc"=>"발급사유"],$search["search_type"])?> -->
                          <input type="hidden" id="search_type" name="search_type" value="">
	                        <div class="input-text">
	                            <label>
	                                 <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
	                            </label>
	                        </div>
	                    </div>
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
        <table class="table-type2">
            <colgroup>
                <col width="50" />
                <col width="150" />
        		<col width="150" />
        		<col width="*" />
        		<col width="150" />
                <col width="150" />
                <col width="150" />
                <col width="150" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">유형</th>
                <th scope="col">회원명(사업자명)</th>
                <th scope="col">발급 사유</th>
                <th scope="col">포인트</th>
                <th scope="col">지급후 포인트</th>
                <th scope="col">처리일시</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
				<?php foreach($result_list as $key=>$row) : ?>
				<tr>
	                <td><?=$start - $key?></td>
	                <td><?=$row["pt_type_nm"]?></td>
	                <td><?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(" . $row["mm_comp_name"] . ")" : ""?></td>
	                <td class="tl"><?=$row["pl_desc"]?></td>
	                <td><?=number_format($row["pl_point"])?>원</td>
	                <td><?=number_format($row["pl_last_point"])?>원</td>
	                <td>
	                	<?=$row["reg_dt"]?>
	                </td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["pl_idx"]?>" style="width:auto;">확인</a>
	                </td>
	            </tr>
	            <?php endforeach;?>
			</tbody>
        </table>
    </div>
    <div>
	    <?=$pagenation?>
	</div>
</div>
<script>
	$(".btnExcel").click(function(){
		$("#frm-search").attr("action","/admin/excelproc/deposit_list_excel");
		$("#frm-search").submit();
	});

	//등록 이동
	$(".btnNew").click(function(){
		location.href="/admin/site/deposit_edit";
	});

	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/site/deposit_edit?idx=" + idx;
	});

	$(".btnSearch").click(function(){
		$("#frm-search").attr("action","/admin/site/deposit_list");
		$("#frm-search").submit();
	});
</script>
