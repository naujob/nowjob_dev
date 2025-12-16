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
                    <th scope="col">사업장명</th>
                    <td>
                        <?=$info["mm_comp_name"]?>
                        <?=$info["mm_comp_type"]=="" ? "":""?>
                    </td>
                    <th scope="col">사업자번호</th>
                    <td>
                        <?=$info["mm_comp_num"]=="" ? "-":$info["mm_comp_num"]?><br/>
                    </td>
                </tr>
                <tr>
                    <th scope="col">사업자유형</th>
                    <td>
                        <?=getCompType($info["mm_comp_type"])?>
                        <?php if($info["mm_comp_out_yn"]=="N" && $info["mm_comp_type"]=="OUT") : ?>
                   			<a style="padding:3px 10px;" href="javascript:void(0);" class="btn-xs btn-type6 btnOut ml-5" data-idx="<?=$info["mm_idx"]?>" style="width:auto;">아웃소싱 승인처리</a>
						<?php endif; ?>
                    </td>
                    <th scope="col">사업자 인증 파일</th>
                    <td>
                    	<?php if($info["mm_comp_type"]=="OUT" && $info["mm_comp_auth"]!="") : ?>
                    	<?php
                    		//파일명 구하기
                    		$extension = pathinfo($info["mm_comp_auth"], PATHINFO_EXTENSION);
							$file_name = "사업자 등록증 파일_" . $info["mm_idx"] . "." . $extension;
                    	?>
                        <a style="text-decoration:underline;" href="/outer/download.php?name=<?=$file_name?>&url=<?=getcwd()?>/<?=$info["mm_comp_auth"]?>">첨부파일 다운로드</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                   <th scope="col">비밀번호</th>
                   <td>
                       <a href="javascript:void(0);" class="btn-xs btn-type10 ">비밀번호 초기화</a>
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
                             </label>
                         </div>
                     </td>
                     <th scope="col">생년월일</th>
                     <td>
                    	<div class="input-text ">
                             <label>
                                 <?=$info["mm_birth"]?>
                             </label>
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
                    <th scope="col">필요업무</th>
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
                      <th scope="col">희망성별</th>
                      <td colspan="3">
                         	<div class="input-text ">
                              <?=$info["mm_sex"]?>
                          </div>
                      </td>
                  </tr>
                  <tr>
                      <th scope="col">희망나이</th>
                      <td colspan="3">
                         	<div class="input-text ">
                              <label>
								<?php if($add["ma_sage"]!="0" && $add["ma_eage"]!="0") : ?>
                                  	<?=$add["ma_sage"]?>세 이상 ~ <?=$add["ma_eage"]?>세 이하
                                <?php elseif($add["ma_sage"]!="0" && $add["ma_eage"]=="0") : ?>
                                	<?=$add["ma_sage"]?>세 이상 ~
                                <?php elseif($add["ma_sage"]=="0" && $add["ma_eage"]!="0") : ?>
                                	~ <?=$add["ma_eage"]?>세 이하
                                <?php endif; ?>
                              </label>
                          </div>
                      </td>
                  </tr>
                  <tr>
                      <th scope="col">희망국적</th>
                      <td colspan="3">
                         	<div class="input-text ">
                              <?=$add["ma_nation"]?>
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
                      <th scope="col">복리후생</th>
                      <td colspan="3">
                          <div class="input-text ">
                              <?=$add["ma_life_type"]?>
                          </div>
                      </td>
                  </tr>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">채용공고 리스트(최근 10건)</h3>
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
	                <th scope="col">필요업무</th>
	                <th scope="col">지역</th>
					<th scope="col">급여형태</th>
	                <th scope="col">근무형태</th>
	                <th scope="col">등록일시</th>
	                <th scope="col">관리</th>
				</tr>
	            </thead>
            	<tbody>
            		<?php foreach($recruit_list as $row) : ?>
            		<tr>
            			<td><?=$row["rc_idx"]?></td>
            			<td><img src="<?=$row["rc_img1"]?>" style="height:50px;" /></td>
            			<td><?=$row["rc_name"]?></td>
            			<td><?=$row["rc_show_yn"]=="Y" ? "공개채용":"-"?></td>
            			<td><?=($row["rc_work"])?></td>
            			<td><?=$row["ad_addr"]?></td>
            			<td><?=$row["rc_price_type"]?></td>
            			<td><?=$row["rc_work_type"]?></td>
            			<td><?=$row["reg_dt"]?></td>
            			<td><a href="/admin/member/recruit_edit?idx=<?=$row["rc_idx"]?>" class="btn-xs btn-type3" style="width:auto;">채용공고 정보</a></td>
            		</tr>
            		<?php endforeach?>
            	</tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">포인트 리스트(최근 10건)</h3>
           	<table class="table-type2">
	            <colgroup>
	                <col width="50" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <col width="400" />
	                <col width="100" />
	                <col width="100" />
	            </colgroup>
	            <thead>
	            <tr>
	                <th scope="col">번호</th>
	                <th scope="col">가감 유형</th>
	                <th scope="col">가감 포인트</th>
	                <th scope="col">최종포인트</th>
	                <th scope="col">내용</th>
	                <th scope="col">등록일시</th>
	                <th scope="col">관리</th>
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
            			<td><a href="/admin/point/point_edit?ru_idx=<?=$row["pl_idx"]?>" class="btn-xs btn-type3" style="width:auto;">포인트 정보</a></td>
            		</tr>
            		<?php endforeach?>
            	</tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">결제 리스트(최근 10건)</h3>
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
					<th scope="col">회원명</th>
					<th scope="col">연락처</th>
					<th scope="col">회원 지역</th>
					<th scope="col">등록일시</th>
				</tr>
				</thead>
				<tbody>
					<?php foreach($fav_list as $row) : ?>
					<tr>
						<td><?=$row["bk_idx"]?></td>
						<td><?=$row["mm_id"]?></td>
						<td><?=$row["mm_name"]?></td>
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
            <?php if($info["mm_status"]!="SY" && $info["mm_status"]!="SN" && $info["mm_status"]!="SH") : ?>
                <button type="button" id="btnStopPopup" class="btn-l btn-type6 w150 ml-10">자격정지</button>
            <?php endif; ?>
            <?php if($info["mm_status"]=="SY" || $info["mm_status"]=="SN" || $info["mm_status"]=="SH") : ?>
                <button type="button" id="btnStopCancle" class="btn-l btn-type6 w150 ml-10">자격정지해제</button>
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
   $(document).ready(function(){

       $(".btnOut").click(function(){
       		var params = {};
			params["mm_idx"] = $(this).data("idx");
       		COMMON.confirm("아웃소싱 승인 처리 하시겠습니까?",function(){

		        comAjax("/admin/member/out_proc",params,function(result){
		            if(result.code == "00"){
		                COMMON.alert("승인처리 되었습니다.",function(){
		                    location.reload();
		                });
		            }else{
		                COMMON.alert(result.msg);
		            }
		        });
       		});
       });

       //리스트이동
	    $("#btnList").click(function(){
	        history.go(-1);
	    });

	    $("#btnDelete").click(function(){
			var idx = $("[name=mm_idx]").val();
			COMMON.confirm("삭제 처리 하시겠습니까?<br/>삭제시 동일(본인인증)정보로 재가입이 가능합니다.",function(){
        //"비노출처리 하시겠습니까?"
				var params = {};
				params["idx"] = idx;
				params["proc_mode"] = "delete";
        params["mm_phone"]  = '<?=$info["mm_phone"]?>';
        params["mm_id"]     = '<?=$info["mm_id"]?>';

		        comAjax("/admin/member/member_edit_proc",params,function(result){
		            if(result.code == "00"){
		                COMMON.alert(result.msg,function(){
		                    location.href="/admin/member/comp_list";
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
            params["mm_status"] = "<?php echo $info["mm_status"]; ?>";

            comAjax("/admin/member/member_edit_proc",params,function(result){
                if(result.code == "00"){
                    COMMON.alert(result.msg,function(){
                        //location.href="/admin/member/comp_list";
                        location.reload();
                    });
                }else{
                    COMMON.alert(result.msg);
                }
            });
    	});

      $("#btnStopCancle").click(function(){

        COMMON.confirm("자격정지를 해제하시겠습니까?",function(){
              var idx = $("[name=mm_idx]").val();
              var params = {};
              params["idx"] = idx;
              params["mm_stop_desc"] = $("#mm_stop_desc").val();
              params["proc_mode"] = "stopcancle";
              params["mm_status"] = "<?php echo $info["mm_status"]; ?>";

              comAjax("/admin/member/member_edit_proc",params,function(result){
                  if(result.code == "00"){
                      COMMON.alert(result.msg,function(){
                        location.reload();
                      });
                  }else{
                      COMMON.alert(result.msg);
                  }
              });

        });


    });


   });
</script>
