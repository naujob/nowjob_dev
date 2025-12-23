# 채팅 데이터 표시 문제 분석 및 Mobile 데이터 흐름 정리

## 1. 데이터 표시가 안되었던 이유

### 문제 원인

**Web 버전 (`chatting_cont.php`)에서 데이터가 표시되지 않았던 이유:**

1. **`room_info_return` 이벤트가 발생하지 않음**
   - `socket.emit("room_info")` 호출 후 `room_info_return` 이벤트가 서버에서 발생하지 않음
   - 이로 인해 `get_chat_info` API가 호출되지 않음
   - 결과적으로 채용정보/인재정보가 표시되지 않음

2. **하드코딩된 예시 데이터**
   - HTML에 하드코딩된 예시 데이터가 있어서 JavaScript로 데이터를 채우지 않으면 빈 값이 표시됨
   - 예: "인천 연수구 14만원 칸막이일", "내국인만", "60세 이하" 등

3. **탭 정보 전달 누락**
   - `get_chat_info` API 호출 시 `chat_tab_id` 파라미터가 전달되지 않아서
   - 각 탭(지원한 공고, 제안받은 공고, 제안받은 인재, 제안보낸 인재)에 맞는 데이터를 구분할 수 없었음

### 해결 방법

1. **`message_list_return` 이벤트에서 `get_chat_info` 호출 추가**
   - `room_info_return` 이벤트가 발생하지 않는 경우를 대비하여
   - `message_list_return` 이벤트 핸들러에서도 `get_chat_info` API를 호출하도록 수정

2. **하드코딩된 데이터 제거**
   - HTML에서 예시 데이터를 모두 제거하고 빈 값으로 변경
   - JavaScript로 동적으로 데이터를 채우도록 수정

3. **`chat_tab_id` 파라미터 추가**
   - `get_chat_info` API 호출 시 현재 탭 정보(`chat_tab_id`)를 전달
   - 백엔드에서 탭에 맞는 데이터를 반환하도록 수정

---

## 2. Mobile 버전 (`chat_view.php`) 데이터 흐름

### 전체 구조

Mobile 버전은 **서버 사이드 렌더링(SSR)** 방식을 사용합니다.
- PHP 컨트롤러에서 데이터를 미리 로드하여 뷰에 전달
- 뷰 파일에서 PHP 변수를 직접 사용하여 HTML 렌더링
- JavaScript는 메시지 리스트 로드 및 실시간 채팅 처리만 담당

### 데이터 흐름 순서

#### 1단계: 페이지 요청 및 컨트롤러 처리
**파일:** `application/shop/controllers/chat.php` → `chat_view()` 함수

```php
function chat_view() {
    // 1. URL 파라미터에서 rm_idx 추출
    $rm_idx = input_get("rm_idx");
    
    // 2. 채팅방 정보 조회
    $room = $this->common_model->getRoom(["rm_idx" => $rm_idx]);
    
    // 3. 채용공고/이력서 정보 조회 (rm_type이 "recruit"인 경우)
    if ($room["rm_type"] == "recruit") {
        $data["recruit"] = $this->member_model->getRecruitDetail($room["rc_idx"], $this->mm_idx);
        $data["resume"] = $this->member_model->getResumeDetail($room["ru_idx"], $this->mm_idx);
        $data["career_list"] = $this->member_model->getResumeCareerList(["ru_idx" => $room["ru_idx"]]);
        $data["certi_list"] = $this->member_model->getResumeCertiList(["ru_idx" => $room["ru_idx"]]);
    }
    
    // 4. 뷰에 데이터 전달
    $data["user_type"] = $this->user->getUserType();
    $data["rm_idx"] = $rm_idx;
    $data["room"] = $room;
    
    // 5. 뷰 렌더링
    $this->layout->shop('/chat/chat_view', $data);
}
```

