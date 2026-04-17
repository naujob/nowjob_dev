<?php
$banner_list = getBannerList("BN00302011");

# IOS 검수 대비
if ($g_ios_publish == true) {
  foreach ($popup_list as $key => $popup) {
    if ($popup["pp_idx"] != "7" && $popup["pp_idx"] != "8") {
      unset($popup_list[$key]);
    }
  }

  foreach ($banner_list as $key => $bb) {
    if ($bb["bd_idx"] == "28" || $bb["bd_idx"] == "26" || $bb["bd_idx"] == "25" || $bb["bd_idx"] == "34" || $bb["bd_idx"] == "32" || $bb["bd_idx"] == "30"  || $bb["bd_idx"] == "29") {
      unset($banner_list[$key]);
    }
  }
}
?>
<!-- 이벤트팝업 -->
<?php if ($popup_list != null) : ?>
  <div id="notice1Panel" class="layer-panel card-type notify" tabindex="-1">
    <div class="panel-container">
      <div class="panel-header">
        <h2 class="sr-text">이벤트 알림</h2>
      </div>
      <div class="panel-body">
        <div class="panel-content">
          <div class="event-banner">
            <div class="notice-slider keen-slider">
              <?php foreach ($popup_list as $popup) : ?>
                <div class="keen-slider__slide">
                  <a href="<?= $popup["pp_link_m"] ?>"><img src="<?= $popup["pp_pc_img"] ?>" alt="<?= $popup["pp_name"] ?>"></a>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <div class="sticky-bottom view-footer">
          <button type="button" aria-controls="notice1Panel" onclick="setCookie('notice1Panel', 'hide', 1);return false;" class="btn btn-secondary js-toggle-panel">오늘 하루 열지 않기</button>
          <button type="button" aria-controls="notice1Panel" class="btn btn-primary js-toggle-panel">확인</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    /* 쿠키추가 */
    function setCookie(cookie_name, value, days) {
      var exdate = new Date();
      var cookie_value;

      exdate.setDate(exdate.getDate() + days);
      cookie_value = escape(value) + ((days == null) ? '' : '; expires =' + exdate.toUTCString());
      document.cookie = cookie_name + '=' + cookie_value;
    }

    /* 쿠키 가져오기 */
    function getCookie(cookie_name) {
      return document.cookie.indexOf(cookie_name);
    }

    /* 쿠키체크 후 팝업 열기 */
    function showNotice(selector, cookie_name) {
      if (getCookie(cookie_name) < 0) {
        var targetElement = document.querySelector(selector);
        if (!targetElement) return;

        // openPanel이 정의되어 있는지 확인
        if (typeof window.openPanel === 'function') {
          openPanel(targetElement);
        } else {
          // openPanel이 아직 로드되지 않았으면 잠시 후 재시도
          var retryCount = 0;
          var maxRetries = 20; // 최대 2초 대기 (20 * 100ms)

          var checkOpenPanel = setInterval(function() {
            retryCount++;
            if (typeof window.openPanel === 'function') {
              clearInterval(checkOpenPanel);
              openPanel(targetElement);
            } else if (retryCount >= maxRetries) {
              clearInterval(checkOpenPanel);
              console.warn('openPanel 함수를 로드할 수 없습니다.');
            }
          }, 100);
        }
      }
    }

    window.addEventListener('DOMContentLoaded', function() {
      var slider = document.querySelector('.event-banner .notice-slider');

      showNotice('#notice1Panel', 'notice1Panel');

      if (typeof KeenSlider !== undefined) {
        new KeenSlider(slider, {
          loop: false,
          defaultAnimation: {
            duration: 1000,
          },
        }, [
          addActiveClass,
          // sliderDots,
          function autoplay(slider) {
            sliderAutoplay(slider, 4000, false);
          }
        ]);
      }
    });
  </script>
<?php endif; ?>

