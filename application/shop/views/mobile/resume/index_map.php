<div class="backdrop-loader" hidden>
    <div class="loader show"></div>
</div>

<div class="page page-map">
    <div class="top-bar is-transparency">
        <a href="#" class="btn-back"><span class="sr-text">이전으로</span></a>
        <?php function fShortAddrDong($longaddr){
						$parts = explode(' ', $longaddr);
    				return end($parts);//매지막 배열요소
				} ?>
        <h1 class="current-area">
            <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel"><?=fShortAddrDong($addr_info["ad_addr"])?></a>
        </h1>
        <a href="#advancedSearch" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text">상세검색 열기</span></a>
    </div><!-- //top-bar -->

    <div class="page-content">

        <!-- 목록 슬라이더 -->
        <div class="card-slider">
            <div class="slider-wrap keen-slider">
                <?php foreach($data["result_list"] as $row) : ?>
                <!-- 슬라이드 -->
                <div class="keen-slider__slide" id="gps_<?=$row["ad_gps_x"]?>_<?=$row["ad_gps_y"]?><?=($row["ad_gps_x"]=="0.0000000000"?"_".$row["ru_idx"]:"")?>">
                    <!-- 내용 -->
                    <div class="item-info recruit">
                        <div class="thumb-wrap">
                            <!-- 추천라벨 -->
                            <p class="recomm-badge"><span>추천</span></p>
                            <button type="button" data-bk_type="RESUME" data-bk_key="<?=$row["ru_idx"]?>" data-user_id="<?=$row["mm_id"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 추가</span></button>
                            <!-- 썸네일 -->
                            <div class="thumb">
                                <a href="/resume/resume_detail/<?=$row["ru_idx"]?>"> <img src="<?=getResumeImg($row)?>" onError="this.src='/resource/shop/mobile/img/common/noimage3.png'"  alt=""></a>
                            </div>

                            <p class="star-rating">
                                <!-- 평점 아이콘 : (width: 점수 / 5 * 100) -->
                                <span class="star" style="width:100%;"></span>
                                <span class="sr-text">평점</span>
                                <span class="sr-text">5</span>
                            </p>
                        </div>
                        <div class="content-wrap">
                            <div class="meta top">
                                <!-- 직종라벨 -->
                                <p class="category"><?=($row["ru_work_img"])?></p>
                                <!-- 조건 -->
                                <p class="condition"><?=($row["ru_work_type"])?></p>
                            </div>
                            <div class="title-wrap">
                                <!-- 이름 -->
                                <p class="title"><strong><a href="/resume/resume_detail/<?=$row["ru_idx"]?>"><?=$row["mm_name"]?></a></strong></p>
                                <!-- 추가사항 -->
                                <div class="meta">
                                    <p><?=$row["mm_nation"]?></p>
	                                <span>/</span>
	                                <p><?=$row["ma_age"]?></p>
                                </div>
                            </div>
                             <ul class="working-day">
                             	<?=$row["ru_week"]?>

                            </ul>
                            <!-- 해시태그 -->
                            <div class="hashtag" style="margin-top: 15px;">
                            	<?php if($row["ru_my_type"]=="") echo "<p class=\"tag\" style=\"width:100%; text-align:right;\">&nbsp;</p>";
                                    else echo str_replace("right","left",str_replace("100%","60%",$row["ru_my_type"])); ?>
                            </div>
                            <button type="button" aria-haspopup="dialog" data-ru_idx="<?=$row["ru_idx"]?>" class="btnRequest btn btn-primary" style="margin-top: -1.3rem;">제안하기</button>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
        </div>
        <!-- //목록 슬라이더 -->

        <!-- 지도 -->
        <div class="map">
            <div id="jsMap" class="js-map"></div>
        </div>

        <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>&libraries=clusterer"></script>

        <script>
            //테스트 좌표 데이터(인천 연수구 xx동)
            var data = [];
            <?php foreach($data["result_list"] as $row) : ?>
            <?php   if($row["ad_gps_y"] != "0.0000000000"){ ?>
            data.push({coords: { lat: '<?=$row["ad_gps_y"]?>', lng: '<?=$row["ad_gps_x"]?>'}});
            <?php   } ?>
            <?php endforeach; ?>

            //20221003 수정
            //지도 및 슬라이더 생성
            <?php if(count($data["result_list"]) > 0) : ?>
            window.addEventListener('DOMContentLoaded', function () {
                kakao.maps.load(function () {
                    if (!data) return;

                    var cardSlider = null;
                    var markers = new Array();
                    var activeMarker = null;
                    var clusterer = null;

                    var markerImg = new kakao.maps.MarkerImage(
                        '/resource/shop/mobile/img/ic_loc_pin.svg',
                        new kakao.maps.Size(36, 47),
                        { offset: new kakao.maps.Point(16, 42)}
                    );

                    var markerHoverImg = new kakao.maps.MarkerImage(
                        '/resource/shop/mobile/img/ic_loc_pin_on.svg',
                        new kakao.maps.Size(48, 62),
                        { offset: new kakao.maps.Point(22, 56) }
                    );

                    <?php if($ad_gps_y=="0.0000000000"){ $ad_gps_y = "37.566742"; }
                          if($ad_gps_x=="0.0000000000"){ $ad_gps_x = "126.978182"; } ?>

                    var map = new kakao.maps.Map(document.getElementById('jsMap'), {
                        center : new kakao.maps.LatLng('<?=$ad_gps_y?>', '<?=$ad_gps_x?>'),
                        level : 9,//지도레벨(시도구 영역을 클러스터처리하는 최대최소레벨)
                        minLevel: 7,//20220929 수정
                        maxLevel: 11,
                    });

                    function selectMarker(num) {
                        markers[num].setZIndex(2);
                        markers[num].setImage(markerHoverImg);
                        activeMarker = markers[num];
                    }

                    // 마커의 위도, 경도 값들을 저장할 배열
                    var markerPositions = [];

                    var pinIdx = 0;
                    //데이터로 마커생성
                    data.forEach(function(d) {
                        pinIdx++;
                        //console.log(d);
                        //console.log(d.coords.lat);
                        //console.log(d.coords.lng);

                        //var latLng = {
                        //  lat: data.n.La,
                        //  lng: data.n.Ma
                        //};
                        //console.log(d.n.La);
                        //console.log(d.n.Ma);
                        //markerPositions.push(latLng);

                        var markerImg2 = new kakao.maps.MarkerImage(
                            '/resource/shop/mobile/img/ic_loc_pin.svg?lat='+d.coords.lat+'&lng='+d.coords.lng,
                            new kakao.maps.Size(36, 47),
                            { offset: new kakao.maps.Point(16, 42)}
                        );

                        var marker = new kakao.maps.Marker({
                            map: map,
                            position : new kakao.maps.LatLng(d.coords.lat, d.coords.lng),
                            image: markerImg2,
                            clickable: true
                        });
                        // markers.push(new kakao.maps.Marker({
                        //     map: map,
                        //     position : new kakao.maps.LatLng(d.coords.lat, d.coords.lng),
                        //     image: markerImg,
                        //     clickable: true
                        // }));
                        markers.push(marker);

                        kakao.maps.event.addListener(marker, 'click', function() {
                            // 마커 위에 인포윈도우를 표시합니다
                            infowindow.open(map, marker);
                            //alert('click');
                        });

                    });

                    markers.forEach(function(data) {
                        //console.log('마커 #' +data);
                        //console.log(data);

                        var url = data.T.ok;
                        var queryString = url.split('?')[1]; // "?" 뒤의 쿼리스트링 부분을 가져옴
                        var latOrg;
                        var lngOrg;

                        if (queryString) {
                            var params = new URLSearchParams(queryString);
                            latOrg = params.get('lat');
                            lngOrg = params.get('lng');

                            //console.log('위도 (lat): ' + latOrg);
                            //console.log('경도 (lng): ' + lngOrg);
                        } else {
                            //console.log('쿼리스트링이 존재하지 않습니다.');
                        }

                        // 마커의 위도와 경도 값을 배열에 저장
                        var latLng = {
                          lat: data.n.La,
                          lng: data.n.Ma,
                          latOrg: latOrg,
                          lngOrg: lngOrg
                        };
                        //console.log(data.n.La);
                        //console.log(data.n.Ma);
                        markerPositions.push(latLng);

                    });

                    selectMarker(0);
                    map.panBy(1,1);

                    /* 마커 클릭 이벤트 (슬라이드 이동) */
                    //클러스터 생성
                    clusterer = new kakao.maps.MarkerClusterer({
                        map: map,
                        markers: markers,
                        averageCenter: true,
                        minLevel: 7,
                        minClusterSize: 1,
                        styles: [{
                            width : '48px', height : '48px',
                            background: 'url(/resource/shop/mobile/img/map_cluster_bg1.svg) no-repeat 0 0',
                            backgroundsize: 'contain',
                            color: '#fff',
                            textAlign: 'center',
                            lineHeight: '48px'
                        }]
                    });

                    clusterer.addMarkers(markers);//추가

                    /* active 마커가 있을때 클러스터 스타일 클래스 추가 */
                    kakao.maps.event.addListener(clusterer, 'clustered', function(clusters) {
                        for(var i = 0; i < clusters.length; i++){
                            var markerContent = clusters[i].getClusterMarker().getContent();
                            markerContent.classList.remove('has-active');
                            clusters[i].getMarkers().forEach(function (marker) {
                                if (marker === activeMarker) {
                                    markerContent.classList.add('has-active');
                                }
                            });
                        }
                    });

                    // 마커 클러스터러에 클릭이벤트를 등록합니다
                    // 마커 클러스터러를 생성할 때 disableClickZoom을 true로 설정하지 않은 경우
                    // 이벤트 헨들러로 cluster 객체가 넘어오지 않을 수도 있습니다
                    kakao.maps.event.addListener(clusterer, 'clusterclick', function(cluster) {

                        //console.log('클릭된 클러스터:', cluster.getMarkers());
                        var markers = cluster.getMarkers();
                        markers.forEach(function(marker, index) {
                            //console.log('클러스터에 포함된 마커 ' + (index + 1) + ':', marker.getPosition());
                        });

                        // 현재 지도 레벨에서 1레벨 확대한 레벨
                        var level = map.getLevel()-1;

                        // 지도를 클릭된 클러스터의 마커의 위치를 기준으로 확대합니다
                        map.setLevel(level, {anchor: cluster.getCenter()});

                        //console.log(cluster);
                        //console.log(cluster._clusterer._clusters[0]._center.La);
                        //console.log(cluster._clusterer._clusters[0]._center.Ma);

                        var la = cluster._clusterer._clusters[0]._center.La;
                        var ma = cluster._clusterer._clusters[0]._center.Ma;

                        // 배열에서 해당 위치와 일치하는 인덱스를 찾기
                        var matchingIndex = markerPositions.findIndex(function(position) {

                            //console.log(position.lat.toFixed(4));
                            //console.log(la.toFixed(4));
                            //console.log(position.lng.toFixed(4));
                            //console.log(ma.toFixed(4));

                            return position.lat.toFixed(4) === la.toFixed(4) && position.lng.toFixed(4) === ma.toFixed(4);
                        });

                        // 일치하는 인덱스를 콘솔에 출력
                        if (matchingIndex !== -1) {
                            //console.log('일치하는 마커는 배열의 ' + (matchingIndex + 1) + ' 번째에 있습니다.');
                            var matchedPosition = markerPositions[matchingIndex];
                            //console.log('일치하는 마커의 latOrg: ' + matchedPosition.latOrg + ', lngOrg: ' + matchedPosition.lngOrg);

                            $('.keen-slider__slide').removeClass('active');
                            //console.log('#gps_'+matchedPosition.lngOrg+'_'+matchedPosition.latOrg);
                            $('#gps_'+matchedPosition.lngOrg+'_'+matchedPosition.latOrg).addClass('active');
                            //scrollToDiv('gps_'+matchedPosition.lngOrg+'_'+matchedPosition.latOrg);
                            //cardSlider.moveToSlide(3);
                            var targetDiv = $('#gps_'+matchedPosition.lngOrg+'_'+matchedPosition.latOrg);
                            var index = $('.slider-wrap > div.keen-slider__slide').index(targetDiv);
                            //console.log('index:'+index);
                            //cardSlider.moveToIdx(1);

                            moveToSlideById(cardSlider, 'gps_'+matchedPosition.lngOrg+'_'+matchedPosition.latOrg) ;

                        } else {
                            console.log('일치하는 마커가 없습니다.');
                        }

                    });

                    //카드 슬라이더 생성
                    if (typeof KeenSlider !== 'undefined') {
                        cardSlider = new KeenSlider('.card-slider .slider-wrap', {
                                loop: (data.length == 1 ? false : true),
                                slides: {
                                    origin: 'center',
                                    perView: 1.2,
                                    spacing: 10,
                                },
                                slideChanged: function (slider) {
                                    /* 카드 슬라이더 이동시 이벤트 (마커 이미지 변경) */
                                    if(activeMarker) {
                                        activeMarker.setImage(markerImg);
                                        activeMarker.setZIndex(0);
                                    }

                                    slider.slides.forEach(function (slide, idx) {
                                        if(slide.classList.contains('active')) {
                                            selectMarker(idx);
                                            map.panTo(markers[idx].getPosition());//20220929 수정
                                        }
                                    });
                                },
                                created: function (slider) {
                                    slider.container.parentNode.classList.add('initialized');
                                },
                            }, [ addActiveClass,]
                        );
                    }

                });
            });
            <?php else : ?>
            	var container = document.getElementById('jsMap'); //지도를 담을 영역의 DOM 레퍼런스
				var options = { //지도를 생성할 때 필요한 기본 옵션
					center: new kakao.maps.LatLng(<?=$addr_info["ad_gps_y"]?>, <?=$addr_info["ad_gps_x"]?>), //지도의 중심좌표.
					level : 7,
                    minLevel: 2,
                    maxLevel: 11,
				};

				var map = new kakao.maps.Map(container, options);
            <?php endif; ?>

            function scrollToDiv(targetDivId) {

                alert('1');

                // 슬라이더 전체를 감싸는 div 요소 선택 (여기서는 예시로 #sliderWrap 사용)
                var sliderWrap = $('.slider-wrap');

                // 이동하려는 타겟 div 요소
                var targetDiv = $('#' + targetDivId);

                if (targetDiv.length && sliderWrap.length) {
                    // 타겟 div의 가로 위치 값을 계산
                    var targetPosition = targetDiv.position().left + sliderWrap.scrollLeft();

                    // 슬라이더 래퍼를 가로로 스크롤 이동
                    sliderWrap.animate({
                        scrollLeft: targetPosition
                    }, 500); // 500ms 동안 애니메이션 효과로 이동
                }
            }

            function moveToSlideById(slider, id) {
              const slides = slider.slides;
              //console.log(slides);
              //console.log(id);
              const targetIndex = Array.from(slides).findIndex(slide => slide.id === id);

              if (targetIndex !== -1) {
                slider.moveToIdx(targetIndex);
              } else {
                console.log('Slide with ID ' + id + ' not found');
              }
            }

        </script>
    </div><!-- //page-content -->
