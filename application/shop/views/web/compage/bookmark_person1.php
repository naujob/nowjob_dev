<?php
$card_list = formatResumeListRows($list, getTruncateLength());
$my_recruits = array();
if (isset($recruit_list) && is_array($recruit_list)) {
  foreach ($recruit_list as $row) {
    if (!empty($row["rc_idx"])) $my_recruits[] = $row;
  }
}
?>
<?= getCachCss(RES_PATH . "/css/card_v2.css") ?>
<?= getCashJs(RES_PATH . "/js/card_v2.js") ?>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab3"; ?>
      <?php $tab_on2 = "menu1"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
      <div class="tab-panel on">
        <div class="recruit-bookmark">
          <h3 class="sr-text"><?= t('즐겨찾기한 인재') ?></h3>
          <div class="nc-head">
            <p class="nc-cnt"><span><?= t('즐겨찾기한 인재') ?> <b><?= count($card_list) ?></b><?= t('명') ?></span></p>
          </div>
          <ol class="nc-list" id="ajaxList" data-select="single">
            <?php $card_select = 'single'; include APPPATH . 'views/web/_partial/resume_list_card.php'; ?>
            <?php if (empty($card_list)) : ?><li class="list-state"><?= t('즐겨찾기한 인재가 없습니다.') ?></li><?php endif; ?>
          </ol>
          <div class="nc-selbar is-empty" data-list="#ajaxList">
            <p class="nc-seln"></p>
            <button type="button" class="nc-clear"><?= t('선택 해제') ?></button>
            <button type="button" id="btnPropose" class="nc-go"><?= t('제안하기') ?></button>
          </div>
        </div>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<!-- 제안할 공고 고르기 -->
<div class="nc-dim" id="proposeDim">
  <div class="nc-md" role="dialog" aria-modal="true" aria-labelledby="proposeTitle">
    <h3 id="proposeTitle"><?= t('어떤 공고로 제안할까요?') ?></h3>
    <p class="nc-md-sub"><b id="proposeName"></b> <?= t('인재에게 이 공고로 제안해요.') ?></p>
    <?php if (!empty($my_recruits)) : ?>
      <?php foreach ($my_recruits as $i => $row) : ?>
        <label class="nc-rc<?= $i === 0 ? ' is-on' : '' ?>">
          <input type="radio" name="propose_rc" value="<?= (int) $row["rc_idx"] ?>"<?= $i === 0 ? ' checked' : '' ?>>
          <i></i>
          <span><b><?= htmlspecialchars($row["rc_name"], ENT_QUOTES, 'UTF-8') ?></b><small><?= get_yyyymmdd($row["rc_sdate"]) ?> ~ <?= get_yyyymmdd($row["rc_edate"]) ?></small></span>
        </label>
      <?php endforeach; ?>
    <?php else : ?>
      <p class="nc-empty"><?= t('등록된 채용공고가 없습니다.') ?></p>
    <?php endif; ?>
    <p class="nc-pinfo"><?= t('하루 무료 제안 2회를 쓴 뒤에는 포인트가 차감돼요.') ?></p>
    <div class="nc-md-btns">
      <button type="button" class="nc-md-no"><?= t('취소') ?></button>
      <button type="button" class="nc-md-yes"<?= empty($my_recruits) ? ' disabled' : '' ?>><?= t('제안하기') ?></button>
    </div>
  </div>
</div>

<script>
  /* 제안 직후 자동 인사 메시지를 저장한 뒤 제안보낸 인재로 이동 (인재상세와 같은 방식) */
  var goAfterGreeting = function(rmIdx, messageText, url, toastMsg) {
    var moved = false;
    var go = function() {
      if (moved) return;
      moved = true;
      try {
        sessionStorage.setItem('pendingToastMsg', toastMsg);
      } catch (e) {}
      location.href = url;
    };
    // 소켓을 연결하지 않는 화면이면 인사 메시지 없이 바로 이동 (3초 대기 방지)
    if (!rmIdx || typeof socket === 'undefined' || !socket || window.__SOCKET_ENABLED === false) {
      go();
      return;
    }
    socket.once("message_insert_return", go);
    setTimeout(go, 3000);
    var userId = $("#user_id").val();
    var currentDate = typeof getCurrentDate === 'function' ? getCurrentDate() : new Date().toISOString();
    socket.emit("message_insert", {
      rm_idx: Number(rmIdx),
      user_id: userId,
      chat_type: "recruit",
      mg_type: "text",
      mg_text: messageText,
      mg_req_dt: "",
      mg_img: "",
      mg_file: "",
      mg_imoti: "",
      mg_dt: currentDate
    }, [{
      user_id: userId,
      user_nick: ""
    }]);
  };

  $(document).on('click', '.nc-rc', function() {
    $('.nc-rc').removeClass('is-on');
    $(this).addClass('is-on').find('input').prop('checked', true);
  });

  $("#btnPropose").click(function() {
    var $on = ncCard.picked("#ajaxList");
    if (!$on.length) {
      commonAlert(t("제안하실 인재를 선택해주세요."), function() {});
      return;
    }
    $("#proposeName").text($on.first().find('.nc-title').text());
    $("#proposeDim").addClass('is-show');
  });

  $("#proposeDim .nc-md-no").click(function() {
    $("#proposeDim").removeClass('is-show');
  });

  $("#proposeDim .nc-md-yes").click(function() {
    var ru_idx = ncCard.picked("#ajaxList").first().data('ru_idx');
    var rc_idx = $("[name='propose_rc']:checked").val();
    if (!rc_idx) {
      commonAlert(t("제안하실 공고를 선택해주세요."), function() {});
      return;
    }
    $("#proposeDim").removeClass('is-show');

    // 오늘 같은 공고로 제안했는지 확인 → 포인트 확인 → 제안
    comAjax("/resume_proc/todayapply", { ru_idx: ru_idx, rc_idx: rc_idx }, function(ret) {
      if (ret.code != "00") {
        commonAlert(ret.msg, function() {});
        return;
      }
      var params = {};
      params["mq_idxs"] = [ru_idx];
      params["rc_idx"] = rc_idx;
      params["mq_type"] = "RESUME";
      comAjax("/common_proc/member_request_check", params, function(ret_1) {
        if (ret_1.code != "00") {
          commonAlert(ret_1.msg, function() {});
          return;
        }
        var add_text = (ret_1.data.point_yn == "Y" ? "<br/>" + ret_1.data.point_price + t("P 포인트가 차감됩니다.") : "");
        openPopup('confirm', {
          content: '<em>' + t('해당공고') + '</em><br> ' + t('로 제안을 보내시겠습니까?') + add_text,
          secondCallback: function() {
            comAjax("/common_proc/member_request", params, function(ret_2) {
              if (ret_2.code == "00") {
                goAfterGreeting(ret_2.data ? ret_2.data.rm_idx : null, "안녕하세요.\n채용중인 일자리를 제안드립니다.", "/compage/bookmark_person3", t("제안이 완료되었습니다."));
              } else {
                commonAlert(ret_2.msg, function() {});
              }
            });
          }
        });
      });
    });
  });
</script>
