<?php
// 언어 전환 스위치 공통 마크업
// $lang_switch_variant 값으로 놓이는 자리를 고른다 (모양은 css/m_lang_switch.css, 동작은 js/m_lang_switch.js)
//   'header' : 채용목록·인재목록 상단 헤더 (main_visual.php) — 사용 가능한 언어 버튼을 전부 보여준다
//   'float'  : 그 밖의 모든 화면 오른쪽 아래 플로팅 (_layout/basic.php) — 토글 버튼 1개만 보여준다
//   'top'    : 상세 화면 상단 바 (hr_post.php, job_post.php) — 토글 버튼 1개만 보여준다
// 토글 규칙 (header 제외)
//   - 현재 언어가 ko가 아니면 [KO] 버튼만 보인다 (누르면 한국어로 복귀)
//   - 현재 언어가 ko이면 마지막으로 고른 외국어(site_lang_pref 쿠키) 버튼만 보인다
//   - 외국어를 고른 적이 없으면 아무것도 그리지 않는다
$lang_switch_variant = isset($lang_switch_variant) ? $lang_switch_variant : 'header';
// float 자리에서만 쓰는 값: 하단 메뉴가 있는 화면이면 true (basic.php 가 설정)
$lang_switch_nav = isset($lang_switch_nav) ? $lang_switch_nav : false;
// 현재 언어 (모바일은 site_lang 쿠키 기준 — i18n_helper::site_lang())
$lang_switch_now = site_lang();

// 언어 목록 (새 언어는 여기 한 줄 추가; enabled=false 는 번역 준비 전이라 버튼을 숨김)
$lang_switch_registry = array(
  'ko' => array('label' => 'KO', 'lang_attr' => 'ko',    'enabled' => true,  'flag' => 'kr'),
  'zh' => array('label' => '中文', 'lang_attr' => 'zh-CN', 'enabled' => true,  'flag' => 'cn'),
  'en' => array('label' => 'EN', 'lang_attr' => 'en',    'enabled' => false, 'flag' => null),
  'ru' => array('label' => 'RU', 'lang_attr' => 'ru',    'enabled' => false, 'flag' => 'ru'),
);

// 이 자리에 보일 버튼 언어 코드 목록
$lang_switch_codes = array();
if ($lang_switch_variant === 'header') {
  foreach ($lang_switch_registry as $code => $info) {
    if ($info['enabled']) {
      $lang_switch_codes[] = $code;
    }
  }
} else {
  if ($lang_switch_now !== 'ko') {
    $lang_switch_codes[] = 'ko';
  } else {
    // 마지막으로 고른 외국어 (목록에 있고 켜진 언어만 인정)
    $lang_switch_pref = isset($_COOKIE['site_lang_pref']) ? $_COOKIE['site_lang_pref'] : '';
    if ($lang_switch_pref !== 'ko' && isset($lang_switch_registry[$lang_switch_pref]) && $lang_switch_registry[$lang_switch_pref]['enabled']) {
      $lang_switch_codes[] = $lang_switch_pref;
    }
  }
}

