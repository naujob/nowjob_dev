<?php
$banner_list = getBannerList("BN00010005");

function fWorkColor($arg)
{
    // $arg = str_replace("주간", "<font style='color:#3a70db;font-weight:600;'>주간</font>", $arg); //파랑
    // $arg = str_replace("야간", "<font style='color:#F86048;font-weight:600;'>야간</font>", $arg); //빨강
    // $arg = str_replace("2교대", "<font style='color:#1B9D7D;font-weight:600;'>2교대</font>", $arg); //민트
    // $arg = str_replace("근무협의", "<font style='color:#333;font-weight:600;'>근무협의</font>", $arg); //검정
    // $arg = str_replace("협의", "<font style='color:#333;font-weight:600;'>협의</font>", $arg); //검정
    $arg = str_replace("주간", "<font style='color:#333;font-weight:600;'>주간</font>", $arg); //파랑
    $arg = str_replace("야간", "<font style='color:#333;font-weight:600;'>야간</font>", $arg); //빨강
    $arg = str_replace("2교대", "<font style='color:#333;font-weight:600;'>2교대</font>", $arg); //민트
    $arg = str_replace("근무협의", "<font style='color:#333;font-weight:600;'>근무협의</font>", $arg); //검정
    $arg = str_replace("협의", "<font style='color:#333;font-weight:600;'>협의</font>", $arg); //검정
    return $arg;
}

function fWorkColorTop($arg)
{
    $arg = str_replace("주간", "<font style='color:#fff700;font-weight:600;'>주간</font>", $arg); //파랑
    $arg = str_replace("야간", "<font style='color:#fff700;font-weight:600;'>야간</font>", $arg); //빨강
    $arg = str_replace("2교대", "<font style='color:#fff700;font-weight:600;'>2교대</font>", $arg); //민트
    $arg = str_replace("근무협의", "<font style='color:#fff700;font-weight:600;'>근무협의</font>", $arg); //검정
    $arg = str_replace("시간협의", "<font style='color:#fff700;font-weight:600;'>시간협의</font>", $arg); //검정
    $arg = str_replace("협의", "<font style='color:#fff700;font-weight:600;'>협의</font>", $arg); //검정
    return $arg;
}

function fAddrMake($addr)
{

    $addr = str_replace("경기 ", "", $addr);
    $addr = str_replace("강원 ", "", $addr);
    $addr = str_replace("충남 ", "", $addr);
    $addr = str_replace("충북 ", "", $addr);
    $addr = str_replace("경남 ", "", $addr);
    $addr = str_replace("경북 ", "", $addr);
    $addr = str_replace("전남 ", "", $addr);
    $addr = str_replace("전북 ", "", $addr);
    $addr = str_replace("제주 ", "", $addr);
    $addr = str_replace("경기도 ", "", $addr);
    $addr = str_replace("강원도 ", "", $addr);
    $addr = str_replace("충남도 ", "", $addr);
    $addr = str_replace("충북도 ", "", $addr);
    $addr = str_replace("경남도 ", "", $addr);
    $addr = str_replace("경북도 ", "", $addr);
    $addr = str_replace("전남도 ", "", $addr);
    $addr = str_replace("전북도 ", "", $addr);
    $addr = str_replace("제주도 ", "", $addr);
    $addr = str_replace(",", "", $addr);

    $addrArray = explode(" ", $addr);
    if (count($addrArray) === 3) {
        if (preg_match('/시$/u', $addrArray[0])) {
            // "시"를 제거
            $addrArray[0] = mb_substr($addrArray[0], 0, -1, 'UTF-8');
        }
        $addr = $addrArray[0] . " " . $addrArray[2];
    }
    $addrArray = explode(" ", $addr);
    if (count($addrArray) === 2) {
        if (preg_match('/시$/u', $addrArray[0])) {
            // "시"를 제거
            $addrArray[0] = mb_substr($addrArray[0], 0, -1, 'UTF-8');
        }
        $addr = $addrArray[0] . " " . $addrArray[1];
    }
    return $addr;
}

