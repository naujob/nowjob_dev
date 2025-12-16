<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/setting/manage/grant_menu">
        <div class="search-box">
        	<h3 class="sub-title mb-10">운영자 계정관리</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w150 btnNew">권한 메뉴 등록</button>
	        </div>
            <table>
                <colgroup>
                    <col width="150px"/>
                    <col width="*"/>
                </colgroup>
                <tbody>
                <tr>
                    <th scope="row">검색어</th>
                    <td colspan="3" class="inline-wrap">
						<div class="sum-select-input">
                            <?=inputSelect("search_type","search_type",getOptionList("search_grant_menu"),$search["search_type"])?>
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
                <col width="100" />
                <col width="*" />
                <col width="150" />
                <col width="150" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">권한명</th>
                <th scope="col">설정수</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
	                <td><?=$start - $key?></td>
	                <td class="tl"><?=$row["mg_name"]?></td>
	                <td><?=$row["set_cnt"]?></td>
	                <td><?=substr($row["reg_dt"],0, 10)?></td>
	                <td>
                		<?php if($row["mg_master_yn"]=="N") : ?>
	                	<a href="javascript:void(0);" data-idx="<?=$row["mg_idx"]?>" class="btn-xs btn-type3 btnEdit" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" data-idx="<?=$row["mg_idx"]?>" class="btn-xs btn-type4 btnDelete" style="width:auto;">삭제</a>
	                	<?php else : ?>
	                		<span class="btn-xs status-disable" >수정불가</span>
	                	<?php endif;?>
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
		//검색
		$(".btnSearch").click(function(){
			$("#frm-search").submit();
		});
	

		//등록 화면이동
		$(".btnNew").click(function(){
			location.href="/admin/setting/manage/grant_menu_edit";
		});
		
		//수정처리
		$(".btnEdit").click(function(){
			var idx = $(this).data("idx");
			location.href="/admin/setting/manage/grant_menu_edit?mg_idx=" + idx;
		});
		
		$(".btnDelete").click(function(){
			var idx = $(this).data("idx");
			COMMON.confirm("삭제 하시겠습니까?",function(){
				var params = {};
				params["idx"] = idx;
				params["proc_mode"] = "delete";
		        comAjax("/admin/setting/manage/grant_menu_proc",params,function(result){
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
	});
</script>