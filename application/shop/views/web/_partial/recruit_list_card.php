<?php
/**
 * PC 채용목록 공고 카드 파셜 (서버 렌더링 전용)
 * web/recruit/job_list.php 첫 화면과 recruit_proc::getRecruitList(render=html) AJAX 응답이 이 파일 하나로 같은 카드를 그린다
 * 마크업과 표시 규칙은 web/_layout/recruit_list_template.php 및 예전 JS 치환 로직과 같다
 *
 * 사용 전 설정 변수:
 *   $card_list - formatRecruitListRows() 결과 (rt_* 값은 서버 헬퍼가 만든 HTML이라 그대로 출력)
 *   $mm_type   - 회원 유형 (MEM만 북마크 버튼 표시)
 *
 * 기업이 입력한 값(공고명/주소 등)은 이스케이프한다
 */
$card_list = (isset($card_list) && is_array($card_list)) ? $card_list : array();
$card_is_mem = (isset($mm_type) && $mm_type === "MEM");
foreach ($card_list as $card) :
  $card_is_reco = !empty($card['pm_idx']);
  $card_negotiable = (strpos((string) $card['rc_price_type'], '협의') !== false);
  $card_period = $card_negotiable ? '' : $card['rt_price_type'];
  $card_amount = $card_negotiable ? t('급여협의') : number_format((float) $card['rc_price_pay']) . t('원');
  $card_icons = isset($card['rt_work_badge']) ? $card['rt_work_badge'] : trimCategoryLabelImg($card['rt_work_img'], 2);
  $card_id = htmlspecialchars($card['rc_idx'], ENT_QUOTES, 'UTF-8');
  $card_name = htmlspecialchars($card['rc_name'], ENT_QUOTES, 'UTF-8');
?>
<li class="list-item" data-rc_idx="<?= $card_id ?>">
  <div class="content-li">
    <div class="content-info">
      <div class="content-body">
        <div class="img">
          <img src="<?= htmlspecialchars($card['rt_recruit_img'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= $card_name ?> <?= t('공고 이미지') ?>">
          <?php if ($card_is_mem) : ?><button type="button" data-bk_type="RECRUIT" data-bk_key="<?= $card_id ?>" class="btnBookMark btn-bookmark js-toggle-bookmark<?= (isset($card['book_yn']) && $card['book_yn'] === 'Y') ? ' on' : '' ?>"><span class="sr-text"><?= t('즐겨찾기 추가') ?></span></button><?php endif; ?>
          <p class="recomm-badge"><?php if ($card_is_reco) : ?><span><?= t('추천') ?></span><?php endif; ?></p>
        </div>
        <div class="desc">
          <div class="desc1">
            <div class="timestamp"><?php if ($card_is_reco) : ?><p class="reco-title"><?= t('지금뜨는 추천공고') ?></p><?php else : ?><?= htmlspecialchars(t('등록일 :') . ' ' . $card['up_dt'], ENT_QUOTES, 'UTF-8') ?><?php endif; ?></div>
            <div class="wicon"><?= $card_icons ?></div>
            <div class="title ellipsis"><?= $card_name ?></div>
            <div class="summary">
              <p><?= htmlspecialchars(t_list($card['rc_nation_nm']), ENT_QUOTES, 'UTF-8') ?></p>
              <p><?= htmlspecialchars($card['rc_age_nm'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
          </div>
          <div class="desc2">
            <div class="summary wtype"><?= $card['rt_work_type'] ?></div>
            <p class="area"><?= htmlspecialchars($card['ad_addr'], ENT_QUOTES, 'UTF-8') ?></p>
          </div>
          <div class="desc3">
            <div class="summary"><p class="period"><?= $card_period ?></p></div>
            <p class="amount"><?= htmlspecialchars($card_amount, ENT_QUOTES, 'UTF-8') ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</li>
<?php endforeach; ?>
