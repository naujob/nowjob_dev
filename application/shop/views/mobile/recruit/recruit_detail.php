<?php
function formatPhoneNumber($number) {
    $number = preg_replace('/[^0-9]/', '', $number);

    if (preg_match('/^01[016789]\d{7,8}$/', $number)) {
        return preg_replace('/^(\d{3})(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
    }

    if (preg_match('/^02\d{7,8}$/', $number)) {
        return preg_replace('/^(02)(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
    }

    if (preg_match('/^0[3-9]{1}[0-9]{1}\d{7,8}$/', $number)) {
        return preg_replace('/^(0\d{2})(\d{3,4})(\d{4})$/', '$1-$2-$3', $number);
    }

    if (preg_match('/^(15|16|18)\d{6}$/', $number)) {
        return preg_replace('/^(\d{4})(\d{4})$/', '$1-$2', $number);
    }

    return $number;

}
?>
<input type="hidden" name="rc_idx" value="<?=$info["rc_idx"]?>" />
<style>
.btn-close-popup { display: none; }
</style>
<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="<?=$top_button?>"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
        <span class="rt-group">
            <a href="/mypage/resume_list" class="btn-action">이력서 작성</a>
            <a href="#sharePanel" aria-haspopup="dialog" aria-controls="sharePanel" class="btn-share js-toggle-panel"><span class="sr-text">공유하기</span></a>
        </span>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="view-visual-slider">
            <div class="slider-wrap keen-slider">
                <?php if($info["rc_img1"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["rc_img1"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
                <?php if($info["rc_img2"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["rc_img2"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
                <?php if($info["rc_img3"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["rc_img3"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
                <?php if($info["rc_img4"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["rc_img4"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
                <?php if($info["rc_img5"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["rc_img5"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
                <?php if($info["rc_img99"]!="") : ?>
                <div class="keen-slider__slide">
                    <img data-src="<?=$info["rc_img99"]?>" alt="" class="lozad">
                </div>
                <?php endif; ?>
            </div>
            <a href="javascript:void(0);" class="bigsize"><img src="/resource/shop/web/img/bigsize.png" /></a>
        </div>

        <script>
            window.addEventListener('DOMContentLoaded', function () {
                new KeenSlider(".view-visual-slider .slider-wrap", {
                    loop: true,
                }, [
                    sliderDots,
                    addActiveClass
                ]);
            });
        </script>

        <div class="section view-header">
            <!-- 즐겨찾기 -->
            <button type="button" data-bk_type="RECRUIT" data-bk_key="<?=$info["rc_idx"]?>" ata-user_id="<?=$mem_info["mm_id"]?>"  class="btnBookMark btn-view-bookmark js-toggle-bookmark <?=$book_yn=="Y" ? "on":""?>">
                <span class="sr-text">즐겨찾기 추가</span>
            </button>
            <div class="title-wrap">
                <div class="container">
                    <div class="meta">
                        <!-- 작성일 -->
                        <p class="date"><?=get_yyyymmdd($info["reg_dt"])?> - <?=$info["rc_idx"]?></p>
                        <!-- 총 즐겨찾기수 -->
                        <p class="total-bookmark">
                            <span class="sr-text">총 즐겨찾기수</span>
                            <em><?=$info["fav_cnt"]?></em>
                        </p>
                        <!-- 조회수 -->
                        <p class="total-view">
                            <span class="sr-text">조회수</span>
                            <em><?=$info["rc_hit"]?></em>
                        </p>
                    </div>
                    <!-- 제목 -->
                    <h2 class="view-title"><?=$info["rc_name"]?></h2>
					<!-- 모집기간 -->
                    <p class="recruit-period">
                        <span>모집기간</span>
                        <em><?=get_yyyymmdd($info["rc_sdate"])?> ~ <?=get_yyyymmdd($info["rc_edate"])?></em>
                    </p>			
                    <!-- 신고하기 -->
                    <button type="button" aria-haspopup="dialog" aria-controls="reportForm1" class="btn-report js-report"><span class="sr-text">신고하기</span></button>
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
			        			params["bd_idx"] = $("[name=rc_idx]").val();
			        			params["br_type"] = "recruit";
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

            <div class="summary">
                <div class="container">
                    <h3 class="secondary-title">구인정보</h3>
                    <table class="info-table">
                        <caption><span class="sr-text">구인정보</span></caption>
                        <colgroup><col><col></colgroup>
                        <tbody>
                            <tr>
                                <td class="label">업무구분</td>
                                <td class="value">
                                    <!-- 직종라벨 -->
                                    <?=getWorkingImg($info["rc_work"])?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">급여</td>
                                <td class="value">
                                    <?php if(strpos($info["rc_price_type"],"주급")!==false) : ?>
                                    <span class="pay">
                                        <em class="amount">급여협의</em>
                                    </span>
                                    <?php endif; ?>
                                    <?php if(strpos($info["rc_price_type"],"일급")!==false && strpos($info["rc_price_type"],"주급")===false && $info["rc_day_price"]!=0) :  ?>
                                    	<span class="pay">
	                                        <em class="amount"><?=number_format($info["rc_day_price"])?></em>
	                                        <span>(일급)</span>
	                                    </span>
                                    <?php endif; ?>
                                    <?php if(strpos($info["rc_price_type"],"월급")!==false && strpos($info["rc_price_type"],"주급")===false && $info["rc_month_price"]!=0) :  ?>
                                    	<span class="pay">
	                                        <em class="amount"><?=number_format($info["rc_month_price"])?></em>
	                                        <span>(월급)</span>
	                                    </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">근무형태</td>
                                <td class="value">
                                	<span class="working-time"><?=$info["rc_work_type"]?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">근무시간</td>
                                <td class="value">
                                    <?php if($info["rc_am_stime"]=="" && $info["rc_pm_stime"]=="") : ?>
                                        -
                                    <?php else : ?>
                                        <?php if(strpos($info["rc_work_type"],"2교대")!==false) : ?>

                                            <?php if($info["rc_am_stime"]!="" && $info["rc_am_etime"]) : ?>
                                                <span class="time"><?=$info["rc_am_stime"]?>~<?=$info["rc_am_etime"]?>(주간)</span>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>

                                            <?php if($info["rc_pm_stime"]!="" && $info["rc_pm_etime"]) : ?>
                                                <span class="time"><?=$info["rc_pm_stime"]?>~<?=$info["rc_pm_etime"]?>(야간)</span>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(strpos($info["rc_work_type"],"주간")!==false && strpos($info["rc_work_type"],"2교대")===false) :  ?>
                                            <?php if($info["rc_am_stime"]!="" && $info["rc_am_etime"]) : ?>
                                                <span class="time"><?=$info["rc_am_stime"]?>~<?=$info["rc_am_etime"]?>(주간)</span>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(strpos($info["rc_work_type"],"야간")!==false && strpos($info["rc_work_type"],"2교대")===false) :  ?>
                                            <?php if($info["rc_pm_stime"]!="" && $info["rc_pm_etime"]) : ?>
                                                <span class="time"><?=$info["rc_pm_stime"]?>~<?=$info["rc_pm_etime"]?>(야간)</span>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">희망국적</td>
                                <td class="value"><?=($info["rc_nation"]=="내국인,외국인" || trim($info["rc_nation"])==""?"무관":$info["rc_nation"])?></td>
                            </tr>
                            <tr>
                                <td class="label">희망성별</td>
                                <td class="value"><?=($info["rc_sex"]=="남자,여자" || trim($info["rc_sex"])==""?"무관":$info["rc_sex"])?></td>
                            </tr>
                            <tr>
                                <td class="label">희망나이</td>
                                <td class="value"><?php
                                  if($info["rc_sage"]=="0" && $info["rc_eage"]=="0"){
                                    echo "무관";
                                  }else{
                                    echo $info["rc_sage"]."세이상~".$info["rc_eage"]."세이하";
                                  }
                                ?></td>
                            </tr>
                            <tr>
                                <td class="label">근무요일</td>
                                <td class="value">
                                    <ul class="working-day">
                                        <?=getWeekYoil($info["rc_week"])?>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="section view-location">
            <div class="container">
                <h3 class="secondary-title">기본정보</h3>
                <div class="location">
                    <table class="info-table">
                        <tr>
                            <td class="label">상호명</td>
                            <td class="value">
                                <span><?=$mem_info["mm_comp_name"]?></span>
                            </td>
                        </tr>						
                        <tr>
                            <td class="label">연락처</td>
                            <td class="value">
                                <span><?=formatPhoneNumber($mem_info["mm_phone"])?></span>
                            </td>
                        </tr>						
                        <tr>
                            <td class="label">상세주소</td>
                            <td class="value">
                                <span><?=$addr["ad_doro"]?> <?=$addr["ad_detail"]?></span>
                            </td>
                        </tr>						
                    </table>
                    <?php if($g_ios_publish!=true ) : ?>
                    <a href="#findLocation" role="button" aria-haspopup="dialog" aria-controls="findLocation" class="btn-primary btn-round js-toggle-panel">길찾기</a>
                    <?php endif; ?>
                </div>
                <div class="map">
                    <!-- 회사 지도 좌표  -->
                    <input type="hidden" id="geocoder" value="<?=$addr["ad_gps_y"]?>,<?=$addr["ad_gps_x"]?>">
                    <div id="jsMap" class="js-map"></div>
                </div>

                <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>&libraries=services"></script>
                <script>
                    window.addEventListener('load', function () {
                        var mapContainer = document.getElementById('jsMap');
                        var geocoder = document.getElementById('geocoder').value;
                        var coords, map, marker;

                        if (!geocoder) {
                            mapContainer.parentNode.setAttribute('hidden','');
                            return;
                        }

                        geocoder = geocoder.split(',');
                        coords = new kakao.maps.LatLng(geocoder[0], geocoder[1]);

                        map = new kakao.maps.Map(mapContainer, {
                            center: coords,
                            level: 3,
                        });

                        map.setZoomable(false);

                        marker = new kakao.maps.Marker({
                            position: coords,
                            image: new kakao.maps.MarkerImage(
                               '/resource/shop/mobile/img/ic_loc_pin_on.svg',
                                new kakao.maps.Size(36, 47),
                                { offset: new kakao.maps.Point(16, 42) }
                            )
                        });

                        marker.setMap(map);
                    });
                </script>
            </div>
        </div>

        <div class="section view-benefit">
            <div class="container">
                <h3 class="secondary-title">복리후생</h3>
                <ul class="benefit-group">
                    <?=getLifeType($info["rc_life_type"],"-v2")?>
                </ul>
            </div>
        </div>

        <div class="section view-desc">
            <div class="container">
                <h3 class="secondary-title">상세업무정보</h3>
                <!-- 작성내용 -->
                <div class="desc" style="width: 100%; overflow-x: auto;">
                   <p><?=str_replace("\n","<br/>",$info["rc_desc"])?></p><br>
                </div>
				<div class="hashtag">
					<?=getHashTag($info["rc_hash"])?>
				</div>
            </div>
        </div>
        <div class="view-footer">
            <button id="btnRecruit" type="button" aria-haspopup="dialog" class="btn btn-secondary">지원하기</button>
            <?php //print_r($info); ?>
            <?php //echo $info[mm_phone]; ?>
            <!-- <button type="button" aria-haspopup="dialog" class="btn btn-primary" onclick="location.href='tel:<?=$info[mm_phone]?>';">전화하기</button> -->
            <a href="tel:<?=$info[mm_phone]?>" class="btn btn-primary">전화하기</a>
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
                            desc = ''; //document.querySelector('.view-desc .desc').textContent.replace(/\s/g,''),
                            imgUrl = 'https://nowjob.co.kr/resource/shop/web/img/nowjob_info.png'; //document.querySelector('.view-visual-slider img').getAttribute('src');
                            url = location.href,
                            appUrl = 'nowjob://recruit/recruit_detail/<?=$info["rc_idx"]?>'; //'scheme://host_path';

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

<!-- 길찾기 팝업 -->
<div id="findLocation" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="sr-text">길찾기 지도선택</h2>
            <button type="button" aria-controls="findLocation" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <div class="vendor-links">
                  <?php //todo //print_r($info);
                    //echo "[".$os_type."]";
                        if($os_type != "android"){ ?>
                     <a href="javascript:naverMap('<?=$info["mm_comp_name"]?>','<?=$addr["ad_gps_y"]?>','<?=$addr["ad_gps_x"]?>');" id="findNaverMap" class="vendor-link1">
                        <span class="ico" style="border:none;"><img src="/resource/shop/mobile/img/map_naver.png" alt=""></span>
                        <span>네이버</span>
                     </a>
                  <?php } ?>
                  <?php if(true){ ?>
                     <a href="javascript:kakaoMap('<?=$info["mm_comp_name"]?>','<?=$addr["ad_gps_y"]?>','<?=$addr["ad_gps_x"]?>');" class="vendor-link2">
                        <span class="ico" style="border:none;"><img src="/resource/shop/mobile/img/map_kakao.png" alt=""></span>
                        <span>카카오</span>
                     </a>
                  <?php } ?>
                  <?php if($os_type != "ios"){  ?>
                     <a href="javascript:tMap('<?=$info["mm_comp_name"]?>','<?=$addr["ad_gps_y"]?>','<?=$addr["ad_gps_x"]?>');" class="vendor-link3">
                        <span class="ico" style="border:none;"><img src="/resource/shop/mobile/img/mpa_tmap.png" alt=""></span>
                        <span>티맵</span>
                     </a>
                  <?php } ?>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- //길찾기 팝업 -->

<input type="hidden" name="rc_idx" value="<?=$info["rc_idx"]?>" />
<input type="hidden" name="rc_name" value="<?=$info["rc_name"]?>" />
<input type="hidden" name="resume_yn" value="<?=$resume ==null ? "N":"Y"?>" />
<script>
	var sel_lat = 0;
	var sel_lng = 0;

	//티맵 길안내
	function tMap(name,lat,lng){
		location.href = ("https://apis.openapi.sk.com/tmap/app/routes?appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41&name="+name+"&lon="+lng+"&lat="+lat);

    //var tmapUrl = 'tmap://route?rGoName='+encodeURIComponent(name)+'&rGoX='+lng+'&rGoY='+lat;
    //lng = 127.032423;
    //lat = 37.490928;
    //var tmapUrl = 'tmap://route?rGoName='+encodeURIComponent(name)+'&rGoX='+lng+'&rGoY='+lat;
    //alert(tmapUrl);
    //window.location.href = tmapUrl;

    //tmap://route?goalx=" + lon + "&goaly=" + lat + "&goalname=" + name + query;
    //?appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41&lat=37.0702312017&lon=126.8771905406&name=주식회사+부강제이엠
    //var url = "tmap://route?goalx=" + lng + "&goaly=" + lat + "&goalname=" + name + "&appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41";

    //tmap 중계 페이지에서 실행되는 코드
    // tmap://route?goalx=126.8771905406&goaly=37.0702312017&goalname=%EC%A3%BC%EC%8B%9D%ED%9A%8C%EC%82%AC %EB%B6%80%EA%B0%95%EC%A0%9C%EC%9D%B4%EC%97%A0&appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41
    //         tmap://route?goalx=126.8771906&goaly=37.07312017&goalname=%E%EC%9 7%A0&appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41
    //var url = "tmap://route?goalx=" + lng + "&goaly=" + lat + "&goalname=" + encodeURIComponent(name) + "&appkey=l7xx7179ddde21ca4bfb8e6b03c710138f41";
    //var url = 'tmap://route?goalx=126.8771905406&goaly=37.0702312017&goalname=%EC%A3%BC%EC%8B%9D%ED%9A%8C%EC%82%AC%EB%B6%80%EA%B0%95%EC%A0%9C%EC%9D%B4%EC%97%A0&appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41';
    //alert(url);
    //window.location = url;

	}

	//카카오맵 길안내
	function kakaoMap(name,lat,lng){
		var os_type ="<?=$os_type?>";
		if(os_type=="android"){
			sel_lat = lat;
			sel_lng = lng;
			APP_NOWAPK.getLocation();
	  }else if(os_type=="ios"){
	    	sel_lat = lat;
			  sel_lng = lng;

			//test window.webkit.messageHandlers.getLocation.postMessage({});
      //window.open("https://map.kakao.com/link/to/"+name+","+lat+","+lng);
      //var kakaoMapUrl = 'kakaomap://route?sp=' + startX + ',' + startY + '&ep=' + lat + ',' + lng + '&by=CAR';
      //var kakaoMapUrl = 'kakaomap://route?ep=' + lat + ',' + lng + '&by=CAR';

      //window.location.href = kakaoMapUrl;

      if (!Kakao.isInitialized()) {
          Kakao.init("<?=$kakao_key["인증키"]?>");
      }

      var targetname = name;
      Kakao.Navi.share({
        name: name,
        x: Number(lng),
        y: Number(lat),
        coordType: 'wgs84',
      });
      // Kakao.Navi.share({
      //   name: '현대백화점 판교점',
      //   x: 127.11205203011632,
      //   y: 37.39279717586919,
      //   coordType: 'wgs84',
      // });

		}else{
			window.open("https://map.kakao.com/link/to/"+name+","+lat+","+lng);
		}

		//location.href="kakaomap://route?sp=",127.005515&ep=37.4979502,127.0276368&by=" + name;
	}

	//네이버맵 길안내
	function naverMap(name,lat,lng){

    //네이버앱이 있으면 바로 실행
    // var url = 'nmap://route/walk?'+
    //          'dlat=' + lat +
    //         '&dlng=' + lng +
    //         '&dname=' + encodeURIComponent(name);
    //net::ERR_UNKOWN_URL_SCHEME

            // var url = 'nmap://route/walk?slat=' + startLat +
            //         '&slng=' + startLng +
            //         '&dlat=' + endLat +
            //         '&dlng=' + endLng +
            //         '&dname=' + encodeURIComponent(endName);

    // 네이버맵 앱 실행 시도
    //window.location.href = url;

    // 앱이 설치되어 있지 않은 경우를 대비해 타임아웃 설정
    // setTimeout(function() {
    //   // 웹 브라우저에서 네이버 길찾기 페이지 열기
    //   //window.location.href = 'https://map.naver.com/v5/directions/' + startLat + ',' + startLng + ',' + endLat + ',' + endLng + ',' + encodeURIComponent(endName);
    //
    //   location.href = ("https://app.map.naver.com/launchApp/?version=11&menu=navigation&elat="+lat+"&elng="+lng+"&etitle="+name);
    //
    // }, 2000);

		location.href = ("https://app.map.naver.com/launchApp/?version=11&menu=navigation&elat="+lat+"&elng="+lng+"&etitle="+name);
	}
</script>

<script>
function returnLocation(lat,lng){
  //안드로이드 앱에 위치를 요청한 후
  //콜백받는 부분임
    // 위치 권한이 없거나 거부된 경우 조용히 종료 (팝업 표시 안 함)
    if(lat=="0" && lng =="0"){
    	// 위치 권한이 없는 경우 팝업 없이 조용히 종료
    	return;
    }

	//location.href = ("kakaomap://route?sp="+sel_lat+","+sel_lng+"&ep="+lat+","+lng+"&by=CAR");
  location.href = ("kakaomap://route?sp="+lat+","+lng+"&ep="+sel_lat+","+sel_lng+"&by=CAR");
}
</script>

<script>
$(document).ready(function(){
	$('.bigsize').magnificPopup({
	    items: [
	    <?php if($info["rc_img1"]!="") : ?>
        {
	        src: '<?=$info["rc_img1"]?>'
	    },
        <?php endif; ?>
        <?php if($info["rc_img2"]!="") : ?>
        {
	        src: '<?=$info["rc_img2"]?>'
	    },
        <?php endif; ?>
        <?php if($info["rc_img3"]!="") : ?>
        {
	        src: '<?=$info["rc_img3"]?>'
	    },
        <?php endif; ?>
        <?php if($info["rc_img4"]!="") : ?>
        {
	        src: '<?=$info["rc_img4"]?>'
	    },
        <?php endif; ?>
        <?php if($info["rc_img5"]!="") : ?>
        {
	        src: '<?=$info["rc_img5"]?>'
	    },
        <?php endif; ?>
	    ],
	    gallery: {
	      enabled: true
	    },
	    type: 'image' // this is default type
	});

	var user_type = "<?=$this->user->getUserType();?>";
    $("#btnRecruit").click(function(){
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
					        setCookie('non_mem_last_cont', window.location.href, 1);
					        location.href="/member/join_start";
	            },
			      });
            $('.btn-close-popup').show();

            return;
        }

        if(user_type!="MEM"){
			commonAlert("기업 회원은 지원 할 수 없습니다.",function(){});
			return;
		}

    	//이력서 여부 확인
        if($("[name=resume_yn]").val()=="N"){
            commonAlert("이력서 작성및 공개이력서 설정후 지원해주세요.",function(){});
            return;
        }

        //$('.btn-close-popup').hide();
        openPopup('confirm', {
            content: '<em>“'+$("[name=rc_name]").val()+'”</em>으로<br> 지원하시겠습니까?',
            firstCallback: function () {
                $('.btn-close-popup').show();
            },
            secondCallback: function () {
                var params = {};
                var mq_idxs = [];
                mq_idxs.push($("[name=rc_idx]").val());
			          params["mq_idxs"] = mq_idxs;
 				        params["mq_type"] = "RECRUIT";
			          comAjax("/common_proc/member_request",params,function(ret){
                     console.log(ret);
                     $('.btn-close-popup').show();

			               if(ret.code=="00"){
			        	           commonAlert("지원 신청 되었습니다.",function(){
			        		                location.reload();
			        	           });
			               }else{
			                    commonAlert(ret.msg,function(){});
			                    return;
			               }
			          });
            }
        });
        $('.btn-close-popup').hide();
    });
});
</script>
