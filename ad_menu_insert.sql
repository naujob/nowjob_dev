-- PC배너광고 클릭수 관리 메뉴 등록
-- 전화걸기/지원하기 관리 메뉴(am_idx=503) 아래에 위치

-- 먼저 503의 부모를 확인: SELECT * FROM st_admin_menu WHERE am_idx = 503;
-- 503이 2depth인 경우, PC배너광고는 같은 레벨에 추가
-- 503이 3depth인 경우, 부모의 자식으로 추가

-- 503이 2depth 메뉴라면 (대메뉴 '운영관리' 또는 유사 아래):
-- INSERT INTO st_admin_menu (am_parent, am_name, am_url, am_depth, am_sort, am_status, am_hidden)
-- VALUES (503의_parent값, 'PC배너광고 클릭수 관리', '/admin/ad/pc_banner_click_list', 2, 504, 'Y', 'N');

-- 503이 3depth 메뉴라면 (중메뉴 아래):
-- INSERT INTO st_admin_menu (am_parent, am_name, am_url, am_depth, am_sort, am_status, am_hidden)
-- VALUES (503의_parent값, 'PC배너광고 클릭수 관리', '/admin/ad/pc_banner_click_list', 3, 504, 'Y', 'N');

-- 가장 안전한 방법: 503의 정보 확인 후 동일한 am_parent를 사용하여 추가
-- 다음 SQL로 am_idx 503의 정보 확인:
SELECT am_idx, am_parent, am_depth, am_name, am_url, am_sort
FROM st_admin_menu
WHERE am_idx = 503;

-- 위 쿼리 결과의 am_parent 값으로 아래 SQL 실행:
-- 예시 (am_parent가 466이라면):
-- INSERT INTO st_admin_menu (am_parent, am_name, am_url, am_depth, am_sort, am_status, am_hidden)
-- VALUES (466, 'PC배너광고 클릭수 관리', '/admin/ad/pc_banner_click_list', 3, 504, 'Y', 'N');
