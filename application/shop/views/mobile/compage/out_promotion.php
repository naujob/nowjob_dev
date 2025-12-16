<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">아웃소싱 관리</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="tab-container">
            <ul class="tab-list">
                <li><a href="/compage/out_member" class="tab">아웃소싱 멤버관리</a></li>
                <li class="on"><span class="tab">아웃소싱 광고관리</span></li>
            </ul>
        </div>

        <div class="tab-panel">
            <h2 class="sr-text">아웃소싱 광고관리</h2>

            <div class="profile-list">
                <ol class="list">
                    <li>
                        <!-- 아웃소싱 업체명 -->
                        <a href="/compage/point_charge_step1" class="item-info company angle-link">
                            <div class="thumb-wrap">
                                <div class="thumb"><img data-src="<?=$mem_info["mm_thumb"]?>" alt="" class="lozad"></div>
                                <p class="outsourcing-badge"><span class="sr-text">아웃소싱</span></p>
                            </div>
                            <div class="content-wrap">
                                <p class="name"><em><?=$mem_info["mm_comp_name"]?></em></p>
                                <div class="meta">
                                    <p class="tel"><?=$mem_info["mm_phone"]?></p>
                                    <p class="email"><?=$mem_info["mm_id"]?></p>
                                </div>
                            </div>
                        </a>

                        <!-- 아웃소싱 멤버 -->
                        <ol class="list">
                        	<?php foreach($result_list as $row) : ?>
                            <li>
                                <!-- 광고관리 페이지 링크 -->
                                <a href="/compage/recruit_list" class="item-info person angle-link">
                                    <div class="thumb-wrap">
                                        <div class="thumb"><img data-src="<?=$row["mm_thumb"]?>" alt="" class="lozad"></div>
                                    </div>
                                    <div class="content-wrap">
                                        <p class="name"><em><?=$row["mm_comp_name"]?></em>님</p>
                                        <div class="meta">
                                            <p class="tel"><?=$row["mm_phone"]?></p>
                                            <p class="email"><?=$row["mm_id"]?></p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ol>
                        <!-- //아웃소싱 멤버 -->
                    </li>
                </ol>
            </div>

            <div class="container">
                <p class="help-msg">아웃소싱을 활성화하면 광고내역을 공유하실수 있습니다.</p>
            </div>

        </div>

    </div><!-- //page-content -->

</div>