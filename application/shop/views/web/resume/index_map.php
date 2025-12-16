<?php

function fAddrMake($addr){

  $addr = str_replace("경기 ", "", $addr);
  $addr = str_replace("강원 ", "", $addr);
  $addr = str_replace("충남 ", "", $addr);
  $addr = str_replace("충북 ", "", $addr);
  $addr = str_replace("경남 ", "", $addr);
  $addr = str_replace("경북 ", "", $addr);
  $addr = str_replace("전남 ", "", $addr);
  $addr = str_replace("전북 ", "", $addr);
  $addr = str_replace("제주 ", "", $addr);
  $addr = str_replace(",", "", $addr);

  $addrArray = explode(" ", $addr);
  if(count($addrArray) === 3) {
    $addr = $addrArray[0] . " " . $addrArray[2];
  }
  return $addr;

}

function fWorkColor($arg){
  $arg = str_replace("주간", "<font style='color:#3a70db;font-weight:600;'>주간</font>", $arg); //파랑
  $arg = str_replace("야간", "<font style='color:#F86048;font-weight:600;'>야간</font>", $arg); //빨강
  $arg = str_replace("2교대", "<font style='color:#1B9D7D;font-weight:600;'>2교대</font>", $arg); //민트
  $arg = str_replace("근무협의", "<font style='color:#333;font-weight:600;'>근무협의</font>", $arg); //검정
  $arg = str_replace("시간협의", "<font style='color:#333;font-weight:600;'>시간협의</font>", $arg); //검정
  $arg = str_replace("협의", "<font style='color:#333;font-weight:600;'>협의</font>", $arg); //검정
  return $arg;
}

 ?>
