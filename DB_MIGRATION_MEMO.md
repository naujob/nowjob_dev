# DB 마이그레이션

## 1. 개인회원 미확인 알림톡 기능

## 개요

개인회원(MEM)이 기업의 채팅 메시지를 확인하지 않았을 때 3일 주기로 알림톡을 발송하는 cron 기능을 추가했습니다. 이를 위해 아래 테이블을 신규 생성해야 합니다.

## 실행할 SQL

운영 DB에서 다음 SQL을 직접 실행해주세요:

```sql
CREATE TABLE st_alimtalk_sent_mem (
  as_idx INT NOT NULL AUTO_INCREMENT,
  rm_idx INT NOT NULL,
  mem_mm_idx INT NOT NULL,
  sent_dt DATETIME NOT NULL,
  sent_count INT NOT NULL DEFAULT 0,
  PRIMARY KEY (as_idx),
  UNIQUE KEY uk_rm_idx (rm_idx)
);
```

### 컬럼 설명

| 컬럼 | 설명 |
|------|------|
| `as_idx` | 발송 이력 PK |
| `rm_idx` | 채팅방 idx (st_room.rm_idx) |
| `mem_mm_idx` | 개인회원 idx (st_member.mm_idx) |
| `sent_dt` | 마지막 발송 시간 |
| `sent_count` | 누적 발송 횟수 |

### 동작 방식

- 채팅방별로 한 행씩 기록되며, 채팅 미확인 상태가 지속되면 3일마다 재발송
- `ON DUPLICATE KEY UPDATE`를 이용해 이미 발송한 방에 대해서는 `sent_dt`와 `sent_count`를 갱신

## Cron 스케줄

아래 두 시간대에 자동 실행하도록 설정합니다:

```bash
# 오전 10시(주중/주말 매일)
0 10 * * * curl -s http://도메인/cron_proc/check_unread_alimtalk_mem > /dev/null 2>&1

# 오후 4시(주중/주말 매일) - 신규 채팅만 발송
0 16 * * * curl -s http://도메인/cron_proc/check_unread_alimtalk_mem > /dev/null 2>&1
```

> 기존의 `check_unread_alimtalk()`(기업측)와 동일한 스케줄입니다.

## 변경 사항

### 1. `application/common/libraries/PpurioClient.php`

개인회원용 미확인 알림톡 발송 메서드 추가:
```php
public function sendPersonalUnreadNotificationAlimtalk($recipientPhone, $templateVariables, $options = array())
```

템플릿 코드는 이미 설정에 등록됨: `application/common/config/ppurio.php:22`
```php
'unread_notification_mem' => 'ppur_2026062910121015156256937'
```

### 2. `application/shop/controllers/cron_proc.php`

개인회원 대상 미확인 알림톡 발송 함수 추가:
```php
public function check_unread_alimtalk_mem()
```

조건: `alarm_apply = 'Y'` 추가 (기업회원 제안 알림 플래그)

### 3. `application/shop/views/mobile/setting/alarm.php`

개인회원(MEM)에게만 보이는 새 알림 옵션 추가:
```html
<?php else : ?> <!-- 개인회원 -->
<li>
    <span class="label">기업회원 제안 알림</span>
    <span class="value">
        <label class="btn-toggle">
            <input type="checkbox" name="alarm_apply" value="Y" ...>
```

참고: `alarm_apply` 컬럼은 재사용
- 기업회원(COMP): "개인회원 지원 알림"
- 개인회원(MEM): "기업회원 제안 알림"

## 테스트 방법

로컬/스테이징 환경에서 `curl`로 직접 호출:

```bash
# 로컬 테스트
curl -s http://localhost:8080/cron_proc/check_unread_alimtalk_mem

# 스테이징 테스트
curl -s http://dev.도메인/cron_proc/check_unread_alimtalk_mem
```

로그 출력:
- 발송 대상 없음
- MongoDB 연결 오류 (연결 문제 시)
- 알림톡 발송 실패 상세 (뿌리오 API 오류 시)
- 완료 - 발송 건수: N

