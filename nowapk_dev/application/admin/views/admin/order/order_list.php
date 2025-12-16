<div class="contents-search">
    <form id="frm-search" class="controls" method="get" autocomplete="off" action="/admin/order/order_list">
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
                    <td class="inline-wrap" colspan="3">
                         <div class="sum-select-input" style="padding-left:160px;">
                            <?=inputSelect("search_type","search_type",[""=>"전체","mm_name"=>"회원명","mm_id"=>"아이디","o_num"=>"주문번호","o_goods_name"=>"상품명"],$search["search_type"],"style='width:150px;'")?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                   
                </tr>
                <tr>
                    <th scope="row">결제수단</th>
                    <td class="inline-wrap">
                         <div class="input-text ">
                            <label>
                               <?=inputSelect("o_paymethod","o_paymethod",[""=>"전체","CARD"=>"카드결제","BANK"=>"실시간계좌이체","VBANK"=>"가상계좌","COUPON"=>"쿠폰결제"],$search["o_paymethod"],"style='width:150px;'")?>
                            </label>
                        </div>
                    </td>
                    <th scope="row">결제상태</th>
                    <td class="inline-wrap">
                         <div class="input-text ">
                            <label>
                                <?=inputSelect("o_status","o_status",[""=>"전체","결제대기"=>"결제대기","결제완료"=>"결제완료","주문취소"=>"주문취소"],$search["o_status"],"style='width:150px;'")?>
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
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">주문번호</th>
                <th scope="col">결제상태</th>
                <th scope="col">회원명(사업자명)</th>
                <th scope="col">회원 아이디</th>
                <th scope="col">상품명</th>
                <th scope="col">결제금액</th>
                <th scope="col">쿠폰금액</th>
                <th scope="col">충전포인트</th>
                <th scope="col">결제수단</th>
                <th scope="col">결제일시</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
					<td><?=$row["o_idx"]?></td>
	               	<td><?=$row["o_num"]?></td>
        			<td><?=getOrderStatus($row["o_status"])?></td>
        			<td><?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(" . $row["mm_comp_name"] . ")" : ""?></td>
        			<td><?=$row["mm_id"]?></td>
        			<td><?=$row["o_goods_name"]?></td>
        			<td><?=number_format($row["o_total_price"])?></td>
        			<td><?=number_format($row["o_coupon_price"])?></td>
        			<td><?=number_format($row["order_point"])?></td>
        			<td><?=getPayMethod($row["o_paymethod"])?></td>
        			<td><?=$row["o_pay_dt"]?></td>
        			<td><a href="/admin/order/order_edit?o_idx=<?=$row["o_idx"]?>" class="btn-xs btn-type3" style="width:auto;">상세 정보</a></td>
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
		$("#page_size").change(function(){
			$("#frm-search").attr("action","/admin/order/order_list");
			$("#frm-search").submit();
		});
		
		
		$(".btnEdit").click(function(){
			var o_num = $(this).data("o_num");
			location.href="/admin/order/order_edit?o_num=" + o_num;
		});
		
		$(".btnSearch").click(function(){
			$("#frm-search").attr("action","/admin/order/order_list");
			$("#frm-search").submit();
		});
		
	});
	
</script>