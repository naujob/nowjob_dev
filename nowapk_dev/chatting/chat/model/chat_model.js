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

//방정보 등록
container.insertRoom = async function(data,callback){
	//기본키 가져오기
	//var room_idx = await $db.getNextSequence('room');
	//data.room_idx = room_idx;

	//정보 만들기
	var chat_room = {
		rm_idx : data.rm_idx,
		rc_idx : data.rc_idx,
		rm_type : data.rm_type,
		rm_img : data.rm_img,
	    rm_subject : data.rm_subject,
	    rm_desc : data.rm_desc,
	    rm_last_msg : "",
	    rm_last_dt : $common.getCurrentDate(),
	    rm_users : data.user_list,
	    user_info : data.user_list,
	    rm_status : "N",
	    rm_messages : []
	};

	//디비 등록
	var ret = await $db.cs_room.insertOne(chat_room);
	callback(data.rm_idx);
}

//만들어진방 있는지 확인
container.getUsersRoom = async function(params){
	var ret = await $db.cs_room.findOne(params);
    return ret;
}

//방정보 조회
container.getRoom = async function(params){
	var ret = await $db.cs_room.findOne({rm_idx : params.rm_idx},{projection:{rm_idx : 1,rm_type : 1,user_info : 1,rm_subject : 1,rm_last_msg : 1,rm_last_dt : 1,rm_users : 1}});
	if(ret!=null){
		//공유방인경우 회원등록되지 않았으면 회원등록한다.
		var chk_user = false;
		for(var icnt=0;icnt < ret.rm_users.length;icnt++){
			if(ret.rm_users[icnt].user_id==params.con_user_id){
				chk_user = true;
			}
		}
		if(chk_user==false){
			var user_confirm = await $db.cs_room.updateOne({rm_idx : ret.rm_idx},{$push : {rm_users : {"user_id" : params.con_user_id}}});
		}

		//회원정보 초기화
		for(var icnt=0;icnt < ret.rm_users.length;icnt++){
			var user_info = await $db.cs_user.findOne({user_id : ret.rm_users[icnt].user_id});
			if(user_info!=null){
				ret.rm_users[icnt].user_idx = user_info.user_idx;
				ret.rm_users[icnt].user_nick = user_info.user_nick;
				ret.rm_users[icnt].user_img = user_info.user_img;
				ret.rm_users[icnt].user_desc = user_info.user_desc;
				ret.rm_users[icnt].user_status = user_info.user_status;
			}else{
				ret.rm_users[icnt].user_idx = 0;
				ret.rm_users[icnt].user_nick = "조회안됨";
				ret.rm_users[icnt].user_img = "";
				ret.rm_users[icnt].user_desc = "";
				ret.rm_users[icnt].user_status = "N";
			}
		}

		//메시지 총 카운트
		var count_info = $db.cs_room.aggregate(
			[
				{$match : params},
				{$project: {count: {$size: '$rm_messages'}}}
			]);

		if(count_info!=null){
			ret.rm_count = count_info.count;
		}else{
			ret.rm_count = 0;
		}

	}
    return ret;
}

//방정보 리스트 조회
container.getRoomMessages = async function(params){
	var ret = await $db.cs_room.aggregate(
		[
			{$match : {rm_idx : params.rm_idx}},
			{$unwind:'$rm_messages'},
			{$lookup : {
			    "from": "user",
			    "as": "rm_messages.user_info",
			    "localField": "rm_messages.user_id",
			    "foreignField": "user_id"
			}},
			{$sort : {'rm_messages.mg_idx' : -1}},
			{$skip : params.skip},
			{$limit : params.limit},
			{$project : {rm_messages : '$rm_messages'}}
		]).toArray();
    return ret;
}

