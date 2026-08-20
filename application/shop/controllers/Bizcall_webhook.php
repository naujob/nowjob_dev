<?php
class Bizcall_webhook extends CI_Controller
{ // 통화가 끝날 때마다 통신사가 보내주는 통화 시간과 발신/착신 정보를 우리 DB에 안전하게 저장하고, 통신사에 "수신 완료" 신호를 보내는 코드
	function cdr()
	{
		$seq    = input_post("seq", "");
		$sdt    = input_post("sdt", ""); // 통화 시작 시각(YYYYMMDDHHMMSS)
		$vn     = input_post("vn", ""); // 050 가상번호
		$fromn  = input_post("fromn", ""); // 전화를 건 개인회원의 진짜 휴대폰 번호
		$ton    = input_post("ton", ""); // 전화를 받은 회사의 번호
		$memo   = input_post("memo", ""); // 처음에 우리가 넘겨줬던 공고 번호
		$outdur = (int) input_post("outdur", 0); // 기업이 실제로 통화한 시간 (초 단위)

		header('Content-Type: application/json; charset=utf-8');

		if ($seq === "" || $vn === "") {
			echo json_encode(array("rt" => 1, "rs" => "FAIL"));
			exit;
		}

		$callStartAt = null;
		if (preg_match('/^\d{14}$/', $sdt)) {
			$callStartAt = substr($sdt, 0, 4) . '-' . substr($sdt, 4, 2) . '-' . substr($sdt, 6, 2)
				. ' ' . substr($sdt, 8, 2) . ':' . substr($sdt, 10, 2) . ':' . substr($sdt, 12, 2);
		}

		$this->load->model('recruitmodel', 'recruit_model');
		$this->recruit_model->insertBizcallLog(array(
			"v_number"      => $vn,
			"caller_number" => $fromn,
			"callee_number" => $ton,
			"duration"      => $outdur,
			"job_post_id"   => $memo,
			"call_start_at" => $callStartAt,
		));

		echo json_encode(array("rt" => 0, "rs" => "SUCCESS"));
		exit;
	}
}
