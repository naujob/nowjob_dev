-- Migration: 회원 작성 데이터 자동번역 저장 테이블 (번역 메모리)
-- Date: 2026-11-11
-- Purpose: 채용공고(rc_name, rc_hash, rc_desc)·이력서(ru_name, ru_desc, 경력/자격증 rc_name) 원문을
--          Google 번역으로 중국어 간체 번역해 저장한다. 웹 /zh 페이지에서 원문 대신 표시한다.
-- 설계:
--   1) 행 번호가 아니라 "원문 텍스트의 md5"를 키로 저장한다.
--      → 같은 문장은 한 번만 번역, 공고 재등록·복사·관리자 직접 수정·이력서 경력 재삽입도 자동 처리.
--   2) 원본 테이블(st_recruit, st_resume)은 변경하지 않는다 (ALTER 없음).
--   3) tr_status: P=번역 대기, Y=완료, E=실패(tr_err_cnt 3회 미만이면 cron이 재시도)
--   4) cron_proc/translate_pending 이 1분마다 대기분을 번역한다.
-- 주의:
--   - 기존 테이블과 같은 utf8 문자셋을 사용한다 (st_recruit/st_resume 가 utf8 이므로 원문도 utf8 범위).
--   - 이력서 원문(개인정보 포함 가능)이 저장되므로 백업·접근 권한은 원본 테이블과 동일하게 관리.
--   - CREATE TABLE IF NOT EXISTS 라서 여러 번 실행해도 안전하다(멱등).

-- =========================================================
-- [사전 점검] 읽기 전용
-- =========================================================
-- SHOW TABLE STATUS WHERE Name IN ('st_recruit', 'st_resume');   -- Collation 확인 (utf8_* 이면 그대로 적용)

-- =========================================================
-- [적용] 번역 메모리 테이블 생성
-- =========================================================
CREATE TABLE IF NOT EXISTS st_translation (
  tr_idx      INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  tr_hash     CHAR(32)         NOT NULL,              -- md5(원문)
  tr_lang     VARCHAR(5)       NOT NULL,              -- 대상 언어: 'zh'
  tr_format   CHAR(1)          NOT NULL DEFAULT 'T',  -- T=일반 텍스트, H=HTML(rc_desc 스마트에디터)
  tr_src      MEDIUMTEXT       NOT NULL,              -- 원문 (cron이 번역할 때 사용)
  tr_text     MEDIUMTEXT       NULL,                  -- 번역문
  tr_status   CHAR(1)          NOT NULL DEFAULT 'P',  -- P=대기, Y=완료, E=실패
  tr_err_cnt  TINYINT UNSIGNED NOT NULL DEFAULT 0,    -- 실패 횟수 (3회 이상이면 재시도 중단)
  tr_reg_dt   DATETIME         NOT NULL,
  tr_upd_dt   DATETIME         NULL,
  PRIMARY KEY (tr_idx),
  UNIQUE KEY uk_hash_lang (tr_hash, tr_lang),         -- 화면 표시 시 IN (...) 조회 + INSERT IGNORE 중복 방지
  KEY idx_status (tr_status, tr_idx)                  -- cron 대기열 조회
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =========================================================
-- [확인] 적용 후
-- =========================================================
-- SELECT tr_status, COUNT(*) FROM st_translation GROUP BY tr_status;
-- SELECT tr_idx, tr_format, LEFT(tr_src, 40), LEFT(tr_text, 40), tr_status FROM st_translation ORDER BY tr_idx DESC LIMIT 20;
