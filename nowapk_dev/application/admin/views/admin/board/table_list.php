<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/board/table_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">게시판 그룹 리스트</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w120 btnNew">게시판 등록</button>
	        </div>
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
                            <?=inputSelect("search_type","search_type",getOptionList("table_search"),$search["search_type"])?>
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
                           <?=inputRadio("show_yn","show_yn",getOptionList("all_display_yn"),$search["show_yn"])?>
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
                <col width="*" />
                <col width="120" />
                <col width="120" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">노출여부</th>
                <th scope="col">기본게시판여부</th>
                <th scope="col">노출위치</th>
                <th scope="col">게시판명</th>
                <th scope="col">등록일</th>
                <th scope="col">수정일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
	                <td><?=$start - $key?></td>
	                 <td>
	                	<span class="status-able"><?=$row["show_yn_nm"]?></span>
	                </td>
	                <td>
	                	<span class="status-<?=$row["tb_basic_yn"]=="Y" ? "danger":"primary"?>"><?=$row["basic_yn_nm"]?></span>
	                </td>
	                <td><?=$row["location_nm"]?></td>
	                <td class="tl"><?=$row["tb_name"]?></td>
	                <td><?=substr($row["reg_dt"],0,10)?></td>
	                <td><?=substr($row["up_dt"],0,10)?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["tb_idx"]?>" style="width:auto;"><?=$row["tb_basic_yn"]=="Y" ? "확인":"수정"?></a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type1 btnDirect" data-id="<?=$row["tb_id"]?>" style="width:auto;">바로가기</a>
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
		location.href="/admin/board/table_edit?idx=" + idx;
	});
	
	$(".btnDirect").click(function(){
		var id = $(this).data("id");
		location.href="/admin/board/board_list?tb_id=" + id;
	});
	
	$(".btnNew").click(function(){
		location.href="/admin/board/table_edit";
	});
	
	$(".btnSearch").click(function(){
		$("#frm-search").submit();
	});
});
</script>