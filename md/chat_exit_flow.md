# 채팅방 나가기 기능 - 데이터 흐름 및 함수 정리

## 📋 목차
1. [전체 흐름도](#전체-흐름도)
2. [단계별 상세 설명](#단계별-상세-설명)
3. [중요 변수 정리](#중요-변수-정리)
4. [관련 함수 목록](#관련-함수-목록)

---

## 🔄 전체 흐름도

```
[사용자 클릭] 
    ↓
[프론트엔드: chat_view.php]
    ├─ exitChatRoom() - 나가기 확인
    ├─ doExitRoom() - AJAX 호출
    └─ socket.emit("message_insert") - 시스템 메시지 전송
    ↓
[백엔드: chat_proc.php]
    ├─ exit_room() - 나가기 처리
    ├─ MongoDB 업데이트 (exit_yn='Y', exit_dt 설정)
    ├─ 시스템 메시지 생성
    └─ 응답 반환 (system_message 포함)
    ↓
[소켓 서버: chat_server.js]
    ├─ message_insert 이벤트 수신
    ├─ exit_yn 체크 (나간 사용자 메시지 차단)
    └─ 메시지 DB 저장 및 브로드캐스트
    ↓
[목록 필터링: chat_proc.php]
    ├─ get_chat_target_list*() 함수들
    ├─ exit_yn='Y' 체크
    └─ 나간 채팅방 제외
    ↓
[접근 차단]
    ├─ chat.php::chat_view() - 페이지 접근 차단
    ├─ chat_model.js::getRoom() - 소켓 방 정보 차단
    └─ chat_server.js::message_insert() - 메시지 전송 차단
```

---

## 📝 단계별 상세 설명

### 1단계: 프론트엔드 - 나가기 버튼 클릭

**파일**: `application/shop/views/mobile/chat/chat_view.php`

#### 1.1 exitChatRoom() 함수 (119-144줄)
```javascript
function exitChatRoom(redirectUrl) {
    var rm_idx = "<?= $rm_idx ?>";  // 채팅방 ID
    var g_user_id = ensureValidUserId();  // 사용자 ID
    
    // 확인 메시지 표시
    commonConfirm("채팅방을 나가시겠습니까?", function() {
        doExitRoom(rm_idx, g_user_id, redirectUrl);
    });
}
```

**역할**:
- 나가기 확인 다이얼로그 표시
- `doExitRoom()` 호출

**중요 변수**:
- `rm_idx`: 채팅방 고유 ID
- `g_user_id`: 현재 사용자 ID
- `redirectUrl`: 나가기 후 이동할 URL

---

#### 1.2 doExitRoom() 함수 (147-176줄)
```javascript
function doExitRoom(rm_idx, g_user_id, redirectUrl) {
    comAjax("/chat_proc/exit_room", {
        rm_idx: rm_idx,
        user_id: g_user_id
    }, function(ret) {
        if (ret.code == "00") {
            // 시스템 메시지를 소켓으로 전송
            if (ret.data && ret.data.system_message) {
                socket.emit("message_insert", systemMsg, rm_info.rm_users);
            }
            // 목록 페이지로 이동
            location.href = redirectUrl || '/chat/chat_list';
        }
    });
}
```

**역할**:
- AJAX로 `/chat_proc/exit_room` 호출
- 성공 시 소켓으로 시스템 메시지 전송
- 목록 페이지로 리다이렉트

**중요 변수**:
- `ret.data.system_message`: 서버에서 생성한 시스템 메시지 객체
- `rm_info.rm_users`: 채팅방 참여자 목록

---

### 2단계: 백엔드 - 나가기 처리

**파일**: `application/shop/controllers/chat_proc.php`

#### 2.1 exit_room() 함수 (2020-2227줄)

**2.1.1 초기화 및 검증 (2022-2040줄)**
```php
$rm_idx = input_post("rm_idx", "");
$user_id = $this->user->getUserId();
$mm_idx = $this->user->getUserIdx();

// 필수 파라미터 검증
if (empty($rm_idx) || empty($user_id)) {
    json_return("90", "필수 파라미터가 없습니다.", []);
    return;
}
```

**중요 변수**:
- `$rm_idx`: 채팅방 ID
- `$user_id`: 사용자 ID (문자열)
- `$mm_idx`: 회원 인덱스 (숫자)

---

**2.1.2 MongoDB 연결 및 채팅방 조회 (2042-2057줄)**
```php
$mongo = new Mongo("mongodb://localhost:27017");
$database = $mongo->chat;
$collection = $database->room;

$query = ['rm_idx' => (int)$rm_idx];
$room = $collection->findOne($query);
```

**중요 변수**:
- `$room`: MongoDB에서 조회한 채팅방 정보
- `$room["rm_users"]`: 채팅방 참여자 배열

---

**2.1.3 사용자 정보 조회 (2067-2069줄)**
```php
$user_info = $this->member_model->getMember(["mm_idx" => $mm_idx]);
$user_name = !empty($user_info["mm_name"]) 
    ? $user_info["mm_name"] 
    : (!empty($user_info["mm_comp_name"]) 
        ? $user_info["mm_comp_name"] 
        : $user_id);
```

**중요 변수**:
- `$user_name`: 사용자 이름 (시스템 메시지에 사용)

---

**2.1.4 rm_users 배열 순회 및 exit_yn 업데이트 (2071-2113줄)**
```php
$rm_users = isset($room["rm_users"]) ? $room["rm_users"] : [];
$user_found = false;
$all_exited = true;

foreach ($rm_users as $key => $room_user) {
    if (isset($room_user["user_id"]) && $room_user["user_id"] == $user_id) {
        $user_found = true;
        
        // 이미 나간 사용자인 경우
        if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
            json_return("00", "이미 나간 채팅방입니다.", []);
            return;
        }
        
        // exit_yn='Y', exit_dt 설정
        $rm_users[$key]["exit_yn"] = "Y";
        $rm_users[$key]["exit_dt"] = new MongoDate();
    } else {
        // 다른 사용자: exit_yn이 없거나 'N'이면 all_exited = false
        if (!isset($room_user["exit_yn"]) || $room_user["exit_yn"] != "Y") {
            $all_exited = false;
        }
    }
}
```

**중요 변수**:
- `$user_found`: 사용자를 찾았는지 여부 (boolean)
- `$all_exited`: 모든 사용자가 나갔는지 여부 (boolean)
- `$rm_users[$key]["exit_yn"]`: 나가기 여부 ('Y' 또는 없음)
- `$rm_users[$key]["exit_dt"]`: 나간 시간 (MongoDate 객체)

---

**2.1.5 MongoDB 업데이트 (2141-2159줄)**
```php
$update_data = ['$set' => ['rm_users' => $rm_users]];

// 모든 사용자가 나갔으면 rm_status를 'CLOSED'로 설정
if ($all_exited) {
    $update_data['$set']['rm_status'] = 'CLOSED';
}

$update_result = $collection->update($query, $update_data);
```

**중요 변수**:
- `$update_data`: MongoDB 업데이트 명령
- `$all_exited`: 모든 사용자가 나갔는지 여부
- `$update_result`: 업데이트 결과

---

**2.1.6 시스템 메시지 생성 (2172-2197줄)**
```php
$system_message = [
    "rm_idx" => (int)$rm_idx,
    "user_id" => $user_id,
    "chat_type" => "recruit",
    "mg_type" => "system_exit",
    "mg_text" => $user_name . "님이 채팅방을 나갔습니다.",
    "mg_req_dt" => "",
    "mg_img" => "",
    "mg_file" => "",
    "mg_imoti" => "",
    "mg_dt" => date("Y-m-d H:i:s"),
    "mg_reads" => []
];

// 나머지 사용자들에게 읽음 상태 설정
foreach ($rm_users as $room_user) {
    if (isset($room_user["user_id"]) && $room_user["user_id"] != $user_id) {
        $system_message["mg_reads"][] = [
            "user_id" => $room_user["user_id"],
            "read_yn" => "N"
        ];
    }
}
```

**중요 변수**:
- `$system_message`: 시스템 메시지 객체
- `$system_message["mg_type"]`: "system_exit" (시스템 메시지 타입)
- `$system_message["mg_reads"]`: 읽음 상태 배열

---

**2.1.7 응답 반환 (2210-2214줄)**
```php
json_return("00", "채팅방을 나갔습니다.", [
    "rm_idx" => $rm_idx,
    "system_message" => $system_message,
    "all_exited" => $all_exited
]);
```

---

### 3단계: 소켓 서버 - 시스템 메시지 처리

**파일**: `chatting/chat/chat_server.js`

#### 3.1 message_insert 이벤트 (414-477줄)

**3.1.1 나간 사용자 체크 (431-454줄)**
```javascript
const room = await $db.cs_room.findOne({rm_idx: target_rm_idx});
if(room && room.rm_users){
    var user_exited = false;
    for(var icnt=0;icnt < room.rm_users.length;icnt++){
        if(room.rm_users[icnt].user_id == g_user_id){
            // exit_yn='Y'이면 나간 채팅방
            if(room.rm_users[icnt].exit_yn && room.rm_users[icnt].exit_yn == "Y"){
                user_exited = true;
                break;
            }
        }
    }
    
    // 나간 채팅방이면 메시지 전송 차단
    if(user_exited){
        socket.emit('message_insert_return', {
            code: "90",
            msg: "나간 채팅방입니다.",
            data: null
        });
        return;
    }
}
```

**역할**:
- 나간 사용자(`exit_yn='Y'`)의 메시지 전송 차단
- 일반 메시지와 시스템 메시지 모두 체크

---

**3.1.2 메시지 저장 및 브로드캐스트 (456-462줄)**
```javascript
// 메시지 DB 저장
const ret = await new Promise((resolve) => {
    $exe.chat.insertMessage(data, (res) => resolve(res));
});

// 모든 클라이언트에게 메시지 전송
io.sockets.in(target_rm_idx).emit('message_insert_return', ret);
```

---

### 4단계: 프론트엔드 - 시스템 메시지 표시

**파일**: `application/shop/views/mobile/chat/chat_view.php`

#### 4.1 showMessage() 함수 - system_exit 처리 (1480-1484줄)
```javascript
else if (info.mg_type == "system_exit") {
    // 시스템 메시지: 채팅방 나가기 알림
    var content = "<p class='msg-item chat-notify' data-info='" + item + "'>";
    content += info.mg_text;
    content += "</p>";
}
```

**역할**:
- `mg_type == "system_exit"`인 메시지를 시스템 알림으로 표시
- 다른 사용자에게 "XXX님이 채팅방을 나갔습니다." 메시지 표시

---

### 5단계: 목록 필터링 - 나간 채팅방 제외

**파일**: `application/shop/controllers/chat_proc.php`

#### 5.1 목록 조회 함수들

**관련 함수**:
- `get_chat_target_list_recruit()` (14줄)
- `get_chat_target_list_recruit2()` (246줄)
- `get_chat_target_list_recruit_mem()` (473줄)
- `get_chat_target_list()` (633줄)
- `get_chat_target_list2()` (793줄)
- `get_chat_target_list2_mem()` (928줄)

**공통 필터링 로직** (예: 523-551줄):
```php
foreach ($rm_users as $room_user) {
    if (isset($room_user["user_id"]) && $room_user["user_id"] == $user_id) {
        if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
            $user_exited = true;
            // 디버깅 로그
            $this->log_exit_room_debug("LIST_FILTER", "목록에서 나간 채팅방 제외", [...]);
        } else {
            $user_in_room = true;
        }
        break;
    }
}

// 나간 채팅방은 목록에서 제외
if ($user_exited) {
    unset($temp_list[$key]);
    continue;
}
```

**중요 변수**:
- `$user_exited`: 사용자가 나갔는지 여부 (boolean)
- `$user_in_room`: 사용자가 채팅방에 있는지 여부 (boolean)

---

#### 5.2 rm_idx_list 필터링 (room_count 계산용)

**함수**: `get_chat_target_list_recruit()`, `get_chat_target_list_recruit2()`

**로직** (196-201줄):
```php
// 필터링된 rm_idx_list를 문자열로 변환하여 반환 (room_count 계산용)
if (!empty($filtered_rm_idx_list)) {
    $temp_list[$key]["rm_idx_list"] = "(" . implode(",", $filtered_rm_idx_list) . ")";
} else {
    $temp_list[$key]["rm_idx_list"] = "()";
}
```

**역할**:
- 나간 채팅방을 제외한 `rm_idx_list` 생성
- 클라이언트에서 `room_count` 계산 시 정확한 개수 표시

---

### 6단계: 접근 차단 - 나간 채팅방 재입장 방지

#### 6.1 페이지 접근 차단

**파일**: `application/shop/controllers/chat.php`

**함수**: `chat_view()` (164-203줄)
```php
$mongo_room = $collection->findOne($query);

if ($mongo_room) {
    $rm_users = isset($mongo_room["rm_users"]) ? $mongo_room["rm_users"] : [];
    $user_exited = false;
    
    foreach ($rm_users as $room_user) {
        if (isset($room_user["user_id"]) && $room_user["user_id"] == $user_id) {
            if (isset($room_user["exit_yn"]) && $room_user["exit_yn"] == "Y") {
                $user_exited = true;
                break;
            }
        }
    }
    
    // 나간 채팅방이면 접근 차단
    if ($user_exited) {
        echo "<script>alert('나간 채팅방입니다.'); location.href='/chat/chat_list';</script>";
        exit;
    }
}
```

**역할**:
- URL로 직접 접근 시도 시 차단
- 나간 채팅방 페이지 접근 불가

---

#### 6.2 소켓 방 정보 차단

**파일**: `chatting/chat/model/chat_model.js`

**함수**: `getRoom()` (65-83줄)
```javascript
var ret = await $db.cs_room.findOne({rm_idx : params.rm_idx}, ...);

if(ret!=null){
    // 나간 채팅방인지 확인 (exit_yn='Y' 체크)
    var user_exited = false;
    for(var icnt=0;icnt < ret.rm_users.length;icnt++){
        if(ret.rm_users[icnt].user_id==params.con_user_id){
            if(ret.rm_users[icnt].exit_yn && ret.rm_users[icnt].exit_yn == "Y"){
                user_exited = true;
                break;
            }
        }
    }
    
    // 나간 채팅방이면 null 반환 (접근 차단)
    if(user_exited){
        return null;
    }
}
```

**역할**:
- 소켓을 통한 방 정보 조회 시 차단
- 나간 사용자는 방 정보를 받을 수 없음

---

#### 6.3 메시지 전송 차단

**파일**: `chatting/chat/chat_server.js`

**함수**: `message_insert` 이벤트 (431-454줄)
```javascript
const room = await $db.cs_room.findOne({rm_idx: target_rm_idx});
if(room && room.rm_users){
    var user_exited = false;
    for(var icnt=0;icnt < room.rm_users.length;icnt++){
        if(room.rm_users[icnt].user_id == g_user_id){
            if(room.rm_users[icnt].exit_yn && room.rm_users[icnt].exit_yn == "Y"){
                user_exited = true;
                break;
            }
        }
    }
    
    // 나간 채팅방이면 메시지 전송 차단
    if(user_exited){
        socket.emit('message_insert_return', {
            code: "90",
            msg: "나간 채팅방입니다.",
            data: null
        });
        return;
    }
}
```

**역할**:
- 나간 사용자의 메시지 전송 차단
- 일반 메시지와 시스템 메시지 모두 체크

---

## 🔑 중요 변수 정리

### MongoDB 데이터 구조

#### room 컬렉션
```javascript
{
    rm_idx: 123,  // 채팅방 고유 ID
    rm_type: "recruit",  // 채팅방 타입
    rm_status: "ACTIVE" | "CLOSED",  // 채팅방 상태
    rm_users: [  // 참여자 배열
        {
            user_id: "01012345678",  // 사용자 ID
            exit_yn: "Y" | undefined,  // 나가기 여부
            exit_dt: MongoDate,  // 나간 시간
            // ... 기타 사용자 정보
        }
    ],
    rm_messages: [  // 메시지 배열
        {
            mg_type: "system_exit",  // 메시지 타입
            mg_text: "홍길동님이 채팅방을 나갔습니다.",
            mg_reads: [  // 읽음 상태
                {
                    user_id: "01012345678",
                    read_yn: "N"
                }
            ]
        }
    ]
}
```

---

### 프론트엔드 변수

| 변수명 | 타입 | 설명 | 위치 |
|--------|------|------|------|
| `rm_idx` | String/Number | 채팅방 ID | chat_view.php |
| `g_user_id` | String | 현재 사용자 ID | chat_view.php |
| `rm_info` | Object | 채팅방 정보 객체 | chat_view.php |
| `rm_info.rm_users` | Array | 참여자 목록 | chat_view.php |
| `systemMsg` | Object | 시스템 메시지 객체 | chat_view.php |
| `redirectUrl` | String | 리다이렉트 URL | chat_view.php |

---

### 백엔드 변수

| 변수명 | 타입 | 설명 | 위치 |
|--------|------|------|------|
| `$rm_idx` | Integer | 채팅방 ID | chat_proc.php |
| `$user_id` | String | 사용자 ID | chat_proc.php |
| `$mm_idx` | Integer | 회원 인덱스 | chat_proc.php |
| `$user_name` | String | 사용자 이름 | chat_proc.php |
| `$room` | Array | MongoDB 채팅방 정보 | chat_proc.php |
| `$rm_users` | Array | 참여자 배열 | chat_proc.php |
| `$user_found` | Boolean | 사용자 찾음 여부 | chat_proc.php |
| `$all_exited` | Boolean | 모든 사용자 나감 여부 | chat_proc.php |
| `$user_exited` | Boolean | 사용자 나감 여부 | chat_proc.php |
| `$user_in_room` | Boolean | 사용자 참여 여부 | chat_proc.php |
| `$system_message` | Array | 시스템 메시지 객체 | chat_proc.php |
| `$filtered_rm_idx_list` | Array | 필터링된 채팅방 ID 리스트 | chat_proc.php |

---

### 소켓 서버 변수

| 변수명 | 타입 | 설명 | 위치 |
|--------|------|------|------|
| `target_rm_idx` | Number | 대상 채팅방 ID | chat_server.js |
| `g_user_id` | String | 메시지 전송자 ID | chat_server.js |
| `user_exited` | Boolean | 사용자 나감 여부 | chat_server.js |
| `room` | Object | 채팅방 정보 | chat_server.js |

---

## 📚 관련 함수 목록

### 프론트엔드 함수

| 함수명 | 파일 | 줄 번호 | 역할 |
|--------|------|---------|------|
| `exitChatRoom()` | chat_view.php | 119 | 나가기 확인 및 호출 |
| `doExitRoom()` | chat_view.php | 147 | AJAX 호출 및 소켓 전송 |
| `showMessage()` | chat_view.php | 1480 | system_exit 메시지 표시 |

---

### 백엔드 함수

| 함수명 | 파일 | 줄 번호 | 역할 |
|--------|------|---------|------|
| `exit_room()` | chat_proc.php | 2020 | 나가기 처리 메인 함수 |
| `log_exit_room_debug()` | chat_proc.php | 2228 | 디버깅 로그 기록 |
| `get_chat_target_list_recruit()` | chat_proc.php | 14 | 목록 조회 (지원한 공고) |
| `get_chat_target_list_recruit2()` | chat_proc.php | 246 | 목록 조회 (제안받은 공고) |
| `get_chat_target_list_recruit_mem()` | chat_proc.php | 473 | 목록 조회 (회원용) |
| `get_chat_target_list()` | chat_proc.php | 633 | 목록 조회 (일반) |
| `get_chat_target_list2()` | chat_proc.php | 793 | 목록 조회 (제안받은 공고) |
| `get_chat_target_list2_mem()` | chat_proc.php | 928 | 목록 조회 (회원용 제안받은 공고) |
| `chat_view()` | chat.php | 164 | 채팅방 페이지 접근 차단 |

---

### 소켓 서버 함수

| 함수명 | 파일 | 줄 번호 | 역할 |
|--------|------|---------|------|
| `message_insert` (이벤트) | chat_server.js | 414 | 메시지 전송 처리 |
| `getRoom()` | chat_model.js | 65 | 방 정보 조회 (접근 차단) |

---

## 🔍 데이터 흐름 요약

1. **사용자 액션**: 나가기 버튼 클릭
2. **프론트엔드**: 확인 다이얼로그 → AJAX 호출
3. **백엔드**: MongoDB 업데이트 (exit_yn='Y', exit_dt 설정)
4. **백엔드**: 시스템 메시지 생성 및 반환
5. **프론트엔드**: 소켓으로 시스템 메시지 전송
6. **소켓 서버**: 메시지 저장 및 브로드캐스트
7. **다른 사용자**: "XXX님이 채팅방을 나갔습니다." 메시지 수신
8. **목록 조회**: exit_yn='Y'인 채팅방 제외
9. **접근 차단**: 나간 채팅방 재입장 및 메시지 전송 차단

---

## 🛡️ 보안 및 검증 포인트

1. **사용자 검증**: `$user_id`와 `rm_users` 배열의 `user_id` 매칭
2. **중복 처리 방지**: 이미 `exit_yn='Y'`인 경우 성공 응답
3. **빈 배열 처리**: `rm_users`가 비어있는 경우 처리
4. **모든 사용자 나감 처리**: `all_exited` 플래그로 `rm_status='CLOSED'` 설정
5. **접근 차단 다층 방어**: 페이지 접근, 소켓 조회, 메시지 전송 3단계 차단

---

## 📝 디버깅 로그

**로그 파일**: `/website/nowapk/logs/chat_exit_room_YYYY-MM-DD.log`

**로그 단계**:
- `START`: 나가기 시작
- `BEFORE_UPDATE`: 업데이트 전 상태
- `USER_UPDATE`: 사용자 exit_yn 업데이트
- `UPDATE_DATA`: MongoDB 업데이트 데이터
- `AFTER_UPDATE`: 업데이트 후 상태
- `LIST_FILTER`: 목록 필터링
- `SUCCESS`: 나가기 완료
- `ERROR`: 오류 발생
- `ALREADY_EXITED`: 이미 나간 사용자
- `ALL_EXITED`: 모든 사용자 나감

---

## ✅ 체크리스트

나가기 기능이 정상 작동하는지 확인:

- [ ] 나가기 버튼 클릭 시 확인 다이얼로그 표시
- [ ] MongoDB에 `exit_yn='Y'`, `exit_dt` 설정
- [ ] 시스템 메시지 생성 및 전송
- [ ] 다른 사용자에게 "나갔습니다" 메시지 표시
- [ ] 목록에서 나간 채팅방 제외
- [ ] 나간 채팅방 페이지 접근 차단
- [ ] 나간 채팅방 메시지 전송 차단
- [ ] 모든 사용자 나감 시 `rm_status='CLOSED'` 설정
- [ ] `room_count` 정확한 개수 표시

