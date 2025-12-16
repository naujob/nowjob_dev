 <div id="content" class="page page-mypage">
     <main class="page-content">
         <div class="container">
             <?php $tab_on = "tab9"; ?>
             <?php $tab_on2 = "menu3"; ?>
             <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
             <div class="tab-panel on">
                 <h3 class="sr-text">문의하기</h3>
                 <div class="board-wrap">
                     <div class="board-list-top">
                         <p class="total">전체<em><?= $result_count ?></em>건</p>
                         <div class="list-filter">
                             <!-- 활성화 항목 : on 클래스 추가 -->
                             <a role="button" href="/compage/qna_list?sort=reg_dt" class="btn <?= $sort == "reg_dt" ? "on" : "" ?>">최신순</a>
                             <a role="button" href="/compage/qna_list?sort=hit" class="btn <?= $sort == "hit" ? "on" : "" ?>">조회순</a>
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
                                 <tr>
                                     <td class="no"><?= $row["bd_idx"] ?></td>
                                     <td class="title">
                                         <em class="board-category <?= getLabel($row["bd_cate"]) ?>"><?= $row["bd_cate"] ?></em>
                                         <a href="/compage/qna_view/<?= $row["bd_idx"] ?>"><?= $row["bd_subject"] ?></a>
                                     </td>
                                     <td class="status">
                                         <em class="board-category <?= $row["bd_aws_yn"] == "N" ? "dimmed" : "" ?>"><?= $row["bd_aws_yn"] == "N" ? "미답변" : "답변완료" ?></em>
                                     </td>
                                     <td class="regdate"><?= get_yyyymmdd($row["reg_dt"]) ?></td>
                                 </tr>
                             <?php endforeach; ?>
                         </tbody>
                     </table>
                     <!-- //게시판 목록 -->
                 </div>
                 <?= $pagging ?>
                 <div class="form-action-wrap align-right">
                     <a href="/compage/qna_write" class="btn-round btn-tertiary">작성하기</a>
                 </div>
             </div>
         </div>
     </main><!-- //page-content -->
 </div><!-- //page -->