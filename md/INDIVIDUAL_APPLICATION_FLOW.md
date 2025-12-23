# 개인이 지원했을 때 기업의 제안받은 인재 탭 데이터 흐름

## 개요
개인회원이 기업의 채용공고에 지원했을 때, 기업회원의 "제안받은 인재" 탭에 표시되기까지의 전체 데이터 흐름과 함수 호출 순서를 정리합니다.

---

## 1단계: 개인회원이 채용공고에 지원

### 1.1 프론트엔드 - 지원 버튼 클릭
**파일**: `application/shop/views/web/recruit/job_post.php` 또는 모바일 버전
**함수**: 지원 버튼 클릭 이벤트 핸들러

**처리 내용**:
- 개인회원이 채용공고 상세 페이지에서 "지원하기" 버튼 클릭
- `mq_type = "RECRUIT"` 설정
- `mq_idxs` 배열에 `rc_idx` (채용공고 번호) 포함

### 1.2 백엔드 - 지원 요청 처리
**파일**: `application/shop/controllers/common_proc.php`
**함수**: `member_request()`
**라인**: 1111-1156

**처리 순서**:
1. **입력값 검증** (879-905줄)
   - `mq_type = "RECRUIT"` 확인
   - `mq_idxs` 배열 검증
   - 로그인 여부 확인

2. **이력서 정보 조회** (932-934줄)
   ```php
   $resume = $this->member_model->getResume([
       "mm_idx" => $mm_idx, 
       "ru_show_yn" => "Y"
   ]);
   ```

3. **지원 데이터 등록** (1112-1127줄)
   ```php
   $data = [
       "mq_type" => "RECRUIT",
       "mm_idx" => $mm_idx,        // 개인회원 번호
       "rc_idx" => $idx,            // 채용공고 번호
       "ru_idx" => $resume["ru_idx"] // 이력서 번호
   ];
   $result = $this->member_model->setMemberRequest($data, $data);
   ```
   - `st_member_request` 테이블에 레코드 생성
   - `mq_type = 'RECRUIT'`로 저장
   - `mq_idx` (자동 증가) 생성

4. **채팅방 생성** (1129-1156줄)
   ```php
   $info_chat = [
       "rm_idx" => '',
       "rc_idx" => $data["rc_idx"],
       "ru_idx" => $data["ru_idx"],
       "td_idx" => 0,
       "mm_idx" => $mm_idx,
       "point_yn" => 'N'
   ];
   $result_chat = $this->common_model->insertRoom($info_chat);
   ```
   - `st_room` 테이블에 채팅방 생성
   - `rm_idx` (채팅방 번호) 생성
   - MongoDB 채팅방은 별도 API(`newroommake`)로 생성

**데이터베이스 저장**:
- `st_member_request` 테이블:
  - `mq_type = 'RECRUIT'`
  - `mm_idx = 개인회원번호`
  - `rc_idx = 채용공고번호`
  - `ru_idx = 이력서번호`
  - `reg_dt = 현재시간`

- `st_room` 테이블:
  - `rm_idx = 채팅방번호`
  - `rc_idx = 채용공고번호`
  - `ru_idx = 이력서번호`
  - `mm_idx = 개인회원번호`
  - `reg_dt = 현재시간`

---

## 2단계: 기업회원이 제안받은 인재 탭 열기

### 2.1 프론트엔드 - 탭 클릭
**파일**: `application/shop/views/web/_layout/chatting_cont.php`
**라인**: 2022-2034

**처리 내용**:
```javascript
function getChatTargetList(arg) {
    var getUrl = "get_chat_target_list_recruit";
    if (arg == '1') {
        getUrl = "get_chat_target_list_recruit"; // 제안받은 인재 탭
    }
    comAjax("/chat_proc/" + getUrl, {}, function(ret) {
        // 공고 목록 표시
    });
}
```

### 2.2 백엔드 - 공고 목록 조회
**파일**: `application/shop/controllers/chat_proc.php`
**함수**: `get_chat_target_list_recruit()`
**라인**: 14-258

**처리 순서**:

1. **공고 목록 조회** (23줄)
   ```php
   $result = $this->member_model->getRecruitActaiveList($mm_idx);
   ```

