<?php
// 서버 렌더링 단계에서 주소를 축약해 반환
function fRemoveBigAdr($arg)
{
    $parts1 = explode(",", $arg);
    $arg = $parts1[0];

    $replacements = [
        "서울특별시 " => "서울시 ",
        "경기도 " => "",
        "강원특별자치도 " => "",
        "충청남도 " => "",
        "충청북도 " => "",
        "경상남도 " => "",
        "경상북도 " => "",
        "전라남도 " => "",
        "전북특별자치도 " => "",
        "제주특별자치도 " => "",
        "세종특별자치시 " => "세종시 ",
        "광주광역시 " => "광주시 ",
        "인천광역시 " => "인천시 ",
        "울산광역시 " => "울산시 ",
        "부산광역시 " => "부산시 ",
        "대구광역시 " => "대구시 ",
        "대전광역시 " => "대전시 ",
        "경기 " => "",
        "강원 " => "",
        "충남 " => "",
        "충북 " => "",
        "경남 " => "",
        "경북 " => "",
        "전남 " => "",
        "전북 " => "",
        "제주 " => ""
    ];

    foreach ($replacements as $search => $replace) {
        $arg = str_replace($search, $replace, $arg);
    }

    $parts = explode(" ", $arg);
    if (count($parts) >= 3) {
        return $parts[0] . " " . $parts[2];
    } else {
        return $arg;
    }
}

function getWorkType2025($work_type)
{
    if (!$work_type) {
        return "";
    }

    $items = [];

    if (preg_match_all('/<span>(.*?)<\/span>/', $work_type, $matches)) {
        $items = $matches[1];
    } else {
        $items = preg_split('/\s*,\s*/', $work_type);
    }

    $item_set = array_unique(array_map('trim', $items));

    $has_jugan = in_array('주간', $item_set);
    $has_yagan = in_array('야간', $item_set);
    $has_2shift = in_array('2교대', $item_set);

    if ($has_jugan && $has_yagan && $has_2shift) {
        return '<em class="wtype0">근무협의</em>';
    }

    $result = '';
    foreach ($item_set as $text) {
        if ($text === '') continue;

        $cls = '';
        if ($text === '주간') {
            $cls = 'wtype1';
        } else if ($text === '야간') {
            $cls = 'wtype2';
        } else if ($text === '2교대') {
            $cls = 'wtype3';
        }

        $result .= '<em class="' . $cls . '">' . $text . '</em>';
    }

    return $result;
}

function getConvertResumePriceType($price_type)
{
    $allTypes = ['일급', '주급', '월급'];

    $containsAll = true;
    foreach ($allTypes as $type) {
        if (strpos($price_type, $type) === false) {
            $containsAll = false;
            break;
        }
    }

    if ($containsAll) {
        return '<em>급여협의</em>';
    }

    $types = preg_split('/\s*,\s*/', $price_type);

    $result = '';
    foreach ($types as $type) {
        $type = trim($type);
        $result .= '<em>' . $type . '</em>';
    }

    return $result;
}

