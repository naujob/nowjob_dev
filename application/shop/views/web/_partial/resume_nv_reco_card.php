<?php
/**
 * PC 추천인재 카드 파셜 (nv-people2 그리드, nv_reco.css .nv-person2)
 * 첫 화면(hr_list 서버 렌더)과 AJAX(search_reco_list2025 render=nv)가 같이 쓴다
 *
 * 입력 $card_list = formatResumeRecoRows() 결과
 * 최대 장수 자르기는 호출하는 쪽에서 한다 (hr_list는 8장)
 */
$card_list = (isset($card_list) && is_array($card_list)) ? $card_list : array();
$card_pin = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>';
foreach ($card_list as $card) :
  $card_id = htmlspecialchars($card['ru_idx'], ENT_QUOTES, 'UTF-8');
  $card_url = '/resume/hr_post/' . $card_id;
  $card_name = htmlspecialchars($card['ru_name'], ENT_QUOTES, 'UTF-8');
  // 주소는 앞 두 단어만 (시/군/구 까지)
  $card_addr_parts = preg_split('/\s+/', trim((string) $card['ad_addr']), -1, PREG_SPLIT_NO_EMPTY);
  $card_area = htmlspecialchars(implode(' ', array_slice($card_addr_parts, 0, 2)), ENT_QUOTES, 'UTF-8');
?>
<article class="nv-card nv-person2">
  <div class="nv-pz"><div class="nv-avatar"><img src="<?= htmlspecialchars($card['ru_resume_img'], ENT_QUOTES, 'UTF-8') ?>" alt=""></div></div>
  <div class="nv-job-body">
    <div class="nv-job-top"><div class="nv-badges"><?= $card['ru_work_badge'] ?></div><span class="nv-area"><?= $card_pin ?><?= $card_area ?></span></div>
    <h3 class="nv-job-title"><a href="<?= $card_url ?>" target="_blank"><?= $card_name ?></a></h3>
    <ul class="nv-meta"><li><?= htmlspecialchars(t($card['mm_nation']), ENT_QUOTES, 'UTF-8') ?></li><li><?= htmlspecialchars($card['mm_age'], ENT_QUOTES, 'UTF-8') ?><?= htmlspecialchars(t('대'), ENT_QUOTES, 'UTF-8') ?></li></ul>
    <div class="nv-pay"><span class="nv-pay-type"><?= $card['ru_work_type'] ?></span><span class="nv-pay-amt"><?= $card['ru_price_type'] ?></span></div>
  </div>
  <a class="nv-cover" href="<?= $card_url ?>" target="_blank" aria-hidden="true" tabindex="-1"></a>
</article>
<?php endforeach; ?>
