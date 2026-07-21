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
        ";

        // 두 설정 타입(usual, subs)에 대해 반복 처리
        $all_sent = 0;
        foreach (array('usual', 'subs') as $pks_type) {
            $setting = $this->ppurio_setting_model->getSetting($pks_type);
            if (empty($setting)) {
                continue;
            }

            // 시간 조건 확인: 1차 또는 2차 알림 시각에 해당하는가
            $is_alarm1 = $this->_isAlarmTimeNow($setting['comp_apply_alarm1_time']);
            $is_alarm2 = $this->_isAlarmTimeNow($setting['comp_apply_alarm2_time']);

            // 이 타입에서는 시간 조건이 맞지 않으면 skip
            if (!$is_alarm1 && !$is_alarm2) {
                continue;
            }

            // 설정에서 미열람 알림 재발송 일수 읽기
            $unread_day = (int)$setting['comp_apply_unread_day'];

            // 2차 알림 시각인 경우 신규 채팅방 + N일 주기 재발송
            if ($is_alarm2 && !$is_alarm1) {
                // 2차: 오늘 1차 알림시간 이후 새로 생긴 채팅방 + N일 후 1회 재발송 (날짜 기준)
                $alarm_time_hm = $setting['comp_apply_alarm1_time'];
                $time_where = "
                  AND r.reg_dt >= CONCAT(CURDATE(), ' {$alarm_time_hm}:00')
                  AND (s.as_idx IS NULL OR (s.sent_count = 1 AND DATE(s.sent_dt) <= DATE_SUB(CURDATE(), INTERVAL ? DAY)))
                ";
            } else {
                // 1차: 미발송 또는 N일 이상 지난 대상 (날짜 기준) - 1회 재발송만 수행
                $time_where = "
                  AND (s.as_idx IS NULL OR (s.sent_count = 1 AND DATE(s.sent_dt) <= DATE_SUB(CURDATE(), INTERVAL ? DAY)))
                ";
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
                LEFT JOIN st_alimtalk_sent s ON r.rm_idx = s.rm_idx
                {$where_condition}
            ", array($unread_day))->result_array();

            if (empty($rooms)) {
                continue;
            }

            $sent = $this->_processUnreadAlimtalk($rooms);
            $all_sent += $sent;
        }

        if ($all_sent == 0) {
            echo "발송 대상 없음\n";
        } else {
            echo "완료 - 발송 건수: {$all_sent}\n";
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
        } catch (Exception $e) {
            echo "MongoDB 연결 오류: " . $e->getMessage() . "\n";
            return 0;
        }

        // PpurioClient 로드
        $ppurioPath = dirname(dirname(APPPATH)) . '/application/common/libraries/PpurioClient.php';
        if (!file_exists($ppurioPath)) {
            echo "PpurioClient 파일 없음: " . $ppurioPath . "\n";
            return 0;
        }
        require_once($ppurioPath);
        $ppurio = new CI_PpurioClient();

        $sent_count = 0;

        // 각 채팅방에서 미확인 메시지 확인 후 발송
        foreach ($rooms as $row) {
            try {
                $room = $collection->findOne(array('rm_idx' => (int)$row['rm_idx']));
                if (!$room || !isset($room['rm_messages']) || empty($room['rm_messages'])) {
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

                if (!$has_unread_recruit_msg) continue;

                // 미확인 → 카카오 알림톡 발송
                $result = $ppurio->sendUnreadNotificationAlimtalk(
                    $row['mm_phone'],
                    array(),
                    array()
                );

                $is_success = isset($result['ok']) && $result['ok'] === true;

                if ($is_success) {
                    // 발송 성공 시에만 이력 기록
                    $this->db->query(
                        "INSERT INTO st_alimtalk_sent (rm_idx, comp_mm_idx, sent_dt, sent_count)
                         VALUES (?, ?, NOW(), 1)
                         ON DUPLICATE KEY UPDATE sent_dt = NOW(), sent_count = sent_count + 1",
                        array($row['rm_idx'], $row['comp_mm_idx'])
                    );
                    $sent_count++;
                }

            } catch (Exception $e) {
                continue;
            }
        }

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
        ";

        // 두 설정 타입(usual, subs)에 대해 반복 처리
        $all_sent = 0;
        foreach (array('usual', 'subs') as $pks_type) {
            $setting = $this->ppurio_setting_model->getSetting($pks_type);
            if (empty($setting)) {
                continue;
            }

            // 시간 조건 확인: 1차 또는 2차 알림 시각에 해당하는가
            $is_alarm1 = $this->_isAlarmTimeNow($setting['comp_apply_alarm1_time']);
            $is_alarm2 = $this->_isAlarmTimeNow($setting['comp_apply_alarm2_time']);

            // 이 타입에서는 시간 조건이 맞지 않으면 skip
            if (!$is_alarm1 && !$is_alarm2) {
                continue;
            }

            // 설정에서 미열람 알림 재발송 일수 읽기
            $unread_day = (int)$setting['mem_apply_unread_day'];

            // 2차 알림 시각인 경우 신규 채팅방 + N일 주기 재발송
            if ($is_alarm2 && !$is_alarm1) {
                // 2차: 오늘 1차 알림시간 이후 새로 생긴 채팅방 + N일 후 1회 재발송 (날짜 기준)
                $alarm_time_hm = $setting['mem_apply_alarm1_time'];
                $time_where = "
                  AND r.reg_dt >= CONCAT(CURDATE(), ' {$alarm_time_hm}:00')
                  AND (s.as_idx IS NULL OR (s.sent_count = 1 AND DATE(s.sent_dt) <= DATE_SUB(CURDATE(), INTERVAL ? DAY)))
                ";
            } else {
                // 1차: 미발송 또는 N일 이상 지난 대상 (날짜 기준) - 1회 재발송만 수행
                $time_where = "
                  AND (s.as_idx IS NULL OR (s.sent_count = 1 AND DATE(s.sent_dt) <= DATE_SUB(CURDATE(), INTERVAL ? DAY)))
                ";
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
                LEFT JOIN st_alimtalk_sent_mem s ON r.rm_idx = s.rm_idx
                {$where_condition}
            ", array($unread_day))->result_array();

            if (empty($rooms)) {
                continue;
            }

            $sent = $this->_processUnreadAlimtalkMem($rooms);
            $all_sent += $sent;
        }

        if ($all_sent == 0) {
            echo "발송 대상 없음\n";
        } else {
            echo "완료 - 발송 건수: {$all_sent}\n";
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
        } catch (Exception $e) {
            echo "MongoDB 연결 오류: " . $e->getMessage() . "\n";
            return 0;
        }

        // PpurioClient 로드
        $ppurioPath = dirname(dirname(APPPATH)) . '/application/common/libraries/PpurioClient.php';
        if (!file_exists($ppurioPath)) {
            echo "PpurioClient 파일 없음: " . $ppurioPath . "\n";
            return 0;
        }
        require_once($ppurioPath);
        $ppurio = new CI_PpurioClient();

        $sent_count = 0;

        // 각 채팅방에서 미확인 메시지 확인 후 발송
        foreach ($rooms as $row) {
            try {
                $room = $collection->findOne(array('rm_idx' => (int)$row['rm_idx']));
                if (!$room || !isset($room['rm_messages']) || empty($room['rm_messages'])) {
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

                if (!$has_unread_recruit_msg) continue;

                // 미확인 → 카카오 알림톡 발송
                $result = $ppurio->sendPersonalUnreadNotificationAlimtalk(
                    $row['mm_phone'],
                    array(),
                    array()
                );

                $is_success = isset($result['ok']) && $result['ok'] === true;

                if ($is_success) {
                    // 발송 성공 시에만 이력 기록
                    $this->db->query(
                        "INSERT INTO st_alimtalk_sent_mem (rm_idx, mem_mm_idx, sent_dt, sent_count)
                         VALUES (?, ?, NOW(), 1)
                         ON DUPLICATE KEY UPDATE sent_dt = NOW(), sent_count = sent_count + 1",
                        array($row['rm_idx'], $row['mem_mm_idx'])
                    );
                    $sent_count++;
                }

            } catch (Exception $e) {
                continue;
            }
        }

        return $sent_count;
    }

    // 조건 기반 매칭 알림톡 발송 (설정값 기반)
    public function weekly_match_alimtalk()
    {
        $start_time = microtime(true);
        $current_week = date('Y-W'); // 주차 식별자 (YYYY-WW)

        // 1. PpurioClient 로드
        $ppurioPath = dirname(dirname(APPPATH)) . '/application/common/libraries/PpurioClient.php';
        if (!file_exists($ppurioPath)) {
            echo "PpurioClient 파일 없음: " . $ppurioPath . "\n";
            return;
        }
        require_once($ppurioPath);
        $ppurio = new CI_PpurioClient();

        // 2. 활성 이력서 조회 (주소 포함)
        $resumes = $this->_getActiveResumes();
        if (empty($resumes)) {
            echo "활성 이력서 없음\n";
            return;
        }
        // 3. 게재 중인 채용공고 조회 (주소 포함)
        $recruits = $this->_getActiveRecruits();
        if (empty($recruits)) {
            echo "게재 중인 채용공고 없음\n";
            return;
        }

        // 두 설정 타입(usual, subs)에 대해 반복 처리
        $total_sent_corp = 0;
        $total_sent_member = 0;

        foreach (array('usual', 'subs') as $pks_type) {
            $setting = $this->ppurio_setting_model->getSetting($pks_type);
            if (empty($setting)) {
                continue;
            }

            // 기업 매칭 발송 여부 판단
            $comp_due = $this->_isMatchDayToday($setting['comp_match_days']) &&
                        ($this->_isAlarmTimeNow($setting['comp_match_alarm1_time']) ||
                         $this->_isAlarmTimeNow($setting['comp_match_alarm2_time']));

            // 개인 매칭 발송 여부 판단
            $mem_due = $this->_isMatchDayToday($setting['mem_match_days']) &&
                       ($this->_isAlarmTimeNow($setting['mem_match_alarm1_time']) ||
                        $this->_isAlarmTimeNow($setting['mem_match_alarm2_time']));

            // 이 타입에서는 둘 다 발송 대상이 아니면 skip
            if (!$comp_due && !$mem_due) {
                continue;
            }

            // 기업 매칭 조건 파싱
            // area^nation^sex^age^work^price^work_type	
            $comp_cond_arr = $this->_parseConditions($setting['comp_match_cond']);
            // 개인 매칭 조건 파싱
            $mem_cond_arr = $this->_parseConditions($setting['mem_match_cond']);

            // 기업 매칭 처리
            if ($comp_due) {
                $matches_for_corp = $this->_calculateCorpMatches($resumes, $recruits, $comp_cond_arr);
                $sent_corp = $this->_sendCorpAlimtalk($matches_for_corp, $current_week, $ppurio);
                $total_sent_corp += $sent_corp;
            }

            // 개인 매칭 처리
            if ($mem_due) {
                $matches_for_member = $this->_calculateMemMatches($resumes, $recruits, $mem_cond_arr);
                $sent_member = $this->_sendMemberAlimtalk($matches_for_member, $current_week, $ppurio);
                $total_sent_member += $sent_member;
            }
        }

        // 4. 실행 결과 출력
        $elapsed = round(microtime(true) - $start_time, 2);
        if ($total_sent_corp == 0 && $total_sent_member == 0) {
            echo "발송 대상 시각 아님\n";
        } else {
            echo "매칭 알림톡 발송 완료 - 기업: {$total_sent_corp}건, 개인: {$total_sent_member}건, 소요시간: {$elapsed}초\n";
        }
    }

    /**
     * 기업 대상 매칭 계산
     */
    private function _calculateCorpMatches($resumes, $recruits, $comp_cond_arr)
    {
        $matches_for_corp = array();

        // 지역 조건이 켜져있으면 → groupByArea 후 지역별 매칭
        if (in_array('area', $comp_cond_arr)) {
            $resume_by_area = $this->_groupByArea($resumes);
            $recruit_by_area = $this->_groupByArea($recruits);

            foreach ($resume_by_area as $area => $area_resumes) {
                 //area:특정지역 인덱스 ex:  $area = '서울시 강남구' area_resumes: 특정지역의 이력서들
                if (!isset($recruit_by_area[$area])) continue; // 지역이 같은 공고가 없다
                $area_recruits = $recruit_by_area[$area];

                foreach ($area_resumes as $resume) {
                    foreach ($area_recruits as $recruit) {
                        if ($this->_isMatched($resume, $recruit, $comp_cond_arr)) {
                            $matches_for_corp[] = array(
                                'recruit' => $recruit,
                                'resume' => $resume
                            );
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

        // 지역 조건이 켜져있으면 → groupByArea 후 지역별 매칭
        if (in_array('area', $mem_cond_arr)) {
            $resume_by_area = $this->_groupByArea($resumes);
            $recruit_by_area = $this->_groupByArea($recruits);

            foreach ($resume_by_area as $area => $area_resumes) {
                if (!isset($recruit_by_area[$area])) continue;
                $area_recruits = $recruit_by_area[$area];

                foreach ($area_resumes as $resume) {
                    foreach ($area_recruits as $recruit) {
                        if ($this->_isMatched($resume, $recruit, $mem_cond_arr)) {
                            $matches_for_member[] = array(
                                'resume' => $resume,
                                'recruit' => $recruit
                            );
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
     * 주소를 시/구 단위로 그룹핑
     * "인천광역시 남동구 고잔동" → "인천광역시 남동구"
     */
    private function _groupByArea($items)
    {
        return $this->match_model->groupByArea($items);
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

            $result = $ppurio->sendCorporateAlimtalk(
                $recruit['mm_phone'],
                array(),
                array()
            );

            // 발송 이력 기록
            if (isset($result['ok']) && $result['ok'] === true) {
                $this->db->query(
                    "INSERT INTO st_match_alimtalk_sent
                     (sender_type, receiver_mm_idx, target_key, sent_week, sent_dt, read_yn)
                     VALUES ('COMP', ?, ?, ?, NOW(), 'N')
                     ON DUPLICATE KEY UPDATE sent_dt = NOW(), read_yn = 'N'",
                    array($recruit['mm_idx'], $resume['ru_idx'], $current_week)
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

            $result = $ppurio->sendPersonalAlimtalk(
                $resume['mm_phone'],
                array(),
                array()
            );

            // 발송 이력 기록
            if (isset($result['ok']) && $result['ok'] === true) {
                $this->db->query(
                    "INSERT INTO st_match_alimtalk_sent
                     (sender_type, receiver_mm_idx, target_key, sent_week, sent_dt, read_yn)
                     VALUES ('MEM', ?, ?, ?, NOW(), 'N')
                     ON DUPLICATE KEY UPDATE sent_dt = NOW(), read_yn = 'N'",
                    array($resume['mm_idx'], $recruit['rc_idx'], $current_week)
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

    // 헬퍼: 지금이 설정된 알림 시각과 정확히 일치하는가 (분 단위)
    private function _isAlarmTimeNow($time_str) {
        if ($time_str === '' || $time_str === null) {
            return false;
        }
        return date('H:i') === $time_str;
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
                    "SELECT mm_idx FROM st_recruit WHERE rc_idx = ? AND rc_status = 'Y'",
                    array($row['rc_idx'])
                )->row_array();
                if (!$recruit_info) continue;

                $recruit_owner = $this->member_model->getMember(array("mm_idx" => $recruit_info["mm_idx"]));
                if (!$recruit_owner || empty($recruit_owner["mm_phone"])) continue;

                $company_name = !empty($recruit_owner["mm_comp_name"]) ? $recruit_owner["mm_comp_name"] : "";
                $result = $ppurio->sendUnreadNotificationAlimtalk(
                    $recruit_owner["mm_phone"],
                    array(),
                    array("name" => $company_name)
                );

                if (isset($result['ok']) && $result['ok'] === true) {
                    $this->db->query(
                        "INSERT IGNORE INTO st_request_alimtalk_sent (mq_idx, mq_type, sent_dt) VALUES (?, 'RECRUIT', NOW())",
                        array($row['mq_idx'])
                    );
                    $sent++;
                }
            } catch (Exception $e) {
                log_message('error', '[PPURIO][send_request_alimtalk][RECRUIT] mq_idx=' . $row['mq_idx'] . ' ' . $e->getMessage());
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
                $target_resume = $this->member_model->getResume(array("ru_idx" => $row['ru_idx']));
                if (!$target_resume) continue;

                $target_member = $this->member_model->getMember(array("mm_idx" => $target_resume["mm_idx"]));
                if (!$target_member || empty($target_member["mm_phone"])) continue;

                $candidate_name = !empty($target_member["mm_name"]) ? $target_member["mm_name"] : "";
                $result = $ppurio->sendPersonalUnreadNotificationAlimtalk(
                    $target_member["mm_phone"],
                    array(),
                    array("name" => $candidate_name)
                );

                if (isset($result['ok']) && $result['ok'] === true) {
                    $this->db->query(
                        "INSERT IGNORE INTO st_request_alimtalk_sent (mq_idx, mq_type, sent_dt) VALUES (?, 'RESUME', NOW())",
                        array($row['mq_idx'])
                    );
                    $sent++;
                }
            } catch (Exception $e) {
                log_message('error', '[PPURIO][send_request_alimtalk][RESUME] mq_idx=' . $row['mq_idx'] . ' ' . $e->getMessage());
            }
            usleep(100000);
        }
        return $sent;
    }
}