?>
<style>
    .btn-bookmark {
        display: block;
        position: absolute;
        top: 0px;
        right: 0px;
        width: 40px;
        height: 40px;
        border: 0;
        background: url("https://nowjob.co.kr/resource/shop/web/img/ic_bookmark_on.svg") no-repeat 0 0;
        background-size: 0px;
        pointer-events: none;
        z-index: 1;
    }

    .btn-bookmark:before {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        border-top: 40px solid #f8f8f8;
        border-left: 40px solid transparent;
        z-index: -1;
    }

    .btn-bookmark:after {
        content: "";
        display: block;
        position: absolute;
        top: 3px;
        right: 3px;
        width: 16px;
        height: 16px;
        border-style: hidden;
        background: url("https://nowjob.co.kr/resource/shop/web/img/ic_bookmark_off.svg") no-repeat 0 0;
        background-size: contain;
        pointer-events: all;
        z-index: 1;
    }

    .btn-bookmark.on:after {
        background-image: inherit;
    }

    .sr-text {
        display: block;
        position: absolute;
        width: 1px;
        height: 1px;
        margin: 0;
        padding: 0;
        font-size: 1px;
        color: transparent;
        pointer-events: none;
        white-space: nowrap;
        overflow: hidden;
        border: 0;
        outline: 0;
    }

    .result-list .content-li {
        position: relative;
        width: 100%;
        height: 115px;
        overflow: hidden;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
        background: #fff;
        margin-bottom: 10px;
        transition: background 0.2s ease;
    }

    .result-list .content-li.on {
        border: 1px solid #57CCAF;
    }

    .result-list .content-li:hover {
        background: #f8f8f8;
    }

    .content-li * {
        padding: 0;
        margin: 0;
    }

    .content-li .content-info {
        width: 100%;
        height: 100%;
        padding: 10px;
    }

    .content-info .wicon img {
        height: 18px;
    }

    .content-info .title {
        font-size: 16px;
        font-weight: bold;
        margin: 1px 0px;
    }

    .content-info .content-body {
        position: relative;
        overflow: hidden;
        height: 95px;
    }

    .content-info .desc {
        position: relative;
        height: 100%;
        margin: 0 0 0 110px;
        cursor: pointer;
    }

    .content-info .desc .ellipsis {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .content-info .img {
        position: absolute;
        width: 95px;
        height: 95px;
        border: 0;
        border-radius: 5px;
        color: #888;
        overflow: hidden;
    }

    .content-info .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* 이미지 비율 유지하며 박스를 꽉 채움 */
        display: block;
    }

    .content-info .link {
        color: #5085BB;
    }

    .content-info .summary {
        display: flex;
        font-size: 15px;
        gap: 5px;
        position: relative;
    }

    .content-info .summary p {
        position: relative;
        padding-right: 5px;
        margin: 0;
        color: #000;
        font-weight: 600;
    }

    .content-info .summary p:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 1px;
        height: 0.5em;
        margin: auto;
        border-right: 1px solid #ACACAC;
    }

    .content-info .summary>* {
        min-width: 0;
        max-width: 100%;
        margin-right: 4px;
        margin-bottom: -3px;
    }

    .content-info .summary.area p {
        color: #000;
        font-weight: 600;
    }

    .content-info .summary.reco p {
        color: #1b9d7d;
        font-weight: 600;
    }

    .content-info .summary2 {
        position: absolute;
        right: 0px;
        bottom: 0px;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0;
        color: #1b9d7d;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .content-info .summary2 p {
        margin: 0;
        text-align: right;
    }

    .content-info .summary2 .pricetype {
        display: flex;
        gap: 5px;
        justify-content: flex-end;
        position: relative;
    }

    .content-info .summary2 .pricetype em {
        position: relative;
        display: inline-block;
        font-style: normal;
    }

    .content-info .summary2 .pricetype em:not(:last-child) {
        padding-right: 5px;
    }

    .content-info .summary2 .pricetype em:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 1px;
        height: 0.5em;
        margin: auto;
        border-right: 1px solid #777777;
    }

    .content-info .summary3 {
        position: absolute;
        right: 0px;
        top: 0px;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0;
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .content-info .summary3 p {
        margin: 0;
        text-align: right;
    }

    .content-info .summary3 .worktype {
        display: flex;
        gap: 5px;
        justify-content: flex-end;
        position: relative;

    }

    .content-info .summary3 .worktype em {
        position: relative;
        display: inline-block;
        font-style: normal;
    }

    .content-info .summary3 .worktype em.wtype1 {
        color: #3a70db;
    }

    .content-info .summary3 .worktype em.wtype2 {
        color: #F86048;
    }

    .content-info .summary3 .worktype em.wtype3 {
        color: #1B9D7D;
    }

    .content-info .summary3 .worktype em:not(:last-child) {
        padding-right: 5px;
    }

    .content-info .summary3 .worktype em:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 1px;
        height: 0.5em;
        margin: auto;
        border-right: 1px solid #777777;
    }

    .content-info .star-rating {
        position: absolute;
        bottom: 5px;
        left: 5px;
        width: 85px;
        height: 15px;
        margin: 5px auto 0;
    }

    .working-day {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin: 0 0 -4px;
        list-style: none;
    }

    .working-day>li {
        width: 1.8em;
        height: 1.8em;
        margin: 0 0.5em 4px 0;
        font-size: 12px;
        color: #BEBFC9;
        line-height: 1.5;
        text-align: center;
        text-indent: -1px;
        border-radius: 100%;
        border: 1px solid #57CCAF;
        background: #fff;
        float: left;
    }

    .working-day>li:last-child {
        margin-right: 0;
    }

    .working-day li[aria-hidden=false] {
        color: #fff;
        border-color: transparent;
        background-color: #57CCAF;
    }

    .result-list .list-item+.list-item {
        border-top: 0;
    }

    /* 지원한 공고 링크 스타일 */
    .content-info .job-link {
        position: absolute;
        bottom: 0;
        right: 37px;
        font-size: 12px;
        color: #5085BB;
        text-decoration: none;
        border: 1px solid #5085BB;
        padding: 2px 6px;
        border-radius: 3px;
    }

    /* 탭 컨테이너 스크롤 시 상단 고정 */
    .page.page-mypage-view .top-bar~.page-content .tab-container {
        position: sticky;
        top: 0 !important;
        z-index: 10;
        background: #fff;
    }
