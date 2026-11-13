<?php
// 주소 포맷팅은 JavaScript의 fRemoveBigAdrJS 함수에서 처리
?>
<!-- 카카오 지도 SDK/본체/타일 서버에 미리 연결 (DNS·TLS 연결 시간 단축) -->
<link rel="preconnect" href="https://dapi.kakao.com">
<link rel="preconnect" href="https://t1.kakaocdn.net">
<?= getCachCss(RES_PATH . "/css/m_card_v2.css") ?>
<?= getCachCss(RES_PATH . "/css/m_map_v2.css") ?>
<style>
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
    border-style: none;
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

  /* 카드 슬라이더: 브라우저 기본 가로 스크롤 + 스냅
     (keen-slider는 매 프레임 모든 카드 위치를 JS로 계산 → 카드 357장에서 초당 3프레임으로 끊김.
      네이티브 스크롤은 JS 계산 없이 브라우저가 처리하므로 카드 수와 무관하게 부드러움) */
  .page-map .card-slider {
    --map-card-w: calc((100vw - 2px) / 1.2); /* 1.2장 보기 (기존 perView: 1.2) */
  }

  .page-map .card-slider .map-card-track {
    display: flex;
    gap: 10px;
    margin: 0;
    list-style: none;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior-x: contain;
    scrollbar-width: none;
    /* 양옆 여백: 첫/마지막 카드도 화면 가운데에 멈추도록 (기존 origin: 'center') */
    padding-left: calc((100vw - var(--map-card-w)) / 2);
    padding-right: calc((100vw - var(--map-card-w)) / 2);
  }

  .page-map .card-slider .map-card-track::-webkit-scrollbar {
    display: none;
  }

  .page-map .card-slider .map-card-track > .list-item {
    flex: 0 0 var(--map-card-w);
    /* flex 항목은 기본 min-width:auto라 긴 제목(nowrap) 폭만큼 늘어남 → 폭 고정해야 말줄임(ellipsis) 동작
       (keen-slider는 슬라이드에 min/max-width를 인라인으로 고정했었음) */
    min-width: 0;
    max-width: var(--map-card-w);
    padding: 2rem 0; /* 기존 .card-slider .keen-slider__slide 여백 */
    scroll-snap-align: center;
    /* 화면 밖 카드는 그리기 생략 (카드 수백 장일 때 렌더링 비용 감소) */
    content-visibility: auto;
    contain-intrinsic-size: auto 189px;
  }

  /* 카드 하나를 다시 그릴 때 영향 범위를 카드 안으로 한정 */
  .page-map .card-slider .content-li {
    contain: layout paint;
  }

  /* 제목 줄(.title-wrap)도 flex라, 제목에 min-width:0이 있어야 카드 폭 안에서 말줄임표(…) 표시 */
  .page-map .card-slider .content-li .title-wrap .title {
    flex: 1 1 auto;
    min-width: 0;
  }

  /* 주소 로딩 상태 */
  #myLocation[data-loading="true"] {
    min-height: 0;
    visibility: hidden;
  }
</style>
<div class="backdrop-loader" hidden>
  <div class="loader show"></div>
</div>

