<div class="dropdown-wrap">
	<a href="#topSearch" role="button" aria-expanded="false" aria-controls="topSearch" class="btn-top-search js-toggle-dropdown"><span class="sr-text">전체검색</span></a>
	<!-- 전체검색 -->
	<div id="topSearch" class="dropdown top-search">
	    <form action="/">
	        <div class="content-wrap">
	            <div class="container">

	                <!-- 검색어 입력 -->
	                <div class="search-form">
	                    <label>
	                        <span class="sr-text">검색어 입력</span>
	                        <input type="search" name="g_keyword" autocomplete="off" autofocus placeholder="검색어를 입력해 주세요" aria-required="true" >
	                    </label>
	                    <button type="button" id="btnTopSearch" class="btn-search"><span class="sr-text">검색하기</span></button>
	                </div>

	                <!-- 검색 옵션 선택 -->
	                <!--div class="search-option">
	                    <div class="col">
	                        <div class="form-row">
	                            <p class="form-label">카테고리</p>
	                            <div class="form-group col-4">
	                                 <?php foreach($g_wrok_list as $key=>$row) : ?>
	                                <label class="form-toggle-btn2">
	                                    <input type="checkbox" name="g_category[]" value="<?=$key?>" >
	                                    <span class="btn"><?=$key?></span>
	                                </label>
	                                <?php endforeach;?>
	                            </div>
	                        </div>
	                        <div class="form-row">
	                            <p class="form-label">근무형태</p>
	                            <div class="form-group col-4">
	                                 <?php foreach($g_work_type_list as $row) : ?>
	                                <label class="form-toggle-btn2">
	                                    <input type="checkbox" name="g_work_type[]" value="<?=$row["cd_val"]?>" >
	                                    <span class="btn"><?=$row["cd_val"]?></span>
	                                </label>
	                                <?php endforeach;?>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col">
	                        <div class="form-row">
	                            <p class="form-label">근무요일
	                                <span class="option">
	                                    <label class="form-checkbox">
	                                        <input type="checkbox" name="g_week_yn" value="Y" class="sr-text">
	                                        <span class="label">요일무관</span>
	                                    </label>
	                                </span>
	                            </p>
	                            <div class="form-group is-justify">
	                                <?php foreach($g_week_list as $row) : ?>
	                                <label class="form-toggle-btn2 sm">
	                                    <input type="checkbox" name="g_week[]" value="<?=$row["cd_val"]?>">
	                                    <span class="btn"><?=$row["cd_val"]?></span>
	                                </label>
	                                <?php endforeach;?>
	                            </div>
	                        </div>

	                        <div class="form-row">
	                            <p class="form-label">급여</p>
	                            <div class="form-group col-3">
	                                <?php foreach($g_price_type_list as $row) : ?>
	                                <label class="form-toggle-btn2">
	                                    <input type="checkbox" name="g_price_type[]" value="<?=$row["cd_val"]?>">
	                                    <span class="btn"><?=$row["cd_val"]?></span>
	                                </label>
	                                <?php endforeach;?>
	                            </div>
	                        </div>

	                        <div class="form-row">
	                            <p class="form-label">성별</p>
	                            <div class="form-group col-3">
	                                <label class="form-toggle-btn2">
	                                    <input type="checkbox" name="g_sex[]" value="남자">
	                                    <span class="btn">남자</span>
	                                </label>
	                                <label class="form-toggle-btn2">
	                                    <input type="checkbox" name="g_sex[]" value="여자">
	                                    <span class="btn">여자</span>
	                                </label>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col">

	                        <div class="form-row">
	                            <p class="form-label">나이</p>
	                            <div class="inline-group">
	                                <label class="form-selectbox col">
	                                    <span class="sr-text">최소나이 선택</span>
	                                    <select name="g_sage">
	                                        <option value="">선택</option>
	                                        <?php for($icnt=2;$icnt < 7;$icnt++) : ?>
	                                        	<option value="<?=$icnt * 10?>"><?=$icnt * 10?>세</option>
	                                        <?php endfor; ?>
	                                    </select>
	                                    <span class="liner"></span>
	                                </label>
	                                <span class="entity">~</span>
	                                <label class="form-selectbox col">
	                                    <span class="sr-text">최대나이 선택</span>
	                                    <select name="g_eage">
	                                    	<option value="">선택</option>
	                                        <?php for($icnt=2;$icnt < 8;$icnt++) : ?>
	                                        	<option value="<?=$icnt * 10?>"><?=$icnt * 10?>세</option>
	                                        <?php endfor; ?>
	                                    </select>
	                                    <span class="liner"></span>
	                                </label>
	                            </div>
	                        </div>

	                        <div class="form-row">
	                            <p class="form-label">지역</p>
	                            <div class="inline-group current-area-form">
	                                <label class="form-selectbox col">
	                                    <span class="sr-text">시 선택</span>
	                                    <select name="g_addr1">
	                                    	<option value="">선택</option>
	                                    	<?php foreach($g_addr_list as $key1 => $row) : ?>
	                                        <option value="<?=$key1?>"><?=$key1?></option>
	                                        <?php endforeach; ?>
	                                    </select>
	                                    <span class="liner"></span>
	                                </label>
	                                <span class="entity">~</span>
	                                <label class="form-selectbox col">
	                                    <span class="sr-text">구군 선택</span>
	                                    <select id="g_addr2" name="g_addr2">
	                                    	<option value="">선택</option>
	                                        <?php foreach($g_addr_list as $key1 => $row) : ?>
	                                        	<?php foreach($row as $key2=> $row2) : ?>
	                                        		<option data-addr1="<?=$key1?>" value="<?=$key2?>" style="display:none;"><?=$key2?></option>
	                                        	<?php endforeach; ?>
	                                        <?php endforeach; ?>
	                                    </select>
	                                    <span class="liner"></span>
	                                </label>
	                            </div>
	                        </div>

	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="search-history">
	            <div class="container">
	                <ul class="added-list" id="search_key_list">
	                	<?php foreach($g_tag_list as $tag) : ?>
	                    <li>
	                        <a class="btnTag" href="javascript:void(0);" data-tag="<?=$tag?>">#<?=$tag?></a>
	                    </li>
	                    <?php endforeach;?>
	                </ul>
	            </div>
	        </div-->
	    </form>
	</div>
	<!-- //전체검색 -->