## 안내 사항

- 기존의 `st_alimtalk_sent`(기업측 발송 이력)은 변경하지 않음
- 개인회원의 알림 설정 플래그: `st_member.alarm_chat = 'Y'`인 경우만 발송 대상
- MongoDB 채팅 메시지의 `chat_type = 'recruit'`이고 개인회원(`mem_mm_id`)이 읽지 않은 경우만 발송

---

## 2. 카카오 채널발송[일반회원/유료회원] 알림 설정 기능

### 개요

관리자가 알림톡 발송 시각, 미열람 알림 재발송 주기, 매칭 발송 요일, 매칭 조건을 직접 설정할 수 있도록 하기 위해 신규 테이블을 생성합니다. 기존에 PHP 코드에 하드코딩되어 있던 값들을 DB 설정값으로 전환합니다.

### 실행할 SQL

```sql
CREATE TABLE st_ppurio_katalk_setting (
  pks_idx INT NOT NULL AUTO_INCREMENT,
  pks_type VARCHAR(20) NOT NULL DEFAULT 'usual',

  -- 기업회원 - 지원자 알림 (미열람 알림톡)
  comp_apply_alarm1_time VARCHAR(5) NOT NULL DEFAULT '10:00',
  comp_apply_alarm2_time VARCHAR(5) NOT NULL DEFAULT '16:00',
  comp_apply_unread_day  INT NOT NULL DEFAULT 3,

  -- 기업회원 - 이력서 매칭 알림
  comp_match_alarm1_time VARCHAR(5) NOT NULL DEFAULT '10:00',
  comp_match_alarm2_time VARCHAR(5) NOT NULL DEFAULT '',
  comp_match_days  VARCHAR(50) NOT NULL DEFAULT 'MON',
  comp_match_cond  VARCHAR(100) NOT NULL DEFAULT 'area^nation^sex^age^work^price^work_type',

  -- 개인회원 - 지원자(채팅) 알림
  mem_apply_alarm1_time VARCHAR(5) NOT NULL DEFAULT '10:00',
  mem_apply_alarm2_time VARCHAR(5) NOT NULL DEFAULT '16:00',
  mem_apply_unread_day  INT NOT NULL DEFAULT 3,

  -- 개인회원 - 채용공고 매칭 알림
  mem_match_alarm1_time VARCHAR(5) NOT NULL DEFAULT '10:00',
  mem_match_alarm2_time VARCHAR(5) NOT NULL DEFAULT '',
  mem_match_days  VARCHAR(50) NOT NULL DEFAULT 'MON',
  mem_match_cond  VARCHAR(100) NOT NULL DEFAULT 'area^nation^sex^age^work^price^work_type',

  mod_dt DATETIME NULL,
  PRIMARY KEY (pks_idx),
  UNIQUE KEY uk_pks_type (pks_type)
);

INSERT INTO st_ppurio_katalk_setting (pks_type) VALUES ('usual');
INSERT INTO st_ppurio_katalk_setting (pks_type) VALUES ('subs');
```

### 컬럼 설명

| 컬럼 | 설명 |
|------|------|
| `pks_idx` | PK |
| `pks_type` | 설정 타입 ('usual': 일반회원, 'subs': 유료회원) |
| `comp_apply_alarm1_time` | 기업 지원자 알림 1차 시각 (HH:MM 형식) |
| `comp_apply_alarm2_time` | 기업 지원자 알림 2차 시각 (빈값=비활성) |
| `comp_apply_unread_day` | 기업 지원자 미열람 알림 재발송 주기 (일수) |
| `comp_match_alarm1_time` | 기업 매칭 알림 1차 시각 |
| `comp_match_alarm2_time` | 기업 매칭 알림 2차 시각 (빈값=비활성) |
| `comp_match_days` | 기업 매칭 발송 요일 (^로 구분: MON^WED^FRI) |
| `comp_match_cond` | 기업 매칭 조건 (^로 구분: area^nation^sex 등) |
| `mem_apply_alarm1_time` | 개인 채팅 알림 1차 시각 |
| `mem_apply_alarm2_time` | 개인 채팅 알림 2차 시각 (빈값=비활성) |
| `mem_apply_unread_day` | 개인 채팅 미열람 알림 재발송 주기 (일수) |
| `mem_match_alarm1_time` | 개인 매칭 알림 1차 시각 |
| `mem_match_alarm2_time` | 개인 매칭 알림 2차 시각 (빈값=비활성) |
| `mem_match_days` | 개인 매칭 발송 요일 |
| `mem_match_cond` | 개인 매칭 조건 |
| `mod_dt` | 마지막 수정 시간 |

