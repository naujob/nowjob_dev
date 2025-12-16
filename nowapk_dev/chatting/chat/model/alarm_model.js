var container = {};

/////////////////////////////////////////////////////
// 커스텀 모듈 로드
/////////////////////////////////////////////////////
const Promise = require('promise');
const async = require('async');
const await = require('await');

/////////////////////////////////////////////////////
// 모듈 로드
/////////////////////////////////////////////////////
const $db = require("../database/database.js");
const $common = require("../common/common.js");

/////////////////////////////////////////////////////
// 변수 설정
/////////////////////////////////////////////////////

/////////////////////////////////////////////////////
// 기능 정의
/////////////////////////////////////////////////////

//알람 등록
container.insertAlarm = async function(data,callback){
	//기본키 가져오기
	var al_idx = await $db.getNextSequence('alarm');

	//정보 만들기
	var alarm_info = {
		al_idx : al_idx,
		user_id : data.user_id,
		al_type : data.al_type,
		al_key : data.al_key,
		al_text : data.al_text,
		al_url : data.al_url,
		show_yn : "N",
	    reg_dt : $common.getFormatDate(new Date(),'full'),
	};

	//디비 등록
	var ret = await $db.cs_alarm.insertOne(alarm_info);
	callback(al_idx);
}

//알람 등록
container.checkAlarmHours = async function(data,callback){
	var chk_dt = $common.getPrevHourDate();
	var ret = await $db.cs_alarm.find({ "user_id" : data.user_id,"reg_dt" : {"$gte": chk_dt} }).toArray();
    return ret.length;
}

//푸시 발송 등록
container.insertNotification = async function(data,callback){
	//기본키 가져오기
	var no_idx = await $db.getNextSequence('notification');

	var alarm_info = {
		no_idx : no_idx,
		user_id : data.user_id,
		no_subject : data.no_subject,
		no_text : data.no_text,
		no_url : data.no_url,
		no_token : data.no_token,
		send_yn : "N",
	    reg_dt : $common.getCurrentDate(),
	};

	//디비 등록
	var ret = await $db.cs_notification.insertOne(alarm_info);
	callback(no_idx);
}

//회원정보 업데이트
container.updateNotification = async function(idx,data,callback){
	var ret = await $db.cs_user.updateOne({no_idx : idx},{$set : data});
	callback(ret);
}


//알람 리스트
container.getAlarmList = async function(params){
	var ret = await $db.cs_alarm.findOne(params).toArray();
    return ret;
}

//알람 카운트
container.getAlarmCount = async function(params){
	var ret = await $db.cs_alarm.find(params).toArray();
    return ret.length;
}

/////////////////////////////////////////////////////
// 객체 적용
/////////////////////////////////////////////////////
module.exports = container;
