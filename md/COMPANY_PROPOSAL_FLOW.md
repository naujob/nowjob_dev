# 개인이 지원했을 때 기업의 제안보낸 인재 탭 데이터 흐름

## 개요
개인회원이 기업의 채용공고에 지원했을 때, 기업회원의 "제안보낸 인재" 탭에 표시되는지 여부와 전체 데이터 흐름을 정리합니다.

**중요**: 제안보낸 인재 탭은 **기업이 개인에게 제안한 경우만** 표시되어야 하며, **개인이 지원한 경우는 표시되지 않아야** 합니다.

---

## 핵심 원리

### mq_type 구분
- **`mq_type = 'RECRUIT'`**: 개인회원이 기업의 채용공고에 지원 (개인이 지원)
- **`mq_type = 'RESUME'`**: 기업회원이 개인의 이력서에 제안 (기업이 제안)

### 제안보낸 인재 탭의 조건
- `mq_type = 'RESUME'` (기업이 제안)
- `mq.mm_idx = 기업회원번호` (기업회원이 제안한 경우)

**결론**: 개인이 지원한 경우(`mq_type = 'RECRUIT'`)는 제안보낸 인재 탭에 **표시되지 않습니다**.

---

## 1단계: 기업회원이 제안보낸 인재 탭 열기

### 1.1 프론트엔드 - 탭 클릭
**파일**: 
- 웹: `application/shop/views/web/_layout/chatting_cont.php`
- 모바일: `application/shop/views/mobile/chat/chat_list3.php`

**웹 버전** (chatting_cont.php, 2022-2032줄):
```javascript
function getChatTargetList(arg) {
    var getUrl = "get_chat_target_list_recruit";
    if (arg == '2') {
        getUrl = "get_chat_target_list_recruit2"; // 제안보낸 인재 탭
    }
    comAjax("/chat_proc/" + getUrl, {}, function(ret) {
        // 공고 목록 표시
    });
}
```

**모바일 버전** (chat_list3.php, 478-489줄):
```javascript
function getChatTargetList(arg) {
    var getUrl = "get_chat_target_list_recruit2"; // 제안보낸 인재 탭
    comAjax("/chat_proc/" + getUrl, {}, function(ret) {
        // 공고 목록 표시
    });
}
```

### 1.2 백엔드 - 공고 목록 조회
**파일**: `application/shop/controllers/chat_proc.php`
**함수**: `get_chat_target_list_recruit2()`
**라인**: 260-638

**처리 순서**:

1. **공고 목록 조회** (270줄)
   ```php
   $result = $this->member_model->getRecruitActaiveList2($mm_idx);
   ```

2. **MongoDB 연결 및 채팅방 정보 조회** (278-505줄)
   - 각 공고별로 채팅방 목록(`rm_idx_list`) 파싱
   - MongoDB에서 마지막 메시지, 시간, 미확인 카운트 조회
   - 나간 채팅방 필터링

3. **필터링 로직** (430-438줄)
   ```php
   // 채팅방이 없는 공고는 제외
   if (empty($filtered_rm_idx_list)) {
       unset($temp_list[$key]);
       continue;
   }
   ```

4. **응답 반환** (637줄)
   ```php
   json_return("00", "조회성공", ["list" => $list]);
   ```

### 1.3 모델 - 공고 목록 조회
**파일**: `application/common/models/membermodel.php`
**함수**: `getRecruitActaiveList2($mm_idx)`
**라인**: 825-886

**SQL 쿼리** (828-861줄):
```sql
SELECT aa.*,
    -- 제안보낸 인재 수 (기업이 제안한 경우만)
    (SELECT COUNT(*) FROM st_member_request
     WHERE mq_type = 'RESUME'
     AND   rc_idx = aa.rc_idx
     AND   mm_idx = '$mm_idx') reqcnt,
    
    -- 조회 수
    (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
     WHERE vw.mv_type='resume'
     AND   req.mq_type = 'RESUME'
     AND   req.rc_idx = aa.rc_idx
     AND   req.mm_idx = '$mm_idx'
     AND   vw.mv_key = req.ru_idx
     AND   vw.mm_idx = aa.mm_idx) viewcnt,
    
    -- 채팅방 목록 (기업이 제안한 경우의 채팅방만)
    IFNULL((SELECT CONCAT('(', GROUP_CONCAT(DISTINCT rm_idx ORDER BY rm_idx ASC SEPARATOR ', '), ')')
     FROM st_room
     WHERE rc_idx = aa.rc_idx
     AND ru_idx IN (SELECT ru_idx FROM st_member_request 
                    WHERE mq_type = 'RESUME' 
                    AND rc_idx = aa.rc_idx 
                    AND mm_idx = '$mm_idx')
    ), '()') AS rm_idx_list,
    
    -- 최근 업데이트 시간
    (SELECT MAX(up_dt) FROM st_room 
     WHERE rc_idx = aa.rc_idx AND reg_dt <> up_dt) AS max_up_dt

FROM st_recruit aa
WHERE aa.mm_idx = '$mm_idx'
AND aa.rc_status = 'Y' 
AND aa.rc_sdate <= now() 
AND aa.rc_edate >= now()

-- 기업이 제안한 인재가 있는 공고만 조회
AND EXISTS (
    SELECT 1 FROM st_member_request mq
    WHERE mq.rc_idx = aa.rc_idx
    AND mq.mm_idx = '$mm_idx'
    AND mq.mq_type = 'RESUME'
)
ORDER BY max_up_dt desc, aa.rc_idx desc
```

