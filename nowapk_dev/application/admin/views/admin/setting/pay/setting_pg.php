<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="<?=currentUrl() ?>">
        <div class="search-box">
        	<h3 class="sub-title mb-10">PG 연동 관리</h3>
        </div>
    </form>
    <div class="search-result">
        <table class="table-type2">
            <colgroup>
                <col width="80" />
                <col width="*" />
                <col width="150" />
                <col width="150" />
                <col width="150" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">PG사명</th>
                <th scope="col">기본설정여부</th>
                <th scope="col">테스트여부</th>
                <th scope="col">설정일시</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($list as $row) : ?>
				 <tr>
	                <td>1</td>
	                <td class="tl">
	                	<?=$row["sp_name"]?>
	                </th>
	                <td>
	                	<?php  if($row["sp_default_yn"] == "Y") : ?>
	                		<span class="status-danger">기본 사용중</span>
	                	<?php else : ?>
	                		<span class="status-disable">미선택</span>
	                	<?php endif; ?>
	                </td>
	                <td>
	                	<?=$row["sp_name"]?>
	                </td>
	                <td>
	                	<?=$row["up_dt"]?>
	                </td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["sp_idx"]?>" style="width:auto;">수정</a>
	                </td>
	            </tr>
	            <?php endforeach; ?>
			</tbody>
        </table>
    </div>
</div>
<script>
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/setting/pay/setting_pg_detail?idx=" + idx;
	});
</script>