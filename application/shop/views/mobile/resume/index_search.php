<div id="advancedSearch" class="layer-panel" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="panel-title">인재정보 상세검색</h2>
            <button type="button" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">

                <div class="section advanced-search-form">
                    <div class="container">
                        <div class="form-row">
                            <label>
                                <span class="form-label">검색어</span>
                                <input type="search" name="text" placeholder="검색어를 입력해 주세요" aria-required="true" required class="form-text" value="<?=$search_txt["text"]?>">
                            </label>
                        </div>
                        <div class="form-row">
                            <p class="form-label">지역</p>

                            <div class="current-area">
                                <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel"><?=$addr_info["ad_addr"]?></a>
                            </div>

                            <div class="js-range-slider">
                                <!--div class="min">
                                    <input type="range" name="skm" min="0" max="50" step="5" data-unit="km" value="<?=$search_txt["skm"]?>" data-minmax="0,50">
                                    <div class="thumb"><output class="label"><?=$search_txt["skm"]?></output></div>
                                </div-->
                                <div class="max">
                                    <!-- 최대값 -->
                                    <input type="range" name="ekm" min="5" max="50" step="5" data-unit="km" value="<?=$search_txt["ekm"]?>" >
                                    <div class="thumb"><output class="label"><?=$search_txt["ekm"]?></output></div>
                                </div>
                                <div class="fill"></div>
                            </div>
                        </div>

                        <!-- 20220929 수정 -->
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
                                <?php foreach($wrok_list as $row) : ?>
                                <label class="form-toggle-btn2 sm">
                                    <input type="checkbox" name="category[]" data-select-limit="2" value="<?=$row["cd_val"]?>" data-img="<?=$row["cd_val2"]?>" <?=in_array_return($row["cd_val"],$search_txt["category"],"check")?>>
                                    <span class="btn"><?=$row["cd_val"]?></span>
                                </label>
                                <?php endforeach;?>
                            </div>
                            <script>
                                document.querySelectorAll('.js-sel-all').forEach(function (el) {
                                    var name = el.getAttribute('name');
                                    var items = document.querySelectorAll('input[name="'+ name +'"]');

                                    el.addEventListener('change', function () {
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
                            <div class="form-toggle-group col-4">
                                <?php foreach($work_type_list as $key=> $row) : ?>
                                <label class="form-toggle-btn2">
                                    <input type="checkbox" name="work_type[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$search_txt["work_type"],"check")?>>
                                    <span class="btn"><?=$row["cd_val"]?></span>
                                </label>
                                <?php endforeach;?>
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
                                <?php foreach($week_list as $row) : ?>
                                <label class="form-toggle-btn2">
                                    <input type="checkbox" name="week[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$search_txt["week"],"check")?>>
                                    <span class="btn"><?=$row["cd_val"]?></span>
                                </label>
                                <?php endforeach;?>
                            </div>
                        </div>

                        <div class="form-row">
                            <p class="form-label">급여 <em>다중선택가능</em></p>
                            <div class="form-toggle-group col-4">
                                <?php foreach($price_type_list as $key=> $row) : ?>
                                <label class="form-toggle-btn2">
                                    <input type="checkbox" name="price_type[]" value="<?=$row["cd_val"]?>" <?=in_array_return($row["cd_val"],$search_txt["price_type"],"check")?>>
                                    <span class="btn"><?=$row["cd_val"]?></span>
                                </label>
                                <?php endforeach;?>
                            </div>
                        </div>

                        <div class="form-row">
                            <p class="form-label">성별 <em>다중선택가능</em></p>
                            <div class="form-toggle-group col-4">
                                <label class="form-toggle-btn2">
                                    <input type="checkbox" name="sex[]" value="남자" <?=in_array_return("남자",$search_txt["sex"],"check")?>>
                                    <span class="btn">남자</span>
                                </label>
                                <label class="form-toggle-btn2">
                                    <input type="checkbox" name="sex[]" value="여자" <?=in_array_return("여자",$search_txt["sex"],"check")?>>
                                    <span class="btn">여자</span>
                                </label>
                            </div>
                        </div>

                        <!-- 20220929 수정 -->
                        <!--div class="form-row">
                            <p class="form-label">나이</p>
                            <div class="inline-group">
                                <div class="col">
                                    <label>
                                        <span class="sr-text">최소나이 선택</span>
                                        <input type="text" name="sage" placeholder="최소" readonly required class="form-text js-wheel">
                                    </label>
                                    <div class="wheel-wrapper"><div id="minWheel" class="wheel"></div></div>
                                </div>
                                <span class="entity">~</span>
                                <div class="col">
                                    <label>
                                        <span class="sr-text">최대나이 선택</span>
                                        <input type="text" name="eage" placeholder="최대" readonly required class="form-text js-wheel">
                                    </label>
                                    <div class="wheel-wrapper"><div id="maxWheel" class="wheel"></div></div>
                                </div>
                            </div>
                        </div-->

                        <div class="form-row">
                            <p class="form-label">나이</p>
                            <div class="inline-group">
                                <div class="col">
                                    <select name="sage" style="width:100%;height:40px;padding-left:12px;border-radius: 20px; border: 1px solid #ccc;" >
                                    	<option value="">선택</option>
                                    	<?php for($icnt=20;$icnt <= 80;$icnt++) : ?>
                                    	<option value="<?=$icnt?>" <?=$search_txt["sage"]==$icnt ? "selected":""?>><?=$icnt?>세</option>
                                    	<?php endfor;?>
                                    </select>
                                </div>
                                <span class="entity">~</span>
                                <div class="col">
                                    <select name="eage" style="width:100%;height:40px;padding-left:12px;border-radius: 20px; border: 1px solid #ccc;">
                                    	<option value="">선택</option>
                                    	<?php for($icnt=20;$icnt <= 80;$icnt++) : ?>
                                    	<option value="<?=$icnt?>" <?=$search_txt["eage"]==$icnt ? "selected":""?>><?=$icnt?>세</option>
                                    	<?php endfor;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom view-footer">
                <button id="btnSearch" type="button" class="btn btn-primary">검색하기</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

    $("[name='sage']").change(function() {
        var selectedAge = parseInt($(this).val(), 10);
        $("[name='eage']").empty().append('<option value="">선택</option>'); // eage 초기화
        if (!isNaN(selectedAge)) {
            populateAgeOptions("[name='sage']", selectedAge, 80); // sage 이후 숫자만 추가
        }
    });

    // sage, eage 초기화
    function populateAgeOptions(selectElement, min, max) {
        for (var i = min; i <= max; i++) {
            //console.log(`<option value="${i}">${i}세</option>`);
            $("[name='eage']").append('<option value="'+i+'">'+i+'세</option>');
        }
    }

});

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

	$("[name='workingDay']").click(function(){
		$("[name='week[]']").prop("checked",$(this).is(":checked"));
	});

	$(".btn-close-panel").click(function(){
		$("body").css("overflow","");
		$("#advancedSearch").removeClass("on");
	});

	// $("[name='sage']").change(function(){
	// 	if($("[name='sage']").val()!="" && $("[name='eage']").val()!=""){
	// 		if($("[name='sage']").val() > $("[name='eage']").val()){
	// 			$("[name='eage']").val($("[name='sage']").val());
	// 		}
	// 	}
	// });

	$("[name='eage']").change(function(){
		if($("[name='sage']").val()!="" && $("[name='eage']").val()!=""){
			if($("[name='eage']").val() < $("[name='sage']").val()){
				$("[name='sage']").val($("[name='eage']").val());
			}
		}
	});
	$("#btnSearch").click(function(){
		list_page = 1;

        initCategory();

		//닫기
		$("#advancedSearch").removeClass("on");
		$("body").css("overflow","");

		//검색
		$(list_id).html("");
		getBoardList("Y");
	});


</script>
