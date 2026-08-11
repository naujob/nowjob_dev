<script language="javascript">
	//검색처리
	/**
	 * getSearchApi
	 * 주소 검색어(API) 요청을 처리하는 함수
	 * 
	 * @param {string} keyword - 검색할 주소 키워드
	 * @param {string} type - 검색 유형 (예: "search", "gps" 등)
	 * @param {number|string} xpos - X(경도) 좌표값
	 * @param {number|string} ypos - Y(위도) 좌표값
	 * @param {function} callback - 검색 결과 처리 콜백 함수
	 */
	function getSearchApi(keyword, type, xpos, ypos, callback) {
		$("#searchApiFrm").find("[name=keyword]").val(keyword);

		getAddr(type, xpos, ypos, callback);

	}

	/**
	 * getAddr
	 * 주소 검색 API 호출 및 결과 처리 함수
	 *
	 * @param {string} type - 검색 유형 (예: "search", "gps" 등)
	 * @param {number|string} xpos - X(경도) 좌표 또는 0
	 * @param {number|string} ypos - Y(위도) 좌표 또는 0
	 * @param {function} callback - 검색 결과를 처리할 콜백 함수
	 *
	 * 설명:
	 * - 검색어 유효성 체크를 수행하고, 실패 시 함수 실행을 중단합니다.
	 * - juso.go.kr 주소검색 API를 비동기 호출합니다.
	 * - 호출 결과의 오류 코드와 메시지를 확인하여 예외 상황에 대응합니다.
	 * - 정상적인 검색 결과(juso 배열이 1개 이상) 경우 makeListJson을 호출하고, 결과가 없으면 기본 주소 리스트를 생성합니다.
	 */
	function getAddr(type, xpos, ypos, callback) {
		// 적용예 (api 호출 전에 검색어 체크)
		if (!checkSearchedWord(document.searchApiFrm.keyword)) {
			return;
		}
		//console.log($("#searchApiFrm").serialize());
		//return; //test

		//alert($("#searchApiFrm").serialize());
		//alert($("#searchApiFrm").find("[name=keyword]").val());
		$.ajax({
			url: "https://business.juso.go.kr/addrlink/addrLinkApiJsonp.do" //인터넷망
				,
			type: "post",
			data: $("#searchApiFrm").serialize(),
			dataType: "jsonp",
			crossDomain: true,
			success: function(jsonStr) {
				/**
				 *   {
					"results": {
					"juso": [
					{
					"zipNo": "12345",                    // 우편번호
					"roadAddr": "경기 시흥시 정왕로 123",  // 도로명주소 ← 최신 표준
					"jibunAddr": "경기 시흥시 정왕동 456", // 지번주소 (구식)
					"admCd": "4192010600",               // 행정구역코드
					"rnMgtSn": "412821234567"            // 도로명관리번호
										}
									]
								}
							}
				* 
				*/
				$("#list").html("");
				var errCode = jsonStr.results.common.errorCode;
				var errDesc = jsonStr.results.common.errorMessage;
				if (errCode != "0") {
					commonAlert(errCode + ":" + errDesc, function() {
						callback("");
					});
				} else {
					//alert(jsonStr.results.juso.length);
					if (jsonStr != null && jsonStr.results.juso.length > 0) { // false
						makeListJson(type, jsonStr, callback);
					} else if (jsonStr != null && jsonStr.results.juso.length == 0) { // true
						var htmlStr = "";
						htmlStr += "<li class='post-item btnItem' style='cursor:pointer;' ";
						htmlStr += "data-doro='" + $("#searchApiFrm").find("[name=keyword]").val() + "' ";
						htmlStr += "data-jibun='" + $("#searchApiFrm").find("[name=keyword]").val() + "' ";
						htmlStr += "data-zipcode='' ";
						htmlStr += "data-admcd='' ";
						htmlStr += "data-rnmgtsn='' ";
						htmlStr += "data-udrtyn='0' ";
						htmlStr += "data-buldmnnm='' ";
						htmlStr += "data-buldslno='0' ";
						htmlStr += "data-xpos='" + xpos + "' ";
						htmlStr += "data-ypos='" + ypos + "' ";
						htmlStr += " >";
						htmlStr += "<dl class='post-info'>";
						htmlStr += "<dt class='sr-text'>기초구역번호(새 우편번호)</dt>";
						htmlStr += "<dd class='post-code'>";
						htmlStr += "    <button type='button' class='btn'>&nbsp;</button>";
						htmlStr += "</dd>";
						htmlStr += "<dt class='sr-text'>상세주소</dt>";
						htmlStr += "<dd>";
						htmlStr += "    <dl class='address'>";
						htmlStr += "        <dt>주소</dt>";
						htmlStr += "        <dd>";
						htmlStr += "            <button type='button' class='btn gps_search'>" + $("#searchApiFrm").find("[name=keyword]").val() + "</button>";
						htmlStr += "        </dd>";
						htmlStr += "    </dl>";
						htmlStr += "</dd>";
						htmlStr += "</dl>";
						htmlStr += "</li>";

						callback(htmlStr);
					} else {
						commonAlert('현재 위치로 주소 찾기에 실패하였습니다.직접 검색해주세요..', function() {
							$("#txt_addr").focus();
						});
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
		//alert(JSON.stringify(jsonStr));
		$(jsonStr.results.juso).each(function() {
			var item_var = " data-doro='" + this.roadAddr + "' ";
			item_var += " data-jibun='" + this.jibunAddr + "' ";
			item_var += " data-zipcode='" + this.zipNo + "' ";
			item_var += " data-admCd='" + this.admCd + "' ";
			item_var += " data-rnMgtSn='" + this.rnMgtSn + "' ";
			item_var += " data-udrtYn='" + this.udrtYn + "' ";
			item_var += " data-buldMnnm='" + this.buldMnnm + "' ";
			item_var += " data-buldSlno='" + this.buldSlno + "' ";

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
		});

		if (htmlStr == "") {
			if (type == "gps") {
				commonAlert('현재 위치로 주소 찾기에 실패하였습니다.직접 검색해주세요.', function() {
					$("#txt_addr").focus();
				});
			} else {
				commonAlert('주소를 상세히 입력해 주시기 바랍니다.', function() {
					$("#txt_addr").focus();
					callback(htmlStr);
				});
			}
		} else {
			callback(htmlStr);
		}
	}

	// 선택된 주소 정보를 저장할 전역 변수
	var selectedAddrData = null;

	//받아줄 함수 상단에서 선언해야함. : selectAddrInfo
	$(document).on("click", ".btnItem", function(e) {
		var $this = $(this);
		var $clickedElement = $(e.target); // 클릭된 실제 요소

		// 클릭된 요소가 버튼인지 확인
		var isButtonClick = $clickedElement.is('button.btn');
		var selectedAddr = null; // 선택된 주소 (도로명 또는 지번)
		var isDoro = false; // 도로명 버튼 클릭 여부

		// 버튼을 클릭한 경우, 도로명인지 지번인지 구분
		if (isButtonClick) {
			// 도로명 버튼은 'gps_search' 클래스를 가지고 있음
			isDoro = $clickedElement.hasClass('gps_search');

			// 클릭된 버튼의 텍스트를 확인하여 도로명/지번 구분
			// 도로명 버튼이 아니면 지번 버튼으로 간주
			if (!isDoro) {
				// 지번 버튼인지 확인 (도로명 버튼이 아니고, 우편번호 버튼도 아닌 경우)
				var $parentLi = $clickedElement.closest('.btnItem');
				var buttonText = $clickedElement.text().trim();
				var doroText = $parentLi.attr('data-doro');
				var jibunText = $parentLi.attr('data-jibun');

				// 버튼 텍스트가 지번 주소와 일치하면 지번 버튼
				if (buttonText === jibunText) {
					isDoro = false;
				} else if (buttonText === doroText) {
					isDoro = true;
				}
			}
		}

		var post = $(this).attr("data-zipcode");
		var doro = $(this).attr("data-doro");
		var jibun = $(this).attr("data-jibun");
		var admCd = $(this).attr("data-admCd");
		var rnMgtSn = $(this).attr("data-rnMgtSn");
		var udrtYn = $(this).attr("data-udrtYn");
		var buldMnnm = $(this).attr("data-buldMnnm");
		var buldSlno = $(this).attr("data-buldSlno");
		var xpos = $(this).attr("data-xpos");
		var ypos = $(this).attr("data-ypos");

		// 선택된 주소 결정 (버튼 클릭 시에만 구분, 그 외에는 기본값 사용)
		if (isButtonClick) {
			selectedAddr = isDoro ? doro : jibun;
		} else {
			// li 요소를 직접 클릭한 경우 기본값으로 도로명 사용
			selectedAddr = doro;
			isDoro = true;
		}

		$("#gpsApiFrm").find("[name=admCd]").val(admCd);
		$("#gpsApiFrm").find("[name=rnMgtSn]").val(rnMgtSn);
		$("#gpsApiFrm").find("[name=udrtYn]").val(udrtYn);
		$("#gpsApiFrm").find("[name=buldMnnm]").val(buldMnnm);
		$("#gpsApiFrm").find("[name=buldSlno]").val(buldSlno);

		// 주소 선택 시 바로 저장
		if ($(this).attr("data-xpos") != undefined && $(this).attr("data-xpos") != '') {
			if (typeof selectAddrInfo === 'function') {
				selectAddrInfo(post, doro, jibun, selectedAddr, xpos, ypos, "");
				$("#changeArea").removeClass("on");
			}
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

						// 바로 저장
						if (typeof selectAddrInfo === 'function') {
							selectAddrInfo(post, doro, jibun, selectedAddr, p.x, p.y, "");
							$("#changeArea").removeClass("on");
						}
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