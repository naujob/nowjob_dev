 <div class="contents-search">
    <form id="frm-search" class="controls" method="get" action="/admin/member/member_list">
        <div class="search-box">
        	<h3 class="sub-title mb-10">개인회원 리스트</h3>
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
                            <?=inputSelect("search_type","search_type",[""=>"전체","mm_name"=>"이름","mm_id"=>"아이디","mm_phone"=>"연락처","ad_addr"=>"지역"],$search["search_type"])?>
                            <div class="input-text">
                                <label>
                                     <?=inputText("search_value","search_value",$search["search_value"],"검색어를 입력해주세요.","",200)?>
                                </label>
                            </div>
                        </div>
                    </td>
                    <th scope="row">회원상태</th>
                    <td class="inline-wrap">
                       	<?=inputRadio("mm_status","mm_status",[""=>"전체","Y"=>"가입회원","N"=>"탈퇴회원","S"=>"자격정지"],$search["mm_status"])?>
					</td>
                </tr>
                 <tr>
                    <th scope="row">성별</th>
                    <td class="inline-wrap">
                       	<?=inputRadio("mm_sex","mm_sex",[""=>"전체","남"=>"남자","여"=>"여자"],$search["mm_sex"])?>
					</td>
                    <th scope="row">국적</th>
                    <td class="inline-wrap">
                       	<?=inputRadio("mm_nation","mm_nation",[""=>"전체","내국인"=>"내국인","외국인"=>"외국인"],$search["mm_nation"])?>
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
    	<div class="btn-table inline-wrap">
    		<div class="right">
              <button type="button" class="btn-xs btn-type10 w100" onclick="location.href='./member_reg?type=MEM';" style="float: left;">
                <span>회원생성</span>
              </button>
	            <button type="button" class="btn-xs btn-excel btnExcel">
	            	<img src="/resource/admin/img/icons/icon8/icon-excel.png"  />
	            	<span>엑셀 다운로드</span>
	            </button>
    		</div>
        </div>
        <table class="table-type2">
            <colgroup>
            	<col width="50" />
                <col width="50" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="100" />
                <col width="150" />
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
                <th scope="col">상태</th>
                <th scope="col">가입유형</th>
                <th scope="col">아이디</th>
                <th scope="col">회원명</th>
                <th scope="col">연락처</th>
				<th scope="col">국적</th>
                <th scope="col">성별</th>
                <th scope="col">지역</th>
                <th scope="col">카테고리</th>
                <th scope="col">보유포인트</th>
                <th scope="col">가입일</th>
                <th scope="col">광고수신<br>동의</th>
                <th scope="col">관리</th>
			</tr>
            </thead>
            <tbody>
            	<?php foreach($result_list as $key=> $row) : ?>
				<tr>
					 <td class="tc">
	                	<div class='input-checkbox'>
				            <label>
				                <input type='checkbox' name='chk[]' value='<?=$row["mm_idx"]?>'   />
				                <span style='padding-left:5px;'><strong></strong></span>
				            </label>
				        </div>
	                </td>
	                <td><?=$start - $key?></td>
	                <td>
	                	<?=getMemberStatus($row["mm_status"])?>
	                	<?php if($row["mm_status"]!="S" && $row["stop_dt"]!="0000-00-00 00:00:00") : ?>
	                		 <br/><p class="" style="color:#f33;margin-top:4px;">자격정지이력</p>
	                	<?php endif; ?>
	                </td>
	                <td><?=$row["mm_join_type"]?></td>
                    <td><?=$row["mm_id"]?></td>
	                <td><?=$row["mm_name"]?></td>
	                <td><?=str_replace("-", "", $row["mm_phone"])?></td>
	                <td><?=$row["mm_nation"]?></td>
	                <td><?=$row["mm_sex"]?></td>
	                <td><?=$row["ad_addr"]?></td>
                    <td><?=($row["ma_work"])?></td>
	                <td><?=number_format($row["mm_point"])?></td>
	                <td><?=$row["reg_dt"]?></td>
	                <td><?php echo $row["mm_agree_ad"] === "Y" ? "동의" : ($row["mm_agree_ad"] === "N" ? "미동의" : "-"); ?></td>
	                <td>
	                	<a href="javascript:void(0);" class="btn-xs btn-type3 btnEdit" data-idx="<?=$row["mm_idx"]?>" style="width:auto;">상세정보</a>
	                	<a href="javascript:void(0);" class="btn-xs btn-type1 btnGoLogin" data-mm_idx="<?=$row["mm_idx"]?>" data-mm_status="<?=$row["mm_status"]?>" style="width:auto;">로그인</a>
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
		$(".btnExcel").click(function(){
			$("#frm-search").attr("action","/admin/member/member_list_excel");
			$("#frm-search").submit();
		});

       	$(".btnEdit").click(function(){
           	var idx = $(this).data("idx");
           	location.href="/admin/member/member_edit?idx=" + idx;
       	});

       $(".btnSearch").click(function(){
           $("#frm-search").attr("action","/admin/member/member_list");
           $("#frm-search").submit();
       });

       $(".btnClear").click(function(){
           var params = {};
			params["mm_idx"] = $(this).data("idx");
       		COMMON.confirm("본인인증 키값을 제거(테스트용) 하시겠습니까?",function(){
		        comAjax("/admin/member/clear_mm_di",params,function(result){
		            if(result.code == "00"){
		                COMMON.alert("제거 되었습니다.",function(){
		                    location.reload();
		                });
		            }else{
		                COMMON.alert(result.msg);
		            }
		        });
       		});
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


			COMMON.confirm("비노출 처리 하시겠습니까?",function(){
				var params = {};
				params["proc_mode"] = "hidden";
				params["chk_list"] = chk_list;
				params["mg_idx"] = $("#all_mg_idx").val();
		        comAjax("/admin/member/member_list_proc",params,function(result){
		            if(result.code == "00"){
		                location.reload();
		            }else{
		                COMMON.alert(result.msg);
		            }
		        });
			});
		});

		$(".btnGoLogin").click(function(){
			var mm_idx = $(this).data("mm_idx");
			var mm_status = $(this).data("mm_status");
			if(mm_status!="Y"){
				alert("승인완료된 회원만 로그인이 가능합니다.");
				return;
			}
			// 팝업 차단을 피하려고 클릭 시점에 빈 창을 먼저 열고, 토큰 발급 후 주소를 채운다
			var win = window.open("about:blank");
			if(!win){
				COMMON.alert("팝업 차단을 해제해 주세요.");
				return;
			}
			comAjax("/admin/member/issue_auto_login_token",{mm_idx:mm_idx},function(result){
				if(result.code=="00"){
					win.location.href = "https://nowjob.co.kr/member_proc/auto_login?token=" + encodeURIComponent(result.data.token);
				}else{
					win.close();
					COMMON.alert(result.msg);
				}
			});
		});

   });
</script>
