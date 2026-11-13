<?= getCachCss(RES_PATH . "/css/resume_list.css") ?>
<?= getCachCss(RES_PATH . "/css/hr_post.css") ?>
<?php
function convertHashtagString($input)
{
  //echo "1:".$input."<br>";
  // 1. 해시태그(#) 제거 및 앞뒤 공백 제거
  $cleaned = preg_replace('/#/', '', $input);

  //echo "2:".$cleaned."<br>";
  // 2. 쉼표(,)를 기준으로 분할 및 공백 제거
  $words = array_map('trim', explode(',', $cleaned));

  //echo "3:".$words."<br>";
  // 3. 빈 요소 제거 (공백 또는 잘못된 쉼표 처리)
  $words = array_filter($words, function ($word) {
    return !empty($word);
  });

  // 4. 변환된 문자열 반환
  $rtn = implode(', ', $words);
  //echo "4:".$rtn."<br>";

  $rtn = str_replace(' ', ', ', $rtn);
  //echo "5:".$rtn."<br>";

  return $rtn;
}

function fRemoveBigAdrPHP($arg)
{

  $parts1 = explode(",", $arg);
  $arg = $parts1[0];

  $replacements = [
    "서울특별시 " => "서울시 ",
    "경기도 " => "",
    "강원특별자치도 " => "",
    "충청남도 " => "",
    "충청북도 " => "",
    "경상남도 " => "",
    "경상북도 " => "",
    "전라남도 " => "",
    "전북특별자치도 " => "",
    "제주특별자치도 " => "",
    "세종특별자치시 " => "세종시 ",
    "광주광역시 " => "광주시 ",
    "인천광역시 " => "인천시 ",
    "울산광역시 " => "울산시 ",
    "부산광역시 " => "부산시 ",
    "대구광역시 " => "대구시 ",
    "대전광역시 " => "대전시 ",
    "경기 " => "",
    "강원 " => "",
    "충남 " => "",
    "충북 " => "",
    "경남 " => "",
    "경북 " => "",
    "전남 " => "",
    "전북 " => "",
    "제주 " => ""
  ];

  foreach ($replacements as $search => $replace) {
    $arg = str_replace($search, $replace, $arg);
  }

  $parts = explode(" ", $arg);
  if (count($parts) >= 3) {
    return $parts[0] . " " . $parts[2];
  } else {
    return $arg;
  }
}

