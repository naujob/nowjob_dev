<div id="content" class="page page-event">
    <main class="page-content">

        <div class="container">
            <h2 class="primary-title">이벤트</h2>

            <div class="tabs-wrap">
                <ul class="tabs">
                    <!-- 활성화 항목 : on 클래스 추가 -->
                    <li class="<?=$date_status=="ing" ? "on":""?>">
                        <a href="/community/event_list?date_status=ing" class="tab"><span>진행중 이벤트</span></a>
                    </li>
                    <li class="<?=$date_status=="end" ? "on":""?>">
                        <a href="/community/event_list?date_status=end" class="tab"><span>종료 이벤트</span></a>
                    </li>
                </ul>
            </div>

            <div class="board-wrap">
                <ol class="event-list">
                    <?php foreach($result_list as $row) : ?>
                    <li>
                        <div class="thumb">
                            <a href="/community/event_view/<?=$row["bd_idx"]?>"><img src="<?=$row["bd_list_img1"]?>" alt=""></a>
                        </div>
                        <div class="meta">
                            <!-- 제목 -->
                            <p class="title"><a href="#"><?=$row["bd_subject"]?></a></p>
                            <!-- 요약내용 -->
                            <p class="summary"><?=$row["bd_subtext"]?></p>
                            <!-- 이벤트 기간 -->
                            <p class="ico-regdate"><?=substr($row["bd_sdate"],0,10)?>~<?=substr($row["bd_edate"],0,10)?></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ol>
            </div>
            <?=$pagging?>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
