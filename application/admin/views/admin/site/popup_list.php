<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/site/popup_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">팝업 관리</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w120 btnNew">팝업 등록</button>
	        </div>
	        <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="670px"/>
                    <col width="150px"/>
                    <col width="670px"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">검색어</th>
                    <td class="inline-wrap">
                         <div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",[""=>"-- 전체검색 --","pp_name"=>"팝업명"],$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                   <th scope="row">노출여부</th>
                    <td class="inline-wrap">
                         <div class="inline-wrap">
                           <?=inputRadio("pp_status","pp_status",["Y"=>"노출","N"=>"비노출"],$search["pp_status"])?>
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
                <col width="100" />
                <col width="100" />
                <col width="180" />
                <col width="*" />
                
                <col width="200" />
                <col width="120" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                 <th scope="col">노출유형</th>
                <th scope="col">노출 여부</th>
                <th scope="col">이미지</th>
                <th scope="col">팝업명</th>
                <th scope="col">기간</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
				<?php foreach($result_list as $key=>$row) : ?>
				<tr>
	                <td><?=$start - $key?></td>
	                <td><?=($row["pp_type"]=="ALL" ? "전체":($row["pp_type"]=="MEM" ? "일반":"기업"))?></td>
	                <td><?=$row["pp_status"]=="Y" ? "<span class='status-able'>노출</span>":"<span class='status-disable'>비노출</span>"?></td>
	                <td>
	                	<img src="<?=$row["pp_pc_img"]?>" style="width:100px;" />
	                </td>
	                <td class="tl"><?=$row["pp_name"]?></td>
	                <td><?=substr($row["pp_sdate"],0,10)?> ~ <?=substr($row["pp_edate"],0,10)?></td>
	                <td><?=substr($row["reg_dt"],0,10)?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["pp_idx"]?>" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete" data-idx="<?=$row["pp_idx"]?>" style="width:auto;">삭제</a>
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
	//등록 이동
	$(".btnNew").click(function(){
		location.href="/admin/site/popup_edit";
	});
	
	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/site/popup_edit?idx=" + idx;
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
	        comAjax("/admin/site/popup_edit_proc",params,function(result){
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