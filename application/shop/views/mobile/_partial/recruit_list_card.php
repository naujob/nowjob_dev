<?php
/**
 * 채용목록(job_list) 공고 카드 목록 — 서버 렌더링 전용 파셜
 * job_list.php 첫 화면과 recruit_proc::getRecruitList(render=html) AJAX 응답이 이 파일 하나로 같은 카드를 그린다
 *
 * 사용 전 설정 변수:
 *   $card_list - formatRecruitListRows() + recruitModel::addChatBadgeInfo()를 거친 공고 목록
 *
 * 기업이 입력한 값(공고명/주소 등)은 이스케이프하고, rt_* 값은 서버 헬퍼가 만든 HTML이라 그대로 출력한다
 */
$card_list = (isset($card_list) && is_array($card_list)) ? $card_list : array();
foreach ($card_list as $row) :
  $card_addr = isset($row['ad_addr']) ? $row['ad_addr'] : '';
  $card_addr = (mb_strlen($card_addr) > 20) ? mb_substr($card_addr, 0, 20) . '...' : $card_addr;
  $card_chat_rm_idx = isset($row['chat_rm_idx']) ? $row['chat_rm_idx'] : '';
?>
<li class="list-item">
  <div class="content-li<?= !empty($row['pm_idx']) ? ' recomm' : '' ?>" data-rc_idx="<?= htmlspecialchars($row['rc_idx'], ENT_QUOTES, 'UTF-8') ?>">
    <div class="content-info">
      <div class="content-body">
        <div class="desc">
          <div class="wicon">
            <?= $row['rt_recom_badge'] ?>
            <?= $row['rt_work_img'] ?>
          </div>
          <div class="title ellipsis"><?= htmlspecialchars($row['rc_name'], ENT_QUOTES, 'UTF-8') ?></div>
          <div class="summary">
            <p><?= htmlspecialchars($row['rc_nation_nm'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><?= htmlspecialchars($row['rc_age_nm'], ENT_QUOTES, 'UTF-8') ?></p>
          </div>
          <div class="summary-with-chat">
            <div class="summary area">
              <p><?= htmlspecialchars($card_addr, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <?php if (!empty($card_chat_rm_idx)) : ?>
            <div class="chat_mark" data-visible="true" data-chat_type="<?= htmlspecialchars($row['chat_type'], ENT_QUOTES, 'UTF-8') ?>" data-rm_idx="<?= htmlspecialchars($card_chat_rm_idx, ENT_QUOTES, 'UTF-8') ?>"></div>
            <?php endif; ?>
          </div>
        </div>
        <div class="summary-container">
          <div class="summary3"><?= $row['rt_work_type'] ?></div>
          <div class="summary2">
            <p class="period"><?= $row['rt_price_type'] ?></p>
            <p class="amount"><?= $row['rt_price_pay'] ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</li>
<?php endforeach; ?>
