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

    // 매주 월요일 오전 10시 - 개인/기업 조건 매칭 알림톡 발송
    // crontab: 0 10 * * 1 curl -s http://도메인/cron_proc/weekly_match_alimtalk > /dev/null 2>&1
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

        // 4. 주소를 시/구 단위로 그룹핑
        $resume_by_area = $this->_groupByArea($resumes);
        $recruit_by_area = $this->_groupByArea($recruits);

        // 5. 같은 지역 내에서 세부 조건 매칭
        $matches_for_corp = array();   // 기업에게 보낼 매칭 (이력서 정보)
        $matches_for_member = array(); // 개인에게 보낼 매칭 (공고 정보)

        foreach ($resume_by_area as $area => $area_resumes) {
            if (!isset($recruit_by_area[$area])) continue;
            $area_recruits = $recruit_by_area[$area];

            foreach ($area_resumes as $resume) {
                foreach ($area_recruits as $recruit) {
                    if ($this->_isMatched($resume, $recruit)) {
                        // 기업에게: 이 이력서를 알려줌
                        $matches_for_corp[] = array(
                            'recruit' => $recruit,
                            'resume' => $resume
                        );
                        // 개인에게: 이 공고를 알려줌
                        $matches_for_member[] = array(
                            'resume' => $resume,
                            'recruit' => $recruit
                        );
                    }
                }
            }
        }

        if (empty($matches_for_corp) && empty($matches_for_member)) {
            echo "매칭 결과 없음\n";
            return;
        }

        // 6. 중복 발송 체크 후 발송
        $sent_corp = $this->_sendCorpAlimtalk($matches_for_corp, $current_week, $ppurio);
        $sent_member = $this->_sendMemberAlimtalk($matches_for_member, $current_week, $ppurio);

        // 7. 실행 결과 출력
        $elapsed = round(microtime(true) - $start_time, 2);
        echo "매칭 알림톡 발송 완료 - 기업: {$sent_corp}건, 개인: {$sent_member}건, 소요시간: {$elapsed}초\n";
    }

    /**
     * 활성 이력서 조회 (주소 포함)
     */
    private function _getActiveResumes()
    {
        $sql = "
            SELECT r.ru_idx, r.mm_idx, r.ru_name, r.ru_work, r.ru_work_type, r.reg_dt,
                   m.mm_name, m.mm_phone, m.mm_sex, m.mm_birth, m.mm_nation,
                   a.ad_addr
            FROM st_resume r
            INNER JOIN st_member m ON r.mm_idx = m.mm_idx
            INNER JOIN st_address a ON r.mm_idx = a.mm_idx
                AND a.ad_type = 'RESUME' AND a.ad_key = r.ru_idx
            WHERE r.ru_show_yn = 'Y'
              AND r.ru_status = 'Y'
              AND m.mm_type = 'MEM'
              AND m.mm_status = 'Y'
              AND m.mm_phone != ''
            ORDER BY r.reg_dt DESC
        ";
        return $this->db->query($sql)->result_array();
    }

    /**
     * 게재 중인 채용공고 조회 (주소 포함)
     */
    private function _getActiveRecruits()
    {
        $sql = "
            SELECT rc.rc_idx, rc.mm_idx, rc.rc_name, rc.rc_work, rc.rc_work_type,
                   rc.rc_sex, rc.rc_sage, rc.rc_eage, rc.rc_nation, rc.reg_dt,
                   m.mm_comp_name, m.mm_phone,
                   a.ad_addr
            FROM st_recruit rc
            INNER JOIN st_member m ON rc.mm_idx = m.mm_idx
            INNER JOIN st_address a ON rc.mm_idx = a.mm_idx
                AND a.ad_type IN ('RECRUIT', 'RECRUIT_EXPOSE_1', 'RECRUIT_EXPOSE_2')
                AND a.ad_key = rc.rc_idx
            WHERE rc.rc_status = 'Y'
              AND rc.rc_show_yn = 'Y'
              AND rc.rc_sdate <= NOW()
              AND rc.rc_edate >= NOW()
              AND m.mm_type = 'COMP'
              AND m.mm_status = 'Y'
              AND m.mm_phone != ''
            ORDER BY rc.reg_dt DESC
        ";
        return $this->db->query($sql)->result_array();
    }

    /**
     * 주소를 시/구 단위로 그룹핑
     * "인천광역시 남동구 고잔동" → "인천광역시 남동구"
     */
    private function _groupByArea($items)
    {
        $grouped = array();
        foreach ($items as $item) {
            $addr = isset($item['ad_addr']) ? trim($item['ad_addr']) : '';
            if ($addr === '') continue;

            $parts = explode(' ', $addr);
            // 시/도 + 구/군 (처음 2단어)
            $area = (count($parts) >= 2) ? $parts[0] . ' ' . $parts[1] : $parts[0];

            if (!isset($grouped[$area])) {
                $grouped[$area] = array();
            }
            $grouped[$area][] = $item;
        }
        return $grouped;
    }

    /**
     * 이력서와 채용공고의 세부 조건 매칭 확인
     * 모든 조건이 일치해야 매칭됨
     */
    private function _isMatched($resume, $recruit)
    {
        // 1. 직종 매칭 (1개 이상 겹침)
        $ru_works = array_map('trim', explode(',', $resume['ru_work'])); // explode(',', ...): 문자열을 쉼표로 분리하여 배열로 변환
        $rc_works = array_map('trim', explode(',', $recruit['rc_work']));
        $work_intersect = array_intersect($ru_works, $rc_works); //여러 배열에서 공통으로 존재하는 값들만 추출
        if (empty($work_intersect)) return false;

        // 2. 성별 매칭
        if ($resume['mm_sex'] !== '') {
            $sex_map = array('남' => '남자', '여' => '여자');
            $member_sex = isset($sex_map[$resume['mm_sex']]) ? $sex_map[$resume['mm_sex']] : '';
            if ($member_sex !== '') {
                $rc_sex_arr = array_map('trim', explode(',', $recruit['rc_sex']));
                if (!in_array($member_sex, $rc_sex_arr)) return false;
            }
        }

        // 3. 나이 매칭 (mm_birth가 0 이상일 때만 검사)
        if (((int)$resume['mm_birth']) > 0 && ((int)$recruit['rc_sage']) > 0 && ((int)$recruit['rc_eage']) > 0) {
            $korean_age = (int)date('Y') - (int)$resume['mm_birth'] + 1;
            $normalized = round($korean_age / 10) * 10;
            if ($normalized < 20) $normalized = 20;
            if ($normalized > 70) $normalized = 70;
            if ($normalized < (int)$recruit['rc_sage'] || $normalized > (int)$recruit['rc_eage']) return false;
        }

        // 4. 국적 매칭
        if ($resume['mm_nation'] != '') {
            $rc_nations = array_map('trim', explode(',', $recruit['rc_nation']));
            if (in_array($resume['mm_nation'], array('내국인', '외국인'))) {
                if (!in_array($resume['mm_nation'], $rc_nations)) return false;
            }
        }

        // 5. 근무형태 매칭 (1개 이상 겹침)
        $ru_types = array_map('trim', explode(',', $resume['ru_work_type']));
        $rc_types = array_map('trim', explode(',', $recruit['rc_work_type']));
        $type_intersect = array_intersect($ru_types, $rc_types);
        if (empty($type_intersect)) return false;

        return true;
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

            // 템플릿 변수 구성 (기업회원 템플릿: var3=이력서제목, var4=URL)
            $corp_vars = array(
                '', // var1
                '', // var2
                isset($resume['ru_name']) ? $resume['ru_name'] : '', // var3
                site_url('resume/hr_post/' . $resume['ru_idx']) // var4
            );

            $result = $ppurio->sendCorporateAlimtalk(
                $recruit['mm_phone'],
                $corp_vars,
                array('name' => isset($recruit['mm_comp_name']) ? $recruit['mm_comp_name'] : '')
            );

            // 발송 이력 기록
            if (isset($result['ok']) && $result['ok'] === true) {
                $this->db->query(
                    "INSERT INTO st_match_alimtalk_sent
                     (sender_type, receiver_mm_idx, target_key, sent_week, sent_dt)
                     VALUES ('COMP', ?, ?, ?, NOW())
                     ON DUPLICATE KEY UPDATE sent_dt = NOW()",
                    array($recruit['mm_idx'], $resume['ru_idx'], $current_week)
                );
                $sent++;
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

            // 템플릿 변수 구성 (개인회원 템플릿: var1=공고명, var2=URL)
            $personal_vars = array(
                isset($recruit['rc_name']) ? $recruit['rc_name'] : '', // var1
                site_url('recruit/job_post/' . $recruit['rc_idx']) // var2
            );

            $result = $ppurio->sendPersonalAlimtalk(
                $resume['mm_phone'],
                $personal_vars,
                array('name' => isset($resume['mm_name']) ? $resume['mm_name'] : '')
            );

            // 발송 이력 기록
            if (isset($result['ok']) && $result['ok'] === true) {
                $this->db->query(
                    "INSERT INTO st_match_alimtalk_sent
                     (sender_type, receiver_mm_idx, target_key, sent_week, sent_dt)
                     VALUES ('MEM', ?, ?, ?, NOW())
                     ON DUPLICATE KEY UPDATE sent_dt = NOW()",
                    array($resume['mm_idx'], $recruit['rc_idx'], $current_week)
                );
                $sent++;
            }

            // API 과부하 방지
            usleep(100000); // 0.1초
        }

        return $sent;
    }
}
