-- Migration: 공고 정리(cleanup_closed_recruits) 시 구매 이력에 공고 제목 보존
-- Date: 2026-11-05
-- Purpose: 마감 후 30일 지난 공고를 삭제하면 st_promotion의 rc_idx로 가져오던 공고 제목이 사라진다.
--          삭제 직전에 제목을 pm_rc_name에 복사해 두고, 구매 이력 목록은 이 값을 대체값으로 쓴다.
-- 주의: 운영 DB에 직접 실행 (원격 접속 불가 환경이므로 사용자가 실행).
--       기존 행은 NULL로 두면 된다. 공고가 삭제되는 시점에 채워진다.

-- 1) 실행 전 확인: st_recruit.rc_name 길이/타입 (TEXT로 넣어 잘림을 피함)
-- SHOW COLUMNS FROM st_recruit LIKE 'rc_name';

-- 2) 컬럼 추가



