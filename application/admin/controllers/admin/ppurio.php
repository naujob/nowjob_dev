<?php
class Ppurio extends MY_Controller{
    function __construct() {
        parent::__construct();
        $this->load->model('sitemodel','site_model');
        $this->load->model('membermodel','member_model');
        $this->load->library('common');
        $this->load->model('matchmodel', 'match_model');
        $this->load->model('ppuriokatalksettingmodel','ppurio_setting_model');
    }

    // 추천인재 구매상품 개인 리스트
    function buy_recom(){
        // 파라미터 설정
        $page = input_get("page", 1);
        $sdate = input_get("sdate", "");
        $edate = input_get("edate", "");
        $search_value = input_get("search_value", "");

        // 처음 접근 시 어제 날짜로 자동 필터링 (이전 데이터는 검색했을 때만 표시)
        if ($sdate == "" && $edate == "" && $search_value == "") {
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $sdate = $yesterday;
            $edate = $yesterday;
        }

        // 데이터 설정
        $params = [
            "pagesize" => 20,
            "page" => $page,
            "sdate" => $sdate,
            "edate" => $edate,
            "search_value" => $search_value,
        ];
        $result = $this->site_model->getPromotionSendLogList($params);

        // 리턴 설정
        $data = [
            "search" => [
                "sdate" => $sdate,
                "edate" => $edate,
                "search_value" => $search_value,
            ],
            "start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
            "result_list" => $result["list"],
            "result_count" => $result["count"],
            "pagenation" => $result["pagenation"],
        ];

        $this->layout->admin('admin/ppurio/buy_recom', $data);
    }

    


