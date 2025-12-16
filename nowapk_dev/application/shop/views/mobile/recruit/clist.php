<?php
	$banner_list = getBannerList("BN00010001");
	$banner = null;
	if(count($banner_list) > 0){
		$banner = $banner_list[0];
	}

	function fRemoveBigAdr($arg){
		//경기, 충남 등 도 이름 제거
		$arg = str_replace("경기 ", "", $arg);
		$arg = str_replace("강원 ", "", $arg);
		$arg = str_replace("충남 ", "", $arg);
		$arg = str_replace("충북 ", "", $arg);
		$arg = str_replace("경남 ", "", $arg);
		$arg = str_replace("경북 ", "", $arg);
		$arg = str_replace("전남 ", "", $arg);
		$arg = str_replace("전북 ", "", $arg);
		$arg = str_replace("제주 ", "", $arg);

		$parts = explode(" ", $arg);
		if(count($parts) === 3) {
    	return $parts[0] . " " . $parts[2];
		} else {
    	return $arg;
		}

		//return $arg;
	}

	function fWorkColor($arg){
  	$arg = str_replace("주간", "<font style='color:#3a70db;'>주간</font>", $arg); //파랑
		$arg = str_replace("야간", "<font style='color:#F86048;'>야간</font>", $arg); //빨강
		$arg = str_replace("2교대", "<font style='color:#1B9D7D;'>2교대</font>", $arg); //민트
		$arg = str_replace("근무협의", "<font style='color:#333;'>근무협의</font>", $arg); //검정
		$arg = str_replace("협의", "<font style='color:#333;'>협의</font>", $arg); //검정
		return $arg;
	}

	function fHashSpace($rc_work,$arg){
		$arg = str_replace("<p class='tag'>#</p>", "", $arg);
	  $arg = str_replace("<p class='tag'># </p>", "", $arg);

		$arg = str_replace("#", "&nbsp;&nbsp;#", $arg);

		$arg = str_replace("<p ", "<p style=\"color:#3a70db; background-color:transparent; padding-left:0;\" ", $arg);

		//if($arg==""){
			//$arg = str_replace("","",$rc_work);  #신규라인  #조립  #자재  #대환영  #주급도가능해요  #언제든연랃주세요
			//echo "[[[trim:".trim($arg)."]]]";
			$work_array = explode(",", $rc_work);
    	$formatted_work_array = array_map(function($work) use ($arg) {
				//echo "[[[trim:".trim($arg)."]]]";
				//echo "[[find:".'#'.trim($work).'<'."]]";
				if(strpos(trim($arg), "#".trim($work)."<") === false){
					//echo "[false]";
					//return "&nbsp;&nbsp;#".trim($work);
					return "<p style=\"color:#3a70db; background-color:transparent; padding-left:0;\" class='tag'>#".trim($work)."</p>";
				}else {
					//echo "[true]";
				}
    	}, $work_array);

    	//return '<p style="color:#3a70db; background-color:transparent; padding-left:0;" class="tag">'.implode(' ', $formatted_work_array).'</p>';
			//$arg .= "<p style=\"color:#3a70db; background-color:transparent; padding-left:0;\" class='tag'>".implode(" ", $formatted_work_array)."</p>";
			$arg .= implode("",$formatted_work_array);
		//}
		return $arg;
	}

	function fWorkListDistImg($str){
				// 입력된 문자열을 쉼표로 분할하고 중복 제거
		    $workList = array_unique(explode(',', $str));

				// 최대 2개의 요소만 선택
				$workList = array_slice($workList, 0, 2);

		    // 각 단어를 이미지 태그로 변환
		    $result = array_map(function($work) {
		        switch ($work) {
		            case '물류': return '<img src="/resource/uploads/admin/96a9e5ff8f7b6e0db82dc62743893bbd.png" class="category-label">';
		            case '생산':  return '<img src="/resource/uploads/admin/0d548978150efc63e46df52aa80385f4.png" class="category-label">';
								case '사무직':  return '<img src="/resource/uploads/admin/0a74dd279aa909668a2ec4720b2fd3ec.png" class="category-label">';
								case '운전배달':  return '<img src="/resource/uploads/admin/e74fa3c1fe93835d3c6f7ad4ecaacd77.png" class="category-label">';
								case '식당':  return '<img src="/resource/uploads/admin/2d83db66386a8e1ad404f2fd48e3f79c.png" class="category-label">';
								case '서비스':  return '<img src="/resource/uploads/admin/7a710e0f902f2c03e5b358fa9c52ce42.png" class="category-label">';
								case '매장관리':  return '<img src="/resource/uploads/admin/5bbf09fb49560ef98d69320b5427bbc4.png" class="category-label">';
								case '건설':  return '<img src="/resource/uploads/admin/68cbb430bc2e25903d6b14c03c73de31.png" class="category-label">';
								case '상담':  return '<img src="/resource/uploads/admin/f275ec8d6882e94b9549fd1e868f3745.png" class="category-label">';
								case '디자인':  return '<img src="/resource/uploads/admin/ba1480a5bdcb90a13e5fa456ead881b3.png" class="category-label">';
								case '고객상담':  return '<img src="/resource/uploads/admin/864e7c47fa39284c66de727a3e6e7b93.png" class="category-label">';
								case '기타':  return '<img src="/resource/uploads/admin/533534388eb9372c4e61021a648d7abf.png" class="category-label">';
		            // 필요한 경우, 다른 작업 카테고리에 대한 추가 이미지 태그를 추가합니다.
		            default:
		                return $work; // 기본적으로 원래 텍스트를 반환합니다.
		        }
		    }, $workList);

		    // 결과 배열을 문자열로 결합하여 반환
		    return implode(' ', $result);
	}
