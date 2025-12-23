# 채팅방 중복 제거 및 먼저 생성된 채팅방 우선 표시 기능

## 📋 수정 개요

기업회원이 인재에게 제안하고, 인재가 같은 채용공고에 지원하면 같은 채팅방(`rm_idx`)이 여러 탭에 중복으로 표시되는 문제를 해결했습니다. 먼저 생성된 채팅방(더 오래된 `reg_dt`)을 우선 표시하도록 수정했습니다.

---

## 🔧 수정된 파일 목록

### 웹 버전
- `/application/shop/views/web/_layout/chatting_cont.php`

### 모바일 버전
- `/application/shop/views/mobile/chat/chat_list.php` (개인회원: 지원한 공고)
- `/application/shop/views/mobile/chat/chat_list2.php` (개인회원: 제안받은 공고)
- `/application/shop/views/mobile/chat/index_comp.php` (기업회원: 제안받은 인재)
- `/application/shop/views/mobile/chat/chat_list3.php` (기업회원: 제안보낸 인재)

---

## 📝 상세 수정 내용

### 1. 웹 버전 (`chatting_cont.php`)

#### 1.1 `getChatTargetList()` 함수 (1485줄)
**기업회원 (COMP)**
- **제안받은 인재 탭** (`arg==1`): `chat_list2`(제안보낸 인재)와 중복 제거
- **제안보낸 인재 탭** (`arg==2`): `chat_list1`(제안받은 인재)와 중복 제거
- DOM에서 다른 탭의 `room-item`과 `content-wrap`을 확인하여 `rm_idx`와 `reg_dt` 수집
- `reg_dt` 비교하여 먼저 생성된 항목만 유지

**개인회원 (MEM)**
- **지원한 공고 탭** (`arg==1`): `chat_list2`(제안받은 공고)와 중복 제거
- DOM에서 다른 탭 확인 후 `reg_dt` 비교

#### 1.2 `getChatTargetList2()` 함수 (1790줄)
**기업회원 (COMP)**
- `chat_list2` 로드 시: `chat_list1`과 중복 제거

**개인회원 (MEM)**
- `chat_list2` 로드 시: `chat_list1`과 중복 제거

---

### 2. 모바일 버전

#### 2.1 `chat_list.php` (개인회원: 지원한 공고)
- **중복 제거 대상**: `chat_list2.php`(제안받은 공고)
- **방식**: localStorage 사용 (`chat_list2_rooms` 키)
- **저장**: `chat_list1_rooms` 키로 현재 페이지 채팅방 정보 저장

#### 2.2 `chat_list2.php` (개인회원: 제안받은 공고)
- **중복 제거 대상**: `chat_list.php`(지원한 공고)
- **방식**: localStorage 사용 (`chat_list1_rooms` 키)
- **저장**: `chat_list2_rooms` 키로 현재 페이지 채팅방 정보 저장

#### 2.3 `index_comp.php` (기업회원: 제안받은 인재)
- **중복 제거 대상**: `chat_list3.php`(제안보낸 인재), `chat_list.php`(채용공고)
- **방식**: localStorage 사용 (`chat_list3_rooms`, `chat_list1_rooms` 키)
- **저장**: `index_comp_rooms` 키로 현재 페이지 채팅방 정보 저장

#### 2.4 `chat_list3.php` (기업회원: 제안보낸 인재)
- **중복 제거 대상**: `index_comp.php`(제안받은 인재), `chat_list.php`(채용공고)
- **방식**: localStorage 사용 (`index_comp_rooms`, `chat_list1_rooms` 키)
- **저장**: `chat_list3_rooms` 키로 현재 페이지 채팅방 정보 저장

---

## 🔄 데이터 흐름

### 웹 버전 데이터 흐름

```
1. 사용자가 탭 클릭 (제안받은 인재 / 제안보낸 인재)
   ↓
2. getChatTargetList(arg) 호출
   ↓
3. 서버에서 채팅방 목록 조회 (AJAX)
   ↓
4. 다른 탭의 DOM 확인
   - $('#chat_list1 .room-item') 또는 $('#chat_list2 .room-item')
   - class에서 room_idx 추출: /room_idx_(\d+)/ 정규식
   - data-target_id에서 room_idx 추출
   - data-regdt에서 reg_dt 추출
   ↓
5. 중복 체크 및 필터링
   - 같은 rm_idx가 다른 탭에 있는지 확인
   - 있으면 reg_dt 비교
   - 현재 항목의 reg_dt > 다른 탭의 reg_dt → 제외 (나중에 생성됨)
   - 현재 항목의 reg_dt <= 다른 탭의 reg_dt → 유지 (먼저 생성됨)
   ↓
6. 필터링된 목록 화면에 표시
```

### 모바일 버전 데이터 흐름

```
1. 페이지 로드 (chat_list.php / chat_list2.php / index_comp.php / chat_list3.php)
   ↓
2. getChatTargetList() 또는 getChatTargetList2() 호출
   ↓
3. 서버에서 채팅방 목록 조회 (AJAX)
   ↓
4. localStorage에서 다른 페이지의 채팅방 정보 확인
   - chat_list1_rooms: chat_list.php의 채팅방 정보
   - chat_list2_rooms: chat_list2.php의 채팅방 정보
   - index_comp_rooms: index_comp.php의 채팅방 정보
   - chat_list3_rooms: chat_list3.php의 채팅방 정보
   ↓
5. 중복 체크 및 필터링
   - 같은 rm_idx가 다른 페이지에 있는지 확인
   - 있으면 reg_dt 비교
   - 현재 항목의 reg_dt > 다른 페이지의 reg_dt → 제외
   - 현재 항목의 reg_dt <= 다른 페이지의 reg_dt → 유지
   ↓
6. 필터링된 목록 화면에 표시
   ↓
7. 현재 페이지의 채팅방 정보를 localStorage에 저장
   - {rm_idx: reg_dt} 형태로 저장
   - 다른 페이지에서 참조할 수 있도록 함
```

