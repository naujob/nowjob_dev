<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Cron 작업용 컨트롤러 (보안 설정 필요: IP 제한 등)
class Cron_proc extends CI_Controller
{
    // 지원 미확인 기업회원에게 3일 주기로 알림톡 발송
    public function check_unread_alimtalk()
    {
        // 1. rc_idx > 0인 채팅방 + 3일 이내 미발송 건만 조회
        $rooms = $this->db->query("
            SELECT r.rm_idx, r.rc_idx,
                   rc.mm_idx AS comp_mm_idx,
                   m.mm_id AS comp_mm_id, m.mm_phone, m.mm_comp_name
            FROM st_room r
            INNER JOIN st_recruit rc ON r.rc_idx = rc.rc_idx
            INNER JOIN st_member m ON rc.mm_idx = m.mm_idx
            LEFT JOIN st_alimtalk_sent s ON r.rm_idx = s.rm_idx
            WHERE r.rc_idx > 0
              AND m.mm_type = 'COMP'
              AND m.mm_status = 'Y'
              AND m.mm_phone != ''
              AND (s.as_idx IS NULL OR s.sent_dt <= DATE_SUB(NOW(), INTERVAL 3 DAY))
        ")->result_array();

        if (empty($rooms)) {
            echo "발송 대상 없음\n";
            return;
        }

        // 2. MongoDB 연결
        $uri = get_mongo_uri();
        $dbName = get_mongo_db_name();

        try {
            @$mongo = new Mongo($uri);
            $db = $mongo->$dbName;
            $collection = $db->room;
        } catch (Exception $e) {
            echo "MongoDB 연결 오류: " . $e->getMessage() . "\n";
            return;
        }

        // 3. PpurioClient 로드 (공유 라이브러리 경로)
        $ppurioPath = dirname(dirname(APPPATH)) . '/application/common/libraries/PpurioClient.php';
        if (!file_exists($ppurioPath)) {
            echo "PpurioClient 파일 없음: " . $ppurioPath . "\n";
            return;
        }
        require_once($ppurioPath);
        $ppurio = new CI_PpurioClient();

        $sent_count = 0;

        // 4. 각 채팅방에서 chat_type="recruit"인 메시지의 미확인 여부 확인
        foreach ($rooms as $row) {
            try {
                $room = $collection->findOne(array('rm_idx' => (int)$row['rm_idx']));
                if (!$room || !isset($room['rm_messages']) || empty($room['rm_messages'])) {
                    continue;
                }

                // chat_type="recruit"인 메시지 중 기업회원이 읽지 않은 것이 있는지 확인
                $has_unread_recruit_msg = false;
                foreach ($room['rm_messages'] as $msg) {
                    // chat_type이 "recruit"인 메시지만 대상
                    if (!isset($msg['chat_type']) || $msg['chat_type'] != 'recruit') {
                        continue;
                    }
                    if (!isset($msg['mg_reads'])) continue;

                    foreach ($msg['mg_reads'] as $read) {
                        if (isset($read['user_id']) && $read['user_id'] == $row['comp_mm_id']
                            && isset($read['read_yn']) && $read['read_yn'] == 'N') {
                            $has_unread_recruit_msg = true;
                            break 2;
                        }
                    }
                }

                if (!$has_unread_recruit_msg) continue;

                // 미확인 → 알림톡 발송
                $corp_vars = array('', '', '', site_url('recruit/job_post/' . $row['rc_idx']));
                $ppurio->sendCorporateAlimtalk(
                    $row['mm_phone'], $corp_vars,
                    array('name' => isset($row['mm_comp_name']) ? $row['mm_comp_name'] : '')
                );

                // 발송 이력 기록 (INSERT 또는 UPDATE)
                $this->db->query(
                    "INSERT INTO st_alimtalk_sent (rm_idx, comp_mm_idx, sent_dt, sent_count)
                     VALUES (?, ?, NOW(), 1)
                     ON DUPLICATE KEY UPDATE sent_dt = NOW(), sent_count = sent_count + 1",
                    array($row['rm_idx'], $row['comp_mm_idx'])
                );

                $sent_count++;

            } catch (Exception $e) {
                echo "채팅방 처리 오류 (rm_idx: " . $row['rm_idx'] . "): " . $e->getMessage() . "\n";
                continue;
            }
        }

        echo "완료 - 발송 건수: " . $sent_count . "\n";
    }
}