//마지막 메시지 조회
getLastMessage = async function(rm_idx){
	var ret = await $db.cs_room.aggregate(
	[
		{$match : {rm_idx : rm_idx}},
		{$unwind:'$rm_messages'},
		{$lookup: {
		    "from": "user",
		    "as": "rm_messages.user_info",
		    "localField": "rm_messages.user_id",
		    "foreignField": "user_id"
		}},
		{$sort : {'rm_messages.mg_idx' : -1}},
		{$limit : 1},
		{$project : {rm_messages : '$rm_messages'}}
	]).toArray();

	if(ret.length > 0){
		return ret[0];
	}else{
		return null;
	}
}

container.updateMessageReturnCount = async function(data,callback){
	$common.writeLog("rm_idx" + '::' + data.rm_idx + '::mg_idx:' + data.mg_idx + '::user_id:' + data.user_id);
	var ret1 = await $db.cs_room.updateOne(
		{"rm_idx" : data.rm_idx},{$set : {"rm_messages.$[message].mg_reads.$[read].read_yn" : "Y"}},
		{arrayFilters : [{"message.mg_idx" : data.mg_idx},{"read.user_id" : data.user_id}]}
	);

	//완료후 카운터 리턴
	var ret2 = await $db.cs_room.aggregate(
	[
		{$match : {rm_idx : data.rm_idx}},
		{$unwind:'$rm_messages'},
		{$match : {'rm_messages.mg_idx' : data.mg_idx}},
		{$project : {rm_messages : '$rm_messages'}},
        {$unwind:'$rm_messages.mg_reads'},
        {$match : {'rm_messages.mg_reads.read_yn' : "N"}},
        {$project : {mg_reads : '$rm_messages.mg_reads'}}
	]).toArray();

	var return_count = 0;
	if(ret2.length > 0){
		return_count = ret2.length;
	}else{
		return_count = 0;
	}

	callback({count : return_count,mg_idx : data.mg_idx});
}

container.updateMessageStatus = async function(data,callback){
	$common.writeLog("rm_idx" + '::' + data.rm_idx + '::mg_idx:' + data.mg_idx + '::mg_imoti:' + data.mg_imoti);
	var ret1 = await $db.cs_room.updateOne(
		{"rm_idx" : data.rm_idx},{$set : {"rm_messages.$[message].mg_imoti" : data.mg_imoti}},
		{arrayFilters : [{"message.mg_idx" : data.mg_idx}]}
	);

	callback({mg_idx : data.mg_idx});
}

container.updateMessageUserCount = async function(data,callback){
	$common.writeLog("update multiple : rm_idx" + '::' + data.rm_idx + '::user_id:' + data.con_user_id);
	var user_confirm = await $db.cs_room.updateMany(
		{rm_idx : data.rm_idx}
		,{$set : {"rm_messages.$[].mg_reads.$[read].read_yn" : "Y"}}
		,{arrayFilters : [{"read.user_id" : data.con_user_id}]}
	);
	callback({mg_idx : data.mg_idx});
}


container.getNotReadCount = async function(data,callback){
	//완료후 카운터 리턴
	var ret2 = await $db.cs_room.aggregate(
	[
		{$match : {rm_idx : data.rm_idx}},
		{$unwind:'$rm_messages'},
		{$project : {rm_messages : '$rm_messages'}},
        {$unwind:'$rm_messages.mg_reads'},
        {$match : {'rm_messages.mg_reads.read_yn' : "N",'rm_messages.mg_reads.user_id' : data.user_id}},
        {$project : {mg_reads : '$rm_messages.mg_reads'}},
		{$group: { _id: '',count:{$sum:1}}}
	]).toArray();

	var return_count = 0;
	if(ret2.length > 0){
		return_count = ret2[0]["count"];
	}else{
		return_count = 0;
	}

	callback({count : return_count,rm_idx : data.rm_idx});
}

container.getUsewrNotReadCount = async function(data,callback){
	//완료후 카운터 리턴
	var ret2 = await $db.cs_room.aggregate(
	[
		{$match : {"rm_users.user_id" : data.user_id}},
		{$unwind:'$rm_messages'},
		{$project : {rm_messages : '$rm_messages'}},
        {$unwind:'$rm_messages.mg_reads'},
        {$match : {'rm_messages.mg_reads.read_yn' : "N",'rm_messages.mg_reads.user_id' : data.user_id}},
        {$project : {mg_reads : '$rm_messages.mg_reads'}},
		{$group: { _id: '',count:{$sum:1}}}
	]).toArray();

	var return_count = 0;
	if(ret2.length > 0){
		return_count = ret2[0]["count"];
	}else{
		return_count = 0;
	}

	callback({count : return_count,user_id : data.user_id,socket_id : data.socket_id});
}


