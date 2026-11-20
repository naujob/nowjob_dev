<?php
/**
 * PC 채용공고 카드 (card_v2.css) — 채용목록·추천기업·통합검색·A.I 매칭·관심공고·지원한 공고 공용
 * 채용목록 첫 화면과 recruit_proc 목록 AJAX(render=html) 응답도 이 파일로 그린다
 *
 * 사용 전 설정 변수:
 *   $card_list   - formatRecruitListRows() 결과
 *   $mm_type     - 회원 유형 (MEM만 즐겨찾기 하트)
 *   $card_select - 'multi'이면 사진 위 체크로 고르는 화면 (관심공고·지원한 공고)
 *   $card_value  - 체크 값 필드 (기본 rc_idx, 지원한 공고는 mq_idx)
 */
if (!function_exists('ncWorkTypes')) {
  // 근무형태 표시: 2개까지, 3개 이상이면 근무협의
  function ncWorkTypes($raw)
  {
    $arr = array_values(array_filter(array_map('trim', explode(',', (string) $raw)), 'strlen'));
    if (count($arr) > 2) return array(t('근무협의'));
    return array_map('t', $arr);
  }
}
if (!function_exists('ncCheckSvg')) {
  function ncCheckSvg()
  {
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>';
  }
}
if (!function_exists('ncHeartSvg')) {
  function ncHeartSvg()
  {
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20s-7.5-4.6-7.5-10.2A4.3 4.3 0 0 1 12 7.3a4.3 4.3 0 0 1 7.5 2.5C19.5 15.4 12 20 12 20z"/></svg>';
  }
}

$card_list = (isset($card_list) && is_array($card_list)) ? $card_list : array();
$card_is_mem = (isset($mm_type) && $mm_type === "MEM");
$card_select = isset($card_select) ? $card_select : '';
$card_value = isset($card_value) ? $card_value : 'rc_idx';
foreach ($card_list as $card) :
  $card_id = htmlspecialchars($card['rc_idx'], ENT_QUOTES, 'UTF-8');
  $card_name = htmlspecialchars($card['rc_name'], ENT_QUOTES, 'UTF-8');
  $card_is_reco = !empty($card['pm_idx']);
  $card_badges = isset($card['rt_work_badge']) ? $card['rt_work_badge'] : '';
  $card_wt = ncWorkTypes(isset($card['rc_work_type']) ? $card['rc_work_type'] : '');
  $card_nego = (strpos((string) $card['rc_price_type'], '협의') !== false);
  $card_types = array_values(array_filter(array_map('trim', explode(',', (string) $card['rc_price_type'])), 'strlen'));
  $card_rep = (isset($card['rc_price_rep']) && in_array($card['rc_price_rep'], $card_types)) ? $card['rc_price_rep'] : end($card_types);
?>
<li class="nc-card<?= $card_is_reco ? ' is-reco' : '' ?>" data-rc_idx="<?= $card_id ?>" data-href="<?= htmlspecialchars(lang_url('/recruit/job_post/' . $card['rc_idx']), ENT_QUOTES, 'UTF-8') ?>">
  <div class="nc-ph">
    <img src="<?= htmlspecialchars($card['rt_recruit_img'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= $card_name ?> <?= t('공고 이미지') ?>" loading="lazy">
    <?php if ($card_select) : ?>
      <label class="nc-chk"><input type="checkbox" name="chk[]" value="<?= htmlspecialchars($card[$card_value], ENT_QUOTES, 'UTF-8') ?>"><?= ncCheckSvg() ?><span class="sr-text"><?= t('선택') ?></span></label>
    <?php elseif ($card_is_mem) : ?>
      <button type="button" class="btnBookMark nc-heart<?= (isset($card['book_yn']) && $card['book_yn'] === 'Y') ? ' on' : '' ?>" data-bk_type="RECRUIT" data-bk_key="<?= $card_id ?>"><?= ncHeartSvg() ?><span class="sr-text"><?= t('즐겨찾기 추가') ?></span></button>
    <?php endif; ?>
  </div>
  <div class="nc-main">
    <p class="nc-when"><?php if ($card_is_reco) : ?><span class="nc-hot"><?= t('추천공고') ?></span><?php else : ?><?= htmlspecialchars(t('등록일 :') . ' ' . $card['up_dt'], ENT_QUOTES, 'UTF-8') ?><?php endif; ?></p>
    <div class="nc-badges"><?= $card_badges ?></div>
    <p class="nc-title"><?= $card_name ?></p>
    <p class="nc-meta"><span><?= htmlspecialchars(t_list($card['rc_nation_nm']), ENT_QUOTES, 'UTF-8') ?></span><span><?= htmlspecialchars($card['rc_age_nm'], ENT_QUOTES, 'UTF-8') ?></span></p>
  </div>
  <div class="nc-mid">
    <?php if (count($card_wt) === 2) : ?>
      <p class="nc-wt is-two"><em><?= htmlspecialchars($card_wt[0], ENT_QUOTES, 'UTF-8') ?></em><i></i><em><?= htmlspecialchars($card_wt[1], ENT_QUOTES, 'UTF-8') ?></em></p>
    <?php else : ?>
      <p class="nc-wt"><?= htmlspecialchars(implode(' ', $card_wt), ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <p class="nc-area"><?= htmlspecialchars($card['ad_addr'], ENT_QUOTES, 'UTF-8') ?></p>
  </div>
  <div class="nc-pay">
    <?php if ($card_nego || empty($card_types)) : ?>
      <p class="nc-amt is-nego"><?= t('급여협의') ?></p>
    <?php else : ?>
      <div class="nc-pt"><?php foreach ($card_types as $type) : if ($type === $card_rep) continue; ?><span class="is-sub"><?= htmlspecialchars(t($type), ENT_QUOTES, 'UTF-8') ?></span><?php endforeach; ?><span><?= htmlspecialchars(t($card_rep), ENT_QUOTES, 'UTF-8') ?></span></div>
      <p class="nc-amt"><?= number_format((float) $card['rc_price_pay']) ?><small><?= t('원') ?></small></p>
    <?php endif; ?>
  </div>
</li>
<?php endforeach; ?>
