<?php
// 주소 포맷팅은 JavaScript의 fRemoveBigAdrJS 함수에서 처리
?>
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
      var recruitlist = [];
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
      window.loadKakaoMapSdk('<?= $kakao_key["인증키"] ?>', initJobMap, 'clusterer,services');

      function initJobMap() {
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
          var autoSelectPending = false; // 첫 진입 자동 선택 대기 (클러스터 계산 완료 전에 핀이 그려진 경우)

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
            // 클러스터가 이미 계산됐으면 바로 선택, 아니면 'clustered' 이벤트에서 선택
            // (핀이 지도 준비 직후 도착하면 클러스터러가 아직 계산 전이라 _clusters가 비어 있음)
            if (clusterer._clusters && clusterer._clusters.length > 0) {
              autoSelectPending = false;
              selectClusterByCoordinates();
            } else {
              autoSelectPending = true;
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
            currentLatitude = nLat;
            currentLongitude = nLng;
            var latlng = new kakao.maps.LatLng(nLat, nLng);
            map.setCenter(latlng);
            // 클러스터가 선택되면 그 안에서 주소를 갱신하므로, 못 찾았을 때만 역지오코딩 (중복 호출 방지)
            if (!selectClusterByCoordinates()) {
              searchAddrFromCoords(latlng, displayCenterInfo);
            }
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
                selectedCluster = null;
              }
            }
            // 첫 진입 자동 선택이 클러스터 계산을 기다리고 있었으면 지금 실행
            if (autoSelectPending && clusters && clusters.length > 0) {
              autoSelectPending = false;
              selectClusterByCoordinates();
            }
          });

          window.insertList = function() {
            document.querySelector('.slider-wrap').innerHTML = '';
            var fragments = '';
            var templateHtml = document.getElementById('tpl_list').innerHTML;

            recruitlist.forEach(function(d, listNo) {
              var $content = templateHtml;

              // 국적
              if (d.rc_nation_nm == '무관' || d.rc_nation_nm == '내국인,외국인') {
                $content = $content.replaceAll('{{rc_nation_nm}}', '국적무관');
              } else {
                $content = $content.replaceAll('{{rc_nation_nm}}', d.rc_nation_nm);
              }

              // 나이
              if (d.rc_age_nm == '무관') {
                $content = $content.replaceAll('{{rc_age_nm}}', '나이무관');
              } else {
                $content = $content.replaceAll('{{rc_age_nm}}', d.rc_age_nm);
              }

              // 직접 삽입 (서버에서 이미 포맷됨)
              $content = $content.replaceAll('{{rc_idx}}',        d.rc_idx);
              $content = $content.replaceAll('{{book_yn}}',       d.book_yn == 'Y' ? 'on' : '');
              $content = $content.replaceAll('{{ad_addr}}',       fRemoveBigAdrJS(d.ad_addr));
              $content = $content.replaceAll('{{rc_name}}',       d.rc_name);
              $content = $content.replaceAll('{{rt_recruit_img}}', d.rt_recruit_img);
              $content = $content.replaceAll('{{rt_work_img}}',   d.rt_work_img);
              $content = $content.replaceAll('{{rt_work_type}}',  d.rt_work_type  || '');
              $content = $content.replaceAll('{{rc_price_type}}', d.rt_price_type || '');
              $content = $content.replaceAll('{{rc_price_pay}}',  d.rt_price_pay  || '');
              $content = $content.replaceAll('{{rt_recom_badge}}', d.rt_recom_badge || '');

              // <li> 태그 제거 후 keen-slider__slide로 감싸기
              var innerHtml = $content.replace(/<li[^>]*>/g, '').replace(/<\/li>/g, '');
              fragments += '<div class="keen-slider__slide" id="content-li-' + listNo + '">' + innerHtml + '</div>';
            });

            // 이전 슬라이더의 이벤트 리스너를 해제 (destroy 없이 재생성하면 리스너가 누적되어 점점 느려짐)
            if (cardSlider && typeof cardSlider.destroy === 'function') {
              cardSlider.destroy();
            }
            cardSlider = null;

            document.querySelector('.slider-wrap').innerHTML = fragments;

            // KeenSlider 재초기화
            if (typeof KeenSlider !== 'undefined') {
              cardSlider = new KeenSlider('.card-slider .slider-wrap', {
                loop: recruitlist.length !== 1,
                slides: { origin: 'center', perView: 1.2, spacing: 10 },
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

  window.fetchRecruitList = function() {
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
      "pins[]": pins
    };
    listXhr = $.ajax({
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
          $('#myLocation').html(fRemoveBigAdrJS(addr));
        }
      },
      error: function(xhr, status, error) {
        console.error("주소 업데이트 오류:", error);
      }
    });
  }

  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li, .content-li2').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.location.href = url;
    }
  });
</script>

<?php $tpl_id = 'tpl_list'; $tpl_recomm_class = false; $tpl_show_toggle = false; // 지도 페이지에서는 토글 버튼 숨김 ?>
<?php include(APPPATH . 'views/mobile/_partial/recruit_card_template.php'); ?>
