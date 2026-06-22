<?php
// 템플릿 파라미터: 패널 ID (필수)
if (!isset($addrPopupId)) {
    $addrPopupId = 'changeArea';
}
?>
<style>
  #<?= $addrPopupId ?>.layer-panel.card-type .panel-header {
    position: relative;
    padding-top: 1.25rem;
    padding-bottom: 1.25rem;
    background: inherit;
  }

  #<?= $addrPopupId ?>.layer-panel.card-type .panel-header .panel-title {
    background: inherit;
  }

  #<?= $addrPopupId ?>.layer-panel.card-type .panel-header .panel-title em {
    font-style: normal;
    color: #1B9D7D;
  }

  #<?= $addrPopupId ?>.layer-panel.card-type .panel-body {
    padding: 0;
  }

  #<?= $addrPopupId ?>.layer-panel.card-type .panel-container {
    height: 100%;
    max-height: 95%;
  }

  #<?= $addrPopupId ?> .panel-content {
    position: relative;
    height: 100%;
  }

  #<?= $addrPopupId ?> .result-wrap {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    font-family: sans-serif;
  }

  #<?= $addrPopupId ?> .search-cont {
    padding: 20px;
    background: #f0f0f0;
    flex: 0 0 auto;
  }

  #<?= $addrPopupId ?> .search-input {
    display: flex;
    align-items: center;
    height: 100%;
  }

  #<?= $addrPopupId ?> .search-input input {
    flex: 1;
    height: 45px;
    padding: 5px 10px;
    font-size: 15px;
    background-color: #ffff;
    border-radius: 7px;
  }

  #<?= $addrPopupId ?> .search-input input::placeholder {
    color: #999;
    opacity: 1;
    font-weight: 500;
  }

  #<?= $addrPopupId ?> .search-input .icon {
    margin-left: 5px;
  }

  #<?= $addrPopupId ?> .addr-popup-list {
    flex: 1 1 auto;
    display: flex;
    flex-direction: row;
    min-height: 100px;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    position: static;
    height: auto;
    margin-top: 0;
    font-size: inherit;
    float: none;
  }

  #<?= $addrPopupId ?> .addr-popup-search,
  #<?= $addrPopupId ?> .addr-popup-dept,
  #<?= $addrPopupId ?> .addr-popup-dept1,
  #<?= $addrPopupId ?> .addr-popup-dept2,
  #<?= $addrPopupId ?> .addr-popup-dept3 {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  #<?= $addrPopupId ?> .addr-popup-search {
    width: 100%;
  }

  #<?= $addrPopupId ?> .addr-popup-dept {
    width: 100%;
    display: flex;
    flex-direction: row;
  }

  #<?= $addrPopupId ?> .addr-popup-dept1 {
    width: 20%;
    border-right: 1px solid #ccc;
    border: none;
    border-right: 1px solid #ccc;
  }

  #<?= $addrPopupId ?> .addr-popup-dept2 {
    width: 40%;
    border-right: 1px solid #ccc;
    border: none;
    border-right: 1px solid #ccc;
  }

  #<?= $addrPopupId ?> .addr-popup-dept3 {
    width: 40%;
    border: none;
  }

  #<?= $addrPopupId ?> .addr-popup-title {
    padding: 5px 8px;
    background: #f0f0f0;
    font-weight: bold;
    flex: 0 0 auto;
    border-bottom: 1px solid #ccc;
    text-align: center;
  }

  #<?= $addrPopupId ?> .addr-popup-search ul,
  #<?= $addrPopupId ?> .addr-popup-dept1 ul,
  #<?= $addrPopupId ?> .addr-popup-dept2 ul,
  #<?= $addrPopupId ?> .addr-popup-dept3 ul {
    flex: 1 1 auto;
    margin: 0;
    padding: 0;
    list-style: none;
    overflow-y: auto;
    scrollbar-width: none;
  }

  #<?= $addrPopupId ?> .addr-popup-dept1 ul::-webkit-scrollbar,
  #<?= $addrPopupId ?> .addr-popup-dept2 ul::-webkit-scrollbar,
  #<?= $addrPopupId ?> .addr-popup-dept3 ul::-webkit-scrollbar,
  #<?= $addrPopupId ?> .addr-popup-search ul::-webkit-scrollbar {
    width: 0;
    height: 0;
  }

  #<?= $addrPopupId ?> .addr-popup-dept1 ul li.selectable.addr-popup-on,
  #<?= $addrPopupId ?> .addr-popup-dept2 ul li.selectable.addr-popup-on {
    background-color: #31A182;
    color: #fff;
  }

  #<?= $addrPopupId ?> .addr-popup-search ul li {
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

  #<?= $addrPopupId ?> .addr-popup-search ul li label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    cursor: pointer;
  }

  #<?= $addrPopupId ?> .addr-popup-search ul li.addr-popup-on label {
    background-color: #fff;
    color: #31A182;
  }

  #<?= $addrPopupId ?> .addr-popup-search ul li .text {
    flex: 1;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    text-overflow: ellipsis;
    cursor: pointer;
    padding: 0.5rem 1rem;
    padding-left: 2rem;
  }

  #<?= $addrPopupId ?> .addr-popup-search ul li input[type="checkbox"] {
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

  #<?= $addrPopupId ?> .addr-popup-search ul li label::before {
    content: "";
    display: block;
    position: absolute;
    left: 5px;
    width: 1.25rem;
    height: 1.25rem;
    background: url(/resource/shop/mobile/img/ic_chkbox2025_off.svg) no-repeat 0 0;
    background-size: contain;
    border-radius: 100%;
    z-index: 1;
  }

  #<?= $addrPopupId ?> .addr-popup-search ul li.addr-popup-on label::before {
    background: url(/resource/shop/mobile/img/ic_chkbox2025_on.svg) no-repeat 0 0;
    background-size: contain;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-box {
    flex: 0 0 auto;
    max-height: 225px;
    overflow-y: auto;
    padding-bottom: 0;
    box-sizing: border-box;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-box .addr-popup-div1 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 5px;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-box .addr-popup-div2 {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 15px;
    margin-left: 10px;
  }

  #<?= $addrPopupId ?> .addr-popup-dept1 ul li,
  #<?= $addrPopupId ?> .addr-popup-dept2 ul li,
  #<?= $addrPopupId ?> .addr-popup-dept3 ul li {
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

  #<?= $addrPopupId ?> .addr-popup-dept1 ul li label,
  #<?= $addrPopupId ?> .addr-popup-dept2 ul li label,
  #<?= $addrPopupId ?> .addr-popup-dept3 ul li label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    cursor: pointer;
  }

  #<?= $addrPopupId ?> .addr-popup-dept1 ul li.addr-popup-on label,
  #<?= $addrPopupId ?> .addr-popup-dept2 ul li.addr-popup-on label,
  #<?= $addrPopupId ?> .addr-popup-dept3 ul li.addr-popup-on label {
    background-color: #fff;
    color: #31A182;
  }

  #<?= $addrPopupId ?> .addr-popup-dept1 ul li .text,
  #<?= $addrPopupId ?> .addr-popup-dept2 ul li .text,
  #<?= $addrPopupId ?> .addr-popup-dept3 ul li .text {
    flex: 1;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    cursor: pointer;
    padding: 0.5rem 1rem;
  }

  #<?= $addrPopupId ?> .addr-popup-dept1 ul li input[type="checkbox"],
  #<?= $addrPopupId ?> .addr-popup-dept2 ul li input[type="checkbox"],
  #<?= $addrPopupId ?> .addr-popup-dept3 ul li input[type="checkbox"] {
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

  #<?= $addrPopupId ?> .addr-popup-dept1 ul li label::after,
  #<?= $addrPopupId ?> .addr-popup-dept2 ul li label::after,
  #<?= $addrPopupId ?> .addr-popup-dept3 ul li label::after {
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

  #<?= $addrPopupId ?> .addr-popup-dept1 ul li.addr-popup-on label::after,
  #<?= $addrPopupId ?> .addr-popup-dept2 ul li.addr-popup-on label::after,
  #<?= $addrPopupId ?> .addr-popup-dept3 ul li.addr-popup-on label::after {
    background: url(/resource/shop/mobile/img/ic_chkbox2025_on.svg) no-repeat center center;
    background-size: contain;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-box .addr-popup-div1 {
    padding: 10px 10px;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-box .addr-popup-div1-title {
    font-weight: 600;
    font-size: 1.1rem;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-box .addr-popup-sel-cnt {
    margin-left: 10px;
    color: #31A182;
    font-weight: 600;
    margin-right: 10px;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-box .addr-popup-div1-desc {
    font-size: 0.8rem;
    color: #777;
  }

  #<?= $addrPopupId ?> .addr-popup-selbtn {
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

  #<?= $addrPopupId ?> .addr-popup-selbtn-close {
    cursor: pointer;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-box .addr-popup-div3 {
    margin-top: 5px;
    display: flex;
    gap: 10px;
    justify-content: space-between;
    margin-right: 10px;
    margin-left: 10px;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-init {
    flex: 3;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-apply {
    flex: 7;
  }

  #<?= $addrPopupId ?> .addr-popup-sel-init,
  #<?= $addrPopupId ?> .addr-popup-sel-apply {
    background-color: #31A182;
    font-size: 1.3rem;
    color: white;
    font-weight: 900;
    text-align: center;
    padding: 1rem 0;
    border-radius: 80px;
    cursor: pointer;
  }

  #<?= $addrPopupId ?> .search-input {
    position: relative;
  }

  #<?= $addrPopupId ?> .form-text {
    width: 100%;
    padding-right: 36px;
    height: 40px;
    box-sizing: border-box;
  }

  #<?= $addrPopupId ?> .icon-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
  }

  #<?= $addrPopupId ?> .icon-btn img {
    width: 20px;
    height: 20px;
  }

  #<?= $addrPopupId ?> .addr-popup-hidden {
    height: 0 !important;
    overflow: hidden !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    flex: 0 0 0 !important;
    opacity: 0;
    pointer-events: none;
  }

  #<?= $addrPopupId ?> .addr-popup-visible {
    height: auto;
    opacity: 1;
    flex: 1 1 auto;
    pointer-events: auto;
  }
