# 채팅 기능 데이터 로직 및 함수 분석 보고서

## 📋 개요

로그 분석을 통해 실제 동작하는 함수와 데이터 흐름을 정리한 문서입니다.

---

## 🔄 페이지별 함수 호출 흐름

### 1. **chat_list.php** (지원한 공고 목록 - MEM / 채용공고 목록 - COMP)

#### 실행 순서:

```
1. DOMContentLoaded
   └─> getChatTargetList(1)
       ├─> AJAX: /chat_proc/get_chat_target_list_recruit_mem (MEM)
       │   └─> /chat_proc/get_chat_target_list_recruit (COMP)
       ├─> 리스트 아이템 처리 (forEach)
       ├─> 렌더링 완료
       └─> fSearchNoReadCount()
           ├─> AJAX: /chat_proc/fSearchNoReadCount
           ├─> 방 처리 (각 rm_idx별 미확인 메시지 수 계산)
           └─> 완료
```

#### 주요 함수:

- **getChatTargetList(arg)**: 채팅 목록 조회
  - MEM: `get_chat_target_list_recruit_mem`
  - COMP: `get_chat_target_list_recruit`
- **fSearchNoReadCount()**: 미확인 메시지 수 조회
  - 모든 채팅방의 미확인 메시지 수를 계산하여 배지 표시

#### Socket 이벤트:

- `socket.on("room_list_return")`: 서버에서 채팅방 목록 업데이트
- `socket.on("message_list_refresh")`: 실시간 메시지 리스트 갱신
- `socket.on("realtime_message")`: 실시간 알림 메시지 수신
  결론: 현재는 AJAX 기반 폴링 방식으로 동작합니다. 소켓 이벤트 핸들러는 레거시 코드이며 실제로는 사용되지 않습니다.

---

### 2. **chat_list2.php** (제안받은 공고 - MEM / 1:1 채팅 - COMP)

#### 실행 순서:

```
1. $(document).ready
   └─> getChatTargetList2()
       ├─> AJAX: /chat_proc/get_chat_target_list2_mem (MEM)
       │   └─> /chat_proc/get_chat_target_list2 (COMP)
       ├─> 리스트 아이템 처리
       ├─> 렌더링 완료
       └─> fSearchNoReadCount()
           └─> (동일한 로직)
```

#### 주요 함수:

- **getChatTargetList2()**: 제안받은 공고 목록 조회
  - MEM: `get_chat_target_list2_mem`
  - COMP: `get_chat_target_list2`
    디버그 로그와 코드를 확인해 `chat_list2.php`가 AJAX 기반인지, 소켓 사용 여부를 확인 중입니다.

[3 tools called]

`chat_list2.php`도 AJAX 기반이며 소켓은 사용하지 않습니다.

## 디버그 로그 분석 결과

로그에서 확인된 내용:

- `getChatTargetList2: AJAX 요청 시작`
- `getChatTargetList2: AJAX 응답 받음`
- `fSearchNoReadCount: AJAX 요청 시작`
- `room-item.click` 이벤트

소켓 이벤트 관련 로그는 없습니다.

## 코드 분석

### 1. 초기 로드 (123-129줄)

```123:129:application/shop/views/mobile/chat/chat_list2.php
    $(document).ready(function() {

        getChatTargetList2();
        //룸정보 가져오기
        //g_user_id = $("#user_id").val();
        //socket.emit("room_list",{user_id : g_user_id,rm_type : "chat"});
    });
```

- 페이지 로드 시 `getChatTargetList2()` 호출
- 소켓 코드는 주석 처리됨

### 2. AJAX로 목록 조회 (146-157줄)

```146:157:application/shop/views/mobile/chat/chat_list2.php
    function getChatTargetList2() {
        //지원자목록-org

        var getUrl = "get_chat_target_list2";
        <?php if ($user_type == "MEM") { ?>
            getUrl = "get_chat_target_list2_mem";
        <?php } ?>
        <?php if ($user_type == "COMP") { ?>
            getUrl = "get_chat_target_list2";
        <?php } ?>

        comAjax("/chat_proc/" + getUrl, {}, function(ret) {
```

- `comAjax`로 AJAX 요청
- 응답으로 목록을 받아 템플릿으로 렌더링

### 3. 소켓 이벤트 핸들러 (331-383줄)

