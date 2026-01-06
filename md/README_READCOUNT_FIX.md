# 채팅 읽음 카운트 실시간 업데이트 문제 해결 보고서

## 문제 요약

**증상**: 
- 상대방이 채팅방에 없을 때 메시지를 보낸 후, 상대방이 채팅방에 입장해도 보낸 사람의 readcount가 실시간으로 업데이트되지 않음
- 상대방이 채팅방에 입장 후 메시지를 보내야만 readcount가 업데이트됨
- 채팅방을 나갔다가 다시 들어와야 readcount가 업데이트됨

**목표**: 
- 상대방이 채팅방에 입장하기만 해도 보낸 사람의 readcount가 즉시 0으로 업데이트되어야 함

---

## 문제 원인 분석

### 1. 서버 측 문제

#### 문제 1-1: `room_join` 시 읽음 카운트 전송 오류
- **위치**: `chatting/chat/chat_server.js` (room_join 핸들러)
- **원인**: 
  - `updateEnterRoom` 함수가 `{ code, msg, data: { list: [...] } }` 형식으로 반환
  - 코드에서 `ret.list`를 확인했지만 실제로는 `ret.data.list`에 데이터가 있음
  - 읽음 처리 전의 count를 그대로 전송하여 읽음 처리 후에도 count가 1로 표시됨

#### 문제 1-2: 읽음 처리 후 count 값 오류
- **위치**: `chatting/chat/chat_server.js` (room_join 핸들러)
- **원인**: 
  - `ret.list`에는 읽음 처리 **전**의 count 값이 포함됨 (예: count: 1)
  - 읽음 처리가 완료된 후이므로 count는 0이어야 하는데, 기존 count 값을 그대로 전송

### 2. 클라이언트 측 문제

#### 문제 2-1: `room_join_return` 핸들러에서 읽음 카운트 초기화
- **위치**: `application/shop/views/mobile/chat/chat_view.php`
- **원인**: 
  - `room_join_return` 이벤트 핸들러에서 `$(".readcount").html("");`로 모든 읽음 카운트를 초기화
  - 서버에서 보낸 실시간 읽음 카운트 업데이트를 덮어씀

#### 문제 2-2: 메시지 로드 순서 문제
- **위치**: `application/shop/views/mobile/chat/chat_view.php`
- **원인**: 
  - `room_join_return`에서 읽음 카운트를 업데이트하려 하지만, 메시지가 아직 DOM에 없음
  - 메시지 로드 순서: `room_join_return` → `message_list_return`
  - 메시지가 로드되기 전에 읽음 카운트를 업데이트하려 해서 실패

#### 문제 2-3: 상대방 메시지 수신 시 자동 읽음 처리 누락
- **위치**: `application/shop/views/mobile/chat/chat_view.php`
- **원인**: 
  - 상대방이 보낸 메시지를 받았을 때 자동으로 읽음 처리를 하지 않음
  - 채팅방에 있을 때 메시지를 받으면 즉시 읽음 처리해야 함

---

## 해결 방법

### 해결 1: 서버 측 수정

#### 1-1. `ret.data.list` 경로 수정
```javascript
// 수정 전
if (ret && ret.list && Array.isArray(ret.list)) {
    // ret.list는 undefined
}

// 수정 후
var actualList = (ret && ret.data && ret.data.list) ? ret.data.list : [];
if (actualList && Array.isArray(actualList) && actualList.length > 0) {
    // actualList 사용
}
```

#### 1-2. 읽음 처리 후 count를 0으로 전송
```javascript
// 수정 전
io.sockets.in(data.rm_idx).emit('message_read_count_return', {
    data: {
        count: msgInfo.count,  // 읽음 처리 전의 count (예: 1)
        mg_idx: msgInfo._id
    }
});

// 수정 후
io.sockets.in(data.rm_idx).emit('message_read_count_return', {
    data: {
        count: 0,  // 읽음 처리 완료되어 count는 0
        mg_idx: msgInfo._id
    }
});
```

**파일**: `chatting/chat/chat_server.js` (375-450줄)

### 해결 2: 클라이언트 측 수정

#### 2-1. `room_join_return`에서 읽음 카운트 초기화 제거
```javascript
// 수정 전
socket.on("room_join_return", function(ret) {
    $(".readcount").html(""); // 모든 읽음 카운트 초기화 (문제 발생)
});

// 수정 후
socket.on("room_join_return", function(ret) {
    // 읽음 카운트 초기화 제거 - 서버에서 실시간 업데이트하므로
    // 읽음 카운트 정보를 pendingReadCounts에 저장
    if (ret && ret.list && Array.isArray(ret.list)) {
        pendingReadCounts = {};
        for (var i = 0; i < ret.list.length; i++) {
            var msgInfo = ret.list[i];
            pendingReadCounts[msgInfo._id] = msgInfo.count || 0;
        }
    }
});
```

**파일**: `application/shop/views/mobile/chat/chat_view.php` (2448-2476줄, 3421-3449줄)

#### 2-2. 메시지 로드 후 읽음 카운트 업데이트
```javascript
// message_list_return 핸들러에 추가
socket.on("message_list_return", function(ret) {
    // 메시지 리스트 가져오기
    var message_list = ret.data;
    for (var icnt = 0; icnt < message_list.length; icnt++) {
        showMessage(message_list[icnt].rm_messages, true);
    }
    
    // 🔥 메시지 로드 후 저장된 읽음 카운트 정보 업데이트
    if (Object.keys(pendingReadCounts).length > 0) {
        for (var mg_idx in pendingReadCounts) {
            if (pendingReadCounts.hasOwnProperty(mg_idx)) {
                var count = pendingReadCounts[mg_idx];
                updateReadCount(mg_idx, count);
            }
        }
        pendingReadCounts = {};
    }
});
```

