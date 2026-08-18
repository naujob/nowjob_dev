$(document).ready(function () {
	$("#chgPwd").click(function () {
		$("#chgPwdCont").addClass("on");
	});

	$(".btnClosePwd").click(function () {
		$("#chgPwdCont").removeClass("on");
	});

	$("#btnChgPwd").click(function () {
		if ($("#mm_pwd").val() == "") {
			commonAlert("기존 비밀번호를 입력해주세요.", function () {
				$("#mm_pwd").focus();
			});
			return;
		}

		if ($("#change_pwd").val() == "") {
			commonAlert("새 비밀번호를 입력해주세요.", function () {
				$("#change_pwd").focus();
			});
			return;
		}

		//데이터 확인
		if (chkPassword($("#change_pwd").val()) == false || $("#change_pwd").val() == "") {
			commonAlert("비밀번호는 8~20자 이내, 숫자/특수문자/영문대소문자를 사용해주세요.", function () {
				$("#change_pwd").focus();
			});
			return;
		}

		if ($("#change_pwd_c").val() == "") {
			commonAlert("새 비밀번호 확인을 입력해주세요.", function () {
				$("#change_pwd_c").focus();
			});
			return;
		}

		if ($("#change_pwd").val() != $("#change_pwd_c").val()) {
			commonAlert("새 비밀번호와 새 비밀번호 확인이 다릅니다.", function () {
				$("#change_pwd_c").focus();
			});
			return;
		}

		var params = $("#frm_pwd").serialize();
		comAjax("/setting_proc/change_pwd", params, function (ret) {
			if (ret.code == "00") {
				commonAlert("비밀번호가 변경되었습니다.", function () {
					location.replace("/mypage/index");
				});
			} else {
				commonAlert(ret.msg, function () { });
			}
		});
	});

	$(".btn-back").click(function () {
		history.back();
		return false;
	});

	$("#btnLogout").click(function () {
		location.href = "/member/logout";
	});

	$("a,button").click(function () {
		var scrollPosition = $("#app > .page").scrollTop();
		localStorage.setItem("divScrollPositon", scrollPosition);
	});

	$(document).on("click", ".click-item", function () {
		var link = $(this).attr("data-link");
		location.href = link;
	});

	//var recruiteWin;
	$(document).on("click", ".click-item-new", function () {
		var link = $(this).attr("data-link");
		window.open(link, '_blank');
		//location.href=link;
		//if (recruiteWin) {
		//	recruiteWin.location = link;
		//}else{
		//	recruiteWin = window.open(link, '_blank');
		//}
	});

	$(".btn-close-panel").click(function () {
		$(this).closest(".layer-panel").removeClass("on");
	});

	window.addEventListener('pageshow', function (event) {
		if (event.persisted || window.performance && window.performance.navigation.type == 2) {
			$("#app > .page").scrollTop(localStorage.getItem("divScrollPositon"));
		}
	});

	//북마크 처리
	$(document).on("click", ".btnBookMark", function () {
		var bk_key = $(this).attr("data-bk_key");
		var bk_type = $(this).attr("data-bk_type");

		var $relatedButtons = $(".btnBookMark").filter(function () {
			return (
				$(this).attr("data-bk_key") === bk_key &&
				$(this).attr("data-bk_type") === bk_type
			);
		});

		var params = {
			bk_key: bk_key,
			bk_type: bk_type
		};

		comAjax("/common_proc/bookmark", params, function (ret) {
			if (ret.code !== "00") {
				$relatedButtons.removeClass("on");
				commonAlert(ret.msg, function () { });
				return;
			}

			if (ret.data.yn === "Y") {
				$relatedButtons.addClass("on");
			} else {
				$relatedButtons.removeClass("on");
			}
		});
	});
	/*
	$(document).on("click",".btnBookMark",function(){
		var params = {};
		params["bk_key"] = $(this).attr("data-bk_key");
		params["bk_type"] = $(this).attr("data-bk_type");
		var target_user_id = $(this).attr("data-user_id");

		var $this = $(this);
			  comAjax("/common_proc/bookmark",params,function(ret){
					if(ret.code !="00"){
						$this.removeClass("on");
						commonAlert(ret.msg,function(){});
						return;
					}else{
						if(ret.data.yn =="Y"){
					$this.addClass("on");
				}else{
					$this.removeClass("on");
				}
					}
		});
	});
	*/

	$(".btn-toggle-menu").click(function () {
		callVibrate(400);
	});
});

