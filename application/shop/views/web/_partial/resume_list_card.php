<?php
/**
 * PC 인재 카드 (card_v2.css) — 인재목록·통합검색·A.I 매칭(기업)·관심인재 공용
 * 인재목록 첫 화면과 resume_proc::search_list2025(render=html) 응답도 이 파일로 그린다
 *
 * 사용 전 설정 변수:
 *   $card_list   - formatResumeListRows() 결과
 *   $mm_type     - 회원 유형 (COMP만 즐겨찾기 하트)
 *   $card_select - 'single'(한 명 골라 제안) | 'multi'(여러 명 골라 제안 취소)
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
$card_is_comp = (isset($mm_type) && $mm_type === "COMP");
$card_select = isset($card_select) ? $card_select : '';
$card_days_all = array('월', '화', '수', '목', '금', '토', '일');
foreach ($card_list as $card) :
  $card_id = htmlspecialchars($card['ru_idx'], ENT_QUOTES, 'UTF-8');
  $card_name = htmlspecialchars($card['ru_name'], ENT_QUOTES, 'UTF-8');
  $card_is_reco = !empty($card['pm_idx']);
  $card_badges = isset($card['ru_work_badge']) ? $card['ru_work_badge'] : '';
  $card_wt = ncWorkTypes(isset($card['ru_work_type_raw']) ? $card['ru_work_type_raw'] : '');
  $card_types = array_values(array_filter(array_map('trim', explode(',', isset($card['ru_price_type_raw']) ? (string) $card['ru_price_type_raw'] : '')), 'strlen'));
  $card_nego = (empty($card_types) || count($card_types) > 2 || strpos(implode(',', $card_types), '협의') !== false);
  $card_week = array_map('trim', explode(',', isset($card['ru_week_raw']) ? (string) $card['ru_week_raw'] : ''));
  $card_meta = array();
  if (trim((string) $card['mm_nation']) !== '') $card_meta[] = t_list($card['mm_nation']);
  if (!empty($card['mm_age'])) $card_meta[] = $card['mm_age'] . t('대');
?>
<li class="nc-card<?= $card_is_reco ? ' is-reco' : '' ?>" data-ru_idx="<?= $card_id ?>" data-rc_idx="<?= htmlspecialchars(isset($card['rc_idx']) ? $card['rc_idx'] : '', ENT_QUOTES, 'UTF-8') ?>" data-href="<?= htmlspecialchars(lang_url('/resume/hr_post/' . $card['ru_idx']), ENT_QUOTES, 'UTF-8') ?>">
  <div class="nc-ph">
    <img src="<?= htmlspecialchars($card['ru_resume_img'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= $card_name ?> <?= t('프로필 이미지') ?>" loading="lazy">
    <?php if ($card_select) : ?>
      <label class="nc-chk"><input type="checkbox" name="chk[]" value="<?= $card_id ?>"><?= ncCheckSvg() ?><span class="sr-text"><?= t('선택') ?></span></label>
    <?php elseif ($card_is_comp) : ?>
      <button type="button" class="btnBookMark nc-heart<?= (isset($card['book_yn']) && $card['book_yn'] === 'Y') ? ' on' : '' ?>" data-bk_type="RESUME" data-bk_key="<?= $card_id ?>"><?= ncHeartSvg() ?><span class="sr-text"><?= t('즐겨찾기 추가') ?></span></button>
    <?php endif; ?>
  </div>
  <div class="nc-main">
    <p class="nc-when"><?php if ($card_is_reco) : ?><span class="nc-hot"><?= t('추천인재') ?></span><?php elseif (!empty($card['up_dt_timestamp'])) : ?><?= htmlspecialchars(t('등록일 :') . ' ' . $card['up_dt_timestamp'], ENT_QUOTES, 'UTF-8') ?><?php endif; ?></p>
    <div class="nc-badges"><?= $card_badges ?></div>
    <p class="nc-title"><?= $card_name ?></p>
    <p class="nc-meta"><?php foreach ($card_meta as $meta) : ?><span><?= htmlspecialchars($meta, ENT_QUOTES, 'UTF-8') ?></span><?php endforeach; ?></p>
  </div>
  <div class="nc-mid">
    <?php if (count($card_wt) === 2) : ?>
      <p class="nc-wt is-two"><em><?= htmlspecialchars($card_wt[0], ENT_QUOTES, 'UTF-8') ?></em><i></i><em><?= htmlspecialchars($card_wt[1], ENT_QUOTES, 'UTF-8') ?></em></p>
    <?php else : ?>
      <p class="nc-wt"><?= htmlspecialchars(implode(' ', $card_wt), ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <p class="nc-area"><?= htmlspecialchars($card['ad_addr'], ENT_QUOTES, 'UTF-8') ?></p>
    <div class="nc-pt is-mid">
      <?php if ($card_nego) : ?><span class="is-nego"><?= t('급여협의') ?></span>
      <?php else : foreach ($card_types as $type) : ?><span><?= htmlspecialchars(t($type), ENT_QUOTES, 'UTF-8') ?></span><?php endforeach; endif; ?>
    </div>
  </div>
  <ul class="nc-days">
    <?php foreach ($card_days_all as $day) : if (!in_array($day, $card_week)) continue; ?><li><?= htmlspecialchars(t($day), ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?>
  </ul>
</li>
<?php endforeach; ?>
