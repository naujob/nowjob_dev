<?php
/**
 * PC 추천인재 슬라이더 카드 파셜 (서버 렌더링 전용)
 * web/resume/hr_list.php 첫 화면에서 쓴다. 입력 $card_list = resume.php::_formatRecoList() 결과
 * 마크업은 web/_layout/reco_list_template.php(hr_list 옵션: 북마크·mm_idx 표시)와 같다
 *
 * 사용 전 설정 변수:
 *   $card_list - 추천인재 포맷 결과
 *   $mm_type   - 회원 유형 (COMP만 북마크 버튼 표시)
 */
$card_list = (isset($card_list) && is_array($card_list)) ? $card_list : array();
$card_is_comp = (isset($mm_type) && $mm_type === "COMP");
foreach ($card_list as $card) :
  $card_id = htmlspecialchars($card['ru_idx'], ENT_QUOTES, 'UTF-8');
  $card_name = htmlspecialchars($card['ru_name'], ENT_QUOTES, 'UTF-8');
  $card_nation = trim((string) $card['mm_nation']);
?>
<div class="keen-slider__slide">
  <div class="person-card reco-card" data-ru_idx="<?= $card_id ?>" data-mm_idx="<?= htmlspecialchars($card['mm_idx'], ENT_QUOTES, 'UTF-8') ?>">
    <div class="thumb-wrap">
      <div class="img">
        <img src="<?= htmlspecialchars($card['ru_resume_img'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= $card_name ?> 프로필 이미지">
        <?php if ($card_is_comp) : ?>
          <button type="button" data-bk_type="RESUME" data-bk_key="<?= $card_id ?>"
            class="btnBookMark btn-bookmark js-toggle-bookmark<?= (isset($card['book_yn']) && $card['book_yn'] === 'Y') ? ' on' : '' ?>">
            <span class="sr-text">즐겨찾기 추가</span>
          </button>
        <?php endif; ?>
      </div>
    </div>
    <div class="desc">
      <div class="content-wrap">
        <p class="category"><?= isset($card['ru_work_badge']) ? $card['ru_work_badge'] : $card['ru_work_img'] ?></p>
        <div class="title-wrap">
          <p class="title"><strong><?= $card_name ?></strong></p>
        </div>
        <div class="meta">
          <p class="national"><?= htmlspecialchars($card_nation, ENT_QUOTES, 'UTF-8') ?></p>
          <p class="age"><?= htmlspecialchars($card['mm_age'] . '대', ENT_QUOTES, 'UTF-8') ?></p>
          <p class="work_type"><?= $card['ru_work_type'] ?></p>
          <p class="price_type"><?= $card['ru_price_type'] ?></p>
        </div>
        <div class="hashtag"><?= $card['ru_my_type'] ?></div>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>
