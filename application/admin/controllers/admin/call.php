<?php
    class Call extends MY_Controller{
		function __construct() {
            parent::__construct();

			// 모델 로드
			$this->load->model('recruitmodel','recruit_model');
			$this->load->library('logger');
	    }

		// 전화걸기/지원하기 로그 - 기업별 집계 목록
        function call_log_list(){
			// 파라미터 설정
			$page         = input_get("page", 1);
			$search_value = input_get("search_value", "");
			$cl_type      = input_get("cl_type", "ALL");

			// 데이터 조회
			$params = array(
				"pagesize"     => 20,
				"page"         => $page,
				"search_value" => $search_value,
				"cl_type"      => $cl_type,
			);

			$result = $this->recruit_model->getCallLogSummaryList($params);

			// 리턴 설정
			$data = array(
				"search"       => array(
					"search_value" => $search_value,
					"cl_type"      => $cl_type,
				),
				"start"        => ($result["count"] - (($page - 1) * $params["pagesize"])),
				"result_list"  => $result["list"],
				"result_count" => $result["count"],
				"pagenation"   => $result["pagenation"],
			);

			$this->layout->admin('admin/call/call_log_list', $data);
        }

		// 전화걸기 로그 삭제
		function call_log_delete_proc(){
			$rc_idx = input_post("rc_idx", "");

			if ($rc_idx == "") {
				json_return("99", "잘못된 요청입니다.");
				return;
			}

			$result = $this->recruit_model->deleteCallLog($rc_idx);

			if ($result) {
				json_return("00", "삭제되었습니다.");
			} else {
				json_return("99", "삭제 중 오류가 발생했습니다.");
			}
		}
    }
?>