**처리 내용:**
- 채팅방 정보(`$room`) 조회
- 채용공고 정보(`$data["recruit"]`) 조회 (MEM 타입인 경우)
- 이력서 정보(`$data["resume"]`) 조회 (COMP 타입인 경우)
- 경력/자격증 리스트 조회

---

#### 2단계: 뷰 파일에서 PHP 변수로 데이터 렌더링
**파일:** `application/shop/views/mobile/chat/chat_view.php` (462-786줄)

**MEM 타입 (개인회원) - 지원한 공고 표시:**
```php
<?php if ($user_type == "MEM") : ?>
    <?php
    // 이미지 경로 설정
    $recruitThumb = getRecruitImg($recruit);
    
    // 가격 타입 처리
    $price_type_display = getPriceType($recruit["rc_price_type"]);
    
    // 급여 처리
    $price_pay_display = getRecuitPrice($recruit);
    
    // 근무 타입 처리
    $work_type_display = getWorkType($recruit["rc_work_type"], "span");
    
    // 주소 처리
    $addr_display = !empty($recruit["ad_addr"]) ? $recruit["ad_addr"] : "";
    
    // 국적 표시 처리
    $nation_display = $recruit["rc_nation_nm"];
    ?>
    <div class="chat-recruit-content-li recomm">
        <!-- 채용공고 정보를 PHP 변수로 직접 표시 -->
        <img src="<?= $recruitThumb ?>" alt="">
        <div class="chat-recruit-title"><?= $recruit["rc_name"] ?></div>
        <p><?= $nation_display ?></p>
        <p><?= $recruit["rc_age_nm"] ?></p>
        <!-- ... -->
    </div>
<?php endif; ?>
```

**COMP 타입 (기업회원) - 인재 정보 표시:**
```php
<?php else : ?>
    <?php
    // 이미지 경로 설정
    $resumeThumb = getResumeImg($resume);
    
    // 근무희망요일 처리
    $week_display = getWeekYoil($resume["ru_week"]);
    
    // 가격 타입 처리
    $price_type_display = getPriceType($resume["ru_price_type"]);
    
    // 근무 타입 처리
    $work_type_display = getWorkType($resume["ru_work_type"]);
    ?>
    <div class="chat-resume-content-li recomm">
        <!-- 인재 정보를 PHP 변수로 직접 표시 -->
        <img src="<?= $resumeThumb ?>" alt="">
        <div class="chat-resume-title"><?= $resume["ru_name"] ?></div>
        <p><?= $resume["mm_nation"] ?></p>
        <p><?= $resume["ma_age"] ?>세</p>
        <!-- ... -->
    </div>
<?php endif; ?>
```

**처리 내용:**
- PHP 헬퍼 함수로 데이터 포맷팅 (`getRecruitImg`, `getPriceType`, `getWorkType` 등)
- HTML에 PHP 변수를 직접 출력하여 데이터 표시
- 서버 사이드에서 모든 데이터 처리 완료

---

#### 3단계: JavaScript 초기화 및 Socket.IO 연결
**파일:** `application/shop/views/mobile/chat/chat_view.php` (2750-2784줄)

```javascript
// 웹에서 실행
window.addEventListener('DOMContentLoaded', function(e) {
    // 1. 사용자 ID 확인
    g_user_id = ensureValidUserId();
    
    // 2. Socket.IO 이벤트: 채팅방 입장
    socket.emit("room_join", {
        rm_idx: Number(rm_idx),
        con_user_id: g_user_id
    });
    
    // 3. Socket.IO 이벤트: 채팅방 정보 요청
    socket.emit("room_info", {
        rm_idx: Number(rm_idx),
        con_user_id: g_user_id
    });
    
    // 4. Socket.IO 이벤트: 메시지 리스트 요청
    var params = {
        rm_idx: Number(rm_idx),
        skip: 0,
        limit: 1000
    };
    socket.emit("message_list", params);
});
```

**처리 내용:**
- 페이지 로드 시 Socket.IO 연결
- 채팅방 입장 및 정보 요청
- 메시지 리스트 요청

