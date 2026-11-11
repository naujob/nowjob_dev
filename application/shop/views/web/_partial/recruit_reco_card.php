<?php
/**
 * PC 추천공고 슬라이더 카드 파셜 (서버 렌더링 전용)
 * web/recruit/job_list.php 첫 화면에서 쓴다. 입력 $card_list = recruit.php::_formatRecruitRecoList() 결과
 * 마크업은 web/_layout/reco_card_template.php(tpl_reco_list, 북마크·해시태그 없음)와 같다
 */
$card_list = (isset($card_list) && is_array($card_list)) ? $card_list : array();
foreach ($card_list as $card) :
  $card_negotiable = (strpos((string) $card['rc_price_type'], '협의') !== false);
  $card_period = $card_negotiable ? '<em> </em>' : $card['rt_price_type'];
  $card_amount = $card_negotiable ? t('급여협의') : number_format((float) $card['rc_price_pay']) . t('원');
  $card_icons = trimCategoryLabelImg($card['rt_work_img'], 2);
  $card_id = htmlspecialchars($card['rc_idx'], ENT_QUOTES, 'UTF-8');
  $card_name = htmlspecialchars($card['rc_name'], ENT_QUOTES, 'UTF-8');
?>
<div class="keen-slider__slide">
  <div class="recruit-card" data-rc_idx="<?= $card_id ?>">
    <div class="thumb-wrap">
      <div class="thumb">
        <a href="<?= lang_url('/recruit/job_post/' . $card_id) ?>" target="_blank">
          <img src="<?= htmlspecialchars($card['rt_recruit_img'], ENT_QUOTES, 'UTF-8') ?>" alt="">
        </a>
      </div>
    </div>
    <div class="content-wrap">
      <div class="inline-group">
        <?= $card_icons ?>
        <p class="area"><?= htmlspecialchars($card['ad_addr'], ENT_QUOTES, 'UTF-8') ?></p>
      </div>
      <p class="title">
        <strong>
          <a href="<?= lang_url('/recruit/job_post/' . $card_id) ?>" target="_blank"><?= $card_name ?></a>
        </strong>
      </p>
      <div class="card-bottom-wrap">
        <div class="card-meta-group">
          <div class="meta">
            <p><?= htmlspecialchars(t_list($card['rc_nation_nm']), ENT_QUOTES, 'UTF-8') ?></p>
            <p><?= htmlspecialchars($card['rc_age_nm'], ENT_QUOTES, 'UTF-8') ?></p>
          </div>
          <div class="summary wtype"><?= $card['rt_work_type'] ?></div>
        </div>
        <div class="price-wrap">
          <p class="period"><?= $card_period ?></p>
          <p class="amount"><?= htmlspecialchars($card_amount, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>
