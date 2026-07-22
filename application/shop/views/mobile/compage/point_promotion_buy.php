<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="/compage/index" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">포인트 충전 · 광고 구매 / 관리</h1>
    </div><!-- //top-bar -->

    <div class="page-content">
        <?php $tab_on = "tab4"; ?>
        <?php include_once(get_base_url() . '/application/shop/views/mobile/compage/top_line.php'); ?>
        <div class="tab-panel">
            <div class="section purchage-promo">
                <div class="container">
                    <h2 class="sr-text">광고구매</h3>
                        <ul class="promo-list">
                            <?php foreach ($promotion_list as $row) : ?>
                                <li>
                                    <div class="promo-card">
                                        <div class="card-body">
                                            <p class="title"><strong><?= $row["cd_val"] ?></strong></p>
                                            <?php if ($row["cd_key"] == "C001") { ?>
                                                <p class="title" style="font-size: 0.9rem; font-weight: 100;">채용정보 상단 추천공고란에 노출됩니다</p>
                                            <?php } else if ($row["cd_key"] == "C002") { ?>
                                                <p class="title" style="font-size: 0.9rem; font-weight: 100;">채용정보 채용공고를 4시간마다 채용정보 리스트 상위에 노출 시킵니다.</p>
                                            <?php } else if ($row["cd_key"] == "C003") { ?>
                                                <p class="title" style="font-size: 0.9rem; font-weight: 100;">채용정보 채용공고를 8시간마다 채용정보 리스트 상위에 노출 시킵니다. </p>
                                            <?php } else if ($row["cd_key"] == "C004") { ?>
                                                <p class="title" style="font-size: 0.9rem; font-weight: 100;">홈화면 지금뜨는 업체란에 노출시킵니다.</p>
                                            <?php } ?>
                                            <?php if ($row["cd_val2"] != "") : ?>
                                                <!-- <p><?= $row["cd_val2"] ?></p> -->
                                            <?php endif; ?>
                                            <p class="required-point">차감포인트 : <em><?= $row["cd_val3"] ?></em>P</p>
                                        </div>
                                        <div class="card-footer">
                                            <a href="javascript:void(0);" data-code="<?= $row["cd_key"] ?>" data-point="<?= $row["cd_val3"] ?>" class="btnBuy btn btn-tertiary">구매</a>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="help-msg">메인배너 광고건은 나우잡 고객센터 032-833-0018 에 문의바랍니다<br>(별도협의필요)</p>
                </div>
            </div>
        </div>
    </div><!-- //page-content -->
</div>
<input type="hidden" name="mm_point" value="<?= $mem_info["mm_point"] ?>" />

<script>
    window.addEventListener('DOMContentLoaded', function() {
        $(".btnBuy").click(function() {
            var code = $(this).attr("data-code");
            var point = $(this).attr("data-point");
            var mm_point = $("[name=mm_point]").val();

            if (parseInt(point) > parseInt(mm_point)) {
                commonAlert("보유하신 포인트가 부족합니다. 보유포인트 : " + mm_point.format(), function() {});
                return;
            }

            //_replace("/compage/point_promotion_payment/" + code);
            location.href = "/compage/point_promotion_payment/" + code;
        });
    });
</script>