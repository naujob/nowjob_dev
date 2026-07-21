<?php
/**
 * 인재 카드 JS 템플릿 파셜 (AJAX 렌더링용 <template> 태그)
 *
 * 사용 전 설정 변수:
 *   $tpl_id           - template 태그 id (기본값 'tpl_resume_card')
 *   $tpl_recomm_class - bool, content-li에 recomm 클래스 + 추천 배지 포함 여부 (기본값 false)
 *   $tpl_show_mm_idx  - bool, data-mm_idx 속성 포함 여부 (hr_list에서 본인 이력서 판별용, 기본값 false)
 *
 * JS에서 {{플레이스홀더}} 치환 후 #ajaxList에 append
 */
$tpl_id            = isset($tpl_id)            ? $tpl_id            : 'tpl_resume_card';
$tpl_recomm_class  = isset($tpl_recomm_class)  ? (bool)$tpl_recomm_class : false;
$tpl_show_mm_idx   = isset($tpl_show_mm_idx)   ? (bool)$tpl_show_mm_idx  : false;
$tpl_show_chat_mark = isset($tpl_show_chat_mark) ? (bool)$tpl_show_chat_mark : false;
?>
<template id="<?= $tpl_id ?>">
  <li class="list-item">
    <div class="content-li<?= $tpl_recomm_class ? ' recomm' : '' ?>"
      data-ru_idx="{{ru_idx}}"
      <?= $tpl_show_mm_idx ? 'data-mm_idx="{{mm_idx}}"' : '' ?>>
      <div class="content-info">
        <div class="content-body">
          <!-- <div class="img">
            <img src="{{ru_resume_img}}">
            <?php if (isset($mm_type) && $mm_type === 'COMP'): ?>
              <button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}"
                class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}">
                <span class="sr-text">즐겨찾기 추가</span>
              </button>
            <?php endif; ?>
            <?php if ($tpl_recomm_class): ?>
              <p class="recomm-badge"><span>추천</span></p>
            <?php endif; ?>
          </div> -->
          <div class="desc">
            <div class="wicon">{{ru_recom_badge}}{{ru_work_img}}</div>
            <div class="title ellipsis">{{mm_name}}</div>
            <div class="summary">
              <ul class="working-day">{{ru_week}}</ul>
            </div>
            <div class="summary-with-chat">
              <div class="summary">
                <p>{{mm_nation}}</p>
                <p>{{ma_age}}</p>
                <p>{{ad_addr}}</p>
              </div>
              <?php if ($tpl_show_chat_mark): ?>
              <div class="chat_mark" data-visible="{{has_chat}}">
                  <img src="/resource/shop/mobile/img/hr_list_chat_mark.png" alt="채팅 이력">
              </div>
              <?php endif; ?>
            </div>
            <div class="summary3">
              <p class="worktype">{{ru_work_type}}</p>
              
            </div>
            <div class="summary2">
              <p class="pricetype">{{ru_price_type}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </li>
</template>
