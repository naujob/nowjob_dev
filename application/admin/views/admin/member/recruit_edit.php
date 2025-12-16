<script type="text/javascript" src="/resource/shop/web/smarteditor2/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<form id="frm" class="controls" method="post">
    <input type="hidden" name="rc_idx" value="<?=getData($info,"rc_idx")?>" />
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
                    <th scope="col">회원명(사업자명)</th>
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
                    <th scope="col">채용공고 이미지</th>
                    <td colspan="3">
                    	<div class="img-cont">
	                       	<?php if($info["rc_img1"]!="") : ?>
			                <a class="item-img" href="<?=$info["rc_img1"]?>">
			                    <img src="<?=$info["rc_img1"]?>" alt="">
			                </a>
			                <?php endif; ?>
			                <?php if($info["rc_img2"]!="") : ?>
			                <a class="item-img" href="<?=$info["rc_img2"]?>">
			                    <img src="<?=$info["rc_img2"]?>" alt="" >
			                </a>
			                <?php endif; ?>
			                <?php if($info["rc_img3"]!="") : ?>
			                <a class="item-img" href="<?=$info["rc_img3"]?>">
			                    <img src="<?=$info["rc_img3"]?>" alt="">
			                </a>
			                <?php endif; ?>
			                <?php if($info["rc_img4"]!="") : ?>
			                <a class="item-img" href="<?=$info["rc_img4"]?>">
			                    <img src="<?=$info["rc_img4"]?>" alt="">
			                </a>
			                <?php endif; ?>
			                <?php if($info["rc_img5"]!="") : ?>
			                <a class="item-img" href="<?=$info["rc_img5"]?>">
			                    <img src="<?=$info["rc_img5"]?>" alt="">
			                </a>
			                <?php endif; ?>
		                </div>
                    </td>
                </tr>
                <tr>
                 	<th scope="col">노출여부</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=getRecruitStatus($info["rc_show_yn"])?>
                        </div>
                    </td>
                 </tr>
                <tr>
                	 <th scope="col">공고명</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["rc_name"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	 <th scope="col">노출기간</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=substr($info["rc_sdate"],0,10)?> ~ <?=substr($info["rc_edate"],0,10)?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">업무구분</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["rc_work"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">급여</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["rc_price_type"]?>
                       		<?php if(strpos($info["rc_price_type"],"주급")!==false) : ?>
                            <span class="pay">
                                <em class="amount">회사내규를 따름</em>
                            </span>
                            <?php endif; ?>
                            <?php if(strpos($info["rc_price_type"],"일급")!==false && strpos($info["rc_price_type"],"주급")===false) :  ?>
                            	<span class="pay">
                                    <em class="amount"><?=number_format($info["rc_day_price"])?></em>
                                    <span>(일급)</span>
                                </span>
                            <?php endif; ?>
                            <?php if(strpos($info["rc_price_type"],"일급")!==false && strpos($info["rc_price_type"],"주급")===false) :  ?>
                            	<span class="pay">
                                    <em class="amount"><?=number_format($info["rc_month_price"])?></em>
                                    <span>(월급)</span>
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">근무형태</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["rc_work_type"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">근무시간</th>
                    <td colspan="3">
                        <?php if(strpos($info["rc_work_type"],"2교대")!==false) : ?>
	                        <span class="time"><?=$info["rc_am_stime"]?>~<?=$info["rc_am_stime"]?>(주간)</span>
	                        <span class="time"><?=$info["rc_pm_stime"]?>~<?=$info["rc_pm_stime"]?>(야간)</span>
                        <?php endif; ?>
                        <?php if(strpos($info["rc_work_type"],"주간")!==false && strpos($info["rc_work_type"],"2교대")===false) :  ?>
                        	<span class="time"><?=$info["rc_am_stime"]?>~<?=$info["rc_am_stime"]?>(주간)</span>
                        <?php endif; ?>
                        <?php if(strpos($info["rc_work_type"],"야간")!==false && strpos($info["rc_work_type"],"2교대")===false) :  ?>
                        	<span class="time"><?=$info["rc_pm_stime"]?>~<?=$info["rc_pm_stime"]?>(야간)</span>
                        <?php endif; ?>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">희망국적</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["rc_nation"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">희망성별</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["rc_sex"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">희망나이</th>
                    <td colspan="3">
                        <div class="input-text ">

                       		<?php if($info["rc_sage"]!="0" && $info["rc_eage"]!="0") : ?>
                              	<?=$info["rc_sage"]?>세 이상 ~ <?=$info["rc_eage"]?>세 이하
                            <?php elseif($info["rc_sage"]!="0" && $info["rc_eage"]=="0") : ?>
                            	<?=$info["rc_sage"]?>세 이상 ~
                            <?php elseif($info["rc_sage"]=="0" && $info["rc_eage"]!="0") : ?>
                            	~ <?=$info["rc_eage"]?>세 이하
                            <?php endif; ?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	<th scope="col">근무요일</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<ul class="working-day">
                                <?=($info["rc_week"])?>
                            </ul>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	 <th scope="col">주소</th>
                    <td colspan="3">
                        <div class="input-text ">
                        	[<?=$info["ad_addr"]?>]<?=$info["ad_doro"]?> <?=$info["ad_detail"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	 <th scope="col">해시태그</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["rc_hash"]?>
                        </div>
                    </td>
                 </tr>

                 <tr>
                	 <th scope="col">복리후생</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["rc_life_type"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	 <th scope="col">상세업무정보</th>
                    <td colspan="3">
                        <div class="input-text" style="clear:both;">
                          <textarea id="rc_desc" name="rc_desc" maxlength="500" class="form-textarea js-autoheight" style="height:300px;"><?=$info["rc_desc"]?></textarea>
                          <?php /* str_replace("\n","<br/>",$info["rc_desc"]) */ ?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                	 <th scope="col">작성일시</th>
                    <td colspan="3">
                        <div class="input-text ">
                       		<?=$info["reg_dt"]?>
                        </div>
                    </td>
                 </tr>
                 <tr>
                     <th scope="col">게시글 상태</th>
                    <td colspan="3">
                        <div class="input-text ">
                            <?php if($info["rc_status"]=="Y") : ?>
                                정상
                            <?php elseif($info["rc_status"]=="H") : ?>
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
            <h3 class="sub-title mb-10">지원 이력서 리스트(최근 10건)</h3>
           	<table class="table-type2">
	            <colgroup>
	                <col width="50" />
	                <col width="100" />
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
	                <th scope="col">이력서명</th>
	                <th scope="col">지원상태</th>
	                <th scope="col">등록일시</th>
				</tr>
	            </thead>
            	<tbody>
            		<?php foreach($resume_list as $row) : ?>
            		<tr>
            			<td><?=$row["mq_idx"]?></td>
            			<td><?=$row["mm_id"]?></td>
            			<td><?=$row["mm_name"]?><?=$row["mm_type"]=="COMP" ? "(".$row["mm_comp_name"].")":""?></td>
            			<td><?=$row["mm_phone"]?></td>
            			<td><a style="text-decoration:underline;" href="/admin/member/resume_edit?idx=<?=$row["ru_idx"]?>"><?=$row["ru_name"]?></a></td>
            			<td><?=$row["mq_status"]?></td>
            			<td><?=$row["mq_reg_dt"]?></td>
            		</tr>
            		<?php endforeach?>
            	</tbody>
            </table>
        </div>

        <div class="btn-wrap inline-wrap mt-40 tc">
            <button type="button" id="btnSave" class="btn-l btn-type2 w150">수정</button>
            <button type="button" id="btnList" class="btn-l btn-type2 w150">목록으로</button>
            <?php if($info["rc_status"]!="H") : ?>
                <button type="button" id="btnStopPopup" class="btn-l btn-type6 w150 ml-10">관리자 비공개</button>
            <?php elseif($info["rc_status"]=="H") : ?>
                <button type="button" id="btnStopRelease" class="btn-l btn-type6 w150 ml-10">비공개 해제</button>
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

		var idx = $("[name=rc_idx]").val();
        var params = {};
        params["idx"] = idx;
        params["stop_desc"] = $("#stop_desc").val();
        params["proc_mode"] = "stop";
        comAjax("/admin/member/recruit_edit_proc",params,function(result){
            if(result.code == "00"){
                COMMON.alert(result.msg,function(){
                    location.href="/admin/member/recruit_list";
                });
            }else{
                COMMON.alert(result.msg);
            }
        });
	});

  $(document).ready(function(){
      var oEditors = [];
      nhn.husky.EZCreator.createInIFrame({
       oAppRef: oEditors,
       elPlaceHolder: "rc_desc",
       sSkinURI: "/resource/shop/web/smarteditor2/SmartEditor2Skin_ko_KR.html",
       fCreator: "createSEditor2"
      });


      $("#btnSave").click(function(){

    			COMMON.confirm("수정 하시겠습니까?",function(){

              oEditors.getById["rc_desc"].exec("UPDATE_CONTENTS_FIELD", []);

              var form = $('#frm');
              var params = new FormData(form[0]);
              params.set("rc_idx","<?=getData($info,"rc_idx")?>");
              params.set("rc_desc", $('#rc_desc').val());
              fileAjaxLoading("/admin/member/recruit_write",params,function(ret){
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
              params.set("rc_idx","<?=getData($info,"rc_idx")?>");
              fileAjaxLoading("/admin/member/recruit_delete",params,function(ret){

                  if(ret.code=="00"){
                      COMMON.alert("삭제되었습니다.",function(){
                        location.href="/admin/member/recruit_list";
                      });
                  }else{
                      commonAlert(ret.msg,function(){});
                      return;
                  }
              });

    			},function(){

    			});

      });//btnDel

      $("#btnStopRelease").click(function(){

    			COMMON.confirm("관리자 비공개를 해제 하시겠습니까?",function(){

              var params = {};
              params["idx"] = '<?=getData($info,"rc_idx")?>';
              params["proc_mode"] = "stopcancle";
              comAjax("/admin/member/recruit_edit_proc",params,function(ret){
                  if(ret.code=="00"){
                      COMMON.alert("공개로 전환되었습니다.",function(){
                          location.reload();
                      });
                  }else{
                      commonAlert(ret.msg,function(){});
                      return;
                  }
              });

    			},function(){

    			});

      });//btnStopRelease

  });//ready

</script>