?>
<div id="content" class="page page-recruit">
	<div class="header is-transparency">
			<h1 class="top-logo"><span class="sr-text">지금 뜨는, 좋은 일자리 NOW JOB</span></h1>
			<a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
					<span class="sr-text">미확인 알림메세지</span>
					<!-- 미확인 알림개수 -->
					<span class="cnt"><?=$g_alarm_cnt?></span>
			</a>
	</div><!-- //header -->

	<?php if($banner !=null) : ?>
			<div class="top-visual">
					<div class="visual-content">
							<p><?=$banner["bn_text1"]?></p>
							<p class="headline"><?=$banner["bn_text2"]?></p>
							<a href="/mypage/resume_list" class="btn">이력서 작성</a>
					</div>
					<div class="visual-img">
							<img src="<?=$banner["bg_img"]?>" alt="">
					</div>
			</div>
			<?php endif; ?>

    <main class="page-content">

        <div class="recomm-slider recruit">
					<style>
						.arrow--left { display: none; }
						.arrow--right { display: none; }
					</style>
            <div class="container" style="padding-top: 1rem;">
                <h2 class="primary-title" style="margin-bottom: 12px;">지금 뜨는 업체</h2>

                <div class="slider-wrap keen-slider" id="my-keen-slider" style="display:none; width: calc(490% - 7px); height: 190px;">
                    <?php foreach($up_list as $row) : ?>
					    <!-- 목록 -->
	                    <div class="keen-slider__slide" style="float:left; width: 30%; height: 50px; margin-right: 20px;">
	                        <div class="recruit-card">
	                            <div class="thumb-wrap">
	                                <!-- 추천라벨 -->
	                                <!-- <p class="recomm-badge">추천</p> -->
	                                <!-- 썸네일 -->
																	<!--
	                                <div class="thumb click-item-new" data-link="/recruit/wlistcomp/<?=$row["mm_idx"]?>">
	                                    <a href="/recruit/wlistcomp/<?=$row["mm_idx"]?>" target="_blank"><img data-src="<?=getCompImg($row)?>" alt="" class="lozad"></a>
	                                </div>
																	-->
																	<div class="thumb click-item-new" data-link="/recruit/wlistcomp/<?=$row["mm_idx"]?>" style="border: 1px solid #E9E9E9; height: 130px; overflow: hidden; width: 100%; padding: 5px; vertical-align: middle; position: relative; background-color: #fff;">
	                                    <a href="/recruit/wlistcomp/<?=$row["mm_idx"]?>" target="_blank" style="position: relative; display: block; width: 100%; height: 100%; overflow:hidden; background:url('<?=getCompImg($row)?>'); background-size: contain; background-position: center; background-repeat: no-repeat;"><img data-src="<?=getCompImg($row)?>" alt="" class="lozad" style="transform: none; -webkit-transform:none; top:0; left:0; display:none;"></a>
	                                </div>
	                                <!-- 즐겨찾기 추가 -->
	                                <!-- <button type="button" data-bk_type="RECRUIT" data-bk_key="<?=$row["rc_idx"]?>" data-user_id="<?=$row["mm_id"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 추가</span></button> -->
	                            </div>
	                            <div class="content-wrap click-item-new" data-link="/recruit/wlistcomp/<?=$row["mm_idx"]?>" style="background-color:#fff; border: 1px solid #E9E9E9; border-top: 0;">
	                                <div class="inline-group">
	                                    <!-- 카테고리 라벨 -->
	                                    <div style="max-width: 154px; overflow: hidden; height: 26px;"><?=getWorkingImg($row["ma_work"],1)?></div>
	                                    <!-- 지역 -->
	                                    <p class="area"><?=fRemoveBigAdr($row["ad_addr"])?></p>
	                                </div>
	                                <!-- 제목 -->
	                                <p class="title"><strong><a href="/recruit/wlistcomp/<?=$row["mm_idx"]?>" target="_blank"><?=$row["mm_comp_name"]?></a></strong></p>
																	<!--
																	<div class="meta">
	                                    <p><?=$row["rc_nation_nm"]?></p>
	                                    <p><?=str_replace(" 이상","",str_replace(" 이하","",$row["rc_age_nm"]))?></p>
	                                </div>
																	-->
																	<!--
	                                <div class="price-wrap" style="position:relative;">
																			<p class="type" style="position:absolute; left:0px; top: 27px;"><?=fWorkColor(getWorkTypePoint($row["rc_work_type"],$row))?></p>
	                                    <p class="type"><?=getPriceTypePoint($row["rc_price_type"],$row)?></p>
																			<?php if(getRecuitPrice($row)=="<em class='num'>0</em> 원"){ ?>
																				<p class="amount" style="height:24px;"></p>
																			<?php }else{ ?>
																				<p class="amount"><?=getRecuitPrice($row)?></p>
																			<?php } ?>
	                                </div>
																	-->
	                                <!-- 해시태그 -->
																	<!--
	                                <div class="hashtag">
	                                    <?=fHashSpace($row["rc_work"],getHashTag($row["rc_hash"],3))?>
	                                </div>
																	-->
	                            </div>
	                        </div>
	                    </div>
					 <?php endforeach;?>
                </div>
            </div>
            <script>
                window.addEventListener('DOMContentLoaded', function () {
                    if (typeof KeenSlider === undefined) return;

                    var slider = new KeenSlider(".recomm-slider .slider-wrap", {
                        loop: true,
                        slides: { perView: 7 },
                    }, [
                        addActiveClass,
                        sliderArrows
                    ]);

					var interval = 0;
					var sliderElement = document.getElementById("my-keen-slider");
					function autoplay(run) {
					    clearInterval(interval);
					    interval = setInterval(() => {
					      if (run && slider) {
					        slider.next();
					      }
					    }, 3400);
					}

					sliderElement.addEventListener("mouseover", () => {
						autoplay(false);
					});
					sliderElement.addEventListener("mouseout", () => {
						autoplay(true);
					});
					autoplay(true);
                });
            </script>
        </div>

				<style>
				.section .container .search-form label .searchMiddlebox {
  				border: 1px solid #57CCAF;
					border-radius: 30px;
				}
				.section .container .search-form label .searchMiddlebox:focus {
  				border: 1px solid #57CCAF;
					outline: none;
				}
				.section .container .search-form .suggestBox { max-width: 500px; margin: 0 auto; text-align: left; height: 30px; line-height: 30px; margin-top: 5px; height: 30px; overflow: hidden; }
				.section .container .search-form .suggestBox span { background-color: #57ccaf; color: #fff; font-size: 14px; padding: 0px 10px; border-radius: 7px; float: left; }
				.section .container .search-form .suggestBox ul { float: left; font-size: 14px; color: #777; margin-left: 10px; width: calc(100% - 100px); }
				.section .container .search-form .suggestBox ul li { position: relative; list-style: none; float:left; cursor: pointer; margin-right: 10px; padding-right: 10px; }
				.section .container .search-form .suggestBox ul li:after { content: ""; display: block; position: absolute; top: 0; right: 0; bottom: 0; width: 1px; height: 0.875em; margin: auto; border-right: 1px solid #ACACAC; }
				.section .container .search-form .suggestBox ul li:last-child:after { content: none; }
				</style>
        <div class="section">
            <div class="container" style="padding-top: 0; margin-top: -20px;">

                <div class="search-result">

                    <!-- 검색내용 -->
                    <div class="content-area">

                        <!-- 검색결과 -->
                        <div class="result-wrap">

                            <div class="result-header" style="margin-bottom: 10px;">
                                <!-- 총개수 -->
                                <p class="total">총 <em id="total_count"><?=count($all_list)?></em>건</p>
                                <!-- 정렬 -->
                                <div class="orderby">
                                </div>
                            </div>

														<style>
														.item-info .btn-bookmark:before {	display: none; }
														</style>
                            <!-- 목록 -->
                            <ol class="result-list" id="ajaxList" data-page="1" style="min-height:400px;">
															<?php foreach($all_list as $row) : ?>
																<li class="list-item item-hover click-item" data-link="/recruit/wlistcomp/<?=$row["mm_idx"]?>">
															        <div class="item-info colgroup">
															            <div class="thumb-wrap">
															                <div class="thumb big click-item-new" data-link="/recruit/wlistcomp/<?=$row["mm_idx"]?>">
															                    <a data-href="/recruit/wlistcomp/<?=$row["mm_idx"]?>" target="_blank"><img src="<?=$row["mm_img1"]?>" alt=""></a>
															                </div>
															            </div>
															            <div class="content-wrap click-item-new" data-link="/recruit/wlistcomp/<?=$row["mm_idx"]?>">
																							<?php if($row["mm_comp_num"] == ''){ ?>
																								<p class="title"></p>
																							<?php }else{ ?>
																								<p class="title"><img src='/resource/shop/web/img/certimg2.png' style='height:24px;'><a data-href=""><?=$row["mm_comp_name"]?></a></p>
																							<?php } ?>

																							<p class="title" style="font-size: 0.9rem;">현재 채용중 <?=$row["rt_ing_cnt"]?>건</p>
																							<div class="inline-group">
															                    <p class="category"><?=fWorkListDistImg($row["rc_work_list"])?></p>
															                </div>

															                <div class="meta">
															                </div>
															            </div>
																					<div style="display:none;width:130px; text-align: center;">
																						<p class="type"><?=$row["rt_work_type"]?></p>
																            <p class="area"><?=$row["ad_addr"]?></p>
																					</div>
															            <div class="selection" style="display:none;">
																							<div style="border: 1px solid #ccc; border-radius: 5px; padding: 3px 43px 3px 14px ; line-height: 34px; overflow:hidden; width: 110px; height: 34px" onclick="triggerButtonClick(this);">
																								<button type="button" data-bk_type="COMPANY" data-bk_key="<?=$row["mm_idx"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>" style="top: 88px; right: 11px; width: 118px;">관심기업<span class="sr-text">관심기업 추가</span></button>
																							</div>
															            </div>
															        </div>
															    </li>
																<?php endforeach;?>

                            </ol>
                            <!-- //목록 -->

                            <script>
                                /* 체크박스 선택시 지원하기 카운터 개수 변경 */
                                function updateCounter (num) {
                                    if(!document.querySelector('.send-request')) return;
                                    document.querySelector('.send-request .selected').innerHTML = num;
                                }

                                /* 체크박스 선택 제한 툴팁 표시 */
                                function selectRecruitList (limitNum, elm) {
                                    var checkboxList = document.querySelectorAll('.recruit-checkbox input');
                                    var selectedCheckbox = document.querySelectorAll('.recruit-checkbox input:checked');
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

                            <div id="page_cont">

                            </div>

                            <script>
                                /* 목록이 3개이하인경우 리스트액션 고정 */
                                window.addEventListener('scroll', function () {
                                    var minY = document.querySelector('.result-wrap').getBoundingClientRect().top - (window.innerHeight / 2);
                                    var stickyElm = document.querySelector('.result-wrap .list-action');

                                    if (document.querySelector('.result-list').children.length < 3) return;

                                    if (minY <= document.body.scrollTop) {
                                        stickyElm.style.position = 'sticky';
                                    } else {
                                        stickyElm.style.position = '';
                                    }
                                });
                            </script>
                        </div><!-- //result-wrap -->
                    </div><!-- //content-area -->
                </div><!-- //container -->
            </div>
        </div>
    </main><!-- //page-content -->

		<?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>

</div><!-- //page -->

<!-- 리스트 템플릿 -->
<template id="tpl_list">
	<li class="list-item item-hover">
        <div class="item-info colgroup">
            <div class="thumb-wrap">
                <!-- 추천라벨 -->
                <!-- <p class="recomm-badge"><span>추천</span></p> -->
                <!-- <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" data-user_id="{{mm_id}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button> -->
                <!-- 썸네일 -->
                <div class="thumb big click-item-new" data-link="/recruit/wlistcomp/{{comp_idx}}">
                    <a href="/recruit/wlistcomp/{{comp_idx}}" target="_blank"><img src="{{rt_recruit_img}}" alt=""></a>
                </div>
            </div>
            <div class="content-wrap click-item-new" data-link="/recruit/wlistcomp/{{comp_idx}}">
            	<!-- <div class="timestamp">등록일 : {{timestamp}}</div> -->
                <!-- 제목 -->
                <p class="title">{{compAuth}}</p>
								<p class="title">{{rt_ing_cnt}}</p>
								<div class="inline-group">
                    <!-- 직종라벨 -->
                    <p class="category">{{rt_work_img}}</p>
                </div>

                <!-- 추가조건 -->
                <div class="meta">
                    <!-- 근무시간 -->
                    <!-- <p class="type">{{rt_work_type}}</p> -->
                    <!-- <span> | </span> -->
										<!--
                    <p>{{rc_nation_nm}}</p>
                    <span> | </span>
                    <p>{{rc_age_nm}}</p>
										-->
                </div>
            </div>
            <!-- 지역 -->
						<div style="display:none;width:130px; text-align: center;">
							<p class="type">{{rt_work_type}}</p>
	            <p class="area">{{ad_addr}}</p>
						</div>
            <!-- 지불방식 -->
            <div class="pay" style="display:none;">
                <p class="period">{{rt_price_type}}</p>
                <p class="amount"><em class="num">{{rt_price}}</em></p>
            </div>
            <!-- 목록선택 (제한20개) -->
            <div class="selection">
								<div style="border: 1px solid #ccc; border-radius: 5px; padding: 3px 43px 3px 14px ; line-height: 34px; overflow:hidden; width: 110px; height: 34px" onclick="triggerButtonClick(this);">
									<button type="button" data-bk_type="COMPANY" data-bk_key="{{comp_idx}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}" style="top: 88px; right: 11px; width: 118px;">관심기업<span class="sr-text">관심기업 추가</span></button>
								</div>
            </div>
        </div>
    </li>
</template>

<!-- 리스트 템플릿 -->
<template id="tpl_reco_list">
    <div class="keen-slider__slide">
        <div class="recruit-card click-item" data-link="/recruit/recruit_detail/{{rc_idx}}">
            <div class="thumb-wrap">
                <!-- 추천라벨 -->
                <p class="recomm-badge">추천</p>
                <!-- 썸네일 -->
                <div class="thumb">
                    <a href="/recruit/recruit_detail/{{rc_idx}}" target="_blank"><img src="{{rt_recruit_img}}" alt="" class=""></a>
                </div>
                <!-- 즐겨찾기 추가 -->
                <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" data-user_id="{{mm_id}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button>
            </div>
            <div class="content-wrap">
                <div class="inline-group">
                    <!-- 카테고리 라벨 -->
                    {{rt_work_img}}
                    <!-- 지역 -->
                    <p class="area">{{ad_addr}}</p>
                </div>
                <!-- 제목 -->
                <p class="title"><strong><a href="/recruit/recruit_detail/{{rc_idx}}" target="_blank">{{rc_name}}</a></strong></p>
                <div class="meta">
                    <!-- 선택된 항목에 em태그로 처리 -->
                    <p class="type">{{rt_work_type}}</p>
                    <p>{{rc_nation_nm}}</p>
                    <p>{{rc_age_nm}}</p>
                </div>
                <div class="price-wrap">
                    <!-- 선택된 항목에 em태그로 처리 -->
                    <p class="type">{{rt_price_type}}</p>
                    <p class="amount">{{rt_price}}</p>
                </div>
                <!-- 해시태그 -->
                <div class="hashtag">
                    {{rt_hash}}
                </div>
            </div>
        </div>
    </div>
</template>

<input type="hidden" name="resume_yn" value="<?=$resume ==null ? "N":"Y"?>" />
<input type="hidden" name="mm_type" value="<?=$this->user->getUserType()?>" />
<script>
	$(".btnTag").click(function(){
		var ar_cate = ["생산","물류","건설","매장관리","식당","서비스","운전배달","고객상담","사무직","디자인","기타","상담","제조","생산"];
		var ar_yoil = ["일","월","화","수","목","금","토","요일무관"];
		var ar_price = ["일급","주급","월급"];
		var ar_work = ["주간","야간","2교대"];
		var ar_sex = ["남자","여자"];
		var ar_year = ["20대","30대","40대","50대","60대","70대"];
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

	$(".btnCloseAdv").click(function(){
		$("#advancedSearchWidget").attr("hidden","");
	});

	$("#btnRegist").click(function(){
		//로그인 여부 확인
        if($("#is_login").val()!="Y"){
            //commonLogin();
						commonAlert("로그인후 이용해주세요!",function(){ location.href='/member/login'; });
            return;
        }

        if($("[name='mm_type']").val()!="MEM"){
        		commonAlert("일반회원만 이력서 작성이 가능합니다.",function(){});
            return;
        }

        location.href="/mypage/resume_list";
	});

	$(document).on("click",".form-checkbox",function(e){
		e.stopPropagation();
	});

	$("#btnRequest").click(function(){
        //로그인 여부 확인
        if($("#is_login").val()!="Y"){
            commonLogin();
            return;
        }

        //이력서 여부 확인
        if($("[name=resume_yn]").val()=="N"){
            commonAlert("이력서 작성후 제안해주세요.",function(){});
            return;
        }

		var mq_idxs = new Array();

		for(var icnt =0;icnt < $("[name='chk[]']:checked").length;icnt++){
			mq_idxs.push($("[name='chk[]']:checked").eq(icnt).val());
		}

		if(mq_idxs.length==0){
			commonAlert("제안하실 채용정보를 선택해주세요.",function(){});
			return;
		}

		openPopup('confirm', {
            content: '<em>선택한 </em>공고에 <br> 지원하시겠습니까?',
            secondCallback: function () {
              	var params = {};
			    params["mq_idxs"] = mq_idxs;
			    params["mq_type"] = "RECRUIT";
			    comAjax("/common_proc/member_request",params,function(ret){
			        if(ret.code=="00"){
			        	commonAlert("제안 신청 되었습니다.",function(){
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
	var list_id = "#ajaxList";
    var list_empty_id = "#ajaxEmpty";
	var list_page = 1;
	var list_loading = false;

	var search_array = {
		text : "",
		addr1 : "",
		addr2 : "",
		addr3 : "",
		skm : "",
		ekm : "",
		category : "",
		work_type : "",
		week : "",
		week_yn : "",
		price_type : "",
		sex : "",
		sage : "",
		eage : "",
		order_by : "",
	};

	$(document).ready(function(){
		//데이터 캐싱처리
		var params = {
			cashing_id: "recruit_cashing",
			list_id: list_id,
			category_list_id : "#page_cont",
			scroll_obj : $(".page-recruit"),
			fn_cashing: initCashingData,
			fn_load: initLoadData,
		};
		$.CashingAjax(params);
	});

	//캐싱처리 추가 작업
	function initCashingData() {
		//list_page = $(list_id).attr("data-page");
		//showEmpty();

		//상단 검색처리
		initTopSearch(true);
	}

	//데이터 호출 처리
	function initLoadData() {
		//상단 검색처리
		initTopSearch(false);

		//검색
		initPage(false);
	}

	//요일무관
	$("[name='week_yn']").change(function(){
		if($(this).is(":checked")){
			$("[name='week[]']").prop("checked",true);
		}else{
			$("[name='week[]']").prop("checked",false);
		}
	});

	//주말 전체 처리
	$("[name='week[]']").click(function(){
		if($("[name='week[]']").length==$("[name='week[]']:checked").length){
			$("[name='week_yn']").prop("checked",true);
		}else{
			$("[name='week_yn']").prop("checked",false);
		}
	});

	//카테고리 전체
	$("[name='category_all']").click(function(){
		if($(this).is(":checked")){
			$("[name='category[]']").prop("checked",true);
		}else{
			$("[name='category[]']").prop("checked",false);
		}

		for(var icnt=0;icnt < $("[name='category[]']").length;icnt++){
			var cate_nm = $("[name='category[]']").eq(icnt).val();

			for(var jcnt=0;jcnt < $(".widget-option").length;jcnt++){
				if(cate_nm==$(".widget-option").eq(jcnt).text()){
					if($(this).is(":checked")){
						$(".widget-option").eq(jcnt).addClass("on");
					}else{
						$(".widget-option").eq(jcnt).removeClass("on");
					}
				}
			}
		}
	});

	//카테고리 전체 처리
	$("[name='category[]']").click(function(){
		if($("[name='category[]']").length==$("[name='category[]']:checked").length){
			$("[name='category_all']").prop("checked",true);
		}else{
			$("[name='category_all']").prop("checked",false);
		}
	});

	//근무형태 전체
	$("[name='work_type_all']").click(function(){
		if($(this).is(":checked")){
			$("[name='work_type[]']").prop("checked",true);
			$("[name='price_type[]']").prop("checked",true);
		}else{
			$("[name='work_type[]']").prop("checked",false);
			$("[name='price_type[]']").prop("checked",false);
		}

		for(var icnt=0;icnt < $("[name='work_type[]']").length;icnt++){
			var cate_nm = $("[name='work_type[]']").eq(icnt).val();

			for(var jcnt=0;jcnt < $(".widget-option").length;jcnt++){
				if(cate_nm==$(".widget-option").eq(jcnt).text()){
					if($(this).is(":checked")){
						$(".widget-option").eq(jcnt).addClass("on");
					}else{
						$(".widget-option").eq(jcnt).removeClass("on");
					}
				}
			}
		}

		for(var icnt=0;icnt < $("[name='price_type[]']").length;icnt++){
			var cate_nm = $("[name='price_type[]']").eq(icnt).val();

			for(var jcnt=0;jcnt < $(".widget-option").length;jcnt++){
				if(cate_nm==$(".widget-option").eq(jcnt).text()){
					if($(this).is(":checked")){
						$(".widget-option").eq(jcnt).addClass("on");
					}else{
						$(".widget-option").eq(jcnt).removeClass("on");
					}
				}
			}
		}

	});

	//근무형태 전체 처리
	$("[name='work_type[]'], [name='price_type[]']").click(function(){
		if($("[name='work_type[]']").length==$("[name='work_type[]']:checked").length &&
	     $("[name='price_type[]']").length==$("[name='price_type[]']:checked").length){
			$("[name='work_type_all']").prop("checked",true);
		}else{
			$("[name='work_type_all']").prop("checked",false);
		}
	});

	//성별 전체
	$("[name='sex_all']").click(function(){
		if($(this).is(":checked")){
			$("[name='sex[]']").prop("checked",true);
		}else{
			$("[name='sex[]']").prop("checked",false);
		}

		for(var icnt=0;icnt < $("[name='sex[]']").length;icnt++){
			var cate_nm = $("[name='sex[]']").eq(icnt).val();

			for(var jcnt=0;jcnt < $(".widget-option").length;jcnt++){
				if(cate_nm==$(".widget-option").eq(jcnt).text()){
					if($(this).is(":checked")){
						$(".widget-option").eq(jcnt).addClass("on");
					}else{
						$(".widget-option").eq(jcnt).removeClass("on");
					}
				}
			}
		}
	});

	//성별 전체 처리
	$("[name='sex[]']").click(function(){
		if($("[name='sex[]']").length==$("[name='sex[]']:checked").length){
			$("[name='sex_all']").prop("checked",true);
		}else{
			$("[name='sex_all']").prop("checked",false);
		}
	});

	//요일 전체
	$("[name='week_all']").click(function(){
		if($(this).is(":checked")){
			$("[name='week[]']").prop("checked",true);
		}else{
			$("[name='week[]']").prop("checked",false);
		}

		for(var icnt=0;icnt < $("[name='week[]']").length;icnt++){
			var cate_nm = $("[name='week[]']").eq(icnt).val();

			for(var jcnt=0;jcnt < $(".widget-option").length;jcnt++){
				if(cate_nm==$(".widget-option").eq(jcnt).text()){
					if($(this).is(":checked")){
						$(".widget-option").eq(jcnt).addClass("on");
					}else{
						$(".widget-option").eq(jcnt).removeClass("on");
					}
				}
			}
		}
	});

	//요일 전체 처리
	$("[name='week[]']").click(function(){
		if($("[name='week[]']").length==$("[name='week[]']:checked").length){
			$("[name='week_all']").prop("checked",true);
		}else{
			$("[name='week_all']").prop("checked",false);
		}
	});

	//나이 전체
	$("[name='eage_all']").click(function(){
		if($(this).is(":checked")){
			$("[name='eage[]']").prop("checked",true);
		}else{
			$("[name='eage[]']").prop("checked",false);
		}

		for(var icnt=0;icnt < $("[name='eage[]']").length;icnt++){
			var cate_nm = $("[name='eage[]']").eq(icnt).val();

			for(var jcnt=0;jcnt < $(".widget-option").length;jcnt++){
				if(cate_nm==$(".widget-option").eq(jcnt).text()){
					if($(this).is(":checked")){
						$(".widget-option").eq(jcnt).addClass("on");
					}else{
						$(".widget-option").eq(jcnt).removeClass("on");
					}
				}
			}
		}
	});

	//나이 전체 처리
	$("[name='eage[]']").click(function(){
		if($("[name='eage[]']").length==$("[name='eage[]']:checked").length){
			$("[name='eage_all']").prop("checked",true);
		}else{
			$("[name='eage_all']").prop("checked",false);
		}
	});

	//정렬변경
	$("[name='order_by']").click(function(){
		//검색
		initPage(true);
	});

	$("[name='category_all']").click(function(){
		//검색
		initPage(true);
	});

	//나이 처리
	$("[name='eage']").click(function(){
		$("[name='eage']").not(this).prop("checked",false);
		changeTab(this,"eage",$(this).val()+"대");
	});

	$("[name='category[]']").click(function(){
		changeTab(this,"category",$(this).val());
	});

	$("[name='price_type[]']").click(function(){
		changeTab(this,"price_type",$(this).val());
	});

	$("[name='work_type[]']").click(function(){
		changeTab(this,"work_type",$(this).val());
	});

	$("[name='sex[]']").click(function(){
		changeTab(this,"sex",$(this).val());
	});

	$("[name='week[]']").click(function(){
		changeTab(this,"week",$(this).val());
	});

	$("[name='week_yn']").click(function(){
		changeTab(this,"week_yn",$(this).val());
	});

	//왼쪽 메뉴 상태 변경
	function changeTab($this,type,val){
		if(type=="addr" || type=="eage"){
			$(".widget-option").each(function(i){
				if(val==$(this).text()){
					$(this).closest(".form-group").find("a").removeClass("on");
					$(this).addClass("on");
				}
			});
		}else{
			if(type=="week_yn"){
				if($($this).is(":checked")){
					$(".left-week").find("a").addClass("on");
				}else{
					$(".left-week").find("a").removeClass("on");
				}
			}else{
				$(".widget-option").each(function(i){
					if(val==$(this).text()){
						if($(this).hasClass("on")){
							$(this).removeClass("on");
						}else{
							$(this).addClass("on");
						}
					}
				});
			}
		}

		//검색
		if(type!="addr"){
			initPage(true);
		}
	}

	//왼쪽 메뉴 클릭 처리
	function setTab($this,type,val){
		//console.log(type+','+val);
		$("#advancedSearchWidget").removeAttr("hidden");
		if(type=="addr"){
			$($this).closest(".form-group").find("a").removeClass("on");
			$($this).addClass("on");
			changeSearchAddr(1,val,"");
		}else if(type=="eage" && false){
			if($($this).closest(".form-group").find("a").hasClass("on")){
				$($this).closest(".form-group").find("a").removeClass("on");
				//$($this).addClass("on");
			}else{
				$($this).addClass("on");
			}
			//$($this).closest(".form-group").find("a").removeClass("on");
			//$($this).addClass("on");

			$('[name=eage]').each(function(idx) {
	            var data = $(this).val();
				if(val==data){
					$('[name=eage]').eq(idx).prop("checked",true);
				}
	        });

			if(val=="all"){
					//console.log('all');
					if($($this).hasClass("on")){
						//console.log('if');
						$("[name='eage_all']").prop("checked",true);
					}else{
						//console.log('else');
						$("[name='eage_all']").prop("checked",false);
					}

					if($("[name='eage_all']").is(":checked")){
						//console.log('if1');
						$("[name='eage[]']").prop("checked",true);
						//$(".left-week").find("a").addClass("on");
					}else{
						//console.log('if2');
						$("[name='eage[]']").prop("checked",false);
						//$(".left-week").find("a").removeClass("on");
					}
			}

		}else{
			if($($this).hasClass("on")){
				$($this).removeClass("on");
			}else{
				$($this).addClass("on");
			}

			if(type=="week" && val=="all"){
				if($($this).hasClass("on")){
					$("[name='week_yn']").prop("checked",true);
				}else{
					$("[name='week_yn']").prop("checked",false);
				}

				if($("[name='week_yn']").is(":checked")){
					$("[name='week[]']").prop("checked",true);
					$(".left-week").find("a").addClass("on");
				}else{
					$("[name='week[]']").prop("checked",false);
					$(".left-week").find("a").removeClass("on");
				}

			}else if(val=="all"){
				//console.log('11');
				if($($this).hasClass("on")){
					$("[name='"+type+"_all']").prop("checked",true);
				}else{
					$("[name='"+type+"_all']").prop("checked",false);
				}

				if($("[name='"+type+"_all']").is(":checked")){
					$("[name='"+type+"[]']").prop("checked",true);
					//$(".left-week").find("a").addClass("on");
				}else{
					$("[name='"+type+"[]']").prop("checked",false);
					//$(".left-week").find("a").removeClass("on");
				}

				if(type=="price_type" && val=="all"){
					if($($this).hasClass("on")){
						$("[name='"+type+"[]']").prop("checked",true);
					}else{
						$("[name='"+type+"[]']").prop("checked",false);
					}

				}

			}else{
				$("[name='" + type + "[]" + "']").each(function(idx) {
		            var data = $(this).val();
					if(val==data){
						if($("[name='" + type + "[]" + "']").eq(idx).is(":checked")){
							$("[name='" + type + "[]" + "']").eq(idx).prop("checked",false);
						}else{
							$("[name='" + type + "[]" + "']").eq(idx).prop("checked",true);
						}
					}
		        });
			}
		}

		//검색
		initPage(true);
	}

	function changeSearchAddr(depth,addr1,addr2){
		if(depth==1){
			$('[name=searchArea1]').each(function(idx) {
	            var val = $(this).val();
				if(val==addr1){
					$("[name=searchArea1]").eq(idx).prop("checked",true);
				}
	        });
		}

		get_addrlist(depth,addr1,addr2);
		getRecoList();
	}

	//지역 선택 처리
	$(document).on("click","[name=searchArea1]",function(){
		changeTab(this,"addr",$(this).val()=="전체" ? "전체":$(this).val());
		changeSearchAddr(1,$(this).val(),"");
		//$('#multiarea').append('<span style="margin-right:10px; border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;">'+$(this).val()+' <span style="margin-left:5px; cursor:pointer;" onclick="$(this).parent().hide();">×</span></span>');
		addMultiarea('A','1',$(this).val());
	});

	$(document).on("click","[name=searchArea2]",function(){
		changeSearchAddr(2,$(this).attr("data-addr1"),$(this).val());
		//$('#multiarea').append('<span style="margin-right:10px; border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;">'+$(this).val()+' <span style="margin-left:5px; cursor:pointer;" onclick="$(this).parent().hide();">×</span></span>');
		addMultiarea('A','2',$(this).val());
	});

	$(document).on("click","[name=searchArea3]",function(){
		if($(this).val()==""){
			$("[name=searchArea3]").prop("checked",false);
			$("[name=searchArea3]").eq(0).prop("checked",true);
		}else{
			$("[name=searchArea3]").eq(0).prop("checked",false);
			if($("[name=searchArea3]:checked").length==0){
				$("[name=searchArea3]").eq(0).prop("checked",true);
			}
		}
		changeSearchAddr(3,$(this).attr("data-addr3"),$(this).val());
		//$('#multiarea').append('<span style="margin-right:10px; border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;">'+$(this).val()+' <span style="margin-left:5px; cursor:pointer;" onclick="$(this).parent().hide();">×</span></span>');
		var clickTxt = $(this).val();
		if(this.checked){
			addMultiarea('R','3',$(this).val());
		}else{
			$('#multiarea span').filter(function() {
        return $(this).data('addr') === '3,'+clickTxt;
    	}).remove();
			getBoardList(true);
		}
	});

	function setBoardParams(){
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

		//console.log($("[name='searchArea3']:checked"));

		//검색어 설정
		search_array.text = $("[name='text']").val();
		search_array.addr1 = $("[name='searchArea1']:checked").val()=="전체" ? "":$("[name='searchArea1']:checked").val();
		search_array.addr2 = $("[name='searchArea2']:checked").val();
		search_array.addr3 = $("[name='searchArea3']:checked").val();
		search_array.skm = "";
		search_array.ekm = "";
		search_array.category = arr_category;
		search_array.work_type = arr_work_type;
		search_array.week = arr_week;
		search_array.price_type = arr_price_type;
		search_array.sex = arr_sex;
		search_array.sage = "";
		search_array.eage = $("[name='eage']:checked").val();
		search_array.order_by = $("[name='order_by']:checked").val();

		localStorage.setItem("recruit_array",JSON.stringify(search_array));

	}

	function getBoardList(is_top){
		setTimeout(function(){
    	getBoardListDelay(is_top);
		}, 500);
	}

	//검색
	function getBoardListDelay(is_top){
		//파라미터 설정
		setBoardParams();

		var params = {};
		params["page"] = list_page;
		params["text"] = search_array.text;
		params["addr1"] = search_array.addr1;
		params["addr2"] = search_array.addr2;
		params["addr3"] = search_array.addr3;
		params["skm"] = search_array.skm;
		params["ekm"] = search_array.ekm;
		params["category"] = search_array.category;
		params["work_type"] = search_array.work_type;
		params["week"] = search_array.week;
		params["price_type"] = search_array.price_type;
		params["sex"] = search_array.sex;
		params["sage"] = search_array.sage;
		params["eage"] = search_array.eage;
		params["order_by"] = search_array.order_by;

		var multiAddr1 = '', multiAddr2 = '', multiAddr3 = '';
		//console.log('each');
    $('#multiarea span').each(function() {
			//console.log($(this));
			if ($(this).data('addr') !== undefined) {
					//console.log($(this).data('addr'));
					var dataAddr = $(this).data('addr'); // data-addr 값 추출
					var parts = dataAddr.split(','); // 쉼표로 분리
					//console.log(parts);
					if (parts.length === 2) { // 올바르게 분리되었는지 확인
							var key = parts[0]; // 첫 번째 부분 (1, 2, 3 중 하나)
							var value = parts[1]; // 두 번째 부분 (지역 이름)
							//console.log(key);
							//console.log(value);

							if(key==1){
									multiAddr1 = multiAddr1!='' ? multiAddr1 + '^' + value : value;
							}
							if(key==2){
									multiAddr2 = multiAddr2!='' ? multiAddr2 + '^' + value : value;
							}
							if(key==3){
									//console.log(multiAddr3);
									multiAddr3 = multiAddr3!='' ? multiAddr3 + '^' + value : value;
									//console.log(multiAddr3);
							}
							//console.log(key);
					}
			}
			//console.log(multiAddr3);
		});
		params["multiAddr1"] = multiAddr1;
		params["multiAddr2"] = multiAddr2;
		params["multiAddr3"] = multiAddr3;

		//console.log("search_web_list call:"+multiAddr3);

		/*
		comAjax("/recruit/clistAll",params,function(ret){
			console.log(ret);
			if(ret.code=="00"){
				console.log(ret);
				$(list_id).attr("data-page",list_page);
				$("#total_count").html(ret.data.list.length);
				//console.log(ret.data.pagenation);
				if (ret.data.pagenation && ret.data.pagenation.trim() !== '') {
					$("#page_cont").html(ret.data.pagenation.replace(/<li>/g, '').replace(/page-next/g, 'page-next btn'));
				}else{
					$("#page_cont").html('');
				}
				showBoardList(ret.data.list,is_top);
			}else{

			}
		});
		*/
	}

	function initTopSearch(is_back){
		var top_serch_yn = localStorage.getItem("top_search_yn");

		if(top_serch_yn=="Y" || is_back==true){
			var top_search_array = "";
			if(is_back==true){
				var top_search_array = localStorage.getItem("recruit_array");
			}else{
				localStorage.setItem("top_search_yn","");
				var top_search_array = localStorage.getItem("top_search_array");
			}

			var info = JSON.parse(top_search_array);
			var arr_category = info.category.split("^");
	        var arr_work_type = info.work_type.split("^");
	        var arr_price_type = info.price_type.split("^");
	        var arr_week = info.week.split("^");
	        var arr_sex = info.sex.split("^");
	        var arr_text = new Array();

			//$("[name='g_keyword']").val(info.text);


			if(info.week_yn=="Y"){
				$("[name='week_yn']").prop("checked",true);
				arr_text.push({type:"radio",val:"요일무관"});
			}

			if(info.eage!=""){
				for(var jcnt=0;jcnt < $("[name='eage']").length;jcnt++){
					if($("[name='eage']").eq(jcnt).val()==info.eage){
						$("[name='eage']").eq(jcnt).prop("checked",true);
						arr_text.push({type:"radio",val:info.eage + "대"});
					}
				}
			}

			if(info.addr1!=""){
				for(var jcnt=0;jcnt < $("[name='searchArea1']").length;jcnt++){
					if($("[name='searchArea1']").eq(jcnt).val()==info.addr1){
						$("[name='searchArea1']").eq(jcnt).prop("checked",true);
						//$("[name='searchArea1']").eq(jcnt).click();
						arr_text.push({type:"radio",val:info.addr1});
					}
				}
				changeSearchAddr(1,info.addr1,"");
			}

			if(info.addr2!=""){
				for(var jcnt=0;jcnt < $("[name='searchArea2']").length;jcnt++){
					if($("[name='searchArea2']").eq(jcnt).val()==info.addr2){
						$("[name='searchArea2']").eq(jcnt).prop("checked",true);
						arr_text.push({type:"radio",val:info.addr2});
					}
				}
				changeSearchAddr(2,info.addr1,info.addr2);
			}


			for(var icnt=0;icnt < arr_category.length;icnt++){
				for(var jcnt=0;jcnt < $("[name='category[]']").length;jcnt++){
					if($("[name='category[]']").eq(jcnt).val()==arr_category[icnt]){
						$("[name='category[]']").eq(jcnt).prop("checked",true);
						arr_text.push({type:"check",val:$("[name='category[]']").eq(jcnt).val()});
					}
				}
			}

			for(var icnt=0;icnt < arr_work_type.length;icnt++){
				for(var jcnt=0;jcnt < $("[name='work_type[]']").length;jcnt++){
					if($("[name='work_type[]']").eq(jcnt).val()==arr_work_type[icnt]){
						$("[name='work_type[]']").eq(jcnt).prop("checked",true);
						arr_text.push({type:"check",val:$("[name='work_type[]']").eq(jcnt).val()});
					}
				}
			}

			for(var icnt=0;icnt < arr_price_type.length;icnt++){
				for(var jcnt=0;jcnt < $("[name='price_type[]']").length;jcnt++){
					if($("[name='price_type[]']").eq(jcnt).val()==arr_price_type[icnt]){
						$("[name='price_type[]']").eq(jcnt).prop("checked",true);
						arr_text.push({type:"check",val:$("[name='price_type[]']").eq(jcnt).val()});
					}
				}
			}

			for(var icnt=0;icnt < arr_week.length;icnt++){
				for(var jcnt=0;jcnt < $("[name='week[]']").length;jcnt++){
					if($("[name='week[]']").eq(jcnt).val()==arr_week[icnt]){
						$("[name='week[]']").eq(jcnt).prop("checked",true);
						arr_text.push({type:"check",val:$("[name='week[]']").eq(jcnt).val()});
					}
				}
			}

			for(var icnt=0;icnt < arr_sex.length;icnt++){
				for(var jcnt=0;jcnt < $("[name='sex[]']").length;jcnt++){
					if($("[name='sex[]']").eq(jcnt).val()==arr_sex[icnt]){
						$("[name='sex[]']").eq(jcnt).prop("checked",true);
						arr_text.push({type:"check",val:$("[name='sex[]']").eq(jcnt).val()});
					}
				}
			}

			if(arr_text.length  > 0){
				$("#advancedSearchWidget").removeAttr("hidden");
				for(icnt=0;icnt < arr_text.length;icnt++){
					$(".widget-option").each(function(i){
						if(arr_text[icnt].val==$(this).text()){
							if(arr_text[icnt].type=="radio"){
								$(this).closest(".form-group").find("a").removeClass("on");
							}
							$(this).addClass("on");
						}
					});
				}
			}

		}
	}

	function initPage(is_top){
		list_page = 1;
		getBoardList(is_top);
	}

	function goPage(page){
		list_page = page;
		getBoardList(true);
	}

	function triggerButtonClick(divElement) {
    // 클릭된 div 내의 첫 번째 button을 찾아 click 이벤트를 트리거합니다.
  	divElement.querySelector('.btnBookMark').click();
	}

	//데이터 보여주기
	function showBoardList(list,is_top){
		//모두 제거
		$(list_id).html("");

		//리스트 추가
		list.forEach(function(row){
			console.log(row);
			var $clone  = $("#tpl_list").clone();
    		var $content = $clone.html();

				$content = $content.replaceAll("{{rt_recruit_img}}",row.mm_img1);
				//$content = $content.replaceAll("{{rt_recruit_img}}",row.mm_thumb);
				$content = $content.replaceAll("{{rt_ing_cnt}}","현재 채용중 "+row.rt_ing_cnt+"건");

				$content = $content.replaceAll("{{comp_idx}}",row.mm_idx);
				$content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");

            var cate_type = "cate1";
            if(row.bd_cate=="업무"){
                cate_type = "cate1";
            }else if(row.bd_cate=="일상"){
                cate_type = "cate2";
            }else if(row.bd_cate=="질문"){
                cate_type = "cate3";
            }
    		$content = $content.replaceAll("{{rc_idx}}",row.rc_idx);

			// $addr = row.ad_addr;
			// $addr = $addr.replaceAll("경기 ", "");
			// $addr = $addr.replaceAll("강원 ", "");
			// $addr = $addr.replaceAll("충남 ", "");
			// $addr = $addr.replaceAll("충북 ", "");
			// $addr = $addr.replaceAll("경남 ", "");
			// $addr = $addr.replaceAll("경북 ", "");
			// $addr = $addr.replaceAll("전남 ", "");
			// $addr = $addr.replaceAll("전북 ", "");
			// $addr = $addr.replaceAll("제주 ", "");
			//
			// let addrParts = $addr.split(" ");
			// if (addrParts.length === 3) {
			//     $addr = addrParts[0] + " " + addrParts[2];
			// }
			//
			// $content = $content.replaceAll("{{ad_addr}}",$addr);
			$content = $content.replaceAll("{{rc_name}}",row.rc_name);
			$content = $content.replaceAll("{{distance}}",row.distance + "km")

			if(row.rc_nation_nm=="무관"){
				$content = $content.replaceAll("{{rc_nation_nm}}","국적무관");
			}else{
				$content = $content.replaceAll("{{rc_nation_nm}}",row.rc_nation_nm);
			}

			// if(row.rc_age_nm=="무관"){
			// 	$content = $content.replaceAll("{{rc_age_nm}}","나이무관");
			// }else{
			// 	$content = $content.replaceAll("{{rc_age_nm}}",row.rc_age_nm.replaceAll(" 이상","").replaceAll(" 이하",""));
			// }


			// var $categoryHtml = $('<div>' + row.rt_work_img + '</div>');
    	// $categoryHtml.filter('img:gt(1)').remove();
    	// $categoryHtml = $categoryHtml.prop('outerHTML');
			var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
			$images.slice(2).remove();
			var $categoryHtml = $images.parent().html();
			if($categoryHtml==undefined){
				$content = $content.replaceAll("{{rt_work_img}}", "");
			}else{
				$content = $content.replaceAll("{{rt_work_img}}", $categoryHtml);
			}

			// $work_type = row.rt_work_type;
			// $work_type = $work_type.replaceAll("주간","<font style='color:#3a70db;'>주간</font>");
			// $work_type = $work_type.replaceAll("야간", "<font style='color:#F86048;'>야간</font>"); //빨강
			// $work_type = $work_type.replaceAll("2교대", "<font style='color:#1B9D7D;'>2교대</font>"); //민트
			// $work_type = $work_type.replaceAll("근무협의", "<font style='color:#333;'>근무협의</font>"); //검정
			// $work_type = $work_type.replaceAll("시간협의", "<font style='color:#333;'>근무협의</font>"); //검정
			//
			// $content = $content.replaceAll("{{rt_work_type}}",$work_type);

			//if(row.rt_price_type.indexOf('급여협의') > -1){
			//	$content = $content.replaceAll("{{rt_price_type}}","");
			//}else{
				$content = $content.replaceAll("{{rt_price_type}}",row.rt_price_type);
			//}

			// if(row.rt_price.indexOf(">0<") > 0 || row.rt_price.indexOf('급여협의') > -1){
			// 	$content = $content.replaceAll("{{rt_price}}","");
			// }else{
			// 	$content = $content.replaceAll("{{rt_price}}",row.rt_price);
			// }
			$content = $content.replaceAll("{{mm_id}}",row.mm_id);
			$content = $content.replaceAll("{{timestamp}}",row.timestamp);

			if(row.mm_comp_num == ''){
				$content = $content.replaceAll("{{compAuth}}","");
			}else{
				$content = $content.replaceAll("{{compAuth}}","<img src='/resource/shop/web/img/certimg2.png' style='height:24px;'> "+row.mm_comp_name);
			}

    		//복사한 내용 다시 넣기
    		$clone.html($content);

    		$(list_id).append($clone.html());
		});

		if(is_top==true){
			var ajax_top = $(".search-result").position().top - 30;
			$( 'html, body' ).animate( { scrollTop : ajax_top }, 400 );
		}
	}

	function get_addrlist(depth,ad1,ad2){
		if(depth==3){
			initPage(true);
			return;
		}

		var params = {};
		params["depth"] = depth;
	    params["addr1"] = ad1;
	    params["addr2"] = ad2;

	    comAjax("/common_proc/get_addrlist",params,function(ret){
	        if(ret.code=="00"){
	        	var html = "";

	        	if(depth==1){
	        		var item ="";
		        	item +="<label class='form-checkbox'>";
	                item +="    <input type='radio' data-addr1='"+ad1+"' checked name='searchArea2' value=''>";
	                item +="    <span class='label'>전체</span>";
	                item +="</label>";
	                html +=item;
	            }else{
	            	if(ad2!=""){
	            		var item ="";
			        	item +="<label class='form-checkbox'>";
		                item +="    <input type='checkbox' data-addr1='"+ad1+"' data-addr2='"+ad2+"' checked name='searchArea3' value=''>";
		                item +="    <span class='box'>전체</span>";
		                item +="</label>";
		                html +=item;
	            	}
	        	}

	        	for(var icnt=0;icnt<ret.data.list.length;icnt++){
	        		if(depth==1){
		        		var ca_ad1 = ret.data.list[icnt].ca_ad1;
		        		var ca_ad2 = ret.data.list[icnt].ca_ad2;
		        		var ca_ad3 = ret.data.list[icnt].ca_ad3;

		        		var item ="";
			        	item +="<label class='form-checkbox'>";
		                item +="    <input type='radio' data-addr1='"+ca_ad1+"' name='searchArea2' value='"+ca_ad2+"'>";
		                item +="    <span class='label'>"+ca_ad2+"</span>";
		                item +="</label>";

		                html +=item;
	        		}else{
	        			var ca_ad1 = ret.data.list[icnt].ca_ad1;
		        		var ca_ad2 = ret.data.list[icnt].ca_ad2;
		        		var ca_ad3 = ret.data.list[icnt].ca_ad3;

		        		var item ="";
			        	item +="<label class='form-checkbox'>";
		                item +="    <input type='checkbox' data-addr1='"+ca_ad1+"' data-addr2='"+ca_ad2+"' name='searchArea3' value='"+ca_ad3+"'>";
		                item +="    <span class='box'>"+ca_ad3+"</span>";
		                item +="</label>";

		                html +=item;
	        		}
	        	}

	        	if(depth==1){
	        		$(".area-gugun").html(html);
	        		$("[name='searchArea2']").eq(0).prop("checked",true);
	        		$(".area-dong").attr("hidden","");

	        		if(html==""){
	        			$(".area-gugun").attr("hidden","");
	        		}
	        	}else{
	        		$(".area-dong").html(html);
	        		$(".area-dong").removeAttr("hidden");

	        		if(html==""){
	        			$(".area-dong").attr("hidden","");
	        		}
	        	}

	        	initPage(true);
	        }else{
	            commonAlert(ret.msg,function(){});
	            return;
	        }
	    });
	}


	//검색
	function getRecoList(){
		//파라미터 설정
		var params = {};
		params["addr1"] = $("[name='searchArea1']:checked").val()=="전체" ? "":$("[name='searchArea1']:checked").val();
		params["addr2"] = $("[name='searchArea2']:checked").val();
		params["addr3"] = $("[name='searchArea3']:checked").val();

		comAjax("/recruit_proc/search_web_reco_list",params,function(ret){
			if(ret.code=="00"){
				var list = ret.data.list;
				//모두 제거
				$("#my-keen-slider").html("");
				$(".arrow--left").remove();
				$(".arrow--right").remove();
				//리스트 추가
				list.forEach(function(row){
					//console.log(row);
					var $clone  = $("#tpl_reco_list").clone();
	    		var $content = $clone.html();
          var cate_type = "cate1";
          if(row.bd_cate=="업무"){
              cate_type = "cate1";
          }else if(row.bd_cate=="일상"){
              cate_type = "cate2";
          }else if(row.bd_cate=="질문"){
              cate_type = "cate3";
          }
	    		$content = $content.replaceAll("{{rc_idx}}",row.rc_idx);
	    		$content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");
					$content = $content.replaceAll("{{ad_addr}}",row.ad_addr);
					$content = $content.replaceAll("{{rc_name}}",row.rc_name);
					$content = $content.replaceAll("{{distance}}",row.distance + "km")
					$content = $content.replaceAll("{{rc_nation_nm}}",row.rc_nation_nm);
					$content = $content.replaceAll("{{rc_age_nm}}",row.rc_age_nm.replaceAll(" 이상","").replaceAll(" 이하",""));

					$content = $content.replaceAll("{{rt_recruit_img}}",row.rt_recruit_img);

					var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
					$images.slice(2).remove();
					var $categoryHtml = $images.parent().html();
					//$categoryHtml.filter('img:gt(1)').remove();
					//$categoryHtml = $categoryHtml.prop('outerHTML');
					$content = $content.replaceAll("{{rt_work_img}}", $categoryHtml);
					$content = $content.replaceAll("{{rt_work_type}}",row.rt_work_type);
					$content = $content.replaceAll("{{rt_price_type}}",row.rt_price_type);
					if(row.rt_price.indexOf('급여협의') > -1){
						$content = $content.replaceAll("{{rt_price}}","");
					}else{
						$content = $content.replaceAll("{{rt_price}}",row.rt_price);
					}
					$content = $content.replaceAll("{{mm_id}}",row.mm_id);
					$content = $content.replaceAll("{{rt_hash}}",row.rt_hash);
					$content = $content.replaceAll("{{timestamp}}",row.timestamp);

					$clone.html($content);

					$("#my-keen-slider").append($clone.html());
				});

				//복사한 내용 다시 넣기
    		var slider = new KeenSlider(".recomm-slider .slider-wrap", {
            loop: true,
            slides: { perView: 4 },
        }, [
            addActiveClass,
            sliderArrows
        ]);

        var interval = 0;
				var sliderElement = document.getElementById("my-keen-slider");
				function autoplay(run) {
				    clearInterval(interval);
				    interval = setInterval(() => {
				      if (run && slider) {
				        slider.next();
				      }
				    }, 3400);
				}

				sliderElement.addEventListener("mouseover", () => {
					autoplay(false);
				});
				sliderElement.addEventListener("mouseout", () => {
					autoplay(true);
				});
				autoplay(true);
			}else{

			}
		});
	}

	function fSearchConInit(){
		$("input:checkbox").prop("checked", false);
		$('#multiarea').html('');
		initPage(true);
		//getBoardList(true);
		//getRecoList();
	}

	function addMultiarea(gbn,depth,text) {

			if(text=="전체"){
					fSearchConInit();
					$("#multiarea").append('<span class="multiAreaBox alladdr" style="margin-right:10px; border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;" >' + text + '</span>');
				 	return;
			}else{
				$(".alladdr").remove();
			}

			if(depth=='1'){ text = text + '전체'; }
			var existingSpan = $("#multiarea").find("span:contains('" + text + "')");

			// gbn이 'A'인 경우
			if (gbn === 'A') {
					// 이미 존재하는 경우 아무 동작 안함
					if (existingSpan.length > 0) {
							return;
					}
					// 없는 경우 추가
					else {
						if($('#multiarea .multiAreaBox').length > 9){
							commonAlert("최대 10개까지 선택할 수 있습니다.",function(){});
							return;
						}else{
							$("#multiarea").append('<span class="multiAreaBox" style="margin-right:10px; border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;" data-addr="'+depth+',' + text + '">' + text + '<span style="margin-left:5px; cursor:pointer;" onclick="$(this).parent().remove();getBoardList(true);">×</span></span>');
						}
					}
			}
			// gbn이 'R'인 경우
			else if (gbn === 'R') {
					// 이미 존재하는 경우 제거
					if (existingSpan.length > 0) {
							existingSpan.remove();
					}
					// 없는 경우 추가
					else {
						if($('#multiarea .multiAreaBox').length > 9){
							commonAlert("최대 10개까지 선택할 수 있습니다.",function(){});
							return;
						}else{
							$("#multiarea").append('<span class="multiAreaBox" style="margin-right:10px; border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;" data-addr="'+depth+',' + text + '">' + text + '<span style="margin-left:5px; cursor:pointer;" onclick="$(this).parent().remove();getBoardList(true);">×</span></span>');
						}
					}
			}
	}

</script>
<?php
/*
echo "[[test]]";
$test = "ㅁㅁㅁ>#물류<";
echo "pos:[".strpos(trim($test), '>#물류<')."]";
if(strpos(trim($test), '>#물류<') ===false){
	echo "false";
}else{
	echo "true";
}

$arg = ";#주간</p> <p style=\"color:#3a70db; background-color:transparent; padding-left:0;\" class='tag'>&nbsp;&nbsp;#생산</p> <p s";
$work = "생산";
if(strpos(trim($arg), '#'.trim($work).'<') === false){
	echo "1false";
}else{
	echo "1true";
}
*/
 ?>
