<?php
/**
 * 인재 카드 파셜 템플릿 (PHP 서버사이드 렌더링)
 *
 * 사용 전 설정 변수:
 *   $row             - DB 데이터 배열 (ru_idx, ru_name, ru_week, mm_nation, mm_birth, ma_age,
 *                      ad_addr, ru_price_type, ru_work_type, ru_work, book_yn, rc_idx 등)
 *   $mm_type         - 회원 타입 문자열 (COMP이면 즐겨찾기 버튼 표시)
 *   $bk_type         - 즐겨찾기 타입 ('RECRUIT' 또는 'RESUME', 기본값 'RESUME')
 *   $show_job_link   - bool, 지원한 공고 링크 표시 여부 (기본값 false)
 *   $show_recomm_badge - bool, 추천 배지 표시 여부 (기본값 false)
 */

$_bk_type         = isset($bk_type) ? $bk_type : 'RESUME';
$_show_job_link   = isset($show_job_link) ? (bool)$show_job_link : false;
$_show_recomm     = isset($show_recomm_badge) ? (bool)$show_recomm_badge : false;

// 나이 표시: mm_birth가 있으면 getKoreanAge 사용, 없으면 ma_age 필드 사용
if (!empty($row['mm_birth'])) {
    $_age = getKoreanAge($row['mm_birth']);
} else {
    $_age = isset($row['ma_age']) ? $row['ma_age'] : '';
}
?>
<li class="list-item">
  <div class="content-li"
    data-ru_idx="<?= $row['ru_idx'] ?>"
    <?php if (!empty($row['mq_idx'])) : ?>data-mq_idx="<?= $row['mq_idx'] ?>"<?php endif; ?>
  >
    <div class="content-info">
      <div class="content-body">
        <div class="img">
          <img src="<?= getResumeImg($row) ?>">
          <?php if (isset($mm_type) && $mm_type === 'COMP') : ?>
            <button type="button"
              data-bk_type="<?= htmlspecialchars($_bk_type, ENT_QUOTES, 'UTF-8') ?>"
              data-bk_key="<?= $row['ru_idx'] ?>"
              class="btnBookMark btn-bookmark js-toggle-bookmark <?= ($row['book_yn'] == 'Y' ? 'on' : '') ?>">
              <span class="sr-text">즐겨찾기 추가</span>
            </button>
          <?php endif; ?>
          <?php if ($_show_recomm) : ?>
            <p class="recomm-badge"><span>추천</span></p>
          <?php endif; ?>
        </div>
        <p class="star-rating"><span class="star" style="width:100%;"></span><span class="sr-text">평점</span><span class="sr-text">5</span></p>
        <div class="desc">
          <div class="wicon"><?= getWorkingImg($row['ru_work']) ?></div>
          <div class="title ellipsis"><?= $row['ru_name'] ?></div>
          <div class="summary">
            <ul class="working-day"><?= getWeekYoil($row['ru_week']) ?></ul>
          </div>
          <div class="summary">
            <?php if (!empty($row['mm_nation'])) : ?>
              <p><?= $row['mm_nation'] ?></p>
            <?php endif; ?>
            <?php if ($_age !== '' && $_age != 0) : ?>
              <p><?= $_age ?>세</p>
            <?php endif; ?>
            <?php if (!empty($row['ad_addr'])) : ?>
              <p class="area-addr" data-addr="<?= htmlspecialchars($row['ad_addr'], ENT_QUOTES, 'UTF-8') ?>"><?= fRemoveBigAdr($row['ad_addr']) ?></p>
            <?php endif; ?>
          </div>
          <div class="summary2">
            <p class="pricetype"><?= getConvertResumePriceType($row['ru_price_type']) ?></p>
          </div>
          <div class="summary3">
            <p class="worktype"><?= getWorkType2025($row['ru_work_type']) ?></p>
          </div>
          <?php if ($_show_job_link && !empty($row['rc_idx'])) : ?>
            <a href="/recruit/job_post/<?= $row['rc_idx'] ?>" class="job-link">지원한 공고</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</li>