---

#### 4단계: Socket.IO 이벤트 핸들러 처리
**파일:** `application/shop/views/mobile/chat/chat_view.php` (2787-2818줄)

```javascript
// room_info_return 이벤트 수신
socket.on("room_info_return", function(ret) {
    if (ret.code != "00") {
        alert("데이터 조회시 문제가 발생하였습니다.");
    } else {
        // 채팅방 정보 저장
        rm_info = ret.data;
        
        // 메시지 리스트 조회
        var params = {
            rm_idx: Number(rm_idx),
            skip: rm_start,
            limit: rm_length
        };
        socket.emit("message_list", params);
    }
});

// message_list_return 이벤트 수신
socket.on("message_list_return", function(ret) {
    // 메시지 리스트 가져오기
    var message_list = ret.data;
    for (var icnt = 0; icnt < message_list.length; icnt++) {
        showMessage(message_list[icnt].rm_messages, true);
    }
    if (pagging_yn == false) {
        scrollBottom();
    }
    pagging_yn = false;
});
```

**처리 내용:**
- `room_info_return`: 채팅방 정보 수신 후 메시지 리스트 요청
- `message_list_return`: 메시지 리스트 수신 후 화면에 표시
- **중요:** Mobile 버전은 채용정보/인재정보를 PHP로 이미 렌더링했으므로
  JavaScript에서는 메시지 처리만 담당

---

### 함수 실행 순서 (Mobile 버전)

```
1. 사용자 액션
   └─> URL 접근: /chat/chat_view?rm_idx=844

2. 서버 사이드 처리 (PHP)
   ├─> Chat::chat_view() 컨트롤러 실행
   │   ├─> getRoom() - 채팅방 정보 조회
   │   ├─> getRecruitDetail() - 채용공고 정보 조회 (MEM 타입)
   │   ├─> getResumeDetail() - 이력서 정보 조회 (COMP 타입)
   │   └─> getResumeCareerList() / getResumeCertiList() - 경력/자격증 조회
   │
   └─> chat_view.php 뷰 파일 렌더링
       ├─> PHP 변수로 채용정보/인재정보 HTML 생성
       └─> JavaScript 코드 포함

3. 클라이언트 사이드 처리 (JavaScript)
   ├─> DOMContentLoaded 이벤트
   │   ├─> socket.emit("room_join") - 채팅방 입장
   │   ├─> socket.emit("room_info") - 채팅방 정보 요청
   │   └─> socket.emit("message_list") - 메시지 리스트 요청
   │
   ├─> socket.on("room_info_return")
   │   └─> rm_info 저장 후 message_list 요청
   │
   └─> socket.on("message_list_return")
       └─> showMessage() 함수로 메시지 화면에 표시
```

---

## 3. Web vs Mobile 차이점

| 구분 | Web 버전 (`chatting_cont.php`) | Mobile 버전 (`chat_view.php`) |
|------|-------------------------------|------------------------------|
| **데이터 로딩 방식** | 클라이언트 사이드 (JavaScript) | 서버 사이드 (PHP) |
| **채용정보/인재정보 표시** | JavaScript로 동적 생성 | PHP로 서버에서 렌더링 |
| **데이터 소스** | `get_chat_info` API 호출 | 컨트롤러에서 직접 조회 |
| **초기 로딩** | 빈 HTML → JavaScript로 채움 | 서버에서 완성된 HTML 제공 |
| **Socket.IO 사용** | 채팅방 정보 + 메시지 리스트 | 메시지 리스트만 |
| **탭 정보** | `chat_tab_id`로 탭 구분 필요 | URL 파라미터로 구분 |
| **HTML 구조** | `chat-recruit-content-li` / `chat-resume-content-li` | `chat-recruit-content-li` / `chat-resume-content-li` |
| **CSS 스타일** | `chat-view-header .chat-recruit-*` / `chat-view-header .chat-resume-*` | `chat-view-header .chat-recruit-*` / `chat-view-header .chat-resume-*` |
| **데이터 정리** | JavaScript에서 `fRemoveBigAdrJS()` 등 함수 사용 | PHP에서 헬퍼 함수로 처리 |

