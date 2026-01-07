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
container.insertRoom = async function (data, callback) {
	//기본키 가져오기
	//var room_idx = await $db.getNextSequence('room');
	//data.room_idx = room_idx;

	// user_list 또는 rm_users 확인 (하위 호환성)
	var user_list = data.user_list || data.rm_users || [];

	//정보 만들기
	var chat_room = {
		rm_idx: data.rm_idx,
		rc_idx: data.rc_idx || 0,
		ru_idx: data.ru_idx || null,
		rm_type: data.rm_type,
		rm_img: data.rm_img,
		rm_subject: data.rm_subject,
		rm_desc: data.rm_desc,
		rm_last_msg: "",
		rm_last_dt: $common.getCurrentDate(),
		rm_users: user_list,
		user_info: user_list,
		rm_status: "N",
		rm_messages: []
	};

	//디비 등록
	var ret = await $db.cs_room.insertOne(chat_room);
	callback(data.rm_idx);
}

//만들어진방 있는지 확인
container.getUsersRoom = async function (params) {
	var ret = await $db.cs_room.findOne(params);
	return ret;
}

//방정보 조회
container.getRoom = async function (params) {
	// 🔥 로깅: getRoom 시작
	const fs = require('fs');
	try {
		const logDir = '/website/nowapk/debug_log';
		if (!fs.existsSync(logDir)) {
			fs.mkdirSync(logDir, { recursive: true });
		}
		const logFile = `${logDir}/chat_debug_${new Date().toISOString().split('T')[0]}.log`;
		const logData = {
			type: "getRoom_start",
			rm_idx: params.rm_idx,
			con_user_id: params.con_user_id,
			timestamp: new Date().toISOString()
		};
		const logLine = `[${new Date().toISOString()}] ${JSON.stringify(logData)}\n`;
		fs.appendFileSync(logFile, logLine, 'utf8');
	} catch (err) {
		// 로깅 오류는 무시
	}

	var ret = await $db.cs_room.findOne({ rm_idx: params.rm_idx }, { projection: { rm_idx: 1, rm_type: 1, user_info: 1, rm_subject: 1, rm_last_msg: 1, rm_last_dt: 1, rm_users: 1 } });

	// 🔥 로깅: MongoDB 조회 결과
	try {
		const logDir = '/website/nowapk/debug_log';
		if (!fs.existsSync(logDir)) {
			fs.mkdirSync(logDir, { recursive: true });
		}
		const logFile = `${logDir}/chat_debug_${new Date().toISOString().split('T')[0]}.log`;
		const logData = {
			type: "getRoom_mongodb_result",
			rm_idx: params.rm_idx,
			con_user_id: params.con_user_id,
			room_exists: ret ? 'Y' : 'N',
			rm_users_count: ret && ret.rm_users ? ret.rm_users.length : 0,
			timestamp: new Date().toISOString()
		};
		const logLine = `[${new Date().toISOString()}] ${JSON.stringify(logData)}\n`;
		fs.appendFileSync(logFile, logLine, 'utf8');
	} catch (err) {
		// 로깅 오류는 무시
	}

	if (ret != null) {
		// 나간 채팅방인지 확인 (exit_yn='Y' 체크)
		var user_exited = false;
		var exit_yn_value = null;
		for (var icnt = 0; icnt < ret.rm_users.length; icnt++) {
			if (ret.rm_users[icnt].user_id == params.con_user_id) {
				// exit_yn='Y'이면 나간 채팅방
				if (ret.rm_users[icnt].exit_yn && ret.rm_users[icnt].exit_yn == "Y") {
					user_exited = true;
					exit_yn_value = "Y";
					break;
				} else {
					exit_yn_value = ret.rm_users[icnt].exit_yn || "N";
				}
			}
		}

		// 나간 채팅방이면 null 반환 (접근 차단)
		if (user_exited) {
			// 🔥 로깅: exit_yn='Y'로 인한 null 반환
			try {
				const logDir = '/website/nowapk/debug_log';
				if (!fs.existsSync(logDir)) {
					fs.mkdirSync(logDir, { recursive: true });
				}
				const logFile = `${logDir}/chat_debug_${new Date().toISOString().split('T')[0]}.log`;
				const logData = {
					type: "getRoom_return_null_exit_yn",
					rm_idx: params.rm_idx,
					con_user_id: params.con_user_id,
					exit_yn: exit_yn_value,
					reason: "사용자가 나간 채팅방 (exit_yn='Y')",
					timestamp: new Date().toISOString()
				};
				const logLine = `[${new Date().toISOString()}] ${JSON.stringify(logData)}\n`;
				fs.appendFileSync(logFile, logLine, 'utf8');
			} catch (err) {
				// 로깅 오류는 무시
			}
			return null;
		}

		//공유방인경우 회원등록되지 않았으면 회원등록한다.
		var chk_user = false;
		for (var icnt = 0; icnt < ret.rm_users.length; icnt++) {
			if (ret.rm_users[icnt].user_id == params.con_user_id) {
				chk_user = true;
			}
		}
		if (chk_user == false) {
			var user_confirm = await $db.cs_room.updateOne({ rm_idx: ret.rm_idx }, { $push: { rm_users: { "user_id": params.con_user_id } } });
		}

		//회원정보 초기화
		for (var icnt = 0; icnt < ret.rm_users.length; icnt++) {
			var user_info = await $db.cs_user.findOne({ user_id: ret.rm_users[icnt].user_id });
			if (user_info != null) {
				ret.rm_users[icnt].user_idx = user_info.user_idx;
				ret.rm_users[icnt].user_nick = user_info.user_nick;
				ret.rm_users[icnt].user_img = user_info.user_img;
				ret.rm_users[icnt].user_desc = user_info.user_desc;
				ret.rm_users[icnt].user_status = user_info.user_status;
			} else {
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
				{ $match: params },
				{ $project: { count: { $size: '$rm_messages' } } }
			]);

		if (count_info != null) {
			ret.rm_count = count_info.count;
		} else {
			ret.rm_count = 0;
		}

	} else {
		// 🔥 로깅: MongoDB에 방이 없음
		try {
			const logDir = '/website/nowapk/debug_log';
			if (!fs.existsSync(logDir)) {
				fs.mkdirSync(logDir, { recursive: true });
			}
			const logFile = `${logDir}/chat_debug_${new Date().toISOString().split('T')[0]}.log`;
			const logData = {
				type: "getRoom_return_null_no_room",
				rm_idx: params.rm_idx,
				con_user_id: params.con_user_id,
				reason: "MongoDB에 방이 존재하지 않음",
				timestamp: new Date().toISOString()
			};
			const logLine = `[${new Date().toISOString()}] ${JSON.stringify(logData)}\n`;
			fs.appendFileSync(logFile, logLine, 'utf8');
		} catch (err) {
			// 로깅 오류는 무시
		}
	}
	return ret;
}

