<script language="javascript">
    //검색처리
    function getSearchApi(keyword, type, xpos, ypos, callback) {
        $("#searchApiFrm").find("[name=keyword]").val(keyword);

        getAddr(type, xpos, ypos, callback);

    }

    function getAddr(type, xpos, ypos, callback) {
        // 적용예 (api 호출 전에 검색어 체크)
        if (!checkSearchedWord(document.searchApiFrm.keyword)) {
            return;
        }

        $.ajax({
            url: "https://business.juso.go.kr/addrlink/addrLinkApiJsonp.do" //인터넷망
                ,
            type: "post",
            data: $("#searchApiFrm").serialize(),
            dataType: "jsonp",
            crossDomain: true,
            success: function(jsonStr) {
                $("#list").html("");
                //$('.search-input').html(jsonStr); //test
                var errCode = jsonStr.results.common.errorCode;
                var errDesc = jsonStr.results.common.errorMessage;
                if (errCode != "0") {
                    commonAlert(errCode + ":" + errDesc, function() {
                        callback("");
                    });
                } else {
                    if (jsonStr != null && jsonStr.results.juso.length > 0) { //false
                        makeListJson(type, jsonStr, callback);
                    } else if (jsonStr != null && jsonStr.results.juso.length == 0) { // true
                        //alert(xpos);
                        //alert(ypos);
                        var item_var = " data-doro='" + document.searchApiFrm.keyword.value + "' ";
                        item_var += " data-jibun='" + document.searchApiFrm.keyword.value + "' ";
                        item_var += " data-zipcode='' ";
                        item_var += " data-admCd='' ";
                        item_var += " data-rnMgtSn='' ";
                        item_var += " data-udrtYn='' ";
                        item_var += " data-buldMnnm='' ";
                        item_var += " data-buldSlno='' ";
                        item_var += " data-xpos='" + xpos + "' ";
                        item_var += " data-ypos='" + ypos + "' ";
                        item_var += " data-addrshort='" + document.searchApiFrm.keyword.value + "' ";

                        var htmlStr = "";
                        htmlStr += "<li class='post-item btnItem' style='cursor:pointer;' " + item_var + " >";
                        htmlStr += "<dl class='post-info'>";
                        htmlStr += "<dt class='sr-text'>상세주소</dt>";
                        htmlStr += "<dd>";
                        htmlStr += "    <dl class='address'>";
                        htmlStr += "        <dt>주소</dt>";
                        htmlStr += "        <dd>";
                        htmlStr += "            <button type='button' class='btn' style='padding-left: 10px;'> " + document.searchApiFrm.keyword.value + "</button>";
                        htmlStr += "        </dd>";
                        htmlStr += "    </dl>";
                        htmlStr += "</dd>";
                        htmlStr += "</dl>";
                        htmlStr += "</li>";
                        callback(htmlStr);

                    } else {
                        commonAlert('현재 위치로 주소 찾기에 실패하였습니다.직접 검색해주세요..', function() {});
                    }
                }
            },
            error: function(xhr, status, error) {
                commonAlert(error, function() {
                    callback("");
                });
            }
        });
    }


    function makeListJson(type, jsonStr, callback) {
        var htmlStr = "";
        //$('#txt_addr').html(window.location.pathname);//test
        let addrDong = new Set();
        $(jsonStr.results.juso).each(function() {
            var item_var = " data-doro='" + this.roadAddr + "' ";
            item_var += " data-jibun='" + this.jibunAddr + "' ";
            item_var += " data-zipcode='" + this.zipNo + "' ";
            item_var += " data-admCd='" + this.admCd + "' ";
            item_var += " data-rnMgtSn='" + this.rnMgtSn + "' ";
            item_var += " data-udrtYn='" + this.udrtYn + "' ";
            item_var += " data-buldMnnm='" + this.buldMnnm + "' ";
            item_var += " data-buldSlno='" + this.buldSlno + "' ";
            item_var += " data-addrshort='" + this.siNm + " " + this.sggNm + " " + this.emdNm + "' ";

            if (window.location.pathname == '/resume/index_map' || window.location.pathname == '/recruit/index_map') {
                console.log(this);
                if (!addrDong.has(this.siNm + " " + this.sggNm + " " + this.emdNm)) {
                    addrDong.add(this.siNm + " " + this.sggNm + " " + this.emdNm);

                    htmlStr += "<li class='post-item btnItem' style='cursor:pointer;' " + item_var + " >";
                    htmlStr += "<dl class='post-info'>";
                    htmlStr += "<dt class='sr-text'>읍면동까지만</dt>";
                    htmlStr += "<dt class='sr-text'>상세주소</dt>";
                    htmlStr += "<dd>";
                    htmlStr += "    <dl class='address'>";
                    htmlStr += "        <dt>읍면동 단위</dt>";
                    htmlStr += "        <dd>";
                    htmlStr += "            <button type='button' class='btn' style='padding-left: 10px;'> " + this.siNm + " " + this.sggNm + " " + this.emdNm + "</button>";
                    htmlStr += "        </dd>";
                    htmlStr += "    </dl>";
                    htmlStr += "</dd>";
                    htmlStr += "</dl>";
                    htmlStr += "</li>";

                }

            } else {
                htmlStr += "<li class='post-item btnItem' style='cursor:pointer;' " + item_var + " >";
                htmlStr += "<dl class='post-info'>";
                htmlStr += "<dt class='sr-text'>기초구역번호(새 우편번호)</dt>";
                htmlStr += "<dd class='post-code'>";
                htmlStr += "    <button type='button' class='btn'>" + this.zipNo + "</button>";
                htmlStr += "</dd>";
                htmlStr += "<dt class='sr-text'>상세주소</dt>";
                htmlStr += "<dd>";
                htmlStr += "    <dl class='address'>";
                htmlStr += "        <dt>도로명</dt>";
                htmlStr += "        <dd>";
                htmlStr += "            <button type='button' class='btn gps_search'>" + this.roadAddr + "</button>";
                htmlStr += "        </dd>";
                htmlStr += "        <dt>지번</dt>";
                htmlStr += "        <dd>";
                htmlStr += "            <button type='button' class='btn'>" + this.jibunAddr + "</button>";
                htmlStr += "        </dd>";
                htmlStr += "    </dl>";
                htmlStr += "</dd>";
                htmlStr += "</dl>";
                htmlStr += "</li>";
            }

        });

        if (htmlStr == "") {
            if (type == "gps") {
                commonAlert('현재 위치로 주소 찾기에 실패하였습니다.직접 검색해주세요.', function() {
                    $("#txt_addr_popup").focus();
                });
            } else {
                commonAlert('주소를 상세히 입력해 주시기 바랍니다.', function() {
                    $("#txt_addr_popup").focus();
                    callback(htmlStr);
                });
            }
        } else {
            callback(htmlStr);
        }
    }

    //받아줄 함수 상단에서 선언해야함. : selectAddrInfo
    // 도로명 또는 지번 버튼 클릭 시 해당 주소를 전달
    $(document).on("click", ".btnItem .btn", function(e) {
        e.stopPropagation(); // 부모 요소의 클릭 이벤트 방지

        var $btnItem = $(this).closest(".btnItem");
        var $clickedBtn = $(this);
        var clickedBtnText = $clickedBtn.text().trim();

        // 도로명 버튼인지 확인: gps_search 클래스가 있거나, 부모 dt가 "도로명"이거나, 버튼 텍스트가 도로명 주소와 일치
        var doro = $btnItem.attr("data-doro");
        var jibun = $btnItem.attr("data-jibun");
        var isDoro = $clickedBtn.hasClass("gps_search") ||
            $clickedBtn.closest("dt").text().trim() === "도로명" ||
            (doro && clickedBtnText === doro);

        console.log("[주소선택] 주소 버튼 클릭됨");
        console.log("  - 클릭한 버튼 텍스트:", clickedBtnText);
        console.log("  - 버튼 타입:", isDoro ? "도로명" : "지번");

        var post = $btnItem.attr("data-zipcode");
        var admCd = $btnItem.attr("data-admCd");
        var rnMgtSn = $btnItem.attr("data-rnMgtSn");
        var udrtYn = $btnItem.attr("data-udrtYn");
        var buldMnnm = $btnItem.attr("data-buldMnnm");
        var buldSlno = $btnItem.attr("data-buldSlno");
        var addrshort = $btnItem.attr("data-addrshort");
        var xpos = $btnItem.attr("data-xpos");
        var ypos = $btnItem.attr("data-ypos");

        // 클릭한 버튼에 따라 전체 주소 선택 (도로명 또는 지번)
        var selectedAddr = isDoro ? doro : jibun;

        console.log("[주소선택] 추출된 주소 정보:", {
            zipcode: post,
            doro: doro,
            jibun: jibun,
            addrshort: addrshort,
            selectedAddr: selectedAddr,
            isDoro: isDoro,
            xpos: xpos,
            ypos: ypos
        });

        $("#gpsApiFrm").find("[name=admCd]").val(admCd);
        $("#gpsApiFrm").find("[name=rnMgtSn]").val(rnMgtSn);
        $("#gpsApiFrm").find("[name=udrtYn]").val(udrtYn);
        $("#gpsApiFrm").find("[name=buldMnnm]").val(buldMnnm);
        $("#gpsApiFrm").find("[name=buldSlno]").val(buldSlno);

        if ($btnItem.attr("data-xpos") != undefined && $btnItem.attr("data-xpos") != '') {
            console.log("[주소선택] GPS 좌표가 있는 경우 - get_addr_name 호출");
            comAjax("/common_proc/get_addr_name", {
                query: jibun
            }, function(ret) {
                console.log("[주소선택] get_addr_name 응답:", ret);
                if (ret.code == "00") {

                    $(".btnItem").css("background-color", "");
                    $btnItem.css("background-color", "#eee");
                    //alert(xpos);
                    //alert(ypos);
                    console.log("[주소선택] selectAddrInfo 호출 (GPS 좌표 있음):", {
                        zipcode: post,
                        doro: doro,
                        jibun: jibun,
                        addr: selectedAddr,
                        x: xpos,
                        y: ypos
                    });
                    console.log("[주소선택] 현재 페이지 경로:", window.location.pathname);
                    console.log("[주소선택] selectAddrInfo 함수 존재 여부:", typeof selectAddrInfo);
                    console.log("[주소선택] selectAddrInfoRecruit 함수 존재 여부:", typeof selectAddrInfoRecruit);
                    if (typeof selectAddrInfo === 'function') {
                        selectAddrInfo(post, doro, jibun, selectedAddr, xpos, ypos);
                    } else {
                        console.error("[주소선택] selectAddrInfo 함수를 찾을 수 없습니다!");
                    }

                } else {
                    console.log("[주소선택] get_addr_name 실패:", ret);
                    commonAlert('GPS - 주소정보가 정확하지 않습니다. 위치를 검색하여 선택해주세요..', function() {});
                }
                return;
            });
            return;
        }

        $.ajax({
            url: "https://business.juso.go.kr/addrlink/addrCoordApiJsonp.do" // 좌표검색 OPEN API URL
                ,
            type: "post",
            data: $("#gpsApiFrm").serialize(),
            dataType: "jsonp" // 크로스도메인으로 인한 jsonp 이용, 검색결과형식 JSON
                ,
            crossDomain: true,
            success: function(jsonStr) { // jsonStr : 주소 검색 결과 JSON 데이터
                console.log(jsonStr);

                var errCode = jsonStr.results.common.errorCode;
                var errDesc = jsonStr.results.common.errorMessage;
                if (errCode != "0") {
                    commonAlert('GPS 정보 검색오류:' + errCode + "=" + errDesc, function() {});
                } else {
                    if (jsonStr != null) {
                        //위도 경도로 변환
                        var entX = (jsonStr.results.juso[0].entX);
                        var entY = (jsonStr.results.juso[0].entY);

                        //소수점 자르고 시작
                        var coord_X = Math.round(entX * 1000000) / 1000000;
                        var coord_Y = Math.round(entY * 1000000) / 1000000;
                        var point = [coord_X, coord_Y];

                        proj4.defs["EPSG:5179"] = "+proj=tmerc +lat_0=38 +lon_0=127.5 +k=0.9996 +x_0=1000000 +y_0=2000000 +ellps=GRS80 +units=m +no_defs"; //제공되는 좌표

                        let grs80 = proj4.Proj(proj4.defs["EPSG:5179"])
                        let wgs84 = proj4.Proj(proj4.defs["EPSG:4326"]); //경위도

                        let p = proj4.toPoint(point);
                        p = proj4.transform(grs80, wgs84, p);

                        //쇼트 주소 검색
                        console.log("[주소선택] GPS 좌표 변환 완료, get_addr_name 호출");
                        comAjax("/common_proc/get_addr_name", {
                            query: jibun
                        }, function(ret) {
                            console.log("[주소선택] get_addr_name 응답:", ret);
                            if (ret.code == "00") {
                                $(".btnItem").css("background-color", "");
                                $btnItem.css("background-color", "#eee");

                                console.log("[주소선택] selectAddrInfo 호출 (좌표 변환 후):", {
                                    zipcode: post,
                                    doro: doro,
                                    jibun: jibun,
                                    addr: selectedAddr,
                                    x: p.x,
                                    y: p.y
                                });
                                console.log("[주소선택] 현재 페이지 경로:", window.location.pathname);
                                console.log("[주소선택] selectAddrInfo 함수 존재 여부:", typeof selectAddrInfo);
                                console.log("[주소선택] selectAddrInfoRecruit 함수 존재 여부:", typeof selectAddrInfoRecruit);
                                if (typeof selectAddrInfo === 'function') {
                                    //selectAddrInfo(post,doro,jibun,ret.data.addr,p.x,p.y);
                                    selectAddrInfo(post, doro, jibun, selectedAddr, p.x, p.y);
                                } else {
                                    console.error("[주소선택] selectAddrInfo 함수를 찾을 수 없습니다!");
                                }
                            } else {
                                console.log("[주소선택] get_addr_name 실패:", ret);
                                commonAlert('현재 선택하신 주소는 GPS정보를 제공하지 않습니다. 다른주소를 선택해주세요..', function() {});
                            }
                        });
                    }
                }
            },
            error: function(xhr, status, error) {
                commonAlert('GPS 정보 검색시 오류가 발생하였습니다.', function() {});
            }
        });
    });

    //특수문자, 특정문자열(sql예약어의 앞뒤공백포함) 제거
    function checkSearchedWord(obj) {
        if (obj.value.length > 0) {
            //특수문자 제거
            var expText = /[%=><]/;
            if (expText.test(obj.value) == true) {
                commonAlert("특수문자를 입력 할수 없습니다.", function() {});
                obj.value = obj.value.split(expText).join("");
                return false;
            }

            //특정문자열(sql예약어의 앞뒤공백포함) 제거
            var sqlArray = new Array(
                //sql 예약어
                "OR", "SELECT", "INSERT", "DELETE", "UPDATE", "CREATE", "DROP", "EXEC",
                "UNION", "FETCH", "DECLARE", "TRUNCATE"
            );

            var regex;
            for (var i = 0; i < sqlArray.length; i++) {
                regex = new RegExp(sqlArray[i], "gi");
                if (regex.test(obj.value)) {
                    commonAlert("\"" + sqlArray[i] + "\"와(과) 같은 특정문자로 검색할 수 없습니다.", function() {});
                    obj.value = obj.value.replace(regex, "");
                    return false;
                }
            }
        }
        return true;
    }
