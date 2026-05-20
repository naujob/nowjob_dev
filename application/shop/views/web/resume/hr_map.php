<?php
// 주소 포맷팅은 JavaScript의 fRemoveBigAdrJS 함수에서 처리
?>
<style>
  .page-map .js-map-wrap {
    opacity: 1;
  }

  .page-map .result-wrap {
    width: 490px;
    padding: 22px 0 10px 10px;
    z-index: 2;
    overflow: visible;
    transition: transform 0.3s ease;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
  }

  .page-map .result-wrap.collapsed {
    transform: translateX(-490px);
  }

  .collapse-btn {
    position: absolute;
    right: -24px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 100px;
    background: #eee;
    border: 0;
    border-left: none;
    cursor: pointer;
    font-size: 16px;
    line-height: 60px;
    text-align: center;
    border-radius: 0 10px 10px 0;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
  }

  .result-wrap-container {
    height: 100%;
    margin: 0 auto;
    padding: 0;
  }

  .page-map .result-wrap .scroll-content {
    padding: 0;
  }

  .btn-bookmark {
    display: block;
    position: absolute;
    top: 0px;
    right: 0px;
    width: 40px;
    height: 40px;
    border: 0;
    background: url("<?= RES_PATH ?>/img/ic_bookmark_on.svg") no-repeat 0 0;
    background-size: 0px;
    pointer-events: none;
    z-index: 1;
  }

  .btn-bookmark:before {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    border-top: 40px solid #f8f8f8;
    border-left: 40px solid transparent;
    z-index: -1;
  }

  .btn-bookmark:after {
    content: "";
    display: block;
    position: absolute;
    top: 3px;
    right: 3px;
    width: 16px;
    height: 16px;
    background: url("<?= RES_PATH ?>/img/ic_bookmark_off.svg") no-repeat 0 0;
    background-size: contain;
    pointer-events: all;
    z-index: 1;
  }

  .btn-bookmark.on:after {
    background-image: inherit;
  }

  .sr-text {
    display: block;
    position: absolute;
    width: 1px;
    height: 1px;
    margin: 0;
    padding: 0;
    font-size: 1px;
    color: transparent;
    pointer-events: none;
    white-space: nowrap;
    overflow: hidden;
    border: 0;
    outline: 0;
  }

  .reco-content .content-li2 {
    position: relative;
    width: 462px;
    height: 115px;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 10px;
    border: 1px solid #EB5349;
    overflow: hidden;
    background: #fff;
    margin-bottom: 10px;
  }

  .scroll-content .content-li {
    position: relative;
    width: 462px;
    height: 115px;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
    background: #fff;
    margin-bottom: 10px;
    transition: background 0.2s ease;
  }

  .scroll-content .content-li:hover {
    background: #f8f8f8;
  }

  .content-li *,
  .content-li2 * {
    padding: 0;
    margin: 0;
  }

  .content-li .content-info,
  .content-li2 .content-info {
    width: 100%;
    height: 100%;
    padding: 10px;
  }

  .content-info .wicon img {
    height: 18px;
  }

  .content-info .title {
    font-size: 16px;
    font-weight: bold;
    margin: 4px 0px;
  }

  .content-info .content-body {
    position: relative;
    overflow: hidden;
    height: 100%;
  }

  .content-info .desc {
    position: relative;
    height: 100%;
    margin: 0 0 0 110px;
    cursor: pointer;
  }

  .content-info .desc .ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .content-info .img {
    position: absolute;
    width: 95px;
    height: 95px;
    border: 0;
    border-radius: 5px;
    color: #888;
    overflow: hidden;
  }

  .content-info .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    /* 이미지 비율 유지하며 박스를 꽉 채움 */
    display: block;
  }

  .content-info .link {
    color: #5085BB;
  }

  .content-info .summary {
    display: flex;
    font-size: 15px;
    gap: 5px;
    position: relative;
  }

  .content-info .summary p {
    position: relative;
    padding-right: 5px;
    margin: 0;
    color: #000;
    font-weight: 600;
  }

  .content-info .summary p:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #ACACAC;
  }

  .content-info .summary>* {
    min-width: 0;
    max-width: 100%;
    margin-right: 4px;
    margin-bottom: -3px;
  }

  .content-info .summary.area p {
    color: #000;
    font-weight: 600;
  }

  .content-info .summary.reco p {
    color: #1b9d7d;
    font-weight: 600;
  }

  .content-info .summary2 {
    position: absolute;
    right: 0px;
    bottom: 0px;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0;
    color: #1b9d7d;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }

  .content-info .summary2 p {
    margin: 0;
    text-align: right;
  }

  .content-info .summary2 .pricetype {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
  }

  .content-info .summary2 .pricetype em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .content-info .summary2 .pricetype em:not(:last-child) {
    padding-right: 5px;
  }

  .content-info .summary2 .pricetype em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777777;
  }

  .content-info .summary3 {
    position: absolute;
    right: 0px;
    top: 0px;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }

  .content-info .summary3 p {
    margin: 0;
    text-align: right;
  }

  .content-info .summary3 .worktype {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;

  }

  .content-info .summary3 .worktype em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }

  .content-info .summary3 .worktype em.wtype1 {
    color: #3a70db;
  }

  .content-info .summary3 .worktype em.wtype2 {
    color: #F86048;
  }

  .content-info .summary3 .worktype em.wtype3 {
    color: #1B9D7D;
  }

  .content-info .summary3 .worktype em:not(:last-child) {
    padding-right: 5px;
  }

  .content-info .summary3 .worktype em:not(:last-child)::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 1px;
    height: 0.5em;
    margin: auto;
    border-right: 1px solid #777777;
  }

  .content-info .star-rating {
    position: absolute;
    bottom: 5px;
    left: 5px;
    width: 85px;
    height: 15px;
    margin: 5px auto 0;
  }

  .working-day {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    margin: 0 0 -4px;
    list-style: none;
  }

  .working-day>li {
    width: 1.8em;
    height: 1.8em;
    margin: 0 0.5em 4px 0;
    font-size: 12px;
    color: #BEBFC9;
    line-height: 1.8;
    text-align: center;
    text-indent: -1px;
    border-radius: 100%;
    border: 1px solid #57CCAF;
    background: #fff;
    float: left;
  }

  .working-day>li:last-child {
    margin-right: 0;
  }

  .working-day li[aria-hidden=false] {
    color: #fff;
    border-color: transparent;
    background-color: #57CCAF;
  }
