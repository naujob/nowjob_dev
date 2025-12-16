<?php
// 기존 PHP 함수 주석처리 - add.js의 fRemoveBigAdrJS 함수 사용
/*
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
*/

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
        background: url("https://nowjob.co.kr/resource/shop/web/img/ic_bookmark_off.svg") no-repeat 0 0;
        background-size: contain;
        pointer-events: all;
        z-index: 1;
    }

    .btn-bookmark.on:after {
        background-image: inherit;
    }

    .result-list {
        margin-top: 20px;
    }

    .result-list .list-item {
        margin-bottom: 15px;
    }

    .result-list .content-li {
        position: relative;
        width: 100%;
        height: 120px;
        overflow: visible;
        font-size: 12px;
        line-height: 1.5;
        border: 0px;
        transition: background 0.2s ease;
        cursor: pointer;
    }

    .result-list .content-li:hover {
        background: #f8f8f8;
    }

    .result-list .content-li .content-info {
        width: 100%;
        height: 100%;
        padding: 5px;
        overflow: visible;
    }

    .result-list .content-info .content-body {
        position: relative;
        overflow: visible;
        display: flex;
        height: 100%;
        gap: 10px;
    }

    .result-list .content-info .img {
        position: relative;
        width: 110px;
        min-width: 110px;
        height: 110px;
        flex-shrink: 0;
        border: 0;
        border-radius: 5px;
        color: #888;
        overflow: hidden;
    }

    .result-list .content-info .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .result-list .content-info .star-rating {
        position: absolute;
        bottom: 5px;
        left: 9px;
        width: 85px;
        height: 15px;
        margin: 5px auto 0;
    }

    .result-list .content-info .desc {
        position: relative;
        display: flex;
        height: 100%;
        gap: 8px;
        overflow: visible;
    }

    .result-list .content-info .desc .desc1 {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 500px;
        min-width: 0;
        flex: 1 1 auto;
        justify-content: center;
        gap: 5px;
    }

    .result-list .content-info .desc .desc1 .wicon img {
        height: 26px;
    }

    .result-list .content-info .desc .desc1 .title {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .result-list .content-info .desc .desc1 .ellipsis {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .result-list .content-info .summary {
        display: flex;
        font-size: 15px;
        gap: 3px;
        position: relative;
    }

    .result-list .content-info .summary p {
        position: relative;
        padding-right: 5px;
        margin: 0;
        font-size: 16px;
        color: #333;
    }

    .result-list .content-info .summary p:not(:last-child)::after {
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

    .result-list .content-info .summary em {
        position: relative;
        display: inline-block;
        font-style: normal;
    }

    .result-list .content-info .summary em:not(:last-child) {
        padding-right: 5px;
    }

    .result-list .content-info .summary em.on {
        color: #1b9d7d;
    }

    .result-list .content-info .summary em:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 1px;
        height: 0.5em;
        margin: auto;
        border-right: 1px solid #777;
    }

    .result-list .content-info .desc .desc2 {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 280px;
        min-width: 180px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .result-list .content-info .desc .desc2 p.area {
        color: #000;
        font-size: 18px;
        font-weight: 600;
    }

    .result-list .content-info .desc .desc2 .wtype {
        display: flex;
        gap: 5px;
        justify-content: flex-end;
        position: relative;
        font-weight: 600;
    }

    .result-list .content-info .desc .desc2 .wtype em {
        position: relative;
        display: inline-block;
        font-style: normal;
        color: #333;
    }

    .result-list .content-info .desc .desc2 .wtype em:not(:last-child) {
        padding-right: 5px;
    }

    .result-list .content-info .desc .desc2 .wtype em:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 1px;
        height: 0.5em;
        margin: auto;
        border-right: 1px solid #777;
    }

    .result-list .content-info .desc .desc2 .wtype em.wtype1 {
        color: #3a70db;
    }

    .result-list .content-info .desc .desc2 .wtype em.wtype2 {
        color: #F86048;
    }

    .result-list .content-info .desc .desc2 .wtype em.wtype3 {
        color: #1B9D7D;
    }

    .result-list .content-info .desc .desc2 .period {
        display: flex;
        gap: 5px;
        justify-content: flex-end;
        position: relative;
        font-weight: 600;
    }

    .result-list .content-info .desc .desc2 .period em {
        position: relative;
        display: inline-block;
        font-style: normal;
    }

    .result-list .content-info .desc .desc2 .period em:not(:last-child) {
        padding-right: 5px;
    }

    .result-list .content-info .desc .desc2 .period em.on {
        color: #1b9d7d;
    }

    .result-list .content-info .desc .desc2 .period em:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 1px;
        height: 0.5em;
        margin: auto;
        border-right: 1px solid #777;
    }

    .result-list .content-info .desc .desc3 {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 300px;
        min-width: 180px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        gap: 3px;
    }

    .result-list .content-info .desc .desc3 p {
        margin: 0;
        text-align: right;
    }

    .page-mypage .result-list .selection {
        padding: 0px;
    }

    .result-list .content-info .selection {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 50px;
        align-items: center;
        justify-content: center;
    }

    .working-day>li {
        width: 35px;
        height: 35px;
        margin: 0 0.5em 4px 0;
        font-size: 17px;
        color: #BEBFC9;
        line-height: 2;
        text-align: center;
        text-indent: -1px;
        border-radius: 100%;
        border: 1px solid #BEBFC9;
        background: #fff;
        float: left;
    }

    .result-list .content-info .desc .desc4 {
        display: flex;
        flex-direction: column;
        height: 100%;
        width: 180px;
        min-width: 140px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        gap: 3px;
        overflow: visible;
    }
</style>
<div id="content" class="page page-mypage">
    <main class="page-content">

        <div class="container">
            <?php $tab_on = "tab3"; ?>
            <?php $tab_on2 = "menu3"; ?>
            <?php include_once(get_base_url() . '/application/shop/views/web/compage/top_line.php'); ?>
            <div class="tab-panel on">
                <div class="recruit-bookmark">
                    <h3 class="sr-text">제안보낸 지원공고</h3>
                    <!-- <div class="result-header">
                        <p class="total">검색결과 <em><?= $count ?></em>건</p>
                    </div> -->
                    <div class="result-wrap">

                        <!-- 목록 -->
                        <ol class="result-list">
                            <?php foreach ($list as $row) : ?>
                                <li class="list-item" data-ru_idx="<?= $row["ru_idx"] ?>">
                                    <div class="content-li">
                                        <div class="content-info">
                                            <div class="content-body">
                                                <div class="img">
                                                    <img src="<?= getResumeImg($row) ?>">
                                                    <button type="button" data-bk_type="RESUME" data-bk_key="<?= $row["ru_idx"] ?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?= $row["book_yn"] == "Y" ? "on" : "" ?>"><span class="sr-text">즐겨찾기 추가</span></button>
                                                    <p class="star-rating">
                                                        <span class="star" style="width:100%;"></span>
                                                        <span class="sr-text">평점</span>
                                                        <span class="sr-text">5</span>
                                                    </p>
                                                </div>
                                                <div class="desc">
                                                    <div class="desc1">
                                                        <div class="wicon"><?= getWorkingImg($row["ru_work"]) ?></div>
                                                        <div class="title ellipsis"><?= $row["ru_name"] ?></div>
                                                        <div class="summary">
                                                            <p><?= $row["mm_nation"] ?></p>
                                                            <p><?= getKoreanAge($row["mm_birth"]) ?>세</p>
                                                        </div>
                                                    </div>
                                                    <div class="desc2">
                                                        <div class="summary">
                                                            <p class="wtype"><?= getWorkType2025($row["ru_work_type"]) ?></p>
                                                            <p class="period"><?= getConvertResumePriceType($row["ru_price_type"]) ?></p>
                                                        </div>
                                                        <p class="area" data-addr="<?= htmlspecialchars($row["ad_addr"], ENT_QUOTES, 'UTF-8') ?>"><?= $row["ad_addr"] ?></p>
                                                    </div>
                                                    <div class="desc3">
                                                        <ul class="working-day">
                                                            <?= getWeekYoil($row["ru_week"]) ?>
                                                        </ul>
                                                    </div>
                                                    <div class="desc4">
                                                        <div class="form-action-wrap align-right">
                                                            <button type="button" aria-haspopup="dialog" class="btn-round btn-tertiary btnCancle"
                                                                data-ru_idx="<?= $row["ru_idx"] ?>"
                                                                data-rc_idx="<?= $row["rc_idx"] ?>"
                                                                data-target_id="<?= $row["target_id"] ?>"
                                                                data-user_id="<?= $row["user_id"] ?>"
                                                                data-target_img="<?= $row["target_img"] ?>"
                                                                data-user_img="<?= $row["user_img"] ?>"
                                                                data-target_name="<?= $row["target_name"] ?>"
                                                                data-user_name="<?= $row["user_name"] ?>"
                                                                data-subject="<?= $row["rc_name"] ?>">제안 취소</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                        <!-- //목록 -->
                    </div>
                </div>
            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->

<script>
    //제안을 취소 처리
    $(document).on("click", ".btnCancle", function(e) {
        e.stopPropagation(); // desc 클릭 이벤트 전파 방지
        var ru_idx = $(this).data('ru_idx');

        openPopup('confirm', {
            content: '<em>제안</em>을 취소하시겠습니까?',
            secondCallback: function() {

                var params = {};
                params["ru_idx"] = ru_idx;
                params["rc_idx"] = $(this).data('rc_idx'); // rc_idx도 전달
                comAjax("/compage_proc/resume_propose_cancle", params, function(ret) {
                    if (ret.code == "00") {
                        commonAlert("제안 취소 되었습니다.", function() {
                            location.reload();
                        });
                    } else {
                        console.log(ret);
                        commonAlert("삭제처리시 오류가 발생하였습니다.", function() {});
                    }
                });
            }
        });

    });

    // desc 클릭시 상세페이지 이동
    $(document).on('click', '.desc', function() {
        var ruIdx = $(this).closest('.list-item').data('ru_idx');
        if (ruIdx) {
            var url = '/resume/hr_post/' + ruIdx;
            window.open(url, '_blank');
        }
    });

    // 주소 정리 함수 적용 (add.js의 fRemoveBigAdrJS 사용)
    $(document).ready(function() {
        $('.area[data-addr]').each(function() {
            var $this = $(this);
            var originalAddr = $this.data('addr');
            if (originalAddr && typeof fRemoveBigAdrJS === 'function') {
                var formattedAddr = fRemoveBigAdrJS(originalAddr);
                $this.text(formattedAddr);
            }
        });
    });
</script>