</style>
<div id="<?= $addrPopupId ?>" class="layer-panel card-type" tabindex="-1">
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
              <input type="text" id="txt_addr_<?= $addrPopupId ?>" name="txt_addr_<?= $addrPopupId ?>" placeholder="예시 : 신림동, 관악구" autocomplete="off" required class="form-text">
              <button id="iconBtn_<?= $addrPopupId ?>" type="button" class="icon-btn">
                <img id="iconImg_<?= $addrPopupId ?>" src="/resource/shop/mobile/img/ic_search2025.svg" alt="search icon">
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
            <div class="addr-popup-div1">
              <span class="addr-popup-div1-title">선택한 지역</span><span class="addr-popup-sel-cnt">0</span><span class="addr-popup-div1-desc">(최대 1개)</span>
            </div>
            <div class="addr-popup-div2">
            </div>
            <div class="addr-popup-div3">
              <div class="addr-popup-sel-init">초기화</div>
              <div class="addr-popup-sel-apply">적용하기</div>
            </div>
          </div>

        </div>
        <script>
        (function() {
          var PANEL_ID = '<?= $addrPopupId ?>';
          var $panel = $('#' + PANEL_ID);
          var $txtInput = $panel.find('[id^="txt_addr_"]');
          var $iconBtn = $panel.find('[id^="iconBtn_"]');
          var $iconImg = $panel.find('[id^="iconImg_"]');
          var $searchAddr = $panel.find('.addr-popup-search');
          var $dept = $panel.find('.addr-popup-dept');
          var debounceTimer = null;

          function restoreSelectedRegionsToDiv2() {
            var pageType = window.__addrPopupPageType || 'default';
            var storageKey_regions = 'selectedRegions_' + pageType;
            var storageKey_area = 'searchAreaStr_' + pageType;
            var storageKey_name = 'addrName_' + pageType;

            var $div2 = $panel.find('.addr-popup-div2');
            $div2.find('.addr-popup-selbtn').remove();

            var savedSelectedRegions = sessionStorage.getItem(storageKey_regions);
            var savedSearchAreaStr = sessionStorage.getItem(storageKey_area);
            var savedAddrName = sessionStorage.getItem(storageKey_name);

            if (savedSelectedRegions && savedSearchAreaStr) {
              try {
                var selectedRegions = JSON.parse(savedSelectedRegions);
                var searchArea = JSON.parse(savedSearchAreaStr);

                searchArea.forEach(function(area, index) {
                  var $selbtn = $('<div>')
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

          // PANEL_ID가 changeAreaExpose인 경우 전역 복원 함수 노출 (웹/모바일 페이지에서 호출 가능)
          if (PANEL_ID === 'changeAreaExpose') {
            window.restoreExposeAddrPopup = function() {
              restoreSelectedRegionsToDiv2();
            };
          }

          $(document).ready(function() {
            setTimeout(restoreSelectedRegionsToDiv2, 100);
          });

          $(document).on('click', '.js-toggle-panel[aria-controls="' + PANEL_ID + '"]', function() {
            var panel = document.getElementById(PANEL_ID);
            if (panel && panel.hasAttribute('hidden')) {
              panel.removeAttribute('hidden');
            }
            setTimeout(restoreSelectedRegionsToDiv2, 50);
          });

          if (typeof window.__hideAllRegionsInAddrPopup === 'undefined') {
            window.__hideAllRegionsInAddrPopup = false;
          }

          function shouldHideAllRegions() {
            return window.__hideAllRegionsInAddrPopup === true;
          }

          window.applyAddrPopupAllRegionFilter = function() {
            if (!shouldHideAllRegions()) return;

            $panel.find('.addr-popup-search ul li, .addr-popup-dept2 ul li, .addr-popup-dept3 ul li').each(function() {
              var text = $(this).find('.text').text().trim();
              if (text.endsWith('전체')) {
                $(this).remove();
              }
            });

            $panel.find('.addr-popup-div2 .addr-popup-selbtn').each(function() {
              var text = $(this).clone().children().remove().end().text().trim();
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
              $panel.find('.addr-popup-search ul').empty();
            }
          }

          $(document).on('input', '#txt_addr_' + PANEL_ID, function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
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

          $panel.on('click', 'li[data-step][data-addr]', function(e) {
            var $li = $(this);
            var step = $li.data('step');
            var addr = $li.data('addr');
            var hasCheckbox = $li.find('input[type="checkbox"]').length > 0;

            if ($li.data('processing')) return;
            $li.data('processing', true);
            setTimeout(function() { $li.removeData('processing'); }, 200);

            if (hasCheckbox) {
              searchSelect(step, addr, $li);
            } else {
              searchLocation(step, addr, $li);
            }

            $panel.find('.addr-popup-search, .addr-popup-dept1, .addr-popup-dept2, .addr-popup-dept3').each(function() {
              var $targetContainer = $(this);
              var $targetLi = $targetContainer.find('li[data-step="' + step + '"][data-addr="' + addr + '"]').not($li);
              if (!$targetLi.length) return;

              var targetHasCheckbox = $targetLi.find('input[type="checkbox"]').length > 0;

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
            var hasCheckbox = $clickedLi.find('input[type="checkbox"]').length > 0;
            if (hasCheckbox) return;

            var $parentDiv = $clickedLi.closest('.addr-popup-dept1, .addr-popup-dept2');
            if (!$parentDiv.length) return;

            $parentDiv.find('li.addr-popup-on').each(function() {
              if ($(this).find('input[type="checkbox"]').length === 0) {
                $(this).removeClass('addr-popup-on');
              }
            });

            $clickedLi.addClass('addr-popup-on');
            get_location_data(step, addr);
          }

          function searchSelect(step, addr, $clickedLi) {
            var isSelected = $clickedLi.toggleClass('addr-popup-on').hasClass('addr-popup-on');
            var currentStep = parseInt(step, 10);
            var $div2 = $panel.find('.addr-popup-div2');
            var text = $clickedLi.text().trim();
            var combinedText = "";
            var textParts = text.split(" ");

            if (shouldHideAllRegions() && textParts[textParts.length - 1] === "전체") {
              $clickedLi.removeClass('addr-popup-on');
              return;
            }

            if (textParts[textParts.length - 1] === "전체") {
              if (currentStep === 1) {
                combinedText = replaceRegionNames(text);
              } else {
                combinedText = removeRegionNames(text);
              }
            } else {
              combinedText = textParts[textParts.length - 1];
            }

            var existingBtnSelector = '.addr-popup-selbtn[data-step="' + step + '"][data-addr="' + addr + '"]';
            if (isSelected) {
              var $existing = $div2.find(existingBtnSelector);
              var alreadyExists = $existing.length > 0;
              var currentCount = $panel.find('.addr-popup-selbtn').length;
              var removeCount = 0;

              $panel.find('.addr-popup-selbtn[data-step][data-addr]').each(function() {
                var $btn = $(this);
                var btnStep = parseInt($btn.data('step'), 10);
                var btnAddr = $btn.data('addr');

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

              var maxSelection = typeof window.__addrPopupMaxSelection !== 'undefined'
                ? window.__addrPopupMaxSelection
                : 1;

              var projectedCount = currentCount + (alreadyExists ? 0 : 1) - removeCount;

              if (projectedCount > maxSelection) {
                $clickedLi.removeClass('addr-popup-on');
                return;
              }

              if (!alreadyExists) {
                var $selbtn = $('<div>')
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

            $panel.find('li[data-step][data-addr]').each(function() {
              var $li = $(this);
              if ($li.is($clickedLi)) return;

              var hasCheckbox = $li.find('input[type="checkbox"]').length > 0;
              if (!hasCheckbox) return;

              var liStep = parseInt($li.data('step'), 10);
              var liAddr = $li.data('addr');

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

            $panel.find('.addr-popup-selbtn[data-step][data-addr]').each(function() {
              var $btn = $(this);
              var btnStep = parseInt($btn.data('step'), 10);
              var btnAddr = $btn.data('addr');

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

          $panel.on('click', '.addr-popup-selbtn', function() {
            var $btn = $(this);
            var step = $btn.data('step');
            var addr = $btn.data('addr');

            $panel.find('li[data-step="' + step + '"][data-addr="' + addr + '"]').removeClass('addr-popup-on');
            $btn.remove();
            updateSelectedCount();

            var newNames = [];
            $panel.find('.addr-popup-div2 .addr-popup-selbtn').each(function() {
              var text = $(this).clone().children().remove().end().text().trim();
              newNames.push(text);
            });
            window.selectedRegions = newNames;

            if (window.updateSelectedRegionsDisplay) {
              window.updateSelectedRegionsDisplay();
            }
          });

          $panel.find('input[type="checkbox"]').on('click', function(e) {
            e.stopPropagation();
          });

          function get_location_data(step, addr) {
            var addrParts = addr.trim().split(" ");
            var sido_name = addrParts[0] || "";
            var gugun_name = addrParts[1] ? addrParts[1] + (addrParts[2] ? " " + addrParts[2] : "") : "";

            var currentStep = parseInt(step, 10);
            var searchStep = currentStep + 1;

            var params = {
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
                  $panel.find('.addr-popup-dept2 ul, .addr-popup-dept3 ul').empty();

                  var addr1 = addr;
                  var isSelected = $panel.find('.addr-popup-selbtn[data-step="1"][data-addr="' + addr1 + '"]').length > 0;

                  if (!shouldHideAllRegions()) {
                    var allLi = $('<li>')
                      .attr('data-step', '1')
                      .attr('data-addr', addr1)
                      .toggleClass('addr-popup-on', isSelected)
                      .append(
                        $('<label>').append(
                          $('<span>').addClass('text').text(replaceRegionNames(sido_name) + ' 전체'),
                          $('<input>').attr({
                            type: 'checkbox',
                            name: 'chk[]'
                          })
                        )
                      );
                    $panel.find('.addr-popup-dept2 ul').append(allLi);
                  }

                  $.each(ret.data, function(i, item) {
                    var addr2 = addr + ' ' + item.gugun_name;
                    var isSelected = $panel.find('.addr-popup-selbtn[data-step="2"][data-addr="' + addr2 + '"]').length > 0;

                    var newLi = $('<li>')
                      .attr('data-step', '2')
                      .attr('data-addr', addr2)
                      .addClass('selectable')
                      .toggleClass('addr-popup-on', isSelected)
                      .append(
                        $('<span>').addClass('text').text(item.gugun_name)
                      );
                    $panel.find('.addr-popup-dept2 ul').append(newLi);
                  });

                } else if (currentStep === 2) {
                  $panel.find('.addr-popup-dept3 ul').empty();

                  var addr2 = addr;
                  var isSelected = $panel.find('.addr-popup-selbtn[data-step="2"][data-addr="' + addr2 + '"]').length > 0;

                  if (!shouldHideAllRegions()) {
                    var allLi = $('<li>')
                      .attr('data-step', '2')
                      .attr('data-addr', addr2)
                      .toggleClass('addr-popup-on', isSelected)
                      .append(
                        $('<label>').append(
                          $('<span>').addClass('text').text(gugun_name + ' 전체'),
                          $('<input>').attr({
                            type: 'checkbox',
                            name: 'chk[]'
                          })
                        )
                      );
                    $panel.find('.addr-popup-dept3 ul').append(allLi);
                  }

                  $.each(ret.data, function(i, item) {
                    var addr3 = addr + ' ' + item.b_dong_name;
                    var isSelected = $panel.find('.addr-popup-selbtn[data-step="3"][data-addr="' + addr3 + '"]').length > 0;

                    var newLi = $('<li>')
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
                    $panel.find('.addr-popup-dept3 ul').append(newLi);
                  });
                }
              }
            });
          }

          if (!window['__addrPopupEventsRegistered_' + PANEL_ID]) {
            window['__addrPopupEventsRegistered_' + PANEL_ID] = true;

            $panel.on('click', '.addr-popup-sel-init', function() {
              window.backupSelectedRegions = window.selectedRegions ? window.selectedRegions.slice() : [];
              window.backupSearchArea = [];
              $panel.find('.addr-popup-selbtn').each(function() {
                window.backupSearchArea.push({
                  addr: $(this).data('addr'),
                  step: $(this).data('step')
                });
              });

              $panel.find('.addr-popup-dept2 ul, .addr-popup-dept3 ul').empty();
              $txtInput.val('');
              $panel.find('li.addr-popup-on').removeClass('addr-popup-on');
              $panel.find('.addr-popup-div2 .addr-popup-selbtn').remove();
              updateSelectedCount();

              window.selectedRegions = [];
              updateSearchView();
              resetAllRegionFlag();

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

            $panel.on('click', '.addr-popup-sel-apply', function(e) {
              e.stopPropagation();

              var addrNames = [];

              if ($panel.find('.addr-popup-selbtn').length > 0) {
                $panel.find('.addr-popup-selbtn').each(function() {
                  var text = $(this).clone().children().remove().end().text().trim();
                  addrNames.push(text);
                });
                var addrName = addrNames.join(', ');

                window.selectedRegions = addrNames.slice(0, 1);
              } else {
                var addrName = '지역을 선택해 주세요';
                window.selectedRegions = [];
              }

              var searchArea = [];
              var search_addr = [];

              $panel.find('.addr-popup-selbtn').each(function() {
                var addr = $(this).data('addr');
                var step = $(this).data('step');
                if (addr) {
                  searchArea.push({
                    addr: addr,
                    step: step
                  });
                  search_addr.push(addr);
                }
              });
              var searchAreaStr = JSON.stringify(searchArea);

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

              $panel.removeClass("on");
              resetAllRegionFlag();
            });

            var panel = document.getElementById(PANEL_ID);
            if (panel) {
              var closeBtn = panel.querySelector('.btn-close-panel');
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

                    $panel.find('.addr-popup-div2 .addr-popup-selbtn').remove();
                    if (window.backupSearchArea && window.backupSearchArea.length > 0) {
                      window.backupSearchArea.forEach(function(area, index) {
                        var displayText = window.selectedRegions[index] || '';
                        var $selbtn = $('<div>')
                          .addClass('addr-popup-selbtn')
                          .attr('data-step', area.step)
                          .attr('data-addr', area.addr)
                          .text(displayText)
                          .append($('<div>').addClass('addr-popup-selbtn-close').text('x'));
                        $panel.find('.addr-popup-div2').append($selbtn);
                      });
                    }
                    updateSelectedCount();

                    $panel.find('.addr-popup-dept2 ul, .addr-popup-dept3 ul').empty();
                    $txtInput.val('');
                    $panel.find('li.addr-popup-on').removeClass('addr-popup-on');
                    updateSearchView();

                    panel.classList.remove('on');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                  }, 50);
                });
              }
            }
          }

          function updateSelectedCount() {
            var count = $panel.find('.selbtn').length;
            $panel.find('.addr_sel_cnt').text(count);
          }

          function fSearchKeyUp() {
            var search_keyword = $txtInput.val().trim();
            if (search_keyword.length < 2) return;

            var params = {
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

                $panel.find('.addr-popup-search ul').empty();

                if (ret.data) {
                  $.each(ret.data, function(i, item) {
                    var addr = item.search_b_dong;
                    var step = item.step;
                    if (shouldHideAllRegions() && step < 3) return;
                    var isSelected = $panel.find('.addr-popup-selbtn[data-step="' + step + '"][data-addr="' + addr + '"]').length > 0;

                    var displayText = step < 3 ? addr + ' 전체' : addr;

                    var newLi = $('<li>')
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
                    $panel.find('.addr-popup-search ul').append(newLi);
                  });
                }
              }
            });
          }

          function replaceRegionNames(text) {
            var regionMap = {
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
              function(match) { return regionMap[match]; }
            );
          }

          function removeRegionNames(text) {
            var regionMap = {
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
              function(match) { return regionMap[match]; }
            );
          }
        })();
        </script>

      </div>
    </div>
  </div>
</div>
