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
    }

    // 지원 미확인 기업회원에게 3일 주기로 알림톡 발송
    // 추가: 오후 4시(16시)에는 오늘 오전 10시 이후 새로 생긴 대상에만 발송
    public function check_unread_alimtalk()
    {
        // 1. rc_idx > 0인 채팅방 중 발송 대상 조회
        // 시간대별로 다른 조건으로 필터링:
        // - 오전 10시: 3일 이상 발송하지 않은 대상 (기존 대상 대상)
        // - 오후 4시: 오늘 오전 10시 이후 새로 생긴 대상 (신규 지원자)
        $current_hour = (int)date('H'); // 현재 시간(24시간 형식)
        $is_afternoon = ($current_hour >= 16 && $current_hour < 17);

        // 공통 기본 조건
        $base_where = "
            WHERE r.rc_idx > 0
              AND m.mm_type = 'COMP'
              AND m.mm_status = 'Y'
              AND m.mm_agree_ad = 'Y'
              AND m.alarm_apply = 'Y'
              AND m.mm_phone != ''
        ";

        // 시간대별 추가 조건
        if ($is_afternoon) {
            // 오후 4시: 오늘 10시 이후 새로 생긴 채팅방만
            // s.as_idx IS NULL 조건은 **"아직 한 번도 알림을 보내지 않은 채팅방"**을 필터링
            $time_where = "
              AND r.reg_dt >= CONCAT(CURDATE(), ' 10:00:00') 
              AND s.as_idx IS NULL
            ";
        } else {
            // 오전 10시: 3일 주기 재발송 대상
            /**
             * (아직 미발송) OR (3일 이상 지난 것)
             *   ↓
             *   새로운 채팅방도 보내고, 옛날 채팅도 다시 한 번 보냄
             * 
             */
            $time_where = "
              AND (s.as_idx IS NULL OR s.sent_dt <= DATE_SUB(NOW(), INTERVAL 3 DAY))
            ";
        }

        $where_condition = $base_where . $time_where;

        $rooms = $this->db->query("
            SELECT r.rm_idx, r.rc_idx, r.ru_idx,
                   rc.mm_idx AS comp_mm_idx,
                   m.mm_id AS comp_mm_id, m.mm_phone, m.mm_comp_name
            FROM st_room r
            INNER JOIN st_recruit rc ON r.rc_idx = rc.rc_idx
            INNER JOIN st_member m ON rc.mm_idx = m.mm_idx
            LEFT JOIN st_alimtalk_sent s ON r.rm_idx = s.rm_idx
            {$where_condition}
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

                // 미확인 → 알림톡 발송 (템플릿 변수: var1=[*이름*], var2=[*휴대폰*], var3=[*1*])
                $result = $ppurio->sendUnreadNotificationAlimtalk(
                    $row['mm_phone'],
                    array(
                        isset($row['mm_comp_name']) ? $row['mm_comp_name'] : '',  // var1 = [*이름*]
                        $row['mm_phone'],                                          // var2 = [*휴대폰*]
                        "이력서 지원 알림"                                          // var3 = [*1*]
                    ),
                    array(
                        'name' => isset($row['mm_comp_name']) ? $row['mm_comp_name'] : '',
                        'targetPath' => '/recruit/job_post/' . $row['rc_idx']  // ← 버튼 생성
                    )
                );

                // 발송 이력 기록 (성공한 경우에만)
                if (isset($result['ok']) && $result['ok'] === true) {
                    $this->db->query(
                        "INSERT INTO st_alimtalk_sent (rm_idx, comp_mm_idx, sent_dt, sent_count)
                         VALUES (?, ?, NOW(), 1)
                         ON DUPLICATE KEY UPDATE sent_dt = NOW(), sent_count = sent_count + 1",
                        array($row['rm_idx'], $row['comp_mm_idx'])
                    );
                    $sent_count++;
                } else {
                    $error_msg = isset($result['error']) ? $result['error'] : '알림톡 발송 실패';
                    echo "알림톡 발송 실패 (rm_idx: " . $row['rm_idx'] . "): " . $error_msg . "\n";
                }

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
    private function _isMatched($resume, $recruit)
    {
        return $this->match_model->isMatched($resume, $recruit);
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
                array(
                    'name' => isset($recruit['mm_comp_name']) ? $recruit['mm_comp_name'] : '',
                    'targetPath' => '/resume/hr_post/' . $resume['ru_idx']  // ← 이력서 조회 페이지
                )
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
                array(
                    'name' => isset($resume['mm_name']) ? $resume['mm_name'] : '',
                    'targetPath' => '/recruit/job_post/' . $recruit['rc_idx']  // ← 채용공고 상세 페이지
                )
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
}
