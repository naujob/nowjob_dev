<div id="content" class="page page-map">
    <main class="page-content">
        <div class="map-wrap">
            <div class="container">
                <div class="result-wrap">
                    <p class="current-area">
                        <a id="btnAddr" href="javascript:void(0);" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel"><?=$addr_info["ad_addr"]?></a>
                    </p>
                    <!-- 목록 -->
                    <div class="list">
                        <div class="scroll-content">
                            <?php foreach($data["result_list"] as $row) : ?>
                            <!-- 목록아이템 -->
                            <div class="list-item">
                                <!-- 내용 -->
                                <div class="item-info sale">
                                    <div class="thumb-wrap">
                                        <!-- 추천라벨 -->
                                        <!--p class="recomm-badge"><span>추천</span></p-->
                                        <!-- 즐겨찾기 추가된 목록: on -->
                                        <button type="button" data-bk_type="TRADE" data-bk_key="<?=$row["td_idx"]?>" class="btnBookMark btn-bookmark js-toggle-bookmark <?=$row["book_yn"]=="Y" ? "on":""?>"><span class="sr-text">즐겨찾기 해제</span></button>
                                        <!-- 썸네일 -->
                                        <div class="thumb">
			                                <a href="/trade/trade_detail/<?=$row["td_idx"]?>"><img data-src="<?=getTradeImg($row)?>" alt="" class="lozad"></a>
			                            </div>
                                    </div>
                                    <div class="content-wrap">
			                            <p class="title"><strong><a href="/trade/trade_detail/<?=$row["td_idx"]?>"><?=$row["td_name"]?></a></strong></p>
			                            <div class="meta">
			                                <p class="area"><?=$row["ad_addr"]?></p>
			                            </div>
			                            <div class="price-wrap">
			                                <p class="amount"><em class="num"><?=number_format($row["td_price"])?></em> 원</p>
			                                <p class="date"><?=get_yyyymmdd($row["reg_dt"])?></p>
			                            </div>
			                        </div>
                                </div>
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <!-- //목록 -->
                </div>
                <!-- 지도 -->
                <div class="js-map-wrap">
                    <div id="jsMap" class="js-map"></div>
                </div>

                <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>&libraries=clusterer"></script>

                <script>
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

                            var map = new kakao.maps.Map(document.getElementById('jsMap'), {
                                center : new kakao.maps.LatLng(data[0].coords.lat, data[0].coords.lng),
                                level : 9,//지도레벨(시도구 영역을 클러스터처리하는 최대최소레벨)
                                minLevel: 7,
                                maxLevel: 9,
                            });

                            function selectMarker(num) {
                                if(!markers[num]) return;

                                var items = list.querySelectorAll('.list-item');

                                markers[num].setZIndex(2);
                                markers[num].setImage(markerHoverImg);
                                activeMarker = markers[num];

                                if (items.length > 0) {
                                    items[num].classList.add('on');
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

                            //클러스터 생성
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

                            /* 목록 마우스오버 이벤트 */
                            list.addEventListener('mouseover', function (e) {
                                var target = e.target;
                                var idx = -1;

                                if (!target.closest('.list-item')) return;
                                target = target.closest('.list-item');
                                idx = getItemIdx(target);

                                selectMarker(idx);
                                clusterRedraw();
                            });

                            list.addEventListener('mouseout', function (e) {
                                unSelectMarker();
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

<script>
	var list_id = "#ajaxList";

	var search_array = {
		text : "",
		skm : "",
		ekm : "",
		category : "",
		sprice : "",
		eprice : "",
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

		//검색어 설정
		search_array.text = $("[name='text']").val();
		search_array.skm = $("[name='skm']").val();
		search_array.ekm = $("[name='ekm']").val();
		search_array.category = arr_category;
		search_array.sprice = $("[name='sprice']").val();
		search_array.eprice = $("[name='eprice']").val();
    }

	function getBoardList(save_yn){
		var params = {};
		params["page"] = list_page;
		params["text"] = search_array.text;
		params["skm"] = search_array.skm;
		params["ekm"] = search_array.ekm;
		params["category"] = search_array.category;
		params["sprice"] = search_array.sprice;
		params["eprice"] = search_array.eprice;
        params["save_yn"] = save_yn;

		comAjax("/trade_proc/search_list",params,function(ret){
			if(ret.code=="00"){
				location.reload();
			}else{

			}
		});
	}
</script>

<script>
$(document).ready(function(){
    var ad_addr = "<?=$addr_info["ad_addr"]?>";

    if(ad_addr==""){
        $("#changeArea").addClass("on");
    }
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
</script>
<?php include_once (get_base_url() . '/application/shop/views/web/_layout/addr_popup.php'); ?>
