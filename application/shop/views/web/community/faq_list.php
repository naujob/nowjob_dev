<div id="content" class="page page-faq">
    <main class="page-content">
        <div class="container">
            <h2 class="primary-title">FAQ</h2>

            <div class="tabs-wrap">
                <ul class="tabs">
                    <?php foreach($category_list as $cate) : ?>
                        <li class="<?=str_replace("/","",$bd_cate)==str_replace("/","",$cate) ? "on":"" ?>"><a href="/community/faq_list?bd_cate=<?=$cate?>" class="tab"><?=$cate?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- 탭 내용 -->
            <div class="tab-panel on">
                <!-- 목록 -->
                <ol class="faq-list">
                    <?php foreach($result_list as $row) : ?>
                    <li class="list-item">
                        <a href="#" aria-expanded="false" class="accordion-title js-toggle-accordion"><?=$row["bd_subject"]?></a>
                        <div class="accordion-content" hidden>
                           <?=str_replace("\n","<br/>",$row["bd_content"])?>
                        </div>
                    </li>
                    <?php endforeach; ?>

                </ol>
			 <?=$pagging?>
            </div>
            <!-- //탭 내용 -->

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->
