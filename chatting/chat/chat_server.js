const express = require("express");
const fs = require('fs');
const path = require('path');
const app = express();
const bodyParser = require('body-parser');
const $config = require('./config/config.js');
const app_env = (process.env.APP_ENV || 'production').toLowerCase();
const is_dev_runtime = app_env === 'development' || app_env === 'dev' || app_env === 'local';

let server;
// if (is_dev_runtime) {

// 	server = require("http").createServer(app); 
// } else { 
// 	const ssl_key_path = process.env.CHAT_SSL_KEY_PATH || '/etc/pki/ssl/nowjob.co.kr_20260531839F1.key.pem';
    
 
//     const ssl_cert_path = process.env.CHAT_SSL_CERT_PATH || '/etc/pki/ssl/nowjob.co.kr_20260531839F1.crt.pem';
// 	var options = { 
// 		key: fs.readFileSync(ssl_key_path),
// 		cert: fs.readFileSync(ssl_cert_path)
// 	};
// 	server = require("https").createServer(options, app);
// }

server = require("http").createServer(app); // node.js 서버 생성
const io = require('socket.io')(server, { // Socket.io 서버 초기화
	cors: {
		origin: "*",
		methods: ["GET", "POST"],
		credentials: true
	},
	allowEIO3: true, // 이전 버전 호환성
	transports: ['websocket', 'polling']
});
const cors = require('cors');
const async = require('async');
const await = require('await');

/*

var options = {
	key: fs.readFileSync('/etc/httpd/ssl/nowapk/nowapk.co.kr_2022101213697.key.pem'),
	cert: fs.readFileSync('/etc/httpd/ssl/nowapk/nowapk.co.kr_2022101213697.crt.pem')
};
const https = require("https").createServer(options,app);
*/

/////////////////////////////////////////////////////
// FCM 설정
/////////////////////////////////////////////////////
const admin = require('firebase-admin');
const firebase_prod_path = path.join(__dirname, 'config', 'firebase-service-account.json');
const firebase_dev_path = path.join(__dirname, 'config', 'firebase-service-account-dev.json');

let firebase_service_account_path = process.env.CHAT_FIREBASE_SERVICE_ACCOUNT_PATH || '';
if (!firebase_service_account_path) {
	const default_paths = is_dev_runtime
		? [firebase_dev_path, firebase_prod_path]
		: [firebase_prod_path, firebase_dev_path];
	firebase_service_account_path = default_paths.find((p) => fs.existsSync(p)) || default_paths[0];
}

if (!fs.existsSync(firebase_service_account_path)) {
	throw new Error(`Firebase service account file not found: ${firebase_service_account_path}`);
}

const serviceAccount = JSON.parse(fs.readFileSync(firebase_service_account_path, 'utf8'));
const firebase_project_id = $config.firebase.project_id || serviceAccount.project_id;
const fcm_admin = admin.initializeApp({
	credential: admin.credential.cert(serviceAccount),
	projectId: firebase_project_id
});

// FCM 발송 함수
function send_fcm(idx, data) {
	//토큰이 없거나 비어있으면 실행하지 않는다.
	if (!data["no_token"] || data["no_token"] == "" || data["no_token"] == null) {
		return false;
	}

	//발송 조건 확인 : 푸시 수신여부 등확인. : 추가해야함.
	if (1 == 1) {

	}

	const registrationToken = data["no_token"];

	// 메시지 내용에서 "[알 수 없음]"을 실제 사용자 이름으로 교체
	var message_text = data["no_text"];
	if (message_text && message_text.includes("[알 수 없음]")) {
		// 메시지에서 발신자 ID 추출 (메시지 패턴: "[알 수 없음]님의 채용공고 메시지가 도착했습니다.")
		// 이 경우 실제 발신자 정보를 데이터베이스에서 조회

		// 임시로 기본값 설정 (실제로는 더 정교한 로직이 필요)
		message_text = message_text.replace("[알 수 없음]", "사용자");
	}

	// 채팅방 URL 추출 (푸시 알림 클릭 시 이동할 URL)
	const chat_url = data["no_url"] || "";

	// iOS 배지 숫자: 실제 미확인 알림 개수 조회
	let badge_count = 0; // 기본값: 조회 실패 시 배지 숨김
	if (data["user_id"]) {
		// 미확인 알림 개수 조회
		$exe.alarm.getAlarmCount({ user_id: data["user_id"], show_yn: "N" }, function (ret) {
			if (ret && ret.data !== undefined) {
				badge_count = ret.data || 0; // 실제 알림 개수 (0이면 배지 숨김)
			} else {
				badge_count = 0; // 조회 실패 시 배지 숨김
			}

			// 각 알림마다 고유한 notificationId 생성 (네이티브 앱의 PendingIntent 고유성 보장을 위해)
			// 타임스탬프 + 랜덤 값 조합으로 고유성 보장
			const notificationId = (Date.now() % 2147483647) + Math.floor(Math.random() * 1000);

			// FCM 메시지 구성 (Firebase Admin SDK는 data 필드의 모든 값이 문자열이어야 함)
			// notification 페이로드 제거: 안드로이드 13+에서 OS가 알림을 자동 생성하여 onMessageReceived()가 호출되지 않음
			// data 페이로드만 사용하여 onMessageReceived()가 항상 호출되도록 보장
			const message = {
				data: {
					title: String(data["no_subject"] || ""),
					body: String(message_text || ""),
					url: String(chat_url || ""),  // URL을 문자열로 명시적 변환
					type: "chat_message",
					timestamp: String(Date.now()),
					notificationId: String(notificationId),  // 네이티브 앱에서 PendingIntent의 requestCode로 사용
					click_action: String(chat_url || "")  // Android용 click_action 추가
				},
				// Android용 추가 설정
				android: {
					priority: "high"  // 메시지 우선순위 (high로 설정 시 즉시 전달)
					// android.notification 제거: data 페이로드만 사용하여 클라이언트에서 커스텀 알림 생성
				},
				// iOS용 추가 설정
				apns: {
					payload: {
						aps: {
							sound: "default",
							badge: badge_count  // 실제 미확인 알림 개수로 설정
						},
						url: chat_url || ""  // iOS용 커스텀 데이터
					}
				},
				token: registrationToken
			};

			// Send a message to the device corresponding to the provided
			// registration token.
			fcm_admin.messaging().send(message)
				.then((response) => {
					//푸시 성공처리
					$exe.alarm.updateNotification(idx, { send_yn: "Y" }, function (ret) { });
				})
				.catch((error) => {
					//푸시 실패처리
					$exe.alarm.updateNotification(idx, { send_yn: "N", no_result: error }, function (ret) { });
				});
		});
	} else {
		// user_id가 없는 경우 기본값으로 처리
		const message = {
			notification: {
				title: data["no_subject"],
				body: message_text,
			},
			data: {
				title: String(data["no_subject"] || ""),
				body: String(message_text || ""),
				url: String(chat_url || ""),  // URL을 문자열로 명시적 변환
				type: "chat_message",
				timestamp: String(Date.now()),
				notification_id: notification_id,  // Android 앱에서 고유한 PendingIntent 생성에 사용
				click_action: String(chat_url || "")  // Android용 click_action 추가
			},
			// Android용 추가 설정
			android: {
				priority: "high",  // 메시지 우선순위 (high로 설정 시 즉시 전달)
				notification: {
					clickAction: chat_url || "",  // Android에서 알림 클릭 시 실행할 액션
					channelId: "nowapk_default",  // 알림 채널 ID (앱의 CH_ID와 일치해야 함)
					sound: "default",  // 알림 소리
					defaultSound: true,  // 기본 소리 사용
					defaultVibrateTimings: true,  // 기본 진동 패턴 사용
					visibility: "public"  // 잠금 화면에도 알림 표시 (public: 완전 표시, private: 일부만, secret: 표시 안함)
				}
			},
			// iOS용 추가 설정
			apns: {
				payload: {
					aps: {
						sound: "default",
						badge: badge_count  // 기본값 0 (배지 숨김)
					},
					url: chat_url || ""  // iOS용 커스텀 데이터
				}
			},
			token: registrationToken
		};

		// Send a message to the device corresponding to the provided
		// registration token.
		fcm_admin.messaging().send(message)
			.then((response) => {
				//푸시 성공처리
				$exe.alarm.updateNotification(idx, { send_yn: "Y" }, function (ret) { });
			})
			.catch((error) => {
				//푸시 실패처리
				$exe.alarm.updateNotification(idx, { send_yn: "N", no_result: error }, function (ret) { });
			});
	}
}

