<?php
// 주소 포맷팅은 JavaScript의 fRemoveBigAdrJS 함수에서 처리
?>
<style>
  .page-map .result-wrap .slider-wrap {
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
    background: url("https://nowjob.co.kr/resource/shop/web/img/ic_bookmark_on.svg") no-repeat 0 0;
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
    border-style: none;
    background: url("https://nowjob.co.kr/resource/shop/web/img/ic_bookmark_off.svg") no-repeat 0 0;
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
    width: 100%;
    height: 115px;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 10px;
    border: 1px solid #1b9d7d;
    overflow: hidden;
    background: #fff;
    margin-bottom: 10px;
  }

  .slider-wrap .content-li {
    position: relative;
    width: 100%;
    height: 110px;
    overflow: hidden;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
    background: #fff;
    margin-bottom: 10px;
    transition: background 0.2s ease;
    cursor: pointer;
  }

  .slider-wrap .content-li:hover {
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
    font-size: 1rem;
    font-weight: bold;
    margin-bottom: -2px;
  }

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
    margin: 0 0 0 100px;
    height: 90px;
  }

  .content-info .desc .ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 80%;
  }

  .content-info .img {
    position: absolute;
    width: 90px;
    height: 90px;
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
    gap: 5px;
    position: relative;
    font-size: 14px;
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
    font-size: 17px;
    font-weight: 600;
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

  .content-info .summary2 .period {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
    position: relative;
    font-weight: 600;
  }

  .content-info .summary2 .amount em {
    font-style: normal;
  }

  .content-info .summary2 .period em {
    position: relative;
    display: inline-block;
    font-style: normal;
    color: #777;
  }

  .content-info .summary2 .period em:not(:last-child) {
    padding-right: 5px;
  }

  .content-info .summary2 .period em.on {
    color: #1b9d7d;
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
    border-right: 1px solid #777;
  }

  .content-info .summary3 {
    position: absolute;
    top: 6px;
    right: 10px;
    color: #000;
    height: 17px;
    text-align: right;
    font-size: 17px;
    font-weight: 600;
  }

  .content-info .summary3 em {
    position: relative;
    display: inline-block;
    font-style: normal;
    padding-left: 5px;
  }

  .content-info .summary3 em:not(:last-child) {
    padding-right: 5px;
  }

  .content-info .summary3 em:not(:last-child)::after {
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

  .content-info .summary3 em.wtype1 {
    color: #3a70db;
  }

  .content-info .summary3 em.wtype2 {
    color: #F86048;
  }

  .content-info .summary3 em.wtype3 {
    color: #1B9D7D;
  }

  .centerAddr {
    position: absolute;
    left: 10px;
    top: 10px;
    border-radius: 2px;
    background: #fff;
    background: rgba(255, 255, 255, 0.8);
    z-index: 1;
    padding: 5px;
  }

  #centerAddr {
    display: block;
    margin-top: 2px;
    font-weight: normal;
  }

  .centerMarkerAddr {
    padding: 5px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
  }
</style>
<div class="backdrop-loader" hidden>
  <div class="loader show"></div>
</div>

<div class="page page-map">
  <div class="top-bar is-transparency">
    <a href="#" class="btn-back" style="background-image: url('/resource/shop/mobile/img/ic_back_dark.svg');"><span class="sr-text">이전으로</span></a>
    <h2 class="current-area">
      <span id="myLocation" class="myLocationBtn"><?= $ad_addr != "" ? $ad_addr : "서울 중구 태평로1가" ?></span>
    </h2>
  </div>

  <div class="page-content">

    <div class="card-slider">
      <div class="slider-wrap keen-slider">
      </div>
    </div>

    <div class="map">
      <div id="jsMap" class="js-map"></div>
    </div>

    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$kakao_key["인증키"]?>&libraries=clusterer,services"></script>
    <script>
      var data = [];
      var recruitlist = [];
      var currentLatitude = <?= $ad_gps_y > 1 ? $ad_gps_y : 37.5665 ?>;
      var currentLongitude = <?= $ad_gps_x > 1 ? $ad_gps_x : 126.9780 ?>;
      var currentLevel = 8; // 진입 시 2km 수준으로 더 줌 (필요 시 조정)
      var currentDong = "<?= $ad_addr != "" ? $ad_addr : "서울특별시 중구 태평로1가" ?>";
      var pins = [];

      window.addEventListener('DOMContentLoaded', function() {
        kakao.maps.load(function() {
          var map = new kakao.maps.Map(document.getElementById('jsMap'), {
            center: new kakao.maps.LatLng(currentLatitude, currentLongitude),
            level: currentLevel,
            minLevel: 5,
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
              var marker = new kakao.maps.Marker({
                position: new kakao.maps.LatLng(d.ad_gps_y, d.ad_gps_x)
              });
              marker.uid = d.rc_idx;
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
          // 지도 드래그 시작 시 card-slider 숨김 처리
          kakao.maps.event.addListener(map, 'dragstart', function() {
            var cardSlider = document.querySelector('.card-slider');
            if (cardSlider) {
              cardSlider.style.display = 'none';
            }
          });
          kakao.maps.event.addListener(clusterer, 'clusterclick', function(cluster) {
            const markerList = cluster.getMarkers();
            const uidList = markerList.map(m => m.uid);
            pins = [...uidList];
            map.setCenter(cluster.getCenter());
            resetClusterStyle(selectedCluster);
            highlightClusterStyle(cluster);
            selectedCluster = cluster;
            var latlng = map.getCenter();
            currentLatitude = latlng.Ma;
            currentLongitude = latlng.La;
            searchAddrFromCoords(latlng, displayCenterInfo);
            fetchRecruitList();
            // 클러스터 클릭 시 card-slider 표시
            var cardSlider = document.querySelector('.card-slider');
            if (cardSlider) {
              cardSlider.style.display = '';
            }
          });

          function searchAddrFromCoords(coords, callback) {
            geocoder.coord2RegionCode(coords.getLng(), coords.getLat(), callback);
          }

          function displayCenterInfo(result, status) {
            if (status === kakao.maps.services.Status.OK) {
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
              $('#myLocation').html(fRemoveBigAdrJS(currentDong));
              var jibun = !!result[0].address ? result[0].address.address_name : '';
              var doro = !!result[0].road_address ? result[0].road_address.address_name : '';
              // updateAddrInfo('', doro, jibun, currentDong, currentLongitude, currentLatitude);
            }
          }

          function isLocationBridgeReady() {
            return (
              (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK && typeof APP_NOWAPK.getLocation === 'function') ||
              (window.webkit && window.webkit.messageHandlers && (window.webkit.messageHandlers.startGettingLocation || window.webkit.messageHandlers.getLocation))
            );
          }

          function requestBridgeLocation() {
            if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK && typeof APP_NOWAPK.getLocation === 'function') {
              APP_NOWAPK.getLocation();
              return true;
            }
            if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.startGettingLocation) {
              window.webkit.messageHandlers.startGettingLocation.postMessage({});
              return true;
            }
            if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.getLocation) {
              window.webkit.messageHandlers.getLocation.postMessage({});
              return true;
            }
            return false;
          }

          function waitForLocationBridgeReady(timeoutMs, intervalMs, onReady, onTimeout) {
            var startedAt = Date.now();
            (function checkReady() {
              if (isLocationBridgeReady()) {
                onReady();
                return;
              }
              if (Date.now() - startedAt >= timeoutMs) {
                if (typeof onTimeout === 'function') {
                  onTimeout();
                }
                return;
              }
              setTimeout(checkReady, intervalMs);
            })();
          }

          function applyRealtimeLocation(lat, lng) {
            var nLat = parseFloat(lat);
            var nLng = parseFloat(lng);
            if (!isFinite(nLat) || !isFinite(nLng) || nLat === 0 || nLng === 0) {
              console.log('[job_map][location] invalid location payload:', lat, lng);
              return;
            }
            console.log('[job_map][location] apply:', nLat, nLng);
            currentLatitude = nLat;
            currentLongitude = nLng;
            var latlng = new kakao.maps.LatLng(nLat, nLng);
            map.setCenter(latlng);
            searchAddrFromCoords(latlng, displayCenterInfo);
            selectClusterByCoordinates();
          }

          window.returnLocation = function(lat, lng) {
            console.log('[job_map][location] returnLocation called:', lat, lng);
            applyRealtimeLocation(lat, lng);
          };

          function initRealtimeLocationFromBridge() {
            var status = null;
            try {
              status = localStorage.getItem("nowjob_location_permission_status");
            } catch (e) {}
            if (status !== "granted") {
              console.log('[job_map][location] permission not granted:', status);
              return;
            }
            console.log('[job_map][location] permission granted, requesting bridge location');
            var tryRequest = function() {
              requestBridgeLocation();
            };
            if (isLocationBridgeReady()) {
              tryRequest();
            } else {
              waitForLocationBridgeReady(1500, 100, tryRequest, function() {});
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
            const toleranceKm = 3;

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
            fetchRecruitList();
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

          window.insertList = function() {
            var tmpContent = '';
            var listNo = 0;
            document.querySelector('.slider-wrap').innerHTML = '';
            recruitlist.forEach(function(d) {
              tmpContent +=
                '<div class="keen-slider__slide">' +
                '  <div class="content-li" data-rc_idx="' + d.rc_idx + '" id="content-li-' + listNo + '">' +
                '  	<div class="content-info">' +
                '  	  <div class="content-body">' +
                '   		<div class="img">' +
                '   		  <img src="' + d.rt_recruit_img + '">' +
                <?php if ($mm_type === "MEM") { ?> '   		  <button type="button" data-bk_type="RECRUIT" data-bk_key="' + d.rc_idx + '" class="btnBookMark btn-bookmark js-toggle-bookmark ' + (d.book_yn == 'Y' ? 'on' : '') + '"><span class="sr-text">즐겨찾기 추가</span></button>' +
                <?php } ?> '   		</div>' +
                '    		<div class="desc">' +
                '    		  <div class="wicon">' + d.rt_work_img + '</div>' +
                '    		  <div class="title ellipsis">' + d.rc_name + '</div>' +
                '    		  <div class="summary">' +
                '    			  <p>' + (d.rc_nation_nm == '무관' ? '국적무관' : d.rc_nation_nm) + '</p>' +
                '    			  <p>' + (d.rc_age_nm == '무관' ? '나이무관' : d.rc_age_nm) + '</p>' +
                '    		  </div>' +
                '    		  <div class="summary area">' +
                '    			  <p>' + fRemoveBigAdrJS(d.ad_addr) + '</p>' +
                '    		  </div>' +
                '    		  <div class="summary2">' +
                '			      <p class="period">' + (getConvertPriceType(d.rc_price_type, d.rc_price_rep)) + '</p>' +
                '			      <p class="amount">' + (getConvertPrice(d.rc_price_pay)) + '</p>' +
                '    		  </div>' +
                '     	</div>' +
                '  	  </div>' +
                '  	  <div class="summary3">' + getWorkType(d.rt_work_type) + '</div>' +
                '  	</div>' +
                '  </div>' +
                '</div>';
              listNo++;
            });

            document.querySelector('.slider-wrap').insertAdjacentHTML('beforeend', tmpContent);
            if (typeof KeenSlider !== 'undefined') {
              cardSlider = new KeenSlider('.card-slider .slider-wrap', {
                loop: (recruitlist.length == 1 ? false : true),
                slides: {
                  origin: 'center',
                  perView: 1.2,
                  spacing: 10,
                },
                created: function(slider) {
                  slider.container.parentNode.classList.add('initialized');
                },
              }, [addActiveClass, ]);
            }
          };


          initRealtimeLocationFromBridge();
        });

      });
    </script>

  </div>
</div>

<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<script>
  window.addEventListener('DOMContentLoaded', function() {
    // 초기 주소를 단축 형식으로 포맷팅
    setTimeout(function() {
      var initialAddr = $('#myLocation').text();
      if (initialAddr && initialAddr !== "서울 중구 태평로1가") {
        $('#myLocation').text(fRemoveBigAdrJS(initialAddr));
      }
    }, 100);

    fetchRecruitPin();
  });

  window.fetchRecruitPin = function() {
    $.ajax({
      url: "/recruit_proc/getPins",
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

  window.fetchRecruitList = function() {
    var params = {
      mapLevel: currentLevel,
    };

    $.ajax({
      url: "/recruit_proc/search_save_params2025",
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

    var params = {
      "pins[]": pins
    };
    $.ajax({
      url: "/recruit_proc/getPinsMapList",
      type: "post",
      data: params,
      traditional: true,
      dataType: "json",
      crossDomain: true,
      success: function(json) {
        if (json.code === "00" && json.data && Array.isArray(json.data.list)) {
          recruitlist.length = 0;
          json.data.list.forEach(function(item) {
            recruitlist.push(item);
          });
          insertList();
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
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

  function getConvertPriceType(rc_price_type, rc_price_rep) {

    if (rc_price_type == "협의") {
      return '';
    }

    const types = rc_price_type.split(',');

    if (types.length === 1) {
      return `<em class="on">${types[0]}</em>`;
    }

    return types.map(type => {
      if (type === rc_price_rep) {
        return `<em class="on">${type}</em>`;
      } else {
        return `<em>${type}</em>`;
      }
    }).join('');
  }

  function getConvertPrice(rc_price_pay) {

    if (!rc_price_pay) {
      return '급여협의';
    }

    return Number(rc_price_pay).toLocaleString() + "원";
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

  function numberWithCommas(x) {
    if (typeof x !== 'number') {
      x = parseFloat(x);
    }
    if (isNaN(x)) return '';
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function fWorkColor(arg) {
    if (arg === "주간,야간,2교대" || !arg) {
      return "<font style='color:#333;'>근무협의</font>";
    }
    arg = arg.replaceAll(",", "");
    arg = arg.replaceAll("주간", "<font style='color:#3a70db;'>주간</font>");
    arg = arg.replaceAll("야간", "<font style='color:#F86048;'>야간</font>");
    arg = arg.replaceAll("2교대", "<font style='color:#1B9D7D;'>2교대</font>");
    return arg;
  }

  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li, .content-li2').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.location.href = url;
    }
  });
</script>