</div>

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

<script>
	var check_user_type = "<?=$this->user->getUserType();?>";
	var ru_idx = 0;
	$(".btnRequest").click(function(){
        //로그인 여부 확인
        if($("#is_login").val()!="Y"){
            commonLogin();
            return;
        }

        if(check_user_type!="COMP"){
			commonAlert("일반 회원은 제안 할 수 없습니다.",function(){});
			return;
		}

       	$("[name='chk_request[]']").prop("checked",false);
        $("#selectPanel").addClass("on");

        ru_idx = $(this).attr("data-ru_idx");
	});

	$(".btnProcess").click(function(){
        if($("[name='chk_request[]']:checked").length==0){
			commonAlert("제안하실 공고를 선택해주세요.",function(){});
			return;
		}

		var rc_idx = $("[name='chk_request[]']:checked").eq(0).val();
		var mq_idxs = new Array();
		mq_idxs.push(ru_idx);
		openPopup('confirm', {
            content: '<em>선택한 </em>공고를<br> 제안하시겠습니까?',
            secondCallback: function () {
              	var params = {};
			    params["mq_idxs"] = mq_idxs;
			    params["rc_idx"] = rc_idx;
			    params["mq_type"] = "RESUME";
			    comAjax("/common_proc/member_request",params,function(ret){
			    	if(ret.code=="00"){
			        	commonAlert("선택한 채용공고로 제안하였습니다.",function(){
			        		$("#selectPanel").removeClass("on");
			        	});
			        }else{
			            commonAlert(ret.msg,function(){
			            	$("#selectPanel").removeClass("on");
			            });
			            return;
			        }
			    });
            }
        });
	});
</script>

<script>
var list_id = "#list";

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

    //console.log(params);
    comAjax("/resume_proc/search_save_params",params,function(ret){
        if(ret.code=="00"){
            location.reload();
        }else{

        }
    });
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


function logSvgInCluster(cluster) {
    // 객체를 재귀적으로 탐색하는 함수
    function findSvg(obj) {
        for (var key in obj) {
            if (obj.hasOwnProperty(key)) {
                var value = obj[key];

                // 문자열인 경우 '.svg'가 포함된 데이터 로그 출력
                if (typeof value === 'string' && value.includes('.svg?p')) {
                    console.log(value);
                }

                // 객체나 배열인 경우 재귀적으로 탐색
                else if (typeof value === 'object' && value !== null) {
                    findSvg(value);
                }
            }
        }
    }

    // cluster 객체에서 '.svg' 포함된 값을 찾기 시작
    findSvg(cluster);
}


</script>
<?php include_once (get_base_url() . '/application/shop/views/mobile/_layout/addr_popup.php'); ?>

<!-- 상세검색 패널 -->
<?php include_once (get_base_url() . '/application/shop/views/mobile/resume/index_search.php'); ?>
<!-- //상세검색 패널 -->