<script>
  // 지금 뜨는 업체 슬라이더 초기화 (조건 밖에서 실행)
  window.addEventListener('DOMContentLoaded', function() {
    var hotSlider = document.querySelector('.hot-list .slider-wrap');
    var prevBtn = document.querySelector('.slider-nav-prev');
    var nextBtn = document.querySelector('.slider-nav-next');

    if (hotSlider && typeof KeenSlider !== 'undefined') {
      // 이미 초기화되었으면 스킵
      if (!hotSlider.__keenslider__) {
        // loop peek을 위해 슬라이드가 3개 미만이면 복제
        var slides = hotSlider.querySelectorAll('.keen-slider__slide');
        if (slides.length > 0 && slides.length < 4) {
          var cloneCount = Math.ceil(4 / slides.length) * slides.length;
          var added = slides.length;

          // 이미지 src 매핑 (복제 후 src 손실 방지)
          var imageSrcMap = {};
          slides.forEach(function(slide, idx) {
            slide.querySelectorAll('img').forEach(function(img, imgIdx) {
              imageSrcMap[idx + '-' + imgIdx] = img.src;
            });
          });

          while (added < cloneCount) {
            slides.forEach(function(slide, slideIdx) {
              if (added < cloneCount) {
                var cloned = slide.cloneNode(true);
                // 복제 후 이미지 처리: data-src → src로 변환 및 data-loaded 설정
                cloned.querySelectorAll('img').forEach(function(img, imgIdx) {
                  var srcKey = slideIdx + '-' + imgIdx;
                  if (imageSrcMap[srcKey]) {
                    img.src = imageSrcMap[srcKey];
                  }
                  // Lozad가 적용된 이미지 처리
                  if (img.classList.contains('lozad')) {
                    // data-src가 있으면 src로 변환
                    if (img.getAttribute('data-src') && !img.src) {
                      img.src = img.getAttribute('data-src');
                    }
                    img.setAttribute('data-loaded', 'true');
                  }
                });
                hotSlider.appendChild(cloned);
                added++;
              }
            });
          }
        }

        var slider = new KeenSlider(hotSlider, {
          loop: true,
          slides: {
            perView: 1.1,
            spacing: 12,
          },
          drag: true,
          dragMinDist: 0,
          rubberband: true,
          renderMode: 'precision'
        });

        // 이전 버튼 클릭
        if (prevBtn) {
          prevBtn.addEventListener('click', function() {
            slider.prev();
          });
        }

        // 다음 버튼 클릭
        if (nextBtn) {
          nextBtn.addEventListener('click', function() {
            slider.next();
          });
        }
      }
    }
  });
</script>
<!-- 홈 페이지 특화 스타일 -->
<style>
  /* 지금 뜨는 업체 슬라이더 양쪽 peek — container overflow 재정의 */
  .section:has(.hot-list) .container {
    overflow: visible;
  }

  .section:has(.hot-list) {
    overflow: hidden;
    /* 섹션 밖으로는 클리핑 */
  }

  .result-list-card .content-li {
    border: 1px solid #EB5349;
  }



  .result-list-card .content-info .summary3 em {
    padding-left: 5px;
  }



  .result-list-card .content-info .summary2 {
    font-size: 18px;
    font-weight: 700;
    color: #1b9d7d;
  }

  .result-list-card .content-info .summary2 .period {
    font-size: 16px;
    font-weight: 600;
  }

  .result-list-card .content-info .summary2 .period em.on {
    color: #1b9d7d;
  }

  .item-info .thumb {
    width: 5rem;
  }

  .item-info {
    padding: 0.4rem 0;
  }

  .item-info .comp_name {
    margin-top: 2px;
  }

  .item-info .title {
    margin: 2px 0 2px;
  }

  .item-info .meta {
    margin: 2px -2px 0 0;
  }

  .item-info .working-time {
    /* font-size: 0.7rem; */
    line-height: 1rem;
  }

  .item-info .area {
    max-height: 1.3rem;
    margin-top: 4px;
  }

  .item-info {
    margin: 5px 0;
  }

  .item-info .amount {
    letter-spacing: -2px;
  }



  .section+.section {
    margin-top: 0;
    border-top: 0;
  }