### 요일/조건 코드

- **요일**: `MON` / `TUE` / `WED` / `THU` / `FRI` / `SAT` / `SUN` (PHP `date('D')`의 대문자 3글자)
- **조건**: `area` / `nation` / `sex` / `age` / `work` / `price` / `work_type` (MatchModel::isMatched의 검사 단계 이름)

---

## 3. 지원/제안 접수 알림톡 배치발송 기능

### 개요

기존 "지원하기" / "제안보내기" 완료 시 즉시 발송되던 카카오 알림톡을 평일(월~금) 오전 10시와 오후 4시, 하루 2회 배치로 발송하는 기능을 추가합니다. 이를 위해 신규 테이블 생성과 관리자 설정 테이블 확장이 필요합니다.

### 실행할 SQL

#### 3-1. 발송 이력 추적 테이블 신규 생성

```sql
CREATE TABLE st_request_alimtalk_sent (
  ras_idx  INT NOT NULL AUTO_INCREMENT,
  mq_idx   INT NOT NULL,
  mq_type  VARCHAR(20) NOT NULL,
  sent_dt  DATETIME NOT NULL,
  PRIMARY KEY (ras_idx),
  UNIQUE KEY uk_mq_idx (mq_idx)
);
```

#### 3-2. 관리자 설정 테이블에 컬럼 추가

```sql
ALTER TABLE st_ppurio_katalk_setting
  ADD COLUMN comp_request_alarm1_time VARCHAR(5) NOT NULL DEFAULT '10:00' AFTER comp_apply_unread_day,
  ADD COLUMN comp_request_alarm2_time VARCHAR(5) NOT NULL DEFAULT '16:00' AFTER comp_request_alarm1_time,
  ADD COLUMN mem_request_alarm1_time  VARCHAR(5) NOT NULL DEFAULT '10:00' AFTER mem_apply_unread_day,
  ADD COLUMN mem_request_alarm2_time  VARCHAR(5) NOT NULL DEFAULT '16:00' AFTER mem_request_alarm1_time;
```

### 컬럼 설명

| 컬럼 | 설명 |
|------|------|
| `ras_idx` | 발송 이력 PK |
| `mq_idx` | 지원/제안 기록 idx (st_member_request.mq_idx) |
| `mq_type` | 요청 타입 (RECRUIT: 지원, RESUME: 제안) |
| `sent_dt` | 발송 시간 |
| `comp_request_alarm1_time` | 기업 지원 접수 알림 오전 배치 시각 (HH:MM) |
| `comp_request_alarm2_time` | 기업 지원 접수 알림 오후 배치 시각 |
| `mem_request_alarm1_time` | 개인 제안 접수 알림 오전 배치 시각 |
| `mem_request_alarm2_time` | 개인 제안 접수 알림 오후 배치 시각 |

### 발송 시각별 대상 범위

- **오전 배치** (comp_request_alarm1_time / mem_request_alarm1_time):
  - 직전 영업일(평일) 오후 배치 시각 ~ 오늘 오전 배치 시각
  - 예: 월요일 10:00 배치는 금요일 16:00 ~ 월요일 10:00 사이 대상
  