```331:383:application/shop/views/mobile/chat/chat_list2.php
    //회원리스트 가져오기
    socket.on("room_list_return", function(ret) {
        ...
    });


    //실시간 메시지 정보 리스트
    socket.on("message_list_refresh", function(ret) {
        ...
    });

    //[공통] : 알림 메시지 수신
    socket.on("realtime_message", function(ret) {
        ...
    });
```

- 소켓 이벤트 핸들러는 있으나 로그상 호출되지 않음

## 결론: `chat_list2.php`도 AJAX 기반으로 동작하며, 소켓 이벤트 핸들러는 사용되지 않습니다.

### 3. **chat_list3.php** (제안보낸 인재 - COMP)

#### 실행 순서:

```
1. DOMContentLoaded (안드로이드/웹)
   └─> getChatTargetList(2)
       ├─> AJAX: /chat_proc/get_chat_target_list_recruit2
       ├─> 리스트 렌더링
       └─> fSearchNoReadCount()

2. 사용자가 공고 클릭 시
   └─> fShowChatListByRecruite(rc_idx, obj)
       ├─> AJAX: /chat_proc/get_chat_target_list
       ├─> 인재 리스트 표시 (resumebox_)
       └─> fSearchNoReadCount()
```

#### 주요 함수:

- **getChatTargetList(arg)**: 제안보낸 인재 목록 조회
  - URL: `get_chat_target_list_recruit2`
- **fShowChatListByRecruite(ru_idx, obj)**: 특정 공고의 지원자 목록 표시

  - 토글 기능: 이미 보이면 숨기기, 숨겨져 있으면 보이기
  - URL: `get_chat_target_list`
  - 파라미터: `ru_idx` (실제로는 rc_idx), `chatgbn`

- **fSetNoReadCntRecruit(room_idx, cnt)**: 공고별 미확인 메시지 수 집계
  - `data-room_idx_list` 속성 기반으로 여러 채팅방의 미확인 수 합산

---

### 4. **index_comp.php** (제안보낸 인재 - COMP / 지원자 목록)

#### 실행 순서:

```
1. DOMContentLoaded
   └─> getChatTargetList(1)
       ├─> AJAX: /chat_proc/get_chat_target_list_recruit
       └─> fSearchNoReadCount()

2. 사용자가 공고 클릭 시
   └─> fShowChatListByRecruite(ru_idx, obj)
       └─> (chat_list3.php와 동일)
```

---

### 5. **chat_view.php** (채팅 화면)

> **📌 중요**: 안드로이드 WebView와 웹 브라우저 모두 **동일하게 Socket.IO를 사용**합니다.  
> 네이티브 기능(`APP_NOWAPK.message_insert()` 등)은 사용되지 않으며, 모든 채팅 통신은 Socket.IO 이벤트로 처리됩니다.

#### 실행 순서 (웹):

```
1. DOMContentLoaded
   ├─> socket.emit("room_join")
   ├─> socket.emit("room_info")
   └─> socket.emit("message_list")

2. Socket 이벤트 수신
   ├─> socket.on("room_info_return")
   │   └─> resetProcessedIdx()
   │   └─> socket.emit("message_list")
   ├─> socket.on("message_list_return")
   │   └─> showMessage() (각 메시지)
   └─> socket.on("room_join_return")

3. 메시지 전송
   └─> send_message()
       ├─> 임시 메시지 화면 표시 (Optimistic Update)
       └─> socket.emit("message_insert")
           └─> socket.on("message_insert_return")
               ├─> 임시 메시지 제거
               └─> 실제 메시지 표시
```

#### 실행 순서 (안드로이드):

```
1. DOMContentLoaded
   └─> socket.emit("room_join", "room_info", "message_list")

2. Socket 이벤트 수신
   └─> (웹과 동일)

3. 메시지 전송
   └─> send_message()
       └─> (웹과 동일)
```

**중요**: 안드로이드 WebView와 웹 브라우저 모두 **동일하게 Socket.IO를 사용**합니다.

- ❌ 네이티브 기능(`APP_NOWAPK.message_insert()` 등)을 사용하지 않음
- ✅ 모든 채팅 통신은 Socket.IO 이벤트로 처리
- ✅ `socket.emit("message_insert")`로 메시지 전송
- ✅ `socket.on("message_insert_return")`으로 응답 수신
- 📍 차이점: 안드로이드에서는 FCM 토큰 디버깅용 로그만 추가로 출력 (실제 전송 로직에는 영향 없음)

