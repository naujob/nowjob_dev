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
    <div class="content-li<?= $tpl_recomm_class ? ' recomm' : '' ?>" data-ru_idx="{{ru_idx}}" data-collapsed="true" <?= $tpl_show_mm_idx ? 'data-mm_idx="{{mm_idx}}"' : '' ?>>
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
            <?php if (!isset($tpl_simple_mode) || !$tpl_simple_mode): ?>
            <div class="wicon">{{ru_recom_badge}}{{ru_work_img}}</div>
            <?php endif; ?>
            <div class="title-wrap">
              <div class="title ellipsis">{{mm_name}}</div>
              <?php if (isset($tpl_simple_mode) && $tpl_simple_mode): ?>
              <button class="toggle-expand" type="button">
                <i class="fa-solid fa-angle-up"></i>
              </button>
              <?php endif; ?>
            </div>
            <div class="simple-content" data-collapsed="true">
              <div class="data-row">
                <p class="data-label"><?= $tpl_data_label ?></p>
                <p class="company-info">{{mm_comp_name}}</p>
              </div>
              <div class="btn-wrap">
                <button class="btn-detail">상세보기</button>
                <button class="btn-close">닫기</button>
              </div>
            </div>
            <?php if (!isset($tpl_simple_mode) || !$tpl_simple_mode): ?>
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
              <div class="chat_mark" data-visible="{{has_chat}}" data-rm_idx="{{chat_rm_idx}}" style="background-image: url('/resource/shop/mobile/img/hr_list_chat_mark.png');"></div>
              <?php endif; ?>
            </div>
            <div class="summary3">
              <p class="worktype">{{ru_work_type}}</p>

            </div>
            <div class="summary2">
              <p class="pricetype">{{ru_price_type}}</p>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </li>
</template>

<style>

  .btn-wrap .btn-detail,
  .btn-wrap .btn-close {
    font-size:  1.5rem;
    padding: 6px 12px;
    border: 1px solid #cdc8c8;

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
  .result-list-card{
    padding: 10px 0;
  }
  .data-label {
    font-size: 0.875rem;
    color: #999;
    margin: 0;
  }

  .company-info {
    font-size: 0.875rem;
    color: #666;
    margin: 0;
    font-weight: 500;
  }

  .toggle-expand i {
    color: #999;
    font-size: 1.2rem;
    display: inline-block;
    transition: transform 0.3s ease-in-out;
  }

  .toggle-expand i[data-state="expanded"] {
    transform: rotate(180deg);
  }

  .toggle-expand i[data-state="collapsed"] {
    transform: rotate(0deg);
  }

  .result-list-card .content-info .desc {
    transition: height 0.3s ease;
  }

  .simple-content {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: max-height 0.3s ease, opacity 0.3s ease;
  }

  .simple-content[data-collapsed="true"] {
    max-height: 0;
    opacity: 0;
  }

  .simple-content[data-collapsed="false"] {
    max-height: 500px;
    opacity: 1;
  }
  .desc .title-wrap {
    height: 55px;
 }
 .desc .title-wrap .title.ellipsis {
    font-size: 25px;
 }


 .content-body .desc .simple-content .data-row {
    height: 27px;
 }
 .content-body .desc .simple-content .data-row .data-label {
    font-size: 17px;
 }
 .content-body .desc .simple-content .data-row .status-label {
    font-size: 17px;
 }
</style>
