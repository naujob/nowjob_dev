<?php
	$banner_list = getBannerList("BN00010001");
	$banner = null;
	if(count($banner_list) > 0){
		$banner = $banner_list[0];
	}

	function fRemoveBigAdr($arg){

		$parts1 = explode(",", $arg); // $arg를 ',' 기준으로 분할
		$arg = $parts1[0]; // 분할된 첫 번째 요소를 $arg에 할당


		//경기, 충남 등 도 이름 제거
		$arg = str_replace("서울특별시 ", "서울 ", $arg);
		$arg = str_replace("경기 ", "", $arg);
		$arg = str_replace("강원 ", "", $arg);
		$arg = str_replace("충남 ", "", $arg);
		$arg = str_replace("충북 ", "", $arg);
		$arg = str_replace("경남 ", "", $arg);
		$arg = str_replace("경북 ", "", $arg);
		$arg = str_replace("전남 ", "", $arg);
		$arg = str_replace("전북 ", "", $arg);
		$arg = str_replace("제주 ", "", $arg);
		$arg = str_replace("경기도 ", "", $arg);
	  $arg = str_replace("강원도 ", "", $arg);
	  $arg = str_replace("충남도 ", "", $arg);
	  $arg = str_replace("충북도 ", "", $arg);
	  $arg = str_replace("경남도 ", "", $arg);
	  $arg = str_replace("경북도 ", "", $arg);
	  $arg = str_replace("전남도 ", "", $arg);
	  $arg = str_replace("전북도 ", "", $arg);
	  $arg = str_replace("제주도 ", "", $arg);

		$parts = explode(" ", $arg);
		if(count($parts) >= 3) {
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

	function getNego($txt){
		if(strpos($txt,'협의') > -1){
			return "<em>&nbsp;</em>";
		}else{
			return $txt;
		}
	}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<div id="content" class="page page-recruit">
    <main class="page-content">
        <div class="visual-banner recruit">
            <div class="container">
                <div class="visual-content">
                    <p>새로 등록된 추천 공고</p>
                    <p class="headline">지금 뜨는, 좋은 일자리</p>
                    <a href="javascript:void(0);" id="btnRegist" class="btn-round">이력서 작성</a>
                </div>
            </div>
        </div>

        <div class="section recomm-keyword">
            <div class="container" style="position:relative;">
                <p class="title">#추천키워드</p>
								<div id="recomm-keyword-left-arrow" style="display: inline-block; width: 20px; height: 20px; line-height: 17px; border: 1px solid #777; border-radius: 10px; text-align: center; position: absolute; top: 10px; cursor:pointer;">←</div>
                <div id="hashtag-container" class="hashtag" style="display:inline-block; width: 1190px; margin-left: 30px; transition: scroll 0.3s; ">
                    <?php foreach($tag_list as $tag) : ?>
                    	<a class="tag btnTag" href="javascript:void(0);" data-tag="<?=$tag?>">#<?=$tag?></a>
                    <?php endforeach;?>
                </div>
								<div id="recomm-keyword-right-arrow" style="display: inline-block; width: 20px; height: 20px; line-height: 17px; border: 1px solid #777; border-radius: 10px; text-align: center; position: absolute; top: 10px; margin-left: 10px; cursor:pointer;">→</div>
            </div>
        </div>
        <div class="recomm-slider recruit">
            <div class="container swiperX">
                <h2 class="primary-title">추천공고</h2>

                <div class="slider-wrap keen-slider swiper-wrapperX" id="my-keen-slider" style="width: calc(100% - 0px);">
										<?php $counter = 0; ?>
                    <?php foreach($reco_list as $row) : ?>
											<?php
												$counter++;
												if ($counter > 24) {
													break;
												}
											?>
					    <!-- 목록 -->
	                    <div class="keen-slider__slide swiper-slide" style="cursor:pointer;">
	                        <div class="recruit-card">
	                            <div class="thumb-wrap">
	                                <!-- 추천라벨 -->
	                                <p class="recomm-badge" style="display:none;">추천</p>
	                                <!-- 썸네일 -->
	                                <div class="thumb click-item-new" data-link="/recruit/recruit_detail/<?=$row["rc_idx"]?>">
	                                    <a data-href="/recruit/recruit_detail/<?=$row["rc_idx"]?>" ><img data-src="<?=getRecruitImg($row)?>" alt="" class="lozad"></a>
	                                </div>
	                                <!-- 즐겨찾기 추가 -->
	                                <button type="button" data-bk_type="RECRUIT" data-bk_key="<?=$row["rc_idx"]?>" data-user_id="<?=$row["mm_id"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 추가</span></button>
	                            </div>
	                            <div class="content-wrap click-item-new" data-link="/recruit/recruit_detail/<?=$row["rc_idx"]?>">
	                                <div class="inline-group">
	                                    <!-- 카테고리 라벨 -->
	                                    <div style="max-width: 200px; overflow: hidden; height: 26px;"><?=getWorkingImg($row["rc_work"],2)?></div>
	                                    <!-- 지역 -->
	                                    <p class="area" data-addr="<?=$row["ad_addr"]?>"><?=fRemoveBigAdr($row["ad_addr"])?></p>
	                                </div>
	                                <!-- 제목 -->
	                                <p class="title"><strong><a data-href="/recruit/recruit_detail/<?=$row["rc_idx"]?>" ><?=$row["rc_name"]?></a></strong></p>
	                                <div class="meta">
	                                    <!-- 선택된 항목에 em태그로 처리 -->
	                                    <p><?=$row["rc_nation_nm"]?></p>
	                                    <p><?=str_replace(" 이상","",str_replace(" 이하","",$row["rc_age_nm"]))?></p>
	                                </div>
	                                <div class="price-wrap" style="position:relative;">
	                                    <!-- 선택된 항목에 em태그로 처리 -->
																			<p class="type" style="position:absolute; left:0px; top: 27px;"><?=fWorkColor(getWorkTypePoint($row["rc_work_type"],$row))?></p>
	                                    <p class="type"><?=getNego(getPriceTypePoint($row["rc_price_type"],$row))?></p>
																			<?php if(getRecuitPrice($row)=="<em class='num'>0</em> 원"){ ?>
																				<p class="amount" style="height:24px;"></p>
																			<?php }else{ ?>
																				<p class="amount"><?=getRecuitPrice($row)?></p>
																			<?php } ?>
	                                </div>
	                                <!-- 해시태그 -->
	                                <div class="hashtag">
	                                    <?=fHashSpace($row["rc_work"],getHashTag($row["rc_hash"],3))?>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
					 <?php endforeach;?>
                </div>
								<!--
								<div class="swiper-button-next">next</div>
								<div class="swiper-button-prev">prev</div>
								-->
            </div>
            <script>
                window.addEventListener('DOMContentLoaded', function () {
									fSlideSet();

/*
									const swiper = new Swiper('.swiper', {
  // Optional parameters
  //direction: 'vertical',
  loop: true,
	spaceBetween : 6,
	slidesPerView: 4,

  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
  },

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  // And if we need scrollbar
  scrollbar: {
    el: '.swiper-scrollbar',
  },
});
*/
                });

								function fSlideSet(){

									if (typeof KeenSlider === undefined) return;

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
										//autoplay(false);
									});
									sliderElement.addEventListener("mouseout", () => {
										//autoplay(true);
									});
									//autoplay(true);

								}
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
            <div class="container">

								<div class="search-form" style="text-align: center; padding: 50px 0 10px 0; position: relative; display: block;">
		                <label>
		                    <span class="sr-text">검색어 입력</span>
		                    <input type="search" name="g_keyword" id="searchMiddlebox" class="searchMiddlebox" autocomplete="off" value="" placeholder="검색어를 입력해 주세요" aria-required="true" style="padding: 16px; width: 530px; height: 50px; font-size: 16px; font-weight: 600; color: #222; border: 2px solid #777;" onflcus="blur();" onkeyup="if(event.keyCode==13){fSearchChk();} if($(this).val()==''){ $('#search_help').show(); }else{ $('#search_help').hide(); }">
												<div id="search_help" style="position: absolute; top: 65px; left: calc(50% - 94px); width: 20px; height: 20px; background-color: #57CCAF; color: #fff; font-size: 18px; font-weight: 600; line-height: 10px; border-radius: 20px; padding: 5px;cursor:pointer;" onmouseover="$('#search_info').show();" onmouseout="$('#search_info').hide();">!</div>
												<div id="search_info" style="display:none;position: absolute; top: 92px; left: calc(50% - 184px); border: 1px solid #57ccaf; border-radius: 10px; background-color: #fff; z-index: 10; padding: 15px;">
														<span style="display: block; font-weight: 600; margin-bottom: 10px; font-size: 1.1em;">검색조건</span>
														<span style="display: block;color: #777; line-height: 22px;">검색창에 원하는<br>업직종, 지역, 급여, 근무형태를<br>입력해 주세요</span>
												</div>
		                </label>
		                <button type="button" id="btnTopSearch" class="btn-search" onclick="fSearchChk();"
											style="position: absolute; top: 50px; right: 324px; width: 100px; height: 50px; border: 0; border-radius: 25px; background: #57CCAF url(https://nowjob.co.kr/resource/shop/web/img/ic_search.svg) no-repeat 30% 50%; background-size: 19px auto;">
											<span class="sr-text" style="width:57px;height:20px;color:#fff;font-size:16px;font-weight:600;top:15px; left: 30px;">검색</span>
										</button>
										<div class="suggestBox">
											<img src="/resource/shop/web/img/fav_keyword.png" style="float:left;"/>
											<ul>
												<?php foreach($tag_search as $tag) : ?>
													<li><a href="/home/search?text=<?=$tag?>"><?=$tag?></a></li>
		                    <?php endforeach;?>
											</ul>
										</div>
							  </div>

                <div class="search-result">
                    <!-- 퀵옵션 -->
                    <div class="sidebar">
                        <div class="widget">
                            <div class="widget-header">
                                <p class="title">지역별</p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-options align-center">
                                    <ul class="form-group col-4">
                                    	<li><a href="javascript:void(0);" onclick="setTab(this,'addr','전체');" role="button" class="widget-option">전체</a></li>
                                    	<?php foreach($g_addr_list as $key1 => $addr) : ?>
                                        <li><a href="javascript:void(0);" onclick="setTab(this,'addr','<?=$addr["ca_ad1"]?>');" role="button" class="widget-option"><?=$addr["ca_ad1"]?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <a href="/recruit/index_map" class="find-map" target="_blank">지도에서 찾기</a>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="widget-header">
                                <p class="title">업직종별</p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-options">
                                    <ul class="form-group col-4">
																			<li style='flex: 0 0 20%;'><a href="javascript:void(0);" onclick="setTab(this,'category','all');"  class="widget-option">전체</a></li>
																			<?php $wrok_i = 1; ?>
                                    	<?php foreach($wrok_list as $row) : ?>
																				<?php $wrok_i++; ?>
																				<?php if($wrok_i % 4 == 1){ echo "<li style='flex: 0 0 20%;'>"; } ?>
																				<?php if($wrok_i % 4 == 2){ echo "<li style='flex: 0 0 20%;'>"; } ?>
																				<?php if($wrok_i % 4 == 3){ echo "<li style='flex: 0 0 27%;'>"; } ?>
																				<?php if($wrok_i % 4 == 0){ echo "<li style='flex: 0 0 31%;'>"; } ?>
																				<?php if($row["cd_val"]=="매장관리" || $row["cd_val"]=="고객상담"){ ?>
																					<a href="javascript:void(0);" onclick="setTab(this,'category','<?=$row["cd_val"]?>');"  class="widget-option" style="/* margin-left:-21px; */"><?=$row["cd_val"]?></a></li>
																				<?php }else{ ?>
																					<a href="javascript:void(0);" onclick="setTab(this,'category','<?=$row["cd_val"]?>');"  class="widget-option"><?=$row["cd_val"]?></a></li>
																				<?php } ?>
		                                <?php endforeach;?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="widget-header">
                                <p class="title">근무형태/급여</p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-options">
                                    <ul class="form-group col-4">
																			<li><a href="javascript:void(0);" onclick="setTab(this,'work_type','all');"  class="widget-option">전체</a></li>
                                        <?php foreach($work_type_list as $row) : ?>
		                                <li><a href="javascript:void(0);" onclick="setTab(this,'work_type','<?=$row["cd_val"]?>');"  class="widget-option"><?=$row["cd_val"]?></a></li>
		                                <?php endforeach;?>
																		<li><a href="javascript:void(0);" onclick="setTab(this,'price_type','all');"  class="widget-option">전체</a></li>
		                                <?php foreach($price_type_list as $row) : ?>
		                                <li><a href="javascript:void(0);" onclick="setTab(this,'price_type','<?=$row["cd_val"]?>');"  class="widget-option"><?=$row["cd_val"]?></a></li>
		                                <?php endforeach;?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="widget-header">
                                <p class="title">성별</p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-options">
                                    <ul class="form-group col-4">
																				<li><a href="javascript:void(0);" onclick="setTab(this,'sex','all');" class="widget-option">전체</a></li>
                                        <li><a href="javascript:void(0);" onclick="setTab(this,'sex','남자');" class="widget-option">남자</a></li>
                                        <li><a href="javascript:void(0);" onclick="setTab(this,'sex','여자');" class="widget-option">여자</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="widget-header">
                                <p class="title">근무요일</p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-options align-center">
                                    <ul class="form-group col-4 left-week">
																			<li><a href="javascript:void(0);" onclick="setTab(this,'week','all');" class="widget-option">전체</a></li>
                                        <?php foreach($week_list as $row) : ?>
		                                <li><a href="javascript:void(0);" onclick="setTab(this,'week','<?=$row["cd_val"]?>');"  class="widget-option"><?=$row["cd_val"]?></a></li>
		                                <?php endforeach;?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="widget">
                            <div class="widget-header">
                                <p class="title">나이</p>
                            </div>
                            <div class="widget-body">
                                <div class="widget-options">
                                    <ul class="form-group col-4">
																			<li><a href="javascript:void(0);" onclick="setTab(this,'eage','all');" class="widget-option">전체</a></li>
                                    	<?php for($icnt=2;$icnt < 8;$icnt++) : ?>
                                        	 <li><a href="javascript:void(0);" onclick="setTab(this,'eage','<?=($icnt * 10)?>');" class="widget-option"><?=($icnt * 10)?>대</a></li>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 검색내용 -->
                    <div class="content-area">

                        <div id="advancedSearchWidget" class="widget" hidden>
                            <div class="widget-header">
                                <p class="title">지역별</p>
                                <a href="/recruit/index_map" class="find-map">지도에서 찾기</a>
                            </div>
                            <div class="widget-body">
                                <!-- 상세검색 -->
                                <div class="advanced-search">
                                    <div class="area">
                                        <!-- 지역(시) -->
                                        <div class="area-si">
                                        	<label class="option">
                                                <input type="radio" name="searchArea1" value="전체">
                                                <span class="btn">전체</span>
                                            </label>
                                        	<?php foreach($g_addr_list as $key1 => $addr) : ?>
	                                        <label class="option">
                                                <input type="radio" name="searchArea1" value="<?=$addr["ca_ad1"]?>">
                                                <span class="btn"><?=$addr["ca_ad1"]?></span>
                                            </label>
	                                        <?php endforeach; ?>
                                        </div>
                                        <!-- 지역(구군) -->
                                        <div class="area-gugun">
                                            <label class="form-checkbox">
                                                <input type="radio" name="searchArea2" value="">
                                                <span class="label">전체</span>
                                            </label>
                                        </div>
                                        <!-- 지역(동) -->
                                        <div class="area-dong" hidden>
                                        	<label class="form-checkbox">
                                                <input type="radio" name="searchArea3" value="">
                                                <span class="box">전체</span>
                                            </label>
                                        </div>

                                        <script>
                                            /* 지역(동) 옵션 표시 */
                                            function showSearchArea3 () {
                                                document.querySelector('.area-dong').hidden = false;
                                            }

                                            /* 지역(시) 선택 해제 */
                                            function deSelectArea1 (items) {
                                                items.forEach(function (item) {
                                                    if (item.classList.contains('on')) {
                                                        item.classList.remove('on');
                                                    }
                                                });
                                            }

                                            window.addEventListener('DOMContentLoaded', function () {
                                                var options = document.querySelectorAll('.widget-option[data-id]');
                                                var areas = document.querySelectorAll('input[name="searchArea1"]');

                                                /* 지역(시) 선택 */
                                                areas.forEach(function (area) {
                                                    area.addEventListener('change', function (e) {
                                                        var target = document.querySelector('[data-id="#'+ this.getAttribute('id') +'"]');

                                                        if (!target) return;

                                                        deSelectArea1(options);

                                                        target.classList.add('on');
                                                    });
                                                });


                                                /* 지역(구군) 선택시 지역(동) 옵션 표시 */
                                                //document.querySelector('.area-gugun').addEventListener('change', function (e) {
                                                    //showSearchArea3();
                                                //});
                                            });
                                        </script>
                                    </div>
                                    <div class="other">
                                        <!-- 추가옵션 -->
                                        <div class="form-table">
                                            <div class="form-row">
                                                <p class="form-label">업직종별</p>
                                                <div class="value">
																										<div class="form-group col-8" style="float: left; width: 94px; display: inline-block;">
																											<label class="form-checkbox" style="width: 100%;">
																													<input type="checkbox" name="category_all" value="all" class="js-check-all">
																													<span class="box">전체</span>
																											</label>
																										</div>
                                                    <div class="form-group col-8" style="display: inline-block; float: left; width: calc(100% - 27px); margin-left: 105px; margin-top: -21px;">
                                                        <?php foreach($wrok_list as $row) : ?>
                                                        <label class="form-checkbox">
                                                            <input type="checkbox" name="category[]" value="<?=$row["cd_val"]?>">
                                                            <span class="box"><?=$row["cd_val"]?></span>
                                                        </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <p class="form-label">근무형태/급여</p>
                                                <div class="value">
                                                    <div class="form-group col-8">
																											<label class="form-checkbox">
																													<input type="checkbox" name="work_type_all" value="all" class="js-check-all">
																													<span class="box">전체</span>
																											</label>
                                                        <?php foreach($work_type_list as $row) : ?>
						                                <label class="form-checkbox">
                                                            <input type="checkbox" name="work_type[]" value="<?=$row["cd_val"]?>">
                                                            <span class="box"><?=$row["cd_val"]?></span>
                                                        </label>
						                                <?php endforeach;?>
						                                <?php foreach($price_type_list as $row) : ?>
						                                <label class="form-checkbox">
                                                            <input type="checkbox" name="price_type[]" value="<?=$row["cd_val"]?>">
                                                            <span class="box"><?=$row["cd_val"]?></span>
                                                        </label>
						                                <?php endforeach;?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <p class="form-label">성별</p>
                                                <div class="value">
                                                    <div class="form-group col-8">
																											<label class="form-checkbox">
																													<input type="checkbox" name="sex_all" value="all" class="js-check-all">
																													<span class="box">전체</span>
																											</label>
                                                        <label class="form-checkbox">
                                                            <input type="checkbox" name="sex[]" value="남자">
                                                            <span class="box">남자</span>
                                                        </label>
                                                        <label class="form-checkbox">
                                                            <input type="checkbox" name="sex[]" value="여자">
                                                            <span class="box">여자</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <p class="form-label">근무요일</p>
                                                <div class="value">
                                                    <div class="form-group col-8">
                                                        <label class="form-checkbox">
                                                            <input type="checkbox" name="week_yn" value="Y">
                                                            <span class="box">전체</span>
                                                        </label>
                                                        <?php foreach($week_list as $row) : ?>
			                                				<label class="form-checkbox">
	                                                            <input type="checkbox" name="week[]" value="<?=$row["cd_val"]?>">
	                                                            <span class="box"><?=$row["cd_val"]?></span>
	                                                        </label>
		                                				<?php endforeach;?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <p class="form-label">나이</p>
                                                <div class="value">
                                                    <div class="form-group col-8">
																											<label class="form-checkbox">
																													<input type="checkbox" name="eage_all" value="all" class="js-check-all">
																													<span class="box">전체</span>
																											</label>
                                                        <?php for($age=2;$age < 8;$age++) : ?>
			                                        		<label class="form-checkbox">
	                                                            <input type="checkbox" name="eage[]" value="<?=($age * 10)?>">
	                                                            <span class="box"><?=($age * 10)?>대</span>
	                                                        </label>
				                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
														<div id="multiarea" style="padding: 20px;height: 59.2px;">
														</div>
														<span style="right: 55px; line-height: 23px; padding: 10px 20px; position: absolute; bottom: 7px; font-size: 14px; cursor: pointer;" onclick="fSearchConInit();"><img src="/resource/shop/web/img/reload_btn.png" style="width: 16px; margin-top: 3px; margin-right: 5px;" />초기화</span>
														<span class="btnCloseAdv"><img src="/resource/shop/web/img/icon_close.png" style="width:23px" /></span>
                        </div>
                        <!-- 검색결과 -->
                        <div class="result-wrap">

                            <div class="result-header">
                                <!-- 총개수 -->
                                <p class="total">채용정보 <em id="total_count"></em>건</p>
                                <!-- 정렬 -->
                                <div class="orderby">
                                    <label class="option">
                                        <input type="radio" name="order_by" value="recent" checked>
                                        <span class="label">최신순</span>
                                    </label>
                                    <label class="option">
                                        <input type="radio" name="order_by" value="fav" >
                                        <span class="label">인기순</span>
                                    </label>
                                </div>
                            </div>

                            <!-- 목록 -->
                            <ol class="result-list" id="ajaxList" data-page="1" style="min-height:400px;">
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

                            <div class="list-action v2">
                                <a href="/recruit/index_map" title="지도에서 찾기" class="btn-map"><span class="sr-text">지도보기</span></a>
                                <div class="send-request">
                                    <button id="btnRequest"  type="button" class="btn-primary btn-request">
                                        <span>지원하기</span>
                                        <!-- 선택된 목록개수 -->
                                        <span class="counter">
                                            (<em class="selected">0</em> /
                                            <span class="total">5</span>)
                                        </span>
                                    </button>
                                </div>
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
</div><!-- //page -->

