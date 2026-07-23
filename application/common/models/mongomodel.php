<?php
class mongoModel extends MY_Model
{
	function __construct()
	{
		parent::__construct();
	}

	# 몽고DB 연동처리 : 회원가입 , 로그인
	function insertUserInfo($mem_info, $token)
	{
		$mongo_user = get_mongodb("user");
		$chk_id = $mongo_user->findOne(['user_id' => $mem_info["mm_id"]]);
		if ($chk_id == null) {
			$user_idx = key_mongodb("user");
			$mongo_user->insert([
				'user_idx' => "" . $user_idx,
				'user_id' => $mem_info["mm_id"],
				'user_pwd' => $mem_info["mm_pwd"],
				'user_nick' => $mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"],
				'user_img' => $mem_info["mm_thumb"],
				'user_desc' => "",
				'user_status' => "Y",
				'socket_id' => "",
				'alarm_chat' => $mem_info["alarm_chat"],
				'alarm_resume' => $mem_info["alarm_resume"],
				'alarm_fav' => $mem_info["alarm_fav"],
				'alarm_adv' => $mem_info["alarm_adv"],
				'alarm_deny_yn' => $mem_info["alarm_deny_yn"],
				'alarm_deny_yoil' => $mem_info["alarm_deny_yoil"],
				'alarm_deny_stime' => $mem_info["alarm_deny_stime"],
				'alarm_deny_etime' => $mem_info["alarm_deny_etime"],
				'token' => $token
			]);
		} else {
			$mongo_user->update(
				['user_id' => $mem_info["mm_id"]],
				[
					'$set' =>
					[
						'user_pwd' => $mem_info["mm_pwd"],
						'user_nick' => $mem_info["mm_type"] == "COMP" ? $mem_info["mm_comp_name"] : $mem_info["mm_name"],
						'user_img' => $mem_info["mm_thumb"],
						'alarm_chat' => $mem_info["alarm_chat"],
						'alarm_resume' => $mem_info["alarm_resume"],
						'alarm_fav' => $mem_info["alarm_fav"],
						'alarm_adv' => $mem_info["alarm_adv"],
						'alarm_deny_yn' => $mem_info["alarm_deny_yn"],
						'alarm_deny_yoil' => $mem_info["alarm_deny_yoil"],
						'alarm_deny_stime' => $mem_info["alarm_deny_stime"],
						'alarm_deny_etime' => $mem_info["alarm_deny_etime"],
						'token' => $token
					]
				]
			);
		}
	}

	function isUserData($mm_id)
	{
		$mongo_user = get_mongodb("user");
		$chk_id = $mongo_user->findOne(['user_id' => $mm_id]);
		return $check_id;
	}

	# 정보 갱신 : user_id 필수
	function updateUserInfo($mem_info)
	{
		$mongo_user = get_mongodb("user");
		$chk_id = $mongo_user->findOne(['user_id' => $mem_info["mm_id"]]);

		// MongoDB 업데이트 로그 추가
		//$log_message = "[" . date('Y-m-d H:i:s') . "] MongoDB updateUserInfo - 사용자ID: " . $mem_info["mm_id"] . ", 사용자 존재: " . ($chk_id ? "있음" : "없음") . "\n";
		//file_put_contents('/tmp/mongo_update.log', $log_message, FILE_APPEND | LOCK_EX);

		if ($chk_id != null) {
			// mm_id 필드를 제외하고 업데이트
			$update_data = $mem_info;
			unset($update_data['mm_id']); // mm_id 필드 제거

			//$log_message = "[" . date('Y-m-d H:i:s') . "] MongoDB 업데이트 실행 - 데이터: " . json_encode($update_data) . "\n";
			//file_put_contents('/tmp/mongo_update.log', $log_message, FILE_APPEND | LOCK_EX);

			$mongo_user->update(
				['user_id' => $mem_info["mm_id"]],
				[
					'$set' =>
					$update_data
				]
			);

			//$log_message = "[" . date('Y-m-d H:i:s') . "] MongoDB 업데이트 완료\n";
			//file_put_contents('/tmp/mongo_update.log', $log_message, FILE_APPEND | LOCK_EX);
			return true;
		} else {
			//$log_message = "[" . date('Y-m-d H:i:s') . "] MongoDB 업데이트 실패 - 사용자 없음\n";
			//file_put_contents('/tmp/mongo_update.log', $log_message, FILE_APPEND | LOCK_EX);
			return false;
		}
	}
}