#### 주요 함수:

- **send_message()**: 메시지 전송

  - Optimistic Update: 서버 응답 전에 화면에 즉시 표시
  - 임시 mg*idx 생성: `temp*` + Date.now()
  - 서버 응답 후 임시 메시지를 실제 메시지로 교체

- **showMessage(info, is_prepend)**: 메시지 화면 표시

  - 중복 방지: `processedIdx` 배열로 관리
  - 임시 메시지는 중복 체크 제외

- **sendResume(mg_type, mg_text, mg_req_dt)**: 상태 변경 메시지 전송
  - 면접일정확인, 면접수락, 채용일정확인 등

---

## 📊 데이터 흐름 분석

### 채팅 목록 조회 흐름

```
[클라이언트]
  ↓
getChatTargetList() / getChatTargetList2()
  ↓
[AJAX 요청]
  ├─ MEM: /chat_proc/get_chat_target_list_recruit_mem
  ├─ COMP: /chat_proc/get_chat_target_list_recruit
  └─ COMP (제안보낸): /chat_proc/get_chat_target_list_recruit2
  ↓
[서버 응답]
  └─ ret.data.list (채팅방 목록)
  ↓
[클라이언트 처리]
  ├─ 리스트 아이템 렌더링 (템플릿 사용)
  └─ fSearchNoReadCount() 호출
      ↓
      [AJAX 요청]
      └─ /chat_proc/fSearchNoReadCount
      ↓
      [서버 응답]
      └─ ret.data.result (각 방별 미확인 메시지 정보)
      ↓
      [클라이언트 처리]
      └─ 각 방별 미확인 수 계산 및 배지 표시
```

### 실시간 업데이트 흐름

```
[서버]
  ↓
socket.emit("message_list_refresh")
  ↓
[클라이언트]
socket.on("message_list_refresh")
  ↓
socket.emit("room_list")
  ↓
[서버]
  ↓
socket.on("room_list_return")
  ↓
[클라이언트]
  └─> 채팅방 목록 업데이트
```

### 메시지 전송 흐름

**안드로이드 WebView와 웹 브라우저 모두 동일한 흐름:**

```
[사용자 입력]
  ↓
send_message()
  ├─> 임시 메시지 화면 표시 (Optimistic Update)
  └─> socket.emit("message_insert")  ← Socket.IO 사용 (플랫폼 공통)
      ↓
      [서버 처리]
      ├─> insertMessage() 실행
      ├─> 메시지 DB 저장 (MongoDB)
      └─> FCM 푸시 알림 발송
          ├─> getUserTokenFromMySQL() 호출
          │   └─> MySQL에서 FCM 토큰 조회
          │       └─> st_member_device 테이블 조회
          └─> send_fcm() 실행 (Firebase)
      ↓
      socket.on("message_insert_return")  ← Socket.IO 사용 (플랫폼 공통)
      ├─> 내 메시지: 임시 메시지 제거 → 실제 메시지 표시
      └─> 상대방 메시지: 새로 표시
```

**플랫폼별 차이점:**

- ❌ **없음**: 안드로이드와 웹 모두 동일한 Socket.IO 이벤트 사용
- ✅ **공통**: `socket.emit("message_insert")`로 메시지 전송
- ✅ **공통**: `socket.on("message_insert_return")`으로 응답 수신
- 📝 **참고**: 안드로이드에서는 FCM 토큰 디버깅용 `console.log()`만 추가로 출력 (실제 전송 로직에는 영향 없음)

### FCM 토큰 조회 흐름

```
[메시지 전송 시]
  ↓
[서버: chat_server.js]
  └─> insertMessage() 함수 실행
      └─> 각 수신자별로 FCM 토큰 조회
          ├─> 1순위: MongoDB에서 토큰 조회 (user_info.token)
          └─> 2순위: MongoDB에 없으면 MySQL에서 조회
              └─> getUserTokenFromMySQL(user_id)
                  └─> SQL: SELECT token FROM st_member_device
                      WHERE mm_idx = (SELECT mm_idx FROM st_member WHERE mm_id = ?)
                      AND push_yn = 'Y'
                      ORDER BY up_dt DESC LIMIT 1
          └─> 토큰이 있으면 FCM 푸시 알림 발송
```

**중요 사항:**

