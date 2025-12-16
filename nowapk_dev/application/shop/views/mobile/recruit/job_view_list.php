<?php
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

  $item_set = array_unique(array_map('trim', $items));

  $has_jugan = in_array('주간', $item_set);
  $has_yagan = in_array('야간', $item_set);
  $has_2shift = in_array('2교대', $item_set);

  if ($has_jugan && $has_yagan && $has_2shift) {
    return '<em class="wtype0">근무협의</em>';
  }

  $result = '';
  foreach ($item_set as $text) {
    if ($text === '') continue;

    $cls = '';
    if ($text === '주간') {
      $cls = 'wtype1';
    } else if ($text === '야간') {
      $cls = 'wtype2';
    } else if ($text === '2교대') {
      $cls = 'wtype3';
    }

    $result .= '<em class="' . $cls . '">' . $text . '</em>';
  }

  return $result;
}

function getConvertPriceType($rc_price_type, $rc_price_rep)
{
  if ($rc_price_type == "협의") {
    return '<em>&nbsp;</em>';
  }

  // 쉼표로 분리
  $types = preg_split('/\s*,\s*/', $rc_price_type);

  if (count($types) === 1) {
    return '<em class="on">' . $types[0] . '</em>';
  }

  // 다중 유형 처리
  $result = '';
  foreach ($types as $type) {
    $type = trim($type);
    if ($type === $rc_price_rep) {
      $result .= '<em class="on">' . $type . '</em>';
    } else {
      $result .= '<em>' . $type . '</em>';
    }
  }

  return $result;
}

function getConvertPrice($rc_price_pay)
{
  if (!$rc_price_pay) {
    return '급여협의';
  }

  return number_format($rc_price_pay) . "원";
}

function fRemoveBigAdr($arg)
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

