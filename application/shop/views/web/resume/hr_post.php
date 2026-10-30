<?= getCachCss(RES_PATH . "/css/resume_list.css") ?>
<input type="hidden" name="ru_idx" value="<?= $info["ru_idx"] ?>" />
<?php
// 블러 처리 조건 정의
$isLogin = $this->user->isLogin();
$userType = $isLogin ? $this->user->getUserType() : null;
$isOwnResume = ($userType === "MEM" && isset($info["mm_idx"]) && (int)$info["mm_idx"] === (int)$this->user->getUserIdx());

function fWorkColor($arg)
{

  $arg = (strpos($arg, ",") !== false ? "협의" : ($arg));

  $arg = str_replace("주간", "<font style='color:#3a70db;font-weight:600;'>주간</font>", $arg); //파랑
  $arg = str_replace("야간", "<font style='color:#F86048;font-weight:600;'>야간</font>", $arg); //빨강
  $arg = str_replace("2교대", "<font style='color:#1B9D7D;font-weight:600;'>2교대</font>", $arg); //민트
  $arg = str_replace("근무협의", "<font style='color:#333;font-weight:600;'>근무협의</font>", $arg); //검정
  $arg = str_replace("시간협의", "협의", $arg);
  $arg = str_replace("협의", "<font style='color:#333;font-weight:600;'>협의</font>", $arg); //검정
  return $arg;
}

function fPay($arg)
{
  $arg = str_replace("급여협의", "협의", $arg);
  $arg = (strpos($arg, ",") !== false ? "협의" : ($arg));
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
  $addr = str_replace(",", "", $addr);

  $addrArray = explode(" ", $addr);
  // 시·군·구 + 동/읍/면만 표시
  if (count($addrArray) >= 2) {
    $addr = $addrArray[0] . " " . $addrArray[1];
  }
  return $addr;
}