// 보일 버튼이 없으면 아무것도 그리지 않는다 (플로팅 wrapper 포함)
if (!empty($lang_switch_codes)) :
  // 같은 화면에 스위치가 여러 개 있어도 별 모양 id가 겹치지 않도록 자리마다 다른 id를 쓴다
  $lang_switch_star_id = 'm-flag-cn-star-' . $lang_switch_variant;
  $lang_switch_count = count($lang_switch_codes);
  // 이 요청에서 스위치를 실제로 그렸다는 표시 (basic.php 가 플로팅 중복 여부를 이 값으로 판단)
  $GLOBALS['lang_switch_drawn'] = true;

  // 국기 아이콘 SVG (code 는 $lang_switch_registry 의 flag 값)
  $lang_switch_flags = array(
    'kr' => '<svg class="lang-flag" viewBox="0 0 30 20" aria-hidden="true" focusable="false">
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
    </svg>',
    'cn' => '<svg class="lang-flag" viewBox="0 0 30 20" aria-hidden="true" focusable="false">
      <rect width="30" height="20" fill="#DE2910"/>
      <defs>
        <polygon id="' . $lang_switch_star_id . '" points="0,-1 0.235,-0.324 0.951,-0.309 0.38,0.124 0.588,0.809 0,0.4 -0.588,0.809 -0.38,0.124 -0.951,-0.309 -0.235,-0.324"/>
      </defs>
      <use href="#' . $lang_switch_star_id . '" transform="translate(5 5) scale(2.4)" fill="#FFDE00"/>
      <use href="#' . $lang_switch_star_id . '" transform="translate(10 2) scale(0.8)" fill="#FFDE00"/>
      <use href="#' . $lang_switch_star_id . '" transform="translate(11.5 4) scale(0.8)" fill="#FFDE00"/>
      <use href="#' . $lang_switch_star_id . '" transform="translate(11.5 6.5) scale(0.8)" fill="#FFDE00"/>
      <use href="#' . $lang_switch_star_id . '" transform="translate(10 8.5) scale(0.8)" fill="#FFDE00"/>
    </svg>',
    'ru' => '<svg class="lang-flag" viewBox="0 0 30 20" aria-hidden="true" focusable="false">
      <rect width="30" height="6.67" fill="#FFFFFF"/>
      <rect y="6.67" width="30" height="6.67" fill="#0039A6"/>
      <rect y="13.33" width="30" height="6.67" fill="#D52B1E"/>
    </svg>',
  );
  ?>
  <?php if ($lang_switch_variant === 'float') : ?>
  <div id="langSwitchFloat" class="lang-float" data-nav="<?= $lang_switch_nav ? 'true' : 'false' ?>" data-hidden="false" data-compact="false">
  <?php endif; ?>
  <?php if ($lang_switch_variant === 'header' && $lang_switch_count >= 3) :
    // 언어가 3개 이상이면 현재 언어 하나만 보이고, 누르면 목록이 펼쳐진다 (2개 이하는 아래 토글 그대로)
    $lang_switch_item_html = array();
    foreach ($lang_switch_codes as $lang_switch_code) {
      $lang_switch_info = $lang_switch_registry[$lang_switch_code];
      $lang_switch_flag_html = ($lang_switch_info['flag'] !== null && isset($lang_switch_flags[$lang_switch_info['flag']])) ? $lang_switch_flags[$lang_switch_info['flag']] : '';
      $lang_switch_item_html[$lang_switch_code] = $lang_switch_flag_html . '<span>' . $lang_switch_info['label'] . '</span>';
    }
    // 처음 보이는 글자: 현재 언어 (목록에 없으면 첫 번째 언어)
    $lang_switch_current_code = isset($lang_switch_item_html[$lang_switch_now]) ? $lang_switch_now : $lang_switch_codes[0];
  ?>
  <div class="lang-dropdown" data-lang="<?= $lang_switch_now ?>" data-open="false">
    <button type="button" class="lang-dd-btn" aria-haspopup="listbox" aria-expanded="false" aria-label="<?= t('언어 선택') ?>">
      <span class="lang-dd-current"><?= $lang_switch_item_html[$lang_switch_current_code] ?></span>
      <i class="lang-dd-caret" aria-hidden="true"></i>
    </button>
    <div class="lang-dd-list" role="listbox">
      <?php foreach ($lang_switch_codes as $lang_switch_code) : ?>
      <button type="button" class="lang-dd-item" role="option" data-lang-value="<?= $lang_switch_code ?>" lang="<?= $lang_switch_registry[$lang_switch_code]['lang_attr'] ?>" aria-pressed="<?= $lang_switch_now === $lang_switch_code ? 'true' : 'false' ?>"><?= $lang_switch_item_html[$lang_switch_code] ?></button>
      <?php endforeach; ?>
    </div>
  </div>
  <?php else : ?>
  <div class="lang-switch" data-lang="<?= $lang_switch_now ?>" data-count="<?= $lang_switch_count ?>" role="group" aria-label="<?= t('언어 선택') ?>">
    <?php foreach ($lang_switch_codes as $lang_switch_code) :
      $lang_switch_info = $lang_switch_registry[$lang_switch_code];
      $lang_switch_flag_html = ($lang_switch_info['flag'] !== null && isset($lang_switch_flags[$lang_switch_info['flag']])) ? $lang_switch_flags[$lang_switch_info['flag']] : '';
    ?>
    <button type="button" class="lang-switch-btn" data-lang-value="<?= $lang_switch_code ?>" lang="<?= $lang_switch_info['lang_attr'] ?>" aria-pressed="<?= $lang_switch_now === $lang_switch_code ? 'true' : 'false' ?>"<?php if ($lang_switch_count === 1) : ?> data-alt-value="<?= $lang_switch_now ?>" data-alt-lang="<?= $lang_switch_registry[$lang_switch_now]['lang_attr'] ?>"<?php endif; ?>>
      <?= $lang_switch_flag_html ?>
      <span><?= $lang_switch_info['label'] ?></span>
    </button>
    <?php endforeach; ?>
    <?php if ($lang_switch_count === 1) :
      // 버튼 하나짜리 토글: AJAX 전환 후 버튼을 반대 언어 모양으로 바꿀 때 쓸 내용 (m_lang_switch.js 가 교체)
      $lang_switch_alt_info = $lang_switch_registry[$lang_switch_now];
      $lang_switch_alt_flag = ($lang_switch_alt_info['flag'] !== null && isset($lang_switch_flags[$lang_switch_alt_info['flag']])) ? $lang_switch_flags[$lang_switch_alt_info['flag']] : '';
    ?>
    <template class="lang-switch-alt"><?= $lang_switch_alt_flag ?><span><?= $lang_switch_alt_info['label'] ?></span></template>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <?php if ($lang_switch_variant === 'float') : ?>
  </div>
  <?php endif; ?>
<?php endif; ?>