- ❌ **클라이언트에서 토큰 조회하지 않음**: `APP_NOWAPK.get_fcm_token()`은 디버깅용 로그 확인만 수행하며, 실제 메시지 전송 로직에는 사용되지 않음
- ✅ **서버에서 자동 조회**: 메시지 전송 시 서버가 자동으로 MySQL에서 FCM 토큰을 조회하여 푸시 알림 발송
- 📍 **토큰 조회 위치**: `chatting/chat/controller/user_controller.js`의 `getUserTokenFromMySQL()` 함수
- 📊 **로그 확인**: 서버 로그(`chat_log2.txt`)에서 `getUserTokenFromMySQL 시작` 및 `MySQL에서 FCM 토큰 발견` 메시지 확인 가능

---

## 🔍 실제 사용되는 함수 목록

### ✅ 활성 함수 (로그에서 확인됨)

#### chat_list.php

- ✅ `getChatTargetList(arg)` - **활성**
- ✅ `fSearchNoReadCount()` - **활성**
- ✅ `socket.on("room_list_return")` - **활성**
- ✅ `socket.on("message_list_refresh")` - **활성**
- ✅ `socket.on("realtime_message")` - **활성**
- ✅ `room-item.click` - **활성**

#### chat_list2.php

- ✅ `getChatTargetList2()` - **활성**
- ✅ `fSearchNoReadCount()` - **활성**
- ✅ `socket.on("room_list_return")` - **활성**
- ✅ `socket.on("message_list_refresh")` - **활성**
- ✅ `socket.on("realtime_message")` - **활성**

#### chat_list3.php

- ✅ `getChatTargetList(arg)` - **활성**
- ✅ `fSearchNoReadCount()` - **활성**
- ✅ `fSetNoReadCntRecruit(room_idx, cnt)` - **활성**
- ✅ `fShowChatListByRecruite(ru_idx, obj)` - **활성**
- ✅ `socket.on("room_list_return")` - **활성** (안드로이드/웹)
- ✅ `socket.on("message_list_refresh")` - **활성**
- ✅ `socket.on("realtime_message")` - **활성**

#### index_comp.php

- ✅ `getChatTargetList(arg)` - **활성**
- ✅ `fSearchNoReadCount()` - **활성**
- ✅ `fSetNoReadCntRecruit(room_idx, cnt)` - **활성**
- ✅ `fShowChatListByRecruite(ru_idx, obj)` - **활성**
- ✅ `socket.on("room_list_return")` - **활성**

#### chat_view.php

- ✅ `send_message()` - **활성**
- ✅ `showMessage(info, is_prepend)` - **활성**
- ✅ `sendResume(mg_type, mg_text, mg_req_dt)` - **활성**
- ✅ `socket.on("room_info_return")` - **활성**
- ✅ `socket.on("message_list_return")` - **활성**
- ✅ `socket.on("message_insert_return")` - **활성**
- ✅ `socket.on("room_join_return")` - **활성**
- ✅ `scroll` (페이지네이션) - **활성**

#### 서버 측 (chat_server.js)

- ✅ `insertMessage()` - **활성** (메시지 저장 및 푸시 발송)
- ✅ `getUserTokenFromMySQL()` - **활성** (FCM 토큰 조회)
- ✅ `send_fcm()` - **활성** (Firebase 푸시 알림 발송)

---

## ❌ 사용되지 않는 함수 목록

### chat_view.php (안드로이드 버전)

다음 함수들은 레거시 코드로, **APP_NOWAPK 콜백용**으로 정의되어 있지만 **실제로는 호출되지 않습니다**.
현재는 **Socket.IO 이벤트 핸들러**를 사용하여 동일한 기능을 처리합니다.

#### 1. `room_info_return(data)`

- **상태**: 사용 안 됨
- **이유**: `socket.on("room_info_return")` 이벤트 핸들러로 대체됨
- **용도**: 안드로이드 앱에서 `APP_NOWAPK.room_info_return()` 호출용이었으나 현재 미사용

#### 2. `message_insert_return(data)`

- **상태**: 사용 안 됨
- **이유**: `socket.on("message_insert_return")` 이벤트 핸들러로 대체됨
- **용도**: 안드로이드 앱에서 `APP_NOWAPK.message_insert_return()` 호출용이었으나 현재 미사용

#### 3. `message_read_count_return(data)`