function getNego($txt)
{
    if (strpos($txt, '협의') > -1) {
        return "<em>&nbsp;</em>";
    } else {
        return $txt;
    }
}
?>
<!-- 공지팝업 -->
<?php if ($popup_list != null) : ?>
    <style>
        .layer-popup[id*=noticePopup] {
            width: 520px;
        }
    </style>
    <div id="noticePopup1" class="layer-popup" style="top:245px;left:auto; right:10px;">
        <div role="dialog" aria-modal="true" class="popup-container">
            <div class="popup-content">

                <!-- 20230719 수정 -->
                <div class="slider-wrap">
                    <div class="notice-slider keen-slider">
                        <?php foreach ($popup_list as $popup) : ?>
                            <div class="keen-slider__slide">
                                <a href="<?= $popup["pp_link"] ?>"><img width="520px" src="<?= $popup["pp_pc_img"] ?>" alt="<?= $popup["pp_name"] ?>"></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
            <div class="popup-footer">
                <button type="button" aria-controls="noticePopup1" class="btn btn-secondary js-close-popup js-hidden">오늘 하루 열지 않기<!--다시 보지 않기--></button>
                <button type="button" aria-controls="noticePopup1" class="btn btn-primary js-close-popup js-close">닫기</button>
            </div>
        </div>
    </div>

    <script>
        /* 공지팝업 표시,숩김(쿠키저장) */
        (function() {
            var allNotice = document.querySelectorAll('[id*="noticePopup"]');

            if (!allNotice) return;

            /* 20230719 수정 */
            window.addEventListener('DOMContentLoaded', function() {
                allNotice.forEach(function(popup) {
                    var cookies = document.cookie.match('(^|;) ?' + popup.getAttribute('id') + '=([^;]*)(;|$)');
                    var slider = popup.querySelector('.notice-slider');

                    popup.querySelector('.js-close').addEventListener('click', function(e) {
                        console.log('.js-close');
                        console.log(this.getAttribute('aria-controls'));
                        document.getElementById(this.getAttribute('aria-controls')).remove();
                    });

                    popup.querySelector('.js-hidden').addEventListener('click', function(e) {
                        console.log('.js-hidden');
                        console.log(this.getAttribute('aria-controls'));

                        var name = popup.getAttribute('id');
                        var date = new Date();
                        var days = 1; //30;//제한일
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        document.cookie = name + '=1;expires=' + date.toUTCString() + "; path=/;SameSite=Lax";

                        console.log('.js-hidden1');
                        document.getElementById(this.getAttribute('aria-controls')).remove();
                        console.log('.js-hidden2');
                    });

                    if (!cookies) {
                        popup.classList.add('in');
                    }

                    if (typeof KeenSlider !== undefined) {
                        new KeenSlider(slider, {
                            loop: false,
                            defaultAnimation: {
                                duration: 1000,
                            },
                        }, [
                            addActiveClass,
                            sliderDots,
                            function autoplay(slider) {
                                sliderAutoplay(slider, 4000, false);
                            }
                        ]);
                    }
                });
            });
        }());
    </script>
    <!-- //공지팝업 -->