---

## 4. 해결된 문제 요약

### Before (문제 상황)
- `room_info_return` 이벤트가 발생하지 않아 `get_chat_info` API가 호출되지 않음
- 하드코딩된 예시 데이터만 표시됨
- 각 탭에 맞는 데이터가 표시되지 않음

### After (해결 후)
- `message_list_return` 이벤트에서도 `get_chat_info` API 호출
- 하드코딩된 데이터 제거, JavaScript로 동적 생성
- `chat_tab_id` 파라미터 전달로 각 탭에 맞는 데이터 표시
- 모든 탭에서 정상적으로 데이터 표시 확인

### 최근 개선 사항 (2024년 업데이트)

1. **HTML 구조 통일**
   - Web 버전의 `top_recruit` 구조를 Mobile 버전(`chat_view.php`)과 동일하게 변경
   - 채용정보: `chat-recruit-content-li` 클래스 사용
   - 인재정보: `chat-resume-content-li` 클래스 사용
   - 동일한 필드 항목 표시 (이미지, 직종 아이콘, 제목, 국적/나이, 주소, 가격 타입/급여, 근무 타입)

2. **CSS 스타일 통일**
   - Mobile 버전과 동일한 CSS 스타일 적용
   - `chat-view-header .chat-recruit-*` 선택자 사용
   - `chat-view-header .chat-resume-*` 선택자 사용
   - main.css 참조하지 않고 `chatting_cont.php`에 직접 작성

3. **데이터 정리 함수 적용**
   - 주소 처리: `fRemoveBigAdrJS()` 함수로 주소 정리 (시/도명 축약, 구/동 처리)
   - 국적 처리: "무관" 또는 "내국인,외국인" → "국적무관"으로 변환
   - 근무 타입 변환: `<span>` → `<em>` 태그로 변환하고 클래스 추가 (wtype1, wtype2, wtype3)
   - 나이 처리: 0세인 경우 "무관"으로 표시

4. **UI/UX 개선**
   - 북마크 버튼 주석 처리 (웹 채팅 팝업에서는 사용 안 함)
   - `top_recruit` 클릭 시 상세페이지로 이동 기능 추가
   - `chat-recruit-desc` 내부 요소 간격 일정하게 조정 (gap: 4px)

5. **중복 호출 방지**
   - `ajax_first_num` 초기화로 "데이터 처리중입니다." 알림 방지
   - `comAjaxV2` 호출 전 `ajax_first_num = 0` 설정

---

## 5. 참고 사항

### Mobile 버전의 장점
- 초기 로딩 시 이미 데이터가 표시되어 있어 사용자 경험이 좋음
- SEO에 유리 (서버 사이드 렌더링)
- JavaScript 오류가 발생해도 기본 정보는 표시됨

### Web 버전의 장점
- 동적 탭 전환 시 데이터만 갱신하면 되어 효율적
- 팝업 형태로 여러 채팅방을 관리하기 용이
- 실시간 업데이트가 쉬움

---

## 6. Web 버전 데이터 처리 상세

### HTML 구조 (2024년 업데이트)