?>
<?php $isOwnResume = true; ?>
<style>
    :root {

    /* page-recruit 폰트 크기 변수 (기본 15px 기준) */
    --page-recruit-font-size: 15px;
    --page-recruit-font-size-xs: 9px;      /* 9px */

    --page-recruit-font-size-10: 10px;     /* 10px */
    --page-recruit-font-size-sm: 12px;      /* 12px */
    --page-recruit-font-size-13: 13px;     /* 13px */
    --page-recruit-font-size-md: 14px;     /* 14px */
    --page-recruit-font-size-base: 15px;      /* 15px */
    --page-recruit-font-size-lg: 16px;     /* 16px */
    --page-recruit-font-size-18: 18px;      /* 18px */
    --page-recruit-font-size-xl: 21px;     /* 21px */
    --page-recruit-font-size-2xl: 27px;     /* 27px */
}
  #header { 
    margin-bottom: 0;
  }
  .item-view .info-table .working-day>li {
    width: 36px;
    height: 36px;
    font-size: 18px;
    line-height: 36px;
  }

  .arrow--disabled {
    display: none;
  }

  .item-view .profile-record>li:last-child {
    border-color: #57CCAF;
  }

  .item-view.person .btn-report {
    margin: 10px 0 0 auto;
  }

  .item-view .star-rating {
    position: absolute;
    top: 138px;
    left: 28px;
    width: 85px;
    height: 15px;
    margin: 5px auto 0;
  }

  .recent-history .history-body {
    padding: 10px 5px;
  }

  .recent-history .keen-slider__slide {
    border: 0px;
  }

  .person .recent-history .recently-slider {
    height: 620px;
  }

  .history-body .content-li {
    position: relative;
    width: 333px;
    height: 95px;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 10px;
    border: 1px solid #57CCAF;
    overflow: hidden;
    background: #fff;
    margin-bottom: 10px;
    transition: background 0.2s ease;
    cursor: pointer;
  }

  .history-body .content-li:hover {
    background: #f8f8f8;
  }

  .history-body .content-li * {
    padding: 0;
    margin: 0;
  }

  .history-body .content-li .content-info {
    width: 100%;
    height: 100%;
    padding: 5px;
  }

  .history-body .content-info .wicon img {
    height: 18px;
  }

  .history-body .content-info .title {
    font-size: 15px;
    font-weight: bold;
    line-height: 25px;
  }

  .history-body .content-info .content-body {
    position: relative;
    overflow: hidden;
  }

  .history-body .content-info .desc {
    position: relative;
    height: 84px;
    margin: 0;
    display: flex;
    flex-direction: column;
  }

  .history-body .content-info .desc .ellipsis {
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .history-body .content-info .img {
    position: absolute;
    width: 84px;
    height: 84px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  .history-body .content-info .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .history-body .content-info .link {
    color: #5085BB;
  }

  .history-body .content-info .summary {
    display: flex;
    font-size: var(--page-recruit-font-size-md);
    gap: 0;
    position: relative;
  }

  .history-body .content-info .summary p {
    position: relative;
    padding-right: calc(8px + 0.05em);
    margin: 0;
    color: #000;
  }

  .history-body .content-info .summary p:not(:first-child) {
    padding-left: 8px;
  }

  .history-body .content-info .summary p:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: -0.875px;
    bottom: 0;
    width: 1.75px;
    height: 0.7em;
    margin: auto;
    background: #777777;
  }

  .history-body .content-info .summary>* {
    min-width: 0;
    max-width: 100%;
    margin-right: 4px;
    margin-bottom: -3px;
  }

  .history-body .content-info .summary .working-day {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin: 0 0 -4px 5px;
    list-style: none;
  }

  .history-body .content-info .summary .working-day>li {
    width: 2.1666666667em;
    height: 2.1666666667em;
    margin: 0 0.5em 4px 0;
    font-size: 9px;
    color: #BEBFC9;
    line-height: 2;
    text-align: center;
    text-indent: -1px;
    border-radius: 100%;
    border: 1px solid #BEBFC9;
    background: #fff;
    float: left;
  }

  .history-body .content-info .summary .working-day>li:last-child {
    margin-right: 0;
  }

  .history-body .content-info .summary .working-day li[aria-hidden=false] {
    color: #fff;
    border-color: transparent;
    background-color: #1B9D7D;
  }


  .history-body .content-info .summary2 {
    position: absolute;
    right: 2px;
    bottom: 0px;
    font-size: var(--page-recruit-font-size-md);
    font-weight: 700;
    letter-spacing: 0;
    color: #777;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }

  .history-body .content-info .summary2 p {
    margin: 0;
    text-align: right;
  }

  .history-body .content-info .summary2 .period {
    display: flex;
    gap: 0;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;
  }

  .history-body .content-info .summary2 .amount em {
    font-style: normal;
  }

  .history-body .content-info .summary2 .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #1b9d7d;
  }

  .history-body .content-info .summary2 .period em:not(:first-child) {
    padding-left: 8px;
  }

  .history-body .content-info .summary2 .period em:not(:last-child) {
    padding-right: calc(8px + 0.05em);
  }

  .history-body .content-info .summary2 .period em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: -0.875px;
    bottom: 0;
    width: 1.75px;
    height: 0.7em;
    margin: auto;
    background: #777777;
  }

  .history-body .content-info .summary3 {
    position: absolute;
    top: 0px;
    right: 2px;
    color: #000;
    width: auto;
    height: 16px;
    cursor: pointer;
    font-size: var(--page-recruit-font-size-md);
    font-weight: 600;
  }

  .history-body .content-info .summary3 .wtype {
    display: flex;
    gap: 0;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;
  }

  .history-body .content-info .summary3 .worktype em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #333;
  }

  .history-body .content-info .summary3 .worktype em:not(:first-child) {
    padding-left: 8px;
  }

  .history-body .content-info .summary3 .worktype em:not(:last-child) {
    padding-right: calc(8px + 0.05em);
  }

  .history-body .content-info .summary3 .worktype em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: -0.875px;
    bottom: 0;
    width: 1.75px;
    height: 0.7em;
    margin: auto;
    background: #777777;
  }

  .history-body .content-info .summary3 .worktype em.wtype1 {
    color: #000;
  }

  .history-body .content-info .summary3 .worktype em.wtype2 {
    color: #000;
  }

  .history-body .content-info .summary3 .worktype em.wtype3 {
    color: #000;
  }

  /* 개인회원이 자신의 이력서를 볼 때 btn-request:before 비활성화 */
  .item-view.person .view-aside .sticky-group.own-resume .btn-request:before {
    display: none;
    content: none;
  }

  /* 개인회원이 자신의 이력서를 볼 때 광고신청 버튼만 배경색 변경 */
  .item-view.person .view-aside .sticky-group.own-resume #btnPromotionResume {
    background-color: #1B9D7D;
  }

  /* 개인회원이 자신의 이력서를 볼 때: 두 버튼 사이 간격 줄이기 */
  .item-view.person .view-aside .sticky-group.own-resume #btnEditResume {
    margin-bottom: -12px;
  }

  /* 개인회원이 자신의 이력서를 볼 때: 수정 버튼 배경 이미지 */
  .item-view.person .view-aside .sticky-group.own-resume #btnEditResume {
    background-image: url('/resource/shop/web/img/reviseBtn.svg');
    background-repeat: no-repeat;
    background-position: 77px center;
    background-size: 35px 35px;
    padding-left: 35px;
  }

  /* 개인회원이 자신의 이력서를 볼 때: 광고신청 버튼 배경 이미지 */
  .item-view.person .view-aside .sticky-group.own-resume #btnPromotionResume {
    background-image: url('/resource/shop/web/img/adBtn.svg');
    background-repeat: no-repeat;
    background-position: 77px center;
    background-size: 35px 35px;
    padding-left: 45px;
  }

  /* ru_price_type 클래스에 대한 스타일 추가 (hr_list.php와 동일) */
  .ru_price_type {
    color: #1B9D7D;
    font-size: 20px;
    font-weight: 600;
  }

  /* ru_price_type 내의 em 태그 기울임 제거 */
  .ru_price_type em {
    font-style: normal;
    position: relative;
    display: inline-block;
    color: #1b9d7d;
  }

  .ru_price_type em.off {
   
  }
  .info-table .value em {
    position: relative;
    display: inline-block;
  }

  .info-table .value em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: -0.875px;
    bottom: 0;
    width: 1.75px;
    height: 0.7em;
    margin: auto;
    background: #777777;
  }

  .info-table .value em:not(:first-child) {
    padding-left: 8px;
  }

  .info-table .value em:not(:last-child) {
    padding-right: calc(8px + 0.05em);
  }
  
  /* ru_price_type 내의 구분자 스타일 (직선 구분자) */
 
  .ru_price_type em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: -0.875px;
    bottom: 0;
    width: 1.75px;
    height: 0.7em;
    margin: auto;
    background: #777777;
  }

  .ru_price_type em:not(:first-child) {
    padding-left: 8px;
  }

  .ru_price_type em:not(:last-child) {
    padding-right: calc(8px + 0.05em);
  }

  .type .gapbar1,
  .type .gapbar2 {
    display: inline-block;
    width: 1.75px;
    height: 0.7em;
    margin: 0 4px;
    vertical-align: middle;
    background: #777777;
  }

  .person-card .meta p {
    width: unset;
  }

  /* info-table 내 em 태그 스타일 (급여형태 표시용) - .worktype 제외 */
  .info-table .value > em {
    font-style: normal;
    color: #1b9d7d;
  }

  .info-table .value > em.off {
    color: #222;
  }
  .history-body .content-info .summary2 .period em.off {
    color: #222;
  }

  .btn-group[data-request-state="none"] .btnGoChat {
    display: none;
  }
  .period-list em {
    display: inline-block;
  }
  .item-view.person .view-content .summary > .inline-group {
    align-items: flex-start;
    gap: 60px;
  }

  /* 근무지역 주소 em: 구분선 규칙만 쓰고 .info-table .value > em의 초록 글자색은 상속으로 해제 */
  #work-area-addr > em {
    color: inherit;
  }

  /* 경력소개 → 자격증 순서로 세로 배치 (모바일과 동일) */
  .view-career-row {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 30px;
    margin-top: 30px;
  }

  .view-career-row > .col {
    flex: 1;
    min-width: 0;
  }

  .view-career-row .section.view-profile {
    padding: 0;
  }

  .view-career-row .info-table .label {
    line-height: 1.8;
  }
    .info-table .value.work-type em {
    color: #222;
}
</style>
<div id="content" class="page page-view page-hr-post page-resume-view">
  <main class="page-content">
    <div class="item-view person">
      <div class="container">
        <!-- 컨텐츠 영역 -->
        <div class="view-content">
          <div class="section view-header">
            <div class="profile">
              <div class="thumb">
                <img src="<?= getResumeImg($info) ?>" onError="this.src='/resource/shop/mobile/img/common/noimage3.png'" alt="">
              </div>
              <!-- 평점 -->
              <p class="star-rating">
                <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                <span class="star" style="width:100%;"></span>
                <span class="sr-text"><?= t('평점') ?></span>
                <span class="sr-text">5</span>
              </p>
              <ul class="profile-record">
                  <li><span class="label"><?= t('프로필 조회') ?></span><em class="value"><?= $info["ru_hit"] ?></em></li>
                  <li><span class="label"><?= t('받은제안') ?></span><em class="value"><?= $info["req_cnt"] ?></em></li>
                  <li><span class="label"><?= t('관심인재') ?></span><em class="value"><?= $info["fav_cnt"] ?></em></li>
                </ul>

            </div>


          </div>



          <nav class="hr-tabs" aria-label="<?= t('이력서 항목') ?>">
            <div class="hr-tabs-in">
              <button type="button" class="on" data-go="#hrCondition"><?= t('근무조건') ?></button>
              <button type="button" data-go="#hrIntro"><?= t('자기소개') ?></button>
              <button type="button" data-go="#hrCareer"><?= t('자격증·경력') ?></button>
            </div>
          </nav>
          <div class="summary" id="hrCondition">
                  <h3 class="secondary-title"><?= t('근무조건') ?></h3>
              <table class="info-table">
                <caption><span class="sr-text"><?= t('희망근무조건') ?></span></caption>
                <colgroup>
                  <col>
                  <col>
                </colgroup>
                <tbody>
                  <tr>
                    <td class="label"><?= t('업무구분') ?></td>
                    <td class="value work-value">
                      <?= getWorkingBadge($info["ru_work"]) ?>
                    </td>
                  </tr>
                  <tr>
                    <td class="label"><?= t('급여형태') ?></td>
                    <td class="value"><?= getConvertResumePriceType($info["ru_price_type"]) ?></td>
                  </tr>
                  <tr>
                    <td class="label"><?= t('근무형태') ?></td>
                    <td class="value work-type"><?= getWorkType2025($info["ru_work_type"]) ?></td>
                  </tr>
                  <tr>
                    <td class="label"><?= t('근무요일') ?></td>
                    <td class="value">
                      <?php
                      // 요일무관 체크 여부 확인
                      $isWeekUnlimited = (isset($info["ru_week_yn"]) && $info["ru_week_yn"] === "Y");
                      // 원본 요일 값 (빈값 체크용)
                      $originalWeek = isset($info["ru_week"]) ? trim((string) $info["ru_week"]) : "";

                      // 비어있거나 모든 요일(월,화,수,목,금,토,일)인지 체크
                      $isAllWeek = ($originalWeek === "" || $originalWeek === "월,화,수,목,금,토,일");
                      ?>
                      <?php if ($isWeekUnlimited || $isAllWeek) : ?>
                        <!-- 무관일 경우 모든 요일 표시 -->
                        <ul class="working-day">
                          <?= getWeekYoil("") ?>
                        </ul>
                      <?php else : ?>
                        <ul class="working-day">
                          <?= getWeekYoil($originalWeek) ?>
                        </ul>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td class="label"><?= t('근무지역') ?></td>
                    <td class="value" id="work-area-addr">
                      <?php foreach ($addr as $key => $ad) : ?>
                        <?php if ($ad["ad_addr"] != "") : ?>
                          <em class="addr-item" data-addr="<?= htmlspecialchars($ad["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($ad["ad_addr"], ENT_QUOTES, 'UTF-8') ?></em>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

          <h3 class="secondary-title hr-intro-title" id="hrIntro"><?= t('자기소개') ?></h3>
          <div class="section view-introduce">
            <div class="introduce-wrap">
              <?= str_replace("\n", "<br/>", $info["ru_desc"]) ?>
            </div>
            <div class="hashtag">
              <?= getHashTag(convertHashtagString($info["ru_my_type"])) ?>
            </div>
          </div>

          <div class="view-career-row" id="hrCareer">
            <div class="col">
              <h3 class="secondary-title"><?= t('자격증') ?></h3>
              <div class="section view-profile">
                  <table class="info-table">
                    <caption><span class="sr-text"></span></caption>
                    <colgroup>
                      <col>
                      <col>
                    </colgroup>
                    <tbody>
                     
                      <tr>
                        <td class="label"><?= t('자격증') ?></td>
                        <td class="value">
                          <ul class="period-list">
                            <?php foreach ($certi_list as $row) : ?>
                              <li>
                                
                                <span><?= $row["rc_name"] ?></span> &nbsp;
                                <em><?= $row["rc_dt"] ?></em>
                              </li>
                            <?php endforeach; ?>
                          </ul>
                        </td>
                      </tr>
                    </tbody>
                  </table>
              </div>
            </div>
            <div class="col">
              <h3 class="secondary-title"><?= t('경력소개') ?></h3>
              <div class="section view-profile">
                  <table class="info-table">
                    <caption><span class="sr-text"><?= t('경력소개') ?></span></caption>
                    <colgroup>
                      <col>
                      <col>
                    </colgroup>
                    <tbody>
                   
                      <tr>
                        <td class="label"><?= t('경력사항') ?></td>
                        <td class="value">
                          <ul class="period-list">
                            <?php foreach ($career_list as $row) : ?>
                              <li>
                                
                                <span><?= $row["rc_name"] ?></span>
                                <?php
                                $is_date = false;
                                $sdate = "";
                                $edate = "";
                                $arr_date = explode("~", $row["rc_date"]);
                                if (count($arr_date) > 1) {
                                  $is_date = true;
                                  $sdate = str_replace(".", "-", trim($arr_date[0])) . "-01";
                                  $edate = str_replace(".", "-", trim($arr_date[1])) . "-01";
                                }
                                ?>
                                &nbsp;
                                <em><?= $row["rc_date"] ?><?= $is_date ? "(" . get_date_diff_txt($sdate, $edate) . ")" : "" ?></em>
                              </li>
                            <?php endforeach; ?>
                          </ul>
                        </td>
                      </tr>
                    </tbody>
                  </table>
              </div>
            </div>
          </div>
          <script>
            /* 이동 탭: 누르면 섹션으로 이동, 스크롤하면 현재 섹션 표시 */
            (function() {
              var nav = document.querySelector('.hr-tabs');
              if (!nav) return;
              var btns = [].slice.call(nav.querySelectorAll('button'));
              var lock = 0;
              function setOn(b) {
                btns.forEach(function(x) {
                  x.classList.toggle('on', x === b);
                  x.setAttribute('aria-current', x === b ? 'true' : 'false');
                });
              }
              btns.forEach(function(b) {
                b.addEventListener('click', function() {
                  var t = document.querySelector(b.getAttribute('data-go'));
                  if (!t) return;
                  setOn(b);
                  lock = Date.now();
                  var y = t.getBoundingClientRect().top + window.pageYOffset - nav.offsetHeight - 80;
                  var smooth = !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
                  window.scrollTo({ top: y, behavior: smooth ? 'smooth' : 'auto' });
                });
              });
              function spy() {
                nav.classList.toggle('stuck', nav.getBoundingClientRect().top <= 1 && window.pageYOffset > 0);
                if (Date.now() - lock < 700) return;
                var cur = btns[0], line = nav.offsetHeight + 120;
                btns.forEach(function(b) {
                  var t = document.querySelector(b.getAttribute('data-go'));
                  if (t && t.getBoundingClientRect().top - line <= 0) cur = b;
                });
                setOn(cur);
              }
              window.addEventListener('scroll', spy, { passive: true });
              spy();
            })();
          </script>





        </div>
        <!-- 컨텐츠 영역 -->

        <!-- aside -->
        <div class="view-aside">
          <div class="sticky-group own-resume">
            <div class="summary-card">
              <div class="top">
                <div class="inline-group">
                  <!-- 카테고리 라벨 -->
                  <?= getWorkingBadge($info["ru_work"]) ?>
                </div>
              </div>
              <!-- 타이틀 -->
              <p class="title"><?= htmlspecialchars($info["ru_name"], ENT_QUOTES, 'UTF-8') ?></p>
              <!-- 기타정보 -->
              <?php $mem_info["ad_addr"] = isset($addr[0]['ad_addr']) ? $addr[0]['ad_addr'] : ''; ?>
              <p class="type">
                <span><?= $mem_info["mm_nation"] ?></span>
                <span class="gapbar1" style="<?= trim($mem_info["mm_nation"]) == "" ? "display:none;" : "" ?>"></span>
                <span><?= getKoreanAge($mem_info["mm_birth"]) ?><?= t('대') ?></span>
                <span class="gapbar2"></span>
                <span class="area-addr" data-addr="<?= htmlspecialchars($mem_info["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($mem_info["ad_addr"], ENT_QUOTES, 'UTF-8') ?></span>
              </p>
            </div>
          </div>
        </div>
        <!-- //aside -->
      </div>
    </div>
    <div class="view-footer">
      <div class="form-action-wrap">
        <button type="button" id="btnDelete" aria-haspopup="dialog" class="btn-round btn-secondary js-remove"><?= t('삭제') ?></button>
        <button type="button" id="btnEdit" class="btn-round btn-tertiary"><?= t('수정') ?></button>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->