- **오후 배치** (comp_request_alarm2_time / mem_request_alarm2_time):
  - 오늘 오전 배치 시각 ~ 오늘 오후 배치 시각
  - 예: 월요일 16:00 배치는 월요일 10:00 ~ 16:00 사이 대상

### Cron 스케줄

```bash
# 지원/제안 접수 알림톡 배치 발송 (평일 오전 10시/오후 4시)
0 10 * * * curl -s http://도메인/cron_proc/send_request_alimtalk > /dev/null 2>&1
0 16 * * * curl -s http://도메인/cron_proc/send_request_alimtalk > /dev/null 2>&1
```

PHP 내부에서 평일 여부를 체크하므로 crontab은 매일 호출해도 평일에만 발송됨.

### 변경 사항

#### 1. `application/shop/controllers/common_proc.php`

- 지원하기(RECRUIT) 즉시발송 블록 제거 (1172~1208행)
- 제안하기(RESUME) 즉시발송 블록 제거 (1315~1348행)
- `st_member_request` insert는 유지되며 `reg_dt`가 자동 기록됨

#### 2. `application/shop/controllers/cron_proc.php`

신규 public 함수 추가:
```php
public function send_request_alimtalk()
```

신규 private 헬퍼 함수 추가:
- `_sendRequestBatch($mq_type, $alarm1_time, $alarm2_time, $ppurio)` - 시간창 계산 및 대상 조회
- `_getPrevBusinessDay($date_str)` - 직전 영업일(평일) 계산
- `_processRecruitRequestAlimtalk($rows, $ppurio)` - 지원(RECRUIT) 발송 처리
- `_processResumeRequestAlimtalk($rows, $ppurio)` - 제안(RESUME) 발송 처리

#### 3. `application/admin/views/admin/ppurio/ppurio_katalk_usual.php`, `ppurio_katalk_subs.php`

- 기업회원 섹션: "지원 접수 알림" 입력 필드 추가
- 개인회원 섹션: "제안 접수 알림" 입력 필드 추가
- 컨트롤러(`ppurio_katalk_usual_proc`, `ppurio_katalk_subs_proc`)는 POST 필드를 그대로 저장하는 범용 구조라 수정 불필요

### 동작 방식

1. "지원하기" / "제안보내기" 완료 시 `st_member_request`에만 insert되고, 알림톡은 발송되지 않음
2. 매일 크론이 설정된 시각(10시/16시)에 `send_request_alimtalk()` 호출
3. 평일 확인 후 시간창별로 미발송 건(`st_request_alimtalk_sent`에 없는 건) 조회
4. 조회된 건마다 알림톡 발송 (성공 시에만 이력 기록)
5. 중복 발송 방지: `st_request_alimtalk_sent` 테이블의 UNIQUE KEY(`mq_idx`)로 "건당 1회만 발송" 보장

### 테스트 방법

로컬/스테이징 환경에서 직접 호출:

```bash
# 로컬 테스트
curl -s http://localhost:8080/cron_proc/send_request_alimtalk

# 스테이징 테스트
curl -s http://dev.도메인/cron_proc/send_request_alimtalk
```

로그 출력:
- 주말일 경우: "주말 - 발송 대상 아님"
- 설정값이 비어있거나 현재 시각과 불일치: "발송 대상 없음"
- 정상 발송: "완료 - 발송 건수: N"

### 안내 사항

- 기존 `comp_apply_alarm1/2_time`, `mem_apply_alarm1/2_time`은 채팅 미확인 알림용이므로 건드리지 않음
- 신규 설정값(`comp_request_alarm1/2_time`, `mem_request_alarm1/2_time`)이 비어있으면 해당 배치는 발송되지 않음
- 중복 발송 예방: 서버 장애나 크론 재실행으로 같은 건이 여러 번 처리되어도 이력 테이블이 방어함
- 기존 `check_unread_alimtalk` 계열과 동일한 usual/subs 이중 루프 구조로 설정별 독립 처리
