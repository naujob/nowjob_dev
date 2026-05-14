<div class="page page-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">채용공고</h1>
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

        <!-- 20220929 수정 -->
        <div class="section view-header">
            <!-- 즐겨찾기 -->
            <!--button type="button" data-bk_type="RECRUIT" data-bk_key="<?=$info["rc_idx"]?>"  class="btn-bookmark btn-view-bookmark js-toggle-bookmark">
                <span class="sr-text">즐겨찾기 추가</span>
            </button-->
            <div class="top-notice">
                <div class="container">
                    <div class="meta">
                        <!-- 작성일 -->
                        <p class="date"><?=get_yyyymmdd($info["reg_dt"])?></p>
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
                </div>
            </div>
            <div class="title-wrap">
                <div class="container">
                    <!-- 제목 -->
                    <h2 class="view-title"><?=$info["rc_name"]?></h2>
                    <!-- 모집기간 -->
                    <p class="recruit-period">
                        <span>모집기간</span>
                        <em><?=get_yyyymmdd($info["rc_sdate"])?> ~ <?=get_yyyymmdd($info["rc_edate"])?></em>
                    </p>
                </div>
            </div>
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
                                <td class="label">급여형태</td>
                                <td class="value"><span class="working-time"><?=$info["rc_price_type"]?></span></td>
                            </tr>
                            <tr>
                                <td class="label">급여</td>
                                <td class="value">
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
                                    <?php if(strpos($info["rc_price_type"],"월급")!==false && strpos($info["rc_price_type"],"주급")===false) :  ?>
                                    	<span class="pay">
	                                        <em class="amount"><?=number_format($info["rc_month_price"])?></em>
	                                        <span>(월급)</span>
	                                    </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="label">근무형태</td>
                                <td class="value"><span class="working-time"><?=$info["rc_work_type"]?></span></td>
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
                                <td class="value"><?=$info["rc_nation"]?></td>
                            </tr>
                            <tr>
                                <td class="label">희망성별</td>
                                <td class="value"><?=$info["rc_sex"]?></td>
                            </tr>
                            <tr>
                                <td class="label">희망나이</td>
                                <td class="value"><?=$info["rc_sage"]?>세이상~<?=$info["rc_eage"]?>세이하</td>
                            </tr>
                            <tr>
                                <td class="label">근무요일</td>
                                <td class="value">
                                    <ul class="working-day">
                                       	<?=getWeekYoil($info["rc_week"])?>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="value">
                                    <div class="hashtag">
                                        <?=getHashTag($info["rc_hash"])?>
                                    </div>
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
                        <caption><span class="sr-text">기본주소</span></caption>
                        <tr>
                            <td class="label">상세주소</td>
                            <td class="value">
                                <!-- 도로명 -->
                                <span><?=$addr["ad_doro"]?></span>
                                <!-- 나머지 주소 -->
                                <span><?=$addr["ad_detail"]?></span>
                            </td>
                        </tr>
                    </table>
                    <a href="#findLocation" role="button" aria-haspopup="dialog" aria-controls="findLocation" class="btn-primary btn-round js-toggle-panel">길찾기</a>
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
            <!-- 20220929 수정 -->
            <div class="top-notice">
                <div class="container">
                    <p class="help-msg">기업 연락처는 면접결정시 개인에 공유할수 있습니다.</p>
                </div>
            </div>
        </div>

        <div class="section view-benefit">
            <div class="container">
                <h3 class="secondary-title">복리후생</h3>
                <ul class="benefit-group">
                	<?=getLifeType($info["rc_life_type"])?>
                </ul>
            </div>
        </div>

        <div class="section view-desc">
            <div class="container">
                <h3 class="secondary-title">상세업무정보</h3>
                <!-- 작성내용 -->
                <div class="desc">
                    <p><?= $row["rc_desc"] ?></p><br>
                </div>
            </div>
        </div>

        <div class="view-footer">
            <button id="btnDelete" type="button" aria-haspopup="dialog" class="btn btn-secondary js-remove">삭제</button>
            <button id="btnEdit" type="button" class="btn btn-primary">수정</button>
        </div>

    </div><!-- //page-content -->
</div>

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
                    <a href="javascript:naverMap('<?=$info["rc_name"]?>','<?=$addr["ad_gps_y"]?>','<?=$addr["ad_gps_x"]?>');" id="findNaverMap" class="vendor-link1">
                        <span class="ico"><img src="/resource/shop/mobile/img/map_vendor1.jpg" alt=""></span>
                        <span>네이버</span>
                    </a>
                    <a href="javascript:kakaoMap('<?=$info["rc_name"]?>','<?=$addr["ad_gps_y"]?>','<?=$addr["ad_gps_x"]?>');" class="vendor-link2">
                        <span class="ico"><img src="/resource/shop/mobile/img/map_vendor2.jpg" alt=""></span>
                        <span>카카오</span>
                    </a>
                    <?php if($g_ios_publish!=true ) : ?>
                        <a href="javascript:tMap('<?=$info["rc_name"]?>','<?=$addr["ad_gps_y"]?>','<?=$addr["ad_gps_x"]?>');" class="vendor-link3">
                            <span class="ico"><img src="/resource/shop/mobile/img/map_vendor3.jpg" alt=""></span>
                            <span>티맵</span>
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- //길찾기 팝업 -->

<script>
	//티맵 길안내
	function tMap(name,lat,lng){
		window.open("https://apis.openapi.sk.com/tmap/app/routes?appKey=l7xx7179ddde21ca4bfb8e6b03c710138f41&name="+name+"&lon="+lng+"&lat="+lat);
	}

	//카카오맵 길안내
	function kakaoMap(name,lat,lng){
		window.open("https://map.kakao.com/link/to/"+name+","+lat+","+lng);
	}

	//네이버맵 길안내
	function naverMap(name,lat,lng){
		window.open("http://app.map.naver.com/launchApp/?version=11&menu=navigation&elat="+lat+"&elng="+lng+"&etitle="+name);
	}
</script>


<input type="hidden" name="rc_idx" value="<?=$info["rc_idx"]?>" />
<input type="hidden" name="rc_name" value="<?=$info["rc_name"]?>" />
<script>
	$(document).ready(function(){
		$("#btnDelete").click(function(){
			var rc_name = $("[name=rc_name]").val();
			var rc_idx = $("[name=rc_idx]").val();
            openPopup('confirm', {
                content: '<em>“'+rc_name+'”</em><br>채용공고를 삭제하시겠습니까?<br> 등록하신 모든 데이터는<br> 삭제됩니다.',
                firstButton: '취소',
                secondButton: '삭제',
                secondCallback: function () {
                    var params = {};
                    params["rc_idx"] = rc_idx;
		            comAjax("/compage_proc/recruit_delete",params,function(ret){
		                if(ret.code=="00"){
		                    location.replace("/compage/recruit_list");
		                }else{
		                    commonAlert(ret.msg,function(){});
		                    return;
		                }
		            });
                }
            });
		});

		$("#btnEdit").click(function(){
			comAjax("/compage_proc/recruit_start",{},function(ret){
                if(ret.code=="00" || ret.code=="01"){
                    location.href="/compage/recruit_write1_new/" + $("[name=rc_idx]").val();
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
		});
	});
</script>
