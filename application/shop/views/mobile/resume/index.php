<?php
	$banner_list = getBannerList("BN00302041");
	$banner = null;
	if(count($banner_list) > 0){
		$banner = $banner_list[0];
	}
?>
<div class="page page-person">
    <div class="header is-transparency">
        <h1 class="top-logo"><span class="sr-text">지금 뜨는, 좋은 일자리 NOW JOB</span></h1>
        <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
            <span class="sr-text">미확인 알림메세지</span>
            <!-- 미확인 알림개수 -->
            <span class="cnt"><?=$g_alarm_cnt?></span>
        </a>
    </div><!-- //header -->

    <div class="page-content">
        <?php if($banner !=null) : ?>
        <div class="top-visual">
						<style>
						.page-person .top-visual .visual-content { margin-top: 4.7rem; /* 3.875rem; */ padding: 0 7.729468599vw 1.0vw; }
						</style>
            <div class="visual-content">
                <p><?=$banner["bn_text1"]?></p>
                <p class="headline" style="font-size: 6vw;"><?=$banner["bn_text2"]?></p>
                <a href="/compage/recruit_list" class="btn" style="position: absolute; top: 7.5rem; right: 1.5rem; border: 1px solid #57CCAF;">채용공고 작성</a>
            </div>
            <div class="visual-img">
                <img src="<?=$banner["bg_img"]?>" alt="">
            </div>
        </div>
        <?php endif; ?>

        <!-- 상세 검색 옵션 선택시: has-option 클래스 추가 -->
        <div class="search-option has-option">
            <!-- 현재 위치 -->
						<?php function fShortAddrDong($longaddr){
								$parts = explode(' ', $longaddr);
		    				return end($parts);//매지막 배열요소
						} ?>
						<style>
						.current-area { padding: 0.5rem 0 0.5rem; }
						.search-option.has-option .btn-option { top: 1.1rem; }
						</style>
            <div class="current-area">
                <div class="container">
                    <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel addrName"><?=fShortAddrDong($addr_info["ad_addr"])?></a>
                </div>
            </div>
            <!-- 선택된 카테고리 -->
            <div class="categories">
                <div class="container" id="tab_list">

                </div>
            </div>
            <a href="#advancedSearch" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text">상세검색 열기</span></a>
        </div>

        <div class="section is-last">
						<style>
						.section .container { padding-top: 0.5625rem; padding-bottom: 0.5625rem; }
						.item-info.column .thumb { padding-top: 75%; }
						.recomm-list .keen-slider__slide { max-width: 11.0rem; }
						.item-info .title-wrap { margin: 4px 0 4px 0; }
						.item-info.column .star-rating { margin-top: -80px; margin-right: 5px; }
						.item-info.column .condition { font-size: 0.65rem; margin-top: -3rem; }
						.item-info .condition { /* margin-top: -3px; */ }
						.item-info .condition .period { font-weight: 400; }
						.item-info .condition .working-time { font-weight: 400; /* font-size: 0.7rem; */ }
						.item-info .meta:last-child { min-height: 1rem; }
						.item-info .thumb { width: 5rem; }
						.item-info { padding: 0.5rem 0; margin: 5px 0; }
						.item-info .star-rating { margin-top: -1rem; }
						.item-info .hashtag { margin-top: 2px; }
						.item-info .period    { color: #3a8976; font-size: 0.8125rem; }
						.item-info .period em { color: #3a8976; font-style: normal; }
						.item-info .meta em { font-style: normal; }
						.item-info .working-day li { width: 1.8em; height: 1.8em; padding-left:0.1em; line-height: 1.4em; margin-right: 4px; text-indent: -2px; }
						</style>
            <div class="container">
                <h2 class="sr-text">채용공고</h2>
                <!-- 목록 -->
                <ol class="result-list" id="ajaxList" data-page="1">
                </ol>
                <!-- //목록 -->
                <!-- 빈목록 -->
	            <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
	                <p>등록된 정보가 없습니다.</p>
	            </div>
	            <!-- //빈목록 -->
            </div>
        </div>
        <div class="list-action">
            <a href="/resume/index_map" class="btn-map"><span class="sr-text">지도보기</span></a>
            <div class="send-request" style="display:none;">
                <button type="button" id="btnReqeust" class="btn-primary btn-request">제안하기</button>
            </div>
        </div>

	</div><!-- //page-content -->
	<!-- bottom-nav -->
    <?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
    <!-- //bottom-nav -->
</div>

<!-- 알림목록 -->
<?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>
<!-- //알림목록 -->

<!-- 상세검색 패널 -->
<?php include_once (get_base_url() . '/application/shop/views/mobile/resume/index_search.php'); ?>
<!-- //상세검색 패널 -->


<!-- 리스트 템플릿 -->
<template id="tpl_list">
    <li class="list-item">
        <div class="item-info person click-item"  data-link="/resume/resume_detail/{{ru_idx}}">
            <div class="thumb-wrap">
								<p class="recomm-badge" style="margin: 0; background: #57CCAF;"><span>추천</span></p>
                <button type="button" data-bk_type="RESUME" data-bk_key="{{ru_idx}}" data-user_id="{{mm_id}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button>
                <!-- 썸네일 -->
                <div class="thumb">
                    <a href="/resume/resume_detail/{{ru_idx}}"><img src="{{ru_resume_img}}" onError="this.src='/resource/shop/mobile/img/common/noimage3.png'" alt=""></a>
                </div>
                <!-- 평점 -->
                <p class="star-rating">
                    <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                    <span class="star" style="width:60%;"></span>
                    <span class="sr-text">평점</span>
                    <span class="sr-text">3</span>
                </p>
            </div>

            <div class="content-wrap">
                <div class="meta top">
                    <!-- 직종라벨 -->
                    <p class="category">
                        {{ru_work_img}}
                    </p>
                    <!-- 조건 -->
                    <p class="condition"><span class="working-time" style="color: #777; font-weight: 600;">{{ru_work_type}}</span></p>
                    <label class="form-checkbox" style="display:none;">
                        <input type="checkbox" name="chk[]" value="{{ru_idx}}" class="sr-text" onchange="selectRecruitList(1,this)">
                        <span class="label-red"></span>
                        <span class="sr-text">지원하기 선택</span>
                    </label>
                </div>
                <div class="title-wrap">
                    <!-- 이름 -->
                    <p class="title"><strong><a href="/resume/resume_detail/{{ru_idx}}">{{mm_name}}</a></strong></p>
                    <!-- 추가사항 -->
                </div>
                <ul class="working-day">
                    {{ru_week}}
                </ul>
								<div class="meta" style="display: block; margin-top: 2px;">
										<p style="display:inline-block;">{{mm_nation}}</p>
										<span class="nation_age_bar" style="display:none;">|</span>
										<p style="display:inline-block; font-weight: 600;">{{ma_age}}세</p>
										<p style="display:none;">({{distance}})</p>
										<em class="period" style="float: right; font-weight: 600; margin-top: -0.34rem;">{{ru_price_type}}</em>
								</div>
                <!-- 해시태그 -->
								<!--
                <div class="hashtag">
                    {{ru_my_type}}
                </div>
								-->
								<!-- <p class="condition"></p> -->
            </div>
        </div>
    </li>
</template>

<template id="tpl_category">
    <label class="form-toggle-btn2 sm {{active}} btn-category">
        <input type="checkbox" name="filter" value="{{category_name}}">
        <span class="cate">{{category_name}}</span>
    </label>
</template>

<input type="hidden" name="recruit_yn" value="<?=$recruit ==null ? "N":"Y"?>" />
<input type="hidden" name="mm_type" value="<?=$this->user->getUserType()?>" />

<!-- 제안 리스트 -->
<div id="selectPanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container">
        <div class="panel-header" style="background: #57CCAF; color: #fff; padding-top: 1.25rem; margin-bottom: 20px;">
            <!-- 약관제목 -->
            <h2 class="panel-title" style="color:#fff;">진행중인 채용공고</h2>
            <button type="button" aria-controls="selectPanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="terms-wrap">

                    <div class="post-list">
		                <ul class="list">
		                	<?php foreach($recruit_list as $row) : ?>
		                    <li style="border: 1px solid #ccc; border-radius: 10px; margin-bottom: 10px;">
		                        <div class="selectable-item">
		                            <label class="form-checkbox">
		                                <input type="radio" name="chk_request[]" value="<?=$row["rc_idx"]?>" data-rc_name="<?=$row["rc_name"]?>" >
		                                <span class="label"></span>
		                                <span class="sr-text">선택</span>
		                            </label>
		                            <a href="javascript:void(0);" class="angle-link" style="background:none;">
		                                <strong><?=$row["rc_name"]?></strong>
		                                <span class="date"><?=get_yyyymmdd($row["rc_sdate"])?> ~ <?=get_yyyymmdd($row["rc_edate"])?></span>
		                            </a>
		                        </div>
		                    </li>
		                    <?php endforeach; ?>
		                    <?php if(count($recruit_list)==0) : ?>
		                    <div class="is-empty-v2">
				                <p>등록된 채용공고가 없습니다.</p>
				            </div>
				            <?php endif; ?>
		                </ul>
		            </div>

                    <!-- //목록 -->
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="sticky-bottom view-footer">
                <button type="button" id="" aria-controls="terms2Panel" class="btnProcess btn btn-primary js-toggle-panel">제안 보내기</button>
            </div>
        </div>
    </div>
</div>
<!-- //제안 리스트 -->

<style>
    /* 팝업 스타일 */
    #applypop {
        position: fixed;
        top: 200px; /* 적절한 위치로 조정 */
        right: -4%;
        transform: translateX(-50%);
        background-color: #1B9D7D;
        color: white;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 100;
        white-space: nowrap;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
				z-index: 10;
        display: none; /* 초기에는 숨김 */
    }

    /* 팝업의 화살표 (아래쪽 삼각형) */
    #applypop::after {
        content: "";
        position: absolute;
        bottom: 18px;
        right: -29px;
        transform: translateX(-26%);
        border-width: 12px;
        border-style: solid;
        border-color: transparent transparent transparent #1B9D7D;
    }