container.getNotReadCountList = async function(data){
	if(data.rm_idxs==[]){
		return [];
	}

	//완료후 카운터 리턴
	//,'rm_messages.mg_reads.user_id' : {"$ne" : data.user_id}
	var ret2 = await $db.cs_room.aggregate(
	[
	    {$match : {rm_idx : {$in : data.rm_idxs}}},
	    {$unwind:'$rm_messages'},
	    {$project : {rm_idx : '$rm_idx' ,rm_messages : '$rm_messages'}},
	    {$unwind:'$rm_messages.mg_reads'},
	    {$match : {'rm_messages.mg_reads.read_yn' : "N"}},
	    {$project : {rm_idx : '$rm_idx' ,mg_reads : '$rm_messages.mg_reads'}},
	    {$group: { _id: "$rm_idx" ,count:{$sum:1}, rc_idx:"$rc_idx"}}
	]).toArray();

	return ret2;
}

container.updateEnterRoom = async function(data,callback){

	//완료후 카운터 리턴
	var ret2 = await $db.cs_room.aggregate(
	[
		{$match : {rm_idx : data.rm_idx}},
	    {$unwind:'$rm_messages'},
	    {$project : {rm_messages : '$rm_messages'}},
	    {$unwind:'$rm_messages.mg_reads'},
	    {$match : {'rm_messages.mg_reads.read_yn' : "N"}},
	    {$project : {mg_idx : '$rm_messages.mg_idx',mg_reads : '$rm_messages.mg_reads'}},
	    {$group:{_id: "$mg_idx", count: {$sum:1}}},
	    {$sort : { _id : 1 } }
	]).toArray();

	var ret1 = await $db.cs_room.updateOne(
		{"rm_idx" : data.rm_idx},{$set : {"rm_messages.$[].mg_reads.$[read].read_yn" : "Y"}},
   		{arrayFilters : [{"read.user_id" : data.con_user_id}]}
	);

	var result = {};
	if(ret2.length > 0){
		result.list = ret2;
	}else{
		result.list = [];
	}

	callback(result);
}





