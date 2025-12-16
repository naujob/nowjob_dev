<div class="page page-tutorial">
    <!-- 기업회원 튜토리얼 -->
    <div class="tutorial-slider">
        <div class="slider-wrap keen-slider">
            <div class="keen-slider__slide">
                <img src="/resource/shop/mobile/img/employer/tutorial1.png" alt="내 위치 기준 인재 탐색">
            </div>
            <div class="keen-slider__slide">
                <img src="/resource/shop/mobile/img/employer/tutorial2.png" alt="인재목록 별점 시스템">
            </div>
            <div class="keen-slider__slide">
                <img src="/resource/shop/mobile/img/employer/tutorial3.png" alt="포인트 광고 노출 시스템">
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
    $(document).ready(function() {
        $("#btnStart").click(function() {
            location.href = "/";
        });
    });
</script>