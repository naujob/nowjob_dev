-- Migration: 사업자번호 인증 무료 광고("지금뜨는 업체(7일권)") 정리
-- Date: 2026-11-02
-- Purpose: 사업자번호 인증 시 무료 지급하던 광고가 C004(지금뜨는 업체)가 아니라 C002(점프업)로 저장되어 왔다.
--          지급 코드는 compage_proc.php에서 삭제했고, 이미 저장된 행은 아래에서 확인 후 하나만 골라 직접 실행한다.

-- 1) 확인: 지금 기간이 남아 있는 무료 지급 행
SELECT pm_idx, mm_idx, rc_idx, pm_code, pm_name, pm_point, pm_sdate, pm_edate, pm_status
FROM st_promotion
WHERE pm_code = 'C002'
  AND pm_name = '점프업(4시간)(5일)'
  AND pm_point = 0
  AND pm_edate >= NOW()
ORDER BY pm_idx DESC;

-- 2-a) (선택) 지금뜨는 업체로 바꿔서 남은 기간 동안 "지금 뜨는 업체" 목록에 노출
-- UPDATE st_promotion
-- SET pm_code = 'C004'
-- WHERE pm_code = 'C002'
--   AND pm_name = '지금뜨는 업체(7일권)'
--   AND pm_point = 0
--   AND pm_edate >= NOW();

-- 2-b) (선택) 지금 바로 종료 (점프업 효과도 끝남)
-- UPDATE st_promotion
-- SET pm_status = 'N'
-- WHERE pm_code = 'C002'
--   AND pm_name = '지금뜨는 업체(7일권)'
--   AND pm_point = 0
--   AND pm_edate >= NOW();
