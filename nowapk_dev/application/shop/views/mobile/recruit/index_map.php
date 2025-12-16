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
            <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel"><?= fShortAddrDong($addr_info["ad_addr"]) ?: "전체" ?></a>
        </h1>
        <a href="#advancedSearch" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text">상세검색 열기</span></a>
    </div><!-- //top-bar -->

    <div class="page-content">

        <!-- 목록 슬라이더 -->
        <div class="card-slider">
            <div class="slider-wrap keen-slider">
                <?php foreach($data["result_list"] as $row) : ?>
							<?php if($row["ad_gps_y"] != "0.0000000000") : ?>
                <!-- 슬라이드 -->
                <div class="keen-slider__slide">
                    <!-- 내용 -->
                    <div class="item-info recruit">
                        <div class="thumb-wrap">
                            <!-- 추천라벨 -->
                            <p class="recomm-badge"><span>추천</span></p>
                            <button type="button" data-bk_type="RECRUIT" data-bk_key="<?=$row["rc_idx"]?>" data-user_id="<?=$row["mm_id"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 추가</span></button>
                            <!-- 썸네일 -->
                            <div class="thumb">
                                <a href="/recruit/recruit_detail/<?=$row["rc_idx"]?>"><img data-src="<?=getRecruitImg($row)?>" alt="" class="lozad"></a>
                            </div>
                        </div>
                        <div class="content-wrap">
                            <div class="meta top">
                                <!-- 직종라벨 -->
                                <p class="category"><?=getWorkingImg($row["rc_work"])?></p>
                                <!-- 지역 -->
                                <p class="area"><?=$row["ad_addr"]?></p>
                                <!-- 근무시간 -->
                                <p class="working-time"><?=getWorkType($row["rc_work_type"])?></p>
                            </div>
                            <!-- 제목 -->
                            <p class="title"><strong><a href="/recruit/recruit_detail/<?=$row["rc_idx"]?>"><?=$row["rc_name"]?></a></strong></p>
                            <!-- 추가조건 -->
                            <div class="meta">
                                <p><?=$row["rc_nation_nm"]?></p>
                                <span>/</span>
                                <p><?=$row["rc_age_nm"]?></p>
                            </div>
                            <!-- 지불방식 -->
                            <div class="pay">
                                <p class="period"><?=getPriceType($row["rc_price_type"])?></p>
                                <p class="amount"><?=getRecuitPrice($row)?></p>
                            </div>
                            <button type="button" aria-haspopup="dialog" data-rc_idx="<?=$row["rc_idx"]?>" class="btnReqeust btn btn-primary">지원하기</button>
                        </div>
                    </div>
                </div>
                            <?php endif;?>
                <?php endforeach;?>
            </div>
        </div>
        <!-- //목록 슬라이더 -->

        <!-- 지도 -->
        <div class="map">
            <div id="jsMap" class="js-map"></div>
        </div>

        <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>"></script>

        <script>
            //테스트 좌표 데이터(인천 연수구 xx동)
            var data = [];
            <?php foreach($data["result_list"] as $row) : ?>
							<?php if($row["ad_gps_y"] != "0.0000000000") : ?>
            data.push({coords: { lat: '<?=$row["ad_gps_y"]?>', lng: '<?=$row["ad_gps_x"]?>'}});
                            <?php endif;?>
            <?php endforeach; ?>

            //20221003 수정
            //지도 및 슬라이더 생성
            <?php if(count($data["result_list"]) > 0) : ?>
            window.addEventListener('DOMContentLoaded', function () {
                kakao.maps.load(function () {

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
					var nowposlat = <?=$ad_gps_y!=0.0000000000?$ad_gps_y:37.5665 ?>;
					var nowposlng = <?=$ad_gps_x!=0.0000000000?$ad_gps_x:126.9780 ?>;
                    var map = new kakao.maps.Map(document.getElementById('jsMap'), {
						center : new kakao.maps.LatLng(nowposlat, nowposlng),
                        level : 7,//지도레벨(시도구 영역을 클러스터처리하는 최대최소레벨)
                        minLevel: 2,
                        maxLevel: 9,
                    });
						
                    if (!data) return;

                    function selectMarker(num) {
                        markers[num].setZIndex(2);
                        markers[num].setImage(markerHoverImg);
                        activeMarker = markers[num];
                    }

                    //데이터로 마커생성
                    data.forEach(function(d) {
                        markers.push(new kakao.maps.Marker({
                            map: map,
                            position : new kakao.maps.LatLng(d.coords.lat, d.coords.lng),
                            image: markerImg
                        }));
                    });

                    selectMarker(0);
                    map.panBy(1,1);

                    /* 마커 클릭 이벤트 (슬라이드 이동) */
                    markers.forEach(function (marker, i) {
                        kakao.maps.event.addListener(marker, 'click', function() {
                            cardSlider.moveToIdx(i);
                        });
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
                    maxLevel: 9,
				};

				var map = new kakao.maps.Map(container, options);
            <?php endif; ?>
        </script>
    </div><!-- //page-content -->
</div>

<input type="hidden" name="resume_yn" value="<?=$resume ==null ? "N":"Y"?>" />
<script>
	var check_user_type = "<?=$this->user->getUserType();?>";
	$(".btnReqeust").click(function(){
        //로그인 여부 확인
        if($("#is_login").val()!="Y"){
            commonLogin();
            return;
        }

        if(check_user_type!="MEM"){
			commonAlert("기업 회원은 지원 할 수 없습니다.",function(){});
			return;
		}

        //이력서 여부 확인
        if($("[name=resume_yn]").val()=="N"){
            commonAlert("이력서 작성및 공개이력서 설정후 지원해주세요.",function(){});
            return;
        }

		var mq_idxs = new Array();
		mq_idxs.push($(this).attr("data-rc_idx"));

		openPopup('confirm', {
            content: '<em>선택한</em> 공고에<br> 지원하시겠습니까?',
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
    //alert(params["price_type"]);
    params["sex"] = search_array.sex;
    params["sage"] = search_array.sage;
    params["eage"] = search_array.eage;
    params["save_yn"] = save_yn;

    //console.log(params);
    //alert('send');

    comAjax("/recruit_proc/search_save_params",params,function(ret){
        if(ret.code=="00"){
          // console.log(ret);
          location.reload();
        }else{
          //console.log(ret);
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

    //console.log(params);
    //alert('selectaddr');
    //return;

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

<!-- 상세검색 패널 -->
<?php include_once (get_base_url() . '/application/shop/views/mobile/recruit/index_search.php'); ?>
<!-- //상세검색 패널 -->
