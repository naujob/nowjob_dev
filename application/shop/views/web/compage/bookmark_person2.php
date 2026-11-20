<?php
$card_list = formatResumeListRows($list, getTruncateLength());
?>
<?= getCachCss(RES_PATH . "/css/card_v2.css") ?>
<?= getCashJs(RES_PATH . "/js/card_v2.js") ?>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab3"; ?>
      <?php $tab_on2 = "menu2"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
      <div class="tab-panel on">
        <div class="recruit-bookmark">
          <h3 class="sr-text"><?= t('제안받은 인재') ?></h3>
          <div class="nc-head">
            <p class="nc-cnt"><span><?= t('제안받은 인재') ?> <b><?= count($card_list) ?></b><?= t('명') ?></span></p>
          </div>
          <ol class="nc-list" id="ajaxList">
            <?php include APPPATH . 'views/web/_partial/resume_list_card.php'; ?>
            <?php if (empty($card_list)) : ?><li class="list-state"><?= t('제안받은 인재가 없습니다.') ?></li><?php endif; ?>
          </ol>
        </div>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->
