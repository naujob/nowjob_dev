기존 md 파일 형식을 확인한 뒤, 데이터 로직 정리 문서를 마크다운으로 작성합니다.

[2 tools called]

데이터 로직 정리 문서를 마크다운으로 작성합니다.

[1 tool called]

채팅 시스템 데이터 로직 정리 마크다운 파일 내용입니다. `/website/nowapk/md/CHAT_DATA_LOGIC.md`로 저장하세요.

```markdown
# 채팅 시스템 데이터 로직 및 함수 흐름 분석

## 📋 개요

채팅 서버(`chat_server.js`)의 로그 파일(`chat_log.txt`, `chat_log2.txt`)을 분석하여 실제 동작하는 함수와 데이터 흐름을 정리한 문서입니다.

---

## 🏗️ 시스템 아키텍처

### 기술 스택

- **서버**: Node.js + Express
- **실시간 통신**: Socket.IO
- **데이터베이스**: MongoDB (주), MySQL (보조)
- **푸시 알림**: Firebase Cloud Messaging (FCM)
- **프로토콜**: HTTPS (SSL 인증서 사용)

### 디렉토리 구조
```

chatting/chat/
├── chat_server.js # 메인 서버 파일
├── controller/ # 컨트롤러 레이어
│ ├── chat_controller.js
│ ├── user_controller.js
│ └── alarm_controller.js
├── model/ # 모델 레이어
│ ├── chat_model.js
│ ├── user_model.js
│ └── alarm_model.js
├── database/ # 데이터베이스 연결
│ ├── database.js # MongoDB
│ └── mysql.js # MySQL
└── common/ # 공통 유틸리티

```

---

## 🔄 주요 데이터 흐름

### 1. 서버 초기화 및 연결

#### 실행 순서:
```

1. chat_server.js 시작
   ├─> $db.init() (MongoDB 연결)
   │ └─> database.js::init()
   │ ├─> MongoDB 클라이언트 연결
   │ ├─> 컬렉션 초기화 (room, user, alarm, notification, table_key)
   │ └─> 연결 완료
   ├─> $mysql.init() (MySQL 연결)
   └─> io.on('connection') (Socket.IO 연결 대기)

```

#### 주요 함수:
- **`database.js::init()`**: MongoDB 연결 및 컬렉션 초기화
- **`database.js::isConnected()`**: 연결 상태 확인
- **`database.js::waitForConnection()`**: 연결 대기 (최대 50회 시도)

#### 데이터 저장 위치:
- MongoDB: `mongodb://localhost:27017`
- 컬렉션: `room`, `user`, `alarm`, `notification`, `table_key`

---

### 2. 사용자 등록/로그인 흐름

#### 이벤트: `user_insert` 또는 `user_auto_insert`

#### 실행 순서:
```

socket.on('user_insert')
├─> data.socket_id = socket.id (소켓 ID 추가)
├─> $exe.user.insertUser()
│ └─> user_controller.js::insertUser()
│ ├─> user_model.js::getUser() (중복 체크)
│ ├─> user_model.js::insertUser() (MongoDB 저장)
│ │ ├─> $db.getNextSequence('user') (시퀀스 생성)
│ │ ├─> 비밀번호 해시화
│ │ └─> cs_user.insertOne() (사용자 정보 저장)
│ └─> callback 반환
├─> socket.broadcast.emit('user_insert_alarm') (다른 사용자에게 알림)
└─> socket.emit('user_insert_return') (등록 결과 반환)

````

#### 주요 함수:
- **`user_controller.js::insertUser()`**: 회원 등록 처리
- **`user_controller.js::insertAutoUser()`**: 자동 회원 등록 (기존 회원이면 업데이트)
- **`user_model.js::insertUser()`**: MongoDB에 사용자 정보 저장
- **`user_model.js::getUser()`**: 사용자 조회
- **`user_model.js::updateUser()`**: 사용자 정보 업데이트

#### 데이터 구조:
```javascript
{
  user_idx: 1,              // 시퀀스 번호
  user_id: "01012345678",   // 사용자 ID (전화번호)
  user_pwd: "hashed_pwd",   // 해시된 비밀번호
  user_nick: "사용자명",
  user_img: "/path/to/img",
  user_desc: "설명",
  user_status: "Y",         // 접속 상태
  socket_id: "socket_id",   // Socket.IO ID
  token: "fcm_token",       // FCM 토큰
  last_location: "chat_room" // 마지막 위치
}
````

