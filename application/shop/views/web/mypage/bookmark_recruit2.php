<?php

?>
<style>
  .btn-bookmark {
    display: block;
    position: absolute;
    top: 0px;
    right: 0px;
    width: 40px;
    height: 40px;
    border: 0;
    background: url("https://nowjob.co.kr/resource/shop/web/img/ic_bookmark_on.svg") no-repeat 0 0;
    background-size: 0px;
    pointer-events: none;
    z-index: 1;
  }

  .btn-bookmark:before {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    border-top: 40px solid #f8f8f8;
    border-left: 40px solid transparent;
    z-index: -1;
  }

  .btn-bookmark:after {
    content: "";
    display: block;
    position: absolute;
    top: 3px;
    right: 3px;
    width: 16px;
    height: 16px;
    background: url("https://nowjob.co.kr/resource/shop/web/img/ic_bookmark_off.svg") no-repeat 0 0;
    background-size: contain;
    pointer-events: all;
    z-index: 1;
  }

  .btn-bookmark.on:after {
    background-image: inherit;
  }

  .result-list {
    margin-top: 20px;
  }

  .result-list .content-li {
    position: relative;
    width: 100%;
    height: 120px;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
    border: 0px;
    overflow: hidden;
    transition: background 0.2s ease;
    cursor: pointer;
  }

  .result-list .content-li:hover {
    background: #f8f8f8;
  }

  .result-list .content-li .content-info {
    width: 100%;
    height: 100%;
    padding: 5px;
  }

  .result-list .content-info .content-body {
    position: relative;
    overflow: hidden;
    display: flex;
    height: 100%;
    gap: 10px;
  }

  .result-list .content-info .img {
    position: relative;
    width: 110px;
    height: 110px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  .result-list .content-info .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .result-list .content-info .desc {
    position: relative;
    display: flex;
    height: 100%;
    gap: 10px;
  }

  .result-list .content-info .desc .desc1 {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 600px;
    justify-content: center;
  }

  .result-list .content-info .desc .desc1 .wicon img {
    height: 26px;
  }

  .result-list .content-info .desc .desc1 .title {
    font-size: 20px;
    font-weight: 600;
  }

  .result-list .content-info .desc .desc1 .ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .result-list .content-info .summary {
    display: flex;
    font-size: 15px;
    gap: 5px;
    position: relative;
  }

  .result-list .content-info .summary p {
    position: relative;
    padding-right: 5px;
    margin: 0;
    font-size: 16px;
    color: #333;
  }

  .result-list .content-info .summary p:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #ACACAC;
  }

  .result-list .content-info .summary em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .result-list .content-info .summary em:not(:last-child) {
    padding-right: 5px;
  }

  .result-list .content-info .summary em.on {
    color: #1b9d7d;
  }

  .result-list .content-info .summary em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777;
  }

  .result-list .content-info .desc .desc2 {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 300px;
    align-items: center;
    justify-content: center;
  }

  .result-list .content-info .desc .desc2 p.area {
    color: #000;
    font-size: 18px;
    font-weight: 600;
  }

  .result-list .content-info .desc .desc2 .wtype {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-weight: 600;
  }

  .result-list .content-info .desc .desc2 .wtype em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #333;
  }

  .result-list .content-info .desc .desc2 .wtype em:not(:last-child) {
    padding-right: 5px;
  }

  .result-list .content-info .desc .desc2 .wtype em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777;
  }

  .result-list .content-info .desc .desc2 .wtype em.wtype1 {
    color: #3a70db;
  }

  .result-list .content-info .desc .desc2 .wtype em.wtype2 {
    color: #F86048;
  }

  .result-list .content-info .desc .desc2 .wtype em.wtype3 {
    color: #1B9D7D;
  }


  .result-list .content-info .desc .desc3 {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 250px;
    align-items: flex-end;
    justify-content: center;
  }

  .result-list .content-info .desc .desc3 p {
    margin: 0;
    text-align: right;
  }

  .result-list .content-info .desc .desc3 .period {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;
  }

  .result-list .content-info .desc .desc3 .amount {
    font-weight: 600;
    font-size: 22px;
    color: #1b9d7d;
  }

  .result-list .content-info .desc .desc3 .amount em {
    font-style: normal;
  }

  .result-list .content-info .desc .desc3 .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .result-list .content-info .desc .desc3 .period em:not(:last-child) {
    padding-right: 5px;
  }

  .result-list .content-info .desc .desc3 .period em.on {
    color: #1b9d7d;
  }

  .result-list .content-info .desc .desc3 .period em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777;
  }

  .page-mypage .result-list .selection {
    padding: 0px;
  }

  .result-list .content-info .selection {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100px;
    align-items: center;
    justify-content: center;
  }

  .page-mypage .result-list .selection .label-red:before {
    width: 35px;
    height: 35px;
  }

  .page-mypage .result-list .selection .label-red:after {
    width: 35px;
    height: 35px;
  }
