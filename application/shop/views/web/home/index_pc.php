<?php
$banner_list = getBannerList("BN00010005");
?>
<?= getCachCss(RES_PATH . "/css/recruit_card.css") ?>
<?= getCachCss(RES_PATH . "/css/nv_reco.css") ?>
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
        <button type="button" aria-controls="noticePopup1" class="btn btn-secondary js-close-popup js-hidden"><?= t('오늘 하루 열지 않기') ?><!--다시 보지 않기--></button>
        <button type="button" aria-controls="noticePopup1" class="btn btn-primary js-close-popup js-close"><?= t('닫기') ?></button>
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
          <h3 class="primary-title"><?= t('추천공고') ?></h3>
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
        <a href="/recruit/job_list" target="_blank" class="btn-view-all"><?= t('더보기') ?></a>
      </div>
    </div>

    <!-- 추천기업 -->
    <section class="nv nv-band">
      <div class="container">
        <div class="nv-head">
          <div>
            <h2><?= t('추천기업') ?></h2>
            <p><?= t('요즘 구직자들이 많이 찾는 기업이에요') ?></p>
          </div>
          <a class="nv-more" href="/recruit/companies"><?= t('더보기') ?> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        </div>
        <div class="nv-comps">
          <?php foreach (array_slice((array) $up_list, 0, 9) as $row) :
            $comp_name = htmlspecialchars($row["mm_comp_name"], ENT_QUOTES, 'UTF-8');
            $comp_url = "/recruit/company/" . $row["mm_idx"];
            $comp_desc = preg_match('/<span[^>]*>([^<]*)<\/span>$/', getRandomCompDesc($row["mm_comp_name"]), $m) ? $m[1] : "";
          ?>
            <article class="nv-card nv-comp">
              <div class="nv-logo"><img src="<?= getCompImg($row) ?>" alt="<?= $comp_name ?> <?= t('로고') ?>"></div>
              <div class="nv-comp-txt">
                <h3 class="nv-comp-name"><a href="<?= $comp_url ?>" target="_blank"><?= $comp_name ?></a></h3>
                <p class="nv-comp-desc"><?= $comp_desc ?></p>
                <span class="nv-live"><?= t('채용 중') ?></span>
              </div>
              <span class="nv-go"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
              <a class="nv-cover" href="<?= $comp_url ?>" target="_blank" aria-hidden="true" tabindex="-1"></a>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- 추천인재 -->
    <section class="nv nv-sec">
      <div class="container">
        <div class="nv-head">
          <div>
            <h2><?= t('추천인재') ?></h2>
            <p><?= t('우리 회사 근처에서 일자리를 찾는 인재예요') ?></p>
          </div>
          <a class="nv-more" href="/resume/hr_list"><?= t('더보기') ?> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        </div>
        <div class="nv-people2" id="nvPeople"></div>
      </div>
    </section>


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

        // 국적 라벨은 서버(getRecruitRecoList)가 이미 "국적무관"/번역 처리까지 끝내서 내려줌 — 그대로 사용

        var $images = $('<div>' + row.rt_work_img + '</div>').find('.category-label');
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
          pricePay = t("급여협의");
        } else {
          pricePay = Number(row.rc_price_pay).toLocaleString() + t("원");
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
      "limit": "8"
    };
    comAjax("/resume_proc/search_reco_list2025", params, function(ret) {
      if (ret.code == "00") renderNvPeople(ret.data.list, "#nvPeople");
    });
  }


  window.addEventListener('DOMContentLoaded', function() {
    getRecoList();
    getRecoPersonList();
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