</style>
<style>
  #app-intercept-layer {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    justify-content: center;
    padding: 0 1rem 1rem;
    z-index: 1300;
    pointer-events: none;
    transform: translateY(110%);
    transition: transform 0.3s ease, opacity 0.3s ease;
    opacity: 0;
  }

  #app-intercept-layer.visible {
    transform: translateY(0);
    opacity: 1;
    pointer-events: auto;
  }

  #app-intercept-layer .app-intercept-panel {
    width: 100%;
    max-width: 480px;
    background: #fff;
    border-radius: 16px 16px 0 0;
    box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.2);
    padding: 1.25rem;
    border: 1px solid #f0f0f0;
    text-align: center;
  }

  #app-intercept-layer .app-intercept-label {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #57CCAF;
    margin-bottom: 0.35rem;
  }

  #app-intercept-layer .app-intercept-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
    color: #1b1b1b;
  }

  #app-intercept-layer .app-intercept-description {
    margin: 0.35rem 0 1.15rem;
    font-size: 0.95rem;
    color: #5b5b5b;
  }

  #app-intercept-layer .app-intercept-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
  }

  #app-intercept-layer .app-intercept-actions button {
    flex: 1 1 45%;
    min-width: 105px;
    height: 60px;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    border: none;
    font-size: 1.2rem;
    font-weight: 600;
    cursor: pointer;
  }

  #app-intercept-layer .app-intercept-actions .app-intercept-open {
    background: linear-gradient(135deg, #57CCAF, #57CCAF);
    color: #fff;
  }

  #app-intercept-layer .app-intercept-actions .app-intercept-close {
    background: #f0f0f0;
    color: #333;
  }

  /* Auto resume flow loading */
  #auto-resume-loading {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.35);
    backdrop-filter: blur(2px);
  }

  #auto-resume-loading .loading-card {
    width: min(86vw, 320px);
    padding: 20px 18px;
    border-radius: 16px;
    background: #fff;
    text-align: center;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
  }

  #auto-resume-loading .loading-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1B9D7D;
    margin: 0 0 6px;
  }

  #auto-resume-loading .loading-desc {
    font-size: 0.9rem;
    color: #6b6b6b;
    margin: 0 0 14px;
  }

  #auto-resume-loading .loading-spinner {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 3px solid #e8f4f1;
    border-top-color: #1B9D7D;
    animation: auto-resume-spin 0.8s linear infinite;
    margin: 0 auto;
  }

  @keyframes auto-resume-spin {
    to {
      transform: rotate(360deg);
    }
  }
</style>

<div id="app-intercept-layer" aria-hidden="true">
  <div class="app-intercept-panel">
    <p class="app-intercept-label">나우잡</p>
    <p class="app-intercept-title">앱에서 더 빠르게 NOW JOB</p>
    <p class="app-intercept-description">앱으로 이동하면 알림과 맞춤 추천을 바로 확인할 수 있어요.</p>
    <div class="app-intercept-actions">
      <button type="button" class="app-intercept-open">앱으로 볼래요</button>
      <button type="button" class="app-intercept-close">이대로 볼래요</button>
    </div>
  </div>
</div>

<div id="auto-resume-loading" aria-hidden="true">
  <div class="loading-card" role="status" aria-live="polite">
    <p class="loading-title">이력서 자동 작성 중</p>
    <p class="loading-desc">잠시만 기다려주세요.</p>
    <div class="loading-spinner" aria-hidden="true"></div>
  </div>
