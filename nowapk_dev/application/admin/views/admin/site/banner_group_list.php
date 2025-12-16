<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/site/banner_group_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">배너 그룹 관리</h3>
        	<!--div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w150 btnNew">배너 그룹 등록</button>
	        </div-->
	        <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="1350px"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">검색어</th>
                    <td class="inline-wrap">
                         <div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",[""=>"-- 전체검색 --","bg_name"=>"배너 그룹명","bg_desc"=>"설명"],$search["search_type"])?>
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
                <col width="100" />
                <col width="230" />
                <col width="*" />
                <col width="100" />
                <col width="100" />
                <col width="150" />
                <col width="250" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">노출 여부</th>
                <th scope="col">배너 그룹명</th>
                <th scope="col">설명</th>
                <th scope="col">등록 배너수</th>
                <th scope="col">배너 등록 가능수</th>
                <th scope="col">등록일</th>
                <th scope="col">배너설정</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=>$row) : ?>
				<tr>
	                <td><?=$start + $key?></td>
	                <td><?=$row["bg_status"]=="Y" ? "<span class='status-able'>노출</span>":"<span class='status-disable'>비노출</span>"?></td>
	                <td class="tl"><?=$row["bg_name"]?></td>
	                <td class="tl">
	                	<?=$row["bg_desc"]?>
					</td>
	                <td><?=$row["banner_cnt"]?></td>
	                <td><?=$row["max_len"]?></td>
	                <td><?=substr($row["reg_dt"],0,10)?></td>
	                 <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type1 btnBanner" data-idx="<?=$row["bg_idx"]?>" style="width:auto;">배너설정</a>
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
	//수정 이동
	$(".btnBanner").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/site/banner_list?bg_idx=" + idx;
	});
	
	$(".btnSearch").click(function(){
		$("#frm-search").submit();
	});
</script>