</script>
<form name="searchApiFrm" id="searchApiFrm" method="post">
    <input type="hidden" name="currentPage" value="1" /> <!-- 요청 변수 설정 (현재 페이지. currentPage : n > 0) -->
    <input type="hidden" name="countPerPage" value="100" /><!-- 요청 변수 설정 (페이지당 출력 개수. countPerPage 범위 : 0 < n <= 100) -->
    <input type="hidden" name="resultType" value="json" /> <!-- 요청 변수 설정 (검색결과형식 설정, json) -->
    <input type="hidden" name="confmKey" value="<?= $g_setting["addr_doro"] ?>" /><!-- 요청 변수 설정 (승인키) -->
    <input type="hidden" name="keyword" value="" /><!-- 요청 변수 설정 (키워드) -->
</form>

<form name="gpsApiFrm" id="gpsApiFrm" method="post">
    <input type="hidden" name="confmKey" value="<?= $g_setting["addr_gps"] ?>" /><!-- 요청 변수 설정 (승인키) -->
    <input type="hidden" name="resultType" value="json" /> <!-- 요청 변수 설정 (검색결과형식 설정, json) -->
    <input type="hidden" name="admCd" value="" /> <!-- 요청 변수 설정 (행정구역코드) -->
    <input type="hidden" name="rnMgtSn" value="" /><!-- 요청 변수 설정 (도로명코드) -->
    <input type="hidden" name="udrtYn" value="" /> <!-- 요청 변수 설정 (지하여부) -->
    <input type="hidden" name="buldMnnm" value="" /><!-- 요청 변수 설정 (건물본번) -->
    <input type="hidden" name="buldSlno" value="" /><!-- 요청 변수 설정 (건물부번) -->

</form>