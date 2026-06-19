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
  }

  #changeAreaExpose.layer-panel.card-type .panel-container {
    height: 100%;
    max-height: 95%;
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

  #changeAreaExpose .addr_list {
    flex: 1 1 auto;
    display: flex;
    flex-direction: row;
    min-height: 100px;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
  }

  #changeAreaExpose .search_addr,
  #changeAreaExpose .addr_dept,
  #changeAreaExpose .addr_dept1,
  #changeAreaExpose .addr_dept2,
  #changeAreaExpose .addr_dept3 {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  #changeAreaExpose .search_addr {
    width: 100%;
  }

  #changeAreaExpose .addr_dept {
    width: 100%;
    display: flex;
    flex-direction: row;
  }

  #changeAreaExpose .addr_dept1 {
    width: 20%;
    border-right: 1px solid #ccc;
  }

  #changeAreaExpose .addr_dept2 {
    width: 40%;
    border-right: 1px solid #ccc;
  }

  #changeAreaExpose .addr_dept3 {
    width: 40%;
  }

  #changeAreaExpose .addr_title {
    padding: 5px 8px;
    background: #f0f0f0;
    font-weight: bold;
    flex: 0 0 auto;
    border-bottom: 1px solid #ccc;
    text-align: center;
  }

  #changeAreaExpose .search_addr ul,
  #changeAreaExpose .addr_dept1 ul,
  #changeAreaExpose .addr_dept2 ul,
  #changeAreaExpose .addr_dept3 ul {
    flex: 1 1 auto;
    margin: 0;
    padding: 0;
    list-style: none;
    overflow-y: auto;
    scrollbar-width: none;
  }

  #changeAreaExpose .addr_dept1 ul::-webkit-scrollbar,
  #changeAreaExpose .addr_dept2 ul::-webkit-scrollbar,
  #changeAreaExpose .addr_dept3 ul::-webkit-scrollbar,
  #changeAreaExpose .search_addr ul::-webkit-scrollbar {
    width: 0;
    height: 0;
  }

  #changeAreaExpose .addr_dept1 ul li.selectable.addr_on,
  #changeAreaExpose .addr_dept2 ul li.selectable.addr_on {
    background-color: #31A182;
    color: #fff;
  }

  #changeAreaExpose .search_addr ul li {
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

  #changeAreaExpose .search_addr ul li label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    cursor: pointer;
  }

  #changeAreaExpose .search_addr ul li.addr_on label {
    background-color: #fff;
    color: #31A182;
  }

  #changeAreaExpose .search_addr ul li .text {
    flex: 1;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    text-overflow: ellipsis;
    cursor: pointer;
    padding: 0.5rem 1rem;
    padding-left: 2rem;
  }

  #changeAreaExpose .search_addr ul li input[type="checkbox"] {
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

  #changeAreaExpose .search_addr ul li label::before {
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

  #changeAreaExpose .search_addr ul li.addr_on label::before {
    background: url(/resource/shop/mobile/img/ic_chkbox2025_on.svg) no-repeat 0 0;
    background-size: contain;
  }

  #changeAreaExpose .addr_list_sel_box {
    flex: 0 0 auto;
    max-height: 225px;
    overflow-y: auto;
    padding-bottom: 0;
    box-sizing: border-box;
  }

  #changeAreaExpose .addr_list_sel_box .div1 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 5px;
  }

  #changeAreaExpose .addr_list_sel_box .div2 {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 15px;
    margin-left: 10px;
  }

  #changeAreaExpose .addr_dept1 ul li,
  #changeAreaExpose .addr_dept2 ul li,
  #changeAreaExpose .addr_dept3 ul li {
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

  #changeAreaExpose .addr_dept1 ul li label,
  #changeAreaExpose .addr_dept2 ul li label,
  #changeAreaExpose .addr_dept3 ul li label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    cursor: pointer;
  }

  #changeAreaExpose .addr_dept1 ul li.addr_on label,
  #changeAreaExpose .addr_dept2 ul li.addr_on label,
  #changeAreaExpose .addr_dept3 ul li.addr_on label {
    background-color: #fff;
    color: #31A182;
  }

  #changeAreaExpose .addr_dept1 ul li .text,
  #changeAreaExpose .addr_dept2 ul li .text,
  #changeAreaExpose .addr_dept3 ul li .text {
    flex: 1;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    cursor: pointer;
    padding: 0.5rem 1rem;
  }

  #changeAreaExpose .addr_dept1 ul li input[type="checkbox"],
  #changeAreaExpose .addr_dept2 ul li input[type="checkbox"],
  #changeAreaExpose .addr_dept3 ul li input[type="checkbox"] {
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

  #changeAreaExpose .addr_dept1 ul li label::after,
  #changeAreaExpose .addr_dept2 ul li label::after,
  #changeAreaExpose .addr_dept3 ul li label::after {
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

  #changeAreaExpose .addr_dept1 ul li.addr_on label::after,
  #changeAreaExpose .addr_dept2 ul li.addr_on label::after,
  #changeAreaExpose .addr_dept3 ul li.addr_on label::after {
    background: url(/resource/shop/mobile/img/ic_chkbox2025_on.svg) no-repeat center center;
    background-size: contain;
  }

  #changeAreaExpose .addr_list_sel_box .div1 {
    padding: 10px 10px;
  }

  #changeAreaExpose .addr_list_sel_box .div1title {
    font-weight: 600;
    font-size: 1.1rem;
  }

  #changeAreaExpose .addr_list_sel_box .addr_sel_cnt {
    margin-left: 10px;
    color: #31A182;
    font-weight: 600;
    margin-right: 10px;
  }

  #changeAreaExpose .addr_list_sel_box .div1desc {
    font-size: 0.8rem;
    color: #777;
  }

  #changeAreaExpose .selbtn {
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

  #changeAreaExpose .selbtnclose {
    cursor: pointer;
  }

  #changeAreaExpose .addr_list_sel_box .div3 {
    margin-top: 5px;
    display: flex;
    gap: 10px;
    justify-content: space-between;
    margin-right: 10px;
    margin-left: 10px;
  }

  #changeAreaExpose .addr_sel_init {
    flex: 3;
  }

  #changeAreaExpose .addr_sel_apply {
    flex: 7;
  }

  #changeAreaExpose .addr_sel_init,
  #changeAreaExpose .addr_sel_apply {
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

  #changeAreaExpose .hidden {
    height: 0 !important;
    overflow: hidden !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    flex: 0 0 0 !important;
    opacity: 0;
    pointer-events: none;
  }

  #changeAreaExpose .visible {
    height: auto;
    opacity: 1;
    flex: 1 1 auto;
    pointer-events: auto;
  }
