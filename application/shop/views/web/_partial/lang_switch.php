<?php
// PC 언어 전환 스위치 공통 마크업 (_layout/basic.php 헤더에서 include)
// 버튼 목록은 i18n_helper.php :: site_lang_registry() 에서 만든다 → 언어를 추가할 때 이 파일을 고치지 않는다
//   - 새 언어 추가: site_lang_registry() 에 한 줄 + 아래 $lang_switch_flags 에 국기가 없으면 국기 하나
//   - 동작(주소·사전·문구 전환)은 _layout/basic.php 의 스크립트가 버튼의 data-* 값을 읽어 처리한다
$lang_switch_now = site_lang();
$lang_switch_registry = site_lang_registry();

// 국기 아이콘 SVG (키는 site_lang_registry() 의 flag 값)
$lang_switch_flags = array(
  'kr' => '<svg class="lang-flag" viewBox="0 0 30 20" aria-hidden="true" focusable="false">'
    . '<rect width="30" height="20" fill="#FFFFFF"/>'
    . '<path d="M10.5,10 A4.5,4.5 0 0 1 19.5,10 Z" fill="#CD2E3A"/>'
    . '<path d="M10.5,10 A4.5,4.5 0 0 0 19.5,10 Z" fill="#0047A0"/>'
    . '<circle cx="15" cy="7.75" r="2.25" fill="#0047A0"/>'
    . '<circle cx="15" cy="12.25" r="2.25" fill="#CD2E3A"/>'
    . '<g fill="#000000">'
    . '<rect x="3" y="2.5" width="5" height="0.8"/><rect x="3" y="3.7" width="5" height="0.8"/><rect x="3" y="4.9" width="5" height="0.8"/>'
    . '<rect x="22" y="2.5" width="1.8" height="0.8"/><rect x="25.2" y="2.5" width="1.8" height="0.8"/>'
    . '<rect x="22" y="3.7" width="5" height="0.8"/><rect x="22" y="4.9" width="1.8" height="0.8"/><rect x="25.2" y="4.9" width="1.8" height="0.8"/>'
    . '<rect x="3" y="13.5" width="1.8" height="0.8"/><rect x="6.2" y="13.5" width="1.8" height="0.8"/>'
    . '<rect x="3" y="14.7" width="5" height="0.8"/><rect x="3" y="15.9" width="1.8" height="0.8"/><rect x="6.2" y="15.9" width="1.8" height="0.8"/>'
    . '<rect x="22" y="13.5" width="1.8" height="0.8"/><rect x="25.2" y="13.5" width="1.8" height="0.8"/>'
    . '<rect x="22" y="14.7" width="1.8" height="0.8"/><rect x="25.2" y="14.7" width="1.8" height="0.8"/>'
    . '<rect x="22" y="15.9" width="1.8" height="0.8"/><rect x="25.2" y="15.9" width="1.8" height="0.8"/>'
    . '</g></svg>',
  'cn' => '<svg class="lang-flag" viewBox="0 0 30 20" aria-hidden="true" focusable="false">'
    . '<rect width="30" height="20" fill="#DE2910"/>'
    . '<defs><polygon id="flag-cn-star" points="0,-1 0.235,-0.324 0.951,-0.309 0.38,0.124 0.588,0.809 0,0.4 -0.588,0.809 -0.38,0.124 -0.951,-0.309 -0.235,-0.324"/></defs>'
    . '<use href="#flag-cn-star" transform="translate(5 5) scale(2.4)" fill="#FFDE00"/>'
    . '<use href="#flag-cn-star" transform="translate(10 2) scale(0.8)" fill="#FFDE00"/>'
    . '<use href="#flag-cn-star" transform="translate(11.5 4) scale(0.8)" fill="#FFDE00"/>'
    . '<use href="#flag-cn-star" transform="translate(11.5 6.5) scale(0.8)" fill="#FFDE00"/>'
    . '<use href="#flag-cn-star" transform="translate(10 8.5) scale(0.8)" fill="#FFDE00"/>'
    . '</svg>',
);

// 주소 접두어 목록 (예: /zh) — 전환 시 현재 주소에서 접두어를 떼기 위해 쓴다
$lang_switch_prefixes = array();
foreach ($lang_switch_registry as $lang_switch_info) {
  if ($lang_switch_info['prefix'] !== '') {
    $lang_switch_prefixes[] = $lang_switch_info['prefix'];
  }
}
?>
<div class="lang-switch" data-lang="<?= $lang_switch_now ?>" data-prefixes="<?= htmlspecialchars(implode(',', $lang_switch_prefixes), ENT_QUOTES, 'UTF-8') ?>" role="group" aria-label="<?= t('언어 선택') ?>">
<?php foreach ($lang_switch_registry as $lang_switch_code => $lang_switch_info) :
  $lang_switch_flag_html = (isset($lang_switch_flags[$lang_switch_info['flag']])) ? $lang_switch_flags[$lang_switch_info['flag']] : '';
?>
  <?php
    // 사전 파일은 캐시 무효화용 버전을 붙여 넘긴다 (사전이 없는 언어는 빈 값)
    $lang_switch_dict_url = ($lang_switch_info['dict'] !== '') ? $lang_switch_info['dict'] . '?v=' . getFileVersion($lang_switch_info['dict']) : '';
  ?>
  <button type="button" class="lang-switch-btn" data-lang-value="<?= $lang_switch_code ?>" data-lang-prefix="<?= $lang_switch_info['prefix'] ?>" data-lang-html="<?= $lang_switch_info['html_lang'] ?>" data-lang-dict="<?= $lang_switch_dict_url ?>" lang="<?= $lang_switch_info['lang_attr'] ?>" aria-pressed="<?= $lang_switch_now === $lang_switch_code ? 'true' : 'false' ?>">
    <?= $lang_switch_flag_html ?>
    <span><?= $lang_switch_info['label'] ?></span>
  </button>
<?php endforeach; ?>
</div>