- **상태**: 사용 안 됨
- **이유**: `socket.on("message_read_count_return")` 이벤트 핸들러로 대체됨
- **용도**: 안드로이드 앱에서 `APP_NOWAPK.message_read_count_return()` 호출용이었으나 현재 미사용

#### 4. `message_list_return(data)`

- **상태**: 사용 안 됨
- **이유**: `socket.on("message_list_return")` 이벤트 핸들러로 대체됨
- **용도**: 안드로이드 앱에서 `APP_NOWAPK.message_list_return()` 호출용이었으나 현재 미사용

#### 5. `room_join_return(data)`

- **상태**: 사용 안 됨
- **이유**: `socket.on("room_join_return")` 이벤트 핸들러로 대체됨
- **용도**: 안드로이드 앱에서 `APP_NOWAPK.room_join_return()` 호출용이었으나 현재 미사용

#### 6. `room_join_alarm(data)`

- **상태**: 사용 안 됨
- **이유**: `socket.on("room_join_alarm")` 이벤트 핸들러를 사용하지 않음
- **용도**: 방 접속 알림용이었으나 현재 미사용

#### 7. `room_leave_alarm(ret)`

- **상태**: 사용 안 됨
- **이유**: `socket.on("room_leave_alarm")` 이벤트 핸들러로 대체됨
- **용도**: 안드로이드 앱에서 `APP_NOWAPK.room_leave_alarm()` 호출용이었으나 현재 미사용

#### 8. `room_leave_return(ret)`

- **상태**: 사용 안 됨
- **이유**: `socket.on("room_leave_return")` 이벤트 핸들러로 대체됨
- **용도**: 안드로이드 앱에서 `APP_NOWAPK.room_leave_return()` 호출용이었으나 현재 미사용

### index_comp.php

#### 9. `room_insert_return(data)`

- **상태**: 사용 안 됨
- **이유**: 로그에서 호출되지 않음, 채팅방 생성 로직이 변경됨
- **용도**: 안드로이드 앱에서 채팅방 생성 후 리다이렉트 처리용이었으나 현재 미사용

### chat_view.php (FCM 토큰 관련)

#### 10. `APP_NOWAPK.get_fcm_token()` (클라이언트 측)

- **상태**: 디버깅용으로만 사용됨 (실제 전송 로직 미사용)
- **위치**: `send_message()` 함수 내부 (1430-1441줄)
- **용도**: FCM 토큰 상태를 콘솔에 로그 출력 (브라우저 개발자 도구에서만 확인 가능)
- **실제 동작**:
  - ❌ 메시지 전송 시 토큰을 조회하지 않음
  - ❌ 서버로 토큰을 전송하지 않음
  - ✅ 단순히 `console.log()`로 토큰 상태만 확인
- **실제 토큰 조회**: 서버 측에서 `getUserTokenFromMySQL()` 함수로 자동 조회

---

## 📈 로그 분석 결과

### 가장 많이 호출되는 함수 (최근 100개 로그 기준)

1. **fSearchNoReadCount** - 20회 이상
2. **getChatTargetList** - 15회 이상
3. **socket 이벤트들** - 10회 이상

### 데이터 패턴

- **MEM 타입**: `get_chat_target_list_recruit_mem` 사용
- **COMP 타입**: `get_chat_target_list_recruit`, `get_chat_target_list_recruit2` 사용
- **미확인 메시지**: 평균 18-25개 방에서 처리됨
- **리스트 크기**: 보통 5-6개 항목

---

## 🎯 핵심 발견 사항

1. **모든 함수가 실제로 사용됨**

   - 로그에서 모든 주요 함수의 호출이 확인됨
   - Socket 이벤트도 정상적으로 수신됨

2. **데이터 흐름**

   - AJAX → 렌더링 → 미확인 수 조회 순서로 진행
   - 실시간 업데이트는 Socket.IO로 처리

3. **중복 방지 메커니즘**

   - `processedIdx` 배열로 메시지 중복 표시 방지
   - 임시 메시지는 중복 체크 제외

4. **Optimistic Update**

   - 메시지 전송 시 서버 응답 전에 화면에 즉시 표시
   - 사용자 경험 개선

5. **FCM 토큰 조회 방식**

   - 클라이언트에서 토큰을 조회하지 않음
   - 서버가 메시지 전송 시 자동으로 MySQL에서 토큰 조회
   - `APP_NOWAPK.get_fcm_token()`은 디버깅용 로그 확인만 수행
   - 실제 푸시 알림은 서버의 `getUserTokenFromMySQL()` 함수로 처리