function fNoEmTag($arg)
{
  $arg = str_replace("<em>", "", $arg);
  $arg = str_replace("</em>", "", $arg);
  return $arg;
}

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
  // null 또는 undefined 체크
  if (empty($arg) || !is_string($arg)) {
    return '';
  }

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
<div id="content" class="page page-view">
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
                <span class="sr-text">평점</span>
                <span class="sr-text">5</span>
              </p>
              <?php if ($isOwnResume) { ?>
                <div class="profile-detail">
                  <!-- 이름 -->
                  <p class="name"><em><?= $mem_info["mm_name"] ?></em>님</p>
                  <div class="meta">
                    <!-- 추가사항 -->
                    <p><span><?= $mem_info["mm_nation"] ?></span> / <span><?= $mem_info["ma_age"] ?>세</span></p>
                    <p><?= $mem_info["mm_phone"] ?></p>
                  </div>
                </div>
              <?php } ?>
              <?php
              // 기업회원 정보 캐싱 (카카오/네이버 간편가입자는 PASS 미인증으로 간주)
              $currentUserInfo = $this->user->getCurrentUserInfo();
              $joinType = strtoupper(isset($currentUserInfo["mm_join_type"]) ? $currentUserInfo["mm_join_type"] : "");
              $isSnsJoinWithoutPass = in_array($joinType, ["KAKAO", "NAVER"]);
              $isCorpUser = ($this->user->getUserType() === "COMP");
              $hasBusinessCert = !empty($currentUserInfo["mm_comp_num"]);
              $hasPassCert = isset($currentUserInfo["mm_di"]) && strpos($currentUserInfo["mm_di"], "MC0") === 0;
              $hasEffectivePassCert = $hasPassCert || $isSnsJoinWithoutPass;
              $isCorpCertified = $isCorpUser && $hasBusinessCert && $hasEffectivePassCert;
              ?>
              <?php if ($isOwnResume) { ?>
                <ul class="profile-record">
                  <li><span class="label">프로필 조회</span><em class="value"><?= $info["ru_hit"] ?></em></li>
                  <li><span class="label">받은제안</span><em class="value"><?= $info["req_cnt"] ?></em></li>
                  <li><span class="label">관심인재</span><em class="value"><?= $info["fav_cnt"] ?></em></li>
                </ul>
              <?php } else if (!$isCorpUser) { ?>
                <ul class="profile-record">
                  <li><span class="label">프로필 조회</span><em class="value">0</em></li>
                  <li><span class="label">받은제안</span><em class="value">0</em></li>
                  <li><span class="label">관심인재</span><em class="value">0</em></li>
                </ul>
              <?php } else { //comp
              ?>
                <?php if (!$isCorpCertified) { ?>
                  <ul class="profile-record">
                    <li><span class="label">프로필 조회</span><em class="value">0</em></li>
                    <li><span class="label">받은제안</span><em class="value">0</em></li>
                    <li><span class="label">관심인재</span><em class="value">0</em></li>
                  </ul>
                  <div style="position: absolute; top: 0px; left: 336px; width: 633px; height: 155px;">
                    <!-- 기업회원 인증 안내 문구 비활성화 -->
                    <!-- 사업자 인증 버튼 비활성화
                    <?php if (!$hasBusinessCert) { ?>
                      <a href="javascript:void(0);" id="btnCompCert" style="position: absolute; top: 90px; right: 135px; background-color: #57CCAF; color: #fff; padding: 5px 10px; border-radius: 6px;">사업자 인증</a>
                    <?php } ?>
                    -->
                    <?php if (!$hasEffectivePassCert) { ?>
                      <!-- <a href="/compage/mypage/pass" style="position: absolute; top: 90px; right: 38px; background-color: #57CCAF; color: #fff; padding: 5px 10px; border-radius: 6px;">PASS 인증</a> -->
                    <?php } ?>
                  </div>
                <?php   } else { ?>
                  <ul class="profile-record">
                    <li><span class="label">프로필 조회</span><em class="value"><?= $info["ru_hit"] ?></em></li>
                    <li><span class="label">받은제안</span><em class="value"><?= $info["req_cnt"] ?></em></li>
                    <li><span class="label">관심인재</span><em class="value"><?= $info["fav_cnt"] ?></em></li>
                  </ul>
                <?php     } ?>
              <?php } //comp 
              ?>

            </div>

            <div class="inline-group">
              <!-- 신고하기 -->
              <button type="button" aria-haspopup="dialog" aria-controls="reportForm1" class="btn-report js-report"><span class="sr-text">신고하기</span></button>

              <!-- 신고하기 양식 -->
              <template id="ReportTemp">
                <div class="alert-msg">
                  <p>해당게시글을 신고하시겠습니까?<br> 신고사유를 작성해주세요</p>
                  <div class="report-form">
                    <label>
                      <span class="sr-text">신고사유입력</span>
                      <textarea name="br_desc" placeholder="신고사유를 작성해주세요" autofocus class="form-textarea js-autoheight"></textarea>
                    </label>
                  </div>
                </div>
              </template>
              <!-- //신고하기 양식 -->
              <script>
                /* 신고내용 작성 팝업 */
                window.addEventListener('DOMContentLoaded', function() {
                  document.querySelector('.js-report').addEventListener('click', function() {
                    var content = document.querySelector('#ReportTemp').innerHTML;
                    openPopup('modal', {
                      id: 'reportForm',
                      title: '신고하기',
                      content: content,
                      firstButton: '확인',
                      firstCallback: function() {
                        if ($("[name=br_desc]").val() == "") {
                          commonAlert("내용을 입력해주세요.", function() {
                            $("[name=br_desc]").focus();
                          });
                          return false;
                        }
                        var params = {};
                        params["bd_idx"] = $("[name=ru_idx]").val();
                        params["br_type"] = "resume";
                        params["br_desc"] = $("[name=br_desc]").val();
                        comAjax("/setting_proc/board_report", params, function(ret) {
                          if (ret.code == "00") {
                            removeElement("#ReportWriter1");
                            commonAlert("신고 처리 되었습니다.", function() {});
                          } else {
                            commonAlert("신고 처리시 오류가 발생하였습니다.", function() {});
                          }
                        });
                      }
                    });
                  });
                });
              </script>
            </div>

            <div class="summary">
                  <h3 class="secondary-title">희망근무조건</h3>
              <table class="info-table">
                <caption><span class="sr-text">희망근무조건</span></caption>
                <colgroup>
                  <col>
                  <col>
                </colgroup>
                <tbody>
                  <tr>
                    <td class="label">업무구분</td>
                    <td class="value">
                      <?= getWorkingImg($info["ru_work"]) ?>
                    </td>
                  </tr>
                  <tr>
                    <td class="label">근무지역</td>
                    <td class="value" id="work-area-addr">
                      <?php foreach ($addr as $key => $ad) : ?>
                        <?php if ($ad["ad_addr"] != "") : ?>
                          <em class="addr-item" data-addr="<?= htmlspecialchars($ad["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($ad["ad_addr"], ENT_QUOTES, 'UTF-8') ?></em>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </td>
                  </tr>
                  <tr>
                    <td class="label">급여형태</td>
                    <td class="value"><?= getConvertResumePriceType($info["ru_price_type"]) ?></td>
                  </tr>
                  <tr>
                    <td class="label">근무형태</td>
                    <td class="value work-type"><?= getWorkType2025($info["ru_work_type"]) ?></td>
                  </tr>
                  <tr>
                    <td class="label">근무요일</td>
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
                </tbody>
              </table>
            </div>
          </div>



          <div class="view-career-row">
            <div class="col">
              <h3 class="secondary-title">경력소개</h3>
              <div class="section view-profile" <?php echo (!$isOwnResume && $userType !== 'COMP') ? 'style="filter: blur(3px);"' : ''; ?>>
                  <table class="info-table">
                    <caption><span class="sr-text">경력소개</span></caption>
                    <colgroup>
                      <col>
                      <col>
                    </colgroup>
                    <tbody>
                   
                      <tr>
                        <td class="label">경력사항</td>
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
            <div class="col">
              <h3 class="secondary-title">자격증</h3>
              <div class="section view-profile" <?php echo (!$isOwnResume && $userType !== 'COMP') ? 'style="filter: blur(3px);"' : ''; ?>>
                  <table class="info-table">
                    <caption><span class="sr-text"></span></caption>
                    <colgroup>
                      <col>
                      <col>
                    </colgroup>
                    <tbody>
                     
                      <tr>
                        <td class="label">자격증</td>
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
          </div>




          <h3 class="secondary-title" style="margin-top: 60px;">자기소개</h3>
          <div class="section view-introduce" <?php echo (!$isOwnResume && $userType !== 'COMP') ? 'style="filter: blur(3px);"' : ''; ?>>
            <div class="introduce-wrap">
              <?= str_replace("\n", "<br/>", $info["ru_desc"]) ?>
            </div>
            <div class="hashtag">
              <?= getHashTag(convertHashtagString($info["ru_my_type"])) ?>
            </div>
          </div>
        </div>
        <!-- 컨텐츠 영역 -->

        <!-- aside -->
        <div class="view-aside">

          <?php
          // 개인회원이 자신의 이력서를 볼 경우를 판단 (변수 재사용)
          $isOwnResume = ($this->user->getUserType() === "MEM" && isset($info["mm_idx"]) && $info["mm_idx"] == $this->user->getUserIdx());
          $isPreview = isset($_GET['preview']) && $_GET['preview'] == '1';
          ?>
          <div class="sticky-group<?php if ($isOwnResume) : ?> own-resume<?php endif; ?>">
            <div class="summary-card">
              <div class="top">
                <?php if ($isOwnResume) { ?>
                  <!-- 개인회원이 자신의 이력서를 볼 때: 급여형태 표시 -->
                  <div class="inline-group">
                    <span class="ru_price_type"><?= getConvertResumePriceType($info["ru_price_type"]) ?></span>
                  </div>
                <?php } else { ?>
                  <!-- 기업회원이 볼 때: 카테고리 라벨 -->
                  <div class="inline-group">
                    <!-- 카테고리 라벨 -->
                    <?= getWorkingImg($info["ru_work"]) ?>
                  </div>
                <?php } ?>

                <!-- 공유하기 -->
                <div class="share-wrap">
                  <button type="button" class="btn-share js-toggle-dropdown"><span class="sr-text">공유하기</span></button>
                  <div class="dropdown">
                    <!-- 카카오톡 공유 기능 주석처리 -->

                    <a href="#" onclick="shareMessage();return false;" class="share-link1">
                      <span class="ico"><img src="/resource/shop/web/img/ic_share_vendor1.svg" alt=""></span>
                      <span>카카오톡</span>
                    </a>

                    <a href="#" onclick="CopyUrl();return false;" class="share-link2">
                      <span class="ico"><img src="/resource/shop/web/img/ic_share_copy_url.svg" alt=""></span>
                      <span>URL복사</span>
                    </a>
                  </div>

                  <!-- 카카오 SDK 스크립트 주석처리 -->
                  <script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script>
                  <script>
                    /* 카카오톡 공유 기능 주석처리 */

                    // 카카오 공유 내용 (앱 공유에서도 사용)
                    function getShareKakaoData() {
                      var descParts = <?= json_encode(array_values(array_filter(array(trim($mem_info["mm_nation"]), getKoreanAge($mem_info["mm_birth"]) == "0" ? "" : getKoreanAge($mem_info["mm_birth"]) . "대"))), JSON_UNESCAPED_UNICODE) ?>,
                        firstAddr = $.trim($('#work-area-addr .addr-item').first().text());
                      if (firstAddr) descParts.push(firstAddr);

                      return {
                        title: <?= json_encode(trim($info["ru_name"]) != "" ? trim($info["ru_name"]) : getAstrik($mem_info["mm_name"]), JSON_UNESCAPED_UNICODE) ?>,
                        description: descParts.join(' | '),
                        buttonTitle: '이력서 확인하기',
                        url: location.href
                      };
                    }

                    function shareMessage() {
                      if (!Kakao.isInitialized()) {
                        Kakao.init("<?= $kakao_key["인증키"] ?>");
                      }

                      var data = getShareKakaoData();

                      setTimeout(function() {
                        Kakao.Share.sendDefault({
                          objectType: 'feed',
                          content: {
                            title: data.title,
                            description: data.description,
                            imageUrl: '/resource/shop/web/img/nowjob_info.png',
                            link: {
                              mobileWebUrl: data.url,
                              webUrl: data.url
                            },
                          },
                          buttons: [{
                            title: data.buttonTitle,
                            link: {
                              mobileWebUrl: data.url,
                              webUrl: data.url
                            },
                          }],
                        });
                      }, 100);
                    }


                    /* URL 복사 */
                    function CopyUrl() {
                      var dummy = document.createElement('input'),
                        url = location.href;

                      document.body.appendChild(dummy);
                      dummy.value = url;
                      dummy.select();
                      document.execCommand('copy');
                      document.body.removeChild(dummy);
                      openPopup('alert', {
                        content: 'URL을 복사했습니다.'
                      });
                    }
                  </script>
                </div>

                <!-- 즐겨찾기 -->
                <button type="button" data-bk_type="RESUME" data-bk_key="<?= $info["ru_idx"] ?>" data-user_id="<?= $mem_info["mm_id"] ?>" class="btnBookMark btn-view-bookmark js-toggle-bookmark <?= $book_yn == "Y" ? "on" : "" ?>">
                  <span class="sr-text">즐겨찾기 추가</span>
                </button>
              </div>
              <?php if ($isOwnResume) { ?>
                <!-- 개인회원이 자신의 이력서를 볼 때: 이력서명 표시 -->
                <p class="title"><?= htmlspecialchars($info["ru_name"], ENT_QUOTES, 'UTF-8') ?></p>
              <?php } else { ?>
                <!-- 기업회원이 볼 때: 기존 타이틀 및 기타정보 -->
                <!-- 타이틀 -->
                <p class="title"><?= htmlspecialchars($info["ru_name"], ENT_QUOTES, 'UTF-8') ?></p>
                <!-- 기타정보 -->

                <?php $mem_info["ad_addr"] = isset($addr[0]['ad_addr']) ? $addr[0]['ad_addr'] : ''; ?>

                <p class="type">
                  <?php if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) == "") {
                    $gapbar1 = "display:none;";
                    $gapbar2 = "";
                  } else if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) != "") {
                    $gapbar1 = "display:none;";
                    $gapbar2 = "";
                  } else if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) == "") {
                    $gapbar1 = "display:none;";
                    $gapbar2 = "";
                  } else if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) != "") {
                    $gapbar1 = "display:none;";
                    $gapbar2 = "";
                  } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) == "") {
                    $gapbar1 = "display:none;";
                    $gapbar2 = "";
                  } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) != "") {
                    $gapbar1 = "";
                    $gapbar2 = "";
                  } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) == "") {
                    $gapbar1 = "";
                    $gapbar2 ="";
                  } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) != "") {
                    $gapbar1 = "";
                    $gapbar2 = "";
                  }
                  ?>
                  <span><?= $mem_info["mm_nation"] ?></span>
                  <span class="gapbar1" style="<?= $gapbar1 ?>"></span>
                  <span><?= getKoreanAge($mem_info["mm_birth"]) ?>대</span>
                  <span class="gapbar2" style="<?= $gapbar2 ?>"></span>
                  <span class="area-addr" data-addr="<?= htmlspecialchars($mem_info["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($mem_info["ad_addr"], ENT_QUOTES, 'UTF-8') ?></span>
                </p>
              <?php } ?>

              <div class="btn-group" data-request-state="<?= isset($already_requested) && $already_requested ? 'sent' : 'none' ?>">
                <?php if ($isOwnResume && !$isPreview) { ?>
                  <!-- 개인회원이 자신의 이력서를 볼 때: 수정 및 광고신청 버튼 -->
                  <button type="button" id="btnEditResume" class="btn-request btn-primary">수정</button>
                  <button type="button" id="btnPromotionResume" class="btn-request btn-primary">광고신청</button>
                <?php } else if (!$isPreview) { ?>
                  <!-- 기업회원이 볼 때: 제안보내기 버튼 -->
                  <?php if ($this->user->getUserType() == "MEM") { ?>
                    <button type="button" aria-haspopup="dialog" data-ru_idx="<?= $info["ru_idx"] ?>" id="btnPropose" class="btn-request btn-primary" style="background-color:#ccc;" disabled>제안하기</button>
                  <?php } else { ?>
                    <button type="button" aria-haspopup="dialog" data-ru_idx="<?= $info["ru_idx"] ?>" id="btnPropose" class="btn-request btn-primary">제안하기</button>
                    <!-- 기업과 인재 사이에 지원/제안 이력이 있을 때: 채팅하기 버튼 -->
                    <button type="button" id="btnGoChat" class="btnGoChat btn-request btn-primary"
                        data-rm_idx="<?= isset($chat_rm_idx) ? htmlspecialchars($chat_rm_idx, ENT_QUOTES, 'UTF-8') : '' ?>"
                        data-rc_idx="<?= isset($chat_rc_idx) ? htmlspecialchars($chat_rc_idx, ENT_QUOTES, 'UTF-8') : '' ?>"
                        data-tab="<?= isset($chat_tab) ? htmlspecialchars($chat_tab, ENT_QUOTES, 'UTF-8') : '2' ?>">채팅하기</button>
                  <?php } ?>
                <!-- 전화하기 버튼: 기업회원만 사용 가능 (일반회원/비로그인은 클릭 시 안내 팝업) -->
                <button type="button" id="btnCallResume" class="btn-request btn-primary" data-state="idle">
                  <span class="call-text">전화하기</span>
                </button>
                <?php } ?>

                <?php if ($isPreview) { ?>
                  <div class="preview-action-bar">
                    <a href="/mypage/resume_write/<?= $info['ru_idx'] ?>?from=preview" class="btn-primary btn-edit-icon">수정하기</a>
                    <button type="button" class="btn-primary btn-register" onclick="submitPreviewComplete()">등록하기</button>
                  </div>
                <?php } ?>
              </div>
            </div>

            <!-- 히스토리 목록 -->
            <?php if (!$isOwnResume) : ?>
              <div class="recent-history">
                <div class="history-header">
                  <p class="title">내가 본 인재</p>
                </div>
                <div class="history-body">
                  <div class="recently-slider keen-slider">
                    <!-- 목록 -->
                    <?php foreach ($view_list as $row) : ?>
                      <div class="keen-slider__slide">
                        <div class="content-li" data-ru_idx="<?= $row["ru_idx"] ?>">
                          <div class="content-info">
                            <div class="content-body">
                              <div class="desc">
                                <div class="wicon"><?= $row["ru_work_img"] ?></div>
                                <div class="title ellipsis"><?= $row["ru_name"] ?></div>
                                <div class="summary">
                                  <ul class="working-day"><?= $row["ru_week"] ?></ul>
                                </div>
                                <div class="summary">
                                  <p><?= $row["mm_nation"] ?></p>
                                  <p><?= $row["mm_age"] > 0 ? $row["mm_age"] . '대' : '무관' ?></p>
                                  <p class="area-addr" data-addr="<?= htmlspecialchars($row["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= $row["ad_addr"] ?></p>
                                </div>
                                <div class="summary2">
                                  <p class="period"><?= getPriceType($row["ru_price_type"]) ?></p>
                                </div>
                                <div class="summary3">
                                  <p class="wtype"><?= getWorkTypeWithColor($row["ru_work_type"]) ?></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                    <!-- 목록 -->
                  </div>
                </div>
                <script>
                  window.addEventListener('DOMContentLoaded', function() {
                    if (typeof initVerticalSlider === 'function') {
                      initVerticalSlider('.recently-slider', false);  // false: 화살표 없음
                    }
                  });
                </script>
              </div>
            <?php endif; ?>
            <!-- //히스토리 목록 -->

          </div>

        </div>
        <!-- //aside -->

      </div>
    </div>

    <?php if (!$isOwnResume) : ?>
      <div class="view-footer">
        <div class="container">

          <!-- 하단 관련 인재 -->
          <div class="recomm-slider person">
            <h2 class="sr-text">추천인재</h2>
            <div class="slider-wrap keen-slider" id="my-keen-slider">
            </div>
          </div>
          <!-- //하단 관련 인재 -->

        </div>
      </div>
    <?php endif; ?>

  </main><!-- //page-content -->
