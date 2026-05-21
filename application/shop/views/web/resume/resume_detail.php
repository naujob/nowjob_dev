<input type="hidden" name="ru_idx" value="<?=$info["ru_idx"]?>" />
<?php
function fWorkColor($arg){

  $arg = (strpos($arg,",")!==false ? "협의" : ($arg));

  $arg = str_replace("주간", "<font style='color:#3a70db;font-weight:600;'>주간</font>", $arg); //파랑
  $arg = str_replace("야간", "<font style='color:#F86048;font-weight:600;'>야간</font>", $arg); //빨강
  $arg = str_replace("2교대", "<font style='color:#1B9D7D;font-weight:600;'>2교대</font>", $arg); //민트
  $arg = str_replace("근무협의", "<font style='color:#333;font-weight:600;'>근무협의</font>", $arg); //검정
  $arg = str_replace("시간협의", "협의", $arg);
  $arg = str_replace("협의", "<font style='color:#333;font-weight:600;'>협의</font>", $arg); //검정
  return $arg;
}

function fPay($arg){
  $arg = str_replace("급여협의", "협의", $arg);
  $arg = (strpos($arg,",")!==false ? "협의" : ($arg));
  return $arg;
}

function fAddrMake($addr){

  $addr = str_replace("경기 ", "", $addr);
  $addr = str_replace("강원 ", "", $addr);
  $addr = str_replace("충남 ", "", $addr);
  $addr = str_replace("충북 ", "", $addr);
  $addr = str_replace("경남 ", "", $addr);
  $addr = str_replace("경북 ", "", $addr);
  $addr = str_replace("전남 ", "", $addr);
  $addr = str_replace("전북 ", "", $addr);
  $addr = str_replace("제주 ", "", $addr);
  $addr = str_replace(",", "", $addr);

  $addrArray = explode(" ", $addr);
  if(count($addrArray) === 3) {
    $addr = $addrArray[0] . " " . $addrArray[2];
  }
  return $addr;

}

function fNoEmTag($arg){
  $arg = str_replace("<em>", "", $arg);
  $arg = str_replace("</em>", "", $arg);
  return $arg;
}

function convertHashtagString($input) {
    //echo "1:".$input."<br>";
    // 1. 해시태그(#) 제거 및 앞뒤 공백 제거
    $cleaned = preg_replace('/#/', '', $input);

    //echo "2:".$cleaned."<br>";
    // 2. 쉼표(,)를 기준으로 분할 및 공백 제거
    $words = array_map('trim', explode(',', $cleaned));

    //echo "3:".$words."<br>";
    // 3. 빈 요소 제거 (공백 또는 잘못된 쉼표 처리)
    $words = array_filter($words, function($word) {
        return !empty($word);
    });

    // 4. 변환된 문자열 반환
    $rtn = implode(', ', $words);
    //echo "4:".$rtn."<br>";

    $rtn = str_replace(' ', ', ', $rtn);
    //echo "5:".$rtn."<br>";

    return $rtn;
}

