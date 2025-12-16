const express = require("express");
const fs = require( 'fs' );
const app = express();
const bodyParser = require('body-parser');
//const https = require("http").createServer(app);
var options = {
    key: fs.readFileSync('/etc/pki/ssl/www.nowjob.co.kr_2023050820108.key.pem'),
    cert: fs.readFileSync('/etc/pki/ssl/www.nowjob.co.kr_2023050820108.crt.pem')
};
const https = require("https").createServer(options,app);
const io = require('socket.io')(https, { cors: { origin: "*" } });
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
const serviceAccount = require('./config/nowjob-558df-firebase-adminsdk-fmcnt-b15c34ec42.json');
const fcm_admin = admin.initializeApp({
    credential: admin.credential.cert(serviceAccount),
});

function send_fcm(idx,data){
	//토큰업으면 실행하지 않는다.
	if(data["no_token"]==""){
		return false;
	}

	//발송 조건 확인 : 푸시 수신여부 등확인. : 추가해야함.
	if(1==1){

	}

	const registrationToken = data["no_token"];
	const message = {
	    notification: {
	        title: data["no_subject"],
	        body: data["no_text"],
	    },
	    token: registrationToken
	};

	// Send a message to the device corresponding to the provided
	// registration token.
	fcm_admin.messaging().send(message)
    .then((response) => {
         $common.writeLog('푸시 메시지[성공]:::' + response);

         //푸시 성공처리
         $exe.alarm.updateNotification(idx,{send_yn : "Y"},function(ret){});
    })
    .catch((error) => {
         $common.writeLog('푸시 메시지[실패]:::' + error);

         //푸시 성공처리
         $exe.alarm.updateNotification(idx,{send_yn : "N", no_result : error},function(ret){});
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
const $config  = require('./config/config.json');

/////////////////////////////////////////////////////
// 변수 초기화
/////////////////////////////////////////////////////
const port = $config.server.port;
var room_list = new Array();

//접속허옹
app.use(cors());
app.use(bodyParser.json({limit: '50mb'})); //body 의 크기 설정
app.use(bodyParser.urlencoded({limit: '50mb', extended: true})); //url의 크기 설정
app.use(bodyParser.json());

//서버 시작
https.listen(port, () => {
	//서버 시작 로그
	$common.writeLog("http server start :: " + port + " start");

	//데이터베이스 초기화
	$db.init();

	//mysql 초기하
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
app.post('/room_list', function(req,res) {
    var data = req.body;
    $exe.chat.getRoomList(data,function(ret){
        res.send(ret);
    });
});



//접속 처리
io.on('connection', function (socket) {
	//컨넥트 로그
	$common.writeLog(socket.id + ' :: connected!!');

    //회원정보 접속처리

  	//연결테스트
  	socket.emit('connection_return', `${socket.id} 연결 되었습니다.`);

  	///////////////////////////////////////////////////////
  	// 유저관련 처리
  	///////////////////////////////////////////////////////


  	//유저 등록
  	socket.on('user_insert',function (data) {
        //소켓 아이디 추가
        data.socket_id = socket.id;

        //회원 등록
        $exe.user.insertUser(data,function(ret){
  			//회원추가 알림
			socket.broadcast.emit('user_insert_alarm', '신규회원가입 알림');

			//리턴
  			socket.emit('user_insert_return', ret);
  		});
  	});

    //유저 등록
  	socket.on('user_auto_insert',function (data) {
        //소켓 아이디 추가
        data.socket_id = socket.id;

        //회원 등록
        $exe.user.insertAutoUser(data,function(ret){
  			//회원추가 알림
			socket.broadcast.emit('user_insert_alarm', '접속 알림');

			//리턴
  			socket.emit('user_insert_return', ret);
  		});
  	});

  	//유저 로그인
  	socket.on('user_login',function (data) {
  		$exe.user.loginCheck(data,function(ret){
  			//로그인 문제 없으면 소켓 업데이트
			if(ret.code == "00"){

			}

			//회원추가 접속 알림
			socket.broadcast.emit('user_login_alarm', '로그인 알림');

			//리턴
  			socket.emit('user_login_return', ret);
  		});
  	});

  	//새로고침 회원 소켓 업데이트 : 접속중으로 처리
  	socket.on('update_status',function (data) {
  		//회원아이디랑 소켓아이디 저장.
  		var params = {user_id : data.user_id,socket_id : socket.id,last_location : data.last_location};
  		$exe.user.updateUserSocketId(params,function(ret){});
  	});

  	//친구 리스트 조회 : 현재는 모든 친구 검색하고 있음 : 수정해야함.
  	socket.on('user_list',function (data) {
  		$exe.user.getUserList(data,function(ret){
  			socket.emit('user_list_return', ret);
  		});
  	});

  	//팝업 친구 리스트 : 현재는 모든 친구 검색하고 있음 : 수정해야함.
  	socket.on('pop_user_list',function (data) {
  		$exe.user.getUserList(data,function(ret){
  			socket.emit('pop_user_list_return', ret);
  		});
  	});

  	///////////////////////////////////////////////////////
  	// 채팅관련 처리
  	///////////////////////////////////////////////////////

	// 룸 접속 처리
	socket.on('room_join',function (data) {
		socket.join(data.rm_idx);
		$exe.chat.updateEnterRoom(data,function(ret){
            //모두 리턴한다.
            socket.to(data.rm_idx).emit('room_join_return', ret);
  		});
  	});

	// 룸 해제 처리
	socket.on('room_leave',function (data) {
		socket.leave(data.rm_idx);
		socket.emit('room_leave_return', data.rm_idx);
  	});

  	//룸 생성하기
  	socket.on('room_insert',function (data) {
  		$exe.chat.insertRoom(data,function(ret){
  			socket.emit('room_insert_return', ret);
  		});
  	});

  	//룸 조회하기
  	socket.on('room_info',function (data) {
        //메시지 읽음 처리
        $exe.chat.updateMessageUserCount(data,function(ret22){
            $exe.chat.getRoom(data,function(ret){
    			//채팅방 접속
    			socket.room = data.rm_idx;
    			socket.join(data.rm_idx);

                //소켓 리턴
      			socket.emit('room_info_return', ret);
      		});

            //모두 리턴한다.
            socket.to(data.rm_idx).emit('room_join_alarm', data.con_user_id);
        });
  	});

    //알람 메시지 발송
  	socket.on('alarm_message',function (data) {
        $exe.user.getUser({user_id : data.user_id},function(user_info){
            var target_socket_id = user_info.socket_id;

            //푸시 발송
            var push = {
                user_id : data.user_id,
                no_subject : data.subject,
                no_text : data.text,
                no_url : data.url,
                no_token : user_info.token,
                no_result : "",
                reg_dt : $common.getCurrentDate()
            };
            $exe.alarm.insertNotification(push,function(idx){
                send_fcm(idx,push);
            });

            //알림 추가
            var alarm = {
                user_id : data.user_id,
                al_type : data.type,
                al_key : data.key,
                al_text : data.html,
                al_etc : "",
                al_url : data.url
            };
            $exe.alarm.insertAlarm(alarm,function(idx){});

            // 알림 브로드 캐스트
            $exe.alarm.getAlarmCount({user_id : data.user_id,show_yn : "N"},function(ret3){
                //모두 리턴한다.
                var info = {
                    count : ret3.data
                };
                socket.to(target_socket_id).emit('alarm_refresh', info);

                $common.writeLog('알람 수신[카운트]:::' + ret3.data);
            });
        });
  	});

  	//메시지 전송 : 등록
  	socket.on('message_insert',function (data,rm_users) {
  		var g_user_id = data.user_id;
  		var g_user_name = "";
  		var target_chat_type = data.chat_type;
        var target_mg_text = data.mg_text;
        var target_rm_idx = data.rm_idx;

  		$exe.chat.insertMessage(data,function(ret){
            //모두 리턴한다.
			io.sockets.in(data.rm_idx).emit('message_insert_return', ret);

            //비활성 회원은 접속 상태가 N이라면 푸시 발송 , Y 라면 알람 메시지 발송한다.toast처리
			var user_ids = [];
			for(var icnt=0;icnt < rm_users.length;icnt++){
				if(g_user_id!=rm_users[icnt].user_id){
					user_ids.push(rm_users[icnt].user_id);
				}else{
					g_user_name = rm_users[icnt].user_nick;
				}
			}

			$exe.user.getUserListV2({user_id : {$in : user_ids}},function(ret2){
	  			for(var icnt=0;icnt < ret2.length;icnt++){
                    var target_user_id = ret2[icnt].user_id;
                    var target_socket_id = ret2[icnt].socket_id;
                    var target_token = ret2[icnt].token;

					//접속중이면 toast 메시지 발송
					if(ret2[icnt].user_status=="N"){
                        //1시간 간격으로 발송한다. 1시간 지나지 않으면 발송하지 않는다.
                        $exe.alarm.checkAlarmHours({user_id : target_user_id},function(ret3){
                            if(ret3.data==0){
                                //푸시 발송
        						var push = {
        							user_id : target_user_id,
        							no_subject : (target_chat_type=="recruit" ? "채용공고":"중고거래") + " 메시지 알림",
        							no_text : "[" + g_user_name + "]님의 " + (target_chat_type=="recruit" ? "채용공고":"중고거래") + " 메시지가 도착했습니다.",
        							no_url : "",
        							no_token : target_token,
        							no_result : "",
        						    reg_dt : $common.getCurrentDate(),
        						};
        						$exe.alarm.insertNotification(push,function(idx){
        							send_fcm(idx,push);
        						});

        						$common.writeLog('채팅유형:::' + target_chat_type);

        						//알림 추가
        						var alarm = {
        							user_id : target_user_id,
        							al_type : target_chat_type,
                                    al_key : target_rm_idx,
        							al_text : "[" + g_user_name + "]님의 " + (target_chat_type=="recruit" ? "채용공고":"중고거래") + " 메시지가 도착했습니다.",
                                    al_etc : "",
        							al_url : "",
        						};
        						$exe.alarm.insertAlarm(alarm,function(idx){});
                            }
                        });
					}else{
						//채팅리스트 리프레시
						if(ret2[icnt].last_location=="chat_list"){
							socket.to(ret2[icnt].socket_id).emit('message_list_refresh', "refresh");
						}

						//미수신 알림 메시지 전송
						if(ret2[icnt].last_location!="chat_room"){
                            //알림 추가
                            var alarm = {
                                user_id : target_user_id,
                                al_type : target_chat_type,
                                al_key : target_rm_idx,
                                al_text : "[" + g_user_name + "]님의 " + (target_chat_type=="recruit" ? "채용공고":"중고거래") + " 메시지가 도착했습니다.",
                                al_etc : "",
                                al_url : "",
                            };
                            $exe.alarm.insertAlarm(alarm,function(idx){});

                            // 알림 브로드 캐스트
                            $exe.alarm.getAlarmCount({user_id : target_user_id,show_yn : "N"},function(ret3){
                                //모두 리턴한다.
    					        var info = {
        					        count : ret3.data
    					        };
    					        socket.to(target_socket_id).emit('alarm_refresh', info);

    					        $common.writeLog('알람 수신[카운트]:::' + ret3.data);
                            });
							//미수신 알람 전송
							//var con_socket_id = ret2[icnt].socket_id;
							//var con_user_id = ret2[icnt].user_id;
							//$exe.chat.getUsewrNotReadCount({user_id : con_user_id,socket_id : con_socket_id},function(count_ret){
					        //    //모두 리턴한다.
					        //    var info = {
					        //    	type : "chat",
					        //    	not_read : count_ret.data.count
					        //    };
					        //    socket.to(count_ret.data.socket_id).emit('realtime_message', info);
					  		//});
						}
					}
				}
	  		});
  		});
  	});

    //메시지 읽음 처리
    socket.on('message_read_return',function (data) {
  		$exe.chat.updateMessageReturnCount(data,function(ret){
            //모두 리턴한다.
            socket.to(data.rm_idx).emit('message_read_count_return', ret);
  		});
  	});

  	//메시지 리스트 조회
  	socket.on('message_list',function (data) {
  		$exe.chat.getRoomMessages(data,function(ret){
  			socket.emit('message_list_return', ret);
  		});
  	});

  	//메시지 리스트 조회
  	socket.on('room_list',function (data) {
  		$exe.chat.getRoomList(data,function(ret){
  			socket.emit('room_list_return', ret);
  		});
  	});

    //미수신 메시지 조회
    socket.on('message_user_read_count',function (data) {
    	var socket_id = socket.id;
  		$exe.chat.getUsewrNotReadCount({user_id : data.user_id},function(count_ret){
           //모두 리턴한다.
            var info = {
            	type : "chat",
            	not_read : count_ret.data.count
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
        var params = {socket_id : socket.id,user_status : "N"};
        $exe.user.updateUserStatus(params,function(ret){});
    });


	// 접속 종료 알림
	socket.on('disconnect', function () {
        //회원 접속정보 변경. : 접속 끝내기
        var params = {socket_id : socket.id,user_status : "N"};
        $exe.user.updateUserStatus(params,function(ret){});

		//채팅방 종료알림
		socket.emit('room_leave_alarm');
  	});
});