**파일**: `application/shop/views/mobile/chat/chat_view.php` (2350-2367줄, 3338-3355줄)

#### 2-3. 상대방 메시지 수신 시 자동 읽음 처리
```javascript
// message_insert_return 핸들러에 추가
socket.on("message_insert_return", function(ret) {
    // ...
    } else {
        // 🔥 상대방이 보낸 메시지: 새로 표시
        if (!processedIdx.includes(received_message.mg_idx)) {
            showMessage(received_message, false);
            
            // 🔥 실시간 읽음 처리: 상대방이 보낸 메시지를 받았을 때 자동으로 읽음 처리
            var readParams = {
                rm_idx: Number(rm_idx),
                mg_idx: Number(received_message.mg_idx),
                user_id: g_user_id
            };
            socket.emit("message_read_return", readParams);
        }
    }
});
```

**파일**: `application/shop/views/mobile/chat/chat_view.php` (2412-2419줄, 3351-3358줄)

---

## 수정된 파일 목록

### 서버 측
1. `chatting/chat/chat_server.js`
   - `room_join` 핸들러 수정 (375-450줄)
   - `ret.data.list` 경로 수정
   - 읽음 처리 후 count를 0으로 전송

2. `chatting/chat/model/chat_model.js`
   - `updateEnterRoom` 함수에 로깅 추가 (297-328줄)

3. `chatting/chat/common/common.js`
   - `writeBusinessLog` 함수 추가 (비즈니스 로그 기록)

### 클라이언트 측
1. `application/shop/views/mobile/chat/chat_view.php`
   - `room_join_return` 핸들러 수정 (안드로이드: 2448-2476줄, iOS: 3421-3449줄)
   - `message_list_return` 핸들러 수정 (안드로이드: 2350-2367줄, iOS: 3338-3355줄)
   - `message_insert_return` 핸들러 수정 (안드로이드: 2412-2419줄, iOS: 3351-3358줄)
   - 공통 함수 추가: `pendingReadCounts`, `updateReadCount()` (1841-1860줄)

---

## 작동 흐름

### 정상 작동 시나리오

1. **A가 메시지 전송 (B가 채팅방에 없음)**
   - A의 readcount = 1 표시

2. **B가 채팅방 입장**
   - 서버에서 `room_join` 이벤트 처리
   - `updateEnterRoom` 함수 호출 → 모든 메시지 읽음 처리
   - `message_read_count_return` 이벤트를 채팅방의 모든 사용자에게 전송 (count: 0)

3. **A의 클라이언트가 이벤트 수신**
   - `message_read_count_return` 핸들러에서 readcount를 0으로 업데이트
   - **결과: A의 readcount가 즉시 0으로 변경됨** ✅

### 추가 시나리오: 상대방이 채팅방에 있을 때

1. **A가 메시지 전송 (B가 채팅방에 있음)**
   - B의 클라이언트가 `message_insert_return` 이벤트 수신
   - B의 클라이언트가 자동으로 `message_read_return` 이벤트 전송
   - 서버에서 읽음 처리 후 `message_read_count_return` 이벤트 전송
   - A의 클라이언트가 readcount를 0으로 업데이트
   - **결과: A의 readcount가 즉시 0으로 변경됨** ✅

---

## 로깅 개선

### 추가된 로깅 함수
- `writeBusinessLog()`: JSON 형식으로 비즈니스 로그 기록
- 로그 파일: `/website/nowapk/logs/business_error_YYYY-MM-DD.log`
- 로그 형식:
  ```json
  {
    "timestamp": "2025-12-30 15:14:27",
    "function": "room_join",
    "error": "room_join 이벤트 수신",
    "data": { ... },
    "ip": "...",
    "user_agent": "..."
  }
  ```

### 주요 로그 이벤트
- `room_join`: 채팅방 입장 이벤트
- `updateEnterRoom_start`: 읽음 처리 시작
- `updateEnterRoom_aggregate`: MongoDB 집계 결과
- `updateEnterRoom_updateOne`: MongoDB 업데이트 결과
- `updateEnterRoom_result`: 최종 결과
- `room_join_emit_read_count`: 읽음 카운트 이벤트 발송
- `room_join_no_list`: 읽음 처리할 메시지 없음

---

## 테스트 결과

✅ **정상 작동 확인**
- 상대방이 채팅방에 입장하기만 해도 보낸 사람의 readcount가 즉시 0으로 업데이트됨
- 상대방이 채팅방에 있을 때 메시지를 받으면 즉시 읽음 처리됨
- 채팅방을 나갔다가 다시 들어와도 정상 작동함

---

## 참고 사항

1. **레거시 코드 주석 처리**
   - `APP_NOWAPK` 브리지 함수들은 주석 처리됨
   - Socket.IO 방식으로 완전히 전환됨

2. **FCM 토큰 기능**
   - 메시지 전송 시 FCM 푸시 알림 정상 작동 확인
   - Socket.IO 변경과 무관하게 작동

3. **호환성**
   - 안드로이드와 iOS 모두 동일한 로직 적용
   - 웹 브라우저에서도 정상 작동

---

## 작성일
2025-12-30

## 작성자
AI Assistant (Auto)