</div>
<div class="page page-home">



  <!-- 알림 패널 - 주석처리됨 -->
  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span>
      <span class="cnt"><?= $g_alarm_cnt ?></span>
    </a> -->

  <div class="page-content">
    <?php include(APPPATH . 'views/mobile/_partial/main_visual.php'); ?>


    <!--지금뜨는업체-->
    <div class="section">
      <div class="container">
        <h2 class="secondary-title index">지금 뜨는 업체</h2>
        <!-- <a href="/recruit/clist" class="btn-view-all">더보기</a> -->
        <div class="hot-list">
          <div class="slider-wrap keen-slider">
            <?php
            // 최대 8개 업체 (2슬라이드 × 4개)
            $limited_up_list = isset($up_list) && is_array($up_list) ? array_slice($up_list, 0, 8) : array();
            // 4개씩 청크로 분할 (한 슬라이드 = 2x2 그리드)
            $chunked_up_list = array_chunk($limited_up_list, 4);

            // 마지막 청크 null 패딩
            if (!empty($chunked_up_list)) {
              $last_idx = count($chunked_up_list) - 1;
              while (count($chunked_up_list[$last_idx]) < 4) {
                $chunked_up_list[$last_idx][] = null;
              }
            }

            foreach ($chunked_up_list as $chunk) :
            ?>
              <div class="keen-slider__slide">
                <div class="hot-grid">
                  <?php foreach ($chunk as $row) : ?>
                    <?php if ($row !== null) : ?>
                      <!-- 실제 업체 아이템 -->
                      <a href="/recruit/company/<?= $row["mm_idx"] ?>" class="hot-item">
                        <div class="thumb">
                          <img data-src="<?= getCompImg($row) ?>" alt="" class="lozad">
                          <p class="name"><?= $row["mm_comp_name"] ?></p>
                        </div>
                      </a>
                    <?php else : ?>
                      <!-- 빈 패딩 셀: 그리드 자리 유지 -->
                      <div class="hot-item hot-item--empty">
                        <div class="thumb"></div>
                      </div>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="container">
        <h2 class="secondary-title">추천공고</h2>
        <ol class="result-list result-list-card">
        </ol>
        <!-- <a href="/recruit/job_list" class="btn-view-all">더보기</a> -->
      </div>
    </div>
    <!-- 네이티브 광고 영역 -->
    <div id="native-ad-container" style="height: 250px; width: 100%; display: block; background: #f0f0f0; margin: 20px 0; border: 2px solid #1b9d7d; position: relative; z-index: 100;"></div>
    <div class="footer">
      <p class="name">
        <span class="company-text"><strong>NOW D&I Co., Ltd (주)나우디앤아이</strong></span>
        <button class="toggle-btn" aria-label="회사 정보 열기/닫기">
          <span class="icon-right" style="display:inline;vertical-align:middle;">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
              <polyline points="7,5 13,10 7,15" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" />
            </svg>
          </span>
          <span class="icon-down" style="display:none;vertical-align:middle;">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
              <polyline points="5,7 10,13 15,7" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" />
            </svg>
          </span>
        </button>
      </p>
      <div class="service-provider" style="display:none;">
        <p>
          <span>Representative name : HAN OG CHOI</span><br />
          <span style="font-size: 9px;">Business registration number : 634-88-03093<br />
            Mail order business : 2025-인천연수구-1886<br />
            Address : 14, Songdogukje-daero 286beon-gil, Yeonsu-gu, Incheon<br />
            Business of disseminating job information : J15000202540002<br />
            Tel : 032-833-0018 E-mail : nau2023@naver.com
          </span>
        </p>
        <p>Copyright NOW D&I Co., Ltd all right reserved.</p>
      </div>
    </div>
    <a href="#app" class="btn-top js-goto-top"><span class="sr-text">Top</span></a>
  </div><!-- //page-content -->



  <!-- bottom-nav -->
  <?php $select_menu = "home"; ?>
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
  <!-- //bottom-nav -->
</div>
<!-- 알림목록 - 주석처리됨 -->
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<?php $tpl_id = 'tpl_list'; ?>
<?php include(APPPATH . 'views/mobile/_partial/recruit_card_template.php'); ?>