?>
<div id="content" class="page page-view">
    <main class="page-content">
        <div class="item-view person">
            <div class="container">
                <!-- 컨텐츠 영역 -->
                <div class="view-content">
                    <div class="section view-header">
                        <div class="profile">
                            <div class="thumb">
                                <?php //print_r($info); ?>
                                <img src="<?=getResumeImg($info)?>" onError="this.src='/resource/shop/mobile/img/common/noimage3.png'" alt="" >
                            </div>
                            <div class="profile-detail">
                                <!-- 이름 -->
                                <p class="name"><em class="view-title"><?=getAstrik($mem_info["mm_name"])?></em>님</p>
                                <div class="meta">
                                    <!-- 추가사항 -->
                                    <p><span><?=$mem_info["mm_nation"]?></span>
                                      <?=($mem_info["mm_nation"]!="" && $mem_info["ma_age"]!="0"?" / ":"")?>
                                      <?=($mem_info["ma_age"]=="0"?"":"<span>".$mem_info["ma_age"]."세</span>")?></p>
                                    <p>
                                    	<?=$mem_info["ad_addr"]?>
                                    </p>
                                </div>
                            </div>
                            <?php if($this->user->getUserType()!="COMP"){ ?>
                              <ul class="profile-record" style="filter: blur(5px);">
                                  <li><span class="label">프로필 조회</span><em class="value">0</em></li>
                                  <li><span class="label">받은제안</span><em class="value">0</em></li>
                                  <li><span class="label">관심인재</span><em class="value">0</em></li>
                                  <li>
                                      <ul>
                                          <!-- 개수가0일때 dimmed 클래스추가 -->
                                          <li class="dimmed"><span class="label">면접 불참 및 취소</span><em class="value">0</em></li>
                                          <li class="dimmed"><span class="label">무단결근</span><em class="value">0</em></li>
                                          <li class="dimmed"><span class="label">무단퇴사</span><em class="value">0</em></li>
                                      </ul>
                                  </li>
                              </ul>
                            <?php }else{ //comp ?>
                            <?php     if($this->user->getCurrentUserInfo()["mm_comp_num"] == "" || strpos($this->user->getCurrentUserInfo()["mm_di"],"MC0") !== 0 ){ ?>
                              <ul class="profile-record"
                                      style="filter: blur(5px);"
                              >
                                  <li><span class="label">프로필 조회</span><em class="value">0</em></li>
                                  <li><span class="label">받은제안</span><em class="value">0</em></li>
                                  <li><span class="label">관심인재</span><em class="value">0</em></li>
                                  <li>
                                      <ul>
                                          <!-- 개수가0일때 dimmed 클래스추가 -->
                                          <li class="dimmed"><span class="label">면접 불참 및 취소</span><em class="value">0</em></li>
                                          <li class="dimmed"><span class="label">무단결근</span><em class="value">0</em></li>
                                          <li class="dimmed"><span class="label">무단퇴사</span><em class="value">0</em></li>
                                      </ul>
                                  </li>
                              </ul>
                              <div style="position: absolute; top: 0px; left: 336px; width: 633px; height: 155px;">
                                  <span style="position: absolute; top: 43px; font-size: 1.8em; font-weight: 600; left: 49px;">기업회원 인증시 <br>확인이 가능합니다.</span>
                                  <?php if($this->user->getCurrentUserInfo()["mm_comp_num"] == ""){ ?>
                                      <a href="/compage/mypage/compcert" style="position: absolute; top: 90px; right: 135px; background-color: #57CCAF; color: #fff; padding: 5px 10px; border-radius: 6px;">사업자 인증</a>
                                  <?php } ?>
                                  <?php if(strpos($this->user->getCurrentUserInfo()["mm_di"],"MC0") !== 0 ){ ?>
                                      <a href="/compage/mypage/pass" style="position: absolute; top: 90px; right: 38px; background-color: #57CCAF; color: #fff; padding: 5px 10px; border-radius: 6px;">PASS 인증</a>
                                  <?php } ?>
                              </div>
                              <?php   }else{ ?>
                              <ul class="profile-record">
                                  <li><span class="label">프로필 조회</span><em class="value"><?=$info["ru_hit"]?></em></li>
                                  <li><span class="label">받은제안</span><em class="value"><?=$info["req_cnt"]?></em></li>
                                  <li><span class="label">관심인재</span><em class="value"><?=$info["fav_cnt"]?></em></li>
                                  <li>
                                      <ul>
                                          <!-- 개수가0일때 dimmed 클래스추가 -->
                                          <li class="dimmed"><span class="label">면접 불참 및 취소</span><em class="value"><?=$info["non_cnt"]?></em></li>
                                          <li class="dimmed"><span class="label">무단결근</span><em class="value"><?=$info["abs_cnt"]?></em></li>
                                          <li class="dimmed"><span class="label">무단퇴사</span><em class="value"><?=$info["lv_cnt"]?></em></li>
                                      </ul>
                                  </li>
                              </ul>
                            <?php     } ?>
                            <?php }//comp ?>

                        </div>

                        <div class="inline-group">
                            <p class="help-msg">개인 연락처는 면접결정시 기업에 공유할수 있습니다.</p>
                            <!-- 신고하기 -->
                            <button type="button" aria-haspopup="dialog" aria-controls="reportForm1" class="btn-report js-report"><span class="sr-text">신고하기</span></button>

                            <!-- 신고하기 양식 -->
                            <template id="ReportTemp">
                                <div class="alert-msg">
                                    <p>해당게시글을 신고하시겠습니까?<br> 신고사유를 작성해주세요</p>
                                    <div class="report-form">
                                        <label>
                                            <span class="sr-text">신고사유입력</span>
                                            <textarea name="br_desc" placeholder="신고사유를 작성해주세요" autofocus class="form-textarea js-autoheight"></textarea>
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
                        </div>

                        <div class="summary">
                            <div class="inline-group">
                                <div class="col">
                                    <h3 class="secondary-title">인재정보</h3>
                                    <table class="info-table">
                                        <caption><span class="sr-text">인재정보</span></caption>
                                        <colgroup><col><col></colgroup>
                                        <tbody>
                                            <tr>
                                                <td class="label">업무구분</td>
                                                <td class="value">
                                                   <?=getWorkingImg($info["ru_work"])?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="label">근무지역</td>
                                                <td class="value">
                                                  <?php $addr_cnt = 0; ?>
                                                	<?php foreach($addr as $key=> $ad) : ?>
				                                		              <? //=$key==0 ? $ad["ad_addr"]:" ," . $ad["ad_addr"]?>
                                                      <?php if($ad["ad_addr"] != ""){
                                                              if($addr_cnt > 0) echo ", ";
                                                              $addr_cnt++;
                                                              echo $ad["ad_addr"];
                                                            }
                                                       ?>
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
                                            <!--
                                            <tr>
                                                <td class="label">근무요일</td>
                                                <td class="value">
                                                    <ul class="working-day">
                                                        <?=getWeekYoil($info["ru_week"])?>
                                                    </ul>
                                                </td>
                                            </tr>
                                            -->
                                            <tr>
                                                <td class="label">근무희망일</td>
                                                <td class="value"><?=(get_date($info["ru_work_dt"]))?>(<?=get_yoil($info["ru_work_dt"])?>)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col">
                                    <h3 class="secondary-title">&nbsp;<!-- 경력소개 --></h3>
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
                                                <td class="label">자&nbsp;&nbsp;격&nbsp;&nbsp;증</td>
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
                                            <tr>
                                                <td class="label">근무요일</td>
                                                <td class="value">
                                                    <ul class="working-day">
                                                        <?=getWeekYoil($info["ru_week"])?>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section view-introduce" style="margin-top: 30px;">
                        <h3 class="secondary-title">자기소개</h3>
                        <div class="introduce-wrap">
                            <?=str_replace("\n","<br/>",$info["ru_desc"])?>
                        </div>
                        <div class="hashtag">
                            <? //=getMyTypeLeft($info["ru_my_type"])?>
                            <?=getHashTag(convertHashtagString($info["ru_my_type"]))?>
                        </div>
                    </div>
                    <!--div class="section view-portfolio">
                        <h3 class="secondary-title">포트폴리오</h3>
                        <p>
                            <a href="#" target="_blank" download class="file-download">portfilo.zip</a>
                        </p>
                    </div-->
                    <div class="section view-attachment">
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

                </div>
                <!-- 컨텐츠 영역 -->

                <!-- aside -->
                <div class="view-aside">

                    <div class="sticky-group">
                        <div class="summary-card">
                            <div class="top">
                                <div class="inline-group">
                                    <!-- 카테고리 라벨 -->
                                   	<?=getWorkingImg($info["ru_work"])?>
                                </div>

                                <!-- 공유하기 -->
                                <div class="share-wrap">
                                    <button type="button" class="btn-share js-toggle-dropdown"><span class="sr-text">공유하기</span></button>
                                    <div class="dropdown">
                                        <a href="#" onclick="shareMessage();return false;" class="share-link1">
                                            <span class="ico"><img src="/resource/shop/web/img/ic_share_vendor1.svg" alt=""></span>
                                            <span>카카오톡</span>
                                        </a>
                                        <a href="#" onclick="CopyUrl();return false;" class="share-link2">
                                            <span class="ico"><img src="/resource/shop/web/img/ic_share_copy_url.svg" alt=""></span>
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
                                                //imgUrl = document.querySelector('.view-visual .visual-slider img').getAttribute('src');
                                                url = location.href,
                                                appUrl = 'scheme://host_path';

                                                title = '나우잡 인재 - '+title;
                                                desc = ''; //'나우잡에서 인재 정보를 확인 하세요';
                                                imgUrl = '/resource/shop/web/img/nowjob_info.png'; //'/resource/shop/web/img/top_logo.svg';

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

                                <!-- 즐겨찾기 -->
                                 <button type="button" data-bk_type="RESUME" data-bk_key="<?=$info["ru_idx"]?>" data-user_id="<?=$mem_info["mm_id"]?>" class="btnBookMark btn-view-bookmark js-toggle-bookmark <?=$book_yn=="Y" ? "on":""?>">
					                <span class="sr-text">즐겨찾기 추가</span>
					            </button>
                            </div>
                            <!-- 타이틀 -->
                            <p class="title"><?=getAstrik($mem_info["mm_name"])?></p>
                            <!-- 기타정보 -->
                            <p class="type">
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
                              <span><?=$mem_info["mm_nation"]?></span>
                              <span class="gapbar1" style="<?=$gapbar1?>">/</span>
                              <span><?=getKoreanAge($mem_info["mm_birth"])?>세</span>
                              <span class="gapbar2" style="<?=$gapbar2?>">/</span>
                              <span><?=$mem_info["ad_addr"]?></span>
                            </p>
                            <!-- 평점 -->
                            <p class="star-rating">
                                <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                                <span class="star" style="width:100%;"></span>
                                <span class="sr-text">평점</span>
                                <span class="sr-text">5</span>
                            </p>

                            <?php if($this->user->getUserType()=="MEM"){ ?>
                              <button type="button" aria-haspopup="dialog" data-ru_idx="<?=$info["ru_idx"]?>" class="btnRequest btn-request btn-primary" style="background-color:#ccc;" disabled>제안보내기</button>
                            <?php }else{ ?>
                              <button type="button" aria-haspopup="dialog" data-ru_idx="<?=$info["ru_idx"]?>" class="btnRequest btn-request btn-primary">제안보내기</button>
                            <?php } ?>
                        </div>

                        <!-- 히스토리 목록 -->
                        <div class="recent-history">
                            <div class="history-header">
                                <p class="title">내가 본 인재</p>
                            </div>
                            <div class="history-body">
                                <div class="recently-slider keen-slider">
									<?php foreach($view_list as $row) : ?>
                                    <!-- 목록 -->
                                    <div class="keen-slider__slide">
                                        <div class="summary-card">
                                            <div class="inline-group">
                                                <!-- 카테고리 라벨 -->
                                                 <?=getWorkingImg($row["ru_work"])?>
                                            </div>
                                            <p class="title">
                                                <a href="/resume/resume_detail/<?=$row["ru_idx"]?>"><?=getAstrik($row["mm_name"])?></a>
                                            </p>
                                            <?php if(trim($row["mm_nation"]) == "" && trim($row["ma_age"]) == "" && trim($row["ad_addr"]) == ""){
                                                $ingapbar1 = "display:none;"; $ingapbar2 = "display:none;";
                                            }else if(trim($row["mm_nation"]) == "" && trim($row["ma_age"]) == "" && trim($row["ad_addr"]) != ""){
                                                $ingapbar1 = "display:none;"; $ingapbar2 = "display:none;";
                                            }else if(trim($row["mm_nation"]) == "" && trim($row["ma_age"]) != "" && trim($row["ad_addr"]) == ""){
                                                $ingapbar1 = "display:none;"; $ingapbar2 = "display:none;";
                                            }else if(trim($row["mm_nation"]) == "" && trim($row["ma_age"]) != "" && trim($row["ad_addr"]) != ""){
                                                $ingapbar1 = "display:none;"; $ingapbar2 = "";
                                            }else if(trim($row["mm_nation"]) != "" && trim($row["ma_age"]) == "" && trim($row["ad_addr"]) == ""){
                                                $ingapbar1 = "display:none;"; $ingapbar2 = "display:none;";
                                            }else if(trim($row["mm_nation"]) != "" && trim($row["ma_age"]) == "" && trim($row["ad_addr"]) != ""){
                                                $ingapbar1 = ""; $ingapbar2 = "display:none;";
                                            }else if(trim($row["mm_nation"]) != "" && trim($row["ma_age"]) != "" && trim($row["ad_addr"]) == ""){
                                                $ingapbar1 = ""; $ingapbar2 = "display:none;";
                                            }else if(trim($row["mm_nation"]) != "" && trim($row["ma_age"]) != "" && trim($row["ad_addr"]) != ""){
                                                $ingapbar1 = ""; $ingapbar2 = "";
                                            }
                                            ?>

                                            <p class="type">
                                              <span><?=$row["mm_nation"]?></span>
                                              <span class="gapbar1" style="<?=$ingapbar1?>">/</span>
                                              <span><?=getKoreanAge($row["mm_birth"])?>세</span>
                                              <span class="gapbar2" style="<?=$ingapbar2?>">/</span>
                                              <span><?=fAddrMake($row["ad_addr"])?></span>
                                            </p>
                                            <!-- 평점 -->
                                            <p class="star-rating">
                                                <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                                                <span class="star" style="width:100%;"></span>
                                                <span class="sr-text">평점</span>
                                                <span class="sr-text">5</span>
                                            </p>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                    <!-- 목록 -->
                                </div>
                            </div>

                            <script>
                                window.addEventListener('DOMContentLoaded', function () {
                                    if (typeof KeenSlider === 'undefined') return;
                                    var thumbnails = new KeenSlider( ".recently-slider", {
                                            initial: 0,
                                            slides: { perView: 5, },
                                            vertical: true,
                                            drag: false,
                                        },
                                        [addActiveClass, sliderArrows]
                                    );
                                });
                            </script>
                        </div>
                        <!-- //히스토리 목록 -->

                    </div>

                </div>
                <!-- //aside -->

            </div>
        </div>

        <div class="view-footer">
            <div class="container">

                <!-- 하단 관련 인재 -->
                <div class="recomm-slider person">
                    <h2 class="sr-text">추천인재</h2>

                    <div class="slider-wrap keen-slider">
                     	<?php foreach($reco_list as $row) : ?>
                        <?php if($row["ru_idx"] == $info["ru_idx"]) continue; ?>
						<!-- 목록 -->
	                    <div class="keen-slider__slide" >
	                        <div class="person-card">
	                            <div class="thumb-wrap">
	                                <!-- 추천라벨 -->
	                                <p class="recomm-badge"><span>추천</span></p>
	                                <button type="button" data-bk_type="RESUME" data-bk_key="<?=$row["ru_idx"]?>" data-user_id="<?=$row["mm_id"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 추가</span></button>
	                                <!-- 썸네일 -->
	                                <div class="thumb">
	                                    <a href="/resume/resume_detail/<?=$row["ru_idx"]?>"><img src="<?=getResumeImg($row)?>" onError="this.src='/resource/shop/mobile/img/common/noimage3.png'" alt="""></a>
	                                </div>
	                            </div>
	                            <div class="content-wrap" style="padding: 46px 0px 24px;">
	                                <!-- 직종라벨 -->
	                                <p class="category">
	                                    <?=getWorkingImg($row["ru_work"])?>
	                                </p>
                                  <p class="area" style="float: right; margin-top: -36px; margin-right: 0px; font-size: 0.9em;"><?=fAddrMake($row["ad_addr"])?></p>
	                                <div class="title-wrap">
	                                    <!-- 제목 -->
	                                    <p class="title"><strong><a href="/resume/resume_detail/<?=$row["ru_idx"]?>"><?=getAstrik($row["mm_name"])?></a></strong></p>
	                                    <!-- 평점 -->
	                                    <p class="star-rating">
	                                        <span class="star" style="width:100%;"></span>
	                                        <span class="sr-text">평점</span>
	                                        <span class="sr-text">5</span>
	                                    </p>
	                                </div>
	                                <!-- 추가사항 -->
	                                <div class="meta">
	                                    <p class="national"><?=$row["mm_nation"]?></p>
	                                    <p class="age"><?=($row["ma_age"]=="0"?"":$row["ma_age"]."대")?></p>

	                                    <p class="txt-color2" style=""><?=fWorkColor(getWorkType($row["ru_work_type"]))?></p>
                                      <p class="txt-color2" style="/* width: 42.5px; overflow: hidden; height: 20px; padding: 0 5px; */"><?=fPay(fNoEmTag(getPriceType($row["ru_price_type"])))?></p>
	                                </div>
	                                <!-- 지역 -->
	                                <!-- <p class="area"><?=fAddrMake($row["ad_addr"])?></p> -->
	                                <!-- 태그 -->
	                                <div class="hashtag" style="border-top: 1px solid #000; padding-top: 5px;">
	                                    <?=getMyType($row["ru_my_type"])?>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
						<?php endforeach;?>
                    </div>
                    <script>
                        window.addEventListener('DOMContentLoaded', function () {
                            if (typeof KeenSlider === undefined) return;

                            new KeenSlider(".recomm-slider .slider-wrap", {
                                loop: true,
                                slides: { perView: 5 },
                            }, [
                                addActiveClass,
                                sliderArrows
                            ]);
                        });
                    </script>
                </div>
                <!-- //하단 관련 인재 -->

            </div>
        </div>

    </main><!-- //page-content -->
</div><!-- //page -->


<!-- 제안 리스트 -->
<div id="selectPanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container" style="max-width:500px;margin:0 auto;">
        <div class="panel-header">
            <!-- 약관제목 -->
            <h2 class="panel-title">진행중인 채용공고</h2>
            <button type="button" aria-controls="selectPanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="terms-wrap">
                    <div class="post-list">
		                <ul class="list">
		                	<?php foreach($recruit_list as $row) : ?>
		                    <li>
		                        <div class="selectable-item" style="position:relative;">
		                            <label class="form-checkbox"style="position:absolute;top:40px;">
		                                <input type="radio" name="chk_request[]" value="<?=$row["rc_idx"]?>" data-rc_name="<?=$row["rc_name"]?>" >
		                                <span class="label"></span>
		                                <span class="sr-text">선택</span>
		                            </label>
		                            <p style="padding:20px;padding-left:30px;">
		                                <strong><?=$row["rc_name"]?></strong><br/>
		                                <span class="date"><?=get_yyyymmdd($row["rc_sdate"])?> ~ <?=get_yyyymmdd($row["rc_edate"])?></span>
		                            </p>
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
            <div class="sticky-bottom" style="width:100%;">
                <button type="button" style="width:100%;" aria-controls="terms2Panel" class="btnProcess btn btn-primary js-toggle-panel">제안 보내기</button>
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


    //오늘 제안여부 확인
    var paramsToday = {};
    paramsToday["ru_idx"] = $("[name=ru_idx]").val();
    comAjax("/resume_proc/todayapply",paramsToday,function(ret){

      if(ret.code=="00"){
        //제안가능
        var rc_idx = $("[name='chk_request[]']:checked").eq(0).val();
    		var mq_idxs = new Array();
    		mq_idxs.push($("[name='ru_idx']").val());

    		var params = {};
    	    params["mq_idxs"] = mq_idxs;
    	    params["rc_idx"] = rc_idx;
    	    params["mq_type"] = "RESUME";

    	    comAjax("/common_proc/member_request_check",params,function(ret_1){
    			if(ret_1.code=="00"){
    				var add_text = (ret_1.data.point_yn=="Y" ? "<br/>" + ret_1.data.point_price + "P 포인트가 차감됩니다." : "");
    				openPopup('confirm', {
    		            content: '<em>해당공고</em><br> 로 제안을 보내시겠습니까?' + add_text,
    		            secondCallback: function () {
    					    comAjax("/common_proc/member_request",params,function(ret){
    					    	if(ret.code=="00"){
    					        	commonAlert("선택한 채용공고로 제안하였습니다.",function(){
    					        		$("#selectPanel").removeClass("on");

    									//채팅창 오픈
    					            	$("body").addClass('has-popup');
    						            $(".js-open-chat").addClass("is-trigger");
    						            $("#chatPopup").addClass("in");
    						            $(".btn-close-chat").addClass("btn-close-chat");
    						            $(".js-close-chat").addClass("show");

    					                //첫번째 탭 활성화
    					                setShow("1");

    									//해당공고 클릭 처리
    									fShowChatListByRecruite(rc_idx);

    						            //페이징 시작 초기화
    									//rm_start = 0;
    						            //rm_idx = ret.data;

    									console.log('채팅방 콜');
    									//룸 호출
    									setTimeout(function(){
    								        //$("#chat_view").html("");
    										//$('.chat-view').show();

    										//var targetId = $("#chat_list2 li:first-child").data("target_id");
    										var targetId = $("#chat_list1_1 li:first-child").data("target_id");
    								        socket.emit("room_join",{rm_idx : Number(targetId),con_user_id : $("#user_id").val()});
    								        socket.emit("room_info",{rm_idx : Number(targetId),con_user_id : $("#user_id").val()});
    										$('.chat-view').css('display','flex');
    										//$("#chat_list1_1 li:first-child").click();

    							   		},5000);

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
    		    }else{
    		  		commonAlert(ret_1.msg,function(){});
    		  	}
    		});



      }else if(ret.code=="01"){
          commonAlert("<em>5분 후</em> 다시 제안이 가능합니다.",function(){
            // 알림 후 패널 닫기
            $("#selectPanel").removeClass("on");
          });
          return;
      }else{
          commonAlert(ret.msg,function(){
            // 알림 후 패널 닫기
            $("#selectPanel").removeClass("on");
          });
          return;
      }


    });

	});
</script>
