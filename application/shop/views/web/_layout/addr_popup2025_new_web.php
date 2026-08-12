<style>
  #changeAreaExpose.layer-panel.card-type .panel-header {
    position: relative;
    padding-top: 1.25rem;
    padding-bottom: 1.25rem;
    background: inherit;
  }

  #changeAreaExpose.layer-panel.card-type .panel-header .panel-title {
    background: inherit;
  }

  #changeAreaExpose.layer-panel.card-type .panel-header .panel-title em {
    font-style: normal;
    color: #1B9D7D;
  }

  #changeAreaExpose.layer-panel.card-type .panel-body {
    padding: 0;
    overflow: hidden;
    overflow-y: hidden;
  }

  #changeAreaExpose.layer-panel.card-type .panel-container {
    max-width: 500px;
    max-height: 710px;
    margin: 0 auto;
    top: 50%;
    left: 20%;
    transform: translate(-50%, -50%);
    bottom: auto;

  }

  #changeAreaExpose .panel-content {
    position: relative;
    height: 100%;
  }

  #changeAreaExpose .result-wrap {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    font-family: sans-serif;
  }

  #changeAreaExpose .search-cont {
    padding: 20px;
    background: #f0f0f0;
    flex: 0 0 auto;
  }

  #changeAreaExpose .search-input {
    display: flex;
    align-items: center;
    height: 100%;
  }

  #changeAreaExpose .search-input input {
    flex: 1;
    height: 45px;
    padding: 5px 10px;
    font-size: 15px;
    background-color: #ffff;
    border-radius: 7px;
  }

  #changeAreaExpose .search-input input::placeholder {
    color: #999;
    opacity: 1;
    font-weight: 500;
  }

  #changeAreaExpose .search-input .icon {
    margin-left: 5px;
  }

  #changeAreaExpose .addr-popup-list {
    flex: 1 1 auto;
    display: flex;
    flex-direction: row;
    min-height: 100px;
    max-height: 400px;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    overflow-y: auto;
  }

  #changeAreaExpose .addr-popup-search,
  #changeAreaExpose .addr-popup-dept,
  #changeAreaExpose .addr-popup-dept1,
  #changeAreaExpose .addr-popup-dept2,
  #changeAreaExpose .addr-popup-dept3 {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  #changeAreaExpose .addr-popup-search {
    width: 100%;
  }

  #changeAreaExpose .addr-popup-dept {
    width: 100%;
    display: flex;
    flex-direction: row;
  }

  #changeAreaExpose .addr-popup-dept1 {
    width: 20%;
    border-right: 1px solid #ccc;
  }

  #changeAreaExpose .addr-popup-dept2 {
    width: 40%;
    border-right: 1px solid #ccc;
  }

  #changeAreaExpose .addr-popup-dept3 {
    width: 40%;
  }

  #changeAreaExpose .addr-popup-title {
    padding: 5px 8px;
    background: #f0f0f0;
    font-weight: bold;
    flex: 0 0 auto;
    border-bottom: 1px solid #ccc;
    text-align: center;
  }

  #changeAreaExpose .addr-popup-search ul,
  #changeAreaExpose .addr-popup-dept1 ul,
  #changeAreaExpose .addr-popup-dept2 ul,
  #changeAreaExpose .addr-popup-dept3 ul {
    flex: 1 1 auto;
    margin: 0;
    padding: 0;
    list-style: none;
    overflow-y: auto;
    scrollbar-width: none;
  }

  #changeAreaExpose .addr-popup-dept1 ul::-webkit-scrollbar,
  #changeAreaExpose .addr-popup-dept2 ul::-webkit-scrollbar,
  #changeAreaExpose .addr-popup-dept3 ul::-webkit-scrollbar,
  #changeAreaExpose .addr-popup-search ul::-webkit-scrollbar {
    width: 0;
    height: 0;
  }

  #changeAreaExpose .addr-popup-dept1 ul li.selectable.addr-popup-on,
  #changeAreaExpose .addr-popup-dept2 ul li.selectable.addr-popup-on {
    background-color: #31A182;
    color: #fff;
  }

  #changeAreaExpose .addr-popup-search ul li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0;
    margin: 10px 0;
    position: relative;
    font-size: 1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
  }

  #changeAreaExpose .addr-popup-search ul li label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    cursor: pointer;
  }

  #changeAreaExpose .addr-popup-search ul li.addr-popup-on label {
    background-color: #fff;
    color: #31A182;
  }

  #changeAreaExpose .addr-popup-search ul li .text {
    flex: 1;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    text-overflow: ellipsis;
    cursor: pointer;
    padding: 0.5rem 1rem;
    padding-left: 2rem;
  }

  #changeAreaExpose .addr-popup-search ul li input[type="checkbox"] {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    position: absolute;
    width: 1.25rem;
    height: 1.25rem;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    z-index: 2;
    cursor: pointer;
  }

  #changeAreaExpose .addr-popup-search ul li label::before {
    content: "";
    display: block;
    position: absolute;
    /*top: 50%;*/
    left: 5px;
    width: 1.25rem;
    height: 1.25rem;
    background: url(/resource/shop/mobile/img/ic_chkbox2025_off.svg) no-repeat 0 0;
    background-size: contain;
    border-radius: 100%;
    z-index: 1;
  }

  #changeAreaExpose .addr-popup-search ul li.addr-popup-on label::before {
    background: url(/resource/shop/mobile/img/ic_chkbox2025_on.svg) no-repeat 0 0;
    background-size: contain;
  }

  #changeAreaExpose .addr-popup-sel-box {
    flex: 0 0 auto;
    max-height: 225px;
    overflow-y: auto;
    padding-bottom: 0;
    box-sizing: border-box;
  }

  #changeAreaExpose .addr-popup-sel-box .addr-popup-div1 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 5px;
  }

  #changeAreaExpose .addr-popup-sel-box .addr-popup-div2 {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 15px;
    margin-left: 10px;
  }

  #changeAreaExpose .addr-popup-dept1 ul li,
  #changeAreaExpose .addr-popup-dept2 ul li,
  #changeAreaExpose .addr-popup-dept3 ul li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 3px;
    position: relative;
    font-size: 1.1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
  }

  #changeAreaExpose .addr-popup-dept1 ul li label,
  #changeAreaExpose .addr-popup-dept2 ul li label,
  #changeAreaExpose .addr-popup-dept3 ul li label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    cursor: pointer;
  }

  #changeAreaExpose .addr-popup-dept1 ul li.addr-popup-on label,
  #changeAreaExpose .addr-popup-dept2 ul li.addr-popup-on label,
  #changeAreaExpose .addr-popup-dept3 ul li.addr-popup-on label {
    background-color: #fff;
    color: #31A182;
  }

  #changeAreaExpose .addr-popup-dept1 ul li .text,
  #changeAreaExpose .addr-popup-dept2 ul li .text,
  #changeAreaExpose .addr-popup-dept3 ul li .text {
    flex: 1;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    cursor: pointer;
    padding: 0.5rem 1rem;
  }

  #changeAreaExpose .addr-popup-dept1 ul li input[type="checkbox"],
  #changeAreaExpose .addr-popup-dept2 ul li input[type="checkbox"],
  #changeAreaExpose .addr-popup-dept3 ul li input[type="checkbox"] {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    position: absolute;
    width: 1.25rem;
    height: 1.25rem;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
    z-index: 2;
    cursor: pointer;
  }

  #changeAreaExpose .addr-popup-dept1 ul li label::after,
  #changeAreaExpose .addr-popup-dept2 ul li label::after,
  #changeAreaExpose .addr-popup-dept3 ul li label::after {
    content: "";
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    width: 1.25rem;
    height: 1.25rem;
    background: url(/resource/shop/mobile/img/ic_chkbox2025_off.svg) no-repeat center center;
    background-size: contain;
    border-radius: 100%;
    z-index: 1;
  }

  #changeAreaExpose .addr-popup-dept1 ul li.addr-popup-on label::after,
  #changeAreaExpose .addr-popup-dept2 ul li.addr-popup-on label::after,
  #changeAreaExpose .addr-popup-dept3 ul li.addr-popup-on label::after {
    background: url(/resource/shop/mobile/img/ic_chkbox2025_on.svg) no-repeat center center;
    background-size: contain;
  }

  #changeAreaExpose .addr-popup-sel-box .addr-popup-div1 {
    padding: 10px 10px;
  }

  #changeAreaExpose .addr-popup-sel-box .addr-popup-div1-title {
    font-weight: 600;
    font-size: 1.1rem;
  }

  #changeAreaExpose .addr-popup-sel-box .addr-popup-sel-cnt {
    margin-left: 10px;
    color: #31A182;
    font-weight: 600;
    margin-right: 10px;
  }

  #changeAreaExpose .addr-popup-sel-box .addr-popup-div1-desc {
    font-size: 0.8rem;
    color: #777;
  }

  #changeAreaExpose .addr-popup-selbtn {
    background-color: #eee;
    color: #666666;
    font-size: 1rem;
    padding: 8px;
    border-radius: 6px;
    position: relative;
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
  }

  #changeAreaExpose .addr-popup-selbtn-close {
    cursor: pointer;
  }

  #changeAreaExpose .addr-popup-sel-box .addr-popup-div3 {
    margin-top: 5px;
    display: flex;
    gap: 10px;
    justify-content: space-between;
    margin-right: 10px;
    margin-left: 10px;
    margin-bottom: 20px;
  }

  #changeAreaExpose .addr-popup-sel-init {
    flex: 3;
  }

  #changeAreaExpose .addr-popup-sel-apply {
    flex: 7;
  }

  #changeAreaExpose .addr-popup-sel-init,
  #changeAreaExpose .addr-popup-sel-apply {
    background-color: #31A182;
    font-size: 1.3rem;
    color: white;
    font-weight: 900;
    text-align: center;
    padding: 1rem 0;
    border-radius: 80px;
    cursor: pointer;

  }

  #changeAreaExpose .search-input {
    position: relative;
  }

  #changeAreaExpose .form-text {
    width: 100%;
    padding-right: 36px;
    height: 40px;
    box-sizing: border-box;
  }

  #changeAreaExpose .icon-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
  }

  #changeAreaExpose .icon-btn img {
    width: 20px;
    height: 20px;
  }

  #changeAreaExpose .addr-popup-hidden {
    height: 0 !important;
    overflow: hidden !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    flex: 0 0 0 !important;
    opacity: 0;
    pointer-events: none;
  }

  #changeAreaExpose .addr-popup-visible {
    height: 500px;
    opacity: 1;
    flex: 1 1 auto;
    pointer-events: auto;
  }
