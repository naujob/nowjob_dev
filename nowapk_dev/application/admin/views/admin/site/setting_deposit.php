<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/site/setting_deposit">
        <div class="search-box">
        	<h3 class="sub-title mb-10">적립금 설정 관리</h3>
        	<table>
	            <colgroup>
	                <col width="150px"/>
	                <col width="*"/>
	            </colgroup>
	            <tbody>
	            <tr>
	                <th scope="row">검색어</th>
	                <td class="inline-wrap">
	                     <div class="sum-select-input">
	                        <?=inputSelect("search_type","search_type",[""=>"-- 선택 --","all"=>"전체검색","pp_name"=>"설명"],$search["search_type"])?>
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
                <col width="*" />
        		<col width="120" />
        		<col width="100" />
        		<col width="100" />
                <col width="100" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">발행처</th>
                <th scope="col">설명</th>
                <th scope="col">발행금액(%)</th>
                <th scope="col">사용여부</th>
                <th scope="col">등록일</th>
                <th scope="col">수정일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
				
	            <?php foreach($result_list as $key=>$row) : ?>
				<tr>
	                <td><?=$start - $key?></td>
	                <td><?=$row["pt_type_nm"]?></td>
	                <td><?=$row["pt_desc"]?></td>
	                 <td><?=$row["pt_point"]?>원</td>
	                  <td><?=$row["pt_status"]=="Y" ? "<span class='status-able'>발행중</span>":"<span class='status-disable'>중지</span>"?></td>
	                <td><?=substr($row["reg_dt"],0,10)?></td>
	                <td><?=substr($row["up_dt"],0,10)?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["pt_idx"]?>" style="width:auto;">수정</a>
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
		location.href="/admin/site/setting_deposit_edit";
	});
	
	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/site/setting_deposit_edit?idx=" + idx;
	});
	
	$(".btnSearch").click(function(){
		$("#frm-search").submit();
	});
</script>
