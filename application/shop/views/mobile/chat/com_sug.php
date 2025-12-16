<div class="page page-chat">
    <div class="header">
        <h1 class="title">채팅방</h1>
    </div><!-- //header -->

    <div class="page-content">

        <div class="tab-container">
            <ul class="tab-list">
                <li class="on">
                    <a href="#" class="tab">
                        제안보낸 인재

                    </a>
                </li>
                <li>
                    <a href="#" class="tab">제안받은 인재</a>
                </li>
            </ul>
        </div>

        <style>
            /* 탭 배지 스타일 */
            .tab-list .tab {
                position: relative;
                display: inline-block;
            }
        </style>

        <div class="tab-panel">
            <div class="container">
                <!-- 컨텐츠 영역 -->
            </div>
        </div>

    </div><!-- //page-content -->

    <!-- bottom-nav -->
    <?php $select_menu = "chat"; ?>
    <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
    <!-- //bottom-nav -->
</div>

<script>
    // 페이지 로드 시 탭 초기화 및 "제안보낸 인재" 탭 자동 활성화
    document.addEventListener('DOMContentLoaded', function() {
        var tabs = document.querySelectorAll('.tab-list .tab');

        // 탭 클릭 이벤트 처리
        tabs.forEach(function(tab) {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                // 모든 탭에서 'on' 클래스 제거
                var allTabs = document.querySelectorAll('.tab-list li');
                allTabs.forEach(function(li) {
                    li.classList.remove('on');
                });

                // 클릭한 탭의 부모 li에 'on' 클래스 추가
                this.parentElement.classList.add('on');

                // 탭별 컨텐츠 처리 (필요시 추가)
                var tabText = this.textContent.trim();
                if (tabText === '제안보낸 인재') {
                    // 제안보낸 인재 컨텐츠 로드
                    console.log('제안보낸 인재 탭 활성화');
                } else if (tabText === '제안받은 인재') {
                    // 제안받은 인재 컨텐츠 로드
                    console.log('제안받은 인재 탭 활성화');
                }
            });
        });

        // 페이지 로드 시 "제안보낸 인재" 탭 자동 클릭
        var firstTab = document.querySelector('.tab-list li:first-child .tab');
        if (firstTab) {
            // 탭 클릭 이벤트 트리거
            firstTab.click();
        }
    });
</script>