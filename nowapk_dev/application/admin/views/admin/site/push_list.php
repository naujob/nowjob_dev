<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/site/push_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">푸시 관리</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w120 btnNew">푸시 발송</button>
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
                            <?=inputSelect("search_type","search_type",[""=>"-- 전체검색 --","pu_adm_name"=>"푸시 관리명"],$search["search_type"])?>
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
                <col width="180" />
                <col width="*" />

                <col width="150" />
                <col width="150" />
                <col width="200" />
                <col width="120" />
                <col width="200" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">푸시 관리명</th>
                <th scope="col">제목</th>
                <th scope="col">내용</th>
                <th scope="col">이미지</th>
                <th scope="col">상태</th>
                <th scope="col">결과</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
				<?php foreach($result_list as $key=>$row) : ?>
				<tr>
	                <td><?=$start - $key?></td>
	                <td class="tl"><?=$row["pu_adm_title"]?></td>
	                <td class="tl"><?=$row["pu_title"]?></td>
	                <td class="tl"><?=$row["pu_desc"]?></td>
	                <td>
                		<?php if($row["pu_img"]!="") : ?>
	                	<img src="<?=$row["pu_img"]?>" style="width:100px;" />
	                	<?php endif; ?>
	                </td>
                    <td><?=($row["pu_status"]=="N" ? "발송대기":"발송완료")?></td>
                    <td><?=($row["pu_success_cnt"])?> / <?=($row["pu_fail_cnt"])?></td>
	                <td><?=substr($row["reg_dt"],0,10)?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["pu_idx"]?>" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete" data-idx="<?=$row["pu_idx"]?>" style="width:auto;">삭제</a>
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
		location.href="/admin/site/push_edit";
	});

	//수정 이동
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/site/push_edit?idx=" + idx;
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
	        comAjax("/admin/site/push_edit_proc",params,function(result){
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
