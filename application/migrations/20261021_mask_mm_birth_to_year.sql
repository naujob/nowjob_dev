-- Migration: 개인회원 생년월일을 출생연도 4자리로 축약 (개인정보 보호)
-- Date: 2026-10-21
-- Purpose: st_member.mm_birth(varchar(10))에 저장된 YYYYMMDD / YYYY-MM-DD 값을 출생연도 YYYY 4자리로 줄인다.
--          월·일은 더 이상 저장하지 않는다. 나이 계산은 앞 4자리(연도) 기준이다.
-- 주의:
--   1) 코드 배포와 함께 적용할 것. 배포 후 4자리 값을 8자리 전제 코드가 읽지 않도록 소비 코드도 같이 바뀌어 있어야 한다.
--   2) 되돌릴 수 없다. 반드시 st_member 백업 후, 사전 점검 결과를 확인하고 1회만 실행할 것.
--   3) 기업회원(MEM 이외)은 대상에서 제외한다.
--   4) 이미 4자리인 값, 빈 값, '0'은 건드리지 않는다. 여러 번 실행해도 결과가 같다(멱등).

-- =========================================================
-- [사전 점검] 읽기 전용 — 적용 전에 결과 확인
-- =========================================================
-- 1) 컬럼 타입 확인 (varchar(10) 이어야 함)
-- SHOW COLUMNS FROM st_member LIKE 'mm_birth';

-- 2) 변환 대상 건수 (개인회원 중 8자리 또는 YYYY-MM-DD 형식)
-- SELECT COUNT(*) FROM st_member
-- WHERE mm_type = 'MEM'
--   AND (mm_birth REGEXP '^[0-9]{8}$' OR mm_birth REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$');

-- 3) 변환 결과 샘플 확인 (원본 → 변환값)
-- SELECT mm_idx, mm_birth AS before_val, LEFT(REPLACE(mm_birth, '-', ''), 4) AS after_val
-- FROM st_member
-- WHERE mm_type = 'MEM'
--   AND (mm_birth REGEXP '^[0-9]{8}$' OR mm_birth REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$')
-- LIMIT 20;

-- =========================================================
-- [적용]
-- =========================================================
UPDATE st_member
SET mm_birth = LEFT(REPLACE(mm_birth, '-', ''), 4)
WHERE mm_type = 'MEM'
  AND (mm_birth REGEXP '^[0-9]{8}$' OR mm_birth REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$');
