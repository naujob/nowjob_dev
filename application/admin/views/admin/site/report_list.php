<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="<?=$list_page?>">
        <div class="search-box">
        	<h3 class="sub-title mb-10">신고관리 리스트</h3>
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
                            <?=inputSelect("search_type","search_type",[""=>"-- 전체검색 --","subject"=>"제목","br_desc"=>"내용"],$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                  	<th scope="row">유형</th>
	                <td class="inline-wrap">
	                     <?=inputSelect("br_type","br_type",[""=>"-- 유형 --","community"=>"커뮤니티","trade"=>"중고거래","resume"=>"이력서","recruit"=>"채용공고"],$search["br_type"])?>
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
                <col width="120" />
                <col width="250" />
                <col width="*" />
                <col width="150" />
                <col width="200" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">유형</th>
                <th scope="col">제목</th>
                <th scope="col">내용</th>
                <th scope="col">작성자</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
	               	<td><?=$start - $key?></td>
	               	<td ><?=$row["br_type_nm"]?></td>
	                <td class="tl"><?=$row["subject"]?></td>
	                <td><textarea style="width:100%;height:66px;border:solid 1px #ddd;" readonly="true"><?=$row["br_desc"]?></textarea></td>
	                <td><?=$row["mm_name"]?></td>
	                <td><?=substr($row["reg_dt"],0,10)?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnConfirm" data-br_type="<?=$row["br_type"]?>" data-br_idx="<?=$row["br_idx"]?>" style="width:auto;">게시글 보기</a>
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
		location.href="/admin/site/report_edit?idx=" + idx;
	});
	
	//수정해야함
	$(".btnConfirm").click(function(){
		var br_idx = $(this).attr("data-br_idx");
		var br_type = $(this).attr("data-br_type");
		
		if(br_type=="community"){
			location.href = "/admin/site/community_edit?idx=" + br_idx;	
		}else if(br_type=="trade"){
			location.href = "/admin/trade/trade_edit?idx=" + br_idx;	
		}else if(br_type=="resume"){
			location.href = "/admin/member/resume_edit?idx=" + br_idx;	
		}else if(br_type=="recruit"){
			location.href = "/admin/member/recruit_edit?idx=" + br_idx;	
		}
	});
	
	$(".btnSearch").click(function(){
		$("#frm-search").submit();
	});
});
</script>