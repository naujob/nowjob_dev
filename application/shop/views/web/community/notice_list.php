 <div id="content" class="page page-notice">
    <main class="page-content">

        <div class="container">
            <h2 class="primary-title">공지사항</h2>

            <div class="board-wrap">
                <div class="board-list-top">
                    <p class="total">전체<em><?=$result_count?></em>건</p>
                    <div class="list-filter">
                        <!-- 활성화 항목 : on 클래스 추가 -->
                        <a role="button" href="/community/notice_list?sort=reg_dt" class="btn <?=$sort=="reg_dt" ? "on":""?>">최신순</a>
                            <a role="button" href="/community/notice_list?sort=hit" class="btn <?=$sort=="hit" ? "on":""?>">조회순</a>
                    </div>
                </div>

                <!-- 게시판 목록 -->
                <table class="board-list">
                    <colgroup><col> <col style="width:72%;"> <col style="width:9%"> <col style="width:10%"></colgroup>
                    <tbody>
                        <?php foreach($result_list as $key=> $row) : ?>
                        <tr>
                            <td class="no"><?=$start - $key?></td>
                            <td class="title">
                            	<?php if($row["bd_notice_yn"]=="Y") : ?>
                                <em class="board-category">공지</em>
                                <?php else :?>
                                	
                                <?php endif; ?>
                                <a href="/community/notice_view/<?=$row["bd_idx"]?>"><?=$row["bd_subject"]?></a>
                            </td>
                            <td class="regdate"><?=substr($row["reg_dt"],0,10)?></td>
                            <td class="viewing"><?=$row["bd_hit"]?></td>
                        </tr>
                        <?php endforeach; ?>
                       
                    </tbody>
                </table>
                <!-- //게시판 목록 -->
            </div>

            <?=$pagging?>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->