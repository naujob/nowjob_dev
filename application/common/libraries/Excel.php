<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  =======================================
 *  Author     : Muhammad Surya Ikhsanudin
 *  License    : Protected
 *  Email      : mutofiyah@gmail.com
 *
 *  Dilarang merubah, mengganti dan mendistribusikan
 *  ulang tanpa sepengetahuan Author
 *  =======================================
*/
require_once getcwd() . "/application" ."/common/third_party/PHPExcel.php";

/**
 * Class Excel
 */
class Excel extends PHPExcel
{
    /**
     * Excel constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 파일명 생성
     * @param $string
     * @param bool $excel_date_bool
     * @return false|string
     */
    function getFilename($string, $excel_date_bool = true)
    {
        $excel_date = date('Ymd_Hms');
        $file_name = iconv("UTF-8", 'EUC-KR', $string . '_' . $excel_date);

        if (!$excel_date_bool) {
            $file_name = iconv("UTF-8", 'EUC-KR', $string);
        }

        return $file_name;
    }

    /**
     * 엑셀 출력용 헤더 부분 설정
     */
    function setHeader()
    {
//        @set_time_limit(-1);
//        ini_set('memory_limit', '-1');

        $this->excel->setActiveSheetIndex(0);
    }

    /**
     * 엑셀 출력용 푸터 부분 설정
     * @param $file_name
     * @throws PHPExcel_Reader_Exception
     * @throws PHPExcel_Writer_Exception
     */
    function setFooter($file_name)
    {
        $filename = $file_name . '.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel5');
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }

}