**주요 조건**:
- `mq_type = 'RESUME'`: 기업이 제안한 경우만 포함
- `mq.mm_idx = 기업회원번호`: 기업회원이 제안한 경우만
- `EXISTS` 절: 기업이 제안한 인재가 있는 공고만 목록에 포함
- `reqcnt`: 기업이 제안한 인재 수 카운트
- `rm_idx_list`: 기업이 제안한 경우의 채팅방만 포함

**중요**: 개인이 지원한 경우(`mq_type = 'RECRUIT'`)는 이 쿼리에서 **제외**됩니다.

---

## 2단계: 공고 클릭 시 인재 목록 조회

### 2.1 프론트엔드 - 공고 클릭
**파일**: 
- 웹: `application/shop/views/web/_layout/chatting_cont.php`
- 모바일: `application/shop/views/mobile/chat/chat_list3.php`

**웹 버전** (chatting_cont.php, 3986-4016줄):
```javascript
function fShowChatListByRecruite(ru_idx, obj) {
    var parentListId = $(obj).closest('ol').attr('id'); // chat_list1 또는 chat_list2
    var params = {};
    params["rc_idx"] = ru_idx;  // 채용공고 번호
    params["chatgbn"] = parentListId; // "chat_list2" 또는 "chatPanel2"
    
    comAjax("/chat_proc/get_chat_target_list_by_recruit_new", params, function(ret) {
        // 인재 목록 표시
    });
}
```

**모바일 버전** (chat_list3.php, 940-973줄):
```javascript
function fShowChatListByRecruite(ru_idx, obj) {
    var params = {};
    params["ru_idx"] = ru_idx;
    params["chatgbn"] = 'chatPanel2'; // 제안보낸 인재 탭
    
    comAjax("/chat_proc/get_chat_target_list", params, function(ret) {
        // 인재 목록 표시
    });
}
```

### 2.2 백엔드 - 인재 목록 조회
**파일**: `application/shop/controllers/chat_proc.php`
**함수**: `get_chat_target_list_by_recruit_new()`
**라인**: 1894-2010

**처리 순서**:

1. **파라미터 확인** (1898-1904줄)
   ```php
   $rc_idx = input_post("rc_idx", "");
   $chatgbn = input_post("chatgbn", "");
   ```

2. **인재 목록 조회** (1906-1923줄)
   ```php
   $params = [
       "pagesize" => 200,
       "page" => 1,
       "mm_idx" => $mm_idx,
       "ru_idx" => $rc_idx,
   ];
   
   if ($chatgbn == "chat_list2" || $chatgbn == "chatPanel2") {
       // 제안보낸 인재 탭
       $result = $this->member_model->getRequestRecruitListSended($params);
   } else {
       // 제안받은 인재 탭
       $params["ru_idx"] = 0;
       $result = $this->member_model->getRequestRecruitList($params);
   }
   ```

3. **데이터 처리** (1928-1947줄)
   - 이력서 이미지 처리
   - 근무 이미지 처리
   - 채팅방 정보 조회

4. **MongoDB에서 마지막 메시지 조회** (1953-2010줄)
   - 마지막 메시지 텍스트
   - 마지막 메시지 시간
   - 미확인 메시지 카운트

### 2.3 모델 - 인재 목록 조회 (제안보낸 인재)
**파일**: `application/common/models/membermodel.php`
**함수**: `getRequestRecruitListSended($params)`
**라인**: 4577-4648