//방정보 리스트 조회
container.getRoomMessages = async function (params) {
	var ret = await $db.cs_room.aggregate(
		[
			{ $match: { rm_idx: params.rm_idx } },
			{ $unwind: '$rm_messages' },
			{
				$lookup: {
					"from": "user",
					"as": "rm_messages.user_info",
					"localField": "rm_messages.user_id",
					"foreignField": "user_id"
				}
			},
			{ $sort: { 'rm_messages.mg_idx': -1 } },
			{ $skip: params.skip },
			{ $limit: params.limit },
			{ $project: { rm_messages: '$rm_messages' } }
		]).toArray();

	// 🔥 로깅: 메시지 조회 결과
	const fs = require('fs');
	try {
		const logDir = '/website/nowapk/debug_log';
		if (!fs.existsSync(logDir)) {
			fs.mkdirSync(logDir, { recursive: true });
		}
		const logFile = `${logDir}/chat_debug_${new Date().toISOString().split('T')[0]}.log`;
		const logData = {
			type: "getRoomMessages",
			rm_idx: params.rm_idx,
			skip: params.skip,
			limit: params.limit,
			message_count: ret.length,
			messages: ret.map(item => ({
				mg_idx: item.rm_messages ? item.rm_messages.mg_idx : null,
				user_id: item.rm_messages ? item.rm_messages.user_id : null,
				mg_text: item.rm_messages ? item.rm_messages.mg_text : null,
				mg_type: item.rm_messages ? item.rm_messages.mg_type : null,
				mg_reads: item.rm_messages ? item.rm_messages.mg_reads : null
			})),
			timestamp: new Date().toISOString()
		};
		const logLine = `[${new Date().toISOString()}] ${JSON.stringify(logData)}\n`;
		fs.appendFileSync(logFile, logLine, 'utf8');
	} catch (err) {
		// 로깅 오류는 무시
	}

	return ret;
}