</div>
<script>
	$(".btnTag").click(function(){
		var ar_cate = ["생산","물류","건설","매장관리","식당","서비스","운전배달","고객상담","사무직","디자인","기타","상담"];
		var ar_yoil = ["일","월","화","수","목","금","토","요일무관"];
		var ar_price = ["일급","주급","월급"];
		var ar_work = ["주간","야간","2교대"];
		var ar_sex = ["남자","여자"];
		var tag = $(this).attr("data-tag");

		if(ar_cate.indexOf(tag) > -1){
			top_search_array.category = tag;
		}

		if(ar_yoil.indexOf(tag) > -1){
			if(tag=="요일무관"){
				top_search_array.week_yn = tag;
			}else{
				top_search_array.week = tag;
			}
		}

		if(ar_price.indexOf(tag) > -1){
			top_search_array.price_type = tag;
		}

		if(ar_work.indexOf(tag) > -1){
			top_search_array.work_type = tag;
		}

		if(ar_sex.indexOf(tag) > -1){
			top_search_array.sex = tag;
		}

		localStorage.setItem("top_search_array",JSON.stringify(top_search_array));
		localStorage.setItem("top_search_yn","Y");
		location.href="/recruit/wlist";
	});

	$("[name=g_addr1]").change(function(){
		var val = $(this).val();
		$("[name=g_addr2] option").not(":first").hide();
		$("[name=g_addr2]").val("");
		$("[name=g_addr2]").closest(".form-selectbox").find(".liner").text("선택");
		if($(this).val()!=""){
			$('[name=g_addr2] option').each(function(idx) {
	            var addr1 = $(this).attr('data-addr1'); // 선택 여부
				if(val==addr1){
					$("[name=g_addr2] option").eq(idx).show();
				}
	        });
	        $("#g_addr2 option").eq(0).attr("selected","selected");
		}
	});

	$("[name=g_week_yn]").click(function(){
		if($(this).is(":checked")){
			$("[name='g_week[]']").prop("checked",true);
		}else{
			$("[name='g_week[]']").prop("checked",false);
		}
	});

	//검색 저장
	var top_search_array = {
		text : "",
		category : "",
		work_type : "",
		week : "",
		week_yn : "",
		price_type : "",
		sex : "",
		sage : "",
		eage : "",
		addr1 : "",
		addr2 : "",
		addr3 : "",
	};

	//검색
	$("#btnTopSearch").click(function(){
		setTopSearch();
	});

	var top_search_settion = localStorage.getItem("top_search_array");
	if(top_search_settion!=null){
		getTopSearch();
	}

	function getTopSearch(){
		var info = JSON.parse(top_search_settion);
		var arr_category = info.category.split("^");
        var arr_work_type = info.work_type.split("^");
        var arr_price_type = info.price_type.split("^");
        var arr_week = info.week.split("^");
        var arr_sex = info.sex.split("^");

		$("[name='g_keyword']").val(info.text);
		$("[name='g_sage']").val(info.sage);
		$("[name='g_eage']").val(info.eage);
		$("[name='g_addr1']").val(info.addr1);
		$("[name='g_addr2']").val(info.addr2);

		if(info.addr1!=""){
			$("[name=g_addr2] option").not(":eq(0)").hide();
			$("[name=g_addr2]").val("");

			$('[name=g_addr2] option').each(function(idx) {
	            var addr1 = $(this).attr('data-addr1'); // 선택 여부
				if(info.addr1==addr1){
					$("[name=g_addr2] option").eq(idx).show();
					$("[name='g_addr2']").val(info.addr2);
				}
	        });
		}else{
			$("[name=g_addr2] option").not(":eq(0)").hide();
			$("[name=g_addr2] option").eq(0).prop("selected",true);
		}


		if(info.week_yn=="Y"){
			$("[name='g_week_yn']").prop("checked",true);
		}

		for(var icnt=0;icnt < arr_category.length;icnt++){
			for(var jcnt=0;jcnt < $("[name='g_category[]']").length;jcnt++){
				if($("[name='g_category[]']").eq(jcnt).val()==arr_category[icnt]){
					$("[name='g_category[]']").eq(jcnt).prop("checked",true);
				}
			}
		}

		for(var icnt=0;icnt < arr_work_type.length;icnt++){
			for(var jcnt=0;jcnt < $("[name='g_work_type[]']").length;jcnt++){
				if($("[name='g_work_type[]']").eq(jcnt).val()==arr_work_type[icnt]){
					$("[name='g_work_type[]']").eq(jcnt).prop("checked",true);
				}
			}
		}

		for(var icnt=0;icnt < arr_price_type.length;icnt++){
			for(var jcnt=0;jcnt < $("[name='g_price_type[]']").length;jcnt++){
				if($("[name='g_price_type[]']").eq(jcnt).val()==arr_price_type[icnt]){
					$("[name='g_price_type[]']").eq(jcnt).prop("checked",true);
				}
			}
		}

		for(var icnt=0;icnt < arr_week.length;icnt++){
			for(var jcnt=0;jcnt < $("[name='g_week[]']").length;jcnt++){
				if($("[name='g_week[]']").eq(jcnt).val()==arr_week[icnt]){
					$("[name='g_week[]']").eq(jcnt).prop("checked",true);
				}
			}
		}

		for(var icnt=0;icnt < arr_sex.length;icnt++){
			for(var jcnt=0;jcnt < $("[name='g_sex[]']").length;jcnt++){
				if($("[name='g_sex[]']").eq(jcnt).val()==arr_sex[icnt]){
					$("[name='g_sex[]']").eq(jcnt).prop("checked",true);
				}
			}
		}
	}

	//검색샐정
	function setTopSearch(){
		var arr_category = "";
        var arr_work_type = "";
        var arr_price_type = "";
        var arr_week = "";
        var arr_sex = "";

        for(var icnt=0;icnt < $("[name='g_category[]']:checked").length;icnt++){
            if($("[name='g_category[]']:checked").eq(icnt).val()!=""){
                if(arr_category==""){
                    arr_category = $("[name='g_category[]']:checked").eq(icnt).val();
                }else{
                    arr_category += "^" + $("[name='g_category[]']:checked").eq(icnt).val();
                }
            }
        }

        for(var icnt=0;icnt < $("[name='g_work_type[]']:checked").length;icnt++){
            if($("[name='g_work_type[]']:checked").eq(icnt).val()!=""){
                if(arr_work_type==""){
                    arr_work_type = $("[name='g_work_type[]']:checked").eq(icnt).val();
                }else{
                    arr_work_type += "^" + $("[name='g_work_type[]']:checked").eq(icnt).val();
                }
            }
        }

        for(var icnt=0;icnt < $("[name='g_price_type[]']:checked").length;icnt++){
            if($("[name='g_price_type[]']:checked").eq(icnt).val()!=""){
                if(arr_price_type==""){
                    arr_price_type = $("[name='g_price_type[]']:checked").eq(icnt).val();
                }else{
                    arr_price_type += "^" + $("[name='g_price_type[]']:checked").eq(icnt).val();
                }
            }
        }

        for(var icnt=0;icnt < $("[name='g_week[]']:checked").length;icnt++){
            if($("[name='g_week[]']:checked").eq(icnt).val()!=""){
                if(arr_week==""){
                    arr_week = $("[name='g_week[]']:checked").eq(icnt).val();
                }else{
                    arr_week += "^" + $("[name='g_week[]']:checked").eq(icnt).val();
                }
            }
        }

        for(var icnt=0;icnt < $("[name='g_sex[]']:checked").length;icnt++){
            if($("[name='g_sex[]']:checked").eq(icnt).val()!=""){
                if(arr_sex==""){
                    arr_sex = $("[name='g_sex[]']:checked").eq(icnt).val();
                }else{
                    arr_sex += "^" + $("[name='g_sex[]']:checked").eq(icnt).val();
                }
            }
        }

		//검색어 설정
		top_search_array.text = $("[name='g_keyword']").val();
		top_search_array.category = arr_category;
		top_search_array.work_type = arr_work_type;
		top_search_array.week = arr_week;
		top_search_array.week_yn = $("[name=g_week_yn]:checked").val();
		top_search_array.price_type = arr_price_type;
		top_search_array.sex = arr_sex;
		top_search_array.sage = $("[name='g_sage']").val();
		top_search_array.eage = $("[name='g_eage']").val();
		top_search_array.addr1 = $("[name='g_addr1']").val();
		top_search_array.addr2 = $("[name='g_addr2']").val();
		top_search_array.addr3 = "";
		localStorage.setItem("top_search_array",JSON.stringify(top_search_array));
		localStorage.setItem("top_search_yn","Y");

		location.href="/recruit/wlist";
	}

</script>
