<?= getCachCss(RES_PATH . "/css/resume_list.css") ?>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab3"; ?>
      <?php $tab_on2 = "menu1"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
      <div class="tab-panel on">
        <div class="recruit-bookmark">
          <h3 class="sr-text">즐겨찾기한 인재</h3>
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
$tpl_show_action_slot = false;
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
      html = html.replaceAll("{{action_slot}}", "");
      $clone.html(html);
      $ul.append($clone.html());
    });
  }

  $(document).on('click', '.desc', function() {
    var ruIdx = $(this).closest('.list-item').data('ru_idx');
    if (ruIdx) {
      var url = '/resume/hr_post/' + ruIdx;
      window.open(url, '_blank');
    }
  });
</script>
