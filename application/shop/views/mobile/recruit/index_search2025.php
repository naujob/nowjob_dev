<style>
    /* 검색 폼 스타일 20250919*/
    .form-toggle-group.flex-layout {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .form-toggle-btn2.flex-item {
        flex: 1;
        min-width: 0;
        width: auto !important;
    }

    .age-select-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .age-select-item {
        flex: 1;
        min-width: 0;
    }

    .age-select {
        width: 100%;
        height: 36px;
        padding-left: 12px;
        border: 1px solid #BEBFC9;
        border-radius: 18px;
        box-sizing: border-box;
    }

    .age-separator {
        display: inline-block;
        margin-top: 7px;
        font-weight: bold;
    }

    .form-row.age-row {
        margin-bottom: 50px;
    }
</style>
<div id="advancedSearch" class="layer-panel" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="panel-title">채용정보 상세검색</h2>
            <button type="button" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <div class="section advanced-search-form">
                    <div class="container" style="padding: 30px;">

                        <div class="form-row">
                            <p class="form-label">카테고리 <em>다중선택가능</em>
                                <span class="option">
                                    <label class="form-checkbox">
                                        <input type="checkbox" name="category[]" value="" class="sr-text js-sel-all">
                                        <span class="label">전체선택</span>
                                    </label>
                                </span>
                            </p>
                            <div class="form-toggle-group col-4">
                                <?php foreach ($wrok_list as $row) : ?>
                                    <label class="form-toggle-btn2 sm" style="width: 22%;">
                                        <input type="checkbox" name="category[]" data-select-limit="2" value="<?= $row["cd_val"] ?>" data-img="<?= $row["cd_val2"] ?>" <?= in_array_return($row["cd_val"], $search_txt["category"], "check") ?>>
                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <script>
                                document.querySelectorAll('.js-sel-all').forEach(function(el) {
                                    var name = el.getAttribute('name');
                                    var items = document.querySelectorAll('input[name="' + name + '"]');

                                    el.addEventListener('change', function() {
                                        if (!items) return;
                                        items.forEach(function(item) {
                                            if (item !== el) {
                                                item.checked = el.checked;
                                            }
                                        });
                                    });

                                });
                            </script>
                        </div>

                        <div class="form-row">
                            <p class="form-label">근무형태 <em>다중선택가능</em></p>
                            <div class="form-toggle-group flex-layout">
                                <?php foreach ($work_type_list as $key => $row) : ?>
                                    <label class="form-toggle-btn2 flex-item">
                                        <input type="checkbox" name="work_type[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $search_txt["work_type"], "check") ?>>
                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-row">
                            <p class="form-label">
                                근무요일 <em>다중선택가능</em>
                                <span class="option">
                                    <label class="form-checkbox">
                                        <input type="checkbox" name="workingDay" value="all" class="sr-text">
                                        <span class="label">요일무관</span>
                                    </label>
                                </span>
                            </p>
                            <div class="form-toggle-group align-justify">
                                <?php foreach ($week_list as $row) : ?>
                                    <label class="form-toggle-btn2">
                                        <input type="checkbox" name="week[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $search_txt["week"], "check") ?>>
                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-row">
                            <p class="form-label">급여 <em>다중선택가능</em></p>
                            <div class="form-toggle-group flex-layout">
                                <?php foreach ($price_type_list as $key => $row) : ?>
                                    <label class="form-toggle-btn2 flex-item">
                                        <input type="checkbox" name="price_type[]" value="<?= $row["cd_val"] ?>" <?= in_array_return($row["cd_val"], $search_txt["price_type"], "check") ?>>
                                        <span class="btn"><?= $row["cd_val"] ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-row">
                            <p class="form-label">성별 <em>다중선택가능</em></p>
                            <div class="form-toggle-group flex-layout">
                                <label class="form-toggle-btn2 flex-item">
                                    <input type="checkbox" name="sex[]" value="남자" <?= in_array_return("남자", $search_txt["sex"], "check") ?>>
                                    <span class="btn">남자</span>
                                </label>
                                <label class="form-toggle-btn2 flex-item">
                                    <input type="checkbox" name="sex[]" value="여자" <?= in_array_return("여자", $search_txt["sex"], "check") ?>>
                                    <span class="btn">여자</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-row age-row">
                            <p class="form-label">나이</p>
                            <div class="age-select-group">
                                <div class="age-select-item">
                                    <select name="sage" class="age-select">
                                        <option value="">선택</option>
                                        <?php for ($icnt = 20; $icnt <= 80; $icnt++) : ?>
                                            <option value="<?= $icnt ?>" <?= $search_txt["sage"] == $icnt ? "selected" : "" ?>><?= $icnt ?>세</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <span class="age-separator">~</span>pan>
                                <div class="age-select-item">
                                    <select name="eage" class="age-select">
                                        <option value="">선택</option>
                                        <?php for ($icnt = 20; $icnt <= 80; $icnt++) : ?>
                                            <option value="<?= $icnt ?>" <?= $search_txt["eage"] == $icnt ? "selected" : "" ?>><?= $icnt ?>세</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom">
                <button id="btnSearch" type="button" class="btn btn-primary">검색하기</button>
            </div>
        </div>
    </div>
</div>

<script>
    /*
	$(document).ready(function(){
		$('[name=sage]').mobiscroll().number({
	        theme: 'ios',
	        themeVariant: 'light',
	        layout: 'fixed',
	        step: 1,
	        min: 18,
	        max: 80,
	        width: 150
	    });

	    $('[name=eage]').mobiscroll().number({
	        theme: 'ios',
	        themeVariant: 'light',
	        layout: 'fixed',
	        step: 1,
	        min: 18,
	        max: 80,
	        width: 150
	    });
	});
*/

    $("[name='workingDay']").click(function() {
        $("[name='week[]']").prop("checked", $(this).is(":checked"));
    });

    $(".btn-close-panel").click(function() {
        $("body").css("overflow", "auto");
        setTimeout(function() {
            $("body").css("overflow", "auto");
        }, 500);
        $("#advancedSearch").removeClass("on");
    });

    $("[name='sage']").change(function() {
        if ($("[name='sage']").val() != "" && $("[name='eage']").val() != "") {
            if ($("[name='sage']").val() > $("[name='eage']").val()) {
                $("[name='eage']").val($("[name='sage']").val());
            }
        }
    });

    $("[name='eage']").change(function() {
        if ($("[name='sage']").val() != "" && $("[name='eage']").val() != "") {
            if ($("[name='eage']").val() < $("[name='sage']").val()) {
                $("[name='sage']").val($("[name='eage']").val());
            }
        }
    });
    $("#btnSearch").click(function() {
        list_page = 1;

        initCategory();

        //닫기
        $("#advancedSearch").removeClass("on");
        $("body").css("overflow", "auto");

        //검색
        $(list_id).html("");
        getBoardList("Y");
    });
</script>