2. **MongoDB 연결 및 채팅방 정보 조회** (31-227줄)
   - 각 공고별로 채팅방 목록(`rm_idx_list`) 파싱
   - MongoDB에서 마지막 메시지, 시간, 미확인 카운트 조회
   - 나간 채팅방 필터링

3. **필터링 로직** (199-208줄)
   ```php
   // 채팅방이 없는 공고는 제외
   // 단, 제안받은 인재가 있는 경우(reqcnt > 0)에는 채팅방이 없어도 표시
   if (empty($filtered_rm_idx_list)) {
       $reqcnt = isset($row["reqcnt"]) ? (int)$row["reqcnt"] : 0;
       if ($reqcnt == 0) {
           unset($temp_list[$key]);
           continue;
       }
   }
   ```

4. **응답 반환** (257줄)
   ```php
   json_return("00", "조회성공", ["list" => $list]);
   ```

### 2.3 모델 - 공고 목록 조회
**파일**: `application/common/models/membermodel.php`
**함수**: `getRecruitActaiveList($mm_idx)`
**라인**: 753-817

**SQL 쿼리** (786-817줄):
```sql
SELECT aa.*,
    -- 제안받은 인재 수 (개인이 지원한 경우만)
    (SELECT COUNT(*) FROM st_member_request
     WHERE mq_type = 'RECRUIT'
     AND   rc_idx = aa.rc_idx) reqcnt,
    
    -- 조회 수
    (SELECT COUNT(vw.mv_idx) FROM st_member_view vw, st_member_request req
     WHERE vw.mv_type='resume'
     AND   req.mq_type = 'RECRUIT'
     AND   req.rc_idx = aa.rc_idx
     AND   vw.mv_key = req.ru_idx
     AND   vw.mm_idx = aa.mm_idx) viewcnt,
    
    -- 채팅방 목록 (개인이 지원한 경우의 채팅방만)
    IFNULL((SELECT CONCAT('(', GROUP_CONCAT(DISTINCT rm_idx ORDER BY rm_idx ASC SEPARATOR ', '), ')')
     FROM st_room
     WHERE rc_idx = aa.rc_idx
     AND (ru_idx IN (SELECT ru_idx FROM st_member_request 
                     WHERE mq_type = 'RECRUIT' AND rc_idx = aa.rc_idx))
    ), '()') AS rm_idx_list,
    
    -- 최근 업데이트 시간
    (SELECT MAX(up_dt) FROM st_room 
     WHERE rc_idx = aa.rc_idx AND reg_dt <> up_dt) AS max_up_dt

FROM st_recruit aa
WHERE aa.mm_idx = '$mm_idx'
AND aa.rc_status = 'Y' 
AND aa.rc_sdate <= now() 
AND aa.rc_edate >= now()

-- 개인이 지원한 공고만 조회
AND EXISTS (
    SELECT 1 FROM st_member_request mq
    WHERE mq.rc_idx = aa.rc_idx
    AND mq.mq_type = 'RECRUIT'
)
GROUP BY aa.rc_idx
ORDER BY max_up_dt desc, aa.rc_idx desc
```

**주요 조건**:
- `mq_type = 'RECRUIT'`: 개인이 지원한 경우만 포함
- `EXISTS` 절: 개인이 지원한 공고만 목록에 포함
- `reqcnt`: 개인이 지원한 인재 수 카운트
- `rm_idx_list`: 개인이 지원한 경우의 채팅방만 포함

---

## 3단계: 공고 클릭 시 인재 목록 조회

### 3.1 프론트엔드 - 공고 클릭
**파일**: `application/shop/views/web/_layout/chatting_cont.php`
**함수**: `fShowChatListByRecruite(ru_idx, obj)`
**라인**: 3986-4054

**처리 내용**:
```javascript
function fShowChatListByRecruite(ru_idx, obj) {
    var params = {};
    params["rc_idx"] = ru_idx;  // 채용공고 번호
    params["chatgbn"] = parentListId; // "chatPanel1" 또는 "chat_list1"
    
    comAjax("/chat_proc/get_chat_target_list_by_recruit_new", params, function(ret) {
        // 인재 목록 표시
    });
}
```

### 3.2 백엔드 - 인재 목록 조회
**파일**: `application/shop/controllers/chat_proc.php`
**함수**: `get_chat_target_list_by_recruit_new()`
**라인**: 1889-2010