<!-- 리스트 템플릿 -->
<template id="tpl_list">
	<li class="list-item item-hover">
        <div class="item-info colgroup">
            <div class="thumb-wrap">
                <!-- 추천라벨 -->
                <p class="recomm-badge"><span>추천</span></p>
                <button type="button" data-bk_type="RECRUIT" data-bk_key="{{rc_idx}}" data-user_id="{{mm_id}}" class="btnBookMark btn-bookmark js-toggle-bookmark {{book_yn}}"><span class="sr-text">즐겨찾기 추가</span></button>
                <!-- 썸네일 -->
                <div class="thumb big click-item-new" data-link="/recruit/recruit_detail/{{rc_idx}}">
                    <a data-href="/recruit/recruit_detail/{{rc_idx}}" ><img src="{{rt_recruit_img}}" alt=""></a>
                </div>
            </div>
            <div class="content-wrap click-item-new" data-link="/recruit/recruit_detail/{{rc_idx}}">
            	<div class="timestamp">등록일 : {{timestamp}}</div>
                <div class="inline-group">
                    <!-- 직종라벨 -->
                    <p class="category">{{rt_work_img}} {{compAuth}}</p>
                </div>
                <!-- 제목 -->
                <p class="title">{{compName}}</p>
								<p class="title"><strong><a data-href="/recruit/recruit_detail/{{rc_idx}}" >{{rc_name}}</a></strong></p>

                <!-- 추가조건 -->
                <div class="meta">
                    <!-- 근무시간 -->
                    <!-- <p class="type">{{rt_work_type}}</p> -->
                    <!-- <span> | </span> -->
                    <p>{{rc_nation_nm}}</p>
                    <span> | </span>
                    <p>{{rc_age_nm}}</p>
                </div>
            </div>
            <!-- 지역 -->
						<div style="width:130px; text-align: center;">
							<p class="type">{{rt_work_type}}</p>
	            <p class="area" style="color:#333;">{{ad_addr}}</p>
						</div>
            <!-- 지불방식 -->
            <div class="pay">
                <p class="period" style="font-size: 16px;">{{rt_price_type}}</p>
                <p class="amount"><em class="num">{{rt_price}}</em></p>
            </div>
            <!-- 목록선택 (제한20개) -->
            <div class="selection">
                <label class="form-checkbox recruit-checkbox lg">
                    <input type="checkbox" name="chk[]" value="{{rc_idx}}" onchange="selectRecruitList(5,this)" class="sr-text">
                    <span class=label-red></span>
                    <span class="sr-text">지원하기 선택</span>
                </label>
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

				if($("[name='mm_type']").val()!="MEM"){
        	commonAlert("기업회원은 지원 할 수 없습니다.",function(){});
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

		$('.btn-map').insertBefore('#bottomchatbtn');

		//추천키워드 슬라이드 시작
		var scrollAmount = 5; // Set the scroll amount
    var scrollInterval;

    // Function to scroll left
    function scrollLeft() {
        $('#hashtag-container').scrollLeft($('#hashtag-container').scrollLeft() - scrollAmount);
    }

    // Function to scroll right
    function scrollRight() {
        $('#hashtag-container').scrollLeft($('#hashtag-container').scrollLeft() + scrollAmount);
    }

    // Mouse enter event for left arrow
    $('#recomm-keyword-left-arrow').mouseenter(function() {
        scrollInterval = setInterval(scrollLeft, 100);
    }).mouseleave(function() {
        clearInterval(scrollInterval);
    });

    // Mouse enter event for right arrow
    $('#recomm-keyword-right-arrow').mouseenter(function() {
        scrollInterval = setInterval(scrollRight, 100);
    }).mouseleave(function() {
        clearInterval(scrollInterval);
    });
		//추천키워드 슬라이드 종료

		var tagWidth = $('.btnTag').outerWidth(true);
		// Click event for left arrow
    $('#recomm-keyword-left-arrow').click(function() {
        // Scroll left by one tag's width
        $('#hashtag-container').animate({
            scrollLeft: '-=' + tagWidth
        }, 300); // Adjust the animation speed (300ms)
    });

    // Click event for right arrow
    $('#recomm-keyword-right-arrow').click(function() {
        // Scroll right by one tag's width
        $('#hashtag-container').animate({
            scrollLeft: '+=' + tagWidth
        }, 300); // Adjust the animation speed (300ms)
    });

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
		addMultiarea('A','1',$(this).val(),this);
	});

	$(document).on("click","[name=searchArea2]",function(){
		if($(this).val()==''){//지역전체이면
			changeSearchAddr(2,$(this).attr("data-addr1"),$(this).data('addr1')+'전체');
			addMultiarea('A','2',$(this).data('addr1')+'전체',this);
		}else{
			changeSearchAddr(2,$(this).attr("data-addr1"),$(this).val());
			//$('#multiarea').append('<span style="margin-right:10px; border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;">'+$(this).val()+' <span style="margin-left:5px; cursor:pointer;" onclick="$(this).parent().hide();">×</span></span>');
			addMultiarea('A','2',$(this).val(),this);
		}
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
			addMultiarea('R','3',$(this).val(),this);
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

		params["multiAddr1"] = multiAddr1.replace('전체','');
		params["multiAddr2"] = multiAddr2.replace('전체','');
		params["multiAddr3"] = multiAddr3.replace('전체','');

		//console.log("search_web_list call:"+multiAddr1);
		//console.log("search_web_list call:"+multiAddr2);
		//console.log("search_web_list call:"+multiAddr3);

		comAjax("/recruit_proc/search_web_list",params,function(ret){
			//alert(1);
			if(ret.code=="00"){
				//console.log(ret);
				$(list_id).attr("data-page",list_page);
				$("#total_count").html(ret.data.count);
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

		/* //지역변경시  추천공고를 재 조회하므로 여기서는 주석 처리
		//지역을 선택한 경우 추천공고도 해당 지역으로 검색
		comAjax("/recruit_proc/search_web_reco_list",params,function(ret){
			//console.log('reco',ret);
			if(ret.code=="00"){
				console.log(ret.data.list);
				var list = ret.data.list;
				var newHtml = '';

				list.forEach(function(row){

								newHtml += '<div class="keen-slider__slide">';
								newHtml += '		<div class="recruit-card">';
								newHtml += '				<div class="thumb-wrap">';
								newHtml += '						<!-- 추천라벨 -->';
								//newHtml += '						<p class="recomm-badge">추천</p>';
								newHtml += '						<!-- 썸네일 -->';
								newHtml += '						<div class="thumb click-item-new" data-link="/recruit/recruit_detail/'+row.rc_idx+'">';
								newHtml += '								<a href="/recruit/recruit_detail/'+row.rc_idx+'" target="_blank"><img src="'+row.rt_recruit_img+'" alt="" class="lozadX"></a>';
								newHtml += '						</div>';
								newHtml += '						<!-- 즐겨찾기 추가 -->';
								newHtml += '						<button type="button" data-bk_type="RECRUIT" data-bk_key="'+row.rc_idx+'" data-user_id="'+row.mm_id+'" class="btnBookMark btn-bookmark js-toggle-bookmark '+(row.book_yn=='Y'?'on':'')+'"><span class="sr-text">즐겨찾기 추가</span></button>';
								newHtml += '				</div>';
								newHtml += '				<div class="content-wrap click-item-new" data-link="/recruit/recruit_detail/'+row.rc_idx+'">';
								newHtml += '						<div class="inline-group">';
								newHtml += '								<!-- 카테고리 라벨 -->';
								newHtml += '								<div style="max-width: 200px; overflow: hidden; height: 26px;">'+row.rt_work_img+'</div>';
								newHtml += '								<!-- 지역 -->';
								newHtml += '								<p class="area" data-addr="'+row.ad_addr+'">'+fRemoveBigAdrJS(row.ad_addr)+'</p>';
								newHtml += '						</div>';
								newHtml += '						<!-- 제목 -->';
								newHtml += '						<p class="title"><strong><a href="/recruit/recruit_detail/'+row.rc_idx+'" target="_blank">'+row.rc_name+'</a></strong></p>';
								newHtml += '						<div class="meta">';
								newHtml += '								<!-- 선택된 항목에 em태그로 처리 -->';
								newHtml += '								<p>'+row.rc_nation_nm+'</p>';
								newHtml += '								<p>'+row.rc_age_nm.replace(/이상/g, "").replace(/이하/g, "")+'</p>';
								newHtml += '						</div>';
								newHtml += '						<div class="price-wrap" style="position:relative;">';
								newHtml += '								<!-- 선택된 항목에 em태그로 처리 -->';
								newHtml += '								<p class="type" style="position:absolute; left:0px; top: 27px;">'+fWorkColorJS(getWorkTypePointJS(row.rc_work_type))+'</p>';
								newHtml += '								<p class="type">'+getNegoJS(getPriceTypePointJS(row.rc_price_type))+'</p>';
								if(getRecuitPriceJS(row)=="<em class='num'>0</em> 원"){
									newHtml += '								<p class="amount" style="height:24px;"></p>';
								}else{
									newHtml += '								<p class="amount">'+getRecuitPriceJS(row)+'</p>';
								}
								newHtml += '						</div>';
								newHtml += '						<!-- 해시태그 -->';
								newHtml += '						<div class="hashtag">';
								newHtml += '								'+fHashSpaceJS(row.rc_work,getHashTagJS(row.rc_hash,3))+'';
								newHtml += '						</div>';
								newHtml += '				</div>';
								newHtml += '		</div>';
								newHtml += '</div>';

				});//list forEach
				$('#my-keen-slider').html(newHtml);
				fSlideSet();

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

	//데이터 보여주기
	function showBoardList(list,is_top){
		//모두 제거
		$(list_id).html("");

		//리스트 추가
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
    		$content = $content.replaceAll("{{rc_idx}}",row.rc_idx);
    		$content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");

				if(row.pm_idx == null){
					$content = $content.replaceAll("<span>추천</span>","");
					//$content = $content.replaceAll("{{timestamp}}",row.timestamp);
					$content = $content.replaceAll("{{timestamp}}",row.up_dt_timestamp);
				}else{
					//$content = $content.replaceAll("{{timestamp}}",row.pm_timestamp);
					$content = $content.replaceAll("{{timestamp}}","");
					$content = $content.replaceAll("class=\"timestamp\"","class=\"timestamp\" style=\"display:none;\"");
				}

			$addr = row.ad_addr;
			$addr = $addr.replaceAll("경기 ", "");
			$addr = $addr.replaceAll("강원 ", "");
			$addr = $addr.replaceAll("충남 ", "");
			$addr = $addr.replaceAll("충북 ", "");
			$addr = $addr.replaceAll("경남 ", "");
			$addr = $addr.replaceAll("경북 ", "");
			$addr = $addr.replaceAll("전남 ", "");
			$addr = $addr.replaceAll("전북 ", "");
			$addr = $addr.replaceAll("제주 ", "");

			let addrParts = $addr.split(" ");
			if (addrParts.length === 3) {
			    $addr = addrParts[0] + " " + addrParts[2];
			}

			$content = $content.replaceAll("{{ad_addr}}",$addr);
			$content = $content.replaceAll("{{rc_name}}",row.rc_name);
			$content = $content.replaceAll("{{distance}}",row.distance + "km")

			if(row.rc_nation_nm=="무관"){
				$content = $content.replaceAll("{{rc_nation_nm}}","국적무관");
			}else{
				$content = $content.replaceAll("{{rc_nation_nm}}",row.rc_nation_nm);
			}

			if(row.rc_age_nm=="무관"){
				$content = $content.replaceAll("{{rc_age_nm}}","나이무관");
			}else{
				$content = $content.replaceAll("{{rc_age_nm}}",row.rc_age_nm.replaceAll(" 이상","").replaceAll(" 이하",""));
			}

			$content = $content.replaceAll("{{rt_recruit_img}}",row.rt_recruit_img);

			// var $categoryHtml = $('<div>' + row.rt_work_img + '</div>');
    	// $categoryHtml.filter('img:gt(1)').remove();
    	// $categoryHtml = $categoryHtml.prop('outerHTML');
			var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
			$images.slice(2).remove();
			var $categoryHtml = $images.parent().html();
			$content = $content.replaceAll("{{rt_work_img}}", $categoryHtml);

			$work_type = row.rt_work_type;
			// $work_type = $work_type.replaceAll("주간","<font style='color:#3a70db;'>주간</font>");
			// $work_type = $work_type.replaceAll("야간", "<font style='color:#F86048;'>야간</font>"); //빨강
			// $work_type = $work_type.replaceAll("2교대", "<font style='color:#1B9D7D;'>2교대</font>"); //민트
			// $work_type = $work_type.replaceAll("근무협의", "<font style='color:#333;'>근무협의</font>"); //검정
			// $work_type = $work_type.replaceAll("시간협의", "<font style='color:#333;'>근무협의</font>"); //검정
			$work_type = $work_type.replaceAll("주간","<font style='color:#777;'>주간</font>");
			$work_type = $work_type.replaceAll("야간", "<font style='color:#777;'>야간</font>"); //빨강
			$work_type = $work_type.replaceAll("2교대", "<font style='color:#777;'>2교대</font>"); //민트
			$work_type = $work_type.replaceAll("근무협의", "<font style='color:#777;'>근무협의</font>"); //검정
			$work_type = $work_type.replaceAll("시간협의", "<font style='color:#777;'>근무협의</font>"); //검정

			$content = $content.replaceAll("{{rt_work_type}}",$work_type);
			//if(row.rt_price_type.indexOf('급여협의') > -1){
			//	$content = $content.replaceAll("{{rt_price_type}}","");
			//}else{
				$content = $content.replaceAll("{{rt_price_type}}",row.rt_price_type);
			//}

			if(row.rt_price.indexOf(">0<") > 0 || row.rt_price.indexOf('급여협의') > -1){
				$content = $content.replaceAll("{{rt_price}}","");
			}else{
				$content = $content.replaceAll("{{rt_price}}",row.rt_price);
			}
			$content = $content.replaceAll("{{mm_id}}",row.mm_id);


			if(row.mm_comp_num == ''){
				$content = $content.replaceAll("{{compAuth}}","");
			}else{
				//$content = $content.replaceAll("{{compAuth}}","<img src='/resource/shop/web/img/certimg2.png' style='height:24px;'> "+row.mm_comp_name);
				//$content = $content.replaceAll("{{compAuth}}","<img src='/resource/shop/web/img/certiconchk.png' style='height:24px;'> <span style='font-weight: 600; font-size: 18px; line-height: 24px;'>인증기업</span>");
				$content = $content.replaceAll("{{compAuth}}","");
			}
			$content = $content.replaceAll("{{compName}}",row.mm_comp_name);

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
					//autoplay(false);
				});
				sliderElement.addEventListener("mouseout", () => {
					//autoplay(true);
				});
				//autoplay(true);
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

	function addMultiarea(gbn,depth,text,obj) {

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
							$(obj).prop('checked', false);
							return;
						}else{
							if(text!=''){
								$("#multiarea").append('<span class="multiAreaBox" style="margin-right:10px; border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;" data-addr="'+depth+',' + text + '">' + text + '<span style="margin-left:5px; cursor:pointer;" onclick="fSearchBoxUnchk(this); $(this).parent().remove();getBoardList(true);">×</span></span>');
							}
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
							$(obj).prop('checked', false);
							return;
						}else{
							if(text!=''){
								$("#multiarea").append('<span class="multiAreaBox" style="margin-right:10px; border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;" data-addr="'+depth+',' + text + '">' + text + '<span style="margin-left:5px; cursor:pointer;" onclick="fSearchBoxUnchk(this); $(this).parent().remove();getBoardList(true);">×</span></span>');
							}
						}
					}
			}
	}

	function fSearchChk(){
		if($('#searchMiddlebox').val()==''){
			commonAlert("검색어를 입력해 주세요!",function(){});
			return;
		}
		 location.href='/home/search?text='+$('#searchMiddlebox').val();
	}

	function fSearchBoxUnchk(obj){
		var addr = $(obj).parent().data('addr');
		var addrParts = addr.split(',');
    var targetValue = addrParts[1];

		if(addrParts[0]=="1"){
			targetValue = targetValue.replace("전체","");
			$('input[name="searchArea2"][data-addr1="'+targetValue+'"]').prop('checked', false);
		}else{
			$('input[name="searchArea'+addrParts[0]+'"][value="' + targetValue + '"]').prop('checked', false);
		}
	}

	function fRemoveBigAdrJS(arg) {

		const parts1 = arg.split(",");
		arg = parts1[0];

	  // 경기, 충남 등 도 이름 제거
		arg = arg.replace("서울특별시 ", "서울 ");
	  arg = arg.replace("경기 ", "");
	  arg = arg.replace("강원 ", "");
	  arg = arg.replace("충남 ", "");
	  arg = arg.replace("충북 ", "");
	  arg = arg.replace("경남 ", "");
	  arg = arg.replace("경북 ", "");
	  arg = arg.replace("전남 ", "");
	  arg = arg.replace("전북 ", "");
	  arg = arg.replace("제주 ", "");
		arg = arg.replace("경기도 ", "");
	  arg = arg.replace("강원도 ", "");
	  arg = arg.replace("충남도 ", "");
	  arg = arg.replace("충북도 ", "");
	  arg = arg.replace("경남도 ", "");
	  arg = arg.replace("경북도 ", "");
	  arg = arg.replace("전남도 ", "");
	  arg = arg.replace("전북도 ", "");
	  arg = arg.replace("제주도 ", "");

	  const parts = arg.split(" ");
	  if (parts.length >= 3) {
	    return parts[0] + " " + parts[2];
	  } else {
	    return arg;
	  }
	}

	function fWorkColorJS(arg){
		arg = arg.replace("주간", "<font style='color:#3a70db;'>주간</font>"); //파랑
		arg = arg.replace("야간", "<font style='color:#F86048;'>야간</font>"); //빨강
		arg = arg.replace("2교대", "<font style='color:#1B9D7D;'>2교대</font>"); //민트
		arg = arg.replace("근무협의", "<font style='color:#333;'>근무협의</font>"); //검정
		arg = arg.replace("협의", "<font style='color:#333;'>협의</font>"); //검정

		return arg;
	}

	function getWorkTypePointJS(work_type) {
	  let result = "";
	  let arr_type = work_type.split(",");

	  if (arr_type.length > 2) {
	    return "<span>" + "시간협의" + "</span>";
	  } else {
	    result = "<span>";
	    arr_type.forEach(function(txt, index) {
	      result += (result !== "<span>" ? ", " : "") + txt;
	    });
	    result += "</span>";
	  }

	  return result;
	}

	function getPriceTypePointJS(price_type, info = {}) {
	  let point = "";
	  let result = "";
	  let arr_type = price_type.split(",");

	  if (info.rc_price_type === "주급") {
	    point = "주급";
	  } else if (info.rc_price_type && info.rc_price_type.includes("주급")) {
	    point = "주급";
	  } else if (info.rc_price_type && info.rc_price_type.includes("주급") && info.rc_price_type.includes("일급")) {
	    point = "일급";
	  } else if (info.rc_price_type && info.rc_price_type.includes("일급") && info.rc_price_type.includes("월급")) {
	    if (info.rc_month_price >= info.rc_day_price) {
	      point = "월급";
	    } else {
	      point = "일급";
	    }

	    if (info.rc_day_price === 0) {
	      arr_type.splice(0, 1); // Remove the first element (index 0)
	    }

	    if (info.rc_month_price === 0) {
	      arr_type.splice(1, 1); // Remove the second element (index 1)
	    }
	  } else if (info.rc_price_type && info.rc_price_type.includes("일급")) {
	    point = "일급";
	  } else if (info.rc_price_type && info.rc_price_type.includes("월급")) {
	    point = "월급";
	  }

	  if (info.rc_price_rep === "일급" || info.rc_price_rep === "주급" || info.rc_price_rep === "월급") {
	    point = info.rc_price_rep;
	  }

	  if (arr_type.length > 2) {
	    return "<em>" + "급여협의" + "</em>";
	  } else {
	    arr_type.forEach((txt, index) => {
	      if (txt === point) {
	        result += (result !== "" ? "," : "") + "<em>" + txt + "</em>";
	      } else {
	        result += (result !== "" ? "," : "") + "<span>" + txt + "</span>";
	      }
	    });
	  }

	  return result;
	}

	function getNegoJS(txt) {
	  if (txt.indexOf('협의') > -1) {
	    return "<em>&nbsp;</em>";
	  } else {
	    return txt;
	  }
	}

	function getRecuitPriceJS(info, class_name = "num") {
	  if (info == null) {
	    return "";
	  }

	  if (info.rc_price_type === "일급,주급,월급") {
	    return "<span>급여협의</span>";
	  } else if (info.rc_price_type === "월급") {
	    if (info.rc_month_price === "" || info.rc_month_price === undefined) {
	      return "<em class='"+class_name+"'></em>";
	    } else {
	      return "<em class='"+class_name+"'>"+numberFormat(info.rc_month_price)+"</em> 원";
	    }
	  } else if (info.rc_price_type === "일급") {
	    if (info.rc_day_price === "" || info.rc_day_price === undefined) {
				return "<em class='"+class_name+"'></em>";
	    } else {
	      return "<em class='"+class_name+"'>"+numberFormat(info.rc_day_price)+"</em> 원";
	    }
	  } else {
	    if (info.rc_day_price === "" || info.rc_day_price === undefined) {
				return "<em class='"+class_name+"'></em>";
	    } else {
	      return "<em class='"+class_name+"'>"+numberFormat(info.rc_day_price)+"</em> 원";
	    }
	  }

	  return "-";
	}

	function numberFormat(number) {
	  return number.toLocaleString('en');
	}

	function fHashSpaceJS(rc_work, arg) {
	  arg = arg.replace("<p class='tag'>#</p>", "");
	  arg = arg.replace("<p class='tag'># </p>", "");

	  arg = arg.replace(/#/g, "&nbsp;&nbsp;#");

	  arg = arg.replace("<p ", "<p style=\"color:#3a70db; background-color:transparent; padding-left:0;\" ");

	  let work_array = rc_work.split(",");

	  let formatted_work_array = work_array.map(function(work) {
	    work = work.trim();
	    if (arg.indexOf("#" + work + "<") === -1) {
	      return `<p style="color:#3a70db; background-color:transparent; padding-left:0;" class='tag'>#${work}</p>`;
	    }
	  }).filter(Boolean);  // Removes any undefined elements

	  arg += formatted_work_array.join("");

	  return arg;
	}

	function getHashTagJS(hash, max = 10) {
	  let result = "";
	  if (hash == null || hash === "") {
	    return "";
	  }

	  let arr_hash = hash.split(",");

	  arr_hash.forEach(function(txt, key) {
	    if (key < max) {
	      if (!txt.includes("#") && txt.trim() !== "") {
	        txt = "#" + txt.trim();
	      }
	      if (txt.trim() !== "") {
	        result += ` <p class='tag'>${txt}</p>`;
	      }
	    }
	  });

	  return result;
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
