# FCM 토큰 처리 흐름 문서

## 개요

이 문서는 안드로이드/iOS 앱으로부터 FCM(Firebase Cloud Messaging) 토큰을 받아서 서버에서 처리하는 전체 과정을 설명합니다.

### 전체 흐름 요약

```
앱 (안드로이드/iOS)
  ↓ Firebase SDK로 토큰 발급
  ↓ 네이티브 HTTP 클라이언트로 서버 API 호출
서버 (PHP - update_token 함수)
  ↓ MySQL에 토큰 저장
  ↓ 로그인 상태면 MongoDB에도 동기화
  ↓ Socket.IO 서버에도 전송
채팅 메시지 전송 시
  ↓ MongoDB에서 토큰 조회 (우선)
  ↓ 없으면 MySQL에서 조회
  ↓ FCM으로 푸시 알림 발송
```

---

## 1. 앱 단계: 토큰 발급 및 전송

### 1.1 안드로이드 앱

**토큰 발급:**

- Firebase SDK의 `FirebaseMessagingService.onNewToken()` 또는 `FirebaseMessaging.getInstance().getToken()` 호출
- Google Firebase 서버로부터 FCM 토큰 문자열 수신 (예: `fn8apx8DQ06LDyZC03noUd:APA91b...`)

**서버 전송:**

- 네이티브 HTTP 클라이언트 라이브러리 사용 (OkHttp)
- `POST /common_proc/update_token` API 호출
- 전송 파라미터:
  - `uuid`: 기기 고유 식별자
  - `token`: FCM 토큰 문자열
  - `mm_idx`: (선택) 로그인한 회원 인덱스
- User-Agent: `okhttp/4.9.2`
- Content-Type: `multipart/form-data` 또는 `application/x-www-form-urlencoded`

### 1.2 iOS 앱

**토큰 발급:**

- Firebase SDK의 `Messaging.messaging().fcmToken` 또는 `MessagingDelegate.didReceiveRegistrationToken()` 호출
- Google Firebase 서버로부터 FCM 토큰 문자열 수신

**서버 전송:**

- 네이티브 HTTP 클라이언트 사용 (URLSession/CFNetwork)
- `POST /common_proc/update_token` API 호출
- 전송 파라미터: 안드로이드와 동일
- User-Agent: `nowjobswift/45 CFNetwork/...`
- Content-Type: 주로 `application/x-www-form-urlencoded`

### 1.3 공통 사항

- **웹뷰를 거치지 않음**: 네이티브 앱이 직접 HTTP API를 호출
- **로그인 상태 무관**: 로그인하지 않아도 토큰 등록 가능 (mm_idx=0으로 저장)
- **자동 재시도**: 앱 시작 시, 토큰 갱신 시 자동으로 서버에 전송

### 1.4 FCM 토큰 갱신 조건

Firebase FCM 토큰은 다음 상황에서 자동으로 갱신됩니다:

#### Firebase SDK가 자동으로 토큰 갱신하는 경우:

1. **앱 최초 설치 시**

   - 새로운 기기에 앱이 설치되면 FCM이 고유한 등록 토큰 발급
   - `onNewToken()` 콜백 자동 호출

2. **앱 재설치 시**

   - 앱을 삭제 후 재설치하면 기존 토큰 무효화
   - 새로운 토큰이 자동으로 발급됨

3. **앱 데이터 삭제 시**

   - 사용자가 앱 데이터를 삭제하면 토큰이 갱신됨
   - 안드로이드: 설정 → 앱 → 데이터 삭제
   - iOS: 앱 삭제 후 재설치

4. **기기 복원 시**

   - 백업에서 기기를 복원하면 토큰이 갱신될 수 있음
   - 새 기기로 복원하는 경우

5. **FCM 서버 정책에 의한 갱신**

   - 보안상의 이유로 FCM 서버가 토큰을 무효화하고 새 토큰 발급
   - 장기간 사용되지 않은 토큰
   - 보안 취약점 발견 시

6. **앱 업데이트 시 (특정 경우)**
   - Firebase SDK 버전 업데이트
   - 앱 서명 키 변경
   - Google Play 서비스 업데이트

#### 토큰 갱신 감지 방법:

**안드로이드:**

