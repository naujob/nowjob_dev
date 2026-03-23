<!-- 상세검색 패널 -->
<div id="advancedSearch" class="layer-panel" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header">
            <h2 class="panel-title">중고거래 상세검색</h2>
            <button type="button" class="btn-close-panel"><span class="sr-text">닫기</span></button>
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
                                <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel">인천 연수구</a>
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

                        <!-- 20221107 수정 -->
                        <div class="form-row">
                            <p class="form-label">카테고리 <em>다중선택가능</em></p>
                            <div class="form-toggle-group col-4">
                                <label class="form-toggle-btn2 sm">
                                    <input type="checkbox" name="category[]" value="전체"  class="sr-text js-sel-all">
                                    <span class="btn">전체</span>
                                </label>
                                <?php foreach($category_list as $row) : ?>
                                <label class="form-toggle-btn2 sm">
                                    <input type="checkbox" name="category[]" data-select-limit="2" value="<?=$row["cd_val"]?>" data-img="<?=$row["cd_val2"]?>" <?=in_array_return($row["cd_val"],$search_txt["category"],"check")?>>
                                    <span class="btn"><?=$row["cd_val"]?></span>
                                </label>
                                <?php endforeach;?>
                            </div>
                        </div>
                        <div class="form-row">
                            <p class="form-label">판매가</p>

                            <div class="js-range-slider">
                                <div class="min">
                                    <!-- 최소값 -->
                                    <input type="range" name="sprice" min="0" max="100" step="5" data-unit="만원" value="<?=$search_txt["sprice"]?>">
                                    <div class="thumb"><output class="label"><?=$search_txt["sprice"]?></output></div>
                                </div>
                                <div class="max">
                                    <!-- 최대값 -->
                                    <input type="range" name="eprice" min="5" max="100" step="5" data-unit="만원" value="<?=$search_txt["eprice"]?>">
                                    <div class="thumb"><output class="label"><?=$search_txt["eprice"]?></output></div>
                                </div>
                                <div class="fill"></div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom view-footer">
                <button type="button" id="btnSearch"class="btn btn-primary">검색하기</button>
            </div>
        </div>
    </div>
</div>
<!-- //상세검색 패널 -->
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
<script>
	$(".btn-close-panel").click(function(){
		$("body").css("overflow","");
		$("#advancedSearch").removeClass("on");
	});

	$("#btnSearch").click(function(){
		list_page = 1;

        initCategory();

		//닫기
		$("body").css("overflow","");
		$("#advancedSearch").removeClass("on");

		//검색
		$(list_id).html("");
		getBoardList("Y");
	});


</script>