//마지막 메시지 조회
getLastMessage = async function (rm_idx) {
	var ret = await $db.cs_room.aggregate(
		[
			{ $match: { rm_idx: rm_idx } },
			{ $unwind: '$rm_messages' },
			{
				$lookup: {
					"from": "user",
					"as": "rm_messages.user_info",
					"localField": "rm_messages.user_id",
					"foreignField": "user_id"
				}
			},
			{ $sort: { 'rm_messages.mg_idx': -1 } },
			{ $limit: 1 },
			{ $project: { rm_messages: '$rm_messages' } }
		]).toArray();

	if (ret.length > 0) {
		return ret[0];
	} else {
		return null;
	}
}

container.updateMessageReturnCount = async function (data, callback) {
	var ret1 = await $db.cs_room.updateOne(
		{ "rm_idx": data.rm_idx }, { $set: { "rm_messages.$[message].mg_reads.$[read].read_yn": "Y" } },
		{ arrayFilters: [{ "message.mg_idx": data.mg_idx }, { "read.user_id": data.user_id }] }
	);

	//완료후 카운터 리턴
	var ret2 = await $db.cs_room.aggregate(
		[
			{ $match: { rm_idx: data.rm_idx } },
			{ $unwind: '$rm_messages' },
			{ $match: { 'rm_messages.mg_idx': data.mg_idx } },
			{ $project: { rm_messages: '$rm_messages' } },
			{ $unwind: '$rm_messages.mg_reads' },
			{ $match: { 'rm_messages.mg_reads.read_yn': "N" } },
			{ $project: { mg_reads: '$rm_messages.mg_reads' } }
		]).toArray();

	var return_count = 0;
	if (ret2.length > 0) {
		return_count = ret2.length;
	} else {
		return_count = 0;
	}

	callback({ count: return_count, mg_idx: data.mg_idx });
}

container.updateMessageStatus = async function (data, callback) {
	var ret1 = await $db.cs_room.updateOne(
		{ "rm_idx": data.rm_idx }, { $set: { "rm_messages.$[message].mg_imoti": data.mg_imoti } },
		{ arrayFilters: [{ "message.mg_idx": data.mg_idx }] }
	);

	callback({ mg_idx: data.mg_idx });
}

container.updateMessageUserCount = async function (data, callback) {
	var user_confirm = await $db.cs_room.updateMany(
		{ rm_idx: data.rm_idx }
		, { $set: { "rm_messages.$[].mg_reads.$[read].read_yn": "Y" } }
		, { arrayFilters: [{ "read.user_id": data.con_user_id }] }
	);
	callback({ mg_idx: data.mg_idx });
}


container.getNotReadCount = async function (data, callback) {
	//완료후 카운터 리턴
	var ret2 = await $db.cs_room.aggregate(
		[
			{ $match: { rm_idx: data.rm_idx } },
			{ $unwind: '$rm_messages' },
			{ $project: { rm_messages: '$rm_messages' } },
			{ $unwind: '$rm_messages.mg_reads' },
			{ $match: { 'rm_messages.mg_reads.read_yn': "N", 'rm_messages.mg_reads.user_id': data.user_id } },
			{ $project: { mg_reads: '$rm_messages.mg_reads' } },
			{ $group: { _id: '', count: { $sum: 1 } } }
		]).toArray();

	var return_count = 0;
	if (ret2.length > 0) {
		return_count = ret2[0]["count"];
	} else {
		return_count = 0;
	}

	callback({ count: return_count, rm_idx: data.rm_idx });
}

container.getUsewrNotReadCount = async function (data, callback) {
	//완료후 카운터 리턴
	var ret2 = await $db.cs_room.aggregate(
		[
			{ $match: { "rm_users.user_id": data.user_id } },
			{ $unwind: '$rm_messages' },
			{ $project: { rm_messages: '$rm_messages' } },
			{ $unwind: '$rm_messages.mg_reads' },
			{ $match: { 'rm_messages.mg_reads.read_yn': "N", 'rm_messages.mg_reads.user_id': data.user_id } },
			{ $project: { mg_reads: '$rm_messages.mg_reads' } },
			{ $group: { _id: '', count: { $sum: 1 } } }
		]).toArray();

	var return_count = 0;
	if (ret2.length > 0) {
		return_count = ret2[0]["count"];
	} else {
		return_count = 0;
	}

	callback({ count: return_count, user_id: data.user_id, socket_id: data.socket_id });
}