```java
// FirebaseMessagingService에서 자동 호출
@Override
public void onNewToken(String token) {
    // 새 토큰을 서버에 전송
    sendTokenToServer(token);
}
```

**iOS:**

```swift
// MessagingDelegate에서 자동 호출
func messaging(_ messaging: Messaging, didReceiveRegistrationToken fcmToken: String?) {
    // 새 토큰을 서버에 전송
    sendTokenToServer(fcmToken)
}
```

**중요:**

- ✅ 토큰 갱신은 **Firebase SDK가 자동으로 처리**
- ✅ 앱은 `onNewToken()` 콜백에서 새 토큰을 받아 서버에 전송만 하면 됨
- ✅ 서버는 `update_token()` API로 토큰을 받아 MySQL/MongoDB에 저장
- ⚠️ **토큰이 갱신되면 기존 토큰은 무효화**되므로 서버에 반드시 업데이트해야 함

---

## 2. 서버 단계: update_token() 함수 처리

### 2.1 함수 위치

**파일:** `application/shop/controllers/common_proc.php`  
**함수:** `update_token()` (186줄부터)

### 2.2 처리 순서

#### Step 1: 파라미터 수신 및 검증

```php
$token = input_post("token", "");  // FCM 토큰
$uuid = input_post("uuid", "");    // 기기 UUID
$app_mm_idx = input_post("mm_idx", 0);  // (선택) 앱에서 전송한 회원 인덱스
```

- 토큰 길이 검증 (최소 50자)
- 토큰 형식 검증 (알파벳, 숫자, 특수문자만 허용)
- UUID 필수 확인

#### Step 2: OS 타입 감지

```php
// User-Agent 또는 헤더로 OS 타입 자동 감지
if (preg_match('/nowjobswift|CFNetwork|Darwin/i', $user_agent)) {
    $os_type = 'ios';
} else if (preg_match('/NowJobApp_Android|Android/i', $user_agent)) {
    $os_type = 'android';
}
```

#### Step 3: 사용자 식별

1. **UUID로 기존 디바이스 정보 조회**

   ```php
   $device_info = $this->member_model->getMemberDevice(["uuid" => $uuid]);
   ```

2. **사용자 식별 우선순위:**
   - UUID로 조회한 디바이스의 `mm_idx` (이미 연결된 경우)
   - 세션 정보의 `mm_idx` (웹에서 로그인한 경우)
   - 앱에서 전송한 `app_mm_idx`
   - 없으면 `mm_idx = 0` (로그인 전 상태)

#### Step 4: MySQL에 토큰 저장

**기존 디바이스가 있는 경우:**

```php
$update_data = [
    "token" => $token,
    "push_yn" => "Y",
    "up_dt" => date('Y-m-d H:i:s'),
    "os_type" => $os_type  // 감지된 OS 타입
];

// 로그인 상태면 mm_idx도 함께 업데이트
if ($device_info["mm_idx"] == 0 && $mm_idx > 0) {
    $update_data["mm_idx"] = $mm_idx;  // 자동 연결
}

$this->member_model->setMemberDevice($update_data, ["uuid" => $uuid]);
```

**새 디바이스인 경우:**

```php
$new_device_data = [
    "uuid" => $uuid,
    "token" => $token,
    "push_yn" => "Y",
    "mm_idx" => $mm_idx > 0 ? $mm_idx : 0,  // 로그인 상태면 연결, 아니면 0
    "os_type" => $os_type,
    "reg_dt" => date('Y-m-d H:i:s'),
    "up_dt" => date('Y-m-d H:i:s')
];

$this->member_model->insertMemberDevice($new_device_data);
```

**저장 위치:** MySQL `st_member_device` 테이블

#### Step 5: MongoDB 동기화 (로그인 상태일 때만)

```php
if ($final_mm_idx > 0) {  // 로그인한 사용자인 경우
    $mem_info = $this->member_model->getMember(["mm_idx" => $final_mm_idx]);
    if ($mem_info) {
        $mongo_info = [
            'mm_id' => $mem_info["mm_id"],  // 회원 ID
            'token' => $token,
            'last_update' => new MongoDB\BSON\UTCDateTime()
        ];
        $this->mongo_model->updateUserInfo($mongo_info);
    }
}
```