<div id="content" class="page page-map">
    <main class="page-content">
        <div class="map-wrap">
            <div class="container">
                <div class="result-wrap">
                  <?php function fShortAddrDong($longaddr) {
                            $parts = explode(' ', $longaddr);

                            $regions = ['서울', '경기도', '인천', '부산', '대구', '광주', '대전', '울산', '세종', '충청', '전라', '경상', '강원'];
                            $pattern = '/^(' . implode('|', $regions) . ')/';

                            if (preg_match($pattern, $parts[0])) { //in_array($parts[0], $regions)
                              if (isset($parts[2]) && mb_substr($parts[2], -1) === "구") {
                                return $parts[3] ? $parts[3] : "";
                              }else{
                                return $parts[2] ? $parts[2] : "";
                              }
                            } else {
                              if (isset($parts[1]) && mb_substr($parts[1], -1) === "구") {
                                return $parts[2] ? $parts[2] : "";
                              }else{
                                return $parts[1] ? $parts[1] : "";
                              }
                            }
                        }  ?>
                    <p class="current-area" style="">
                        <a id="btnAddr" href="javascript:void(0);" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel"><?=fShortAddrDong($addr_info["ad_addr"])?></a>
                        <a href="#advancedSearch" onclick="$('#advancedSearch').show(); $('#advancedSearch .btn-close-panel').closest('.layer-panel').addClass('on'); return false;" style="background: url(/resource/shop/mobile/img/ic_option.svg) no-repeat 100% 100%; top: 4px; right: 23px; display: inline-block; width: 20px; height: 20px; background-size: contain; position: absolute;" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text">상세검색 열기</span></a>
                    </p>
                    <!-- 목록 -->
                    <div class="list" style="margin-top: 20px;">
                        <div class="scroll-content">
                            <?php $list_idx = -1; ?>
                            <?php foreach($data["result_list"] as $row) :
                              $list_idx++;
                              ?>
							<!-- 목록아이템 -->
                            <div class="list-item list-item-<?=$list_idx?>">
                                <div class="item-info person">
                                    <div class="thumb-wrap">
                                        <!-- 추천라벨 -->
                                        <!--p class="recomm-badge"><span>추천</span></p-->
                                        <!-- 썸네일 -->
                                        <div class="thumb">
                                            <a href="/resume/resume_detail/<?=$row["ru_idx"]?>" target="_blank"><img data-src="<?=getResumeImg($row)?>" alt="" class="lozad"></a>
                                        </div>
                                        <!-- 평점 -->
                                        <p class="star-rating">
                                            <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                                            <span class="star" style="width:100%;"></span>
                                            <span class="sr-text">평점</span>
                                            <span class="sr-text">5</span>
                                        </p>
                                        <button type="button" data-bk_type="RESUME" data-bk_key="<?=$row["ru_idx"]?>" data-user_id="<?=$row["mm_id"]?>"  class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>" style="right: 0px; "><span class="sr-text">즐겨찾기 추가</span></button>
                                    </div>

                                    <div class="content-wrap">
                                        <div class="meta top" style="padding-left:0px;">
                                            <!-- 직종라벨 -->
                                            <?=getWorkingImg($row["ru_work"])?>
                                        </div>
                                        <div class="title-wrap">
                                            <!-- 이름 -->
                                             <p class="title"><strong><a href="/resume/resume_detail/<?=$row["ru_idx"]?>" target="_blank"><?=getAstrik($row["mm_name"])?></a></strong></p>
            					                        <!-- 추가사항 -->
            					                        <div class="meta">
            					                            <p class="national"><?=$row["mm_nation"]?></p>
            					                            <span>/</span>
            					                            <p class="age"><?=$row["ma_age"]?>세</p>
            					                            <!-- <span>/</span> -->
            					                        </div>
                                              <p class="area" style="text-align:right;"><?=fAddrMake($row["ad_addr"])?><?=(($row["distance"]=="" || $row["distance"]>500)?"":" / ".$row["distance"]."Km")?></p>
                                        </div>
                                        <!-- 조건 -->
                                        <p class="type"><?=($row["ru_price_type"])?>,<?=fWorkColor($row[ "ru_work_type"])?></p>
                                        <!-- <p class="area" style="width: fit-content; margin-bottom: 10px; margin-top: 5px;"><?=fAddrMake($row["ad_addr"])?><?=(($row["distance"]=="" || $row["distance"]>500)?"":" / ".$row["distance"]."Km")?></p> -->
                                        <ul class="working-day" style="position:absolute; top:59px;">
                                            <?=$row["ru_week"] ?>
                                            <?php //getWeekYoil($row["ru_week"])?>
                                        </ul>
                                        <!-- 해시태그 -->
                                        <div class="hashtag" style="margin-top: 36px;width:100%;">
                                            <?=getMyType($row["ru_my_type"])?>
                                        </div>
                                        <!-- 목록선택 (제한20개) -->
                                        <div class="selection">
                                            <label class="form-checkbox lg">
                                                <input type="checkbox" name="chk[]" value="<?=$row["ru_idx"]?>" onchange="selectRecruitList(2,this)" class="sr-text">
                                                <span class="label-red"></span>
                                                <span class="sr-text">지원하기 선택</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<?php endforeach;?>
                        </div>
                    </div>
                    <!-- //목록 -->

                    <div class="send-request">
                        <button id="btnReqeust"  type="button" aria-haspopup="dialog" class="btn-request btn-primary">
                            <span>제안보내기</span>
                            <!-- 선택된 목록개수 -->
                            <span class="counter">
                                (<em class="selected">0</em> /
                                <span class="total">2</span>)
                            </span>
                        </button>
                    </div>

                    <script>
                        /* 체크박스 선택시 지원하기 카운터 개수 변경 */
                        function updateCounter (num) {
                            if(!document.querySelector('.send-request')) return;
                            document.querySelector('.send-request .selected').innerHTML = num;
                        }

                        /* 체크박스 선택 제한 툴팁 표시 */
                        function selectRecruitList (limitNum, elm) {
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
                                if(!e.target.classList || (e.target.classlist && !e.target.classList.contains('.tooltip')) || e.target.closest('.js-map')) return;

                                tooltip.classList.remove('on');

                                tooltip.addEventListener('transitionend', function () {
                                    this.remove();
                                });

                            }, {once : true});
                        }

                        function fSearch(){
                          $('#searchkey').submit();
                          /*
                          var selectedCategories = ''; // 선택된 카테고리 값을 저장할 변수
                          $('input[name="category[]"]:checked').each(function(index) {
                              if (index > 0) {
                                  selectedCategories += '^';
                              }
                              selectedCategories += $(this).val();
                          });

                          var selectedWorktype = '';
                          $('input[name="work_type[]"]:checked').each(function(index) {
                              if (index > 0) {
                                  selectedWorktype += '^';
                              }
                              selectedWorktype += $(this).val();
                          });

                          var selectedWeek = '';
                          $('input[name="week[]"]:checked').each(function(index) {
                              if (index > 0) {
                                  selectedWeek += '^';
                              }
                              selectedWeek += $(this).val();
                          });

                          var selectedPricetype = '';
                          $('input[name="price_type[]"]:checked').each(function(index) {
                              if (index > 0) {
                                  selectedPricetype += '^';
                              }
                              selectedPricetype += $(this).val();
                          });

                          var selectedSex = '';
                          $('input[name="sex[]"]:checked').each(function(index) {
                              if (index > 0) {
                                  selectedSex += '^';
                              }
                              selectedSex += $(this).val();
                          });

                          location.href='./index_map?text='+$('keyword').val()+'&category='+selectedCategories+'&work_type='+selectedWorktype+'&week='+selectedWeek+'&price_type='+selectedPricetype+'&sex='+selectedSex+'&sage='+$('#sage').val()+'&eage='+$('#eage').val();
                          */
                        }
                    </script>

                    <!-- filter -->
                    <div id="advancedSearch" class="layer-panel" tabindex="-1" style="display:none;">
                        <div class="panel-container" style="width: calc(100% - 40px); margin: 0 auto;">
                            <div class="panel-header" style="position: relative;">
                                <h2 class="panel-title">인재정보 상세검색</h2>
                                <button type="button" class="btn-close-panel js-toggle-panel" style="position: absolute; top: -4px; right: 8px; width: 3.5625rem; height: 100%; border: 0; font-size: 2em; background-color: #fff;"><span class="sr-text">닫기</span>×</button>
                            </div>
                            <div class="panel-body">
                                <div class="panel-content">

                                    <div class="section advanced-search-form">
                                        <div class="container" style="padding: 0;">
                                          <form id="searchkey" name="searchkey" action="./index_map" method="post">
                                            <div class="form-row">
                                                <label>
                                                    <span class="form-label">검색어</span>
                                                    <input type="search" name="text" id="keyword" placeholder="검색어를 입력해 주세요" aria-required="true" required="" class="form-text" value="">
                                                </label>
                                            </div>
                                            <div class="form-row" style="display:none;">
                                                <p class="form-label">지역</p>

                                                <div class="current-area">
                                                    <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel">수원시 권선구 곡반정동</a>
                                                </div>

                                                <div class="js-range-slider">
                                                    <!--div class="min">
                                                        <input type="range" name="skm" min="0" max="50" step="5" data-unit="km" value="" data-minmax="0,50">
                                                        <div class="thumb"><output class="label"></output></div>
                                                    </div-->
                                                    <div class="max">
                                                        <!-- 최대값 -->
                                                        <input type="range" name="ekm" min="5" max="50" step="5" data-unit="km" value="30">
                                                        <div class="thumb" style="transform: translateX(188.889px);"><output class="label">30km</output></div>
                                                    </div>
                                                    <div class="fill" style="clip-path: inset(0px 44.4444% 0px 0px);"></div>
                                                </div>
                                            </div>

                                            <!-- 20220929 수정 -->
                                            <div class="form-row">
                                                <p class="form-label">카테고리 <em style="font-style: normal; color: #aaa; font-weight: 100; font-size: 0.9em;">다중선택가능</em>
                                                    <span class="option">
                                                        <label class="form-checkbox">
                                                            <input type="checkbox" name="category[]" value="" class="sr-text js-sel-all">
                                                            <span class="label">전체선택</span>
                                                        </label>
                                                    </span>
                                                </p>
                                                <div class="form-toggle-group col-4">
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="생산" data-img="/resource/uploads/admin/0d548978150efc63e46df52aa80385f4.png">
                                                        <span class="btn" style="padding: 0 15px;">생산</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="사무직" data-img="/resource/uploads/admin/0a74dd279aa909668a2ec4720b2fd3ec.png">
                                                        <span class="btn" style="padding: 0 15px;">사무직</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="운전배달" data-img="/resource/uploads/admin/e74fa3c1fe93835d3c6f7ad4ecaacd77.png">
                                                        <span class="btn" style="padding: 0 15px;">운전배달</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="물류" data-img="/resource/uploads/admin/96a9e5ff8f7b6e0db82dc62743893bbd.png">
                                                        <span class="btn" style="padding: 0 15px;">물류</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="식당" data-img="/resource/uploads/admin/2d83db66386a8e1ad404f2fd48e3f79c.png">
                                                        <span class="btn" style="padding: 0 15px;">식당</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="서비스" data-img="/resource/uploads/admin/7a710e0f902f2c03e5b358fa9c52ce42.png">
                                                        <span class="btn" style="padding: 0 15px;">서비스</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="매장관리" data-img="/resource/uploads/admin/5bbf09fb49560ef98d69320b5427bbc4.png">
                                                        <span class="btn" style="padding: 0 15px;">매장관리</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="건설" data-img="/resource/uploads/admin/68cbb430bc2e25903d6b14c03c73de31.png">
                                                        <span class="btn" style="padding: 0 15px;">건설</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="상담" data-img="/resource/uploads/admin/f275ec8d6882e94b9549fd1e868f3745.png">
                                                        <span class="btn" style="padding: 0 15px;">상담</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="디자인" data-img="/resource/uploads/admin/ba1480a5bdcb90a13e5fa456ead881b3.png">
                                                        <span class="btn" style="padding: 0 15px;">디자인</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="고객상담" data-img="/resource/uploads/admin/864e7c47fa39284c66de727a3e6e7b93.png">
                                                        <span class="btn" style="padding: 0 15px;">고객상담</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2 sm" style="width:89px;">
                                                        <input type="checkbox" name="category[]" data-select-limit="2" value="기타" data-img="/resource/uploads/admin/533534388eb9372c4e61021a648d7abf.png">
                                                        <span class="btn" style="padding: 0 15px;">기타</span>
                                                    </label>
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
                                                <p class="form-label">근무형태 <em style="font-style: normal; color: #aaa; font-weight: 100; font-size: 0.9em;">다중선택가능</em></p>
                                                <div class="form-toggle-group col-3">
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="work_type[]" value="주간">
                                                        <span class="btn">주간</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="work_type[]" value="야간">
                                                        <span class="btn">야간</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="work_type[]" value="2교대">
                                                        <span class="btn">2교대</span>
                                                    </label>
                                                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <p class="form-label">
                                                    근무요일 <em style="font-style: normal; color: #aaa; font-weight: 100; font-size: 0.9em;">다중선택가능</em>
                                                    <span class="option">
                                                        <label class="form-checkbox">
                                                            <input type="checkbox" name="workingDay" value="all" class="sr-text">
                                                            <span class="label">요일무관</span>
                                                        </label>
                                                    </span>
                                                </p>
                                                <div class="form-toggle-group align-justify">
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="week[]" value="월">
                                                        <span class="btn" style="min-width: 35px;">월</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="week[]" value="화">
                                                        <span class="btn" style="min-width: 35px;">화</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="week[]" value="수">
                                                        <span class="btn" style="min-width: 35px;">수</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="week[]" value="목">
                                                        <span class="btn" style="min-width: 35px;">목</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="week[]" value="금">
                                                        <span class="btn" style="min-width: 35px;">금</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="week[]" value="토">
                                                        <span class="btn" style="min-width: 35px;">토</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="week[]" value="일">
                                                        <span class="btn" style="min-width: 35px;">일</span>
                                                    </label>
                                                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <p class="form-label">급여 <em style="font-style: normal; color: #aaa; font-weight: 100; font-size: 0.9em;">다중선택가능</em></p>
                                                <div class="form-toggle-group col-3">
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="price_type[]" value="일급">
                                                        <span class="btn">일급</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="price_type[]" value="주급">
                                                        <span class="btn">주급</span>
                                                    </label>
                                                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="price_type[]" value="월급">
                                                        <span class="btn">월급</span>
                                                    </label>
                                                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <p class="form-label">성별 <em style="font-style: normal; color: #aaa; font-weight: 100; font-size: 0.9em;">다중선택가능</em></p>
                                                <div class="form-toggle-group col-2">
                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="sex[]" value="남자">
                                                        <span class="btn">남자</span>
                                                    </label>
                                                    <label class="form-toggle-btn2">
                                                        <input type="checkbox" name="sex[]" value="여자">
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
                                                        <select id="sage" name="sage" style="width:100%;height:35px;padding-left:12px; color: #ADAEB9; border: 1px solid #ADAEB9; border-radius: 20px;">
                                                        	<option value="">선택</option>
                                                        	                                    	<option value="20">20세</option>
                                                        	                                    	<option value="21">21세</option>
                                                        	                                    	<option value="22">22세</option>
                                                        	                                    	<option value="23">23세</option>
                                                        	                                    	<option value="24">24세</option>
                                                        	                                    	<option value="25">25세</option>
                                                        	                                    	<option value="26">26세</option>
                                                        	                                    	<option value="27">27세</option>
                                                        	                                    	<option value="28">28세</option>
                                                        	                                    	<option value="29">29세</option>
                                                        	                                    	<option value="30">30세</option>
                                                        	                                    	<option value="31">31세</option>
                                                        	                                    	<option value="32">32세</option>
                                                        	                                    	<option value="33">33세</option>
                                                        	                                    	<option value="34">34세</option>
                                                        	                                    	<option value="35">35세</option>
                                                        	                                    	<option value="36">36세</option>
                                                        	                                    	<option value="37">37세</option>
                                                        	                                    	<option value="38">38세</option>
                                                        	                                    	<option value="39">39세</option>
                                                        	                                    	<option value="40">40세</option>
                                                        	                                    	<option value="41">41세</option>
                                                        	                                    	<option value="42">42세</option>
                                                        	                                    	<option value="43">43세</option>
                                                        	                                    	<option value="44">44세</option>
                                                        	                                    	<option value="45">45세</option>
                                                        	                                    	<option value="46">46세</option>
                                                        	                                    	<option value="47">47세</option>
                                                        	                                    	<option value="48">48세</option>
                                                        	                                    	<option value="49">49세</option>
                                                        	                                    	<option value="50">50세</option>
                                                        	                                    	<option value="51">51세</option>
                                                        	                                    	<option value="52">52세</option>
                                                        	                                    	<option value="53">53세</option>
                                                        	                                    	<option value="54">54세</option>
                                                        	                                    	<option value="55">55세</option>
                                                        	                                    	<option value="56">56세</option>
                                                        	                                    	<option value="57">57세</option>
                                                        	                                    	<option value="58">58세</option>
                                                        	                                    	<option value="59">59세</option>
                                                        	                                    	<option value="60">60세</option>
                                                        	                                    	<option value="61">61세</option>
                                                        	                                    	<option value="62">62세</option>
                                                        	                                    	<option value="63">63세</option>
                                                        	                                    	<option value="64">64세</option>
                                                        	                                    	<option value="65">65세</option>
                                                        	                                    	<option value="66">66세</option>
                                                        	                                    	<option value="67">67세</option>
                                                        	                                    	<option value="68">68세</option>
                                                        	                                    	<option value="69">69세</option>
                                                        	                                    	<option value="70">70세</option>
                                                        	                                    	<option value="71">71세</option>
                                                        	                                    	<option value="72">72세</option>
                                                        	                                    	<option value="73">73세</option>
                                                        	                                    	<option value="74">74세</option>
                                                        	                                    	<option value="75">75세</option>
                                                        	                                    	<option value="76">76세</option>
                                                        	                                    	<option value="77">77세</option>
                                                        	                                    	<option value="78">78세</option>
                                                        	                                    	<option value="79">79세</option>
                                                        	                                    	<option value="80">80세</option>
                                                        	                                    </select>
                                                    </div>
                                                    <span class="entity">~</span>
                                                    <div class="col">
                                                        <select id="eage" name="eage" style="width:100%;height:35px;padding-left:12px; color: #ADAEB9; border: 1px solid #ADAEB9; border-radius: 20px;">
                                                        	<option value="">선택</option>
                                                        	                                    	<option value="20">20세</option>
                                                        	                                    	<option value="21">21세</option>
                                                        	                                    	<option value="22">22세</option>
                                                        	                                    	<option value="23">23세</option>
                                                        	                                    	<option value="24">24세</option>
                                                        	                                    	<option value="25">25세</option>
                                                        	                                    	<option value="26">26세</option>
                                                        	                                    	<option value="27">27세</option>
                                                        	                                    	<option value="28">28세</option>
                                                        	                                    	<option value="29">29세</option>
                                                        	                                    	<option value="30">30세</option>
                                                        	                                    	<option value="31">31세</option>
                                                        	                                    	<option value="32">32세</option>
                                                        	                                    	<option value="33">33세</option>
                                                        	                                    	<option value="34">34세</option>
                                                        	                                    	<option value="35">35세</option>
                                                        	                                    	<option value="36">36세</option>
                                                        	                                    	<option value="37">37세</option>
                                                        	                                    	<option value="38">38세</option>
                                                        	                                    	<option value="39">39세</option>
                                                        	                                    	<option value="40">40세</option>
                                                        	                                    	<option value="41">41세</option>
                                                        	                                    	<option value="42">42세</option>
                                                        	                                    	<option value="43">43세</option>
                                                        	                                    	<option value="44">44세</option>
                                                        	                                    	<option value="45">45세</option>
                                                        	                                    	<option value="46">46세</option>
                                                        	                                    	<option value="47">47세</option>
                                                        	                                    	<option value="48">48세</option>
                                                        	                                    	<option value="49">49세</option>
                                                        	                                    	<option value="50">50세</option>
                                                        	                                    	<option value="51">51세</option>
                                                        	                                    	<option value="52">52세</option>
                                                        	                                    	<option value="53">53세</option>
                                                        	                                    	<option value="54">54세</option>
                                                        	                                    	<option value="55">55세</option>
                                                        	                                    	<option value="56">56세</option>
                                                        	                                    	<option value="57">57세</option>
                                                        	                                    	<option value="58">58세</option>
                                                        	                                    	<option value="59">59세</option>
                                                        	                                    	<option value="60">60세</option>
                                                        	                                    	<option value="61">61세</option>
                                                        	                                    	<option value="62">62세</option>
                                                        	                                    	<option value="63">63세</option>
                                                        	                                    	<option value="64">64세</option>
                                                        	                                    	<option value="65">65세</option>
                                                        	                                    	<option value="66">66세</option>
                                                        	                                    	<option value="67">67세</option>
                                                        	                                    	<option value="68">68세</option>
                                                        	                                    	<option value="69">69세</option>
                                                        	                                    	<option value="70">70세</option>
                                                        	                                    	<option value="71">71세</option>
                                                        	                                    	<option value="72">72세</option>
                                                        	                                    	<option value="73">73세</option>
                                                        	                                    	<option value="74">74세</option>
                                                        	                                    	<option value="75">75세</option>
                                                        	                                    	<option value="76">76세</option>
                                                        	                                    	<option value="77">77세</option>
                                                        	                                    	<option value="78">78세</option>
                                                        	                                    	<option value="79">79세</option>
                                                        	                                    	<option value="80">80세</option>
                                                        	                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                          </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="sticky-bottom" style="width: 100%; display: block;">
                                    <button id="btnSearch" style="width: 100%;" type="button" class="btn btn-primary" onclick="fSearch();">검색하기</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- //filter -->

                </div>

                <!-- 지도 -->
                <div class="js-map-wrap">
                    <div id="jsMap" class="js-map"></div>
                </div>

                <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>&libraries=clusterer"></script>

                <script>
                var nowOverIdx = null;
                    //테스트 좌표 데이터(인천 연수구 xx동)
                   	var data = [];

		            <?php foreach($data["result_list"] as $row) : ?>
		            data.push({coords: { lat: '<?=$row["ad_gps_y"]?>', lng: '<?=$row["ad_gps_x"]?>'}});
		            <?php endforeach; ?>

                    function getOffsetX () {
                        var list = document.querySelector('.result-wrap');
                        var offsetX = list.offsetWidth + list.offsetLeft;
                        var wrapper = document.querySelector('.js-map-wrap');

                        wrapper.style.left = offsetX + 'px';
                        wrapper.classList.add('initialized');
                    }

                    //지도 및 슬라이더 생성
                    window.addEventListener('DOMContentLoaded', function () {
                        getOffsetX();

                        kakao.maps.load(function () {
                            if (!data) return;

                            var list = document.querySelector('.result-wrap');
                            var markers = new Array();
                            var activeMarker = null;
                            var clusterer = null;
                            var activeCluster = new Array();

                            var markerImg = new kakao.maps.MarkerImage(
                                '/resource/shop/web/img/ic_loc_pin.svg',
                                new kakao.maps.Size(36, 47),
                                { offset: new kakao.maps.Point(16, 42)}
                            );

                            var markerHoverImg = new kakao.maps.MarkerImage(
                                '/resource/shop/web/img/ic_loc_pin_on.svg',
                                new kakao.maps.Size(48, 62),
                                { offset: new kakao.maps.Point(22, 56) }
                            );

                            var nowposlat = <?=$ad_gps_y?>;
                            var nowposlng = <?=$ad_gps_x?>;
                            var map = new kakao.maps.Map(document.getElementById('jsMap'), {
                                //center : new kakao.maps.LatLng(data[0].coords.lat, data[0].coords.lng),
                                center : new kakao.maps.LatLng(nowposlat, nowposlng),
                                level : 9,//지도레벨(시도구 영역을 클러스터처리하는 최대최소레벨)
                                minLevel: 6,
                                maxLevel: 14,
                            });
                            // 마커가 표시될 위치입니다
                            var markerPosition  = new kakao.maps.LatLng(nowposlat, nowposlng);

                            // 마커를 생성합니다
                            var marker = new kakao.maps.Marker({
                                position: markerPosition
                            });
                            // 마커가 지도 위에 표시되도록 설정합니다
                            marker.setMap(map);

                            function selectMarker(num,clickyn) {
                                if(!markers[num]) return;

                                var items = list.querySelectorAll('.list-item');

                                markers[num].setZIndex(2);
                                markers[num].setImage(markerHoverImg);
                                activeMarker = markers[num];

                                $('.list-item').css('background-color','#fff');
                                if (items.length > 0) {

                                  if(clickyn){
                                    //items[num].classList.add('on');
                                    //$(items[num]).css('background-color','#ededed');
                                    $('.list-item-'+num).addClass('on');
                                    $('.list-item-'+num).css('background-color','#ededed');

                                    // num 번째 list-item을 맨 위로 이동, 이미 맨 위에 있는지 확인
                                    //console.log(num);
                                    if (!$('.list-item-'+num).is(':first-child')) {
                                      $('.list-item-'+num).prependTo($('.list-item-'+num).parent());
                                    }

                                    // var rect = $(items[num])[0].getBoundingClientRect();
                                    // var isVisible = (
                                    //     rect.top >= 0 &&
                                    //     rect.left >= 0 &&
                                    //     rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                                    //     rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                                    // );
                                    //
                                    // if (!isVisible) {
                                    //     $(items[num])[0].scrollIntoView({ behavior: 'smooth', block: 'center' }); // 화면에 없으면 스크롤
                                    // }

                                  }

                                }
                            }

                            function unSelectMarker() {
                                if(!activeMarker) return;

                                var checkbox = list.querySelector('.on input[type="checkbox"]');

                                if (!checkbox || (checkbox && !checkbox.checked)) {
                                    activeMarker.setZIndex(0);
                                    activeMarker.setImage(markerImg);
                                }

                                if(list.querySelector('.on')) {
                                    list.querySelector('.on').classList.remove('on');
                                    $('.list-item').css('background-color','#fff');
                                }

                                activeMarker = null;
                            }

                            function getItemIdx(el) {
                                var index = null;

                                list.querySelectorAll('.list-item').forEach(function (item, idx) {
                                    if (item === el) index = idx;
                                });

                                return index;
                            }

                            function clusterRedraw () {
                                setTimeout(function () {
                                    clusterer.redraw();
                                }, 100);
                            }

                            //데이터로 마커생성
                            data.forEach(function(d) {
                                markers.push(new kakao.maps.Marker({
                                    map: map,
                                    position : new kakao.maps.LatLng(d.coords.lat, d.coords.lng),
                                    image: markerImg
                                }));
                            });

                            map.panBy(1,1);

                            /* 마커 마우스오버 이벤트 */
                            markers.forEach(function (marker, i) {
                                kakao.maps.event.addListener(marker, 'mouseover', function() {
                                  //console.log('mouseover');
                                  //console.log(marker);
                                  nowOverIdx = i;
                                  //alert(nowOverIdx);
                                    selectMarker(i,false);
                                });

                                kakao.maps.event.addListener(marker, 'mouseout', function() {
                                  //console.log('mouseout');
                                  //console.log(marker);
                                    unSelectMarker();
                                });

                                kakao.maps.event.addListener(marker, 'click', function() {
                                  //console.log('click');
                                  //console.log(marker);
                                    var checkbox;

                                    selectMarker(i,true);

                                    checkbox = list.querySelector('.on input[type="checkbox"]');

                                    if (checkbox) {
                                        checkbox.checked = !checkbox.checked;
                                        checkbox.onchange();
                                    }
                                });
                            });

                            //클러스터 생성
                            /*
                            clusterer = new kakao.maps.MarkerClusterer({
                                map: map,
                                markers: markers,
                                averageCenter: true,
                                minLevel: 7,
                                minClusterSize: 1,
                                styles: [{
                                    width : '48px', height : '48px',
                                    background: 'url(/resource/shop/web/img/map_cluster_bg1.svg) no-repeat 0 0',
                                    backgroundsize: 'contain',
                                    color: '#fff',
                                    textAlign: 'center',
                                    lineHeight: '48px'
                                }]
                            });
                            */

                            /* active 마커가 있을때 클러스터 스타일 클래스 추가 */
                            kakao.maps.event.addListener(clusterer, 'clustered', function(clusters) {
                                var markerContent;

                                for(var i = 0; i < clusters.length; i++){
                                    markerContent = clusters[i].getClusterMarker().getContent();

                                    markerContent.classList.remove('has-active');

                                    clusters[i].getMarkers().forEach(function (marker,ixd) {
                                        if (marker.getImage() == markerHoverImg) {
                                            markerContent.classList.add('has-active');
                                        }
                                    });
                                }
                            });

                            kakao.maps.event.addListener(clusterer, 'clusterclick', function(cluster) {

                              //console.log(cluster);

                              // 클러스터의 정보를 출력
                              //console.log('클릭된 클러스터:', cluster);

                              //console.log('clusterer:', clusterer);
                              // 클릭된 클러스터가 몇 번째 객체인지 찾기 위해 clusterer의 클러스터 배열을 순회합니다.
                              //var clusters = clusterer.getClusters();
                              //var index = clusters.indexOf(cluster);
                              //console.log('click index:'+index);

                              // 인덱스를 콘솔에 출력합니다.
                              //if (index !== -1) {
                              //    console.log('Clicked cluster index: ' + index);
                              //} else {
                              //    console.log('Cluster not found in the clusterer.');
                              //}

                              // 클러스터의 중심 좌표
                              var center = cluster.getCenter();
                              console.log('클러스터 중심 좌표:', center);

                              // 클러스터에 포함된 마커들
                              var markers = cluster.getMarkers();
                              console.log('클러스터에 포함된 마커들:', markers);

                              // 클러스터의 크기 (포함된 마커의 수)
                              var size = cluster.getSize();
                              //console.log('클러스터 크기:', size);

                                // // 현재 지도 레벨에서 1레벨 확대한 레벨
                                // var level = map.getLevel()-1;
                                //
                                // // 지도를 클릭된 클러스터의 마커의 위치를 기준으로 확대합니다
                                // map.setLevel(level, {anchor: cluster.getCenter()});
                            });

                            /* 목록 마우스오버 이벤트 */
                            list.addEventListener('mouseover', function (e) {
                                var target = e.target;
                                var idx = -1;

                                if (!target.closest('.list-item')) return;
                                target = target.closest('.list-item');
                                idx = getItemIdx(target);

                                //console.log('over '+idx);
                                selectMarker(idx,false);
                                clusterRedraw();
                                console.log(markers[idx].getPosition());
                                if(markers[idx].getPosition().La > 0 && markers[idx].getPosition().Ma > 0){
                                  map.panTo(markers[idx].getPosition());

                                }
                            });

                            list.addEventListener('mouseout', function (e) {
                                unSelectMarker();
                                clusterRedraw();
                            });

                            list.addEventListener('change', function (e) {
                                var target = e.target;
                                var idx = -1;

                                if (!target.closest('.list-item')) return;
                                target = target.closest('.list-item');
                                idx = getItemIdx(target);

                                if (target.querySelector('input[type="checkbox"]').checked) {
                                    selectMarker(idx,false);
                                    //test map.panTo(markers[idx].getPosition());
                                } else {
                                    unSelectMarker();
                                }

                                clusterRedraw();
                            });

                        });
                    });

                    window.addEventListener('resize', getOffsetX);
                </script>

            </div>
        </div>
    </main><!-- //page-content -->