#### 저장 위치:

- MongoDB `user` 컬렉션

---

### 3. 채팅방 생성/조회 흐름

#### 이벤트: `room_info`

#### 실행 순서:

```
socket.on('room_info')
  ├─> $exe.chat.updateMessageUserCount() (읽음 처리)
  │   └─> chat_model.js::updateMessageUserCount()
  │       └─> MongoDB updateMany (모든 메시지 읽음 처리)
  │
  ├─> $exe.chat.getRoom() (방 정보 조회)
  │   └─> chat_controller.js::getRoom()
  │       └─> chat_model.js::getRoom()
  │           ├─> MongoDB findOne (방 정보 조회)
  │           ├─> 사용자 정보 초기화 (user_nick, user_img 등)
  │           └─> 메시지 카운트 계산
  │
  ├─> 방 정보가 없으면 자동 생성
  │   ├─> room_data 생성 (기본값 설정)
  │   ├─> $exe.chat.insertRoom()
  │   │   └─> chat_controller.js::insertRoom()
  │   │       ├─> chat_model.js::getUsersRoom() (기존 방 체크)
  │   │       └─> chat_model.js::insertRoom() (새 방 생성)
  │   │           └─> MongoDB insertOne (채팅방 저장)
  │   └─> 생성 후 재조회
  │
  ├─> socket.join(data.rm_idx) (Socket.IO 룸 입장)
  └─> socket.emit('room_info_return') (방 정보 반환)
```

#### 주요 함수:

- **`chat_controller.js::getRoom()`**: 방 정보 조회
- **`chat_controller.js::insertRoom()`**: 방 생성 처리
- **`chat_model.js::getRoom()`**: MongoDB에서 방 정보 조회 및 사용자 정보 초기화
- **`chat_model.js::insertRoom()`**: MongoDB에 채팅방 저장
- **`chat_model.js::getUsersRoom()`**: 기존 방 존재 여부 확인

#### 데이터 구조:

```javascript
{
  rm_idx: 787,                    // 방 인덱스
  rc_idx: 0,                      // 채용공고 인덱스
  rm_type: "recruit",             // 방 타입 (recruit/trade/channel)
  rm_subject: "채용공고 채팅방",
  rm_desc: "채용공고 관련 채팅방",
  rm_last_msg: "마지막 메시지",
  rm_last_dt: "2025-11-27T01:31:30.264Z",
  rm_users: [                     // 방 사용자 목록
    {
      user_id: "01012345678",
      user_nick: "사용자명",
      user_img: "/path/to/img",
      user_status: "Y"
    }
  ],
  rm_messages: [],                // 메시지 배열
  rm_status: "N"
}
```

#### 저장 위치:

- MongoDB `room` 컬렉션

---

### 4. 메시지 전송 흐름 (핵심)

#### 이벤트: `message_insert`

#### 실행 순서:

