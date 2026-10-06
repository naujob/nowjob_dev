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
$tpl_show_toggle   = isset($tpl_show_toggle)   ? (bool)$tpl_show_toggle  : true;
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
            <?php if (isset($tpl_simple_mode) && $tpl_simple_mode): ?>
            <!-- 채팅 화면 단순 모드: 이력서 제목 + 공개상태 한 줄 -->
            <div style="display: flex; align-items: center; gap: 0; height: 100%; width: 100%;">
              <div class="title ellipsis" style="flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ru_name}}</div>
              <p class="status-label" style="margin: 0;">{{status_label}}</p>
            </div>
            <?php else: ?>
            <!-- 일반 모드: 토글 가능한 아코디언 카드 -->
            <div class="title-wrap">
              <div class="title ellipsis">{{mm_name}}</div>
              <?php if ($tpl_show_toggle): ?>
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
            <?php endif; ?>
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
              <div class="chat_mark" data-visible="{{has_chat}}" data-chat_type="{{chat_type}}" data-rm_idx="{{chat_rm_idx}}"></div>
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

  /* 채팅화면 단순모드 칩: recruit_card_template.php의 status-label과 동일 스타일 (이 파일에서 재사용) */
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

  .chat-view-header .result-list-card .status-label {
    width: auto;
    flex-shrink: 0;
    padding: 0 1rem;
    border-radius: 0;
    min-width: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 32px;
    white-space: nowrap;
  }

  /* chat 화면에서 simple 모드 시 각진 chip 스타일 */
  .chat-view-header .result-list-card .company-info {
    width: auto;
    flex-shrink: 0;
    padding: 0 1rem;
    border-radius: 0;
    min-width: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    white-space: nowrap;
    background: #1B9D7D;
    color: #ffffff;
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
