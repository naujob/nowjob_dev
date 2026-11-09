<?php
/**
 * PC 인재목록 이력서 카드 파셜 (서버 렌더링 전용)
 * web/resume/hr_list.php 첫 화면과 resume_proc::search_list2025(render=html) AJAX 응답이 이 파일 하나로 같은 카드를 그린다
 * 마크업과 표시 규칙은 web/_layout/resume_list_template.php(hr_list 옵션) 및 예전 JS 치환 로직과 같다
 *
 * 사용 전 설정 변수:
 *   $card_list - formatResumeListRows() 결과 (ru_price_type_raw로 첫 급여유형을 계산)
 *   $mm_type   - 회원 유형 (COMP만 북마크 버튼 표시)
 */
$card_list = (isset($card_list) && is_array($card_list)) ? $card_list : array();
$card_is_comp = (isset($mm_type) && $mm_type === "COMP");
foreach ($card_list as $card) :
  $card_is_reco = !empty($card['pm_idx']);
  $card_id = htmlspecialchars($card['ru_idx'], ENT_QUOTES, 'UTF-8');
  $card_name = htmlspecialchars($card['ru_name'], ENT_QUOTES, 'UTF-8');
  $card_nation = trim((string) $card['mm_nation']);
  // 나이: 값이 없으면 빈칸 (예전 JS와 같음)
  $card_age = !empty($card['mm_age']) ? $card['mm_age'] . '대' : '';
  $card_price = getPriceTypeFirst(isset($card['ru_price_type_raw']) ? $card['ru_price_type_raw'] : '');
  $card_icons = $card['ru_work_img'];
?>
<li class="list-item item-hover" data-ru_idx="<?= $card_id ?>" data-mm_idx="<?= htmlspecialchars($card['mm_idx'], ENT_QUOTES, 'UTF-8') ?>">
  <div class="item-info">
    <div class="img">
      <img src="<?= htmlspecialchars($card['ru_resume_img'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= $card_name ?> 프로필 이미지">
      <?php if ($card_is_comp) : ?><button type="button" data-bk_type="RESUME" data-bk_key="<?= $card_id ?>" class="btnBookMark btn-bookmark js-toggle-bookmark<?= (isset($card['book_yn']) && $card['book_yn'] === 'Y') ? ' on' : '' ?>"><span class="sr-text">즐겨찾기 추가</span></button><?php endif; ?>
      <?php if ($card_is_reco) : ?><p class="recomm-badge"><span>추천</span></p><?php endif; ?>
    </div>
    <p class="star-rating"><span class="star" style="width:100%;"></span><span class="sr-text">평점</span><span class="sr-text">5</span></p>

    <div class="desc">
      <div class="content-wrap">
        <div class="timestamp"><?php if ($card_is_reco) : ?><p class="reco-title">지금뜨는 추천인재</p><?php else : ?><?= htmlspecialchars($card['up_dt_timestamp'], ENT_QUOTES, 'UTF-8') ?><?php endif; ?></div>
        <div class="inline-group">
          <p class="category"><?= $card_icons ?></p>
        </div>
        <div class="title-wrap" style="display: block;">
          <p class="title"><?= $card_name ?></p>
        </div>
        <div class="meta">
          <p class="national"><?= htmlspecialchars($card_nation, ENT_QUOTES, 'UTF-8') ?></p>
          <p class="age"><?= htmlspecialchars($card_age, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
      </div>

      <div style="width: 290px;" class="price_type_wrap">
        <span class="type price_type"><?= $card['ru_work_type'] ?></span>
        <span class="area"><?= htmlspecialchars($card['ad_addr'], ENT_QUOTES, 'UTF-8') ?></span>
        <span class="ru_price_type"><?= $card_price ?></span>
      </div>
      <ul class="working-day">
        <?= $card['ru_week'] ?>
      </ul>
    </div>
  </div>
</li>
<?php endforeach; ?>
