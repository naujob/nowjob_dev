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
<style>

  .btn-wrap .btn-detail,
  .btn-wrap .btn-close {
    font-size: 0.8rem;
    padding: 6px 12px;
    border: 1px solid #ddd;
  
    background-color: #fff;
    cursor: pointer;
    flex: 1;
    padding: 6px 12px;
  }

  .btn-detail:hover,
  .btn-close:hover {
    background-color: #f5f5f5;
    border-color: #999;
  }
  .btn-wrap {
    display: flex;
    
    margin-top: 8px;
    justify-content: flex-end;
  }
  .chat-view-header .result-list-card{
    padding: 10px 0;
  }
  .status-label {
    font-size: 0.875rem;
    color: #ffffff;
    margin: 0;
    background: #1B9D7D;
    border: 1px solid #1B9D7D;
    width: 55px;
    border-radius: 15px;
    text-align: center;
    padding: 2px;
  }
  .data-label {
    font-size: 0.875rem;
    color: #999;
    margin: 0;
  }
</style>
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
            <?php if (!isset($tpl_simple_mode) || !$tpl_simple_mode): ?>
            <div class="wicon">
              {{rt_recom_badge}}{{rt_work_img}}
            </div>
            <?php endif; ?>
            <div class="title ellipsis">{{rc_name}}</div>
            <?php if (isset($tpl_simple_mode) && $tpl_simple_mode && isset($tpl_data_label)): ?>
            <div class style="display: flex; gap: 314px; align-items: center; margin-top: 4px;">
              <p class="data-label"><?= $tpl_data_label ?></p>
              <p class="status-label">{{status_label}}</p>
            </div>
            <div class="btn-wrap">
              <button class="btn-detail">상세보기</button>
              <button class="btn-close">닫기</button>
            </div>
            <?php endif; ?>
            <?php if (!isset($tpl_simple_mode) || !$tpl_simple_mode): ?>
            <div class="summary">
              <p>{{rc_nation_nm}}</p>
              <p>{{rc_age_nm}}</p>
            </div>
            <div class="summary area">
              <p>{{ad_addr}}</p>
            </div>
            <?php endif; ?>
          </div>
          <?php if (!isset($tpl_simple_mode) || !$tpl_simple_mode): ?>
          <div class="summary-container">
            <div class="summary3">{{rt_work_type}}</div>
            <div class="summary2">
              <p class="period">{{rc_price_type}}</p>
              <p class="amount">{{rc_price_pay}}</p>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </li>
</template>
<style>
  
</style>