<input type="hidden" name="ru_idx" value="<?= $info["ru_idx"] ?>" />
<input type="hidden" name="ru_name" value="<?= $info["ru_name"] ?>" />
<script>
  $(document).ready(function() {
    $("#btnDelete").click(function() {
      var ru_name = $("[name=ru_name]").val();
      var ru_idx = $("[name=ru_idx]").val();
      openPopup('confirm', {
        content: '<em>“' + ru_name + '”</em><br> ' + t('이력서를 삭제하시겠습니까?') + '<br> ' + t('등록하신 모든 데이터는') + '<br> ' + t('삭제됩니다.'),
        firstButton: t('취소'),
        secondButton: t('삭제'),
        secondCallback: function() {
          var params = {};
          params["ru_idx"] = ru_idx;
          comAjax("/mypage_proc/resume_delete", params, function(ret) {
            if (ret.code == "00") {
              location.replace("/mypage/resume_list");
            } else {
              commonAlert(ret.msg, function() {});
              return;
            }
          });
        }
      });
    });

    $("#btnEdit").click(function() {
      var ru_idx = $("[name=ru_idx]").val();
      comAjax("/mypage_proc/resume_start", {ru_idx: ru_idx}, function(ret) {
        if (ret.code == "00") {
          location.href = "/mypage/resume_write/" + ru_idx;
        } else {
          commonAlert(ret.msg, function() {});
          return;
        }
      });
    });

    // 근무지역: 서버가 출력한 .addr-item(data-addr)의 텍스트만 JS로 축약 (resume/hr_post.php와 동일 방식)
    // 축약 후 같은 주소가 되는 항목은 제거하여 중복 표시를 막음
    var workAreaSeen = [];
    $('#work-area-addr .addr-item').each(function() {
      var $item = $(this);
      var originalAddr = $item.attr('data-addr');
      if (!originalAddr || typeof fRemoveBigAdrJS !== 'function') return;

      var formattedAddr = fRemoveBigAdrJS(originalAddr);
      if (!formattedAddr) return;

      if (workAreaSeen.indexOf(formattedAddr) !== -1) {
        $item.remove();
        return;
      }
      workAreaSeen.push(formattedAddr);
      $item.text(formattedAddr);
    });

    // 요약 영역 주소 처리 (.area-addr에 data-addr 사용)
    $('.area-addr[data-addr]').each(function() {
      var $this = $(this);
      var originalAddr = $this.data('addr');
      if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
        var formattedAddr = fRemoveBigAdrJS(originalAddr);
        $this.text(formattedAddr);
      }
    });
  });
</script>