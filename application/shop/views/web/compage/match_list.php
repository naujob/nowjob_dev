<?php
$week_kr = [t('일'), t('월'), t('화'), t('수'), t('목'), t('금'), t('토')];
$match_groups = array();
foreach ($groups as $group) {
    if (empty($group['list'])) continue;
    $match_groups[] = $group;
}
?>
<?= getCachCss(RES_PATH . "/css/card_v2.css") ?>
<?= getCashJs(RES_PATH . "/js/card_v2.js") ?>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <h2 class="primary-title"><?= t('A.I 매칭 리스트') ?></h2>

      <?php if (!empty($match_groups)) : ?>
        <?php foreach ($match_groups as $group) :
          $ts = strtotime($group['sent_date']);
          $date_label = date('Y.n.j', $ts) . '(' . $week_kr[date('w', $ts)] . t('요일') . ')';
        ?>
          <h3 class="nc-date"><?= $date_label ?></h3>
          <ol class="nc-list">
            <?php $card_list = formatResumeListRows($group['list'], getTruncateLength()); include APPPATH . 'views/web/_partial/resume_list_card.php'; ?>
          </ol>
        <?php endforeach; ?>

        <!-- 페이징 -->
        <?php if ($total_pages > 1) : ?>
          <div class="pagination-wrapper" style="display:flex;justify-content:center;align-items:center;gap:5px;margin:40px 0;padding:10px 0;">
            <?php if ($page > 1) : ?>
              <a href="?page=<?= $page - 1 ?>" class="btn-round btn-secondary" style="padding:8px 12px;border:1px solid #ddd;background:#fff;color:#333;border-radius:4px;text-decoration:none;">◀ <?= t('이전') ?></a>
            <?php endif; ?>
            <span style="align-self:center;font-size:14px;"><?= $page ?> / <?= $total_pages ?></span>
            <?php if ($page < $total_pages) : ?>
              <a href="?page=<?= $page + 1 ?>" class="btn-round btn-secondary" style="padding:8px 12px;border:1px solid #ddd;background:#fff;color:#333;border-radius:4px;text-decoration:none;"><?= t('다음') ?> ▶</a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      <?php else : ?>
        <div class="is-empty_v2" style="background:#F4F4F4;padding:40px 20px;text-align:center;margin-top:40px;">
          <p><?= t('매칭된 인재가 없습니다.') ?></p>
        </div>
      <?php endif; ?>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->