</style>
<div id="content" class="page page-map">
  <main class="page-content">
    <div class="map-wrap">
      <div class="result-wrap-container">
        <div class="result-wrap">
          <p class="current-area" style="">
            <span id="myLocation" class="myLocationBtn"><?= $ad_addr != "" ? $ad_addr : "서울 중구 태평로1가" ?></span>
          </p>
          <div class="list" style="margin-top: 20px;">
            <div class="reco-content">
            </div>
            <div class="scroll-content">
            </div>
          </div>
          <button class="collapse-btn">〈</button>
        </div>
        <div class="js-map-wrap">
          <div id="jsMap" class="js-map"></div>
        </div>
        <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?= isset($kakao_key["인증키"]) ? $kakao_key["인증키"] : "" ?>&libraries=clusterer,services"></script>
        <script>
          var data = [];
          var resumelist = [];
          var currentLatitude = <?= $ad_gps_y > 1 ? $ad_gps_y : 37.5665 ?>;
          var currentLongitude = <?= $ad_gps_x > 1 ? $ad_gps_x : 126.9780 ?>;
          var currentLevel = <?= $search_txt["mapLevel"] ? $search_txt["mapLevel"] : 6 ?>;
          var pins = [];

          window.addEventListener('DOMContentLoaded', function() {
            kakao.maps.load(function() {
              var map = new kakao.maps.Map(document.getElementById('jsMap'), {
                center: new kakao.maps.LatLng(currentLatitude, currentLongitude),
                level: currentLevel,
                minLevel: 3,
                maxLevel: 10,
              });
              var geocoder = new kakao.maps.services.Geocoder();
              var clusterer = new kakao.maps.MarkerClusterer({
                map: map,
                averageCenter: true,
                minLevel: 1,
                gridSize: 80,
                disableClickZoom: true,
                minClusterSize: 1,
                styles: [{
                  width: '40px',
                  height: '40px',
                  background: 'rgba(87, 204, 175, 0.8)',
                  borderRadius: '50%',
                  color: '#fff',
                  textAlign: 'center',
                  lineHeight: '40px',
                  fontWeight: 'bold',
                  boxShadow: '0 0 0 5px rgba(87, 204, 175, 0.4)'
                }]
              });
              if (!data) return;
              var markers = new Array();
              var selectedCluster = null;

              function clearMarkers() {
                for (let marker of markers) {
                  marker.setMap(null);
                }
                markers.length = 0;
              }

              window.drawPin = function() {
                clearMarkers();
                data.forEach(function(d) {
                  // 2025.02.10 위치 보안 및 시각적 효과를 위해 마커 위치를 랜덤하게 산개 (Jittering)
                  // 0.003도 약 300m 범위 내 랜덤 분포
                  var lat = parseFloat(d.ad_gps_y);
                  var lng = parseFloat(d.ad_gps_x);
                  var scatterFactor = 0.01;
                  var rLat = lat + (Math.random() - 0.5) * scatterFactor;
                  var rLng = lng + (Math.random() - 0.5) * scatterFactor;

                  var marker = new kakao.maps.Marker({
                    position: new kakao.maps.LatLng(rLat, rLng)
                  });
                  marker.uid = d.ru_idx;
                  markers.push(marker);
                });
                clusterer.addMarkers(markers);
                selectClusterByCoordinates();
              };

              function resetClusterStyle(cluster) {
                if (!cluster) return;
                const marker = cluster.getClusterMarker();
                if (!marker) return;
                const content = marker.getContent();
                if (!content) return;
                content.style.background = 'rgba(87, 204, 175, 0.8)';
                content.style.boxShadow = '0 0 0 5px rgba(87, 204, 175, 0.4)';
              }

              function highlightClusterStyle(cluster) {
                if (!cluster) return;
                const marker = cluster.getClusterMarker();
                if (!marker) return;
                const content = marker.getContent();
                if (!content) return;
                content.style.background = 'rgba(255, 70, 70, 0.9)';
                content.style.boxShadow = '0 0 0 5px rgba(255, 70, 70, 0.4)';
              }
              kakao.maps.event.addListener(map, 'idle', function() {
                currentLevel = map.getLevel();
              });
              kakao.maps.event.addListener(clusterer, 'clusterclick', function(cluster) {
                const markerList = cluster.getMarkers();
                const uidList = markerList.map(m => m.uid);
                pins = [...uidList];
                map.setCenter(cluster.getCenter());
                console.log(cluster.getCenter());
                resetClusterStyle(selectedCluster);
                highlightClusterStyle(cluster);
                selectedCluster = cluster;
                var latlng = map.getCenter();
                currentLatitude = latlng.Ma;
                currentLongitude = latlng.La;
                searchAddrFromCoords(map.getCenter(), displayCenterInfo);
                fetchResumeList();
              });

              function searchAddrFromCoords(coords, callback) {
                geocoder.coord2RegionCode(coords.getLng(), coords.getLat(), callback);
              }

              function displayCenterInfo(result, status) {
                if (status === kakao.maps.services.Status.OK) {
                  var centerSearchDiv = document.getElementById('centerSearch');
                  var tmp = "";
                  for (var i = 0; i < result.length; i++) {
                    if (result[i].region_type === 'B') {
                      tmp = result[i].region_1depth_name ? result[i].region_1depth_name + " " : "";
                      tmp += result[i].region_2depth_name ? result[i].region_2depth_name + " " : "";
                      tmp += result[i].region_3depth_name ? result[i].region_3depth_name : "";
                      currentDong = tmp;
                      break;
                    }
                  }
                  var jibun = !!result[0].address ? result[0].address.address_name : '';
                  var doro = !!result[0].road_address ? result[0].road_address.address_name : '';
                  updateAddrInfo('', doro, jibun, currentDong, currentLongitude, currentLatitude);
                }
              }

              function getDistanceKm(lat1, lng1, lat2, lng2) {
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLng = (lng2 - lng1) * Math.PI / 180;

                const a =
                  Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) *
                  Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLng / 2) * Math.sin(dLng / 2);

                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
              }

              function selectClusterByCoordinates() {
                if (typeof currentLatitude !== 'number' || typeof currentLongitude !== 'number') {
                  return;
                }

                const targetLatLng = new kakao.maps.LatLng(currentLatitude, currentLongitude);
                const clusters = clusterer._clusters;

                if (!clusters || clusters.length === 0) {
                  return;
                }

                let matchedCluster = null;
                let minDistance = Infinity;
                const toleranceKm = 3; // 3km 반경 이내에서 탐색

                for (let i = 0; i < clusters.length; i++) {
                  const cluster = clusters[i];
                  const center = cluster.getCenter();

                  const distance = getDistanceKm(
                    targetLatLng.getLat(), targetLatLng.getLng(),
                    center.getLat(), center.getLng()
                  );

                  if (distance <= toleranceKm && distance < minDistance) {
                    matchedCluster = cluster;
                    minDistance = distance;
                  }
                }

                if (!matchedCluster) {
                  return;
                }

                const markerList = matchedCluster.getMarkers();
                const uidList = markerList.map(m => m.uid);

                pins = [...uidList];
                map.setCenter(matchedCluster.getCenter());

                resetClusterStyle(selectedCluster);
                highlightClusterStyle(matchedCluster);
                selectedCluster = matchedCluster;

                searchAddrFromCoords(map.getCenter(), displayCenterInfo);
                fetchResumeList();
              }

              kakao.maps.event.addListener(clusterer, 'clustered', function(clusters) {
                clusters.forEach(function(cluster) {
                  resetClusterStyle(cluster);
                });
                if (selectedCluster) {
                  const isSelectedClusterVisible = clusters.some(c => c === selectedCluster);
                  if (isSelectedClusterVisible) {
                    highlightClusterStyle(selectedCluster);
                  } else {
                    selectedCluster = null;
                  }
                }
              });
            });
          });
        </script>

      </div>
    </div>
  </main>
