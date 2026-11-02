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
  /* 희망근무조건 요일 아이콘 (resume/hr_post.php와 동일) */
  .item-view .info-table .working-day>li {
    width: 36px;
    height: 36px;
    font-size: 18px;
    line-height: 36px;
  }

  /* info-table 값 영역 구분선 (resume/hr_post.php와 동일) */
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

  /* info-table 내 em 태그 스타일 (급여형태 표시용) - .worktype 제외 */
  .info-table .value > em {
    font-style: normal;
    color: #1b9d7d;
  }

  .info-table .value > em.off {
    color: #222;
  }

  .period-list em {
    display: inline-block;
  }

  /* 근무지역 주소 em: 구분선 규칙만 쓰고 글자색은 상속 */
  #work-area-addr > em {
    color: inherit;
  }

  /* 경력소개 → 자격증 순서로 세로 배치 (resume/hr_post.php와 동일) */
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
              <div class="section view-profile">
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
            </div>
            <div class="col">
              <h3 class="secondary-title">자격증</h3>
              <div class="section view-profile">
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
          <div class="section view-introduce">
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