**채용정보 (MEM 타입):**
```html
<div id="top_recruit" class="chat-recruit-content-li recomm">
    <div class="chat-recruit-content-info">
        <div class="chat-recruit-content-body">
            <div class="chat-recruit-img">
                <a id="top_link" href="">
                    <img id="top_img" src="" alt="" />
                </a>
            </div>
            <div class="chat-recruit-desc">
                <div class="chat-recruit-wicon" id="top_work_img"></div>
                <div class="chat-recruit-title ellipsis">
                    <a id="top_title_link" href=""><strong id="top_title"></strong></a>
                </div>
                <div class="chat-recruit-summary">
                    <p id="top_nation"></p>
                    <p id="top_age"></p>
                </div>
                <div class="chat-recruit-summary area">
                    <p class="js-chat-addr" id="top_area" data-addr=""></p>
                </div>
                <div class="chat-recruit-summary2">
                    <p class="period" id="top_price_type"></p>
                    <p class="amount" id="top_price"></p>
                </div>
            </div>
        </div>
        <div class="chat-recruit-summary3" id="top_work_type"></div>
    </div>
    <a href="#chatSummary" id="detailgo" class="chat-recruit-btn-wrapper">주요정보보기</a>
</div>
```

**인재정보 (COMP 타입):**
```html
<div id="top_recruit" class="chat-resume-content-li recomm">
    <div class="chat-resume-content-info">
        <div class="chat-resume-content-body">
            <div class="chat-resume-img">
                <a id="top_link" href="">
                    <img id="top_img" src="" alt="" />
                </a>
            </div>
            <div class="chat-resume-desc">
                <div class="chat-resume-wicon" id="top_work_img"></div>
                <div class="chat-resume-title ellipsis">
                    <a id="top_title_link" href=""><strong id="top_name_mem"></strong></a>
                </div>
                <div class="chat-resume-summary">
                    <ul class="working-day" id="top_weekday"></ul>
                </div>
                <div class="chat-resume-summary">
                    <p id="top_nation"></p>
                    <p id="top_age"></p>
                    <p class="js-chat-resume-addr" id="top_area" data-addr=""></p>
                </div>
                <div class="chat-resume-summary2" id="top_price_type"></div>
                <div class="chat-resume-summary3" id="top_work_type"></div>
            </div>
        </div>
    </div>
    <a href="#chatSummary" id="detailgo" class="chat-resume-btn-wrapper">인재정보보기</a>
</div>
```

### JavaScript 데이터 처리

**데이터 정리 함수:**
```javascript
// 주소 정리 함수
function fRemoveBigAdrJS(arg) {
    // 시/도명 축약 처리
    // 구/동 처리
    // 반환: 정리된 주소 문자열
}

// 국적 처리
var nationDisplay = ret.data.rc_nation_nm || "";
if (nationDisplay == "무관" || nationDisplay == "내국인,외국인") {
    nationDisplay = "국적무관";
}

// 근무 타입 변환
workTypeHtml = workTypeHtml.replace(/<span>(.*?)<\/span>/g, function(match, text) {
    // 주간 → wtype1, 야간 → wtype2, 2교대 → wtype3
    return '<em class="' + cls + '">' + text + '</em>';
});
```

**클릭 이벤트:**
```javascript
// top_recruit 클릭 시 상세페이지로 이동
$(document).on("click", "#top_recruit", function(e) {
    // 링크나 버튼 클릭 시는 기본 동작 유지
    if ($(e.target).is("a") || $(e.target).closest("a").length > 0) {
        return;
    }
    
    // 채용정보인 경우
    var rc_idx = $(this).attr("data-rc_idx");
    if (rc_idx) {
        window.location.href = "/recruit/job_post/" + rc_idx;
        return;
    }
    
    // 인재정보인 경우
    var ru_idx = $(this).attr("data-ru_idx");
    if (ru_idx) {
        window.location.href = "/resume/hr_post/" + ru_idx;
    }
});
```

---

## 7. 향후 개선 사항

1. **이벤트 핸들러 통합**
   - `room_info_return`과 `message_list_return`에서 중복 호출 방지 로직 강화
   - `ajax_first_num` 초기화로 중복 호출 알림 방지

2. **에러 처리 개선**
   - API 호출 실패 시 재시도 로직 추가
   - 사용자에게 명확한 에러 메시지 표시

3. **성능 최적화**
   - 데이터 캐싱 고려
   - 불필요한 API 호출 최소화
