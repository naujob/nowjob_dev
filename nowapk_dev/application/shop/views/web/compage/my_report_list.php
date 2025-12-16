 <div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab9";?>
            <?php $tab_on2 = "menu4";?>
            <?php include_once (get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <h3 class="sr-text">문의하기</h3>
                <div class="board-wrap">
                    <div class="board-list-top">
                        <p class="total">전체<em><?=$result_count?></em>건</p>
                        <div class="list-filter">
                            <!-- 활성화 항목 : on 클래스 추가 -->
                            <a role="button" href="/mypage/qna_list?sort=reg_dt" class="btn <?=$sort=="reg_dt" ? "on":""?>">최신순</a>
                            <a role="button" href="/mypage/qna_list?sort=hit" class="btn <?=$sort=="hit" ? "on":""?>">조회순</a>
                        </div>
                    </div>

                    <!-- 게시판 목록 -->
                    <table class="board-list">
                        <colgroup><col> <col style="width:74%;"> <col style="width:15%"></colgroup>
                        <tbody>
                        	<?php foreach($result_list as $key=> $row) : ?>
                            <tr>
                                <td class="no"><?=$row["br_idx"]?></td>
                                <td class="title">
                                    <a href="/compage/my_report_view/<?=$row["br_idx"]?>"><?=$row["br_desc"]?></a>
                                </td>
                                <td class="regdate"><?=($row["reg_dt"])?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(count($result_list)==0) : ?>
                              <div class="is-empty-v2">
                                  <p>등록된 데이터가 없습니다.</p>
                              </div>
                            <?php endif; ?>

                        </tbody>
                    </table>
                    <!-- //게시판 목록 -->
                </div>
               	<?=$pagging?>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->
