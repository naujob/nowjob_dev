<?php
$banner_list = getBannerList("BN00010001");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<style>
  .search-result .content-area {
    margin: 0 10px;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
  }

  .search-result .sidebar {
    width: 200px;
    height: 400px;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 200px;
    flex: 0 0 200px;
  }

  .result-list {
    list-style: none;
    border-top: 2px solid #000;
    display: flex;
    width: 100%;
    padding: 24px 0 40px;
    flex-wrap: wrap;
    gap: 10px;
  }

  .result-list .list-item {
    position: relative;
    border: 1px solid #E9EAF4;
    width: 320px;
    height: 220px;
    border-radius: 8px;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    position: relative;
    padding: 10px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, .1);
  }

  .item-info {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    flex-direction: column;
    position: relative;
    padding: 0;
  }

  .item-info .content-wrap {
    padding: 8px 0 0 0;
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    height: 70px;
    text-align: center;
  }

  .item-info .comp-img {
    width: 150px;
    height: 150px;
    overflow: hidden;
    margin: 0 auto;
  }

  .item-info .comp-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }

  .item-info .comp_name {
    font-size: 20px;
    font-weight: 700;
  }

  .item-info .rt_cnt {
    font-weight: 500;
  }

  .item-info .rt_cnt span {
    font-weight: 700;
    color: #1B9D7D;
  }

  .top-recomm {
    list-style: none;
    display: flex;
    width: 100%;
    flex-wrap: wrap;
    gap: 16px;
  }

  .top-recomm .comp-info {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    position: relative;
    align-items: center;
    width: 220px;
    height: 275px;
    cursor: pointer;
  }

  .top-recomm .comp-info .comp-img {
    position: absolute;
    width: 220px;
    height: 220px;
    padding: 10px;
    overflow: hidden;
    top: 0;
    background-color: #fff;
    border: 1px solid #E9EAF4;
    border-radius: 8px;
  }

  .top-recomm .comp-info .comp-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }

  .top-recomm .comp-info .comp-name {
    position: absolute;
    bottom: 20px;
    width: 100%;
    height: 24px;
    font-size: 16px;
    font-weight: 700;
  }

  .recomm {
    position: relative;
    background-color: #fff;
    padding-bottom: 80px;
  }

  .bottom-recomm {
    list-style: none;
    display: flex;
    width: 100%;
    flex-wrap: wrap;
    gap: 23px;
    margin-top: 20px;
  }

  .bottom-recomm .comp-info {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    position: relative;
    align-items: center;
    width: 180px;
    height: 235px;
    cursor: pointer;
  }

  .bottom-recomm .comp-info .comp-img {
    position: absolute;
    width: 180px;
    height: 180px;
    padding: 10px;
    overflow: hidden;
    top: 0;
    background-color: #fff;
    border: 1px solid #E9EAF4;
    border-radius: 8px;
  }

  .bottom-recomm .comp-info .comp-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }

  .bottom-recomm .comp-info .comp-name {
    position: absolute;
    bottom: 20px;
    width: 100%;
    height: 24px;
    font-size: 16px;
    font-weight: 700;
  }

  .recomm-slider {
    padding: 0 0 50px 0;
  }

  .page-content {
    position: relative;
    padding: 120px 0 0 0;
  }
</style>

<div id="content" class="page page-recruit">
  <main class="page-content">
    <div class="recomm-slider">
      <div class="container">
        <h2 class="primary-title">지금 뜨는 업체</h2>
        <div class="top-recomm">
          <?php
          $count = 0;
          foreach ($up_list as $row) :
            if ($count >= 6) break;
          ?>
            <div class="comp-info" data-comm_idx="<?= $row["mm_idx"] ?>">
              <div class="comp-img">
                <img src="<?= getCompImg($row) ?>">
              </div>
              <p class="comp-name"><?= $row["mm_comp_name"] ?></p>
            </div>
          <?php
            $count++;
          endforeach;
          ?>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="search-result">
          <div class="sidebar"></div>
          <div class="content-area">
            <div class="result-wrap">
              <div class="result-header">
                <p class="total">총 <em id="total_count"><?= count($all_list) ?></em>건</p>
              </div>
              <ol class="result-list" id="ajaxList" data-page="1" style="min-height:400px;">
                <?php foreach ($all_list as $row) : ?>
                  <li class="list-item item-hover" data-comm_idx="<?= $row["mm_idx"] ?>">
                    <div class="item-info">
                      <div class="comp-img">
                        <img src="<?= getCompImg($row) ?>" onError="this.src='/resource/shop/mobile/img/common/noimage4.png'">
                      </div>
                      <div class="content-wrap ">
                        <p class="comp_name"><?= $row["mm_comp_name"] ?></p>
                        <p class="rt_cnt">채용공고 <span><?= $row["rt_ing_cnt"] ?></span>건</p>
                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ol>
            </div><!-- //result-wrap -->
          </div><!-- //content-area -->
          <div class="sidebar"></div>
        </div><!-- //search-result -->
      </div><!-- //container -->
    </div><!-- //section -->

    <div class="recomm">
      <div class="container">
        <h3>추천 업체</h3>
        <div class="bottom-recomm">
          <?php
          $count = 0;
          foreach ($up_list2 as $row) :
            if ($count >= 7) break;
          ?>
            <div class="comp-info" data-comm_idx="<?= $row["mm_idx"] ?>">
              <div class="comp-img">
                <img src="<?= getCompImg($row) ?>">
              </div>
              <p class="comp-name"><?= $row["mm_comp_name"] ?></p>
            </div>
          <?php
            $count++;
          endforeach;
          ?>
        </div>
      </div>
    </div>

  </main><!-- //page-content -->
</div><!-- //page -->
<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />
<script>
  $(document).on('click', '.comp-info,.list-item', function() {
    var commIdx = $(this).data('comm_idx');
    console.log(commIdx);
    if (commIdx) {
      var url = '/recruit/company/' + commIdx;
      window.open(url, '_blank');
    }
  });
  $("#btnRegist").click(function() {
    if ($("#is_login").val() != "Y") {
      commonAlert("로그인후 이용해주세요!", function() {
        location.href = '/member/login';
      });
      return;
    }

    if ($("[name='mm_type']").val() != "MEM") {
      commonAlert("일반회원만 이력서 작성이 가능합니다.", function() {});
      return;
    }

    location.href = "/mypage/resume_list";
  });
</script>