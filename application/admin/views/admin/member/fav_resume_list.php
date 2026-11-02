 <div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/member/fav_resume_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">이력서 즐겨찾기 리스트</h3>
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
                            <?=inputSelect("search_type","search_type",[""=>"전체","mem_id"=>"즐겨찾기 아이디","mem_name"=>"즐겨찾기 기업명","rc_name"=>"이력서"],$search["search_type"])?>
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
         <table class="table-type2" style="table-layout:fixed;">
            <colgroup>
                 <col width="50" />
                <col width="250" />
                <col width="100" />
                <col width="200" />
                <col width="100" />
                <col width="150" />
                <col width="100" />
                <col width="100" />
                <col width="50" />
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">즐겨찾기 아이디</th>
                <th scope="col">즐겨찾기 기업명</th>
                <th scope="col">이력서</th>
				<th scope="col">이력서 채용가능일</th>
                <th scope="col">이력서 지역</th>
                <th scope="col">이력서 업무구분</th>
                <th scope="col">등록일시</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
	                <td><?=$start - $key?></td>
                    <td><?=$row["mem_mm_id"]?></td>
	                <td><?=$row["mem_mm_comp_name"]?></td>
	                <td><a style="text-decoration:underline;" href="/admin/member/resume_edit?idx=<?=$row["ru_idx"]?>"><?=$row["ru_name"]?></a></td>
                    <td><?=substr($row["ru_work_dt"],0,10)?></td>
	                <td><?=$row["ad_addr"]?></td>
	                <td><?=$row["ru_work"]?></td>
	                <td><?=$row["bk_reg_dt"]?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type4 btnDelete" data-idx="<?=$row["bk_idx"]?>" style="width:auto;">삭제</a>
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
       $(".btnDelete").click(function(){
			var idx = $(this).data("idx");
			COMMON.confirm("삭제 하시겠습니까?",function(){
				var params = {};
				params["idx"] = idx;
			    comAjax("/admin/member/fav_list_proc",params,function(result){
			        if(result.code == "00"){
			            location.reload();
			        }else{
			            COMMON.alert(result.msg);
			        }
			    });
			},function(){

			});
       });


       $(".btnSearch").click(function(){
           $("#frm-search").attr("action","/admin/member/fav_resume_list");
           $("#frm-search").submit();
       });
   });
</script>
