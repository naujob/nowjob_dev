<?php
$banner_list = getBannerList("BN00010005");
?>
<style>
  .recruit-recomm .recruit-card {
    width: calc(100% - 18px);
  }

  .recruit-card .content-wrap {
    cursor: pointer;
  }

  /* 2줄 슬라이더 스타일 */
  .recruit-recomm .slider-row {
    position: relative;
    margin-bottom: 0;
  }

  .recruit-recomm .slider-row:last-child {
    margin-bottom: 0;
  }

  .recruit-recomm .arrow--top {
    top: 50%;
    display: none;
    /* 화살표 숨김 */
  }

  .recruit-recomm .arrow--second {
    top: 50%;
    display: none;
    /* 화살표 숨김 */
  }

  .recruit-recomm .arrow--third {
    top: 50%;
    display: none;
    /* 화살표 숨김 */
  }

  .recruit-recomm .arrow--bottom {
    top: 50%;
    display: none;
    /* 화살표 숨김 */
  }

  .recomm-slider.person .arrow {
    position: absolute;
    top: 50%;
    left: 26px;
    width: 90px;
    height: 80px;
    margin: 40px 0 0;
    border: 0;
    background: url("/resource/shop/web/img/main/main_slider2_prev.svg") no-repeat 0 50%;
    background-size: 46px auto;
    z-index: 1;
  }

  .recomm-slider.person .arrow--right {
    left: auto;
    right: 26px;
    background-image: url("/resource/shop/web/img/main/main_slider2_next.svg");
    background-position: 100% 50%;
  }

  .person-card .meta p em {
    font-style: normal;
  }

  .person-card .img {
    position: relative;
    width: 256px;
    height: 256px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  .person-card .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .person-card .desc {
    cursor: pointer;
  }

  .person-card .title {
    width: 50%;
  }

  .person-card .area {
    margin: 0;
    color: #000;
    height: auto;
    width: 50%;
    text-align: right;
    font-size: 18px;
    font-weight: 600;
  }

  .person-card .hashtag {
    display: block;
    margin: 14px 0 0;
    padding: 5px 0 0;
    font-size: 0;
    -o-text-overflow: ellipsis;
    text-overflow: ellipsis;
    white-space: nowrap;
    border-top: 1px solid #000;
    overflow: hidden;
  }

  .person-card .tag {
    display: inline-block;
    margin: 0 8px 0 0;
    padding: 0 12px;
    font-size: 16px;
    color: #3A70DB;
    line-height: 2;
    text-align: center;
    vertical-align: top;
    border-radius: 100px;

  }

  .person-card .tag:last-child {
    margin-right: 0;
  }

  .bottom-recomm {
    list-style: none;
    display: flex;
    width: 100%;
    flex-wrap: wrap;
    gap: 23px;
    margin-top: 20px;
  }

  .bottom-recomm .comp-info {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    position: relative;
    align-items: center;
    width: 180px;
    height: 235px;
    cursor: pointer;
  }

  .bottom-recomm .comp-info .comp-img {
    position: absolute;
    width: 180px;
    height: 180px;
    padding: 10px;
    overflow: hidden;
    top: 0;
    background-color: #fff;
    border: 1px solid #EB5349;
    border-radius: 8px;
  }

  .bottom-recomm .comp-info .comp-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }

  .bottom-recomm .comp-info .comp-name {
    position: absolute;
    bottom: 20px;
    width: 100%;
    height: 24px;
    font-size: 16px;
    font-weight: 700;
  }
</style>

