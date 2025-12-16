<style>
    .category-btn {
        border: none;
        background: none;
        padding: 0;
        margin: 0;
        font: inherit;
        cursor: pointer;
        outline: none;

        color: #666;
        font-size: 1rem;
        font-weight: 500;
        text-align: center;
        text-transform: uppercase;

    }

    .category-btn.active {
        font-weight: bold;
        color: #1B9D7D;
    }

    .category-filter {
        display: inline-flex;
        gap: 30px;
        margin-bottom: 0px;
        position: absolute;
        padding-left: 53px;

    }
</style>

<div id="content" class="page page-community">
    <main class="page-content">
        <div class="container">
            <h2 class="primary-title">커뮤니티</h2>

            <div class="latest-posts">
                <div class="slider-wrap keen-slider">
                    <?php foreach ($best_list as $row) : ?>
                        <!-- 목록아이템 -->
                        <div class="keen-slider__slide">
                            <div class="card">
                                <p class="title">
                                    <em class="board-category <?= getLabel($row["bd_cate"]) ?>"><?= $row["bd_cate"] ?></em>
                                    <a href="/community/community_view/<?= $row["bd_idx"] ?>"><?= $row["bd_subject"] ?></a>
                                </p>
                                <div class="meta">
                                    <p class="ico-regdate"><?= get_yyyymmdd($row["reg_dt"]) ?></p>
                                    <p class="ico-viewing"><?= $row["bd_hit"] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <script>
                    window.addEventListener('DOMContentLoaded', function() {
                        if (typeof KeenSlider === undefined) return;

                        new KeenSlider(".latest-posts .slider-wrap", {
                            loop: true,
                            slides: {
                                perView: 4
                            },
                        }, [
                            addActiveClass,
                            sliderArrows
                        ]);
                    });
                </script>

            </div>

            <div class="board-wrap">
                <div class="board-list-top">
                    <div class="category-filter">
                        <button type="button" class="category-btn active" data-category="all">전체</button>
                        <button type="button" class="category-btn" data-category="업무">업무</button>
                        <button type="button" class="category-btn" data-category="일상">일상</button>
                        <button type="button" class="category-btn" data-category="질문">질문</button>
                        <button type="button" class="category-btn" data-category="자유">자유</button>
                    </div>
                    <div class="list-filter">
                        <!-- 활성화 항목 : on 클래스 추가 -->
                        <a role="button" href="/community/community_list?sort=reg_dt" class="btn <?= $sort == "reg_dt" ? "on" : "" ?>">최신순</a>
                        <a role="button" href="/community/community_list?sort=hit" class="btn <?= $sort == "hit" ? "on" : "" ?>">조회순</a>
                    </div>
                </div>

                <!-- 게시판 목록 -->
                <table class="board-list">
                    <colgroup>
                        <col>
                        <col style="width:72%;">
                        <col style="width:9%">
                        <col style="width:10%">
                    </colgroup>
                    <tbody>
                        <?php foreach ($result_list as $key => $row) : ?>
                            <tr class="board-row" data-category="<?= $row["bd_cate"] ?>">
                                <td class="no"><?= $start - $key ?></td>
                                <td class="title">
                                    <?php if ($row["bd_notice_yn"] == "Y") : ?>
                                        <em class="board-category">공지</em>
                                    <?php else: ?>
                                        <em class="board-category <?= getLabel($row["bd_cate"]) ?>"><?= $row["bd_cate"] ?></em>
                                    <?php endif; ?>
                                    <a href="/community/community_view/<?= $row["bd_idx"] ?>"><?= $row["bd_subject"] ?></a>
                                </td>
                                <td class="regdate"><?= get_yyyymmdd($row["reg_dt"]) ?></td>
                                <td class="viewing"><?= $row["bd_hit"] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- //게시판 목록 -->
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const categoryBtns = document.querySelectorAll('.category-btn');
                    const boardRows = document.querySelectorAll('.board-row');

                    categoryBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const selectedCategory = this.getAttribute('data-category');

                            // 모든 버튼에서 active 클래스 제거
                            categoryBtns.forEach(b => b.classList.remove('active'));
                            // 클릭된 버튼에 active 클래스 추가
                            this.classList.add('active');

                            // 테이블 행 필터링
                            boardRows.forEach(row => {
                                const rowCategory = row.getAttribute('data-category');

                                if (selectedCategory === 'all' || rowCategory === selectedCategory) {
                                    row.classList.remove('hidden');
                                } else {
                                    row.classList.add('hidden');
                                }
                            });
                        });
                    });
                });
            </script>
            <?= $pagging ?>
            <div class="form-action-wrap align-right">
                <a href="/community/community_write" class="btn-round btn-tertiary">작성하기</a>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->