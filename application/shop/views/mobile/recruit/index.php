<?php
	$banner_list = getBannerList("BN00010001");
	$banner = null;
	if(count($banner_list) > 0){
		$banner = $banner_list[0];
	}
?>
<div class="page-recruit">
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
						.top-visual .visual-content { padding: 0 7.729468599vw 6.14vw; }
						</style>
            <div class="visual-content" style="margin: 4.7rem 49.2753623188vw 0.0rem 0; padding: 0 7.729468599vw 1.0vw;">
                <p><?=$banner["bn_text1"]?></p>
                <p class="headline" style="font-size: 6vw;"><?=$banner["bn_text2"]?></p>
                <a href="/mypage/resume_list" class="btn" style="position: absolute; top: 7.5rem; right: 1.5rem; border: 1px solid #57CCAF;">이력서 작성</a>
            </div>
            <div class="visual-img">
                <img src="<?=$banner["bg_img"]?>" alt="">
            </div>
        </div>
        <?php endif; ?>

        <!-- 상세 검색 옵션 선택시: has-option 클래스 추가 -->
				<?php function fShortAddrDong($longaddr){
						$parts = explode(' ', $longaddr);
    				return end($parts);//매지막 배열요소
				} ?>
        <div class="search-option has-option">
						<style>
						.current-area { padding: 0.5rem 0 0.50rem; }
						.search-option.has-option .btn-option { top: 0.975rem; }
						</style>
            <!-- 현재 위치 -->
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
						.section .container { padding-top: 1.0625rem; }
						.item-info .thumb { width: 5rem; }
						.item-info { padding: 0.4rem 0; }
						.item-info .comp_name { margin-top: 2px; }
						.item-info .title { margin: 2px 0 2px; }
						.item-info .meta { margin: 2px -2px 0 0; }
						.recomm-badge { background: #1B9D7D; }
					.item-info .period { /* font-size: 0.7rem; */ }
						.item-info .working-time { /* font-size: 0.7rem; */ line-height: 1rem; }
						.item-info .area { max-height: 1.3rem; margin-top: 4px; }
						.item-info { margin: 5px 0; }
						.item-info .amount { letter-spacing: -2px; }
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
            <a href="/recruit/index_map" class="btn-map"><span class="sr-text">지도보기</span></a>
            <div class="send-request" style="display:none;">
                <!-- 선택된 목록개수 -->
                <p class="counter">
                    <em class="selected">0</em>/
                    <span class="total">5</span>
                </p>
                <button type="button" id="btnRequest" class="btn-primary btn-request">지원하기</button>
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
<?php include_once (get_base_url() . '/application/shop/views/mobile/recruit/index_search.php'); ?>
<!-- //상세검색 패널 -->


<!-- 리스트 템플릿 -->
<!--template id="tpl_list">
	<li class="list-item">
        <div class="item-info recruit click-item"  data-link="/recruit/recruit_detail/{{rc_idx}}">
            <div class="thumb-wrap">
                <p class="recomm-badge"><span>추천</span></p>
                <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" data-user_id="{{mm_id}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button>
                <div class="thumb">
                    <a href="/recruit/recruit_detail/{{rc_idx}}"><img src="{{rt_recruit_img}}" alt=""></a>
                </div>
            </div>

            <div class="content-wrap">
                <div class="meta top">
                    <p class="category">{{rt_work_img}}</p>
                    <p class="area">{{ad_addr}}</p>
                    <p class="working-time">{{rt_work_type}}</p>
                    <label class="form-checkbox">
                        <input type="checkbox" name="chk[]" value="{{rc_idx}}" onchange="selectRecruitList(5,this)" class="sr-text">
                        <span class=label-red></span>
                        <span class="sr-text">지원하기 선택</span>
                    </label>
                </div>
                <p class="title"><strong><a href="/recruit/recruit_detail/{{rc_idx}}">{{rc_name}}</a></strong></p>
                <div class="meta">
                    <p>{{rc_nation_nm}}</p>
                    <span>/</span>
                    <p>{{rc_age_nm}}</p>
                </div>
                <div class="pay">
                    <p class="period">{{rt_price_type}}</p>
                    <p class="km">{{distance}}</p>
                    <p class="amount">{{rt_price}}</p>
                </div>
            </div>
        </div>
    </li>
</template!-->

<template id="tpl_list">
	<li class="list-item">
        <div class="item-info recruit click-item"  data-link="/recruit/recruit_detail/{{rc_idx}}">
            <div class="thumb-wrap">
                <!-- 추천라벨 -->
                <p class="recomm-badge"><span>추천</span></p>
                <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" data-user_id="{{mm_id}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button>
                <!-- 썸네일 -->
                <div class="thumb">
                    <a href="/recruit/recruit_detail/{{rc_idx}}"><img src="{{rt_recruit_img}}" alt=""></a>
                </div>
            </div>

            <div class="content-wrap">
                <div class="meta top">
                    <!-- 직종라벨 -->
                    <p class="category" style="max-width: 130px; overflow: hidden; height: 18px;">{{rt_work_img}}</p>

                    <!-- 근무시간 -->
                    <p class="working-time" style="color:#777;font-weight: 600;">{{rt_work_type}}</p>
                     <!-- 목록선택 (제한20개) -->
                    <label class="form-checkbox" style="display:none;">
                        <input type="checkbox" name="chk[]" value="{{rc_idx}}" onchange="selectRecruitList(5,this)" class="sr-text">
                        <span class=label-red></span>
                        <span class="sr-text">지원하기 선택</span>
                    </label>
                </div>
								<!-- 제목 -->
                <!-- <p class="comp_name">{{mm_comp_name}}</p> -->
								<!-- 지역 -->
								<p class="area" style="color: #777;font-weight: 600;font-size: 0.7rem;">{{ad_addr}} <span class="km">&nbsp;{{distance}}</span></p>
                <!-- 제목 -->
                <p class="title" style="max-width: 70%;"><strong><a href="/recruit/recruit_detail/{{rc_idx}}">{{rc_name}}</a></strong></p>
                <!-- 추가조건 -->
                <div class="meta" style="font-weight: 600;font-size: 0.7rem;">
                    <p>{{rc_nation_nm}}</p>
                    <span>/</span>
                    <p>{{rc_age_nm}}</p>
                </div>
                <!-- 지불방식 -->
                <div class="pay" style="margin-top: -2.1rem;">
                    <p class="period" style="font-weight: 600;">{{rt_price_type}}</p>
                    <p class="amount">{{rt_price}}</p>
                </div>
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


<input type="hidden" name="resume_yn" value="<?=$resume ==null ? "N":"Y"?>" />
<input type="hidden" name="mm_type" value="<?=$this->user->getUserType()?>" />

<style>
    /* 팝업 스타일 */
    #applypop {
        position: fixed;
        top: 200px; /* 적절한 위치로 조정 */
        right: -5%;
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
	한 번에 보낼 수 있는 <br>입사지원은 <font style="color: yellow;">5회</font> 입니다.
</div>

<script>
	$("#btnRequest").click(function(){
        //로그인 여부 확인
        if($("#is_login").val()!="Y"){
            commonLogin();
            return;
        }

				if($("[name='mm_type']").val()!="MEM"){
        	commonAlert("기업회원은 지원 할 수 없습니다.",function(){});
            return;
        }

        //이력서 여부 확인
        if($("[name=resume_yn]").val()=="N"){
            commonAlert("이력서 작성및 공개이력서 설정후 지원해주세요.",function(){});
            return;
        }

		var mq_idxs = new Array();

		for(var icnt =0;icnt < $("[name='chk[]']:checked").length;icnt++){
			mq_idxs.push($("[name='chk[]']:checked").eq(icnt).val());
		}

		if(mq_idxs.length==0){
			commonAlert("지원하실 공고를 선택해주세요.",function(){});
			return;
		}

		openPopup('confirm', {
            content: '<em>선택한 </em>공고에<br> 지원하시겠습니까?',
            secondCallback: function () {
              	var params = {};
			    params["mq_idxs"] = mq_idxs;
			    params["mq_type"] = "RECRUIT";
			    comAjax("/common_proc/member_request",params,function(ret){
			        if(ret.code=="00"){
			        	commonAlert("지원 신청 되었습니다.",function(){
			        		$("[name='chk[]']").prop("checked",false);
			        	});
			        }else{
			            commonAlert(ret.msg,function(){});
			            return;
			        }
			    });
            }
        });
	});
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
			cashing_id: "recruit_cashing",
			list_id: list_id,
			category_list_id : "#tab_list",
			scroll_obj : $(".page-recruit"),
			fn_cashing: initCashingData,
			fn_load: initLoadData,
		};
		$.CashingAjax(params);

    //카테고리 설정.
    initCategory();

		$('body').css('overflow','auto'); //24.11.25 임시처리

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

		comAjax("/recruit_proc/search_list",params,function(ret){
			//console.log(ret);
			//alert(ret);//test
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
			//console.log(row);
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

						if(row.pm_idx == null){
							//$content = $content.replaceAll("<span>추천</span>","");
							$content = $content.replaceAll("<p class=\"recomm-badge\"><span>추천</span></p>","");
						}


    		$content = $content.replaceAll("{{rc_idx}}",row.rc_idx);
    		$content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");
			$content = $content.replaceAll("{{ad_addr}}",getCity(row.ad_addr));
			$content = $content.replaceAll("{{rc_name}}",row.rc_name);
			$content = $content.replaceAll("{{distance}}",row.distance + "km")
			$content = $content.replaceAll("{{rc_nation_nm}}",getNation(row.rc_nation_nm));
			$content = $content.replaceAll("{{rc_age_nm}}",getAge(row.rc_age_nm));

			$content = $content.replaceAll("{{rt_recruit_img}}",row.rt_recruit_img);
			$content = $content.replaceAll("{{rt_work_img}}",row.rt_work_img);
			$content = $content.replaceAll("{{rt_work_type}}",fWorkColor(row.rt_work_type));
			if(row.rt_price_type=='<em>급여협의</em>'){
				$content = $content.replaceAll("{{rt_price_type}}","&nbsp;");
			}else{
				$content = $content.replaceAll("{{rt_price_type}}",row.rt_price_type);
			}
			// $work_type = row.rt_work_type;
			// $work_type = $work_type.replaceAll("주간","<font style='color:#777;'>주간</font>");
			// $work_type = $work_type.replaceAll("야간", "<font style='color:#777;'>야간</font>"); //빨강
			// $work_type = $work_type.replaceAll("2교대", "<font style='color:#777;'>2교대</font>"); //민트
			// $work_type = $work_type.replaceAll("근무협의", "<font style='color:#777;'>근무협의</font>"); //검정
			// $work_type = $work_type.replaceAll("시간협의", "<font style='color:#777;'>근무협의</font>"); //검정
			// $content = $content.replaceAll("{{rt_price_type}}",$work_type);

			$content = $content.replaceAll("{{rt_price}}",row.rt_price.replaceAll(" 원","원"));
			$content = $content.replaceAll("{{mm_id}}",row.mm_id);
			$content = $content.replaceAll("{{mm_comp_name}}",row.mm_comp_name);

    		//복사한 내용 다시 넣기
    		$clone.html($content);

    		$(list_id).append($clone.html());
		});

       	showEmpty();
	}

	function getNation(arg){
		arg = arg.replaceAll("내국인,외국인", "국적무관");
		return arg;
	}

	function getAge(arg){
		arg = arg.replaceAll("세 이상 ~", "~");
		arg = arg.replaceAll("세 이하", "대");
		arg = arg.replaceAll("무관", "나이무관");
		return arg;
	}

	function fWorkColor(arg){
  	arg = arg.replaceAll("주간", "<font style='color:#3a70db;'>주간</font>"); //파랑
		arg = arg.replaceAll("야간", "<font style='color:#F86048;'>야간</font>"); //빨강
		arg = arg.replaceAll("2교대", "<font style='color:#1B9D7D;'>2교대</font>"); //민트
		arg = arg.replaceAll("근무협의", "<font style='color:#333;'>근무협의</font>"); //검정
		//arg = arg.replaceAll("협의", "<font style='color:#333;'>협의</font>"); //검정

		return arg;
	}

	function getCity(addr) {
		addr = addr.replaceAll("경기도","");
		addr = addr.replaceAll("경기","");
		addr = addr.replaceAll("서울특별시","");
		addr = addr.replaceAll("서울시","");
		addr = addr.replaceAll("인천광역시","인천시");
		addr = addr.replaceAll("인천","인천시");
		addr = addr.replaceAll("인천시시","인천시");
		addr = addr.replaceAll("충청남도","");
		addr = addr.replaceAll("충남","");
		addr = addr.replaceAll("충청북도","");
		addr = addr.replaceAll("충북","");
		addr = addr.replaceAll("경상북도","");
		addr = addr.replaceAll("경북","");
		addr = addr.replaceAll("경상남도","");
		addr = addr.replaceAll("경남","");
		addr = addr.replaceAll("전라북도","");
		addr = addr.replaceAll("전남","");
		addr = addr.replaceAll("전라남도","");
		addr = addr.replaceAll("전남","");
		addr = addr.replaceAll("강원도","");
		addr = addr.replaceAll("강원","");
		addr = addr.replaceAll("전북특별자치도","");
		addr = addr.replaceAll("세종특별자치시","세종시");

    // 문자열 앞뒤 공백 제거 후 공백(" ") 기준으로 나누기
    let arr_addr = addr.trim().split(" ");

    // 첫 번째 요소(도시명) 반환
    return arr_addr[0] || "";
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

	// 페이지로딩
	$(window).scroll(function(){
		var $window = $(this);
		var scrollTop = $window.scrollTop() + $window.height();
		var windowHeight = $window.height();
		var documentHeight = $(list_id).height();

		if(list_loading==false){
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
				//$('.counter').show();
				//$(".counter").fadeOut(2000);


				let rect = elm.getBoundingClientRect();
        //let scrollY = window.scrollY || document.documentElement.scrollTop; // 스크롤 고려

        // 팝업 위치 조정 (버튼의 top과 맞추기)
				let popup = document.getElementById("applypop");
        //popup.style.top = (rect.top + scrollY - 22) + "px"; // 버튼과 높이 정렬
				popup.style.top = (rect.top - 21) + "px"; // 버튼과 높이 정렬
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

		//console.log(params);

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