<div class="page page-map">
  <div class="top-bar is-transparency">
    <a href="#" class="btn-back" style="background-image: url('/resource/shop/mobile/img/ic_back_dark.svg');"><span class="sr-text">이전으로</span></a>
    <h2 class="current-area">
      <span id="myLocation" class="myLocationBtn" data-loading="true"></span>
    </h2>
  </div>

  <div class="page-content">

    <div class="card-slider">
      <ol class="slider-wrap map-card-track result-list-card mlv2-rc" id="ajaxList">
      </ol>
    </div>

    <div class="map">
      <div id="jsMap" class="js-map"></div>
    </div>

    <script>
      var data = [];
      var currentLatitude = <?= $ad_gps_y > 1 ? $ad_gps_y : 37.5665 ?>;
      var currentLongitude = <?= $ad_gps_x > 1 ? $ad_gps_x : 126.9780 ?>;
      var currentLevel = 8; // 진입 시 2km 수준으로 더 줌 (필요 시 조정)
      var currentDong = "<?= isset($ad_addr) && $ad_addr != "" ? $ad_addr : "서울특별시 중구 태평로1가" ?>";
      var pins = [];

      // SDK 로드와 핀 AJAX를 동시에 진행하고, 셋 다 준비되면 핀을 그린다
      var mapReady = false;   // 카카오맵 초기화 완료 여부
      var pinsReady = false;  // 핀 데이터 수신 완료 여부
      var tilesReady = false; // 지도 타일이 처음 화면에 그려졌는지
      // (마커 생성·클러스터 계산이 1초 가까이 걸려 그동안 지도 타일을 못 그림 → 지도를 먼저 보여준 뒤 핀을 그림)
      function tryDrawPin() {
        if (mapReady && pinsReady && tilesReady && typeof window.drawPin === 'function') {
          window.drawPin();
        }
      }

      // 카카오 SDK 비동기 로드 (본문 파싱을 막지 않음)
      window.loadKakaoMapSdk('<?= $kakao_key["인증키"] ?>', initJobMap, 'clusterer,services');

      function initJobMap() {
        (function() {
          var map = new kakao.maps.Map(document.getElementById('jsMap'), {
            center: new kakao.maps.LatLng(currentLatitude, currentLongitude),
            level: currentLevel,
            minLevel: 5,
            maxLevel: 10,
          });
          // 지도 타일이 처음 그려지면 그때 핀을 그림 (타일 이벤트가 안 오면 1.5초 뒤 그림)
          function onFirstTiles() {
            if (tilesReady) return;
            tilesReady = true;
            tryDrawPin();
          }
          kakao.maps.event.addListener(map, 'tilesloaded', onFirstTiles);
          setTimeout(onFirstTiles, 1500);
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
          var autoSelectPending = false; // 자동 선택 대기 (클러스터가 아직 계산 전이거나, 지도 이동 후 재계산 대기)
          var autoSelectTimer = null;    // 재계산 이벤트가 오지 않을 때를 대비한 안전 타이머

          // 자동 선택 실행: 클러스터를 못 고르면 상단 주소만 현재 좌표 기준으로 갱신
          function runAutoSelect() {
            autoSelectPending = false;
            if (autoSelectTimer) {
              clearTimeout(autoSelectTimer);
              autoSelectTimer = null;
            }
            if (!selectClusterByCoordinates()) {
              searchAddrFromCoords(new kakao.maps.LatLng(currentLatitude, currentLongitude), displayCenterInfo);
            }
          }

          // 자동 선택 예약: 다음 'clustered' 이벤트에서 실행, 1초 안에 안 오면 타이머로 실행
          function requestAutoSelect() {
            autoSelectPending = true;
            if (autoSelectTimer) {
              clearTimeout(autoSelectTimer);
            }
            autoSelectTimer = setTimeout(function() {
              if (autoSelectPending) {
                runAutoSelect();
              }
            }, 1000);
          }

          function clearMarkers() {
            for (let marker of markers) {
              marker.setMap(null);
            }
            markers.length = 0;
          }

          // 핀 좌표 목록 (added = 마커로 만들었는지)
          var pinPoints = [];
         // drawPin() 함수에서 채워지는 방식 (372줄)
          // pinPoints.push({ 
          //   uid: d.rc_idx,      // 공고 번호
          //   lat: lat,           // 위도
          //   lng: lng,           // 경도
          //   added: false        // 마커로 만들었는지 여부
          // });

          var MARKER_BATCH = 100; // 한 번에 만드는 마커 수 (마커 1개 생성 ≈ 0.4ms → 100개 ≈ 40ms)
          var markerJob = null;   // 현재 일하는 중인지 기록

          
          // 마커를 100개씩 나눠서 클러스터러에 넘기기
          
          function addMarkersInView(onDone) {
            // 이미 마커를 만드는 중이면 끝난 뒤 새 화면 범위를 한 번 더 확인
            if (markerJob) {
              // 이미 만드는 중이면 끝난 뒤 새 화면 범위를 한 번 더 확인
              markerJob.rescan = true;
              // 끝나고 실행할 함수(알림 등)가 있다면 줄을 세워둠
              if (onDone) markerJob.callbacks.push(onDone);
              return; // 지금 함수는 여기서 끝!
            }

            var bounds = map.getBounds(); // 1️⃣ 지도의 현재 범위 구하기
            var sw = bounds.getSouthWest();  // 왼쪽 아래
            var ne = bounds.getNorthEast(); // 오른쪽 위
            var latPad = (ne.getLat() - sw.getLat()) * 0.5;
            var lngPad = (ne.getLng() - sw.getLng()) * 0.5;
            var minLat = sw.getLat() - latPad; // 남쪽 경계 (더 남쪽으로)
            var maxLat = ne.getLat() + latPad;
            var minLng = sw.getLng() - lngPad;
            var maxLng = ne.getLng() + lngPad;
            // 1. pinPoints에서 화면 범위 내의 핀만 필터링
            // 이번에 만들 핀 목록
            var todo = [];
            for (var i = 0; i < pinPoints.length; i++) {
              var p = pinPoints[i];
              // added = false인 핀만 마커로 만들고, 화면 범위 안에 있는지 확인
              if (!p.added && p.lat >= minLat && p.lat <= maxLat && p.lng >= minLng && p.lng <= maxLng) {
                todo.push(p);
              }
            }

            markerJob = { rescan: false, callbacks: onDone ? [onDone] : [] };
            var idx = 0;
             // 2. 100개씩 나눠서 마커 객체로 변환
            function step() {
              var end = Math.min(idx + MARKER_BATCH, todo.length);
              var batch = [];
              for (; idx < end; idx++) {
                var pt = todo[idx];
                var marker = new kakao.maps.Marker({
                  position: new kakao.maps.LatLng(pt.lat, pt.lng)
                });
                marker.uid = pt.uid;
                pt.added = true;
                markers.push(marker);
                batch.push(marker);
              }
              if (batch.length > 0) {
                clusterer.addMarkers(batch, true); // true = 추가만 하고 다시 그리지 않음
              }
              if (idx < todo.length) {
                setTimeout(step, 0); // 다음 묶음 전에 브라우저에 차례를 넘김 (터치·화면 갱신 처리)
                return;
              }

              // 모두 만들었으면 클러스터를 한 번만 다시 그림
              if (todo.length > 0) {
                clusterer.redraw(); // 마커들을 화면에 다시 배치하고 표시하는 함수 새로운 상태를 반영해서 화면을 갱신
              }
              var job = markerJob;
              markerJob = null;
              job.callbacks.forEach(function(cb) {
                cb();
              });
              if (job.rescan) {
                addMarkersInView();
              }
            }
            step();
          }
         // 핀 데이터를 카카오 마커 객체로 변환
          window.drawPin = function() {
            clearMarkers();
            clusterer.clear();
            pinPoints = [];
            data.forEach(function(d) {
              var lat = parseFloat(d.ad_gps_y);
              var lng = parseFloat(d.ad_gps_x);
              if (!isFinite(lat) || !isFinite(lng)) {
                return;
              }
              pinPoints.push({ uid: d.rc_idx, lat: lat, lng: lng, added: false });
            });
            // 마커를 나눠 만든 뒤(비동기) 클러스터가 그려지면 내 위치 근처 클러스터를 자동 선택
            // drawPin() 함수에서 마커 생성 완료 후 실행할 작업을 지정
            addMarkersInView(function() {
              // 클러스터가 이미 계산됐으면 바로 선택, 아니면 'clustered' 이벤트에서 선택
              if (clusterer._clusters && clusterer._clusters.length > 0) { // ↑ 클러스터가 존재하고 ↑ 개수가 1개 이상이면
                runAutoSelect(); // ← 사용자 위치 근처 클러스터 자동 선택
              } else {
                requestAutoSelect();
              }
            });
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
            // 지도를 옮기거나 축소해 새로 보이는 영역의 핀을 마커로 추가
            if (pinPoints.length > 0) {
              addMarkersInView();
            }
          });
          // 지도 드래그 시작 시 card-slider 숨김 처리
          kakao.maps.event.addListener(map, 'dragstart', function() {
            var cardSlider = document.querySelector('.card-slider');
            if (cardSlider) {
              cardSlider.style.display = 'none';
            }
          });
          // 클러스터 클릭 시 해당 클러스터의 핀 목록을 가져와 카드 슬라이드 표시
          kakao.maps.event.addListener(clusterer, 'clusterclick', function(cluster) {
          // 클러스터 클릭 시 지도 확대는 기본 동작이므로, 카드 슬라이드 표시만 처리  
          // 클릭한 클러스터 안의 마커 uid들 추출
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
            // 이 공고/인재들의 상세 정보를 서버에서 받아 카드 슬라이드에 표시
            fetchRecruitList();
            // 클러스터 클릭 시 card-slider 표시
            var cardSlider = document.querySelector('.card-slider');
            if (cardSlider) {
              cardSlider.style.display = '';
            }
          });
          // 지도 중심 좌표로 주소를 검색
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
            currentLatitude = nLat;
            currentLongitude = nLng;
            // 지도를 옮긴 직후의 클러스터 목록은 "이동 전 화면" 기준이므로,
            // 새 위치 기준으로 다시 계산된 뒤(clustered)에 선택하도록 예약
            requestAutoSelect();
            map.setCenter(new kakao.maps.LatLng(nLat, nLng));
          }

          window.returnLocation = function(lat, lng) {
            applyRealtimeLocation(lat, lng);
          };

          function initRealtimeLocationFromBridge() {
            var status = null;
            try {
              status = localStorage.getItem("nowjob_location_permission_status");
            } catch (e) {}
            if (status !== "granted") {
              return;
            }
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
          // 사용자 위치 근처의 클러스터 찾아서 빨간색으로 표시
          // 현재 좌표 3km 이내의 가장 가까운 클러스터를 선택하고 목록을 불러온다 (선택 성공 시 true)
          function selectClusterByCoordinates() {
            if (typeof currentLatitude !== 'number' || typeof currentLongitude !== 'number') {
              return false;
            }

            const targetLatLng = new kakao.maps.LatLng(currentLatitude, currentLongitude);
            const clusters = clusterer._clusters;

            if (!clusters || clusters.length === 0) {
              return false;
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
              return false;
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
            return true;
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
                // 마커 추가 등으로 클러스터가 새로 만들어진 경우: 선택했던 핀이 든 클러스터를 다시 찾아 강조
                const firstUid = pins.length > 0 ? pins[0] : null;
                const again = firstUid === null ? null : clusters.find(function(c) {
                  return c.getMarkers().some(function(m) { return m.uid === firstUid; });
                });
                selectedCluster = again || null;
                if (again) {
                  highlightClusterStyle(again);
                }
              }
            }
            // 자동 선택이 클러스터 계산을 기다리고 있었으면 지금 실행
            if (autoSelectPending && clusters && clusters.length > 0) {
              runAutoSelect();
            }
          });

          // 하단 카드: 서버가 그린 채용목록 카드 HTML을 그대로 넣고 가로 스크롤(scroll-snap)로 넘김
          window.insertList = function(html) {
            var track = document.getElementById('ajaxList');
            track.innerHTML = html;
            track.scrollLeft = 0; // 새 클러스터는 첫 카드부터 보이게
            // .card-slider는 initialized 클래스가 붙어야 보임 (main.css)
            document.querySelector('.card-slider').classList.add('initialized');
          };


          // 지도 준비 완료 → 핀 데이터가 먼저 도착해 있으면 바로 그림
          mapReady = true;
          tryDrawPin();
          initRealtimeLocationFromBridge();
        })();
      }
    </script>

  </div>