</style>
<div id="content" class="page page-mypage">
  <main class="page-content">
    <div class="container">
      <?php $tab_on = "tab3"; ?>
      <?php $tab_on2 = "menu3"; ?>
      <?php include_once(get_base_url() . '/application/shop/views/web/mypage/top_line_2025.php'); ?>
      <div class="tab-panel on">
        <div class="recruit-bookmark">
          <h3 class="sr-text">제안받은 지원공고</h3>
          <div class="result-wrap">
            <ol class="result-list">
              <?php foreach ($list as $row) : ?>
                <li class="list-item" data-rc_idx="<?= $row["rc_idx"] ?>">
                  <div class="content-li">
                    <div class="content-info">
                      <div class="content-body">
                        <div class="img">
                          <img src="<?= getRecruitImg($row) ?>">
                          <button type="button" data-bk_type="RECRUIT" data-bk_key="<?= $row["rc_idx"] ?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?= ($row["book_yn"] == "Y" ? "on" : "") ?>"><span class="sr-text">즐겨찾기 추가</span></button>
                        </div>
                        <div class="desc">

                          <div class="desc1">
                            <div class="wicon"><?= getWorkingImg($row["rc_work"]) ?></div>
                            <div class="title ellipsis"><?= $row["rc_name"] ?></div>
                            <div class="summary">
                              <p><?= $row["rc_nation_nm"] ?></p>
                              <p><?= $row["rc_age_nm"] ?></p>
                            </div>
                          </div>

                          <div class="desc2">
                            <div class="summary">
                              <p class="wtype"><?= getWorkType2025($row["rc_work_type"]) ?></p>
                            </div>
                            <!-- PHP 함수 주석처리: <p class="area"><?= fRemoveBigAdr($row["ad_addr"]) ?></p> -->
                            <p class="area" data-addr="<?= htmlspecialchars($row["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"></p>
                          </div>

                          <div class="desc3">
                            <div class="summary">
                              <p class="period"><?= getConvertPriceType($row["rc_price_type"], $row["rc_price_rep"]) ?></p>
                            </div>
                            <p class="amount"><?= number_format($row["rc_price_pay"]) ?> 원</p>
                          </div>
                        </div>

                        <div class="selection">
                          <label class="form-checkbox">
                            <input type="checkbox" name="chk[]" value="<?= $row["rc_idx"] ?>" class="sr-text">
                            <span class="label-red"></span>
                            <span class="sr-text">지원하기 선택</span>
                          </label>
                        </div>

                      </div>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ol>
            <!-- //목록 -->
          </div>
          <div class="form-action-wrap align-right">
            <button type="button" id="btnRequest" aria-haspopup="dialog" class="btn-round btn-tertiary">지원하기</button>
          </div>
        </div>
      </div>
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->
<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<script>
  $("#btnRequest").click(function() {
    //로그인 여부 확인
    if ($("#is_login").val() != "Y") {
      commonLogin();
      return;
    }

    //이력서 여부 확인
    if ($("[name=resume_yn]").val() == "N") {
      commonAlert("이력서 작성및 공개이력서 설정후 지원해주세요.", function() {});
      return;
    }

    var mq_idxs = new Array();

    for (var icnt = 0; icnt < $("[name='chk[]']:checked").length; icnt++) {
      mq_idxs.push($("[name='chk[]']:checked").eq(icnt).val());
    }

    if (mq_idxs.length == 0) {
      commonAlert("지원하실 공고를 선택해주세요.", function() {});
      return;
    }

    openPopup('confirm', {
      content: '<em>선택한</em> 공고에<br> 지원하시겠습니까?',
      secondCallback: function() {
        var params = {};
        params["mq_idxs"] = mq_idxs;
        params["mq_type"] = "RECRUIT";
        comAjax("/common_proc/member_request", params, function(ret) {
          if (ret.code == "00") {
            commonAlert("지원 신청 되었습니다.", function() {
              $("[name='chk[]']").prop("checked", false);
            });
          } else {
            commonAlert(ret.msg, function() {});
            return;
          }
        });
      }
    });
  });
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.list-item').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.open(url, '_blank');
    }
  });

  // 주소 변환: fRemoveBigAdrJS 함수 사용
  $(document).ready(function() {
    $('.area[data-addr]').each(function() {
      var originalAddr = $(this).attr('data-addr');
      if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
        var convertedAddr = fRemoveBigAdrJS(originalAddr);
        $(this).text(convertedAddr);
      }
    });
  });
</script>