<?php
defined('BASEPATH') or exit('No direct script access allowed');
class MY_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	private $error_code = [
		'10' => '등록 데이터가 비어있습니다.',
		'11' => '검색 조건이 비어있습니다.',
		'90' => '데이터베이스 처리 오류',
	];

	//등록 처리
	function db_insert($table_name, $arr_data = [])
	{
		if (empty($arr_data)) {
			return $this->get_error("10");
		}

		try {
			if (!$result = $this->db->insert($table_name, $arr_data)) {
				$error = $this->db->error();
				// 에러 코드와 메시지가 모두 있는 경우에만 처리
				if (isset($error["code"]) && $error["code"] != 0) {
					$error_msg = isset($error["message"]) ? $error["message"] : "";
					log_message('error', 'db_insert 실패 - table: ' . $table_name . ', code: ' . $error["code"] . ', message: ' . $error_msg);
					return $this->get_error($error["code"], $error_msg);
				}
				// 에러 정보가 없는 경우 기본 에러 반환
				log_message('error', 'db_insert 실패 (에러 정보 없음) - table: ' . $table_name);
				return $this->get_error("90", "데이터베이스 처리 중 오류가 발생했습니다.");
			}

			$key = $this->db->insert_id();
			return $this->get_success("insert", $key);
		} catch (Exception $e) {
			log_message('error', 'db_insert 예외 발생: ' . $e->getMessage() . ', table=' . $table_name . ', trace: ' . $e->getTraceAsString());
			return $this->get_error("90", "데이터베이스 처리 중 예외가 발생했습니다: " . $e->getMessage());
		}
	}

	//등록 처리
	function db_insert_batch($table_name, $arr_data = [])
	{
		if (empty($arr_data)) {
			return $this->get_error("10");
		}

		if (!$result = $this->db->insert_batch($table_name, $arr_data)) {
			$error = $this->db->error();
			return $this->get_error($error["code"], $error["message"]);
		}
		return $this->get_success("insert", 0);
	}

	//수정 처리
	function db_update($table_name, $arr_data = [], $where = [])
	{
		if (empty($arr_data)) {
			return $this->get_error("10");
		}

		if (empty($where)) {
			return $this->get_error("11");
		}

		if (!$result = $this->db->update($table_name, $arr_data, $where)) {
			$error = $this->db->error();
			return $this->get_error($error["code"], $error["message"]);
		}
		return $this->get_success("update");
	}

	//수정 처리
	function db_update_batch($table_name, $arr_data = [], $colname, $where = [])
	{
		if (empty($arr_data)) {
			return $this->get_error("10");
		}

		if (!$result = $this->db->update_batch($table_name, $arr_data, $colname)) {
			$error = $this->db->error();
			return $this->get_error($error["code"], $error["message"]);
		}
		return $this->get_success("update");
	}

	//수정 처리
	function db_insert_or_update($proc_mode, $table_name, $arr_data = [], $where = [], $key = 0)
	{
		if (empty($arr_data)) {
			return $this->get_error("10");
		}

		if ($proc_mode == "update") {
			$result =  $this->db_update($table_name, $arr_data, $where);
			$resunt["key"] = $key;
			return $result;
		} else {
			return $this->db_insert($table_name, $arr_data);
		}
	}

	//삭제처리
	function db_delete($table_name, $where = [])
	{
		if (empty($where)) {
			return $this->get_error("11");
		}

		if (!$result = $this->db->delete($table_name, $where)) {
			$error = $this->db->error();
			return $this->get_error($error["code"], $error["message"]);
		}
		return $this->get_success("delete");
	}

	//one 데이터 조회
	function db_row($table_name, $where = [])
	{
		$query = $this->db->get_where($table_name, $where, 0, 1);

		if ($table_name == "st_guest") {
			// error_log("\n:::::db_row: ".$query, "3", "/testlog/php_log");
			// error_log("\n:::::table_name: ".$table_name, "3", "/testlog/php_log");
			// error_log("\n:::::where: ".$where, "3", "/testlog/php_log");
		}

		return $query->row_array();
	}

	//one 데이터 조회
	function db_row_colnum($table_name, $col_nm, $where = [])
	{
		$query = $this->db->get_where($table_name, $where, 0, 1);
		$data =  $query->row_array();

		if ($data == null) {
			return null;
		} else {
			return $data[$col_nm];
		}
	}

	function db_count($table_name, $where)
	{
		$this->db->where($where);
		return $this->db->count_all_results($table_name);
	}

	//데이터 존재여부 확인
	function db_is_data($table_name, $where = [])
	{
		$query = $this->db->get_where($table_name, $where, 0, 1);
		$num = $query->num_rows();

		if ($num > 0) {
			return true;
		} else {
			return false;
		}
	}


	//one 데이터 조회 : 복합 조회
	function db_select_row($cols, $body, $where = [], $order_by = "")
	{
		$query_select  = "SELECT ";
		$query_cols  = "";
		$query_body  = "";
		$query_where  = "";
		$query_orderby = "";

		//리턴 컬럼 설정
		foreach ($cols as $key => $col) {
			$query_cols  .= " " . $col . ($key + 1 == count($cols) ? "" : ",") . ($key + 1 == count($cols) ? "" : "\n");
		}

		//바디 설정
		$query_body = " FROM " . $body . "";

		//조건 설정
		$query_where .= " WHERE 1=1 " . "\n";
		foreach ($where as $wh) {
			$query_where  .= " " . ($wh) . ($key + 1 == count($where) ? "" : "\n");
		}

		//정렬
		$query_orderby .= " order by " . $order_by;

		//카운터쿼리 생성
		$sql_data = "
			$query_select
			$query_cols
			$query_body
			$query_where
		";

		$result = $this->db->query($sql_data);
		return $result->row_array();
	}

	//리스트 리턴
	function db_list($table_name, $where, $cols = "*", $orderby = [], $start = 0, $limit = 0)
	{
		$this->db->select($cols);
		$this->db->from($table_name);
		$this->db->where($where);
		foreach ($orderby as $key => $order) {
			$this->db->order_by($key, $order);
		}
		if ($limit != 0) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	//리스트 리턴
	function db_list_where_in($table_name, $where_in, $cols = "*", $orderby = [], $start = 0, $limit = 0)
	{
		$this->db->select($cols);
		$this->db->from($table_name);
		$this->db->where_in($where_in);
		foreach ($orderby as $key => $order) {
			$this->db->order_by($key, $order);
		}
		if ($limit != 0) {
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result_array();
	}


	//리스트 리턴
	function db_select_list($cols = [], $body = "", $where = [], $order_by = "", $limit = 0, $is_print = false)
	{
		//결과 변수
		$db_result = [];

		//생성 변수
		$query_select  = "SELECT ";
		$query_cols  = "";
		$query_body  = "";
		$query_where  = "";
		$query_orderby  = "";

		//리턴 컬럼 설정
		foreach ($cols as $key => $col) {
			$query_cols  .= " " . $col . ($key + 1 == count($cols) ? "" : ",") . ($key + 1 == count($cols) ? "" : "\n");
		}

		//바디 설정
		$query_body = " FROM " . $body . "";

		//조건 설정
		$query_where .= " WHERE 1=1 " . "\n";
		foreach ($where as $wh) {
			$query_where  .= " " . ($wh) . ($key + 1 == count($where) ? "" : "\n");
		}

		//정렬
		$query_orderby .= " order by " . $order_by;

		if ($limit > 0) {
			$query_orderby .= " limit 0, " . $limit;
		}

		//LIMIT

		//리스트 생성
		$sql_list = "
			$query_select
			$query_cols
			$query_body
			$query_where
			$query_orderby
		";
		$result = $this->db->query($sql_list);
		$list = $result->result_array();
		$db_result = $list;

		return $db_result;
	}

	//리스트 리턴
	function db_paging_list($cols = [], $body = "", $where = [], $order_by = "", $page = 1, $limit = 20, $group_by = "", $is_print = false, $is_ajax = false)
	{

		//결과 변수
		$db_result = ["count" => 0, "list" => "", "pagenation" => "", "page" => $page];

		//생성 변수
		$query_select  = "SELECT ";
		$query_count  = " COUNT(*) as cnt ";
		$query_cols  = "";
		$query_body  = "";
		$query_where  = "";
		$query_orderby  = "";
		$query_limit  = "";

		//리턴 컬럼 설정
		foreach ($cols as $key => $col) {
			$query_cols  .= " " . $col . ($key + 1 == count($cols) ? "" : ",") . ($key + 1 == count($cols) ? "" : "\n");
		}

		//바디 설정
		$query_body = " FROM " . $body . "";

		//조건 설정
		$query_where .= " WHERE 1=1 " . "\n";
		foreach ($where as $wh) {
			$query_where  .= " " . ($wh) . ($key + 1 == count($where) ? "" : "\n");
		}

		//정렬
		$query_orderby .= " order by " . $order_by;

		//LIMIT
		$start = (($page - 1) * $limit);
		$query_limit .= " LIMIT $start , $limit ";

		//카운터쿼리 생성
		if ($group_by != "") {
			$sql_count = "
                SELECT count(*) as cnt
                FROM (
        			$query_select
        			$query_count
        			$query_body
        			$query_where
                    $group_by
                ) aa
    		";
		} else {
			$sql_count = "
    			$query_select
    			$query_count
    			$query_body
    			$query_where
    		";
		}


		$result = $this->db->query($sql_count);
		$count = $result->row_array();
		if ($count != null) {
			$db_result["count"] = $count["cnt"];
		}

		//리스트 생성
		$sql_list = "
			$query_select
			$query_cols
			$query_body
			$query_where
            $group_by
			$query_orderby
			$query_limit
		";

		/*
    if(strpos($sql_list,"querylog") > 0){
      error_log("\nquery:".$sql_list."\n", "3", "/testlog/php_log");
    }
    */
		// 데이터 누적 분산 처리 
		if (strpos($sql_list, "querylog") > 0) {
			$yearMonth = date('Y-m');
			$today = date('Y-m-d');
			$now = date('Y-m-d H:i:s');

			$logDir = "/website/nowapk/php_log/$yearMonth";
			$logFile = "$logDir/$today.log";

			if (!is_dir($logDir)) {
				mkdir($logDir, 0755, true);
			}

			//error_log("\nquery($now):\n" . $sql_list . "\n", "3", $logFile);
		}

		//쿼리 로그
		//$db_result["query"] = $sql_list;

		$result = $this->db->query($sql_list);
		$list = $result->result_array();
		$db_result["list"] = $list;

		//페이지 생성
		$db_result["pagenation"] = ($is_ajax == false ? get_page_info($page, $db_result["count"], $limit) : get_page_ajax($page, $db_result["count"], $limit));

		if ($is_print) {
			$this->show_printV2($sql_count, $sql_list);
		}

		return $db_result;
	}

	function inject($str)
	{
		return mysql_real_escape_string($str);
	}

	//쿼리 출력
	function show_print($str_count, $str_list)
	{
		$str_count = str_replace("\n", "<br/>", $str_count);
		$str_list = str_replace("\n", "<br/>", $str_list);
		echo "<div style='background:#eee;border:solid 1px #ddd;font-size:12px;padding:20px;width:100%;margin:20px;'>";
		echo "<h4 style='background:#333;color:#fff;line-height:40px;padding-left:10px;'>카운터 쿼리</h4>";
		echo "<div style=''>$str_count</div>";
		echo "</div>";

		echo "<div style='background:#eee;border:solid 1px #ddd;font-size:12px;padding:20px;width:100%;margin:20px;'>";
		echo "<h4 style='background:#333;color:#fff;line-height:40px;padding-left:10px;'>리스트 쿼리</h4>";
		echo "<div style=''>$str_list</div>";
		echo "</div>";
	}

	//쿼리 출력
	function show_printV2($str_count, $str_list)
	{
		echo $str_count;
		echo "         ";
		echo $str_list;
	}

	//오류메시지 리턴
	function get_error($code, $msg = "")
	{
		if ($msg != "") {
			return ['code' => $code, 'msg' => $msg];
		} else {
			// $this->error_code로 수정 (클래스 변수 참조)
			$error_msg = isset($this->error_code[$code]) ? $this->error_code[$code] : '알 수 없는 오류가 발생했습니다.';
			return ['code' => $code, 'msg' => $error_msg];
		}
	}

	//성공메시지 리턴
	function get_success($type, $primary_key = 0)
	{
		if ($type == "insert") {
			return ['code' => '00', 'msg' => '등록 되었습니다.', 'key' => $primary_key];
		} else if ($type == "update") {
			return ['code' => '00', 'msg' => '수정 되었습니다.', 'key' => ''];
		} else if ($type == "delete") {
			return ['code' => '00', 'msg' => '삭제 되었습니다.', 'key' => ''];
		} else if ($type == "dup") {
			return ['code' => '91', 'msg' => '이미 존재하는 데이터가 있습니다.', 'key' => ''];
		}
	}
}
