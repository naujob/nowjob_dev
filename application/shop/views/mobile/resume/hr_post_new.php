<?php
function fHashSet($txt){
    $tags = explode(",", $txt);

    // 각 요소를 # 태그로 변환하여 출력
    $result = "";
    foreach ($tags as $tag) {
        $tag = trim($tag); // 공백 제거
        if (!empty($tag)) { // 빈 값 방지
            $result .= '<p class="tag" style="width:auto;text-align:left;">#' . htmlspecialchars($tag) . '</p>' . "\n";
        }
    }

    return $result;
}
function fRemoveBigAdr($arg) {

  $parts1 = explode(",", $arg);
  $arg = $parts1[0];

  $replacements = [
    "서울특별시 " => "서울시 ",
    "경기도 " => "",
    "강원특별자치도 " => "",
    "충청남도 " => "",
    "충청북도 " => "",
    "경상남도 " => "",
    "경상북도 " => "",
    "전라남도 " => "",
    "전북특별자치도 " => "",
    "제주특별자치도 " => "",
    "세종특별자치시 " => "세종시 ",
    "광주광역시 " => "광주시 ",
    "인천광역시 " => "인천시 ",
    "울산광역시 " => "울산시 ",
    "부산광역시 " => "부산시 ",
    "대구광역시 " => "대구시 ",
    "대전광역시 " => "대전시 ",
    "경기 " => "",
    "강원 " => "",
    "충남 " => "",
    "충북 " => "",
    "경남 " => "",
    "경북 " => "",
    "전남 " => "",
    "전북 " => "",
    "제주 " => ""
  ];

  foreach ($replacements as $search => $replace) {
    $arg = str_replace($search, $replace, $arg);
  }

  $parts = explode(" ", $arg);
  if (count($parts) >= 3) {
    return $parts[0] . " " . $parts[2];
  } else {
    return $arg;
  }
}
?>
<input type="hidden" name="ru_idx" value="<?=$info["ru_idx"]?>" />
<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="<?=$top_button?>"><span class="sr-text">이전으로</span></a>
        <h1 class="title">인재상세정보</h1>
        <span class="rt-group">
            <a href="/compage/recruit_list" class="btn-action">채용공고작성</a>
            <a href="#sharePanel" aria-haspopup="dialog" aria-controls="sharePanel" class="btn-share js-toggle-panel"><span class="sr-text">공유하기</span></a>
        </span>
    </div><!-- //top-bar -->

    <div class="page-content">
        <div class="section view-header">
            <div class="profile">
                <div class="container">
                    <div class="thumb-wrap">
                        <div class="thumb">
                            <img src="<?=getResumeImg($info)?>" onError="this.src='/resource/shop/mobile/img/common/noimage3.png'"  alt="">
                        </div>
                        <!-- 평점 -->
                        <p class="star-rating v2">
                            <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                            <span class="star" style="width:<?=($mem_info["eval_num"] * 20)?>%;"></span>
                            <span class="sr-text">평점</span>
                            <span class="sr-text">5</span>
                        </p>
                    </div>
                    <div class="profile-detail">
                        <!-- 이름 -->
                        <p class="name"><em class="view-title"><?=getAstrik($mem_info["mm_name"])?></em>님</p>
                        <div class="meta">
                            <!-- 연락처 -->
                            <?php if(trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) == ""){
                                $gapbar1 = "display:none;"; $gapbar2 = "display:none;";
                            }else if(trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) != ""){
                                $gapbar1 = "display:none;"; $gapbar2 = "display:none;";
                            }else if(trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) == ""){
                                $gapbar1 = "display:none;"; $gapbar2 = "display:none;";
                            }else if(trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) != ""){
                                $gapbar1 = "display:none;"; $gapbar2 = "";
                            }else if(trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) == ""){
                                $gapbar1 = "display:none;"; $gapbar2 = "display:none;";
                            }else if(trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) != ""){
                                $gapbar1 = ""; $gapbar2 = "display:none;";
                            }else if(trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) == ""){
                                $gapbar1 = ""; $gapbar2 = "display:none;";
                            }else if(trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) != ""){
                                $gapbar1 = ""; $gapbar2 = "";
                            }
                            ?>
                            <p><span><?=$mem_info["mm_nation"]?></span>
                                <span class="gapbar1" style="<?=$gapbar1?>">|</span>
                                <span><?=(getKoreanAge($mem_info["mm_birth"])=="0"?"":getKoreanAge($mem_info["mm_birth"])."세")?></span></p>
                             <p><span><?=fRemoveBigAdr($mem_info["ad_addr"])?></span></p>
                        </div>
                        <!-- 즐겨찾기 -->
                        <button type="button" data-bk_type="RESUME" data-bk_key="<?=$info["ru_idx"]?>" data-user_id="<?=$mem_info["mm_id"]?>" class="btnBookMark btn-view-bookmark js-toggle-bookmark <?=$book_yn=="Y" ? "on":""?>">
			                <span class="sr-text">즐겨찾기 추가</span>
			            </button>
                    </div>
                    <?php if($this->user->getUserType()!="COMP"){ ?>
                      <ul class="profile-record" style="filter: blur(5px);">
                          <li><span class="label">프로필 조회</span><em class="value">0</em></li>
                          <li><span class="label">받은제안</span><em class="value">0</em></li>
                          <li><span class="label">관심인재</span><em class="value">0</em></li>
                      </ul>
                    <?php }else{ //comp ?>
                    <?php     if($this->user->getCurrentUserInfo()["mm_comp_num"] == "" || $this->user->getCurrentUserInfo()["mm_di"] == "" ){ ?>
                      <ul class="profile-record" style="filter: blur(5px);">
                          <li><span class="label">프로필 조회</span><em class="value">0</em></li>
                          <li><span class="label">받은제안</span><em class="value">0</em></li>
                          <li><span class="label">관심인재</span><em class="value">0</em></li>
                      </ul>
                      <div style="position: absolute; top: 190px; left: 0px; width: 100%; height: 173px;">
                          <span style="position: absolute; top: 24px; font-size: 1.8em; font-weight: 600; left: 49px;">기업회원 인증시 <br>확인이 가능합니다.</span>
                          <?php if($this->user->getCurrentUserInfo()["mm_comp_num"] == ""){ ?>
                              <a href="/compage/index?gbn=compcert" style="position: absolute; top: 120px; right: 70px; background-color: #57CCAF; color: #fff; padding: 5px 10px; border-radius: 6px;">사업자 인증</a>
                          <?php } ?>
                          <?php if($this->user->getCurrentUserInfo()["mm_di"] == "" ){ ?>
                              <a href="/compage/index?gbn=pass" style="position: absolute; top: 120px; right: 180px; background-color: #57CCAF; color: #fff; padding: 5px 10px; border-radius: 6px;">PASS 인증</a>
                          <?php } ?>
                      </div>
                    <?php     }else{ ?>
                      <ul class="profile-record">
                          <li><span class="label">프로필 조회</span><em class="value"><?=$info["ru_hit"]?></em></li>
                          <li><span class="label">받은제안</span><em class="value"><?=$info["req_cnt"]?></em></li>
                          <li><span class="label">관심인재</span><em class="value"><?=$info["fav_cnt"]?></em></li>
                      </ul>
                    <?php     } ?>
                    <?php }//comp ?>
                </div>
            </div>
            <!-- 20220929 수정 -->
            <div class="summary">
                <div class="container">
                    <h3 class="secondary-title">희망근무조건</h3>
                    <table class="info-table">
                        <caption><span class="sr-text">희망근무조건</span></caption>
                        <colgroup><col><col></colgroup>
                        <tbody>
                            <tr>
                                <td class="label">업무구분</td>
                                <td class="value">
                                	<?=getWorkingImg($info["ru_work"])?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">지역</td>
                                <td class="value">
                                  <?php $addr_cnt = 0; ?>
                                	<?php foreach($addr as $key=> $ad) : ?>
                                		<? //=$key==0 ? $ad["ad_addr"]:" ," . $ad["ad_addr"]?>
                                    <?php if($ad["ad_addr"]!=""){
                                      if($addr_cnt > 0) echo ", ";
                                      $addr_cnt++;
                                      echo $ad["ad_addr"];
                                    } ?>
                                	<?php endforeach; ?>
								</td>
                            </tr>
                            <tr>
                                <td class="label">급여형태</td>
                               	<td class="value"><?=$info["ru_price_type"]?></td>
                            </tr>
                            <?php if(strpos($info["ru_price_type"],"월급")!==false && $info["ru_month_price"]!="0") : ?>
	                            <tr>
	                                <td class="label">희망연봉</td>
	                                <td class="value"><?=number_format($info["ru_month_price"])?>원</td>
	                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td class="label">근무형태</td>

                                 <td class="value"><?=$info["ru_work_type"]?></td>
                            </tr>
                            <tr>
                                <td class="label">근무요일</td>
                                <td class="value">
                                    <ul class="working-day">
                                        <?=getWeekYoil($info["ru_week"])?>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">근무시작일</td>
                                <td class="value"><?=(get_date($info["ru_work_dt"]))?>(<?=get_yoil($info["ru_work_dt"])?>)</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- 신고하기 -->
                    <button type="button" aria-haspopup="dialog" aria-controls="reportForm1" class="btn-report js-report"><span class="sr-text">신고하기</span></button>
                </div>
            </div>
        </div>

        <!-- 신고하기 양식 -->
        <template id="ReportTemp">
            <div class="alert-msg">
                <p>해당게시글을 신고하시겠습니까?<br> 신고사유를 작성해주세요</p>
                <div class="report-form">
                    <label>
                        <span class="sr-text">신고사유입력</span>
                        <textarea name="br_desc" placeholder="신고사유를 작성해주세요" class="form-textarea js-autoheight"></textarea>
                    </label>
                </div>
            </div>
        </template>
        <!-- //신고하기 양식 -->

        <script>
            /* 신고내용 작성 팝업 */
            window.addEventListener('DOMContentLoaded', function () {
                document.querySelector('.js-report').addEventListener('click', function () {
                    var content = document.querySelector('#ReportTemp').innerHTML;
                    openPopup('modal', {
                        id:'reportForm',
                        title:'신고하기',
                        content: content,
                        firstButton: '확인',
                        firstCallback: function () {
                            if($("[name=br_desc]").val()==""){
		                        commonAlert("내용을 입력해주세요.",function(){
		                            $("[name=br_desc]").focus();
		                        });
		                        return false;
		                    }
		                    var params = {};
		        			params["bd_idx"] = $("[name=ru_idx]").val();
		        			params["br_type"] = "resume";
		                    params["br_desc"] = $("[name=br_desc]").val();
		        			comAjax("/setting_proc/board_report",params,function(ret){
		        				if(ret.code=="00"){
		                            removeElement("#ReportWriter1");
		        					commonAlert("신고 처리 되었습니다.",function(){
		                            });
		        				}else{
		                            commonAlert("신고 처리시 오류가 발생하였습니다.",function(){});
		        				}
		        			});
                        }
                    });
                });
            });
        </script>

        <div class="section view-career">
            <div class="container">
                <h3 class="secondary-title">경력소개</h3>
                <table class="info-table">
                    <caption><span class="sr-text">경력</span></caption>
                    <colgroup><col><col></colgroup>
                    <tbody>
                        <tr>
                            <td class="label">최종학력</td>
                            <td class="value"><?=$info["ru_edu"]?></td>
                        </tr>
                        <tr>
                            <td class="label">경력사항</td>
                            <td class="value">
                                <ul class="period-list">
                                	<?php foreach($career_list as $row) : ?>
                                    <li>
                                        <span><?=$row["rc_type"]?></span>
                                        <span><?=$row["rc_name"]?></span>
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
                                        <em><?=$row["rc_date"]?><?=$is_date ? "(" . get_date_diff_txt($sdate,$edate) . ")" : ""?></em>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">자격증</td>
                            <td class="value">
                                <ul class="period-list">
                                	<?php foreach($certi_list as $row) : ?>
                                    <li>
                                    	<span>자격증</span>
                                        <span><?=$row["rc_name"]?></span>
                                        <em><?=$row["rc_dt"]?></em>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="section view-introduce">
            <div class="container">
                <h3 class="secondary-title">자기소개</h3>
                <div class="introduce-wrap">
                  	<?=str_replace("\n","<br/>",$info["ru_desc"])?>
                </div>
                <div class="hashtag" style="justify-content: left;">
                    <?=fHashSet($info["ru_my_type"])?>
                </div>
            </div>
        </div>
        <!-- div class="section view-attachment">
            <div class="container">
                <h3 class="secondary-title">첨부사진</h3>
                <div class="pic-group">
                    <?php if($info["ru_img1"]!="") : ?>
                    <div class="pic-card"><img data-src="<?=$info["ru_img1"]?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if($info["ru_img2"]!="") : ?>
                    <div class="pic-card"><img data-src="<?=$info["ru_img2"]?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if($info["ru_img3"]!="") : ?>
                    <div class="pic-card"><img data-src="<?=$info["ru_img3"]?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if($info["ru_img4"]!="") : ?>
                    <div class="pic-card"><img data-src="<?=$info["ru_img4"]?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                    <?php if($info["ru_img5"]!="") : ?>
                    <div class="pic-card"><img data-src="<?=$info["ru_img5"]?>" alt="" class="lozad"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div -->

        <div class="view-footer">
            <button type="button" aria-haspopup="dialog" data-ru_idx="<?=$info["ru_idx"]?>" class="btnRequest btn btn-primary">제안보내기</button>
        </div>

    </div><!-- //page-content -->

