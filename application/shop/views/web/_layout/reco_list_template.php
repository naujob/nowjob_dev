<?php
// 추천 인재 슬라이더 템플릿 (hr_list.php, hr_post.php 공용)
// 기본값 설정
$reco_tpl_id       = isset($reco_tpl_id)       ? $reco_tpl_id       : 'tpl_reco_list';
$reco_show_bookmark = isset($reco_show_bookmark) ? $reco_show_bookmark : false;
$reco_show_mm_idx   = isset($reco_show_mm_idx)   ? $reco_show_mm_idx   : false;
?>
<template id="<?= $reco_tpl_id ?>">
  <div class="keen-slider__slide">
    <div class="person-card reco-card"
         data-ru_idx="{{ru_idx}}"
         <?php if ($reco_show_mm_idx): ?>data-mm_idx="{{mm_idx}}"<?php endif; ?>>
      <div class="thumb-wrap">
        <div class="img">
          <img src="{{ru_resume_img}}" alt="{{ru_name}} 프로필 이미지">
          <?php if ($reco_show_bookmark && $mm_type === "COMP"): ?>
            <button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}"
              class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}">
              <span class="sr-text">즐겨찾기 추가</span>
            </button>
          <?php endif; ?>
        </div>
      </div>
      <div class="desc">
        <div class="content-wrap">
          <p class="category">{{ru_work_img}}</p>
          <div class="title-wrap">
            <p class="title"><strong>{{ru_name}}</strong></p>
          </div>
          <div class="meta">
            <p class="national">{{mm_nation}}</p>
            <p class="age">{{mm_age}}</p>
            <p class="work_type">{{ru_work_type}}</p>
            <p class="price_type">{{ru_price_type}}</p>
          </div>
          <div class="hashtag">{{ru_my_type}}</div>
        </div>
      </div>
    </div>
  </div>
</template>