```
socket.on('message_insert')
  ├─> MongoDB 연결 확인
  │
  ├─> 메시지 DB 저장
  │   └─> $exe.chat.insertMessage()
  │       └─> chat_controller.js::insertMessage()
  │           └─> chat_model.js::insertMessage()
  │               ├─> 방 정보 조회 (room_info)
  │               ├─> 사용자 정보 조회 (user_info)
  │               ├─> 마지막 메시지 조회 (last_msg)
  │               ├─> 날짜 라인 추가 여부 확인
  │               ├─> 읽음 상태 리스트 생성 (read_users)
  │               ├─> 메시지 생성 및 DB 저장
  │               │   ├─> $db.getNextSequence('message')
  │               │   └─> MongoDB updateOne (rm_messages 배열에 추가)
  │               └─> 방 정보 업데이트 (rm_last_msg, rm_last_dt)
  │
  ├─> 실시간 메시지 전송
  │   └─> io.sockets.in(rm_idx).emit('message_insert_return')
  │
  ├─> 푸시 알림 처리
  │   ├─> $exe.chat.getRoomUsersFromDB() (DB에서 전체 사용자 조회)
  │   │   └─> MongoDB findOne (rm_users 배열 조회)
  │   │
  │   ├─> $exe.chat.getRoom() (방 정보 조회)
  │   │
  │   ├─> 사용자 목록 통합
  │   │   ├─> 기존 방 정보 사용자 (rm_users)
  │   │   ├─> 접속 중인 사용자 (rm_users 파라미터)
  │   │   └─> DB에서 조회한 사용자 (중복 제거)
  │   │
  │   ├─> 발신자 제외 및 실제 사용자 필터링
  │   │
  │   ├─> 발신자 이름 조회
  │   │   └─> $exe.user.getUser()
  │   │
  │   ├─> 접속 중인 사용자 처리
  │   │   ├─> 실시간 알림 표시
  │   │   │   └─> io.to(socket_id).emit('chat_notification')
  │   │   └─> 알림 등록 (채팅방에 없을 경우)
  │   │       ├─> $exe.alarm.insertAlarm()
  │   │       │   └─> alarm_model.js::insertAlarm()
  │   │       │       └─> MongoDB insertOne (alarm 컬렉션)
  │   │       ├─> $exe.alarm.getAlarmCount()
  │   │       └─> socket.emit('alarm_refresh')
  │   │
  │   └─> FCM 푸시 발송 (각 사용자별)
  │       ├─> FCM 토큰 조회
  │       │   ├─> MongoDB에서 조회 (우선)
  │       │   └─> 없으면 MySQL에서 조회
  │       │       └─> $exe.user.getUserTokenFromMySQL()
  │       ├─> $exe.alarm.insertNotification()
  │       │   └─> alarm_model.js::insertNotification()
  │       │       └─> MongoDB insertOne (notification 컬렉션)
  │       └─> send_fcm() (Firebase FCM 발송)
  │           └─> fcm_admin.messaging().send()
  │
  └─> 접속 중인 사용자에게 메시지 리프레시
      └─> io.to(socket_id).emit('message_refresh')
```

#### 주요 함수:

- **`chat_model.js::insertMessage()`**: 메시지 DB 저장
- **`chat_model.js::getLastMessage()`**: 마지막 메시지 조회
- **`chat_controller.js::getRoomUsersFromDB()`**: DB에서 채팅방 사용자 조회
- **`user_controller.js::getUserTokenFromMySQL()`**: MySQL에서 FCM 토큰 조회
- **`alarm_controller.js::insertAlarm()`**: 알림 등록
- **`alarm_controller.js::insertNotification()`**: 푸시 알림 등록
- **`chat_server.js::send_fcm()`**: Firebase FCM 발송

#### 데이터 구조:

```javascript
// 메시지 구조
{
  user_id: "01012345678",
  mg_idx: 2845,                    // 시퀀스 번호
  mg_type: "text",                 // text/img/file/timeline 등
  mg_text: "메시지 내용",
  mg_dt: "2025-11-27T01:31:33.434Z",
  mg_reads: [                      // 읽음 상태
    { user_id: "01012345678", read_yn: "Y" },
    { user_id: "01087654321", read_yn: "N" }
  ],
  user_info: [...]                 // 사용자 정보 배열
}
```

#### 저장 위치:

- MongoDB `room` 컬렉션의 `rm_messages[]` 배열
- MongoDB `alarm` 컬렉션 (알림)
- MongoDB `notification` 컬렉션 (푸시 알림 내역)

---

### 5. 메시지 읽음 처리 흐름

#### 이벤트: `message_read_return`

#### 실행 순서:

```
socket.on('message_read_return')
  └─> $exe.chat.updateMessageReturnCount()
      └─> chat_controller.js::updateMessageReturnCount()
          └─> chat_model.js::updateMessageReturnCount()
              ├─> MongoDB updateOne (read_yn = "Y"로 변경)
              ├─> 미읽음 카운트 조회 (aggregate)
              └─> callback 반환
              └─> io.sockets.in(rm_idx).emit('message_read_count_return')
```

#### 주요 함수:

- **`chat_model.js::updateMessageReturnCount()`**: 메시지 읽음 처리 및 카운트 조회

#### MongoDB 쿼리:

```javascript
// 읽음 처리
updateOne(
  { rm_idx: data.rm_idx },
  { $set: { "rm_messages.$[message].mg_reads.$[read].read_yn": "Y" } },
  {
    arrayFilters: [
      { "message.mg_idx": data.mg_idx },
      { "read.user_id": data.user_id },
    ],
  }
);

// 미읽음 카운트 조회
aggregate([
  { $match: { rm_idx: data.rm_idx } },
  { $unwind: "$rm_messages" },
  { $match: { "rm_messages.mg_idx": data.mg_idx } },
  { $unwind: "$rm_messages.mg_reads" },
  { $match: { "rm_messages.mg_reads.read_yn": "N" } },
]);
```