**SQL 쿼리** (4579-4637줄):
```sql
SELECT ru_idx, mm_idx, ru_name, ..., MIN(rm_idx) rm_idx, max(rm_up_dt) rm_up_dt
FROM (
    SELECT
        bb.*,                    -- 이력서 정보
        aa.mm_name AS target_name,
        rcmm.mm_comp_name AS user_name,
        bb.ru_img1 AS target_img,
        rc.rc_img1 AS user_img,
        aa.mm_name,
        aa.mm_nation,
        aa.mm_sex,
        aa.mm_id AS target_id,
        rcmm.mm_id AS user_id,
        mi.ma_age,
        ad.ad_addr,
        rc.rc_idx,
        rc.rc_name,
        (CASE WHEN dd.bk_idx IS NOT NULL THEN 'Y' ELSE 'N' END) AS book_yn,
        room.rm_idx, 
        room.up_dt rm_up_dt
        
    FROM st_member aa
    INNER JOIN st_member_info mi
        ON aa.mm_idx = mi.mm_idx
    INNER JOIN st_resume bb
        ON aa.mm_idx = bb.mm_idx
    INNER JOIN st_member_request mq
        ON mq.mq_type = 'RESUME'              -- 기업이 제안한 경우만
        AND mq.mm_idx = '기업회원번호'        -- 기업회원이 제안한 경우
        AND mq.rc_idx = '채용공고번호'       -- 특정 공고로 필터링
        AND bb.ru_idx = mq.ru_idx
    INNER JOIN st_recruit rc
        ON mq.rc_idx = rc.rc_idx
    INNER JOIN st_member rcmm
        ON rc.mm_idx = rcmm.mm_idx
    LEFT JOIN st_bookmark dd
        ON bb.ru_idx = dd.bk_key AND dd.bk_type = 'RESUME' 
        AND dd.mm_idx = '기업회원번호'
    LEFT JOIN st_address ad
        ON aa.mm_idx = ad.mm_idx AND ad.ad_type = 'MEMBER'
    LEFT JOIN st_room room
        ON room.rc_idx = mq.rc_idx AND room.ru_idx = mq.ru_idx
        
    WHERE 1=1
    AND aa.mm_status = 'Y'
    AND mq.rc_idx = rc.rc_idx
    AND rc.mm_idx = '기업회원번호'  -- 기업회원의 공고만
    
    -- 특정 공고로 필터링 (공고 클릭 시)
    AND mq.rc_idx = '채용공고번호'
    
    ORDER BY mq.mq_idx DESC
) innertable
GROUP BY ru_idx, mm_idx, ...
ORDER BY rm_up_dt desc
LIMIT 0, 200
```

**주요 조건**:
- `mq.mq_type = 'RESUME'`: 기업이 제안한 경우만
- `mq.mm_idx = 기업회원번호`: 기업회원이 제안한 경우만
- `mq.rc_idx = 채용공고번호`: 특정 공고의 인재만

**중요**: 개인이 지원한 경우(`mq_type = 'RECRUIT'`)는 이 쿼리에서 **제외**됩니다.

### 2.4 모델 - 인재 목록 조회 (다른 방식)
**파일**: `application/common/models/membermodel.php`
**함수**: `getRequestRecruitList2($params)`
**라인**: 4715-4800

**SQL 쿼리** (4739-4768줄):
```sql
SELECT ...
FROM st_member aa
INNER JOIN st_member_info mi
    ON aa.mm_idx = mi.mm_idx
INNER JOIN st_resume bb
    ON aa.mm_idx = bb.mm_idx
INNER JOIN st_member_request mq
    ON mq.ru_idx = bb.ru_idx 
    AND mq.mq_type = 'RESUME'              -- 기업이 제안한 경우만
INNER JOIN st_recruit rc
    ON mq.rc_idx = rc.rc_idx
INNER JOIN st_member rcmm
    ON rc.mm_idx = rcmm.mm_idx
LEFT JOIN st_bookmark dd
    ON bb.ru_idx = dd.bk_key AND dd.bk_type = 'RESUME' 
    AND dd.mm_idx = '기업회원번호'
LEFT JOIN st_address ad
    ON aa.mm_idx = ad.mm_idx AND ad.ad_type = 'RESUME' 
    AND ad.ad_key = bb.ru_idx
LEFT JOIN st_room room
    ON room.rc_idx = mq.rc_idx AND room.ru_idx = mq.ru_idx

WHERE 1=1
AND aa.mm_status = 'Y'
AND mq.mm_idx = '기업회원번호'  -- 기업회원이 제안한 경우만

-- 특정 공고로 필터링 (공고 클릭 시)
AND mq.rc_idx = '채용공고번호'

ORDER BY mq.mq_idx DESC
```

**주요 조건**:
- `mq.mq_type = 'RESUME'`: 기업이 제안한 경우만
- `mq.mm_idx = 기업회원번호`: 기업회원이 제안한 경우만

**중요**: 개인이 지원한 경우(`mq_type = 'RECRUIT'`)는 이 쿼리에서 **제외**됩니다.

---

## 3단계: 프론트엔드에서 인재 목록 표시