<script>
  function iosTest(args) {
    alert("ios_test::" + args);
    return "OK";
  }

  function iosSend() {
    var send_data = {
      action: "poing",
      params: {
        count: 1
      }
    };
    window.webkit.messageHandlers.grant_push.postMessage(send_data);
  }

  function getRecoList() {

    var params = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "24"
    };

    comAjax("/recruit_proc/getRecruitRecoList", params, function(ret) {
      if (ret.code == "00") {
        var list = ret.data.list;
        $(".result-list").html("");

        list.forEach(function(row) {
          var $clone = $("#tpl_list").clone();
          var $content = $clone.html();
          $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
          $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
          $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
          // 제목이 18자 이상이면 ...으로 자르기
          var rc_name = row.rc_name;
          if (rc_name && rc_name.length > 18) {
            rc_name = rc_name.substring(0, 18) + '...';
          }

          $content = $content.replaceAll("{{rc_name}}", rc_name);
          $content = $content.replaceAll("{{rc_age_nm}}", row.rc_age_nm);
          $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);
          $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img);
          $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type || "");
          if (row.rc_nation_nm == "무관" || row.rc_nation_nm == "내국인,외국인") {
            $content = $content.replaceAll("{{rc_nation_nm}}", "국적무관");
          } else {
            $content = $content.replaceAll("{{rc_nation_nm}}", row.rc_nation_nm.replace("만", ""));
          }
          $content = $content.replaceAll("{{rc_price_type}}", row.rt_price_type || '');
          $content = $content.replaceAll("{{rc_price_pay}}", row.rt_price_pay || '');
          $clone.html($content);

          $(".result-list").append($clone.html());
        });

      } else {

      }
    });
  }

  function fRemoveBigAdrJS(arg) {
    // null 또는 undefined 체크
    if (!arg || typeof arg !== 'string') {
      return '';
    }
    const parts1 = arg.split(",");
    arg = parts1[0];
    arg = arg.replace("서울특별시 ", "서울시 ");
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
      // parts[2]가 숫자를 포함하고 있으면 parts[1]만 결합 (parts[2]는 제외)
      if (/\d/.test(parts[2])) {
        return parts[0] + " " + parts[1];
      } else {
        return parts[0] + " " + parts[2];
      }
    } else {
      return arg;
    }
  }

  window.addEventListener('DOMContentLoaded', function() {
    getRecoList();
    $('.footer .toggle-btn').on('click', function() {
      var $serviceProvider = $('.footer .service-provider');
      var $iconRight = $(this).find('.icon-right');
      var $iconDown = $(this).find('.icon-down');
      if ($serviceProvider.hasClass('show')) {
        $serviceProvider.removeClass('show').hide();
        $iconRight.show();
        $iconDown.hide();
      } else {
        $serviceProvider.addClass('show').show();
        $iconRight.hide();
        $iconDown.show();
      }
    });
  });

  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.location.href = url;
    }
  });
