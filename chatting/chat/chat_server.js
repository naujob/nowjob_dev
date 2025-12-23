const express = require("express");
const fs = require('fs');
const app = express();
const bodyParser = require('body-parser');

//const https = require("http").createServer(app);
var options = {
	key: fs.readFileSync('/etc/pki/ssl/nowjob.co.kr_2025043053993.key.pem'),
	cert: fs.readFileSync('/etc/pki/ssl/nowjob.co.kr_2025043053993.crt.pem')

	//  key:  fs.readFileSync('/etc/pki/ssl/nowjob.co.kr_202405089ECF3.key.pem'),
	//  cert: fs.readFileSync('/etc/pki/ssl/nowjob.co.kr_202405089ECF3.crt.pem')
	//  key: fs.readFileSync('/etc/pki/ssl/www.nowjob.co.kr_2023050820108.key.pem'),
	//  cert: fs.readFileSync('/etc/pki/ssl/www.nowjob.co.kr_2023050820108.crt.pem')
};
const https = require("https").createServer(options, app);
const io = require('socket.io')(https, {
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
const serviceAccount = require('./config/nowjob-cb256-firebase-adminsdk-nh112-505a61fb39.json');
const fcm_admin = admin.initializeApp({
	credential: admin.credential.cert(serviceAccount),
	projectId: 'nowjob-cb256'
});


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

	// FCM 메시지 구성 (Firebase Admin SDK는 data 필드의 모든 값이 문자열이어야 함)
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
					badge: 1
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

/////////////////////////////////////////////////////
// 커스텀 모듈 로드
/////////////////////////////////////////////////////
const $common = require("./common/common.js");
const $db = require("./database/database.js");
const $mysql = require("./database/mysql.js");
const $model = require("./model/model.js");
const $exe = require("./controller/controller.js");
const $config = require('./config/config.json');

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
https.listen(port, () => {
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
		socket.join(data.rm_idx);
		$exe.chat.updateEnterRoom(data, function (ret) {
			//모두 리턴한다.
			//$common.writeLog('room_join_return::' + ret.data);
			//socket.to(data.rm_idx).emit('room_join_return', ret);
			socket.emit('room_join_return', ret);
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
		//메시지 읽음 처리
		$exe.chat.updateMessageUserCount(data, function (err, ret22) {
			$exe.chat.getRoom(data, function (ret) {
				// getRoom 결과 로그
				$common.writeLog('room_info:: getRoom 결과 - ret:' + JSON.stringify(ret) + ', ret.data 존재:' + (ret && ret.data ? 'Y' : 'N'));

				// 방 정보가 없으면 자동으로 생성
				if (!ret || !ret.data) {
					$common.writeLog('room_info:: 방 정보 없음 - 새 방 생성 시작 - rm_idx:' + data.rm_idx);
					// 새 방 정보 생성
					var room_data = {
						rm_idx: data.rm_idx,
						rc_idx: 0,
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

					$common.writeLog('room_info:: room_data 생성 완료 - user_list:' + JSON.stringify(room_data.user_list) + ', user_list 개수:' + (room_data.user_list ? room_data.user_list.length : 0));

					$exe.chat.insertRoom(room_data, function (insert_ret) {
						$common.writeLog('room_info:: insertRoom 호출 완료 - insert_ret:' + JSON.stringify(insert_ret));
						// 생성된 방 정보로 다시 조회
						$exe.chat.getRoom(data, function (new_ret) {
							$common.writeLog('room_info:: 생성 후 재조회 결과 - new_ret.data 존재:' + (new_ret && new_ret.data ? 'Y' : 'N') + ', rm_users 존재:' + (new_ret && new_ret.data && new_ret.data.rm_users ? 'Y' : 'N') + ', rm_users 개수:' + (new_ret && new_ret.data && new_ret.data.rm_users ? new_ret.data.rm_users.length : 0));

							//채팅방 접속
							socket.room = data.rm_idx;
							socket.join(data.rm_idx);

							//소켓 리턴
							socket.emit('room_info_return', new_ret);
						});
					});
				} else {
					//채팅방 접속
					socket.room = data.rm_idx;
					socket.join(data.rm_idx);

					//소켓 리턴
					socket.emit('room_info_return', ret);
				}
			});

			//모두 리턴한다.
			socket.to(data.rm_idx).emit('room_join_alarm', data.con_user_id);
		});
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

			const g_user_id = data.user_id;
			const target_rm_idx = data.rm_idx;
			const target_chat_type = data.chat_type;
			const target_mg_text = data.mg_text;

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

			// 메시지 DB 저장
			const ret = await new Promise((resolve) => {
				$exe.chat.insertMessage(data, (res) => resolve(res));
			});

			// 모든 클라이언트에게 메시지 전송
			io.sockets.in(target_rm_idx).emit('message_insert_return', ret);

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
			for (const room_user of all_users) {
				const room_user_id = room_user.user_id;
				if (room_user_id && room_user_id !== g_user_id) {
					// rm_users에서 먼저 찾기
					let found_user = rm_users.find(u => u.user_id === room_user_id);
					let socket_id = found_user && found_user.socket_id ? found_user.socket_id : null;
					let user_status = found_user && found_user.user_status ? found_user.user_status : "N";
					let last_location = found_user && found_user.last_location ? found_user.last_location : "";

					// socket_id가 없으면 MongoDB에서 조회
					if (!socket_id || socket_id === "") {
						try {
							const mongo_user = await new Promise((resolve) => {
								$exe.user.getUser({ user_id: room_user_id }, resolve);
							});
							if (mongo_user) {
								if (!socket_id && mongo_user.socket_id) {
									socket_id = mongo_user.socket_id;
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
						}
					}

					// 접속 중인 사용자에게 실시간 알림 표시
					if (socket_id && online_user_ids.includes(room_user_id)) {
						io.to(socket_id).emit('chat_notification', {
							rm_idx: target_rm_idx,
							mg_idx: ret.data && ret.data[0] ? ret.data[0].mg_idx : ret.mg_idx,
							sender_name: sender_name,
							message: target_mg_text,
							chat_type: target_chat_type
						});
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
										io.to(socket_id).emit('message_list_refresh', "refresh");
									}

									// alarm_refresh 이벤트로 알림 카운트 전송
									io.to(socket_id).emit('alarm_refresh', { count: count });
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


						// DB에 푸시 내역 기록 후 전송
						$exe.alarm.insertNotification(push, function (idx) {
							if (idx && idx !== "[object Object]") {
								send_fcm(idx, push);
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
		$exe.chat.updateMessageReturnCount(data, function (ret) {
			//모두 리턴한다.
			//socket.to(data.rm_idx).emit('message_read_count_return', ret);
			//socket.to(data.rm_idx).emit('message_read_count_return', ret);
			io.sockets.in(data.rm_idx).emit('message_read_count_return', ret);
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
		var socket_id = socket.id;
		$exe.chat.getUsewrNotReadCount({ user_id: data.user_id }, function (count_ret) {
			//모두 리턴한다.
			var info = {
				type: "chat",
				not_read: count_ret.data.count
			};

			io.sockets.in(socket_id).emit('realtime_message', info);
		});
	});

	///////////////////////////////////////////////////////
	// 공통 처리
	///////////////////////////////////////////////////////

	// 로그아웃 상태 처리
	socket.on('buyguest_status', function () {
		//회원 접속정보 변경. : 접속 끝내기
		var params = { socket_id: socket.id, user_status: "N" };
		$exe.user.updateUserStatus(params, function (ret) { });
	});


	// 접속 종료 알림
	socket.on('disconnect', function () {
		//회원 접속정보 변경. : 접속 끝내기
		var params = { socket_id: socket.id, user_status: "N" };
		$exe.user.updateUserStatus(params, function (ret) { });

		//채팅방 종료알림
		socket.emit('room_leave_alarm');
	});
});
