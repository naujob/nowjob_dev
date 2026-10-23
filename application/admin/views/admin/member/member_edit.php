<form id="frm" class="controls" method="post">
    <input type="hidden" name="mm_idx" value="<?=getData($info,"mm_idx")?>" />
    <input type="hidden" name="proc_mode" value="<?=getProcMode($info)?>" />

    <div class="contents-frm">
        <div class="section">
            <h3 class="sub-title mb-10">회원정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="180px"/>
                    <col width="600px"/>
                    <col width="180px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
               <tr>
                    <th scope="col">아이디</th>
                    <td>
                       	<div class="input-text ">
                            <?=$info["mm_id"]?>
                        </div>
                    </td>
                    <th scope="col">회원명</th>
                    <td>
                       	<div class="input-text ">
                            <label>
                                <?=$info["mm_name"]?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">회원유형</th>
                    <td>
                        <?=$info["mm_type"]=="WEB" ? "일반":"기업"?>
                    </td>
                    <th scope="col">가입유형</th>
                    <td>
                        <?=$info["mm_join_type"]=="KAKAO" ? "카카오": ($info["mm_join_type"]=="WEB" ? "이메일" : "애플")?>
                    </td>
                </tr>
                <tr>
                   <th scope="col">비밀번호</th>
                   <td>
                       <a href="javascript:void(0);" id="btnRePwd" class="btn-xs btn-type10 ">비밀번호 초기화</a>
                   </td>
                   <th scope="col">국적</th>
                   <td>
                        <div class="input-text ">
                           <label>
                               <?=$info["mm_nation"]?>
                           </label>
                       </div>
                   </td>
               </tr>
               <tr>
                     <th scope="col">성별</th>
                     <td>
                        	<div class="input-text ">
                             <label>
                                 <?=$info["mm_sex"]?>
                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                 <a href="javascript:fSaveGender('M');" style="background-color: #2a3954; color: #ddd; padding: 5px 10px; margin-left: 20px;">남자로 저장</a>
                                 <a href="javascript:fSaveGender('W');" style="background-color: #2a3954; color: #ddd; padding: 5px 10px; margin-left: 20px;">여자로 저장</a>
                             </label>
                             <script>
                             function fSaveGender(arg){
                                if(confirm('저장하시겠습니까?')){
                                    var params = {};
                            			  params["mm_idx"] = $("[name='mm_idx']").val();
                                    if(arg=='M'){
                                      params["mm_sex"] = '남';
                                    }
                                    if(arg=='W'){
                                      params["mm_sex"] = '여';
                                    }
                                    comAjax("/admin/member/member_gendersave",params,function(result){
                                        if(result.code == "00"){
                                            COMMON.alert("성별이 수정되었습니다.",function(){
                                              location.reload();
                                            });
                                        }else{
                                            COMMON.alert(result.msg);
                                        }
                                    });
                                }
                             }
                             </script>
                         </div>
                     </td>
                     <th scope="col">생년월일</th>
                     <td>
                    	<div class="input-text ">
                             <label>
                                 <input type="text" id="mm_birth" value="<?=$info["mm_birth"]?>" style="max-width: 100px;">
                                 예) YYYYMMDD
                                 <a href="javascript:fSaveBirth();" style="background-color: #2a3954; color: #ddd; padding: 5px 10px; margin-left: 20px;">생년월일저장</a>
                             </label>
                             <script>
                             function fSaveBirth(){
                               if($('#mm_birth').val()==''){
                                  alert('생년월일을 입력하세요');
                                  return;
                               }
                               if($('#mm_birth').val() >= '19010101' && $('#mm_birth').val() <= '20991231'){
                                  if(confirm('저장하시겠습니까?')){
                                      var params = {};
                              			  params["mm_idx"] = $("[name='mm_idx']").val();
                                      params["mm_birth"] = $("#mm_birth").val();
                                      comAjax("/admin/member/member_birthsave",params,function(result){
                                          if(result.code == "00"){
                                              COMMON.alert("생년월일이 수정되었습니다.",function(){
                                                location.reload();
                                              });
                                          }else{
                                              COMMON.alert(result.msg);
                                          }
                                      });

                                  }
                                  return;
                               }else{
                                 alert('생년월일을 YYYYMMDD 형태로 입력하세요');
                                 return;
                               }
                             }
                             </script>
                         </div>
                     </td>
                 </tr>

                 <tr>
                    <th scope="col">휴대폰번호<em class="ess"></em></th>
                   	<td class="inline-wrap">
					     <div class="input-text  mt-5">
                            <label>
                            	<?=$info["mm_phone"]?>
                            </label>
                        </div>
					</td>
                    <th scope="col">보유포인트</th>
                    <td>
                       	 <div class="input-text  mt-5">
                            <label>
                                <?=number_format($info["mm_point"])?>P
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">회원상태</th>
                    <td colspan="3">
                        <div class="input-text ">
                            <?=getMemberStatus($info["mm_status"])?>
                        </div>
                    </td>
                </tr>
                <?php if($info["fire_dt"]!="0000-00-00 00:00:00") : ?>
                <tr>
                    <th scope="col">탈퇴일시</th>
                    <td>
                        <div class="input-text ">
                            <?=$info["fire_dt"]?>
                        </div>
                    </td>
                    <th scope="col">탈퇴사유</th>
                    <td>
                        <div class="input-text ">
                            <?=inputTextArea("fire_reason_desc","fire_reason_desc",$info["fire_reason_desc"],"","style='width:100%;height:60px;padding:10px;' readonly='true' ","")?>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                <?php if($info["stop_dt"]!="0000-00-00 00:00:00") : ?>
                <tr>
                    <th scope="col">자격정지일시</th>
                    <td>
                        <div class="input-text ">
                            <?=$info["stop_dt"]?>
                        </div>
                    </td>
                    <th scope="col">정지사유</th>
                    <td>
                        <div class="input-text ">
                            <?=inputTextArea("mm_stop_desc","mm_stop_desc",$info["mm_stop_desc"],"","style='width:100%;height:60px;padding:10px;' readonly='true' ","")?>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">추가정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="180px"/>
                    <col width="600px"/>
                    <col width="180px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
               <tr>
                    <th scope="col">희망업무</th>
                    <td colspan="3">
                       	<div class="input-text ">
                            <?=$add["ma_work"]?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">근무형태</th>
                    <td colspan="3">
                       	<div class="input-text ">
                            <label>
                                <?=$add["ma_work_type"]?>
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">희망급여</th>
                    <td colspan="3">
                        	<div class="input-text ">
                             <?=$add["ma_price_type"]?>
                         </div>
                     </td>

                 </tr>
                 <tr>
                     <th scope="col">근무요일</th>
                     <td colspan="3">
                        	<div class="input-text ">
                             <label>
                                 <?=$add["ma_week"]?>
                             </label>
                         </div>
                     </td>
                 </tr>
                 <tr>
                      <th scope="col">근무시작일</th>
                      <td colspan="3">
                         	<div class="input-text ">
                              <?=substr($add["ma_work_dt"],0,10)?>
                          </div>
                      </td>

                  </tr>
                  <tr>
                      <th scope="col">최종학력</th>
                      <td colspan="3">
                         	<div class="input-text ">
                              <label>
                                  <?=$add["ma_edu"]?>
                              </label>
                          </div>
                      </td>
                  </tr>
                  <tr>
                       <th scope="col">간단경력</th>
                       <td colspan="3" style="vertical-align:top;">
                           <table class="table-small-info" style="max-width:500px;">
                           <tr>
                           		<th style="width:100px;">구분</th>
                           		<th style="width:200px;">회사명</th>
                           		<th style="width:200px;">기간</th>
                           </tr>
                           <?php foreach($career_list as $row) : ?>
                            <tr>
                               <td><?=$row["mc_type"]?></td>
                               <td><?=$row["mc_name"]?></td>
                               <?php
                                   $is_date = false;
                                   $sdate = "";
                                   $edate = "";
                                   $arr_date = explode("~",$row["mc_date"]);
                                   if(count($arr_date) > 1){
                                       $is_date = true;
                                       $sdate = str_replace(".","-",trim($arr_date[0])) . "-01";
                                       $edate = str_replace(".","-",trim($arr_date[1])) . "-01";
                                   }
                               ?>
                               <td><?=$row["mc_date"]?><?=$is_date ? "(" . get_date_diff_txt($sdate,$edate) . ")" : ""?></td>
                           </tr>
                           <?php endforeach; ?>
                           </table>
                       </td>

                   </tr>
                   <tr>
                       <th scope="col">자격증</th>
                       <td colspan="3">
                           <table class="table-small-info" style="max-width:500px;">
                           <tr>
                           		<th style="width:100px;">구분</th>
                           		<th style="width:200px;">자격증명</th>
                           		<th style="width:200px;">취득일</th>
                           </tr>
                           <?php foreach($certi_list as $row) : ?>
                           <tr>
                               <td>자격증</td>
                               <td><?=$row["mc_name"]?></td>
                               <td><?=$row["mc_dt"]?></td>
                           </tr>
                           <?php endforeach; ?>
                            </table>
                       </td>
                   </tr>
                   <tr>
                        <th scope="col">나의강점</th>
                        <td colspan="3" style="vertical-align:top;">
                           	<div class="input-text ">
                                <?=$add["ma_my_type"]?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="col">자기소개</th>
                        <td colspan="3">
                           	<div class="input-text ">
                                <?=str_replace("\n","<br/>",$add["ma_desc"])?>
                            </div>
                        </td>
                    </tr>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">이력서 리스트</h3>
           	<table class="table-type2">
	            <colgroup>
	                <col width="50" />
	                <col width="70" />
	                <col width="150" />
	                <col width="80" />
	                <col width="80" />
	                <col width="100" />
	                <col width="80" />
	                <col width="80" />
	                <col width="100" />
	                <col width="80" />
	            </colgroup>
	            <thead>
	            <tr>
	                <th scope="col">번호</th>
	                <th scope="col">이미지</th>
	                <th scope="col">제목</th>
	                <th scope="col">노출여부</th>
	                <th scope="col">희망업무</th>
	                <th scope="col">지역</th>
					<th scope="col">급여형태</th>
	                <th scope="col">근무형태</th>
	                <th scope="col">등록일시</th>
	                <th scope="col">관리</th>
				</tr>
	            </thead>
            	<tbody>
            		<?php foreach($resume_list as $row) : ?>
            		<tr>
            			<td><?=$row["ru_idx"]?></td>
            			<td><img src="<?=$row["ru_img1"]?>" style="height:50px;" /></td>
            			<td><?=$row["ru_name"]?></td>
            			<td><?=$row["ru_show_yn"]=="Y" ? "공개이력서":"-"?></td>
            			<td><?=($row["ru_work"])?></td>
            			<td><?=$row["ad_addr"]?></td>
            			<td><?=$row["ru_price_type"]?></td>
            			<td><?=$row["ru_work_type"]?></td>
            			<td><?=$row["reg_dt"]?></td>
            			<td><a href="/admin/member/resume_edit?idx=<?=$row["ru_idx"]?>" class="btn-xs btn-type3" style="width:auto;">이력서 정보</a></td>
            		</tr>
            		<?php endforeach?>
            	</tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">포인트 리스트</h3>
           	<table class="table-type2">
	            <colgroup>
	                <col width="50" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <col width="400" />
	                <col width="100" />
	                <!-- <col width="100" /> -->
	            </colgroup>
	            <thead>
	            <tr>
	                <th scope="col">번호</th>
	                <th scope="col">가감 유형</th>
	                <th scope="col">가감 포인트</th>
	                <th scope="col">최종포인트</th>
	                <th scope="col">내용</th>
	                <th scope="col">등록일시</th>
	                <!-- <th scope="col">관리</th> -->
				</tr>
	            </thead>
            	<tbody>
            		<?php foreach($point_list as $row) : ?>
            		<tr>
            			<td><?=$row["pl_idx"]?></td>
            			<td><?=getPointStatus($row["pl_type"])?></td>
            			<td><?=number_format($row["pl_point"])?>P</td>
            			<td><?=number_format($row["pl_last_point"])?>P</td>
            			<td><?=$row["pl_desc"]?></td>
            			<td><?=$row["reg_dt"]?></td>
            			<!-- <td><a href="/admin/point/point_edit?ru_idx=<?=$row["pl_idx"]?>" class="btn-xs btn-type3" style="width:auto;">포인트 정보</a></td> -->
            		</tr>
            		<?php endforeach?>
            	</tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">결제 리스트</h3>
           	<table class="table-type2">
	            <colgroup>
	                <col width="50" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <col width="200" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	            </colgroup>
	            <thead>
	            <tr>
	                <th scope="col">번호</th>
	                <th scope="col">주문자명</th>
	                <th scope="col">주문자연락처</th>
	                <th scope="col">구매금액</th>
	                <th scope="col">쿠폰사용금액</th>
	                <th scope="col">충전포인트</th>
	                <th scope="col">상품명</th>
	                <th scope="col">결제수단</th>
	                <th scope="col">결제상태</th>
	                <th scope="col">결제일시</th>
	                <th scope="col">관리</th>
				</tr>
	            </thead>
            	<tbody>
            		<?php foreach($order_list as $row) : ?>
            		<tr>
            			<td><?=$row["o_idx"]?></td>
            			<td><?=$row["o_oname"]?></td>
            			<td><?=$row["o_ophone"]?></td>
            			<td><?=number_format($row["o_total_price"])?></td>
            			<td><?=number_format($row["o_coupon_price"])?></td>
            			<td><?=number_format($row["order_point"])?></td>
            			<td><?=$row["o_goods_name"]?></td>
            			<td><?=$row["o_paymethod"]?></td>
            			<td><?=getOrderStatus($row["o_status"])?></td>
            			<td><?=$row["o_pay_dt"]?></td>
            			<td><a href="/admin/order/order_edit?o_idx=<?=$row["o_idx"]?>" class="btn-xs btn-type3" style="width:auto;">결제 상세 정보</a></td>
            		</tr>
            		<?php endforeach?>
            	</tbody>
            </table>
        </div>

        <div class="section">
			<h3 class="sub-title mb-10">즐겨찾기 리스트(최근 10건)</h3>
			<table class="table-type2">
				<colgroup>
					<col width="50" />
					<col width="100" />
					<col width="100" />
					<col width="100" />
					<col width="100" />
					<col width="100" />
				</colgroup>
				<thead>
				<tr>
					<th scope="col">번호</th>
					<th scope="col">아이디</th>
					<th scope="col">기업명</th>
					<th scope="col">연락처</th>
					<th scope="col">기업지역</th>
					<th scope="col">등록일시</th>
				</tr>
				</thead>
				<tbody>
					<?php foreach($fav_list as $row) : ?>
					<tr>
						<td><?=$row["bk_idx"]?></td>
						<td><?=$row["mm_id"]?></td>
						<td><?=$row["mm_comp_name"]?></td>
						<td><?=$row["mm_phone"]?></td>
						<td><?=$row["ad_addr"]?></td>
						<td><?=$row["bk_reg_dt"]?></td>
					</tr>
					<?php endforeach?>
				</tbody>
			</table>
		</div>
		<div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <?php if($info["mm_status"]!="S") : ?>
                <button type="button" id="btnStopPopup" class="btn-l btn-type6 w150 ml-10">자격정지</button>

            <?php else : ?>
            	<button type="button" id="btnReturn" class="btn-l btn-type6 w150 ml-10">자격정지 해제</button>
            <?php endif; ?>
            <button type="button" id="btnDelete" class="btn-l btn-type4 w150 ml-10">삭제</button>
        </div>
    </div>
