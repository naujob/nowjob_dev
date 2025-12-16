<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="<?=$list_page?>">
        <div class="search-box">
        	<h3 class="sub-title mb-10">1:1문의 리스트</h3>
        	
            <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="*"/>
                    <col width="150px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">검색어</th>
                    <td class="inline-wrap">
                         <div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",[""=>"전체","bd_subject"=>"제목"],$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <th scope="row">답변 여부</th>
                    <td class="inline-wrap">
                       	<?=inputRadio("bd_aws_yn","bd_aws_yn",["" => "전체", "Y"=>"답변완료","N "=>"미답변"],$search["bd_aws_yn"])?>
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
                <col width="200" />
                <col width="120" />
                <col width="*" />
        		<col width="100" />
                <col width="120" />
                <col width="150" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">회원명(사업자명)</th>
                <th scope="col">분류</th>
                <th scope="col">제목</th>
                <th scope="col">답변여부</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
				<?php foreach($result_list as $key=> $row) : ?>
				
	            <tr>
	               	<td><?=$start - $key?></td>
	                <td><?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(" . $row["mm_comp_name"] . ")" : ""?></td>
	                <td><?=$row["bd_cate"]?></td>
	                <td class="tl"><?=$row["bd_subject"]?></td>
	                <td><?=$row["bd_aws_yn"]=="Y" ? "<span class='status-able'>답변완료</span>":"<span class='status-disable'>미답변</span>"?></td>
	                <td><?=substr($row["reg_dt"],0,10)?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-tb_id="<?=$row["tb_id"]?>" data-idx="<?=$row["bd_idx"]?>" style="width:auto;">답변달기</a>
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
		location.href="<?=$edit_page?>?idx=" + idx;
	});
	
	//수정해야함
	$(".btnDirect").click(function(){
		alert("싸이트 만든후 적용");
	});
	
	$(".btnNew").click(function(){
		if($("#tb_id").val()==""){
			COMMON.alert("게시판을 선택해주세요.",function(){
				$("#tb_id").focus();
			});
			return;
		}
		location.href="<?=$edit_page?>";
	});
	
	$(".btnDelete").click(function(){
		var idx = $(this).data("idx");
		COMMON.confirm("삭제 하시겠습니까?",function(){
			var params = {};
			params["idx"] = idx;
			params["proc_mode"] = "delete";
	        comAjax("/admin/site/board_edit_proc",params,function(result){
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
	
	$(".btnSearch").click(function(){
		$("#frm-search").submit();
	});
});
</script>