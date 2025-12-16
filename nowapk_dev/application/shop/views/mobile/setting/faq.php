<div class="page page-settings">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">FAQ</h1>
    </div><!-- //top-bar -->

    <div class="page-content">

        <div class="tab-container">
            <ul class="tab-list">
                <?php
                    //포인트 카테고리 숨김
                    if($g_ios_publish==true ) {
                        unset($category_list[3]);

                        foreach($result_list as $key=> $dd){
                            if($dd["bd_idx"]==8422 || $dd["bd_idx"]==8427){
                                unset($result_list[$key]);
                            }
                        }
                    }
                ?>
                <?php foreach($category_list as $cate) : ?>
                    <li class="<?=str_replace("/","",$bd_cate)==str_replace("/","",$cate) ? "on":"" ?>"><a href="/setting/faq?bd_cate=<?=$cate?>" class="tab"><?=$cate?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="tab-panel">
            <h2 class="sr-text">기업회원</h2>

            <div class="section is-last">
                <!-- 카테고리 -->
                <div class="container" style="padding-top:0px;">
                    <!--ul class="category-group">
                        <li class="on"><a href="#" class="btn">사용하나?</a></li>
                        <li><a href="#" class="btn">정리필요!!!!</a></li>
                        <li><a href="#" class="btn">정리필요!!!!</a></li>
                        <li><a href="#" class="btn">기업정보관리</a></li>
                        <li><a href="#" class="btn">기타</a></li>
                    </ul-->
                </div>
                <!-- 목록 -->
                <div class="result-wrap">
                    <div class="faq-list">
                        <ol class="list">
                        	<?php foreach($result_list as $row) : ?>
                            <li class="list-item">
                                <a href="#" aria-expanded="false" class="accordion-title js-toggle-accordion"><?=$row["bd_subject"]?></a>
                                <div class="accordion-content" hidden>
                                   <?=str_replace("\n","<br/>",$row["bd_content"])?>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </div>

            </div>

        </div>

    </div><!-- //page-content -->

</div>