### 3.1 템플릿 렌더링
**파일**: 
- 웹: `application/shop/views/web/_layout/chatting_cont.php`
- 모바일: `application/shop/views/mobile/chat/chat_list3.php`

**템플릿**: `#tpl_chat_by_recruite` 또는 `#tpl_chat2`
- 인재 이미지
- 인재 이름
- 근무 정보
- 마지막 메시지 미리보기
- 마지막 메시지 시간
- 미확인 메시지 카운트

---

## 전체 데이터 흐름 요약

```
1. 기업회원이 제안보낸 인재 탭 열기
   ↓
   chatting_cont.php::getChatTargetList(arg='2')
   또는 chat_list3.php::getChatTargetList()
   ↓
   chat_proc::get_chat_target_list_recruit2()
   ├─ member_model::getRecruitActaiveList2()
   │  └─ SQL: mq_type='RESUME' AND mq.mm_idx=기업회원번호인 공고만 조회
   ├─ MongoDB에서 채팅방 정보 조회
   └─ 필터링: 채팅방이 있는 공고만 표시
   
2. 공고 클릭 시 인재 목록 조회
   ↓
   chatting_cont.php::fShowChatListByRecruite()
   또는 chat_list3.php::fShowChatListByRecruite()
   ↓
   chat_proc::get_chat_target_list_by_recruit_new()
   ├─ chatgbn 확인: "chat_list2" 또는 "chatPanel2"
   ├─ member_model::getRequestRecruitListSended()
   │  └─ SQL: mq_type='RESUME' AND mq.mm_idx=기업회원번호 AND mq.rc_idx=공고번호인 인재 조회
   ├─ MongoDB에서 마지막 메시지 조회
   └─ 인재 목록 반환
   
3. 프론트엔드에서 인재 목록 표시
   ↓
   템플릿 렌더링 (#tpl_chat_by_recruite 또는 #tpl_chat2)
```

---

## 개인이 지원한 경우의 처리

### 개인이 지원했을 때
- `st_member_request` 테이블에 `mq_type = 'RECRUIT'`로 저장
- `mq.mm_idx = 개인회원번호` (개인회원이 지원)

### 제안보낸 인재 탭에서의 조회
- `getRecruitActaiveList2()`: `mq_type = 'RESUME' AND mq.mm_idx = 기업회원번호` 조건
- `getRequestRecruitListSended()`: `mq_type = 'RESUME' AND mq.mm_idx = 기업회원번호` 조건

**결과**: 개인이 지원한 경우(`mq_type = 'RECRUIT'`)는 **제안보낸 인재 탭에 표시되지 않습니다**.

---

## 주요 테이블 및 필드

### st_member_request
- `mq_idx`: 요청 번호 (PK)
- `mq_type`: 요청 타입 
  - `'RECRUIT'` = 개인 지원
  - `'RESUME'` = 기업 제안
- `mm_idx`: 요청한 회원 번호
  - 개인 지원: 개인회원 번호
  - 기업 제안: 기업회원 번호
- `rc_idx`: 채용공고 번호
- `ru_idx`: 이력서 번호
- `reg_dt`: 등록일시

### st_room
- `rm_idx`: 채팅방 번호 (PK)
- `rc_idx`: 채용공고 번호
- `ru_idx`: 이력서 번호
- `mm_idx`: 채팅방 생성자 번호
- `reg_dt`: 등록일시
- `up_dt`: 업데이트일시

### st_recruit
- `rc_idx`: 채용공고 번호 (PK)
- `mm_idx`: 기업회원 번호
- `rc_status`: 공고 상태 ('Y' = 활성)
- `rc_sdate`: 공고 시작일
- `rc_edate`: 공고 종료일

---

## 핵심 포인트

1. **제안보낸 인재 탭**: `mq_type = 'RESUME' AND mq.mm_idx = 기업회원번호`인 경우만 표시
2. **개인 지원 시**: `mq_type = 'RECRUIT'`로 저장되므로 제안보낸 인재 탭에 **표시되지 않음**
3. **공고 목록 조회**: `getRecruitActaiveList2()` - 기업이 제안한 인재가 있는 공고만
4. **인재 목록 조회**: `getRequestRecruitListSended()` - 기업이 제안한 인재만
5. **채팅방 필터링**: 나간 채팅방(`exit_yn='Y'`)은 제외

---

## 결론

**개인이 지원한 경우는 제안보낸 인재 탭에 표시되지 않습니다.**

제안보낸 인재 탭은 오직 **기업이 개인에게 제안한 경우(`mq_type = 'RESUME' AND mq.mm_idx = 기업회원번호`)**만 표시되도록 설계되어 있습니다.

개인이 지원한 경우는 **제안받은 인재 탭**에만 표시됩니다.