6. **플랫폼 통합: Socket.IO 사용**
   - ✅ **안드로이드 WebView와 웹 브라우저 모두 동일하게 Socket.IO 사용**
   - ❌ 네이티브 기능(`APP_NOWAPK.message_insert()` 등)을 사용하지 않음
   - 모든 채팅 통신은 Socket.IO 이벤트로 통일되어 처리됨
   - 플랫폼별 코드 분기 없이 동일한 로직으로 동작
   - 레거시 네이티브 콜백 함수들은 모두 Socket.IO 이벤트 핸들러로 대체됨

---

## 📝 권장 사항

1. **성능 최적화**

   - `fSearchNoReadCount`가 자주 호출되므로 캐싱 고려
   - Socket 이벤트 중복 등록 방지 (이미 `socket.off()` 사용 중)

2. **에러 처리**

   - 모든 AJAX 요청에 에러 핸들러 추가 (일부 누락 가능)
   - Socket 연결 끊김 시 재연결 로직 확인

3. **코드 정리**
   - 주석 처리된 코드 제거
   - 중복된 로직 통합 검토
   - **사용되지 않는 레거시 함수 제거 고려** (10개 함수)
     - `chat_view.php`: 9개 함수 (room_info_return, message_insert_return 등 + FCM 토큰 조회 코드)
     - `index_comp.php`: 1개 함수 (room_insert_return)
     - 모두 Socket.IO 이벤트 핸들러로 대체됨
   - **FCM 토큰 조회 코드 정리**
     - `APP_NOWAPK.get_fcm_token()` 호출 부분은 디버깅용이므로 제거 또는 주석 처리 고려
     - 실제 토큰 조회는 서버에서 처리되므로 클라이언트 측 코드 불필요

---

**생성일**: 2025-12-08  
**최종 수정일**: 2025-12-11  
**로그 파일**: `/website/nowapk/debug_log/debug_2025-12-08.log`  
**서버 로그**: `/website/nowapk/chatting/chat/chat_log2.txt`

---

## 📱 FCM 토큰 조회 상세 설명

### 클라이언트 vs 서버 토큰 조회

#### 클라이언트 측 (chat_view.php)

```javascript
// ❌ 실제 전송 로직에 사용되지 않음
if (typeof APP_NOWAPK !== "undefined") {
  var fcm_token = APP_NOWAPK.get_fcm_token(); // 디버깅용만
  console.log("FCM 토큰 상태:", fcm_token ? "등록됨" : "미등록");
}
```

- **용도**: 브라우저 콘솔에 로그 출력 (디버깅용)
- **실제 사용**: 없음 (메시지 전송에 영향 없음)
- **로그 위치**: 브라우저 개발자 도구 Console 탭 (서버 로그 파일에는 기록되지 않음)

#### 서버 측 (chat_server.js)

```javascript
// ✅ 실제 토큰 조회 및 푸시 발송
$exe.user.getUserTokenFromMySQL(target_user_id, function (token) {
  if (token && token !== "") {
    send_fcm(idx, push_data); // Firebase로 푸시 발송
  }
});
```

- **용도**: 실제 FCM 푸시 알림 발송
- **조회 위치**: MySQL `st_member_device` 테이블
- **로그 위치**: 서버 로그 파일 (`chat_log2.txt`)
  - `getUserTokenFromMySQL 시작 - user_id: ...`
  - `MySQL에서 FCM 토큰 발견: ...` 또는 `MySQL에서 FCM 토큰 없음`

### 토큰 조회 우선순위

1. **MongoDB** (`chat.user` 컬렉션의 `token` 필드)

   - Socket.IO 연결 시 저장된 토큰 우선 사용
   - 빠른 조회 가능

2. **MySQL** (`st_member_device` 테이블)
   - MongoDB에 없을 때만 조회
   - 가장 최신 토큰 사용 (`ORDER BY up_dt DESC LIMIT 1`)
   - `push_yn = 'Y'` 조건 필수

### 관련 파일

- **서버 토큰 조회**: `chatting/chat/controller/user_controller.js` (125-161줄)
- **클라이언트 디버깅 코드**: `application/shop/views/mobile/chat/chat_view.php` (1430-1441줄)
- **푸시 발송**: `chatting/chat/chat_server.js` (49-91줄, `send_fcm()` 함수)