</style>
<div id="changeAreaExpose" class="layer-panel card-type" tabindex="-1">
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
              <button id="iconBtnExpose" type="button" class="icon-btn">
                <img id="iconImgExpose" src="/resource/shop/mobile/img/ic_search2025.svg" alt="search icon">
              </button>
            </div>
          </div>

          <div class="addr_list">
            <div class="search_addr hidden">
              <div class="addr_title">검색목록</div>
              <ul>
              </ul>
            </div>
            <div class="addr_dept visible">
              <div class="addr_dept1">
                <div class="addr_title">시·도</div>
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
              <div class="addr_dept2">
                <div class="addr_title">시·군·구</div>
                <ul>
                </ul>
              </div>
              <div class="addr_dept3">
                <div class="addr_title">읍·면·동</div>
                <ul>
                </ul>
              </div>
            </div>
          </div>

          <div class="addr_list_sel_box">
            <!-- [변경] PHP 렌더링 제거 - JavaScript에서 sessionStorage로 복원 -->
            <div class="div1">
              <span class="div1title">선택한 지역</span><span class="addr_sel_cnt">0</span><span class="div1desc">(최대 2개)</span>
            </div>
            <div class="div2">
              <!-- JavaScript에서 sessionStorage의 데이터로 .selbtn 요소를 동적으로 생성 -->
            </div>
            <div class="div3">
              <div class="addr_sel_init">초기화</div>
              <div class="addr_sel_apply">적용하기</div>
            </div>
          </div>

        </div>
        <script>
          const $txtInputExpose = $('#txt_addr_expose');
          const $iconBtnExpose = $('#iconBtnExpose');
          const $iconImgExpose = $('#iconImgExpose');
          const $searchAddrExpose = $('#changeAreaExpose .search_addr');
          const $deptExpose = $('#changeAreaExpose .addr_dept');
          const $targetContainersExpose = $('#changeAreaExpose .search_addr, #changeAreaExpose .addr_dept1, #changeAreaExpose .addr_dept2, #changeAreaExpose .addr_dept3');
          let debounceTimerExpose = null;

          // [변경] sessionStorage에서 선택된 지역 복원 (페이지별 구분)
          function restoreSelectedRegionsToDiv2Expose() {
            var pageType = window.__addrPopupPageType || 'default';
            var storageKey_regions = 'selectedRegions_' + pageType;
            var storageKey_area = 'searchAreaStr_' + pageType;
            var storageKey_name = 'addrName_' + pageType;

            const $div2 = $('#changeAreaExpose .div2');

            // 항상 .div2를 초기화
            $div2.find('.selbtn').remove();

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
                    .addClass('selbtn')
                    .attr('data-step', area.step)
                    .attr('data-addr', area.addr)
                    .text(selectedRegions[index] || '')
                    .append(
                      $('<div>').addClass('selbtnclose').text('x')
                    );
                  $div2.append($selbtn);
                });

                updateSelectedCountExpose();
              } catch (e) {
                updateSelectedCountExpose();
              }
            } else {
              updateSelectedCountExpose();
            }
          }

          // 초기화 후 복원 시도
          $(document).ready(function() {
            setTimeout(restoreSelectedRegionsToDiv2Expose, 100);
          });

          // 패널이 열릴 때마다 복원 시도
          $(document).on('click', '.js-toggle-panel[aria-controls="changeAreaExpose"]', function() {
            setTimeout(restoreSelectedRegionsToDiv2Expose, 50);
          });

          if (typeof window.__hideAllRegionsInAddrPopupExpose === 'undefined') {
            window.__hideAllRegionsInAddrPopupExpose = false;
          }

          function shouldHideAllRegionsExpose() {
            return window.__hideAllRegionsInAddrPopupExpose === true;
          }

          window.applyAddrPopupAllRegionFilterExpose = function() {
            if (!shouldHideAllRegionsExpose()) return;

            $('#changeAreaExpose .search_addr ul li, #changeAreaExpose .addr_dept2 ul li, #changeAreaExpose .addr_dept3 ul li').each(function() {
              const text = $(this).find('.text').text().trim();
              if (text.endsWith('전체')) {
                $(this).remove();
              }
            });

            $('#changeAreaExpose .div2 .selbtn').each(function() {
              const text = $(this).clone().children().remove().end().text().trim();
              if (text.endsWith('전체')) {
                $(this).remove();
              }
            });

            updateSelectedCountExpose();
          };

          function resetAllRegionFlagExpose() {
            window.__hideAllRegionsInAddrPopupExpose = false;
          }

          function updateSearchViewExpose() {
            if ($.trim($txtInputExpose.val()) !== '') {
              $iconImgExpose.attr('src', '/resource/shop/mobile/img/ic_close2025.svg').attr('alt', 'clear');
              $searchAddrExpose.removeClass('hidden').addClass('visible');
              $deptExpose.removeClass('visible').addClass('hidden');
            } else {
              $iconImgExpose.attr('src', '/resource/shop/mobile/img/ic_search2025.svg').attr('alt', 'search icon');
              $searchAddrExpose.removeClass('visible').addClass('hidden');
              $deptExpose.removeClass('hidden').addClass('visible');
              $('#changeAreaExpose .search_addr ul').empty();
            }
          }

          $(document).on('input', '#txt_addr_expose', function() {
            clearTimeout(debounceTimerExpose);
            debounceTimerExpose = setTimeout(() => {
              updateSearchViewExpose();
              fSearchKeyUpExpose();
            }, 300);
          });

          $iconBtnExpose.on('click', function() {
            if ($iconImgExpose.attr('alt') === 'clear') {
              $txtInputExpose.val('');
              updateSearchViewExpose();
              $txtInputExpose.focus();
            }
          });

          $targetContainersExpose.on('click', 'li[data-step][data-addr]', function(e) {
            const $li = $(this);
            const step = $li.data('step');
            const addr = $li.data('addr');
            const hasCheckbox = $li.find('input[type="checkbox"]').length > 0;

            if ($li.data('processing')) return;
            $li.data('processing', true);
            setTimeout(() => $li.removeData('processing'), 200);

            if (hasCheckbox) {
              searchSelectExpose(step, addr, $li);
            } else {
              searchLocationExpose(step, addr, $li);
            }

            $targetContainersExpose.each(function() {
              const $targetContainer = $(this);
              const $targetLi = $targetContainer.find(`li[data-step="${step}"][data-addr="${addr}"]`).not($li);
              if (!$targetLi.length) return;

              const targetHasCheckbox = $targetLi.find('input[type="checkbox"]').length > 0;

              if (targetHasCheckbox === hasCheckbox) {
                if (hasCheckbox) {
                  $targetLi.toggleClass('addr_on');
                } else {
                  $targetContainer.find('li.addr_on').each(function() {
                    if ($(this).find('input[type="checkbox"]').length === 0) {
                      $(this).removeClass('addr_on');
                    }
                  });
                  $targetLi.addClass('addr_on');
                }
              }
            });
          });

          function searchLocationExpose(step, addr, $clickedLi) {
            const hasCheckbox = $clickedLi.find('input[type="checkbox"]').length > 0;
            if (hasCheckbox) return;

            const $parentDiv = $clickedLi.closest('.addr_dept1, .addr_dept2');
            if (!$parentDiv.length) return;

            $parentDiv.find('li.addr_on').each(function() {
              if ($(this).find('input[type="checkbox"]').length === 0) {
                $(this).removeClass('addr_on');
              }
            });

            $clickedLi.addClass('addr_on');
            get_location_dataExpose(step, addr);
          }

          function searchSelectExpose(step, addr, $clickedLi) {
            const isSelected = $clickedLi.toggleClass('addr_on').hasClass('addr_on');
            const currentStep = parseInt(step, 10);
            const $div2 = $('#changeAreaExpose .div2');
            const text = $clickedLi.text().trim();
            var combinedText = "";
            const textParts = text.split(" ");
            if (shouldHideAllRegionsExpose() && textParts[textParts.length - 1] === "전체") {
              $clickedLi.removeClass('addr_on');
              return;
            }
            if (textParts[textParts.length - 1] === "전체") {
              if (currentStep === 1) {
                combinedText = replaceRegionNamesExpose(text)
              } else {
                combinedText = removeRegionNamesExpose(text);
              }
            } else {
              combinedText = textParts[textParts.length - 1];
            }

            const existingBtnSelector = `.selbtn[data-step="${step}"][data-addr="${addr}"]`;
            if (isSelected) {
              const $existing = $div2.find(existingBtnSelector);
              const alreadyExists = $existing.length > 0;
              const currentCount = $('#changeAreaExpose .selbtn').length;
              let removeCount = 0;
              $('#changeAreaExpose .selbtn[data-step][data-addr]').each(function() {
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
                : 2;

              const projectedCount = currentCount + (alreadyExists ? 0 : 1) - removeCount;

              if (projectedCount > maxSelection) {
                $clickedLi.removeClass('addr_on');
                return;
              }

              if (!alreadyExists) {
                const $selbtn = $('<div>')
                  .addClass('selbtn')
                  .attr('data-step', step)
                  .attr('data-addr', addr)
                  .text(combinedText)
                  .append(
                    $('<div>')
                    .addClass('selbtnclose')
                    .text('x')
                  );
                $div2.append($selbtn);
                updateSelectedCountExpose();
              }
            } else {
              $div2.find(existingBtnSelector).remove();
              updateSelectedCountExpose();
            }

            $('#changeAreaExpose li[data-step][data-addr]').each(function() {
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
                $li.removeClass('addr_on');
              }
            });

            $('#changeAreaExpose .selbtn[data-step][data-addr]').each(function() {
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
                updateSelectedCountExpose();
              }
            });
          }

          $('#changeAreaExpose').on('click', '.selbtn', function() {
            const $btn = $(this);
            const step = $btn.data('step');
            const addr = $btn.data('addr');

            $(`#changeAreaExpose li[data-step="${step}"][data-addr="${addr}"]`).removeClass('addr_on');
            $btn.remove();
            updateSelectedCountExpose();

            var newNames = [];
            $('#changeAreaExpose .div2 .selbtn').each(function() {
              var text = $(this).clone().children().remove().end().text().trim();
              newNames.push(text);
            });
            window.selectedRegions = newNames;

            if (window.updateSelectedRegionsDisplay) {
              window.updateSelectedRegionsDisplay();
            }
          });

          $('#changeAreaExpose').find('input[type="checkbox"]').on('click', function(e) {
            e.stopPropagation();
          });

          function get_location_dataExpose(step, addr) {
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
                  $('#changeAreaExpose .addr_dept2 ul, #changeAreaExpose .addr_dept3 ul').empty();

                  const addr1 = addr;
                  const isSelected = $(`#changeAreaExpose .selbtn[data-step="1"][data-addr="${addr1}"]`).length > 0;

                  if (!shouldHideAllRegionsExpose()) {
                    const allLi = $('<li>')
                      .attr('data-step', '1')
                      .attr('data-addr', addr1)
                      .toggleClass('addr_on', isSelected)
                      .append(
                        $('<label>').append(
                          $('<span>').addClass('text').text(replaceRegionNamesExpose(sido_name) + ' 전체'),
                          $('<input>').attr({
                            type: 'checkbox',
                            name: 'chk[]'
                          })
                        )
                      );
                    $('#changeAreaExpose .addr_dept2 ul').append(allLi);
                  }

                  $.each(ret.data, function(i, item) {
                    const addr2 = addr + ' ' + item.gugun_name;
                    const isSelected = $(`#changeAreaExpose .selbtn[data-step="2"][data-addr="${addr2}"]`).length > 0;

                    const newLi = $('<li>')
                      .attr('data-step', '2')
                      .attr('data-addr', addr2)
                      .addClass('selectable')
                      .toggleClass('addr_on', isSelected)
                      .append(
                        $('<span>').addClass('text').text(item.gugun_name)
                      );
                    $('#changeAreaExpose .addr_dept2 ul').append(newLi);
                  });

                } else if (currentStep === 2) {
                  $('#changeAreaExpose .addr_dept3 ul').empty();

                  const addr2 = addr;
                  const isSelected = $(`#changeAreaExpose .selbtn[data-step="2"][data-addr="${addr2}"]`).length > 0;

                  if (!shouldHideAllRegionsExpose()) {
                    const allLi = $('<li>')
                      .attr('data-step', '2')
                      .attr('data-addr', addr2)
                      .toggleClass('addr_on', isSelected)
                      .append(
                        $('<label>').append(
                          $('<span>').addClass('text').text(gugun_name + ' 전체'),
                          $('<input>').attr({
                            type: 'checkbox',
                            name: 'chk[]'
                          })
                        )
                      );
                    $('#changeAreaExpose .addr_dept3 ul').append(allLi);
                  }

                  $.each(ret.data, function(i, item) {
                    const addr3 = addr + ' ' + item.b_dong_name;
                    const isSelected = $(`#changeAreaExpose .selbtn[data-step="3"][data-addr="${addr3}"]`).length > 0;

                    const newLi = $('<li>')
                      .attr('data-step', '3')
                      .attr('data-addr', addr3)
                      .toggleClass('addr_on', isSelected)
                      .append(
                        $('<label>').append(
                          $('<span>').addClass('text').text(item.b_dong_name),
                          $('<input>').attr({
                            type: 'checkbox',
                            name: 'chk[]'
                          })
                        )
                      );
                    $('#changeAreaExpose .addr_dept3 ul').append(newLi);
                  });
                }
              },
              error: function(xhr, status, error) {
              }
            });
          }

          if (!window.__addrPopupExposeEventsRegistered) {
            window.__addrPopupExposeEventsRegistered = true;

            $('#changeAreaExpose').on('click', '.addr_sel_init', function() {
              window.backupSelectedRegions = window.selectedRegions ? window.selectedRegions.slice() : [];
              window.backupSearchArea = [];
              $('#changeAreaExpose .selbtn').each(function() {
                window.backupSearchArea.push({
                  addr: $(this).data('addr'),
                  step: $(this).data('step')
                });
              });

              $('#changeAreaExpose .addr_dept2 ul, #changeAreaExpose .addr_dept3 ul').empty();
              $txtInputExpose.val('');
              $('#changeAreaExpose li.addr_on').removeClass('addr_on');
              $('#changeAreaExpose .div2 .selbtn').remove();
              updateSelectedCountExpose();

              window.selectedRegions = [];
              updateSearchViewExpose();
              resetAllRegionFlagExpose();

              var pageType = window.__addrPopupPageType || 'default';
              var storageKey_regions = 'selectedRegions_' + pageType;
              var storageKey_area = 'searchAreaStr_' + pageType;
              var storageKey_name = 'addrName_' + pageType;

              sessionStorage.removeItem(storageKey_regions);
              sessionStorage.removeItem(storageKey_area);
              sessionStorage.removeItem(storageKey_name);

              if (typeof window.updateSelectedRegionsTags === 'function') {
                window.updateSelectedRegionsTags();
              }
            });

            $('#changeAreaExpose').on('click', '.addr_sel_apply', function(e) {
              e.stopPropagation();

              var addrNames = [];

              if ($('#changeAreaExpose .selbtn').length > 0) {
                $('#changeAreaExpose .selbtn').each(function() {
                  const text = $(this).clone().children().remove().end().text().trim();
                  addrNames.push(text);
                });
                addrName = addrNames.join(', ');

                window.selectedRegions = addrNames.slice(0, 2);
              } else {
                addrName = '지역을 선택해 주세요';
                window.selectedRegions = [];
              }

              const searchArea = [];
              search_addr = [];

              $('#changeAreaExpose .selbtn').each(function() {
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

              var pageType = window.__addrPopupPageType || 'default';
              var storageKey_regions = 'selectedRegions_' + pageType;
              var storageKey_area = 'searchAreaStr_' + pageType;
              var storageKey_name = 'addrName_' + pageType;

              sessionStorage.setItem(storageKey_regions, JSON.stringify(window.selectedRegions));
              sessionStorage.setItem(storageKey_area, JSON.stringify(searchArea));
              sessionStorage.setItem(storageKey_name, addrName);

              if (typeof window.saveSearchStateToStorage === 'function') {
                window.saveSearchStateToStorage();
              } else if (typeof saveSearchStateToStorage === 'function') {
                saveSearchStateToStorage();
              }

              window.backupSelectedRegions = [];
              window.backupSearchArea = [];

              if (typeof window.onChangeAreaApply === 'function') {
                try {
                  window.onChangeAreaApply({
                    addrNames: addrNames,
                    addrNameText: addrName,
                    searchArea: searchArea,
                    searchAreaStr: searchAreaStr
                  });
                } catch (e) {
                }
              }

              $("#changeAreaExpose").removeClass("on");
              resetAllRegionFlagExpose();
            });

            const changeAreaExposePanel = document.getElementById('changeAreaExpose');
            if (changeAreaExposePanel) {
              const closeBtn = changeAreaExposePanel.querySelector('.btn-close-panel');
              if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                  e.preventDefault();
                  e.stopPropagation();
                  setTimeout(function() {
                    if (window.backupSelectedRegions && window.backupSelectedRegions.length > 0) {
                      window.selectedRegions = window.backupSelectedRegions.slice();
                    } else {
                      window.selectedRegions = [];
                    }

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
                    updateSelectedCountExpose();

                    $('#changeAreaExpose .addr_dept2 ul, #changeAreaExpose .addr_dept3 ul').empty();
                    $txtInputExpose.val('');
                    $('#changeAreaExpose li.addr_on').removeClass('addr_on');
                    updateSearchViewExpose();

                    changeAreaExposePanel.classList.remove('on');
                    changeAreaExposePanel.setAttribute('hidden', '');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                  }, 50);
                });
              }
            }
          }

          function updateSelectedCountExpose() {
            const count = $('#changeAreaExpose .selbtn').length;
            $('#changeAreaExpose .addr_sel_cnt').text(count);
          }

          function fSearchKeyUpExpose() {
            const search_keyword = $('#txt_addr_expose').val().trim();
            if (search_keyword.length < 2) return;

            const params = {
              search_keyword: search_keyword
            };

            $.ajax({
              url: "/common_proc/get_location_search",
              type: "post",
              data: params,
              traditional: true,
              dataType: "json",
              crossDomain: true,
              success: function(ret) {
                if (ret.code !== "00") return;

                $('#changeAreaExpose .search_addr ul').empty();

                if (ret.data) {
                  $.each(ret.data, function(i, item) {
                    const addr = item.search_b_dong;
                    const step = item.step;
                    if (shouldHideAllRegionsExpose() && step < 3) return;
                    const isSelected = $(`#changeAreaExpose .selbtn[data-step="${step}"][data-addr="${addr}"]`).length > 0;

                    const displayText = step < 3 ? addr + ' 전체' : addr;

                    const newLi = $('<li>')
                      .attr('data-step', step)
                      .attr('data-addr', addr)
                      .toggleClass('addr_on', isSelected)
                      .append(
                        $('<label>').append(
                          $('<span>').addClass('text').text(displayText),
                          $('<input>').attr({
                            type: 'checkbox',
                            name: 'chk[]'
                          })
                        )
                      );
                    $('#changeAreaExpose .search_addr ul').append(newLi);
                  });
                }
              },
              error: function(xhr, status, error) {
              }
            });
          }

          function replaceRegionNamesExpose(text) {
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

          function removeRegionNamesExpose(text) {
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
