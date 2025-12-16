<div class="page page-tutorial" style="position: relative;">
    <a href="javascript:void(0);" class="btnX" id="btnX" style="position: absolute; top: 20px; left: 20px; z-index: 999; display: flex; align-items: center; justify-content: center; min-width: 44px; min-height: 44px; padding: 10px;">
        <img src="/resource/shop/mobile/img/ic_alert_close.svg" />
    </a>
    <!-- 개인회원 튜토리얼 -->
    <div class="tutorial-slider">
        <div class="slider-wrap keen-slider">
            <div class="keen-slider__slide">
                <img src="/resource/shop/mobile/img/user/tutorial1.png" alt="내 위치 기준 인재 탐색">
            </div>
            <div class="keen-slider__slide">
                <img src="/resource/shop/mobile/img/user/tutorial2.png" alt="인재목록 별점 시스템">
            </div>
            <div class="keen-slider__slide">
                <img src="/resource/shop/mobile/img/user/tutorial3.png" alt="포인트 광고 노출 시스템">
            </div>
        </div>
    </div>
    <script>
        //튜토리얼 슬라이더 생성
        document.addEventListener('DOMContentLoaded', function() {
            new KeenSlider(".tutorial-slider .slider-wrap", {
                loop: false,
            }, [
                sliderDots,
                addActiveClass
            ]);
        });
    </script>
    <div class="sticky-bottom">
        <a href="javascript:void(0);" id="btnStart" class="btn btn-primary">시작하기</a>
    </div>
</div>
<script>
    window.addEventListener('load', function() {
        // 뒤로가기 막기 - 히스토리에 현재 페이지 추가
        window.history.pushState(null, null, location.href);

        // 뒤로가기 시 현재 페이지로 다시 이동
        window.addEventListener('popstate', function() {
            window.history.pushState(null, null, location.href);
        });

        // X 버튼 클릭 시 join_start로 이동
        $(".btnX").click(function() {
            location.replace("/member/join_start");
        });

        // 시작하기 버튼 클릭 → 정상 다음 단계 이동
        $("#btnStart").click(function() {
            location.href = "/member/join_mem_step1";
        });
    });

    // 스와이프 뒤로가기 비활성화 (이 페이지에서는 스와이프로 뒤로가기 불가)
    window.DISABLE_SWIPE_BACK = true;
</script>

<?php $join_type = "member"; ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/member/join_pass.php'); ?>