</style>

<div id="applypop">
	한 번에 보낼 수 있는 <br>제안은 <font style="color: yellow;">1회</font> 입니다.
</div>

<script>
	$("#btnReqeust").click(function(){
        //로그인 여부 확인
        if($("#is_login").val()!="Y"){
            commonLogin();
            return;
        }

				if($("[name='mm_type']").val()!="COMP"){
        	commonAlert("일반회원은 지원 할 수 없습니다.",function(){});
            return;
        }
        //이력서 여부 확인
        if($("[name=recruit_yn]").val()=="N"){
            commonAlert("채용공고 등록및 공개채용공고 설정후 제안해주세요.",function(){});
            return;
        }

		var mq_idxs = new Array();

		for(var icnt =0;icnt < $("[name='chk[]']:checked").length;icnt++){
			mq_idxs.push($("[name='chk[]']:checked").eq(icnt).val());
		}

		//체크한 인재리스트
		if(mq_idxs.length==0){
			commonAlert("제안하실 인재를 선택해주세요.",function(){});
			return;
		}
		////console.log(mq_idxs);

		$("#selectPanel").addClass("on");
	});//btnReqeust

	$(".btnProcess").click(function(){

		var mq_idxs = new Array();

		for(var icnt =0;icnt < $("[name='chk[]']:checked").length;icnt++){
			mq_idxs.push($("[name='chk[]']:checked").eq(icnt).val());
		}

		//체크한 인재리스트
		if(mq_idxs.length==0){
			commonAlert("제안하실 인재를 선택해주세요.",function(){});
			return;
		}

		if($("[name='chk_request[]']:checked").length==0){
			commonAlert("제안하실 공고를 선택해주세요.",function(){});
			return;
		}

		var rc_idx = $("[name='chk_request[]']:checked").eq(0).val();
		var ru_title = $("[name='chk_request[]']:checked").eq(0).data('rc_name');

		var params = {};
		params["mq_idxs"] = mq_idxs; //resume
		params["mq_type"] = "RESUME";
		params["rc_idx"] = rc_idx;
		comAjax("/common_proc/member_request_check",params,function(ret_1){
				if(ret_1.code=="00"){
					var add_text = (ret_1.data.point_yn=="Y" ? "<br/>" + ret_1.data.point_price + "P 포인트가 차감됩니다." : "");

					openPopup('confirm', {
			        content: '<em>선택하신</em> 인재에게<br> 제안하시겠습니까?',
			        secondCallback: function () {
						    	comAjax("/common_proc/member_request",params,function(ret){
						        if(ret.code=="00"){
						        	commonAlert("채용공고로 제안하였습니다.",function(){
						        		$("[name='chk[]']").prop("checked",false);
												$("#selectPanel").removeClass("on");
						        	});
						        }else{
						            commonAlert(ret.msg,function(){ $("#selectPanel").removeClass("on"); });
						            return;
						        }
						    	});
			        }
			    });

			}else{
				commonAlert(ret_1.msg,function(){});
			}
		});

	});//btnProcess
