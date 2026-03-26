<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
        	<?php $tab_on = "tab2";?>
            <?php $tab_on2 = "menu1";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <style>
                  .tabbox{}
                  .tabbox ul {}
                  .tabbox ul li { list-style: none; font-size: 1.0em; border-radius: 5px 5px 0 0; width: fit-content; padding: 15px 35px; background-color: transparent; float: left; border-bottom: 0; cursor:pointer; color:#ccc; }
                  .tabbox ul li .tabtitle{}
                  .tabbox ul li .tabcnt{ margin-left: 15px; }
                  .tabbox ul li.tabon{ background-color: #fff; font-weight: 600; color: #000; border-bottom: 3px solid #57CCAF; }
                </style>
                <div class="tabbox">
                  <ul>
                      <li onclick="location.href='/compage/recruit_list_new?gbn=ing';" <?php if($_GET[gbn]=='ing'){ echo "class=\"tabon\""; }?> ><span class="tabtitle">게재중인 공고</span><span class="tabcnt"><?=$recruit_count[0]['ingcnt']?></span></li>
                      <li onclick="location.href='/compage/recruit_list_new?gbn=wait';" <?php if($_GET[gbn]=='wait'){ echo "class=\"tabon\""; }?> ><span class="tabtitle">대기중인 공고</span><span class="tabcnt"><?=$recruit_count[0]['waitcnt']?></span></li>
                      <li onclick="location.href='/compage/recruit_list_new?gbn=close';" <?php if($_GET[gbn]=='close'){ echo "class=\"tabon\""; }?> ><span class="tabtitle">마감된 공고</span><span class="tabcnt"><?=$recruit_count[0]['closecnt']?></span></li>
                      <li onclick="location.href='/compage/recruit_list_new?gbn=all';" <?php if($_GET[gbn]!='ing' && $_GET[gbn]!='wait' && $_GET[gbn]!='close'){ echo "class=\"tabon\""; }?> ><span class="tabtitle">전체공고</span><span class="tabcnt"><?=$recruit_count[0]['allcnt']?></span></li>
                  </ul>
                </div>
                <?php $currentDateTime = new DateTime(); ?>
                <div class="manage-my-content">
                    <form action="/">
                        <h3 class="sr-text">채용공고 관리</h3>
                        <div class="board-wrap">
                            <table class="board-list" style="border-top: 2px solid #ededed;">
                                <colgroup><col><col style="width:17%"><col><col><col><col></colgroup>
                                <tbody>
                                    <tr>
                                      <td style="font-weight: 600; background-color: #ededed;">공고</td>
                                      <td style="font-weight: 600; background-color: #ededed;">공개/비공개</td>
                                      <td style="font-weight: 600; background-color: #ededed;">공고기간</td>
                                      <td style="font-weight: 600; background-color: #ededed;">지원자 현황</td>
                                      <td style="font-weight: 600; background-color: #ededed;">유료서비스</td>
                                      <td style="font-weight: 600; background-color: #ededed;">관리</td>
                                    </tr>
                                    <?php $lines = 0; ?>
                                    <?php foreach($recruit_list as $row) : ?>
                                    <?php   $lines++; ?>
                                    <tr>
                                        <td class="selectable-item" style="min-width: 300px;">
                                                <div class="item-info colgroup">
                                                    <div class="thumb-wrap">
                                                        <!-- 썸네일 -->
                                                        <div class="thumb big click-item-new" data-link="/recruit/job_post_new/<?=$row["rc_idx"]?>">
                                                            <img src="<?=$row["rc_thumb"]?>" alt="">
                                                        </div>
                                                    </div>
                                                    <div class="content-wrap click-item-new" data-link="/recruit/job_post_new/<?=$row["rc_idx"]?>">
                                                      <div class="timestamp" style="font-size: 0.8em;">등록일 : <?=$row["reg_dt"]?></div>
                                                        <div class="inline-group">
                                                            <!-- 직종라벨 -->
                                                            <p class="category"><?=getWorkingImg($row["rc_work"],1)?></p>
                                                        </div>
                                                        <!-- <p class="title"><?=$row["compAuth"]?></p> -->
                                                        <p class="title" style="padding: 0;"><strong><?=$row["rc_name"]?></strong></p>

                                                        <!-- 추가조건 -->
                                                        <div class="meta" style="font-size: 0.8em;">
                                                            <!-- 근무시간 -->
                                                            <p><?=$row["rc_nation"]?></p>
                                                            <span><?=(($row["rc_nation"]!="" && ($row["rc_sage"]!="0" || $row["rc_eage"]!="0"))?" | ":"")?></span>
                                                            <p><?=($row["rc_sage"]=="0"?"":$row["rc_sage"]."대")?><?=(($row["rc_sage"]=="0" && $row["rc_eage"]=="0")?"":" ~ ")?><?=($row["rc_eage"]=="0"?"":$row["rc_eage"]."대")?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                        </td>
                                        <td style="width:200px;">
                                            <?php if($row["rc_show_yn"]=="Y") : ?>
                                            	<em class="important-label" style="position:relative;right:0; top:0; background-color: #1B9D7D; color: #fff; padding: 10px 15px; width: 80px; border-radius: 20px;">공&nbsp;&nbsp;개</em>
                                              <button onclick="fSetClose(<?=$row["rc_idx"]?>); return false;" style="background-color: #f2f2f2; color: #BEBFC9; padding: 7px 15px; width: 80px; border-radius: 20px; border: 1px solid #BEBFC9; margin-left: 5px;">비공개</button>
                                            <?php else : ?>
                                              <button onclick="fSetOpen(<?=$row["rc_idx"]?>); return false;" style="background-color: #f2f2f2; color: #BEBFC9; padding: 7px 15px; width: 80px; border-radius: 20px; border: 1px solid #BEBFC9; margin-left: 0px;">공&nbsp;&nbsp;개</button>
                                              <em class="disabled-label" style="position:relative;right:0; top:0; background-color: #1B9D7D; color: #fff; padding: 10px 15px; width: 80px; border-radius: 20px; margin-left: 0px;">비공개</em>
                                            <?php endif; ?>
                                        </td>
                                        <td class="regdate"><?=get_yyyymmdd($row["rc_sdate"])?> ~ <?=get_yyyymmdd($row["rc_edate"])?></td>
                                        <td style="line-height: 26px; text-align: left;">
                                            지원자 <a href="/compage/bookmark_person2" style="color: #57CCAF; text-decoration: underline; margin-left: 5px;"><?=$row["reqcnt"]?> 명</a><br>
                                            열&nbsp;&nbsp;&nbsp;&nbsp;람 <a href="/compage/bookmark_person2/view" style="color: #57CCAF; text-decoration: underline; margin-left: 5px;"><?=$row["viewcnt"]?> 명</a><br>
                                            미열람 <a href="/compage/bookmark_person2/noview" style="color: red <?php /* =(($row["reqcnt"]-$row["viewcnt"] > 0)?"red":"#57CCAF") */ ?>; text-decoration: underline; margin-left: 5px;"><?=($row["reqcnt"]-$row["viewcnt"])?> 명</a>
                                        </td>
                                        <td style="line-height: 26px;">
                                            <?php /* if($row["pm_edate"] != '' && $row["pm_edate"] < date("Y-m-d")){ ?>
                                            <?php   //echo "이용만료<br>"; ?>
                                            <a href="/compage/point_promotion_buy" style="border: 1px solid #ededed; padding: 5px 10px; margin-top: 10px; display: inline-block; width: fit-content;border-radius: 20px; background-color:#fff;">상품신청</a>
                                            <?php } */ ?>
                                            <?php //echo $row["pm_name"]." ".$row["pm_edate"]; ?>
                                            <?php //echo $row["pm_edate"]; ?>
                                            <?php $pmEdate = DateTime::createFromFormat('Y-m-d H:i:s', $row["pm_edate"]); ?>
                                            <?php if(!empty($row["pm_name"])){ ?>
                                              <?php if($pmEdate < $currentDateTime){
                                                echo "이용만료<br>"; ?>
                                                <a href="/compage/point_promotion_buy" style="border: 1px solid #ededed; padding: 5px 10px; margin-top: 10px; display: inline-block; width: fit-content;border-radius: 20px; background-color:#fff;">상품신청</a>
                                              <?php }else{ ?>
                                                <?=($row["pm_name"] != '' ? $row["pm_name"]."<br>" : "")?>
                                              <?php } ?>
                                            <?php }else{ ?>
                                              <a href="/compage/point_promotion_buy" style="border: 1px solid #ededed; padding: 5px 10px; margin-top: 10px; display: inline-block; width: fit-content;border-radius: 20px; background-color:#fff;">상품신청</a>
                                            <?php } ?>

                                            <?php /* if(!empty($row["pm_name"]) && $pmEdate < $currentDateTime){ ?>
                                              <?=($row["pm_name"] != '' ? $row["pm_name"]."<br>" : "")?>
                                            <?php }else{ ?>
                                              <a href="/compage/point_promotion_buy" style="border: 1px solid #ededed; padding: 5px 10px; margin-top: 10px; display: inline-block; width: fit-content;border-radius: 20px; background-color:#fff;">상품신청</a>
                                            <?php } */ ?>
                                        </td>
                                        <!-- <td class="status">진행중</td> -->
                                        <td class="action" style="line-height: 50px;">
                                          <a href="/compage/recruit_write_new/<?=$row["rc_idx"]?>" class="btn" style="border: 1px solid #ededed; font-weight: 400;border-radius: 20px; background-color:#fff;text-decoration:none;">수&nbsp;&nbsp;&nbsp;정</a><br>
                                          <?php if($row["rc_edate"] < date("Y-m-d")." 23:59:59"){ ?>
                                            <!-- <a href="#" onclick="return false;" class="btn" style="border: 1px solid #ededed; font-weight: 400;cursor: text;background-color:#ccc!important;border-radius: 20px; background-color:#fff; text-decoration:none;">마감</a><br> -->
                                            <a href="#" onclick="fSetRecruitDel(<?=$row["rc_idx"]?>); return false;" class="btn" style="border: 1px solid #ededed; font-weight: 400;border-radius: 20px; background-color:#fff; text-decoration:none;">삭&nbsp;&nbsp;&nbsp;제</a><br>
                                          <?php }else{ ?>
                                            <a href="#" onclick="fSetRecruitClose(<?=$row["rc_idx"]?>); return false;" class="btn" style="border: 1px solid #ededed; font-weight: 400;border-radius: 20px; background-color:#fff;text-decoration:none;">마&nbsp;&nbsp;&nbsp;감</a><br>
                                          <?php } ?>
                                          <a href="/compage/recruit_write_new/<?=$row["rc_idx"]?>?gbn=re" class="btn" style="border: 1px solid #ededed; font-weight: 400;padding: 0 26px;border-radius: 20px; background-color:#fff;text-decoration:none;">재등록</a>
                                            <!-- <a href="/compage/recruit_view/<?=$row["rc_idx"]?>" class="btn">상세보기</a> -->
                                            <!-- <a href="/compage/recruit_write/<?=$row["rc_idx"]?>" class="btn">재등록</a> -->
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if($lines == 0){ ?>
                                    <tr>
                                      <td colspan="6" style="padding: 50px;">공고 없음</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <p class="help-msg">채용공고 등록은 최대 20개까지만 가능합니다.<br> 채용공고기간이 2개월지날시 자동삭제됩니다.</p>

                        <div class="form-action-wrap align-right">
                            <!-- <button id="btnDefault" type="button" class="btn-round btn-tertiary">공개 채용공고로 설정</button> -->
                            <button id="btnWriteList" type="button" class="btn-round btn-tertiary" >채용공고 등록</button>
                        </div>

                    </form>
                </div>

            </div>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->


<script>
	$(document).ready(function(){
		//디폴트 설정
		$("#btnDefault").click(function(){
			var rc_idx_list = "";
			if($("[name='rc_show_yn[]']:checked").length ==0){
				commonAlert("공개 채용공고로 설정하실 공고를 선택해주세요.",function(){});
				return;
			}

			for(var icnt=0;icnt < $("[name='rc_show_yn[]']:checked").length;icnt++){
				if(rc_idx_list==""){
					rc_idx_list = $("[name='rc_show_yn[]']:checked").eq(icnt).val();
				}else{
					rc_idx_list += "," + $("[name='rc_show_yn[]']:checked").eq(icnt).val();
				}
			}

			openPopup('confirm', {
                content:'<p>공개 채용공고로 설정 하시겠습니까?</p>',
                firstButton: '취소',
                secondButton: '설정',
                secondCallback: function () {
                    var params = {};
					params["rc_idx_list"] = rc_idx_list;
					comAjax("/compage_proc/recruit_default",params,function(ret){
		                if(ret.code=="00"){
		                    location.reload();
		                }else{
		                    commonAlert(ret.msg,function(){});
		                    return;
		                }
		            });
                }
            });
		});


    $("#btnWriteList").click(function(){
      comAjax("/compage_proc/recruit_start",{},function(ret){
          if(ret.code=="00"){
              location.href="/compage/recruit_write_new";
          }else if(ret.code=="01"){
            openPopup('confirm', {
                  //20220929 수정
                  content:'<p>채용공고등록은 <em>1일 5건 무료</em>입니다<br> 추가로 공고를 등록하시려면<br> 포인트에서 차감됩니다.</p><p class="required-point">채용공고등록: <span>'+ret.data.use_point+'P</span>차감</p>',
                  secondButton:'유료등록',
                  secondCallback: function () {
                      comAjax("/compage_proc/recruit_start_point",{},function(ret2){
                    if(ret2.code=="00"){
                        location.href="/compage/recruit_write_new";
                    }else if(ret2.code=="01"){
                      commonAlert("보유하신 포인트가 부족합니다.(보유포인트 : "+ret2.data.mm_point.format()+"P)<br/>포인트 구매후 등록부탁드립니다.",function(){});
                    }else{
                        commonAlert(ret2.msg,function(){});
                        return;
                    }
                });
                  }
              });
          }else{
              commonAlert(ret.msg,function(){});
              return;
          }
      });
    });


	});//ready

  function fSetOpen(rc_idx){
    openPopup('confirm', {
          content:'<p>공개 채용공고로 설정 하시겠습니까?</p>',
          firstButton: '취소',
          secondButton: '설정',
          secondCallback: function () {
              var params = {};
              params["rc_idx_list"] = rc_idx;
              comAjax("/compage_proc/recruit_open",params,function(ret){
              if(ret.code=="00"){
                  location.reload();
              }else{
                  commonAlert(ret.msg,function(){});
                  return;
              }
          });
          }
      });

  }

  function fSetClose(rc_idx){
    openPopup('confirm', {
          content:'<p>비공개 채용공고로 설정 하시겠습니까?</p>',
          firstButton: '취소',
          secondButton: '설정',
          secondCallback: function () {
              var params = {};
              params["rc_idx_list"] = rc_idx;
              comAjax("/compage_proc/recruit_close",params,function(ret){
              if(ret.code=="00"){
                  location.reload();
              }else{
                  commonAlert(ret.msg,function(){});
                  return;
              }
          });
          }
      });

  }


  function fSetRecruitClose(rc_idx){
    openPopup('confirm', {
          content:'<p>마감 처리 하시겠습니까?</p>',
          firstButton: '취소',
          secondButton: '마감',
          secondCallback: function () {
              var params = {};
              params["rc_idx_list"] = rc_idx;
              comAjax("/compage_proc/recruit_end",params,function(ret){
              if(ret.code=="00"){
                  location.reload();
              }else{
                  commonAlert(ret.msg,function(){});
                  return;
              }
          });
          }
      });

  }

  function fSetRecruitDel(rc_idx){
    openPopup('confirm', {
          content:'<p>삭제 처리 하시겠습니까?</p>',
          firstButton: '취소',
          secondButton: '삭제',
          secondCallback: function () {
              var params = {};
              params["rc_idx_list"] = rc_idx;
              console.log(params);
              comAjax("/compage_proc/recruit_del",params,function(ret){
              if(ret.code=="00"){
                  commonAlert("삭제되었습니다.",function(){});
                  location.reload();
              }else{
                  commonAlert(ret.msg,function(){});
                  return;
              }
          });
          }
      });

  }

</script>