---

## 🎯 핵심 로직

### 중복 제거 알고리즘

```javascript
// 1. 다른 탭/페이지의 채팅방 정보 수집
var existingRooms = {}; // {rm_idx: reg_dt}

// 2. 현재 목록 필터링
list = list.filter(function(row) {
    if (row.rm_idx && existingRooms[row.rm_idx]) {
        var currentRegDt = row.reg_dt;
        var otherRegDt = existingRooms[row.rm_idx];
        
        // 먼저 생성된 것(더 오래된 날짜)만 유지
        if (currentRegDt > otherRegDt) {
            return false; // 나중에 생성된 항목 제외
        }
    }
    return true;
});
```

### 우선순위 규칙

1. **같은 `rm_idx`가 여러 탭/페이지에 있을 때**
   - `reg_dt`(등록일)를 문자열 비교 (날짜 형식: YYYY-MM-DD)
   - 더 작은 값(더 오래된 날짜) = 먼저 생성된 채팅방
   - 먼저 생성된 채팅방만 표시

2. **예시**
   - 제안하기: 2025-01-01에 채팅방 생성 → `chat_list3.php`에 표시
   - 지원하기: 2025-01-05에 같은 채팅방 사용 → `index_comp.php`에 표시
   - 결과: `chat_list3.php`에만 표시 (먼저 생성됨)

---

## 📊 탭/페이지별 중복 제거 관계

### 기업회원 (웹)
- **제안받은 인재** (`chat_list1`) ↔ **제안보낸 인재** (`chat_list2`)

### 기업회원 (모바일)
- **제안받은 인재** (`index_comp.php`) ↔ **제안보낸 인재** (`chat_list3.php`)
- **채용공고** (`chat_list.php`) ↔ **제안받은 인재** (`index_comp.php`)
- **채용공고** (`chat_list.php`) ↔ **제안보낸 인재** (`chat_list3.php`)

### 개인회원 (웹/모바일)
- **지원한 공고** (`chat_list1` / `chat_list.php`) ↔ **제안받은 공고** (`chat_list2` / `chat_list2.php`)

---

## 🔑 localStorage 키 구조 (모바일)

| 키 이름 | 페이지 | 설명 |
|---------|--------|------|
| `chat_list1_rooms` | `chat_list.php` | 채용공고(기업) / 지원한 공고(개인) |
| `chat_list2_rooms` | `chat_list2.php` | 제안받은 공고(개인) |
| `index_comp_rooms` | `index_comp.php` | 제안받은 인재(기업) |
| `chat_list3_rooms` | `chat_list3.php` | 제안보낸 인재(기업) |

**데이터 형식**: `{"rm_idx": "reg_dt", ...}`
- 예: `{"123": "2025-01-01", "456": "2025-01-05"}`

---

## ⚠️ 주의사항

1. **날짜 형식**: `reg_dt`는 문자열 비교를 사용하므로 날짜 형식이 일관되어야 함
   - 형식: `YYYY-MM-DD` 또는 `YYYY-MM-DD HH:MM:SS`
   - 문자열 비교로도 정확히 동작 (ISO 8601 형식)

2. **localStorage 용량**: 모바일에서 localStorage 사용 시 용량 제한 고려
   - 현재는 `rm_idx`와 `reg_dt`만 저장하므로 용량 문제 없음

3. **브라우저 호환성**: localStorage는 모든 모던 브라우저에서 지원

4. **에러 처리**: localStorage 읽기/쓰기 실패 시 try-catch로 처리

---

## ✅ 테스트 시나리오

### 시나리오 1: 기업이 먼저 제안
1. 기업회원이 인재에게 제안 (2025-01-01)
   - `chat_list3.php`에 채팅방 표시
2. 인재가 같은 공고에 지원 (2025-01-05)
   - `index_comp.php` 로드 시 `chat_list3.php`의 채팅방 확인
   - `reg_dt` 비교: 2025-01-05 > 2025-01-01
   - 결과: `index_comp.php`에 표시 안 됨 (중복 제거)
   - `chat_list3.php`에만 표시 유지

### 시나리오 2: 인재가 먼저 지원
1. 인재가 공고에 지원 (2025-01-01)
   - `chat_list.php`에 채팅방 표시
2. 기업이 같은 인재에게 제안 (2025-01-05)
   - `chat_list3.php` 로드 시 `chat_list.php`의 채팅방 확인
   - `reg_dt` 비교: 2025-01-05 > 2025-01-01
   - 결과: `chat_list3.php`에 표시 안 됨 (중복 제거)
   - `chat_list.php`에만 표시 유지

---

## 📌 추가 개선 사항 (선택)

1. **채팅방 입장 시 카운트 제거**: 이미 구현됨 (`room_join_return`, `room_info_return`)
2. **하단 채팅 버튼 카운트 업데이트**: 이미 구현됨 (`#noreadcountall`)

---

## 🎉 완료 상태

- ✅ 웹 버전: 기업회원, 개인회원 모두 적용
- ✅ 모바일 버전: 기업회원, 개인회원 모두 적용
- ✅ 먼저 생성된 채팅방 우선 표시
- ✅ 중복 채팅방 제거
- ✅ 채팅방 입장 시 읽지 않은 메시지 카운트 제거