</div><!-- //page -->

<!-- 추천 리스트 템플릿 -->
<?php
$reco_show_bookmark = false;
$reco_show_mm_idx   = false;
include APPPATH . 'views/web/_layout/reco_list_template.php';
?>

<!-- 제안 리스트 -->
<div id="selectPanel" class="layer-panel card-type" tabindex="-1">
  <div class="panel-container" style="max-width:500px;margin:0 auto;">
    <div class="panel-header">
      <!-- 약관제목 -->
      <h2 class="panel-title">진행중인 채용공고</h2>
      <button type="button" aria-controls="selectPanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
    </div>
    <div class="panel-body">
      <div class="panel-content">
        <div class="terms-wrap">
          <div class="post-list">
            <ul class="list">
              <?php
              // 변수가 존재하고 배열인지 확인
              $hasRecruits = isset($recruit_list) && is_array($recruit_list) && count($recruit_list) > 0;
              $validRecruitCount = 0; // 실제로 출력된 유효한 채용공고 개수

              if ($hasRecruits) {
                // 유효한 채용공고 개수 먼저 계산
                foreach ($recruit_list as $row) {
                  if (!empty($row["rc_idx"]) && $row["rc_idx"] != "0" && $row["rc_idx"] != 0) {
                    $validRecruitCount++;
                  }
                }
              }
              ?>
              <?php if ($hasRecruits && $validRecruitCount > 0) : ?>
                <?php foreach ($recruit_list as $row) : ?>
                  <?php
                  // rc_idx가 유효한지 확인 (null, 빈 값, 0 체크)
                  if (empty($row["rc_idx"]) || $row["rc_idx"] == "0" || $row["rc_idx"] == 0) {
                    continue; // 유효하지 않은 항목은 건너뛰기
                  }
                  ?>
                  <li>
                    <div class="selectable-item" style="position:relative;">
                      <label class="form-checkbox" style="position:absolute;top:40px;">
                        <input type="radio" name="chk_request[]" value="<?= $row["rc_idx"] ?>" data-rc_name="<?= $row["rc_name"] ?>">
                        <span class="label"></span>
                        <span class="sr-text">선택</span>
                      </label>
                      <p style="padding:20px;padding-left:30px;">
                        <strong><?= $row["rc_name"] ?></strong><br />
                        <span class="date"><?= get_yyyymmdd($row["rc_sdate"]) ?> ~ <?= get_yyyymmdd($row["rc_edate"]) ?></span>
                      </p>
                    </div>
                  </li>
                <?php endforeach; ?>
              <?php else : ?>
                <div class="is-empty-v2">
                  <p>등록된 채용공고가 없습니다.</p>
                </div>
              <?php endif; ?>
            </ul>
          </div>

          <!-- //목록 -->
        </div>
      </div>
    </div>
    <?php if ($validRecruitCount > 0) : ?>
      <div class="panel-footer">
        <div class="sticky-bottom" style="width:100%;">
          <button type="button" style="width:100%;" aria-controls="terms2Panel" class="btnProcess btn btn-primary js-toggle-panel">보내기</button>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
