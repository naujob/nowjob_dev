<form id="frm" class="controls" method="post">
    <input type="hidden" name="ru_idx" value="<?=getData($info,"ru_idx")?>" />
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
                            <?=$mem["mm_id"]?>
                        </div>
                    </td>
                    <th scope="col">회원명</th>
                    <td>
                       	<div class="input-text ">
                            <label>
                                <?=$mem["mm_name"]?><?=$mem["mm_type"]=="COMP" ? "(".$mem["mm_comp_name"].")":""?>
                            </label>
                        </div>
                    </td>
                 </tr>
                 <tr>
                    <th scope="col">휴대폰번호<em class="ess"></em></th>
                   	<td class="inline-wrap">
					     <div class="input-text  mt-5">
                            <label>
                            	<?=$mem["mm_phone"]?>
                            </label>
                        </div>
					</td>
                    <th scope="col">보유포인트</th>
                    <td>
                       	 <div class="input-text  mt-5">
                            <label>
                                <?=number_format($mem["mm_point"])?>P
                            </label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">채용공고 정보</h3>
            <table class="table-form">
                <colgroup>
                    <col width="180px"/>
                    <col width="600px"/>
                    <col width="180px"/>
                    <col width="600px"/>
                </colgroup>
                <tbody>
               <tr>
                    <th scope="col">이력서 이미지</th>
                    <td colspan="3">
                    	<div class="img-cont">
	                       	<?php if($info["ru_img1"]!="") : ?>
			                <a class="item-img" href="<?=$info["ru_img1"]?>">
			                    <img src="<?=$info["ru_img1"]?>" alt="">
			                </a>
			                <?php endif; ?>
			                <?php if($info["ru_img2"]!="") : ?>
			                <a class="item-img" href="<?=$info["ru_img2"]?>">
			                    <img src="<?=$info["ru_img2"]?>" alt="" >
			                </a>
			                <?php endif; ?>
			                <?php if($info["ru_img3"]!="") : ?>
			                <a class="item-img" href="<?=$info["ru_img3"]?>">
			                    <img src="<?=$info["ru_img3"]?>" alt="">
			                </a>
			                <?php endif; ?>
			                <?php if($info["ru_img4"]!="") : ?>
			                <a class="item-img" href="<?=$info["ru_img4"]?>">
			                    <img src="<?=$info["ru_img4"]?>" alt="">
			                </a>
			                <?php endif; ?>
			                <?php if($info["ru_img5"]!="") : ?>
			                <a class="item-img" href="<?=$info["ru_img5"]?>">
			                    <img src="<?=$info["ru_img5"]?>" alt="">
			                </a>
			                <?php endif; ?>
		                </div>
                    </td>
                </tr>
                <tr>
                 	<th scope="col">노출여부</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=getResumeStatus($info["ru_show_yn"])?>
                        </div>
                    </td>
                 </tr>
                <tr>
                	 <th scope="col">이력서명</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["ru_name"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">업무구분</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["ru_work"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	 <th scope="col">지역</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["ad_addr"]?>
                        </div>
                    </td>
                 </tr>
                  <tr>
                	<th scope="col">급여형태</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["ru_price_type"]?>
                        </div>
                    </td>
                 </tr>
                  <tr>
                	<th scope="col">근무형태</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["ru_work_type"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">근무요일</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<ul class="working-day">
                                <?=($info["ru_week"])?>
                            </ul>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">근무시작일</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=substr($info["ru_work_dt"],0,10)?>
                        </div>
                    </td>
                 </tr>
                  <tr>
                	<th scope="col">최종학력</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["ru_edu"]?>
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
                               <td><?=$row["rc_type"]?></td>
                               <td><?=$row["rc_name"]?></td>
                               <?php
                                   $is_date = false;
                                   $sdate = "";
                                   $edate = "";
                                   $arr_date = explode("~",$row["rc_date"]);
                                   if(count($arr_date) > 1){
                                       $is_date = true;
                                       $sdate = str_replace(".","-",trim($arr_date[0])) . "-01";
                                       $edate = str_replace(".","-",trim($arr_date[1])) . "-01";
                                   }
                               ?>
                               <td><?=$row["rc_date"]?><?=$is_date ? "(" . get_date_diff_txt($sdate,$edate) . ")" : ""?></td>
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
                               <td><?=$row["rc_name"]?></td>
                               <td><?=$row["rc_dt"]?></td>
                           </tr>
                           <?php endforeach; ?>
                            </table>
                       </td>
                   </tr>

                 <tr>
                	 <th scope="col">나의강점</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["ru_my_type"]?>
                        </div>
                    </td>
                 </tr>

                 <tr>
                	 <th scope="col">자기소개</th>
                    <td colspan="3">
                        <div class="input-text" style="">
                          <textarea id="ru_desc" name="ru_desc" maxlength="500" class="form-textarea js-autoheight" style="height:200px;"><?=$info["ru_desc"]?></textarea>
                          <?php /* str_replace("\n","<br/>",$info["ru_desc"])?> */ ?>
                        </div>
                    </td>
                 </tr>

                 <tr>
                     <th scope="col">게시글 상태</th>
                    <td colspan="3">
                        <div class="input-text ">
                            <?php if($info["ru_status"]=="Y") : ?>
                                정상
                            <?php elseif($info["ru_status"]=="H") : ?>
                                관리자 비공개
                            <?php endif; ?>
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
            <h3 class="sub-title mb-10">지원공고 리스트(최근 10건)</h3>
           	<table class="table-type2">
	            <colgroup>
	                <col width="50" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <!--col width="100" /-->
	                <col width="100" />
	            </colgroup>
	            <thead>
	            <tr>
	                <th scope="col">번호</th>
	                <th scope="col">아이디</th>
	                <th scope="col">상호명</th>
	                <th scope="col">연락처</th>
	                <th scope="col">채용공고명</th>
	                <!--th scope="col">지원상태</th-->
	                <th scope="col">등록일시</th>
				</tr>
	            </thead>
            	<tbody>
            		<?php foreach($recruit_list as $row) : ?>
            		<tr>
            			<td><?=$row["mq_idx"]?></td>
            			<td><?=$row["mm_id"]?></td>
            			<td><?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(".$row["mm_comp_name"].")":""?></td>
            			<td><?=$row["mm_phone"]?></td>
            			<td><a style="text-decoration:underline;" href="/admin/member/recruit_edit?idx=<?=$row["rc_idx"]?>"><?=$row["rc_name"]?></a></td>
            			<!--td><?=$row["req_status"]?></td-->
            			<td><?=$row["req_reg_dt"]?></td>
            		</tr>
            		<?php endforeach?>
            	</tbody>
            </table>
        </div>
        <div class="section">
            <h3 class="sub-title mb-10">제안받은 채용공고 리스트(최근 10건)</h3>
           	<table class="table-type2">
	            <colgroup>
	                <col width="50" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <col width="100" />
	                <!--col width="100" /-->
	                <col width="100" />
	            </colgroup>
	            <thead>
	            <tr>
	                <th scope="col">번호</th>
	                <th scope="col">아이디</th>
	                <th scope="col">상호명</th>
	                <th scope="col">연락처</th>
	                <th scope="col">채용공고명</th>
	                <!--th scope="col">지원상태</th-->
	                <th scope="col">등록일시</th>
				</tr>
	            </thead>
            	<tbody>
            		<?php foreach($request_list as $row) : ?>
            		<tr>
            			<td><?=$row["mq_idx"]?></td>
            			<td><?=$row["mm_id"]?></td>
            			<td><?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(".$row["mm_comp_name"].")":""?></td>
            			<td><?=$row["mm_phone"]?></td>
            			<td><a style="text-decoration:underline;" href="/admin/member/recruit_edit?idx=<?=$row["rc_idx"]?>"><?=$row["rc_name"]?></a></td>
            			<!--td><?=$row["req_status"]?></td-->
            			<td><?=$row["req_reg_dt"]?></td>
            		</tr>
            		<?php endforeach?>
            	</tbody>
            </table>
        </div>

         <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnSave" class="btn-l btn-type2 w150">수정</button>
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <?php if($info["ru_status"]!="HY" && $info["ru_status"]!="HN") : ?>
              <?php //echo "[".$info["ru_status"]."]"; ?>
                <button type="button" id="btnStopPopup" class="btn-l btn-type6 w150 ml-10">관리자 비공개</button>
            <?php endif; ?>
            <?php if($info["ru_status"]=="HY" || $info["ru_status"]=="HN") : ?>
                <button type="button" id="btnStopCancle" class="btn-l btn-type6 w180 ml-10">관리자 비공개 해제</button>
            <?php endif; ?>
            <button type="button" id="btnDel" class="btn-l btn-type2 w150">삭제</button>
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

    		var idx = $("[name=ru_idx]").val();
            var params = {};
            params["idx"] = idx;
            params["stop_desc"] = $("#stop_desc").val();
            params["proc_mode"] = "stop";
            params["ru_status"] = "<?php echo $info["ru_status"]; ?>";
            //console.log(params);
            //return;
            //todo
            comAjax("/admin/member/resume_edit_proc",params,function(result){
                if(result.code == "00"){
                    COMMON.alert(result.msg,function(){
                        //location.href="/admin/member/resume_list";
                        location.reload();
                    });
                }else{
                    COMMON.alert(result.msg);
                }
            });
    	});

      $("#btnStopCancle").click(function(){

        COMMON.confirm("관리자 비공개를 해제 하시겠습니까?",function(){

            var idx = $("[name=ru_idx]").val();
            var params = {};
            params["idx"] = idx;
            params["stop_desc"] = $("#stop_desc").val();
            params["proc_mode"] = "stopcancle";
            params["ru_status"] = "<?php echo $info["ru_status"]; ?>";
            //console.log(params);
            //return;
            //todo
            comAjax("/admin/member/resume_edit_proc",params,function(result){
                if(result.code == "00"){
                    COMMON.alert(result.msg,function(){
                        //location.href="/admin/member/resume_list";
                        location.reload();
                    });
                }else{
                    COMMON.alert(result.msg);
                }
            });

        },function(){

        });

    });

      $(document).ready(function(){

        $("#btnSave").click(function(){

      			COMMON.confirm("수정 하시겠습니까?",function(){

                var form = $('#frm');
                var params = new FormData(form[0]);
                params.set("ru_idx","<?=getData($info,"ru_idx")?>");
                params.set("ru_desc", $('#ru_desc').val());
                fileAjaxLoading("/admin/member/resume_write",params,function(ret){
                    if(ret.code=="00"){
                        location.reload();
                    }else{
                        commonAlert(ret.msg,function(){});
                        return;
                    }
                });

      			},function(){

      			});

        });//btnSave

        $("#btnDel").click(function(){

            COMMON.confirm("삭제 하시겠습니까?",function(){

                var form = $('#frm');
                var params = new FormData(form[0]);
                params.set("ru_idx","<?=getData($info,"ru_idx")?>");
                fileAjaxLoading("/admin/member/resume_delete",params,function(ret){

                    if(ret.code=="00"){
                        COMMON.alert("삭제되었습니다.",function(){
                          location.href="/admin/member/resume_list";
                        });
                    }else{
                        commonAlert(ret.msg,function(){});
                        return;
                    }
                });

            },function(){

            });

        });//btnDel


      });//ready

</script>