<?php endif; ?>
<div id="content" class="page page-home">
    <main class="page-content">
        <div class="main-visual">
            <div class="container">
                <div class="thumb-list js-scrollbar">
                    <div class="scroll-content">
                        <?php foreach ($banner_list as $banner) : ?>
                            <div class="list-item" data-back="<?= $banner["bn_bg_color"] ?>">
                                <div class="inline-group">
                                    <div class="thumb"><img data-src="<?= $banner["bg_thumb"] ?>" alt="" class="lozad"></div>
                                    <p class="title"><?= $banner["bn_text1"] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="scrollbar-track">
                        <div class="scrollbar-thumb"></div>
                    </div>
                </div>
                <div class="visual-slider keen-slider">
                    <?php foreach ($banner_list as $banner) : ?>
                        <div class="keen-slider__slide">
                            <div class="container">
                                <div class="visual-content">
                                    <?php if ($banner["bn_link_yn"] != "Y") : ?>
                                        <a href="<?= $banner["bn_link"] ?>" class="btn" target="<?= $banner["bn_link_type"] ?>" style="position:absolute;top: 390px;left:48px;">자세히 보기</a>
                                    <?php endif; ?>
                                </div>
                                <img data-src="<?= $banner["bg_img"] ?>" alt="" class="lozad">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <script>
                window.addEventListener('DOMContentLoaded', function() {
                    if (typeof KeenSlider === undefined) return;

                    function deActive() {
                        thumbs.forEach(function(child) {
                            child.classList.remove('active');
                        });
                        visualSlider.slides.forEach(function(child) {
                            child.classList.remove('active');
                        });
                    }

                    function active(idx) {
                        thumbs[idx].classList.add('active');
                        $(".main-visual").css("background-color", thumbs[idx].getAttribute("data-back"));
                        visualSlider.slides[idx].classList.add('active');
                    }

                    function next(idx) {
                        clearTimeout(timeout);
                        deActive();
                        active(idx);
                        visualSlider.moveToIdx(idx);
                    }

                    function clearNextTimeout() {
                        clearTimeout(timeout);
                    }

                    function nextTimeout() {
                        clearTimeout(timeout);
                        timeout = setTimeout(function() {
                            var idx = visualSlider.track.details.rel + 1;
                            if (idx > visualSlider.slides.length - 1) {
                                idx = 0;
                            }
                            deActive();
                            active(idx);
                            visualSlider.moveToIdx(idx);
                        }, 3000);
                    }

                    var timeout;
                    var thumbs = document.querySelectorAll('.thumb-list .list-item');
                    var visualSlider = new KeenSlider('.main-visual .visual-slider', {
                        loop: true,
                        defaultAnimation: {
                            duration: 2000,
                        },
                        drag: false,
                        renderMode: 'custom',
                    });

                    thumbs.forEach(function(child, idx) {
                        child.addEventListener('click', function() {
                            next(idx);
                        });
                    });

                    visualSlider.on('detailsChanged', nextTimeout);
                    next(0);
                    nextTimeout();
                });
            </script>
        </div>
        <!--a href="http://cdev.d-mygred.com/gate/external/firstbuy">테스트</a-->
        <div class="section main-category">
            <div class="container">
                <h3 class="primary-title">나우잡에선 다양한 분야의 일자리가 있습니다.</h3>
                <ul class="job-categories">
                    <li>
                        <a href="javascript:setTopSearch('생산');" class="job-category1"><span>생산</span></a>
                    </li>
                    <li>
                        <a href="javascript:setTopSearch('물류');" class="job-category2"><span>물류</span></a>
                    </li>
                    <li>
                        <a href="javascript:setTopSearch('건설');" class="job-category3"><span>건설</span></a>
                    </li>
                    <li>
                        <a href="javascript:setTopSearch('매장관리');" class="job-category4"><span>매장관리</span></a>
                    </li>
                    <li>
                        <a href="javascript:setTopSearch('식당');" class="job-category5"><span>식당</span></a>
                    </li>
                    <li>
                        <a href="javascript:setTopSearch('서비스');" class="job-category6"><span>서비스</span></a>
                    </li>
                    <li>
                        <a href="javascript:setTopSearch('운전배달');" class="job-category7"><span>운전배달</span></a>
                    </li>
                    <li>
                        <a href="javascript:setTopSearch('고객상담');" class="job-category8"><span>고객상담</span></a>
                    </li>
                    <li>
                        <a href="javascript:setTopSearch('사무직');" class="job-category9"><span>사무직</span></a>
                    </li>
                    <li>
                        <a href="javascript:setTopSearch('디자인');" class="job-category10"><span>디자인</span></a>
                    </li>
                    <li>
                        <a href="javascript:setTopSearch('기타');" class="job-category11"><span>기타</span></a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="section main-recruit">
            <div class="container">
                <div class="section-headline">
                    <h3 class="primary-title">추천공고</h3>
                    <p class="desc">지금 뜨는, 좋은 일자리 나우잡에서 추천하는 공고입니다</p>
                </div>

                <div class="recruit-recomm">
                    <div class="slider-wrap keen-slider swiper-container" id="my-keen-slider">

                        <!-- 목록내 6개 항목 -->
                        <div class="keen-slider__slide swiper-wrapper">
                            <!-- 해당 카테고리번호 클래스 추가(category1~7) -->
                            <?php $counter = 0; ?>
                            <?php foreach ($recruit_list as $row) : ?>
                                <?php
                                $counter++;
                                if ($counter > 24) {
                                    break;
                                }
                                ?>
                                <div class="recruit-card category2 swiper-slide">
                                    <!-- 즐겨찾기 추가 -->
                                    <button type="button" data-bk_type="RECRUIT" data-bk_key="<?= $row["rc_idx"] ?>" data-user_id="<?= $row["mm_id"] ?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?= $row["book_yn"] == "Y" ? "on" : "" ?>"><span class="sr-text">즐겨찾기 추가</span></button>
                                    <!-- 썸네일 -->
                                    <div class="thumb click-item-new" data-link="/recruit/job_post/<?= $row["rc_idx"] ?>">
                                        <a data-href="/recruit/job_post/<?= $row["rc_idx"] ?>"><img src="<?= getRecruitImg($row) ?>" alt=""></a>
                                    </div>
                                    <div class="content-wrap click-item-new" data-link="/recruit/job_post/<?= $row["rc_idx"] ?>">
                                        <div class="inline-group">
                                            <!-- 추천라벨 -->
                                            <p class="recomm-badge" style="position: absolute; top: -253px; left: -23px; border-radius: 8px;">추천</p>
                                            <!-- 카테고리 라벨 -->
                                            <?= getWorkingImg($row["rc_work"], 1) ?>
                                            <!-- 지역 -->
                                            <p class="area"><?= fAddrMake($row["ad_addr"]) ?></p>
                                        </div>
                                        <!-- 제목 -->
                                        <p class="title"><strong><a data-href="/recruit/job_post/<?= $row["rc_idx"] ?>"><?= $row["rc_name"] ?></a></strong></p>
                                        <div class="meta">
                                            <!-- 선택된 항목에 em태그로 처리 -->
                                            <!-- <p class="type"><?= getWorkTypePoint($row["rc_work_type"]) ?></p> -->
                                            <p><?= $row["rc_nation_nm"] ?></p>
                                            <p><?= $row["rc_age_nm"] ?></p>
                                        </div>
                                        <div class="price-wrap" style="position: relative;">
                                            <!-- 선택된 항목에 em태그로 처리 -->
                                            <p class="type" style="position: absolute; left:0px; top: 27px; "><?= fWorkColorTop(getWorkTypePoint($row["rc_work_type"])) ?></p>
                                            <p class="type"><?= getNego(getPriceTypePoint($row["rc_price_type"], $row)) ?></p>
                                            <p class="amount"><?= getRecuitPrice($row) ?></p>
                                        </div>
                                        <!-- 해시태그 -->
                                        <div class="hashtag" style="height: 48px;display:none;">
                                            <?= getHashTag($row["rc_hash"], 3) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>

                <a href="/recruit/job_list" target="_blank" class="btn-view-all">더보기</a>
            </div>

            <!--<script src="https://unpkg.com/swiper/swiper-bundle.min.js">-->

            <script>
                /* 20231005 수정 */
                window.addEventListener('DOMContentLoaded', function() {

                    // 									 const swiper = new Swiper('.swiper-container', {
                    //   autoplay: {
                    //     delay: 2000,
                    //     disableOnInteraction: false,
                    //   },
                    // });

                    if (typeof KeenSlider === undefined) return;

                    var container = document.querySelector('.recruit-recomm .slider-wrap');
                    var slide = document.querySelectorAll('.recruit-recomm .keen-slider__slide');
                    var total = 0;
                    var animation = {
                        duration: 100000, //이동속도 ms  50000
                        easing: function(t) {
                            return t;
                        }
                    };

                    if (slide.length <= 1) {
                        container.appendChild(slide[0].cloneNode(true));
                        container.appendChild(slide[0].cloneNode(true));
                    }

                    total = document.querySelectorAll('.recruit-recomm .recruit-card').length / 6;

                    var slider = new KeenSlider(".recruit-recomm .slider-wrap", {
                        loop: true,
                        drag: false,
                        renderMode: 'performance',
                        rubberband: false,
                        slides: {
                            origin: 'center',
                            perView: 1,
                        },
                        created(s) {
                            s.moveToIdx(total, true, animation);
                        },
                        updated(s) {
                            s.moveToIdx(s.track.details.abs + total, true, animation);
                        },
                        animationEnded(s) {
                            s.moveToIdx(s.track.details.abs + total, true, animation);
                        },
                    }, [sliderArrows]);


                    // // 마우스 오버 시 슬라이드 속도를 느리게
                    // $('.recruit-recomm .slider-wrap').on('mouseover', function() {
                    // 		console.log('slow');
                    // 		console.log(slider);
                    //     //slider.options.duration = slowSpeed;
                    // 		//slider.options({ duration: slowSpeed });
                    // 		//slider.stop();
                    // 		//slider.animator.active = false;
                    // 		//slider.destroy();
                    // 		slider.animator.stop();
                    // 		console.log('slow');
                    // });

                    // 										// 마우스가 나가면 원래 속도로 복귀
                    // 						        $('.recruit-recomm .slider-wrap').on('mouseleave', function() {
                    // 												console.log('origin');
                    // 												console.log(slider);
                    // 						            //slider.options.duration = originalSpeed;
                    // 												//slider.options({ duration: originalSpeed });
                    // 												//var frame = {distance:z,duration:s(E.dur),easing:f};
                    // 												//distance:h,duration:g,easing:f
                    //
                    // 												function easeOutCubic(t) {
                    // 												  return 1 - Math.pow(1 - t, 3);
                    // 												}
                    //
                    // 												var keyframes = {
                    //       distance: 100,
                    //       duration: 100,
                    //       earlyExit: 1,
                    // 			easing: easeOutCubic
                    //       //easing: (t: 1) => 1
                    //     };//[]
                    //
                    // 												slider.animator.start(keyframes);
                    //
                    // // 												slider.animator.start([
                    // //   { distance: 100, duration: 1000 },
                    // //   { distance: -50, duration: 500 },
                    // //   { distance: 200, duration: 1500 }
                    // // ]);
                    //
                    //
                    // 												console.log('origin');
                    // 						        });


                    /*
                    										var originalSpeed =  50000; // 원래 슬라이드 속도
                            						var slowSpeed     = 5000000; // 마우스 오버 시 슬라이드 속도


                    */
                    /*
                    var autoSlideInterval;
                    var speed = 5000; // 기본 속도 설정

                    function startAutoSlide(sliderInstance) {
                    autoSlideInterval = setInterval(function() {
                    sliderInstance.next();
                    }, speed);
                    }

                    function stopAutoSlide() {
                    clearInterval(autoSlideInterval);
                    }

                    $('.recruit-recomm .slider-wrap').on('mouseenter', function() {
                    stopAutoSlide(); // 슬라이드 멈추고
                    speed = 10000; // 속도 천천히
                    startAutoSlide(slider); // 다시 시작
                    });

                    $('.recruit-recomm .slider-wrap').on('mouseleave', function() {
                    stopAutoSlide(); // 슬라이드 멈추고
                    speed = 5000; // 원래 속도로
                    startAutoSlide(slider); // 다시 시작
                    });
                    */
                });
            </script>

        </div>

        <div class="section main-person">
            <div class="container">
                <div class="section-headline">
                    <h3 class="primary-title">추천인재</h3>
                    <p class="desc">지금 뜨는, 좋은 일자리 나우잡에서 추천하는 인재입니다</p>
                </div>

                <div class="person-recomm">
                    <div class="slider-wrap keen-slider">
                        <?php foreach ($resume_list as $row) : ?>
                            <!-- 목록 -->
                            <div class="keen-slider__slide">
                                <div class="person-card">
                                    <div class="thumb-wrap">
                                        <!-- 추천라벨 -->
                                        <p class="recomm-badge"><span>추천</span></p>
                                        <button type="button" data-bk_type="RESUME" data-bk_key="<?= $row["ru_idx"] ?>" data-user_id="<?= $row["mm_id"] ?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?= $row["book_yn"] == "Y" ? "on" : "" ?>"><span class="sr-text">즐겨찾기 추가</span></button>
                                        <!-- 썸네일 -->
                                        <div class="thumb">
                                            <a href="/resume/hr_post/<?= $row["ru_idx"] ?>" target="_blank"><img data-src="<?= getResumeImg($row) ?>" class="lozad" onError="this.src='/resource/shop/mobile/img/common/noimage3.png'" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="content-wrap">
                                        <!-- 직종라벨 -->
                                        <p class="category">
                                            <?= getWorkingImg($row["ru_work"]) ?>
                                        </p>
                                        <p class="area" style="float: right; margin-top: -36px; margin-right: -7px;"><?= fAddrMake($row["ad_addr"]) ?></p>
                                        <div class="title-wrap">
                                            <!-- 제목 -->
                                            <p class="title"><strong><a href="/resume/hr_post/<?= $row["ru_idx"] ?>" target="_blank"><?= getAstrik($row["mm_name"]) ?></a></strong></p>
                                            <!-- 평점 -->
                                            <p class="star-rating">
                                                <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                                                <span class="star" style="width:100%;"></span>
                                                <span class="sr-text">평점</span>
                                                <span class="sr-text">5</span>
                                            </p>
                                        </div>
                                        <!-- 추가사항 -->
                                        <div class="meta">
                                            <p class="national"><?= $row["mm_nation"] ?></p>
                                            <p class="age"><?= ($row["ma_age"] == "0" ? "" : $row["ma_age"] . "대") ?></p>
                                            <p class="txt-color2"><?= fWorkColor(strpos($row["ru_work_type"], ",") !== false ? "협의" : ($row["ru_work_type"])) ?></p>
                                            <p class="txt-color2"><?= (strpos($row["ru_price_type"], ",") !== false ? "협의" : ($row["ru_price_type"])) ?></p>
                                        </div>
                                        <!-- 지역 -->
                                        <!-- <p class="area"><?= fAddrMake($row["ad_addr"]) ?></p> -->
                                        <!-- 태그 -->
                                        <div class="hashtag" style="border-top: 1px solid #000; padding-top: 5px;">
                                            <?= getMyType($row["ru_my_type"], 3) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <a href="/resume/hr_list" target="_blank" class="btn-view-all">더보기</a>
            </div>
            <script>
                window.addEventListener('DOMContentLoaded', function() {
                    if (typeof KeenSlider === undefined) return;
                    new KeenSlider(".person-recomm .slider-wrap", {
                        loop: true,
                        slides: {
                            perView: 5,
                        }
                    }, [
                        addActiveClass,
                        sliderArrows
                    ]);
                });
            </script>

        </div>

        <div class="section main-company">
            <div class="container">
                <h3 class="primary-title">지금 뜨는 업체</h3>

                <ul class="company-recomm">
                    <?php foreach ($up_list as $row) : ?>
                        <li>
                            <div class="thumb">
                                <a href="/recruit/wlistcomp/<?= $row["mm_idx"] ?>" target="_blank"><img data-src="<?= getCompImg($row) ?>" alt="" class="lozad"></a>
                            </div>
                            <p class="title"><a href="/recruit/wlistcomp/<?= $row["mm_idx"] ?>" target="_blank"><?= $row["mm_comp_name"] ?></a></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="/recruit/clist" class="btn-view-all">더보기</a>
            </div>
        </div>

        <?php /* ?>
        <div class="section bg-gray main-market">
            <div class="container">
                <h3 class="primary-title">중고거래</h3>

                <div class="market-recomm">
                    <div class="slider-wrap">
                        <?php foreach($trade_list as $row) : ?>
	                    <div class="list-item">
                            <div class="sale-card">
                                <div class="thumb-wrap">
                                    <!-- 추천라벨 -->
                                    <p class="recomm-badge"><span>추천</span></p>
                                    <!-- 즐겨찾기 추가된 목록: on -->
                                    <button type="button" data-bk_type="TRADE" data-bk_key="<?=$row["td_idx"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 해제</span></button>
                                    <!-- 썸네일 -->
                                    <div class="thumb">
                                        <a href="/trade/trade_detail/<?=$row["td_idx"]?>"><img data-src="<?=getTradeImg($row)?>" alt="" class="lozad"></a>
                                    </div>
                                </div>
                                <div class="content-wrap">
                                    <p class="title"><strong><a href="/trade/trade_detail/<?=$row["td_idx"]?>"><?=$row["td_name"]?></a></strong></p>
	                                <div class="meta">
	                                    <p class="area"><?=$row["ad_addr"]?></p>
	                                </div>
	                                <div class="price-wrap">
	                                    <p class="amount"><em class="num"><?=number_format($row["td_price"])?></em> 원</p>
	                                    <p class="date"><?=get_yyyymmdd($row["reg_dt"])?></p>
	                                </div>
                                </div>
                            </div>
                        </div>
	                    <?php endforeach; ?>
                    </div>
                </div>
                <a href="/trade/wlist" class="btn-view-all">더보기</a>
            </div>
        </div>
				<?php */ ?>

    </main><!-- //page-content -->
</div><!-- //page -->