**저장 위치:** MongoDB `chat.user` 컬렉션 (`mm_id` 기준)

#### Step 6: Socket.IO 서버에도 전송 (로그인 상태일 때만)

```php
if ($final_mm_idx > 0) {
    $socket_data = [
        "user_id" => $mem_info["mm_id"],
        "token" => $token
    ];

    // cURL로 Socket.IO 서버에 전송
    curl_setopt($ch, CURLOPT_URL, "https://nowjob.co.kr:3030/update_token");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($socket_data));
    curl_exec($ch);
}
```

**저장 위치:** Socket.IO 서버의 MongoDB `chat.cs_user` 컬렉션

---

## 3. 데이터 저장 위치 및 구조

### 3.1 MySQL: st_member_device 테이블

**목적:** 기기별 FCM 토큰 관리 (UUID 기준)

**주요 컬럼:**

- `md_idx`: 디바이스 인덱스 (PK)
- `uuid`: 기기 고유 식별자
- `mm_idx`: 회원 인덱스 (0 = 로그인 전, >0 = 로그인한 사용자)
- `token`: FCM 토큰 문자열
- `push_yn`: 푸시 알림 동의 여부 (Y/N)
- `os_type`: 운영체제 타입 (android/ios)
- `reg_dt`: 등록일시
- `up_dt`: 수정일시

**특징:**

- 로그인 전에도 토큰 저장 가능 (`mm_idx = 0`)
- 로그인 시 자동으로 `mm_idx` 업데이트
- 한 기기당 하나의 레코드 (UUID 기준)

### 3.2 MongoDB: chat.user 컬렉션

**목적:** 채팅 시스템에서 사용할 사용자 정보 및 토큰 (mm_id 기준)

**주요 필드:**

- `user_id`: 회원 ID (mm_id)
- `token`: FCM 토큰
- `user_nick`: 사용자 닉네임
- `user_img`: 사용자 이미지
- `last_update`: 마지막 업데이트 시간

**특징:**

- 로그인한 사용자(`mm_idx > 0`)만 저장
- `update_token()` 함수에서 자동 동기화
- 채팅 메시지 전송 시 토큰 조회에 사용

### 3.3 Socket.IO 서버: MongoDB cs_user 컬렉션

**목적:** 실시간 채팅 서버에서 사용할 사용자 정보 및 토큰

**저장 방식:**

- PHP 서버에서 cURL로 Socket.IO 서버 API 호출
- Socket.IO 서버가 자체 MongoDB에 저장

**특징:**

- 로그인한 사용자만 저장
- 실시간 채팅 푸시 발송에 사용

---

## 4. 사용자 연결 처리

### 4.1 자동 연결 로직

`update_token()` 함수 내부에서 자동으로 처리됩니다:

```php
// mm_idx 처리: 로그인한 사용자가 토큰을 업데이트할 때 mm_idx = 0인 경우 자동 연결
if ($device_info["mm_idx"] == 0 && $mm_idx > 0) {
    // 로그인한 사용자가 mm_idx = 0인 디바이스를 업데이트하면 자동 연결
    $update_data["mm_idx"] = $mm_idx;
}
```

**동작 시나리오:**

1. **로그인 전 토큰 등록:**

   - 앱 설치 후 토큰 발급 → `update_token()` 호출
   - `mm_idx = 0`으로 MySQL에 저장
   - MongoDB에는 저장하지 않음 (사용자 식별 불가)

2. **로그인 후 토큰 갱신:**

   - 사용자가 로그인
   - 토큰 갱신 또는 재등록 시 `update_token()` 호출
   - `mm_idx = 0`인 디바이스를 현재 로그인 사용자(`mm_idx`)로 자동 연결
   - MongoDB에도 동기화

3. **이미 연결된 경우:**
   - 기존 `mm_idx` 유지
   - 토큰만 업데이트

### 4.2 로그인 시 연결 (member_proc::login)

로그인 성공 시에도 디바이스 연결을 시도합니다:

```php
// member_proc.php의 login() 함수
$headers = apache_request_headers();
if (isset($headers["uuid"])) {
    $mem_device = $this->member_model->getMemberDevice(["uuid" => $headers["uuid"], "mm_idx" => 0]);
    if ($mem_device != null) {
        $this->member_model->setMemberDevice(["mm_idx" => $mem_info["mm_idx"]], ["uuid" => $headers["uuid"]]);
    }
}
```

