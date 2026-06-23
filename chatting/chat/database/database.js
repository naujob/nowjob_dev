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
const $common = require("../common/common.js");
const $config  = require('../config/config.js');

/////////////////////////////////////////////////////
// 변수 설정
/////////////////////////////////////////////////////
const db_client = require('mongodb').MongoClient;
const db_url = $config.mongodb.url + ':' + $config.mongodb.port;
// db_url mongodb://localhost:27017
container.db_conn = null;
container.db_name = $config.mongodb.database;

//테이블 리스트
container.cs_room = null;
container.cs_user = null;
container.cs_alarm = null;
container.cs_notification = null;
container.cs_table_key = null;

container.user_info = null;
container.room_info = null;

// MongoDB 재연결 시도 카운트
container.reconnect_attempts = 0;
container.max_reconnect_attempts = 10;
container.reconnect_interval = 5000; // 5초

/////////////////////////////////////////////////////
// 기능 정의
/////////////////////////////////////////////////////

// MongoDB 연결 (재시도 로직 포함)
container.connectMongoDB = function(callback) {
    $common.writeLog('MongoDB 연결 시도: ' + db_url + ' (시도: ' + (container.reconnect_attempts + 1) + '/' + container.max_reconnect_attempts + ')');

    db_client.connect(db_url, {
        useNewUrlParser: true,
        useUnifiedTopology: true,
        retryWrites: true,
        maxPoolSize: 10,
        serverSelectionTimeoutMS: 5000
    }, function (err, client) {
        if (err) {
            $common.writeLog('MongoDB 연결 오류: ' + err.message);

            // 재시도 횟수 증가
            container.reconnect_attempts++;

            // 최대 재시도 횟수 초과 확인
            if (container.reconnect_attempts >= container.max_reconnect_attempts) {
                $common.writeLog('MongoDB 최대 재시도 횟수 초과 (' + container.reconnect_attempts + '/' + container.max_reconnect_attempts + ')');
                if (callback) callback(err);
                return;
            }

            // 재시도 대기
            $common.writeLog('MongoDB ' + (container.reconnect_interval / 1000) + '초 후 재시도...');
            setTimeout(function() {
                container.connectMongoDB(callback);
            }, container.reconnect_interval);
            return;
        }

        // 연결 성공
        $common.writeLog('✓ MongoDB 연결 성공');
        container.db_conn = client.db(container.db_name);
        container.cs_room = container.db_conn.collection('room');
        container.cs_user = container.db_conn.collection('user');
        container.cs_alarm = container.db_conn.collection('alarm');
        container.cs_notification = container.db_conn.collection('notification');
        container.cs_table_key = container.db_conn.collection('table_key');

        // 재연결 시도 카운트 초기화
        container.reconnect_attempts = 0;

        $common.writeLog('=== MongoDB 컬렉션 초기화 완료 ===');
        $common.writeLog('데이터베이스: ' + container.db_name); // :데이터베이스: dev_chat
        $common.writeLog('컬렉션 - room: ' + (container.cs_room ? '✓' : '✗')); // ✓
        $common.writeLog('컬렉션 - user: ' + (container.cs_user ? '✓' : '✗'));// ✓
        $common.writeLog('컬렉션 - alarm: ' + (container.cs_alarm ? '✓' : '✗'));// ✓
        $common.writeLog('컬렉션 - notification: ' + (container.cs_notification ? '✓' : '✗'));// ✓
        $common.writeLog('컬렉션 - table_key: ' + (container.cs_table_key ? '✓' : '✗'));// ✓
        if (callback) callback(null);
    });
}

container.init = function(msg){
    $common.writeLog('=== 데이터베이스 초기화 시작 ===');
    $common.writeLog('MongoDB URL: ' + db_url); // MongoDB URL: mongodb://localhost:27017
    $common.writeLog('데이터베이스명: ' + container.db_name); // dev_chat
    $common.writeLog('최대 재시도 횟수: ' + container.max_reconnect_attempts); // 최대 재시도 횟수: 10
    $common.writeLog('재시도 간격: ' + container.reconnect_interval + 'ms'); // 재시도 간격: 5000ms
    container.reconnect_attempts = 0;
    container.connectMongoDB();
}

// MongoDB 연결 상태 확인 함수
container.isConnected = function() {
    return container.db_conn !== null;
}

// MongoDB 연결 대기 함수
container.waitForConnection = function(callback, maxAttempts = 50) {
  //  $common.writeLog('MongoDB 연결 대기 시작 (최대시도: ' + maxAttempts + '회)');
    let attempts = 0;
    const checkConnection = () => {
        if (container.isConnected()) {
           // $common.writeLog('✓ MongoDB 연결 확인됨 (' + attempts + '회 시도)');
            callback();
        } else if (attempts < maxAttempts) {
            attempts++;
            if (attempts % 5 === 0) {
              //  $common.writeLog('MongoDB 연결 대기 중... (' + attempts + '/' + maxAttempts + ')');
            }
            setTimeout(checkConnection, 100);
        } else {
           // $common.writeLog('✗ MongoDB 연결 타임아웃 (' + maxAttempts + '회 시도)');
            callback(new Error('MongoDB 연결 타임아웃'));
        }
    };
    checkConnection();
}

container.getNextSequence = async function(name,callback){
	//해당 키 카운트
	var count = await container.cs_table_key.count({_id: name});

	//카운트 여부에 따라 등록및 수정
	if (count > 0) {
        await container.cs_table_key.updateOne({_id: name},{$inc:{'seq':1}});
       // $common.writeLog('테이블키 업데이트: ' + name);
    }else{
        await container.cs_table_key.insertOne({_id:name, seq : 1});
       // $common.writeLog('테이블키 신규 등록: ' + name);
    }

    //갯수 리턴
    var ret = await container.cs_table_key.findOne({_id: name});
   // $common.writeLog('다음 시퀀스 - ' + name + ': ' + ret.seq);
    return ret.seq;
}


/////////////////////////////////////////////////////
// 객체 적용
/////////////////////////////////////////////////////
module.exports = container;
