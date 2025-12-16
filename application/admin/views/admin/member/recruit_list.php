 <div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/member/recruit_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">채용공고 리스트</h3>
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
                            <?=inputSelect("search_type","search_type",[""=>"전체","mm_name"=>"상호명","rc_name"=>"공고명","ad_addr"=>"지역"],$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <th scope="row">노출여부</th>
                    <td class="inline-wrap">
                       	<?=inputRadio("show_yn","show_yn",[""=>"전체","Y"=>"노출","N"=>"비노출","H"=>"관리자 비공개"],$search["show_yn"])?>
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
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="180" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
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
                <th scope="col">노출여부</th>
                <th scope="col">사업장명</th>
                <th scope="col">공고명</th>
                <th scope="col">기간</th>
				<th scope="col">국적</th>
                <th scope="col">성별</th>
                <th scope="col">지역</th>
                <th scope="col">카테고리</th>
                <th scope="col">등록일시</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
					<td class="tc">
	                	<div class='input-checkbox'>
				            <label>
				                <input type='checkbox' name='chk[]' value='<?=$row["rc_idx"]?>'   />
				                <span style='padding-left:5px;'><strong></strong></span>
				            </label>
				        </div>
	                </td>
	                <td><?=$start - $key?></td>
                    <td>
	                	<?=getRecruitUseYn($row["rc_status"])?>
	                </td>
	                <td>
	                	<?=getRecruitStatus($row["rc_show_yn"])?>
	                </td>
	                <td><?=$row["mm_comp_name"]?></td>
	                <td><?=$row["rc_name"]?></td>
	                <td>&nbsp;&nbsp;<?=substr($row["rc_sdate"],0,10)?><br/>~<?=substr($row["rc_edate"],0,10)?></td>
	                <td><?=$row["rc_nation"]?></td>
	                <td><?=$row["rc_sex"]?></td>
	                <td><?=$row["ad_addr"]?></td>
                    <td><?=($row["rc_work"])?></td>
	                <td><?=$row["reg_dt"]?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["rc_idx"]?>" style="width:auto;">상세정보</a>
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
           location.href="/admin/member/recruit_edit?idx=" + idx;
       });

       $(".btnSearch").click(function(){
           $("#frm-search").attr("action","/admin/member/recruit_list");
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
				COMMON.alert("처리하실 회원을 선택해 주세요.");
				return;
			}


			var idx = $("[name=rc_idx]").val();
			COMMON.confirm("비노출처리 하시겠습니까?",function(){
				var params = {};
				params["proc_mode"] = "hidden";
				params["chk_list"] = chk_list;
		        comAjax("/admin/member/recruit_list_proc",params,function(result){
		            if(result.code == "00"){
		                COMMON.alert(result.msg,function(){
		                    location.href="/admin/member/recruit_list";
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