</style>
<div class="page page-mypage-view">
    <div class="top-bar">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <h1 class="title">관심인재 관리</h1>
    </div><!-- //top-bar -->
    <div class="page-content">
        <div class="tab-container">
            <ul class="tab-list">
                <li><a href="javascript:_replace('/compage/bookmark_person1');" class="tab">즐겨찾기한 인재</a></li>
                <li class="on"><span class="tab">지원받은 인재</span></li>
            </ul>
        </div>
        <div class="tab-panel">
            <div class="section my-bookmark">
                <div class="container">
                    <h2 class="sr-text">채용공고 지원받은 인재</h2>
                    <div class="result-wrap">
                        <!-- 목록 -->
                        <ol class="result-list">
                            <?php foreach ($list as $row) : ?>
                                <li class="list-item">
                                    <div class="content-li" data-ru_idx="<?= $row["ru_idx"] ?>" data-mq_idx="<?= $row["mq_idx"] ?>">
                                        <div class="content-info">
                                            <div class="content-body">
                                                <div class="img">
                                                    <img src="<?= getResumeImg($row) ?>">
                                                    <button type="button" data-bk_type="RESUME" data-bk_key="<?= $row["ru_idx"] ?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?= ($row["book_yn"] == "Y" ? "on" : "") ?>"><span class="sr-text">즐겨찾기 추가</span></button>
                                                </div>
                                                <p class="star-rating"><span class="star" style="width:100%;"></span><span class="sr-text">평점</span><span class="sr-text">5</span></p>
                                                <div class="desc">
                                                    <div class="wicon"><?= getWorkingImg($row["ru_work"]) ?></div>
                                                    <div class="title ellipsis"><?= $row["ru_name"] ?></div>
                                                    <div class="summary">
                                                        <ul class="working-day"><?= getWeekYoil($row["ru_week"]) ?></ul>
                                                    </div>
                                                    <div class="summary">
                                                        <p><?= $row["mm_nation"] ?></p>
                                                        <p><?= $row["ma_age"] ?>세</p>
                                                        <?php if (!empty($row["ad_addr"])) : ?>
                                                            <p class="area-addr" data-addr="<?= htmlspecialchars($row["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= fRemoveBigAdr($row["ad_addr"]) ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="summary2">
                                                        <p class="pricetype"><?= getConvertResumePriceType($row["ru_price_type"]) ?></p>
                                                    </div>
                                                    <div class="summary3">
                                                        <p class="worktype"><?= getWorkType2025($row["ru_work_type"]) ?></p>
                                                    </div>
                                                    <?php if (!empty($row["rc_idx"])) : ?>
                                                        <a href="/recruit/job_post/<?= $row["rc_idx"] ?>" class="job-link">지원한 공고</a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                        <!-- //목록 -->
                    </div>
                    <!-- <div class="sticky-bottom">
                        <button type="button" aria-haspopup="dialog" id="btnReqeust" class="btn btn-primary">제안보내기</button>
                        <button id="btnCancel" type="button" class="btn btn-secondary js-remove">취소하기</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div><!-- //page-content -->
