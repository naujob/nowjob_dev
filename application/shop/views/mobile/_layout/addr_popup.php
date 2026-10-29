 <!-- 현재위치 변경 레이어팝업 -->
 <div id="changeArea" class="layer-panel card-type" tabindex="-1">
   <div class="panel-container">
     <!-- 핸들바 클릭 시 팝업 닫기 (main.js 문서 위임 핸들러 처리) -->
     <div class="bottom-sheet-handlebar"></div>
     <div class="panel-header">
       <h2 class="sr-text">현재 위치 변경</h2>
       <button type="button" aria-controls="changeArea" class="btn-close-popup js-toggle-panel"><span class="sr-text">닫기</span></button>
     </div>
     <div class="panel-body" style="/* overflow-y:hidden; */">
       <div class="panel-content">
         <style>
           /* .short_addr_box {
            display: none;
            position: absolute;
            top: 35px;
            left: 0px;
            width: 100%;
            height: 198px;
            overflow: auto;
            border: 1px solid #aaa;
            background-color: #fff;
            line-height: 2rem;
            padding: 5px;
            z-index: 10;
          } */

           /* 상세주소 입력 영역 스타일 */
           .address-detail-section {
             display: none;
             margin-top: 1rem;
             padding: 1rem;
             background-color: #f9f9f9;
             border-radius: 8px;
             border: 1px solid #e0e0e0;
           }

           .address-detail-section.show {
             display: block;
           }

           .address-detail-section .selected-address {
             margin-bottom: 0.75rem;
             padding: 0.75rem;
             background-color: #fff;
             border-radius: 6px;
             font-size: 0.9375rem;
             color: #333;
             border: 1px solid #e0e0e0;
           }

           .address-detail-section .selected-address-label {
             font-size: 0.8125rem;
             color: #666;
             margin-bottom: 0.25rem;
           }

           .address-detail-section .selected-address-text {
             font-weight: 500;
             color: #222;
           }

           .address-detail-section .detail-input-wrapper {
             margin-bottom: 0.75rem;
           }

           .address-detail-section .detail-input-wrapper label {
             display: block;
             font-size: 0.875rem;
             color: #666;
             margin-bottom: 0.5rem;
           }

           .address-detail-section .detail-input-wrapper input {
             width: 100%;
             height: 3rem;
             padding: 0 1rem;
             font-size: 1rem;
             color: #222;
             border: 1px solid #BEBFC9;
             border-radius: 6px;
             background: #fff;
             box-sizing: border-box;
           }

           .address-detail-section .detail-input-wrapper input::placeholder {
             color: #999;
           }

           .address-detail-section .detail-input-wrapper input:focus {
             outline: none;
             border-color: #1B9D7D;
           }

           .address-detail-section .btn-confirm {
             width: 100%;
             height: 3rem;
             background-color: #1B9D7D;
             color: #fff;
             border: none;
             border-radius: 6px;
             font-size: 1rem;
             font-weight: 500;
             cursor: pointer;
           }

           .address-detail-section .btn-confirm:hover {
             background-color: #158a6f;
           }

           .address-detail-section .btn-cancel {
             width: 100%;
             height: 3rem;
             margin-top: 0.5rem;
             background-color: #fff;
             color: #666;
             border: 1px solid #BEBFC9;
             border-radius: 6px;
             font-size: 1rem;
             cursor: pointer;
           }

           .address-detail-section .btn-cancel:hover {
             background-color: #f5f5f5;
           }

           /* 여백 정리: 핸들바·헤더·본문·안내문의 상하 여백을 줄임 (#changeArea 범위로 한정) */
           #changeArea .bottom-sheet-handlebar {
             margin-top: 0;
             padding: 16px 0;
           }

           #changeArea .panel-header {
             
             padding: 0;
           }

           #changeArea .panel-body {
             padding: 0.5rem 1.375rem 1.25rem;
           }

           #changeArea .tip {
             margin-top: 1.5rem;
             padding-top: 1.25rem;
           }
         </style>
         <div class="page-join">
           <h2 class="primary-title align-center" style="margin-top: 0; margin-bottom: 1rem;"><em>위치를 설정</em>해주세요!</h2>
           <div class="find-loc-form">
             <form id="frm" onsubmit="return false" autocomplete="off">
               <div class="form-row">
                 <label class="search-cont">
                   <!-- <span class="form-label">도로명주소</span> -->
                   <div class="search-input">
                     <input type="text" id="txt_addr" name="txt_addr" placeholder="예시 : 주흥1길 12, 부산 센텀중앙로 97" autocomplete="off" aria-required="true" required class="form-text" onkeyup="fSearchKeyUp(this);">
                     <a id="btnSearchAddr" class="icon" href="javascript:void(0);">
                       <img src="/resource/shop/mobile/img/common/search.png" alt="주소 검색" />
                     </a>
                     <!-- <div class="short_addr_box"></div> -->
                   </div>
                 </label>
               </div>

               <!-- 현재 위치로 주소찾기 기능 사용 안함 -->
               <!-- <button type="button" id="btnGps" class="btn-primary btn-find-loc" style="margin-top: 1rem;">현재 위치로 주소찾기</button> -->
               <!-- <span id="mygps" style="margin-top: 10px; display: inline-block; color: #777;"></span> -->
               <!-- <a href="javascript:initAddrInfo();" class="btn-loc-init">초기화</a> -->

               <!-- 검색결과 -->
               <div class="result-wrap" style="margin:0;">
                 <!-- 조회목록 -->
                 <ol class="post-list hidden">

                 </ol>
                 <!-- //조회목록 -->
                 <div class="post-tip tip" style="display:block;">
                   <p class="title">주소입력TIP</p>
                   <p>아래의 조합으로 검색하시면 더 정확한 결과로 검색됩니다.</p>
                   <dl>
                     <dt>도로명+건물번호</dt>
                     <dd>예시 : 주흥1길 12, 부산 센텀중앙로 97</dd>
                     <dt>동/읍/면/리 + 번지</dt>
                     <dd>예시 : 반포동 742-16, 재송동 1208-2</dd>
                     <dt>건물명, 아파트명</dt>
                     <dd>예시 : 반포자이아파트, 센텀스카이비즈</dd>
                   </dl>
                   <p class="desc">2014년부터 도로명주소 전면 시행으로 인해 도로명주소가<br> 기본으로 보여집니다<br> 도로명 주소 확인 : https://www.juso.go.kr</p>
                 </div>

                 <style>
                   #changeArea .addr_list {
                     margin-top: 0;
                     display: block;
                     font-size: 0.9rem;
                     position: relative;
                     width: 100%;
                     height: 385px;
                   }



                   #changeArea .addr_dept1 {
                     width: 20%;
                     display: inline-block;
                     border: 1px solid #DCDCDC;
                     margin: 0;
                     float: left;
                     max-height: 206px;
                     overflow: auto;
                     background-color: #f6f6f6;
                     color: #333;
                   }

                   #changeArea .addr_dept2 {
                     width: 40%;
                     display: inline-block;
                     border: 1px solid #DCDCDC;
                     margin: 0;
                     float: left;
                     max-height: 206px;
                     overflow: auto;
                     color: #333;
                     height: 100%;
                   }

                   #changeArea .addr_dept3 {
                     width: 40%;
                     display: inline-block;
                     border: 1px solid #DCDCDC;
                     margin: 0;
                     float: left;
                     max-height: 206px;
                     overflow: auto;
                     color: #333;
                     height: 100%;
                   }

                   #changeArea .addr_on {
                     background-color: #57CCAF;
                   }

                   #changeArea .addr_list_sel_box {
                     position: absolute;
                     bottom: -10px;
                     width: 100%;
                     height: 190px;
                     border: 1px solid #dcdcdc;
                     border-radius: 20px 20px 0 0;
                     z-index: 10;
                     background-color: #fff;
                   }

                   #changeArea .addr_list_sel_box .div1 {
                     padding: 10px 10px;
                   }

                   #changeArea .addr_list_sel_box .div1title {
                     font-weight: 600;
                     font-size: 1.1rem;
                   }

                   #changeArea .addr_list_sel_box .addr_sel_cnt {
                     margin-left: 10px;
                     color: #57CCAF;
                     font-weight: 600;
                     margin-right: 10px;
                   }

                   #changeArea .addr_list_sel_box .div1desc {
                     color: #777;
                   }

                   #changeArea .addr_list_sel_box .div2 {
                     display: block;
                     width: 100%;
                     height: 66px;
                     overflow: auto;
                   }

                   #changeArea .addr_list_sel_box .div2b {
                     /* display: block; width: 200%; height: 30px; */
                   }

                   #changeArea .addr_list_sel_box .div2 .selbtn {
                     float: left;
                     margin-left: 10px;
                     background-color: #57CCAF;
                     color: #fff;
                     padding: 5px 10px;
                     border-radius: 10px;
                     font-size: 0.8rem;
                     margin-bottom: 5px;
                   }

                   #changeArea .addr_list_sel_box .div2 .selbtn .selbtnclose {
                     display: inline-block;
                     margin-left: 10px;
                   }

                   #changeArea .addr_list_sel_box .div3 {
                     display: block;
                     padding: 10px;
                     /* margin-top: 10px; */
                   }

                   #changeArea .addr_list_sel_box .div3 .addr_sel_init {
                     background-color: #57CCAF;
                     color: #fff;
                     border-radius: 7px;
                     padding: 5px;
                     width: 30%;
                     display: inline-block;
                     text-align: center;
                   }

                   #changeArea .addr_list_sel_box .div3 .addr_sel_apply {
                     background-color: #57CCAF;
                     color: #fff;
                     border-radius: 7px;
                     padding: 5px;
                     width: 67%;
                     display: inline-block;
                     float: right;
                     text-align: center;
                   }
                    #changeArea .btn-close-popup{
                      
                     top: -19px;
                    }
                 </style>
                 <div class="addr_list" style="display:none;">
                   <div class="addr_dept1">
                     <ul>
                       <li class="addr_on">서울</li>
                       <li>경기</li>
                       <li>인천</li>
                       <li>강원</li>
                       <li>대전</li>
                       <li>세종</li>
                       <li>충남</li>
                       <li>충북</li>
                       <li>부산</li>
                       <li>울산</li>
                       <li>경남</li>
                       <li>경북</li>
                       <li>대구</li>
                       <li>광주</li>
                       <li>전남</li>
                       <li>전북</li>
                       <li>제주</li>
                     </ul>
                   </div>
                   <div class="addr_dept2">
                     <ul>
                     </ul>
                   </div>
                   <div class="addr_dept3">
                     <ul>
                     </ul>
                   </div>
                   <div class="addr_list_sel_box">
                     <div class="div1"><span class="div1title">선택한 지역</span><span class="addr_sel_cnt">0</span><span class="div1desc">최대 5개</span></div>
                     <div class="div2">
                       <div class="div2b"></div>
                       <!--
                                      <div class="selbtn">개포1동<div class="selbtnclose" >x</div></div>
                                      <div class="selbtn">개포1동<div class="selbtnclose">x</div></div>
                                      <div class="selbtn">개포1동<div class="selbtnclose">x</div></div>
                                      -->
                     </div>
                     <div class="div3">
                       <div class="addr_sel_init" onclick="fAddrSelInit();">초기화</div>
                       <div class="addr_sel_apply" onclick="$('#txt_addr').css('color','#fff');$('#txt_addr').val(document.querySelector('.selbtn').getAttribute('data-addrall').replaceAll('_',' ')); searchText();">적용하기</div>
                     </div>
                   </div>
                 </div>
               </div>

             </form>
           </div>
         </div>

       </div>
     </div>
   </div>
 </div>
 <!-- //현재위치 변경 레이어팝업 -->
 <script>
   $(document).ready(function() {
     // 팝업이 열릴 때 초기화
     $(document).on('click', '.js-toggle-panel[aria-controls="changeArea"], a[href="#changeArea"]', function() {
       // 팝업이 열리는 경우 초기화
       setTimeout(function() {
         if ($("#changeArea").hasClass("on")) {
           selectedAddrData = null;
         }
       }, 100);
     });

     $(".addr_dept1 li").click(function() {
       ////console.log($(this).html());
       $(".addr_dept1 li").removeClass('addr_on');
       $(this).addClass('addr_on');
       get_addrlist(1, $(this).html(), '');
     });

     $("#btnSearchAddr").click(function() {
       searchText();
     });

     $("#txt_addr").keyup(function(e) {
       if (e.keyCode == 13) {
         searchText();
       }
       return;
     });

     //위치 정보 검색 - 사용 안함
     // $("#btnGps").click(function() {
     //   // 사용자가 명시적으로 버튼을 클릭했을 때만 위치 권한 요청
     //   var os_type = "<?= $os_type ?>";

     //   if (os_type == "android") {
     //     APP_NOWAPK.getLocation();
     //   } else if (os_type == "ios") {
     //     window.webkit.messageHandlers.getLocation.postMessage({});
     //   } else {
     //     // 웹 브라우저용 로직
     //     if (navigator.geolocation) {
     //       navigator.geolocation.getCurrentPosition(function(position) {
     //         //$('#mygps').html(position.coords.latitude+', '+position.coords.longitude);

     //         comAjax("/common_proc/get_gps_address", {
     //           x: position.coords.longitude,
     //           y: position.coords.latitude
     //         }, function(ret) {
     //           if (ret.code == "00") {
     //             var parts = ret.data.split("^");
     //             var frontPart = "";
     //             var oldAddress = "";
     //             var loadAddress = "";
     //             var zipCode = "";
     //             var xpos = "";
     //             var ypos = "";

     //             frontPart = parts[0];
     //             if (parts.length > 1) {
     //               oldAddress = parts[1];
     //             }
     //             if (parts.length > 2) {
     //               loadAddress = parts[2];
     //             }
     //             if (parts.length > 3) {
     //               zipCode = parts[3];
     //             }
     //             if (parts.length > 4) {
     //               xpos = position.coords.longitude;
     //             }
     //             if (parts.length > 5) {
     //               ypos = position.coords.latitude;
     //             }

     //             var gpsAddress = '<li class="post-item btnItem" style="cursor:pointer;" ';
     //             gpsAddress += 'data-doro="' + loadAddress + '" ';
     //             gpsAddress += 'data-jibun="' + oldAddress + '" ';
     //             gpsAddress += 'data-zipcode="' + zipCode + '" ';
     //             gpsAddress += 'data-xpos="' + xpos + '" ';
     //             gpsAddress += 'data-ypos="' + ypos + '" ';
     //             gpsAddress += 'data-admcd="" data-rnmgtsn="" data-udrtyn="0" data-buldmnnm="" data-buldslno="">';
     //             gpsAddress += '<dl class="post-info"><dt class="sr-text">기초구역번호(새 우편번호)</dt>';
     //             if (zipCode != '') gpsAddress += '<dd class="post-code"><button type="button" class="btn">' + zipCode + '</button></dd>';
     //             gpsAddress += '<dt class="sr-text">상세주소</dt><dd><dl class="address">';
     //             if (loadAddress != '') gpsAddress += '<dt>도로명</dt><dd><button type="button" class="btn gps_search">' + loadAddress + '</button></dd>';
     //             gpsAddress += '<dt>지번</dt><dd><button type="button" class="btn">' + oldAddress + '</button></dd></dl></dd></dl></li>';

     //             getSearchApi(frontPart, "gps", position.coords.longitude, position.coords.latitude, function(html) {
     //               $(".post-list").removeClass("hidden");
     //               $(".post-list").html("");
     //               $(".post-list").append(gpsAddress);
     //               $(".post-list").append(html);
     //               clickFirstPostItem();

     //               if (html == "") {
     //                 commonAlert('주소검색이 되지 않는 지역입니다.직접 검색해주세요.', function() {});
     //               }
     //             })
     //           } else {
     //             commonAlert('주소검색이 되지 않는 지역입니다.직접 검색해주세요.', function() {});
     //           }
     //         });
     //       }, function(error) {
     //         if (error.message == "User denied Geolocation") {
     //           commonAlert('위치사용을 허용 또는  주소를 입력하여 조회 해주세요.', function() {});
     //         }
     //       }, {
     //         enableHighAccuracy: true,
     //         timeout: 10000,
     //         maximumAge: 0,
     //         timeout: Infinity
     //       });
     //     } else {
     //       commonAlert('GPS를 지원하지 않습니다.  주소를 입력하여 조회 해주세요.', function() {});
     //     }
     //   }
     // });


     get_addrlist(1, '서울', '');

   });

   var addrReadySearch = true;

   let debounceTimer;
   //var bSearchKeyUp = false;
   function fSearchKeyUp(obj) {

     clearTimeout(debounceTimer);
     //if(bSearchKeyUp) return;
     //bSearchKeyUp = true;
     var params = {};
     params["addr"] = $(obj).val().trim();
     if ($(obj).val().trim() == '') return;
     debounceTimer = setTimeout(function() {
       comAjax("/common_proc/get_addrlist_keylike", params, function(ret) {
         ////console.log(ret);
         /* $('.short_addr_box').html('<ul></ul>');
         for (var icnt = 0; icnt < ret.data.list.length; icnt++) {
           var item = '<li onclick="$(\'#txt_addr\').val(\'' + ret.data.list[icnt].ca_short_addr + '\'); $(\'.short_addr_box\').hide(); searchText();">' + ret.data.list[icnt].ca_short_addr + '</li>';
           $('.short_addr_box ul').append(item);
         }
         if (ret.data.list.length == 0) {
           $('.short_addr_box').hide();
         } else {
           $('.short_addr_box').show();
         } */
         //bSearchKeyUp = false;
       });
     }, 300); // 300ms 딜레이 (디바운싱)
   }

   function addr_dept2_li_click(obj) {
     //console.log($(obj).html());
     $(".addr_dept2 li").removeClass('addr_on');
     $(obj).addClass('addr_on');
     get_addrlist(2, $(obj).data('addr'), $(obj).html());
   }

   function addr_dept3_li_click(obj) {
     //console.log($(obj).html());
     $(".addr_dept3 li").removeClass('addr_on');
     $(obj).addClass('addr_on');

     let checkbox = obj.querySelector('input[type="checkbox"]');
     if (checkbox) {
       checkbox.checked = true;
       //   //console.log("체크박스 선택됨:", checkbox);
       // } else {
       //   console.warn("체크박스를 찾을 수 없습니다.");
     }

     faddrchk(checkbox);
   }

   function get_addrlist(depth, addr1, addr2) {
     var params = {};
     params["depth"] = depth;
     params["addr1"] = addr1;
     params["addr2"] = addr2;

     comAjax("/common_proc/get_addrlist", params, function(ret) {
       //console.log(ret);
       if (ret.code == "00") {
         var html = "";

         if (depth == 1) {
           $('.addr_dept2').html('<ul></ul>');
           var item = "";
         } else {
           $('.addr_dept3').html('<ul></ul>');
         }

         for (var icnt = 0; icnt < ret.data.list.length; icnt++) {
           if (depth == 1) {
             var ca_ad1 = ret.data.list[icnt].ca_ad1;
             var ca_ad2 = ret.data.list[icnt].ca_ad2;
             var ca_ad3 = ret.data.list[icnt].ca_ad3;

             var item = '<li data-addr="' + ret.data.list[icnt].ca_ad1.trim() + '" onclick="addr_dept2_li_click(this);">' + ret.data.list[icnt].ca_ad2.trim() + '</li>';
             if (ret.data.list[icnt].ca_ad2.trim() != '') {
               $('.addr_dept2 ul').append(item);
             }
           } else {
             var ca_ad1 = ret.data.list[icnt].ca_ad1;
             var ca_ad2 = ret.data.list[icnt].ca_ad2;
             var ca_ad3 = ret.data.list[icnt].ca_ad3;

             var item_chk = '<label class="form-checkbox" style="float: right;">';
             item_chk += '<input type="checkbox" name="chk[]" onchange="return faddrchk(this);" class="sr-text" data-addr="' + ret.data.list[icnt].ca_ad1.trim() + '" data-addr2="' + ret.data.list[icnt].ca_ad2.trim() + '" data-addr3="' + ret.data.list[icnt].ca_ad3.trim() + '" ';

             if ($('div.selbtn[data-addrall="' + ret.data.list[icnt].ca_ad1.trim() + '_' + ret.data.list[icnt].ca_ad2.trim() + '_' + ret.data.list[icnt].ca_ad3.trim() + '"]').length > 0) {
               item_chk += ' checked ';
             } else {
               //no action : unchecked
             }

             item_chk += '>';

             item_chk += '<span class="label-red"></span>';
             item_chk += '<span class="sr-text">지원하기 선택</span>';
             item_chk += '</label>';
             var item = '<li data-addr="' + ret.data.list[icnt].ca_ad1.trim() + '" data-addr2="' + ret.data.list[icnt].ca_ad2.trim() + '" onclick="addr_dept3_li_click(this);">' + ret.data.list[icnt].ca_ad3.trim() + ' ' + item_chk + '</li>';
             if (ret.data.list[icnt].ca_ad3.trim() != '') {
               $('.addr_dept3 ul').append(item);
             }
           }
         }

         //if(depth==1 && addrReadySearch){
         //  addr_dept2_li_click(this);
         //  addrReadySearch = false;
         //}


       } else {
         commonAlert(ret.msg, function() {});
         return;
       }
     });
   }

   function fAddrSelInit() {
     $('.div2b').html('');
     $('.addr_dept3 input[name="chk[]"]').prop('checked', false);
   }

   function faddrchk(obj) {
     var selcnt = parseInt($('.addr_sel_cnt').html());

     // 선택된 주소 정보를 조합하여 data-addrall 값 생성
     var addrAll = $(obj).data('addr').trim() + '_' + $(obj).data('addr2').trim() + '_' + $(obj).data('addr3').trim();

     // 이미 추가된 항목인지 확인 (중복 방지)
     if ($('.div2b').find('div.selbtn[data-addrall="' + addrAll + '"]').length > 0) {
       //console.log('이미 추가된 주소입니다.');
       return;
     }

     //console.log($(this).html());
     if (obj.checked) {
       if (selcnt >= 5) {
         $(obj).prop('checked', false);
         return false;
       }
       //console.log('true');
       $('.div2b').append('<div class="selbtn" data-addrall="' + $(obj).data('addr').trim() + '_' + $(obj).data('addr2').trim() + '_' + $(obj).data('addr3').trim() + '" data-addr="' + $(obj).data('addr').trim() + '" data-addr2="' + $(obj).data('addr2').trim() + '" data-addr3="' + $(obj).data('addr3').trim() + '">' + $(obj).data('addr3') + '<div class="selbtnclose" onclick="seladdrdel(this);">x</div></div>');
       selcnt++;
     } else {
       //console.log('false');
       $('div.selbtn[data-addrall="' + $(obj).data('addr').trim() + '_' + $(obj).data('addr2').trim() + '_' + $(obj).data('addr3').trim() + '"]').remove();
       selcnt--;
     }
     $('.addr_sel_cnt').html(selcnt);
   }

   function seladdrdel(obj) {
     $(obj).parent().remove();
     var selcnt = parseInt($('.addr_sel_cnt').html());
     selcnt--;
     $('.addr_sel_cnt').html(selcnt);
     $('input[name="chk[]"][data-addr="' + $(obj).parent().data('addr').trim() + '"][data-addr2="' + $(obj).parent().data('addr2').trim() + '"][data-addr3="' + $(obj).parent().data('addr3').trim() + '"]').prop('checked', false);
   }

   function searchText() {
     if ($("#txt_addr").val() == "") {
       commonAlert('검색하실 주소를 입력해주세요.', function() {
         $("#txt_addr").focus();
       });
       return;
     }
     searchApi($("#txt_addr").val());
   }

   function searchApi(keyword) {
     getSearchApi($("#txt_addr").val(), "search", 0, 0, function(html) {
       $(".post-list").html("");
       $(".post-list").append(html); // 검색결과 추가

       if (html == "") {
         $(".post-list").addClass("hidden");
         //$(".post-tip").removeClass("hidden");
       } else {
         //$(".post-tip").addClass("hidden");
         $(".post-list").removeClass("hidden");
       }

       // 자동 선택 제거 - 사용자가 직접 주소를 선택하도록 함
       // if ($(".post-list li").length > 0) {
       //   $(".post-list li:first").click();
       // }

     })
   }

   // getLocation() 함수는 더 이상 사용되지 않음
   // 버튼 클릭 핸들러에서 직접 위치 권한을 요청함
   // 이 함수는 returnLocation() 콜백과의 호환성을 위해 유지하되, 직접 호출 시 실행 차단
   function getLocation() {
     // 외부에서 직접 호출 시 실행 차단 (버튼 클릭을 통해서만 실행되어야 함)
     console.warn('getLocation() 함수는 직접 호출할 수 없습니다. "현재 위치로 주소찾기" 버튼을 클릭해주세요.');
     return;
   }

   function clickFirstPostItem() {
     // post-list 내부의 첫 번째 li 요소 선택
     let firstLi = document.querySelector(".post-list li");

     // li 요소가 존재하면 클릭 이벤트 실행
     if (firstLi) {
       firstLi.click();
       //console.log("첫 번째 li 요소가 클릭되었습니다.");
     } else {
       console.warn("post-list 내 li 요소가 없습니다.");
     }
   }

   function returnLocation(lat, lng) {
     // 위치 권한이 없거나 거부된 경우 조용히 종료 (팝업 표시 안 함)
     if (lat == "0" && lng == "0") {
       // 위치 권한이 없는 경우 팝업 없이 조용히 종료
       return;
     }

     //$('#mygps').html(lat+', '+lng);

     comAjax("/common_proc/get_gps_address", {
       x: lng,
       y: lat
     }, function(ret) {
       if (ret.code == "00") {
         var parts = ret.data.split("^");
         var frontPart = "";
         var oldAddress = "";
         var loadAddress = "";
         var zipCode = "";
         var xpos = "";
         var ypos = "";

         frontPart = parts[0];
         if (parts.length > 1) {
           oldAddress = parts[1];
         }
         if (parts.length > 2) {
           loadAddress = parts[2];
         }
         if (parts.length > 3) {
           zipCode = parts[3];
         }
         if (parts.length > 4) {
           xpos = lng;
         }
         if (parts.length > 5) {
           ypos = lat;
         }

         var gpsAddress = '<li class="post-item btnItem" style="cursor:pointer;" ';
         gpsAddress += 'data-doro="' + loadAddress + '" ';
         gpsAddress += 'data-jibun="' + oldAddress + '" ';
         gpsAddress += 'data-zipcode="' + zipCode + '" ';
         gpsAddress += 'data-xpos="' + xpos + '" ';
         gpsAddress += 'data-ypos="' + ypos + '" ';
         gpsAddress += 'data-admcd="" data-rnmgtsn="" data-udrtyn="0" data-buldmnnm="" data-buldslno="">';
         gpsAddress += '<dl class="post-info"><dt class="sr-text">기초구역번호(새 우편번호)</dt>';
         if (zipCode != '') gpsAddress += '<dd class="post-code"><button type="button" class="btn">' + zipCode + '</button></dd>';
         gpsAddress += '<dt class="sr-text">상세주소</dt><dd><dl class="address">';
         if (loadAddress != '') gpsAddress += '<dt>도로명</dt><dd><button type="button" class="btn gps_search">' + loadAddress + '</button></dd>';
         gpsAddress += '<dt>지번</dt><dd><button type="button" class="btn">' + oldAddress + '</button></dd></dl></dd></dl></li>';

         //ret.data
         getSearchApi(frontPart, "gps", lng, lat, function(html) {
           $(".post-list").removeClass("hidden");
           $(".post-list").html("");
           $(".post-list").append(gpsAddress);
           $(".post-list").append(html);
           if (html == "") {
             commonAlert('주소검색이 되지 않는 지역입니다.직접 검색해주세요.', function() {});
           }
         })
       } else {
         commonAlert('주소검색이 되지 않는 지역입니다.직접 검색해주세요.', function() {});
       }
     });
   }

   // 검색 완료 선택 처리
   function initAddrInfo() {
     var params = {};
     params["ad_zipcode"] = "";
     params["ad_jibun"] = "";
     params["ad_doro"] = "";
     params["ad_addr"] = "지역 선택 없음";
     params["ad_gps_x"] = 127.028361548;
     params["ad_gps_y"] = 37.496486063;

     comAjax("/common_proc/set_addr_info", params, function(ret) {
       if (ret.code == "00") {
         location.reload();
       } else {
         commonAlert(ret.msg, function() {});
         return;
       }
     });
   }
 </script>
 <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_search_api.php'); ?>