<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Cron 작업용 컨트롤러 (보안 설정 필요: IP 제한 등)
class Cron_proc extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('common');
        $this->load->model('matchmodel', 'match_model');
        $this->load->model('membermodel', 'member_model');
        $this->load->model('ppuriokatalksettingmodel', 'ppurio_setting_model');
    }

    // 지원 미확인 기업회원에게 알림톡 발송 (설정값 기반)
    public function check_unread_alimtalk()
    {
        // 공통 기본 조건
        $base_where = "
            WHERE r.rc_idx > 0
              AND m.mm_type = 'COMP'
              AND m.mm_status = 'Y'
              AND m.mm_agree_ad = 'Y'
              AND m.alarm_apply = 'Y'
              AND m.mm_phone != ''
              AND (om.mm_idx IS NULL OR om.mm_status NOT IN ('N','H')) /* 채팅 상대(개인)가 탈퇴한 방은 알림 안 보냄 */
        ";

        // 두 설정 타입(usual, subs)에 대해 반복 처리
        $all_sent = 0;
        foreach (array('usual', 'subs') as $pks_type) {
            echo "[DEBUG] === {$pks_type} 설정 처리 시작 ===\n";
            $setting = $this->ppurio_setting_model->getSetting($pks_type);
            if (empty($setting)) {
                echo "⚠️  {$pks_type} 설정 없음\n";
                continue;
            }

            // 시간 조건 확인: 1차 또는 2차 알림 시각에 해당하는가
            $alarm1_time = $setting['comp_apply_alarm1_time'];
            $alarm2_time = $setting['comp_apply_alarm2_time'];
            $is_alarm1 = $this->_isAlarmTimeNow($alarm1_time);
            $is_alarm2 = $this->_isAlarmTimeNow($alarm2_time);
            echo "[DEBUG] {$pks_type} - 1차시간: {$alarm1_time} (일치: " . ($is_alarm1 ? '예' : '아니오') . "), 2차시간: {$alarm2_time} (일치: " . ($is_alarm2 ? '예' : '아니오') . ")\n";

            // 이 타입에서는 시간 조건이 맞지 않으면 skip
            if (!$is_alarm1 && !$is_alarm2) {
                echo "⚠️  {$pks_type} - 시간 조건 미충족, skip\n";
                continue;
            }

            // 설정에서 미열람 알림 재발송 일수 읽기
            $unread_day = (int)$setting['comp_apply_unread_day'];
            echo "[DEBUG] {$pks_type} - 재발송 일수: {$unread_day}일\n";

            // 2차 알림 시각인 경우 신규 채팅방 + N일 주기 재발송
            if ($is_alarm2 && !$is_alarm1) {
                // 2차: 오늘 1차 알림시간 이후 새로 생긴 채팅방 + N일 후 1회 재발송 (날짜 기준)
                $alarm_time_hm = $setting['comp_apply_alarm1_time'];
                $time_where = "
                  AND r.reg_dt >= CONCAT(CURDATE(), ' {$alarm_time_hm}:00')
                  AND (s.as_idx IS NULL OR (s.sent_count = 1 AND DATE(s.sent_dt) <= DATE_SUB(CURDATE(), INTERVAL ? DAY)))
                ";
                echo "[DEBUG] {$pks_type} - 2차 알림 모드 (신규 채팅방 + {$unread_day}일 재발송)\n";
            } else {
                // 1차: 미발송 또는 N일 이상 지난 대상 (날짜 기준) - 1회 재발송만 수행
                $time_where = "
                  AND (s.as_idx IS NULL OR (s.sent_count = 1 AND DATE(s.sent_dt) <= DATE_SUB(CURDATE(), INTERVAL ? DAY)))
                ";
                echo "[DEBUG] {$pks_type} - 1차 알림 모드 (미발송 또는 {$unread_day}일 이상 지난 대상)\n";
            }

            $where_condition = $base_where . $time_where;

            // 발송 대상 조회 (2차, 1차 모두 bind 파라미터 필요)
            $rooms = $this->db->query("
                SELECT r.rm_idx, r.rc_idx, r.ru_idx,
                       rc.mm_idx AS comp_mm_idx,
                       m.mm_id AS comp_mm_id, m.mm_phone, m.mm_comp_name
                FROM st_room r
                INNER JOIN st_recruit rc ON r.rc_idx = rc.rc_idx
                INNER JOIN st_member m ON rc.mm_idx = m.mm_idx
                LEFT JOIN st_resume oru ON r.ru_idx = oru.ru_idx
                LEFT JOIN st_member om ON oru.mm_idx = om.mm_idx
                LEFT JOIN st_alimtalk_sent s ON r.rm_idx = s.rm_idx
                {$where_condition}
            ", array($unread_day))->result_array();

            echo "[DEBUG] {$pks_type} - 발송 대상 조회 결과: " . count($rooms) . "건\n";

            if (empty($rooms)) {
                echo "⚠️  {$pks_type} - 발송 대상 없음\n";
                continue;
            }

            $sent = $this->_processUnreadAlimtalk($rooms);
            $all_sent += $sent;
        }

        if ($all_sent == 0) {
            echo "⚠️  전체 발송 대상 없음\n";
        } else {
            echo "✅ 완료 - 전체 발송 건수: {$all_sent}\n";
        }
    }

    // check_unread_alimtalk의 실제 발송 로직 (MongoDB 조회 및 알림톡 발송)
    private function _processUnreadAlimtalk($rooms)
    {
        // MongoDB 연결
        $uri = get_mongo_uri();
        $dbName = get_mongo_db_name();

        try {
            @$mongo = new Mongo($uri);
            $db = $mongo->$dbName;
            $collection = $db->room;
            echo "[DEBUG] MongoDB 연결 성공\n";
        } catch (Exception $e) {
            echo "❌ MongoDB 연결 오류: " . $e->getMessage() . "\n";
            return 0;
        }

        // PpurioClient 로드
        $ppurioPath = dirname(dirname(APPPATH)) . '/application/common/libraries/PpurioClient.php';
        if (!file_exists($ppurioPath)) {
            echo "❌ PpurioClient 파일 없음: " . $ppurioPath . "\n";
            return 0;
        }
        require_once($ppurioPath);
        $ppurio = new CI_PpurioClient();
        echo "[DEBUG] PpurioClient 로드 성공\n";

        $sent_count = 0;
        echo "[DEBUG] 처리할 채팅방 개수: " . count($rooms) . "\n";

        // 각 채팅방에서 미확인 메시지 확인 후 발송
        foreach ($rooms as $row) {
            try {
                $room = $collection->findOne(array('rm_idx' => (int)$row['rm_idx']));
                if (!$room || !isset($room['rm_messages']) || empty($room['rm_messages'])) {
                    echo "[DEBUG] rm_idx={$row['rm_idx']}: 메시지 없음\n";
                    continue;
                }

                // chat_type="recruit"인 메시지 중 기업회원이 읽지 않은 것이 있는지 확인
                $has_unread_recruit_msg = false;
                foreach ($room['rm_messages'] as $msg) {
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

                if (!$has_unread_recruit_msg) {
                    echo "[DEBUG] rm_idx={$row['rm_idx']}: 미읽음 메시지 없음\n";
                    continue;
                }

                echo "[DEBUG] rm_idx={$row['rm_idx']}, comp_mm_idx={$row['comp_mm_idx']}, 전화={$row['mm_phone']}: 발송 시도\n";

                // 미확인 → 카카오 알림톡 발송
                $result = $ppurio->sendUnreadNotificationAlimtalk(
                    $row['mm_phone'],
                    array(),
                    array()
                );

                $is_success = isset($result['ok']) && $result['ok'] === true;
                echo "[DEBUG] API 응답: " . json_encode($result) . "\n";

                if ($is_success) {
                    // 발송 성공 시에만 이력 기록
                    $db_result = $this->db->query(
                        "INSERT INTO st_alimtalk_sent (rm_idx, comp_mm_idx, sent_dt, sent_count)
                         VALUES (?, ?, NOW(), 1)
                         ON DUPLICATE KEY UPDATE sent_dt = NOW(), sent_count = sent_count + 1",
                        array($row['rm_idx'], $row['comp_mm_idx'])
                    );

                    if ($db_result) {
                        echo "✅ rm_idx={$row['rm_idx']}: 발송 및 DB 저장 성공\n";
                        $sent_count++;
                    } else {
                        echo "❌ rm_idx={$row['rm_idx']}: DB 저장 실패 - " . $this->db->last_query() . "\n";
                    }
                } else {
                    echo "❌ rm_idx={$row['rm_idx']}: 뿌리오 API 발송 실패\n";
                }

            } catch (Exception $e) {
                echo "❌ rm_idx={$row['rm_idx']}: 예외 발생 - " . $e->getMessage() . "\n";
                continue;
            }
        }

        echo "[DEBUG] 총 발송 건수: {$sent_count}\n";
        return $sent_count;
    }

    // 공고 미확인 개인회원에게 알림톡 발송 (설정값 기반)
    public function check_unread_alimtalk_mem()
    {
        // 공통 기본 조건
        $base_where = "
            WHERE r.ru_idx > 0
              AND m.mm_type = 'MEM'
              AND m.mm_status = 'Y'
              AND m.mm_agree_ad = 'Y'
              AND m.alarm_chat = 'Y'
              AND m.alarm_apply = 'Y'
              AND m.mm_phone != ''
              AND (om.mm_idx IS NULL OR om.mm_status NOT IN ('N','H')) /* 채팅 상대(기업)가 탈퇴한 방은 알림 안 보냄 */
        ";

        // 두 설정 타입(usual, subs)에 대해 반복 처리
        $all_sent = 0;
        foreach (array('usual', 'subs') as $pks_type) {
            echo "[DEBUG] === {$pks_type} 설정 처리 시작 ===\n";
            $setting = $this->ppurio_setting_model->getSetting($pks_type);
            if (empty($setting)) {
                echo "⚠️  {$pks_type} 설정 없음\n";
                continue;
            }

            // 시간 조건 확인: 1차 또는 2차 알림 시각에 해당하는가
            $alarm1_time = $setting['comp_apply_alarm1_time'];
            $alarm2_time = $setting['comp_apply_alarm2_time'];
            $is_alarm1 = $this->_isAlarmTimeNow($alarm1_time);
            $is_alarm2 = $this->_isAlarmTimeNow($alarm2_time);
            echo "[DEBUG] {$pks_type} - 1차시간: {$alarm1_time} (일치: " . ($is_alarm1 ? '예' : '아니오') . "), 2차시간: {$alarm2_time} (일치: " . ($is_alarm2 ? '예' : '아니오') . ")\n";

            // 이 타입에서는 시간 조건이 맞지 않으면 skip
            if (!$is_alarm1 && !$is_alarm2) {
                echo "⚠️  {$pks_type} - 시간 조건 미충족, skip\n";
                continue;
            }

            // 설정에서 미열람 알림 재발송 일수 읽기
            $unread_day = (int)$setting['mem_apply_unread_day'];
            echo "[DEBUG] {$pks_type} - 재발송 일수: {$unread_day}일\n";

            // 2차 알림 시각인 경우 신규 채팅방 + N일 주기 재발송
            if ($is_alarm2 && !$is_alarm1) {
                // 2차: 오늘 1차 알림시간 이후 새로 생긴 채팅방 + N일 후 1회 재발송 (날짜 기준)
                $alarm_time_hm = $setting['mem_apply_alarm1_time'];
                $time_where = "
                  AND r.reg_dt >= CONCAT(CURDATE(), ' {$alarm_time_hm}:00')
                  AND (s.as_idx IS NULL OR (s.sent_count = 1 AND DATE(s.sent_dt) <= DATE_SUB(CURDATE(), INTERVAL ? DAY)))
                ";
                echo "[DEBUG] {$pks_type} - 2차 알림 모드 (신규 채팅방 + {$unread_day}일 재발송)\n";
            } else {
                // 1차: 미발송 또는 N일 이상 지난 대상 (날짜 기준) - 1회 재발송만 수행
                $time_where = "
                  AND (s.as_idx IS NULL OR (s.sent_count = 1 AND DATE(s.sent_dt) <= DATE_SUB(CURDATE(), INTERVAL ? DAY)))
                ";
                echo "[DEBUG] {$pks_type} - 1차 알림 모드 (미발송 또는 {$unread_day}일 이상 지난 대상)\n";
            }

            $where_condition = $base_where . $time_where;

            // 발송 대상 조회 (2차, 1차 모두 bind 파라미터 필요)
            $rooms = $this->db->query("
                SELECT r.rm_idx, r.rc_idx, r.ru_idx,
                       ru.mm_idx AS mem_mm_idx,
                       m.mm_id AS mem_mm_id, m.mm_phone, m.mm_name
                FROM st_room r
                INNER JOIN st_resume ru ON r.ru_idx = ru.ru_idx
                INNER JOIN st_member m ON ru.mm_idx = m.mm_idx
                LEFT JOIN st_recruit orc ON r.rc_idx = orc.rc_idx
                LEFT JOIN st_member om ON orc.mm_idx = om.mm_idx
                LEFT JOIN st_alimtalk_sent_mem s ON r.rm_idx = s.rm_idx
                {$where_condition}
            ", array($unread_day))->result_array();

            echo "[DEBUG] {$pks_type} - 발송 대상 조회 결과: " . count($rooms) . "건\n";

            if (empty($rooms)) {
                echo "⚠️  {$pks_type} - 발송 대상 없음\n";
                continue;
            }

            $sent = $this->_processUnreadAlimtalkMem($rooms);
            $all_sent += $sent;
        }

        if ($all_sent == 0) {
            echo "⚠️  전체 발송 대상 없음\n";
        } else {
            echo "✅ 완료 - 전체 발송 건수: {$all_sent}\n";
        }
    }

    // check_unread_alimtalk_mem의 실제 발송 로직 (MongoDB 조회 및 알림톡 발송)
    private function _processUnreadAlimtalkMem($rooms)
    {
        // MongoDB 연결
        $uri = get_mongo_uri();
        $dbName = get_mongo_db_name();

        try {
            @$mongo = new Mongo($uri);
            $db = $mongo->$dbName;
            $collection = $db->room;
            echo "[DEBUG] MongoDB 연결 성공\n";
        } catch (Exception $e) {
            echo "❌ MongoDB 연결 오류: " . $e->getMessage() . "\n";
            return 0;
        }

        // PpurioClient 로드
        $ppurioPath = dirname(dirname(APPPATH)) . '/application/common/libraries/PpurioClient.php';
        if (!file_exists($ppurioPath)) {
            echo "❌ PpurioClient 파일 없음: " . $ppurioPath . "\n";
            return 0;
        }
        require_once($ppurioPath);
        $ppurio = new CI_PpurioClient();
        echo "[DEBUG] PpurioClient 로드 성공\n";

        $sent_count = 0;
        echo "[DEBUG] 처리할 채팅방 개수: " . count($rooms) . "\n";

        // 각 채팅방에서 미확인 메시지 확인 후 발송
        foreach ($rooms as $row) {
            try {
                $room = $collection->findOne(array('rm_idx' => (int)$row['rm_idx']));
                if (!$room || !isset($room['rm_messages']) || empty($room['rm_messages'])) {
                    echo "[DEBUG] rm_idx={$row['rm_idx']}: 메시지 없음\n";
                    continue;
                }

                // chat_type="recruit"인 메시지 중 개인회원이 읽지 않은 것이 있는지 확인
                $has_unread_recruit_msg = false;
                foreach ($room['rm_messages'] as $msg) {
                    if (!isset($msg['chat_type']) || $msg['chat_type'] != 'recruit') {
                        continue;
                    }
                    if (!isset($msg['mg_reads'])) continue;

                    foreach ($msg['mg_reads'] as $read) {
                        if (isset($read['user_id']) && $read['user_id'] == $row['mem_mm_id']
                            && isset($read['read_yn']) && $read['read_yn'] == 'N') {
                            $has_unread_recruit_msg = true;
                            break 2;
                        }
                    }
                }

                if (!$has_unread_recruit_msg) {
                    echo "[DEBUG] rm_idx={$row['rm_idx']}: 미읽음 메시지 없음\n";
                    continue;
                }

                echo "[DEBUG] rm_idx={$row['rm_idx']}, mem_mm_idx={$row['mem_mm_idx']}, 전화={$row['mm_phone']}: 발송 시도\n";

                // 미확인 → 카카오 알림톡 발송
                $result = $ppurio->sendPersonalUnreadNotificationAlimtalk(
                    $row['mm_phone'],
                    array(),
                    array()
                );

                $is_success = isset($result['ok']) && $result['ok'] === true;
                echo "[DEBUG] API 응답: " . json_encode($result) . "\n";

                if ($is_success) {
                    // 발송 성공 시에만 이력 기록
                    $db_result = $this->db->query(
                        "INSERT INTO st_alimtalk_sent_mem (rm_idx, mem_mm_idx, sent_dt, sent_count)
                         VALUES (?, ?, NOW(), 1)
                         ON DUPLICATE KEY UPDATE sent_dt = NOW(), sent_count = sent_count + 1",
                        array($row['rm_idx'], $row['mem_mm_idx'])
                    );

                    if ($db_result) {
                        echo "✅ rm_idx={$row['rm_idx']}: 발송 및 DB 저장 성공\n";
                        $sent_count++;
                    } else {
                        echo "❌ rm_idx={$row['rm_idx']}: DB 저장 실패 - " . $this->db->last_query() . "\n";
                    }
                } else {
                    echo "❌ rm_idx={$row['rm_idx']}: 뿌리오 API 발송 실패\n";
                }

            } catch (Exception $e) {
                echo "❌ rm_idx={$row['rm_idx']}: 예외 발생 - " . $e->getMessage() . "\n";
                continue;
            }
        }

        echo "[DEBUG] 총 발송 건수: {$sent_count}\n";
        return $sent_count;
    }

    // 조건 기반 매칭 알림톡 발송 (설정값 기반)
    public function weekly_match_alimtalk()
    {
        $start_time = microtime(true);
        $current_week = date('Y-W'); // 주차 식별자 (YYYY-WW)
        echo "[DEBUG] 매칭 알림톡 시작 - 주차: {$current_week}, 현재시간: " . date('Y-m-d H:i:s') . "\n";

        // 1. PpurioClient 로드
        $ppurioPath = dirname(dirname(APPPATH)) . '/application/common/libraries/PpurioClient.php';
        if (!file_exists($ppurioPath)) {
            echo "❌ PpurioClient 파일 없음: " . $ppurioPath . "\n";
            return;
        }
        require_once($ppurioPath);
        $ppurio = new CI_PpurioClient();
        echo "[DEBUG] PpurioClient 로드 성공\n";

        // 2. 활성 이력서 조회 (주소 포함)
        $resumes = $this->_getActiveResumes();
        if (empty($resumes)) {
            echo "⚠️  활성 이력서 없음\n";
            return;
        }
        echo "[DEBUG] 활성 이력서: " . count($resumes) . "건\n";

        // 3. 게재 중인 채용공고 조회 (주소 포함)
        $recruits = $this->_getActiveRecruits();
        if (empty($recruits)) {
            echo "⚠️  게재 중인 채용공고 없음\n";
            return;
        }
        echo "[DEBUG] 게재 중인 채용공고: " . count($recruits) . "건\n";

        // 두 설정 타입(usual, subs)에 대해 반복 처리
        $total_sent_corp = 0;
        $total_sent_member = 0;

        foreach (array('usual', 'subs') as $pks_type) {
            echo "[DEBUG] === {$pks_type} 설정 처리 시작 ===\n";
            $setting = $this->ppurio_setting_model->getSetting($pks_type);
            if (empty($setting)) {
                echo "⚠️  {$pks_type} 설정 없음\n";
                continue;
            }

            // 기업 매칭 발송 여부 판단
            $comp_match_days = $setting['comp_match_days'];
            $comp_match_alarm1_time = $setting['comp_match_alarm1_time'];
            $comp_due = $this->_isMatchDayToday($comp_match_days) &&
                        $this->_isAlarmTimeNow($comp_match_alarm1_time);
            echo "[DEBUG] 기업 매칭 - 요일: {$comp_match_days}, 시간: {$comp_match_alarm1_time}, 발송 대상: " . ($comp_due ? '예' : '아니오') . "\n";

            // 개인 매칭 발송 여부 판단
            $mem_match_days = $setting['mem_match_days'];
            $mem_match_alarm1_time = $setting['mem_match_alarm1_time'];
            $mem_due = $this->_isMatchDayToday($mem_match_days) &&
                       $this->_isAlarmTimeNow($mem_match_alarm1_time);
            echo "[DEBUG] 개인 매칭 - 요일: {$mem_match_days}, 시간: {$mem_match_alarm1_time}, 발송 대상: " . ($mem_due ? '예' : '아니오') . "\n";

            // 이 타입에서는 둘 다 발송 대상이 아니면 skip
            if (!$comp_due && !$mem_due) {
                echo "[DEBUG] 이 설정에서는 발송 대상 아님\n";
                continue;
            }

            // 기업 매칭 조건 파싱
            // area^nation^sex^age^work^price^work_type
            $comp_cond_arr = $this->_parseConditions($setting['comp_match_cond']);
            echo "[DEBUG] 기업 매칭 조건: " . implode(', ', $comp_cond_arr) . "\n";

            // 개인 매칭 조건 파싱
            $mem_cond_arr = $this->_parseConditions($setting['mem_match_cond']);
            echo "[DEBUG] 개인 매칭 조건: " . implode(', ', $mem_cond_arr) . "\n";

            // 기업 매칭 처리
            if ($comp_due) {
                echo "[DEBUG] 기업 매칭 계산 시작...\n";
                $matches_for_corp = $this->_calculateCorpMatches($resumes, $recruits, $comp_cond_arr);
                echo "[DEBUG] 기업 매칭 결과: " . count($matches_for_corp) . "건\n";
                $sent_corp = $this->_sendCorpAlimtalk($matches_for_corp, $current_week, $ppurio);
                echo "[DEBUG] 기업 발송 완료: {$sent_corp}건\n";
                $total_sent_corp += $sent_corp;
            }

            // 개인 매칭 처리
            if ($mem_due) {
                echo "[DEBUG] 개인 매칭 계산 시작...\n";
                $matches_for_member = $this->_calculateMemMatches($resumes, $recruits, $mem_cond_arr);
                echo "[DEBUG] 개인 매칭 결과: " . count($matches_for_member) . "건\n";
                $sent_member = $this->_sendMemberAlimtalk($matches_for_member, $current_week, $ppurio);
                echo "[DEBUG] 개인 발송 완료: {$sent_member}건\n";
                $total_sent_member += $sent_member;
            }
        }

        // 4. 실행 결과 출력
        $elapsed = round(microtime(true) - $start_time, 2);
        if ($total_sent_corp == 0 && $total_sent_member == 0) {
            echo "⚠️  발송 대상 시각 아님\n";
        } else {
            echo "✅ 매칭 알림톡 발송 완료 - 기업: {$total_sent_corp}건, 개인: {$total_sent_member}건, 소요시간: {$elapsed}초\n";
        }
    }

    /**
     * 기업 대상 매칭 계산
     */
    private function _calculateCorpMatches($resumes, $recruits, $comp_cond_arr)
    {
        $matches_for_corp = array();

        // 지역 조건이 켜져있으면 → 우선순위 지역 매칭
        if (in_array('area', $comp_cond_arr)) {
            // 이력서를 ru_idx별로 주소 배열로 그룹화
            $resumes_by_ru = $this->_groupResumesByRuIdx($resumes);
            // 공고를 rc_idx별로 주소 배열로 그룹화
            $recruits_by_rc = $this->_groupRecruitsByRcIdx($recruits);

            // 주소 → 지역 매핑 캐시 (DB 쿼리 최소화)
            $area_cache = array();

            // 각 공고별로 반복
            foreach ($recruits_by_rc as $rc_idx => $recruit_data) {
                $recruit_main = $recruit_data['recruit'];
                $recruit_matched_resumes = array(); // 이 공고에 매칭된 이력서 ru_idx들

                // 공고의 첫번째 주소부터 순서대로 시도 (RECRUIT 우선, 그 다음 expose)
                foreach ($recruit_data['addresses'] as $recruit_addr) {
                    // 캐시 확인
                    if (!isset($area_cache[$recruit_addr])) {
                        $area_cache[$recruit_addr] = $this->_extractAreaSido($recruit_addr);
                    }
                    $recruit_area = $area_cache[$recruit_addr];

                    // 각 이력서별로 반복
                    foreach ($resumes_by_ru as $ru_idx => $resume_data) {
                        // 이 공고에 이미 매칭된 이력서면 skip
                        if (in_array($ru_idx, $recruit_matched_resumes)) continue;

                        $resume_main = $resume_data['resume'];
                        // 이력서의 주소를 첫번째부터 순서대로 시도
                        foreach ($resume_data['addresses'] as $resume_addr) {
                            // 캐시 확인
                            if (!isset($area_cache[$resume_addr])) {
                                $area_cache[$resume_addr] = $this->_extractAreaSido($resume_addr);
                            }
                            $resume_area = $area_cache[$resume_addr];

                            // 지역 일치 확인 (둘 다 null이면 매칭 제외)
                            if ($recruit_area !== null && $resume_area !== null
                                && $recruit_area === $resume_area
                                && $this->_isMatched($resume_main, $recruit_main, $comp_cond_arr)) {
                                $matches_for_corp[] = array(
                                    'recruit' => $recruit_main,
                                    'resume' => $resume_main
                                );
                                // 이 공고에 매칭된 이력서 기록 (같은 공고에는 한 번만)
                                $recruit_matched_resumes[] = $ru_idx;
                                break; // 이력서의 주소 루프 탈출 (이 이력서는 이 공고에 한 번만)
                            }
                        }
                    }
                }
            }
        } else {
            // 지역 조건이 꺼져있으면 → 전체 매칭
            $matches_for_corp = $this->match_model->matchAllPairs($resumes, $recruits, $comp_cond_arr);
            // 형식 변환: resume/recruit 순서를 기업용으로 맞춤
            $formatted = array();
            foreach ($matches_for_corp as $match) {
                $formatted[] = array(
                    'recruit' => $match['recruit'],
                    'resume' => $match['resume']
                );
            }
            $matches_for_corp = $formatted;
        }

        return $matches_for_corp;
    }

    /**
     * 개인 대상 매칭 계산
     */
    private function _calculateMemMatches($resumes, $recruits, $mem_cond_arr)
    {
        $matches_for_member = array();

        // 지역 조건이 켜져있으면 → 우선순위 지역 매칭
        if (in_array('area', $mem_cond_arr)) {
            // 이력서를 ru_idx별로 주소 배열로 그룹화
            $resumes_by_ru = $this->_groupResumesByRuIdx($resumes);
            // 공고를 rc_idx별로 주소 배열로 그룹화
            $recruits_by_rc = $this->_groupRecruitsByRcIdx($recruits);

            // 주소 → 지역 매핑 캐시 (DB 쿼리 최소화)
            $area_cache = array();

            // 각 이력서별로 반복
            foreach ($resumes_by_ru as $ru_idx => $resume_data) {
                $resume_main = $resume_data['resume'];
                $resume_matched_recruits = array(); // 이 이력서가 매칭된 공고 rc_idx들

                // 이력서의 첫번째 주소부터 순서대로 시도
                foreach ($resume_data['addresses'] as $resume_addr) {
                    // 캐시 확인
                    if (!isset($area_cache[$resume_addr])) {
                        $area_cache[$resume_addr] = $this->_extractAreaSido($resume_addr);
                    }
                    $resume_area = $area_cache[$resume_addr];

                    // 각 공고별로 반복
                    foreach ($recruits_by_rc as $rc_idx => $recruit_data) {
                        // 이 이력서가 이미 이 공고에 매칭됨?
                        if (in_array($rc_idx, $resume_matched_recruits)) continue;

                        $recruit_main = $recruit_data['recruit'];
                        // 공고의 주소를 첫번째부터 순서대로 시도 (RECRUIT 우선, 그 다음 expose)
                        foreach ($recruit_data['addresses'] as $recruit_addr) {
                            // 캐시 확인
                            if (!isset($area_cache[$recruit_addr])) {
                                $area_cache[$recruit_addr] = $this->_extractAreaSido($recruit_addr);
                            }
                            $recruit_area = $area_cache[$recruit_addr];

                            // 지역 일치 확인 (둘 다 null이면 매칭 제외)
                            if ($recruit_area !== null && $resume_area !== null
                                && $recruit_area === $resume_area
                                && $this->_isMatched($resume_main, $recruit_main, $mem_cond_arr)) {
                                $matches_for_member[] = array(
                                    'resume' => $resume_main,
                                    'recruit' => $recruit_main
                                );
                                // 이 이력서가 이 공고에 매칭됨 기록 (같은 이력서-공고는 한 번만)
                                $resume_matched_recruits[] = $rc_idx;
                                break; // 공고의 주소 루프 탈출 (이 공고는 이 이력서에 한 번만)
                            }
                        }
                    }
                }
            }
        } else {
            // 지역 조건이 꺼져있으면 → 전체 매칭
            $matches_for_member = $this->match_model->matchAllPairs($resumes, $recruits, $mem_cond_arr);
        }

        return $matches_for_member;
    }

    /**
     * 활성 이력서 조회 (주소 포함)
     */
    private function _getActiveResumes()
    {
        return $this->match_model->getActiveResumes();
    }

    /**
     * 게재 중인 채용공고 조회 (주소 포함)
     */
    private function _getActiveRecruits()
    {
        return $this->match_model->getActiveRecruits();
    }

    /**
     * 이력서와 채용공고의 세부 조건 매칭 확인
     * 모든 조건이 일치해야 매칭됨
     */
    private function _isMatched($resume, $recruit, $conditions = null)
    {
        return $this->match_model->isMatched($resume, $recruit, $conditions);
    }

    /**
     * 기업회원에게 매칭 이력서 알림톡 발송
     * 같은 기업에게 같은 이력서를 이번 주에 중복 발송하지 않음
     * 각 기업에게는 최신 1건만 발송
     */
    private function _sendCorpAlimtalk($matches, $current_week, $ppurio)
    {
        if (empty($matches)) return 0;

        // 기업별로 그룹핑하여 각 기업마다 가장 최신 1건만 추출
        $corp_latest = array();
        foreach ($matches as $match) {
            $corp_idx = $match['recruit']['mm_idx'];
            if (!isset($corp_latest[$corp_idx]) ||
                strtotime($match['resume']['reg_dt']) > strtotime($corp_latest[$corp_idx]['resume']['reg_dt'])) {
                $corp_latest[$corp_idx] = $match;
            }
        }

        $sent = 0;
        $batch_limit = 100;

        foreach ($corp_latest as $match) {
            if ($sent >= $batch_limit) break;

            $recruit = $match['recruit'];
            $resume = $match['resume'];

            // 중복 체크
            $result = $this->db->query(
                "SELECT ms_idx FROM st_match_alimtalk_sent
                 WHERE sender_type = 'COMP'
                   AND receiver_mm_idx = ?
                   AND target_key = ?
                   AND sent_week = ?",
                array($recruit['mm_idx'], $resume['ru_idx'], $current_week)
            );
            $dup_check = $result->row_array();

            if (!empty($dup_check)) continue;

            echo "[DEBUG] [기업 매칭] sendCorporateAlimtalk 호출 - phone=" . $recruit['mm_phone'] . ", comp_name=" . $recruit['mm_comp_name'] . ", resume_name=" . $resume['ru_name'] . "\n";
            $result = $ppurio->sendCorporateAlimtalk(
                $recruit['mm_phone'],
                array(),
                array()
            );
            echo "[DEBUG] [기업 매칭] API 응답 - ok=" . (isset($result['ok']) ? ($result['ok'] ? 'true' : 'false') : 'N/A') . ", error=" . (isset($result['error']) ? $result['error'] : 'N/A') . "\n";

            // 발송 이력 기록
            if (isset($result['ok']) && $result['ok'] === true) {
                $this->db->query(
                    "INSERT INTO st_match_alimtalk_sent
                     (sender_type, receiver_mm_idx, target_key, source_key, sent_week, sent_dt, read_yn)
                     VALUES ('COMP', ?, ?, ?, ?, NOW(), 'N')
                     ON DUPLICATE KEY UPDATE sent_dt = NOW(), read_yn = 'N'",
                    array($recruit['mm_idx'], $resume['ru_idx'], $recruit['rc_idx'], $current_week)
                );
                $sent++;

                // AI 매칭 PUSH 알림 발송
                sendMatchPush($recruit['mm_idx'], 'COMP', $resume['ru_name'], $resume['ru_idx']);
            }

            // API 과부하 방지
            usleep(100000); // 0.1초
        }

        return $sent;
    }

    /**
     * 개인회원에게 매칭 공고 알림톡 발송
     * 같은 개인에게 같은 공고를 이번 주에 중복 발송하지 않음
     * 각 개인에게는 최신 1건만 발송
     */
    private function _sendMemberAlimtalk($matches, $current_week, $ppurio)
    {
        if (empty($matches)) return 0;

        // 개인별로 그룹핑하여 각 개인마다 가장 최신 1건만 추출
        $member_latest = array();
        foreach ($matches as $match) {
            $mem_idx = $match['resume']['mm_idx'];
            if (!isset($member_latest[$mem_idx]) ||
                strtotime($match['recruit']['reg_dt']) > strtotime($member_latest[$mem_idx]['recruit']['reg_dt'])) {
                $member_latest[$mem_idx] = $match;
            }
        }

        $sent = 0;
        $batch_limit = 100;

        foreach ($member_latest as $match) {
            if ($sent >= $batch_limit) break;

            $resume = $match['resume'];
            $recruit = $match['recruit'];

            // 중복 체크
            $result = $this->db->query(
                "SELECT ms_idx FROM st_match_alimtalk_sent
                 WHERE sender_type = 'MEM'
                   AND receiver_mm_idx = ?
                   AND target_key = ?
                   AND sent_week = ?",
                array($resume['mm_idx'], $recruit['rc_idx'], $current_week)
            );
            $dup_check = $result->row_array();

            if (!empty($dup_check)) continue;

            echo "[DEBUG] [개인 매칭] sendPersonalAlimtalk 호출 - phone=" . $resume['mm_phone'] . ", member_name=" . $resume['mm_name'] . ", recruit_name=" . $recruit['rc_name'] . "\n";
            $result = $ppurio->sendPersonalAlimtalk(
                $resume['mm_phone'],
                array(),
                array()
            );
            echo "[DEBUG] [개인 매칭] API 응답 - ok=" . (isset($result['ok']) ? ($result['ok'] ? 'true' : 'false') : 'N/A') . ", error=" . (isset($result['error']) ? $result['error'] : 'N/A') . "\n";

            // 발송 이력 기록
            if (isset($result['ok']) && $result['ok'] === true) {
                $this->db->query(
                    "INSERT INTO st_match_alimtalk_sent
                     (sender_type, receiver_mm_idx, target_key, source_key, sent_week, sent_dt, read_yn)
                     VALUES ('MEM', ?, ?, ?, ?, NOW(), 'N')
                     ON DUPLICATE KEY UPDATE sent_dt = NOW(), read_yn = 'N'",
                    array($resume['mm_idx'], $recruit['rc_idx'], $resume['ru_idx'], $current_week)
                );
                $sent++;

                // AI 매칭 PUSH 알림 발송
                sendMatchPush($resume['mm_idx'], 'MEM', $recruit['rc_name'], $recruit['rc_idx']);
            }

            // API 과부하 방지
            usleep(100000); // 0.1초
        }

        return $sent;
    }

    // 전날 등록된 추천인재 프로모션 발송 로그 수집
    // crontab: 0 4 * * * curl -s http://도메인/cron_proc/collect_promotion_send_log > /dev/null 2>&1
    public function collect_promotion_send_log()
    {
        // 어제 날짜로 조회
        $target_date = date('Y-m-d', strtotime('-1 day'));
       
        // 테스트: 오늘 날짜로 조회하기
       // $target_date = date('Y-m-d');
       
       // 1. 전날 등록된 M001(추천인재등록) 프로모션 조회
        $promotions = $this->db->query("
            SELECT p.pm_idx, p.rc_idx, r.ru_name, r.ru_idx
            FROM st_promotion p
            INNER JOIN st_resume r ON p.rc_idx = r.ru_idx
            WHERE p.pm_code = 'M001'
              AND DATE(p.reg_dt) = ?
              AND p.pm_status != 'C'
        ", array($target_date))->result_array();

        if (empty($promotions)) {
            echo "발송 대상 프로모션 없음 (날짜: {$target_date})\n";
            return;
        }

        echo "수집 대상 프로모션: " . count($promotions) . "건\n";

        // 2. 발송 대상 기업회원 조회 (기업명 포함)
        $companies = $this->db->query("
            SELECT mm_idx, mm_comp_name, mm_phone
            FROM st_member
            WHERE mm_type = 'COMP'
              AND mm_status = 'Y'
              AND mm_agree_ad = 'Y'
              AND alarm_adv = 'Y'
              AND mm_phone != ''
        ")->result_array();

        if (empty($companies)) {
            echo "발송 대상 기업회원 없음\n";
            return;
        }

        echo "발송 대상 기업회원: " . count($companies) . "개\n";

        // 기업명 배열 생성
        $comp_names = array_map(function($company) {
            return $company['mm_comp_name'];
        }, $companies);

        // 3. 각 프로모션별로 로그 기록
        $logged_count = 0;
        foreach ($promotions as $promo) {
            try {
                $this->db->query(
                    "INSERT INTO st_ppurio_send_log (pm_idx, ru_idx, ru_name, send_date, comp_count, comp_names, alarm_adv_count, reg_dt)
                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
                    array(
                        $promo['pm_idx'],
                        $promo['ru_idx'],
                        isset($promo['ru_name']) ? $promo['ru_name'] : '',
                        $target_date,
                        count($companies),
                        json_encode($comp_names),
                        count($companies)
                    )
                );
                $logged_count++;
            } catch (Exception $e) {
                echo "로그 기록 오류 (pm_idx: " . $promo['pm_idx'] . "): " . $e->getMessage() . "\n";
                continue;
            }
        }

        echo "완료 - 로그 기록 건수: {$logged_count}\n";
    }

    // 헬퍼: 지금이 설정된 알림 시간의 시간대에 포함되는가
    // 크론이 정확한 분에 실행되지 않을 수 있으므로 시간만 비교
    private function _isAlarmTimeNow($time_str) {
        if ($time_str === '' || $time_str === null) {
            return false;
        }

        return date('H') == substr($time_str, 0, 2);
    }

    // 헬퍼: 오늘이 설정된 매칭요일에 포함되는가
    private function _isMatchDayToday($days_str) {
        if ($days_str === '' || $days_str === null) {
            return false;
        }
        $today = strtoupper(date('D')); // Mon → MON
        return in_array($today, explode('^', $days_str));
    }

    // 헬퍼: 문자열을 배열로 변환 (^ 구분자)
    private function _parseConditions($cond_str) {
        if ($cond_str === '' || $cond_str === null) {
            return array();
        }
        return explode('^', $cond_str);
    }

    // 지원/제안 접수 알림톡 배치 발송 (평일 오전/오후 2회)
    public function send_request_alimtalk()
    {
        // 평일(월~금)만 처리 - 크론은 매일 고정시각 호출, 여기서 요일 필터링
        if ((int)date('N') >= 6) { //월요일은 1, 화요일은 2, 수요일은 3, 목요일은 4, 금요일은 5, 토요일은 6, 일요일은 7
            echo "주말 - 발송 대상 아님\n";
            return;
        }

        $ppurioPath = dirname(dirname(APPPATH)) . '/application/common/libraries/PpurioClient.php';
        if (!file_exists($ppurioPath)) {
            echo "PpurioClient 파일 없음\n";
            return;
        }
        require_once($ppurioPath);
        $ppurio = new CI_PpurioClient();

        $total_sent = 0;
        foreach (array('usual', 'subs') as $pks_type) {
            $setting = $this->ppurio_setting_model->getSetting($pks_type);
            if (empty($setting)) continue;

            // RECRUIT(지원) → 채용공고 소유자(기업)에게
            $total_sent += $this->_sendRequestBatch('RECRUIT', $setting['comp_request_alarm1_time'], $setting['comp_request_alarm2_time'], $ppurio);
            // RESUME(제안) → 이력서 소유자(개인)에게
            $total_sent += $this->_sendRequestBatch('RESUME', $setting['mem_request_alarm1_time'], $setting['mem_request_alarm2_time'], $ppurio);
        }

        if ($total_sent == 0) {
            echo "발송 대상 없음\n";
        } else {
            echo "완료 - 발송 건수: {$total_sent}\n";
        }
    }

    // 배치 발송 헬퍼: 시간창 계산 + 대상 조회 + 발송 처리
    private function _sendRequestBatch($mq_type, $alarm1_time, $alarm2_time, $ppurio)
    {
        $is_alarm1 = $this->_isAlarmTimeNow($alarm1_time);
        $is_alarm2 = $this->_isAlarmTimeNow($alarm2_time);
        if (!$is_alarm1 && !$is_alarm2) return 0;

        if ($is_alarm1) {
            // 오전 배치: 직전 영업일 alarm2_time ~ 오늘 alarm1_time
            $window_start = $this->_getPrevBusinessDay(date('Y-m-d')) . ' ' . $alarm2_time . ':00';
            $window_end   = date('Y-m-d') . ' ' . $alarm1_time . ':00';
        } else {
            // 오후 배치: 오늘 alarm1_time ~ 오늘 alarm2_time
            $window_start = date('Y-m-d') . ' ' . $alarm1_time . ':00';
            $window_end   = date('Y-m-d') . ' ' . $alarm2_time . ':00';
        }

        $rows = $this->db->query(
            "SELECT r.mq_idx, r.mm_idx, r.rc_idx, r.ru_idx
             FROM st_member_request r
             INNER JOIN st_member sm ON sm.mm_idx = r.mm_idx AND sm.mm_status = 'Y' /* 요청 보낸 사람이 탈퇴했으면 알림 안 보냄 */
             LEFT JOIN st_request_alimtalk_sent s ON s.mq_idx = r.mq_idx
             WHERE r.mq_type = ?
               AND r.reg_dt >= ?
               AND r.reg_dt < ?
               AND s.ras_idx IS NULL",
            array($mq_type, $window_start, $window_end)
        )->result_array();

        if (empty($rows)) return 0;

        return $mq_type == 'RECRUIT'
            ? $this->_processRecruitRequestAlimtalk($rows, $ppurio)
            : $this->_processResumeRequestAlimtalk($rows, $ppurio);
    }

    // 직전 영업일(평일) 계산
    private function _getPrevBusinessDay($date_str)
    {
        $timestamp = strtotime($date_str);
        do {
            $timestamp = strtotime('-1 day', $timestamp);
        } while ((int)date('N', $timestamp) >= 6);
        return date('Y-m-d', $timestamp);
    }

    // 지원(RECRUIT) 알림톡 발송 + 이력 기록
    private function _processRecruitRequestAlimtalk($rows, $ppurio)
    {
        $sent = 0;
        foreach ($rows as $row) {
            try {
                $recruit_info = $this->db->query(
                    "SELECT mm_idx FROM st_recruit WHERE rc_idx = ? AND rc_status = 'Y' AND rc_show_yn = 'Y'",
                    array($row['rc_idx'])
                )->row_array();
                if (!$recruit_info) continue;

                // 공고 주인(받는 기업)이 탈퇴(N/H)·정지 등 정상 회원이 아니면 발송하지 않음
                $recruit_owner = $this->member_model->getMember(array("mm_idx" => $recruit_info["mm_idx"]));
                if (!$recruit_owner || $recruit_owner["mm_status"] != "Y" || empty($recruit_owner["mm_phone"])) continue;

                $company_name = !empty($recruit_owner["mm_comp_name"]) ? $recruit_owner["mm_comp_name"] : "";
                echo "[DEBUG] [지원 알림] sendUnreadNotificationAlimtalk 호출 - phone=" . $recruit_owner["mm_phone"] . ", company_name=" . $company_name . "\n";
                $result = $ppurio->sendUnreadNotificationAlimtalk(
                    $recruit_owner["mm_phone"],
                    array(),
                    array("name" => $company_name)
                );
                echo "[DEBUG] [지원 알림] API 응답 - ok=" . (isset($result['ok']) ? ($result['ok'] ? 'true' : 'false') : 'N/A') . ", error=" . (isset($result['error']) ? $result['error'] : 'N/A') . "\n";

                if (isset($result['ok']) && $result['ok'] === true) {
                    $this->db->query(
                        "INSERT IGNORE INTO st_request_alimtalk_sent (mq_idx, mq_type, sent_dt) VALUES (?, 'RECRUIT', NOW())",
                        array($row['mq_idx'])
                    );
                    $sent++;
                }
            } catch (Exception $e) {
                //log_message('error', '[PPURIO][send_request_alimtalk][RECRUIT] mq_idx=' . $row['mq_idx'] . ' ' . $e->getMessage());
            }
            usleep(100000);
        }
        return $sent;
    }

    // 제안(RESUME) 알림톡 발송 + 이력 기록
    private function _processResumeRequestAlimtalk($rows, $ppurio)
    {
        $sent = 0;
        foreach ($rows as $row) {
            try {
                // 비공개(탈퇴 시 ru_show_yn='N')된 이력서는 발송하지 않음
                $target_resume = $this->member_model->getResume(array("ru_idx" => $row['ru_idx']));
                if (!$target_resume || $target_resume["ru_show_yn"] != "Y") continue;

                // 이력서 주인(받는 개인)이 탈퇴(N/H)·정지 등 정상 회원이 아니면 발송하지 않음
                $target_member = $this->member_model->getMember(array("mm_idx" => $target_resume["mm_idx"]));
                if (!$target_member || $target_member["mm_status"] != "Y" || empty($target_member["mm_phone"])) continue;

                $candidate_name = !empty($target_member["mm_name"]) ? $target_member["mm_name"] : "";
                echo "[DEBUG] [제안 알림] sendPersonalUnreadNotificationAlimtalk 호출 - phone=" . $target_member["mm_phone"] . ", member_name=" . $candidate_name . "\n";
                $result = $ppurio->sendPersonalUnreadNotificationAlimtalk(
                    $target_member["mm_phone"],
                    array(),
                    array("name" => $candidate_name)
                );
                echo "[DEBUG] [제안 알림] API 응답 - ok=" . (isset($result['ok']) ? ($result['ok'] ? 'true' : 'false') : 'N/A') . ", error=" . (isset($result['error']) ? $result['error'] : 'N/A') . "\n";

                if (isset($result['ok']) && $result['ok'] === true) {
                    $this->db->query(
                        "INSERT IGNORE INTO st_request_alimtalk_sent (mq_idx, mq_type, sent_dt) VALUES (?, 'RESUME', NOW())",
                        array($row['mq_idx'])
                    );
                    $sent++;
                }
            } catch (Exception $e) {
                //log_message('error', '[PPURIO][send_request_alimtalk][RESUME] mq_idx=' . $row['mq_idx'] . ' ' . $e->getMessage());
            }
            usleep(100000);
        }
        return $sent;
    }

    /**
     * 이력서를 ru_idx별로 주소 배열로 그룹화
     * 각 이력서의 주소들을 ad_idx 순서대로 배열에 저장 (지번주소 기준)
     */
    private function _groupResumesByRuIdx($resumes)
    {
        $grouped = array();

        foreach ($resumes as $resume) {
            $ru_idx = $resume['ru_idx'];
            if (!isset($grouped[$ru_idx])) {
                $grouped[$ru_idx] = array(
                    'resume' => $resume,
                    'addresses' => array()
                );
            }
            // 주소 배열에 추가 (중복 방지) - 지번(ad_jibun) 없으면 일반주소(ad_addr)로 대체
            $addr = !empty($resume['ad_jibun']) ? $resume['ad_jibun'] : $resume['ad_addr'];
            if (!in_array($addr, $grouped[$ru_idx]['addresses'])) {
                $grouped[$ru_idx]['addresses'][] = $addr;
            }
        }

        return $grouped;
    }

    /**
     * 공고를 rc_idx별로 주소 배열로 그룹화
     * 각 공고의 주소들을 ad_idx 순서대로 배열에 저장 (지번주소 기준)
     */
    private function _groupRecruitsByRcIdx($recruits)
    {
        $grouped = array();

        foreach ($recruits as $recruit) {
            $rc_idx = $recruit['rc_idx'];
            if (!isset($grouped[$rc_idx])) {
                $grouped[$rc_idx] = array(
                    'recruit' => $recruit,
                    'addresses' => array()
                );
            }
            // 주소 배열에 추가 (중복 방지) - 지번(ad_jibun) 없으면 일반주소(ad_addr)로 대체
            $addr = !empty($recruit['ad_jibun']) ? $recruit['ad_jibun'] : $recruit['ad_addr'];
            if (!in_array($addr, $grouped[$rc_idx]['addresses'])) {
                $grouped[$rc_idx]['addresses'][] = $addr;
            }
        }

        return $grouped;
    }

    /**
     * 주소에서 광역시도 + 시군구 추출 (2단어)
     * "경기도 안산시 단원구 773-7" → "경기도 안산시"
     * "서울특별시 강남구 역삼동" → "서울특별시 강남구"
     * 약칭 정규화: "인천시" → "인천광역시"
     * 주소가 없으면 null 반환 (빈 값끼리 오매칭되는 것을 방지하기 위해 ''이 아닌 null 사용)
     */
    private function _extractAreaSido($addr)
    {
        if (empty($addr)) return null;

        $parts = explode(' ', trim($addr));

        // 광역시 약칭 정규화 (약칭 → 정식명)
        $sido_alias = array(
            '인천시' => '인천광역시',
            '대구시' => '대구광역시',
            '광주시' => '광주광역시',
            '대전시' => '대전광역시',
            '부산시' => '부산광역시',
            '울산시' => '울산광역시',
            '서울시' => '서울특별시',
            '세종시' => '세종특별자치시',
            '제주시' => '제주특별자치도'
            
        );

        if (isset($sido_alias[$parts[0]])) {
            $parts[0] = $sido_alias[$parts[0]];
        }

        // 첫 번째 단어가 시/도인지 확인
        if (!empty($parts[0])) {
            $this->db->select('sido_name');
            $this->db->where('sido_name', $parts[0]);
            $this->db->where('step', 1);
            $this->db->limit(1);
            $result = $this->db->get('location_data')->row_array();

            if ($result) {
                // 시/도 + 두 번째 단어(시/군/구) 반환
                if (!empty($parts[1])) {
                    return $parts[0] . ' ' . $parts[1];
                } else {
                    return $parts[0];
                }
            }
        }

        // sido를 찾지 못한 경우 처음 두 단어 반환 또는 첫 단어만 반환
        if (count($parts) >= 2) {
            return $parts[0] . ' ' . $parts[1];
        }
        return $parts[0];
    }

    // 델피콤 쪽에서 자체 만료된(로컬에서 갱신하지 않은) 가상번호를 IDLE로 회수
    // crontab: 0 5 * * * curl -s https://nowjob.co.kr/cron_proc/release_expired_virtual_numbers > /dev/null 2>&1
    public function release_expired_virtual_numbers()
    {
        $this->load->model('membermodel', 'member_model');
        $released = $this->member_model->releaseExpiredVirtualNumbers();
        echo "만료된 가상번호 회수: {$released}건\n";
    }

    // 채팅 첨부파일 30일 경과분 자동 삭제
    // crontab: 0 2 * * * curl -s https://nowjob.co.kr/cron_proc/cleanup_expired_chat_files > /dev/null 2>&1
    public function cleanup_expired_chat_files()
    {
        $target_dirs = [
            FCPATH . 'resource/uploads/chat',
            FCPATH . 'resource/uploads/chat/resize',
        ];
         $expire_seconds = 30 * 24 * 60 * 60; // 30일
        //$expire_seconds = 1 * 24 * 60 * 60; // 1일 (테스트용)
        $now = time(); // 현재 시간
        $deleted = 0;

        foreach ($target_dirs as $dir) {
            if (!is_dir($dir)) continue;
            foreach (glob($dir . '/*') as $file) {
                if (!is_file($file)) continue;
                // filemtime은 파일의 마지막 수정 시간
                // 채팅 파일은 업로드 후 수정되지 않으므로 filemtime = 업로드 시간
                if (($now - filemtime($file)) > $expire_seconds) {
                    if (@unlink($file)) {
                        $deleted++;
                    }
                }
            }
        }

        echo "[cleanup_expired_chat_files] 삭제된 파일 수: {$deleted}\n";
    }

    // 채용공고 임시 사진 24시간 경과분 삭제 (등록완료 전 임시 폴더만 대상, 정식 폴더는 건드리지 않음)
    // crontab: 0 4 * * * curl -s https://nowjob.co.kr/cron_proc/cleanup_temp_recruit_images > /dev/null 2>&1
    public function cleanup_temp_recruit_images()
    {
        $temp_base = FCPATH . 'resource/uploads/temp';
        $expire_seconds = 24 * 60 * 60; // 24시간
        $now = time();
        $deleted = 0;

        if (!is_dir($temp_base)) {
            echo "[cleanup_temp_recruit_images] 임시 폴더 없음\n";
            return;
        }

        // 회원별(temp/{mm_idx}) 하위 폴더와 resize 변형까지 재귀로 순회
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($temp_base, FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if (!$file->isFile()) continue;
            if (($now - $file->getMTime()) > $expire_seconds) {
                if (@unlink($file->getPathname())) {
                    $deleted++;
                }
            }
        }

        echo "[cleanup_temp_recruit_images] 삭제된 파일 수: {$deleted}\n";
    }

    // 전원 퇴장(CLOSED) 후 30일 경과한 채팅방 메시지 삭제
    // crontab: 0 3 * * * curl -s https://nowjob.co.kr/cron_proc/cleanup_closed_room_messages > /dev/null 2>&1
    public function cleanup_closed_room_messages()
    {
        $uri = get_mongo_uri();
        $dbName = get_mongo_db_name();
        $expire_seconds = 30 * 24 * 60 * 60; // 30일
        //$expire_seconds = 1 * 24 * 60 * 60; // 1일 (테스트용)

        try {
            @$mongo = new Mongo($uri);
            $db = $mongo->$dbName;
            $collection = $db->room;
            echo "[DEBUG] MongoDB 연결 성공\n";
        } catch (Exception $e) {
            echo "❌ MongoDB 연결 오류: " . $e->getMessage() . "\n";
            return;
        }

        $cursor = $collection->find(
            ['rm_status' => 'CLOSED'],
            ['rm_idx' => 1, 'rm_users' => 1, 'rm_messages' => 1]
        );

        $now = time();
        $cleared = 0;
        $checked = 0;

        foreach ($cursor as $room) {
            $checked++;

            // 이미 비어있으면 skip (멱등성)
            if (empty($room['rm_messages'])) continue;

            // rm_users[].exit_dt 중 최댓값 = 방이 CLOSED된 시점
            $max_exit_sec = null;
            if (!empty($room['rm_users'])) {
                foreach ($room['rm_users'] as $u) {
                    if (isset($u['exit_dt']) && $u['exit_dt'] instanceof MongoDate) {
                        if ($max_exit_sec === null || $u['exit_dt']->sec > $max_exit_sec) {
                            $max_exit_sec = $u['exit_dt']->sec;
                        }
                    }
                }
            }

            // exit_dt를 알 수 없으면 판단 불가 - skip
            if ($max_exit_sec === null) continue;

            if (($now - $max_exit_sec) < $expire_seconds) continue;

            $collection->update(
                ['rm_idx' => $room['rm_idx']],
                ['$set' => ['rm_messages' => []]]
            );
            $cleared++;
            echo "[DEBUG] rm_idx={$room['rm_idx']}: 메시지 삭제 완료\n";
        }

        echo "[cleanup_closed_room_messages] 확인: {$checked}건, 메시지 삭제: {$cleared}건\n";
    }

    // 유료상품 보유 공고 자동 재등록 (새로운 공고 row를 매일 생성)
    // crontab: 0 1 * * * curl -s https://nowjob.co.kr/cron_proc/auto_reregister_paid_recruit > /dev/null 2>&1
    public function auto_reregister_paid_recruit()
    {
        $this->load->model('recruitmodel', 'recruit_model');
        $result = $this->recruit_model->autoReregisterPaidRecruit();

        $message = $result["message"];
        $success = isset($result["success"]) ? $result["success"] : 0;
        $fail = isset($result["fail"]) ? $result["fail"] : 0;

        echo "[auto_reregister_paid_recruit] {$message} | 성공: {$success}건, 실패: {$fail}건\n";
    }

    // 15일 지난 탈퇴 회원 데이터 실제 정리 (공고/이력서/중고/파일 삭제, 주문 익명화, st_member 개인정보 삭제 등)
    // 되돌릴 수 없는 삭제 작업이라 CLI 실행이거나 서버 자신(localhost)에서 온 요청만 허용
    // 운영/개발 사이트가 같은 서버에 있어 http://localhost 는 기본 가상호스트(운영)로 가므로, 사이트를 명시해서 호출한다
    // (--resolve로 도메인을 127.0.0.1에 붙여 REMOTE_ADDR은 localhost로 유지)
    // crontab: 10 4 * * * curl -s --resolve nowjob.co.kr:443:127.0.0.1 https://nowjob.co.kr/cron_proc/cleanup_withdrawn_members > /dev/null 2>&1
    public function cleanup_withdrawn_members()
    {
        // 개발 사이트(설치 경로에 dev_nowapk 포함)에서는 실행하지 않음 - 운영에서만 실제 정리
        if (strpos(FCPATH, 'dev_nowapk') !== false) {
            echo "[cleanup_withdrawn_members] 개발 사이트라 실행을 건너뜁니다.\n";
            return;
        }

        $client_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $is_localhost = in_array($client_ip, array('127.0.0.1', '::1'));
        if (!is_cli() && !$is_localhost) {
            header("HTTP/1.1 403 Forbidden");
            echo "Forbidden";
            exit;
        }

        // 한 번에 처리할 최대 건수. 운영 DB에 이미 15일 지난 미정리 회원이 많이 밀려 있을 수 있어
        // 기본값을 낮게 두고, 필요하면 ?limit= 파라미터로 늘려서 수동 실행한다.
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        if ($limit <= 0 || $limit > 200) {
            $limit = 20;
        }

        $result = $this->member_model->cleanupWithdrawnMembers($limit);

        echo "[cleanup_withdrawn_members] fire_dt 보정: {$result['backfilled']}건, 정리 성공: {$result['processed']}건, 실패: {$result['fail']}건\n";
    }

    // 테스트용: 특정 이력서로만 매칭 테스트
    public function test_match_single_resume()
    {
        $ru_idx = 3094;
        $mm_idx = 4818;

        echo "[TEST] 이력서 테스트 시작 - ru_idx={$ru_idx}, mm_idx={$mm_idx}\n";

        // 특정 이력서 조회 (matchmodel 사용)
        $all_resumes = $this->_getActiveResumes(); // 전체 활성 이력서 조회
        $resumes = array_values(array_filter($all_resumes, function($r) use ($ru_idx, $mm_idx) {
            return $r['ru_idx'] == $ru_idx && $r['mm_idx'] == $mm_idx;
        })); // 특정 이력서만 필터링

        if (empty($resumes)) {
            echo "❌ 이력서 없음\n";
            return;
        }

        echo "[TEST] 조회된 이력서: " . count($resumes) . "건\n";
        echo "[TEST] 이력서 정보:\n";
        echo json_encode($resumes[0], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";

        // 테스트용: 처음 50개 공고만 조회 (빠른 테스트)
        $all_recruits = $this->_getActiveRecruits();
        $recruits = array_slice($all_recruits, 0, 50); // array_slice(배열, 시작위치, 개수)로 앞에서 50개만 잘라냅니다.
        echo "[TEST] 조회된 공고: " . count($recruits) . "건 (테스트용 제한, 전체 {" . count($all_recruits) . "})\n";

        // 지역 매칭만 테스트 (area 조건만)
        $comp_cond_arr = array('area');
        echo "[TEST] 매칭 조건: " . implode(',', $comp_cond_arr) . "\n";

        $matches = $this->_calculateCorpMatches($resumes, $recruits, $comp_cond_arr); // 이력서 목록, 공고 목록, 조건 목록을 넘겨 실제 매칭을 계산

        echo "[TEST] 매칭 결과: " . count($matches) . "건\n";
        if (!empty($matches)) {
            foreach ($matches as $match) {
                echo "[TEST] ✅ rc_idx={$match['recruit']['rc_idx']}, 공고지역={$match['recruit']['ad_addr']}, 매칭됨\n";
            }
        } else {
            echo "[TEST] 매칭된 공고 없음 - /var/log/php_errors.log에서 실패 원인 확인\n";
        }

        echo "[TEST] 이력서 주소 목록:\n";
        foreach ($resumes as $resume) {
            $addr = !empty($resume['ad_jibun']) ? $resume['ad_jibun'] : $resume['ad_addr'];
            if (!empty($addr)) {
                $extracted = $this->_extractAreaSido($addr);
                echo "[TEST]   주소: {$addr} → 추출: {$extracted}\n";
            }
        }
    }

    /**
     * 테스트용: 기업회원의 공고(rc_idx)에 활성 이력서가 몇 건 매칭되는지,
     * 매칭되지 않은 이력서는 어떤 조건에서 탈락했는지 출력
     * 읽기 전용 - 알림톡 발송 및 이력 기록 없음
     *
     * 사용법: /cron_proc/test_match_by_recruit/125/25   (mm_idx/rc_idx)
     */
    public function test_match_by_recruit($mm_idx = 4, $rc_idx = 313)
    {
        $mm_idx = intval($mm_idx);
        $rc_idx = intval($rc_idx);
        echo "[TEST] 기업 매칭 테스트 - mm_idx={$mm_idx}, rc_idx={$rc_idx}\n";

        // 1. 대상 공고가 활성 공고 목록(발송 대상)에 있고, 해당 기업 소유인지 확인
        $target_rows = array_filter($this->_getActiveRecruits(), function ($r) use ($mm_idx, $rc_idx) {
            return $r['mm_idx'] == $mm_idx && $r['rc_idx'] == $rc_idx;
        });
        if (empty($target_rows)) {
            echo "❌ 활성 공고 목록에 없음 - 탈락 원인 확인\n";
            $this->_diagnoseInactiveRecruit($mm_idx, $rc_idx);
            return;
        }

        // 주소가 여러 개면 행이 여러 개로 나오므로 rc_idx 기준으로 묶음
        $recruits_by_rc = $this->_groupRecruitsByRcIdx($target_rows);
        $recruit_data = $recruits_by_rc[$rc_idx];
        $recruit = $recruit_data['recruit'];
        echo "✅ 활성 공고 확인 - 회사: {$recruit['mm_comp_name']}, 공고: {$recruit['rc_name']}, 직종: {$recruit['rc_work']}, 근무형태: {$recruit['rc_work_type']}, 급여타입: {$recruit['rc_price_type']}\n";
        echo "[TEST] 공고 조건 - 성별: {$recruit['rc_sex']}, 나이: {$recruit['rc_sage']}~{$recruit['rc_eage']}, 국적: {$recruit['rc_nation']}\n";

        // 2. 공고 지역 (area 조건용)
        $area_cache = array();
        $recruit_areas = $this->_collectAreas($recruit_data['addresses'], $area_cache);
        echo "[TEST] 공고 지역: " . (empty($recruit_areas) ? '없음' : implode(', ', $recruit_areas)) . "\n";

        // 3. 활성 이력서 전체 + 이력서별 지역 미리 계산 (reg_dt 최신순)
        $resumes_by_ru = $this->_groupResumesByRuIdx($this->_getActiveResumes());
        echo "[TEST] 활성 이력서: " . count($resumes_by_ru) . "건\n";

        $resume_areas_by_ru = array();
        foreach ($resumes_by_ru as $ru_idx => $resume_data) {
            $resume_areas_by_ru[$ru_idx] = $this->_collectAreas($resume_data['addresses'], $area_cache);
        }

        // 4. 설정 타입별(usual, subs)로 기업 매칭 조건 적용
        foreach (array('usual', 'subs') as $pks_type) {
            $setting = $this->ppurio_setting_model->getSetting($pks_type);
            if (empty($setting)) {
                echo "⚠️  {$pks_type} 설정 없음\n";
                continue;
            }

            $cond_arr = $this->_parseConditions($setting['comp_match_cond']);
            echo "\n[TEST] === {$pks_type} 설정 ===\n";
            echo "[TEST] 기업 발송 요일: {$setting['comp_match_days']}, 시간: {$setting['comp_match_alarm1_time']}\n";
            echo "[TEST] 매칭 조건: " . implode(', ', $cond_arr) . "\n";

            $matched = array();     // 매칭된 이력서 (reg_dt 최신순)
            $fail_lines = array();  // 탈락 이력서 상세
            $fail_count = array();  // 사유별 건수 (한 이력서가 여러 사유에 걸릴 수 있음)

            foreach ($resumes_by_ru as $ru_idx => $resume_data) {
                $resume = $resume_data['resume'];
                $label = "ru_idx={$ru_idx} ({$resume['ru_name']} / {$resume['ru_work']})";

                $reasons = $this->_getMatchFailReasons($resume, $recruit, $cond_arr, $resume_areas_by_ru[$ru_idx], $recruit_areas);
                if (empty($reasons)) {
                    $matched[] = $label;
                    continue;
                }

                foreach ($reasons as $reason) {
                    $fail_count[$reason] = isset($fail_count[$reason]) ? $fail_count[$reason] + 1 : 1;
                }
                $fail_lines[] = "  ❌ {$label} - 탈락 사유: " . implode(', ', $reasons);
            }

            echo "✅ 매칭 이력서: " . count($matched) . "건\n";
            foreach ($matched as $line) {
                echo "  ✅ {$line}\n";
            }
            if (!empty($matched)) {
                echo "[TEST] 기업 발송 시 대상 이력서: 최신 1건 → {$matched[0]}\n";
            }

            echo "[TEST] 탈락 이력서: " . count($fail_lines) . "건\n";
            echo "[TEST] 사유별 건수 (중복 포함):\n";
            foreach ($fail_count as $reason => $cnt) {
                echo "  - {$reason}: {$cnt}건\n";
            }
            echo "[TEST] 탈락 상세:\n";
            foreach ($fail_lines as $line) {
                echo $line . "\n";
            }
        }

        // 5. 이번 주 이미 발송된 기업 알림 이력 (발송 로직의 중복 방지 기준과 동일)
        $current_week = date('Y-W');
        $sent_rows = $this->db->query(
            "SELECT target_key, sent_dt FROM st_match_alimtalk_sent
             WHERE sender_type = 'COMP' AND receiver_mm_idx = ? AND sent_week = ?",
            array($mm_idx, $current_week)
        )->result_array();
        echo "\n[TEST] 이번 주({$current_week}) 기업 매칭 발송 이력: " . count($sent_rows) . "건\n";
        foreach ($sent_rows as $sent) {
            echo "  - target_key(ru_idx)={$sent['target_key']}, 발송시각={$sent['sent_dt']}\n";
        }
    }

    /**
     * 주소 목록을 시/도+시/군/구 지역 목록으로 변환 (null 제외, 중복 제거)
     * $area_cache: 주소 → 지역 캐시 (DB 조회 최소화, 참조로 공유)
     */
    private function _collectAreas($addresses, &$area_cache)
    {
        $areas = array();
        foreach ($addresses as $addr) {
            if (!isset($area_cache[$addr])) {
                $area_cache[$addr] = $this->_extractAreaSido($addr);
            }
            if ($area_cache[$addr] !== null) {
                $areas[] = $area_cache[$addr];
            }
        }
        return array_values(array_unique($areas));
    }

    /**
     * 이력서-공고 1쌍의 탈락 사유 목록 반환 (빈 배열이면 매칭)
     * 조건을 하나씩 따로 검사해서 어떤 조건에서 막혔는지 구분
     */
    private function _getMatchFailReasons($resume, $recruit, $cond_arr, $resume_areas, $recruit_areas)
    {
        $reasons = array();

        foreach ($cond_arr as $cond) {
            if ($cond === '') continue;

            if ($cond == 'area') {
                // 지역: 이력서와 공고가 시/도+시/군/구 기준으로 하나라도 겹쳐야 함
                $area_common = array_intersect($resume_areas, $recruit_areas);
                if (empty($area_common)) {
                    $reasons[] = 'area';
                }
                continue;
            }

            // 나머지 조건은 기존 매칭 로직에 해당 조건 하나만 넘겨서 검사
            if (!$this->_isMatched($resume, $recruit, array($cond))) {
                $reasons[] = $cond;
            }
        }

        return $reasons;
    }

    /**
     * 활성 목록에서 빠진 공고의 원인 확인 (발송 대상 조회 조건과 동일한 항목)
     */
    private function _diagnoseInactiveRecruit($mm_idx, $rc_idx)
    {
        $row = $this->db->query(
            "SELECT rc.mm_idx AS rc_mm_idx, rc.rc_status, rc.rc_show_yn, rc.rc_sdate, rc.rc_edate,
                    (rc.rc_sdate <= NOW()) AS started_yn, (rc.rc_edate >= NOW()) AS not_ended_yn,
                    m.mm_type, m.mm_status, m.mm_agree_ad, m.alarm_resume, m.mm_phone,
                    (SELECT COUNT(*) FROM st_address a
                      WHERE a.mm_idx = rc.mm_idx AND a.ad_key = rc.rc_idx
                        AND (a.ad_type = 'RECRUIT' OR a.ad_type LIKE 'recruit_expose%')) AS addr_cnt
             FROM st_recruit rc
             INNER JOIN st_member m ON rc.mm_idx = m.mm_idx
             WHERE rc.rc_idx = ?",
            array($rc_idx)
        )->row_array();

        if (empty($row)) {
            echo "❌ st_recruit/st_member에 rc_idx={$rc_idx} 공고가 없음\n";
            return;
        }

        if ($row['rc_mm_idx'] != $mm_idx) {
            echo "❌ 공고 소유자는 mm_idx={$row['rc_mm_idx']} 입니다 (입력한 mm_idx={$mm_idx}와 다름)\n";
        }

        // 활성 공고 조회(_getActiveRecruits)의 조건과 같은 순서로 점검
        $checks = array(
            array('공고 상태 (rc_status = Y)', $row['rc_status'] == 'Y', $row['rc_status']),
            array('공고 노출 (rc_show_yn = Y)', $row['rc_show_yn'] == 'Y', $row['rc_show_yn']),
            array('게재 시작 (rc_sdate <= NOW)', $row['started_yn'] == 1, $row['rc_sdate']),
            array('게재 종료 전 (rc_edate >= NOW)', $row['not_ended_yn'] == 1, $row['rc_edate']),
            array('회원 유형 (mm_type = COMP)', $row['mm_type'] == 'COMP', $row['mm_type']),
            array('회원 상태 (mm_status = Y)', $row['mm_status'] == 'Y', $row['mm_status']),
            array('광고 동의 (mm_agree_ad = Y)', $row['mm_agree_ad'] == 'Y', $row['mm_agree_ad']),
            array('알림 설정 (alarm_resume = Y)', $row['alarm_resume'] == 'Y', $row['alarm_resume']),
            array('휴대폰 번호 존재', $row['mm_phone'] != '', $row['mm_phone'] != '' ? '있음' : '없음'),
            array('공고 주소 존재 (st_address RECRUIT)', $row['addr_cnt'] > 0, $row['addr_cnt'] . '건'),
        );

        foreach ($checks as $check) {
            echo ($check[1] ? "  ✅ " : "  ❌ ") . $check[0] . " → " . $check[2] . "\n";
        }
    }

}
