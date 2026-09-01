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

		// 웹훅 요청 수신 로그
		if (function_exists('log_message')) {
			log_message('info', '[BIZCALL][CDR] 웹훅 요청 수신 - seq=' . $seq . ', vn=' . $vn . ', sdt=' . $sdt . ', fromn=' . $fromn . ', ton=' . $ton . ', memo=' . $memo . ', outdur=' . $outdur);
		}

		if ($seq === "" || $vn === "") {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][CDR] 필수 파라미터 누락 - seq=' . $seq . ', vn=' . $vn);
			}
			echo json_encode(array("rt" => 1, "rs" => "FAIL"));
			exit;
		}

		$this->load->model('membermodel', 'member_model');
		$log_data = array(
			"v_number"      => $vn,
			"caller_number" => $fromn,
			"callee_number" => $ton,
			"duration"      => $outdur,
			"job_post_id"   => $memo,
			"call_start_at" => $sdt,
		);

		$insert_result = $this->member_model->insertBizcallLog($log_data);
		if (function_exists('log_message')) {
			log_message('info', '[BIZCALL][CDR] 통화기록 저장 완료 - vn=' . $vn . ', result=' . ($insert_result ? 'success' : 'fail'));
		}

		// 통화 발생 → 만료시간 연장 (실패해도 웹훅 응답은 항상 SUCCESS로 반환해 델피콤 재전송 방지)
		$cfg = config_item('bizcall');
		$expireHours = isset($cfg['expire_hours']) ? (int)$cfg['expire_hours'] : 2160;

		if (function_exists('log_message')) {
			log_message('info', '[BIZCALL][CDR] 만료시간 연장 시도 - vn=' . $vn . ', expire_hours=' . $expireHours);
		}

		require_once(dirname(APPPATH) . '/common/libraries/BizCallClient.php');
		$bizcall = new CI_BizCallClient();
		$extendRes = $bizcall->extendVirtualNumberExpire($vn, $expireHours);

		if ($extendRes['ok']) {
			if (function_exists('log_message')) {
				log_message('info', '[BIZCALL][CDR] 만료시간 연장 성공 - vn=' . $vn);
			}
			$this->member_model->renewVirtualNumberExpire($vn, $expireHours);
			if (function_exists('log_message')) {
				log_message('info', '[BIZCALL][CDR] 로컬 DB expire_at 갱신 완료 - vn=' . $vn);
			}
		} else {
			if (function_exists('log_message')) {
				log_message('error', '[BIZCALL][CDR] 만료시간 연장 실패 - vn=' . $vn . ', error=' . $extendRes['error'] . ', response=' . json_encode($extendRes['response']));
			}
		}

		if (function_exists('log_message')) {
			log_message('info', '[BIZCALL][CDR] 웹훅 응답 - rt=0, rs=SUCCESS');
		}

		echo json_encode(array("rt" => 0, "rs" => "SUCCESS"));
		exit;
	}
}