</div><!-- //page -->

<!-- 제안 리스트 -->
<div id="selectPanel" class="layer-panel card-type" tabindex="-1">
    <div class="panel-container" style="max-width:500px;margin:0 auto;">
        <div class="panel-header">
            <!-- 약관제목 -->
            <h2 class="panel-title">진행중인 채용공고</h2>
            <button type="button" aria-controls="selectPanel" class="btn-close-panel js-toggle-panel"><span class="sr-text">닫기</span></button>
        </div>
        <div class="panel-body">
            <div class="panel-content">
                <div class="terms-wrap">
                    <div class="post-list">
		                <ul class="list">
		                	<?php foreach($recruit_list as $row) : ?>
		                    <li>
		                        <div class="selectable-item" style="position:relative;">
		                            <label class="form-checkbox"style="position:absolute;top:40px;">
		                                <input type="radio" name="chk_request[]" value="<?=$row["rc_idx"]?>" data-rc_name="<?=$row["rc_name"]?>" >
		                                <span class="label"></span>
		                                <span class="sr-text">선택</span>
		                            </label>
		                            <p style="padding:20px;padding-left:30px;">
		                                <strong><?=$row["rc_name"]?></strong><br/>
		                                <span class="date"><?=get_yyyymmdd($row["rc_sdate"])?> ~ <?=get_yyyymmdd($row["rc_edate"])?></span>
		                            </p>
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
            <div class="sticky-bottom" style="width:100%;">
                <button type="button" style="width:100%;" aria-controls="terms2Panel" class="btnProcess btn btn-primary js-toggle-panel">제안 보내기</button>
            </div>
        </div>
    </div>
