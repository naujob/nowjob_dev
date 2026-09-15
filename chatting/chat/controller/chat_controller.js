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

	$common.writeLog('chat_controller:: insertRoom 호출 전 - data.user_list 존재:' + (data.user_list ? 'Y' : 'N') + ', data.user_list 개수:' + (data.user_list ? data.user_list.length : 0) + ', data.rm_users 존재:' + (data.rm_users ? 'Y' : 'N'));

	$chat.insertRoom(data,async function(room_idx){
		$common.writeLog('chat_controller:: insertRoom 완료 - room_idx:' + room_idx);
		callback($common.jsonReturn("00","등록성공",room_idx));
		return;
	});
}

//룸 정보 조회
container.getRoom = async function(data,callback){
	var room = await $chat.getRoom({rm_idx : data.rm_idx,con_user_id : data.con_user_id});
	callback($common.jsonReturn("00","조회 성공",room));
}

// MySQL에서 rm_idx로 rc_idx, ru_idx 조회 후 MongoDB에서 기존 방 찾기
container.findExistingRoomByMySQL = async function(data, callback){
	try {
		const $mysql = require("../database/mysql.js");
		const $db = require("../database/database.js");
		$common.writeLog("MySQL(st_room) findExistingRoomByMySQL 시작 rm_idx=" + data.rm_idx + " rc_idx=" + (data.rc_idx || "") + " ru_idx=" + (data.ru_idx || ""));

		// MySQL에서 rm_idx로 rc_idx, ru_idx 조회
		return new Promise((resolve, reject) => {
			const sql = "SELECT rm_idx, rc_idx, ru_idx FROM st_room WHERE rm_idx = ?";
			$mysql.db_conn.query(sql, [data.rm_idx], async function(err, results) {
				if (err) {
					$common.writeLog("MySQL(st_room) findExistingRoomByMySQL 오류: " + err.message);
					callback(null);
					resolve(null);
					return;
				}

				if (!results || results.length === 0) {
					// MySQL에 방이 없을 때: 클라이언트가 전달한 rm_idx가 실제로는 rc_idx일 가능성 고려
					// 1. data에 rc_idx, ru_idx가 있으면 사용
					if (data.rc_idx && data.ru_idx) {
						const rc_idx = data.rc_idx;
						const ru_idx = data.ru_idx;

						// MongoDB에서 기존 방 찾기
						const existing_room = await $db.cs_room.findOne({ 
							rc_idx: rc_idx, 
							ru_idx: ru_idx,
							rm_type: "recruit"
						});
						$common.writeLog("MongoDB(cs_room) findExistingRoomByMySQL rc_idx/ru_idx 조회 rc_idx=" + rc_idx + " ru_idx=" + ru_idx + " found=" + (existing_room ? "Y" : "N"));

						if (existing_room) {
							callback({
								existing_rm_idx: existing_room.rm_idx,
								rc_idx: rc_idx,
								ru_idx: ru_idx
							});
							resolve({
								existing_rm_idx: existing_room.rm_idx,
								rc_idx: rc_idx,
								ru_idx: ru_idx
							});
							return;
						}

						// MongoDB에서 찾지 못했지만 data에서 rc_idx, ru_idx를 가져옴
						callback({
							existing_rm_idx: null,
							rc_idx: rc_idx,
							ru_idx: ru_idx
						});
						resolve({
							existing_rm_idx: null,
							rc_idx: rc_idx,
							ru_idx: ru_idx
						});
						return;
					}

					// 2. data에 rc_idx, ru_idx가 없으면, 전달받은 rm_idx를 rc_idx로 간주하고 MySQL에서 ru_idx 찾기
					// 클라이언트가 rc_idx를 rm_idx로 잘못 전달한 경우 대비
					const sql2 = "SELECT DISTINCT rc_idx, ru_idx FROM st_room WHERE rc_idx = ? LIMIT 1";
					$mysql.db_conn.query(sql2, [data.rm_idx], async function(err2, results2) {
						if (!err2 && results2 && results2.length > 0) {
							const mysql_room2 = results2[0];
							const rc_idx = mysql_room2.rc_idx;
							const ru_idx = mysql_room2.ru_idx;

							// MongoDB에서 기존 방 찾기
							const existing_room = await $db.cs_room.findOne({ 
								rc_idx: rc_idx, 
								ru_idx: ru_idx,
								rm_type: "recruit"
							});
							$common.writeLog("MongoDB(cs_room) findExistingRoomByMySQL(rc_idx fallback) rc_idx=" + rc_idx + " ru_idx=" + ru_idx + " found=" + (existing_room ? "Y" : "N"));

							if (existing_room) {
								callback({
									existing_rm_idx: existing_room.rm_idx,
									rc_idx: rc_idx,
									ru_idx: ru_idx
								});
								resolve({
									existing_rm_idx: existing_room.rm_idx,
									rc_idx: rc_idx,
									ru_idx: ru_idx
								});
								return;
							}

							// MongoDB에서 찾지 못했지만 MySQL에서 rc_idx, ru_idx를 찾음
							callback({
								existing_rm_idx: null,
								rc_idx: rc_idx,
								ru_idx: ru_idx
							});
							resolve({
								existing_rm_idx: null,
								rc_idx: rc_idx,
								ru_idx: ru_idx
							});
							return;
						}

						// rc_idx로도 찾지 못했으면 null 반환
						$common.writeLog("MySQL(st_room) findExistingRoomByMySQL rc_idx fallback 결과 없음 rm_idx=" + data.rm_idx);
						callback(null);
						resolve(null);
						return;
					});
					return;
				}

				const mysql_room = results[0];
				const rc_idx = mysql_room.rc_idx;
				const ru_idx = mysql_room.ru_idx;

				$common.writeLog("MySQL(st_room) findExistingRoomByMySQL 결과 rm_idx=" + data.rm_idx + " rc_idx=" + rc_idx + " ru_idx=" + ru_idx);
				// rc_idx와 ru_idx가 있으면 MongoDB에서 기존 방 찾기
				if (rc_idx && ru_idx) {
					// MongoDB에서 rc_idx와 ru_idx로 기존 방 찾기
					const existing_room = await $db.cs_room.findOne({ 
						rc_idx: rc_idx, 
						ru_idx: ru_idx,
						rm_type: "recruit"
					});
					$common.writeLog("MongoDB(cs_room) findExistingRoomByMySQL 조회 rc_idx=" + rc_idx + " ru_idx=" + ru_idx + " found=" + (existing_room ? "Y" : "N"));

					if (existing_room) {
						callback({
							existing_rm_idx: existing_room.rm_idx,
							rc_idx: rc_idx,
							ru_idx: ru_idx
						});
						resolve({
							existing_rm_idx: existing_room.rm_idx,
							rc_idx: rc_idx,
							ru_idx: ru_idx
						});
						return;
					}
				}

				// MongoDB에서 찾지 못했지만 MySQL 정보는 있음 (새 방 생성 시 rc_idx, ru_idx 사용)
				callback({
					existing_rm_idx: null,
					rc_idx: rc_idx,
					ru_idx: ru_idx
				});
				resolve({
					existing_rm_idx: null,
					rc_idx: rc_idx,
					ru_idx: ru_idx
				});
			});
		});
	} catch (error) {
		callback(null);
	}
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

//메시지 삭제 (미읽음 상태일 때만 허용)
container.deleteMessage = async function(data,callback){
	$chat.deleteMessage(data,async function(ret){
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