<!-- //제안 리스트 -->

<script>
  var check_user_type = "<?= $this->user->getUserType(); ?>";

  // 이벤트 위임 방식으로 변경하여 팝업 닫은 후에도 버튼 클릭이 정상 동작하도록 수정
  // 네임스페이스를 사용하여 중복 등록 방지
  $(document).off('click', '#btnPropose').on('click', '#btnPropose', function(e) {
    var $btn = $(this);

    // PC 인재정보 제안보내기 분석 (운영 환경에서만)
    var isProdEnvironment = !window.location.pathname.includes('/dev_nowapk');
    if (isProdEnvironment && typeof my_analytics !== 'undefined') {
      my_analytics.goal('pc_resume');
    }

    // 버튼이 disabled 상태인지 확인
    if ($btn.prop('disabled') || $btn.hasClass('disabled')) {
      e.preventDefault();
      e.stopPropagation();
      return false;
    }

    // 이벤트 전파 중단 (다른 핸들러와의 충돌 방지)
    e.stopImmediatePropagation();

    //로그인 여부 확인
    if ($("#is_login").val() != "Y") {
      //commonLogin();

      openPopup('confirm', {
        id: 'loginconfirm',
        content: '<strong>로그인후 이용해주세요!</strong><small></small>',
        firstButton: '로그인',
        secondButton: '회원가입',
        firstCallback: function() {
          location.href = "/member/login";
        },
        secondCallback: function() {
          setCookie('non_mem_last_resume', window.location.href, 1);
          location.href = "/member/join_start";
        },
      });

      return false;
    }

    if (check_user_type != "COMP") {
      commonAlert("일반 회원은 제안 할 수 없습니다.", function() {});
      return false;
    }

    $("[name='chk_request[]']").prop("checked", false);
    // [설명] '제안 보내기' 버튼 클릭 시 제안 선택 패널을 화면에 표시
    // main.js의 openPanel 함수 사용
    if (typeof openPanel === 'function') {
      var selectPanel = document.getElementById('selectPanel');
      if (selectPanel) {
        openPanel(selectPanel);
      } else {
        $("#selectPanel").addClass("on");
      }
    } else {
      $("#selectPanel").addClass("on");
    }

    return false;
  });

  // 기업회원이 구직자에게 전화(050 안심번호) 연결: 로그인/기업회원 여부 확인 후 가상번호 요청
  $(document).off('click', '#btnCallResume').on('click', '#btnCallResume', function(e) {
    e.preventDefault();
    var $btn = $(this);

    // 이미 안심번호를 받아온 상태거나 요청 중이면 재요청하지 않음
    if ($btn.attr('data-state') === 'revealed' || $btn.attr('data-state') === 'loading') {
      return false;
    }

    // 로그인 여부 확인 (btnPropose와 동일한 패턴)
    if ($("#is_login").val() != "Y") {
      openPopup('confirm', {
        id: 'loginconfirm',
        content: '<strong>로그인후 이용해주세요!</strong><small></small>',
        firstButton: '로그인',
        secondButton: '회원가입',
        firstCallback: function() {
          location.href = "/member/login";
        },
        secondCallback: function() {
          setCookie('non_mem_last_resume', window.location.href, 1);
          location.href = "/member/join_start";
        },
      });
      return false;
    }

    // 기업회원 전용 기능
    if (check_user_type != "COMP") {
      commonAlert("기업회원만 이용가능합니다.", function() {});
      return false;
    }

    var ruIdx = $("[name=ru_idx]").val();
    $btn.attr('data-state', 'loading');

    comAjax("/resume_proc/get_call_number", { ru_idx: ruIdx }, function(ret) {
      if (ret.code == "00" && ret.data && ret.data.vn) {
        $btn.attr('data-state', 'revealed');
        $btn.find('.call-text').text("안심번호: " + ret.data.vn);
      } else {
        $btn.attr('data-state', 'idle');
        commonAlert(ret.msg || "전화 연결에 실패했습니다.", function() {});
      }
    });

    return false;
  });

  // 닫기 버튼 클릭 이벤트 처리
  // 이벤트 위임 방식으로 처리하여 확실하게 작동하도록 함
  $(document).on('click', '.btn-close-panel[aria-controls="selectPanel"]', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    // 패널 닫기
    var selectPanel = document.getElementById('selectPanel');
    if (selectPanel) {
      // main.js의 closePanel 함수 사용
      if (typeof closePanel === 'function') {
        closePanel(selectPanel);
      } else {
        // closePanel 함수가 없으면 직접 처리
        $("#selectPanel").removeClass("on");
        selectPanel.hidden = true;
        document.body.style.overflow = '';
      }
    }

    return false;
  });

  $(".btnProcess").click(function() {
    if ($("[name='chk_request[]']:checked").length == 0) {
      commonAlert("제안하실 공고를 선택해주세요.", function() {});
      return;
    }


    //오늘 제안여부 확인
    var rc_idx = $("[name='chk_request[]']:checked").eq(0).val();
    var paramsToday = {};
    paramsToday["ru_idx"] = $("[name=ru_idx]").val();
    paramsToday["rc_idx"] = rc_idx;
    comAjax("/resume_proc/todayapply", paramsToday, function(ret) {

      if (ret.code == "00") {
        //제안가능
        var mq_idxs = new Array();
        mq_idxs.push($("[name='ru_idx']").val());

        var params = {};
        params["mq_idxs"] = mq_idxs;
        params["rc_idx"] = rc_idx;
        params["mq_type"] = "RESUME";

        comAjax("/common_proc/member_request_check", params, function(ret_1) {
          if (ret_1.code == "00") {
            var add_text = (ret_1.data.point_yn == "Y" ? "<br/>" + ret_1.data.point_price + "P 포인트가 차감됩니다." : "");
            openPopup('confirm', {
              content: '<em>해당공고</em><br> 로 제안을 보내시겠습니까?' + add_text,
              secondCallback: function() {
                comAjax("/common_proc/member_request", params, function(ret) {
                  if (ret.code == "00") {
                    // 알림창 주석처리 - 바로 채팅방으로 이동
                    // commonAlert("선택한 채용공고로 제안하였습니다.", function() {

                    // '제안보내기' 패널을 닫는다.
                    $("#selectPanel").removeClass("on");

                    //채팅창 오픈
                    $("body").addClass('has-popup');
                    $(".js-open-chat").addClass("is-trigger");
                    $("#chatPopup").addClass("in");
                    $(".btn-close-chat").addClass("btn-close-chat");
                    $(".js-close-chat").addClass("show");

                    // 사용자 타입과 제안/지원 여부에 따라 올바른 탭 활성화
                    // hr_post.php는 기업회원이 인재에게 제안하는 페이지이므로 탭2 (제안한 인재)
                    var userType = "<?= $this->user->getUserType() ?>";
                    var targetTab = "2"; // 제안을 보낸 경우: 탭2 (제안한 인재/제안받은 공고)
                    setShow(targetTab);

                    //해당공고 클릭 처리 - 채팅방 목록 로드

                    // 채팅방 목록을 직접 로드
                    var chatParams = {};
                    chatParams["ru_idx"] = rc_idx;
                    chatParams["chatgbn"] = 'chatPanel2'; // 제안한 인재 탭

                    // chatting_cont.php의 getChatTargetList 함수를 사용하여 채팅방 목록 로드
                    // 기업회원 제안보낸 인재 탭: get_chat_target_list_recruit2
                    if (typeof getChatTargetList === 'function') {
                      // getChatTargetList 함수 호출 (탭2: 제안한 인재)
                      getChatTargetList("2");

                      // 채팅방 목록 로드 후 채팅방 찾아서 자동으로 열기
                      // 채팅방 목록이 DOM에 추가될 때까지 반복 확인
                      var checkCount = 0;
                      var maxCheckCount = 30; // 최대 15초 대기 (0.5초 * 30)
                      var recruitClicked = false; // 공고 클릭 플래그
                      // 현재 인재의 ru_idx 가져오기 (정확한 채팅방 찾기 위해 필요)
                      var currentRuIdx = $("[name=ru_idx]").val();
                      var checkInterval = setInterval(function() {
                        try {
                          checkCount++;

                          // 제안한 인재 탭에서 해당 rc_idx와 ru_idx 모두 일치하는 채팅방 찾기
                          var targetRmIdx = null;

                          // 이미 펼쳐진 인재 목록에서 채팅방 찾기 (우선 확인)
                          $("#chat_list2 .room-item").each(function() {
                            var $item = $(this);
                            var itemRcIdx = $item.find('.angle-link-resume').data('rc_idx') ||
                              $item.find('a[data-rc_idx]').data('rc_idx') ||
                              $item.data('rc_idx');
                            var itemRuIdx = $item.find('.angle-link-resume').data('ru_idx') ||
                              $item.find('a[data-ru_idx]').data('ru_idx') ||
                              $item.data('ru_idx');
                            // rc_idx와 ru_idx 모두 일치해야 정확한 채팅방을 찾을 수 있음
                            if (itemRcIdx == rc_idx && itemRuIdx == currentRuIdx) {
                              // targetRmIdx는 해당 채팅방(room-item)의 고유 식별자(target_id)를 의미함. 
                              // 즉, 열어야 할 채팅방의 rm_idx(채팅방 인덱스) 값이며
                              // 이후 openChatRoom(targetRmIdx) 호출 시 바로 이 값을 사용.
                              targetRmIdx = $item.data('target_id');
                              return false; // break
                            }
                          });

                          // 채팅방을 찾았으면 즉시 열기
                          if (targetRmIdx) {
                            clearInterval(checkInterval);
                            openChatRoom(targetRmIdx);
                            return;
                          }

                          // 채팅방을 찾지 못했고, 공고를 아직 클릭하지 않은 경우
                          if (!recruitClicked) {
                            // 기업회원의 경우: 공고 목록에서 해당 rc_idx 찾기
                            var $targetRecruitItem = null;
                            $("#chat_list2 .list-item").each(function() {
                              var $item = $(this);
                              var $link = $item.find('a[onclick*="fShowChatListByRecruite"]');
                              if ($link.length > 0) {
                                var onclickAttr = $link.attr('onclick') || '';
                                var match = onclickAttr.match(/fShowChatListByRecruite\('(\d+)'/);
                                if (match && match[1] == rc_idx) {
                                  $targetRecruitItem = $link;
                                  return false; // break
                                }
                              }
                            });

                            // 공고를 찾았으면 한 번만 클릭하여 인재 목록 펼치기
                            if ($targetRecruitItem && $targetRecruitItem.length > 0) {
                              recruitClicked = true; // 플래그 설정
                              $targetRecruitItem.trigger('click');
                              // 공고 클릭 후 다음 체크에서 인재 목록 확인
                              return;
                            }
                          }

                          // 최대 시도 횟수에 도달한 경우
                          if (checkCount >= maxCheckCount) {
                            clearInterval(checkInterval);
                            //console.error("채팅방을 찾을 수 없음 - rc_idx:", rc_idx, "ru_idx:", currentRuIdx);
                            // ru_idx와 rc_idx 모두 일치하는 채팅방 찾기 (마지막 시도)
                            var $matchedChatItem = null;
                            $("#chat_list2 .room-item").each(function() {
                              var $item = $(this);
                              var itemRcIdx = $item.find('.angle-link-resume').data('rc_idx') ||
                                $item.find('a[data-rc_idx]').data('rc_idx') ||
                                $item.data('rc_idx');
                              var itemRuIdx = $item.find('.angle-link-resume').data('ru_idx') ||
                                $item.find('a[data-ru_idx]').data('ru_idx') ||
                                $item.data('ru_idx');
                              if (itemRcIdx == rc_idx && itemRuIdx == currentRuIdx) {
                                $matchedChatItem = $item;
                                return false; // break
                              }
                            });

                            if ($matchedChatItem && $matchedChatItem.length > 0) {
                              var matchedRmIdx = $matchedChatItem.data('target_id');
                              if (matchedRmIdx) {
                                openChatRoom(matchedRmIdx);
                              }
                            }
                            // 매칭되는 채팅방을 찾지 못한 경우: 아무 동작도 하지 않음
                            // 사용자가 채팅 목록에서 수동으로 선택할 수 있도록 함
                          }
                        } catch (error) {
                          //console.error("채팅방 찾기 오류:", error);
                          clearInterval(checkInterval);
                        }
                      }, 500); // 0.5초마다 확인

                      // 채팅방 열기 함수
                      function openChatRoom(targetRmIdx) {
                        if (!targetRmIdx || typeof socket === 'undefined' || !socket) {
                          //console.error("채팅방 열기 실패 - targetRmIdx:", targetRmIdx, "socket:", typeof socket);
                          return;
                        }

                        // 첫 메시지 전송 함수를 먼저 정의 (이벤트 리스너에서 사용하기 위해)
                        if (!window.sendFirstMessageAfterProposal) {
                          window.sendFirstMessageAfterProposal = function(targetUserId, targetRmIdx) {
                            if (!targetUserId || !targetRmIdx || typeof socket === 'undefined' || !socket) {
                              //console.error("첫 메시지 전송 실패 - targetUserId:", targetUserId, "targetRmIdx:", targetRmIdx, "socket:", typeof socket);
                              return;
                            }

                            // g_user_id 가져오기
                            var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();

                            // getCurrentDate 함수 확인
                            var currentDate = typeof getCurrentDate === 'function' ? getCurrentDate() : new Date().toISOString();

                            // 사용자 타입에 따른 메시지 내용 결정
                            var current_user_type = typeof check_user_type !== 'undefined' ? check_user_type : "";
                            var messageText = "";
                            if (current_user_type === "COMP") {
                              // 기업이 개인에게 제안보낼 경우
                              messageText = "안녕하세요.\n채용중인 일자리를 제안드립니다.";
                            } else {
                              // 개인이 기업에게 지원할 경우
                              messageText = "안녕하세요.\n채용공고에 이력서를 지원합니다.";
                            }

                            var firstMessage = {
                              rm_idx: Number(targetRmIdx),
                              user_id: g_user_id,
                              chat_type: "recruit",
                              mg_type: "text",
                              mg_text: messageText,
                              mg_req_dt: "",
                              mg_img: "",
                              mg_file: "",
                              mg_imoti: "",
                              mg_dt: currentDate,
                              mg_reads: new Array(),
                            };

                            // 읽음 상태 설정
                            firstMessage.mg_reads.push({
                              read_yn: "Y",
                              user_id: g_user_id
                            });
                            firstMessage.mg_reads.push({
                              read_yn: "N",
                              user_id: targetUserId
                            });

                            // 사용자 목록
                            var firstUsers = [];
                            firstUsers.push({
                              user_id: g_user_id,
                              user_nick: ""
                            });
                            firstUsers.push({
                              user_id: targetUserId,
                              user_nick: ""
                            });

                            // 메시지 전송
                            socket.emit("message_insert", firstMessage, firstUsers);
                          };
                        }

                        // chatting_cont.php의 .room-item 클릭 핸들러와 동일한 로직 실행
                        // 대화내용 중복 차단 내역 초기화
                        if (typeof resetProcessedIdx === 'function') {
                          resetProcessedIdx();
                        }

                        // 초기화
                        if (typeof click_type !== 'undefined') {
                          click_type = "list";
                        }
                        if (typeof rm_start !== 'undefined') {
                          rm_start = 0;
                        }
                        $("#chat_view").html("");
                        if (typeof rm_idx !== 'undefined') {
                          rm_idx = targetRmIdx;
                        }

                        // get_chat_info 중복 호출 방지 플래그 초기화
                        if (typeof window.chat_info_called !== 'undefined') {
                          window.chat_info_called = false;
                        }

                        // 채팅방 정보 준비 상태 초기화
                        if (typeof rm_info_ready !== 'undefined') {
                          rm_info_ready = false;
                        }
                        if (typeof rm_info !== 'undefined') {
                          rm_info = {};
                        }

                        $("#btnSend").prop("disabled", true); // 버튼 비활성화

                        // g_user_id 가져오기
                        var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();

                        // 제안 완료 후 자동 메시지 전송 플래그 설정
                        window.autoSendFirstMessage = {
                          enabled: true,
                          rm_idx: targetRmIdx,
                          rc_idx: rc_idx,
                          messageListChecked: false, // message_list_return에서 메시지가 없다고 확인했는지 여부
                          targetUserId: null // room_info_return에서 찾은 상대방 ID
                        };

                        // 리스너를 emit보다 먼저 등록해 응답 레이스로 이벤트를 놓치지 않도록 보강
                        var earlyBindSocketOnce = function(eventName, handler) {
                          if (!socket || typeof handler !== 'function') return;
                          if (typeof socket.once === 'function') {
                            socket.once(eventName, handler);
                            return;
                          }
                          if (typeof socket.on === 'function') {
                            var wrapped = function(ret) {
                              if (typeof socket.off === 'function') {
                                socket.off(eventName, wrapped);
                              }
                              handler(ret);
                            };
                            socket.on(eventName, wrapped);
                          }
                        };

                        earlyBindSocketOnce("room_info_return", function(ret) {
                          if (!ret || ret.code !== "00" || !window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) return;
                          if (!ret.data || Number(ret.data.rm_idx) !== Number(window.autoSendFirstMessage.rm_idx)) return;

                          // chatting_cont.php의 전송 조건(rm_info_ready/rm_info)을 만족시키도록 동기화
                          if (typeof rm_info !== 'undefined') {
                            rm_info = ret.data;
                          }
                          if (typeof rm_info_ready !== 'undefined') {
                            rm_info_ready = true;
                          }

                          var targetUserId = null;
                          if (ret.data.rm_users && Array.isArray(ret.data.rm_users)) {
                            for (var i = 0; i < ret.data.rm_users.length; i++) {
                              if (ret.data.rm_users[i].user_id && ret.data.rm_users[i].user_id != g_user_id) {
                                targetUserId = ret.data.rm_users[i].user_id;
                                break;
                              }
                            }
                          }

                          if (targetUserId) {
                            window.autoSendFirstMessage.targetUserId = targetUserId;
                            if (typeof g_chat_other_id !== 'undefined' && !g_chat_other_id) {
                              g_chat_other_id = targetUserId;
                            }
                            if (window.autoSendFirstMessage.messageListChecked && window.sendFirstMessageAfterProposal) {
                              window.sendFirstMessageAfterProposal(targetUserId, window.autoSendFirstMessage.rm_idx);
                              window.autoSendFirstMessage = null;
                            }
                          }
                        });

                        earlyBindSocketOnce("message_list_return", function(ret) {
                          if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) return;
                          var messageList = ret && ret.data ? ret.data : [];
                          var hasMessages = false;
                          if (Array.isArray(messageList) && messageList.length > 0) {
                            for (var i = 0; i < messageList.length; i++) {
                              if (messageList[i].rm_messages && Array.isArray(messageList[i].rm_messages) && messageList[i].rm_messages.length > 0) {
                                hasMessages = true;
                                break;
                              }
                            }
                          }
                          if (!hasMessages) {
                            window.autoSendFirstMessage.messageListChecked = true;
                            if (window.autoSendFirstMessage.targetUserId && window.sendFirstMessageAfterProposal) {
                              window.sendFirstMessageAfterProposal(window.autoSendFirstMessage.targetUserId, window.autoSendFirstMessage.rm_idx);
                              window.autoSendFirstMessage = null;
                            }
                          }
                        });

                        // 룸 호출
                        socket.emit("room_join", {
                          rm_idx: Number(targetRmIdx),
                          con_user_id: g_user_id
                        });

                        // 채팅방 정보 조회
                        socket.emit("room_info", {
                          rm_idx: Number(targetRmIdx),
                          con_user_id: g_user_id
                        });

                        // 메시지 리스트 조회
                        var params = {
                          rm_idx: Number(targetRmIdx),
                          skip: 0,
                          limit: 1000
                        };
                        socket.emit("message_list", params);

                        // 채팅 뷰 표시
                        $('.chat-view').css('display', 'flex');
                        $('.chat-view').removeAttr('hidden');


                        // get_chat_info를 직접 호출하여 상대방 ID 가져오기
                        if (typeof comAjaxV2 === 'function') {
                          // ajax_first_num 초기화하여 중복 호출 알림 방지
                          if (typeof ajax_first_num !== 'undefined') {
                            ajax_first_num = 0;
                          }

                          comAjaxV2("/chat_proc/get_chat_info", {
                            rm_idx: targetRmIdx,
                            mm_type: userType,
                            chat_tab_id: "2"
                          }, function(ret) {
                            if (ret.code == "00" && ret.data && ret.data.mm_id) {
                              var targetUserId = ret.data.mm_id;

                              // 플래그에 상대방 ID 저장
                              if (window.autoSendFirstMessage && window.autoSendFirstMessage.enabled) {
                                window.autoSendFirstMessage.targetUserId = targetUserId;
                                // get_chat_info에서 가져온 상대방 ID를 data 속성에도 저장
                                $("#top_name").data('chatotherid', targetUserId);

                                // message_list_return에서 메시지가 없다고 확인했으면 바로 전송
                                if (window.autoSendFirstMessage.messageListChecked && window.sendFirstMessageAfterProposal) {
                                  window.sendFirstMessageAfterProposal(targetUserId, targetRmIdx);
                                  // 플래그 즉시 해제하여 중복 전송 방지
                                  window.autoSendFirstMessage = null;
                                  return; // 전송 완료 후 즉시 리턴
                                }
                              }
                            } else {}
                          });
                        }

                        function bindSocketOnce(eventName, handler) {
                          if (!socket || typeof handler !== 'function') return;
                          if (typeof socket.once === 'function') {
                            socket.once(eventName, handler);
                            return;
                          }
                          if (typeof socket.on === 'function') {
                            var wrapped = function(ret) {
                              if (typeof socket.off === 'function') {
                                socket.off(eventName, wrapped);
                              }
                              handler(ret);
                            };
                            socket.on(eventName, wrapped);
                          }
                        }

                        // room_info_return 이벤트를 한 번만 리스닝하여 상대방 ID 저장 및 메시지 전송 시도
                        bindSocketOnce("room_info_return", function(ret) {
                          if (ret.code == "00" && window.autoSendFirstMessage && window.autoSendFirstMessage.enabled) {
                            var autoSendInfo = window.autoSendFirstMessage;

                            // 현재 채팅방인지 확인
                            if (autoSendInfo.rm_idx == ret.data.rm_idx || autoSendInfo.rm_idx == rm_idx) {

                              // 상대방 ID 찾기
                              var targetUserId = null;
                              var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();

                              if (ret.data && ret.data.rm_users && Array.isArray(ret.data.rm_users)) {
                                for (var i = 0; i < ret.data.rm_users.length; i++) {
                                  if (ret.data.rm_users[i].user_id && ret.data.rm_users[i].user_id != g_user_id) {
                                    targetUserId = ret.data.rm_users[i].user_id;
                                    break;
                                  }
                                }
                              } else {}

                              // 상대방 ID를 찾지 못한 경우 get_chat_info에서 가져오기
                              if (!targetUserId) {
                                // get_chat_info가 호출될 때까지 대기
                                setTimeout(function() {
                                  // 플래그가 이미 해제되었는지 확인 (get_chat_info에서 이미 전송했을 수 있음)
                                  if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) {
                                    return;
                                  }

                                  var chatOtherId = $("#top_name").data('chatotherid');
                                  if (chatOtherId) {
                                    targetUserId = chatOtherId;

                                    // 플래그가 여전히 존재하는지 확인
                                    if (window.autoSendFirstMessage) {
                                      window.autoSendFirstMessage.targetUserId = targetUserId;

                                      // message_list_return에서 메시지가 없다고 확인했으면 바로 전송
                                      if (window.autoSendFirstMessage.messageListChecked && window.sendFirstMessageAfterProposal) {
                                        window.sendFirstMessageAfterProposal(targetUserId, autoSendInfo.rm_idx);
                                        // 플래그 즉시 해제하여 중복 전송 방지
                                        window.autoSendFirstMessage = null;
                                      }
                                    }
                                  } else {}
                                }, 1500);
                              } else {
                                // 상대방 ID를 플래그에 저장
                                window.autoSendFirstMessage.targetUserId = targetUserId;

                                // message_list_return에서 메시지가 없다고 확인했으면 바로 전송
                                // (message_list_return이 먼저 발생했을 수 있음)
                                if (window.autoSendFirstMessage.messageListChecked && window.sendFirstMessageAfterProposal) {
                                  window.sendFirstMessageAfterProposal(targetUserId, autoSendInfo.rm_idx);
                                  // 플래그 해제하여 중복 전송 방지
                                  window.autoSendFirstMessage = null;
                                }
                              }
                            }
                          }
                        });

                        // message_list_return 이벤트를 한 번만 리스닝하여 메시지 개수 확인
                        bindSocketOnce("message_list_return", function(ret) {
                          // 이미 전송되었으면 스킵 (room_info_return에서 전송했을 수 있음)
                          if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) {
                            return;
                          }

                          var autoSendInfo = window.autoSendFirstMessage;

                          // 메시지 리스트 확인
                          var messageList = ret.data || [];
                          var hasMessages = false;

                          if (Array.isArray(messageList) && messageList.length > 0) {
                            for (var i = 0; i < messageList.length; i++) {
                              if (messageList[i].rm_messages && Array.isArray(messageList[i].rm_messages) && messageList[i].rm_messages.length > 0) {
                                hasMessages = true;
                                break;
                              }
                            }
                          }


                          // 메시지가 없으면 자동으로 첫 메시지 전송
                          if (!hasMessages) {

                            // message_list_return에서 메시지가 없다고 확인했음을 플래그에 저장
                            window.autoSendFirstMessage.messageListChecked = true;

                            // 상대방 ID 가져오기 (여러 소스에서 시도)
                            var targetUserId = null;
                            var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();

                            // 1. 플래그에 저장된 상대방 ID 사용 (get_chat_info 또는 room_info_return에서 저장됨)
                            if (autoSendInfo.targetUserId) {
                              targetUserId = autoSendInfo.targetUserId;
                            }

                            // 2. rm_info에서 상대방 ID 찾기
                            if (!targetUserId && typeof rm_info !== 'undefined' && rm_info && rm_info.rm_users && Array.isArray(rm_info.rm_users)) {
                              for (var i = 0; i < rm_info.rm_users.length; i++) {
                                if (rm_info.rm_users[i].user_id != g_user_id) {
                                  targetUserId = rm_info.rm_users[i].user_id;
                                  break;
                                }
                              }
                            }

                            // 3. get_chat_info에서 가져온 값 사용 (더 긴 대기 시간)
                            if (!targetUserId) {
                              setTimeout(function() {
                                // 플래그가 이미 해제되었는지 확인 (다른 곳에서 이미 전송했을 수 있음)
                                if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) {
                                  return;
                                }

                                var chatOtherId = $("#top_name").data('chatotherid');
                                if (chatOtherId && window.sendFirstMessageAfterProposal) {
                                  window.sendFirstMessageAfterProposal(chatOtherId, window.autoSendFirstMessage.rm_idx);
                                  // 플래그 즉시 해제하여 중복 전송 방지
                                  window.autoSendFirstMessage = null;
                                } else {
                                  // 4. g_chat_other_id 사용
                                  var gChatOtherId = (typeof g_chat_other_id !== 'undefined' && g_chat_other_id) ? g_chat_other_id : null;
                                  if (gChatOtherId && window.sendFirstMessageAfterProposal) {
                                    window.sendFirstMessageAfterProposal(gChatOtherId, window.autoSendFirstMessage.rm_idx);
                                    // 플래그 즉시 해제하여 중복 전송 방지
                                    window.autoSendFirstMessage = null;
                                  } else {
                                    //console.error("get_chat_info에서도 상대방 ID를 찾을 수 없음");
                                    // 플래그 해제
                                    window.autoSendFirstMessage = null;
                                  }
                                }
                              }, 2000); // get_chat_info 완료 대기
                              return; // setTimeout에서 처리하므로 여기서는 리턴
                            }

                            // 4. g_chat_other_id 사용
                            if (!targetUserId && typeof g_chat_other_id !== 'undefined' && g_chat_other_id) {
                              targetUserId = g_chat_other_id;
                            }

                            // 상대방 ID를 찾았으면 메시지 전송
                            // 플래그가 여전히 존재하는지 확인 (다른 곳에서 이미 전송했을 수 있음)
                            if (targetUserId && window.sendFirstMessageAfterProposal && window.autoSendFirstMessage && window.autoSendFirstMessage.enabled) {
                              window.sendFirstMessageAfterProposal(targetUserId, autoSendInfo.rm_idx);
                              // 플래그 즉시 해제하여 중복 전송 방지
                              window.autoSendFirstMessage = null;
                            } else if (!targetUserId) {
                              // 상대방 ID를 찾지 못한 경우 재시도
                              setTimeout(function() {
                                // 플래그가 이미 해제되었는지 확인 (다른 곳에서 이미 전송했을 수 있음)
                                if (!window.autoSendFirstMessage || !window.autoSendFirstMessage.enabled) {
                                  return;
                                }

                                // 재시도 시 여러 소스에서 다시 찾기
                                var retryTargetUserId = window.autoSendFirstMessage.targetUserId ||
                                  $("#top_name").data('chatotherid') ||
                                  (typeof g_chat_other_id !== 'undefined' ? g_chat_other_id : null) ||
                                  (typeof rm_info !== 'undefined' && rm_info && rm_info.rm_users ?
                                    (function() {
                                      var g_user_id = typeof g_user_id !== 'undefined' ? g_user_id : $("#user_id").val();
                                      for (var i = 0; i < rm_info.rm_users.length; i++) {
                                        if (rm_info.rm_users[i].user_id != g_user_id) {
                                          return rm_info.rm_users[i].user_id;
                                        }
                                      }
                                      return null;
                                    })() : null);

                                if (retryTargetUserId && window.sendFirstMessageAfterProposal) {
                                  window.sendFirstMessageAfterProposal(retryTargetUserId, window.autoSendFirstMessage.rm_idx);
                                  // 플래그 즉시 해제하여 중복 전송 방지
                                  window.autoSendFirstMessage = null;
                                } else {
                                  //console.error("상대방 ID를 찾을 수 없음 - 재시도 실패");
                                  // 플래그 해제
                                  window.autoSendFirstMessage = null;
                                }
                              }, 3000);
                            }
                          } else {
                            window.autoSendFirstMessage = null;
                          }
                        });
                      }
                    } else {
                      //console.error("getChatTargetList 함수를 찾을 수 없음");
                    }

                    // 3초에서 1초로 단축

                    // }); // 알림창 콜백 주석처리
                  } else {
                    //console.error("제안보내기 오류:", ret);
                    commonAlert(ret.msg, function() {
                      $("#selectPanel").removeClass("on");
                    });
                    return;
                  }
                });
              }
            });
          } else {
            commonAlert(ret_1.msg, function() {});
          }
        });



      } else if (ret.code == "01") {
        commonAlert("<em>5분 후</em> 다시 제안이 가능합니다.", function() {
          // 알림 후 패널 닫기
          $("#selectPanel").removeClass("on");
        });
        return;
      } else if (ret.code == "90") {
        // 지원이 이미 있는 경우
        commonAlert(ret.msg, function() {
          // 알림 후 패널 닫기
          $("#selectPanel").removeClass("on");
        });
        return;
      } else {
        commonAlert(ret.msg, function() {
          // 알림 후 패널 닫기
          $("#selectPanel").removeClass("on");
        });
        return;
      }


    });

  });

  // 라디오 버튼 직접 클릭 시 기본 동작 보장
  $(document).on('click', 'input[type="radio"][name="chk_request[]"]', function(e) {
    // 라디오 버튼의 기본 동작을 보장하기 위해 이벤트 전파 중단하지 않음
    // 기본 동작으로 라디오 버튼이 선택됨
  });

  // label 클릭 시 기본 동작 보장
  $(document).on('click', '.selectable-item label', function(e) {
    // label 클릭 시 라디오 버튼이 자동으로 선택되므로 기본 동작 유지
    e.stopPropagation(); // selectable-item 클릭 이벤트로 전파되지 않도록
  });

  // selectable-item 클릭 시 해당 항목의 radio 버튼 선택
  $(document).on('click', '.selectable-item', function(e) {
    // label, input, 또는 그 자식 요소를 클릭한 경우는 기본 동작 유지
    if ($(e.target).is('label') ||
      $(e.target).is('input[type="radio"]') ||
      $(e.target).is('span') ||
      $(e.target).closest('label').length > 0 ||
      $(e.target).closest('input[type="radio"]').length > 0) {
      // 라디오 버튼이나 label을 클릭한 경우는 기본 동작만 수행
      return;
    }

    // selectable-item의 다른 부분을 클릭한 경우에만 radio 버튼 선택
    e.preventDefault();
    e.stopPropagation();

    // 해당 항목 내의 radio 버튼 찾기
    var $radio = $(this).find('input[type="radio"][name="chk_request[]"]');
    if ($radio.length > 0) {
      // radio 버튼 선택
      $radio.prop('checked', true);
      // radio 버튼의 change 이벤트 트리거
      $radio.trigger('change');
    }
  });

  function getRecoList() {
    getRecoListCommon({
      containerId: 'my-keen-slider',
      limit: 12,  // 화살표로 넘겨볼 수 있도록 한 화면(perView)보다 많이 로드
      excludeRuIdx: '<?= $info["ru_idx"] ?>',
      showMmIdx: false,
      perView: 4  // 카드 폭 확대 (1280/4 = 320px)
    });
  }

  $(document).ready(function() {
    getRecoList();
  });

  // 개인회원이 자신의 이력서를 볼 때: 수정 버튼 클릭 이벤트
  $(document).on('click', '#btnEditResume', function() {
    var ruIdx = $("[name=ru_idx]").val();
    if (ruIdx) {
      location.href = "/mypage/resume_view/" + ruIdx;
    }
  });

  // 개인회원이 자신의 이력서를 볼 때: 광고신청 버튼 클릭 이벤트
  $(document).on('click', '#btnPromotionResume', function() {
    location.href = "/mypage/point_promotion_buy";
  });

  // 기업회원이 이미 제안/지원 이력이 있을 때: 채팅하기 버튼 클릭 이벤트 (PC: 팝업 내 room 자동 진입)
  $(document).on('click', '#btnGoChat', function() {
    var rcIdx = $(this).attr('data-rc_idx');
    var rmIdx = $(this).attr('data-rm_idx');
    var targetTab = $(this).attr('data-tab') || '2';
    var currentRuIdx = $("[name=ru_idx]").val();

    if (!rcIdx || !rmIdx) {
      return; // 필요한 데이터가 없으면 아무 동작 안 함
    }

    // 채팅 팝업 열기
    $("body").addClass('has-popup');
    $(".js-open-chat").addClass("is-trigger");
    $("#chatPopup").addClass("in");
    $(".btn-close-chat").addClass("btn-close-chat");
    $(".js-close-chat").addClass("show");

    // 올바른 탭 활성화
    if (typeof setShow === 'function') {
      setShow(targetTab);
    }

    // 채팅방 목록 로드
    if (typeof getChatTargetList === 'function') {
      getChatTargetList(targetTab);

      // 채팅방 목록 로드 후 해당 room을 찾아 자동으로 클릭
      var checkCount = 0;
      var maxCheckCount = 30; // 최대 15초 대기 (0.5초 * 30)
      var recruitClicked = false; // 공고 클릭 플래그

      var checkInterval = setInterval(function() {
        try {
          checkCount++;
          var targetRmIdx = null;

          // 이미 펼쳐진 인재 목록에서 room 찾기 (rc_idx와 ru_idx 모두 일치)
          var chatListId = targetTab == "1" ? "#chat_list1" : "#chat_list2";
          var chatListSelector = chatListId + " .room-item";
          var foundItems = $(chatListSelector);

          foundItems.each(function() {
            var $item = $(this);
            // .room-item 내부의 <a> 태그에서 data 속성 찾기
            var $link = $item.find('a');
            var itemRcIdx = $link.data('rc_idx') || $item.data('rc_idx');
            var itemRuIdx = $link.data('ru_idx') || $item.data('ru_idx');

            if (itemRcIdx && itemRuIdx && itemRcIdx == rcIdx && itemRuIdx == currentRuIdx) {
              targetRmIdx = $item.data('target_id');
              return false; // break
            }
          });

          // room을 찾았으면 즉시 클릭
          if (targetRmIdx) {
            clearInterval(checkInterval);
            var $roomItem = $("[data-target_id='" + targetRmIdx + "']");
            if ($roomItem.length > 0) {
              $roomItem.trigger('click');
              // 채팅 뷰 표시 (chatting_cont.php의 room-item 클릭 핸들러와 동일하게)
              $('.chat-view').css('display', 'flex');
              $('.chat-view').removeAttr('hidden');
            }
            return;
          }

          // room을 찾지 못했고, 공고를 아직 클릭하지 않은 경우
          if (!recruitClicked) {
            var $targetRecruitItem = null;
            var listItemSelector = targetTab == "1" ? "#chat_list1 .list-item" : "#chat_list2 .list-item";
            $(listItemSelector).each(function() {
              var $item = $(this);
              var $link = $item.find('a[onclick*="fShowChatListByRecruite"]');
              if ($link.length > 0) {
                var onclickAttr = $link.attr('onclick') || '';
                var match = onclickAttr.match(/fShowChatListByRecruite\('(\d+)'/);
                if (match && match[1] == rcIdx) {
                  $targetRecruitItem = $link;
                  return false; // break
                }
              }
            });

            // 공고를 찾았으면 한 번만 클릭하여 인재 목록 펼치기
            if ($targetRecruitItem && $targetRecruitItem.length > 0) {
              recruitClicked = true; // 플래그 설정
              $targetRecruitItem.trigger('click');
              return; // 다음 체크에서 인재 목록 확인
            }
          }

          // 최대 시도 횟수에 도달한 경우
          if (checkCount >= maxCheckCount) {
            clearInterval(checkInterval);
            // 마지막 한 번 더 확인
            var $lastItem = $("[data-target_id='" + rmIdx + "']");
            if ($lastItem.length > 0) {
              $lastItem.trigger('click');
            }
          }
        } catch (error) {
          clearInterval(checkInterval);
        }
      }, 500); // 0.5초마다 확인
    }
  });

  $(document).on('click', '.desc', function() {
    var ruIdx = $(this).closest('.person-card, .content-li').data('ru_idx');
    if (ruIdx) {
      var url = '/resume/hr_post/' + ruIdx;
      window.open(url, '_blank');
    }
  });

  // 사업자 인증 버튼 클릭 이벤트 - 기업정보입력 팝업 기능
  $(document).on('click', '#btnCompCert', function(e) {
    e.preventDefault();

    // requireCompCertWithGuide 함수 사용 (안내 팝업 먼저 표시)
    function tryRequireCompCert() {
      // basic.php에 정의된 함수 사용 (window 객체에 등록되어 있지 않을 수 있으므로 직접 호출)
      if (typeof requireCompCertWithGuide === 'function') {
        requireCompCertWithGuide();
        return true;
      }
      // window 객체에 등록된 경우
      if (typeof window.requireCompCertWithGuide === 'function') {
        window.requireCompCertWithGuide();
        return true;
      }
      return false;
    }

    // 즉시 시도
    if (tryRequireCompCert()) {
      return;
    }

    // 함수가 아직 로드되지 않았을 수 있으므로 잠시 대기 후 재시도
    var retryCount = 0;
    var maxRetries = 10;
    var retryInterval = setInterval(function() {
      retryCount++;
      if (tryRequireCompCert() || retryCount >= maxRetries) {
        clearInterval(retryInterval);
        if (retryCount >= maxRetries) {
          // 최종 실패 시 안내 메시지
          commonAlert("기업정보 입력 기능을 불러올 수 없습니다.<br>페이지를 새로고침 후 다시 시도해주세요.", function() {});
        }
      }
    }, 100);
  });

  // 주소 정리 함수 적용 (add.js의 fRemoveBigAdrJS 사용)
  $(document).ready(function() {
    // 슬라이더의 주소 처리
    $('.area-addr[data-addr]').each(function() {
      var $this = $(this);
      var originalAddr = $this.data('addr');
      if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
        var formattedAddr = fRemoveBigAdrJS(originalAddr);
        $this.text(formattedAddr);
      }
    });

    // 근무지역: 서버가 출력한 .addr-item(data-addr)의 텍스트만 JS로 축약 (모바일 #addr-display와 동일 방식)
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
  });

  window.submitPreviewComplete = function() {
    var ruIdx = $("[name=ru_idx]").val();
    comAjax("/mypage_proc/resume_complete", { ru_idx: ruIdx }, function(ret) {
      if (ret.code == "00") {
        commonAlert("이력서가 등록되었습니다.", function() {
          location.href = "/mypage/resume_list";
        });
      } else {
        commonAlert(ret.msg || "등록 처리 중 오류가 발생했습니다.", function() {});
      }
    });
  };
</script>