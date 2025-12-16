<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/site/setting_coupon">
        <div class="search-box">
        	<h3 class="sub-title mb-10">쿠폰 발급 설정 리스트</h3>
        	<?php if($admin_info["master_yn"]=="Y") : ?>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w120 btnNew">쿠폰 설정</button>
	        </div>
	        <?php endif; ?>
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
	                        <?=inputSelect("search_type","search_type",[""=>"전체","cp_name"=>"쿠폰명"],$search["search_type"])?>
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
                <col width="*" />
        		<col width="100" />
        		<col width="100" />
        		<col width="100" />
        		<col width="100" />
                <col width="200" />
                <col width="100" />
                <col width="150" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">쿠폰명</th>
                <th scope="col">금액</th>
                <th scope="col">최소사용금액</th>
                <th scope="col">자동여부</th>
                <th scope="col">쿠폰상태</th>
                <th scope="col">사용기간</th>
                <th scope="col">발급수</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
				<?php foreach($result_list as $key=>$row) : ?>
	            <tr>
	                 <td><?=$start - $key?></td>
	               
	                <td class="tl">
	                	<?=$row["cp_name"]?>
	                </td>
	                <td>
	                	<?=number_format($row["cp_price"])?><?=$row["cp_dan"]=="won" ? "원":"%"?>
	                </td>
	                <td>
	                	<?=number_format($row["cp_limit_price"])?>원
	                </td>
	                <td>
	                	<?=$row["cp_auto_type_nm"]?>
	                </td>
	                <td>
	                	<?=$row["cp_status_nm"]?>
	                </td>
	               
	                <td>
	                	발급일로부터 <?=$row["cp_use_day"]?>일
	                </td>
	         
	                <td><a href="/admin/site/coupon_list?cp_idx=<?=$row["cp_idx"]?>"><?=number_format($row["cp_cnt"])?>건</a></td>
	                <td><?=substr($row["reg_dt"],0,10)?></td>
	                <td>
	                	<?php if($admin_info["master_yn"]=="Y") : ?>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["cp_idx"]?>" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete" data-idx="<?=$row["cp_idx"]?>" style="width:auto;">삭제</a>
	                	<?php endif; ?>
	                	<a href="/admin/site/coupon_edit?cp_idx=<?=$row["cp_idx"]?>" class="btn-xs btn-type1 btnManual" data-idx="" style="width:auto;">수동발급</a>
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
		location.href="/admin/site/setting_coupon_edit";
	});
	
	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/site/setting_coupon_edit?idx=" + idx;
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
	        comAjax("/admin/site/setting_coupon_edit_proc",params,function(result){
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