</script>
<script>
  (function() {
    const layer = document.getElementById("app-intercept-layer");
    if (!layer) {
      return;
    }

    const COOL_DOWN_KEY = "nowjobAppLayerCooldownAt";
    // const COOL_DOWN_MS = 2 * 60 * 60 * 1000;
    const COOL_DOWN_MS = 0;
    const OPEN_SCHEME = "nowjob://home";
    const ANDROID_PKG = "app.nowapk.com";
    const ANDROID_STORE_INTENT = "intent://details?id=" + ANDROID_PKG + "#Intent;scheme=market;package=com.android.vending;end";
    const ANDROID_STORE_MARKET = "market://details?id=" + ANDROID_PKG;
    const ANDROID_STORE_WEB = "https://play.google.com/store/apps/details?id=" + ANDROID_PKG;
    const IOS_STORE = "https://apps.apple.com/kr/app/id6446226901";
    const OPEN_DELAY = 200;

    const openButton = layer.querySelector(".app-intercept-open");
    const closeButton = layer.querySelector(".app-intercept-close");

    let fallbackTimer = null;
    let hiddenTimer = null;

    function isMobileBrowser() {
      const ua = navigator.userAgent || "";
      return /Android|iPhone|iPad|iPod/i.test(ua);
    }

    function isIOSWebView() {
      const ua = navigator.userAgent || "";
      const isIOS = /iPhone|iPad|iPod/i.test(ua);
      const isSafari = /Safari/i.test(ua);
      return isIOS && !isSafari;
    }

    function isAppViaUrl() {
      try {
        const query = new URLSearchParams(window.location.search);
        return query.get('is_app') === 'true';
      } catch (ignore) {
        return false;
      }
    }

    function ensureAppFlagForIOSWebView() {
      if (!isIOSWebView() || isAppViaUrl()) {
        return;
      }
      try {
        const url = new URL(window.location.href);
        url.searchParams.set("is_app", "true");
        window.history.replaceState(null, "", url.toString());
      } catch (ignore) {}
    }

    function writeCooldown() {
      try {
        localStorage.setItem(COOL_DOWN_KEY, Date.now().toString());
      } catch (ignore) {}
    }

    function readCooldown() {
      try {
        const stored = localStorage.getItem(COOL_DOWN_KEY);
        const timestamp = Number(stored);
        return Number.isNaN(timestamp) ? null : timestamp;
      } catch (ignore) {
        return null;
      }
    }

    function shouldShowLayer() {
      const timestamp = readCooldown();
      if (!timestamp) {
        return true;
      }
      return Date.now() - timestamp >= COOL_DOWN_MS;
    }

    function showLayer() {
      layer.classList.add("visible");
      layer.setAttribute("aria-hidden", "false");
    }

    function hideLayer() {
      layer.classList.remove("visible");
      layer.setAttribute("aria-hidden", "true");
    }

    function attachFallbackCancels() {
      function cleanup() {
        window.removeEventListener("pagehide", pagehideHandler);
        document.removeEventListener("visibilitychange", visibilityHandler);
        if (hiddenTimer) { // 화면이 숨겨질 때 타이머 취소
          clearTimeout(hiddenTimer);
          hiddenTimer = null;
        }
      }

      function pagehideHandler() {
        // Page is actually leaving (app opened), cancel store fallback.
        cancelFallback();
        cleanup();
      }
      // 화면이 숨겨질 때 타이머 설정
      function visibilityHandler() {
        if (document.visibilityState === "hidden") {
          // Debounce hidden so brief UI changes (alert, scheme prompt) don't cancel fallback.
          hiddenTimer = setTimeout(function() {
            cancelFallback();
            cleanup();
          }, 200);
        } else if (hiddenTimer) {
          clearTimeout(hiddenTimer);
          hiddenTimer = null;
        }
      }

      window.addEventListener("pagehide", pagehideHandler);
      document.addEventListener("visibilitychange", visibilityHandler);

      return cleanup;
    }

    function cancelFallback() {
      if (fallbackTimer) {
        clearTimeout(fallbackTimer);
        fallbackTimer = null;
      }
    }

    function openApp() {
      writeCooldown();
      hideLayer();

      const ua = navigator.userAgent || ""; // iOS인지 안드로이드인지 판별 확인
      const isIOS = /iPhone|iPad|iPod/i.test(ua);
      const storeUrl = isIOS ? IOS_STORE : ANDROID_STORE_INTENT; // 스토어 URL 설정

      const removeCancelHandlers = attachFallbackCancels();
      fallbackTimer = setTimeout(function() {
        removeCancelHandlers();
        if (document.visibilityState === "hidden") {
          return;
        }
        if (isIOS) {
          location.href = storeUrl;
          return;
        }
        location.href = ANDROID_STORE_INTENT;
        setTimeout(function() {
          if (document.visibilityState === "hidden") {
            return;
          }
          location.href = ANDROID_STORE_MARKET;
        }, 300);
        setTimeout(function() {
          if (document.visibilityState === "hidden") {
            return;
          }
          location.href = ANDROID_STORE_WEB;
        }, 900);
      }, OPEN_DELAY);
    }

    function dismissLayer() {
      writeCooldown();
      hideLayer();
    }

    function initializePrompt() {
      ensureAppFlagForIOSWebView();
      if (!isMobileBrowser() || isAppViaUrl()) {
        return;
      }
      if (shouldShowLayer()) {
        showLayer();
      }
    }

    if (document.readyState === "complete" || document.readyState === "interactive") {
      initializePrompt();
    } else {
      window.addEventListener("DOMContentLoaded", initializePrompt);
    }

    if (openButton) {
      openButton.addEventListener("click", openApp);
    }

    if (closeButton) {
      closeButton.addEventListener("click", dismissLayer);
    }
  })();
