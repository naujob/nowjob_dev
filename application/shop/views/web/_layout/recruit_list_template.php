<!-- 채용공고 리스트 템플릿 (job_list.php, company.php에서 공유) -->
<template id="tpl_list">
  <li class="list-item" data-rc_idx="{{rc_idx}}">
    <div class="content-li">
      <div class="content-info">
        <div class="content-body">
          <div class="img">
            <img src="{{rt_recruit_img}}">
            <?php if ($mm_type === "MEM") { ?><button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button><?php } ?>
            <p class="recomm-badge"><span>추천</span></p>
          </div>
          <div class="desc">
            <div class="desc1">
              <div class="timestamp">{{timestamp}}</div>
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