---

## 5. 푸시 발송 시 토큰 조회

### 5.1 채팅 메시지 전송 시

**파일:** `chatting/chat/chat_server.js`  
**함수:** `socket.on('message_insert', ...)` (414줄부터)

### 5.2 토큰 조회 순서

#### Step 1: 푸시 대상자 목록 생성

```javascript
// 1. MongoDB 방 정보에서 사용자 목록 가져오기
const room_info = await $exe.chat.getRoom({ rm_idx: target_rm_idx, ... });
const actual_room_info = room_info && room_info.data ? room_info.data : null;

// 2. 현재 접속 중인 사용자 목록 (rm_users 파라미터)
// 3. DB에서 조회한 사용자 목록
// → 모두 합쳐서 중복 제거
```

#### Step 2: 발신자 제외 및 필터링

```javascript
const target_users = all_users.filter((u) => {
  return (
    u.user_id &&
    u.user_id !== g_user_id && // 발신자 제외
    !u.user_id.startsWith("user_") && // 가짜 계정 제외
    !u.user_id.startsWith("test_")
  );
});
```

#### Step 3: FCM 토큰 조회 (우선순위: MongoDB → MySQL)

```javascript
for (const target_user of target_users) {
  // 1순위: MongoDB에서 토큰 조회
  let token = target_user.token || "";

  // 2순위: MongoDB에 없으면 MySQL에서 조회
  if (!token || token === "") {
    token = await new Promise((resolve) => {
      $exe.user.getUserTokenFromMySQL(target_user_id, resolve);
    });
  }

  // MySQL 조회 로직 (user_controller.js)
  // SELECT token FROM st_member_device
  // WHERE mm_idx = (SELECT mm_idx FROM st_member WHERE mm_id = ?)
  // AND push_yn = 'Y'
  // ORDER BY up_dt DESC LIMIT 1
}
```

#### Step 4: 푸시 알림 발송

```javascript
if (token && token !== "" && token !== null) {
  const push = {
    user_id: target_user_id,
    no_subject:
      (target_chat_type === "recruit" ? "채용공고" : "중고거래") +
      " 메시지 알림",
    no_text:
      "[" +
      sender_name +
      "]님의 " +
      (target_chat_type === "recruit" ? "채용공고" : "중고거래") +
      " 메시지가 도착했습니다.",
    no_token: token,
    no_url: "",
    no_result: "",
    reg_dt: $common.getCurrentDate(),
  };

  // DB에 푸시 내역 기록 후 전송
  $exe.alarm.insertNotification(push, function (idx) {
    if (idx && idx !== "[object Object]") {
      send_fcm(idx, push); // Firebase로 푸시 발송
    }
  });
}
```

**중요 사항:**

- ✅ **서버에서 자동 처리**: 클라이언트(앱/웹)에서 토큰을 조회하거나 전송하지 않음
- ✅ **`send_fcm()` 함수 사용**: `chatting/chat/chat_server.js`의 `send_fcm()` 함수(49-91줄)에서 실제 Firebase FCM 발송 처리
- ✅ **플랫폼 공통**: 안드로이드 WebView와 웹 브라우저 모두 Socket.IO를 통해 메시지 전송, 서버에서 자동으로 푸시 발송

### 5.3 토큰 조회 우선순위 요약

1. **MongoDB `chat.user` 컬렉션** (빠른 조회)

   - `user_id`(mm_id)로 조회
   - 채팅 시스템에서 주로 사용

2. **MySQL `st_member_device` 테이블** (백업)
   - MongoDB에 없을 때만 조회
   - `mm_id` → `mm_idx` → `token` 순으로 조회
   - 가장 최신 토큰 사용 (`ORDER BY up_dt DESC LIMIT 1`)

---

## 6. 주요 함수 및 파일 위치

### 6.1 PHP 서버

| 함수/파일           | 위치                                           | 용도                           |
| ------------------- | ---------------------------------------------- | ------------------------------ |
| `update_token()`    | `application/shop/controllers/common_proc.php` | FCM 토큰 등록/갱신 (메인 함수) |
| `setMemberDevice()` | `application/common/models/membermodel.php`    | MySQL 디바이스 정보 저장/수정  |
| `getMemberDevice()` | `application/common/models/membermodel.php`    | MySQL 디바이스 정보 조회       |
| `updateUserInfo()`  | `application/common/models/mongomodel.php`     | MongoDB 사용자 정보 업데이트   |

