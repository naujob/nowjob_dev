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

    // 추천인재 구매상품 개인 리스트 - 페이징 조회
    // 어제 기준 발송 로그를 조회하며, 기간/검색어로 필터링 가능
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

    


    // 추천인재 구매상품 개인 엑셀다운로드 - 2개 sheet로 구성 (요약, 기업별 알림동의)
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

    // 매칭 이력서 SMS 발송 (기업회원 대상) - 1단계 채용공고 목록 조회
    // 모든 활성 채용공고를 조회하며, 검색/카테고리/지역/노출여부로 필터링
    // 페이징: 20건 단위, 검색조건은 reset=1로 초기화 가능
    function send_sms_corp() {
        // reset 파라미터 확인 - 검색 조건 초기화
        $reset = input_get("reset", "");
        if ($reset == "1") {
            redirect('/admin/ppurio/send_sms_corp');
            return;
        }

        // 활성 채용공고 조회 (플랫 구조)
        $recruits = $this->match_model->getActiveRecruits();

        if (empty($recruits)) {
            $data = array(
                'result_list' => array(),
                'result_count' => 0,
                'search' => array('search_type' => '', 'search_value' => '', 'category' => array()),
                'select_category' => array(),
            );
            $this->layout->admin('admin/ppurio/send_sms_corp', $data);
            return;
        }

        // 검색 파라미터 수신
        $search_type  = input_get("search_type", "");
        $search_value = input_get("search_value", "");
        $category     = $this->input->get("category");
        if (!is_array($category)) $category = array();
        $rc_age       = $this->input->get("rc_age");
        if (!is_array($rc_age)) $rc_age = array();
        $rc_nation    = $this->input->get("rc_nation");
        if (!is_array($rc_nation)) $rc_nation = array();
        $rc_sex       = $this->input->get("rc_sex");
        if (!is_array($rc_sex)) $rc_sex = array();
        $rc_work_type = $this->input->get("rc_work_type");
        if (!is_array($rc_work_type)) $rc_work_type = array();
        $rc_price_type = $this->input->get("rc_price_type");
        if (!is_array($rc_price_type)) $rc_price_type = array();

        // "전체" 옵션(value="") 처리 - 전체 선택 시 필터 무시
        if (in_array('', $category)) $category = array();
        if (in_array('', $rc_age)) $rc_age = array();
        if (in_array('', $rc_nation)) $rc_nation = array();
        if (in_array('', $rc_sex)) $rc_sex = array();
        if (in_array('', $rc_work_type)) $rc_work_type = array();
        if (in_array('', $rc_price_type)) $rc_price_type = array();

        // 필터링 적용
        $result_list = $recruits;

        if ($search_value != "") {
            $result_list = array_filter($result_list, function($row) use ($search_type, $search_value) {
                $matches_any = false;
                if ($search_type === "" || $search_type === "mm_comp_name") {
                    if (strpos($row['mm_comp_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "rc_name") {
                    if (strpos($row['rc_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "ad_addr") {
                    if (strpos($row['ad_addr'], $search_value) !== false) $matches_any = true;
                }
                return $matches_any;
            });
        }

        if (!empty($category)) {
            $result_list = array_filter($result_list, function($row) use ($category) {
                foreach ($category as $cat) {
                    if ($cat != "" && strpos($row['rc_work'], $cat) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 국적 필터링 (배열, 부분 일치)
        if (!empty($rc_nation)) {
            $result_list = array_filter($result_list, function($row) use ($rc_nation) {
                foreach ($rc_nation as $nation) {
                    if ($nation != "" && strpos($row['rc_nation'], $nation) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 성별 필터링 (배열, 부분 일치)
        if (!empty($rc_sex)) {
            $result_list = array_filter($result_list, function($row) use ($rc_sex) {
                foreach ($rc_sex as $sex) {
                    if ($sex != "" && strpos($row['rc_sex'], $sex) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 연령 필터링 (배열, rc_sage, rc_eage 범위 비교)
        if (!empty($rc_age)) {
            $age_map = [
                "20대" => ["start" => 20, "end" => 29],
                "30대" => ["start" => 30, "end" => 39],
                "40대" => ["start" => 40, "end" => 49],
                "50대" => ["start" => 50, "end" => 59],
                "60대" => ["start" => 60, "end" => 69],
                "70대" => ["start" => 70, "end" => 79],
            ];

            $result_list = array_filter($result_list, function($row) use ($rc_age, $age_map) {
                $rc_sage = isset($row['rc_sage']) ? (int)$row['rc_sage'] : 0;
                $rc_eage = isset($row['rc_eage']) ? (int)$row['rc_eage'] : 0;

                foreach ($rc_age as $age) {
                    if ($age != "" && isset($age_map[$age])) {
                        $age_range = $age_map[$age];
                        if ($rc_eage >= $age_range['start'] && $rc_sage <= $age_range['end']) {
                            return true;
                        }
                    }
                }
                return false;
            });
        }

        // 근무형태 필터링
        if (!empty($rc_work_type)) {
            $result_list = array_filter($result_list, function($row) use ($rc_work_type) {
                foreach ($rc_work_type as $w) {
                    if ($w != "" && strpos($row['rc_work_type'], $w) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 급여형태 필터링
        if (!empty($rc_price_type)) {
            $result_list = array_filter($result_list, function($row) use ($rc_price_type) {
                foreach ($rc_price_type as $wt) {
                    if ($wt != "" && strpos($row['rc_price_type'], $wt) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        $result_list = array_values($result_list);

        // 각 공고에 대해 추가 노출지역 조회 (같은 mm_idx, rc_idx의 RECRUIT_EXPOSE 타입)
        foreach ($result_list as $key => $row) {
            $result_list[$key]['expose_addrs'] = array();

            if (isset($row['mm_idx']) && isset($row['rc_idx'])) {
                $expose_query = $this->db->query(
                    "SELECT DISTINCT ad_addr FROM st_address WHERE mm_idx = ? AND ad_key = ? AND ad_type IN ('RECRUIT_EXPOSE_1', 'RECRUIT_EXPOSE_2')",
                    array($row['mm_idx'], $row['rc_idx'])
                );
                if ($expose_query) {
                    $expose_addrs = $expose_query->result_array();
                    if (!empty($expose_addrs)) {
                        $result_list[$key]['expose_addrs'] = array_map(function($a) { return $a['ad_addr']; }, $expose_addrs);
                    }
                }
            }
        }

        // 카테고리 목록 조회
        $category_list = $this->site_model->getCodeList(array("cd_grp" => "희망업무"));
        $select_category = getKeyValue($category_list, "cd_val", "cd_val");
        // 디자인, 매장관리 제거
        unset($select_category["디자인"]);
        unset($select_category["매장관리"]);

        // 근무형태 목록 조회
        $work_list = $this->site_model->getCodeList(array("cd_grp" => "근무형태"));
        $select_work = getKeyValue($work_list, "cd_val", "cd_val");

        // 급여형태 목록 조회 (recruit_write.php와 동일)
        $select_work_type = array(
            "시급" => "시급",
            "일급" => "일급",
            "주급" => "주급",
            "월급" => "월급",
            "급여협의" => "급여협의"
        );

        // 뷰에 전달
        $data = array(
            'result_list' => $result_list,
            'result_count' => count($result_list),
            'search' => array(
                'search_type'  => $search_type,
                'search_value' => $search_value,
                'category'     => $category,
                'rc_age'       => is_array($rc_age) ? $rc_age : array(),
                'rc_nation'    => is_array($rc_nation) ? $rc_nation : array(),
                'rc_sex'       => is_array($rc_sex) ? $rc_sex : array(),
                'rc_work_type' => $rc_work_type,
                'rc_price_type' => $rc_price_type,
            ),
            'select_category' => $select_category,
            'select_work' => $select_work,
            'select_work_type' => $select_work_type,
        );

        $this->layout->admin('admin/ppurio/send_sms_corp', $data);
    }

    // SMS 발송 (기업 대상) - 발송 처리
    // 매칭 이력서 SMS 발송 (기업회원 대상) - 2단계 이력서 목록 조회
    // POST로 넘어온 선택된 기업(mm_idx[]) 배열을 받아 보관
    // 모든 활성 이력서를 조회해 다중 선택 화면 표시
    function send_sms_corp_resume() {
        // 선택된 기업 mm_idx 배열 받기
        $selected_mm_idx = $this->input->post("mm_idx");
        if (!is_array($selected_mm_idx)) $selected_mm_idx = array();

        if (empty($selected_mm_idx)) {
            redirect('/admin/ppurio/send_sms_corp');
            return;
        }

        // 검색 조건 받기
        $search_type = $this->input->post("search_type");
        $search_type = isset($search_type) ? $search_type : "";
        $search_value = $this->input->post("search_value", "");
        $category = $this->input->post("category");
        if (!is_array($category)) $category = array();
        $mm_age = $this->input->post("mm_age");
        if (!is_array($mm_age)) $mm_age = array();
        $mm_nation = $this->input->post("mm_nation");
        if (!is_array($mm_nation)) $mm_nation = array();
        $mm_sex = $this->input->post("mm_sex");
        if (!is_array($mm_sex)) $mm_sex = array();
        $ru_work_type = $this->input->post("ru_work_type");
        if (!is_array($ru_work_type)) $ru_work_type = array();
        $ru_price_type = $this->input->post("ru_price_type");
        if (!is_array($ru_price_type)) $ru_price_type = array();

        // "전체" 옵션(value="") 처리 - 전체 선택 시 필터 무시
        if (in_array('', $category)) $category = array();
        if (in_array('', $mm_age)) $mm_age = array();
        if (in_array('', $mm_nation)) $mm_nation = array();
        if (in_array('', $mm_sex)) $mm_sex = array();
        if (in_array('', $ru_work_type)) $ru_work_type = array();
        if (in_array('', $ru_price_type)) $ru_price_type = array();

        // 활성 이력서 조회
        $resumes = $this->match_model->getActiveResumes();

        // 검색 조건에 따른 필터링
        $filtered_resumes = array();
        foreach ($resumes as $resume) {
            $match = true;

            // 검색어 필터 (search_type에 따라 다중 필드 검색)
            if (!empty($search_value)) {
                $matches_any = false;
                if ($search_type === "" || $search_type === "mm_name") {
                    if (strpos($resume['mm_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "ru_name") {
                    if (strpos($resume['ru_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "ad_addr") {
                    if (strpos($resume['ad_addr'], $search_value) !== false) $matches_any = true;
                }
                if (!$matches_any) {
                    $match = false;
                }
            }

            // 카테고리 필터
            if (!empty($category)) {
                if (!in_array($resume['ru_work'], $category)) {
                    $match = false;
                }
            }

            // 연령 필터 (배열, mm_birth 기반 나이 계산)
            if (!empty($mm_age)) {
                $age_map = [
                    "20대" => ["start" => 20, "end" => 29],
                    "30대" => ["start" => 30, "end" => 39],
                    "40대" => ["start" => 40, "end" => 49],
                    "50대" => ["start" => 50, "end" => 59],
                    "60대" => ["start" => 60, "end" => 69],
                    "70대" => ["start" => 70, "end" => 79],
                ];

                if (!isset($resume['mm_birth']) || empty($resume['mm_birth'])) {
                    $match = false;
                } else {
                    $birth = strtotime($resume['mm_birth']);
                    $current_age = date('Y') - date('Y', $birth);
                    if (date('md') < date('md', $birth)) $current_age--;

                    $matches_any = false;
                    foreach ($mm_age as $age) {
                        if ($age != "" && isset($age_map[$age])) {
                            $age_range = $age_map[$age];
                            if ($current_age >= $age_range['start'] && $current_age <= $age_range['end']) {
                                $matches_any = true;
                                break;
                            }
                        }
                    }
                    if (!$matches_any) {
                        $match = false;
                    }
                }
            }

            // 국적 필터 (배열, 부분 일치)
            if (!empty($mm_nation)) {
                $matches_any = false;
                foreach ($mm_nation as $nation) {
                    if ($nation != "" && strpos($resume['mm_nation'], $nation) !== false) {
                        $matches_any = true;
                        break;
                    }
                }
                if (!$matches_any) {
                    $match = false;
                }
            }

            // 성별 필터 (배열, 부분 일치)
            if (!empty($mm_sex)) {
                $matches_any = false;
                foreach ($mm_sex as $sex) {
                    if ($sex != "" && strpos($resume['mm_sex'], $sex) !== false) {
                        $matches_any = true;
                        break;
                    }
                }
                if (!$matches_any) {
                    $match = false;
                }
            }

            // 근무형태 필터
            if (!empty($ru_work_type)) {
                if (!in_array($resume['ru_work_type'], $ru_work_type)) {
                    $match = false;
                }
            }

            // 급여형태 필터
            if (!empty($ru_price_type)) {
                if (!in_array($resume['ru_price_type'], $ru_price_type)) {
                    $match = false;
                }
            }

            if ($match) {
                $filtered_resumes[] = $resume;
            }
        }

        // 카테고리 옵션
        $category_list = $this->site_model->getCodeList(array("cd_grp" => "희망업무"));
        $select_category = getKeyValue($category_list, "cd_val", "cd_val");

        // 근무형태 옵션
        $work_list = $this->site_model->getCodeList(array("cd_grp" => "근무형태"));
        $select_work = getKeyValue($work_list, "cd_val", "cd_val");

        // 급여형태 옵션 (하드코딩)
        $select_work_type = array(
            "시급" => "시급",
            "일급" => "일급",
            "주급" => "주급",
            "월급" => "월급",
            "급여협의" => "급여협의"
        );

        $data = array(
            'result_list' => $filtered_resumes,
            'result_count' => count($filtered_resumes),
            'selected_mm_idx' => $selected_mm_idx,
            'search' => array(
                'search_type' => $search_type,
                'search_value' => $search_value,
                'category' => $category,
                'mm_age' => is_array($mm_age) ? $mm_age : array(),
                'mm_nation' => is_array($mm_nation) ? $mm_nation : array(),
                'mm_sex' => is_array($mm_sex) ? $mm_sex : array(),
                'ru_work_type' => $ru_work_type,
                'ru_price_type' => $ru_price_type,
            ),
            'select_category' => $select_category,
            'select_work' => $select_work,
            'select_work_type' => $select_work_type,
        );

        $this->layout->admin('admin/ppurio/send_sms_corp_resume', $data);
    }

    // 매칭 이력서 SMS 발송 (기업회원 대상) - 2단계 SMS 발송 처리 (AJAX)
    // 선택된 기업(mm_idx[]) × 선택된 이력서(ru_idx[])의 모든 조합에 대해 SMS 발송
    // 주 단위 중복 체크로 이미 발송한 조합은 스킵
    function send_sms_corp_resume_proc() {
        $sent_count = 0;
        $fail_count = 0;
        $current_week = date('Y-W');

        // 선택된 이력서 ru_idx 배열 수신
        $selected_ru_idx = $this->input->post("selected_ru_idx");
        if (!is_array($selected_ru_idx)) {
            $selected_ru_idx = array();
        }

        // 선택된 기업 mm_idx 배열 수신
        $selected_mm_idx = $this->input->post("selected_mm_idx");
        if (!is_array($selected_mm_idx)) {
            $selected_mm_idx = array();
        }

        if (empty($selected_ru_idx)) {
            json_return("99", "이력서를 선택해주세요.");
            return;
        }

        if (empty($selected_mm_idx)) {
            json_return("99", "기업을 선택해주세요.");
            return;
        }

        // 게재 중인 채용공고 조회 - 기업별 연락처(mm_phone) 확보용
        $recruits = $this->match_model->getActiveRecruits();
        $recruit_by_mm = array();
        foreach ($recruits as $r) {
            if (!isset($recruit_by_mm[$r['mm_idx']])) {
                $recruit_by_mm[$r['mm_idx']] = $r;
            }
        }

        // 선택된 기업 × 선택된 이력서 전체 조합에 대해 발송
        foreach ($selected_mm_idx as $mm_idx) {
            if (!isset($recruit_by_mm[$mm_idx])) {
            
                $fail_count++;
                continue;
            }
            $recruit = $recruit_by_mm[$mm_idx];

            foreach ($selected_ru_idx as $ru_idx) {
                try {
                    // 중복 체크 (기업-이력서-주 단위) - SMS 발송 이력 테이블
                    $result = $this->db->query(
                        "SELECT ms_idx FROM st_match_sms_sent
                         WHERE sender_type = 'COMP'
                           AND receiver_mm_idx = ?
                           AND target_key = ?
                           AND sent_week = ?",
                        array($mm_idx, $ru_idx, $current_week)
                    );
                    $dup_check = $result->row_array();
                    if (!empty($dup_check)) continue;

                    if (empty($recruit['mm_phone'])) {

                        $fail_count++;
                        continue;
                    }

                    // SMS 메시지 (90바이트 제한)
                    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $main_domain = str_replace('admin.', '', $_SERVER['HTTP_HOST']);
                    $is_production = (strpos(__FILE__, '/nowapk/') !== false && strpos(__FILE__, '/dev_nowapk/') === false);
                    $bridge_path = $is_production ? '/alimtalk/match_corp' : '/alimtalk/send_match_corp_bridge';
                    $sms_msg = "[나우잡]\n등록된 채용공고에 적합한 추천인재가 등록되었습니다.\n\n[이력서 확인하기]\n" . $scheme . '://' . $main_domain . $bridge_path;
                    $send_result = $this->common->sendNotificationSms($recruit['mm_phone'], $sms_msg);

                    if ($send_result['code'] === '00') {
                        $this->db->query(
                            "INSERT INTO st_match_sms_sent
                             (sender_type, receiver_mm_idx, target_key, source_key, sent_week, sent_dt, read_yn)
                             VALUES ('COMP', ?, ?, ?, ?, NOW(), 'N')
                             ON DUPLICATE KEY UPDATE sent_dt = NOW(), read_yn = 'N'",
                            array($mm_idx, $ru_idx, $recruit['rc_idx'], $current_week)
                        );
                        $sent_count++;

                        // AI 매칭 PUSH 알림 발송 (resume_by_mm에서 이력서명 조회)
                        $resume_name = '';
                        if (isset($resume_by_mm[$mm_idx]) && isset($resume_by_mm[$mm_idx]['ru_name'])) {
                            $resume_name = $resume_by_mm[$mm_idx]['ru_name'];
                        }
                        sendMatchPush($mm_idx, 'COMP', $resume_name, $ru_idx);
                    } else {

                        $fail_count++;
                    }
                } catch (Exception $e) {

                    $fail_count++;
                }
            }
        }

        if ($fail_count > 0) {
            json_return("00", "발송 완료 - 성공: " . $sent_count . "건, 실패: " . $fail_count . "건");
        } else {
            json_return("00", "발송 완료 - " . $sent_count . "건");
        }
    }

    // 매칭 채용공고 SMS 발송 (개인회원 대상) - 2단계 채용공고 목록 조회
    // POST로 넘어온 선택된 개인/이력서(mm_idx[]) 배열을 받아 보관
    // 모든 활성 채용공고를 조회해 다중 선택 화면 표시
    function send_sms_member_recruit() {
        // 선택된 개인 mm_idx 배열 받기
        $selected_mm_idx = $this->input->post("mm_idx");
        if (!is_array($selected_mm_idx)) $selected_mm_idx = array();

        if (empty($selected_mm_idx)) {
            redirect('/admin/ppurio/send_sms_member');
            return;
        }

        // 검색 조건 받기
        $search_type = $this->input->post("search_type");
        $search_type = isset($search_type) ? $search_type : "";
        $search_value = $this->input->post("search_value", "");
        $category = $this->input->post("category");
        if (!is_array($category)) $category = array();
        $rc_age = $this->input->post("rc_age");
        if (!is_array($rc_age)) $rc_age = array();
        $rc_nation = $this->input->post("rc_nation");
        if (!is_array($rc_nation)) $rc_nation = array();
        $rc_sex = $this->input->post("rc_sex");
        if (!is_array($rc_sex)) $rc_sex = array();
        $rc_work_type = $this->input->post("rc_work_type");
        if (!is_array($rc_work_type)) $rc_work_type = array();
        $rc_price_type = $this->input->post("rc_price_type");
        if (!is_array($rc_price_type)) $rc_price_type = array();

        // "전체" 옵션(value="") 처리 - 전체 선택 시 필터 무시
        if (in_array('', $category)) $category = array();
        if (in_array('', $rc_age)) $rc_age = array();
        if (in_array('', $rc_nation)) $rc_nation = array();
        if (in_array('', $rc_sex)) $rc_sex = array();
        if (in_array('', $rc_work_type)) $rc_work_type = array();
        if (in_array('', $rc_price_type)) $rc_price_type = array();

        // 활성 채용공고 조회
        $recruits = $this->match_model->getActiveRecruits();

        // 검색 조건에 따른 필터링
        $filtered_recruits = array();
        foreach ($recruits as $recruit) {
            $match = true;

            // 검색어 필터 (search_type에 따라 다중 필드 검색)
            if (!empty($search_value)) {
                $matches_any = false;
                if ($search_type === "" || $search_type === "mm_comp_name") {
                    if (strpos($recruit['mm_comp_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "rc_name") {
                    if (strpos($recruit['rc_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "ad_addr") {
                    if (strpos($recruit['ad_addr'], $search_value) !== false) $matches_any = true;
                }
                if (!$matches_any) {
                    $match = false;
                }
            }

            // 카테고리 필터
            if (!empty($category)) {
                if (!in_array($recruit['rc_work'], $category)) {
                    $match = false;
                }
            }

            // 연령 필터 (배열, rc_sage, rc_eage 범위 비교)
            if (!empty($rc_age)) {
                $age_map = [
                    "20대" => ["start" => 20, "end" => 29],
                    "30대" => ["start" => 30, "end" => 39],
                    "40대" => ["start" => 40, "end" => 49],
                    "50대" => ["start" => 50, "end" => 59],
                    "60대" => ["start" => 60, "end" => 69],
                    "70대" => ["start" => 70, "end" => 79],
                ];

                $rc_sage = isset($recruit['rc_sage']) ? (int)$recruit['rc_sage'] : 0;
                $rc_eage = isset($recruit['rc_eage']) ? (int)$recruit['rc_eage'] : 0;
                $matches_any = false;

                foreach ($rc_age as $age) {
                    if ($age != "" && isset($age_map[$age])) {
                        $age_range = $age_map[$age];
                        if ($rc_eage >= $age_range['start'] && $rc_sage <= $age_range['end']) {
                            $matches_any = true;
                            break;
                        }
                    }
                }
                if (!$matches_any) {
                    $match = false;
                }
            }

            // 국적 필터 (배열, 부분 일치)
            if (!empty($rc_nation)) {
                $matches_any = false;
                foreach ($rc_nation as $nation) {
                    if ($nation != "" && strpos($recruit['rc_nation'], $nation) !== false) {
                        $matches_any = true;
                        break;
                    }
                }
                if (!$matches_any) {
                    $match = false;
                }
            }

            // 성별 필터 (배열, 부분 일치)
            if (!empty($rc_sex)) {
                $matches_any = false;
                foreach ($rc_sex as $sex) {
                    if ($sex != "" && strpos($recruit['rc_sex'], $sex) !== false) {
                        $matches_any = true;
                        break;
                    }
                }
                if (!$matches_any) {
                    $match = false;
                }
            }

            // 근무형태 필터
            if (!empty($rc_work_type)) {
                if (!in_array($recruit['rc_work_type'], $rc_work_type)) {
                    $match = false;
                }
            }

            // 급여형태 필터
            if (!empty($rc_price_type)) {
                if (!in_array($recruit['rc_price_type'], $rc_price_type)) {
                    $match = false;
                }
            }

            if ($match) {
                $filtered_recruits[] = $recruit;
            }
        }

        // 각 공고에 대해 추가 노출지역 조회 (같은 mm_idx, rc_idx의 RECRUIT_EXPOSE 타입)
        foreach ($filtered_recruits as $key => $row) {
            $filtered_recruits[$key]['expose_addrs'] = array();

            if (isset($row['mm_idx']) && isset($row['rc_idx'])) {
                $expose_query = $this->db->query(
                    "SELECT DISTINCT ad_addr FROM st_address WHERE mm_idx = ? AND ad_key = ? AND ad_type IN ('RECRUIT_EXPOSE_1', 'RECRUIT_EXPOSE_2')",
                    array($row['mm_idx'], $row['rc_idx'])
                );
                if ($expose_query) {
                    $expose_addrs = $expose_query->result_array();
                    if (!empty($expose_addrs)) {
                        $filtered_recruits[$key]['expose_addrs'] = array_map(function($a) { return $a['ad_addr']; }, $expose_addrs);
                    }
                }
            }
        }

        // 카테고리 옵션
        $category_list = $this->site_model->getCodeList(array("cd_grp" => "희망업무"));
        $select_category = getKeyValue($category_list, "cd_val", "cd_val");
        // 디자인, 매장관리 제거
        unset($select_category["디자인"]);
        unset($select_category["매장관리"]);

        // 근무형태 옵션
        $work_list = $this->site_model->getCodeList(array("cd_grp" => "근무형태"));
        $select_work = getKeyValue($work_list, "cd_val", "cd_val");

        // 급여형태 옵션 (하드코딩)
        $select_work_type = array(
            "시급" => "시급",
            "일급" => "일급",
            "주급" => "주급",
            "월급" => "월급",
            "급여협의" => "급여협의"
        );

        $data = array(
            'result_list' => $filtered_recruits,
            'result_count' => count($filtered_recruits),
            'selected_mm_idx' => $selected_mm_idx,
            'search' => array(
                'search_type' => $search_type,
                'search_value' => $search_value,
                'category' => $category,
                'rc_age' => is_array($rc_age) ? $rc_age : array(),
                'rc_nation' => $rc_nation,
                'rc_sex' => $rc_sex,
                'rc_work_type' => $rc_work_type,
                'rc_price_type' => $rc_price_type,
            ),
            'select_category' => $select_category,
            'select_work' => $select_work,
            'select_work_type' => $select_work_type,
        );

        $this->layout->admin('admin/ppurio/send_sms_member_recruit', $data);
    }

    // 매칭 채용공고 SMS 발송 (개인회원 대상) - 2단계 SMS 발송 처리 (AJAX)
    // 선택된 개인/이력서(mm_idx[]) × 선택된 채용공고(rc_idx[])의 모든 조합에 대해 SMS 발송
    // 주 단위 중복 체크로 이미 발송한 조합은 스킵
    function send_sms_member_recruit_proc() {
        $sent_count = 0;
        $fail_count = 0;
        $current_week = date('Y-W');

        // 선택된 채용공고 rc_idx 배열 수신
        $selected_rc_idx = $this->input->post("selected_rc_idx");
        if (!is_array($selected_rc_idx)) {
            $selected_rc_idx = array();
        }

        // 선택된 회원 mm_idx 배열 수신
        $selected_mm_idx = $this->input->post("selected_mm_idx");
        if (!is_array($selected_mm_idx)) {
            $selected_mm_idx = array();
        }

        if (empty($selected_rc_idx)) {
            json_return("99", "채용공고를 선택해주세요.");
            return;
        }

        if (empty($selected_mm_idx)) {
            json_return("99", "회원을 선택해주세요.");
            return;
        }

        // 활성 이력서 조회 - 개인별 연락처(mm_phone) 확보용
        $resumes = $this->match_model->getActiveResumes();
        $resume_by_mm = array();
        foreach ($resumes as $r) {
            if (!isset($resume_by_mm[$r['mm_idx']])) {
                $resume_by_mm[$r['mm_idx']] = $r;
            }
        }

        // 선택된 회원 × 선택된 채용공고 전체 조합에 대해 발송
        foreach ($selected_mm_idx as $mm_idx) {
            if (!isset($resume_by_mm[$mm_idx])) {

                $fail_count++;
                continue;
            }
            $resume = $resume_by_mm[$mm_idx];

            foreach ($selected_rc_idx as $rc_idx) {
                try {
                    // 중복 체크 (개인-채용공고-주 단위) - SMS 발송 이력 테이블
                    $result = $this->db->query(
                        "SELECT ms_idx FROM st_match_sms_sent
                         WHERE sender_type = 'MEM'
                           AND receiver_mm_idx = ?
                           AND target_key = ?
                           AND sent_week = ?",
                        array($mm_idx, $rc_idx, $current_week)
                    );
                    $dup_check = $result->row_array();
                    if (!empty($dup_check)) continue;

                    if (empty($resume['mm_phone'])) {

                        $fail_count++;
                        continue;
                    }

                    // SMS 메시지 (90바이트 제한)
                    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $main_domain = str_replace('admin.', '', $_SERVER['HTTP_HOST']);
                    $is_production = (strpos(__FILE__, '/nowapk/') !== false && strpos(__FILE__, '/dev_nowapk/') === false);
                    $bridge_path = $is_production ? '/alimtalk/match_per' : '/alimtalk/send_per_bridge';
                    $sms_msg = "[나우잡]\n등록된 이력서에 적합한 채용공고가 매칭되었습니다\n\n[채용공고 확인하기]\n" . $scheme . '://' . $main_domain . $bridge_path;
                    $send_result = $this->common->sendNotificationSms($resume['mm_phone'], $sms_msg);

                    if ($send_result['code'] === '00') {
                        $this->db->query(
                            "INSERT INTO st_match_sms_sent
                             (sender_type, receiver_mm_idx, target_key, source_key, sent_week, sent_dt, read_yn)
                             VALUES ('MEM', ?, ?, ?, ?, NOW(), 'N')
                             ON DUPLICATE KEY UPDATE sent_dt = NOW(), read_yn = 'N'",
                            array($mm_idx, $rc_idx, $resume['ru_idx'], $current_week)
                        );
                        $sent_count++;

                        // AI 매칭 PUSH 알림 발송 (recruit_by_mm에서 공고명 조회)
                        $recruit_name = '';
                        if (isset($recruit_by_mm[$mm_idx]) && isset($recruit_by_mm[$mm_idx]['rc_name'])) {
                            $recruit_name = $recruit_by_mm[$mm_idx]['rc_name'];
                        }
                        sendMatchPush($mm_idx, 'MEM', $recruit_name, $rc_idx);
                    } else {

                        $fail_count++;
                    }
                } catch (Exception $e) {

                    $fail_count++;
                }
            }
        }

        if ($fail_count > 0) {
            json_return("00", "발송 완료 - 성공: " . $sent_count . "건, 실패: " . $fail_count . "건");
        } else {
            json_return("00", "발송 완료 - " . $sent_count . "건");
        }
    }

    // 매칭 채용공고 SMS 발송 (개인회원 대상) - 1단계 이력서/개인 목록 조회
    // 모든 활성 이력서를 조회하며, 검색/카테고리/지역/노출여부로 필터링
    // 페이징: 20건 단위, 검색조건은 reset=1로 초기화 가능
    function send_sms_member() {
        // reset 파라미터 확인 - 검색 조건 초기화
        $reset = input_get("reset", "");
        if ($reset == "1") {
            redirect('/admin/ppurio/send_sms_member');
            return;
        }

        // 활성 이력서 조회
        $resumes = $this->match_model->getActiveResumes();

        // 검색 파라미터
        $search_type  = input_get("search_type", "");
        $search_value = input_get("search_value", "");
        $category     = $this->input->get("category");
        if (!is_array($category)) $category = array();
        $mm_age       = $this->input->get("mm_age");
        if (!is_array($mm_age)) $mm_age = array();
        $mm_nation    = $this->input->get("mm_nation");
        if (!is_array($mm_nation)) $mm_nation = array();
        $mm_sex       = $this->input->get("mm_sex");
        if (!is_array($mm_sex)) $mm_sex = array();
        $ru_work_type = $this->input->get("ru_work_type");
        if (!is_array($ru_work_type)) $ru_work_type = array();
        $ru_price_type = $this->input->get("ru_price_type");
        if (!is_array($ru_price_type)) $ru_price_type = array();

        // "전체" 옵션(value="") 처리 - 전체 선택 시 필터 무시
        if (in_array('', $category)) $category = array();
        if (in_array('', $mm_age)) $mm_age = array();
        if (in_array('', $mm_nation)) $mm_nation = array();
        if (in_array('', $mm_sex)) $mm_sex = array();
        if (in_array('', $ru_work_type)) $ru_work_type = array();
        if (in_array('', $ru_price_type)) $ru_price_type = array();

        if ($search_value != "") { // strpos(문자열, 찾을문자열)  array_filter:true를 반환한 요소만 결과 배열에 남깁
            $resumes = array_filter($resumes, function($r) use ($search_type, $search_value) {
                $matches_any = false;
                if ($search_type === "" || $search_type === "mm_name") {
                    if (strpos($r['mm_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "ru_name") {
                    if (strpos($r['ru_name'], $search_value) !== false) $matches_any = true;
                }
                if ($search_type === "" || $search_type === "ad_addr") {
                    if (strpos($r['ad_addr'], $search_value) !== false) $matches_any = true;
                }
                return $matches_any;
            });
        }

        if (!empty($category)) {
            $resumes = array_filter($resumes, function($r) use ($category) {
                foreach ($category as $cat) {
                    if ($cat != "" && strpos($r['ru_work'], $cat) !== false) return true;
                }
                return false;
            });
        }

        // 연령 필터링 (배열, mm_birth 기반 나이 계산)
        if (!empty($mm_age)) {
            $age_map = [
                "20대" => ["start" => 20, "end" => 29],
                "30대" => ["start" => 30, "end" => 39],
                "40대" => ["start" => 40, "end" => 49],
                "50대" => ["start" => 50, "end" => 59],
                "60대" => ["start" => 60, "end" => 69],
                "70대" => ["start" => 70, "end" => 79],
            ];

            $resumes = array_filter($resumes, function($r) use ($mm_age, $age_map) {
                if (!isset($r['mm_birth']) || empty($r['mm_birth'])) return false;

                $birth = strtotime($r['mm_birth']);
                $current_age = date('Y') - date('Y', $birth);
                if (date('md') < date('md', $birth)) $current_age--;

                foreach ($mm_age as $age) {
                    if ($age != "" && isset($age_map[$age])) {
                        $age_range = $age_map[$age];
                        if ($current_age >= $age_range['start'] && $current_age <= $age_range['end']) {
                            return true;
                        }
                    }
                }
                return false;
            });
        }

        // 국적 필터링 (배열, 부분 일치)
        if (!empty($mm_nation)) {
            $resumes = array_filter($resumes, function($r) use ($mm_nation) {
                foreach ($mm_nation as $nation) {
                    if ($nation != "" && strpos($r['mm_nation'], $nation) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 성별 필터링 (배열, 부분 일치)
        if (!empty($mm_sex)) {
            $resumes = array_filter($resumes, function($r) use ($mm_sex) {
                foreach ($mm_sex as $sex) {
                    if ($sex != "" && strpos($r['mm_sex'], $sex) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 근무형태 필터링
        if (!empty($ru_work_type)) {
            $resumes = array_filter($resumes, function($r) use ($ru_work_type) {
                foreach ($ru_work_type as $w) {
                    if ($w != "" && strpos($r['ru_work_type'], $w) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 급여형태 필터링
        if (!empty($ru_price_type)) {
            $resumes = array_filter($resumes, function($r) use ($ru_price_type) {
                foreach ($ru_price_type as $wt) {
                    if ($wt != "" && strpos($r['ru_price_type'], $wt) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        $resumes = array_values($resumes);

        $category_list = $this->site_model->getCodeList(array("cd_grp" => "희망업무"));
        $select_category = getKeyValue($category_list, "cd_val", "cd_val");

        // 근무형태 목록 조회
        $work_list = $this->site_model->getCodeList(array("cd_grp" => "근무형태"));
        $select_work = getKeyValue($work_list, "cd_val", "cd_val");

        // 급여형태 목록 조회 (recruit_write.php와 동일)
        $select_work_type = array(
            "시급" => "시급",
            "일급" => "일급",
            "주급" => "주급",
            "월급" => "월급",
            "급여협의" => "급여협의"
        );

        $data = array(
            'result_list' => $resumes,
            'result_count' => count($resumes),
            'search' => array(
                'search_type'  => $search_type,
                'search_value' => $search_value,
                'category'     => $category,
                'mm_age'       => is_array($mm_age) ? $mm_age : array(),
                'mm_nation'    => is_array($mm_nation) ? $mm_nation : array(),
                'mm_sex'       => is_array($mm_sex) ? $mm_sex : array(),
                'ru_work_type' => $ru_work_type,
                'ru_price_type' => $ru_price_type,
            ),
            'select_category' => $select_category,
            'select_work' => $select_work,
            'select_work_type' => $select_work_type,
        );

        $this->layout->admin('admin/ppurio/send_sms_member', $data);
    }

    // 카카오 채널발송[일반회원] - 설정 화면 조회
    // ppurio_setting 모델에서 설정값을 조회해 뷰에 전달
    function ppurio_katalk_usual(){
        $info = $this->ppurio_setting_model->getSetting('usual');
        $data = [
            "title" => "카카오 채널발송[일반회원]",
            "info" => $info,
        ];
        $this->layout->admin('admin/ppurio/ppurio_katalk_usual', $data);
    }

    // 카카오 채널발송[일반회원] - 설정 저장 (AJAX)
    // POST로 넘어온 설정값들을 ^ 구분자로 변환후 DB에 저장
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

    // 카카오 채널발송[유료회원] - 설정 화면 조회
    // ppurio_setting 모델에서 설정값을 조회해 뷰에 전달
    function ppurio_katalk_subs(){
        $info = $this->ppurio_setting_model->getSetting('subs');
        $data = [
            "title" => "카카오 채널발송[유료회원]",
            "info" => $info,
        ];
        $this->layout->admin('admin/ppurio/ppurio_katalk_subs', $data);
    }

    // 카카오 채널발송[유료회원] - 설정 저장 (AJAX)
    // POST로 넘어온 설정값들을 ^ 구분자로 변환후 DB에 저장
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

    // 매칭 이력서 SMS 발송 내역 (기업 수신) - 조회
    function sms_cor_reslut(){
        $page = input_get("page", 1);
        $sdate = input_get("sdate", "");
        $edate = input_get("edate", "");
        $search_type = input_get("search_type", "");
        $search_value = input_get("search_value", "");
        $category = $this->input->get("category");
        if (!is_array($category)) $category = array();
        $age_range = $this->input->get("age_range");
        if (!is_array($age_range)) $age_range = array();
        $nationality = $this->input->get("nationality");
        if (!is_array($nationality)) $nationality = array();
        $gender = $this->input->get("gender");
        if (!is_array($gender)) $gender = array();
        $work_type = $this->input->get("work_type");
        if (!is_array($work_type)) $work_type = array();
        $price_type = $this->input->get("price_type");
        if (!is_array($price_type)) $price_type = array();

        // "전체" 옵션(value="") 처리 - 전체 선택 시 필터 무시
        if (in_array('', $category)) $category = array();
        if (in_array('', $age_range)) $age_range = array();
        if (in_array('', $nationality)) $nationality = array();
        if (in_array('', $gender)) $gender = array();
        if (in_array('', $work_type)) $work_type = array();
        if (in_array('', $price_type)) $price_type = array();

        $params = [
            "pagesize" => 20, "page" => $page,
            "sdate" => $sdate, "edate" => $edate,
            "search_type" => $search_type, "search_value" => $search_value,
            "category" => $category, "age_range" => $age_range, "nationality" => $nationality,
            "gender" => $gender, "work_type" => $work_type, "price_type" => $price_type,
        ];
        $result = $this->site_model->getCorSmsSentList($params);

        // 카테고리 목록 조회
        $category_list = $this->site_model->getCodeList(array("cd_grp" => "희망업무"));
        $select_category = getKeyValue($category_list, "cd_val", "cd_val");

        // 근무형태 목록 조회
        $work_list = $this->site_model->getCodeList(array("cd_grp" => "근무형태"));
        $select_work = getKeyValue($work_list, "cd_val", "cd_val");

        // 급여형태 목록 (recruit_write.php와 동일)
        $select_work_type = array(
            "시급" => "시급",
            "일급" => "일급",
            "주급" => "주급",
            "월급" => "월급",
            "급여협의" => "급여협의"
        );

        $data = [
            "search" => [
                "sdate" => $sdate, "edate" => $edate,
                "search_type" => $search_type, "search_value" => $search_value,
                "category" => $category, "age_range" => $age_range, "nationality" => $nationality,
                "gender" => $gender, "work_type" => $work_type, "price_type" => $price_type,
            ],
            "start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
            "result_list" => $result["list"],
            "result_count" => $result["count"],
            "pagenation" => $result["pagenation"],
            "select_category" => $select_category,
            "select_work" => $select_work,
            "select_work_type" => $select_work_type,
        ];

        $this->layout->admin('admin/ppurio/sms_cor_reslut', $data);
    }

    // 매칭 채용공고 SMS 발송 내역 (개인 수신) - 조회
    function sms_mem_reslut(){
        $page = input_get("page", 1);
        $sdate = input_get("sdate", "");
        $edate = input_get("edate", "");
        $search_type = input_get("search_type", "");
        $search_value = input_get("search_value", "");
        $category = $this->input->get("category");
        if (!is_array($category)) $category = array();
        $age_range = $this->input->get("age_range");
        if (!is_array($age_range)) $age_range = array();
        $nationality = $this->input->get("nationality");
        if (!is_array($nationality)) $nationality = array();
        $gender = $this->input->get("gender");
        if (!is_array($gender)) $gender = array();
        $work_type = $this->input->get("work_type");
        if (!is_array($work_type)) $work_type = array();
        $price_type = $this->input->get("price_type");
        if (!is_array($price_type)) $price_type = array();

        // "전체" 옵션(value="") 처리 - 전체 선택 시 필터 무시
        if (in_array('', $category)) $category = array();
        if (in_array('', $age_range)) $age_range = array();
        if (in_array('', $nationality)) $nationality = array();
        if (in_array('', $gender)) $gender = array();
        if (in_array('', $work_type)) $work_type = array();
        if (in_array('', $price_type)) $price_type = array();

        $params = [
            "pagesize" => 20, "page" => $page,
            "sdate" => $sdate, "edate" => $edate,
            "search_type" => $search_type, "search_value" => $search_value,
            "category" => $category, "age_range" => $age_range, "nationality" => $nationality,
            "gender" => $gender, "work_type" => $work_type, "price_type" => $price_type,
        ];
        $result = $this->site_model->getMemSmsSentList($params);

        // 카테고리 목록 조회
        $category_list = $this->site_model->getCodeList(array("cd_grp" => "희망업무"));
        $select_category = getKeyValue($category_list, "cd_val", "cd_val");

        // 근무형태 목록 조회
        $work_list = $this->site_model->getCodeList(array("cd_grp" => "근무형태"));
        $select_work = getKeyValue($work_list, "cd_val", "cd_val");

        // 급여형태 목록 (recruit_write.php와 동일)
        $select_work_type = array(
            "시급" => "시급",
            "일급" => "일급",
            "주급" => "주급",
            "월급" => "월급",
            "급여협의" => "급여협의"
        );

        $data = [
            "search" => [
                "sdate" => $sdate, "edate" => $edate,
                "search_type" => $search_type, "search_value" => $search_value,
                "category" => $category, "age_range" => $age_range, "nationality" => $nationality,
                "gender" => $gender, "work_type" => $work_type, "price_type" => $price_type,
            ],
            "start" => ($result["count"] - (($page - 1) * $params["pagesize"])),
            "result_list" => $result["list"],
            "result_count" => $result["count"],
            "pagenation" => $result["pagenation"],
            "select_category" => $select_category,
            "select_work" => $select_work,
            "select_work_type" => $select_work_type,
        ];

        $this->layout->admin('admin/ppurio/sms_mem_reslut', $data);
    }

    // 매칭 채용공고 SMS 발송 내역 차트 페이지 (개인 수신)
    function sms_mem_chart(){
        $sdate = input_get("sdate", "");
        $edate = input_get("edate", "");

        // 날짜 범위 기본값
        if(empty($sdate) || empty($edate)){
            $sdate = date("Y-m-d", strtotime("-13 days"));
            $edate = date("Y-m-d");
        }

        $params = [
            "sdate" => $sdate,
            "edate" => $edate,
        ];

        // 원본 데이터 조회 및 차원별 집계
        $result = $this->site_model->getMemSmsChartRawList($params);
        $chart_data = $this->_buildChartSeries($result, "업종", "mem");

        $data = [
            "search" => [
                "sdate" => $sdate,
                "edate" => $edate,
            ],
            "chart" => $chart_data,
        ];

        $this->layout->admin('admin/ppurio/sms_mem_chart', $data);
    }

    // 매칭 이력서 SMS 발송 내역 차트 페이지 (기업 수신)
    function sms_cor_chart(){
        $sdate = input_get("sdate", "");
        $edate = input_get("edate", "");

        // 날짜 범위 기본값
        if(empty($sdate) || empty($edate)){
            $sdate = date("Y-m-d", strtotime("-13 days"));
            $edate = date("Y-m-d");
        }

        $params = [
            "sdate" => $sdate,
            "edate" => $edate,
        ];

        // 원본 데이터 조회 및 차원별 집계
        $result = $this->site_model->getCorSmsChartRawList($params);
        $chart_data = $this->_buildChartSeries($result, "업종", "cor");

        $data = [
            "search" => [
                "sdate" => $sdate,
                "edate" => $edate,
            ],
            "chart" => $chart_data,
        ];

        $this->layout->admin('admin/ppurio/sms_cor_chart', $data);
    }

    // AJAX 차트 데이터 조회 (개인 수신)
    function sms_mem_chart_data(){
        $sdate = input_get("sdate", "");
        $edate = input_get("edate", "");
        $dimension = input_get("dimension", "업종");

        // 날짜 범위 기본값
        if(empty($sdate) || empty($edate)){
            $sdate = date("Y-m-d", strtotime("-13 days"));
            $edate = date("Y-m-d");
        }

        $params = [
            "sdate" => $sdate,
            "edate" => $edate,
        ];

        // 원본 데이터 조회 및 차원별 집계
        $result = $this->site_model->getMemSmsChartRawList($params);
        $chart_data = $this->_buildChartSeries($result, $dimension, "mem");

        json_return("00", "", $chart_data);
    }

    // AJAX 차트 데이터 조회 (기업 수신)
    function sms_cor_chart_data(){
        $sdate = input_get("sdate", "");
        $edate = input_get("edate", "");
        $dimension = input_get("dimension", "업종");

        // 날짜 범위 기본값
        if(empty($sdate) || empty($edate)){
            $sdate = date("Y-m-d", strtotime("-13 days"));
            $edate = date("Y-m-d");
        }

        $params = [
            "sdate" => $sdate,
            "edate" => $edate,
        ];

        // 원본 데이터 조회 및 차원별 집계
        $result = $this->site_model->getCorSmsChartRawList($params);
        $chart_data = $this->_buildChartSeries($result, $dimension, "cor");

        json_return("00", "", $chart_data);
    }

    // 차트 시리즈 데이터 구성 (비공개 헬퍼)
    // 데이터베이스에 어지럽게 흩어져 있던 회원 정보(연령, 성별 등)를 날짜별로 묶어서 예쁜 차트로 시각화하기 위한 작업
    private function _buildChartSeries($rows, $dimension, $type){
        /**
         *rows 반환 데이터 예시:
        *    [
         *   {sent_dt: "2026-07-01 10:30:00", rc_work: "생산", rc_sage: "25", ...},
        *    {sent_dt: "2026-07-01 14:20:00", rc_work: "건설", rc_sage: "35", ...},
        *    {sent_dt: "2026-07-02 09:15:00", rc_work: "생산", rc_sage: "28", ...},
        *    ...
        *    ]
         * 
         * 
         * 
         */
        // 날짜 범위 추출
        // substr(..., 0, 10) 문자열의 0번째 글자(시작)부터 10글자만 자르라는 뜻 "2026-07-16 14:57:16" 중에서 앞부분의 "2026-07-16"
        // date("Y-m-d"); 오늘 날짜(Year-Month-Day)를 임시 시작 날짜로
        $start_date = isset($rows[0]) ? substr($rows[0]["sent_dt"], 0, 10) : date("Y-m-d");
        $end_date = isset($rows[count($rows)-1]) ? substr($rows[count($rows)-1]["sent_dt"], 0, 10) : date("Y-m-d");
        // 시작일과 종료일 사이에 있는 모든 날짜를 하나씩 채워 넣기
        // 날짜 배열 생성
        $dates = [];
        $current = strtotime($start_date);
        $end = strtotime($end_date);
        while($current <= $end){
            $dates[date("Y-m-d", $current)] = date("m-d", $current); //$dates["2026-07-13"] = "07-13";
            $current += 86400; //하루(86400초) 더하기
        }

        // 차원 컬럼 정의
        $col_map = [
            "mem" => [
                "업종" => "rc_work",
                "연령" => "rc_sage",
                "성별" => "rc_sex",
                "국적" => "rc_nation",
                "근무형태" => "rc_work_type",
                "급여형태" => "rc_price_type",
            ],
            "cor" => [
                "업종" => "ru_work",
                "연령" => "mm_birth",
                "성별" => "mm_sex",
                "국적" => "mm_nation",
                "근무형태" => "ru_work_type",
                "급여형태" => "ru_price_type",
            ]
        ];

        $col = isset($col_map[$type][$dimension]) ? $col_map[$type][$dimension] : "rc_work";

        // 데이터 집계
        $matrix = [];
        $totals = [];

        foreach($rows as $row){
            $date = substr($row["sent_dt"], 0, 10);

            // 차원 값 추출
            $values = [];
            if($dimension === "연령"){
                if($type === "mem"){
                    // rc_sage: 시작연령
                    $age_val = isset($row["rc_sage"]) ? $row["rc_sage"] : 0;
                    $values[] = $age_val > 0 ? (floor($age_val/10)*10) . "대" : "미상";
                } else {
                    // mm_birth: 생년월일 문자열
                    $birth = isset($row["mm_birth"]) ? $row["mm_birth"] : "";
                    $age_decade = getKoreanAge($birth);
                    $values[] = ($age_decade && $age_decade !== "0") ? $age_decade . "대" : "미상";
                }
            } else {
                $raw_value = isset($row[$col]) ? $row[$col] : "";
                if(empty($raw_value)){
                    $values[] = "미상";
                } else {
                    // 쉼표로 분리된 여러 값 처리
                    $exploded = array_map('trim', explode(',', $raw_value));
                    $values = array_filter($exploded, function($v) { return !empty($v); });
                    if(empty($values)){
                        $values[] = "미상";
                    }
                }
            }

            // 각 값에 대해 누적
            foreach($values as $value){
                if(!isset($matrix[$date])){
                    $matrix[$date] = [];
                }
                if(!isset($matrix[$date][$value])){
                    $matrix[$date][$value] = 0;
                }
                $matrix[$date][$value]++;

                if(!isset($totals[$value])){
                    $totals[$value] = 0;
                }
                $totals[$value]++;
            }
        }

        // 합계로 값 정렬
        arsort($totals);
        $sorted_values = array_keys($totals);

        // 팔레트 색상
        $colors = [
            "rgba(255, 99, 132, 1)",    // 빨강
            "rgba(54, 162, 235, 1)",    // 파랑
            "rgba(255, 206, 86, 1)",    // 노랑
            "rgba(75, 192, 192, 1)",    // 초록
            "rgba(153, 102, 255, 1)",   // 보라
            "rgba(255, 159, 64, 1)",    // 주황
            "rgba(199, 199, 199, 1)",   // 회색
            "rgba(83, 102, 255, 1)",    // 남색
        ];

        // 시리즈 구성
        $series = [];
        foreach($sorted_values as $idx => $val){
            $data = [];
            foreach($dates as $date => $label){
                $data[] = isset($matrix[$date][$val]) ? $matrix[$date][$val] : 0;
            }
            $color = $colors[$idx % count($colors)];
            $series[] = [
                "name" => $val,
                "data" => $data,
                "total" => $totals[$val],
                "color" => $color,
            ];
        }

        return [
            "dates" => array_values($dates),
            "series" => $series,
        ];
    }
}
?>
