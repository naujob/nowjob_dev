<div class="page page-mypage-view page-coupon-list">
  <div class="top-bar">
    <a href="/compage/index" class="btn-back"><span class="sr-text">이전으로</span></a>
    <h1 class="title">쿠폰 관리</h1>
  </div><!-- //top-bar -->

  <div class="page-content">

    <div class="tab-container">
      <ul class="tab-list">
        <li <?php if ($_GET[gbn] == 'N') {
              echo "class=\"on\"";
            } ?>><a href="javascript:_replace('/compage/coupon_list?gbn=N');" class="tab">사용가능한 쿠폰</a></li>
        <li <?php if ($_GET[gbn] == 'Y') {
              echo "class=\"on\"";
            } ?>><a href="javascript:_replace('/compage/coupon_list?gbn=Y');" class="tab">사용만료 쿠폰</a></li>
      </ul>
    </div>

    <div class="section my-bookmark">
      <div class="container">
        <p class="result-summary">
          보유쿠폰 <em><?= $coupon_count ?></em>건
        </p>
        <div class="result-wrap">
          <!-- 목록 -->
          <ol class="result-list">
            <?php foreach ($coupon_list as $row) : ?>
              <li>
                <div class="coupon">
                  <div class="content-wrap">
                    <p class="title"><strong><?= $row["cp_name"] ?></strong></p>
                    <p class="benefit"><em><?= $row["cl_price"] ?></em><?= $row["cl_dan"] == "won" ? "원" : "%" ?></p>
                    <p class="date">사용기간 : <em><?= str_replace("-", ".", substr($row["cl_use_sdate"], 0, 10)) ?>~<?= str_replace("-", ".", substr($row["cl_use_edate"], 0, 10)) ?></em></p>
                    <p class="date" style="color:#aaa;">최소사용금액 : <em><?= $row["cl_limit_price"] ?></em></p>
                    <?php if ($row["cl_use_yn"] == "Y") { ?>
                      <a href="" class="coupon-status-btn">사용완료</a>
                    <?php } else if ($row["cl_use_edate"] < date("Y-m-d H:i:s")) { ?>
                      <a href="" class="coupon-status-btn">기간만료</a>
                    <?php } else { ?>
                      <a href="javascript:_replace('/compage/point_charge_step2/<?= $row["cl_idx"] ?>');" onclick="/* return fGoPointPay('<?= $row["cl_price"] ?>','<?= $row["cl_dan"] ?>'); */" class="coupon-status-btn">사용하기</a>
                    <?php } ?>
                  </div>
                  <?php if ($row["cl_use_yn"] == "Y") { ?>
                    <div class="coupon-overlay">사용 쿠폰</div>
                  <?php } else if ($row["cl_use_edate"] < date("Y-m-d H:i:s")) { ?>
                    <div class="coupon-overlay">기간 만료</div>
                  <?php } ?>
                </div>
              </li>
            <?php endforeach; ?>

          </ol>
          <!-- //목록 -->
        </div>
      </div>
    </div>
  </div><!-- //page-content -->
</div>

<script>
  function fGoPointPay(cl_price, cl_dan) {

    if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {
      // 아이폰일 경우 location.href로 이동
      location.href = "/compage/point_charge_step2";
      return false;
    }

    var params = {};
    if (cl_dan == 'won') {
      if (cl_price < 0 || cl_price > 999999999) {
        alert('금액이 맞지 않습니다.');
        return false;
      }
      if (cl_price < 2000) {
        params["point_key"] = "P001";
        params["point_price"] = "1000";
      } else if (cl_price < 5000) {
        params["point_key"] = "P002";
        params["point_price"] = "2000";
      } else if (cl_price < 10000) {
        params["point_key"] = "P003";
        params["point_price"] = "5000";
      } else if (cl_price < 50000) {
        params["point_key"] = "P004";
        params["point_price"] = "10000";
      } else if (cl_price < 100000) {
        params["point_key"] = "P005";
        params["point_price"] = "50000";
      } else {
        params["point_key"] = "P006";
        params["point_price"] = "100000";
      }
      //alert('1');
      comAjax("/mypage_proc/point_charge_step1", params, function(ret) {
        //alert('ret:'+ret.code);
        if (ret.code == "00") {
          location.href = "/compage/point_charge_step2";
          return false;
        } else {
          commonAlert(ret.msg, function() {});
          return false;
        }
      });
    } else {
      alert('단위가 맞지 않습니다.');
      return false;
    }

  }
</script>