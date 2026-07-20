<?php
/**
 * 채용공고 카드 JS 템플릿 파셜 (AJAX 렌더링용 <template> 태그)
 *
 * 사용 전 설정 변수:
 *   $tpl_id           - template 태그 id (기본값 'tpl_recruit_card')
 *   $tpl_recomm_class - bool, content-li에 recomm 클래스 + 추천 배지 포함 여부 (기본값 false)
 *
 * JS에서 {{플레이스홀더}} 치환 후 #ajaxList에 append
 */
$tpl_id           = isset($tpl_id)           ? $tpl_id           : 'tpl_recruit_card';
$tpl_recomm_class = isset($tpl_recomm_class) ? (bool)$tpl_recomm_class : false;
?>
<template id="<?= $tpl_id ?>">
  <li class="list-item">
    <div class="content-li<?= $tpl_recomm_class ? ' recomm' : '' ?>" data-rc_idx="{{rc_idx}}">
      <div class="content-info">
        <div class="content-body">
          <!-- <div class="img">
            <img src="{{rt_recruit_img}}">
            <?php if (isset($mm_type) && ($mm_type === 'MEM' || $mm_type === 'COMP')): ?>
              <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}"
                class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}">
                <span class="sr-text">즐겨찾기 추가</span>
              </button>
            <?php endif; ?>
            <?php if ($tpl_recomm_class): ?>
              <p class="recomm-badge"><span>추천</span></p>
            <?php endif; ?>
          </div> -->
          <div class="desc">
            <div class="wicon">
              {{rt_recom_badge}}{{rt_work_img}}
            </div>
            <div class="title ellipsis">{{rc_name}}</div>
            <div class="summary">
              <p>{{rc_nation_nm}}</p>
              <p>{{rc_age_nm}}</p>
            </div>
            <div class="summary area">
              <p>{{ad_addr}}</p>
            </div>
          </div>
          <div class="summary-container">
            <div class="summary3">{{rt_work_type}}</div>
            <div class="summary2">
              <p class="period">{{rc_price_type}}</p>
              <p class="amount">{{rc_price_pay}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </li>
</template>
