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
  /* 탭 컨테이너 스크롤 시 상단 고정 */
  .page.page-mypage-view .top-bar~.page-content .tab-container {
    position: -webkit-sticky !important;
    position: sticky !important;
    top: 0 !important;
    z-index: 10 !important;
    background: #fff !important;
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
                <li><a href="javascript:_replace('/mypage/bookmark_recruit1');" class="tab">즐겨찾기한 공고</a></li>
                <li class="on"><span class="tab">제안받은 공고</span></li>
            </ul>
        </div>

    <div class="tab-panel">
      <div class="section recruit-bookmark">
        <div class="container">
          <h2 class="sr-text">제안받은 공고</h3>
          <div class="result-wrap">
            <!-- 목록 -->
            <ol class="result-list result-list-card">
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
                        </div>
                        <div class="summary-container">
                          <div class="summary3"><?=getConvertWorkType($row["rc_work_type"])?></div>
                          <div class="summary2">
                            <p class="period"><?=getConvertPriceType($row["rc_price_type"], $row["rc_price_rep"])?></p>
                            <p class="amount"><?=getConvertPrice($row["rc_price_pay"])?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>            
              <?php endforeach; ?>
            </ol>
            <!-- //목록 -->
          </div>
          <div class="sticky-bottom">
            <button type="button" aria-haspopup="dialog" id="btnRequest" class="btn btn-primary ">지원하기</button>
          </div>     
        </div>
      </div>
    </div>
  </div><!-- //page-content -->
</div>


<input type="hidden" name="resume_yn" value="<?=$resume ==null ? "N":"Y"?>" />
<script>
  window.addEventListener('DOMContentLoaded', function() {
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
        var url = '/recruit/job_post/' + rcIdx;
        location.href = url;
      }
    });
  });
</script>