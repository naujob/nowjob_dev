<?php
/**
 * 채용공고 카드 파셜 템플릿 (PHP 서버사이드 렌더링)
 *
 * 사용 전 설정 변수:
 *   $row                           - DB 데이터 배열
 *   $card_options['show_bookmark'] - bool, 즐겨찾기 버튼 표시 여부 (기본값 false)
 *   $card_options['use_js_addr']   - bool, JS로 주소 변환 처리 시 true (기본값 false)
 *   $card_options['use_mq_idx']    - bool, 지원공고 취소용 mq_idx 포함 여부 (기본값 false)
 *   $card_options['recomm_badge']  - bool, 추천 배지 표시 여부 (기본값 false)
 */
$_show_bookmark = isset($card_options['show_bookmark']) ? (bool)$card_options['show_bookmark'] : false;
$_use_js_addr   = isset($card_options['use_js_addr'])   ? (bool)$card_options['use_js_addr']   : false;
$_use_mq_idx    = isset($card_options['use_mq_idx'])    ? (bool)$card_options['use_mq_idx']    : false;
$_recomm_badge  = isset($card_options['recomm_badge'])  ? (bool)$card_options['recomm_badge']  : false;
?>
<li class="list-item">
  <div class="content-li" data-rc_idx="<?= $row['rc_idx'] ?>"
    <?= $_use_mq_idx && !empty($row['mq_idx']) ? 'data-mq_idx="' . $row['mq_idx'] . '"' : '' ?>>
    <div class="content-info">
      <div class="content-body">
        <div class="img">
          <img src="<?= getRecruitImg($row) ?>">
          <?php if ($_show_bookmark): ?>
            <button type="button" data-bk_type="RECRUIT" data-bk_key="<?= $row['rc_idx'] ?>"
              class="btnBookMark btn-bookmark js-toggle-bookmark <?= ($row['book_yn'] === 'Y' ? 'on' : '') ?>">
              <span class="sr-text">즐겨찾기 추가</span>
            </button>
          <?php endif; ?>
          <?php if ($_recomm_badge): ?>
            <p class="recomm-badge"><span>추천</span></p>
          <?php endif; ?>
        </div>
        <div class="desc">
          <div class="wicon"><?= getWorkingImg($row['rc_work']) ?></div>
          <div class="title ellipsis"><?= $row['rc_name'] ?></div>
          <div class="summary">
            <p><?= $row['rc_nation_nm'] ?></p>
            <p><?= $row['rc_age_nm'] ?></p>
          </div>
          <div class="summary area">
            <?php if ($_use_js_addr): ?>
              <p class="area-addr" data-addr="<?= htmlspecialchars($row['ad_addr'], ENT_QUOTES, 'UTF-8') ?>"><?= $row['ad_addr'] ?></p>
            <?php else: ?>
              <p><?= fRemoveBigAdr($row['ad_addr']) ?></p>
            <?php endif; ?>
          </div>
        </div>
        <div class="summary-container">
          <div class="summary3"><?= getConvertWorkType($row['rc_work_type']) ?></div>
          <div class="summary2">
            <p class="period"><?= getConvertPriceType($row['rc_price_type'], $row['rc_price_rep']) ?></p>
            <p class="amount"><?= getConvertPrice($row['rc_price_pay']) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</li>
