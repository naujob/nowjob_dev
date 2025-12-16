<div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/setting/manage/user">
        <div class="search-box">
        	<h3 class="sub-title mb-10">운영자 계정관리</h3>
        	<div class="btn-top inline-wrap">
	            <button type="button" class="btn-m btn-type6 w100 btnNew">신규등록</button>
	        </div>
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
                            <?=inputSelect("search_type","search_type",[""=>"전체"],$search["search_type"])?>
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
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="150" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">메뉴권한</th>
                <th scope="col">관리자 아이디</th>
                <th scope="col">성명</th>
                <th scope="col">연락처</th>
                <th scope="col">이메일</th>
                <th scope="col">상태</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
	                <td><?=$start - $key?></td>
	                <td><?=$row["mg_name"]?></td>
	                <td><?=$row["au_id"]?></td>
	                <td><?=$row["au_name"]?></td>
	                <td><?=$row["au_hp"]?></td>
	                <td><?=$row["au_email"]?></td>
	                <td>
	                	<?php if($row["au_status"]=="Y"){?>
	                	<span class="status-able">사용중</span>
	                	<?php }else{ ?>
	                	<span class="status-disable">정지</span>
	                	<?php  } ?>
	                </td>
	                <td><?=substr($row["reg_dt"],0,10)?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["au_idx"]?>" style="width:auto;">수정</a>
	                	<a href="javascript:void(0);" data-idx="<?=$row["au_idx"]?>" class="btn-xs btn-type4 btnDelete" style="width:auto;">삭제</a>
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
	//검색
	$(".btnSearch").click(function(){
		$("#frm-search").submit();
	});

	//등록
	$(".btnNew").click(function(){
		location.href="/admin/setting/manage/user_edit";
	});
	
	//수정
	$(".btnEdit").click(function(){
		var idx = $(this).data("idx");
		location.href="/admin/setting/manage/user_edit?idx=" + idx;
	});

	$(".btnDelete").click(function(){
		var idx = $(this).data("idx");
		COMMON.confirm("삭제 하시겠습니까?",function(){
			var params = {};
			params["idx"] = idx;
			params["proc_mode"] = "delete";
	        comAjax("/admin/setting/manage/user_edit_proc",params,function(result){
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