### 6.2 Node.js 채팅 서버

| 함수/파일                 | 위치                                          | 용도                                         |
| ------------------------- | --------------------------------------------- | -------------------------------------------- |
| `message_insert` 이벤트   | `chatting/chat/chat_server.js`                | 채팅 메시지 전송 시 푸시 발송                |
| `getUserTokenFromMySQL()` | `chatting/chat/controller/user_controller.js` | MySQL에서 토큰 조회                          |
| `updateUserToken()`       | `chatting/chat/model/user_model.js`           | MongoDB에 토큰 저장                          |
| `send_fcm()`              | `chatting/chat/chat_server.js` (49-91줄)      | Firebase로 푸시 발송 (채팅 메시지 푸시 처리) |

---

## 7. 데이터 흐름 다이어그램

```
┌─────────────────┐
│  안드로이드/iOS  │
│      앱         │
└────────┬────────┘
         │ 1. Firebase SDK로 토큰 발급
         │ 2. POST /common_proc/update_token
         │    (uuid, token, mm_idx)
         ↓
┌─────────────────┐
│  PHP 서버       │
│ update_token()  │
└────────┬────────┘
         │
         ├─→ MySQL (st_member_device)
         │   - uuid 기준 저장
         │   - 로그인 전/후 모두 저장
         │
         ├─→ MongoDB (chat.user)
         │   - mm_id 기준 저장
         │   - 로그인 상태일 때만
         │
         └─→ Socket.IO 서버
             - MongoDB (cs_user)
             - 로그인 상태일 때만

채팅 메시지 전송 시:
┌─────────────────┐
│ 채팅 서버        │
│ message_insert   │
└────────┬────────┘
         │
         ├─→ MongoDB에서 토큰 조회 (1순위)
         │
         └─→ MySQL에서 토큰 조회 (2순위)
             │
             └─→ send_fcm() 함수 호출
                 │
                 └─→ Firebase Admin SDK로 푸시 발송
                     │
                     └─→ 푸시 성공/실패 결과 DB 업데이트
```

---

## 8. 주의사항 및 특이사항

### 8.1 로그인 전 토큰 저장

- 로그인하지 않아도 토큰은 MySQL에 저장됨 (`mm_idx = 0`)
- MongoDB에는 저장되지 않음 (사용자 식별 불가)
- 로그인 후 토큰을 갱신하면 자동으로 사용자와 연결됨

### 8.2 토큰 중복 방지

- 같은 토큰이 다른 사용자에게 할당되지 않도록 검증
- 기존 토큰과 사용자가 다르면 오류 반환

### 8.3 OS 타입 자동 감지

- User-Agent 또는 헤더로 안드로이드/iOS 자동 구분
- `st_member_device.os_type` 컬럼에 저장

### 8.4 Socket.IO 서버 동기화

- PHP 서버에서 cURL로 Socket.IO 서버 API 호출
- 실패해도 MySQL/MongoDB 저장은 계속 진행 (최소 하나라도 성공하면 성공 처리)

---

## 9. 로그 확인

모든 처리 과정은 다음 로그 파일에 기록됩니다:

- **파일:** `/website/nowapk/logs/business_error_YYYY-MM-DD.log`
- **함수명:** `update_token`
- **기록 내용:**
  - 함수 진입
  - 받은 파라미터 (uuid, token 길이 등)
  - 사용자 식별 결과
  - MySQL 저장 결과
  - MongoDB 동기화 결과
  - Socket.IO 전송 결과

---

## 10. 채팅 메시지 푸시 알림 처리 상세

### 10.1 send_fcm() 함수

**위치:** `chatting/chat/chat_server.js` (49-91줄)

**역할:** Firebase Admin SDK를 사용하여 실제 FCM 푸시 알림을 발송하는 함수

**호출 위치:** `socket.on('message_insert')` 이벤트 핸들러 내부 (666줄)

**처리 흐름:**