---

### 6. 채팅방 리스트 조회 흐름

#### 이벤트: `room_list` 또는 `room_list2`

#### 실행 순서:

```
socket.on('room_list')
  └─> $exe.chat.getRoomList()
      └─> chat_controller.js::getRoomList()
          └─> chat_model.js::getRoomList()
              ├─> MongoDB aggregate 쿼리
              │   ├─> $match (방 타입, 사용자 필터링)
              │   ├─> $lookup (사용자 정보 조인)
              │   ├─> $sort (최신순 정렬)
              │   └─> $project (필요한 필드만 선택)
              ├─> 미읽음 메시지 카운트 계산
              │   └─> getNotReadCountList()
              └─> callback 반환
              └─> socket.emit('room_list_return')
```

#### 주요 함수:

- **`chat_model.js::getRoomList()`**: 채팅방 리스트 조회 (MongoDB aggregate)
- **`chat_model.js::getNotReadCountList()`**: 미읽음 메시지 카운트 조회

#### MongoDB 쿼리:

```javascript
aggregate([
  {
    $match: {
      rm_type: "recruit",
      "rm_users.user_id": user_id,
    },
  },
  {
    $lookup: {
      from: "user",
      localField: "rm_users.user_id",
      foreignField: "user_id",
      as: "rm_users",
    },
  },
  { $sort: { rm_last_dt: -1 } },
]);
```

---

### 7. 알림 처리 흐름

#### 이벤트: `alarm_message`

#### 실행 순서:

```
socket.on('alarm_message')
  ├─> $exe.user.getUser() (사용자 정보 조회)
  │   └─> user_model.js::getUser()
  │
  ├─> 푸시 알림 등록
  │   └─> $exe.alarm.insertNotification()
  │       └─> alarm_model.js::insertNotification()
  │           └─> MongoDB insertOne (notification 컬렉션)
  │
  ├─> FCM 발송
  │   └─> send_fcm()
  │       └─> fcm_admin.messaging().send()
  │           ├─> 성공: updateNotification(send_yn: "Y")
  │           └─> 실패: updateNotification(send_yn: "N", no_result: error)
  │
  ├─> 알림 등록
  │   └─> $exe.alarm.insertAlarm()
  │       └─> alarm_model.js::insertAlarm()
  │           └─> MongoDB insertOne (alarm 컬렉션)
  │
  └─> 알림 카운트 조회 및 브로드캐스트
      └─> $exe.alarm.getAlarmCount()
          └─> alarm_model.js::getAlarmCount()
              └─> socket.to(target_socket_id).emit('alarm_refresh')
```

#### 주요 함수:

- **`alarm_model.js::insertAlarm()`**: 알림 저장
- **`alarm_model.js::insertNotification()`**: 푸시 알림 저장
- **`alarm_model.js::getAlarmCount()`**: 미확인 알림 카운트 조회
- **`chat_server.js::send_fcm()`**: Firebase FCM 발송

---

### 8. 접속 종료 처리

#### 이벤트: `disconnect`

#### 실행 순서:

```
socket.on('disconnect')
  └─> $exe.user.updateUserStatus()
      └─> user_controller.js::updateUserStatus()
          └─> user_model.js::updateUserStatus()
              └─> MongoDB updateOne (user_status = "N")
```

#### 주요 함수:

- **`user_model.js::updateUserStatus()`**: 접속 상태 변경

---

## 📊 데이터베이스 구조

### MongoDB 컬렉션

#### 1. `room` (채팅방)

```javascript
{
  _id: ObjectId,
  rm_idx: 787,                    // 방 인덱스
  rc_idx: 0,                      // 채용공고 인덱스
  rm_type: "recruit",             // 방 타입
  rm_subject: "채용공고 채팅방",
  rm_desc: "설명",
  rm_last_msg: "마지막 메시지",
  rm_last_dt: ISODate,
  rm_users: [                     // 방 사용자 목록
    {
      user_id: "01012345678",
      user_nick: "사용자명",
      user_img: "/path/to/img",
      user_status: "Y"
    }
  ],
  rm_messages: [                  // 메시지 배열
    {
      user_id: "01012345678",
      mg_idx: 2845,
      mg_type: "text",
      mg_text: "메시지 내용",
      mg_dt: ISODate,
      mg_reads: [
        { user_id: "01012345678", read_yn: "Y" }
      ],
      user_info: [...]
    }
  ],
  rm_status: "N"
}
```