<style>
  .result-list .list-item+.list-item {
    border: 0px;
  }

  .list-item .content-li {
    position: relative;
    width: 100%;
    height: 115px;
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

  .list-item .content-li:hover {
    background: #f8f8f8;
  }

  .list-item .content-li * {
    padding: 0;
    margin: 0;
  }

  .list-item .content-li .content-info {
    width: 100%;
    height: 100%;
    padding: 5px;
  }

  .list-item .content-info .wicon img {
    height: 19px;
  }

  .list-item .content-info .title {
    font-size: 18px;
    font-weight: bold;

  }

  .list-item .content-info .content-body {
    position: relative;
    overflow: hidden;
    height: 105px;
    margin-top: 5px;
    /* 아래로 살짝 이동 */
  }

  .list-item .content-info .desc {
    position: relative;
    margin: 0 0 0 100px;
  }

  .list-item .content-info .desc .ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .list-item .content-info .img {
    position: absolute;
    width: 95px;
    height: 95px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  .list-item .content-info .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    /* 이미지 비율 유지하며 박스를 꽉 채움 */
    display: block;
  }

  .list-item .content-info .link {
    color: #5085BB;
  }

  .list-item .content-info .summary {
    display: flex;
    font-size: 16px;
    gap: 5px;
    position: relative;
  }

  .list-item .content-info .summary p {
    position: relative;
    padding-right: 5px;
    margin: 0;
    color: #999;
  }

  .list-item .content-info .summary p:not(:last-child)::after {
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

  .list-item .content-info .summary>* {
    min-width: 0;
    max-width: 100%;
    margin-right: 4px;
    margin-bottom: -3px;
  }

  .list-item .content-info .summary.area p {
    color: #000;
    font-weight: 600;
  }

  .list-item .content-info .summary.reco p {
    color: #1b9d7d;
    font-weight: 600;
  }

  .list-item .content-info .summary2 {
    position: absolute;
    right: 2px;
    top: 50px;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 0;
    color: #777;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }

  .list-item .content-info .summary2 p {
    margin: 0;
    text-align: right;
  }

  .list-item .content-info .summary2 .period {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 16px;
    font-weight: 600;
  }

  .list-item .content-info .summary2 .amount {
    color: #1b9d7d;
  }

  .list-item .content-info .summary2 .amount em {
    font-style: normal;
  }

  .list-item .content-info .summary2 .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .list-item .content-info .summary2 .period em:not(:last-child) {
    padding-right: 5px;
  }

  .list-item .content-info .summary2 .period em.on {
    color: #1b9d7d;
  }

  .list-item .content-info .summary2 .period em:not(:last-child)::after {
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

  .list-item .content-info .summary3 {
    position: absolute;
    top: 0px;
    right: 2px;
    color: #000;
    width: auto;
    height: 17px;
    cursor: pointer;
  }

  .list-item .content-info .summary3 .wtype {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 16px;
    font-weight: 600;
  }

  .list-item .content-info .summary3 .wtype em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #333;
  }

  .list-item .content-info .summary3 .wtype em:not(:last-child) {
    padding-right: 5px;
  }

  .list-item .content-info .summary3 .wtype em:not(:last-child)::after {
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

  .list-item .content-info .summary3 .wtype em.wtype1 {
    color: #3a70db;
  }

  .list-item .content-info .summary3 .wtype em.wtype2 {
    color: #F86048;
  }

  .list-item .content-info .summary3 .wtype em.wtype3 {
    color: #1B9D7D;
  }
</style>
<div class="page-recruit">

  <div class="top-bar">
    <a href="javascript:history.back();" class="btn-replace"><span class="sr-text">최근본공고</span></a>
    <h1 class="title" id="rm_subject">최근본공고</h1>
  </div>


  <div class="page-content" style="padding-bottom: 0;">

    <div class="section is-last">
      <div class="container">
        <h2 class="sr-text">채용공고</h2>
        <!-- 목록 -->
        <ol class="result-list" id="ajaxList" data-page="1">
          <?php if (!empty($view_list)) { ?>
            <?php foreach ($view_list as $row) : ?>
              <li class="list-item">

                <div class="content-li" data-rc_idx="<?= $row["rc_idx"] ?>">
                  <div class="content-info">
                    <div class="content-body">
                      <div class="img">
                        <img src="<?= $row["rt_recruit_img"] ?>">
                      </div>
                      <div class="desc">
                        <div class="wicon"><?= getWorkingImg($row["rc_work"]) ?></div>
                        <div class="title ellipsis"><?= $row["rc_name"] ?></div>
                        <div class="summary">
                          <p><?= $row["rc_nation_nm"] ?></p>
                          <p><?= $row["rc_age_nm"] ?></p>
                        </div>
                        <div class="summary area">
                          <p><?= fRemoveBigAdr($row["ad_addr"]) ?></p>
                        </div>
                        <div class="summary2">
                          <p class="period"><?= getConvertPriceType($row["rc_price_type"], $row["rc_price_rep"]) ?></p>
                          <p class="amount"><?= getConvertPrice($row["rc_price_pay"]) ?></p>
                        </div>
                        <div class="summary3">
                          <p class="wtype"><?= getWorkType2025($row["rc_work_type"]) ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </li>
            <?php endforeach; ?>
          <?php } else { ?>
            <?php if ($this->user->isLogin()) : ?>
              <li style="margin-top: 50%; text-align: center; color: #777;">최근 본 공고가 없습니다.</li>
            <?php else : ?>
              <li style="margin-top: 50%; text-align: center; color: #777;">로그인 하시면,<br> 최근 본 공고를 확인 하실 수 있습니다.</li>
            <?php endif; ?>
          <?php } ?>
        </ol>
        <!-- //목록 -->
        <!-- 빈목록 -->
        <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
          <p>등록된 정보가 없습니다.</p>
        </div>
        <!-- //빈목록 -->
      </div>
    </div>

  </div><!-- //page-content -->

</div>
<script>
  $(document).ready(function() {
    $('body').css('overflow', 'auto'); //24.11.25 임시처리
  });
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      location.href = url;
    }
  });
</script>