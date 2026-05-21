<?= getCachCss(RES_PATH . "/css/resume_list.css") ?>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab3"; ?>
      <?php $tab_on2 = "menu3"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
      <div class="tab-panel on">
        <div class="recruit-bookmark">
          <h3 class="sr-text">제안보낸 인재</h3>
          <div class="result-wrap">
            <!-- 목록 -->
            <ol class="result-list" id="ajaxList"></ol>
            <!-- //목록 -->
          </div>
        </div>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<?php
// JavaScript 렌더링용 데이터 가공
$js_list = [];
foreach ($list as $row) {
  $row["ru_work_img"]   = getWorkingImg($row["ru_work"]);
  $row["ru_week_html"]  = getWeekYoil($row["ru_week"]);
  $row["mm_age"]        = getKoreanAge($row["mm_birth"]);
  $row["ru_resume_img"] = getResumeImg($row);
  $js_list[] = $row;
}
$tpl_id               = 'tpl_person_card';
$tpl_show_timestamp   = false;
$tpl_show_mm_idx      = false;
$tpl_show_action_slot = true;
$tpl_price_wrap_width = '290px';
include APPPATH . 'views/web/_layout/resume_list_template.php';
?>

<script>
  var personList = <?= json_encode($js_list, JSON_UNESCAPED_UNICODE) ?>;

  $(document).ready(function() {
    showPersonList(personList);
  });

  function showPersonList(list) {
    var $ul = $("#ajaxList");
    $ul.html("");
    list.forEach(function(row) {
      var $clone = $("#tpl_person_card").clone();
      var html = $clone.html();
      html = html.replaceAll("{{ru_idx}}", row.ru_idx || "");
      html = html.replaceAll("{{mm_idx}}", row.mm_idx || "");
      html = html.replaceAll("{{ru_resume_img}}", row.ru_resume_img || "");
      html = html.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      html = html.replaceAll("{{ru_work_img}}", row.ru_work_img || "");
      html = html.replaceAll("{{ru_name}}", row.ru_name || "");
      html = html.replaceAll("{{mm_nation}}", row.mm_nation || "");
      html = html.replaceAll("{{ma_age}}", row.mm_age ? row.mm_age + "대" : "");
      html = html.replaceAll("{{ru_work_type}}", getWorkTypeFirst(row.ru_work_type) || "");
      html = html.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr) || "");
      html = html.replaceAll("{{ru_price_type}}", getPriceTypeJSFirst(row.ru_price_type) || "");
      html = html.replaceAll("{{ru_week}}", row.ru_week_html || "");
      html = html.replaceAll("{{timestamp}}", "");
      var actionHtml = '<div class="action-wrap"><div class="form-action-wrap align-right">' +
        '<button type="button" aria-haspopup="dialog" class="btn-round btn-tertiary btnCancle" ' +
        'data-ru_idx="' + (row.ru_idx || "") + '" ' +
        'data-rc_idx="' + (row.rc_idx || "") + '" ' +
        'data-target_id="' + (row.target_id || "") + '" ' +
        'data-user_id="' + (row.user_id || "") + '" ' +
        'data-target_img="' + (row.target_img || "") + '" ' +
        'data-user_img="' + (row.user_img || "") + '" ' +
        'data-target_name="' + (row.target_name || "") + '" ' +
        'data-user_name="' + (row.user_name || "") + '" ' +
        'data-subject="' + (row.rc_name || "") + '">제안 취소</button>' +
        '</div></div>';
      html = html.replaceAll("{{action_slot}}", actionHtml);
      $clone.html(html);
      $ul.append($clone.html());
    });
  }

  $(document).on("click", ".btnCancle", function(e) {
    e.stopPropagation();
    var ru_idx = $(this).data('ru_idx');
    var $btn = $(this);

    openPopup('confirm', {
      content: '<em>제안</em>을 취소하시겠습니까?',
      secondCallback: function() {
        var params = {};
        params["ru_idx"] = ru_idx;
        params["rc_idx"] = $btn.data('rc_idx');

        comAjax("/compage_proc/resume_propose_cancle", params, function(ret) {
          if (ret.code == "00") {
            commonAlert("제안 취소 되었습니다.", function() {
              location.reload();
            });
          } else {
            console.log(ret);
            commonAlert("삭제처리시 오류가 발생하였습니다.", function() {});
          }
        });
      }
    });
  });

  $(document).on('click', '.desc', function() {
    var ruIdx = $(this).closest('.list-item').data('ru_idx');
    if (ruIdx) {
      var url = '/resume/hr_post/' + ruIdx;
      window.open(url, '_blank');
    }
  });
</script>
