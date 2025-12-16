<form id="frm" class="controls" method="post">
    <div class="article">
        <div class="tab-content-wrap">
            <div class="tab-article on">
            	<div class="table-btn inline-wrap">
		            <button type="button" class="btn-m btn-type6 w120 btnNew">신규 추가</button>
		        </div>
                <table class="table-type2">
		            <colgroup>
		                <col width="100" />
		                <col width="100" />
		                <col width="100" />
		                <col width="150" />
		                <col width="150" />
                    <col width="150" />
                    <col width="150" />
		                <col width="100" />
		                <col width="100" />
		                <col width="100" />
		            </colgroup>
		            <thead>
		            <tr>
		                <th scope="col">정렬순서</th>
		                <th scope="col">분류명</th>
		                <th scope="col">아이콘</th>
		                <th scope="col">업체 이미지1</th>
		                <th scope="col">업체 이미지2</th>
                    <th scope="col">업체 이미지3</th>
                    <th scope="col">업체 이미지4</th>
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
			                <td><img style="height:24px;" src="<?=($row["cd_val2"])?>" /></td>
			                <td><img style="width:100px;" src="<?=($row["cd_val3"])?>" /></td>
			                <td><img style="width:100px;" src="<?=($row["cd_val4"])?>" /></td>
                      <td><img style="width:100px;" src="<?=($row["cd_val5"])?>" /></td>
                      <td><img style="width:100px;" src="<?=($row["cd_val6"])?>" /></td>
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
	//등록 이동
	$(".btnNew").click(function(){
		location.href="/admin/setting/basic/config_edit";
	});

	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/setting/basic/config_edit?idx=" + idx;
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
