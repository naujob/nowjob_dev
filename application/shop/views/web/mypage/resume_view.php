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
<style>
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
  .inline-group {
    flex-direction: column;

  }
.section.view-profile .inline-group .col:nth-child(2) {
  width: 150px;
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
                <img src="<?= getMemberImg($mem_info) ?>" alt="프로필 이미지" onError="this.src='/resource/shop/mobile/img/common/noimage4.png'">
              </div>
              <!-- 평점 -->
              <p class="star-rating">
                <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                <span class="star" style="width:80%;"></span>
                <span class="sr-text">평점</span>
                <span class="sr-text">5</span>
              </p>
              <div class="profile-detail">
                <!-- 이름 -->
                <p class="name"><em><?= $mem_info["mm_name"] ?></em>님</p>
                <div class="meta">
                  <!-- 추가사항 -->
                  <p><span><?= $mem_info["mm_nation"] ?></span> / <span><?= $mem_info["ma_age"] ?>세</span></p>
                  <p><?= $mem_info["mm_phone"] ?></p>
                </div>
              </div>
              <ul class="profile-record">
                <li><span class="label">프로필 조회</span><em class="value"><?= $info["ru_hit"] ?></em></li>
                <li><span class="label">받은제안</span><em class="value"><?= $info["req_cnt"] ?></em></li>
                <li><span class="label">관심인재</span><em class="value"><?= $info["fav_cnt"] ?></em></li>
              </ul>
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
                        <td class="value"><?= $info["ru_price_type"] ?></td>
                      </tr>
                      <tr>
                        <td class="label">근무일자</td>
                        <td class="value"><?= (get_date($info["ru_work_dt"])) ?>(<?= get_yoil($info["ru_work_dt"]) ?>)</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="col">
                 
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
                          foreach ($addr as $key => $ad) :
                            if ($ad["ad_addr"] != "") {
                              if ($addr_cnt > 0) echo " | ";
                              $addr_cnt++;
                              echo fRemoveBigAdrPHP($ad["ad_addr"]);
                            }
                          endforeach;
                          */
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td class="label">근무형태</td>
                        <td class="value"><?= $info["ru_work_type"] ?></td>
                      </tr>
                      <tr>
                        <td class="label">근무요일</td>
                        <td class="value">
                          <?php
                          // 요일무관 체크 여부 확인
                          $isWeekUnlimited = (isset($info["ru_week_yn"]) && $info["ru_week_yn"] === "Y");
                          // 저장된 요일 값 확인
                          $hasSavedWeek = isset($info["ru_week"]) && trim((string) $info["ru_week"]) !== "";
                          // 저장된 값이 없으면 간편등록 기본값(평일) 사용
                          $weekDisplaySource = $hasSavedWeek ? $info["ru_week"] : "월,화,수,목,금";
                          ?>
                          <?php if ($isWeekUnlimited) : ?>
                            무관
                          <?php else : ?>
                            <ul class="working-day">
                              <?= getWeekYoil($weekDisplaySource) ?>
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
            <?php if (!empty($info["ru_my_type"])) : ?>
              <h3 class="secondary-title" style="margin-top: 30px;">나의강점</h3>
            <div class="hashtag">
                <?php
                // 나의강점 데이터 처리 (resume_write.php 팝업 표시 방식 참고)
                // 쉼표로 구분된 문자열을 배열로 변환
                $my_type_array = explode(",", $info["ru_my_type"]);
                foreach ($my_type_array as $type) {
                  $type = trim($type);
                  if (!empty($type)) {
                    // 해시태그가 없으면 추가
                    if (strpos($type, "#") === false) {
                      $type = "#" . $type;
                    }
                    echo "<p class='tag'>" . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . "</p>";
                  }
                }
                ?>
            </div>
            <?php endif; ?>
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
                        <td class="label">자격증</td>
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
                <div class="inline-group" style="flex-direction: row;">
                  <!-- 카테고리 라벨 -->
                  <?= getWorkingImg($info["ru_work"]) ?>
                </div>
              </div>
              <!-- 타이틀 -->
              <p class="title"><?= $mem_info["mm_name"] ?></p>
              <!-- 기타정보 -->
              <?php $mem_info["ad_addr"] = isset($addr[0]['ad_addr']) ? $addr[0]['ad_addr'] : ''; ?>
              <p class="type"><span><?= $mem_info["mm_nation"] ?></span> / <span><?= $mem_info["ma_age"] ?>세</span> / <span class="area-addr" data-addr="<?= htmlspecialchars($mem_info["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($mem_info["ad_addr"], ENT_QUOTES, 'UTF-8') ?></span></p>
            </div>
          </div>
        </div>
        <!-- //aside -->
      </div>
    </div>
    <div class="view-footer">
      <div class="form-action-wrap">
        <button type="button" id="btnDelete" aria-haspopup="dialog" class="btn-round btn-secondary js-remove">삭제</button>
        <button type="button" id="btnEdit" class="btn-round btn-tertiary">수정</button>
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
        content: '<em>“' + ru_name + '”</em><br> 이력서를 삭제하시겠습니까?<br> 등록하신 모든 데이터는<br> 삭제됩니다.',
        firstButton: '취소',
        secondButton: '삭제',
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

    // 근무지역 주소 처리 (add.js의 fRemoveBigAdrJS 사용)
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

        // 정리된 주소들을 " | "로 연결하여 표시
        $workArea.text(formattedAddrs.join(' | '));
      }
    }

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