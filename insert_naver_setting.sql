-- 네이버 로그인 설정 저장 SQL
-- st_setting 테이블에 네이버 Client ID와 Client Secret 저장

-- 기존 데이터가 있으면 삭제 (중복 방지)
DELETE FROM st_setting 
WHERE st_group = '네이버' 
AND st_key IN ('Client ID', 'Client Secret');

-- 네이버 Client ID 저장
INSERT INTO st_setting (st_group, st_key, st_value) 
VALUES ('네이버', 'Client ID', 'th8WxFwsjYCQamFCSdsq');

-- 네이버 Client Secret 저장
INSERT INTO st_setting (st_group, st_key, st_value) 
VALUES ('네이버', 'Client Secret', 'fpJET5asR3');