#### 2. `user` (사용자)

```javascript
{
  _id: ObjectId,
  user_idx: 1,
  user_id: "01012345678",
  user_pwd: "hashed_pwd",
  user_nick: "사용자명",
  user_img: "/path/to/img",
  user_desc: "설명",
  user_status: "Y",               // 접속 상태
  socket_id: "socket_id",
  token: "fcm_token",             // FCM 토큰
  last_location: "chat_room",
  alarm_chat: "Y",
  alarm_resume: "Y",
  alarm_fav: "Y"
}
```

#### 3. `alarm` (알림)

```javascript
{
  _id: ObjectId,
  al_idx: 1,
  user_id: "01012345678",
  al_type: "recruit",             // 알림 타입
  al_key: "787",                  // 채팅방 인덱스
  al_text: "알림 내용",
  al_url: "",
  show_yn: "N",                   // 확인 여부
  reg_dt: "2025-11-27T01:31:33.434Z"
}
```

#### 4. `notification` (푸시 알림 내역)

```javascript
{
  _id: ObjectId,
  no_idx: 1,
  user_id: "01012345678",
  no_subject: "알림 제목",
  no_text: "알림 내용",
  no_url: "",
  no_token: "fcm_token",
  send_yn: "Y",                   // 발송 여부
  no_result: "",                   // 발송 결과
  reg_dt: ISODate
}
```

#### 5. `table_key` (시퀀스 관리)

```javascript
{
  _id: "message",                 // 또는 "user", "alarm" 등
  seq: 2845                       // 현재 시퀀스 번호
}
```

### MySQL 테이블

#### 1. `st_member` (회원 정보)

- `mm_idx`: 회원 인덱스
- `mm_id`: 회원 ID (전화번호)
- `mm_status`: 상태

#### 2. `st_member_device` (FCM 토큰)

- `mm_idx`: 회원 인덱스
- `token`: FCM 토큰
- `push_yn`: 푸시 수신 여부
- `up_dt`: 업데이트 일시

---

## 🔑 주요 함수 상세 설명

### Controller 레이어

#### `chat_controller.js`

**`insertRoom(data, callback)`**

- 채팅방 생성 처리
- 기존 방 존재 여부 확인 후 생성
- 파라미터: `rm_idx`, `rm_type`, `user_list` 등
- 반환: `{code: "00", msg: "등록성공", data: room_idx}`

**`getRoom(data, callback)`**

- 채팅방 정보 조회
- 사용자 정보 초기화 포함
- 파라미터: `rm_idx`, `con_user_id`
- 반환: `{code: "00", msg: "조회 성공", data: room}`

**`insertMessage(data, callback)`**

- 메시지 등록 처리
- 파라미터: `rm_idx`, `user_id`, `mg_type`, `mg_text` 등
- 반환: `{code: "00", msg: "등록성공", data: messages}`

**`getRoomUsersFromDB(params, callback)`**

- DB에서 채팅방 사용자 조회
- MongoDB에서 `rm_users` 배열 조회
- 파라미터: `rm_idx`
- 반환: `user[]` 배열

#### `user_controller.js`

**`insertUser(data, callback)`**

- 회원 등록 처리
- 중복 체크 후 등록
- 파라미터: `user_id`, `user_pwd`, `user_nick` 등
- 반환: `{code: "00", msg: "회원가입 성공", data: user}`

**`getUser(data, callback)`**

- 사용자 정보 조회
- 파라미터: `user_id`
- 반환: `user` 객체

**`getUserTokenFromMySQL(user_id, callback)`**

- MySQL에서 FCM 토큰 조회
- `st_member_device` 테이블에서 조회
- 반환: `token` 문자열

#### `alarm_controller.js`

**`insertAlarm(data, callback)`**

- 알림 등록
- 파라미터: `user_id`, `al_type`, `al_text` 등
- 반환: `{code: "00", msg: "등록성공"}`

**`insertNotification(data, callback)`**

- 푸시 알림 등록
- 파라미터: `user_id`, `no_subject`, `no_text`, `no_token` 등
- 반환: `no_idx` (알림 인덱스)