</div>

<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<script>
  function loadInitialAddress() {
    $.ajax({
      url: "/common_proc/get_addr_info",
      type: "post",
      dataType: "json",  // ③ 응답 데이터 형식 (JSON)
      crossDomain: true,  // ④ CORS 요청 허용
      success: function(json) {
        if (json.code === "00" && json.data && json.data.ad_addr) {
          var addr = json.data.ad_addr;
          $('#myLocation')
            .text(fRemoveBigAdrJS(addr))
            .removeAttr('data-loading');
        } else {
          $('#myLocation')
            .text("")
            .removeAttr('data-loading');
        }
      },
      error: function(xhr, status, error) {
        $('#myLocation')
          .text("")
          .removeAttr('data-loading');
      }
    });
  }

  window.addEventListener('DOMContentLoaded', function() {
    loadInitialAddress();
    fetchRecruitPin();
  });
 // 서버에서 핀 데이터 받기 (AJAX)
 // 페이지 진입 시 1회만 호출
 // 위치만 받아서 지도에 표시하고({rc_idx: 7, gps_x: 127.0, gps_y: 37.0}), 클러스터 클릭 시 상세 목록을 AJAX로 받아 카드 슬라이드에 표시
 // 지도에 핀(클러스터) 표시
  window.fetchRecruitPin = function() {
    $.ajax({
      url: "/recruit_proc/getPins", // ← 공고 위치 데이터 요청 핀 데이터 = 각 공고의 위치 정보(위도/경도) + 공고 번호
      type: "post",
      traditional: true,
      dataType: "json",
      crossDomain: true,
      success: function(json) {
        if (json.code === "00" && json.data && Array.isArray(json.data)) {
          data.length = 0;
          json.data.forEach(function(item) {
            data.push(item);  // ← 새로운 데이터만 추가
          });
          // 핀 수신 완료 → 지도가 준비돼 있으면 바로 그림
          pinsReady = true; // ← 준비 완료 신호
          tryDrawPin(); // ← 지도에 초록 원 그리기
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  // 클러스터 목록 요청 중복 방지: 마지막으로 요청한 pins 키와 진행 중인 요청
  var lastPinsKey = '';
  var listXhr = null;
  //  서버에서 핀 데이터(위치+공고번호) 받기 / 공고 상세 정보 {rc_idx: 7, rc_title: "개발자", rc_name: "홍길동", ...} 목록 받기
  // 클러스터 클릭할 때마다 호출 / 하단 카드 슬라이더 표시
  window.fetchRecruitList = function() {
    var pinsKey = pins.slice().sort(function(a, b) {  // ← 클릭한 클러스터의 공고 번호들
      return a - b;
    }).join(',');
    if (pinsKey === '' || pinsKey === lastPinsKey) {
      return; // 같은 클러스터 재선택 → 이미 받은 목록 유지
    }
    if (listXhr) {
      listXhr.abort(); // 이전 클러스터 요청은 취소하고 마지막 선택만 처리
    }
    lastPinsKey = pinsKey;

    var params = {
      "pins[]": pins,
      "render": "html"
    };
    listXhr = $.ajax({
      url: "/recruit_proc/getPinsMapList",
      type: "post",
      data: params,
      traditional: true,
      dataType: "json",
      crossDomain: true,
      success: function(json) {
        if (json.code === "00" && json.data && typeof json.data.list_html === "string") {
          insertList(json.data.list_html);  // ← 하단 카드로 표시
        }
      },
      error: function(xhr, status, error) {
        if (status === 'abort') {
          return;
        }
        lastPinsKey = ''; // 실패 시 같은 클러스터를 다시 눌러 재시도할 수 있게
        console.error("데이터 가져오기 오류:", error);
      },
      complete: function(xhr) {
        if (listXhr === xhr) {
          listXhr = null;
        }
      }
    });
  }
  // 주소 정보 업데이트 (AJAX)
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
  // 카드 클릭 시 상세 페이지로 이동
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li, .content-li2').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.location.href = url;
    }
  });
</script>
<?= getCashJs(RES_PATH . "/js/m_map_v2.js") ?>
