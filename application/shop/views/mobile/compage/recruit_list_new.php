<div class="page page-mypage">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
        <a href="javascript:void(0);" id="btnRecruit" class="btn-action js-add-new">채용공고등록</a>
    </div><!-- //top-bar -->

    <div class="page-content">
        <form id="frm" onsubmit="return false" autocomplete="false">
            <div class="post-list">
                <ul class="list">
                	<?php foreach($recruit_list as $row) : ?>
                    <li>
                        <div class="selectable-item">
                            <label class="form-checkbox">
                                <input type="checkbox" name="rc_show_yn[]" value="<?=$row["rc_idx"]?>" data-rc_name="<?=$row["rc_name"]?>" <?=$row["rc_show_yn"]=="Y" ? "checked":""?> >
                                <span class="label"></span>
                                <span class="sr-text">선택</span>
                            </label>
                            <a href="/compage/recruit_view_new/<?=$row["rc_idx"]?>" class="angle-link">
                                <strong style="width: calc(100% - 40px); display: inline-block;"><?=$row["rc_name"]?></strong>
                                <span class="date"><?=get_yyyymmdd($row["rc_sdate"])?> ~ <?=get_yyyymmdd($row["rc_edate"])?></span>
                            </a>
                            <a href="/compage/applicant_list?rcidx=<?=$row["rc_idx"]?>" style="position: absolute; right: 10%; width: 32px; top: 41%; font-size: 0.8rem;"><img src="/resource/shop/mobile/img/user_gray.png" style="width:16px;"> <?=$row["reqcnt"]?></a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                    <?php if(count($recruit_list)==0) : ?>
                    <div class="is-empty">
		                <p>등록된 채용공고가 없습니다.</p>
		            </div>
		            <?php endif; ?>
                </ul>
            </div>
            <?php if(count($recruit_list) > 0) : ?>
            <div class="container">
                <p class="help-msg">채용공고 등록은 최대 10개까지만 가능합니다.<br> 채용공고기간이 3개월지날시 자동삭제됩니다.</p>
            </div>
            <?php endif; ?>
            <div class="sticky-bottom">
                <button id="btnDefault" type="button" class="btn btn-primary">공개 채용공고로 설정</button>
            </div>
        </form>
    </div><!-- //page-content -->
</div>

<script>
  window.addEventListener('DOMContentLoaded', function() {  
	//$(document).ready(function(){
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

		//처리
		$("#btnRecruit").click(function(){
            comAjax("/compage_proc/recruit_start",{},function(ret){
              console.log(ret);
                if((ret.code=="00" || ret.code=="01") && (ret.data.comAuthStat == "N" || ret.data.passAuthStat == "N") ){
                    if(ret.data.comAuthStat == "N" && ret.data.passAuthStat == "N"){
                        openPopup('modal', {
                    				content: '<div style="padding: 50px;">인증 후에 공고를 등록 하실 수 있습니다.</div>',
            								firstButton: '<div style="width: 100%; height: 56px; line-height: 56px; background: #57CCAF;">기업정보 인증</div>',
            								secondButton: '<div style="width: 100%; height: 56px; line-height: 56px; background: #57CCAF;">PASS 인증</div>',
            								firstCallback: function () {
            										location.href="/compage/index?gbn=compcert";
            								},
            								secondCallback: function () {
            										location.href="/compage/index?gbn=pass";
            								}
                    		});

                    }else if(ret.data.comAuthStat == "N" && ret.data.passAuthStat == "Y"){
                        openPopup('modal', {
                            content: '<div style="padding: 50px;">인증 후에 공고를 등록 하실 수 있습니다.</div>',
                            firstButton: '<div style="width: 100%; height: 56px; line-height: 56px; background: #57CCAF;">기업정보 인증</div>',
                            secondButton: '<div style="width: 100%; height: 56px; line-height: 56px; cursor: no-drop; background: #BEBFC9;" onclick="return false;">PASS 인증</div>',
                            firstCallback: function () {
                                location.href="/compage/index?gbn=compcert";
                            },
                            secondCallback: function () {
                            }
                        });

                    }else if(ret.data.comAuthStat == "Y" && ret.data.passAuthStat == "N"){
                        openPopup('modal', {
                            content: '<div style="padding: 50px;">인증 후에 공고를 등록 하실 수 있습니다.</div>',
                            firstButton: '<div style="width: 100%; height: 56px; line-height: 56px; cursor: no-drop; background: #BEBFC9;" onclick="return false;">기업정보 인증</div>',
                            secondButton: '<div style="width: 100%; height: 56px; line-height: 56px; background: #57CCAF;">PASS 인증</div>',
                            firstCallback: function () {
                            },
                            secondCallback: function () {
                                location.href="/compage/index?gbn=pass";
                            }
                        });
                    }

                }else if(ret.code=="00"){
                    //_replace("/compage/recruit_write1");
                    location.href="/compage/recruit_write1_new";
                }else if(ret.code=="01"){
                	openPopup('confirm', {
	                    //20220929 수정
	                    content:'<p>채용공고등록은 <em>1일 2건 무료</em>입니다<br> 추가로 공고를 등록하시려면<br> 포인트에서 차감됩니다.</p><p class="required-point">채용공고등록: <span>'+ret.data.use_point+'P</span>차감</p>',
	                    secondButton:'유료등록',
	                    secondCallback: function () {
	                        comAjax("/compage_proc/recruit_start_point",{},function(ret2){
				                if(ret2.code=="00"){
				                    //_replace("/compage/recruit_write1");
                            location.href="/compage/recruit_write1_new";
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
	});


</script>
