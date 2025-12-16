<?php

function fRemoveBigAdr($arg){
  //경기, 충남 등 도 이름 제거
  $arg = str_replace("경기 ", "", $arg);
  $arg = str_replace("강원 ", "", $arg);
  $arg = str_replace("충남 ", "", $arg);
  $arg = str_replace("충북 ", "", $arg);
  $arg = str_replace("경남 ", "", $arg);
  $arg = str_replace("경북 ", "", $arg);
  $arg = str_replace("전남 ", "", $arg);
  $arg = str_replace("전북 ", "", $arg);
  $arg = str_replace("제주 ", "", $arg);

  $parts = explode(" ", $arg);
  if(count($parts) === 3) {
    return $parts[0] . " " . $parts[2];
  } else {
    return $arg;
  }

  //return $arg;
}

function fWorkColor($arg){
  $arg = str_replace("주간", "<font style='color:#3a70db;'>주간</font>", $arg); //파랑
  $arg = str_replace("야간", "<font style='color:#F86048;'>야간</font>", $arg); //빨강
  $arg = str_replace("2교대", "<font style='color:#1B9D7D;'>2교대</font>", $arg); //민트
  $arg = str_replace("근무협의", "<font style='color:#333;'>근무협의</font>", $arg); //검정
  $arg = str_replace("협의", "<font style='color:#333;'>협의</font>", $arg); //검정
  return $arg;
}

function fHashSpace($rc_work,$arg){
  $arg = str_replace("<p class='tag'>#</p>", "", $arg);
  $arg = str_replace("<p class='tag'># </p>", "", $arg);

  $arg = str_replace("#", "&nbsp;&nbsp;#", $arg);

  $arg = str_replace("<p ", "<p style=\"color:#3a70db; background-color:transparent; padding-left:0;\" ", $arg);

  $work_array = explode(',', $rc_work);
  $formatted_work_array = array_map(function($work) use ($arg) {
    if(mb_strpos(trim($arg), '#'.trim($work).'</p>') === false){
      //return '&nbsp;&nbsp;#'.trim($work);
      return "<p style=\"color:#3a70db; background-color:transparent; padding-left:0;\" class='tag'>#".trim($work)."</p>";
    }
  }, $work_array);

  //$arg .= '<p style="color:#3a70db; background-color:transparent; padding-left:0;" class="tag">'.implode(' ', $formatted_work_array).'</p>';
  $arg .= implode("",$formatted_work_array);

  return $arg;
}

