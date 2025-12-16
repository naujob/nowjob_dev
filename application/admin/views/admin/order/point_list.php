<div class="contents-search">
    <form id="frm-search" class="controls" method="get" autocomplete="off" action="/admin/order/point_list">
       	<input type="hidden" name="chk_list" value="" />
        <div class="search-box">
        	<h3 class="sub-title mb-10">주문 리스트</h3>
            <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="600px"/>
                    <col width="150px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
               <tr>
                    
                    <th scope="row">검색어</th>
                    <td class="inline-wrap">
                         <div class="sum-select-input" style="padding-left:160px;">
                            <?=inputSelect("search_type","search_type",[""=>"전체","mm_name"=>"회원명","mm_id"=>"아이디","o_num"=>"주문번호","pl_desc"=>"내용"],$search["search_type"],"style='width:150px;'")?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <th scope="row">가감 유형</th>
                    <td class="inline-wrap">
                         <div class="input-text">
                            <label>
                                <?=inputSelect("mm_type","mm_type",[""=>"전체","plus"=>"적립","minus"=>"사용"],$search["mm_type"],"style='width:150px;'")?>
                            </label>
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
                <col width="150" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="250" />
                <col width="120" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">유형</th>
                <th scope="col">회원명(사업자명)</th>
                <th scope="col">회원 아이디</th>
                <th scope="col">가감 유형</th>
	            <th scope="col">가감 포인트</th>
	            <th scope="col">최종포인트</th>
	            <th scope="col">주문번호</th>
                <th scope="col">내용</th>
	            <th scope="col">등록일시</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
					<td><?=$row["pl_idx"]?></td>
					<td><?=$row["pt_type_nm"]?></td>
	               	<td><?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(" . $row["mm_comp_name"] . ")" : ""?></td>
        			<td><?=$row["mm_id"]?></td>
        			<td><?=getPointStatus($row["pl_type"])?></td>
            		<td><?=number_format($row["pl_point"])?>P</td>
            		<td><?=number_format($row["pl_last_point"])?>P</td>
        			<td><?=($row["o_num"])?></td>
        			<td><?=($row["pl_desc"])?></td>
        			<td><?=$row["reg_dt"]?></td>
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
	$(document).ready(function(){
		$(".btnSearch").click(function(){
			$("#frm-search").attr("action","/admin/order/point_list");
			$("#frm-search").submit();
		});
	});
</script>