container.getNotReadCountList = async function (data) {
	if (data.rm_idxs == []) {
		return [];
	}

	//완료후 카운터 리턴
	//,'rm_messages.mg_reads.user_id' : {"$ne" : data.user_id}
	var ret2 = await $db.cs_room.aggregate(
		[
			{ $match: { rm_idx: { $in: data.rm_idxs } } },
			{ $unwind: '$rm_messages' },
			{ $project: { rm_idx: '$rm_idx', rm_messages: '$rm_messages' } },
			{ $unwind: '$rm_messages.mg_reads' },
			{ $match: { 'rm_messages.mg_reads.read_yn': "N" } },
			{ $project: { rm_idx: '$rm_idx', mg_reads: '$rm_messages.mg_reads' } },
			{ $group: { _id: "$rm_idx", count: { $sum: 1 }, rc_idx: "$rc_idx" } }
		]).toArray();

	return ret2;
}

container.updateEnterRoom = async function (data, callback) {

	//완료후 카운터 리턴
	// 채팅방에 입장할 때 읽지 않은 메시지(안 읽은 메시지) 개수를 집계하기 위해 aggregate를 사용하여 집계 쿼리를 실행한다.
	var ret2 = await $db.cs_room.aggregate(
		[
			{ $match: { rm_idx: data.rm_idx } },
			// '$rm_messages'는 컬럼(필드, MongoDB document의 배열 필드)입니다.
			{ $unwind: '$rm_messages' },
			// 네, 두 개 다 컬럼(필드)입니다.
			{ $project: { rm_messages: '$rm_messages' } },
			{ $unwind: '$rm_messages.mg_reads' },
			{ $match: { 'rm_messages.mg_reads.read_yn': "N" } },
			{ $project: { mg_idx: '$rm_messages.mg_idx', mg_reads: '$rm_messages.mg_reads' } },
			{ $group: { _id: "$mg_idx", count: { $sum: 1 } } },
			{ $sort: { _id: 1 } }
		]).toArray();

	var ret1 = await $db.cs_room.updateOne(
		{ "rm_idx": data.rm_idx }, { $set: { "rm_messages.$[].mg_reads.$[read].read_yn": "Y" } },
		{ arrayFilters: [{ "read.user_id": data.con_user_id }] }
	);

	var result = {};
	if (ret2.length > 0) {
		result.list = ret2;
	} else {
		result.list = [];
	}

	callback(result);
}





