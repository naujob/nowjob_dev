<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">포인트 충전 · 광고 구매 / 관리</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="join-welcome">
            <div class="container">
                <h2 class="primary-title">포인트 충전이 완료 되었습니다.</h2>
                <p>충전 금액 : <?=number_format($point_info["cd_val2"]) . "원"?></p>
            </div>
        </div>
        <div class="form-action-wrap">
            <a href="/mypage/point_charge_list" class="btn-round btn-tertiary">충전내역 확인</a>
        </div>
    </div><!-- //page-content -->
</div>