```javascript
function send_fcm(idx, data) {
  // 1. 토큰 유효성 검사
  if (!data["no_token"] || data["no_token"] == "" || data["no_token"] == null) {
    return false;
  }

  // 2. 메시지 내용 정리 (특수 문자 처리)
  var message_text = data["no_text"];
  if (message_text && message_text.includes("[알 수 없음]")) {
    message_text = message_text.replace("[알 수 없음]", "사용자");
  }

  // 3. Firebase FCM 메시지 객체 생성
  const message = {
    notification: {
      title: data["no_subject"],
      body: message_text,
    },
    token: data["no_token"],
  };

  // 4. Firebase로 푸시 발송
  fcm_admin
    .messaging()
    .send(message)
    .then((response) => {
      // 성공: 푸시 내역 업데이트 (send_yn = 'Y')
      $exe.alarm.updateNotification(idx, { send_yn: "Y" }, function (ret) {});
    })
    .catch((error) => {
      // 실패: 푸시 내역 업데이트 (send_yn = 'N', 오류 내용 저장)
      $exe.alarm.updateNotification(
        idx,
        { send_yn: "N", no_result: error },
        function (ret) {}
      );
    });
}
```

**특징:**

- ✅ **채팅 메시지 전용**: 채팅 메시지 전송 시에만 호출됨
- ✅ **자동 호출**: 서버에서 메시지 저장 후 자동으로 호출
- ✅ **에러 처리**: 성공/실패 모두 DB에 기록
- ✅ **플랫폼 독립**: 안드로이드/웹 구분 없이 동일하게 처리

### 10.2 클라이언트에서 토큰 조회하지 않음

**중요:** 채팅 메시지 전송 시 클라이언트(앱/웹)에서 FCM 토큰을 조회하거나 전송하지 않습니다.

**클라이언트 측 코드 (chat_view.php):**

```javascript
// ❌ 실제 전송 로직에 사용되지 않음 (디버깅용만)
if (typeof APP_NOWAPK !== "undefined") {
  var fcm_token = APP_NOWAPK.get_fcm_token(); // 단순 로그 확인
  console.log("FCM 토큰 상태:", fcm_token ? "등록됨" : "미등록");
}
```

**실제 처리:**

- ✅ 서버가 `message_insert` 이벤트 수신 시 자동으로 토큰 조회
- ✅ 서버가 `getUserTokenFromMySQL()` 함수로 MySQL에서 토큰 조회
- ✅ 서버가 `send_fcm()` 함수로 Firebase에 푸시 발송

### 10.3 플랫폼 통합 처리

**안드로이드 WebView와 웹 브라우저 모두 동일:**

- ✅ Socket.IO를 통해 메시지 전송 (`socket.emit("message_insert")`)
- ✅ 서버에서 자동으로 푸시 알림 발송
- ✅ 클라이언트는 메시지 전송만 담당, 푸시는 서버가 처리

---

## 11. 요약

1. **앱에서 토큰 발급**: Firebase SDK가 자동으로 처리
2. **서버로 전송**: 네이티브 HTTP 클라이언트로 `update_token` API 호출
3. **MySQL 저장**: UUID 기준으로 항상 저장 (로그인 전/후 모두)
4. **MongoDB 동기화**: 로그인 상태일 때만 `mm_id` 기준으로 저장
5. **Socket.IO 동기화**: 로그인 상태일 때만 채팅 서버에도 전송
6. **채팅 메시지 전송 시**:
   - 클라이언트는 Socket.IO로 메시지만 전송
   - 서버가 자동으로 FCM 토큰 조회 (MongoDB → MySQL 순)
   - 서버가 `send_fcm()` 함수로 Firebase에 푸시 발송
   - 클라이언트는 토큰을 조회하거나 전송하지 않음

**핵심 포인트:**

- 안드로이드/iOS 모두 동일한 `update_token()` 함수 사용
- 로그인 전에도 토큰 저장 가능, 로그인 시 자동 연결
- 여러 저장소에 중복 저장하여 가용성 확보
- **채팅 메시지 푸시는 서버에서 자동 처리** (`send_fcm()` 함수)
- **클라이언트는 토큰 조회하지 않음** (서버가 자동 조회)
- **안드로이드 WebView와 웹 브라우저 모두 Socket.IO 사용** (플랫폼 통합)