</div>

<!-- 공유하기 팝업 -->
<div id="sharePanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="sr-text">공유하기</h2>
            <button type="button" aria-controls="sharePanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="vendor-links">
                    <a href="#" onclick="shareMessage();return false;" class="share-link1">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_vendor1.svg" alt=""></span>
                        <span>카카오톡</span>
                    </a>
                    <a href="#" onclick="CopyUrl();return false;" class="share-link2">
                        <span class="ico"><img src="/resource/shop/mobile/img/share_copy_url.svg" alt=""></span>
                        <span>URL복사</span>
                    </a>
                </div>

                <script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script>
                <script>
                    /* 카카오톡 공유 */
                    function shareMessage() {
                        if (!Kakao.isInitialized()) {
                            Kakao.init("<?=$kakao_key["인증키"]?>");
                        }

                        var title = document.querySelector('.view-title').textContent.replace(/\s/g,''),
                            //desc = document.querySelector('.view-desc .desc').textContent.replace(/\s/g,''),
                            //imgUrl = document.querySelector('.view-visual-slider img').getAttribute('src');
                            url = location.href,
                            appUrl = 'scheme://host_path';

                            title = '나우잡 인재 - '+title;
                            desc = '';//'나우잡에서 인재 정보를 확인 하세요';
                            //imgUrl = 'https://nowjob.co.kr/resource/shop/web/img/top_logo.svg';
                            imgUrl = 'https://nowjob.co.kr/resource/shop/web/img/nowjob_info.png';

	                        setTimeout(function () {
	                            Kakao.Share.sendDefault({
	                                objectType: 'feed',
	                                content: {
	                                    title: title,//글제목
	                                    description: desc,//글내용
	                                    imageUrl: imgUrl,//대표이미지 절대주소
	                                    link: {
	                                        mobileWebUrl: url,
	                                    },
	                                },
	                                buttons: [
	                                    {
	                                        title: '웹으로 보기',
	                                        link: {
	                                            mobileWebUrl: url,
	                                            webUrl: url
	                                        },
	                                    },
	                                    {
	                                        title: '앱으로 보기',
	                                        link: {
	                                            mobileWebUrl: appUrl,
	                                            webUrl: url
	                                        },
	                                    },
	                                ],
	                            });
                        }, 100);
                    }

                    /* URL 복사 */
                    function CopyUrl(){
                        var dummy = document.createElement('input'),
                            url = location.href;

                        document.body.appendChild(dummy);
                        dummy.value = url;
                        dummy.select();
                        document.execCommand('copy');
                        document.body.removeChild(dummy);
                        openPopup('alert',{content:'URL을 복사했습니다.'});
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<!-- //공유하기 팝업 -->


<!-- 제안 리스트 -->
<div id="selectPanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header" style="background: #57CCAF; color: #fff; padding-top: 1.25rem; margin-bottom: 20px;">
            <!-- 약관제목 -->
            <h2 class="panel-title" style="color:#fff;">진행중인 채용공고</h2>
            <button type="button" aria-controls="selectPanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="terms-wrap">

                    <div class="post-list">
		                <ul class="list">
		                	<?php foreach($recruit_list as $row) : ?>
		                    <li style="border: 1px solid #ccc; border-radius: 10px; margin-bottom: 10px;">
		                        <div class="selectable-item">
		                            <label class="form-checkbox">
		                                <input type="radio" name="chk_request[]" value="<?=$row["rc_idx"]?>" data-rc_name="<?=$row["rc_name"]?>" >
		                                <span class="label"></span>
		                                <span class="sr-text">선택</span>
		                            </label>
		                            <a href="javascript:void(0);" class="angle-link" style="background:none;">
		                                <strong><?=$row["rc_name"]?></strong>
		                                <span class="date"><?=get_yyyymmdd($row["rc_sdate"])?> ~ <?=get_yyyymmdd($row["rc_edate"])?></span>
		                            </a>
		                        </div>
		                    </li>
		                    <?php endforeach; ?>
		                    <?php if(count($recruit_list)==0) : ?>
		                    <div class="is-empty-v2">
				                <p>등록된 채용공고가 없습니다.</p>
				            </div>
				            <?php endif; ?>
		                </ul>
		            </div>

                    <!-- //목록 -->
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom">
                <button type="button" id="" aria-controls="terms2Panel" class="btnProcess btn btn-primary js-toggle-panel">제안 보내기</button>
            </div>
        </div>
    </div>
</div>
<!-- //제안 리스트 -->

<script>
	var check_user_type = "<?=$this->user->getUserType();?>";
	$(".btnRequest").click(function(){
        //로그인 여부 확인
        if($("#is_login").val()!="Y"){
            //commonLogin();

            openPopup('confirm', {
				      id:'loginconfirm',
	            content:'<strong>로그인후 이용해주세요!</strong><small></small>',
	            firstButton:'로그인',
	            secondButton:'회원가입',
	            firstCallback: function () {
                location.href="/member/login";
	            },
	            secondCallback:  function () {
					        setCookie('non_mem_last_resume', window.location.href, 1);
					        location.href="/member/join_start";
	            },
			      });
            $('.btn-close-popup').show();

            return;
        }

        if(check_user_type!="COMP"){
			commonAlert("일반 회원은 제안 할 수 없습니다.",function(){});
			return;
		}

        $("[name='chk_request[]']").prop("checked",false);
        $("#selectPanel").addClass("on");

	});

	$(".btnProcess").click(function(){
        if($("[name='chk_request[]']:checked").length==0){
			commonAlert("제안하실 공고를 선택해주세요.",function(){});
			return;
		}

		var rc_idx = $("[name='chk_request[]']:checked").eq(0).val();
    var ru_title = $("[name='chk_request[]']:checked").eq(0).data('rc_name');
		var mq_idxs = new Array();
		mq_idxs.push($("[name='ru_idx']").val());
		openPopup('confirm', {
        content: '<em>'+ru_title+'</em> 로<br> 제안을 보내시겠습니까?',
        firstCallback: function () {

        },
        secondCallback: function () {
          var params = {};
			    params["mq_idxs"] = mq_idxs;
			    params["rc_idx"] = rc_idx;
			    params["mq_type"] = "RESUME";
			    comAjax("/common_proc/member_request",params,function(ret){
            //console.log(ret);
			    	if(ret.code=="00"){
			        	commonAlert("선택한 채용공고로 제안하였습니다.",function(){
			        		$("#selectPanel").removeClass("on");
			        	});
		        }else{
		            commonAlert(ret.msg,function(){
		            	$("#selectPanel").removeClass("on");
		            });
		            return;
		        }
			    });
        }
    });
    $('.btn-close-popup').hide();

	});
</script>