//방정보 등록
container.insertMessage = async function(data,callback){
	try {
		$common.writeLog('insertMessage 함수 시작 - rm_idx: ' + data.rm_idx + ', user_id: ' + data.user_id);
		
		//변수 설정.
		var ret_array = [];
		var read_users = [];
		var date_line = "N";
		
		$common.writeLog('방 정보 조회 시작');
		var room_info = await $db.cs_room.findOne({rm_idx : data.rm_idx});
		$common.writeLog('방 정보 조회 결과: ' + (room_info ? '성공' : '실패'));
		
		if(!room_info) {
			$common.writeLog('방 정보가 없습니다 - rm_idx: ' + data.rm_idx);
			callback({code: "90", msg: "방 정보를 찾을 수 없습니다.", data: null});
			return;
		}
		
		$common.writeLog('사용자 정보 조회 시작');
		var user_info = await $db.cs_user.find({user_id : data.user_id}).toArray();	//회원은 리스트 객체로 넘긴다. : 데이터 타입 문제임
		$common.writeLog('사용자 정보 조회 결과: ' + (user_info ? '성공' : '실패'));
		
		$common.writeLog('마지막 메시지 조회 시작');
		var last_msg = await getLastMessage(data.rm_idx);
		$common.writeLog('마지막 메시지 조회 결과: ' + (last_msg ? '있음' : '없음'));

	if(last_msg==null){
		date_line = "Y";
	}else{
		var last_date = new Date(last_msg.rm_messages.mg_dt);
		var current_ymd = $common.getFormatDate(new Date(),"ymd");
		var last_ymd = $common.getFormatDate(last_date,"ymd");

		//날짜가 지난날이라면 날짜 라인 추가한다.
		if(last_ymd < current_ymd){
			date_line = "Y";
		}

		//아이콘 노출 여부 확인
		var current_hhmm = $common.getFormatDate(new Date(),"hh:mm");
		var last_hhmm = $common.getFormatDate(last_date,"hh:mm");
	}

	//메시지 읽은 리스트 만들기.
	for(var icnt=0;icnt < room_info.rm_users.length;icnt++){
		if(data.user_id == room_info.rm_users[icnt].user_id){
			read_users.push({user_id : room_info.rm_users[icnt].user_id,read_yn : 'Y'});
		}else{
			read_users.push({user_id : room_info.rm_users[icnt].user_id,read_yn : 'N'});
		}
	}

	if(date_line=="Y"){
		var mg_idx = await $db.getNextSequence('message');
		var message = {
			user_id : "",
			mg_idx : mg_idx,
			mg_type : "timeline",
		    mg_text : "",
			mg_dt : "",
			mg_req_dt : "",
		    mg_img : "",
		    mg_file : "",
		    mg_imoti : "",
		    mg_dt : $common.getCurrentDate(),
		    mg_reads : [],
		    user_info : {}
		};

		//디비 등록
		var ret = await $db.cs_room.updateOne({rm_idx : data.rm_idx},{$push : {rm_messages : message}});
		ret_array.push(message);
	}

	//정보 만들기
	var mg_idx = await $db.getNextSequence('message');
	var message = {
		user_id : data.user_id,
		mg_idx : mg_idx,
		mg_type : data.mg_type,
	    mg_text : data.mg_text,
		mg_req_dt : data.mg_req_dt,
	    mg_img : data.mg_img,
	    mg_file : data.mg_file,
	    mg_imoti : data.mg_imoti,
	    mg_dt : $common.getCurrentDate(),
	    mg_reads : read_users,
	    user_info : user_info
	};

	//디비 등록
	var ret = await $db.cs_room.updateOne({rm_idx : data.rm_idx},{$push : {rm_messages : message}});
	ret_array.push(message);

	//원본 데이터 업데이트
	var rm_last_msg = "";
	if(data.mg_type=="text"){
		rm_last_msg = data.mg_text;
	}else if(data.mg_type =="file"){
		rm_last_msg = "파일을 첨부 하였습니다.";
	}else if(data.mg_type =="img"){
		rm_last_msg = "이미지를 전송 하였습니다.";
	}else  if(data.mg_type =="면접일정확인"){
		rm_last_msg = "면접일 확정요쳥 하였습니다.";

	}else  if(data.mg_type =="개인면접취소요청"){
		rm_last_msg = "면접 취소요쳥 하였습니다.";

	}else  if(data.mg_type =="기업면접취소요청"){
		rm_last_msg = "면접 취소요쳥 하였습니다.";

	}else  if(data.mg_type =="개인면접변경요청"){
		rm_last_msg = "면접일 변경요청 하였습니다.";

	}else  if(data.mg_type =="개인면접변경수락"){
		rm_last_msg = "면접일 변경수락 하였습니다.";

	}else  if(data.mg_type =="개인면접수락"){
		rm_last_msg = "면접 수락 하였습니다.";

	}else  if(data.mg_type =="면접취소"){
		rm_last_msg = "면접 취소 하였습니다.";

	}else  if(data.mg_type =="채용일정확인"){
		rm_last_msg = "채용일 확정요쳥 하였습니다.";

	}else  if(data.mg_type =="개인채용취소요청"){
		rm_last_msg = "채용 취소요쳥 하였습니다.";

	}else  if(data.mg_type =="기업채용취소요청"){
		rm_last_msg = "채용 취소요쳥 하였습니다.";

	}else  if(data.mg_type =="개인채용변경요청"){
		rm_last_msg = "채용일 변경요청 하였습니다.";

	}else  if(data.mg_type =="개인채용변경수락"){
		rm_last_msg = "채용일 변경수락 하였습니다.";

	}else  if(data.mg_type =="개인채용수락"){
		rm_last_msg = "채용 수락 하였습니다.";

	}else  if(data.mg_type =="개인채용취소요청"){
		rm_last_msg = "채용 취소요쳥 하였습니다.";

	}else  if(data.mg_type =="채용취소"){
		rm_last_msg = "채용 취소 하였습니다.";

	}

	$common.writeLog('방 정보 업데이트 시작');
	var ret = await $db.cs_room.updateOne({rm_idx : data.rm_idx},{$set : {rm_last_dt : $common.getCurrentDate(),rm_last_msg : rm_last_msg}});
	$common.writeLog('방 정보 업데이트 완료');

	$common.writeLog('insertMessage 함수 완료 - 반환 데이터: ' + JSON.stringify(ret_array));
	callback(ret_array);
	
	} catch(error) {
		$common.writeLog('insertMessage 함수 에러: ' + error.message);
		$common.writeLog('insertMessage 함수 에러 스택: ' + error.stack);
		callback({code: "90", msg: "메시지 삽입 중 오류 발생: " + error.message, data: null});
	}
}

