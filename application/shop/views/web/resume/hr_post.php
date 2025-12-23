<input type="hidden" name="ru_idx" value="<?= $info["ru_idx"] ?>" />
<?php
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

function getWorkType2025($work_type)
{
  if (!$work_type) {
    return "";
  }

  $items = [];

  if (preg_match_all('/<span>(.*?)<\/span>/', $work_type, $matches)) {
    $items = $matches[1];
  } else {
    $items = preg_split('/\s*,\s*/', $work_type);
  }

  if (empty($items)) {
    return "";
  }

  // 첫 번째 값만 사용 (em 태그 없이 텍스트만 반환)
  $firstItem = trim($items[0]);
  if ($firstItem === '') {
    return "";
  }

  return $firstItem;
}
function getConvertResumePriceType($price_type)
{
  if (!$price_type) {
    return "";
  }

  $allTypes = ['일급', '주급', '월급'];

  $containsAll = true;
  foreach ($allTypes as $type) {
    if (strpos($price_type, $type) === false) {
      $containsAll = false;
      break;
    }
  }

  if ($containsAll) {
    return '급여협의';
  }

  // 첫 번째 값만 반환 (em 태그 없이 텍스트만 반환)
  $types = preg_split('/\s*,\s*/', $price_type);
  if (empty($types)) {
    return "";
  }

  $firstType = trim($types[0]);
  return $firstType;
}
?>
<style>
  .item-view .info-table .working-day>li {
    width: 36px;
    height: 36px;
    font-size: 18px;
    line-height: 36px;
  }

  .arrow--disabled {
    display: none;
  }

  .person-card .img {
    position: relative;
    width: 216px;
    height: 216px;
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

  .person-card .meta p em {
    font-style: normal;
  }

  .person-card .meta p {
    position: relative;
    width: 35%;
    word-break: keep-all;
    text-align: center;
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
    margin: 0 0 0 90px;
    display: flex;
    flex-direction: column;
  }

  .history-body .content-info .desc .ellipsis {
    width: 210px;
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
    font-size: 14px;
    gap: 5px;
    position: relative;
  }

  .history-body .content-info .summary p {
    position: relative;
    padding-right: 5px;
    margin: 0;
    color: #999;
  }

  .history-body .content-info .summary p:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #ACACAC;
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
    margin: 0 0 -4px;
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
    background-color:#1B9D7D;
  }


  .history-body .content-info .summary2 {
    position: absolute;
    right: 2px;
    bottom: 0px;
    font-size: 14px;
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
    gap: 5px;
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

  .history-body .content-info .summary2 .period em:not(:last-child) {
    padding-right: 5px;
  }

  .history-body .content-info .summary2 .period em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777;
  }

  .history-body .content-info .summary3 {
    position: absolute;
    top: 0px;
    right: 2px;
    color: #000;
    width: 56px;
    height: 16px;
    cursor: pointer;
  }

  .history-body .content-info .summary3 .wtype {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;
  }

  .history-body .content-info .summary3 .wtype em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #333;
  }

  .history-body .content-info .summary3 .wtype em:not(:last-child) {
    padding-right: 5px;
  }

  .history-body .content-info .summary3 .wtype em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777777;
  }

  .history-body .content-info .summary3 .wtype em.wtype1 {
    color: #3a70db;
  }

  .history-body .content-info .summary3 .wtype em.wtype2 {
    color: #F86048;
  }

  .history-body .content-info .summary3 .wtype em.wtype3 {
    color: #1B9D7D;
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
              <div class="profile-detail">
                <!-- 이름 -->
                <p class="name"><em class="view-title"><?= getAstrik($mem_info["mm_name"]) ?></em>님</p>
              </div>
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
              <?php if (!$isCorpUser) { ?>
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
              <div class="inline-group">
                <div class="col">
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
                        <td class="label">급여형태</td>
                        <td class="value"><?= getConvertResumePriceType($info["ru_price_type"]) ?></td>
                      </tr>
                      <tr>
                        <td class="label">근무일자</td>
                        <td class="value"><?= (get_date($info["ru_work_dt"])) ?>(<?= get_yoil($info["ru_work_dt"]) ?>)</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col">
                  <h3 class="secondary-title">&nbsp;</h3>
                  <table class="info-table">
                    <caption><span class="sr-text"></span></caption>
                    <colgroup>
                      <col>
                      <col>
                    </colgroup>
                    <tbody>
                      <tr>
                        <td class="label">근무지역</td>
                        <td class="value" id="work-area-addr" data-addrs="<?= htmlspecialchars(json_encode(array_filter(array_column($addr, 'ad_addr'))), ENT_QUOTES, 'UTF-8') ?>">
                          <?php
                          // 기존 PHP 함수 주석처리 - add.js의 fRemoveBigAdrJS 함수 사용
                          /*
                          $addr_cnt = 0;
                          $processed_addrs = []; // 처리된 주소를 저장할 배열
                          foreach ($addr as $key => $ad) :
                            if ($ad["ad_addr"] != "") {
                              $processed_addr = fRemoveBigAdrPHP($ad["ad_addr"]);
                              // 중복 체크: 이미 처리된 주소가 아니면 출력
                              if (!in_array($processed_addr, $processed_addrs)) {
                                if ($addr_cnt > 0) echo ", ";
                                $addr_cnt++;
                                echo $processed_addr;
                                $processed_addrs[] = $processed_addr; // 처리된 주소 배열에 추가
                              }
                            }
                          endforeach;
                          */
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td class="label">근무형태</td>
                        <td class="value"><?= getWorkType2025($info["ru_work_type"]) ?></td>
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
                            무관
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
            </div>
          </div>

          <div class="section view-introduce" style="margin-top: 30px;">
            <h3 class="secondary-title">자기소개</h3>
            <div class="introduce-wrap">
              <?= str_replace("\n", "<br/>", $info["ru_desc"]) ?>
            </div>
            <div class="hashtag">
              <?= getHashTag(convertHashtagString($info["ru_my_type"])) ?>
            </div>
          </div>


          <div class="section view-profile">
            <div class="summary">
              <div class="inline-group">
                <div class="col">
                  <h3 class="secondary-title">경력소개</h3>
                  <table class="info-table">
                    <caption><span class="sr-text">경력소개</span></caption>
                    <colgroup>
                      <col>
                      <col>
                    </colgroup>
                    <tbody>
                      <tr>
                        <td class="label">최종학력</td>
                        <td class="value"><?= $info["ru_edu"] ?></td>
                      </tr>
                      <tr>
                        <td class="label">경력사항</td>
                        <td class="value">
                          <ul class="period-list">
                            <?php foreach ($career_list as $row) : ?>
                              <li>
                                <span><?= $row["rc_type"] ?></span>
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
                                <em><?= $row["rc_date"] ?><?= $is_date ? "(" . get_date_diff_txt($sdate, $edate) . ")" : "" ?></em>
                              </li>
                            <?php endforeach; ?>
                          </ul>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col">
                  <h3 class="secondary-title">&nbsp;</h3>
                  <table class="info-table">
                    <caption><span class="sr-text"></span></caption>
                    <colgroup>
                      <col>
                      <col>
                    </colgroup>
                    <tbody>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="label">자&nbsp;&nbsp;격&nbsp;&nbsp;증</td>
                        <td class="value">
                          <ul class="period-list">
                            <?php foreach ($certi_list as $row) : ?>
                              <li>
                                <span>자격증</span>
                                <span><?= $row["rc_name"] ?></span>
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
                  <?= getWorkingImg($info["ru_work"]) ?>
                </div>

                <!-- 공유하기 -->
                <div class="share-wrap">
                  <button type="button" class="btn-share js-toggle-dropdown"><span class="sr-text">공유하기</span></button>
                  <div class="dropdown">
                    <!-- 카카오톡 공유 기능 주석처리 -->
                    <!--
                    <a href="#" onclick="shareMessage();return false;" class="share-link1">
                      <span class="ico"><img src="/resource/shop/web/img/ic_share_vendor1.svg" alt=""></span>
                      <span>카카오톡</span>
                    </a>
                    -->
                    <a href="#" onclick="CopyUrl();return false;" class="share-link2">
                      <span class="ico"><img src="/resource/shop/web/img/ic_share_copy_url.svg" alt=""></span>
                      <span>URL복사</span>
                    </a>
                  </div>

                  <!-- 카카오 SDK 스크립트 주석처리 -->
                  <!-- <script defer src="https://developers.kakao.com/sdk/js/kakao.js"></script> -->
                  <script>
                    /* 카카오톡 공유 기능 주석처리 */
                    /*
                    function shareMessage() {
                      if (!Kakao.isInitialized()) {
                        Kakao.init("<?= $kakao_key["인증키"] ?>");
                      }

                      var title = document.querySelector('.view-title').textContent.replace(/\s/g, ''),
                        //desc = document.querySelector('.view-desc .desc').textContent.replace(/\s/g,''),
                        //imgUrl = document.querySelector('.view-visual .visual-slider img').getAttribute('src');
                        url = location.href,
                        appUrl = 'scheme://host_path';

                      title = '나우잡 인재 - ' + title;
                      desc = ''; //'나우잡에서 인재 정보를 확인 하세요';
                      imgUrl = 'https://nowjob.co.kr/resource/shop/web/img/nowjob_info.png'; //'https://nowjob.co.kr/resource/shop/web/img/top_logo.svg';

                      setTimeout(function() {
                        Kakao.Share.sendDefault({
                          objectType: 'feed',
                          content: {
                            title: title, //글제목
                            description: desc, //글내용
                            imageUrl: imgUrl, //대표이미지 절대주소
                            link: {
                              mobileWebUrl: url,
                            },
                          },
                          buttons: [{
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
                    */

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
              <!-- 타이틀 -->
              <p class="title"><?= getAstrik($mem_info["mm_name"]) ?></p>
              <!-- 기타정보 -->

              <?php $mem_info["ad_addr"] = isset($addr[0]['ad_addr']) ? $addr[0]['ad_addr'] : ''; ?>

              <p class="type">
                <?php if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) == "") {
                  $gapbar1 = "display:none;";
                  $gapbar2 = "display:none;";
                } else if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) != "") {
                  $gapbar1 = "display:none;";
                  $gapbar2 = "display:none;";
                } else if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) == "") {
                  $gapbar1 = "display:none;";
                  $gapbar2 = "display:none;";
                } else if (trim($mem_info["mm_nation"]) == "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) != "") {
                  $gapbar1 = "display:none;";
                  $gapbar2 = "";
                } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) == "") {
                  $gapbar1 = "display:none;";
                  $gapbar2 = "display:none;";
                } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) == "" && trim($mem_info["ad_addr"]) != "") {
                  $gapbar1 = "";
                  $gapbar2 = "display:none;";
                } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) == "") {
                  $gapbar1 = "";
                  $gapbar2 = "display:none;";
                } else if (trim($mem_info["mm_nation"]) != "" && trim($mem_info["ma_age"]) != "" && trim($mem_info["ad_addr"]) != "") {
                  $gapbar1 = "";
                  $gapbar2 = "";
                }
                ?>
                <span><?= $mem_info["mm_nation"] ?></span>
                <span class="gapbar1" style="<?= $gapbar1 ?>">/</span>
                <span><?= getKoreanAge($mem_info["mm_birth"]) ?>세</span>
                <span class="gapbar2" style="<?= $gapbar2 ?>">/</span>
                <span class="area-addr" data-addr="<?= htmlspecialchars($mem_info["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($mem_info["ad_addr"], ENT_QUOTES, 'UTF-8') ?></span>
              </p>

              <?php if ($this->user->getUserType() == "MEM") { ?>
                <button type="button" aria-haspopup="dialog" data-ru_idx="<?= $info["ru_idx"] ?>" class="btnRequest btn-request btn-primary" style="background-color:#ccc;" disabled>제안보내기</button>
              <?php } else { ?>
                <button type="button" aria-haspopup="dialog" data-ru_idx="<?= $info["ru_idx"] ?>" class="btnRequest btn-request btn-primary">제안보내기</button>
              <?php } ?>
            </div>

            <!-- 히스토리 목록 -->
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
                            <div class="img">
                              <img src="<?= $row["ru_resume_img"] ?>">
                            </div>
                            <div class="desc">
                              <div class="wicon"><?= $row["ru_work_img"] ?></div>
                              <div class="title ellipsis"><?= $row["ru_name"] ?></div>
                              <div class="summary">
                                <ul class="working-day"><?= $row["ru_week"] ?></ul>
                              </div>
                              <div class="summary">
                                <p><?= $row["mm_nation"] ?></p>
                                <p><?= $row["mm_age"] ?>세</p>
                                <p class="area-addr" data-addr="<?= htmlspecialchars($row["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= $row["ad_addr"] ?></p>
                              </div>
                              <div class="summary2">
                                <p class="period"><?= getConvertResumePriceType($row["ru_price_type"]) ?></p>
                              </div>
                              <div class="summary3">
                                <p class="wtype"><?= getWorkType2025($row["ru_work_type"]) ?></p>
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
                  if (typeof KeenSlider === 'undefined') return;
                  var thumbnails = new KeenSlider(".recently-slider", {
                      initial: 0,
                      slides: {
                        perView: 6,
                      },
                      vertical: true,
                      drag: true,
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

        <!-- 하단 관련 인재 -->
        <div class="recomm-slider person">
          <h2 class="sr-text">추천인재</h2>
          <div class="slider-wrap keen-slider" id="my-keen-slider">
          </div>
        </div>
        <!-- //하단 관련 인재 -->

      </div>
    </div>

  </main><!-- //page-content -->
</div><!-- //page -->

<!-- 추천 리스트 템플릿 -->
<template id="tpl_reco_list">
  <div class="keen-slider__slide ">
    <div class="person-card" data-ru_idx="{{ru_idx}}">
      <div class="thumb-wrap">

        <div class="img">
          <img src="{{ru_resume_img}}">
          <button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button>
        </div>
      </div>
      <div class="desc">
        <div class="content-wrap" style="padding: 46px 5px 24px;">
          <p class="category">
            {{ru_work_img}}
          </p>
          <div class="title-wrap">
            <p class="title"><strong>{{mm_name}}</strong></p>
            <p class="area">{{ad_addr}}</p>
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
              ?>
              <?php if ($hasRecruits) : ?>
                <?php foreach ($recruit_list as $row) : ?>
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
    <div class="panel-footer">
      <div class="sticky-bottom" style="width:100%;">
        <button type="button" style="width:100%;" aria-controls="terms2Panel" class="btnProcess btn btn-primary js-toggle-panel">제안 보내기</button>
      </div>
    </div>
  </div>
</div>
<!-- //제안 리스트 -->

<script>
  var check_user_type = "<?= $this->user->getUserType(); ?>";
  $(".btnRequest").click(function() {
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

      return;
    }

    if (check_user_type != "COMP") {
      commonAlert("일반 회원은 제안 할 수 없습니다.", function() {});
      return;
    }

    $("[name='chk_request[]']").prop("checked", false);
    $("#selectPanel").addClass("on");

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
                  console.log("제안보내기 응답:", ret);
                  if (ret.code == "00") {
                    commonAlert("선택한 채용공고로 제안하였습니다.", function() {
                      $("#selectPanel").removeClass("on");

                      //채팅창 오픈
                      $("body").addClass('has-popup');
                      $(".js-open-chat").addClass("is-trigger");
                      $("#chatPopup").addClass("in");
                      $(".btn-close-chat").addClass("btn-close-chat");
                      $(".js-close-chat").addClass("show");

                      //첫번째 탭 활성화
                      setShow("1");

                      //해당공고 클릭 처리 - 채팅방 목록 로드
                      console.log("채팅방 목록 로드 시작 - rc_idx:", rc_idx);

                      // 채팅방 목록을 직접 로드
                      var chatParams = {};
                      chatParams["ru_idx"] = rc_idx;
                      chatParams["chatgbn"] = 'chatPanel1';

                      comAjax("/chat_proc/get_chat_target_list", chatParams, function(ret) {
                        console.log("채팅방 목록 응답:", ret);
                        if (ret.code == "00") {
                          $("#chat_list1_1").html("");
                          var list = ret.data.list;
                          console.log("채팅방 목록:", list);

                          if (list && list.length > 0) {
                            list.forEach(function(row) {
                              if (rc_idx == row.rc_idx) {
                                console.log("매칭되는 채팅방 발견:", row);
                                // 채팅방 템플릿 처리
                                var $clone = $("#tpl_chat_by_recruite").clone();
                                if ($clone.length > 0) {
                                  var $content = $clone.html();
                                  $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
                                  $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
                                  $content = $content.replaceAll("{{rm_idx}}", row.rm_idx);
                                  $content = $content.replaceAll("{{mm_name}}", row.mm_name);
                                  $content = $content.replaceAll("{{mm_img}}", row.mm_img);
                                  $content = $content.replaceAll("{{rc_name}}", row.rc_name);
                                  $content = $content.replaceAll("{{rm_last_msg}}", row.rm_last_msg);
                                  $content = $content.replaceAll("{{rm_last_dt}}", row.rm_last_dt);

                                  $clone.html($content);
                                  $("#chat_list1_1").append($clone.html());
                                }
                              }
                            });
                          } else {
                            console.log("채팅방 목록이 비어있음");
                          }
                        } else {
                          console.error("채팅방 목록 로드 실패:", ret.msg);
                        }
                      });

                      //페이징 시작 초기화
                      //rm_start = 0;
                      //rm_idx = ret.data;

                      console.log('채팅방 콜');
                      //룸 호출 - 채팅방 목록 로드 후 실행
                      setTimeout(function() {
                        try {
                          //var targetId = $("#chat_list2 li:first-child").data("target_id");
                          var targetId = $("#chat_list1_1 li:first-child").data("target_id");
                          console.log("targetId:", targetId);

                          if (targetId && typeof socket !== 'undefined' && socket) {
                            console.log("채팅방 연결 시도 - rm_idx:", targetId);
                            socket.emit("room_join", {
                              rm_idx: Number(targetId),
                              con_user_id: $("#user_id").val()
                            });
                            socket.emit("room_info", {
                              rm_idx: Number(targetId),
                              con_user_id: $("#user_id").val()
                            });
                            $('.chat-view').css('display', 'flex');
                            console.log("채팅방 연결 성공");
                          } else {
                            console.log("채팅방 연결 실패 - targetId:", targetId, "socket:", typeof socket);
                          }
                        } catch (chatError) {
                          console.error("채팅방 처리 오류:", chatError);
                        }
                        //$("#chat_list1_1 li:first-child").click();

                      }, 3000); // 5초에서 3초로 단축

                    });
                  } else {
                    console.error("제안보내기 오류:", ret);
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
        commonAlert("<em>다음날</em> 다시 제안이 가능합니다.", function() {});
        return;
      } else if (ret.code == "90") {
        // 지원이 이미 있는 경우
        commonAlert(ret.msg, function() {});
        return;
      } else {
        commonAlert(ret.msg, function() {});
        return;
      }


    });

  });

  function getRecoList() {
    var params = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "5",
      "idx": "<?= $info["ru_idx"] ?>"
    };
    comAjax("/resume_proc/search_reco_list2025", params, function(ret) {
      if (ret.code == "00") {
        var list = ret.data.list;
        $("#my-keen-slider").html("");
        $(".arrow--left").remove();
        $(".arrow--right").remove();
        list.forEach(function(row) {
          var $clone = $("#tpl_reco_list").clone();
          var $content = $clone.html();

          $content = $content.replaceAll("{{ru_idx}}", row.ru_idx);
          $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
          $content = $content.replaceAll("{{mm_name}}", row.mm_name);
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

          $("#my-keen-slider").append($clone.html());
        });

        var slideCount = document.querySelectorAll('.recomm-slider .slider-wrap .keen-slider__slide').length;
        // var perView = slideCount < 5 ? slideCount : 5;
        var perView = 5;
        var slider = new KeenSlider(".recomm-slider .slider-wrap", {
          loop: slideCount > perView,
          slides: {
            perView: perView
          },
        }, [
          addActiveClass,
          sliderArrows
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
      } else {

      }
    });
  }

  // add.js와 동일한 주소 축약 로직 사용
  function fRemoveBigAdrJS(arg) {
    if (typeof arg !== "string") {
      return "";
    }

    var raw = arg.trim();
    if (raw === "") {
      return "";
    }

    // 쉼표 포함 시 첫 토큰만 사용
    var firstPart = raw.split(",")[0];

    var replacements = [
      ["서울특별시 ", "서울시 "],
      ["경기도 ", ""],
      ["강원특별자치도 ", ""],
      ["충청남도 ", ""],
      ["충청북도 ", ""],
      ["경상남도 ", ""],
      ["경상북도 ", ""],
      ["전라남도 ", ""],
      ["전북특별자치도 ", ""],
      ["제주특별자치도 ", ""],
      ["세종특별자치시 ", "세종시 "],
      ["광주광역시 ", "광주시 "],
      ["인천광역시 ", "인천시 "],
      ["울산광역시 ", "울산시 "],
      ["부산광역시 ", "부산시 "],
      ["대구광역시 ", "대구시 "],
      ["대전광역시 ", "대전시 "],
      ["경기 ", ""],
      ["강원 ", ""],
      ["충남 ", ""],
      ["충북 ", ""],
      ["경남 ", ""],
      ["경북 ", ""],
      ["전남 ", ""],
      ["전북 ", ""],
      ["제주 ", ""]
    ];

    replacements.forEach(function(pair) {
      firstPart = firstPart.replace(pair[0], pair[1]);
    });

    var parts = firstPart.split(" ");
    if (parts.length >= 3) {
      // 세 번째 토큰이 숫자를 포함하면 첫 두 토큰만 사용
      if (/\d/.test(parts[2])) {
        return parts[0] + " " + parts[1];
      }
      return parts[0] + " " + parts[2];
    }

    return firstPart;
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
  $(document).ready(function() {
    getRecoList();
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

    // 근무지역 주소 처리 (중복 제거 포함)
    var $workArea = $('#work-area-addr');
    if ($workArea.length > 0) {
      var addrArray = $workArea.data('addrs');
      if (addrArray && Array.isArray(addrArray) && typeof fRemoveBigAdrJS === 'function') {
        var processedAddrs = [];
        var formattedAddrs = [];

        addrArray.forEach(function(originalAddr) {
          if (originalAddr && originalAddr.trim() !== '') {
            var formattedAddr = fRemoveBigAdrJS(originalAddr);
            // 중복 체크: 이미 처리된 주소가 아니면 추가
            if (formattedAddr && processedAddrs.indexOf(formattedAddr) === -1) {
              processedAddrs.push(formattedAddr);
              formattedAddrs.push(formattedAddr);
            }
          }
        });

        // 정리된 주소들을 쉼표로 연결하여 표시
        $workArea.text(formattedAddrs.join(', '));
      }
    }
  });
</script>