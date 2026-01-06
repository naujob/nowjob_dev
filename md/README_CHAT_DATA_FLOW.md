# 채팅 시스템 데이터 흐름 상세 가이드

## 목차
1. [채팅 최초 입장](#1-채팅-최초-입장)
2. [메시지 최초 보내기](#2-메시지-최초-보내기)
3. [상대방 채팅방 입장](#3-상대방-채팅방-입장)
4. [상대방 메시지 보내기](#4-상대방-메시지-보내기)
5. [중요 변수 설명](#중요-변수-설명)

---

## 1. 채팅 최초 입장

### 시나리오
사용자 A가 채팅방에 처음 입장하는 경우

### 클라이언트 측 (chat_view.php)

#### 1-1. 페이지 로드 및 초기화
```javascript
// 위치: chat_view.php (안드로이드 섹션 시작 부분)
window.addEventListener('DOMContentLoaded', function(e) {
    g_user_id = ensureValidUserId();  // 사용자 ID 확인
    rm_info_ready = false;             // 채팅방 정보 준비 상태 플래그
    processedIdx = [];                 // 중복 메시지 방지 배열
    pendingReadCounts = {};           // 읽음 카운트 임시 저장 객체
```

**중요 변수:**
- `g_user_id`: 현재 사용자 ID (예: "kakao_4417753240")
- `rm_info_ready`: 채팅방 정보 준비 완료 여부 (boolean)
- `processedIdx`: 이미 표시한 메시지 ID 배열 (중복 방지)
- `pendingReadCounts`: 채팅방 입장 시 읽음 카운트 정보 임시 저장 객체

#### 1-2. 채팅방 정보 조회
```javascript
// 위치: chat_view.php (2315줄)
socket.emit("room_info", {
    rm_idx: Number(rm_idx),
    con_user_id: g_user_id
});
```

**전송 데이터:**
```javascript
{
    rm_idx: 950,                    // 채팅방 ID
    con_user_id: "kakao_4417753240" // 현재 사용자 ID
}
```

### 서버 측 (chat_server.js)

#### 1-3. room_info 이벤트 처리
```javascript
// 위치: chat_server.js (417줄)
socket.on('room_info', function (data) {
    // data = { rm_idx: 950, con_user_id: "kakao_4417753240" }
    
    // 1. 채팅방 정보 조회
    $exe.chat.getRoom(data, function (ret) {
        // ret = { code: "00", msg: "success", data: { rm_idx, rm_users, ... } }
        
        // 2. 채팅방이 없으면 생성
        if (!ret.data) {
            $exe.chat.insertRoom(room_data, function (insert_ret) {
                // 새 채팅방 생성 후 재조회
            });
        }
        
        // 3. 클라이언트에 채팅방 정보 전송
        socket.emit('room_info_return', ret);
    });
});
```

**중요 변수:**
- `data.rm_idx`: 채팅방 ID
- `data.con_user_id`: 현재 사용자 ID
- `ret.data`: 채팅방 정보 객체
  - `ret.data.rm_idx`: 채팅방 ID
  - `ret.data.rm_users`: 채팅방 사용자 배열
  - `ret.data.rm_messages`: 채팅방 메시지 배열

### 클라이언트 측 (chat_view.php)

#### 1-4. room_info_return 이벤트 수신
```javascript
// 위치: chat_view.php (2333줄)
socket.on("room_info_return", function(ret) {
    // ret = { code: "00", msg: "success", data: { rm_idx, rm_users, ... } }
    
    if (ret.code != "00") {
        // 에러 처리
        return;
    }
    
    // 1. 중복 메시지 방지 배열 초기화
    resetProcessedIdx();
    
    // 2. 채팅방 정보 저장
    rm_info = ret.data;
    rm_info_ready = true;  // 채팅방 정보 준비 완료
    
    // 3. 전송 버튼 활성화
    $("#btnSend").prop("disabled", false);
    
    // 4. 메시지 리스트 조회 요청
    var params = {
        rm_idx: Number(rm_idx),
        skip: rm_start,      // 0 (시작 위치)
        limit: rm_length     // 20 (가져올 개수)
    };
    socket.emit("message_list", params);
});
```

**중요 변수:**
- `rm_info`: 채팅방 정보 객체 (전역 변수)
- `rm_info_ready`: 채팅방 정보 준비 완료 플래그
- `rm_start`: 메시지 조회 시작 위치 (기본값: 0)
- `rm_length`: 한 번에 가져올 메시지 개수 (기본값: 20)

### 서버 측 (chat_server.js)

#### 1-5. message_list 이벤트 처리
```javascript
// 위치: chat_server.js (913줄)
socket.on('message_list', function (data) {
    // data = { rm_idx: 950, skip: 0, limit: 20 }
    
    $exe.chat.getRoomMessages(data, function (ret) {
        // ret = { code: "00", msg: "success", data: [ ... ] }
        // ret.data: 메시지 배열
        
        socket.emit('message_list_return', ret);
    });
});
```

**중요 변수:**
- `data.rm_idx`: 채팅방 ID
- `data.skip`: 건너뛸 메시지 개수
- `data.limit`: 가져올 메시지 개수
- `ret.data`: 메시지 배열
  - 각 메시지: `{ rm_messages: { mg_idx, user_id, mg_text, mg_reads, ... } }`

### 클라이언트 측 (chat_view.php)

#### 1-6. message_list_return 이벤트 수신
```javascript
// 위치: chat_view.php (2350줄)
socket.on("message_list_return", function(ret) {
    // ret = { code: "00", msg: "success", data: [ ... ] }
    
    // 1. 중복 방지 배열 초기화
    if (pagging_yn == false) {
        resetProcessedIdx();
    }
    
    // 2. 메시지 리스트 가져오기
    var message_list = ret.data;
    for (var icnt = 0; icnt < message_list.length; icnt++) {
        // 각 메시지를 화면에 표시
        showMessage(message_list[icnt].rm_messages, true);
    }
    
    // 3. 저장된 읽음 카운트 정보 업데이트 (room_join에서 저장한 것)
    if (Object.keys(pendingReadCounts).length > 0) {
        for (var mg_idx in pendingReadCounts) {
            var count = pendingReadCounts[mg_idx];
            updateReadCount(mg_idx, count);
        }
        pendingReadCounts = {};
    }
    
    // 4. 스크롤을 맨 아래로 이동
    if (pagging_yn == false) {
        scrollBottom();
    }
});
```

**중요 변수:**
- `message_list`: 메시지 배열
- `pendingReadCounts`: 채팅방 입장 시 저장한 읽음 카운트 정보
- `pagging_yn`: 페이지네이션 여부 (false: 초기 로드, true: 추가 로드)

---

## 2. 메시지 최초 보내기

### 시나리오
사용자 A가 채팅방에 입장한 후 첫 메시지를 보내는 경우

### 클라이언트 측 (chat_view.php)

#### 2-1. 메시지 전송 버튼 클릭
```javascript
// 위치: chat_view.php (sendResume 함수)
function sendResume() {
    var text = $("#chat_input").val().trim();
    if (!text) return;
    
    // 1. 채팅방 정보 준비 확인
    if (!rm_info_ready || !rm_info) {
        alert("채팅방 정보를 불러오는 중입니다.");
        return;
    }
    
    // 2. 임시 메시지 표시 (Optimistic Update)
    var temp_message = {
        mg_idx: "temp_" + Date.now(),
        user_id: g_user_id,
        mg_text: text,
        mg_dt: new Date(),
        temp_yn: true  // 임시 메시지 플래그
    };
    showMessage(temp_message, false);
    scrollBottom();
    
    // 3. 입력창 초기화
    $("#chat_input").val("");
    
    // 4. 메시지 객체 생성
    var message = {
        rm_idx: Number(rm_idx),
        user_id: g_user_id,
        chat_type: "recruit",  // 또는 "trade"
        mg_type: "text",
        mg_text: text,
        mg_reads: []  // 읽음 정보 배열 (서버에서 채워짐)
    };
    
    // 5. 서버로 메시지 전송
    socket.emit("message_insert", message, rm_info.rm_users);
}
```

**중요 변수:**
- `text`: 사용자가 입력한 메시지 텍스트
- `message`: 전송할 메시지 객체
  - `message.rm_idx`: 채팅방 ID
  - `message.user_id`: 보낸 사람 ID
  - `message.mg_text`: 메시지 내용
- `rm_info.rm_users`: 채팅방 사용자 배열

### 서버 측 (chat_server.js)

#### 2-2. message_insert 이벤트 처리
```javascript
// 위치: chat_server.js (583줄)
socket.on('message_insert', async function (data, rm_users) {
    // data = { rm_idx: 950, user_id: "kakao_4417753240", mg_text: "안녕하세요", ... }
    // rm_users = [{ user_id: "kakao_4417753240" }, { user_id: "01077417262" }]
    
    const g_user_id = data.user_id;        // "kakao_4417753240"
    const target_rm_idx = data.rm_idx;     // 950
    const target_mg_text = data.mg_text;   // "안녕하세요"
    
    // 1. 나간 채팅방인지 확인
    const room = await $db.cs_room.findOne({ rm_idx: target_rm_idx });
    if (room && room.rm_users) {
        // exit_yn='Y' 체크
        // 나간 채팅방이면 메시지 전송 차단
    }
    
    // 2. 메시지 DB 저장
    const ret = await new Promise((resolve) => {
        $exe.chat.insertMessage(data, (res) => resolve(res));
    });
    // ret = { code: "00", msg: "success", data: [{ rm_messages: { mg_idx, user_id, mg_text, mg_reads, ... } }] }
    
    // 3. 모든 클라이언트에게 메시지 전송
    io.sockets.in(target_rm_idx).emit('message_insert_return', ret);
    
    // 4. FCM 푸시 알림 발송 (상대방이 채팅방에 없을 때)
    // - MongoDB에서 상대방 FCM 토큰 조회
    // - 없으면 MySQL에서 조회
    // - 토큰이 있으면 FCM 푸시 발송
    
    // 5. 접속 중인 사용자들에게 실시간 알림
    rm_users.forEach(user => {
        if (user.user_id !== g_user_id) {
            // chat_notification, alarm_refresh 이벤트 전송
        }
    });
});
```

**중요 변수:**
- `data`: 메시지 데이터 객체
- `rm_users`: 채팅방 사용자 배열
- `ret`: 메시지 저장 결과
  - `ret.data`: 저장된 메시지 배열
  - `ret.data[0].rm_messages.mg_idx`: 생성된 메시지 ID
  - `ret.data[0].rm_messages.mg_reads`: 읽음 정보 배열

#### 2-3. insertMessage 함수 (chat_model.js)
```javascript
// 위치: chat_model.js (337줄)
container.insertMessage = async function (data, callback) {
    // 1. 채팅방 정보 조회
    var room_info = await $db.cs_room.findOne({ rm_idx: data.rm_idx });
    
    // 2. 사용자 정보 조회
    var user_info = await $db.cs_user.findOne({ user_id: data.user_id });
    
    // 3. 메시지 객체 생성
    var message = {
        mg_idx: await $db.getNextSequence('message'),  // 새 메시지 ID 생성
        user_id: data.user_id,
        user_nick: user_info.user_nick,
        mg_type: data.mg_type,
        mg_text: data.mg_text,
        mg_dt: new Date(),
        mg_reads: []  // 읽음 정보 배열
    };
    
    // 4. 읽음 정보 초기화 (채팅방의 모든 사용자에 대해)
    if (room_info.rm_users) {
        for (var i = 0; i < room_info.rm_users.length; i++) {
            message.mg_reads.push({
                user_id: room_info.rm_users[i].user_id,
                read_yn: (room_info.rm_users[i].user_id == data.user_id) ? "Y" : "N"
            });
        }
    }
    
    // 5. MongoDB에 메시지 저장
    await $db.cs_room.updateOne(
        { rm_idx: data.rm_idx },
        { $push: { rm_messages: message } }
    );
    
    // 6. 결과 반환
    callback({ code: "00", msg: "success", data: [{ rm_messages: message }] });
};
```

**중요 변수:**
- `message.mg_idx`: 생성된 메시지 ID (예: 4030)
- `message.mg_reads`: 읽음 정보 배열
  - 각 항목: `{ user_id: "...", read_yn: "Y" 또는 "N" }`
  - 보낸 사람: `read_yn: "Y"` (자동 읽음)
  - 상대방: `read_yn: "N"` (읽지 않음)

### 클라이언트 측 (chat_view.php)

#### 2-4. message_insert_return 이벤트 수신
```javascript
// 위치: chat_view.php (2405줄)
socket.on("message_insert_return", function(ret) {
    // ret = { code: "00", msg: "success", data: [{ rm_messages: { mg_idx, user_id, mg_text, ... } }] }
    
    if (ret.code != "00") {
        // 에러 처리
        $("[data-temp-message='true']").remove();  // 임시 메시지 제거
        return;
    }
    
    var message_list = ret.data || [];
    for (var icnt = 0; icnt < message_list.length; icnt++) {
        var received_message = message_list[icnt];
        var is_my_message = (received_message.user_id == g_user_id);
        
        if (is_my_message) {
            // 내가 보낸 메시지: 임시 메시지를 실제 메시지로 교체
            $("[data-temp-message='true']").last().remove();
            
            // 실제 메시지 표시
            if (!processedIdx.includes(received_message.mg_idx)) {
                showMessage(received_message, false);
                processedIdx.push(received_message.mg_idx);
            }
        } else {
            // 상대방이 보낸 메시지: 새로 표시
            if (!processedIdx.includes(received_message.mg_idx)) {
                showMessage(received_message, false);
                processedIdx.push(received_message.mg_idx);
                
                // 🔥 실시간 읽음 처리: 상대방이 보낸 메시지를 받았을 때 자동으로 읽음 처리
                var readParams = {
                    rm_idx: Number(rm_idx),
                    mg_idx: Number(received_message.mg_idx),
                    user_id: g_user_id
                };
                socket.emit("message_read_return", readParams);
            }
        }
    }
    
    scrollBottom();
});
```

**중요 변수:**
- `received_message`: 수신된 메시지 객체
- `is_my_message`: 내가 보낸 메시지 여부 (boolean)
- `readParams`: 읽음 처리 요청 파라미터

---

## 3. 상대방 채팅방 입장

### 시나리오
사용자 B가 채팅방에 입장하는 경우 (A가 이미 메시지를 보낸 상태)

### 클라이언트 측 (chat_view.php - 사용자 B)

#### 3-1. 채팅방 입장 및 room_join 이벤트 전송
```javascript
// 위치: chat_view.php (room_info_return 핸들러 내부)
socket.emit("room_join", {
    rm_idx: Number(rm_idx),
    con_user_id: g_user_id  // "01077417262"
});
```

**전송 데이터:**
```javascript
{
    rm_idx: 950,
    con_user_id: "01077417262"
}
```

### 서버 측 (chat_server.js)

#### 3-2. room_join 이벤트 처리
```javascript
// 위치: chat_server.js (376줄)
socket.on('room_join', function (data) {
    // data = { rm_idx: 950, con_user_id: "01077417262" }
    
    // 1. Socket.IO 룸에 조인
    socket.join(data.rm_idx);
    
    // 2. 채팅방 입장 처리 (모든 메시지 읽음 처리)
    $exe.chat.updateEnterRoom(data, function (ret) {
        // ret = { code: "00", msg: "처리성공", data: { list: [{ _id: 4030, count: 1 }] } }
        
        // 3. 읽음 처리 전 읽지 않은 메시지 리스트 추출
        var actualList = (ret && ret.data && ret.data.list) ? ret.data.list : [];
        // actualList = [{ _id: 4030, count: 1 }]  // 읽음 처리 전의 count
        
        // 4. 클라이언트에 room_join_return 전송
        var returnData = {
            code: "00",
            msg: "success",
            list: actualList
        };
        socket.emit('room_join_return', returnData);
        
        // 5. 채팅방의 모든 사용자에게 읽음 카운트 업데이트 전송
        if (actualList && actualList.length > 0) {
            for (var i = 0; i < actualList.length; i++) {
                var msgInfo = actualList[i];
                // 읽음 처리 후이므로 count는 0
                io.sockets.in(data.rm_idx).emit('message_read_count_return', {
                    data: {
                        count: 0,  // 읽음 처리 완료
                        mg_idx: msgInfo._id
                    }
                });
            }
        }
    });
});
```

**중요 변수:**
- `data.rm_idx`: 채팅방 ID
- `data.con_user_id`: 입장한 사용자 ID
- `actualList`: 읽음 처리 전 읽지 않은 메시지 리스트
  - 각 항목: `{ _id: mg_idx, count: 읽지 않은 개수 }`

#### 3-3. updateEnterRoom 함수 (chat_model.js)
```javascript
// 위치: chat_model.js (297줄)
container.updateEnterRoom = async function (data, callback) {
    // data = { rm_idx: 950, con_user_id: "01077417262" }
    
    // 1. 읽음 처리 전 읽지 않은 메시지 개수 집계
    var ret2 = await $db.cs_room.aggregate([
        { $match: { rm_idx: data.rm_idx } },
        { $unwind: '$rm_messages' },
        { $unwind: '$rm_messages.mg_reads' },
        { $match: { 'rm_messages.mg_reads.read_yn': "N" } },
        { $group: { _id: "$rm_messages.mg_idx", count: { $sum: 1 } } }
    ]).toArray();
    // ret2 = [{ _id: 4030, count: 1 }]  // mg_idx 4030에 대해 읽지 않은 사용자 1명
    
    // 2. 모든 메시지를 읽음 처리 (read_yn: "N" → "Y")
    var ret1 = await $db.cs_room.updateOne(
        { "rm_idx": data.rm_idx },
        { $set: { "rm_messages.$[].mg_reads.$[read].read_yn": "Y" } },
        { arrayFilters: [{ "read.user_id": data.con_user_id }] }
    );
    
    // 3. 결과 반환
    var result = {
        list: ret2  // 읽음 처리 전의 리스트
    };
    callback(result);
};
```

**중요 변수:**
- `ret2`: 읽음 처리 전 읽지 않은 메시지 리스트
- `ret1`: MongoDB 업데이트 결과
- `result.list`: 읽음 처리 전의 메시지 리스트 (count 포함)

### 클라이언트 측 (chat_view.php - 사용자 B)

#### 3-4. room_join_return 이벤트 수신
```javascript
// 위치: chat_view.php (2448줄)
socket.on("room_join_return", function(ret) {
    // ret = { code: "00", msg: "success", list: [{ _id: 4030, count: 1 }] }
    
    if (ret && ret.list && Array.isArray(ret.list)) {
        // 읽음 카운트 정보를 임시 저장 (메시지 로드 후 업데이트)
        pendingReadCounts = {};
        for (var i = 0; i < ret.list.length; i++) {
            var msgInfo = ret.list[i];
            var mg_idx = msgInfo._id;      // 4030
            var count = msgInfo.count || 0; // 1 (읽음 처리 전)
            
            pendingReadCounts[mg_idx] = count;
            
            // 메시지가 이미 DOM에 있으면 즉시 업데이트
            if (updateReadCount(mg_idx, count)) {
                // 업데이트 성공
            }
        }
    }
});
```

**중요 변수:**
- `pendingReadCounts`: 읽음 카운트 임시 저장 객체
  - 키: `mg_idx` (메시지 ID)
  - 값: `count` (읽지 않은 개수)
- 예: `{ 4030: 1 }` → 메시지 4030에 대해 읽지 않은 사용자 1명

### 클라이언트 측 (chat_view.php - 사용자 A)

#### 3-5. message_read_count_return 이벤트 수신 (사용자 A)
```javascript
// 위치: chat_view.php (2478줄)
socket.on("message_read_count_return", function(ret) {
    // ret = { data: { count: 0, mg_idx: 4030 } }
    
    var count = (ret.data && ret.data.count !== undefined) ? ret.data.count : 0;
    var mg_idx = (ret.data && ret.data.mg_idx !== undefined) ? ret.data.mg_idx : null;
    
    if (mg_idx) {
        // 특정 메시지의 읽음 카운트 업데이트
        var $msgElement = $("#mg_idx_" + mg_idx);
        if ($msgElement.length > 0) {
            if (count == 0) {
                $msgElement.find(".readcount").html("");  // 읽음 카운트 제거
            } else {
                $msgElement.find(".readcount").html(count);  // 읽음 카운트 표시
            }
        }
    }
});
```

**중요 변수:**
- `count`: 읽지 않은 사용자 개수 (0이면 모두 읽음)
- `mg_idx`: 메시지 ID
- `$msgElement`: 메시지 DOM 요소

---

## 4. 상대방 메시지 보내기

### 시나리오
사용자 B가 채팅방에 입장한 후 메시지를 보내는 경우

### 클라이언트 측 (chat_view.php - 사용자 B)

#### 4-1. 메시지 전송 (2번과 동일)
```javascript
// 위치: chat_view.php (sendResume 함수)
socket.emit("message_insert", message, rm_info.rm_users);
```

### 서버 측 (chat_server.js)

#### 4-2. message_insert 이벤트 처리 (2-2와 동일)
```javascript
// 메시지 DB 저장 및 모든 클라이언트에게 전송
io.sockets.in(target_rm_idx).emit('message_insert_return', ret);
```

### 클라이언트 측 (chat_view.php - 사용자 A)

#### 4-3. message_insert_return 이벤트 수신
```javascript
// 위치: chat_view.php (2405줄)
socket.on("message_insert_return", function(ret) {
    var message_list = ret.data || [];
    for (var icnt = 0; icnt < message_list.length; icnt++) {
        var received_message = message_list[icnt];
        var is_my_message = (received_message.user_id == g_user_id);
        
        if (is_my_message) {
            // 내가 보낸 메시지 처리
        } else {
            // 🔥 상대방이 보낸 메시지: 새로 표시
            if (!processedIdx.includes(received_message.mg_idx)) {
                showMessage(received_message, false);
                processedIdx.push(received_message.mg_idx);
                
                // 🔥 실시간 읽음 처리: 상대방이 보낸 메시지를 받았을 때 자동으로 읽음 처리
                var readParams = {
                    rm_idx: Number(rm_idx),
                    mg_idx: Number(received_message.mg_idx),
                    user_id: g_user_id
                };
                socket.emit("message_read_return", readParams);
            }
        }
    }
});
```

### 서버 측 (chat_server.js)

#### 4-4. message_read_return 이벤트 처리
```javascript
// 위치: chat_server.js (897줄)
socket.on('message_read_return', function (data) {
    // data = { rm_idx: 950, mg_idx: 4031, user_id: "kakao_4417753240" }
    
    // 1. 메시지 읽음 처리 (read_yn: "N" → "Y")
    $exe.chat.updateMessageReturnCount(data, function (ret) {
        // ret = { count: 0, mg_idx: 4031 }
        // count: 읽지 않은 사용자 개수 (읽음 처리 후)
        
        // 2. 채팅방의 모든 사용자에게 읽음 카운트 업데이트 전송
        io.sockets.in(data.rm_idx).emit('message_read_count_return', {
            data: {
                count: ret.count,  // 0 (모두 읽음)
                mg_idx: ret.mg_idx
            }
        });
    });
});
```

#### 4-5. updateMessageReturnCount 함수 (chat_model.js)
```javascript
// 위치: chat_model.js (179줄)
container.updateMessageReturnCount = async function (data, callback) {
    // data = { rm_idx: 950, mg_idx: 4031, user_id: "kakao_4417753240" }
    
    // 1. 특정 메시지의 읽음 상태 업데이트
    var ret1 = await $db.cs_room.updateOne(
        { "rm_idx": data.rm_idx },
        { $set: { "rm_messages.$[message].mg_reads.$[read].read_yn": "Y" } },
        { arrayFilters: [
            { "message.mg_idx": data.mg_idx },
            { "read.user_id": data.user_id }
        ]}
    );
    
    // 2. 읽음 처리 후 읽지 않은 사용자 개수 집계
    var ret2 = await $db.cs_room.aggregate([
        { $match: { rm_idx: data.rm_idx } },
        { $unwind: '$rm_messages' },
        { $match: { 'rm_messages.mg_idx': data.mg_idx } },
        { $unwind: '$rm_messages.mg_reads' },
        { $match: { 'rm_messages.mg_reads.read_yn': "N" } }
    ]).toArray();
    
    // 3. 결과 반환
    callback({
        count: ret2.length,  // 읽지 않은 사용자 개수
        mg_idx: data.mg_idx
    });
};
```

### 클라이언트 측 (chat_view.php - 사용자 B)

#### 4-6. message_read_count_return 이벤트 수신 (사용자 B)
```javascript
// 위치: chat_view.php (2478줄)
socket.on("message_read_count_return", function(ret) {
    // ret = { data: { count: 0, mg_idx: 4031 } }
    
    // 내가 보낸 메시지의 읽음 카운트 업데이트
    var count = ret.data.count;  // 0
    var mg_idx = ret.data.mg_idx; // 4031
    
    var $msgElement = $("#mg_idx_" + mg_idx);
    if ($msgElement.length > 0) {
        if (count == 0) {
            $msgElement.find(".readcount").html("");  // 읽음 카운트 제거
        }
    }
});
```

---

## 중요 변수 설명

### 클라이언트 측 변수 (chat_view.php)

#### 전역 변수
- `g_user_id` (string)
  - 현재 사용자 ID
  - 예: "kakao_4417753240", "01077417262"
  - 초기화: `ensureValidUserId()` 함수로 설정

- `rm_info` (object)
  - 채팅방 정보 객체
  - 구조: `{ rm_idx, rm_users, rm_messages, ... }`
  - 설정: `room_info_return` 이벤트에서 설정

- `rm_info_ready` (boolean)
  - 채팅방 정보 준비 완료 여부
  - `false`: 채팅방 정보 로딩 중
  - `true`: 채팅방 정보 준비 완료, 메시지 전송 가능

- `processedIdx` (array)
  - 이미 표시한 메시지 ID 배열
  - 중복 메시지 표시 방지용
  - 예: `[4030, 4031, 4032]`

- `pendingReadCounts` (object)
  - 채팅방 입장 시 읽음 카운트 정보 임시 저장
  - 키: `mg_idx` (메시지 ID)
  - 값: `count` (읽지 않은 개수)
  - 예: `{ 4030: 1, 4031: 0 }`

- `rm_idx` (number)
  - 현재 채팅방 ID
  - URL 파라미터에서 가져옴

- `rm_start` (number)
  - 메시지 조회 시작 위치
  - 기본값: 0

- `rm_length` (number)
  - 한 번에 가져올 메시지 개수
  - 기본값: 20

- `pagging_yn` (boolean)
  - 페이지네이션 여부
  - `false`: 초기 로드 또는 새 메시지
  - `true`: 추가 로드 (스크롤 위로)

#### 메시지 객체 구조
```javascript
{
    mg_idx: 4030,                    // 메시지 ID
    user_id: "kakao_4417753240",     // 보낸 사람 ID
    user_nick: "홍길동",              // 보낸 사람 닉네임
    mg_type: "text",                 // 메시지 타입
    mg_text: "안녕하세요",            // 메시지 내용
    mg_dt: "2025-12-30T15:14:27Z",  // 메시지 시간
    mg_reads: [                      // 읽음 정보 배열
        {
            user_id: "kakao_4417753240",
            read_yn: "Y"              // "Y": 읽음, "N": 읽지 않음
        },
        {
            user_id: "01077417262",
            read_yn: "N"
        }
    ],
    temp_yn: false                   // 임시 메시지 여부
}
```

### 서버 측 변수 (chat_server.js, chat_model.js)

#### Socket.IO 관련
- `socket.id` (string)
  - Socket.IO 연결 ID
  - 예: "8kFS_gnJGUQW91u8AAAd"

- `socket.handshake.address` (string)
  - 클라이언트 IP 주소
  - 예: "::ffff:112.170.223.212"

- `socket.handshake.headers['user-agent']` (string)
  - 클라이언트 User Agent
  - 예: "Mozilla/5.0 (Linux; Android 13; ...)"

#### 데이터베이스 관련
- MongoDB 컬렉션
  - `cs_room`: 채팅방 정보
  - `cs_user`: 사용자 정보

- MongoDB 문서 구조 (cs_room)
```javascript
{
    rm_idx: 950,
    rm_users: [
        {
            user_id: "kakao_4417753240",
            user_nick: "홍길동",
            exit_yn: "N"  // "Y": 나간 채팅방
        }
    ],
    rm_messages: [
        {
            mg_idx: 4030,
            user_id: "kakao_4417753240",
            mg_text: "안녕하세요",
            mg_reads: [
                {
                    user_id: "kakao_4417753240",
                    read_yn: "Y"
                },
                {
                    user_id: "01077417262",
                    read_yn: "N"
                }
            ]
        }
    ]
}
```

#### 이벤트 데이터 구조

**room_join 이벤트:**
```javascript
{
    rm_idx: 950,
    con_user_id: "01077417262"
}
```

**room_join_return 이벤트:**
```javascript
{
    code: "00",
    msg: "success",
    list: [
        { _id: 4030, count: 1 }  // 읽음 처리 전의 count
    ]
}
```

**message_insert 이벤트:**
```javascript
{
    rm_idx: 950,
    user_id: "kakao_4417753240",
    chat_type: "recruit",
    mg_type: "text",
    mg_text: "안녕하세요"
}
```

**message_insert_return 이벤트:**
```javascript
{
    code: "00",
    msg: "success",
    data: [
        {
            rm_messages: {
                mg_idx: 4030,
                user_id: "kakao_4417753240",
                mg_text: "안녕하세요",
                mg_reads: [...]
            }
        }
    ]
}
```

**message_read_return 이벤트:**
```javascript
{
    rm_idx: 950,
    mg_idx: 4030,
    user_id: "kakao_4417753240"
}
```

**message_read_count_return 이벤트:**
```javascript
{
    data: {
        count: 0,      // 읽지 않은 사용자 개수
        mg_idx: 4030   // 메시지 ID
    }
}
```

---

## 함수 호출 순서 요약

### 1. 채팅 최초 입장
```
클라이언트: socket.emit("room_info")
    ↓
서버: socket.on('room_info') → $exe.chat.getRoom()
    ↓
서버: socket.emit('room_info_return')
    ↓
클라이언트: socket.on("room_info_return") → socket.emit("message_list")
    ↓
서버: socket.on('message_list') → $exe.chat.getRoomMessages()
    ↓
서버: socket.emit('message_list_return')
    ↓
클라이언트: socket.on("message_list_return") → showMessage()
```

### 2. 메시지 최초 보내기
```
클라이언트: sendResume() → showMessage(temp_message) → socket.emit("message_insert")
    ↓
서버: socket.on('message_insert') → $exe.chat.insertMessage()
    ↓
서버: io.sockets.in(rm_idx).emit('message_insert_return')
    ↓
클라이언트: socket.on("message_insert_return") → showMessage(real_message)
```

### 3. 상대방 채팅방 입장
```
클라이언트 B: socket.emit("room_join")
    ↓
서버: socket.on('room_join') → socket.join(rm_idx) → $exe.chat.updateEnterRoom()
    ↓
서버: updateEnterRoom() → MongoDB 읽음 처리 → callback({ list: [...] })
    ↓
서버: socket.emit('room_join_return') → io.sockets.in(rm_idx).emit('message_read_count_return')
    ↓
클라이언트 B: socket.on("room_join_return") → pendingReadCounts 저장
    ↓
클라이언트 A: socket.on("message_read_count_return") → updateReadCount()
```

### 4. 상대방 메시지 보내기
```
클라이언트 B: socket.emit("message_insert")
    ↓
서버: socket.on('message_insert') → $exe.chat.insertMessage() → io.sockets.in(rm_idx).emit('message_insert_return')
    ↓
클라이언트 A: socket.on("message_insert_return") → showMessage() → socket.emit("message_read_return")
    ↓
서버: socket.on('message_read_return') → $exe.chat.updateMessageReturnCount() → io.sockets.in(rm_idx).emit('message_read_count_return')
    ↓
클라이언트 B: socket.on("message_read_count_return") → updateReadCount()
```

---

## 작성일
2025-12-30

## 작성자
AI Assistant (Auto)

