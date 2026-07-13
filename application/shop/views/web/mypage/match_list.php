<?php
// JS 렌더링용 데이터 전처리
$week_kr = ['일', '월', '화', '수', '목', '금', '토'];
$js_groups = array();

foreach ($groups as $group) {
    if (empty($group['list'])) continue;

    $group_list = array();
    foreach ($group['list'] as $row) {
        $group_list[] = array(
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
    $js_groups[] = array('sent_date' => $group['sent_date'], 'list' => $group_list);
}
?>
<link rel="stylesheet" href="<?= RES_PATH ?>/css/recruit_list.css">
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
            <ol class="result-list" id="ajaxList_<?= $idx ?>"></ol>
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
          <p>매칭된 공고가 없습니다.</p>
        </div>
      <?php endif; ?>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<?php
$tpl_show_timestamp    = false;
$tpl_show_recomm_badge = false;
$tpl_show_selection    = false;
include_once(get_base_url() . '/application/shop/views/web/_layout/recruit_list_template.php');
?>

<script>
  var matchGroups = <?= json_encode($js_groups) ?>;

  (function() {
    matchGroups.forEach(function(group, idx) {
      var $listEl = $("#ajaxList_" + idx);
      group.list.forEach(function(row) {
        var $clone = $("#tpl_list").clone();
        var content = $clone.html();
        content = content.split("{{rc_idx}}").join(row.rc_idx);
        content = content.split("{{rt_recruit_img}}").join(row.rt_recruit_img);
        content = content.split("{{book_yn}}").join(row.book_yn);
        content = content.split("{{rt_work_img}}").join(row.rt_work_img);
        content = content.split("{{rc_name}}").join(row.rc_name);
        content = content.split("{{rc_nation_nm}}").join(row.rc_nation_nm == '무관' ? '국적무관' : row.rc_nation_nm);
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
    });
  })();

  // 공고 클릭 시 상세 페이지 열기
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.list-item').data('rc_idx');
    if (rcIdx) {
      window.open('/recruit/job_post/' + rcIdx, '_blank');
    }
  });
</script>
