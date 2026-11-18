<?php
// 언어 전환 스위치(KO / 中文) 공통 마크업
// $lang_switch_variant 값으로 놓이는 자리를 고른다 (모양은 css/m_lang_switch.css, 동작은 js/m_lang_switch.js)
//   'header' : 채용목록·인재목록 상단 헤더 (main_visual.php)
//   'float'  : 모든 화면 오른쪽 아래 플로팅 (_layout/basic.php)
//   'top'    : 상세 화면 상단 바 (hr_post.php, job_post.php)
$lang_switch_variant = isset($lang_switch_variant) ? $lang_switch_variant : 'header';
// 현재 언어 (모바일은 site_lang 쿠키 기준 — i18n_helper::site_lang())
$lang_switch_now = site_lang();
// 같은 화면에 스위치가 여러 개 있어도 별 모양 id가 겹치지 않도록 자리마다 다른 id를 쓴다
$lang_switch_star_id = 'm-flag-cn-star-' . $lang_switch_variant;
?>
<div class="lang-switch" data-lang="<?= $lang_switch_now ?>" role="group" aria-label="<?= t('언어 선택') ?>">
  <button type="button" class="lang-switch-btn" data-lang-value="ko" lang="ko" aria-pressed="<?= $lang_switch_now === 'ko' ? 'true' : 'false' ?>">
    <!-- 태극기 아이콘 -->
    <svg class="lang-flag" viewBox="0 0 30 20" aria-hidden="true" focusable="false">
      <rect width="30" height="20" fill="#FFFFFF"/>
      <path d="M10.5,10 A4.5,4.5 0 0 1 19.5,10 Z" fill="#CD2E3A"/>
      <path d="M10.5,10 A4.5,4.5 0 0 0 19.5,10 Z" fill="#0047A0"/>
      <circle cx="15" cy="7.75" r="2.25" fill="#0047A0"/>
      <circle cx="15" cy="12.25" r="2.25" fill="#CD2E3A"/>
      <g fill="#000000">
        <rect x="3" y="2.5" width="5" height="0.8"/>
        <rect x="3" y="3.7" width="5" height="0.8"/>
        <rect x="3" y="4.9" width="5" height="0.8"/>
        <rect x="22" y="2.5" width="1.8" height="0.8"/>
        <rect x="25.2" y="2.5" width="1.8" height="0.8"/>
        <rect x="22" y="3.7" width="5" height="0.8"/>
        <rect x="22" y="4.9" width="1.8" height="0.8"/>
        <rect x="25.2" y="4.9" width="1.8" height="0.8"/>
        <rect x="3" y="13.5" width="1.8" height="0.8"/>
        <rect x="6.2" y="13.5" width="1.8" height="0.8"/>
        <rect x="3" y="14.7" width="5" height="0.8"/>
        <rect x="3" y="15.9" width="1.8" height="0.8"/>
        <rect x="6.2" y="15.9" width="1.8" height="0.8"/>
        <rect x="22" y="13.5" width="1.8" height="0.8"/>
        <rect x="25.2" y="13.5" width="1.8" height="0.8"/>
        <rect x="22" y="14.7" width="1.8" height="0.8"/>
        <rect x="25.2" y="14.7" width="1.8" height="0.8"/>
        <rect x="22" y="15.9" width="1.8" height="0.8"/>
        <rect x="25.2" y="15.9" width="1.8" height="0.8"/>
      </g>
    </svg>
    <span>KO</span>
  </button>
  <button type="button" class="lang-switch-btn" data-lang-value="zh" lang="zh-CN" aria-pressed="<?= $lang_switch_now === 'zh' ? 'true' : 'false' ?>">
    <!-- 오성홍기 아이콘 -->
    <svg class="lang-flag" viewBox="0 0 30 20" aria-hidden="true" focusable="false">
      <rect width="30" height="20" fill="#DE2910"/>
      <defs>
        <polygon id="<?= $lang_switch_star_id ?>" points="0,-1 0.235,-0.324 0.951,-0.309 0.38,0.124 0.588,0.809 0,0.4 -0.588,0.809 -0.38,0.124 -0.951,-0.309 -0.235,-0.324"/>
      </defs>
      <use href="#<?= $lang_switch_star_id ?>" transform="translate(5 5) scale(2.4)" fill="#FFDE00"/>
      <use href="#<?= $lang_switch_star_id ?>" transform="translate(10 2) scale(0.8)" fill="#FFDE00"/>
      <use href="#<?= $lang_switch_star_id ?>" transform="translate(11.5 4) scale(0.8)" fill="#FFDE00"/>
      <use href="#<?= $lang_switch_star_id ?>" transform="translate(11.5 6.5) scale(0.8)" fill="#FFDE00"/>
      <use href="#<?= $lang_switch_star_id ?>" transform="translate(10 8.5) scale(0.8)" fill="#FFDE00"/>
    </svg>
    <span>中文</span>
  </button>
</div>
