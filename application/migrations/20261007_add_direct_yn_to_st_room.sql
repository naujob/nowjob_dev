-- Migration: 채팅방 다이렉트 채팅 이력 표시 컬럼 추가
-- Date: 2026-10-07
-- Purpose: 다이렉트 채팅(채팅하기)은 더 이상 st_member_request에 기록을 남기지 않으므로,
--          "이 방(=같은 기업↔개인 쌍)에서 채팅하기를 한 적이 있는지"를 st_room에 표시한다.
--          ("이미 채팅중입니다" 팝업 / 첫 메시지 "채팅을 요청하였습니다." 전송 여부 판단용)
-- 주의: 코드 배포 전에 반드시 먼저 적용할 것 (direct_yn 컬럼이 없으면 방 생성 INSERT가 실패함)

-- =========================================================
-- [사전 점검] 읽기 전용 — 적용 전에 결과 확인
-- =========================================================
-- 1) st_room 기존 컬럼 확인 (direct_yn 중복 여부)
-- SHOW CREATE TABLE st_room;

-- 2) rm_type 분포 (member_request로 만든 방이 'recruit'인지 확인)
-- SELECT rm_type, COUNT(*) FROM st_room GROUP BY rm_type;

-- 3) 요청 기록이 하나도 없는 recruit 방 수 (PC insert_room 등) → 모바일 순수 채팅 목록에 새로 노출될 방
-- SELECT COUNT(*) FROM st_room room
-- WHERE room.rm_type = 'recruit'
--   AND NOT EXISTS (SELECT 1 FROM st_member_request mq WHERE mq.rm_idx = room.rm_idx);

-- 4) 인덱스 확인 (st_member_request.rm_idx, st_room.rc_idx/ru_idx/mm_idx)
-- SHOW INDEX FROM st_member_request;
-- SHOW INDEX FROM st_room;

-- 5) 레거시 혼합 방 진단 (방의 rc는 더미인데 정식 기록이 있는 방) — 0건이어야 함
-- SELECT room.rm_idx FROM st_room room
-- JOIN st_recruit rc ON rc.rc_idx = room.rc_idx AND rc.rc_auto_yn = 'Y'
-- WHERE EXISTS (SELECT 1 FROM st_member_request f WHERE f.rm_idx = room.rm_idx AND f.mq_type IN ('RECRUIT','RESUME'));

-- =========================================================
-- [적용]
-- =========================================================
ALTER TABLE st_room ADD COLUMN direct_yn CHAR(1) NOT NULL DEFAULT 'N' COMMENT '다이렉트 채팅 이력 여부';

-- 기존 _DIRECT 요청 기록이 있는 방은 다이렉트 이력 있음으로 채움
UPDATE st_room SET direct_yn = 'Y'
WHERE rm_idx IN (
	SELECT rm_idx FROM (
		SELECT DISTINCT rm_idx FROM st_member_request
		WHERE mq_type IN ('RECRUIT_DIRECT', 'RESUME_DIRECT') AND rm_idx IS NOT NULL
	) t
);