//방정보 등록
container.insertMessage = async function (data, callback) {
	try {
		//변수 설정.
		var ret_array = [];
		var read_users = [];
		var date_line = "N";

		var room_info = await $db.cs_room.findOne({ rm_idx: data.rm_idx });

		if (!room_info) {
			callback({ code: "90", msg: "방 정보를 찾을 수 없습니다.", data: null });
			return;
		}

		var user_info = await $db.cs_user.find({ user_id: data.user_id }).toArray();	//회원은 리스트 객체로 넘긴다. : 데이터 타입 문제임

		var last_msg = await getLastMessage(data.rm_idx);

		if (last_msg == null) {
			date_line = "Y";
		} else {
			var last_date = new Date(last_msg.rm_messages.mg_dt);
			var current_ymd = $common.getFormatDate(new Date(), "ymd");
			var last_ymd = $common.getFormatDate(last_date, "ymd");

			//날짜가 지난날이라면 날짜 라인 추가한다.
			if (last_ymd < current_ymd) {
				date_line = "Y";
			}

			//아이콘 노출 여부 확인
			var current_hhmm = $common.getFormatDate(new Date(), "hh:mm");
			var last_hhmm = $common.getFormatDate(last_date, "hh:mm");
		}

		//메시지 읽은 리스트 만들기.
		// ✅ 서버에서 설정한 mg_reads가 있으면 우선 사용 (실제 접속 사용자 확인 완료)
		// 서버에서 mg_reads를 설정했다면 server_set_reads 플래그가 true로 설정됨
		if (data.server_set_reads === true && data.mg_reads && Array.isArray(data.mg_reads) && data.mg_reads.length > 0) {
			read_users = data.mg_reads;
		} else if (data.mg_reads && Array.isArray(data.mg_reads) && data.mg_reads.length > 0) {
			// 클라이언트에서 보낸 mg_reads (레거시 지원, 채팅방 생성 직후 첫 메시지 처리용)
			// ⚠️ 중요: 제안하기/지원하기 직후에는 클라이언트에서 보낸 mg_reads를 우선 사용
			// MongoDB의 rm_users에 상대방이 아직 없을 수 있기 때문
			read_users = data.mg_reads;
		} else if (room_info.rm_users && Array.isArray(room_info.rm_users) && room_info.rm_users.length > 0) {
			// room_info.rm_users에서 읽음 상태 생성
			// ⚠️ 주의: 제안하기/지원하기 직후에는 rm_users에 상대방이 없을 수 있음
			for (var icnt = 0; icnt < room_info.rm_users.length; icnt++) {
				if (data.user_id == room_info.rm_users[icnt].user_id) {
					read_users.push({ user_id: room_info.rm_users[icnt].user_id, read_yn: 'Y' });
				} else {
					read_users.push({ user_id: room_info.rm_users[icnt].user_id, read_yn: 'N' });
				}
			}
		} else {
			// rm_users가 없으면 현재 사용자만 읽음 처리
			read_users.push({ user_id: data.user_id, read_yn: 'Y' });
		}

		// 🔥 로깅: read_users 생성 후
		const fs = require('fs');
		try {
			const logDir = '/website/nowapk/debug_log';
			if (!fs.existsSync(logDir)) {
				fs.mkdirSync(logDir, { recursive: true });
			}
			const logFile = `${logDir}/chat_debug_${new Date().toISOString().split('T')[0]}.log`;
			const logData = {
				type: "insertMessage_read_users",
				rm_idx: data.rm_idx,
				user_id: data.user_id,
				mg_text: data.mg_text,
				server_set_reads: data.server_set_reads,
				data_mg_reads: data.mg_reads,
				room_info_rm_users: room_info.rm_users,
				final_read_users: read_users,
				timestamp: new Date().toISOString()
			};
			const logLine = `[${new Date().toISOString()}] ${JSON.stringify(logData)}\n`;
			fs.appendFileSync(logFile, logLine, 'utf8');
		} catch (err) {
			// 로깅 오류는 무시
		}

		if (date_line == "Y") {
			var mg_idx = await $db.getNextSequence('message');
			var message = {
				user_id: "",
				mg_idx: mg_idx,
				mg_type: "timeline",
				mg_text: "",
				mg_dt: "",
				mg_req_dt: "",
				mg_img: "",
				mg_file: "",
				mg_imoti: "",
				mg_dt: $common.getCurrentDate(),
				mg_reads: [],
				user_info: {}
			};

			//디비 등록
			var ret = await $db.cs_room.updateOne({ rm_idx: data.rm_idx }, { $push: { rm_messages: message } });
			ret_array.push(message);
		}

		//정보 만들기
		var mg_idx = await $db.getNextSequence('message');
		var message = {
			user_id: data.user_id,
			mg_idx: mg_idx,
			mg_type: data.mg_type,
			mg_text: data.mg_text,
			mg_req_dt: data.mg_req_dt,
			mg_img: data.mg_img,
			mg_file: data.mg_file,
			mg_imoti: data.mg_imoti,
			mg_dt: $common.getCurrentDate(),
			mg_reads: read_users,
			user_info: user_info
		};

		//디비 등록
		var ret = await $db.cs_room.updateOne({ rm_idx: data.rm_idx }, { $push: { rm_messages: message } });
		ret_array.push(message);

		//원본 데이터 업데이트
		var rm_last_msg = "";
		if (data.mg_type == "text") {
			rm_last_msg = data.mg_text;
		} else if (data.mg_type == "file") {
			rm_last_msg = "파일을 첨부 하였습니다.";
		} else if (data.mg_type == "img") {
			rm_last_msg = "이미지를 전송 하였습니다.";
		} else if (data.mg_type == "면접일정확인") {
			rm_last_msg = "면접일 확정요쳥 하였습니다.";

		} else if (data.mg_type == "개인면접취소요청") {
			rm_last_msg = "면접 취소요쳥 하였습니다.";

		} else if (data.mg_type == "기업면접취소요청") {
			rm_last_msg = "면접 취소요쳥 하였습니다.";

		} else if (data.mg_type == "개인면접변경요청") {
			rm_last_msg = "면접일 변경요청 하였습니다.";

		} else if (data.mg_type == "개인면접변경수락") {
			rm_last_msg = "면접일 변경수락 하였습니다.";

		} else if (data.mg_type == "개인면접수락") {
			rm_last_msg = "면접 수락 하였습니다.";

		} else if (data.mg_type == "면접취소") {
			rm_last_msg = "면접 취소 하였습니다.";

		} else if (data.mg_type == "채용일정확인") {
			rm_last_msg = "채용일 확정요쳥 하였습니다.";

		} else if (data.mg_type == "개인채용취소요청") {
			rm_last_msg = "채용 취소요쳥 하였습니다.";

		} else if (data.mg_type == "기업채용취소요청") {
			rm_last_msg = "채용 취소요쳥 하였습니다.";

		} else if (data.mg_type == "개인채용변경요청") {
			rm_last_msg = "채용일 변경요청 하였습니다.";

		} else if (data.mg_type == "개인채용변경수락") {
			rm_last_msg = "채용일 변경수락 하였습니다.";

		} else if (data.mg_type == "개인채용수락") {
			rm_last_msg = "채용 수락 하였습니다.";

		} else if (data.mg_type == "개인채용취소요청") {
			rm_last_msg = "채용 취소요쳥 하였습니다.";

		} else if (data.mg_type == "채용취소") {
			rm_last_msg = "채용 취소 하였습니다.";

		}

		var ret = await $db.cs_room.updateOne({ rm_idx: data.rm_idx }, { $set: { rm_last_dt: $common.getCurrentDate(), rm_last_msg: rm_last_msg } });

		callback(ret_array);

	} catch (error) {
		callback({ code: "90", msg: "메시지 삽입 중 오류 발생: " + error.message, data: null });
	}
}

