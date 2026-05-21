<?= getCachCss(RES_PATH . "/css/resume_list.css") ?>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab3"; ?>
      <?php $tab_on2 = "menu3"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
      <div class="tab-panel on">
        <div class="recruit-bookmark">
          <h3 class="sr-text">제안보낸 인재</h3>
          <div class="result-wrap">
            <!-- 목록 -->
            <ol class="result-list">
              <?php foreach ($list as $row) : ?>
                <li class="list-item" data-ru_idx="<?= $row["ru_idx"] ?>">
                  <div class="item-info">
                    <div class="img">
                      <img src="<?= getResumeImg($row) ?>">
                      <button type="button" data-bk_type="RESUME" data-bk_key="<?= $row["ru_idx"] ?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?= $row["book_yn"] == "Y" ? "on" : "" ?>"><span class="sr-text">즐겨찾기 추가</span></button>
                      <p class="star-rating">
                        <span class="star" style="width:100%;"></span>
                        <span class="sr-text">평점</span>
                        <span class="sr-text">5</span>
                      </p>
                    </div>
                    <div class="desc">
                      <div class="content-wrap">
                        <div class="inline-group"><p class="category"><?= getWorkingImg($row["ru_work"]) ?></p></div>
                        <div class="title-wrap"><p class="title"><?= $row["ru_name"] ?></p></div>
                        <div class="meta">
                          <p class="national"><?= $row["mm_nation"] ?></p>
                          <p class="age"><?= getKoreanAge($row["mm_birth"]) ?>대</p>
                        </div>
                      </div>
                      <div class="price_type_wrap" style="width:290px;">
                        <span class="type price_type" data-work-type="<?= htmlspecialchars($row["ru_work_type"], ENT_QUOTES, 'UTF-8') ?>"></span>
                        <span class="area" data-addr="<?= htmlspecialchars($row["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"></span>
                        <span class="ru_price_type" data-price-type="<?= htmlspecialchars($row["ru_price_type"], ENT_QUOTES, 'UTF-8') ?>"></span>
                      </div>
                      <ul class="working-day">
                        <?= getWeekYoil($row["ru_week"]) ?>
                      </ul>
                    </div>
                    <div class="action-wrap">
                      <div class="form-action-wrap align-right">
                        <button type="button" aria-haspopup="dialog" class="btn-round btn-tertiary btnCancle"
                          data-ru_idx="<?= $row["ru_idx"] ?>"
                          data-rc_idx="<?= $row["rc_idx"] ?>"
                          data-target_id="<?= $row["target_id"] ?>"
                          data-user_id="<?= $row["user_id"] ?>"
                          data-target_img="<?= $row["target_img"] ?>"
                          data-user_img="<?= $row["user_img"] ?>"
                          data-target_name="<?= $row["target_name"] ?>"
                          data-user_name="<?= $row["user_name"] ?>"
                          data-subject="<?= $row["rc_name"] ?>">제안 취소</button>
                      </div>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ol>
            <!-- //목록 -->
          </div>
        </div>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<script>
  $(document).on("click", ".btnCancle", function(e) {
    e.stopPropagation();
    var ru_idx = $(this).data('ru_idx');

    openPopup('confirm', {
      content: '<em>제안</em>을 취소하시겠습니까?',
      secondCallback: function() {
        var params = {};
        params["ru_idx"] = ru_idx;
        params["rc_idx"] = $(this).data('rc_idx');

        comAjax("/compage_proc/resume_propose_cancle", params, function(ret) {
          if (ret.code == "00") {
            commonAlert("제안 취소 되었습니다.", function() {
              location.reload();
            });
          } else {
            console.log(ret);
            commonAlert("삭제처리시 오류가 발생하였습니다.", function() {});
          }
        });
      }
    });
  });

  $(document).on('click', '.desc', function() {
    var ruIdx = $(this).closest('.list-item').data('ru_idx');
    if (ruIdx) {
      var url = '/resume/hr_post/' + ruIdx;
      window.open(url, '_blank');
    }
  });

  $(document).ready(function() {
    // 주소 정리
    $('.area[data-addr]').each(function() {
      var $this = $(this);
      var originalAddr = $this.data('addr');
      if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
        var formattedAddr = fRemoveBigAdrJS(originalAddr);
        $this.text(formattedAddr);
      }
    });

    // 근무형태 정리
    $('.price_type[data-work-type]').each(function() {
      var $this = $(this);
      var workType = $this.data('work-type');
      if (workType && typeof getWorkTypeFirst === 'function') {
        var formattedWorkType = getWorkTypeFirst(workType);
        if (formattedWorkType) {
          $this.html(formattedWorkType);
        }
      }
    });

    // 급여형태 정리
    $('.ru_price_type[data-price-type]').each(function() {
      var $this = $(this);
      var priceType = $this.data('price-type');
      if (priceType && typeof getPriceTypeJSFirst === 'function') {
        var formattedPriceType = getPriceTypeJSFirst(priceType);
        if (formattedPriceType) {
          $this.html(formattedPriceType);
        }
      }
    });
  });
</script>