/////////////////////////////////////////////////////
// 커스텀 모듈 로드
/////////////////////////////////////////////////////
const $common = require("./common/common.js");
const $db = require("./database/database.js");
const $mysql = require("./database/mysql.js");
const $model = require("./model/model.js");
const $exe = require("./controller/controller.js");
const push_api_host = $config.push.host || 'localhost';
const push_api_port = parseInt($config.push.port || '443', 10);
const push_api_path = $config.push.path || '/common_proc/send_chat_push';

/////////////////////////////////////////////////////
// 변수 초기화
/////////////////////////////////////////////////////
const port = $config.server.port;
var room_list = new Array();

//접속허옹
app.use(cors());
app.use(bodyParser.json({ limit: '50mb' })); //body 의 크기 설정
app.use(bodyParser.urlencoded({ limit: '50mb', extended: true })); //url의 크기 설정
app.use(bodyParser.json());

//서버 시작
server.listen(port, () => {
	// 서버 시작 로그
	//$common.writeLog('========== 채팅 서버 시작 ==========');
	//$common.writeLog('포트: ' + port); // 포트: 3031
	//$common.writeLog('환경: ' + (is_dev_runtime ? 'development' : 'production')); //development
	//$common.writeLog('========== 환경변수 정보 ==========');
	//$common.writeLog('APP_ENV: ' + process.env.APP_ENV); //APP_ENV: development
	//$common.writeLog('CHAT_SERVER_PORT: ' + process.env.CHAT_SERVER_PORT); // CHAT_SERVER_PORT: 3031
	//$common.writeLog('CHAT_MONGO_DB: ' + process.env.CHAT_MONGO_DB); // CHAT_MONGO_DB: dev_chat
	//$common.writeLog('CHAT_MONGO_URL: ' + process.env.CHAT_MONGO_URL); // :CHAT_MONGO_URL: mongodb://localhost
	//$common.writeLog('CHAT_MYSQL_HOST: ' + process.env.CHAT_MYSQL_HOST); // CHAT_MYSQL_HOST: localhost
	//$common.writeLog('CHAT_MYSQL_DB: ' + process.env.CHAT_MYSQL_DB); // CHAT_MYSQL_DB: dev_nowapk
	//$common.chkLog('채팅 서버가 포트 ' + port + '에서 시작되었습니다.');

	//데이터베이스 초기화
	$db.init();

	// MongoDB 연결 대기 후 서버 준비 완료
	$db.waitForConnection((err) => {
		// MongoDB 연결 상태는 조용히 처리
	});

	//mysql 초기화
	$mysql.init();
});

///////////////////////////////////////////////////////
// 유저관련 처리
///////////////////////////////////////////////////////

//회원 등록처리
//app.post('/user_insert', function(req,res) {
//	var data = req.body;
//	//$common.writeLog("user_insert :: user_id:" + req.body.user_id);
//	//res.send(req.body);
//	$exe.user.insertUser(data,function(ret){
//		socket.emit('user_add_send', {});
//		res.send(ret);
//	});
//});

//유저 로그인
//app.post('/user_login', function(req,res) {
//	var data = req.body;
//	$exe.user.loginCheck(data,function(ret){
//		//로그인 문제 없으면 소켓 업데이트
//		if(ret.code == "00"){
//
//		}
//		res.send(ret);
//	});
//});

//MongoDB 채팅방 생성 API (common_proc.php에서 호출)
app.post('/newroommake', function (req, res) {
	var data = req.body;

	$exe.chat.insertRoom(data, function (ret) {
		res.send(ret);
	});
});

//룸 리스트
app.post('/room_list', function (req, res) {
	var data = req.body;
	$exe.chat.getRoomList(data, function (ret) {
		res.send(ret);
	});
});

//룸 리스트2
app.post('/room_list2', function (req, res) {
	var data = req.body;
	$exe.chat.getRoomList(data, function (ret) {
		res.send(ret);
	});
});


