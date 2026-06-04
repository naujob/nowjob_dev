<div id="content" class="page page-mypage">
    <main class="page-content">
        <div class="container">
            <?php $tab_on = "tab2"; ?>
            <?php $tab_on2 = "menu1"; ?>
            <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <style>
                    /* 탭 메뉴 스타일 */

                    .tabbox ul li {
                        list-style: none;
                        font-size: 1.0em;
                        border-radius: 5px 5px 0 0;
                        width: fit-content;
                        padding: 15px 35px;
                        background-color: transparent;
                        float: left;
                        border-bottom: 0;
                        cursor: pointer;
                        color: #ccc;
                    }

                    .tabbox ul li .tabcnt {
                        margin-left: 15px;
                    }

                    .tabbox ul li.tabon {
                        /* background-color: #fff; */
                        font-weight: 600;
                        color: #000;
                        border-bottom: 3px solid #57CCAF;
                    }

                    /* 지원자 현황 스타일  수정 2025-08-11*/
                    .applicant-status {
                        line-height: 26px;
                        text-align: left;
                    }

                    .applicant-status .status-item {
                        white-space: nowrap;
                    }

                    .applicant-status .status-link {
                        color: #57CCAF;
                        text-decoration: none;
                        margin-left: 5px;
                    }

                    .applicant-status .status-link.noview {
                        color: #57CCAF;
                    }

                    /* 공개/비공개 버튼 세로 배치 */
                    .visibility-controls {
                        display: flex;
                        flex-direction: column;
                        gap: 5px;
                        /* 간격 줄임 */
                        /* 좌우 마진을 같게 설정 */
                        margin-left: auto;
                        margin-right: auto;
                        /* 중앙 정렬을 위해 좌우 마진을 auto로 설정 */
                        align-items: center;
                        /* flex 요소들을 가운데 정렬 */
                        margin-top: 0;
                        /* 마진 제거 */
                        /* 위쪽으로 10px 이동 */
                    }

                    /* content-wrap 높이 설정 - td padding 고려 */
                    .board-list .item-info .content-wrap {
                        height: 110px;
                        /* 130px - td padding 20px */
                        max-height: 110px;
                        min-height: 110px;
                        /* 최소 높이도 설정 */
                        box-sizing: border-box;
                        /* 패딩과 테두리를 포함한 전체 높이 계산 */
                        overflow: hidden;
                        /* 넘침 방지 */
                    }

                    /* 테이블 전체에 border-box 적용 */
                    .board-list {
                        box-sizing: border-box;
                        table-layout: fixed;
                        /* 테이블 레이아웃 고정 - 높이 계산을 정확하게 */
                    }

                    .board-list * {
                        box-sizing: border-box;
                    }



                    /* meta 클래스 내부 p 태그 font-size 설정 */
                    .meta p {
                        font-size: 1.1rem;
                    }


                    /* =================================================
                     * 유료상품 컬럼 스타일 정의
                     * 컬럼 너비를 줄여서 관리 컬럼과의 간격 조정
                     * ================================================= */

                    /* 유료상품 컬럼 컨테이너 - 좁은 공간에 맞게 조정 */
                    .board-list td:nth-child(5),
                    .board-list td:has(.product-info-child) {
                        text-align: center;
                        overflow-wrap: break-word;

                    }

                    /* 유료상품 컬럼 헤더(th) - 통일성 유지 */
                    .board-list thead tr th:nth-child(5) {
                        text-align: center;
                        padding-left: 10px;
                        padding-right: 10px;
                    }

                    .board-list tbody tr {
                        height: 130px;
                        max-height: 130px;
                    }

                    /* 테이블 셀 높이 통일 - content-wrap 기준으로 설정 */
                    .board-list tbody td {
                        height: 130px;
                        max-height: 130px;
                        min-height: 130px;
                        /* 최소 높이도 설정 */
                        vertical-align: middle;
                        /* 모든 셀 세로 중앙 정렬 */
                        padding: 10px;
                        overflow: hidden;
                        /* 내용이 넘치지 않도록 */
                    }



                    /* 첫 번째 셀(공고 정보) 최소 너비 설정 */
                    .board-list tbody td.selectable-item {
                        min-width: 300px;
                    }

                    /* 공개/비공개 셀 너비 설정 */
                    .board-list tbody td:nth-child(2) {
                        width: 200px;
                    }

                    /* 상품 정보 셀 라인 높이 설정 */
                    .product-info-child {
                        line-height: 20px;

                        /* 라인 높이 줄임 */
                    }



                    /* 제목 패딩 제거 - 더 구체적인 선택자 사용 */
                    .board-list .item-info .content-wrap .title {
                        padding: 0;
                    }

                    /* col 너비가 변경될 때를 위한 반응형 스타일 */
                    @media (max-width: 1200px) {
                        .visibility-controls {
                            margin-left: calc(10% - 50px);
                        }
                    }

                    @media (max-width: 900px) {
                        .visibility-controls {
                            margin-left: calc(12% - 40px);
                        }
                    }



                    /* 상품신청 버튼 스타일 */
                    .page.page-mypage .btn-promotion-buy {
                        border: 1px solid #ededed;
                        padding: 8px 5px;
                        /* 패딩 줄임 */
                        margin-top: 2px;
                        /* 마진 줄임 */
                        display: inline-block;
                        width: 48%;
                        border-radius: 20px;
                        background-color: #fff;
                        text-decoration: none;
                        color: inherit;
                    }

                    .visibility-controls .label {
                        position: relative;
                        right: 0;
                        top: 0;
                        background-color: #57CCAF;
                        color: #fff;
                        padding: 5px 13px;
                        /* 패딩 더 줄임 */
                        width: 60%;
                        min-height: 25px;
                        /* 높이 더 줄임 */
                        border-radius: 20px;
                        vertical-align: middle;
                        line-height: 21px;
                        /* 라인 높이 줄임 */
                        border: 1px solid #57CCAF;
                        margin-bottom: 3px;
                    }

                    .visibility-controls .btn {
                        background-color: #f2f2f2;
                        /* color: #BEBFC9; */
                        padding: 5px 13px;
                        /* 패딩 더 줄임 */
                        width: 60%;
                        /* 74.125px에서 %로 변경 - 부모 요소의 50% */
                        height: 33px;
                        /* 높이 더 줄임 */

                        border-radius: 20px;
                        border: 1px solid #BEBFC9;
                        cursor: pointer;
                    }

                    /* 공고기간 줄바꿈 방지 */
                    .regdate {
                        white-space: nowrap;
                        min-width: 150px;
                    }



                    /* 공고기간 날짜 텍스트 정렬 */
                    .regdate-child {
                        margin-top: 0;
                        display: inline-block;
                        /* vertical-align이 적용되도록 inline-block으로 변경 */

                        /* 세로 중앙 정렬 */
                    }

                    /* 공고 정보 컨테이너 패딩 제거 */
                    .item-info.colgroup {
                        padding: 0;
                    }

                    /* status-wrap 정렬 */
                    .status-wrap-up {
                        margin-top: 0;
                    }

                    /* 관리 버튼 컬럼 패딩 제거 */
                    .board-list .action {
                        padding: 0;
                        line-height: normal;
                        /* line-height 제거 */

                    }

                    /* 관리 버튼 세로 간격 조정 - 110px 높이에 맞춤 */
                    .board-list .action .btn {
                        display: inline-block;
                        /* inline-block으로 변경하여 텍스트 크기에 맞춤 */
                        text-align: center;
                        /* 텍스트 중앙 정렬 */
                        padding: 6px 12px;
                        /* 패딩 더 줄임 */
                        border-radius: 20px;
                        /* 둥근 모서리 */
                        background-color: #fff;
                        /* 흰색 배경 */
                        border: 1px solid #ededed;
                        /* 테두리 */
                        font-weight: 400;
                        text-decoration: none;
                        color: #333;
                        width: 66.89%;
                        min-height: 25px;
                        /* height 대신 min-height 사용 */
                        box-sizing: border-box;
                        /* border-box 유지 */

                        line-height: 21px;
                        /* 라인 높이 줄임 */
                        margin-bottom: 5px;
                        /* 간격 더 줄임 */
                        vertical-align: middle;
                        /* 세로 중앙 정렬 */
                    }

                    /* 첫 번째 버튼의 위 마진 제거 */
                    .board-list .action .btn:first-child {
                        margin-top: 0;
                    }

                    /* 마지막 버튼의 아래 마진 제거 */
                    .board-list .action .btn:last-child {
                        margin-bottom: 0;
                    }

                    /* Action 버튼 컨테이너 중앙 정렬 - Flexbox 사용 */
                    .board-list tbody tr td.action .action-buttons {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        width: 100%;

                    }

                    /* 공고기간 컬럼 스타일 - 잘리지 않도록 처리 */
                    .board-list td.regdate,
                    .board-list td:nth-child(3) {
                        white-space: nowrap;
                        overflow: visible;
                        line-height: 1.3;
                        padding: 8px 5px;
                        min-width: 120px;
                    }

                    /* 공고기간 컬럼 헤더 스타일 */
                    .board-list th:nth-child(3) {
                        white-space: nowrap;
                        overflow: visible;
                        line-height: 1.3;
                        padding: 8px 5px;
                        min-width: 120px;
                    }

                    /* =================================================
                     * 테이블 전체 폰트사이즈 통일 관리
                     * board-wrap 클래스로 일괄 적용하여 유지보수성 향상
                     * ================================================= */

                    .board-wrap {
                        font-size: 1.04em;
                    }

                    /* 상품 정보 정렬 */
                    .product-info-child {
                        margin-top: 0;
                    }

                    /* 썸네일 크기 조정 - content-wrap 높이에 맞춤 */
                    .board-list .item-info .thumb.big {
                        width: 110px;
                        max-width: 110px;
                        height: auto;
                        max-height: 110px;
                        /* 110px - 여백 20px */
                        object-fit: cover;
                        /* 이미지 비율 유지하면서 영역에 맞춤 */
                        box-sizing: border-box;
                        /* 패딩과 테두리를 포함한 전체 크기 계산 */
                        overflow: hidden;
                        /* 넘침 방지 */
                    }

                    /* 썸네일 이미지 크기 조정 */
                    .thumb.big img {
                        width: 100%;
                        max-width: 100%;
                        height: auto;
                        min-height: 100%;
                        object-fit: cover;
                    }



                    /* 테이블 헤더 셀 스타일 - 반응형 단위 사용 */
                    .board-list th.table-header-cell {
                        font-weight: 600;
                        background-color: #ededed;
                        min-height: 2.5rem;
                        /* 35px 대신 rem 사용 */
                        padding: 0.5rem 0.75rem;
                        /* 8px 12px 대신 rem 사용 */
                        text-align: center;
                        vertical-align: middle;
                        border-bottom: 1px solid #D3D3D3;
                        word-break: keep-all;
                        line-height: 1.2;
                        /* 빈 셀 처리를 위한 간단한 방법 */
                        white-space: nowrap;
                        overflow: hidden;
                    }

                    /* 테이블 헤더 정렬 고정 */
                    .board-list thead th {
                        text-align: center;
                    }

                    /* 보이지 않지만 열 폭 계산에는 참여 */
                    .board-list .sizer td {
                        padding: 0 !important;
                        border: 0 !important;
                        height: 0 !important;
                        line-height: 0 !important;
                        font-size: 0 !important;
                        visibility: hidden;
                        /* display:none 쓰면 안 됩니다 */
                    }

                    /* 빈 상태 행 */
                    .board-list .empty-row td {
                        text-align: center;
                        padding: 50px 0;
                    }

                    /* 컬럼별 간격 세밀 조정 */
                    .board-list th:nth-child(1),
                    .board-list td:nth-child(1) {
                        padding-left: 15px;
                        padding-right: 10px;
                    }

                    .board-list th:nth-child(2),
                    .board-list td:nth-child(2) {
                        padding-left: 8px;
                        padding-right: 0px;


                    }


                    .board-list td:nth-child(3) {
                        padding-left: 60px;
                        padding-right: 0px;
                    }

                    .board-list th:nth-child(3) {
                        padding-left: 77px;
                        padding-right: 0px;

                    }

                    .board-list th:nth-child(4),
                    .board-list td:nth-child(4) {
                        padding-left: 100px;
                        padding-right: 0px;
                    }


                    .board-list td:nth-child(5) {
                        padding-left: 8px;
                        padding-right: 8px;
                        position: relative;
                        left: 12px;
                    }

                    .board-list th:nth-child(5) span {
                        position: relative;
                        left: 10px;
                    }

                    .board-list td:nth-child(6) {
                        /* padding-left: 5px;
                        padding-right: 5px; */
                        position: relative;
                        left: 12px;
                    }

                    .board-list th:nth-child(6) span {
                        position: relative;
                        left: 10px;
                    }
                </style>
                <?php $list_gbn = isset($_GET['gbn']) ? $_GET['gbn'] : ''; ?>
                <div class="tabbox">
                    <ul>
                        <li onclick="location.href='/compage/recruit_list?gbn=ing';" <?php if ($list_gbn == 'ing') {
                                                                                            echo "class=\"tabon\"";
                                                                                        } ?>><span class="tabtitle">게재중인 공고</span><span class="tabcnt"><?= $recruit_count[0]['ingcnt'] ?></span></li>
                        <li onclick="location.href='/compage/recruit_list?gbn=wait';" <?php if ($list_gbn == 'wait') {
                                                                                            echo "class=\"tabon\"";
                                                                                        } ?>><span class="tabtitle">대기중인 공고</span><span class="tabcnt"><?= $recruit_count[0]['waitcnt'] ?></span></li>
                        <li onclick="location.href='/compage/recruit_list?gbn=close';" <?php if ($list_gbn == 'close') {
                                                                                            echo "class=\"tabon\"";
                                                                                        } ?>><span class="tabtitle">마감된 공고</span><span class="tabcnt"><?= $recruit_count[0]['closecnt'] ?></span></li>
                        <li onclick="location.href='/compage/recruit_list?gbn=all';" <?php if ($list_gbn != 'ing' && $list_gbn != 'wait' && $list_gbn != 'close') {
                                                                                            echo "class=\"tabon\"";
                                                                                        } ?>><span class="tabtitle">전체공고</span><span class="tabcnt"><?= $recruit_count[0]['allcnt'] ?></span></li>
                    </ul>
                </div>
                <?php // $currentDateTime = new DateTime(); // 사용되지 않는 변수 
                ?>
                <div class="manage-my-content">
                    <form action="/">
                        <h3 class="sr-text">채용공고 관리</h3>
                        <div class="board-wrap">
                            <table class="board-list" style="border-top: 2px solid #ededed;">
                                <colgroup>
                                    <col style="width:35%">
                                    <col style="width:12%">
                                    <col style="width:12%">
                                    <col style="width:17%">
                                    <col style="width:16%">
                                    <col style="width:10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="table-header-cell">&nbsp;공고&nbsp;</th>
                                        <th class="table-header-cell">&nbsp;공개/비공개&nbsp;</th>
                                        <th class="table-header-cell"><span style="font-size: 1.1em;">공고기간</span></th>
                                        <th class="table-header-cell">&nbsp;지원자&nbsp;</th>
                                        <th class="table-header-cell"><span style="font-size: 1.1em;">유료상품</span></th>
                                        <th class="table-header-cell"><span style="font-size: 1.1em;">관리</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $lines = 0; ?>
                                    <?php foreach ($recruit_list as $row) : ?>
                                        <?php $lines++; ?>
                                        <tr>
                                            <td class="selectable-item" style="min-width: 300px;">
                                                <div class="item-info colgroup">
                                                    <div class="thumb-wrap">
                                                        <!-- 썸네일 -->
                                                        <div class="thumb big click-item-new" data-link="/recruit/job_post/<?= $row["rc_idx"] ?>">
                                                            <img src="<?= getRecruitImg($row) ?>" alt="">
                                                        </div>
                                                    </div>
                                                    <div class="content-wrap click-item-new" data-link="/recruit/job_post/<?= $row["rc_idx"] ?>">
                                                        <!-- <div class="timestamp" style="font-size: 0.8em;">등록일 : <?= $row["reg_dt"] ?></div> -->
                                                        <div class="inline-group">
                                                            <!-- 직종라벨 -->
                                                            <p class="category"><?= getWorkingImg($row["rc_work"], 2) ?></p>
                                                        </div>
                                                        <!-- <p class="title"><?= $row["compAuth"] ?></p> -->
                                                        <p class="title"><strong><?= $row["rc_name"] ?></strong></p>

                                                        <!-- 추가조건 -->
                                                        <div class="meta" style="font-size: 0.8em;">
                                                            <!-- 근무시간 | 국적 | 나이 -->
                                                            <p>
                                                                <?= $row["rc_work_type"] ?> |
                                                                <?= ($row["rc_nation"] == "무관" || $row["rc_nation"] == "내국인,외국인" || $row["rc_nation"] == "") ? "국적무관" : $row["rc_nation"] ?> |
                                                                <?= formatAgeRange($row["rc_sage"], $row["rc_eage"]) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="visibility-controls">
                                                    <?php if ($row["rc_show_yn"] == "Y") : ?>
                                                        <span class="label">공&nbsp;&nbsp;개</span>
                                                        <button onclick="fSetClose(<?= $row["rc_idx"] ?>); return false;" class="btn">비공개</button>
                                                    <?php else : ?>
                                                        <button onclick="fSetOpen(<?= $row["rc_idx"] ?>); return false;" class="btn">공&nbsp;&nbsp;개</button>
                                                        <span class="label">비공개</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                            <td class="regdate">
                                                <div class="regdate-child"><?= date('y.m.d', strtotime($row["rc_sdate"])) ?> ~ <?= date('y.m.d', strtotime($row["rc_edate"])) ?></div>
                                            </td>
                                            <td class="applicant-status">
                                                <div class="status-wrap status-wrap-up">
                                                    <div class="status-item">지원자 <a href="/compage/bookmark_person2" class="status-link"><?= $row["reqcnt"] ?> </a>명</div>
                                                    <div class="status-item">열&nbsp;&nbsp;&nbsp;&nbsp;람 <a href="/compage/bookmark_person2/view" class="status-link"><?= $row["viewcnt"] ?> </a>명</div>
                                                    <div class="status-item">미열람 <a href="/compage/bookmark_person2/noview" class="status-link noview"><?= ($row["reqcnt"] - $row["viewcnt"]) ?> </a>명</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="product-info-child">
                                                    <?php /* if($row["pm_edate"] != '' && $row["pm_edate"] < date("Y-m-d")){ ?>
                                            <?php   //echo "이용만료<br>"; ?>
                                            <a href="/compage/point_promotion_buy" style="border: 1px solid #ededed; padding: 5px 10px; margin-top: 10px; display: inline-block; width: fit-content;border-radius: 20px; background-color:#fff;">상품신청</a>
                                            <?php } */ ?>
                                                    <?php //echo $row["pm_name"]." ".$row["pm_edate"]; 
                                                    ?>
                                                    <?php if (!empty($row["pm_name"])) { ?>
                                                        <?php if (strtotime($row["pm_edate"]) < time()) {
                                                            echo "이용만료<br>"; ?>
                                                            <a href="/compage/point_promotion_buy" class="btn-promotion-buy">상품신청</a>
                                                        <?php } else { ?>
                                                            <?php
                                                            // pm_name에서 (숫자+일) 패턴 찾기
                                                            $pm_name = $row["pm_name"];
                                                            if (preg_match('/\(\d+일\)/', $pm_name)) {
                                                                // (숫자+일) 앞에 날짜 범위 추가 (년도 제외)
                                                                $date_range = date('m.d', strtotime($row["pm_sdate"])) . " ~ " . date('m.d', strtotime($row["pm_edate"]));

                                                                // pm_name에서 (숫자+일) 부분을 분리
                                                                $service_name = preg_replace('/\(\d+일\)/', '', $pm_name); // 서비스명에서 (숫자+일) 제거
                                                                $days = preg_match('/\((\d+)일\)/', $pm_name, $matches) ? $matches[1] : '';

                                                                // 서비스명 + 날짜(숫자+일) 형식으로 출력
                                                                echo trim($service_name) . "<br>";
                                                                echo '<span style="white-space: nowrap;">' . $date_range . " (" . $days . "일)</span><br>";
                                                            } else {
                                                                echo $pm_name . "<br>";
                                                            }
                                                            ?>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <a href="/compage/point_promotion_buy" class="btn-promotion-buy">상품신청</a>
                                                    <?php } ?>

                                                    <?php /* if(!empty($row["pm_name"]) && $pmEdate < $currentDateTime){ ?>
                                              <?=($row["pm_name"] != '' ? $row["pm_name"]."<br>" : "")?>
                                            <?php }else{ ?>
                                              <a href="/compage/point_promotion_buy" style="border: 1px solid #ededed; padding: 5px 10px; margin-top: 10px; display: inline-block; width: fit-content;border-radius: 20px; background-color:#fff;">상품신청</a>
                                            <?php } */ ?>
                                                </div>
                                            </td>
                                            <!-- <td class="status">진행중</td> -->
                                            <td class="action">
                                                <div class="action-buttons">
                                                    <a href="/compage/recruit_write/<?= $row["rc_idx"] ?>" class="btn">수&nbsp;&nbsp;&nbsp;정</a>
                                                    <?php if ($row["rc_edate"] < date("Y-m-d") . " 23:59:59") { ?>
                                                        <a href="#" class="btn" onclick="fSetRecruitDel(<?= $row["rc_idx"] ?>); return false;" class="btn">삭&nbsp;&nbsp;&nbsp;제</a>
                                                    <?php } else { ?>
                                                        <a href="#" class="btn" onclick="fSetRecruitClose(<?= $row["rc_idx"] ?>); return false;" class="btn">마&nbsp;&nbsp;&nbsp;감</a>
                                                    <?php } ?>
                                                    <a href="/compage/recruit_write/<?= $row["rc_idx"] ?>?gbn=re" class="btn">재등록</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if ($lines == 0): ?>
                                        <!-- 폭 고정용 더미 행: 열 개수(6개)와 동일하게 td를 둡니다 -->
                                        <tr class="sizer" aria-hidden="true">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <!-- 실제 메시지 행 -->
                                        <tr class="empty-row">
                                            <td colspan="6">공고 없음</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <p class="help-msg">채용공고 등록은 최대 20개까지만 가능합니다.<br> 채용공고기간이 2개월지날시 자동삭제됩니다.</p>

                        <div class="form-action-wrap align-right">
                            <!-- <button id="btnDefault" type="button" class="btn-round btn-tertiary">공개 채용공고로 설정</button> -->
                            <button id="btnWriteList" type="button" class="btn-round btn-tertiary">채용공고 등록</button>
                        </div>

                    </form>
                </div>

            </div>

        </div>

    </main><!-- //page-content -->
</div><!-- //page -->


<script>
    $(document).ready(function() {
        //디폴트 설정
        $("#btnDefault").click(function() {
            var rc_idx_list = "";
            if ($("[name='rc_show_yn[]']:checked").length == 0) {
                commonAlert("공개 채용공고로 설정하실 공고를 선택해주세요.", function() {});
                return;
            }

            for (var icnt = 0; icnt < $("[name='rc_show_yn[]']:checked").length; icnt++) {
                if (rc_idx_list == "") {
                    rc_idx_list = $("[name='rc_show_yn[]']:checked").eq(icnt).val();
                } else {
                    rc_idx_list += "," + $("[name='rc_show_yn[]']:checked").eq(icnt).val();
                }
            }

            openPopup('confirm', {
                content: '<p>공개 채용공고로 설정 하시겠습니까?</p>',
                firstButton: '취소',
                secondButton: '설정',
                secondCallback: function() {
                    var params = {};
                    params["rc_idx_list"] = rc_idx_list;
                    comAjax("/compage_proc/recruit_default", params, function(ret) {
                        if (ret.code == "00") {
                            location.reload();
                        } else {
                            commonAlert(ret.msg, function() {});
                            return;
                        }
                    });
                }
            });
        });


        // btnWriteList 버튼도 btnWrite와 동일한 로직 사용
        $("#btnWriteList").click(function() {
            // top_line.php의 btnWrite와 동일한 로직이므로 btnWrite 클릭 이벤트 트리거
            $("#btnWrite").trigger('click');
        });


    }); //ready

    function fSetOpen(rc_idx) {
        openPopup('confirm', {
            content: '<p>공개 채용공고로 설정 하시겠습니까?</p>',
            firstButton: '취소',
            secondButton: '설정',
            secondCallback: function() {
                var params = {};
                params["rc_idx_list"] = rc_idx;
                comAjax("/compage_proc/recruit_open", params, function(ret) {
                    if (ret.code == "00") {
                        location.reload();
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
            }
        });

    }

    function fSetClose(rc_idx) {
        openPopup('confirm', {
            content: '<p>비공개 채용공고로 설정 하시겠습니까?</p>',
            firstButton: '취소',
            secondButton: '설정',
            secondCallback: function() {
                var params = {};
                params["rc_idx_list"] = rc_idx;
                comAjax("/compage_proc/recruit_close", params, function(ret) {
                    if (ret.code == "00") {
                        location.reload();
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
            }
        });

    }


    function fSetRecruitClose(rc_idx) {
        openPopup('confirm', {
            content: '<p>마감 처리 하시겠습니까?</p>',
            firstButton: '취소',
            secondButton: '마감',
            secondCallback: function() {
                var params = {};
                params["rc_idx_list"] = rc_idx;
                comAjax("/compage_proc/recruit_end", params, function(ret) {
                    if (ret.code == "00") {
                        location.reload();
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
            }
        });

    }

    function fSetRecruitDel(rc_idx) {
        openPopup('confirm', {
            content: '<p>삭제 처리 하시겠습니까?</p>',
            firstButton: '취소',
            secondButton: '삭제',
            secondCallback: function() {
                var params = {};
                params["rc_idx_list"] = rc_idx;
                console.log(params);
                comAjax("/compage_proc/recruit_del", params, function(ret) {
                    if (ret.code == "00") {
                        commonAlert("삭제되었습니다.", function() {});
                        location.reload();
                    } else {
                        commonAlert(ret.msg, function() {});
                        return;
                    }
                });
            }
        });

    }
</script>