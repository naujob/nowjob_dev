<?php
$card_list = formatRecruitListRows($list, getTruncateLength());
?>
<?= getCachCss(RES_PATH . "/css/card_v2.css") ?>
<?= getCashJs(RES_PATH . "/js/card_v2.js") ?>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab3"; ?>
      <?php $tab_on2 = "menu2"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/mypage/top_line_2025.php'); ?>
      <div class="tab-panel on">
        <div class="recruit-bookmark">
          <h3 class="sr-text"><?= t('지원한 공고') ?></h3>
          <div class="nc-head">
            <p class="nc-cnt"><span><?= t('지원한 공고') ?> <b><?= count($card_list) ?></b><?= t('건') ?></span></p>
            <?php if (!empty($card_list)) : ?><button type="button" class="nc-all" data-list="#ajaxList"><i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></i><?= t('전체 선택') ?></button><?php endif; ?>
          </div>
          <ol class="nc-list" id="ajaxList" data-select="multi">
            <?php $card_select = 'multi'; $card_value = 'mq_idx'; include APPPATH . 'views/web/_partial/recruit_list_card.php'; ?>
            <?php if (empty($card_list)) : ?><li class="list-state"><?= t('지원한 공고가 없습니다.') ?></li><?php endif; ?>
          </ol>
          <div class="nc-selbar is-empty" data-list="#ajaxList" data-unit="<?= t('건') ?>">
            <p class="nc-seln"></p>
            <button type="button" class="nc-clear"><?= t('선택 해제') ?></button>
            <button type="button" id="btnCancel" class="nc-go is-danger"><?= t('취소하기') ?></button>
          </div>
        </div>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<script>
  /* 취소하기 버튼 */
  $("#btnCancel").click(function() {
    var mq_idxs = new Array();
    for (var icnt = 0; icnt < $("[name='chk[]']:checked").length; icnt++) {
      mq_idxs.push($("[name='chk[]']:checked").eq(icnt).val());
    }
    if (mq_idxs.length == 0) {
      commonAlert(t("취소하실 공고를 선택해주세요."), function() {});
      return;
    }
    openPopup('confirm', {
      content: '<em>' + t('선택한') + '</em> ' + t('공고를') + '<br> ' + t('취소하시겠습니까?'),
      secondCallback: function() {
        var params = {};
        params["proc_type"] = "member_request";
        params["mq_idxs"] = mq_idxs;
        comAjax("/common_proc/request_cancel", params, function(ret) {
          if (ret.code == "00") {
            commonAlert(t("취소 되었습니다."), function() {
              location.reload();
            });
          } else {
            commonAlert(ret.msg, function() {});
            return;
          }
        });
      }
    });
  });
</script>
