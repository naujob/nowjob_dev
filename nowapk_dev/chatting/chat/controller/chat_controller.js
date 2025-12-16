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
const $chat = require("../model/chat_model.js");
const $user = require("../model/user_model.js");
const $common = require("../common/common.js");

/////////////////////////////////////////////////////
// 변수 설정
/////////////////////////////////////////////////////

/////////////////////////////////////////////////////
// 기능 정의
/////////////////////////////////////////////////////

//유저 등록
container.insertRoom = async function(data,callback){

	//기존 방 조회
	/*
	var users = [];
	for(var icnt=0;icnt < data.user_list.length;icnt++){
		users.push({"rm_users.user_id" : data.user_list[icnt].user_id});
	}

	if(data.rm_type=="chat"){
		var usersRoom = await $chat.getUsersRoom(
			{
				"$and" : [
					{ "$and" : users},
					{"$where" : "this.rm_users.length==" + users.length},
					{ "rm_type" : data.rm_type},
				]
			}
		);

		if(usersRoom!=null){
			callback($common.jsonReturn("00","기존방 조회 성공",usersRoom.rm_idx));
			return;
		}
	}else if(data.rm_type=="trade"){
		var usersRoom = await $chat.getUsersRoom(
			{
				"$and" : [
					{ "$and" : users},
					{"$where" : "this.rm_users.length==" + users.length},
					{ "rm_type" : data.rm_type},
				]
			}
		);

		if(usersRoom!=null){
			callback($common.jsonReturn("00","기존방 조회 성공",usersRoom.rm_idx));
			return;
		}
	}else if(data.rm_type =="channel"){
		var usersRoom = await $chat.getUsersRoom(
			{
				"$and" : [
					{ "rm_type" : data.rm_type},
					{"rm_subject" : data.rm_subject}
				]
			}
		);

		if(usersRoom!=null){
			callback($common.jsonReturn("01","이미 존재하는 채널방이 있습니다.",usersRoom.rm_idx));
			return;
		}
	}
	*/

	var usersRoom = await $chat.getUsersRoom(
		{ "rm_idx" : data.rm_idx}
	);

	$common.writeLog("room check :::: rm_idx" + '::' + data.rm_idx + '::usersRoom:' + usersRoom);

	if(usersRoom!=null){
		callback($common.jsonReturn("00","이미 존재하는 채널방이 있습니다.",usersRoom.rm_idx));
		return;
	}


	$chat.insertRoom(data,async function(room_idx){
		callback($common.jsonReturn("00","등록성공",room_idx));
		return;
	});
}

//룸 정보 조회
container.getRoom = async function(data,callback){
	var room = await $chat.getRoom({rm_idx : data.rm_idx,con_user_id : data.con_user_id});
	callback($common.jsonReturn("00","조회 성공",room));
}

//룸 메시지 리스트 조회
container.getRoomMessages = async function(params,callback){
	var message_list = await $chat.getRoomMessages(params);
	callback($common.jsonReturn("00","조회 성공",message_list));
}

//마지막 메시지
container.getLastMessage = async function(rm_idx,callback){
	var message_list = await $chat.getLastMessage(rm_idx);
	callback($common.jsonReturn("00","조회 성공",message_list));
}


//메시지 등록
container.insertMessage = async function(data,callback){
	$chat.insertMessage(data,async function(ret){
		callback($common.jsonReturn("00","등록성공",ret));
		return;
	});
}

//메시지 상태 수정
container.updateMessageStatus = async function(data,callback){
	$chat.updateMessageStatus(data,async function(ret){
		callback($common.jsonReturn("00","처리성공",ret));
		return;
	});
}

//메시지 읽음처리
container.updateMessageReturnCount = async function(data,callback){
	$chat.updateMessageReturnCount(data,async function(ret){
		callback($common.jsonReturn("00","처리성공",ret));
		return;
	});
}

//메시지 읽음처리 회원아이디 전체
container.updateMessageUserCount = async function(data,callback){
	$chat.updateMessageUserCount(data,async function(ret){
		callback($common.jsonReturn("00","처리성공",ret));
		return;
	});
}


//미수신 메시지 카운트
container.getNotReadCount = async function(data,callback){
	$chat.getNotReadCount(data,async function(ret){
		callback($common.jsonReturn("00","처리성공",ret));
		return;
	});
}

//미수신 유저별 메시지 카운트
container.getUsewrNotReadCount = async function(data,callback){
	$chat.getUsewrNotReadCount(data,async function(ret){
		callback($common.jsonReturn("00","처리성공",ret));
		return;
	});
}


//메시지 카운트 업데이트
container.updateEnterRoom = async function(data,callback){
	$chat.updateEnterRoom(data,async function(ret){
		callback($common.jsonReturn("00","처리성공",ret));
		return;
	});
}

//룸 리스트 조회
container.getRoomList = async function(data,callback){
	var room_list = await $chat.getRoomList(data);
	callback(room_list);
}

// 채팅방 전체 사용자 DB에서 조회 (MongoDB 또는 MySQL)
container.getRoomUsersFromDB = async function(params, callback) {
	try {
		$common.writeLog('=== getRoomUsersFromDB 시작 ===');
		$common.writeLog('요청 파라미터: ' + JSON.stringify(params));
		
		// MongoDB에서 채팅방 정보 조회
		const $db = require("../database/database.js");
		
		$common.writeLog('MongoDB 연결 상태: ' + ($db.isConnected() ? '연결됨' : '연결 안됨'));
		
		if (!$db.isConnected()) {
			$common.writeLog('❌ MongoDB 연결되지 않음 - getRoomUsersFromDB 실패');
			callback([]);
			return;
		}
		
		$common.writeLog('MongoDB에서 채팅방 조회 시도 - rm_idx: ' + params.rm_idx);
		
		// MongoDB에서 채팅방 조회
		const room = await $db.cs_room.findOne({ rm_idx: parseInt(params.rm_idx) });
		
		$common.writeLog('MongoDB 조회 결과: ' + (room ? '채팅방 발견' : '채팅방 없음'));
		
		if (!room) {
			$common.writeLog('❌ MongoDB에서 채팅방을 찾을 수 없음 - rm_idx: ' + params.rm_idx);
			callback([]);
			return;
		}
		
		$common.writeLog('채팅방 정보: ' + JSON.stringify({
			rm_idx: room.rm_idx,
			rm_subject: room.rm_subject,
			rm_users_count: room.rm_users ? room.rm_users.length : 0
		}));
		
		if (!room.rm_users || !Array.isArray(room.rm_users)) {
			$common.writeLog('❌ 채팅방에 사용자 정보가 없음 - rm_idx: ' + params.rm_idx);
			callback([]);
			return;
		}
		
		$common.writeLog('✅ MongoDB에서 채팅방 사용자 조회 성공 - count: ' + room.rm_users.length);
		$common.writeLog('사용자 목록: ' + JSON.stringify(room.rm_users.map(u => ({ user_id: u.user_id, user_nick: u.user_nick }))));
		
		callback(room.rm_users);
		
	} catch (err) {
		$common.writeLog('❌ getRoomUsersFromDB 오류: ' + err.message);
		$common.writeLog('오류 스택: ' + err.stack);
		callback([]);
	}
}

/////////////////////////////////////////////////////
// 객체 적용
/////////////////////////////////////////////////////
module.exports = container;