**처리 순서**:

1. **파라미터 확인** (1893-1899줄)
   ```php
   $rc_idx = input_post("rc_idx", "");
   $chatgbn = input_post("chatgbn", "");
   ```

2. **인재 목록 조회** (1901-1918줄)
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
       // 제안받은 인재 탭 (chatPanel1)
       $params["ru_idx"] = 0;
       $result = $this->member_model->getRequestRecruitList($params);
   }
   ```

3. **데이터 처리** (1923-1942줄)
   - 이력서 이미지 처리
   - 근무 이미지 처리
   - 채팅방 정보 조회

4. **MongoDB에서 마지막 메시지 조회** (1948-2010줄)
   - 마지막 메시지 텍스트
   - 마지막 메시지 시간
   - 미확인 메시지 카운트

### 3.3 모델 - 인재 목록 조회
**파일**: `application/common/models/membermodel.php`
**함수**: `getRequestRecruitList($params)`
**라인**: 4443-4567

**SQL 쿼리** (4504-4567줄):
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
        ON mq.ru_idx = bb.ru_idx 
        AND (
            mq.mq_type = 'RECRUIT'  -- 개인이 지원한 경우
            OR (mq.mq_type = 'RESUME' AND mq.mm_idx != '기업회원번호')
        )
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
- `mq.mq_type = 'RECRUIT'`: 개인이 지원한 경우
- `rc.mm_idx = 기업회원번호`: 기업회원의 공고만
- `mq.rc_idx = 채용공고번호`: 특정 공고의 인재만

---

## 4단계: 프론트엔드에서 인재 목록 표시

### 4.1 템플릿 렌더링
**파일**: `application/shop/views/web/_layout/chatting_cont.php`
**라인**: 1192-1210

**템플릿**: `#tpl_chat_resume`
- 인재 이미지
- 인재 이름
- 근무 정보
- 마지막 메시지 미리보기
- 마지막 메시지 시간
- 미확인 메시지 카운트

---

## 전체 데이터 흐름 요약

```
1. 개인회원 지원
   ↓
   common_proc::member_request()
   ├─ setMemberRequest() → st_member_request 테이블 저장 (mq_type='RECRUIT')
   └─ insertRoom() → st_room 테이블 저장 (채팅방 생성)
   
2. 기업회원이 제안받은 인재 탭 열기
   ↓
   chatting_cont.php::getChatTargetList(arg='1')
   ↓
   chat_proc::get_chat_target_list_recruit()
   ├─ member_model::getRecruitActaiveList()
   │  └─ SQL: mq_type='RECRUIT'인 공고만 조회
   ├─ MongoDB에서 채팅방 정보 조회
   └─ 필터링: reqcnt > 0인 공고만 표시
   
3. 공고 클릭 시 인재 목록 조회
   ↓
   chatting_cont.php::fShowChatListByRecruite()
   ↓
   chat_proc::get_chat_target_list_by_recruit_new()
   ├─ member_model::getRequestRecruitList()
   │  └─ SQL: mq_type='RECRUIT' AND rc_idx=공고번호인 인재 조회
   ├─ MongoDB에서 마지막 메시지 조회
   └─ 인재 목록 반환
   
4. 프론트엔드에서 인재 목록 표시
   ↓
   템플릿 렌더링 (# tpl_chat_by_recruite)
```

---

## 주요 테이블 및 필드

### st_member_request
- `mq_idx`: 요청 번호 (PK)
- `mq_type`: 요청 타입 ('RECRUIT' = 개인 지원, 'RESUME' = 기업 제안)
- `mm_idx`: 요청한 회원 번호
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

1. **개인 지원 시**: `mq_type = 'RECRUIT'`로 저장
2. **공고 목록 조회**: `mq_type = 'RECRUIT'`인 공고만 표시
3. **인재 목록 조회**: `mq_type = 'RECRUIT'` AND `rc.mm_idx = 기업회원번호`인 인재만 표시
4. **채팅방 필터링**: 나간 채팅방(`exit_yn='Y'`)은 제외
5. **채팅방 없는 공고**: `reqcnt > 0`인 경우에도 표시 (개인이 지원한 경우)
