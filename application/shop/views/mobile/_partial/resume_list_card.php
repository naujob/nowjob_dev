<?php
/**
 * 인재목록(hr_list) 인재 카드 목록 — 서버 렌더링 전용 파셜
 * hr_list.php 첫 화면과 resume_proc::search_list2025(render=html) AJAX 응답이 이 파일 하나로 같은 카드를 그린다
 *
 * 사용 전 설정 변수:
 *   $card_list - formatResumeListRows() + member_model->addResumeChatBadgeInfo()를 거친 인재 목록
 *
 * 인재·회원이 입력한 값(이름/국적/주소/채팅 속성)은 이스케이프하고,
 * ru_work_img / ru_week / ru_work_type / ru_price_type / ru_recom_badge 는 서버 헬퍼가 만든 HTML이라 그대로 출력한다
 */
$card_list = (isset($card_list) && is_array($card_list)) ? $card_list : array();
foreach ($card_list as $row) :
  $card_is_recomm = !empty($row['pm_idx']);
  $card_chat_rm_idx = isset($row['chat_rm_idx']) ? $row['chat_rm_idx'] : '';
  $card_chat_type = isset($row['chat_type']) ? $row['chat_type'] : '';
?>
<li class="list-item">
  <div class="content-li<?= $card_is_recomm ? ' recomm' : '' ?>"
       data-ru_idx="<?= htmlspecialchars($row['ru_idx'], ENT_QUOTES, 'UTF-8') ?>"
       data-mm_idx="<?= htmlspecialchars($row['mm_idx'], ENT_QUOTES, 'UTF-8') ?>">
    <div class="content-info">
      <div class="content-body">
        <div class="desc">
          <div class="wicon">
            <?= $row['ru_recom_badge'] ?>
            <?= $row['ru_work_img'] ?>
          </div>
          <div class="title ellipsis"><?= htmlspecialchars($row['ru_name'], ENT_QUOTES, 'UTF-8') ?></div>
          <div class="summary">
            <ul class="working-day"><?= $row['ru_week'] ?></ul>
          </div>
          <div class="summary-with-chat">
            <div class="summary">
              <p><?= htmlspecialchars($row['mm_nation'], ENT_QUOTES, 'UTF-8') ?></p>
              <p><?= ($row['mm_age'] > 0) ? htmlspecialchars($row['mm_age'], ENT_QUOTES, 'UTF-8') . '대' : '' ?></p>
              <p><?= htmlspecialchars($row['ad_addr'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="chat_mark" data-visible="<?= !empty($card_chat_rm_idx) ? 'true' : 'false' ?>" data-chat_type="<?= htmlspecialchars($card_chat_type, ENT_QUOTES, 'UTF-8') ?>" data-rm_idx="<?= htmlspecialchars($card_chat_rm_idx, ENT_QUOTES, 'UTF-8') ?>"></div>
          </div>
          <div class="summary3">
            <p class="worktype"><?= $row['ru_work_type'] ?></p>
          </div>
          <div class="summary2"><p class="pricetype"><?= $row['ru_price_type'] ?></p></div>
        </div>
      </div>
    </div>
  </div>
</li>
<?php endforeach; ?>
