-- Migration: 광고 구매 시 공고 복사본 방식 표식 컬럼
-- Date: 2026-11-06
-- Purpose: 광고 구매 시 원본 공고를 복사해 복사본(rc_idx)에 광고를 연결한다.
--          pm_origin_rc_idx = 원본 공고 번호 (0이면 기존 방식: 원본에 직접 연결)
--          자동 재등록 크론(autoReregisterPaidRecruit)은 pm_origin_rc_idx = 0인 상품만 처리한다.
-- 주의: 운영 DB에 직접 실행 (원격 접속 불가 환경이므로 사용자가 실행).
--       코드 배포 전에 먼저 적용해야 한다 (크론 조회가 이 컬럼을 참조한다).

-- 1) 실행 전 확인
-- SHOW COLUMNS FROM st_promotion LIKE 'pm_origin_rc_idx';

-- 2) 컬럼 추가 (기존 행은 0 = 기존 방식)
ALTER TABLE st_promotion
  ADD COLUMN pm_origin_rc_idx INT(11) NOT NULL DEFAULT 0 AFTER rc_idx;
