<form id="frm" class="controls" method="post">
    <input type="hidden" name="td_idx" value="<?=getData($info,"td_idx")?>" />
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
                    <th scope="col">회원명(사업자명)</th>
                    <td>
                       	<div class="input-text ">
                            <label>
                                <?=$info["mm_name"]?><?=$info["mm_type"]=="COMP" ? "(".$info["mm_comp_name"].")":""?>
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
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">상품정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="180px"/>
                    <col width="600px"/>
                    <col width="180px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
               <tr>
                    <th scope="col">판매 이미지</th>
                    <td colspan="3">
                    	<div class="img-cont">
	                       	<?php if($info["td_img1"]!="") : ?>
			                <a class="item-img" href="<?=$info["td_img1"]?>">
			                    <img src="<?=$info["td_img1"]?>" alt="">
			                </a>
			                <?php endif; ?>
			                <?php if($info["td_img2"]!="") : ?>
			                <a class="item-img btnPreview" href="<?=$info["td_img2"]?>">
			                    <img src="<?=$info["td_img2"]?>" alt="" >
			                </a>
			                <?php endif; ?>
			                <?php if($info["td_img3"]!="") : ?>
			                <a class="item-img btnPreview" href="<?=$info["td_img3"]?>">
			                    <img src="<?=$info["td_img3"]?>" alt="">
			                </a>
			                <?php endif; ?>
			                <?php if($info["td_img4"]!="") : ?>
			                <a class="item-img btnPreview" href="<?=$info["td_img4"]?>">
			                    <img src="<?=$info["td_img4"]?>" alt="">
			                </a>
			                <?php endif; ?>
			                <?php if($info["td_img5"]!="") : ?>
			                <a class="item-img btnPreview" href="<?=$info["td_img5"]?>">
			                    <img src="<?=$info["td_img5"]?>" alt="">
			                </a>
			                <?php endif; ?>
		                </div>
                    </td>
                </tr>
                <tr>
                    <th scope="col">상품명</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["td_name"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                    <th scope="col">판매금액</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=number_format($info["td_price"])?>원
                        </div>
                    </td>
                 </tr>
                 <tr>
                    <th scope="col">카테고리</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["td_category"]?>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <th scope="col">해시태그</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["td_hash"]?>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <th scope="col">상세설명</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["td_desc"]?>
                        </div>
                    </td>
                  </tr>
                  <tr>
                      <th scope="col">게시글 상태</th>
                     <td colspan="3">
                         <div class="input-text ">
                             <?=getRecruitUseYn($info["td_status"]);?>
                         </div>
                     </td>
                  </tr>
                  <?php if($info["stop_dt"]!="0000-00-00 00:00:00") : ?>
                  <tr>
                      <th scope="col">비공개 처리일시</th>
                      <td colspan="3">
                          <div class="input-text ">
                              <?=$info["stop_dt"]?>
                          </div>
                      </td>
                  </tr>
                  <tr>
                      <th scope="col">처리사유</th>
                      <td colspan="3">
                          <div class="input-text ">
                              <?=inputTextArea("stop_desc","stop_desc",$info["stop_desc"],"","style='width:100%;height:60px;padding:10px;' readonly='true' ","")?>
                          </div>
                      </td>
                  </tr>
                  <?php endif; ?>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">채팅거래 클릭 리스트(최근 10건)</h3>
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
	                <th scope="col">회원명(상호명)</th>
	                <th scope="col">연락처</th>
	                <th scope="col">회원 지역</th>
	                <th scope="col">등록일시</th>
				</tr>
	            </thead>
            	<tbody>
            		<?php foreach($chat_list as $row) : ?>
            		<tr>
            			<td><?=$row["rm_idx"]?></td>
            			<td><?=$row["mm_id"]?></td>
            			<td><?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(".$row["mm_comp_name"].")":""?></td>
            			<td><?=$row["mm_phone"]?></td>
            			<td><?=$row["mm_ad_addr"]?></td>
            			<td><?=$row["rm_reg_dt"]?></td>
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
	                <th scope="col">회원명(상호명)</th>
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
            			<td><?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(".$row["mm_comp_name"].")":""?></td>
            			<td><?=$row["mm_phone"]?></td>
            			<td><?=$row["mm_ad_addr"]?></td>
            			<td><?=$row["bk_reg_dt"]?></td>
            		</tr>
            		<?php endforeach?>
            	</tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <?php if($info["td_status"]!="H") : ?>
                <button type="button" id="btnStopPopup" class="btn-l btn-type6 w150 ml-10">관리자 비공개</button>
            <?php endif; ?>
        </div>
    </div>
</form>


<div class="popup popup-stop popStop">
   <div class="ds-table">
       <div class="ds-table-cell">
           <div class="container w700">
               <div class="header">
                   <h2>게시글 비공개</h2>
                   <button type="button" class="btn-close" onclick="popClose('.popup-stop');">닫기</button>
               </div>
               <div class="contents">
                   <div class="info mb-20">
                       <h3 class="bold mb-10">게시글을 비공개 처리 하시겠습니까?</h3>

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
                               <?=inputTextArea("stop_desc","stop_desc","","","style='width:100%;height:100px;padding:10px;'","100")?>
                           </td>
                       </tr>
                       </tbody>
                   </table>
               </div>
               <div class="btn-wrap inline-wrap tc">
                   <button type="button" class="btn-m btn-type1" onclick="popClose('.popup-stop');">닫기</button>
                   <button type="button" class="btn-m btn-type6 btnStop" id="btnStop">비공개 처리</button>
               </div>
           </div>
       </div>
   </div>
</div>

<script>
	 $(".img-cont").magnificPopup({
		  delegate: 'a',
		  type: 'image',
		  gallery: {
	      enabled: true
	    },
	});

	//리스트이동
    $("#btnList").click(function(){
        history.go(-1);
    });

	$("#btnDelete").click(function(){
		var idx = $("[name=td_idx]").val();
		COMMON.confirm("비노출처리 하시겠습니까?",function(){
			var params = {};
			params["idx"] = idx;
			params["proc_mode"] = "delete";
	        comAjax("/admin/trade/trade_edit_proc",params,function(result){
	            if(result.code == "00"){
	                COMMON.alert(result.msg,function(){
	                    location.href="/admin/trade/trade_list";
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
        if($("#stop_desc").val()==""){
            COMMON.alert("사유를 입력해주세요.",function(){
                $("#stop_desc").focus();
            });
            return;
        }

		var idx = $("[name=td_idx]").val();
        var params = {};
        params["idx"] = idx;
        params["stop_desc"] = $("#stop_desc").val();
        params["proc_mode"] = "stop";
        comAjax("/admin/trade/trade_edit_proc",params,function(result){
            if(result.code == "00"){
                COMMON.alert(result.msg,function(){
                    location.href="/admin/trade/trade_list";
                });
            }else{
                COMMON.alert(result.msg);
            }
        });
	});
</script>
