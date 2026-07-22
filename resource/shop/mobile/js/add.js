$(document).ready(function () {
    $(".bottom-nav .menu").after().click(function () {
        $("#menu").removeClass("open");
    });

    // btn-back 클릭 핸들러
    $(".btn-back").click(function () {
        const currentPath = window.location.pathname;
        const $button = $(this);
        const targetHref = $button.attr('href');

        // 이력서 작성 페이지에서는 각 페이지의 핸들러가 처리
        if (currentPath.indexOf('/mypage/resume_write1') > -1 ||
            currentPath.indexOf('/mypage/resume_write4') > -1) {
            return false;
        }

        // href 속성이 있으면 해당 페이지로 이동, 없으면 히스토리 백
        if (targetHref && targetHref !== '#') {
            location.href = targetHref;
        } else {
            history.back();
        }
        return false;
    });

    $(document).on("click", ".btn-replace", function () {
        var url = $(this).attr("data-url");
        _replace(url);
    });

    $(document).on("click", ".go-replace", function () {
        var url = $(this).attr("data-url");
        _replace(url);
    });

    $(".btn-home").click(function () {
        location.href = "/";
    });

    $("a,button").click(function () {
        var scrollPosition = $("#app > .page").scrollTop();
        localStorage.setItem("divScrollPositon", scrollPosition);
    });

    $(document).on("click", ".click-item", function () {
        var link = $(this).attr("data-link");
        location.href = link;
    });

    window.addEventListener('pageshow', function (event) {
        if (event.persisted || window.performance && window.performance.navigation.type == 2) {
            $("#app > .page").scrollTop(localStorage.getItem("divScrollPositon"));
        }
    });

    //북마크 처리
    $(document).on("click", ".btnBookMark", function () {
        var params = {};
        params["bk_key"] = $(this).attr("data-bk_key");
        params["bk_type"] = $(this).attr("data-bk_type");
        var target_user_id = $(this).attr("data-user_id");

        var $this = $(this);
        comAjax("/common_proc/bookmark", params, function (ret) {
            if (ret.code != "00") {
                $this.removeClass("on");
                commonAlert(ret.msg, function () { });
                return;
            } else {
                if (ret.data.yn == "Y") {
                    $this.addClass("on");
                } else {
                    $this.removeClass("on");
                }
            }
        });
    });

    $(".btn-toggle-menu").click(function () {
        callVibrate(400);
    });
});

function _replace(url) {
    if (history.replaceState) {
        // history의 replaceState 기능이 사용 가능한 경우
        history.replaceState(null, document.title, url);
        history.go(0);
    } else {
        // history의 replaceState 기능이 사용 가능하지 않은 경우
        location.replace(url);
    }
}

function callVibrate(time) {
    // 네비게이션 사용해 진동 메소드 선언
    navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;

    if (navigator.vibrate) {
        navigator.vibrate(time); // 진동 수행 1000 = 1초
        // navigator.vibrate(0); // 진동 정지
    }
}

