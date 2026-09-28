-- Migration: 다이렉트 채팅 자동생성 더미 레코드 구분 컬럼 추가
-- Date: 2026-09-28
-- Purpose: rc_auto_yn, ru_auto_yn 컬럼 추가로 자동생성된 더미 채용공고/이력서 표식

ALTER TABLE st_recruit ADD COLUMN rc_auto_yn CHAR(1) NOT NULL DEFAULT 'N' AFTER rc_status;
ALTER TABLE st_resume  ADD COLUMN ru_auto_yn CHAR(1) NOT NULL DEFAULT 'N' AFTER ru_status;

-- 인덱스 추가 (더미 레코드 조회 최적화)
ALTER TABLE st_recruit ADD INDEX idx_rc_auto_yn (rc_auto_yn);
ALTER TABLE st_resume  ADD INDEX idx_ru_auto_yn (ru_auto_yn);