</div>
<!-- //제안 리스트 -->

<script>
	var check_user_type = "<?=$this->user->getUserType();?>";
	var mq_idxs = new Array();
	$("#btnReqeust").click(function(){
        //로그인 여부 확인
        if($("#is_login").val()!="Y"){
            commonLogin();
            return;
        }

		if(check_user_type!="COMP"){
			commonAlert("일반 회원은 제안 할 수 없습니다.",function(){});
			return;
		}

		for(var icnt =0;icnt < $("[name='chk[]']:checked").length;icnt++){
			mq_idxs.push($("[name='chk[]']:checked").eq(icnt).val());
		}

		if(mq_idxs.length==0){
			commonAlert("제안하실 공고를 선택해주세요.",function(){});
			return;
		}

		$("[name='chk_request[]']").prop("checked",false);
        $("#selectPanel").addClass("on");
	});

	$(".btnProcess").click(function(){
		if($("[name='chk_request[]']:checked").length==0){
			commonAlert("제안하실 공고를 선택해주세요.",function(){});
			return;
		}

		var rc_idx = $("[name='chk_request[]']:checked").eq(0).val();

		var params = {};
	    params["mq_idxs"] = mq_idxs;
	    params["rc_idx"] = rc_idx;
	    params["mq_type"] = "RESUME";
	    comAjax("/common_proc/member_request_check",params,function(ret_1){
			if(ret_1.code=="00"){
				var add_text = (ret_1.data.point_yn=="Y" ? "<br/>" + ret_1.data.point_price + "P 포인트가 차감됩니다." : "");
				openPopup('confirm', {
		            content: '<em>해당공고</em><br> 로 제안을 보내시겠습니까?' + add_text,
		            secondCallback: function () {

					    comAjax("/common_proc/member_request",params,function(ret){
					    	if(ret.code=="00"){
					        	commonAlert("선택한 채용공고로 제안하였습니다.",function(){
					        		location.reload();
					        	});
					        }else{
					            commonAlert(ret.msg,function(){
					            	location.reload();
					            });
					            return;
					        }
					    });
		            }
		        });
		    }else{
		  		commonAlert(ret_1.msg,function(){
		  			location.reload();
		  		});
		  	}
		});
	});
</script>


<script>
$(document).ready(function(){
    var ad_addr = "<?=$addr_info["ad_addr"]?>";

    if(ad_addr.trim()==""){
        $("#changeArea").addClass("on");
    }

    $('#sage').change(function() {
        updateAgeOptions($(this).val());
        //$('#eage').val();
    });

});

$("#btnAddr").click(function(){
	$("#changeArea").addClass("on");
});

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

function updateAgeOptions(minAge){

    $('#eage').empty();

    // 기본 선택 옵션 추가
    $('#eage').append('<option value="">선택</option>');

    // 주어진 값 이상의 나이를 옵션으로 추가
    for (var age = minAge; age <= 80; age++) {
       $('#eage').append('<option value="' + age + '">' + age + '세</option>');
    }
}
</script>
<?php include_once (get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>
