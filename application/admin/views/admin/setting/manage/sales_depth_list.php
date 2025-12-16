<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/setting/goods/dlv_add_setting">
        <div class="search-box">
        	<h3 class="sub-title mb-10">영업부서 관리</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w150 btnNew">영업부서 추가</button>
	        </div>
            
        </div>
    </form>
    <div class="search-result">
        <table class="table-type2">
            <colgroup>
                <col width="100" />
                <col width="400" />
                <col width="400" />
                <col width="200" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">부서명</th>
                <th scope="col">담당자명</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key => $row) : ?>
				<tr>
	                <td><?=$start - $key?></td>
	                <td><?=$row["cd_val"]?></td>
	                <td><?=$row["cd_val2"]?></td>
	                <td><?=$row["reg_dt"]?></td>
	                <td>
	                	<a href="javascript:void(0);" data-idx="<?=$row["cd_idx"]?>" class="btn-xs btn-type3 btnEdit" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" data-idx="<?=$row["cd_idx"]?>" class="btn-xs btn-type4 btnDelete" style="width:auto;">삭제</a>
	                </td>
	            </tr>
	            <?php endforeach; ?>
	            
			</tbody>
        </table>
    </div>
    <div>
	    <?=$pagenation?>
	</div>
</div>
<script>
	$(document).ready(function(){
		//등록 화면이동
		$(".btnNew").click(function(){
			location.href="/admin/setting/manage/sales_depth_edit";
		});
		
		//수정처리
		$(".btnEdit").click(function(){
			var idx = $(this).data("idx");
			location.href="/admin/setting/manage/sales_depth_edit?idx=" + idx;
		});
		
		$(".btnDelete").click(function(){
			var idx = $(this).data("idx");
			COMMON.confirm("삭제 하시겠습니까?"
			,function(){
				var params = {};
				params["proc_mode"] = "delete";
				params["cd_idx"] = idx;
				comAjax("/admin/setting/manage/sales_depth_proc",params,function(result){
					if(result.code == "00"){
						COMMON.alert(result.msg,function(){
							location.href="/admin/setting/manage/sales_depth_list";	
						});
					}else{
						COMMON.alert(result.msg);
					}
				});
			}
			,function(){
				
			}
			);
		});
		
		$(".btnSearch").click(function(){
			$("#frm-search").submit();
		});
	});
</script>