// iOS 시스템 스와이프 뒤로가기 처리 (네이티브에서 handleSwipeBack 호출함)
// history.back()은 브라우저 기록 그대로 이동 → WebView 환경에서 예상과 다르게 동작 → 그래서 직접 어느 페이지로 갈지 정의
function customBack() {
    try {
        const currentPath = window.location.pathname;
       // URL: https://example.com/chat/chat_view?from=applied
       // currentPath = /chat/chat_view
        // chat_view 페이지는 자체 뒤로가기 로직 사용 (from 파라미터 기반)
        if (currentPath.indexOf('/chat/chat_view') > -1 || currentPath.indexOf('/chat/trade_view') > -1) {
            const params = new URLSearchParams(window.location.search); // params = { from: 'applied' }
            const from = params.get('from') || '';

            const backMap = {
                applied: '/chat/chat_list',
                proposed_job: '/chat/chat_list2',
                talent_received: '/chat/index_comp',
                talent_sent: '/chat/chat_list3'
            };

            const backUrl = backMap[from] || '/chat/chat_list';

            const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || []; 
            if (historyStack.length > 0 && historyStack[historyStack.length - 1].indexOf('/chat/chat_view') > -1) {
                historyStack.pop(); //  historyStack에서 현재 chat_view 기록 제거 historyStack 기존: ["/home", "/chat/chat_list", "/chat/chat_view"] pop() 후:["/home", "/chat/chat_list"]
                sessionStorage.setItem('historyStack', JSON.stringify(historyStack));
            }

            window.location.href = backUrl;
            return;
        }

        // chat 페이지들은 recruit/job_list로 이동
        if (currentPath.indexOf('/chat/index_comp') > -1 ||
            currentPath.indexOf('/chat/chat_list') > -1 ||
            currentPath.indexOf('/chat/chat_list2') > -1 ||
            currentPath.indexOf('/chat/chat_list3') > -1) {

            window.location.href = '/recruit/job_list';
            return;
        }

        // 채용공고 작성 페이지들은 compage/recruit_list로 이동
        if (currentPath.indexOf('/compage/recruit_write1_1') > -1 ||
            currentPath.indexOf('/compage/recruit_write2_1') > -1 ||
            currentPath.indexOf('/compage/recruit_write3_1') > -1) {

            const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || [];
            if (historyStack.length > 0) {
                const lastPage = historyStack[historyStack.length - 1];
                if (lastPage.indexOf('/compage/recruit_write1_1') > -1 ||
                    lastPage.indexOf('/compage/recruit_write2_1') > -1 ||
                    lastPage.indexOf('/compage/recruit_write3_1') > -1) {
                    historyStack.pop();
                    sessionStorage.setItem('historyStack', JSON.stringify(historyStack));
                }
            }

            window.location.href = '/compage/recruit_list';
            return;
        }

        // 이력서 작성 페이지들은 mypage/resume_list로 이동
        if (currentPath.indexOf('/mypage/resume_write1') > -1) {
            const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || [];
            if (historyStack.length > 0) {
                const lastPage = historyStack[historyStack.length - 1];
                if (lastPage.indexOf('/mypage/resume_write1') > -1) {
                    historyStack.pop();
                    sessionStorage.setItem('historyStack', JSON.stringify(historyStack));
                }
            }

            window.location.href = '/mypage/resume_list';
            return;
        }

        // compage 포인트/북마크/쿠폰 페이지들은 compage/index로 이동
        if (currentPath.indexOf('/compage/point_promotion_buy') > -1 ||
            currentPath.indexOf('/compage/point_charge_step1') > -1 ||
            currentPath.indexOf('/compage/point_charge_list') > -1 ||
            currentPath.indexOf('/compage/point_use_list') > -1 ||
            currentPath.indexOf('/compage/point_promotion_list') > -1 ||
            currentPath.indexOf('/compage/bookmark_person1') > -1 ||
            currentPath.indexOf('/compage/bookmark_person2') > -1 ||
            currentPath.indexOf('/compage/coupon_list') > -1) {

            window.location.href = '/compage/index';
            return;
        }

        // mypage 포인트/북마크/쿠폰 페이지들은 mypage/index로 이동
        if (currentPath.indexOf('/mypage/point_charge_step1') > -1 ||
            currentPath.indexOf('/mypage/point_charge_list') > -1 ||
            currentPath.indexOf('/mypage/point_use_list') > -1 ||
            currentPath.indexOf('/mypage/point_promotion_buy') > -1 ||
            currentPath.indexOf('/mypage/point_promotion_list') > -1 ||
            currentPath.indexOf('/mypage/bookmark_recruit1') > -1 ||
            currentPath.indexOf('/mypage/bookmark_recruit2') > -1 ||
            currentPath.indexOf('/mypage/coupon_list') > -1) {

            window.location.href = '/mypage/index';
            return;
        }

        const historyStack = JSON.parse(sessionStorage.getItem('historyStack')) || [];
        const normalizedCurrentPath = normalizePath(currentPath);

        while (historyStack.length > 0) {
            const lastPagePath = normalizePath(historyStack[historyStack.length - 1]);
            if (lastPagePath === normalizedCurrentPath) {
                historyStack.pop();
            } else {
                break;
            }
        }

        if (historyStack.length > 0) {
            let previousPage = historyStack[historyStack.length - 1];
            let normalizedPreviousPath = normalizePath(previousPage);

            while (historyStack.length > 0 && normalizedPreviousPath === '/home/splash') {
                historyStack.pop();
                if (historyStack.length > 0) {
                    previousPage = historyStack[historyStack.length - 1];
                    normalizedPreviousPath = normalizePath(previousPage);
                }
            }

            if (historyStack.length === 0) {
                previousPage = '/home/index';
            }

            sessionStorage.setItem('historyStack', JSON.stringify(historyStack));
            window.location.href = previousPage;
        } else {
            window.location.href = '/home/index';
        }

    } catch (e) {
        console.error('[customBack] 오류:', e);
    }
}

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
    $("body").append("<div id='ajax_loding'><img src='/resource/shop/mobile/img/common/loading.svg' /></div>")
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
function comAjax(url, params, callback) {
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
            $(".layer-popup").remove();
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


//사용자가 목록 페이지에서 상세 페이지로 갔다가 뒤로가기로 돌아왔을 때, 보던 목록 화면을 그대로 복원해주는 AJAX 캐싱 유틸리티
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

/* 모바일 유형 */
function checkMobile() {
    var varUA = navigator.userAgent.toLowerCase(); //userAgent 값 얻기
    if (varUA.indexOf('android') > -1) {
        //안드로이드
        return "android";
    } else if (varUA.indexOf("iphone") > -1 || varUA.indexOf("ipad") > -1 || varUA.indexOf("ipod") > -1) {
        //IOS
        return "ios";
    } else {
        //아이폰, 안드로이드 외
        return "other";
    }
}

/**
 * Android/iOS WebView 뒤로가기 버튼 커스텀 핸들러 (네이티브 → JS 호출)
 * 
 * 사용법:
 * - Android onBackPressed()에서: mWebView.evaluateJavascript("handleAndroidBack()", null);
 * - iOS WKNavigationDelegate에서: webView.evaluateJavaScript("handleAndroidBack();", completionHandler: nil)
 * 
 * 동작:
 * - 홈 화면(/)에서는 JS에서 절대 뒤로가기 실행하지 않음 (네이티브에서도 차단)
 * - 커스텀 규칙에 등록된 페이지는 지정한 URL로 이동
 * - 그 외 페이지는 일반 history.back() 처리
 */
function handleAndroidBack() {
    try {
        // 현재 경로만 추출 (쿼리 파라미터 제외)
        const current = location.pathname;

        // 홈 화면(/)에서는 JS에서 절대 뒤로가기 실행하지 않음
        if (current === "/") {
            return;
        }

        // 뒤로가기 커스텀 규칙 맵
        const backCustomMap = {
            // /compage/index → 홈화면
            "/compage/index": "/",
            // compage 포인트/북마크/쿠폰 → compage 메인
            "/compage/point_promotion_buy": "/compage/index",
            "/compage/point_charge_step1": "/compage/index",
            "/compage/point_charge_list": "/compage/index",
            "/compage/point_use_list": "/compage/index",
            "/compage/point_promotion_list": "/compage/index",
            "/compage/bookmark_person1": "/compage/index",
            "/compage/bookmark_person2": "/compage/index",
            "/compage/coupon_list": "/compage/index",
            // mypage 포인트/북마크/쿠폰 → mypage 메인
            "/mypage/point_charge_step1": "/mypage/index",
            "/mypage/point_charge_list": "/mypage/index",
            "/mypage/point_use_list": "/mypage/index",
            "/mypage/point_promotion_buy": "/mypage/index",
            "/mypage/point_promotion_list": "/mypage/index",
            "/mypage/bookmark_recruit1": "/mypage/index",
            "/mypage/bookmark_recruit2": "/mypage/index",
            "/mypage/coupon_list": "/mypage/index",
            // 채팅 페이지 → 구인 목록
            "/chat/index_comp": "/recruit/job_list",
            "/chat/chat_list": "/recruit/job_list",
            "/chat/chat_list2": "/recruit/job_list",
            "/chat/chat_list3": "/recruit/job_list"
        };

        // 현재 페이지가 커스텀 목록에 포함되어 있나요?
        for (const key in backCustomMap) {
            // startsWith를 사용하여 경로 패턴 매칭
            if (current.startsWith(key)) {
                // 커스텀 대상 페이지 → 지정한 URL로 이동 (히스토리에 쌓이지 않도록 replace 사용)
                location.replace(backCustomMap[key]);
                return;
            }
        }

        // 커스텀 페이지가 아니면 일반 history.back() 처리
        if (window.history.length > 1) {
            history.back();
        } else {
            // 히스토리가 없으면 홈으로 이동 (replace 사용)
            location.replace("/");
        }

    } catch (error) {
        // 오류 발생 시 기본 동작 (뒤로가기)
        if (window.history.length > 1) {
            history.back();
        } else {
            // 히스토리가 없으면 홈으로 이동 (replace 사용)
            location.replace("/");
        }
    }
}
