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

//유저 등록
container.insertUser = async function(data,callback){
	//기본키 가져오기
	var user_idx = await $db.getNextSequence('user');
	var passwd = await $common.passwordHash(data.user_pwd);

	//디비 등록
	var ret = await $db.cs_user.insertOne({
		           user_idx : user_idx
		         , user_id : data.user_id
		         , user_pwd : passwd
		         , user_nick : data.user_nick
		         , user_img : data.user_img
		         , user_desc : data.user_desc
		         , user_status : "Y"
				 , socket_id : data.socket_id
				 , last_location : data.last_location
	 },function(error,response){
		if(error) {
			callback(false);
		}else{
			callback(true);
		}
	 });
}


//회원정보 업데이트
container.updateUserSocketId = async function(params,callback){
	var ret = await $db.cs_user.updateOne({user_id : params.user_id},{$set : {socket_id : params.socket_id,last_location : params.last_location,user_status : "Y"}});
	callback(ret);
}

//FCM 토큰 업데이트
container.updateUserToken = async function(params,callback){
	// MongoDB user 컬렉션에 토큰 저장
	var ret = await $db.cs_user.updateOne({user_id : params.user_id},{$set : {token : params.token}});
	callback(ret);
}

//회원정보 업데이트
container.updateUser = async function(params,callback){
	var ret = await $db.cs_user.updateOne({user_id : params.user_id},{$set : {
		user_nick : params.user_nick
		,user_id : params.user_id
		,user_pwd : params.user_pwd
		,user_img : params.user_img
	}});
	callback(ret);
}


//회원정보 업데이트
container.updateUserStatus = async function(params,callback){
	$common.chkLog("user status ::: socket_id : " + params.socket_id + ":::" + "user_status : " + params.user_status);
	var ret = await $db.cs_user.updateOne({socket_id : params.socket_id},{$set : {user_status : params.user_status}});
	callback(ret);
}


//유저 조회
container.getUser = async function(params){
	var ret = await $db.cs_user.findOne(params);
    return ret;
}

//유저 리스트 조회
container.getUserList = async function(params){
	var ret = await $db.cs_user.find(params).toArray();
    return ret;
}

//MySQL에서 FCM 토큰 조회
container.getUserTokenFromMySQL = async function(user_id, callback){
	try {
		// MySQL 연결 및 쿼리 실행
		const mysql = require('mysql2/promise');
		const connection = await mysql.createConnection({
			host: 'localhost',
			user: 'root',
			password: 'password',
			database: 'nowapk'
		});
		
		// 사용자 ID로 mm_idx 조회
		const [userRows] = await connection.execute(
			'SELECT mm_idx FROM st_member WHERE mm_id = ? AND mm_status = "Y"',
			[user_id]
		);
		
		if (userRows.length > 0) {
			const mm_idx = userRows[0].mm_idx;
			
			// FCM 토큰 조회
			const [tokenRows] = await connection.execute(
				'SELECT token FROM st_member_device WHERE mm_idx = ? AND push_yn = "Y" ORDER BY up_dt DESC LIMIT 1',
				[mm_idx]
			);
			
			await connection.end();
			
			if (tokenRows.length > 0 && tokenRows[0].token) {
				callback(tokenRows[0].token);
			} else {
				callback(null);
			}
		} else {
			await connection.end();
			callback(null);
		}
	} catch (error) {
		//$common.writeLog('MySQL FCM 토큰 조회 오류: ' + error.message);
		callback(null);
	}
}

//MySQL에서 push_yn 업데이트 (로그아웃 시 사용)
container.updatePushYnFromMySQL = async function(socket_id, push_yn, callback){
	try {
		// 먼저 MongoDB에서 socket_id로 user_id 조회
		var mongo_user = await $db.cs_user.findOne({socket_id : socket_id});
		
		if (!mongo_user || !mongo_user.user_id) {
			//$common.writeLog('updatePushYnFromMySQL: socket_id로 사용자를 찾을 수 없음 - socket_id: ' + socket_id); 
			callback(false);
			return;
		}
		
		var user_id = mongo_user.user_id;
		
		// MySQL 연결 및 쿼리 실행
		const mysql = require('mysql2/promise');
		const connection = await mysql.createConnection({
			host: 'localhost',
			user: 'root',
			password: 'password',
			database: 'nowapk'
		});
		
		// 사용자 ID로 mm_idx 조회 후 push_yn 업데이트
		const [updateResult] = await connection.execute(
			'UPDATE st_member_device SET push_yn = ?, up_dt = NOW() WHERE mm_idx = (SELECT mm_idx FROM st_member WHERE mm_id = ? AND mm_status = "Y")',
			[push_yn, user_id]
		);
		
		await connection.end();
		
		//$common.writeLog('updatePushYnFromMySQL: push_yn 업데이트 완료 - user_id: ' + user_id + ', push_yn: ' + push_yn);
		callback(true);
	} catch (error) {
		//$common.writeLog('MySQL push_yn 업데이트 오류: ' + error.message);
		callback(false);
	}
}

//MySQL에서 신뢰 가능한 FCM 토큰 조회 (채팅 푸시 전용)
container.getTokenFromMySQL = function(user_id) {
	return new Promise((resolve) => {
		const $mysql = require("../database/mysql.js");
		if (!$mysql.db_conn) {
	
			resolve(null);
			return;
		}
		const sql = "SELECT d.token " +
			"FROM st_member_device d " +
			"JOIN st_member m ON m.mm_idx = d.mm_idx AND m.mm_status = 'Y' " +
			"WHERE m.mm_id = ? AND d.push_yn = 'Y' " +
			"ORDER BY d.up_dt DESC LIMIT 1";
		$mysql.db_conn.query(sql, [user_id], function(err, results) {
			if (err) {
			
				resolve(null);
				return;
			}
			if (results && results.length > 0 && results[0].token) {
			
				resolve(results[0].token);
			} else {
				
				resolve(null);
			}
		});
	});
};




/////////////////////////////////////////////////////
// 객체 적용
/////////////////////////////////////////////////////
module.exports = container;