### Model 레이어

#### `chat_model.js`

**`insertMessage(data, callback)`**

- 메시지 DB 저장
- 날짜 라인 추가 처리
- 읽음 상태 리스트 생성
- 방 정보 업데이트
- 반환: `messages[]` 배열

**`getRoom(params)`**

- MongoDB에서 방 정보 조회
- 사용자 정보 초기화
- 메시지 카운트 계산
- 반환: `room` 객체

**`getRoomList(params)`**

- 채팅방 리스트 조회 (aggregate)
- 사용자 정보 조인
- 미읽음 메시지 카운트 계산
- 반환: `room[]` 배열

**`updateMessageReturnCount(data, callback)`**

- 메시지 읽음 처리
- 미읽음 카운트 조회
- 반환: `{count: 0, mg_idx: 2845}`

#### `user_model.js`

**`insertUser(data, callback)`**

- 사용자 정보 MongoDB 저장
- 시퀀스 생성
- 비밀번호 해시화
- 반환: `true/false`

**`getUser(params)`**

- MongoDB에서 사용자 조회
- 반환: `user` 객체 또는 `null`

**`updateUserStatus(params, callback)`**

- 접속 상태 업데이트
- 파라미터: `socket_id`, `user_status`

#### `alarm_model.js`

**`insertAlarm(data, callback)`**

- 알림 MongoDB 저장
- 시퀀스 생성
- 반환: `al_idx`

**`insertNotification(data, callback)`**

- 푸시 알림 내역 저장
- 시퀀스 생성
- 반환: `no_idx`

**`getAlarmCount(params)`**

- 미확인 알림 카운트 조회
- 반환: `count` 숫자

### Database 레이어

#### `database.js`

**`init()`**

- MongoDB 연결
- 컬렉션 초기화
- 연결 상태 설정

**`getNextSequence(name)`**

- 시퀀스 번호 생성
- `table_key` 컬렉션 사용
- 반환: `seq` 숫자

**`isConnected()`**

- MongoDB 연결 상태 확인
- 반환: `true/false`

---

## 📝 로그 분석 결과

### 주요 로그 패턴

#### 1. 방 정보 조회

```
room_info:: getRoom 결과 - ret:{"code":"00","msg":"조회 성공","data":...}
room_info:: 방 정보 없음 - 새 방 생성 시작 - rm_idx:787
room_info:: insertRoom 호출 완료
```

#### 2. 메시지 전송

```
insertMessage 함수 시작 - rm_idx: 787, user_id: 01074255501
방 정보 조회 시작
사용자 정보 조회 시작
마지막 메시지 조회 시작
insertMessage:: rm_users 존재 - 개수:1
방 정보 업데이트 시작
insertMessage 함수 완료
```

#### 3. 사용자 조회

```
=== getRoomUsersFromDB 시작 ===
MongoDB에서 채팅방 조회 시도 - rm_idx: 787
MongoDB 조회 결과: 채팅방 발견
✅ MongoDB에서 채팅방 사용자 조회 성공 - count: 1
```

---

## 🔍 주요 데이터 흐름 요약

1. **사용자 접속**

   - Socket.IO 연결 → 사용자 정보 MongoDB 저장/업데이트

2. **채팅방 조회**

   - 방 정보 조회 → 없으면 자동 생성 → 사용자 정보 초기화

3. **메시지 전송**

   - 메시지 MongoDB 저장 → 실시간 전송 → 푸시 알림 발송

4. **읽음 처리**

   - MongoDB 업데이트 → 실시간 카운트 전송

5. **알림 관리**
   - MongoDB에 저장 → FCM 푸시 발송

---

## ⚠️ 주의사항

1. **MongoDB 연결 확인**: 메시지 전송 전 연결 상태 확인 필수
2. **FCM 토큰 조회 순서**: MongoDB → MySQL 순으로 조회
3. **사용자 필터링**: 발신자 및 테스트 사용자 제외 처리
4. **중복 제거**: 사용자 목록 통합 시 중복 제거 필수
5. **에러 처리**: 모든 비동기 작업에 에러 핸들링 필요

---

## 📚 참고 자료

- Socket.IO 공식 문서: https://socket.io/docs/
- MongoDB Node.js 드라이버: https://mongodb.github.io/node-mongodb-native/
- Firebase Admin SDK: https://firebase.google.com/docs/admin/setup