    // 추천인재 구매상품 개인 엑셀다운로드
    function buy_recom_excel(){
        $this->load->library('excel');

        // 파라미터 설정 (전체 조회)
        $sdate = input_get("sdate", "");
        $edate = input_get("edate", "");
        $search_value = input_get("search_value", "");

        $params = [
            "pagesize" => 100000,
            "page" => 1,
            "sdate" => $sdate,
            "edate" => $edate,
            "search_value" => $search_value,
        ];
        $result = $this->site_model->getPromotionSendLogList($params);

        // 엑셀 파일 설정
        $file_name = $this->excel->getFilename("추천인재_구매상품_개인");

        // ===== Sheet1: 요약 정보 =====
        $sheet1 = $this->excel->getActiveSheet();
        $sheet1->setTitle("요약");
 
        $num = 1;  // 시작 행 번호
        $columns_sheet1 = ['A', 'B', 'C', 'D', 'E']; // 컬럼 설정

        // 셀크기 조절
        $column_widths_sheet1 = [
            'A' => 10,  // 번호
            'B' => 40,  // 인재이름
            'C' => 15,  // 발송일
            'D' => 15,  // 발송대상 기업수
            'E' => 20,  // 프로모션IDX
        ];
        // 컬럼 너비 설정
        foreach ($column_widths_sheet1 as $column => $width) {
            $sheet1->getColumnDimension($column)->setWidth($width);
        }

        // 헤더 설정
        $sheet1->setCellValue('A' . $num, "번호");
        $sheet1->setCellValue('B' . $num, "인재이름");
        $sheet1->setCellValue('C' . $num, "발송일");
        $sheet1->setCellValue('D' . $num, "발송대상 기업수");
        $sheet1->setCellValue('E' . $num, "프로모션IDX");

        // 헤더 스타일
        $sheet1->getStyle($columns_sheet1[0] . ($num) . ':' . $columns_sheet1[count($columns_sheet1)-1] . ($num))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFEEEEEE');
        $sheet1->getStyle($columns_sheet1[0] . ($num) . ':' . $columns_sheet1[count($columns_sheet1)-1] . ($num))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet1->getStyle($columns_sheet1[0] . ($num) . ':' . $columns_sheet1[count($columns_sheet1)-1] . ($num))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet1->getStyle($columns_sheet1[0] . ($num) . ':' . $columns_sheet1[count($columns_sheet1)-1] . ($num))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $num++;

        // 데이터 행
        if (count($result["list"]) > 0) {
            foreach ($result["list"] as $row_idx => $row) {
                $tableData = array_combine(
                    array_values($columns_sheet1),
                    [
                        ($num - 1),
                        isset($row['ru_name']) ? $row['ru_name'] : '',
                        isset($row['send_date']) ? $row['send_date'] : '',
                        isset($row['comp_count']) ? $row['comp_count'] : 0,
                        isset($row['pm_idx']) ? $row['pm_idx'] : '',
                    ]
                );
                foreach ($tableData as $k => $v) {
                    $sheet1->setCellValue($k . ($num), $v);
                    $sheet1->getStyle('A'.$num.':' . $columns_sheet1[count($columns_sheet1)-1] .$num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $sheet1->getStyle('A'.$num.':' . $columns_sheet1[count($columns_sheet1)-1] .$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $sheet1->getStyle('A'.$num.':' . $columns_sheet1[count($columns_sheet1)-1] .$num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                }
                $num++;
            }
        }

        // ===== Sheet2: 기업별 알림동의 상태 =====
        $sheet2 = $this->excel->createSheet();
        $sheet2->setTitle("기업별알림동의");

        $num2 = 1;
        $columns_sheet2 = ['A', 'B', 'C', 'D', 'E'];

        // 셀크기 조절
        $column_widths_sheet2 = [
            'A' => 10,  // 번호
            'B' => 40,  // 인재이름
            'C' => 15,  // 발송일
            'D' => 40,  // 기업명
            'E' => 15,  // 알림동의
        ];

        foreach ($column_widths_sheet2 as $column => $width) {
            $sheet2->getColumnDimension($column)->setWidth($width);
        }

        // 헤더 설정
        $sheet2->setCellValue('A' . $num2, "번호");
        $sheet2->setCellValue('B' . $num2, "인재이름");
        $sheet2->setCellValue('C' . $num2, "발송일");
        $sheet2->setCellValue('D' . $num2, "기업명");
        $sheet2->setCellValue('E' . $num2, "알림동의");

        // 헤더 스타일
        $sheet2->getStyle($columns_sheet2[0] . ($num2) . ':' . $columns_sheet2[count($columns_sheet2)-1] . ($num2))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFEEEEEE');
        $sheet2->getStyle($columns_sheet2[0] . ($num2) . ':' . $columns_sheet2[count($columns_sheet2)-1] . ($num2))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet2->getStyle($columns_sheet2[0] . ($num2) . ':' . $columns_sheet2[count($columns_sheet2)-1] . ($num2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet2->getStyle($columns_sheet2[0] . ($num2) . ':' . $columns_sheet2[count($columns_sheet2)-1] . ($num2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $num2++;

        // 기업별 알림동의 상태 데이터
        if (count($result["list"]) > 0) {
            $detail_idx = 1;
            foreach ($result["list"] as $row) {
                // 기업명 JSON 디코딩
                $comp_names_arr = [];
                if (isset($row['comp_names']) && !empty($row['comp_names'])) {
                    $comp_names_arr = json_decode($row['comp_names'], true);
                    if (!is_array($comp_names_arr)) {
                        $comp_names_arr = [];
                    }
                }
                $alarm_adv_count = isset($row['alarm_adv_count']) ? $row['alarm_adv_count'] : 0;

                foreach ($comp_names_arr as $comp_idx => $comp_name) {
                    $alarm_status = ($comp_idx < $alarm_adv_count) ? '동의' : '미동의';

                    $tableData2 = array_combine(
                        array_values($columns_sheet2),
                        [
                            $detail_idx,
                            isset($row['ru_name']) ? $row['ru_name'] : '',
                            isset($row['send_date']) ? $row['send_date'] : '',
                            $comp_name,
                            $alarm_status,
                        ]
                    );
                    foreach ($tableData2 as $k => $v) {
                        $sheet2->setCellValue($k . ($num2), $v);
                        $sheet2->getStyle('A'.$num2.':' . $columns_sheet2[count($columns_sheet2)-1] .$num2)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $sheet2->getStyle('A'.$num2.':' . $columns_sheet2[count($columns_sheet2)-1] .$num2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $sheet2->getStyle('A'.$num2.':' . $columns_sheet2[count($columns_sheet2)-1] .$num2)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    }
                    $num2++;
                    $detail_idx++;
                }
            }
        }

        // 엑셀 저장
        $this->excel->setFooter($file_name);
    }

    // 지원자 알림 SMS 발송 - 목록 조회
    function sendPpurioSmsUnread() {
        // 1. rc_idx > 0인 채팅방 중 발송 대상 조회 (현재 시간대 무관 - 모든 대상)
        $base_where = "
            WHERE r.rc_idx > 0
              AND m.mm_type = 'COMP'
              AND m.mm_status = 'Y'
              AND m.mm_agree_ad = 'Y'
              AND m.alarm_apply = 'Y'
              AND m.mm_phone != ''
        ";

        // 발송되지 않은 대상 또는 3일 이상 지난 대상
        $time_where = "
          AND (s.as_idx IS NULL OR s.sent_dt <= DATE_SUB(NOW(), INTERVAL 3 DAY))
        ";

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

        // 2. MongoDB 접근하여 미확인 메시지 여부 확인
        $send_targets = array();
        if (!empty($rooms)) {
            $uri = get_mongo_uri();
            $dbName = get_mongo_db_name();

            try {
                @$mongo = new Mongo($uri);
                $db = $mongo->$dbName;
                $collection = $db->room;

                foreach ($rooms as $row) {
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

                    if ($has_unread_recruit_msg) {
                        $send_targets[] = $row;
                    }
                }
            } catch (Exception $e) {
                echo "MongoDB 연결 오류: " . $e->getMessage() . "\n";
                return;
            }
        }

        // 3. 뷰에 전달
        $data = array(
            'result_list' => $send_targets,
            'result_count' => count($send_targets),
        );

        $this->layout->admin('admin/ppurio/send_sms_unread', $data);
    }

    // 지원자 알림 SMS 발송 - 발송 처리
    function sendPpurioSmsUnread_proc() {
        $sent_count = 0;

        // 1. rc_idx > 0인 채팅방 중 발송 대상 조회
        $base_where = "
            WHERE r.rc_idx > 0
              AND m.mm_type = 'COMP'
              AND m.mm_status = 'Y'
              AND m.mm_agree_ad = 'Y'
              AND m.alarm_apply = 'Y'
              AND m.mm_phone != ''
        ";
        // 발송되지 않은 대상 또는 3일 이상 지난 대상
        $time_where = "
          AND (s.as_idx IS NULL OR s.sent_dt <= DATE_SUB(NOW(), INTERVAL 3 DAY))
        ";

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
            json_return("99", "발송 대상이 없습니다.");
            return;
        }

        // 2. MongoDB 접근
        $uri = get_mongo_uri();
        $dbName = get_mongo_db_name();

        try {
            @$mongo = new Mongo($uri);
            $db = $mongo->$dbName;
            $collection = $db->room;
        } catch (Exception $e) {
            json_return("99", "MongoDB 연결 오류: " . $e->getMessage());
            return;
        }

        // 3. 각 채팅방에서 chat_type="recruit"인 미확인 메시지 확인 후 SMS 발송
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

                // 미확인 → SMS 발송
                $sms_msg = "[나우잡] 확인하지 않은 지원자가 있습니다. 확인하기: " . site_url('resume/hr_post/' . $row['ru_idx']);
                $this->common->sendNotificationSms($row['mm_phone'], $sms_msg);

                // 발송 이력 기록
                $this->db->query(
                    "INSERT INTO st_alimtalk_sent (rm_idx, comp_mm_idx, sent_dt, sent_count)
                     VALUES (?, ?, NOW(), 1)
                     ON DUPLICATE KEY UPDATE sent_dt = NOW(), sent_count = sent_count + 1",
                    array($row['rm_idx'], $row['comp_mm_idx'])
                );

                $sent_count++;

            } catch (Exception $e) {
                continue;
            }
        }

        json_return("00", "발송 완료 - " . $sent_count . "건");
    }

    // 헬퍼: ^ 구분자로 조건 배열 파싱
    private function _parseMatchConditions($cond_str) {
        if ($cond_str === '' || $cond_str === null) return array();
        return explode('^', $cond_str); // 문자열 -> 배열 리턴
    }

    // 헬퍼: 기업 매칭 계산 (area 조건 유무로 분기)
    private function _calcCorpMatches($resumes, $recruits, $cond_arr) {
        if (in_array('area', $cond_arr)) {
            // 1. 이력서를 지역별로 그룹핑
            $resume_by_area  = $this->match_model->groupByArea($resumes);
            // 2. 공고를 지역별로 그룹핑
            $recruit_by_area = $this->match_model->groupByArea($recruits);
            $matches = array();
            foreach ($resume_by_area as $area => $area_resumes) {
                  // 3. 그 지역에 공고가 없으면 스킵
                if (!isset($recruit_by_area[$area])) continue;
                  // 4. 같은 지역의 이력서 × 공고만 매칭
                foreach ($area_resumes as $resume) {
                    foreach ($recruit_by_area[$area] as $recruit) {
                        if ($this->match_model->isMatched($resume, $recruit, $cond_arr)) {
                            $matches[] = array('recruit' => $recruit, 'resume' => $resume);
                        }
                    }
                }
            }
            return $matches;
        } else {
            $pairs = $this->match_model->matchAllPairs($resumes, $recruits, $cond_arr);
            $result = array();
            foreach ($pairs as $p) {
                $result[] = array('recruit' => $p['recruit'], 'resume' => $p['resume']);
            }
            return $result;
        }
    }

    // 매칭 이력서 SMS 발송 (기업 대상) - 목록 조회
    function sendCorPpurioSms() {
        $current_week = date('Y-W');

        // 1. 활성 이력서 조회
        $resumes = $this->match_model->getActiveResumes();
        // 2. 게재 중인 채용공고 조회
        $recruits = $this->match_model->getActiveRecruits();

        if (empty($resumes) || empty($recruits)) {
            $data = array(
                'result_list' => array(),
                'result_count' => 0,
                'search' => array('search_type' => '', 'search_value' => '', 'category' => array()),
                'select_category' => array(),
            );
            $this->layout->admin('admin/ppurio/send_sms_corp', $data);
            return;
        }

        // 3. usual 설정에서 매칭 조건 읽기 및 매칭 실행
        $setting = $this->ppurio_setting_model->getSetting('usual');
        $cond_str = isset($setting['comp_match_cond']) ? $setting['comp_match_cond'] : '';
        $comp_cond_arr = $this->_parseMatchConditions($cond_str);

        // 4. 매칭 결과 수집
        $matches_for_corp = $this->_calcCorpMatches($resumes, $recruits, $comp_cond_arr);

        // 5. 기업별 최신 1건만 추출
        $corp_latest = array();
        foreach ($matches_for_corp as $match) {
            $corp_idx = $match['recruit']['mm_idx'];
            if (!isset($corp_latest[$corp_idx]) ||
                strtotime($match['resume']['reg_dt']) > strtotime($corp_latest[$corp_idx]['resume']['reg_dt'])) {
                $corp_latest[$corp_idx] = $match;
            }
        }
         // 주 단위 재발송 허용: 다음 주(새로운 주)가 되면 다시 발송 가능하므로
        // 6. 중복 체크: 이번 주에 이미 발송한 대상 제외
        $send_targets = array();
        foreach ($corp_latest as $match) {
            $result = $this->db->query(
                "SELECT ms_idx FROM st_match_alimtalk_sent
                 WHERE sender_type = 'COMP'
                   AND receiver_mm_idx = ?
                   AND target_key = ?
                   AND sent_week = ?",
                array($match['recruit']['mm_idx'], $match['resume']['ru_idx'], $current_week)
            );
            $dup_check = $result->row_array();

            if (empty($dup_check)) {
                $send_targets[] = $match;
            }
        }

        // 7. 검색 파라미터 수신 및 필터링
        $search_type  = input_get("search_type", "");
        $search_value = input_get("search_value", "");
        $category     = $this->input->get("category");
        if (!is_array($category)) $category = array();

        if ($search_value != "") {
            $send_targets = array_filter($send_targets, function($match) use ($search_type, $search_value) {
                $r = $match['recruit'];
                $matches_any = false;
                if ($search_type === "" || $search_type === "mm_comp_name") {
                    if (strpos($r['mm_comp_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "rc_name") {
                    if (strpos($r['rc_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "ad_addr") {
                    if (strpos($r['ad_addr'], $search_value) !== false) $matches_any = true;
                }
                return $matches_any;
            });
        }

        if (!empty($category)) {
            $send_targets = array_filter($send_targets, function($match) use ($category) {
                foreach ($category as $cat) {
                    if ($cat != "" && strpos($match['recruit']['rc_work'], $cat) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 지역 필터링
        $ad_addr = input_get("ad_addr", "");
        if ($ad_addr != "") {
            $send_targets = array_filter($send_targets, function($match) use ($ad_addr) {
                return strpos($match['recruit']['ad_addr'], $ad_addr) !== false;
            });
        }

        // 노출여부 필터링
        $rc_show_yn = input_get("rc_show_yn", "");
        if ($rc_show_yn != "") {
            $send_targets = array_filter($send_targets, function($match) use ($rc_show_yn) {
                return $match['recruit']['rc_show_yn'] === $rc_show_yn;
            });
        }

        $send_targets = array_values($send_targets);

        // 8. 카테고리 목록 조회
        $category_list = $this->site_model->getCodeList(array("cd_grp" => "희망업무"));
        $select_category = getKeyValue($category_list, "cd_val", "cd_val");

        // 9. 뷰에 전달
        $data = array(
            'result_list' => $send_targets,
            'result_count' => count($send_targets),
            'search' => array(
                'search_type'  => $search_type,
                'search_value' => $search_value,
                'category'     => $category,
                'ad_addr'      => $ad_addr,
                'rc_show_yn'   => $rc_show_yn,
            ),
            'select_category' => $select_category,
        );

        $this->layout->admin('admin/ppurio/send_sms_corp', $data);
    }

    // 매칭 이력서 SMS 발송 (기업 대상) - 발송 처리
    function sendCorPpurioSms_proc() {
        $sent_count = 0;
        $current_week = date('Y-W');

        // 1. 활성 이력서 조회
        $resumes = $this->match_model->getActiveResumes();
        // 2. 게재 중인 채용공고 조회
        $recruits = $this->match_model->getActiveRecruits();

        if (empty($resumes) || empty($recruits)) {
            json_return("99", "발송 대상이 없습니다.");
            return;
        }

        // 3. usual 설정에서 매칭 조건 읽기 및 매칭 실행
        $setting = $this->ppurio_setting_model->getSetting('usual');
        $cond_str = isset($setting['comp_match_cond']) ? $setting['comp_match_cond'] : '';
        $comp_cond_arr = $this->_parseMatchConditions($cond_str);

        // 4. 매칭 결과 수집
        $matches_for_corp = $this->_calcCorpMatches($resumes, $recruits, $comp_cond_arr);

        if (empty($matches_for_corp)) {
            json_return("99", "발송 대상이 없습니다.");
            return;
        }

        // 5. 기업별 최신 1건만 추출
        $corp_latest = array();
        foreach ($matches_for_corp as $match) {
            $corp_idx = $match['recruit']['mm_idx'];
            if (!isset($corp_latest[$corp_idx]) ||
                strtotime($match['resume']['reg_dt']) > strtotime($corp_latest[$corp_idx]['resume']['reg_dt'])) {
                $corp_latest[$corp_idx] = $match;
            }
        }

        // 6. SMS 발송
        foreach ($corp_latest as $match) {
            try {
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

                // SMS 발송
                $sms_msg = "[나우잡] 조건에 맞는 인재가 등록되었습니다. 확인하기: " . site_url('resume/hr_post/' . $resume['ru_idx']);
                $this->common->sendNotificationSms($recruit['mm_phone'], $sms_msg);

                // 발송 이력 기록
                $this->db->query(
                    "INSERT INTO st_match_alimtalk_sent
                     (sender_type, receiver_mm_idx, target_key, sent_week, sent_dt)
                     VALUES ('COMP', ?, ?, ?, NOW())
                     ON DUPLICATE KEY UPDATE sent_dt = NOW()",
                    array($recruit['mm_idx'], $resume['ru_idx'], $current_week)
                );
                $sent_count++;

            } catch (Exception $e) {
                continue;
            }
        }

        json_return("00", "발송 완료 - " . $sent_count . "건");
    }

    // 매칭 이력서 SMS 발송 (기업 대상) - 이력서 목록 조회 (Page 2)
    function sendCorPpurioSmsResume() {
        $current_week = date('Y-W');

        // 선택된 기업 mm_idx 받기
        $selected_mm_idx_str = $this->input->get("mm_idx");
        $selected_mm_idx = array();
        if (!empty($selected_mm_idx_str)) {
            $selected_mm_idx = array_map('trim', explode(',', $selected_mm_idx_str));
        }

        // 1. 활성 이력서 조회
        $resumes = $this->match_model->getActiveResumes();
        // 2. 게재 중인 채용공고 조회
        $recruits = $this->match_model->getActiveRecruits();

        if (empty($resumes) || empty($recruits)) {
            $data = array(
                'result_list' => array(),
                'result_count' => 0,
            );
            $this->layout->admin('admin/ppurio/send_sms_corp_resume', $data);
            return;
        }

        // 3. usual 설정에서 매칭 조건 읽기 및 매칭 실행
        $setting = $this->ppurio_setting_model->getSetting('usual');
        $cond_str = isset($setting['comp_match_cond']) ? $setting['comp_match_cond'] : '';
        $comp_cond_arr = $this->_parseMatchConditions($cond_str);

        // 4. 매칭 결과 수집
        $matches_for_corp = $this->_calcCorpMatches($resumes, $recruits, $comp_cond_arr);

        // 5. 기업별 최신 1건만 추출 (선택된 기업만 필터링)
        $corp_latest = array();
        foreach ($matches_for_corp as $match) {
            $corp_idx = $match['recruit']['mm_idx'];

            // 선택된 기업이 지정된 경우, 해당 기업만 처리
            if (!empty($selected_mm_idx) && !in_array($corp_idx, $selected_mm_idx)) {
                continue;
            }

            if (!isset($corp_latest[$corp_idx]) ||
                strtotime($match['resume']['reg_dt']) > strtotime($corp_latest[$corp_idx]['resume']['reg_dt'])) {
                $corp_latest[$corp_idx] = $match;
            }
        }

        // 6. 중복 체크: 이번 주에 이미 발송한 대상 제외
        $send_targets = array();
        foreach ($corp_latest as $match) {
            $result = $this->db->query(
                "SELECT ms_idx FROM st_match_alimtalk_sent
                 WHERE sender_type = 'COMP'
                   AND receiver_mm_idx = ?
                   AND target_key = ?
                   AND sent_week = ?",
                array($match['recruit']['mm_idx'], $match['resume']['ru_idx'], $current_week)
            );
            $dup_check = $result->row_array();

            if (empty($dup_check)) {
                $send_targets[] = $match;
            }
        }

        // 7. 뷰에 전달
        $data = array(
            'result_list' => $send_targets,
            'result_count' => count($send_targets),
        );

        $this->layout->admin('admin/ppurio/send_sms_corp_resume', $data);
    }

    // 매칭 이력서 SMS 발송 (기업 대상) - 이력서 발송 처리 (Page 2 AJAX)
    function sendCorPpurioSmsResume_proc() {
        $sent_count = 0;
        $current_week = date('Y-W');

        // 선택된 이력서 ru_idx 배열 수신
        $selected_ru_idx = $this->input->post("selected_ru_idx");
        if (!is_array($selected_ru_idx)) {
            $selected_ru_idx = array();
        }

        if (empty($selected_ru_idx)) {
            json_return("99", "이력서를 선택해주세요.");
            return;
        }

        // 1. 활성 이력서 조회
        $resumes = $this->match_model->getActiveResumes();
        // 2. 게재 중인 채용공고 조회
        $recruits = $this->match_model->getActiveRecruits();

        if (empty($resumes) || empty($recruits)) {
            json_return("99", "발송 대상이 없습니다.");
            return;
        }

        // 3. usual 설정에서 매칭 조건 읽기 및 매칭 실행
        $setting = $this->ppurio_setting_model->getSetting('usual');
        $cond_str = isset($setting['comp_match_cond']) ? $setting['comp_match_cond'] : '';
        $comp_cond_arr = $this->_parseMatchConditions($cond_str);

        // 4. 매칭 결과 수집
        $matches_for_corp = $this->_calcCorpMatches($resumes, $recruits, $comp_cond_arr);

        if (empty($matches_for_corp)) {
            json_return("99", "발송 대상이 없습니다.");
            return;
        }

        // 5. 기업별 최신 1건만 추출
        $corp_latest = array();
        foreach ($matches_for_corp as $match) {
            $corp_idx = $match['recruit']['mm_idx'];
            if (!isset($corp_latest[$corp_idx]) ||
                strtotime($match['resume']['reg_dt']) > strtotime($corp_latest[$corp_idx]['resume']['reg_dt'])) {
                $corp_latest[$corp_idx] = $match;
            }
        }

        // 6. SMS 발송 (선택된 이력서만)
        foreach ($corp_latest as $match) {
            try {
                $recruit = $match['recruit'];
                $resume = $match['resume'];

                // 선택된 이력서인지 확인
                if (!in_array($resume['ru_idx'], $selected_ru_idx)) {
                    continue;
                }

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

                // SMS 발송
                $sms_msg = "[나우잡] 조건에 맞는 인재가 등록되었습니다. 확인하기: " . site_url('resume/hr_post/' . $resume['ru_idx']);
                $this->common->sendNotificationSms($recruit['mm_phone'], $sms_msg);

                // 발송 이력 기록
                $this->db->query(
                    "INSERT INTO st_match_alimtalk_sent
                     (sender_type, receiver_mm_idx, target_key, sent_week, sent_dt)
                     VALUES ('COMP', ?, ?, ?, NOW())
                     ON DUPLICATE KEY UPDATE sent_dt = NOW()",
                    array($recruit['mm_idx'], $resume['ru_idx'], $current_week)
                );
                $sent_count++;

            } catch (Exception $e) {
                continue;
            }
        }

        json_return("00", "발송 완료 - " . $sent_count . "건");
    }

    // 매칭 채용공고 SMS 발송 (개인 대상) - 채용공고 목록 조회 (Page 2)
    function sendMemPpurioSmsRecruit() {
        $current_week = date('Y-W');

        // 선택된 개인 mm_idx 받기
        $selected_mm_idx_str = $this->input->get("mm_idx");
        $selected_mm_idx = array();
        if (!empty($selected_mm_idx_str)) {
            $selected_mm_idx = array_map('trim', explode(',', $selected_mm_idx_str));
        }

        // 1. 활성 이력서 조회
        $resumes = $this->match_model->getActiveResumes();
        // 2. 게재 중인 채용공고 조회
        $recruits = $this->match_model->getActiveRecruits();

        if (empty($resumes) || empty($recruits)) {
            $data = array(
                'result_list' => array(),
                'result_count' => 0,
            );
            $this->layout->admin('admin/ppurio/send_sms_member_recruit', $data);
            return;
        }

        // 3. usual 설정에서 매칭 조건 읽기 및 매칭 실행
        $setting = $this->ppurio_setting_model->getSetting('usual');
        $cond_str = isset($setting['mem_match_cond']) ? $setting['mem_match_cond'] : '';
        $mem_cond_arr = $this->_parseMatchConditions($cond_str);

        // 4. 매칭 결과 수집 (개인 대상은 resume/recruit 순서)
        if (in_array('area', $mem_cond_arr)) {
            $resume_by_area  = $this->match_model->groupByArea($resumes);
            $recruit_by_area = $this->match_model->groupByArea($recruits);
            $matches_for_member = array();
            foreach ($resume_by_area as $area => $area_resumes) {
                if (!isset($recruit_by_area[$area])) continue;
                foreach ($area_resumes as $resume) {
                    foreach ($recruit_by_area[$area] as $recruit) {
                        if ($this->match_model->isMatched($resume, $recruit, $mem_cond_arr)) {
                            $matches_for_member[] = array('resume' => $resume, 'recruit' => $recruit);
                        }
                    }
                }
            }
        } else {
            $pairs = $this->match_model->matchAllPairs($resumes, $recruits, $mem_cond_arr);
            $matches_for_member = array();
            foreach ($pairs as $p) {
                $matches_for_member[] = array('resume' => $p['resume'], 'recruit' => $p['recruit']);
            }
        }

        // 5. 개인별 최신 1건만 추출 (선택된 개인만 필터링)
        $member_latest = array();
        foreach ($matches_for_member as $match) {
            $mem_idx = $match['resume']['mm_idx'];

            // 선택된 개인이 지정된 경우, 해당 개인만 처리
            if (!empty($selected_mm_idx) && !in_array($mem_idx, $selected_mm_idx)) {
                continue;
            }

            if (!isset($member_latest[$mem_idx]) ||
                strtotime($match['recruit']['reg_dt']) > strtotime($member_latest[$mem_idx]['recruit']['reg_dt'])) {
                $member_latest[$mem_idx] = $match;
            }
        }

        // 6. 중복 체크: 이번 주에 이미 발송한 대상 제외
        $send_targets = array();
        foreach ($member_latest as $match) {
            $result = $this->db->query(
                "SELECT ms_idx FROM st_match_alimtalk_sent
                 WHERE sender_type = 'MEM'
                   AND receiver_mm_idx = ?
                   AND target_key = ?
                   AND sent_week = ?",
                array($match['resume']['mm_idx'], $match['recruit']['rc_idx'], $current_week)
            );
            $dup_check = $result->row_array();

            if (empty($dup_check)) {
                $send_targets[] = $match;
            }
        }

        // 7. 뷰에 전달
        $data = array(
            'result_list' => $send_targets,
            'result_count' => count($send_targets),
        );

        $this->layout->admin('admin/ppurio/send_sms_member_recruit', $data);
    }

    // 매칭 채용공고 SMS 발송 (개인 대상) - 채용공고 발송 처리 (Page 2 AJAX)
    function sendMemPpurioSmsRecruit_proc() {
        $sent_count = 0;
        $current_week = date('Y-W');

        // 선택된 채용공고 rc_idx 배열 수신
        $selected_rc_idx = $this->input->post("selected_rc_idx");
        if (!is_array($selected_rc_idx)) {
            $selected_rc_idx = array();
        }

        if (empty($selected_rc_idx)) {
            json_return("99", "채용공고를 선택해주세요.");
            return;
        }

        // 1. 활성 이력서 조회
        $resumes = $this->match_model->getActiveResumes();
        // 2. 게재 중인 채용공고 조회
        $recruits = $this->match_model->getActiveRecruits();

        if (empty($resumes) || empty($recruits)) {
            json_return("99", "발송 대상이 없습니다.");
            return;
        }

        // 3. usual 설정에서 매칭 조건 읽기 및 매칭 실행
        $setting = $this->ppurio_setting_model->getSetting('usual');
        $cond_str = isset($setting['mem_match_cond']) ? $setting['mem_match_cond'] : '';
        $mem_cond_arr = $this->_parseMatchConditions($cond_str);

        // 4. 매칭 결과 수집 (개인 대상은 resume/recruit 순서)
        if (in_array('area', $mem_cond_arr)) {
            $resume_by_area  = $this->match_model->groupByArea($resumes);
            $recruit_by_area = $this->match_model->groupByArea($recruits);
            $matches_for_member = array();
            foreach ($resume_by_area as $area => $area_resumes) {
                if (!isset($recruit_by_area[$area])) continue;
                foreach ($area_resumes as $resume) {
                    foreach ($recruit_by_area[$area] as $recruit) {
                        if ($this->match_model->isMatched($resume, $recruit, $mem_cond_arr)) {
                            $matches_for_member[] = array('resume' => $resume, 'recruit' => $recruit);
                        }
                    }
                }
            }
        } else {
            $pairs = $this->match_model->matchAllPairs($resumes, $recruits, $mem_cond_arr);
            $matches_for_member = array();
            foreach ($pairs as $p) {
                $matches_for_member[] = array('resume' => $p['resume'], 'recruit' => $p['recruit']);
            }
        }

        if (empty($matches_for_member)) {
            json_return("99", "발송 대상이 없습니다.");
            return;
        }

        // 5. 개인별 최신 1건만 추출
        $member_latest = array();
        foreach ($matches_for_member as $match) {
            $mem_idx = $match['resume']['mm_idx'];
            if (!isset($member_latest[$mem_idx]) ||
                strtotime($match['recruit']['reg_dt']) > strtotime($member_latest[$mem_idx]['recruit']['reg_dt'])) {
                $member_latest[$mem_idx] = $match;
            }
        }

        // 6. SMS 발송 (선택된 채용공고만)
        foreach ($member_latest as $match) {
            try {
                $resume = $match['resume'];
                $recruit = $match['recruit'];

                // 선택된 채용공고인지 확인
                if (!in_array($recruit['rc_idx'], $selected_rc_idx)) {
                    continue;
                }

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

                // SMS 발송
                $sms_msg = "[나우잡] 조건에 맞는 채용공고가 있습니다. 확인하기: " . site_url('recruit/job_post/' . $recruit['rc_idx']);
                $this->common->sendNotificationSms($resume['mm_phone'], $sms_msg);

                // 발송 이력 기록
                $this->db->query(
                    "INSERT INTO st_match_alimtalk_sent
                     (sender_type, receiver_mm_idx, target_key, sent_week, sent_dt)
                     VALUES ('MEM', ?, ?, ?, NOW())
                     ON DUPLICATE KEY UPDATE sent_dt = NOW()",
                    array($resume['mm_idx'], $recruit['rc_idx'], $current_week)
                );
                $sent_count++;

            } catch (Exception $e) {
                continue;
            }
        }

        json_return("00", "발송 완료 - " . $sent_count . "건");
    }

    // 매칭 채용공고 SMS 발송 (개인 대상) - 목록 조회
    function sendMemPpurioSms() {
        $current_week = date('Y-W');

        // 1. 활성 이력서 조회
        $resumes = $this->match_model->getActiveResumes();
        // 2. 게재 중인 채용공고 조회
        $recruits = $this->match_model->getActiveRecruits();

        if (empty($resumes) || empty($recruits)) {
            $data = array(
                'result_list' => array(),
                'result_count' => 0,
                'search' => array('search_type' => '', 'search_value' => '', 'category' => array()),
                'select_category' => array(),
            );
            $this->layout->admin('admin/ppurio/send_sms_member', $data);
            return;
        }

        // 3. usual 설정에서 매칭 조건 읽기 및 매칭 실행
        $setting = $this->ppurio_setting_model->getSetting('usual');
        $cond_str = isset($setting['mem_match_cond']) ? $setting['mem_match_cond'] : '';
        $mem_cond_arr = $this->_parseMatchConditions($cond_str);

        // 4. 매칭 결과 수집 (개인 대상은 resume/recruit 순서)
        if (in_array('area', $mem_cond_arr)) {
            $resume_by_area  = $this->match_model->groupByArea($resumes);
            $recruit_by_area = $this->match_model->groupByArea($recruits);
            $matches_for_member = array();
            foreach ($resume_by_area as $area => $area_resumes) {
                if (!isset($recruit_by_area[$area])) continue;
                foreach ($area_resumes as $resume) {
                    foreach ($recruit_by_area[$area] as $recruit) {
                        if ($this->match_model->isMatched($resume, $recruit, $mem_cond_arr)) {
                            $matches_for_member[] = array('resume' => $resume, 'recruit' => $recruit);
                        }
                    }
                }
            }
        } else {
            $pairs = $this->match_model->matchAllPairs($resumes, $recruits, $mem_cond_arr);
            $matches_for_member = array();
            foreach ($pairs as $p) {
                $matches_for_member[] = array('resume' => $p['resume'], 'recruit' => $p['recruit']);
            }
        }

        // 5. 개인별 최신 1건만 추출
        $member_latest = array();
        foreach ($matches_for_member as $match) {
            $mem_idx = $match['resume']['mm_idx'];
            if (!isset($member_latest[$mem_idx]) ||
                strtotime($match['recruit']['reg_dt']) > strtotime($member_latest[$mem_idx]['recruit']['reg_dt'])) {
                $member_latest[$mem_idx] = $match;
            }
        }

        // 6. 중복 체크: 이번 주에 이미 발송한 대상 제외
        $send_targets = array();
        foreach ($member_latest as $match) {
            $result = $this->db->query(
                "SELECT ms_idx FROM st_match_alimtalk_sent
                 WHERE sender_type = 'MEM'
                   AND receiver_mm_idx = ?
                   AND target_key = ?
                   AND sent_week = ?",
                array($match['resume']['mm_idx'], $match['recruit']['rc_idx'], $current_week)
            );
            $dup_check = $result->row_array();

            if (empty($dup_check)) {
                $send_targets[] = $match;
            }
        }

        // 7. 검색 파라미터 수신 및 필터링
        $search_type  = input_get("search_type", "");
        $search_value = input_get("search_value", "");
        $category     = $this->input->get("category");
        if (!is_array($category)) $category = array();

        if ($search_value != "") {
            $send_targets = array_filter($send_targets, function($match) use ($search_type, $search_value) {
                $r = $match['recruit'];
                $m = $match['resume'];
                $matches_any = false;
                if ($search_type === "" || $search_type === "mm_name") {
                    if (strpos($m['mm_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "rc_name") {
                    if (strpos($r['rc_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "ad_addr") {
                    if (strpos($r['ad_addr'], $search_value) !== false) $matches_any = true;
                }
                return $matches_any;
            });
        }

        if (!empty($category)) {
            $send_targets = array_filter($send_targets, function($match) use ($category) {
                foreach ($category as $cat) {
                    if ($cat != "" && strpos($match['recruit']['rc_work'], $cat) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 지역 필터링
        $ad_addr = input_get("ad_addr", "");
        if ($ad_addr != "") {
            $send_targets = array_filter($send_targets, function($match) use ($ad_addr) {
                return strpos($match['recruit']['ad_addr'], $ad_addr) !== false;
            });
        }

        // 노출여부 필터링
        $rc_show_yn = input_get("rc_show_yn", "");
        if ($rc_show_yn != "") {
            $send_targets = array_filter($send_targets, function($match) use ($rc_show_yn) {
                return $match['recruit']['rc_show_yn'] === $rc_show_yn;
            });
        }

        $send_targets = array_values($send_targets);

        // 8. 카테고리 목록 조회
        $category_list = $this->site_model->getCodeList(array("cd_grp" => "희망업무"));
        $select_category = getKeyValue($category_list, "cd_val", "cd_val");

        // 9. 뷰에 전달
        $data = array(
            'result_list' => $send_targets,
            'result_count' => count($send_targets),
            'search' => array(
                'search_type'  => $search_type,
                'search_value' => $search_value,
                'category'     => $category,
                'ad_addr'      => $ad_addr,
                'rc_show_yn'   => $rc_show_yn,
            ),
            'select_category' => $select_category,
        );

        $this->layout->admin('admin/ppurio/send_sms_member', $data);
    }

    // 매칭 채용공고 SMS 발송 (개인 대상) - 발송 처리
    function sendMemPpurioSms_proc() {
        $sent_count = 0;
        $current_week = date('Y-W');

        // 1. 활성 이력서 조회
        $resumes = $this->match_model->getActiveResumes();
        // 2. 게재 중인 채용공고 조회
        $recruits = $this->match_model->getActiveRecruits();

        if (empty($resumes) || empty($recruits)) {
            json_return("99", "발송 대상이 없습니다.");
            return;
        }

        // 3. usual 설정에서 매칭 조건 읽기 및 매칭 실행
        $setting = $this->ppurio_setting_model->getSetting('usual');
        $cond_str = isset($setting['mem_match_cond']) ? $setting['mem_match_cond'] : '';
        $mem_cond_arr = $this->_parseMatchConditions($cond_str);

        // 4. 매칭 결과 수집 (개인 대상은 resume/recruit 순서)
        if (in_array('area', $mem_cond_arr)) {
            $resume_by_area  = $this->match_model->groupByArea($resumes);
            $recruit_by_area = $this->match_model->groupByArea($recruits);
            $matches_for_member = array();
            foreach ($resume_by_area as $area => $area_resumes) {
                if (!isset($recruit_by_area[$area])) continue;
                foreach ($area_resumes as $resume) {
                    foreach ($recruit_by_area[$area] as $recruit) {
                        if ($this->match_model->isMatched($resume, $recruit, $mem_cond_arr)) {
                            $matches_for_member[] = array('resume' => $resume, 'recruit' => $recruit);
                        }
                    }
                }
            }
        } else {
            $pairs = $this->match_model->matchAllPairs($resumes, $recruits, $mem_cond_arr);
            $matches_for_member = array();
            foreach ($pairs as $p) {
                $matches_for_member[] = array('resume' => $p['resume'], 'recruit' => $p['recruit']);
            }
        }

        if (empty($matches_for_member)) {
            json_return("99", "발송 대상이 없습니다.");
            return;
        }

        // 5. 개인별 최신 1건만 추출
        $member_latest = array();
        foreach ($matches_for_member as $match) {
            $mem_idx = $match['resume']['mm_idx'];
            if (!isset($member_latest[$mem_idx]) ||
                strtotime($match['recruit']['reg_dt']) > strtotime($member_latest[$mem_idx]['recruit']['reg_dt'])) {
                $member_latest[$mem_idx] = $match;
            }
        }

        // 6. SMS 발송
        foreach ($member_latest as $match) {
            try {
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

                // SMS 발송
                $sms_msg = "[나우잡] 조건에 맞는 채용공고가 있습니다. 확인하기: " . site_url('recruit/job_post/' . $recruit['rc_idx']);
                $this->common->sendNotificationSms($resume['mm_phone'], $sms_msg);

                // 발송 이력 기록
                $this->db->query(
                    "INSERT INTO st_match_alimtalk_sent
                     (sender_type, receiver_mm_idx, target_key, sent_week, sent_dt)
                     VALUES ('MEM', ?, ?, ?, NOW())
                     ON DUPLICATE KEY UPDATE sent_dt = NOW()",
                    array($resume['mm_idx'], $recruit['rc_idx'], $current_week)
                );
                $sent_count++;

            } catch (Exception $e) {
                continue;
            }
        }

        json_return("00", "발송 완료 - " . $sent_count . "건");
    }

    // 카카오 채널발송[일반회원] - 설정 화면
    function ppurio_katalk_usual(){
        $info = $this->ppurio_setting_model->getSetting('usual');
        $data = [
            "title" => "카카오 채널발송[일반회원]",
            "info" => $info,
        ];
        $this->layout->admin('admin/ppurio/ppurio_katalk_usual', $data);
    }

    // 카카오 채널발송[일반회원] - 설정 저장
    function ppurio_katalk_usual_proc(){
        $params = input_post_all();

        // 배열 값들을 ^ 구분자로 변환
        $data = [];
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode('^', $value);
            } else {
                $data[$key] = $value;
            }
        }

        $result = $this->ppurio_setting_model->updateSetting($data, 'usual');
        json_return("00", "설정이 저장되었습니다.", "");
    }

    // 카카오 채널발송[유료회원] - 설정 화면
    function ppurio_katalk_subs(){
        $info = $this->ppurio_setting_model->getSetting('subs');
        $data = [
            "title" => "카카오 채널발송[유료회원]",
            "info" => $info,
        ];
        $this->layout->admin('admin/ppurio/ppurio_katalk_subs', $data);
    }

    // 카카오 채널발송[유료회원] - 설정 저장
    function ppurio_katalk_subs_proc(){
        $params = input_post_all();

        // 배열 값들을 ^ 구분자로 변환
        $data = [];
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode('^', $value);
            } else {
                $data[$key] = $value;
            }
        }

        $result = $this->ppurio_setting_model->updateSetting($data, 'subs');
        json_return("00", "설정이 저장되었습니다.", "");
    }
}
?>
