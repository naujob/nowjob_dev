-- Migration: 개인회원 생년월일을 출생연도 4자리로 축약 (개인정보 보호)
-- Date: 2026-10-21
-- Purpose: st_member.mm_birth(varchar(10))에 저장된 YYYYMMDD / YYYY-MM-DD 값을 출생연도 YYYY 4자리로 줄인다.
--          월·일은 더 이상 저장하지 않는다. 나이 계산은 앞 4자리(연도) 기준이다.
-- 주의:
--   1) 코드 배포와 함께 적용할 것. 배포 후 4자리 값을 8자리 전제 코드가 읽지 않도록 소비 코드도 같이 바뀌어 있어야 한다.
--   2) 되돌릴 수 없다. 반드시 st_member 백업 후, 사전 점검 결과를 확인하고 1회만 실행할 것.
--   3) mm_type 조건을 두지 않아 기업회원(COMP)도 대상에 포함된다. 실행 전 기업 행의 mm_birth 사용 여부를 확인할 것.
--   4) 이미 4자리인 값, 빈 값, '0'은 건드리지 않는다. 여러 번 실행해도 결과가 같다(멱등).
--   5) 카카오 회원은 반드시 20261022_create_st_member_kakao_birth.sql(원본 백필)을 먼저 실행한 뒤 이 파일을 실행할 것.
--      이 UPDATE가 카카오 회원의 월일을 지우므로 백필이 선행되지 않으면 원본이 사라진다.

-- =========================================================
-- [사전 점검] 읽기 전용 — 적용 전에 결과 확인
-- =========================================================
-- 1) 컬럼 타입 확인 (varchar(10) 이어야 함)
-- SHOW COLUMNS FROM st_member LIKE 'mm_birth';

-- 2) 변환 대상 건수 (8자리 또는 YYYY-MM-DD 형식)
-- SELECT COUNT(*) FROM st_member
-- WHERE (mm_birth REGEXP '^[0-9]{8}$' OR mm_birth REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$');

-- 3) 변환 결과 샘플 확인 (원본 → 변환값)
-- SELECT mm_idx, mm_birth AS before_val, LEFT(REPLACE(mm_birth, '-', ''), 4) AS after_val
-- FROM st_member
-- WHERE (mm_birth REGEXP '^[0-9]{8}$' OR mm_birth REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$')
-- LIMIT 20;

-- =========================================================
-- [적용]
-- =========================================================
UPDATE st_member
SET mm_birth = LEFT(TRIM(mm_birth), 4)
WHERE (TRIM(mm_birth) REGEXP '^[0-9]{4}[^0-9]' OR TRIM(mm_birth) REGEXP '^[0-9]{8}$');
