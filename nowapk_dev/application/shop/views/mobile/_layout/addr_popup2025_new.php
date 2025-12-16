 <style>
   .layer-panel.card-type .panel-header {
     padding-top: 1.25rem;
     padding-bottom: 1.25rem;
     background: inherit;
   }

   .layer-panel.card-type .panel-header .panel-title {
     background: inherit;
   }

   .layer-panel.card-type .panel-header .panel-title em {
     font-style: normal;
     color: #1B9D7D;
   }

   .layer-panel.card-type .panel-body {
     padding: 0;
   }

   .layer-panel.card-type .panel-container {
     height: 100%;
     max-height: 95%;
   }

   .layer-panel .panel-content {
     position: relative;
     height: 100%;
   }

   .result-wrap {
     height: 100%;
     margin: 0;
     display: flex;
     flex-direction: column;
     font-family: sans-serif;
   }

   .search-cont {
     padding: 20px;
     background: #f0f0f0;
     flex: 0 0 auto;
   }

   .search-input {
     display: flex;
     align-items: center;
     height: 100%;
   }

   .search-input input {
     flex: 1;
     height: 30px;
     padding: 5px 10px;
     font-size: 14px;
     background-color: #ffff;
     border: 1px solid #ccc;
     border-radius: 3px;
   }

   .search-input .icon {
     margin-left: 5px;
   }

   .addr_list {
     flex: 1 1 auto;
     display: flex;
     flex-direction: row;
     min-height: 100px;
     border-top: 1px solid #777;
     border-bottom: 1px solid #777;
   }

   .search_addr,
   .addr_dept,
   .addr_dept1,
   .addr_dept2,
   .addr_dept3 {
     display: flex;
     flex-direction: column;
     height: 100%;
   }

   .search_addr {
     width: 100%;
   }

   .addr_dept {
     width: 100%;
     display: flex;
     flex-direction: row;
   }

   .addr_dept1 {
     width: 20%;
     border-right: 1px solid #777;
   }

   .addr_dept2 {
     width: 40%;
     border-right: 1px solid #777;
   }

   .addr_dept3 {
     width: 40%;
   }

   .addr_title {
     padding: 5px 8px;
     background: #f0f0f0;
     font-weight: bold;
     flex: 0 0 auto;
     border-bottom: 1px solid #ccc;
     text-align: center;
   }

   .search_addr ul,
   .addr_dept1 ul,
   .addr_dept2 ul,
   .addr_dept3 ul {
     flex: 1 1 auto;
     margin: 0;
     padding: 0;
     list-style: none;
     overflow-y: auto;
   }

   .addr_dept1 ul li.selectable:hover,
   .addr_dept2 ul li.selectable:hover,
   .addr_dept1 ul li.selectable.addr_on,
   .addr_dept2 ul li.selectable.addr_on {
     background-color: #31A182;
     color: #fff;
   }

   .search_addr ul li {
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

   .search_addr ul li label {
     display: flex;
     align-items: center;
     justify-content: space-between;
     width: 100%;
     cursor: pointer;
   }

   .search_addr ul li label:hover,
   .search_addr ul li.addr_on label {
     background-color: #fff;
     color: #31A182;
   }

   .search_addr ul li .text {
     flex: 1;
     overflow: hidden;
     white-space: nowrap;
     position: relative;
     text-overflow: ellipsis;
     cursor: pointer;
     padding: 0.5rem 1rem;
     padding-left: 2rem;
   }

   .search_addr ul li input[type="checkbox"] {
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

   .search_addr ul li label::before {
     content: "";
     display: block;
     position: absolute;
     /*top: 50%;*/
     left: 5px;
     width: 1.25rem;
     height: 1.25rem;
     background: url(https://nowjob.co.kr/resource/shop/mobile/img/ic_chkbox2025_off.svg) no-repeat 0 0;
     background-size: contain;
     border-radius: 100%;
     z-index: 1;
   }

   .search_addr ul li.addr_on label::before {
     background: url(https://nowjob.co.kr/resource/shop/mobile/img/ic_chkbox2025_on.svg) no-repeat 0 0;
     background-size: contain;
   }

   .addr_list_sel_box {
     flex: 0 0 auto;
     max-height: 225px;
     overflow-y: auto;
     padding: 10px;
     box-sizing: border-box;
   }

   .addr_list_sel_box .div1 {
     display: flex;
     align-items: center;
     gap: 10px;
     margin-bottom: 5px;
   }

   .addr_list_sel_box .div2 {
     display: flex;
     flex-wrap: wrap;
     gap: 5px;
     margin-bottom: 15px;
   }

   .addr_dept1 ul li,
   .addr_dept2 ul li,
   .addr_dept3 ul li {
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

   .addr_dept1 ul li label,
   .addr_dept2 ul li label,
   .addr_dept3 ul li label {
     display: flex;
     align-items: center;
     justify-content: space-between;
     width: 100%;
     cursor: pointer;
   }

   .addr_dept1 ul li label:hover,
   .addr_dept2 ul li label:hover,
   .addr_dept3 ul li label:hover,
   .addr_dept2 ul li.addr_on label,
   .addr_dept3 ul li.addr_on label {
     background-color: #fff;
     color: #31A182;
   }

   .addr_dept1 ul li .text,
   .addr_dept2 ul li .text,
   .addr_dept3 ul li .text {
     flex: 1;
     overflow: hidden;
     white-space: nowrap;
     text-overflow: ellipsis;
     cursor: pointer;
     padding: 0.5rem 1rem;
   }

   .addr_dept1 ul li input[type="checkbox"],
   .addr_dept2 ul li input[type="checkbox"],
   .addr_dept3 ul li input[type="checkbox"] {
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

   .addr_dept1 ul li label::after,
   .addr_dept2 ul li label::after,
   .addr_dept3 ul li label::after {
     content: "";
     position: absolute;
     right: 5px;
     top: 50%;
     transform: translateY(-50%);
     width: 1.25rem;
     height: 1.25rem;
     background: url(https://nowjob.co.kr/resource/shop/mobile/img/ic_chkbox2025_off.svg) no-repeat center center;
     background-size: contain;
     border-radius: 100%;
     z-index: 1;
   }

   .addr_dept1 ul li.addr_on label::after,
   .addr_dept2 ul li.addr_on label::after,
   .addr_dept3 ul li.addr_on label::after {
     background: url(https://nowjob.co.kr/resource/shop/mobile/img/ic_chkbox2025_on.svg) no-repeat center center;
     background-size: contain;
   }

   .addr_list_sel_box .div1 {
     padding: 10px 10px;
   }

   .addr_list_sel_box .div1title {
     font-weight: 600;
     font-size: 1.1rem;
   }

   .addr_list_sel_box .addr_sel_cnt {
     margin-left: 10px;
     color: #31A182;
     font-weight: 600;
     margin-right: 10px;
   }

   .addr_list_sel_box .div1desc {
     font-size: 0.8rem;
     color: #777;
   }

   .selbtn {
     background-color: #57CCAF;
     color: #fff;
     font-size: 1rem;
     padding: 8px;
     border-radius: 6px;
     position: relative;
     display: flex;
     align-items: center;
     gap: 5px;
     cursor: pointer;
   }

   .selbtnclose {
     cursor: pointer;
   }

   .addr_list_sel_box .div3 {
     margin-top: 5px;
     display: flex;
     gap: 10px;
     justify-content: space-between;
   }

   .addr_sel_init {
     flex: 3;
   }

   .addr_sel_apply {
     flex: 7;
   }

   .addr_sel_init,
   .addr_sel_apply {
     background-color: #31A182;
     font-size: 1rem;
     color: white;
     font-weight: 600;
     text-align: center;
     padding: 12px;
     border-radius: 6px;
     cursor: pointer;
   }

   .search-input {
     position: relative;
   }

   .form-text {
     width: 100%;
     padding-right: 36px;
     height: 40px;
     box-sizing: border-box;
   }

   .icon-btn {
     position: absolute;
     right: 8px;
     top: 50%;
     transform: translateY(-50%);
     background: none;
     border: none;
     padding: 0;
     cursor: pointer;
   }

   .icon-btn img {
     width: 20px;
     height: 20px;
   }

   .hidden {
     height: 0 !important;
     overflow: hidden !important;
     padding: 0 !important;
     margin: 0 !important;
     border: none !important;
     flex: 0 0 0 !important;
     opacity: 0;
     pointer-events: none;
   }

   .visible {
     height: auto;
     opacity: 1;
     flex: 1 1 auto;
     pointer-events: auto;
   }
 </style>
 <div id="changeArea" class="layer-panel card-type" tabindex="-1">
   <div class="panel-container">
     <div class="panel-header">
       <h2 class="panel-title align-center"><em>검색지역</em> 설정</h2>
       <button type="button" aria-controls="changeArea" class="btn-close-popup  js-close-popup js-toggle-panel"><span class="sr-text">닫기</span></button>
     </div>
     <div class="panel-body">
       <div class="panel-content">
         <div class="result-wrap">

           <div class="search-cont">
             <div class="search-input">
               <input type="text" id="txt_addr" name="txt_addr" placeholder="예시 : 신림동, 관악구" autocomplete="off" required class="form-text">
               <button id="iconBtn" type="button" class="icon-btn">
                 <img id="iconImg" src="https://nowjob.co.kr/resource/shop/mobile/img/ic_search2025.svg" alt="search icon">
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
                   <li data-step="1" data-addr="제주도" class="selectable"><span class="text">제주</span></li>
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
             <?php
              $area_list_json = isset($search_txt["searchAreaStr"]) ? $search_txt["searchAreaStr"] : '[]';
              $addr_name_list_str = isset($search_txt["addrName"]) ? $search_txt["addrName"] : '';

              $area_list = json_decode($area_list_json, true);
              $addr_names = array_map('trim', explode(',', $addr_name_list_str));
              $area_count = is_array($area_list) ? count($area_list) : 0;
              ?>
             <div class="div1">
               <span class="div1title">선택한 지역</span><span class="addr_sel_cnt"><?= $area_count; ?></span><span class="div1desc">(최대 3개)</span>
             </div>
             <div class="div2">
               <?php if (is_array($area_list)): ?>
                 <?php foreach ($area_list as $i => $area): ?>
                   <?php
                    $addr_full = $area['addr'];
                    $step = $area['step'];
                    $addr_short = isset($addr_names[$i]) ? $addr_names[$i] : '';
                    ?>
                   <div class="selbtn" data-step="<?= htmlspecialchars($step) ?>" data-addr="<?= htmlspecialchars($addr_full) ?>">
                     <?= htmlspecialchars($addr_short) ?>
                     <div class="selbtnclose">x</div>
                   </div>
                 <?php endforeach; ?>
               <?php endif; ?>
             </div>
             <div class="div3">
               <div class="addr_sel_init">초기화</div>
               <div class="addr_sel_apply">적용하기</div>
             </div>
           </div>

         </div>
         <script>
           const $txtInput = $('#txt_addr');
           const $iconBtn = $('#iconBtn');
           const $iconImg = $('#iconImg');
           const $searchAddr = $('.search_addr');
           const $dept = $('.addr_dept');
           const $targetContainers = $('.search_addr, .addr_dept1, .addr_dept2, .addr_dept3');
           let debounceTimer = null;

           function updateSearchView() {
             if ($.trim($txtInput.val()) !== '') {
               $iconImg.attr('src', 'https://nowjob.co.kr/resource/shop/mobile/img/ic_close2025.svg').attr('alt', 'clear');
               $searchAddr.removeClass('hidden').addClass('visible');
               $dept.removeClass('visible').addClass('hidden');
             } else {
               $iconImg.attr('src', 'https://nowjob.co.kr/resource/shop/mobile/img/ic_search2025.svg').attr('alt', 'search icon');
               $searchAddr.removeClass('visible').addClass('hidden');
               $dept.removeClass('hidden').addClass('visible');
               $('.search_addr ul').empty();
             }
           }

           $(document).on('input', '#txt_addr', function() {
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

           function searchLocation(step, addr, $clickedLi) {
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
             get_location_data(step, addr);
           }

           function searchSelect(step, addr, $clickedLi) {
             const isSelected = $clickedLi.toggleClass('addr_on').hasClass('addr_on');
             const currentStep = parseInt(step, 10);
             const $div2 = $('.div2');
             const text = $clickedLi.text().trim();
             var combinedText = "";
             const textParts = text.split(" ");
             if (textParts[textParts.length - 1] === "전체") {
               if (currentStep === 1) {
                 combinedText = replaceRegionNames(text)
               } else {
                 combinedText = removeRegionNames(text);
               }
             } else {
               combinedText = textParts[textParts.length - 1];
             }

             const existingBtnSelector = `.selbtn[data-step="${step}"][data-addr="${addr}"]`;
             if (isSelected) {
               const $existing = $div2.find(existingBtnSelector);
               const alreadyExists = $existing.length > 0;
               const currentCount = $('.selbtn').length;
               let removeCount = 0;
               $('.selbtn[data-step][data-addr]').each(function() {
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

               const projectedCount = currentCount + (alreadyExists ? 0 : 1) - removeCount;

               if (projectedCount > 3) {
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
                 $li.removeClass('addr_on');
               }
             });

             $('.selbtn[data-step][data-addr]').each(function() {
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


           $(document).on('click', '.selbtn', function() {
             const $btn = $(this);
             const step = $btn.data('step');
             const addr = $btn.data('addr');

             $(`li[data-step="${step}"][data-addr="${addr}"]`).removeClass('addr_on');
             $btn.remove();
             updateSelectedCount();
           });

           $('input[type="checkbox"]').on('click', function(e) {
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
                   $('.addr_dept2 ul, .addr_dept3 ul').empty();

                   const addr1 = addr;
                   const isSelected = $(`.selbtn[data-step="1"][data-addr="${addr1}"]`).length > 0;

                   const allLi = $('<li>')
                     .attr('data-step', '1')
                     .attr('data-addr', addr1)
                     .toggleClass('addr_on', isSelected)
                     .append(
                       $('<label>').append(
                         $('<span>').addClass('text').text(replaceRegionNames(sido_name) + ' 전체'),
                         $('<input>').attr({
                           type: 'checkbox',
                           name: 'chk[]'
                         })
                       )
                     );
                   $('.addr_dept2 ul').append(allLi);

                   $.each(ret.data, function(i, item) {
                     const addr2 = addr + ' ' + item.gugun_name;
                     const isSelected = $(`.selbtn[data-step="2"][data-addr="${addr2}"]`).length > 0;

                     const newLi = $('<li>')
                       .attr('data-step', '2')
                       .attr('data-addr', addr2)
                       .addClass('selectable')
                       .toggleClass('addr_on', isSelected)
                       .append(
                         $('<span>').addClass('text').text(item.gugun_name)
                       );
                     $('.addr_dept2 ul').append(newLi);
                   });

                 } else if (currentStep === 2) {
                   $('.addr_dept3 ul').empty();

                   const addr2 = addr;
                   const isSelected = $(`.selbtn[data-step="2"][data-addr="${addr2}"]`).length > 0;

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
                   $('.addr_dept3 ul').append(allLi);

                   $.each(ret.data, function(i, item) {
                     const addr3 = addr + ' ' + item.b_dong_name;
                     const isSelected = $(`.selbtn[data-step="3"][data-addr="${addr3}"]`).length > 0;

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
                     $('.addr_dept3 ul').append(newLi);
                   });
                 }
               },
               error: function(xhr, status, error) {
                 console.error("데이터 가져오기 오류:", error);
               }
             });
           }

           $(document).on('click', '.addr_sel_init', function() {
             $('.addr_dept2 ul, .addr_dept3 ul').empty();
             $txtInput.val('');
             $('li.addr_on').removeClass('addr_on');
             $('.div2 .selbtn').remove();
             updateSelectedCount();
             updateSearchView();
           });

           $(document).on('click', '.addr_sel_apply', function() {

             const count = $('.selbtn').length;
             //if (count<1) return;
             var addrNames = [];

             if ($('.selbtn').length > 0) {
               $('.selbtn').each(function() {
                 const text = $(this).clone().children().remove().end().text().trim();
                 addrNames.push(text);
               });
               addrName = addrNames.join(', ');
               $('.addrName').text(addrName);
             } else {
               addrName = '전체';
               $('.addrName').text(addrName);
             }

             const searchArea = [];
             search_addr = [];

             $('.selbtn').each(function() {
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

             // index_search.php의 전역 함수 호출하여 선택된 지역 정보 전달
             if (typeof window.onRegionSelectionComplete === 'function') {
               window.onRegionSelectionComplete(addrNames);
             }

             list_page = 1;
             $("#changeArea").removeClass("on");
             $("body").css("overflow", "auto");
             $(list_id).html("");
             getBoardList("Y");
           });

           function updateSelectedCount() {
             const count = $('.selbtn').length;

             $('.addr_sel_cnt').text(count);
           }

           function fSearchKeyUp() {
             const search_keyword = $('#txt_addr').val().trim();
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

                 $('.search_addr ul').empty();

                 if (ret.data) {
                   $.each(ret.data, function(i, item) {
                     const addr = item.search_b_dong;
                     const step = item.step;
                     const isSelected = $(`.selbtn[data-step="${step}"][data-addr="${addr}"]`).length > 0;

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
                     $('.search_addr ul').append(newLi);
                   });

                 }
               },
               error: function(xhr, status, error) {
                 console.error("데이터 가져오기 오류:", error);
               }
             });
           }

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