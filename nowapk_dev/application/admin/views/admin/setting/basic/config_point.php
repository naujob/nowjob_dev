<form id="frm" class="controls" method="post">
    <div class="article">
        <div class="tab-content-wrap">
            <div class="tab-article on">
                <table class="table-type2">
		            <colgroup>
		                <col width="50" />
		                <col width="100" />
		                <col width="100" />
		                <col width="150" />
		                <col width="150" />
		                <col width="150" />
		                <col width="100" />
		            </colgroup>
		            <thead>
		            <tr>
		                <th scope="col">정렬순서</th>
		                <th scope="col">코드명</th>
		                <th scope="col">결제금액</th>
		                <th scope="col">지급포인트</th>
		                <th scope="col">사용여부</th>
		                <th scope="col">등록/수정일시</th>
		                <th scope="col">관리</th>
					</tr>
		            </thead>
		            <tbody>
		            	<?php foreach($result_list as $key=> $row) : ?>
						<tr>
			                <td><?=$row["cd_sort"]?></td>
			                <td><?=$row["cd_key"]?></td>
			                <td><?=number_format($row["cd_val"])?></td>
			                <td><?=number_format($row["cd_val2"])?></td>
			                <td><?=($row["use_yn"]=="Y" ? "사용":"미사용")?></td>
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
		location.href="/admin/setting/basic/config_point_edit?idx=" + idx;
	});
	
	$(".btnDelete").click(function(){
		var idx = $(this).data("idx");
		COMMON.confirm("삭제 하시겠습니까?",function(){
			var params = {};
			params["idx"] = idx;
			params["proc_mode"] = "delete";
	        comAjax("/admin/setting/basic/config_proc",params,function(result){
	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    location.reload();
	                });
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
		},function(){
			
		});
	});
</script>
