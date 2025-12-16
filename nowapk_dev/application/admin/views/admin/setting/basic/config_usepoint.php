<form id="frm" class="controls" method="post">
    <div class="article">
        <div class="tab-content-wrap">
            <div class="tab-article on">
                <table class="table-type2">
		            <colgroup>
		                <col width="100" />
		                <col width="100" />
		                <col width="150" />
		                <col width="150" />
		                <col width="150" />
		                <col width="100" />
		            </colgroup>
		            <thead>
		            <tr>
		            	<th scope="col">그룹</th>
		                <th scope="col">코드명</th>
		                <th scope="col">내용</th>
		                <th scope="col">포인트</th>
		                <th scope="col">수정일시</th>
		                <th scope="col">관리</th>
					</tr>
		            </thead>
		            <tbody>
		            	<?php foreach($result_list as $key=> $row) : ?>
						<tr>
			                <td><?=$row["cd_grp"]?></td>
			                <td><?=$row["cd_key"]?></td>
			                <td><?=($row["cd_val"])?></td>
			                <td><?=number_format($row["cd_val3"])?></td>
			                <td><?=substr($row["reg_dt"],0, 10)?></td>
			                <td>
			                	<a href="javascript:void(0);" data-idx="<?=$row["cd_idx"]?>" class="btn-xs btn-type3 btnEdit" style="width:auto;">수정</a>
			                </td>
			            </tr>
			            <?php endforeach;?>
					</tbody>
		        </table>
            </div>
        </div>
    </div>
</form>

<script>

	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/setting/basic/config_usepoint_edit?idx=" + idx;
	});
	
</script>
