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
const $config  = require('../config/config.json');

/////////////////////////////////////////////////////
// 변수 설정
/////////////////////////////////////////////////////
const db_client = require('mongodb').MongoClient;
const db_url = $config.mongodb.url + ':' + $config.mongodb.port;

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

/////////////////////////////////////////////////////
// 기능 정의
/////////////////////////////////////////////////////
container.init = function(msg){
    $common.writeLog('MongoDB 연결 시작: ' + db_url);
    db_client.connect(db_url, { useNewUrlParser: true, useUnifiedTopology: true }, function (err, client) {
	   if (err) {
	      $common.writeLog('MongoDB 연결 오류: ' + err);
	      return;
	   }
	   $common.writeLog('MongoDB 연결 성공');
	   container.db_conn = client.db(container.db_name);
	   container.cs_room = container.db_conn.collection('room');
	   container.cs_user = container.db_conn.collection('user');
       container.cs_alarm = container.db_conn.collection('alarm');
       container.cs_notification = container.db_conn.collection('notification');
	   container.cs_table_key = container.db_conn.collection('table_key');
	   
	   $common.writeLog('MongoDB 컬렉션 초기화 완료');
	});
}

// MongoDB 연결 상태 확인 함수
container.isConnected = function() {
    return container.db_conn !== null;
}

// MongoDB 연결 대기 함수
container.waitForConnection = function(callback, maxAttempts = 50) {
    let attempts = 0;
    const checkConnection = () => {
        if (container.isConnected()) {
            $common.writeLog('MongoDB 연결 확인됨');
            callback();
        } else if (attempts < maxAttempts) {
            attempts++;
            $common.writeLog('MongoDB 연결 대기 중... (' + attempts + '/' + maxAttempts + ')');
            setTimeout(checkConnection, 100);
        } else {
            $common.writeLog('MongoDB 연결 타임아웃');
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
    }else{
        await container.cs_table_key.insertOne({_id:name, seq : 1});
    }

    //갯수 리턴
    var ret = await container.cs_table_key.findOne({_id: name});
    return ret.seq;
}


/////////////////////////////////////////////////////
// 객체 적용
/////////////////////////////////////////////////////
module.exports = container;