//접속 처리
io.on('connection', function (socket) {
	//회원정보 접속처리

	//연결테스트
	socket.emit('connection_return', `${socket.id} 연결 되었습니다.`);

	///////////////////////////////////////////////////////
	// 유저관련 처리
	///////////////////////////////////////////////////////


	//유저 등록
	socket.on('user_insert', function (data) {
		//소켓 아이디 추가
		data.socket_id = socket.id;

		//회원 등록
		$exe.user.insertUser(data, function (ret) {
			//회원추가 알림
			socket.broadcast.emit('user_insert_alarm', '신규회원가입 알림');

			//리턴
			socket.emit('user_insert_return', ret);
		});
	});

	//유저 등록
	socket.on('user_auto_insert', function (data) {
		//소켓 아이디 추가
		data.socket_id = socket.id;

		//회원 등록
		$exe.user.insertAutoUser(data, function (ret) {
			// FCM 토큰이 있으면 함께 등록
			if (data.token && data.token !== "" && data.token !== null) {
				var token_data = {
					user_id: data.user_id,
					token: data.token
				};
				$exe.user.updateUserToken(token_data, function (token_ret) { });
			}

			//회원추가 알림
			socket.broadcast.emit('user_insert_alarm', '접속 알림');

			//리턴
			socket.emit('user_insert_return', ret);
		});
	});

	//유저 로그인
	socket.on('user_login', function (data) {
		$exe.user.loginCheck(data, function (ret) {
			//로그인 문제 없으면 소켓 업데이트
			if (ret.code == "00") {

			}

			//회원추가 접속 알림
			socket.broadcast.emit('user_login_alarm', '로그인 알림');

			//리턴
			socket.emit('user_login_return', ret);
		});
	});

	//새로고침 회원 소켓 업데이트 : 접속중으로 처리
	socket.on('update_status', function (data) {
		//회원아이디랑 소켓아이디 저장.
		var params = { user_id: data.user_id, socket_id: socket.id, last_location: data.last_location };
		$exe.user.updateUserSocketId(params, function (ret) { });
	});

	//FCM 토큰 등록 (안드로이드 앱에서만)
	socket.on('update_token', function (data) {
		// 토큰이 유효한 경우에만 저장 (안드로이드 앱에서만 토큰이 발급됨)
		if (data.token && data.token !== "" && data.token !== null) {
			var params = { user_id: data.user_id, token: data.token };
			$exe.user.updateUserToken(params, function (ret) {
				socket.emit('update_token_return', ret);
			});
		} else {
			// 토큰이 없어도 응답은 보내줌
			socket.emit('update_token_return', { code: "01", msg: "토큰이 없어서 등록을 건너뜁니다." });
		}
	});

	//친구 리스트 조회 : 현재는 모든 친구 검색하고 있음 : 수정해야함.
	socket.on('user_list', function (data) {
		$exe.user.getUserList(data, function (ret) {
			socket.emit('user_list_return', ret);
		});
	});

	//팝업 친구 리스트 : 현재는 모든 친구 검색하고 있음 : 수정해야함.
	socket.on('pop_user_list', function (data) {
		$exe.user.getUserList(data, function (ret) {
			socket.emit('pop_user_list_return', ret);
		});
	});

	///////////////////////////////////////////////////////
	// 채팅관련 처리
	///////////////////////////////////////////////////////

	// 룸 접속 처리
	socket.on('room_join', function (data) {
	//	$common.writeLog("socket room_join 시작 rm_idx=" + data.rm_idx + " con_user_id=" + data.con_user_id); //  rm_idx=31 con_user_id=01011111111
		socket.join(data.rm_idx);

		// 무엇을 update하지? → "방 입장 정보"를 update하는 동작입니다.
		// updateEnterRoom 함수는 사용자가 채팅방(rm_idx)에 입장할 때, 해당 사용자의 입장(읽음) 정보를 DB에 기록하고
		// 아직 읽지 않은 메시지들의 정보를 조회해서 전달합니다.
		$exe.chat.updateEnterRoom(data, function (ret) {
			//모두 리턴한다.
			//$common.writeLog('room_join_return::' + ret.data);
			//socket.to(data.rm_idx).emit('room_join_return', ret);

			// ret는 { code: "00", msg: "처리성공", data: { list: [...] } } 형식
			// ret.data.list에 실제 리스트가 있음
			/**
			 * actualList 구조:
			 * - 타입: Array
			 * - 내용: 채팅방 입장 시 읽음 처리 전 상태의 읽지 않은 메시지 정보 배열
			 * - 각 항목 구조:
			 *   {
			 *     "_id": Number,    // 메시지 ID (mg_idx)
			 *     "count": Number   // 해당 메시지를 읽지 않은 사용자 수 (read_yn="N"인 사용자 수)
			 *   }
			 *  { _id: mg_idx, count: 읽지 않은 사용자 수 }
			 * 예시:
			 * [
			 *   { "_id": 4306, "count": 1 },  // 메시지 4306번을 1명이 읽지 않음
			 *   { "_id": 4307, "count": 1 }   // 메시지 4307번을 1명이 읽지 않음
			 * ]
			 * 
			 * 참고:
			 * - updateEnterRoom 함수에서 읽음 처리 전(read_yn="N") 상태를 먼저 조회하여 반환
			 * - 이후 DB에서 실제 읽음 처리(read_yn="N" → "Y") 실행
			 * - 따라서 actualList는 읽음 처리 전 상태의 데이터임
			 */
			var actualList = (ret && ret.data && ret.data.list) ? ret.data.list : [];
			// rm_idx=31 unread_messages=0
			//$common.writeLog("socket room_join updateEnterRoom 완료 rm_idx=" + data.rm_idx + " unread_messages=" + (actualList ? actualList.length : 0));

			// ret를 { code, msg, list } 형식으로 변환하여 전송
			var returnData = {
				code: ret ? ret.code : "00",
				msg: ret ? ret.msg : "success",
				list: actualList
			};
			socket.emit('room_join_return', returnData);

			// 🔥 실시간 읽음 처리: 채팅방 입장 시 읽음 처리된 모든 메시지의 읽음 카운트를 실시간으로 업데이트
			// actualList에는 읽음 처리 전 { _id: mg_idx, count: 읽지 않은 개수 } 형태의 배열이 있음
			// 입장한 사용자가 읽음 처리했으므로, 각 메시지의 실제 읽음 카운트를 계산해야 함
			if (actualList && Array.isArray(actualList) && actualList.length > 0) {
				// 🔥 각 메시지에 대해 실제 읽음 카운트 계산
				var processMessage = function (index) {
					if (index >= actualList.length) return;

					var msgInfo = actualList[index];

					// 🔥 mg_idx가 null이면 건너뛰기
					if (!msgInfo._id || msgInfo._id === null || msgInfo._id === undefined) {
						processMessage(index + 1);
						return;
					}

					// 🔥 실제 읽음 카운트 계산 (입장한 사용자가 읽은 후의 count)
					// actualList의 count는 읽음 처리 전 상태이므로, 입장한 사용자가 읽었으므로 1을 뺌
					// 단, 입장한 사용자가 보낸 메시지의 경우 원래 count에 입장한 사용자가 포함되어 있지 않을 수 있음
					// 따라서 실제 읽음 카운트를 계산하기 위해 updateMessageReturnCount를 호출
					$exe.chat.updateMessageReturnCount({
						rm_idx: data.rm_idx,
						mg_idx: msgInfo._id,
						// con_user_id는 현재 채팅방에 입장한(읽음 처리를 하는) 사용자의 ID를 의미함.
						// 예) 본인이 방에 입장할 때 자신의 user_id가 con_user_id로 전달됨
						user_id: data.con_user_id
					}, function (ret) {
						// 🔥 ret는 {code, msg, data: {count, mg_idx}} 형태로 반환됨
						var resultData = (ret && ret.data) ? ret.data : ret;
						var mg_idx = resultData.mg_idx;
						var count = resultData.count;

						// 🔥 mg_idx가 null이면 emit하지 않음
						if (mg_idx && mg_idx !== null && mg_idx !== undefined) {
							//$common.writeLog('[message_read_count_return] emit 시작 (room_join) - rm_idx: ' + data.rm_idx + ', mg_idx: ' + mg_idx + ', count: ' + count);
							io.sockets.in(data.rm_idx).emit('message_read_count_return', {
								data: {
									count: count,  // 실제 읽음 카운트
									mg_idx: mg_idx
								}
							});
							//$common.writeLog('[message_read_count_return] emit 완료');
						}

						// 다음 메시지 처리
						processMessage(index + 1);
					});
				};

				// 첫 번째 메시지부터 처리 시작
				processMessage(0);
			}
		});
	});

	// 룸 해제 처리
	socket.on('room_leave', function (data) {
		socket.leave(data.rm_idx);
		socket.emit('room_leave_return', data.rm_idx);
	});

	//룸 생성하기
	socket.on('room_insert', function (data) {
		$exe.chat.insertRoom(data, function (ret) {
			socket.emit('room_insert_return', ret);
		});
	});


	//룸 조회하기
	socket.on('room_info', function (data) {
		//$common.writeLog("[NODE_DEBUG] socket room_info 시작 rm_idx=" + data.rm_idx + " con_user_id=" + data.con_user_id);
		// 🔥 읽음 처리: 채팅방에 입장할 때만 읽음 처리
		// room_join과 함께 호출될 때는 읽음 처리를 하되,
		// 단독으로 호출될 때(예: 채팅방 목록 새로고침)는 읽음 처리하지 않음
		// 이를 구분하기 위해 소켓이 이미 해당 방에 join되어 있는지 확인
		// room_join이 먼저 호출되면 socket.join으로 방에 join되므로, room_info에서는 읽음 처리하지 않음
		var is_already_joined = socket.rooms.has(String(data.rm_idx));
		console.log("[NODE_DEBUG] is_already_joined=" + is_already_joined);

		// 🔥 읽음 처리: room_join이 먼저 호출되지 않았을 때만 읽음 처리
		// room_join이 먼저 호출되면 socket.join으로 방에 join되므로, room_info에서는 읽음 처리하지 않음
		// (room_join에서 이미 읽음 처리를 했으므로)
		if (!is_already_joined) {
			console.log("[NODE_DEBUG] updateEnterRoom 호출 시작");
			$exe.chat.updateEnterRoom(data, function (ret) {
				console.log("[NODE_DEBUG] updateEnterRoom 콜백 - ret:", JSON.stringify(ret).substring(0, 200));

				// room_join과 동일하게 각 메시지의 readcount를 계산하여 emit
				var actualList = (ret && ret.list) ? ret.list : [];
				console.log("[NODE_DEBUG] actualList 길이=" + (actualList ? actualList.length : 0));
			//	$common.writeLog("socket room_info updateEnterRoom 완료 rm_idx=" + data.rm_idx + " unread_messages=" + (actualList ? actualList.length : 0));

				if (actualList && Array.isArray(actualList) && actualList.length > 0) {
					// 🔥 각 메시지에 대해 실제 읽음 카운트 계산
					var processMessage = function (index) {
						if (index >= actualList.length) {
							// 모든 메시지 처리 완료 후 방 정보 조회
							console.log("[NODE_DEBUG] getRoom 호출 시작");
							$exe.chat.getRoom(data, function (ret) {
								console.log("[NODE_DEBUG] getRoom 콜백 - ret:", JSON.stringify(ret).substring(0, 200));
								processRoomInfo(ret, data, socket);
							});
							return;
						}

						var msgInfo = actualList[index];

						// 🔥 mg_idx가 null이면 건너뛰기
						if (!msgInfo._id || msgInfo._id === null || msgInfo._id === undefined) {
							processMessage(index + 1);
							return;
						}

						// 🔥 실제 읽음 카운트 계산
						$exe.chat.updateMessageReturnCount({
							rm_idx: data.rm_idx,
							mg_idx: msgInfo._id,
							user_id: data.con_user_id
						}, function (ret) {
							// 🔥 ret는 {code, msg, data: {count, mg_idx}} 형태로 반환됨
							var resultData = (ret && ret.data) ? ret.data : ret;
							var mg_idx = resultData.mg_idx;
							var count = resultData.count;

							// 🔥 mg_idx가 null이면 emit하지 않음
							if (mg_idx && mg_idx !== null && mg_idx !== undefined) {
								//$common.writeLog('[message_read_count_return] emit 시작 (room_info) - rm_idx: ' + data.rm_idx + ', mg_idx: ' + mg_idx + ', count: ' + count);
								io.sockets.in(data.rm_idx).emit('message_read_count_return', {
									data: {
										count: count,
										mg_idx: mg_idx
									}
								});
								//$common.writeLog('[message_read_count_return] emit 완료');
							}

							// 다음 메시지 처리
							processMessage(index + 1);
						});
					};

					// 첫 번째 메시지부터 처리 시작
					processMessage(0);
				} else {
					//$common.writeLog("[NODE_DEBUG] updateEnterRoom unread_messages=0");
					//	$common.writeLog("socket room_info updateEnterRoom unread_messages=0 rm_idx=" + data.rm_idx);
					// 읽지 않은 메시지가 없으면 바로 방 정보 조회
					//$common.writeLog("[NODE_DEBUG] getRoom 호출 시작 (unread=0)");
					$exe.chat.getRoom(data, function (ret) {
						console.log("[NODE_DEBUG] getRoom 콜백 - ret:", JSON.stringify(ret).substring(0, 200));
						processRoomInfo(ret, data, socket);
					});
				}
			});
		} else {
			console.log("[NODE_DEBUG] 이미 join됨, getRoom 호출");
			// 이미 방에 join되어 있으면 (room_join이 먼저 호출됨) 읽음 처리하지 않고 정보만 조회
			$exe.chat.getRoom(data, function (ret) {
				console.log("[NODE_DEBUG] getRoom 콜백 - ret:", JSON.stringify(ret).substring(0, 200));
				processRoomInfo(ret, data, socket);
			});
		}

		function processRoomInfo(ret, data, socket) {
			console.log("[NODE_DEBUG] processRoomInfo 시작 - ret 타입:", typeof ret, "ret이 null?", ret === null, "ret이 undefined?", ret === undefined);
			console.log("[NODE_DEBUG] ret 값:", JSON.stringify(ret).substring(0, 300));
			// 방 정보가 없으면 자동으로 생성
			if (!ret || !ret.data) {
				console.log("[NODE_DEBUG] 방 정보 없음 - MongoDB에서 조회 불가");
				//$common.writeLog("socket room_info 방정보 없음 rm_idx=" + data.rm_idx + " -> findExistingRoomByMySQL");
				// 🔥 MySQL에서 실제 rm_idx 확인 (rc_idx, ru_idx로 기존 MongoDB 방 찾기)
				$exe.chat.findExistingRoomByMySQL(data, function (existing_room_result) {
					if (existing_room_result && existing_room_result.existing_rm_idx) {
						//$common.writeLog("findExistingRoomByMySQL 기존방 발견 rm_idx=" + existing_room_result.existing_rm_idx);
						// 기존 방으로 조회
						var existing_data = {
							rm_idx: existing_room_result.existing_rm_idx,
							con_user_id: data.con_user_id
						};
						$exe.chat.getRoom(existing_data, function (existing_ret) {
							if (existing_ret && existing_ret.data) {
								//채팅방 접속
								socket.room = existing_room_result.existing_rm_idx;
								socket.join(existing_room_result.existing_rm_idx);

								//소켓 리턴
								socket.emit('room_info_return', existing_ret);
							} else {
								//$common.writeLog("findExistingRoomByMySQL 결과로도 방 없음 -> 새 방 생성");
								// 기존 방도 없으면 새로 생성
								createNewRoom(existing_room_result);
							}
						});
					} else if (existing_room_result && (existing_room_result.rc_idx || existing_room_result.ru_idx)) {
						//$common.writeLog("findExistingRoomByMySQL rc_idx/ru_idx만 확인됨 -> 새 방 생성 rc_idx=" + existing_room_result.rc_idx + " ru_idx=" + existing_room_result.ru_idx);
						// MySQL에서 rc_idx, ru_idx는 찾았지만 MongoDB에 기존 방이 없음 - 새로 생성 (rc_idx, ru_idx 포함)
						createNewRoom(existing_room_result);
					} else {
						//$common.writeLog("findExistingRoomByMySQL 결과 없음 -> 새 방 생성 rm_idx=" + data.rm_idx);
						// MySQL에서도 찾지 못했으면 새로 생성
						createNewRoom(null);
					}
				});

				// 새 방 생성 함수
				function createNewRoom(mysql_room_info) {
					// 새 방 정보 생성 (MySQL에서 조회한 rc_idx, ru_idx 사용)
					var room_data = {
						rm_idx: data.rm_idx,
						rc_idx: (mysql_room_info && mysql_room_info.rc_idx) ? mysql_room_info.rc_idx : 0,
						ru_idx: (mysql_room_info && mysql_room_info.ru_idx) ? mysql_room_info.ru_idx : null,
						rm_type: "recruit",
						rm_img: "",
						rm_subject: "채용공고 채팅방",
						rm_desc: "채용공고 관련 채팅방",
						rm_last_msg: "",
						rm_last_dt: $common.getCurrentDate(),
						user_list: [
							{ user_id: data.con_user_id }
						],
						rm_status: "N",
						rm_messages: []
					};

					$exe.chat.insertRoom(room_data, function (insert_ret) {
						// 생성된 방 정보로 다시 조회
						$exe.chat.getRoom(data, function (new_ret) {
							//채팅방 접속
							socket.room = data.rm_idx;
							socket.join(data.rm_idx);

							//소켓 리턴
							socket.emit('room_info_return', new_ret);
						});
					});
				}
			} else {
				console.log("[NODE_DEBUG] room_info_return emit 준비 - ret 길이:", Object.keys(ret).length);
				//채팅방 접속
				socket.room = data.rm_idx;
				socket.join(data.rm_idx);

				//소켓 리턴
				console.log("[NODE_DEBUG] room_info_return emit 시작");
				socket.emit('room_info_return', ret);
				console.log("[NODE_DEBUG] room_info_return emit 완료");
			}

			//모두 리턴한다.
			socket.to(data.rm_idx).emit('room_join_alarm', data.con_user_id);
		}
	});

	//알람 메시지 발송
	socket.on('alarm_message', function (data) {
		$exe.user.getUser({ user_id: data.user_id }, function (user_info) {
			var target_socket_id = user_info.socket_id;

			//푸시 발송
			var push = {
				user_id: data.user_id,
				no_subject: data.subject,
				no_text: data.text,
				no_url: data.url,
				no_token: user_info.token,
				no_result: "",
				reg_dt: $common.getCurrentDate()
			};
			$exe.alarm.insertNotification(push, function (idx) {
				send_fcm(idx, push);
			});

			//알림 추가
			var alarm = {
				user_id: data.user_id,
				al_type: data.type,
				al_key: data.key,
				al_text: data.html,
				al_etc: "",
				al_url: data.url
			};
			$exe.alarm.insertAlarm(alarm, function (idx) { });

			// 알림 브로드 캐스트
			$exe.alarm.getAlarmCount({ user_id: data.user_id, show_yn: "N" }, function (ret3) {
				//모두 리턴한다.
				var info = {
					count: ret3.data
				};
				socket.to(target_socket_id).emit('alarm_refresh', info);
			});
		});
	});

	//메시지 전송 : 등록
	socket.on('message_update_status', function (data) {
		var mg_idx = data.mg_idx;
		var rm_idx = data.rm_idx;
		var mg_imoti = data.mg_imoti;

		$exe.chat.updateMessageStatus(data, function (ret) { });
	});

	//메시지 전송 : 등록
	socket.on('message_insert', async function (data, rm_users) {
	//	$common.writeLog('[message_insert] 이벤트 수신 - user_id: ' + (data && data.user_id ? data.user_id : 'unknown') + ', socket_id: ' + socket.id + ', rm_idx: ' + (data && data.rm_idx ? data.rm_idx : 'unknown'));

		try {
			// MongoDB 연결 상태 확인
			if (!$db.isConnected()) {
				socket.emit('message_insert_return', {
					code: "90",
					msg: "데이터베이스 연결 오류",
					data: null
				});
				return;
			}

			// g_user_id: 메시지를 보내는 사용자 ID
			const g_user_id = data.user_id;
			// target_rm_idx: 메시지를 보낼 채팅방(rm_idx) 번호
			const target_rm_idx = data.rm_idx;
			// target_chat_type: 메시지의 채팅 타입 (ex: 일반, 이미지 등)
			const target_chat_type = data.chat_type;
			// target_mg_text: 메시지의 실제 텍스트 내용
			const target_mg_text = data.mg_text;

			//	$common.writeLog('[message_insert] 소켓에서 메시지 수신 - rm_idx: ' + target_rm_idx + ', user_id: ' + g_user_id + ', chat_type: ' + target_chat_type + ', mg_text: ' + target_mg_text);

			// 나간 채팅방인지 확인 (exit_yn='Y' 체크)
			const room = await $db.cs_room.findOne({ rm_idx: target_rm_idx });
			if (room && room.rm_users) {
				var user_exited = false;
				for (var icnt = 0; icnt < room.rm_users.length; icnt++) {
					if (room.rm_users[icnt].user_id == g_user_id) {
						// exit_yn='Y'이면 나간 채팅방
						if (room.rm_users[icnt].exit_yn && room.rm_users[icnt].exit_yn == "Y") {
							user_exited = true;
							break;
						}
					}
				}

				// 나간 채팅방이면 메시지 전송 차단
				if (user_exited) {
					socket.emit('message_insert_return', {
						code: "90",
						msg: "나간 채팅방입니다.",
						data: null
					});
					return;
				}
			}

			// ✅ 실제 채팅방에 접속한 사용자만 확인하여 mg_reads 설정
			// 클라이언트에서 보낸 mg_reads는 무시하고 항상 서버에서 실제 접속 사용자 확인
			// 방 정보 조회 (getRoom 대신 직접 DB에서 조회하여 rm_users 확실히 가져오기)
			const room_for_reads = await $db.cs_room.findOne({ rm_idx: target_rm_idx });

			// 사용자 목록: DB의 rm_users와 클라이언트에서 보낸 rm_users 병합 (클라이언트가 더 정확할 수 있음)
			let all_room_users = [];
			if (room_for_reads && room_for_reads.rm_users && room_for_reads.rm_users.length > 0) {
				all_room_users = [...room_for_reads.rm_users];
			}

			// 클라이언트에서 보낸 rm_users가 있으면 병합 (상대방이 아직 DB에 등록되지 않았을 수 있음)
			if (rm_users && Array.isArray(rm_users) && rm_users.length > 0) {
				rm_users.forEach(rm_user => {
					if (rm_user && rm_user.user_id) {
						// 중복 체크
						const exists = all_room_users.find(u => u.user_id === rm_user.user_id);
						if (!exists) {
							all_room_users.push({ user_id: rm_user.user_id });
						}
					}
				});
			}

			if (all_room_users.length > 0) {
				// Socket.IO를 통해 실제 접속한 소켓 ID 확인
				const roomSockets = io.sockets.adapter.rooms.get(String(target_rm_idx));
				const connectedUserIds = new Set();

				// 접속한 소켓의 사용자 ID 수집 (DB에서 socket_id로 조회)
				if (roomSockets && roomSockets.size > 0) {
					const socketIdArray = Array.from(roomSockets);
					for (let i = 0; i < socketIdArray.length; i++) {
						const socketId = socketIdArray[i];
						try {
							// DB에서 socket_id로 사용자 조회
							const user = await $db.cs_user.findOne({ socket_id: socketId });
							if (user && user.user_id) {
								connectedUserIds.add(user.user_id);
							}
						} catch (err) {
							// socket_id로 사용자 조회 실패 시 무시
						}
					}
				}

				// 보낸 사람은 항상 접속한 것으로 간주
				connectedUserIds.add(g_user_id);

				// mg_reads 생성: 실제 접속한 사용자만 read_yn='Y', 나머지는 'N'
				// 클라이언트에서 보낸 mg_reads는 무시하고 서버에서 새로 생성
				// all_room_users에는 DB의 rm_users와 클라이언트에서 보낸 rm_users가 모두 포함되어 있음
				data.mg_reads = [];
				for (var icnt = 0; icnt < all_room_users.length; icnt++) {
					const user_id = all_room_users[icnt].user_id;
					// 🔥 user_id가 null이거나 빈 문자열이면 건너뛰기 (readcount 오류 방지)
					if (!user_id || user_id === null || user_id === "" || user_id === "null") {
						continue;
					}

					// 회원 상태 조회 (탈퇴 여부 확인)
					let mm_status = 'Y';
					try {
						const member_info = await new Promise((resolve) => {
							$exe.member.getMember({ mm_id: user_id }, resolve);
						});
						if (!member_info || member_info.mm_status !== 'Y') {
							mm_status = 'N';
						}
					} catch (err) {
						// 회원 정보 조회 실패 시 'Y'로 설정
						mm_status = 'Y';
					}

					if (user_id === g_user_id) {
						// 보낸 사람은 항상 읽음
						data.mg_reads.push({ user_id: user_id, read_yn: 'Y', mm_status: mm_status });
					} else if (connectedUserIds.has(user_id)) {
						// 실제 접속한 사용자는 읽음
						data.mg_reads.push({ user_id: user_id, read_yn: 'Y', mm_status: mm_status });
					} else {
						// 접속하지 않은 사용자는 읽지 않음
						data.mg_reads.push({ user_id: user_id, read_yn: 'N', mm_status: mm_status });
					}
				}

				// 서버에서 설정한 mg_reads임을 표시
				data.server_set_reads = true;
			} else {
				// 방 정보가 없으면 보낸 사람만 읽음 처리
				data.mg_reads = [{ user_id: g_user_id, read_yn: 'Y' }];
				data.server_set_reads = true;
			}

			// ✅ DB의 rm_users 업데이트: 클라이언트에서 보낸 rm_users에 있는 사용자가 DB에 없으면 추가
			// ⚠️ 중요: insertMessage 호출 전에 rm_users를 먼저 업데이트해야 함
			// insertMessage 함수에서 room_info.rm_users를 조회하여 mg_reads를 생성하기 때문
			if (all_room_users.length > 0 && room_for_reads) {
				const db_rm_users = room_for_reads.rm_users || [];
				const db_user_ids = db_rm_users.map(u => u.user_id);
				const users_to_add = all_room_users.filter(u => !db_user_ids.includes(u.user_id));

				if (users_to_add.length > 0) {
					try {
						await $db.cs_room.updateOne(
							{ rm_idx: target_rm_idx },
							{ $push: { rm_users: { $each: users_to_add } } }
						);
						// rm_users 업데이트 후 room_for_reads도 갱신 (insertMessage에서 사용)
						room_for_reads.rm_users = [...db_rm_users, ...users_to_add];
					} catch (err) {
						// DB의 rm_users 업데이트 실패 시 무시
					}
				}
			}

			// 메시지 DB 저장
			const ret = await new Promise((resolve) => {
				$exe.chat.insertMessage(data, (res) => resolve(res));
			});

			// 모든 클라이언트에게 메시지 전송
			//$common.writeLog('[message_insert_return] emit 시작 - rm_idx: ' + target_rm_idx + ', code: ' + (ret && ret.code ? ret.code : 'unknown'));
			io.sockets.in(target_rm_idx).emit('message_insert_return', ret);
			//$common.writeLog('[message_insert_return] emit 완료');

			// ------------------------------  
			// ✅ 방 입장 여부 관계없이 푸시 발송되도록 개선
			// ------------------------------
			// ① DB에서 전체 사용자 목록 조회
			const db_room_users = await new Promise((resolve) => {
				$exe.chat.getRoomUsersFromDB({ rm_idx: target_rm_idx }, resolve);
			});

			// 방 정보 조회 (기존 로직 유지)
			const room_info = await new Promise((resolve) => {
				$exe.chat.getRoom({ rm_idx: target_rm_idx, con_user_id: g_user_id }, resolve);
			});

			const actual_room_info = room_info && room_info.data ? room_info.data : null;

			// 데이터 구조 정리 - 기존 방 정보 + 현재 접속 중인 사용자 모두 포함
			let all_users = [];

			// 1. 기존 방 정보 사용자 추가
			if (actual_room_info && actual_room_info.rm_users && actual_room_info.rm_users.length > 0) {
				all_users = [...actual_room_info.rm_users];
			}

			// 2. 현재 접속 중인 사용자 추가 (중복 제거)
			rm_users.forEach(rm_user => {
				if (rm_user && rm_user.user_id && !all_users.find(u => u.user_id === rm_user.user_id)) {
					all_users.push(rm_user);
				}
			});

			// 3. DB에서 조회한 사용자 추가 (중복 제거)
			if (db_room_users && Array.isArray(db_room_users) && db_room_users.length > 0) {
				db_room_users.forEach(db_user => {
					if (db_user && db_user.user_id && !all_users.find(u => u.user_id === db_user.user_id)) {
						all_users.push(db_user);
					}
				});
			}

			if (!all_users || all_users.length === 0) {
				// 현재 접속 중인 사용자들에게만 실시간 메시지 전송
				rm_users.forEach(user => {
					if (user && user.user_id && user.user_id !== "" && user.user_id !== g_user_id) {
						if (user.socket_id && user.socket_id !== "") {
							io.to(user.socket_id).emit('message_refresh', {
								rm_idx: target_rm_idx,
								mg_idx: ret.mg_idx
							});
						}
					}
				});
				return;
			}

			// 발신자 제외 및 실제 사용자만 필터링
			const target_users = all_users.filter(u => {
				// user_id가 있고, 발신자가 아니며, 실제 사용자 ID인 경우만
				// 실제 사용자 ID는 전화번호 형태(숫자로 시작)이거나 특정 패턴을 가짐
				const isRealUser = u.user_id &&
					u.user_id !== g_user_id &&
					!u.user_id.startsWith("user_") &&
					!u.user_id.startsWith("target_") &&
					!u.user_id.startsWith("test_") &&
					u.user_id !== "admin" &&
					u.user_id !== "system" &&
					u.user_id !== "";

				return isRealUser;
			});

			// ------------------------------
			// ② 발신자 이름 찾기
			// ------------------------------
			let sender_name = "사용자";
			try {
				const sender_info = await new Promise((resolve) => {
					$exe.user.getUser({ user_id: g_user_id }, resolve);
				});
				if (sender_info && sender_info.user_nick) {
					sender_name = sender_info.user_nick;
				}
			} catch (err) {
				// 발신자 이름 조회 실패 시 기본값 사용
			}

			// ------------------------------
			// ③ 접속 중인 사용자에게 실시간 알림 표시 및 알림 등록
			// ------------------------------
			const online_user_ids = rm_users
				.filter(u => u && u.user_id && u.user_id !== "")
				.map(u => u.user_id);

			// 접속 중인 사용자들에게 실시간 알림 표시 및 알림 등록
			//$common.writeLog('[message_insert] all_users 반복 시작 - 사용자 수: ' + all_users.length);
			for (const room_user of all_users) {
				const room_user_id = room_user.user_id;
				//$common.writeLog('[message_insert] all_users 루프 - room_user_id: ' + room_user_id + ', g_user_id: ' + g_user_id);
				if (room_user_id && room_user_id !== g_user_id) {
				   //	$common.writeLog('[message_insert] 회원 상태 조회 시작 - room_user_id: ' + room_user_id);
					// 탈퇴 회원 여부 확인 (MySQL에서 조회)
					try {
						const member_info = await new Promise((resolve) => {
							//$common.writeLog('[message_insert] MySQL st_member 조회 시작 - mm_id: ' + room_user_id);
							// MySQL st_member에서 mm_id로 회원 정보 조회
							$mysql.db_conn.query('SELECT mm_idx, mm_id, mm_status FROM st_member WHERE mm_id = ? LIMIT 1', [room_user_id], function(err, results) {
								if (err) {
									//	$common.writeLog('[message_insert] MySQL 조회 오류 - room_user_id: ' + room_user_id + ', error: ' + err.message);
									resolve(null);
								} else {
									const result = (results && results.length > 0) ? results[0] : null;
								//	$common.writeLog('[message_insert] MySQL 조회 완료 - result: ' + JSON.stringify(result));
									resolve(result);
								}
							});
						});
						//$common.writeLog('[message_insert] 회원 정보 조회 완료 - member_info: ' + JSON.stringify(member_info));
						// 탈퇴 회원(mm_status !== 'Y')이면 알림 전송 스킵
						if (!member_info || member_info.mm_status !== 'Y') {
						//	$common.writeLog('[message_insert] 탈퇴 회원이므로 스킵 - room_user_id: ' + room_user_id);
							continue;
						}
					} catch (err) {
						// 회원 정보 조회 실패 시 안전하게 스킵
						//$common.writeLog('[message_insert] 회원 정보 조회 예외 - room_user_id: ' + room_user_id + ', error: ' + err.message);
						continue;
					}

					// rm_users에서 먼저 찾기
				//	$common.writeLog('[message_insert] rm_users에서 socket_id 찾기 시작 - room_user_id: ' + room_user_id);
					let found_user = rm_users.find(u => u.user_id === room_user_id);
					let socket_id = found_user && found_user.socket_id ? found_user.socket_id : null;
				//	$common.writeLog('[message_insert] rm_users에서 socket_id 찾기 결과 - socket_id: ' + (socket_id ? socket_id : 'null'));
					let user_status = found_user && found_user.user_status ? found_user.user_status : "N";
					let last_location = found_user && found_user.last_location ? found_user.last_location : "";

					// socket_id가 없으면 MongoDB에서 조회
					if (!socket_id || socket_id === "") {
					//	$common.writeLog('[message_insert] MongoDB에서 socket_id 찾기 시작 - room_user_id: ' + room_user_id);
						try {
							const mongo_user = await new Promise((resolve) => {
								$exe.user.getUser({ user_id: room_user_id }, resolve);
							});
						//	$common.writeLog('[message_insert] MongoDB 사용자 조회 완료 - mongo_user: ' + (mongo_user ? 'found' : 'not found'));
							if (mongo_user) {
								if (!socket_id && mongo_user.socket_id) {
									socket_id = mongo_user.socket_id;
							//		$common.writeLog('[message_insert] MongoDB에서 socket_id 획득 - socket_id: ' + socket_id);
								}
								if (!last_location && mongo_user.last_location) {
									last_location = mongo_user.last_location;
								}
								if (user_status === "N" && mongo_user.user_status) {
									user_status = mongo_user.user_status;
								}
							}
						} catch (err) {
							// MongoDB 사용자 조회 실패 시 기본값 사용
							$common.writeLog('[message_insert] MongoDB 사용자 조회 오류 - room_user_id: ' + room_user_id + ', error: ' + err.message);
						}
					}

					// 접속 중인 사용자에게 실시간 알림 표시
					// socket_id가 있으면 채팅방 join 여부와 관계없이 발송
					// (기존: online_user_ids.includes(room_user_id) 조건으로 채팅방에 없는 사용자는 알림 미발송 문제 수정)
					//$common.writeLog('[message_insert] socket_id 최종 확인 - room_user_id: ' + room_user_id + ', socket_id: ' + (socket_id ? socket_id : 'null'));
					if (socket_id) {
						//$common.writeLog('[chat_notification] emit 시작 - socket_id: ' + socket_id + ', room_user_id: ' + room_user_id + ', rm_idx: ' + target_rm_idx);
						io.to(socket_id).emit('chat_notification', {
							rm_idx: target_rm_idx,
							mg_idx: ret.data && ret.data[0] ? ret.data[0].mg_idx : ret.mg_idx,
							sender_name: sender_name,
							message: target_mg_text,
							chat_type: target_chat_type
						});
						//$common.writeLog('[chat_notification] emit 완료');
					} else {
						//$common.writeLog('[message_insert] socket_id가 null이어서 chat_notification 발송 스킵 - room_user_id: ' + room_user_id);
					}

					// 채팅방에 있지 않은 경우 알림 등록 (last_location이 빈 문자열이거나 chat_room이 아닌 경우)
					if (!last_location || last_location === "" || last_location !== "chat_room") {
						// MongoDB 알림 등록
						const alarm = {
							user_id: room_user_id,
							al_type: target_chat_type,
							al_key: target_rm_idx,
							al_text: "[" + sender_name + "]님의 " + (target_chat_type === "recruit" ? "채용공고" : "중고거래") + " 메시지가 도착했습니다.",
							al_etc: "",
							al_url: "",
							show_yn: "N",
							reg_dt: $common.getCurrentDate()
						};

						$exe.alarm.insertAlarm(alarm, function (idx) {
							// 알림 카운트 조회 및 브로드캐스트
							$exe.alarm.getAlarmCount({ user_id: room_user_id, show_yn: "N" }, function (ret3) {
								const count = ret3.data || 0;

								if (socket_id) {
									// message_list_refresh 이벤트 발송 (채팅리스트 페이지에 있는 경우)
									if (last_location === "chat_list") {
									//	$common.writeLog('[message_list_refresh] emit 시작 - socket_id: ' + socket_id + ', room_user_id: ' + room_user_id);
										io.to(socket_id).emit('message_list_refresh', "refresh");
									//	$common.writeLog('[message_list_refresh] emit 완료');
									}

									// alarm_refresh 이벤트로 알림 카운트 전송
								//	$common.writeLog('[alarm_refresh] emit 시작 - socket_id: ' + socket_id + ', room_user_id: ' + room_user_id + ', count: ' + count);
									io.to(socket_id).emit('alarm_refresh', { count: count });
								//	$common.writeLog('[alarm_refresh] emit 완료');
								}
							});
						});
					}
				}
			}

			// ------------------------------
			// ④ 대상자별 푸시 전송 (채팅방 입장 여부와 관계없이)
			// ------------------------------
			if (target_users.length > 0) {
				// 각 사용자별로 FCM 토큰 조회 및 푸시 발송
				for (const target_user of target_users) {
					const target_user_id = target_user.user_id;

					// FCM 토큰 조회 (MongoDB → MySQL 순)
					let token = target_user.token || "";

					// MongoDB 토큰이 없으면 MySQL에서 조회
					if (!token || token === "") {
						token = await new Promise((resolve) => {
							$exe.user.getUserTokenFromMySQL(target_user_id, resolve);
						});
					}

					// FCM 토큰이 있으면 푸시 알림 발송
					if (token && token !== "" && token !== null) {
						// 채팅방 URL 생성 (푸시 알림 클릭 시 해당 채팅방으로 이동)
						const chat_url = "/chat/chat_view?rm_idx=" + target_rm_idx;

						const push = {
							user_id: target_user_id,
							no_subject: (target_chat_type === "recruit" ? "채용공고" : "중고거래") + " 메시지 알림",
							no_text: "[" + sender_name + "]님의 " + (target_chat_type === "recruit" ? "채용공고" : "중고거래") + " 메시지가 도착했습니다.",
							no_url: chat_url,
							no_token: token,
							no_result: "",
							reg_dt: $common.getCurrentDate(),
						};

						// DB에 푸시 내역 기록 (항상 실행)
						$exe.alarm.insertNotification(push, function (idx) {
							if (!idx || idx === "[object Object]") {
								return;
							}

							// PHP API 성공 여부 추적
							let phpApiSuccess = false;
							let notificationIdx = idx;

							// PHP API를 먼저 시도 (common_proc와 동일한 방식)
							try {
								const https_module = require('https');
								const postData = JSON.stringify({
									token: token,
									info: {
										title: push.no_subject,
										body: push.no_text,
										url: chat_url,
										type: "chat_message",
										user_id: target_user_id
									}
								});

								const options = {
									hostname: push_api_host,
									port: push_api_port,
									path: push_api_path,
									method: 'POST',
									headers: {
										'Content-Type': 'application/json',
										'Content-Length': Buffer.byteLength(postData),
										'User-Agent': 'Node.js Chat Server'
									},
									timeout: 3000 // 3초 타임아웃
								};

								const req = https_module.request(options, (res) => {
									let responseData = '';
									res.on('data', (chunk) => {
										responseData += chunk;
									});
									res.on('end', () => {
										try {
											const result = JSON.parse(responseData);
											if (result.code === "00") {
												phpApiSuccess = true;
												console.log('✅ PHP API 푸시 발송 성공');
												// PHP API 성공 시 결과 업데이트
												$exe.alarm.updateNotification(notificationIdx, { send_yn: "Y" }, function (ret) { });
											} else {
												// PHP API 실패 시 기존 방식으로 fallback
												fallbackToOriginalMethod();
											}
										} catch (e) {
											// 파싱 실패 시 기존 방식으로 fallback
											fallbackToOriginalMethod();
										}
									});
								});

								req.on('error', (error) => {
									// PHP API 호출 실패 시 기존 방식으로 fallback
									fallbackToOriginalMethod();
								});

								req.on('timeout', () => {
									req.destroy();
									// 타임아웃 시 기존 방식으로 fallback
									fallbackToOriginalMethod();
								});

								req.write(postData);
								req.end();

								// 3.5초 후에도 응답이 없으면 기존 방식으로 fallback
								setTimeout(() => {
									if (!phpApiSuccess) {
										fallbackToOriginalMethod();
									}
								}, 3500);

							} catch (e) {
								// PHP API 호출 실패 시 기존 방식으로 fallback
								fallbackToOriginalMethod();
							}

							// 기존 방식으로 fallback하는 함수
							function fallbackToOriginalMethod() {
								if (phpApiSuccess) {
									return; // 이미 PHP API가 성공했으면 기존 방식 호출 안 함
								}
								// 기존 방식으로 푸시 발송 (send_fcm이 updateNotification도 처리)
								send_fcm(notificationIdx, push);
							}
						});
					}
				}
			}

			// ------------------------------
			// ⑤ 접속 중인 사용자들에게 실시간 메시지 리프레시
			// ------------------------------
			rm_users.forEach(user => {
				if (user && user.user_id && user.user_id !== "" && user.user_id !== g_user_id) {
					if (user.socket_id && user.socket_id !== "") {
						io.to(user.socket_id).emit('message_refresh', {
							rm_idx: target_rm_idx,
							mg_idx: ret.mg_idx
						});
					}
				}
			});

		} catch (err) {
			// 메시지 전송 오류 처리
		}
	});

	//메시지 읽음 처리
	socket.on('message_read_return', function (data) {
		// 🔥 mg_idx가 없으면 처리하지 않음
		if (!data.mg_idx || data.mg_idx === null || data.mg_idx === undefined) {
			return;
		}

		$exe.chat.updateMessageReturnCount(data, function (ret) {
			//모두 리턴한다.
			//socket.to(data.rm_idx).emit('message_read_count_return', ret);
			//socket.to(data.rm_idx).emit('message_read_count_return', ret);
			// 기존 형식과 일관성을 위해 data 객체로 감싸서 전송
			// 메시지의 읽음(리드) 카운트가 변경되었을 때 해당 채팅방(rm_idx)의 모든 소켓(사용자)에게
			// 'message_read_count_return' 이벤트를 emit하여 읽음 수를 실시간으로 업데이트해주는 역할입니다.

			// 🔥 ret는 {code, msg, data: {count, mg_idx}} 형태로 반환됨
			var resultData = (ret && ret.data) ? ret.data : ret;
			var mg_idx = resultData.mg_idx;
			var count = resultData.count;

			// 🔥 mg_idx가 null이면 emit하지 않음
			if (mg_idx && mg_idx !== null && mg_idx !== undefined) {
				//$common.writeLog('[message_read_count_return] emit 시작 (message_read) - rm_idx: ' + data.rm_idx + ', mg_idx: ' + mg_idx + ', count: ' + count);
				io.sockets.in(data.rm_idx).emit('message_read_count_return', {
					data: {
						count: count,
						mg_idx: mg_idx
					}
				});
				//$common.writeLog('[message_read_count_return] emit 완료');
			}
		});
	});

	//메시지 리스트 조회
	socket.on('message_list', function (data) {
		$exe.chat.getRoomMessages(data, function (ret) {
			socket.emit('message_list_return', ret);
		});
	});

	//메시지 리스트 조회
	socket.on('room_list', function (data) {
		$exe.chat.getRoomList(data, function (ret) {
			socket.emit('room_list_return', ret);
		});
	});

	//메시지 리스트 조회2
	socket.on('room_list2', function (data) {
		$exe.chat.getRoomList(data, function (ret) {
			socket.emit('room_list_return2', ret);
		});
	});

	//미수신 메시지 조회
	socket.on('message_user_read_count', function (data) {
	//	$common.writeLog('[message_user_read_count] 이벤트 수신 - user_id: ' + data.user_id + ', socket_id: ' + socket.id);

		var socket_id = socket.id;
		$exe.chat.getUsewrNotReadCount({ user_id: data.user_id }, function (count_ret) {
			//모두 리턴한다.
			var info = {
				type: "chat",
				not_read: count_ret.data.count
			};

		//	$common.writeLog('[realtime_message] emit 시작 - user_id: ' + data.user_id + ', socket_id: ' + socket_id + ', count: ' + count_ret.data.count);
			io.sockets.in(socket_id).emit('realtime_message', info);
		//	$common.writeLog('[realtime_message] emit 완료');
		});
	});

	///////////////////////////////////////////////////////
	// 공통 처리
	///////////////////////////////////////////////////////

	// 로그아웃 상태 처리
	socket.on('buyguest_status', function () {
		//회원 접속정보 변경. : 접속 끝내기
		// MongoDB user_status를 'N'으로 업데이트하고, MySQL의 push_yn도 'N'으로 업데이트
		var params = { socket_id: socket.id, user_status: "N" };
		$exe.user.updateUserStatus(params, function (ret) { });
	});


	// 접속 종료 알림
	socket.on('disconnect', function () {
		//회원 접속정보 변경. : 접속 끝내기
		// MongoDB user_status를 'N'으로 업데이트하고, MySQL의 push_yn도 'N'으로 업데이트
		var params = { socket_id: socket.id, user_status: "N" };
		$exe.user.updateUserStatus(params, function (ret) { });

		//채팅방 종료알림
		socket.emit('room_leave_alarm');
	});
});
