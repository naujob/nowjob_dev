<?php
class Ppurio extends MY_Controller{
    function __construct() {
        parent::__construct();
        $this->load->model('sitemodel','site_model');
        $this->load->model('membermodel','member_model');
    }

    // 추천인재 구매상품 개인 리스트
    function buy_recom(){
        // 파라미터 설정
        $page = input_get("page", 1);
        $sdate = input_get("sdate", "");
        $edate = input_get("edate", "");
        $search_value = input_get("search_value", "");

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
}
?>
