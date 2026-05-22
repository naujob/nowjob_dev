<?php
/* JS 렌더링용 데이터 전처리: PHP 함수 결과를 배열로 변환 */
$js_list = array();
foreach ($list as $row) {
    $js_list[] = array(
        'rc_idx'           => $row['rc_idx'],
        'rt_recruit_img'   => getRecruitImg($row),
        'book_yn'          => ($row['book_yn'] == 'Y' ? 'on' : ''),
        'rt_work_img'      => getWorkingImg($row['rc_work']),
        'rc_name'          => $row['rc_name'],
        'rc_nation_nm'     => $row['rc_nation_nm'],
        'rc_age_nm'        => $row['rc_age_nm'],
        'rt_work_type'     => getWorkTypeWithColor($row['rc_work_type']),
        'ad_addr'          => $row['ad_addr'],
        'rc_price_type'    => getConvertPriceType($row['rc_price_type'], $row['rc_price_rep']),
        'rc_price_display' => number_format($row['rc_price_pay']) . ' 원',
        'chk_value'        => $row['rc_idx'],
    );
}
?>
<link rel="stylesheet" href="<?= RES_PATH ?>/css/recruit_list.css">
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab3"; ?>
      <?php $tab_on2 = "menu1"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/mypage/top_line_2025.php'); ?>
      <div class="tab-panel on">
        <div class="recruit-bookmark">
          <h3 class="sr-text">즐겨찾기한 지원공고</h3>
          <div class="result-wrap">
            <ol class="result-list" id="ajaxList">
            </ol>
          </div>
          <div class="form-action-wrap align-right">
            <button type="button" id="btnRequest" aria-haspopup="dialog" class="btn-round btn-tertiary">지원하기</button>
          </div>
        </div>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />

<?php
$tpl_show_timestamp    = false;
$tpl_show_recomm_badge = false;
$tpl_show_selection    = true;
include_once(get_base_url() . '/application/shop/views/web/_layout/recruit_list_template.php');
?>

<script>
  var recruitList = <?= json_encode($js_list) ?>;

  /* 목록 렌더링 */
  (function() {
    var $listEl = $("#ajaxList");
    recruitList.forEach(function(row) {
      var $clone = $("#tpl_list").clone();
      var content = $clone.html();
      content = content.split("{{rc_idx}}").join(row.rc_idx);
      content = content.split("{{rt_recruit_img}}").join(row.rt_recruit_img);
      content = content.split("{{book_yn}}").join(row.book_yn);
      content = content.split("{{rt_work_img}}").join(row.rt_work_img);
      content = content.split("{{rc_name}}").join(row.rc_name);
      content = content.split("{{rc_nation_nm}}").join(row.rc_nation_nm);
      content = content.split("{{rc_age_nm}}").join(row.rc_age_nm);
      content = content.split("{{rt_work_type}}").join(row.rt_work_type);
      content = content.split("{{ad_addr}}").join(typeof fRemoveBigAdrJS === 'function' ? fRemoveBigAdrJS(row.ad_addr) : row.ad_addr);
      // 협의인 경우 금액 표시 안함
      if (row.rc_price_type.indexOf('협의') > -1) {
        content = content.split("{{rc_price_type}}").join(row.rc_price_type);
        content = content.split("{{rc_price_display}}").join("");
      } else {
        content = content.split("{{rc_price_type}}").join(row.rc_price_type);
        content = content.split("{{rc_price_display}}").join(row.rc_price_display);
      }
      content = content.split("{{chk_value}}").join(row.chk_value);
      content = content.split("{{timestamp}}").join('');
      $clone.html(content);
      $listEl.append($clone.html());
    });
  })();

  /* 지원하기 버튼 */
  $("#btnRequest").click(function() {
    if ($("#is_login").val() != "Y") {
      commonLogin();
      return;
    }
    if ($("[name=resume_yn]").val() == "N") {
      commonAlert("이력서 작성및 공개이력서 설정후 지원해주세요.", function() {});
      return;
    }
    var mq_idxs = new Array();
    for (var icnt = 0; icnt < $("[name='chk[]']:checked").length; icnt++) {
      mq_idxs.push($("[name='chk[]']:checked").eq(icnt).val());
    }
    if (mq_idxs.length == 0) {
      commonAlert("지원하실 공고를 선택해주세요.", function() {});
      return;
    }
    openPopup('confirm', {
      content: '<em>선택한</em> 공고에<br> 지원하시겠습니까?',
      secondCallback: function() {
        var params = {};
        params["mq_idxs"] = mq_idxs;
        params["mq_type"] = "RECRUIT";
        comAjax("/common_proc/member_request", params, function(ret) {
          if (ret.code == "00") {
            commonAlert("지원 신청 되었습니다.", function() {
              $("[name='chk[]']").prop("checked", false);
            });
          } else {
            commonAlert(ret.msg, function() {});
            return;
          }
        });
      }
    });
  });

  /* 공고 클릭 시 상세 페이지 열기 */
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.list-item').data('rc_idx');
    if (rcIdx) {
      window.open('/recruit/job_post/' + rcIdx, '_blank');
    }
  });
</script>
