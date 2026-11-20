<?php
$card_list = formatResumeListRows($list, getTruncateLength());
?>
<?= getCachCss(RES_PATH . "/css/card_v2.css") ?>
<?= getCashJs(RES_PATH . "/js/card_v2.js") ?>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab3"; ?>
      <?php $tab_on2 = "menu3"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
      <div class="tab-panel on">
        <div class="recruit-bookmark">
          <h3 class="sr-text"><?= t('제안보낸 인재') ?></h3>
          <div class="nc-head">
            <p class="nc-cnt"><span><?= t('제안보낸 인재') ?> <b><?= count($card_list) ?></b><?= t('명') ?></span></p>
            <?php if (!empty($card_list)) : ?><button type="button" class="nc-all" data-list="#ajaxList"><i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></i><?= t('전체 선택') ?></button><?php endif; ?>
          </div>
          <ol class="nc-list" id="ajaxList" data-select="multi">
            <?php $card_select = 'multi'; include APPPATH . 'views/web/_partial/resume_list_card.php'; ?>
            <?php if (empty($card_list)) : ?><li class="list-state"><?= t('제안보낸 인재가 없습니다.') ?></li><?php endif; ?>
          </ol>
          <div class="nc-selbar is-empty" data-list="#ajaxList" data-unit="<?= t('명') ?>">
            <p class="nc-seln"></p>
            <button type="button" class="nc-clear"><?= t('선택 해제') ?></button>
            <button type="button" id="btnCancelPropose" class="nc-go is-danger"><?= t('제안 취소') ?></button>
          </div>
        </div>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<script>
  /* 고른 인재들의 제안을 차례로 취소 */
  $("#btnCancelPropose").click(function() {
    var $on = ncCard.picked("#ajaxList");
    if (!$on.length) {
      commonAlert(t("취소하실 인재를 선택해주세요."), function() {});
      return;
    }
    openPopup('confirm', {
      content: '<em>' + $on.length + t('명') + '</em> ' + t('에게 보낸 제안을 취소하시겠습니까?'),
      secondCallback: function() {
        var items = $on.map(function() {
          return { ru_idx: $(this).data('ru_idx'), rc_idx: $(this).data('rc_idx') };
        }).get();
        var fail = 0;
        var next = function(i) {
          if (i >= items.length) {
            commonAlert(fail ? t("일부 제안을 취소하지 못했습니다.") : t("제안 취소 되었습니다."), function() {
              location.reload();
            });
            return;
          }
          comAjax("/compage_proc/resume_propose_cancle", items[i], function(ret) {
            if (ret.code != "00") fail++;
            next(i + 1);
          });
        };
        next(0);
      }
    });
  });
</script>
