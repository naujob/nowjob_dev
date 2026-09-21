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
const $user = require("../model/user_model.js");
const $common = require("../common/common.js");

/////////////////////////////////////////////////////
// 변수 설정
/////////////////////////////////////////////////////

/////////////////////////////////////////////////////
// 기능 정의
/////////////////////////////////////////////////////

//유저 등록
container.insertUser = async function(data,callback){
	var chk_user = await $user.getUser({user_id : data.user_id});

	if(chk_user!=null){
		callback($common.jsonReturn("10","이미 존재하는 아이디가 있습니다."));
		return;
	}

	$user.insertUser(data,async function(ret){
		$common.chkLog("check ::: user_id : " + data.user_id + ":::" + "insert_count : " + ret);
		if(ret == true){
			var user = await $user.getUser({user_id : data.user_id});
			callback($common.jsonReturn("00","회원가입 성공.",user));
		}else{
			callback($common.jsonReturn("90","회원가입시 문제가 발생하였습니다."));
		}
	});
}

//유저 동기화 + 서버
container.insertAutoUser = async function(data,callback){
	var chk_user = await $user.getUser({user_id : data.user_id});

	if(chk_user!=null){
		//업데이트
		$user.updateUser(data,async function(ret){
			callback($common.jsonReturn("00","조회 성공",chk_user));
		});
		return;
	}

	$user.insertUser(data,async function(ret){
		$common.chkLog("check ::: user_id : " + data.user_id + ":::" + "insert_count : " + ret);
		if(ret == true){
			var user = await $user.getUser({user_id : data.user_id});
			callback($common.jsonReturn("00","회원가입 성공.",user));
		}else{
			callback($common.jsonReturn("90","회원가입시 문제가 발생하였습니다."));
		}
	});
}

//유저 수정
container.updateUserSocketId = async function(data,callback){
	$user.updateUserSocketId(data,async function(ret){
		callback($common.jsonReturn("00","수정완료",""));
	});
}

//유저 상태 변경
container.updateUserStatus = async function(data,callback){
	$user.updateUserStatus(data,async function(ret){
		// user_status가 'N'이면 MySQL의 push_yn도 'N'으로 업데이트 (로그아웃 처리)
		if (data.user_status === "N" && data.socket_id) {
			$user.updatePushYnFromMySQL(data.socket_id, "N", function(success){
				if (success) {
					$common.writeLog('updateUserStatus: MySQL push_yn 업데이트 완료');
				} else {
					$common.writeLog('updateUserStatus: MySQL push_yn 업데이트 실패');
				}
			});
		}
		callback($common.jsonReturn("00","수정완료",""));
	});
}

//FCM 토큰 업데이트
container.updateUserToken = async function(data,callback){
	$user.updateUserToken(data,async function(ret){
		callback($common.jsonReturn("00","토큰 업데이트 완료",""));
	});
}

//유저 조회
container.getUser = async function(data,callback){
	var user = await $user.getUser({user_id : data.user_id});
	callback(user);
}

//MySQL에서 신뢰 가능한 FCM 토큰 조회
container.getTokenFromMySQL = function(user_id) {
	return $user.getTokenFromMySQL(user_id);
}

//유저 리스트 조회 : 어디 사용하는거지?
container.getUserList = async function(data,callback){
	var params = {'user_id' : {'$ne' : data.user_id}};
	var user_list = await $user.getUserList(params);
	callback(user_list);
}

//유저 리스트 조회
container.getUserListV2 = async function(params,callback){
	var user_list = await $user.getUserList(params);
	callback(user_list);
}

//유저 로그인 체크
container.loginCheck = async function(data,callback){
	var user = await $user.getUser({user_id : data.user_id});
	if(user==null){
		callback($common.jsonReturn("90","등록된 회원이 업습니다."));
	}else{
		var is_passwd = $common.passwordCompare(data.user_pwd,user.user_pwd);
		if(is_passwd==true){
			callback($common.jsonReturn("00","로그인성공.",user));
		}else{
			callback($common.jsonReturn("91","비밀번호를 확인해주세요."));
		}
	}
}



// MySQL에서 FCM 토큰 조회
container.getUserTokenFromMySQL = async function(user_id, callback) {
	try {
		$common.writeLog('getUserTokenFromMySQL 시작 - user_id: ' + user_id);
		
		// MySQL 연결
		const $mysql = require("../database/mysql.js");
		
		// MySQL 연결 초기화 확인
		if (!$mysql.db_conn) {
			$common.writeLog('MySQL 연결이 초기화되지 않음');
			$mysql.init();
		}
		
		// 사용자 ID로 FCM 토큰 조회
		const sql = "SELECT token FROM st_member_device WHERE mm_idx = (SELECT mm_idx FROM st_member WHERE mm_id = ? AND mm_status = 'Y') AND push_yn = 'Y' ORDER BY up_dt DESC LIMIT 1";
		
		$mysql.db_conn.query(sql, [user_id], function(err, results) {
			if (err) {
				$common.writeLog('MySQL 토큰 조회 오류: ' + err.message);
				callback("");
				return;
			}
			
			if (results && results.length > 0 && results[0].token) {
				$common.writeLog('MySQL에서 FCM 토큰 발견: ' + results[0].token.substring(0, 20) + '...');
				callback(results[0].token);
			} else {
				$common.writeLog('MySQL에서 FCM 토큰 없음 - user_id: ' + user_id);
				callback("");
			}
		});
		
	} catch (err) {
		$common.writeLog('getUserTokenFromMySQL 오류: ' + err.message);
		callback("");
	}
}

/////////////////////////////////////////////////////
// 객체 적용
/////////////////////////////////////////////////////
module.exports = container;
