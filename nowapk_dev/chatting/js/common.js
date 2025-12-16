const base_url = "https://nowjob.co.kr:3030";
const weekday = ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'];

// Firebase 설정
const firebaseConfig = {
    apiKey: "AIzaSyBXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", // 실제 API 키로 교체 필요
    authDomain: "nowjob-558df.firebaseapp.com",
    projectId: "nowjob-558df",
    storageBucket: "nowjob-558df.appspot.com",
    messagingSenderId: "123456789012", // 실제 Sender ID로 교체 필요
    appId: "1:123456789012:web:abcdefghijklmnop" // 실제 App ID로 교체 필요
};

// Firebase 초기화
if (typeof firebase !== 'undefined') {
    firebase.initializeApp(firebaseConfig);
}

// socket 객체는 전역에서 설정되므로 여기서는 참조만
let socket;

// socket 객체가 설정될 때까지 대기
waitForSocket();
registerFCMToken(); // FCM 토큰 등록 추가

function waitForSocket() {
	if (window.socket) {
		socket = window.socket;
		updateSocket();
	} else {
		// socket이 아직 설정되지 않았으면 잠시 후 다시 시도
		setTimeout(waitForSocket, 100);
	}
}

function updateSocket(){
	//접속 성공시 상태 처리한다.
	if($("#os_type").val()!="android"){
		socket.on("connection_return", function(ret) {
			var user_id = getUserId();
			var location = getLocation();
			if(user_id!=""){
				socket.emit("update_status", {user_id : user_id,last_location : location});
				return true;
			}else{
				socket.emit("buyguest_status", {});
				return true;
			}
		});	
	}else{
		var user_id = getUserId();
		var location = getLocation();
		if(user_id!=""){
			APP_NOWAPK.update_status(user_id,location);
			return true;
		}else{
			APP_NOWAPK.buyguest_status();
			return true;
		}
	}
}

// FCM 토큰 등록 함수 (안드로이드 앱에서만 실행)
function registerFCMToken(){
	// 안드로이드 앱에서만 FCM 토큰 등록
	if($("#os_type").val() == "android" && typeof APP_NOWAPK !== 'undefined'){
		// 안드로이드 앱의 FCM 토큰 등록 함수 호출
		if(typeof APP_NOWAPK.register_fcm_token === 'function'){
			APP_NOWAPK.register_fcm_token();
		}
	} else {
		console.log('PC 환경이므로 FCM 토큰 등록을 건너뜁니다.');
	}
}

function getLocation(){
	var href = window.location.href;

	if(href.indexOf("/chat/chat_list") > -1 || href.indexOf("/chat/trade_list") > -1){
		return "chat_list";
	}else if(href.indexOf("/chat/chat_view") > -1 || href.indexOf("/chat/trade_view") > -1){
		return "chat_room";
	}else{
		return "site";
	}
}

function getUserId(){
	if($("#is_login").val()=="Y"){
		return $("#user_id").val();
	}else{
		return "";
	}
}

//ajax 호출 처리
function nodeAjax(url,params,callback) {
	//var jsonData = JSON.stringify(params);
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: params,
        success: function (result) {
            callback(result);
        },
        error: function (xhr, status, error) {
            alert('통신오류 : ' + error);
        }
    });
}

/**
 * 공용 ajax 처리
 * @param url : 호출URL
 * @param params : 파라미터
 * @param callback : 콜백
 */
function fileAjax(url,params,callback) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: params,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
            callback(result);
        },
        error: function (xhr, status, error) {
            alert('통신오류 : ' + error);
        }
    });
}

function getCurrentDate(){
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth();
    var today = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    var milliseconds = date.getMilliseconds();
    return new Date(year, month, today, hours, minutes, seconds, milliseconds);
}

function getCurrentDt(){
    var date = new Date();
    var year = date.getFullYear();
    var month = (1 + date.getMonth());
    var day = date.getDate();
    var hours = date.getHours();
    var minute = date.getMinutes();
    var second = date.getSeconds();
    var milliseconds = date.getMilliseconds();

    month = month >= 10 ? month : '0' + month;
    day = day >= 10 ? day : '0' + day;
	hours = hours >= 10 ? hours : '0' + hours;
	minute = minute >= 10 ? minute : '0' + minute;
	second = second >= 10 ? second : '0' + second;

    return {year : year,month:month,day : day,hours : hours,minute : minute,second : second};
}

function getCurrentAddDt(plus,day){
    var date = new Date();

    if(plus=="add"){
    	date.setDate(date.getDate() + day);
    }else{
    	date.setDate(date.getDate() - day);
    }

    var year = date.getFullYear();
    var month = (1 + date.getMonth());
    var day = date.getDate();
    var hours = date.getHours();
    var minute = date.getMinutes();
    var second = date.getSeconds();
    var milliseconds = date.getMilliseconds();

    month = month >= 10 ? month : '0' + month;
    day = day >= 10 ? day : '0' + day;
	hours = hours >= 10 ? hours : '0' + hours;
	minute = minute >= 10 ? minute : '0' + minute;
	second = second >= 10 ? second : '0' + second;

    return {year : year,month:month,day : day,hours : hours,minute : minute,second : second};
}


function getFormatDate(date,format){
	if(date==undefined){
		return "";
	}

    var year = date.getFullYear();
    var month = (1 + date.getMonth());
    var day = date.getDate();
	var hours = date.getHours();
	var minute = date.getMinutes();
	var second = date.getSeconds();

    month = month >= 10 ? month : '0' + month;
    day = day >= 10 ? day : '0' + day;
	hours = hours >= 10 ? hours : '0' + hours;
	minute = minute >= 10 ? minute : '0' + minute;
	second = second >= 10 ? second : '0' + second;

    if(format=="ymd"){
        return  year + '' + month + '' + day;
    }else if(format=="kor"){
    	var week = weekday[date.getDay()];
        return  year + '년 ' + month + '월 ' + day + '일 ' + '(' + week + ')';
    }else if(format=="hh:mm"){
        return  hours + ':' + minute;
    }else if(format=="chat"){
    	var now = getCurrentDt();
    	var yester = getCurrentAddDt("minus",1);
    	//오늘인지 여부 확인
    	if(now.year == year && now.month==month && now.day == day){
    		return  hours + ':' + minute;
    	}else if(yester.year == year && yester.month==month && yester.day == day){
    		return  "어제";
    	}else{
    		return  year + '- ' + month + '- ' + day;
    	}
    }
}

function replaceAll(str, searchStr, replaceStr) {
   return str.split(searchStr).join(replaceStr);
}

function preview_image(event,target_id)
{
	var reader = new FileReader();
	reader.onload = function()
	{
		var output = document.getElementById(target_id);
		output.src = reader.result;
	}
	reader.readAsDataURL(event.target.files[0]);
}
