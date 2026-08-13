<?php
$banner_list = getBannerList("BN00010005");
?>
<?= getCachCss(RES_PATH . "/css/recruit_card.css") ?>
<style>
  #header {
    margin-bottom: 0;
  }

  /* .recruit-card .meta p:first-child {

    width: auto;
  }

  .recruit-card .meta p {

    width: auto;
  } */

  .recruit-recomm .recruit-card {
    width: calc(25% - 18px);
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

 

  .person-card .meta p em {
    font-style: normal;
  }

  /* 인재 카드 */
  .person-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    padding: 12px;
    border: 1px solid #e0e0e0;
    border-radius: 25px;
    background: #ffffff;
    margin-bottom: 40px;
    height: 363px;
    justify-content: space-between;
    gap: 8px;
    padding: 25px 8px 25px;

  }

  .person-card-grid .person-card .thumb {
    overflow: hidden;
    flex-shrink: 0;
    background-color: unset;
    padding: 0px 0 0px;
    margin-top: 0px;
    margin-bottom: 0px;
    width: 112px;
    height: 112px;
    display: block;
    position: relative;
  }

  .person-card-grid .person-card .thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    min-width: 0%;
    min-height: 0%;
    border-radius: 50%;
    position: static;
    transform: none;
    display: block;
  }

  /* 뱃지 영역 */
  .person-card .badge-wrap {
    display: flex;
    gap: 4px;

    justify-content: center;
    flex-wrap: wrap;
    margin-top: 0px;
    margin-bottom: 0px;
  }

  /* 메타 정보 (주간 | 40대 | 월급) */
  .person-card .meta {
    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 1.2rem;
    color: #000;
    font-weight: 500;
    width: 101%;
    margin-top: 0px;
    margin-bottom: 0px;
  }

  .person-card .meta p {
    margin: 0;
    flex: 1;
    text-align: center;
  }

  .person-card .meta p:first-child {
    text-align: center;
  }



  .person-card .meta .meta-divider {
    color: #292c32;
    flex-shrink: 0;
  }

  /* main.css의 p:after 구분선 무효화 */
  .person-card .meta p:after {
    display: none;
  }

  /* 이력서명 */
  .person-card .name {

    font-size: 1.2rem;
    font-weight: 700;
    color: #000;
    text-align: center;
    margin-top: 0px;
    margin-bottom: 0px;
  }

  /* 주소 */
  .person-card .addr {

    font-size: 1.2rem;
    font-weight: 700;
    color: #000;
    text-align: center;
    margin-top: 0px;
    margin-bottom: 0px;
  }

  .bottom-recomm {
    list-style: none;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    width: 100%;
    gap: 16px 8px;
    margin-top: 20px;
    max-width: none !important;
    flex-wrap: unset;
    padding: 10px 0px;
    overflow: visible;
  }

  .comp-info>* {
    margin: 0 !important;
  }

  .bottom-recomm .comp-info {
    background: rgb(255, 255, 255);
    border-radius: 25px;
    border: 1.5px solid rgb(224, 224, 224);

    padding: 25px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    text-align: center;

    min-height: 280px;
    position: relative;
    width: 100%;
    height: auto;
    cursor: pointer;
    min-width: 250px;
    flex: 1 1 0%;
    margin-bottom: 30px;
  }

  .bottom-recomm .comp-info .comp-img {
    width: 100%;
    height: 220px;
    border-radius: 0px;
    overflow: visible;
    margin-bottom: 0px;
    display: block;
    align-items: center;
    justify-content: center;
    background: none;

    padding: 0px;
    margin-top: 0px;
    box-sizing: border-box;
    flex-direction: column;
    position: relative;

  }

  .bottom-recomm .comp-info .comp-img img {
    width: 100%;
    object-fit: contain;
    border-radius: 0px;
    height: 100% !important;
    display: block;
    max-height: none;

  }

  .bottom-recomm .comp-info .comp-name {
    position: relative;
    width: 100%;
    height: auto;
    font-size: 1.2rem;
    font-weight: 700;
    margin-top: 8px;
    margin-bottom: 4px;
    text-align: center;
    padding: 0;
    z-index: 2;
  }

  .bottom-recomm .comp-info .comp-desc {
    position: relative;
    width: 100%;
    height: 90px;
    font-size: 1.2rem;
    font-weight: 600;

    text-align: left;
    padding: 0 8px;
    box-sizing: border-box;
    line-height: 1.3;
    z-index: 2;
  }

  /* 인재 카드 2줄 그리드 레이아웃 (4개씩 2줄) */
  .person-card-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    width: 99%;
    gap: 16px 8px;
  }

  /* 화살표 숨김 */
  .arrow {
    display: none;
  }

  /* 추천공고 카드의 급여 기간(period) 스타일 오버라이드 */
  .recruit-card.category2 .price-wrap .period em.on,
  .recruit-card.category2 .price-wrap .period em:only-child {
    color: #FFF700;
    font-weight: 600;
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
        <div class="mv-slider-wrap">
          <!-- dot 네비게이션 -->
          <div class="mv-dots">
            <?php $banner_count = 0;
            foreach ($banner_list as $banner) : ?>
              <button class="mv-dot <?= $banner_count === 0 ? 'active' : '' ?>" data-index="<?= $banner_count ?>"></button>
            <?php $banner_count++;
            endforeach; ?>
          </div>

          <!-- 슬라이드 목록 -->
          <div class="mv-slides">
            <?php $banner_idx = 0;
            foreach ($banner_list as $banner) : ?>
              <div class="mv-slide <?= $banner_idx === 0 ? 'active' : '' ?>" style="background-color: <?= isset($banner['bn_bg_color']) ? $banner['bn_bg_color'] : '#5A5FD4' ?>;" data-link="<?= isset($banner['bn_link']) ? $banner['bn_link'] : '#' ?>" data-link-type="<?= isset($banner['bn_link_type']) ? $banner['bn_link_type'] : '_self' ?>">
                <img data-src="<?= $banner["bg_img"] ?>" alt="" class="lozad mv-slide-img">
              </div>
            <?php $banner_idx++;
            endforeach; ?>
          </div>
        </div>
      </div>

      <script>
        // dot 클릭 시 슬라이드 이동 (자동 이동 없음)
        window.addEventListener('DOMContentLoaded', function() {
          var slides = document.querySelectorAll('.mv-slide');
          var dots = document.querySelectorAll('.mv-dot');

          function goTo(idx) {
            slides.forEach(function(s) {
              s.classList.remove('active');
            });
            dots.forEach(function(d) {
              d.classList.remove('active');
            });
            slides[idx].classList.add('active');
            dots[idx].classList.add('active');
          }

          dots.forEach(function(dot, idx) {
            dot.addEventListener('click', function() {
              goTo(idx);
            });
          });

          // 슬라이드 클릭 시 이동
          slides.forEach(function(slide) {
            slide.style.cursor = 'pointer';
            slide.addEventListener('click', function() {
              var link = this.getAttribute('data-link');
              var linkType = this.getAttribute('data-link-type');
              if (link && link !== '#') {
                window.open(link, linkType);
              }
            });
          });
        });
      </script>
    </div>
    <div class="section main-recruit">
      <div class="container">
        <div class="section-headline">
          <h3 class="primary-title">추천공고</h3>
        </div>

        <div class="recruit-recomm">
          <!-- 첫 번째 슬라이더 (위쪽) -->
          <div class="slider-row">
            <div class="slider-wrap keen-slider swiper-container" id="my-keen-slider-top">
            </div>
          </div>

          <!-- 두 번째 슬라이더 -->
          <div class="slider-row">
            <div class="slider-wrap keen-slider swiper-container" id="my-keen-slider-second">
            </div>
          </div>

          <!-- 세 번째 슬라이더 -->
          <div class="slider-row">
            <div class="slider-wrap keen-slider swiper-container" id="my-keen-slider-third">
            </div>
          </div>

          <!-- 네 번째 슬라이더 (아래쪽) -->
          <div class="slider-row">
            <div class="slider-wrap keen-slider swiper-container" id="my-keen-slider-bottom">
            </div>
          </div>
        </div>
        <a href="/recruit/job_list" target="_blank" class="btn-view-all">더보기</a>
      </div>
    </div>

    <div class="section main-company">
      <div class="container">
        <h3 class="primary-title">지금 뜨는 업체</h3>
        <div class="bottom-recomm">
          <?php
          $count = 0;
          foreach ($up_list as $row) :
            if ($count >= 10) break;
          ?>
            <div class="comp-info" data-comm_idx="<?= $row["mm_idx"] ?>">
              <!-- <p class="comp-name"><?= $row["mm_comp_name"] ?></p> -->
              <div class="comp-img">
                <img src="<?= getCompImg($row) ?>">
              </div>
              <p class="comp-desc"><?= getRandomCompDesc($row["mm_comp_name"]) ?></p>
            </div>
          <?php
            $count++;
          endforeach;
          ?>
        </div>
        <!-- <a href="/recruit/companies" class="btn-view-all">더보기</a> -->
      </div>
    </div>

    <div class="section main-person recomm-slider person">
      <div class="container">
        <div class="section-headline">
          <h3 class="primary-title">추천인재</h3>
        </div>
        <div class="slider-wrap keen-slider" id="my-keen-slider2">
        </div>
        <!-- <a href="/resume/hr_list" target="_blank" class="btn-view-all">더보기</a> -->
      </div>
    </div>


  </main><!-- //page-content -->
</div><!-- //page -->


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
</script>

<?php
$tpl_show_bookmark    = true;
$tpl_slide_wrapper    = false;
$tpl_card_extra_class = 'category2';
include_once(get_base_url() . '/application/shop/views/web/_layout/reco_card_template.php');
?>

<script>
  // 슬라이더 생성 함수
  function createSlider(list, sliderId, arrowClass) {
    var container = document.querySelector('#' + sliderId);
    container.innerHTML = '';

    var tpl = document.getElementById('tpl_reco_list');
    var tplHtml = tpl ? tpl.innerHTML : '';

    // 4개씩 묶어서 슬라이드 생성
    for (var i = 0; i < list.length; i += 4) {
      var slideHtml = '<div class="keen-slider__slide">';

      // 슬라이드당 최대 4개 카드 생성
      for (var j = 0; j < 4 && i + j < list.length; j++) {
        var row = list[i + j];

        if (row.rc_nation_nm == "무관" || row.rc_nation_nm == "내국인,외국인") {
          row.rc_nation_nm = "국적무관";
        } else {
          row.rc_nation_nm = row.rc_nation_nm.replace("만", "");
        }

        var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
        $images.slice(2).remove();
        var $categoryHtml = $images.parent().html();

        var priceType = "";
        if (row.rc_price_type.indexOf('협의') > -1) {
          priceType = "<em> </em>";
        } else {
          priceType = getConvertPriceType(row.rc_price_type, row.rc_price_rep);
        }

        var pricePay = "";
        if (row.rc_price_type.indexOf('협의') > -1) {
          pricePay = "급여협의";
        } else {
          pricePay = Number(row.rc_price_pay).toLocaleString() + "원";
        }

        var cardHtml = tplHtml;
        cardHtml = cardHtml.replace(/\{\{rc_idx\}\}/g, row.rc_idx);
        cardHtml = cardHtml.replace('{{rt_recruit_img}}', row.rt_recruit_img);
        cardHtml = cardHtml.replace('{{rt_work_img}}', $categoryHtml);
        cardHtml = cardHtml.replace('{{ad_addr}}', fRemoveBigAdrJS(row.ad_addr));
        cardHtml = cardHtml.replace('{{rc_name}}', row.rc_name);
        cardHtml = cardHtml.replace('{{rc_nation_nm}}', row.rc_nation_nm);
        cardHtml = cardHtml.replace('{{rc_age_nm}}', row.rc_age_nm);
        cardHtml = cardHtml.replace('{{rt_work_type}}', row.rt_work_type);
        cardHtml = cardHtml.replace('{{rc_price_type}}', priceType);
        cardHtml = cardHtml.replace('{{rc_price_pay}}', pricePay);
        cardHtml = cardHtml.replace('{{book_yn}}', row.book_yn == 'Y' ? 'on' : '');

        slideHtml += cardHtml;
      }

      slideHtml += '</div>';
      container.insertAdjacentHTML('beforeend', slideHtml);
    }

    var slides = container.querySelectorAll('.keen-slider__slide');
    var total = slides.length;

    function nauriControls(slider) {
      var leftArrow = document.querySelector('.' + arrowClass + '.arrow--left');
      var rightArrow = document.querySelector('.' + arrowClass + '.arrow--right');

      if (leftArrow) {
        leftArrow.addEventListener('click', function() {
          slider.prev();
        });
      }

      if (rightArrow) {
        rightArrow.addEventListener('click', function() {
          slider.next();
        });
      }
    }

    if (total > 0) {
      var slider = new KeenSlider('#' + sliderId, {
        loop: total > 1,
        drag: true,
        renderMode: 'performance',
        rubberband: true,
        slides: {
          perView: 1,
        },
      }, [nauriControls]);
    }
  }
  // 추천인재 리스트 로드
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

        // 10개씩 묶어서 슬라이드 생성 (5개씩 2줄)
        for (var i = 0; i < list.length; i += 10) {
          var slideHtml = '<div class="keen-slider__slide"><div class="person-card-grid">';

          // 슬라이드당 최대 10개 카드 생성
          for (var j = 0; j < 10 && i + j < list.length; j++) {
            var row = list[i + j];
            slideHtml +=
              '<div class="person-card" data-ru_idx="' + row.ru_idx + '" data-mm_idx="' + (row.mm_idx || "") + '">' +
              '<div class="thumb"><img src="' + row.ru_resume_img + '" alt="프로필"></div>' +
              '<div class="badge-wrap">' + row.ru_work_img + '</div>' +
              '<p class="name">' + row.ru_name + '</p>' +
              '<div class="meta">' +
              '<p class="work_type">' + getWorkTypeFirst(row.ru_work_type) + '</p>' +
              '<span class="meta-divider">|</span>' +
              '<p class="nation">' + row.mm_nation + '</p>' +
              '<span class="meta-divider">|</span>' +
              '<p class="age">' + row.mm_age + '대</p>' +
              '<span class="meta-divider">|</span>' +
              '<p class="price_type">' + getPriceTypeJSFirst(row.ru_price_type_raw) + '</p>' +
              '</div>' +
              '<p class="addr">' + fRemoveBigAdrJS(row.ad_addr) + '</p>' +
              '</div>';
          }

          slideHtml += '</div></div>';
          $("#my-keen-slider2").append(slideHtml);
        }

        var slideCount = document.querySelectorAll('.main-person .slider-wrap .keen-slider__slide').length;
        var perView = 1;

        var slider = new KeenSlider(".main-person .slider-wrap", {
          loop: slideCount > perView,
          slides: {
            perView: perView
          },
        });
      } else {

      }
    });
  }


  // 현재 로그인한 사용자 정보
  var currentUserMmIdx = <?php echo $this->user->isLogin() ? $this->user->getUserIdx() : 'null'; ?>;
  var currentUserType = '<?php echo isset($mm_type) ? $mm_type : ""; ?>';

  window.addEventListener('DOMContentLoaded', function() {
    getRecoList();
    getRecoPersonList();
  });
  // 개인회원의 경우 자신의 이력서만 클릭 가능, 기업회원은 모든 이력서 클릭 가능
  $(document).on('click', '#my-keen-slider2 .person-card', function() {
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
  // 공고 카드 클릭 시 상세 페이지로 이동
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