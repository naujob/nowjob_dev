-- Migration: 카카오 생년월일 원본 보관 테이블 생성 + 기존 카카오 회원 백필
-- Date: 2026-10-22
-- Purpose: 카카오 정책상 카카오가 준 생년월일은 변형해서 저장하면 안 되므로 원문을 그대로 보관한다.
--          st_member.mm_birth는 출생연도 4자리만 저장하므로 원본 월일은 이 테이블에만 남는다.
-- 주의:
--   1) 반드시 20261021_mask_mm_birth_to_year.sql 보다 먼저 실행할 것 (백필은 마스킹 전 mm_birth를 읽어야 함).
--   2) 탈퇴 15일 정리(_cleanupWithdrawnMemberOne)에서 mm_idx 기준으로 함께 삭제된다.
--   3) 연도만 제공됐던 기존 카카오 회원은 mm_birth에 '0101'이 붙어 있어 실제 월일을 구분할 수 없다(알려진 한계).
--   4) INSERT IGNORE + PK(mm_idx)라서 여러 번 실행해도 중복되지 않는다(멱등).

-- =========================================================
-- [사전 점검] 읽기 전용 — 적용 전에 결과 확인
-- =========================================================
-- 1) st_member.mm_idx 타입이 INT UNSIGNED 인지 확인 (다르면 아래 CREATE의 타입을 맞출 것)
-- SHOW COLUMNS FROM st_member LIKE 'mm_idx';

-- 2) 백필 대상 건수 (카카오 회원 중 8자리 YYYYMMDD 형태)
-- SELECT COUNT(*) FROM st_member
-- WHERE mm_join_type = 'KAKAO' AND TRIM(mm_birth) REGEXP '^[0-9]{8}$';

-- 3) 백필 샘플 확인 (원본 → 분리값)
-- SELECT mm_idx, mm_birth AS before_val, LEFT(TRIM(mm_birth), 4) AS birthyear, SUBSTRING(TRIM(mm_birth), 5, 4) AS birthday
-- FROM st_member
-- WHERE mm_join_type = 'KAKAO' AND TRIM(mm_birth) REGEXP '^[0-9]{8}$'
-- LIMIT 20;

-- =========================================================
-- [적용 1] 원본 보관 테이블 생성
-- =========================================================
CREATE TABLE IF NOT EXISTS st_member_kakao_birth (
  mm_idx        INT UNSIGNED NOT NULL,                 -- st_member.mm_idx (회원당 1행)
  mk_birthyear  VARCHAR(10)  NOT NULL DEFAULT '',      -- 카카오 birthyear 원문 (없으면 '')
  mk_birthday   VARCHAR(10)  NOT NULL DEFAULT '',      -- 카카오 birthday 원문 (MMDD 또는 MM-DD, 없으면 '')
  reg_dt        DATETIME     NOT NULL,
  PRIMARY KEY (mm_idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =========================================================
-- [적용 2] 기존 카카오 회원 백필 (마스킹 전 원본 복사)
-- =========================================================
INSERT IGNORE INTO st_member_kakao_birth (mm_idx, mk_birthyear, mk_birthday, reg_dt)
SELECT mm_idx, LEFT(TRIM(mm_birth), 4), SUBSTRING(TRIM(mm_birth), 5, 4), NOW()
FROM st_member
WHERE mm_join_type = 'KAKAO'
  AND TRIM(mm_birth) REGEXP '^[0-9]{8}$';