//방리스트 조회
container.getRoomList = async function(params){

	fs.appendFile('chat_log.txt', `${new Date().toISOString()}: model.chat getRoomList ${params}\n`, (err) => {});
	fs.appendFile('chat_log.txt', `${new Date().toISOString()}: model.chat getRoomList ${params.rm_type}\n`, (err) => {});
	fs.appendFile('chat_log.txt', `${new Date().toISOString()}: model.chat getRoomList ${params.user_id}\n`, (err) => {});


	var match = {};

	if(params.rm_type=="chat"){
		//match = {"rm_type" : params.rm_type,"rm_users.user_id" : params.user_id,'rm_last_msg': {"$ne" : ''}};
		match = {"rm_type" : params.rm_type,"rm_users.user_id" : params.user_id};
	}else if(params.rm_type=="channel"){
		match = {"rm_type" : params.rm_type};
	}else if(params.rm_type=="trade"){
		match = {"rm_type" : params.rm_type};
		match = {"rm_type" : params.rm_type,"rm_users.user_id" : params.user_id,'rm_last_msg': {"$ne" : ''}};
	}
	var ret = await $db.cs_room.aggregate(
	[
		{$match : match},
	    {
	        $lookup: {
	            from: "user",
	            localField: "rm_users.user_id",
	            foreignField: "user_id",
	            as: "rm_users",
	         },
	    },
	    { $sort : { "rm_last_dt" : -1 } },
	    {
	        $project: {
	            'rm_idx' : 1,
	            'rm_type' : 1,
	            'rm_subject' : 1,
	            'rm_img' : 1,
	            'user_info' : 1,
	            'rm_desc' : 1,
	            'rm_last_msg' : 1,
	            'rm_last_dt' : 1,
	            'rm_users.user_id' : 1,
	            'rm_users.user_nick' : 1,
	            'rm_users.user_img' : 1,
	            'rm_users.user_desc' : 1,
	        }
	    }
	]).toArray();

	var rm_idxs = [];
	for(var icnt=0;icnt < ret.length;icnt++){
		ret[icnt].not_read = 0;
		rm_idxs.push(ret[icnt].rm_idx);
	}

	//읽지 않은 수 가져오기
	var count_list = await container.getNotReadCountList({rm_idxs : rm_idxs,user_id : params.user_id});
	for(var jcnt = 0;jcnt < count_list.length;jcnt++){
		for(var icnt=0;icnt < ret.length;icnt++){
			if(ret[icnt].rm_idx == count_list[jcnt]._id){
				ret[icnt].not_read = count_list[jcnt].count;
			}
		}
	}

	fs.appendFile('chat_log.txt', `${new Date().toISOString()}: model.chat getRoomList end\n`, (err) => {});

    return ret;
}

/////////////////////////////////////////////////////
// 객체 적용
/////////////////////////////////////////////////////
module.exports = container;
