<?php
/*
 * 추천 공고 카드 공통 템플릿
 * include 전에 옵션 변수 설정:
 *   $tpl_show_bookmark    : true  → MEM 유저에게 북마크 버튼 표시 (기본: false)
 *   $tpl_show_hashtag     : true  → 해시태그 영역 표시 (기본: false)
 *   $tpl_slide_wrapper    : false → keen-slider__slide 래퍼 생략 (기본: true)
 *   $tpl_card_extra_class : ''    → 카드에 추가할 CSS 클래스 (기본: '')
 * 외부 의존:
 *   $mm_type : MEM 타입 체크용 (MEM이 아니면 북마크 버튼 비표시)
 */
$tpl_show_bookmark    = isset($tpl_show_bookmark)    ? $tpl_show_bookmark    : false;
$tpl_show_hashtag     = isset($tpl_show_hashtag)     ? $tpl_show_hashtag     : false;
$tpl_slide_wrapper    = isset($tpl_slide_wrapper)    ? $tpl_slide_wrapper    : true;
$tpl_card_extra_class = isset($tpl_card_extra_class) ? $tpl_card_extra_class : '';
?>
<!-- 추천 리스트 템플릿 -->
<template id="tpl_reco_list">
<?php if ($tpl_slide_wrapper) : ?>
  <div class="keen-slider__slide">
<?php endif; ?>
    <div class="recruit-card <?php echo htmlspecialchars($tpl_card_extra_class, ENT_QUOTES, 'UTF-8'); ?>" data-rc_idx="{{rc_idx}}">
      <div class="thumb-wrap">
        <div class="thumb">
          <a href="/recruit/job_post/{{rc_idx}}" target="_blank">
            <img src="{{rt_recruit_img}}" alt="">
          </a>
        </div>
        <?php if ($tpl_show_bookmark && isset($mm_type) && $mm_type === "MEM") : ?>
          <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}"
            class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}">
            <span class="sr-text">즐겨찾기 추가</span>
          </button>
        <?php endif; ?>
      </div>
      <div class="content-wrap">
        <div class="inline-group">
          {{rt_work_img}}
          <p class="area">{{ad_addr}}</p>
        </div>
        <p class="title">
          <strong>
            <a href="/recruit/job_post/{{rc_idx}}" target="_blank">{{rc_name}}</a>
          </strong>
        </p>
        <div class="card-bottom-wrap">
          <div class="card-meta-group">
            <div class="meta">
              <p>{{rc_nation_nm}}</p>
              <p>{{rc_age_nm}}</p>
            </div>
            <div class="summary wtype">{{rt_work_type}}</div>
          </div>
          <div class="price-wrap">
            <p class="period">{{rc_price_type}}</p>
            <p class="amount">{{rc_price_pay}}</p>
          </div>
        </div>
        <?php if ($tpl_show_hashtag) : ?>
          <div class="hashtag">{{rt_hash}}</div>
        <?php endif; ?>
      </div>
    </div>
<?php if ($tpl_slide_wrapper) : ?>
  </div>
<?php endif; ?>
</template>