//비밀번호체크
function chkPassword(value) {
	var pattern1 = /[0-9]/;
	var pattern2 = /[a-zA-Z]/;
	var pattern3 = /[~!@\#$%<>^&*]/;     // 원하는 특수문자 추가 제거
	if (!pattern1.test(value) || !pattern2.test(value) || !pattern3.test(value) || value.length < 8 || value.length > 20) {
		return false;
	}
	return true;
}

function showNoData(target) {
	var html = "";
	html += "<div class='nodata'>";
	html += "	<div class='img'><img src='/resource/shop/web/img/nodata.png' /></div>";
	html += "	<div class='msg'>검색 결과가 없습니다.</div>";
	html += "</div>";
	$(target).html(html);
}

function callVibrate(time) {
	console.log("");
	console.log("[callVibrate] : [start]");
	console.log("");

	// 네비게이션 사용해 진동 메소드 선언
	navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;

	if (navigator.vibrate) {
		console.log("");
		console.log("[callVibrate] : [working]");
		console.log("");

		navigator.vibrate(time); // 진동 수행 1000 = 1초
		// navigator.vibrate(0); // 진동 정지
	}
	else {
		console.log("");
		console.log("[callVibrate] : [not working]");
		console.log("");
		//alert("진동을 지원하지 않는 디바이스입니다 ....");
	}
};

//쿠키 설정
var setCookie = function (name, value, day) {
	var date = new Date();
	date.setTime(date.getTime() + day * 60 * 60 * 24 * 1000);
	document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
};

//쿠키 가져오기
var getCookie = function (name) {
	var value = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
	return value ? value[2] : null;
};

//쿠키 삭제
var deleteCookie = function (name) {
	var date = new Date();
	document.cookie = name + "= " + "; expires=" + date.toUTCString() + "; path=/";
}

//윈도우 센터 열기
function PopupCenter(url, title, w, h) {
	// Fixes dual-screen position                         Most browsers      Firefox
	var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
	var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

	var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
	var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

	var left = ((width / 2) - (w / 2)) + dualScreenLeft;
	var top = ((height / 2) - (h / 2)) + dualScreenTop;
	var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

	// Puts focus on the newWindow
	if (window.focus) {
		newWindow.focus();
	}
}


/**
 * 숫자 포맷
 */
Number.prototype.format = function () {
	if (this == 0) return 0;

	var reg = /(^[+-]?\d+)(\d{3})/;
	var n = (this + '');

	while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

	return n;
};

/**
* 숫자 포맷
 */
String.prototype.format = function () {
	var num = parseFloat(this);
	if (isNaN(num)) return "0";

	return num.format();
};

String.prototype.replaceAll = function (org, dest) {
	return this.split(org).join(dest);
}

// 주소 텍스트에서 광역·도 명칭을 정리해 짧은 주소로 반환
function fRemoveBigAdrJS(arg) {
	if (typeof arg !== "string") {
		return "";
	}

	var raw = arg.trim();
	if (raw === "") {
		return "";
	}

	// 쉼표 포함 시 첫 토큰만 사용
	var firstPart = raw.split(",")[0];

	var replacements = [
		["서울특별시 ", "서울시 "],
		["경기도 ", ""],
		["강원특별자치도 ", ""],
		["충청남도 ", ""],
		["충청북도 ", ""],
		["경상남도 ", ""],
		["경상북도 ", ""],
		["전라남도 ", ""],
		["전북특별자치도 ", ""],
		["제주특별자치도 ", ""],
		["세종특별자치시 ", "세종시 "],
		["광주광역시 ", "광주시 "],
		["인천광역시 ", "인천시 "],
		["울산광역시 ", "울산시 "],
		["부산광역시 ", "부산시 "],
		["대구광역시 ", "대구시 "],
		["대전광역시 ", "대전시 "],
		["경기 ", ""],
		["강원 ", ""],
		["충남 ", ""],
		["충북 ", ""],
		["경남 ", ""],
		["경북 ", ""],
		["전남 ", ""],
		["전북 ", ""],
		["제주 ", ""]
	];

	replacements.forEach(function (pair) {
		firstPart = firstPart.replace(pair[0], pair[1]);
	});

	var parts = firstPart.split(" ");
	if (parts.length >= 3) {
		// 세 번째 토큰이 숫자를 포함하면 첫 두 토큰만 사용
		if (/\d/.test(parts[2])) {
			return parts[0] + " " + parts[1];
		}
		return parts[0] + " " + parts[2];
	}

	return firstPart;
}


function showAjaxLoading() {
	$("body").append("<div id='ajax_loding'><img src='/resource/shop/mobile/img/common/loading.svg' alt='로딩 중' /></div>")
}

function hideAjaxLoading() {
	$("#ajax_loding").remove();
}

/**
 * 공용 ajax 처리
 * @param url : 호출URL
 * @param params : 파라미터
 * @param callback : 콜백
 */
var ajax_first_num = 0;
var ajax_prev_url = "";
function comAjax(url, params, callback) {
	//동일한 URL이 아니면 다른 URL로 처리한다.
	if (ajax_prev_url != "" && ajax_prev_url != url) {
		ajax_first_num = 0;
	}
	ajax_prev_url = url;

	//console.log(params);

	$.ajax({
		type: "POST",
		url: url,
		data: params,
		beforeSend: function (xhr, opts) {
			//ajax_first_num = ajax_first_num + 1;
			//if(ajax_first_num!=1){
			//	commonAlert("데이터 처리중입니다.",function(){});
			//	xhr.abort();
			//	return false;
			//}
		},
		success: function (result) {
			//ajax_first_num = 0;
			//$(".layer-popup").remove();
			callback(result);
		},
		error: function (xhr, status, error) {
			//console.log(error);
			//ajax_first_num = 0;
			commonAlert('통신오류 : ' + error, function () { });
		}
	});
}

//레이어 안없앰
function comAjaxV2(url, params, callback) {
	$.ajax({
		type: "POST",
		url: url,
		data: params,
		beforeSend: function (xhr, opts) {
			ajax_first_num = ajax_first_num + 1;
			if (ajax_first_num != 1) {
				commonAlert("데이터 처리중입니다.", function () { });
				xhr.abort();
				return false;
			}
		},
		success: function (result) {
			ajax_first_num = 0;
			callback(result);
		},
		error: function (xhr, status, error) {
			ajax_first_num = 0;
			commonAlert('통신오류 : ' + error, function () { });
		}
	});
}

/**
 * 공용 ajax 처리
 * @param url : 호출URL
 * @param params : 파라미터
 * @param callback : 콜백
 */
function fileAjax(url, params, callback) {
	$.ajax({
		type: "POST",
		url: url,
		data: params,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function (xhr, opts) {
			ajax_first_num = ajax_first_num + 1;
			if (ajax_first_num != 1) {
				commonAlert("데이터 처리중입니다.", function () { });
				xhr.abort();
				return false;
			}
		},
		success: function (result) {
			ajax_first_num = 0;
			$(".layer-popup").remove();
			callback(result);
		},
		error: function (xhr, status, error) {
			ajax_first_num = 0;
			commonAlert('통신오류 : ' + error, function () { });
		}
	});
}

var ajax_first_num = 0;
function comAjaxLoading(url, params, callback) {
	$.ajax({
		type: "POST",
		url: url,
		data: params,
		beforeSend: function (xhr, opts) {
			ajax_first_num = ajax_first_num + 1;
			if (ajax_first_num != 1) {
				commonAlert("데이터 처리중입니다.", function () { });
				xhr.abort();
				return false;
			}
			showAjaxLoading();
		},
		success: function (result) {
			hideAjaxLoading();
			$(".layer-popup").remove();
			ajax_first_num = 0;
			callback(result);
		},
		error: function (xhr, status, error) {
			hideAjaxLoading();
			ajax_first_num = 0;
			commonAlert('통신오류 : ' + error, function () { });
		}
	});
}

/**
 * 공용 ajax 처리
 * @param url : 호출URL
 * @param params : 파라미터
 * @param callback : 콜백
 */
function fileAjaxLoading(url, params, callback) {
	$.ajax({
		type: "POST",
		url: url,
		data: params,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function (xhr, opts) {
			ajax_first_num = ajax_first_num + 1;
			if (ajax_first_num != 1) {
				commonAlert("데이터 처리중입니다.", function () { });
				xhr.abort();
				return false;
			}
			showAjaxLoading();
		},
		success: function (result) {
			hideAjaxLoading();
			$(".layer-popup").remove();
			ajax_first_num = 0;
			callback(result);
		},
		error: function (xhr, status, error) {
			hideAjaxLoading();
			ajax_first_num = 0;
			commonAlert('통신오류 : ' + error, function () { });
		}
	});
}

function getDateAdd(sDate, nNum, type) {
	var yy = parseInt(sDate.substr(0, 4), 10);
	var mm = parseInt(sDate.substr(5, 2), 10);
	var dd = parseInt(sDate.substr(8), 10);

	var dt;
	if (type == "d") {
		dt = new Date(yy, mm - 1, dd + nNum);
	} else if (type == "m") {
		dt = new Date(yy, mm - 1, dd + (nNum * 31));
	} else if (type == "y") {
		dt = new Date(yy + nNum, mm - 1, dd);
	}

	yy = dt.getFullYear();
	mm = dt.getMonth() + 1;
	mm = (mm < 10) ? '0' + mm : mm;
	dd = dt.getDate();
	dd = (dd < 10) ? '0' + dd : dd;

	return '' + yy + '-' + mm + '-' + dd;
}

function getToday(format) {
	var date = new Date();
	return getTempDate(date, format);
}

function getTempDate(date, format) {
	var year = date.getFullYear();
	var month = (1 + date.getMonth());
	var day = date.getDate();
	var hours = date.getHours();
	var minute = date.getMinutes();
	var second = date.getSeconds();

	month = month >= 10 ? month : '0' + month;
	day = day >= 10 ? day : '0' + day;

	if (format == "ymd") {
		return year + '' + month + '' + day;
	} else if (format == "y-m-d") {
		return year + '-' + month + '-' + day;
	}
}

function getFormatDate(date, format) {
	var year = date.getFullYear();
	var month = (1 + date.getMonth());
	var day = date.getDate();
	var hours = date.getHours();
	var minute = date.getMinutes();
	var second = date.getSeconds();

	month = month >= 10 ? month : '0' + month;
	day = day >= 10 ? day : '0' + day;

	if (format == "ymd") {
		return year + '' + month + '' + day;
	} else if (format == "y-m-d") {
		return year + '-' + month + '-' + day;
	}
}


function commonAlert(msg, callback) {
	openPopup('alert2', {
		content: msg,
		firstButton: '확인',
		firstCallback: function () {
			callback();
		},
	});
}

function commonLogin() {
	commonAlert("로그인후 이용해주세요!", function () {

	});
}

function toastShow(msg) {
	new ToasterBox({
		msg: msg,
		html: true,
		duration: 2000,
		className: null,
		closeButton: true,
		maxWidth: '100%',
		autoOpen: true,
		position: 'top-center',
		backgroundColor: null,
		closeIcon: null
	});
}

function removeElement(ele) {
	$(ele).fadeOut("normal", function () { $(this).remove(); });
}


//ajax 캐싱
$.CashingAjax = function (pm) {
	$(document).click(function () {
		sessionStorage.setItem(pm.cashing_id + "_list_data", $(pm.list_id).html());
		sessionStorage.setItem(pm.cashing_id + "_category_list", $(pm.category_list_id).html());
		sessionStorage.setItem(pm.cashing_id + "_scroll_top", $(window).scrollTop());
		sessionStorage.setItem(pm.cashing_id + "_page", $(pm.list_id).attr("data-page"));

		if (pm.list_params != null && pm.list_params != "") {
			if (pm.list_params.length > 0) {
				for (var icnt = 0; icnt < pm.list_params.length; icnt++) {
					sessionStorage.setItem(pm.cashing_id + pm.list_params[icnt], getParameter(pm.list_params[icnt]));
				}
			}
		}
		if (pm.more_id != "") {
			sessionStorage.setItem(pm.cashing_id + "more_list", $(pm.more_id).hasClass("hidden") ? "TRUE" : "FALSE");
		}

		if (pm.total_cnt_id != "") {
			sessionStorage.setItem(pm.cashing_id + "total_cnt", $(pm.total_cnt_id).text());
		}
	});

	//뒤로왔을때 상품 유지
	window.addEventListener('pageshow', function (event) {
		if (event.persisted || window.performance && window.performance.navigation.type == 2) {
			//데이터 가져오기
			var list_data = sessionStorage.getItem(pm.cashing_id + "_list_data");
			var scroll_top = sessionStorage.getItem(pm.cashing_id + "_scroll_top");
			var category_list = sessionStorage.getItem(pm.cashing_id + "_category_list");
			var page = sessionStorage.getItem(pm.cashing_id + "_page");

			//데이터 설정
			$(pm.list_id).html(list_data);
			$(pm.list_id).attr("data-page", page);
			//pm.scroll_obj.scrollTop(scroll_top);
			$(window).scrollTop(scroll_top);

			// 카테고리 캐싱 설정
			// @TODO: 선택한 카테고리 표기
			$(pm.category_list_id).html(category_list);
			if ($('.swiper-slide').length > 1) {
				new Swiper('.category-wrap.swiper-container', {
					slidesPerView: 'auto',
					spaceBetween: 0,
					speed: 200,
					pagination: {
						el: '.swiper-pagination'
					}
				});
			}

			//파라미터 설정
			if (pm.list_params != null && pm.list_params != "") {
				if (pm.list_params.length > 0) {
					for (var icnt = 0; icnt < pm.list_params.length; icnt++) {
						var param_data = sessionStorage.getItem(pm.cashing_id + pm.list_params[icnt]);
						setParameter(pm.list_params[icnt], param_data);

						//파라미터 옵션 예외처리
						if (pm.list_params[icnt] == "data-option") {
							$(".jsFilter").removeClass("on");
							for (var jcnt = 0; jcnt < $(".jsFilter").length; jcnt++) {
								if ($(".jsFilter").eq(jcnt).attr("data-option") == param_data) {
									$(".jsFilterOpen").html($(".jsFilter").eq(jcnt).html());
									$(".jsFilter").eq(jcnt).addClass("on");
								}
							}
						}
					}
				}
			}

			//수량 처리
			if (pm.total_cnt_id != "") {
				var total_cnt = sessionStorage.getItem(pm.cashing_id + "total_cnt");
				$(pm.total_cnt_id).text(total_cnt);
			}

			//더보기 처리
			if (pm.more_id != "") {
				var more_list = sessionStorage.getItem(pm.cashing_id + "more_list");
				if (more_list == "FALSE") {
					$(pm.more_id).removeClass("hidden")
				}
			}
			pm.fn_cashing();
		} else {
			pm.fn_load();
		}
	}, false);

	function getParameter(key) {
		return $(pm.list_id).attr(key);
	}

	function setParameter(key, val) {
		$(pm.list_id).attr(key, val);
	}
};

/* 쿠키추가 */
function setCookie(cookie_name, value, days) {
	var exdate = new Date();
	var cookie_value;

	exdate.setDate(exdate.getDate() + days);
	cookie_value = escape(value) + ((days == null) ? '' : '; expires =' + exdate.toUTCString());
	document.cookie = cookie_name + '=' + cookie_value;
}

/* 쿠키 가져오기 */
function getCookie(cookie_name) {
	return document.cookie.indexOf(cookie_name);
}

/* 쿠키체크 후 팝업 열기 */
function showNotice(selector, cookie_name) {
	if (getCookie(cookie_name) < 0) {
		openPanel(document.querySelector(selector));
	}
}