</script>

<!-- 네이티브 광고 렌더링 -->
<script>
  // Android에서 호출: renderNativeAd({headline, body, icon, rating, cta})
  // ⚠️ 이 함수는 즉시 정의되어야 합니다 (Android가 광고 로드 후 바로 호출)
  window.renderNativeAd = function(adData) {
    console.log('📱 renderNativeAd 호출됨:', adData);

    var container = document.getElementById('native-ad-container');
    if (!container) {
      console.error('❌ native-ad-container를 찾을 수 없습니다!');
      return;
    }

    // 네이티브 광고 HTML 렌더링
    var html = '<div style="background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #e8e8e8; min-height: 200px; display: flex; flex-direction: column; justify-content: center; align-items: center;">';

    if (adData.icon) {
      html += '<div style="text-align: center; margin-bottom: 8px;"><img src="' + adData.icon + '" style="width: 50px; height: 50px; border-radius: 4px;" onerror="this.style.display=\'none\';" /></div>';
    }

    if (adData.headline) {
      html += '<div style="font-weight: 600; font-size: 14px; color: #1b1b1b; margin-bottom: 4px; text-align: center;">' + adData.headline + '</div>';
    }

    if (adData.body) {
      html += '<div style="font-size: 12px; color: #666; margin-bottom: 8px; text-align: center; max-height: 40px; overflow: hidden;">' + adData.body + '</div>';
    }

    if (adData.rating && adData.rating > 0) {
      html += '<div style="font-size: 12px; color: #ff9800; margin-bottom: 8px;">⭐ ' + adData.rating.toFixed(1) + '</div>';
    }

    if (adData.cta) {
      html += '<button id="native-ad-button" type="button" onclick="window.handleNativeAdClick();" style="background: #1b9d7d; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; font-size: 12px; font-weight: 600; width: 100%; max-width: 200px; cursor: pointer; margin-top: 8px;">' + adData.cta + '</button>';
    }

    html += '</div>';

    container.innerHTML = html;
    container.style.backgroundColor = 'transparent';
    container.style.display = 'block';

    console.log('✅ 네이티브 광고 렌더링 완료');
  };

  // 네이티브 광고 클릭 핸들러
  window.handleNativeAdClick = function() {
    console.log('✅ 네이티브 광고 버튼 클릭됨');
    if (typeof APP_NOWAPK !== 'undefined' && typeof APP_NOWAPK.onNativeAdClick === 'function') {
      console.log('📢 APP_NOWAPK.onNativeAdClick() 호출 중...');
      APP_NOWAPK.onNativeAdClick();
    } else {
      console.error('❌ APP_NOWAPK 또는 onNativeAdClick 함수를 찾을 수 없습니다');
    }
  };

  // 초기 플레이스홀더 표시
  document.addEventListener('DOMContentLoaded', function() {
    var nativeAdContainer = document.getElementById('native-ad-container');
    if (nativeAdContainer && nativeAdContainer.innerHTML === '') {
      console.log('✅ native-ad-container 로드됨, 플레이스홀더 표시');
      nativeAdContainer.style.backgroundColor = '#f9f9f9';
      nativeAdContainer.style.display = 'flex';
      nativeAdContainer.style.alignItems = 'center';
      nativeAdContainer.style.justifyContent = 'center';
      nativeAdContainer.innerHTML = '<p style="color: #999; font-size: 12px; margin: 0;">📡 앱에서 광고를 로드 중입니다...</p>';
    }
  });
</script>