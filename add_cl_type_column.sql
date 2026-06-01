-- st_call_log 테이블에 cl_type 컬럼 추가
-- CALL=전화하기, APPLY=지원하기 구분용

ALTER TABLE `st_call_log`
ADD COLUMN `cl_type` varchar(10) DEFAULT 'CALL'
COMMENT '로그유형 (CALL=전화하기, APPLY=지원하기)'
AFTER `rc_idx`;

-- 컬럼 추가 확인
-- DESCRIBE st_call_log;
