 <div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/trade/trade_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">중고거래 리스트</h3>
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
                         <div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",[""=>"전체","td_name"=>"상품명","td_hash"=>"해시태그","mm_id"=>"아이디","mm_phone"=>"연락처","ad_addr"=>"지역"],$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <th scope="row">판매금액</th>
                    <td class="inline-wrap">
                         <div class="input-text w130">
                            <label>
                                <?=inputNumber("sprice","sprice",$search["sprice"],"금액(원)","",200)?>
                            </label>
                        </div>
                        <span>~</span>
                        <div class="input-text w130">
                            <label>
                                <?=inputNumber("eprice","eprice",$search["eprice"],"금액(원)","",200)?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">노출여부</th>
                    <td class="inline-wrap" colspan="3">
                       	<?=inputRadio("show_yn","show_yn",[""=>"전체","Y"=>"정상","H"=>"관리자 비공개"],$search["show_yn"])?>
					</td>
                 </tr>
                <tr>
                    <th scope="row">카테고리</th>
                    <td class="inline-wrap" colspan="3">
                       	<?=inputCheck("","category[]",$select_category,$search["category"])?>
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
                <col width="50" />
                <col width="120" />
                <col width="80" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="150" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="50" />
                <col width="100" />
                <col width="100" />
            </colgroup>
            <thead>
            <tr>
            	<th scope="col">
                	<div class='input-checkbox'>
			            <label>
			                <input type='checkbox' id='chk_all_Y' name='chk_all' value='Y'   />
			                <span style='padding-left:5px;'><strong></strong></span>
			            </label>
			        </div>
                </th>
                <th scope="col">번호</th>
                <th scope="col">게시글상태</th>
                <th scope="col">이미지</th>
                <th scope="col">아이디</th>
                <th scope="col">회원명(사업자명)</th>
                <th scope="col">연락처</th>
				<th scope="col">상품명</th>
				<th scope="col">판매금액</th>
                <th scope="col">카테고리</th>
                <th scope="col">해시태그</th>
                <th scope="col">HIT</th>
                <th scope="col">등록일시</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>

					<td class="tc">
	                	<div class='input-checkbox'>
				            <label>
				                <input type='checkbox' name='chk[]' value='<?=$row["td_idx"]?>'   />
				                <span style='padding-left:5px;'><strong></strong></span>
				            </label>
				        </div>
	                </td>
	                <td><?=$start - $key?></td>
                    <td>
	                	<?=getRecruitUseYn($row["td_status"])?>
	                </td>
	                <td><img style="height:80px;" src="<?=getTradeImg($row)?>" /></td>
                    <td><?=$row["mm_id"]?></td>
	                <td><?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(".$row["mm_comp_name"].")":""?></td>
	                <td><?=$row["mm_phone"]?></td>
	                <td><?=$row["td_name"]?></td>
	                <td><?=number_format($row["td_price"])?>원</td>
	                <td><?=$row["td_category"]?></td>
                    <td><?=$row["td_hash"]?></td>
	                <td><?=number_format($row["td_hit"])?></td>
	                <td><?=$row["reg_dt"]?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["td_idx"]?>" style="width:auto;">상세정보</a>
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
   $(document).ready(function(){

       $(".btnEdit").click(function(){
           var idx = $(this).data("idx");
           location.href="/admin/trade/trade_edit?idx=" + idx;
       });

       $(".btnSearch").click(function(){
           $("#frm-search").attr("action","/admin/trade/trade_list");
           $("#frm-search").submit();
       });

       $(".btnAllHidden").click(function(){
			var chk_list = "";

			for(var icnt=0;icnt < $("[name='chk[]']:checked").length;icnt++){
				if(chk_list==""){
					chk_list = $("[name='chk[]']:checked").eq(icnt).val();
				}else{
					chk_list += "," + $("[name='chk[]']:checked").eq(icnt).val();
				}
			}

			if(chk_list==""){
				COMMON.alert("처리하실 중고거래를 선택해 주세요.");
				return;
			}


			COMMON.confirm("비노출 처리 하시겠습니까?",function(){
				var params = {};
				params["proc_mode"] = "hidden";
				params["chk_list"] = chk_list;
				params["mg_idx"] = $("#all_mg_idx").val();
		        comAjax("/admin/trade/trade_list_proc",params,function(result){
		            if(result.code == "00"){
		                location.reload();
		            }else{
		                COMMON.alert(result.msg);
		            }
		        });
			});
		});
   });
</script>
