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
  .page-map .working-day>li:last-child {
    margin-right: 0;
  }

  
  .result-list-card .content-info .summary p:last-child {
        padding-right: 0;
  }
  .result-list-card .content-info .summary{
    font-size: 15px;
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
      <div class="slider-wrap keen-slider result-list-card">
      </div>
    </div>

    <div class="map">
      <div id="jsMap" class="js-map"></div>
    </div>

    <script>
      var data = [];
      var resumelist = [];
      var currentLatitude = <?= $ad_gps_y > 1 ? $ad_gps_y : 37.5665 ?>;
      var currentLongitude = <?= $ad_gps_x > 1 ? $ad_gps_x : 126.9780 ?>;
      var currentLevel = 8; // 진입 시 2km 수준으로 더 줌 (필요 시 조정)
      var currentDong = "<?= isset($ad_addr) && $ad_addr != "" ? $ad_addr : "서울특별시 중구 태평로1가" ?>";
      var pins = [];
      var cardSlider = null; // 카드 슬라이더(KeenSlider) 인스턴스 — 재생성 전 destroy용

      // SDK 로드와 핀 AJAX를 동시에 진행하고, 둘 다 끝나면 핀을 그린다
      var mapReady = false;  // 카카오맵 초기화 완료 여부
      var pinsReady = false; // 핀 데이터 수신 완료 여부
      function tryDrawPin() {
        if (mapReady && pinsReady && typeof window.drawPin === 'function') {
          window.drawPin();
        }
      }

      // 카카오 SDK 비동기 로드 (본문 파싱을 막지 않음)
      window.loadKakaoMapSdk('<?= $kakao_key["인증키"] ?>', initHrMap, 'clusterer,services');

      function initHrMap() {
        (function() {
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

          window.drawPin = function() {
            clearMarkers();
            data.forEach(function(d) {
              // 2025.02.10 위치 보안 및 시각적 효과를 위해 마커 위치를 랜덤하게 산개 (Jittering)
              // 0.003도 약 300m 범위 내 랜덤 분포
              var lat = parseFloat(d.ad_gps_y);
              var lng = parseFloat(d.ad_gps_x);
              var scatterFactor = 0.01; 
              var rLat = lat + (Math.random() - 0.5) * scatterFactor;
              var rLng = lng + (Math.random() -0.5) * scatterFactor;
              var marker = new kakao.maps.Marker({
                position: new kakao.maps.LatLng(rLat, rLng)
              });
              marker.uid = d.ru_idx;
              markers.push(marker);
            });
            clusterer.addMarkers(markers);
            // 클러스터가 이미 계산됐으면 바로 선택, 아니면 'clustered' 이벤트에서 선택
            // (핀이 지도 준비 직후 도착하면 클러스터러가 아직 계산 전이라 _clusters가 비어 있음)
            if (clusterer._clusters && clusterer._clusters.length > 0) {
              runAutoSelect();
            } else {
              requestAutoSelect();
            }
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
            fetchResumeList();
            // 클러스터 클릭 시 card-slider 표시
            var cardSlider = document.querySelector('.card-slider');
            if (cardSlider) {
              cardSlider.style.display = '';
            }
          }); 
          // 좌표 → 행정구역 이름으로 바꾸는 함수
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
              console.log('[hr_map][location] invalid location payload:', lat, lng);
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

          // 현재 좌표에서 가장 가까운 클러스터를 선택하고 목록을 불러온다 (선택 성공 시 true)
          // 이력서는 공고보다 드물고 핀을 무작위로 흩어 찍으므로 3km 제한을 두지 않고,
          // 화면에 계산된 클러스터 중 가장 가까운 것을 고른다
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

            for (let i = 0; i < clusters.length; i++) {
              const cluster = clusters[i];
              const center = cluster.getCenter();

              const distance = getDistanceKm(
                targetLatLng.getLat(), targetLatLng.getLng(),
                center.getLat(), center.getLng()
              );

              if (distance < minDistance) {
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
            // 지도 중심 좌표 → 행정구역 이름 만들기 → 화면에 출력
            searchAddrFromCoords(map.getCenter(), displayCenterInfo);
            fetchResumeList();
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
                selectedCluster = null;
              }
            }
            // 자동 선택이 클러스터 계산을 기다리고 있었으면 지금 실행
            if (autoSelectPending && clusters && clusters.length > 0) {
              runAutoSelect();
            }
          });

          // 카드 HTML을 문자열로 모아 한 번에 삽입 (카드마다 jQuery 파싱/append 하면 리플로우가 N번 발생)
          window.insertList = function() {
            // 템플릿은 루프 밖에서 한 번만 읽고, 바깥 <li class="list-item"> 래퍼도 치환 전에 한 번만 제거
            // (치환 후에 <li>를 지우면 ru_week의 요일 <li>까지 지워져 .working-day 스타일이 깨짐)
            var templateHtml = document.getElementById('tpl_map_card').innerHTML
              .replace(/<li class="list-item"[^>]*>/, '')
              .replace(/<\/li>\s*$/, '');
            var fragments = '';

            resumelist.forEach(function(d) {
              var $templateContent = templateHtml;

              // 추천 뱃지: pm_idx 없으면 recomm 클래스 및 뱃지 제거
              if (d.pm_idx == null) {
                $templateContent = $templateContent.replaceAll('<div class="content-li recomm"', '<div class="content-li"');
                $templateContent = $templateContent.replaceAll('<p class="recomm-badge"><span>추천</span></p>', '');
              }

              // 나이 단위 "대"로 통일
              var ageStr = (d.mm_age || '') + '대';

              // 국적 빈값 처리
              if (!d.mm_nation || d.mm_nation.trim() === '') {
                $templateContent = $templateContent.replaceAll('{{mm_nation}}', '');
              } else {
                $templateContent = $templateContent.replaceAll('{{mm_nation}}', d.mm_nation);
              }

              $templateContent = $templateContent.replaceAll('{{ru_idx}}',        d.ru_idx || '');
              $templateContent = $templateContent.replaceAll('{{book_yn}}',       d.book_yn == 'Y' ? 'on' : '');
              $templateContent = $templateContent.replaceAll('{{mm_name}}',       d.ru_name || '');
              $templateContent = $templateContent.replaceAll('{{ru_week}}',       d.ru_week || '');
              $templateContent = $templateContent.replaceAll('{{ru_work_img}}',   d.ru_work_img || '');
              $templateContent = $templateContent.replaceAll('{{ma_age}}',        ageStr);
              $templateContent = $templateContent.replaceAll('{{ad_addr}}',       fRemoveBigAdrJS(d.ad_addr || ''));
              $templateContent = $templateContent.replaceAll('{{ru_price_type}}', getPriceType(d.ru_price_type));
              $templateContent = $templateContent.replaceAll('{{ru_work_type}}',  getWorkType(d.ru_work_type));
              $templateContent = $templateContent.replaceAll('{{ru_resume_img}}', d.ru_resume_img || '');
              $templateContent = $templateContent.replaceAll('{{ru_recom_badge}}', d.ru_recom_badge || '');

              fragments += '<div class="keen-slider__slide">' + $templateContent + '</div>';
            });

            // 이전 슬라이더의 이벤트 리스너를 해제 (destroy 없이 재생성하면 리스너가 누적되어 점점 느려짐)
            if (cardSlider && typeof cardSlider.destroy === 'function') {
              cardSlider.destroy();
            }
            cardSlider = null;

            document.querySelector('.slider-wrap').innerHTML = fragments;

            if (typeof KeenSlider !== 'undefined') {
              cardSlider = new KeenSlider('.card-slider .slider-wrap', {
                loop: (resumelist.length == 1 ? false : true),
                slides: {
                  origin: 'center',
                  perView: 1.2,
                  spacing: 10,
                },
                created: function(slider) {
                  slider.container.parentNode.classList.add('initialized');
                },
              }, [addActiveClass]);
            }
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
      dataType: "json",
      crossDomain: true,
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
          // 핀 수신 완료 → 지도가 준비돼 있으면 바로 그림
          pinsReady = true;
          tryDrawPin();
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

  window.fetchResumeList = function() {
    var pinsKey = pins.slice().sort(function(a, b) {
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
      "currentLatitude": currentLatitude,
      "currentLongitude": currentLongitude,
      "pins[]": pins
    };
    listXhr = $.ajax({
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
          insertList();
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
          $('#myLocation').html(replaceRegionNames(addr));
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

    // 쉼표로 구분된 문자열 처리
    const types = work_type.split(',').map(item => item.trim()).filter(item => item);

    return types.map(text => { // map:배열(Array)의 각 요소를 → 다른 값으로 변환하는 함수
      let cls = '';
      if (text === '주간') {
        cls = 'wtype1';
      } else if (text === '야간') {
        cls = 'wtype2';
      } else if (text === '2교대') {
        cls = 'wtype3';
      }
      return `<em class="${cls}">${text}</em>`;
    }).join(''); // join:배열(Array)을 → 하나의 문자열(String)로 합치는 함수
  }

  $(document).on('click', '.content-li', function(e) {
    // 버튼이나 링크 클릭은 무시
    if ($(e.target).closest('button, a, input').length) {
      return;
    }

    var ruIdx = $(this).data('ru_idx');

    <?php if ($mm_type !== "COMP") { ?>
      commonAlert("기업회원 전용 서비스입니다.", function() {});
    <?php } else { ?>
      if (ruIdx) {
        window.location.href = '/resume/hr_post/' + ruIdx;
      }
    <?php } ?>
  });
</script>

<?php
$tpl_id           = 'tpl_map_card';
$tpl_recomm_class = true;
$tpl_show_mm_idx  = false;
$tpl_show_toggle  = false; // 지도 페이지에서는 펼침/접힘 토글 버튼 숨김
?>
<?php include(APPPATH . 'views/mobile/_partial/resume_card_template.php'); ?>
