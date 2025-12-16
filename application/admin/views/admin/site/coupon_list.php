<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/site/coupon_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">쿠폰 발급 리스트</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w120 btnNew">쿠폰 수동 발급</button>
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
	            	<th scope="row">쿠폰명</th>
	                <td class="inline-wrap">
	                     <select id="cp_idx" name="cp_idx" style="width:200px;">
					   		<option value="">-- 쿠폰선택 --</option>
					   		<?php foreach($coupon_list as $cp) : ?>
						   		<option <?=$cp["cp_idx"]==$search["cp_idx"] ? "selected":""?> value="<?=$cp["cp_idx"]?>">
						   			<?=$cp["cp_name"]?>
						   		</option>
					   		<?php endforeach;?>
					   	</select>
	                </td>
	                <th scope="row">검색어</th>
	                <td class="inline-wrap">
	                     <div class="sum-select-input">
	                        <?=inputSelect("search_type","search_type",[""=>"-- 선택 --","all"=>"전체검색","cp_name"=>"쿠폰명","mm_id"=>"회원 아이디","mm_name"=>"회원명"],$search["search_type"])?>
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
        <table class="table-type2 mt-10">
    		<colgroup>
    			<col width="50px" />
    			<col width="150px" />
    			<col width="120px" />
    			<col width="*" />
    			<col width="100px" />
    			<col width="100px" />
    			<col width="100px" />
    			<col width="120px" />
    			<col width="100px" />
    			<col width="100px" />
    		</colgroup>
    		<tr>
    			<th>번호</th>
    			<th>회원명(사업자명)</th>
    			<th>쿠폰코드</th>
    			<th>쿠폰명</th>
    			<th>할인금액</th>
    			<th>최소사용금액</th>
    			<th>사용기간유형</th>
    			<th>사용가능기간</th>
    			<th>상태</th>
    			<th>관리</th>
    		</tr>
    		<?php foreach($result_list as $key=>$row) : ?>
    		<tr>
    			<td><?=$start - $key?></td>
    			<td>
    				<?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(".$row["mm_comp_name"].")":""?>
    			</td>
    			<td><?=$row["cl_idx"]?></td>
    			<td class="tl">
    				<div class="txt_eli">
						<?=$row["cp_name"]?>
					</div>
				</td>
    			<td><?=number_format($row["cl_price"])?>원</td>
    			<td><?=number_format($row["cl_limit_price"])?>원</td>
    			<td><?=$row["cp_use_type_nm"]?></td>
    			<td><?=substr($row["cl_use_sdate"],0,10)?><br/><?=substr($row["cl_use_edate"],0,10)?></td>
    			<td><span class="status-able">사용가능</span></td>
				<td>
    				<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["cl_idx"]?>" style="width:auto;">수정</a>
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
		location.href="/admin/site/coupon_edit";
	});
	
	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/site/coupon_edit?idx=" + idx;
	});
	
	$(".btnSearch").click(function(){
		if($("#search_value").val()!="" && $("#search_type").val()==""){
			COMMON.alert("검색 유형을 선택해주세요.",function(){
				$("#search_type").focus();
			});
			return;
		}
		$("#frm-search").submit();
	});
	
</script>