function fAddLink($data){
    // Use regex to replace http and https links with an anchor tag
    return preg_replace_callback(
        '/\\bhttps?:\\/\\/[^\\s()<>]+(?:\\([\w\d]+\\)|([^\\p{P}\\s]|\\/))/',
        function ($matches) {
            // For each match, return the anchor tag with the matched URL
            return '<a href="' . $matches[0] . '" target="_blank" style="color:blue;">' . $matches[0] . '</a>';
        },
        $data
    );
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
						<a href="javascript:void(0);" class="bigsize"><img src="/resource/shop/web/img/bigsize.png" /></a>
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
                                <p class="date"><?= date('Y-m-d', strtotime($info["reg_dt"])) ?> - <?=$info["rc_idx"]?></p>
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
                            <!-- 신고하기 -->
                            <button type="button" aria-haspopup="dialog" aria-controls="reportForm1" class="btn-report js-report"><span class="sr-text">신고하기</span></button>
                        </div>

                        <!-- 신고하기 양식 -->
                        <template id="ReportTemp">
                            <div class="alert-msg">
                                <p>해당 게시글을 신고하시겠습니까?<br> 신고사유를 작성해주세요</p>
                                <div class="report-form">
                                    <label>
                                        <span class="sr-text">신고사유입력</span>
                                        <textarea name="br_desc" placeholder="신고사유" autofocus class="form-textarea js-autoheight"></textarea>
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
                            <h3 class="secondary-title">채용정보</h3>
                            <table class="info-table">
                                <caption><span class="sr-text">채용정보</span></caption>
                                <colgroup><col><col><col><col></colgroup>
                                <tbody>
                                    <tr>
                                        <td class="label">업무구분</td>
                                        <td class="value"><?=getWorkingImg($info["rc_work"])?></td>
                                        <td class="label">희망국적</td>
                                        <td class="value"><?=($info["rc_nation"]?$info["rc_nation"]:"무관")?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">급여형태</td>
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
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="section view-desc">
                        <h3 class="secondary-title">상세업무정보</h3>
                        <!-- 작성내용 -->
                        <div class="desc" style="clear:both;">
                            <?php //=str_replace("src=\"h-ttps","src=\"https",fAddLink(str_replace("\n","<br/>",str_replace("src=\"https","src=\"h-ttps",$info["rc_desc"]))))?>
                            <?=str_replace("src=\"h-ttps","src=\"https",str_replace("\n","<br/>",str_replace("src=\"https","src=\"h-ttps",$info["rc_desc"])))?>
							<div class="hashtag">
								<?=getHashTag(convertHashtagString($info["rc_hash"]))?>
							</div>
                        </div>
                    </div>

                    <?php if($info["rc_life_type"]!=""){ ?>
                    <div class="section view-benefit">
                        <h3 class="secondary-title">복리후생</h3>
                        <ul class="benefit-group">
                            <?=getLifeType($info["rc_life_type"],"-v2")?>
                        </ul>
                    </div>
                    <?php } ?>

                    <div class="section view-ext">
                        <div class="inline-group">
                            <div class="col lt">
                                <h3 class="secondary-title">근무시작일</h3>
                                <div class="calendar" style="<?php /* =($info["rc_work_dt"]=="0000-00-00 00:00:00"?"display:none;":"") */ ?>">
                                    <div class="js-datepicker"></div>
                                    <input type="hidden" name="startDate" value="<?=$info["rc_work_dt"]?>" aria-required="true" required class="js-date-value">
                                    <?php if($info["rc_work_dt"]=="0000-00-00 00:00:00"){ ?>
                                      <script>
                                      $(document).ready(function() {
                                          // 1초(1000밀리초) 후에 .flatpickr-prev-month 요소를 숨깁니다.
                                          setTimeout(function() {
                                            $('.flatpickr-prev-month').hide();
                                            $('.flatpickr-next-month').hide();
                                          }, 500); // 1000밀리초 = 1초
                                      });
                                      </script>
                                      <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #000; opacity: 0.2; z-index: 9;">&nbsp;</div>
                                    <?php } ?>
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
                                            <?php if($info["rc_work_dt"]=="0000-00-00 00:00:00"){ ?>
                                              var today = new Date();
                                              defaultDate = today; //today.getFullYear() + '년 ' + (today.getMonth() + 1) + '월 ' + today.getDate() + '일 ' + weekday[day] + '요일'
                                            <?php } ?>

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
                                <h3 class="secondary-title" style="position:relative;">상세지도
                                	<a target="_blank" href="https://map.kakao.com/link/to/<?=$mem_info["mm_comp_name"]?>,<?=$addr["ad_gps_y"]?>,<?=$addr["ad_gps_x"]?>" style="position:absolute;background:#1B9D7D;color:#fff;border-radius:5px;border:solid 1px #1B9D7D;font-size:13px;padding:4px 10px;z-index:999;top:50px;right:7px;">길찾기</a>
                                </h3>
                                <div class="map" style="position:relative; height: 280px;">
                                    <!-- 회사 지도 좌표  -->
                                    <input type="hidden" id="geocoder" value="<?=$addr["ad_gps_y"]?>,<?=$addr["ad_gps_x"]?>">
                                    <div id="jsMap" class="js-map"></div>
                                </div>
                                <span style=" width: 100%; height: 24px; font-size: 14px; position: absolute; bottom: 16px; z-index: 10;">
                                      · 위 정보는 근무지를 기반으로 자동매칭된 정보가 아닌, 기업 회원이 직접 입력한 정보입니다.
                                </span>
                                <span style=" width: 100%; height: 24px; font-size: 14px; position: absolute; bottom: -6px; z-index: 10;">
                                      · 지도는 근무지 위치를 나타내며 회사 소재지와 일치하지 않을 수 있습니다.
                                </span>

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
                                <tr>
                                    <td class="label">연락처</td>
                                    <td class="value"><?=formatPhoneNumber($mem_info["mm_phone"])?></td>
                                </tr>								
                            </tbody>
                        </table>
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
                                    <?=getWorkingImg($info["rc_work"],2)?>
                                </div>

                                <!-- 공유하기 -->
                                <div class="share-wrap">
                                    <button type="button" class="btn-share js-toggle-dropdown"><span class="sr-text">공유하기</span></button>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);" onclick="shareMessage();return false;" class="share-link1">
                                            <span class="ico"><img src="/resource/shop/web/img/ic_share_vendor1.svg" alt=""></span>
                                            <span>카카오톡</span>
                                        </a>
                                        <a href="javascript:void(0);" onclick="CopyUrl();return false;" class="share-link2">
                                            <span class="ico"><img src="/resource/shop/web/img/ic_share_copy_url.svg" alt=""></span>
                                            <span>URL복사</span>
                                        </a>
                                    </div>

                                    <script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script>
                                    <script>
                                        /* 카카오톡 공유 */
                                        function shareMessage() {
                                            if (!Kakao.isInitialized()) {
                                                //Kakao.init("a0a27ea14e36a89cbe2de50dc61323b8");
                                                Kakao.init("844a775ddd4bfd3e56f838762708ef04");
                                            }

                                            var title = document.querySelector('.view-title').textContent.replace(/\s/g,''),
                                                desc = '';//document.querySelector('.view-desc .desc').textContent.replace(/\s/g,''),
                                                imgUrl = 'https://nowjob.co.kr/resource/shop/web/img/nowjob_info.png';//document.querySelector('.view-visual .visual-slider img').getAttribute('src');
                                                url = location.href,
                                                appUrl = 'scheme://host_path';

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
                                <button type="button" data-bk_type="RECRUIT" data-bk_key="<?=$info["rc_idx"]?>" ata-user_id="<?=$mem_info["mm_id"]?>"  class="btnBookMark btn-view-bookmark js-toggle-bookmark <?=$book_yn=="Y" ? "on":""?>">
					                <span class="sr-text">즐겨찾기 추가</span>
					            </button>
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
                              <?php if($info["rc_day_price"]=="0"){ ?>
                                <p class="amount"><em></em></p>
                              <?php }else{ ?>
                                <p class="amount"><em><?=number_format($info["rc_day_price"])?></em>원</p>
                              <?php } ?>
                            <?php endif; ?>
                            <?php if(strpos($info["rc_price_type"],"월급")!==false && strpos($info["rc_price_type"],"주급")===false && $info["rc_month_price"]!=0) :  ?>
                            	<p class="meta"><em>월급</em></p>
                              <?php if($info["rc_month_price"]=="0"){ ?>
                                <p class="amount"><em></em></p>
                              <?php }else{ ?>
                                <p class="amount"><em><?=number_format($info["rc_month_price"])?></em>원</p>
                              <?php } ?>
                            <?php endif; ?>
                            <?php if($this->user->getUserType()=="COMP"){ ?>
                              <button id="btnRecruit" type="button" aria-haspopup="dialog" class="btn-request btn-primary" style="background-color:#ccc;" disabled>지원하기</button>
                            <?php }else{ ?>
                              <button id="btnRecruit" type="button" aria-haspopup="dialog" class="btn-request btn-primary">지원하기</button>
                            <?php } ?>
                        </div>

                        <!-- 히스토리 목록 -->
                        <div class="recent-history" style="width: 100%;">
                            <div class="history-header">
                                <p class="title">내가 본 공고</p>
                            </div>
                            <div class="history-body">
                                <div class="recently-slider keen-slider">
                                    <?php foreach($view_list as $row) : ?>
                                    <!-- 목록 -->
                                    <div class="keen-slider__slide">
                                        <div class="summary-card">
                                            <div class="inline-group">
                                                <!-- 카테고리 라벨 -->
                                                <?=getWorkingImg($row["rc_work"])?>
                                                <!-- 지역 -->
                                                <p class="area"><?=fRemoveBigAdr($row["ad_addr"])?></p>
                                            </div>
                                            <p class="title">
                                                <a href="/recruit/job_post/<?=$row["rc_idx"]?>"><?=$row["rc_name"]?></a>
                                            </p>
                                            <?php if(strpos($row["rc_price_type"],"주급")!==false) : ?>
					                            <p class="meta"><em></em></p>
					                            <p class="amount"><em>협의가능</em></p>
				                            <?php endif; ?>
				                            <?php if(strpos($row["rc_price_type"],"일급")!==false && strpos($row["rc_price_type"],"주급")===false) :  ?>
				                            	<p class="meta"><em>일급</em></p>
                                      <?php if($row["rc_day_price"]=="0"){  ?>
                                        <p class="amount" style="height:18px;"></p>
                                      <?php }else{  ?>
                                        <p class="amount"><em><?=number_format($row["rc_day_price"])?></em>원</p>
                                      <?php }  ?>
				                            <?php endif; ?>
				                            <?php if(strpos($row["rc_price_type"],"월급")!==false && strpos($row["rc_price_type"],"주급")===false) :  ?>
				                            	<p class="meta"><em>월급</em></p>
                                      <?php if($row["rc_month_price"]=="0"){  ?>
                                        <p class="amount" style="height:18px;"></p>
                                      <?php }else{  ?>
                                        <p class="amount"><em><?=number_format($row["rc_month_price"])?></em>원</p>
                                      <?php }  ?>
				                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
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

                <!-- 하단 관련 공고 -->
                <div class="recomm-slider recruit">
                    <h3 class="sr-text">관련공고</h3>
					<div class="slider-wrap keen-slider swiper-wrapperX" id="my-keen-slider" style="width: calc(100% - 0px);">
					</div>
                </div>
                <!-- //하단 관련 공고 -->

            </div>
        </div>

    </main><!-- //page-content -->
</div><!-- //page -->
<style>
.recruit-card .type em {
    display: inline-block;
    font-style: normal;
    font-weight: 500;
    color: #1B9D7D;
}
</style>
<!-- 추천 리스트 템플릿 -->
<template id="tpl_reco_list">
    <div class="keen-slider__slide">
        <div class="recruit-card">
            <div class="thumb-wrap">
                <!-- p class="recomm-badge">추천</p -->
                <div class="thumb">
                    <a href="/recruit/job_post/{{rc_idx}}" target="_blank"><img src="{{rt_recruit_img}}" alt="" class=""></a>
                </div>
                <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" data-user_id="{{mm_id}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button>
            </div>
            <div class="content-wrap">
                <div class="inline-group">
                    {{rt_work_img}}
                    <p class="area">{{ad_addr}}</p>
                </div>
                <p class="title"><strong><a href="/recruit/job_post/{{rc_idx}}" target="_blank">{{rc_name}}</a></strong></p>
                <div class="meta">
                    <p class="type">{{rt_work_type}}</p>
                    <p>{{rc_nation_nm}}</p>
                    <p>{{rc_age_nm}}</p>
                </div>
                <div class="price-wrap" style="position:relative;">
                    <p class="type">{{rt_price_type}}</p>
                    <p class="amount">{{rt_price}}</p>
                </div>
                <div class="hashtag">
                    {{rt_hash}}
                </div>
            </div>
        </div>
    </div>
</template>

<input type="hidden" name="rc_idx" value="<?=$info["rc_idx"]?>" />
<input type="hidden" name="rc_name" value="<?=$info["rc_name"]?>" />
<input type="hidden" name="resume_yn" value="<?=$resume ==null ? "N":"Y"?>" />
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

	var check_user_type = "<?=$this->user->getUserType();?>";
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


            return;
        }

        if(check_user_type!="MEM"){
			commonAlert("기업 회원은 지원 할 수 없습니다.",function(){});
			return;
		}

    	//이력서 여부 확인
        if($("[name=resume_yn]").val()=="N"){
            commonAlert("이력서 작성및 공개이력서 설정후 지원해주세요.",function(){});
            return;
        }

        //오늘 지원여부 확인
        var paramsToday = {};
        paramsToday["rc_idx"] = $("[name=rc_idx]").val();

        comAjax("/recruit_proc/todayapply",paramsToday,function(ret){

             if(ret.code=="00"){
               openPopup('confirm', {
                   content: '<em>“'+$("[name=rc_name]").val()+'”</em>으로<br> 지원하시겠습니까?',
                   secondCallback: function () {
                       var params = {};
                       var mq_idxs = [];
                       mq_idxs.push($("[name=rc_idx]").val());
       			          params["mq_idxs"] = mq_idxs;
        				        params["mq_type"] = "RECRUIT";

                       comAjax("/common_proc/member_request",params,function(ret){
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

             }else if(ret.code=="01"){
                 commonAlert("<em>다음날</em> 다시 지원이 가능합니다..",function(){});
                 return;
             }else{
                 commonAlert(ret.msg,function(){});
                 return;
             }
        });

    });
	
	//추천공고
	function getRecoList(){
		var params = {};
		params["addr1"] = $("[name='searchArea']:checked").val()=="전국" ? "":$("[name='searchArea']:checked").val();
		params["addr2"] = $("[name='searchArea2']:checked").val();
		params["addr3"] = $("[name='searchArea3']:checked").val();
		params["comp_idx"] = <?=$info["mm_idx"]?>;
		params["rc_idx"] = <?=$info["rc_idx"]?>;
		params["limit"] = 4;
		comAjax("/recruit_proc/search_comp_reco_list2025",params,function(ret){
			if(ret.code=="00"){
				var list = ret.data.list;
				$("#my-keen-slider").html("");
				$(".arrow--left").remove();
				$(".arrow--right").remove();
				list.forEach(function(row){
					var $clone  = $("#tpl_reco_list").clone();
					var $content = $clone.html();
					
					if(row.rc_nation_nm=="무관"||row.rc_nation_nm=="내국인,외국인"){
						$content = $content.replaceAll("{{rc_nation_nm}}","국적무관");
					}else {
						$content = $content.replaceAll("{{rc_nation_nm}}",row.rc_nation_nm.replace("만", ""));
					}				
					
					$content = $content.replaceAll("{{rc_idx}}",row.rc_idx);
					$content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");
					$content = $content.replaceAll("{{ad_addr}}",fRemoveBigAdrJS(row.ad_addr));
					$content = $content.replaceAll("{{rc_name}}",row.rc_name);
					$content = $content.replaceAll("{{distance}}",row.distance + "km")
					$content = $content.replaceAll("{{rc_age_nm}}",getConvertAgeLabel(row.rc_age_nm));

					$content = $content.replaceAll("{{rt_recruit_img}}",row.rt_recruit_img);

					var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
					$images.slice(2).remove();
					var $categoryHtml = $images.parent().html();
					$content = $content.replaceAll("{{rt_work_img}}", $categoryHtml);
					$content = $content.replaceAll("{{rt_work_type}}",row.rt_work_type);
					if(row.rt_price_type.indexOf('급여협의') > -1){
						$content = $content.replaceAll("{{rt_price_type}}","<em> </em>");
					}else{
						$content = $content.replaceAll("{{rt_price_type}}",row.rt_price_type);
					}
					$content = $content.replaceAll("{{rt_price}}",row.rt_price);
					$content = $content.replaceAll("{{mm_id}}",row.mm_id);
					$content = $content.replaceAll("{{rt_hash}}",getHashTagJS(row.rt_hash,3,row.rc_work));
					
					
					$content = $content.replaceAll("{{timestamp}}",row.timestamp);

					$clone.html($content);

					$("#my-keen-slider").append($clone.html());
				});
				
				var slideCount = document.querySelectorAll('.recomm-slider .slider-wrap .keen-slider__slide').length;
				var perView = slideCount < 4 ? slideCount : 4;
				var slider = new KeenSlider(".recomm-slider .slider-wrap", {
					loop: false,
					slides: { perView: 4 },
				}, [
					addActiveClass
				]);

				var interval = 0;
				var sliderElement = document.getElementById("my-keen-slider");
				function autoplay(run) {
				    clearInterval(interval);
				    interval = setInterval(() => {
				      if (run && slider) {
				        slider.next();
				      }
				    }, 3400);
				}

				sliderElement.addEventListener("mouseover", () => {
					//autoplay(false);
				});
				sliderElement.addEventListener("mouseout", () => {
					//autoplay(true);
				});
				//autoplay(true);
			}else{

			}
		});
	}

	function fRemoveBigAdrJS(arg) {

		const parts1 = arg.split(",");
		arg = parts1[0];

		arg = arg.replace("서울특별시 ", "");
		arg = arg.replace("경기 ", "");
		arg = arg.replace("강원 ", "");
		arg = arg.replace("충남 ", "");
		arg = arg.replace("충북 ", "");
		arg = arg.replace("경남 ", "");
		arg = arg.replace("경북 ", "");
		arg = arg.replace("전남 ", "");
		arg = arg.replace("전북 ", "");
		arg = arg.replace("제주 ", "");
		arg = arg.replace("경기도 ", "");
		arg = arg.replace("강원특별자치도 ", "");
		arg = arg.replace("충청남도 ", "");
		arg = arg.replace("충청북도 ", "");
		arg = arg.replace("경상남도 ", "");
		arg = arg.replace("경상북도 ", "");
		arg = arg.replace("전라남도 ", "");
		arg = arg.replace("전북특별자치도 ", "");
		arg = arg.replace("제주특별자치도 ", "");
		arg = arg.replace("세종특별자치시 ", "세종시 ");
		arg = arg.replace("광주광역시 ", "광주시 ");
		arg = arg.replace("인천광역시 ", "인천시 ");
		arg = arg.replace("울산광역시 ", "울산시 ");
		arg = arg.replace("부산광역시 ", "부산시 ");
		arg = arg.replace("대구광역시 ", "대구시 ");
		arg = arg.replace("대전광역시 ", "대전시 ");

	  const parts = arg.split(" ");
	  if (parts.length >= 3) {
	    return parts[0] + " " + parts[2];
	  } else {
	    return arg;
	  }
	}
	
	function getConvertAgeLabel(age) {
		let cleaned = age.replaceAll(" 이상", "").replaceAll(" 이하", "");
		if (!age.includes("나이무관")) {
			if (!age.includes("이상")) {
				cleaned = "~ " + cleaned;
			}
			if (!age.includes("이하")) {
				cleaned = cleaned + " ~";
			}
		}
		return cleaned;
	}
	
	function getHashTagJS(hash, max = 10, rc_work) {
		let result = "";
		let tmp = [...hash.matchAll(/<p[^>]*>#?([^<]+)<\/p>/g)]
		  .map(match => match[1])
		  .join(',');
		  
		let arr_hash = tmp.split(",");
		let arr_work = rc_work.split(",");
		arr_work.forEach(function(txt, key) {
			if (key < max) {
				if (txt.trim() !== "") {
					arr_hash.push(txt);
				}
			}
		});
		
		arr_hash.forEach(function(txt, key) {
			if (key < max) {
				if (!txt.includes("#") && txt.trim() !== "") {
					txt = "#" + txt.trim();
				}
				if (txt.trim() !== "") {
					result += ` <p class='tag'>${txt}</p>`;
				}
			}
		});
	  
		return result;
	}	
	
	getRecoList();	
	
});
</script>
