<div class="contents-search">
	<input type="hidden" name="bg_idx" value="<?=$info["bg_idx"]?>" />
    <form id="frm-search" class="controls" method="get" action="/admin/site/banner_list?bg_idx=<?=$info["bg_idx"]?>">
        <div class="search-box">
        	<h3 class="sub-title mb-10"><?=$info["bg_name"]?> (<?=$info["max_len"]?>개까지 등록가능)</h3>
        	<?php if(count($result_list) < $info["max_len"]) : ?>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w150 btnNew">배너 등록</button>
	        </div>
	        <?php endif; ?>
        </div>
    </form>
    <div class="search-result">
        <table class="table-type2">
            <colgroup>
                <col width="50" />
                <col width="100" />
                <col width="100" />
                <col width="150" />
                <col width="*" />
               
                <col width="120" />
                <col width="200" />
                <col width="120" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">정렬</th>
                <th scope="col">노출 여부</th>
                <th scope="col">이미지</th>
                <th scope="col">배너명</th>
               
                <th scope="col">진행여부</th>
                <th scope="col">기간</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=>$row) : ?>
				<tr>
	                <td><?=$key + 1?></td>
	                <td><?=$row["bn_sort"]?></td>
	                <td><?=$row["bn_status"]=="Y" ? "<span class='status-able'>노출</span>":"<span class='status-disable'>비노출</span>"?></td>
	                <td>
	                	<img src="<?=$row["bn_pc_img"]?>" style="width:100px;" />
	                </td>
	                <td class="tl"><?=$row["bn_name"]?></td>
	                <td><?=$row["bn_ing"]?></td>
	                <td>
	                	<?=substr($row["bn_sdate"],0,10)?> ~ <?=substr($row["bn_edate"],0,10)?>
					</td>
	                <td><?=substr($row["reg_dt"],0,10)?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["bn_idx"]?>" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete" data-idx="<?=$row["bn_idx"]?>" style="width:auto;">삭제</a>
	                </td>
	            </tr>
	            <?php endforeach;?>
	           
			</tbody>
        </table>
    </div>
</div>

<script>
	//등록 이동
	$(".btnNew").click(function(){
		location.href="/admin/site/banner_edit?bg_idx=" + $("[name=bg_idx]").val();
	});
	
	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/site/banner_edit?bg_idx=" + $("[name=bg_idx]").val() + "&idx=" + idx;
	});
	
	$(".btnSearch").click(function(){
		$("#frm-search").submit();
	});
	
	$(".btnDelete").click(function(){
		var idx = $(this).data("idx");
		COMMON.confirm("삭제 하시겠습니까?",function(){
			var params = {};
			params["idx"] = idx;
			params["proc_mode"] = "delete";
	        comAjax("/admin/site/banner_edit_proc",params,function(result){
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