//방리스트 조회
container.getRoomList = async function (params) {

	fs.appendFile('chat_log.txt', `${new Date().toISOString()}: model.chat getRoomList ${params}\n`, (err) => { });
	fs.appendFile('chat_log.txt', `${new Date().toISOString()}: model.chat getRoomList ${params.rm_type}\n`, (err) => { });
	fs.appendFile('chat_log.txt', `${new Date().toISOString()}: model.chat getRoomList ${params.user_id}\n`, (err) => { });


	var match = {};

	if (params.rm_type == "chat") {
		//match = {"rm_type" : params.rm_type,"rm_users.user_id" : params.user_id,'rm_last_msg': {"$ne" : ''}};
		match = { "rm_type": params.rm_type, "rm_users.user_id": params.user_id };
	} else if (params.rm_type == "channel") {
		match = { "rm_type": params.rm_type };
	} else if (params.rm_type == "trade") {
		match = { "rm_type": params.rm_type };
		match = { "rm_type": params.rm_type, "rm_users.user_id": params.user_id, 'rm_last_msg': { "$ne": '' } };
	}
	var ret = await $db.cs_room.aggregate(
		[
			{ $match: match },
			{
				$lookup: {
					from: "user",
					localField: "rm_users.user_id",
					foreignField: "user_id",
					as: "rm_users",
				},
			},
			{ $sort: { "rm_last_dt": -1 } },
			{
				$project: {
					'rm_idx': 1,
					'rm_type': 1,
					'rm_subject': 1,
					'rm_img': 1,
					'user_info': 1,
					'rm_desc': 1,
					'rm_last_msg': 1,
					'rm_last_dt': 1,
					'rm_users.user_id': 1,
					'rm_users.user_nick': 1,
					'rm_users.user_img': 1,
					'rm_users.user_desc': 1,
				}
			}
		]).toArray();

	var rm_idxs = [];
	for (var icnt = 0; icnt < ret.length; icnt++) {
		ret[icnt].not_read = 0;
		rm_idxs.push(ret[icnt].rm_idx);
	}

	//읽지 않은 수 가져오기
	var count_list = await container.getNotReadCountList({ rm_idxs: rm_idxs, user_id: params.user_id });
	for (var jcnt = 0; jcnt < count_list.length; jcnt++) {
		for (var icnt = 0; icnt < ret.length; icnt++) {
			if (ret[icnt].rm_idx == count_list[jcnt]._id) {
				ret[icnt].not_read = count_list[jcnt].count;
			}
		}
	}

	fs.appendFile('chat_log.txt', `${new Date().toISOString()}: model.chat getRoomList end\n`, (err) => { });

	return ret;
}

/////////////////////////////////////////////////////
// 객체 적용
/////////////////////////////////////////////////////
module.exports = container;