</script>

<script>
	var ad_addr = "<?=$addr_info["ad_addr"]?>";
	var list_id = "#ajaxList";
    var list_empty_id = "#ajaxEmpty";
	var list_page = 1;
	var list_loading = false;

	var search_array = {
		text : "",
		skm : "",
		ekm : "",
		category : "",
		work_type : "",
		week : "",
		price_type : "",
		sex : "",
		sage : "",
		eage : "",
	};

	var tab_list = [];
	tab_list.push({df : "검색어",text : "",active : ""});		//0
	tab_list.push({df : "거리",text : "",active : ""});		//1
	tab_list.push({df : "카테고리",text : "",active : ""});	//2
	tab_list.push({df : "근무형태",text : "",active : ""});	//3
	tab_list.push({df : "근무요일",text : "",active : ""});	//4
	tab_list.push({df : "급여",text : "",active : ""});		//5
	tab_list.push({df : "성별",text : "",active : ""});		//6
	tab_list.push({df : "나이",text : "",active : ""});		//7

	$(document).ready(function(){
		//데이터 캐싱처리
		var params = {
			cashing_id: "resume_cashing",
			list_id: list_id,
			category_list_id : "#tab_list",
			scroll_obj : $(".page-recruit"),
			fn_cashing: initCashingData,
			fn_load: initLoadData,
		};
		$.CashingAjax(params);

        //카테고리 설정.
        initCategory();
	});

    $(document).on("click",".btn-category",function(){
        $("#advancedSearch").addClass("on");
    });

	$(document).on("click",".form-checkbox",function(e){
		e.stopPropagation();
	});

	//캐싱처리 추가 작업
	function initCashingData() {
		list_page = $(list_id).attr("data-page");
		showEmpty();
	}

	//데이터 호출 처리
	function initLoadData() {
		getBoardList("");
	}

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

		//검색어 설정
		search_array.text = $("[name='text']").val();
		search_array.skm = $("[name='skm']").val();
		search_array.ekm = $("[name='ekm']").val();
		search_array.category = arr_category;
		search_array.work_type = arr_work_type;
		search_array.week = arr_week;
		search_array.price_type = arr_price_type;
		search_array.sex = arr_sex;
		search_array.sage = $("[name='sage']").val();
		search_array.eage = $("[name='eage']").val();

		//초기화
		for(var icnt=0;icnt < tab_list.length;icnt++){
			tab_list[icnt].text = "";
			tab_list[icnt].active = "";
		}

        //검색어
		if(search_array.text!=""){
            tab_list[0].text = search_array.text;
            tab_list[0].active = "on";
		}

        //거리
		if(ad_addr !="지역 선택 없음"){
			if(search_array.ekm!=""){
				tab_list[1].text = "~" + search_array.ekm + "km";
				tab_list[1].active = "on";
			}
		}

        //카테고리
        if(search_array.category!=""){
            tab_list[2].text = search_array.category.replaceAll("^",",");
            tab_list[2].active = "on";
		}

        //근무형태
        if(search_array.work_type!=""){
            tab_list[3].text = search_array.work_type.replaceAll("^",",");
            tab_list[3].active = "on";
		}

        //근무요일
        if(search_array.week!=""){
            if(search_array.week=="일^월^화^수^목^금^토"){
                tab_list[4].text = "일~토";
            }else {
                tab_list[4].text = search_array.week.replaceAll("^",",");
            }
            tab_list[4].active = "on";
		}

        //급여
        if(search_array.price_type!=""){
            tab_list[5].text = search_array.price_type.replaceAll("^",",");
            tab_list[5].active = "on";
		}

        //성별
        if(search_array.sex!=""){
            tab_list[6].text = search_array.sex.replaceAll("^",",");
            tab_list[6].active = "on";
		}

        //나이
		if(search_array.sage!="" || search_array.eage!=""){
            if(search_array.sage!="" && search_array.eage==""){
                tab_list[7].text = search_array.sage + "세 ~";
                tab_list[7].active = "on";
            }else if(search_array.sage=="" && search_array.eage!=""){
                tab_list[7].text = " ~" + search_array.eage + "대";
                tab_list[7].active = "on";
            }else if(search_array.sage!="" && search_array.eage!=""){
                tab_list[7].text = search_array.sage + "대" + " ~ " + search_array.eage + "대";
                tab_list[7].active = "on";
            }
		}
    }

	function getBoardList(save_yn){
		//alert('getBoardList');//test
		var params = {};
		params["page"] = list_page;

		params["text"] = search_array.text;
		params["skm"] = search_array.skm;
		params["ekm"] = search_array.ekm;
		params["category"] = search_array.category;
		params["work_type"] = search_array.work_type;
		params["week"] = search_array.week;
		params["price_type"] = search_array.price_type;
		params["sex"] = search_array.sex;
		params["sage"] = search_array.sage;
		params["eage"] = search_array.eage;
        params["save_yn"] = save_yn;

		//탭 UI정리
		initTabUi();

		//params["page"] = 2;//test
		//alert(params["page"]);//test

		comAjax("/resume_proc/search_list",params,function(ret){
			//console.log(ret);//test
			if(ret.code=="00"){
				list_page++;

				$(list_id).attr("data-page",list_page);

				showBoardList(ret.data.result_list);
			}else{

			}
		});
	}

	function initTabUi(){

		//카테고리 구성
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

	//데이터 보여주기
	function showBoardList(list){
		list.forEach(function(row){
			var $clone  = $("#tpl_list").clone();
    		var $content = $clone.html();
            var cate_type = "cate1";
            if(row.bd_cate=="업무"){
                cate_type = "cate1";
            }else if(row.bd_cate=="일상"){
                cate_type = "cate2";
            }else if(row.bd_cate=="질문"){
                cate_type = "cate3";
            }

						////console.log(row);
						if(row.pm_idx==null){
							$content = $content.replaceAll("<p class=\"recomm-badge\" style=\"margin: 0; background: #57CCAF;\"><span>추천</span></p>","");
							//$content = $content.replaceAll("추천1","");
							////console.log('replace');
						}else{
							////console.log('no replace');
						}

    		$content = $content.replaceAll("{{ru_idx}}",row.ru_idx);
    		$content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");
			//$content = $content.replaceAll("{{mm_name}}",row.mm_name);
			$content = $content.replaceAll("{{mm_name}}",row.ru_name);
			$content = $content.replaceAll("{{ru_week}}",row.ru_week);
			$content = $content.replaceAll("{{distance}}",row.distance + "km");
			$content = $content.replaceAll("{{ru_work_img}}",row.ru_work_img);
			if(row.mm_nation.replaceAll(" ","") != ''){
				$content = $content.replaceAll("{{mm_nation}}",row.mm_nation);
			}else{
				$content = $content.replaceAll("<p style=\"display:inline-block;\">{{mm_nation}}</p>","<p style=\"display:none;\"></p>");
			}
			// 서버에서 이미 계산된 mm_age 사용 (범위: 20, 30, 40 등)
			if(row.mm_age=='0' || row.mm_age==''){
				$content = $content.replaceAll("{{ma_age}}세","");
			}else{
				$content = $content.replaceAll("{{ma_age}}",row.mm_age);
			}
			$content = $content.replaceAll("{{ru_price_type}}",row.ru_price_type);
			$content = $content.replaceAll("{{ru_my_type}}",row.ru_my_type);
			$content = $content.replaceAll("{{ru_work_type}}",fWorkColor(row.ru_work_type));
			$content = $content.replaceAll("{{ru_resume_img}}",row.ru_resume_img);
			$content = $content.replaceAll("{{mm_id}}",row.mm_id);
			$content = $content.replaceAll("{{distance}}",row.distance);
			////console.log(row);
			// if(row.mm_nation.replaceAll(" ","") != '' && row.ma_age.replaceAll(" ","") != ''){
			// 	$content = $content.replaceAll("class=\"nation_age_bar\" style=\"display:none;\"","class=\"nation_age_bar\" style=\"display:inline-block;\"");
			// }
			if(row.mm_nation.replaceAll(" ","") != '' && getKoreanAge(row.mm_birth) != '0'){
				$content = $content.replaceAll("class=\"nation_age_bar\" style=\"display:none;\"","class=\"nation_age_bar\" style=\"display:inline-block;\"");
			}

    		//복사한 내용 다시 넣기
    		$clone.html($content);

    		$(list_id).append($clone.html());
		});

       	showEmpty();
	}

	function fWorkColor(arg){
  	arg = arg.replaceAll("주간", "<font style='color:#3a70db;'>주간</font>"); //파랑
		arg = arg.replaceAll("야간", "<font style='color:#F86048;'>야간</font>"); //빨강
		arg = arg.replaceAll("2교대", "<font style='color:#1B9D7D;'>2교대</font>"); //민트
		arg = arg.replaceAll("근무협의", "<font style='color:#333;'>근무협의</font>"); //검정
		//arg = arg.replaceAll("협의", "<font style='color:#333;'>협의</font>"); //검정

		return arg;
	}

	function showEmpty(){
		//리스트 없으면 숨김처리
        if($(list_id).find("li").length == 0){
            $(list_empty_id).show();
            $(list_id).hide();
        }else{
            $(list_id).show();
            $(list_empty_id).hide();
        }
	}

	$('.page').scroll(function(){
		//console.log('page:'+$(window).height());//test
		var $page = $(this);
		var scrollTop = $page.scrollTop();
		//console.log(scrollTop);//test

		if(list_loading===false){

			//console.log(scrollTop + ':' + $(window).height());//test
			if(scrollTop +100 >= $(window).height())
		    {
		    	list_loading = true;

		        //0.3초 후부터 재로딩 가능함.
		        setTimeout(function(){
		        	list_loading = false;
		        },300);

		        //리스트 로드
		        getBoardList("");
		    }
		}

	});
	// 페이지로딩
	$(window).scroll(function(){
		var $window = $(this);
		var scrollTop = $window.scrollTop() + $window.height();
		var windowHeight = $window.height();
		var documentHeight = $(list_id).height();
		////console.log(scrollTop);//test
		if(list_loading==false){

			////console.log(scrollTop+400 + ':' + documentHeight);//test
			if(scrollTop+400>=documentHeight)
		    {
		    	list_loading = true;

		        //0.3초 후부터 재로딩 가능함.
		        setTimeout(function(){
		        	list_loading = false;
		        },300);

		        //리스트 로드
		        getBoardList("");
		    }
		}
	});


</script>

<!--퍼블 소스-->
<script>

    /* 체크박스 선택시 지원하기 카운터 개수 변경 */
    function updateCounter (num) {
        if(!document.querySelector('.send-request')) return;
        document.querySelector('.send-request .selected').innerHTML = num;
    }

    /* 체크박스 선택 제한 툴팁 표시 */
    function selectRecruitList (limitNum, elm) {
				$('.send-request').show();

				let rect = elm.getBoundingClientRect();
				let popup = document.getElementById("applypop");
        //popup.style.top = (rect.top + scrollY - 22) + "px"; // 버튼과 높이 정렬
				popup.style.top = (rect.top - 24) + "px"; // 버튼과 높이 정렬
				$('#applypop').show();
				$("#applypop").fadeOut(3000);

        var checkboxList = document.querySelectorAll('.form-checkbox input');
        var selectedCheckbox = document.querySelectorAll('.form-checkbox input:checked');
        var tooltip = document.querySelector('.tooltip');

        if(!elm.checked || (elm.checked && selectedCheckbox.length <= limitNum)) {
            updateCounter(selectedCheckbox.length);
            return;
        }

        elm.checked = false;

        if (!tooltip) {
            tooltip = document.createElement('DIV');
            tooltip.setAttribute('class', 'tooltip middle-left');
            tooltip.innerHTML = '한번에 보낼 수 있는<br> 입사지원은 <em class="cnt">'+ limitNum +'회</em> 입니다';
        }

        elm.parentNode.parentNode.appendChild(tooltip);

        tooltip.style.top = elm.offsetTop + (elm.parentNode.offsetHeight / 2) - (tooltip.offsetHeight / 2) + 'px';
        tooltip.style.left = elm.offsetLeft - tooltip.offsetWidth - 20 + 'px';

        tooltip.classList.add('on');

        window.addEventListener('click', function (e) {
            if(!e.target.classList || (e.target.classlist && !e.target.classList.contains('.tooltip'))) return;
            tooltip.classList.remove('on');
        }, {once : true});
    }
</script>

<script>
// 검색 완료 선택 처리
function selectAddrInfo(zipcode,doro,jibun,addr,x,y){
    var params = {};
    params["ad_zipcode"] = zipcode;
    params["ad_jibun"] = jibun;
    params["ad_doro"] = doro;
    params["ad_addr"] = addr;
    params["ad_gps_x"] = x;
    params["ad_gps_y"] = y;

    comAjax("/common_proc/set_addr_info",params,function(ret){
        if(ret.code=="00"){
            location.reload();
        }else{
            commonAlert(ret.msg,function(){});
            return;
        }
    });
}
</script>
<?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>
