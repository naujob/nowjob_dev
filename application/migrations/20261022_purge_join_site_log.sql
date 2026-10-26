-- Migration: 가입 임시 데이터(st_site_log의 JOIN% 행)에 남은 개인정보 파기
-- Date: 2026-10-22
-- Purpose: SNS(카카오·네이버·위챗·구글) 및 휴대폰 본인인증 가입 과정의 개인정보(이름·이메일·전화·DI·생년월일·평문 비밀번호)를
--          DB에 더 이상 남기지 않는다. 코드 배포 후 가입 임시 데이터는 PHP 세션(join_temp_log)에만 저장된다.
-- 주의:
--   1) 코드 배포와 함께 적용할 것. 배포 후에는 DB의 JOIN% 행을 읽지 않으므로 지워도 가입 흐름에 영향이 없다.
--   2) 되돌릴 수 없다. 실행 전 건수 확인 후 1회만 실행할 것.
--   3) 가입 도중이던 사용자는 임시 데이터를 잃으므로 다시 가입 절차를 진행해야 한다(영향 미미).
--   4) sl_txt만 비우고 행은 남긴다(sl_status·mm_idx 등 기록은 유지). 여러 번 실행해도 결과가 같다(멱등).

-- =========================================================
-- [사전 점검] 읽기 전용 — 적용 전에 결과 확인
-- =========================================================
-- 1) 단계별 대상 건수
-- SELECT sl_type, sl_step, COUNT(*) AS cnt
-- FROM st_site_log
-- WHERE sl_type LIKE 'JOIN%' AND sl_txt <> ''
-- GROUP BY sl_type, sl_step;

-- 2) 샘플 확인 (개인정보 값이 들어 있는지 눈으로 확인하지 말고 길이만 확인)
-- SELECT session_id, mm_idx, sl_type, sl_step, CHAR_LENGTH(sl_txt) AS txt_len, reg_dt
-- FROM st_site_log
-- WHERE sl_type LIKE 'JOIN%' AND sl_txt <> ''
-- ORDER BY reg_dt DESC
-- LIMIT 20;

-- =========================================================
-- [적용]
-- =========================================================
UPDATE st_site_log
SET sl_txt = ''
WHERE sl_type LIKE 'JOIN%'
  AND sl_txt <> '';
