<?php
// 플로팅 액션 그룹 맨 위 언어 전환 항목 (job_post.php, lang_switch.php 에서 include)
// 다른 floating-action-item 과 같은 태그(a)와 구조(아이콘 + span.action-label)로 만든다.
// 누르면 반대 언어로 바뀐다. 바뀐 뒤 모양은 <template class="lang-switch-alt"> 와 맞바꾼다 (m_lang_switch.js 의 flipSingleToggle)
$lang_switch_code = $lang_switch_codes[0];
$lang_switch_info = $lang_switch_registry[$lang_switch_code];
$lang_switch_flag_html = ($lang_switch_info['flag'] !== null && isset($lang_switch_flags[$lang_switch_info['flag']])) ? $lang_switch_flags[$lang_switch_info['flag']] : '';
$lang_switch_alt_info = $lang_switch_registry[$lang_switch_now];
$lang_switch_alt_flag = ($lang_switch_alt_info['flag'] !== null && isset($lang_switch_flags[$lang_switch_alt_info['flag']])) ? $lang_switch_flags[$lang_switch_alt_info['flag']] : '';
?>
<a href="#" class="floating-action-item lang-action-item" data-lang-value="<?= $lang_switch_code ?>" lang="<?= $lang_switch_info['lang_attr'] ?>" aria-pressed="false" data-count="1" data-alt-value="<?= $lang_switch_now ?>" data-alt-lang="<?= $lang_switch_alt_info['lang_attr'] ?>">
  <?= $lang_switch_flag_html ?>
  <span class="action-label"><?= $lang_switch_info['label'] ?></span>
</a>
<template class="lang-switch-alt"><?= $lang_switch_alt_flag ?><span class="action-label"><?= $lang_switch_alt_info['label'] ?></span></template>