</form>


<div class="popup popup-stop popStop">
   <div class="ds-table">
       <div class="ds-table-cell">
           <div class="container w700">
               <div class="header">
                   <h2>회원 강제정지</h2>
                   <button type="button" class="btn-close" onclick="popClose('.popup-stop');">닫기</button>
               </div>
               <div class="contents">
                   <div class="info mb-20">
                       <h3 class="bold mb-10">선택하신회원을 강제 정지 하시겠습니까?</h3>

                   </div>
                   <table class="table-form">
                       <colgroup>
                           <col width="150px"/>
                           <col width="*"/>
                       </colgroup>
                       <tbody>
                       <tr>
                           <th scope="row" class="tl">사유</th>
                           <td class="inline-wrap tl">
                               <?=inputTextArea("mm_stop_desc","mm_stop_desc","","","style='width:100%;height:100px;padding:10px;'","100")?>
                           </td>
                       </tr>
                       </tbody>
                   </table>
               </div>
               <div class="btn-wrap inline-wrap tc">
                   <button type="button" class="btn-m btn-type1" onclick="popClose('.popup-stop');">닫기</button>
                   <button type="button" class="btn-m btn-type6 btnStop" id="btnStop">정지처리</button>
               </div>
           </div>
       </div>
   </div>
</div>
<script>
	$("#btnRePwd").click(function(){
		COMMON.confirm("비밀번호를 초기화 하시겠습니까?",function(){
			var params = {};
			params["mm_idx"] = $("[name='mm_idx']").val();
		    comAjax("/admin/member/member_repwd",params,function(result){
		        if(result.code == "00"){
		            COMMON.alert("임시 비밀번호가 고객에게 발송되었습니다.",function(){
	            		location.reload();
		            });
		        }else{
		            COMMON.alert(result.msg);
		        }
		    });
		},function(){

		});
	});

	//리스트이동
    $("#btnList").click(function(){
        history.go(-1);
    });

    $("#btnDelete").click(function(){
		var idx = $("[name=mm_idx]").val();
		COMMON.confirm("회원 정보와 공고·이력서·채팅방·주문 등 연관 데이터가 모두 삭제됩니다.<br/>삭제된 정보는 복구할 수 없습니다. 삭제하시겠습니까?",function(){
			var params = {};
			params["idx"] = idx;
			params["proc_mode"] = "delete";

	        comAjax("/admin/member/member_edit_proc",params,function(result){
	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    location.href="/admin/member/member_list";
	                });
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
		},function(){

		});
	});

	$("#btnReturn").click(function(){
		var idx = $("[name=mm_idx]").val();
		COMMON.confirm("자격정지 해제 하시겠습니까?<br/>회원상태가 회원가입으로 변경됩니다.",function(){
			var params = {};
			params["idx"] = idx;
			params["proc_mode"] = "return";
	        comAjax("/admin/member/member_edit_proc",params,function(result){
	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    //location.href="/admin/member/member_list";
                      location.reload();
	                });
	            }else{
	                COMMON.alert(result.msg);
	            }
	        });
		},function(){

		});
	});

    $("#btnStopPopup").click(function(){
        $(".popStop").show();
    });

    $("#btnStop").click(function(){
        if($("#mm_stop_desc").val()==""){
            COMMON.alert("사유를 입력해주세요.",function(){
                $("#mm_stop_desc").focus();
            });
            return;
        }

		var idx = $("[name=mm_idx]").val();
        var params = {};
        params["idx"] = idx;
        params["mm_stop_desc"] = $("#mm_stop_desc").val();
        params["proc_mode"] = "stop";
        comAjax("/admin/member/member_edit_proc",params,function(result){
            if(result.code == "00"){
                COMMON.alert(result.msg,function(){
                    //location.href="/admin/member/member_list";
                    location.reload();
                });
            }else{
                COMMON.alert(result.msg);
            }
        });
	});
</script>