</div>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        // $("#btnReqeust").click(function() {
        //     //로그인 여부 확인
        //     if ($("#is_login").val() != "Y") {
        //         commonLogin();
        //         return;
        //     }

        //     var mq_idxs = [];

        //     // bookmark_person1.php와 동일한 방식으로 선택된 항목 수집
        //     $(".content-li.on").each(function() {
        //         var mq_idx = $(this).data("mq_idx");
        //         if (mq_idx) {
        //             mq_idxs.push(mq_idx);
        //         }
        //     });

        //     if (mq_idxs.length == 0) {
        //         commonAlert("제안하실 공고를 선택해주세요.", function() {});
        //         return;
        //     }

        //     openPopup('confirm', {
        //         content: '<em>선택한 </em>이력서에<br> 제안하시겠습니까?',
        //         secondCallback: function() {
        //             var params = {};
        //             params["mq_idxs"] = mq_idxs;
        //             params["mq_type"] = "RESUME";
        //             comAjax("/common_proc/member_request", params, function(ret) {
        //                 if (ret.code == "00") {
        //                     commonAlert("제안 신청 되었습니다.", function() {
        //                         $(".content-li").removeClass("on");
        //                     });
        //                 } else {
        //                     commonAlert(ret.msg, function() {});
        //                     return;
        //                 }
        //             });
        //         }
        //     });
        // });

        // $("#btnCancel").click(function() {
        //     var mq_idxs = [];

        //     // bookmark_person1.php와 동일한 방식으로 선택된 항목 수집
        //     $(".content-li.on").each(function() {
        //         var mq_idx = $(this).data("mq_idx");
        //         if (mq_idx) {
        //             mq_idxs.push(mq_idx);
        //         }
        //     });

        //     if (mq_idxs.length == 0) {
        //         commonAlert("취소하실 제안받은 인재를 선택해주세요.", function() {});
        //         return;
        //     }

        //     openPopup('confirm', {
        //         content: '<em>선택한</em> 지원받은 인재를<br> 취소하시겠습니까?',
        //         secondCallback: function() {
        //             var params = {};
        //             params["proc_type"] = "member_request";
        //             params["mq_idxs"] = mq_idxs;
        //             comAjax("/common_proc/request_cancel", params, function(ret) {
        //                 if (ret.code == "00") {
        //                     commonAlert("취소 되었습니다.", function() {
        //                         location.reload();
        //                     });
        //                 } else {
        //                     commonAlert(ret.msg, function() {});
        //                     return;
        //                 }
        //             });
        //         }
        //     });
        // });

        // content-li 영역 클릭 시 인재 상세정보로 이동
        $(document).on('click', '.content-li', function(e) {
            // 즐겨찾기 버튼 클릭은 제외
            if ($(e.target).closest('.btnBookMark').length > 0) {
                return;
            }
            // 지원한 공고 링크 클릭은 제외
            if ($(e.target).closest('.job-link').length > 0) {
                return;
            }
            
            var ruIdx = $(this).data('ru_idx');
            if (ruIdx) {
                var url = '/resume/hr_post/' + ruIdx;
                location.href = url;
            }
        });

        // 주소 정리 함수 적용 (add.js의 fRemoveBigAdrJS 사용)
        $(document).ready(function() {
            $('.area-addr[data-addr]').each(function() {
                var $this = $(this);
                var originalAddr = $this.data('addr');
                if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
                    var formattedAddr = fRemoveBigAdrJS(originalAddr);
                    $this.text(formattedAddr);
                }
            });
        });
    });
</script>