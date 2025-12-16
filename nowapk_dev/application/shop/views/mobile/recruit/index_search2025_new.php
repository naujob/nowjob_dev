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
                                <?php foreach($wrok_list as $row) : ?>
                                <label class="form-toggle-btn2 sm" style="width: 22%;">
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
                                <label class="form-toggle-btn2" style="width: 22%;">
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
                                <label class="form-toggle-btn2" style="width: 22%;">
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

                        <div class="form-row" style="margin-bottom: 50px;">
                            <p class="form-label">나이</p>
                            <div class="inline-group" style="display: block;">
                                <div class="col" style="width: 22%; float: left;">
                                    <select name="sage" style="width:100%;height:36px;padding-left:12px;border: 1px solid #BEBFC9; border-radius: 18px;">
                                    	<option value="">선택</option>
                                    	<?php for($icnt=20;$icnt <= 80;$icnt++) : ?>
                                    	<option value="<?=$icnt?>" <?=$search_txt["sage"]==$icnt ? "selected":""?>><?=$icnt?>세</option>
                                    	<?php endfor;?>
                                    </select>
                                </div>
                                <span class="entity" style="display: inline-block; float: left; margin-top: 7px;">~</span>
                                <div class="col" style="width: 22%; float: left;">
                                    <select name="eage" style="width:100%;height:36px;padding-left:12px;border: 1px solid #BEBFC9; border-radius: 18px;">
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
            <div class="sticky-bottom">
                <button id="btnSearch" type="button" class="btn btn-primary">검색하기</button>
            </div>
        </div>
    </div>
</div>

<script>
  function initCategory(){
    var arr_category = "";
    var arr_work_type = "";
    var arr_price_type = "";
    var arr_week = "";
    var arr_sex = "";

    for(var icnt=0;icnt < $("[name='category[]']:checked").length;icnt++){
      if($("[name='category[]']:checked").eq(icnt).val()!=""){
        if(arr_category==""){
          arr_category = $("[name='category[]']:checked").eq(icnt).val();
        }else{
          arr_category += "^" + $("[name='category[]']:checked").eq(icnt).val();
        }
      }
    }

    for(var icnt=0;icnt < $("[name='work_type[]']:checked").length;icnt++){
      if($("[name='work_type[]']:checked").eq(icnt).val()!=""){
        if(arr_work_type==""){
          arr_work_type = $("[name='work_type[]']:checked").eq(icnt).val();
        }else{
          arr_work_type += "^" + $("[name='work_type[]']:checked").eq(icnt).val();
        }
      }
    }

    for(var icnt=0;icnt < $("[name='price_type[]']:checked").length;icnt++){
      if($("[name='price_type[]']:checked").eq(icnt).val()!=""){
        if(arr_price_type==""){
          arr_price_type = $("[name='price_type[]']:checked").eq(icnt).val();
        }else{
          arr_price_type += "^" + $("[name='price_type[]']:checked").eq(icnt).val();
        }
      }
    }

    for(var icnt=0;icnt < $("[name='week[]']:checked").length;icnt++){
      if($("[name='week[]']:checked").eq(icnt).val()!=""){
        if(arr_week==""){
          arr_week = $("[name='week[]']:checked").eq(icnt).val();
        }else{
          arr_week += "^" + $("[name='week[]']:checked").eq(icnt).val();
        }
      }
    }

    for(var icnt=0;icnt < $("[name='sex[]']:checked").length;icnt++){
      if($("[name='sex[]']:checked").eq(icnt).val()!=""){
        if(arr_sex==""){
          arr_sex = $("[name='sex[]']:checked").eq(icnt).val();
        }else{
          arr_sex += "^" + $("[name='sex[]']:checked").eq(icnt).val();
        }
      }
    }

    search_array.category = arr_category;
    search_array.work_type = arr_work_type;
    search_array.week = arr_week;
    search_array.price_type = arr_price_type;
    search_array.sex = arr_sex;
    search_array.sage = $("[name='sage']").val();
    search_array.eage = $("[name='eage']").val();

    for(var icnt=0;icnt < tab_list.length;icnt++){
      tab_list[icnt].text = "";
      tab_list[icnt].active = "";
    }

    if(search_array.category!=""){
      tab_list[0].text = search_array.category.replaceAll("^",",");
      tab_list[0].active = "on";
    }

    if(search_array.work_type!=""){
      tab_list[1].text = search_array.work_type.replaceAll("^",",");
      tab_list[1].active = "on";
    }

    if(search_array.week!=""){
      if(search_array.week=="일^월^화^수^목^금^토"){
        tab_list[2].text = "일~토";
      }else {
        tab_list[2].text = search_array.week.replaceAll("^",",");
      }
      tab_list[2].active = "on";
    }

    if(search_array.price_type!=""){
      tab_list[3].text = search_array.price_type.replaceAll("^",",");
      tab_list[3].active = "on";
    }

    if(search_array.sex!=""){
      tab_list[4].text = search_array.sex.replaceAll("^",",");
      tab_list[4].active = "on";
    }

    if(search_array.sage!="" || search_array.eage!=""){
      if(search_array.sage!="" && search_array.eage==""){
        tab_list[5].text = search_array.sage + "세 ~";
        tab_list[5].active = "on";
      }else if(search_array.sage=="" && search_array.eage!=""){
        tab_list[5].text = " ~" + search_array.eage + "세";
        tab_list[5].active = "on";
      }else if(search_array.sage!="" && search_array.eage!=""){
        tab_list[5].text = search_array.sage + "세" + " ~ " + search_array.eage + "세";
        tab_list[5].active = "on";
      }
    }
  }

  function initTabUi(){
    $("#tab_list").html("");
    for(var icnt=0;icnt < tab_list.length;icnt++){
      var $clone  = $("#tpl_category").clone();
      var $content = $clone.html();
      $content = $content.replaceAll("{{active}}",tab_list[icnt].active);
      if(tab_list[icnt].active =="on"){
        $content = $content.replaceAll("{{category_name}}",tab_list[icnt].text);
      }else{
        $content = $content.replaceAll("{{category_name}}",tab_list[icnt].df);
      }
      $("#tab_list").append($content);
    }
  }
  
	$("[name='workingDay']").click(function(){
		$("[name='week[]']").prop("checked",$(this).is(":checked"));
	});

	$(".btn-close-panel").click(function(){
		$("body").css("overflow","auto");
    setTimeout(function(){ $("body").css("overflow","auto"); }, 500);
		$("#advancedSearch").removeClass("on");
	});

	$("[name='sage']").change(function(){
		if($("[name='sage']").val()!="" && $("[name='eage']").val()!=""){
			if($("[name='sage']").val() > $("[name='eage']").val()){
				$("[name='eage']").val($("[name='sage']").val());
			}
		}
	});

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
    initTabUi();
		//닫기
		$("#advancedSearch").removeClass("on");
		$("body").css("overflow","auto");

		//검색
		$(list_id).html("");
		getBoardList("Y");
	});

</script>
