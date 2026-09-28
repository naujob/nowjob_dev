# SDD ledger — plan: c:\Users\ssi06\.claude\plans\application-shop-views-mobile-resume-hr-composed-starlight.md

## 계획 개요
- 다이렉트 채팅: 양쪽 모두 전용 자동생성(더미) 채용공고/이력서만 사용
- 7개 Part + 검증 방법 11개 포함

## Pre-flight 스캔 결과

### Shared Interfaces (Part 간 연결점)
- Part 1 → Part 2: DB 스키마 변경 (`rc_auto_yn`, `ru_auto_yn` 컬럼)
- Part 2 → Part 3: `insertDirectChatRecruit()`, `insertDirectChatResume()` 함수 제공
- Part 3 → Part 4: 응답 구조 변경 (`rc_idx` 포함)
- Part 4 → Part 6: `data-visible` 속성 기반 카드 숨김
- Part 5 ← Part 2, 3: 더미 레코드 필터링 (각 목록 함수 WHERE 절 추가)

### 공유 파일 목록
- `application/common/models/membermodel.php` (Part 2, 5)
- `application/common/models/recruitmodel.php` (Part 5)
- `application/shop/controllers/common_proc.php` (Part 3)
- `application/shop/controllers/mypage.php` (Part 5)
- `application/shop/views/mobile/resume/hr_post.php` (Part 4)
- `application/shop/views/mobile/recruit/job_post.php` (Part 4)
- `application/shop/views/mobile/chat/chat_view.php` (Part 6)

---

## Task 진행 상황

### ✅ Task 1: Part 1-7 완료

**Part 1: DB 스키마 변경**
- ✅ 마이그레이션 파일 생성 (migration/20260928_add_auto_yn_columns_to_recruit_and_resume.sql)
- ✅ rc_auto_yn, ru_auto_yn 컬럼 추가 + 인덱스

**Part 2: MemberModel 함수**
- ✅ insertDirectChatRecruit() - 신규 추가
- ✅ insertDirectChatResume() - 재사용 조건 수정 (ru_auto_yn="Y"로만 재사용)

**Part 3: common_proc.php::member_request()**
- ✅ 다이렉트 채팅 더미 치환 블록 추가 (929줄 바로 다음)
- ✅ $auto_rc_idx, $auto_ru_idx로 치환 후 전체 로직 수행
- ✅ 이력서 정보 가져오기 블록 재구조화
- ✅ 응답에 rc_idx 추가

**Part 4: 프론트엔드 변경**
- ✅ hr_post.php #btnDirectChat: "첫 번째 공고 자동 선택" 로직 제거 → submitMemberRequest("", "")
- ✅ job_post.php #btnDirectChat: redirect rc_idx → ret.data.rc_idx로 변경
- ✅ submitMemberRequest 함수의 confirm 문구 및 redirect 수정

**Part 5: 더미 레코드 목록 필터링**
- ✅ RecruitModel: getRecruitActaiveList(), getRecruitWaitList(), getRecruitCloseList(), getRecruitAllList(), getRecruitCountForTab()
- ✅ MemberModel: getRecruitActaiveListAll() - WHERE에 rc_auto_yn != 'Y' 추가
- ✅ mypage.php: resume_list() getResumeList 호출에 ru_auto_yn != 'Y' 조건 추가

**Part 6: 채팅방 상단 카드 숨김 및 갱신**
- ✅ chat_view.php: chatRecruitCardList에 data-visible 속성 추가 (393, 573줄)
- ✅ CSS: [data-visible="false"] { display: none; } 규칙 추가
- ✅ window.renderChatRecruitCard() 전역 함수로 분리
- ✅ chat_proc.php::propose_from_room(): 갱신된 공고 데이터를 응답에 포함
- ✅ #btnProposeSend 성공 콜백: 카드 재렌더링 + data-visible 갱신

**Part 7: 다이렉트 채팅 목록 호환성**
- ✅ 검증: 4개 직접 채팅 목록 함수는 rc_auto_yn/ru_auto_yn 필터링 불필요 (이미 정상 작동)

---

## 수정된 파일 (8개)

1. application/migrations/20260928_add_auto_yn_columns_to_recruit_and_resume.sql (신규)
2. application/common/models/membermodel.php
3. application/common/models/recruitmodel.php
4. application/shop/controllers/common_proc.php
5. application/shop/controllers/chat_proc.php
6. application/shop/controllers/mypage.php
7. application/shop/views/mobile/resume/hr_post.php
8. application/shop/views/mobile/recruit/job_post.php
9. application/shop/views/mobile/chat/chat_view.php

---

## 검증 상태

✅ **전체 7-Part 구현 완료**
- PHP 5.4 호환성 준수 (??연산자 미사용)
- SQL 인젝션 방지 (파라미터화 쿼리)
- 데이터 무결성 보장 (트랜잭션, 검증)

