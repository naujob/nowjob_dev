<?= getCachCss(RES_PATH . "/css/resume_list.css") ?>
<?php
// 인재 목록 공통 템플릿 - hr_list.php, search.php에서 include
// 사용 예:
// $tpl_id = 'tpl_resume_card';
// $tpl_show_timestamp = true;
// $tpl_show_mm_idx = true;
// $tpl_show_reco_title = true;
// $tpl_price_wrap_width = '290px';
// include APPPATH . 'views/web/_layout/resume_list_template.php';

$tpl_id             = isset($tpl_id)             ? $tpl_id             : 'tpl_resume_card';
$tpl_show_timestamp = isset($tpl_show_timestamp) ? $tpl_show_timestamp : false;
$tpl_show_mm_idx    = isset($tpl_show_mm_idx)    ? $tpl_show_mm_idx    : false;
$tpl_show_reco_title = isset($tpl_show_reco_title) ? $tpl_show_reco_title : false;
$tpl_price_wrap_width = isset($tpl_price_wrap_width) ? $tpl_price_wrap_width : '20%';
?>
<template id="<?= $tpl_id ?>">
  <li class="list-item item-hover" data-ru_idx="{{ru_idx}}"<?php if ($tpl_show_mm_idx): ?> data-mm_idx="{{mm_idx}}"<?php endif; ?>>
    <div class="item-info">

      <div class="img">
        <img src="{{ru_resume_img}}">
        <?php if ($mm_type === "COMP"): ?>
        <button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button>
        <?php endif; ?>
        <p class="recomm-badge"><span>추천</span></p>
      </div>
      <p class="star-rating"><span class="star" style="width:100%;"></span><span class="sr-text">평점</span><span class="sr-text">5</span></p>

      <div class="desc">
        <div class="content-wrap">
          <?php if ($tpl_show_timestamp || $tpl_show_reco_title): ?><div class="timestamp"><?php if ($tpl_show_reco_title): ?><p class="reco-title">지금뜨는 추천인재</p><?php endif; ?><?php if ($tpl_show_timestamp): ?>{{timestamp}}<?php endif; ?></div><?php endif; ?>
          <div class="inline-group">
            <p class="category">{{ru_work_img}}</p>
          </div>
          <div class="title-wrap" style="display: block;">
            <p class="title">{{ru_name}}</p>
          </div>
          <div class="meta">
            <p class="national">{{mm_nation}}</p>
            <p class="age">{{ma_age}}</p>
          </div>
        </div>

        <div style="align-content: center;width: <?= $tpl_price_wrap_width ?>;text-align: center;" class="price_type_wrap">
          <span class="type price_type">{{ru_work_type}}</span>
          <span class="area">{{ad_addr}}</span>
          <span class="ru_price_type">{{ru_price_type}}</span>
        </div>
        <ul class="working-day">
          {{ru_week}}
        </ul>
      </div>
    </div>
  </li>
</template>
