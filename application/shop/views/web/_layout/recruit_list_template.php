<?php
/* 채용공고 리스트 템플릿 파라미터 (include 전에 설정 가능)
 * $tpl_id                : template 요소 id (기본: 'tpl_list')
 * $tpl_item_hover        : li에 item-hover 클래스 추가 여부 (기본: false)
 * $tpl_show_timestamp    : timestamp 영역 표시 여부 (기본: true)
 * $tpl_show_recomm_badge : 추천 배지 표시 여부 (기본: true)
 */
$tpl_id                = isset($tpl_id)                ? $tpl_id                : 'tpl_list';
$tpl_item_hover        = isset($tpl_item_hover)        ? $tpl_item_hover        : false;
$tpl_show_timestamp    = isset($tpl_show_timestamp)    ? $tpl_show_timestamp    : true;
$tpl_show_recomm_badge = isset($tpl_show_recomm_badge) ? $tpl_show_recomm_badge : true;
?>
<!-- 채용공고 리스트 템플릿 (job_list.php, company.php, search.php에서 공유) -->
<template id="<?= $tpl_id ?>">
  <li class="list-item<?= $tpl_item_hover ? ' item-hover' : '' ?>" data-rc_idx="{{rc_idx}}">
    <div class="content-li">
      <div class="content-info">
        <div class="content-body">
          <div class="img">
            <img src="{{rt_recruit_img}}">
            <?php if ($mm_type === "MEM") { ?><button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
            <?php if ($tpl_show_recomm_badge) { ?><p class="recomm-badge"><span>추천</span></p><?php } ?>
          </div>
          <div class="desc">
            <div class="desc1">
              <?php if ($tpl_show_timestamp) { ?><div class="timestamp">{{timestamp}}</div><?php } ?>
              <div class="wicon">{{rt_work_img}}</div>
              <div class="title ellipsis">{{rc_name}}</div>
              <div class="summary">
                <p>{{rc_nation_nm}}</p>
                <p>{{rc_age_nm}}</p>
              </div>
            </div>
            <div class="desc2">
              <div class="summary wtype">
                {{rt_work_type}}
              </div>
              <p class="area">{{ad_addr}}</p>
            </div>
            <div class="desc3">
              <div class="summary">
                <p class="period">{{rc_price_type}}</p>
              </div>
              <p class="amount">{{rc_price_display}}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </li>
</template>
<?php
/* 사용한 파라미터 변수 정리 (후속 include 오염 방지) */
unset($tpl_id, $tpl_item_hover, $tpl_show_timestamp, $tpl_show_recomm_badge);
?>