</style>
<div id="changeAreaExpose" class="layer-panel card-type" tabindex="-1" style="position:fixed;">
  <div class="panel-container">
    <div class="panel-header">
      <h2 class="panel-title align-center"><em>검색지역</em> 설정</h2>
      <button class="btn-close-panel" type="button" aria-label="닫기"></button>
    </div>
    <div class="panel-body">
      <div class="panel-content">
        <div class="result-wrap">
          <div class="search-cont">
            <div class="search-input">
              <input type="text" id="txt_addr_expose" name="txt_addr_expose" placeholder="예시 : 신림동, 관악구" autocomplete="off" required class="form-text">
              <button id="iconBtn" type="button" class="icon-btn">
                <img id="iconImg" src="/resource/shop/mobile/img/ic_search2025.svg" alt="search icon">
              </button>
            </div>
          </div>

          <div class="addr-popup-list">
            <div class="addr-popup-search addr-popup-hidden">
              <div class="addr-popup-title">검색목록</div>
              <ul>
              </ul>
            </div>
            <div class="addr-popup-dept addr-popup-visible">
              <div class="addr-popup-dept1">
                <div class="addr-popup-title">시·도</div>
                <ul>
                  <li data-step="1" data-addr="서울특별시" class="selectable"><span class="text">서울</span></li>
                  <li data-step="1" data-addr="경기도" class="selectable"><span class="text">경기</span></li>
                  <li data-step="1" data-addr="인천광역시" class="selectable"><span class="text">인천</span></li>
                  <li data-step="1" data-addr="강원특별자치도" class="selectable"><span class="text">강원</span></li>
                  <li data-step="1" data-addr="대전광역시" class="selectable"><span class="text">대전</span></li>
                  <li data-step="1" data-addr="세종특별자치시" class="selectable"><span class="text">세종</span></li>
                  <li data-step="1" data-addr="충청남도" class="selectable"><span class="text">충남</span></li>
                  <li data-step="1" data-addr="충청북도" class="selectable"><span class="text">충북</span></li>
                  <li data-step="1" data-addr="부산광역시" class="selectable"><span class="text">부산</span></li>
                  <li data-step="1" data-addr="울산광역시" class="selectable"><span class="text">울산</span></li>
                  <li data-step="1" data-addr="경상남도" class="selectable"><span class="text">경남</span></li>
                  <li data-step="1" data-addr="경상북도" class="selectable"><span class="text">경북</span></li>
                  <li data-step="1" data-addr="대구광역시" class="selectable"><span class="text">대구</span></li>
                  <li data-step="1" data-addr="광주광역시" class="selectable"><span class="text">광주</span></li>
                  <li data-step="1" data-addr="전라남도" class="selectable"><span class="text">전남</span></li>
                  <li data-step="1" data-addr="전북특별자치도" class="selectable"><span class="text">전북</span></li>
                  <li data-step="1" data-addr="제주특별자치도" class="selectable"><span class="text">제주</span></li>
                </ul>
              </div>
              <div class="addr-popup-dept2">
                <div class="addr-popup-title">시·군·구</div>
                <ul>
                </ul>
              </div>
              <div class="addr-popup-dept3">
                <div class="addr-popup-title">읍·면·동</div>
                <ul>
                </ul>
              </div>
            </div>
          </div>

          <div class="addr-popup-sel-box">
            <!-- [변경] PHP 렌더링 제거 - JavaScript에서 sessionStorage로 복원 -->
            <div class="addr-popup-div1">
              <span class="addr-popup-div1-title">선택한 지역</span><span class="addr-popup-sel-cnt">0</span><span class="addr-popup-div1-desc">(최대 1개)</span>
            </div>
            <div class="addr-popup-div2">
              <!-- JavaScript에서 sessionStorage의 데이터로 .addr-popup-selbtn 요소를 동적으로 생성 -->
            </div>
            <div class="addr-popup-div3">
              <div class="addr-popup-sel-init">초기화</div>
              <div class="addr-popup-sel-apply">적용하기</div>
            </div>
          </div>

        </div>
        <script>
          const $txtInput = $('#txt_addr_expose');
          const $iconBtn = $('#iconBtn');
          const $iconImg = $('#iconImg');
          const $searchAddr = $('#changeAreaExpose .addr-popup-search');
          const $dept = $('#changeAreaExpose .addr-popup-dept');
          const $targetContainers = $('#changeAreaExpose .addr-popup-search, #changeAreaExpose .addr-popup-dept1, #changeAreaExpose .addr-popup-dept2, #changeAreaExpose .addr-popup-dept3');
          let debounceTimer = null;

          // [변경] sessionStorage에서 선택된 지역 복원 (페이지별 구분)
          function restoreSelectedRegionsToDiv2() {
            var pageType = window.__addrPopupPageType || 'default'; // expose1,expose2
            var storageKey_regions = 'selectedRegions_' + pageType;
            var storageKey_area = 'searchAreaStr_' + pageType;
            var storageKey_name = 'addrName_' + pageType;

        

            const $div2 = $('#changeAreaExpose .addr-popup-div2');

            // 항상 .addr-popup-div2를 초기화
            $div2.find('.addr-popup-selbtn').remove();

            // sessionStorage에서 복원 (페이지별 키 사용)
            const savedSelectedRegions = sessionStorage.getItem(storageKey_regions);
            const savedSearchAreaStr = sessionStorage.getItem(storageKey_area);
            const savedAddrName = sessionStorage.getItem(storageKey_name);

            if (savedSelectedRegions && savedSearchAreaStr) {
              try {
                const selectedRegions = JSON.parse(savedSelectedRegions);
                const searchArea = JSON.parse(savedSearchAreaStr);



                searchArea.forEach((area, index) => {
                  const $selbtn = $('<div>')
                    .addClass('addr-popup-selbtn')
                    .attr('data-step', area.step)
                    .attr('data-addr', area.addr)
                    .text(selectedRegions[index] || '')
                    .append(
                      $('<div>').addClass('addr-popup-selbtn-close').text('x')
                    );
                  $div2.append($selbtn);
                });

                updateSelectedCount();
              } catch (e) {
               
                updateSelectedCount();
              }
            } else {
             
              updateSelectedCount();
            }
          }

          // 팝업이 열릴 때 UI를 초기화하는 함수
          function resetAddrPopupOnOpen() {
            $('#changeAreaExpose .addr-popup-dept2 ul, #changeAreaExpose .addr-popup-dept3 ul').empty();
            $txtInput.val('');
            $('#changeAreaExpose li.addr-popup-on').removeClass('addr-popup-on');
            $('#changeAreaExpose .addr-popup-div2 .addr-popup-selbtn').remove();
            updateSelectedCount();
            updateSearchView();
            resetAllRegionFlag();
          }

          // 전역 초기화 함수 노출 (페이지에서 호출 가능하도록)
          window.resetExposeAddrPopup = function() {
            resetAddrPopupOnOpen();
          };

          // 전역 복원 함수 노출 (페이지에서 호출 가능하도록)
          window.restoreExposeAddrPopup = function() {
            restoreSelectedRegionsToDiv2();
          };

          // 초기화 후 복원 시도
          $(document).ready(function() {
            setTimeout(restoreSelectedRegionsToDiv2, 100);
          });

          // 패널이 열릴 때마다 초기화 시도
          $(document).on('click', '.js-toggle-panel[aria-controls="changeAreaExpose"]', function() {
            setTimeout(resetAddrPopupOnOpen, 50);
          });

          if (typeof window.__hideAllRegionsInAddrPopup === 'undefined') { // 페이지 전역에서 전체 지역 숨김 여부를 추적하는 플래그 초기화
            window.__hideAllRegionsInAddrPopup = false;
          }
          // 전체 지역 숨김 여부를 반환하는 함수. true인 경우 전체 지역이 숨겨져야 함
          function shouldHideAllRegions() {
            return window.__hideAllRegionsInAddrPopup === true;
          }
          // 전체 지역 숨김 플래그를 설정하는 함수. true로 설정하면 전체 지역이 숨겨지고, false로 설정하면 전체 지역이 다시 보이도록 함
          window.applyAddrPopupAllRegionFilter = function() {
            if (!shouldHideAllRegions()) return;

            $('.addr-popup-search ul li, .addr-popup-dept2 ul li, .addr-popup-dept3 ul li').each(function() {
              const text = $(this).find('.text').text().trim();
              if (text.endsWith('전체')) {
                $(this).remove();
              }
            });

            $('.addr-popup-div2 .addr-popup-selbtn').each(function() {
              const text = $(this).clone().children().remove().end().text().trim();
              if (text.endsWith('전체')) {
                $(this).remove();
              }
            });

            updateSelectedCount();
          };

          function resetAllRegionFlag() {
            window.__hideAllRegionsInAddrPopup = false;
          }

          function updateSearchView() {
            if ($.trim($txtInput.val()) !== '') {
              $iconImg.attr('src', '/resource/shop/mobile/img/ic_close2025.svg').attr('alt', 'clear');
              $searchAddr.removeClass('addr-popup-hidden').addClass('addr-popup-visible');
              $dept.removeClass('addr-popup-visible').addClass('addr-popup-hidden');
            } else {
              $iconImg.attr('src', '/resource/shop/mobile/img/ic_search2025.svg').attr('alt', 'search icon');
              $searchAddr.removeClass('addr-popup-visible').addClass('addr-popup-hidden');
              $dept.removeClass('addr-popup-hidden').addClass('addr-popup-visible');
              $('.addr-popup-search ul').empty();
            }
          }

          $(document).on('input', '#txt_addr_expose', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
              updateSearchView();
              fSearchKeyUp();
            }, 300);
          });

          $iconBtn.on('click', function() {
            if ($iconImg.attr('alt') === 'clear') {
              $txtInput.val('');
              updateSearchView();
              $txtInput.focus();
            }
          });

          $targetContainers.on('click', 'li[data-step][data-addr]', function(e) {
            const $li = $(this);
            const step = $li.data('step');
            const addr = $li.data('addr');
            const hasCheckbox = $li.find('input[type="checkbox"]').length > 0;

            if ($li.data('processing')) return;
            $li.data('processing', true);
            setTimeout(() => $li.removeData('processing'), 200);

            if (hasCheckbox) {
              searchSelect(step, addr, $li);
            } else {
              searchLocation(step, addr, $li);
            }

            $targetContainers.each(function() {
              const $targetContainer = $(this);
              const $targetLi = $targetContainer.find(`li[data-step="${step}"][data-addr="${addr}"]`).not($li);
              if (!$targetLi.length) return;

              const targetHasCheckbox = $targetLi.find('input[type="checkbox"]').length > 0;

              if (targetHasCheckbox === hasCheckbox) {
                if (hasCheckbox) {
                  $targetLi.toggleClass('addr-popup-on');
                } else {
                  $targetContainer.find('li.addr-popup-on').each(function() {
                    if ($(this).find('input[type="checkbox"]').length === 0) {
                      $(this).removeClass('addr-popup-on');
                    }
                  });
                  $targetLi.addClass('addr-popup-on');
                }
              }
            });
          });

          function searchLocation(step, addr, $clickedLi) {
            const hasCheckbox = $clickedLi.find('input[type="checkbox"]').length > 0;
            if (hasCheckbox) return;

            const $parentDiv = $clickedLi.closest('.addr-popup-dept1, .addr-popup-dept2');
            if (!$parentDiv.length) return;

            $parentDiv.find('li.addr-popup-on').each(function() {
              if ($(this).find('input[type="checkbox"]').length === 0) {
                $(this).removeClass('addr-popup-on');
              }
            });

            $clickedLi.addClass('addr-popup-on');
            get_location_data(step, addr);
          }

          /**
           * 선택된 주소 항목(리스트 아이템)을 토글하고, 선택된 상태에 따라
           * UI(버튼 등)를 신규 생성 또는 제거 및 최대 선택 개수(3개) 제한 로직을 처리하는 함수입니다.
           * 
           * @param {number|string} step - 주소단계(1,2,3 등)
           * @param {string} addr - 주소값
           * @param {jQuery} $clickedLi - 클릭된 li 주소 jQuery 객체
           */
          function searchSelect(step, addr, $clickedLi) {
            const isSelected = $clickedLi.toggleClass('addr-popup-on').hasClass('addr-popup-on');
            const currentStep = parseInt(step, 10);
            const $div2 = $('.addr-popup-div2');
            const text = $clickedLi.text().trim();
            var combinedText = "";
            const textParts = text.split(" ");
            if (shouldHideAllRegions() && textParts[textParts.length - 1] === "전체") {
              $clickedLi.removeClass('addr-popup-on');
              return;
            }
            if (textParts[textParts.length - 1] === "전체") {
              if (currentStep === 1) {
                combinedText = replaceRegionNames(text)
              } else {
                combinedText = removeRegionNames(text);
              }
            } else {
              combinedText = textParts[textParts.length - 1];
            }

            const existingBtnSelector = `.addr-popup-selbtn[data-step="${step}"][data-addr="${addr}"]`;
            if (isSelected) {
              const $existing = $div2.find(existingBtnSelector);
              const alreadyExists = $existing.length > 0;
              const currentCount = $('.addr-popup-selbtn').length;
              let removeCount = 0;
              $('.addr-popup-selbtn[data-step][data-addr]').each(function() {
                const $btn = $(this);
                const btnStep = parseInt($btn.data('step'), 10);
                const btnAddr = $btn.data('addr');

                if (
                  (currentStep === 1 && btnStep > 1 && btnAddr.startsWith(addr)) ||
                  (currentStep === 2 &&
                    ((btnStep === 1 && addr.startsWith(btnAddr)) || (btnStep === 3 && btnAddr.startsWith(addr)))) ||
                  (currentStep === 3 &&
                    ((btnStep === 1 && addr.startsWith(btnAddr)) || (btnStep === 2 && addr.startsWith(btnAddr))))
                ) {
                  removeCount++;
                }
              });

              const maxSelection = typeof window.__addrPopupMaxSelection !== 'undefined'
                ? window.__addrPopupMaxSelection
                : 1;

              const projectedCount = currentCount + (alreadyExists ? 0 : 1) - removeCount;

              if (projectedCount > maxSelection) {
                $clickedLi.removeClass('addr-popup-on');
                return;
              }

              if (!alreadyExists) {
                const $selbtn = $('<div>')
                  .addClass('addr-popup-selbtn')
                  .attr('data-step', step)
                  .attr('data-addr', addr)
                  .text(combinedText)
                  .append(
                    $('<div>')
                    .addClass('addr-popup-selbtn-close')
                    .text('x')
                  );
                $div2.append($selbtn);
                updateSelectedCount();
              }
            } else {
              $div2.find(existingBtnSelector).remove();
              updateSelectedCount();
            }
            /*
            if (isSelected) {
              if ($div2.find(existingBtnSelector).length === 0) {
                const $selbtn = $('<div>')
                  .addClass('addr-popup-selbtn')
                  .attr('data-step', step)
                  .attr('data-addr', addr)
                  .text(combinedText)
                  .append(
                    $('<div>')
                      .addClass('addr-popup-selbtn-close')
                      .text('x')
                  );
                $div2.append($selbtn);
                updateSelectedCount();
              }
            } else {
              $div2.find(existingBtnSelector).remove();
              updateSelectedCount();
            }
            */
            $('li[data-step][data-addr]').each(function() {
              const $li = $(this);
              if ($li.is($clickedLi)) return;

              const hasCheckbox = $li.find('input[type="checkbox"]').length > 0;
              if (!hasCheckbox) return;

              const liStep = parseInt($li.data('step'), 10);
              const liAddr = $li.data('addr');

              if (
                (currentStep === 1 && liStep > 1 && liAddr.startsWith(addr)) ||
                (currentStep === 2 &&
                  ((liStep === 1 && addr.startsWith(liAddr)) || (liStep === 3 && liAddr.startsWith(addr)))) ||
                (currentStep === 3 &&
                  ((liStep === 1 && addr.startsWith(liAddr)) || (liStep === 2 && addr.startsWith(liAddr))))
              ) {
                $li.removeClass('addr-popup-on');
              }
            });

            $('.addr-popup-selbtn[data-step][data-addr]').each(function() {
              const $btn = $(this);
              const btnStep = parseInt($btn.data('step'), 10);
              const btnAddr = $btn.data('addr');

              if (
                (currentStep === 1 && btnStep > 1 && btnAddr.startsWith(addr)) ||
                (currentStep === 2 &&
                  ((btnStep === 1 && addr.startsWith(btnAddr)) || (btnStep === 3 && btnAddr.startsWith(addr)))) ||
                (currentStep === 3 &&
                  ((btnStep === 1 && addr.startsWith(btnAddr)) || (btnStep === 2 && addr.startsWith(btnAddr))))
              ) {
                $btn.remove();
                updateSelectedCount();
              }
            });
          }


          $('#changeAreaExpose').on('click', '.addr-popup-selbtn', function() {
            const $btn = $(this);
            const step = $btn.data('step');
            const addr = $btn.data('addr');

            $(`#changeAreaExpose li[data-step="${step}"][data-addr="${addr}"]`).removeClass('addr-popup-on');
            $btn.remove();
            updateSelectedCount();

            // 남은 .addr-popup-selbtn 기준으로 이름 배열 재구성
            var newNames = [];
            $('#changeAreaExpose .addr-popup-div2 .addr-popup-selbtn').each(function() {
              var text = $(this).clone().children().remove().end().text().trim();
              newNames.push(text);
            });
            window.selectedRegions = newNames;

            // index_search.php의 window.updateSelectedRegionsDisplay 함수 호출
            if (window.updateSelectedRegionsDisplay) {
              window.updateSelectedRegionsDisplay();
            }
          });

          $('#changeAreaExpose').find('input[type="checkbox"]').on('click', function(e) {
            e.stopPropagation();
          });

          function get_location_data(step, addr) {
            const addrParts = addr.trim().split(" ");
            const sido_name = addrParts[0] || "";
            const gugun_name = addrParts[1] ? addrParts[1] + (addrParts[2] ? " " + addrParts[2] : "") : "";

            const currentStep = parseInt(step, 10);
            const searchStep = currentStep + 1;

            const params = {
              step: searchStep,
              sido_name: sido_name,
              gugun_name: gugun_name
            };

            $.ajax({
              url: "/common_proc/get_location_data",
              type: "post",
              data: params,
              traditional: true,
              dataType: "json",
              crossDomain: true,
              success: function(ret) {
                if (ret.code !== "00") return;

                if (currentStep === 1) {
                  $('#changeAreaExpose .addr-popup-dept2 ul, #changeAreaExpose .addr-popup-dept3 ul').empty();

                  $.each(ret.data, function(i, item) {
                    // 모든 섹션에서 "전체" 필터링
                    if (!item.gugun_name || item.gugun_name.trim() === '' || item.gugun_name.includes('전체')) {
                      return;
                    }
                    const addr2 = addr + ' ' + item.gugun_name;
                    const isSelected = $(`#changeAreaExpose .addr-popup-selbtn[data-step="2"][data-addr="${addr2}"]`).length > 0;

                    const newLi = $('<li>')
                      .attr('data-step', '2')
                      .attr('data-addr', addr2)
                      .addClass('selectable')
                      .toggleClass('addr-popup-on', isSelected)
                      .append(
                        $('<span>').addClass('text').text(item.gugun_name)
                      );
                    $('#changeAreaExpose .addr-popup-dept2 ul').append(newLi);
                  });

                } else if (currentStep === 2) {
                  $('#changeAreaExpose .addr-popup-dept3 ul').empty();

                  $.each(ret.data, function(i, item) {
                    // 모든 섹션에서 "전체" 필터링
                    if (!item.b_dong_name || item.b_dong_name.trim() === '' || item.b_dong_name.includes('전체')) {
                      return;
                    }
                    const addr3 = addr + ' ' + item.b_dong_name;
                    const isSelected = $(`#changeAreaExpose .addr-popup-selbtn[data-step="3"][data-addr="${addr3}"]`).length > 0;

                    const newLi = $('<li>')
                      .attr('data-step', '3')
                      .attr('data-addr', addr3)
                      .toggleClass('addr-popup-on', isSelected)
                      .append(
                        $('<label>').append(
                          $('<span>').addClass('text').text(item.b_dong_name),
                          $('<input>').attr({
                            type: 'checkbox',
                            name: 'chk[]'
                          })
                        )
                      );
                    $('#changeAreaExpose .addr-popup-dept3 ul').append(newLi);
                  });
                }
              },
              error: function(xhr, status, error) {

              }
            });
          }

          // [중복 방지] addr_popup의 모든 이벤트 핸들러는 한 번만 등록
          if (!window.__addrPopupExposeEventsRegistered) {
            window.__addrPopupExposeEventsRegistered = true;

            $('#changeAreaExpose').on('click', '.addr-popup-sel-init', function() {
              var pageType = window.__addrPopupPageType || 'default';


              // 초기화 전 현재 선택 상태를 백업으로 저장 (X 버튼 클릭 시 복원용)
              window.backupSelectedRegions = window.selectedRegions ? window.selectedRegions.slice() : [];
              window.backupSearchArea = [];
              $('#changeAreaExpose .addr-popup-selbtn').each(function() {
                window.backupSearchArea.push({
                  addr: $(this).data('addr'),
                  step: $(this).data('step')
                });
              });

            // UI 초기화
            $('#changeAreaExpose .addr-popup-dept2 ul, #changeAreaExpose .addr-popup-dept3 ul').empty();
            $txtInput.val('');
            $('#changeAreaExpose li.addr-popup-on').removeClass('addr-popup-on');
            $('#changeAreaExpose .addr-popup-div2 .addr-popup-selbtn').remove();
            updateSelectedCount();

            // window.selectedRegions 초기화
            window.selectedRegions = [];
            updateSearchView();
            resetAllRegionFlag();

            // [변경] sessionStorage도 함께 초기화 (페이지별로 구분)
            var pageType = window.__addrPopupPageType || 'default';
            var storageKey_regions = 'selectedRegions_' + pageType;
            var storageKey_area = 'searchAreaStr_' + pageType;
            var storageKey_name = 'addrName_' + pageType;

           

            sessionStorage.removeItem(storageKey_regions);
            sessionStorage.removeItem(storageKey_area);
            sessionStorage.removeItem(storageKey_name);

            // main_visual.php의 지역 태그 UI 업데이트
            if (typeof window.updateSelectedRegionsTags === 'function') {
              window.updateSelectedRegionsTags();
            }
            }); // 초기화 버튼 클릭 핸들러 종료

          // sessionStorage에 선택된 지역 상태 저장
          // 적용하기 클릭 시 스크롤 잠김을 방지하기 위해 body 스타일을 복원
          $('#changeAreaExpose').on('click', '.addr-popup-sel-apply', function(e) {
            // 이벤트 버블링 차단 (main.js의 배경클릭 핸들러가 closePanel 호출하는 것 방지)
            e.stopPropagation();

            const count = $('#changeAreaExpose .addr-popup-selbtn').length;
            //if (count<1) return;
            var addrNames = []; // 선택된 지역 이름을 담을 배열

            if ($('#changeAreaExpose .addr-popup-selbtn').length > 0) {
              $('#changeAreaExpose .addr-popup-selbtn').each(function() {
                const text = $(this).clone().children().remove().end().text().trim();
                addrNames.push(text);
              });
              addrName = addrNames.join(', '); // "서울, 강남구" 등으로 문자열 생성
              // 상세검색 패널에서 열린 경우 .addrName 업데이트 안함 (닫기 버튼의 동기화 방지)
              if (!window.skipAddrNameUpdate) {
                $('.addrName').text(addrName).attr('data-selected', 'true');
              }
              window.skipAddrNameUpdate = false; // 플래그 초기화

              // window.selectedRegions에 지역 이름 배열 할당
              window.selectedRegions = addrNames.slice(0, 1); // 최대 1개만 저장
            } else {
              addrName = '지역을 선택해 주세요';
              // 상세검색 패널에서 열린 경우 .addrName 업데이트 안함
              if (!window.skipAddrNameUpdate) {
                $('.addrName').text(addrName).attr('data-selected', 'false');
              }
              window.skipAddrNameUpdate = false; // 플래그 초기화

              // 선택된 지역이 없으면 selectedRegions도 초기화
              window.selectedRegions = [];
            }

            const searchArea = [];
            search_addr = [];

            $('#changeAreaExpose .addr-popup-selbtn').each(function() {
              const addr = $(this).data('addr');
              const step = $(this).data('step');
              if (addr) {
                searchArea.push({
                  addr: addr,
                  step: step
                });
                search_addr.push(addr);
              }
            });
            searchAreaStr = JSON.stringify(searchArea);

            // [변경] 선택된 지역을 sessionStorage에 저장 (페이지별로 구분)
            var pageType = window.__addrPopupPageType || 'default';
            var storageKey_regions = 'selectedRegions_' + pageType;
            var storageKey_area = 'searchAreaStr_' + pageType;
            var storageKey_name = 'addrName_' + pageType;


            // sessionStorage에 저장 (페이지별 구분, 앱 종료 시 자동 초기화)
            sessionStorage.setItem(storageKey_regions, JSON.stringify(window.selectedRegions));
            sessionStorage.setItem(storageKey_area, JSON.stringify(searchArea));
            sessionStorage.setItem(storageKey_name, addrName);
            if (typeof window.saveSearchStateToStorage === 'function') {
              window.saveSearchStateToStorage();
            } else if (typeof saveSearchStateToStorage === 'function') {
              saveSearchStateToStorage();
            }

            // 백업 삭제 (변경 확정, 다음 초기화를 위해 준비)
            window.backupSelectedRegions = [];
            window.backupSearchArea = [];

            // main_visual.php에서 열린 경우만 지역 태그 UI 업데이트
            // (index_search.php에서 열린 경우는 검색 버튼 클릭 시 처리)
            var openedFromMainVisual = !document.querySelector('#advancedSearch');
            if (openedFromMainVisual && typeof window.updateSelectedRegionsTags === 'function') {
              window.updateSelectedRegionsTags();
            }

            // index_search.php의 전역 함수 호출하여 addr_sel_apply(적용하기)클릭시 선택된 지역 정보 전달
            if (typeof window.onRegionSelectionComplete === 'function') {
              window.onRegionSelectionComplete(addrNames);
            }

            // 페이지별 커스텀 적용 처리 (true 반환 시 기본 목록 갱신 생략)
            var handled = false;
            if (typeof window.onChangeAreaApply === 'function') {
              try {
                handled = window.onChangeAreaApply({
                  addrNames: addrNames, // [\"고덕동\"]
                  addrNameText: addrName, // 고덕동
                  searchArea: searchArea, // [{\"addr\":\"서울특별시 강동구 고덕동\",\"step\":3}]
                  searchAreaStr: searchAreaStr // [{\"addr\":\"서울특별시 강동구 고덕동\",\"step\":3}]
                }) === true;
              } catch (e) {

              }
            }

            $("#changeAreaExpose").removeClass("on");
            resetAllRegionFlag();
            // QuickInfo 플로우에서 열린 경우 다시 기본 닫기 동작 허용
            if (window.__disableChangeAreaClose) {
              window.__disableChangeAreaClose = false;
            }
            // QuickInfo 패널에서 열린 경우, 적용 후 다시 열기 (PC)
            if (window.__returnToQuickInfo && typeof window.openQuickInfoPanel === 'function') {
              window.__returnToQuickInfo = false;
              window.openQuickInfoPanel();
            }

            if (!handled) {
              if (typeof list_page !== "undefined") list_page = 1;
              if (typeof list_id !== "undefined" && typeof getBoardList === 'function') {
                $(list_id).html("");
                getBoardList("Y");
              }
            }
          });

          // X버튼 클릭: 백업이 있으면 복원 (초기화 취소), 없으면 UI만 초기화
          const changeAreaExposePanel = document.getElementById('changeAreaExpose');
          if (changeAreaExposePanel) {
            const closeBtn = changeAreaExposePanel.querySelector('.btn-close-panel');
            if (closeBtn) {
              closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                setTimeout(function() {
                  // index_search.php에서 열렸으면 (advancedSearch 패널이 있으면)
                  var openedFromIndexSearch = !!document.querySelector('#advancedSearch');

                  if (openedFromIndexSearch) {
                    // index_search에서 열린 경우: 선택한 지역을 버려야 함 (백업 복원)
                    // X 버튼은 "선택 취소" 의미이므로, 이전 상태로 복구
                    if (window.backupSelectedRegions && window.backupSelectedRegions.length > 0) {
                      window.selectedRegions = window.backupSelectedRegions.slice();
                    } else {
                      window.selectedRegions = [];
                    }

                    // index_search의 지역 UI 업데이트 (선택한 지역 제거)
                    if (typeof window.updateSelectedRegionsDisplay === 'function') {
                      window.updateSelectedRegionsDisplay();
                    }

                    // index_search의 탭 UI도 함께 갱신 (지역 탭 업데이트)
                    if (typeof window.initCategory === 'function') {
                      window.initCategory();
                    }
                    if (typeof window.initTabUi === 'function') {
                      window.initTabUi();
                    }

                    // addr_popup의 UI 초기화
                    $('#changeAreaExpose .addr_dept2 ul, #changeAreaExpose .addr_dept3 ul').empty();
                    $txtInput.val('');
                    $('#changeAreaExpose li.addr_on').removeClass('addr_on');
                    updateSearchView();
                  } else {
                    // main_visual에서 열린 경우: 초기화 취소 (백업 복원)
                    if (window.backupSelectedRegions && window.backupSelectedRegions.length > 0) {
                      // 백업된 선택 상태 복원
                      window.selectedRegions = window.backupSelectedRegions.slice();

                      // UI 복원: 백업된 검색 영역 기반으로 버튼 재생성
                      $('#changeAreaExpose .div2 .selbtn').remove();
                      if (window.backupSearchArea && window.backupSearchArea.length > 0) {
                        window.backupSearchArea.forEach(function(area, index) {
                          const displayText = window.selectedRegions[index] || '';
                          const $selbtn = $('<div>')
                            .addClass('selbtn')
                            .attr('data-step', area.step)
                            .attr('data-addr', area.addr)
                            .text(displayText)
                            .append($('<div>').addClass('selbtnclose').text('x'));
                          $('#changeAreaExpose .div2').append($selbtn);
                        });
                      }
                      updateSelectedCount();

                      // main_visual.php의 지역 태그 UI 업데이트
                      if (typeof window.updateSelectedRegionsTags === 'function') {
                        window.updateSelectedRegionsTags();
                      }
                    } else {
                      // 백업이 없으면 UI만 초기화
                      $('#changeAreaExpose .div2 .selbtn').remove();
                      updateSelectedCount();

                      // main_visual.php의 지역 태그 UI 업데이트
                      if (typeof window.updateSelectedRegionsTags === 'function') {
                        window.updateSelectedRegionsTags();
                      }
                    }

                    // 리스트 초기화
                    $('#changeAreaExpose .addr_dept2 ul, #changeAreaExpose .addr_dept3 ul').empty();
                    $txtInput.val('');
                    $('#changeAreaExpose li.addr_on').removeClass('addr_on');
                    updateSearchView();
                  }

                  // 패널 닫기
                  changeAreaExposePanel.classList.remove('on');
                  changeAreaExposePanel.setAttribute('hidden', '');
                  document.body.style.overflow = '';
                  document.body.style.paddingRight = '';

                  // body 스타일 복원
                  if (typeof window.restorePanelClosedState === 'function') {
                    window.restorePanelClosedState();
                  } else if (typeof restorePanelClosedState === 'function') {
                    restorePanelClosedState();
                  }
                }, 50);
              });
            }
          }
          } // addr_popup 모든 이벤트 핸들러 if 블록 종료

          // 선택된 지역 개수 업데이트 함수
          function updateSelectedCount() {
            const count = $('#changeAreaExpose .addr-popup-selbtn').length;

            $('#changeAreaExpose .addr-popup-sel-cnt').text(count);
          }
          // 검색어 입력 시 AJAX 요청을 보내어 검색 결과를 가져오는 함수입니다. 디바운스 적용으로 불필요한 요청 방지
          function fSearchKeyUp() {
            const search_keyword = $('#txt_addr_expose').val().trim();
            if (search_keyword.length < 2) return;

            const params = {
              search_keyword: search_keyword
            };
            // 검색어 입력 시마다 AJAX 요청을 보내어 검색 결과를 가져오는 부분입니다.
            $.ajax({
              url: "/common_proc/get_location_search",
              type: "post",
              data: params,
              traditional: true,
              dataType: "json",
              crossDomain: true,
              success: function(ret) {
                if (ret.code !== "00") return;

                $('#changeAreaExpose .addr-popup-search ul').empty();

                if (ret.data) {
                  $.each(ret.data, function(i, item) {
                    const addr = item.search_b_dong; // 검색 결과에서 주소를 가져옵니다. 예: "서울특별시 강남구 강남동"
                    const step = item.step; // 주소 단계 (1, 2, 3 등)도 함께 가져옵니다. 예: 3
                    // 모든 섹션에서 "전체" 필터링
                    if (shouldHideAllRegions() && step < 3) return;
                    if (!addr || addr.trim() === '' || addr.includes('전체')) return;
                    const isSelected = $(`#changeAreaExpose .addr-popup-selbtn[data-step="${step}"][data-addr="${addr}"]`).length > 0;

                    const displayText = step < 3 ? addr + ' 전체' : addr;

                    // displayText에 "전체"가 포함되면 렌더링하지 않음
                    if (displayText.includes('전체')) return;

                    const newLi = $('<li>')
                      .attr('data-step', step)
                      .attr('data-addr', addr)
                      .toggleClass('addr-popup-on', isSelected)
                      .append(
                        $('<label>').append(
                          $('<span>').addClass('text').text(displayText),
                          $('<input>').attr({
                            type: 'checkbox',
                            name: 'chk[]'
                          })
                        )
                      );
                    $('#changeAreaExpose .addr-popup-search ul').append(newLi);
                  });

                }
              },
              error: function(xhr, status, error) {

              }
            });
          }
          // "서울특별시" → "서울" 등으로 변환하는 함수입니다. "전체" 항목에서 시도 시, "서울 전체" → "서울 전체"로 유지되도록 합니다.
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
          // "서울 전체" → "서울 전체" 유지, "서울특별시 강남구" → "강남구" 등으로 변환하는 함수입니다. "전체" 항목에서 시도 시, "서울 전체" → "서울 전체"로
          function removeRegionNames(text) {
            const regionMap = {
              "서울특별시 ": "",
              "부산광역시 ": "",
              "대구광역시 ": "",
              "인천광역시 ": "",
              "광주광역시 ": "",
              "대전광역시 ": "",
              "울산광역시 ": "",
              "세종특별자치시 ": "",
              "경기도 ": "",
              "강원특별자치도 ": "",
              "충청북도 ": "",
              "충청남도 ": "",
              "전북특별자치도 ": "",
              "전라남도 ": "",
              "경상북도 ": "",
              "경상남도 ": "",
              "제주특별자치도 ": ""
            };
            return text.replace(
              new RegExp(Object.keys(regionMap).join("|"), "g"),
              match => regionMap[match]
            );
          }
        </script>

      </div>
    </div>
  </div>
</div>