<!-- 공지팝업 -->
<?php if ($popup_list != null) : ?>
  <style>
    .layer-popup[id*=noticePopup] {
      width: 520px;
    }
  </style>
  <div id="noticePopup1" class="layer-popup" style="position: fixed; top:auto; left:auto; right:0px; bottom: 0px;">
    <div role="dialog" aria-modal="true" class="popup-container">
      <div class="popup-content">
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
    (function() {
      var allNotice = document.querySelectorAll('[id*="noticePopup"]');
      if (!allNotice) return;
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
<?php endif; ?>
<!-- //공지팝업 -->

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
    <div class="section main-recruit">
      <div class="container">
        <div class="section-headline">
          <h3 class="primary-title">추천공고</h3>
          <p class="desc">지금 뜨는, 좋은 일자리 나우잡에서 추천하는 공고입니다</p>
        </div>

        <div class="recruit-recomm">
          <!-- 첫 번째 슬라이더 (위쪽) -->
          <div class="slider-row">
            <button class="arrow arrow--left arrow--top"><span>Previous</span></button>
            <div class="slider-wrap keen-slider swiper-container" id="my-keen-slider-top">
            </div>
            <button class="arrow arrow--right arrow--top"><span>Next</span></button>
          </div>

          <!-- 두 번째 슬라이더 -->
          <div class="slider-row">
            <button class="arrow arrow--left arrow--second"><span>Previous</span></button>
            <div class="slider-wrap keen-slider swiper-container" id="my-keen-slider-second">
            </div>
            <button class="arrow arrow--right arrow--second"><span>Next</span></button>
          </div>

          <!-- 세 번째 슬라이더 -->
          <div class="slider-row">
            <button class="arrow arrow--left arrow--third"><span>Previous</span></button>
            <div class="slider-wrap keen-slider swiper-container" id="my-keen-slider-third">
            </div>
            <button class="arrow arrow--right arrow--third"><span>Next</span></button>
          </div>

          <!-- 네 번째 슬라이더 (아래쪽) -->
          <div class="slider-row">
            <button class="arrow arrow--left arrow--bottom"><span>Previous</span></button>
            <div class="slider-wrap keen-slider swiper-container" id="my-keen-slider-bottom">
            </div>
            <button class="arrow arrow--right arrow--bottom"><span>Next</span></button>
          </div>
        </div>
        <a href="/recruit/job_list" target="_blank" class="btn-view-all">더보기</a>
      </div>
    </div>

    <div class="section main-person recomm-slider person">
      <div class="container">
        <div class="section-headline">
          <h3 class="primary-title">추천인재</h3>
          <p class="desc">지금 뜨는, 좋은 일자리 나우잡에서 추천하는 인재입니다</p>
        </div>
        <div class="slider-wrap keen-slider" id="my-keen-slider2">
        </div>
        <a href="/resume/hr_list" target="_blank" class="btn-view-all">더보기</a>
      </div>
    </div>

    <div class="section main-company">
      <div class="container">
        <h3 class="primary-title">지금 뜨는 업체</h3>
        <div class="bottom-recomm">
          <?php
          $count = 0;
          foreach ($up_list as $row) :
            if ($count >= 7) break;
          ?>
            <div class="comp-info" data-comm_idx="<?= $row["mm_idx"] ?>">
              <div class="comp-img">
                <img src="<?= getCompImg($row) ?>">
              </div>
              <p class="comp-name"><?= $row["mm_comp_name"] ?></p>
            </div>
          <?php
            $count++;
          endforeach;
          ?>
        </div>
        <a href="/recruit/companies" class="btn-view-all">더보기</a>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<!-- 공고 리스트 템플릿 -->
<template id="tpl_reco_list">
  <div class="keen-slider__slide swiper-wrapper">
    <div class="recruit-card category2 swiper-slide" data-rc_idx="{{rc_idx}}">
      <?php if ($mm_type == "MEM") { ?><button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" class=" btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
      <div class="thumb">
        <img src="{{rt_recruit_img}}" alt="">
      </div>
      <div class="content-wrap">
        <div class="inline-group">
          {{rt_work_img}}
          <p class="area">{{ad_addr}}</p>
        </div>
        <p class="title"><strong>{{rc_name}}</strong></p>
        <div class="meta">
          <p>{{rc_nation_nm}}</p>
          <p>{{rc_age_nm}}</p>
        </div>
        <div class="price-wrap" style="position: relative;">
          <p class="type" style="position: absolute; left:0px; top: 27px; ">{{rt_work_type}}</p>
          <p class="type">{{rc_price_type}}</p>
          <p class="amount">{{rc_price_pay}}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<!-- 인재 리스트 템플릿 -->
<template id="tpl_person_list">
  <div class="keen-slider__slide ">
    <div class="person-card" data-ru_idx="{{ru_idx}}" data-mm_idx="{{mm_idx}}">
      <div class="thumb-wrap">
        <div class="img">
          <img src="{{ru_resume_img}}">
          <?php if ($mm_type === "COMP") { ?><button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
        </div>
      </div>
      <div class="desc">
        <div class="content-wrap" style="padding: 46px 5px 24px;">
          <p class="category">
            {{ru_work_img}}
          </p>
          <div class="title-wrap">
            <p class="title"><strong>{{ru_name}}</strong></p>
            
          </div>
          <div class="meta">
            <p class="national">{{mm_nation}}</p>
            <p class="age">{{mm_age}}</p>
            <p class="work_type">{{ru_work_type}}</p>
            <p class="price_type">{{ru_price_type}}</p>
          </div>
          <div class="hashtag">
            {{ru_my_type}}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  function getRecoList() {
    // 첫 번째 슬라이더용 데이터
    var paramsTop = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "12"
    };

    // 두 번째 슬라이더용 데이터
    var paramsSecond = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "12",
      "offset": "12"
    };

    // 세 번째 슬라이더용 데이터
    var paramsThird = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "12",
      "offset": "24"
    };

    // 네 번째 슬라이더용 데이터
    var paramsBottom = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "12",
      "offset": "36"
    };

    // 첫 번째 슬라이더 데이터 로드
    comAjax("/recruit_proc/getRecruitRecoList", paramsTop, function(ret) {
      if (ret.code == "00") {
        createSlider(ret.data.list, "my-keen-slider-top", "arrow--top");
      }
    });

    // 두 번째 슬라이더 데이터 로드
    comAjax("/recruit_proc/getRecruitRecoList", paramsSecond, function(ret) {
      if (ret.code == "00") {
        createSlider(ret.data.list, "my-keen-slider-second", "arrow--second");
      }
    });

    // 세 번째 슬라이더 데이터 로드
    comAjax("/recruit_proc/getRecruitRecoList", paramsThird, function(ret) {
      if (ret.code == "00") {
        createSlider(ret.data.list, "my-keen-slider-third", "arrow--third");
      }
    });

    // 네 번째 슬라이더 데이터 로드
    comAjax("/recruit_proc/getRecruitRecoList", paramsBottom, function(ret) {
      if (ret.code == "00") {
        createSlider(ret.data.list, "my-keen-slider-bottom", "arrow--bottom");
      }
    });
  }

  // 슬라이더 생성 함수
  function createSlider(list, sliderId, arrowClass) {
    $("#" + sliderId).html("");

    list.forEach(function(row) {
      var $clone = $("#tpl_reco_list").clone();
      var $content = $clone.html();

      if (row.rc_nation_nm == "무관" || row.rc_nation_nm == "내국인,외국인") {
        $content = $content.replaceAll("{{rc_nation_nm}}", "국적무관");
      } else {
        $content = $content.replaceAll("{{rc_nation_nm}}", row.rc_nation_nm.replace("만", ""));
      }

      $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
      $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
      $content = $content.replaceAll("{{rc_name}}", row.rc_name);
      $content = $content.replaceAll("{{rc_age_nm}}", row.rc_age_nm);

      $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);

      var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
      $images.slice(2).remove();
      var $categoryHtml = $images.parent().html();
      $content = $content.replaceAll("{{rt_work_img}}", $categoryHtml);
      $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type);
      if (row.rc_price_type.indexOf('협의') > -1) {
        $content = $content.replaceAll("{{rc_price_type}}", "<em> </em>");
      } else {
        $content = $content.replaceAll("{{rc_price_type}}", getConvertPriceType(row.rc_price_type, row.rc_price_rep));
      }
      if (row.rc_price_type.indexOf('협의') > -1) {
        $content = $content.replaceAll("{{rc_price_pay}}", "급여협의");
      } else {
        $content = $content.replaceAll("{{rc_price_pay}}", Number(row.rc_price_pay).toLocaleString() + "원");
      }
      $clone.html($content);

      $("#" + sliderId).append($clone.html());
    });

    var container = document.querySelector('#' + sliderId);
    var slide = document.querySelectorAll('#' + sliderId + ' .recruit-card');
    var perView = 4;
    var total = slide.length;

    function nauriControls(slider) {
      var leftArrow = document.querySelector('.' + arrowClass + '.arrow--left');
      var rightArrow = document.querySelector('.' + arrowClass + '.arrow--right');

      // 왼쪽 화살표 클릭 - 이전 슬라이드로 이동
      leftArrow.addEventListener('click', () => {
        slider.prev();
      });

      // 오른쪽 화살표 클릭 - 다음 슬라이드로 이동
      rightArrow.addEventListener('click', () => {
        slider.next();
      });
    }

    var slider = new KeenSlider('#' + sliderId, {
      loop: total > perView,
      drag: total > perView,
      renderMode: 'performance',
      rubberband: false,
      slides: {
        perView: 4,
      },
      // 자동 움직임 제거 - created, updated, animationEnded 콜백 제거
    }, [nauriControls]);
  }

  function getRecoPersonList() {
    var params = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "10"
    };
    comAjax("/resume_proc/search_reco_list2025", params, function(ret) {
      if (ret.code == "00") {
        var list = ret.data.list;
        $("#my-keen-slider2").html("");
        list.forEach(function(row) {
          var $clone = $("#tpl_person_list").clone();
          var $content = $clone.html();

          $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
          $content = $content.replaceAll("{{mm_idx}}", row.mm_idx || "");
          $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
          $content = $content.replaceAll("{{ru_name}}", row.ru_name || "");
          $content = $content.replaceAll("{{ru_week}}", row.ru_week);
          $content = $content.replaceAll("{{distance}}", row.distance + "km");
          $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img);

          if (row.mm_nation.trim() == '') {
            $content = $content.replaceAll("{{mm_nation}}", "");
            $content = $content.replaceAll("class=\"national\" style=\"display: inline-block;\"", "class=\"national\" style=\"display: none;\"");
          } else {
            $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
          }

          $content = $content.replaceAll("{{mm_age}}", row.mm_age + "세");

          $content = $content.replaceAll("{{ru_price_type}}", getPriceType(row.ru_price_type));
          $content = $content.replaceAll("{{ru_work_type}}", getWorkType(row.ru_work_type));
          $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img);
          $content = $content.replaceAll("{{ru_my_type}}", getHashTagJS(row.ru_my_type, 2));
          $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
          $content = $content.replaceAll("{{timestamp}}", row.timestamp);

          $clone.html($content);

          $("#my-keen-slider2").append($clone.html());
        });

        var slideCount = document.querySelectorAll('.main-person .slider-wrap .keen-slider__slide').length;
        var perView = 5;

        var slider = new KeenSlider(".main-person .slider-wrap", {
          loop: slideCount > perView,
          slides: {
            perView: perView
          },
        }, [sliderArrows]);
      } else {

      }
    });
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

  function getConvertPriceType(rc_price_type, rc_price_rep) {
    const types = rc_price_type.split(',');

    if (types.length === 1) {
      return `<em class="on">${types[0]}</em>`;
    }

    return types.map(type => {
      if (type === rc_price_rep) {
        return `<em class="on">${type}</em>`;
      } else {
        return `<em>${type}</em>`;
      }
    }).join('');
  }

  function getPriceType(price_type) {
    if (!price_type) {
      return "";
    }
    if (price_type === "일급,주급,월급") {
      return "급여협의";
    }
    // 첫 번째 값만 반환
    const firstType = price_type.split(',')[0].trim();
    return firstType;
  }

  function getWorkType(work_type) {
    if (!work_type) {
      return "";
    }
    const matches = work_type.match(/<span>(.*?)<\/span>/g);

    if (!matches || matches.length === 0) {
      return "";
    }
    // 첫 번째 값만 반환
    const firstMatch = matches[0];
    const text = firstMatch.replace(/<\/?span>/g, '').trim();
    let cls = '';
    if (text === '주간') {
      cls = 'wtype1';
    } else if (text === '야간') {
      cls = 'wtype2';
    } else if (text === '2교대') {
      cls = 'wtype3';
    }
    return `<em class="${cls}">${text}</em>`;
  }

  function getHashTagJS(hash, max = 10) {
    let result = "";
    let tmp = hash;
    tmp = tmp.replaceAll("<p class=\'tag\' style=\'width:100%;text-align:right;\'>", "");
    tmp = tmp.replaceAll("</p>", "");
    let arr_hash = tmp.trim().split(" ");
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

  // 현재 로그인한 사용자 정보
  var currentUserMmIdx = <?php echo $this->user->isLogin() ? $this->user->getUserIdx() : 'null'; ?>;
  var currentUserType = '<?php echo isset($mm_type) ? $mm_type : ""; ?>';

  window.addEventListener('DOMContentLoaded', function() {
    getRecoList();
    getRecoPersonList();
  });

  $(document).on('click', '#my-keen-slider2 .desc', function() {
    var $personCard = $(this).closest('.person-card');
    var ruIdx = $personCard.data('ru_idx');
    var resumeMmIdx = $personCard.data('mm_idx'); // 이력서 소유자 인덱스
    
    // 개인회원이고 자신의 이력서인 경우 클릭 가능
    if (currentUserType === "MEM" && currentUserMmIdx && resumeMmIdx && currentUserMmIdx == resumeMmIdx) {
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.open(url, '_blank');
      }
      return;
    }
    
    // 기업회원인 경우 클릭 가능
    <?php if ($mm_type === "COMP") { ?>
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.open(url, '_blank');
      }
    <?php } else { ?>
      // 개인회원이지만 자신의 이력서가 아닌 경우
      commonAlert("기업회원 전용 서비스입니다.", function() {});
    <?php } ?>
  });

  $(document).on('click', '.recruit-card .content-wrap', function() {
    var rcIdx = $(this).closest('.recruit-card').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.open(url, '_blank');
    }
  });

  $(document).on('click', '.comp-info', function() {
    var commIdx = $(this).data('comm_idx');
    if (commIdx) {
      var url = '/recruit/company/' + commIdx;
      window.open(url, '_blank');
    }
  });
</script>
