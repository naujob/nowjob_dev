<div class="page page-settings-view">

    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">회원탈퇴</h1>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="leave-form">
            <div class="container">
                <div class="logo"><img src="/resource/shop/mobile/img/title_logo_dark.png" width="176" height="58" alt=""></div>

                <p class="primary-title">회원탈퇴에 앞서서<br> <em>유의사항 및 안내</em>를 반드시 읽고<br> 진행해주세요!</p>

                <div class="desc">
                    <p>탈퇴후, 회원님이 등록하신 개인 정보 및 서비스 이용기록이 모두 삭제 되며 삭제된 데이터는 복구되지 않습니다.</p>
                    <p>삭제되는 내용은 어디까지나 회원님이 가입시에 사용된 내용이며 서비스 이용 중 올리신 게시물(채팅기록, 게시물 등)은 보존됩니다.</p>
                    <p>탈퇴 후 회원님의 구매하셨던 포인트(P)는 소멸되므로 유의하여 주시길 바랍니다. 탈퇴하신 후 잔여 포인트에 대한 환불은 불가합니다.</p>
                </div>

                <div class="sticky-bottom view-footer">
                    <button type="button" id="btnNext" class="btn btn-primary">다음</button>
                </div>
            </div>
        </div>
    </div><!-- //page-content -->
</div>

<script>
$(document).ready(function(){
	$("#btnNext").click(function(){
        location.href="/setting/fire_step2";
	});
});
</script>
