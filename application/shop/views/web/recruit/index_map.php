<?php
  function replaceRegionNames($text) {
    $regionMap = [
      "서울특별시" => "서울",
      "부산광역시" => "부산",
      "대구광역시" => "대구",
      "인천광역시" => "인천",
      "광주광역시" => "광주",
      "대전광역시" => "대전",
      "울산광역시" => "울산",
      "세종특별자치시" => "세종",
      "경기도" => "경기",
      "강원특별자치도" => "강원",
      "충청북도" => "충북",
      "충청남도" => "충남",
      "전북특별자치도" => "전북",
      "전라남도" => "전남",
      "경상북도" => "경북",
      "경상남도" => "경남",
      "제주특별자치도" => "제주"
    ];
    return str_replace(array_keys($regionMap), array_values($regionMap), $text);
  }
  $ad_addr = replaceRegionNames(trim($addr_info['ad_addr']));
?>
<style>
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
    background: url("/resource/shop/web/img/ic_bookmark_on.svg") no-repeat 0 0;
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
    background: url("/resource/shop/web/img/ic_bookmark_off.svg") no-repeat 0 0;
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
    border: 1px solid #1b9d7d;
    overflow: hidden;
    background: #fff;
    margin-bottom:10px;
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
    margin-bottom:10px;
    transition: background 0.2s ease;
    cursor: pointer;
  }
  .scroll-content .content-li:hover {
    background: #f8f8f8;
  }
  .content-li * ,.content-li2 * {padding: 0;margin: 0;}
  .content-li .content-info,.content-li2 .content-info {width: 100%;height: 100%;padding:10px;}
  .content-info .wicon img{height: 18px;}
  .content-info .title {font-size: 16px;font-weight: bold;margin: 4px 0px;}
  .content-info .gopost {
    position: absolute;
    top: 6px;
    right: 10px;
    color: #000;
    width: 56px;
    height: 17px;
    cursor: pointer;
  }
  .content-info .gopost:after {
    content: '';
    position: absolute;
    margin-left: -12px;
    right: 0;
    top: 0px;
    width: 16px;
    height: 16px;
    background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iY3VycmVudENvbG9yIiBjbGFzcz0iYmkgYmktYXJyb3ctcmlnaHQtc2hvcnQiIHZpZXdCb3g9IjAgMCAxNiAxNiI+PHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNNSA4YTAuNS41IDAgMCAxIC41LS41aDUuNzkzTDguMTQ2IDUuMzU0YTAuNS41IDAgMSAxIC43MDgtLjcwOGwzIDNhMC41LjUgMCAwIDEgMCAuNzA4bC0zIDNhMC41LjUgMCAxIDEtLjcwOC0uNzA4TDEwLjI5MyA4LjVINS41QTAuNS41IDAgMCAxIDUgOCIvPjwvc3ZnPg==");
  }
  .content-info .gopost:hover {
    color: #1b9d7d;
  }
  .content-info .gopost:hover:after {
    background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iIzFiOWQ3ZCIgY2xhc3M9ImJpIGJpLWFycm93LXJpZ2h0LXNob3J0IiB2aWV3Qm94PSIwIDAgMTYgMTYiPjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTSA1IDhhLjUuNSAwIDAgMSAuNS0uNWg1Ljc5M0w4LjE0NiA1LjM1NGEuNS41IDAgMSAxIC43MDgtLjcwOGwzIDNhLjUuNSAwIDAgMSAwIC43MDhsLTMgM2EuNS41IDAgMSAxLS43MDgtLjcwOEwxMC4yOTMgOC41SDUuNUFBLjUuNSAwIDAgMSA1IDgiLz48L3N2Zz4=");
  }

  .content-info .content-body {
    position: relative;
    overflow: hidden;
  }
  .content-info .desc {
    position: relative;
    margin: 0 0 0 110px;
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
    object-fit: cover; /* 이미지 비율 유지하며 박스를 꽉 채움 */
    display: block;
  }
  .content-info .link {color: #5085BB;}
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
    color: #999;
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
  .content-info .summary > * {
    min-width: 0;
    max-width: 100%;
    margin-right: 4px;
    margin-bottom: -3px;
  }
  .content-info .summary.area p{
    color: #000;
    font-weight: 600;    
  } 
  .content-info .summary.reco p{
    color: #1b9d7d;
    font-weight: 600;    
  } 
  .content-info .summary2 {
    position: absolute;
    right: 0px;
    bottom: 0px;
    font-size: 17px;
    font-weight: 700;
    letter-spacing: 0;
    color: #1b9d7d;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }
  .content-info .summary2 .summary2 p {
    margin: 0;
    text-align: right;
  }
  .content-info .summary2 .period{
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-size: 14px;
    font-weight: 600;    
  }
  .content-info .summary2 .amount em {
    font-style: normal;
  }
  .content-info .summary2 .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
  }
  .content-info .summary2 .period em:not(:last-child) {
    padding-right: 5px; 
  }
  .content-info .summary2 .period em.month {
    color:#777;
  }
  .content-info .summary2 .period em:not(:last-child)::after {
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
  .overlayWrap {
    position: absolute;
    left: -14px;
    bottom: 50px;
    width: 320px;
    height: 161px;
    margin-left: -144px;
    text-align: left;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
  }
  .overlayWrap * {
    padding: 0;
    margin: 0;
  }
  .overlayWrap .content-info {
    width: 318px;
    height: 150px;
    border-radius: 5px;
    border-bottom: 2px solid #ccc;
    border-right: 1px solid #ccc;
    overflow: hidden;
    background: #fff;
  }
  .overlayWrap .content-info:nth-child(1) {
    border: 0;
    box-shadow: 0px 1px 2px #888;
  }
  .overlayWrap .content-info:after {
    content: '';
    position: absolute;
    margin-left: -12px;
    left: 50%;
    bottom: 0px;
    width: 22px;
    height: 12px;
    background-image: url("/resource/shop/web/img/vertex_white.png");
  }
  .overlayWrap .content-info .desc {
    position: relative;
    margin: 0;
  }
  .overlayWrap .content-info .content-body {
    padding:10px;
  }
  .overlayWrap .content-info .comp {
    padding: 5px 0 0 25px;
    height: 30px;
    background: #f6f8fa;
    border-bottom: 1px solid #f6f8fa;
    font-size: 14px;
    font-weight: bold;
  }
  .overlayWrap .content-info .btn-bookmark {
    display: block;
    position: absolute;
    top: 4px;
    left: 3px;
    width: 22px;
    height: 22px;
    border: 0;
    background: url("/resource/shop/web/img/ic_bookmark_on.svg") no-repeat 0 0;
    background-size: 0px;
    pointer-events: none;
    z-index: 1;
  }
  .overlayWrap .content-info .btn-bookmark:before {
    content: "";
    display: none;
  }
  .overlayWrap .content-info .btn-bookmark:after {
    content: "";
    display: block;
    position: absolute;
    top: 3px;
    left: 3px;
    width: 16px;
    height: 16px;
    background: url("/resource/shop/web/img/ic_bookmark_off.svg") no-repeat 0 0;
    background-size: contain;
    pointer-events: all;
    z-index: 1;
  }
  .overlayWrap .content-info .btn-bookmark.on:after {
    background-image: inherit;
  }
  .iwTitle {font-weight:bold;display:block;}
  .centerAddr {position:absolute;left:10px;top:10px;border-radius: 2px;background:#fff;background:rgba(255,255,255,0.8);z-index:1;padding:5px;}
  #centerAddr {display:block;margin-top:2px;font-weight: normal;}
  .centerMarkerAddr {padding:5px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;}  					
  .dongSearchBtn {
    position: absolute;
    bottom: 75px;
    left: 0px;
    right: 0px;
    z-index: 100;
    text-align: center;
    height: 0px;
    display:none;
  }
  .dongSearchBtn.on {
    display:block;
  }					
  .search_map_btn {
    height: 45px;
    padding: 0px 25px;
    border: none;
    border-radius: 23px;
    background: #57ccaf;
    box-shadow: rgba(0, 0, 0, 0.2) 0px 4px 10px 0px;
    font-size: 0px;
    white-space: nowrap;
  }
  .search_map_btn::before {
    overflow: hidden;
    display: inline-block;
    font-size: 1px;
    line-height: 1px;
    color: transparent;
    background-image: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNCAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMu b3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyLjcwNzggMTEuNzEwN0wxMC4zMzg3IDkuMzQwOUMxMS4wNDAzIDguMzI3MzMgMTEuNTAwNCA3LjA3NzE1IDExLjUwMDQgNS42MzM1MkMxMS41MDA0IDIuNTA5NDcgOC45OTA5IDAgNiAwQzMuMDA5MTEgMCAwIDIuNTA5NDcgMCA1LjYzMzUyQzAgOC43NTcwMiAzLjAwOTExIDExLjI2NjUgNiAxMS4yNjY1QzcuNDQzNjMgMTEuMjY2NSA4LjY5MzggMTAuODA2NCA5LjcwNzM2IDEwLjEwMTdMMTMuMDY2MSAxMy40NzE1QzEzLjI2NTYgMTMuNjcyNCAxMy41OTkyIDEzLjY3MjQgMTMuNzk5MiAxMy40NzE1QzE0LjAwMDMgMTMuMjcwNSAxNC4wMDAzIDEyLjkzNzIgMTMuNzk5MiAxMi43MzcxTDEyLjcwNzggMTEuNzEwN1pNNiAxMEMzLjgwOTM2IDEwIDEuOTk5OTkgOC4xOTA2NCAxLjk5OTk5IDUuNjMzNTJDMS45OTk5OSAzLjA3NjQxIDMuODA5MzYgMS4yNjY1IDYgMS4yNjY1QzguMTkwNjQgMS4yNjY1IDEwIDMuMDc2NDEgMTAgNS42MzM1MkMxMCA4LjE5MDY0IDguMTkwNjQgMTAgNiAxMFoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPg==");
    width: 14px;
    height: 15px;
    margin: 0px 7px 0px 0px;
    vertical-align: 3px;
    content: "";
  }
  .search_map_btn .btn_text {
    display: inline-block;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: -0.5px;
    line-height: 16px;
    color: rgb(255, 255, 255);
    vertical-align: 5px;
  }
  .customeroverlay { margin-top: -80px; width: 150px; overflow: hidden; margin-left: 20px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; max-height: 40px; background-color: #fff; padding: 10px; border-radius: 10px; border: 1px solid #ff6d6d; }
</style>
<div id="content" class="page page-map">
  <main class="page-content">
    <div class="map-wrap">
      <div class="container">
        <div class="result-wrap">
          <p class="current-area">
            <span id="myLocation" class="myLocationBtn"><?= $ad_addr != "" ? $ad_addr : "서울 중구 태평로1가" ?></span>
            <input type="hidden" id="currentL" value="4">                           
            <a href="#advancedSearch" onclick="$('#advancedSearch').show(); $('#advancedSearch .btn-close-panel').closest('.layer-panel').addClass('on'); return false;" style="background: url(/resource/shop/mobile/img/ic_option.svg) no-repeat 100% 100%; top: 4px; right: 23px; display: none; width: 20px; height: 20px; background-size: contain; position: absolute;" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text">상세검색 열기</span></a>
          </p>
          <!-- 목록 -->
          <div class="list" style="margin-top: 20px;">
            <div class="reco-content">
            </div>
            <div class="scroll-content">
            </div>
          </div>
        </div>
        <!-- 지도 -->
        <div class="js-map-wrap">
          <div id="jsMap" class="js-map"></div>
          <div class="dongSearchBtn">
            <button class="search_map_btn"><span id="centerSearch" class="btn_text">주변 검색</span></button>
          </div>
        </div>
        <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>&libraries=services"></script>
        <script>		  
          var referenceLatitude = <?=$ad_gps_y>1?$ad_gps_y:37.5665 ?>;
          var referenceLongitude = <?=$ad_gps_x>1?$ad_gps_x:126.9780 ?>;		
          var currentLatitude = <?=$ad_gps_y>1?$ad_gps_y:37.5665 ?>;
          var currentLongitude = <?=$ad_gps_x>1?$ad_gps_x:126.9780 ?>;
          var currentLevel = 7;
        
          var currentDong = "<?= $ad_addr != "" ? $ad_addr : "서울특별시 중구 태평로1가" ?>";
          var searchDong = [];
          var distance = 0;
          var mapContainer = document.getElementById('jsMap'), // 지도를 표시할 div 
              mapOption = {
                center: new kakao.maps.LatLng(currentLatitude, currentLongitude), // 지도의 중심좌표
                level : currentLevel,
                minLevel: 5,
                maxLevel: 9,
              }; 				
          
          var data = [];

          function getOffsetX () {
            var list = document.querySelector('.result-wrap');
            var offsetX = list.offsetWidth + list.offsetLeft;
            var wrapper = document.querySelector('.js-map-wrap');

            wrapper.style.left = offsetX + 'px';
            wrapper.classList.add('initialized');
          }

          var lastClickIdx = 0;
          //지도 및 슬라이더 생성
          window.addEventListener('DOMContentLoaded', function () {
              getOffsetX();
              fetchSearchDong(fetchRecruitList);
              kakao.maps.load(function () {
                                      
              var map = new kakao.maps.Map(mapContainer, mapOption); 
              var geocoder = new kakao.maps.services.Geocoder();
              var centerMarkerPosition  = new kakao.maps.LatLng(currentLatitude, currentLongitude);
              var centerMarker = new kakao.maps.Marker({position: centerMarkerPosition,clickable: true}),
                infowindow = new kakao.maps.InfoWindow({zindex:1,removable: true});
              searchAddrFromCoords(map.getCenter(), displayCenterInfo);
              var mapTypeControl = new kakao.maps.MapTypeControl();
              map.addControl(mapTypeControl, kakao.maps.ControlPosition.TOPRIGHT);
              var zoomControl = new kakao.maps.ZoomControl();
              map.addControl(zoomControl, kakao.maps.ControlPosition.RIGHT);
              kakao.maps.event.addListener(map, 'click', function() {
                unSelectMarker();
              });	
              
              kakao.maps.event.addListener(map, 'idle', function() {
                var latlng = map.getCenter();
                currentLatitude = latlng.Ma;
                currentLongitude = latlng.La;
                currentLevel = map.getLevel();
                infowindow.close();
                distance = getDistanceFromLatLonInKm(referenceLatitude, referenceLongitude, currentLatitude, currentLongitude);
                if( distance.toFixed(1) > 2 ) {
                   $(".dongSearchBtn").addClass("on");
                } else {
                   $(".dongSearchBtn").removeClass("on");
                }
                searchAddrFromCoords(map.getCenter(), displayCenterInfo);
              });						  
              
              function searchAddrFromCoords(coords, callback) {
                geocoder.coord2RegionCode(coords.getLng(), coords.getLat(), callback);         
              }

              function searchDetailAddrFromCoords(coords, callback) {
                geocoder.coord2Address(coords.getLng(), coords.getLat(), callback);
              }

              function displayCenterInfo(result, status) {
                if (status === kakao.maps.services.Status.OK) {
                  var centerSearchDiv = document.getElementById('centerSearch');
                  var tmp = "";
                  for(var i = 0; i < result.length; i++) {
                    if (result[i].region_type === 'B') {
                      tmp = result[i].region_1depth_name?result[i].region_1depth_name+" ":"";
                      tmp += result[i].region_2depth_name?result[i].region_2depth_name+" ":"";
                      tmp += result[i].region_3depth_name?result[i].region_3depth_name:"";
                      currentDong = tmp;
                      centerSearchDiv.innerHTML = result[i].region_3depth_name + " 주변 검색";
                      break;
                    }
                  }
                }    
              }

              function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
                const R = 6371;
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;

                const a = 
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
                
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                const distance = R * c;

                return distance;
              }

              if (!data) return;

              var list = document.querySelector('.result-wrap');
              var markers = new Array();
              var overlays = new Array();
              var activeMarker = null;
              var clusterer = null;

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

              function selectMarker(num,overlay) {

                var pinNum = num;
                var itemId = '#content-li-' + num;
                var position = $(itemId).index();
                num = position;
                if(!markers[num]) return;

                var items = $('.content-li');
                if(activeMarker) activeMarker.setZIndex(0);
                items.each(function(index, element) {
                    markers[pinNum].setZIndex(0);
                    markers[index].setImage(markerImg);
                    overlays[index].setMap(null);
                });
                markers[pinNum].setZIndex(2);
                markers[pinNum].setImage(markerHoverImg);
                activeMarker = markers[pinNum];
                
                if (items.length > 0) {
                  if(overlay){
                    overlays[pinNum].setMap(map);
                  }
                }
              }
            
              function unSelectMarker() {
                  if(!activeMarker) return;
                  var items = $('.content-li');
                  if(activeMarker) activeMarker.setZIndex(0);
                  items.each(function(index, element) {
                      markers[index].setZIndex(0);
                      markers[index].setImage(markerImg);
                      overlays[index].setMap(null);
                  });

                  activeMarker = null;
              }

              function getItemIdx(el) {
                let target = el;
                while (target) {
                  const keyElement = target.closest("[data-rc_idx]");
                  if (keyElement) {
                    const targetRc = Number(keyElement.getAttribute("data-rc_idx"));
                    const index = data.findIndex(item => Number(item.rc_idx) === targetRc);                                      
                    return index;
                  }
                  target = target.parentElement;
                }
                return -1;
              }
                            
              function getListIdx(idx) {
                let items = document.querySelectorAll('.scroll-content .content-li');
                for (let i = 0; i < items.length; i++) {
                    if (parseInt(items[i].getAttribute('data-rc_idx')) === idx) {
                        return i; // 몇 번째인지 반환 (0부터 시작)
                    }
                }
                return -1; // 찾지 못한 경우
              }
              
              function clearMarkers() {
                for (let marker of markers) {
                marker.setMap(null);
                }
                markers.length = 0;
              }

              function clearOverlays() {
                for (let overlay of overlays) {
                overlay.setMap(null);
                }
                overlays.length = 0;
              }

              window.drawPin = function () {
                clearMarkers();
                clearOverlays();
                data.forEach(function(d) {
                  markers.push(new kakao.maps.Marker({
                    map: map,
                    position : new kakao.maps.LatLng(d.ad_gps_y, d.ad_gps_x),
                    image: markerImg
                  }));

                  var content = '<div class="overlayWrap" data-rc_idx="'+ d.rc_idx +'">' + 
                    '	<div class="content-info">' + 
                    '	  <div class="comp">' + d.mm_comp_name + '</div>' + 
                    '	  <a class="gopost" href="https://nowjob.co.kr/recruit/job_post/'+ d.rc_idx +'" target="_blank">채용정보</a>' + 
                    '	  <button type="button" data-bk_type="RECRUIT" data-bk_key="'+ d.rc_idx +'" class="btnBookMark btn-bookmark js-toggle-bookmark ' + (d.book_yn=='Y' ? 'on':'') + '"><span class="sr-text">즐겨찾기 추가</span></button>' + 
                    '	  <div class="content-body">' + 
                    '		<div class="desc">' + 
                    '		  <div class="wicon">'+ d.rt_work_img +'</div>' + 
                    '		  <div class="title ellipsis">'+ d.rc_name +'</div>' + 
                    '		  <div class="summary">' + 
                    '			<p>' + d.rc_work_type + '</p>' + 
                    '			<p>' + (d.rc_nation_nm=='무관'?'국적무관':d.rc_nation_nm + '</p>' + 
                    '			<p>' + (d.rc_age_nm=='무관'?'나이무관':d.rc_age_nm) + '</p>' + 
                    '		  </div>' + 
                    '		  <div class="summary area">' + 
                    '			<p>' + fRemoveBigAdrJS(d.ad_addr) + '</p>' + 
                    '			<p>' + ((d.distance=='' || d.distance>500)?'':d.distance+'Km') + '</p>' + 
                    '		  </div>' + 
                    '		  <div class="summary2">' + 
                    '			<p class="period">' + getPriceType(d.rc_price_type) + '</p>' + 
                    '			<p class="amount">' + (d.rc_price_type == '일급'?numberWithCommas(d.rc_day_price)+' 원':(d.rc_price_type == '월급'?numberWithCommas(d.rc_month_price)+' 원':d.rc_price_type == '주급'?numberWithCommas(d.rc_week_price)+' 원':'급여협의'))) + '</p>' + 
                    '		  </div>' + 
                    '		</div>' + 
                    '	  </div>' + 
                    '	</div>' + 
                    '</div>';

                  overlays.push(new kakao.maps.CustomOverlay({
                    clickable: true,
                    content: content,
                    position: new kakao.maps.LatLng(d.ad_gps_y, d.ad_gps_x),
                    yAnchor: 1,
                    zIndex: 3
                  }));
                  
                });
                                                        
                window.closeOverlay = function() {
                    var items = $('.content-li');
                    items.each(function(index, element) {
                        overlays[index].setMap(null);
                    });
                };        
                              
                map.panBy(1,1);

                //var nowOverIdx = null;

                markers.forEach(function (marker, i) {
                  kakao.maps.event.addListener(marker, 'mouseover', function() {
                    //nowOverIdx = i;
                    selectMarker(i,true);
                  });

                  kakao.maps.event.addListener(marker, 'mouseout', function() {
                   // unSelectMarker();
                  });

                  kakao.maps.event.addListener(marker, 'click', function() {
                    selectMarker(i,true);
                    var latlng = markers[i].getPosition();
                    map.setCenter(latlng); 									
                  });

                });					
                
              }; 
              
              window.insertList = function () {
                var tmpContent = '';
                var listNo = 0;
                document.querySelector('.scroll-content').innerHTML = '';
                data.forEach(function(d) {
                  tmpContent += '<div class="content-li" data-rc_idx="'+ d.rc_idx +'" id="content-li-' + listNo + '">' +
                    '	<div class="content-info">' + 
                    '	  <div class="content-body">' + 
                    '		<div class="img">' + 
                    '		  <img src="' + d.rt_recruit_img +'">' + 
                    '		  <button type="button" data-bk_type="RECRUIT" data-bk_key="'+ d.rc_idx +'" class="btnBookMark btn-bookmark js-toggle-bookmark ' + (d.book_yn=='Y' ? 'on':'') + '"><span class="sr-text">즐겨찾기 추가</span></button>' + 
                    '		</div>' + 
                    '		<div class="desc">' + 
                    '		  <div class="wicon">'+ d.rt_work_img +'</div>' + 
                    '		  <div class="title ellipsis">'+ d.rc_name +'</div>' + 
                    '		  <div class="summary">' + 
                    '			<p>' + d.rc_work_type + '</p>' + 
                    '			<p>' + (d.rc_nation_nm=='무관'?'국적무관':d.rc_nation_nm + '</p>' + 
                    '			<p>' + (d.rc_age_nm=='무관'?'나이무관':d.rc_age_nm) + '</p>' + 
                    '		  </div>' + 
                    '		  <div class="summary area">' + 
                    '			<p>' + fRemoveBigAdrJS(d.ad_addr) + '</p>' + 
                    '			<p>' + ((d.distance=='' || d.distance>500)?'':d.distance+'Km') + '</p>' + 
                    '		  </div>' + 
                    '		  <div class="summary2">' + 
                    '			<p class="period">' + getPriceType(d.rc_price_type) + '</p>' + 
                    '			<p class="amount">' + (d.rc_price_type == '일급'?numberWithCommas(d.rc_day_price)+' 원':(d.rc_price_type == '월급'?numberWithCommas(d.rc_month_price)+' 원':d.rc_price_type == '주급'?numberWithCommas(d.rc_week_price)+' 원':'급여협의'))) + '</p>' + 
                    '		  </div>' + 
                    '		</div>' + 
                    '	  </div>' + 
                    '	</div>' + 
                    '</div>';

                  listNo ++;
                });
                              
                document.querySelector('.scroll-content').insertAdjacentHTML('beforeend', tmpContent);
                drawPin();
              };   

              $(".dongSearchBtn").click(function(){
                var latlng = map.getCenter();
                centerMarker.setPosition(latlng);
                centerMarker.setMap(map); 
                
                if (currentLevel >= 7) {
                  var circle = new kakao.maps.Circle({
                    center: latlng,
                    radius: 4000,
                    strokeWeight: 2,
                    strokeColor: '#57ccaf',
                    strokeOpacity: 1,
                    strokeStyle: 'dash',
                    fillColor: '#57ccaf',
                    fillOpacity: 0.1
                  });
                  circle.setMap(map);								

                  setTimeout(function () {
                    //centerMarker.setMap(null);
                    circle.setMap(null);
                  }, 3000);								
                }
                searchDetailAddrFromCoords(latlng, function(result, status) {
                  if (status === kakao.maps.services.Status.OK) {
                  var jibun = !!result[0].address ? result[0].address.address_name : '';
                  var doro = !!result[0].road_address ? result[0].road_address.address_name : '';
                  updateAddrInfo('',doro,jibun,currentDong,currentLongitude,currentLatitude);
                  }   
                });	
                
                fetchSearchDong(fetchRecruitList);
                referenceLatitude = currentLatitude;
                referenceLongitude  = currentLongitude;	
              });	
  
              /* 목록 마우스클릭 이벤트 */
              list.addEventListener('click', function (e) {
                var target = e.target;
                var idx = -1;
                if (!target.closest('.content-li')) return;
                target = target.closest('.content-li');
                idx = getItemIdx(target);
                map.panTo(markers[idx].getPosition());
                selectMarker(idx,true);
              });

              list.addEventListener('mouseout', function (e) {
                //unSelectMarker();
              });

            });

          });

          function replaceRegionNames(text) {
            const regionMap = {
              "서울특별시": "서울",
              "부산광역시": "부산",
              "대구광역시": "대구",
              "인천광역시": "인천",
              "광주광역시": "광주",
              "대전광역시": "대전",
              "울산광역시": "울산",
              "세종특별자치시": "세종",
              "경기도": "경기",
              "강원특별자치도": "강원",
              "충청북도": "충북",
              "충청남도": "충남",
              "전북특별자치도": "전북",
              "전라남도": "전남",
              "경상북도": "경북",
              "경상남도": "경남",
              "제주특별자치도": "제주"
            };
            return text.replace(
              new RegExp(Object.keys(regionMap).join("|"), "g"),
              match => regionMap[match]
            );
          }
          
          function replaceRegionNames2(text) {
            const regionMap = {
              "서울 " : "서울특별시 ",
              "부산 " : "부산광역시 ",
              "대구 " : "대구광역시 ",
              "인천 " : "인천광역시 ",
              "광주 " : "광주광역시 ",
              "대전 " : "대전광역시 ",
              "울산 " : "울산광역시 ",
              "세종특별자치시 " : "세종특별자치시 세종시 ",
              "경기 " : "경기도 ",
              "강원 " : "강원특별자치도 ",
              "충북 " : "충청북도 ",
              "충남 " : "충청남도 ",
              "전북 " : "전북특별자치도 ",
              "전남 " : "전라남도 ",
              "경북 " : "경상북도 ",
              "경남 " : "경상남도 ",
              "제주 " : "제주특별자치도 " 
            };
            return text.replace(
              new RegExp(Object.keys(regionMap).join("|"), "g"),
              match => regionMap[match]
            );
          }

          window.addEventListener('resize', getOffsetX);
        </script>
      </div>
    </div>
  </main>
</div>

<input type="hidden" name="resume_yn" value="N" />

<!-- 추천 리스트 템플릿 -->
<template id="tpl_list">
    <div class="content-li2" data-rc_idx="{{rc_idx}}"> 
      <div class="content-info">
        <div class="content-body">
          <div class="img">
            <img src="{{rt_recruit_img}}" alt="{{rc_name}} 공고 이미지">
          </div>
          <div class="desc">
            <div class="wicon"> 
              {{rt_work_img}} 
            </div>
            <div class="title ellipsis">
              {{rc_name}}
            </div>
            <div class="summary">
              <p>{{rt_work_type}}</p>
              <p>{{rc_nation_nm}}</p>
              <p>{{rc_age_nm}}</p>
            </div>
            <div class="summary reco">
              <p>지금뜨는 공고</p>
            </div>
            <div class="summary2">
              <p class="period">{{rt_price_type}}</p>
              <p class="amount">{{rt_price}}</p>
            </div>
          </div>
        </div>
        <a class="gopost" href="/recruit/job_post/{{rc_idx}}" target="_blank">채용정보</a>       
      </div>
    </div>
</template>

<script>
  $(function() {
    getRecoList();						  
  });

  function getRecoList(){
    var params = {};
    params["addr1"] = "";
    params["addr2"] = "";
    params["addr3"] = "";
    params["limit"] = "2";

    comAjax("/recruit_proc/search_web_reco_list2025",params,function(ret){
      if(ret.code=="00"){
        var list = ret.data.list;
        list.forEach(function(row){
          var $clone  = $("#tpl_list").clone();
          var $content = $clone.html();
          
          if(row.rc_nation_nm=="무관"||row.rc_nation_nm=="내국인,외국인"){
            $content = $content.replaceAll("{{rc_nation_nm}}","국적무관");
          }else {
            $content = $content.replaceAll("{{rc_nation_nm}}",row.rc_nation_nm.replace("만", ""));
          }				
          $content = $content.replaceAll("{{rc_idx}}",row.rc_idx);
          $content = $content.replaceAll("{{book_yn}}",row.book_yn=="Y" ? "on":"");
          $content = $content.replaceAll("{{ad_addr}}","추천공고");
          //$content = $content.replaceAll("{{ad_addr}}",fRemoveBigAdrJS(row.ad_addr));
          $content = $content.replaceAll("{{rc_name}}",row.rc_name);
          $content = $content.replaceAll("{{distance}}",row.distance + "km")
          $content = $content.replaceAll("{{rc_age_nm}}",getConvertAgeLabel(row.rc_age_nm));

          $content = $content.replaceAll("{{rt_recruit_img}}",row.rt_recruit_img);

          var $images = $('<div>' + row.rt_work_img + '</div>').find('img');
          $images.slice(2).remove();
          var $categoryHtml = $images.parent().html();
          $content = $content.replaceAll("{{rt_work_img}}", $categoryHtml);
          $content = $content.replaceAll("{{rt_work_type}}",row.rt_work_type);
          $content = $content.replaceAll("{{rt_price_type}}",row.rt_price_type);
          if(row.rt_price.indexOf('급여협의') > -1){
            $content = $content.replaceAll("{{rt_price}}","");
          }else{
            $content = $content.replaceAll("{{rt_price}}",row.rt_price);
          }
          $content = $content.replaceAll("{{mm_id}}",row.mm_id);
          $content = $content.replaceAll("{{timestamp}}",row.timestamp);
          $clone.html($content);
          $(".reco-content").prepend($clone.html());
        });
      }
    });
  }		
  
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

	function getConvertAgeLabel(age) {
		let cleaned = age.replaceAll(" 이상", "").replaceAll(" 이하", "");
		if (!age.includes("나이무관")) {
			if (!age.includes("이상")) {
				cleaned = "~ " + cleaned;
			}
			if (!age.includes("이하")) {
				cleaned = cleaned + " ~";
			}
		}
		return cleaned;
	}
	
  function updateAddrInfo(zipcode,doro,jibun,addr,x,y){
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
          $('#myLocation').html(replaceRegionNames(addr));
        }
      },
      error: function(xhr, status, error) {
        console.error("주소 업데이트 오류:", error);
      }
    });

  }

  window.fetchSearchDong = function(callback) {
    var params = {
      lat: currentLatitude,
      lng: currentLongitude,
      dong: replaceRegionNames2(currentDong),
      nKm: 4
    };
    $.ajax({
      url: "/common_proc/get_search_b_dong",
      type: "post",
      data: params,
      dataType: "json",
      crossDomain: true,
      success: function(json) {
        searchDong = [];
        if (json.code === "00" && Array.isArray(json.data)) {
          searchDong = json.data.map(function(item) {
            let dong = item.search_b_dong;
            if (dong.startsWith("세종특별자치시 세종시")) {
              dong = dong.replace("세종특별자치시 세종시", "세종특별자치시");
            }
            return dong;
          });
        }
        if (typeof callback === "function") {
          callback();
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }
	
  window.fetchRecruitList = function() {
    if (!Array.isArray(searchDong) || searchDong.length === 0) {
      return;
    }

    var params = {
      "currentLatitude" : currentLatitude,
      "currentLongitude" : currentLongitude,
      "search_addr[]": searchDong
    };
    $.ajax({
      url: "/recruit_proc/search_map_list2025",
      type: "post",
      data: params,
      traditional: true,
      dataType: "json",
      crossDomain: true,
      success: function(json) {
        if (json.code === "00" && json.data && Array.isArray(json.data.list)) {
          data.length = 0;
          json.data.list.forEach(function(item) {
            if (!data.some(function(existing) {
              return existing.rc_idx === item.rc_idx;
            })) {
              data.push(item);
            }
          });
          insertList();
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  function getPriceType(price_type) {
      if (price_type === "일급,주급,월급") {
          return "";
      }
      if (!price_type) {
          return "";
      }
      return price_type
          .split(',')
          .map(type => `<em>${type}</em>`)
          .join('');
  }

  function numberWithCommas(x) {
    if (typeof x !== 'number') {
      x = parseFloat(x);
    }
    if (isNaN(x)) return '';
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
			
</script>
