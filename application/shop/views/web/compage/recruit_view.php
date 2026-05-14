<div id="content" class="page page-view">
    <main class="page-content">
        <div class="item-view recruit">
            <div class="container">
                <!-- 컨텐츠 영역 -->
                <div class="view-content">
                    <div class="view-visual">
                        <div class="inline-group">
                            <div class="visual-slider keen-slider">
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
                            <div class="thumb-slider">
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
                        </div>

                        <script>
                            function ThumbnailPlugin(main) {
                                return function (slider) {
                                    function removeActive() {
                                        slider.slides.forEach(function (slide) {
                                            slide.classList.remove('active')
                                        });
                                    }

                                    function addActive(idx) {
                                        slider.slides[idx].classList.add('active');
                                    }

                                    function addClickEvents() {
                                        slider.slides.forEach(function (slide, idx) {
                                            slide.addEventListener('click', function () {
                                                main.moveToIdx(idx);
                                            });
                                        })
                                    }

                                    slider.on('created', function () {
                                        addActive(slider.track.details.rel);
                                        addClickEvents();
                                        main.on('animationStarted', function (main) {
                                            removeActive();
                                            var next = main.animator.targetIdx || 0;
                                            addActive(main.track.absToRel(next));
                                            slider.moveToIdx(Math.min(slider.track.details.maxIdx, next));
                                        });
                                    });
                                };
                            }

                            window.addEventListener('DOMContentLoaded', function () {
                                if (typeof KeenSlider === 'undefined') return;

                                var mslider = new KeenSlider('.visual-slider', {
                                    loop: true,
                                    defaultAnimation: { duration: 100, },
                                    drag: false,
                                    detailsChanged: function (s) {
                                        s.slides.forEach(function (element, idx) {
                                            element.style.opacity = s.track.details.slides[idx].portion;
                                        });
                                    },
                                    renderMode: 'custom',
                                }, [addActiveClass]);
                                var thumbnails = new KeenSlider( ".thumb-slider .slider-wrap", {
                                        initial: 0,
                                        slides: { perView: 4, },
                                        vertical: true,
                                        drag: false,
                                    },
                                    [ThumbnailPlugin(mslider), sliderArrows]
                                );
                            });
                        </script>
                    </div>

                    <div class="section view-header">
                        <div class="view-meta">
                            <div class="inline-group">
                                <!-- 작성일 -->
                                <p class="date"><?=$info["reg_dt"]?></p>
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

                        <div class="title-wrap">
                            <!-- 제목 -->
                            <h2 class="view-title"><?=$info["rc_name"]?></h2>
                            <!-- 모집기간 -->
                            <p class="recruit-period">
                                <span>모집기간</span>
                                <em><?=get_yyyymmdd($info["rc_sdate"])?> ~ <?=get_yyyymmdd($info["rc_edate"])?></em>
                            </p>
                        </div>

                        <div class="summary">
                            <h3 class="secondary-title">구인정보</h3>
                            <table class="info-table">
                                <caption><span class="sr-text">구인정보</span></caption>
                                <colgroup><col><col><col><col></colgroup>
                                <tbody>
                                    <tr>
                                        <td class="label">업무구분</td>
                                        <td class="value"><?=getWorkingImg($info["rc_work"])?></td>
                                        <td class="label">희망국적</td>
                                        <td class="value"><?=($info["rc_nation"]?$info["rc_nation"]:"무관")?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">급 여</td>
                                        <td class="value txt-color2">
                                        	<?php if(strpos($info["rc_price_type"],"주급")!==false) : ?>
		                                    <span class="pay">
		                                        <em class="amount">협의가능</em>
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
                                        <td class="label">희망성별</td>
                                        <td class="value"><?=($info["rc_sex"]?$info["rc_sex"]:"무관")?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">근무형태</td>
                                        <td class="value txt-color2"><?=getWorkTypeV2($info["rc_work_type"])?></td>
                                        <td class="label">희망나이</td>
                                        <td class="value">
                                            <?php if($info["rc_sage"] > 0 || $info["rc_eage"] > 0) : ?>
                                                <?=$info["rc_sage"]?>세이상~<?=$info["rc_eage"]?>세이상
                                            <?php else : ?>
                                                무관
                                            <?php endif; ?>
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
                                        <td class="label">근무요일</td>
                                        <td class="value">
                                          <?php if($info["rc_week"]==""){ ?>
                                            무관
                                          <?php }else{ ?>
                                            <ul class="working-day">
                                                <?=getWeekYoil($info["rc_week"])?>
                                            </ul>
                                          <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><span class="sr-text">해시</span></td>
                                        <td colspan="2">
                                            <div class="hashtag">
                                                <?=getHashTag($info["rc_hash"])?>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="section view-location">
                        <h3 class="secondary-title">기본정보</h3>
                        <table class="info-table">
                            <caption><span class="sr-text">상호</span></caption>
                            <colgroup><col><col></colgroup>
                            <tbody>
                                <tr>
                                    <td class="label">근무지 주소</td>
                                    <td class="value"><?=$addr["ad_doro"]?> <?=$addr["ad_detail"]?></td>
                                </tr>
                                <tr>
                                    <td class="label">상호</td>
                                    <td class="value"><?=$mem_info["mm_comp_name"]?></td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="help-msg">기업 연락처는 면접결정시 개인에 공유할수 있습니다.</p>
                    </div>

                    <div class="section view-desc">
                        <h3 class="secondary-title">상세업무정보</h3>
                        <!-- 작성내용 -->
                        <div class="desc">
                            <?= $info["rc_desc"] ?>
                        </div>
                    </div>

                    <div class="section view-benefit">
                        <h3 class="secondary-title">복리후생</h3>
                        <ul class="benefit-group">
                            <?=getLifeType($info["rc_life_type"],"-v2")?>
                        </ul>
                    </div>

                    <div class="section view-ext">
                        <div class="inline-group">
                            <div class="col lt">
                                <h3 class="secondary-title">근무시작일</h3>
                                <div class="calendar" style="<?=($info["rc_work_dt"]=="0000-00-00 00:00:00"?"display:none;":"")?>">
                                    <div class="js-datepicker"></div>
                                    <input type="hidden" name="startDate" value="<?=$info["rc_work_dt"]?>" aria-required="true" required class="js-date-value">
                                </div>
                                <p class="startdate"><?=($info["rc_work_dt"]=="0000-00-00 00:00:00"?"근무시작일 무관":"")?></p>

                                <script>
                                    (function () {
                                        function chgMonth (instance) {
                                            var $con = instance.calendarContainer.querySelector('.flatpickr-month'),
                                                month = Number(instance.currentMonth) + 1,
                                                year = instance.currentYear;
                                            $con.innerHTML = '<span class="month">' + month + '<small>월</small></span><span class="year">' + year + '</span>';
                                        }

                                        window.addEventListener('DOMContentLoaded', function () {
                                            if (typeof flatpickr == 'undefined') return false;

                                            var defaultDate = document.querySelector('.js-date-value').value;

                                            var weekday = ['일','월','화','수','목','금','토'];

                                            //달력 생성
                                            flatpickr('.calendar .js-datepicker', {
                                                inline: true,
                                                locale: 'ko',
                                                dateFormat: 'Y-m-d',
                                                ariaDateFormat: 'Y-m-d',
                                                defaultDate: defaultDate,
                                                nextArrow: '<span class="sr-text">다음</span>',
                                                prevArrow: '<span class="sr-text">이전</span>',
                                                disableMobile: true,
                                                enable: [{from: defaultDate, to: defaultDate}],
                                                onMonthChange: function (selectedDates, dateStr, instance) {
                                                    chgMonth(this);
                                                },
                                                onDayCreate: function(dObj, dStr, fp, dayElem){
                                                    var day = new Date(dayElem.getAttribute('aria-label')).getDay();
                                                    if (day == 0) {
                                                        dayElem.classList.add('sun');
                                                    } else if (day == 6) {
                                                        dayElem.classList.add('sat');
                                                    }
                                                },
                                                onReady: function () {
                                                    chgMonth(this);

                                                    var dday = new Date(defaultDate);
                                                    var day = new Date(defaultDate).getDay();

                                                    <?php if($info["rc_work_dt"]!="0000-00-00 00:00:00"){ ?>
                                                    document.querySelector('.startdate').
                                                    innerHTML = dday.getFullYear() + '년 ' + (dday.getMonth() + 1) + '월 ' + dday.getDate() + '일 ' + weekday[day] + '요일'
                                                    <?php } ?>

                                                }
                                            });
                                        });
                                    }());
                                </script>
                            </div>
                            <div class="col rt">
                                <h3 class="secondary-title">상세지도</h3>
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
                                                '/resource/shop/web/img/ic_loc_pin2.svg',
                                                new kakao.maps.Size(56, 74),
                                                { offset: new kakao.maps.Point(38, 56) }
                                            )
                                        });

                                        marker.setMap(map);
                                    });
                                </script>

                            </div>
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
                                    <?=getWorkingImg($info["rc_work"])?>
                                    <!-- 지역 -->
                                    <p class="area"><?=$addr["ad_addr"]?></p>
                                </div>
                            </div>
                            <!-- 타이틀 -->
                            <p class="title"><?=$info["rc_name"]?></p>
                            <!-- 지급정보 -->
                            <?php if(strpos($info["rc_price_type"],"주급")!==false) : ?>
	                            <p class="meta"><em></em></p>
	                            <p class="amount"><em>협의가능</em></p>
                            <?php endif; ?>
                            <?php if(strpos($info["rc_price_type"],"일급")!==false && strpos($info["rc_price_type"],"주급")===false && $info["rc_day_price"]!=0) :  ?>
                            	<p class="meta"><em>일급</em></p>
                            	<p class="amount"><em><?=number_format($info["rc_day_price"])?></em>원</p>
                            <?php endif; ?>
                            <?php if(strpos($info["rc_price_type"],"월급")!==false && strpos($info["rc_price_type"],"주급")===false && $info["rc_month_price"]!=0) :  ?>
                            	<p class="meta"><em>월급</em></p>
                            	<p class="amount"><em><?=number_format($info["rc_month_price"])?></em>원</p>
                            <?php endif; ?>
                        	</td>
                        </div>
                    </div>
                </div>
                <!-- //aside -->
            </div>
        </div>

        <div class="view-footer">
            <div class="container">
                <div class="form-action-wrap">
                    <button id="btnDelete" type="button" aria-haspopup="dialog" class="btn-round btn-secondary">삭제</button>
            		<button id="btnEdit" type="button" class="btn-round btn-tertiary">수정</button>
                </div>
            </div>
        </div>

    </main><!-- //page-content -->
</div><!-- //page -->



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
                    location.href="/compage/recruit_write/" + $("[name=rc_idx]").val();
                }else{
                    commonAlert(ret.msg,function(){});
                    return;
                }
            });
		});
	});
</script>
