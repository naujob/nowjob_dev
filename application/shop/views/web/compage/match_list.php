<?php
// JS 렌더링용 데이터 가공
$week_kr = ['일', '월', '화', '수', '목', '금', '토'];
$js_groups = array();

foreach ($groups as $group) {
    if (empty($group['list'])) continue;

    $group_list = array();
    foreach ($group['list'] as $row) {
        $row_data = array(
            'ru_idx'        => $row['ru_idx'],
            'mm_idx'        => $row['mm_idx'],
            'ru_resume_img' => getResumeImg($row),
            'book_yn'       => $row['book_yn'],
            'ru_work_img'   => getWorkingImg($row['ru_work']),
            'ru_name'       => $row['ru_name'],
            'mm_nation'     => $row['mm_nation'],
            'mm_age'        => getKoreanAge($row['mm_birth']),
            'ru_work_type'  => getWorkTypeFirst($row['ru_work_type']),
            'ad_addr'       => $row['ad_addr'],
            'ru_price_type' => getPriceTypeJSFirst($row['ru_price_type']),
            'ru_week_html'  => getWeekYoil($row['ru_week']),
        );
        $group_list[] = $row_data;
    }
    $js_groups[] = array('sent_date' => $group['sent_date'], 'list' => $group_list);
}
?>
<?= getCachCss(RES_PATH . "/css/resume_list.css") ?>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <h2 class="primary-title">A.I 매칭 리스트</h2>

      <?php if (!empty($js_groups)) : ?>
        <?php foreach ($js_groups as $idx => $group) :
          $ts = strtotime($group['sent_date']);
          $date_label = date('Y.n.j', $ts) . '(' . $week_kr[date('w', $ts)] . '요일)';
        ?>
          <h3 class="match-date-header" style="font-size: 16px; font-weight: 600; margin-top: 30px; margin-bottom: 15px; color: #333;">
            <span><?= $date_label ?></span>
          </h3>
          <div class="result-wrap">
            <!-- 목록 -->
            <ol class="result-list" id="ajaxList_<?= $idx ?>"></ol>
            <!-- //목록 -->
          </div>
        <?php endforeach; ?>

        <!-- 페이징 -->
        <?php if ($total_pages > 1) : ?>
          <div class="pagination-wrapper" style="display:flex;justify-content:center;align-items:center;gap:5px;margin:40px 0;padding:10px 0;">
            <?php if ($page > 1) : ?>
              <a href="?page=<?= $page - 1 ?>" class="btn-round btn-secondary" style="padding:8px 12px;border:1px solid #ddd;background:#fff;color:#333;border-radius:4px;text-decoration:none;">◀ 이전</a>
            <?php endif; ?>
            <span style="align-self:center;font-size:14px;"><?= $page ?> / <?= $total_pages ?></span>
            <?php if ($page < $total_pages) : ?>
              <a href="?page=<?= $page + 1 ?>" class="btn-round btn-secondary" style="padding:8px 12px;border:1px solid #ddd;background:#fff;color:#333;border-radius:4px;text-decoration:none;">다음 ▶</a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      <?php else : ?>
        <div class="is-empty_v2" style="background:#F4F4F4;padding:40px 20px;text-align:center;margin-top:40px;">
          <p>매칭된 인재가 없습니다.</p>
        </div>
      <?php endif; ?>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<?php
$tpl_id               = 'tpl_person_card';
$tpl_show_timestamp   = false;
$tpl_show_mm_idx      = false;
$tpl_show_action_slot = false;
$tpl_price_wrap_width = '290px';
include APPPATH . 'views/web/_layout/resume_list_template.php';
?>

<script>
  var matchGroups = <?= json_encode($js_groups, JSON_UNESCAPED_UNICODE) ?>;

  (function() {
    matchGroups.forEach(function(group, idx) {
      var $listEl = $("#ajaxList_" + idx);
      group.list.forEach(function(row) {
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
        html = html.replaceAll("{{ru_work_type}}", row.ru_work_type || "");
        html = html.replaceAll("{{ad_addr}}", row.ad_addr || "");
        html = html.replaceAll("{{ru_price_type}}", row.ru_price_type || "");
        html = html.replaceAll("{{ru_week}}", row.ru_week_html || "");
        html = html.replaceAll("{{timestamp}}", "");
        html = html.replaceAll("{{action_slot}}", "");
        $clone.html(html);
        $listEl.append($clone.html());
      });
    });
  })();

  // 인재 클릭 시 상세 페이지 열기
  $(document).on('click', '.desc', function() {
    var ruIdx = $(this).closest('.list-item').data('ru_idx');
    if (ruIdx) {
      var url = '/resume/hr_post/' + ruIdx;
      window.open(url, '_blank');
    }
  });
</script>
