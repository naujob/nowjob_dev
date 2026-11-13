<?php
/**
 * PC 추천공고 사진 카드 파셜 (메인·채용목록·채용상세 공용, CSS는 nv_reco.css .rv-*)
 * 입력 $card_list = formatRecruitRecoRows() 결과
 * 카드 한 장은 keen-slider__slide로 감싼다 (job_list.php의 슬라이더 초기화 JS가 이 클래스를 센다)
 */
$card_list = (isset($card_list) && is_array($card_list)) ? $card_list : array();
$CI = &get_instance();
$card_bookmark = ($CI->user->getUserType() === "MEM");
$card_pin = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>';
$card_heart = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20.3s-7.5-4.6-7.5-10.1A4.3 4.3 0 0 1 12 7.6a4.3 4.3 0 0 1 7.5 2.6c0 5.5-7.5 10.1-7.5 10.1z" fill="none" stroke="#fff" stroke-width="1.9" stroke-linejoin="round"/></svg>';
foreach ($card_list as $card) :
  $card_id = htmlspecialchars($card['rc_idx'], ENT_QUOTES, 'UTF-8');
  // 공고명은 formatRecruitRecoRows()에서 중국어 번역·말줄임이 이미 적용된 값이다
  $card_name = htmlspecialchars($card['rc_name'], ENT_QUOTES, 'UTF-8');
  $card_url = lang_url('/recruit/job_post/' . $card_id);
  $card_negotiable = (strpos((string) $card['rc_price_type'], '협의') !== false);
  $card_nation = in_array($card['rc_nation_nm'], array('무관', '내국인,외국인')) ? t('국적무관') : t_list($card['rc_nation_nm']);
  $card_wtypes = array_values(array_filter(array_map('trim', explode(',', (string) $card['rc_work_type'])), 'strlen'));
  if (count($card_wtypes) > 2) {
    $card_wtypes = array(t('근무협의'));
  }
  // 근무형태 라벨도 중국어 사전으로 번역 (사전에 없으면 원문 유지)
  $card_wtypes = array_map('t', $card_wtypes);
  $card_ptypes = array_map('trim', explode(',', (string) $card['rc_price_type']));
  // 급여 유형(시급/월급 등)도 중국어 사전으로 번역 (사전에 없으면 원문 유지)
  $card_ptype = t(in_array($card['rc_price_rep'], $card_ptypes) ? $card['rc_price_rep'] : $card_ptypes[0]);
  $card_meta = array_merge(array($card_nation, $card['rc_age_nm']), $card_wtypes);
?>
<div class="keen-slider__slide">
<article class="rv-card">
  <div class="rv-photo"><img src="<?= htmlspecialchars($card['rt_recruit_img'], ENT_QUOTES, 'UTF-8') ?>" alt="" loading="lazy"></div>
  <?php if ($card_bookmark) : ?>
    <button type="button" class="rv-save btnBookMark <?= $card['book_yn'] === 'Y' ? 'on' : '' ?>" data-bk_type="RECRUIT" data-bk_key="<?= $card_id ?>" aria-label="<?= t('관심 공고 저장') ?>"><?= $card_heart ?></button>
  <?php endif; ?>
  <div class="rv-body">
    <div class="rv-top">
      <div class="rv-badges"><?= $card['rt_work_badge'] ?></div>
      <span class="rv-area"><?= $card_pin ?><?= htmlspecialchars($card['ad_addr'], ENT_QUOTES, 'UTF-8') ?></span>
    </div>
    <h3 class="rv-title"><a href="<?= $card_url ?>" target="_blank"><?= $card_name ?></a></h3>
    <ul class="rv-meta">
      <?php foreach ($card_meta as $meta) : if ($meta === '' || $meta === null) continue; ?>
        <li><?= htmlspecialchars($meta, ENT_QUOTES, 'UTF-8') ?></li>
      <?php endforeach; ?>
    </ul>
    <div class="rv-pay">
      <?php if ($card_negotiable) : ?>
        <span class="rv-pay-amt"><?= t('급여협의') ?></span>
      <?php else : ?>
        <span class="rv-pay-type"><?= htmlspecialchars(t($card_ptype), ENT_QUOTES, 'UTF-8') ?></span>
        <span class="rv-pay-amt"><?= number_format((float) $card['rc_price_pay']) ?><small><?= t('원') ?></small></span>
      <?php endif; ?>
    </div>
  </div>
  <a class="rv-cover" href="<?= $card_url ?>" target="_blank" aria-hidden="true" tabindex="-1"></a>
</article>
</div>
<?php endforeach; ?>
