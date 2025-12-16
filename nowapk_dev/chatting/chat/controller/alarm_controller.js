var container = {};

/////////////////////////////////////////////////////
// 모듈 로드
/////////////////////////////////////////////////////
const Promise = require('promise');
const async = require('async');
const await = require('await');

/////////////////////////////////////////////////////
// 커스텀 모듈 로드
/////////////////////////////////////////////////////
const $alarm = require("../model/alarm_model.js");
const $user = require("../model/user_model.js");
const $common = require("../common/common.js");

/////////////////////////////////////////////////////
// 변수 설정
/////////////////////////////////////////////////////

/////////////////////////////////////////////////////
// 기능 정의
/////////////////////////////////////////////////////

//알람 등록
container.insertAlarm = async function(data,callback){
	$alarm.insertAlarm(data,async function(){
		callback($common.jsonReturn("00","등록성공"));
		return;
	});
}

//미확인 알람 카운트
container.checkAlarmHours = async function(params,callback){
	var length = await $alarm.checkAlarmHours(params);
	callback($common.jsonReturn("00","조회 성공",length));
}


//알람 가져오기
container.getAlarmList = async function(params,callback){
	var room = await $alarm.getAlarmList(params);
	callback($common.jsonReturn("00","조회 성공",room));
}

//미확인 알람 카운트
container.getAlarmCount = async function(params,callback){
	var length = await $alarm.getAlarmCount(params);
	callback($common.jsonReturn("00","조회 성공",length));
}

//알람 등록
container.insertNotification = async function(data,callback){
	$alarm.insertNotification(data,async function(idx){
		callback(idx); // idx를 직접 반환하도록 수정
		return;
	});
}

//유저 수정
container.updateNotification = async function(idx,data,callback){
	$user.updateUserSocketId(idx,async function(ret){
		callback($common.jsonReturn("00","처리완료",""));
	});
}


/////////////////////////////////////////////////////
// 객체 적용
/////////////////////////////////////////////////////
module.exports = container;