</div>

<script>
  $(function() {
    // 초기 주소를 단축 형식으로 포맷팅
    setTimeout(function() {
      var initialAddr = $('#myLocation').text();
      if (initialAddr && initialAddr !== "서울 중구 태평로1가") {
        $('#myLocation').text(fRemoveBigAdrJS(initialAddr));
      }
    }, 100);

    getRecoList();
  });

  function getRecoList() {

    var params = {
      "addr1": "",
      "addr2": "",
      "addr3": "",
      "limit": "2"
    };
    $.ajax({
      url: "/resume_proc/search_reco_list2025",
      type: "post",
      data: params,
      traditional: true,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code == "00") {
          var list = ret.data.list;
          var tmpContent = '';
          document.querySelector('.reco-content').innerHTML = '';
          ret.data.list.forEach(function(d) {
            tmpContent += '<div class="content-li2" data-ru_idx="' + d.ru_idx + '">' +
              '	<div class="content-info">' +
              '	  <div class="content-body">' +
              '		<div class="img">' +
              '		  <img src="' + d.ru_resume_img + '">' +
              '		</div>' +
              '		<div class="desc">' +
              '		  <div class="wicon">' + d.ru_work_img + '</div>' +
              '		  <div class="title ellipsis">' + d.ru_name + '</div>' +
              '		  <div class="summary">' +
              '			<ul class="working-day">' + d.ru_week + '</ul>' +
              '		  </div>' +
              '		  <div class="summary reco">' +
              '			<p>지금뜨는 추천인재</p>' +
              '		  </div>' +
              '		  <div class="summary2">' +
              '			<p class="pricetype">' + getPriceType(d.ru_price_type) + '</p>' +
              '		  </div>' +
              '		  <div class="summary3">' +
              '			<p class="worktype">' + getWorkType(d.ru_work_type) + '</p>' +
              '		  </div>' +
              '		</div>' +
              '	  </div>' +
              '	</div>' +
              '</div>';
          });
          document.querySelector('.reco-content').insertAdjacentHTML('beforeend', tmpContent);
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  /*
  var check_user_type = "<?= $this->user->getUserType(); ?>";
	$("#btnReqeust").click(function(){
    if($("#is_login").val()!="Y"){
      commonLogin();
      return;
    }

		if(check_user_type!="COMP"){
			commonAlert("일반 회원은 제안 할 수 없습니다.",function(){});
			return;
		}
  });
  */
</script>


<script>
  $(document).ready(function() {
    fetchResumePin();
  });

  window.fetchResumePin = function() {
    $.ajax({
      url: "/resume_proc/getPins2025",
      type: "post",
      traditional: true,
      dataType: "json",
      crossDomain: true,
      success: function(json) {
        if (json.code === "00" && json.data && Array.isArray(json.data)) {
          data.length = 0;
          json.data.forEach(function(item) {
            data.push(item);
          });
          drawPin();
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  window.fetchResumeList = function() {

    var params = {
      mapLevel: currentLevel,
    };

    //if () {
    $.ajax({
      url: "/resume_proc/search_save_params2025",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code === "00") {
          //console.log('설정저장');
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 저장 오류:", error);
      }
    });
    //}    

    var params = {
      "currentLatitude": currentLatitude,
      "currentLongitude": currentLongitude,
      "pins[]": pins
    };
    $.ajax({
      url: "/resume_proc/search_map_list2025",
      type: "post",
      data: params,
      traditional: true,
      dataType: "json",
      crossDomain: true,
      success: function(json) {
        if (json.code === "00" && json.data && Array.isArray(json.data.list)) {
          resumelist.length = 0;
          json.data.list.forEach(function(item) {
            resumelist.push(item);
          });
          getRecoList();
          insertList();
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  window.insertList = function() {
    var tmpContent = '';
    var listNo = 0;
    document.querySelector('.scroll-content').innerHTML = '';
    resumelist.forEach(function(d) {
      tmpContent += '<div class="content-li" data-ru_idx="' + d.ru_idx + '" id="content-li-' + listNo + '">' +
        '	<div class="content-info">' +
        '	  <div class="content-body">' +
        '		<div class="img">' +
        '		  <img src="' + d.ru_resume_img + '">' +
        <?php if ($mm_type === "COMP") { ?> '		  <button type="button" data-bk_type="RESUME" data-bk_key="' + d.ru_idx + '" class="btnBookMark btn-bookmark js-toggle-bookmark ' + (d.book_yn == 'Y' ? 'on' : '') + '"><span class="sr-text">즐겨찾기 추가</span></button>' +
        <?php } ?> '		</div>' +
        '		<p class="star-rating"><span class="star" style="width:100%;"></span><span class="sr-text">평점</span><span class="sr-text">5</span></p>' +
        '		<div class="desc">' +
        '		  <div class="wicon">' + d.ru_work_img + '</div>' +
        '		  <div class="title ellipsis">' + d.ru_name + '</div>' +
        '		  <div class="summary">' +
        '			<ul class="working-day">' + d.ru_week + '</ul>' +
        '		  </div>' +
        '		  <div class="summary">' +
        '			<p>' + (d.mm_nation == '' ? '-' : d.mm_nation) + '</p>' +
        '			<p>' + d.mm_age + '대</p>' +
        '			<p>' + fRemoveBigAdrJS(d.ad_addr) + '</p>' +
        '		  </div>' +
        '		  <div class="summary2">' +
        '			<p class="pricetype">' + getPriceType(d.ru_price_type) + '</p>' +
        '		  </div>' +
        '		  <div class="summary3">' +
        '			<p class="worktype">' + getWorkType(d.ru_work_type) + '</p>' +
        '		  </div>' +
        '		</div>' +
        '	  </div>' +
        '	</div>' +
        '</div>';

      listNo++;
    });

    document.querySelector('.scroll-content').insertAdjacentHTML('beforeend', tmpContent);
    const $wrap = $('.result-wrap');
    if ($wrap.hasClass('collapsed')) {
      $wrap.removeClass('collapsed');
      $wrap.find('.collapse-btn').text('〈');
    }
  };

  function fRemoveBigAdrJS(arg) {

    const parts1 = arg.split(",");
    arg = parts1[0];

    arg = arg.replace("서울특별시 ", "서울시 ");
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
    arg = arg.replace("강원특별자치도 ", "");
    arg = arg.replace("충청남도 ", "");
    arg = arg.replace("충청북도 ", "");
    arg = arg.replace("경상남도 ", "");
    arg = arg.replace("경상북도 ", "");
    arg = arg.replace("전라남도 ", "");
    arg = arg.replace("전북특별자치도 ", "");
    arg = arg.replace("제주특별자치도 ", "");
    arg = arg.replace("세종특별자치시 ", "세종시 ");
    arg = arg.replace("광주광역시 ", "광주시 ");
    arg = arg.replace("인천광역시 ", "인천시 ");
    arg = arg.replace("울산광역시 ", "울산시 ");
    arg = arg.replace("부산광역시 ", "부산시 ");
    arg = arg.replace("대구광역시 ", "대구시 ");
    arg = arg.replace("대전광역시 ", "대전시 ");

    const parts = arg.split(" ");
    if (parts.length >= 3) {
      return parts[0] + " " + parts[2];
    } else {
      return arg;
    }
  }

  function updateAddrInfo(zipcode, doro, jibun, addr, x, y) {
    var params = {};
    params["ad_zipcode"] = zipcode;
    params["ad_jibun"] = jibun;
    params["ad_doro"] = doro;
    params["ad_addr"] = addr;
    params["ad_gps_x"] = x;
    params["ad_gps_y"] = y;
    $.ajax({
      url: "/common_proc/set_addr_info",
      type: "post",
      data: params,
      dataType: "json",
      crossDomain: true,
      success: function(json) {
        searchDong = [];
        if (json.code === "00") {
          $('#myLocation').html(fRemoveBigAdrJS(addr));
        }
      },
      error: function(xhr, status, error) {
        console.error("주소 업데이트 오류:", error);
      }
    });

  }


  function getPriceType(price_type) {
    if (!price_type) {
      return "";
    }
    if (price_type === "일급,주급,월급") {
      return "급여협의";
    }
    return price_type
      .split(',')
      .map(type => `${type}`)
      .join('');
  }

  function getWorkType(work_type) {
    if (!work_type) {
      return "";
    }
    const matches = work_type.match(/<span>(.*?)<\/span>/g);

    if (!matches) {
      return "";
    }
    return matches.map(item => {
      const text = item.replace(/<\/?span>/g, '').trim();
      let cls = '';
      if (text === '주간') {
        cls = 'wtype1';
      } else if (text === '야간') {
        cls = 'wtype2';
      } else if (text === '2교대') {
        cls = 'wtype3';
      }
      return `<em class="${cls}">${text}</em>`;
    }).join('');
  }

  $(document).on('click', '.desc', function() {
    <?php if ($mm_type !== "COMP") { ?>
      commonAlert("기업회원 전용 서비스입니다.", function() {});
      return;
    <?php } else { ?>
      var ruIdx = $(this).closest('.content-li, .content-li2').data('ru_idx');
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.open(url, '_blank');
      }
    <?php } ?>
  });
  $(function() {
    $('.collapse-btn').on('click', function() {
      const $wrap = $(this).closest('.result-wrap');
      $wrap.toggleClass('collapsed');
      $(this).text($wrap.hasClass('collapsed') ? '〉' : '〈');
    });
  });
</script>