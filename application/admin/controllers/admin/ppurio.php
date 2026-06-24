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
        $num = 1;
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        $last_column = end($columns);

        // 셀크기 조절
        $column_widths = [
            'A' => 10,  // 번호
            'B' => 40,  // 인재이름
            'C' => 15,  // 발송일
            'D' => 15,  // 발송대상 기업수
            'E' => 100, // 발송대상 기업명
            'F' => 20,  // 로그등록일시
            'G' => 15,  // 프로모션IDX
        ];

        foreach ($column_widths as $column => $width) {
            $this->excel->getActiveSheet()->getColumnDimension($column)->setWidth($width);
        }

        // 헤더 설정
        $this->excel->getActiveSheet()->setCellValue('A' . $num, "번호");
        $this->excel->getActiveSheet()->setCellValue('B' . $num, "인재이름");
        $this->excel->getActiveSheet()->setCellValue('C' . $num, "발송일");
        $this->excel->getActiveSheet()->setCellValue('D' . $num, "발송대상 기업수");
        $this->excel->getActiveSheet()->setCellValue('E' . $num, "발송대상 기업명");
        $this->excel->getActiveSheet()->setCellValue('F' . $num, "로그등록일시");
        $this->excel->getActiveSheet()->setCellValue('G' . $num, "프로모션IDX");

        // 헤더 스타일
        $this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFEEEEEE');
        $this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle($columns[0] . ($num) . ':' . $columns[count($columns)-1] . ($num))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $num++;

        // 데이터 행
        if (count($result["list"]) > 0) {
            $no = 0;
            foreach ($result["list"] as $row) {
                // 기업명 JSON 디코딩
                $comp_names_str = '';
                if (isset($row['comp_names']) && !empty($row['comp_names'])) {
                    $comp_names_arr = json_decode($row['comp_names'], true);
                    if (is_array($comp_names_arr)) {
                        $comp_names_str = implode(', ', $comp_names_arr);
                    }
                }

                $tableData = array_combine(
                    array_values($columns),
                    [
                        ($num - 1),
                        isset($row['ru_name']) ? $row['ru_name'] : '',
                        isset($row['send_date']) ? $row['send_date'] : '',
                        isset($row['comp_count']) ? $row['comp_count'] : 0,
                        $comp_names_str,
                        isset($row['reg_dt']) ? substr($row['reg_dt'], 0, 19) : '',
                        isset($row['pm_idx']) ? $row['pm_idx'] : '',
                    ]
                );
                foreach ($tableData as $k => $v) {
                    $this->excel->getActiveSheet()->setCellValue($k . ($num), $v);
                    $this->excel->getActiveSheet()->getStyle('A'.$num.':' . $columns[count($columns)-1] .$num)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $this->excel->getActiveSheet()->getStyle('A'.$num.':' . $columns[count($columns)-1] .$num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $this->excel->getActiveSheet()->getStyle('A'.$num.':' . $columns[count($columns)-1] .$num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                }
                $num++;
            }
        }

        // 엑셀 저장
        $this->excel->setFooter($file_name);
    }
}
?>
