<?php
function getConvertWorkType($work_type) {
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

function getConvertPriceType($rc_price_type, $rc_price_rep) {

  if ($rc_price_type == "협의") {
    return '<em>급여협의</em>';
  }

  $types = preg_split('/\s*,\s*/', $rc_price_type);

  if (count($types) === 1) {
    return '<em class="on">' . $types[0] . '</em>';
  }

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

function getConvertPrice($rc_price_pay) {
  if (!$rc_price_pay) {
    return '<em>급여협의</em>';
  }
  return number_format($rc_price_pay)." 원";
}
                                   
function fRemoveBigAdr($arg) {

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
  .btn-bookmark {
    display: block;
    position: absolute;
    top: 0px;
    right: 0px;
    width: 40px;
    height: 40px;
    border: 0;
    background: url("/resource/shop/web/img/ic_bookmark_on.svg") no-repeat 0 0;
    background-size: 0px;
    pointer-events: none;
    z-index: 1;
  }
  .btn-bookmark:before {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    border-top: 40px solid #f8f8f8;
    border-left: 40px solid transparent;
    z-index: -1;
  }
  .btn-bookmark:after {
    content: "";
    display: block;
    position: absolute;
    top: 3px;
    right: 3px;
    width: 16px;
    height: 16px;
    border-style: hidden;
    background: url("/resource/shop/web/img/ic_bookmark_off.svg") no-repeat 0 0;
    background-size: contain;
    pointer-events: all;
    z-index: 1;
  }
  .btn-bookmark.on:after {
    background-image: inherit;
  }
  .sr-text {
    display: block;
    position: absolute;
    width: 1px;
    height: 1px;
    margin: 0;
    padding: 0;
    font-size: 1px;
    color: transparent;
    pointer-events: none;
    white-space: nowrap;
    overflow: hidden;
    border: 0;
    outline: 0;
  }

  .result-list .content-li {
    position: relative;
    width: 100%;
    height: 115px;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
    background: #fff;
    margin-bottom:10px;
    transition: background 0.2s ease;
  }
  .result-list .content-li.on {
    border: 1px solid #57CCAF;
  }
  .content-li * {padding: 0;margin: 0;}
  .content-li .content-info {width: 100%;height: 100%;padding:10px;}
  .content-info .wicon img{height: 18px;}
  .content-info .title {font-size: 16px;font-weight: bold;margin: 1px 0px;}
  .content-info .summary3 {
    position: absolute;
    right: 0px;
    top: 0px;
    font-size: 15px;
    font-weight: 600; 
    letter-spacing: 0;
    color: #333;
    display: flex;
    gap: 5px;
    align-items: flex-end;
  }
  .content-info .summary3 p {
    margin: 0;
    text-align: right;
  }

  .content-info .summary3 em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }
  .content-info .summary3 em.wtype1 {
    color:#3a70db;
  }
  .content-info .summary3 em.wtype2 {
    color:#F86048;
  }
  .content-info .summary3 em.wtype3 {
    color:#1B9D7D;
  }
  .content-info .summary3 em:not(:last-child) {
    padding-right: 5px; 
  }
  .content-info .summary3 em:not(:last-child)::after {
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

  .content-info .content-body {
    position: relative;
    height: 95px;  
    overflow: hidden;
  }
  .recomm-badge {
    position: absolute;
    background: #EB5349;
    z-index: 1;
    margin: 3px;
    padding: 3px;
    border-radius: 3px;
  }
  .content-info .desc {
    position: relative;
    height: 95px;
    margin: 0 0 0 105px;
  }
  .content-info .desc .ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width:80%;
  }
  .content-info .img {
    position: absolute;
    width: 95px;
    height: 95px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  .content-info .img img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* 이미지 비율 유지하며 박스를 꽉 채움 */
    display: block;
  }
  .content-info .link {color: #5085BB;}
  .content-info .summary {
    display: flex;
    font-size: 15px;
    gap: 5px;
    position: relative;
  }
  .content-info .summary p {
    position: relative;
    padding-right: 5px;
    margin: 0;
    color: #999;
  }
  .content-info .summary p:not(:last-child)::after {
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

  .content-info .summary > * {
    min-width: 0;
    max-width: 100%;
    margin-right: 4px;
    margin-bottom: -3px;
  }

  .content-info .summary.area p{
    color: #000;
    font-weight: 600;
  }
  .content-info .summary.reco p{
    color: #1b9d7d;
    font-weight: 600;
  }
  .content-info .summary2 {
    position: absolute;
    right: 0px;
    bottom: 0px;
    font-size: 17px;
    font-weight: 700;
    letter-spacing: 0;
    color: #1b9d7d;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }
  .content-info .summary2 .summary2 p {
    margin: 0;
    text-align: right;
  }
  .content-info .summary2 .period{
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;
  }
  .content-info .summary2 .amount em {
    font-style: normal;
  }
  .content-info .summary2 .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color:#777;
  }
  .content-info .summary2 .period em.on {
    color: #1B9D7D;
  }
  .content-info .summary2 .period em:not(:last-child) {
    padding-right: 5px;
  }
  .content-info .summary2 .period em:not(:last-child)::after {
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
  .result-list .list-item + .list-item {
    border-top: 0;
  }
  
</style>

<div class="page page-mypage-view">
  <div class="top-bar">
    <a href="javascript:void(0);" class="btn-back"><span class="sr-text">이전으로</span></a>
    <h1 class="title">관심공고 관리</h1>
  </div><!-- //top-bar -->

  <div class="page-content">
    <div class="tab-container">
      <ul class="tab-list">
        <li><a href="/mypage/bookmark_recruit1_new" class="tab">즐겨찾기한 공고</a></li>
        <li class="on"><span class="tab">제안받은 공고</span></li>
      </ul>
    </div>

    <div class="tab-panel">
      <div class="section recruit-bookmark">
        <div class="container">
          <h2 class="sr-text">제안받은 공고</h3>
          <div class="result-wrap">
            <!-- 목록 -->
            <ol class="result-list">
              <?php foreach($list as $row) : ?>
                <li class="list-item">
                  <div class="content-li" data-rc_idx="<?=$row["rc_idx"]?>">
                    <div class="content-info">
                      <div class="content-body">
                        <div class="img">
                          <img src="<?=getRecruitImg($row)?>">
                        </div>
                        <div class="desc">
                          <div class="wicon">
                            <?=getWorkingImg($row["rc_work"])?>
                          </div>
                          <div class="title ellipsis">
                            <?=$row["rc_name"]?>
                          </div>
                          <div class="summary">
                            <p><?=$row["rc_nation_nm"]?></p>
                            <p><?=$row["rc_age_nm"]?></p>
                          </div>
                          <div class="summary area">
                            <p><?=fRemoveBigAdr($row["ad_addr"])?></p>
                          </div>
                          <div class="summary2">
                            <p class="period"><?=getConvertPriceType($row["rc_price_type"], $row["rc_price_rep"])?></p>
                            <p class="amount"><?=getConvertPrice($row["rc_price_pay"])?></p>
                          </div>
                          <div class="summary3"><?=getConvertWorkType($row["rc_work_type"])?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>            
              <?php endforeach; ?>
            </ol>
            <!-- //목록 -->
          </div>
          <div class="sticky-bottom view-footer">
            <button type="button" aria-haspopup="dialog" id="btnRequest" class="btn btn-primary ">지원하기</button>
          </div>     
        </div>
      </div>
    </div>
  </div><!-- //page-content -->
</div>


<input type="hidden" name="resume_yn" value="<?=$resume ==null ? "N":"Y"?>" />
<script>
	$("#btnRequest").click(function(){
    //로그인 여부 확인
    if($("#is_login").val()!="Y"){
      commonLogin();
      return;
    }

    //이력서 여부 확인
    if($("[name=resume_yn]").val()=="N"){
      commonAlert("이력서 작성및 공개이력서 설정후 지원해주세요.",function(){});
      return;
    }

    var mq_idxs = [];

    $(".content-li.on").each(function () {
      var rc_idx = $(this).data("rc_idx");
      mq_idxs.push(rc_idx);
    });
    
		if(mq_idxs.length==0){
			commonAlert("지원하실 공고를 선택해주세요.",function(){});
			return;
		}

		openPopup('confirm', {
      content: '<em>선택한</em> 공고에<br> 지원하시겠습니까?',
      secondCallback: function () {
        var params = {};
        params["mq_idxs"] = mq_idxs;
        params["mq_type"] = "RECRUIT";
        comAjax("/common_proc/member_request",params,function(ret){
          if(ret.code=="00"){
            commonAlert("지원 신청 되었습니다.",function(){
              $("[name='chk[]']").prop("checked",false);
            });
          }else{
            commonAlert(ret.msg,function(){});
            return;
          }
        });
      }
    });
	});

  $(document).on('click', '.desc', function () {
    $(this).closest('.content-li').toggleClass('on');
  });
  
  $(document).on('click', '.img', function () {
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post_new/' + rcIdx;
      location.href = url;
    }
  });
</script>